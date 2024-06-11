<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;

/**
 * This is the model class for table "vendors".
 *
 * The followings are the available columns in table 'vendors':
 * @property integer $vnd_id
 * @property integer $vnd_user_id
 * @property integer $vnd_uvr_id
 * @property integer $vnd_contact_id
 * @property integer $vnd_ref_code
 * @property string $vnd_name
 * @property string $vnd_code
 * @property integer $vnd_type
 * @property string $vnd_longitude
 * @property string $vnd_latitude
 * @property integer $vnd_agreement_id
 * @property integer $vnd_firm_type
 * @property string $vnd_firm_pan
 * @property string $vnd_firm_ccin
 * @property string $vnd_firm_attach
 * @property integer $vnd_rel_tier
 * @property integer $vnd_application_aborted
 * @property integer $vnd_cat_type
 * @property integer $vnd_is_dco
 * @property integer $vnd_tnc_id
 * @property integer $vnd_tnc
 * @property string $vnc_tnc_datetime
 * @property integer $vnd_rm
 * @property integer $vnd_agmt_is_accept
 * @property string $vnd_agmt_accept_date
 * @property integer $vnd_agmt_is_ver
 * @property integer $vnd_active
 * @property string $vnd_modified_date
 * @property string $vnd_create_date
 * @property integer $vnd_is_merged
 * @property integer $vnd_merged_to
 * @property integer $vnd_delete_reason
 * @property integer $vnd_delete_other
 * @property integer $vnd_registered_platform
 *
 * The followings are the available model relations:
 * @property VendorDevice $vendorDevices
 * @property VendorPref $vendorPrefs
 * @property VendorAgreement $vndAgreement
 * @property VendorStats $vendorStats
 * @property Contact $vndCompany
 * @property Contact $vndOwner
 * @property Contact $vndContact
 * @property Users $vndUser
 * @property Vendors $vndRefCode
 * @property BookingCab[] $bookingCabs
 * @property BookingVendorRequest[] $bookingVendorRequests
 * @property VendorImages[] $vendorImages
 * @property VendorVehicle[] $vendorVehicles
 * 
 * @property VendorPref carModels
 */
class Vendors extends CActiveRecord
{

	const FR_COD_FREEZE			 = 1;
	const FR_CREDIT_LIMIT_FREEZE	 = 2;
	const FR_LOW_RATING_FREEZE	 = 3;
	const FR_DOC_PENDING_FREEZE	 = 4;
	const FR_MANUAL_FREEZE		 = 5;

	public $vnd_password1, $vnd_password, $vnd_owner, $vnd_address, $vnd_pan_no, $vnd_license_no, $vnd_voter_no, $vnd_aadhaar_no, $vnd_bank_name, $vnd_bank_branch, $vnd_bank_account_no, $vnd_bank_ifsc,
			$vnd_account_type, $vnd_beneficiary_name, $vnd_create_date1, $vnd_create_date2, $vnd_beneficiary_id, $local_vehicle_year, $local_vehicle_model, $serviceTypes;
	public $to_city, $from_city, $pickup_date, $booking_type, $vndIsBlocked, $vndIsFreezed, $vndUnApproved, $first_name, $last_name, $vnd_username, $vnd_car_model1, $vnd_car_year1, $vnd_car_number1, $vnd_driver_name1, $vnd_driver_license1, $vnd_car_model, $vnd_car_year, $vnd_car_number, $vnd_driver_name, $vnd_driver_license;
	public $vnd_home_zone, $vnd_accepted_zone, $vnd_agreement_date, $vnd_agreement_date1, $vnd_agreement_file_link, $vnd_voter_id_path, $vnd_aadhaar_path, $vnd_pan_path, $vnd_licence_path, $vnd_city, $vnd_state;
	public $vnd_zone, $vnd_cty, $vnd_operator, $tot_approved_vehicle, $tot_approved_driver, $city, $from_date, $to_date, $vnd_amount_pay, $vnd_amount;
	public $drivers_approved, $drivers_all, $vdrv_vnd_id, $vehicles_approved, $vehicles_all, $vvhc_vnd_id, $vnd_status, $vnd_source, $vnd_region, $vnd_security_paid;
	public $vnd_phone, $vnd_email, $vnd_company, $vnd_agreement_id, $vnd_contact_name, $vnd_log, $vnd_withdrawable_bal, $vnd_name1, $locale_contact_email, $vnd_service_class, $vnd_notification, $vnd_message, $vnd_subject, $vnd_sms, $vnd_vehicle_category;
//public $firm_type	 = [1 => 'Individual', 2 => 'Partnership', 3 => 'Private Limited', 4 => 'Limited'];
	public $vnd_is_voterid, $vnd_is_pan, $vnd_is_aadhar, $vnd_is_license, $vnd_is_agreement, $vnd_is_bank, $vnd_is_approve, $vnd_is_loggedin, $vnd_mod_day, $vnd_reset_desc, $bkgtypes, $zon_name, $vnd_is_nmi, $vnd_platform;
	public $firm_type	 = [1 => 'Individual', 2 => 'Business'];
	public $accType		 = [1 => 'Current', 0 => 'Saving'];
	public $filterType	 = [1 => 'Yes', 0 => 'No'];
	public $bookingType	 = [1 => 'Yes', 2 => 'No'];
	public $vnd_vehicle_type;
	public $vendorStatus = [0 => 'Deleted', 1 => 'Approved', 2 => 'Blocked / InActive / Rejected', 3 => 'Pending', 4 => 'Ready to Approve'];
	public $vnd_bkg_agent_id, $dayRange, $bkg_create_date1, $bkg_create_date2;

//0 => 'Deleted',

	/**
	 * @return string the associated database table name
	 */
	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => "vnd_active > 0",
		);
		return $arr;
	}

	public function tableName()
	{
		return 'vendors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		$ydate		 = date('Y');
		$minydate	 = $ydate - 10;
		return array(
			array('vnd_name', 'required', 'on' => 'insert'),
			array('vnd_user_id, vnd_contact_id, vnd_type, vnd_is_dco, vnd_rel_tier, vnd_application_aborted, vnd_is_dco, vnd_tnc_id, vnd_tnc, vnd_rm, vnd_agmt_is_accept, vnd_agmt_is_ver, vnd_active', 'numerical', 'integerOnly' => true),
			//array('vnd_cat_type, vnd_contact_name', 'required', 'on' => 'insert'),
			array('vnd_name, vnd_code, vnd_longitude, vnd_latitude, vnd_firm_pan, vnd_firm_ccin', 'length', 'max' => 200),
			array('vnd_firm_attach', 'length', 'max' => 200),
			array('vnc_tnc_datetime, vnd_agmt_accept_date, vnd_city,vnd_zone', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
//array('vnd_name, first_name, last_name, vnd_email,  vnd_tnc, vnd_city', 'required', 'on' => 'vendorjoin'),
			array('vnd_name, first_name, last_name,  vnd_tnc,vnd_city', 'required', 'on' => 'vendorjoin'),
			//array('vnd_email', 'email', 'on' => 'vendorjoin', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('vnd_name', 'length', 'max' => 200),
			//['vnd_email', 'email', 'message' => 'This email address is not valid'],
//['vnd_phone,vnd_alt_contact_number', 'numerical', 'integerOnly' => true],
//['vnd_phone', 'validatePhone', 'on' => 'insert,insertApp'],
//array('vnd_phone', 'checkDuplicatePhone', 'on' => 'insert,insertApp,vendorjoin'),
//array('vnd_phone', 'validatePhone', 'on' => 'insert,insertApp,vendorjoin'),
//array('vnd_email', 'checkDuplicateEmail', 'on' => 'insert,insertApp,vendorjoin'),
			array('vnd_id,vnd_rel_tier', 'required', 'on' => 'upgradeTire'),
			array('vnd_id,vnd_name', 'required', 'on' => 'upgradeName'),
			array('vnd_id,vnd_cat_type', 'required', 'on' => 'upgradeType'),
			array('vnd_contact_id,vnd_rel_tier', 'required', 'on' => 'agreementupdate'),
			array('vnd_id, vnd_active', 'required', 'on' => 'isApprove'),
			array('vnd_rel_tier', 'required', 'on' => 'dataagreementupdate'),
			array('vnd_id,vnd_code,vnd_name', 'required', 'on' => 'updateCode'),
			array('vnd_owner, vnd_email, vnd_phone, validatePhone, validatePhoneEmail', 'required', 'on' => 'unregVendorJoin'),
			//array('vnd_owner', 'required', 'on' => 'unregVendorJoin'),
			array('vnd_name, vnd_code', 'required', 'on' => 'unregUpdateVendorJoin'),
			array('vnd_name', 'required', 'on' => 'vendorjoins'),
			array('vnd_car_model, vnd_car_year, vnd_car_number,vnd_driver_name,vnd_driver_license', 'required', 'on' => 'vendorjoindetails'),
			array('vnd_car_model, vnd_car_year, vnd_car_number', 'required', 'on' => 'vendorjoindetailsnotdco'),
			array('vnd_id, vnd_user_id, vnd_contact_id, vnd_name, vnd_code, vnd_type, vnd_longitude, vnd_latitude, vnd_agreement_id, vnd_is_dco,vnd_registered_platform, vnd_firm_pan, vnd_firm_ccin, vnd_firm_attach, vnd_rel_tier, vnd_application_aborted, vnd_cat_type, vnd_is_dco, vnd_tnc_id, vnd_tnc, vnc_tnc_datetime, vnd_rm, vnd_agmt_is_accept, vnd_agmt_accept_date, vnd_agmt_is_ver, vnd_active, vnd_modified_date, vnd_create_date, to_city, from_city, pickup_date, vnd_create_date1, vnd_create_date2, vnd_region,vnd_is_merged,vnd_merged_to,vnd_ref_code,vnd_is_nmi', 'safe'),
			array('vnd_agreement_date1,vnd_is_dco,vnd_password1,vnd_status,vnd_owner,vnd_company', 'safe'),
			//array('vnd_name, vnd_phone, vnd_address, vnd_username', 'required', 'on' => 'update'),
			array('vnd_car_year', 'compare', 'operator' => '<=', 'compareValue' => $ydate, 'message' => "You can put maximum as $ydate", 'on' => 'vendorjoinVehicle'),
			array('vnd_car_year', 'compare', 'operator' => '>=', 'compareValue' => $minydate, 'message' => "Models before $minydate are out dated", 'on' => 'vendorjoinVehicle'),
			array('vnd_name', 'required', 'on' => 'update'),
			array('vnd_contact_name', 'required', 'on' => 'contactupdate'),
			array('vnd_contact_id', 'checkDuplicateVendor', 'on' => 'contactupdate'),
			array('vnd_car_number', 'checkDuplicateVehicleNo', 'on' => 'vendorjoinVehicle'),
			//array('vnd_username', 'unique', 'on' => 'insert,update'),
			array('vnd_operator,vnd_amount_pay,vnd_amount,vnd_rm,vnd_mod_day,vnd_cty,vndIsBlocked,vndIsFreezed,vndUnApproved', 'safe'),
		);
	}

//	public function afterSave()
//	{
//		parent::afterSave();
//		if ($this->vnd_code == null && $this->vnd_id > 0)
//		{
//			$codeArr		 = Filter::getCodeById($this->vnd_id, "vendor");
//			$this->vnd_code = $codeArr['code'];
//		}
//		return true;
//	}

	public function beforeValidate()
	{
		if ($this->vnd_agreement_date1 != NULL)
		{
			$this->vnd_agreement_date = DateTimeFormat::DatePickerToDate($this->vnd_agreement_date1);
		}
		if ($this->vnd_contact_id != '')
		{
			$arrEmails					 = ContactEmail::model()->findByContactID($this->vnd_contact_id);
			$this->locale_contact_email	 = $arrEmails[0];
		}
		return parent::beforeValidate();
	}

	public function afterSave()
	{
		parent::afterSave();
		VendorAgreement::model()->saveAgreement($this->vnd_id);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array(
			'vendorDevices'			 => array(self::HAS_ONE, 'VendorDevice', 'vdc_vnd_id'),
			'vendorPrefs'			 => array(self::HAS_ONE, 'VendorPref', 'vnp_vnd_id'),
			'vendorStats'			 => array(self::HAS_ONE, 'VendorStats', 'vrs_vnd_id'),
			//'vndCompany' => array(self::BELONGS_TO, 'Contact', 'vnd_company_id'),
//'vndOwner' => array(self::BELONGS_TO, 'Contact', 'vnd_contact_id'),
			'vndContact'			 => array(self::BELONGS_TO, 'Contact', 'vnd_contact_id'),
			'vndRefCode'			 => array(self::BELONGS_TO, 'Vendors', 'vnd_ref_code'),
			'vndAgreement'			 => array(self::HAS_ONE, 'VendorAgreement', 'vag_vnd_id'),
			'vndUser'				 => array(self::BELONGS_TO, 'Users', 'vnd_user_id'),
			'bookingCabs'			 => array(self::HAS_MANY, 'BookingCab', 'bcb_vendor_id'),
			'bookingVendorRequests'	 => array(self::HAS_MANY, 'BookingVendorRequest', 'bvr_vendor_id'),
			'vendorImages'			 => array(self::HAS_MANY, 'VendorImages', 'vni_vendor_id'),
			'vendorDrivers'			 => array(self::HAS_MANY, 'VendorDriver', 'vdrv_vnd_id'),
			'vendorVehicles'		 => array(self::HAS_MANY, 'VendorVehicle', 'vvhc_vnd_id'),
			'carModels'				 => array(self::HAS_MANY, 'carModels', 'vht_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vnd_id'					 => 'Vnd',
			'vnd_user_id'				 => 'Vnd User',
			'vnd_contact_id'			 => 'Contact',
			'vnd_contact_name'			 => 'Contact Name',
			'vnd_owner'					 => 'Owner Name',
			//'vnd_company_id' => 'Vnd Company',
			'vnd_name'					 => 'Name',
			'vnd_code'					 => 'Code',
			'vnd_address'				 => 'Address',
			'vnd_company'				 => 'Company Name',
			'vnd_type'					 => 'Type',
			'vnd_city'					 => 'City',
			'vnd_longitude'				 => 'Longitude',
			'vnd_latitude'				 => 'Latitude',
			'vnd_agreement_id'			 => 'Agreement',
			'vnd_firm_type'				 => 'Vendor Firm Type',
			'vnd_firm_pan'				 => 'Vendor Firm Pan',
			'vnd_firm_ccin'				 => 'Vendor Firm Ccin',
			'vnd_firm_attach'			 => 'Firm Attach',
			'vnd_rel_tier'				 => 'Rel Tier',
			'vnd_application_aborted'	 => 'Application Aborted',
			'vnd_cat_type'				 => 'Vendor Type',
			'vnd_tnc_id'				 => 'Vnd Tnc',
			'vnd_tnc'					 => 'Vnd Tnc',
			'vnc_tnc_datetime'			 => 'Vnc Tnc Datetime',
			'vnd_rm'					 => 'Vnd Rm',
			'vnd_agmt_is_accept'		 => 'Agmt Is Accept',
			'vnd_agmt_accept_date'		 => 'Agmt Accept Date',
			'vnd_agmt_is_ver'			 => 'Agmt Is Ver',
			'vnd_active'				 => 'Active',
			'vnd_modified_date'			 => 'Modified Date',
			'vnd_create_date'			 => 'Date added',
			'vnd_agreement_date1'		 => 'Agreement Date',
			'vnd_car_model'				 => 'Car Model',
			'vnd_car_year'				 => 'Car Year',
			'vnd_car_number'			 => 'Car Number',
			'vnd_driver_name'			 => 'Driver Name',
			'vnd_driver_license'		 => 'Driver license',
			'locale_contact_email'		 => 'Contact Email'
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

		$criteria->compare('vnd_id', $this->vnd_id);
		$criteria->compare('vnd_user_id', $this->vnd_user_id);
		$criteria->compare('vnd_contact_id', $this->vnd_contact_id);
//$criteria->compare('vnd_company_id',$this->vnd_company_id);
		$criteria->compare('vnd_name', $this->vnd_name, true);
		$criteria->compare('vnd_code', $this->vnd_code, true);
//$criteria->compare('vnd_company',$this->vnd_company,true);
		$criteria->compare('vnd_type', $this->vnd_type);
		$criteria->compare('vnd_longitude', $this->vnd_longitude, true);
		$criteria->compare('vnd_latitude', $this->vnd_latitude, true);
		$criteria->compare('vnd_agreement_id', $this->vnd_agreement_id);
		$criteria->compare('vnd_firm_type', $this->vnd_firm_type);
		$criteria->compare('vnd_firm_pan', $this->vnd_firm_pan, true);
		$criteria->compare('vnd_firm_ccin', $this->vnd_firm_ccin, true);
		$criteria->compare('vnd_firm_attach', $this->vnd_firm_attach, true);
		$criteria->compare('vnd_rel_tier', $this->vnd_rel_tier);
		$criteria->compare('vnd_application_aborted', $this->vnd_application_aborted);
		$criteria->compare('vnd_is_dco', $this->vnd_is_dco);
		$criteria->compare('vnd_cat_type', $this->vnd_cat_type);
		$criteria->compare('vnd_tnc_id', $this->vnd_tnc_id);
		$criteria->compare('vnd_tnc', $this->vnd_tnc);
		$criteria->compare('vnc_tnc_datetime', $this->vnc_tnc_datetime, true);
		$criteria->compare('vnd_rm', $this->vnd_rm);
		$criteria->compare('vnd_agmt_is_accept', $this->vnd_agmt_is_accept);
		$criteria->compare('vnd_agmt_accept_date', $this->vnd_agmt_accept_date, true);
		$criteria->compare('vnd_agmt_is_ver', $this->vnd_agmt_is_ver);
		$criteria->compare('vnd_active', $this->vnd_active);
		$criteria->compare('vnd_modified_date', $this->vnd_modified_date, true);
		$criteria->compare('vnd_create_date', $this->vnd_create_date, true);
		$criteria->compare('vnd_is_nmi', $this->vnd_is_nmi, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vendors the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getDeleteReasonList($type = '')
	{
		$reasons = [
			1	 => "Vendor doesn't want to continue application.",
			2	 => "Application rejected - requirements not met.",
			3	 => "Vendor was previously blocked",
			4	 => "Duplicate Vendor",
			5	 => "Other"];
		if ($type != '')
		{
			$value = $reasons[$type];
			return $value;
		}
		else
		{
			return $reasons;
		}
	}

	public function checkDuplicateVendor()
	{
		if ($this->vnd_contact_id != '')
		{
			$cnt = Vendors::model()->checkDuplicateContactByVendor($this->vnd_contact_id, $this->vnd_ref_code);
			if ($cnt > 0)
			{
				$this->addError('vnd_contact_id', "This contact is taken by another vendor.");
				return false;
			}
		}
		if ($this->vndContact->ctt_pan_no != '' && $this->isNewRecord)
		{
			$cnt = Vendors::model()->checkDuplicateContactByVendorPan($this->vndContact->ctt_pan_no, $this->vnd_ref_code);
			if ($cnt > 0)
			{
				$this->addError('vnd_contact_id', "This Pan no is taken by another vendor.");
				return false;
			}
		}
		return true;
	}

	public function saveData($oldData, $agreement_file = '', $agreement_file_tmp = '', $type = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$contactId		 = ContactProfile::getByEntityId($this->vnd_id, UserInfo::TYPE_VENDOR);
		$contactId		 = ($contactId == '') ? $this->vnd_contact_id : $contactId;
		$contEmailPhone	 = Contact::model()->getContactDetails($contactId);
		$otp			 = rand(10000, 99999);
		$userInfo		 = UserInfo::getInstance();
		$returnSet		 = new ReturnSet();
		$isNew			 = $this->isNewRecord;
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			$result = CActiveForm::validate($this, null, false);

			if ($result == '[]')
			{
				if ($isNew)
				{
					$phone		 = $this->vndContact->contactPhones[0]->phn_phone_no;
					$ext		 = $this->vndContact->contactPhones[0]->phn_phone_country_code;
					$phoneModel	 = $this->vndContact->contactPhones;
					foreach ($phoneModel as $value)
					{
						$value->phn_otp = $otp;
						$value->save();
					}
//$this->vndContact->contactPhones[0]->phn_otp = $verify_code;
//$this->vndContact->contactPhones[0]->save();
				}
				if (!$this->save())
				{
					throw new Exception('Failed to save vendor.');
				}
				$this->vnd_ref_code	 = $this->vnd_id;
				$arr				 = Filter::getCodeById($this->vnd_id, "vendor");
				if ($arr['success'] == 1)
				{
					$this->vnd_code = $arr['code'];
					$this->save();
				}

				$this->vendorPrefs->setAttribute('vnp_vnd_id', $this->vnd_id);
				$this->vendorStats->setAttribute('vrs_vnd_id', $this->vnd_id);
				if ($this->vendorDevices != NULL)
				{
					$this->vendorDevices->setAttribute('vdc_vnd_id', $this->vnd_id);
				}
				$resultPrefs = CActiveForm::validate($this->vendorPrefs, null, false);

				$resultStats = CActiveForm::validate($this->vendorStats, null, false);

				if ($resultPrefs != '[]' || $resultStats != '[]')
				{
					if ($resultPrefs != '[]' && $resultStats != '[]')
					{
						$arrResult = array_merge($resultPrefs, $resultStats);
					}
					else if ($resultPrefs != '[]')
					{
						$arrResult = $resultPrefs;
					}
					else if ($resultStats != '[]')
					{
						$arrResult = $resultStats;
					}
					throw new Exception($arrResult);
				}

				if (!$this->vendorPrefs->save() || !$this->vendorStats->save())
				{
//$this->vendorPrefs->errors;
					throw new Exception('Failed to save vendor.');
				}
				if ($this->vendorDevices != NULL)
				{
					if (!$this->vendorDevices->save())
					{
						throw new Exception('Failed to save vendor.');
					}
				}

//$contEmailPhone	 = Contact::model()->getContactDetails($this->vnd_contact_id);
//				$usersId = Users::model()->linkUserid($contEmailPhone['eml_email_address'], $contEmailPhone['phn_phone_no']);
//				if ($usersId != "")
//				{
//					$this->vnd_user_id = $usersId;
//					$this->save();
//				}
//				else
//				{
//					$chars		 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
//					$password	 = substr(str_shuffle($chars), 0, 4);
//					$this->createUserByVendor($this->vnd_id, $contEmailPhone, md5($password), 2);
//				}

				if ($agreement_file != '' && $agreement_file_tmp != '')
				{
					$uploadedFile = CUploadedFile::getInstance($this, "vnd_agreement_file_link");
					if ($uploadedFile != '')
					{
						$path	 = $this->uploadVendorFiles($uploadedFile, $this->vnd_id, 'agreement');
						$success = VendorAgreement::model()->saveDocument($this->vnd_id, $path, $userInfo, 'agreement', $this->vnd_agreement_date);
						if (!$success)
						{
							throw new Exception('Failed to save vendor agreement.');
						}
					}
				}
				if ($this->vnd_uvr_id > 0 && $type == 'unreg')
				{
					$uvrmodel				 = UnregVendorRequest::model()->findByPk($this->vnd_uvr_id);
					$uvrmodel->uvr_active	 = 0;
					if ($uvrmodel->save())
					{
						Document::model()->transferUnregData($uvrmodel, $this->vnd_id, $contactId);
					}
				}

				if ($isNew)
				{
					$emailWrapper	 = new emailWrapper();
					$emailWrapper->adminVendorSignupEmail($this, $contactId);
					$desc			 = "New Vendor created";
					$event			 = VendorsLog::VENDOR_CREATED;
					$isOtpSend		 = Contact::sendVerification($phone, Contact::TYPE_PHONE, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_VENDOR, 0, $this->vndContact->contactPhones[0]->phn_otp, $ext);
					$isEmailSend	 = Contact::sendVerification($contEmailPhone['eml_email_address'], Contact::TYPE_EMAIL, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_VENDOR);
				}
				else
				{
					$desc				 = "Vendor modified |";
					$getOldDifference	 = array_merge(array_diff_assoc($oldData['vendor'], $this->attributes) + array_diff_assoc($oldData['stats'], $this->vendorStats->attributes) + array_diff_assoc($oldData['pref'], $this->vendorPrefs->attributes));
					$changesForLog		 = " Old Values: " . $this->getModificationMSG($getOldDifference, false) . " " . $this->getModificationZonesMSG($getOldDifference, $oldData['pref'], $this->vendorPrefs->attributes, 1);
					$getNewDifference	 = array_merge(array_diff_assoc($this->attributes, $oldData['vendor']) + array_diff_assoc($this->vendorStats->attributes, $oldData['stats']) + array_diff_assoc($this->vendorPrefs->attributes, $oldData['pref']));
					$changesNewForLog	 = " New Values: " . $this->getModificationMSG($getNewDifference, false) . " " . $this->getModificationZonesMSG($getOldDifference, $oldData['pref'], $this->vendorPrefs->attributes, 2);
					$desc				 .= $changesForLog . '<br />' . $changesNewForLog;
					$event				 = VendorsLog::VENDOR_MODIFIED;
					if ($type == 'approve')
					{

						Vendors::accountApproveVendor($this->vnd_id);
//						$contactModel	 = Contact::model()->getContactDetails($contactId);
//						$objPhoneNumber	 = ContactPhone::getPrimaryNumber($contactId);
//						$email			 = ContactEmail::getPrimaryEmail($contactId);
//						if ($objPhoneNumber)
//						{
//							$countryCode = $objPhoneNumber->getCountryCode();
//							$number		 = $objPhoneNumber->getNationalNumber();
//						}
//						$params			 = ['vnd_id'			 => $this->vnd_id,
//							'full_name'			 => $contactModel['ctt_first_name'] . '' . $contactModel['ctt_last_name'],
//							'email'				 => $email,
//							'video_link'		 => 'https://youtu.be/AfbwgIJN0H0',
//							'app_link'			 => 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en',
//							'driver_app_link'	 => 'https://play.google.com/store/apps/details?id=com.gozocabs.driver&hl=en_US'
//						];
//						$emailwrapper	 = new emailWrapper();
//						$smsModel		 = new smsWrapper();
//						$payLoadData	 = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
//						AppTokens::model()->notifyVendor($this->vnd_id, $payLoadData, 'Your account has been approved. Please login to your for accepting and doing trips.', 'Approve Vendor');
//						$emailwrapper->mailToApproveVendor($params);
//						$whtResponse	 = WhatsappLog::informVendorsOnApprove($this->vnd_id);
//
//						//$smsModel->sendApproveVendor($this->vnd_id, $number, $countryCode);
//						VendorsLog::model()->createLog($this->vnd_id, "Vendor approved manually.", $userInfo, VendorsLog::VENDOR_APPROVE, false, false);
					}
				}


				VendorsLog::model()->createLog($this->vnd_id, $desc, $userInfo, $event, false, false);
				if ($contactId)
				{
					ContactProfile::updateEntity($contactId, $this->vnd_id, UserInfo::TYPE_VENDOR);
				}

//Added this DCO
				if ($this->vnd_cat_type == 1)
				{
					$contactModel	 = Contact::model()->findByPk($contactId);
					$drvName		 = $contactModel->ctt_first_name . ' ' . $contactModel->ctt_last_name;
//$driverModel	 = Drivers::model()->isIdExists($contactId);
					$contactPrfModel = ContactProfile::findByContactId($contactId);
					if ($contactPrfModel->cr_is_driver == '' || $contactPrfModel->cr_is_driver == NULL)
					{
						$res = Drivers::addDriverDetails($contactId, $drvName);
						if ($res->getStatus())
						{
							$data		 = ['vendor' => $this->vnd_id, 'driver' => $res->getData()];
							$resLinked	 = VendorDriver::model()->checkAndSave($data);
							$drvId		 = $data['driver'];
						}
					}
					else
					{
						$drvId = $contactPrfModel->cr_is_driver;
					}
					if ($drvId)
					{
						ContactProfile::updateEntity($contactId, $drvId, UserInfo::TYPE_DRIVER);
					}
				}
				if ($this->vnd_code != '')
				{
					$this->vnd_name = $this->generateName();
//For DCO
					if ($this->vnd_cat_type == 1)
					{
						$this->vnd_cat_type	 = 1;
						$this->vnd_is_dco	 = 1;
					}
					else
					{
						$this->vnd_cat_type	 = 2; //Vendor
						$this->vnd_is_dco	 = 0;
					}

					$this->save();
				}

				$success = DBUtil::commitTransaction($transaction);
				$returnSet->setStatus(true);
			}
			else
			{
				throw new Exception('Validation Failed.');
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$this->addError("vnd_id", $e->getMessage());
			$returnSet->setErrors($this->getErrors());
			DBUtil::rollbackTransaction($transaction);
			return $returnSet;
		}
//return $success;
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	public function getModificationZonesMSG($diff, $oldData, $newData, $type)
	{
		if ($diff['vnp_accepted_zone'])
		{
			$arrOld		 = explode(",", $oldData['vnp_accepted_zone']);
			$arrNew		 = explode(",", $newData['vnp_accepted_zone']);
			$commonList	 = array_intersect($arrOld, $arrNew);

			if ($commonList != [])
			{
				if ($type == 1)
				{
					$arrZoneData = array_diff($arrOld, $commonList);
					if (count($arrZoneData) == 0)
					{
						goto skip;
					}
				}
				else
				{
					$arrZoneData = array_diff($arrNew, $commonList);
					if (count($arrZoneData) == 0)
					{
						goto skip;
					}
				}
				$arrZone = Zones::model()->getZoneList1();
				foreach ($arrZoneData as $key)
				{
					$arrLable[] = $arrZone[$key];
				}
				$strZone = implode(',', $arrLable);
				$msg	 .= ' Accepted Zones: ' . $strZone . ' ';
			}
			skip:
			return $msg;
		}
	}

	public function setApprove($vndId, $userInfo)
	{
		$model				 = Vendors::model()->findByPk($vndId);
		$model->vnd_active	 = 1;
		$model->scenario	 = 'isApprove';
		if ($model->save())
		{
			$params	 = ['vnd_id'			 => $model->vnd_id,
				'full_name'			 => $model->vndContact->getName(),
				'email'				 => ContactEmail::getPrimaryEmail($model->vndContact->ctt_id),
				'video_link'		 => 'https://youtu.be/AfbwgIJN0H0',
				'app_link'			 => 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en',
				'driver_app_link'	 => 'https://play.google.com/store/apps/details?id=com.gozocabs.driver&hl=en_US'
			];
			emailWrapper::mailToApproveVendor($params);
			$desc	 = "Vendor Modified : " . VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_APPROVE);
			VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, VendorsLog::VENDOR_APPROVE, false, false);
		}
	}

	public function createUserByVendor($vndid, $contact, $password, $platform)
	{
		$sql	 = "INSERT INTO users (`usr_name`, `usr_lname`, `usr_email`, `usr_password`, `usr_country_code`, `usr_mobile`,`usr_create_platform`)
              VALUES ('" . $contact['ctt_first_name'] . "', '" . $contact['ctt_last_name'] . "', '" . $contact['eml_email_address'] . "','" . $password . "','" . $contact['phn_phone_country_code'] . "', '" . $contact['phn_phone_no'] . "','" . $platform . "')";
		DBUtil::command($sql)->execute();
		$userid	 = Yii::app()->db->getLastInsertID();
		$result	 = DBUtil::command('UPDATE `vendors` SET vnd_user_id = "' . $userid . '" WHERE vnd_id= "' . $vndid . '"')->execute();
		return $result;
	}

	public function uploadVendorFiles($uploadedFile, $vendor_id, $type = 'agreement')
	{
		$fileName	 = $vendor_id . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByVendorId = $dir . DIRECTORY_SEPARATOR . $vendor_id;
		if (!is_dir($dirByVendorId))
		{
			mkdir($dirByVendorId);
		}

		$foldertoupload	 = $dirByVendorId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVendorId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $vendor_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function findByCode($code)
	{
		$sql = "SELECT v2.vnd_id as cnt
FROM   `vendors` v1 INNER JOIN `vendors` v2 ON v2.vnd_id = v1.vnd_ref_code
WHERE  v1.vnd_code = '$code' AND v2.vnd_active > 0";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getList($command = false, $qry)
	{
		$homeCity = ($this->vnd_city != '') ? $this->vnd_city : '';
		if ($this->vndContact->ctt_city != '')
		{
			$homeCity = ($this->vndContact->ctt_city != '') ? $this->vndContact->ctt_city : '';
		}
		$vendorPhone2	 = '';
		$vendorCity		 = '';
		$vendorActive	 = ($this->vnd_active != '') ? $this->vnd_active : '';
		$vendorActive	 = implode(',', $vendorActive);
		$sql			 = " ";
		if ($this->vnd_service_class != '')
		{
			$strVehicleClass = " AND FIND_IN_SET({$this->vnd_service_class}, vendor_pref.vnp_is_allowed_tier)";
		}
		if ($this->vnd_vehicle_category != '')
		{
			switch ($this->vnd_vehicle_category)
			{
				case 1 :
					$strVehicleCategory	 = " AND vendor_stats.vrs_car_reg_compact_cnt > 0";
					break;
				case 2 :
					$strVehicleCategory	 = " AND vendor_stats.vrs_car_reg_suv_cnt > 0";
					break;
				case 3 :
					$strVehicleCategory	 = " AND vendor_stats.vrs_car_reg_sedan_cnt > 0";
					break;
			}
		}
		$sqlCount = "SELECT   v1.vnd_id
					FROM `vendors` v2 
					INNER JOIN vendors v1 ON v2.vnd_ref_code = v1.vnd_id
					INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = v1.vnd_id $strVehicleClass
					INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = v1.vnd_id $strVehicleCategory
					INNER JOIN contact_profile ON contact_profile.cr_is_vendor = v1.vnd_id AND cr_status=1 AND v1.vnd_id = v1.vnd_ref_code 
					INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
					LEFT JOIN contact_email ON contact.ctt_id = contact_email.eml_contact_id AND contact_email.eml_active = 1 
					LEFT JOIN contact_phone ON contact.ctt_id = contact_phone.phn_contact_id AND contact_phone.phn_active = 1 ";

		$sqlData = "SELECT v1.vnd_id, v1.vnd_name, v1.vnd_contact_id,
			v1.vnd_user_id, v1.vnd_code, v1.vnd_rel_tier,
			v1.vnd_cat_type, v1.vnd_type, v1.vnd_create_date,
			count(DISTINCT v3.vnd_id) cntMergedVnd,GROUP_CONCAT(DISTINCT v3.vnd_code) codeMergedVnd, 
			v1.vnd_active, vendor_pref.vnp_is_attached, vendor_pref.vnp_is_freeze,
			vendor_pref.vnp_oneway, vendor_pref.vnp_round_trip, vendor_pref.vnp_cod_freeze,
			vendor_pref.vnp_boost_enabled, vendor_pref.vnp_vhc_boost_count, vendor_stats.vrs_vnd_overall_rating,
			vendor_stats.vrs_security_amount, vendor_stats.vrs_security_receive_date,vendor_stats.vrs_last_logged_in, vendor_stats.vrs_car_reg_compact_cnt,
			vendor_stats.vrs_car_reg_sedan_cnt, vendor_stats.vrs_car_reg_suv_cnt, vendor_stats.vrs_approve_driver_count,vendor_stats.vrs_last_bkg_cmpleted,
			" . ($this->vnd_source == 232 ? 'IFNULL(lock_payment.bkg_pickup_date,"-")' : '"-"') . " as last_lock_date, 
			vendor_stats.vrs_approve_car_count, contact.ctt_city, contact.ctt_is_name_dl_matched,
			contact.ctt_is_name_pan_matched,contact.ctt_id,contact.ctt_name, contact.ctt_first_name, contact.ctt_last_name,
			contact.ctt_business_name, contact.ctt_address, contact_phone.phn_phone_no,contact_email.eml_email_address, 
			GROUP_CONCAT(DISTINCT CONCAT(phn_phone_no, '|', phn_is_primary, '|', phn_is_verified)) phn_phone_no, 
			GROUP_CONCAT(DISTINCT CONCAT(eml_email_address, '|', eml_is_primary, '|', eml_is_verified)) eml_email_address, 
			contact_profile.cr_contact_id 
			, vrs_pending_drivers, vrs_rejected_drivers, vrs_count_driver 
			, vrs_pending_cars, vrs_rejected_cars, vrs_count_car 
			, doc.vnd_agreement_lnk, doc.vnd_agreement_dt 
			FROM   `vendors` v2
				INNER JOIN vendors v1 ON v2.vnd_ref_code = v1.vnd_id AND v1.vnd_ref_code = v1.vnd_id
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = v1.vnd_id $strVehicleClass
				INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = v1.vnd_id $strVehicleCategory
				INNER JOIN contact_profile ON contact_profile.cr_is_vendor = v1.vnd_id AND cr_status=1 
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND contact.ctt_active = 1
				LEFT JOIN vendors v3 ON v3.vnd_ref_code = v1.vnd_ref_code AND v3.vnd_id<>v3.vnd_ref_code
				LEFT JOIN contact_email ON contact.ctt_id = contact_email.eml_contact_id AND contact_email.eml_active = 1 
				LEFT JOIN contact_phone ON contact.ctt_id = contact_phone.phn_contact_id AND contact_phone.phn_active = 1 
				LEFT JOIN 
					(SELECT   vendor_agreement.vag_vnd_id, vendor_agreement.vag_soft_path AS vnd_agreement_lnk, vendor_agreement.vag_soft_date AS vnd_agreement_dt
						FROM     `vendor_agreement`
						WHERE    `vendor_agreement`.vag_soft_date IN 
							(SELECT   MAX(vendor_agreement.vag_soft_date) AS vnd_agreement_dt
								FROM     `vendor_agreement`
								GROUP BY vendor_agreement.vag_vnd_id)
					GROUP BY vendor_agreement.vag_vnd_id) AS doc
				ON doc.vag_vnd_id = v1.vnd_id ";

		if ($this->vnd_vehicle_type != '')
		{
			$strVehicleType = "INNER JOIN vendor_vehicle ON vendor_vehicle.vvhc_vnd_id = v1.vnd_id AND vvhc_active = 1 
								INNER JOIN vehicles ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vhc_active = 1 AND vhc_type_id = {$this->vnd_vehicle_type}";

			$sqlCount	 .= $strVehicleType;
			$sqlData	 .= $strVehicleType;
		}
		if ($this->vnd_source == 232)
		{
			$agentWhere = '';
			if ($this->vnd_bkg_agent_id != '')
			{
				$agentWhere = " AND bkg.bkg_agent_id  = $this->vnd_bkg_agent_id";
			}
			$strLockPayment = "LEFT JOIN(SELECT DISTINCT (vnd.vnd_id), bcb.bcb_lock_vendor_payment,max(bkg.bkg_pickup_date) as bkg_pickup_date
								FROM `booking_cab` bcb
								INNER JOIN `booking` bkg ON bkg.bkg_bcb_id = bcb.bcb_id AND bkg.bkg_active=1 AND  bkg.bkg_status IN(3,5,6,7)
								INNER JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id AND vnd.vnd_active>0
								WHERE bcb.bcb_lock_vendor_payment = 1 $agentWhere
								GROUP BY vnd.vnd_id ORDER BY bkg.bkg_pickup_date desc) AS lock_payment ON lock_payment.vnd_id=v1.vnd_id";

			$sqlCount	 .= $strLockPayment;
			$sqlData	 .= $strLockPayment;
		}

		$sqlCount	 .= " WHERE cr_created = (SELECT MAX(cr_created) FROM contact_profile AS cp WHERE cp.cr_is_vendor = v1.vnd_id AND cr_status=1 AND cp.cr_contact_id=contact.ctt_ref_code)";
		$sqlData	 .= " WHERE cr_created = (SELECT MAX(cr_created) FROM contact_profile AS cp WHERE cp.cr_is_vendor = v1.vnd_id AND cr_status=1 AND cp.cr_contact_id=contact.ctt_ref_code)";

		if ($qry['searchdlmismatch'] != '')
		{
			$dlMismatchedVal = $qry['searchdlmismatch'];
			$sqlCondition	 .= "  AND contact.ctt_is_name_dl_matched  = $dlMismatchedVal";
			$sql			 .= "  AND contact.ctt_is_name_dl_matched  = $dlMismatchedVal";
		}
		if ($qry['searchpanmismatch'] != '')
		{
			$panMismatchedVal	 = $qry['searchpanmismatch'];
			$sqlCondition		 .= "  AND contact.ctt_is_name_pan_matched  = $panMismatchedVal";
			$sql				 .= "  AND contact.ctt_is_name_pan_matched  = $panMismatchedVal";
		}
		if ($qry['searchvndpaymentlock'] != '')
		{
			$vndPaymentLockVal	 = $qry['searchvndpaymentlock'];
			$sqlCondition		 .= "  AND lock_payment.bcb_lock_vendor_payment = $vndPaymentLockVal";
			$sql				 .= "  AND lock_payment.bcb_lock_vendor_payment = $vndPaymentLockVal";
		}
		if ($this->vendorPrefs->vnp_home_zone != '')
		{
			$sql .= " AND vendor_pref.vnp_home_zone IN ({$this->vendorPrefs->vnp_home_zone})";
		}

		if ($this->vnd_registered_platform == 1)
		{
			$sql .= " AND v2.vnd_registered_platform IN ({$this->vnd_registered_platform})";
		}
		if ($this->vnd_cat_type == 1)
		{
			$sql .= " AND v1.vnd_cat_type = 1";
		}

		if ($this->vendorPrefs->vnp_accepted_zone != '')
		{
			$sql .= " AND vendor_pref.vnp_accepted_zone IN ({$this->vendorPrefs->vnp_accepted_zone})";
		}
		if ($homeCity != '')
		{
			$sql .= " AND contact.ctt_city='$homeCity'";
		}
		if ($this->vnd_name != '')
		{
			$sql .= " AND (v2.vnd_name LIKE '%{$this->vnd_name}%' || 
		v2.vnd_code LIKE '%{$this->vnd_name}%' || 
		contact.ctt_first_name LIKE '%{$this->vnd_name}%' || 
		contact.ctt_last_name LIKE '%{$this->vnd_name}%' || 
		contact.ctt_business_name LIKE '%{$this->vnd_name}%')";
		}


		if ($this->vnd_owner != '')
		{
			$sql .= " AND (contact.ctt_first_name LIKE '%{$this->vnd_owner}%' || contact.ctt_last_name LIKE '%{$this->vnd_owner}%')";
		}
		if ($this->vnd_company != '')
		{
			$sql .= " AND contact.ctt_business_name LIKE '%{$this->vnd_company}%'";
		}
		if ($this->vndContact->contactPhones->phn_phone_no != '')
		{
			$sql .= " AND contact_phone.phn_phone_no='{$this->vndContact->contactPhones->phn_phone_no}' AND contact_phone.phn_active = 1";
		}
		if ($this->vndContact->contactEmails->eml_email_address != '')
		{
			$sql .= " AND contact_email.eml_email_address='{$this->vndContact->contactEmails->eml_email_address}' AND contact_email.eml_active = 1";
		}
		if ($this->vndContact->ctt_address != '')
		{
			$sql .= " AND contact.ctt_address LIKE '%{$this->vndContact->ctt_address}%'";
		}
		if (isset($this->vnd_id) && $this->vnd_id != "")
		{
			$sql .= " AND (vnd_id = {$this->vnd_id})";
		}
		if ($this->vndContact->ctt_id != "")
		{
			$sql .= " AND (contact.ctt_id = {$this->vndContact->ctt_id})";
		}

		$or = "";
		if (in_array(1, $this->bkgtypes) != '')
		{
			$query	 .= "  vendor_pref.vnp_oneway = 1";
			$or		 = " OR ";
		}
		if (in_array(2, $this->bkgtypes) != '')
		{
			$query	 .= $or . " vendor_pref.vnp_round_trip = 1";
			$or		 = " OR ";
		}
		if (in_array(3, $this->bkgtypes) != '')
		{
			$query	 .= $or . " vendor_pref.vnp_airport = 1";
			$or		 = " OR ";
		}
		if (in_array(4, $this->bkgtypes) != '')
		{
			$query	 .= $or . " vendor_pref.vnp_package = 1";
			$or		 = " OR ";
		}
		if (in_array(5, $this->bkgtypes) != '')
		{
			$query	 .= $or . " vendor_pref.vnp_daily_rental = 1";
			$or		 = " OR ";
		}
		if (in_array(14, $this->bkgtypes) != '')
		{
			$query	 .= $or . " vendor_pref.vnp_lastmin_booking = 1";
			$or		 = " OR ";
		}
		if (in_array(6, $this->bkgtypes) != '')
		{
			$query .= $or . " vendor_pref.vnp_tempo_traveller = 1";
		}
		if ($query != '')
		{
			$sql .= " AND (" . $query . ")";
		}

		if ($this->vnd_rel_tier > 0)
		{
			$tier	 = ($this->vnd_rel_tier == 2) ? 0 : $this->vnd_rel_tier;
			$sql	 .= " AND v2.vnd_rel_tier='{$tier}'";
		}
		if ($this->vnd_platform == 2)
		{
			$sql .= " AND vendor_stats.vrs_platform=2";
		}
		if ($this->vendorStats->vrs_vnd_overall_rating != '')
		{
			switch ($this->vendorStats->vrs_vnd_overall_rating)
			{
				case 1 :
					$sql .= " AND (vendor_stats.vrs_vnd_overall_rating BETWEEN 0 AND 0.9)";
					break;
				case 2 :
					$sql .= " AND (vendor_stats.vrs_vnd_overall_rating BETWEEN 1 AND 1.9)";
					break;
				case 3 :
					$sql .= " AND (vendor_stats.vrs_vnd_overall_rating BETWEEN 2 AND 2.9)";
					break;
				case 4 :
					$sql .= " AND (vendor_stats.vrs_vnd_overall_rating BETWEEN 3 AND 3.9)";
					break;
				case 5 :
					$sql .= " AND (vendor_stats.vrs_vnd_overall_rating BETWEEN 4 AND 5)";
					break;
			}
		}

		if ($this->vnd_source == 221 || $this->vnd_source == 232)
		{
			$sql .= " AND v2.vnd_active IN (1,2,3,4)";
		}
		else
		{
			if ($this->vnd_status != '')
			{
				switch ($this->vnd_status)
				{
					case 1:
						$sql .= " AND v2.vnd_active=1";
						break;
					case 2:
						$sql .= " AND v2.vnd_active=2";
						break;
					case 3:
						$sql .= " AND vendor_pref.vnp_is_freeze=1";
						break;
					case 4:
						$sql .= " AND vendor_pref.vnp_is_freeze=1 AND v2.vnd_active IN (1,2)";
						break;
					case 5:
						$sql .= " AND vendor_pref.vnp_is_freeze=2 AND v2.vnd_active IN (1,2)";
						break;
				}
			}
			else
			{
				$sql .= " AND v1.vnd_active IN (1,2)";
			}
		}


		if ($this->vnd_source != '')
		{
			switch ($this->vnd_source)
			{
				case 210:
					$sql .= " AND v1.vnd_id NOT IN (
                                SELECT vendors.vnd_id FROM `vendors` INNER JOIN vendor_agreement vag ON vag.vag_vnd_id = vendors.vnd_id 
								WHERE (vag.vag_soft_path!='' OR vag.vag_soft_path IS NOT NULL)
                            )";
					break;
				case 211:
					$sql .= " AND v1.vnd_id NOT IN (SELECT vendors.vnd_id
                                FROM `vendors` WHERE (
                                    ((contact.ctt_bank_name !='' OR contact.ctt_bank_name IS NULL) AND (contact.ctt_bank_branch !='' OR contact.ctt_bank_branch IS NULL) AND (contact.ctt_bank_ifsc !='' OR contact.ctt_bank_ifsc IS NULL) AND (contact.ctt_bank_account_no !='' OR contact.ctt_bank_account_no IS NULL)) OR (vendors.vnd_firm_pan!='' AND vendors.vnd_firm_pan IS NULL))
                                )";
					break;
				case 221:
					$sql .= " AND v1.vnd_id IN (
                                   SELECT vendors.vnd_id
									FROM `vendors` 
									INNER JOIN contact_profile ON contact_profile.cr_is_vendor = vnd_id AND cr_status=1 AND vnd_id = vnd_ref_code 
									INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
									JOIN vendor_agreement ON vendor_agreement.vag_vnd_id = vendors.vnd_id 
									WHERE (contact.ctt_voter_doc_id IS NOT NULL OR contact.ctt_voter_doc_id <>'')
									AND (contact.ctt_pan_doc_id IS NOT NULL OR contact.ctt_pan_doc_id <>'')
									AND (contact.ctt_aadhar_doc_id IS NOT NULL OR contact.ctt_aadhar_doc_id <>'')
									AND (contact.ctt_license_doc_id IS NOT NULL OR contact.ctt_license_doc_id <>'')
									AND (vendor_agreement.vag_soft_path IS NOT NULL OR vendor_agreement.vag_soft_path <>'')
									AND vendors.vnd_active IN (3)
                                )";
					break;
			}
		}
		if ($this->vnd_security_paid == 1)
		{
			$sql .= " AND vendor_stats.vrs_security_amount > 0 ";
		}
		if ($this->vnd_security_paid == 2)
		{
			$sql .= " AND vendor_stats.vrs_security_amount <= 0 ";
		}
		$sql .= " GROUP BY v2.vnd_ref_code";

#echo $sqlData . $sql;
#die();

		if ($command == false)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount$sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider("$sqlData$sql", [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 =>
					['vnd_name', 'ctt_business_name', 'phn_phone_no', 'eml_email_address', 'vrs_security_amount', 'last_lock_date', 'vnd_create_date'],
					'defaultOrder'	 => ''],
				'pagination'	 => [],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll("$sqlData$sql", DBUtil::SDB());
		}
	}

	public function getRating()
	{
		return array(1	 => '0 to 0.9',
			2	 => '1 to 1.9',
			3	 => '2 to 2.9',
			4	 => '3 to 3.9',
			5	 => '4 to 4.9/5');
	}

	public function getStatusList()
	{
		return array(
			1	 => 'ACTIVE',
			2	 => 'BLOCKED',
			3	 => 'FROZEN',
			4	 => 'ADMIN FROZEN');
// return array(1 => 'Freezed', 2 => 'Unfreeze');
	}

	public static function getTierList()
	{
		return array(1 => 'Golden Tier', 2 => 'Silver Tier');
	}

	public function setDataForVendorjoin($arr, $socialuserid = 0, $platform = 0)
	{
		$name				 = trim($arr['first_name'] . " " . $arr['last_name']);
		$phone				 = trim($arr['phn_phone_no']);
		$email				 = trim($arr['eml_email_address']);
		$city				 = trim($arr['vnd_city']);
		$cityLatLong		 = Cities::model()->findByPk($city);
		$getNearestZoneList	 = Zones::model()->getZoneListByCityId($cityLatLong->cty_lat, $cityLatLong->cty_long, $maxDistance		 = 500);
		$acceptedZoneList	 = implode(', ', $getNearestZoneList);
		$vndown				 = $arr['vnp_cars_own'];
		$isdriver			 = $arr['vnd_cat_type'];
		$carmodel			 = explode(',', $arr['vnd_car_model1']);
		$caryear			 = explode(',', $arr['vnd_car_year1']);
		$carnumber			 = explode(',', $arr['vnd_car_number1']);
		$drivername			 = explode(',', $arr['vnd_driver_name1']);
		$driverlicense		 = explode(',', $arr['vnd_driver_license1']);
		$transaction		 = DBUtil::beginTransaction();
		try
		{

			$model			 = new Vendors();
			$modelVendPref	 = new VendorPref();
			$modelVendStats	 = new VendorStats();
			$modelVendDevice = new VendorDevice();
			$modelContact	 = new Contact();
			$cityModel		 = Cities::model()->findByPk($city);
			$model->vnd_name = $name . "-" . $cityModel->cty_name;
			if ($isdriver == 1 && $vndown == 1)
			{
				$modelContact->ctt_license_no = $driverlicense[0];
			}

			$chars								 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password							 = substr(str_shuffle($chars), 0, 4);
			$model->vnd_password1				 = $password;
			$modelVendPref->vnp_is_attached		 = 0;
			$model->vnd_tnc						 = 1;
			$model->vnd_tnc_id					 = 6;
			$model->vnd_tnc_datetime			 = new CDbExpression('NOW()');
			$model->vnd_user_id					 = $socialuserid;
			$modelVendDevice->vdc_device		 = $_SERVER['HTTP_USER_AGENT'];
			$modelContact->ctt_city				 = $city;
			$model->vnd_contact_name			 = $name;
			$modelVendPref->vnp_accepted_zone	 = $acceptedZoneList;
			$modelContact->ctt_first_name		 = $arr['first_name'];
			$modelContact->ctt_last_name		 = $arr['last_name'];
			$modelContact->ctt_user_type		 = 1;
			$modelContact->ctt_address			 = $cityModel->cty_name;
			$modelContact->ctt_city				 = $city;
			$modelContact->ctt_state			 = $cityModel->cty_state_id;
			$modelVendStats->vrs_platform		 = $platform;

			$modelVendPref->vnp_cars_own = $vndown;
			$model->vnd_cat_type		 = $isdriver;
			if (isset($city) && $city != '')
			{
				$zoneData						 = Zones::model()->getNearestZonebyCity($city);
				$modelVendPref->vnp_home_zone	 = $zoneData['zon_id'];
			}
			$model->vnd_active	 = 3;
			$result				 = [];
			$result				 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				if ($model->save())
				{
					$modelVendPref->setAttribute('vnp_vnd_id', $model->vnd_id);
					$modelVendPref->save();
					$modelVendStats->setAttribute('vrs_vnd_id', $model->vnd_id);
					$modelVendStats->save();
					$modelVendDevice->setAttribute('vdc_vnd_id', $model->vnd_id);
					$modelVendDevice->save();

					$isContactDriver = Drivers::model()->getContactByDrivers($email, $phone);
					if (!$isContactDriver)
					{
						$modelContact->contactEmails = $modelContact->convertToContactEmailObjects($email);
						$modelContact->contactPhones = $modelContact->convertToContactPhoneObjects($phone);
						$modelContact->save();
						$modelContact->saveEmails();
						$modelContact->savePhones();
						ContactEmail::setPrimaryEmail($modelContact->ctt_id);
						ContactPhone::setPrimaryPhone($modelContact->ctt_id);
					}

//$model->vnd_contact_id	 = $modelContact->ctt_id;
					$model->vnd_ref_code	 = $model->vnd_id;
					$model->vnd_contact_id	 = ($isContactDriver['drv_contact_id']) ? $isContactDriver['drv_contact_id'] : $modelContact->ctt_id;
					$model->update();
					$usersId				 = Users::model()->linkUserid($email, $phone);
					if ($usersId != "")
					{
						$model->vnd_user_id = $usersId;
						$model->update();
					}
					else
					{
						$contactArray = array('ctt_first_name' => $arr['first_name'], 'ctt_last_name' => $arr['last_name'], 'eml_email_address' => $email, 'phn_phone_country_code' => '91', 'phn_phone_no' => $phone);
						$model->createUserByVendor($model->vnd_id, $contactArray, md5($password), 1);
					}

					$vndCode = Filter::getCodeById($model->vnd_id, "vendor");
					if ($vndCode['success'] == 1)
					{
						$model->vnd_code = $vndCode['code'];
						$model->save();
					}
					if ($modelContact->ctt_id)
					{
						ContactProfile::updateEntity($modelContact->ctt_id, $model->vnd_id, UserInfo::TYPE_VENDOR);
					}
					if ($model->vnd_code != '')
					{
						$model->vnd_name = $this->generateName();
						$model->save();
					}
					for ($i = 0; $i < $vndown; $i++)
					{

						if ($vndown == 1 && $isdriver == 1 && !$isContactDriver)
						{
							$driverModel				 = new Drivers();
							$driverModel->drv_name		 = $drivername[$i];
							$driverModel->drv_contact_id = $modelContact->ctt_id;
							$socialUserClount			 = Drivers::model()->getAllDriverIdsByUserId($socialuserid);
							if ($socialUserClount == null)
							{
								$driverModel->drv_user_id = $socialuserid;
							}
							if (!$driverModel->save())
							{
								throw new Exception('Driver not saved correctly');
							}
						}

						$vehicleExist = VendorVehicle::model()->getVehiclebyVehicleNumber($carnumber[$i]);
						if (!$vehicleExist)
						{
							$vehiclesModel				 = new Vehicles();
							$vehiclesModel->vhc_type_id	 = $carmodel[$i];
							$vehiclesModel->vhc_number	 = $carnumber[$i];
							$vehiclesModel->vhc_year	 = $caryear[$i];
							if (!$vehiclesModel->save())
							{
								throw new Exception('Vehicles not saved correctly');
							}
						}
						else
						{
							$vehiclesModel->vhc_id = $vehicleExist;
						}
						$vehiclesId						 = $vehiclesModel->vhc_id;
						$vendorVehicleModel				 = new VendorVehicle();
						$vendorVehicleModel->vvhc_vhc_id = $vehiclesModel->vhc_id;
						$vendorVehicleModel->vvhc_vnd_id = $model->vnd_id;
						$vendorVehicleModel->save();

						if ($vndown == 1 && $isdriver == 1 && !$isContactDriver)
						{
							$driverId						 = ($driverModel->drv_id) ? $driverModel->drv_id : $isContactDriver['drv_id'];
							$vendorDriverModel				 = new VendorDriver();
							$vendorDriverModel->vdrv_vnd_id	 = $model->vnd_id;
							$vendorDriverModel->vdrv_drv_id	 = $driverId;
							$vendorDriverModel->save();

							$drvModel = Drivers::model()->findByPk($driverId);

							$drvModel->drv_ref_code = $driverId;
							$drvModel->save();
						}
					}
					VendorStats::model()->updateCountDrivers($model->vnd_id);
					VendorStats::model()->updateCarTypeCount($model->vnd_id);
					$modelAgmt = VendorAgreement::model()->findByVndId($model->vnd_id);
					if (!$modelAgmt)
					{
						$modelAgmt2				 = new VendorAgreement();
						$modelAgmt2->vag_vnd_id	 = $model->vnd_id;
						$modelAgmt2->vag_active	 = 0;
						$modelAgmt2->save();
					}
					$event_id			 = VendorsLog::VENDOR_CREATED;
					$desc				 = "New Vendor created";
					$userInfo			 = UserInfo::getInstance();
					$userInfo->userType	 = 2;
					VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $event_id, false, false);
					$emailObj			 = new emailWrapper();
					$emailObj->vendorjoinEmail($arr, $cityModel->cty_name, $model->vnd_id, $password);
					DBUtil::commitTransaction($transaction);
				}
				return ['password' => $password, 'success' => true];
			}
			else
			{
				return $result['success'] = false;
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$message			 = $e->getMessage();
			return $result['success']	 = false;
		}
	}

	public function passwordResetForVendor($vendorModel, $vendorModel1)
	{
		if ($vendorModel != '')
		{
			if ($vendorModel->vnd_email != "")
			{
				$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$password					 = substr(str_shuffle($chars), 0, 4);
				$vendorModel->vnd_password1	 = $password;
				$vendorModel->save();
				$emailObj					 = new emailWrapper();
				$emailObj->attachTaxiMail($vendorModel->vnd_id, $password);
			}
		}
		else if ($vendorModel1 != '')
		{
			if ($vendorModel1->vnd_email != "")
			{
				$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$password					 = substr(str_shuffle($chars), 0, 4);
				$vendorModel1->vnd_password1 = $password;
				$vendorModel1->save();
				$emailObj					 = new emailWrapper();
				$emailObj->attachTaxiMail($vendorModel1->vnd_id, $password);
			}
		}
	}

	public function getViewDetailbyId($vndId)
	{
		$sql	 = "SELECT contact.ctt_id,contact.ctt_ref_code,
				`vnd_id`,
				 vendors.vnd_rel_tier,
				`vnd_name`,
				`vnd_code`,
				`vnd_is_dco`,
				`vnd_cat_type`,	
				`usr_name` as vnd_username,
				`phn_phone_no` as vnd_phone, 
				`eml_email_address` as vnd_email, 
				cr_contact_id, 
					 contact.ctt_name as vnd_contact_person, 
				`vnp_preferred_time_slots` as vnd_preferred_time,
				`phn_phone_country_code` as vnd_phone_country_code,
				`phn_phone_no` as vnd_contact_number,
				`phn_phone_no` as vnd_alt_contact_number, 
					contact.`ctt_address` as vnd_address,
				 NULL as `vnd_route_served`,
					contact.`ctt_business_name` as business_name,
					contact.`ctt_user_type` ,
					contact.ctt_business_type as business_type,
					contact.ctt_first_name , 
					contact.ctt_last_name , 
					(SELECT CONCAT(contact.ctt_first_name,' ',contact.ctt_last_name) 
					FROM contact WHERE ctt_id = ctt_owner_id) as vnd_owner, 
				`vnp_sedan_count` as vnd_sedan_count,
				`vnp_compact_count` as vnd_compact_count,
				`vnp_suv_count` as vnd_suv_count,
				 NULL as `vnd_sedan_rate`,
				 NULL as `vnd_compact_rate`,
				 NULL as `vnd_suv_rate`,
				`vnp_oneway` as vnd_booking_type,
				 vcty.cty_id as`vnd_city`,
				 stt_id,stt_name,
				 contact.ctt_city,vcty.cty_name,contact.ctt_state,
				`vnd_active`,
				`vnd_modified_date`,
				`vnd_create_date`,
				`vnd_type`,
				`vdc_device` as vnd_device,
				`vdc_os_version` as vnd_os_version,
				`vdc_device_uuid` as vnd_device_uuid,
				`vdc_apk_version` as vnd_apk_version,
				`vdc_mac_address` as vnd_mac_address, 
				 NULL as vnd_ip_address,
				`vdc_serial` as vnd_serial,
				`vnd_longitude`,
				`vnd_latitude`,
				`vnd_tnc_id`,
				`vnd_tnc`,
				`vnd_tnc_datetime`, 
				`vnp_is_attached` as vnd_is_exclusive, 
				vendor_stats.`vrs_vnd_overall_rating` as vnd_overall_rating, 
				`vrs_overall_score` as vnd_overall_score,
				 NULL as vnd_total_vehicles,
				`vrs_total_trips` as vnd_total_trips,
				`vrs_last_thirtyday_trips` as vnd_last_thirtyday_trips,
				 NULL as vnd_total_drivers,
				contact.`ctt_bank_name` as vnd_bank_name,
				contact.`ctt_bank_branch` as vnd_bank_branch,
				contact.`ctt_beneficiary_name` as vnd_beneficiary_name,
				contact.`ctt_account_type` as vnd_account_type,
				contact.`ctt_bank_ifsc` as vnd_bank_ifsc,
				contact.`ctt_bank_account_no` as vnd_bank_account_no,
				contact.`ctt_beneficiary_id` as vnd_beneficiary_id,
				`vrs_total_amount` as vnd_total_amount,
				`vrs_last_thirtyday_amount` as vnd_last_thirtyday_amount,
				`vrs_last_trip_datetime` as vnd_last_trip_datetime,
				`vrs_first_trip_datetime` as vnd_first_trip_datetime,
				 NULL as `vnd_code_password`,
				`vnp_home_zone` as vnd_home_zone,
				`vnp_accepted_zone` as vnd_accepted_zone,
				`vnp_excluded_cities` as vnd_excluded_cities,
				 NULL  as`vnd_log`,
				`vnp_notes` as vnd_notes,
				`vrs_credit_limit` as vnd_credit_limit,
				`vrs_security_amount` as vnd_security_amount,
				`vrs_security_receive_date` as vnd_security_receive_date,
				 0 as `vnd_deposited`,
				 NULL as `vnd_return_zone`,
				`vrs_credit_throttle_level` as vnd_credit_throttle_level,
				`vrs_mark_vend_count` as vnd_mark_vendor_count,
				 NULL as `vnd_assign`,
				`vnp_is_freeze` as vnd_is_freeze, 
				 NULL as `agt_is_freeze`,
				 0 as `vnd_agreement`,
				 vag_soft_date as vnd_agreement_date,
                 vag_soft_flag,
				 0 as `vnd_incorporation_year`,
				`vnd_firm_type`, 
				`vnd_firm_pan`,
				`vnd_firm_ccin`,
				 NULL as `vnd_photo_path`,
				`vrs_effective_credit_limit` as vnd_effective_credit_limit,
				`vrs_effective_overdue_days` as vnd_effective_overdue_days,
				`vnd_rm` as vnd_admin,
				`vrs_driver_mismatch_count` as vnd_driver_mismatch_count,
				`vrs_car_mismatch_count` as vnd_car_mismatch_count,
				`vrs_withdrawable_balance` as withdrawable_balance,
				`vnp_cod_freeze` AS vnd_cod_freeze,
				 vnp_oneway, vnp_round_trip,vnp_multi_trip, vnp_airport,vnp_package,vnp_flexxi,
                 vnp_daily_rental, vnp_lastmin_booking,vnp_tempo_traveller,
				`vnd_application_aborted`,
				`vnp_mod_day` as vnd_mod_day,
				`vnp_invoice_date` as vnd_invoice_date,
				`vnp_settle_date` as vnd_settle_date,
				vcty.cty_name vnd_city_name,
				hzon.zon_id,hzon.zon_name,
				hzon.zon_name vnd_home_zone,
				NULL as  vnd_return_zone_name,
				contact.ctt_license_no as vnd_license_no,
				contact.ctt_license_exp_date as vnd_license_exp_date,
				contact.ctt_aadhaar_no as vnd_aadhaar_no,
				contact.ctt_voter_no as vnd_voter_no,
				contact.ctt_pan_no as vnd_pan_no,
				contact.ctt_dl_issue_authority as vnd_license_issue_auth,
                vendor_pref.vnp_boost_enabled,vendor_pref.vnp_vhc_boost_count
				FROM vendors 
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
				INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
				LEFT JOIN contact_profile ON contact_profile.cr_is_vendor=vendors.vnd_id AND cr_status=1
                LEFT JOIN contact ctt ON ctt.ctt_id=cr_contact_id AND ctt.ctt_active = 1 
				LEFT JOIN contact ON contact.ctt_id=ctt.ctt_ref_code AND contact.ctt_active = 1  
			    LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id AND  contact_email.eml_is_verified=1 AND contact_email.eml_active =1
                LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_verified=1 AND contact_phone.phn_active =1 
				LEFT JOIN vendor_device ON vendor_device.vdc_vnd_id = vendors.vnd_id
                LEFT JOIN vendor_agreement ON vendor_agreement.vag_vnd_id = vendors.vnd_id
                LEFT JOIN users ON users.user_id = vendors.vnd_user_id
				LEFT JOIN cities vcty ON vcty.cty_id = contact.ctt_city
				LEFT JOIN states stt ON stt.stt_id = vcty.cty_state_id
				LEFT JOIN zones hzon ON hzon.zon_id = vendor_pref.vnp_home_zone   
				WHERE vendors.vnd_id=:vndId 
                AND contact.ctt_id=contact.ctt_ref_code
				GROUP BY vendors.vnd_id,cr_created DESC";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['vndId' => $vndId]);

		if ($data['vnd_accepted_zone'] != null)
		{
			DBUtil::getINStatement($data['vnd_accepted_zone'], $bindString2, $params2);
			$vnd_accepted_sql				 = "SELECT GROUP_CONCAT(DISTINCT acczon.zon_name ORDER BY acczon.zon_name ASC SEPARATOR ', ') vnd_accepted_zone_name   FROM  zones acczon WHERE acczon.zon_id IN ($bindString2) ";
			$data['vnd_accepted_zone_name']	 = DBUtil::queryScalar($vnd_accepted_sql, DBUtil::SDB(), $params2);
		}
		else
		{
			$data['vnd_accepted_zone_name'] = "";
		}

		if ($data['vnd_excluded_cities'] != null)
		{
			DBUtil::getINStatement($data['vnd_excluded_cities'], $bindString3, $params3);
			$vnd_excluded_sql					 = "SELECT GROUP_CONCAT(DISTINCT exc.cty_name ORDER BY exc.cty_name ASC SEPARATOR ', ') vnd_excluded_cities_name   FROM  cities exc WHERE exc.cty_id IN ($bindString3) ";
			$data['vnd_excluded_cities_name']	 = DBUtil::queryScalar($vnd_excluded_sql, DBUtil::SDB(), $params3);
		}
		else
		{
			$data['vnd_excluded_cities_name'] = "";
		}
		$vndIds = Vendors::getRelatedIds($vndId);

		$data['vnd_security_amount'] = AccountTransactions::getSecurityAmount($vndIds);
// AccountTransDetails::model()->calAmntByVendorReffBoth($vndId);
		return $data;
	}

	public function getContactByVndId($vndId)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')   FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code INNER JOIN vendors v3 ON v3.vnd_ref_code = v2.vnd_id  WHERE v1.vnd_id = '" . $vndId . "'";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		$sql = "SELECT `phn_phone_no` AS vnd_contact_number FROM vendors
                    INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1   
                    INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_id =contact.ctt_ref_code AND contact.ctt_active =1
                    LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_primary = 0 AND contact_phone.phn_active=1 AND contact_phone.phn_is_verified = 1
                WHERE vendors.vnd_id IN ($vndIds) AND vendors.vnd_id = vendors.vnd_ref_code GROUP By vnd_contact_number";
		return DBUtil::queryAll($sql);
	}

	public function getCodebyid($vndId = 0)
	{
		$vndCode = '';
		$vndId	 = ($vndId > 0) ? $vndId : $this->vnd_id;
		if ($vndId > 0)
		{
			$arr = [
				'0'	 => 'Z',
				'1'	 => 'A',
				'2'	 => 'B',
				'3'	 => 'C',
				'4'	 => 'D',
				'5'	 => 'E',
				'6'	 => 'F',
				'7'	 => 'G',
				'8'	 => 'H',
				'9'	 => 'I'];
			foreach (str_split(str_pad($vndId, 6, 0, STR_PAD_LEFT)) as $v)
			{
				$vndCode .= $arr[$v];
			}
			return $vndCode;
		}
		return false;
	}

	public function getIdByCode($vndCode)
	{
		return self::model()->findByAttributes(array('vnd_code' => $vndCode));
	}

	public static function getCollectionReport($qry = [], $command = false)
	{
		$order		 = ($qry['order'] != '') ? $qry['order'] : '';
		$name		 = ($qry['name'] != '') ? $qry['name'] : '';
		$zone		 = ($qry['zone'] != '') ? $qry['zone'] : '';
		$city		 = ($qry['city'] != '') ? $qry['city'] : '';
		$payableFor	 = ($qry['payableFor'] != '') ? $qry['payableFor'] : '';
		$amount		 = ($qry['amount'] != '') ? $qry['amount'] : '';
		$vndid		 = ($qry['vndid'] != '') ? $qry['vndid'] : '';
		$admin		 = ($qry['admin'] != '') ? $qry['admin'] : '';
		$modDay		 = ($qry['modDay'] != '') ? $qry['modDay'] : '';
		$dayRange	 = ($qry['dayRange'] != '') ? $qry['dayRange'] : '';

		$where = "";
		if ($zone != '')
		{
			$where .= " AND vnp.vnp_home_zone IN ($zone)";
		}
		if ($name != '')
		{
			$where .= " AND (vnd.vnd_name LIKE '%$name%')";
			#$vndName .= " AND (v1.vnd_name LIKE '%$name%')";
		}
		if ($city != '')
		{
			$where .= " AND ctt.ctt_city=$city";
		}
		if ($admin != '')
		{
			$where .= " AND vnd.vnd_rm=$admin";
		}
		if ($vndid != '')
		{
			$where .= " AND vnd.vnd_id=$vndid";
		}
		if ($modDay != '')
		{
			$where .= " AND vnp.vnp_mod_day=$modDay";
		}

		if ($payableFor != '')
		{
			if ($payableFor == 1)
			{
				$amt	 = ($amount > 0) ? -$amount : 0;
				$having	 = "totTrans<$amt";
			}
			else if ($payableFor == 2)
			{
				$amt	 = ($amount > 0) ? $amount : 0;
				$having	 = "totTrans>$amt";
			}
		}
		else
		{
			$having = "totTrans<100000";
		}

		if ($dayRange != null)
		{
			$range = "vrs.vrs_last_trip_datetime BETWEEN DATE_SUB(NOW(), INTERVAL $dayRange DAY) AND NOW()";
		}
		else
		{
			$range = "vrs.vrs_last_trip_datetime >= '2018-04-01 00:00:00' OR vrs.vrs_last_trip_datetime IS NULL";
		}
		$sql = "SELECT vnd.vnd_ref_code as vnd_id, vnd.vnd_name, vnd.vnd_code, vnd.vnd_contact_id, ctt.ctt_id as contact_id, 
				CONCAT(adm.adm_fname, ' ', adm.adm_lname) AS relation_manager, 
				vrs.vrs_credit_limit AS vnd_credit_limit, GROUP_CONCAT(DISTINCT(ctt_beneficiary_id) SEPARATOR ' | ') AS vnd_beneficiary_id, 
				vrs.vrs_effective_credit_limit AS vnd_effective_credit_limit, 
				vrs.vrs_effective_overdue_days AS vnd_effective_overdue_days, 
				vrs.vrs_security_amount AS vnd_security_amount1, 
				vrs.vrs_security_receive_date, vrs.vrs_locked_amount, 
				vrs_avg30 AS vsm_avg30, vrs_avg10 AS vsm_avg10, vrs.vrs_last_bkg_cmpleted  last_trip_completed_date, 
				vnp.vnp_is_freeze AS vnd_is_freeze, vnp.vnp_home_zone, ctt.ctt_city, vnd.vnd_rm, vnd_active, 
				vnp.vnp_cod_freeze AS vnd_cod_freeze, vrs.vrs_total_trips AS trips, vrs.vrs_vnd_overall_rating AS rating, 
				MAX(ctt.ctt_bank_details_modify_date) as bankdetails_modify_date, 
				COUNT(DISTINCT ctt.ctt_id) cntContact, 
				a.totTrans,b.securityAmount AS vnd_security_amount,
				IF(vnp.vnp_is_freeze <> 0 OR vnd.vnd_active <> 1, 0, GREATEST((  (-1 * totTrans) - vrs.vrs_locked_amount), 0)) withdrawable_balance,
				MAX(apt_last_login) apt_last_login,
				cty_name, vrs_dependency,
                IF(b.securityAmount <> vrs.vrs_security_amount,1,0) hasDifferentSecurityAmount
				FROM vendors vnd 
				INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id 
				INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id 
					AND ctp.cr_status = 1 
				INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1  
				INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
				INNER JOIN 
				(
					SELECT v1.vnd_ref_code, SUM(atd.adt_amount) totTrans 
					FROM vendors v1 
					INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = v1.vnd_id 
						AND atd.adt_active = 1 AND atd.adt_status = 1 
					INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id 
						AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 AND act.act_active = 1 
						AND act.act_status = 1 
					WHERE 1 AND act.act_date >= '2021-04-01 00:00:00'  
					GROUP BY v1.vnd_ref_code 
					HAVING {$having} 
				) a ON vnd.vnd_ref_code = a.vnd_ref_code 
				LEFT JOIN 
				(
					SELECT v1.vnd_ref_code, IFNULL(SUM(adt.adt_amount), 0) securityAmount
						FROM vendors v1
					INNER JOIN account_trans_details adt ON
						adt.adt_trans_ref_id = v1.vnd_id AND adt.adt_ledger_id IN(57, 14) 
						AND adt.adt_active = 1 AND adt.adt_status = 1
					INNER JOIN account_transactions act ON
						adt.adt_trans_id = act.act_id AND act.act_active = 1 AND act.act_status = 1
					INNER JOIN account_trans_details adt1 ON
						adt1.adt_trans_id = act.act_id AND adt1.adt_active = 1 AND adt1.adt_status = 1 
						AND adt1.adt_ledger_id = 34
					WHERE act.act_date >= '2021-04-01 00:00:00'
					GROUP BY v1.vnd_ref_code 
				) b ON vnd.vnd_ref_code = b.vnd_ref_code 
				LEFT JOIN admins adm ON adm.adm_id = vnd.vnd_rm 
				LEFT JOIN app_tokens apt ON apt.apt_entity_id = vnd.vnd_id AND apt.apt_status = 1 
					AND apt.apt_user_type = 2
				LEFT JOIN cities ON cty_id = ctt_city
				WHERE ({$range}) {$where} 
				GROUP BY vnd.vnd_ref_code 
				HAVING {$having} ";

		$sqlCount = $sql;

		if ($command == false)
		{
			$pageSize		 = 50;
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB3(),
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes' =>
					['vnd_name', 'totTrans', 'lastTrans', 'lastTransDate', 'vnd_is_freeze', 'vnd_effective_credit_limit', 'withdrawable_balance',
						'vnd_effective_overdue_days', 'last_trip_completed_date', 'vnd_amount_pay', 'vnd_amount', 'relation_manager', 'vnd_security_amount',
						'lastTransSent', 'lastTransSentDate', 'vsm_avg10', 'vsm_avg30', 'bankdetails_modify_date'],
				],
				'keyField'		 => 'vnd_id',
				'pagination'	 => ['pageSize' => $pageSize],
			]);

			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	public function getCollectionReport_OLD($qry = [], $command = false)
	{
		$order		 = ($qry['order'] != '') ? $qry['order'] : '';
		$name		 = ($qry['name'] != '') ? $qry['name'] : '';
		$zone		 = ($qry['zone'] != '') ? $qry['zone'] : '';
		$city		 = ($qry['city'] != '') ? $qry['city'] : '';
		$payableFor	 = ($qry['payableFor'] != '') ? $qry['payableFor'] : '';
		$amount		 = ($qry['amount'] != '') ? $qry['amount'] : '';
		$vndid		 = ($qry['vndid'] != '') ? $qry['vndid'] : '';
		$admin		 = ($qry['admin'] != '') ? $qry['admin'] : '';
		$modDay		 = ($qry['modDay'] != '') ? $qry['modDay'] : '';
		$dayRange	 = ($qry['dayRange'] != '') ? $qry['dayRange'] : '';

		$where = "";
		if ($zone != '')
		{
			$where .= " AND vnp.vnp_home_zone IN ($zone)";
		}
		if ($name != '')
		{
			$where	 .= " AND (vnd.vnd_name LIKE '%$name%')";
			$vndName .= " AND (v1.vnd_name LIKE '%$name%')";
		}
		if ($city != '')
		{
			$where .= " AND ctt.ctt_city=$city";
		}
		if ($admin != '')
		{
			$where .= " AND vnd.vnd_rm=$admin";
		}
		if ($vndid != '')
		{
			$where .= " AND vnd.vnd_id=$vndid";
		}
		if ($modDay != '')
		{
			$where .= " AND vnp.vnp_mod_day=$modDay";
		}

		if ($payableFor != '')
		{
			if ($payableFor == 1)
			{
				$amt	 = ($amount > 0) ? -$amount : 0;
				$having	 = "totTrans<$amt";
			}
			else if ($payableFor == 2)
			{
				$amt	 = ($amount > 0) ? $amount : 0;
				$having	 = "totTrans>$amt";
			}
		}
		else
		{
			$having = "totTrans<100000";
		}

		if ($dayRange != null)
		{
			$range = "vrs.vrs_last_trip_datetime BETWEEN DATE_SUB(NOW(), INTERVAL $dayRange DAY) AND NOW()";
		}
		else
		{
			$range = "vrs.vrs_last_trip_datetime >= '2018-04-01 00:00:00' OR vrs.vrs_last_trip_datetime IS NULL";
		}

		$sql = "SELECT vnd.vnd_id, vnd.vnd_name, vnd.vnd_code, vnd.vnd_contact_id, ctt.ctt_id as contact_id, 
				CONCAT(adm.adm_fname, ' ', adm.adm_lname) AS relation_manager, 
				vrs.vrs_credit_limit AS vnd_credit_limit, ctt_beneficiary_id AS vnd_beneficiary_id, 
				vrs.vrs_effective_credit_limit AS vnd_effective_credit_limit, vrs.vrs_effective_overdue_days AS vnd_effective_overdue_days, 
				vrs.vrs_security_amount AS vnd_security_amount, vrs.vrs_security_receive_date, vrs.vrs_locked_amount, 
				vrs_avg30 AS vsm_avg30, vrs_avg10 AS vsm_avg10, vrs.vrs_last_bkg_cmpleted  last_trip_completed_date, 
				vnp.vnp_is_freeze AS vnd_is_freeze, vnp.vnp_home_zone, ctt.ctt_city, vnd.vnd_rm, vnd_active, 
				vnp.vnp_cod_freeze AS vnd_cod_freeze, vrs.vrs_total_trips AS trips, vrs.vrs_vnd_overall_rating AS rating, 
				ctt.ctt_bank_details_modify_date as bankdetails_modify_date, COUNT(DISTINCT ctt.ctt_id) cntContact, 
				a.totTrans, 
				IF(vnp.vnp_is_freeze <> 0 OR vnd.vnd_active <> 1, 0, GREATEST((  (-1 * totTrans) - vrs.vrs_locked_amount), 0)) withdrawable_balance,
				MAX(apt_last_login) apt_last_login,
				cty_name,
				vrs_dependency
				FROM vendors vnd 
				INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id 
				INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1 
				INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1  
				INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
				INNER JOIN 
				(
					SELECT v1.vnd_id, SUM(atd.adt_amount) totTrans 
					FROM vendors v1 
					INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = v1.vnd_id AND atd.adt_active = 1 AND atd.adt_status = 1 
					INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 AND act.act_active = 1 AND act.act_status = 1 
					WHERE 1 AND act.act_date >= '2021-04-01 00:00:00' {$vndName} 
					GROUP BY v1.vnd_id 
					HAVING {$having} 
				) a ON vnd.vnd_id = a.vnd_id 
				LEFT JOIN admins adm ON adm.adm_id = vnd.vnd_rm 
				LEFT JOIN app_tokens apt ON apt.apt_entity_id = vnd.vnd_id AND apt.apt_status = 1 AND apt.apt_user_type = 2
				LEFT JOIN cities ON cty_id = ctt_city
				WHERE ({$range}) {$where} 
				GROUP BY vnd.vnd_ref_code 
				HAVING {$having} ";

		$sqlCount = $sql;

		if ($command == false)
		{
			$pageSize		 = 50;
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB3(),
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes' =>
					['vnd_name', 'totTrans', 'lastTrans', 'lastTransDate', 'vnd_is_freeze', 'vnd_effective_credit_limit', 'withdrawable_balance',
						'vnd_effective_overdue_days', 'last_trip_completed_date', 'vnd_amount_pay', 'vnd_amount', 'relation_manager', 'vnd_security_amount',
						'lastTransSent', 'lastTransSentDate', 'vsm_avg10', 'vsm_avg30', 'bankdetails_modify_date'],
				],
				'keyField'		 => 'vnd_id',
				'pagination'	 => ['pageSize' => $pageSize],
			]);

			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3());
		}
	}

	/* public function getCollectionReport_OLD($qry = [], $command = false)
	  {
	  $order		 = ($qry['order'] != '') ? $qry['order'] : '';
	  $name		 = ($qry['name'] != '') ? $qry['name'] : '';
	  $zone		 = ($qry['zone'] != '') ? $qry['zone'] : '';
	  $city		 = ($qry['city'] != '') ? $qry['city'] : '';
	  $payableFor	 = ($qry['payableFor'] != '') ? $qry['payableFor'] : '';
	  $amount		 = ($qry['amount'] != '') ? $qry['amount'] : '';
	  $vndid		 = ($qry['vndid'] != '') ? $qry['vndid'] : '';
	  $admin		 = ($qry['admin'] != '') ? $qry['admin'] : '';
	  $modDay		 = ($qry['modDay'] != '') ? $qry['modDay'] : '';
	  $dayRange	 = ($qry['dayRange'] != '') ? $qry['dayRange'] : '';

	  $where = "";
	  if ($zone != '')
	  {
	  $where .= " AND vnp.vnp_home_zone IN ($zone)";
	  }
	  if ($name != '')
	  {
	  $where .= " AND (vnd.vnd_name LIKE '%$name%')";
	  }
	  if ($city != '')
	  {
	  $where .= " AND ctt.ctt_city=$city";
	  }
	  if ($admin != '')
	  {
	  $where .= " AND vnd.vnd_rm=$admin";
	  }
	  if ($vndid != '')
	  {
	  $where .= " AND vnd.vnd_id=$vndid";
	  }
	  if ($modDay != '')
	  {
	  $where .= " AND vnp.vnp_mod_day=$modDay";
	  }
	  if ($payableFor != '')
	  {
	  if ($payableFor == 1)
	  {
	  $amt	 = ($amount > 0) ? -$amount : 0;
	  $having	 = "totTrans<$amt";
	  }
	  else if ($payableFor == 2)
	  {
	  $amt	 = ($amount > 0) ? $amount : 0;
	  $having	 = "totTrans>$amt";
	  }
	  }
	  else
	  {
	  $having = "totTrans<100000";
	  }
	  //		if ($command == true)
	  //		{
	  //			$having = "totTrans <> 0";
	  //		}

	  if ($dayRange != null)
	  {
	  $range = "vrs.vrs_last_trip_datetime BETWEEN DATE_SUB(NOW(), INTERVAL $dayRange DAY) AND NOW()";
	  }
	  else
	  {
	  $range = "vrs.vrs_last_trip_datetime >= '2018-04-01 00:00:00' OR vrs.vrs_last_trip_datetime IS NULL";
	  }

	  $sql = " SELECT *,
	  IF(a.vnd_is_freeze <> 0 OR vnd_active <> 1,0,GREATEST((  (-1 * totTrans) - a.vrs_locked_amount), 0)) withdrawable_balance
	  FROM ( SELECT
	  vnd.vnd_id,
	  vnd.vnd_name,
	  vnd.vnd_code,
	  vnd.vnd_contact_id,
	  ctt.ctt_id as contact_id,
	  CONCAT(adm.adm_fname, ' ', adm.adm_lname) AS relation_manager,
	  vrs.vrs_credit_limit AS vnd_credit_limit,
	  ctt_beneficiary_id AS vnd_beneficiary_id,
	  vrs.vrs_effective_credit_limit AS vnd_effective_credit_limit,
	  vrs.vrs_effective_overdue_days AS vnd_effective_overdue_days,
	  vrs.vrs_security_amount AS vnd_security_amount,
	  vrs.vrs_security_receive_date,
	  vrs.vrs_locked_amount,
	  vrs_avg30 AS vsm_avg30,
	  vrs_avg10 AS vsm_avg10,
	  SUM(atd.adt_amount) totTrans,
	  vrs.vrs_last_bkg_cmpleted  last_trip_completed_date,
	  vnp.vnp_is_freeze AS vnd_is_freeze,
	  vnp.vnp_home_zone,
	  ctt.ctt_city,
	  vnd.vnd_rm,
	  vnd_active,
	  vnp.vnp_cod_freeze AS vnd_cod_freeze,
	  vrs.vrs_total_trips AS trips,
	  vrs.vrs_vnd_overall_rating AS rating,
	  ctt.ctt_bank_details_modify_date as bankdetails_modify_date,
	  COUNT(DISTINCT ctt.ctt_id) cntContact
	  FROM vendors vnd
	  INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
	  INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1
	  INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code
	  INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
	  INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = vnd.vnd_id AND atd.adt_active = 1
	  INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 AND atd.adt_type = 2
	  AND act.act_active = 1
	  LEFT JOIN admins adm ON adm.adm_id = vnd.vnd_rm
	  WHERE ($range) AND vnd.vnd_id = vnd.vnd_ref_code AND act.act_date >= '2021-04-01 00:00:00' $where
	  GROUP BY vnd.vnd_ref_code
	  HAVING $having ";

	  $sqlCount	 = "SELECT
	  SUM(atd.adt_amount) totTrans
	  FROM     vendors vnd
	  INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
	  INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1
	  INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code
	  INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
	  INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = vnd.vnd_id AND atd.adt_active = 1
	  INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id =14 AND atd.adt_type = 2  AND act.act_active = 1
	  WHERE ($range) AND vnd.vnd_id = vnd.vnd_ref_code AND act.act_date >= '2021-04-01 00:00:00'  $where
	  GROUP BY vnd.vnd_ref_code
	  HAVING $having
	  ";
	  $sql		 = $sql . " ) as a ";
	  echo $sqlCount	 = $sqlCount;
	  die();
	  if ($command == false)
	  {
	  //$defaultOrder	 = ($order != '') ? $order : 'vnd_name ASC';
	  $pageSize		 = 50;
	  #$count			 = Yii::app()->db1->createCommand("SELECT COUNT(*) FROM ($sqlCount) abc")->queryScalar();
	  $count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3())->queryScalar();
	  $dataprovider	 = new CSqlDataProvider($sql, [
	  'db'			 => DBUtil::SDB3(),
	  'totalItemCount' => $count,
	  'sort'			 =>
	  ['attributes' =>
	  ['vnd_name', 'totTrans', 'lastTrans',
	  'lastTransDate', 'vnd_is_freeze',
	  'vnd_effective_credit_limit', 'withdrawable_balance',
	  'vnd_effective_overdue_days', 'last_trip_completed_date',
	  'vnd_amount_pay', 'vnd_amount', 'relation_manager', 'vnd_security_amount',
	  'lastTransSent', 'lastTransSentDate', 'vsm_avg10', 'vsm_avg30', 'bankdetails_modify_date'],
	  //'defaultOrder'	 => $defaultOrder,
	  ],
	  'keyField'		 => 'vnd_id',
	  'pagination'	 => ['pageSize' => $pageSize],
	  ]);

	  return $dataprovider;
	  }
	  else
	  {
	  return DBUtil::query($sql, DBUtil::SDB3());
	  }
	  } */

	public function getCollectionReportNew($qry = [], $command = false)
	{
		$order		 = ($qry['order'] != '') ? $qry['order'] : '';
		$name		 = ($qry['name'] != '') ? $qry['name'] : '';
		$zone		 = ($qry['zone'] != '') ? $qry['zone'] : '';
		$city		 = ($qry['city'] != '') ? $qry['city'] : '';
		$payableFor	 = ($qry['payableFor'] != '') ? $qry['payableFor'] : '';
		$amount		 = ($qry['amount'] != '') ? $qry['amount'] : '';
		$vndid		 = ($qry['vndid'] != '') ? $qry['vndid'] : '';
		$admin		 = ($qry['admin'] != '') ? $qry['admin'] : '';
		$modDay		 = ($qry['modDay'] != '') ? $qry['modDay'] : '';

		$where = "";
		if ($zone != '')
		{
			$where .= " AND vnp.vnp_home_zone IN ($zone)";
		}
		if ($name != '')
		{
			$where .= " AND (vnd.vnd_name LIKE '%$name%')";
		}
		if ($city != '')
		{
			$where .= " AND ctt.ctt_city=$city";
		}
		if ($admin != '')
		{
			$where .= " AND vnd.vnd_rm=$admin";
		}
		if ($vndid != '')
		{
			$where .= " AND vnd.vnd_id=$vndid";
		}
		if ($modDay != '')
		{
			$where .= " AND vnp.vnp_mod_day=$modDay";
		}
		if ($payableFor != '')
		{
			if ($payableFor == 1)
			{
				$amt	 = ($amount > 0) ? -$amount : 0;
				$having	 = "totTrans<$amt";
			}
			else if ($payableFor == 2)
			{
				$amt	 = ($amount > 0) ? $amount : 0;
				$having	 = "totTrans>$amt";
			}
		}
		else
		{
			$having = "totTrans<100000";
		}
		if ($command == true)
		{
			$having = "totTrans <> 0";
		}

		$randomNumber	 = rand();
		$tempTable		 = "PartnerBalence" . $randomNumber;

		DBUtil::dropTempTable($tempTable);

		$sqlTemp = "(INDEX my_index_name (vnd_id)) SELECT sum(adt_amount)as vendor_amount,vrs_security_amount as vnd_security_amount,
			    vendor_stats.vrs_locked_amount as locked_amount,vnp_is_freeze,vnd_active,vnd_name,vnd_code,vnd_id
                FROM   account_trans_details adt 
				INNER JOIN vendors vnd ON adt.adt_trans_ref_id = vnd.vnd_id
				INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vnd.vnd_id
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                WHERE  adt.adt_active = 1 AND act.act_active=1
                AND adt.adt_status = 1
                AND adt.adt_ledger_id = 14
				$where
                AND adt.adt_type = 2 GROUP BY vnd_id ";
#echo $sqlTemp;exit;
		$res	 = DBUtil::createTempTable($tempTable, $sqlTemp);
//Yii::app()->db1->createCommand($sqlTemp)->queryScalar();

		$sql = "SELECT *, 
		IF(a.vnd_is_freeze <> 0 ,0,GREATEST((  (-1 * a.totTrans) - a.lockAmount), 0)) AS withdrawable_balance 
          FROM ( SELECT 
				vnd.vnd_id,
				vnd.vnd_name,
				vnd.vnd_code,
				vnd.vnd_contact_id,
				ctt.ctt_id as contact_id,
	   CONCAT(adm.adm_fname, ' ', adm.adm_lname) AS relation_manager,
       vrs.vrs_credit_limit AS vnd_credit_limit,
       ctt_beneficiary_id AS vnd_beneficiary_id,
       vrs.vrs_effective_credit_limit AS vnd_effective_credit_limit,
       vrs.vrs_effective_overdue_days AS vnd_effective_overdue_days,
       xyz.vnd_security_amount As vnd_security_amount,
       vrs.vrs_security_receive_date ,
       vrs.vrs_locked_amount,
       vrs_avg30 AS vsm_avg30,
       vrs_avg10 AS vsm_avg10,
       xyz.vendor_amount AS totTrans,
	   xyz.locked_amount AS lockAmount,
       vrs.vrs_last_bkg_cmpleted AS last_trip_completed_date,
       vnp.vnp_is_freeze AS vnd_is_freeze, 
	   vnp.vnp_home_zone, 
	   ctt.ctt_city,
	   vnd.vnd_rm,
       vnp.vnp_cod_freeze AS vnd_cod_freeze,
       vrs.vrs_total_trips AS trips,
       vrs.vrs_vnd_overall_rating AS rating,
	   ctt.ctt_bank_details_modify_date as bankdetails_modify_date,
	   COUNT(DISTINCT ctt.ctt_id) cntContact
	 FROM vendors vnd
	 INNER JOIN $tempTable xyz ON xyz.vnd_id=vnd.vnd_id 	
     INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id          
	 INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1
	 INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code    
     INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
     INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = vnd.vnd_id AND atd.adt_active = 1
     INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 AND atd.adt_type = 2
        AND act.act_active = 1  
     LEFT JOIN admins adm ON adm.adm_id = vnd.vnd_rm
	WHERE (vrs.vrs_last_trip_datetime >= '2018-04-01 00:00:00' OR vrs.vrs_last_trip_datetime IS NULL)  $where 
		GROUP BY vnd_ref_code
		HAVING $having ";
#$recordset	 = DBUtil::queryAll($sql1,DBUtil::SDB());



		$sqlCount	 = "SELECT   vnd.vnd_id, vnp.vnp_home_zone, ctt.ctt_city,vnd.vnd_rm,
					SUM(atd.adt_amount) totTrans,vnd.vnd_name
					FROM     vendors vnd
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id 
					INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1
					INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code  
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
					INNER  JOIN account_trans_details atd ON atd.adt_trans_ref_id = vnd.vnd_id AND atd.adt_active = 1
					INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id =14 AND atd.adt_type = 2  AND act.act_active = 1
                    WHERE (vrs.vrs_last_trip_datetime >= '2018-04-01 00:00:00' OR vrs.vrs_last_trip_datetime IS NULL) $where 
					GROUP BY vnd_ref_code
					HAVING $having
					";
		$sql		 = $sql . " ) as a ";
		$sqlCount	 = $sqlCount;
		DBUtil::dropTempTable($createTempTable);
		if ($command == false)
		{
//$defaultOrder	 = ($order != '') ? $order : 'vnd_name ASC';
			$pageSize		 = 500;
			$count			 = Yii::app()->db1->createCommand("SELECT COUNT(*) FROM ($sqlCount) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => Yii::app()->db1,
				'totalItemCount' => $count,
				'sort'			 =>
				['attributes' =>
					['vnd_name', 'totTrans', 'lastTrans',
						'lastTransDate', 'vnd_is_freeze',
						'vnd_effective_credit_limit', 'withdrawable_balance',
						'vnd_effective_overdue_days', 'last_trip_completed_date',
						'vnd_amount_pay', 'vnd_amount', 'relation_manager', 'vnd_security_amount',
						'lastTransSent', 'lastTransSentDate', 'vsm_avg10', 'vsm_avg30', 'bankdetails_modify_date'],
				//'defaultOrder'	 => $defaultOrder,
				],
				'keyField'		 => 'vnd_id',
				'pagination'	 => ['pageSize' => $pageSize],
			]);

			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql);
		}
	}

	public function getTdsReport($date1 = '', $date2 = '', $type = 'data')
	{
		$sql = "SELECT v1.vnd_id,
							   v1.vnd_name, 
                               c1.ctt_name, 
							   c1.ctt_pan_no, 
							   SUM(IF(atd1.adt_ledger_id IN(37,55),atd1.adt_amount,0))*-1 as totalTds, 
                                                           SUM(IF(atd1.adt_ledger_id=22,atd1.adt_amount,0)) as totalTripPurchased 
							FROM account_transactions act
							INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_ledger_id=14 AND atd.adt_active=1
							INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_ledger_id IN(22,37,55) AND atd1.adt_active=1
							INNER JOIN vendors v1 ON v1.vnd_id=atd.adt_trans_ref_id AND v1.vnd_id=v1.vnd_ref_code
							INNER JOIN contact_profile cp ON cp.cr_is_vendor=v1.vnd_id AND cp.cr_status=1
							INNER JOIN contact c1 ON c1.ctt_id=cp.cr_contact_id AND c1.ctt_ref_code=c1.ctt_id AND c1.ctt_active =1
							WHERE act_date BETWEEN '$date1' AND '$date2 23:59:59' AND act.act_type = 5
						GROUP BY v1.vnd_id";

		$sqlCount = "SELECT COUNT(*) AS totalTds,v1.vnd_id
						    FROM account_transactions act
							INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_ledger_id=14 AND atd.adt_active=1
							INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_ledger_id IN(22,37,55) AND atd1.adt_active=1
							INNER JOIN vendors v1 ON v1.vnd_id=atd.adt_trans_ref_id AND v1.vnd_id=v1.vnd_ref_code
							INNER JOIN contact_profile cp ON cp.cr_is_vendor=v1.vnd_id AND cp.cr_status=1
							INNER JOIN contact c1 ON c1.ctt_id=cp.cr_contact_id AND c1.ctt_ref_code=c1.ctt_id AND c1.ctt_active =1
							WHERE act_date BETWEEN '$date1' AND '$date2 23:59:59' AND act.act_type = 5
						GROUP BY v1.vnd_id";

		if ($type == 'data')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['defaultOrder' => 'totalTds  DESC']
			]);

			return $dataprovider;
		}
		else if ($type == 'command')
		{
			$sql		 .= " ORDER BY totalTds DESC";
			$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB());
			return $recordset;
		}
	}

	public static function checkDuplicateContactByVendor($ctcId, $vndId = 0)
	{
		$param	 = ['cttId' => $ctcId];
		$cond	 = "";
		if ($vndId > 0)
		{
			$param['refCode']	 = $vndId;
			$cond				 = " AND vnd_ref_code<>:refCode";
		}
//$sql = "SELECT COUNT(1) as cnt FROM vendors WHERE vnd_active >0 AND vnd_contact_id=:cttId" . $cond;
		$sql = "SELECT COUNT(1) as cnt FROM vendors 
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1
			INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_id =contact.ctt_ref_code AND contact.ctt_active =1 AND contact.ctt_id =:cttId
			WHERE vnd_active >0" . $cond;
		$cnt = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $cnt;
	}

	public static function checkDuplicateContactByVendorPan($panId, $vndId = 0)
	{
		$param	 = ['panId' => $panId];
		$cond	 = "";
		if ($vndId > 0)
		{
			$param['refCode']	 = $vndId;
			$cond				 = " AND vnd_ref_code<>:refCode";
		}
		$sql = "SELECT COUNT(1) as cnt FROM `vendors` 
                    INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1
                    INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_id =contact.ctt_ref_code AND contact.ctt_active =1
				WHERE vnd_active > 0 AND contact.ctt_pan_no =:panId " . $cond;
		$cnt = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $cnt;
	}

	public function checkDuplicateUserByVendor($ctcid)
	{
		$contctArray = Contact::model()->getContactDetails($ctcid);
		$usersId	 = Users::model()->linkUserid($contctArray['eml_email_address'], $contctArray['phn_phone_no']);
		$vndId		 = Vendors::getVendorIdByUserId($usersId);
		$cntUsrVnd	 = ($vndId) ? 1 : 0;
		return $cntUsrVnd;
	}

	public function getModificationMSG($diff)
	{
		$model	 = $msg	 = '';
		if (count($diff) > 0)
		{
			if ($diff['vnd_contact_id'])
			{
				$msg .= ' Contact ID: ' . $diff['vnd_contact_id'] . ',';
			}
			if ($diff['vnd_cat_type'])
			{
				if ($diff['vnd_cat_type'] == 1)
				{
					$msg .= ' Type: DCO ,';
				}
				else
				{
					$msg .= ' Type: Vendor ,';
				}
			}
			if ($diff['vnd_firm_pan'])
			{
				$msg .= ' Vendor Firm Pan: ' . $diff['vnd_firm_pan'] . ',';
			}
			if ($diff['vnd_firm_ccin'])
			{
				$msg .= ' Vendor Firm CCIN: ' . $diff['vnd_firm_ccin'] . ',';
			}
			if ($diff['firm_type'])
			{
				$msg .= ' Vendor Firm Type: ' . $diff['firm_type'] . ',';
			}
			if ($diff['vnp_home_zone'] || $diff['vnd_home_zone'])
			{
				if ($diff['vnd_home_zone'] != '')
				{
					$msg .= ' Home Zone: ' . $diff['vnd_home_zone'] . ',';
				}
				else
				{
					$msg .= ' Home Zone: ' . $diff['vnp_home_zone'] . ',';
				}
			}
			if ($diff['vnp_sedan_count'] || $diff['vnd_sedan_count'])
			{
				if ($diff['vnd_sedan_count'] != '')
				{
					$msg .= ' Sedan Count: ' . $diff['vnd_sedan_count'] . ',';
				}
				else
				{
					$msg .= ' Sedan Count: ' . $diff['vnp_sedan_count'] . ',';
				}
			}
			if ($diff['vnp_compact_count'] || $diff['vnd_compact_count'])
			{
				if ($diff['vnd_compact_count'] != '')
				{
					$msg .= ' Compact Count: ' . $diff['vnd_compact_count'] . ',';
				}
				else
				{
					$msg .= ' Compact Count: ' . $diff['vnp_compact_count'] . ',';
				}
			}
			if ($diff['vnp_suv_count'] || $diff['vnd_suv_count'])
			{
				if ($diff['vnd_suv_count'] != '')
				{
					$msg .= ' SUV Count: ' . $diff['vnd_suv_count'] . ',';
				}
				else
				{
					$msg .= ' SUV Count: ' . $diff['vnp_suv_count'] . ',';
				}
			}
			if ($diff['vnp_notes'] || $diff['vnd_notes'])
			{
				if ($diff['vnd_notes'] != '')
				{
					$msg .= ' Notes: ' . $diff['vnd_notes'] . ',';
				}
				else
				{
					$msg .= ' Notes: ' . $diff['vnp_notes'] . ',';
				}
			}
			if ($diff['booking_type'])
			{
				$msg .= ' Booking Type(One Way): ' . $diff['booking_type'] . ',';
			}
			if ($diff['vrs_credit_limit'])
			{
				$msg .= ' Credit Limit: ' . $diff['vrs_credit_limit'] . ',';
			}
			if ($diff['vrs_security_amount'])
			{
				$msg .= ' Security Amount: ' . $diff['vrs_security_amount'] . ',';
			}
			if ($diff['vnp_oneway'] || $diff['vnp_round_trip'] || $diff['vnp_round_trip'] || $diff['vnp_package'] || $diff['vnp_daily_rental'] || $diff['vnp_airport'] || $diff['vnp_tempo_traveller'] || $diff['vnp_lastmin_booking'])
			{
				$msg .= ' Services: ';
			}
			if ($diff['vnp_oneway'])
			{
				if ($diff['vnp_oneway'] == 1)
				{
					$msg .= ' One Way ,';
				}
			}
			if ($diff['vnp_round_trip'])
			{
				if ($diff['vnp_round_trip'] == 1)
				{
					$msg .= ' Round Trip ,';
				}
			}
			if ($diff['vnp_round_trip'])
			{
				if ($diff['vnp_round_trip'] == 1)
				{
					$msg .= ' Round Trip ,';
				}
			}
			if ($diff['vnp_package'])
			{
				if ($diff['vnp_package'] == 1)
				{
					$msg .= ' Package ,';
				}
			}
			if ($diff['vnp_daily_rental'])
			{
				if ($diff['vnp_daily_rental'] == 1)
				{
					$msg .= ' Day Rental ,';
				}
			}
			if ($diff['vnp_airport'])
			{
				if ($diff['vnp_airport'] == 1)
				{
					$msg .= ' Airport Transfer ,';
				}
			}
			if ($diff['vnp_tempo_traveller'])
			{
				if ($diff['vnp_tempo_traveller'] == 1)
				{
					$msg .= ' Tempo Traveller ,';
				}
			}
			if ($diff['vnp_lastmin_booking'])
			{
				if ($diff['vnp_lastmin_booking'] == 1)
				{
					$msg .= ' Last Min Booking ,';
				}
			}
			if ($diff['vnd_bank_name'])
			{
				$msg .= ' Bank Name ' . $diff['vnd_bank_name'] . ',';
			}
			if ($diff['vnd_bank_branch'])
			{
				$msg .= ' Branch Name ' . $diff['vnd_bank_branch'] . ',';
			}
			if ($diff['vnd_bank_account_no'])
			{
				$msg .= ' Account No ' . $diff['vnd_bank_account_no'] . ',';
			}
			if ($diff['vnd_bank_ifsc'])
			{
				$msg .= ' IFSC ' . $diff['vnd_bank_ifsc'] . ',';
			}
			if ($diff['vnd_beneficiary_name'])
			{
				$msg .= ' Beneficiary name ' . $diff['vnd_beneficiary_name'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function getCountVehicle($vndId)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')   FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code INNER JOIN vendors v3 ON v2.vnd_ref_code = v3.vnd_id  WHERE v1.vnd_id = '" . $vndId . "'";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();
		if (!empty($vndIds))
		{
			$arrTotal = Vehicles::model()->findBySql("SELECT count(1) total_vehicle,sum(IF(vhc_approved=1,1,0)) total_approved,
				sum(IF(vhc_approved=3,1,0)) total_rejected,
                sum(IF(vhc_approved=2,1,0)) total_pending_approval FROM `vehicles`
                INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                WHERE vvhc_vnd_id in ($vndIds)  AND vhc_active=1");
		}
		return $arrTotal;
	}

	public function getCountDriver($vndId)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')   FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code INNER JOIN vendors v3 ON v2.vnd_ref_code = v3.vnd_id  WHERE v1.vnd_id = '" . $vndId . "'";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();
		if (!empty($vndIds))
		{
			$arrTotal = Drivers::model()->findBySql("SELECT count(DISTINCT d2.drv_id) total_driver,sum(IF(d2.drv_approved=1,1,0)) total_approved,
				sum(IF(d2.drv_approved=3,1,0)) total_rejected,sum(IF(d2.drv_approved=2,1,0)) total_pending_approval 
				FROM  drivers d2 
				INNER JOIN  vendor_driver on vdrv_drv_id = d2.drv_id  WHERE vdrv_vnd_id in ($vndIds) AND d2.drv_active>0");
		}
		return $arrTotal;
	}

	public function getDrillDownInfo($vndId)
	{
		$relVndIds = \Vendors::getRelatedIds($vndId);

//		$date		 = date('Y-m-d h:i:s');
		$date	 = Filter::getDBDateTime();
		$params	 = array("date" => $date);
		$qry	 = "SELECT DISTINCT vnd_name,
                            ctt_user_type,
                            ctt_business_name,
                            ctt_address,
                            ctt_first_name,
                            ctt_last_name,
                            vrs_credit_limit,
                            vnp_notes,
                            phn_phone_no,
                            vrs_security_amount,
                            vrs_security_receive_date,
                            vrs_vnd_overall_rating,
                            eml_email_address,
                            ctt_beneficiary_id,
							IF(ctt_ref_code=ctt_id,1,0) cttRank,
  (SELECT ROUND(((ROUND(AVG(IF(overall_rating IN (5,4), overall_rating, rtg_customer_driver)),1) + ROUND(AVG(IF(overall_rating IN (5,4),overall_rating,rtg_customer_car)),1) + ROUND(AVG(IF(rtg_csr_vendor IS NULL,5,rtg_csr_vendor)),1))/3),1)
   FROM
     (SELECT bcb_vendor_id,
             IF(rtg_customer_overall IS NULL, 5, rtg_customer_overall) AS overall_rating,
             rtg_customer_driver,
             rtg_customer_car,
             rtg_csr_vendor
      FROM ratings
       JOIN booking ON bkg_id = rtg_booking_id
       JOIN booking_cab ON bcb_id = bkg_bcb_id
      WHERE rtg_active=1
        AND bkg_active=1
        AND bkg_status IN (3, 5, 6, 7)
        AND bkg_pickup_date>='2015-10-25 00:00:00'
        AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 90 DAY) AND :date) agtRating
   WHERE agtRating.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_three_month_rating,

  (SELECT ROUND(((ROUND(AVG(IF(overall_rating IN (5,4), overall_rating, rtg_customer_driver)),1) + ROUND(AVG(IF(overall_rating IN (5,4),overall_rating,rtg_customer_car)),1) + ROUND(AVG(IF(rtg_csr_vendor IS NULL,5,rtg_csr_vendor)),1))/3),1)
   FROM
     (SELECT bcb_vendor_id,
             IF(rtg_customer_overall IS NULL, 5, rtg_customer_overall) AS overall_rating,
             rtg_customer_driver,
             rtg_customer_car,
             rtg_csr_vendor
      FROM ratings
       JOIN booking ON bkg_id = rtg_booking_id
       JOIN booking_cab ON bcb_id=bkg_bcb_id
      WHERE rtg_active=1
        AND bkg_active=1
        AND bkg_status IN (3, 5, 6, 7)
        AND bkg_pickup_date>='2015-10-25 00:00:00'
        AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 180 DAY) AND :date) agtRating
   WHERE agtRating.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_six_month_rating,

  (SELECT ROUND(((ROUND(AVG(IF(overall_rating IN (5,4), overall_rating, rtg_customer_driver)),1) + ROUND(AVG(IF(overall_rating IN (5,4),overall_rating,rtg_customer_car)),1) + ROUND(AVG(IF(rtg_csr_vendor IS NULL,5,rtg_csr_vendor)),1))/3),1)
   FROM
     (SELECT bcb_vendor_id,
             IF(rtg_customer_overall IS NULL, 5, rtg_customer_overall) AS overall_rating,
             rtg_customer_driver,
             rtg_customer_car,
             rtg_csr_vendor
      FROM ratings
      JOIN booking ON bkg_id = rtg_booking_id
      JOIN booking_cab ON bcb_id=bkg_bcb_id
      WHERE rtg_active=1 AND bkg_active=1
        AND bkg_status IN (3, 5, 6, 7)
        AND bkg_pickup_date>='2015-10-25 00:00:00'
        AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 365 DAY) AND :date) agtRating
        WHERE agtRating.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_twelve_month_rating,
        cty_name AS vnd_home_city,

  (SELECT DISTINCT GROUP_CONCAT(zon_name)
   FROM zones
   WHERE FIND_IN_SET(zon_id,
			(SELECT DISTINCT GROUP_CONCAT(CONCAT_WS(',' ,vnp_home_zone ,vnp_accepted_zone ))
                        FROM vendors INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
			 WHERE vnd_id IN ({$relVndIds})))) AS vnd_zones, vrs_total_trips,
  (SELECT COUNT(*) AS total_trips
   FROM booking
   RIGHT JOIN booking_cab ON bcb_id = bkg_bcb_id
   WHERE bkg_active=1
     AND bkg_status<8
     AND bkg_pickup_date>='2015-10-25 00:00:00'
     AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 10 DAY) AND :date
     AND booking_cab.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_ten_day_trips,

  (SELECT COUNT(*) AS total_trips
   FROM booking
    JOIN booking_cab ON bcb_id = bkg_bcb_id
   WHERE bkg_active=1
     AND bkg_status<8
     AND bkg_pickup_date>='2015-10-25 00:00:00'
     AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 30 DAY) AND :date
     AND booking_cab.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_one_month_trips,

  (SELECT COUNT(*) AS total_trips
   FROM booking
    JOIN booking_cab ON bcb_id=bkg_bcb_id
   WHERE bkg_active=1
     AND bkg_status<8
     AND bkg_pickup_date>='2015-10-25 00:00:00'
     AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 90 DAY) AND :date
     AND booking_cab.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_three_month_trips,

  (SELECT COUNT(*) AS total_trips
   FROM booking
    JOIN booking_cab ON bcb_id=bkg_bcb_id
   WHERE bkg_active=1
     AND bkg_status<8
     AND bkg_pickup_date>='2015-10-25 00:00:00'
     AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 180 DAY) AND :date
     AND booking_cab.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_six_month_trips,

  (SELECT COUNT(*) AS total_trips
   FROM booking
    JOIN booking_cab ON bcb_id=bkg_bcb_id
   WHERE bkg_active=1
     AND bkg_status<8
     AND bkg_pickup_date>='2015-10-25 00:00:00'
     AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 365 DAY) AND :date
     AND booking_cab.bcb_vendor_id IN ({$relVndIds})) AS vnd_last_twelve_month_trips
FROM vendors
INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id 
INNER JOIN contact_profile ON contact_profile.cr_is_vendor=vendors.vnd_id AND cr_status=1
INNER JOIN contact ON ctt_id=cr_contact_id AND ctt_active = 1  

LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id  AND (eml_active = 1)
LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id  AND (phn_active = 1)
LEFT JOIN cities ON cty_id = ctt_city
WHERE vnd_id IN ({$relVndIds})  ORDER BY cttRank DESC";

		$recordset = DBUtil::queryRow($qry, DBUtil::SDB(), $params);
		return $recordset;
	}

	public function markedBadListByVendorId($vndId)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')   FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code INNER JOIN vendors v3 ON v3.vnd_ref_code = v2.vnd_id  WHERE v1.vnd_id = '" . $vndId . "'";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		$sql = "    SELECT 
					a.`blg_created`,
					a.`blg_desc`,
					a.`blg_remark_type`,
					b.`bkg_booking_id`,
					b.`bkg_pickup_date`,
					d.`cty_name` AS from_city_name,
					e.`cty_name` AS to_city_name
				    FROM booking_log a
					JOIN `vendors` c ON c.vnd_id = a.blg_vendor_assigned_id
					JOIN `booking` b ON a.blg_booking_id = b.bkg_id
					JOIN cities d ON d.cty_id = b.bkg_from_city_id
					JOIN cities e ON e.cty_id = b.bkg_to_city_id
				    WHERE 1 and a.blg_vendor_assigned_id IN ($vndIds)  AND c.vnd_id = c.vnd_ref_code  AND a.blg_mark_vendor > 0";

		$sqlCount = "SELECT 
					COUNT(*)
				    FROM booking_log a
					JOIN `vendors` c ON c.vnd_id = a.blg_vendor_assigned_id
					JOIN `booking` b ON a.blg_booking_id = b.bkg_id
					JOIN cities d ON d.cty_id = b.bkg_from_city_id
					JOIN cities e ON e.cty_id = b.bkg_to_city_id
				    WHERE 1 and a.blg_vendor_assigned_id IN ($vndIds)  AND c.vnd_id = c.vnd_ref_code  AND a.blg_mark_vendor > 0";

		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['bkg_booking_id', 'blg_remark_type', 'blg_desc'],
				'defaultOrder'	 => 'blg_created DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function rateZoneByRoute($vndId)
	{

		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')   FROM vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code INNER JOIN vendors v3 ON v3.vnd_ref_code = v2.vnd_id  WHERE v1.vnd_id = '" . $vndId . "'";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		$qry = "SELECT DISTINCT route.rut_id,route.rut_name as route_name,vnd.vnd_id 
				FROM route 
				INNER JOIN zone_cities zctFROM ON zctFROM.zct_cty_id=route.rut_from_city_id 
				INNER JOIN zone_cities zctTo ON zctTo.zct_cty_id=route.rut_to_city_id
				INNER JOIN vendor_pref vnp ON (FIND_IN_SET(zctFROM.zct_zon_id, vnp.vnp_home_zone) OR  FIND_IN_SET(zctFROM.zct_zon_id, vnp.vnp_accepted_zone))
				AND (FIND_IN_SET(zctTo.zct_zon_id, vnp.vnp_home_zone) OR  FIND_IN_SET(zctTo.zct_zon_id, vnp.vnp_accepted_zone))
				INNER JOIN vendors vnd ON vnd.vnd_id = vnp.vnp_vnd_id
				WHERE vnd.vnd_id in ($vndIds) AND vnd.vnd_id = vnd.vnd_ref_code";

		$recordset = DBUtil::queryAll($qry);
		return $recordset;
	}

	public static function getVendorsByIds($vndIds)
	{
		$sql = "SELECT vnd_id,vnd_name,IF(contact.ctt_business_type > 0,contact.ctt_business_type,vnd_firm_type) vnd_firm_type,ctt_first_name,ctt_last_name,ctt_business_name,ctt_user_type,phn_phone_no,
			IF(ctt_user_type=1,IF(ctt_first_name IS NOT NULL && ctt_last_name IS NOT NULL,CONCAT(ctt_first_name, ' ',ctt_last_name),ctt_first_name),ctt_business_name) AS vnd_owner
                FROM   `vendors`
                       INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1
                       INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id =contact.ctt_ref_code AND contact.ctt_active =1
                       INNER JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id and contact_email.eml_is_primary = 1 AND contact_email.eml_active = 1 
                       INNER JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id  and contact_phone.phn_is_primary = 1 AND contact_phone.phn_active = 1
                WHERE  `vnd_id` IN ($vndIds) AND vendors.vnd_id = vendors.vnd_ref_code";
		return DBUtil::queryAll($sql);
	}

	public static function getVendorIdByUserId($userId)
	{
		$sql				 = "SELECT vnd_id  FROM `vendors` WHERE `vnd_user_id` = :userId";
		$param[':userId']	 = $userId;
		$cdb				 = DBUtil::command($sql);
		$vndid				 = $cdb->queryScalar($param);
		return $vndid;
	}

	public function getName()
	{
		$vendorName = '';
		if ($this->vndContact->ctt_user_type == 1)
		{
			$vendorName = $this->vndContact->ctt_first_name . '' . $this->vndContact->ctt_last_name;
		}
		else if ($this->vndContact->ctt_user_type == 2)
		{
			$vendorName = $this->vndContact->ctt_business_name;
		}
		else
		{
			$vendorName = $this->vndContact->ctt_first_name . '' . $this->vndContact->ctt_last_name;
		}

		return $vendorName;
	}

	public function getUniqueName()
	{
		return '-' . Cities::getName($this->vndContact->ctt_city) . '-' . $this->getName();
	}

	public function listtoapprove($type = '')
	{
		$whereClause = $joinClause	 = "";
		$active		 = ($this->vnd_active == 2) ? 2 : 3;

		$join = ($this->vnd_vehicle_type != '') ? "LEFT JOIN vendor_vehicle ON vendor_vehicle.vvhc_vnd_id = vnd.vnd_id 
                                            INNER JOIN vehicles ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id 
                                            INNER JOIN vehicle_types ON vehicle_types.vht_id = vehicles.vhc_type_id" : "";

		$sql = "SELECT vnd.vnd_id, vnd.vnd_name, vnd.vnd_active, vnd.vnd_delete_reason, vnd.vnd_delete_other, ctt.ctt_business_name, 
					phn.phn_is_verified, phn.phn_phone_no, eml.eml_is_verified, eml.eml_email_address, ctt.ctt_city, vnd.vnd_create_date, cty.cty_name ";

		$sqlCount = "SELECT vnd.vnd_id ";

		$joinClause = " FROM `vendors` vnd 
			INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id=vnd.vnd_id 
			INNER JOIN contact_profile ON contact_profile.cr_is_vendor = vnd.vnd_id AND cr_status=1 
			INNER JOIN contact ctt ON ctt.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
			LEFT JOIN contact_phone phn ON phn.phn_contact_id=ctt.ctt_id AND phn.phn_active=1 
			LEFT JOIN contact_email eml ON eml.eml_contact_id=ctt.ctt_id AND eml.eml_active=1 
			LEFT JOIN cities cty ON cty.cty_id = ctt.ctt_city " . $join;

		//$whereClause = " WHERE vnd.vnd_active=$active AND vnd.vnd_id = vnd.vnd_ref_code ";
		$whereClause = " WHERE vnd.vnd_active IN(3,4) AND vnd.vnd_id = vnd.vnd_ref_code ";

		if ($this->vendorPrefs->vnp_home_zone != '')
		{
#$sql		 .= " AND vnp.vnp_home_zone IN ({$this->vendorPrefs->vnp_home_zone})";
#$sqlCount	 .= " AND (vnp.vnp_home_zone IN ({$this->vendorPrefs->vnp_home_zone}))";

			$whereClause .= " AND vnp.vnp_home_zone IN ({$this->vendorPrefs->vnp_home_zone})";
		}
		if ($this->vnd_city != '')
		{
#$sql		 .= " AND ctt.ctt_city='$this->vnd_city'";
#$sqlCount	 .= " AND ctt.ctt_city='$this->vnd_city'";

			$whereClause .= " AND ctt.ctt_city='$this->vnd_city'";
		}
		if (isset($this->vnd_name) && $this->vnd_name != "")
		{
#$sql		 .= " AND (vnd.vnd_name LIKE '%" . $this->vnd_name . "%')";
#$sqlCount	 .= " AND (vnd.vnd_name LIKE '%" . $this->vnd_name . "%')";

			$whereClause .= " AND (vnd.vnd_name LIKE '%" . $this->vnd_name . "%')";
		}
		if ($this->vnd_vehicle_type != '')
		{
#$sql		 .= " AND vehicles.vhc_type_id  = $this->vnd_vehicle_type";
#$sqlCount	 .= " AND vehicles.vhc_type_id  = $this->vnd_vehicle_type";

			$whereClause .= " AND vehicles.vhc_type_id  = $this->vnd_vehicle_type";
		}
		if (isset($this->vnd_id) && $this->vnd_id != "")
		{
#$sql		 .= " AND (vnd.vnd_id = {$this->vnd_id})";
#$sqlCount	 .= " AND (vnd.vnd_id = {$this->vnd_id})";

			$whereClause .= " AND (vnd.vnd_id = {$this->vnd_id})";
		}
		if (isset($this->vnd_registered_platform) && $this->vnd_registered_platform == 1)
		{
#$sql		 .= " AND (vnd.vnd_id = {$this->vnd_id})";
#$sqlCount	 .= " AND (vnd.vnd_id = {$this->vnd_id})";

			$whereClause .= " AND (vnd.vnd_registered_platform = {$this->vnd_registered_platform})";
		}
		if (isset($this->vnd_create_date) && $this->vnd_create_date != "")
		{
#$sql		 .= " AND (vnd.vnd_create_date LIKE '%" . $this->vnd_create_date . "%')";
#$sqlCount	 .= " AND (vnd.vnd_create_date LIKE '%" . $this->vnd_create_date . "%')";

			$whereClause .= " AND (vnd.vnd_create_date LIKE '%" . $this->vnd_create_date . "%')";
		}
		if ($this->vndContact->contactPhones->phn_phone_no != "")
		{
#$sql		 .= " AND (phn.phn_phone_no LIKE '%{$this->vndContact->contactPhones->phn_phone_no}%')";
#$sqlCount	 .= " AND (phn.phn_phone_no LIKE '%{$this->vndContact->contactPhones->phn_phone_no}%')";

			$whereClause .= " AND (phn.phn_phone_no LIKE '%{$this->vndContact->contactPhones->phn_phone_no}%')";
		}
		if ($this->vndContact->ctt_business_name != "")
		{
#$sql		 .= " AND (ctt.ctt_business_name LIKE '%{$this->vndContact->ctt_business_name}%')";
#$sqlCount	 .= " AND (ctt.ctt_business_name LIKE '%{$this->vndContact->ctt_business_name}%')";

			$whereClause .= " AND (ctt.ctt_business_name LIKE '%{$this->vndContact->ctt_business_name}%')";
		}
		if ($this->vndContact->ctt_id != "")
		{
#$sql		 .= " AND (ctt.ctt_id = {$this->vndContact->ctt_id})";
#$sqlCount	 .= " AND (ctt.ctt_id = {$this->vndContact->ctt_id})";

			$whereClause .= " AND (ctt.ctt_id = {$this->vndContact->ctt_id})";
		}
		if ($this->vndContact->contactEmails->eml_email_address != "")
		{
#$sql		 .= " AND (eml.eml_email_address LIKE '%{$this->vndContact->contactEmails->eml_email_address}%')";
#$sqlCount	 .= " AND (eml.eml_email_address LIKE '%{$this->vndContact->contactEmails->eml_email_address}%')";

			$whereClause .= " AND (eml.eml_email_address LIKE '%{$this->vndContact->contactEmails->eml_email_address}%')";
		}
		if ($this->vnd_is_nmi == 1)
		{
			$nmiZone = InventoryRequest::getNMIZoneId();
#$sql		 .= " AND (vnp.vnp_home_zone  IN ($nmiZone))";
#$sqlCount	 .= " AND (vnp.vnp_home_zone  IN ($nmiZone))";

			$whereClause .= " AND (vnp.vnp_home_zone  IN ($nmiZone)) ";
		}

#$sql		 .= " Group by vnd.vnd_ref_code";
#$sqlCount	 .= " Group by vnd.vnd_ref_code";

		$whereClause .= " GROUP BY vnd.vnd_ref_code";

		$sql		 = $sql . $joinClause . $whereClause;
		//echo $sql;
		//exit;
		$sqlCount	 = $sqlCount . $joinClause . $whereClause;

		$count = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
		if ($type == 'count')
		{
			return $count;
		}
		if ($type == 'query')
		{
			return $sql;
		}
		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 =>
			['attributes'	 =>
				['vnd_name', 'vnd_id', 'ctt_business_name',
					'phn_phone_no', 'eml_email_address', 'ctt_city', 'vnd_create_date',
					'cty_name'],
				'defaultOrder'	 => 'vnd_id DESC'],
			'pagination'	 => ['pageSize' => 20],
		]);
		return $dataprovider;
	}

	public function findByUserId($userId)
	{
		$qry		 = "select count(1) as cntvnduvr from vendors where vnd_uvr_id = $userId and vnd_active > 0";
		$recordset	 = DBUtil::queryRow($qry);
		return $recordset;
	}

	public function getVendorAssignmentReport($type = '')
	{
		$sql = "SELECT 
				vendors.vnd_id
				,vendors.vnd_name
				,SUM(IF((bkg_assign_mode IN (0)), 1, 0)) AS manual_assigned_bookings
				,SUM(IF(( booking.bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY) AND NOW() AND (bkg_assign_mode IN (0)) ),1,0)) as manual_assigned_bookings_30
				,SUM(IF((bkg_assign_mode IN (1)), 1, 0)) AS system_assigned_bookings
				,SUM(IF(( booking.bkg_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 30 DAY),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59') AND (bkg_assign_mode IN (1)) ),1,0)) as system_assigned_bookings_30
				,( SELECT MAX(app_tokens.apt_last_login)   FROM `app_tokens`  WHERE app_tokens.apt_user_type=2 and app_tokens.apt_entity_id=vendors.vnd_id) AS   last_login          
				FROM `booking`
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id   
				INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id AND bcb_active = 1
				JOIN vendors ON vendors.vnd_id =booking_cab.bcb_vendor_id 
				WHERE 1 AND  booking.bkg_active=1 AND booking.bkg_create_date >= '2015-11-01 00:00:00' and booking.bkg_status in (3,5,6,7)
				GROUP BY vnd_id";

		$sqlCount = "SELECT 
						count(*)    
						FROM `booking`
						JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id AND bcb_active = 1
						WHERE 1 AND  booking.bkg_active=1 AND booking.bkg_create_date >= '2015-11-01 00:00:00' and booking.bkg_status in (3,5,6,7)
						GROUP BY booking_cab.bcb_vendor_id";
		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_name', 'system_assigned_bookings', 'manual_assigned_bookings', 'system_assigned_bookings_30', 'manual_assigned_bookings_30', 'last_login'],
					'defaultOrder'	 => 'vnd_id desc'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public function getRegistrationProgress($type = '', $args = [])
	{
		$region	 = ($args['region'] != '') ? $args['region'] : '';
		$zone	 = ($args['zone'] != '') ? $args['zone'] : '';
		$select	 = "SELECT   vendors.vnd_id,contact.ctt_id,
			contact_phone.phn_phone_no 
         , contact_email.eml_email_address
         , vendors.vnd_name
         , vnd_active
         , IF(vendors.vnd_active = 1, 'Yes', 'No') AS approve
         , IFNULL(vendor_stats.vrs_docs_score, 0) AS vrascore
		 , vendor_stats.vrs_docs_r4a
         , IFNULL(vendor_stats.vrs_count_driver, 0) AS countdriver
         , IFNULL(vendor_stats.vrs_count_car, 0) AS countcars
		 , vendor_stats.vrs_last_logged_in
         , zones.zon_name
         , stt_zone
         , (CASE WHEN (stt_zone = '1') THEN 'North' WHEN (stt_zone = '2') THEN 'West' WHEN (stt_zone = '3') THEN 'Central' WHEN (stt_zone = '4') THEN 'South' WHEN (stt_zone = '5') THEN 'East' WHEN (stt_zone = '6') THEN 'North East' END) as region
         , vendor_pref.vnp_home_zone
		 , vendor_pref.vnp_is_orientation
		 , vendor_pref.vnp_orientation_type
         , vendors.vnd_create_date
         , IF(contact.ctt_user_type = '2', 'Yes', 'No') AS company
         , IF((ctt_bank_name <> '' && ctt_bank_branch <> '' && ctt_beneficiary_name <> '' && ctt_bank_ifsc <> ''), 'Yes', 'No') as bank_details
         , vendor_agreement.vag_draft_agreement, vendor_agreement.vag_id,
         vendor_agreement.vag_digital_agreement,
         vag_digital_date,
         vag_digital_flag,contact.ctt_city as 'vnd_city'";
		$join	 = ($this->vnd_vehicle_type != '') ? "LEFT JOIN vendor_vehicle ON vendor_vehicle.vvhc_vnd_id = vendors.vnd_id 
                                            LEFT JOIN vehicles ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id 
                                            LEFT JOIN vehicle_types ON vehicle_types.vht_id = vehicles.vhc_type_id" : "";
		$sql	 = "  FROM `vendors`
				INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id 
				INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id 
				INNER JOIN `contact_profile` ON contact_profile.cr_is_vendor = vendors.vnd_id AND cr_status=1 
				INNER JOIN `contact` ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				LEFT JOIN `contact_email` ON contact_email.eml_contact_id = contact.ctt_id AND eml_is_primary = 1 AND eml_active =1 
				LEFT JOIN `contact_phone` ON contact_phone.phn_contact_id = contact.ctt_id AND phn_is_primary = 1 AND phn_active =1 
				LEFT JOIN `zones` ON (zones.zon_id=vendor_pref.vnp_home_zone) 
				LEFT JOIN `vendor_agreement` ON vendor_agreement.vag_vnd_id = vendors.vnd_id 
				LEFT JOIN cities cty ON cty.cty_id = contact.ctt_city 
				LEFT JOIN `states` ON cty.cty_state_id = states.stt_id 
				" . $join . "
				WHERE  1 AND cr_created = (SELECT MAX(cr_created) FROM contact_profile AS cp WHERE cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1) AND vendors.vnd_active > 0";
		if ($zone != '')
		{
			$sql .= " AND vendor_pref.vnp_home_zone=$zone";
		}
		if ($this->vnd_is_voterid != '')
		{
			$isVoterSql	 = ($this->vnd_is_voterid > 0) ? " AND (contact.ctt_voter_doc_id IS NOT NULL OR contact.ctt_voter_doc_id <>'')" : " AND (contact.ctt_voter_doc_id IS NULL OR contact.ctt_voter_doc_id ='')";
			$sql		 .= $isVoterSql;
		}
		if ($this->vnd_is_pan != '')
		{
			$isPanSql	 = ($this->vnd_is_pan > 0) ? " AND (contact.ctt_pan_doc_id IS NOT NULL OR contact.ctt_pan_doc_id <>'')" : " AND (contact.ctt_pan_doc_id IS NULL OR contact.ctt_pan_doc_id ='')";
			$sql		 .= $isPanSql;
		}
		if ($this->vnd_is_aadhar != '')
		{
			$isAadharSql = ($this->vnd_is_aadhar > 0) ? " AND (contact.ctt_aadhar_doc_id IS NOT NULL OR contact.ctt_aadhar_doc_id <>'')" : " AND (contact.ctt_aadhar_doc_id IS NULL OR contact.ctt_aadhar_doc_id ='')";
			$sql		 .= $isAadharSql;
		}
		if ($this->vnd_is_license != '')
		{
			$isLicenseSql	 = ($this->vnd_is_license > 0) ? " AND (contact.ctt_license_doc_id IS NOT NULL OR contact.ctt_license_doc_id <>'')" : " AND (contact.ctt_license_doc_id IS NULL OR contact.ctt_license_doc_id ='')";
			$sql			 .= $isLicenseSql;
		}
		if ($this->vnd_is_agreement != '')
		{
			$isAgreementSql	 = ($this->vnd_is_agreement > 0) ? " AND (vendor_agreement.vag_soft_path IS NOT NULL OR vendor_agreement.vag_soft_path <>'')" : " AND (vendor_agreement.vag_soft_path IS NULL OR vendor_agreement.vag_soft_path ='')";
			$sql			 .= $isAgreementSql;
		}
		if ($this->vnd_operator != '')
		{
			$isOperatorSql	 = " AND (vendors.vnd_name LIKE '%$this->vnd_operator%' OR vendors.vnd_code = '$this->vnd_operator')";
			$sql			 .= $isOperatorSql;
		}
		if ($this->vnd_is_bank != '')
		{
			$isBankSql	 = ($this->vnd_is_bank > 0) ? " AND (ctt_bank_name <>'' AND ctt_bank_branch <>'' AND ctt_beneficiary_name <>'' AND ctt_bank_ifsc <>'')" : " AND (ctt_bank_name IS NULL AND 	ctt_bank_branch IS NULL AND ctt_beneficiary_name IS NULL AND ctt_bank_ifsc IS NULL)";
			$sql		 .= $isBankSql;
		}
		/* if ($this->vnd_is_approve != '')
		  {
		  $isApproveSql	 = ($this->vnd_is_approve == 1) ? " AND vendors.vnd_active=1" : " AND vendors.vnd_active!=1";
		  $sql			 .= $isApproveSql;
		  } */

		if ($this->vnd_city != '')
		{
			$citysql = ($this->vnd_city > 0) ? ' AND contact.ctt_city = ' . $this->vnd_city : '';
			$sql	 .= $citysql;
		}
		if ($this->vnd_vehicle_type != '')
		{
			$sql .= " AND vehicles.vhc_type_id  = $this->vnd_vehicle_type";
//$sqlCount	 .= " AND vehicles.vhc_type_id  = $this->vnd_vehicle_type";
		}
		if ($this->vnd_phone != "")
		{
			$sql .= " AND (contact_phone.phn_phone_no LIKE '%{$this->vnd_phone}%')";
//$sqlCount	 .= " AND (contact_phone.phn_phone_no LIKE '%{$this->vnd_phone}%')";
		}
		if ($this->vnd_email != "")
		{
			$sql .= " AND (contact_email.eml_email_address LIKE '%{$this->vnd_email}%')";
//$sqlCount	 .= " AND (contact_email.eml_email_address LIKE '%{$this->vnd_email}%')";
		}
		if (isset($this->vnd_create_date1) && $this->vnd_create_date1 != "")
		{
			$sql .= " AND (vendors.vnd_create_date LIKE '" . $this->vnd_create_date1 . "%')";
//$sqlCount	 .= " AND (vendors.vnd_create_date LIKE '%" . $this->vnd_create_date1 . "%')";
		}
		if (isset($this->vnd_active) && $this->vnd_active != "")
		{
			$sql .= " AND (vendors.vnd_active = " . $this->vnd_active . ")";
//$sqlCount	 .= " AND (vnd.vnd_create_date LIKE '%" . $this->vnd_create_date . "%')";
		}
		if ($this->vnd_is_nmi == 1)
		{
			$nmiZone = InventoryRequest::getNMIZoneId();
			$sql	 .= " AND (vendor_pref.vnp_home_zone  IN ($nmiZone))";
//	$sqlCount	 .= " AND (vnp.vnp_home_zone  IN ($nmiZone))";
		}


		if ($this->vnd_id != '')
		{
			$sql .= " AND vendors.vnd_id=$this->vnd_id";
		}
		$groupBy = " GROUP BY vendors.vnd_ref_code";
		$orderBy = " ORDER BY vendor_pref.vnp_is_orientation DESC, vendor_stats.vrs_docs_r4a DESC";
		if ($this->vnd_is_loggedin != '')
		{
			$isLoggedSql = ($this->vnd_is_loggedin > 0) ? " AND (vrs_last_logged_in IS NOT NULL OR vrs_last_logged_in <>'')" : " AND (vrs_last_logged_in IS NULL OR vrs_last_logged_in ='')";
			$sql		 .= $isLoggedSql;
		}
		if ($region != '')
		{
			$sql .= " AND stt_zone=$region";
		}
		$query = $select . $sql . $groupBy;
//echo "<pre>";echo $query; dd($this);die;
		if ($type == 'command')
		{
			if ($this->vnd_id != '')
			{
				return DBUtil::queryRow($query, DBUtil::SDB());
			}
			else
			{
				return DBUtil::queryAll($query . $orderBy, DBUtil::SDB());
			}
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(DISTINCT vendors.vnd_id) " . $sql, DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($query, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_name', 'vnp_home_zone', 'region', 'drivers_added', 'cars_added', 'last_login', 'vag_digital_date', 'vrascore', 'vrs_last_logged_in'],
					'defaultOrder'	 => ' vendor_pref.vnp_is_orientation DESC, vendor_stats.vrs_docs_r4a DESC ,vrascore DESC'],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
	}

	/*
	 * This function is used in vendor Registration Progres data into CSV export file
	 * data array of data to inputed 
	 */

	public function getRegistrationProgressCSVReport($data)
	{
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RegProgress_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Name', 'Vendor Email', 'Vendor Phone', 'Home Zone', 'Region',
				'Registered', 'Last Logged In', 'Digital Agreement Date', 'R4A score(Ready for Approval Score)',
				'Bank Details', 'Drivers Added', 'Cars Added', 'Vendor Status']);

			if (count($data) > 0)
			{
				foreach ($data as $row)
				{

					if ($row['vnd_active'] == '1')
					{
						$vendor_status = 'Approved';
					}
					else if ($row['vnd_active'] == '3')
					{
						$vendor_status = 'Pending';
					}
					else if ($row['vnd_active'] == '2')
					{
						$vendor_status = 'Blocked / InActive / Rejected';
					}
					else if ($row['vnd_active'] == '0')
					{
						$vendor_status = 'Deleted';
					}
					else
					{
						$vendor_status = '-';
					}

					$rowArray	 = array(
//'vnd_id'					 => $row['vnd_id'],
//'ctt_id'					 => $row['ctt_id'],
						'vnd_name'					 => $row['vnd_name'],
						'vnd_email'					 => $row['eml_email_address'],
						'vnd_phone'					 => $row ['phn_phone_no'],
						'vnd_homezone'				 => $row['zon_name'],
						'vnd_region'				 => $row['region'],
						'vnd_registerd_date'		 => $row['vnd_create_date'],
						'vnd_last_logged_in'		 => $row['vrs_last_logged_in'],
						'vnd_digital_agreement_date' => $row['vag_digital_date'],
						'vnd_vrascore'				 => $row['vrascore'] . " / " . ($row['vrs_docs_r4a'] == 1) ? '[ Ready 4 Approval ]' : '',
						//'vnd_draft_agreement'		 => $row['vag_draft_agreement'],
//'vnd_digital_agreement'		 => $row['vag_digital_agreement'],
						'vnd_bank_details'			 => $row['bank_details'],
						'vnd_countdriver'			 => $row['countdriver'],
						'vnd_countcars'				 => $row['countcars'],
						'vnd_status'				 => $vendor_status
					);
					$row1		 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
			}
			fclose($handle);
			if (!$data)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}
	}

	public function getRegionList()
	{
		$source = [
			1	 => 'North',
			2	 => 'West',
			3	 => 'Central',
			4	 => 'South',
			5	 => 'East',
			6	 => 'North East',
			7	 => 'South Kerala'
		];
		return $source;
	}

	/**
	 * 
	 * @return array
	 */
	public static function getCatTypeList()
	{
		$types = [
			1	 => 'DCO',
			2	 => 'Vendor',
			3	 => 'Preferred'
		];
		return $types;
	}

	/**
	 * 
	 * @param integer $vendorType
	 * @return string
	 */
	public static function getTypeByVendorType($vendorType)
	{
		$type = '';
		if ($vendorType > 0)
		{
			$list	 = self::getCatTypeList();
			$type	 = $list[$vendorType];
		}
		return $type;
	}

	public function countVendorDocMissing()
	{
		$returnSet = Yii::app()->cache->get('countVendorDocMissing');
		if ($returnSet === false)
		{
			$sql		 = "SELECT count(1) FROM vendors 
				INNER JOIN vendor_agreement ON vendor_agreement.vag_vnd_id=vendors.vnd_id 
				INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_id =contact.ctt_ref_code AND contact.ctt_active =1
				WHERE 
				(
				(vendor_agreement.vag_digital_flag=0 AND vendor_agreement.vag_soft_flag=0) OR 
				contact.ctt_pan_doc_id IS NULL OR 
				(contact.ctt_voter_doc_id IS NULL AND contact.ctt_license_doc_id IS NULL AND ctt_aadhar_doc_id IS NULL)
				)
				AND vendors.vnd_id = vendors.vnd_ref_code AND vnd_active = 1 LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countVendorDocMissing', $returnSet, 600);
		}
		return $returnSet;
	}

	public function countVendorBankMissing()
	{
		$returnSet = Yii::app()->cache->get('countVendorBankMissing');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(1) FROM   vendors vnd 
                            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status=1 
                            INNER JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id AND ctt.ctt_id =ctt.ctt_ref_code AND ctt.ctt_active =1 
				WHERE  (((ctt.ctt_bank_name = '' OR ctt.ctt_bank_name IS NULL) AND (ctt.ctt_bank_branch = '' OR ctt.ctt_bank_branch IS NULL) AND (
					   ctt.ctt_bank_ifsc =
					   '' OR ctt.ctt_bank_ifsc IS NULL) AND (ctt.ctt_bank_account_no = '' OR ctt.ctt_bank_account_no IS NULL)) OR (ctt.ctt_pan_no
						 IS NULL OR ctt.ctt_pan_no = '')) AND vnd.vnd_id = vnd.vnd_ref_code AND  vnd.vnd_active = 1 LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countVendorBankMissing', $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param boolean $contact
	 * @return array
	 */
	public static function fetchApprovedList($contact = false)
	{
		$contactJoin = "";
		if ($contact == true)
		{
			$contactJoin = " INNER JOIN `contact` ON contact.ctt_id=vendors.vnd_contact_id AND (((contact.ctt_first_name!='' OR contact.ctt_last_name!='') AND contact.ctt_user_type=1) OR (contact.ctt_user_type=2 AND contact.ctt_business_name!=''))";
		}
		$sql = "SELECT vnd_id FROM `vendors` $contactJoin WHERE vendors.vnd_active>0 AND vendors.vnd_cat_type!=3 ORDER BY vendors.vnd_id DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getApprovedList()
	{
		$sql			 = "SELECT vnd_id,vnd_name FROM vendors WHERE vnd_active > 0 AND vnd_active IN (1,2) ORDER BY vnd_name";
		$vendorModels	 = DBUtil::command($sql)->queryAll($sql);
		$arrList		 = [];
		foreach ($vendorModels as $vendorModel)
		{
			$arrList[$vendorModel['vnd_id']] = $vendorModel['vnd_name'];
		}
		return $arrList;
	}

	public function getApprovedListJSON()
	{
		$arrVendor	 = $this->getApprovedList();
		$arrJSON	 = [];
		$arrJSON[]	 = array_merge(array("id" => '0', "text" => "All"), $arrJSON);
		foreach ($arrVendor as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getJSONAllVendorsbyQuery($query, $vnd = '', $showInactive = '0')
	{
		$rows		 = $this->getAllVendorsbyQuery($query, $vnd, $showInactive);
		$arrCities	 = array();
		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['vnd_id'], "text" => $row['vnd_name']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getAllVendorsbyQuery($query = '', $vnd = '', $onlyActive = '0')
	{
		$qry		 = '';
		$limitNum	 = 30;
		$params		 = array();

		if ($vnd != '')
		{
			$params['vnd']	 = $vnd;
			$qry1			 = " AND 1 OR vnd_id=:vnd";
			$limitNum		 = 29;
		}
		if ($query == '')
		{
			$qry .= " AND vnd_id IN (SELECT bcb_vendor_id FROM (SELECT bcb_vendor_id, COUNT(*) as cnt FROM booking_cab
                    WHERE bcb_active = 1 AND bcb_created > DATE_SUB(NOW(), INTERVAL 4 MONTH)
                    GROUP BY bcb_vendor_id ORDER BY cnt DESC LIMIT 0, $limitNum) a)";
		}
		else
		{
			DBUtil::getLikeStatement($query, $bindString, $params1);
			$params	 = array_merge($params, $params1);
			$qry	 .= " AND vnd_name LIKE $bindString";
		}
		if ($onlyActive == '1')
		{
			$qry .= " AND vnd.vnd_active = 1";
		}
		$sql = "SELECT vnd.vnd_id, vnd.vnd_name
                FROM vendors vnd
                WHERE vnd.vnd_name<> '' $qry $qry1 ORDER BY vnd.vnd_name LIMIT 0,30 ";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public function fetchByPriority1($bkgId, $findReturn = true, $where = '', $limit = '')
	{
		$commandArr					 = $this->fetchRatingQuery($bkgId, $findReturn, $where, $limit);
		$params						 = $commandArr['params'];
		$sqlCommand					 = $commandArr['sqlCommand'];
		$countCommandArr			 = $this->fetchRatingQuery($bkgId, $findReturn, $where, $limit, '', false, FALSE, 2);
		$countCommand				 = $countCommandArr['sqlCommand'];
		$params1['vndIsBlocked']	 = $this->vndIsBlocked | 0;
		$params1['vndIsFreezed']	 = $this->vndIsFreezed | 0;
		$params1['vndUnApproved']	 = $this->vndUnApproved | 0;
		$count						 = $countCommand->queryScalar($params);
		$dataprovider				 = new CSqlDataProvider($sqlCommand, [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vnd_name', 'vnd_overall_score', 'tScore', 'cScore', 'mScore', 'totalScore', 'bvr_bid_amount'],
				'defaultOrder'	 => 'bidding DESC, bvr_bid_amount ASC, totalScore DESC, vnd_overall_score DESC, tScore DESC'
			]
		]);

		return $dataprovider;
	}

	public function fetchRatingQuery($bkgId, $findReturn = true, $where = '', $limit = '', $order = '', $onlyExclusive = false, $onlyFreeze = false, $type = 1)
	{
		$includeBlocked		 = ($this->vndIsBlocked == 1) ? true : false;
		$includeFreezed		 = ($this->vndIsFreezed == 1) ? true : false;
		$includeUnapproved	 = ($this->vndUnApproved == 1) ? true : false;
		$vndActive			 = " (vnd_active=1";
		$agtvndActive		 = " (agt.vnd_active = 1";
		if ($includeBlocked)
		{
			$vndActive		 .= " OR vnd_active=2";
			$agtvndActive	 .= " OR agt.vnd_active=2";
		}
		if ($includeUnapproved)
		{
			$vndActive		 .= " OR (vnd_active=3 AND vnd_create_date >= DATE_SUB(NOW(), INTERVAL +36 HOUR))";
			$agtvndActive	 .= " OR (agt.vnd_active=3 AND agt.vnd_create_date >= DATE_SUB(NOW(), INTERVAL +36 HOUR))";
		}
		$vndActive		 .= ") ";
		$agtvndActive	 .= ") ";

		$model				 = Booking::model()->findByPk($bkgId);
		$bcb_vendor_amount	 = $model->bkgBcb->bcb_vendor_amount;
		$bcbVendorDues		 = $model->bkgBcb->getVendorDues();
		$creditScore		 = "0";
		if ($bcbVendorDues > ($bcb_vendor_amount * 0.2))
		{
			$creditScore = "20";
		}
		$advance		 = $model->bkgInvoice->getAdvanceReceived();
		$pickupDate		 = $model->bkg_pickup_date;
		$estimatedTime	 = $model->bkg_trip_duration;
		$fromCity		 = $model->bkg_from_city_id;
		$toCity			 = $model->bkg_to_city_id;
		$bkgType		 = $model->bkg_booking_type;
		$ret			 = '';
		if (!$findReturn)
		{
			$ret = ' AND 1<>1';
		}
		$sqlReturnExclusive	 = "SELECT  vnd_id,vnd_create_date,vnd_active,vnd_cat_type,  GROUP_CONCAT(bk1.bkg_id) as bkid, NULL as vhcids,  20 as TypeScore
			FROM `booking` bkg
			LEFT JOIN zone_cities zct1From ON bkg.bkg_from_city_id=zct1From.zct_cty_id
			LEFT JOIN zone_cities zct1To ON bkg.bkg_to_city_id=zct1To.zct_cty_id
			INNER JOIN(
              SELECT bkg_id, bkg_status, bcb.bcb_cab_id, bcb.bcb_vendor_id, bkg_pickup_date,
               bkg_from_city_id, bkg_to_city_id,
                bkg_trip_duration,
                zct2From.zct_cty_id as zct_from_city, zct2From.zct_zon_id as zct_from_zone,
  	      			zct2To.zct_cty_id as zct_to_city, zct2To.zct_zon_id as zct_to_zone
              FROM booking
              INNER JOIN booking_cab bcb ON booking.bkg_bcb_id=bcb.bcb_id  AND bcb_active=1
              INNER JOIN zone_cities zct2From ON booking.bkg_from_city_id=zct2From.zct_cty_id AND zct2From.zct_active=1
		    		  INNER JOIN zone_cities zct2To ON booking.bkg_to_city_id=zct2To.zct_cty_id AND zct2To.zct_active=1
				  WHERE  1 AND bkg_status IN (3,5)
			  ) bk1 ON ((zct1From.zct_zon_id = bk1.zct_to_zone OR bkg.bkg_from_city_id=bk1.bkg_to_city_id)
	        AND (zct1To.zct_zon_id = bk1.zct_from_zone OR bkg.bkg_to_city_id=bk1.bkg_from_city_id))
		      AND bkg.bkg_pickup_date BETWEEN DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE)
		      AND DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration+600 MINUTE)
		      INNER JOIN vendors ON vendors.vnd_id = bk1.bcb_vendor_id
		      INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id AND (vendor_pref.vnp_is_attached=1)
      WHERE bkg.bkg_id=$bkgId AND bkg.bkg_booking_type=1 $ret 
		  GROUP BY vnd_id
			";
		$sqlExclusive		 = "SELECT DISTINCT vnd_id,vnd_create_date,vnd_active,vnd_cat_type, NULL as bkid, GROUP_CONCAT(DISTINCT vhc_id) as vhcids, 15 as TypeScore
                FROM `vendors`
				JOIN contact ON vnd_contact_id = contact.ctt_id
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
                INNER JOIN vendor_vehicle on vvhc_vnd_id = vendors.vnd_id AND ctt_city='$fromCity'
                INNER JOIN vehicles ON vehicles.vhc_id = vvhc_vhc_id
                AND vhc_active=1 AND vhc_is_freeze=0 AND vendor_pref.vnp_is_attached=1
                WHERE vehicles.vhc_id NOT IN
                (
                   SELECT bcb_cab_id FROM booking_cab bcb
                  INNER JOIN booking brt ON brt.bkg_bcb_id = bcb.bcb_id AND brt.bkg_active = 1 AND bcb.bcb_active = 1 AND bkg_status=5
                  WHERE bcb_cab_id IS NOT NULL AND  bkg_pickup_date BETWEEN DATE_SUB('$pickupDate', INTERVAL $estimatedTime + 240 MINUTE)
                          AND DATE_ADD('$pickupDate', INTERVAL 240 MINUTE)
                )
                GROUP BY vnd_id
			  ";

		$sqlReturnOneWay		 = "SELECT  DISTINCT   vnd_id,vnd_create_date,vnd_active,vnd_cat_type,  GROUP_CONCAT(bk1.bkg_id) as bkid, NULL as vhcids, 5 as TypeScore
			FROM `booking` bkg
			LEFT JOIN zone_cities zct1From ON bkg.bkg_from_city_id=zct1From.zct_cty_id
			LEFT JOIN zone_cities zct1To ON bkg.bkg_to_city_id=zct1To.zct_cty_id
			INNER JOIN(
			SELECT bkg_id, bkg_status, bcb.bcb_cab_id, bcb.bcb_vendor_id, bkg_pickup_date,
               bkg_from_city_id, bkg_to_city_id,
                bkg_trip_duration,
                zct2From.zct_cty_id as zct_from_city, zct2From.zct_zon_id as zct_from_zone,
  	      			zct2To.zct_cty_id as zct_to_city, zct2To.zct_zon_id as zct_to_zone
              FROM booking
              INNER JOIN booking_cab bcb ON booking.bkg_bcb_id=bcb.bcb_id  AND bcb_active=1
              INNER JOIN zone_cities zct2From ON booking.bkg_from_city_id=zct2From.zct_cty_id AND zct2From.zct_active=1
		      INNER JOIN zone_cities zct2To ON booking.bkg_to_city_id=zct2To.zct_cty_id AND zct2To.zct_active=1
				  WHERE  1 AND bkg_status IN (3,5)
			  ) bk1 ON ((zct1From.zct_zon_id = bk1.zct_to_zone OR bkg.bkg_from_city_id=bk1.bkg_to_city_id)
	        AND (zct1To.zct_zon_id = bk1.zct_from_zone OR bkg.bkg_to_city_id=bk1.bkg_from_city_id))
		      AND bkg.bkg_pickup_date BETWEEN DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration MINUTE)
		      AND DATE_ADD(bk1.bkg_pickup_date, INTERVAL bk1.bkg_trip_duration+600 MINUTE)

			INNER JOIN vendors ON vendors.vnd_id = bk1.bcb_vendor_id
			INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id AND (vendor_pref.vnp_oneway=1)
			WHERE bkg.bkg_id=$bkgId AND bkg.bkg_booking_type=1 $ret 
				GROUP BY vnd_id
			";
		$sqlRegisteredMeterDown	 = "SELECT  DISTINCT   vnd_id,vnd_create_date,vnd_active,vnd_cat_type, NULL as bkid, GROUP_CONCAT(DISTINCT vhc_id) as vhcids, 10 as TypeScore FROM `vendors`
			INNER JOIN vendor_vehicle on vvhc_vnd_id = vendors.vnd_id
            INNER JOIN vehicles ON vehicles.vhc_id = vvhc_vhc_id AND vhc_active=1 AND vhc_is_freeze=0
			INNER JOIN cab_availabilities ON vehicles.vhc_id = cab_availabilities.cav_cab_id AND cab_availabilities.cav_from_city=$fromCity AND cab_availabilities.cav_to_cities=$toCity AND cav_status=1
			WHERE vendors.vnd_type IN (0,1,2) AND cav_date_time BETWEEN DATE_SUB('$pickupDate', INTERVAL 1440 MINUTE) AND '$pickupDate' AND $bkgType=1 $ret GROUP BY vnd_id";
		$sqlRegistered			 = "SELECT  DISTINCT   vnd_id,vnd_create_date,vnd_active,vnd_cat_type, NULL as bkid, NULL as vhcids, 3 as TypeScore FROM `vendors` INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id=vendors.vnd_id
			WHERE vendors.vnd_type IN (0,2) AND vendor_pref.vnp_is_attached<>1 AND ((vendor_pref.vnp_oneway=1 AND $bkgType=1) OR ($bkgType=2) ) GROUP BY vnd_id";
		$sqlVendorType			 = "SELECT *, MAX(TypeScore) as tScore, GROUP_CONCAT(bkid) as bkids, GROUP_CONCAT(vhcids) as vhc_ids FROM  (($sqlReturnExclusive) UNION ($sqlExclusive) UNION ($sqlReturnOneWay) UNION ($sqlRegisteredMeterDown)
                                        UNION ($sqlRegistered)) agtVendorType WHERE $vndActive GROUP BY vnd_id";

		$sqlVendorHomeCity		 = "SELECT   vnd_id,vnd_create_date,vnd_active,vnd_cat_type, 5 as cityScore FROM vendors INNER JOIN contact ON contact.ctt_id = vendors.vnd_contact_id WHERE (contact.ctt_city IN ('$toCity','$fromCity')) AND $vndActive";
		$sqlVendorHomeZone		 = "SELECT DISTINCT   vnd_id,vnd_create_date,vnd_active,vnd_cat_type, 4 as cityScore FROM vendors
			INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
			INNER JOIN zone_cities ON (FIND_IN_SET(zct_zon_id, IFNULL(vendor_pref.vnp_home_zone,'')))
			AND NOT FIND_IN_SET(zct_cty_id, IFNULL(vendor_pref.vnp_excluded_cities,''))
			WHERE zct_cty_id IN ('$toCity','$fromCity') AND $vndActive";
		$sqlVendorAcceptedZone	 = "SELECT DISTINCT   vnd_id,vnd_create_date,vnd_active,vnd_cat_type, 1 as cityScore FROM vendors
			INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
			INNER JOIN zone_cities ON (FIND_IN_SET(zct_zon_id, vendor_pref.vnp_accepted_zone))
			AND NOT FIND_IN_SET(zct_cty_id, IFNULL(vendor_pref.vnp_excluded_cities,''))
			WHERE zct_cty_id IN ('$toCity','$fromCity') AND $vndActive";

		$sqlVendorZone = "SELECT *, MAX(cityScore) as cScore FROM (($sqlVendorHomeCity) UNION ($sqlVendorHomeZone) UNION ($sqlVendorAcceptedZone)) agtZone WHERE $vndActive GROUP BY vnd_id";

		$sqlVendor30daysMiles = "SELECT bcb_vendor_id,  SUM(bkg_trip_distance) AS totalMiles
			FROM booking b1
			INNER JOIN booking_cab bcb1 ON b1.bkg_bcb_id = bcb1.bcb_id AND bcb1.bcb_active = 1 AND b1.bkg_status IN (3,5,6,7)
			WHERE bkg_active=1 AND bkg_status IN (3,5,6,7) AND bkg_pickup_date>DATE_SUB(NOW(), INTERVAL 30 DAY)
			GROUP BY bcb_vendor_id";

		if ($type == 1)
		{
			$select = "	agt.`vnd_id`,
						agt.vnd_active,
						bvr.bvr_bid_amount,
						IF(bvr.bvr_accepted=1 AND bvr.bvr_bid_amount > 0 AND bvr.bvr_bid_amount IS NOT NULL ,1,IF(bvr.bvr_accepted=2,-1,0)) AS bidding,
						agt.`vnd_name`,
						agtEmail.`eml_email_address` AS vnd_email,
						agtPhone.`phn_phone_no` AS vnd_phone,
						agtContact.`ctt_business_name` AS vnd_company,
						0 AS vnd_owner,
						agtStats.`vrs_vnd_overall_rating` AS vnd_overall_rating,
						agtStats.`vrs_overall_score` AS vnd_overall_score,
						agtStats.`vrs_last_thirtyday_trips` AS vnd_last_thirtyday_trips,
						0 AS vnd_total_drivers,
						agtStats.`vrs_last_thirtyday_amount` AS vnd_last_thirtyday_amount,
						agtPref.`vnp_home_zone` AS vnd_home_zone,
						agtPref.`vnp_accepted_zone` AS vnd_accepted_zone,
						agtStats.`vrs_credit_limit` AS vnd_credit_limit,
						agtPref.`vnp_is_freeze` AS vnd_is_freeze,
						bkids,
						vhcids,
						tScore,
						cScore,
						IF(
							IFNULL(vrs_outstanding, 0) > 0,
							$creditScore ,
							IF(
								IFNULL(vrs_outstanding, 0) < 1000,
								5,
								0
							)
						) creditscore,
						(
							IFNULL(agtStats.vrs_overall_score, 0) + IFNULL(tScore, 0) + IF(
								IFNULL(vrs_outstanding, 0) > 0,
								$creditScore ,
								IF(
									IFNULL(vrs_outstanding, 0) < 1000,
									5,
									0
								)
							) + IFNULL(cScore, 0)  + IFNULL(vrs_drv_app_last10_trps, 0) + IFNULL(vrs_avg_cab_used, 0) + IF(
								agtPref.vnp_is_attached = 1,
								15,
								IF(agt.vnd_cat_type = 1, 15, 0)
							)
						) AS totalScore,
						(
							(
								IFNULL(agtStats.vrs_overall_score, 0) + IFNULL(tScore, 0) + IFNULL(cScore, 0) + IF(
									IFNULL(vrs_outstanding, 0) > 0,
									$creditScore ,
									IF(
										IFNULL(vrs_outstanding, 0) < 1000,
										5,
										0
									)
								) + IFNULL(vrs_drv_app_last10_trps, 0) + IFNULL(vrs_avg_cab_used, 0) + IF(
									agtPref.vnp_is_attached = 1,
									15,
									IF(agt.vnd_cat_type = 1, 10, 0)
								)
							) * $bcb_vendor_amount / IF(
								bvr_bid_amount = 0,
								$bcb_vendor_amount,
								bvr_bid_amount
							)
						) AS rank,
						IF(
							(
								agt.vnd_active IN(2, 3) OR agtPref.vnp_is_freeze != 0
							),
							1,
							0
						) vnd_forbidden";
		}
		else
		{
			$select = "count(*) as count";
		}


		$sql = "
			SELECT $select
			FROM vendors agt
			JOIN vendor_stats agtStats ON vnd_id = agtStats.vrs_vnd_id
			JOIN contact agtContact ON agtContact.ctt_id =agt.vnd_contact_id 
			JOIN vendor_pref agtPref ON vnd_id = agtPref.vnp_vnd_id
			LEFT JOIN contact_email agtEmail ON agtEmail.eml_contact_id = agtContact.ctt_id AND agtEmail.eml_is_primary = 1
			LEFT JOIN contact_phone agtPhone ON agtPhone.phn_contact_id = agtContact.ctt_id AND agtPhone.phn_is_primary = 1
			LEFT JOIN ($sqlVendorType) agtVT ON agt.vnd_id=agtVT.vnd_id
			LEFT JOIN ($sqlVendorZone) agtVZ ON agt.vnd_id=agtVZ.vnd_id
			LEFT JOIN booking_vendor_request bvr ON agt.vnd_id=bvr.bvr_vendor_id AND bvr.bvr_bcb_id='$model->bkg_bcb_id'
			WHERE  $agtvndActive AND (agtPref.vnp_cod_freeze=0 OR (agtPref.vnp_cod_freeze=1 AND $advance>0))";

		if ($where != '')
		{
			$sql .= " AND $where";
		}

		if ($this->vnd_name != '')
		{
			$sql				 .= " AND agt.vnd_name LIKE :vndName";
			$params[':vndName']	 = "%$this->vnd_name%";
		}
		if ($this->vnd_phone != '')
		{
			$sql				 .= " AND agtPhone.phn_phone_no LIKE :vndPhone";
			$params[':vndPhone'] = "%$this->vnd_phone%";
		}

		if ($onlyExclusive)
		{
			$sql .= " AND agtPref.vnp_is_attached=1";
		}

		if ($onlyFreeze)
		{
			$sql		 .= " AND agtPref.vnp_is_freeze=1";
			$advance	 = $model->bkgInvoice->getAdvanceReceived();
			$bkgAmount	 = ($model->bkg_total_amount == 0) ? 1 : $model->bkg_total_amount;

			$paymentRatio = round(($advance / $bkgAmount) * 100);
			if ($paymentRatio < 30)
			{
				$sql .= " AND 1<>1";
			}
		}
		else
		{
			if ($includeFreezed)
			{
				$sql .= " AND  vnp_is_freeze >= 0";
			}
			else
			{
				$sql .= " AND  vnp_is_freeze=0";
			}
		}


		$sql .= " AND (IFNULL(agtStats.vrs_overall_score,0) + IFNULL(tScore,0) +
			 IFNULL(cScore,0) +IF(IFNULL(vrs_outstanding,0) > 0, $creditScore, IF(IFNULL(vrs_outstanding,0) < 1000,5,0))) > 0 ";

		if ($order != '')
		{
			$sql .= " ORDER BY bidding DESC, rank DESC, agtStats.vrs_overall_score DESC, tScore DESC";
		}

		if ($limit != '')
		{
			$sql .= " LIMIT " . $limit;
		}

		$sqlCommand = Yii::app()->db1->createCommand($sql);
		return ['qry' => $sql, 'sqlCommand' => $sqlCommand, 'params' => $params];
	}

	public function getMissingPaperList()
	{
		$sql = "SELECT   vnd_id, eml.eml_email_address AS vnd_email, concat(contact.ctt_first_name, ' ', contact.ctt_last_name) AS contact_name, contact.ctt_business_name, contact.ctt_user_type total_vehicle, total_vehicle_approved, total_driver, total_driver_approved
                FROM vendors
         JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status=1 
         JOIN contact ON contact.ctt_id = cp.cr_contact_id and contact.ctt_id =contact.ctt_ref_code AND contact.ctt_active =1 AND vendors.vnd_id = vendors.vnd_ref_code
         JOIN contact_email eml ON eml.eml_contact_id = contact.ctt_id AND eml.eml_is_primary = 1 AND eml.eml_active = 1
         LEFT JOIN (SELECT   vvhc_vnd_id, SUM(IF(vhc_active = 1, 1, 0)) AS total_vehicle
                    FROM     vehicles INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                    WHERE vhc_active=1
                    GROUP BY vvhc_vnd_id) a
           ON vendors.vnd_id = a.vvhc_vnd_id
         LEFT JOIN (SELECT   vvhc_vnd_id, SUM(IF(vhc_approved = 1, 1, 0)) AS total_vehicle_approved
                    FROM     vehicles INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                    WHERE vhc_approved=1
                    GROUP BY vvhc_vnd_id) b
           ON vendors.vnd_id = b.vvhc_vnd_id
         LEFT JOIN (SELECT   vdrv_vnd_id, SUM(IF(d2.drv_active = 1, 1, 0)) AS total_driver
                    FROM     drivers d2 INNER JOIN vendor_driver ON vdrv_drv_id = d2.drv_id AND d2.drv_id = d2.drv_ref_code
                    WHERE d2.drv_active=1
                    GROUP BY vdrv_vnd_id) c
           ON vendors.vnd_id = c.vdrv_vnd_id
         LEFT JOIN (SELECT   vdrv_vnd_id, SUM(IF(d2.drv_approved = 1, 1, 0)) AS total_driver_approved
                    FROM     drivers d2 INNER JOIN vendor_driver ON vdrv_drv_id = d2.drv_id AND d2.drv_id = d2.drv_ref_code
                    WHERE d2.drv_approved=1
                    GROUP BY vdrv_vnd_id) d
           ON vendors.vnd_id = d.vdrv_vnd_id
WHERE    vnd_active = 1 AND NULLIF(eml.eml_email_address, ' ') IS NOT NULL
                GROUP BY vendors.vnd_id
HAVING   ((total_vehicle > total_vehicle_approved OR total_driver > total_driver_approved) AND (total_vehicle > 0 OR total_driver
          > 0))";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function missingDriverCarInformation($venActive)
	{
		$sql	 = "SELECT vnd_id,vnd_active,vnd_name,vnd_phone,vnd_email,vnd_owner,total_vehicle,total_vehicle_approved,total_driver,total_driver_approved
                FROM vendors
                LEFT JOIN (
                    SELECT vvhc_vnd_id,SUM(IF(vhc_active=1,1,0)) as total_vehicle
                    FROM vehicles
                    INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                    WHERE vhc_active=1
                    GROUP BY vvhc_vnd_id
                 ) a ON vendors.vnd_id=a.vvhc_vnd_id
                LEFT JOIN (
                    SELECT vvhc_vnd_id,SUM(IF(vhc_approved=1,1,0)) as  total_vehicle_approved
                    FROM vehicles
                    INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                    WHERE vhc_approved=1
                    GROUP BY vvhc_vnd_id
                ) b ON vendors.vnd_id=b.vvhc_vnd_id
                LEFT JOIN (
                    SELECT vdrv_vnd_id,SUM(IF(d2.drv_active=1,1,0)) as  total_driver
                    FROM  drivers d1 INNER JOIN drivers d2 ON d1.drv_id = d2.drv_ref_code
                    INNER JOIN vendor_driver ON vdrv_drv_id = d2.drv_id 
                    WHERE d2.drv_active=1
                    GROUP BY vdrv_vnd_id
                ) c ON vendors.vnd_id=c.vdrv_vnd_id
                LEFT JOIN (
                    SELECT vdrv_vnd_id,SUM(IF(d2.drv_approved=1,1,0)) as  total_driver_approved
                   FROM  drivers d1 INNER JOIN drivers d2 ON d1.drv_id = d2.drv_ref_code
                    INNER JOIN vendor_driver ON vdrv_drv_id = d2.drv_id
                    WHERE d2.drv_approved=1
                    GROUP BY vdrv_vnd_id
                ) d ON vendors.vnd_id=d.vdrv_vnd_id
                WHERE vnd_active=$venActive";
		$sql	 .= " GROUP BY vendors.vnd_id";
		$rows	 = DBUtil::queryAll($sql);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ($row['vnd_active'] == 2)
				{
					$subject = 'Complete your Car and Driver paperwork today';
					if (($row['total_vehicle'] > $row['total_vehicle_approved']) || ($row['total_driver'] > $row['total_driver_approved']))
					{
						$incompleteVehicle	 = ($row['total_vehicle'] - $row['total_vehicle_approved']);
						$incompleteDriver	 = ($row['total_driver'] - $row['total_driver_approved']);
						$body				 = 'Dear ' . $row['vnd_owner'] . ',<br/><br/>';
						$body				 .= 'Your account has ' . $incompleteVehicle . ' cars and ' . $incompleteDriver . ' drivers with incomplete paperwork.';
						$body				 .= '<br/>We are unable to activate your account until you have paperwork of all commercial car and driver added in your account for activation.';
						$body				 .= '<br/><br/>Please add the paperwork and details for the commercial car and driver today.';
						$body				 .= '<br/><br/>Thank you,
                            <br/>Gozocab Team';
						$smsChanges			 = 'Your account has ' . $incompleteVehicle . ' cars and ' . $incompleteDriver . ' drivers with incomplete paperwork. ';
						$smsChanges			 .= ' Please add the paperwork and details today.';
					}
					else
					{
						$body		 = 'Dear ' . $row['vnd_owner'] . ',<br/><br/>
                        Your account has 0 cars and drivers active.';
						$body		 .= '<br/>We are unable to activate your account until you have atleast one commercial car and driver added in your account for activation.';
						$body		 .= '<br/><br/>Please add the paperwork and details for the commercial car and driver today.';
						$body		 .= '<br/><br/>Thank you,
                            <br/>Gozocab Team';
						$smsChanges	 = 'Your account has 0 cars and drivers active. ';
						$smsChanges	 .= ' Please add the paperwork and details today.';
					}
					$userName	 = $row['vnd_owner'];
					$email		 = $row['vnd_email'];
					$phone		 = $row['vnd_phone'];
					$Id			 = $row['vnd_id'];
					/* var @model emailWrapper */
					$emailCom	 = new emailWrapper();
					$emailCom->paperworkDriverCarEmail($subject, $body, $userName, $email, $Id);
					/* var @model smsWrappper */
					$msgCom		 = new smsWrapper();
//$msgCom->sentPaperworkSmsVendor('91', $phone, $Id, $smsChanges);
				}
				else if ($row['vnd_active'] == 1)
				{
					$subject = 'Complete your Car and Driver paperwork today';
					if (($row['total_vehicle'] > $row['total_vehicle_approved']) || ($row['total_driver'] > $row['total_driver_approved']))
					{
						$incompleteVehicle	 = ($row['total_vehicle'] - $row['total_vehicle_approved']);
						$incompleteDriver	 = ($row['total_driver'] - $row['total_driver_approved']);
						$body				 = 'Dear ' . $row['vnd_owner'] . ',<br/><br/>';
						$body				 .= 'Your account has ' . $incompleteVehicle . ' cars and ' . $incompleteDriver . ' drivers with incomplete paperwork.';
						$body				 .= '<br/>We need you to add the relevant paperwork for these cars and drivers.';
						$body				 .= '<br/><br/>Please add the paperwork and details for the commercial car and driver today.';
						$body				 .= '<br/><br/>Always deliver 5 star service and get customers to add review for your service. The higher your rating in our system, the more bookings you will receive from the system.';
						$body				 .= '<br/><br/>Thank you,
                            <br/>Gozocab Team';

						$smsChanges	 = 'Your account has ' . $incompleteVehicle . ' cars and ' . $incompleteDriver . ' drivers with incomplete paperwork.';
						$smsChanges	 .= ' Please add the paperwork and details today.';
					}
					else
					{
						$body	 = 'Dear ' . $row['vnd_owner'] . ',<br/><br/>';
						$body	 .= 'Your account has 0 cars and drivers active.';
						$body	 .= '<br/><br/>Please add the paperwork and details for the commercial car and driver today.';
						$body	 .= '<br/><br/>Always deliver 5 star service and get customers to add review for your service. The higher your rating in our system, the more bookings you will receive from the system.';
						$body	 .= '<br/><br/>Thank you,
                            <br/>Gozocab Team';

						$smsChanges	 = 'Your account has 0 cars and drivers active.';
						$smsChanges	 .= ' Please add the paperwork and details today.';
					}
					$userName	 = $row['vnd_owner'];
					$email		 = $row['vnd_email'];
					$phone		 = $row['vnd_phone'];
					$Id			 = $row['vnd_id'];
					/* var @model emailWrapper */
					$emailCom	 = new emailWrapper();
					$emailCom->paperworkDriverCarEmail($subject, $body, $userName, $email, $Id);
					/* var @model smsWrappper */
					$msgCom		 = new smsWrapper();
//$msgCom->sentPaperworkSmsVendor('91', $phone, $Id, $smsChanges);
				}
			}
		}
	}

	public static function getCollectionList($days = 0, $limit = 0, $vndId = 0)
	{
		$limit = " ";
		if ($days > 0)
		{
			$where = " AND act_created > DATE_SUB(NOW(), INTERVAL $days DAY)";
		}
		if ($limit > 0)
		{
			$limit = " LIMIT 0,1";
		}
		if ($vndId > 0)
		{
// Getting Merged VendorIds
			$vndIds = Vendors::getVndIdsByRefCode($vndId);
			if ($vndIds == null || $vndIds == "")
			{
				throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
			}

			$where .= " AND vnd_id IN ({$vndIds})";
		}
		$sql = "SELECT vnd.vnd_ref_code as vnd_id, vrs.vrs_security_amount, 
				IF(vrs.vrs_credit_limit IS NULL, '1000', vrs.vrs_credit_limit)  AS creditLimit,
				SUM(adt.adt_amount) totTrans 
				FROM `vendors` vnd 
				INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id AND vnd_active IN (1,2) 
				INNER JOIN account_trans_details adt ON vnd.vnd_id = adt.adt_trans_ref_id AND adt.adt_active = 1 
					AND adt.adt_status = 1 AND adt.adt_ledger_id = 14 AND adt.adt_type = 2
				INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id 
					AND act.act_active = 1 AND act.act_status = 1 
				WHERE 1 $where
				GROUP BY vnd.vnd_ref_code  
				ORDER BY vnd.vnd_ref_code DESC 
				$limit";
		Logger::writeToConsole("SQL: " . $sql);
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getDueByPickupDate($vndId, $hr = 24)
	{
		$sql = "SELECT ROUND(IFNULL(SUM(vendor_due),0)) as vendor_due FROM
                (
                    SELECT (booking_cab.bcb_vendor_amount-SUM(biv.bkg_total_amount-biv.bkg_advance_amount+biv.bkg_refund_amount)) as vendor_due,
                    MIN(booking.bkg_pickup_date) as tripStart
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                    INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=booking.bkg_booking_id
                    AND booking.bkg_status IN (3,5) AND booking.bkg_active=1 AND booking_cab.bcb_active=1
                    AND booking_cab.bcb_vendor_id=$vndId
                    GROUP BY booking_cab.bcb_id
                    HAVING tripStart < DATE_ADD(NOW(), INTERVAL $hr HOUR)
                )a";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getOverdueDayByDateRange($vndId)
	{
		$sql = "SELECT IFNULL( DATEDIFF(NOW(), IFNULL(MAX(IF(Balance <= 0, trnsDate, NULL)),    MIN(trnsDate))),   60   ) AS overdue
                FROM   (

                           SELECT trnsDate, amt, @Balance := @Balance + a.amt AS Balance
                                        FROM     (
                                                      SELECT adt.adt_amount amt,DATE_FORMAT(act.act_date, '%Y-%m-%d') trnsDate
                                                      FROM   account_trans_details adt  JOIN account_transactions act ON act.act_id = adt.adt_trans_id
                                                      WHERE  act.act_active=1 AND adt.adt_ledger_id = 14 AND adt.adt_type = 2 AND adt.adt_status=1 AND adt.adt_active=1 AND date(act.act_date) BETWEEN DATE(DATE_SUB(NOW(), INTERVAL 60 DAY))  AND CURDATE() AND adt.adt_trans_ref_id = $vndId
                                                      GROUP BY adt.adt_trans_ref_id, adt.adt_id ORDER BY act.act_date ASC
                                                  ) a,
                                                 (
                                                        SELECT  @Balance  := (SELECT SUM(adt1.adt_amount)  FROM   account_trans_details adt1  JOIN account_transactions act1 ON act1.act_id = adt1.adt_trans_id WHERE act1.act_active=1 AND adt1.adt_active = 1 AND adt1.adt_status = 1 AND adt1.adt_ledger_id = 14 AND adt1.adt_type = 2
                                                        AND adt1.adt_trans_ref_id = $vndId AND date(act1.act_date) < DATE(DATE_SUB(NOW(), INTERVAL 60 DAY)))
                                                  ) AS variableInit
                WHERE 1
               ) a";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getCityCoverageReport($args = null, $type = null)
	{
		$region		 = ($args['region'] != '') ? $args['region'] : '';
		$zone		 = ($args['zone'] != '') ? $args['zone'] : '';
		$city		 = ($args['city'] != '') ? $args['city'] : '';
		$sql		 = "SELECT
					cities.cty_name,
					(
						CASE WHEN(states.stt_zone = '1') THEN 'North' WHEN(states.stt_zone = '2') THEN 'West' WHEN(states.stt_zone = '3') THEN 'Central' WHEN(states.stt_zone = '4') THEN 'South' WHEN(states.stt_zone = '5') THEN 'East' WHEN(states.stt_zone = '6') THEN 'North East'
                 END
				) AS region,
				GROUP_CONCAT(
					DISTINCT zones.zon_name SEPARATOR ', '
				) AS home_zone_name
				FROM `cities`
                LEFT JOIN `zone_cities` ON zone_cities.zct_cty_id=cities.cty_id
                LEFT JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
                LEFT JOIN `states` ON cities.cty_state_id=states.stt_id
				WHERE	cities.cty_active = 1";
		$sqlCount	 = "SELECT
						cities.cty_name
						FROM `cities`
						LEFT JOIN `zone_cities` ON zone_cities.zct_cty_id=cities.cty_id
						LEFT JOIN `zones` ON zones.zon_id=zone_cities.zct_zon_id
						LEFT JOIN `states` ON cities.cty_state_id=states.stt_id
						WHERE	cities.cty_active = 1";
		if ($region <> '')
		{
			$sql		 .= " AND states.stt_zone=$region";
			$sqlCount	 .= " AND states.stt_zone=$region";
		}
		if ($zone <> '')
		{
			$sql		 .= " AND zones.zon_id=$zone";
			$sqlCount	 .= " AND zones.zon_id=$zone";
		}
		if ($city <> '')
		{
			$sql		 .= " AND cities.cty_id=$city";
			$sqlCount	 .= " AND cities.cty_id=$city";
		}
		$sql		 .= " GROUP BY cities.cty_id ";
		$sqlCount	 .= " GROUP BY cities.cty_id ";

		if ($type == 'command')
		{
			$sql .= " ORDER BY cities.cty_name ASC";
			return DBUtil::queryAll($sql);
		}
		else
		{

			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['cty_name', 'opt_homezone', 'opt_servingzone', 'region'],
					'defaultOrder'	 => 'cty_id ASC'],
				'pagination'	 => ['pageSize' => 200],
			]);
			return $dataprovider;
		}
	}

	public function getVendorById($id)
	{
		return $this->model()->findByPk($id)->vnd_name;
	}

	public function getBySettleDate($date1, $date2)
	{
		$sql = "SELECT 	vendors.vnd_id,contact_email.eml_email_address,SUM(vendor_transactions.ven_trans_amount) AS vendor_amount,past.pastDues,current_payable,cur.ven_tds_amount,
		vendor_stats.vrs_credit_limit,vendor_stats.vrs_security_amount,vendor_pref.vnp_settle_date,vendor_pref.vnp_mod_day,vendors.vnd_name
		FROM `vendors`
		INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
		INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
		INNER JOIN contact ON contact.ctt_id=vendors.vnd_contact_id
		LEFT JOIN contact_email ON contact_email.eml_contact_id= contact.ctt_id AND contact_email.eml_is_primary=1
		LEFT JOIN vendor_transactions ON vendors.vnd_id=vendor_transactions.trans_vendor_id  AND vendor_transactions.ven_trans_active = 1
        LEFT JOIN
                (
                    SELECT trans_vendor_id,SUM(ven_trans_amount) as pastDues
                    FROM `vendor_transactions`
                    WHERE date(ven_trans_date)<'$date1'
                    GROUP BY `trans_vendor_id`
                ) past ON past.trans_vendor_id=vendors.vnd_id
                LEFT JOIN
                (
                    SELECT trans_vendor_id, SUM(ven_trans_amount) as current_payable, round(sum(ven_tds_amount),2) as ven_tds_amount
                    FROM `vendor_transactions` WHERE 1=1 AND vendor_transactions.ven_trans_active=1
                    AND date(ven_trans_date) BETWEEN '$date1' AND '$date2'
                    GROUP BY trans_vendor_id
                ) cur ON cur.trans_vendor_id=vendors.vnd_id
                WHERE vendors.vnd_active IN (1,2)
                AND vendor_pref.vnp_invoice_date = DATE_ADD(CURDATE(),INTERVAL 5 DAY)
                GROUP BY vendors.vnd_id";
		return DBUtil::queryAll($sql);
	}

	public function saveForAgreementCopy($vendorId)
	{
		try
		{
			$success = false;
			$host	 = Yii::app()->params['host'];
			$baseURL = Yii::app()->params['fullBaseURL'];
			$model	 = Vendors::model()->findByPk($vendorId);

			$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($dir))
			{
				mkdir($dir);
			}

			$agmtModel = VendorAgreement::model()->findByVndId($vendorId);

			$url		 = $baseURL . '/admpnl/vendor/generateAgreementForVendor?vendorId=' . $vendorId . '&ds=1';
			$url		 = str_replace('./', '', $url);
			$agreement	 = $this->file_get_contents_curl($url);

			$myfile				 = fopen(PUBLIC_PATH . "/attachments/vendors/$vendorId/digitalAgreement_{$agmtModel->vag_digital_ver}.pdf", "w");
			Logger::create("Digital Agreement ->" . $myfile, CLogger::LEVEL_TRACE);
			fwrite($myfile, $agreement);
			fclose($myfile);
			$digitalAgreementUrl = "/attachments/vendors/$vendorId/digitalAgreement_{$agmtModel->vag_digital_ver}.pdf";
			$fileArray			 = [0 => ['PATH' => PUBLIC_PATH . "/attachments/vendors/$vendorId/digitalAgreement_{$agmtModel->vag_digital_ver}.pdf"]];
			$attachments		 = json_encode($fileArray);
			Logger::create($attachments, CLogger::LEVEL_TRACE);
			$url				 = $baseURL . '/admpnl/vendor/generateAgreementForVendor?vendorId=' . $vendorId . '&ds=0';
			$url				 = str_replace('./', '', $url);
			$agreement			 = $this->file_get_contents_curl($url);
			$myfile				 = fopen(PUBLIC_PATH . "/attachments/vendors/$vendorId/draftAgreement_" . $agmtModel->vag_digital_ver . ".pdf", "w");
			Logger::create("Draft Agreement ->" . $myfile, CLogger::LEVEL_TRACE);
			fwrite($myfile, $agreement);
			fclose($myfile);
			$draftAgreement		 = "/attachments/vendors/$vendorId/draftAgreement_{$agmtModel->vag_digital_ver}.pdf";
			$fileArray2			 = [0 => ['PATH' => PUBLIC_PATH . "/attachments/vendors/$vendorId/draftAgreement_" . $agmtModel->vag_digital_ver . ".pdf"]];
			$attachments2		 = json_encode($fileArray2);
			Logger::create($attachments2, CLogger::LEVEL_TRACE);

			if ($digitalAgreementUrl != '' && $draftAgreement != '')
			{
				$agmtModel->vag_digital_agreement	 = $digitalAgreementUrl;
				$agmtModel->vag_draft_agreement		 = $draftAgreement;
				if ($agmtModel->save())
				{
					Logger::create("Agreements has been created", CLogger::LEVEL_INFO);
					$success = true;
				}
				else
				{
					throw new Exception("Agreements creation failed.\n\t\t" . json_encode($agmtModel->getErrors()));
				}
			}
			else
			{
				throw new Exception("Agreements creation failed. Digital or draft agreement not created. \n");
			}
		}
		catch (Exception $e)
		{
			Logger::create("Agreements not sent.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
		}
		return $success;
	}

	public function emailForAgreementCopy($vendorId)
	{
		$success	 = false;
		$model		 = Vendors::model()->findByPk($vendorId);
		/* var @model $model */
		$contactId	 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
		$contactId	 = ($contactId == '') ? $model->vnd_contact_id : $contactId;
		$email		 = ContactEmail::getContactEmailById($contactId);
		if ($email != '')
		{
			$agmtModel = VendorAgreement::model()->findByVndId($vendorId);

			$digitalAgmtLink = "<a href='" . Yii::app()->params['fullBaseURL'] . $agmtModel->vag_digital_agreement . "' target'_blank'>Click for Digital Agreement</a>";
			$draftAgmtLink	 = "<a href='" . Yii::app()->params['fullBaseURL'] . $agmtModel->vag_draft_agreement . "' target'_blank'>Click for Draft Agreement</a>";

			$isAgreement = 1;
// EMAIL 1 :: digitally signed agreement
			$subject1	 = 'Your copy of updated GozoCabs Operator Agreement dated: ' . date("d/m/Y", strtotime(DATE('Y-m-d'))) . '';
			$emailBody1	 = 'Dear ' . $model->vnd_name . ',<br/><br/>
                                You have just accepted the attached version of our operator agreement. Attached copy is for your records.
                                <br/><br/>This agreement was digitally signed and accepted by
                                <br/>Name : ' . $model->vnd_name . '
                                <br/>from IP Address : ' . $agmtModel->vag_digital_ip . '
                                <br/>on device : ' . $agmtModel->vag_digital_device_id . '
                                <br/>at Location :
                                <br/><br/>Draft Agreement Link : ' . $draftAgmtLink . '
                                <br/><br/>For any clarifications, please call us at 033-66283910 or email partners@gozocabs.in <mailto:partners@gozocabs.in>
                                <br/><br/>Thanks,
                                <br/><br/>Gozocabs';

			$emailCom = new emailWrapper();
			$emailCom->vendorInvoiceEmail($subject1, $emailBody1, $email, $ledgerPdf, $invoicePdf, $vendorId, $attachments, EmailLog::EMAIl_VENDOR_AGREEMENT, $isAgreement);

// EMAIL 2 :: printed agreement
			$subject2	 = 'Please sign, date and upload attached GozoCabs Operator Agreement';
			$emailBody2	 = 'Dear ' . $model->vnd_name . ',<br/><br/>
                                Please print, sign and date this attached GozoCabs operator agreement.
                                <br/><br/>Upload scanned copy of the signed agreement using your Gozo Partner Mobile App and post a physical copy to GozoCabs at.
                                <br/><br/>Digital Agreement Link : ' . $digitalAgmtLink . '
                                <br/><br/>Gozo Technologies Private Limited
                                <br/>DN-2, Signet Tower
                                <br/>Salt Lake, Sector-V
                                <br/>Kolkata - 700091
                                <br/>West Bengal
                                <br/><br/>For any clarifications, please call us at 033-66283910 or email partners@gozocabs.in <mailto:partners@gozocabs.in>
                                <br/><br/>Thanks,
                                <br/><br/>Gozocabs';
			$emailCom	 = new emailWrapper();
			$emailCom->vendorInvoiceEmail($subject2, $emailBody2, $email, $ledgerPdf, $invoicePdf, $vendorId, $attachments2, EmailLog::EMAIl_VENDOR_AGREEMENT, $isAgreement);

			$agmtModel->vag_digital_is_email = 1;
			$agmtModel->save();
			$success						 = true;
			Logger::trace("Agreements has been sent (Vendor ID: $vendorId)");
		}
		else
		{
			Logger::info("Agreements not sent. Email ID is missing (Vendor ID: $vendorId)");
		}
		return $success;
	}

	public function file_get_contents_curl($url)
	{
		$ch		 = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$data	 = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public function updateInvoiceDate()
	{
		$sql		 = "UPDATE `vendors`
						LEFT JOIN (
						 SELECT CURDATE() as today,
							 DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) DAY + INTERVAL (DAYOFWEEK(CURDATE())>=(MOD(vendors.vnd_id,5)+2))*7+(MOD(vendors.vnd_id,5)+2) DAY,'%Y-%m-%d') AS d,
							  (MOD(vendors.vnd_id,5)+2) as mod_d,
						   (CASE
							   WHEN (MOD(vendors.vnd_id,5)+2)=2 THEN 'Monday'
							   WHEN (MOD(vendors.vnd_id,5)+2)=3 THEN 'Tuesday'
							   WHEN (MOD(vendors.vnd_id,5)+2)=4 THEN 'Wednesday'
							   WHEN (MOD(vendors.vnd_id,5)+2)=5 THEN 'Thursday'
							   WHEN (MOD(vendors.vnd_id,5)+2)=6 THEN 'Friday'
						   END) as day_week,vendors.vnd_active,
							  SUM(vendor_transactions.ven_trans_amount) AS vendor_amount,vendor_stats.vrs_credit_limit,vendors.vnd_id
							  FROM `vendors`
							  INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
							  LEFT JOIN vendor_transactions ON vendors.vnd_id=vendor_transactions.trans_vendor_id  AND vendor_transactions.ven_trans_active = 1
							  WHERE vendor_transactions.ven_trans_active = 1 AND  vendors.vnd_active IN (1,2)
							  GROUP BY vendors.vnd_id
						  )vnd ON vnd.vnd_id=vendors.vnd_id
						  INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id=vendors.vnd_id
						  SET vendor_pref.vnp_mod_day=vnd.mod_d,vendor_pref.vnp_invoice_date=vnd.d WHERE vendors.vnd_active IN (1,2)";
		$recordset	 = DBUtil::command($sql)->execute();
		echo $recordset . " Vendors settle date updated.";
	}

	public function getAll()
	{
		$sql		 = "SELECT vnd_id,vnd_name FROM `vendors`  WHERE vnd_active > 0  ORDER BY vnd_name";
		$recordall	 = DBUtil::queryAll($sql);
		return $recordall;
	}

	public function getAvgBalanceByVendorId($vndId)
	{
		$sql = "SELECT MIN(runningBalance) as min30Days, ROUND(AVG(runningBalance),2) as avg30Days, min10Days, avg10Days
                FROM
                (
                    SELECT DATE(calendar_table.dt), trans_vendor_id , SUM(vendor_transactions.ven_trans_amount) as totTrans, DATE(ven_trans_date) as trans_date,(
                        SELECT SUM(vendor_transactions.ven_trans_amount) FROM vendor_transactions WHERE  vendor_transactions.trans_vendor_id=$vndId AND ven_trans_active=1
                        AND DATE(vendor_transactions.ven_trans_created)<=calendar_table.dt
                    ) as runningBalance
                    FROM `calendar_table`
                    LEFT JOIN `vendor_transactions` ON calendar_table.dt=DATE(vendor_transactions.ven_trans_created)
                    AND vendor_transactions.ven_trans_active=1 AND vendor_transactions.trans_vendor_id=$vndId
                    WHERE  DATE(calendar_table.dt) BETWEEN DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND CURDATE()
                    GROUP BY calendar_table.dt
                    ORDER BY calendar_table.dt DESC
                ) a,
                (
                    SELECT MIN(runningBalance) as min10Days, ROUND(AVG(runningBalance),2) as avg10Days FROM
                    (
                        SELECT DATE(calendar_table.dt), trans_vendor_id , SUM(vendor_transactions.ven_trans_amount) as totTrans, DATE(ven_trans_date) as trans_date,(
                            SELECT SUM(vendor_transactions.ven_trans_amount) FROM vendor_transactions WHERE  vendor_transactions.trans_vendor_id=$vndId AND ven_trans_active=1
                            AND DATE(vendor_transactions.ven_trans_created)<=calendar_table.dt
                        ) as runningBalance
                        FROM `calendar_table`
                        LEFT JOIN `vendor_transactions` ON calendar_table.dt=DATE(vendor_transactions.ven_trans_created)
                        AND vendor_transactions.ven_trans_active=1 AND vendor_transactions.trans_vendor_id=$vndId
                        WHERE  DATE(calendar_table.dt) BETWEEN DATE_SUB(CURDATE(),INTERVAL 10 DAY) AND CURDATE()
                        GROUP BY calendar_table.dt
                        ORDER BY calendar_table.dt DESC
                    ) b
                 )a2";

		return DBUtil::queryRow($sql);
	}

	public function fetchRegistrationProcessByInterval()
	{
		$sql = "SELECT vendors.vnd_id
				FROM `vendors`
                                INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
				INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active = 1
				INNER JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id AND contact_email.eml_is_primary=1 and contact_email.eml_active=1
				LEFT JOIN 
				(
					SELECT email_log.elg_ref_id,MAX(email_log.elg_status_date) as elg_maxdate
					FROM `email_log`
					WHERE email_log.elg_type=23 AND email_log.elg_created >= CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 YEAR), ' 00:00:00')
					GROUP BY email_log.elg_ref_id
				)elg ON vendors.vnd_id=elg.elg_ref_id
				WHERE vendors.vnd_id = vendors.vnd_ref_code AND vendors.vnd_active!=1
				AND vendors.vnd_name!=''
				AND contact_email.eml_email_address!=''
				AND vendors.vnd_application_aborted=0
				AND (DATE_SUB(NOW(),INTERVAL 48 HOUR) > elg.elg_maxdate OR elg.elg_maxdate IS NULL)";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getAllWoAgreementFile()
	{
		$sql = "SELECT vendors.vnd_id,vendors.vnd_name,  vendor_agreement.vag_soft_path, cnt
                FROM `vendors`
                LEFT JOIN `vendor_agreement` ON vendor_agreement.vag_vnd_id = vendors.vnd_id
                INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id
                INNER JOIN
                (
                    SELECT booking_cab.bcb_vendor_id,COUNT(1) as cnt
                    FROM `booking_cab`
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status IN (6,7)
                    WHERE booking_cab.bcb_active=1
                    GROUP BY booking_cab.bcb_vendor_id
                    HAVING (cnt>=2)
                )bcb ON bcb.bcb_vendor_id=vendors.vnd_id
                WHERE vendors.vnd_active=1
                AND vendor_pref.vnp_is_freeze IN (0,1)
                AND (vendor_agreement.vag_soft_flag=0 OR vendor_agreement.vag_digital_flag=0)
                ORDER BY cnt DESC";
		return DBUtil::queryAll($sql);
	}

	public function autoAdminFreezeNoAgreement($userInfo = null)
	{
		$transaction = DBUtil::beginTransaction();
		$vndArray	 = [];
		try
		{
			$digitalVersion	 = Yii::app()->params['digitalagmtversion'];
			$sql			 = "SELECT DISTINCT
									vendors.vnd_id
					FROM
					`vendors`
					INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
					INNER JOIN `contact` ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active = 1
					INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id AND vendors.vnd_id = vendors.vnd_ref_code
					LEFT JOIN `document` votter ON votter.doc_id = contact.ctt_voter_doc_id AND votter.doc_type=2
					AND votter.doc_status IN(0,1) AND votter.doc_active=1 AND votter.doc_file_front_path IS NOT NULL
					LEFT JOIN `document` aadher ON aadher.doc_id = contact.ctt_aadhar_doc_id AND aadher.doc_type=3
					AND aadher.doc_status IN(0,1) AND aadher.doc_active=1 AND aadher.doc_file_front_path IS NOT NULL
					LEFT JOIN `document` pan ON pan.doc_id = contact.ctt_pan_doc_id AND pan.doc_type=4
					AND pan.doc_status IN(0,1) AND pan.doc_active=1 AND pan.doc_file_front_path IS NOT NULL
					LEFT JOIN `document` licence ON licence.doc_id = contact.ctt_license_doc_id AND licence.doc_type=5
					AND licence.doc_status IN(0,1) AND licence.doc_active=1 AND licence.doc_file_front_path IS NOT NULL
					INNER JOIN vendor_agreement ON vendors.vnd_id = vendor_agreement.vag_vnd_id AND vendor_agreement.vag_hard_flag <> 1 AND vendors.vnd_active = 1 AND vendor_pref.vnp_is_freeze IN(0, 1) 
					AND vendor_agreement.vag_active = 1 
									WHERE 1 and (aadher.doc_id IS  NULL AND votter.doc_id IS  NULL )  OR 	(vendor_agreement.vag_digital_ver < '$digitalVersion' OR vendor_agreement.vag_digital_ver IS NULL) OR pan.doc_id IS NULL
					GROUP BY vendors.vnd_id	ORDER BY vendor_agreement.`vag_digital_date` DESC";

			$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
			$ctr	 = 0;
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$vndArray[$ctr] = $row['vnd_id'];
					$ctr++;
				}
				$sqlFreeze	 = "UPDATE `vendor_pref` SET vendor_pref.vnp_doc_pending_freeze = 1 WHERE vendor_pref.vnp_vnd_id IN (  SELECT vnd_id FROM ($sql)a  )";
				$return		 = DBUtil::command($sqlFreeze)->execute();
				if (count($vndArray) > 0 && $return > 0)
				{
					if (count($vndArray) == $return)
					{
						foreach ($vndArray as $vnd_id)
						{
							$success = Vendors::model()->updateFreeze($vnd_id);
							if ($success)
							{
								/* @var $vndLog VendorsLog */
								$vndLog		 = new VendorsLog();
								$desc		 = "Freezed (No Agreement on file). Don't assign booking unless agreement is signed";
								$event_id	 = VendorsLog::VENDOR_ADMINISTRATIVE_FREEZE;
								$vndLog->createLog($vnd_id, $desc, $userInfo, $event_id, false, false);
								$log		 = $vnd_id . " -> " . $desc;
								Logger::create($log, CLogger::LEVEL_INFO);
							}
						}
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						$errors = "Vendor log and return data not yet matched.\n\t\t";
						throw new Exception($errors);
					}
					$vndArray = [];
				}
			}
			else
			{
				$errors = "There is no such vendor for freeze due to agreement.\n\t\t";
				throw new Exception($errors);
			}
		}
		catch (Exception $e)
		{
			Logger::create("Not Freeze.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public function getListOfLastActive($day = 14)
	{
		$sql = "SELECT   vendors.vnd_id  
				FROM  `vendors`
				INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id
				INNER JOIN `vendor_stats`  ON vendor_stats.vrs_vnd_id = vendors.vnd_id  AND vendor_pref.vnp_is_freeze = 0 AND vendors.vnd_active = 1
				INNER JOIN app_tokens on app_tokens.apt_entity_id=vendors.vnd_id    and  vendor_stats.vrs_last_logged_in=app_tokens.apt_last_login  AND app_tokens.`apt_status` = 0
				WHERE  vendor_stats.vrs_last_logged_in < DATE_SUB(NOW(), INTERVAL 14 DAY) 
				GROUP BY   vendors.vnd_id
				ORDER BY vendors.vnd_id DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getLowRatingList()
	{
		$sql = "SELECT DISTINCT v2.vnd_ref_code as vnd_id
                FROM `vendors` v2
				INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = v2.vnd_id and v2.vnd_id = v2.vnd_ref_code
                INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = v2.vnd_id
                INNER JOIN 
                (
                    SELECT booking_cab.bcb_vendor_id , COUNT(1) as trip
                    FROM `booking_cab` 
                    INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id 
                    AND booking.bkg_active=1 
                    AND booking_cab.bcb_active=1 
                    WHERE booking.bkg_status IN (6,7) 
                    GROUP BY booking_cab.bcb_vendor_id 
                    HAVING trip >3
                ) cab ON cab.bcb_vendor_id=v2.vnd_id
                WHERE  vendor_pref.vnp_is_freeze=0 AND vendor_stats.vrs_vnd_overall_rating <2.5 AND v2.vnd_active = 1 group by v2.vnd_ref_code  LIMIT 0,5";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	/*	 * *
	 * function used for unfreeze vendor whose rating increased
	 */

	public function unFreezeForRating()
	{
		$result = $this->ratingFreezeList();

		foreach ($result as $res)
		{
			$vendorId	 = $res['vnd_id'];
			$status		 = 0;
			$sucess		 = VendorPref::updateLowRatingFreeze($vendorId, $status);
			if ($sucess)
			{
				$success			 = Vendors::model()->updateFreeze($vendorId);
				$chekFrozenStatus	 = VendorPref::checkfrozen($vendorId);
				if ($success && $chekFrozenStatus == 0)
				{
					$event_id	 = VendorsLog::VENDOR_UNFREEZE;
					$desc		 = "Vendor Unfreezed (due to vendor rating increased)";
					VendorsLog::model()->createLog($vendorId, $desc, UserInfo::getInstance(), $event_id, false, false);
				}
			}
		}
	}

	public function ratingFreezeList()
	{
		$sql	 = "SELECT DISTINCT v2.vnd_ref_code as vnd_id
                FROM `vendors` v2
				INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = v2.vnd_id and v2.vnd_id = v2.vnd_ref_code
                INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = v2.vnd_id
                WHERE  vendor_pref.vnp_low_rating_freeze=1 AND vendor_stats.vrs_vnd_overall_rating >2.5 AND v2.vnd_active = 1 group by v2.vnd_ref_code";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public function getHalfLifeList()
	{
		$sql = "SELECT  
				vendors.vnd_id,
				IF(vendor_stats.vrs_effective_credit_limit IS NULL, '0', vendor_stats.vrs_effective_credit_limit) AS effectiveCreditLimit,
				SUM(adt.adt_amount) totTrans
				FROM  account_trans_details adt
				JOIN  account_transactions act  ON act.act_id = adt.adt_trans_id AND adt.adt_type = 2 AND adt.adt_ledger_id = 14 AND act.act_active = 1
				JOIN `vendors` ON vendors.vnd_id = adt.adt_trans_ref_id AND adt.adt_active = 1 AND adt.adt_status = 1  and vendors.vnd_ref_code=vendors.vnd_id
				JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id
				JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id
				WHERE  vendors.vnd_active = 1
				GROUP BY vendors.vnd_ref_code";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	/**
	 * Get vendor stats by zone
	 * @return type
	 */
	public static function getStatsByZone()
	{
		$sql = "SELECT vnp_home_zone , 
				COUNT(lastLoggedIn),
				SUM(IF(lastLoggedIn > DATE_SUB(NOW(),INTERVAL 30 DAY),1,0)) as totalLoggedIn,
				COUNT(1) as totalCount
				FROM `vendors` 
				INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id=vendors.vnd_id AND vendors.vnd_active=1 
				LEFT JOIN 
				(
					SELECT MAX(app_tokens.apt_last_login) as lastLoggedIn, 
					app_tokens.apt_user_id 
					FROM `app_tokens` WHERE app_tokens.apt_user_type=2
					GROUP BY app_tokens.apt_user_id
				 ) as token ON token.apt_user_id=vendors.vnd_id 
				WHERE vendor_pref.vnp_home_zone>0
				GROUP BY vendor_pref.vnp_home_zone";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function updateInventoryStats()
	{
// zone wise update
		$results = self::getStatsByZone();
		$ctr	 = 0;
		foreach ($results as $val)
		{
			$areaType	 = 1;
			$areaId		 = $val['vnp_home_zone'];
			$type		 = 1;
			$totalCount	 = $val['totalCount'];
			$activeCount = $val['totalLoggedIn'];
			InventoryStats::addInventory($areaType, $areaId, $type, null, $totalCount, $activeCount);
			$ctr++;
		}
	}

	public function updateDetails($vnd_id = 0)
	{
		$returnset = new ReturnSet();
		try
		{
// UPDATING vrs_vnd_overall_rating, vrs_overall_score, vrs_vnd_total_trip, vrs_trust_score, vrs_no_of_star
			$vendorRatingUpdate	 = Ratings::model()->getVendorAveragerating($vnd_id);
			$whereQry1			 = $vnd_id > 0 ? " and  vrs_vnd_id=$vnd_id " : '';
			$selectqry1			 = "SELECT vrs_vnd_overall_rating,vrs_vnd_id,
			CASE
			when vrs_vnd_overall_rating=5  then 10
			when vrs_vnd_overall_rating=4  then 8
			when vrs_vnd_overall_rating=3  then 5
			when vrs_vnd_overall_rating=2  then 3
			when vrs_vnd_overall_rating=1  then 0
			when vrs_vnd_overall_rating>1 AND vrs_vnd_overall_rating<2 then ROUND((vrs_vnd_overall_rating-1)*3)
			when vrs_vnd_overall_rating>2 AND vrs_vnd_overall_rating<3 then ROUND(((vrs_vnd_overall_rating-2)*2)+3)
			when vrs_vnd_overall_rating>3 AND vrs_vnd_overall_rating<4 then ROUND(((vrs_vnd_overall_rating-3)*3)+5)
			when vrs_vnd_overall_rating>4 AND vrs_vnd_overall_rating<5 then ROUND(((vrs_vnd_overall_rating-4)*2)+8)
			END as ratezz
			FROM vendor_stats where 1 AND vrs_modified_date > DATE_SUB(NOW(), INTERVAL 5 DAY) $whereQry1";
			$resultqry1			 = DBUtil::query($selectqry1, DBUtil::SDB());
			foreach ($resultqry1 as $val)
			{
				if ($val['ratezz'] != null && $val['ratezz'] != "" && $val['vrs_vnd_overall_rating'] != null && $val['vrs_vnd_overall_rating'] != "")
				{
					try
					{
						$updateqry1 = "UPDATE vendor_stats SET vrs_overall_score ={$val['ratezz']} WHERE vrs_vnd_overall_rating = {$val['vrs_vnd_overall_rating']} and vrs_vnd_id={$val['vrs_vnd_id']} $whereQry1";
						DBUtil::execute($updateqry1);
					}
					catch (Exception $ex)
					{
						Logger::writeToConsole($ex->getMessage());
					}
				}
			}

// UPDATING vrs_security_amount
			$whereSecurityQry	 = $vnd_id > 0 ? " AND atd1.adt_trans_ref_id={$vnd_id} " : '';
			$sqlVndSecurity		 = "SELECT atd1.adt_trans_ref_id 
				FROM account_trans_details atd 
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 
					AND atd.adt_status=1 AND atd.adt_ledger_id IN (34) AND atd.adt_status=1 
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id IN (14) AND atd1.adt_active=1 
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) AND act.act_date >= DATE_SUB(NOW(), INTERVAL 3 DAY) 
					{$whereSecurityQry} 
				GROUP BY atd.adt_trans_ref_id";
			$resVndSecurity		 = DBUtil::query($sqlVndSecurity, DBUtil::SDB());
			foreach ($resVndSecurity as $valVndSecurity)
			{
				$secVndId = $valVndSecurity['atd1.adt_trans_ref_id'];

				if ($secVndId > 0)
				{
					$ledgerSecurityBalance	 = AccountTransactions::getSecurityAmount($secVndId);
					$ledgerSecurityBalance	 = ((is_null($ledgerSecurityBalance) || $ledgerSecurityBalance == '') ? 0 : $ledgerSecurityBalance);
					$updateSecurity			 = "UPDATE vendor_stats SET vrs_security_amount={$ledgerSecurityBalance} WHERE 1 AND vrs_vnd_id={$secVndId}";
					DBUtil::execute($updateSecurity);
				}
			}

// UPDATING vrs_total_trips
			$whereQry2	 = $vnd_id > 0 ? " AND vnd_ref_code=$vnd_id " : '';
			$selectqry2	 = "SELECT IFNULL(vnd_ref_code, bcb_vendor_id) as code,  COUNT(*) AS total FROM booking
							INNER JOIN booking_cab on bcb_id=bkg_bcb_id AND booking_cab.bcb_active=1
							INNER JOIN vendors ON vnd_id=bcb_vendor_id AND vnd_active IN (1,2) $whereQry2 
							WHERE bkg_active=1 AND bkg_status IN (6,7) AND bkg_create_date>='2015-10-01' GROUP BY code";
			$resultqry2	 = DBUtil::query($selectqry2, DBUtil::SDB());
			foreach ($resultqry2 as $val)
			{
				try
				{
					$updateqry2 = "UPDATE vendor_stats SET vrs_total_trips = {$val['total']} WHERE  vendor_stats.vrs_vnd_id={$val['code']}";
					DBUtil::execute($updateqry2);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_last_thirtyday_trips
			$whereQry3	 = $vnd_id > 0 ? " AND vnd_ref_code=$vnd_id " : '';
			$selectqry3	 = "
							SELECT IFNULL(vnd_ref_code, bcb_vendor_id) as code, COUNT(*) AS total FROM booking
							INNER JOIN booking_cab on bcb_id=bkg_bcb_id
							INNER JOIN vendors ON vnd_id=bcb_vendor_id AND vnd_active IN(1,2) $whereQry3
							WHERE bkg_active=1 AND bkg_status IN (6,7) AND bkg_create_date>='2015-10-01' 
							AND bkg_pickup_date>DATE_ADD(NOW(), INTERVAL -30 DAY) 
							GROUP BY code
							";
			$resultqry3	 = DBUtil::query($selectqry3, DBUtil::SDB());
			foreach ($resultqry3 as $val)
			{
				try
				{
					$updateqry3 = "UPDATE vendor_stats SET vrs_last_thirtyday_trips = {$val['total']} WHERE  vendor_stats.vrs_vnd_id={$val['code']}";
					DBUtil::execute($updateqry3);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_last_thirtyday_amount
			$whereQry4	 = $vnd_id > 0 ? " AND vnd_ref_code=$vnd_id " : '';
			$selectqry4	 = "
								SELECT vnd_ref_code, SUM(biv.bkg_total_amount) AS total FROM booking
								INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
								INNER JOIN vendors ON vnd_id=bcb_vendor_id  AND vnd_active IN(1,2) $whereQry4
								INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=booking.bkg_id
								WHERE bkg_active=1 AND bkg_status IN (6,7) AND bkg_create_date>='2015-10-01' 
								AND bkg_pickup_date>DATE_ADD(NOW(), INTERVAL -30 DAY) 
								GROUP BY vnd_ref_code 
							";
			$resultqry4	 = DBUtil::query($selectqry4, DBUtil::SDB());
			foreach ($resultqry4 as $val)
			{
				try
				{
					$updateqry4 = "UPDATE vendor_stats SET vrs_last_thirtyday_amount = {$val['total']} WHERE  vendor_stats.vrs_vnd_id={$val['vnd_ref_code']}";
					DBUtil::execute($updateqry4);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_total_amount
			$whereQry5	 = $vnd_id > 0 ? "AND vnd_ref_code=$vnd_id " : '';
			$selectqry5	 = "
							SELECT vnd_ref_code,SUM(IFNULL(biv.bkg_total_amount,0)) AS total FROM booking
							INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
							INNER JOIN vendors ON vnd_id=bcb_vendor_id AND vnd_active IN(1,2) $whereQry5
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=booking.bkg_id
							WHERE bkg_active=1 AND bkg_status IN (6,7) AND bkg_create_date>='2015-10-01'
							AND (bkg_pickup_date)>='2015-10-25'
							GROUP BY vnd_ref_code
							";
			$resultqry5	 = DBUtil::query($selectqry5, DBUtil::SDB());
			foreach ($resultqry5 as $val)
			{
				try
				{
					$updateqry5 = "UPDATE vendor_stats SET vrs_total_amount = {$val['total']} WHERE  vendor_stats.vrs_vnd_id={$val['vnd_ref_code']}";
					DBUtil::execute($updateqry5);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_last_trip_datetime
			$whereQry6	 = $vnd_id > 0 ? " AND vnd_ref_code=$vnd_id " : '';
			$selectqry6	 = "SELECT vnd_ref_code,  MAX(bkg_pickup_date) AS date_time FROM booking
							INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
							INNER JOIN vendors ON vnd_id=bcb_vendor_id $whereQry6
							WHERE bkg_active=1 AND bkg_status IN (6,7) AND bkg_create_date>='2015-10-01' 
							AND vnd_active IN (1,2) AND bkg_pickup_date<NOW() 
							GROUP BY vnd_ref_code";
			$resultqry6	 = DBUtil::query($selectqry6, DBUtil::SDB());
			foreach ($resultqry6 as $val)
			{
				try
				{
					$updateqry6 = "UPDATE vendor_stats SET vrs_last_trip_datetime = '{$val['date_time']}' WHERE  vendor_stats.vrs_vnd_id={$val['vnd_ref_code']}";
					DBUtil::execute($updateqry6);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_first_trip_datetime
			$whereQry7	 = $vnd_id > 0 ? "AND vnd_ref_code=$vnd_id " : '';
			$selectqry7	 = "SELECT 
                                            vnd_ref_code, 
                                            MIN(bkg_pickup_date) AS date_time,
                                            SUM(IF(bkg_booking_type=1,1,0)) AS OW_Count,
                                            SUM(IF(bkg_booking_type IN (2,3),1,0)) AS RT_Count,
                                            SUM(IF(bkg_booking_type=4,1,0)) AS AT_Count,
                                            SUM(IF(bkg_booking_type=5,1,0)) AS PT_Count,
                                            SUM(IF(bkg_booking_type=6,1,0)) AS FL_Count,
                                            SUM(IF(bkg_booking_type=7,1,0)) AS SH_Count,
                                            SUM(IF(bkg_booking_type=8,1,0)) AS CT_Count,
                                            SUM(IF(bkg_booking_type=9,1,0)) AS DR_4HR_Count,
                                            SUM(IF(bkg_booking_type=10,1,0)) AS DR_8HR_Count,
                                            SUM(IF(bkg_booking_type=11,1,0)) AS DR_12HR_Count,
                                            SUM(IF(bkg_booking_type=12,1,0)) AS AP_Count
                                            FROM booking
                                            INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
                                            INNER JOIN vendors ON vnd_id=bcb_vendor_id AND vnd_active IN(1,2) $whereQry7
                                            WHERE bkg_active=1 AND bkg_status IN (6,7) 
                                            AND bkg_create_date>='2015-10-01'
                                            GROUP BY vnd_ref_code";
			$resultqry7	 = DBUtil::query($selectqry7, DBUtil::SDB());
			foreach ($resultqry7 as $val)
			{
				try
				{
					$updateqry7 = "UPDATE vendor_stats SET vrs_first_trip_datetime = '{$val['date_time']}'
                                                    ,vendor_stats.vrs_OW_Count ='{$val['OW_Count']}'
                                                    ,vendor_stats.vrs_RT_Count ='{$val['RT_Count']}'
                                                    ,vendor_stats.vrs_AT_Count ='{$val['AT_Count']}'
                                                    ,vendor_stats.vrs_PT_Count ='{$val['PT_Count']}'
                                                    ,vendor_stats.vrs_FL_Count ='{$val['FL_Count']}'
                                                    ,vendor_stats.vrs_SH_Count ='{$val['SH_Count']}'
                                                    ,vendor_stats.vrs_CT_Count ='{$val['CT_Count']}'
                                                    ,vendor_stats.vrs_DR_4HR_Count ='{$val['DR_4HR_Count']}'
                                                    ,vendor_stats.vrs_DR_8HR_Count ='{$val['DR_8HR_Count']}'
                                                    ,vendor_stats.vrs_DR_12HR_Count ='{$val['DR_12HR_Count']}'
                                                    ,vendor_stats.vrs_AP_Count ='{$val['AP_Count']}'
                                                    WHERE  vendor_stats.vrs_vnd_id={$val['vnd_ref_code']}";
					DBUtil::execute($updateqry7);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			VendorStats::model()->insertEmptyStats(0, $vnd_id);

// UPDATING vrs_count_car, vrs_count_driver
			$whereQry81 = $vnd_id > 0 ? "AND vnd_ref_code=$vnd_id " : '';
//$whereQry82	 = $vnd_id > 0 ? " and  vrs_vnd_id=$vnd_id " : '';

			$selectqry8	 = "SELECT
							   IFNULL(vnd_ref_code, vendors.vnd_id) as vnd_code,
								COUNT(
									DISTINCT vendor_vehicle.vvhc_id
								) AS coutCars,
							   COUNT(DISTINCT IFNULL(drivers.drv_ref_code, drv_id)) AS coutDrivers
							FROM `vendors`
							LEFT JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vnd_id = vendors.vnd_id AND vvhc_active=1
							LEFT JOIN `vendor_driver` ON vendor_driver.vdrv_vnd_id = vendors.vnd_id AND vdrv_active=1
							LEFT JOIN `drivers` ON drivers.drv_id = vendor_driver.vdrv_drv_id AND drivers.drv_active > 0
							LEFT JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active > 0
							WHERE
								vnd_active IN(1,2)  $whereQry81 
								AND 
								(
									vendor_vehicle.vvhc_active = 1 OR vendor_driver.vdrv_active = 1
								) 
							GROUP BY
							  vnd_ref_code";
			$resultqry8	 = DBUtil::query($selectqry8, DBUtil::SDB());
			foreach ($resultqry8 as $val)
			{
				try
				{
					$updateqry8 = "UPDATE `vendor_stats` SET vendor_stats.vrs_count_car ={$val['coutCars']}  , vendor_stats.vrs_count_driver ={$val['coutDrivers']}  WHERE 1 AND vendor_stats.vrs_vnd_id  ={$val['vnd_code']}";
					DBUtil::execute($updateqry8);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

			VendorStats::model()->insertEmptyStats(1, $vnd_id);

// UPDATING vrs_last_logged_in
			$whereQry91	 = $vnd_id > 0 ? "AND vnd_ref_code=$vnd_id " : '';
//$whereQry92	 = $vnd_id > 0 ? " and  vrs_vnd_id=$vnd_id " : '';
			$selectqry9	 = "SELECT	IFNULL(vnd_ref_code, app_tokens.apt_entity_id) as vnd_id,MAX(app_tokens.apt_last_login) AS last_login
							FROM `app_tokens` INNER JOIN vendors ON vnd_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type = 2 $whereQry91
							WHERE vnd_active IN(1,2) AND app_tokens.apt_entity_id IS NOT NULL AND app_tokens.apt_status=1 GROUP BY vnd_id";
			$resultqry9	 = DBUtil::query($selectqry9, DBUtil::SDB());
			foreach ($resultqry9 as $val)
			{
				try
				{
					$updateqry9 = "UPDATE `vendor_stats` SET vendor_stats.vrs_last_logged_in='{$val['last_login']}'  where 1 AND vendor_stats.vrs_vnd_id={$val['vnd_id']}";
					DBUtil::execute($updateqry9);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_approve_car_count, vrs_count_car, vrs_pending_cars, vrs_rejected_cars
			$whereQry101 = $vnd_id > 0 ? "AND vnd_ref_code=$vnd_id " : '';
//$whereQry102 = $vnd_id > 0 ? " and  vrs_vnd_id=$vnd_id " : '';
			$selectqry10 = "SELECT
							IFNULL(vnd_ref_code, vendors.vnd_id) as code,
							vendors.vnd_name,
							SUM(
								IF(
									(
										vehicles.vhc_approved = 1 AND vehicles.vhc_active = 1
									),
									1,
									0
								)
							) AS coutApproveCars,
							SUM(IF(vehicles.vhc_approved = '2', '1', '0')) AS vehicles_pending_approval,
							SUM(IF(vehicles.vhc_approved = '3', '1', '0')) AS vehicles_rejected,
							vendor_stats.vrs_approve_car_count,
							vendor_stats.vrs_count_car,
							COUNT(DISTINCT vendor_vehicle.vvhc_id) AS coutCars
						FROM `vendors`
						INNER JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vnd_id = vendors.vnd_id AND vendor_vehicle.vvhc_active=1
						INNER JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id and vhc_active>0
						INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id
						WHERE vnd_active IN(1,2) AND vehicles.vhc_active > 0 $whereQry101 GROUP BY vnd_ref_code HAVING (coutCars > 0)";
			$resultqry10 = DBUtil::query($selectqry10, DBUtil::SDB());
			foreach ($resultqry10 as $val)
			{
				try
				{
					$updateqry10 = "UPDATE `vendor_stats` SET vendor_stats.vrs_approve_car_count = {$val['coutApproveCars']},vendor_stats.vrs_count_car={$val['coutCars']},vendor_stats.vrs_pending_cars = {$val['vehicles_pending_approval']},vendor_stats.vrs_rejected_cars = {$val['vehicles_rejected']} WHERE 1 AND vendor_stats.vrs_vnd_id = {$val['code']}";
					DBUtil::execute($updateqry10);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}

// UPDATING vrs_approve_driver_count, vrs_count_driver, vrs_pending_drivers, vrs_rejected_drivers
			$whereQry111 = $vnd_id > 0 ? "AND vnd_ref_code=$vnd_id " : '';
//$whereQry112 = $vnd_id > 0 ? " and  vrs_vnd_id=$vnd_id " : '';
			$selectqry11 = "SELECT
							IFNULL(vnd_ref_code, vendors.vnd_id) as code,
							vendors.vnd_name,
							COUNT(
								DISTINCT vendor_driver.vdrv_drv_id
							) AS coutDrivers,
							SUM(
								IF(
									(
										drv_approved = 1 AND drv_active = 1
									),
									1,
									0
								)
							) AS coutApproveDrivers,
							SUM(IF(drv_approved = '2', '1', '0')) AS drivers_pending_approval, 
							SUM(IF(drv_approved = '3', '1', '0')) AS drivers_rejected,
							vendor_stats.vrs_approve_driver_count,
							vendor_stats.vrs_count_driver
						FROM `vendors`
						INNER JOIN `vendor_driver` ON vendor_driver.vdrv_vnd_id = vendors.vnd_id AND vendor_driver.vdrv_active=1
						INNER JOIN `drivers` ON drivers.drv_id = vendor_driver.vdrv_drv_id AND drv_active>0
						INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id=vendors.vnd_id
						WHERE vnd_active IN(1,2) AND drivers.drv_active > 0 $whereQry111
						GROUP BY vnd_ref_code
						HAVING (coutDrivers>0)";
			$resultqry11 = DBUtil::query($selectqry11, DBUtil::SDB());
			foreach ($resultqry11 as $val)
			{
				try
				{
					$updateqry11 = "UPDATE `vendor_stats`
					SET  vendor_stats.vrs_approve_driver_count ={$val['coutApproveDrivers']},vendor_stats.vrs_count_driver={$val['coutDrivers']},vendor_stats.vrs_pending_drivers={$val['drivers_pending_approval']},vendor_stats.vrs_rejected_drivers={$val['drivers_rejected']} WHERE 1 AND vendor_stats.vrs_vnd_id ={$val['code']}";
					DBUtil::execute($updateqry11);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
				}
			}
			$returnset->setStatus(true);
			$returnset->setMessage("Vendor Statistical Data Update Successfully");
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$returnset->setStatus(false);
			$returnset->setMessage("Unable To Update Vendor Statistical Data");
		}
		return $returnset;
	}

	public function getAmountPayable()
	{
		$source = [
			1	 => 'Amount Payable',
			2	 => 'Amount Receivable'
		];
		return $source;
	}

	public function getRelationManager()
	{
		$sql	 = "SELECT CONCAT(admins.adm_fname,' ',admins.adm_lname) as relation_manager,admins.adm_id
                FROM  `vendors`
                INNER JOIN `admins` ON admins.adm_id=vendors.vnd_rm
                GROUP BY relation_manager ORDER BY relation_manager";
		$rows	 = DBUtil::queryAll($sql);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$relationData[] = array("id" => $row['adm_id'], "text" => $row['relation_manager']);
			}
			return CJSON::encode($relationData);
		}
		else
		{
			return CJSON::encode('');
		}
	}

	public function getActionButton($data, $booking_id, $booking2_id = 0)
	{
		$btn = '<div class="btn-group1">';
		if ($data["vhc_ids"] != "")
		{
			$btnView1	 = "<i class='fa fa-bookmark'></i>";
			$href		 = Yii::app()->createUrl("/admin/booking/listbyvhc", array("ids" => $data["vhc_ids"], "agtid" => $data["vnd_id"], "bkid" => $booking_id));
			$btn		 .= CHtml::link($btnView1, $href, [
						'class'		 => "btn btn-warning btn-sm mb5 mr5",
						"onclick"	 => "return showReturnDetails(this,'2');",
						"title"		 => "Exclusive/MeterDown",
						"vendorId"	 => $data["vnd_id"]
			]);
		}
		if ($data["bkids"] != "")
		{
			$btnView1	 = "<i class='fa fa-exchange'></i>";
			$href		 = Yii::app()->createUrl("/admin/booking/listbyids", array("ids" => $data["bkids"], "bkid" => $booking_id));
			$btn		 .= CHtml::link($btnView1, $href, [
						'class'		 => "btn btn-success btn-sm mb5 mr5",
						"onclick"	 => "return showReturnDetails(this,'1');",
						"title"		 => "Return Trip Available",
						"vendorId"	 => $data["vnd_id"]
			]);
		}
		$forbidden	 = $data['vnd_forbidden'];
		$btnView1	 = "<i class='fa fa-check'></i> Assign";
		if (Yii::app()->controller->module->id == 'rcsr')
		{
			$href = Yii::app()->createUrl("rcsr/booking/assignvendor", array('agtid' => $data["vnd_id"], 'bkid' => $booking_id, 'bid_amount' => $data["bvr_bid_amount"], "forbiddenVendor" => $forbidden));
		}
		else
		{
			$href = Yii::app()->createUrl("admin/booking/assignvendor", array('agtid' => $data["vnd_id"], 'bkid' => $booking_id, 'bid_amount' => $data["bvr_bid_amount"], "forbiddenVendor" => $forbidden));
		}
		$btn .= CHtml::link($btnView1, $href, [
					'class'		 => "btn btn-info btn-sm mb5 mr5 uberAssignVendor",
					"onclick"	 => "return vendorAssigned(this,$forbidden);",
					"title"		 => "Return Trip Available",
					"vendorId"	 => $data["vnd_id"],
		]);

		$btn .= "</div>";

		return $btn;
	}

	public function isApproved($vnd_id, $msg = '0')
	{
		$approve = 0;
		if (isset($vnd_id) && $vnd_id > 0)
		{
			$statusArr = Vendors::model()->getAllStatusByVnd($vnd_id);
//$statusArr['is_agmt'] = 1;//static value should be deleted.
			switch ($msg)
			{
				case 0:
					$approve = ($statusArr['is_agmt'] == 1 && $statusArr['is_doc'] == 1 && $statusArr['is_car'] == 1 && $statusArr['is_driver'] == 1) ? 1 : 0;
				case 1:
//$approve = '';
					if ($statusArr['is_agmt'] == 1 && $statusArr['vnd_row']['cout_doc_rejected'] > 0)
					{
						$approve = 'Fixed and re-upload the document.';
					}
					break;
			}
		}


		return $approve;
	}

	/**
	 * 
	 * @param integer $vnd_id
	 * @return integer
	 */
	public function isApprovedCnt($vnd_id)
	{
		$approve = 0;
		if (isset($vnd_id) && $vnd_id > 0)
		{
			$statusArr	 = Vendors::model()->getAllStatusByVnd($vnd_id);
			$approve	 = ($statusArr['is_agmt'] == 1 && $statusArr['is_doc'] == 1 && $statusArr['is_car'] == 1 && $statusArr['is_driver'] == 1) ? 1 : 0;
		}
		return $approve;
	}

	/**
	 * 
	 * @param integer $vnd_id
	 * @return string
	 */
	public function isApprovedMessage($vnd_id)
	{
		$approve = '';
		if (isset($vnd_id) && $vnd_id > 0)
		{
			$statusArr = Vendors::model()->getAllStatusByVnd($vnd_id);
			if ($statusArr['is_agmt'] == 1 && $statusArr['vnd_row']['cout_doc_rejected'] > 0)
			{
				$approve = 'Fixed and re-upload the document.';
			}
		}
		return $approve;
	}

	public static function getDCOStatusDetails($vndId)
	{
		$status					 = array();
		$status['is_agreement']	 = VendorStats::model()->statusCheckAgreement($vndId);
		$status['is_document']	 = VendorStats::model()->statusCheckDocument($vndId);
		$status['is_car']		 = VendorStats::vehicleStatus($vndId);
		$status['is_driver']	 = VendorStats::driverStatus($vndId);
		$arr					 = Vendors::checkAlertMsg($vndId);
		$status['flag']			 = $arr['flag'];
		$status['message']		 = $arr['message'];
		return $status;
	}

	public static function checkAlertMsg($vndId)
	{
		$vendorModel			 = Vendors::model()->findByPk($vndId);
		$prefModel				 = $vendorModel->vendorPrefs;
		$requiredSDAmount		 = $prefModel->vnp_min_sd_req_amt;
		$statModel				 = $vendorModel->vendorStats;
		$currentSecurityAmount	 = $statModel->vrs_security_amount;
		if ($currentSecurityAmount < $requiredSDAmount)
		{
			$securityFlag	 = 1;
			$securityAmount	 = $requiredSDAmount - $currentSecurityAmount;
			$securityMsg	 = "Please pay your security amount ₹" . $securityAmount;
		}

		$outstandingBalence	 = $statModel->vrs_outstanding;
		$withdraw			 = $statModel->vrs_withdrawable_balance;
		if ($outstandingBalence > 0 && $withdraw < 1)
		{
			$outstandingFlag = 1;
			$outstandingMsg	 = "Please pay your outstanding balance ₹" . $outstandingBalence;
		}
		if ($securityFlag == 1 || $outstandingFlag == 1)
		{
			$flag = 1;
			if ($securityFlag == 1 && $outstandingFlag == 1)
			{
				$message = "Please pay your security amount ₹$securityAmount" . " and outstanding balence ₹" . $outstandingBalence . " to increase the chance of winning bid.";
			}
			else
			{
				$message = $securityMsg . $outstandingMsg . ' to increase the chance of winning bid.';
			}
		}
		$arr = array("flag" => $flag, "message" => $message);
		return $arr;
	}

	public function getAllStatusByVnd($vnd_id)
	{

		$is_agreement	 = VendorStats::model()->statusCheckAgreement($vnd_id);
		//$is_agreement	 = 0;
		$is_document	 = VendorStats::model()->statusCheckDocument($vnd_id);

#$is_car				 = VendorStats::model()->statusCheckVehicle($vnd_id);
		$is_car				 = VendorStats::vehicleStatus($vnd_id);
#$is_driver			 = VendorStats::model()->statusCheckDriver($vnd_id);
		$is_driver			 = VendorStats::driverStatus($vnd_id);
		$vnd_row			 = VendorStats::model()->getStatusByVndId($vnd_id);
		$vndSocialLinking	 = Vendors::model()->checkSocialLinking($vnd_id);
		$vndServiceType		 = VendorPref::model()->checkStatusServiceType($vnd_id);
//$vndCotactType			 = Vendors::model()->checkContactType($vnd_id);
		$vendorModel		 = Vendors::model()->findByPk($vnd_id);
		$contactId			 = ContactProfile::getByVendorId($vnd_id);
		//$contact_id			 = $vendorModel->attributes['vnd_contact_id'];
		$contact_val		 = Contact::model()->findByPk($contactId);
		$user_type			 = $contact_val->attributes['ctt_user_type'];
//$is_agreement				 =1;// static data should be changed
		$is_memoLicense		 = 0;
		if ($user_type == 1)
		{
			$is_memoLicense	 = 1;
			$is_bussiness	 = 0;
		}
		else
		{
// check memo licence
			$is_bussiness	 = 1;
			$licence_id		 = $contact_val->attributes['ctt_license_doc_id'];
			if ($licence_id != "")
			{
//check approval
				$licenceStatus = Document::model()->checkDocumentStatus($licence_id);
				if ($licenceStatus != 2)
				{
					$is_memoLicense = 1;
				}
			}
			$memo_id = $contact_val->attributes['ctt_memo_doc_id'];
			if ($memo_id != "")
			{
//check approval
				$memoStatus = Document::model()->checkDocumentStatus($memo_id);
				if ($memoStatus != 2)
				{
					$is_memoLicense = 1;
				}
			}
		}
		$isSocialLinkingMandatory = false;
		//$is_memoLicense = 1;

		return ['is_agmt'			 => (int) $is_agreement
			, 'is_doc'			 => (int) $is_document
			, 'is_car'			 => (int) $is_car
			, 'is_driver'			 => (int) $is_driver
			, 'is_bussiness'		 => (int) $is_bussiness
			, 'vnd_row'			 => $vnd_row
			, 'vnd_social_link'	 => $vndSocialLinking
			, 'linking_required'	 => $isSocialLinkingMandatory
			, 'is_memo_licence'	 => $is_memoLicense
			, 'isServiceType'		 => $vndServiceType
		];
	}

	public function checkSocialLinking($vnd_id)
	{
		$result = false;
		if ($vnd_id > 0)
		{
			$sql	 = "SELECT v2.vnd_user_id from vendors v1 INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code where v1.vnd_id = $vnd_id";
			$userid	 = DBUtil::command($sql)->queryScalar();
			if ($userid > 0)
			{
				$result = Users::model()->checkSocialLinking($userid);
			}
		}
		return $result;
	}

	public function getReceivablePendingHtml()
	{
		$data = $this->accountReceivables();

		$totTransReceivable	 = $data['totTransReceivable'];
		$totTransPayable	 = ($data['totTransPayable'] * -1);

		$html = '<table width="90%" border="1" cellspacing="4" cellpadding="4" style="font-family:verdana; font-size:13px; border-collapse: collapse;">
			<tbody>
                    <tr>
			<td><b>Total Receivables :</b></td>
			<td align="right">' . number_format($totTransReceivable, 0, '.', ',') . '</td>
			<td colspan="3"></td>
                    </tr>
			<tr>
			<td><b>Total Payables :</b></td>
			<td align="right">' . number_format($totTransPayable, 0, '.', ',') . '</td>
			<td colspan="3"></td>
                    </tr>
			<tr>
			<td colspan="5">&nbsp;</td>
                    </tr>
			<tr>
			<th width="20%" align="left">Amount Range</th>
			<th width="20%" align="right">Count Of Vendors</th>
			<th width="20%" align="right">Total Amount Pending</th>
			<th width="20%" align="right">Average Pending Amount</th>
			<th width="20%" align="right">Avg #Days Pending</th>
                    </tr>
			<tr><th align="left">More than 50K</th>
			<td align="right">' . number_format($data['count50plus'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum50plus'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg50plus'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday50plus'], 0, '.', ',') . '</td>
                    </tr>
			<tr><th align="left">25K to 50K</th>
			<td align="right">' . number_format($data['count50k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum50k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg50k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday50k'], 0, '.', ',') . '</td>
                    </tr>
			<tr><th align="left">15K to 25K</th>
			<td align="right">' . number_format($data['count25k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum25k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg25k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday25k'], 0, '.', ',') . '</td>
                    </tr>
			<tr><th align="left">10K to 15K</th>
			<td align="right">' . number_format($data['count15k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum15k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg15k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday15k'], 0, '.', ',') . '</td>
                    </tr>
			<tr><th align="left">5K to 10K</th>
			<td align="right">' . number_format($data['count10k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum10k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg10k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday10k'], 0, '.', ',') . '</td>
                    </tr>
			<tr><th align="left">3K-5K</th>
			<td align="right">' . number_format($data['count5k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum5k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg5k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday5k'], 0, '.', ',') . '</td>
			</tr>
			<tr><th align="left">Under 3K</th>
			<td align="right">' . number_format($data['count3k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum3k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg3k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday3k'], 0, '.', ',') . '</td>
			</tr>
			<tr><th align="left">Under 0K <br>(ie. Amount Payable)</th>
			<td align="right">' . number_format($data['count0k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['sum0k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avg0k'], 0, '.', ',') . '</td>
			<td align="right">' . number_format($data['avgday0k'], 0, '.', ',') . '</td>
			</tr>
			</tbody>
			</table><br/><br/>';

		return $html;
	}

	public function accountReceivables()
	{
		$sql = "SELECT totTransReceivable, totTransPayable, sum50plus,count50plus,ROUND((sum50plus/count50plus),2) as avg50plus,ROUND((day50plus/count50plus),0) as avgday50plus,
                sum50k,count50k,ROUND((sum50k/count50k),2) as avg50k,ROUND((day50k/count50k),0) as avgday50k,
                count25k,sum25k,ROUND((sum25k/count25k),2) as avg25k,ROUND((day25k/count25k),0) as avgday25k,
                count15k,sum15k,ROUND((sum15k/count15k),2) as avg15k,ROUND((day15k/count15k),0) as avgday15k,
                count10k,sum10k,ROUND((sum10k/count10k),2) as avg10k,ROUND((day10k/count10k),0) as avgday10k,
                count5k,sum5k,ROUND((sum5k/count5k),2) as avg5k,ROUND((day5k/count5k),0) as avgday5k,
                count3k,sum3k,ROUND((sum3k/count3k),2) as avg3k,ROUND((day3k/count3k),0) as avgday3k,
                count0k,sum0k,ROUND((sum0k/count0k),2) as avg0k,ROUND((day0k/count0k),0) as avgday0k
                FROM
                (
                    SELECT 
					SUM(IF(totTrans>0,totTrans,0)) as totTransReceivable,
					SUM(IF(totTrans<=0,totTrans,0)) as totTransPayable,
					SUM(IF(totTrans>50001,totTrans,0)) as sum50plus,
                    SUM(IF(totTrans>50001,1,0)) as count50plus,
                    SUM(IF(totTrans>50001,vrs_effective_overdue_days,0)) as day50plus,
                    SUM(IF(totTrans BETWEEN 25001 AND 50000,totTrans,0)) as sum50k,
                    SUM(IF(totTrans BETWEEN 25001 AND 50000,1,0)) as count50k,
                    SUM(IF(totTrans BETWEEN 25001 AND 50000,vrs_effective_overdue_days,0)) as day50k,
                    SUM(IF(totTrans BETWEEN 15001 AND 25000,totTrans,0)) as sum25k,
                    SUM(IF(totTrans BETWEEN 15001 AND 25000,1,0)) as count25k,
                    SUM(IF(totTrans BETWEEN 15001 AND 25000,vrs_effective_overdue_days,0)) as day25k,
                    SUM(IF(totTrans BETWEEN 10001 AND 15000,totTrans,0)) as sum15k,
                    SUM(IF(totTrans BETWEEN 10001 AND 15000,1,0)) as count15k,
                    SUM(IF(totTrans BETWEEN 10001 AND 15000,vrs_effective_overdue_days,0)) as day15k,
                    SUM(IF(totTrans BETWEEN 5001 AND 10000,totTrans,0)) as sum10k,
                    SUM(IF(totTrans BETWEEN 5001 AND 10000,1,0)) as count10k,
                    SUM(IF(totTrans BETWEEN 5001 AND 10000,vrs_effective_overdue_days,0)) as day10k,
                    SUM(IF(totTrans BETWEEN 3001 AND 5000,totTrans,0)) as sum5k,
                    SUM(IF(totTrans BETWEEN 3001 AND 5000,1,0)) as count5k,
                    SUM(IF(totTrans BETWEEN 3001 AND 5000,vrs_effective_overdue_days,0)) as day5k,
                    SUM(IF(totTrans BETWEEN 0 AND 3000,totTrans,0)) as sum3k,
                    SUM(IF(totTrans BETWEEN 0 AND 3000,1,0)) as count3k,
                    SUM(IF(totTrans BETWEEN 0 AND 3000,vrs_effective_overdue_days,0)) as day3k,
                    ABS(SUM(IF(totTrans<0,totTrans,0))) as sum0k,
                    SUM(IF(totTrans<0,1,0)) as count0k,
                    SUM(IF(totTrans<0,vrs_effective_overdue_days,0)) as day0k
                    FROM
                    (
							SELECT vnd_id, vnd_name , SUM(adt.adt_amount) totTrans, vnd.vnd_active , vendor_stats.vrs_effective_overdue_days
                            FROM `vendors` vnd
							INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vnd.vnd_id AND vendor_stats.vrs_last_trip_datetime >= '2018-04-01 00:00:00' 
                            JOIN account_trans_details adt ON vnd.vnd_id = adt.adt_trans_ref_id
                            JOIN account_transactions act ON act.act_id = adt.adt_trans_id   
                            WHERE act.act_active=1 
							AND adt.adt_type=2 
							AND adt.adt_ledger_id=14 
							AND adt.adt_active=1 
							AND adt.adt_status=1 
                            GROUP BY adt.adt_trans_ref_id 
                            HAVING (totTrans < 100000)     
							
                     ) a
                 )b";
		return DBUtil::queryRow($sql);
	}

	public function getReceivablePendingByVendorHtml()
	{
		$rows = $this->accountReceivableByVendor();

		$html .= '<table width="90%" border="1" cellspacing="4" cellpadding="4" style="font-family:verdana; font-size:13px; border-collapse: collapse;">
			<tbody>
                    <tr>
			<th align="left">Vendor Receivable List :</th>
			<td colspan="3">' . count($rows) . '</td>
			</tr>
			<tr>
			<td colspan="5">&nbsp;</td>
			</tr>
			<tr>
			<th width="25%" align="left">Vendor</th>
			<th width="25%" align="right">Amount Pending</th>
			<th width="25%" align="right">Last Payment Receive Date</th>
			<th width="25%" align="right">Last Payment Receive Amount</th>
			</tr>';

		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$html .= '<tr>
                            <th align="left">' . $row['name'] . '</th>
                            <td align="right">' . number_format($row['totTrans'], 0, '.', ',') . '</td>
                            <td align="right">' . $row['lastTransDate'] . '</td>
                            <td align="right">' . (($row['lastTrans'] != '') ? number_format($row['lastTrans'], 0, '.', ',') : '') . '</td>
                        </tr>';
			}
		}

		$html .= "</table><br/><br/>";
		return $html;
	}

	public function accountReceivableByVendor()
	{
		$randomNumber	 = rand();
		$createTable	 = "AccountTransTemp$randomNumber";

		$sqlDrop = "DROP TABLE IF EXISTS $createTable;";
		DBUtil::command($sqlDrop)->execute();

		$sqlTemp = "CREATE TEMPORARY TABLE $createTable
						   (INDEX my_index_name (act_ref_id))
							SELECT DATE_FORMAT(receivedDate, '%d/%m/%Y') AS lastTransDate,ABS(account_trans_details.adt_amount) AS lastTrans,account_transactions.act_ref_id
							FROM `account_transactions` 
							INNER JOIN `account_trans_details` ON account_transactions.act_id = account_trans_details.adt_trans_id AND account_trans_details.adt_ledger_id IN(23, 29, 30)
							INNER JOIN
							(
								SELECT act_ref_id,MAX(account_transactions.act_date) AS receivedDate
								FROM `account_transactions`
								INNER JOIN `account_trans_details` ON account_transactions.act_id = account_trans_details.adt_trans_id AND account_trans_details.adt_ledger_id IN(23, 29, 30)
								WHERE account_trans_details.adt_amount > 0 	AND account_transactions.act_type = 2 
								GROUP BY act_ref_id
							) AS maxtransDate ON maxtransDate.act_ref_id = account_transactions.act_ref_id AND maxtransDate.receivedDate = account_transactions.act_date 
							WHERE account_trans_details.adt_amount > 0 AND account_transactions.act_type = 2
							GROUP BY act_ref_id ";
		DBUtil::command($sqlTemp)->execute();

		$sql = "SELECT
				vnd_id,vnd_name AS name,SUM(			account_trans_details.adt_amount
				) AS totTrans,
				lastTransDate,
				lastTrans
			FROM  `vendors`
			INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id AND vendor_stats.vrs_last_trip_datetime >= '2018-04-01 00:00:00' 
			INNER JOIN `contact` ON contact.ctt_id = vendors.vnd_contact_id AND vendors.vnd_active=1
			INNER JOIN `account_trans_details` ON account_trans_details.adt_trans_ref_id = vendors.vnd_id
			INNER JOIN `account_transactions` ON account_transactions.act_id = account_trans_details.adt_trans_id
		    LEFT JOIN $createTable AS receive ON receive.act_ref_id = vendors.vnd_id
					WHERE
				account_transactions.act_active = 1 
				AND account_trans_details.adt_type = 2 
				AND account_trans_details.adt_ledger_id = 14 
				AND account_trans_details.adt_active = 1 
				AND account_trans_details.adt_status = 1 
			GROUP BY
				account_trans_details.adt_trans_ref_id
			HAVING 
				(totTrans > 5000)
			ORDER BY
				totTrans
			DESC";
		return DBUtil::queryAll($sql);
	}

	public function missingDriverCarNotification($venActive)
	{

		$sql = "SELECT
				vnd_id,
				vnd_active,
				total_vehicle,
				total_vehicle_approved,
				total_driver,
				total_driver_approved
                FROM vendors
				LEFT JOIN 
                (
					SELECT vvhc_vnd_id,SUM(IF(vhc_active=1,1,0)) as total_vehicle
                            FROM vehicles
                            INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                            WHERE vhc_active=1
					GROUP BY vvhc_vnd_id 
				) a	ON vendors.vnd_id=a.vvhc_vnd_id

				LEFT JOIN 
				(
					SELECT vvhc_vnd_id,SUM(IF(vhc_approved=1,1,0)) as  total_vehicle_approved
                            FROM vehicles
                            INNER JOIN vendor_vehicle ON vvhc_vhc_id = vhc_id
                            WHERE vhc_approved=1
					GROUP BY vvhc_vnd_id		
				) b ON vendors.vnd_id=b.vvhc_vnd_id

				LEFT JOIN 
				(
					SELECT vdrv_vnd_id,SUM(IF(d2.drv_active=1,1,0)) as  total_driver
					FROM  drivers d2
					INNER JOIN vendor_driver ON vdrv_drv_id = d2.drv_id and d2.drv_id = d2.drv_ref_code 
                            WHERE d2.drv_active=1
					GROUP BY vdrv_vnd_id
				) c ON vendors.vnd_id=c.vdrv_vnd_id

				LEFT JOIN 
				(
					SELECT vdrv_vnd_id,SUM(IF(d2.drv_approved=1,1,0)) as  total_driver_approved
					FROM  drivers d2 
					INNER JOIN vendor_driver ON vdrv_drv_id = d2.drv_id  and d2.drv_id = d2.drv_ref_code
                            WHERE d2.drv_approved=1
					GROUP BY vdrv_vnd_id
				) d ON vendors.vnd_id=d.vdrv_vnd_id
                WHERE vnd_active=$venActive";

		$sql	 .= " GROUP BY vendors.vnd_id";
		$sql	 .= " HAVING ((total_vehicle>total_vehicle_approved) OR (total_driver>total_driver_approved))";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ($row['vnd_active'] == 2)
				{
					$payLoadData = ['vendorId' => $row['vnd_id'], 'EventCode' => Booking::CODE_MISSING_PAPERWORK];
					$message	 = "Your account has 0 cars and drivers active.";
					$success	 = AppTokens::model()->notifyVendor($row['vnd_id'], $payLoadData, $message, "Pending Car and Driver paperwork.");
				}
				else if ($row['vnd_active'] == 1)
				{
					$incompleteVehicle	 = ($row['total_vehicle'] - $row['total_vehicle_approved']);
					$incompleteDriver	 = ($row['total_driver'] - $row['total_driver_approved']);
					if ($incompleteVehicle > 0 || $incompleteDriver > 0)
					{
						$message = "Your account has " . $incompleteVehicle . " cars and " . $incompleteDriver . "drivers with incomplete paperwork.";
					}
					else
					{
						$message = "Your account has 0 cars and drivers active.";
					}
					$payLoadData = ['vendorId' => $row['vnd_id'], 'EventCode' => Booking::CODE_MISSING_PAPERWORK];
					$success	 = AppTokens::model()->notifyVendor($row['vnd_id'], $payLoadData, $message, "Pending Car and Driver paperwork.");
				}
			}
		}
	}

	public function updatetnc($data, $vendorId)
	{
		$model = Vendors::model()->findByPk($vendorId);

		$model->vnd_tnc			 = 1;
		$model->vnd_tnc_id		 = $data->new_tnc_id;
		$model->vnd_tnc_datetime = new CDbExpression('NOW()');
		$model->scenario		 = 'updatetnc';
		$result					 = CActiveForm::validate($model);

		if ($result == '[]')
		{
			$model->save();
			return $model;
		}
		else
		{
			return false;
		}
	}

// created by mc
	public static function getVendorByUserId($userId)
	{

		$sql				 = "SELECT *  FROM `vendors` WHERE `vnd_user_id` = :userId";
		$param[':userId']	 = $userId;
		$cdb				 = DBUtil::command($sql);
		$vnd				 = $cdb->queryRow($param);

		return $vnd;
	}

	public static function getVendorCodeByUserId($userId)
	{

		$sql = "SELECT vnd_id,vnd_code_password  FROM `vendors` WHERE `vnd_user_id` = $userId";
		$cdb = DBUtil::command($sql);
		return DBUtil::queryAll($sql);
	}

// check vendor existance through token and logged in according to that token
	public function authoriseVendor($token)
	{

		$appToken1 = AppTokens::model()->find('apt_token_id = :token and apt_status = 1', array('token' => $token));

		if (empty($appToken1))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

//fetch userid from user id
	public function getVendorIdByToken($token)
	{
		$appToken1 = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));

		if (empty($appToken1))
		{
			return false;
		}
		else
		{
			return $appToken1->attributes;
		}
	}

//fetch userid from vendor id
	public function getUseridByVendorId($vendor_id)
	{
		$sql				 = "SELECT vnd_user_id  FROM `vendors` WHERE `vnd_id` = :vendorId";
		$param[':vendorId']	 = $vendor_id;
		$cdb				 = DBUtil::command($sql);
		$userid				 = $cdb->queryScalar($param);
		return $userid;
	}

	public static function getByUserId($userId)
	{
		$params	 = ["userId" => $userId];
		$sql	 = "SELECT * FROM (
						SELECT v1.vnd_id, IF(cp1.cr_is_consumer=:userId, 2, IF(cp.cr_is_consumer=:userId, 1, 0)) as rank FROM contact_profile cp
							INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id
							INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id AND c1.ctt_active=1
							INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
							INNER JOIN vendors v ON v.vnd_id=IFNULL(cp1.cr_is_vendor, cp.cr_is_vendor) AND v.vnd_active>0
							INNER JOIN vendors v1 ON v1.vnd_id=v.vnd_ref_code
							WHERE (cp1.cr_is_consumer=:userId)
						UNION
						SELECT v1.vnd_id, IF(cp1.cr_is_consumer=:userId, 2, IF(cp.cr_is_consumer=:userId, 1, 0)) as rank FROM contact_profile cp
							INNER JOIN contact c ON cp.cr_contact_id=c.ctt_id
							INNER JOIN contact c1 ON c.ctt_ref_code=c1.ctt_id AND c1.ctt_active=1
							INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
							INNER JOIN vendors v ON v.vnd_id=IFNULL(cp1.cr_is_vendor, cp.cr_is_vendor) AND v.vnd_active>0
							INNER JOIN vendors v1 ON v1.vnd_id=v.vnd_ref_code
							WHERE (cp.cr_is_consumer=:userId)
					) a ORDER BY rank DESC";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		if ($row)
		{
			$vendorId	 = $row['vnd_id'];
			$model		 = self::model()->findByPk($vendorId);
		}
		else
		{
			/** @todo To be removed after contact and users data merged */
			$model = self::model()->find("vnd_user_id=:user AND vnd_active>0", ["user" => $userId]);
		}
		return $model;
	}

// vendor login through fb or google
	public function socialVendorlogin($provider, $processSyncdata, $deviceData1)
	{
		$success	 = false;
		$userData	 = CJSON::decode($processSyncdata, true);
		$deviceData	 = CJSON::decode($deviceData1, true);
		$userName	 = '';
		$result		 = [];

		try
		{
			if (!in_array($provider, ['Google', 'Facebook']))
			{
				throw new Exception("Invalid provider", 400);
			}
			$identifier	 = $userData['id'];
			$userRow	 = Users::getBySocialAccount($identifier);
			if (!$userRow)
			{
				throw new Exception("Invalid Social Login", 401);
			}
			$model = self::getByUserId($userRow['user_id']);
			if (!$model)
			{
				throw new Exception("User not linked to Partner Account", 401);
			}

			if ($model->vendorPrefs->vnp_multi_link == 1)
			{
				throw new Exception("Already linked with another Vendor", 401);
			}

			$passWord			 = $userRow['usr_password'];
			$userName			 = $userRow['usr_name'] . ' ' . $userRow['usr_lname'];
			$phone				 = $userRow['usr_mobile'];
			$email				 = $userRow['usr_email'];
			$identity			 = new UserIdentity($email, $passWord);
			$identity->userId	 = $userRow['user_id'];
			if (!$identity->authenticate())
			{
				throw new Exception("Unable to authenticate", 400);
			}
			$identity->setEntityID($model->vnd_id);
			$identity->setUserType(2);
			if ($model->vnd_tnc == 0)
			{
				$model->vnd_tnc_datetime = new CDbExpression('NOW()');
				$model->vnd_tnc			 = 1;
				$tmodel					 = Terms::model()->getText(5);
				$model->vnd_tnc_id		 = $tmodel->tnc_id;
				$model->scenario		 = 'updatetnc';
				$result					 = CActiveForm::validate($model);
				if ($result == '[]')
				{
					$model->save();
				}
			}
			/* @var $webUser GWebUser */
			$webUser	 = Yii::app()->user;
			$webUser->login($identity);
			$aptModel	 = AppTokens::Add($webUser->getId(), 2, Yii::app()->user->getEntityID(), $deviceData);
			if (!$aptModel)
			{
				Yii::log('vendor login failed: ', CLogger::LEVEL_INFO);
				throw new Exception("Failed to create session", 500);
			}
			$sessionId	 = $aptModel->apt_token_id;
			Yii::log('vendor login ' . json_encode($aptModel), CLogger::LEVEL_INFO);
			$tmodel		 = Terms::model()->getText(5);
			$tnc_check	 = false;
			$new_tnc_id	 = $tmodel->tnc_id;
			if ($model->vnd_tnc_id == $tmodel->tnc_id)
			{
				$tnc_check	 = true;
				$new_tnc_id	 = '';
			}
			$code			 = $model->vnd_code;
			$rating			 = VendorStats::fetchRating($model->vnd_id);
//$is_approve		 = Vendors::model()->isApproved($model->vnd_id, 0);
//$is_message		 = Vendors::model()->isApproved($model->vnd_id, 1);
			$is_approve		 = Vendors::model()->isApprovedCnt($model->vnd_id);
			$is_message		 = Vendors::model()->isApprovedMessage($model->vnd_id);
			$documentUpload	 = Document::model()->checkDocumentUpload($model->vnd_id);
			$agreementUpload = VendorAgreement::model()->findAgreementStatusByVndId($model->vnd_id);
			$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($model->vnd_id);

			$vendorLavel	 = $model->vndContact->getName();
			$vendor_details	 = Vendors::model()->findByPk($model->vnd_id);
			$contact_id		 = $vendor_details[attributes]['vnd_contact_id'];
			$vndName		 = $vendor_details[attributes]['vnd_name'];
			$vnd_rel_tier	 = $vendor_details->vnd_rel_tier;
			if ($contact_id != "")
			{
				$contact_details = Contact::model()->getContactDetails($contact_id);
				$email			 = $contact_details['eml_email_address'];
				$phone			 = $contact_details['phn_phone_no'];
			}

			$success = true;

			$result	 = ['sessionId'			 => $sessionId,
				'ownerName'			 => '',
				'user_id'			 => $webUser->getId(),
				"vnd_id"			 => $webUser->getEntityID(),
				"vnd_code"			 => $code,
				'userEmail'			 => $email,
				'userPhone'			 => $phone,
				'userName'			 => $vndName,
				'tnc_check'			 => $tnc_check,
				'new_tnc_id'		 => $new_tnc_id,
				'documentUpload'	 => $documentUpload,
				'agreementUpload'	 => $agreementUpload,
				'versionCheck'		 => $versionCheck,
				'version'			 => '',
				'is_approve'		 => $is_approve,
				'is_message'		 => $is_message,
				'rating'			 => $rating,
				'vendor_level'		 => $vendorLavel];
			$msg	 = "Login Successful";
		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
		}

		$response = [
			'success'	 => $success,
			'message'	 => $msg,
				] + $result;
		Logger::create("Response params :: " . json_encode($response), CLogger::LEVEL_INFO);
		return CJSON::encode($response);
	}

//new function for vendor Login
//old function socialvendor login
	public function vendorLogin($userModel, $deviceData)
	{
		Logger::trace("<===User Id===>" . $userModel->user_id);
		$model = self::getByUserId($userModel->user_id);
		if (empty($model))
		{
			throw new Exception("Vendor account not signed up with this user", ReturnSet::ERROR_UNAUTHORISED);
//$model->addError("authentication", "Vendor account not signed up with this user");
		}
		$identity			 = new UserIdentity($userModel->usr_email, $userModel->usr_password);
		$identity->userId	 = $userModel->user_id;
		$identity->setEntityID($model->vnd_id);
		$identity->setUserType(2);
		if ($model->vnd_tnc == 0)
		{
			$model->vnd_tnc_datetime = new CDbExpression('NOW()');
			$model->vnd_tnc			 = 1;
			$tmodel					 = Terms::model()->getText(5);
			$model->vnd_tnc_id		 = $tmodel->tnc_id;
			$model->scenario		 = 'updatetnc';
			$result					 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				$model->save();
			}
		}
		/* @var $webUser GWebUser */
		$webUser	 = Yii::app()->user;
		$webUser->login($identity);
		$aptModel	 = AppTokens::Add($webUser->getId(), 2, Yii::app()->user->getEntityID(), $deviceData);
		if (!$aptModel)
		{
			throw new Exception("Failed to create token", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		$sessionId	 = $aptModel->apt_token_id;
		$tmodel		 = Terms::model()->getText(5);
		$tnc_check	 = false;
		$new_tnc_id	 = $tmodel->tnc_id;
		if ($model->vnd_tnc_id == $tmodel->tnc_id)
		{
			$tnc_check	 = true;
			$new_tnc_id	 = '';
		}
		$code		 = $model->vnd_code;
		$contactId	 = ContactProfile::getByEntityId($model->vnd_id, UserInfo::TYPE_VENDOR);
		if (!$contactId)
		{
			throw new Exception("Issue in contact profile", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		/** @var Contact $cttModel */
		$cttModel = Contact::model()->findByPk($contactId);
		if (!$contactId)
		{
			throw new Exception("Issue in contact model", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		$vendorLavel = $cttModel->getName();

		$rating			 = VendorStats::fetchRating($model->vnd_id);
		$is_approve		 = Vendors::model()->isApprovedCnt($model->vnd_id);
		$is_message		 = Vendors::model()->isApprovedMessage($model->vnd_id);
		$documentUpload	 = Document::model()->checkDocumentUpload($model->vnd_id);
		$agreementUpload = VendorAgreement::model()->findAgreementStatusByVndId($model->vnd_id);
		$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($model->vnd_id);

		$vendor_details = Vendors::model()->findByPk($model->vnd_id);

		$vndName		 = $vendor_details->vnd_name;
		$vnd_rel_tier	 = $vendor_details->vnd_rel_tier;
		$contact_id		 = $vendor_details->vnd_contact_id;
		$isActive		 = $vendor_details->vnd_active;
		$blockReason	 = ($isActive == 2) ? VendorsLog::getBlockReason($model->vnd_id) : null;
		$isGozoNow		 = (int) $vendor_details->vendorPrefs->vnp_gnow_status;
#$vendorLavel	 = $model->vndContact->getName();
		$result			 = [
			'session'			 => $sessionId,
			'vnd_code'			 => $code,
			'tnc_check'			 => $tnc_check,
			'new_tnc_id'		 => $new_tnc_id,
			'documentUpload'	 => $documentUpload,
			'agreementUpload'	 => $agreementUpload,
			'versionCheck'		 => $versionCheck,
			'userName'			 => $vndName,
			'version'			 => '',
			'is_approve'		 => $is_approve,
			'is_message'		 => $is_message,
			'rating'			 => $rating,
			'vendor_level'		 => $vendorLavel,
			'vendor_contact_id'	 => $contact_id,
			'vendor_id'			 => $model->vnd_id,
			'vnd_rel_tier'		 => $vnd_rel_tier,
			'isActive'			 => $isActive,
			'blockReason'		 => $blockReason,
			'isGozoNow'			 => $isGozoNow
		];

		return $result;
	}

	public function firmTypeList()
	{
		$firmlist = [
			1	 => 'Individual',
			2	 => 'Business',
		];
		asort($firmlist);
		return $firmlist;
	}

	public function getFirmByFirmId($firmId)
	{
		$list = $this->firmTypeList();
		return $list[$firmId];
	}

	/**
	 * 
	 * @param intger $vndId
	 * @return integer
	 */
	public static function getApkVersion($vndId)
	{
		$sql = "SELECT app_tokens.apt_apk_version FROM `app_tokens`
				WHERE app_tokens.apt_user_type = 2 AND app_tokens.apt_entity_id = '$vndId'
				ORDER BY app_tokens.apt_id DESC LIMIT 0, 1";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * 
	 * @param integer $vndId
	 * @return array
	 */
	public function infoDetails($vndId)
	{
		$sql = "SELECT IF(contact.ctt_voter_doc_id IS NULL OR contact.ctt_license_doc_id IS NULL OR contact.ctt_aadhar_doc_id IS NULL OR contact.ctt_pan_doc_id IS NULL OR agreement.vag_soft_path IS NULL, 1, 0) as documentUpload,
                IF(SUM(IF((contact.ctt_profile_path IS NULL) OR (contact.ctt_aadhar_doc_id IS NULL) OR (contact.ctt_pan_doc_id IS NULL) OR (contact.ctt_voter_doc_id IS NULL) OR (contact.ctt_license_doc_id IS NULL) OR (contact.ctt_police_doc_id IS NULL), 1, 0)) > 0, 1, 0) as driverDocumentUpload,
                IF(SUM(IF((v1.vhc_insurance_proof IS NULL AND v2.vhc_insurance_proof IS NULL) OR (v1.vhc_front_plate IS NULL AND v2.vhc_front_plate IS NULL) OR (v1.vhc_rear_plate IS NULL AND v2.vhc_rear_plate IS NULL) OR (v1.vhc_pollution_certificate IS NULL AND v2.vhc_pollution_certificate IS NULL) OR (v1.vhc_reg_certificate IS NULL AND v2.vhc_reg_certificate IS NULL) OR (v1.vhc_permits_certificate IS NULL AND v2.vhc_permits_certificate IS NULL) OR (v1.vhc_fitness_certificate IS NULL AND v2.vhc_fitness_certificate IS NULL), 1, 0)) > 0, 1, 0) as vehicleDocumentUpload,
                a.overDue, vendor_pref.vnp_is_freeze as freeze, vnd.vnd_active as active, NOW() as lastLogin,CAST(vendors.vnd_rel_tier as INT) as vnd_tier,vrs_vnd_overall_rating as rating               FROM `vendors`
                INNER JOIN vendors vnd ON vendors.vnd_id = vnd.vnd_ref_code
				INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id=vnd.vnd_id
                INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id AND cpr.cr_status = 1
                INNER JOIN `contact` ON contact.ctt_id = cpr.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active = 1
				LEFT JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vnd.vnd_id
                LEFT JOIN `vendor_agreement` agreement ON agreement.vag_vnd_id=vnd.vnd_id
                LEFT JOIN(
                    SELECT SUM(ven_trans_amount) as overDue,trans_vendor_id
                    FROM `vendor_transactions` WHERE ven_trans_active = 1
                    GROUP BY trans_vendor_id
                ) a ON a.trans_vendor_id = vnd.vnd_id
                LEFT JOIN vendor_driver on vdrv_vnd_id = vnd.vnd_id
                LEFT JOIN drivers d2 on d2.drv_id = vdrv_drv_id 
				LEFT JOIN drivers_info d3 on d3.drv_driver_id = d2.drv_id 
                LEFT JOIN vendor_vehicle on vvhc_vnd_id = vnd.vnd_id
                LEFT JOIN vehicles v1 on v1.vhc_id = vvhc_vhc_id
                LEFT JOIN vehicles_info v2 on v2.vhc_vehicle_id = v1.vhc_id
                WHERE v1.vhc_is_freeze <> 1 
				AND v1.vhc_active = 1 
				AND d2.drv_is_freeze <> 1 
				AND d2.drv_active = 1 
				AND vnd.vnd_id = '$vndId'";

		$recordset = DBUtil::queryRow($sql);

		$apt_apk_version			 = self::getApkVersion($vndId);
		$recordset['apk_version']	 = $apt_apk_version;
		return $recordset;
	}

	public function getDetailsbyId($vndid)
	{
		$criteria			 = new CDbCriteria;
		$criteria->compare('vnd_id', $vndid);
		$criteria->with		 = [];
		$criteria->together	 = TRUE;
		$model				 = $this->find($criteria);
		$contactId			 = ContactProfile::getByEntityId($vndid, UserInfo::TYPE_VENDOR);
		$model->vndContact	 = Contact::model()->findByPk($contactId);
		$contactModel		 = $model->vndContact;
		$data				 = $model->attributes;
		$dataContact		 = $contactModel->attributes;
		$cttUserType		 = $model->vndContact->ctt_user_type;
		$cttAccType			 = $model->vndContact->ctt_account_type;
		$vndHomeZone		 = $model->vendorPrefs->vnp_home_zone;
//$vndBookingType					 = $model->vendorPrefs->vnp_booking_type;
		$vndBookingType		 = $model->vendorPrefs->vnp_oneway;

		$data['firm_type']				 = $model->getFirmType($cttUserType);
		$data['account_type']			 = $model->getAccountType($cttAccType);
		$data['home_zone']				 = Zones::model()->getZoneById($vndHomeZone);
		$data['accepted_zone']			 = $this->getZoneNameById($data['vnd_id'], 'accepted');
		$data['return_zone']			 = "";
		$data['vendor_city']			 = Cities::getName($dataContact['ctt_city']);
		$data['booking_type']			 = $model->getBookingType($vndBookingType);
		$phoneNo						 = ContactPhone::model()->getContactPhoneById($model->vnd_contact_id);
		$emailAddress					 = ContactEmail::model()->getContactEmailById($model->vnd_contact_id);
		$data ['vnd_name']				 = $model->vnd_name;
		$data['vnd_phone']				 = $phoneNo;
		$data['vnd_email']				 = $emailAddress;
		$data['vnd_alt_contact_number']	 = "";
		$data['vnd_address']			 = $model->vndContact->ctt_address;
		$data['vnd_owner']				 = ($model->vndContact->ctt_user_type == 1) ? $model->vndContact->ctt_first_name . ' ' . $model->vndContact->ctt_last_name : $model->vndContact->ctt_business_name;
		$data['vnd_company']			 = $model->vndContact->ctt_business_name;
		$data['vnd_incorporation_year']	 = "";
		$data['vnd_sedan_count']		 = $model->vendorPrefs->vnp_sedan_count;
		$data['vnd_sedan_rate']			 = "";
		$data['vnd_compact_count']		 = $model->vendorPrefs->vnp_compact_count;
		$data['vnd_compact_rate']		 = "";
		$data['vnd_suv_count']			 = $model->vendorPrefs->vnp_suv_count;
		$data['vnd_suv_rate']			 = "";
		$data['vnd_bank_name']			 = $model->vndContact->ctt_bank_name;
		$data['vnd_bank_branch']		 = $model->vndContact->ctt_bank_branch;
		$data['vnd_bank_account_no']	 = $model->vndContact->ctt_bank_account_no;
		$data['vnd_bank_ifsc']			 = $model->vndContact->ctt_bank_ifsc;
		$data['vnd_beneficiary_name']	 = $model->vndContact->ctt_beneficiary_name;
		$data['vnd_notes']				 = $model->vendorPrefs->vnp_notes;
		$data['serviceType']			 = $model->vendorPrefs->vnp_oneway;
		return $data;
	}

	public function getFirmType($firmid = 0)
	{
		$firmType = $this->firm_type;
		if ($firmid > 0)
		{
			return $firmType[$firmid];
		}
		else
		{
			return $firmType;
		}
	}

	public function getAccountType($accid = 0)
	{
		$accType = $this->accType;
		if ($accid > 0)
		{
			return $accType[$accid];
		}
		else
		{
			return $accType;
		}
	}

	public function getZoneNameById($vndid, $type)
	{
		if ($type == 'accepted')
		{
			$sql = "SELECT GROUP_CONCAT(zones.zon_name) znames
                    FROM vendor_pref join zones on find_in_set(zones.zon_id,replace(vnp_accepted_zone,', ',','))
                    WHERE vnp_vnd_id = $vndid GROUP BY vnp_vnd_id";
		}
		$result = DBUtil::queryRow($sql);
		return $result['znames'];
	}

	public function getBookingType($bookingType = 0)
	{
		$bookingTypes = $this->filterType;
		return $bookingTypes[$bookingType];
	}

	public function getApiMapping($dataSet = [])
	{
		$vendorContact						 = $this->vndContact;
		$dataSet['vnd_owner']				 = $this->getName();
		$dataSet['vnd_company']				 = $vendorContact->ctt_business_name;
		$dataSet['vnd_address']				 = $vendorContact->ctt_address;
		$dataSet['vnd_phone']				 = ContactPhone::model()->getContactPhoneById($this->vnd_contact_id);
		$dataSet['vnd_alt_contact_number']	 = ContactPhone::model()->getAlternateContactById($this->vnd_contact_id)->altPhoneNo;
		$dataSet['vnd_email']				 = ContactEmail::model()->getContactEmailById($this->vnd_contact_id);
		$dataSet['vnd_land_phone']			 = '';
		$dataSet['vnd_land_phone2']			 = '';
		$dataSet['vnd_voter_no']			 = $vendorContact->ctt_voter_no;
		$dataSet['vnd_aadhaar_no']			 = $vendorContact->ctt_aadhaar_no;
		$dataSet['vnd_pan_no']				 = $vendorContact->ctt_pan_no;
		$dataSet['vnd_license_no']			 = $vendorContact->ctt_license_no;
		$dataSet['vnd_license_issue_date']	 = $vendorContact->ctt_license_issue_date;
		$dataSet['vnd_license_exp_date']	 = $vendorContact->ctt_license_exp_date;
		$dataSet['vnd_bank_name']			 = $vendorContact->ctt_bank_name;
		$dataSet['vnd_bank_account_no']		 = $vendorContact->ctt_bank_account_no;
		$dataSet['vnd_bank_branch']			 = $vendorContact->ctt_bank_branch;
		$dataSet['vnd_beneficiary_name']	 = $vendorContact->ctt_beneficiary_name;
		$dataSet['vnd_bank_ifsc']			 = $vendorContact->ctt_bank_ifsc;
		$dataSet['vnd_account_type']		 = $vendorContact->ctt_account_type;
		$dataSet['vnd_city']				 = $vendorContact->ctt_city;
		$dataSet['vnd_city_name']			 = Cities::getName($dataSet['vnd_city']);
		$dataSet['bussinessType']			 = $vendorContact->ctt_business_type;

		return $dataSet;
	}

	public function findByVendorContactID($vnd_contact_id)
	{
		$model = Vendors::model()->find('vnd_contact_id=:vnd_contact_id AND vnd_active>0', ['vnd_contact_id' => $vnd_contact_id]);
		return $model;
	}

	public function getAssigningJSON()
	{
		$arrVendor	 = $this->getApprovedList();
		$arrJSON	 = [];

		foreach ($arrVendor as $key => $val)
		{
			if ($val != '')
			{
				$arrJSON[] = array("id" => $key, "text" => $val);
			}
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function getCancelReasonList()
	{
// 2, 3, 5, 7
		$cancelStatus = [
			1	 => 'Assigned By Mistake',
			2	 => 'Cab Not Approved',
			3	 => 'Cab Not Available',
			5	 => 'Cab Not In Good Condition',
			6	 => 'Customer itinerary changed',
			7	 => 'Vendor Denied Duty',
			8	 => 'Smart match available',
			10	 => 'Smart match broken',
			12	 => 'Price Issue',
			13	 => 'Wrong itinerary',
			14	 => 'Wrong Segment Cab Sent',
			15	 => 'No cab available for the said cab segment',
			16	 => 'Cab delay',
			9	 => 'Other',
			11	 => 'Other (without penalty)'
		];
		return $cancelStatus;
	}

	public function searchfetchlist()
	{
		$query1	 = [];
		$query2	 = '';
		$query	 = 'SELECT DISTINCT vnd_id, vnd_name, contact_phone.phn_phone_no as vnd_phone from vendors
					JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id AND vendors.vnd_id = vendors.vnd_ref_code
                                        JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status = 1
					JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active = 1
					JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id';
		if ($this->from_city != '')
		{
			$query		 .= " INNER JOIN zone_cities z1 ON (FIND_IN_SET(z1.zct_zon_id, vendor_pref.vnp_accepted_zone) OR FIND_IN_SET(z1.zct_zon_id, vendor_pref.vnp_home_zone)) AND z1.zct_cty_id=" . $this->from_city;
			$query1[]	 = "FIND_IN_SET(" . $this->from_city . ", vendor_pref.vnp_excluded_cities)";
		}
		if ($this->to_city != '')
		{
			$query		 .= " INNER JOIN zone_cities z2 ON (FIND_IN_SET(z2.zct_zon_id, vendor_pref.vnp_accepted_zone) OR FIND_IN_SET(z2.zct_zon_id, vendor_pref.vnp_home_zone)) AND z2.zct_cty_id=" . $this->to_city;
			$query1[]	 = "FIND_IN_SET(" . $this->to_city . ", vendor_pref.vnp_excluded_cities
)";
		}
		if (!empty($query1))
		{
			$query2 = " AND vnd_id NOT IN (SELECT vnd_id FROM vendors WHERE " . implode(" OR ", $query1) . ")";
		}
		$query .= " WHERE vnd_active=1" . $query2;

		$count			 = DBUtil::command("SELECT COUNT(*) From ($query) a")->queryScalar();
		$dataProvider	 = new CSqlDataProvider($query, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['vnd_name', 'vnd_phone']
			],
			'pagination'	 => ['pageSize' => 50]
		]);
		return $dataProvider;
	}

	public function getInvoicePdf($vendorId, $fromDate, $toDate)
	{
		$record		 = Vendors::model()->getDrillDownInfo($vendorId);
		$dataList	 = Booking::model()->getInvoiceData($vendorId, $fromDate, $toDate, 1);
		$html2pdf	 = Yii::app()->ePdf->mPdf();
		$data		 = ['record'	 => $record,
			'dataList'	 => $dataList,
			'pdf'		 => $html2pdf,
			'fromDate'	 => $fromDate,
			'toDate'	 => $toDate];
		return $data;
	}

	public function getRegionPerfReport($type = '', $date1 = '', $date2 = '', $region = '')
	{
		$sqlByDate	 = '';
		$sqlRegion1	 = '';
		$sqlRegion2	 = '';
		if ($date1 != '' && $date2 != '')
		{
			$sqlByDate .= "AND booking.bkg_create_date BETWEEN '$date1' AND '$date2'";
		}
		if (isset($region) && $region != '')
		{
			$sqlRegion1	 .= "AND state1.stt_zone=$region";
			$sqlRegion2	 .= " AND stt_zone =$region";
		}
		$sql = "SELECT (CASE WHEN (stt_zone='1') THEN 'North'
                        WHEN (stt_zone='2') THEN 'West'
                        WHEN (stt_zone='3') THEN 'Central'
                        WHEN (stt_zone='4') THEN 'South'
                        WHEN (stt_zone='5') THEN 'East'
                        WHEN (stt_zone='6') THEN 'North East'
                           END
                       ) as region, vendors.vnd_name, vendor_stats.vrs_vnd_overall_rating as vnd_overall_rating, countAssigned as bookings_assigned,
                       countAssignedAdvanced as bookings_assigned_advance, countAssignedCod as bookings_assigned_cod,
                       bookingCancelled as bookings_cancelled, bookingCancelledAdvance as bookings_cancelled_advance,
                       bookingCancelledCod as booking_cancelled_cod, bookingAmount as booking_amount,
                       vendorAmount as vendor_amount
                    FROM `vendors`
					JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                    LEFT JOIN (
                        SELECT SUM(IF(booking.bkg_status IN (2,3,5,6,7),'1','0')) as countAssigned,
                        SUM(IF((booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount)>0 AND booking.bkg_status IN (2,3,5,6,7),'1','0')) as countAssignedAdvanced,
                        SUM(IF((booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount)=0 AND booking.bkg_status IN (2,3,5,6,7),'1','0')) as countAssignedCod,
                        SUM(IF((booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount)>0 AND booking.bkg_status=9,'1','0')) as bookingCancelledAdvance,
                        SUM(IF((booking_invoice.bkg_advance_amount-booking_invoice.bkg_refund_amount)=0 AND booking.bkg_status=9,'1','0')) as bookingCancelledCod,
                        SUM(IF(booking.bkg_status=9,'1','0')) as bookingCancelled,
                        booking_cab.bcb_vendor_id,
                        SUM(IF(booking.bkg_status IN (2,3,5,6,7),booking_invoice.bkg_total_amount,'0')) as bookingAmount,
                        SUM(IF(booking.bkg_status IN (2,3,5,6,7),booking_invoice.bkg_vendor_amount,'0')) as vendorAmount,stateName,stt_zone
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
						INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
                        LEFT JOIN(
                            SELECT states.stt_name as stateName,cities.cty_id,states.stt_zone
                            FROM `states`
                            INNER JOIN `cities` ON cities.cty_state_id=states.stt_id
                            GROUP BY cities.cty_id
                        ) state1 ON state1.cty_id=booking.bkg_from_city_id
                        WHERE 1
                        AND booking_cab.bcb_active=1 $sqlRegion1
                        AND booking.bkg_status IN (2,3,5,6,7,9) $sqlByDate
                        GROUP BY booking_cab.bcb_vendor_id
                    ) book1 ON book1.bcb_vendor_id=vendors.vnd_id
                    WHERE vendors.vnd_active=1  $sqlRegion2
                    GROUP BY vendors.vnd_id ";

		$sqlCount = "SELECT book1.bcb_vendor_id
                    FROM `vendors`
					JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                    LEFT JOIN (
                        SELECT 
                        booking_cab.bcb_vendor_id,stt_zone
                        FROM `booking_cab`
                        INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
						INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
                        LEFT JOIN(
                            SELECT states.stt_name as stateName,cities.cty_id,states.stt_zone
                            FROM `states`
                            INNER JOIN `cities` ON cities.cty_state_id=states.stt_id
                            GROUP BY cities.cty_id
                        ) state1 ON state1.cty_id=booking.bkg_from_city_id
                        WHERE 1
                        AND booking_cab.bcb_active=1 $sqlRegion1
                        AND booking.bkg_status IN (2,3,5,6,7,9) $sqlByDate
                        GROUP BY booking_cab.bcb_vendor_id
                    ) book1 ON book1.bcb_vendor_id=vendors.vnd_id
                    WHERE vendors.vnd_active=1 $sqlRegion2
                    GROUP BY vendors.vnd_id ";

		if ($type == 'command')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['region', 'vnd_name', 'bookings_assigned', 'bookings_assigned_advance', 'bookings_assigned_cod', 'bookings_cancelled', 'bookings_cancelled_advance', 'booking_cancelled_cod'],
					'defaultOrder'	 => ''],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
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

	public function createNew($arr, $docTrans = 0)
	{
		$success	 = false;
		$getErrors	 = [];
		$userInfo	 = UserInfo::getInstance();
		try
		{
			$cityName		 = '';
			$cityName		 = strtolower(Cities::getName($arr['vnd_city']));
			$model			 = new Vendors();
			$modelStats		 = new VendorStats();
			$modelPref		 = new VendorPref();
			$modelDevice	 = new VendorDevice();
			$modelContact	 = new Contact();

			$model->scenario				 = 'unregVendorjoin';
			$modelContact->ctt_first_name	 = $arr['vnd_firstName'];
			$modelContact->ctt_last_name	 = $arr['vnd_lastName'];
			$modelContact->ctt_city			 = $arr['vnd_city'];
			$modelContact->ctt_voter_no		 = $arr['vnd_voter_no'];
			$modelContact->ctt_pan_no		 = $arr['vnd_pan_no'];
			$modelContact->ctt_aadhaar_no	 = $arr['vnd_aadhaar_no'];
			$modelContact->ctt_license_no	 = $arr['vnd_license_no'];
			$modelContact->ctt_address		 = $arr['vnd_address'];
			$modelContact->ctt_user_type	 = 1;
			if (!$modelContact->save())
			{
				throw new Exception('failed to save to contact');
			}
			if ($arr['vnd_phone'] != '')
			{
				$modelContactPhone					 = new ContactPhone();
				$modelContactPhone->phn_phone_no	 = $arr['vnd_phone'];
				$modelContactPhone->phn_contact_id	 = $modelContact->ctt_id;
				$modelContactPhone->phn_is_primary	 = 1;
				if (!$modelContactPhone->save())
				{
					throw new Exception('failed to save');
				}
			}
			if ($arr['vnd_email'] != '')
			{
				$modelContactEmail						 = new ContactEmail();
				$modelContactEmail->eml_email_address	 = $arr['vnd_email'];
				$modelContactEmail->eml_is_primary		 = 1;
				$modelContactEmail->eml_contact_id		 = $modelContact->ctt_id;
				if (!$modelContactEmail->save())
				{
					throw new Exception('failed to save');
				}
			}
			$model->vnd_cat_type	 = $arr['vnd_is_driver'];
			$model->vnd_name		 = $arr['vnd_owner'];
			$model->vnd_active		 = $arr['vnd_active'];
			$model->vnd_contact_id	 = $modelContact->ctt_id;
			$model->vnd_uvr_id		 = $arr['vnd_uvr_id'];

			$contEmailPhone	 = Contact::model()->getContactDetails($model->vnd_contact_id);
			$usersId		 = Users::model()->linkUserid($contEmailPhone['eml_email_address'], $contEmailPhone['phn_phone_no']);
			if ($usersId != "")
			{
				$model->vnd_user_id = $usersId;
			}
			else
			{
				$this->createUserByVendor($model->vnd_id, $contEmailPhone, $arr['vnd_password'], 2);
			}

			if ($model->validate())
			{
				if ($model->save())
				{
					$vendorId		 = $model->vnd_id;
					$codeArray		 = Filter::getCodeById($vendorId, 'vendor');
					$model->vnd_code = $codeArray['code'];
					$model->vnd_name = $model->vnd_name . "-" . $codeArray['code'] . "-" . $cityName;
					$model->scenario = 'unregUpdateVendorJoin';
					if ($model->save())
					{
						$modelStats->vrs_vnd_id	 = $model->vnd_id;
						$modelPref->vnp_vnd_id	 = $model->vnd_id;
						$modelDevice->vdc_vnd_id = $model->vnd_id;
						$modelStats->save();
						$modelPref->save();
						$modelDevice->save();
						$desc					 = "New Vendor created";

						VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, VendorsLog::VENDOR_CREATED, false, false);
						if ($docTrans == 1)
						{
							if ($arr['vnd_voter_front_path'] != '')
							{
								$modelDocumentV						 = new Document();
								$modelDocumentV->doc_file_front_path = $arr['vnd_voter_front_path'];
								$modelDocumentV->doc_type			 = 2;
								$modelDocumentV->doc_status			 = 0;
								$modelDocumentV->save();
								$modelContact->ctt_voter_doc_id		 = $modelDocumentV->doc_id;
							}
							if ($arr['vnd_aadhaar_front_path'] != '')
							{
								$modelDocumentA						 = new Document();
								$modelDocumentA->doc_file_front_path = $arr['vnd_aadhaar_front_path'];
								$modelDocumentA->doc_type			 = 3;
								$modelDocumentA->doc_status			 = 0;
								$modelDocumentA->save();
								$modelContact->ctt_aadhar_doc_id	 = $modelDocumentA->doc_id;
							}
							if ($arr['vnd_pan_front_path'] != '')
							{
								$modelDocumentP						 = new Document();
								$modelDocumentP->doc_file_front_path = $arr['vnd_pan_front_path'];
								$modelDocumentP->doc_type			 = 4;
								$modelDocumentP->doc_status			 = 0;
								$modelDocumentP->save();
								$modelContact->ctt_pan_doc_id		 = $modelDocumentP->doc_id;
							}
							if ($arr['vnd_licence_front_path'] != '')
							{
								$modelDocumentL						 = new Document();
								$modelDocumentL->doc_file_front_path = $arr['vnd_licence_front_path'];
								$modelDocumentL->doc_type			 = 5;
								$modelDocumentL->doc_status			 = 0;
								$modelDocumentL->save();
								$modelContact->ctt_license_doc_id	 = $modelDocumentL->doc_id;
							}
							$modelContact->save();
						}
						$emailWrapper	 = new emailWrapper();
						$emailWrapper->adminVendorSignupEmail($model, $modelContact->ctt_id);
						$success		 = true;
					}
					else
					{
						$getErrors	 = $model->getErrors();
						$msg		 = "Error saving  " . json_encode($model->getErrors());
						throw new Exception($msg);
					}
				}
				else
				{
					$msg = "Error saving  " . json_encode($model->getErrors());
					throw new Exception($msg);
				}
			}
			else
			{
				$getErrors	 = $model->getErrors();
				$msg		 = "Error saving  " . json_encode($model->getErrors());
				throw new Exception($msg);
			}
		}
		catch (Exception $ex)
		{
//DBUtil::rollbackTransaction($transaction);
			$errors = $ex->getMessage();
			throw new Exception($errors);
		}
		return ['success' => $success, 'errors' => $errors, 'msg' => $msg, 'vendorID' => $vndID];
	}

	public function updateDriverMismatchCount($vndId)
	{
		$sql = "UPDATE `vendors`,vendor_stats
				SET    vendor_stats.vrs_driver_mismatch_count = (vendor_stats.vrs_driver_mismatch_count + 1)
				WHERE  vendors.vnd_id = $vndId AND vendor_stats.vrs_vnd_id = vendors.vnd_id";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function updateCarMismatchCount($vndId)
	{
		$sql = "UPDATE `vendors`,vendor_stats
				SET    vendor_stats.vrs_car_mismatch_count = (vendor_stats.vrs_car_mismatch_count + 1)
				WHERE  vendors.vnd_id = $vndId AND vendor_stats.vrs_vnd_id = vendors.vnd_id";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public function getRelatedContact($conatctId, $arr, $active)
	{
		$where	 = "";
		$where0	 = "";
		if ($arr['name'])
		{
			$name	 = $arr['name'];
			$where	 = " AND  ( (t.ctt_business_name LIKE '%" . $name . "%') OR (t.ctt_first_name LIKE '%" . $name . "%') OR (t.ctt_last_name LIKE '%" . $name . "%'))";
			$where0	 = " AND ( (cntt.ctt_business_name LIKE '%" . $name . "%') OR (cntt.ctt_first_name LIKE '%" . $name . "%') OR (cntt.ctt_last_name LIKE '%" . $name . "%'))";
		}
		if ($arr['email_address'])
		{
			$cntemail	 = $arr['email_address'];
			$where		 = "  AND (cnte.eml_email_address LIKE '%" . $cntemail . "%')";
		}
		if ($arr['phone_no'])
		{
			$cntph	 = $arr['phone_no'];
			$where	 = "  AND (cntp.phn_phone_no LIKE '%" . $cntph . "%')";
		}

		$sql = " SELECT vnd.vnd_id,t.ctt_created_date,t.ctt_first_name,t.ctt_last_name, t.contactperson,	t.ctt_user_type,t.ctt_active,t.ctt_business_name,t.ctt_id, cntp.phn_phone_no, cnte.eml_email_address, rank from    
                        ( 
						SELECT ( 
						         CASE cnt0.ctt_user_type
						             WHEN 1 THEN concat(cnt0.ctt_first_name, ' ' , cnt0.ctt_last_name)
								    WHEN 2 THEN cnt0.ctt_business_name
						         END
						        ) as contactperson,
								cnt0.ctt_created_date,cnt0.ctt_user_type,cnt0.ctt_first_name,cnt0.ctt_last_name, cnt0.ctt_active,cnt0.ctt_business_name,cnt0.ctt_id, 1 as rank  from contact cnt0 where ctt_id IN (
	                    SELECT distinct cntt.ctt_id FROM contact c1
						INNER JOIN contact cntt ON (cntt.ctt_first_name=c1.ctt_first_name or cntt.ctt_last_name=c1.ctt_last_name or cntt.ctt_business_name=c1.ctt_business_name)
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
				INNER JOIN Vendors as vnd ON t.ctt_id=vnd.vnd_contact_id 
                JOIN contact_phone as cntp ON t.ctt_id=cntp.phn_contact_id 
				LEFT JOIN contact_email as cnte ON t.ctt_id = cnte.eml_contact_id
                where  vnd.vnd_active >0 and t.ctt_active = $active AND cnte.eml_is_primary = 1 AND cntp.phn_is_primary = 1 and cntp.phn_active = 1  and cnte.eml_active = 1 and  t.ctt_id<> $conatctId $where";

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

	public static function getCountByCity($city)
	{
		$sql = "SELECT *
				FROM 
                   (
							SELECT cities_stats.cts_cty_id AS vnd_city, cities_stats.cts_vnd_cnt AS cnt, 0 AS distance
							FROM cities_stats
							WHERE     cities_stats.cts_cty_id = :city	AND cities_stats.cts_vnd_cnt >= 2

							UNION
							SELECT cities_stats.cts_cty_id AS vnd_city, cities_stats.cts_vnd_cnt AS cnt, IFNULL(rut_actual_distance, rut_estm_distance) AS distance
							FROM route
							INNER JOIN cities_stats  ON  cities_stats.cts_cty_id = route.rut_from_city_id AND cities_stats.cts_vnd_cnt >= 2 AND IFNULL(rut_actual_distance, rut_estm_distance) < 40
							WHERE rut_to_city_id = :city
							GROUP BY cities_stats.cts_cty_id   
                    ) a
				ORDER BY DISTANCE ASC";

		$param[':city']	 = $city;
		$row			 = DBUtil::queryRow($sql, DBUtil::SDB(), $param, 60 * 60 * 24, CacheDependency::Type_Vendor);
		if (!$row)
		{
			$row = null;
		}
		return $row;
	}

	public static function getNearestCity($city)
	{
		$sql = "SELECT *
				FROM 
                   (
							SELECT cities_stats.cts_cty_id AS vnd_city, cities_stats.cts_vnd_cnt AS cnt, 0 AS distance
							FROM cities_stats
							WHERE     cities_stats.cts_cty_id = :city	AND cities_stats.cts_vnd_cnt >= 2

							UNION
							SELECT cities_stats.cts_cty_id AS vnd_city, cities_stats.cts_vnd_cnt AS cnt, IFNULL(rut_actual_distance, rut_estm_distance) AS distance
							FROM route
							INNER JOIN cities_stats  ON  cities_stats.cts_cty_id = route.rut_from_city_id AND cities_stats.cts_vnd_cnt >= 2 AND rut_estm_distance < 800
							WHERE rut_to_city_id = :city
							GROUP BY cities_stats.cts_cty_id   
                    ) a
				ORDER BY DISTANCE ASC LIMIT 0,1";

		$param[':city']	 = $city;
		$row			 = DBUtil::queryRow($sql, DBUtil::SDB(), $param, 60 * 60 * 24, CacheDependency::Type_Vendor);
		if (!$row)
		{
			$row = null;
		}
		return $row;
	}

	public static function getHomeZonesCount($zones)
	{
		if (empty($zones))
		{
			return 0;
		}
		DBUtil::getINStatement($zones, $bindString, $params);

		$sql	 = "SELECT COUNT(1) as cnt FROM vendors
				INNER JOIN vendor_pref ON vnd_id=vnp_vnd_id AND vnd_active=1 
				WHERE vendor_pref.vnp_is_freeze=0 AND vendor_pref.vnp_manual_freeze=0 AND vendor_pref.vnp_is_dormant=0 
					AND vendor_pref.vnp_low_rating_freeze=0 AND vendor_pref.vnp_doc_pending_freeze=0 AND vendor_pref.vnp_credit_limit_freeze=0 
					AND vendor_pref.vnp_home_zone IN ($bindString)
				GROUP BY vnp_home_zone ORDER BY cnt DESC
				";
		$count	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params, 60 * 60 * 24, CacheDependency::Type_Vendor);
		if (!$count)
		{
			$count = 0;
		}
		return $count;
	}

	public function freezeVendor($vndId, $freezeType = 0, $value = 0, $reason = NULL)
	{
		$userInfo	 = UserInfo::getInstance();
		$model		 = Vendors::model()->resetScope()->findByPk($vndId);
		$modelPrefs	 = $model->vendorPrefs;
		$success	 = false;
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$contactId	 = ($contactId == '') ? $model->vnd_contact_id : $contactId;
		if ($freezeType == 1) // COD Freeze/Unfreeze
		{
			$modelPrefs->vnp_cod_freeze = ($value == 0 ? 1 : 0);
			if ($modelPrefs->save())
			{
				$event_id	 = VendorsLog::VENDOR_COD_FREEZE;
				$desc		 = ($value == 0 ? "COD freeze." : "COD Unfreezed.");
				$success	 = true;
				Vendors::notifyFreezeUnfreezeVendor($vndId, $event_id, $desc, $modelPrefs->vnp_cod_freeze);
			}
		}
		else if ($freezeType == 2) // Credit Limit Freeze
		{
			if ($modelPrefs->vnp_credit_limit_freeze != $value)
			{
				$modelPrefs->vnp_credit_limit_freeze = $value;
				if ($modelPrefs->save())
				{
					$event_id	 = ($value == 1 ? VendorsLog::VENDOR_FREEZE : VendorsLog::VENDOR_UNFREEZE);
					$desc1		 = ($value == 1 ? "Credit Freezed (Eff. Cr. limit exceeded)" : "Credit Unfreezed (Eff. Cr. limit restored)");
					$desc		 = "Vendor " . $desc1;
					$success	 = true;
					Vendors::notifyFreezeUnfreezeVendor($vndId, $event_id, $desc, $value);
				}
			}
		}
		else if ($freezeType == 3) // Low Rating
		{
			$modelPrefs->vnp_low_rating_freeze = 1;
			if ($modelPrefs->save())
			{
				$event_id	 = VendorsLog::VENDOR_ADMINISTRATIVE_FREEZE;
				$desc		 = "Vendor freezed. [Low Ratings (" . $model->vendorStats->vrs_vnd_overall_rating . ") ]";
				$success	 = true;
				Vendors::notifyFreezeUnfreezeVendor($vndId, $event_id, $desc, 1);
			}
		}
		else if ($freezeType == 4) // No Agreement/Document pending
		{
			$modelPrefs->vnp_doc_pending_freeze = 1;
			if ($modelPrefs->save())
			{
				$event_id	 = VendorsLog::VENDOR_ADMINISTRATIVE_FREEZE;
				$desc		 = "Vendor freezed (Incomplete or missing docs)";
				$success	 = true;
				Vendors::notifyFreezeUnfreezeVendor($vndId, $event_id, $desc, 1);
			}
		}
		else if ($freezeType == 5) // Manual Freeze/Unfreeze
		{
			if ($modelPrefs->vnp_is_freeze == 0)
			{
				$modelPrefs->vnp_manual_freeze	 = 1;
				$message						 = 'Your vendor account is Freezed.';
				$event_id						 = VendorsLog::VENDOR_ADMINISTRATIVE_FREEZE;
				$desc							 = "Vendor is Administrative Freezed. Reason -->" . trim($reason);
			}
			else if ($modelPrefs->vnp_is_freeze > 0)
			{
				//remove all freezes when we do manual unfreeze
				$modelPrefs->vnp_credit_limit_freeze = 0;
				$modelPrefs->vnp_low_rating_freeze	 = 0;
				$modelPrefs->vnp_doc_pending_freeze	 = 0;
				$modelPrefs->vnp_manual_freeze		 = 0;
				//$modelPrefs->vnp_is_freeze		     = 0;
				Vendors::model()->updateDetails($vndId);

				Vendors::notifyToAccountUnblocked($vndId);

//				$response							 = WhatsappLog::accountUnblocked($vndId, $contactId);
//				$message							 = 'Your vendor account is unblocked.';
				$event_id	 = VendorsLog::VENDOR_ADMINISTRATIVE_UNFREEZE;
				$desc		 = "Vendor account now unblocked. Reason -->" . trim($reason);
			}
			if ($modelPrefs->save())
			{
				$success = true;
				Vendors::notifyFreezeUnfreezeVendor($vndId, $event_id, $desc, $modelPrefs->vnp_manual_freeze);
			}
//			if ($value)
//			{
//				$number = ContactPhone::getContactPhoneById($contactId);
//				// SMS
//				if ($response['status'] == 3)
//				{
//					$msgCom	 = new smsWrapper();
//					$name	 = $model->vndContact->getName();
//					$smstype = SmsLog::VENDOR_ADMINISTRATIVE_UNFREEZED;
//					$msgCom->informVendorOnBlocknFreezed('91', $number, $message, $name, $vndId, $smstype);
//				}
//			}
		}
		if ($success)
		{
			$result = Vendors::model()->updateFreeze($vndId);
		}
		return $result;
	}

	public function updateFreeze($vndId = 0)
	{
		$userInfo = UserInfo::getInstance();
		if ($vndId > 0)
		{
			$model		 = Vendors::model()->resetScope()->findByPk($vndId);
			$modelPrefs	 = $model->vendorPrefs;
			$sum		 = $modelPrefs->vnp_credit_limit_freeze + $modelPrefs->vnp_low_rating_freeze + $modelPrefs->vnp_doc_pending_freeze + $modelPrefs->vnp_manual_freeze;
			$success	 = false;

			$modelPrefs->vnp_is_freeze = ($sum > 0 ? 1 : 0);
			if ($modelPrefs->save())
			{
				$success = true;
			}
		}
		else
		{
			$makeVendorFreezeList	 = "SELECT `vnp_vnd_id` FROM `vendor_pref` WHERE ((vendor_pref.vnp_credit_limit_freeze + vendor_pref.vnp_low_rating_freeze + vendor_pref.vnp_doc_pending_freeze + vendor_pref.vnp_manual_freeze) > 0) AND vendor_pref.vnp_is_freeze = 0";
			$makeVndFreezeList		 = DBUtil::command($makeVendorFreezeList)->queryAll();

			$sql = "UPDATE vendor_pref SET vendor_pref.vnp_is_freeze = 1 
					WHERE ((vendor_pref.vnp_credit_limit_freeze + vendor_pref.vnp_low_rating_freeze + vendor_pref.vnp_doc_pending_freeze + vendor_pref.vnp_manual_freeze) > 0) AND vendor_pref.vnp_is_freeze = 0";

			$success = DBUtil::command($sql)->execute();
			if ($success)
			{
				foreach ($makeVndFreezeList as $key => $value)
				{
					$vendorId	 = $value['vnp_vnd_id'];
					$event_id	 = VendorsLog::VENDOR_FREEZE;
					$desc		 = "Vendor Freezed.";
					VendorsLog::model()->createLog($vendorId, $desc, $userInfo, $event_id);
				}
			}
		}
		return $success;
	}

	public function saveMergeData($oldData, $oldDataStats, $oldDataPref, $arr, $agreement_file = '', $agreement_file_tmp = '', $type = '')
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$vndid		 = $this->vnd_id;
		$transaction = DBUtil::beginTransaction();

		$arrcontact = Contact::model()->getContactDetails($arr['vnd_contact_id']);
		if (!($this->vnd_id))
		{
			$arr['vnd_name'] = $arr['vnd_name'] . '-' . Cities::getName($arrcontact['ctt_city']) . '-' . $arrcontact['ctt_business_name'];
		}
		$agreement_date		 = $arr['vnd_agreement_date1'];
		$this->attributes	 = $arr;
		$newData			 = $this->attributes;
		$newPrefData		 = $this->vendorPrefs->attributes;
		$newStatsData		 = $this->vendorStats->attributes;

		try
		{
			$result = CActiveForm::validate($this, null, false);
			if ($result == '[]')
			{
				if ($this->save())
				{
					$this->vendorPrefs->setAttribute('vnp_vnd_id', $this->vnd_id);
					$this->vendorPrefs->save();

					$this->vendorStats->setAttribute('vrs_vnd_id', $this->vnd_id);
					$this->vendorStats->save();

					if ($agreement_file != '' && $agreement_file_tmp != '')
					{
						$agreement_date = DateTimeFormat::DatePickerToDate($agreement_date);
						if ($agreement_date == '1970-01-01')
						{
							$agreement_date = date("Y-m-d");
						}
						$uploadedFile = CUploadedFile::getInstance($this, "vnd_agreement_file_link");
						if ($uploadedFile != '')
						{
							$path	 = $this->uploadVendorFiles($uploadedFile, $this->vnd_id, 'agreement');
							$success = $this->saveDocument($this->vnd_id, $path, $userInfo, 'agreement', $agreement_date);
						}
					}

					VendorAgreement::model()->saveAgreement($this->vnd_id);
					if ($vndid > 0)
					{
						$desc				 = "Vendor modified |";
						$getOldDifference	 = array_merge(array_diff_assoc($oldData, $newData) + array_diff_assoc($oldDataStats, $newStatsData) + array_diff_assoc($oldDataPref, $newPrefData));
						$changesForLog		 = " Old Values: " . $this->getModificationMSG($getOldDifference, false);
						$desc				 .= $changesForLog;
						VendorsLog::model()->createLog($this->vnd_id, $desc, $userInfo, VendorsLog::VENDOR_MODIFIED, false, false);
					}
					$success = DBUtil::commitTransaction($transaction);
				}
			}
		}
		catch (Exception $e)
		{
			$this->addError("vnd_id", $e->getMessage());
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function checkExistingVendor($userId)
	{
		$sql = "SELECT count(1) FROM vendors WHERE vnd_user_id = '$userId' AND vnd_active>0";
		return DBUtil::command($sql)->queryScalar();
	}

	public static function checkExists($userId)
	{
		$sql	 = "SELECT COUNT(1) FROM vendors WHERE vnd_user_id = :id AND vnd_active > 0";
		$count	 = DBUtil::command($sql, DBUtil::MDB())->queryScalar(['id' => $userId]);
		return ($count > 0);
	}

	public function getDuplicateUserByVendor($vndId, $userId)
	{
		$pageSize		 = 25;
		$sql			 = "SELECT vnd_name,vnd_code,vnd_id,vnd_contact_id,vnd_user_id FROM vendors WHERE vnd_user_id = '$userId' AND vnd_active>0";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vnd_name', 'vnd_code', 'vnd_contact_id', 'vnd_user_id'],
				'defaultOrder'	 => 'vnd_name  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);

		return $dataprovider;
	}

	public function updateVendorMerge($vendorarr, $vndId)
	{
		$sql = "Update `vendors` set vnd_active=2 WHERE vnd_id  in ($vendorarr)";
		$cnt = DBUtil::command($sql)->execute();
		foreach ($vendorarr as $value)
		{
			$event_id	 = VendorsLog::VENDOR_INACTIVE;
			$desc		 = "Vendor is Blocked. Reason: Vendor Mergerd";
			$userInfo	 = UserInfo::getInstance();
			VendorsLog::model()->createLog($value, $desc, $userInfo, $event_id);
		}

		$sql = "Update `vendors` set vnd_is_merged=1,vnd_merged_to=$vndId WHERE vnd_id  in ($vendorarr)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "Update `vendors` set `vnd_ref_code` =$vndId WHERE vnd_id  in ($vendorarr)";
		$cnt = DBUtil::command($sql)->execute();

		$sql = "update `vendors` set vnd_merge_on = now() WHERE vnd_id  in ($vendorarr)";
		$cnt = DBUtil::command($sql)->execute();
	}

	public function getVendorContact($userID)
	{
		$sql	 = "SELECT cntp.phn_phone_no as phone_no 
				FROM vendors vnd 
				INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id AND cpr.cr_status = 1
				INNER JOIN contact_phone cntp ON cpr.cr_contact_id = cntp.phn_contact_id
				WHERE vnd.vnd_user_id = $userID";
		$result	 = DBUtil::queryRow($sql);
		return $result['phone_no'];
	}

	public function getAllVendorIdsByUserId($userId)
	{
		$sql			 = "SELECT vnd_id,vnd_user_id,vnd_contact_id FROM vendors WHERE vnd_user_id = '$userId' AND vnd_active>0  ";
		$vendorIdsAll	 = DBUtil::queryAll($sql);
		return $vendorIdsAll;
	}

	public function getActiveVendorDetails($userID)
	{
		$sql	 = "SELECT vnd.vnd_id as vnd_id,vnd.vnd_name as vnd_name,cntp.ctt_first_name as fname, cntp.ctt_last_name as lname "
				. "FROM vendors vnd "
				. "INNER JOIN contact cntp ON  vnd.vnd_contact_id = cntp.ctt_id "
				. "INNER JOIN vendor_pref vndprf ON  vnd.vnd_id = vndprf.vnp_vnd_id "
				. " WHERE cntp.ctt_active = 1  AND vndprf.vnp_is_freeze = 0   AND vnd.vnd_user_id = $userID";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	public function getPendingAppNotification()
	{
		$sql = "Select v1.vnd_id from vendors v1
				INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
				INNER JOIN app_tokens ON v2.vnd_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type = 2 
				AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 3 DAY) AND apt_device_token IS NOT NULL AND apt_status=1
				where v1.vnd_active = 1 AND v2.vnd_id = v2.vnd_ref_code GROUP BY v2.vnd_ref_code";

		$cdb		 = DBUtil::command($sql);
		$vendorList	 = $cdb->queryAll();
		return $vendorList;
	}

	public function findVendorListForNotification()
	{
		$sql = "SELECT
				vnd_id,
				vnp_accepted_zone,
				vnp_home_zone,
				vnp_excluded_cities
				FROM
				vendors 
				INNER JOIN vendor_pref vnp ON vnd_id = vnp.vnp_vnd_id AND vnd_active = 1 and vnd_id=vnd_ref_code
				INNER JOIN vendor_stats vrs ON vnd_id = vrs.vrs_vnd_id  AND vrs.vrs_last_logged_in >= DATE_SUB(NOW(), INTERVAL 3 DAY)
				WHERE 1 group by vnd_ref_code ";
		return DBUtil::queryAll($sql);
	}

	public function updateVendorAfterSendNotification($vndId)
	{
		$sql = "UPDATE vendors SET vnd_notify_datetime = NOW() WHERE vnd_id=$vndId";
		DBUtil::command($sql)->execute();
	}

	public function getActiveVendor()
	{
		$sql = "SELECT vnd_id FROM vendors WHERE vnd_id = vnd_ref_code";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function checkDuplicateVehicleNo($attribute, $params)
	{
		$vnumber = str_replace(' ', '', strtolower(trim($this->vnd_car_number)));
		$sql	 = "SELECT vhc_id,vhc_insurance_exp_date,vhc_reg_exp_date,vhc_year,vhc_description,vhc_type_id,vht.vht_model,
				REPLACE(LOWER(vhc_number),' ', '') as vhc_number FROM vehicles 
				LEFT JOIN vehicle_types vht ON vht.vht_id=vehicles.vhc_type_id 
				where  vhc_active > 0 HAVING vhc_number='$vnumber'
				order by vhc_approved,vhc_created_at desc";
		$result	 = DBUtil::queryAll($sql);

		if (count($result) > 0)
		{
			$this->local_vehicle_year	 = $result[0]['vhc_year'];
			$this->local_vehicle_model	 = $result[0]['vht_model'];
			$this->addError($attribute, "Duplicate vehicle number.");
			return false;
		}
		return true;
	}

	public function saveDocument($vendorId, $path, UserInfo $userInfo = null, $doc_type, $agreement_date = '')
	{
		Logger::trace("vendor id" . $vendorId);
		$success = false;
		if ($path != '' && $vendorId != '')
		{
			try
			{
				if ($doc_type == 'agreement')
				{
					Logger::trace("type id" . $doc_type);
					/* @var $model Vendors */
					$model		 = Vendors::model()->findByPk($vendorId);
					/* @var $modelAgmt VendorAgreement */
					$modelAgmt	 = VendorAgreement::model()->findByVndId($vendorId);
					if ($modelAgmt == '')
					{
						/* @var $modelAgmt2 VendorAgreement */
						$modelAgmt2					 = new VendorAgreement();
						$modelAgmt2->vag_vnd_id		 = $vendorId;
						$modelAgmt2->vag_soft_date	 = $agreement_date;
						$modelAgmt2->vag_soft_path	 = $path;
//$modelAgmt2->vag_soft_flag	 = 1;
						$modelAgmt2->vag_soft_ver	 = Yii::app()->params['digitalagmtversion'];
						$modelAgmt2->save();
//$success					 = true;
					}
					else
					{
						$modelAgmt->vag_soft_path	 = $path;
						$modelAgmt->vag_soft_date	 = $agreement_date;
//$modelAgmt->vag_soft_flag	 = 1;
						$modelAgmt->vag_soft_ver	 = $modelAgmt->vag_digital_ver;
						$modelAgmt->save();
//$success					 = true;
					}
					$model->vnd_agreement_date = $agreement_date;
					$model->save();
				}
				$success	 = true;
				$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
				$logArray	 = VendorsLog::model()->getLogByDocumentType($doc_type);
				$logDesc	 = VendorsLog::model()->getEventByEventId($logArray['upload']);
				VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
				DBUtil::commitTransaction($transaction);
				return $success;
			}
			catch (Exception $ex)
			{
				$model->addError('vnd_id', $e->getMessage());
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function getDetails($vndId)
	{

		$rows = VehicleStats::model()->getUnapprovedDocByVndId($vndId);
		return $rows;
	}

	public function getByPickupDropoffCity($fcity, $tcity)
	{
		$query = "SELECT DISTINCT v2.vnd_id, v2.vnd_name, contact_phone.phn_phone_no as vnd_phone from vendors v1
					INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
					INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = v2.vnd_id
                    INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = v2.vnd_id AND cpr.cr_status = 1
                    JOIN contact ON contact.ctt_id = cpr.cr_contact_id
					JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id";
		if ($fcity)
		{
			$query .= " INNER JOIN zone_cities z1 ON (FIND_IN_SET(z1.zct_zon_id, vendor_pref.vnp_accepted_zone) OR FIND_IN_SET(z1.zct_zon_id, vendor_pref.vnp_home_zone)) AND z1.zct_cty_id=" . $fcity;
		}
		if ($tcity)
		{
			$query .= " INNER JOIN zone_cities z2 ON (FIND_IN_SET(z2.zct_zon_id, vendor_pref.vnp_accepted_zone) OR FIND_IN_SET(z2.zct_zon_id, vendor_pref.vnp_home_zone)) AND z2.zct_cty_id=" . $tcity;
		}
		$query	 .= " WHERE v2.vnd_active=1 AND v2.vnd_id = v2.vnd_ref_code";
		return $count	 = DBUtil::command("SELECT COUNT(*) From ($query) a")->queryScalar();
	}

	public function getDetailsAdmin($page_no = 0, $total_count = 0, $search_txt = '')
	{
		$offset = $page_no * 20;

		$qry = "SELECT v2.vnd_id, v2.vnd_name, contact_phone.phn_phone_no AS vnd_phone, contact_email.eml_email_address AS vnd_email,
						c1.cty_name AS vnd_city, v2.vnd_create_date, vendor_stats.vrs_mark_vend_count,zones.zon_name as home_zone, 
						vendor_stats.vrs_vnd_overall_rating, v2.vnd_active
						FROM vendors v1 
						INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code	
						INNER JOIN vendor_pref ON v2.vnd_id = vendor_pref.vnp_vnd_id
						INNER JOIN vendor_stats ON v2.vnd_id = vendor_stats.vrs_vnd_id
                        INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = v2.vnd_id AND cpr.cr_status = 1
						INNER JOIN contact ON contact.ctt_id = cpr.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code 
						LEFT JOIN contact_phone ON contact.ctt_id = contact_phone.phn_contact_id AND contact_phone.phn_is_primary = 1
						LEFT JOIN contact_email ON contact.ctt_id = contact_email.eml_contact_id AND contact_email.eml_is_primary = 1
						LEFT JOIN cities c1 ON	c1.cty_id = contact.ctt_city
						LEFT JOIN zones ON zones.zon_id = vendor_pref.vnp_home_zone 
						WHERE vendor_pref.vnp_is_freeze <> 1 AND v2.vnd_active > 0 AND v2.vnd_id = v2.vnd_ref_code";

		if ($search_txt != '')
		{
			$search_txt	 = str_replace(' ', '%', $search_txt);
			$qry		 .= " AND (v2.vnd_name LIKE '%$search_txt%' OR v2.vnd_code LIKE '%$search_txt%' 
					OR contact_phone.phn_phone_no='$search_txt' OR contact_email.eml_email_address='$search_txt' 
					OR c1.cty_name LIKE '%$search_txt%' OR contact.ctt_name LIKE '%$search_txt%')";
		}

		$qry		 .= ($total_count == 0) ? " LIMIT 50 OFFSET $offset" : "";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getDetailsAdminDropDown($search_txt = '')
	{
		$qry		 = "select vnd_id, vnd_name from vendors WHERE  vnd_id = vnd_ref_code";
		$qry		 .= ($search_txt != '') ? " AND (vnd_name LIKE '%$search_txt%')" : "";
		$recordset	 = DBUtil::queryAll($qry);
		return $recordset;
	}

	public function getNotificationForVendorUsingDriverApp()
	{
		$sql = "SELECT   vendors.vnd_id
      FROM booking
					INNER JOIN booking_cab ON  booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
					JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					JOIN booking_track ON booking_track.btk_bkg_id = booking.bkg_id
				INNER JOIN vendors ON vendors.vnd_id = booking_cab.bcb_vendor_id AND vendors.vnd_active = 1 and vendors.vnd_ref_code = vendors.vnd_id
				WHERE    booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() AND (booking.bkg_status IN (6, 7) OR (booking.bkg_status =5 AND booking_track.bkg_ride_complete = 1))
      GROUP BY vendors.vnd_id
				HAVING   round(((SUM(IF(booking_trail.btr_drv_score >= 70, 1, 0)) / COUNT(DISTINCT booking.bkg_id)) * 100), 2) < 75";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getVndCountByZone($fzone)
	{
		$sql = " SELECT COUNT(vnp_vnd_id) cnt
				FROM   vendors 
				       INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd_id
				WHERE  vnd_active = 1 AND (vnp.vnp_home_zone = $fzone OR FIND_IN_SET($fzone, vnp_accepted_zone)) AND vnp.vnp_is_freeze = 0 AND
				       vnp.vnp_is_orientation =
				       2 AND vnp.vnp_orientation_type = 1 AND vnd_id = vnd_ref_code";
		return Yii::app()->db1->createCommand($sql)->queryScalar();
	}

	public static function notificationForGoldenTier($vndId, $message, $title)
	{
		$payLoadData = ['EventCode' => Booking::CODE_VENDOR_TIER];
		$success	 = AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, $title);
		if ($success == true)
		{
			$result = json_encode($payLoadData) . " / " . $message . " / " . $title;
//echo $result."\n";
			Logger::create($result, CLogger::LEVEL_INFO);
		}
		else
		{
			Logger::create("Notification not sent ", CLogger::LEVEL_ERROR);
		}
	}

	/**
	 * 
	 * @param integer $vndId
	 * @return object
	 * @throws Exception
	 */
	public function updateVendorName($vndId)
	{
		$returnSet = new ReturnSet();
		try
		{
			/* @var $model Vendors */
			$model			 = $this->findByPk($vndId);
			$model->vnd_name = $this->generateName($vndId);
			$model->scenario = 'upgradeName';
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setData(['name' => $model->vnd_name]);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $vndId
	 * @param integer $vndType
	 * @return object
	 * @throws Exception
	 */
	public function updateVendorCatType($vndId, $vndType = '')
	{
		$returnSet = new ReturnSet();
		try
		{
			/* @var $model Vendors */
			$model = $this->findByPk($vndId);
			if (isset($vndType) && $vndType != '')
			{
				$model->vnd_cat_type = $vndType;
				goto ApprovedCar;
			}
			$model->vnd_cat_type = Vendors::setVendorType($model->vnd_id);
			ApprovedCar:
			$model->scenario	 = 'upgradeType';
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setData(['vnd_type' => $model->vnd_cat_type]);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $vndId
	 * @return integer
	 */
	public static function setVendorType($vndId)
	{
		$getCatType = VendorVehicle::getApprovedCarByVndId($vndId);
		return ($getCatType > 0) ? $getCatType : 0;
	}

	/**
	 * 
	 * @param integer $vndId
	 * @return string
	 * @throws Exception
	 */
	public function generateName()
	{
		$vndId			 = $this->vnd_id;
		$vndInfo		 = [];
		$cttId			 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$contactModel	 = Contact::model()->findByPk($cttId);
		$vndInfo[]		 = Filter::strReplace($contactModel->getName());

		if (isset($this->vnd_code) && $this->vnd_code != '')
		{
			$vndInfo[] = $this->vnd_code;
		}

		if ($this->vendorPrefs->vnp_home_zone != '')
		{
			$vndInfo[] = Zones::model()->getZoneById($this->vendorPrefs->vnp_home_zone);
		}

		$name = implode("_", Filter::removeNull($vndInfo));

		return $name;
	}

	/**
	 * 
	 * @param integer $vndId
	 * @param integer $tier
	 * @param array $userInfo
	 * @return boolean
	 * @throws Exception
	 */
	public function updateTire($vndId, $tier = 0, $userInfo)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($tier > 0)
			{
				/* @var $model Vendors */
				$model				 = $this->findByPk($vndId);
				$model->vnd_rel_tier = $tier;
				$model->scenario	 = 'upgradeTire';
				if ($model->validate() && $model->save())
				{
					if (isset($tier) && $tier == 1)
					{
						$message = "You have been upgraded to Golden tier.";
						$eventId = VendorsLog::VENDOR_GOLDEN_TIER;
					}
					VendorsLog::model()->createLog($model->vnd_id, $message, $userInfo, $eventId, false, false);
					$success = DBUtil::commitTransaction($transaction);
				}
				else
				{
					throw new Exception("Errors : " . $model->getErrors());
				}
			}
			else
			{
				/* @var $model VendorPref */
				$model							 = VendorPref::model()->getByVendorId($vndId);
				$model->vnp_deny_tire_upgrade	 = 1;
				if ($model->validate() && $model->save())
				{
					$message = "You denied tier upgrade.";
					$eventId = VendorsLog::VENDOR_TIER_DENY;
					VendorsLog::model()->createLog($model->vnp_vnd_id, $message, $userInfo, $eventId, false, false);
					$success = DBUtil::commitTransaction($transaction);
				}
				else
				{
					throw new Exception("Errors : " . $model->getErrors());
				}
			}
			$result = ['success' => $success, 'message' => $message];
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$errorCode	 = $ex->getCode();
			$errors		 = $ex->getMessage();
			$result		 = ['success' => $success, 'errors' => $errors, 'errorCode' => $errorCode];
		}
		return $result;
	}

	/**
	 * 
	 * @param integer $oldRmId
	 * @param integer $newRmId
	 *  
	 * */
	public function updateRelationshipManager($oldRmId, $newRmId)
	{
		$sql		 = "UPDATE `vendors` SET vendors.vnd_rm = $newRmId WHERE vendors.vnd_rm = $oldRmId";
		$recordset	 = DBUtil::command($sql)->execute();

		if ($recordset != '')
		{
			return true;
		}
		return false;
	}

	public function unAssignBlockVendor($vndId)
	{
		$sql	 = "SELECT
				bkg_id,
				bcb.bcb_id,
				bkg_pickup_date,
				bkg_status
				FROM
					booking
				INNER JOIN booking_cab bcb ON
					bkg_bcb_id = bcb.bcb_id AND bcb.bcb_vendor_id = $vndId
				WHERE
					bkg_status IN(3, 5) AND bkg_pickup_date > NOW() + INTERVAL 1 HOUR";
		$result	 = DBUtil::queryAll($sql, DBUtil::SDB());
		foreach ($result as $key => $value)
		{
			$reason		 = 'Vendor has been blocked.';
			$reasonId	 = 20;
			Booking::model()->canVendor($value['bcb_id'], $reason, null, [], $reasonId);
		}
	}

	public function getBlockedVendorList($param = '')
	{
		if ($param == '')
		{
			$param = ' vlg1.vlg_created BETWEEN(NOW() - INTERVAL 90 DAY) AND NOW()';
		}

		$sql		 = "SELECT  vnd_id,
						CASE
						   WHEN stt.stt_zone = 1 THEN 'North'
						   WHEN stt.stt_zone = 2 THEN 'West'
						   WHEN stt.stt_zone = 3 THEN 'Central'
						   WHEN stt.stt_zone = 4 THEN 'South'
						   WHEN stt.stt_zone = 5 THEN 'East'
						   WHEN stt.stt_zone = 6 THEN 'North East'
						   WHEN stt.stt_zone = 7 THEN 'South'
						   ELSE '-'
						END
						   AS Region,
						vnd_name,
						vnd_create_date      AS joinDate,
						IFNULL(vrs.vrs_vnd_overall_rating , 'NA') AS vnd_avg_rating,
						vlg.vlg_created  AS blocked_date,
						vlg.vlg_desc
					  FROM vendors AS a
					  JOIN vendors_log AS vlg
						 ON vlg.vlg_id =
							(SELECT vlg1.vlg_id
							 FROM vendors_log AS vlg1
							 WHERE     vlg1.vlg_vnd_id = a.vnd_id
								   AND vlg1.vlg_event_id = 4
								   AND ( $param  )
							 ORDER BY vlg1.vlg_created DESC
							 LIMIT 1)
					  INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd_id
					  INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd_id
					  INNER JOIN zone_cities zc ON vnp.vnp_home_zone = zc.zct_zon_id
					  INNER JOIN cities cty ON zc.zct_cty_id = cty.cty_id
					  INNER JOIN states stt ON cty.cty_state_id = stt.stt_id
				 WHERE vnd_active = 2
				 GROUP BY vnd_id";
		$sqlCount	 = "SELECT  distinct vnd_id
					  FROM vendors AS a
					  JOIN vendors_log AS vlg
						 ON vlg.vlg_id =
							(SELECT vlg1.vlg_id
							 FROM vendors_log AS vlg1
							 WHERE     vlg1.vlg_vnd_id = a.vnd_id
								   AND vlg1.vlg_event_id = 4
								   AND ( $param  )
							 ORDER BY vlg1.vlg_created DESC
							 LIMIT 1)
					  INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd_id
					  INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd_id
					  INNER JOIN zone_cities zc ON vnp.vnp_home_zone = zc.zct_zon_id
					  INNER JOIN cities cty ON zc.zct_cty_id = cty.cty_id
					  INNER JOIN states stt ON cty.cty_state_id = stt.stt_id
				 WHERE vnd_active = 2";

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['joinDate', 'blocked_date'],
				'defaultOrder'	 => 'vnd_id DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function blockedVendorExportList($date1, $date2)
	{
		if ($date1 != null && $date2 != null)
		{
			$param = " vlg1.vlg_created BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
		}
		else
		{
			$param = 'vlg1.vlg_created BETWEEN(NOW() - INTERVAL 90 DAY) AND NOW()';
		}
		$sql = "SELECT vnd_id,
						CASE
						   WHEN stt.stt_zone = 1 THEN 'North'
						   WHEN stt.stt_zone = 2 THEN 'West'
						   WHEN stt.stt_zone = 3 THEN 'Central'
						   WHEN stt.stt_zone = 4 THEN 'South'
						   WHEN stt.stt_zone = 5 THEN 'East'
						   WHEN stt.stt_zone = 6 THEN 'North East'
						   WHEN stt.stt_zone = 7 THEN 'South'
						   ELSE '-'
						END
						   AS Region,
						vnd_name,
						vnd_create_date      AS joinDate,
						IFNULL(vrs.vrs_vnd_overall_rating , 'NA') AS vnd_avg_rating,
						vlg.vlg_created  AS blocked_date,
						vlg.vlg_desc
					  FROM vendors AS a
					  JOIN vendors_log AS vlg
						 ON vlg.vlg_id =
							(SELECT vlg1.vlg_id
							 FROM vendors_log AS vlg1
							 WHERE     vlg1.vlg_vnd_id = a.vnd_id
								   AND vlg1.vlg_event_id = 4
								   AND ( $param  )
							 ORDER BY vlg1.vlg_created DESC
							 LIMIT 1)
					  INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd_id
					  INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd_id
					  INNER JOIN zone_cities zc ON vnp.vnp_home_zone = zc.zct_zon_id
					  INNER JOIN cities cty ON zc.zct_cty_id = cty.cty_id
					  INNER JOIN states stt ON cty.cty_state_id = stt.stt_id
				 WHERE vnd_active = 2
				 GROUP BY vnd_id
				 ORDER BY vnd_id DESC";

		$recordSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $recordSet;
	}

	public function amountPenalties($model, $platformName, $event = "", $trackLogModel = NULL)
	{
		$returnSet			 = new ReturnSet();
		$bookingId			 = $model->btkBkg->bkg_booking_id;
		$bkgId				 = $model->btkBkg->bkg_id;
		$pickupDate			 = $model->btkBkg->bkg_pickup_date;
		$eventTriggeredTime	 = ($event == 203) ? $model->bkg_trip_arrive_time : $model->bkg_trip_start_time;
//		$eventTriggeredTime			 = $model->bkg_trip_start_time; //Received from App end for pickup
//		$eventTriggeredTime			 = $model->bkg_trip_arrive_time; //Received from App end for arrive
		$vendorId			 = $model->btkBkg->bkgBcb->bcb_vendor_id;
		$estimateStart		 = date("Y-m-d H:i:s", strtotime($pickupDate . "-30 minutes"));
		$tripDuration		 = $model->btkBkg->bkg_trip_duration;
		$estimateComplete	 = date("Y-m-d H:i:s", strtotime($pickupDate . "+ $tripDuration minutes"));
		$arrivedCoordinates	 = BookingTrackLog::model()->getCoordinatesByEvent($bkgId, BookingTrack::DRIVER_ARRIVED);
		$arrived			 = explode(',', $arrivedCoordinates['btl_coordinates']);
		if (($arrivedCoordinates['btl_coordinates'] == "" || $arrivedCoordinates['btl_coordinates'] == NULL ) && $trackLogModel->btl_event_type_id == 203)
		{

			$arrived = explode(',', $trackLogModel->btl_coordinates);
		}
		$arrivedLat	 = (float) $arrived[0];
		$arrivedLong = (float) $arrived[1];

		$eventTriggeredCoordinates = explode(",", $model->bkg_trip_start_coordinates);
		if ($model->bkg_trip_start_coordinates == NULL || $model->bkg_trip_start_coordinates == "")
		{
			$eventTriggeredCoordinates = explode(",", $model->btkBkg->bookingRoutes[0]->brt_from_latitude . ',' . $model->btkBkg->bookingRoutes[0]->brt_from_longitude);
		}
		$startLat	 = (float) $eventTriggeredCoordinates[0];
		$startLong	 = (float) $eventTriggeredCoordinates[1];
		$qry		 = "SELECT CalcDistance($arrivedLat,$arrivedLong,$startLat,$startLong) AS `CalcDistance`";
		$distance	 = ((DBUtil::command($qry)->queryScalar()));

		if ($pickupDate >= $eventTriggeredTime)
		{
			$returnSet->setMessage("No Overdue penalties");
			goto end;
		}
		/*
		 * As discussed with Deepesh Arora and Kaushal Goenka on 13/10/2020 2 km changed to 15 km 		 
		 */
//		if ($distance > 15 && $event == 101)
//		{
//			$penaltyAmount	 = 200;
//			$message		 = "Trip started late. OTP verified ({$platformName}).";
//			$remarks		 = "Arrived location and pickup location are different. Penalty against booking ID #$bookingId";
//			$penaltyType     = PenaltyRules::PTYPE_ARRIVED_LOCATION_DIFFERENT;
//			$result		     = AccountTransactions::checkAppliedPenaltyByType($bkgId, $penaltyType);
//			if($result)
//			{
//			AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, $penaltyAmount, $remarks,'', $penaltyType);
//			}
//			$returnSet->setMessage($message);
//			goto end;
//		}
		if ($event == 203)
		{

			$sepatater		 = " ";
			$vendorAmount	 = $model->btkBkg->bkgBcb->bcb_vendor_amount;
			/* <=3km: No penalty 3 and <6: Rs 50 =6km: 10% of VA (As per trello) */
			if ($trackLogModel->btl_is_discrepancy == 1 && ($arrivedLat != 0 && $arrivedLong != 0) && ($startLat != 0 && $startLong != 0))
			{
				$discrepenciesPenaltyAmount = BookingTrack::getDiscrepancyPenality($distance, $vendorAmount, PenaltyRules::PTYPE_DRIVER_ARRIVED_FAR_FROM_LOCATION);
				if ($discrepenciesPenaltyAmount > 0)
				{
					$discrepenciesRemarks	 = "Driver arrived {$distance}km far from location for booking #$bookingId";
					$penaltyType			 = PenaltyRules::PTYPE_DRIVER_ARRIVED_FAR_FROM_LOCATION;
					$result					 = AccountTransactions::checkAppliedPenaltyByType($bkgId, $penaltyType);
					if ($result)
					{
						AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, $discrepenciesPenaltyAmount, $discrepenciesRemarks, '', $penaltyType);
					}
				}
			}
			$penaltyAmount	 = BookingTrack::getLateArrivePenality($bkgId, $model->bkg_trip_arrive_time, PenaltyRules::PTYPE_DRIVER_ARRIVED_LATE);
			$transferzId	 = Config::get('transferz.partner.id');
			if ($penaltyAmount > 0 && $transferzId != $model->btkBkg->bkg_agent_id)
			{
				$lateArriveRemarks	 = "Driver arrived late for booking #$bookingId";
				$penaltyType		 = PenaltyRules::PTYPE_DRIVER_ARRIVED_LATE;
				$result				 = AccountTransactions::checkAppliedPenaltyByType($bkgId, $penaltyType);
				if ($result)
				{
					AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, $penaltyAmount, $lateArriveRemarks, '', $penaltyType);
				}
			}
			if ($discrepenciesRemarks != "" && $lateArriveRemarks != "")
			{
				$sepatater = "|";
			}
			$remarks = $discrepenciesRemarks . $sepatater . $lateArriveRemarks;
			$returnSet->setMessage($remarks);
		}
//		if ($model->btkBkg->bkgPref->bkg_trip_otp_required == 1)
//		{
//			$penaltyAmount = BookingTrack::getLatePenality($bkgId, $model->bkg_trip_start_time);
//			if ($penaltyAmount > 0)
//			{
//				$message = "OTP verified({$platformName}).";
//				$remarks = "Late complete booking #$bookingId";
//				$penaltyType = Penalty::PTYPE_LATE_COMPLETE_BOOKING;
//				$result		     = AccountTransactions::checkAppliedPenaltyByType($bkgId, $penaltyType);
//				if($result)
//				{
//				AccountTransactions::model()->addVendorPenalty($bkgId, $vendorId, $penaltyAmount, $remarks,'', $penaltyType);
//				}
//			}
//			$returnSet->setMessage($message);
//		}

		end:
		return $returnSet;
	}

	/**
	 * This function is used for registering vendors in the system
	 * @param type $requestData
	 */
	public static function registerVendor($receivedData)
	{
		$returnset = new ReturnSet();
		try
		{
			if (empty($receivedData))
			{
				throw new Exception("Data missing", ReturnSet::ERROR_INVALID_DATA);
			}

			$requestData = json_decode($receivedData);

			$drvLicenseNo = $requestData->driverLicenseNo;
			if (!empty($drvLicenseNo))
			{
				$isExists = Contact::model()->checkLicenseNo($drvLicenseNo);
				if ($isExists->getStatus())
				{
					$contactId = Contact::getContactIdByLicense($drvLicenseNo);
					if ($contactId)
					{
						$contactProfile = ContactProfile::getProfileByCttId($contactId);
						if (empty($contactProfile['cr_is_vendor']))
						{
							$requestData->contactId = $contactId;
							goto add;
						}
						$returnset->setErrors("License already exists in our system. Please contact our team to verify your details", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
						goto skipAll;
					}
				}
			}
			add:
			$isNewFlag = 0;
			if ($requestData->contactId > 0)
			{
				$contactId						 = $requestData->contactId;
				/**
				 * update Exiting Contact 
				 */
				$contactModel					 = Contact::model()->findByPk($contactId);
				$contactModel->ctt_first_name	 = $requestData->fName;
				$contactModel->ctt_last_name	 = $requestData->lName;
				$contactModel->ctt_city			 = $requestData->cityId;
				$contactModel->ctt_business_name = empty($requestData->businessName) ? "" : $requestData->businessName;
				$contactModel->ctt_license_no	 = empty($requestData->driverLicenseNo) ? "" : $requestData->driverLicenseNo;
				/**
				 * save ref code for contact 
				 */
				Contact::model()->updateRefCode($contactId, $contactId);

				if (!$contactModel->save())
				{
					throw new Exception(json_encode($contactModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				$emailResponse	 = ContactEmail::add($requestData->emailId, $contactId, 1);
				$phResponse		 = ContactPhone::add($contactId, $requestData->phoneNumber, UserInfo::TYPE_VENDOR, $requestData->countryCode, SocialAuth::Eml_Gozocabs, 1, 0);
			}
			else
			{
				$isNewFlag		 = 1;
//Create New Contact
				$newContact		 = Contact::addContact($requestData);
				$contactDetails	 = $newContact->getData();

				$contactId	 = $contactDetails->id;
				$phoneData	 = $contactDetails->phone->getData();
			}

			if ($requestData->telegramId != null)
			{
				Users::updateIreadVal($requestData->socialLoginUserId, $requestData->telegramId);
			}


			if (!$contactId)
			{
				$returnset->setErrors("Failed to create contact", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				goto skipAll;
			}

//Create new vendor
			$vendorModel					 = new Vendors();
			$vendorModel->vnd_user_id		 = UserInfo::getUserId();
			$vendorModel->vnd_contact_id	 = $contactId;
			$vendorModel->vnd_name			 = $requestData->fName . $requestData->lName;
			$vendorModel->vnd_cat_type		 = 2; //Vendor
			$vendorModel->vnd_is_dco		 = 0;
			$vendorModel->city				 = $requestData->cityId;
			$vendorModel->vnd_tnc			 = 1;
			$vendorModel->vnd_tnc_id		 = 6;
			$vendorModel->vnd_tnc_datetime	 = new CDbExpression('NOW()');

			$vendorModel->vnd_active = 3; //Save in pending for approval state

			if (!$vendorModel->save())
			{
				throw new Exception(json_encode($vendorModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}


			$updateModel = Vendors::model()->findByPk($vendorModel->vnd_id);

			$codeArray					 = Filter::getCodeById($updateModel->vnd_id, 'vendor'); //Vendor Code
			$updateModel->vnd_code		 = $codeArray["code"];
			$updateModel->vnd_ref_code	 = $updateModel->vnd_id;
			$updateModel->vnd_name		 = $updateModel->vnd_name . "_" . $codeArray["code"];

			if (!$updateModel->save())
			{
				throw new Exception(json_encode($updateModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}


			$zoneData = Zones::model()->getNearestZonebyCity($requestData->cityId, 60);
			if (!$zoneData)
			{
				$zoneData = [];
			}

			VendorPref::addVendorPref($updateModel->vnd_id, $zoneData, $requestData->carOwnCount);

//Vendor Stat
			$vendorStat					 = new VendorStats();
			$vendorStat->vrs_vnd_id		 = $updateModel->vnd_id;
			$vendorStat->vrs_platform	 = $requestData->platform;
			$vendorStat->save();

//Create vendor profile
			$model = Vendors::model()->findByPk($vendorModel->vnd_id);
			if ($model->vnd_contact_id)
			{
				ContactProfile::setProfile($model->vnd_contact_id, UserInfo::TYPE_VENDOR);
			}

			/**
			 * Send verification OTP and SMS
			 */
			if ($isNewFlag)
			{
//Send phone verification OTP
//$phoneNo = ContactPhone::model()->getContactPhoneById($contactId);
//$contactPhone = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNo);
				$isOtpSend = Contact::sendVerification($phoneData['number'], Contact::TYPE_PHONE, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_VENDOR, 0, $phoneData['otp'], $phoneData['ext']);

//Send email verification link

				$contactModel = ContactEmail::model()->findEmailIdByEmail($requestData->emailId);
				if (!$contactModel->eml_is_verified)
				{
					$isEmailSend = Contact::sendVerification($requestData->emailId, Contact::TYPE_EMAIL, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_VENDOR);
				}

				$socialUserId	 = UserInfo::getUserId();
				$userModel		 = Users::model()->findByPk($socialUserId);
				if (!empty($userModel))
				{
					$userModel->usr_contact_id = $contactId;
					if ($userModel->save())
					{
						ContactProfile::setProfile($contactId, UserInfo::TYPE_CONSUMER);
					}
				}
			}

			$returnset->setStatus(true);
			$returnset->setData($contactId, false);
			$returnset->setMessage("Congratulations!. Your account is created successfully, we have sent a verification link to your mail and mobile number.Please verify it");
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnset->setException($ex);
		}

		skipAll:
		return $returnset;
	}

	/**
	 * This function is used for adding vendor from OPS App
	 * @param type $contactId
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function add($contactId, $name, $isDco, $cityId, $regPlatform = '')
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($contactId))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

//Create new vendor
			$vendorModel					 = new Vendors();
			$vendorModel->vnd_user_id		 = null;
			$vendorModel->vnd_contact_id	 = $contactId;
			$vendorModel->vnd_name			 = $name;
			$vendorModel->vnd_cat_type		 = 2; //Vendor
			$vendorModel->vnd_is_dco		 = $isDco;
			$vendorModel->city				 = $cityId;
			$vendorModel->vnd_tnc			 = 1;
			$vendorModel->vnd_tnc_id		 = 6;
			$vendorModel->vnd_tnc_datetime	 = new CDbExpression('NOW()');

			if ($regPlatform != '')
			{
				$vendorModel->vnd_registered_platform = $regPlatform;
			}
			if ($isDco)
			{
				$vendorModel->vnd_cat_type = 1;
			}
			if ($vendorModel->scenario == 'insert')
			{
				$vendorModel->vnd_active = 3;
			}
			$active					 = ($vendorModel->vnd_active > 0) ? $vendorModel->vnd_active : 3;
			$vendorModel->vnd_active = $active; //Save in pending for approval state

			if (!$vendorModel->save())
			{
				throw new Exception(json_encode($vendorModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$updateModel = Vendors::model()->findByPk($vendorModel->vnd_id);

			$codeArray					 = Filter::getCodeById($updateModel->vnd_id, 'vendor'); //Vendor Code
			$updateModel->vnd_code		 = $codeArray["code"];
			$updateModel->vnd_ref_code	 = $updateModel->vnd_id;
			$updateModel->vnd_name		 = str_replace(' ', '', $updateModel->vnd_name . "_" . $codeArray["code"]);

			if (!$updateModel->save())
			{
				throw new Exception(json_encode($updateModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$zoneData = null;
			if ($cityId > 0)
			{
				$zoneData = Zones::model()->getNearestZonebyCity($cityId);
			}
			if (empty($zoneData))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_VALIDATION);
			}
			if (!VendorPref::model()->getByVendorId($updateModel->vnd_id))
			{
				VendorPref::addVendorPref($updateModel->vnd_id, $zoneData, 0);
			}
			if (!VendorStats::model()->getByVendorId($updateModel->vnd_id))
			{
				$model				 = new VendorStats();
				$model->vrs_vnd_id	 = $updateModel->vnd_id;
				$model->save();
			}
			$returnSet->setStatus(true);
			$returnSet->setData($vendorModel->vnd_id);
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $driverId
	 * @param type $vendorId
	 * @param type $url
	 * @param type $eventId
	 * @return \ReturnSet
	 */
	public function sendNotificationToVendor($bkgId, $driverId, $vendorId, $url, $eventId)
	{
		$returnSet	 = new ReturnSet();
		$drvModel	 = Drivers::model()->getById($driverId);
		$driverName	 = $drvModel->drv_name;
		$vModel		 = $this->getDetailsbyId($vendorId);
		$vendorPhone = $vModel["vnd_phone"];
		$vendorEmail = $vModel["vnd_email"];
		$vendorName	 = $vModel["vnd_owner"];
		if ($eventId == BookingTrack::SOS_START)
		{
			$msg = "$driverName has pressed panic button and wants to notify you of the emergency. Track their location at $url urgently contact them. Gozo is also taking action.";
		}
		else
		{
			$msg = "PANIC Situation resolved.Track $driverName current location at $url";
		}
		$msgCom		 = new smsWrapper();
		$sendSms	 = $msgCom->sendSmsToEmergencyContact($bkgId, $vendorPhone, $msg);
		$emailModel	 = new emailWrapper();
		$sendEmail	 = $emailModel->sendEmailToEmergencyContact($bkgId, $driverName, $vendorName, $vendorEmail, $msg, 1);
		if (!empty($sendSms) && !empty($sendEmail))
		{
			$returnSet->setStatus(true);
			$returnSet->setMessage($msg);
		}
		else
		{
			$returnSet->setStatus(false);
		}

		return $returnSet;
	}

	public static function delVendor($vndId, $canReason, $canReasonOther = '')
	{
		$success = false;
		$message = "Error deleting vendor.";
		if ($vndId > 0 && $canReason != '')
		{
			$vndRow = VendorStats::model()->getBookingByVendorID($vndId);
			if ($vndRow['coutBooking'] > 0 || $vndRow['coutTrans'] > 0)
			{

				$success = false;
				$message = "Vendor has current booking or pending payment";
				goto result;
			}
			$model						 = Vendors::model()->findByPk($vndId);
			$model->vnd_delete_reason	 = $canReason;
			$model->vnd_delete_other	 = ($canReasonOther != '') ? $canReasonOther : NULL;
			$model->vnd_active			 = 0;
			if ($model->save())
			{
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor deleted successfully.", $userInfo, VendorsLog::VENDOR_DELETED);
				$success	 = true;
				$message	 = "Vendor deleted successfully.";
			}
		}
		result:
		return ['success' => $success, 'message' => $message];
	}

	public static function rejectVendor($vndId, $canReason, $canReasonOther = '')
	{
		$success = false;
		$message = "Error rejecting vendor.";
		if ($vndId > 0 && $canReason != '')
		{
			$vndRow = VendorStats::model()->getBookingByVendorID($vndId);
			if ($vndRow['coutBooking'] > 0 || $vndRow['coutTrans'] > 0)
			{

				$success = false;
				$message = "Vendor has current booking or pending payment";
				goto result;
			}
			$model						 = Vendors::model()->findByPk($vndId);
			$model->vnd_delete_reason	 = $canReason;
			$model->vnd_delete_other	 = ($canReasonOther != '') ? $canReasonOther : NULL;
			$model->vnd_active			 = 2;
			if ($model->save())
			{
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor rejected successfully.", $userInfo, VendorsLog::VENDOR_DELETED);
				$success	 = true;
				$message	 = "Vendor rejected successfully.";
			}
		}
		result:
		return ['success' => $success, 'message' => $message];
	}

	public static function revertVendor($vndId, $canReasonOther = '')
	{
		$success = false;
		$message = "Error reverting vendor.";
		if ($vndId > 0 && $canReasonOther != '')
		{
			$vndRow = VendorStats::model()->getBookingByVendorID($vndId);
			if ($vndRow['coutBooking'] > 0 || $vndRow['coutTrans'] > 0)
			{

				$success = false;
				$message = "Vendor has current booking or pending payment";
				goto result;
			}
			$model					 = Vendors::model()->findByPk($vndId);
			$model->vnd_delete_other = ($canReasonOther != '') ? $canReasonOther : NULL;
			$model->vnd_active		 = 3;
			if ($model->save())
			{
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor reverted successfully.", $userInfo, VendorsLog::VENDOR_APPROVE);
				$success	 = true;
				$message	 = "Vendor reverted successfully.";
			}
		}
		result:
		return ['success' => $success, 'message' => $message];
	}

	/**
	 * 
	 * @param type $primaryContactId
	 * @param type $duplicateContactId
	 * @throws Exception
	 */
	public function mergeConIds($primaryContactId, $duplicateContactId, $source = null)
	{
		$userInfo = UserInfo::getInstance();
		if (empty($primaryContactId) || empty($duplicateContactId))
		{
			throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}

		$sql				 = "SELECT * FROM `vendors` WHERE `vnd_contact_id` =:id";
		$arrDupVendorData	 = DBUtil::command($sql, DBUtil::MDB())->query(['id' => $duplicateContactId]);

		if (!empty($arrDupVendorData))
		{
			foreach ($arrDupVendorData as $vndData)
			{
				$vndId = $vndData["vnd_id"];

				$updateDuplicate = "	UPDATE `vendors` 
					SET    `vnd_contact_id` = $primaryContactId
					WHERE  vnd_contact_id = $duplicateContactId AND vnd_id = $vndId";

				$numrows = DBUtil::command($updateDuplicate)->execute();

				ContactMerged::updateReferenceIds($primaryContactId, $duplicateContactId, ContactMerged::TYPE_VENDOR, $vndId);

				$docType = "";
				if ($source == Document::Document_Licence)
				{
					$docType = "(Driving License matched)";
				}
				$message = "Contacts merged - Old Contact ID: $duplicateContactId, New Contact Id: $primaryContactId. $docType";
				VendorsLog::model()->createLog($vndId, $message, $userInfo, VendorsLog::VENDOR_MODIFIED, false, false);
			}
		}
	}

	public function mergedVendorId($vendorId = "")
	{
		if ($vendorId != "")
		{
			$sql				 = "WITH RECURSIVE tree (vnd_ref_code,vnd_id,level) AS 
					(
						SELECT vnd_ref_code,vnd_id, 1 AS level FROM vendors WHERE vnd_id = :vendorId
						UNION ALL
						SELECT lpc.vnd_ref_code,lpc.vnd_id,t.level + 1 FROM vendors lpc
						INNER JOIN tree t ON t.vnd_ref_code = lpc.vnd_id
					)
					SELECT vnd_ref_code,level FROM tree WHERE 1 GROUP BY vnd_ref_code ORDER BY level DESC LIMIT 1";
			$vendorIdsDetails	 = DBUtil::queryRow($sql, DBUtil::SDB(), ['vendorId' => $vendorId]);
			return Vendors::model()->findByPk($vendorIdsDetails['vnd_ref_code']);
		}
		else
		{
			return;
		}
	}

	public static function updateTier($odometer, $sccId, $maxAge = 15)
	{
		$cond	 = "";
		$params	 = [];
		if ($sccId > 1)
		{
			$cond .= " AND vhc.vhc_has_cng != 1 ";
		}
//		$cond	 .= " AND (YEAR(CURDATE())- vhc.vhc_year) <= :maxAge   AND (vhc.vhc_end_odometer < :odometerValue OR vhc.vhc_end_odometer IS NULL) AND vhc.vhc_year IS NOT NULL";
//		$params	 = ['odometerValue' => $odometer, 'maxAge' => $maxAge];

		$cond	 .= " AND (YEAR(CURDATE())- vhc.vhc_year) <= :maxAge AND vhc.vhc_year IS NOT NULL";
		$params	 = ['maxAge' => $maxAge];
		$sql	 = "SELECT 
					CONCAT(IF(vnp.vnp_is_allowed_tier IS NULL,'',CONCAT(vnp.vnp_is_allowed_tier,',')),:sccId) as allowed_tier,
					vnp.vnp_id as id from vendors vnd
					INNER JOIN  vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id AND vnd.vnd_active = 1
					INNER JOIN  vendor_vehicle vvhc ON vvhc.vvhc_vnd_id = vnd.vnd_id AND vvhc.vvhc_active = 1
					INNER JOIN  vehicles vhc ON vhc.vhc_id = vvhc.vvhc_vhc_id AND vhc.vhc_approved = 1
					WHERE 1 AND (NOT FIND_IN_SET(:sccId, vnp.vnp_is_allowed_tier) OR vnp.vnp_is_allowed_tier IS NULL) $cond
					GROUP BY vnp.vnp_id";
		$row	 = DBUtil::query($sql, DBUtil::SDB(), ['sccId' => $sccId] + $params);
		foreach ($row as $value)
		{
			try
			{
#echo $value['allowed_tier'].'------------'.$value['id'].'/n/n';
#$param		 = array('allowed_tier' => $value['allowed_tier'], 'id' => $value['id']);
				$sqlUpdate = "UPDATE vendor_pref SET vnp_is_allowed_tier = '" . $value['allowed_tier'] . "' WHERE vendor_pref.vnp_id=" . $value['id'] . "";

				DBUtil::execute($sqlUpdate, $param);
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

	public function updateIsAllowedTier()
	{
		$query	 = "update vendor_pref vnp SET vnp.vnp_is_allowed_tier = NULL where vnp.vnp_is_allowed_tier IS NOT NULL";
		$result	 = DBUtil::command($query)->execute();
	}

	public function getVendorCountByTier($type = '')
	{
		$sql = "";

		$sqlCount = " SELECT *
								FROM (SELECT   a.*, sum(a.value) AS valuetier, 
									sum(a.valuePlus) AS valueplustier, sum(a.plus) AS plustier, sum(a.sltTier) AS selecttier
								FROM (SELECT   vnd.vnd_id, zones.zon_id, vnp.vnp_home_zone, zones.zon_name, zones.zon_lat, zones.zon_long, zon_region,
											SUM(IF(vnp.vnp_is_allowed_tier LIKE '%1%', 1, 0)) AS value, 
											SUM(IF(vnp.vnp_is_allowed_tier LIKE '%2%', 1, 0)) AS valuePlus, 
											SUM(IF(vnp.vnp_is_allowed_tier LIKE '%3%', 1, 0)) AS plus, 
											SUM(IF(vnp.vnp_is_allowed_tier LIKE '%4%', 1, 0)) AS sltTier
											FROM vendors vnd
												INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
												INNER JOIN zones ON zones.zon_id = vnp.vnp_home_zone
										  GROUP BY vnd.vnd_id) a
								GROUP BY a.zon_id) vndr
							   INNER JOIN
							   (SELECT  SUM(IF(vhc_is_allowed_tier LIKE '%1%', 1, 0)) AS vehiclevalue, SUM(IF(vhc_is_allowed_tier LIKE '%2%', 1, 0)) AS vehicleValuePlus,
										SUM(IF(vhc_is_allowed_tier LIKE '%3%', 1, 0)) AS vehiclePlus, SUM(IF(vhc_is_allowed_tier LIKE '%4%', 1, 0)) AS vehicleSelect,          
										vnp_home_zone vhz
										FROM (SELECT   vhc_id, vnp_vnd_id, vnp_home_zone, vhc_is_allowed_tier
										    FROM vehicles
													INNER JOIN vendor_vehicle
													ON vvhc_vhc_id = vhc_id AND vvhc_active = 1 AND vhc_active = 1 AND vhc_approved = 1
												   INNER JOIN vendor_pref ON vendor_vehicle.vvhc_vnd_id = vnp_vnd_id
										  GROUP BY vnp_home_zone, vhc_id) a
									GROUP BY vnp_home_zone) vhcle
								ON vhcle.vhz = vndr.zon_id";
		if ($this->vendorPrefs->vnp_home_zone != '')
		{
			$sql .= " AND zon_id IN ({$this->vendorPrefs->vnp_home_zone})";
		}
		if ($this->zonRegion != '')
		{
			$sql .= " AND zon_region LIKE '%{$this->zonRegion}%'";
		}
		if ($type == 'Command')
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount$sql) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider("$sqlCount$sql", [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 =>
				['attributes'	 =>
					['zon_name'],
					'defaultOrder'	 => ''],
				'pagination'	 => [],
			]);
			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::queryAll("$sqlCount$sql", DBUtil::SDB());
			return $recordset;
		}
	}

	public function getDetailsByZoneID($zoneId = 0, $tierId = 0)
	{
		$sql = "Select vnd.vnd_name, vnd.vnd_code, vnp.vnp_home_zone, zon_name from vendors vnd
				INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id AND vnd_active=1
				INNER JOIN zones ON zones.zon_id = vnp.vnp_home_zone
				INNER JOIN vendor_vehicle ON  vendor_vehicle.vvhc_vnd_id = vnp_vnd_id AND vvhc_active = 1 
				INNER JOIN vehicles ON vvhc_vhc_id = vhc_id AND vhc_active = 1 AND vhc_approved = 1
				where vnp.vnp_home_zone =$zoneId AND vnp.vnp_is_allowed_tier LIKE '%$tierId%'
				AND vhc_active = 1 AND vhc_approved = 1 AND vhc_year IS NOT NULL 
                    GROUP BY vnd.vnd_id";
//return DBUtil::queryAll($sql);

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider("$sql", [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => ["pageSize" => 20],
		]);
		return $dataprovider;
	}

	public static function addByContact($contactModel, $entityId = null)
	{
		/** @var Contact $contactModel */
		$cttId = $contactModel->processData();

		$returnSet = Vendors::processData($cttId, UserInfo::TYPE_VENDOR, $entityId);
		return $returnSet;
	}

	public static function processData($contactId, $entityType, $entityId)
	{
		$returnSet = new ReturnSet();
		try
		{
			if (empty($entityId))
			{
				$entityId = UserInfo::getEntityId();
			}
			/** @var Contact $contactModel */
			$contactModel	 = Contact::model()->findByPk($contactId);
			$emailId		 = $contactModel->contactEmails[0]->eml_email_address;
			$phoneNo		 = $contactModel->contactPhones[0]->phn_phone_no;
			$name			 = ($contactModel->ctt_business_name) ? $contactModel->ctt_business_name : $contactModel->ctt_first_name . "" . $contactModel->ctt_last_name;
			/** @var ContactPhone $modelPhone */
			$modelPhone		 = ContactPhone::model()->findPhoneIdByPhoneNumber($phoneNo);

			$arrProfile = ContactProfile::getEntityById($contactId, $entityType);
			if (!empty($arrProfile["id"]))
			{
				$returnSet = ContactTemp::processData($contactModel, UserInfo::TYPE_VENDOR, $entityId);
				goto skipAll;
			}
			$response	 = Vendors::add($contactId, $name, $contactModel->isDco, $contactModel->ctt_city);
			$vndId		 = $response->getData();

			if (!$response->getStatus())
			{
				$returnSet->setMessage("Failed to create vendor");
				goto skipAll;
			}

//Create vendor profile
			ContactProfile::setProfile($contactId, UserInfo::TYPE_VENDOR);
			if ($contactModel->isDco)
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

			$isOtpSend	 = Contact::sendVerification($modelPhone->phn_phone_no, Contact::TYPE_PHONE, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_OTP, UserInfo::TYPE_VENDOR, 0, $modelPhone->phn_otp, $modelPhone->phn_phone_country_code);
			$isEmailSend = Contact::sendVerification($emailId, Contact::TYPE_EMAIL, $contactId, Contact::NEW_CON_TEMPLATE, Contact::MODE_LINK, UserInfo::TYPE_VENDOR);

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

	public function findByContactID($cttId)
	{
		$model = self::model()->findAll(array("condition" => "vnd_contact_id =$cttId"));
		return $model;
	}

	public static function getCreditLimit($vendorId = 0)
	{
		if ($vendorId > 0)
		{
			$where = " and vnd_id='$vendorId' ";
		}
		$sql = "SELECT 	vnd_id,vrs.vrs_security_amount,	IF(vrs.vrs_credit_limit IS NULL, '1000', vrs.vrs_credit_limit)  AS creditLimit			
                FROM `vendors` vnd
				JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id AND vnd_active > 0				
				WHERE 1 $where	GROUP BY vnd_id ORDER BY vnd.vnd_id DESC";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public static function getPayableList($qry = '')
	{
		if ($qry != '')
		{
			$condition = " and vnd.vnd_id  IN ($qry) ";
		}
		$sql = self::getPaymentQry($condition);

		$count			 = DBUtil::command("SELECT COUNT(1) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, array(
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 =>
				['vnd_name', 'vrs_withdrawable_balance'], 'defaultOrder'	 => 'vrs_withdrawable_balance DESC'],
			'pagination'	 => array('pageSize' => 50),
				)
		);
		return $dataprovider;
	}

	public static function getPayDetailsByIds($ids = '0')
	{
		$condition = " AND 1=2 ";
		if ($ids != '0')
		{
			$condition = " AND vnd.vnd_id  IN ($ids) ";
		}

		$sql = self::getPaymentQry($condition);

		$res = DBUtil::query($sql, DBUtil::MDB());
		return $res;
	}

	public static function getPaymentQry($condition)
	{

		$sql = "SELECT vnd.vnd_id, ( sum(adt_amount) *-1) vendor_amount,vrs_security_amount ,vrs_withdrawable_balance,
					vendor_stats.vrs_locked_amount as locked_amount,vnp_is_freeze,vnd.vnd_active,vnd.vnd_name,vnd.vnd_code,
					`ctt_bank_name` as bank_name,
                    `ctt_bank_branch` as  bank_branch,
                    `ctt_beneficiary_name` as  beneficiary_name,
                    `ctt_account_type` as  account_type,
                    `ctt_bank_ifsc` as bank_ifsc,
                    `ctt_bank_account_no` as  bank_account_no
                FROM   account_trans_details adt 
				INNER JOIN vendors v1 ON adt.adt_trans_ref_id = v1.vnd_id  
				INNER JOIN vendors vnd ON v1.vnd_id = vnd.vnd_ref_code AND vnd.vnd_active =1 
				INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id AND cpr.cr_status =1 
				 INNER JOIN contact ON contact.ctt_id = cpr.cr_contact_id AND contact.ctt_active=1 AND  contact.ctt_id = contact.ctt_ref_code 
                		AND contact.ctt_bank_account_no <> '' AND contact.ctt_bank_ifsc <> ''
						AND contact.ctt_bank_account_no IS NOT NULL AND contact.ctt_bank_ifsc IS NOT NULL
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vnd.vnd_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vnd.vnd_id
                INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id				
                WHERE  adt.adt_active = 1 AND act.act_active=1
					AND adt.adt_status = 1
					AND adt.adt_ledger_id = 14
					AND adt.adt_type = 2 AND vrs_withdrawable_balance > 0 
					AND vendor_pref.vnp_is_freeze=0 AND vnd.vnd_active=1 
					AND vnd.vnd_id NOT IN (
						SELECT  onb_payee_id FROM online_banking  where onb_payee_type=2 AND onb_status IN (0,3)
					)
					$condition
                GROUP BY vnd_id HAVING vendor_amount > 0 
				 ";
		return $sql;
	}

	public static function processPayment($vendor_id, $amount = 0, $islogable = false)
	{
		if ($amount > 0 && $islogable)
		{
			$message = "Withdrawable amount of Rs.$amount transferred to bank account";
			VendorsLog::model()->createLog($vendor_id, $message, $userInfo, VendorsLog::PAYMENT_MADE, false, false);
		}
		$vndStats	 = VendorStats::model()->getbyVendorId($vendor_id);
		$res		 = $vndStats->setLockedAmount();
	}

	public static function getVendorCancellation($date1, $date2, $type = '')
	{
		$cond = '';
		if ($date1 == '' || $date2 == '')
		{
			$date1	 = date('Y-m-01') . " 00:00:00";
			$date2	 = date("Y-m-t") . ' 23:59:59';
			$cond	 = " AND (bkg_create_date BETWEEN '{$date1}'  AND '{$date2}')";
		}
		else
		{
			$date1	 = $date1 . " 00:00:00";
			$date2	 = $date2 . " 23:59:59";
			$cond	 = " AND (bkg_create_date BETWEEN '{$date1}'  AND '{$date2}') ";
		}
		$sql = "SELECT
				booking_cab.bcb_vendor_id AS vendor_id,
				vnd_name AS vendor_name,
				COUNT(IF(booking_cab.bcb_vendor_id IS NOT NULL,1,NULL)) AS total_vendor_assigned_count,
				COUNT(IF(booking_cab.bcb_vendor_id IS NOT NULL AND bkg_status IN (6, 7),1,NULL)) AS total_vendor_served_count,
				COUNT(
					  IF(
							bcb_vendor_id IS NOT NULL            
							AND bkg_status in (9)
							AND btr_cancel_date IS NOT NULL
							AND ((btr_cancel_date>bkg_pickup_date) OR (CalcWorkingHour(btr_cancel_date,bkg_pickup_date)<4))
							AND bkg_create_date<=DATE_SUB(NOW(), INTERVAL 2 HOUR),
							1,
							NULL
					    )
					)  AS total_vendor_cancel_count
				FROM booking
				INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN vendors ON bcb_vendor_id =vnd_id 
				WHERE 1 AND booking_cab.bcb_vendor_id IS NOT NULL  $cond
				GROUP BY booking_cab.bcb_vendor_id";

		$sqlCount = "SELECT	 COUNT(*)
					FROM booking
					INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
					INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
					INNER JOIN vendors vnd ON bcb_vendor_id =vnd_id 
					WHERE 1 AND booking_cab.bcb_vendor_id IS NOT NULL  $cond
					GROUP BY booking_cab.bcb_vendor_id";
		if ($type == 'Command')
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) temp", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, array(
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['vendor_id', 'vnd_name', 'total_vendor_assigned_count', 'total_vendor_served_count', 'total_vendor_cancel_count'],
					'defaultOrder'	 => 'total_vendor_cancel_count DESC'], 'pagination'	 => array('pageSize' => 100))
			);
			return $dataprovider;
		}
		else
		{
			$recordSet = DBUtil::query($sql, DBUtil::SDB());
			return $recordSet;
		}
	}

	public function getcarModelJSON()
	{
		$sql			 = "SELECT * FROM `vehicle_types` WHERE `vht_active`=1";
		$zoneRateData	 = DBUtil::query($sql);
		$data			 = CJSON::encode($zoneRateData);
		return $data;
	}

	public static function getBookingHistoryById($vndId, $isRating = 0)
	{
		$sql1			 = "SELECT vnd_ref_code FROM vendors WHERE vnd_id = :vndid";
		$vnd_ref_code	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), ['vndid' => $vndId]);
		$sql			 = "SELECT group_concat(vnd_id) FROM vendors WHERE vnd_ref_code =:vnd_ref_code OR vnd_id = :vnd_id";
		$vendors		 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['vnd_ref_code' => $vnd_ref_code, 'vnd_id' => $vndId]);
		$vendors		 = $vendors != null ? $vendors : "-1";
		DBUtil::getINStatement($vendors, $bindString, $params);

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
                    IF(rtg.rtg_customer_review = '', 'N/A', IFNULL(rtg.rtg_customer_review, 'N/A')) bkg_customer_review
                FROM
                    booking bkg
                INNER JOIN booking_cab bcb ON
                    bkg.bkg_bcb_id = bcb.bcb_id
                INNER JOIN cities c1 ON
                    bkg.bkg_from_city_id = c1.cty_id
                INNER JOIN cities c2 ON
                    bkg.bkg_to_city_id = c2.cty_id
                INNER JOIN vehicles vhc ON
                    bcb.bcb_cab_id = vhc.vhc_id
                LEFT JOIN ratings rtg ON
                    bkg.bkg_id = rtg.rtg_booking_id
                WHERE
                    bkg.bkg_status IN(5, 6, 7) AND bcb.bcb_vendor_id IN ($bindString)";

		if ($isRating == 1)
		{
			$sql .= " AND rtg_customer_overall IS NOT NULL";
		}

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bkg_id'],
				'defaultOrder'	 => 'bkg_pickup_date DESC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	/**
	 * This function is used to block payment release based on some condition
	 * @param type $bkgId
	 * @param type $remarks
	 * @throws Exception
	 */
	public static function stopVendorPayment($bkgId, $remarks = NULL)
	{
		/**
		 * Stop vendor payment
		 */
		$model		 = Booking::model()->findByPk($bkgId);
		$tripmodel	 = BookingCab::model()->findByPk($model->bkg_bcb_id);
		if ($tripmodel->bcb_lock_vendor_payment == 0)
		{
			$tripmodel->bcb_lock_vendor_payment = 1;
			if (!$tripmodel->save())
			{
				throw new Exception($tripmodel->hasErrors(), ReturnSet::ERROR_INVALID_DATA);
			}
			$params['blg_ref_id'] = $bkgId;
			BookingLog::model()->createLog($bkgId, $remarks, UserInfo::model(), BookingLog::LOCKED_PAYMENT, false, $params);
		}
	}

	/**
	 * This function is used for getting all vendor for auto approval in service call queue
	 * @return queryObject array
	 */
	public function getAllVendorApproval()
	{
		$sql = " SELECT   vnd_id,ctt_id, vnd_name  FROM `vendors`
				JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vendors.vnd_id AND cpr.cr_status =1
				JOIN  `contact`  ON contact.ctt_id = cpr.cr_contact_id and ctt_active=1
				JOIN  `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id
				LEFT JOIN `vendor_agreement` ON vendor_agreement.vag_vnd_id = vendors.vnd_id
				WHERE  1 AND vendors.vnd_active IN (3,4)  GROUP BY vendors.vnd_id  ORDER BY vendor_agreement.vag_digital_date DESC, vendor_stats.vrs_docs_r4a DESC LIMIT 0,50";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * This function is used for  manual assignment for vendor from admin panel
	 * @return type dataprovider
	 */
	public function manualAssignVendorList($bkgId)
	{
		$model			 = Booking::model()->findByPk($bkgId);
		$bcbVendorAmount = $model->bkgBcb->bcb_vendor_amount;
		$bkgTotalAmount	 = $model->bkgInvoice->bkg_total_amount;
		$bkgServiceTax	 = $model->bkgInvoice->bkg_service_tax;
		$bkgBaseAmount	 = $model->bkgInvoice->bkg_base_amount;
		$agtType		 = $model->bkgAgent->agt_type;
		$agtCommission	 = $model->bkgAgent->agt_commission;
		$maxVendorAmount = ($bkgTotalAmount - $bkgServiceTax - ($agtType == 2 ? round($bkgBaseAmount * $agtCommission * 0.01) : 0));
		$bcbId			 = $model->bkgBcb->bcb_id;
		$fromCity		 = $model->bkg_from_city_id;
		$toCity			 = $model->bkg_to_city_id;
		$pickupDate		 = $model->bkg_pickup_date;
		$homeZones		 = $zones			 = ZoneCities::model()->findZoneByCityes($fromCity . "," . $toCity);
		$cabTypes		 = SvcClassVhcCat::getCatIdBySvcid($model->bkg_vehicle_type_id);

// Bidded Vendors
		$strBiddedVendorIds = '';
//		$biddedVendorIds	 = BookingVendorRequest::getVendorIdsbyBcbId($bcbId);
//		if ($biddedVendorIds)
//		{
//			$strBiddedVendorIds = " OR vendors.vnd_id IN ($biddedVendorIds) ";
//		}

		$params		 = [
			'bcbVendorAmount'	 => (int) $bcbVendorAmount,
			'maxVendorAmount'	 => (int) $maxVendorAmount,
			'bcbId'				 => (int) $bcbId,
			'fromCity'			 => (int) $fromCity,
			'toCity'			 => (int) $toCity,
			'pickupDate'		 => $pickupDate,
			'zones'				 => $zones,
			'homeZones'			 => $homeZones,
			'cabTypes'			 => (int) $cabTypes
		];
		$paramsCount = [
			'bcbId'		 => (int) $bcbId,
			'fromCity'	 => (int) $fromCity,
			'toCity'	 => (int) $toCity,
			'pickupDate' => $pickupDate,
			'zones'		 => $zones,
			'homeZones'	 => $homeZones,
			'cabTypes'	 => (int) $cabTypes
		];

		$select = 'SELECT 
				vendors.vnd_id, vendors.vnd_name,
				IFNULL(bvr.bvr_bid_amount, 0) AS bidAmount,
				IF(bvr.bvr_bid_amount IS NOT NULL AND bvr.bvr_bid_amount>0,1,0) AS bidFlag,
				MAX(CalculateSMT( :maxVendorAmount, :bcbVendorAmount,
			   IFNULL( bvr.bvr_bid_amount,
					IF( cav.cav_total_amount IS NOT NULL
						AND vhc.vhc_id IS NOT NULL, cav.cav_total_amount, :bcbVendorAmount)),
					vrs.vrs_vnd_overall_rating, vrs.vrs_sticky_score,
					vrs.vrs_penalty_count, vrs.vrs_driver_app_used,
					vrs.vrs_dependency, vrs.vrs_boost_percentage))  AS smtScore,
				 IF(FIND_IN_SET(vendor_pref.vnp_home_zone, :zones), 1, 0) AS homeZoneScore,
				 IF( CONCAT(",", IFNULL(vendor_pref.vnp_accepted_zone, "0"), ",") REGEXP CONCAT(",(", REPLACE(:zones,",", "|"), "),"),1,0) AS acceptedZone,
				 cav.cav_id, IF(cav.cav_id IS NULL, 0, 1) AS cavScore,
				 vrs.vrs_vnd_overall_rating as vnd_overall_rating,
				 vrs.vrs_dependency as dependencyScore,
				 bvr.bvr_bid_amount, bvr.bvr_created_at,
				 vendors.vnd_active,
				 vnp_is_freeze as vnd_is_freeze,
				 IF(bvr.bvr_accepted=1 AND bvr.bvr_bid_amount > 0 AND bvr.bvr_bid_amount IS NOT NULL ,1,IF(bvr.bvr_accepted=2,-1,0)) AS bidding,
				 IF((vendors.vnd_active IN(2, 3) OR vnp_is_freeze != 0),1,0) vnd_forbidden';

		$selectCount = " SELECT  DISTINCT vendors.vnd_id ";
		$orderby	 = " ORDER BY bidAmount ASC,smtScore DESC,cavScore DESC, homeZoneScore DESC ";
		$groupby	 = " GROUP BY vendors.vnd_ref_code ";

		$sql = ' FROM vendors v1
				INNER JOIN vendors ON vendors.vnd_id = v1.vnd_ref_code
				INNER JOIN vendor_pref  ON vendor_pref.vnp_vnd_id = vendors.vnd_id AND vendors.vnd_active = 1
				INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vendors.vnd_id
				LEFT JOIN booking_vendor_request bvr ON     bvr.bvr_vendor_id = vendors.vnd_id AND bvr.bvr_active = 1 AND bvr.bvr_bcb_id = :bcbId
				LEFT JOIN cab_availabilities cav  ON     cav.cav_vendor_id = vendors.vnd_id AND cav.cav_from_city = :fromCity   AND FIND_IN_SET(:toCity, cav.cav_to_cities) AND :pickupDate BETWEEN cav.cav_date_time AND DATE_ADD(cav.cav_date_time, INTERVAL cav.cav_duration MINUTE)
				LEFT JOIN vehicles vhc  ON     vhc.vhc_id = cav.cav_cab_id  AND FIND_IN_SET(vhc.vhc_type_id, :cabTypes)
				WHERE 1 AND					 
					((bvr_bid_amount>0 AND bvr_accepted=1) OR
						(
						FIND_IN_SET(vendor_pref.vnp_home_zone, :homeZones)
						OR 
						CONCAT(",", IFNULL(vendor_pref.vnp_accepted_zone, "0"), ",") REGEXP CONCAT(",(", REPLACE( :zones, ",", "|"), "),")
					) 
					AND NOT CONCAT(",", IFNULL(vendor_pref.vnp_excluded_cities,  "0"), ",") REGEXP CONCAT(",(", :fromCity,"|", :toCity, "),")
					)
				';

		$includeBlocked	 = ($this->vndIsBlocked == 1) ? true : false;
		$sql			 .= " AND (v1.vnd_active=1";
		if ($includeBlocked)
		{
			$sql .= " OR v1.vnd_active=2";
		}

		$includeUnapproved = ($this->vndUnApproved == 1) ? true : false;
		if ($includeUnapproved)
		{
			$sql .= " OR (v1.vnd_active=3 AND v1.vnd_create_date >= DATE_SUB(NOW(), INTERVAL +36 HOUR))";
		}
		$sql .= ") ";

		$includeFreezed = ($this->vndIsFreezed == 1) ? true : false;
		if ($includeFreezed)
		{
			$sql .= " AND  vnp_is_freeze >= 0";
		}
		else
		{
			$sql .= " AND  vnp_is_freeze=0";
		}

		if ($this->vnd_name != '')
		{
			$sql .= " AND v1.vnd_name LIKE '%$this->vnd_name%'";
		}

		if ($this->vnd_phone != '')
		{
			$vendorIds	 = Vendors::getVendorIds($this->vnd_phone);
			$sql		 .= " AND v1.vnd_id IN ($vendorIds) ";
		}
		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($selectCount $sql) a", DBUtil::SDB(), $paramsCount);
		$dataprovider	 = new CSqlDataProvider("$select$sql$groupby", [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['vnd_name', 'vnd_overall_score', 'smtScore', 'cavScore', 'bvr_bid_amount', 'dependencyScore'],
				'defaultOrder'	 => 'bidFlag DESC,bidAmount ASC,smtScore DESC,cavScore DESC,homeZoneScore DESC'
			], 'pagination'	 => ['pageSize' => 100]
		]);

		return $dataprovider;
	}

	/**
	 * This function is used for all the vendor based on their phone number
	 * @param int $phone
	 * @return type string
	 */
	public static function getVendorIds($phone)
	{
		$sql		 = "SELECT GROUP_CONCAT(vnd_id) FROM vendors
						INNER JOIN  contact_profile ON vnd_id=cr_is_vendor AND  cr_status=1
						INNER JOIN  contact_phone ON cr_contact_id =phn_contact_id AND  cr_status=1
                        WHERE `phn_phone_no`  like '%$phone%'";
		return $contactAll	 = DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	/**
	 * @return array()|false Return linked Vendor ID or false if not found
	 */
	public static function getByEmailPhone($email, $phone)
	{
		$params	 = ["email" => $email, "phone" => $phone];
		$sql	 = "SELECT IFNULL(v11.vnd_id, v01.vnd_id) as vndId, c1.ctt_id, ce.eml_is_verified, cph.phn_is_verified,
						IF(v11.vnd_id IS NOT NULL, 2, IF(v01.vnd_id IS NOT NULL,1,0)) as rank
				FROM contact c
				INNER JOIN contact_email ce ON c.ctt_id=ce.eml_contact_id AND ce.eml_email_address=:email AND ce.eml_active=1
				INNER JOIN contact_phone cph ON c.ctt_id=cph.phn_contact_id AND cph.phn_phone_no=:phone AND cph.phn_active=1
				INNER JOIN contact c1 ON c1.ctt_id=c.ctt_ref_code AND c.ctt_active=1 AND c1.ctt_active=1
				INNER JOIN contact_profile cp1 ON cp1.cr_contact_id=c1.ctt_id
				INNER JOIN contact_profile cp ON cp.cr_contact_id=c.ctt_id
				LEFT JOIN vendors v1 ON v1.vnd_id=cp1.cr_is_vendor AND v1.vnd_active IN (1,3,4)
				LEFT JOIN vendors v11 ON v11.vnd_id=v1.vnd_ref_code AND v11.vnd_active IN (1,3,4)
				LEFT JOIN vendors v ON v.vnd_id=cp.cr_is_vendor  AND v.vnd_active IN (1,3,4)
				LEFT JOIN vendors v01 ON v01.vnd_id=v.vnd_ref_code AND v01.vnd_active IN (1,3,4)
				WHERE v11.vnd_id IS NOT NULL OR v01.vnd_id IS NOT NULL
				ORDER BY ce.eml_is_verified DESC, cph.phn_is_verified DESC, rank DESC";

		$row = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	/**
	 * unlink vendor
	 * @param type $vendorId
	 * @param type $userId*
	 */
	public static function unlinkUser($vendorId, $userId)
	{
		$updateData = "UPDATE Vendors SET 	vnd_user_id = 0 WHERE vnd_id = $vendorId AND vnd_user_id=" . $userId;

		DBUtil::command($updateData)->execute();
	}

	/**
	 * This function is used for getting Driver App Usage Details
	 * @param integer $arr 
	 * @return dataprovider
	 */
	public static function getVendorusageDetails($arr = [], $command = DBUtil::ReturnType_Provider)
	{
		$where = '';
		if ($arr['bkg_pickup_date1'] != '' && $arr['bkg_pickup_date2'] != '')
		{
			$fromDate	 = $arr['bkg_pickup_date1'];
			$toDate		 = $arr['bkg_pickup_date2'];
			$where		 .= " AND bkg_pickup_date>= '" . $fromDate . "' AND bkg_pickup_date < '" . $toDate . "'";
		}
		if ($arr['bcb_vendor_id'] != '' && $arr['bcb_vendor_id'] != '0')
		{
			$where		 .= "  AND bcb_vendor_id=" . $arr['bcb_vendor_id'];
			$agtwhere	 = "  AND bcb_vendor_id=" . $arr['bcb_vendor_id'];
		}

		$sql = "SELECT v.vnd_id,v.vnd_name,v.vnd_code,COUNT(*),DATE_FORMAT(bkg_pickup_date,'%d-%m-%Y') date,
								GROUP_CONCAT(DISTINCT IF(apt.apt_id IS NULL AND btl.btl_bkg_id IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_loggedin,
								GROUP_CONCAT(DISTINCT IF(apt.apt_id IS NOT NULL AND btl.btl_bkg_id IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_left,
								GROUP_CONCAT(DISTINCT IF(btl.btl_bkg_id IS NOT NULL AND bkg_trip_arrive_time IS NULL, bkg_id, NULL) SEPARATOR ', ') as not_arrived,
								GROUP_CONCAT(DISTINCT IF(bkg_trip_arrive_time IS NOT NULL AND bkg_trip_start_time IS NULL , bkg_id, NULL) SEPARATOR ', ') as not_started,
								GROUP_CONCAT(DISTINCT IF(bkg_trip_start_time IS NOT NULL AND bkg_trip_end_time IS NOT NULL , bkg_id, NULL) SEPARATOR ', ') as not_ended,
								CONCAT(DATE_FORMAT(MIN(bkg_pickup_date),'%d-%m-%Y'), ' - ', DATE_FORMAT(MAX(bkg_pickup_date),'%d-%m-%Y')) as date_range,
								COUNT(DISTINCT bkg_id) booking_count,
								COUNT(DISTINCT IF(apt.apt_id IS NULL, bkg_id, NULL)) as not_loggedin_count,
								COUNT(DISTINCT IF(btl.btl_bkg_id IS NULL, bkg_id, NULL)) as left_count,
								COUNT(DISTINCT IF(bkg_trip_arrive_time IS NULL, bkg_id, NULL)) as arrived_count,
								COUNT(DISTINCT IF(bkg_trip_start_time IS NULL, bkg_id, NULL)) as start_count,
								COUNT(DISTINCT IF(bkg_trip_end_time IS NULL, bkg_id, NULL)) as end_count,
								ROUND(COUNT(DISTINCT IF(bkg_trip_arrive_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) as arrived_percent,
								ROUND(COUNT(DISTINCT IF(bkg_trip_start_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) as start_percent,
								ROUND(COUNT(DISTINCT IF(bkg_trip_end_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) as end_percent
								FROM booking AS b 
								INNER JOIN booking_track btk ON b.bkg_id = btk.btk_bkg_id
								INNER JOIN booking_cab AS bcb ON bcb.bcb_id=b.bkg_bcb_id
								INNER JOIN vendors AS v ON bcb.bcb_vendor_id = v.vnd_id 
								INNER JOIN vendors AS v1 ON v.vnd_id = v1.vnd_ref_code
								LEFT JOIN app_tokens AS apt ON apt.apt_entity_id=v.vnd_id AND apt.apt_last_login>DATE_SUB(b.bkg_pickup_date, INTERVAL 2 HOUR) AND apt.apt_status=1
								LEFT JOIN booking_track_log btl ON b.bkg_id = btl.btl_bkg_id AND btl.btl_event_type_id=201
								WHERE bkg_status IN (5,6,7)  " . $where . "
								GROUP BY DATE,v.vnd_ref_code
								ORDER BY v.vnd_ref_code 
							";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes' => ['date', 'date_range'], 'defaultOrder' => 'bkg_pickup_date DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	/**
	 * 
	 * @param int $fcity
	 * @param int $vehicleTypeId
	 * @return CDbDataReader
	 */
	public static function getByPickupCitynCabType($fcity, $tcity, $cabTypeList, $excludeVendors = '', $distance = 200, $onlyGozoNow = false)
	{
		$homeZone	 = ZoneCities::getZonesByCity($fcity);
		$tcityZone	 = ZoneCities::getZonesByCity($tcity);

		$acceptedZones	 = Zones::getServiceZoneList($homeZone, $distance);
		$hzoneArr		 = explode(',', $homeZone);
		$tzoneArr		 = explode(',', $tcityZone);
		$acceptedZoneArr = explode(',', $acceptedZones);
		$mergeZoneArr	 = array_unique(array_merge($hzoneArr, $acceptedZoneArr));

		$allZone = implode(',', $mergeZoneArr);
		$data	 = Vendors::getByZonesnCabType($homeZone, $tcityZone, $allZone, $cabTypeList, $excludeVendors, $onlyGozoNow);
		return $data;
	}

	/**
	 * 
	 * @param string $zones
	 * @param int $vehicleTypeId
	 * @return CDbDataReader
	 */
	public static function getByZonesnCabType($homeZone, $tcityZone, $allZone, $cabTypeList, $excludeVendors = '', $onlyGozoNow = false)
	{

		$params	 = ['homeZone' => $homeZone, 'tcityZone' => $tcityZone, 'allZone' => $allZone, 'cabTypeList' => $cabTypeList];
		$where	 = '';
		if ($excludeVendors != '')
		{
			$params['excludeVendors']	 = $excludeVendors;
			$where						 = " AND v2.vnd_id NOT IN($excludeVendors)";
		}
		if ($onlyGozoNow)
		{
			$where .= " AND vnp.vnp_gozonow_enabled <2";
		}

		$sql = "SELECT DISTINCT v2.vnd_id, v2.vnd_name, vrs.vrs_trust_score,  
			contact_phone.phn_phone_no AS vnd_phone,
			vnp_home_zone, vrs.vrs_last_logged_in, 
			IF(v2.vnd_create_date > DATE_SUB(NOW(),INTERVAL 3 MONTH), 40, 0) AS joinScore,
			IF(FIND_IN_SET(vnp_home_zone, :homeZone), 40, IF(FIND_IN_SET(vnp_home_zone, :tcityZone), 20, 0)) AS zoneScore,
			IF(FIND_IN_SET(vnp_home_zone, :homeZone), 1, IF(FIND_IN_SET(vnp_home_zone, :tcityZone), 2, 3)) AS isHomeZone,
			IF(FIND_IN_SET(vhc.vhc_type_id,:cabTypeList), 20, 0) AS cabTypeScore,
			CASE  						
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 40
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 3 MONTH) THEN 25
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 6 MONTH) THEN 20
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 9 MONTH) THEN 15
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 12 MONTH) THEN 10
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 18 MONTH) THEN 5
			   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 24 MONTH) THEN 1
			   ELSE 0
			 END  AS loginScore
			FROM vendors v1
			INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code AND v2.vnd_id = v2.vnd_ref_code
			INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = v2.vnd_id AND vnp.vnp_gnow_status=1
			INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = v2.vnd_id
			LEFT JOIN vendor_vehicle vvhc ON vvhc.vvhc_vnd_id = v2.vnd_id
			INNER JOIN vehicles vhc ON vhc.vhc_id = vvhc.vvhc_vhc_id		
			INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = v2.vnd_id
			INNER JOIN contact_phone ON contact_phone.phn_contact_id = cpr.cr_contact_id
				   AND contact_phone.phn_is_verified = 1 AND contact_phone.phn_is_primary = 1 				 
			WHERE v2.vnd_active = 1 AND v2.vnd_id = v2.vnd_ref_code 
				AND vnp.vnp_manual_freeze = 0
				AND (FIND_IN_SET(vnp_home_zone, :allZone))
				$where 						 
				AND ((vrs.vrs_last_logged_in IS NULL AND v2.vnd_create_date > DATE_SUB(NOW(),INTERVAL 12 MONTH))  
						OR vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 2 YEAR))							 
			GROUP BY v2.vnd_ref_code
			ORDER BY (joinScore + cabTypeScore + loginScore + zoneScore + (vrs.vrs_trust_score*2)) DESC LIMIT 0, 300		 
				";

		$data = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param type $code
	 * @return type
	 */
	public static function getVndIdByCode($code)
	{
		$find = "-";
		if (!preg_match("/{$find}/i", $code))
		{
			$code = substr_replace($code, '-', 1, 0);
		}
		$sql = "SELECT vnd_id FROM `vendors` WHERE `vnd_code` = '" . $code . "'";

		return $vndId = DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public static function amountPayableToGozo_OLD()
	{
		$sql			 = "SELECT                 
							vnd_code,
							GROUP_CONCAT(bcb_id SEPARATOR ', ') as tripId,   
							vnd_name,
							bkg_pickup_date,
							SUM(biv.bkg_net_advance_amount) AS advanceAmount,
							SUM(biv.bkg_total_amount) AS totalAmount,
							SUM(biv.bkg_total_amount-biv.bkg_net_advance_amount) AS amountToCollect,   
							SUM(booking_cab.bcb_vendor_amount) AS tripVendorAmount,
							vendor_stats.vrs_outstanding AS outstanding_balance,
							SUM(biv.bkg_total_amount-biv.bkg_net_advance_amount) - SUM(booking_cab.bcb_vendor_amount)  + vrs_outstanding AS vendorDue
							FROM   booking_cab
							INNER JOIN booking
							ON booking_cab.bcb_id = booking.bkg_bcb_id
							INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=booking.bkg_id
							INNER JOIN vendors ON vendors.vnd_id = booking_cab.bcb_vendor_id AND vendors.vnd_id = vendors.vnd_ref_code
							INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
							WHERE  bkg_status IN(3,5) 
							AND bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR) AND booking_cab.bcb_active=1
							GROUP BY bcb_vendor_id HAVING vendorDue > 0";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => [
				'defaultOrder' => 'bkg_pickup_date ASC'],
		]);
		return $dataprovider;
	}

	/**
	 * This function is used to calculate net payable vendor amount to G0Z0
	 * @return \CSqlDataProvider
	 */
	public static function amountPayableToGozo()
	{
		$sql = "SELECT bcb_vendor_id, vnd_name, vnd_code, GROUP_CONCAT(bcb_ids SEPARATOR ', ') as bcbIds,
			SUM(totalAmount) AS totalAmount, 
			SUM(advanceAmount) AS advanceAmount, 
			SUM(amountToCollect) AS amountToCollect, 
			SUM(tripVendorAmount) AS tripVendorAmount, 
			(SUM(tripVendorAmount) - SUM(amountToCollect)) vndNetEffect, 
			(SUM(bcbVndNetEffect) + outstanding_balance) AS vendorToGozo,
			outstanding_balance  
			FROM 
			(
				SELECT GROUP_CONCAT(bcb_id SEPARATOR ', ') as bcb_ids, bcb_bkg_id1, bcb_vendor_id, vnd_name, vnd_code,  
				SUM(biv.bkg_total_amount) AS totalAmount,
				SUM(biv.bkg_net_advance_amount) AS advanceAmount,
				SUM(biv.bkg_total_amount - biv.bkg_net_advance_amount) AS amountToCollect,   
				booking_cab.bcb_vendor_amount AS tripVendorAmount,
				((booking_cab.bcb_vendor_amount - (SUM(biv.bkg_total_amount) - SUM(biv.bkg_net_advance_amount))) * -1) AS bcbVndNetEffect,
				vendor_stats.vrs_outstanding AS outstanding_balance 
				FROM booking_cab
				INNER JOIN booking ON booking_cab.bcb_id = booking.bkg_bcb_id
				INNER JOIN booking_invoice as biv ON biv.biv_bkg_id=booking.bkg_id
				INNER JOIN vendors ON vendors.vnd_id = booking_cab.bcb_vendor_id AND vendors.vnd_id = vendors.vnd_ref_code
				INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
				WHERE bkg_status IN (3,5) AND booking_cab.bcb_active=1 
				AND bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR) 
				GROUP BY bcb_id 
			) a 
			GROUP BY bcb_vendor_id 
			HAVING vendorToGozo > 0 AND vndNetEffect < 0";

		$count = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());

		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'pagination'	 => false,
			'sort'			 => [
				'defaultOrder' => 'vendorToGozo DESC'],
		]);

		return $dataprovider;
	}

	/**
	 * 
	 * @param int $vndId
	 * @return Array
	 */
	public static function getArriveTimeStats($vndId)
	{
		$params	 = ['vndId' => $vndId];
		$sql	 = "SELECT vnd.vnd_name, vnd.vnd_code, vrs.vrs_total_trips totalTrips,  
					SUM(IF(bkg_trip_arrive_time > vndbkg.bkg_pickup_date, 1, 0)) lateArrive, 
					SUM(IF(bkg_trip_arrive_time <= vndbkg.bkg_pickup_date, 1, 0)) onTimeArrive
					FROM   vendors vnd
						   JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
						   JOIN booking_cab vndbcb ON vndbcb.bcb_vendor_id = vnd.vnd_id
						   JOIN booking vndbkg ON vndbkg.bkg_bcb_id = vndbcb.bcb_id AND vndbkg.bkg_status IN (6, 7)
						   JOIN booking_track btr ON btr.btk_bkg_id = vndbkg.bkg_id
					WHERE  vnd.vnd_id = :vndId AND btr.bkg_trip_start_time IS NOT NULL";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getExpairedDocuments()
	{
		$afterOneMonth	 = "DATE_ADD(NOW(), INTERVAL 30 DAY)";
		$beforeTenDays	 = "DATE_SUB(NOW(), INTERVAL 11 DAY)";
		$sql			 = "SELECT vnd.vnd_id,ctt.ctt_first_name, ctt.ctt_last_name,ctt.ctt_license_exp_date 
				FROM vendors vnd
				INNER JOIN contact_profile cop ON cop.cr_is_vendor=vnd.vnd_id AND cop.cr_status=1 
				INNER JOIN contact ctt ON ctt.ctt_id=cop.cr_contact_id AND ctt.ctt_active = 1
				INNER JOIN document doc ON ctt.ctt_license_doc_id=doc.doc_id  AND doc.doc_status = 1
				WHERE (ctt.ctt_license_exp_date BETWEEN $beforeTenDays AND $afterOneMonth) AND vnd.vnd_active = 1 ";

		$results = DBUtil::queryAll($sql, DBUtil::SDB());
		if ($results)
		{
			self::expiredDocNotification($results, 30);
		}
	}

	public static function getExpairedDocumentsWithTenDays()
	{
		$afterTenDay = "DATE_ADD(NOW(), INTERVAL 10 DAY)";
		$today		 = "CURRENT_DATE";
		$sql		 = "SELECT vnd.vnd_id,ctt.ctt_first_name, ctt.ctt_last_name,ctt.ctt_license_exp_date 
				FROM vendors vnd
				INNER JOIN contact_profile cop ON cop.cr_is_vendor=vnd.vnd_id AND cop.cr_status=1 
				INNER JOIN contact ctt ON ctt.ctt_id=cop.cr_contact_id AND ctt.ctt_active = 1
				INNER JOIN document doc ON ctt.ctt_license_doc_id=doc.doc_id  AND doc.doc_status = 1
				WHERE (ctt.ctt_license_exp_date BETWEEN $today AND $afterTenDay) AND vnd.vnd_active = 1 ";

		$results = DBUtil::queryAll($sql, DBUtil::SDB());
		if ($results)
		{
			self::expiredDocNotification($results, 10);
		}
	}

	public static function expiredDocNotification($vendorData, $days = 0)
	{
		foreach ($vendorData as $vendor)
		{
			$currentDate		 = date("Y-m-d", strtotime(date('Y-m-d')));
			$licenseDate		 = date("Y-m-d", strtotime($vendor['ctt_license_exp_date']));
			$licenseDateFromat	 = date("d/M/Y", strtotime($vendor['ctt_license_exp_date']));
			$msg				 = ($currentDate < $licenseDate) ? 'expires on' : 'expired on';
			$msg2				 = "License $msg $licenseDateFromat";
			$message			 = "Hello " . $vendor['ctt_first_name'] . " " . $vendor['ctt_last_name'] . " your $msg2. Please upload latest documents to prevent your account from being frozen.";

			$payLoadData = ['vendorId' => $vendor['vnd_id'], 'EventCode' => Document::Document_Vendor_Expired];
			$success	 = AppTokens::model()->notifyVendor($vendor['vnd_id'], $payLoadData, $message, "Vendor Documents expiring in $days days.");
		}
	}

	public static function getAllVendorForDocumentApproval()
	{
		$sql = "
                SELECT temp.vnd_id,
                GROUP_CONCAT(IF(temp.driverContactId>0,temp.driverContactId,NULL)) as driverContactId ,
                GROUP_CONCAT(IF(temp.vehicleIds>0,temp.vehicleIds,NULL)) as vehicleIds 
                FROM 
                (
                    SELECT  
                    cp.cr_is_vendor AS vnd_id,
                    cp.cr_contact_id  AS driverContactId,
                    0  AS vehicleIds
                    FROM drivers drv 
                    INNER JOIN contact_profile AS cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 AND drv.drv_id = drv.drv_ref_code AND cp.cr_is_vendor IS NOT NULL
                    INNER JOIN contact AS contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1
                    INNER JOIN vendors ON cp.cr_is_vendor = vendors.vnd_id AND vendors.vnd_id = vendors.vnd_ref_code AND vnd_active>0
                    LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND contact.ctt_voter_doc_id > 0 AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0  
                    LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND contact.ctt_aadhar_doc_id > 0 AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0   AND docaadhar.doc_created_at BETWEEN CURDATE() AND NOW()
                    LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND contact.ctt_pan_doc_id > 0 AND docpan.doc_type = 4 AND docpan.doc_active = 1  AND docpan.doc_status=0  AND docpan.doc_created_at BETWEEN CURDATE() AND NOW()
                    LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND contact.ctt_license_doc_id > 0 AND doclicence.doc_type = 5 AND doclicence.doc_active = 1  AND doclicence.doc_status=0  AND doclicence.doc_created_at BETWEEN CURDATE() AND NOW()
                    LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND contact.ctt_police_doc_id > 0 AND docpolicever.doc_type = 7 AND docpolicever.doc_active = 1  AND docpolicever.doc_status=0  AND docpolicever.doc_created_at BETWEEN CURDATE() AND NOW()
                    WHERE 1 
                    AND
                    (
                       ((docvoter.doc_id IS NOT NULL)      AND docvoter.doc_created_at BETWEEN CURDATE() AND NOW()   AND  ( (docvoter.doc_file_front_path IS NOT NULL and  docvoter.doc_file_front_path!='' ) OR (docvoter.doc_file_back_path IS NOT NULL and docvoter.doc_file_back_path!='' ) ))
                       OR ((docaadhar.doc_id IS NOT NULL)  AND docaadhar.doc_created_at BETWEEN CURDATE() AND NOW()   AND    ((docaadhar.doc_file_front_path IS NOT NULL and  docaadhar.doc_file_front_path!='')  OR (docaadhar.doc_file_back_path IS NOT NULL and docaadhar.doc_file_back_path!='')))
                       OR ((docpan.doc_id IS NOT NULL)     AND docpan.doc_created_at BETWEEN CURDATE() AND NOW()  AND    (( docpan.doc_file_front_path IS NOT NULL and docpan.doc_file_front_path!='')  OR (docpan.doc_file_back_path IS NOT NULL and docpan.doc_file_back_path!='')))
                       OR ((doclicence.doc_id IS NOT NULL) AND doclicence.doc_created_at BETWEEN CURDATE() AND NOW()   AND    (( doclicence.doc_file_front_path IS NOT NULL and  doclicence.doc_file_front_path!='' )      OR (doclicence.doc_file_back_path IS NOT NULL and doclicence.doc_file_back_path!='')))
                       OR ((docpolicever.doc_id IS NOT NULL) AND docpolicever.doc_created_at BETWEEN CURDATE() AND NOW()  AND    (docpolicever.doc_file_front_path IS NOT NULL and  docpolicever.doc_file_front_path!=''))
                    ) 
                    GROUP BY cp.cr_is_vendor

                    UNION 

                    SELECT
                    vendors.vnd_id   AS vnd_id,
                    0  AS driverContactId,
                    vehicle_docs.vhd_vhc_id  AS vehicleIds
                    FROM vendors
                    INNER JOIN contact_profile AS cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status =1 AND vendors.vnd_id = vendors.vnd_ref_code AND vnd_active>0 
                    INNER JOIN contact  ON contact.ctt_id = cp.cr_contact_id AND ctt_id = ctt_ref_code AND ctt_active =1
                    INNER JOIN vendor_vehicle ON vendor_vehicle.vvhc_vnd_id = cp.cr_is_vendor AND vendor_vehicle.vvhc_active=1
                    INNER JOIN vehicle_docs ON  vehicle_docs.vhd_vhc_id=vendor_vehicle.vvhc_vhc_id 
                    WHERE 1 AND vhd_status=0 AND vehicle_docs.vhd_active = 1 AND	vehicle_docs.vhd_file IS NOT NULL AND vehicle_docs.vhd_file <> ''    
                    AND vhd_created_at BETWEEN CURDATE() AND NOW()  AND vehicle_docs.vhd_type NOT IN (8,9,10,11,13)
                    GROUP BY vendors.vnd_id
                ) AS temp GROUP BY temp.vnd_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * show vendor according to their name search
	 * @param type $name
	 * @return type
	 */
	public static function getByName($name)
	{
		$sql = "SELECT v1.vnd_id,v1.vnd_name,ctt_first_name,ctt_last_name,ctt_business_name
				FROM `vendors` v1 
				INNER JOIN contact_profile ON contact_profile.cr_is_vendor = vnd_id AND cr_status=1 AND vnd_id = vnd_ref_code 
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				WHERE 1 AND (v1.vnd_name LIKE '%$name%' || v1.vnd_code LIKE '%$name%' || 
				contact.ctt_first_name LIKE '%$name%' || 
				contact.ctt_last_name LIKE '%$name%')";

		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getById($id)
	{
		$sql = "SELECT v1.vnd_id,v1.vnd_name,ctt_first_name,ctt_last_name,ctt_business_name
				FROM `vendors` v1 
				INNER JOIN contact_profile ON contact_profile.cr_is_vendor = vnd_id AND cr_status=1 AND vnd_id = vnd_ref_code 
				INNER JOIN contact ON contact.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1 
				WHERE 1 AND v1.vnd_id = $id AND v1.vnd_active = 1";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * This function is used for getting all vendor for auto approval in service call queue
	 * @return queryObject array
	 */
	public function getAllVendorApprovalOnInventoryShortage($zoneId)
	{
		$sql = "SELECT vnd.vnd_id,ctt.ctt_id,vnd.vnd_name
                FROM `vendors` vnd
                INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
                INNER JOIN contact_profile ON contact_profile.cr_is_vendor = vnd.vnd_id AND cr_status = 1
                INNER JOIN contact ctt     ON ctt.ctt_id = contact_profile.cr_contact_id AND ctt_active = 1
                WHERE 1
                AND vnd.vnd_active = 3
                AND vnd.vnd_id = vnd.vnd_ref_code
                AND (vnp.vnp_home_zone IN ($zoneId))      
                AND vnd_create_date BETWEEN  CONCAT(DATE_SUB(CURDATE(),INTERVAL 120 DAY),' 00:00:00') AND  NOW()
                GROUP BY vnd.vnd_ref_code";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param string $zones
	 * @param int $vehicleTypeId
	 * @return CDbDataReader
	 */
	public static function getByCabType($cabTypeList, $includeVendors = '', $onlyGozoNow = false)
	{

		$params	 = ['cabTypeList' => $cabTypeList];
		$where	 = '';
		if ($includeVendors != '')
		{
			$where = " AND v2.vnd_id IN($includeVendors)";
		}
		if ($onlyGozoNow)
		{
			$where .= " AND vnp.vnp_gozonow_enabled <2";
		}

		$sql = "SELECT DISTINCT v2.vnd_id, v2.vnd_name, vrs.vrs_trust_score,  
						contact_phone.phn_phone_no AS vnd_phone,
						vnp_home_zone, vrs.vrs_last_logged_in, 
						IF(v2.vnd_create_date > DATE_SUB(NOW(),INTERVAL 2 MONTH), 40, 0) AS joinScore,
						 CASE  						
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 40
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 3 MONTH) THEN 25
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 6 MONTH) THEN 20
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 9 MONTH) THEN 15
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 12 MONTH) THEN 10
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 18 MONTH) THEN 5
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 24 MONTH) THEN 1
						   ELSE 0
						 END  AS loginScore
					FROM vendors v1
					INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = v2.vnd_id AND vnp.vnp_gnow_status=1
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = v2.vnd_id
					INNER JOIN vendor_vehicle vvhc ON vvhc.vvhc_vnd_id = v2.vnd_id AND vvhc.vvhc_active=1
					INNER JOIN vehicles vhc ON vhc.vhc_id = vvhc.vvhc_vhc_id AND vhc.vhc_active = 1 		
					INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = v2.vnd_id
					INNER JOIN contact_phone ON contact_phone.phn_contact_id = cpr.cr_contact_id
						   AND contact_phone.phn_is_verified = 1 AND contact_phone.phn_is_primary = 1 				 
					WHERE v2.vnd_active = 1 AND v2.vnd_id = v2.vnd_ref_code AND vnp_manual_freeze = 0						 
							$where 						 
							AND FIND_IN_SET(vhc.vhc_type_id,:cabTypeList) AND vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 2 YEAR)
							 
					GROUP BY v2.vnd_id
					ORDER BY (joinScore + loginScore + (vrs.vrs_trust_score*2)) DESC LIMIT 0, 300		 
				";

		$data = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * This function is used to get the Car Count of vendors
	 * @return CDbDataReader 
	 */
	public static function getAllVendorCarCount()
	{
		$sql = "
				SELECT IFNULL(vnd_ref_code, vendors.vnd_id) AS vnd_id,
				COUNT(DISTINCT vendor_vehicle.vvhc_id) AS totalNoOfCars
				FROM   `vendors`
				LEFT JOIN `vendor_vehicle`
				ON vendor_vehicle.vvhc_vnd_id = vendors.vnd_id
                AND vvhc_active = 1
				LEFT JOIN `vehicles`
				ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id
                 AND vehicles.vhc_active > 0
				WHERE  vnd_active IN( 1, 2 )
					   AND vendor_vehicle.vvhc_active = 1
					   AND vnd_id = vnd_ref_code
				GROUP  BY vnd_ref_code 
				";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * This function is used to update vendor category type (1 => dco, 2=> vendor)
	 * @param type $vndId
	 * @param type $type
	 */
	public static function updateVendorCategoryType($vndId, $type = 0)
	{
		$params	 = ['vndId' => $vndId, 'type' => $type];
		$sql	 = "UPDATE vendors  SET vnd_is_dco =:type WHERE vnd_id =:vndId";
		DBUtil::execute($sql, $params);
	}

	/**
	 * function to count vendor booking within one month
	 * @param type $vndId
	 * @return type booking count Int
	 */
	public static function getVendorBookingCount($vndId, $days = null)
	{
		$params = ['vndId' => $vndId];
		if ($days != null)
		{
			$condition = " AND bkg.bkg_pickup_date BETWEEN (NOW() - INTERVAL $days DAY) AND NOW()";
		}

		$sql = "SELECT count(bkg_bcb_id) as bookingCount FROM booking bkg 
				JOIN booking_cab ON bcb_id = bkg.bkg_bcb_id AND bcb_active = 1 
				INNER JOIN vendors ON vnd_id=bcb_vendor_id  AND bcb_vendor_id = :vndId
				WHERE 1 $condition  AND bkg.bkg_status IN (6,7)";

		$cnt = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $cnt;
	}

	/** 	
	 * @param int $vndId
	 * @return array
	 */
	public static function showViewDetails($vndId)
	{

		$sql	 = "SELECT
                vnd_id, vnd_name, vnd_code, eml_email_address as vnd_email,
                cr_contact_id,  contact.ctt_name as vnd_contact_person,
                phn_phone_no as vnd_contact_number,
                phn_phone_no as vnd_alt_contact_number, contact.ctt_address as vnd_address,                 
                contact.ctt_business_name, contact.ctt_user_type ,                
                contact.ctt_first_name , contact.ctt_last_name ,
                (SELECT CONCAT(ctt_first_name,' ',ctt_last_name) FROM contact WHERE ctt_id = ctt_owner_id) as vnd_owner,  
                vnd_active, vnd_create_date, vnd_type,               
                vrs_vnd_overall_rating as vnd_overall_rating,
                vrs_overall_score as vnd_overall_score,                
				vrs_dependency,
                vrs_total_trips as vnd_total_trips, 
				vrs_first_approve_date,
				vrs_last_approve_date,
                contact.ctt_bank_name as vnd_bank_name,
                contact.ctt_bank_branch as vnd_bank_branch,
                contact.ctt_beneficiary_name as vnd_beneficiary_name,
                contact.ctt_account_type as vnd_account_type,
                contact.ctt_bank_ifsc as vnd_bank_ifsc,
                contact.ctt_bank_account_no as vnd_bank_account_no,
                contact.ctt_beneficiary_id as vnd_beneficiary_id,                
                vnp_home_zone as vnd_home_zone,
                vnp_accepted_zone as vnd_accepted_zone,               
                vrs_credit_limit as vnd_credit_limit, 
				contact.ctt_pan_no as vnd_pan_no,                
                vnp_is_freeze as vnd_is_freeze,  vnp_cod_freeze AS vnd_cod_freeze, vnp_credit_limit_freeze,vnp_low_rating_freeze,vnp_doc_pending_freeze,vnp_manual_freeze,
				vcty.cty_name vnd_city_name, hzon.zon_name vnd_home_zone,
				vnp_is_attached,vendor_pref.vnp_boost_enabled,vendor_pref.vnp_vhc_boost_count,vnp_oneway,vnp_round_trip,vnp_multi_trip,vnp_airport,
				vnp_package,vnp_flexxi,vnp_daily_rental,vnp_lastmin_booking,vnp_tempo_traveller,vnd_firm_type                
                FROM vendors
                INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id = vendors.vnd_id
                INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
                LEFT JOIN contact_profile ON contact_profile.cr_is_vendor=vendors.vnd_id AND cr_status=1
                
				LEFT JOIN contact ctt ON ctt.ctt_id=cr_contact_id AND ctt.ctt_active = 1 
				LEFT JOIN contact ON contact.ctt_id=ctt.ctt_ref_code AND contact.ctt_active = 1 

                LEFT JOIN contact_email ON contact_email.eml_contact_id = contact.ctt_id AND  contact_email.eml_is_verified=1 AND contact_email.eml_active =1
                LEFT JOIN contact_phone ON contact_phone.phn_contact_id = contact.ctt_id AND contact_phone.phn_is_verified=1 AND contact_phone.phn_active =1                  
                LEFT JOIN cities vcty ON vcty.cty_id = contact.ctt_city
                LEFT JOIN zones hzon ON hzon.zon_id = vendor_pref.vnp_home_zone  
                WHERE vendors.vnd_id IN (:vndId) 
				AND contact.ctt_id=contact.ctt_ref_code
                GROUP BY vendors.vnd_id,cr_created DESC";
		$params	 = ["vndId" => $vndId];
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param integer $vndId
	 * @param integer $tier
	 * @param array $userInfo
	 * @return boolean
	 * @throws Exception
	 */
	public static function updateVendorTire($vndId, $tier = 0, $userInfo)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			/* @var $model Vendors */
			$model				 = Vendors::model()->findByPk($vndId);
			$model->vnd_rel_tier = $tier;
			$model->scenario	 = 'upgradeTire';
			if ($model->validate() && $model->save())
			{
				$message = $tier > 0 ? "You have been upgraded to Golden tier." : "You have been degraded from Golden tier";
				$eventId = $tier > 0 ? VendorsLog::VENDOR_GOLDEN_TIER : VendorsLog::VENDOR_TIER_DENY;
				VendorsLog::model()->createLog($model->vnd_id, $message, $userInfo, $eventId, false, false);
				$success = DBUtil::commitTransaction($transaction);
				$returnSet->setStatus(true);
				$returnSet->setMessage("Tier date updated successfully");
			}
			else
			{
				throw new Exception("Errors : " . $model->getErrors());
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getVendorList($query = null)
	{
		$criteria			 = new CDbCriteria();
		$criteria->select	 = "vnd_id , vnd_code";
		$criteria->compare('vnd_active', 1);
		if ($query != null)
		{
			$criteria->compare('vnd_code', $query);
		}
		$criteria->order = "vnd_code";
		$comments		 = Vendors::model()->findAll($criteria);
		return $comments;
	}

	/**
	 * 
	 * @param string $zones
	 * @param int $vehicleTypeId
	 * @return CDbDataReader
	 */
	public static function getGroupListByCabType($vehicleTypeId, $includeVendors = '', $onlyGozoNow = false, $hourDuration = 60, $bkgType = "")
	{
		$params	 = [];
		$where	 = '';
		if ($vehicleTypeId > 0)
		{
			$cabTypeList = Vehicles::getVhcTypeFromScv($vehicleTypeId);

			$params['cabTypeList'] = $cabTypeList;

			$where = " AND FIND_IN_SET(vhc.vhc_type_id,:cabTypeList) ";
		}
		if ($includeVendors != '')
		{
			$where		 = " AND v2.vnd_id IN($includeVendors)";
			$whereAPt	 = " AND apt.apt_entity_id IN($includeVendors)";
		}
		else
		{
			$where		 = " AND 1=2";
			$whereAPt	 = " AND 1=2";
		}
		if ($onlyGozoNow)
		{
			$where .= " AND vnp.vnp_gozonow_enabled <2";
		}

		if ($bkgType == 14)
		{
			$where .= " AND  vnp.vnp_airport =1 OR vnp.vnp_daily_rental =1";
		}

		$sql = "SELECT	GROUP_CONCAT(DISTINCT vnd_id) vndIds, 						
						GROUP_CONCAT(if(apt_platform!=7,apt_entity_id,null)) entIdVendor,
						GROUP_CONCAT(if(apt_platform=7,apt_entity_id,null)) entIdDCO,
						count(DISTINCT vnd_id) cntVnd,
						GROUP_CONCAT(DISTINCT apt_device_token) aptTokens,
						GROUP_CONCAT(if(apt_platform!=7,apt_device_token,null)) aptTokenVendor, 
                        GROUP_CONCAT(if(apt_platform=7,apt_device_token,null)) aptTokenDCO, 
						sum(cntToken1) cntToken,
						sum(if(apt_platform!=7,cntToken1,0)) cntVndToken, 
						sum(if(apt_platform=7,cntToken1,0)) cntDCOToken  
				from (
				SELECT DISTINCT v2.vnd_id, apt_entity_id,apt_platform,
    					GROUP_CONCAT( DISTINCT apt_device_token) apt_device_token ,
							count(DISTINCT apt_device_token) cntToken1,
						IF(v2.vnd_create_date > DATE_SUB(NOW(),INTERVAL 2 MONTH), 40, 0) AS joinScore,
						 CASE  						
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 40
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 3 MONTH) THEN 25
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 6 MONTH) THEN 20
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 9 MONTH) THEN 15
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 12 MONTH) THEN 10
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 18 MONTH) THEN 5
						   WHEN vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 24 MONTH) THEN 1
						   ELSE 0
						 END  AS loginScore
					FROM vendors v1
					INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
    				INNER JOIN (
                        SELECT apt_device_token,apt_entity_id ,apt_user_type,apt_platform
                        	from  `app_tokens` apt  
                        	WHERE apt.apt_user_type=2 AND apt.apt_status = 1 
                        	AND apt.apt_entity_id>0 AND apt.apt_device_token IS NOT NULL  
							AND apt.apt_last_login >= DATE_SUB(NOW(),INTERVAL $hourDuration HOUR)
                        	$whereAPt
                        	ORDER BY apt.apt_last_login DESC  
                   	 )apt ON apt.apt_entity_id = v2.vnd_id 
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = v2.vnd_id AND (vnp.vnp_gnow_status=1 )
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = v2.vnd_id
					INNER JOIN vendor_vehicle vvhc ON vvhc.vvhc_vnd_id = v2.vnd_id AND vvhc.vvhc_active=1
					INNER JOIN vehicles vhc ON vhc.vhc_id = vvhc.vvhc_vhc_id AND vhc.vhc_active = 1 		
					INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = v2.vnd_id 			 
					WHERE  v2.vnd_active = 1 AND v2.vnd_id = v2.vnd_ref_code AND vnp_manual_freeze = 0		
							$where 	
						 	AND vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL 2 YEAR)    						 
							AND apt.apt_entity_id>0 AND apt.apt_device_token IS NOT NULL
					GROUP BY v2.vnd_id
					ORDER BY (joinScore + loginScore + (vrs.vrs_trust_score*2)) DESC LIMIT 0, 300)a";

		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param type $vndId
	 * @return type
	 */
	public static function getFullDetails($vndId)
	{
		$data			 = [];
		$data['details'] = Vendors::model()->getViewDetailbyId($vndId);
		$data['person']	 = Contact::getByPerson($data['details']['cr_contact_id']);
		$data['docs']	 = Document::getDocModels($data['details']['cr_contact_id']);
//$rating = VendorStats::fetchRating($vndId);
		return $data;
	}

	/**
	 * 
	 * @param type $zoneList
	 * @param type $lastLoginMonth
	 * @param type $excludeVendors
	 * @return type
	 */
	public static function getListByZoneIds($zoneList, $lastLoginMonth = 12, $excludeVendors = '')
	{

		$params	 = ['zoneList' => $zoneList];
		$where	 = '';
		if ($excludeVendors != '')
		{
			$params['excludeVendors']	 = $excludeVendors;
			$where						 = " AND v2.vnd_id NOT IN(:excludeVendors)";
		}

		$sql = "SELECT GROUP_CONCAT(DISTINCT v2.vnd_id) 
					FROM vendors v1
					INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = v2.vnd_id AND vnp.vnp_gnow_status=1
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = v2.vnd_id  			 
					WHERE v2.vnd_active = 1 AND v2.vnd_id = v2.vnd_ref_code AND vnp.vnp_manual_freeze = 0
							AND (FIND_IN_SET(vnp_home_zone, :zoneList))
							$where 						 
						AND ((vrs.vrs_last_logged_in IS NULL AND v2.vnd_create_date > DATE_SUB(NOW(),INTERVAL 12 MONTH))  
						OR vrs.vrs_last_logged_in > DATE_SUB(NOW(), INTERVAL $lastLoginMonth MONTH))							 
				";

		$data = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function getCustomerRating()
	{
		$sql = "SELECT bkg_id, bkg_pickup_date, bcb.bcb_vendor_id, bui.bkg_user_id, biv.bkg_gozo_amount, 
					biv.bkg_vendor_amount, biv.bkg_total_amount, bcb.bcb_driver_id
				FROM `booking` 
				INNER JOIN booking_cab bcb ON bcb.bcb_id = booking.bkg_bcb_id
				INNER JOIN booking_user bui ON bui.bui_bkg_id = booking.bkg_id 
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
				WHERE `bkg_pickup_date` >= '2022-10-01 00:00:00'  AND bcb_vendor_id IS NOT NULL  AND booking.bkg_active = 1 
						AND booking.bkg_status IN(6,7)
				GROUP BY bkg_id  
				ORDER BY `booking`.`bkg_pickup_date`  ASC";

		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $vndID
	 * @return type int
	 */
	public static function getSD($vndID)
	{
		$relVendorList = \Vendors::getRelatedIds($vndID);
		if ($relVendorList)
		{
			$primaryVnd	 = \Vendors::getPrimaryByIds($relVendorList);
			$vndID		 = $primaryVnd['vnd_id'];
		}

		$amount		 = 0;
		$vndSetting	 = "SELECT 
						vnp_min_sd_req_amt,vrs_security_amount,vrs_security_receive_date,vrs_outstanding 
							FROM `vendors`
							   INNER JOIN vendor_pref ON vnd_id = vendor_pref.vnp_vnd_id 
							   INNER JOIN vendor_stats ON vnd_id = vrs_vnd_id 
							   WHERE vnd_id = $vndID ";
		$result		 = DBUtil::queryRow($vndSetting, DBUtil::SDB());

		$securityAmount					 = AccountTransactions::getSecurityAmount($relVendorList);
		$result['vrs_security_amount']	 = $securityAmount;
		if ($result['vnp_min_sd_req_amt'] > $result['vrs_security_amount'])
		{

			$amountPending = $result['vnp_min_sd_req_amt'] - $result['vrs_security_amount'];

			$totTrans = $result['vrs_outstanding'];

//$totTrans		 = $result['vrs_outstanding'];
			$ruleSD			 = Config::get('vendor.SD.settings');
			$validDateArr	 = json_decode($ruleSD);

			$security_receive_date = strtotime($result['vrs_security_receive_date']);
			if ($security_receive_date == null)
			{
				$security_receive_date = strtotime("-6 day");
			}

			$var		 = time() - $security_receive_date;
			$difference	 = round($var / (60 * 60 * 24));

			Logger::writeToConsole("Difference: {$difference}, SDinstallmentIntervalDay: {$validDateArr->SDinstallmentIntervalDay}");

			if ($difference > $validDateArr->SDinstallmentIntervalDay)
			{
				if ($validDateArr->SDinstallmentAmt > $amountPending)
				{
					$amount = $amountPending;
				}
				else
				{
					$amount = $validDateArr->SDinstallmentAmt;
				}

				Logger::writeToConsole("Amount: {$amount}");

				if ($totTrans * -1 < $amount)
				{
					$amount = 0;
					goto skip;
//throw new Exception('Failed to save accounts.');
				}
				$date			 = Filter::getDBDateTime();
				$remarks		 = "Security deposit";
				$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndID);
				$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_SECURITY_DEPOSIT, Accounting::AT_OPERATOR, $vndID, '', $remarks);
				$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndID, '', $remarks);
				$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);
				if (!$status)
				{
					throw new Exception('Failed to save accounts.');
				}
			}
		}

		skip:
		Logger::writeToConsole("Final Amount: {$amount}");
		return $amount;
	}

##### checking and updating data in table = temp_contacts and DB = test

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
			$sql	 = "SELECT tpc.tpc_fname fname,tpc.tpc_lname lname,tpc.tpc_email email,tpc.tpc_phone as number FROM test.temp_contacts tpc WHERE tpc.tpc_phone = :phone";
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
	public static function checkExist($phone)
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

	public function getProfileStrength($type = DBUtil::ReturnType_Provider)
	{
		$where	 = '';
		$vndId	 = $this->vnd_id;
		if ($vndId != '')
		{
			$where .= " AND vnd.vnd_id = $vndId";
		}
		if (isset($this->vnd_active) && $this->vnd_active != "")
		{
			$where .= " AND (vnd.vnd_active = " . $this->vnd_active . ")";
		}
		else
		{
			$where .= " AND (vnd.vnd_active = 1)";
		}

		$sql = "SELECT vnd.vnd_id, vnd.vnd_name, vrs.vrs_sticky_score, vrs.vrs_security_amount, vrs.vrs_vnd_overall_rating, vrs.vrs_vnd_total_trip,
				vrs.vrs_tot_bid, vrs.vrs_count_driver, vrs.vrs_count_car, vrs.vrs_approve_driver_count, vrs.vrs_approve_car_count, 
				vrs.vrs_trust_score, vrs.vrs_docs_score, vrs.vrs_no_of_star, vrs.vrs_denied_duty_cnt, vrs.vrs_total_trips, vrs.vrs_locked_amount,
				vrs.vrs_withdrawable_balance, vrs.vrs_last_bkg_cmpleted, vrs.vrs_total_completed_days_30, vrs.vrs_total_vehicle_30, vrs.vrs_driver_app_used,
				vrs.vrs_penalty_count, vrs.vrs_total_booking, vrs.vrs_margin, vrs.vrs_bid_win_percentage, vrs.vrs_dependency, vnd.vnd_active,
				vnp.vnp_is_freeze, vnp.vnp_cod_freeze, vnp.vnp_credit_limit_freeze, vnp.vnp_low_rating_freeze, vnp.vnp_doc_pending_freeze,
				vnp.vnp_manual_freeze
				FROM `vendors` vnd
				INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id   
				INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
				WHERE 1 $where
				GROUP BY vnd.vnd_id";

		$sqlCount = "SELECT 
						count(*)    
						FROM `vendors` vnd
						INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id   
						WHERE 1 $where
						GROUP BY vnd_id";
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['vnd_name', 'vrs_dependency', 'vrs_sticky_score', 'vrs_trust_score'],
					'defaultOrder'	 => 'vnd_id  desc'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public static function getDcoData($bkgFromDate, $bkgToDate, $fromDate, $toDate, $isDCOApp = 0, $command = DBUtil::ReturnType_Provider)
	{
		$params		 = array();
		$fromTime	 = ' 00:00:00';
		$toTime		 = ' 23:59:59';
		$where		 = "";
		$join		 = " LEFT JOIN app_tokens ON app_tokens.apt_entity_id=vendors.vnd_id  AND app_tokens.apt_platform =7	AND app_tokens.apt_user_type=2 ";
		if ($bkgFromDate != '' && $bkgToDate != '')
		{
			$params['bkgFromDate']	 = $bkgFromDate . $fromTime;
			$params['bkgToDate']	 = $bkgToDate . $toTime;
			$where					 .= " AND app_tokens.apt_date BETWEEN  :bkgFromDate AND :bkgToDate  ";
			$join					 = " INNER JOIN app_tokens ON app_tokens.apt_entity_id=vendors.vnd_id  AND app_tokens.apt_platform =7	AND app_tokens.apt_user_type=2 ";
		}

		if ($fromDate != '' && $toDate != '')
		{
			$params['fromDate']	 = $fromDate . $fromTime;
			$params['toDate']	 = $toDate . $toTime;
			$where				 .= " AND vnd_create_date BETWEEN  :fromDate AND :toDate ";
		}

		if ($isDCOApp == 1)
		{
			$where .= 'AND vendors.vnd_registered_platform=1';
		}

		if ($bkgFromDate != '' && $bkgToDate != '')
		{
			$startDate	 = $bkgFromDate . $fromTime;
			$endsDate	 = $bkgToDate . $toTime;
		}
		else if ($fromDate != '' && $toDate != '')
		{
			$startDate	 = $fromDate . $fromTime;
			$endsDate	 = $toDate . $toTime;
		}
		else
		{
			$startDate	 = date("Y-m-d", strtotime("-30 day", time()));
			$endsDate	 = date('Y-m-d');
		}
		$biddingAcceptTable	 = "biddingAccept_" . rand();
		$sqlBiddingAccept	 = " (INDEX bidding_vendor_id (bcb_vendor_id)) 
								SELECT 
								COUNT(*) AS BiddingAccept,
								bcb_vendor_id
								FROM `booking_cab`
								  INNER JOIN booking ON booking.bkg_bcb_id=booking_cab.bcb_id 
								WHERE 1 
									AND bkg_active=1
									AND bcb_assign_mode IN (0,1,2)
									AND bcb_vendor_id>0
									AND booking_cab.bcb_created BETWEEN  '$startDate' AND '$endsDate'
								GROUP BY booking_cab.bcb_vendor_id";
		DBUtil::createTempTable($biddingAcceptTable, $sqlBiddingAccept, DBUtil::SDB());

		$totalBidTable	 = "totalBid_" . rand();
		$sqlTotalBid	 = " (INDEX bid_vendor_id (bvr_vendor_id)) 
							SELECT 
							COUNT(booking_vendor_request.bvr_booking_id) AS TotalBid,bvr_vendor_id
							FROM booking
							   INNER JOIN booking_vendor_request ON booking_vendor_request.bvr_booking_id=booking.bkg_id 
							WHERE 1 
							   AND bkg_active=1
							   AND booking_vendor_request.bvr_vendor_id>0
							   AND booking_vendor_request.bvr_created_at BETWEEN  '$startDate' AND '$endsDate'
							GROUP BY booking_vendor_request.bvr_vendor_id";
		DBUtil::createTempTable($totalBidTable, $sqlTotalBid, DBUtil::SDB());

		$tempDcoServed	 = "dcoServed_" . rand();
		$sqlDcoServed	 = " (INDEX dco_vendor_id (bcb_vendor_id))
							SELECT 
							COUNT(booking.bkg_id) AS DcoServedCnt,booking_cab.bcb_vendor_id
							FROM booking
								INNER JOIN booking_cab ON booking.bkg_bcb_id=booking_cab.bcb_id 
								INNER JOIN booking_track_log ON booking_track_log.btl_bkg_id =booking.bkg_id
							WHERE 1 
								AND bcb_vendor_id>0
								AND booking_track_log.btl_created  BETWEEN  '$startDate' AND '$endsDate'
								AND booking.bkg_status IN (5,6,7)
								AND booking.bkg_active=1 
								AND `btl_event_type_id` IN (104,101)
								AND `btl_event_platform` = 7 
							GROUP BY bcb_vendor_id";
		DBUtil::createTempTable($tempDcoServed, $sqlDcoServed, DBUtil::SDB());

		$sql = "SELECT
				vendors.vnd_id,
				app_tokens.apt_id,
				vendors.vnd_code AS 'VendorCode',
				vendors.vnd_name AS 'VendorName',
				vendors.vnd_create_date AS 'VendorCreateDate',
				TIMESTAMPDIFF(DAY,vendors.vnd_create_date,NOW()) AS 'VendorAge',
				IF(vnp_is_freeze=1,'Freeze',IF(vnp_is_freeze<>1 AND vnd_active=1,'Active','Inactive')) AS 'VendorStatus',
				IF(vendor_pref.vnp_cod_freeze=1,'Yes','NO') AS 'CODFreeze',
				IF(vendor_pref.vnp_credit_limit_freeze=1,'Yes','NO') AS 'CreditLimitFreeze',
				IF(vendor_pref.vnp_low_rating_freeze=1,'Yes','NO') AS 'LowRatingFreeze',
				IF(vendor_pref.vnp_doc_pending_freeze=1,'Yes','NO') AS 'DOCPendingFreeze',
				IF(vendor_pref.vnp_manual_freeze=1,'Yes','NO') AS 'ManualFreeze',
				bidAccept.BiddingAccept AS 'BiddingAccept',
				totalBid.TotalBid AS 'TotalBid',				
				dcoServedCnt.DcoServedCnt AS 'DcoServedCnt',
				vendor_stats.vrs_total_trips AS 'TotalServed',
				vendors.vnd_registered_platform
				FROM vendors
				INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id=vendors.vnd_id
				INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id=vendors.vnd_id
				LEFT JOIN $biddingAcceptTable bidAccept ON bidAccept.bcb_vendor_id=vendors.vnd_id
				LEFT JOIN $totalBidTable totalBid ON totalBid.bvr_vendor_id=vendors.vnd_id
				LEFT JOIN $tempDcoServed dcoServedCnt ON dcoServedCnt.bcb_vendor_id=vendors.vnd_id
				$join
				WHERE 1				
				AND vnd_active=1	
				$where GROUP BY vendors.vnd_id";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'params'		 => $params,
				'db'			 => DBUtil::SDB(),
				'pagination'	 => ['pageSize' => 100],
				'sort'			 => ['attributes' => array('BiddingAccept', 'TotalBid', 'DcoServedCnt', 'TotalServed', 'VendorAge'), 'defaultOrder' => 'TotalServed DESC,vnd_create_date DESC'],
			]);
			return $dataprovider;
		}
		else
		{
			$orderBy = " ORDER BY vendors.vnd_create_date DESC";
			return DBUtil::query($sql . $orderBy, DBUtil:: SDB(), $params);
		}
	}

	/**
	 * 
	 * @param type $phone
	 * @return boolean
	 */
	public static function getDcoServedCount($vendorId, $fromDate, $toDate)
	{
		try
		{

			$params				 = array();
			$fromTime			 = ' 00:00:00';
			$toTime				 = ' 23:59:59';
			$params['fromDate']	 = $fromDate . $fromTime;
			$params['toDate']	 = $toDate . $toTime;
			$params['vendorId']	 = $vendorId;
			$sql				 = "SELECT count(distinct blg_booking_id) as DcoBookingCnt 
					FROM `booking_log` 
					WHERE 1
					AND `blg_entity_type` = 2 
					AND `blg_entity_id` = :vendorId 
					AND `blg_event_id` IN (216,215) 
					AND blg_user_type=10 
					AND blg_booking_status IN (5,6,7,9)
					AND blg_created BETWEEN  :fromDate AND :toDate ";
			return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
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
	 * @return boolean
	 */
	public static function getTotalBidsByVendor($vendorId)
	{
		try
		{
			$params				 = array();
			$params['vendorId']	 = $vendorId;
			$sql				 = "SELECT 
					COUNT(booking_vendor_request.bvr_booking_id)
					FROM booking_pref
					INNER JOIN booking ON booking.bkg_id=booking_pref.bpr_bkg_id
					INNER JOIN booking_vendor_request ON booking_vendor_request.bvr_booking_id=booking.bkg_id
					WHERE 1 AND booking_vendor_request.bvr_vendor_id=:vendorId";
			return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		}
		catch (Exception $ex)
		{
			Logger::trace($ex->getMessage());
		}
		return false;
	}

	public static function getTotalBiddingAcceptByVendor($vendorId)
	{
		try
		{
			$params				 = array();
			$params['vendorId']	 = $vendorId;
			$sql				 = "SELECT COUNT(*) AS cnt  FROM `booking_cab`
					INNER JOIN booking ON booking.bkg_bcb_id=booking_cab.bcb_id
					WHERE `bcb_vendor_id` =:vendorId 	AND bcb_assign_mode IN (0,1,2)";
			return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		}
		catch (Exception $ex)
		{
			Logger::trace($ex->getMessage());
		}
		return false;
	}

	public static function isDco($userId)
	{
		$dcoFlag		 = 0;
		$receivedData	 = ContactProfile::getEntitybyUserId($userId);
		if ($receivedData['cr_is_vendor'] != null && $receivedData['cr_is_vendor'] > 0 && $receivedData['cr_is_driver'] != null && $receivedData['cr_is_driver'] > 0)
		{
			$dcoFlag = 1;
		}

		return $dcoFlag;
	}

	/**
	 * this function is used for adjust vendor security amount with vendor outstanding
	 * @param type $vndID
	 * @param type $amount
	 * @return boolean
	 * @throws Exception
	 */
	public static function adjustLockedAmount($vndID, $amount)
	{

		$params				 = array();
		$params['vendorId']	 = $vndID;
		$vndSetting			 = "SELECT vrs_security_amount, vrs_outstanding FROM `vendors`
						INNER JOIN vendor_pref ON vnd_id = vendor_pref.vnp_vnd_id 
						INNER JOIN vendor_stats ON vnd_id = vrs_vnd_id 
						WHERE vnd_id = :vendorId ";
		$result				 = DBUtil::queryRow($vndSetting, DBUtil::SDB(), $params);

		$totTrans		 = $result['vrs_outstanding'];
		$remainingAmount = 0;
		$success		 = false;
		$locked			 = $amount;
		if ($totTrans >= 0)
		{
			$remainingAmount = $amount;
			goto skip;
		}

		$totTrans = $totTrans * -1; // outstanding negetive gozo will pay to vendor

		if ($totTrans < $amount)
		{

			$remainingAmount = $amount - $totTrans;
			$amount			 = $totTrans;
		}
		if ($amount > 0)
		{
			$deductedAmount						 = $amount * -1;
			$vndStatsModel						 = VendorStats::model()->getbyVendorId($vndID);
			$outStanding						 = $vndStatsModel->vrs_outstanding;
			$lockAmount							 = $vndStatsModel->vrs_locked_amount;
			$vndStatsModel->vrs_outstanding		 = ($outStanding - $deductedAmount) | 0;
			$vndStatsModel->vrs_locked_amount	 = ($lockAmount + $deductedAmount) | 0;
			$success							 = $vndStatsModel->save();
		}

		$date	 = Filter::getDBDateTime();
		$remarks = "Locked amount";
		/* $accTransModel	 = AccountTransactions::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndID);
		  $crTrans		 = AccountTransDetails::getInstance(Accounting::LI_SECURITY_DEPOSIT, Accounting::AT_OPERATOR, $vndID, '', $remarks);
		  $drTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndID, '', $remarks);
		  $status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR); */
		if ($status)
		{
			$success = true;
		}
//update outstanding:

		skip:
		$data = array(
			'success'			 => $success,
			'remainingAmount'	 => $remainingAmount,
			'lockedAmount'		 => $locked);
		return $data;
	}

	/**
	 * 
	 * @param type $vndID
	 * @param type $amount
	 * @return boolean
	 */
	public static function updateSecurityAmount($vndID, $amount)
	{

		$model										 = Vendors::model()->resetScope()->findByPk($vndID);
		$modelVendStats								 = $model->vendorStats;
		$modelVendStats->vrs_security_amount		 = $modelVendStats->vrs_security_amount + $amount;
		$modelVendStats->vrs_security_receive_date	 = new CDbExpression('NOW()');

		if ($modelVendStats->save())
		{
			$desc = 'Security deposit ' . $amount . " transfered from vendor account";

			Logger::writeToConsole("Desc: {$desc}");
			VendorsLog::model()->createLog($model->vnd_id, $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SECURITY_DEPOSIT, false, false);
			return true;
		}
	}

	/**
	 * 
	 * @param integer $zone
	 * @param string $scvIds
	 * @param integer $command
	 * @return \CSqlDataProvider
	 */
	public static function getProfileReport($zone, $scvIds, $type = DBUtil::ReturnType_Provider)
	{
		DBUtil::getINStatement($scvIds, $bindString1, $params);
		if ($scvIds != null)
		{
			$sqlSvcClass = "AND svc_class_vhc_cat.scv_id IN ($bindString1)";
		}
		$sql			 = "SELECT
				vnd_id,
				vnd_name,
				scv_label,
				IF(vnp_home_zone=zones.zon_id,'Yes','No') AS homeZone,
				IF(vnp_is_freeze=1,'Freeze',IF(vnp_is_freeze<>1 AND vnd_active=1,'Active','Inactive')) AS vendor_status,
                ((IFNULL(vrs_step1_unassign_count,0)) +(IFNULL(vrs_step2_unassign_count,0))+(IFNULL(vrs_system_unassign_count,0))) AS Total_Unassign_Count,
				vrs_total_trips,
				vrs_vnd_overall_rating,
				vrs_denied_duty_cnt,
				vrs_docs_score,
				vrs_approve_driver_count,
				vrs_approve_car_count,
				vrs_last_bkg_cmpleted
				FROM `vendors`
				INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id=vendors.vnd_id
				INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id=vendors.vnd_id
				INNER JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vnd_id =vendors.vnd_id
				INNER JOIN `vehicles` ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id
				INNER JOIN `svc_class_vhc_cat` ON svc_class_vhc_cat.scv_id=vehicles.vhc_type_id
				INNER JOIN `zones` ON (FIND_IN_SET(zon_id, vendor_pref.vnp_accepted_zone) OR FIND_IN_SET(zon_id, vendor_pref.vnp_home_zone))
				INNER JOIN `contact_profile` ON contact_profile.cr_is_vendor=vendors.vnd_id AND contact_profile.cr_status=1
				INNER JOIN `contact_phone` ON contact_phone.phn_contact_id=contact_profile.cr_contact_id AND contact_phone.phn_active=1 AND contact_phone.phn_is_primary=1
				WHERE vendors.vnd_active=1
				AND zones.zon_id=:zone $sqlSvcClass
				AND vendor_vehicle.vvhc_active=1
				AND vehicles.vhc_active=1
				GROUP BY vendors.vnd_id
				ORDER BY homeZone DESC";
		$params[':zone'] = $zone;
		$sqlCount		 = "SELECT COUNT(1) as cnt FROM (
            SELECT
				vnd_id,
				IF(vnp_home_zone=zones.zon_id,1,0) AS homeZone
				FROM `vendors`
				INNER JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id=vendors.vnd_id
				INNER JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id=vendors.vnd_id
				INNER JOIN `vendor_vehicle` ON vendor_vehicle.vvhc_vnd_id =vendors.vnd_id
				INNER JOIN `vehicles` ON vehicles.vhc_id=vendor_vehicle.vvhc_vhc_id
				INNER JOIN `svc_class_vhc_cat` ON svc_class_vhc_cat.scv_id=vehicles.vhc_type_id
				INNER JOIN `zones` ON (FIND_IN_SET(zon_id, vendor_pref.vnp_accepted_zone) OR FIND_IN_SET(zon_id, vendor_pref.vnp_home_zone))
				INNER JOIN `contact_profile` ON contact_profile.cr_is_vendor=vendors.vnd_id AND contact_profile.cr_status=1
				INNER JOIN `contact_phone` ON contact_phone.phn_contact_id=contact_profile.cr_contact_id AND contact_phone.phn_active=1 AND contact_phone.phn_is_primary=1
				WHERE vendors.vnd_active=1
				AND zones.zon_id=:zone $sqlSvcClass
				AND vendor_vehicle.vvhc_active=1
				AND vehicles.vhc_active=1
				GROUP BY vendors.vnd_id
				ORDER BY homeZone DESC, IF(vnp_is_freeze<>1 AND vnd_active=1,1,0) DESC,vrs_last_bkg_cmpleted DESC, vrs_total_trips DESC, vrs_vnd_overall_rating DESC
                ) a;";

		$command		 = DBUtil::command($sql, DBUtil::SDB3());
		$command->params = $params;
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$command->getText()} ) temp", DBUtil::SDB3(), $command->params);
			$dataprovider	 = new CSqlDataProvider($command, array(
				"totalItemCount" => $count,
				"params"		 => $command->params,
				'db'			 => DBUtil ::SDB3(),
				"pagination"	 => array("pageSize" => 100),
				'sort'			 => array('attributes'	 => array('vnd_name', 'scv_label', 'homeZone', 'vrs_total_trips', 'vrs_vnd_overall_rating', 'vrs_denied_duty_cnt', 'vrs_docs_score'),
					'defaultOrder'	 => '')
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($command->getText(), DBUtil::SDB3(), $command->params);
		}
	}

	public static function getVndIdsByRefCode($vendorId)
	{

		$relVndIds = \Vendors::getRelatedIds($vendorId);
		return $relVndIds;
//		$params				 = array();
//		$params['refCode']	 = $refCode;
//
//		$sql = "SELECT GROUP_CONCAT(DISTINCT vnd_id) vndIds FROM vendors WHERE vnd_active > 0 AND vnd_ref_code = :refCode";
//		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * @param integer $vendorId
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function getLowRatingCabDriver($vendorId = null, $type = DBUtil::ReturnType_Provider)
	{
		if ($vendorId > 0)
		{
			$sqlSearch		 = " AND vendors.vnd_id=:vndId";
			$params["vndId"] = $vendorId;
		}
		$sql = "SELECT
				vendors.vnd_id,
				vendors.vnd_code,
				vendors.vnd_name,
				TRIM(',' FROM GROUP_CONCAT(DISTINCT IF(driver_stats.drs_drv_overall_rating<=3,drivers.drv_id,''))) AS drv_id,
				TRIM(',' FROM GROUP_CONCAT(DISTINCT IF(vehicles.vhc_overall_rating<=3,vehicles.vhc_id,''))) AS vhc_id
				FROM `vendors`
				JOIN `vendor_driver` ON vendor_driver.vdrv_vnd_id = vendors.vnd_id AND vendor_driver.vdrv_active=1
				JOIN `drivers` ON drivers.drv_id = vendor_driver.vdrv_drv_id AND drivers.drv_active=1 AND drivers.drv_is_freeze = 0 AND drivers.drv_approved = 1
				JOIN `driver_stats` ON driver_stats.drs_drv_id = drivers.drv_id AND drs_active=1
				JOIN `vendor_vehicle` on vendor_vehicle.vvhc_vnd_id = vendors.vnd_id AND vendor_vehicle.vvhc_active=1
				JOIN `vehicles` ON vehicles.vhc_id = vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_active=1
				WHERE 1
				AND vendors.vnd_active = 1 
				AND (driver_stats.drs_drv_overall_rating<=3 OR vhc_overall_rating<=3)";
		$sql .= $sqlSearch;
		$sql .= " GROUP BY vendors.vnd_id";
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, array(
				"totalItemCount" => $count,
				"params"		 => $params,
				'db'			 => DBUtil ::SDB3(),
				"pagination"	 => array("pageSize" => 100),
				'sort'			 => array('attributes'	 => array('vnd_id', 'vnd_code'),
					'defaultOrder'	 => '')
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return LIST
	 */
	public static function getNameBidAmountById($bkgId)
	{
		$params		 = ['bkgId' => $bkgId];
		$pageSize	 = 25;
		$sql		 = "SELECT 
				booking_vendor_request.bvr_bid_amount,
				booking_vendor_request.bvr_created_at,
				booking_vendor_request.bvr_accepted_at,
				vendors.vnd_name,
				vendors.vnd_code
				FROM  `booking_vendor_request`
				INNER JOIN `vendors` ON vendors.vnd_id = booking_vendor_request.bvr_vendor_id
				WHERE booking_vendor_request.bvr_booking_id = :bkgId 
				AND booking_vendor_request.bvr_accepted = 1";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['bvr_bid_amount', 'vnd_name'],
				'defaultOrder'	 => ''], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	/**
	 * function used for showing all related vendor ids
	 * @param type $vndid
	 * @return type
	 */
	public static function relatedVndIds($vndid)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')
						FROM   vendors v1
						       INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
						       INNER JOIN vendors v3 ON v3.vnd_id = v2.vnd_ref_code
						WHERE  v1.vnd_id = $vndid";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		return $vndIds;
	}

	/**
	 * 
	 * @param Vendors $model
	 * @return LIST
	 */
	public static function getDCOLinkAttachmentData($model, $qry = [])
	{
		$params		 = [];
		$dateRange	 = '';
		$where		 = '';

		$groupBy = $qry['groupvar'];
		$region	 = $qry['vnd_region'];
		$state	 = $qry['vnd_state'];

		if (!$model->from_date || !$model->to_date)
		{
			$dateRange = "  AND vnd.vnd_create_date > DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) ";
		}
		else
		{
			$fromDate	 = $model->from_date;
			$toDate		 = $model->to_date;
			$dateRange	 = " AND vnd.vnd_create_date<= '$toDate 23:59:59' AND vnd.vnd_create_date>= '$fromDate 00:00:00' ";
		}

		if (count($qry['vnd_zone']) > 0)
		{
			$zonesStr	 = implode(",", $qry['vnd_zone']);
			$where		 .= " AND z.zon_id IN ($zonesStr) ";
		}
		if ($region != '' || $state != '')
		{
			if ($region != '')
			{
				if (is_array($region) && array_search("4", $region) !== false && array_search("7", $region) === false)
				{
					$region[] = "7";
				}
				$region		 = ($region == '4') ? '4,7' : $region;
				$strRegion	 = implode(',', $region);
				$where		 .= " AND stt.stt_zone IN ($strRegion) ";
			}
			if ($state != '')
			{
				$strState	 = implode(',', $state);
				$where		 .= " AND stt.stt_id IN ($strState) ";
			}
		}

		$sql = "SELECT DATE_FORMAT(vnd.vnd_create_date, '%Y-%m-%d') date,
			DATE_FORMAT(vnd.vnd_create_date, '%Y-%V') week,
			DATE_FORMAT(vnd.vnd_create_date, '%Y-%m') month,
			ctt_city,cty_name, stt.stt_name, z.zon_id,z.zon_name, 
			'$groupBy' groupType,
			COUNT(DISTINCT vnd_id) totSignup, 
			COUNT(DISTINCT IF(vnd.vnd_is_dco=1 AND cpr.cr_is_vendor >0 AND cpr.cr_is_driver IS NULL,vnd_id,NULL)) totDCO,
			COUNT(DISTINCT IF(vnd.vnd_registered_platform=1,vnd_id,NULL)) dcoLink,   
			COUNT(DISTINCT IF(vnd.vnd_active=1,vnd_id,NULL)) approved, 
			COUNT(DISTINCT IF(vnd.vnd_active=3 OR vnd.vnd_active=4,vnd_id,NULL)) pending,
			COUNT(DISTINCT IF(vnd.vnd_active=0 OR vnd.vnd_active=2,vnd_id,NULL)) rejected,
			COUNT(DISTINCT IF(vnd.vnd_active=1 AND vnd.vnd_is_dco=1 AND cpr.cr_is_vendor >0 AND cpr.cr_is_driver IS NULL,vnd_id,NULL)) approvedDCO, 
			COUNT(DISTINCT IF((vnd.vnd_active=3 OR vnd.vnd_active=4) AND vnd.vnd_is_dco=1 AND cpr.cr_is_vendor >0 AND cpr.cr_is_driver IS NULL,vnd_id,NULL)) pendingDCO,
			COUNT(DISTINCT IF((vnd.vnd_active=0 OR vnd.vnd_active=2) AND vnd.vnd_is_dco=1 AND cpr.cr_is_vendor >0 AND cpr.cr_is_driver IS NULL,vnd_id,NULL)) rejectedDCO,
			COUNT(DISTINCT IF(apt_id IS NOT NULL,vnd_id,NULL)) isLoggedIn,
			COUNT(DISTINCT IF(bkg_id IS NOT NULL,bkg_id,NULL)) bkgCnt,
			COUNT(DISTINCT IF(apt_id IS NOT NULL AND apt.apt_platform=7 
					AND apt.apt_last_login>DATE_SUB(NOW(), INTERVAL 24 HOUR),vnd_id,NULL)) lastLogin24Hour 
			FROM `vendors` vnd 
			INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id 
				AND cpr.cr_status = 1 
			INNER JOIN contact ctt on ctt.ctt_id = cpr.cr_contact_id
			INNER JOIN cities c1 ON c1.cty_id=ctt_city AND c1.cty_active=1 
			INNER JOIN states stt ON stt.stt_id=c1.cty_state_id AND stt.stt_active = '1'
			INNER JOIN zone_cities zc ON zc.zct_cty_id=ctt_city 
				AND zc.zct_active=1
			INNER JOIN zones z ON z.zon_id=zc.zct_zon_id 
			LEFT JOIN booking_cab bcb ON bcb.bcb_vendor_id=vnd_id
			LEFT JOIN booking bkg ON bkg.bkg_bcb_id=bcb.bcb_id 
				AND bkg.bkg_status IN (3,5,6,7) 
				AND bkg.bkg_pickup_date>vnd_create_date
			LEFT JOIN app_tokens apt ON apt.apt_entity_id=vnd_id 
				AND apt.apt_user_type=2 
				AND apt.apt_last_login>vnd_create_date  
			WHERE 1
				$where
				$dateRange
				AND vnd.vnd_registered_platform=1				
			GROUP BY $groupBy ";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [],
				'defaultOrder'	 => 'date DESC'], 'pagination'	 => ['pageSize' => 60],
		]);
		return $dataprovider;
	}

	/**
	 * This function is used to send notifications for the event Cab/Driver Assigned
	 * @param string $tripId
	 * @return boolean
	 */
	public static function notifyAssignVendor($tripId, $isSchedule = 0, $schedulePlatform = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::trace("TripId: $tripId");
		$success = false;
		if ($tripId == '')
		{
			Logger::trace("TripId skipAll: $tripId");
			goto skipAll;
		}
		$model = BookingCab::model()->findByPk($tripId);
		if (!$model)
		{
			Logger::trace("TripId model skipall");
			goto skipAll;
		}
		if ($model->bookings[0]->bkg_booking_id == null)
		{
			Logger::trace("Booking  model skipall: {$model->bookings[0]->bkg_booking_id}");
			goto skipAll;
		}

		$remainingWorkingHrs = BookingCab::model()->getRemainingWorkingHours($tripId);
		$nowTime			 = DBUtil::getCurrentTime();
		if ($remainingWorkingHrs['hours'] > 12)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 3 HOUR'));
		}
		else if ($remainingWorkingHrs['hours'] < 12 && $remainingWorkingHrs['hours'] >= 8)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 2 HOUR'));
		}
		else if ($remainingWorkingHrs['hours'] < 8 && $remainingWorkingHrs['hours'] >= 4)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 1 HOUR'));
		}
		else if ($remainingWorkingHrs['hours'] < 4)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 30 MINUTE'));
		}
		$contentParams = array();

		$contentParams['eventId']	 = 1;
		$contentParams['tripId']	 = $tripId;
		$contentParams['primaryId']	 = $tripId;
		$contentParams['endTime']	 = $endTime;
		$contactId					 = ContactProfile::getByEntityId($model->bcb_vendor_id, UserInfo::TYPE_VENDOR);
		$row						 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			Logger::trace("TripId phone skipall");
			goto skipAll;
		}
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $model->bcb_vendor_id, WhatsappLog::REF_TYPE_TRIP, $tripId, $model->bookings[0]->bkg_booking_id, $row['code'], $row['number'], null, 0, Booking::CODE_VENDOR_ASSIGNED, SmsLog::SMS_VENDOR_ASSIGNED);
		$eventScheduleParams = EventSchedule::setData($tripId, ScheduleEvent::TRIP_REF_TYPE, ScheduleEvent::BOOKING_CAB_DRIVER_ASSIGNMNET, "Cab/Driver Assigned", $isSchedule, CJSON::encode(array('tripId' => $tripId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(1, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				// whatsapp
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success						 = true;
				// sms
				$param							 = [];
				$param['blg_vendor_assigned_id'] = $model->bcb_vendor_id;
				$param['blg_driver_id']			 = $model->bcb_driver_id;
				$param['blg_vehicle_id']		 = $model->bcb_cab_id;
				$param['blg_booking_status']	 = $model->bookings[0]->bkg_status;
				$param['blg_ref_id']			 = $response['id'];
				BookingLog::model()->createLog($model->bookings[0]->bkg_id, "Sms sent to vendor for new assigned booking", UserInfo::getInstance(), BookingLog::SMS_SENT, null, $param);
			}
			else if ($response['success'] && $response['type'] == 3)
			{
				$success = true;
				// email
			}
			else if ($response['success'] && $response['type'] == 4)
			{
				$success	 = true;
				$msg1		 = BookingVendorRequest::showBidRankForWinner($tripId, $model->bcb_vendor_id);
				$payLoadData = ['tripId' => $tripId, 'EventCode' => Booking::CODE_VENDOR_ASSIGNED];
				AppTokens::model()->notifyVendor($model->bcb_vendor_id, $payLoadData, $msg1, "A new booking has been assigned");
			}
		}
		skipAll:
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	/**
	 * This function is used to send notifications  for unassigned Trip From Vendor Assigned
	 * @param string $bkgId
	 * @return boolean
	 */
	public static function unassignedTripFromVendor($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			goto skipAll;
		}

		$vndId		 = $bkgModel->bkgBcb->bcb_vendor_id;
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$row		 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			goto skipAll;
		}
		$fromCity			 = Cities::getShortNameByCity($bkgModel->bkgFromCity->cty_name);
		$toCity				 = Cities::getShortNameByCity($bkgModel->bkgToCity->cty_name);
		$date				 = date('d-m-Y', strtotime($bkgModel->bkg_pickup_date));
		$time				 = date('h:i A', strtotime($bkgModel->bkg_pickup_date));
		$bookingId			 = $bkgModel->bkg_booking_id;
		$tripType			 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
		$cabType			 = $bkgModel->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$datePickupDate		 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime			 = $datePickupDate->format('j/M/y h:i A');
		$fromCityName		 = $bkgModel->bkgFromCity->cty_name;
		$toCityName			 = $bkgModel->bkgToCity->cty_name;
		$tripDistance		 = $bkgModel->bkg_trip_distance . ' KM';
		$amtToCollect		 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;
		$contentParams		 = array(
			'bookingId'			 => Filter::formatBookingId($bookingId),
			'bookingtype'		 => $tripType,
			'cabType'			 => $cabType,
			'pickupDate'		 => $pickupTime,
			'pickUp'			 => $fromCityName,
			'drop'				 => $toCityName,
			'fromCity'			 => $fromCity,
			'toCity'			 => $toCity,
			'time'				 => $time,
			'date'				 => $date,
			'distance'			 => $tripDistance,
			'amountToCollect'	 => $amtToCollect,
			'eventId'			 => 14
		);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $bookingId, $row['code'], $row['number'], null, 0, null, SmsLog::REF_BOOKING_ID);
		$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::UNASSIGNED_TRIP_DETAILS_TO_VENDOR, "Unassigned trip details to vendor", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
		MessageEventMaster::processPlatformSequences(14, $contentParams, $receiverParams, $eventScheduleParams);
		$success			 = true;
		skipAll:
		return $success;
	}

	/**
	 * This function is used to send notifications  for vendor dependency boost
	 * @return boolean
	 */
	public static function vendorDependencyBoost($vndId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success	 = false;
		$contactId	 = ContactProfile::getByVendorId($vndId);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$contentParams		 = array('eventId' => 9);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, Booking::CODE_VENDOR_BROADCAST, null);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::DEPENDENCY_BOOST_TO_VENDOR, "vendor dependency boost", $isSchedule, CJSON::encode(array('vndId' => $vndId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(9, $contentParams, $receiverParams, $eventScheduleParams);
		$success			 = true;
		skipAll:
		return $success;
	}

	/**
	 * This function is used to send notifications  for vendor approval 
	 * @param string $vndId
	 * @return boolean
	 */
	public static function accountApproveVendor($vndId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success	 = false;
		$vndModel	 = Vendors::model()->findByPk($vndId);
		if (!$vndModel)
		{
			goto skipAll;
		}
		$contactId		 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$contactModel	 = Contact::model()->getContactDetails($contactId);
		$objPhoneNumber	 = ContactPhone::getPrimaryNumber($contactId);
		$email			 = ContactEmail::getPrimaryEmail($contactId);
		$row			 = array();
		if ($objPhoneNumber)
		{
			$row['code']	 = $objPhoneNumber->getCountryCode();
			$row['number']	 = $objPhoneNumber->getNationalNumber();
		}
		if (!$row || empty($row))
		{
			goto skipAll;
		}
		$contentParams = array(
			'eventId'		 => 8,
			'userName'		 => trim($contactModel['ctt_business_name']) != null ? $contactModel['ctt_business_name'] : $contactModel['ctt_first_name'] . ' ' . $contactModel['ctt_last_name'],
			'link'			 => 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&amp;hl=en&amp;gl=US',
			'videoLink'		 => 'https://youtu.be/AfbwgIJN0H0',
			'appLink'		 => 'https://play.google.com/store/apps/details?id=com.gozocabs.vendor&hl=en',
			'driverAppLink'	 => 'https://play.google.com/store/apps/details?id=com.gozocabs.driver&hl=en_US'
		);

		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $row['code'], $row['number'], $email, 0, Booking::CODE_VENDOR_BROADCAST, SmsLog::SMS_APPROVE_VENDOR, $buttonUrl			 = null, $emailLayout		 = "mail2", $emailReplyTo		 = "vendors@gozocabs.in", $emailReplyName		 = 'Gozo Operator Team', $emailType			 = EmailLog::EMAIL_VENDOR_APPROVE, $emailUserType		 = EmailLog::Vendor, $emailRefType		 = EmailLog::REF_VENDOR_ID, $emailRefId			 = null, $emailLogInstance	 = EmailLog::SEND_ACCOUNT_EMAIL);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_APPROVED, "Vendor Approval", $isSchedule, CJSON::encode(array('vndId' => $vndId)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(8, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success	 = true;
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor approved manually.", $userInfo, VendorsLog::VENDOR_APPROVE, false, false);
				break;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success	 = true;
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor approved manually.", $userInfo, VendorsLog::VENDOR_APPROVE, false, false);
				break;
			}
			else if ($response['success'] && $response['type'] == 3)
			{
				$success	 = true;
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor approved manually.", $userInfo, VendorsLog::VENDOR_APPROVE, false, false);
				break;
			}
			else if ($response['success'] && $response['type'] == 4)
			{
				$success	 = true;
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor approved manually.", $userInfo, VendorsLog::VENDOR_APPROVE, false, false);
				break;
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * @param integer $vndId
	 * @param integer $isSchedule
	 * @param integer $schedulePlatform
	 * @return boolean
	 */
	public static function notifyToAccountBlocked($vndId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			/* @var $model Vendors */
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId		 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$cttModel		 = Contact::model()->findByPk($contactId);
		$userName		 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));
		$contentParams	 = ['eventId' => 16, 'name' => $userName];
		$row			 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			goto skipAll;
		}

		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $model->vnd_id, null, null, null, $row['code'], $row['number'], null, 0, Booking::CODE_VENDOR_BROADCAST, SmsLog::Vendor);
		$eventScheduleParams = EventSchedule::setData($model->vnd_id, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_ACCOUNT_BLOCKED, "Account Blocked", $isSchedule, CJSON::encode(array('vndId' => $model->vnd_id)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(16, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				break;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
				break;
			}
			else if ($response['success'] && $response['type'] == 4)
			{
				$success = true;
				break;
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * @param integer $vndId
	 * @param integer $isSchedule
	 * @param integer $schedulePlatform
	 * @return boolean
	 */
	public static function notifyToAccountUnblocked($vndId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			/* @var $model Vendors */
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId		 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$cttModel		 = Contact::model()->findByPk($contactId);
		$userName		 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));
		$contentParams	 = ['eventId' => 17, 'name' => $userName];
		$row			 = ContactPhone::getNumber($contactId);
		if (!$row || empty($row))
		{
			goto skipAll;
		}

		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $model->vnd_id, null, null, null, $row['code'], $row['number'], null, null, Booking::CODE_VENDOR_BROADCAST, SmsLog::VENDOR_ADMINISTRATIVE_UNFREEZED);
		$eventScheduleParams = EventSchedule::setData($model->vnd_id, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_ACCOUNT_UNBLOCKED, "Account Unblocked", $isSchedule, CJSON::encode(array('vndId' => $model->vnd_id)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(17, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				break;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success = true;
				break;
			}
			else if ($response['success'] && $response['type'] == 4)
			{
				$success = true;
				break;
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * This function is used to send notifications  for Vendor Dues Waived Off
	 * @return None
	 */
	public static function notifyVendorDuesWaivedOff($vndId, $phoneNo, $isSchedule = 0, $schedulePlatform = null)
	{
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$contentParams		 = array('eventId' => 19);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, null, null);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_DUES_WAIVED_OFF, "vendor dues waived off", $isSchedule, CJSON::encode(array('vndId' => $vndId)), 10, $schedulePlatform);
		MessageEventMaster::processPlatformSequences(19, $contentParams, $receiverParams, $eventScheduleParams);
	}

	/**
	 * This function is used to send notifications  for freeze/unfreeze Vendor
	 * @return None
	 */
	public static function notifyFreezeUnfreezeVendor($vndId, $event, $desc, $status, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));
		if ($userName == null || trim($userName) == "")
		{
			goto skipAll;
		}
		$type						 = $status == 1 ? "freezed" : "un-freezed";
		$contentParams				 = array("userName" => $userName, "type" => $type);
		$receiverParams				 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, Booking::CODE_VENDOR_BROADCAST, SmsLog::VENDOR_FROZEN);
		$scheduleEventType			 = $status == 1 ? ScheduleEvent::VENDOR_FREEZE : ScheduleEvent::VENDOR_UNFREEZE;
		$scheduleEventMessage		 = $status == 1 ? "Vendor Freeze" : "Vendor UnFreeze";
		$messageEventId				 = $status == 1 ? 22 : 23;
		$contentParams['eventId']	 = $messageEventId;
		$eventScheduleParams		 = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, $scheduleEventType, $scheduleEventMessage, $isSchedule, CJSON::encode(array('vndId' => $vndId)), 10, $schedulePlatform);
		$responseArr				 = MessageEventMaster::processPlatformSequences($messageEventId, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success	 = true;
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, $desc, $userInfo, $event, false, false);
				break;
			}
			else if ($response['success'] && $response['type'] == 2)
			{
				$success	 = true;
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, $desc, $userInfo, $event, false, false);
				break;
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * This function is used to send notifications  for freeze/unfreeze Vendor
	 * @return None
	 */
	public static function notifyVendorPaymentRelease($vndId, $amount, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = (!empty(trim($cttModel->ctt_business_name)) ? trim($cttModel->ctt_business_name) : ($cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name));
		if ($userName == null || trim($userName) == "")
		{
			goto skipAll;
		}
		$contentParams		 = array('eventId' => 26, "userName" => $userName, "amount" => "Rs. " . $amount);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, Booking::CODE_VENDOR_BROADCAST, null, null);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_PAYMENT_RELEASE, "vendor payment release", $isSchedule, CJSON::encode(array('vndId' => $vndId, 'amount' => $amount)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(26, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				break;
			}
			else if ($response['success'] && $response['type'] == 4)
			{
				$success = true;
				break;
			}
		}
		skipAll:
		return $success;
	}

	public function getassignList($vendorId, $status, $time = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$uberAgentId = Yii::app()->params['uberAgentId'];
		$pickup		 = '';

		if ($time != '')
		{
			$pickup = ' AND bkg.bkg_pickup_date <= DATE_ADD(NOW(), INTERVAL 960 MINUTE)';
		}
		/* $qry	 = "SELECT DISTINCT (bkg.bkg_id),bcb.bcb_id,bcb.bcb_driver_id,bcb.bcb_cab_id,
		  GetUnassignPenaltySlabs(bkgtrail.bkg_assigned_at, bkg.bkg_pickup_date, bcb.bcb_vendor_amount, bcb.bcb_assign_mode, vrs_dependency) as cancelSlabs
		  FROM `booking_cab` bcb
		  INNER JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 AND bkg.bkg_active = 1
		  INNER JOIN booking_trail bkgtrail ON bkg.bkg_id = bkgtrail.btr_bkg_id
		  LEFT JOIN vendor_stats ON vrs_vnd_id=bcb.bcb_vendor_id
		  WHERE
		  bcb.bcb_vendor_id = '$vendorId' AND bkg.bkg_status IN ('$status') $pickup
		  GROUP BY
		  bkg.bkg_id
		  ORDER BY
		  bkg.bkg_pickup_date"; */

		$qry		 = "SELECT bcb_id,bkg.bkg_pickup_date,GetUnassignPenaltySlabs(bkgtrail.bkg_assigned_at, bkg.bkg_pickup_date, bcb.bcb_vendor_amount, bcb.bcb_assign_mode, vrs_dependency) as cancelSlabs
						FROM `booking_cab` bcb 
						INNER JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 
						INNER JOIN booking_trail bkgtrail ON bkg.bkg_id = bkgtrail.btr_bkg_id
						INNER JOIN booking_track bkgtrack ON bkgtrack.btk_bkg_id= bkg.bkg_id AND bkgtrack.bkg_ride_complete=0
						LEFT JOIN vendor_stats ON vrs_vnd_id=bcb.bcb_vendor_id
						WHERE bcb_vendor_id = $vendorId AND bkg.bkg_status IN ('$status') GROUP BY bcb_id ORDER BY bkg_pickup_date DESC";
		Logger::trace($qry);
		$recordset	 = DBUtil::query($qry, DBUtil::SDB());
		return $recordset;
	}

	public static function updateVendorToDCO($vndId = 0)
	{
		$where	 = '';
		$param	 = [];
		if ($vndId > 0)
		{
			$where			 = " AND vnd.vnd_ref_code =:vndId";
			$param['vndId']	 = $vndId;
		}
		$sql = "UPDATE vendors set vnd_is_dco=1 WHERE vnd_ref_code IN (
			SELECT vnd_ref_code from (
			SELECT vnd.vnd_ref_code, vnd.vnd_name, COUNT(DISTINCT drv.drv_ref_code) countDrv, MAX(vnd.vnd_is_dco) as isDCO,
				GROUP_CONCAT(DISTINCT ctt.ctt_ref_code) as drvContactId, GROUP_CONCAT(DISTINCT ctt1.ctt_ref_code) as vndContactId
			FROM `vendors` vnd 
				JOIN vendor_driver vdrv ON vdrv.vdrv_vnd_id = vnd.vnd_id AND vdrv.vdrv_active=1
				INNER JOIN contact_profile cp1 ON cp1.cr_is_vendor=vnd.vnd_ref_code
				INNER JOIN contact ctt1 ON cp1.cr_contact_id=ctt1.ctt_id
				INNER JOIN drivers drv ON drv.drv_id = vdrv.vdrv_drv_id AND drv.drv_active=1
				INNER JOIN drivers drv1 ON drv.drv_ref_code=drv1.drv_id AND drv1.drv_active=1
				INNER JOIN contact_profile cp ON cp.cr_is_driver=drv.drv_ref_code AND cp.cr_status=1
				INNER JOIN contact ctt ON cp.cr_contact_id=ctt.ctt_id
			WHERE vnd.vnd_active=1 $where
			GROUP BY vnd.vnd_ref_code 
			HAVING countDrv=1 AND isDCO=0 AND drvContactId=vndContactId)a)";

		$resQ = DBUtil::execute($sql, $param);
		return $resQ;
	}

	public static function updateDCOToVendor($vndId = 0)
	{
		$where	 = '';
		$param	 = [];
		if ($vndId > 0)
		{
			$where			 = " AND vnd.vnd_ref_code =:vndId";
			$param['vndId']	 = $vndId;
		}
		$sql = "UPDATE vendors set vnd_is_dco=0 WHERE vnd_ref_code IN (
		SELECT vnd_ref_code from (
			SELECT vnd.vnd_ref_code, vnd.vnd_name, COUNT(DISTINCT drv.drv_ref_code) countDrv, 
			MAX(vnd.vnd_is_dco) as isDCO, ctt.ctt_name AS drvContact, ctt1.ctt_name AS vndContact, 
			ctt.ctt_first_name as drvFName, ctt1.ctt_first_name AS vndFName,
			GROUP_CONCAT(DISTINCT ctt.ctt_ref_code) as drvContactId, GROUP_CONCAT(DISTINCT ctt1.ctt_ref_code) as vndContactId
		FROM `vendors` vnd 
			JOIN vendor_driver vdrv ON vdrv.vdrv_vnd_id = vnd.vnd_id AND vdrv.vdrv_active=1
			INNER JOIN contact_profile cp1 ON cp1.cr_is_vendor=vnd.vnd_ref_code
			INNER JOIN contact ctt1 ON cp1.cr_contact_id=ctt1.ctt_id
			INNER JOIN drivers drv ON drv.drv_id = vdrv.vdrv_drv_id AND drv.drv_active=1
			INNER JOIN drivers drv1 ON drv.drv_ref_code=drv1.drv_id AND drv1.drv_active=1
			INNER JOIN contact_profile cp ON cp.cr_is_driver=drv.drv_ref_code AND cp.cr_status=1
			INNER JOIN contact ctt ON cp.cr_contact_id=ctt.ctt_id
			WHERE vnd.vnd_active=1 $where
			GROUP BY vnd.vnd_ref_code 
			HAVING countDrv=1 AND isDCO=1 AND drvContactId<>vndContactId AND drvContact<>vndContact 
				AND SOUNDEX(drvContact) NOT LIKE CONCAT('%',SOUNDEX(vndContact),'%') AND SOUNDEX(vndContact) NOT LIKE CONCAT('%',SOUNDEX(drvContact),'%')
		AND vndContact NOT LIKE CONCAT('%',drvContact,'%') AND  drvContact NOT LIKE CONCAT('%',vndContact,'%') AND drvFName NOT LIKE CONCAT('%',vndFName,'%') 
		AND  vndFName NOT LIKE CONCAT('%',drvFName,'%'))a)";

		$resQ = DBUtil::execute($sql, $param);
		return $resQ;
	}

	public function actionGetDuplicateContacts($start = 0)
	{
		$check = Filter::checkProcess("system getDuplicateContacts");
		if (!$check)
		{
			return;
		}
		Contact::getDuplicateContacts($start);
	}

	public static function getBalanceDetailsByRange($fromDate, $toDate, $vndId = 0)
	{
		$whereDate			 = " AND (act_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59') ";
		$whereOpeningDate	 = " AND act_date < '{$fromDate} 00:00:00' ";
		$whereClosingDate	 = " AND act_date <= '{$toDate} 23:59:59' ";

		if ($vndId > 0)
		{
			$whereDate .= " AND atd.adt_trans_ref_id=$vndId";
		}


		// Transaction VendorIDs
		$sqlVndIds = "SELECT GROUP_CONCAT(DISTINCT atd.adt_trans_ref_id) vndIds 
						FROM account_trans_details atd 
						INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 
							AND atd.adt_status=1 AND atd.adt_ledger_id = 14 
						INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 
							AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))
							{$whereDate} ";

		$vndIds		 = DBUtil::queryScalar($sqlVndIds, DBUtil::SDB());
		$rowVendors	 = false;
		if (!$vndIds)
		{
			goto skipAll;
		}


		$whereFromLedger = " AND atd.adt_ledger_id = 14 AND atd.adt_trans_ref_id IN ({$vndIds}) ";

		// Opening
		$sqlOpening		 = "";
		$openingTable	 = "TmpOpeningBalance_" . rand();
		#$openingTable	 = "TmpOpeningBalance_152730615";
		#$sqlOpening		 = "DROP TABLE IF EXISTS {$openingTable}; CREATE TABLE {$openingTable} ";
		$sqlOpening		 .= "SELECT atd.adt_trans_ref_id, 
								SUM(IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount, atd.adt_amount*-1)) AS opening 
							FROM account_trans_details atd 
							INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
								{$whereFromLedger} 
							INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1  
								AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
								{$whereOpeningDate} 
							GROUP BY atd.adt_trans_ref_id 
							HAVING opening<>0";
		#DBUtil::execute($sqlOpening);
		DBUtil::dropTempTable($openingTable);
		DBUtil::createTempTable($openingTable, $sqlOpening);

		// Closing
		$sqlClosing		 = "";
		$closingTable	 = "TmpClosingBalance_" . rand();
		#$closingTable	 = "TmpClosingBalance_252730617";
		#$sqlClosing	 = "DROP TABLE IF EXISTS {$closingTable}; CREATE TABLE {$closingTable} ";
		$sqlClosing		 .= "SELECT atd.adt_trans_ref_id, 
								SUM(IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount, atd.adt_amount*-1)) AS closing 
							FROM account_trans_details atd 
							INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
								{$whereFromLedger} 
							INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1  
								AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
								{$whereClosingDate} 
							GROUP BY atd.adt_trans_ref_id 
							HAVING closing<>0";
		#DBUtil::execute($sqlClosing);
		DBUtil::dropTempTable($closingTable);
		DBUtil::createTempTable($closingTable, $sqlClosing);

		// Booking
		$sqlBooking		 = "";
		$bookingTable	 = "TmpBookingDetails_" . rand();
		#$bookingTable	 = "TmpBookingDetails_352730616";
		#$sqlBooking	 = "DROP TABLE IF EXISTS {$bookingTable}; CREATE TABLE {$bookingTable} ";
		$sqlBooking		 .= "SELECT bcb_vendor_id, SUM(bcb_vendor_amount) vendor_amount, SUM(bkg_gozo_amount) gozo_amount, 
						SUM(bkg_net_advance_amount) advance_amount, SUM(bkg_total_amount) total_amount, 
						COUNT(DISTINCT bkg_id) booking_count, GROUP_CONCAT(DISTINCT bkg_id SEPARATOR ' | ') booking_ids 
						FROM `booking` bkg 
						INNER JOIN booking_invoice ON biv_bkg_id=bkg.bkg_id AND bkg.bkg_active=1 
						INNER JOIN booking_cab bcb ON bcb_id = bkg.bkg_bcb_id 
						WHERE 1 AND bkg.bkg_status IN (6,7) AND bkg.bkg_pickup_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59' 
						GROUP BY bcb_vendor_id";
		#DBUtil::execute($sqlBooking);
		DBUtil::dropTempTable($bookingTable);
		DBUtil::createTempTable($bookingTable, $sqlBooking);

		// Penalty
		$sqlPenalty		 = "";
		$penaltyTable	 = "TmpPenaltyDetails_" . rand();
		#$penaltyTable	 = "TmpPenaltyDetails_452730615";
		#$sqlPenalty	 = "DROP TABLE IF EXISTS {$penaltyTable}; CREATE TABLE {$penaltyTable} ";
		$sqlPenalty		 .= "SELECT atd1.adt_trans_ref_id, 
								SUM(IF(abs(atd1.adt_amount)<abs(atd.adt_amount), atd1.adt_amount, atd.adt_amount*-1)) AS penalty 
							FROM account_trans_details atd 
							INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
								AND atd.adt_ledger_id = 28 
							INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 
								AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
								AND (act_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59') AND atd1.adt_type = 2 
							GROUP BY atd1.adt_trans_ref_id 
							HAVING penalty <> 0";
		#DBUtil::execute($sqlPenalty);
		DBUtil::dropTempTable($penaltyTable);
		DBUtil::createTempTable($penaltyTable, $sqlPenalty);

		// Payment
		$sqlPayment		 = "";
		$paymentTable	 = "TmpPaymentDetails_" . rand();
		#$paymentTable	 = "TmpPaymentDetails_552730616";
		#$sqlPayment	 = "DROP TABLE IF EXISTS {$paymentTable}; CREATE TABLE {$paymentTable} ";
		$sqlPayment		 .= "SELECT atd.adt_trans_ref_id, 
								SUM(IF(atd1.adt_amount<0, atd1.adt_amount*-1, 0)) AS payment_made,
								SUM(IF(atd1.adt_amount>0, atd1.adt_amount, 0)) AS payment_received 
							FROM account_trans_details atd 
							INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_status=1 
								AND atd.adt_ledger_id = 14  
							INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1  
								AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
								AND (act_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59') AND atd1.adt_ledger_id IN (1,16,17,18,19,20,21,23,29,30,31,32,39,42,46,53,54,55,58) 
							GROUP BY atd.adt_trans_ref_id 
							HAVING (payment_made <> 0 OR payment_received <> 0)"; //831
		#DBUtil::execute($sqlPayment);
		DBUtil::dropTempTable($paymentTable);
		DBUtil::createTempTable($paymentTable, $sqlPayment);

		$sqlVendor	 = "SELECT vnd_ref_code, vnd_code, o.opening, c.closing, tmpBkg.vendor_amount, tmpBkg.gozo_amount, tmpBkg.advance_amount, 
						tmpBkg.total_amount, p.penalty, pay.payment_made, pay.payment_received, tmpBkg.booking_count, tmpBkg.booking_ids 
						FROM vendors vnd 
						LEFT JOIN {$bookingTable} as tmpBkg ON vnd.vnd_ref_code = tmpBkg.bcb_vendor_id 
						LEFT JOIN {$penaltyTable} as p ON vnd.vnd_ref_code = p.adt_trans_ref_id 
						LEFT JOIN {$paymentTable} as pay ON vnd.vnd_ref_code = pay.adt_trans_ref_id 
						LEFT JOIN {$openingTable} as o ON vnd.vnd_ref_code = o.adt_trans_ref_id 
						LEFT JOIN {$closingTable} as c ON vnd.vnd_ref_code = c.adt_trans_ref_id 
						WHERE vnd_ref_code IN ({$vndIds}) AND vnd_ref_code = vnd_id 
						ORDER BY vnd_ref_code";
		$rowVendors	 = DBUtil::query($sqlVendor, DBUtil::SDB());
		skipAll:
		return $rowVendors;
	}

	public static function spInfo($vndId, $drvId)
	{
		$data['isVendor']	 = VendorStats::model()->statusCheckDocument($vndId);
		$data['isCar']		 = VendorStats::vehicleStatus($vndId);
		if ($drvId > 0)
		{
			$result = Drivers::checkDrvStatus($drvId);

			$data['isDriver'] = ($result['licenseDoc'] > 0 ? 1 : 0);
		}
		$vndStatsModel		 = VendorStats::model()->getbyVendorId($vndId);
		$data['oustanding']	 = $vndStatsModel->vrs_outstanding;
		$data['appVersion']	 = self::getApkVersion($vndId);
		return $data;
	}

	public static function checkVendorContact($vndId, $contactId)
	{
		$sqlContact = "SELECT ctt.ctt_id
						FROM vendors vnd 
						INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id 
						INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1 
						INNER JOIN contact AS ctt ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1  
						INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
						WHERE vnd.vnd_ref_code = {$vndId} AND ctt.ctt_id = {$contactId} AND ctp.cr_is_vendor = {$vndId} 
						GROUP BY vnd.vnd_ref_code ";

		return $rowContact = DBUtil::queryScalar($sqlContact);
	}

	public static function getRelatedByCttIds($relCttIds)
	{
		$sql	 = "SELECT GROUP_CONCAT(CONCAT_WS(',',vnd.vnd_id,vnd.vnd_ref_code  )) 
				FROM contact_profile cp 
				INNER JOIN vendors vnd1 ON vnd1.vnd_id = cp.cr_is_vendor 
				INNER JOIN vendors vnd ON vnd.vnd_ref_code = vnd1.vnd_ref_code
				WHERE cp.cr_contact_id IN ({$relCttIds}) 
				AND cp.cr_status = 1 
				AND cp.cr_is_vendor IS NOT NULL";
		$relIds	 = \DBUtil::queryScalar($sql, DBUtil::SDB());

		$relIdArr = explode(',', $relIds);

		$distinctRelIds = implode(',', array_unique($relIdArr));
		return $distinctRelIds;
	}

	public static function getPrimaryByIds($vndIds, $onlyPrimary = true)
	{

		$where = '';
		if ($onlyPrimary)
		{
			$where = ' AND vnd.vnd_active IN (1,2,3,4)';
		}
		$sql = "SELECT DISTINCT vnd.vnd_id,vnd.vnd_ref_code,ctt.ctt_id,ctt.ctt_ref_code, 
			IF(ctt.ctt_id =ctt.ctt_ref_code,1,0) contactWeight	,
			IF(vnd.vnd_id =vnd.vnd_ref_code,1,0) selfWeight	, 
			IF((ctt.ctt_aadhaar_no <> ''  AND ctt.ctt_aadhaar_no IS NOT NULL 
				AND LENGTH(ctt.ctt_aadhaar_no) >=12 ),2,0) hasAdhaar,
			IF((ctt.ctt_voter_no <> ''  AND ctt.ctt_voter_no IS NOT NULL 
				AND LENGTH(ctt.ctt_voter_no) >=8 ),1,0) hasVoter,
			IF((ctt.ctt_license_no <> ''  AND ctt.ctt_license_no IS NOT NULL 
				AND LENGTH(ctt.ctt_license_no) >=8 ),4,0) hasLicense, 
			IF((ctt.ctt_pan_no <> ''  AND ctt.ctt_pan_no IS NOT NULL 
				AND LENGTH(ctt.ctt_pan_no) =10 ),2,0) hasPan,
			IF(TRIM(ctt.ctt_bank_account_no)<> '' AND ctt.ctt_bank_account_no IS NOT NULL,1,0) hasBankRef,
			IF(ctt.ctt_license_exp_date > CURRENT_DATE AND doclicence.doc_status =1 
				AND ctt.ctt_license_exp_date IS NOT NULL
				AND doclicence.doc_id IS NOT NULL,3,0) hasValidLicense,
			IF(docvoter.doc_status =1 AND docvoter.doc_id IS NOT NULL ,1,0) hasValidVoter,
			IF(docaadhar.doc_status =1 AND docaadhar.doc_id IS NOT NULL ,1,0) hasValidAdhaar,
			IF(docpan.doc_status =1 AND docpan.doc_id IS NOT NULL ,2,0) hasValidPan,
			IF(docpolicever.doc_status =1 AND docpolicever.doc_id IS NOT NULL ,1,0) hasValidPV,		
			vnd_active,
			CASE
				WHEN  vnd_active =1 THEN 6
				WHEN  vnd_active =4 THEN 4	
				WHEN  vnd_active =3 THEN 3
				WHEN  vnd_active =2 THEN 2
				ELSE 0
			END as activeRank,
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
				WHEN eml.eml_is_primary=1 AND eml.eml_is_verified=1 
					AND eml.eml_verified_date= eml.eml_verified_date THEN 6
				WHEN eml.eml_is_primary=1 AND eml.eml_is_verified=1 THEN 4
				WHEN eml.eml_is_verified=1 THEN 3
				WHEN eml.eml_is_primary=1 THEN 2
				WHEN eml_id IS NOT NULL THEN 1
				ELSE 0
			END as emailRank 
		FROM vendors vnd 
		INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id 
		AND cpr.cr_status = 1
		INNER JOIN contact ctt ON ctt.ctt_id = cpr.cr_contact_id 
		AND ctt.ctt_active =1
		LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_active = 1
		LEFT JOIN contact_email eml ON eml.eml_contact_id = ctt.ctt_id AND eml.eml_active = 1
		LEFT JOIN document as docvoter ON ctt.ctt_voter_doc_id = docvoter.doc_id 
			AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 
			AND docvoter.doc_file_front_path IS NOT NULL
		LEFT JOIN document as docaadhar ON ctt.ctt_aadhar_doc_id = docaadhar.doc_id 
			AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 
			AND docaadhar.doc_file_front_path IS NOT NULL
		LEFT JOIN document as docpan ON ctt.ctt_pan_doc_id = docpan.doc_id 
			AND docpan.doc_type = 4 AND docpan.doc_active = 1 
			AND docpan.doc_file_front_path IS NOT NULL
		LEFT JOIN document as doclicence ON ctt.ctt_license_doc_id = doclicence.doc_id 
			AND doclicence.doc_type = 5 AND doclicence.doc_active = 1 
			AND doclicence.doc_file_front_path IS NOT NULL
		LEFT JOIN document as docpolicever ON ctt.ctt_police_doc_id = docpolicever.doc_id 
			AND  docpolicever.doc_type = 7 AND docpolicever.doc_active = 1 		
		WHERE vnd.vnd_ref_code IN ({$vndIds}) $where
		GROUP BY ctt.ctt_id,vnd.vnd_ref_code,vnd.vnd_id
		ORDER BY selfWeight DESC, contactWeight DESC,activeRank DESC,hasBankRef DESC, 
			(hasLicense+hasAdhaar+hasPan+hasVoter) DESC,
			(hasValidLicense +hasValidVoter+hasValidAdhaar+hasValidPan+hasValidPV) DESC,hasValidLicense DESC ";

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

	public static function getAllExpiryDocs()
	{
		$sql = "SELECT 
				vnd.vnd_id AS vendorIds,
				IF(ctt.ctt_license_exp_date>= CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59'),1,0) AS license_status
			FROM vendors vnd
				INNER JOIN contact_profile cop ON cop.cr_is_vendor=vnd.vnd_id AND cop.cr_status=1 
				INNER JOIN contact ctt ON ctt.ctt_id=cop.cr_contact_id AND ctt.ctt_active = 1
				INNER JOIN document doc ON ctt.ctt_license_doc_id=doc.doc_id  AND doc.doc_status = 1
			WHERE 1
			AND (ctt.ctt_license_exp_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59'))
			AND vnd.vnd_active = 1
			GROUP BY vnd.vnd_id ";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function notifyExpiryDocs($vndId, $fileType, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		if (!Filter::processPhoneNumber($number, $code))
		{
			goto skipAll;
		}
		$contentParams		 = array("fileType" => $fileType, 'eventId' => 38);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, null, null, null);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_EXPIRY_DOCS, "vendor expiry docs", $isSchedule, CJSON::encode(array('vndId' => $vndId, "fileType" => $fileType)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(38, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				break;
			}
		}
		skipAll:
		return $success;
	}

	public static function getAllRejectedDocs()
	{
		$sql = "SELECT
					vendors.vnd_id AS vendorIds,
					IF(docvoter.doc_id IS NOT NULL,0,1) AS voterId,
					IF(docaadhar.doc_id IS NOT NULL,0,1) AS aadharId,
					IF(docpan.doc_id IS NOT NULL,0,1) AS panId,
					IF(doclicence.doc_id IS NOT NULL,0,1) AS licenceId,
					IF(docpolicever.doc_id IS NOT NULL,0,1) AS policeverId
				FROM vendors
					INNER JOIN contact_profile AS cp ON cp.cr_is_vendor = vendors.vnd_id AND vendors.vnd_id = vendors.vnd_ref_code AND vnd_active > 0
					INNER JOIN contact AS contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active = 1
					LEFT JOIN document AS docvoter	ON contact.ctt_voter_doc_id = docvoter.doc_id AND contact.ctt_voter_doc_id > 0 AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status = 2
					LEFT JOIN document AS docaadhar ON	contact.ctt_aadhar_doc_id = docaadhar.doc_id AND contact.ctt_aadhar_doc_id > 0 AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status = 2 AND docaadhar.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
					LEFT JOIN document AS docpan    ON	contact.ctt_pan_doc_id = docpan.doc_id AND contact.ctt_pan_doc_id > 0 AND docpan.doc_type = 4 AND docpan.doc_active = 1 AND docpan.doc_status = 2 AND docpan.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
					LEFT JOIN document AS doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND contact.ctt_license_doc_id > 0 AND doclicence.doc_type = 5 AND doclicence.doc_active = 1 AND doclicence.doc_status = 2 AND doclicence.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
					LEFT JOIN document AS docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND contact.ctt_police_doc_id > 0 AND docpolicever.doc_type = 7 AND docpolicever.doc_active = 1 AND docpolicever.doc_status = 2 AND docpolicever.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
				WHERE 1 
				AND
				(
					(
						(docvoter.doc_id IS NOT NULL) AND docvoter.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
						AND
						(
							(
								docvoter.doc_file_front_path IS NOT NULL AND docvoter.doc_file_front_path != ''
							) 
							OR
							(
								docvoter.doc_file_back_path IS NOT NULL AND docvoter.doc_file_back_path != ''
							)
						)
					)
					OR
					(
						(docaadhar.doc_id IS NOT NULL) AND docaadhar.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
						AND
						(
							(
							  docaadhar.doc_file_front_path IS NOT NULL AND docaadhar.doc_file_front_path != ''
							) 
							OR
							(
								docaadhar.doc_file_back_path IS NOT NULL AND docaadhar.doc_file_back_path != ''
							)
						)
					) 
					OR
					(
						(docpan.doc_id IS NOT NULL) AND docpan.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
						AND
						(
							(
								docpan.doc_file_front_path IS NOT NULL AND docpan.doc_file_front_path != ''
							) 
							OR
							(
								docpan.doc_file_back_path IS NOT NULL AND docpan.doc_file_back_path != ''
							)
						)
					) 
					OR
					(
						(doclicence.doc_id IS NOT NULL) AND doclicence.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
							AND
							(
								(
									doclicence.doc_file_front_path IS NOT NULL AND doclicence.doc_file_front_path != ''
								) 
								OR
								(
									doclicence.doc_file_back_path IS NOT NULL AND doclicence.doc_file_back_path != ''
								)
							)
					) 
					OR
					(
						(
							docpolicever.doc_id IS NOT NULL
						)   
						AND docpolicever.doc_approved_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
						AND
						(
							docpolicever.doc_file_front_path IS NOT NULL AND docpolicever.doc_file_front_path != ''
						)
					)
				)
				GROUP BY vendors.vnd_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function notifyRejectedDocs($vndId, $fileType, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		if (!Filter::processPhoneNumber($number, $code))
		{
			goto skipAll;
		}
		$contentParams		 = array("fileType" => $fileType, 'eventId' => 39);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, null, null, null);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_REJECTED_DOCS, "vendor rejected docs", $isSchedule, CJSON::encode(array('vndId' => $vndId, "fileType" => $fileType)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(39, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				break;
			}
		}
		skipAll:
		return $success;
	}

	public static function getNotWorkingVendor()
	{
		$sql = "SELECT
					vnd_id,
					IF(MAX(bcb_created)>= CONCAT(DATE_SUB(CURDATE(),INTERVAL 30 DAY),' 23:59:59'),1,NULL) AS bcb_created,
					IF(MAX(bvr_created_at)>= CONCAT(DATE_SUB(CURDATE(),INTERVAL 30 DAY),' 23:59:59'),1,NULL) AS bvr_created_at
				FROM vendors
					INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id = vendors.vnd_id
					LEFT JOIN booking_cab ON booking_cab.bcb_vendor_id = vendors.vnd_id
					LEFT JOIN  booking_vendor_request ON booking_vendor_request.bvr_vendor_id = vendors.vnd_id
				WHERE 1 
					AND vendors.vnd_active = 1
					AND vrs_last_approve_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 30 DAY),' 00:00:00') AND  CONCAT(DATE_SUB(CURDATE(),INTERVAL 30 DAY),' 23:59:59')
				GROUP BY vnd_id  
				HAVING (bcb_created IS NULL AND bvr_created_at IS NULL)";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function notifyNotWorkingVendor($vndId, $isSchedule = 0, $schedulePlatform = null)
	{
		$success = false;
		if ($vndId > 0)
		{
			$model = Vendors::model()->findByPk($vndId);
		}
		if (!$model)
		{
			goto skipAll;
		}
		$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto skipAll;
		}
		Filter::parsePhoneNumber($phoneNo, $code, $number);
		if (!Filter::processPhoneNumber($number, $code))
		{
			goto skipAll;
		}
		$contentParams		 = array('eventId' => 40);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_VENDOR, $vndId, WhatsappLog::REF_TYPE_VENDOR, $vndId, null, $code, $number, null, 0, null, null, null);
		$eventScheduleParams = EventSchedule::setData($vndId, ScheduleEvent::VENDOR_REF_TYPE, ScheduleEvent::VENDOR_NOT_LOGIN, "vendor not login ", $isSchedule, CJSON::encode(array('vndId' => $vndId, "fileType" => $fileType)), 10, $schedulePlatform);
		$responseArr		 = MessageEventMaster::processPlatformSequences(40, $contentParams, $receiverParams, $eventScheduleParams);
		foreach ($responseArr as $response)
		{
			if ($response['success'] && $response['type'] == 1)
			{
				$success = true;
				break;
			}
		}
		skipAll:
		return $success;
	}

	/**
	 * This function is used to get  primary vendor id by contact id
	 * @param integer $cttId
	 * @return array
	 */
	public static function getVendorPrimaryEntitiesByContact($cttId)
	{
		$relCttIds = Contact::getRelatedIds($cttId);
		if ($relCttIds)
		{
			$primaryCtt	 = Contact::getPrimaryByIds($relCttIds);
			$cttId		 = (int) $primaryCtt['ctt_id'];
		}
		$relVendorList = Vendors::getRelatedByCttIds($relCttIds);
		if ($relVendorList)
		{
			$primaryVnd = Vendors::getPrimaryByIds($relVendorList);
		}
		$data = [
			'primaryContact' => $cttId,
			'cr_contact_id'	 => $cttId,
			'cr_is_vendor'	 => (int) $primaryVnd['vnd_id'],
			'vnd_active'	 => (int) $primaryVnd['vnd_active']];
		return $data;
	}

	public static function getCttIdsById($vndIds)
	{
		if ($vndIds == '')
		{
			throw new \Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$sql = " SELECT GROUP_CONCAT( DISTINCT CONCAT_WS(',',ctt.ctt_id,ctt.ctt_ref_code)) FROM contact_profile cp1 
JOIN vendors vnd1 ON vnd1.vnd_id =cp1.cr_is_vendor
JOIN vendors vnd ON vnd.vnd_ref_code =vnd1.vnd_ref_code
JOIN contact_profile cp ON cp.cr_is_vendor=vnd.vnd_id AND cp.cr_status =1
JOIN contact ctt1 ON ctt1.ctt_id=cp.cr_contact_id AND ctt1.ctt_active =1
JOIN contact ctt ON ctt.ctt_ref_code=ctt1.ctt_ref_code AND ctt.ctt_active =1
WHERE cp1.cr_is_vendor IN ({$vndIds}) AND cp1.cr_status =1";

		$relIds = \DBUtil::queryScalar($sql, DBUtil::SDB());

		if (!$relIds)
		{
			$sql = "SELECT GROUP_CONCAT(cr_contact_id)  FROM `contact_profile` 
		WHERE `cr_is_vendor` IN ({$vndIds}) AND cr_status =1";
		}
		$relIds = \DBUtil::queryScalar($sql, DBUtil::SDB());

		$relIdArr = explode(',', $relIds);

		$distinctRelIds = implode(',', array_unique($relIdArr));
		return $distinctRelIds;
	}

	public static function getRelatedIds($vndId, $showActive = true)
	{
		if (empty($vndId))
		{
			return false;
		}
		$vndIds	 = \Vendors::getByRefIds($vndId, $showActive);
		$cttIds	 = \Vendors::getCttIdsById($vndIds);

		if (empty($cttIds))
		{
			return 0;
		}
		$relCttIds	 = \Contact::getRelatedIds($cttIds);
		$relVndList	 = \Vendors::getRelatedByCttIds($relCttIds);
		return $relVndList;
	}

	public static function getPrimaryId($vndId)
	{
		$relVendorList = \Vendors::getRelatedIds($vndId);
		if ($relVendorList)
		{
			$primaryVnd = \Vendors::getPrimaryByIds($relVendorList);
		}
		return $primaryVnd['vnd_id'];
	}

	public static function getByRefIds($vndIds, $showActive = true)
	{
		if ($vndIds == '')
		{
			throw new \Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
		}
		$where = "";
		if ($showActive)
		{
			$where .= ' AND vnd_active>0';
		}
		$sql = "SELECT GROUP_CONCAT(DISTINCT vnd_id)
                FROM vendors
                WHERE 1 $where AND vnd_ref_code IN (
                    SELECT vnd_ref_code FROM vendors
                        WHERE vnd_id IN ({$vndIds}) $where)";

		return \DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	public static function updateStatus($vndId, $status)
	{
		$params	 = ['status' => $status, 'vndId' => $vndId];
		$sql	 = "UPDATE vendors SET vnd_active=:status WHERE vnd_id =:vndId";
		$resQ	 = DBUtil::execute($sql, $params);
		return $resQ;
	}

	public static function modifyReadytoApprove($vndId)
	{
		$vndDocStat	 = Document::checkReadyToApproveDocument($vndId);
		$vndmodel	 = Vendors::model()->findByPk($vndId);
		if ($vndDocStat == 1 && $vndmodel->vnd_active == 3)
		{
			$status = 4; //ready to approve
			self::updateStatus($vndId, $status);
		}
	}

	public static function checkVndStatus($vendorId)
	{
		$block		 = 0;
		$vndModel	 = Vendors::model()->findByPk($vendorId);
		if (!$vndModel || $vndModel->vnd_active != 1)
		{
			$block = 1;
		}
		return $block;
	}

	public static function dumpTdsData()
	{
		$sql	 = "SELECT atd.adt_trans_ref_id vndId, sum(atd.adt_amount) tdsAmount 
		FROM `account_trans_details` atd 
		INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id 
			AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 
		INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id AND atd1.adt_ledger_id = 55 AND atd1.adt_type = 5 
			AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
		INNER JOIN vendors vnd ON vnd.vnd_id=atd.adt_trans_ref_id AND vnd.vnd_id > 0 
		WHERE act.act_active=1 AND act.act_status=1 AND atd.adt_active=1 AND atd.adt_status=1 AND atd1.adt_active=1 AND atd1.adt_status=1 
			AND (act.act_date BETWEEN '2023-04-01 00:00:00' AND '2024-03-31 23:59:59') 			 
		GROUP BY vndId 		
		HAVING tdsAmount > 0";
		$data	 = DBUtil::query($sql, DBUtil::SDB());
		$i		 = 0;
		foreach ($data as $row)
		{
			$vndId		 = $row['vndId'];
			$amount		 = $row['tdsAmount'];
			$selSql		 = "SELECT vtds_id from test.vendor_tds_data_2023_24 
					WHERE vtds_vendor_id=$vndId";
			$isRowExist	 = DBUtil::queryScalar($selSql, DBUtil::MDB());
			if ($isRowExist)
			{
				continue;
			}
			try
			{
				$showActive	 = false;
				$relVenIds	 = Vendors::getRelatedIds($vndId, $showActive);

				$sqlVnd			 = "SELECT sum(atd.adt_amount) tripAmount 
		FROM `account_trans_details` atd 
		INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id 
			AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 
		INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id 
			AND atd1.adt_ledger_id = 22 AND atd1.adt_type = 5 
			AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
		INNER JOIN vendors vnd ON vnd.vnd_id=atd.adt_trans_ref_id 
		WHERE act.act_active=1 AND act.act_status=1 AND atd.adt_active=1 AND atd.adt_status=1 
			AND atd1.adt_active=1 AND atd1.adt_status=1 
			AND (act.act_date BETWEEN '2023-04-01 00:00:00' AND '2024-03-31 23:59:59') 
			AND atd.adt_trans_ref_id IN ({$vndId})  ";
				$tripAmountVal	 = DBUtil::queryScalar($sqlVnd, DBUtil::MDB());

				$tripAmount	 = $tripAmountVal | 0;
				$insSql		 = "INSERT INTO test.vendor_tds_data_2023_24 
		(vtds_vendor_id,vtds_amount,vtds_total_trip_amount,vtds_merged_id_list) 
		VALUES ($vndId, $amount,$tripAmount,'$relVenIds');";
				$res		 = DBUtil::execute($insSql);
				$i++;
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				echo "Error in data insert for vnd:$vndId ";
			}
		}
		echo "Total $i data processed";
	}

	public static function populateOutstandingData()
	{
		$sql	 = "SELECT vtds_vendor_id vndId from test.vendor_tds_data_2023_24";
		$data	 = DBUtil::query($sql, DBUtil::SDB());
		$i		 = 0;
		foreach ($data as $row)
		{
			$vndId	 = $row['vndId'];
			$sqlVnd	 = "SELECT vnd.vnd_id, sum(atd.adt_amount) totalOutstanding,
			max(act.act_date) lastTransactionDate ,
			max(IF(atd1.adt_ledger_id = 22,act.act_date,null)) lastTripTransDate
        FROM `account_trans_details` atd 
        INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id 
            AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 
        INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id = act.act_id  
            AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
        INNER JOIN vendors vnd ON vnd.vnd_id=atd.adt_trans_ref_id 
        WHERE act.act_active=1 AND act.act_status=1 AND atd.adt_active=1 AND atd.adt_status=1 
            AND atd1.adt_active=1 AND atd1.adt_status=1  
            AND atd.adt_trans_ref_id IN ({$vndId})  ";
			try
			{
				$rowOutstanding		 = DBUtil::queryRow($sqlVnd, DBUtil::MDB());
				$totalOutstanding	 = $rowOutstanding['totalOutstanding'];
				$lastTransactionDate = $rowOutstanding['lastTransactionDate'];
				$lastTripTransDate	 = $rowOutstanding['lastTripTransDate'];

				$insSql	 = "UPDATE test.vendor_tds_data_2023_24 
		SET vtds_total_outstanding = '$totalOutstanding',
			vtds_last_overall_trans_date= '$lastTransactionDate',
			vtds_last_trip_trans_date= '$lastTripTransDate' 
		WHERE vtds_vendor_id = $vndId ;";
				$res	 = DBUtil::execute($insSql);
				$i++;
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				echo "Error in data insert for vnd:$vndId ";
			}
		}
		echo "Total $i data processed";
	}

	public static function checkDriverCountForDCO($vndId = 0)
	{
		$where	 = '';
		$param	 = [];
		if ($vndId > 0)
		{
			$where			 = " AND vnd.vnd_ref_code =:vndId";
			$param['vndId']	 = $vndId;
		}
		else
		{
			return false;
		}
		$sql	 = "SELECT   vnd.vnd_id,vnd.vnd_ref_code,vnd.vnd_is_dco,
				GROUP_CONCAT(distinct vdrv.vdrv_drv_id) drvList,
				cpr.cr_is_driver selfDrvid,
				count( distinct vdrv.vdrv_drv_id) drvCount,
				IF(cpr.cr_is_driver > 0,1,0) isSelfDriver,
				IF(MAX(cpr.cr_is_driver IN (vdrv.vdrv_drv_id)),1,0) isSelfInDriverList,
				IF(vnd.vnd_cat_type=1,1,0) catDCOType
			FROM vendors vnd 
			INNER JOIN contact_profile cpr ON cpr.cr_is_vendor = vnd.vnd_id AND cpr.cr_status=1
			INNER JOIN vendor_driver vdrv ON vdrv.vdrv_vnd_id = vnd.vnd_id AND vdrv.vdrv_active = 1
			INNER JOIN drivers drv ON drv.drv_id = vdrv.vdrv_drv_id AND drv.drv_active=1 AND drv.drv_approved=1
			WHERE  vnd.vnd_is_dco = 1 AND  vnd.vnd_ref_code = vnd.vnd_id 
				$where
			GROUP BY vnd.vnd_ref_code
			HAVING drvCount > isSelfInDriverList AND drvCount > 0
			ORDER BY isSelfDriver DESC, isSelfInDriverList DESC, drvCount DESC";
		$resQ	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $resQ;
	}
}
