<?php

/**
 * This is the model class for table "user_social_log".
 *
 * The followings are the available columns in table 'user_social_log':
 * @property integer $usl_id
 * @property integer $usl_user_ref_id
 * @property integer $usl_user_type
 * @property integer $usl_user_id
 * @property string $usl_desc
 * @property integer $usl_event_id
 * @property string $usl_created
 * @property integer $usl_active
 */
class UserSocialLog extends CActiveRecord
{
	const Consumers = 1;
	const Vendor = 2;
	const Driver = 3;
	const Admin = 4;
	const Agent = 5;
	const System = 10;
	const USER_SOCIAL_UNLINK=1;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_social_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('', 'required'),
			array(' usl_user_ref_id, usl_user_type, usl_user_id, usl_event_id, usl_active', 'numerical', 'integerOnly'=>true),
			array('usl_desc', 'length', 'max'=>5000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('usl_id, usl_user_ref_id, usl_user_type, usl_user_id, usl_desc, usl_event_id, usl_created, usl_active', 'safe', 'on'=>'search'),
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
			'uslUserid' => array(self::BELONGS_TO, 'User', 'usl_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'usl_id' => 'Usl',
			'usl_user_ref_id' => 'Usl User Ref',
			'usl_user_type' => 'Usl User Type',
			'usl_user_id' => 'Usl User',
			'usl_desc' => 'Usl Desc',
			'usl_event_id' => 'Usl Event',
			'usl_created' => 'Usl Created',
			'usl_active' => 'Usl Active',
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
		$criteria->compare('usl_id',$this->usl_id);
		$criteria->compare('usl_user_ref_id',$this->usl_user_ref_id);
		$criteria->compare('usl_user_type',$this->usl_user_type);
		$criteria->compare('usl_user_id',$this->usl_user_id);
		$criteria->compare('usl_desc',$this->usl_desc,true);
		$criteria->compare('usl_event_id',$this->usl_event_id);
		$criteria->compare('usl_created',$this->usl_created,true);
		$criteria->compare('usl_active',$this->usl_active);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserSocialLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function createLog($userId, $desc, UserInfo $userInfo = null, $event_id, $oldModel = false, $params = false)
	{
		/* @var $venLog VendorsLog  */
		$userSocialLog = new UserSocialLog();
		$userSocialLog->usl_user_id = $userId;
		$userSocialLog->usl_desc = $desc;
		$userSocialLog->usl_user_type = $userInfo->userType;
		$userSocialLog->usl_user_ref_id = $userInfo->userId;
		$userSocialLog->usl_event_id = $event_id;
		if ($oldModel)
		{
			$userSocialLog->usl_active = $oldModel->usl_active;
		}
		if ($params['usl_created'] != ''):
			$contactLog->usl_created = $params['usl_created'];
		endif;
		if ($params['usl_active'] != ''):
			$contactLog->usl_active = $params['usl_active'];
		endif;
		if ($userSocialLog->validate())
		{
			$userSocialLog->save();
		}
		else
		{
			echo json_encode($userSocialLog->getErrors());
			exit();
			echo "Error in Log";
			exit;
		}
	}

	public function eventList()
	{
		$eventlist = [
			1 => 'User Social Unlink',
		];
		asort($eventlist);
		return $eventlist;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->eventList();
		return $list[$eventId];
	}
	
}
