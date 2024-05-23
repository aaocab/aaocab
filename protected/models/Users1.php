<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $user_id
 * @property string $usr_name
 * @property string $usr_email
 * @property string $usr_password
 * @property string $usr_verification_code
 * @property integer $usr_mobile_verify
 * @property string $usr_mobile_verify_date
 * @property string $usr_city
 * @property string $usr_ip
 * @property integer $usr_gender
 * @property string $usr_profile_pic
 * @property string $usr_profile_pic_path
 * @property string $usr_last_login
 * @property string $usr_created_at
 * @property string $usr_device
 * @property string $usr_modified
 * @property integer $usr_active
 *
 * The followings are the available model relations:
 * @property UserLog[] $userLogs
 */
class Users1 extends CActiveRecord
{

	public $repeat_password;
	public $new_password;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usr_name, usr_email, new_password', 'required', 'on' => 'insert'),
			array('usr_mobile_verify, usr_gender, usr_active', 'numerical', 'integerOnly' => true),
			array('usr_name, usr_email, usr_password, usr_city, usr_ip, usr_profile_pic, usr_device', 'length', 'max' => 255),
			array('usr_verification_code', 'length', 'max' => 100),
			array('usr_profile_pic_path', 'length', 'max' => 500),
			array('usr_mobile_verify_date, usr_last_login, usr_modified', 'safe'),
			array('usr_email', 'isExist', 'on' => 'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, usr_name, usr_email, usr_password, usr_verification_code, usr_mobile_verify, usr_mobile_verify_date, usr_city, usr_ip, usr_gender, usr_profile_pic, usr_profile_pic_path, usr_last_login, usr_created_at, usr_device, usr_modified, usr_active', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function isExist($attribute, $params)
	{

		if (!$this->isNewRecord)
		{
			$scope = "excludeMe";
		}
		$model = $this->findByEmail($this->usr_email);
		if ($model)
		{
			$this->addError($attribute, "Email already registered.");
			return FALSE;
		}
		else
		{
			return true;
		}

		return true;
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'userLogs' => array(self::HAS_MANY, 'UserLog', 'log_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id'				 => 'User',
			'usr_name'				 => 'Usr Name',
			'usr_email'				 => 'Usr Email',
			'usr_password'			 => 'Usr Password',
			'usr_verification_code'	 => 'Usr Verification Code',
			'usr_mobile_verify'		 => 'Usr Mobile Verify',
			'usr_mobile_verify_date' => 'Usr Mobile Verify Date',
			'usr_city'				 => 'Usr City',
			'usr_ip'				 => 'Usr Ip',
			'usr_gender'			 => 'Usr Gender',
			'usr_profile_pic'		 => 'Usr Profile Pic',
			'usr_profile_pic_path'	 => 'Usr Profile Pic Path',
			'usr_last_login'		 => 'Usr Last Login',
			'usr_created_at'		 => 'Usr Created At',
			'usr_device'			 => 'Usr Device',
			'usr_modified'			 => 'Usr Modified',
			'usr_active'			 => 'Usr Active',
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

		$criteria = new CDbCriteria;

		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('usr_name', $this->usr_name, true);
		$criteria->compare('usr_email', $this->usr_email, true);
		$criteria->compare('usr_password', $this->usr_password, true);
		$criteria->compare('usr_verification_code', $this->usr_verification_code, true);
		$criteria->compare('usr_mobile_verify', $this->usr_mobile_verify);
		$criteria->compare('usr_mobile_verify_date', $this->usr_mobile_verify_date, true);
		$criteria->compare('usr_city', $this->usr_city, true);
		$criteria->compare('usr_ip', $this->usr_ip, true);
		$criteria->compare('usr_gender', $this->usr_gender);
		$criteria->compare('usr_profile_pic', $this->usr_profile_pic, true);
		$criteria->compare('usr_profile_pic_path', $this->usr_profile_pic_path, true);
		$criteria->compare('usr_last_login', $this->usr_last_login, true);
		$criteria->compare('usr_created_at', $this->usr_created_at, true);
		$criteria->compare('usr_device', $this->usr_device, true);
		$criteria->compare('usr_modified', $this->usr_modified, true);
		$criteria->compare('usr_active', $this->usr_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function search1($qry)
	{
		$query = '';
		if ($qry['searchname'] != '')
		{
			$query1 = " AND `usr_name` LIKE '%" . $qry['searchname'] . "%' ";
		}
		if ($qry['searchemail'] != '')
		{
			$query2 = " AND usr_email LIKE '%" . $qry['searchemail'] . "%' ";
		}
		if ($qry['searchphone'] != '')
		{
			$query3 = " mob LIKE '%" . $qry['searchphone'] . "%' ";
		}
		if ($qry['locals'] != '')
		{
			if ($qry['locals'] == 1)
			{
				$query4 = " AND (usr_city NOT IN ('Kolkata','Delhi') OR (usr_city IS NULL)   )";
			}
		}
		$query	 = "us.usr_active <> 0 " . $query1 . $query2 . $query4;
		$cdb	 = Yii::app()->db->createCommand()
				->select('*,us.user_id as userid,ul.log_in_time as last_login,us.usr_email as useremail')
				->from('users us')
				->leftjoin('user_log ul', 'ul.log_user=us.user_id')
				//->leftjoin('imp_profile_completeness pc', 'us.user_id=pc.user_id')
				->where($query)
				->group('us.user_id')
				->order('us.user_id DESC')
				->having($query3);
		$Search	 = $cdb->queryAll();
		return $Search;
	}

	public function findByEmail($email)
	{
		return self::model()->findByAttributes(array('usr_email' => $email));
	}

	public function getNameById($userID)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = 'usr_name'; // select fields which you want in output
		$criteria->compare('user_id', $userID);
		return $this->find($criteria);
	}

}
