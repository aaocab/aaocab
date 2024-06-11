<?php

/**
 * This is the model class for table "booking_user".
 *
 * The followings are the available columns in table 'booking_user':
 * @property integer $bui_id
 * @property integer $bui_bkg_id
 * @property integer $bkg_user_id
 * @property string $bkg_user_fname
 * @property string $bkg_user_lname
 * @property string $bkg_country_code
 * @property string $bkg_contact_no
 * @property string $bkg_alt_country_code
 * @property string $bkg_alt_contact_no
 * @property string $bkg_user_email
 * @property string $bkg_user_city
 * @property string $bkg_user_country
 * @property string $bkg_crp_name
 * @property string $bkg_crp_email
 * @property string $bkg_crp_phone
 * @property string $bkg_crp_country_code
 * @property string $bkg_verifycode_email
 * @property integer $bkg_phone_verified
 * @property integer $bkg_email_verified
 * @property string $bkg_verification_code
 * @property string $bkg_bill_fullname
 * @property string $bkg_bill_contact
 * @property string $bkg_bill_email
 * @property string $bkg_bill_address
 * @property string $bkg_bill_company
 * @property string $bkg_bill_gst
 * @property string $bkg_bill_country
 * @property string $bkg_bill_state
 * @property string $bkg_bill_city
 * @property string $bkg_bill_postalcode
 * @property string $bkg_bill_bankcode
 * @property string $bkg_user_last_updated_on
 * @property integer $bkg_contact_id
 * @property integer $bkg_traveller_type
 * The followings are the available model relations:
 * @property Booking $buiBkg
 * @property Users $bkgUser
 * @property Contact $buiContact
 */
class BookingUser extends CActiveRecord
{

	public $bkg_verification_code1, $bkg_verification_code2;
	public $hash1, $hash2, $hash;
	public $ptype, $fullContactNumber, $bkg_bill_bankcode1, $bkg_bill_bankcode2;
	public $coordinates, $platform;
	public $bkg_country_code1	 = '91', $bkg_contact_no1, $bkg_country_code2	 = '91', $bkg_contact_no2;
	public $bkg_user_email1, $bkg_user_fname1, $bkg_user_lname1, $isBlockedLocation;

	// public $partialPayment;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bui_bkg_id', 'required'),
			['bkg_user_fname, bkg_user_lname,bkg_country_code,bkg_contact_no', 'required', 'on' => 'spotShuttle'],
			['bkg_contact_no', 'validateBillingPhone', 'on' => 'spotShuttle'],
			['bkg_user_fname', 'validatePassengerData', 'on' => 'validatePassengerInfo'],
			['bkg_user_fname, bkg_user_lname', 'required', 'on' => 'spotShuttleAdditional'],
			array('bui_bkg_id, bkg_contact_id, bkg_user_id, bkg_phone_verified, bkg_email_verified', 'numerical', 'integerOnly' => true),
			array('bkg_user_fname, bkg_user_lname, bkg_user_email, bkg_user_city, bkg_user_country, bkg_bill_fullname, bkg_bill_contact, bkg_bill_email, bkg_bill_address, bkg_bill_country, bkg_bill_state, bkg_bill_city', 'length', 'max' => 255),
			array('bkg_country_code, bkg_crp_country_code, bkg_verifycode_email, bkg_verification_code, bkg_bill_postalcode', 'length', 'max' => 10),
			array('bkg_contact_no', 'length', 'max' => 100),
			array('bkg_alt_country_code, bkg_crp_phone', 'length', 'max' => 50),
			array('bkg_alt_contact_no', 'length', 'max' => 25),
			array('bkg_crp_name', 'length', 'max' => 500),
			array('bkg_crp_email', 'length', 'max' => 250),
			array('bkg_user_last_updated_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bui_id, bui_bkg_id, bkg_contact_id, bkg_user_id, bkg_user_fname, bkg_user_lname, bkg_country_code, bkg_contact_no, bkg_alt_country_code, bkg_alt_contact_no, bkg_user_email, bkg_user_city, bkg_user_country, bkg_crp_name, bkg_crp_email, bkg_crp_phone, bkg_crp_country_code, bkg_verifycode_email, bkg_phone_verified, bkg_email_verified, bkg_verification_code, bkg_bill_fullname, bkg_bill_contact, bkg_bill_email, bkg_bill_address, bkg_bill_country, bkg_bill_state, bkg_bill_city, bkg_bill_postalcode, bkg_user_last_updated_on,bkg_bill_bankcode,bkg_traveller_type', 'safe', 'on' => 'search'),
//			array('bkg_bill_fullname, bkg_bill_contact, bkg_bill_country', 'required', 'on' => 'advance_pay, step3, journeydetails'),
			array('bkg_bill_fullname, bkg_bill_contact', 'required', 'on' => 'advance_pay, step3, journeydetails'),
			//array('bkg_bill_postalcode', 'validateBillPostalcode', 'on' => 'advance_pay,step3'),
			//array('bkg_bill_state', 'validateStateId', 'on' => 'advance_pay,step3'),
			array('bkg_bill_fullname, bkg_bill_contact, bkg_bill_email', 'required', 'on' => 'lazypay'),
			array('bkg_bill_contact', 'validateBillingPhone', 'on' => 'lazypay, updateGstin'),
			array('bkg_verification_code1', 'verifyCode', 'on' => 'step3'),
			['bkg_user_fname, bkg_user_lname', 'required', 'on' => 'modifybooking,step_cpaaApp'],
			['bkg_contact_no', 'validatePhone', 'on' => 'additional, admininsert,insert, cabRate,cabRateAgent,modifybooking,t1,t2,step_cpaaApp'],
			['bkg_contact_no', 'validatePhone', 'on' => 'multiroute'],
			['bkg_contact_no', 'required', 'on' => 'step_cpaaApp'],
			array('bkg_user_email', 'email', 'except' => 'stepMobile3,cancel_delete_new,validatePassengerInfo', 'message' => 'Please enter valid email address', 'checkMX' => true),
			['bkg_contact_no', 'checkContactPhoneEmail', 'on' => 'multiroute'],
			['bkg_contact_no', 'checkContactPhoneEmail', 'on' => 'additional, cabRate, cabRateAgent, lead_convert,admininsert,adminupdate,t1,t2'],
			array('bkg_country_code, bkg_verification_code', 'length', 'max' => 10),
			array('bkg_alt_country_code', 'length', 'max' => 50),
			array('bkg_alt_contact_no', 'length', 'max' => 25),
			['bkg_user_fname, bkg_user_lname', 'required', 'on' => 'cabRate,admininsert, adminupdate, step3, stepApp3, additional'],
			['bkg_contact_no', 'validateContact', 'on' => 'admininsert, adminupdate,adminupdateuser, cabRateAgent, cabRate'],
			//array('bkg_user_fname', 'CRegularExpressionValidator', 'pattern' => '/^[a-zA-Z0-9 .]*$/', 'message' => "First Name should contain only alphanumeric characters", 'on' => 'insert, adminupdate,cabRate'),
		//	array('bkg_user_lname', 'CRegularExpressionValidator', 'pattern' => '/^[a-zA-Z0-9 .]*$/', 'message' => "Last Name should contain only alphanumeric characters", 'on' => 'insert, adminupdate,cabRate'),
			array('bkg_bill_contact', 'numerical', 'integerOnly' => true),
			array('bkg_bill_email', 'email'),
			array('bkg_bill_postalcode', 'length', 'max' => 8, 'min' => 4,),
			array('bkg_bill_fullname, bkg_bill_contact, bkg_bill_email', 'required', 'on' => 'step3Paytm'),
			array('bkg_contact_no, bkg_alt_contact_no, bkg_contact_no1', 'length', 'max' => 15, 'min' => 5),
			array('bkg_user_fname, bkg_user_lname, bkg_user_email, bkg_user_city, bkg_user_country, bkg_bill_fullname, bkg_bill_contact, bkg_bill_email, bkg_bill_country, bkg_bill_state, bkg_bill_city', 'length', 'max' => 255),
			array('bkg_contact_no, bkg_contact_no1', 'length', 'max' => 100),
			array('bkg_user_last_updated_on, bkg_bill_company, bkg_bill_gst, bkg_bill_address, isBlockedLocation', 'safe'),
			array('bkg_user_fname, bkg_user_lname'
				, 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
			array('bkg_user_fname, bkg_user_lname', 'required', 'on' => 'stepMobile3'),
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
			'buiBkg'	 => array(self::BELONGS_TO, 'Booking', 'bui_bkg_id'),
			'bkgUser'	 => array(self::BELONGS_TO, 'Users', 'bkg_user_id'),
			'buiContact' => array(self::BELONGS_TO, 'Contact', 'bkg_contact_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bui_id'					 => 'Bui',
			'bui_bkg_id'				 => 'Bui Bkg',
			'bkg_user_id'				 => 'User',
			'bkg_user_fname'			 => 'First Name',
			'bkg_user_lname'			 => 'Last Name',
			'bkg_country_code'			 => 'Country Code',
			'bkg_contact_no'			 => 'Contact Number',
			'bkg_contact_no1'			 => 'Contact Number',
			'bkg_alt_country_code'		 => 'Bkg Alt Country Code',
			'bkg_alt_contact_no'		 => 'Alt Contact No',
			'bkg_user_email'			 => 'Email',
			'bkg_user_email1'			 => 'Email',
			'bkg_user_city'				 => 'User City',
			'bkg_user_country'			 => 'User Country',
			'bkg_crp_name'				 => 'Corporate Name',
			'bkg_crp_email'				 => 'Corporate Email',
			'bkg_crp_phone'				 => 'Corporate Phone',
			'bkg_crp_country_code'		 => 'Country Code',
			'bkg_verifycode_email'		 => 'BkgVerifycode Email',
			'bkg_phone_verified'		 => 'Bkg Phone Verified',
			'bkg_email_verified'		 => 'Bkg Email Verified',
			'bkg_verification_code'		 => 'Verification Code',
			'bkg_verification_code1'	 => 'Enter Verification Code',
			'bkg_verification_code2'	 => 'Enter Verification Code',
			'bkg_bill_fullname'			 => 'Billing Fullname',
			'bkg_bill_contact'			 => 'Contact',
			'bkg_bill_email'			 => 'Billing Email',
			'bkg_bill_address'			 => 'Billing Address',
			'bkg_bill_country'			 => 'Billing Country',
			'bkg_bill_state'			 => 'Billing State',
			'bkg_bill_city'				 => 'Billing City',
			'bkg_bill_postalcode'		 => 'Billing Postal code',
			'bkg_bill_bankcode'			 => 'Billing Bank Code',
			'bkg_user_last_updated_on'	 => 'Bkg User Last Updated On',
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

		$criteria->compare('bui_id', $this->bui_id);
		$criteria->compare('bui_bkg_id', $this->bui_bkg_id);
		$criteria->compare('bkg_user_id', $this->bkg_user_id);
		$criteria->compare('bkg_user_fname', $this->bkg_user_fname, true);
		$criteria->compare('bkg_user_lname', $this->bkg_user_lname, true);
		$criteria->compare('bkg_country_code', $this->bkg_country_code, true);
		$criteria->compare('bkg_contact_no', $this->bkg_contact_no, true);
		$criteria->compare('bkg_alt_country_code', $this->bkg_alt_country_code, true);
		$criteria->compare('bkg_alt_contact_no', $this->bkg_alt_contact_no, true);
		$criteria->compare('bkg_user_email', $this->bkg_user_email, true);
		$criteria->compare('bkg_user_city', $this->bkg_user_city, true);
		$criteria->compare('bkg_user_country', $this->bkg_user_country, true);
		$criteria->compare('bkg_crp_name', $this->bkg_crp_name, true);
		$criteria->compare('bkg_crp_email', $this->bkg_crp_email, true);
		$criteria->compare('bkg_crp_phone', $this->bkg_crp_phone, true);
		$criteria->compare('bkg_crp_country_code', $this->bkg_crp_country_code, true);
		$criteria->compare('bkg_verifycode_email', $this->bkg_verifycode_email, true);
		$criteria->compare('bkg_phone_verified', $this->bkg_phone_verified);
		$criteria->compare('bkg_email_verified', $this->bkg_email_verified);
		$criteria->compare('bkg_verification_code', $this->bkg_verification_code, true);
		$criteria->compare('bkg_bill_fullname', $this->bkg_bill_fullname, true);
		$criteria->compare('bkg_bill_contact', $this->bkg_bill_contact, true);
		$criteria->compare('bkg_bill_email', $this->bkg_bill_email, true);
		$criteria->compare('bkg_bill_address', $this->bkg_bill_address, true);
		$criteria->compare('bkg_bill_country', $this->bkg_bill_country, true);
		$criteria->compare('bkg_bill_state', $this->bkg_bill_state, true);
		$criteria->compare('bkg_bill_city', $this->bkg_bill_city, true);
		$criteria->compare('bkg_bill_postalcode', $this->bkg_bill_postalcode, true);
		$criteria->compare('bkg_user_last_updated_on', $this->bkg_user_last_updated_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingUser the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		parent::beforeSave();
		/* if ($this->bkg_contact_id == "")
		  {
		  $this->linkContact();
		  } */
		return true;
	}

	public function validatePassengerData($attribute, $params)
	{
		$error	 = 0;
		$msg	 = '';
		if ($this->bkg_user_fname == '')
		{
			$error++;
			$msg .= 'Name is empty. ';
		}
		if ($this->bkg_contact_no == '')
		{
			$error++;
			$msg .= 'Phone number is empty. ';
		}
		if ($this->bkg_user_email == '')
		{
			$error++;
			$msg .= 'Email is empty. ';
		}

		if ($this->bkg_contact_no != '')
		{
			$phone = Filter::processPhoneNumber($this->bkg_contact_no, $this->bkg_country_code);
			if (!$phone)
			{
				$this->addError($attribute, 'Invalid phone number.');
				return FALSE;
			}
//            $isValid = Filter::validatePhoneNumber("+" . $this->bkg_country_code . $this->bkg_contact_no);
//			if ($isValid)
//			{
				Filter::parsePhoneNumber($phone, $code, $number);
				$this->bkg_contact_no	 = $number;
				$this->bkg_country_code	 = $code;
			//}
		}
		if ($error > 0)
		{
			$this->addError($attribute, $msg);
			return FALSE;
		}
		return TRUE;
	}

	public function validateBillPostalcode($attribute, $params)
	{
		if (trim($this->bkg_bill_country) != 'HK' && trim($this->bkg_bill_postalcode) == '' && $this->ptype == 4)
		{
			$this->addError($attribute, 'Postal Code is Required');
			return false;
		}
		return true;
	}

	public function validateStateId($attribute, $params)
	{
		if (trim($this->bkg_bill_country) == 'IN' && trim($this->bkg_bill_state) == '')
		{
			$this->addError($attribute, 'Billing State is Required');
			return false;
		}
		return true;
	}

	public function validateBillingPhone($attribute, $params)
	{

		if ($this->$attribute != '')
		{
			$phone = Filter::processPhoneNumber($this->$attribute, $this->bkg_country_code);
			if (!$phone)
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
			Filter::parsePhoneNumber($phone, $code, $number);
			$this->$attribute		 = $number;
			$this->bkg_country_code	 = $code;
		}
		else
		{
			$this->addError($attribute, 'Phone number is required for transaction.');
			return FALSE;
		}
		return true;
	}

	public function getUsername()
	{

		return trim($this->bkg_user_fname) . ' ' . trim($this->bkg_user_lname);
	}

	public function getContactNumber()
	{
		$phone		 = '';
		$contactNo	 = $this->bkg_contact_no;
		$countryCode = $this->bkg_country_code;

		if ($contactNo != '')
		{
			$phone = $countryCode . $contactNo;
		}
		return $phone;
	}

	public function getAlternateNumber()
	{
		$phone = '';
		if ($this->bkg_alt_contact_no != '')
		{
			$phone = $this->bkg_alt_country_code . $this->bkg_alt_contact_no;
		}
		return $phone;
	}

	public function verifyCode($attribute, $params)
	{
		if ($this->bkg_user_id == '')
		{
			if (($this->bkg_verification_code != $this->bkg_verification_code1 ) && $this->bkg_verification_code1 != '')
			{
				$this->addError('bkg_verification_code1', 'Verification code not matched. Please try again');
				return FALSE;
			}
		}
		return true;
	}

	public function isPhoneVerify($bkgId, $phash)
	{
		$success = false;
		$model	 = Booking::model()->findByPk($bkgId);
		if (isset($phash) && $phash != '')
		{
			$otpPhone = Yii::app()->shortHash->unHash($phash);
			if (($otpPhone == $model->bkgUserInfo->bkg_verification_code) && ($model->bkgUserInfo->bkg_phone_verified == 0))
			{
				$bkgUserModel						 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
				$bkgUserModel->bkg_phone_verified	 = 1;
				$bkgUserModel->save();
				$success							 = true;
			}
		}
		return $success;
	}

	public function isEmailVerify($bkgId, $ehash)
	{
		$success = false;
		$model	 = Booking::model()->findByPk($bkgId);
		if (isset($ehash) && $ehash != '')
		{
			$otpEmail = Yii::app()->shortHash->unHash($ehash);
			if (($otpEmail == $model->bkgUserInfo->bkg_verifycode_email) && ($model->bkgUserInfo->bkg_email_verified == 0))
			{
				$bkgUserModel						 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
				$bkgUserModel->bkg_email_verified	 = 1;
				$bkgUserModel->save();
				$success							 = true;
			}
		}
		return $success;
	}

	public function getByBkgId($bkgId)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('bui_bkg_id', $bkgId);
		$model		 = $this->find($criteria);
		if ($model)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public function getFullContactNumber()
	{
		$phone = Filter::processPhoneNumber($this->bkg_contact_no, $this->bkg_country_code);
		if (!$phone)
		{
			if ($this->bkg_country_code == '')
			{
				$this->bkg_country_code = '91';
			}

			$phone = '+' . $this->bkg_country_code . '' . $this->bkg_contact_no;
		}
		return $phone;
	}

	/**
	 *
	 * Link and verify contact for booking
	 *
	 * */
	public function linkContact()
	{
		$contactId = Contact::getIdByDetails($this->bkg_user_email, $this->getFullContactNumber(), $this->bkg_user_fname, $this->bkg_user_lname, false);
		if ($contactId)
		{
			goto linkContact;
		}

		if ($this->bkg_user_id == '')
		{
			$userModel = Users::model()->linkUserByEmail($this->bui_bkg_id, Booking::Platform_User);
		}
		else
		{
			$userModel = $this->bkgUser;
		}

		$contactId = $userModel->usr_contact_id;

		linkContact:
		if ($contactId)
		{
			$this->bkg_contact_id	 = $contactId;
			$emlModel				 = ContactEmail::model()->findByConId($contactId);
			$phnModel				 = ContactPhone::model()->findByConId($contactId);
			if ($emlModel || $phnModel)
			{
				$refModel->bkg_email_verified	 = $emlModel[0]->eml_is_verified ? $emlModel[0]->eml_is_verified : '0';
				$refModel->bkg_phone_verified	 = $phnModel[0]->phn_is_verified ? $phnModel[0]->phn_is_verified : '0';
			}
			else
			{
				$refModel->bkg_email_verified	 = $userModel->usr_email_verify;
				$refModel->bkg_phone_verified	 = $userModel->usr_mobile_verify;
			}
		}
	}

	public function saveVerificationOtp($bkgId)
	{
		$model = BookingUser::model()->getByBkgId($bkgId);
		if ($model->bkg_verification_code == '' && $model->bkg_verification_code == NULL)
		{
			$verificationSms				 = rand(100100, 999999);
			$model->bkg_verification_code	 = strtolower($verificationSms);
			$model->save();
		}
		if ($model->bkg_verifycode_email == '' && $model->bkg_verifycode_email == NULL)
		{
			$verificationEmail			 = rand(100100, 999999);
			$model->bkg_verifycode_email = strtolower($verificationEmail);
			$model->save();
		}
		return $model;
	}

	public function sendVerificationCode($logType = '', $mail = false)
	{
		$verification	 = rand(100100, 999999);
		$number			 = $this->bkg_contact_no;
		$ext			 = $this->bkg_country_code;
		$email			 = $this->bkg_user_email;
		if ($this->bkg_verification_code == '')
		{
			$this->bkg_verification_code = strtolower($verification);
			$this->save();
		}
		if ($number != '')
		{
			$msgCom = new smsWrapper();
			$msgCom->sendVerification($ext, $number, $this->bkg_verification_code, $this->buiBkg->bkg_booking_id, $logType);
		}

		if ($email != '' && $mail)
		{
			$verification1 = rand(100100, 999999);
			if ($this->bkg_verifycode_email == '')
			{
				$this->bkg_verifycode_email = strtolower($verification1);
				$this->save();
			}
			$email = new emailWrapper();
			$email->verificationEmail1($this->buiBkg->bkg_id, $logType);
		}
		else if ($email != '')
		{
			$email = new emailWrapper();
			$email->verificationEmail1($this->buiBkg->bkg_id, $logType);
		}
		return true;
	}

	public function validatePhone($attribute, $params)
	{
		$contactNo	 = $this->bkg_contact_no;
		$countryCode = $this->bkg_country_code;
		if ($contactNo != '')
		{
			$phone = Filter::processPhoneNumber($contactNo, $countryCode);
			if (!$phone)
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
			Filter::parsePhoneNumber($phone, $code, $number);
			$this->bkg_contact_no	 = $number;
			$this->bkg_country_code	 = $code;
		}
		return true;
	}

	public function checkContactPhoneEmail($attribute, $params)
	{
		if ($this->bkg_country_code == '')
		{
			$this->addError($attribute, 'Please provide country code');
			return FALSE;
		}
		if ($this->bkg_contact_no == '' && $this->bkg_user_email == '')
		{
			$this->addError($attribute, 'Please provide contact number or email address');
			return FALSE;
		}
		else
		{
			return true;
		}
	}

	public function validateContact($attribute, $params)
	{
		if (trim($this->bkg_contact_no) == '' && trim($this->bkg_user_email) == '')
		{
			$this->addError($attribute, 'Phone/Email is required');
			return false;
		}
		return true;
	}

	public function getByBookingId($id)
	{
		return $this->find('bui_bkg_id=:id', ['id' => $id]);
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return string
	 */
	public static function isConfirmCashBooking($bkgId)
	{
		$sql = "SELECT COUNT(1) as cnt  FROM `booking_pref` WHERE booking_pref.bpr_bkg_id = '$bkgId' AND booking_pref.bkg_is_confirm_cash=1";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param char $type
	 * @return string
	 */
	public static function getPaymentLink($bkgId, $type = 'p')
	{
		$baseURL = Yii::app()->params['fullBaseURL'];
		$model	 = Booking::model()->findByPk($bkgId);
		$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
		if ($type == 'p')
		{
			if ($model->bkgUserInfo->bkg_verification_code > 0)
			{
				$pHash	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);
				$url	 = $baseURL . '/bkpn/' . urlencode($model->bkg_id) . '/' . urlencode($hash) . '/p/' . urlencode($pHash);
			}
			else
			{
				$url = $baseURL . '/bkpn/' . urlencode($model->bkg_id) . '/' . urlencode($hash);
			}
		}
		else if ($type == 'e')
		{
			if ($model->bkgUserInfo->bkg_verifycode_email > 0)
			{
				$eHash	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
				$url	 = $baseURL . '/bkpn/' . urlencode($model->bkg_id) . '/' . urlencode($hash) . '/e/' . urlencode($eHash);
			}
			else
			{
				$url = $baseURL . '/bkpn/' . urlencode($model->bkg_id) . '/' . urlencode($hash);
			}
		}
		return $url;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return string
	 */
	public static function getPaymentLinkByEmail($bkgId)
	{
		$baseURL = Yii::app()->params['fullBaseURL'];
		$model	 = Booking::model()->findByPk($bkgId);
		$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
		if ($model->bkgUserInfo->bkg_verifycode_email > 0)
		{
			$eHash	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
			$url	 = $baseURL . '/bkpn/' . urlencode($model->bkg_id) . '/' . urlencode($hash) . '/e/' . urlencode($eHash);
		}
		else
		{
			$url = $baseURL . '/bkpn/' . urlencode($model->bkg_id) . '/' . urlencode($hash);
		}
		return $url;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @return string
	 */
	public static function getPaymentLinkByPhone($bkgId)
	{
		$model	 = Booking::model()->findByPk($bkgId);
		$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
		if ($model->bkgUserInfo->bkg_verification_code > 0)
		{
			$pHash	 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);
			$url	 = Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash, 'p' => $pHash]);
		}
		else
		{
			$url = Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
		}
		return $url;
	}

	/**
	 * loadDefault
	 */
	public function loadDefault()
	{
		// Logged User
		//if (UserInfo::isLoggedIn() && in_array(UserInfo::getUserType(), [UserInfo::TYPE_CONSUMER, UserInfo::TYPE_AGENT]))
		if (UserInfo::isLoggedIn() && $this->bkg_user_id == '' && in_array(UserInfo::getUserType(), [UserInfo::TYPE_CONSUMER]))
		{
			$this->bkg_user_id = UserInfo::getUserId();
		}

		$verificationSms				 = rand(100100, 999999);
		$verificationEmail				 = rand(100100, 999999);
		$this->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
		$this->bkg_verification_code	 = strtolower($verificationSms);
		$this->bkg_verifycode_email		 = strtolower($verificationEmail);
	}

	public function validateShuttleData($bkgUserArr)
	{
		$scount		 = count($bkgUserArr['bkg_user_fname']);
		$bkgIds		 = [];
		$totAmount	 = 0;
		$errors		 = [];
		for ($s = 0; $s < $scount; $s++)
		{
			$bkgUser1					 = new BookingUser('spotShuttle');
//			$bkgUser1->bui_bkg_id='100000';
			$bkgUser1->bkg_user_fname	 = $bkgUserArr['bkg_user_fname'][$s];
			$bkgUser1->bkg_user_lname	 = $bkgUserArr['bkg_user_lname'][$s];
			if ($s == 0)
			{
				$bkgUser1->bkg_country_code	 = $bkgUserArr['bkg_country_code'][$s];
				$bkgUser1->bkg_contact_no	 = trim($bkgUserArr['bkg_contact_no'][$s]);
				$bkgUser1->bkg_user_email	 = trim($bkgUserArr['bkg_user_email'][$s]);
			}
			else
			{
				$contactRadio = $bkgUserArr['contactRadio'][($s + 1)];

				$bkgUser1->bkg_country_code	 = ($bkgUserArr['bkg_country_code'][$s] == '' && $contactRadio == 1) ? $bkgUserArr['bkg_country_code'][0] : $bkgUserArr['bkg_country_code'][$s];
				$bkgUser1->bkg_contact_no	 = (trim($bkgUserArr['bkg_contact_no'][$s]) == '' && $contactRadio == 1) ? trim($bkgUserArr['bkg_contact_no'][0]) : trim($bkgUserArr['bkg_contact_no'][$s]);
				$bkgUser1->bkg_user_email	 = (trim($bkgUserArr['bkg_user_email'][$s]) == '' && $contactRadio == 1) ? trim($bkgUserArr['bkg_user_email'][0]) : trim($bkgUserArr['bkg_user_email'][$s]);
				if ($contactRadio == 1)
				{
					$bkgUser1->scenario = 'spotShuttleAdditional';
				}
			}
			if (!$bkgUser1->validate())
			{
				$errors[$s] = $bkgUser1->getErrors();
				if (isset($errors[$s]['bui_bkg_id']))
				{
					unset($errors[$s]['bui_bkg_id']);
				}
			}
		}
		return array_filter($errors);
	}

	public function verifyOtpConsumer($bumodel, $model)
	{
		$success	 = false;
		$isVerifyOtp = true;
		$email		 = $model->bkgUserInfo->buiContact->contactEmails[0]->eml_email_address ? $model->bkgUserInfo->buiContact->contactEmails[0]->eml_email_address : $model->bkgUserInfo->bkg_user_email;
		$contactNo	 = $model->bkgUserInfo->buiContact->contactPhones[0]->phn_phone_no ? $model->bkgUserInfo->buiContact->contactPhones[0]->phn_phone_no : $model->bkgUserInfo->bkg_contact_no;
		$code		 = $model->bkgUserInfo->buiContact->contactPhones[0]->phn_phone_country_code ? $model->bkgUserInfo->buiContact->contactPhones[0]->phn_phone_country_code : $model->bkgUserInfo->bkg_country_code;
		if ($bumodel->otp == '' || $bumodel->otp == 'NA')
		{
			$success	 = true;
			$usersModel	 = Users::model()->find('usr_email=:email', ['email' => $email]);
			$usersModel1 = Users::model()->find('usr_mobile=:phone', ['phone' => $contactNo]);
			if (($usersModel != '' && $usersModel->usr_email_verify == 1) || ($usersModel1 != '' && $usersModel1->usr_mobile_verify == 1))
			{
				if ($usersModel->usr_email_verify == 1)
				{
					$model->bkgUserInfo->bkg_email_verified = 1;
				}
				if ($usersModel1->usr_mobile_verify == 1)
				{
					$model->bkgUserInfo->bkg_phone_verified = 1;
				}
			}
			if ($model->bkgUserInfo->bkg_phone_verified == 1 || $model->bkgUserInfo->bkg_email_verified == 1)
			{
				$isVerifyOtp = false;
				$model->bkgUserInfo->save();
			}
			else
			{
				$verification = rand(100100, 999999);
				if ($model->bkgUserInfo->bkg_verification_code == '')
				{
					$model->bkgUserInfo->bkg_verification_code = strtolower($verification);
					$model->bkgUserInfo->save();
				}
				$number	 = $contactNo;
				$ext	 = $code;
				if ($number != '')
				{
					$msgCom		 = new smsWrapper();
					$msgCom->sendVerification($ext, $number, $model->bkgUserInfo->bkg_verification_code, $model->bkg_booking_id, 10);
					$isVerifyOtp = true;
				}
			}
		}
		else
		{
			if ($model->bkgUserInfo->bkg_verification_code == $bumodel->otp)
			{
				$model->bkgUserInfo->bkg_phone_verified		 = 1;
				$model->bkgUserInfo->bkg_verification_code	 = '';
				if ($model->bkgUserInfo->save())
				{
					$success = true;
				}
				if ($model->bkgUserInfo->bkg_email_verified == 1)
				{
					$usersModel = Users::model()->findAll('usr_email=:email', ['email' => $email]);
					foreach ($usersModel as $user)
					{
						if ($user != '' && $user->usr_email_verify != 1)
						{
							$user->usr_email_verify = 1;
							$user->save();
						}
					}
				}
				if ($model->bkgUserInfo->bkg_phone_verified == 1)
				{
					$usersModel = Users::model()->findAll('usr_mobile=:phone', ['phone' => $contactNo]);
					foreach ($usersModel as $user)
					{
						if ($user != '' && $user->usr_mobile_verify != 1)
						{
							$user->usr_mobile_verify = 1;
							$user->save();
						}
					}
				}
			}
			else
			{
				throw new Exception('Invalid OTP: ', ReturnSet::ERROR_INVALID_DATA);
			}
		}
		return ['success' => $success, 'isVerifyOtp' => $isVerifyOtp];
	}

	public function updateData($model, $bkgId)
	{

		if ($model)
		{
			$bkgUserModel						 = $this->getByBkgId($bkgId);
			$bkgUserModel->scenario				 = 'validateData';
			$bkgUserModel->bui_bkg_id			 = $bkgId;
			$bkgUserModel->bkg_user_fname		 = $model->bkg_user_fname;
			$bkgUserModel->bkg_user_lname		 = $model->bkg_user_lname;
			$bkgUserModel->bkg_user_email		 = $model->bkg_user_email;
			$bkgUserModel->bkg_contact_no		 = $model->bkg_contact_no;
			$bkgUserModel->bkg_country_code		 = $model->bkg_country_code;
			$bkgUserModel->bkg_alt_country_code	 = $model->bkg_alt_country_code;
			$bkgUserModel->bkg_alt_contact_no	 = $model->bkg_alt_contact_no;
			$bkgUserModel->bkg_traveller_type	 = $model->bkg_traveller_type;
			$bkgUserModel->bkg_contact_id        = $model->bkg_contact_id;
			if (!$bkgUserModel->save())
			{
				throw new Exception("Failed to update user data", ReturnSet::ERROR_FAILED);
			}
			else
			{
				return true;
			}
		}
	}

	/**
	 *
	 * @param BookingUser $model
	 * @param array $userInfo
	 * @param integer $bookingId
	 * @return \BookingUser
	 * @throws Exception
	 */
	public static function saveBillingInfo($model, $userInfo, $bookingId)
	{
		$modelUser = BookingUser::model()->getByBkgId($bookingId);
		if (!$modelUser)
		{
			$modelUser = new BookingUser();
		}
		$modelUser->attributes				 = Filter::removeNull($model->attributes);
		$modelUser->bkg_user_last_updated_on = new CDbExpression('NOW()');
		$userId								 = $userInfo->userId;
		if (!$userId)
		{
			$userModel = Users::model()->linkUserByEmail($modelUser->bkg_user_id, Booking::Platform_App);
			if ($userModel)
			{
				$userId = $userModel->user_id;
			}
		}
		if ($userId > 0)
		{
			$modelUser->bkg_user_id = $userId;
		}
		if (!$modelUser->save())
		{
			$errors = $modelUser->getErrors();
			Logger::create("Validate Errors : " . json_encode($errors));
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		return $modelUser;
	}

	public static function updateGmtPassengerInfo($model, $bkgId, $type = 0)
	{

		if ($model)
		{
			$bkgUserModel						 = $model->getByBkgId($bkgId);
			$bkgUserModel->scenario				 = 'validatePassengerInfo';
			$bkgUserModel->bui_bkg_id			 = $bkgId;
			$bkgUserModel->bkg_user_fname		 = $model->bkg_user_fname;
			$bkgUserModel->bkg_user_lname		 = $model->bkg_user_lname;
			$bkgUserModel->bkg_user_email		 = $model->bkg_user_email;
			$bkgUserModel->bkg_contact_no		 = $model->bkg_contact_no;
			$bkgUserModel->bkg_country_code		 = $model->bkg_country_code;
			$bkgUserModel->bkg_alt_country_code	 = $model->bkg_alt_country_code;
			$bkgUserModel->bkg_alt_contact_no	 = $model->bkg_alt_contact_no;
			Logger::beginProfile("Contact::createbyBookingUser");
			$cttId								 = Contact::createbyBookingUser($bkgUserModel);
			Logger::endProfile("Contact::createbyBookingUser");
			if ($cttId != '')
			{
				$bkgUserModel->bkg_contact_id = $cttId;
			}
			if (!$bkgUserModel->save())
			{
				$errors = $bkgUserModel->getErrors();
				Logger::create("Validate Errors : " . json_encode($errors));
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			return true;
		}
	}

	public static function getUserDetailsById($bookingUserId)
	{
		$sql		 = "SELECT bkg_user_id, ctt_id,ctt_owner_id,ctt_user_type,ctt_business_name,ctt_city,ctt_state,ctt_address,ctt_first_name,ctt_last_name,ctt_license_no,ctt_voter_no,ctt_aadhaar_no,ctt_pan_no,ctt_license_exp_date,ctt_license_doc_id,ctt_created_date,
			eml_email_address,eml_is_verified,eml_verified_date,phn_phone_country_code,phn_phone_no,phn_is_verified,usr_country,usr_city,
			usr_zip,usr_ip,usr_last_login,usr_create_platform,usr_mark_customer_count,usr_overall_rating,usr_gender FROM booking_user bui
		LEFT JOIN users ON users.user_id = bui.bkg_user_id
		LEFT JOIN contact ON bui.bkg_contact_id = contact.ctt_id
		LEFT JOIN contact_email ON ctt_id = eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
		LEFT JOIN contact_phone ON ctt_id = phn_contact_id  AND phn_is_primary = 1 	AND phn_active =1
		WHERE bui_id = '" . $bookingUserId . "' ";
		return $contactAll	 = DBUtil::queryRow($sql);
	}

	public static function createContactFromUser($bkgUserData)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (empty($bkgUserData))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}
			$buId = $bkgUserData['bui_id'];
			if (strpos($bkgUserData["bkg_user_email"], "test"))
			{
				throw new Exception("Booking User Id: $buId Skipped as it contains test in email", ReturnSet::ERROR_INVALID_DATA);
			}
			$response = "Bui Id: $buId failed"; //Default
			if ($buId)
			{
				$bookingUserModel	 = BookingUser::model()->findByPk($buId);
				$cttId				 = self::updateBkgUserContact($bookingUserModel);
				if ($cttId > 0)
				{
					$response = "BkgUser Id: $buId updated with Contact Id: $cttId";
				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$response	 = $ex->getMessage();
			Logger::error($ex->getMessage());
			DBUtil::rollbackTransaction($transaction);
			$sql		 = "UPDATE booking_user SET bkg_temp_status = 1 WHERE bui_id = {$buId}";
			DBUtil::command($sql)->execute();
		}
		skipData:
		return $response;
	}

	public static function updateBkgUserContact($bkgUserModel)
	{
		$emailId	 = ($bkgUserModel->bkg_user_email == null) ? null : $bkgUserModel->bkg_user_email;
		$phoneNo	 = ($bkgUserModel->bkg_contact_no == null) ? null : $bkgUserModel->bkg_contact_no;
		$firstName	 = ($bkgUserModel->bkg_user_fname == null) ? null : $bkgUserModel->bkg_user_fname;
		$lastName	 = ($bkgUserModel->bkg_user_lname == null) ? null : $bkgUserModel->bkg_user_lname;
		if (empty($emailId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$verified	 = false;
		$cttId		 = Contact::getIdByDetails($emailId, $phoneNo, $firstName, $lastName, $verified);
		if ($cttId > 0)
		{
			$emailModel = ContactEmail::model()->findByEmailAndContact($emailId, $cttId);
			if (empty($emailModel))
			{
				ContactEmail::addNew($cttId, $emailId, SocialAuth::Eml_aaocab, 1, '');
			}
			if ($phoneNo)
			{
				$phoneModel = ContactPhone::model()->findByPhoneAndContact($phoneNo, $cttId);
				if (empty($phoneModel))
				{
					ContactPhone::add($cttId, $phoneNo, '', $bkgUserModel->bkg_country_code, 1, 1, '');
				}
			}
			self::updateContactId($cttId, $bkgUserModel->bui_id);
			ContactProfile::setProfile($cttId, UserInfo::TYPE_CONSUMER);
			//ContactProfile::updateEntity($cttId, $userModel->user_id, UserInfo::TYPE_CONSUMER);
			goto End;
		}

		$cttId = Contact::createbyBookingUser($bkgUserModel);
		self::updateContactId($cttId, $bkgUserModel->bui_id);
		End:
		return $cttId;
	}

	public static function updateContactId($cttIds = null, $buId = null)
	{
		if (empty($cttIds) || empty($buId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql = "UPDATE booking_user SET bkg_contact_id = $cttIds WHERE bui_id = $buId";
		DBUtil::command($sql)->execute();
	}

	public function getRepeatCustomerBookingsCounReport($date1, $date2, $dateType)
	{
		$params	 = ['date1' => $date1, 'date2' => $date2];
		$where	 = $dateType == 1 ? "   bkg_status IN (6,7) AND booking.bkg_pickup_date " : " bkg_status IN (2,3,5,6,7,9) AND booking.bkg_create_date";
		$select	 = $dateType == 1 ? "DATE_FORMAT(bkg_pickup_date, '%Y-%m') AS date " : "DATE_FORMAT(bkg_create_date, '%Y-%m') AS date ";
		$sql	 = "SELECT   temp.date, Sum(temp.CountRepeatCustomer) AS CountRepeatCustomer
					FROM
					(
						SELECT
						$select,
						COUNT(users.usr_contact_id) as cnt,
						COUNT(DISTINCT users.usr_contact_id) as CountRepeatCustomer
						FROM   booking
						INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
						INNER JOIN users ON users.user_id =booking_user.bkg_user_id
						WHERE   $where  BETWEEN :date1 AND :date2 AND  users.usr_contact_id  is not null
						GROUP BY date, users.usr_contact_id Having cnt>=2
					) temp GROUP BY temp.date";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	/**
	 *
	 * @param integer $userId
	 * @return int
	 */
	public function getBonusByUserId($userId)
	{
		$bonusAmount = 0;
		$bkgId		 = Users::getLastBookingById($userId);
		if ($bkgId == null || $bkgId == '')
		{
			goto skipCashback;
		}
		/* @var $model BookingUser */
		$model		 = $this->getByBookingID($bkgId);
		$bonusAmount = BookingInvoice::calculateBonus($model->buiBkg->bkgInvoice);

		skipCashback:

		return $bonusAmount;
	}

	public static function updateUserInfo($bkgId, $data)
	{
		$userInfo						 = UserInfo::getInstance();
		$bkgUserModel					 = BookingUser::model()->getByBkgId($bkgId);
		$bkgUserModel->scenario			 = 'validatePassengerInfo';
		$name							 = explode(" ", $data['passenger']['name']);
		$bkgUserModel->bkg_user_fname	 = (!isset($name[0]) ? "" : $name[0]);
		$bkgUserModel->bkg_user_lname	 = (!isset($name[1]) ? "" : $name[1]);
		$bkgUserModel->bkg_user_email	 = $data['passenger']['email'];
		$bkgUserModel->bkg_country_code	 = $data['passenger']['country_code'];
		$bkgUserModel->bkg_contact_no	 = $data['passenger']['phone_number'];
		Logger::beginProfile("Contact::createbyBookingUser");
		$cttId							 = Contact::createbyBookingUser($bkgUserModel);
		Logger::endProfile("Contact::createbyBookingUser");
		if ($cttId != '')
		{
			$bkgUserModel->bkg_contact_id = $cttId;
		}
		$userId = $userInfo->userId;
		if (!$userId)
		{
			$userModel = Users::model()->linkUserByEmail($bkgId, Booking::Platform_Agent);
			if ($userModel)
			{
				$userId = $userModel->user_id;
			}
		}
		if ($userId > 0)
		{
			$bkgUserModel->bkg_user_id = $userId;
		}
		if (!$bkgUserModel->save())
		{
			$errors = $bkgUserModel->getErrors();
			Logger::create("Validate Errors : " . json_encode($errors));
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
	}

	/**
	 *
	 * @param integer $bkgId
	 * 	@param integer $phone
	 * @return False| Array
	 */
	public static function verifyBookingContact($bkgId, $phone)
	{
		$params	 = ['bkgid' => $bkgId, 'phoneno' => $phone];
		$sql	 = "SELECT bkg_id, bkg_booking_id, IF(phn1.phn_phone_no = :phoneno, c1.ctt_id, c2.ctt_id) as callerContactId,
						c1.ctt_id userContactId, c2.ctt_id travellerContactId, bkg_user_id,bkg_status,
						(phn1.phn_is_verified + phn2.phn_is_verified) phnIsVerified
					FROM   booking bkg
					INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id
					INNER JOIN contact_profile cr ON bui.bkg_user_id = cr.cr_is_consumer AND cr.cr_status = 1
					INNER JOIN contact c1 ON c1.ctt_id = cr.cr_contact_id AND c1.ctt_active = 1
					LEFT JOIN contact c2 ON c2.ctt_id = bui.bkg_contact_id AND c2.ctt_active = 1
					INNER JOIN contact_phone phn1 ON phn1.phn_contact_id = c1.ctt_id AND phn1.phn_active = 1
					LEFT JOIN contact_phone phn2 ON phn2.phn_contact_id = c2.ctt_id AND phn2.phn_active = 1
				WHERE bkg.bkg_id = :bkgid AND
				(
					bui.bkg_contact_no = :phoneno OR
					phn1.phn_phone_no = :phoneno OR
					phn2.phn_phone_no = :phoneno
				)
				ORDER BY  (phn1.phn_is_primary + phn1.phn_is_verified +phn2.phn_is_primary + phn2.phn_is_verified  ) DESC
		";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used to get last completed booking details for given user id 
	 * @param integer $userid
	 * @return  Array
	 */
	public static function getLastCompletedBkgId($userid)
	{
		$sql = "SELECT
					bkg_id,
					bkg_net_base_amount
				FROM booking
					INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
					INNER JOIN booking_user on bui_bkg_id = booking.bkg_id
					INNER JOIN booking_trail on booking_trail.btr_bkg_id = booking.bkg_id
				WHERE 1 
					AND bkg_user_id=:userid 
					AND bkg_status IN (6,7)
					AND bkg_agent_id IS NULL
				ORDER BY btr_mark_complete_date DESC";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['userid' => $userid]);
	}

	/**
	 * This function is used to get last completed booking count for given user id 
	 * @param integer $userid
	 * @return  Array
	 */
	public static function getCompletedBookingByUserCount($userid)
	{
		$sql = "SELECT 
					COUNT(bkg_id)
				FROM booking
					INNER JOIN booking_user on bui_bkg_id = booking.bkg_id
				WHERE 1
					AND bkg_user_id=:userid 
					AND bkg_agent_id IS NULL
					AND bkg_status IN (6,7)";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['userid' => $userid]);
	}

	/**
	 * 
	 * @param type $model
	 * @param type $jsonObj
	 * @return type
	 */
	public static function updateTransferzData($model, $jsonObj)
	{
		$result								 = false;
		$model->bkgUserInfo->scenario		 = 'validatePassengerInfo';
		$model->bkgUserInfo->bkg_user_fname	 = ($jsonObj->travellerInfo->firstName != $model->bkgUserInfo->bkg_user_fname) ? $jsonObj->travellerInfo->firstName : $model->bkgUserInfo->bkg_user_fname;
		$model->bkgUserInfo->bkg_user_lname	 = ($jsonObj->travellerInfo->lastName != $model->bkgUserInfo->bkg_user_lname) ? $jsonObj->travellerInfo->lastName : $model->bkgUserInfo->bkg_user_lname;
		$model->bkgUserInfo->bkg_user_email	 = ($jsonObj->travellerInfo->email != $model->bkgUserInfo->bkg_user_email) ? $jsonObj->travellerInfo->email : $model->bkgUserInfo->bkg_user_email;
		$model->bkgUserInfo->bkg_contact_no	 = ($jsonObj->travellerInfo->phone != $model->bkgUserInfo->bkg_contact_no) ? ltrim($jsonObj->travellerInfo->phone) : $model->bkgUserInfo->bkg_contact_no;
		if ($model->bkgUserInfo->save())
		{
			$result = true;
		}
		return $result;
	}

	/**
	 * 
	 * @param int $bkgId
	 * @return array
	 */
	public static function getBookingBillingDetails($bkgId)
	{
		$contactData			 = [];
		$model					 = BookingUser::model()->getByBkgId($bkgId);
		$contactData['email']	 = $model->bkg_user_email;
		$contactData['phone']	 = $model->bkg_contact_no;
		return $contactData;
	}

	
	/**
	 * 
	 * @param type $model
	 * @param type $bkgUserAttr
	 * @param type $bkgid
	 */
	public static function chageTravellerInfo($model, $bkgUserAttr, $bkgId)
	{
		$model->bkgUserInfo->bkg_traveller_type = $bkgUserAttr['bkg_traveller_type'];
		$model->bkgUserInfo->bkg_user_fname = $bkgUserAttr['bkg_user_fname'];
		$model->bkgUserInfo->bkg_user_lname = $bkgUserAttr['bkg_user_lname'];
		$model->bkgUserInfo->bkg_country_code = $bkgUserAttr['bkg_country_code'];
		$model->bkgUserInfo->bkg_contact_no = $bkgUserAttr['bkg_contact_no'];
		$model->bkgUserInfo->bkg_user_email = $bkgUserAttr['bkg_user_email'];
		
		$transaction = DBUtil::beginTransaction();
		try
		{
			$result	= BookingUser::model()->updateData($model->bkgUserInfo, $bkgId);
			if($result)
			{
				$desc = "Traveller info changed";
				BookingLog::model()->createLog($model->bkgUserInfo->bui_bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_TRAVELLER_INFO_CHNAGED, false);
				$data = ['success' => true, 'username' => trim($bkgUserAttr['bkg_user_fname'] . ' ' . $bkgUserAttr['bkg_user_lname']), 'email' => $bkgUserAttr['bkg_user_email'], 'phoneno' => '+'.$bkgUserAttr['bkg_country_code'].'-'.$bkgUserAttr['bkg_contact_no']];
			}
			else
			{
				$data = ['success' => false];
			}
			
			DBUtil::commitTransaction($transaction);
			echo json_encode($data);
			Yii::app()->end();
		} 
		catch (Exception $ex) {
	        DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
			echo json_encode($returnSet);
			Yii::app()->end();
		}
		
	}
}
