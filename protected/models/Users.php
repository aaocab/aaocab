<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $user_id
 * @property integer $usr_contact_id
 * @property string $usr_name
 * @property string $usr_lname
 * @property string $usr_email
 * @property string $usr_password
 * @property string $usr_verification_code
 * @property string $usr_country_code
 * @property string $usr_mobile
 * @property integer $usr_mobile_verify
 * @property string $usr_mobile_verify_date
 * @property integer $usr_email_verify
 * @property string $usr_email_verify_date
 * @property string $usr_activation_key
 * @property integer $usr_country
 * @property integer $usr_state
 * @property string $usr_city
 * @property string $usr_zip
 * @property string $profile->usr_address1
 * @property string $usr_address2
 * @property string $usr_address3
 * @property string $usr_ip
 * @property integer $usr_gender
 * @property string $usr_profile_pic
 * @property string $usr_profile_pic_path
 * @property string $usr_last_login
 * @property string $usr_created_at
 * @property integer $usr_create_platform
 * @property string $usr_device
 * @property string $usr_modified
 * @property string usr_state_text
 * @property integer $usr_active
 * @property integer $usr_overall_rating
 * @property string $usr_last_trip_datetime
 * @property integer $usr_last_trip_source_city
 * @property integer $usr_last_trip_destination_city
 * @property integer $usr_last_trip_amount
 * @property integer $usr_total_trips
 * @property integer $usr_gozo_kms
 * @property integer $usr_acct_type
 * @property integer $usr_total_amount
 * @property string $usr_preferences
 * @property string $usr_attributes
 * @property integer $usr_corporate_id
 * @property integer $usr_acct_verify
 * @property integer $usr_changepassword
 * The followings are the available model relations:
 * @property UserLog[] $userLogs
 * @property UserPlaces[] $userPlaces
 * @property String $usr_referred_code
 * @property integer $usr_referred_id
 * @property String $usr_refer_code
 * @property String $usr_sos
 * @property Contact $usrContact
 */
class Users extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	public $repeat_password, $new_password, $old_password, $user_email, $usr_log_count, $isNew, $usr_mark_driver_count, $referral_code, $email, $search, $category, $last_booking_create_date1, $last_booking_create_date2;

	const Bonus_Coins				 = 1;
	const Bonus_Wallet			 = 2;
	const Bonus_Promo				 = 3;
	const Platform_Web			 = 1;
	const Platform_Admin			 = 2;
	const Platform_App			 = 3;
	const AcctType_Verify			 = 0;
	const AcctType_WebNonVerify	 = 1;
	const AcctType_MobNonVerify	 = 2;

	public $genderList			 = ['0' => 'All', '1' => 'Male', '2' => 'Female'];
	public $reverseGenderList	 = ['male' => '1', 'female' => '2'];
	public $usr_create_platform	 = [1 => 'Web', 2 => 'Admin', 3 => 'App'];
	public static $loginWith	 = [1 => "Email", 2 => "Phone"];
	public $_identity;
	public $blg_desc, $bkg_booking_id, $from_city_name, $to_city_name, $blg_remark_type, $bkg_pickup_date, $blg_created, $usr_reset_desc;
	public $search_name, $search_email, $search_phone, $search_marked_bad, $gender, $promo_id, $fullContactNumber, $verifyCode, $username;
	public $usernameType;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usr_name, usr_lname, usr_email, usr_password, repeat_password', 'required', 'on' => 'insert'),
			array('usr_contact_id, usr_password', 'required', 'on' => 'new'),
			array('username', 'validateUserName', 'on' => 'userLogin'),
			array('user_id, usr_deactivate_reason', 'required', 'on' => 'deactivate'),
			array('usr_name, usr_lname, usr_email, usr_password, usr_mobile', 'required', 'on' => 'userSignup'),
			array('usr_mobile', 'validatePhoneNumber', 'on' => 'userSignup'),
			array('usr_mobile', 'unique', 'criteria' => array('condition' => 'usr_active = 1'), 'on' => 'userSignup'),
			array('usr_email', 'required', 'on' => 'inserttemplogin'),
			array('user_email', 'required', 'on' => 'forgotpass'),
			array('usr_email', 'email', 'message' => 'Please enter valid email address', 'on' => 'userSignup', 'checkMX' => true, "except" => "user_sync"),
			array('usr_email,user_email', 'email', 'message' => 'Please enter valid email address', 'checkMX' => true, "except" => "user_sync"),
			array('usr_email', 'unique', 'on' => 'insert,insertonbooking,mobinsert,inserttemplogin'),
			array('username', 'required', 'on' => 'userLoginEmailPhone', "message" => "Please enter valid user phone/email address"),
			array('username', 'validateEmailPhone', 'on' => 'userLoginEmailPhone'),
			array('usr_name, usr_lname, usr_email, usr_password, usr_mobile', 'required', 'on' => 'mobinsert'),
			array('usr_name, usr_lname, usr_email', 'required', 'on' => 'insertonbooking'),
			array('usr_mobile', 'required', 'on' => 'insertQrAgent'),
			['usr_name, usr_lname, usr_email, usr_password,usr_mobile', 'required', 'on' => 'agentjoin'],
			['usr_name,usr_password,usr_mobile', 'required', 'on' => 'agentQrjoin'],
			array('repeat_password', 'compare', 'compareAttribute' => 'usr_password', 'on' => ' recover', 'message' => "Passwords don't match"),
			array('repeat_password', 'compare', 'compareAttribute' => 'new_password', 'on' => 'insert', 'message' => "Passwords don't match"),
			array('repeat_password, new_password, old_password', 'required', 'on' => 'change'),
			array('new_password, old_password', 'required', 'on' => 'changepass'),
			['usr_refer_code', 'unique', 'on' => 'refcode'],
			['usr_mobile', 'unique', 'on' => 'insert'],
			array('usr_mobile', 'numerical', 'integerOnly' => true, 'on' => 'insert,linkusers'),
			array('usr_name', 'required', 'on' => 'update'),
			array('usr_email,usr_mobile, usr_gender,usr_address1', 'required', 'on' => 'updateProfile'),
			array('usr_password', 'checkLogin', 'on' => 'login'),
			array('usr_mobile', 'validatePhoneNumber', 'on' => 'linkusers'),
			array('repeat_password', 'compare', 'compareAttribute' => 'new_password', 'on' => 'change', 'message' => "New And Repeat Passwords Do Not Match!"),
			//  array('usr_name, usr_email, usr_password, usr_mobile', 'required'),
			array('usr_mobile_verify, usr_email_verify, usr_create_platform, usr_gender, usr_active', 'numerical', 'integerOnly' => true),
			array('usr_name, usr_email, usr_password, usr_city, usr_address1, usr_address2, usr_address3, usr_ip, usr_profile_pic, usr_device', 'length', 'max' => 255),
			array('usr_verification_code', 'length', 'max' => 100),
			array('usr_mobile, usr_zip', 'length', 'max' => 15),
			array('usr_activation_key', 'length', 'max' => 200),
			array('usr_profile_pic_path', 'length', 'max' => 500),
			array('usr_mobile_verify_date, usr_email_verify_date, usr_last_login, usr_modified,usr_log_count', 'safe'),
			array('usr_name, user_email, usr_city, usr_address1, usr_address2, usr_address3', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
			array('usr_reset_desc', 'required', 'on' => 'reset', 'message' => 'Please enter the reason for resetting bad mark'),
			array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'captchaAction' => 'site/captcha', 'on' => 'captchaRequired'),
			array('usr_name, usr_email, usr_password, repeat_password', 'required', 'on' => 'sociallinkinsert'),
			//array('usr_name', 'CRegularExpressionValidator', 'pattern' => '/^[a-zA-Z0-9 .]*$/', 'message' => "First Name should contain only alphanumeric characters", 'allowEmpty' => false, 'except' => "user_sync"),
			//array('usr_lname', 'CRegularExpressionValidator', 'pattern' => '/^[a-zA-Z0-9 .]*$/', 'message' => "Last Name should contain only alphanumeric characters", 'allowEmpty' => false, 'except' => "user_sync"),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('username,usr_refer_code,usr_referred_code,usr_referred_id, user_id, usr_lname, usr_name, usr_email, usr_password, usr_create_platform, usr_verification_code, 
				usr_mobile, usr_mobile_verify, usr_mobile_verify_date, usr_email_verify, usr_email_verify_date, 
				usr_activation_key, usr_city, usr_zip, usr_address1, usr_address2, usr_address3, usr_ip, usr_gender, 
				usr_profile_pic, usr_profile_pic_path, usr_last_login, usr_created_at, usr_device, usr_modified, usr_active, 
				usr_country, usr_state, usr_state_text, usr_overall_rating, usr_last_trip_datetime, usr_last_trip_source_city, 
				usr_last_trip_destination_city, usr_last_trip_amount, usr_total_trips, usr_gozo_kms, usr_total_amount, 
				usr_preferences, usr_attributes,usr_log_count,usr_mark_customer_count,usr_acct_type,usr_reset_desc,usr_corporate_id,usr_acct_verify,email,usr_sos,usr_contact_id,verifyCode,usr_changepassword,usr_deactivate_reason', 'safe'),
//array('verifyCode', 'CaptchaExtendedValidator', 'allowEmpty'=>!CCaptcha::checkRequirements()),
			array('usr_name,usr_lname', 'nameValidation', 'on' => 'nameValidation'),
		);
	}

	public function defaultScope()
	{
		$arr = array(
			'condition' => "usr_active IN(1)",
		);
		return $arr;
	}

	public function nameValidation($attribute, $params)
	{
		if ($this->hasErrors())
		{
			return false;
		}
		if ($this->usr_name != '')
		{
			$this->addError("usr_name", "First Name cannot be changed");
			//  return false;
		}

		if ($this->usr_lname != '')
		{
			$this->addError("usr_lname", "Last Name cannot be changed");
			// return false;
		}

		return true;
	}

	public function checkLogin($attribute, $params)
	{
		if ($this->hasErrors())
		{
			return false;
		}
		$email			 = $this->email;
		$password		 = md5($this->usr_password);
		$this->_identity = new UserIdentity($email, $password);
		$valid			 = $this->_identity->authenticate();
		switch ($this->_identity->errorCode)
		{
			case 10:
				$this->addError($attribute, 'Your account has been removed. Please contact Support');
				break;
			case 20:
				$this->addError($attribute, 'Your account is not activated. Please check your mail and activate the account');
				break;
			case UserIdentity::ERROR_UNKNOWN_IDENTITY:
				$this->addError($attribute, 'Invalid Email ID / Password');
				break;
			default:
				break;
		}
		if (!$valid)
		{
			$this->addError($attribute, 'Invalid Email ID / Password');
		}

		return $valid;
	}

	public function validateUserName($attribute, $params)
	{
		$this->usr_create_platform	 = UserInfo::$platform;
		$username					 = $this->$attribute;
		$isEmail					 = Filter::validateEmail($username);
		$isPhone					 = false;
		if (!$isEmail)
		{
			$isPhone = Filter::validatePhoneNumber($username);
		}

		if (!$isEmail && !$isPhone)
		{
			$this->addError($attribute, "Please enter valid email id/phone number");
			return false;
		}
		Logger::create("UsersController::validateUserName :: " . $isPhone, CLogger::LEVEL_INFO);
		if ($isEmail)
		{
			$this->usernameType	 = Stub\common\ContactVerification::TYPE_EMAIL;
			$contactId			 = Contact::getByEmailPhone($username);
			if ($contactId == '')
			{
				$this->addError($attribute, "This email is not registered with us");
				return false;
			}
		}

		if ($isPhone)
		{
			$this->usernameType	 = Stub\common\ContactVerification::TYPE_PHONE;
			$phoneNumber		 = Filter::processPhoneNumber($username);
			if (!$phoneNumber)
			{
				$this->addError($attribute, "Please enter valid phone number/email id");
				return false;
			}
			Filter::parsePhoneNumber($phoneNumber, $code, $phone);
			$code				 = ($code[0] != '+' || $code[0] != 0) ? ('+' . $code) : ($code);
			$this->$attribute	 = $code . $phone;
			Logger::create("Users::validateUserName:: fullcontactnumber" . $this->$attribute, CLogger::LEVEL_INFO);
			$contactId			 = Contact::getByEmailPhone('', $this->$attribute);
			if ($contactId == '')
			{
				$this->addError($attribute, "This phone number is not registered with us");
				return false;
			}
		}

		if ($contactId == '')
		{
			$this->addError($attribute, "Sorry, this detail is not registered with us");
			return false;
		}

		$this->usr_contact_id = $contactId;
		return true;
	}

	public function validatePhoneNumber($attribute, $params)
	{

		if ($this->$attribute != '')
		{
			$phone		 = "+" . $this->usr_country_code . $this->$attribute;
			$phonenumber = new libphonenumber\LibPhone($phone);
			$a			 = $phonenumber->toE164();
			$a			 = $phonenumber->toInternational();
			$a			 = $phonenumber->toNational();
			if (!$phonenumber->validate())
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}

			if ($this->usr_country_code == '91' && strlen($this->$attribute) != 10)
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
		}
		else
		{
			$this->addError($attribute, 'Phone number is required for transaction.');
			return FALSE;
		}
		return true;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'appTokens'	 => array(self::HAS_MANY, 'AppTokens', 'apt_user_id'),
			'userLogs'	 => array(self::HAS_MANY, 'UserLog', 'log_user'),
			'usrContact' => array(self::BELONGS_TO, 'Contact', 'usr_contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id'				 => 'User',
			'usr_name'				 => 'User Name',
			'usr_lname'				 => 'Last Name',
			'usr_email'				 => 'Email',
			'usr_password'			 => 'Password',
			'repeat_password'		 => 'Repeat Password',
			'usr_verification_code'	 => 'User Verification Code',
			'usr_mobile'			 => 'Mobile No.',
			'usr_mobile_verify'		 => 'Mobile Verify',
			'usr_mobile_verify_date' => 'Mobile Verify Date',
			'usr_email_verify'		 => 'Email Verify',
			'usr_email_verify_date'	 => 'Email Verify Date',
			'usr_activation_key'	 => 'Activation Key',
			'usr_country'			 => 'Country',
			'usr_state'				 => 'State',
			'usr_state_text'		 => 'Type State',
			'usr_city'				 => 'Select City',
			'usr_ip'				 => 'Ip',
			'usr_address1'			 => 'Address',
			'usr_address2'			 => 'Address Line 2',
			'usr_address3'			 => 'Nearby Landmark',
			'usr_zip'				 => 'Zip Code',
			'usr_gender'			 => 'Gender',
			'usr_profile_pic'		 => 'Profile Pic',
			'usr_profile_pic_path'	 => 'Profile Pic Path',
			'usr_last_login'		 => 'Last Login',
			'usr_created_at'		 => 'Created At',
			'usr_device'			 => 'Device',
			'usr_modified'			 => 'Modified',
			'usr_active'			 => 'Active',
			'usr_acct_type'			 => 'Account Type',
			'usr_referred_code'		 => 'Referral Code',
			'usr_referred_id'		 => 'Referral Id',
			'usr_refer_code'		 => 'Refer Code',
			'usr_reset_desc'		 => 'Reset Reason',
			'email'					 => 'Email',
			'usr_sos'				 => 'SOS Contact List',
			'verifyCode'			 => 'Please enter verification Code',
			'usr_deactivate_reason'	 => 'Please enter the reason'
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
		$criteria->compare('usr_lname', $this->usr_lname, true);
		$criteria->compare('usr_email', $this->usr_email, true);
		$criteria->compare('usr_password', $this->usr_password, true);
		$criteria->compare('usr_verification_code', $this->usr_verification_code, true);
		$criteria->compare('usr_mobile', $this->usr_mobile, true);
		$criteria->compare('usr_mobile_verify', $this->usr_mobile_verify);
		$criteria->compare('usr_mobile_verify_date', $this->usr_mobile_verify_date, true);
		$criteria->compare('usr_email_verify', $this->usr_email_verify);
		$criteria->compare('usr_email_verify_date', $this->usr_email_verify_date, true);
		$criteria->compare('usr_activation_key', $this->usr_activation_key, true);
		$criteria->compare('usr_country', $this->usr_country);
		$criteria->compare('usr_state', $this->usr_state);
		$criteria->compare('usr_city', $this->usr_city, true);
		$criteria->compare('usr_zip', $this->usr_zip, true);
		$criteria->compare('usr_address1', $this->usr_address1, true);
		$criteria->compare('usr_address2', $this->usr_address2, true);
		$criteria->compare('usr_address3', $this->usr_address3, true);
		$criteria->compare('usr_ip', $this->usr_ip, true);
		$criteria->compare('usr_gender', $this->usr_gender);
		$criteria->compare('usr_profile_pic', $this->usr_profile_pic, true);
		$criteria->compare('usr_profile_pic_path', $this->usr_profile_pic_path, true);
		$criteria->compare('usr_last_login', $this->usr_last_login, true);
		$criteria->compare('usr_created_at', $this->usr_created_at, true);
		$criteria->compare('usr_device', $this->usr_device, true);
		$criteria->compare('usr_modified', $this->usr_modified, true);
		$criteria->compare('usr_create_platform', $this->usr_create_platform);
		$criteria->compare('usr_active', $this->usr_active);
		$criteria->compare('usr_log_count', $this->usr_log_count);
		$criteria->compare('usr_deactivate_reason', $this->usr_deactivate_reason);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function searchByNameEmailPhone($agentId)
	{
		//  $sql = "SELECT * from users WHERE usr_active IN(1) AND (usr_name LIKE '%$this->search_name%' OR usr_lname LIKE '%$this->search_name%' OR usr_email LIKE '%$this->search_name%' OR usr_mobile LIKE '%$this->search_name%')";
		$sql	 = "SELECT user_id,usr_contact_id,IF(usr_contact_id IS NULL,usr_name,ctt_name) AS name,IF(usr_contact_id IS NULL,usr_mobile,phn_phone_no) AS phone,IF(usr_contact_id IS NULL,usr_country_code,phn_phone_country_code) AS code,IF(usr_contact_id IS NULL,usr_email,eml_email_address) AS email,usr_city,usr_created_at from users
                    LEFT JOIN contact ON users.usr_contact_id = contact.ctt_id
		            LEFT JOIN contact_phone phn ON users.usr_contact_id = phn.phn_contact_id AND phn_is_primary = 1 AND phn_active = 1
		            LEFT JOIN contact_email eml ON users.usr_contact_id = eml.eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
                    WHERE usr_active IN(1) AND (usr_name LIKE '%$this->search_name%' OR usr_lname LIKE '%$this->search_name%' OR usr_email LIKE '%$this->search_name%' OR usr_mobile LIKE '%$this->search_name%') AND user_id NOT IN (SELECT agu_user_id FROM agent_users WHERE agu_agent_id=$agentId)";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['usr_name', 'usr_active'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 15],
		]);
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

	public function afterFind()
	{
		$this->email = $this->usr_email;
		parent::afterFind();
	}

	public function findByEmail($email)
	{
		return Users::model()->getByEmail($email, false);
	}

	public function findByPhoneorEmail($username)
	{
		$sql		 = "select user_id,usr_email,usr_mobile from users WHERE usr_email = '$username' OR usr_mobile = '$username'";
		$userModel	 = DBUtil::queryRow($sql);
		return $userModel;
	}

	public function findByPhone($phone)
	{
		$sql		 = "select user_id,usr_email,usr_mobile from users WHERE  usr_mobile = '$phone'";
		$userModel	 = DBUtil::queryRow($sql);
		return $userModel;
	}

	/**
	 * @param string $email email_address to be used for login
	 * @param boolean $createUser Create a User Account if not created and contact is found with verified/primary email
	 * @return Users|false will return false if user not found/created
	 *  */
	public function getByEmail($email, $createUser = false)
	{
		$params	 = ["email" => $email];
		$sql	 = "SELECT IFNULL(cp1.cr_is_consumer, cp.cr_is_consumer) as userId, c1.ctt_id, ce.eml_is_primary, ce.eml_is_verified,
						IF(cp1.cr_is_consumer IS NULL, 0, 1) as rank
					FROM contact_email ce
					INNER JOIN contact c ON ce.eml_contact_id=c.ctt_id AND eml_active=1 AND c.ctt_active=1
					INNER JOIN contact_profile cp ON cp.cr_contact_id=c.ctt_id
					INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code
					INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
					WHERE ce.eml_email_address=:email
					ORDER BY (ce.eml_is_primary + ce.eml_is_verified  ) DESC, rank DESC
			";
		$rows	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		$contactId	 = 0;
		$userId		 = 0;

		foreach ($rows as $row)
		{
			if ($row['userId'] > 0)
			{
				$userId		 = $row['userId'];
				$contactId	 = $row['ctt_id'];
				break;
			}
			if ($contactId == 0 && ($row['eml_is_primary'] == 1 || $row['eml_is_verified'] == 1))
			{
				$contactId = $row['ctt_id'];
			}
		}

		if ($userId == 0 && $contactId > 0)
		{
			$model = Users::createbyContact($contactId);
			goto end;
		}

		$model = Users::model()->findByPk($userId);

		end:
		return $model;
	}

	public function getByEmailVerify($email, $acct_type)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('usr_email', $email);
		$criteria->compare('usr_acct_type', $acct_type);
		return Users::model()->find($criteria);
	}

	public function checkValidAttempt($login = 1, $email)
	{
		Users::model()->getByEmail($email);
		if ($login == 1)
		{
			$this->usr_log_count = 0;
		}
		else if ($login == 0)
		{
			$this->usr_log_count = ($this->usr_log_count + 1);
		}
		$this->save();
		return Users::model()->getByEmail($email);
	}

	public function getNameById($userID)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = 'usr_name'; // select fields which you want in output
		$criteria->compare('user_id', $userID);
		return $this->find($criteria);
	}

	public function updateUserMarkCount($userId)
	{
		$sql = "UPDATE `users` SET `usr_mark_customer_count`=usr_mark_customer_count+1 WHERE `user_id`=$userId";
		/* @var $cdb CDbCommand */
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function checkUserMarkCount($userId)
	{
		$sql	 = "SELECT `usr_mark_customer_count` FROM `users` WHERE `usr_mark_customer_count`>0  AND `user_id`=$userId";
		/* @var $cdb CDbCommand */
		$cdb	 = DBUtil::command($sql);
		$Search	 = $cdb->queryRow();
		$count	 = ($Search['usr_mark_customer_count']);
		return $count;
	}

	public function resetMarkBadByUserId($userId)
	{
		if ($userId != '')
		{
			$model								 = new User();
			$userModel							 = $model->findByPk($userId);
			$userModel->usr_id					 = $userId;
			$userModel->usr_mark_customer_count	 = 0;
			$userModel->update();
			return true;
		}
		return false;
	}

	public function encrypt($parameter)
	{
		return md5($parameter);
	}

	public function beforeSave()
	{

		$email = $this->usr_email;
		if ($this->scenario != 'agentjoin' && $this->scenario != 'user_sync' && $this->scenario != 'change' && $this->scenario != 'new' && $this->scenario != 'update' && $this->scenario != 'reset' && $this->scenario != 'refcode' && $this->scenario != 'updateProfile' && $this->scenario != "insertonbooking" && $this->scenario != "agentQrjoin" && $this->scenario != "deactivate")
		{
			$emailexist = $this->findByEmail($email);
			if ($emailexist != '')
			{
				$this->addError("usr_email", "Email already exists");
				return false;
			}
			if (!empty($this->usr_password) && strlen($this->usr_password))
			{
				$this->usr_password = $this->encrypt($this->usr_password . '');
			}
			else
			{
				if (empty($this->usr_password))
				{
					$this->usr_password = $this->findByPk($this->user_id)->usr_password;
				}
			}
		}
		return parent::beforeSave();
	}

	public function search1($qry, $isExport = false)
	{

		$catFilter					 = ($this->category > 0) ? " AND cpr_category = " . $this->category : "";
		$lastBookingCreatedFilter	 = "";
		if ($this->last_booking_create_date1 != '' && $this->last_booking_create_date2 != '')
		{
			$lastBookingCreatedFilter = " AND DATE(urs_last_trip_created) BETWEEN '{$this->last_booking_create_date1}' AND '{$this->last_booking_create_date2}'";
		}
		$sql = "SELECT 
	   user_id,
	   usr_profile_pic_path,
	   usr_name,
	   contact.ctt_first_name,
       contact.ctt_last_name,
	   phn.phn_phone_no,
	   phn.phn_phone_country_code,
	   eml.eml_email_address,
	   usr_city,
	   phn.phn_is_verified,
	   eml.eml_is_verified,
	   usr_created_at,
	   usr_mark_customer_count,
	   usr_acct_verify,
	   '' as last_login,
		cpr_category,
		ctt_tags,
		urs_last_trip_created
		FROM   `users` 
		INNER JOIN user_stats ON urs_user_id = user_id {$lastBookingCreatedFilter}
		INNER JOIN contact ON users.usr_contact_id = contact.ctt_id
		INNER JOIN contact_pref ON cpr_ctt_id = contact.ctt_id {$catFilter}
		LEFT JOIN contact_phone phn ON contact.ctt_id = phn.phn_contact_id AND phn_is_primary = 1 AND phn_active = 1
		LEFT JOIN contact_email eml ON contact.ctt_id = eml.eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
		WHERE  users.usr_active=1";

		$sql .= (!empty($this->search_name)) ? " AND contact.ctt_first_name LIKE '%" . $this->search_name . "%' " : "";
		$sql .= (!empty($this->search_phone)) ? " AND phn.phn_phone_no LIKE '%" . $this->search_phone . "%' " : "";
		$sql .= (!empty($this->search_email)) ? " AND eml.eml_email_address LIKE '%" . $this->search_email . "%' " : "";
		$sql .= ($this->search_marked_bad == 1) ? " AND users.usr_mark_customer_count>0" : "";

		$sql .= " GROUP BY users.user_id";
		if ($isExport)
		{
			$dataArr = DBUtil::query($sql);
			return $dataArr;
		}
		$count = DBUtil::command(("SELECT COUNT(1) FROM ($sql) a"), DBUtil::SDB())->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 =>
			['attributes' =>
				['usr_name', 'last_login', 'usr_created_at'],
			],
			'pagination'	 => ['pageSize' => 50],
		]);
	}

	public function searchPromo($qry)
	{
		$sql	 = "SELECT users.*,contact.ctt_first_name,
				phn.phn_phone_no,
				phn.phn_phone_country_code,
				eml.eml_email_address, phn.phn_is_verified,
				eml.eml_is_verified,promo_users.*,IF(pru_promo_id =" . $this->promo_id . ",1,0) as activePromo
				FROM `users` 
				LEFT JOIN contact ON users.usr_contact_id = contact.ctt_id
				LEFT JOIN contact_phone phn ON contact.ctt_id = phn.phn_contact_id AND phn_is_primary = 1 AND phn_active = 1
				LEFT JOIN contact_email eml ON contact.ctt_id = eml.eml_contact_id AND eml_is_primary = 1 AND eml_active = 1 
				LEFT JOIN promo_users ON promo_users.pru_ref_id=users.user_id
				WHERE users.usr_active<>0 GROUP BY users.user_id"
		;
		$sql	 .= ($this->search_name != '') ? " AND contact.ctt_first_name LIKE '%" . $this->search_name . "%' " : "";
		$sql	 .= ($this->search_phone != '') ? " AND phn.phn_phone_no LIKE '%" . $this->search_phone . "%' " : "";
		$sql	 .= ($this->search_email != '') ? " AND eml.eml_email_address LIKE '%" . $this->search_email . "%' " : "";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['usr_name', 'usr_city', 'last_login', 'usr_created_at', 'usr_mark_customer_count'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 50],
		]);
	}

	public function uchangePassword()
	{
		$this->usr_password		 = $newPassword;
		$model->old_password	 = $oldPassword;
		$model->repeat_password	 = $rePassword;
	}

	public function profiledetails($user)
	{
//		$criteria			 = new CDbCriteria;
////		$criteria->select	 = "usr_name,usr_lname,usr_gender,usr_country_code,usr_mobile,usr_city,usr_zip,usr_address3,usr_address2,usr_address1,usr_profile_pic_path";
//		$criteria->compare('user_id', $user);
//		return $this->find($criteria);

		$sql = "select * from users WHERE user_id = $user";

		$userModel = DBUtil::queryRow($sql);
		return $userModel;
	}

	public function linkUserid($email, $phone)
	{
		$userid = '';
		if ($email != '' || $phone != '')
		{
			$criteria2 = new CDbCriteria;
			if ($email != '' && $phone != '')
			{
				$criteria1	 = new CDbCriteria;
				$criteria1->compare('usr_email', $email);
				$criteria1->compare('usr_mobile', $phone);
				$user1		 = $this->find($criteria1);
				$userid		 = $user1->user_id;
			}

			if ($userid > 0)
			{
				return $userid;
			}
			else
			{
				$criteria2->compare('usr_email', $email);
				$criteria2->compare('usr_mobile', $phone, false, 'OR');
			}
			$user2 = $this->find($criteria2);
			if ($user2)
			{
				$userid = $user2->user_id;
			}
		}
		return $userid;
	}

	/**
	 * @return Users 
	 */
	public function linkUserByEmail($bkg_id, $platform, $sendNotification = true)
	{
		$bkgUserModel = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $bkg_id]);

		$contactId = $bkgUserModel->bkg_contact_id;
		if ($contactId > 0)
		{
			$cpRow	 = ContactProfile::getProfileByCttId($contactId);
			$userId	 = $cpRow['cr_is_consumer'];
			if ($userId > 0)
			{
				$usrModel = Users::model()->findByPk($userId);
				return $usrModel;
			}
		}
		$usrModel = Users::model()->linkUserByBookingUserModel($bkgUserModel, $platform, $sendNotification);
		return $usrModel;
	}

	public function linkUserByBookingUserModel($bkgUserModel, $platform, $sendNotification = true)
	{
		$returnSet	 = new ReturnSet();
		$bkg_id		 = $bkgUserModel->bui_bkg_id;

		$contactId	 = Contact::checkExistingInfoByUser($bkgUserModel);
		$transaction = null;
		try
		{
			$transaction = DBUtil::beginTransaction();
			$usrModel	 = Users::createbyContact($contactId);
			if ($usrModel->hasErrors())
			{
				throw new ModelValidationException($usrModel);
			}
			if ($sendNotification)
			{
				Users::model()->sentConfirmationEmail($bkg_id);
			}
			DBUtil::commitTransaction($transaction);
			return $usrModel;
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			return false;
		}
	}

	public function sentConfirmationEmail($refBookingId = null)
	{
		if ($refBookingId != null && $this->usr_acct_type == '1')
		{
			/* @var $bkgModel Booking */
			$bkgModel		 = Booking::model()->findByPk($refBookingId);
			$bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $refBookingId]);
			if (!$bkgModel)
			{
				return false;
			}
			$response = Contact::referenceUserData($bkgUserModel->bui_id, 1);
			if ($response->getStatus())
			{
				$email = $response->getData()->email['email'];
			}
			//$email		 = $bkgUserModel->bkg_user_email;
			//$userId = $bkgModel->bkg_user_id;
			$bookingId	 = $bkgModel->bkg_booking_id;
			$userId		 = ($this->user_id > 0) ? $this->user_id : $bkgModel->bkgUserInfo->bkg_user_id;
			if ($email != '')
			{
				$emailCom	 = new emailWrapper();
				$logType	 = UserInfo::TYPE_SYSTEM;
				$emailCom->confirmBookingEmailByUserId($userId, $bookingId, $logType);
				return true;
			}
		}
		else if ($this->usr_acct_type == 1)
		{
			//$email		 = $bkgModel->bkg_user_email;
			$userId		 = $bkgModel->bkg_user_id;
			$bookingId	 = $bkgModel->bkg_booking_id;
			if ($email != '')
			{
				$emailCom	 = new emailWrapper();
				$logType	 = UserInfo::TYPE_SYSTEM;
				$emailCom->confirmBookingEmailByUserId($userId, $bookingId, $logType);
				return true;
			}
		}
		return false;
	}

	public function sendConfirmationSMS($refBookingId = null)
	{
		if ($refBookingId != null && $this->usr_acct_type != 0)
		{
			/* @var $bkgModel Booking */
			$bkgModel		 = Booking::model()->findByPk($refBookingId);
			$bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $refBookingId]);
			if (!$bkgModel)
			{
				return false;
			}
			$response = Contact::referenceUserData($bkgUserModel->bui_id, 2);
			if ($response->getStatus())
			{
				$contactNo	 = $response->getData()->phone['number'];
				$countryCode = $response->getData()->phone['ext'];
			}
			$country_code	 = $countryCode;
			$phone			 = $contactNo;
			$bookingId		 = $bkgModel->bkg_booking_id;
			if ($phone != '')
			{
				$msgCom	 = new smsWrapper();
				$logType = UserInfo::TYPE_SYSTEM;
				$link	 = 'aaocab.com' . Yii::app()->createUrl('users/confirmsignup', ['id' => $this->user_id, 'hash' => Yii::app()->shortHash->hash($this->user_id)]);
				$msgCom->confirmUserAccounts($country_code, $phone, $bookingId, $link, $logType);
				return true;
			}
		}
		else if ($this->usr_acct_type != 0)
		{
			$phone			 = $this->usr_mobile;
			$country_code	 = $this->usr_country_code;
			if ($phone != '')
			{
				$msgCom	 = new smsWrapper();
				$logType = UserInfo::TYPE_SYSTEM;
				$link	 = 'aaocab.com' . Yii::app()->createUrl('users/confirmsignup', ['id' => $this->user_id, 'hash' => Yii::app()->shortHash->hash($this->user_id)]);
				$msgCom->confirmUserAccounts($country_code, $phone, '', $link, $logType);
				return true;
			}
		}
		return false;
	}

	public function generateEmailByPhone($code, $phone, $bkgId)
	{
		throw new Exception("Please enter your email id");

//		$email	 = "gozo" . $code . $phone . "." . $bkgId . "@gozocabs.in";
//		$exist	 = $this->getByEmail($email);
//		while ($exist)
//		{
//			$rnd	 = rand(000, 999);
//			$email	 = "gozo" . $code . $phone . "." . $bkgId . "." . $rnd . "@gozocabs.in";
//			$exist	 = $this->getByEmail($email);
//		}
//		return $email;
	}

	public function updateBookingByUserId($bkgId, $usrId)
	{
		if ($bkgId != '' && $usrId != '')
		{
			$book					 = new Booking();
			$book->resetScope();
			$bookModel				 = $book->findByPk($bkgId);
			$bookModel->bkg_user_id	 = $usrId;
			$bookModel->save();
			return true;
		}
		return false;
	}

	public function getUsername()
	{
		return $this->usr_name . " " . $this->usr_lname;
	}

	public function getByReferCode($param)
	{
		return $this->find('usr_refer_code=:code', ['code' => $param]);
	}

	/**
	 * 
	 * @param string $code
	 * @return integer
	 */
	public static function getIdByReferCode($code)
	{
		$sqlParams	 = [':referCode' => $code];
		$sql		 = "SELECT * FROM `users` WHERE users.usr_refer_code='$code'";
		$result		 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $result['user_id'];
	}

	public function markedBadListByUserId($usrId)
	{
		$sql			 = "SELECT a.`blg_event_id`,a.`blg_created`,a.`blg_desc`,a.`blg_remark_type`,a.`blg_mark_driver`,b.`bkg_booking_id`,bu.`bkg_user_fname`,bu.`bkg_user_lname`,b.`bkg_from_city_id`,b.`bkg_to_city_id`,b.`bkg_pickup_date`,c.`user_id`,c.`usr_log`,c.`usr_mark_customer_count`,d.`cty_name` as from_city_name,e.`cty_name` as to_city_name "
				. "FROM booking_log a "
				. " JOIN `booking` b ON a.blg_booking_id=b.bkg_id "
				. "INNER JOIN `booking_user` bu ON bu.bui_bkg_id=b.bkg_id "
				. " JOIN cities d ON d.cty_id=b.bkg_from_city_id "
				. " JOIN cities e ON e.cty_id=b.bkg_to_city_id "
				. " JOIN `users` c ON c.user_id=bu.bkg_user_id "
				. "WHERE a.blg_mark_customer>0 AND bu.bkg_user_id=" . $usrId . "";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'blg_remark_type', 'blg_desc'],
				'defaultOrder'	 => 'blg_created DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

//    public function addCreditRefererOnFirstBooking($referredUser) {
//        $allbookings = Booking::model()->findAll('bkg_user_id=:user', ['user' => $referredUser]);
//        if ($allbookings != '' && count($allbookings) == 1) {
//            /*    @var $userreffered Users  */
//            $userreffered = Users::model()->findByPk($referredUser);
//            if ($userreffered != '' && $userreffered->usr_referred_code != '') {
//                $userrefer = Users::model()->getByReferCode($userreffered->usr_referred_code);
//                if ($userrefer != '') {
//                    $creditModel1 = new UserCredits();
//                    $creditModel1->ucr_user_id = $userrefer->user_id;
//                    $creditModel1->ucr_value = Yii::app()->params['inviterAmount'];
//                    $creditModel1->ucr_desc = 'referred a friend';
//                    $creditModel1->ucr_type = 6;
//                    $creditModel1->ucr_status = 1;
//                    $creditModel1->ucr_ref_id = $referredUser;
//                    $creditModel1->save();
//                    $emailCom = new emailWrapper();
//                    $emailCom->refererCreditedEmail($userrefer->user_id, $userreffered->usr_name . " " . $userreffered->usr_lname, Yii::app()->params['inviterAmount']);
//                    return true;
//                }
//            }
//        }
//        return false;
//    }

	public function addCreditRefererOnFirstBooking($referredUser)
	{
		$allbookings = BookingUser::model()->find('bkg_user_id=:user', ['user' => $referredUser]);
		if ($allbookings)
		{
			/*    @var $userreffered Users  */
			$userreffered = Users::model()->findByPk($referredUser);
			if ($userreffered != '' && $userreffered->usr_referred_code != '')
			{
				$userrefer = Users::model()->getByReferCode($userreffered->usr_referred_code);
				if ($userrefer != '')
				{

					$creditModel1 = UserCredits::model()->resetScope()->find('ucr_user_id=:user AND ucr_ref_id=:ref AND 	ucr_status=2', ['user' => $userrefer->user_id, 'ref' => $referredUser]);
					if ($creditModel1 != '' || $creditModel1 != null)
					{
						$creditModel1->ucr_status = 1;
						$creditModel1->save();
					}
					$emailCom = new emailWrapper();
					$emailCom->refererCreditedEmail($userrefer->user_id, $userreffered->usr_name . " " . $userreffered->usr_lname, Yii::app()->params['inviterAmount']);
					return true;
				}
			}
		}
		return false;
	}

	public function totalBooking($userId)
	{

		return Booking::model()->count('bkg_user_id=' . $userId . ' AND bkg_status<>8');
	}

	public function CorporateUsers($corporateId)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('usr_corporate_id', $corporateId);
		$criteria->compare('usr_name', $this->usr_name, true);
		$criteria->compare('usr_lname', $this->usr_name, true);
		$criteria->compare('usr_email', $this->usr_email, true);
		$criteria->compare('usr_mobile', $this->usr_mobile, true);
		return new CActiveDataProvider($this, array('sort'		 => ['attributes' => ['usr_created_at']],
			'criteria'	 => $criteria,
		));
	}

	public function getUsers($type)
	{
		if ($type == 1)
		{
			$sql = "select user_id from users where usr_active =1";
		}
		if ($type == 2)
		{
			$sql = "Select user_id, MAX(bkg_pickup_date) as max_date FROM `booking` JOIN users ON bkg_user_id = user_id 
						WHERE bkg_status IN (2,3,5,6,7) AND bkg_active = 1 AND usr_active = 1
						GROUP BY user_id 
						HAVING user_id <> '' AND max_date < date_sub(NOW(),INTERVAL 3 MONTH)";
		}
		if ($type == 3)
		{
			$sql = "Select user_id, MAX(bkg_pickup_date) as max_date FROM `booking` JOIN users ON bkg_user_id = user_id 
						WHERE bkg_status IN (2,3,5,6,7) AND bkg_active = 1 AND usr_active = 1
						GROUP BY user_id 
						HAVING user_id <> '' AND max_date < date_sub(NOW(),INTERVAL 6 MONTH)";
		}
		if ($type == 4)
		{
			$sql = "select user_id from users
						WHERE usr_active = 1 AND user_id NOT IN (Select DISTINCT bkg_user_id from booking where bkg_active = 1 and bkg_status IN (2,3,5,6,7) and bkg_user_id <> '')";
		}
		return DBUtil::queryAll($sql);
	}

	/**
	 * This function is used fetching users list based on their phone number 
	 * @param type contactId
	 * @return queryObject array
	 */
	public function linkUser()
	{
		if ($this->usr_email == '' && $this->usr_mobile == '')
		{
			return false;
		}

		$sql = "SELECT users.*, phn_phone_country_code, phn_phone_no, phn_full_number, eml_email_address,
					phn_is_primary, phn_is_verified, eml_is_verified, eml_is_primary
				FROM users  
				INNER JOIN contact_profile ON contact_profile.cr_is_consumer=users.user_id AND cr_status=1
				LEFT JOIN contact_phone ON contact_phone.phn_contact_id=contact_profile.cr_contact_id AND phn_active=1 AND (phn_is_verified = 1 OR phn_is_primary = 1) 
				LEFT JOIN contact_email ce ON ce.eml_contact_id=contact_profile.cr_contact_id AND eml_active=1 
			    WHERE usr_active=1
			  ";

		$params	 = [];
		$cond	 = [];

		if ($this->usr_email != '')
		{
			$params["email"] = trim($this->usr_email);
			$cond[]			 = "(usr_email=:email OR eml_email_address=:email)";
		}

		if ($this->usr_mobile != '')
		{
			$params["phone"] = trim($this->usr_mobile);
			$params["code"]	 = trim($this->usr_country_code);
			$cond[]			 = "((usr_mobile=:phone AND usr_country_code=:code) OR (phn_phone_no=:phone AND phn_phone_country_code=:code))";
		}

		if (count($cond) > 0)
		{
			$sql .= " AND (" . implode(" OR ", $cond) . ")";
		}

		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	public static function getLinkedContactIds($phone, $email)
	{
		if ($phone == '' && $email == '')
		{
			return false;
		}

		$phone = Filter::processPhoneNumber($phone);
		if ($phone)
		{
			Filter::parsePhoneNumber($phone, $code, $number);
			$phone = $code . $number;
		}

		if (!$phone && $email == '')
		{
			return false;
		}

		$sql = "SELECT GROUP_CONCAT(CONCAT(IFNULL(c1.ctt_ref_code, IFNULL(c1.ctt_id, 0)), ',', IFNULL(c2.ctt_ref_code, IFNULL(c2.ctt_id, 0)))) AS cttIds
				FROM users  
				LEFT JOIN contact_profile ON contact_profile.cr_is_consumer=users.user_id AND cr_status=1
				LEFT JOIN contact c1 ON c1.ctt_id=cr_contact_id
				LEFT JOIN contact c2 ON c2.ctt_id=usr_contact_id
			    WHERE usr_active=1
			  ";

		$params	 = [];
		$cond	 = [];

		if ($email != '')
		{
			$params["email"] = trim($email);
			$cond[]			 = "(usr_email=:email)";
		}

		if ($phone != '')
		{
			$params["phone"] = trim($number);
			$params["code"]	 = trim($code);
			$cond[]			 = "(usr_mobile=:phone AND usr_country_code=:code)";
		}

		if (count($cond) > 0)
		{
			$sql .= " AND (" . implode(" OR ", $cond) . ")";
		}

		$cttIds = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $cttIds;
	}

	public function totBookingsWithStatus($userId)
	{
		$sql	 = "SELECT SUM(IF(bkg_status IN(6,7),IF(booking_invoice.bkg_total_amount IS NULL,0,booking_invoice.bkg_total_amount),0)) totAmount,SUM(IF(bkg_status IN(4,5),1,0)) totOntheWay,"
				. "SUM(IF(bkg_status=3,1,0)) totAssinged,"
				. " COUNT(1) totInquiry, MAX(bkg_create_date) As lastInquiryDate,MAX(IF(bkg_status IN(6, 7),bkg_pickup_date,null)) As lastTravelledDate,MAX(IF(bkg_reconfirm_flag=1,bkg_create_date,null)) As lastPaidBookingCreateDate, "
				. "SUM(IF(bkg_status IN(6,7),IF(booking_invoice.bkg_gozo_amount IS NULL,0,booking_invoice.bkg_gozo_amount),0)) totGozoAmount,"
				. "SUM(IF(bkg_status IN(6,7),1,0)) totCompleted,"
				. "SUM(IF(bkg_status IN(3,5,13),1,0)) totOthers,SUM(IF(bkg_status=2,1,0)) totNew,"
				. "SUM(IF(bkg_status=9,1,0)) totCancelled,SUM(IF(bkg_status=10,1,0)) totCancelledQt,SUM(IF(bkg_status IN(2,3,5,6,7),1,0)) total,SUM(IF(bkg_status=1,1,0)) totUnverified, SUM(IF(bkg_status=15,1,0)) totQuote "
				. "FROM `booking` "
				. " JOIN `booking_invoice` ON booking.bkg_id=booking_invoice.biv_bkg_id "
				. " JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id"
				. " WHERE booking_user.bkg_user_id=$userId AND bkg_status<>8 AND bkg_active=1";
		$data	 = DBUtil::queryRow($sql);
		return $data;
	}

	public function verifyEmail($email)
	{
		$users = Users::model()->find("usr_email=:email", ['email' => $email]);
		if (count($users) > 0)
		{
			$code							 = rand(999, 9999);
			$emailWrapper					 = new emailWrapper();
			$email							 = $users->usr_email;
			$emailWrapper->sendVerificationAgent($email, $code);
			$users->usr_verification_code	 = $code;
			if ($users->update())
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
		}
		else
		{
			$status = false;
		}
		return $status;
	}

	public function linkedAgentUsersByAgent($agentId)
	{
		$sql	 = "SELECT users.*,last_login
                    ,IF(usr_contact_id IS NULL,usr_mobile,phn_phone_no) AS phone
                    ,IF(usr_contact_id IS NULL,usr_country_code,phn_phone_country_code) AS code
                    ,IF(usr_contact_id IS NULL,usr_email,eml_email_address) AS email
                    ,IF(usr_contact_id IS NULL,usr_name,ctt_name) AS name 
                    FROM `users` 
                    LEFT JOIN contact ON users.usr_contact_id = contact.ctt_id AND contact.ctt_active = 1
		            LEFT JOIN contact_phone phn ON contact.ctt_id = phn.phn_contact_id AND phn_is_primary = 1 AND phn_active = 1
		            LEFT JOIN contact_email eml ON contact.ctt_id = eml.eml_contact_id AND eml_is_primary = 1 AND eml_active = 1 
                    LEFT JOIN ( 
					SELECT MAX(user_log.log_in_time) as last_login, user_log.log_user FROM `user_log` 
					GROUP BY user_log.log_user 
				) log ON log.log_user=users.user_id INNER JOIN agent_users ON agent_users.agu_user_id=users.user_id  WHERE `usr_active` <> 0 AND agent_users.agu_agent_id=$agentId";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['usr_name', 'usr_city', 'last_login', 'usr_created_at', 'usr_mark_customer_count'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 50],
		]);
	}

	public function linkedVendorId($vndId)
	{
		$sql	 = "SELECT users.*,last_login,IF(usr_contact_id IS NULL,usr_mobile,phn_phone_no) AS phone
                    ,IF(usr_contact_id IS NULL,usr_country_code,phn_phone_country_code) AS code
                    ,IF(usr_contact_id IS NULL,usr_email,eml_email_address) AS email
                    ,IF(usr_contact_id IS NULL,usr_name,ctt_name) AS name  
                    FROM `users` 
                    LEFT JOIN contact ON users.usr_contact_id = contact.ctt_id
		            LEFT JOIN contact_phone phn ON contact.ctt_id = phn.phn_contact_id AND phn_is_primary = 1 AND phn_active = 1
		            LEFT JOIN contact_email eml ON contact.ctt_id = eml.eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
                    LEFT JOIN ( 
					SELECT MAX(user_log.log_in_time) as last_login, user_log.log_user FROM `user_log` 
					GROUP BY user_log.log_user 
				) log ON log.log_user=users.user_id INNER JOIN contact_profile cp  ON cp.cr_is_consumer = users.user_id WHERE `usr_active` <> 0 AND cp.cr_is_vendor =$vndId";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['usr_name', 'usr_city', 'last_login', 'usr_created_at', 'usr_mark_customer_count'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 50],
		]);
	}

	public function linkedDriverId($drvId)
	{
		$sql	 = "SELECT users.*,last_login,IF(usr_contact_id IS NULL,usr_mobile,phn_phone_no) AS phone
                    ,IF(usr_contact_id IS NULL,usr_country_code,phn_phone_country_code) AS code
                    ,IF(usr_contact_id IS NULL,usr_email,eml_email_address) AS email
                    ,IF(usr_contact_id IS NULL,usr_name,ctt_name) AS name 
                     FROM `users`  
                     LEFT JOIN contact ON users.usr_contact_id = contact.ctt_id
		             LEFT JOIN contact_phone phn ON contact.ctt_id = phn.phn_contact_id AND phn_is_primary = 1 AND phn_active = 1
		             LEFT JOIN contact_email eml ON contact.ctt_id = eml.eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
                     LEFT JOIN ( 
					SELECT MAX(user_log.log_in_time) as last_login, user_log.log_user FROM `user_log` 
					GROUP BY user_log.log_user 
				) log ON log.log_user=users.user_id INNER JOIN contact_profile cp  ON cp.cr_is_consumer = users.user_id  WHERE `usr_active` <> 0 AND cp.cr_is_driver =$drvId";
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		return new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 =>
			['attributes'	 =>
				['usr_name', 'usr_city', 'last_login', 'usr_created_at', 'usr_mark_customer_count'],
				'defaultOrder'	 => $defaultOrder],
			'pagination'	 => ['pageSize' => 50],
		]);
	}

	public function isLinkedToAgent($user)
	{
		$agentUsers = AgentUsers::model()->with('agentAgentUsers')->findAll('agu_user_id=:user', ['user' => $user]);
		if ($agentUsers != '' && count($agentUsers) > 0)
		{
			$str = "";
			foreach ($agentUsers as $key => $agentUser)
			{
				$str .= " <label class='bg-info pl5 pr5'>" . $agentUser->agentAgentUsers->agt_fname . "</label>";
			}
			return "<b>Agents: </b>" . $str;
		}
		return false;
	}

	/**
	 * 
	 * @param Users $model
	 * @return string
	 */
	public static function getUniqueReferCode($model)
	{
		$referCode = "";
		if ($model->usr_refer_code != '')
		{
			return $model->usr_refer_code;
		}

		$referCode	 = Users::generateRefCode($model->usr_name);
		$usrModel	 = Users::model()->getByReferCode($referCode);

		if (!empty($usrModel))
		{
			self::getUniqueReferCode($model);
		}

		return $referCode;
	}

	public function getRefercode($userId)
	{
		$refCode			 = "";
		$modelUser			 = Users::model()->resetScope()->findByPk($userId);
		$modelUser->scenario = 'refcode';
		if ($modelUser->usr_refer_code != '')
		{
			$refCode = $modelUser->usr_refer_code;
		}
		else
		{
			do
			{
				$uname				 = $modelUser->usr_name;
				$refCode			 = Users::generateRefCode($uname);
				$checkExistingCode	 = Users::model()->getByReferCode($refCode);
			}
			while ($checkExistingCode);
			$modelUser->usr_refer_code = $refCode;
			if ($modelUser->validate())
			{
				if (!$modelUser->save())
				{
					return false;
				}
			}
		}
		$refArr = ['refCode' => $refCode, 'refMessage' => "Install GozoCabs APP and use '$refCode' as referral code at Registration"];
		return $refArr;
	}

	public function getFbLogin($userId = 0, $email = '', $phone = '', $returnUser = false)
	{
		if (($userId == 0 || $userId == '') && ($email != '' || $phone != ''))
		{
			if ($email != '' && $phone != '')
			{
				$criteria1	 = new CDbCriteria;
				$criteria1->compare('usr_email', trim($email));
				$criteria1->compare('usr_mobile', trim($phone));
				$criteria1->addCondition("usr_active > 0");
				$usrModel	 = $this->find($criteria1);
				if ($usrModel != '')
				{
					$userId = $usrModel->user_id;
				}
			}
			if ($email != '' && $userId == '')
			{
				$criteria2	 = new CDbCriteria;
				$criteria2->compare('usr_email', trim($email));
				$criteria2->addCondition("usr_active > 0");
				$usrModel	 = $this->find($criteria2);
				if ($usrModel)
				{
					$userId = $usrModel->user_id;
				}
			}
			if ($phone != '' && $userId == '')
			{
				$criteria3	 = new CDbCriteria;
				$criteria3->compare('usr_mobile', trim($phone));
				$criteria3->addCondition("usr_active > 0");
				$usrModel	 = $this->find($criteria3);
				if ($usrModel)
				{
					$userId = $usrModel->user_id;
				}
			}
		}
		if (!Yii::app()->user->isGuest)
		{
			$userId = Yii::app()->user->getId();
		}
		if ($userId != 0 && $userId != '')
		{
			if ($returnUser)
			{
				return $userId;
			}

			if (!Yii::app()->user->isGuest)
			{
				$userId	 = Yii::app()->user->getId();
				$result	 = DBUtil::command('SELECT COUNT(*) FROM ' . Yii::app()->db->tablePrefix . 'user_oauth WHERE user_id=' . $userId . ' AND provider="Facebook"')->queryScalar();
				if ($result > 0)
				{
					$userModel = Users::model()->findByPk($userId);
					if ($userModel->usr_gender == '')
					{
						$profile = DBUtil::command('SELECT profile_cache FROM ' . Yii::app()->db->tablePrefix . 'user_oauth WHERE user_id=' . $userId . ' AND provider="Facebook"')->queryScalar();
						if ($profile != '')
						{
							$arr					 = unserialize($profile);
							$userModel->usr_gender	 = Users::model()->reverseGenderList[$arr['gender']];
							$userModel->save();
						}
					}
					return true;
				}
			}
		}
		if ($returnUser)
		{
			return 0;
		}
		return false;
	}

	/* public function oldlinkAppUser($linkeduserid = 0, $social_data = '')
	  {
	  $provider			 = Yii::app()->request->getParam('provider');
	  $process_sync_data	 = Yii::app()->request->getParam('data', '');
	  if ($social_data == '')
	  {
	  $process_sync_data1 = Yii::app()->request->getParam('social_data', '');
	  }
	  else
	  {
	  $process_sync_data1 = $social_data;
	  }
	  $profile_image_url	 = Yii::app()->request->getParam('social_profile_image_url', '');
	  $process_sync_data	 = ($process_sync_data1 == '') ? $process_sync_data : $process_sync_data1;

	  Logger::create("request params :: " . $process_sync_data, CLogger::LEVEL_INFO);
	  Logger::create("provider :: " . $provider, CLogger::LEVEL_INFO);
	  Logger::create("profile_image_url :: " . $profile_image_url, CLogger::LEVEL_INFO);
	  $userData	 = CJSON::decode($process_sync_data, true);

	  $email		 = $userData['email'];
	  $userModel	 = Users::model()->getByEmail($email);

	  if (!$userModel)
	  {
	  if ($linkeduserid > 0)
	  {
	  $userModel = Users::model()->findByPk($linkeduserid);
	  }
	  else
	  {
	  $userModel	 = new Users();
	  $pass		 = uniqid(rand(), TRUE);

	  $userModel->usr_password	 = $pass;
	  $userModel->new_password	 = $pass;
	  $userModel->repeat_password	 = $pass;
	  }
	  }
	  $oauthData				 = [];
	  $sessData				 = [];
	  $oauthData['identifier'] = $userData['id'];

	  if ($provider == 'Google')
	  {
	  $userModel->usr_email			 = $userData['email'];
	  $userModel->usr_name			 = $userData['givenName'];
	  $userModel->usr_lname			 = $userData['familyName'];
	  $userModel->usr_create_platform	 = 1;
	  if (($userModel->usr_mobile == '' || $userModel->usr_mobile == null ) && $userData['phone'] != '')
	  {
	  $userModel->usr_mobile = $userData['phone'];
	  }
	  //			$userModel->usr_country			 = $userData['country'];
	  //			$userModel->usr_mobile			 = $userData['phone'];
	  //			$userModel->usr_zip				 = $userData['zip'];

	  $oauthData['displayName']	 = $userData['displayName'];
	  $oauthData['firstName']		 = $userData['givenName'];
	  $oauthData['lastName']		 = $userData['familyName'];
	  $oauthData['phone']			 = $userData['phone'];
	  $oauthData['email']			 = $userData['email'];
	  $oauthData['gender']		 = $userData['gender'] | '';

	  $sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
	  $sessData['hauth_session.google.token.expires_at']	 = serialize($userData['expirationTime']);
	  $sessData['hauth_session.google.is_logged_in']		 = serialize('1');
	  }
	  if ($provider == 'Facebook')
	  {
	  $userModel->usr_email = $userData['email'];
	  if ($userModel->usr_gender == '' && $userData['gender'] != '')
	  {
	  $userModel->usr_gender = $userData['gender'];
	  }
	  $userModel->usr_name	 = $userData['first_name'];
	  $userModel->usr_lname	 = $userData['last_name'];


	  $oauthData['firstName']	 = $userData['first_name'];
	  $oauthData['lastName']	 = $userData['last_name'];
	  $oauthData['gender']	 = $userData['gender'];
	  $oauthData['email']		 = $userData['email'];

	  $sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
	  }
	  if (($userModel->usr_profile_pic == '' || $userModel->usr_profile_pic == null ) && $profile_image_url != '')
	  {
	  $userModel->usr_profile_pic = $profile_image_url;
	  }
	  $userModel->usr_create_platform	 = 1;
	  $userModel->usr_email_verify	 = 1;
	  $userModel->save();

	  $isSocialLinked = Users::model()->checkSocialLinking($userModel->user_id, $provider);
	  if (!$isSocialLinked)
	  {
	  $tablePrefix = Yii::app()->db->tablePrefix;
	  $oauthtable	 = $tablePrefix . 'user_oauth';
	  $userid		 = $userModel->user_id;
	  $identifier	 = $userData['id'];
	  $sql		 = "select * from $oauthtable where  identifier='$identifier' AND provider = '$provider'";
	  $val		 = DBUtil::queryRow($sql);
	  if (!$val)
	  {
	  $profile_cache	 = serialize($oauthData);
	  $session_data	 = serialize($sessData);
	  $sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`)
	  VALUES ('$userid', '$provider', '$identifier','$profile_cache' , '$session_data')";

	  $resultRow = DBUtil::command($sql)->execute();
	  }
	  }

	  if ($userModel->usr_gender == '' && $userData['gender'] != '')
	  {
	  $genderList				 = Users::model()->reverseGenderList;
	  $userModel->usr_gender	 = $genderList[$userData['gender']];
	  }

	  $success	 = false;
	  $response	 = [];
	  if ($userModel->save())
	  {
	  Logger::create("user data saved ", CLogger::LEVEL_INFO);
	  $response['user_id'] = $userModel->user_id;
	  $success			 = true;
	  }
	  $picdata = $userModel->usr_profile_pic;
	  if (((!file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic)) || $userModel->usr_profile_pic == '' || filesize(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic) == 0) && $picdata != '')
	  {
	  $arrContextOptions = array("ssl" => array(
	  "verify_peer"		 => false,
	  "verify_peer_name"	 => false,
	  ),);
	  if ($userModel->usr_profile_pic_path == '' && $picdata != '')
	  {
	  $userModel->usr_profile_pic_path = $picdata;
	  }
	  if ($userModel->usr_profile_pic_path)
	  {
	  $profilePic = strtolower('images/profiles/' . $userModel->user_id . str_replace(' ', '', $userModel->usr_name)) . rand(10000, 99999) . '.jpg';

	  file_put_contents(
	  $profilePic, file_get_contents($userModel->usr_profile_pic_path, false, stream_context_create($arrContextOptions))
	  );
	  $userModel->usr_profile_pic = '/' . $profilePic;
	  }
	  if ($userModel->validate())
	  {
	  $userModel->save();
	  }
	  } Logger::create("User registered id :: " . $response['user_id'], CLogger::LEVEL_INFO);
	  $response['success'] = $success;
	  return $response;
	  }
	 *
	 */

//	public function linkAppUser($linkeduserid = 0, $social_data = '' ,$social=0)
//	{
//		//$social ==1 case:vendor driver. statement: name password email will not modified in user table
//		$provider			 = Yii::app()->request->getParam('provider');
//		$process_sync_data	 = Yii::app()->request->getParam('data', '');
//		$success			 = false;
//		$msg                 = '';
//		if ($social_data == '')
//		{
//			$process_sync_data1 = Yii::app()->request->getParam('social_data', '');
//		}
//		else
//		{
//			$process_sync_data1 = $social_data;
//		}
//		$profile_image_url	 = Yii::app()->request->getParam('social_profile_image_url', '');
//		$process_sync_data	 = ($process_sync_data1 == '') ? $process_sync_data : $process_sync_data1;
//		
//		Logger::create("request params :: " . $process_sync_data, CLogger::LEVEL_INFO);
//		Logger::create("provider :: " . $provider, CLogger::LEVEL_INFO);
//		Logger::create("profile_image_url :: " . $profile_image_url, CLogger::LEVEL_INFO);
//		$userData	 = CJSON::decode($process_sync_data, true);	
//		$email		 = $userData['email'];	
//		
//		////////////////
//		if(trim($userData['id']) != '' && trim($email) != '')
//		{			
//			$alreadyExistData = Users::model()->checkSocialLinkAlreadyExist($userData['id']);		
//			if (array_key_exists('user_id', $alreadyExistData)) {
//				$msg	= "Already linked with other user";
//				goto result_error;
//			}			
//		} else {			
//			$msg	= "Link unsuccessful.";
//			goto result_error;
//		}
//		//////////////
//		
//		
//		$userModel	 = Users::model()->findByEmail($email);
//		if (!$userModel)
//		{
//			if ($linkeduserid > 0)
//			{
//				
//				$userModel = Users::model()->findByPk($linkeduserid);
//				
//			}
//			else
//			{  
//				$userModel	 = new Users();
//				
//				if($social!=1)// incase of vendor or driver pasword should not be added or modified
//				{
//					
//					$pass		 = uniqid(rand(), TRUE);
//					$userModel->usr_password	 = $pass;
//					$userModel->new_password	 = $pass;
//					$userModel->repeat_password	 = $pass;
//				}
//			}
//		}
//		else
//		{ 
//			$msg	= "Already linked with other user";
//			goto result_error;
//		}
//		
//		
//		$oauthData				 = [];
//		$sessData				 = [];
//		$oauthData['identifier'] = $userData['id'];
//		
//		if ($provider == 'Google')
//		{
//			if($social!=1)// incase of vendor or driver no data will modified for user table 
//			{
//				$userModel->usr_email			 = $userData['email'];
//				$userModel->usr_name			 = $userData['givenName'];
//				$userModel->usr_lname			 = $userData['familyName'];
//				if (($userModel->usr_mobile == '' || $userModel->usr_mobile == null ) && $userData['phone'] != '')
//				{
//					$userModel->usr_mobile = $userData['phone'];
//				}
//			}
//			$userModel->usr_create_platform	 = 1;
////			$userModel->usr_country			 = $userData['country'];
////			$userModel->usr_mobile			 = $userData['phone'];
////			$userModel->usr_zip				 = $userData['zip'];
//
//			$oauthData['displayName']	 = $userData['displayName'];
//			$oauthData['firstName']		 = $userData['givenName'];
//			$oauthData['lastName']		 = $userData['familyName'];
//			$oauthData['phone']			 = $userData['phone'];
//			$oauthData['email']			 = $userData['email'];
//			$oauthData['gender']		 = $userData['gender'] | '';
//			
//
//			$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
//			$sessData['hauth_session.google.token.expires_at']	 = serialize($userData['expirationTime']);
//			$sessData['hauth_session.google.is_logged_in']		 = serialize('1');			
//			
//		}
//		if ($provider == 'Facebook')
//		{
//			if($social!=1)// incase of vendor or driver no data will modified for user table 
//			{
//				$userModel->usr_email    = $userData['email'];
//				$userModel->usr_name	 = $userData['first_name'];
//				$userModel->usr_lname	 = $userData['last_name'];
//			}
//			if ($userModel->usr_gender == '' && $userData['gender'] != '')
//			{
//				$userModel->usr_gender = $userData['gender'];
//			}
//			$oauthData['firstName']	 = $userData['first_name'];
//			$oauthData['lastName']	 = $userData['last_name'];
//			$oauthData['gender']	 = $userData['gender'];
//			$oauthData['email']		 = $userData['email'];
//
//			$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
//		}
//		if (($userModel->usr_profile_pic == '' || $userModel->usr_profile_pic == null ) && $profile_image_url != '')
//		{
//			$userModel->usr_profile_pic = $profile_image_url;
//		}
//		$userModel->usr_create_platform	 = 1;
//		$userModel->usr_email_verify	 = 1;
//		$userModel->save();
//		
//		$isSocialLinked = Users::model()->checkSocialLinking($userModel->user_id, $provider);
//		if (!$isSocialLinked)
//		{
//			$tablePrefix = Yii::app()->db->tablePrefix;
//			$oauthtable	 = $tablePrefix . 'user_oauth';
//			$userid		 = $userModel->user_id;
//			$identifier	 = $userData['id'];
//			$sql		 = "select * from $oauthtable where  identifier='$identifier' AND provider = '$provider'";
//			$val		 = DBUtil::queryRow($sql);
//			if (!$val)
//			{
//				$profile_cache	 = serialize($oauthData);
//				$session_data	 = serialize($sessData);
//			 	$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
//					VALUES ('$userid', '$provider', '$identifier','$profile_cache' , '$session_data')";			
//				$resultRow = DBUtil::command($sql)->execute();
//				
//				
//			}
//			else
//			{ 
//				$msg			 = "Already linked with other user";
//				goto result_error;
//			}
//		}
//
//		if ($userModel->usr_gender == '' && $userData['gender'] != '')
//		{
//			$genderList				 = Users::model()->reverseGenderList;
//			$userModel->usr_gender	 = $genderList[$userData['gender']];
//		}
//
//		
//		$response	 = [];
//		if ($userModel->save())
//		{
//			Logger::create("user data saved ", CLogger::LEVEL_INFO);
//			$response['user_id'] = $userModel->user_id;
//			$success			 = true;
//			$msg				 = "Link successful.";
//		}
//		
//		$picdata = $userModel->usr_profile_pic;
//		if (((!file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic)) || $userModel->usr_profile_pic == '' || filesize(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic) == 0) && $picdata != '')
//		{
//			$arrContextOptions = array("ssl" => array(
//					"verify_peer"		 => false,
//					"verify_peer_name"	 => false,
//				),);
//			if ($userModel->usr_profile_pic_path == '' && $picdata != '')
//			{
//				$userModel->usr_profile_pic_path = $picdata;
//			}
//			if ($userModel->usr_profile_pic_path)
//			{
//				$profilePic = strtolower('images/profiles/' . $userModel->user_id . str_replace(' ', '', $userModel->usr_name)) . rand(10000, 99999) . '.jpg';
//
//				file_put_contents(
//						$profilePic, file_get_contents($userModel->usr_profile_pic_path, false, stream_context_create($arrContextOptions))
//				);
//				$userModel->usr_profile_pic = '/' . $profilePic;
//			}
//			if ($userModel->validate())
//			{
//				$userModel->save();
//			}
//		} Logger::create("User registered id :: " . $response['user_id'], CLogger::LEVEL_INFO);
//		
//		result_error:
//		$response['success'] = $success;
//		$response['msg'] = $msg;
//		return $response;
//	}
	// public function linkAppUser($linkeduserid = 0, $social_data = '' ,$social=0)
	public function linkAppUser($linkeduserid = 0, $social_data = '', $social = 0, $provider_name = 0, $sync_Data = 0, $flag1 = 0)
	{
		//$social ==1 case:vendor driver. statement: name password email will not modified in user table
		$provider			 = Yii::app()->request->getParam('provider');
		$process_sync_data	 = Yii::app()->request->getParam('data', '');

		//////////////
		if ($flag1 === 'vendor-app')
		{
			$provider			 = $provider_name;
			$process_sync_data	 = $sync_Data;
		}
		//////////////

		$success = false;
		$msg	 = '';
		if ($social_data == '')
		{
			$process_sync_data1 = Yii::app()->request->getParam('social_data', '');
		}
		else
		{
			$process_sync_data1 = $social_data;
		}
		$profile_image_url	 = Yii::app()->request->getParam('social_profile_image_url', '');
		$process_sync_data	 = ($process_sync_data1 == '') ? $process_sync_data : $process_sync_data1;

		Logger::create("request params :: " . $process_sync_data, CLogger::LEVEL_INFO);
		Logger::create("provider :: " . $provider, CLogger::LEVEL_INFO);
		Logger::create("profile_image_url :: " . $profile_image_url, CLogger::LEVEL_INFO);
		$userData	 = CJSON::decode($process_sync_data, true);
		$email		 = $userData['email'];

//		if ($userData['email'] != NULL)
//		{
//			$contactEmail = ContactEmail::model()->findEmailIdByEmail($userData['email']);
//			if (count($contactEmail) == 0)
//			{
//				$sql		 = "INSERT INTO contact_email (`eml_contact_id`,`eml_email_address`,`eml_is_verified`,`eml_is_primary`,`eml_active`) VALUES ('$contactEmail->eml_contact_id','$email',1,0,1)";
//				$resultRow	 = DBUtil::command($sql)->execute();
//			}
//		}

		$userModel = Users::model()->findByEmail($email);
		if (!empty($userModel))
		{

			if ($linkeduserid > 0)
			{

				$userModel = Users::model()->findByPk($linkeduserid);
			}
			else
			{
				if ($social != 1)
				{// incase of vendor or driver pasword should not be added or modified
					$userModel					 = new Users();
					$pass						 = uniqid(rand(), TRUE);
					$userModel->usr_password	 = $pass;
					$userModel->new_password	 = $pass;
					$userModel->repeat_password	 = $pass;
				}
			}
		}
		else
		{

			$newPass22						 = rand(100000, 999999);
			$userModel						 = new Users();
			$userModel->usr_password		 = $newPass22;
			$userModel->new_password		 = $newPass22;
			$userModel->repeat_password		 = $newPass22;
			$userModel->usr_email			 = $email;
			$userModel->usr_name			 = null;
			$userModel->usr_lname			 = null;
			$userModel->usr_create_platform	 = 1;
			$userModel->usr_mobile			 = null;
			$userModel->save();
		}


		$oauthData				 = [];
		$sessData				 = [];
		$oauthData['identifier'] = $userData['id'];

		if ($provider == 'Google')
		{
			if ($social != 1)
			{// incase of vendor or driver no data will modified for user table 
				$userModel->usr_email	 = $userData['email'];
				$userModel->usr_name	 = $userData['givenName'];
				$userModel->usr_lname	 = $userData['familyName'];
				if (($userModel->usr_mobile == '' || $userModel->usr_mobile == null ) && $userData['phone'] != '')
				{
					$userModel->usr_mobile = str_replace(' ', '', $userData['phone']);
				}
			}
			$userModel->usr_create_platform = 1;
//			$userModel->usr_country			 = $userData['country'];
//			$userModel->usr_mobile			 = $userData['phone'];
//			$userModel->usr_zip				 = $userData['zip'];

			$oauthData['displayName']	 = $userData['displayName'];
			$oauthData['firstName']		 = $userData['givenName'];
			$oauthData['lastName']		 = $userData['familyName'];
			$oauthData['phone']			 = $userData['phone'];
			$oauthData['email']			 = $userData['email'];
			$oauthData['gender']		 = $userData['gender'] | '';

			$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
			$sessData['hauth_session.google.token.expires_at']	 = serialize($userData['expirationTime']);
			$sessData['hauth_session.google.is_logged_in']		 = serialize('1');
		}
		if ($provider == 'Facebook')
		{
			if ($social != 1)
			{// incase of vendor or driver no data will modified for user table 
				$userModel->usr_email	 = $userData['email'];
				$userModel->usr_name	 = $userData['first_name'];
				$userModel->usr_lname	 = $userData['last_name'];
			}
			if ($userModel->usr_gender == '' && $userData['gender'] != '')
			{
				$userModel->usr_gender = $userData['gender'];
			}
			$oauthData['firstName']	 = $userData['first_name'];
			$oauthData['lastName']	 = $userData['last_name'];
			$oauthData['gender']	 = $userData['gender'];
			$oauthData['email']		 = $userData['email'];

			$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
		}
		if (($userModel->usr_profile_pic == '' || $userModel->usr_profile_pic == null ) && $profile_image_url != '')
		{
			$userModel->usr_profile_pic = $profile_image_url;
		}
		$userModel->usr_create_platform	 = 1;
		$userModel->usr_email_verify	 = 1;
		$userModel->usr_name			 = $oauthData['firstName'];
		$userModel->usr_lname			 = $oauthData['lastName'];

		$userModel->save();

		$isSocialLinked = Users::model()->checkSocialLinking($userModel->user_id, $provider, $userData['id']);
		if (!$isSocialLinked)
		{
			$tablePrefix = Yii::app()->db->tablePrefix;
			$oauthtable	 = $tablePrefix . 'user_oauth';
			$userid		 = $userModel->user_id;
			$identifier	 = $userData['id'];
			$sql		 = "select * from $oauthtable where  identifier='$identifier' AND provider = '$provider'";
			$val		 = DBUtil::queryRow($sql);
			if (!$val)
			{
				$profile_cache	 = serialize($oauthData);
				$session_data	 = serialize($sessData);
				$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
					VALUES ('$userid', '$provider', '$identifier','$profile_cache' , '$session_data')";
				$resultRow		 = DBUtil::command($sql)->execute();
			}
		}

		if ($userModel->usr_gender == '' && $userData['gender'] != '')
		{
			$genderList				 = Users::model()->reverseGenderList;
			$userModel->usr_gender	 = $genderList[$userData['gender']];
		}


		$response = [];
		if ($userModel->save())
		{
			Logger::create("user data saved ", CLogger::LEVEL_INFO);
			$response['user_id'] = $userModel->user_id;
			$success			 = true;
			$msg				 = "Link successful.";
		}

		$picdata = $userModel->usr_profile_pic;
		if (((!file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic)) || $userModel->usr_profile_pic == '' || filesize(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic) == 0) && $picdata != '')
		{
			$arrContextOptions = array("ssl" => array(
					"verify_peer"		 => false,
					"verify_peer_name"	 => false,
				),);
			if ($userModel->usr_profile_pic_path == '' && $picdata != '')
			{
				$userModel->usr_profile_pic_path = $picdata;
			}
			if ($userModel->usr_profile_pic_path)
			{
				$profilePic = strtolower('images/profiles/' . $userModel->user_id . str_replace(' ', '', $userModel->usr_name)) . rand(10000, 99999) . '.jpg';

				file_put_contents(
						$profilePic, file_get_contents($userModel->usr_profile_pic_path, false, stream_context_create($arrContextOptions))
				);
				$userModel->usr_profile_pic = '/' . $profilePic;
			}
			if ($userModel->validate())
			{
				$userModel->save();
			}
		} Logger::create("User registered id :: " . $response['user_id'], CLogger::LEVEL_INFO);

		result_error:
		$response['success'] = $success;
		$response['msg']	 = $msg;
		$response['email']	 = $email;
		return $response;
	}

	public function linkNewAppUser($linkeduserid = 0, $social = 0, $userData, $flag1)
	{
		//$social ==1 case:vendor driver. statement: name password email will not modified in user table
		#$provider			 = Yii::app()->request->getParam('provider');
		#$process_sync_data	 = Yii::app()->request->getParam('data', '');
		//////////////
		if ($flag1 == 'vendor-app')
		{
			$provider = $userData->provider;
		}
		//////////////
		$success = false;
		$email	 = $userData->email;

		$userModel = Users::model()->findByEmail($email);

		if (!empty($userModel))
		{

			if ($linkeduserid > 0)
			{

				$userModel = Users::model()->findByPk($linkeduserid);
			}
			else
			{
				if ($social != 1)
				{// incase of vendor or driver pasword should not be added or modified
					$userModel					 = new Users();
					$pass						 = uniqid(rand(), TRUE);
					$userModel->usr_password	 = $pass;
					$userModel->new_password	 = $pass;
					$userModel->repeat_password	 = $pass;
				}
			}
		}
		else
		{

			$newPass22						 = rand(100000, 999999);
			$userModel						 = new Users();
			$userModel->usr_password		 = $newPass22;
			$userModel->new_password		 = $newPass22;
			$userModel->repeat_password		 = $newPass22;
			$userModel->usr_email			 = $email;
			$userModel->usr_name			 = null;
			$userModel->usr_lname			 = null;
			$userModel->usr_create_platform	 = 1;
			$userModel->usr_mobile			 = null;
			$userModel->save();
		}
		$oauthData				 = [];
		$sessData				 = [];
		$oauthData['identifier'] = $userData->identifier;
		#$userModel						 = new Users();
		if ($provider == 'Google')
		{
			if ($social != 1)
			{// incase of vendor or driver no data will modified for user table 
				$userModel->usr_email	 = $userData->email;
				$userModel->usr_name	 = $userData->givenName;
				$userModel->usr_lname	 = $userData->familyName;
				if (($userModel->usr_mobile == '' || $userModel->usr_mobile == null ) && $userData->phone != '')
				{
					$userModel->usr_mobile = str_replace(' ', '', $userData->phone);
				}
			}
			$userModel->usr_create_platform = 1;
//			$userModel->usr_country			 = $userData['country'];
//			$userModel->usr_mobile			 = $userData['phone'];
//			$userModel->usr_zip				 = $userData['zip'];

			$oauthData['displayName']	 = $userData->displayName;
			$oauthData['firstName']		 = $userData->givenName;
			$oauthData['lastName']		 = $userData->familyName;
			$oauthData['phone']			 = $userData->phone;
			$oauthData['email']			 = $userData->email;
			$oauthData['gender']		 = $userData->gender | '';

			$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
			$sessData['hauth_session.google.token.expires_at']	 = serialize($userData->expirationTime);
			$sessData['hauth_session.google.is_logged_in']		 = serialize('1');
		}
		if ($provider == 'Facebook')
		{
			if ($social != 1)
			{// incase of vendor or driver no data will modified for user table 
				$userModel->usr_email	 = $userData->email;
				$userModel->usr_name	 = $userData->first_name;
				$userModel->usr_lname	 = $userData->last_name;
			}
			if ($userModel->usr_gender == '' && $userData->gender != '')
			{
				$userModel->usr_gender = $userData->gender;
			}
			$oauthData['firstName']	 = $userData->first_name;
			$oauthData['lastName']	 = $userData->last_name;
			$oauthData['gender']	 = $userData->gender;
			$oauthData['email']		 = $userData->email;

			$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
		}
		if (($userModel->usr_profile_pic == '' || $userModel->usr_profile_pic == null ) && $profile_image_url != '')
		{
			$userModel->usr_profile_pic = $profile_image_url;
		}
		$userModel->usr_create_platform	 = 1;
		$userModel->usr_email_verify	 = 1;
		$userModel->usr_name			 = $oauthData['firstName'];
		$userModel->usr_lname			 = $oauthData['lastName'];
		$userModel->usr_email			 = $oauthData['email'];
		#print_r($userModel->attributes);exit;
		if (!$userModel->save())
		{
			$msg = json_encode($userModel->getErrors('usr_email')[0], true);
			goto result_error;
		}
		$isSocialLinked = Users::model()->checkSocialLinking($userModel->user_id, $provider, $userData->identifier);
		if (!$isSocialLinked)
		{
			$tablePrefix = Yii::app()->db->tablePrefix;
			$oauthtable	 = $tablePrefix . 'user_oauth';
			$userid		 = $userModel->user_id;
			$identifier	 = $userData->identifier;
			$sql		 = "select * from $oauthtable where  identifier='$identifier' AND provider = '$provider'";
			$val		 = DBUtil::queryRow($sql);
			if (!$val)
			{
				$profile_cache	 = serialize($oauthData);
				$session_data	 = serialize($sessData);
				$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
					VALUES ('$userid', '$provider', '$identifier','$profile_cache' , '$session_data')";
				$resultRow		 = DBUtil::command($sql)->execute();
			}
			else
			{

				$msg = "Already linked with other user";
				goto result_error;
			}
		}

		if ($userModel->usr_gender == '' && $userData->gender != '')
		{
			$genderList				 = Users::model()->reverseGenderList;
			$userModel->usr_gender	 = $genderList[$userData->gender];
		}


		$response = [];
		if ($userModel->save())
		{
			Logger::create("user data saved ", CLogger::LEVEL_INFO);
			$response['user_id'] = $userModel->user_id;
			$success			 = true;
			$msg				 = "User linked successfully.";
		}

		$picdata = $userModel->usr_profile_pic;
		if (((!file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic)) || $userModel->usr_profile_pic == '' || filesize(PUBLIC_PATH . DIRECTORY_SEPARATOR . $userModel->usr_profile_pic) == 0) && $picdata != '')
		{
			$arrContextOptions = array("ssl" => array(
					"verify_peer"		 => false,
					"verify_peer_name"	 => false,
				),);
			if ($userModel->usr_profile_pic_path == '' && $picdata != '')
			{
				$userModel->usr_profile_pic_path = $picdata;
			}
			if ($userModel->usr_profile_pic_path)
			{
				$profilePic = strtolower('images/profiles/' . $userModel->user_id . str_replace(' ', '', $userModel->usr_name)) . rand(10000, 99999) . '.jpg';

				file_put_contents(
						$profilePic, file_get_contents($userModel->usr_profile_pic_path, false, stream_context_create($arrContextOptions))
				);
				$userModel->usr_profile_pic = '/' . $profilePic;
			}
			if ($userModel->validate())
			{
				$userModel->save();
			}
		} Logger::create("User registered id :: " . $response['user_id'], CLogger::LEVEL_INFO);

		result_error:
		$response['success'] = $success;
		$response['msg']	 = $msg;
		$response['email']	 = $email;
		return $response;
	}

	#deprecated
	#new function linkExistingUser

	//public function linkExistingAppUser($linkeduserid = 0)

	public function linkExistingAppUser($linkeduserid = 0, $provider_name = 0, $sync_Data = 0, $flag1 = 0, $contactId = 0)
	{

		$provider			 = Yii::app()->request->getParam('provider');
		$process_sync_data	 = Yii::app()->request->getParam('data', '');

		//////////////
		if ($flag1 == 'vendor-app')
		{
			$provider			 = $provider_name;
			$process_sync_data	 = $sync_Data;
		}
		//////////////


		$userData	 = CJSON::decode($process_sync_data, true);
		$identifier	 = $userData['id'];
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';

		$sql	 = "select count(*) as cntuser from $oauthtable where  identifier='$identifier' AND user_id = $linkeduserid";
		//echo $sql;exit;
		$result	 = DBUtil::command($sql)->queryScalar();
		//echo $result;exit;
		if ($result > 0)
		{

			$success = false;
			$msg	 = "Already linked try to login with the same account.";
			goto result_error;
		}
		else
		{
			$sql1	 = "select count(*) as cntuser from $oauthtable where  identifier='$identifier' AND user_id != $linkeduserid";
			$result1 = DBUtil::command($sql1)->queryScalar();
			//echo $result1;exit;
			if ($result1 > 0)
			{
				$success = false;
				$msg	 = "Already linked with other user.";
				goto result_error;
			}
			else
			{
				if ($provider == 'Google')
				{
					$oauthData['displayName']							 = $userData['displayName'];
					$oauthData['firstName']								 = $userData['givenName'];
					$oauthData['lastName']								 = $userData['familyName'];
					$oauthData['phone']									 = $userData['phone'];
					$oauthData['email']									 = $userData['email'];
					$oauthData['gender']								 = $userData['gender'] | '';
					$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
					$sessData['hauth_session.google.token.expires_at']	 = serialize($userData['expirationTime']);
					$sessData['hauth_session.google.is_logged_in']		 = serialize('1');
				}
				if ($provider == 'Facebook')
				{
					$oauthData['firstName']							 = $userData['first_name'];
					$oauthData['lastName']							 = $userData['last_name'];
					$oauthData['gender']							 = $userData['gender'];
					$oauthData['email']								 = $userData['email'];
					$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
				}

				$profile_cache	 = serialize($oauthData);
				$session_data	 = serialize($sessData);
				$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
					VALUES ('$linkeduserid', '$provider', '$identifier','$profile_cache' , '$session_data')";
				//echo $sql; exit;
				$resultRow		 = DBUtil::command($sql)->execute();
				if ($userData['email'] != NULL)
				{
					$contactEmail	 = ContactEmail::model()->findEmailIdByEmail($userData['email']);
					$email			 = $userData['email'];
					if (count($contactEmail) == 0 && $contactId != "")
					{
						$sql		 = "INSERT INTO contact_email (`eml_contact_id`,`eml_email_address`,`eml_is_verified`,`eml_is_primary`,`eml_active`) VALUES ('$contactId','$email',1,0,1)";
						$resultRow	 = DBUtil::command($sql)->execute();
					}
				}
				$success = true;
				$msg	 = "User linked successfully.";
			}
		}

		result_error:
		$result = ['success' => $success, 'msg' => $msg];
		return $result;
	}

	public function linkExistingUser($userId, $userData, $flag1, $contactId)
	{


		#$process_sync_data	 = Yii::app()->request->getParam('data', '');
		//////////////

		if ($flag1 == 'vendor-app')
		{
			$provider = $userData->provider;
			#$process_sync_data	 = $sync_Data;
		}
		//////////////
		#$userData	 = CJSON::decode($process_sync_data, true);
		$identifier	 = $userData->identifier;
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';

		$sql = "select count(*) as cntuser from $oauthtable where  identifier='$userData->identifier' AND user_id = $userId";

		$result = DBUtil::command($sql)->queryScalar();

		if ($result > 0)
		{

			$success = false;
			$msg	 = "Already linked try to login with the same account.";
			goto result_error;
		}
		else
		{
			$sql1	 = "select count(*) as cntuser from $oauthtable where  identifier='$userData->identifier' AND user_id != $userId";
			$result1 = DBUtil::command($sql1)->queryScalar();
			//echo $result1;exit;
			if ($result1 > 0)
			{
				$success = false;
				$msg	 = "Already linked with other user.";
				goto result_error;
			}
			else
			{
				if ($provider == 'Google')
				{
					$oauthData['displayName']							 = $userData->displayName;
					$oauthData['firstName']								 = $userData->givenName;
					$oauthData['lastName']								 = $userData->familyName;
					$oauthData['phone']									 = $userData->phone;
					$oauthData['email']									 = $userData->email;
					$oauthData['gender']								 = $userData->gender | '';
					$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
					$sessData['hauth_session.google.token.expires_at']	 = serialize($userData->expirationTime);
					$sessData['hauth_session.google.is_logged_in']		 = serialize('1');
				}
				if ($provider == 'Facebook')
				{
					$oauthData['firstName']							 = $userData->first_name;
					$oauthData['lastName']							 = $userData->last_name;
					$oauthData['gender']							 = $userData->gender;
					$oauthData['email']								 = $userData->email;
					$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
				}

				$profile_cache	 = serialize($oauthData);
				$session_data	 = serialize($sessData);
				$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
					VALUES ($userId, '$provider', '$identifier','$profile_cache' , '$session_data')";
				//echo $sql; exit;
				$resultRow		 = DBUtil::command($sql)->execute();
				if ($userData->email != NULL)
				{
					$contactEmail = ContactEmail::model()->findEmailIdByEmail($userData->email);

					$email = $userData->email;
					if (count($contactEmail) == 0 && $contactId <> "")
					{
						$sql		 = "INSERT INTO contact_email (`eml_contact_id`,`eml_email_address`,`eml_is_verified`,`eml_is_primary`,`eml_active`) VALUES ('$contactId','$email',1,0,1)";
						$resultRow	 = DBUtil::command($sql)->execute();
					}
				}
				$success = true;
				$msg	 = "User linked successfully.";
			}
		}

		result_error:
		$result = ['success' => $success, 'msg' => $msg];
		return $result;
	}

	public function linkAppDriver($linkeduserid = 0, $provider, $process_sync_data)
	{

		//$provider			 = Yii::app()->request->getParam('provider');
		//$process_sync_data	 = Yii::app()->request->getParam('data', '');
		$userData		 = CJSON::decode($process_sync_data, true);
		$identifier		 = $userData['id'];
		$tablePrefix	 = Yii::app()->db->tablePrefix;
		$oauthtable		 = $tablePrefix . 'user_oauth';
		$driv_user_id	 = 0;
		$email			 = $userData['email'];
		$success		 = false;

		//echo $linkeduserid; exit;
//		if ($userData['email'] != NULL)
//		{
//			$contactEmail = ContactEmail::model()->findEmailIdByEmail($userData['email']);
//			if (count($contactEmail) == 0)
//			{
//				$sql		 = "INSERT INTO contact_email (`eml_contact_id`,`eml_email_address`,`eml_is_verified`,`eml_is_primary`,`eml_active`) VALUES ('$contactEmail->eml_contact_id','$email',1,0,1)";
//				$resultRow	 = DBUtil::command($sql)->execute();
//			}
//		}

		if ($linkeduserid != "")
		{
			$sql	 = "select count(*) as cntuser from $oauthtable where  identifier='$identifier' AND user_id = $linkeduserid";
			$result	 = DBUtil::command($sql)->queryScalar();
			if ($result > 0)
			{
				$success = false;
				$msg	 = "Already linked with same user.";
				goto result_error;
			}
			else
			{
				$sql	 = "select * from $oauthtable where  identifier='$identifier' AND user_id != $linkeduserid";
				$result	 = DBUtil::queryRow($sql);
				if (count($result) > 1)
				{
					$uid	 = $result['user_id'];
					$sql	 = "select *  from drivers where  drv_user_id = $uid and drv_id = drv_ref_code ";
					$result1 = DBUtil::queryRow($sql);

					if (count($result1) > 1)
					{
						$success = false;
						$msg	 = "Already linked with other user.";
						goto result_error;
					}
					else
					{
						$driv_user_id	 = $uid;
						$success		 = true;
						$msg			 = "User Linked successfully.";
						goto result_error;
					}
				}
				else
				{
					// inserted into imp outh table

					if ($provider == 'Google')
					{
						$oauthData['displayName']							 = $userData['displayName'];
						$oauthData['firstName']								 = $userData['givenName'];
						$oauthData['lastName']								 = $userData['familyName'];
						$oauthData['phone']									 = $userData['phone'];
						$oauthData['email']									 = $userData['email'];
						$oauthData['gender']								 = $userData['gender'] | '';
						$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
						$sessData['hauth_session.google.token.expires_at']	 = serialize($userData['expirationTime']);
						$sessData['hauth_session.google.is_logged_in']		 = serialize('1');
					}
					if ($provider == 'Facebook')
					{
						$oauthData['firstName']							 = $userData['first_name'];
						$oauthData['lastName']							 = $userData['last_name'];
						$oauthData['gender']							 = $userData['gender'];
						$oauthData['email']								 = $userData['email'];
						$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
					}

					$profile_cache	 = serialize($oauthData);
					$session_data	 = serialize($sessData);
					$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
						VALUES ('$linkeduserid', '$provider', '$identifier','$profile_cache' , '$session_data')";

					//echo $sql; exit;
					$resultRow = DBUtil::command($sql)->execute();

					$driv_user_id	 = $linkeduserid;
					$success		 = true;
					$msg			 = "User linked successfully.";
				}
			}
		}
		else
		{
			$sql	 = "select *  from $oauthtable where  identifier='$identifier'";
			$result	 = DBUtil::queryRow($sql);
			if ($result > 1)
			{
				$uid	 = $result['user_id'];
				$sql	 = "select *  from drivers where  drv_user_id = $uid and drv_id = drv_ref_code ";
				$result1 = DBUtil::queryRow($sql);

				if (count($result1) > 1)
				{
					$success = false;
					$msg	 = "Already linked with other user.";
					goto result_error;
				}
				else
				{
					$driv_user_id	 = $uid;
					$success		 = true;
					$msg			 = "User Linked successfully.";
					goto result_error;
				}
			}
			else
			{

				$sql	 = "select *  from users where  usr_email='$email'";
				$result2 = DBUtil::queryRow($sql);
				if (count($result2) > 1)
				{
					$user_ID = $result2['user_id'];
				}
				else
				{
					$newPass22						 = rand(100000, 999999);
					$userModel						 = new Users();
					$userModel->usr_password		 = $newPass22;
					$userModel->new_password		 = $newPass22;
					$userModel->repeat_password		 = $newPass22;
					$userModel->usr_email			 = $email;
					$userModel->usr_name			 = '.';
					$userModel->usr_lname			 = '.';
					$userModel->usr_create_platform	 = 1;
					$userModel->usr_mobile			 = null;
					$userModel->save();

					$user_ID = $userModel->user_id;
				}

				if ($provider == 'Google')
				{
					$oauthData['displayName']							 = $userData['displayName'];
					$oauthData['firstName']								 = $userData['givenName'];
					$oauthData['lastName']								 = $userData['familyName'];
					$oauthData['phone']									 = $userData['phone'];
					$oauthData['email']									 = $userData['email'];
					$oauthData['gender']								 = $userData['gender'] | '';
					$sessData['hauth_session.google.token.expires_in']	 = serialize('3600');
					$sessData['hauth_session.google.token.expires_at']	 = serialize($userData['expirationTime']);
					$sessData['hauth_session.google.is_logged_in']		 = serialize('1');
				}
				if ($provider == 'Facebook')
				{
					$oauthData['firstName']							 = $userData['first_name'];
					$oauthData['lastName']							 = $userData['last_name'];
					$oauthData['gender']							 = $userData['gender'];
					$oauthData['email']								 = $userData['email'];
					$sessData['hauth_session.facebook.is_logged_in'] = serialize('1');
				}

				$userModel				 = Users::model()->findByPk($user_ID);
				$userModel->usr_name	 = $oauthData['firstName'];
				$userModel->usr_lname	 = $oauthData['lastName'];
				$userModel->save();

				$profile_cache	 = serialize($oauthData);
				$session_data	 = serialize($sessData);
				$sql			 = "INSERT INTO $oauthtable (`user_id`, `provider`, `identifier`, `profile_cache`, `session_data`) 
					VALUES ('$user_ID', '$provider', '$identifier','$profile_cache' , '$session_data')";

				//echo $sql; exit;
				$resultRow = DBUtil::command($sql)->execute();

				$success		 = true;
				$msg			 = "User linked successfully.";
				$driv_user_id	 = $user_ID;
			}
		}

		result_error:
		$result = ['success' => $success, 'msg' => $msg, 'driv_user_id' => $driv_user_id, 'email' => $email];
		return $result;
	}

	/**
	 * 
	 * @param Users $model
	 * @return Users
	 * @throws Exception
	 */
	public static function login($model, $forceSignUp = false)
	{
		/* @var $userModel Users */
		$userModel = self::validateInstance($model, $forceSignUp);
		if (!$userModel)
		{
			throw new Exception("Unable to authenticate.", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		$identity = $userModel->authenticate($userModel->user_id, 1, $userModel);
		$userModel->loginIdentity($identity);
		return $userModel;
	}

	/**
	 * 
	 * @param string $currentVersion
	 * @param string $activeVersion
	 * @param string $token
	 * @param integer $userId
	 * @return type
	 * @throws Exception
	 */
	public static function validateUser($currentVersion, $activeVersion, $tokens, $userId)
	{


		$isVersionCheck = AppTokens::validateVersion($currentVersion, $activeVersion);
		if (!$isVersionCheck)
		{
			throw new Exception("Invalid version : ", ReturnSet::ERROR_INVALID_DATA);
		}
		$isValidateToken = AppTokens::validateToken($tokens);
		if (!$isValidateToken)
		{
			throw new Exception("Unauthorised user : ", ReturnSet::ERROR_INVALID_DATA);
		}

		/* @var $result AppTokens */
		$result = AppTokens::validatePlatform($userId, $tokens);

		if (!$result)
		{
			throw new Exception("Unauthorised Platform : ", ReturnSet::ERROR_INVALID_DATA);
		}
		return $result;
	}

	/**
	 * 
	 * @param string $userName
	 * @param string $password
	 * @return boolean
	 */
	public static function validateModel($userName, $password)
	{
		$criteria	 = new CDbCriteria();
		$columns	 = [
			'usr_email'	 => $userName,
			'usr_mobile' => $userName
		];
		$criteria->addColumnCondition($columns, 'OR', 'AND');
		$model		 = Users::model()->find($criteria);
		if (!$model)
		{
			return false;
		}

		if ($model->usr_password != md5($password))
		{
			return false;
		}
		return $model;
	}

	/**
	 * 
	 * @param integer $identifier
	 * @param integer $provider
	 * @return boolean
	 */
	public static function getModelBySocialAccount($identifier, $provider)
	{
		$row = self::getBySocialAccount($identifier, $provider);
		if (!$row)
		{
			return false;
		}
		$model = Users::model()->findByPk($row['user_id']);
		return $model;
	}

	/**
	 * 
	 * @param integer $entityId
	 * @param integer $entityType
	 * @return \UserIdentity
	 * @throws Exception

	  public function authenticate($entityId, $entityType)
	  {
	  $identity			 = new UserIdentity($userModel->user_email, $userModel->usr_password);
	  $identity->userId	 = $userModel->user_id;
	  if (!$identity->authenticate())
	  {
	  throw new Exception("Unable to authenticate", 401);
	  }
	  $model = self::getByUserId($userModel->user_id);
	  $identity->setEntityID($entityId);
	  $identity->setUserType($entityType);
	  return $identity;
	  } */

	/**
	 * 
	 * @param integer $entityId
	 * @param integer $entityType
	 * @return \UserIdentity
	 * @throws Exception
	 */
	public function authenticate($entityId, $entityType, $model)
	{
		$userModel			 = $model;
		$identity			 = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		$identity->userId	 = $userModel->user_id;
		if (!$identity->authenticate())
		{
			throw new Exception("Unable to authenticate", ReturnSet::ERROR_UNAUTHORISED);
		}
		//$model = self::getByUserId($userModel->user_id);
		$identity->setEntityID($entityId);
		$identity->setUserType($entityType);
		return $identity;
	}

	/**
	 * 
	 * @param SocialAuth | Users $model
	 * @param boolean $forceSignup
	 * @return boolean | Users $model
	 */
	public static function validateInstance($model, $forceSignup = false)
	{
		if ($model instanceof SocialAuth)
		{
			$socialAuthModel = clone $model;

			$socialAuthModel->provider		 = null;
			$socialAuthModel->isNewRecord	 = false;
			$result							 = $socialAuthModel->authenticate($model->provider);

			if ($result == null || ($result->isNewRecord && !$forceSignup))
			{
				return false;
			}
			if ($forceSignup && $result->isNewRecord)
			{
				$result = self::addSocialUser($model, null);
			}
//            skipSocialAuth:
			$userModel = Users::model()->findByPk($result->user_id);
		}
		else if ($model instanceof Users)
		{
			$userModel = clone $model;
			$userModel->setScenario("userLogin");
			//$userModel->scenario	 = 'userLogin';
			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$contactId	 = $userModel->usr_contact_id;
			$userId		 = ContactProfile::getUserId($contactId);
			if (!$userId)
			{
				$userId = Users::getByContactId($contactId);
			}
			if (!$userId)
			{
				$userModel = Users::createbyContact($contactId);
			}
			else
			{
				$userModel = Users::model()->findByPk($userId);
			}

			if ($forceSignup)
			{
				//$userModel = Users::model()->getByEmail($model->usr_email);
				if ($userModel->usr_password != $model->usr_password)
				{
					return false;
				}
			}
		}
		return $userModel;
	}

	public static function socialLinking($data)
	{
		$userData			 = CJSON::decode($process_sync_data, true);
		$email				 = $data['email'];
		$result['success']	 = true;

		$userModel = Users::model()->getByEmail($data['email']);
		if (!$userModel)
		{
			throw new Exception('User not found', ReturnSet::ERROR_INVALID_DATA);
		}
		else
		{
			$isSocialLinked = Users::model()->checkSocialLinking($userModel->user_id, $data['provider']);
			Logger::create("isSocialLinked :: " . ($isSocialLinked), CLogger::LEVEL_INFO);
			if (!$isSocialLinked)
			{
				$result	 = Users::model()->linkAppUser($userModel->user_id);
				$userid	 = $result['user_id'];
				Logger::create("isSocialLinked result :: " . $result['success'], CLogger::LEVEL_INFO);
			}
			if ($result['success'])
			{

				$userModel	 = Users::model()->findByPk($userid);
				$email		 = $userModel->usr_email;
				$md5password = $userModel->usr_password;
			}
		}
	}

	/**
	 * 
	 * @param UserIdentity $identity
	 * @return boolean
	 */
	public function loginIdentity(UserIdentity $identity)
	{
		/* @var $webUser GWebUser */
		$webUser = Yii::app()->user;
		$webUser->login($identity);
		return true;
	}

	public function checkSocialLinking($userid, $provider = '', $identifier = '', $returnProvider = false)
	{
		if ($userid > 0)
		{
			$tablePrefix = Yii::app()->db->tablePrefix;
			$oauthtable	 = $tablePrefix . 'user_oauth';
			$sql		 = "SELECT * from $oauthtable where user_id = $userid";
			$provider	 = trim($provider);
			if ($identifier != '')
			{
				$sql .= " AND identifier = '$identifier'";
			}
			if ($provider != '' && in_array($provider, ['Google', 'Facebook']))
			{
				$sql .= " AND provider = '$provider'";
			}


			$result = DBUtil::queryAll($sql);

			if (count($result) > 0)
			{
				if ($returnProvider)
				{
					$resultData = [];
					foreach ($result as $key => $value)
					{
						$resultData[$value['provider']] = $value;
					}
					$return = ['success' => true, 'data' => $resultData];
					return $return;
				}
				return true;
			}
		}
		return false;
	}

	public function checkSocialLinkAlreadyExist($identifier)
	{
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';
		$sql		 = "select * from $oauthtable where  identifier='$identifier'";
		$val		 = DBUtil::queryRow($sql);
		return $val;
	}

	/**
	 * @param integer $userId
	 * @return array
	 */
	public static function getUserContact($userId)
	{
		$sql = "SELECT
					users.user_id AS userUniqueId,
					cttPrimary.ctt_id AS contactId,
					IFNULL(
						cpPrimary.cr_is_consumer,
						cp.cr_is_consumer
					) AS userId
				FROM   `users`
				INNER JOIN contact_profile cp ON
					cp.cr_is_consumer = users.user_id AND cp.cr_status = 1
				INNER JOIN contact ctt ON
					ctt.ctt_id = cp.cr_contact_id
				INNER JOIN contact cttPrimary ON
					ctt.ctt_ref_code = cttPrimary.ctt_id
				INNER JOIN contact_profile cpPrimary ON
					cpPrimary.cr_contact_id = cttPrimary.ctt_id
				WHERE 1 AND users.user_id = :userId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ["userId" => $userId]);
	}

	public static function getBySocialAccount($identifier)
	{
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';
		$sql		 = "SELECT * from $oauthtable uoa
							INNER JOIN users u ON uoa.user_id=u.user_id
			where  identifier='$identifier'";
		$val		 = DBUtil::queryRow($sql);
		return $val;
	}

	public function findByRouteCities($pickupDate, $frmCity, $toCity, $user_id, $bcb_id)
	{
		$existingGender	 = '';
		$success		 = 0;
		$sql			 = "SELECT
					users.user_id,
					(
						CASE users.usr_gender WHEN '1' THEN 'Male' WHEN '2' THEN 'Female' ELSE 'NULL'
					END
				) AS gender, users.usr_gender,
				booking.bkg_bcb_id,
				booking.bkg_status
				FROM
					`booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1 
				INNER JOIN `booking_user` ON booking.bkg_id=booking_user.bui_bkg_id
				INNER JOIN `users` ON users.user_id = booking_user.bkg_user_id AND users.usr_active = 1
				INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
				WHERE
					booking.bkg_pickup_date = '$pickupDate' AND booking.bkg_from_city_id = '$frmCity' AND booking.bkg_to_city_id = '$toCity' AND booking_invoice.bkg_promo1_code = 'FLATRE1' AND booking.bkg_status = 2 AND booking.bkg_bcb_id = '$bcb_id'
				ORDER BY
					booking.bkg_id
				DESC
				LIMIT 0, 1";
		$row			 = DBUtil::queryRow($sql);

		$usrModel = Users::model()->findByPk($user_id);
		if (($row['usr_gender'] > 0) && $usrModel->usr_gender != $row['usr_gender'])
		{
			$success		 = 1;
			$existingGender	 = $row['gender'];
		}

		return ['success' => $success, 'gender' => $existingGender];
	}

	/**
	 * 
	 * @param integer $userId
	 * @return array
	 */
	public static function getBookingsByUserId($userId)
	{
		$sql = "SELECT
						booking.bkg_id,
						booking.bkg_booking_id,
						booking.bkg_pickup_date,
						booking.bkg_trip_duration,
						bkg_ride_complete,
						bkg_ride_start,
						bkg_sos_sms_trigger,
						DATE_ADD(NOW(), INTERVAL 3 HOUR) AS now_after_3hrs,
						DATE_ADD(
							bkg_pickup_date,
							INTERVAL bkg_trip_duration MINUTE
						) AS tripCompletionTime,
						IF(
							DATE_ADD(
								bkg_pickup_date,
								INTERVAL(bkg_trip_duration + 120) MINUTE
							) < NOW(), 1, 0
						) AS iscompleted
						FROM `booking`
						INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id 
						INNER JOIN `booking_track` ON booking.bkg_id = booking_track.btk_bkg_id AND booking_track.bkg_ride_complete = 0 AND booking_track.bkg_is_no_show = 0
						INNER JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
						WHERE
							booking.bkg_status = 5 
							AND booking.bkg_active = 1 
							AND booking_user.bkg_user_id = '$userId'
							AND booking_track.bkg_trip_start_time < NOW()
							AND booking_track.bkg_ride_start = 1
							AND booking_track.bkg_trip_end_time IS NULL
						ORDER BY
							booking.bkg_pickup_date
						DESC 
						LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param integer $userId
	 * @return integer
	 */
	public static function isSosContactList($userId)
	{
		// bkg_id  whose time is half hour before pickup time 
		$sql	 = "SELECT `bkg_id`,bkg_pickup_date ,booking.bkg_trip_duration,IF(NOW()>=date_sub(bkg_pickup_date,INTERVAL 30 minute),1,0)  AS isStarted,IF(NOW()>= DATE_ADD(bkg_pickup_date, INTERVAL bkg_trip_duration MINUTE),1,0) AS tripCompletionTime,booking_track.bkg_ride_complete,bkg_ride_start FROM `booking`
			 INNER JOIN `booking_track` ON booking.bkg_id = booking_track.btk_bkg_id 
			 INNER JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id
			 WHERE  booking.bkg_active = 1 AND booking.bkg_status IN(5) AND bkg_ride_complete = 0  AND  booking_user.bkg_user_id ='$userId' ORDER BY
							booking.bkg_pickup_date DESC ";
		$data	 = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($data as $recordset)
		{
			//if pickup time is after 30 min OR ride is one the way 
			if (($recordset['isStarted'] == 1 || $recordset['bkg_ride_start'] == 1) && $recordset['tripCompletionTime'] == 0)
			{
				$result			 = $this->getSosContactList($userId);
				//has sosconatactList and ride is on the way  and not completed
				$sosContactAlert = (($result != '' || $result != null)) ? 2 : 1;
			}
			//if completed  return 0
			else
			{
				$sosContactAlert = 0;
			}
		}
		return $sosContactAlert;
	}

	public static function findLastBookingReviewById($userId)
	{
		$isRating		 = 1;
		$lastBkgId		 = null;
		$lastBookingId	 = null;
		$lastRoute		 = null;
		$sql			 = "SELECT DISTINCT
					booking.bkg_id,booking.bkg_route_city_names,
					booking.bkg_booking_id,
					CONCAT(c1.cty_name,' - ',c2.cty_name) as route,
					IF(ratings.rtg_customer_date IS NOT NULL AND ratings.rtg_id > 0, '1', '0') AS isRating
				FROM
					`booking` 
				INNER JOIN `cities` c1 ON c1.cty_id=booking.bkg_from_city_id 
				INNER JOIN `cities` c2 ON c2.cty_id=booking.bkg_to_city_id	
				INNER JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id
				LEFT JOIN `ratings` ON ratings.rtg_booking_id = booking.bkg_id
				WHERE
					booking.bkg_create_date IN(
						SELECT
							MAX(booking.bkg_create_date)
							FROM `booking`
							INNER JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id AND booking.bkg_active = 1
							INNER JOIN `booking_track` ON booking.bkg_id = booking_track.btk_bkg_id
							WHERE booking_user.bkg_user_id = '$userId' AND 
							(
								booking.bkg_status IN(6, 7) OR ( booking.bkg_status IN(5) AND booking_track.bkg_ride_complete = 1 )
							)
						ORDER BY
							booking.bkg_id
						DESC
				) AND booking_user.bkg_user_id = '$userId' AND booking.bkg_status IN (6,7)";
		$row			 = DBUtil::queryRow($sql, DBUtil::SDB());
		if ($row['bkg_id'] > 0)
		{
			$lastBkgId		 = $row['bkg_id'];
			$lastBookingId	 = $row['bkg_booking_id'];
			$lastRoute		 = $row['route'];
			$lastRouteName	 = $row['bkg_route_city_names'];
			$isRating		 = $row['isRating'];
		}
		$reviewStatus = ['bkg_id'		 => $lastBkgId,
			'bkg_booking_id' => $lastBookingId,
			'route'			 => $lastRoute,
			'lastRouteName'	 => $lastRouteName,
			'isRated'		 => (int) $isRating];
		return $reviewStatus;
	}

	public static function info($userId)
	{
		$totalTrips		 = 0;
		$firstTripDate	 = '';
		$lastTripDate	 = '';
		$rating			 = '';
		if (isset($userId) && $userId > 0)
		{
			$sql = "SELECT * FROM `user_stats` WHERE user_stats.urs_user_id=" . $userId . "";
			$row = DBUtil::queryRow($sql);
			if (isset($row['urs_id']) && $row['urs_id'] > 0)
			{
				$totalTrips		 = $row['urs_total_trips'];
				$firstTripDate	 = $row['urs_first_date'];
				$lastTripDate	 = $row['urs_last_date'];
				$rating			 = $row['urs_rating'];
			}
		}
		return ['totalTrips'	 => $totalTrips,
			'firstTripDate'	 => $firstTripDate,
			'lastTripDate'	 => $lastTripDate,
			'rating'		 => $rating];
	}

	public function changePassword($userId, $oldPassword, $newPassword, $rePassword)
	{
		$model->old_password	 = $oldPassword;
		$model->new_password	 = $newPassword;
		$model->repeat_password	 = $rePassword;
		$model->scenario		 = 'change';
		$userModel				 = Users::model()->findByPk($userId);
		$userPassword			 = $userModel->attributes['usr_password'];
		if ($userPassword == md5($model->old_password))
		{
			if ($model->new_password == $model->repeat_password)
			{
				$userModel->usr_password = md5($model->new_password);
				if ($userModel->save())
				{
					Users::model()->logoutByUserId($userId);
					$status	 = 'true';
					$message = 'Password Changed';
				}
				else
				{
					$status	 = 'false';
					$message = 'Password Not Changed';
				}
			}
			else
			{
				$status	 = 'false';
				$message = 'New password and confirm password should be same';
			}
		}
		else
		{

			$status	 = 'false';
			$message = 'Old Password not matching';
		}

		$result = array('type'		 => 'raw',
			'message'	 => $message,
			'status'	 => $status);

		return $result;
	}

	/**
	 * 
	 * @param integer $userId
	 * @return boolean
	 */
	public function updateDetails($userId = 0)
	{
		$success		 = false;
		$rows			 = UserStats::getList($userId);
		$cntInsert		 = 0;
		$cntUpdate		 = 0;
		$cntNotUpdate	 = 0;
		foreach ($rows as $row)
		{
			$model = UserStats::model()->getbyUserId($row['user_id']);
			if (!$model)
			{
				$model = new UserStats();
				$cntInsert++;
			}
			else
			{
				$cntUpdate++;
			}
			$model->urs_user_id			 = $row['user_id'];
			$model->urs_first_date		 = $row['first_trip_date'];
			$model->urs_last_date		 = $row['last_trip_date'];
			//	$model->urs_total_trips		 = $row['total_trips'];
			$model->urs_rating			 = $row['updateReview'];
			$model->urs_active			 = 1;
			$model->urs_OW_Count		 = $row['OW_Count'];
			$model->urs_RT_Count		 = $row['RT_Count'];
			$model->urs_AT_Count		 = $row['AT_Count'];
			$model->urs_PT_Count		 = $row['PT_Count'];
			$model->urs_FL_Count		 = $row['FL_Count'];
			$model->urs_SH_Count		 = $row['SH_Count'];
			$model->urs_CT_Count		 = $row['CT_Count'];
			$model->urs_DR_4HR_Count	 = $row['DR_4HR_Count'];
			$model->urs_DR_8HR_Count	 = $row['DR_8HR_Count'];
			$model->urs_DR_12HR_Count	 = $row['DR_12HR_Count'];
			$model->urs_AP_Count		 = $row['AP_Count'];
			$model->scenario			 = 'updateStats';
			if ($model->save())
			{
				$success = true;
			}
			else
			{
				$cntNotUpdate++;
			}
		}
		if ($userId > 0)
		{
			return $success;
		}
		else
		{
			echo $cntInsert . " rows insert for user stats";
			echo "\n";
			echo $cntUpdate . " rows update for user stats";
			echo "\n";
			echo $cntNotUpdate . " rows not update for user stats";
			echo "\n";
		}
	}

	// forgotpassword
	public function forgotPassword($email, $code, $newPassword)
	{

		//echo $email.$code.$newPassword;
		$user_model = Users::model()->getByEmail($email);

		if (count($user_model['attributes']) > 1)
		{
			$user_id					 = $user_model['attributes']['user_id'];
			$vendor_verification_code	 = $user_model['attributes']['usr_verification_code'];
			if ($code != "")
			{
				if ($vendor_verification_code == $code)
				{

					if ($newPassword != "")
					{

						$userData1				 = Users::model()->findByPk($user_id);
						$userData1->usr_password = md5($newPassword);
						$userData1->update();
						$message				 = "Password Changed Successfully";
						$status					 = true;

						$user_model->usr_verification_code = '';
						$user_model->update();
					}
					else
					{
						$message = "Verified Successfully.";
						$status	 = true;
					}
				}
				else
				{
					$message = "Validation Code didn't match. Resent code and verify";
					$status	 = false;
				}
			}
			else
			{
				$message = "No user found";
			}
		}

		$result = array('message'	 => $message,
			'status'	 => $status,
			'user_id'	 => $user_id);

		return $result;
	}

	//check user existance



	public function checkUserExistance($phoneNumber, $dl, $booking_id)
	{
		if ($booking_id != "")
		{
			//$statement = " UNION (SELECT d.drv_id,d.drv_user_id,d.drv_approved FROM drivers d, booking b, booking_cab bc WHERE b.bkg_booking_id LIKE '%".$booking_id."' AND b.bkg_id=bc.bcb_bkg_id1 AND d.drv_phone='".$phoneNumber."' AND d.drv_id= bc.bcb_driver_id)";
			$statement = " UNION (SELECT drivers.drv_id, drivers.drv_user_id, drivers.drv_approved
					FROM booking bkg 
					INNER JOIN booking_cab ON booking_cab.bcb_id=bkg.bkg_bcb_id AND bkg.bkg_booking_id LIKE '%" . $booking_id . "'
					INNER JOIN drivers ON drivers.drv_id=booking_cab.bcb_driver_id  
					INNER JOIN contact ON contact.ctt_id=drivers.drv_contact_id and drivers.drv_id=drivers.drv_ref_code
					INNER JOIN contact_phone phn ON phn.phn_contact_id=contact.ctt_id AND phn.phn_is_primary=1 AND phn.phn_active=1 AND phn.phn_phone_no='" . $phoneNumber . "')";
		}
		else
		{
			$statement = '';
		}
		$sql = "SELECT d2.drv_id, d2.drv_user_id, d2.drv_approved
				FROM  drivers d2  
				INNER JOIN contact ON contact.ctt_id=d2.drv_contact_id  and d2.drv_id=d2.drv_ref_code
				LEFT JOIN contact_phone ON contact_phone.phn_contact_id=contact.ctt_id AND contact_phone.phn_is_primary=1 AND contact_phone.phn_active=1
				WHERE contact_phone.phn_phone_no ='" . $phoneNumber . "' AND contact.ctt_license_no='" . $dl . "'  $statement";

		$row				 = DBUtil::command($sql)->queryAll($fetchAssociative	 = true);
		if (count($row) == 0)
		{
			$return['result']	 = false;
			$return['msg']		 = "No driver found";
			goto error;
		}
		else if (count($row) > 1)
		{
			$return['result']	 = false;
			$return['msg']		 = "Already linked with other user.";
			goto error;
		}
		else
		{
			$driver_id	 = $row[0]['drv_id'];
			$user_id	 = $row[0]['drv_user_id'];
			if ($row['drv_approved'] > 2)
			{
				$return['result']	 = false;
				$return['msg']		 = "No driver found";
				goto error;
			}
		}
		$otp							 = rand(100100, 999999);
		$drvModel						 = Drivers::model()->findByPk($driver_id);
		$otp							 = rand(100100, 999999);
		$drvModel->drv_verification_code = $otp;
		$drvModel->save();
		$msgCom							 = new smsWrapper();
		$username						 = $drvModel->drv_name;
		$contactDetails					 = Contact::model()->getContactDetails($drvModel->drv_contact_id);
		$msgCom->linkDriverOTP($contactDetails['phn_phone_country_code'], $contactDetails['phn_phone_no'], $otp, $username);
		$return['result']				 = true;
		$return['msg']					 = 'OTP sent successfully';
		if ($contactDetails['ctt_license_no'] != $dl)
		{
			$return['msg'] = 'OTP sent successfully but need proper licence number';
		}
		$return['driver_id'] = $driver_id;
		error:
		return $return;
	}

	public function getVerificationCode($userId)
	{
		$sql = "SELECT usr_verification_code  FROM `users` WHERE `user_id` = $userId";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getSocialList($user_id = "")
	{
		if (isset($this->search) && $this->search != "" && isset($this->email) && $this->email != "")
		{
			$where			 = " AND ( (cnte.eml_email_address LIKE '%" . trim($this->search) . "%')	OR (cntp.phn_phone_no LIKE '%" . trim($this->search) . "%') OR (vnd.vnd_code LIKE '%" . trim($this->search) . "%' ) OR (vnd.vnd_name LIKE '%" . trim($this->search) . "%')) AND cp.cr_is_consumer IN ($user_id)";
			$sql			 = "SELECT 
						vnd.vnd_id,
						cp.cr_is_consumer,
						vnd.vnd_name,
						vnd.vnd_code,
						cntp.phn_phone_no,
						cnte.eml_email_address
						FROM vendors as vnd 
                        INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
						LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
				        LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vnd.vnd_contact_id AND cntp.phn_active = 1
						WHERE vnd.vnd_active > 0 $where group by vnd.vnd_id";
			$sqlCount		 = "SELECT 
						vnd.vnd_id						
						FROM vendors as vnd 
                        INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
						LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
						LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vnd.vnd_contact_id AND cntp.phn_active = 1
						WHERE vnd.vnd_active > 0 $where group by vnd.vnd_id";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_id'],
					'defaultOrder'	 => 'vnd_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if (isset($this->search) && $this->search != "")
		{
			$where		 = " and ( (cnte.eml_email_address LIKE '%" . trim($this->search) . "%')	OR (cntp.phn_phone_no LIKE '%" . trim($this->search) . "%')  OR (vnd.vnd_code LIKE '%" . trim($this->search) . "%' ) OR (vnd.vnd_name LIKE '%" . trim($this->search) . "%'))";
			$sql		 = "SELECT  
							vnd.vnd_id,
							cp.cr_is_consumer,
							vnd.vnd_name,
							vnd.vnd_code,
							cntp.phn_phone_no,
							cnte.eml_email_address
							FROM vendors as vnd
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id 	
							LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
				LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vnd.vnd_contact_id AND cntp.phn_active = 1
							WHERE vnd.vnd_active > 0 $where group by vnd.vnd_id ";
			$sqlCount	 = "SELECT  
							vnd.vnd_id
							FROM vendors as vnd	
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
							LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
							LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vnd.vnd_contact_id AND cntp.phn_active = 1
							WHERE vnd.vnd_active > 0 $where group by vnd.vnd_id ";

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_id'],
					'defaultOrder'	 => 'vnd_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if (isset($this->email) && $this->email != "")
		{
			$where			 = " AND cp.cr_is_consumer IN ($user_id)";
			$sql			 = "SELECT 
							vnd.vnd_id,
							cp.cr_is_consumer,
							vnd.vnd_name,
							vnd.vnd_code,
							cntp.phn_phone_no,
							cnte.eml_email_address
							FROM vendors as vnd	
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
							LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
				LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vnd.vnd_contact_id AND cntp.phn_active = 1
							WHERE vnd.vnd_active > 0 $where group by vnd.vnd_id ";
			$sqlCount		 = "SELECT 
							vnd.vnd_id
							FROM vendors as vnd	
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
							LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
							LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = vnd.vnd_contact_id AND cntp.phn_active = 1
							WHERE vnd.vnd_active > 0 $where group by vnd.vnd_id ";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_id'],
					'defaultOrder'	 => 'vnd_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$sql			 = "SELECT  
							vnd.vnd_id,
							cp.cr_is_consumer,
							vnd.vnd_name,
							vnd.vnd_code,
							cntp.phn_phone_no,
							cnte.eml_email_address,
							imp.profile_cache,
							imp.identifier,
							imp.provider
							FROM vendors as vnd	
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
							INNER JOIN imp_user_oauth AS imp ON imp.user_id = cp.cr_is_consumer
				LEFT JOIN contact_email AS cnte  ON     cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
				LEFT JOIN contact_phone AS cntp  ON     cntp.phn_contact_id = vnd.vnd_contact_id  AND cntp.phn_active = 1
							WHERE vnd.vnd_active > 0  $where group by vnd.vnd_id,imp.provider";
			$sqlCount		 = "SELECT 
							vnd.vnd_id
							FROM vendors as vnd	
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id
							INNER JOIN imp_user_oauth AS imp ON imp.user_id = cp.cr_is_consumer
							LEFT JOIN contact_email AS cnte  ON     cnte.eml_contact_id = vnd.vnd_contact_id AND cnte.eml_active = 1
							LEFT JOIN contact_phone AS cntp  ON     cntp.phn_contact_id = vnd.vnd_contact_id  AND cntp.phn_active = 1
							WHERE vnd.vnd_active > 0  $where group by vnd.vnd_id,imp.provider";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_id'],
					'defaultOrder'	 => 'vnd_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public function getUserIdBySocialEmail($socialEmail)
	{
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';
		$sql		 = "SELECT GROUP_CONCAT( user_id)  FROM $oauthtable WHERE `profile_cache` LIKE '%$socialEmail%'";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
	}

	public function getProfileCacheByUserId($userId)
	{
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';
		$sql		 = "SELECT profile_cache,provider,identifier  FROM $oauthtable WHERE `user_id`=" . trim($userId);
		return DBUtil::queryAll($sql);
	}

	public function getSocialListUsers()
	{
		$where = "";
		if (isset($this->search) && $this->search != "")
		{
			$where .= " and ( (usr.usr_email LIKE '%" . trim($this->search) . "%') OR (usr.usr_mobile LIKE '%" . trim($this->search) . "%') OR (imp.provider LIKE '%" . trim($this->search) . "%')  OR (usr.usr_name LIKE '%" . trim($this->search) . "%' ) OR (usr.usr_lname LIKE '%" . trim($this->search) . "%') OR (imp.profile_cache LIKE '%" . trim($this->search) . "%') )";
		}
		$sql			 = "SELECT  
			usr.user_id, 
			usr.usr_name, 
			usr.usr_lname, 
			usr.usr_mobile, usr.usr_email, 
			imp.provider, imp.profile_cache, 
			vnd.vnd_code AS Vendors, 
			drv.drv_code AS Drivers
			FROM     users AS usr
			INNER JOIN imp_user_oauth AS imp ON imp.user_id = usr.user_id
			LEFT JOIN 
			(
				SELECT CONCAT(vnd_code, '_', vnd_id) AS vnd_code, vnd_user_id FROM   vendors WHERE  vnd_active > 0
			) vnd ON vnd.vnd_user_id = imp.user_id
			LEFT JOIN
			(
			   SELECT CONCAT(drv_code, '_', drv_id) AS drv_code, drv_user_id  FROM   drivers WHERE  drv_active = 1
			) drv  ON drv.drv_user_id = imp.user_id
			   WHERE    usr.usr_active = 1  $where ";
		$sqlCount		 = "SELECT  COUNT(*) FROM  users AS usr
				INNER JOIN imp_user_oauth AS imp ON imp.user_id = usr.user_id
				WHERE    usr.usr_active = 1  $where ";
		$count			 = DBUtil:: command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['user_id'],
				'defaultOrder'	 => 'user_id DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getSocialListDrivers($user_id = "")
	{
		if (isset($this->search) && $this->search != "" && isset($this->email) && $this->email != "")
		{
			$where			 = " and ( (cnte.eml_email_address LIKE '%" . trim($this->search) . "%')	OR (cntp.phn_phone_no LIKE '%" . trim($this->search) . "%') OR (drv_code LIKE '%" . trim($this->search) . "%' ) OR (drv_name LIKE '%" . trim($this->search) . "%')) and drv_user_id in ($user_id)";
			$sql			 = "SELECT  
					drv_id,
					drv_user_id,
					drv_name,
					drv_code,
					cntp.phn_phone_no,
					cnte.eml_email_address
								FROM drivers 
								LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1  
								LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = drv_contact_id AND cntp.phn_active = 1
					WHERE drv_active > 0 $where group by drv_id  order by  drv_id";
			$sqlCount		 = "SELECT
				drv_id
			FROM drivers 
			LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1  
			LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = drv_contact_id AND cntp.phn_active = 1
			WHERE drv_active > 0 $where group by drv_id ";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['drv_id'],
					'defaultOrder'	 => 'drv_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if (isset($this->search) && $this->search != "")
		{
			$where	 = " and ( (cnte.eml_email_address LIKE '%" . trim($this->search) . "%') OR (cntp.phn_phone_no LIKE '%" . trim($this->search) . "%')  OR (drv_code LIKE '%" . trim($this->search) . "%' ) OR (drv_name LIKE '%" . trim($this->search) . "%'))";
			$sql	 = "SELECT  
				drv_id,
				drv_user_id,
				drv_name,
				drv_code,
				cntp.phn_phone_no,
				cnte.eml_email_address
								FROM drivers 	
								LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1 
								LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = drv_contact_id AND cntp.phn_active = 1 
				WHERE drv_active > 0 $where group by drv_id ";

			$sqlCount		 = "SELECT  drv_id
				FROM drivers 	
				LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1 
				LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = drv_contact_id AND cntp.phn_active = 1 
				WHERE drv_active > 0 $where group by drv_id";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['drv_id'],
					'defaultOrder'	 => 'drv_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if (isset($this->email) && $this->email != "")
		{
			$where			 = " and drv_user_id in ($user_id)";
			$sql			 = "SELECT 
					drv_id,
					drv_user_id,
					drv_name,
					drv_code,
					cntp.phn_phone_no,
					cnte.eml_email_address
								FROM drivers 
								LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1  
								LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = drv_contact_id AND cntp.phn_active = 1 
					WHERE drv_active > 0 $where group by drv_id order by  drv_id";
			$sqlCount		 = "SELECT  drv_id
				FROM drivers 
				LEFT JOIN contact_email AS cnte ON cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1  
				LEFT JOIN contact_phone AS cntp ON cntp.phn_contact_id = drv_contact_id AND cntp.phn_active = 1 
				WHERE drv_active > 0 $where group by drv_id";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['drv_id'],
					'defaultOrder'	 => 'drv_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$sql			 = "SELECT  
					drv_id,
					drv_user_id,
					drv_name,
					drv_code,
					cntp.phn_phone_no,
					cnte.eml_email_address,
					imp.profile_cache,
					imp.provider
								 FROM drivers as drv
								INNER JOIN imp_user_oauth AS imp ON imp.user_id = drv_user_id  
								LEFT JOIN contact_email AS cnte  ON     cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1 
								LEFT JOIN contact_phone AS cntp  ON     cntp.phn_contact_id = drv_contact_id  AND cntp.phn_active = 1 
					WHERE drv_active > 0  $where group by drv_id,imp.provider";
			$sqlCount		 = "SELECT  
				drv_id
				FROM drivers as drv
				INNER JOIN imp_user_oauth AS imp ON imp.user_id = drv_user_id  
				LEFT JOIN contact_email AS cnte  ON     cnte.eml_contact_id = drv_contact_id AND cnte.eml_active = 1 
				LEFT JOIN contact_phone AS cntp  ON     cntp.phn_contact_id = drv_contact_id  AND cntp.phn_active = 1 
				WHERE drv_active > 0  $where group by drv_id,imp.provider";
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['drv_id'],
					'defaultOrder'	 => 'drv_id DESC'
				],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public function deleteUserFromImpUserAuth($userid)
	{
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';
		$sql		 = "delete from $oauthtable where user_id='$userid'";
		$res		 = DBUtil::command($sql)->execute();
		return $res;
	}

	public function logoutByUserId($userId)
	{
		$criteria			 = new CDbCriteria;
		$criteria->condition = "apt_user_id = $userId and  apt_status =1 and apt_user_type in (1,2,5)";
		$applogout			 = AppTokens::model()->findAll($criteria);
		foreach ($applogout as $value)
		{
			if ($value)
			{
				$value->apt_status	 = 0;
				$value->apt_logout	 = new CDbExpression('NOW()');
				$logout				 = $value->save();
			}
		}
	}

	public function getSosContactList($userId)
	{
		$sql			 = "SELECT usr_sos  FROM  users WHERE user_id= '$userId' ";
		$sosContact		 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		$sosContactList	 = CJSON::decode($sosContact, true);
		return $sosContactList;
	}

	public function sendNotificationToSosContact($userId, $location)
	{
		$bkgId			 = $location['bkg_id'];
		$bModel			 = Booking::model()->findByPk($location['bkg_id']);
		$UserModel		 = Users::model()->findByPk($userId);
		$userName		 = $UserModel->usr_name . '' . $UserModel->usr_lname;
		$travellerName	 = $bModel->bkgUserInfo->bkg_user_fname . ' ' . $bModel->bkgUserInfo->bkg_user_lname;
		$sosContactList	 = $this->getSosContactList($userId);
		if ($location['lat'] != 0.0 && $location['lon'] != 0.0)
		{
			foreach ($sosContactList As $value)
			{
				$emergencyUserName	 = $value['name'];
				$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
				$phoneNumber		 = substr($phone, -10);
				$emailAddress		 = $value['email'];

				$urlHash = $this->createSOSHashUrl($bkgId, $userId);
				$url	 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
				$msg	 = "$travellerName has pressed panic button and wants to notify you of the emergency. Track their location at $url urgently contact them. Gozo is also taking action.";
				if (strlen($phoneNumber) >= 10)
				{
					$msgCom		 = new smsWrapper();
					$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($bkgId, $phoneNumber, $msg);
				}
				if ($emailAddress != '')
				{
					$emailModel		 = new emailWrapper();
					$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($bkgId, $userName, $emergencyUserName, $emailAddress, $msg);
				}
			}
			$sosSmsTrigger = ($sendSmsFlag != Null || $sendEmailFlag != Null ) ? 2 : 1;
		}
		else
		{
			$sosSmsTrigger = 0;
		}
		$result = array('sosContactList' => $sosContactList,
			'sosSmsTrigger'	 => $sosSmsTrigger);
		return $result;
	}

	public function createSOSHashUrl($bkgId, $UserId)
	{
		$bkgHash	 = Yii::app()->shortHash->hash($bkgId);
		$userHash	 = Yii::app()->shortHash->hash($UserId);
		$urlHash	 = trim($bkgHash . '_' . $userHash);
		return $urlHash;
	}

	function unhashSOSUrl($urlHash)
	{
		$array	 = explode('_', $urlHash);
		$bkgId	 = Yii::app()->shortHash->unhash($array[0]);
		$userId	 = Yii::app()->shortHash->unhash($array[1]);
		return ['bkgId' => $bkgId, 'userId' => $userId];
	}

	static function getImageUrl($fileName)
	{
		$path = "";
		if (strpos($fileName, "https://") === false && $fileName != "")
		{
			$path = Yii::app()->request->hostInfo . $fileName;
		}
		return $path;
	}

	public function sendTripOtp($bkgId = 0)
	{
		$bkgModel		 = Booking::model()->findByPk($bkgId);
		$userName		 = $bkgModel->bkgUserInfo->bkg_user_fname . ' ' . $bkgModel->bkgUserInfo->bkg_user_lname;
		$ext			 = $bkgModel->bkgUserInfo->bkg_country_code;
		$phoneNumber	 = $bkgModel->bkgUserInfo->bkg_contact_no;
		$emailAddress	 = $bkgModel->bkgUserInfo->bkg_user_email;

		$dltId = '';
		if ($bkgModel->bkgTrack != '')
		{
			$msgOTP	 = "Your OTP for starting " . $bkgModel->bkg_booking_id . " is " . $bkgModel->bkgTrack->bkg_trip_otp . " - Gozocabs";
			$dltId	 = smsWrapper::DLT_TRIP_START_OTP_TEMPID;
		}
		if (strlen($phoneNumber) >= 10)
		{
			$msgCom	 = new smsWrapper();
			$slgId	 = $msgCom->sendTripOtp($bkgModel->bkg_booking_id, $ext, $phoneNumber, $msgOTP, $dltId);
			Logger::create("SMS Error: " . json_encode($slgId), CLogger::LEVEL_INFO);
		}
		if ($emailAddress != '')
		{
			$emailModel	 = new emailWrapper();
			$elgId		 = $emailModel->sendTripOtp($bkgModel->bkg_booking_id, $userName, $emailAddress, $msgOTP, $type		 = 0);
			Logger::create("Email Error: " . json_encode($elgId), CLogger::LEVEL_INFO);
		}

		$result = ($slgId != Null || $elgId != Null ) ? true : false;

		return $result;
	}

	public function loginOld($data, $type)
	{
		Logger::create("postData :: " . json_encode($data), CLogger::LEVEL_INFO);
		$email				 = $data['usr_email'];
		$password			 = $data['usr_password'];
		$deviceID			 = $data['deviceid'];
		$deviceVersion		 = $data['version'];
		$apkVersion			 = $data['apk_version'];
		$ipAddress			 = \Filter::getUserIP();
		$deviceInfo			 = $data['device_info'];
		$apt_device_token	 = $data['apt_device_token'];
		$isSocialLogin		 = Yii::app()->request->getParam('isSocialLogin', 0);
		$social_data		 = Yii::app()->request->getParam('social_data', '');
		Logger::create("isSocialLogin :: " . $isSocialLogin, CLogger::LEVEL_INFO);
		if ($isSocialLogin == 1)
		{
			if ($type == 'signup')
			{
				$result = Users::model()->linkAppUser(0, $social_data);
				if ($result['success'])
				{
					$user_id	 = $result['user_id'];
					$userModel	 = Users::model()->findByPk($user_id);
					//$email		 = $userModel->usr_email;
					//$md5password = $userModel->usr_password;
				}
			}
			else
			{
				Logger::create(" SocialLogin :: Entered", CLogger::LEVEL_INFO);
				$provider			 = Yii::app()->request->getParam('provider');
				$process_sync_data	 = Yii::app()->request->getParam('social_data');
				$userData			 = CJSON::decode($process_sync_data, true);
				$email				 = $userData['email'];
				$result['success']	 = true;
				$userModel			 = Users::model()->getByEmail($email);
				if (!$userModel)
				{
					$result = ['success' => false, 'message' => 'User not found'];
					Logger::create("message :: User not found", CLogger::LEVEL_INFO);
					return $result;
				}
				else
				{

					$userid			 = $userModel->user_id;
					Logger::create("message :: User exists with userid : $userid", CLogger::LEVEL_INFO);
					$isSocialLinked	 = Users::model()->checkSocialLinking($userModel->user_id, $provider);

					Logger::create("isSocialLinked :: " . ($isSocialLinked), CLogger::LEVEL_INFO);

					if (!$isSocialLinked)
					{
						$result	 = Users::model()->linkAppUser($userid);
						$userid	 = $result['user_id'];
						Logger::create("isSocialLinked result :: " . $result['success'], CLogger::LEVEL_INFO);
					}
					if ($result['success'])
					{

						$userModel	 = Users::model()->findByPk($userid);
						$email		 = $userModel->usr_email;
						$md5password = $userModel->usr_password;
					}
				}
			}
		}
		else
		{
			$md5password = md5($password);
		}



		/*
		  $identity = new UserIdentity($email, $md5password);
		  if ($identity->authenticate())
		  {
		  $userID		 = $identity->getId();
		  Yii::app()->user->login($identity);
		  $token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		  $appToken	 = AppTokens::model()->findAll('((apt_device_uuid=:device AND apt_device_uuid<>\'\') OR (apt_device_token=:gcmtoken AND apt_device_token<>\'\')) AND apt_token_id<>:token', array('device' => $deviceID, 'gcmtoken' => $apt_device_token, 'token' => $token));
		  if ($appToken != '')
		  {
		  foreach ($appToken as $value)
		  {
		  if (count($value) > 0)
		  {
		  $value->apt_status = 0;
		  $value->update();
		  }
		  }
		  }
		  $appTokenModel = AppTokens::model()->find('apt_token_id = :token AND apt_token_id<>\'\' AND apt_status = :status', array('token' => $token, 'status' => 1));
		  if (!$appTokenModel)
		  {
		  $appTokenModel				 = new AppTokens();
		  $appTokenModel->apt_token_id = Yii::app()->getSession()->getSessionId();
		  }
		  $appTokenModel->apt_user_id		 = $userID;
		  $appTokenModel->apt_device		 = $deviceInfo;
		  $appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
		  $appTokenModel->apt_device_uuid	 = $deviceID;
		  $appTokenModel->apt_user_type	 = 1;
		  $appTokenModel->apt_apk_version	 = $apkVersion;
		  $appTokenModel->apt_ip_address	 = $ipAddress;
		  $appTokenModel->apt_os_version	 = $deviceVersion;
		  $appTokenModel->apt_device_token = $apt_device_token;
		  $appTokenModel->save();
		  $result							 = ['success' => true, 'sessionId' => $appTokenModel->apt_token_id, 'errors' => $appTokenModel->getErrors()];
		  }
		  else
		  {
		  $result = ['success' => false];
		  }

		 */

		return $result;
	}

	public function createUserTempLogin($fname, $lname, $email, $phone, $countryCode, $platform)
	{
		if ($email != '' && $phone != '')
		{
			$criteria1	 = new CDbCriteria;
			$criteria1->compare('usr_email', trim($email));
			$criteria1->compare('usr_mobile', trim($phone));
			$criteria1->addCondition("usr_active > 0");
			$usrModel	 = $this->find($criteria1);
			if ($usrModel != '')
			{
				return array("status" => 1, 'UserId' => $usrModel->user_id, 'message' => "");
			}
		}
		if ($email != '')
		{
			$criteria2	 = new CDbCriteria;
			$criteria2->compare('usr_email', trim($email));
			$criteria2->addCondition("usr_active > 0");
			$usrModel	 = $this->find($criteria2);
			if ($usrModel)
			{
				return array("status" => 1, 'UserId' => $usrModel->user_id, 'message' => "");
			}
		}
		if ($phone != '')
		{
			$criteria3	 = new CDbCriteria;
			$criteria3->compare('usr_mobile', trim($phone));
			$criteria3->addCondition("usr_active > 0");
			$usrModel	 = $this->find($criteria3);
			if ($usrModel)
			{
				return array("status" => 1, 'UserId' => $usrModel->user_id, 'message' => "");
			}
		}
		//$transaction = DBUtil::beginTransaction();
		try
		{
			$usrModel					 = new Users();
			$usrModel->isNew			 = true;
			$usrModel->scenario			 = 'inserttemplogin';
			$usrModel->usr_name			 = $fname;
			$usrModel->usr_lname		 = $lname;
			$usrModel->usr_ip			 = \Filter::getUserIP();
			$usrModel->usr_device		 = UserLog::model()->getDevice();
			$usrModel->usr_country_code	 = $countryCode;
			$usrModel->usr_password		 = 'welcomeToGozo';
			if ($email != '' && $email != 'NULL' && $email != 'null')
			{
				$usrModel->usr_email	 = $email;
				$usrModel->usr_acct_type = '1';
			}
			else
			{
//				$usrModel->usr_email	 = $this->generateEmailByPhone($countryCode, $phone, $platform);
//				$usrModel->usr_acct_type = '2';
				throw new Exception("Please enter valid email id");
			}
			if ($phone != '' && $phone != 'NULL')
			{
				$usrModel->usr_mobile = str_replace(' ', '', $phone);
			}
			$usrModel->usr_active			 = '1';
			$usrModel->usr_create_platform	 = $platform;
			if ($usrModel->save())
			{
				//	DBUtil::commitTransaction($transaction);
				return array("status" => 1, 'UserId' => $usrModel->user_id, 'message' => "");
			}
			else
			{
				foreach ($usrModel->getErrors() as $key => $value)
				{
					//DBUtil::rollbackTransaction($transaction);
					return array("status" => 0, 'message' => $value[0]);
					//exit();
				}
			}
		}
		catch (Exception $ex)
		{
			//DBUtil::rollbackTransaction($transaction);
			return array("status" => 0, 'message' => "Please try again later");
		}
	}

	/**
	 * 
	 * @param model $user Users
	 * @param integer $userId
	 */
	public function updateProfileinfo($model, $userId, $hasImage = 0, $contactId = null)
	{
		/** @var $userModel Users  */
		$userModel = Users::model()->findByPk($userId);
		if ($hasImage == 0)
		{
			$userModel->usr_contact_id	 = $contactId;
			$userModel->usr_name		 = $model->usr_name;
			$userModel->usr_lname		 = $model->usr_lname;
			$userModel->usr_country_code = $model->usr_country_code;
			$userModel->usr_mobile		 = ($model->usr_mobile != '') ? str_replace(' ', '', $model->usr_mobile) : $model->usr_mobile;
			$userModel->usr_address1	 = $model->usr_address1;
			$userModel->usr_gender		 = $model->usr_gender;
			$userModel->usr_zip			 = $model->usr_zip;
			$userModel->usr_state		 = $model->usr_state;
			$userModel->usr_country		 = $model->usr_country;
		}
		if ($hasImage == 1)
		{
			Logger::create("File Request : " . json_encode($_FILES), CLogger::LEVEL_INFO);
			$image		 = $_FILES['image']['name'];
			$imagetmp	 = $_FILES['image']['tmp_name'];
			$profileImage	 = CUploadedFile::getInstanceByName('image');
			if ($profileImage != '')
			{

//				$name		 = $userId . "_" . date('Ymd_His') . $image;
//				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'profiles';

				$path = Users::saveUserProfileImage($userId, $profileImage);

				
//				$file_name	 = basename($name);
//				$f			 = $file_path;
//				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
//				file_put_contents($file_path, $image);
//				Yii::log("Image Path: \n\t Temp: " . $image . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
//				if ($this->img_resize($imagetmp, 1200, $f, $name))
//				{
//					$userModel->usr_profile_pic_path = substr($file_path, strlen(PUBLIC_PATH));
//					$userModel->usr_profile_pic_path = str_replace("\\", "/", $userModel->usr_profile_pic_path);
//				}
//				else
//				{
//					throw new Exception('Profile Image Upload Failed', ReturnSet::ERROR_INVALID_DATA);
//				}
			}
			$userModel->usr_profile_pic = $path;
		}
		if (!$userModel->save())
		{
			throw new Exception('Not Updated Profile Information ', ReturnSet::ERROR_INVALID_DATA);
		}
		if ($hasImage == 1)
		{
			$userModel->usr_profile_pic = $path;
		}
		//Updating contact profile table
		if ($contactId != '')
		{
			ContactProfile::setProfile($contactId, UserInfo::TYPE_CONSUMER);
		}

		return $userModel;
	}

	/**
	 * @param integer $userId
	 * @param string $profileImage
	 * @return boolean
	 */
	public static function saveUserProfileImage($userId, $profileImage)
	{
		$contactId = ContactProfile::getByUserId($userId);
		if ($contactId == null)
		{
			goto skip;
		}
		/* @var $contactModel Contact */
		$contactModel					 = Contact::model()->findByPk($contactId);
		$path							 = Document::upload($contactId, "profile", $profileImage);
		$contactModel->ctt_profile_path	 = $path;
		if ($contactModel->save())
		{
			return $path;
		}
		skip:
		return false;
	}

	function img_resize($tmpname, $size, $save_dir, $save_name, $maxisheight = 0)
	{
		$arr		 = array();
		$save_dir	 .= ( substr($save_dir, -1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : "";
		$arr[1]		 = $save_dir;
		$gis		 = getimagesize($tmpname);
		$arr[21]	 = $tmpname;
		$type		 = $gis[2];
		$arr[2]		 = $gis;
		switch ($type)
		{
			case "1": $imorig	 = imagecreatefromgif($tmpname);
				break;
			case "2": $imorig	 = imagecreatefromjpeg($tmpname);
				break;
			case "3": $imorig	 = imagecreatefrompng($tmpname);
				break;
			default: $imorig	 = imagecreatefromjpeg($tmpname);
		}

		$x	 = imagesx($imorig);
		$y	 = imagesy($imorig);

		$woh = (!$maxisheight) ? $gis[0] : $gis[1];

		if ($woh <= $size)
		{
			$aw	 = $x;
			$ah	 = $y;
		}
		else
		{
			if (!$maxisheight)
			{
				$aw	 = $size;
				$ah	 = $size * $y / $x;
			}
			else
			{
				$aw	 = $size * $x / $y;
				$ah	 = $size;
			}
		}
		$im = imagecreatetruecolor($aw, $ah);
		if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y))
		{
			if (imagejpeg($im, $save_dir . $save_name))
			{
				Yii::log("Image Resampled: " . $save_dir . $save_name, CLogger::LEVEL_INFO, 'system.api.images');
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * 
	 * @param integer $userId
	 * @param integer $vendorId
	 * @param type $model
	 * @return array
	 */
	public function changePass($userId, $newModel)
	{
		$success = false;
		/* @var $model Users */
		$model	 = Users::model()->findByPk($userId);

		if ($model->validate())
		{
			if ($model->usr_password != md5($newModel->old_password))
			{
				throw new Exception('Old Password Not Matching', ReturnSet::ERROR_VALIDATION);
			}
			else if ($newModel->new_password == $newModel->old_password)
			{
				throw new Exception('Password Not Changed', ReturnSet::ERROR_VALIDATION);
			}
			else
			{
				$model->usr_password = md5($newModel->new_password);
				$model->save();
				$success			 = true;
			}
		}
		else
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $success;
	}

	/**
	 * 
	 * @param string $email
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function checkForgotPass($email)
	{
		$returnSet = new ReturnSet();
		try
		{
			$isValid = Filter::validateEmail($email);
			if (!$isValid)
			{
				throw new Exception('Please enter valid email address', ReturnSet::ERROR_VALIDATION);
			}

			$contactId		 = ContactEmail::findById($email);
			$contactModel	 = Contact::model()->findByPk($contactId);
			$users			 = ($contactId == null || $contactId == "") ? array() : Users::model()->findByContactID($contactId);
			if (count($users) > 0)
			{
				$user_id				 = $users[0]->user_id;
				$hash					 = Yii::app()->shortHash->hash($user_id);
				$username				 = $contactModel->ctt_first_name;
				$key					 = md5($users[0]->usr_password);
				$link					 = Yii::app()->createAbsoluteUrl('users/resetpassword', array('key' => $key, 'uid' => $hash));
				//$this->email_receipient	 = $email;
				$mail					 = new YiiMailer();
				$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
				$mail->email_receipient	 = $email;
				$mail->setView('fmailweb');
				$mail->setData(
						array(
							'username'			 => $username,
							'link'				 => $link,
							'userId'			 => $user_id,
							'email_receipient'	 => $email
				));

				$mail->setLayout('mail');
				$mail->setFrom(Yii::app()->params['mail']['noReplyMail'], 'Info Gozocabs');
				$mail->setTo($email, $username);
				$mail->setSubject('Reset your Password');
				if ($mail->sendMail(0))
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
				$body		 = $mail->Body;
				$usertype	 = EmailLog::Consumers;
				$subject	 = 'Reset your Password';
				$refId		 = $user_id;
				$refType	 = EmailLog::REF_USER_ID;
				emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				$returnSet->setStatus(true);
				$returnSet->setMessage("Reset password link has been sent to your email.");
			}
			else
			{
				throw new Exception("Email not found.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			$returnSet->setMessage($ex->getMessage());
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param Users $model
	 * @param integer $isMail
	 * @param integer $isPlatform
	 * @param integer $contactId
	 * @param string $deviceName
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function create(Users $model, $isMail = false, $isPlatform = Users::Platform_Web, $contactId = false, $deviceName = null)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$usrModel					 = new Users();
			$usrModel->attributes		 = $model;
			$usrModel->usr_contact_id	 = $contactId;
			$usrModel->usr_device		 = $deviceName;
			$usrModel->usr_name			 = $model->usr_name;
			$usrModel->usr_lname		 = $model->usr_lname;
			$usrModel->usr_email		 = $model->usr_email;
			$usrModel->usr_mobile		 = $model->usr_mobile;

			$usrModel->usr_password			 = $model->usr_password;
			$usrModel->repeat_password		 = $model->usr_password;
			$usrModel->new_password			 = $model->usr_password;
			$usrModel->usr_create_platform	 = $isPlatform;
			$usrModel->usr_ip				 = \Filter::getUserIP();
			$usrModel->usr_refer_code		 = Users::getUniqueReferCode($model);
			if ($model->usr_referred_code != null)
			{
				$users		 = Users::model()->getByReferCode($model->usr_referred_code);
				$refferalId	 = Users::getIdByReferCode($model->usr_referred_code);
				if (!$users)
				{
					throw new Exception(CJSON::encode('Invalid Referral Code'), ReturnSet::ERROR_VALIDATION);
				}
				$usrModel->usr_referred_code = $model->usr_referred_code;
				$usrModel->usr_referred_id	 = $refferalId;
			}
			$usrModel->scenario = 'userSignup';
			if (!$usrModel->save())
			{
				Logger::info('user not created ' . CJSON::encode($usrModel->getErrors()));
				throw new Exception(CJSON::encode($usrModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			Logger::info('user created ' . $usrModel->user_id);
			if ($isMail)
			{
				$email = new emailWrapper();
				$email->signupEmail($usrModel->user_id);
			}

			if ($contactId != '')
			{
				ContactProfile::setProfile($contactId, UserInfo::TYPE_CONSUMER);
			}
			$returnSet->setStatus(true);
			$returnSet->setData(['userId' => $usrModel->user_id]);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param Users $model
	 * @param type $tokenModel
	 * @return type
	 * @throws Exception
	 */
	public static function doRegister($model, $tokenModel, $contactId = null)
	{
		$userModel						 = new Users();
		$userModel->attributes			 = $model;
		$userModel->usr_device			 = $tokenModel->apt_device;
		$userModel->usr_contact_id		 = $contactId;
		$userModel->usr_create_platform	 = Users::Platform_App;
		$userModel->usr_acct_type		 = Users::AcctType_Verify;
		$userModel->usr_ip				 = \Filter::getUserIP();
		$userModel->usr_name			 = $model->usr_name;
		$userModel->usr_lname			 = $model->usr_lname;
		$userModel->usr_mobile			 = ($model->usr_mobile != '') ? str_replace(' ', '', $model->usr_mobile) : $model->usr_mobile;
		$userModel->usr_password		 = $model->usr_password;
		$userModel->repeat_password		 = $model->usr_password;
		$userModel->new_password		 = $model->usr_password;
		$userModel->usr_email			 = $model->usr_email;
		$userModel->usr_refer_code		 = self::getUniqueReferCode($model);
		$userModel->scenario			 = 'mobinsert';
		if ($model->usr_referred_code != '')
		{
			$users		 = Users::model()->getByReferCode($model->usr_referred_code);
			$refferalId	 = Users::getIdByReferCode($model->usr_referred_code);
			if (!$users)
			{
				throw new Exception(CJSON::encode('Invalid Referral Code'), ReturnSet::ERROR_VALIDATION);
			}
			$userModel->usr_referred_code	 = $model->usr_referred_code;
			$userModel->usr_referred_id		 = $refferalId;
		}
		if (!$userModel->save())
		{
			throw new Exception(CJSON::encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		$email = new emailWrapper();
		$email->signupEmail($userModel->user_id);

//		if ($userModel->usr_refer_code == '' || $userModel->usr_refer_code == NULL)
//		{
//			$arr						 = Users::model()->getRefercode($userModel->user_id);
//			$userModel->usr_refer_code	 = $arr['refCode'];
//		}
		//Updating contact profile table
		if ($contactId != '')
		{
			ContactProfile::setProfile($contactId, UserInfo::TYPE_CONSUMER);
		}
		return $userModel;
	}

	public function goRegister($newModel)
	{
		try
		{
			$type		 = 'signup';
			$userModel	 = Users::model()->getByEmail($newModel->usr_email);

			if (!$userModel)
			{
				if ($isSocialLogin == 1)
				{
					$pass					 = uniqid(rand(), TRUE);
					$newModel->usr_password	 = $pass;
					$newModel->usr_password	 = $pass;
				}

				$result = Users::model()->registerpostonapi($newModel);

				if ($result['success'] == 'true')
				{
					$status = Users::model()->loginpostapi($newModel, $type);
					if ($status['success'] == true)
					{
						$success				 = true;
						$userId					 = Yii::app()->user->getId();
						$refArr					 = Users::model()->getRefercode($userId);
						$refMsg					 = $refArr['refMessage'];
						$userModel				 = Users::model()->findByPk($userId);
						$userName				 = $userModel->usr_name;
						$userModel->usr_password = '';
						$msg					 = "Login Successful";
						$sessionId				 = $status['sessionId'];
					}
					else
					{
						$success = false;
						$msg	 = "Invalid Username/Password";
					}
					return CJSON::encode(['success'		 => $success, 'get'			 => $_GET, 'message'		 => $msg,
								'refer_message'	 => $refMsg,
								'sessionId'		 => $sessionId, 'userId'		 => $userId,
								'userModel'		 => $userModel, 'userName'		 => $userName]);
				}
			}
			else
			{
				$success = false;
				// $msg	 = 'email exist';
				$returnSet->setStatus($success);
				$returnSet->setData($data);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function registerpostonapi($data)
	{
		$model						 = new Users();
		$model->attributes			 = $data;
		//$model->usr_device		 = $newModel->unique_id; 
		$model->usr_name			 = $data->usr_name;
		$model->usr_lname			 = $data->usr_lname;
		$model->usr_email			 = $data->usr_email;
		$model->usr_referred_code	 = $data->usr_referred_code;
		$model->usr_create_platform	 = Users::Platform_App;
		$model->usr_acct_type		 = Users::AcctType_Verify;
		$model->usr_ip				 = \Filter::getUserIP();
		$model->usr_password		 = $data->usr_password;
		$model->repeat_password		 = $data->usr_password;
		$code						 = $data->usr_referred_code;

		$success = true;
		if ($code != '')
		{
			$userModel = Users::model()->getByReferCode($data->usr_referred_code);
			if (!$userModel)
			{
				if ($code != '')
				{
					$model->usr_referred_code = $data->usr_referred_code;
				}
			}
			else
			{
				$errMsg	 = 'Invalid Referral Code';
				$success = false;
			}
		}

		$errMsg = '';
		if ($model->validate() && $success)
		{
			$model->scenario = 'mobinsert';
			$reg			 = $model->save();
			if (!$reg)
			{
				$success = false;
				$msg	 = 'email exist';
			}
			else
			{
				$user_id = $model->user_id;
				if ($model->usr_email != '')
				{
					$email = new emailWrapper();
					$email->signupEmail($user_id);
				}
				$status	 = 1;
				$success = true;
			}
		}
		else
		{
			$errors	 = $model->errors;
			$success = 'false';
			foreach ($errors as $value)
			{
				$errMsg = $value[0];
				break;
			}
		}


		Yii::log("lname: " . $model->usr_lname, CLogger::LEVEL_INFO, 'system.api.inspection');
		$model->usr_password = '';

		$returnSet->setStatus(false);
		$returnSet->setData($model->user_id);

//		$result				 = array('success'	 => $success,
//			'errors'	 => $errors,
//			'error'		 => $errMsg,
//			'get'		 => $data,
//			'userID'	 => $user_id,
//			'data'		 => $model,
//			'code'		 => $code
//		);
		//return $result;
		return $returnSet;
	}

	public function loginpostapi($data, $type)
	{
		Logger::create("callType :: " . $type, CLogger::LEVEL_INFO);
		$email				 = $data['usr_email'];
		$password			 = $data['usr_password'];
		$deviceID			 = $data['deviceid'];
		$deviceVersion		 = $data['version'];
		$apkVersion			 = $data['apk_version'];
		$ipAddress			 = \Filter::getUserIP();
		$deviceInfo			 = $data['device_info'];
		$apt_device_token	 = $data['apt_device_token'];
		$isSocialLogin		 = Yii::app()->request->getParam('isSocialLogin', 0);
		$social_data		 = Yii::app()->request->getParam('social_data', '');
		Logger::create("isSocialLogin :: " . $isSocialLogin, CLogger::LEVEL_INFO);
		if ($isSocialLogin == 1)
		{
			if ($type == 'signup')
			{
				$result = Users::model()->linkAppUser(0, $social_data);
				if ($result['success'])
				{
					$user_id	 = $result['user_id'];
					$userModel	 = Users::model()->findByPk($user_id);
					$email		 = $userModel->usr_email;
					$md5password = $userModel->usr_password;
				}
			}
			else
			{
				Logger::create(" SocialLogin :: Entered", CLogger::LEVEL_INFO);
				$provider			 = Yii::app()->request->getParam('provider');
				$process_sync_data	 = Yii::app()->request->getParam('social_data');
				$userData			 = CJSON::decode($process_sync_data, true);
				$email				 = $userData['email'];
				Logger::create("provider :: " . $provider, CLogger::LEVEL_INFO);
				Logger::create("social_data :: " . $process_sync_data, CLogger::LEVEL_INFO);
				Logger::create("email :: " . $email, CLogger::LEVEL_INFO);

				$result['success']	 = true;
				$userModel			 = Users::model()->getByEmail($email);
				if (!$userModel)
				{
					$result = ['success' => false, 'message' => 'User not found'];
					Logger::create("message :: User not found", CLogger::LEVEL_INFO);
					return $result;
				}
				else
				{

					$userid			 = $userModel->user_id;
					Logger::create("message :: User exists with userid : $userid", CLogger::LEVEL_INFO);
					$isSocialLinked	 = Users::model()->checkSocialLinking($userModel->user_id, $provider);

					Logger::create("isSocialLinked :: " . ($isSocialLinked), CLogger::LEVEL_INFO);

					if (!$isSocialLinked)
					{
						$result	 = Users::model()->linkAppUser($userid);
						$userid	 = $result['user_id'];
						Logger::create("isSocialLinked result :: " . $result['success'], CLogger::LEVEL_INFO);
					}
					if ($result['success'])
					{

						$userModel	 = Users::model()->findByPk($userid);
						$email		 = $userModel->usr_email;
						$md5password = $userModel->usr_password;
					}
				}
			}
		}
		else
		{
			$md5password = md5($password);
		}

		$identity = new UserIdentity($email, $md5password);
		if ($identity->authenticate())
		{
			$userID		 = $identity->getId();
			Yii::app()->user->login($identity);
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$appToken	 = AppTokens::model()->findAll('((apt_device_uuid=:device AND apt_device_uuid<>\'\') OR (apt_device_token=:gcmtoken AND apt_device_token<>\'\')) AND apt_token_id<>:token', array('device' => $deviceID, 'gcmtoken' => $apt_device_token, 'token' => $token));
			if ($appToken != '')
			{
				foreach ($appToken as $value)
				{
					if (count($value) > 0)
					{
						$value->apt_status = 0;
						$value->update();
					}
				}
			}
			$appTokenModel = AppTokens::model()->find('apt_token_id = :token AND apt_token_id<>\'\' AND apt_status = :status', array('token' => $token, 'status' => 1));
			if (!$appTokenModel)
			{
				$appTokenModel				 = new AppTokens();
				$appTokenModel->apt_token_id = Yii::app()->getSession()->getSessionId();
			}
			$appTokenModel->apt_user_id		 = $userID;
			$appTokenModel->apt_device		 = $deviceInfo;
			$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid	 = $deviceID;
			$appTokenModel->apt_user_type	 = 1;
			$appTokenModel->apt_apk_version	 = $apkVersion;
			$appTokenModel->apt_ip_address	 = $ipAddress;
			$appTokenModel->apt_os_version	 = $deviceVersion;
			$appTokenModel->apt_device_token = $apt_device_token;
			$appTokenModel->save();
			$result							 = ['success' => true, 'sessionId' => $appTokenModel->apt_token_id, 'errors' => $appTokenModel->getErrors()];
		}
		else
		{
			$result = ['success' => false];
		}
		return $result;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param string $data
	 * @return boolean
	 * @throws Exception
	 */
	public static function addContactToSOS($userId, $data)
	{
		$success	 = false;
		/* @var $userModel Users */
		$userModel	 = Users::model()->findByPk($userId);
		if (!$userModel)
		{
			throw new Exception(CJSON::encode("Invalid Data : "), ReturnSet::ERROR_INVALID_DATA);
		}
		$userModel->usr_sos = trim($data);
		if (!$userModel->save())
		{
			throw new Exception(CJSON::encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		if ($userModel->save())
		{
			$success = true;
		}
		return $success;
	}

	public function addSOSContacts($userId, $data)
	{
		try
		{
			$userModel = Users::model()->findByPk($userId);
			if ($userModel)
			{
				$userModel->usr_sos = null;
				if ($userModel->save())
				{
					$userModel->usr_sos	 = trim($data);
					$userModel->update();
					$message			 = 'Contacts Saved Successfully';
				}
			}
			else
			{
				$message = "";
			}
			return $message;
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
	}

	/**
	 * 
	 * @param type $model
	 * @return boolean
	 * @throws Exception
	 */
	public function doLogin($model)
	{
		/* @var $userModel Users */
		$userModel = Users::validateInstance($model);
		if (!$userModel)
		{
			return false;
		}
		$customerModel = Users::model()->findByPk($userModel->user_id);

		$identity = $userModel->authenticate($customerModel->user_id, 1, $userModel);
		$userModel->loginIdentity($identity);
		return $customerModel;
	}

	public function fetchUserDetails($userId)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($userId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$findUserProviderDetails = "	
				SELECT u.usr_email emailId, iuo.provider provider
				FROM users u 
				INNER JOIN imp_user_oauth iuo 
					ON iuo.user_id = u.user_id
				WHERE u.user_id = $userId 
					AND u.usr_active = 1
			";

			$arrRelationDetails = DBUtil::queryAll($findUserProviderDetails, DBUtil::SDB());

			if (!empty($arrRelationDetails))
			{
				$returnset->setData($arrRelationDetails);
				$returnset->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * 
	 * @param type $token
	 * @return boolean
	 * @throws Exception
	 */
	public static function doLogout($token)
	{
		$success = false;
		$model	 = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
		if (!$model)
		{
			throw new Exception(CJSON::encode('User logged out not successful.'), ReturnSet::ERROR_VALIDATION);
		}
		$model->apt_status	 = 0;
		$model->apt_logout	 = new CDbExpression('NOW()');
		if (!$model->save())
		{
			throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$success = true;
		return $success;
	}

	public static function addSocialUser(SocialAuth $socialAuth, $token, $userId = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		try
		{
			if ($token == null)
			{
				goto skipValidateToken;
			}
			$isValid = $socialAuth->validateToken($token);
			if (!$isValid)
			{
				$model = Users::model();
				$model->addError("authentication", "Invaid Social Authentication");
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				//throw new Exception("Invaid Social Authentication", ReturnSet::ERROR_VALIDATION);
			}

			skipValidateToken:
			$authModel			 = clone $socialAuth;
			$authModel->provider = null;

			$authModel = $authModel->authenticate($socialAuth->provider);

			if (!$authModel->isNewRecord && $authModel->user_id != $userId)
			{
				$model = Users::model();
				$model->addError("user", "Account already linked with another user");
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if ($userId != null)
			{
				$model = Users::model()->findByPk($userId);
				goto skipAdd;
			}
			$profile = $authModel->getProfile();

			$emailId = $profile->emailVerified;

			if (empty($emailId))
			{
				$model = Users::model();
				$model->addError("email", "Can't verify email using this account.");
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$model = Users::model()->getByEmail($profile->emailVerified, true);
			if ($model)
			{
				goto skipAdd;
			}

			$type		 = SocialAuth::getTypeByProvider($socialAuth->provider);
			$contactSet	 = Contact::createBySocialProfile($authModel->getProfile(), $type);
			$contactId	 = $contactSet->getData()['contactId'];
			$model		 = Users::createbyContact($contactId);

			skipAdd:
			$authModel->user_id = $model->user_id;

			if (!$authModel->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			return $authModel;
		}
		catch (Exception $e)
		{
			ReturnSet::setException($e);
			throw $e;
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public function setSocialProfile(Hybrid_User_Profile $profile)
	{
		$this->usr_email			 = $profile->emailVerified;
		$this->email				 = $profile->emailVerified;
		$this->usr_email_verify		 = 1;
		$this->gender				 = $profile->gender;
		$this->usr_name				 = $profile->firstName;
		$this->usr_lname			 = $profile->lastName;
		$this->usr_mobile			 = ($profile->phone != '') ? str_replace(' ', '', $profile->phone) : $profile->phone;
		$this->usr_country			 = $profile->country;
		$this->usr_profile_pic_path	 = $profile->photoURL;
		$this->usr_city				 = $profile->city;
		$this->usr_zip				 = $profile->zip;
		$this->usr_create_platform	 = UserInfo::$platform;
		$pass						 = uniqid(rand(), TRUE);
		$this->usr_password			 = $pass;
		$this->new_password			 = $pass;
		$this->repeat_password		 = $pass;
	}

	public function whatsappShareTemplate($refcode)
	{
		$text				 = "I just travelled with Gozo Cabs. Excellent service. Amazing prices. Join & book with the link below. Once you travel, I will get 20% cashback. You will get ₹250 off on your first booking. After you travel, you can refer others to get 20% cashback too. www.aaocab.com/invite/" . $refcode;
		$whatappShareLink	 = urlencode($text);
		return $whatappShareLink;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $sosContactList
	 * @param type $travellerName
	 * @param type $url
	 * @param type $eventId
	 * @return \ReturnSet
	 */
	public static function sendNotificationToContact($bkgId, $sosContactList, $travellerName, $url, $eventId)
	{
		$returnSet = new ReturnSet();
		foreach ($sosContactList As $value)
		{
			$emergencyUserName	 = $value["name"];
			$phone				 = str_replace('-', '', str_replace(' ', '', $value["phon_no"]));
			$phoneNumber		 = substr($phone, -10);
			$emailAddress		 = $value["email"];
			$sosContacts[]		 = $phoneNumber;
			if ($eventId == BookingTrack::SOS_START)
			{
				$msg = "$travellerName has pressed panic button and wants to notify you of the emergency. Track their location at $url urgently contact them. Gozo is also taking action.";
			}
			else
			{
				$msg = "PANIC Situation resolved.Track $travellerName current location at $url";
			}

			if (strlen($phoneNumber) >= 10)
			{
				$msgCom		 = new smsWrapper();
				$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($bkgId, $phoneNumber, $msg);

				$bookingLogEvent = BookingLog::mapEvents();
				$oldEventId		 = $bookingLogEvent[$eventId];
				$desc			 = "EMERGENCY Contact ( $phoneNumber ) has been notified.";
				BookingLog::model()->createLog($bkgId, $desc, null, $oldEventId);
			}
			if ($emailAddress != '')
			{
				$emailModel		 = new emailWrapper();
				$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($bkgId, $travellerName, $emergencyUserName, $emailAddress, $msg);
			}
			if (!empty($sendSmsFlag) || !empty($sendEmailFlag))
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage($msg);
			}
			else
			{
				$returnSet->setStatus(false);
			}
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $primaryContactId
	 * @param type $duplicateContactId
	 */
	public function mergeConIds($primaryContactId, $duplicateContactId)
	{
		if (empty($primaryContactId) || empty($duplicateContactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql			 = "SELECT * FROM `users` WHERE `usr_contact_id`=:id";
		$arrDupUsrData	 = DBUtil::command($sql, DBUtil::MDB())->query(['id' => $duplicateContactId]);

		if (!empty($arrDupUsrData))
		{
			foreach ($arrDupUsrData as $drvData)
			{
				$usrId = $drvData["user_id"];

				$updateDuplicate = "UPDATE `users` 
							SET    `usr_contact_id` = $primaryContactId
							WHERE  usr_contact_id = $duplicateContactId AND user_id = $usrId";

				$numrows = DBUtil::command($updateDuplicate)->execute();

				ContactMerged::updateReferenceIds($primaryContactId, $duplicateContactId, ContactMerged::TYPE_USER, $usrId);
			}
		}
	}

	/**
	 * 
	 * @param mixed $contact Contact|int
	 * @throws Exception
	 */
	public static function createbyContact($contact, $sendPassword = 0)
	{
		if (!$contact instanceof Contact)
		{
			$contact = Contact::model()->findByPk($contact);
		}

		$cpModel = ContactProfile::model()->findByContactId($contact->ctt_id);
		if ($cpModel->cr_is_consumer > 0)
		{
			$userModel = Users::model()->findByPk($cpModel->cr_is_consumer);
			goto end;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$email							 = ContactEmail::getPrimaryEmail($contact->ctt_id);
			$phone							 = ContactPhone::model()->getContactPhoneById($contact->ctt_id);
			$phoneCode						 = ContactPhone::model()->getContactPhoneCodeById($contact->ctt_id);
			$phone							 = $phone ? $phone : "";
			$email							 = $email ? $email : "";
			$pasword						 = substr($contact->ctt_first_name, 0, 3) . time() . 'GZ';
			$userModel						 = new Users('new');
			$userModel->usr_contact_id		 = $contact->ctt_id;
			$userModel->usr_create_platform	 = UserInfo::$platform;
			$userModel->usr_acct_type		 = Users::AcctType_Verify;
			$userModel->usr_ip				 = \Filter::getUserIP();
			$userModel->usr_name			 = $contact->ctt_first_name ? $contact->ctt_first_name : "";
			$userModel->usr_lname			 = $contact->ctt_last_name ? $contact->ctt_last_name : "";
			$userModel->usr_mobile			 = ($phone != '') ? str_replace(' ', '', $phone) : $phone;
			$userModel->usr_country_code	 = ($phoneCode != '') ? str_replace(' ', '', $phoneCode) : $phoneCode;
			$userModel->usr_password		 = md5($pasword);
			$userModel->email				 = $email;
			$userModel->usr_email			 = $email;
			$userModel->usr_refer_code		 = Users::getUniqueReferCode($userModel);
			if ($contact->ctt_id > 0)
			{
				$modelCpr = ContactPref::model()->find("cpr_ctt_id=:cId", ['cId' => $contact->ctt_id]);
				if (!$modelCpr)
				{
					$modelCpr				 = new ContactPref();
					$modelCpr->cpr_ctt_id	 = $contact->ctt_id;
				}
				$modelCpr->cpr_category = 1; //bronze
				$modelCpr->save();
			}
			if (!$userModel->save())
			{
				$e = new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				throw $e;
			}
			Users::addSignUpBonus($userModel->user_id);
			if ($sendPassword > 0)
			{
				//// need to send mail and sms || need sms content for reset password
				if ($userModel->email)
				{
					$emailWrapper	 = new emailWrapper();
					$delivery		 = $emailWrapper->signupUserCredential($userModel->user_id, $pasword);

//				else
//				{
//					$link		 = 'aaocab.com' . Yii::app()->createUrl('users/changePassword', ['id' => $userModel->user_id, 'hash' => Yii::app()->shortHash->hash($this->user_id)]);
//					$smsModel	 = new smsWrapper(); 
//					$smsModel->sendLinkVendor($userModel->user_id, $userModel->usr_mobile, $userModel->usr_country_code, $link);
//				}
					if ($delivery['flag'] > 0)
					{
						$userModel->usr_changepassword = 1;
						$userModel->save();
					}
				}
			}
			$cpModel = ContactProfile::linkUserId($contact->ctt_id, $userModel->user_id);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $e;
		}


		end:
		return $userModel;
	}

	/** @deprecated please use ContactProfile::getUserId */
	public static function getByContactId($contactId)
	{
		$params	 = ["contactId" => $contactId];
		$sql	 = "SELECT user_id FROM users WHERE usr_contact_id=:contactId AND usr_active>0";
		return DBUtil::command($sql, DBUtil::MDB())->bindValues($params)->queryScalar();
	}

	/**
	 * Validates user data
	 * @param type $usrData
	 * @return type
	 * @throws Exception
	 */
	public static function validateAndTransferContact($usrData)
	{

		try
		{
			if (empty($usrData))
			{
				throw new Exception("Invalid data1", ReturnSet::ERROR_INVALID_DATA);
			}

			$userId = $usrData["user_id"];
			if (strpos($usrData["usr_email"], "gozo") || strpos($usrData["usr_email"], "test"))
			{
				throw new Exception("User Id: $userId Skipped as it contains gozo or test", ReturnSet::ERROR_INVALID_DATA);
			}

			$response	 = "User Id: $userId failed"; //Default
			$userModel	 = self::model()->findByPk($userId);
			$cttId		 = self::transferToContact($userModel);
			if ($cttId > 0)
			{
				$response = "User Id: $userId updated with Contact Id: $cttId";
			}
		}
		catch (Exception $ex)
		{
			$sql = "UPDATE users SET usr_tmp_status = 1 WHERE user_id = {$userId}";
			DBUtil::command($sql)->execute();
			//echo "\n {$userId} == ";
			//echo $response = $ex->getMessage();
			Logger::error($ex->getMessage());
		}

		return $response;
	}

	/**
	 * 
	 * @param Users $userModel
	 * @return int userId
	 * @throws Exception
	 */
	public static function transferToContact($userModel)
	{
		$emailId	 = $userModel->usr_email;
		$phoneNo	 = $userModel->usr_mobile;
		$firstName	 = $userModel->usr_name;
		$lastName	 = $userModel->usr_lname;
		if (empty($emailId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{
			$transaction = DBUtil::beginTransaction();
			$verified	 = false;
			$cttId		 = Contact::getIdByDetails($emailId, $phoneNo, $firstName, $lastName, $verified);
			if ($cttId > 0)
			{
				#$emailModel = ContactEmail::model()->findByEmailAndContact($emailId, $cttId);
				$numRows = DBUtil::command("SELECT COUNT(1) cnt FROM contact_email WHERE eml_email_address=:emailId AND eml_contact_id=:contactId AND eml_active=1")->queryScalar(['emailId' => $emailId, 'contactId' => $cttId]);
				if ($numRows == 0)
				{
					ContactEmail::addNew($cttId, $emailId, SocialAuth::Eml_Gozocabs, 1, '', $userModel->usr_email_verify);
				}
				if ($phoneNo)
				{
					#$phoneModel = ContactPhone::model()->findByPhoneAndContact($phoneNo, $cttId);
					$numRows = DBUtil::command("SELECT COUNT(1) cnt FROM contact_phone WHERE phn_phone_no=:phoneId AND phn_contact_id=:contactId AND phn_active=1")->queryScalar(['phoneId' => $phoneNo, 'contactId' => $cttId]);
					if ($numRows == 0)
					{
						ContactPhone::add($cttId, $phoneNo, '', $userModel->usr_country_code, 1, 1, '', $userModel->usr_mobile_verify);
					}
				}
				self::updateContactId($cttId, $userModel->user_id);
				ContactProfile::setProfile($cttId, UserInfo::TYPE_CONSUMER);
				//ContactProfile::updateEntity($cttId, $userModel->user_id, UserInfo::TYPE_CONSUMER);
				goto End;
			}

			$cttId = Contact::createByUser($userModel);

			End:
			DBUtil::commitTransaction($transaction);
			// DBUtil::rollbackTransaction($transaction);
			return $cttId;
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * This function is used for updating user contact Id
	 * @param type $cttIds
	 * @param type $userId
	 * @throws Exception
	 */
	public static function updateContactId($cttIds = null, $userId = null)
	{
		if (empty($cttIds) || empty($userId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql	 = "UPDATE users SET usr_contact_id = $cttIds WHERE user_id = $userId";
		$numrows = DBUtil::command($sql)->execute();
	}

	/**
	 * 
	 * @param type $profileData
	 * @param type $fetchData
	 * @param type $user
	 */
	public static function createContactBySocialUser($profileData, $fetchData, $user)
	{

		if ($fetchData['provider'] == 'Google')
		{
			$provider = 2;
		}

		if ($fetchData['provider'] == 'Facebook')
		{
			$provider = 3;
		}

		$jsonObj									 = new stdClass();
		$jsonObj->profile->firstName				 = trim($profileData['firstName']);
		$jsonObj->profile->lastName					 = trim($profileData['lastName']);
		$jsonObj->profile->email					 = trim($profileData['email']);
		$jsonObj->profile->primaryContact->number	 = trim($profileData['phone']);
		$jsonObj->profile->primaryContact->code		 = trim($profileData['usr_country_code']);
		$jsonObj->profile->profilePic				 = $user->usr_profile_pic;

		$contactId = 0;
		if ($profileData['email'] === $user->usr_email)
		{
			$contactId = $user->usr_contact_id;
			if ($contactId)
			{
				Contact::modifyContact($jsonObj, $contactId, 0, UserInfo::TYPE_CONSUMER, $provider);
			}
			else
			{
				$returnSet	 = Contact::createContact($jsonObj, 1, UserInfo::TYPE_CONSUMER, $provider);
				$contactId	 = $returnSet->getData()['id'];
			}
		}

		return $contactId;
	}

	public static function verifyContactItem($usrData)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (empty($usrData))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$contactId	 = $usrData["usr_contact_id"];
			$email		 = $usrData["usr_email"];
			$phoneNo	 = $usrData["usr_mobile"];
			$emailModel	 = ContactEmail::model()->findByEmailAndContact($email, $contactId);
			$phoneModel	 = ContactPhone::model()->findByPhoneAndContact($phoneNo, $contactId);
			$success	 = false;
			if ($emailModel)
			{
				$emailModel->eml_is_verified = 1;
				$success					 = $emailModel->save();
			}
			if ($phoneModel)
			{
				$phoneModel->phn_is_verified = 1;
				$success					 = $phoneModel->save();
			}
			DBUtil::commitTransaction($transaction);
			if ($success)
			{
				echo "verified: " . $contactId . "==";
			}
		}
		catch (Exception $ex)
		{
			$response = $ex->getMessage();
			echo "error: " . $contactId . "==";
			Logger::error($ex->getMessage());
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public function findByContactID($cttId)
	{
		if ($cttId == null || $cttId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$model = self::model()->findAll("usr_contact_id =:cttId", array("cttId" => $cttId));
		return $model;
	}

	/**
	 * This function is used fot storing contact item in user and in future we will remove it.
	 * @param type $model
	 * @param type $contactArray
	 * @param type $phoneArray
	 * @param type $emailArray
	 * @return type $model
	 */
	public static function userContactItem($model, $contactArray, $phoneArray, $emailArray)
	{
		$phone	 = trim($phoneArray['phn_phone_no']);
		$isValid = Filter::validatePhoneNumber($phone);
		if ($isValid)
		{
			Filter::parsePhoneNumber($phone, $code, $number);
		}
		$model->usr_name		 = trim($contactArray['ctt_first_name']);
		$model->usr_lname		 = trim($contactArray['ctt_last_name']);
		$model->usr_email		 = trim($emailArray['eml_email_address']);
		$model->usr_mobile		 = ($phone != '') ? str_replace(' ', '', $phone) : $phone;
		$model->usr_city		 = trim($contactArray['ctt_city']);
		$model->usr_state		 = trim($contactArray['ctt_state']);
		$model->usr_country_code = $code;
		$model->usr_address1	 = trim($contactArray['ctt_address']);

		return $model;
	}

	/**
	 * This function is used for user input
	 * @param type $model
	 * @param type $arr1
	 * @param type $response
	 * @return type boolean
	 */
	public static function userData($model, $arr1)
	{
		if (!$model->usr_contact_id)
		{
			$model->repeat_password		 = $arr1['repeat_password'];
			$model->new_password		 = $arr1['new_password'];
			$model->usr_password		 = $arr1['new_password'] ? $arr1['new_password'] : '';
			$model->usr_gender			 = $arr1['usr_gender'];
			$model->usr_create_platform	 = Users::Platform_Web;
			$model->usr_ip				 = \Filter::getUserIP();
			$model->usr_device			 = UserLog::model()->getDevice();
		}
		else
		{
//			$model->usr_lname		 = $arr1['usr_lname'];
			//$model->usr_country_code = $arr1['usr_country_code'];
			$userModel				 = Users::model()->findByConId($model->usr_contact_id);
			$model->usr_password	 = $userModel->usr_password;
			$model->usr_country		 = $arr1['usr_country'];
			$model->usr_gender		 = $arr1['usr_gender'];
			$model->usr_state_text	 = $arr1['usr_state_text'];
		}
		return $model;
	}

	public static function findByConID($cttId)
	{
		$model = self::model()->find(array("condition" => "usr_contact_id =$cttId AND usr_active = 1"));
		return $model;
	}

	public static function createByUser($userModel)
	{
		$jsonObj									 = new stdClass();
		$jsonObj->profile->firstName				 = $userModel->usr_name;
		$jsonObj->profile->lastName					 = $userModel->usr_lname;
		$jsonObj->profile->email					 = $userModel->usr_email;
		$jsonObj->profile->primaryContact->number	 = $userModel->usr_mobile;
		$jsonObj->profile->primaryContact->code		 = ($userModel->usr_country_code) ? $userModel->usr_country_code : "91";
		$returnSet									 = Contact::createContact($jsonObj, 0, UserInfo::TYPE_CONSUMER);
		$contactId									 = $returnSet->getData()["id"];
		self::updateContactId($contactId, $userModel->user_id);
		return $contactId;
	}

	/**
	 * 
	 * @param int $userId
	 * @return type
	 */
	public static function cntBookingById($userId)
	{
		$params	 = ['usrId' => $userId];
		$sql	 = "SELECT COUNT(1) as totBooking
				FROM `booking` 
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1  AND booking.bkg_status IN (2,3,5,6,7) 
				INNER JOIN `booking_user` On booking_user.bui_bkg_id=booking.bkg_id 
                INNER JOIN `users` ON users.user_id=booking_user.bkg_user_id 
				WHERE booking_user.bkg_user_id=:usrId";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
	}

	/**
	 * 
	 * @param int $userId
	 * @return string
	 */
	public static function getLastBookingById($userId)
	{
		$params	 = ['usrId' => $userId];
		$sql	 = "SELECT booking.bkg_id
				FROM `booking` 
				INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 
				INNER JOIN `booking_user` On booking_user.bui_bkg_id=booking.bkg_id AND booking_user.bkg_user_id=:usrId
				WHERE booking.bkg_active=1  AND booking.bkg_status IN (6,7)
				ORDER BY booking.bkg_pickup_date DESC 
				LIMIT 0,1";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
	}

	/**
	 * @param Users $model
	 * @param String $referralCode
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function processReferralCode($model, $referralCode)
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($model) || $model->usr_referred_code == null)
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}
			$model->usr_referred_code	 = $referralCode;
			$model->usr_referred_id		 = Users::getIdByReferCode($referralCode);
			if (!$model->save())
			{
				$e = new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_FAILED);
				throw $e;
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return ReturnSet
	 */
	public static function processReferralBonous($bkgId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet = new ReturnSet();

		$model		 = Booking::model()->findByPk($bkgId);
		$joinerId	 = $model->bkgUserInfo->bkgUser->user_id;
		$inviteeId	 = $model->bkgUserInfo->bkgUser->usr_referred_id;
		$amount		 = $model->bkgInvoice->bkg_net_base_amount;
		Logger::trace("JoinerId: " . $joinerId . " InviteeId: " . $inviteeId . " Amount: " . $amount);
		if ($joinerId == null || $inviteeId == null || $amount == null)
		{
			$e			 = new Exception("Invitee or joiner Id can not be null.", ReturnSet::ERROR_VALIDATION);
			$returnSet	 = ReturnSet::setException($e);
			goto skipReferBonus;
		}
		$totalBooking = self::cntBookingById($joinerId);
		if ($totalBooking > 1)
		{
			$e			 = new Exception("Bonus not applicable", ReturnSet::ERROR_VALIDATION);
			$returnSet	 = ReturnSet::setException($e);
			goto skipReferBonus;
		}
		$actualAmount	 = $amount;
		$referralConfig	 = Config::get("user.referral");
		if ($referralConfig["limitType"] > 0)
		{
			$lastBkgId	 = Users::getLastBookingById($inviteeId);
			$bivModel	 = BookingInvoice::model()->getByBookingID($lastBkgId);
			if ($bivModel == null)
			{
				goto skipAmount;
			}

			$inviteeLastBookingAmount = $bivModel->bkg_net_base_amount;
			switch ($referralConfig["limitType"])
			{
				case 2:
					$actualAmount	 = max([$amount, $inviteeLastBookingAmount]);
					break;
				case 1:
					$actualAmount	 = min([$amount, $inviteeLastBookingAmount]);
				default:
					$actualAmount	 = min([$amount, $inviteeLastBookingAmount]);
					break;
			}
		}

		skipAmount:

		$bonusAmount = self::getBonusValue($actualAmount, 1);

		if ($bonusAmount === null)
		{
			$e			 = new Exception("Unable to calculate bonus amount", ReturnSet::ERROR_FAILED);
			$returnSet	 = ReturnSet::setException($e);
			goto skipReferBonus;
		}

		$returnSet = self::processBonus(1, $bonusAmount, $inviteeId, $joinerId, $bkgId, $lastBkgId);
		if ($returnSet->isSuccess())
		{
			$eventId = BookingLog::BONUS_REFERRAL;
			if ($remarks == null)
			{
				if ($lastBkgId > 0)
				{
					$refText		 = '';
					$objUserInvitee	 = Contact::model()->getByUserId($inviteeId);
					if ($objUserInvitee)
					{
						$refText = $objUserInvitee->getName();
					}

					$refText .= " (" . Booking::model()->getCodeById($lastBkgId) . ")";
				}
				else if ($bkgId > 0)
				{
					$refText		 = '';
					$objUserJoiner	 = Contact::model()->getByUserId($joinerId);
					if ($objUserJoiner)
					{
						$refText = $objUserJoiner->getName();
					}

					$refText .= " (" . Booking::model()->getCodeById($bkgId) . ")";
				}

				$remarks = "Bonus $bonusAmount added for referring user " . $refText;
			}

			BookingLog::model()->createLog($bkgId, $remarks, UserInfo::getInstance(), $eventId);
			return $returnSet;
		}

		skipReferBonus:
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	/**
	 * 
	 * @param type $type           | 1=>Wallet,2=>GozoCoins,3=>Vouchers,4=>Promos 
	 * @param type $value		   | bonousAmount	
	 * @param type $userId		   | User in which amount has to be credited 	
	 * @param type $refUserId	   | User due to which amount is getting credited 	
	 * @param type $refId		   | refId => joinerId / vouhcerId / promoId	
	 * @param type $inviterBkgId   | Inviter BKG Id	
	 * @return ReturnSet
	 */
	public static function processBonus($type, $value, $userId, $refUserId, $refId, $inviterBkgId)
	{
		switch ($type)
		{
			case 1:
				$returnSet	 = UserWallet::addReferralAmount($value, $userId, $refUserId, ["bkgId" => $refId, "inviterBkgId" => $inviterBkgId]);
				break;
			case 2:
				$returnSet	 = UserCredits::addReferralCoins($value, $userId, $refUserId, ["bkgId" => $refId]);
				break;
			case 3:
				$returnSet	 = VoucherSubscriber::add($value, $userId, $refUserId);
				break;
			case 4:
				$returnSet	 = UserCredits::addReferralCoins($userId, $value, $refUserId, ["bkgId" => $refId]);
				break;
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param int $amount
	 * @param int $type
	 * @return type
	 */
	public static function getBonusValue($amount, $type = 1)
	{
		$referralConfig	 = Config::get("user.referral");
		$refType		 = ($type == 2) ? "joiner" : "invitee";
		$userConfig		 = $referralConfig[$refType];
		switch ($userConfig["type"])
		{
			case 2:
				$value	 = $userConfig["value"];
				break;
			case 1:
				$value	 = self::calculateBonus($userConfig, $amount);
				break;
		}
		return $value;
	}

	/**
	 * 
	 * @param array $config
	 * @param int $amount
	 * @return int
	 */
	public static function calculateBonus($config, $amount = 0)
	{
		switch ($config["calType"])
		{
			case 2:
				$value	 = $config["value"];
				break;
			case 1:
				$value	 = round($config["value"] * 0.01 * $amount);   // 20 percent

				$value = ($config["min"] > 0) ? max([$config["min"], $value]) : $value; // min

				$value	 = ($config["max"] > 0) ? min([$config["max"], $value]) : $value;   // max  
				break;
			default:
				$value	 = 0;
				break;
		}
		return $value;
	}

	/** @return booleans */
	public static function processPayment($userId, $amount)
	{
		$success = UserWallet::add($userId, -1 * $amount);
		return $success;
	}

	/**
	 * 
	 * @return \CSqlDataProvider
	 */
	public function getReferralBonousList($type = '')
	{
		$sql = "SELECT account_transactions.act_amount,account_transactions.act_date, account_transactions.act_remarks, account_transactions.act_type,CONCAT(usr1.usr_name,' ',usr1.usr_lname) as referralName, CONCAT(usr2.usr_name,' ',usr2.usr_lname) as inviteeName,
					usr1.usr_contact_id as invitee_contact_id , usr2.usr_contact_id as inviter_contact_id 
					FROM `account_transactions` 
					JOIN `account_trans_details` as trans1 ON trans1.adt_trans_id=account_transactions.act_id AND trans1.adt_ledger_id=51
					JOIN `account_trans_details` as trans2 ON trans2.adt_trans_id=account_transactions.act_id AND trans2.adt_ledger_id=47
					JOIN `users` as usr1 ON usr1.user_id=trans1.adt_trans_ref_id AND usr1.usr_active=1
					JOIN `users` as usr2 ON usr2.user_id=trans2.adt_trans_ref_id AND usr2.usr_active=1
				    WHERE trans1.adt_ledger_id=51 ORDER BY account_transactions.act_created DESC";

		$sqlCount = "SELECT account_transactions.act_id
					FROM `account_transactions` 
					JOIN `account_trans_details` as trans1 ON trans1.adt_trans_id=account_transactions.act_id AND trans1.adt_ledger_id=51
					JOIN `account_trans_details` as trans2 ON trans2.adt_trans_id=account_transactions.act_id AND trans2.adt_ledger_id=47
					JOIN `users` as usr1 ON usr1.user_id=trans1.adt_trans_ref_id AND usr1.usr_active=1
					JOIN `users` as usr2 ON usr2.user_id=trans2.adt_trans_ref_id AND usr2.usr_active=1
				    WHERE trans1.adt_ledger_id=51 ORDER BY account_transactions.act_created DESC";

		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) a", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['act_remarks', 'act_date', 'act_amount'],
					'defaultOrder'	 => 'act_created DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	/**
	 * 
	 * @param integer $userId
	 * @return ReturnSet
	 */
	public static function getReferUrl($userId)
	{
		$returnSet	 = new ReturnSet();
		$usrModel	 = Users::model()->findByPk($userId);
		if ($usrModel->usr_refer_code == null)
		{
			$records = Self::getRefercode($userId);
			if ($records == false)
			{
				$e			 = new Exception('Unable to create refer code.', ReturnSet::ERROR_INVALID_DATA);
				$returnSet->setStatus(false);
				$returnSet	 = ReturnSet::setException($e);
			}
			$usrModel->usr_refer_code = $records['refCode'];
		}
		$referUrl	 = Yii::app()->params['fullBaseURL'] . "/invite/" . $usrModel->usr_refer_code;
		$referUrl	 = Filter::shortUrl($referUrl);
		$returnSet->setStatus(true);
		$returnSet->setData(['referUrl' => $referUrl]);
		return $returnSet;
	}

	/** @return libphonenumber\PhoneNumber */
	public static function getPrimaryPhone($userId, $isVerified = false)
	{
		if ($userId == '')
		{
			return null;
		}

		$cttModel = Contact::model()->getByUserId($userId);
		if (!$cttModel)
		{
			return null;
		}

		$objPhoneNumber = ContactPhone::getPrimaryNumber($cttModel->ctt_id, $isVerified);
		return $objPhoneNumber;
	}

	/** @deprecated since 20/02/2021 */
	public static function getPhonenumbersByid($userId, $isprimary = false)
	{
		$params	 = ['userId' => $userId];
		$where	 = ' AND 1';
		if ($isprimary)
		{

			$where = ' AND phn.phn_is_primary=1';
		}
		$sql	 = "SELECT DISTINCT phn_phone_country_code,phn_phone_no 
			 FROM users usr JOIN 
			`contact_phone` phn ON phn.phn_contact_id = usr.usr_contact_id WHERE usr.user_id =:userId
			$where
			";
		$listObj = DBUtil::query($sql, DBUtil::SDB(), $params);
		$list	 = [];
		foreach ($listObj as $data)
		{
			$ext	 = ($data['phn_phone_country_code'] > 0) ? $data['phn_phone_country_code'] : '91';
			$list[]	 = $ext . $data['phn_phone_no'];
		}
		return $list;
	}

	/**
	 * 
	 * @param integer $type
	 * @param integer $refId    
	  [ refId = userId : CREDIT_REFERRAL , refId = bkgId : CREDIT_BOOKING ]
	 * @return boolean
	 */
	public static function getSignupBonusValue($type, $refId)
	{
		$signupConfig = Config::get("user.signup.bonus");
		if ($signupConfig["type"] == 0)
		{
			return false;
		}
		switch ($signupConfig["type"])
		{
			case 3:
				$value	 = $signupConfig["value"];
				break;
			case 4:
				$value	 = BookingInvoice::model()->getBonusAmount($refId);
				break;
			case 5:
				$value	 = $signupConfig["value"];
				break;
		}
		return $value;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param integer $bkgId
	 * @return ReturnSet|false will return false if not enabled
	 */
	public static function addSignUpBonus($userId)
	{
		$returnSet = new ReturnSet();
		try
		{
			$signupConfig	 = Config::get("user.signup.bonus");
			$type			 = $signupConfig["type"];
			$amount			 = $signupConfig["value"];
			if ($type == 0 || $amount == 0)
			{
				return false;
			}
			$model	 = Users::model()->findByPk($userId);
			$remarks = "Signup Bonus added";
			switch ($type)
			{
				case 1:
					$transModel	 = UserCredits::addCoins($model->user_id, UserCredits::CREDIT_SIGNUP, null, $amount, null, $remarks, 1);
					break;
				case 2:
					$transModel	 = AccountTransactions::processWallet(new CDbExpression("NOW()"), ($amount * -1), null, Accounting::AT_GIFTCARD, Accounting::LI_JOINING_BONUS, $userId, $remarks, '', UserInfo::getInstance());
					break;
			}

			if ($transModel)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage($remarks);
			}
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
		}
		return $returnSet;
	}

	public function getDrvIdByOauth($oauth)
	{
		$driverId	 = null;
		$tablePrefix = Yii::app()->db->tablePrefix;
		$oauthtable	 = $tablePrefix . 'user_oauth';
		$params		 = ['provider' => trim($oauth->provider), 'identifier' => trim($oauth->identifier)];
		$sql		 = "SELECT user_id  FROM $oauthtable WHERE `provider`=:provider AND identifier  =:identifier";
		$userID		 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		$drvModel	 = Drivers::getByUserId($userID);
		if ($drvModel)
		{
			$driverId = $drvModel->drv_id;
		}
		return $driverId;
	}

	public static function findByEmailId($email)
	{
		if ($email == null || $email == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		Users::activeStatus($email);
		$param		 = ['email' => $email];
		$sql		 = "
                    SELECT usr_contact_id
					FROM `users`
					WHERE usr_email =:email
					AND usr_active = 1
			        ";
		$emailData	 = DBUtil::command($sql, DBUtil::SDB())->query($param);
		return $emailData;
	}

	public static function inactiveStatus($cttId, $email = NULL)
	{
		$params	 = ['cttId' => $cttId, 'email' => $email];
		$sql	 = "UPDATE users SET usr_active = 0 WHERE usr_contact_id =:cttId AND usr_email =:email AND usr_active = 1";
		DBUtil::command($sql, DBUtil::MDB())->execute($params);
	}

	public static function activeStatus($email = NULL)
	{
		$params	 = ['email' => $email];
		$sql	 = "UPDATE users SET usr_active = 1 WHERE  usr_email =:email AND usr_active = 0";
		DBUtil::command($sql, DBUtil::MDB())->execute($params);
	}

	public static function saveData($userId = NULL, $cpassword = NULL, $email = NULL, $contactId = NULL)
	{
		$status						 = "";
		$userModel					 = Users::model()->findByPk($userId);
		$userModel->usr_password	 = md5($cpassword);
		$userModel->usr_email_verify = 1;
		if ($userModel->save())
		{
			ContactEmail::verifyItems($email, $contactId);
			$status = "success";
		}
		return $status;
	}

	public static function savePhoneData($userId = NULL, $cpassword = NULL, $phone = NULL, $contactId = NULL)
	{
		$status					 = "";
		$userModel				 = Users::model()->findByPk($userId);
		$userModel->usr_password = md5($cpassword);
		if ($userModel->save())
		{
			ContactPhone::updateVerifyStatus($contactId, $phone);
			$phoneModel					 = ContactPhone::model()->findByPhoneAndContact($phone, $contactId);
			$phoneModel->phn_is_expired	 = 1;
			if ($phoneModel->save())
			{
				$status = "success";
			}
		}
		return $status;
	}

	public static function getNameByUserId($usrId)
	{
		$param		 = [usrId => $usrId];
		$sql		 = "SELECT CONCAT(users.usr_name,' ',users.usr_lname) AS name FROM users WHERE user_id =:usrId";
		$userName	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($param);
		return $userName;
	}

	/**
	 * 
	 * @param string $email
	 * @param integer $phone
	 * @return integer
	 */
	public static function getIdByContactEmailPhone($email, $phone)
	{
		if (trim($email) != '')
		{
			$emailRecord = ContactEmail::getByEmail($email, $phone);
		}
		if (trim($phone) != '')
		{
			$phoneRecord = ContactPhone::getByPhone($phone, $email);
		}
		$contactId = Contact::getIdByRecord($emailRecord, $phoneRecord);
		return ContactProfile::getUserId($contactId);
	}

	/**
	 * GET All User Ids linked with given email.
	 * @param string $email
	 * @return array()
	 *  */
	public static function getIdsByEmail($email)
	{
		$sql = "SELECT users.user_id FROM contact_email cem
				INNER JOIN contact ON ctt_id=eml_contact_id AND ctt_active=1 AND eml_active=1
				INNER JOIN contact_profile cpr ON cpr.cr_contact_id=ctt_id AND cpr.cr_status=1
				INNER JOIN users ON users.user_id=cpr.cr_is_consumer AND users.usr_active=1
				WHERE  cem.eml_email_address=:email  AND (cem.eml_is_verified=1 OR cem.eml_is_primary=1)
				ORDER BY cem.eml_is_verified DESC, cem.eml_is_primary DESC";

		$rows = DBUtil::query($sql, DBUtil::SDB(), ["email" => $email]);
		return $rows;
	}

	/**
	 * GET All User Ids linked with given phone.
	 * @param string $phone
	 * @return array()
	 *  */
	public static function getIdsByPhone($phone)
	{
		Filter::parsePhoneNumber($phone, $code, $number);
		$sql = "SELECT users.user_id FROM contact_phone cph
				INNER JOIN contact ON ctt_id=phn_contact_id AND ctt_active=1 AND phn_active=1
				INNER JOIN contact_profile cpr ON cpr.cr_contact_id=ctt_id AND cpr.cr_status=1
				INNER JOIN users ON users.user_id=cpr.cr_is_consumer AND users.usr_active=1
				WHERE  cph.phn_full_number=:phone AND (phn_is_primary=1 OR phn_is_verified=1)
				ORDER BY phn_is_verified DESC, phn_is_primary DESC";

		$rows = DBUtil::query($sql, DBUtil::SDB(), ["phone" => $code . $number]);
		return $rows;
	}

	/**
	 * @param int $userId
	 * @param int $telegramId
	 * @return true | false
	 *  */
	public static function updateIreadVal($userId, $telegramId)
	{
		$params	 = ['userId' => $userId];
		$sql	 = "UPDATE imp_user_oauth SET iread_id = $telegramId WHERE user_id = :userId";
		DBUtil::command($sql, DBUtil::MDB())->execute($params);
	}

	/**
	 * @param int $provider
	 * @param int $identifier
	 * @return int | false
	 *  */
	public static function getSocialLoginId($provider, $identifier)
	{
		$param	 = ['identifier' => $identifier];
		$sql	 = "SELECT user_id, iread_id  FROM `imp_user_oauth` WHERE `provider` LIKE '%$provider%' AND identifier = :identifier";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		return $result;
	}

	/*
	 * Get Customer Info tab details with the booking Details for the consumer Page
	 * @param int $userid
	 * @return array()
	 */

	public function getContactViewDetails($userid)
	{
		$sql = "SELECT u.user_id, ctt.ctt_id, ctt.ctt_user_type, ctt.ctt_business_name, ctt.ctt_first_name, ctt.ctt_last_name,
				e.eml_email_address, p.phn_phone_country_code, p.phn_phone_no, p.phn_is_verified, ctt.ctt_state, ctt.ctt_city, ctt.ctt_address,
				cty.cty_id, cty.cty_name, cty.stt_name, u.usr_gender, u.usr_ip, u.usr_zip, cp.cr_is_consumer,
				cp.cr_is_vendor, cp.cr_is_driver, COUNT(DISTINCT bu.bui_id) AS booking_count, MAX(a.apt_last_login) AS last_login, ctt_tags
				FROM users AS u 
				INNER JOIN contact_profile AS cp ON cp.cr_is_consumer = u.user_id AND cp.cr_status = 1 
				INNER JOIN contact AS ctt ON cp.cr_contact_id = ctt.ctt_id AND ctt.ctt_active = 1 AND ctt.ctt_ref_code = ctt.ctt_id 
				LEFT JOIN contact_email AS e ON ctt.ctt_id = e.eml_contact_id AND e.eml_is_primary = 1 AND e.eml_active = 1 
				LEFT JOIN contact_phone AS p ON ctt.ctt_id = p.phn_contact_id AND p.phn_is_primary = 1 AND p.phn_active = 1 
				LEFT JOIN city_list AS cty ON cty.cty_id = ctt.ctt_city 
				LEFT JOIN booking_user bu ON bu.bkg_user_id = u.user_id 
				LEFT JOIN booking bkg ON bkg.bkg_id = bu.bui_bkg_id AND bkg.bkg_status IN (2,3,5,6,7,9)
				LEFT JOIN app_tokens AS a ON u.user_id = a.apt_user_id 
				WHERE u.user_id = {$userid} 
				GROUP BY u.user_id";

		$userModel = DBUtil::queryRow($sql);

		$bookingsql = "SELECT bkg_id, bkg_booking_id, bkg_pickup_date, bkg_create_date, bkg_return_date,
						bkg_trip_distance, bkg_trip_duration, CEIL(bkg_trip_duration/1440) AS trip_duration_days,
						bkg_booking_type, bkg_status, bkg_user_fname, bkg_user_lname, bkg_total_amount,
						bkg_vehicle_type_id, bkg_route_city_names, c1.cty_id AS frm_city_code,
						c1.cty_name AS from_city, c2.cty_name AS to_city, c2.cty_id AS to_city_code,
						booking_user.bkg_contact_no as contact_no, IFNULL(booking_invoice.bkg_gozo_amount, 0) as gozo_amount,
						ratings.rtg_customer_overall as customer_rating, bcb.bcb_vendor_id AS vendor_id,bcb.bcb_driver_id AS driver_id
						FROM `booking`
						INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id 
						INNER JOIN `booking_cab` as bcb ON bcb.bcb_id = booking.bkg_bcb_id 
						INNER JOIN `booking_user` ON booking_user.bui_bkg_id = booking.bkg_id  
						INNER JOIN `cities` c1 ON c1.cty_id = booking.bkg_from_city_id
						INNER JOIN `cities` c2 ON c2.cty_id = booking.bkg_to_city_id
						LEFT JOIN ratings ON ratings.rtg_booking_id = bkg_id 
						WHERE bkg_status IN (2,3,5,6,7,9) AND bkg_active = 1 AND booking_user.bkg_user_id = {$userid}";

		$result = DBUtil::query($bookingsql);

		$ongoingbooking	 = [];
		$upcomingbooking = [];
		if (!empty($result))
		{
			foreach ($result as $r)
			{
				if ($r['bkg_pickup_date'] >= date("Y-m-d H:i:s", strtotime('-2 hours')) && $r['bkg_status'] == '5')
				{
					$ongoingbooking = $r;
				}
				if ($r['bkg_pickup_date'] >= date("Y-m-d H:i:s") && $r['vendor_id'] != null && $r['driver_id'] != null)
				{
					$upcomingbooking = $r;
				}
			}
		}

		return ['userModel' => $userModel, 'bookingModel' => $result, "ongoingbooking" => $ongoingbooking, 'upcomingbooking' => $upcomingbooking];
	}

	/*
	 * Get Trip details Booking list for the Tripdetails Tab in consumer view Page
	 * @param int $userid
	 * @return array()
	 */

	public function getTripDetailsbyUser($userid, $searchBy = 0)
	{
		if ($searchBy == 1)
		{
			$cond .= " AND bkg.bkg_status IN (6,7,9)";
		}
		else
		{
			$cond .= " AND bkg.bkg_status IN (2,3,5,6,7,9,15)";
		}
		$sql = "SELECT
                    `bkg_id`,
                    `bkg_booking_id`,
                    `bkg_booking_type`,
                    `bkg_pickup_date`,
					bkg_status,
                    c1.cty_display_name bkg_from_city,
                    c2.cty_display_name bkg_to_city,
                    vhc.vhc_number,
                    vhc.vhc_id,
                    vhc.vhc_code,
                    IFNULL(rtg.rtg_customer_overall, 'N/A') bkg_customer_overall,
                    IF(rtg.rtg_customer_review = '', 'N/A', IFNULL(rtg.rtg_customer_review, 'N/A')) bkg_customer_review,
 bcb.bcb_vendor_id,v.vnd_name AS vendor_name,bcb.bcb_driver_id,d.drv_name AS driver_name
                FROM
                    booking bkg
                INNER JOIN booking_cab bcb ON
                    bkg.bkg_bcb_id = bcb.bcb_id
                INNER JOIN cities c1 ON
                    bkg.bkg_from_city_id = c1.cty_id
                INNER JOIN cities c2 ON
                    bkg.bkg_to_city_id = c2.cty_id
                LEFT JOIN vehicles vhc ON
                    bcb.bcb_cab_id = vhc.vhc_id
                LEFT JOIN ratings rtg ON
                    bkg.bkg_id = rtg.rtg_booking_id
				LEFT JOIN vendors AS v ON
					bcb.bcb_vendor_id = v.vnd_id
				LEFT JOIN drivers AS d ON
					bcb.bcb_driver_id = d.drv_id
                INNER JOIN booking_user AS bu ON bu.bui_bkg_id =  bkg.bkg_id
                WHERE
                    bu.bkg_user_id = " . $userid . " $cond ORDER BY bkg.bkg_id DESC";

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_id'],
				'defaultOrder'	 => 'bkg_pickup_date DESC'],
			'pagination'	 => ['pageSize' => 10],
		]);
		return $dataprovider;
	}

	public function getUserSocialDetails($userId)
	{
		$sql = "SELECT DISTINCT (a.user_id),
		a.provider,
		a.provider_count,
		a.profile_cache
        FROM (SELECT DISTINCT (user_id),
                      provider,
                      if(provider != '0', 1, 0) AS provider_count,
                      profile_cache                      
        FROM imp_user_oauth
        WHERE imp_user_oauth.user_id = " . $userId .
				") as a 
		 HAVING provider_count = 1
		";
		return DBUtil::queryAll($sql);
	}

	public function deleteSocialDetails($userId, $provider = '')
	{
		$cond = '';
		if ($provider != "")
		{
			$cond .= " AND provider = '" . $provider . "'";
		}
		$sql = "DELETE
        FROM imp_user_oauth
        WHERE imp_user_oauth.user_id = " . $userId . "" . $cond;
		return DBUtil::command($sql, DBUtil::MDB())->execute();
	}

	public static function getUserIdByUserInfo($value, $type)
	{
		switch ($type)
		{
			case 1:
				$cond	 .= "AND usr_email = '$value' ";
				break;
			case 2:
				$cond	 .= " AND usr_mobile = '$value'";
				break;
		}
		$sql	 = "SELECT user_id FROM users where usr_active > 0 $cond";
		$value	 = DBUtil::queryScalar($sql);
		return $value;
	}

	public static function getByName($name)
	{
		$sql = "SELECT u1.user_id, u1.usr_name, u1.usr_lname,ctt_first_name,ctt_last_name,ctt_business_name
				FROM `users` u1 
				INNER JOIN contact_profile ON contact_profile.cr_is_consumer = u1.user_id AND cr_status=1 
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				WHERE 1 AND (u1.usr_name LIKE '%$name%' || u1.usr_lname LIKE '%$name%' || 
				contact.ctt_first_name LIKE '%$name%' || 
				contact.ctt_last_name LIKE '%$name%')";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getById($id)
	{
		$sql = "SELECT ctt_first_name,ctt_last_name,ctt_business_name
				FROM `users` u1 
				INNER JOIN contact_profile ON contact_profile.cr_is_consumer = u1.user_id AND cr_status=1 
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				WHERE 1 AND u1.user_id = $id";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function getPersonList()
	{
		$personoptions = [["id" => UserInfo::TYPE_CONSUMER, "text" => "Customer"],
			["id" => UserInfo::TYPE_VENDOR, "text" => 'Vendor'],
			["id" => UserInfo::TYPE_DRIVER, "text" => 'Driver'],
			["id" => UserInfo::TYPE_AGENT, "text" => 'Agent']];
		return $personoptions;
	}

	/**
	 * @param string $name	 
	 * @return string	   
	 */
	public static function generateRefCode($name)
	{
		$userName	 = preg_replace("/[^a-zA-Z0-9]+/", "", $name);
		$refCode	 = trim(substr($userName, 0, 3) . rand(100, 999) . bin2hex(random_bytes(2)) . substr(uniqid(), -4));
		return $refCode;
	}

	public static function uploadAllToS3($limit = 1000)
	{
		while ($limit > 0)
		{
			$limit1		 = min([1000, $limit]);
			$serverId	 = Config::getServerID();
			if ($serverId == '' || $serverId <= 0)
			{
				Logger::writeToConsole('Server ID not found!!!');
				break;
			}
			$condPath = " AND (usr_s3_data IS NULL AND usr_qr_code_path LIKE '%/doc/{$serverId}/qrcode%') ";

			$sql = "SELECT user_id FROM users 
					WHERE 1 AND usr_active = 1 {$condPath}
					ORDER BY user_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql, DBUtil::SDB());

			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				try
				{
					$usrModel = Users::model()->findByPk($row["user_id"]);
					$usrModel->uploadUserFileToSpace();
				}
				catch (Exception $ex)
				{
					ReturnSet::setException($ex);
				}
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadUserFileToSpace($removeLocal = true)
	{

		$spaceFile	 = null;
		$usrModel	 = $this;
		try
		{
			$localFilePath = $usrModel->getLocalQrCodePath();
			if (!file_exists($localFilePath) || $usrModel->usr_qr_code_path == '')
			{

				if ($usrModel->usr_s3_data == null || $usrModel->usr_s3_data == '')
				{
					$usrModel->scenario		 = 'user_sync';
					$usrModel->usr_s3_data	 = "{}";
					return null;
				}
			}
			$spaceFile				 = $usrModel->uploadToSpace($localFilePath, $usrModel->getDigitalSpacePath(), $removeLocal);
			$usrModel->usr_s3_data	 = is_array(json_decode($spaceFile->toJSON(), true)) ? $spaceFile->toJSON() : "{}";
			if (!$usrModel->save())
			{
				throw new Exception(json_encode($usrModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	public function getLocalQrCodePath()
	{
		$filePath = $this->usr_qr_code_path;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/doc');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getQrCodeLocalPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->usr_qr_code_path;
		}

		return $filePath;
	}

	public function getQrCodeLocalPath()
	{
		return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR;
	}

	public function getDigitalSpacePath()
	{
		return $this->getQrCodeSpacePath($this->usr_qr_code_path);
	}

	public function getQrCodeSpacePath($localPath)
	{
		$fileName		 = basename($localPath);
		$userId			 = $this->user_id;
		$fileType		 = 'QR';
		$fileName		 = $fileType . '_' . $userId . '_' . $fileName;
		$folderExtender	 = Filter::s3FolderPath($userId);
		$path			 = "/users/{$folderExtender}/{$fileName}";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getQrSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	/**
	 * 
	 * @param type $usrId
	 * @return type
	 */
	public static function getUserPathById($usrId)
	{
		$path = '/images/no-image.png';

		$usrModel = Users::model()->findByPk($usrId);
		if (!$usrModel)
		{
			goto end;
		}
		$fieldName = "usr_s3_data";

		$s3Data	 = $usrModel->$fieldName;
		$imgPath = $usrModel->getPath();

		if (file_exists($imgPath) && $imgPath != $usrModel->getQrCodeLocalPath())
		{
			if (substr_count($imgPath, PUBLIC_PATH) > 0)
			{
				$path = substr($imgPath, strlen(PUBLIC_PATH));
			}
			else
			{
				$path = AttachmentProcessing::publish($imgPath);
			}
		}
		else if ($s3Data != '{}' && $s3Data != '')
		{
			$spaceFile	 = \Stub\common\SpaceFile::populate($s3Data);
			$path		 = $spaceFile->getURL();
			if ($spaceFile->isURLCreated())
			{
				$usrModel->$fieldName = $spaceFile->toJSON();
				$usrModel->save();
			}
		}
		end:
		return $path;
	}

	public function getPath()
	{
		$filePath = $this->usr_qr_code_path;

		if (substr($filePath, 0, strlen('doc')) == 'doc')
		{
			$filePath = substr($filePath, strlen('doc'));
		}
		$filePath = $this->getQrCodeLocalPath() . $filePath;

		//	Logger::writeToConsole($filePath);
		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->usr_qr_code_path;
		}

		return $filePath;
	}

	public static function updateQRCodeById($limit = 1)
	{
		#$strEmail = "'ditisaxena1@gmail.com','kishoresridhar.s@gmail.com','nonie18fox@yahoo.co.uk','shcmb1963@gmail.com','abhaideep.srivastava@gmail.com','mandot6025@yahoo.com','nitin.bhatnagar4u@gmail.com','puneetagarwal2001@gmail.com','gaurav.chy1@gmail.com','tashu.sweetheart@gmail.com','srivastavaanand1234@gmail.com','ajaygupta241@gmail.com','lkbararia@rediffmail.com','uniyalamit08@gmail.com','gorimeet93@gmail.com','aditichatterjee5@gmail.com','jaishankarsharma1990@gmail.com','saloney111@gmail.com','caabhinavoberoi@gmail.com','benjwalgourav@gmail.com','premprakashsahu60@gmail.com','abhaysinghsaini@gmail.com','natasha.ifest@gmail.com','vvrnb72@gmail.com','samuel.yohann@gmail.com','prataekgautam@gmail.com','indugalster@gmail.com','kanikasharma0707@gmail.com','bhits1985@gmail.com','krishnaaraviti123@gmail.com','pmsolanki@gmail.com','thvidit@gmail.com','np24101971@gmail.com','vermajyoti983@gmail.com','pawarsanjaysingh@gmail.com','harryiitroorkee@gmail.com','pankitsingla@gmail.com','mahmoodakthar1968@gmail.com','prabhanshu619@gmail.com','eschakr@gmail.com','aamitav017@gmail.com','saikatcool95@yahoo.com','snehalkumar112@gmail.com','abhra_sls7@rediffmail.com','deepika.1612@gmail.com','swastikdutta@gmail.com','hi_dheer@yahoo.com','deepak.rikhi90@gmail.com','anuraglila@gmail.com','pradeepkamadana@gmail.com','erramkeshwar.420@gmail.com','nikhilendu.mandal@gmail.com','seemacreation27@gmail.com','tiyabatra1992@gmail.com','abhilash.vit@gmail.com','indrareddynandyala999@gmail.com','geekkd@gmail.com','anoop_tariyal88@hotmail.com','bablani933@gmail.com','mr.manishsharma01@gmail.com','amsmpm2000@gmail.com','virityagi1997@gmail.com','kjsinghwarden@rediffmail.com','admin@recity.in','singh_kumudranjan@yahoo.com','jeevikasharma10@gmail.com','Sehrawat.shivani29@gmail.com','26kush@gmail.com','laksh20121996@gmail.com','jyotimehra030378@gmail.com','sandy.raja2015@gmail.com','anubhavagrawal64@gmail.com','acmilanrulzanant07@gmail.com','jkgaur0153@gmail.com','suryanshthakur5@gmail.com','bala_mookoni@hotmail.com','sapnakatira09@gmail.com','sheerrysingh048@gmail.com','dsdraw@gmail.com','nitin123khan@gmail.com','yatish419@gmail.com','spuniaiitd@gmail.com','naveenmathur0112@gmail.com','rvbirajdar@gmail.com','joshiiib25@gmail.com','bankoti1965@gmail.com','jyoti.kashyap.rajput@gmail.com','mukeshllingwal@gmail.com','mukeshnegi007@gmail.com','tanyabisht924@gmail.com','drruchimittalms@gmail.com','araturi292@gmail.com','harve272@gmail.com','zoheb.cfc@gmail.com','debopriyo_dhar@yahoo.com','dharitrig239@gmail.com','arjun.kawle@gmail.com','anshulchandel89@gmail.com','ajaydob@gmail.com','mohankumarj05@gmail.com','chanhennessy@gmail.com','dheerajgandhi73@gmail.com','aakash.srivastav@gmail.com','khanna.aayush@gmail.com','rajanverma.ecom@gmail.com','roshnihande@gmail.com','chatwani.lucky92@gmail.com','dchandel.713@gmail.com','nknawin@rediffmail.com','ketan1709@yahoo.com','ankitsingh3410@gmail.com','jigarisca@gmail.com','gauravkakkar.trainer@gmail.com','shalki03singh@gmail.com','ashu.sae@gmail.com','mayanktandon786@gmail.com','kritilachhiramka@gmail.com','sathyaelec@gmail.com','hussainseth@hotmail.com','feroceinternational@gmail.com','itzdarpan@gmail.com','dhaval.393@gmail.com','aviaugust@gmail.com','srajank52@gmail.com','manojlbsti@gmail.com','yudhajit.sarkar@gmail.com','akmehta2010@hotmail.com','rexmaihar@gmail.com','ankita.cal@gmail.com','rvasumathi@gmail.com','rajeevmafan2003@gmail.com','vijay.guptabhai@gmail.com','puneet.mahesh@gmail.com','khankriyal.rahul@gmail.com','aakifshamsi@gmail.com','rumpa.patranabish@gmail.com','ashish_gujar@rediffmail.com','arnab92mukherjee@gmail.com','caankitbalana@gmail.com','subhashgupta.pnb@gmail.com','manjunath.316@gmail.com','vkumarcsc95@gmail.com','kesavanmm@hotmail.com','akshay91293@gmail.com','sharma.rht9@gmail.com','guptanitin720@gmail.com','agrawal.karan.er@gmail.com','anshumanghosh003@gmail.com','manikgarg21@gmail.com','gupta.pavitra@gmail.com','gdas@thecpt.co.in','amandeeppanwar@gmail.com','nikhilgadale2@gmail.com','sunny.shashwat@gmail.com','shauvik.techno@gmail.com','yogesh.ssb@gmail.com','ashishk1409@gmail.com','amt4you@rediff.com','aditi.addycool@gmail.com','vinaytiwari06@gmail.com','nilushinde19@gmail.com','atul.tiet@gmail.com','rkamna0@gmail.com','viv.sri29@gmail.com','charan3108@gmail.com','namrata12feb@gmail.com','kotechavandana1@gmail.com','avegagarwal@hotmail.com','singhalmohit9818@gmail.com','piyushagrawal092@gmail.com','janarthanan.dev@gmail.com','vishal.sonu@gmail.com','manvimehrotra@gmail.com','vinaypv1961@gmail.com','dipmitra.dm@gmail.com','d.prashant3003@gmail.com','sandeep_nanotsk@yahoo.co.in','anjalimasseysps@gmail.com','prateekagr98@gmail.com','hr.conqcorp22@gmail.com','bhanupratap1507@gmail.com','mfosua75@gmail.com','piyu.mumbai@gmail.com','subha2014@outlook.in','er.sumanpathak@gmail.com','shivam25596@gmail.com','achyutanandjha@hotmail.com','ashutoshmudgil@gmail.com','luck_ankur@yahoo.co.in','manoj3518@gmail.com','maujsarup@gmail.com','smgarg312@gmail.com','bhuvanesh.p2403@gmail.com','jaswant011@gmail.com','kapoor.shubham48@gmail.com','srg.firefox@gmail.com','kartikcwc@gmail.com','sumitshakya856@gmail.com','amolkanda@yahoo.co.in','sandeepmehta67@gmail.com','prab.natarajan@gmail.com','drsiddu123@gmail.com','archanchhaya@gmail.com','dbparmar16@gmail.com','sumitla@protonmail.com','methai.karan@gmail.com','khanrumakhan91@gmail.com','chatrathheena@gmail.com','abi.de.mj@gmail.com','arunthakur4u@gmail.com','rajivtaandon@gmail.com','smitabisht1991@gmail.com','pisupatibalakrishna@gmail.com','ramrudra.p@gmail.com','prashant170392@gmail.com','shipra92@gmail.com','acaddirector.kolkata@aesl.in','kjoffice19@gmail.com','rishikantfeb@gmail.com','svhatkar31@gmail.com','hypeandvibepromos@gmail.com','abmonachetri@icloud.com','benjwalgourav@gmail.com','shivani.sh@outlook.in','guru.bandits@gmail.com','soniabhasin83@gmail.com','anuradha.hsh.joshi6@gmail.com','ramesh@yahoo.com','varun@varunagw.com','sakshi9.gupta9@gmail.com','harishkhanduri119@gmail.com','joharimegha28@gmail.com','sreejith.kumar7@gmail.com','sagdya1@gmail.com','lalasourav@gmail.com','sunitabhatt47@yahoo.com','kalluripraneeth@gmail.com','abhish9911@gmail.com','saurabhomernit@gmail.com','anjanisarin@gmail.com','eeshanee14@gmail.com','sachinsrivastava762@gmail.com','shagun.chauhan6@gmail.com','rick.sarma@gmail.com','shivangivarshney92@gmail.com','singlasunali@gmail.com','raghavmaheshwari.101548@gmail.com','omkar.agustya@gmail.com','aashita.singhania@gmail.com','jpsingh746a@gmail.com','deepanshukaushal21@gmail.com','kamalsati85@gmail.com','ng1845@gmail.com','rahul10u@gmail.com','kuldeep.rawat1980@gmail.com','rastogi.saurabh@gmail.com','dalipsherwinlal@gmail.com','urvashidhar.sheen@gmail.com','jai.skit@gmail.com','parichex@gmail.com','basu.sayanee@gmail.com','shashisingh.ihm@gmail.com','joyespramanik@gmail.com','arbhabhra@yahoo.com','dollip009@gmail.com','msgaur123@yahoo.in','navratna.mishra@gmail.com','dipakjivnani@gmail.com','vivekaccess5@gmail.com','bhadrishvipin@gmail.com','singhharsh09111998@gmail.com','rawatmahesh2410@gmail.com','hpal1301@gmail.com','avinashnagpal.1691@gmail.com','mmgupta50@yahoo.com','shivanipargai2527@gmail.com','lraju1719@gmail.com','writetovijeta@gmail.com','nikeshrulez@gmail.com','rahul@panindia.in','vivekbhatt7@gmail.com','bansals90@gmail.com','mayankphogaat7@gmail.com','483.vijay@gmail.com','chawla.chirag@ymail.com','sambhagwat97@gmail.com','aartisingh45@gmail.com','manavaggarwal2803@gmail.com','monakitty369@gmail.com','marinagreat@gmail.com','rohit.gsb@gmail.com','kabadwal@gmail.com','ashishmishra21oct@gmail.com','ayushsingh231088@gmail.com','mohanravuru@gmail.com','sumanyu.satpathyy@gmail.com','mohd.asharaf025@gmail.com','Akanksha6462@gmail.com','abhishek9454@gmail.com','prasad.mogarala@gmail.com','yogenderghangas3@gmail.com','liron6700@gmail.com','rohit.adhlakha@gmail.com','shrutimishra31@gmail.com','mps804186@gmail.com','sunilnaugain@gmail.com','bib5975@gmail.com','trackfitjym17aerobic@gmail.com','rashmibala@icloud.com','deepakvarshney1989@gmail.com','rpsanawar@yahoo.co.in','laxsadnani17@gmail.com','jagmohan1627@gmail.com','shahdanc@gmail.com','rakesh.aimk@gmail.com','mandlik.shilpa85@gmail.com','kalpeshgoradia@yahoo.co.in','saketkumar74@gmail.com','rizansari08@rediffmail.com','exceed007@gmail.com','puneetgupta310@gmail.com','mithileshnaik@gmail.com','avinash.nit02@gmail.com','nporwal1980@gmail.com','nitinbansal1989@yahoo.co.in','bsbhawnasahu998@gmail.com','dumy31@gmail.com','paras160690@gmail.com','simran31162000@gmail.com','palmirapaul@gmail.com','akash.singhal17@gmail.com','satiindresh@gmail.com','shivompandey636@gmail.com','mukeshrawat4u@gmail.com','debrajchandra@hotmail.com','sss.ycd@gmail.com','anandrathi_25@yahoo.com','rajievnaarayan@gmail.com','52sathyam@gmail.com','aditosh19@gmail.com','jetu1234@gmail.com','coolprakhar29@gmail.com','hirentejnani@gmail.com','sanjeev7000@gmail.com','rakeshsharma.d20@gmail.com','namitadimri7@gmail.com','manjula.mj@gmail.com','kaushikchakrabarti2004@gmail.com','akannksha.rawat@gmail.com','kasatsandeep9@gmail.com','sumit.tiwari1010@gmail.com','sandalzhr5@gmail.com','shivamsharma1923@gmail.com','dennis22245@gmail.com','sarfrazdhillon9@gmail.com','nitinsworld80@gmail.com','sagarchainani02@gmail.com','chirag.jog@gmail.com','deepak1332@gmail.com','aryasheelgtm@gmail.com','tarunj47@gmail.com','premthongam666@gmail.com','subhangeeagarwal95@gmail.com','priyatodi@gmail.com','shailesh.gusain@gmail.com','expressions.bhavna@gmail.com','cspl.akshat@yahoo.com','tanvischk@gmail.com','shivam.agg1994@gmail.com','pkarthi28@gmail.com','amit.karia@yahoo.co.in','vashim.mazhar@gmail.com','aanchaljohri94@gmail.com','syedanumzaheer@gmail.com','darshanmahajan@hotmail.com','kiranghk@gmail.com','priyambaner@gmail.com','mathuranoop2007@gmail.com','hrud.nm@gmail.com','vish.kolla@gmail.com','sanjeevgautam2232@gmail.com','sweetyscape@gmail.com','ankit.gupta.cl@gmail.com','trivedi1953@hotmail.com','kirans.kiran7777@gmail.com','maheshpjoshi2003@yahoo.com','official.shashwat@gmail.com','cgshah_5980@yahoo.co.in','deepak1991singla@gmail.com','sandeep2273@gmail.com','shehzadzaidi110@gmail.com','ccnitin@gmail.com','parth08_jugran@yahoo.com','soumikpal169@gmail.com','animeshbhatt31@gmail.com','ashudhewal@gmail.com','shashankmathur@gmail.com','pkgupta59@gmail.com','sanchit.kumar42@gmail.com','mahadev36@gmail.com','shivamss38@gmail.com','joshiisrocking@gmail.com','siddharth0319@gmail.com','gupta.baleshwar@gmail.com','rocintrish@gmail.com','risingshubham@gmail.com','ashu.mundeti@gmail.com','fictionalcorrespondent@gmail.com','physicsarushi@gmail.com','cherantsm@gmail.com','sri16082000@gmail.com','manojnkamath77@gamil.com','suresh.km@asianpaints.com','devyad@rediffmail.com','debajyoti77in@gmail.com','srivastava2anchal@gmail.com','g111.anshul@gmail.com','anupam0459@gmail.com','vardanagarwal3@gmail.com','anoushkasarin22@gmail.com','jayaraj.verma@gmail.com','yash1212jain@gmail.com','manashok9@gmail.com','gslatwal12@gmail.com','shimtavariaji@gmail.com','neharst2412@gmail.com','arghya1980@gmail.com','myrealfriendamit@gmail.com','tcjoshi@rediffmail.com','snehavohra1@gmail.com','paras.jain1989@gmail.com','agarwal.sajal89@gmail.com','mybuy.santanuc@gmail.com','susovanmistry@gmail.com','rajnish.dive@gmail.com','aggarwaldeepak24@gmail.com','navin91174@gmail.com','nivedithajoyappa10@gmail.com','prasannadeep27@gmail.com','raajesh208@gmail.com','rudrika23@gmail.com','sarthakjainmln@gmail.com','stjt.ds@gmail.com','sanchi.geit@gmail.com','vivekkhugshal86@gmail.com','h.imranhussain@gmail.com','ashutoshanshu108@gmail.com','mohitaryawarta@gmail.com','ronitdk@gmail.com','rbhagat123@gmail.com','rld31155@gmail.com','pksengar12@gmail.com','amol477@gmail.com','shashank.saxenaa@gmail.com','chaitra.narasimhaswamy@gmail.com','richa27101985@gmail.com','arpanc1985@gmail.com','surbhimahe@gmail.com','krishna.kulkarni2010@gmail.com','dheerajpantps9@gmail.com','hirennanda007@gmail.com','partner@offcliff.in','mail.badal85@gmail.com','anamshahid856@gmail.com','biswajyotimukherjee08@gmail.com','rishabagarwal185@gmail.com','kuldeepwantu@gmail.com','shreyajain1224@gmail.com','priyarajput8960@gmail.com','sachinndhimann@gmail.com','arin101294@gmail.com','guncha670@gmail.com','sheeshpal9820@gmail.com','bhagwaniem@gmail.com','vikramsing90@gmail.com','ghosh.riddhipratim37@gmail.com','k.aravindshankar@gmail.com','caprithasarkar@gmail.com','travelroof@gmail.com','ray_nikhil@yahoo.co.in','rangt19@gmail.com','bansode.avinash@gmail.com','onlyjaydoshi@gmail.com','archna.iiml@gmail.com','rpshah333@gmail.com','nknitinr300@gmail.com','tejasavi@gmail.com','masaini13@gmail.com','teddybaddy3@gmail.com','kunalw123@gmail.com','pallav05.star@gmail.com','debarundutta90@gmail.com','saharshs012@gmail.com','aroraest@gmail.com','rakesh.nitk12@gmail.com','nitin.k@surya.in','karandesai353@gmail.com','sauravsarkar92@gmail.com','aqib.jamadar1997@gmail.com','maitrayeesharma39@gmail.com','viksitgoel@gmail.com','abivarmas@gmail.com','remogan@gmail.com','negi_hs@yahoo.com','devyani.dagaonkar@gmail.com','dvenkat01@gmail.com','rajasriniv@gmail.com','divyamnevermesses@gmail.com','mepuneet.iitk@gmail.com'";
		#$strPhone = "'9168917432','9840905475','9819304484','9818651502','9650102104','9552516235','9769303069','8006566566','9038130665','8826045963','8779704172','9312105031','9830049882','9643003682','9819363624','9999068190','9990396808','7018909602','7837200999','8265994468','9412148697','8527841228','7715918889','9041076124','9500455526','9678554720','9884105443','9740036664','7042919268','9397645916','8238055640','8130587533','9409279480','8005144783','8077436728','8895504431','7070897770','9874247569','8652765628','7977579509','8240294587','8609042885','9766361199','9836584674','9632104430','9804900049','8099447884','9886220971','9323065346','9949826869','7011990665','9474718125','9911598266','9821253920','7728025963','9550247757','9911813214','9599022573','8850928409','9891979500','9952067622','8810344106','9890950863','9172296254','9313049988','9711919768','8527336245','7247688717','8982343096','9891385047','9836380066','9163483715','9830280758','9004551720','8005655044','9904304232','9833069157','7267870055','9821483877','9899600962','9015753149','9643264474','9319931172','9552542359','8859586071','7005253200','9540804780','9717637477','8745801441','9284339204','7982839395','9639696364','9845183797','9836722732','7044081617','9062431178','9819322686','9599334695','9711119765','9725038590','9916133297','8077908772','9739644344','9167271508','9717640646','9623471143','9782222346','8886803418','7428604657','9322270751','9504577886','9898360526','9897752473','8126792482','9934069907','7023002307','9681544431','7045593414','9799095253','9870934304','9999173212','8779766744','7991154756','8007199598','9818606913','9804063871','9819371228','8295816737','9748246798','9980338848','7017257790','9246877411','9819852288','8168190794','9899895416','6290255908','9890240734','9635079339','9971950006','9810378764','7483147413','7834901732','9447790122','7011835362','9910103553','9988244857','6377327548','9915462021','9971987759','9731202549','8910495065','9650588865','9100960368','9833514618','7003221255','8700415243','9897663472','9711186300','9716838054','7574927912','9967766189','9958499577','9871882003','9998982732','9886783339','8777473029','9423912442','9910021375','8368243013','8447349657','9986132914','8800558835','9650698374','8097010003','9830022608','7660852018','9080717749','9928900029','8861986656','7872154441','9105999989','7248533930','8979740434','7022614562','9873138795','9569854232','9971977762','8696028689','9953206793','9949935580','8872372024','9284000549','9367701707','6354412175','9890226975','9860989689','8384894586','7022075909','9888022892','9701914326','9871370331','8872404709','7016102710','8850757789','7874101064','9960695889','9548513312','7055859484','9352320122','9996147755','9897376833','9027953208','8608508060','9652589379','9836392244','9145032811','7701809248','7020503964','9315378293','8968776559','9690022073','9940369435','9538600746','9910888335','9640261395','9456033386','9971637808','9897891248','9650175820','9480694437','9494877418','410518669','9819748411','7989714317','7017383389','8861263400','8307965074','9560019757','8587097118','7009965256','7428293126','9816956885','8283800034','9051755333','7338255437','7006435865','7589118883','7017195866','7303051814','7906760425','9953008809','9811359735','8447195952','7899077275','9829879541','8093792821','9811556671','9928624777','9871677447','9455039036','9879100310','8017939230','9617364912','7014288731','9871025025','9758104289','9821159489','7060797871','9963973492','9823686134','8826022990','9899995994','9999338066','8750175445','9920089030','8800758008','8555329902','9481770719','9560511633','9667209401','8894567887','9871078307','9759204581','7297866598','9811209454','9940318315','9017774627','7428399664','9650414334','9582298049','9987855921','9958934582','9818290486','8667870205','9717043064','9325663222','9509266509','7042091191','9821472091','9664186645','7011149981','9818498137','9829176047','9820216268','9886623850','7219007123','9903849969','9873181632','9706633132','9974547558','9650293196','9711038489','9817034147','9820347668','9833419991','9829407623','9910491573','9958111091','7350004451','7003928852','9815252907','9324212626','8511113155','9599347967','7354437000','7359683936','8240812066','9833564699','9100154550','9319511722','8860187744','7417726987','8805147850','8017187741','9867691456','9582459812','8388801158','9176038387','9902222245','9560883477','8146537120','8777058785','9003240849','9263300572','9015202476','8840999695','9391076827','8639495039','9820320703','8745066318','7831843681','7204537001','9891945323','9239176426','9962053391','9953156830','7988279620','8233582046','7798618622','7310957694','9891551349','8961112143','9518553719','8885550369','9892194931','9523004236','9899102679','9568471587','8237717197','9004635035','9082962551','9311975933','7990989764','9079712760','9953422129','8727019005','9814840637','9313554397','8506911253','7278682811','8800569908','9810254998','9810979317','9418016215','8544177386','9158098800','8979858133','9044537869','8445865000','9999500797','8885000933','8860133824','9643626533','8310779040','7018533582','8939948344','9949206812','9769986878','9004968800','8800291964','9836392244','9457918830','9701666533','9866304591','9911801028','9953081266','9789983537','9340502483','9319957615','8585928278','6353456806','9811990516','9836874303','9897615594','9899046226','9958095276','9568169283','7718064083','9051633345','9903774871','8469918442','7307967111','8802010840','9663752624','8006464672','9455067878','9165064786','7406122282','9830386982','8830286174','9873144026','9766321110','9608102554','9541457055','8459710824','9440044383','9891727901','9917180111','8126978547','8505855237','9611209801','9871410255','9051313755','9999668053','9970226204','9643896256','8000739302','9422739269','8200661604','8527672084','9620562151','8779297841','9011000359','8448032545','9354274621','9815431580','8420921892','9748709953','9991300601','9892551370','8218737782','8967077210','8220049431','9769742102','9999333690','9428007754','9716101809','9920159516','9653678151','9999359808','8650486497','9986044614','8178468262','9228891208','9614762303','8879576432','7411639213','9614228540','7607543668','8000068178','9644594972','9643006540','8980273706','9911492353','7447352710','9462601860','9927109922','8248169218','8754154125','6239445611','9619872790','7795011883','9900145465','7986086872','9810832126'";

		$params	 = [];
		$limit1	 = min([100, $limit]);

		echo "\r\nSQL == " . $sql = "SELECT DISTINCT user_id, usr_qr_code_path, bkg.bkg_id FROM booking bkg  
					INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
                    INNER JOIN users usr ON usr.user_id = bui.bkg_user_id AND usr_active = 1 
					INNER JOIN contact_profile cpr ON cpr.cr_is_consumer = usr.user_id AND cpr.cr_status = 1
					WHERE (usr.usr_qr_code_path IS NULL OR usr.usr_qr_code_path = '') AND usr.usr_s3_data IS NULL 
                    AND (bkg.bkg_agent_id IS NULL OR bkg.bkg_agent_id = '') AND bkg.bkg_status IN (6,7) 
					AND usr.usr_email NOT LIKE '%gozo%' 
					AND usr.user_id NOT IN (SELECT user_id FROM test.qr_ignore) 
					ORDER BY bkg.bkg_id DESC LIMIT 0, $limit1";
		$res = DBUtil::query($sql, DBUtil::SDB(), $params);
		foreach ($res as $row)
		{
			#sleep(1);
			$returnSet = QrCode::processData($row["user_id"]);

			echo "\r\nuser_id == " . $row["user_id"];
			if (!$returnSet->getStatus())
			{
				echo "\r\nErrorUpdateQRCodeById == " . $returnSet->getMessage();

				echo "\r\nsqlIns == " . $sqlIns = "INSERT INTO test.qr_ignore (`user_id`) VALUES ({$row["user_id"]})";
				DBUtil::execute($sqlIns);
			}

			#Logger::trace("success: " .$returnSet->getStatus(). " message: " . $returnSet->getMessage() ? $returnSet->getMessage() : $this->getError($returnSet));
		}
		$limit -= $limit1;
	}

	public static function getUserByContactId($contactId)
	{
		$sql		 = "SELECT user_id as userId FROM users
						WHERE usr_contact_id=:id ";
		$arrProfile	 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['id' => $contactId]);
		return $arrProfile;
	}

	/**
	 * Validate username  email/phone
	 * @param type $attribute
	 * @param type $params
	 * @return boolean
	 */
	public function validateEmailPhone($attribute, $params)
	{
		$this->usr_create_platform	 = UserInfo::$platform;
		$username					 = $this->$attribute;
		$isEmail					 = Filter::validateEmail($username, true);
		$isPhone					 = false;
		if (!$isEmail)
		{
			$isPhone = Filter::processPhoneNumber($username);
			if (!$isPhone)
			{
				$this->addError($attribute, "Please enter valid email id/phone number");
				return false;
			}

			$phone					 = Filter::parsePhoneNumber($isPhone, $code, $number);
			$this->usr_mobile		 = $number;
			$this->usr_country_code	 = $code;
			$this->usernameType		 = Stub\common\ContactVerification::TYPE_PHONE;
		}
		else
		{
			$this->usr_email	 = trim($username);
			$this->usernameType	 = Stub\common\ContactVerification::TYPE_EMAIL;
		}
		if (!$isEmail && !$isPhone)
		{
			$this->addError($attribute, "Please enter your valid email id/phone number");
			return false;
		}
		return true;
	}

	/**
	 * This function is for to check a variable is phone number or email address
	 * @param type $var
	 * @return boolean|string
	 * // TYPE_PHONE	 = 2;TYPE_EMAIL	 = 1;
	 */
	public static function isEmailOrPhone($var)
	{

		$arr = [];

		$isEmail = Filter::validateEmail($var, true);
		$isPhone = false;
		if (!$isEmail)
		{
			$phone	 = Filter::processPhoneNumber($var);
			$isPhone = ($phone !== false);
		}
		if (!$isEmail && !$isPhone)
		{
			return false;
		}

		if ($isEmail)
		{
			$type	 = Stub\common\ContactVerification::TYPE_EMAIL;
			$arr	 = ['type' => $type, 'value' => $var];
		}

		if ($isPhone)
		{
			$type			 = Stub\common\ContactVerification::TYPE_PHONE;
			Filter::parsePhoneNumber($phone, $code, $number);
			$code			 = ($code[0] != '+' || $code[0] != 0) ? ('+' . $code) : ($code);
			$fullPhoneNumber = $code . $number;
			$arr			 = ['type' => $type, 'value' => $fullPhoneNumber, 'phCode' => $code, 'phNumber' => $number];
		}
		return $arr;
	}

	public function getFullMobileNumber()
	{
		if ($this->usr_mobile == '')
		{
			return false;
		}
		$phone			 = $this->usr_country_code . $this->usr_mobile;
		$objPhone		 = Filter::parsePhoneNumber("+" . $phone, $code, $number);
		$fullPhoneNumber = '+' . $code . $number;
		return $fullPhoneNumber;
	}

	public static function loginByAppmodel($appModel)
	{
		/** @var \AppTokens $appModel */
		$userId				 = $appModel->apt_user_id;
		$userModel			 = Users::model()->findByPk($userId);
		$identity			 = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		$identity->userId	 = $userModel->user_id;
		if (!$identity->authenticate())
		{
			throw new Exception("Unable to authenticate", ReturnSet::ERROR_UNAUTHORISED);
		}
		Yii::app()->user->login($identity);

		return $userModel->user_id;
	}

	/**
	 * 
	 * @return integer type
	 */
	public function validateSignupUsername()
	{
		$userModel	 = $this;
		$type		 = 0;
		if ($userModel->usr_email)
		{
			$isEmail = Filter::validateEmail($userModel->usr_email, true);
			if (!$isEmail)
			{
				$userModel->addError("usr_email", "Please enter valid email address");
				goto end;
			}
		}
		$phNumber	 = $userModel->usr_country_code . $userModel->usr_mobile;
		$isPhone	 = Filter::processPhoneNumber($phNumber);
		if (!$isPhone)
		{
			$userModel->addError("usr_mobile", "Please enter valid phone number");
			goto end;
		}

		if ($isEmail)
		{
			$contactId = Contact::getByEmailPhone($userModel->usr_email);
			if ($contactId != '')
			{
				$userModel->addError("usr_email", "This Email Address " . $userModel->usr_email . " is already registered with us. Click yes to login with this existing account or press cancel to change Email Address and create a new account.");
				$type = Stub\common\ContactVerification::TYPE_EMAIL;
				goto end;
			}
		}
		if ($isPhone)
		{
			$contactId = Contact::getByEmailPhone('', $phNumber);
			if ($contactId != '')
			{
				$userModel->addError("usr_mobile", "This phone number " . $userModel->usr_mobile . " is already registered with us. Click yes to login with this existing account or press cancel to change Email Address and create a new account.");
				$type = Stub\common\ContactVerification::TYPE_PHONE;
			}
		}
		end:
		return $type;
	}

	/**
	 * @param integer $userId
	 * @param string $reason
	 * @param integer $type
	 * @return ReturnSet
	 * @throws Exception
	 */
	public static function deactivate($userId, $reason, $type = UserInfo::TYPE_CONSUMER)
	{
		$returnSet = new ReturnSet();
		try
		{
			/* @var $model Users */
			$model = Users::model()->findByPk($userId);
			if (!$model)
			{
				$model = new Users();
				$model->addError("bkg_id", "User does not exist or already deactivated");
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$model->usr_deactivate_reason	 = $reason;
			$model->usr_active				 = 2;
			$model->scenario				 = 'deactivate';
			if (!$model->validate())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}


			$contactId		 = ContactProfile::getByUserId($userId);
			$contactProfile	 = ContactProfile::model()->findByContactId($contactId);

			if ($type == UserInfo::TYPE_CONSUMER)
			{
				if (ContactProfile::isDriver($contactId) || ContactProfile::isVendor($contactId))
				{
					throw new Exception("Profile is linked to existing vendor/driver account. Please contact gozo support", ReturnSet::ERROR_VALIDATION);
				}
			}
			$transaction = DBUtil::beginTransaction();

			// Deactivate contactEmails or contactPhones for consumer or partner
			$contactReturnSet = Contact::deactivateV1($contactId, $reason);

			if (!$contactReturnSet->isSuccess())
			{
				throw new Exception(json_encode($contactReturnSet->getMessage()), $contactReturnSet->getErrorCode());
			}

			SocialAuth::unlink($userId);

			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if ($contactProfile->cr_is_driver > 0 && $type != UserInfo::TYPE_CONSUMER)
			{
				AppTokens::deactivateByEntityIdandEntityType($contactProfile->cr_is_driver, UserInfo::TYPE_DRIVER);
			}
			if ($contactProfile->cr_is_vendor > 0 && $type != UserInfo::TYPE_CONSUMER)
			{
				AppTokens::deactivateByEntityIdandEntityType($contactProfile->cr_is_vendor, UserInfo::TYPE_VENDOR);
			}
			if ($userId > 0)
			{
				AppTokens::deactivateByUserIdandUserType($userId, UserInfo::TYPE_CONSUMER);
			}

			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setData(['userId' => $userId]);
			$returnSet->setMessage("User deleted successfully");
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($exc);
			$returnSet->setMessage($exc->getMessage());
		}
		return $returnSet;
	}

	public static function getDCOStatusDetails($vndId, $drvId)
	{
		$status				 = array();
		$status['vendorId']	 = (int) $vndId;
		$status['driverId']	 = (int) $drvId;
		$vndModel			 = Vendors::model()->findByPk($vndId);
		if ($vndId > 0 && $vndModel != null)
		{
			$status['is_agreement']	 = VendorStats::model()->statusCheckAgreement($vndId);
			$status['is_document']	 = VendorStats::model()->statusCheckDocument($vndId);
			$status['is_car']		 = VendorStats::vehicleStatus($vndId);
			$status['is_driver']	 = VendorStats::driverStatus($vndId);
			$arr					 = Vendors::checkAlertMsg($vndId);
			$status['securityFlag']	 = $arr['flag'];
			$status['message']		 = $arr['message'];
		}
		if ($drvId > 0)
		{
			$result = Drivers::checkDrvStatus($drvId);

			$status['is_license']	 = ($result['licenseDoc'] > 0 ? 1 : 0);
			$status['is_freeze']	 = (int) $result['drv_is_freeze'];
		}

		return $status;
	}

	/**
	 * This function is used to get contactId by userId
	 * @param integer $userId
	 * @return int
	 */
	public static function getContactByUserId($userId)
	{
		$contactId = 0;
		try
		{
			$userModel = Users::model()->findByPk($userId);
			if (!$userModel)
			{
				throw new Exception("No Contact Found, Error For User Id: {$userId}");
			}
			$userCount = ContactProfile::getCountByEntityId($userId, UserInfo::TYPE_CONSUMER);
			if ($userCount == 1)
			{
				$contactId = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
			}
			if (!$contactId)
			{
				$contactId = Contact::getByEmailPhone($userModel->usr_email, $userModel->usr_mobile);
			}
			if (!$contactId)
			{
				$contactId = ContactProfile::getByUserId($userId);
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
		return $contactId;
	}

	/**
	 * This function is used to send notifications  for customer referrals
	 * @return None
	 */
	public static function CustomerReferrals($userId, $contactId, $url, $discount, $isSchedule = 0, $schedulePlatform = null)
	{
		$phoneNo			 = ContactPhone::getContactNumber($contactId, $whatsappVerified	 = 1);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$contentParams		 = array('eventId' => "12", 'url' => $url, 'discount' => $discount);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, null, null, null, $code, $number, null, 0, null, null);
		$eventScheduleParams = EventSchedule::setData($userId, ScheduleEvent::CUSTOMER_REF_TYPE, ScheduleEvent::CUSTOMER_REFERRALS, "Customer Referrals", $isSchedule, CJSON::encode(array('userId' => $userId)), 10, $schedulePlatform);
		MessageEventMaster::processPlatformSequences(12, $contentParams, $receiverParams, $eventScheduleParams);
		skipAll:
	}

	public static function notifyConsumerForGozonow($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel || $bkgModel->bkg_reconfirm_flag == 1)
		{
			goto skipAll;
		}
		$userId		 = $bkgModel->bkgUserInfo->bkg_user_id;
		$userName	 = $bkgModel->bkgUserInfo->bkg_user_fname . " " . $bkgModel->bkgUserInfo->bkg_user_lname;
		if ($userName == null || trim($userName) == "")
		{
			$contactId	 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
			$cttModel	 = Contact::model()->findByPk($contactId);
			if (!$cttModel)
			{
				goto skipAll;
			}
			$userName = $cttModel->getName();
			if ($userName == null || trim($userName) == "")
			{
				goto skipAll;
			}
		}
		$hash		 = Yii::app()->shortHash->hash($bkgId);
		$url		 = Yii::app()->params['fullBaseURL'] . '/gznow/' . $bkgId . '/' . $hash;
		$buttonUrl	 = 'gznow/' . $bkgId . '/' . $hash;
		$result		 = BookingVendorRequest::getGNowLastOffer($bkgId);
		if (!$result)
		{
			goto skipAll;
		}
		$cabType	 = trim($result['vht_make'] . ' ' . $result['vht_model'] . ' (' . $result['cab_type'] . ')');
		$cabArriveAt = DateTimeFormat::DateTimeToLocale($result['reachingAtTime']);
		$amount		 = Filter::moneyFormatter($result['totalCalculated']);
		$bookingId	 = $bkgModel->bkg_booking_id;
		$pickupTime	 = DateTimeFormat::DateTimeToLocale($bkgModel->bkg_pickup_date);
		$pickupLoc	 = $bkgModel->bkg_pickup_address;
		if ($bkgModel->bkg_pickup_address == null || trim($bkgModel->bkg_pickup_address) == "")
		{
			goto skipAll;
		}
		$dropLoc		 = $bkgModel->bkg_drop_address != null && trim($bkgModel->bkg_drop_address) != "" ? $bkgModel->bkg_drop_address : $bkgModel->bkg_pickup_address;
		$distance		 = $bkgModel->bkg_trip_distance . ' KM';
		$sLink			 = Filter::shortUrl('https://' . Yii::app()->params['host'] . Yii::app()->createUrl('gznow/' . $bkgId . '/' . $hash));
		$contentParams	 = array(
			'userName'		 => $userName,
			'cabType'		 => $cabType,
			'cabArriveAt'	 => $cabArriveAt,
			'amount'		 => $amount,
			'bookingId'		 => Filter::formatBookingId($bookingId),
			'pickupTime'	 => $pickupTime,
			'pickupLoc'		 => $pickupLoc,
			'dropLoc'		 => $dropLoc,
			'distance'		 => $distance,
			'buttonUrl'		 => $url,
			'sLink'			 => $sLink,
			'eventId'		 => "18"
		);
		$phone			 = $bkgModel->bkgUserInfo->bkg_contact_no;
		if ($phone == null || trim($phone) == "")
		{
			$contactId	 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
			$row		 = ContactPhone::getNumber($contactId);
			if (!$row || empty($row))
			{
				goto skipAll;
			}
		}
		else
		{
			Filter::parsePhoneNumber($phone, $code, $number);
			$row['code']	 = $code;
			$row['number']	 = $number;
		}
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $bookingId, $row['code'], $row['number'], null, 1, null, SmsLog::Consumers, $buttonUrl);
		$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::CUSTOMER_GOZONOW, "notify Consumer For Gozonow", $isSchedule, CJSON::encode(array('userId' => $userId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(18, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				BookingLog::missedGozoNowOfferNotified($bkgId, $response['id'], true);
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
				BookingLog::missedGozoNowOfferNotified($bkgId, $response['id']);
			}
		}
		skipAll:
		return $success;
	}

	public static function notifyCustomerOngoingTrip($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($bkgId > 0)
		{
			$model = Booking::model()->findByPk($bkgId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		if (($model->bkg_agent_id != null || $model->bkg_agent_id != "") && !in_array($model->bkg_booking_type, [1, 2, 3]))
		{
			goto skipAll;
		}
		$userName	 = ($model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname);
		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$templateId	 = WhatsappLog::findByTemplateNameLang("customer_ongoing_trip", 'en_US', 'wht_id');
		if ($userName == null || trim($userName) == "")
		{
			$contactId	 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
			$cttModel	 = Contact::model()->findByPk($contactId);
			if (!$cttModel)
			{
				goto skipAll;
			}
			$userName = $cttModel->getName();
			if ($userName == null || trim($userName) == "")
			{
				goto skipAll;
			}
		}
		$contentParams	 = array(
			'eventId'		 => "13",
			'userName'		 => $userName,
			'url'			 => Filter::getBkpnURL($bkgId),
			'phoneNumber'	 => "9051877000",
			'code'			 => "DISC20",
			'offer'			 => "20%"
		);
		$phone			 = $model->bkgUserInfo->bkg_contact_no;
		if ($phone == null || trim($phone) == "")
		{
			$row = ContactPhone::getNumber($contactId);
			if (!$row || empty($row))
			{
				goto skipAll;
			}
		}
		else
		{
			Filter::parsePhoneNumber($phone, $code, $number);
			$row['code']	 = $code;
			$row['number']	 = $number;
		}
		$buttonArr			 = array("type" => 'button', "subType" => 'quick_reply', "text" => "payload", "data" => $templateId);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $model->bkg_booking_id, $row['code'], $row['number'], null, 1, null, null, $buttonArr);
		$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::CUSTOMER_ONGOING_TRIP, "notify Customer Ongoing Trip", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(13, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				$row	 = ["initiateBy" => WhatsappInitiateTrack::INITIATE_BY_GOZO, "initiateType" => WhatsappInitiateTrack::INITIATE_TYPE_USER, "templateId" => $templateId, "phoneNumber" => $row['code'] . $row['number']];
				WhatsappInitiateTrack::add($row);
				WhatsappInitiateTrack::updateStatus($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * This function is used to send notifications  for Vendor Dues Waived Off
	 * @return None
	 */
	public static function notifyReminderForIncompletedLeads($userId, $phoneNo, $bkgId, $fullName, $isSchedule = 0, $schedulePlatform = null)
	{
		$hash				 = Yii::app()->shortHash->hash($bkgId);
		$leadButtonUrl		 = "book-cab/quote/{$bkgId}/{$hash}";
		$leadUrl			 = Yii::app()->params['fullBaseURL'] . '/' . $leadButtonUrl;
		$contentParams		 = [
			'userName'	 => $fullName,
			'discount'	 => "20%",
			'type'		 => "immediately",
			'promo'		 => "DISC20",
			'url'		 => $leadUrl,
			'eventId'	 => "20"
		];
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_USER, $userId, null, $code, $number, null, 1, null, null, $leadButtonUrl);
		$eventScheduleParams = EventSchedule::setData($userId, ScheduleEvent::CUSTOMER_REF_TYPE, ScheduleEvent::REMINDER_FOR_INCOMPLETE_LEADS, "Reminder For incompleted leads", $isSchedule, CJSON::encode(array('userId' => $userId)), 10, $schedulePlatform);
		MessageEventMaster::processPlatformSequences(20, $contentParams, $receiverParams, $eventScheduleParams);
	}

	/**
	 * This function is used to send notifications  for user login/forgot password otp
	 * @return None
	 */
	public static function notifySendOtp($code, $number, $otp, $dltId, $smsTextType, $smsLogType, $isSchedule = 0, $schedulePlatform = null)
	{

		$success			 = false;
		$userInfo			 = UserInfo::getInstance();
		$userId				 = $userInfo->getUserId();
		$contentParams		 = [
			'otp'			 => $otp,
			'smsLogType'	 => $smsLogType,
			'dltId'			 => $dltId,
			'platform'		 => UserInfo::$platform,
			'smsTextType'	 => $smsTextType,
			'primaryId'		 => $dltId,
			'eventId'		 => "27"
		];
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_USER, $userId, null, $code, $number, null, 1, null, $smsLogType, $otp);
		$eventScheduleParams = EventSchedule::setData($userId, ScheduleEvent::CUSTOMER_REF_TYPE, ScheduleEvent::LOGIN_FORGOT_WEB_OTP, "Customer login/forgot web otp", $isSchedule, CJSON::encode(array('code' => $code, 'number' => $number, 'otp' => $otp, 'dltId' => $dltId, 'smsLogType' => $smsLogType, 'smsTextType' => $smsTextType)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(27, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
			}
		}
		return $success;
	}

	/**
	 * This function is used to send notifications  for user  who is applicable for double back offer
	 * @param integer $bkgId
	 * @return None
	 */
	public static function notifyDBO($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		try
		{
			if ($bkgId > 0)
			{
				$model = Booking::model()->findByPk($bkgId);
			}
			if (!$model)
			{
				goto skipAll;
			}
			if ($model->bkgTrail->btr_is_dbo_applicable == 0 || $model->bkgTrail->btr_dbo_amount <= 0)
			{
				goto skipAll;
			}

			$userName	 = ($model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname);
			$userId		 = $model->bkgUserInfo->bkg_user_id;
			if ($userName == null || trim($userName) == "")
			{
				$contactId	 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
				$cttModel	 = Contact::model()->findByPk($contactId);
				if (!$cttModel)
				{
					goto skipAll;
				}
				$userName = $cttModel->getName();
				if ($userName == null || trim($userName) == "")
				{
					goto skipAll;
				}
			}

			$phone = $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no;
			if ($phone == null || trim($phone) == "")
			{
				$row = ContactPhone::getNumber($contactId);
				if (!$row || empty($row))
				{
					goto skipAll;
				}
			}
			else
			{
				Filter::parsePhoneNumber($phone, $code, $number);
				$row['code']	 = $code;
				$row['number']	 = $number;
			}
			$contentParams		 = array(
				'eventId'	 => "28",
				'username'	 => $userName,
				'bookingId'	 => Filter::formatBookingId($model->bkg_booking_id),
				'amount'	 => "₹" . $model->bkgTrail->btr_dbo_amount
			);
			$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $model->bkg_booking_id, $row['code'], $row['number'], null, 0, null, null);
			$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::CUSTOMER_DOUBLE_BACK_OFFER, "notify Customer duouble back offer", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
			$responseArr		 = MessageEventMaster::processPlatformSequences(28, $contentParams, $receiverParams, $eventScheduleParams);
			foreach ($responseArr as $response)
			{
				if ($response['success'] && $response['type'] == 1)
				{
					$success = true;
				}
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
		skipAll:
		return $success;
	}

	public static function getCttIdsById($usrIds)
	{
		if (trim($usrIds) == '')
		{
			throw new \Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql2 = "SELECT GROUP_CONCAT(cr_contact_id) FROM contact_profile 
					WHERE cr_is_consumer IN ({$usrIds}) AND cr_status =1";

		$sql = " SELECT GROUP_CONCAT( DISTINCT CONCAT_WS(',',ctt.ctt_id,ctt.ctt_ref_code)) FROM contact_profile cp1
JOIN users usr ON usr.user_id =cp1.cr_is_consumer 
JOIN contact_profile cp ON cp.cr_is_consumer=usr.user_id AND cp.cr_status =1
JOIN contact ctt1 ON ctt1.ctt_id=cp.cr_contact_id AND ctt1.ctt_active =1
JOIN contact ctt ON ctt.ctt_ref_code=ctt1.ctt_ref_code AND ctt.ctt_active =1
WHERE cp1.cr_is_consumer IN ({$usrIds}) AND cp1.cr_status =1;";

		$relIds = \DBUtil::queryScalar($sql, DBUtil::SDB());

		$relIdArr = explode(',', $relIds);

		$distinctRelIds = implode(',', array_unique($relIdArr));

		return $distinctRelIds;
	}

	public static function getRelatedByCttIds($relCttIds)
	{
		$sql		 = "SELECT GROUP_CONCAT(DISTINCT cp.cr_is_consumer) cr_is_consumer 
				FROM contact_profile cp 
				WHERE cp.cr_contact_id IN ({$relCttIds}) 
				AND cp.cr_status = 1 
				AND cp.cr_is_consumer IS NOT NULL";
		$relDrvIds	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $relDrvIds;
	}

	public static function getRelatedIds($usrId)
	{
		if (empty($usrId))
		{
			return false;
		}
		  $cttIds = \Users::getCttIdsById($usrId);
		if (empty($cttIds))
		{
			return 0;
		}
		$relCttIds	 = \Contact::getRelatedIds($cttIds);
		$relUserList = \Users::getRelatedByCttIds($relCttIds);
		return $relUserList;
	}

	public static function getPrimaryId($usrId)
	{
		$relUserList = \Users::getRelatedIds($usrId);
		if ($relUserList)
		{
			$primaryUsr = \Users::getPrimaryByIds($relUserList);
		}
		return $primaryUsr['user_id'];
	}

	public static function getPrimaryByIds($userIds, $onlyPrimary = true)
	{
		$sql = "SELECT usr.user_id,ctt.ctt_id,ctt.ctt_ref_code, 
			IF(ctt.ctt_id =ctt.ctt_ref_code,1,0) contactWeight	, 
			IF((ctt.ctt_aadhaar_no <> ''  AND ctt.ctt_aadhaar_no IS NOT NULL 
				AND LENGTH(ctt.ctt_aadhaar_no) >=12 ),2,0) hasAdhaar,
			IF((ctt.ctt_voter_no <> ''  AND ctt.ctt_voter_no IS NOT NULL 
				AND LENGTH(ctt.ctt_voter_no) >=8 ),1,0) hasVoter,
			IF((ctt.ctt_license_no <> ''  AND ctt.ctt_license_no IS NOT NULL 
				AND LENGTH(ctt.ctt_license_no) >=8 ),4,0) hasLicense,
			IF(ctt.ctt_license_exp_date > CURRENT_DATE AND document.doc_id IS NOT NULL ,3,0) hasValidLicense,
			IF((ctt.ctt_pan_no <> ''  AND ctt.ctt_pan_no IS NOT NULL 
				AND LENGTH(ctt.ctt_pan_no) =10 ),2,0) hasPan,
			IF(TRIM(ctt.ctt_bank_account_no)<> '' AND ctt.ctt_bank_account_no IS NOT NULL,1,0)  hasBankRef,
			usr.usr_active,
			CASE
				WHEN MAX(phn.phn_is_primary)=1 AND MAX(phn.phn_is_verified)=1 
					AND MAX(phn.phn_verified_date)= phn.phn_verified_date THEN 6
				WHEN MAX(phn.phn_is_primary)=1 AND MAX(phn.phn_is_verified)=1 THEN 4
				WHEN MAX(phn.phn_is_verified)=1 THEN 3
				WHEN MAX(phn.phn_is_primary)=1 THEN 2
				WHEN phn.phn_id IS NOT NULL THEN 1
				ELSE 0
			END as phoneRank,
			CASE
				WHEN MAX(eml.eml_is_primary)=1 AND MAX(eml.eml_is_verified)=1 
					AND MAX(eml.eml_verified_date)= eml.eml_verified_date THEN 6
				WHEN MAX(eml.eml_is_primary)=1 AND MAX(eml.eml_is_verified)=1 THEN 4
				WHEN MAX(eml.eml_is_verified)=1 THEN 3
				WHEN MAX(eml.eml_is_primary)=1 THEN 2
				WHEN eml_id IS NOT NULL THEN 1
				ELSE 0
			END as emailRank,
			IF(document.doc_id IS NOT NULL, 0, 1) AS documentUpload 
		FROM users usr 
		INNER JOIN contact_profile cpr ON cpr.cr_is_consumer = usr.user_id 
		AND cpr.cr_status = 1
		INNER JOIN contact ctt ON ctt.ctt_id = cpr.cr_contact_id 
		AND ctt.ctt_active =1
		LEFT JOIN `document` ON document.doc_id = ctt.ctt_license_doc_id 
			AND document.doc_active = 1 AND document.doc_status =1
			AND document.doc_type = 5 AND document.doc_file_front_path IS NOT NULL				
		LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_active = 1
		LEFT JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id AND eml.eml_active = 1
		WHERE usr.user_id IN ({$userIds}) AND usr.usr_active IN (1)
		GROUP BY ctt.ctt_id
		ORDER BY contactWeight DESC, hasValidLicense DESC";

		if ($onlyPrimary)
		{
			$relData = DBUtil::queryRow($sql, DBUtil::SDB());
		}
		else
		{
			$relData = DBUtil::query($sql, DBUtil::SDB());
		}
		return $relData;
	}

	/**
	 * This function is used to send notifications  for user login/forgot password otp
	 * @return None
	 */
	public static function notifyCoinExpiry($userId, $expiryDate, $coin, $contactID, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		$contact = Contact::getDetails($contactID);
		$email	 = $contact['eml_email_address'];
		$code	 = $contact['phn_phone_country_code'];
		$number	 = $contact['phn_phone_no'];
		$name	 = $contact['ctt_first_name'] . ' ' . $contact['ctt_last_name'];

		Logger::writeToConsole($schedulePlatform . " - " . $email . " - " . $number . " - " . $name);

		if (($schedulePlatform == 2 && trim($number) == '') || ($schedulePlatform == 3 && trim($email) == ''))
		{
			goto skip;
		}

		$contentParams = [
			'expire'	 => $expiryDate,
			'coin'		 => $coin,
			'username'	 => $name,
			'eventId'	 => "43"
		];

		$link		 = Yii::app()->params['fullBaseURL'] . '/bkpn/' . $bkgId . '/' . $hash;
		$buttonUrl	 = 'bkpn/' . $bkgId . '/' . $hash;

		$buttonUrl			 = $receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_USER, $userId, null, $code, $number, $email, 1, null, null, null, 'mail1', null, null, EmailLog::EMAIL_GOZOCOIN_EXPIRY, EmailLog::Consumers, EmailLog::REF_USER_ID, $userId, EmailLog::SEND_SERVICE_EMAIL, null, null);
		//$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_USER, $userId, null, $code, $number, $email, 1, null, null);
		$eventScheduleParams = EventSchedule::setData($userId, ScheduleEvent::CUSTOMER_REF_TYPE, ScheduleEvent::USER_COIN_EXPIRE, "User coin expire", $isSchedule, CJSON::encode(array('coin' => $coin, 'expiryDate' => $expiryDate, 'contactID' => $contactID)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(43, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 3)
			{
				$success = true;
			}
		}

		skip:
		return $success;
	}

	public static function notifyCoinRecharge($userId, $contactID, $coin, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;

		$contact = Contact::getDetails($contactID);
		$email	 = $contact['eml_email_address'];
		$code	 = $contact['phn_phone_country_code'];
		$number	 = $contact['phn_phone_no'];
		$name	 = $contact['ctt_first_name'] . ' ' . $contact['ctt_last_name'];

		Logger::writeToConsole($name . " - " . $email . " - " . $number);

		if (!$email || empty($email))
		{
			goto success;
		}

		$contentParams		 = [
			'eventId'	 => "44",
			'coin'		 => $coin,
			'username'	 => $name
		];
		//$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_USER, $userId, null, $code, $number, $email, 1, null, null);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $userId, WhatsappLog::REF_TYPE_USER, $userId, null, $code, $number, $email, 1, null, null, null, 'mail1', null, null, EmailLog::EMAIL_GOZOCOIN, EmailLog::Consumers, EmailLog::REF_USER_ID, $userId, EmailLog::SEND_SERVICE_EMAIL, null, null);
		$eventScheduleParams = EventSchedule::setData($userId, ScheduleEvent::CUSTOMER_REF_TYPE, ScheduleEvent::USER_COIN_RECHARGE, "User coin recharge", $isSchedule, CJSON::encode(array('coin' => $coin, 'contactID' => $contactID)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(44, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 3)
			{
				$success = true;
			}
		}
		success:
		return $success;
	}

	/**
	 * @param integer $userId
	 * @param integer $contactId
	 * @param integer $bkgId
	 * @param integer $bookingId
	 * @param integer $eventId
	 * @param integer $isSchedule
	 * @param integer $schedulePlatform
	 * @return boolean
	 */
	public static function remindToUpdateAddress($userId, $contactId, $bkgId, $bookingId, $eventId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;

		$contact = Contact::getDetails($contactId);
		$email	 = $contact['eml_email_address'];
		$code	 = $contact['phn_phone_country_code'];
		$number	 = $contact['phn_phone_no'];
		$name	 = $contact['ctt_first_name'] . ' ' . $contact['ctt_last_name'];

		Logger::writeToConsole($userId . " - " . $contactId . " - " . $bkgId . " - " . $bookingId . " - " . $eventId . " - " . $isSchedule . " - " . $schedulePlatform);
		Logger::writeToConsole($email . " - " . $code . $number . " - " . $name);

//		if ((in_array($schedulePlatform, [1, 2]) && trim($number) == '') || ($schedulePlatform == 3 && trim($email) == ''))
//		{
//			goto skip;
//		}

		$hash		 = Yii::app()->shortHash->hash($bkgId);
		$buttonUrl	 = 'bkpn/' . $bkgId . '/' . $hash;
		$link		 = Yii::app()->params['fullBaseURL'] . '/bkpn/' . $bkgId . '/' . $hash;

		Logger::writeToConsole($hash . " - " . $buttonUrl . " - " . $link);

		$contentParams = [
			'eventId'		 => $eventId,
			'bookingId'		 => $bookingId,
			'username'		 => $name,
			'link'			 => $link,
			'customercareno' => '+91-90518-77000'
		];

		$entityType			 = UserInfo::TYPE_CONSUMER;
		$entityId			 = $userId;
		$refType			 = WhatsappLog::REF_TYPE_BOOKING;
		$refId				 = $bkgId;
		$ext				 = $code;
		$isButton			 = 1;
		$appEventCode		 = null;
		$emailReplyTo		 = null;
		$emailReplyName		 = null;
		$emailType			 = EmailLog::EMAIL_UPDATE_ADDRESS_REMINDER;
		$emailUserType		 = EmailLog::Consumers;
		$emailRefType		 = EmailLog::REF_USER_ID;
		$emailRefId			 = $bookingId;
		$emailLogInstance	 = EmailLog::SEND_SERVICE_EMAIL;
		$emailDelayTime		 = 0;
		$smsRefId			 = $bookingId;

		$receiverParams = EventReceiver::setData(
						$entityType,
						$entityId,
						$refType,
						$refId,
						$bookingId,
						$ext,
						$number,
						$email, $isButton, $appEventCode, SmsLog::SMS_UPDATE_ADDRESS_REMINDER,
						$buttonUrl,
						'mail1', $emailReplyTo, $emailReplyName,
						$emailType,
						$emailUserType,
						$emailRefType,
						$emailRefId,
						$emailLogInstance,
						$emailDelayTime,
						$smsRefId);

		$arrData = ['bookingId' => $bookingId, 'username' => $name, 'link' => $link, 'customercareno' => '+91-90518-77000', 'contactId' => $contactId];

		Logger::writeToConsole(json_encode($arrData));

		$eventScheduleParams = EventSchedule::setData(
						$bkgId,
						ScheduleEvent::BOOKING_REF_TYPE,
						ScheduleEvent::UDPATE_BOOKING_ADDRESS,
						"Update Booking Address",
						$isSchedule,
						CJSON::encode($arrData),
						10, $schedulePlatform);

		$responseArr = MessageEventMaster::processPlatformSequences($eventId, $contentParams, $receiverParams, $eventScheduleParams);

		Logger::writeToConsole(json_encode($responseArr));

		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
			}
			else if ($response['success'] && $response['type'] == 3)
			{
				$success = true;
			}
		}

		skip:

		Logger::writeToConsole("Success: " . $success);

		return $success;
	}

}
