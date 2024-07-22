<?php

/**
 * This is the model class for table "users_source_tracking".
 *
 * The followings are the available columns in table 'users_source_tracking':
 * @property integer $ust_id
 * @property integer $ust_user_id
 * @property string $ust_tracking_id
 * @property string $ust_user_phone
 * @property string $ust_user_email
 * @property string $ust_source
 * @property string $ust_medium
 * @property string $ust_ip
 * @property string $ust_campaign_id
 * @property string $ust_group_id
 * @property string $ust_keyword
 */
class UsersSourceTracking extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_source_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            
          
			array('ust_user_id', 'numerical', 'integerOnly'=>true),
			array('ust_tracking_id, ust_keyword', 'length', 'max'=>255),
			array('ust_user_phone, ust_user_email, ust_source,ust_medium, ust_ip', 'length', 'max'=>50),
			array('ust_campaign_id, ust_group_id', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ust_id, ust_user_id, ust_tracking_id, ust_user_phone, ust_user_email, ust_source,ust_medium, ust_ip, ust_campaign_id, ust_group_id, ust_keyword,ust_create_date', 'safe', 'on'=>'search'),
            array('ust_create_date', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ust_id' => 'Ust',
			'ust_user_id' => 'Ust User',
			'ust_tracking_id' => 'Ust Session',
			'ust_user_phone' => 'Ust User Phone',
			'ust_user_email' => 'Ust User Email',
			'ust_source' => 'source',
            'ust_medium' => 'medium',
			'ust_ip' => 'Ust Ip',
			'ust_campaign_id' => 'Ust Campaign',
			'ust_group_id' => 'Ust Group',
			'ust_keyword' => 'Ust Keyword',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ust_id',$this->ust_id);
		$criteria->compare('ust_user_id',$this->ust_user_id);
		$criteria->compare('ust_tracking_id',$this->ust_tracking_id,true);
		$criteria->compare('ust_user_phone',$this->ust_user_phone,true);
		$criteria->compare('ust_user_email',$this->ust_user_email,true);
		$criteria->compare('ust_source',$this->ust_source,true);
        $criteria->compare('ust_medium',$this->ust_medium,true);
		$criteria->compare('ust_ip',$this->ust_ip,true);
		$criteria->compare('ust_campaign_id',$this->ust_campaign_id,true);
		$criteria->compare('ust_group_id',$this->ust_group_id,true);
		$criteria->compare('ust_keyword',$this->ust_keyword,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersSourceTracking the static model class
	 */
	public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function add()
    {
        $param = Filter::parseTrackingParams();
        if($param['source']=="")
        {
            goto skip;
        }
        $model = new UsersSourceTracking();
        if ($ustID = $model->getByParams($param))
        {
            $model = self::model()->findByPk($ustID);
        }
        if (UserInfo::isLoggedIn())
        {
            $userID             = UserInfo::getUserId();
            $model->ust_user_id = $userID;
            $cttID              = ContactProfile::getByUserId($userID);

            $email                 = ContactEmail::getByContactId($cttID);
            $model->ust_user_email = $email;

            $phone                 = ContactPhone::getContactNumber($cttID);
            $model->ust_user_phone = $phone;
        }
        $model->ust_source      = $param['source'];
        $model->ust_medium      = $param['medium'];
        $model->ust_campaign_id = $param['campaign'];
        $model->ust_group_id    = $param['content'];
        $model->ust_keyword     = $param['term'];
        $model->ust_ip          = Filter::getUserIP();
        $model->ust_tracking_id = Yii::app()->request->cookies['tkrid']->value; //Yii::app()->getSession()->getSessionId();
        $model->ust_referal_url = $_SERVER['HTTP_REFERER'];
        $model->ust_create_date = new CDbExpression("NOW()");
        if (!$model->save())
        {
            return false;
        }
        skip:
        return true;
    }
    public function getByParams($param)
    {
        $source    = "";
        $ustID="";
        $trackID = Yii::app()->request->cookies['tkrid']->value;
//        if ($param['source'] != '' && $param['medium'] != '')
//        {
//            $source = "source : " . $param['source'] . " medium : " . $param['medium'];
//        }
        if ($trackID)
        {
            $where = 'AND ust_tracking_id = "' . $trackID . '"';
            if ($param['campaign'])
            {
                $where .= 'AND ust_campaign_id = "' . $param['campaign'] . '"';
            }
            if ($param['source'])
            {
                $where .= 'AND ust_source = "' . $param['source'] . '"';
            }
            if ($param['medium'])
            {
                $where .= 'AND ust_medium = "' . $param['medium'] . '"';
            }
            if (UserInfo::isLoggedIn())
            {
                $userID = UserInfo::getUserId();
                $where .= ' AND (ust_user_id = "' . $userID . '"  OR  ust_user_id IS NULL)';
              
            }
            $sql   = "SELECT * FROM  users_source_tracking WHERE 1 $where ";
            $ustRow = DBUtil::queryRow($sql);
            $ustID = $ustRow['ust_id'];
            
//            if (UserInfo::isLoggedIn() && $ustID)
//            {
//                $userID = UserInfo::getUserId();
//                $sql1       = "select ust_id FROM  users_source_tracking WHERE ust_tracking_id =  $trackID  AND  (ust_user_id =  $userID OR ust_user_id IS NOT NULL)  ";
//                $ustID = DBUtil::queryScalar($sql1);
//            } 
//echo $ustID;
            return $ustID;
        }
        return false;
    }
    public function getUstByContact($phone, $email)
    {
       $ustID   = 0;
//        $trackID = Yii::app()->request->cookies['tkrid']->value;
//        $sql     = "SELECT ust_id FROM `users_source_tracking` WHERE `ust_tracking_id` = '" . $trackID . "'";
//        $ustID   = DBUtil::queryScalar($sql);
//        if ($ustID)
//        {
//            goto lastReturn;
//        }
       
        $sql   = "SELECT ust_id FROM `users_source_tracking` WHERE `ust_user_phone` = '" . $phone . "'";
        $ustID = DBUtil::queryScalar($sql);
        if ($ustID)
        {
            goto lastReturn;
        }
        $sql   = "SELECT ust_id FROM `users_source_tracking` WHERE `ust_user_email` = '" . $email . "'";
        $ustID = DBUtil::queryScalar($sql);
         if ($ustID)
        {
            goto lastReturn;
        }
        lastReturn:
        return $ustID;
    }
   
    

}
