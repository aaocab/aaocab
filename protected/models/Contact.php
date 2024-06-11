<?php

/**
 * This is the model class for table "contact".
 *
 * The followings are the available columns in table 'contact':
 * @property integer $ctt_id
 * @property string $ctt_voter_no
 * @property string $ctt_aadhaar_no
 * @property string $ctt_pan_no
 * @property string $ctt_first_name
 * @property string $ctt_last_name
 * @property string $ctt_business_name
 * @property string $ctt_profile_path
 * @property string $ctt_state
 * @property integer $ctt_city
 * @property string $ctt_address
 * @property integer $ctt_preferred_language
 * @property string $ctt_known_language
 * @property integer $ctt_owner_id
 * @property string $ctt_license_no
 * @property string $ctt_license_exp_date
 * @property string $ctt_license_issue_date
 * @property string $ctt_bank_name
 * @property string $ctt_bank_branch
 * @property string $ctt_bank_account_no
 * @property string $ctt_bank_ifsc
 * @property string $ctt_beneficiary_name
 * @property string $ctt_beneficiary_id
 * @property integer $ctt_account_type
 * @property integer $ctt_user_type
 * @property integer $ctt_business_type
 * @property integer $ctt_voter_doc_id
 * @property integer $ctt_license_doc_id
 * @property integer $ctt_aadhar_doc_id
 * @property integer $ctt_pan_doc_id
 * @property integer $ctt_memo_doc_id
 * @property integer $ctt_is_verified
 * @property integer $ctt_active
 * @property string $ctt_created_date
 * @property string $ctt_modified_date
 * @property string $ctt_tags
 * @property string $ctt_dl_issue_authority
 * @property string $ctt_is_name_dl_matched
 * @property string $ctt_is_name_pan_matched
 * @property string $ctt_bank_details_modify_date
 * @property integer $ctt_vaccine_status
 * @property string $ctt_vaccine_details
 * @property string $ctt_vaccine_modified_date
 * The followings are the available model relations:
 * @property ContactEmail[] $contactEmails
 * @property ContactProfile $contactProfile
 * @property ContactPhone[] $contactPhones
 * @property Vendors[] $vendors
 * @property Contact $contactOwner
 * @property Drivers $drvContact
 * @property ContactTemp $contactTemp
 */
class Contact extends BaseActiveRecord
{

	const TYPE_EMAIL					 = 1;
	const TYPE_PHONE					 = 2;
	const TYPE_NOTIFICATION			 = 3;
	const MODE_LINK					 = 1;
	const MODE_OTP					 = 2;
	const RETURN_ARRAY				 = 1;
	const NOTIFY_OLD_CON_TEMPLATE		 = 1;
	const NEW_CON_TEMPLATE			 = 2;
	const NOTIFY_CON_DECLINED_TEMPLATE = 3;
	const MODIFY_CON_TEMPLATE			 = 4;

	public $ctt_license_issue_date1, $ctt_license_exp_date1, $ownername, $search, $searchtype, $name, $email_address, $phone_no,
			$userInput, $accountInput, $ctt_trip_type, $contactperson, $phn_phone_no, $eml_email_address, $new_ctt_id,
			$check_ctt_address, $check_ctt_city, $check_ctt_state, $check_ctt_voter_no, $check_ctt_aadhaar_no,
			$check_ctt_pan_no, $check_ctt_license_no, $check_ctt_bank_name, $check_ctt_bank_account_no, $check_ctt_bank_branch, $check_ctt_bank_ifsc,
			$check_ctt_beneficiary_name, $check_ctt_beneficiary_id, $userId, $eml_is_verified, $ctyName, $ctt_bank_details_modify_date;
	public $contactDetails	 = [];
	public $driverDetails, $contactTempDetails, $vndId, $cityId, $isDco;
	public $locale_license_exp_date, $locale_license_issue_date, $arr_known_language;
	public $userType		 = [1 => 'Individual', 2 => 'Business'];
	public $bussinessType	 = [0 => '', 1 => 'Sole Propitership', 2 => 'Partner', 3 => 'Private Limited', 4 => 'Limited'];
	public $accountType		 = [0 => 'Savings', 1 => 'Current'];
	public $activeType		 = [0 => 'deleted', 1 => 'active', 2 => 'deactive', 3 => 'pending approval', 4 => 'ready for approval'];
	public $isVerified		 = [0 => 'Unverified', 1 => 'Verified'];
	public $validateType	 = [0 => 'Phone and Email', 1 => 'Phone', 2 => 'Email', 3 => 'Phone, Email and City'];
	public $addType			 = 0;
	public $isApp			 = false;
	public $commit			 = true;
	public $isServiceCall	 = 0;
	public $number1, $number2, $number3, $number4;
	public $strTags;

	// public $ctt_user_type;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('ctt_state', 'required'),
			//array('ctt_license_no,ctt_license_exp_date,ctt_license_issue_date', 'required'),
			array('ctt_owner_id, ctt_account_type, ctt_user_type, ctt_voter_doc_id, ctt_license_doc_id, ctt_aadhar_doc_id, ctt_pan_doc_id, ctt_memo_doc_id, ctt_is_verified, ctt_active,ctt_state', 'numerical', 'integerOnly' => true),
			array('ctt_voter_no, ctt_aadhaar_no, ctt_pan_no, ctt_first_name, ctt_last_name, ctt_license_no, ctt_bank_name, ctt_bank_branch, ctt_beneficiary_name, ctt_beneficiary_id', 'length', 'max' => 100),
			array('ctt_address', 'length', 'max' => 255),
			//array('ctt_id', 'validatePhoneEmail'),
			array('ctt_first_name', 'validateRegister', 'on' => 'validateRegister'),
			array('ctt_user_type', 'validateName', 'on' => 'contactInsUp'),
			array('ctt_license_no, ctt_aadhaar_no, ctt_pan_no, ctt_voter_no', 'validateExistingDocDetails', 'on' => 'insert'),
			array('ctt_bank_account_no, ctt_bank_ifsc', 'length', 'max' => 20),
			array('ctt_license_exp_date, ctt_license_issue_date, ctt_city', 'safe', 'on' => 'search'),
			array('ctt_first_name, ctt_last_name', 'nameValidation', 'on' => 'nameValidation'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ctt_id, ctt_ref_code, ctt_voter_no, ctt_aadhaar_no, ctt_pan_no, ctt_first_name, ctt_last_name, ctt_business_name,
				ctt_profile_path, ctt_state, ctt_city, ctt_address, ctt_preferred_language, ctt_known_language, ctt_owner_id,
				ctt_license_no, ctt_license_exp_date, ctt_license_issue_date, ctt_bank_name, ctt_bank_branch, ctt_bank_account_no,
				ctt_bank_ifsc, ctt_beneficiary_name, ctt_beneficiary_id, ctt_account_type, ctt_user_type, ctt_business_type,
				ctt_voter_doc_id, ctt_license_doc_id, ctt_aadhar_doc_id, ctt_pan_doc_id, ctt_memo_doc_id, ctt_is_verified,
				ctt_active, ctt_created_date, ctt_modified_date,ctt_dl_issue_authority,ctt_trip_type,ctt_tags,strTags,
				arr_known_language, locale_license_issue_date, locale_license_exp_date,ctt_police_doc_id,ctt_is_name_dl_matched,ctt_is_name_pan_matched,ctt_vaccine_details,ctt_vaccine_status,ctt_vaccine_modified_date,strTags', 'safe'),
			array('ctt_name', 'unsafe'),
		);
	}

	public function nameValidation($attributes, $params)
	{
		if ($this->hasErrors())
		{
			return false;
		}
		if ($this->ctt_first_name != '')
		{
			$this->addError("ctt_first_name", "First Name cannot be changed");
			//  return false;
		}

		if ($this->ctt_last_name != '')
		{
			$this->addError("ctt_last_name", "Last Name cannot be changed");
			// return false;
		}
		return true;
	}

	public function validateName($attributes, $params)
	{
		if ($this->ctt_user_type != '' && $this->ctt_user_type == 1)
		{
			if ($this->ctt_first_name == '')
			{
				$this->addError("ctt_first_name", "First Name is mandatory");
				return false;
			}
		}
		if ($this->ctt_user_type != '' && $this->ctt_user_type == 2)
		{
			if ($this->ctt_business_name == '')
			{
				$this->addError("ctt_business_name", "Buisness Name is mandatory");
				return false;
			}
		}
	}

	public function validateRegister($attributes, $params)
	{

		if ($this->ctt_first_name == '' && $this->ctt_user_type != '' && $this->ctt_user_type == 1)
		{
			$this->addError("ctt_first_name", "First Name is mandatory");
			return false;
		}
		if ($this->ctt_last_name == '' && $this->ctt_user_type != '' && $this->ctt_user_type == 1)
		{
			$this->addError("ctt_last_name", "Last Name is mandatory");
			return false;
		}
		if ($this->contactPhones[0]->phn_phone_no == '' && $this->contactPhones[0]['phn_phone_no'] == '')
		{
			$this->addError("ctt_last_name", "Phone number is mandatory");
			return false;
		}

		if ($this->ctt_state == '')
		{
			$this->addError("ctt_state", "State is mandatory");
			return false;
		}
		if ($this->ctt_city == '')
		{
			$this->addError("ctt_city", "City is mandatory");
			return false;
		}
	}

	/**
	 * Contact Doc data validate on Insert scenario
	 * @return boolean
	 */
	public function validateExistingDocDetails($attribute, $params)
	{
		$success = true;
		$bool	 = self::checkExistingDetails($attribute, $this->$attribute, $this->ctt_id);

		if ($bool)
		{
			$label	 = $this->getAttributeLabel($attribute);
			$this->addError($attribute, "$label already exists");
			$success = false;
		}

		return $success;
	}

	public function validatePhoneEmail($attributes, $params)
	{
		if ($this->addType == -1)
		{
			return true;
		}

		if (array_filter($this->contactEmails) == [] && in_array($this->addType, [0, 2, 3]))
		{
			$this->addError("ctt_id", "Email Address is mandatory");
			return false;
		}
		if (array_filter($this->contactPhones) == [] && in_array($this->addType, [0, 1, 3]))
		{
			$this->addError("ctt_id", "Phone Number is mandatory");
			return false;
		}

		if (array_filter($this->contactEmails) != [])
		{
			$resEmails = CActiveForm::validate($this->contactEmails);
			if ($resEmails != '[]')
			{
				$this->addError("contactEmails", $resEmails);
				return false;
			}
		}
		if (array_filter($this->contactPhones) != [])
		{
			$resPhones = CActiveForm::validate($this->contactPhones);
			if ($resPhones != '[]')
			{
				$this->addError("contactPhones", $resPhones);
				return false;
			}
		}
		if ($this->ctt_city == "" && in_array($this->addType, [3]))
		{
			$this->addError("ctt_city", "City is mandatory ");
			return false;
		}
		if ($this->ctt_license_no == "" && in_array($this->addType, [1]))
		{
			$this->addError("ctt_city", "License no is mandatory ");
			return false;
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
			'contactEmails'			 => array(self::HAS_MANY, 'ContactEmail', 'eml_contact_id'),
			'contactProfile'		 => array(self::HAS_ONE, 'ContactProfile', 'cr_contact_id'),
			'contactPhones'			 => array(self::HAS_MANY, 'ContactPhone', 'phn_contact_id'),
			'contactOwner'			 => array(self::BELONGS_TO, 'Contact', 'ctt_owner_id'),
			'drvContact'			 => array(self::HAS_ONE, 'Drivers', 'drv_contact_id'),
			'contactVoter'			 => array(self::BELONGS_TO, 'Document', 'ctt_voter_doc_id'),
			'contactAadhar'			 => array(self::BELONGS_TO, 'Document', 'ctt_aadhar_doc_id'),
			'contactLicense'		 => array(self::BELONGS_TO, 'Document', 'ctt_license_doc_id'),
			'contactPan'			 => array(self::BELONGS_TO, 'Document', 'ctt_pan_doc_id'),
			'contactMemo'			 => array(self::BELONGS_TO, 'Document', 'ctt_memo_doc_id'),
			'contactPoliceVerify'	 => array(self::BELONGS_TO, 'Document', 'ctt_police_doc_id'),
			'contactTemp'			 => array(self::HAS_ONE, 'ContactTemp', 'tmp_ctt_contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ctt_id'				 => 'Ctt',
			'ctt_voter_no'			 => 'Voter No',
			'ctt_aadhaar_no'		 => 'Aadhaar No',
			'ctt_pan_no'			 => 'Pan No',
			'ctt_business_name'		 => 'Business Name',
			'ctt_first_name'		 => 'First Name',
			'ctt_last_name'			 => 'Last Name',
			'ctt_profile_path'		 => 'Profile Image',
			'ctt_state'				 => 'State',
			'ctt_city'				 => 'City',
			'ctt_address'			 => 'Address',
			'ctt_preferred_language' => 'Preferred Language',
			'ctt_known_language'	 => 'Known Language',
			'ctt_owner_id'			 => 'Owner',
			'ctt_license_no'		 => 'License No',
			'ctt_license_exp_date'	 => 'License Exp Date',
			'ctt_license_issue_date' => 'License Issue Date',
			'ctt_bank_name'			 => 'Bank Name',
			'ctt_bank_branch'		 => 'Bank Branch',
			'ctt_bank_account_no'	 => 'Account No.',
			'ctt_bank_ifsc'			 => 'IFSC Code',
			'ctt_beneficiary_name'	 => 'Account Owner Name',
			'ctt_beneficiary_id'	 => 'Account Owner ID',
			'ctt_account_type'		 => 'Account Type',
			'ctt_user_type'			 => 'User Type',
			'ctt_business_type'		 => 'Business Type',
			'ctt_voter_doc_id'		 => 'Voter Doc',
			'ctt_license_doc_id'	 => 'License Doc',
			'ctt_aadhar_doc_id'		 => 'Aadhar Doc',
			'ctt_pan_doc_id'		 => 'Pan Doc',
			'ctt_memo_doc_id'		 => 'Memo Doc',
			'ctt_is_verified'		 => 'Is Verified',
			'ctt_active'			 => 'Active',
			'ctt_created_date'		 => 'Created Date',
			'ctt_modified_date'		 => 'Modified Date',
			'ctt_dl_issue_authority' => 'License Issuing Authority',
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

		$criteria->compare('ctt_id', $this->ctt_id);
		$criteria->compare('ctt_voter_no', $this->ctt_voter_no, true);
		$criteria->compare('ctt_aadhaar_no', $this->ctt_aadhaar_no, true);
		$criteria->compare('ctt_pan_no', $this->ctt_pan_no, true);
		$criteria->compare('ctt_first_name', $this->ctt_first_name, true);
		$criteria->compare('ctt_last_name', $this->ctt_last_name, true);
		$criteria->compare('ctt_business_name', $this->ctt_business_name, true);
		$criteria->compare('ctt_profile_path', $this->ctt_profile_path, true);
		$criteria->compare('ctt_state', $this->ctt_state, true);
		$criteria->compare('ctt_city', $this->ctt_city, true);
		$criteria->compare('ctt_address', $this->ctt_address, true);
		$criteria->compare('ctt_preferred_language', $this->ctt_preferred_language, true);
		$criteria->compare('ctt_known_language', $this->ctt_known_language, true);
		$criteria->compare('ctt_owner_id', $this->ctt_owner_id);
		$criteria->compare('ctt_license_no', $this->ctt_license_no, true);
		$criteria->compare('ctt_license_exp_date', $this->ctt_license_exp_date, true);
		$criteria->compare('ctt_license_issue_date', $this->ctt_license_issue_date, true);
		$criteria->compare('ctt_bank_name', $this->ctt_bank_name, true);
		$criteria->compare('ctt_bank_branch', $this->ctt_bank_branch, true);
		$criteria->compare('ctt_bank_account_no', $this->ctt_bank_account_no, true);
		$criteria->compare('ctt_bank_ifsc', $this->ctt_bank_ifsc, true);
		$criteria->compare('ctt_beneficiary_name', $this->ctt_beneficiary_name, true);
		$criteria->compare('ctt_beneficiary_id', $this->ctt_beneficiary_id, true);
		$criteria->compare('ctt_account_type', $this->ctt_account_type);
		$criteria->compare('ctt_user_type', $this->ctt_user_type);
		$criteria->compare('ctt_business_type', $this->ctt_business_type);
		$criteria->compare('ctt_voter_doc_id', $this->ctt_voter_doc_id);
		$criteria->compare('ctt_license_doc_id', $this->ctt_license_doc_id);
		$criteria->compare('ctt_aadhar_doc_id', $this->ctt_aadhar_doc_id);
		$criteria->compare('ctt_pan_doc_id', $this->ctt_pan_doc_id);
		$criteria->compare('ctt_memo_doc_id', $this->ctt_memo_doc_id);
		$criteria->compare('ctt_is_verified', $this->ctt_is_verified);
		$criteria->compare('ctt_active', $this->ctt_active);
		$criteria->compare('ctt_created_date', $this->ctt_created_date, true);
		$criteria->compare('ctt_modified_date', $this->ctt_modified_date, true);
		$criteria->compare('ctt_vaccine_status', $this->ctt_vaccine_status);
		$criteria->join = 'left join contact_email  ON (contact.ctt_id=contact_email.eml_contact_id)';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contact the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeValidate()
	{
		if ($this->locale_license_exp_date != null)
		{
			$this->ctt_license_exp_date = DateTimeFormat::DatePickerToDate($this->locale_license_exp_date);
		}

		if ($this->locale_license_issue_date != null)
		{
			$this->ctt_license_issue_date = DateTimeFormat::DatePickerToDate($this->locale_license_issue_date);
		}
		if ($this->arr_known_language != null)
		{
			$this->ctt_known_language = implode(",", $this->arr_known_language);
		}
		if ($this->ctt_user_type == 2)
		{
			$this->ctt_first_name	 = ($this->ctt_first_name != "") ? ($this->ctt_first_name) : NULL;
			$this->ctt_last_name	 = ($this->ctt_last_name != "") ? ($this->ctt_last_name) : NULL;
		}
		else
		{
			$this->ctt_business_name = ($this->ctt_business_name != "") ? ($this->ctt_business_name) : NULL;
			$this->ctt_business_type = 0;
		}
		if ($this->ctt_license_no != null)
		{
			$this->ctt_license_no = str_replace(' ', '', $this->ctt_license_no);
		}
		return parent::beforeValidate();
	}

	public function afterFind()
	{
		parent::afterFind();
		if ($this->ctt_license_exp_date != null)
		{
			$this->locale_license_exp_date = DateTimeFormat::DateToDatePicker($this->ctt_license_exp_date);
		}
		if ($this->ctt_license_issue_date != null)
		{
			$this->locale_license_issue_date = DateTimeFormat::DateToDatePicker($this->ctt_license_issue_date);
		}
		if ($this->ctt_known_language != null)
		{
			$this->arr_known_language = explode(",", $this->ctt_known_language);
		}
	}

	public function afterSave()
	{
		parent::afterSave();

		if ($this->isNewRecord && $this->ctt_id > 0)
		{
			$contactPref				 = new ContactPref();
			$contactPref->cpr_ctt_id	 = $this->ctt_id;
			$contactPref->cpr_category	 = 1;
			$contactPref->save();
		}
		if ($this->isNewRecord && $this->ctt_ref_code == null)
		{
			$this->setIsNewRecord(false);
			$this->ctt_ref_code = $this->ctt_id;
			$this->update();
		}
	}

	/*
	 * this function will update bank_details_modify_date whenever bankdetails are updating..
	 * param $oldData array of old contact data as array
	 * param $newData array of new contact data as array
	 *
	 */

	public function checkBankDetailsUpdate($oldData, $newData = NULL)
	{
		//echo "<prE>";print_r($oldData);print_r($this);die;
		$newData['ctt_id'] = $newData['ctt_id'] ? $newData['ctt_id'] : $this->ctt_id;
		if ($oldData['ctt_bank_name'] !== $newData['ctt_bank_name'] || $oldData['ctt_bank_branch'] !== $newData['ctt_bank_branch'] || $oldData['ctt_bank_account_no'] !== $newData['ctt_bank_account_no'] || $oldData['ctt_bank_ifsc'] != $newData['ctt_bank_ifsc'] || $oldData['ctt_account_type'] != $newData['ctt_account_type'])
		{
			if ($newData['ctt_id'])
			{
				$params									 = ['ctt_id' => $newData['ctt_id']];
				$newData['ctt_bank_details_modify_date'] = date("Y-m-d H:i:s");
				$sql									 = "UPDATE contact SET ctt_bank_details_modify_date =now() WHERE ctt_id = :ctt_id";
				$cnt									 = DBUtil::command($sql, DBUtil::MDB())->execute($params);
			}
		}
	}

	/** @return ReturnSet */
	public function create($verify = true, $userType = UserInfo::TYPE_CONSUMER)
	{
		$transaction = null;
		$returnSet	 = new ReturnSet();
		try
		{
			$transaction = DBUtil::beginTransaction();
			$isNew		 = $this->isNewRecord;
			$res		 = $this->save();
			if (!$res)
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			Contact::updateRefCode($this->ctt_id, $this->ctt_id);

			$emailResponse = $this->saveEmails();

			if ($emailResponse->hasErrors())
			{
				throw $emailResponse->getException();
			}

			ContactEmail::setPrimaryEmail($this->ctt_id);

			$phoneResponse = $this->savePhones();
			if ($phoneResponse->hasErrors())
			{
				throw $phoneResponse->getException();
			}
			ContactPhone::setPrimaryPhone($this->ctt_id);

			if (!$verify)
			{
				goto end;
			}
			$this->refresh();
			foreach ($this->contactEmails as $emlModel)
			{
				if (!$emlModel->eml_is_verified)
				{
					Contact::emailVerificationLink($emlModel->eml_email_address, $emlModel->eml_contact_id, $userType, Contact::NEW_CON_TEMPLATE);
				}
			}

			foreach ($this->contactPhones as $phnModel)
			{
				if (!$phnModel->phn_is_verified)
				{
					Contact::sendPhoneVerificationLink($phnModel->phn_full_number, $phnModel->phn_contact_id, $userType, Contact::NEW_CON_TEMPLATE, 0, $phnModel->phn_phone_country_code, $phnModel->phn_otp, 0);
				}
			}

			$desc = "New contact created";
			ContactLog::model()->createLog($this->ctt_id, $desc, ContactLog::CONTACT_CREATED, null);
			$returnSet->setStatus(true);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($exc);
		}
		end:
		return $returnSet;
	}

	public function add($tempValue = 0, $oldData = '', $newData = '')
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();

		try
		{
			$isNew	 = $this->isNewRecord;
			$res	 = $this->save();
			/**
			 * update ref code for contact
			 */
			Contact::updateRefCode($this->ctt_id, $this->ctt_id);
			if (!$res)
			{
				$returnSet->setErrors($this->getErrors(), 0);
				throw new CHttpException("Failed to add contact", 1);
			}
			$emailResponse		 = $this->saveEmails();
			ContactEmail::setPrimaryEmail($this->ctt_id);
			$phoneResponse		 = $this->savePhones();
			$phoneResponseData	 = json_decode(json_encode($phoneResponse))->data;
			// $phoneNumber       = $phoneResponseData->ext . $phoneResponseData->number;

			ContactPhone::setPrimaryByPhone($phoneResponseData->number, $this->ctt_id);
			$this->saveProfileImage($this->ctt_id);
			// ContactProfile::setProfile($this->ctt_id, $this->ctt_type);
			$desc		 = ($isNew) ? "Contact created" : "Contact modified";
			$event		 = ($isNew) ? ContactLog::CONTACT_CREATED : ContactLog::CONTACT_MODIFIED;
			$phoneData	 = $phoneResponse->getData();
			$emlPkId	 = $emailResponse->getData();
			$emailData	 = ContactEmail::model()->findByPk($emlPkId);
			$userType	 = ($this->addType == 3) ? UserInfo::TYPE_VENDOR : UserInfo::TYPE_DRIVER;
			if (!$isNew)
			{
				if ($tempValue != 1)
				{
					if ($emailResponse->getStatus())
					{
						$isEmailSend = Contact::sendVerification($emailData->eml_email_address, Contact::TYPE_EMAIL, $this->ctt_id, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_LINK, $userType);
					}
					if ($phoneResponse->getStatus())
					{
						$phoneData	 = $phoneResponse->getData();
						$isNew		 = $phoneData['isNew'];
						if ($isNew)
						{
							$isOtpSend = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $this->ctt_id, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_OTP, $userType, 0, $phoneData["otp"], $phoneData["ext"]);
						}
					}
				}
			}
			else
			{
				if ($tempValue != 1)
				{
					if ($emailResponse->getStatus())
					{
						$isEmailSend = Contact::sendVerification($emailData->eml_email_address, Contact::TYPE_EMAIL, $this->ctt_id, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, $userType);
					}
					if ($phoneResponse->getData())
					{
						$isOtpSend = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $this->ctt_id, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, $userType, 0, $phoneData["otp"], $phoneData["ext"]);
					}
				}
			}
			if ($oldData != '' && $newData != '')
			{
				if ($oldData['ctt_bank_name'] != $newData['ctt_bank_name'] || $oldData['ctt_bank_branch'] != $newData['ctt_bank_branch'] || $oldData['ctt_bank_account_no'] != $newData['ctt_bank_account_no'] || $oldData['ctt_bank_ifsc'] != $newData['ctt_bank_ifsc'] || $oldData['ctt_account_type'] != $newData['ctt_account_type'])
				{
					$desc .= ', Bank details modified';
				}
			}

			ContactLog::model()->createLog($this->ctt_id, $desc, $event, null);

			if ($this->commit)
			{
				DBUtil::commitTransaction($transaction);
			}
			$returnSet->setStatus(true);
			$returnSet->setData(["id" => $this->ctt_id]);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $contactId
	 * @return Array
	 */
	public static function findByRefCode($contactId)
	{
		$params	 = ['contactId' => $contactId];
		$sql	 = "SELECT ctt_id FROM `contact` WHERE `ctt_ref_code` = :contactId AND ctt_id <>:contactId AND ctt_active=1";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/** @return ReturnSet  */
	public function saveEmails()
	{
		$response = ContactEmail::saveEmails($this->contactEmails, $this->ctt_id);
		return $response;
	}

	/** @return ReturnSet  */
	public function savePhones()
	{
		$response = ContactPhone::savePhones($this->contactPhones, $this->ctt_id);
		return $response;
	}

	public function addPhone($phone)
	{
		$cpModel				 = ContactPhone::model()->getObject($phone, $this->ctt_id);
		$this->contactPhones[]	 = $cpModel;
		$this->savePhones();
	}

	public function addEmail($email)
	{
		$ceModel				 = ContactEmail::model()->getObject($email, $this->ctt_id);
		$this->contactEmails[]	 = $ceModel;
		$this->saveEmails();
	}

	public function convertToEmailObjects($emailArrays)
	{
		$ceModels = [];
		foreach ($emailArrays as $email)
		{
			$cemodel = ContactEmail::getObject($email['eml_email_address'], $this->ctt_id, $email['eml_is_primary'], true, $email['eml_type']);
			if ($cemodel)
			{
				$ceModels[] = $cemodel;
			}
		}
		return $ceModels;
	}

	public function convertToPhoneObjects($phoneArrays)
	{
		$cpModels = [];
		foreach ($phoneArrays as $phone)
		{
			$phoneNumber = Filter::processPhoneNumber($phone['phn_phone_no'], $phone['phn_phone_country_code']);
			if (!$phoneNumber)
			{
				continue;
			}
			$cpmodel = ContactPhone::getObject($phoneNumber, $this->ctt_id, $phone['phn_is_primary'], true, $phone['phn_type'], $phone['phn_is_new']);
			if ($cpmodel)
			{
				$cpModels[] = $cpmodel;
			}
		}
		return $cpModels;
	}

	public function convertToContactEmailObjects($emailArrays)
	{
		$ceModels	 = [];
		$cemodel	 = ContactEmail::getObject($emailArrays, $this->ctt_id);
		$ceModels[]	 = $cemodel;
		return $ceModels;
	}

	public function convertToContactPhoneObjects($phoneArrays)
	{
		$cpModels	 = [];
		$cpmodel	 = ContactPhone::getObject($phoneArrays, $this->ctt_id);
		$cpModels[]	 = $cpmodel;
		return $cpModels;
	}

	public function language()
	{
		$arr = [
			0	 => 'English',
			1	 => 'Hindi',
			2	 => 'Bangla',
			3	 => 'Gujarati',
			4	 => 'Marathi',
			5	 => 'Punjabi',
			6	 => 'Malayalam',
			7	 => 'Tamil',
			8	 => 'Telegu',
			9	 => 'Kannada'
		];
		return $arr;
	}

	public static function languageList($lang = null)
	{
		$arr = [
			0	 => [
				"id"	 => 0,
				"val"	 => "en",
				"text"	 => "English"
			],
			1	 => [
				"id"	 => 1,
				"val"	 => "hi",
				"text"	 => "Hindi"
			],
			2	 =>
			[
				"id"	 => 2,
				"val"	 => "bn",
				"text"	 => "Bangla"
			],
			3	 =>
			[
				"id"	 => 3,
				"val"	 => "gu",
				"text"	 => "Gujarati"
			],
			4	 =>
			[
				"id"	 => 4,
				"val"	 => "mr",
				"text"	 => "Marathi"
			],
			5	 =>
			[
				"id"	 => 5,
				"val"	 => "pa",
				"text"	 => "Punjabi"
			],
			6	 =>
			[
				"id"	 => 6,
				"val"	 => "ml",
				"text"	 => "Malayalam"
			],
			7	 =>
			[
				"id"	 => 7,
				"val"	 => "ta",
				"text"	 => "Tamil"
			],
			8	 =>
			[
				"id"	 => 8,
				"val"	 => "te",
				"text"	 => "Telegu"
			],
			9	 =>
			[
				"id"	 => 9,
				"val"	 => "kn",
				"text"	 => "Kannada"
			]
		];
		if (is_numeric($lang))
		{
			return $arr[$lang];
		}

		return $arr;
	}

	public function getJSON($arr = [])
	{
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function updateContact($docId, $docType, $cttId, $identityNo, $license_exp_date = NULL, $first_name = NULL, $last_name = NULL, $flag = 0)
	{
		$model = $this->findByPk($cttId);

		if ($docType == 2)
		{
			$model->ctt_voter_doc_id = $docId;
			$model->ctt_voter_no	 = $identityNo;
		}
		else if ($docType == 3)
		{
			//$identityNo ="test";
			$model->ctt_aadhar_doc_id	 = $docId;
			$model->ctt_aadhaar_no		 = $identityNo;
		}
		else if ($docType == 4)
		{
			$model->ctt_pan_doc_id			 = $docId;
			$model->ctt_pan_no				 = $identityNo;
			$model->ctt_is_name_pan_matched	 = 0;
		}
		else if ($docType == 5)
		{
			if ($flag == 1)
			{
				$model->ctt_license_exp_date = $license_exp_date != NULL ? DateTimeFormat::DatePickerToDate($license_exp_date) : NULL;
			}
			$desc						 = "Old value: " . $model->ctt_first_name . " " . $model->ctt_last_name . "|| New Value:" . $first_name . " " . $last_name;
			$model->ctt_license_doc_id	 = $docId;
			if ($identityNo != null)
			{
				$model->ctt_license_no = $identityNo;
			}
			if ($first_name != null)
			{
				$model->ctt_first_name = $first_name;
			}
			if ($last_name != null)
			{
				$model->ctt_last_name = $last_name;
			}
			$model->ctt_is_name_dl_matched = 0;
		}
		else if ($docType == 6)
		{
			$model->ctt_memo_doc_id = $docId;
		}
		else if ($docType == 7)
		{
			$model->ctt_police_doc_id = $docId;
		}
		$model->isApp = true;
		$model->update();
		if ($desc != '')
		{
			ContactLog::model()->createLog($model->ctt_id, $desc, ContactLog::CONTACT_MODIFIED, null);
		}
	}

	public function updateContactDocNumber($contact_id, $data)
	{
		$model							 = $this->findByPk($contact_id);
		$oldData						 = $model->attributes;
		$model->ctt_voter_no			 = $data['vnd_voter_no'];
		$model->ctt_aadhaar_no			 = $data['vnd_aadhaar_no'];
		$model->ctt_pan_no				 = $data['vnd_pan_no'];
		$model->ctt_license_no			 = $data['vnd_license_no'];
		$model->isApp					 = true;
		$model->ctt_license_exp_date	 = $data['vnd_license_exp_date'];
		$model->ctt_dl_issue_authority	 = $data['vnd_license_issue_auth'];
		$model->ctt_bank_name			 = $data['vnd_bank_name'];
		$model->ctt_bank_branch			 = $data['vnd_bank_branch'];
		$model->ctt_bank_ifsc			 = $data['vnd_bank_ifsc'];
		$model->ctt_bank_account_no		 = $data['vnd_bank_account_no'];
		$model->ctt_beneficiary_name	 = $data['vnd_beneficiary_name'];
		if ($data['vnd_beneficiary_id'] != '')
		{
			$model->ctt_beneficiary_id = $data['vnd_beneficiary_id'];
		}
		if ($data['vnd_account_type'] != '')
		{
			$model->ctt_account_type = $data['vnd_account_type'];
		}
		$model->update();
		$newData = $model->attributes;
		$this->checkBankDetailsUpdate($oldData, $newData);
	}

	public function fetchList($ctype = '')
	{

		$sql = "select
				cnt.ctt_id,
				cnt.ctt_name as contactperson,
				cnt.ctt_voter_no,
				cnt.ctt_aadhaar_no,
				cnt.ctt_pan_no,
				cnt.ctt_license_no,
				cnt.ctt_business_name,
				cnt.ctt_state,
				cnt.ctt_city,
				cnt.ctt_is_verified,
				cnt.ctt_profile_path,
				cnt.ctt_user_type,
				cnt.ctt_tags,
				group_concat( distinct cntp.phn_phone_no SEPARATOR ',') as phn_phone_no,
				group_concat( distinct cnte.eml_email_address SEPARATOR ',') as eml_email_address,
				cnt.ctt_address,
				group_concat(cntp.phn_is_verified separator ',') as phn_is_verified,
				group_concat(cnte.eml_is_verified separator ',') as eml_is_verified
				from `contact` cnt
				join contact_phone as cntp on cnt.ctt_id = cntp.phn_contact_id	and cntp.phn_active = 1
				left join contact_email as cnte on	cnt.ctt_id = cnte.eml_contact_id	and cnte.eml_active = 1
				where cnt.ctt_active = 1 ";

		$sqlCount = "select cnt.ctt_id
					from `contact` cnt
					join contact_phone as cntp on	cnt.ctt_id = cntp.phn_contact_id	and cntp.phn_active = 1
					left join contact_email as cnte on	cnt.ctt_id = cnte.eml_contact_id and cnte.eml_active = 1
					where	cnt.ctt_active = 1 ";

		if ($ctype != "")
		{
			if ($ctype == 2)
			{
				$ctype = "1,2";
			}
			$sql		 .= " AND cnt.ctt_user_type IN ($ctype)";
			$sqlCount	 .= " AND cnt.ctt_user_type IN ($ctype)";
		}
		if (isset($this->strTags) && $this->strTags != "")
		{
			$searchTags = $this->strTags;
			if (count($searchTags) > 0)
			{
				$arr = [];
				foreach ($searchTags as $tags)
				{
					$arr[] = "FIND_IN_SET($tags,REPLACE(ctt_tags,' ',''))";
				}
				$search2[] = "(" . implode(' OR ', $arr) . ")";

				$sql		 .= " AND " . implode(" AND ", $search2);
				$sqlCount	 .= " AND " . implode(" AND ", $search2);
			}
		}
		if (isset($this->search) && $this->search != "")
		{
			DBUtil::getLikeStatement($this->search, $bindString, $params);
			$sql1	 = "select 	group_concat(ctt_id SEPARATOR ',')
						from contact
						join contact_phone on	ctt_id = phn_contact_id		and phn_active = 1
						left join contact_email on	ctt_id = eml_contact_id	and eml_active = 1
						where	ctt_active = 1 AND (ctt_business_name LIKE $bindString) OR (ctt_first_name LIKE $bindString)
						OR (ctt_last_name LIKE $bindString) OR (eml_email_address LIKE $bindString) OR (phn_phone_no LIKE $bindString) OR (ctt_license_no LIKE $bindString)";
			$cttIds	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), $params);
			if ($cttIds != 0)
			{
				DBUtil::getINStatement($cttIds, $bindString1, $params1);
				$sql		 .= " AND cnt.ctt_id IN ($bindString1)";
				$sqlCount	 .= " AND cnt.ctt_id IN ($bindString1)";
			}
		}
		$sql			 .= " group by cnt.ctt_id";
		$sqlCount		 .= " group by cnt.ctt_id";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB(), $params1);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'params'		 => $params1,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['ctt_contact_person'],
				'defaultOrder'	 => 'ctt_id DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function fetchSearchList($dataprovider = array())
	{

		$sql = "SELECT
				cnt.ctt_id,
				cnt.ctt_voter_no,
				cnt.ctt_aadhaar_no,
				cnt.ctt_pan_no,
				cnt.ctt_license_no,
				cntp.phn_phone_no,
				cnte.eml_email_address,
				cntp.phn_active,
				cnte.eml_active,
				cntp.phn_is_verified,
				cnte.eml_is_verified,
			    cntf.cr_is_vendor as vendor_id,
                cntf.cr_is_driver as driver_id,
                cntf.cr_is_consumer as consumer_id,
                docvoter.doc_file_front_path as doc_voter_front, docvoter.doc_file_back_path as doc_voter_back,
                docaadhar.doc_file_front_path as doc_aadhar_front, docaadhar.doc_file_back_path as doc_aadhar_back,
                docpan.doc_file_front_path as doc_pan_front, docpan.doc_file_back_path as doc_pan_back,
                doclicence.doc_file_front_path as doc_license_front, doclicence.doc_file_back_path as doc_license_back
				FROM `contact` cnt
				INNER JOIN (SELECT phn_contact_id,group_concat(distinct phn_phone_no SEPARATOR ',') as phn_phone_no,group_concat(phn_is_verified SEPARATOR ',') as phn_is_verified,group_concat(phn_active SEPARATOR ',') as phn_active FROM contact_phone group by phn_contact_id) AS cntp
				ON cnt.ctt_id = cntp.phn_contact_id
				LEFT JOIN (SELECT eml_contact_id,group_concat(distinct eml_email_address SEPARATOR ',') as eml_email_address,group_concat(eml_is_verified SEPARATOR ',') as eml_is_verified,group_concat(eml_active SEPARATOR ',') as eml_active FROM contact_email group by eml_contact_id) AS cnte
				ON	cnt.ctt_id = cnte.eml_contact_id
                LEFT JOIN contact_profile as cntf ON cnt.ctt_id = cntf.cr_contact_id
                LEFT JOIN document as docvoter ON cnt.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1
                LEFT JOIN document as docaadhar ON cnt.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1
				LEFT JOIN document as docpan ON cnt.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1
				LEFT JOIN document as doclicence ON cnt.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1
				where cnt.ctt_active = 1 ";

		if (isset($this->search) && $this->search != "")
		{
			$countprovider	 = count($dataprovider);
			$isPhoneArray	 = [];
			$isEmailArray	 = [];
			$isLicenseArray	 = [];
			$isVoterArray	 = [];
			$isAadharArray	 = [];
			$isPanArray		 = [];
			$strparams		 = [];
			$searchType		 = "";
			if ($countprovider > 0)
			{
				foreach ($dataprovider as $key => $value)
				{
					###########PHONE SEARCH##################################################
					$phoneArray = explode(',', $dataprovider[$key]['phn_phone_no']);
					for ($i = 0; $i < count($phoneArray); $i++)
					{
						$bindString	 = $bindString . $i;
						$params		 = $params . $i;
						if (!in_array($phoneArray[$i], $isPhoneArray) && ($phoneArray[$i] != ''))
						{
							DBUtil::getLikeStatement($phoneArray[$i], $bindString, $params);
							$searchType .= "(cntp.phn_phone_no LIKE $bindString) OR ";
							array_push($strparams, $params);
							array_push($isPhoneArray, $phoneArray[$i]);
						}
					}
					##########PHONE SEARCH END##################################################
					##########EMAIL SEARCH######################################################
					$emailArray = explode(',', $dataprovider[$key]['eml_email_address']);
					for ($i = 0; $i < count($emailArray); $i++)
					{
						if (!in_array($emailArray[$i], $isEmailArray) && ($emailArray[$i] != ''))
						{
							$searchType .= "(cnte.eml_email_address = '$emailArray[$i]') OR ";
							array_push($isEmailArray, $emailArray[$i]);
						}
					}
					##########EMAIL SEARCH END##################################################
					##########LICENSE SEARCH####################################################
					$license = $dataprovider[$key]['ctt_license_no'];
					if (!in_array($license, $isLicenseArray) && $license != '')
					{
						$searchType .= "(ctt_license_no = '$license') OR ";
						array_push($isLicenseArray, $license);
					}
					##########LICENSE SEARCH END################################################
					##########VOTER SEARCH####################################################
					$voter = $dataprovider[$key]['ctt_voter_no'];
					if (!in_array($voter, $isVoterArray) && $voter != '')
					{
						$searchType .= "(ctt_voter_no = '$voter') OR ";
						array_push($isVoterArray, $voter);
					}
					##########VOTER SEARCH END################################################
					##########AADHAR SEARCH###################################################
					$aadhar = $dataprovider[$key]['ctt_aadhaar_no'];
					if (!in_array($aadhar, $isAadharArray) && $aadhar != '')
					{
						$searchType .= "(ctt_aadhaar_no = '$aadhar') OR ";
						array_push($isAadharArray, $aadhar);
					}
					##########AADHAR SEARCH END################################################
					##########PAN SEARCH#######################################################
					$pan = $dataprovider[$key]['ctt_pan_no'];
					if (!in_array($pan, $isPanArray) && $pan != '')
					{
						$searchType .= "(ctt_pan_no = '$pan') OR ";
						array_push($isPanArray, $pan);
					}
					##########PAN SEARCH END###################################################
				}
				$strparams = call_user_func_array('array_merge', $strparams);
			}
			else
			{
				DBUtil::getLikeStatement($this->search, $bindString, $params);
				if ($this->searchtype == 1)
				{
					$searchType = "(cnte.eml_email_address = '$this->search')";
				}
				else
				{
					$searchType = "(cntp.phn_phone_no LIKE $bindString)";
				}
				$strparams = array_merge($params);
			}

			$searchType	 = rtrim(trim($searchType), 'OR');
			$sql1		 = "SELECT 	group_concat(ctt_id SEPARATOR ',')
						FROM contact
						INNER JOIN (SELECT phn_contact_id,group_concat(distinct phn_phone_no SEPARATOR ',') as phn_phone_no,group_concat(phn_is_verified SEPARATOR ',') as phn_is_verified,group_concat(phn_active SEPARATOR ',') as phn_active FROM contact_phone group by phn_contact_id) as cntp
						ON ctt_id = cntp.phn_contact_id
						LEFT JOIN (SELECT eml_contact_id,group_concat(distinct eml_email_address SEPARATOR ',') as eml_email_address,group_concat(eml_is_verified SEPARATOR ',') as eml_is_verified,group_concat(eml_active SEPARATOR ',') as eml_active FROM contact_email group by eml_contact_id) as cnte
						ON ctt_id = cnte.eml_contact_id
                        LEFT JOIN contact_profile on ctt_id = cr_contact_id
                        LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1
                        LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1
				        LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1
				        LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1
						where	ctt_active = 1 AND $searchType";

			$cttIds = DBUtil::queryScalar($sql1, DBUtil::SDB(), $strparams);

			DBUtil::getINStatement($cttIds, $newbindString, $strparams1);
			$newbindString	 = ($cttIds != 0) ? $newbindString : "''";
			$sql			 .= " AND cnt.ctt_id IN ($newbindString)";
		}

		$sql .= " group by cnt.ctt_id";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $strparams1);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'params'		 => $strparams1,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['ctt_contact_person'],
				'defaultOrder'	 => 'ctt_id DESC'
			],
			'pagination'	 => false,
		]);

		return $dataprovider;
	}

	/**
	 * Search for contacts
	 * @return mixed
	 */
	public function searchList()
	{
		$arrContactIds	 = [];
		$data			 = $this->search;
		$type			 = $this->searchtype;

		$sql = "SELECT distinct c1.ctt_id, c1.ctt_voter_no, c1.ctt_aadhaar_no, c1.ctt_pan_no, c1.ctt_license_no, eml_email_address, phn_phone_no
				FROM contact c1
				INNER JOIN contact c2 ON c2.ctt_ref_code = c1.ctt_id
                INNER JOIN contact_profile cpr ON c2.ctt_id = cpr.cr_contact_id AND cpr.cr_status = 1
                LEFT JOIN vendors v ON v.vnd_id = cpr.cr_is_vendor AND v.vnd_id = v.vnd_ref_code
                LEFT JOIN vendors v1 ON v1.vnd_id = v.vnd_ref_code
				LEFT JOIN contact_email ON c1.ctt_id = eml_contact_id AND eml_active = 1
				LEFT JOIN contact_phone ON c1.ctt_id = phn_contact_id AND phn_active = 1
				WHERE c1.ctt_active IN (1,3,4) AND c2.ctt_active IN (1,3,4)
                               ";

		if ($type == 1)
		{
			$sql .= " AND eml_email_address = '{$data}' ";
		}
		else if ($type == 2)
		{
			$sql .= " AND phn_phone_no = '{$data}' ";
		}
		$sql .= " GROUP BY v1.vnd_ref_code";

		$result = DBUtil::query($sql, DBUtil::SDB());

		if ($result)
		{
			foreach ($result as $row)
			{
				$cttId		 = $row['ctt_id'];
				$voterNo	 = trim($row['ctt_voter_no']);
				$aadhaarNo	 = trim($row['ctt_aadhaar_no']);
				$panNo		 = trim($row['ctt_pan_no']);
				$licenseNo	 = trim($row['ctt_license_no']);
				$email		 = trim($row['eml_email_address']);
				$phoneNo	 = trim($row['phn_phone_no']);

				$arrContactIds[] = $cttId;

				// Voter
				$res = self::getIdsBySearchType($voterNo, 'voter');
				if ($res)
				{
					$arrContactIds = array_merge($arrContactIds, explode(',', $res));
				}

				// Aadhaar
				$res = self::getIdsBySearchType($aadhaarNo, 'aadhaar');
				if ($res)
				{
					$arrContactIds = array_merge($arrContactIds, explode(',', $res));
				}

				// PAN
				$res = self::getIdsBySearchType($panNo, 'pan');
				if ($res)
				{
					$arrContactIds = array_merge($arrContactIds, explode(',', $res));
				}

				// License
				$res = self::getIdsBySearchType($licenseNo, 'license');
				if ($res)
				{
					$arrContactIds = array_merge($arrContactIds, explode(',', $res));
				}

				// Email
				if ($type != 1)
				{
					$res = self::getIdsBySearchType($email, 'email');
					if ($res)
					{
						$arrContactIds = array_merge($arrContactIds, explode(',', $res));
					}
				}

				// Phone
				if ($type != 2)
				{
					$res = self::getIdsBySearchType($phoneNo, 'phone');
					if ($res)
					{
						$arrContactIds = array_merge($arrContactIds, explode(',', $res));
					}
				}
			}

			if (count($arrContactIds) > 0)
			{
				$contactIds = implode(",", array_unique($arrContactIds));

				/* $sql = "SELECT cnt.ctt_id, cnt.ctt_voter_no, cnt.ctt_aadhaar_no, cnt.ctt_pan_no, cnt.ctt_license_no,
				  cntp.phn_phone_no, cntp.phn_active, cntp.phn_is_verified,
				  cnte.eml_email_address, cnte.eml_is_verified, cnte.eml_active,
				  cntf.cr_is_vendor as vendor_id,	cntf.cr_is_driver as driver_id,	cntf.cr_is_consumer as consumer_id
				  FROM `contact` cnt
				  LEFT JOIN (SELECT phn_contact_id, group_concat(distinct phn_phone_no SEPARATOR ',') as phn_phone_no, group_concat(phn_is_verified SEPARATOR ',') as phn_is_verified, group_concat(phn_active SEPARATOR ',') as phn_active FROM contact_phone WHERE phn_active = 1 group by phn_contact_id) AS cntp
				  ON cnt.ctt_id = cntp.phn_contact_id
				  LEFT JOIN (SELECT eml_contact_id, group_concat(distinct eml_email_address SEPARATOR ',') as eml_email_address,group_concat(eml_is_verified SEPARATOR ',') as eml_is_verified,group_concat(eml_active SEPARATOR ',') as eml_active FROM contact_email WHERE eml_active = 1 group by eml_contact_id) AS cnte
				  ON	cnt.ctt_id = cnte.eml_contact_id
				  LEFT JOIN contact_profile as cntf ON cnt.ctt_id = cntf.cr_contact_id AND cntf.cr_status = 1
				  WHERE cnt.ctt_active = 1 AND cnt.ctt_id IN ({$contactIds})
				  GROUP BY cnt.ctt_id"; */

				$sql = "SELECT cnt.ctt_id, cnt.ctt_voter_no, cnt.ctt_aadhaar_no, cnt.ctt_pan_no, cnt.ctt_license_no,
						GROUP_CONCAT(DISTINCT CONCAT(cntp.phn_phone_no, '|', cntp.phn_active, '|', cntp.phn_is_verified)) phn_phone_no,
						GROUP_CONCAT(DISTINCT CONCAT(cnte.eml_email_address, '|', cnte.eml_is_verified, '|', cnte.eml_active)) eml_email_address,
						GROUP_CONCAT(DISTINCT cntf.cr_is_vendor) as vendor_id,
						GROUP_CONCAT(DISTINCT cntf.cr_is_driver) as driver_id,
						GROUP_CONCAT(DISTINCT cntf.cr_is_consumer) as consumer_id,
						GROUP_CONCAT(DISTINCT if(vnd.vnd_id = vnd.vnd_ref_code, 1, 0)) is_vnd_active,
						GROUP_CONCAT(DISTINCT if(drv.drv_id = drv.drv_ref_code, 1, 0)) is_drv_active
						FROM `contact` cnt
						LEFT JOIN contact_email cnte ON cnt.ctt_id = cnte.eml_contact_id AND cnte.eml_active = 1
						LEFT JOIN contact_phone cntp ON cnt.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
						LEFT JOIN contact_profile as cntf ON cnt.ctt_id = cntf.cr_contact_id AND cntf.cr_status = 1
						LEFT JOIN vendors vnd ON vnd.vnd_id = cntf.cr_is_vendor
                        LEFT JOIN vendors v1 ON v1.vnd_ref_code = vnd.vnd_id
						LEFT JOIN drivers drv ON drv.drv_id = cntf.cr_is_driver
						WHERE cnt.ctt_active = 1 AND cnt.ctt_id IN ({$contactIds})AND cnt.ctt_id=cnt.ctt_ref_code 
						GROUP BY cnt.ctt_id,v1.vnd_ref_code";

				$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
				$dataprovider	 = new CSqlDataProvider($sql, [
					'totalItemCount' => $count,
					'db'			 => DBUtil::SDB(),
					'sort'			 => [
						'defaultOrder' => 'ctt_id DESC'
					],
					'pagination'	 => false,
				]);
			}
		}

		return $dataprovider;
	}

	/**
	 * Get contact by search type & value
	 * @param string $data
	 * @param string $type
	 * @return boolean | integer
	 */
	public static function getIdsBySearchType($data, $type)
	{
		if (trim($data) == '' || trim($type) == '')
		{
			return false;
		}

		$sql = "SELECT GROUP_CONCAT(c1.ctt_id)
				FROM contact c1
				INNER JOIN contact c2 ON c2.ctt_ref_code = c1.ctt_id";

		$sqlWhere = " WHERE c1.ctt_active IN (1,3,4) AND c2.ctt_active IN (1,3,4) ";

		switch ($type)
		{
			case 'email':
				$sql		 .= " LEFT JOIN contact_email ON c1.ctt_id = eml_contact_id AND eml_active = 1 ";
				$sqlWhere	 .= " AND eml_email_address = '{$data}' ";
				break;
			case 'phone':
				$sql		 .= " LEFT JOIN contact_phone ON c1.ctt_id = phn_contact_id AND phn_active = 1 ";
				$sqlWhere	 .= " AND phn_phone_no = '{$data}' ";
				break;
			case 'voter':
				$sqlWhere	 .= " AND c1.ctt_voter_no = '{$data}' ";
				break;
			case 'aadhaar':
				$sqlWhere	 .= " AND c1.ctt_aadhaar_no = '{$data}' ";
				break;
			case 'pan':
				$sqlWhere	 .= " AND c1.ctt_pan_no = '{$data}' ";
				break;
			case 'license':
				$sqlWhere	 .= " AND c1.ctt_license_no = '{$data}' ";
				break;
		}

		$sql .= $sqlWhere;

		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public function getContactJSON($usrtype)
	{
		$contactList = $this->getContactList($usrtype);
		$JSONList	 = [];
		foreach ($contactList as $key => $val)
		{
			$JSONList[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($JSONList);
		return $data;
	}

	public function getContactList($utype)
	{
		$sql		 = "SELECT ctt_id,ctt_owner_id,ctt_first_name,ctt_last_name,ctt_pan_no,eml_id,eml_email_address FROM contact INNER JOIN contact_email ON ctt_id = eml_contact_id WHERE ctt_user_type = '" . $utype . "' AND eml_is_primary = 1 AND eml_active = 1";
		$contactAll	 = DBUtil::queryAll($sql);
		$arrContacts = array();
		foreach ($contactAll as $val)
		{
			$arrContacts[$val['ctt_id']] = $val['eml_email_address'];
		}
		return $arrContacts;
	}

	public function getContactDetails($contid)
	{
		$sql		 = "SELECT ctt_id,ctt_owner_id,ctt_user_type,ctt_business_name,ctt_state,ctt_city,ctt_address,
			ctt_first_name, ctt_last_name,ctt_license_no,ctt_voter_no,ctt_aadhaar_no,ctt_pan_no,ctt_license_exp_date,
			ctt_license_doc_id,
			eml_email_address,phn_phone_country_code,phn_phone_no,ctt_preferred_language,
			cty_name,stt_name,ctt_bank_account_no,ctt_bank_name,ctt_bank_account_no,ctt_bank_ifsc,ctt_beneficiary_name,
			ctt_beneficiary_id,ctt_bank_branch
			 FROM contact
			LEFT JOIN contact_email ON ctt_id = eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
			LEFT JOIN contact_phone ON ctt_id = phn_contact_id  AND phn_is_primary = 1 	AND phn_active =1
			LEFT JOIN cities ON ctt_city = cty_id 
			LEFT JOIN states ON ctt_state = stt_id
			WHERE ctt_id = '" . $contid . "' ";
		return $contactAll	 = DBUtil::queryRow($sql);
	}

	public function getNameById($vndId)
	{
		$vndId		 = ($vndId != "" && $vndId != null) ? $vndId : 0;
		$param		 = array("vndId" => $vndId);
		$arrTotal	 = $this->findBySql("Select
		ctt.ctt_user_type,
		ctt.ctt_name as ownername
		from vendors
		INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vendors.vnd_id AND cpr.cr_status = 1
		JOIN `contact` as ctt ON ctt.ctt_id = cpr.cr_contact_id
		where  vendors.vnd_id in (select v3.vnd_id
		FROM
		vendors v1
		INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
		INNER JOIN vendors v3 ON  v3.vnd_id = v2.vnd_ref_code
		WHERE
		v1.vnd_id = :vndId) and vnd_id = vnd_ref_code", $param);
		return $arrTotal;
	}

	public function getName()
	{
		$name = '';
		if ($this->ctt_user_type == 1)
		{
			$name = $this->ctt_first_name . ' ' . $this->ctt_last_name;
		}
		else if ($this->ctt_user_type == 2)
		{
			$name = $this->ctt_business_name;
		}
		return $name;
	}

	public function getRelatedContact($conatctId, $arr, $active)
	{
		$where	 = "";
		$where0	 = "";
		if ($arr['name'])
		{
			$name	 = $arr['name'];
			$where	 = " AND  ( (t.ctt_business_name LIKE '%" . ($name) . "%') OR (t.ctt_first_name LIKE '%" . ($name) . "%') OR (t.ctt_last_name LIKE '%" . ($name) . "%'))";
			$where0	 = " AND ( (cntt.ctt_business_name LIKE '%" . ($name) . "%') OR (cntt.ctt_first_name LIKE '%" . ($name) . "%') OR (cntt.ctt_last_name LIKE '%" . ($name) . "%'))";
		}
		if ($arr['email_address'])
		{
			$cntemail	 = $arr['email_address'];
			$where		 = "  AND (cnte.eml_email_address LIKE '%" . ($cntemail) . "%')";
		}
		if ($arr['phone_no'])
		{
			$cntph	 = $arr['phone_no'];
			$where	 = "  AND (cntp.phn_phone_no LIKE '%" . ($cntph) . "%')";
		}

		$sql			 = " SELECT t.ctt_created_date,t.ctt_first_name,t.ctt_last_name, t.contactperson,	t.ctt_user_type,t.ctt_active,t.ctt_business_name,t.ctt_id, cntp.phn_phone_no, cnte.eml_email_address, rank from
                        (
						SELECT (
						         CASE cnt0.ctt_user_type
						             WHEN 1 THEN concat(cnt0.ctt_first_name, ' ' , cnt0.ctt_last_name)
								    WHEN 2 THEN cnt0.ctt_business_name
						         END
						        ) as contactperson,
								cnt0.ctt_created_date,cnt0.ctt_user_type,cnt0.ctt_first_name,cnt0.ctt_last_name, cnt0.ctt_active,cnt0.ctt_business_name,cnt0.ctt_id, 1 as rank  from contact cnt0 where ctt_id IN (
	                    SELECT distinct cntt.ctt_id FROM contact c1 INNER JOIN contact cntt ON (cntt.ctt_first_name=c1.ctt_first_name or cntt.ctt_last_name=c1.ctt_last_name or cntt.ctt_business_name=c1.ctt_business_name)
                        WHERE c1.ctt_id = $conatctId $where0 )

                        UNION

                        SELECT (
						           CASE cnt1.ctt_user_type
								        WHEN 1 THEN concat(cnt1.ctt_first_name, ' ' , cnt1.ctt_last_name)
										WHEN 2 THEN cnt1.ctt_business_name
						           END
						        ) as contactperson,
								cnt1.ctt_created_date,cnt1.ctt_user_type,cnt1.ctt_first_name,cnt1.ctt_last_name, cnt1.ctt_active,cnt1.ctt_business_name,cnt1.ctt_id, 2 as rank from contact cnt1 where ctt_id NOT IN
                       (
					    SELECT distinct cntt.ctt_id FROM contact c INNER JOIN contact cntt  ON (cntt.ctt_first_name=c.ctt_first_name or cntt.ctt_last_name=c.ctt_last_name or cntt.ctt_business_name=c.ctt_business_name)
                   	    WHERE c.ctt_id = $conatctId)) t
                LEFT JOIN contact_phone as cntp ON t.ctt_id=cntp.phn_contact_id
				LEFT JOIN contact_email as cnte ON t.ctt_id = cnte.eml_contact_id
                where t.ctt_active = $active AND cnte.eml_is_primary = 1 AND cntp.phn_is_primary = 1 and cntp.phn_active = 1  and cnte.eml_active = 1 and  t.ctt_id<> $conatctId $where";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['contactperson'],
				'defaultOrder'	 => 'rank ASC,ctt_created_date DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getAllContactByIds($cttIdArr)
	{
		$sql		 = "Select  (
			            CASE contact.ctt_user_type
						 WHEN 1 THEN concat(contact.ctt_first_name, ' ' , contact.ctt_last_name)
						 WHEN 2 THEN contact.ctt_business_name
						END
						) as contactperson,contact.ctt_bank_name,contact.ctt_bank_branch,contact.ctt_bank_account_no,contact.ctt_bank_ifsc,contact.ctt_beneficiary_name,contact.ctt_beneficiary_id,contact.ctt_account_type,contact.ctt_account_type, contact.ctt_business_type, contact.ctt_first_name,contact.ctt_last_name,contact.ctt_license_exp_date,contact.ctt_license_issue_date,
				contact.ctt_dl_issue_authority,contact.ctt_id, contact.ctt_voter_no,contact.ctt_license_no, contact.ctt_aadhaar_no, contact.ctt_pan_no, contact.ctt_business_name,
				contact.ctt_state, contact.ctt_city, contact.ctt_is_verified, contact.ctt_profile_path, contact.ctt_user_type, contact.ctt_voter_doc_id, contact.ctt_license_doc_id, contact.ctt_aadhar_doc_id, contact.ctt_pan_doc_id, contact.ctt_memo_doc_id,
				cntp.phn_phone_no, cnte.eml_email_address, contact.ctt_address, cntp.phn_is_verified,
				cnte.eml_is_verified, docvoter.doc_id as doc_id2, docaadhar.doc_id as doc_id3, docpan.doc_id as doc_id4, doclicence.doc_id as doc_id5,
		docmemo.doc_id as doc_id6, docvoter.doc_type as doc_type2, docaadhar.doc_type as doc_type3,
		docpan.doc_type as doc_type4, doclicence.doc_type as doc_type5, docmemo.doc_type as doc_type6,
		docvoter.doc_status as doc_status2, docaadhar.doc_status as doc_status3, docpan.doc_status as doc_status4,
		doclicence.doc_status as doc_status5, docmemo.doc_status as doc_status6,
		docvoter.doc_file_front_path as doc_file_front_path2, docvoter.doc_file_back_path as doc_file_back_path2,
		docaadhar.doc_file_front_path as doc_file_front_path3, docaadhar.doc_file_back_path as doc_file_back_path3,
		docpan.doc_file_front_path as doc_file_front_path4, docpan.doc_file_back_path as doc_file_back_path4,
		doclicence.doc_file_front_path as doc_file_front_path5, doclicence.doc_file_back_path as doc_file_back_path5,
		docmemo.doc_file_front_path as doc_file_front_path6, docvoter.doc_remarks as doc_remarks2, docaadhar.doc_remarks as doc_remarks3,
		docpan.doc_remarks as doc_remarks4, doclicence.doc_remarks as doc_remarks5, docmemo.doc_remarks as doc_remarks6
		from contact
		LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND  docvoter.doc_type = 2 AND docvoter.doc_active = 1
		LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND  docaadhar.doc_type = 3 AND docaadhar.doc_active = 1
		LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND  docpan.doc_type = 4 AND docpan.doc_active = 1
		LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND  doclicence.doc_type = 5 AND doclicence.doc_active = 1
		LEFT JOIN document as docmemo ON contact.ctt_memo_doc_id = docmemo.doc_id AND  docmemo.doc_type = 6 AND docmemo.doc_active = 1
        LEFT JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id   and cntp.phn_active = 1  AND  cntp.phn_is_primary = 1
		LEFT JOIN contact_email as cnte ON contact.ctt_id = cnte.eml_contact_id AND cnte.eml_is_primary = 1 AND  cnte.eml_active = 1
		where contact.ctt_id  IN ($cttIdArr) and ctt_active = 1";
		$contactAll	 = DBUtil::queryAll($sql);
		return $contactAll;
	}

	/**
	 * @return array()|false Return linked Vendor ID or false if not found
	 */
	public function getVendorByEmailPhone($email, $phone)
	{
		return Vendors::getByEmailPhone($email, $phone);
	}

	/** @deprecated since 2021-09-14
	 * Use Vendors::getIdByEmailPhone
	 *  */
	public function linkContactId($email, $phone)
	{
		$result = "	SELECT DISTINCT vnd_id
					FROM vendors
					INNER JOIN contact ON vnd_contact_id = ctt_id
					LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id
					LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id
					WHERE     contact_phone.phn_phone_no = '$phone'
						  AND contact_email.eml_email_address = '$email'
						  AND vnd_active NOT IN (0, 2)
						  AND contact_phone.phn_is_verified = 1
						  AND contact_phone.phn_active = 1
						  AND contact_email.eml_is_verified = 1
						  AND contact_email.eml_active = 1
					";

		$sql				 = DBUtil::command($result)->queryAll($fetchAssociative	 = true);
		return $sql;
	}

	public function updateContactMerge($contactId, $arr, $newContactPh, $newContactEmail)
	{
		$curPhNo	 = $newContactPh[0]->phn_phone_no;
		$curEmail	 = $newContactEmail[0]->eml_email_address;

		$sql = "Update `contact` set ctt_active=0 WHERE ctt_id  in ($arr)";
		$cnt = DBUtil::command($sql)->execute();

		//$sql = "Update `vendors` set vnd_contact_id=$contactId WHERE vnd_contact_id  in ($arr)";
		//$cnt = DBUtil::command($sql)->execute();
		$sql		 = "SELECT `vnd_id` FROM `vendors` WHERE `vnd_contact_id` = '$contactId'";
		$cntVendor	 = DBUtil::command($sql)->execute();

		if ($cntVendor)
		{
			$sql = "Update `vendors` set `vnd_ref_code` =(SELECT `vnd_id` FROM `vendors` WHERE `vnd_contact_id` = '$contactId'),vnd_merge_on = now() WHERE vnd_contact_id = $arr";
			$cnt = DBUtil::command($sql)->execute();

			$sql = "update `vendors` set vnd_merge_on = now() WHERE vnd_contact_id = $contactId";
			$cnt = DBUtil::command($sql)->execute();
		}

		$sql		 = "SELECT `drv_id` FROM `drivers` WHERE `drv_contact_id` = '$contactId'";
		$cntDriver	 = DBUtil::command($sql)->execute();

		if ($cntDriver)
		{
			$sql = "Update `drivers` set `drv_ref_code` =(SELECT `drv_id` FROM `drivers` WHERE `drv_contact_id` = '$contactId'),drv_merge_on = now() WHERE drv_contact_id = $arr";
			$cnt = DBUtil::command($sql)->execute();

			$sql = "update `drivers` set drv_merge_on = now() WHERE drv_contact_id = $contactId";
			$cnt = DBUtil::command($sql)->execute();
		}

		$sql = "Update `contact_email` set eml_contact_id=$contactId,eml_is_primary=0 WHERE eml_contact_id  in ($arr, $contactId)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "Update `contact_phone` set phn_contact_id=$contactId,phn_is_primary=0 WHERE phn_contact_id  in ($arr, $contactId)";
		$cnt = DBUtil::command($sql)->execute();

		/**		 * ******* Remove duplicate phone by setting phn_active=0******** */
		$sql = "UPDATE contact_email SET eml_active =0 WHERE eml_is_primary = 1 AND  eml_email_address in
				  (SELECT (eml_email_address)   FROM contact_email where eml_contact_id=$contactId
				  GROUP BY eml_email_address    HAVING (COUNT(eml_email_address ) > 1) order by eml_contact_id desc)";
		$cnt = DBUtil::command($sql)->execute();

		/**		 * ******* Remove duplicate phone by setting phn_active=0******** */
		$sql = "UPDATE contact_phone SET phn_active=0 WHERE phn_is_primary = 1 AND phn_phone_no in
		( SELECT (phn_phone_no) FROM contact_phone  where phn_contact_id=$contactId
		GROUP BY phn_phone_no  HAVING (COUNT(phn_phone_no ) > 1) order by phn_contact_id desc)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "Update `contact_phone` set phn_is_primary=1,phn_active=1 WHERE phn_phone_no = $curPhNo AND phn_contact_id = $contactId limit 1";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "Update `contact_email` set eml_is_primary=1,eml_active=1 WHERE eml_email_address = '$curEmail' AND eml_contact_id = $contactId limit 1";
		$cnt = DBUtil::command($sql)->execute();

		//  $sql = "UPDATE app_tokens SET apt_status = 0,apt_logout = now()  WHERE apt_user_type = 5 AND apt_status = 1 AND app_tokens.apt_entity_id=(select drv_id from drivers WHERE drv_contact_id = $arr)";
		//   DBUtil::command($sql)->execute();
		$sql = "Update contact set ctt_ref_code = '$contactId' WHERE ctt_id = $arr";
		DBUtil::command($sql)->execute();
	}

	public function getDuplicateContactV1($arr, $ctt_id, $type = "contact", $vnd_id = "")
	{
		$where			 = "";
		$where1			 = "";
		$where2			 = "";
		$where3			 = "";
		$where4			 = "";
		$where5			 = "";
		$where6			 = "";
		$where8			 = "";
		$sql			 = "";
		$sqlCount		 = "";
		$vendors		 = "";
		$vendors1		 = "";
		//$vendors2		 = "";
		$groupby		 = "GROUP BY ctt_id";
		$select			 = "";
		$selectCount	 = "";
		$vendorsCount	 = "";
		$join			 = "";
		$joinEmail		 = "";
		$joinPhone		 = "";
		$mapEntity		 = "";
		if ($type == "vendors")
		{
			$select		 .= "SELECT  ctt_id,vnd_id,vnd_name,vnd_code, eml_contact_id, 
					eml_email_address, phn_contact_id, phn_phone_no, ctt_voter_no, ctt_aadhaar_no, ctt_pan_no, 
					vrs_vnd_overall_rating, vrs_vnd_total_trip, vrs_last_trip_datetime, vnd_active vnd,	ctt_license_no";
			$selectCount .= "SELECT  ctt_id,vnd_id,vnd_active vnd ";
			$mapEntity	 .= "LEFT JOIN vendor_stats as vrs 
 						ON vendors.vnd_id = vrs.vrs_vnd_id";

			$vendors .= "INNER JOIN contact_profile cpp 
						ON vendors.vnd_id = cpp.cr_is_vendor AND cr_status = 1
					INNER JOIN contact  ON contact.ctt_id = cpp.cr_contact_id 
						AND ctt_active = 1  $mapEntity";

			$vendorsCount .= " INNER JOIN contact_profile cpp 
				ON vendors.vnd_id = cpp.cr_is_vendor AND cr_status = 1
					INNER JOIN contact  ON contact.ctt_id = cpp.cr_contact_id AND ctt_active = 1 
						AND vendors.vnd_active =1 
						AND vendors.vnd_is_merged =0 
						AND vendors.vnd_merged_to=0";

			$join .= "  INNER JOIN contact_profile cpp 
				ON contact.ctt_id = cpp.cr_contact_id AND ctt_active = 1  
				INNER JOIN vendors  
				ON vendors.vnd_id = cpp.cr_is_vendor AND cr_status = 1 
						AND vendors.vnd_active =1 
						AND vendors.vnd_is_merged =0 
						AND vendors.vnd_merged_to=0";

			$joinEmail	 .= "  INNER JOIN contact 
				ON contact.ctt_id = contact_email.eml_contact_id AND ctt_active = 1 
			INNER JOIN contact_profile cpp 
				ON contact.ctt_id = cpp.cr_contact_id AND ctt_active = 1 
			INNER JOIN vendors  
				ON vendors.vnd_id = cpp.cr_is_vendor AND cr_status = 1 
						AND vendors.vnd_active =1 
						AND vendors.vnd_is_merged =0 
						AND vendors.vnd_merged_to=0 ";
			$joinPhone	 .= " INNER JOIN contact 
				ON contact.ctt_id = contact_phone.phn_contact_id AND ctt_active = 1 
			INNER JOIN contact_profile cpp 
				ON contact.ctt_id = cpp.cr_contact_id AND ctt_active = 1 
			INNER JOIN vendors  
				ON vendors.vnd_id = cpp.cr_is_vendor AND cr_status = 1 
						AND vendors.vnd_active =1 
						AND vendors.vnd_is_merged =0 
						AND vendors.vnd_merged_to=0  ";
			if ($ctt_id != NULL)
			{
				$vendors1	 .= "OR cr_contact_id IN
					(SELECT cr_contact_id
					FROM contact_profile cpp  
				 	INNER JOIN vendors  
				 		ON vendors.vnd_ref_code = cpp.cr_is_vendor 				 
					WHERE vendors.vnd_active = 1 and  vnd_is_merged =0 and vnd_merged_to=0 
                        AND cpp.cr_contact_id=$ctt_id 
						AND cr_status = 1 
					GROUP BY vendors.vnd_ref_code
					HAVING count(vendors.vnd_id) > 1) ";
				$vendors2	 .= "OR cr_contact_id IN
					(SELECT cr_contact_id
					FROM contact_profile cpp  
				 	INNER JOIN vendors  
				 		ON vendors.vnd_ref_code = cpp.cr_is_vendor 				 
					WHERE vendors.vnd_active = 1 and  vnd_is_merged =0 and vnd_merged_to=0 
                        AND cpp.cr_contact_id=$ctt_id 
						AND cr_status = 1 
					GROUP BY vendors.vnd_ref_code
					HAVING count(vendors.vnd_id) > 0) ";
			}
			else
			{
				$vendors1	 .= " OR cr_contact_id IN
					(SELECT cr_contact_id
					FROM contact_profile cpp  
				 	INNER JOIN vendors  
				 		ON vendors.vnd_ref_code = cpp.cr_is_vendor 				 
					WHERE 1
                    	AND vendors.vnd_active = 1 and vnd_is_merged =0 and vnd_merged_to=0 
						AND cr_status = 1 
					GROUP BY vendors.vnd_ref_code
					HAVING count(vendors.vnd_id) > 1)  ";
				$vendors2	 .= " OR cr_contact_id IN
					(SELECT cr_contact_id
					FROM contact_profile cpp  
				 	INNER JOIN vendors  
				 		ON vendors.vnd_ref_code = cpp.cr_is_vendor 				 
					WHERE 1
                    	AND vendors.vnd_active = 1 and vnd_is_merged =0 and vnd_merged_to=0 
						AND cr_status = 1 
					GROUP BY vendors.vnd_ref_code
					HAVING count(vendors.vnd_id) > 0)  ";
			}
			$groupby = "GROUP BY ctt_id ,vnd_id HAVING vnd =1 ";
		}
		else if ($type == "drivers")
		{
			$selectCount	 .= "SELECT  ctt_id,drv_active drv ";
			$select			 .= "SELECT ctt_id,drv_id,drv_name,drv_code,drv_contact_id, eml_contact_id,
					eml_email_address, phn_contact_id, phn_phone_no, ctt_voter_no, ctt_aadhaar_no,
                    (select  drs_drv_overall_rating from driver_stats where  driver_stats.drs_drv_id=drv_id order by drs_id  limit 0,1)  as drs_drv_overall_rating,
					(select   drs_total_trips from driver_stats where  driver_stats.drs_drv_id=drv_id order by drs_id  limit 0,1)  as drs_total_trips,
					(select   drs_last_trip_date from driver_stats where  driver_stats.drs_drv_id=drv_id order by drs_id  limit 0,1)  as drs_last_trip_date,
                    drv_active drv,
					ctt_pan_no,
					ctt_license_no,ctt_name,ctt_business_name";
			$vendors		 .= "INNER JOIN contact  ON contact.ctt_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0 ";
			$vendorsCount	 .= " INNER JOIN contact  ON contact.ctt_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0";
			$join			 .= " INNER JOIN drivers  ON contact.ctt_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0  ";
			$joinEmail		 .= "INNER JOIN drivers  ON contact_email.eml_contact_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0 ";
			$joinPhone		 .= "INNER JOIN drivers  ON contact_phone.phn_contact_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0 ";
			if ($ctt_id != NULL)
			{
				$vendors1	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0 and drv_contact_id=$ctt_id
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 1)  ";
				$vendors2	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0 and drv_contact_id=$ctt_id
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 0)  ";
			}
			else
			{
				$vendors1	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 1)  ";
				$vendors2	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 0)  ";
			}
			$groupby = "GROUP BY ctt_id,drv_id HAVING drv = 1 ";
		}
		else
		{
			$selectCount .= "SELECT  ctt_id ";
			$select		 .= "SELECT  ctt_id,
					eml_contact_id,
					eml_email_address,
					phn_contact_id,
					phn_phone_no,
					CONCAT(ctt_first_name, ' ' ,ctt_last_name) as contactperson,
					ctt_voter_no,
					ctt_aadhaar_no,
					ctt_pan_no,
					ctt_license_no";
			$groupby	 = "GROUP BY ctt_id  ";
		}

		if ($arr['ctt_voter_no'])
		{
			$where .= "  ctt_voter_no IN
            (SELECT contact.ctt_voter_no
             FROM contact $join
             WHERE ctt_voter_no <> '' AND ctt_voter_no IS NOT NULL 
				AND ctt_active = 1 and (ctt_voter_no LIKE '%" . ($arr['ctt_voter_no']) . "%')
             GROUP BY ctt_voter_no
             HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['ctt_id'])
		{
			$where8 .= "ctt_id IN
            (SELECT contact.ctt_id
             FROM contact $join
             WHERE ctt_id <> '' AND ctt_id IS NOT NULL AND ctt_active = 1 
				and (ctt_id = '" . ($arr['ctt_id']) . "')
             HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['ctt_aadhaar_no'])
		{
			$where1 .= "  ctt_aadhaar_no IN
            (SELECT contact.ctt_aadhaar_no
             FROM contact $join
             WHERE ctt_aadhaar_no <> '' AND ctt_aadhaar_no IS NOT NULL AND ctt_active = 1 and (ctt_aadhaar_no LIKE '%" . ($arr['ctt_aadhaar_no']) . "%')
             GROUP BY ctt_aadhaar_no
             HAVING COUNT(DISTINCT ctt_id) > 1)";
		}
		if ($arr['ctt_pan_no'])
		{
			$where2 .= "  ctt_pan_no IN (SELECT contact.ctt_pan_no
				FROM contact $join
				WHERE ctt_pan_no <> '' AND ctt_pan_no IS NOT NULL AND ctt_active = 1 and (ctt_pan_no LIKE '%" . ($arr['ctt_pan_no']) . "%')
				GROUP BY ctt_pan_no
				HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['ctt_license_no'])
		{
			$where3 .= " ctt_license_no IN (SELECT contact.ctt_license_no
                        FROM contact $join
                        WHERE ctt_license_no <> '' AND ctt_license_no IS NOT NULL AND ctt_active = 1 and (ctt_license_no LIKE '%" . ($arr['ctt_license_no']) . "%')
                        GROUP BY ctt_license_no
                        HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['email_address'])
		{
			$where4 .= " eml_email_address IN
            (SELECT contact_email.eml_email_address
             FROM contact_email  $joinEmail
             WHERE eml_email_address <> '' AND eml_email_address IS NOT NULL AND eml_active = 1 and  (eml_email_address LIKE '%" . ($arr['email_address']) . "%')
             GROUP BY eml_email_address
             HAVING count(DISTINCT eml_contact_id) > 1) ";
		}
		if ($arr['phone_no'])
		{
			$where5 .= " phn_phone_no IN
            (SELECT contact_phone.phn_phone_no
             FROM contact_phone $joinPhone
             WHERE phn_phone_no <> '' AND phn_phone_no IS NOT NULL AND phn_active = 1 and (phn_phone_no LIKE '%" . ($arr['phone_no']) . "%')
             GROUP BY phn_phone_no
             HAVING count(DISTINCT phn_contact_id) > 1)";
		}
		if ($arr['contactperson'])
		{
			$where6 .= "ctt_first_name IN
			(SELECT contact.ctt_first_name
			 FROM contact
			 WHERE ctt_first_name <> '' AND ctt_first_name IS NOT NULL 
				AND ctt_active = 1 and (ctt_first_name LIKE '%" . ($arr['ctt_first_name']) . "%')
			 GROUP BY ctt_first_name
			 HAVING COUNT(DISTINCT ctt_id) > 1)
		AND ctt_last_name IN
			 (SELECT contact.ctt_last_name
			 FROM contact
			 WHERE ctt_last_name <> '' AND ctt_last_name IS NOT NULL 
				AND ctt_active = 1 and (ctt_last_name LIKE '%" . ($arr['ctt_last_name']) . "%')
			 GROUP BY ctt_last_name
			 HAVING COUNT(DISTINCT ctt_id) > 1)";
		}

		if (count($arr) == 0)
		{
			$sqlCount	 .= "$selectCount	FROM $type
					  $vendorsCount
					 LEFT JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id   
						AND cntp.phn_active = 1
					 LEFT JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   
						AND cnte.eml_active = 1
				     WHERE ctt_active > 0 and (   ctt_voter_no IN
            (SELECT contact.ctt_voter_no
             FROM contact $join
             WHERE ctt_voter_no <> '' AND ctt_voter_no IS NOT NULL 
				AND ctt_active = 1
             GROUP BY ctt_voter_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_aadhaar_no IN
            (SELECT contact.ctt_aadhaar_no
             FROM contact $join
             WHERE ctt_aadhaar_no <> '' AND ctt_aadhaar_no IS NOT NULL 
				AND ctt_active = 1
             GROUP BY ctt_aadhaar_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_license_no IN (SELECT contact.ctt_license_no
			FROM contact  $join
			WHERE ctt_license_no <> '' AND ctt_license_no IS NOT NULL 
				AND ctt_active = 1
			GROUP BY ctt_license_no
			HAVING COUNT(DISTINCT ctt_id) > 1)

      OR ctt_pan_no IN (SELECT contact.ctt_pan_no
			FROM contact  $join
			WHERE ctt_pan_no <> '' AND ctt_pan_no IS NOT NULL AND ctt_active = 1
			GROUP BY ctt_pan_no
			HAVING COUNT(DISTINCT ctt_id) > 1)

      OR eml_email_address IN
            (SELECT contact_email.eml_email_address
             FROM contact_email  $joinEmail
             WHERE eml_email_address <> '' AND eml_email_address IS NOT NULL AND eml_active = 1
             GROUP BY eml_email_address
             HAVING count(DISTINCT eml_contact_id) > 1)
      OR phn_phone_no IN
            (SELECT contact_phone.phn_phone_no
             FROM contact_phone  $joinPhone
             WHERE phn_phone_no <> '' AND phn_phone_no IS NOT NULL AND phn_active = 1
             GROUP BY phn_phone_no
             HAVING count(DISTINCT phn_contact_id) > 1)
             $vendors1 ";
			$sql		 .= "
					$select	FROM $type
					$vendors
					 left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id   AND cntp.phn_active = 1
					 left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
				     WHERE ctt_active > 0 and (   ctt_voter_no IN
            (SELECT contact.ctt_voter_no
             FROM contact $join
             WHERE ctt_voter_no <> '' AND ctt_voter_no IS NOT NULL AND ctt_active = 1
             GROUP BY ctt_voter_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_aadhaar_no IN
            (SELECT contact.ctt_aadhaar_no
             FROM contact $join
             WHERE ctt_aadhaar_no <> '' AND ctt_aadhaar_no IS NOT NULL AND ctt_active = 1
             GROUP BY ctt_aadhaar_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_license_no IN (SELECT contact.ctt_license_no
			FROM contact  $join
			WHERE ctt_license_no <> '' AND ctt_license_no IS NOT NULL AND ctt_active = 1
			GROUP BY ctt_license_no
			HAVING COUNT(DISTINCT ctt_id) > 1)

       OR ctt_pan_no IN (SELECT contact.ctt_pan_no
			FROM contact  $join
			WHERE ctt_pan_no <> '' AND ctt_pan_no IS NOT NULL AND ctt_active = 1
			GROUP BY ctt_pan_no
			HAVING COUNT(DISTINCT ctt_id) > 1)

      OR eml_email_address IN
            (SELECT contact_email.eml_email_address
             FROM contact_email  $joinEmail
             WHERE eml_email_address <> '' AND eml_email_address IS NOT NULL AND eml_active = 1
             GROUP BY eml_email_address
             HAVING count(DISTINCT eml_contact_id) > 1)
      OR phn_phone_no IN
            (SELECT contact_phone.phn_phone_no
             FROM contact_phone  $joinPhone
             WHERE phn_phone_no <> '' AND phn_phone_no IS NOT NULL AND phn_active = 1
             GROUP BY phn_phone_no
             HAVING count(DISTINCT phn_contact_id) > 1)
             $vendors1 ";
		}
		else if ($arr['ctt_voter_no'])
		{
			$sql .= "$select	FROM $type
					 $vendors
					 left JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id    AND cntp.phn_active = 1
					 left JOIN contact_email as cnte ON contact.ctt_id= cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE  ctt_active = 1 and (
					$where  $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					 $vendorsCount
					 left JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id    AND cntp.phn_active = 1
					 left JOIN contact_email as cnte ON contact.ctt_id= cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE  ctt_active = 1 and (
					$where  $vendors1 ";

			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or  $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_aadhaar_no'])
		{
			$sql .= "$select	FROM $type
					$vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where1  $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where1  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_pan_no'])
		{
			$sql .= "$select	FROM $type
					 $vendors
					 left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					 left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE ctt_active = 1 and (
					$where2   $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where2  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_license_no'])
		{
			$sql .= "$select	FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					WHERE  ctt_active = 1 and ( $where3  $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where3  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['email_address'])
		{
			$sql .= "$select FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					WHERE ctt_active = 1 and ( $where4  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where4  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['phone_no'])
		{
			$sql .= "$select	FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					WHERE  ctt_active = 1 and ( $where5  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where5  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
		}
		else if ($arr['contactperson'])
		{
			$sql .= "$select	FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id AND cnte.eml_active = 1
					WHERE  ctt_active = 1  and  ( $where6  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where6  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_id'])
		{
			$sql .= "$select FROM $type
					 $vendors
					 left JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id    AND cntp.phn_active = 1
					 left JOIN contact_email as cnte ON contact.ctt_id= cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE  ctt_active = 1 and (ctt_id = '" . ($arr['ctt_id']) . "') and (
					$where8  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where8  $vendors1 ";

			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or  $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}

		if ($ctt_id != NULL && count($arr) > 0)
		{
			if ($vnd_id != "" && $type == "vendors")
			{
				$sql		 .= " ) and  vnd_id <> $vnd_id and vnd_is_merged =0 and vnd_merged_to=0  $groupby ORDER BY ctt_id ";
				$sqlCount	 .= " ) and  vnd_id <> $vnd_id and vnd_is_merged =0 and vnd_merged_to=0  $groupby  ";
			}
			else if ($vnd_id != "" && $type == "drivers")
			{
				$sql		 .= " ) and  drv_id <> $vnd_id and drv_is_merged =0 and drv_merged_to=0  $groupby ORDER BY ctt_id ";
				$sqlCount	 .= " ) and  drv_id <> $vnd_id and drv_is_merged =0 and drv_merged_to=0  $groupby ";
			}
			else
			{
				$sql		 .= " ) and ctt_id <> $ctt_id  $groupby ORDER BY ctt_id ";
				$sqlCount	 .= " ) and ctt_id <> $ctt_id  $groupby  ";
			}
		}
		else if ($ctt_id != NULL && count($arr) == 0)
		{
			if ($vnd_id != "" && $type == "vendors")
			{
				$sql		 .= " ) and vnd_id <> $vnd_id and  vnd_is_merged=0 and vnd_merged_to=0  $groupby  ";
				$sqlCount	 .= " ) and vnd_id <> $vnd_id and  vnd_is_merged=0 and vnd_merged_to=0  $groupby  ";
			}
			else if ($vnd_id != "" && $type == "drivers")
			{
				$sql		 .= " ) and drv_id <> $vnd_id and  drv_is_merged=0 and drv_merged_to=0  $groupby ";
				$sqlCount	 .= " ) and drv_id <> $vnd_id and  drv_is_merged=0 and drv_merged_to=0  $groupby ";
			}
			else
			{
				$sql		 .= " ) and ctt_id = $ctt_id  $groupby ";
				$sqlCount	 .= " ) and ctt_id = $ctt_id  $groupby ";
			}
		}
		else
		{
			$sql		 .= "  )  $groupby";
			$sqlCount	 .= "  )  $groupby";
		}

		$count			 = DBUtil::command("select count(*)  FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, ['db' => DBUtil::SDB(), 'totalItemCount' => $count, 'sort' => ['defaultOrder' => ''], 'pagination' => ['pageSize' => 100]]);
		return $dataprovider;
	}

	public function getDuplicateContact($arr, $ctt_id, $type = "contact", $vnd_id = "")
	{
		$where			 = "";
		$where1			 = "";
		$where2			 = "";
		$where3			 = "";
		$where4			 = "";
		$where5			 = "";
		$where6			 = "";
		$where8			 = "";
		$sql			 = "";
		$sqlCount		 = "";
		$vendors		 = "";
		$vendors1		 = "";
		$vendors2		 = "";
		$groupby		 = "GROUP BY ctt_id";
		$select			 = "";
		$selectCount	 = "";
		$vendorsCount	 = "";
		$join			 = "";
		$joinEmail		 = "";
		$joinPhone		 = "";
		$mapEntity		 = "";
		if ($type == "vendors")
		{
			$select			 .= "SELECT  ctt_id,vnd_id,vnd_name,vnd_code,vnd_contact_id,
					eml_contact_id,
					eml_email_address,
					phn_contact_id,
					phn_phone_no,
					ctt_voter_no,
					ctt_aadhaar_no,
					ctt_pan_no,
                    vrs_vnd_overall_rating,
                    vrs_vnd_total_trip,
                    vrs_last_trip_datetime,
                    vnd_active vnd,
					ctt_license_no";
			$selectCount	 .= "SELECT  ctt_id,vnd_active vnd ";
			$mapEntity		 .= "LEFT JOIN vendor_stats as vrs ON vendors.vnd_id = vrs.vrs_vnd_id";
			$vendors		 .= "INNER JOIN contact  ON contact.ctt_id = vendors.vnd_contact_id  $mapEntity";
			$vendorsCount	 .= " INNER JOIN contact  ON contact.ctt_id = vendors.vnd_contact_id  AND vendors.vnd_active =1 and vendors.vnd_is_merged =0 AND vendors.vnd_merged_to=0";
			$join			 .= " INNER JOIN vendors  ON contact.ctt_id = vendors.vnd_contact_id  AND vendors.vnd_active =1 and vendors.vnd_is_merged =0 AND vendors.vnd_merged_to=0";
			$joinEmail		 .= " INNER JOIN vendors  ON contact_email.eml_contact_id = vendors.vnd_contact_id  AND vendors.vnd_active =1 and vendors.vnd_is_merged =0 AND vendors.vnd_merged_to=0 ";
			$joinPhone		 .= " INNER JOIN vendors  ON contact_phone.phn_contact_id = vendors.vnd_contact_id  AND vendors.vnd_active =1 and vendors.vnd_is_merged =0 AND vendors.vnd_merged_to=0 ";
			if ($ctt_id != NULL)
			{
				$vendors1	 .= "OR vnd_contact_id IN
					(SELECT vnd_contact_id
					FROM vendors
					WHERE vnd_contact_id <> '' AND vnd_contact_id IS NOT NULL AND vnd_active = 1 and  vnd_is_merged =0 and vnd_merged_to=0 and vnd_contact_id=$ctt_id
					GROUP BY vnd_contact_id
					HAVING count(vnd_contact_id) > 1)  ";
				$vendors2	 .= "OR vnd_contact_id IN
					(SELECT vnd_contact_id
					FROM vendors
					WHERE vnd_contact_id <> '' AND vnd_contact_id IS NOT NULL AND vnd_active = 1 and  vnd_is_merged =0 and vnd_merged_to=0 and vnd_contact_id=$ctt_id
					GROUP BY vnd_contact_id
					HAVING count(vnd_contact_id) > 0)  ";
			}
			else
			{
				$vendors1	 .= "OR vnd_contact_id IN
					(SELECT vnd_contact_id
					FROM vendors
					WHERE vnd_contact_id <> '' AND vnd_contact_id IS NOT NULL AND vnd_active = 1 and  vnd_is_merged =0 and vnd_merged_to=0
					GROUP BY vnd_contact_id
					HAVING count(vnd_contact_id) > 1)  ";
				$vendors2	 .= "OR vnd_contact_id IN
					(SELECT vnd_contact_id
					FROM vendors
					WHERE vnd_contact_id <> '' AND vnd_contact_id IS NOT NULL AND vnd_active = 1 and  vnd_is_merged =0 and vnd_merged_to=0
					GROUP BY vnd_contact_id
					HAVING count(vnd_contact_id) > 0)  ";
			}
			$groupby = "GROUP BY ctt_id ,vnd_id HAVING vnd =1 ";
		}
		else if ($type == "drivers")
		{
			$selectCount	 .= "SELECT  ctt_id,drv_active drv ";
			$select			 .= "SELECT ctt_id,drv_id,drv_name,drv_code,drv_contact_id,
					eml_contact_id,
					eml_email_address,
					phn_contact_id,
					phn_phone_no,
					ctt_voter_no,
					ctt_aadhaar_no,
                    (select  drs_drv_overall_rating from driver_stats where  driver_stats.drs_drv_id=drv_id order by drs_id  limit 0,1)  as drs_drv_overall_rating,
					(select   drs_total_trips from driver_stats where  driver_stats.drs_drv_id=drv_id order by drs_id  limit 0,1)  as drs_total_trips,
					(select   drs_last_trip_date from driver_stats where  driver_stats.drs_drv_id=drv_id order by drs_id  limit 0,1)  as drs_last_trip_date,
                    drv_active drv,
					ctt_pan_no,
					ctt_license_no,ctt_name,ctt_business_name";
			$vendors		 .= "INNER JOIN contact  ON contact.ctt_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0 ";
			$vendorsCount	 .= " INNER JOIN contact  ON contact.ctt_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0";
			$join			 .= " INNER JOIN drivers  ON contact.ctt_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0  ";
			$joinEmail		 .= "INNER JOIN drivers  ON contact_email.eml_contact_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0 ";
			$joinPhone		 .= "INNER JOIN drivers  ON contact_phone.phn_contact_id = drivers.drv_contact_id  AND drivers.drv_active =1 and drivers.drv_is_merged =0 AND drivers.drv_merged_to=0 ";
			if ($ctt_id != NULL)
			{
				$vendors1	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0 and drv_contact_id=$ctt_id
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 1)  ";
				$vendors2	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0 and drv_contact_id=$ctt_id
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 0)  ";
			}
			else
			{
				$vendors1	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 1)  ";
				$vendors2	 .= "OR drv_contact_id IN
					(SELECT drv_contact_id
					FROM drivers
					WHERE drv_contact_id <> '' AND drv_contact_id IS NOT NULL AND drv_active =1 and  drv_is_merged =0 and drv_merged_to=0
					GROUP BY drv_contact_id
					HAVING count(drv_contact_id) > 0)  ";
			}
			$groupby = "GROUP BY ctt_id,drv_id HAVING drv = 1 ";
		}
		else
		{
			$selectCount .= "SELECT  ctt_id ";
			$select		 .= "SELECT  ctt_id,
					eml_contact_id,
					eml_email_address,
					phn_contact_id,
					phn_phone_no,
					CONCAT(ctt_first_name, ' ' ,ctt_last_name) as contactperson,
					ctt_voter_no,
					ctt_aadhaar_no,
					ctt_pan_no,
					ctt_license_no";
			$groupby	 = "GROUP BY ctt_id  ";
		}

		if ($arr['ctt_voter_no'])
		{
			$where .= "  ctt_voter_no IN
            (SELECT contact.ctt_voter_no
             FROM contact $join
             WHERE ctt_voter_no <> '' AND ctt_voter_no IS NOT NULL AND ctt_active = 1 and (ctt_voter_no LIKE '%" . ($arr['ctt_voter_no']) . "%')
             GROUP BY ctt_voter_no
             HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['ctt_id'])
		{
			$where8 .= "ctt_id IN
            (SELECT contact.ctt_id
             FROM contact $join
             WHERE ctt_id <> '' AND ctt_id IS NOT NULL AND ctt_active = 1 and (ctt_id LIKE '%" . ($arr['ctt_id']) . "%')
             HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['ctt_aadhaar_no'])
		{
			$where1 .= "  ctt_aadhaar_no IN
            (SELECT contact.ctt_aadhaar_no
             FROM contact $join
             WHERE ctt_aadhaar_no <> '' AND ctt_aadhaar_no IS NOT NULL AND ctt_active = 1 and (ctt_aadhaar_no LIKE '%" . ($arr['ctt_aadhaar_no']) . "%')
             GROUP BY ctt_aadhaar_no
             HAVING COUNT(DISTINCT ctt_id) > 1)";
		}
		if ($arr['ctt_pan_no'])
		{
			$where2 .= "  ctt_pan_no IN (SELECT contact.ctt_pan_no
                        FROM contact $join
                        WHERE ctt_pan_no <> '' AND ctt_pan_no IS NOT NULL AND ctt_active = 1 and (ctt_pan_no LIKE '%" . ($arr['ctt_pan_no']) . "%')
                        GROUP BY ctt_pan_no
                        HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['ctt_license_no'])
		{
			$where3 .= " ctt_license_no IN (SELECT contact.ctt_license_no
                        FROM contact $join
                        WHERE ctt_license_no <> '' AND ctt_license_no IS NOT NULL AND ctt_active = 1 and (ctt_license_no LIKE '%" . ($arr['ctt_license_no']) . "%')
                        GROUP BY ctt_license_no
                        HAVING COUNT(DISTINCT ctt_id) > 1) ";
		}
		if ($arr['email_address'])
		{
			$where4 .= " eml_email_address IN
            (SELECT contact_email.eml_email_address
             FROM contact_email  $joinEmail
             WHERE eml_email_address <> '' AND eml_email_address IS NOT NULL AND eml_active = 1 and  (eml_email_address LIKE '%" . ($arr['email_address']) . "%')
             GROUP BY eml_email_address
             HAVING count(DISTINCT eml_contact_id) > 1) ";
		}
		if ($arr['phone_no'])
		{
			$where5 .= " phn_phone_no IN
            (SELECT contact_phone.phn_phone_no
             FROM contact_phone $joinPhone
             WHERE phn_phone_no <> '' AND phn_phone_no IS NOT NULL AND phn_active = 1 and (phn_phone_no LIKE '%" . ($arr['phone_no']) . "%')
             GROUP BY phn_phone_no
             HAVING count(DISTINCT phn_contact_id) > 1)";
		}
		if ($arr['contactperson'])
		{

			$where6 .= "ctt_first_name IN
            (SELECT contact.ctt_first_name
             FROM contact
             WHERE ctt_first_name <> '' AND ctt_first_name IS NOT NULL AND ctt_active = 1 and (ctt_first_name LIKE '%" . ($arr['ctt_first_name']) . "%')
             GROUP BY ctt_first_name
             HAVING COUNT(DISTINCT ctt_id) > 1)
             AND ctt_last_name IN
             (SELECT contact.ctt_last_name
             FROM contact
             WHERE ctt_last_name <> '' AND ctt_last_name IS NOT NULL AND ctt_active = 1 and (ctt_last_name LIKE '%" . ($arr['ctt_last_name']) . "%')
             GROUP BY ctt_last_name
             HAVING COUNT(DISTINCT ctt_id) > 1)";
		}

		if (count($arr) == 0)
		{
			$sqlCount	 .= "$selectCount	FROM $type
					  $vendorsCount
					 left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id   AND cntp.phn_active = 1
					 left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
				     WHERE ctt_active > 0 and (   ctt_voter_no IN
            (SELECT contact.ctt_voter_no
             FROM contact $join
             WHERE ctt_voter_no <> '' AND ctt_voter_no IS NOT NULL AND ctt_active = 1
             GROUP BY ctt_voter_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_aadhaar_no IN
            (SELECT contact.ctt_aadhaar_no
             FROM contact $join
             WHERE ctt_aadhaar_no <> '' AND ctt_aadhaar_no IS NOT NULL AND ctt_active = 1
             GROUP BY ctt_aadhaar_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_license_no IN (SELECT contact.ctt_license_no
                        FROM contact  $join
                        WHERE ctt_license_no <> '' AND ctt_license_no IS NOT NULL AND ctt_active = 1
                        GROUP BY ctt_license_no
                        HAVING COUNT(DISTINCT ctt_id) > 1)

       OR ctt_pan_no IN (SELECT contact.ctt_pan_no
                        FROM contact  $join
                        WHERE ctt_pan_no <> '' AND ctt_pan_no IS NOT NULL AND ctt_active = 1
                        GROUP BY ctt_pan_no
                        HAVING COUNT(DISTINCT ctt_id) > 1)

      OR eml_email_address IN
            (SELECT contact_email.eml_email_address
             FROM contact_email  $joinEmail
             WHERE eml_email_address <> '' AND eml_email_address IS NOT NULL AND eml_active = 1
             GROUP BY eml_email_address
             HAVING count(DISTINCT eml_contact_id) > 1)
      OR phn_phone_no IN
            (SELECT contact_phone.phn_phone_no
             FROM contact_phone  $joinPhone
             WHERE phn_phone_no <> '' AND phn_phone_no IS NOT NULL AND phn_active = 1
             GROUP BY phn_phone_no
             HAVING count(DISTINCT phn_contact_id) > 1)
             $vendors1 ";
			$sql		 .= "
					$select	FROM $type
					$vendors
					 left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id   AND cntp.phn_active = 1
					 left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
				     WHERE ctt_active > 0 and (   ctt_voter_no IN
            (SELECT contact.ctt_voter_no
             FROM contact $join
             WHERE ctt_voter_no <> '' AND ctt_voter_no IS NOT NULL AND ctt_active = 1
             GROUP BY ctt_voter_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_aadhaar_no IN
            (SELECT contact.ctt_aadhaar_no
             FROM contact $join
             WHERE ctt_aadhaar_no <> '' AND ctt_aadhaar_no IS NOT NULL AND ctt_active = 1
             GROUP BY ctt_aadhaar_no
             HAVING COUNT(DISTINCT ctt_id) > 1)
      OR ctt_license_no IN (SELECT contact.ctt_license_no
                        FROM contact  $join
                        WHERE ctt_license_no <> '' AND ctt_license_no IS NOT NULL AND ctt_active = 1
                        GROUP BY ctt_license_no
                        HAVING COUNT(DISTINCT ctt_id) > 1)

       OR ctt_pan_no IN (SELECT contact.ctt_pan_no
                        FROM contact  $join
                        WHERE ctt_pan_no <> '' AND ctt_pan_no IS NOT NULL AND ctt_active = 1
                        GROUP BY ctt_pan_no
                        HAVING COUNT(DISTINCT ctt_id) > 1)

      OR eml_email_address IN
            (SELECT contact_email.eml_email_address
             FROM contact_email  $joinEmail
             WHERE eml_email_address <> '' AND eml_email_address IS NOT NULL AND eml_active = 1
             GROUP BY eml_email_address
             HAVING count(DISTINCT eml_contact_id) > 1)
      OR phn_phone_no IN
            (SELECT contact_phone.phn_phone_no
             FROM contact_phone  $joinPhone
             WHERE phn_phone_no <> '' AND phn_phone_no IS NOT NULL AND phn_active = 1
             GROUP BY phn_phone_no
             HAVING count(DISTINCT phn_contact_id) > 1)
             $vendors1 ";
		}
		else if ($arr['ctt_voter_no'])
		{
			$sql .= "$select	FROM $type
					 $vendors
					 left JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id    AND cntp.phn_active = 1
					 left JOIN contact_email as cnte ON contact.ctt_id= cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE  ctt_active = 1 and (
					$where  $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					 $vendorsCount
					 left JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id    AND cntp.phn_active = 1
					 left JOIN contact_email as cnte ON contact.ctt_id= cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE  ctt_active = 1 and (
					$where  $vendors1 ";

			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or  $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_aadhaar_no'])
		{
			$sql .= "$select	FROM $type
					$vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where1  $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where1  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_pan_no'])
		{
			$sql .= "$select	FROM $type
					 $vendors
					 left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					 left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE ctt_active = 1 and (
					$where2   $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where2  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_license_no'])
		{
			$sql .= "$select	FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					WHERE  ctt_active = 1 and ( $where3  $vendors1 ";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where3  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['email_address'])
		{
			$sql .= "$select FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					WHERE ctt_active = 1 and ( $where4  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where4  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['phone_no'])
		{
			$sql .= "$select	FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id  AND cnte.eml_active = 1
					WHERE  ctt_active = 1 and ( $where5  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where5  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
		}
		else if ($arr['contactperson'])
		{
			$sql .= "$select	FROM $type
				    $vendors
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id AND cnte.eml_active = 1
					WHERE  ctt_active = 1  and  ( $where6  $vendors1";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where6  $vendors1 ";

			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}
		else if ($arr['ctt_id'])
		{
			$sql .= "$select FROM $type
					 $vendors
					 left JOIN contact_phone as cntp ON contact.ctt_id=cntp.phn_contact_id    AND cntp.phn_active = 1
					 left JOIN contact_email as cnte ON contact.ctt_id= cnte.eml_contact_id  AND cnte.eml_active = 1
					 WHERE  ctt_active = 1 and (ctt_id = '" . ($arr['ctt_id']) . "') and (
					$where8  $vendors2";

			$sqlCount .= "$selectCount	FROM $type
					$vendorsCount
					left JOIN contact_phone AS cntp ON contact.ctt_id = cntp.phn_contact_id  AND cntp.phn_active = 1
					left JOIN contact_email AS cnte ON contact.ctt_id = cnte.eml_contact_id   AND cnte.eml_active = 1
					WHERE ctt_active = 1 and  (
					$where8  $vendors1 ";

			if ($arr['ctt_aadhaar_no'])
			{
				$sql		 .= " or $where1";
				$sqlCount	 .= " or $where1";
			}
			if ($arr['ctt_voter_no'])
			{
				$sql		 .= " or  $where ";
				$sqlCount	 .= " or $where ";
			}
			if ($arr['ctt_pan_no'])
			{
				$sql		 .= " or  $where2";
				$sqlCount	 .= " or $where2";
			}
			if ($arr['ctt_license_no'])
			{
				$sql		 .= " or $where3";
				$sqlCount	 .= " or $where3";
			}
			if ($arr['email_address'])
			{
				$sql		 .= " or $where4";
				$sqlCount	 .= " or $where4";
			}
			if ($arr['phone_no'])
			{
				$sql		 .= " or $where5";
				$sqlCount	 .= " or $where5";
			}
		}

		if ($ctt_id != NULL && count($arr) > 0)
		{
			if ($vnd_id != "" && $type == "vendors")
			{
				$sql		 .= " ) and  vnd_id <> $vnd_id and vnd_is_merged =0 and vnd_merged_to=0  $groupby ORDER BY ctt_id ";
				$sqlCount	 .= " ) and  vnd_id <> $vnd_id and vnd_is_merged =0 and vnd_merged_to=0  $groupby  ";
			}
			else if ($vnd_id != "" && $type == "drivers")
			{
				$sql		 .= " ) and  drv_id <> $vnd_id and drv_is_merged =0 and drv_merged_to=0  $groupby ORDER BY ctt_id ";
				$sqlCount	 .= " ) and  drv_id <> $vnd_id and drv_is_merged =0 and drv_merged_to=0  $groupby ";
			}
			else
			{
				$sql		 .= " ) and ctt_id <> $ctt_id  $groupby ORDER BY ctt_id ";
				$sqlCount	 .= " ) and ctt_id <> $ctt_id  $groupby  ";
			}
		}
		else if ($ctt_id != NULL && count($arr) == 0)
		{
			if ($vnd_id != "" && $type == "vendors")
			{
				$sql		 .= " ) and vnd_id <> $vnd_id and  vnd_is_merged=0 and vnd_merged_to=0  $groupby  ";
				$sqlCount	 .= " ) and vnd_id <> $vnd_id and  vnd_is_merged=0 and vnd_merged_to=0  $groupby  ";
			}
			else if ($vnd_id != "" && $type == "drivers")
			{
				$sql		 .= " ) and drv_id <> $vnd_id and  drv_is_merged=0 and drv_merged_to=0  $groupby ";
				$sqlCount	 .= " ) and drv_id <> $vnd_id and  drv_is_merged=0 and drv_merged_to=0  $groupby ";
			}
			else
			{
				$sql		 .= " ) and ctt_id = $ctt_id  $groupby ";
				$sqlCount	 .= " ) and ctt_id = $ctt_id  $groupby ";
			}
		}
		else
		{
			$sql		 .= "  )  $groupby";
			$sqlCount	 .= "  )  $groupby";
		}


		$count			 = DBUtil::command("select count(*)  FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, ['db' => DBUtil::SDB(), 'totalItemCount' => $count, 'sort' => ['defaultOrder' => ''], 'pagination' => ['pageSize' => 100]]);
		return $dataprovider;
	}

	public function checkContactInfoDriver($attribute, $params)
	{
		if (isset($this->ctt_id) && $this->ctt_id > 0)
		{
			$result = Contact::model()->getContactDetails($this->ctt_id);
			if ($result['phn_phone_no'] == "")
			{
				$this->addError($attribute, "Phone number cannot be blank. ");
				// return false;
			}
			if ($result['ctt_license_no'] == "")
			{
				$this->addError($attribute, "Licence Number cannot be blank. ");
				// return false;
			}
			if ($result['ctt_license_exp_date'] == "")
			{
				$this->addError($attribute, "Licence expiry date cannot be blank. ");
				// return false;
			}
			if ($result['ctt_license_doc_id'] == "")
			{
				$this->addError($attribute, "Licence Path cannot be blank. ");
				// return false;
			}
			return false;
		}
		else
		{
			$this->addError($attribute, "Contact Information cannot be blank. ");
			return false;
		}
		return true;
	}

	public function saveProfileImage($cttId = null)
	{
		$model = $this;
		if ($cttId != null)
		{
			$model = Contact::model()->findByPk($cttId);
		}
		else
		{
			$cttId = $model->ctt_id;
		}
		$profileImage = CUploadedFile::getInstance($model, "ctt_profile_path");
		if ($profileImage != "")
		{
			$path					 = Document::model()->uploadDocument($cttId, 'profile', $profileImage, '');
			$model->ctt_profile_path = $path[0];
			$model->update();
		}
	}

	public static function saveContactLog($oldCttId, $newCttId)
	{
		$userInfo = UserInfo::getInstance();
		if ($oldCttId == NULL)
		{
			$event_id	 = ContactLog::CONTACT_CREATED;
			$desc		 = "Contact created";
		}
		else
		{
			$event_id	 = ContactLog::CONTACT_MODIFIED;
			$desc		 = "Contact modified";
		}
		ContactLog::model()->createLog($newCttId, $desc, $userInfo, $event_id, false, false);
	}

	public function merge($cttid, $mgrcttid, $oldData = '')
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$this->save())
			{
				$returnSet->setErrors($this->getErrors(), 1);
				throw new CHttpException("Failed to add contact", 1);
			}
			Contact::saveProfileImage($cttid);
			$oldVendorModel		 = Vendors::model()->findByVendorContactID($mgrcttid);
			$userInfo			 = UserInfo::getInstance();
			$getOldDifference	 = array_merge(array_diff_assoc($oldData, $this->attributes));
			$changesForLog		 = " Old Values: " . $this->getModificationMSG($getOldDifference, false);
			if ($oldVendorModel->vnd_id != NULL)
			{
				// $message = "Vendor contact merged | Vendor ID: $oldVendorModel->vnd_id and Contact ID: $mgrcttid, Contact Merge:$mgrcttid  is merged with $cttid.";
				$message = "Vendor contact merged | Vendor ID: $oldVendorModel->vnd_id  Contact:$mgrcttid merged with contact:$cttid.";
				$message .= $changesForLog;
				VendorsLog::model()->createLog($oldVendorModel->vnd_id, $message, $userInfo, VendorsLog::VENDOR_MODIFIED, false, false);
			}

			$oldDriverModel = Drivers::model()->findByDriverContactID($mgrcttid);
			if ($oldDriverModel->drv_id != NULL)
			{
				// $message = "Driver contact merged, Driver ID: $oldDriverModel->drv_id And Contact ID: $mgrcttid, Contact	Merge :  $mgrcttid  is merged with $cttid.";
				$message = "Driver contact merged| Driver ID: $oldDriverModel->drv_id; Contact:$mgrcttid merged with contact:$cttid.";
				$message .= $changesForLog;
				DriversLog::model()->createLog($oldDriverModel->drv_id, $message, $userInfo, DriversLog::DRIVER_MODIFIED, false, false);
			}

			Contact::model()->updateContactMerge($cttid, $mgrcttid, $this->contactPhones, $this->contactEmails);
			$event_id	 = ContactLog::CONTACT_MERGE;
			$desc		 = "Contact	Merge: $mgrcttid merged with $cttid  ";
			ContactLog::model()->createLog($cttid, $desc, $event_id, null);
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setData(["id" => $cttid]);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function getModificationMSG($diff)
	{
		$model	 = $msg	 = '';
		if (count($diff) > 0)
		{
			if ($diff['ctt_first_name'] || $diff['ctt_last_name'])
			{
				$msg .= ' Contact Name: ' . $diff['ctt_first_name'] . ' ' . $diff['ctt_last_name'] . ',';
			}
			if ($diff['ctt_business_name'])
			{
				$msg .= ' Contact Business Name: ' . $diff['ctt_business_name'] . ',';
			}
			if ($diff['ctt_address'])
			{
				$msg .= ' Contact Address: ' . $diff['ctt_address'] . ',';
			}
			if ($diff['ctt_city'])
			{
				$msg .= ' Contact City: ' . $diff['ctt_city'] . ',';
			}
			if ($diff['ctt_state'])
			{
				$msg .= ' Contact State: ' . $diff['ctt_state'] . ',';
			}
			if ($diff['ctt_voter_no'])
			{
				$msg .= ' Contact Voter No: ' . $diff['ctt_voter_no'] . ',';
			}
			if ($diff['ctt_aadhaar_no'])
			{
				$msg .= ' Contact Aadhaar No: ' . $diff['ctt_aadhaar_no'] . ',';
			}
			if ($diff['ctt_pan_no'])
			{
				$msg .= ' Contact Pan No: ' . $diff['ctt_pan_no'] . ',';
			}
			if ($diff['ctt_license_no'])
			{
				$msg .= ' Contact License No: ' . $diff['ctt_license_no'] . ',';
			}
			if ($diff['ctt_bank_name'])
			{
				$msg .= ' Contact Bank Name: ' . $diff['ctt_bank_name'] . ',';
			}
			if ($diff['ctt_bank_branch'])
			{
				$msg .= ' Contact Bank Branch: ' . $diff['ctt_bank_branch'] . ',';
			}
			if ($diff['ctt_bank_account_no'])
			{
				$msg .= ' Contact Bank Account No: ' . $diff['ctt_bank_account_no'] . ',';
			}
			if ($diff['ctt_bank_ifsc'])
			{
				$msg .= ' Contact Bank IFSC: ' . $diff['ctt_bank_ifsc'] . ',';
			}
			if ($diff['ctt_beneficiary_name'])
			{
				$msg .= ' Contact Beneficiary Name: ' . $diff['ctt_beneficiary_name'] . ',';
			}
			if ($diff['vrs_credit_limit'])
			{
				$msg .= ' Vendor Credit Limit: ' . $diff['vrs_credit_limit'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public static function setType($cttid, $userType, $accountType)
	{
		$modelId					 = Contact::model()->findByPk($cttid);
		$modelId->ctt_user_type		 = $userType;
		$modelId->ctt_account_type	 = $accountType;
		$modelId->update();
	}

	public function findDuplicateContacts($email = '', $phone = '', $licence = '', $pan = '', $aadhar = '', $voter = '')
	{
		if ($email != '' || $phone != '' || $licence != '' || $pan != '' || $aadhar != '' || $voter != '')
		{
			$cond = "";
			if ($email != '')
			{
				$cond = " AND eml_email_address = '$email'";
			}
			if ($phone != '')
			{
				$cond .= " AND phn_phone_no = '$phone'";
			}
			if ($licence != '')
			{
				$cond .= " AND ctt_license_no='$licence'";
			}
			if ($pan != '')
			{
				$cond .= " AND ctt_pan_no = '$pan'";
			}
			if ($aadhar != '')
			{
				$cond .= " AND ctt_aadhaar_no = '$aadhar'";
			}
			if ($voter != '')
			{
				$cond .= " AND ctt_voter_no = '$voter'";
			}

			$sql = "SELECT ctt_id FROM contact LEFT JOIN contact_phone ON ctt_id = phn_contact_id LEFT JOIN contact_email ON ctt_id = eml_contact_id
						 WHERE 1 $cond GROUP BY ctt_id";
			return DBUtil::queryAll($sql);
		}
		return false;
	}

	public function getUserIdByContactId($contactId)
	{
		$sql = "SELECT DISTINCT (a.user_id),
		a.provider,
		a.provider_count,
		a.profile_cache,
		type as UserType,
        vendors.vnd_code,
        drivers.drv_code
        FROM (SELECT DISTINCT (user_id),
                      provider,
                      if(provider != '0', 1, 0) AS provider_count,
                      profile_cache,
                      'Vendor' AS type
        FROM imp_user_oauth
        WHERE imp_user_oauth.user_id IN
               (SELECT v.vnd_user_id
                FROM contact
					INNER JOIN contact_profile AS cp ON cp.cr_contact_id = contact.ctt_id and cp.cr_status =1 and contact.ctt_id = contact.ctt_ref_code
					INNER JOIN vendors ON vendors.vnd_id = cp.cr_is_vendor AND vendors.vnd_active= 1
					INNER JOIN vendors AS v ON v.vnd_id = vendors.vnd_ref_code
                         AND v.vnd_active = 1
                           AND contact.ctt_active = 1
                           AND contact.ctt_id = $contactId)

		HAVING provider_count = 1
		UNION
		SELECT DISTINCT (user_id),
                      provider,
                      if(provider != '0', 1, 0) AS provider_count,
                      profile_cache,
                      'Driver' AS type
		FROM imp_user_oauth
		WHERE imp_user_oauth.user_id IN
				 (SELECT d1.drv_user_id
                FROM contact
					INNER JOIN contact_profile AS cp ON cp.cr_contact_id = contact.ctt_id AND cp.cr_status =1 AND contact.ctt_id = contact.ctt_ref_code
					INNER JOIN drivers ON drivers.drv_id = cp.cr_is_driver AND drivers.drv_active= 1
					INNER JOIN drivers AS d1 ON d1.drv_id = drivers.drv_ref_code
                           AND d1.drv_active = 1
                           AND contact.ctt_active = 1
                           AND contact.ctt_id = $contactId

                    )
		HAVING provider_count = 1) AS a
		LEFT JOIN vendors  ON  a.user_id = vendors.vnd_user_id  AND vendors.vnd_active = 1
        LEFT JOIN drivers  ON  a.user_id = drivers.drv_user_id  AND drivers.drv_active = 1
						 ";

		return DBUtil::queryAll($sql);
	}

	public function addDriverPendingDocument($drvModel, $contactModel, $dmodel, $arr, $documents)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($arr['bcb_drv_lic_exp_date'] != '' || $arr['bcb_drv_lic_number'] != '' || $documents != null)
			{
				$doc_file_front_path = CUploadedFile::getInstance($dmodel, 'doc_file_front_path');
				$doc_file_back_path	 = CUploadedFile::getInstance($dmodel, 'doc_file_back_path');
				if ($doc_file_front_path !== null || $doc_file_back_path != null)
				{
					$dmodel->attributes							 = $documents;
					$dmodel->doc_type							 = 5;
					$dmodel->doc_temp_approved					 = $documents['doc_temp_approved'][0] == 1 ? 1 : 0;
					$dmodel->entity_id							 = $drvModel->drvContact->ctt_id;
					$success									 = $dmodel->add();
					$drvModel->drvContact->addType				 = -1;
					$drvModel->drvContact->ctt_license_doc_id	 = $dmodel->doc_id;
				}
				if ($arr['bcb_drv_lic_exp_date'] != '' && $arr['bcb_drv_lic_exp_date'] != NULL)
				{
					$drvLicExpDate								 = DateTimeFormat::DatePickerToDate($arr['bcb_drv_lic_exp_date']);
					$drvModel->drvContact->ctt_license_exp_date	 = $drvLicExpDate;
					$newData['ctt_license_exp_date']			 = $drvModel->drvContact->ctt_license_exp_date;
					$drvModel->drvContact->ctt_license_exp_date	 = $drvLicExpDate;
				}
				if ($arr['bcb_drv_lic_number'] != '' && $arr['bcb_drv_lic_number'] != NULL)
				{
					$drvModel->drvContact->ctt_license_no	 = $arr['bcb_drv_lic_number'];
					$newData['ctt_license_no']				 = $drvModel->drvContact->ctt_license_no;
					$drvModel->drvContact->ctt_license_no	 = $arr['bcb_drv_lic_number'];
				}
				if ($drvModel->drvContact->update())
				{
					$description = "Driver modified| ";
					$description .= "Params updated: " . Drivers::model()->getModificationMSG($newData, false);
					DriversLog::model()->createLog($drvModel->drv_id, $description, UserInfo::getInstance(), DriversLog::DRIVER_MODIFIED, false, false);
				}
			}
			DBUtil::commitTransaction($transaction);
			return true;
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			return false;
		}
	}

	/**
	 * Validate the license number (true if already exist, false if not exist)
	 * @param type $licenseNo
	 * @return bool
	 * @throws Exception
	 */
	public static function checkExistingDetails($field, $value, $id = null)
	{
		$success = false;
		if ($value == '')
		{
			goto end;
		}

		$params = ['value' => $value];

		if ($id != null)
		{
			$where			 = " AND ctt_id<>:id";
			$params['id']	 = $id;
		}

		$sql	 = "SELECT COUNT(1) FROM contact WHERE ctt_active>0 AND $field=:value $where";
		$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		$success = ($count > 0);

		end:
		return $success;
	}

	/**
	 * This function is used for validating the license number
	 * @param type $licenseNo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function checkLicenseNo($licenseNo)
	{
		$returnset = new ReturnSet();

		try
		{
			if (empty($licenseNo))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql	 = "SELECT COUNT(1) FROM contact WHERE 	ctt_license_no = :id";
			$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $licenseNo]);

			$returnset->setStatus($count > 0);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * This function is used for finding the contact Id based on licence No
	 * @param type $licenseNo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function getLicenseCtt($licenseNo)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($licenseNo))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql	 = "SELECT ctt_id FROM contact WHERE ctt_license_no = '$licenseNo' and  ctt_active = 1";
			$data	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();

			if (!empty($data))
			{
				$returnset->setStatus(true);
				$returnset->setData($data);
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
	 * This function is used for validating the license number
	 * @param type $voterNo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function checkVoterNo($voterNo)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($voterNo))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql	 = "SELECT COUNT(1) FROM contact WHERE 	ctt_voter_no = :id";
			$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $voterNo]);
			$returnset->setStatus($count > 0);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}
		return $returnset;
	}

	/**
	 * This function is used for validating the license number
	 * @param type $aadhaarNo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function checkAadhaarNo($aadhaarNo)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($aadhaarNo))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql	 = "SELECT COUNT(1) FROM contact WHERE ctt_aadhaar_no = :id";
			$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $aadhaarNo]);
			$returnset->setStatus($count > 0);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}
		return $returnset;
	}

	/**
	 * This function is used for validating the license number
	 * @param type $panNo
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function checkPanNo($panNo)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($panNo))
			{
				throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$sql	 = "SELECT COUNT(1) FROM contact WHERE ctt_pan_no = :id";
			$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar(['id' => $panNo]);
			$returnset->setStatus($count > 0);
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
	 * @param int $validationType - 1: Email , 2: Phone				-	Mandatory
	 * @param int $validationId - ContactId - UK					-	Mandatory
	 * @param int $validationValue - Phone number or Email Address	-	Mandatory
	 *
	 * @param int $validationSource - SocialAuth:: Constant			-	Optional
	 *
	 * @return array
	 */
	public function validateContact($validationType, $validationId, $validationValue, $validationSource = 0)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($validationType) || empty($validationId) || empty($validationValue))
			{
				throw new Exception("Invalid Parameters", ReturnSet::ERROR_INVALID_DATA);
			}

			switch ($validationType)
			{
				case 1:
					$validationSource	 = empty($validationSource) ? SocialAuth::Eml_Gozocabs : $validationSource;
					$returnset			 = ContactEmail::model()->validateData($validationId, $validationValue, $validationSource);
					break;

				case 2:
					$returnset = ContactPhone::model()->validateData($validationId, $validationValue);
					break;
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
	 * Used from WEB
	 * This function is used for adding contact details for a user
	 * @param type $requestData
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addContact($requestData)
	{
		$returnset	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (empty($requestData))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			/**
			 * Add to contact table
			 * If DOC, Add a driver for the same contact Id
			 * Add data to contact email table which will be at unverified state
			 * Add data to contact Phone table which will be ata unverified state
			 */
			$contactModel = new Contact();

			$contactModel->ctt_first_name	 = $requestData->fName;
			$contactModel->ctt_last_name	 = $requestData->lName;
			$contactModel->ctt_city			 = $requestData->cityId;
			$contactModel->ctt_business_name = empty($requestData->businessName) ? "" : $requestData->businessName;
			$contactModel->ctt_license_no	 = empty($requestData->driverLicenseNo) ? "" : $requestData->driverLicenseNo;
			$contactModel->ctt_active		 = 2; //Deactive mode
			//Saving in inactive mode
			if (!$contactModel->save())
			{
				throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$desc = "Contact created from web";
			ContactLog::model()->createLog($contactModel->ctt_id, $desc, ContactLog::CONTACT_CREATED, null);

			$contactId		 = $contactModel->ctt_id; //Last Inserted ID
			$emailResponse	 = ContactEmail::add($requestData->emailId, $contactId, 1);
			$phResponse		 = ContactPhone::add($contactId, $requestData->phoneNumber, UserInfo::TYPE_VENDOR, $requestData->countryCode, SocialAuth::Eml_Gozocabs, 1, 0);

			//$returnset->setData(0);
			if ($emailResponse->getStatus() || $phResponse->getStatus())
			{
				$updateModel			 = Contact::model()->findByPk($contactId);
				$updateModel->ctt_active = 1; //Active Mode
				$updateModel->save();
				/**
				 * update ref code for contact
				 */
				Contact::updateRefCode($contactId, $contactId);

				$contactDetails			 = new stdClass();
				$contactDetails->phone	 = $phResponse;
				//$contactDetails->email = $emailResponse;
				$contactDetails->id		 = $contactId;
				$returnset->setStatus(true);
				$returnset->setData($contactDetails, false);
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	public function updateContactDetails($mgrcttId)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{

			if ($mgrcttId)
			{
				/**
				 * Deactivate contact of merged contact
				 */
				$sql = "UPDATE contact SET ctt_active=0 WHERE ctt_id IN ($mgrcttId)";
				$cnt = DBUtil::command($sql)->execute();
				/**
				 * Deactivate duplicate Email address
				 */
				$sql = "UPDATE contact_email SET eml_active = 0, eml_is_verified = 0 WHERE  eml_contact_id  IN ($mgrcttId)";
				$cnt = DBUtil::command($sql)->execute();

				/**
				 * Deactivate duplicate Phone address
				 */
				$sql = "UPDATE contact_phone SET phn_active = 0 WHERE phn_contact_id IN ($mgrcttId)";
				$cnt = DBUtil::command($sql)->execute();

				/**
				 * Deactivate duplicate Contact Profile
				 */
				Logger::error('contact updateContactDetails: set cr_status = 0. $mgrcttId:' . $mgrcttId, true);

				$sql = "UPDATE contact_profile SET cr_status = 0 WHERE cr_contact_id IN ($mgrcttId)";
				$cnt = DBUtil::command($sql)->execute();
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			return false;
		}
	}

	/**
	 *
	 * @param type $emailAddress
	 * @param type $contactId
	 * @param type $sourceType
	 * @param type $templateStyle
	 * @param type $tempPkId
	 * @param type $vndId
	 * @return type
	 */
	public static function emailVerificationLink($emailAddress, $contactId, $sourceType, $templateStyle, $tempPkId = 0, $vndId = 0)
	{
		$emailWrapper = new emailWrapper();
		switch ($sourceType)
		{
			case UserInfo::TYPE_VENDOR:
				$userType	 = EmailLog::Vendor;
				$refId		 = EmailLog::REF_VENDOR_ID;

				break;
			case UserInfo::TYPE_DRIVER:
				$userType	 = EmailLog::Driver;
				$refId		 = EmailLog::REF_DRIVER_ID;
				break;
			case UserInfo::TYPE_CONSUMER:
				$userType	 = EmailLog::Consumers;
				$refId		 = EmailLog::REF_USER_ID;
				break;
			case UserInfo::TYPE_AGENT:
				$sourceType	 = EmailLog::Agent;
				$userType	 = EmailLog::Agent;
				$refId		 = $contactId;
				break;
			default:
				break;
		}

		$result = $emailWrapper->sendVerificationLink($emailAddress, $contactId, $sourceType, $userType, $refId, $templateStyle, $tempPkId, $vndId);
		return $result;
	}

	/**
	 *
	 * @param type $phone
	 * @param type $contactId
	 * @param type $sourceType
	 * @param type $templateStyle
	 * @param type $tempPkId
	 * @param type $ext
	 * @param type $otp
	 * @param type $vndId
	 * @return type
	 */
	public static function sendPhoneVerificationLink($phone, $contactId, $sourceType, $templateStyle, $tempPkId = 0, $ext, $otp, $vndId)
	{
		$smsWrapper	 = new smsWrapper();
		$userType	 = "";
		$refId		 = "";
		switch ($sourceType)
		{
			case UserInfo::TYPE_VENDOR:
				$userType	 = SmsLog::Vendor;
				$refId		 = SmsLog::REF_VENDOR_ID;
				break;
			case UserInfo::TYPE_DRIVER:
				$userType	 = SmsLog::Driver;
				$refId		 = SmsLog::REF_DRIVER_ID;
				break;
			case UserInfo::TYPE_CONSUMER:
				$userType	 = SmsLog::Consumers;
				$refId		 = SmsLog::REF_USER_ID;
				break;
			case UserInfo::TYPE_AGENT:
				$sourceType	 = SmsLog::Agent;
				$userType	 = SmsLog::Agent;
				$refId		 = $contactId;
				break;
			default:
				break;
		}
		$arrProfile	 = ContactProfile::getEntityById($contactId, $sourceType);
		$entityId	 = $arrProfile['id'];

		//whatsapplog
		$response = WhatsappLog::updateVendorDriverPhoneNumber($contactId, $phone, $otp, $templateStyle, $tempPkId, $sourceType, $entityId, $vndId);

		#echo "$contactId - $phone - $otp - $templateStyle - $tempPkId - $sourceType - $entityId - $vndId";
		#echo $response->getStatus();
		if (!$response->getStatus())
		{
			$response = $smsWrapper->sendOtpForVerification($contactId, $phone, $sourceType, $userType, $refId, $templateStyle, $tempPkId, $ext, $otp, $vndId);
		}
		return $response;
	}

	/**
	 * This function is used for saving the contact details
	 * @return \ReturnSet
	 */
	public function handleContact()
	{
		$returnset = new ReturnSet();
		try
		{
			if (!$this->save())
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_FAILED);
			}

			$desc = "Contact created from app";
			ContactLog::model()->createLog($this->ctt_id, $desc, ContactLog::CONTACT_CREATED, null);

			$contactId = $this->ctt_id;
			/**
			 * save ref code for contact
			 */
			Contact::model()->updateRefCode($this->ctt_id, $this->ctt_id);

			foreach ($this->contactDetails as $contact)
			{
				switch ($contact->mediumType)
				{
					case Stub\common\ContactMedium::TYPE_EMAIL:
						$response = ContactEmail::model()->addNew($contactId, $contact->eml_email_address, SocialAuth::Eml_Gozocabs, $contact->eml_is_primary);
						if ($response->getData() < 0)
						{
							throw new Exception("Failed to create contact email", ReturnSet::ERROR_FAILED);
						}
						break;

					case Stub\common\ContactMedium::TYPE_PHONE:
						$response = ContactPhone::model()->add($contactId, $contact->phn_phone_no, 0, $contact->phn_phone_country_code, SocialAuth::Eml_Gozocabs, $contact->phn_is_primary);
						if ($response->getData() < 0)
						{
							throw new Exception("Failed to create contact phone", ReturnSet::ERROR_FAILED);
						}
						break;
				}
			}

			$returnset->setStatus(true);
			$returnset->setData($contactId, false);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 * This function is used for verifying the contact
	 *
	 * @param type $emailId			- Email address received in request		-	Mandatory
	 * @param type $phone			- Phone Number received in request		-	Mandatory
	 * @param type $returnType		- Array or Boolean (Default)			-	Optional
	 *
	 * @return \ReturnSet
	 */
	public static function verifyContact($emailId, $phone, $returnType = 0)
	{
		$returnset = new ReturnSet();
		try
		{
			$emailResponse	 = ContactEmail::model()->findEmail($emailId, 0, 1, 1);
			$phoneResponse	 = ContactPhone::model()->findPhone($phone, 0, 1, 0);

			if ($emailResponse->getStatus() || $phoneResponse->getStatus())
			{
				if ($returnType > 0)
				{
					$response		 = new stdClass();
					$response->email = $emailResponse->getData();
					$response->phone = $phoneResponse->getData();

					$returnset->setData($response);
				}

				$returnset->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset = $returnset->setException($ex);
		}

		return $returnset;
	}

	/**
	 *
	 * @param type $itemId      - Email Address/ Phone No
	 * @param type $type        - 1: Email / 2: Phone/ 3: Notification
	 * @param type $mode        - 1: Link /2: OTP
	 * @param type $isVerify    - 1: Verify / 2: Not Verify
	 */
	public static function verifyItem($itemId, $type, $mode, $isVerify = 0, $email = null, $modifyPhone = null)
	{
		$returnSet = new ReturnSet();
		switch ($type)
		{
			case Contact::TYPE_EMAIL:

				//$returnSet	 = ContactEmail::model()->updateContacts($itemId, $email, SocialAuth::Eml_Gozocabs, $isVerify);
				$returnSet	 = ContactEmail::model()->editContacts($itemId, $email, SocialAuth::Eml_Gozocabs, $isVerify, 1);
				break;
			case Contact::TYPE_PHONE:
				//$returnSet = ContactPhone::model()->updatePhoneStatus($itemId, $isVerify);
				$returnSet	 = ContactPhone::model()->updateVerifyStatus($itemId, $modifyPhone);
				break;
			case Contact::TYPE_NOTIFICATION:
			/**
			 * @todo Send notification to verify contact
			 */
			default:
				break;
		}
		return $returnSet;
	}

	/**
	 * This function identifies whether contact already exists or not or we need to treat as new.
	 *
	 * @return \ReturnSet
	 * @throws Exception
	 *
	 * Case 1: If contact Exists, If driver not mapped. Inform the contact Id to the alternate contact Item.
	 * Case 2: If doesn't exists, then proceed with contact regular flow
	 */
	public function validateContactItem()
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($this->contactTempDetails["tmp_ctt_phn_number"]))
			{
				// throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
				$returnset->setErrors("Invalid data", ReturnSet::ERROR_INVALID_DATA);
				goto skipAll;
			}


			$licenseNo		 = $this->contactTempDetails['tmp_ctt_license'];
			$isLicenseExist	 = $this->getLicenseCtt($licenseNo);
			$arrFoundConIds	 = [];
			if ($isLicenseExist->getStatus())
			{
				array_push($arrFoundConIds, $isLicenseExist->getData());
				goto skipData;
			}


			$email		 = $this->contactTempDetails['tmp_ctt_email'];
			$response	 = Contact::verifyContact($email, $this->contactTempDetails["tmp_ctt_phn_number"], self::RETURN_ARRAY);
			//Verified Contact dont exists. Treat as New
			if (!$response->getStatus())
			{
				goto skipAll;
			}

//            $arrFoundConIds = [];
			$data = $response->getData();

			//Found contact Id Email Detail
			if (isset($data->email) && !empty($data->email))
			{
				foreach ($data->email as $email)
				{
					array_push($arrFoundConIds, $email["ctt_id"]);
					if (isset($email["mapVendors"]) && !empty($email["mapVendors"]))
					{
						$mapVendors = $email["mapVendors"];
						foreach ($mapVendors as $vnd)
						{
							array_push($arrFoundVndIds, $vnd->vnd_id);
						}
					}
				}
			}
			//Found Contact Id Phone details
			if (isset($data->phone) && !empty($data->phone))
			{
				foreach ($data->phone as $phone)
				{
					array_push($arrFoundConIds, $phone["ctt_id"]);
					if (isset($phone["mapVendors"]) && !empty($phone["mapVendors"]))
					{
						$mapVendors = $phone["mapVendors"];
						foreach ($mapVendors as $vnd)
						{
							array_push($arrFoundVndIds, $vnd->vnd_id);
						}
					}
				}
			}

			skipData:
			$vndId = $this->vndId;
			if (empty($vndId))
			{
				$vndId = UserInfo::getEntityId();
			}

			//Checks if mapped vendor Id matches with logged in vendor Id
			if (in_array($vndId, array_unique($arrFoundVndIds)))
			{
				$returnset->setStatus(true);
				$returnset->setMessage("Driver is already mapped to your account. Can't add it further.");
				goto skipAll;
			}


			$response	 = ContactTemp::model()->add($arrFoundConIds[0], $this->contactTempDetails["tmp_ctt_email"], $this->contactTempDetails["tmp_ctt_name"], $this->contactTempDetails["tmp_ctt_phn_code"], $this->contactTempDetails["tmp_ctt_phn_number"], $this->contactTempDetails["tmp_ctt_license"], $vndId, $this->contactTempDetails["tmp_ctt_phn_otp"]);
			$isEmailSend = Contact::sendVerification($this->contactTempDetails["tmp_ctt_email"], Contact::TYPE_EMAIL, $arrFoundConIds[0], Contact::NOTIFY_OLD_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_DRIVER, $response->getData());
			if (!$isEmailSend)
			{
				$isOtpSend = Contact::sendVerification($this->contactTempDetails["tmp_ctt_phn_number"], Contact::TYPE_PHONE, $arrFoundConIds[0], Contact::NOTIFY_OLD_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_DRIVER, $response->getData(), $this->contactTempDetails["tmp_ctt_phn_otp"], $this->contactTempDetails["tmp_ctt_phn_code"]);
			}
			if ($isEmailSend || $isOtpSend)
			{
				$returnset->setStatus(true);
				$returnset->setMessage("Your request has been noted. We have send verification to the contact details. Please verify it ");
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		skipAll:
		return $returnset;
	}

	public function handleEntity($contactId)
	{
		if (empty($this->vndId))
		{
			$vndId = UserInfo::getEntityId();
		}
		else
		{
			$vndId = $this->vndId;
		}


		$drvName = $this->contactTempDetails["tmp_ctt_name"];
		$emailId = $this->contactTempDetails["tmp_ctt_email"];
		$phoneNo = $this->contactTempDetails["tmp_ctt_phn_number"];

		$response = Drivers::model()->handleDriver($vndId, $contactId, $drvName, $emailId, $phoneNo);
		return $response;
	}

	/**
	 *
	 * @param type $contactValue	-	Email Address/phone
	 * @param type $contactType		-	Email /Phone
	 * @param type $contactId       -	ContactId
	 * @param type $templateStyle   -	 1- newContact 0 - TemporaryContact
	 * @param type $mode				1- MODE_LINK  , 2- MODE_OTP
	 * @param type $userType    2 - TYPE_VENDOR , 3 - TYPE_DRIVER
	 */
	public static function sendVerification($contactValue, $contactType, $contactId, $templateStyle, $mode, $userType, $tempPkId = 0, $verifyCode = 0, $ext = 0, $vndId = 0)
	{
		Logger::profile("Contact::sendVerification Started");
		$returnSet = new ReturnSet();
		try
		{

			if ($contactValue)
			{
				switch ($contactType)
				{
					case self::TYPE_EMAIL:

						$returnSet = Contact::emailVerificationLink($contactValue, $contactId, $userType, $templateStyle, $tempPkId, $vndId);

						break;

					case self::TYPE_PHONE:

						$returnSet = Contact::sendPhoneVerificationLink($contactValue, $contactId, $userType, $templateStyle, $tempPkId, $ext, $verifyCode, $vndId);

					default:
						break;
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}
		Logger::profile("Contact::sendVerification Ended");
		return $returnSet;
	}

	/**
	 *
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addContacts()
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($this->contactDetails))
			{
				throw new Exception("Invalid input", ReturnSet::ERROR_INVALID_DATA);
			}

			$response = Contact::isVendor($this->contactDetails);
			if ($response->getStatus())
			{
				$returnSet->setMessage("This contact details is already registered as vendor. Please use that account");
				goto skipAll;
			}


			$response	 = Contact::handleContact();
			$contactId	 = $response->getData();

			if (empty($contactId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$name = "";
			if ($this->ctt_user_type == 2)
			{
				$name = $this->ctt_business_name;
			}
			else
			{
				$name = $this->ctt_first_name . "" . $this->ctt_last_name;
			}

			$response	 = Vendors::add($contactId, $name, $this->isDco, $this->ctt_city);
			$vndId		 = $response->getData();

			if (!$response->getStatus())
			{
				$returnSet->setMessage("Failed to create vendor");
				goto skipAll;
			}

			//Create vendor profile
			ContactProfile::setProfile($contactId, UserInfo::TYPE_VENDOR);
			if ($this->isDco)
			{
				$driverName	 = $this->ctt_first_name . " " . $this->ctt_last_name;
				$res		 = Drivers::addDriverDetails($contactId, $driverName);
				if ($res->getStatus())
				{
					ContactProfile::setProfile($contactId, UserInfo::TYPE_DRIVER);
					$data		 = ['vendor' => $vndId, 'driver' => $res->getData()];
					$resLinked	 = VendorDriver::model()->checkAndSave($data);
				}
			}

			//Send phone verification OTP
			$phoneNo		 = ContactPhone::model()->getContactPhoneById($contactId);
			$contactPhone	 = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNo);
			$isOtpSend		 = Contact::sendVerification($contactPhone->phn_phone_no, Contact::TYPE_PHONE, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_VENDOR, 0, $contactPhone->phn_otp, $contactPhone->phn_phone_country_code);

			//Send email verification link
			$contactEmail	 = ContactEmail::model()->getContactEmailById($contactId);
			$isEmailSend	 = Contact::sendVerification($contactEmail, Contact::TYPE_EMAIL, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_VENDOR);

			$obj	 = new stdClass();
			$obj->id = $vndId;

			$returnSet->setStatus(true);
			$returnSet->setData($obj);
			$returnSet->setMessage("Vendor account is created successfully, we have sent a verification link to vendor mail and mobile number.Please verify it");
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}

		skipAll:
		return $returnSet;
	}

	/**
	 * This function is used for checking whether verified vendor exists or not
	 * @param type $contactDetails
	 * @return \ReturnSet
	 */
	public function isVendor($contactDetails)
	{
		$returnSet = new ReturnSet();

		foreach ($contactDetails as $contact)
		{
			$cttId	 = self::getIdByDetails($contact->eml_email_address, $contact->phn_phone_no);
			$isExist = ContactProfile::checkExists($cttId);
			if ($isExist)
			{
				$profileModel = contactProfile::model()->findByContactId($cttId);
				foreach ($profileModel as $model)
				{
					if ($model->cr_is_vendor && $model->cr_status)
					{
						$returnSet->setStatus(true);
					}
				}
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for linking the social account with the user
	 * @param type $authData
	 * @param type $userId
	 * @param type $userType
	 */
	public function linkContact($authData, $userId, $userType, $provider)
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($authData) || empty($userId) || empty($userType))
			{
				throw new Exception("Invalid input", ReturnSet::ERROR_INVALID_DATA);
			}

			switch ($userType)
			{
				case UserInfo::TYPE_VENDOR:
					$sql		 = "SELECT ctt_id FROM vendors
									INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
									INNER JOIN contact ON ctt_id = cp.cr_contact_id AND ctt_id = ctt_ref_code AND ctt_active = 1
									WHERE vnd_id = :id AND vnd_active > 0";
					$contactId	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $userId]);

					break;

				case UserInfo::TYPE_DRIVER:
					$sql		 = "SELECT drv_contact_id FROM drivers WHERE drv_id = :id AND drv_id > 0";
					$contactId	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $userId]);

					break;
				case UserInfo::TYPE_CONSUMER:
					break;

				default:
					break;
			}

			$emailId		 = $authData->profile->email;
			$socialUserId	 = $authData->profile->id;
			$conResponse	 = ContactEmail::model()->updateContacts($contactId, $emailId, $provider);
			$contactProfile	 = ContactProfile::model()->findByContactId($contactId);

			if (isset($contactProfile->cr_is_driver) && !empty($contactProfile->cr_is_driver))
			{
				$drvModel				 = Drivers::model()->findByPk($contactProfile->cr_is_driver);
				$drvModel->drv_id		 = $contactProfile->cr_is_driver;
				$drvModel->drv_user_id	 = $socialUserId;

				if (!$drvModel->save())
				{
					throw new Exception(json_encode($drvModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				//Unlink other vendors
				$unlinkOtherDrivers = " UPDATE drivers SET drv_user_id = NULL WHERE drv_user_id = $socialUserId AND drv_id <> $contactProfile->cr_is_driver";
				DBUtil::command($unlinkOtherDrivers)->execute();
			}

			if (isset($contactProfile->cr_is_vendor) && !empty($contactProfile->cr_is_vendor))
			{
				$vndModel				 = Vendors::model()->findByPk($contactProfile->cr_is_vendor);
				$vndModel->vnd_id		 = $contactProfile->cr_is_vendor;
				$vndModel->vnd_user_id	 = $socialUserId;

				if (!$vndModel->save())
				{
					throw new Exception(json_encode($vndModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				//Update token
				$updateAppToken = " UPDATE app_tokens
								SET apt_user_id = $socialUserId
									WHERE `apt_entity_id` = $contactProfile->cr_is_vendor AND `apt_status` = 1 AND apt_token_id = '" . $authData->authId . "'";

				DBUtil::command($updateAppToken)->execute();

				//Unlink other vendors
				$unlinkOtherVendors = " UPDATE vendors SET vnd_user_id = NULL WHERE vnd_user_id = $socialUserId AND vnd_id <> $contactProfile->cr_is_vendor";
				DBUtil::command($unlinkOtherVendors)->execute();
			}




			$returnSet->setStatus(true);
			$returnSet->setMessage("Successfully linked your social account");
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
			$returnSet->setMessage("Failed");
		}

		return $returnSet;
	}

	/**
	 *
	 * @param type $jsonObj - Contact Data
	 * @param type $tempVal - 0-> Send email/phone verification Link 1->Don't send email/phone verification link
	 * @return type
	 */
	public static function createContact($jsonData, $tempVal = 0, $userType, $provider = 1)
	{
		$returnSet = new ReturnSet();
		try
		{

			$phone	 = Filter::processPhoneNumber($jsonData->profile->primaryContact->number, $jsonData->profile->primaryContact->code);
			$cttId	 = self::getIdByDetails($jsonData->profile->email, $phone);

			if ($cttId > 0)
			{
				Logger::info('Modify Contact ' . $cttId);
				Contact::modifyContact($jsonData, $cttId, 0, $userType, $provider);
				$returnSet->setData(["id" => $cttId]);
				goto End;
			}

			$primaryEmail	 = array(array('eml_email_address' => $jsonData->profile->email, 'eml_is_primary' => 1, 'eml_type' => $provider, 'eml_is_verified' => $isVerified));
			$primaryPhone	 = array(array('phn_phone_country_code' => $jsonData->profile->primaryContact->code, 'phn_phone_no' => $jsonData->profile->primaryContact->number, 'phn_is_primary' => 1));

			$contactModel					 = new Contact();
			$contactModel->ctt_first_name	 = $jsonData->profile->firstName;
			$contactModel->ctt_last_name	 = $jsonData->profile->lastName;
			$contactModel->contactEmails	 = $contactModel->convertToEmailObjects($primaryEmail);
			$contactModel->contactPhones	 = $contactModel->convertToPhoneObjects($primaryPhone);

			$returnSet = $contactModel->add($tempVal);
			//$contactModel->setProfile();
			End:
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function modifyContact($jsonObj, $cttId, $isApp = 0, $userType, $provider = 1)
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($jsonObj) || empty($cttId))
			{
				throw new Exception("Invalid input", ReturnSet::ERROR_INVALID_DATA);
			}
			/* @var $contactModel Contact */
			$contactModel					 = Contact::model()->findByPk($cttId);
			$contactModel->ctt_first_name	 = $jsonObj->profile->firstName;
			$contactModel->ctt_last_name	 = $jsonObj->profile->lastName;
			$contactModel->ctt_address		 = $jsonObj->profile->address;
			$contactModel->ctt_state		 = $jsonObj->profile->ctt_state;
			$contactModel->ctt_city			 = $jsonObj->profile->ctt_city;
			if ($isApp)
			{
				$contactModel->ctt_state = $jsonObj->profile->state;
				//$contactModel->ctt_address	 = $jsonObj->profile->address . " " . $jsonObj->profile->pincode;
			}

			$emailResponse = ContactEmail::model()->editContacts($cttId, $jsonObj->profile->email, $provider);
			if (!empty($jsonObj->profile->primaryContact->number))
			{
				$phNumber		 = "+" . $jsonObj->profile->primaryContact->code . $jsonObj->profile->primaryContact->number;
				$phoneResponse	 = ContactPhone::model()->updateContacts($cttId, $phNumber, $userType, $provider);
//				if ($phoneResponse->getStatus() == false)
//				{
//					$returnSet->setErrorCode($phoneResponse->getErrorCode());
//					$returnSet->setErrors($phoneResponse->getErrors());
//					goto skipModify;
//				}
				$phoneData		 = $phoneResponse->getData();
				ContactPhone::primaryToggle($phNumber, $cttId);
			}
			if (!empty($phoneData))
			{
				$isOtpSend = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $cttId, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_OTP, $userType, 0, $phoneData["otp"], $phoneData["ext"]);
			}
			$emailData = $emailResponse->getData();
			ContactEmail::primaryToggle($jsonObj->profile->email, $cttId);
			if (!empty($emailData))
			{
				$emailSend = Contact::sendVerification($jsonObj->profile->email, Contact::TYPE_EMAIL, $cttId, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_LINK, $userType);
			}

			$contactModel->save();

			/**
			 * update ref code for contact
			 */
			Contact::updateRefCode($cttId, $cttId);
			$desc = "Modified Contact Items";
			ContactLog::model()->createLog($cttId, $desc, ContactLog::CONTACT_MODIFIED, null);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			//$returnSet->setException($ex);
			$returnSet = ReturnSet::setException($ex);
		}

		//skipModify:

		return $returnSet;
	}

	/**
	 * This function is used for updating the Primary Status for Phone/email
	 * @param type $status
	 * @param type $phoneAddress
	 * @param type $emailAddress
	 * @param type $cttId
	 * @return \ReturnSet
	 */
	public static function setPrimary($status, $address, $cttId, $type = 0)
	{
		$returnSet = new ReturnSet();
		if ($type == 1)
		{
			try
			{
				if (empty($status) || empty($address) || empty($cttId))
				{
					throw new Exception("Invalid Parameters", ReturnSet::ERROR_INVALID_DATA);
				}
				$transaction = DBUtil::beginTransaction();
				/**
				 * changed all primary status to zero first.
				 */
				$sql		 = "UPDATE contact_phone set phn_is_primary = 0 where phn_contact_id = '$cttId'";
				DBUtil::query($sql);
				/**
				 * changed the primary status to 1 for that email address
				 */
				$sql		 = "UPDATE contact_phone set phn_is_primary = '$status' where phn_phone_no = '$address'";
				DBUtil::query($sql);
				$returnSet->setStatus(true);
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::error($ex->getMessage());
				$returnSet->setException($ex);
			}
			return $returnSet;
		}
		else
		{
			try
			{
				if (empty($status) || empty($address) || empty($cttId))
				{
					throw new Exception("Invalid Parameters", ReturnSet::ERROR_INVALID_DATA);
				}
				$transaction = DBUtil::beginTransaction();
				/**
				 * changed all primary status to zero first.
				 */
				$sql		 = "UPDATE contact_email set eml_is_primary = 0 where eml_contact_id = '$cttId'";
				DBUtil::query($sql);
				/**
				 * changed the primary status to 1 for that email address
				 */
				$sql		 = "UPDATE contact_email set eml_is_primary = '$status' where eml_email_address = '$address'";
				DBUtil::query($sql);
				$returnSet->setStatus(true);
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::error($ex->getMessage());
				$returnSet->setException($ex);
			}
			return $returnSet;
		}
	}

	public static function getContactIdByLicense($licenseNo)
	{
		$sql	 = "SELECT ctt_id FROM contact WHERE ctt_license_no = '$licenseNo' AND ctt_active = 1 ";
		$cttId	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		return $cttId;
	}

	/**
	 * This function is used for finding the duplicate Ids in vendor and d
	 * @param type $contactIds
	 * @return CDbDataReader
	 * @throws Exception
	 */
	public static function getDuplicateList($contactIds)
	{
		$ids = $contactIds;
		if (empty($contactIds))
		{
			throw new Exception("Data not passed", ReturnSet::ERROR_INVALID_DATA);
		}

		$sqlData = "SELECT ctt_id, drv_id, drs_last_logged_in, drs_total_trips, drv_ref_code, drv_contact_id,
					IF(ctt_ref_code=ctt_id, 2, IF(ctt_ref_code IS NULL, 1, 0)) as primaryCtt,
					vnd_id,vrs_last_logged_in,vrs_vnd_total_trip,vnd_ref_code,
					(SUM(IF(drv_id IS NOT NULL AND drv_approved=1,1,0) + IF(vnd_id IS NOT NULL AND vnd_active=1,1,0))) as totalApproved,
					(SUM(IF(drv_id IS NOT NULL,1,0) + IF(vnd_id IS NOT NULL,1,0))) as totalEntity,
					(IF(ldoc.doc_status=1,1,0)+ IF(pdoc.doc_status=1,1,0) +IF(vdoc.doc_status=1,1,0) +IF(adoc.doc_status=1,1,0)) as docCnt,
					GREATEST(IFNULL(vs.vrs_last_logged_in,'1970-01-01'), IFNULL(driver_stats.drs_last_logged_in,'1970-01-01') ) as lastLogin,
					(IFNULL(vs.vrs_vnd_total_trip,0) + IFNULL(driver_stats.drs_total_trips,0)) as totalTrips
					FROM contact ct
					INNER JOIN contact_profile as cp on cp.cr_contact_id = ct.ctt_id and ct.ctt_active = 1 and ct.ctt_id =ct.ctt_ref_code
					LEFT JOIN drivers as drv on drv.drv_id = cp.cr_is_driver and cp.cr_status =1 and drv.drv_id = drv.drv_ref_code
					LEFT JOIN vendors as vnd on vnd.vnd_id = cp.cr_is_vendor and cp.cr_status =1 and vnd.vnd_id = vnd.vnd_ref_code
					LEFT JOIN vendor_stats vs ON vnd.vnd_id = vs.vrs_vnd_id
					LEFT JOIN driver_stats ON drv.drv_id = driver_stats.drs_drv_id
					LEFT JOIN document ldoc on ldoc.doc_id = ct.ctt_license_doc_id AND ldoc.doc_active=1
					LEFT JOIN document pdoc on pdoc.doc_id = ct.ctt_pan_doc_id AND pdoc.doc_active=1
					LEFT JOIN document adoc on adoc.doc_id = ct.ctt_aadhar_doc_id AND adoc.doc_active=1
					LEFT JOIN document vdoc on vdoc.doc_id = ct.ctt_voter_doc_id AND vdoc.doc_active=1
					WHERE ct.ctt_id IN ($ids)
					GROUP BY ctt_id
					ORDER BY primaryCtt DESC, docCnt desc, totalEntity DESC, lastLogin desc, totalTrips DESC";

		$drDetails = DBUtil::query($sqlData);

		return $drDetails;
	}

	/**
	 *
	 * @param type $primaryConId
	 * @param type $duplicateConId
	 */
	public static function mergeIds($primaryConId, $duplicateConId, $source = null)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (empty($primaryConId) || empty($duplicateConId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}
			self::updateConIds($primaryConId, $duplicateConId, $source);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			DBUtil::rollbackTransaction($transaction);
		}
	}

	/**
	 *
	 * @param type $primaryConId
	 * @param type $duplicateConId
	 * @throws Exception
	 */
	public static function updateConIds($primaryConId, $duplicateConId, $source = null)
	{
		if (empty($primaryConId) || empty($duplicateConId))
		{
			throw new Exception("Invalid data passed", ReturnSet::ERROR_INVALID_DATA);
		}

		$isEligible = self::checkMergeEligiblity($primaryConId, $duplicateConId);

		if (!$isEligible)
		{
			ContactMergeRemarks::setManualMerge($primaryConId, $duplicateConId);
			goto skipMerge;
		}

		ContactMerged::getByIds($primaryConId, $duplicateConId);
		self::getDuplicateMatchingDoc($primaryConId, $duplicateConId);

		self::mergeDocData($primaryConId, $duplicateConId);

		Vendors::mergeConIds($primaryConId, $duplicateConId, $source);

		Drivers::mergeConIds($primaryConId, $duplicateConId, $source);
		Users::mergeConIds($primaryConId, $duplicateConId);

		self::updateRefCode($primaryConId, $duplicateConId);
		//self::deactive($duplicateConId);
//		ContactProfile::deactivate($duplicateConId);
//		ContactProfile::activate($primaryConId);

		$docType = "";
		if ($source == Document::Document_Licence)
		{
			$docType = "(Driving License matched)";
		}
		if ($source == Document::Document_Pan)
		{
			$docType = "(PAN matched)";
		}

		$desc = "Merged:  $duplicateConId  is merged with $primaryConId $docType";
		ContactLog::model()->createLog($primaryConId, $desc, ContactLog::CONTACT_MERGE, null);
		echo $desc . '<br>\n';

		skipMerge:
	}

	/**
	 * Update ref code
	 * @param type $primaryConId
	 * @param type $duplicateConId
	 * @throws Exception
	 */
	public static function updateRefCode($primaryConId, $duplicateConId)
	{
		if (empty($primaryConId) || empty($duplicateConId))
		{
			throw new Exception("Invalid data passed", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql	 = "Update contact set ctt_ref_code = $primaryConId WHERE ctt_id = $duplicateConId";
		$result	 = DBUtil::command($sql)->execute();
	}

	public static function getDuplicateMatchingDoc($primaryConId, $duplicateConId)
	{
		$sql = " SELECT cttPrimary.ctt_id as pcttId, cttDuplicate.ctt_id dcttId, vPrimary.doc_id as vPDoc, aPrimary.doc_id as aPDoc, pPrimary.doc_id as pPDoc, lPrimary.doc_id as lPDoc,
				vDuplicate.doc_id as vDDoc, aDuplicate.doc_id as aDDoc, pDuplicate.doc_id as pDDoc, lDuplicate.doc_id as lDDoc
			FROM   contact cttPrimary
				  LEFT JOIN document vPrimary
					ON cttPrimary.ctt_voter_doc_id = vPrimary.doc_id AND vPrimary.doc_status in (0, 1) AND vPrimary.doc_active = 1
				  LEFT JOIN document aPrimary
					ON cttPrimary.ctt_pan_doc_id = aPrimary.doc_id AND aPrimary.doc_status in (0, 1) AND aPrimary.doc_active = 1
				  LEFT JOIN document pPrimary
					ON cttPrimary.ctt_aadhar_doc_id = pPrimary.doc_id AND pPrimary.doc_status in (0, 1) AND pPrimary.doc_active = 1
				  LEFT JOIN document lPrimary
					ON cttPrimary.ctt_license_doc_id = lPrimary.doc_id AND lPrimary.doc_status in (0, 1) AND lPrimary.doc_active = 1
				  INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id = $duplicateConId
				  LEFT JOIN document vDuplicate
					ON cttDuplicate.ctt_voter_doc_id = vDuplicate.doc_id AND vDuplicate.doc_status in (0, 1) AND vDuplicate.doc_active = 1
				  LEFT JOIN document aDuplicate
					ON cttDuplicate.ctt_pan_doc_id = aDuplicate.doc_id AND aDuplicate.doc_status in (0, 1) AND aDuplicate.doc_active = 1
				  LEFT JOIN document pDuplicate
					ON cttDuplicate.ctt_aadhar_doc_id = pDuplicate.doc_id AND pDuplicate.doc_status in (0, 1) AND pDuplicate.doc_active = 1
				  LEFT JOIN document lDuplicate
					ON cttDuplicate.ctt_license_doc_id = lDuplicate.doc_id AND lDuplicate.doc_status in (0, 1) AND lDuplicate.doc_active = 1
			 WHERE  1";

		$drDetails = DBUtil::query($sql);

		ContactMerged::updateFlag($drDetails);
	}

	/**
	 *
	 * @param type $primaryConId
	 * @param type $duplicateConId
	 * @throws Exception
	 */
	public static function checkMergeEligiblity($primaryConId, $duplicateConId)
	{
		if (empty($primaryConId) || empty($duplicateConId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$verifyDetails = "	SELECT IF(cttPrimary.ctt_voter_no != '' AND cttPrimary.ctt_voter_no IS NOT NULL AND cttDuplicate.ctt_voter_no != '' AND cttDuplicate.ctt_voter_no IS NOT NULL AND TRIM(cttDuplicate.ctt_voter_no) <> TRIM(cttPrimary.ctt_voter_no) AND vDuplicate.doc_id IS NOT NULL AND vPrimary.doc_id IS NOT NULL, 1, 0)
					AS verifyVoter,
				  IF(cttPrimary.ctt_aadhaar_no != '' AND cttPrimary.ctt_aadhaar_no IS NOT NULL AND cttDuplicate.ctt_aadhaar_no != '' AND cttDuplicate.ctt_aadhaar_no IS NOT NULL AND TRIM(cttDuplicate.ctt_aadhaar_no) <> TRIM(cttPrimary.ctt_aadhaar_no) AND aDuplicate.doc_id IS NOT NULL AND aPrimary.doc_id IS NOT NULL, 1, 0)
					AS verifyAadhar,
				  IF(cttPrimary.ctt_pan_no != '' AND cttPrimary.ctt_pan_no IS NOT NULL AND cttDuplicate.ctt_pan_no != '' AND cttDuplicate.ctt_pan_no IS NOT NULL AND TRIM(cttDuplicate.ctt_pan_no) <> TRIM(cttPrimary.ctt_pan_no) AND pDuplicate.doc_id IS NOT NULL AND pPrimary.doc_id IS NOT NULL, 1, 0)
					AS verifyPan,
				  IF(cttPrimary.ctt_license_no != '' AND cttPrimary.ctt_license_no IS NOT NULL AND cttDuplicate.ctt_license_no != '' AND cttDuplicate.ctt_license_no IS NOT NULL AND TRIM(cttDuplicate.ctt_license_no) <> TRIM(cttPrimary.ctt_license_no) AND lDuplicate.doc_id IS NOT NULL AND lPrimary.doc_id IS NOT NULL, 1, 0)
					AS verifyLicence
			FROM   contact cttPrimary
				  LEFT JOIN document vPrimary
					ON cttPrimary.ctt_voter_doc_id = vPrimary.doc_id AND vPrimary.doc_status = 1 AND vPrimary.doc_active = 1
				  LEFT JOIN document aPrimary
					ON cttPrimary.ctt_pan_doc_id = aPrimary.doc_id AND aPrimary.doc_status = 1 AND aPrimary.doc_active = 1
				  LEFT JOIN document pPrimary
					ON cttPrimary.ctt_aadhar_doc_id = pPrimary.doc_id AND pPrimary.doc_status = 1 AND pPrimary.doc_active = 1
				  LEFT JOIN document lPrimary
					ON cttPrimary.ctt_license_doc_id = lPrimary.doc_id AND lPrimary.doc_status = 1 AND lPrimary.doc_active = 1
				  INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id = $duplicateConId
				  LEFT JOIN document vDuplicate
					ON cttDuplicate.ctt_voter_doc_id = vDuplicate.doc_id AND vDuplicate.doc_status = 1 AND vDuplicate.doc_active = 1
				  LEFT JOIN document aDuplicate
					ON cttDuplicate.ctt_pan_doc_id = aDuplicate.doc_id AND aDuplicate.doc_status = 1 AND aDuplicate.doc_active = 1
				  LEFT JOIN document pDuplicate
					ON cttDuplicate.ctt_aadhar_doc_id = pDuplicate.doc_id AND pDuplicate.doc_status = 1 AND pDuplicate.doc_active = 1
				  LEFT JOIN document lDuplicate
					ON cttDuplicate.ctt_license_doc_id = lDuplicate.doc_id AND lDuplicate.doc_status = 1 AND lDuplicate.doc_active = 1
			 WHERE  1 HAVING (verifyVoter + verifyPan + verifyAadhar + verifyLicence)=0
				";

		$objData = DBUtil::query($verifyDetails);

		return ($objData->getRowCount() > 0);
	}

	/**
	 * 
	 * @param integer $contactId
	 * @param string $reason
	 * @return ReturnSet
	 * @throws Exception
	 * @deprecated
	 */
	public static function deactivate($contactId, $reason = null)
	{
		$returnSet = new ReturnSet();
		if (empty($contactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

//		if(ContactProfile::isDriver($contactId) || ContactProfile::isVendor($contactId))
//		{
//			throw new Exception("Profile is linked to existing vendor/driver account. Please contact gozo support", ReturnSet::ERROR_VALIDATION);
//		}

		$transaction = DBUtil::beginTransaction();
		try
		{

//			$param				 = [':contactId' => $contactId];
//			$deactivateContact	 = "UPDATE `contact` SET ctt_active = 2 WHERE ctt_id  IN (:contactId)";
//			DBUtil::execute($deactivateContact, $param);

			ContactEmail::unlink("", $contactId);
			ContactPhone::unlink($contactId);

			$desc = "Contact deactivated.";

			if ($reason != null)
			{
				$desc .= " Reason: " . $reason;
			}
			ContactLog::model()->createLog($contactId, $desc, ContactLog::CONTACT_INACTIVE);
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($exc);
			$returnSet->setMessage($exc->getMessage());
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $contactId
	 * @param string $reason
	 * @return ReturnSet
	 * @throws Exception
	 * Deactivate All phones 
	 * Deactivate All emails  
	 */
	public static function deactivateV1($contactId, $reason = null)
	{
		$returnSet = new ReturnSet();
		if (empty($contactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$transaction = DBUtil::beginTransaction();
		try
		{

			ContactPhone::unlinkContactsById($contactId, $reason);

			ContactEmail::unlinkContactsById($contactId, $reason);

			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($exc);
			$returnSet->setMessage($exc->getMessage());
		}
		return $returnSet;
	}

	/**
	 * This function validates the document
	 * @param type $refValue
	 * @param type $refType
	 * @return type
	 * @throws Exception
	 */
	public static function checkDoc($refValue, $refType)
	{
		if (empty($refValue) && empty($refType))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$data = trim($refValue);
		switch ($refType)
		{
			case Document::Document_Licence:
				$response = Contact::checkLicenseNo($data);
				break;

			case Document::Document_Aadhar:
				$response = Contact::checkAadhaarNo($data);
				break;

			case Document::Document_Pan:
				$response	 = Contact::model()->checkPanNo($data);
				break;
			case Document::Document_Voter:
				$response	 = Contact::model()->checkVoterNo($data);
			default:
				break;
		}

		return $response;
	}

	/**
	 * This function updates the documents details to the primary conIds if missing
	 * @param type $primaryConId
	 * @param type $duplicateConId
	 */
	public static function mergeDocData($primaryConId, $duplicateConId)
	{
		try
		{
			$updateVoterId = "	UPDATE contact cttPrimary
				LEFT JOIN document dprimary ON cttPrimary.ctt_voter_doc_id=dprimary.doc_id
				INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id=$duplicateConId
				LEFT JOIN document dDuplicate ON cttDuplicate.ctt_voter_doc_id=dDuplicate.doc_id
					SET    cttPrimary.`ctt_voter_no`=IF(cttDuplicate.ctt_voter_no IS NOT NULL AND cttDuplicate.ctt_voter_no<>''
														AND (dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) OR ((cttPrimary.ctt_voter_no IS NULL OR cttPrimary.ctt_voter_no='')))
													, cttDuplicate.ctt_voter_no, cttPrimary.`ctt_voter_no`),
						cttPrimary.ctt_voter_doc_id = IF(dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) AND (dprimary.doc_status=2 OR dprimary.doc_id IS NULL OR (dprimary.doc_status=0 AND dDuplicate.doc_status=1))
														,  dDuplicate.doc_id, cttPrimary.ctt_voter_doc_id)
				WHERE  (cttPrimary.ctt_voter_no IS NULL OR cttPrimary.ctt_voter_no='' OR
						((dprimary.doc_status=2 OR dprimary.doc_id IS NULL) AND dDuplicate.doc_status IN (0,1)))";

			DBUtil::command($updateVoterId)->execute();

			$updateAdhaarId = "	UPDATE contact cttPrimary
				LEFT JOIN document dprimary ON cttPrimary.ctt_aadhar_doc_id=dprimary.doc_id
				INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id=$duplicateConId
				LEFT JOIN document dDuplicate ON cttDuplicate.ctt_aadhar_doc_id=dDuplicate.doc_id
					SET    cttPrimary.`ctt_aadhaar_no`=IF(cttDuplicate.ctt_aadhaar_no IS NOT NULL AND cttDuplicate.ctt_aadhaar_no<>''
														AND (dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) OR ((cttPrimary.ctt_aadhaar_no IS NULL OR cttPrimary.ctt_aadhaar_no='')))
													, cttDuplicate.ctt_aadhaar_no, cttPrimary.`ctt_aadhaar_no`),
						cttPrimary.ctt_aadhar_doc_id = IF(dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) AND (dprimary.doc_status=2 OR dprimary.doc_id IS NULL OR (dprimary.doc_status=0 AND dDuplicate.doc_status=1))
														,  dDuplicate.doc_id, cttPrimary.ctt_aadhar_doc_id)
				WHERE  (cttPrimary.ctt_aadhaar_no IS NULL OR cttPrimary.ctt_aadhaar_no='' OR
						((dprimary.doc_status=2 OR dprimary.doc_id IS NULL) AND dDuplicate.doc_status IN (0,1)));";

			DBUtil::command($updateAdhaarId)->execute();

			$updatePanNoId = "	UPDATE contact cttPrimary
				LEFT JOIN document dprimary ON cttPrimary.ctt_pan_doc_id=dprimary.doc_id
				INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id=$duplicateConId
				LEFT JOIN document dDuplicate ON cttDuplicate.ctt_pan_doc_id=dDuplicate.doc_id
					SET    cttPrimary.`ctt_pan_no`=IF(cttDuplicate.ctt_pan_no IS NOT NULL AND cttDuplicate.ctt_pan_no<>''
														AND (dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) OR ((cttPrimary.ctt_pan_no IS NULL OR cttPrimary.ctt_pan_no='')))
													, cttDuplicate.ctt_pan_no, cttPrimary.`ctt_pan_no`),
						cttPrimary.ctt_pan_doc_id = IF(dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) AND (dprimary.doc_status=2 OR dprimary.doc_id IS NULL OR (dprimary.doc_status=0 AND dDuplicate.doc_status=1))
														,  dDuplicate.doc_id, cttPrimary.ctt_pan_doc_id)
				WHERE  (cttPrimary.ctt_pan_no IS NULL OR cttPrimary.ctt_pan_no='' OR
						((dprimary.doc_status=2 OR dprimary.doc_id IS NULL) AND dDuplicate.doc_status IN (0,1)))";

			DBUtil::command($updatePanNoId)->execute();

			$updateLicId = "	UPDATE contact cttPrimary
				LEFT JOIN document dprimary ON cttPrimary.ctt_license_doc_id=dprimary.doc_id
				INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id=$duplicateConId
				LEFT JOIN document dDuplicate ON cttDuplicate.ctt_license_doc_id=dDuplicate.doc_id
					SET    cttPrimary.`ctt_license_no`=IF(cttDuplicate.ctt_license_no IS NOT NULL AND cttDuplicate.ctt_license_no<>''
														AND (dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) OR ((cttPrimary.ctt_license_no IS NULL OR cttPrimary.ctt_license_no='')))
													, cttDuplicate.ctt_license_no, cttPrimary.`ctt_license_no`),
						cttPrimary.ctt_license_doc_id = IF(dDuplicate.doc_id IS NOT NULL AND dDuplicate.doc_status IN (0,1) AND (dprimary.doc_status=2 OR dprimary.doc_id IS NULL OR (dprimary.doc_status=0 AND dDuplicate.doc_status=1))
														,  dDuplicate.doc_id, cttPrimary.ctt_license_doc_id)
				WHERE  (cttPrimary.ctt_license_no IS NULL OR cttPrimary.ctt_license_no='' OR
						((dprimary.doc_status=2 OR dprimary.doc_id IS NULL) AND dDuplicate.doc_status IN (0,1)))";

			DBUtil::command($updateLicId)->execute();
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
		}
	}

	/**
	 * Updates primary band details of the primary contact Ids
	 * @param type $primaryConId
	 * @param type $duplicateConId
	 * @throws Exception
	 */
	public static function updateBankDetails($primaryConId, $duplicateConId)
	{
		if (empty($primaryConId) || empty($duplicateConId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		//Bank Name
		$updatePrimaryBankData = "
			UPDATE contact cttPrimary
			INNER JOIN contact cttDuplicate ON cttPrimary.ctt_id = $primaryConId AND cttDuplicate.ctt_id=$duplicateConId
			SET cttPrimary.`ctt_bank_name` = IF(cttPrimary.ctt_bank_name IS NULL OR cttPrimary.ctt_bank_name = '', cttDuplicate.ctt_bank_name, cttPrimary.ctt_bank_name),
				cttPrimary.`ctt_bank_branch` = IF(cttPrimary.ctt_bank_branch IS NULL OR cttPrimary.ctt_bank_branch='', cttDuplicate.ctt_bank_branch, cttPrimary.ctt_bank_branch),
				cttPrimary.`ctt_bank_account_no` = IF(cttPrimary.ctt_bank_account_no IS NULL OR cttPrimary.ctt_bank_account_no='', cttDuplicate.ctt_bank_account_no, cttPrimary.ctt_bank_account_no),
				cttPrimary.`ctt_bank_ifsc` = IF(cttPrimary.ctt_bank_ifsc IS NULL OR cttPrimary.ctt_bank_ifsc='', cttDuplicate.ctt_bank_ifsc, cttPrimary.ctt_bank_ifsc),
				cttPrimary.`ctt_beneficiary_name` = IF(cttPrimary.ctt_beneficiary_name IS NULL OR cttPrimary.ctt_beneficiary_name='', cttDuplicate.ctt_beneficiary_name, cttPrimary.ctt_beneficiary_name),
                cttPrimary.`ctt_beneficiary_id` = IF(cttPrimary.ctt_beneficiary_id IS NULL OR cttPrimary.ctt_beneficiary_id='', cttDuplicate.ctt_beneficiary_id, cttPrimary.ctt_beneficiary_id)
			WHERE (cttPrimary.ctt_bank_name IS NULL OR cttPrimary.ctt_bank_name='') AND (cttPrimary.ctt_bank_ifsc IS NULL OR cttPrimary.ctt_bank_ifsc='')
			";

		$numBNrows = DBUtil::command($updatePrimaryBankData)->execute();
		Logger::trace("Contact::updateBankDetails->updatePrimaryBankData=$numBNrows");
	}

	/**
	 * This function is validating contact merged status
	 * @param int $primaryContactId
	 * @return boolean
	 * @throws Exception
	 */
	public static function isContactMerged($primaryContactId = null)
	{
		if (empty($primaryContactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$params	 = ["id" => $primaryContactId];
		$sql	 = "SELECT COUNT(1) FROM contact WHERE ctt_ref_code=:id AND ctt_active=1";
		$count	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return ($count > 0);
	}

	public static function getDuplicateDetails($arr, $cttId)
	{
		$sql = "SELECT ctt_id,
				ctt_first_name,
				ctt_last_name,
				ctt_license_no,
				ctt_pan_no,
				ctt_bank_name,
				ctt_bank_branch,
				ctt_bank_account_no,
				ctt_bank_ifsc,
				ctt_beneficiary_name,
				ctt_beneficiary_id
				FROM contact
				WHERE ctt_active = 1 AND ctt_ref_code = $cttId";

		$sqlCount		 = "SELECT COUNT(*) FROM contact WHERE ctt_active = 1 AND ctt_ref_code = $cttId";
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, ['db' => DBUtil::SDB(), 'totalItemCount' => $count, 'sort' => ['attributes' => ['ctt_id'], 'defaultOrder' => ''], 'pagination' => ['pageSize' => 100]]);
		return $dataprovider;
	}

	public function getUser($createIfNotExist = true)
	{
		$contactId	 = $this->ctt_id;
		$isProfile	 = $userId		 = ContactProfile::getUserId($contactId);
		if (!$userId)
		{
			$userId = Users::getByContactId($contactId);
		}

		if (!$userId && $createIfNotExist)
		{
			$userModel	 = Users::createbyContact($this);
			$userId		 = $userModel->user_id;
		}
		if ($userId > 0 && !$isProfile)
		{
			ContactProfile::updateEntity($contactId, $userId, UserInfo::TYPE_CONSUMER);
		}

		return $userId;
	}

	/**
	 *
	 * @param type $docId
	 * @param type $docType
	 * @param type $arrContactModel
	 * @throws Exception
	 */
	public static function copyDocDetails($docId = null, $docType = null, $primary = null, $duplicate = null, $status)
	{
		$returnSet = new ReturnSet();
		if ($docId == null || $docType == null || $duplicate == null)
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		switch ($docType)
		{
			case Document::Document_Voter:
				$attributeCol1	 = "ctt_voter_no";
				$attributeCol2	 = "ctt_voter_doc_id";
				break;

			case Document::Document_Aadhar:
				$attributeCol1	 = "ctt_aadhaar_no";
				$attributeCol2	 = "ctt_aadhar_doc_id";
				break;

			case Document::Document_Licence:
				$attributeCol1	 = "ctt_license_no";
				$attributeCol2	 = "ctt_license_doc_id";
				break;

			case Document::Document_Pan:
				$attributeCol1	 = "ctt_pan_no";
				$attributeCol2	 = "ctt_pan_doc_id";
				break;
		}

		if ($status)
		{
			$updatePrimaryDocRefValue = "
			 UPDATE Contact SET contact.$attributeCol2 = $docId where ctt_id = $primary;
			";
		}
		$numrows = DBUtil::command($updatePrimaryDocRefValue)->execute();

		if ($numrows >= 0)
		{
			$returnSet->setStatus(true);
		}

		return $returnSet;
	}

	public static function updateData($data)
	{
		$returnset	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$cttId = trim($data['id']);
			if (empty($cttId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$model	 = Contact::model()->findByPk($cttId);
			$oldData = $model->attributes;
			if ($model == null)
			{
				throw new Exception("Contact not found", ReturnSet::ERROR_INVALID_DATA);
			}
			$model->ctt_first_name		 = trim($data['firstName']);
			$model->ctt_last_name		 = trim($data['lastName']);
			$model->ctt_business_name	 = trim($data['businessName']);
			$model->ctt_aadhaar_no		 = trim($data['aadhaar']);
			$model->ctt_address			 = trim($data['address']);
			$model->ctt_bank_name		 = trim($data['bankname']);
			$model->ctt_bank_branch		 = trim($data['bankbranch']);
			$model->ctt_bank_account_no	 = trim($data['bankaccount']);
			$model->ctt_bank_ifsc		 = trim($data['bankifsc']);
			$model->ctt_beneficiary_name = trim($data['benificiaryname']);
			$model->ctt_license_no		 = trim($data['license']);
			$model->ctt_voter_no		 = trim($data['voter']);
			$model->ctt_pan_no			 = trim($data['panno']);
			if (!$model->update())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$newData = $model->attributes;
			$this->checkBankDetailsUpdate($oldData, $newData);
			$returnset->setStatus(true);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnset;
	}

	/**
	 * Checks whether the user is eligible for user update or not
	 * @param string $emailId
	 * @return bool
	 */
	public static function getIdByDetails($emailId, $phoneNo, $firstName = null, $lastName = null, $verified = true)
	{
		$cttId		 = 0;
		$emlCttIds	 = ContactEmail::getData($emailId, $verified);

		if ($phoneNo != null)
		{
			$phCttIds = ContactPhone::getData($phoneNo, $verified);
		}

		if (isset($emlCttIds))
		{
			$cttId = $emlCttIds;
		}
		if (isset($phCttIds))
		{
			$cttId = $phCttIds;
		}
		if (isset($emlCttIds) && isset($phCttIds))
		{
			$cttId = trim("{$emlCttIds},{$phCttIds}", ",");
		}

		$contactId = self::getTopMatchingContact(explode(",", $cttId), $phoneNo, $emailId, $firstName, $lastName);

		return $contactId;
	}

	/**
	 * Get Top Contact from given contact ids using additional details
	 * @param array $selectContacts List of contact ids in which top contact has to be selected
	 *
	 * @return int|false return false if not contact matched
	 *  */
	public static function getTopMatchingContact($selectContacts, $phone, $email, $firstName = null, $lastName = null)
	{
		$cttIds		 = implode(",", $selectContacts);
		DBUtil::getINStatement($cttIds, $bindString, $params1);
		$params		 = array();
		$selectQry	 = "";
		if ($firstName != "")
		{
			$params['firstName'] = $firstName;
			$selectQry			 .= "IF(SOUNDEX(ctt.ctt_first_name)=SOUNDEX(:firstName),1,0) as firstNameScore, ";
		}
		else
		{
			$selectQry .= "0 as firstNameScore, ";
		}

		if ($lastName != "")
		{
			$params['lastName']	 = $lastName;
			$selectQry			 .= "IF(SOUNDEX(ctt.ctt_last_name)=SOUNDEX(:lastName),1,0) as lastNameScore, ";
		}
		else
		{
			$selectQry .= "0 as lastNameScore, ";
		}
		$params['phone'] = $phone;
		$params['email'] = $email;
		$sql			 = "SELECT ctt1.ctt_id, {$selectQry}
							IF(cp.phn_is_primary=1, 1, 0) as phonePrimaryScore, IF(ce.eml_is_primary=1, 1,0) as emailPrimaryScore,
							IF(cp.phn_is_verified>0, 2,0) as phoneVerifyScore, IF(ce.eml_is_verified>0, 2,0) as emailVerifyScore,
							cp.phn_is_verified, ce.eml_is_verified
							FROM contact ctt
							INNER JOIN contact ctt1 ON ctt.ctt_ref_code=ctt1.ctt_id AND ctt1.ctt_active=1
							LEFT JOIN contact_phone cp ON cp.phn_contact_id=ctt.ctt_id AND cp.phn_active=1 AND cp.phn_phone_no IS NOT NULL AND cp.phn_phone_no<>'' AND cp.phn_full_number=:phone
							LEFT JOIN contact_email ce ON ce.eml_contact_id=ctt.ctt_id AND ce.eml_active=1 AND ce.eml_email_address IS NOT NULL AND ce.eml_email_address<>'' AND ce.eml_email_address=:email
							WHERE ctt.ctt_id IN ($bindString)  AND ctt.ctt_active=1 AND (cp.phn_id IS NOT NULL OR ce.eml_id IS NOT NULL)
							HAVING ((lastNameScore>0 AND firstNameScore>0) OR phoneVerifyScore>0 OR emailVerifyScore>0 OR phonePrimaryScore >0 OR emailPrimaryScore>0)
							ORDER BY (lastNameScore + firstNameScore + phonePrimaryScore + phoneVerifyScore + emailPrimaryScore + emailVerifyScore) DESC, firstNameScore DESC, lastNameScore DESC, phoneVerifyScore DESC, emailVerifyScore DESC";
		$contactId		 = DBUtil::queryScalar($sql, DBUtil::MDB(), array_merge($params, $params1));
		Logger::info($sql . " \n params: " . json_encode($params) . " \n params1: " . json_encode($params1));
		return $contactId;
	}

	/** @param \Stub\common\Document $doc */
	public function setLicenseData($doc)
	{
		//$this->ctt_license_no			 = $doc->refValue;
		$this->ctt_license_no			 = str_replace(' ', '', $doc->refValue); // remove space
		$this->ctt_license_issue_date	 = $doc->issueDate;
		$this->ctt_license_exp_date		 = $doc->expiryDate;
		//$this->ctt_license_doc_id		 = $doc->id;
		$docModel						 = Document::model()->saveDoc($doc, 5);
		$this->ctt_license_doc_id		 = $docModel->doc_id;
	}

	/** @param \Stub\common\Document $doc */
	public function setAadharData($doc)
	{
		$this->ctt_aadhaar_no = $doc->refValue;
	}

	/** @param \Stub\common\Document $doc */
	public function setPanData($doc)
	{
		$this->ctt_pan_no		 = $doc->refValue;
		$docModel				 = Document::model()->saveDoc($doc, 4);
		$this->ctt_pan_doc_id	 = $docModel->doc_id;
	}

	/** @param \Stub\common\Document $doc */
	public function setVoterData($doc)
	{
		$this->ctt_voter_no		 = $doc->refValue;
		$docModel				 = Document::model()->saveDoc($doc, 2);
		$this->ctt_voter_doc_id	 = $docModel->doc_id;
	}

	/**
	 * Creates contact by userdata
	 * @param Users $usrModel
	 * @return type
	 */
	public static function createByUser($usrModel, $provider = 1, $tempValue = 1)
	{
		Logger::profile("contact:createByUser Started");
		if ($provider > 1)
		{
			$tempValue = 0;
		}
		$primaryEmail	 = array(array('eml_email_address' => trim(str_replace(' ', '', $usrModel->usr_email)), 'eml_is_primary' => 1, 'eml_type' => $provider, 'eml_is_verified' => $usrModel->usr_email_verify));
		$primaryPhone	 = array(array('phn_phone_no' => trim(str_replace(' ', '', $usrModel->usr_mobile)), 'phn_is_primary' => 1, 'phn_is_verified' => $usrModel->usr_mobile_verify));

		$contactModel					 = new Contact();
		$cityData						 = [];
		$stateId						 = $usrModel->usr_state ? $usrModel->usr_state : "";
		$contactModel->ctt_first_name	 = $usrModel->usr_name;
		$contactModel->ctt_last_name	 = $usrModel->usr_lname;
		$contactModel->ctt_address		 = $usrModel->usr_address1 . " " . $usrModel->usr_address2 . " " . $usrModel->usr_address3 . " " . $usrModel->usr_zip;
		$contactModel->ctt_state		 = $stateId;
		if ($usrModel->usr_city && $stateId)
		{
			Logger::profile("Cities::findByCityName Started");
			$cityData = Cities::findByCityName($usrModel->usr_city, $stateId);
			Logger::profile("Cities::findByCityName Ended");
		}
		$contactModel->ctt_city		 = $cityData['cty_id'];
		Logger::profile("Contact::convertToEmailObjects Started");
		$contactModel->contactEmails = $contactModel->convertToEmailObjects($primaryEmail);
		Logger::profile("Contact::convertToEmailObjects Ended");
		Logger::profile("Contact::convertToPhoneObjects Started");
		$contactModel->contactPhones = $contactModel->convertToPhoneObjects($primaryPhone);
		Logger::profile("Contact::convertToPhoneObjects Ended");
		$returnSet					 = $contactModel->createUser(2); //1=For not sending verification
		$cttId						 = $returnSet->getData()['id'];
		if ($usrModel->user_id > 0)
		{
			Users::updateContactId($cttId, $usrModel->user_id);
			$contactModel->userId = $usrModel->user_id;
		}
		$contactModel->setProfile();

		Logger::profile("contact:createByUser Ended");
		return $cttId;
	}

	/**
	 * Creates contact bkgUserdata
	 * @param type $bkgusrData
	 * @return type
	 */
	public static function createbyBookingUser($bkguser, $agentId = '', $forceCreate = true)
	{
		/** @var BookingUser $bkguser */
		$email		 = $bkguser->bkg_user_email ? $bkguser->bkg_user_email : "";
		$phone		 = $bkguser->getFullContactNumber();
		$firstName	 = $bkguser->bkg_user_fname ? $bkguser->bkg_user_fname : "";
		$lastName	 = $bkguser->bkg_user_lname ? $bkguser->bkg_user_lname : "";

		if ($bkguser->bkg_contact_id > 0)
		{
			$cttId = $bkguser->bkg_contact_id;
		}
		else
		{
			$cttId = self::getIdByDetails($email, $phone, $firstName, $lastName, false);
		}
		if ($bkguser->buiBkg->bkg_agent_id > 0)
		{
			goto skipAll;
		}
		if ($cttId > 0)
		{
			self::modifiedByBookingUser($bkguser, $cttId, 1);
			goto skipAll;
		}
		$primaryEmail	 = array(array('eml_email_address' => trim(str_replace(' ', '', $email)), 'eml_is_primary' => 1));
		$primaryPhone	 = array(array('phn_phone_country_code' => $bkguser->bkg_country_code, 'phn_phone_no' => trim(str_replace(' ', '', $bkguser->bkg_contact_no)), 'phn_is_primary' => 1));

		$cttId = Contact::checkExistingInfoByUser($bkguser);
		if ($cttId != '')
		{
			$contactModel = Contact::model()->findByPk($cttId);
		}
		else
		{
			$contactModel					 = new Contact();
			$contactModel->ctt_first_name	 = $bkguser->bkg_user_fname;
			$contactModel->ctt_last_name	 = $bkguser->bkg_user_lname;
		}

		$contactModel->contactEmails = $contactModel->convertToEmailObjects($primaryEmail);
		$contactModel->contactPhones = $contactModel->convertToPhoneObjects($primaryPhone);

		if ($cttId == null && $forceCreate)
		{

//  $returnSet = new ReturnSet();
			$returnSet = $contactModel->createUser(2); //2=For not sending verification
			if ($returnSet->getStatus())
			{
				if (!in_array($agentId, Yii::app()->params['notAllowedConProfAgents']))
				{
					$contactModel->setProfile();
				}
				$cttId = $returnSet->getData()['id'];
			}
			else
			{
				throw new Exception("Invalid input", ReturnSet::ERROR_VALIDATION);
			}
		}
		else if ($cttId > 0)
		{

			ContactEmail::saveEmails($contactModel->contactEmails, $cttId);
			ContactPhone::savePhones($contactModel->contactPhones, $cttId);
			ContactProfile::setProfile($cttId, UserInfo::TYPE_CONSUMER);
//ContactProfile::updateEntity($cttId, $bkguser->bkg_user_id, UserInfo::TYPE_CONSUMER);
		}
		skipAll:
		return $cttId;
	}

	public function setProfile()
	{
		$contactId	 = $this->ctt_id;
		$isProfile	 = $userId		 = ContactProfile::getUserId($contactId);
		if (!$userId)
		{
			$userId = Users::getByContactId($contactId);
		}

		if ($this->userId > 0)
		{
			$userId = $this->userId;
		}
		if (!$userId)
		{
			$userModel	 = Users::createbyContact($this);
			$userId		 = $userModel->user_id;
		}
		Logger::profile("Contactprofile:addNew Started");
		if ($userId > 0 && !$isProfile)
		{
			ContactProfile::setProfile($contactId, UserInfo::TYPE_CONSUMER);
		}
		Logger::profile("Contactprofile:addNew Started");
		return $userId;
	}

	public function getIdByPan($panNo)
	{
		$sql	 = "SELECT ctt_id FROM contact WHERE ctt_pan_no = '$panNo' AND ctt_active = 1 ";
		$cttId	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		return $cttId;
	}

	public static function modifiedByBookingUser($bkguser, $cttId, $provider)
	{
		try
		{
			if (empty($bkguser) || empty($cttId))
			{
				throw new Exception("Invalid input", ReturnSet::ERROR_INVALID_DATA);
			}
			$primaryEmail	 = ContactEmail::getPrimaryEmail($cttId);
			$primaryphone	 = ContactPhone::getContactNumber($cttId);
			$isValid		 = Filter::validatePhoneNumber($primaryphone);
			if ($isValid)
			{
				Filter::parsePhoneNumber($primaryphone, $code, $number);
			}
			$phone							 = $number ? $number : $bkguser->bkg_contact_no;
			$email							 = $primaryEmail ? $primaryEmail : $bkguser->bkg_user_email;
			$contactModel					 = Contact::model()->findByPk($cttId);
			$contactModel->ctt_first_name	 = ($contactModel->ctt_first_name != '') ? $contactModel->ctt_first_name : $bkguser->bkg_user_fname;
			$contactModel->ctt_last_name	 = ($contactModel->ctt_last_name != '') ? $contactModel->ctt_last_name : $bkguser->bkg_user_lname;

			ContactEmail::model()->updateContacts($cttId, $email, $provider);
			ContactPhone::model()->updateContacts($cttId, $phone, '', $provider);

			$contactModel->save();
			if ($email != '')
			{
				ContactEmail::primaryToggle($email, $cttId);
			}
			if ($phone != '')
			{
				ContactPhone::primaryToggle($phone, $cttId);
			}
			/**
			 * update ref code for contact
			 */
			Contact::updateRefCode($cttId, $cttId);
			$desc = "Contacts modified";
			ContactLog::model()->createLog($cttId, $desc, ContactLog::CONTACT_MODIFIED, null);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	/**
	 * This function will be used for processing contact Model
	 */
	public function processData()
	{
		$contactId	 = 0;
		$state		 = ContactLog::CONTACT_CREATED;
		$desc		 = "Contact created from app";
		if (empty($this->contactEmails[0]->eml_email_address) && empty($this->contactPhones[0]->phn_phone_no))
		{
			goto skipAll;
		}

		$arrBkp	 = array();
		$result	 = CActiveForm::validate($this);
		if ($result != "[]")
		{
			if (strpos($result, 'license') !== false)
			{
				$contactId = $this->getContactIdByLicense($this->ctt_license_no);
			}
			else if (strpos($result, 'pan') !== false)
			{
				$contactId = $this->getIdByPan($this->ctt_pan_no);
			}

			goto skipToUpdate;
		}

		$contactId = $this->getIdByDetails($this->contactEmails[0]->eml_email_address, $this->contactPhones[0]->phn_phone_no);
		if ($contactId > 0)
		{
			$model = self::model()->findByPk($contactId);
			if (($this->ctt_license_no != $model->ctt_license_no) || ($this->ctt_pan_no != $model->ctt_pan_no))
			{
				//Treat the model as new
				goto skipSave;
			}
		}
		skipToUpdate:
		if ($contactId > 0)
		{
			$desc	 = "Contact updated from app";
			$state	 = ContactLog::CONTACT_MODIFIED;

			$this->unsetAttributes(array("ctt_first_name", "ctt_last_name"));
			$arrBkp				 = $this->attributes;
			$model				 = self::model()->findByPk($contactId);
			$model->attributes	 = array_merge(array_filter((array) $model->attributes), array_filter((array) $arrBkp));
			$model->save();
			goto skipToItems;
		}

		skipSave:
		if ($this->save())
		{
			$contactId = $this->ctt_id;
		}

		skipToItems:
		ContactLog::model()->createLog($contactId, $desc, $state, null);
		Contact::model()->updateRefCode($contactId, $contactId);
		ContactEmail::saveEmails($this->contactEmails, $contactId);
		ContactPhone::savePhones($this->contactPhones, $contactId);

		skipAll:
		return $contactId;
	}

	/**
	 * This function is used for getting booking user related contact data from contact model.
	 * @param type $bui_id int bookingUser model primary key
	 * @param type $type (int) 1=>email,2=>phone,3=>both
	 * @return \ReturnSet
	 */
	public static function referenceUserData($bui_id, $type)
	{
		$returnset	 = new ReturnSet();
		$model		 = BookingUser::model()->findByPk($bui_id);
		$contactData = new stdClass();
		$userName	 = $model->bkg_user_fname;
		$firstName	 = $model->bkg_user_fname;
		$lastName	 = $model->bkg_user_lname;
		if ($type == 1 || $type == 3)
		{
			$email				 = $model->bkg_user_email;
			$emailData			 = ['email' => $email, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
			$contactData->email	 = $emailData;
			$returnset->setData($contactData);
		}
		if ($type == 2 || $type == 3)
		{
			$number				 = $model->bkg_contact_no;
			$ext				 = $model->bkg_country_code;
			$phoneData			 = ['number' => $number, 'ext' => $ext, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
			$contactData->phone	 = $phoneData;
			$returnset->setData($contactData);
		}
		$returnset->setStatus(true);
		return $returnset;
	}

	public function getDocId($refvalue, $type)
	{
		if ($type == 5)
		{
			$sql = "select ctt_license_doc_id  from contact WHERE ctt_active =1 AND ctt_license_no = '$refvalue' order by ctt_license_doc_id DESC LIMIT 0,1";
		}
		else if ($type == 4)
		{
			$sql = "select ctt_pan_doc_id  from contact WHERE ctt_active =1 AND ctt_pan_no = '$refvalue'order by ctt_pan_doc_id DESC LIMIT 0,1";
		}
		else
		{
			$sql = "select ctt_license_doc_id  from contact WHERE ctt_active =1 AND ctt_license_no = '$refvalue' order by ctt_license_doc_id DESC LIMIT 0,1";
		}
		$docId = DBUtil::command($sql, DBUtil::SDB())->queryScalar();
		return $docId;
	}

	public static function getVendorName($ctt_id)
	{
		$sql	 = "SELECT CONCAT(ctt_first_name,' ', ctt_last_name) as vndName FROM contact WHERE ctt_id=$ctt_id AND ctt_active=1";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	/**
	 * This function is used for getting user related contact data from contact model.
	 * @param type $user_id int Users model primary key
	 * @param type $type (int) 1=>email,2=>phone,3=>both
	 * @return \ReturnSet
	 */
	public static function userMappedToItems($user_id, $type)
	{
		$returnset	 = new ReturnSet();
		$model		 = Users::model()->findByPk($user_id);
		$contactId	 = ContactProfile::getByEntityId($user_id, UserInfo::TYPE_CONSUMER);
		$contactData = new stdClass();
		if ($contactId)
		{
			$cttModel	 = self::model()->findByPk($contactId);
			$firstName	 = $cttModel->ctt_first_name;
			$lastName	 = $cttModel->ctt_last_name;
			$userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
			if ($type == 1)
			{
				$email = ContactEmail::getPrimaryEmail($contactId);
				if ($email)
				{
					$emailData			 = ['email' => $email, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
					$contactData->email	 = $emailData;
					$returnset->setData($contactData);
				}
				else
				{
					goto skipTo;
				}
			}
			if ($type == 2)
			{
				$result = ContactPhone::getPrimaryNumber($contactId);
				if ($result)
				{
					$number				 = $result->getNationalNumber();
					$ext				 = $result->getCountryCode() ? $result->getCountryCode() : '91';
					$data				 = ['number' => $number, 'ext' => $ext, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
					$contactData->phone	 = $data;
					$returnset->setData($contactData);
				}
				else
				{
					goto skipTo;
				}
			}
			if ($type == 3)
			{
				$email	 = ContactEmail::getPrimaryEmail($contactId);
				$result	 = ContactPhone::getPrimaryNumber($contactId);
				if ($email && $result)
				{
					$number				 = $result->getNationalNumber();
					$ext				 = $result->getCountryCode() ? $result->getCountryCode() : '91';
					$data				 = ['number' => $number, 'ext' => $ext, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
					$contactData->phone	 = $data;
					$emailData			 = ['email' => $email, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
					$contactData->email	 = $emailData;
					$returnset->setData($contactData);
				}
				else
				{
					goto skipTo;
				}
			}
			$returnset->setStatus(true);
		}
		else
		{
			skipTo:
			$userName	 = $model->usr_name;
			$firstName	 = $model->usr_name;
			$lastName	 = $model->usr_lname;
			if ($type == 1 || $type == 3)
			{
				$email				 = $model->usr_email;
				$emailData			 = ['email' => $email, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
				$contactData->email	 = $emailData;
				$returnset->setData($contactData);
			}
			if ($type == 2 || $type == 3)
			{
				$number				 = $model->usr_mobile;
				$ext				 = $model->usr_country_code;
				$phoneData			 = ['number' => $number, 'ext' => $ext, 'userName' => $userName, 'firstName' => $firstName, 'lastName' => $lastName];
				$contactData->phone	 = $phoneData;
				$returnset->setData($contactData);
			}
			$returnset->setStatus(true);
		}
		return $returnset;
	}

	public function createUser($tempflag = NULL)
	{
		Logger::profile("Contact::createUser Started");
		$returnSet = new ReturnSet();
		try
		{
			//  echo $this->ctt_first_name;
			// Logger::trace("before save ======".$this->ctt_first_name."+++++++".$this->attributes->ctt_first_name."******".$this->attributes['ctt_first_name']);
			$isNew = $this->isNewRecord;

			$res = $this->save();
			// Logger::trace("Save Contact First Name ======".$this->ctt_first_name."Contact ID ==".$this->ctt_id."======SAVE RESULT=======".$res."===ERRORS===".json_encode($errors));
			if (!$res)
			{
				//   Logger::error("NOT SAVED====GET ERROR",json_encode($this->getErrors()));
				$returnSet->setErrors($this->getErrors(), 0);
				throw new CHttpException("Failed to add contact", 1);
			}
			/**
			 * update ref code for contact
			 */
			Contact::updateRefCode($this->ctt_id, $this->ctt_id);

			$primaryEmail	 = ContactEmail::validatePrimary($this->ctt_id);
			$emailResponse	 = ContactEmail::model()->addNew($this->ctt_id, $this->contactEmails[0]->eml_email_address, 1, $primaryEmail, $tempflag);

			$primaryPhone	 = ContactPhone::validatePrimary($this->ctt_id);
			$phoneResponse	 = ContactPhone::model()->add($this->ctt_id, $this->contactPhones[0]->phn_phone_no, '', '', 1, $primaryPhone);
//			$this->saveProfileImage($this->ctt_id);
// ContactProfile::setProfile($this->ctt_id, $this->ctt_type);
			$desc			 = ($isNew) ? "Contact created" : "Contact modified";
			$event			 = ($isNew) ? ContactLog::CONTACT_CREATED : ContactLog::CONTACT_MODIFIED;

			//ContactLog::model()->createLog($this->ctt_id, $desc, $event, null);
			if ($tempflag == 1)
			{
				$this->saveProfileImage($this->ctt_id);
				ContactLog::model()->createLog($this->ctt_id, $desc, $event, null);
				$phoneData	 = $phoneResponse->getData();
				$emlPkId	 = $emailResponse->getData();
				$emailData	 = ContactEmail::model()->findByPk($emlPkId);
				$userType	 = UserInfo::TYPE_CONSUMER;
				if (!$isNew)
				{
					if ($emailResponse->getStatus())
					{
						$isEmailSend = Contact::sendVerification($emailData->eml_email_address, Contact::TYPE_EMAIL, $this->ctt_id, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_LINK, $userType);
					}
					if ($phoneResponse->getStatus())
					{
						$phoneData	 = $phoneResponse->getData();
						$isNew		 = $phoneData['isNew'];
						if ($isNew)
						{
							$isOtpSend = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $this->ctt_id, Contact::MODIFY_CON_TEMPLATE, Contact::MODE_OTP, $userType, 0, $phoneData["otp"], $phoneData["ext"]);
						}
					}
				}
				else
				{
					if ($emailResponse->getStatus())
					{
						$isEmailSend = Contact::sendVerification($emailData->eml_email_address, Contact::TYPE_EMAIL, $this->ctt_id, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, $userType);
					}
					if ($phoneResponse->getData())
					{
						$isOtpSend = Contact::sendVerification($phoneData["number"], Contact::TYPE_PHONE, $this->ctt_id, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, $userType, 0, $phoneData["otp"], $phoneData["ext"]);
					}
				}
			}
			$returnSet->setStatus(true);
			$returnSet->setData(["id" => $this->ctt_id]);
			Logger::profile("Contact::createUser Ended");
		}
		catch (Exception $e)
		{
			Logger::error(json_encode($e));
			$returnSet->setStatus(false);
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating contact in user sign up
	 * @param type $contactArray
	 * @param type $phoneArray
	 * @param type $emailArray
	 * @param type $provider
	 * @return type (int) contact Id
	 */
	public static function userContact($contactArray, $phoneArray, $emailArray, $provider = 1, $model = NULL)
	{
		$drvStatus		 = false;
		$vndStatus		 = false;
		$primaryEmail	 = array(array('eml_email_address' => trim(str_replace(' ', '', $emailArray['eml_email_address'])), 'eml_is_primary' => 1, 'eml_type' => $provider));
		$primaryPhone	 = array(array('phn_phone_no' => trim(str_replace(' ', '', $phoneArray['phn_phone_no'])), 'phn_is_primary' => 1));
		if ($model->ctt_id)
		{

			//    Logger::trace("getting contact ID to model =====================".$model->ctt_id);
			$contactModel = Contact::model()->findByPk($model->ctt_id);
			if ($model->scenario)
			{
				$contactModel->scenario = $model->scenario;
			}
		}
		else
		{
			//    Logger::trace("Not getting contact ID to model =====================");
			$contactModel = new Contact();
		}
		$cityData = [];

		if ($contactArray['ctt_first_name'] != "" && $contactArray['ctt_last_name'] != "")
		{
			$contactModel->ctt_first_name	 = trim($contactArray['ctt_first_name']);
			$contactModel->ctt_last_name	 = trim($contactArray['ctt_last_name']);
		}
		$contactModel->ctyName = $contactArray['ctt_city'];
		if ($contactArray['ctt_business_name'] != '')
		{
			$contactModel->ctt_business_name = trim($contactArray['ctt_business_name']);
		}
		if ($contactModel->ctyName)
		{
			$cityData = Cities::findByCityName($contactModel->ctyName, $contactArray['ctt_state']);
		}
		$contactModel->ctt_city		 = $cityData['cty_id'] ? $cityData['cty_id'] : "";
		$contactModel->ctt_state	 = $contactArray['ctt_state'] ? $contactArray['ctt_state'] : "";
		$contactModel->ctt_address	 = $contactArray['ctt_address'] ? $contactArray['ctt_address'] : "";
		$contactModel->contactEmails = $contactModel->convertToEmailObjects($primaryEmail);
		$contactModel->contactPhones = $contactModel->convertToPhoneObjects($primaryPhone);
		$contactModel->ctt_user_type = ($contactArray['ctt_user_type'] == 2) ? $contactArray['ctt_user_type'] : 1;
		//  Logger::trace("testContactUSerName==before createUser".$contactModel->ctt_first_name."testContactUSerID=== before  createUser".$model->ctt_id);

		$result = $contactModel->createUser(1); //1=For  sending verification
		return $result;
	}

	public static function updateDriverdata($data)
	{

		$contactId	 = $data->ctt_id;
		$model		 = Contact::model()->findByPk($contactId);

		if ($model == null)
		{
			throw new Exception("Contact not found", ReturnSet::ERROR_INVALID_DATA);
		}
		//print_r($data);

		$model->ctt_first_name			 = trim($data->ctt_first_name);
		$model->ctt_last_name			 = trim($data->ctt_last_name);
		$model->ctt_state				 = trim($data->ctt_state);
		$model->ctt_city				 = trim($data->ctt_city);
		$model->ctt_address				 = trim($data->ctt_address);
		$model->ctt_dl_issue_authority	 = trim($data->ctt_dl_issue_authority);

		if (!$model->update())
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$return = "success";
		return $return;
		//$returnset->setStatus(true);
	}

	public static function updateDlMismatchedDriversData()
	{
		$sql	 = "SELECT ctt.ctt_id FROM contact ctt
					INNER JOIN contact_profile as cp ON cp.cr_contact_id = ctt.ctt_id and cp.cr_status = 1 AND ctt.ctt_id = ctt.ctt_ref_code
					INNER JOIN drivers drv ON drv.drv_id = cp.cr_is_driver AND drv.drv_active =1 and drv.drv_id = drv.drv_ref_code
					INNER JOIN document doc ON doc.doc_id=ctt.ctt_license_doc_id AND doc.doc_active=1
					WHERE doc.doc_status = 2 AND ctt.ctt_active=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		$num	 = 0;
		foreach ($result as $val)
		{
			$params		 = array('ctt_id' => $val['ctt_id']);
			$sqlUpdate	 = "UPDATE contact SET ctt_is_name_dl_matched = 2 WHERE ctt_id=:ctt_id AND ctt_active=1";
			$num		 += DBUtil::execute($sqlUpdate, $params);
		}
		return $num;
	}

	public static function updateDlMismatchedVendorsData()
	{
		$sql	 = "SELECT ctt.ctt_id from contact ctt
				INNER JOIN contact_profile cpr ON cpr.cr_contact_id = ctt.ctt_id AND cpr.cr_status = 1
                INNER JOIN vendors vnd ON cpr.cr_is_vendor = vnd.vnd_id AND vnd.vnd_active = 1 AND vnd.vnd_id = vnd.vnd_ref_code
                INNER JOIN document doc ON doc.doc_id=ctt.ctt_license_doc_id AND doc.doc_active=1
                WHERE doc.doc_status = 2 AND ctt.ctt_active=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		$num	 = 0;
		foreach ($result as $val)
		{
			$params		 = array('ctt_id' => $val['ctt_id']);
			$sqlUpdate	 = "UPDATE contact SET ctt_is_name_dl_matched = 2 WHERE ctt_id=:ctt_id AND ctt_active=1";
			$num		 += DBUtil::execute($sqlUpdate, $params);
		}
		return $num;
	}

	public static function updatePANMismatchedDriversData()
	{
		$sql	 = "SELECT ctt.ctt_id from contact ctt
					INNER JOIN contact_profile as cp on cp.cr_contact_id = ctt.ctt_id and cp.cr_status = 1 and ctt.ctt_id = ctt.ctt_ref_code
					INNER JOIN drivers drv on drv.drv_id = cp.cr_is_driver and drv.drv_active =1 and drv.drv_id = drv.drv_ref_code
					INNER JOIN document doc ON doc.doc_id=ctt.ctt_pan_doc_id AND doc.doc_active=1
					WHERE doc.doc_status = 2 AND ctt.ctt_active=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		$num	 = 0;
		foreach ($result as $val)
		{
			$params		 = array('ctt_id' => $val['ctt_id']);
			$sqlUpdate	 = "UPDATE contact SET ctt_is_name_pan_matched = 2 WHERE ctt_id=:ctt_id AND ctt_active=1";
			$num		 += DBUtil::execute($sqlUpdate, $params);
		}
		return $num;
	}

	public static function updatePANMismatchedVendorsData()
	{
		$sql	 = "SELECT ctt.ctt_id from contact ctt
					INNER JOIN contact_profile cpr ON cpr.cr_contact_id = ctt.ctt_id AND cpr.cr_status = 1
					INNER JOIN vendors vnd ON cpr.cr_is_vendor = vnd.vnd_id AND vnd.vnd_active = 1 AND vnd.vnd_id = vnd.vnd_ref_code
					INNER JOIN document doc ON doc.doc_id=ctt.ctt_pan_doc_id AND doc.doc_active=1
					WHERE doc.doc_status = 2 AND ctt.ctt_active=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		$num	 = 0;
		foreach ($result as $val)
		{
			$params		 = array('ctt_id' => $val['ctt_id']);
			$sqlUpdate	 = "UPDATE contact SET ctt_is_name_pan_matched = 2 WHERE ctt_id=:ctt_id AND ctt_active=1";
			$num		 += DBUtil::execute($sqlUpdate, $params);
		}
		return $num;
	}

	/**
	 * @return static | false 
	 */
	public function getByUserId($userId)
	{
		$param	 = ['userId' => $userId];
		$sql	 = "SELECT * FROM (
						SELECT c1.ctt_id, IF(cp1.cr_is_consumer=:userId, 2, IF(cp.cr_is_consumer=:userId, 1, 0)) as rank
							FROM contact_profile cp
							INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id
							INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id AND c1.ctt_active=1
							INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
							WHERE (cp1.cr_is_consumer=:userId)
						UNION
						SELECT c1.ctt_id, IF(cp1.cr_is_consumer=:userId, 2, IF(cp.cr_is_consumer=:userId, 1, 0)) as rank
							FROM contact_profile cp
							INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id
							INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id AND c1.ctt_active=1
							INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
							WHERE (cp.cr_is_consumer=:userId)
					) a ORDER BY rank DESC";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		$model	 = false;
		if ($row)
		{
			$model = self::model()->findByPk($row["ctt_id"]);
		}

		return $model;
	}

	public static function updateDlIssueDate($cttId, $issueDate)
	{
		$cttModel = Contact::model()->findByPk($cttId);
		if (empty($cttModel))
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		$cttModel->ctt_license_issue_date = $issueDate;
		if ($cttModel->save())
		{
			$param	 = ['docID' => $cttModel->ctt_license_doc_id];
			$sql	 = "UPDATE `document` SET `doc_status`= 0 WHERE `doc_id`=:docID";
			DBUtil::execute($sql, $param);
		}
	}

	public static function updateDlExpiryDate($cttId, $expDate)
	{
		$cttModel = Contact::model()->findByPk($cttId);
		if (empty($cttModel))
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		$cttModel->ctt_license_exp_date = $expDate;
		if ($cttModel->save())
		{
			$param	 = ['docID' => $cttModel->ctt_license_doc_id];
			$sql	 = "UPDATE `document` SET `doc_status`= 0 WHERE `doc_id`=:docID";
			DBUtil::execute($sql, $param);
		}
	}

	public static function createByPhoneNumber($phone)
	{
		$contactId = ContactPhone::getContactid($phone);
		if (!$contactId)
		{
			$cModel = new Contact();
			if (!$cModel->save())
			{
				throw new Exception('Error in contact creating: ' . json_encode($cModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$contactId	 = $cModel->ctt_id;
			Filter::parsePhoneNumber($phone, $code, $number);
			$returnSet	 = ContactPhone::model()->add($contactId, $number, $userType	 = 0, $code);
			if (!$returnSet->getStatus())
			{
				throw new Exception('Error in adding contact phone: ' . json_encode($cModel->getErrors()), ReturnSet::ERROR_FAILED);
			}
		}
		return $contactId;
	}

	public static function getPhoneNoByPriority($contid)
	{
		$param	 = ['cttId' => $contid];
		$sql	 = "SELECT * FROM `contact_phone` WHERE `phn_contact_id`=:cttId AND phn_active=1
                        ORDER BY phn_is_primary DESC, phn_is_verified desc, phn_create_date desc
                limit 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $param);
	}

	/**
	 * @param int $cttId
	 * @return int | false
	 *  */
	public static function isApproved($cttId)
	{
		$param = ['cttId' => $cttId];

		$sql	 = "SELECT vnd_id  FROM vendors
					INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
					INNER JOIN contact ON ctt_id = cp.cr_contact_id AND ctt_id = ctt_ref_code AND ctt_active = 1
					WHERE ctt_id = :cttId AND vnd_active =1";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $result;
	}

	/**
	 * This function is used for updating Vaccine Status
	 * @param integer $contactId
	 * @param array $details
	 * @return ReturnSet
	 */
	public static function updateVaccineStaus($cttId, $details)
	{
		$returnset = new ReturnSet();
		try
		{
			$model = Contact::model()->findByPk($cttId);
			if (!$model)
			{
				throw new Exception('Invalid Contact', ReturnSet::ERROR_INVALID_DATA);
			}
			$model->ctt_vaccine_status			 = $details['vaccineStatus'];
			$model->ctt_vaccine_details			 = CJSON::encode($details['vaccinationDetails']);
			$model->ctt_vaccine_modified_date	 = new CDbExpression('NOW()');
			if ($model->save())
			{
				$returnset->setStatus(true);
				$returnset->setMessage("Vaccine Status Updated successfully");
			}
		}
		catch (Exception $e)
		{
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
		}
		return $returnset;
	}

	public function isVaccineUpdateRequired($contactId)
	{
		$result	 = false;
		$param	 = ['cttId' => $contactId];
		$sql	 = "SELECT `ctt_vaccine_status`,`ctt_vaccine_modified_date` FROM `contact` WHERE `ctt_id` = :cttId";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);

		$curtime	 = Filter::getDBDateTime();
		$transTime	 = $result['ctt_vaccine_modified_date'];
		$date1		 = strtotime($curtime);
		$date2		 = strtotime($transTime);
		$diff		 = abs($date1 - $date2);
		$day		 = $diff / (60 * 60 * 24);

		if (($result['ctt_vaccine_status'] == 0 || $result['ctt_vaccine_status'] == 1) && $day >= 15)
		{
			$result = true;
		}
		else if ($result['ctt_vaccine_status'] == 2)
		{
			$result = false;
		}
		return $result;
	}

	/**
	 * This function is used for fetching contact id by license or phone
	 * @param string $license
	 * @param integer $phone
	 * @return integer $contactId
	 */
	public static function getIdByPhoneOrDL($license, $phone)
	{
		$contactId = Contact::getContactIdByLicense($license);
		if (empty($contactId))
		{
			$contactData = ContactPhone::model()->findByContact($phone);
			if (!empty($contactData))
			{
				$contactId = $contactData->phn_contact_id;
			}
		}
		return $contactId;
	}

	/**
	 *
	 * @param Hybrid_User_Profile $profile
	 * @param integer $type
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function createBySocialProfile($profile, $type)
	{
		$returnSet	 = new ReturnSet();
		$email		 = $profile->emailVerified;
		$phone		 = $profile->phone;
		$contactId	 = Contact::model()->getIdByDetails($email, $phone, $profile->firstName, $profile->lastName);
		$transaction = null;
		if ($contactId > 0)
		{
			$returnSet->setStatus(true);
			$returnSet->setData(['contactId' => $contactId]);
			goto end;
		}
		try
		{
			if ($email == '' && $phone == '')
			{
				Logger::trace(CJSON::encode($profile));
				throw new Exception("Unable to fetch phone/email information", ReturnSet::ERROR_INVALID_DATA);
			}
			$transaction			 = DBUtil::beginTransaction();
			$model					 = new Contact();
			$model->ctt_first_name	 = $profile->firstName;
			$model->ctt_last_name	 = $profile->lastName;
			if (!$model->save())
			{
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$contactId = $model->ctt_id;
			if ($email != '')
			{
				$emailReturnSet = ContactEmail::model()->addNew($model->ctt_id, $email, $type, 1, 1, 1);
				if ($emailReturnSet->getStatus() != true)
				{
					throw new Exception(CJSON::encode($emailReturnSet->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
			if ($phone != '')
			{
				$phoneReturnSet = ContactPhone::model()->add($model->ctt_id, $phone, $type, 1, 1, 1);
				if ($phoneReturnSet->getStatus() != true)
				{
					throw new Exception(CJSON::encode($phoneReturnSet->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
			$returnSet->setStatus(true);
			$returnSet->setData(['contactId' => $contactId]);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}

		end:
		return $returnSet;
	}

	/**
	 * Get All matching contacts by email order by matching ranks
	 *
	 * @return array()
	 * */
	public static function getByEmail($email, $phone = '', $firstName = '', $lastName = '')
	{
		$params = ["email" => $email, 'phone' => $phone, 'firstName' => $firstName, 'lastName' => $lastName];

		$sql	 = "SELECT c1.ctt_id, IFNULL(cp1.cr_is_consumer, cp.cr_is_consumer) as userId,
						MAX(ce.eml_is_primary) as isPrimary, MAX(ce.eml_is_verified) AS isVerified,
					IF((c1.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName))=c1.ctt_name)
								OR  (c.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName))=c.ctt_name)
								OR  (c.ctt_name<>'' AND SOUNDEX(CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)))=SOUNDEX(c.ctt_name))
								OR  (c1.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)) LIKE CONCAT('%',c1.ctt_name,'%'))
								OR  (c1.ctt_name<>'' AND c1.ctt_name LIKE CONCAT('%',CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)),'%'))
								OR  (c.ctt_name<>'' AND CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)) LIKE CONCAT('%',c.ctt_name,'%'))
								OR  (c.ctt_name<>'' AND c.ctt_name LIKE CONCAT('%',CONCAT(TRIM(:firstName), ' ', TRIM(:lastName)),'%')),
						 1,0) as nameRank,
						 CASE
							WHEN MAX(phn_is_primary)=1 AND MAX(phn_is_verified)=1 THEN 4
							WHEN MAX(phn_is_verified)=1 THEN 3
							WHEN MAX(phn_is_primary)=1 THEN 2
							WHEN phn_id IS NOT NULL THEN 1
							ELSE 0
						 END as phoneRank,

						IF(cp1.cr_is_consumer IS NULL, 0, 1) as profileRank
					FROM contact_email ce
					INNER JOIN contact c ON ce.eml_contact_id=c.ctt_id AND eml_active=1
					INNER JOIN contact_profile cp ON cp.cr_contact_id=c.ctt_id
					INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code
					INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
					LEFT JOIN contact_phone phn ON phn.phn_contact_id=c1.ctt_id AND phn_active=1 AND phn_full_number=:phone
					WHERE ce.eml_email_address=:email
					GROUP BY c1.ctt_id
					ORDER BY (ce.eml_is_primary + ce.eml_is_verified) DESC, phoneRank DESC, ce.eml_is_verified DESC, profileRank DESC";
		$rows	 = DBUtil::query($sql, DBUtil:: SDB(), $params);
		return $rows;
	}

	public static function getIdByNumberOrDL($license = NULL, $phone = NULL)
	{
		$contactId = Contact::getContactIdByLicense($license);
		//$contactId = ContactProfile::getProfilebyNumber($phone)['cr_contact_id'];
		if (empty($contactId))
		{
			$profileData = ContactProfile::getProfilebyNumber($phone);
			if (!empty($profileData))
			{
				$contactId = $profileData['cr_contact_id'];
			}
		}
		return $contactId;
	}

	public static function getContactIdByEmailPhone($emailRecord, $phoneRecord, $obj, $sendVerifyLink = 0)
	{
		if (($emailRecord == null || $emailRecord->getRowCount() == 0) && ($phoneRecord == null || $phoneRecord->getRowCount() == 0) && $sendVerifyLink != 1)
		{
			$contactSet	 = Contact::createContact($obj, $sendVerifyLink, UserInfo::TYPE_CONSUMER);
			$contactId	 = $contactSet->getData()['id'];
		}
		else
		{
			foreach ($emailRecord as $contactEmail)
			{
				$idByEmail = $contactEmail['ctt_id'];
			}
			foreach ($phoneRecord as $contactPhone)
			{
				$idByPhone = $contactPhone['ctt_id'];
			}

			$contactId = $idByEmail;
			if ($idByPhone != '' && $idByEmail == '')
			{
				$contactId = $idByPhone;
			}
		}
		return $contactId;
	}

	public static function checkExistingInfoByUser($bkgUserModel)
	{
		$email			 = $bkgUserModel->bkg_user_email;
		$cttNo			 = $bkgUserModel->bkg_contact_no;
		$fname			 = $bkgUserModel->bkg_user_fname;
		$lname			 = $bkgUserModel->bkg_user_lname;
		$country_code	 = $bkgUserModel->bkg_country_code;
		$phone			 = $country_code . $cttNo;

		$signupObj	 = new \Stub\consumer\SignUpRequest();
		$obj		 = $signupObj->setModel($bkgUserModel);

		if (trim($email) != '')
		{
			$emailRecord = ContactEmail::getByEmail($email, $phone, $fname, $lname, $limit);
		}
		if (trim($phone) != '')
		{
			$phoneRecord = ContactPhone::getByPhone($phone, $email, $fname, $lname, $limit);
		}

		/** @var BookingUser $bkgUserModel */
		$agentId		 = $bkgUserModel->buiBkg->bkg_agent_id;
		$sendVerifyLink	 = ($agentId > 0) ? 1 : 0;

		$contactId = Contact::getContactIdByEmailPhone($emailRecord, $phoneRecord, $obj, $sendVerifyLink);
		return $contactId;
	}

	/**
	 * 
	 * @param type $email
	 * @param string $phone
	 * @param type $createIfNotExist
	 * @return type
	 */
	public static function getByEmailPhone($email = '', $phone = '', $createIfNotExist = false)
	{
		if (trim($phone) != '')
		{

			$phone	 = Filter::processPhoneNumber($phone);
			$value	 = Filter::parsePhoneNumber($phone, $code, $number);
			//$phone	 = $code . $number;
		}
		if (trim($email) != '')
		{
			$emailRecord = ContactEmail::getByEmail($email, $code . $number, '', '', 'limit 1');
		}
		if (trim($phone) != '')
		{
			$phoneRecord = ContactPhone::getByPhone($code . $number, $email, '', '', 'limit 1');
		}
		$contactId = Contact::getIdByRecord($emailRecord, $phoneRecord);
		Logger::info("Contact::getIdByRecord contactId " . $contactId);
		if ($contactId == '' && $createIfNotExist)
		{
			$contactSet	 = Contact::createContactByInfo($email, $phone, 0, UserInfo::TYPE_CONSUMER);
			$contactId	 = $contactSet->getData()['id'];
			Logger::info("Contact::createContactByInfo contactId " . $contactId);
		}
		return $contactId;
	}

	public static function getIdByRecord($emailRecord = null, $phoneRecord = null)
	{
		$contactId = '';
		foreach ($emailRecord as $contactEmail)
		{
			$idByEmail = $contactEmail['ctt_id'];
		}
		foreach ($phoneRecord as $contactPhone)
		{
			$idByPhone = $contactPhone['ctt_id'];
			Logger::create("Contact::getIdByRecord idByPhone" . $idByPhone, CLogger::LEVEL_INFO);
		}

		if ($idByPhone != '')
		{
			$contactId = $idByPhone;
		}
		else if ($idByEmail != '')
		{
			$contactId = $idByEmail;
		}
		return $contactId;
	}

	/**
	 *
	 * @param type $jsonObj - Contact Data
	 * @return type
	 */
	public static function createContactByInfo($email = '', $phone = '', $tempVal = 0, $userType, $provider = 1)
	{
		$returnSet = new ReturnSet();
		try
		{
			$contactModel = new Contact();
			if ($email != '')
			{
				$primaryEmail				 = array(array('eml_email_address' => $email, 'eml_is_primary' => 1, 'eml_type' => $provider, 'eml_is_verified' => 1));
				$contactModel->contactEmails = $contactModel->convertToEmailObjects($primaryEmail);
			}
			if ($phone != '')
			{
				Filter::parsePhoneNumber($phone, $code, $number);
				$primaryPhone				 = array(array('phn_phone_country_code' => $code, 'phn_phone_no' => $number, 'phn_is_primary' => 1, 'phn_type' => $provider, 'phn_is_verified' => 1));
				$contactModel->contactPhones = $contactModel->convertToPhoneObjects($primaryPhone);
			}

			$returnSet = $contactModel->contactAddByInfo($tempVal);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function contactAddByInfo($tempValue = 0)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();

		try
		{
			$isNew	 = $this->isNewRecord;
			$res	 = $this->save();
			/**
			 * update ref code for contact
			 */
			Contact::updateRefCode($this->ctt_id, $this->ctt_id);
			if (!$res)
			{
				$returnSet->setErrors($this->getErrors(), 0);
				throw new CHttpException("Failed to add contact", 1);
			}
			if ($this->contactEmails)
			{
				$emailResponse	 = $this->saveEmails();
				ContactEmail::setPrimaryEmail($this->ctt_id);
				$emlPkId		 = $emailResponse->getData();
				$emailData		 = ContactEmail::model()->findByPk($emlPkId);
			}
			if ($this->contactPhones)
			{
				$phoneResponse	 = $this->savePhones();
				ContactPhone::setPrimaryPhone($this->ctt_id);
				$phoneData		 = $phoneResponse->getData();
			}

			//$this->saveProfileImage($this->ctt_id);
			$this->setProfile();
			$desc	 = ($isNew) ? "Contact created" : "Contact modified";
			$event	 = ($isNew) ? ContactLog::CONTACT_CREATED : ContactLog::CONTACT_MODIFIED;

			$userType = ($this->addType == 3) ? UserInfo::TYPE_VENDOR : UserInfo::TYPE_DRIVER;

			ContactLog::model()->createLog($this->ctt_id, $desc, $event, null);

			if ($this->commit)
			{
				DBUtil::commitTransaction($transaction);
			}
			$returnSet->setStatus(true);
			$returnSet->setData(["id" => $this->ctt_id]);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			if ($returnSet->getErrorCode() == 0)
			{
				$returnSet->setErrorCode($e->getCode());
				$returnSet->addError($e->getMessage());
			}
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/** @param Stub\common\ContactVerification $objCttVerify */
	public static function verifyOTP($objCttVerify, $canSendSMS = true, $smstextType = null, $canSendEmail = true, $smsLogType = SmsLog::SMS_LOGIN_REGISTER)
	{

		if ($objCttVerify->otp == null || $objCttVerify->otpValidTill == null || $objCttVerify->otpValidTill < time())
		{
			$objCttVerify->otp			 = Filter::generateOtp();
			$objCttVerify->otpValidTill	 = time() + 300;
		}
		$objCttVerify->isSendSMS = 0;
		if ($objCttVerify->otpRetry < 3 && ($objCttVerify->otpLastSent == null || $objCttVerify->otpLastSent < time() - 120))
		{
			if ($objCttVerify->type == Stub\common\ContactVerification::TYPE_EMAIL && $canSendEmail)
			{
				emailWrapper::sendOtp($objCttVerify->value, $objCttVerify->otp);
			}
			else if ($objCttVerify->type == Stub\common\ContactVerification::TYPE_PHONE && $canSendSMS)
			{
				$cacheOTPKey = "OTPCtr {$objCttVerify->value}";
				$cacheObj	 = Yii::app()->cache->get($cacheOTPKey);
				if ($cacheObj == null)
				{
					$cacheObj = $objCttVerify;
					Yii::app()->cache->set($cacheOTPKey, $cacheObj, 10, new CacheDependency("CustomLog"));
				}
				else
				{
					$cacheObj->otpRetry++;
					Logger::trace(session_id());
					Logger::trace(json_encode($cacheObj));
					Logger::warning("Multiple OTP tried", true);
					return $cacheObj;
				}

				Filter::parsePhoneNumber($objCttVerify->value, $code, $number);
				$noOfHRvalid = 1;
				$countSms	 = SmsLog::getCountByType($code, $smsLogType, $code . $number, $noOfHRvalid);
				if ($countSms >= SmsLog::SMS_MAX_ALLOWED)
				{
					return $cacheObj;
				}

				if (UserInfo::$platform == 1)
				{
					if ($smsLogType == SmsLog::SMS_FORGET_PASSWORD)
					{
						$smsWrapperKey = smsWrapper::DLT_OTP_FORGOTPASSWORD;
					}
					else
					{
						$smsWrapperKey = smsWrapper::DLT_APP_OTP_TEMPID;
					}
					$dltId	 = $smstextType != null ? $smsWrapperKey : smsWrapper::DLT_OTP_TEMPID;
					$isSend	 = Users::notifySendOtp($code, $number, $objCttVerify->otp, $dltId, $smstextType, $smsLogType);
				}
				else if ($smstextType != null)
				{

					$isSend = smsWrapper::sendOtpWEBOTP($code, $number, $objCttVerify->otp, $smsLogType);
				}
				else
				{
					$isSend = smsWrapper::sendOtp($code, $number, $objCttVerify->otp, $smsLogType);
				}
				if ($isSend)
				{
					$objCttVerify->isSendSMS = 1;
				}
				if (YII_DEBUG && APPLICATION_ENV != 'production' && Yii::app()->params['sendSMS'] == false)
				{
					$objCttVerify->isSendSMS = 1;
				}
			}
			$objCttVerify->otpLastSent = time();
			$objCttVerify->otpRetry++;
			Yii::app()->cache->set($cacheOTPKey, $cacheObj, 20, new CacheDependency("CustomLog"));
			if ($cacheObj->otpRetry > 1)
			{
				Logger::trace(json_encode($cacheObj));
				Logger::warning("Multiple OTP Sent", true);
			}
		}
		skipOTP:
		return $objCttVerify;
	}

	public static function getKeyBySession($contactVerifications, $verifyValue, $verifyType)
	{
		foreach ($contactVerifications as $key => $contactVerification)
		{
			if ($contactVerification->value == $verifyValue && $contactVerification->type == $verifyType)
			{
				$keyVal = $key;
			}
		}
		return $keyVal;
	}

	public static function getLinkedIdsByEmailPhone($email, $phone)
	{
		$cttIds = [];

		if ($email != '')
		{
			$cttIds[] = ContactEmail::getLinkedContactIds($email);
		}

		if ($phone != '')
		{
			$cttIds[] = ContactPhone::getLinkedContactIds($phone);
		}

		if ($phone != '' || $email != '')
		{
			$cttIds[] = Users::getLinkedContactIds($phone, $email);
		}

		$cttIds	 = array_filter($cttIds);
		$ids	 = implode(",", $cttIds);
		$cttIds	 = array_filter(array_unique(explode(",", $ids)));

		if (count($cttIds) == 0)
		{
			return false;
		}

		DBUtil::getINStatement($cttIds, $bindString, $params);
		$sql = "SELECT GROUP_CONCAT(DISTINCT ctt_ref_code) as cttIds FROM contact WHERE ctt_active=1 AND ctt_id IN ($bindString)";

		$ids = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $ids;
	}

	public static function getAllLinkedByEmailPhone($email, $phone)
	{
		$contactIds = Contact::getLinkedIdsByEmailPhone($email, $phone);

		if (!$contactIds)
		{
			return false;
		}
		$fullNumber	 = "";
		$phone		 = Filter::processPhoneNumber($phone);
		if ($phone)
		{
			Filter::parsePhoneNumber($phone, $code, $number);
			$fullNumber = $code . $number;
		}
		DBUtil::getINStatement($contactIds, $bindString, $params);
		$params["number"]	 = $fullNumber;
		$params["email"]	 = $email;

		$sql = "SELECT ctt_id, ctt_ref_code,ctt_name,ctt_first_name,ctt_last_name, phn_phone_country_code, phn_phone_no, phn_full_number, eml_email_address,
					phn_is_primary, phn_is_verified, eml_is_verified, eml_is_primary, IF(ctt_id=ctt_ref_code, 1, 0) AS isMaster,
					IF(phn_full_number=:number AND phn_full_number!='',1,0) AS phoneMatched,
					IF(eml_email_address=:email AND eml_email_address!='',1,0) AS emailMatched,cr_is_consumer 
				FROM contact c1 
				LEFT JOIN contact_email ON eml_contact_id=c1.ctt_id AND eml_active=1
				LEFT JOIN contact_phone ON phn_contact_id=c1.ctt_id AND phn_active=1
			    INNER JOIN contact_profile ON cr_contact_id=c1.ctt_id AND cr_status=1

				WHERE ctt_ref_code IN ($bindString) AND (eml_id IS NOT NULL OR phn_id IS NOT NULL)
				ORDER BY phoneMatched DESC, emailMatched DESC, phn_is_verified DESC, eml_is_verified DESC, 
					isMaster DESC, phn_is_primary DESC, eml_is_primary DESC 
			";

		$res = DBUtil::query($sql, DBUtil::SDB(), $params);

		return $res;
	}

	/**
	 * 
	 * @param type $value
	 * @param type $cttId
	 * @param type $otpType
	 * @param type $userType
	 * @return type
	 */
	public static function contactVerificationBySendOtp($value, $cttId, $otpType, $userType = UserInfo::TYPE_CONSUMER)
	{
		$returnSet = new ReturnSet();
		try
		{
			$returnSet->setStatus(false);
			$verifyCode = Filter::generateOtp();

			$contactCount = Contact::countContactByValue($otpType, $value);
			if ($contactCount > 1 && $value != '')
			{
				$callBackLink = '<a class="font-12 pl10 pr10 hvr-push" onClick="return reqCMB(2)" href=' . Yii::app()->getBaseUrl(true) . '/scq/existingBookingCallBack?reftype=2 target="_blank">Click here</a>';
				throw new Exception("This no is already linked with another account. If this number belongs to you please " . $callBackLink . " to create a support ticket.", ReturnSet::ERROR_FAILED);
			}

			$data = self::setDataByType($value, $verifyCode, $otpType);

			$status			 = $data['status'];
			$dataArr		 = $data['dataarr'];
			$jsonData		 = Filter::removeNull(json_encode($dataArr));
			$encriptedData	 = Filter::encrypt($jsonData);

			$returnSet->setStatus($status);
			$returnSet->setData(['encCode' => $encriptedData]);
			$status ? $returnSet->setMessage("OTP sent successfully") : $returnSet->setMessage("Unable to send  OTP");
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnSet = ReturnSet::setException($ex);
			$returnSet->setMessage("Something went wrong");
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	public static function countContactByValue($otpType, $value)
	{
		if ($otpType == 1)
		{
			$count = ContactEmail::getLinkedContactCountByEmail($value);
		}
		else
		{
			$count = ContactPhone::getLinkedContactCountByPhone($value);
		}
		return $count;
	}

	/**
	 * 
	 * @param type $verifyCode
	 * @param type $otpType
	 * @return type
	 */
	public static function setDataByType($value, $verifyCode, $otpType)
	{
		switch ($otpType)
		{
			case '1':
				$msg	 = "Your OTP for email verification is " . $verifyCode . " - Gozocabs";
				$status	 = emailWrapper::emailVerificationOtp($value, $verifyCode);
				$dataArr = ['otp' => $verifyCode, 'type' => $otpType, 'value' => $value];
				break;
			case '2':
				$isDelay = 0;
				$msg	 = "Your OTP for phone number verification is " . $verifyCode . " - Gozocabs";
				Filter::parsePhoneNumber($value, $code, $number);
				$sms	 = new Messages();
				$res	 = $sms->sendMessage($code, $number, $msg, $isDelay, 1, smsWrapper::DLT_VERIFY_PHONE_OTP_TEMPID);
				$slgId	 = smsWrapper::createLog($code, $number, "", $msg, $res, $userType, "", '', '', '', $isDelay);
				$status	 = ($slgId) ? true : false;
				$dataArr = ['otp' => $verifyCode, 'type' => $otpType, 'value' => $number];
				break;
		}
		$data = ["status" => $status, 'dataarr' => $dataArr];
		return $data;
	}

	/**
	 * 
	 * @param type $decriptArr
	 * @param type $cttId
	 * @param type $code
	 * @return ReturnSet returnSet
	 * @throws Exception
	 */
	public static function verifyInfo($decriptArr, $cttId, $code)
	{
		$returnSet = new ReturnSet();

		$type	 = $decriptArr->type;
		$model	 = Contact::model()->findByPk($cttId);
		if ($decriptArr->otp != $code)
		{
			$msg = "Sorry! OTP doesn't match.";
			throw new Exception($msg, ReturnSet::ERROR_INVALID_DATA);
		}
		switch ($type)
		{
			case Contact::TYPE_EMAIL:
				$returnSet	 = ContactEmail::model()->editContacts($cttId, $decriptArr->value, SocialAuth::Eml_Gozocabs, 1, 1);
				break;
			case Contact::TYPE_PHONE:
				$returnSet	 = ContactPhone::model()->updateContactStatus($cttId, $decriptArr->value);
				break;
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $value
	 * @param type $cttId
	 * @param type $type
	 * @return type
	 */
	public static function checkIsVerified($value, $cttId, $type)
	{
		switch ($type)
		{
			case '1':
				$contactEmailModel	 = ContactEmail::model()->find('eml_contact_id=:id && eml_email_address=:email', ['id' => $cttId, 'email' => $value]);
				$isVerified			 = $contactEmailModel->eml_is_verified;
				break;
			case '2':
				$contactPhoneModel	 = ContactPhone::model()->find('phn_contact_id=:id && phn_phone_no=:phone', ['id' => $cttId, 'phone' => $value]);
				$isVerified			 = $contactPhoneModel->phn_is_verified;
				break;
		}
		return $isVerified;
	}

	/**
	 * 
	 * @param type $type
	 * @param type $value
	 * @param type $cttId
	 * @return type
	 */
	public static function setPrimaryByType($type, $value, $cttId)
	{
		switch ($type)
		{
			case '1':
				$response	 = ContactEmail::setPrimaryByEmail($value, $cttId);
				break;
			case '2':
				$response	 = ContactPhone::setPrimaryByPhone($value, $cttId);
				break;
		}
		return $response;
	}

	public static function removeDataByType($type, $value, $cttId)
	{
		$returnSet	 = new ReturnSet();
		$params		 = ['cttid' => $cttId, 'type' => $type];
		$transaction = DBUtil::beginTransaction();
		try
		{
			switch ($type)
			{
				case '1':
					$returnSet = ContactEmail::model()->validateEmailById($value, $params);
					if ($returnSet->getStatus())
					{
						$contactEmailModel				 = ContactEmail::model()->find('eml_contact_id=:id && eml_email_address=:email', ['id' => $cttId, 'email' => $value]);
						$contactEmailModel->eml_active	 = 0;
						if (!$contactEmailModel->save())
						{
							throw new Exception(CJSON::encode($contactEmailModel->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
						$returnSet->setMessage('Email id remove successfully');
					}
					break;
				case '2':
					$returnSet = ContactPhone::model()->validatePhoneById($value, $params);
					if ($returnSet->getStatus())
					{
						$phoneModel				 = ContactPhone::model()->find('phn_contact_id=:id && phn_phone_no=:phone', ['id' => $cttId, 'phone' => $value]);
						$phoneModel->phn_active	 = 0;
						if (!$phoneModel->save())
						{
							throw new Exception(CJSON::encode($phoneModel->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
						$returnSet->setMessage('Phone no remove successfully');
					}
					break;
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function getByPerson($cttId)
	{
		$data			 = [];
		$data['contact'] = Contact::model()->getContactDetails($cttId);
		$data['email']	 = ContactEmail::model()->findByContactID($cttId);
		$data['phone']	 = ContactPhone::model()->findByContactID($cttId);
		return $data;
	}

	public static function getByLicenseAndPhone($license, $phone)
	{
		$phone = Filter::processPhoneNumber($phone);
		if (!$phone)
		{
			return false;
		}
		Filter::parsePhoneNumber($phone, $code, $number);
		$phone	 = $code . $number;
		$sql	 = "SELECT cnt.ctt_id FROM contact as cnt
				 INNER JOIN contact_phone as cntp on cnt.ctt_id = cntp.phn_contact_id 
				WHERE cntp.phn_active=1 AND cntp.phn_full_number=:phone AND cnt.ctt_license_no=:license";
		$params	 = ["phone" => $phone, "license" => $license];
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function getDetails($cttId)
	{
		$params	 = ['cttid' => $cttId];
		$sql	 = "SELECT cttRef.ctt_id,cttRef.ctt_owner_id,cttRef.ctt_user_type,
			cttRef.ctt_business_name,cttRef.ctt_state,cttRef.ctt_city,cttRef.ctt_address,
			cttRef.ctt_first_name, cttRef.ctt_last_name,cttRef.ctt_profile_path,cttRef.ctt_account_type,
			cttRef.ctt_license_no, cttRef.ctt_voter_no, cttRef.ctt_aadhaar_no, cttRef.ctt_pan_no, cttRef.ctt_license_exp_date,cttRef.ctt_license_issue_date,
			cttRef.ctt_license_doc_id,cttRef.ctt_dl_issue_authority,
			eml_email_address,phn_phone_country_code,phn_phone_no,
			cttRef.ctt_preferred_language, cty_name,
			stt_name,
			cttRef.ctt_bank_account_no,cttRef.ctt_bank_name,cttRef.ctt_bank_ifsc,cttRef.ctt_beneficiary_name,
			cttRef.ctt_beneficiary_id,cttRef.ctt_bank_branch
			 FROM contact ctt
			INNER JOIN contact cttRef  ON cttRef.ctt_id=ctt.ctt_ref_code AND cttRef.ctt_active = 1
			LEFT JOIN contact_email ON cttRef.ctt_id = eml_contact_id AND eml_is_primary = 1 AND eml_active = 1
			LEFT JOIN contact_phone ON cttRef.ctt_id = phn_contact_id AND phn_is_primary = 1 AND phn_active =1
			LEFT JOIN cities ON cttRef.ctt_city = cty_id 
			LEFT JOIN states ON cttRef.ctt_state = stt_id
			WHERE ctt.ctt_id = :cttid ";
		return DBUtil::queryRow($sql, DBUtil::SDB2(), $params);
	}

	public static function createByPhone($obj)
	{

//$obj="stdClass object {
//  firstName => (string) aa
//  lastName => (string) bb
//  email => stdClass object {
//    address => (string) a@b.com
//  }
//  phone => array(0)
//  auth => stdClass object {
//    password => (string) 8827
//    encodedHash => (string) QY/M6CgIzNF9OzKhfCadzk5UEBKYjU+mtIg1207F6EF38lK2qBpgunIott7LkvRvdOiewuqSqsd0Eg==
//    device => Beans\common\DeviceInfo object {
//      uuid => (string) 7443363c1ada1547122
//      fcmToken => (string) fWUHTg3HShGyMBI95qqiDy:APA91bEv3Blq_QxsJVX5XSAMAjQLcNpHEB75R_lWvHIcmQtWn-EnHH2KaXucv_sU-XkhlwgW6Sj0rYtu7hh4DPY5pUplfYJuBkBnEjprhkYzyE8L8VqIEaR4vBDNTz4KkGazBXad6R_LM
//      sessionToken => (string) fdfae6ce0970e6ea5080e3e45a6596b5
//      apkVersion => (string) 3.20.102806
//      osVersion => (string) 29
//      model => (string) Samsung
//      brand => (string)  M30s xyz
//      lat => null
//      long => null
//      status => (string) 1
//    }
//  }
//}";
//$profileObj=





		$returnSet = $cttModel->create(true, UserInfo::TYPE_CONSUMER);
		if (!$returnSet->isSuccess())
		{
			throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
		}

		$userModel = Users::createbyContact($cttModel->ctt_id);
	}

	public static function getDocFieldInfo($docType)
	{
		$identityNo	 = "";
		$docId		 = "";

		switch ($docType)
		{
			case Document::Document_Voter:
				$identityNo	 = "ctt_voter_no";
				$docId		 = "ctt_voter_doc_id";
				break;

			case Document::Document_Aadhar:
				$identityNo	 = "ctt_aadhaar_no";
				$docId		 = "ctt_aadhar_doc_id";
				break;

			case Document::Document_Licence:
				$identityNo	 = "ctt_license_no";
				$docId		 = "ctt_license_doc_id";
				break;

			case Document::Document_Pan:
				$identityNo	 = "ctt_pan_no";
				$docId		 = "ctt_pan_doc_id";
				break;
			case Document::Document_Memorandum:
				$docId		 = "ctt_memo_doc_id";
				break;
			case Document::Document_Police_Verification_Certificate:
				$docId		 = "ctt_police_doc_id";
				break;
		}
		return ['identityNo' => $identityNo, 'docId' => $docId];
	}

	public function registerDCO($isDco = 1, $regPlatform = '')
	{
		$returnSet = new ReturnSet();
		try
		{
			$contactId	 = $this->ctt_id;
			$name		 = $this->ctt_first_name . " " . $this->ctt_last_name;
			$this->isDco = $isDco;

			$contactData = \ContactProfile::getCodeByCttId($contactId);

			if ($contactData && $contactData['cr_is_vendor'] > 0)
			{
				$vndId = $contactData['cr_is_vendor'];
				goto skipNewVendor;
			}
			$response	 = Vendors::model()->add($contactId, $name, $this->isDco, $this->ctt_city, $regPlatform);
			$vndId		 = $response->getData();

			if (!$response->getStatus())
			{
				$returnSet->setMessage("Failed to create vendor");
				goto skipAll;
			}
			#Create vendor profile  
			ContactProfile::updateEntity($contactId, $vndId, UserInfo::TYPE_VENDOR);
			skipNewVendor:
			$data['vendor']	 = $vndId;
			$dbDate			 = Filter::getDBDateTime();
			if ($this->isDco && $this->ctt_license_no != '' && $this->locale_license_exp_date > $dbDate && $this->ctt_license_doc_id > 0)
			{
				if ($contactData && $contactData['cr_is_driver'] > 0)
				{
					$drvId = $contactData['cr_is_driver'];
					goto skipNewDriver;
				}
				$driverName	 = $this->ctt_first_name . " " . $this->ctt_last_name;
				$returnSet	 = Drivers::addDriverDetails($contactId, $driverName);
				if ($returnSet->getStatus())
				{
					$drvId		 = $returnSet->getData();
					ContactProfile::updateEntity($contactId, $drvId, UserInfo::TYPE_DRIVER);
					$dataArr	 = ['vendor' => $vndId, 'driver' => $drvId];
					$resLinked	 = VendorDriver::model()->checkAndSave($dataArr);
				}
				else
				{
					if ($returnSet->getMessage() != '')
					{
						throw new Exception($returnSet->getMessage(), ReturnSet::ERROR_VALIDATION);
					}
				}
				skipNewDriver:
				$data['driver'] = $drvId;
			}

			$returnSet->setStatus(true);
			$returnSet->setData($data);
			$returnSet->setMessage("Vendor account is created successfully ");
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}

		skipAll:
		return $returnSet;
	}

	/**
	 * 
	 * @param type $phone
	 * @return boolean
	 */
	public static function searchTempContactsByPhone($phone)
	{
		try
		{
			$param	 = ['phone' => $phone];
			$sql	 = "SELECT tpc.tpc_fname fname,tpc.tpc_lname lname,tpc.tpc_email email,tpc.tpc_phone number FROM test.temp_contacts tpc WHERE tpc.tpc_phone = :phone";
			$resQ	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);

			if (!is_null($resQ) && $resQ != '')
			{
				return $resQ;
			}
		}
		catch (Exception $ex)
		{
			Logger::trace($ex->getMessage());
		}
		return false;
	}

	/**
	 * 
	 * @param type $phone
	 * @param type $cttId
	 * @return type
	 */
	public static function updateTempContactsRegisteredByPhone($phone, $cttId)
	{
		if ($phone != '' && $cttId > 0)
		{
			try
			{
				$param	 = ['cttId' => $cttId, 'phone' => $phone];
				$sql	 = "UPDATE test.temp_contacts tpc  
						SET tpc.tpc_registered_ctt_id =:cttId,	
							tpc.tpc_status = 2 
							WHERE tpc.tpc_phone = :phone  ";
				$resQ	 = DBUtil::execute($sql, $param);
				return $resQ;
			}
			catch (Exception $ex)
			{
				Logger::trace($ex->getMessage());
			}
		}
		return false;
	}

	/**
	 * 
	 * @param type $phone 
	 * @return type
	 */
	public static function updateTempContactsAttemptedByPhone($phone)
	{
		if ($phone != '')
		{
			try
			{
				$param	 = ['phone' => $phone];
				$sql	 = "UPDATE test.temp_contacts tpc  
							SET tpc.tpc_status=1 
							WHERE tpc.tpc_phone = :phone  ";
				$resQ	 = DBUtil::execute($sql, $param);
				return $resQ;
			}
			catch (Exception $ex)
			{
				Logger::trace($ex->getMessage());
			}
		}
		return false;
	}

	/**
	 * 
	 * @param type $phone
	 * @return boolean
	 */
	public static function checkExistingTempContacts($phone)
	{
		try
		{
			$param	 = ['phone' => $phone];
			$sql	 = "SELECT tpc.tpc_id FROM test.temp_contacts tpc WHERE tpc.tpc_phone = :phone";
			$resQ	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);

			if (!is_null($resQ) && $resQ != '')
			{
				return $resQ;
			}
		}
		catch (Exception $ex)
		{
			Logger::trace($ex->getMessage());
		}
		return false;
	}

	/**
	 *
	 * @param type $cttId      -  contactId
	 * @param type $type        - 1: Email / 2: Phone  
	 * @param type $value    - Email id / Phone
	 */
	public static function markVerified($cttId, $type, $value)
	{
		$returnSet = new ReturnSet();
		switch ($type)
		{
			case Contact::TYPE_PHONE:

				$isPhoneVerified = \ContactPhone::checkData($cttId, $value, null, null, 1);
				if (!$isPhoneVerified->getStatus())
				{
					\ContactPhone::model()->updateContactStatus($cttId, $value);
				}
				$returnSet->setStatus(true);
				break;
			case Contact::TYPE_EMAIL:

				$isEmailVerified = \ContactEmail::checkData($value, null, $cttId, null, 1);
				if (!$isEmailVerified->getStatus())
				{
					\ContactEmail::markVerified($cttId, $value);
				}
				$returnSet->setStatus(true);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param \Beans\contact\Person $contactObj
	 * @param int $cttId
	 * 
	 * 1	=> 'Agreement', 
	 * 2	=> 'Voter',
	 * 3	=> 'Aadhar',
	 * 4	=> 'Pan',
	 * 5	=> 'Licence', 
	 *  
	 */
	public static function updateProfileDCO($contactObj, $cttId)
	{
		$listDocs	 = Document::model()->findAllByDrvId($cttId);
		$listDoc	 = $listDocs[0];

		$voterStatus	 = $listDoc['doc_status2'];
		$aadhaarStatus	 = $listDoc['doc_status3'];
		$panStatus		 = $listDoc['doc_status4'];
		$licStatus		 = $listDoc['doc_status5'];
		$cttModel		 = Contact::model()->findByPk($cttId);
		/** @var Contact $cttModel */
		if ($voterStatus != 1 && $contactObj->voter != '')
		{
			$cttModel->validateDocumentInfo($contactObj->voter, Document::Document_Voter);
		}
		if ($aadhaarStatus != 1 && $contactObj->aadhaar != '')
		{
			$cttModel->validateDocumentInfo($contactObj->aadhaar, Document::Document_Aadhar);
		}
		if ($panStatus != 1 && $contactObj->pan != '')
		{
			$cttModel->validateDocumentInfo($contactObj->pan, Document::Document_Pan);
		}
		if ($licStatus != 1 && $contactObj->dlNumber != '')
		{
			$dlNumber		 = $contactObj->dlNumber;
			$dlExpiryDate	 = $contactObj->dlExpiryDate;
			$dlIssuingState	 = $contactObj->dlIssuingState;
			$cttModel->validateDrivingLicenceInfo($dlNumber, $dlExpiryDate, $dlIssuingState);
		}
		if ($contactObj->accountInfo)
		{
			$cttModel->setDCOBankInfo($contactObj->accountInfo[0]);
		}
		if (!$cttModel->validate())
		{
			throw new Exception(json_encode($cttModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $cttModel->save();
	}

	/**
	 * 
	 * @param String $docNumber
	 * @param int $docType
	 * @throws Exception
	 */
	public function validateDocumentInfo($docNumber, $docType)
	{
		$docNameList = Document::model()->documentType();
		$docName	 = $docNameList[$docType];
		$fieldName	 = Document::getFieldByType($docType);
		$cttId		 = $this->ctt_id;
		$docCttId	 = Contact::checkContactIdForDocNumber($fieldName, $docNumber);
		if (!$docCttId || $docCttId == $cttId)
		{
			$this->$fieldName = $docNumber;
		}
		else
		{
			throw new Exception("Issue in $docName number", ReturnSet::ERROR_INVALID_DATA);
		}
	}

	/**
	 * 
	 * @param String $docNumber
	 * @param String $dlExpiryDate
	 * @param String $dlIssuingState
	 * @throws Exception
	 */
	public function validateDrivingLicenceInfo($docNumber, $dlExpiryDate = '', $dlIssuingState = '')
	{
		$docType = Document::Document_Licence;

		$fieldName	 = Document::getFieldByType($docType);
		$cttId		 = $this->ctt_id;
		$docCttId	 = Contact::checkContactIdForDocNumber($fieldName, $docNumber);

		if ($dlExpiryDate == '')
		{
			throw new Exception("Driving Licence expiry date not given", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($dlIssuingState == '')
		{
			throw new Exception("Driving Licence issuing state not given", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($dlExpiryDate < \Filter::getDBDateTime())
		{
			throw new Exception("Driving Licence already expired", ReturnSet::ERROR_INVALID_DATA);
		}
		if (!$docCttId || $docCttId == $cttId)
		{
			$this->$fieldName				 = $docNumber;
			$this->ctt_license_exp_date		 = $dlExpiryDate;
			$this->ctt_dl_issue_authority	 = \States::model()->getNameById($dlIssuingState);
		}
		else
		{
			throw new Exception("Issue in Driving Licence number", ReturnSet::ERROR_INVALID_DATA);
		}
	}

	/**
	 * 
	 * @param \Beans\common\AccountInfo $accountInfoObj
	 */
	public function setDCOBankInfo(\Beans\common\AccountInfo $accountInfoObj)
	{
		if (trim($accountInfoObj->type) != '')
		{
			$this->ctt_account_type = (strtolower($accountInfoObj->type) == 'current') ? 1 : 0;
		}
		if (trim($accountInfoObj->accountNumber) != '')
		{
			$this->ctt_bank_account_no = trim($accountInfoObj->accountNumber);
		}
		if (trim($accountInfoObj->accountName) != '')
		{
			$this->ctt_bank_name = trim($accountInfoObj->accountName);
		}
		if (trim($accountInfoObj->benificiaryName) != '')
		{
			$this->ctt_beneficiary_name = trim($accountInfoObj->benificiaryName);
		}
		if (trim($accountInfoObj->branchName) != '')
		{
			$this->ctt_bank_branch = trim($accountInfoObj->branchName);
		}
		if (trim($accountInfoObj->ifscCode) != '')
		{
			$this->ctt_bank_ifsc = trim($accountInfoObj->ifscCode);
		}
	}

	/**
	 * 
	 * @param string $fieldName
	 * @param int $docNumber
	 * @return int
	 */
	public static function checkContactIdForDocNumber($fieldName, $docNumber)
	{

		if ($fieldName != '' && $docNumber != '')
		{
			$sql = "SELECT ctt_ref_code FROM contact WHERE $fieldName = '$docNumber'";
			return DBUtil::queryScalar($sql, DBUtil::SDB());
		}
		return false;
	}

	/**
	 * function used for DCO profile image
	 * @param type $cttId
	 * @param type $image
	 */
	public function saveDcoProfileImage($profileImage)
	{

		$path					 = Document::upload($this->ctt_id, "profile", $profileImage);
		$this->ctt_profile_path	 = $path;
		if ($this->save())
		{
			return $path;
		}
		return false;
	}

	public static function getTags($cttId = 0)
	{
		if (!$cttId || $cttId == '')
		{
			return '';
		}
		$sql = "SELECT ctt_tags FROM contact WHERE ctt_id = $cttId";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public static function phoneEmailValidation($contactModel)
	{
		$licCttId	 = Contact::getContactIdByLicense($contactModel->ctt_license_no);
		$phCttIds	 = null;

		$phone	 = $contactModel->contactPhones[0]->phn_phone_no;
		$email	 = $contactModel->contactEmails[0]->eml_email_address;
		if ($phone)
		{
			$phCttIds = ContactPhone::getData($phone, false);
		}

		$emlCttIds = null;
		if ($email)
		{
			$emlCttIds = ContactEmail::getData($email, false);
		}
		if ($phCttIds && (!$licCttId || !in_array($licCttId, explode(',', $phCttIds))))
		{
			$contactModel->addError("ctt_id", "This phone number is already registered with other account");
		}

		if ($emlCttIds && (!$licCttId || !in_array($licCttId, explode(',', $emlCttIds))))
		{
			$contactModel->addError("ctt_id", "This email is already registered with other account");
		}

		if (strlen($contactModel->ctt_license_no) < 12)
		{
			$contactModel->addError("ctt_id", "Your license was not valid");
		}

		if ($contactModel->hasErrors())
		{
			throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
	}

	/**
	 * 
	 * @param int $pan
	 * @param int $licenseNo
	 * @return bool|int
	 */
	public static function getIdByLicensePan($pan, $licenseNo)
	{
//		$params	 = ['licenseNo' => $licenseNo, 'pan' => $pan];
//		$sql	 = "SELECT ctt_id FROM contact 
//			WHERE ctt_license_no = :licenseNo AND ctt_pan_no = :pan
//			AND ctt_active = 1 ";
//		$cttId	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
//		if($cttId)
//		{
//			goto skipAll;
//		}

		$where	 = '';
		$params	 = [];
		if ($pan && $pan != '')
		{
			$where			 .= " AND ctt_pan_no = :pan ";
			$params['pan']	 = $pan;
		}
		if ($licenseNo && $licenseNo != '')
		{
			$where				 .= " AND ctt_license_no = :licenseNo ";
			$params['licenseNo'] = $licenseNo;
		}
		if (empty($params))
		{
			return false;
		}
		$sql = "SELECT ctt_id FROM contact 
			WHERE ctt_active = 1 $where ";

		$cttId = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $cttId;
	}

	public static function getPrimaryContactToMerger($limit = 0)
	{
		$sql = "SELECT ctt_id,
			TRIM(ctt_aadhaar_no) ctt_aadhaar_no,
			TRIM(ctt_voter_no) ctt_voter_no,
			TRIM(ctt_license_no) ctt_license_no,
			TRIM(ctt_pan_no) ctt_pan_no,
			IF(TRIM(ctt_bank_account_no)<> '' AND ctt_bank_account_no IS NOT NULL,1,0)  hasBankRef,
			TRIM(REGEXP_REPLACE(phn.phn_full_number, '[\\D]', '')) phn_full_number,
			TRIM(eml.eml_email_address) eml_email_address  ,
			CASE
				WHEN MAX(phn.phn_is_primary)=1 AND MAX(phn.phn_is_verified)=1 
					AND MAX(phn.phn_verified_date)= phn.phn_verified_date THEN 6
				WHEN MAX(phn.phn_is_primary)=1 AND MAX(phn.phn_is_verified)=1 THEN 4
				WHEN MAX(phn.phn_is_verified)=1 THEN 3
				WHEN MAX(phn.phn_is_primary)=1 THEN 2
				WHEN phn_full_number IS NOT NULL THEN 1
				ELSE 0
			END as phoneRank,
			CASE
				WHEN MAX(eml.eml_is_primary)=1 AND MAX(eml.eml_is_verified)=1 
					AND MAX(eml.eml_verified_date)= eml.eml_verified_date THEN 6
				WHEN MAX(eml.eml_is_primary)=1 AND MAX(eml.eml_is_verified)=1 THEN 4
				WHEN MAX(eml.eml_is_verified)=1 THEN 3
				WHEN MAX(eml.eml_is_primary)=1 THEN 2
				WHEN eml_email_address IS NOT NULL THEN 1
				ELSE 0
			END as emailRank		
		FROM contact ctt
		LEFT JOIN contact_phone phn ON			
			phn.phn_contact_id = ctt.ctt_id 
			AND LENGTH(TRIM(phn.phn_full_number)) > 7
			AND LENGTH(TRIM(phn.phn_full_number)) <=15			
			AND phn.phn_active = 1 AND phn.phn_is_verified = 1
		LEFT JOIN contact_email eml ON
			eml.eml_contact_id = ctt.ctt_id AND eml.eml_active = 1 AND eml.eml_is_verified = 1			
		WHERE ctt_active=1 AND ctt_ref_code = ctt_id 
		GROUP BY ctt_id,phn_full_number,eml_email_address LIMIT $limit, 5000";

		$dataReader = DBUtil::query($sql, DBUtil::SDB());
		return $dataReader;
	}

	public static function getDuplicateContacts($start = 0)
	{
		$totRows = $start;
		$i		 = 0;
		$ctr	 = 1;
		while ($ctr > 0)
		{
			$ctr		 = 0;
			$dataReader	 = Contact::getPrimaryContactToMerger($totRows);
			foreach ($dataReader as $data)
			{
				$ctr++;
				$totRows++;
				$cttId	 = $data['ctt_id'];
				$aadhaar = preg_replace('/[^a-zA-Z0-9]/', '', $data['ctt_aadhaar_no']);
				$voter	 = preg_replace('/[^a-zA-Z0-9]/', '', $data['ctt_voter_no']);
				$license = preg_replace('/[^a-zA-Z0-9]/', '', $data['ctt_license_no']);
				$pan	 = preg_replace('/[^a-zA-Z0-9]/', '', $data['ctt_pan_no']);

				$phone	 = preg_replace('/[^0-9]/', '', $data['phn_full_number']);
				$email	 = $data['eml_email_address'];

				if ($aadhaar != '' || $voter != '' || $license != '' || $pan != '' || $phone != '' || $email != '')
				{
					$docData = \Contact::getDocumentWeightById($cttId);

					$cttEmailRank	 = $data['emailRank'] | 0;
					$cttPhoneRank	 = $data['phoneRank'] | 0;
					$cttDocRank		 = $docData['docWeight'] | 0;
					$hasBankRef		 = $data['hasBankRef'];

					$dataVal = self::getDuplicateIds($cttId, $aadhaar, $voter, $license, $pan, $phone, $email);

					foreach ($dataVal as $dataMerger)
					{
						try
						{
							$cmdModel						 = new ContactMergedDetails();
							$cmdModel->cmd_ctt_id			 = $cttId;
							$cmdModel->cmd_duplicate_ctt_id	 = $dataMerger['ctt_id'];
							$docDupData						 = \Contact::getDocumentWeightById($dataMerger['ctt_id']);

							$cttDupEmailRank = $dataMerger['emailRank'] | 0;
							$cttDupPhoneRank = $dataMerger['phoneRank'] | 0;
							$cttDupDocRank	 = $docDupData['docWeight'] | 0;
							$cttDupBankRef	 = $dataMerger['hasBankRef'] | 0;

							if ($cttEmailRank + $cttPhoneRank + $cttDocRank + $hasBankRef < $cttDupEmailRank + $cttDupPhoneRank + $cttDupDocRank + $cttDupBankRef)
							{
								$cmdModel->cmd_duplicate_ctt_id	 = $cttId;
								$cmdModel->cmd_ctt_id			 = $dataMerger['ctt_id'];
							}

							if ($dataMerger['hasAdhaar'] == 1)
							{
								$cmdModel->cmd_is_adhaar_matched = 1;
							}
							if ($dataMerger['hasVoter'] == 1)
							{
								$cmdModel->cmd_is_voter_matched = 1;
							}
							if ($dataMerger['hasLicense'] == 1)
							{
								$cmdModel->cmd_is_license_matched = 1;
							}
							if ($dataMerger['hasPan'] == 1)
							{
								$cmdModel->cmd_is_pan_matched = 1;
							}
							if ($dataMerger['hasPhone'] == 1)
							{
								$cmdModel->cmd_is_phone_matched = 1;
							}
							if ($dataMerger['hasEmail'] == 1)
							{
								$cmdModel->cmd_is_email_matched = 1;
							}
							if ($cmdModel->save())
							{
								$i++;
							}

							echo $cttId . "\t :: " . $dataMerger['ctt_id'] . "\t done";
							echo "\n";
						}
						catch (Exception $ex)
						{
							echo "Error in data entry: 
  		contactId ->\t" . $cttId . " with :\t" . $dataMerger['ctt_id'] . ". Reason : " . $ex->getMessage();
							echo "\n";
						}
					}
				}
			}
		}
		echo "Tot rows : $totRows :: Duplicates : $i";
		echo ' Completed';
		echo "\n";
	}

	public static function getDuplicateIds($cttId, $aadhaar, $voter, $license, $pan, $phone, $email)
	{
		$sql	 = "SELECT DISTINCT ctt_id ,
			IF((ctt_aadhaar_no <> '' AND ctt_aadhaar_no = '{$aadhaar}' AND ctt_aadhaar_no IS NOT NULL 
				AND LENGTH(ctt_aadhaar_no) >=12 ),1,0) hasAdhaar,
			IF((ctt_voter_no <> '' AND ctt_voter_no = '{$voter}' AND ctt_voter_no IS NOT NULL 
				AND LENGTH(ctt_voter_no) >=8 ),1,0) hasVoter,
			IF((ctt_license_no <> '' AND ctt_license_no = '{$license}' AND ctt_license_no IS NOT NULL 
				AND LENGTH(ctt_license_no) >=8 ),1,0) hasLicense,
			IF((ctt_pan_no <> '' AND ctt_pan_no = '{$pan}' AND ctt_pan_no IS NOT NULL 
				AND LENGTH(ctt_pan_no) =10 ),1,0) hasPan,
			IF(TRIM(ctt_bank_account_no)<> '' AND ctt_bank_account_no IS NOT NULL,1,0)  hasBankRef,
			IF((REGEXP_REPLACE( phn_full_number, '\\D', '') <> '' 
				AND REGEXP_REPLACE( phn_full_number, '[\\D]', '') = '{$phone}' 
				AND phn_full_number IS NOT NULL),1,0) hasPhone,
			IF((eml_email_address <> '' AND eml_email_address = '{$email}' AND eml_email_address IS NOT NULL),1,0) hasEmail,
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
			END as emailRank	 
		FROM contact ctt	 
		LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_active = 1
		LEFT JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id AND eml.eml_active = 1
		WHERE ((ctt_aadhaar_no <> '' AND ctt_aadhaar_no = '{$aadhaar}' AND ctt_aadhaar_no IS NOT NULL AND LENGTH(ctt_aadhaar_no) >=12 )
			OR (ctt_voter_no <> '' AND ctt_voter_no = '{$voter}' AND ctt_voter_no IS NOT NULL AND LENGTH(ctt_voter_no) >=8 )
			OR (ctt_license_no <> '' AND ctt_license_no = '{$license}' AND ctt_license_no IS NOT NULL AND LENGTH(ctt_license_no) >=8 )
			OR (ctt_pan_no <> '' AND ctt_pan_no = '{$pan}' AND ctt_pan_no IS NOT NULL AND LENGTH(ctt_pan_no) =10 )
			OR (phn_full_number <> '' AND REGEXP_REPLACE( phn_full_number, '\\D', '') = '{$phone}' AND phn_full_number IS NOT NULL)
			OR (eml_email_address <> '' AND eml_email_address = '{$email}' AND eml_email_address IS NOT NULL))
		AND ctt_id <> {$cttId} AND  ctt_active = 1 
		AND ctt_id NOT IN (SELECT distinct cmd_ctt_id FROM contact_merged_details WHERE cmd_duplicate_ctt_id = {$cttId}) 	
        AND ctt_id NOT IN (SELECT distinct cmd_duplicate_ctt_id FROM contact_merged_details WHERE cmd_ctt_id = {$cttId})  		
		GROUP BY ctt_id
		ORDER BY phoneRank DESC,emailRank DESC  ";
		$data	 = DBUtil::query($sql, DBUtil::MDB());
		return $data;
	}

	public static function getDocumentWeightById($cttId)
	{
		$sql = " SELECT ctt_id,
			(IF(license.doc_status=1,4,0)+ IF(pan.doc_status=1,3,0) 
						+IF(voter.doc_status=1,1,0) +IF(aadhaar.doc_status=1,1,0)) as docWeight
			FROM contact ctt
				LEFT JOIN document voter
					ON ctt.ctt_voter_doc_id = voter.doc_id AND voter.doc_active = 1
				LEFT JOIN document aadhaar
					ON ctt.ctt_aadhar_doc_id = aadhaar.doc_id AND aadhaar.doc_active = 1
				LEFT JOIN document pan
					ON ctt.ctt_pan_doc_id = pan.doc_id AND pan.doc_active = 1
				LEFT JOIN document license
					ON ctt.ctt_license_doc_id = license.doc_id AND license.doc_active = 1
			WHERE ctt_id=$cttId AND ctt_active = 1
			GROUP BY ctt_id
			ORDER BY docWeight DESC";

		$docData = DBUtil::queryRow($sql, DBUtil::SDB());
		return $docData;
	}

	public static function getRelatedIds($cttIds)
	{
		$sql	 = "SELECT GROUP_CONCAT(DISTINCT ctt_id) 
				FROM contact
				WHERE ctt_active=1 AND ctt_ref_code IN (
					SELECT ctt_ref_code FROM contact 
						WHERE ctt_id IN ({$cttIds}) AND ctt_active=1)";
		$cttList = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $cttList;
	}

	public static function getPrimaryByIds($cttIds)
	{
		$sql = "SELECT  ctt.ctt_id,ctt.ctt_ref_code, 
					IF(ctt.ctt_id =ctt.ctt_ref_code,1,0) contactWeight	, 
					IF((ctt.ctt_aadhaar_no <> ''  AND ctt.ctt_aadhaar_no IS NOT NULL 
						AND LENGTH(ctt.ctt_aadhaar_no) >=12 ),2,0) hasAdhaar,
					IF((ctt.ctt_voter_no <> ''  AND ctt.ctt_voter_no IS NOT NULL 
						AND LENGTH(ctt.ctt_voter_no) >=8 ),1,0) hasVoter,
					IF((ctt.ctt_license_no <> ''  AND ctt.ctt_license_no IS NOT NULL 
						AND LENGTH(ctt.ctt_license_no) >=8 ),4,-2) hasLicense,
					IF((ctt.ctt_pan_no <> ''  AND ctt.ctt_pan_no IS NOT NULL 
						AND LENGTH(ctt.ctt_pan_no) =10 ),2,0) hasPan,
                    ctt.ctt_active  
				FROM contact ctt     
				WHERE ctt.ctt_id IN ($cttIds) AND ctt.ctt_active =1				 
				ORDER BY contactWeight DESC, (hasLicense+hasAdhaar+hasPan+hasVoter ) DESC;";

		$relData = DBUtil::queryRow($sql, DBUtil::SDB());
		return $relData;
	}

	public static function getRelatedPrimaryListByType($refId = 0, $refType = 0, $showPrimaryOnly = true)
	{
		if ($refId == 0)
		{
			return 0;
		}
		switch ($refType)
		{
			case '0':
				$cttIds	 = $refId;
				break;
			case UserInfo::TYPE_CONSUMER :
				$cttIds	 = \Users::getCttIdsById($refId);
				break;
			case UserInfo::TYPE_VENDOR:
				$cttIds	 = \Vendors::getCttIdsById($refId);
				break;
			case UserInfo::TYPE_DRIVER :
				$cttIds	 = \Drivers::getCttIdsById($refId);
				break;
			default:
				echo "Wrong user type";
				exit;
				break;
		}
		$relData = ContactProfile::getPrimaryEntitiesByContact($cttIds, $showPrimaryOnly);

		return $relData;
	}

}
