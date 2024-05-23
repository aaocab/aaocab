<?php

/**
 * This is the model class for table "agents".
 *
 * The followings are the available columns in table 'agents':
 * @property integer $agt_id
 * @property integer $agt_contact_id
 * @property string $agt_code
 * @property string $agt_fname
 * @property string $agt_lname
 * @property string $agt_username
 * @property string $agt_password
 * @property string $agt_phone
 * @property string $agt_email
 * @property string $agt_contact_person
 * @property string $agt_phone_country_code
 * @property string $agt_contact_number
 * @property string $agt_alt_contact_number
 * @property string $agt_address
 * @property string $agt_company
 * @property integer $agt_city
 * @property integer $agt_active
 * @property string $agt_modified_date
 * @property string $agt_create_date
 * @property integer $agt_type
 * @property string $agt_device
 * @property string $agt_os_version
 * @property string $agt_device_uuid
 * @property string $agt_apk_version
 * @property string $agt_mac_address
 * @property string $agt_longitude
 * @property string $agt_latitude
 * @property integer $agt_tnc_id
 * @property integer $agt_tnc
 * @property string $agt_tnc_datetime
 * @property double $agt_overall_rating
 * @property integer $agt_overall_score
 * @property integer $agt_total_trips
 * @property integer $agt_last_thirtyday_trips
 * @property integer $agt_total_amount
 * @property integer $agt_last_thirtyday_amount
 * @property string $agt_last_trip_datetime
 * @property string $agt_first_trip_datetime
 * @property string $agt_code_password
 * @property string $agt_api_key
 * @property string $agt_allowed_ip
 * @property string $agt_pic_path
 * @property string $agt_log
 * @property integer $agt_confirm_email
 * @property integer $agt_customer_confirm_email
 * @property integer $agt_confirm_sms
 * @property integer $agt_customer_confirm_sms
 * @property integer $agt_driver_assign_email
 * @property integer $agt_customer_driver_assign_email
 * @property integer $agt_driver_assign_sms
 * @property integer $agt_customer_driver_assign_sms
 * @property integer $agt_commission_value
 * @property integer $agt_commission
 * @property integer $agt_gozo_commission_value
 * @property integer $agt_gozo_commission
 * @property integer $agt_allow_discount
 * @property string $agt_verify_phone
 * @property string $agt_owner_name
 * @property string $agt_owner_photo
 * @property string $agt_email_two
 * @property string $agt_phone_two
 * @property string $agt_phone_three
 * @property string $agt_fax
 * @property string $agt_other_contact
 * @property string $agt_driver_license
 * @property string $agt_license_expiry_date
 * @property string $agt_license_issued_state
 * @property string $agt_aadhar_id
 * @property string $agt_voter_id
 * @property string $agt_company_add_proof
 * @property string $agt_trade_license
 * @property string $agt_bank
 * @property string $agt_bank_account
 * @property string $agt_branch_name
 * @property string $agt_swift_code
 * @property string $agt_ifsc_code
 * @property integer $agt_is_owner_photo
 * @property integer $agt_is_license_pic
 * @property integer $agt_is_owner_aadharcard
 * @property integer $agt_is_owner_pancard
 * @property integer $agt_is_bussiness_registration
 * @property integer $agt_is_username_pass
 * @property string $agt_referral_code
 * @property string $agt_location
 * @property string $agt_other_con_name_one
 * @property string $agt_other_con_phone_one
 * @property string $agt_other_con_email_one
 * @property string $agt_other_con_name_two
 * @property string $agt_other_con_phone_two
 * @property string $agt_other_con_email_two
 * @property string $agt_other_con_name_three
 * @property string $agt_other_con_phone_three
 * @property string $agt_other_con_email_three
 * @property string $agt_is_mail_sent
 * @property string $agt_owner_document
 * @property string $agt_payable_percentage
 * @property integer $agt_effective_credit_limit
 * @property integer $agt_overdue_days
 * @property integer $agt_grace_days
 * @property string $agt_approved_untill_date
 * @property integer $agt_approved_by
 * 	
 *
 * @property string $agt_agent_id
 * @property integer $agt_company_type
 * @property integer $agt_is_agreement
 * @property string $agt_agreement
 * @property integer $agt_is_ccin
 * @property string $agt_ccin
 * @property integer $agt_admin_id
 * @property integer $agt_credit_limit
 * @property string $agt_copybooking_name
 * @property integer $agt_copybooking_ismail
 * @property integer $agt_copybooking_issms
 * @property string $agt_copybooking_email
 * @property string $agt_copybooking_phone
 * @property integer $agt_copybooking_admin_ismail
 * @property integer $agt_copybooking_admin_issms
 * @property string $agt_copybooking_admin_email
 * @property string $agt_copybooking_admin_phone
 * @property integer $agt_copybooking_admin_isapp
 * @property integer $agt_trvl_sendupdate
 * @property integer $agt_trvl_isemail
 * @property integer $agt_trvl_issms
 * @property integer $agt_trvl_isapp
 * @property string $agt_trvl_email
 * @property string $agt_trvl_phone
 * @property integer $agt_trvl_accpref
 * @property string $agt_opening_deposit
 * @property integer $agt_is_voter_id
 * @property integer $agt_is_memorandum
 * @property integer $agt_copybooking_admin_id
 * @property string $agt_crp_date
 * @property string $agt_state
 * @property integer $agt_zip
 * @property string $agt_address_alt
 * @property string $agt_alt_state
 * @property integer $agt_alt_zip
 * @property integer $agt_tot_trvl_employee
 * @property string $agt_expected_trvl_month
 * @property integer $agt_anual_turnover
 * @property string $agt_expected_region1
 * @property string $agt_expected_region2
 * @property string $agt_expected_region3
 * @property string $agt_expected_region4
 * @property string $agt_expected_region5
 * @property string $agt_expected_region6
 * @property integer $agt_invoiceopt_booking
 * @property integer $agt_invoiceopt_monthly
 * @property integer $agt_invoiceopt_prepaid
 * @property integer $agt_invoiceopt_traveller
 * @property integer $agt_invoiceopt_def_traveller
 * @property string $agt_invoiceopt_other
 * @property string $agt_acc_contact_name
 * @property string $agt_acc_contact_mobile
 * @property string $agt_acc_contact_phone
 * @property string $agt_acc_contact_email
 * @property string $agt_bank_owner
 * @property string $agt_bank_owner_country
 * @property string $agt_rtgs
 * @property string $agt_bank_micr
 * @property string $agt_bank_branch_addrs
 * @property integer $agt_is_depo_received
 * @property integer $agt_depo_amount
 * @property string $agt_depo_confirmed_by
 * @property string $agt_depo_date
 * @property integer $agt_iscorporate_created
 * @property string $agt_comm_email

 * @property string $agt_aadhar
 * @property integer $agt_approved
 * @property string $agt_secret_key
 * @property integer $agt_vendor_autoassign_flag
 * @property integer $agt_otp_required
 * @property integer $agt_cancel_rule
 * @property string $agt_gstin
 * @property integer $agt_booking_platform
 * @property string $agt_pan_number
 * @property string $agt_pan_card
 * @property integer $agt_use_invoice_logo
 * @property string $agt_invoice_logo_path
 * @property integer $agt_verify_myacc_section
 * @property interger $agt_allow_smartmatch
 * @property integer $agt_ref_type
 * @property integer $agt_ref_id
 * @property int $agt_duty_slip_required
 * @property int $agt_driver_app_required
 * @property int $agt_water_bottles_required
 * @property int $agt_block_autoassignment
 * @property int $agt_is_cash_required
 * @property string $agt_pref_req_other

 * The followings are the available model relations:
 * @property Booking[] $bookings
 * @property ChannelPartnerMarkup[] $channelPartnerMarkups
 * @property Contact $agtContact
 * @property AgentAgreement[] $agtAag
 *
 *  */
class Agents extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $agt_password1, $agt_first_name, $agt_is_voterid, $agt_is_approved, $agt_is_driver_license, $agt_is_aadhar, $repeat_password, $activation_code, $new_password, $old_password, $agt_confirm_password,
			$agt_license_expiry_date_date, $agt_address_line1, $agt_address_line2, $agt_address_line3,
			$agt_address_alt_line1, $agt_address_alt_line2, $agt_address_alt_line3, $adm_fname, $adm_lname, $agt_chk_others, $agt_block_autoassign_flag, $agt_otp_not_required, $search;
	public $company_type	 = [1 => 'Sole Proprietorship', 2 => 'Partnership', 3 => 'Private Limited', 4 => 'Public Limited'];
	public $partnerStatus	 = array('0' => 'Unapproved', '1' => 'Approved', '2' => 'Rejected');
	public $salt			 = "";
	public $createDate1, $createDate2, $agt_payment_lock, $agt_extra_comm_display;

	public function tableName()
	{
		return 'agents';
	}

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => "agt_active >0",
		);
		return $arr;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			['agt_phone', 'validatePhone', 'on' => 'signup,corpsignup,'],
			['agt_copybooking_phone', 'validateCopyBookingPhone', 'on' => 'join4'],
			['agt_phone,agt_fname,agt_username', 'required', 'on' => 'qrsignup'],
			array('agt_fname,agt_lname,agt_username,agt_password1,agt_email,agt_phone', 'required', 'on' => 'insert'),
			['agt_email,agt_phone,agt_tnc,agt_fname,agt_lname,agt_phone_country_code,agt_city', 'required', 'on' => 'signup'],
			['agt_email,agt_company,agt_phone,agt_tnc,agt_fname,agt_lname,agt_phone_country_code', 'required', 'on' => 'corpsignup'],
			array('agt_email', 'email', 'on' => 'signup', 'message' => 'Please enter valid email address', 'checkMX' => true),
			array('agt_company, agt_owner_name, agt_city, agt_email, agt_phone, agt_fname, agt_lname', 'required', 'on' => 'agentjoin'),
			['agt_pan_number', 'required', 'on' => 'join5'],
			['agt_email,agt_company,agt_phone,agt_fname,agt_lname,agt_referral_code', 'required', 'on' => 'corporatejoin'],
			['agt_referral_code', 'validateCorpCode', 'on' => 'corporatejoin'],
			['new_password,repeat_password', 'required', 'on' => 'changepassword1'],
			['agt_company,agt_company_type,agt_owner_name,agt_city,agt_fname,agt_lname', 'required', 'on' => 'join1'],
			['agt_email,agt_phone', 'required', 'on' => 'join2'],
			['agt_email,agt_email_two,agt_other_con_email_one, agt_other_con_email_two, agt_other_con_email_three', 'email', 'message' => 'This email address is not valid'],
			['agt_phone, agt_phone_two, agt_phone_three, agt_other_con_phone_one, agt_other_con_phone_two, agt_other_con_phone_three', 'numerical', 'integerOnly' => true],
			array('agt_active, agt_type, agt_tnc_id,agt_trvl_phone, agt_tnc, agt_overall_score, agt_total_trips, agt_last_thirtyday_trips, agt_total_amount, agt_last_thirtyday_amount', 'numerical', 'integerOnly' => true),
			array('agt_overall_rating', 'numerical'),
			array('agt_tnc, agt_tnc_id, agt_tnc_datetime', 'required', 'on' => 'updatetnc'),
			array('agt_confirm_email, agt_customer_confirm_email, agt_confirm_sms, agt_customer_confirm_sms, agt_driver_assign_email, agt_customer_driver_assign_email, agt_driver_assign_sms, agt_customer_driver_assign_sms, agt_commission_value, agt_commission', 'required', 'on' => 'emailSms'),
			array('agt_username,agt_email,agt_phone', 'unique', 'on' => 'insert,agentjoin,signup,join1,join2,corpsignup', 'message' => 'You have already registered with this {attribute}'),
			array('agt_username,agt_email,agt_phone', 'checkDuplicate', 'on' => 'update', 'message' => 'You have already registered with this {attribute}'),
			array('agt_fname', 'length', 'max' => 200),
			array('agt_lname, agt_password, agt_email, agt_os_version, agt_apk_version, agt_longitude, agt_latitude, agt_code_password', 'length', 'max' => 100),
			array('agt_username, agt_address, agt_device, agt_device_uuid', 'length', 'max' => 255),
			array('agt_contact_person,agt_secret_key', 'length', 'max' => 50),
			array('agt_phone', 'length', 'min' => 8, 'message' => 'Invalid phone number'),
			array('agt_phone_country_code', 'length', 'max' => 5),
			array('agt_password1', 'length', 'min' => 3),
			array('agt_contact_number,agt_phone, agt_alt_contact_number,agt_trvl_phone', 'length', 'max' => 15, 'message' => 'Invalid phone number'),
			array('agt_company, agt_mac_address', 'length', 'max' => 150),
			array('agt_id, agt_code, agt_name', 'required', 'on' => 'updateCode'),
			array('agt_tnc_datetime, agt_last_trip_datetime, agt_first_trip_datetime', 'safe'),
			array('agt_id,agt_code,agt_fname,agt_lname, agt_username, agt_phone,agt_log,agt_phone_country_code, agt_email, agt_contact_person, agt_active, agt_device, agt_os_version, agt_type,agt_confirm_password,agt_password1,agt_api_key, agt_confirm_email, agt_customer_confirm_email, agt_confirm_sms, agt_customer_confirm_sms, agt_driver_assign_email, agt_customer_driver_assign_email, agt_driver_assign_sms, agt_customer_driver_assign_sms, agt_commission_value, agt_commission, agt_gozo_commission_value, agt_gozo_commission, agt_payable_percentage, agt_allow_discount, agt_verify_phone, agt_owner_name, agt_owner_photo, agt_email_two, agt_phone_two, agt_phone_three, agt_fax, agt_other_contact,
                            agt_driver_license, agt_license_expiry_date, agt_license_issued_state, agt_aadhar_id, agt_voter_id, agt_company_add_proof, agt_trade_license, agt_bank,agt_chk_others,
                            agt_bank_account, agt_branch_name, agt_swift_code, agt_ifsc_code, agt_is_owner_photo, agt_is_license_pic, agt_is_owner_aadharcard,agt_use_invoice_logo,agt_invoice_logo_path
                            agt_is_owner_pancard, agt_is_bussiness_registration, agt_referral_code, agt_location, agt_other_con_name_one, agt_other_con_phone_one, agt_other_con_email_one, agt_other_con_name_two, agt_other_con_phone_two, agt_other_con_email_two, agt_other_con_name_three, agt_other_con_phone_three, agt_other_con_email_three, agt_is_username_pass, agt_is_mail_sent, agt_allowed_ip, agt_owner_document
                            ,agt_agent_id,agt_company_type,agt_is_agreement,agt_agreement,agt_is_ccin,agt_ccin,agt_admin_id,agt_credit_limit,agt_copybooking_name,agt_copybooking_ismail,agt_copybooking_issms,agt_copybooking_email,agt_copybooking_phone,agt_copybooking_admin_ismail,agt_copybooking_admin_issms,agt_copybooking_admin_email,agt_copybooking_admin_phone,agt_copybooking_admin_isapp,agt_trvl_sendupdate,agt_trvl_isemail,agt_trvl_issms,agt_trvl_isapp,agt_trvl_email,agt_trvl_phone,agt_trvl_accpref,agt_opening_deposit,agt_is_voter_id,agt_is_memorandum,agt_copybooking_admin_id,agt_crp_date,agt_state,agt_zip,agt_address_alt,agt_alt_state,agt_alt_zip,agt_tot_trvl_employee,
                            agt_expected_trvl_month,agt_anual_turnover,agt_expected_region1,agt_expected_region2,agt_expected_region3,agt_expected_region4,agt_expected_region5,agt_expected_region6,agt_invoiceopt_booking,agt_invoiceopt_monthly,agt_invoiceopt_prepaid,agt_invoiceopt_traveller,agt_invoiceopt_def_traveller,agt_invoiceopt_other,agt_acc_contact_name,agt_acc_contact_phone,agt_acc_contact_mobile,agt_acc_contact_email,agt_bank_owner,agt_bank_owner_country,agt_rtgs,agt_bank_micr,agt_bank_branch_addrs,agt_is_depo_received,agt_depo_amount,agt_depo_confirmed_by,agt_depo_date,agt_iscorporate_created,
                            agt_comm_email,agt_pan_card,agt_city,agt_gstin,agt_aadhar,agt_approved,agt_secret_key,agt_vendor_autoassign_flag,agt_otp_required,agt_cancel_rule,agt_booking_platform,agt_pan_number,agt_duty_slip_required,agt_allow_smartmatch,agt_verify_myacc_section,agt_ref_type,agt_ref_id,agt_driver_app_required,agt_block_autoassignment,agt_water_bottles_required,agt_is_cash_required,agt_pref_req_other,agt_block_autoassign_flag,agt_otp_not_required,agt_contact_id,agt_effective_credit_limit,agt_overdue_days,agt_grace_days,agt_approved_untill_date,agt_approved_by', 'safe'),
		);
	}

//    public function checkduplicate($attr) {
//        $model = $this->find('agt_username=:user', ['user' => $this->agt_username]);
//        if ($model != '') {
//            $this->addError('agt_username', 'Email already exist');
//            return false;
//        } else {
//            return true;
//        }
//    }

	public function checkDuplicate($attribute, $params)
	{
		$agtId		 = trim($this->agt_id);
		$agtUsername = trim($this->agt_username);
		$agtEmail	 = trim($this->agt_email);
		$agtPhone	 = trim($this->agt_phone);

		$strWhere = "";

		$where = " agt_active = 1 ";
		if ($agtId != "")
		{
			$where .= " AND agt_id != '" . $agtId . "'";
		}

		if ($agtUsername != '' || $agtEmail != '' || $agtPhone != "")
		{
			$where .= " AND (";

			if ($agtUsername != "")
			{
				$strWhere = " agt_username = '" . $agtUsername . "'";
			}
			if ($agtEmail != "")
			{
				$strWhere	 .= ($strWhere != "" ? " OR " : "");
				$strWhere	 .= " agt_email = '" . $agtEmail . "'";
			}
			if ($agtPhone != "")
			{
				$strWhere	 .= ($strWhere != "" ? " OR " : "");
				$strWhere	 .= " agt_phone = '" . $agtPhone . "'";
			}

			$where .= $strWhere . " ) ";
		}

		$sql = "SELECT COUNT(1) as cnt FROM agents WHERE $where ";
		$cnt = DBUtil::queryScalar($sql);

		if ($cnt > 0)
		{
			$labelArr = $this->attributeLabels();
			$this->addError($attribute, "You have already registered with this {$labelArr[$attribute]}");
			return false;
		}
		else
		{
			return true;
		}
	}

	public function validatePhone($attribute, $params)
	{

		if ($this->agt_phone != '')
		{

			try
			{
				$phone		 = "+" . $this->agt_phone_country_code . $this->agt_phone;
				$phonenumber = new libphonenumber\LibPhone($phone);
				$a			 = $phonenumber->toE164();
				$a			 = $phonenumber->toInternational();
				$a			 = $phonenumber->toNational();
			}
			catch (Exception $e)
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
			if (!$phonenumber->validate())
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
		}
		return true;
	}

	public function validateCopyBookingPhone($attribute, $params)
	{

		if ($this->agt_copybooking_phone != '')
		{

			try
			{
				$phone		 = "+" . '91' . $this->agt_copybooking_phone;
				$phonenumber = new libphonenumber\LibPhone($phone);
				$a			 = $phonenumber->toE164();
				$a			 = $phonenumber->toInternational();
				$a			 = $phonenumber->toNational();
			}
			catch (Exception $e)
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
			if (!$phonenumber->validate())
			{
				$this->addError($attribute, 'Please enter valid phone number');
				return FALSE;
			}
		}
		return true;
	}

	public function validateCorpCode($attr)
	{
//	$model = $this->find('agt_referral_code=:code', ['code' => $this->agt_referral_code]);
//	if (!$this->isNewRecord)
//	{
//	    $model = $this->find('agt_referral_code=:code AND agt_id<>:id', ['code' => $this->agt_referral_code, 'id' => $this->agt_id]);
//	}
//	if ($model != '')
//	{
//	    $this->addError('agt_referral_code', 'Corporate already exists.');
//	    return false;
//	}
//	else
//	{
		return true;
		//}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'bookings'	 => array(self::HAS_MANY, 'Booking', 'bkg_agent_id'),
			'agtAdmin'	 => array(self::BELONGS_TO, 'Admins', 'agt_admin_id'),
			'agtAag'	 => array(self::HAS_ONE, 'AgentAgreement', 'aag_agt_id'),
			'agtContact' => array(self::BELONGS_TO, 'Contact', 'agt_contact_id'),
			'patStats'	 => array(self::HAS_ONE, 'PartnerStats', 'pts_agt_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agt_id'						 => 'ID',
			'agt_code'						 => 'Code',
			'agt_fname'						 => 'First Name',
			'agt_lname'						 => 'Last Name',
			'agt_username'					 => 'Username',
			'agt_password'					 => 'Password',
			'agt_password1'					 => 'Password',
			'agt_confirm_password'			 => 'Confirm Password',
			'agt_phone'						 => 'Phone',
			'agt_email'						 => 'Email Address',
			'agt_contact_person'			 => 'Contact Person',
			'agt_phone_country_code'		 => 'Phone Country Code',
			'agt_contact_number'			 => 'Contact Number',
			'agt_alt_contact_number'		 => 'Alt Contact Number',
			'agt_address'					 => 'Address',
			'agt_company'					 => 'Company',
			'agt_city'						 => 'City',
			'agt_active'					 => 'Active',
			'agt_modified_date'				 => 'Modified Date',
			'agt_create_date'				 => 'Create Date',
			'agt_type'						 => 'Type',
			'agt_device'					 => 'Device',
			'agt_os_version'				 => 'Os Version',
			'agt_device_uuid'				 => 'Device Uuid',
			'agt_apk_version'				 => 'Apk Version',
			'agt_mac_address'				 => 'Mac Address',
			'agt_longitude'					 => 'Longitude',
			'agt_latitude'					 => 'Latitude',
			'agt_tnc_id'					 => 'Tnc',
			'agt_tnc'						 => 'Terms and Conditions',
			'agt_tnc_datetime'				 => 'Tnc Datetime',
			'agt_overall_rating'			 => 'Overall Rating',
			'agt_overall_score'				 => 'Overall Score',
			'agt_total_trips'				 => 'Total Trips',
			'agt_last_thirtyday_trips'		 => 'Last Thirtyday Trips',
			'agt_total_amount'				 => 'Total Amount',
			'agt_last_thirtyday_amount'		 => 'Last Thirtyday Amount',
			'agt_last_trip_datetime'		 => 'Last Trip Datetime',
			'agt_first_trip_datetime'		 => 'First Trip Datetime',
			'agt_code_password'				 => 'Code Password',
			'agt_pic_path'					 => 'pic path',
			'agt_owner_photo'				 => 'Owner Photo',
			'agt_owner_name'				 => 'Proprietor/Director Name',
			'agt_other_con_name_one'		 => 'Name',
			'agt_other_con_phone_one'		 => 'Phone',
			'agt_other_con_email_one'		 => 'Email',
			'agt_other_con_name_two'		 => 'Name',
			'agt_other_con_phone_two'		 => 'Phone',
			'agt_other_con_email_two'		 => 'Email',
			'agt_other_con_name_three'		 => 'Name',
			'agt_other_con_phone_three'		 => 'Phone',
			'agt_other_con_email_three'		 => 'Email',
			'agt_aadhar_id'					 => 'Aadhar',
			'agt_driver_license'			 => 'Driver License',
			'agt_license_expiry_date'		 => 'License Expiry Date',
			'agt_is_username_pass'			 => 'Agent username  & password created',
			'agt_is_mail_sent'				 => 'Agent mail sent',
			'agt_referral_code'				 => 'Referral Code',
			/*  new fields */
			'agt_trvl_issms'				 => 'Trvl Issms',
			'agt_trvl_isapp'				 => 'Trvl Isapp',
			'agt_trvl_email'				 => 'Trvl Email',
			'agt_trvl_phone'				 => 'Trvl Phone',
			'agt_trvl_accpref'				 => 'Trvl Accpref',
			'agt_opening_deposit'			 => 'Opening Deposit',
			'agt_copybooking_admin_id'		 => 'Copybooking Admin',
			'agt_admin_id'					 => 'Admin',
			'agt_agent_id'					 => 'Agent',
			'agt_company_type'				 => 'Company Type',
			'agt_aadhar'					 => 'Aadhar',
			'agt_acc_contact_name'			 => 'Acc Contact Name',
			'agt_acc_contact_phone'			 => 'Acc Contact Phone',
			'agt_acc_contact_mobile'		 => 'Acc Contact Mobile',
			'agt_acc_contact_email'			 => 'Acc Contact Email',
			'agt_bank_owner'				 => 'Bank Owner',
			'agt_bank_owner_country'		 => 'Bank Owner Country',
			'agt_bank_branch_addrs'			 => 'Bank Branch Addrs',
			'agt_bank_micr'					 => 'Bank Micr',
			'agt_rtgs'						 => 'Rtgs',
			'agt_deposit_proof'				 => 'Deposit Proof',
			'agt_is_depo_received'			 => 'Is Depo Received',
			'agt_depo_amount'				 => 'Depo Amount',
			'agt_depo_confirmed_by'			 => 'Depo Confirmed By',
			'agt_depo_date'					 => 'Depo Date',
			'agt_iscorporate_created'		 => 'Iscorporate Created',
			'agt_comm_email'				 => 'Comm Email',
			'agt_is_agreement'				 => 'Is Agreement',
			'agt_agreement'					 => 'Agreement',
			'agt_is_ccin'					 => 'Is Ccin',
			'agt_ccin'						 => 'Ccin',
			'agt_is_memorandum'				 => 'Is Memorandum',
			'agt_trvl_accpref'				 => 'Trvl Accpref',
			'agt_opening_deposit'			 => 'Opening Deposit',
			'agt_copybooking_admin_id'		 => 'Copybooking Admin',
			'agt_admin_id'					 => 'Admin',
			'agt_agent_id'					 => 'Agent',
			'agt_company_type'				 => 'Company Type',
			'agt_aadhar'					 => 'Aadhar',
			/*  new fields */
			'agt_agent_id'					 => 'AGENT ID',
			'agt_company_type'				 => 'Company Type',
			'agt_is_agreement'				 => 'Is Agreement',
			'agt_agreement'					 => 'Agreement',
			'agt_is_ccin'					 => 'Is CCIN',
			'agt_admin_id'					 => 'Gozo Account Manager',
			'agt_credit_limit'				 => 'Credit Limit',
			'agt_copybooking_name'			 => 'Copy Booking Name1',
			'agt_copybooking_ismail'		 => 'Copy Booking is email1',
			'agt_copybooking_issms'			 => 'Copy Booking is sms1',
			'agt_copybooking_email'			 => 'Copy Booking Email1',
			'agt_copybooking_phone'			 => 'Copy Booking Phone',
			'agt_copybooking_admin_ismail'	 => 'Copy Booking Gozo Account Manager IsEmail',
			'agt_copybooking_admin_issms'	 => 'Copy Booking Gozo Account Manager IsSMS',
			'agt_copybooking_admin_isapp'	 => 'Copy Booking Gozo Account Manager IsApp',
			'agt_copybooking_admin_email'	 => 'Copy Booking Gozo Account Manager Email',
			'agt_copybooking_admin_phone'	 => 'Copy Booking Gozo Account Manager Phone',
			'agt_trvl_sendupdate'			 => 'Traveller Is Send Update',
			'agt_trvl_isemail'				 => 'Traveller Is Email',
			'agt_trvl_issms'				 => 'Traveller Is Sms',
			'agt_trvl_isapp'				 => 'Traveller Is App',
			'agt_trvl_email'				 => 'Traveller Email',
			'agt_trvl_phone'				 => 'Traveller Phone',
			'agt_trvl_accpref'				 => 'Use traveller account  preferences',
			'agt_opening_deposit'			 => 'Account opening deposit',
			'agt_is_voter_id'				 => 'Is Voter ID',
			'agt_is_memorandum'				 => 'Is Memorandum',
			'agt_copybooking_admin_id'		 => 'Gozo Account Manager',
			'agt_crp_date'					 => 'Corporate Date',
			'agt_state'						 => 'State',
			'agt_zip'						 => 'ZIP CODE',
			'agt_address_alt'				 => 'Alternate Address',
			'agt_alt_state'					 => 'Alternate State',
			'agt_alt_zip'					 => 'Alternate Zip',
			'agt_tot_trvl_employee'			 => 'Total Employees to travel',
			'agt_expected_trvl_month'		 => 'Expected month to travel',
			'agt_anual_turnover'			 => 'Annual Turnover',
			'agt_expected_region1'			 => 'Expected Region1',
			'agt_expected_region2'			 => 'Expected Region2',
			'agt_expected_region3'			 => 'Expected Region3',
			'agt_expected_region4'			 => 'Expected Region4',
			'agt_expected_region5'			 => 'Expected Region5',
			'agt_expected_region6'			 => 'Expected Region6',
			'agt_invoiceopt_booking'		 => 'Invoice by booking',
			'agt_invoiceopt_monthly'		 => 'Invoice monthly',
			'agt_invoiceopt_prepaid'		 => 'Pre-paid (Advance payment required)',
			'agt_invoiceopt_traveller'		 => 'Collect from traveller',
			'agt_invoiceopt_def_traveller'	 => 'Collect from traveller (Default)',
			'agt_invoiceopt_other'			 => 'Other invoice option',
			'agt_secret_key'				 => 'Secret Key',
			'agt_vendor_autoassign_flag'	 => 'Vendor Auto Assign Flag',
			'agt_booking_platform'			 => 'Spot Or Manual Booking',
			'agt_pan_number'				 => 'PAN NUMBER',
			'agt_pan_card'					 => 'PAN Card',
			'agt_duty_slip_required'		 => 'Duty Slip',
			'agt_allow_smartmatch'			 => 'Allow Smartmatch',
			'agt_verify_myacc_section'		 => 'Agent Verification My Account Section',
			'agt_ref_type'					 => 'Agt Ref Type',
			'agt_ref_id'					 => 'Agt Ref',
			'agt_driver_app_required'		 => 'Driver App',
			'agt_block_autoassignment'		 => 'Block auto assignment',
			'agt_commission'				 => 'Agent Commission',
			'agt_commission_value'			 => 'Agent Commission Value',
			'agt_payment_outstanding_limit'	 => 'Agent Outstanding Value',
			'agt_payment_outstanding_wallet' => 'Agent Outstanding Wallet Value',
			'agt_effective_credit_limit'	 => 'Effective Credit Limit',
			'agt_overdue_days'				 => 'Agent Overdue Days',
			'agt_grace_days'				 => 'Agent Grace Days'
		);
	}
	
	public function search()
	{
		$cond = "";
		if ($this->agt_owner_name != null)
		{
			$cond .= " AND agt_owner_name LIKE '%" . $this->agt_owner_name . "%' ";
		}
		if ($this->adm_fname != null)
		{
			$cond .= " AND (adm_fname LIKE '%" . $this->adm_fname . "%' OR adm_lname='%" . $this->adm_fname . "%') ";
		}
		if ($this->agt_phone != null)
		{
			$cond .= " AND agt_phone LIKE '%" . $this->agt_phone . "%' ";
		}
		if ($this->agt_email != null)
		{
			$cond .= " AND agt_email LIKE '%" . $this->agt_email . "%' ";
		}
		if ($this->agt_company != '')
		{
			$cond .= " AND (agt_company LIKE '%" . stripslashes($this->agt_company) . "%' OR agt_code='" . stripslashes($this->agt_company) . "') ";
		}
		if(is_array($this->agt_type) && count($this->agt_type) > 0)
		{
			$strAgtType = implode(',', $this->agt_type);
			if(!in_array(3, $this->agt_type))
			{
				$cond .= " AND agt_type IN ({$strAgtType}) AND qrc_agent_id IS NULL ";
			}
			else
			{
				$cond .= " AND (agt_type IN ({$strAgtType}) OR qrc_agent_id > 0) ";
			}
		}
		
		if ($this->agt_active != '')
		{
			$cond .= " AND agt_active = {$this->agt_active} ";
		}
		if ($this->agt_approved != '')
		{
			$cond .= " AND agt_approved = {$this->agt_approved} ";
		}
		if ($this->createDate1 != '' && $this->createDate2 != '')
		{
			$cond .= " AND (agt_create_date BETWEEN '" . $this->createDate1 . " 00:00:00' AND '" . $this->createDate2 . " 23:59:59') ";
		}

		$sql = "SELECT agt_id, agt_type, agt_commission, agt_credit_limit, agt_effective_credit_limit, agt_fname, agt_lname, 
				agt_owner_name, agt_phone, agt_email, agt_contact_person, agt_company, agt_active, agt_approved, agt_create_date,
				adm_fname, adm_lname 
				FROM agents 
				LEFT JOIN admins ON agt_admin_id = adm_id 
				LEFT JOIN qr_code ON qrc_agent_id = agt_id 
				WHERE 1 {$cond} 
				ORDER BY agt_create_date DESC";
		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['defaultOrder'	 => 'agt_create_date DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);

		return $dataprovider;
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
	public function search_OLD()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$agtname			 = '';
		$criteria			 = new CDbCriteria;
		$criteria->with		 = ['agtAdmin' => ['select' => 'adm_fname, adm_lname']];
		$criteria->select	 = "t.agt_id,t.agt_type,t.agt_commission,t.agt_credit_limit,t.agt_effective_credit_limit,t.agt_fname,t.agt_lname,t.agt_owner_name,t.agt_phone, t.agt_email,t.agt_contact_person,t.agt_company,t.agt_active, t.agt_approved,t.agt_phone, t.agt_email,t.agt_contact_person,t.agt_company,t.agt_active,t.agt_create_date";
		if ($this->agt_owner_name != null)
		{
			$criteria->addCondition("agt_owner_name LIKE '%" . $this->agt_owner_name . "%'");
		}
		if ($this->adm_fname != null || $this->adm_lname != null || $this->agt_contact_person != null)
		{
			$criteria->addCondition("adm_fname LIKE '%" . $this->adm_fname . "%' OR adm_lname='%" . $this->adm_lname . "%' OR agt_contact_person='%" . $this->agt_contact_person . "%'");
		}
		if ($this->agt_phone != null)
		{
			$criteria->addCondition("agt_phone LIKE '%" . $this->agt_phone . "%'");
		}
		if ($this->agt_email != null)
		{
			$criteria->addCondition("agt_email LIKE '%" . $this->agt_email . "%'");
		}

		if ($this->agt_company != '')
		{
			$criteria->addCondition("agt_company LIKE '%" . stripslashes($this->agt_company) . "%' OR agt_code='" . stripslashes($this->agt_company) . "'");
		}
		if ($this->agt_type >= 0)
		{
			$criteria->compare('agt_type', $this->agt_type);
		}
		if ($this->agt_active == 1)
		{
			$criteria->compare('agt_active', 1);
		}
		else if ($this->agt_active == 0)
		{
			$criteria->compare('agt_active', 0);
		}
		else if ($this->agt_active == 2)
		{
			$criteria->compare('agt_active', 2);
		}
		else
		{
			$criteria->addInCondition('agt_active', array_values($this->agt_active));
		}

		if ($this->agt_approved == 1)
		{
			$criteria->compare('agt_approved', 1);
		}
		else if ($this->agt_approved == 2)
		{
			$criteria->compare('agt_approved', 2);
		}
		else if ($this->agt_approved == 0)
		{
			$criteria->compare('agt_approved', 0);
		}
		else
		{
			$criteria->addInCondition('agt_approved', array_values($this->agt_approved));
		}
		if ($this->createDate1 != '' && $this->createDate2 != '')
		{
			$criteria->addCondition("agt_create_date BETWEEN '" . $this->createDate1 . " 00:00:00' AND '" . $this->createDate2 . " 23:59:59'");
		}
		$dataProvider = new CActiveDataProvider($this, ['criteria'	 => $criteria, 'sort'		 => array(
				'defaultOrder' => 'agt_create_date DESC',
			), 'pagination' => ['pageSize' => 50]]);

		return $dataProvider;
	}

	public function fetchList($qry)
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ["t.*,tnc.tnc_text, tnc.tnc_version"];
		// $criteria->compare('agt_active', 1);
//        if ($qry['searchname'] != '')
//        {
//            $criteria->addSearchCondition('drv_name', $qry['searchname'], true);
//        }
//        if ($qry['searchemail'] != '')
//        {
//            $criteria->addSearchCondition('drv_email', $qry['searchemail'], true);
//        }
//        if ($qry['searchphone'] != '')
//        {
//            $criteria->addSearchCondition('drv_phone', $qry['searchphone'], true);
//        }
		$criteria->join		 = " LEFT JOIN terms tnc ON tnc_id = agt_tnc_id";
		$criteria->group	 = "agt_id";
		// $criteria->order = 'drv_name DESC';
		$criteria->together	 = true;
		$criteria->order	 = 'agt_create_date DESC';
		$sort				 = new CSort;
		$sort->attributes	 = array(
			'agt_fname', 'agt_lname', 'agt_username', 'agt_phone', 'agt_email'
		);
		$dataProvider		 = new CActiveDataProvider($this, array('criteria' => $criteria, 'sort' => $sort));
		return $dataProvider;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Agents the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
	{
		parent::beforeSave();

		if ($this->agt_password1 != "")
		{
			$this->agt_password = md5($this->agt_password1);
		}
		return true;
	}

	public function getAgentType($agent = '')
	{
		$arr = [0 => "Travel Agent", 1 => "Authorized Reseller Agent", 2 => "Corporate Agent", 3 => "QR Based Agents"]; //dicount given to travel agent,discount given corporate agent,commission given to authorized resseller
		if ($agent != '')
		{
			return $arr[$agent];
		}
		return $arr;
	}

	public function approveArr($approve)
	{
		//registered(0){newly registered} ==> pending_approval(2){identity proof uploaded or sent to gozo } ==> approved(1){verified and approved} , rejected(3){rejected}
		$arr = ['1' => 'approved', '2' => 'pending_approval', '3' => 'rejected', '0' => 'registered'];
		if ($approve != '')
		{
			return $arr[$approve];
		}
		return $arr;
	}

	public function findByCode($code)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `agents` WHERE agents.agt_code='$code' AND agents.agt_active>0";
		return DBUtil::queryScalar($sql);
	}

	public function findByEmail($email)
	{
		return $this->find('agt_username=:username AND agt_type IN(0,2)', ['username' => $email]);
	}

	public function findByApiKey($key, $ip = '')
	{
		$sql = "SELECT * FROM `agents` WHERE agt_api_key='$key'";
		return DBUtil::queryRow($sql);
	}

	public function findCityById($agtid)
	{
		$sql = "select  c.cty_name, a.agt_city, a.agt_id from cities c JOIN agents a on c.cty_id = a.agt_city where a.agt_id = '$agtid'";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public function profiledetails($user)
	{

		$criteria			 = new CDbCriteria;
		$criteria->select	 = "agt_fname,agt_lname,agt_email,agt_phone_country_code,agt_phone,agt_city,agt_alt_contact_number,agt_address,agt_pic_path,agt_company";

		$criteria->compare('agt_id', $user);
		return $this->find($criteria);
	}

	public function updatetnc($data)
	{
		$model					 = Agents::model()->findByPk($data['agt_id']);
		$model->agt_tnc			 = 1;
		$model->agt_tnc_id		 = $data['new_tnc_id'];
		$model->agt_tnc_datetime = new CDbExpression('NOW()');
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

	public function getAgentList()
	{
		$cdb = Yii::app()->db->createCommand()
				->select("agt_id, trim(concat( IFNULL(agt_fname,''),  ' '  ,IFNULL(agt_lname,''))) as agt_name")
				->from('agents')
				->where('agt_active = 1 AND (agt_fname <> ""  OR agt_lname <> "")')
				->order('agt_fname');

		$query = $cdb->queryAll();

		return CHtml::listData($query, 'agt_id', 'agt_name');
	}

	public function addLog($oldData, $newData, $agentUpdatedData = "")
	{
		if ($oldData)
		{
			unset($oldData['agt_log']);
			//echo 'old pan-->'.$oldData['agt_pan_number'];
			unset($newData['agt_log']);
			//echo 'new pan-->'.$newData['agt_pan_number'];
			$getDifference	 = array_diff_assoc($newData, $oldData);
			$getDifference	 = $agentUpdatedData;
//			var_dump($getDifference);
//			exit();
			$remark			 = $this->agt_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
			//if ($remark) {
			if (is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
			}
			else if (is_array($remark))
			{
				$newcomm = $remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			if (count($getDifference) > 0)
			{
				while (count($newcomm) >= 10)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));

				$log = CJSON::encode($newcomm);
				return $log;
				//}
			}
		}
		return $remark;
	}

	public function getEmailSmsCount($agt_id)
	{
		$qry		 = "select agt_confirm_email, agt_customer_confirm_email, agt_confirm_sms, agt_customer_confirm_sms, agt_driver_assign_email, agt_customer_driver_assign_email, agt_driver_assign_sms, agt_customer_driver_assign_sms, agt_commission_value, agt_commission from agents where agt_id = $agt_id";
		$recordset	 = DBUtil::queryRow($qry);
		return $recordset;
	}

	public function getUsername()
	{
		return trim($this->agt_fname) . ' ' . trim($this->agt_lname);
	}

	public function uploadAgentDocument($uploadedFile, $agent_id, $prefix = 'document')
	{
		$fileName	 = $agent_id . "-" . $prefix . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByAgentId = $dir . DIRECTORY_SEPARATOR . $agent_id;
		if (!is_dir($dirByAgentId))
		{
			mkdir($dirByAgentId);
		}

		$foldertoupload	 = $dirByAgentId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByAgentId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $agent_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function uploadAgentUserDocument($uploadedFile, $user_id, $prefix = 'document')
	{
		$fileName	 = $user_id . "-" . $prefix . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirUsers = $dir . DIRECTORY_SEPARATOR . 'Users';
		if (!is_dir($dirUsers))
		{
			mkdir($dirUsers);
		}

		$dirByAgentId = $dirUsers . DIRECTORY_SEPARATOR . $user_id;
		if (!is_dir($dirByAgentId))
		{
			mkdir($dirByAgentId);
		}

		$foldertoupload	 = $dirByAgentId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByAgentId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'Users' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function fetchApproveList()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('agt_id', $this->agt_id);
		$criteria->compare('agt_fname', $this->agt_fname, true);
		$criteria->compare('agt_lname', $this->agt_lname, true);
		$criteria->compare('agt_owner_name', $this->agt_owner_name, true);
		$criteria->compare('agt_password', $this->agt_password, true);
		$criteria->compare('agt_phone', $this->agt_phone, true);
		$criteria->compare('agt_email', $this->agt_email, true);
		$criteria->compare('agt_contact_person', $this->agt_contact_person, true);
		$criteria->compare('agt_active', $this->agt_active);
		$criteria->compare('agt_approved', $this->agt_approved);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function notifyOptions($arr = [])
	{
		$arrNotify			 = [];
		$bookingModel		 = Booking::model()->findByPk($arr['bkgId']);
		$bookingPrefModel	 = BookingPref::model()->getByBooking($arr['bkgId']);
		$bookingUserModel	 = $bookingModel->bkgUserInfo;
		$agentModel			 = Agents::model()->findByPk($bookingModel->bkg_agent_id);
		$userType			 = EmailLog::Agent;
		if ($agentModel->agt_type == 1)
		{
			$userType = EmailLog::Corporate;
		}
		$sendToUser = $arr['sendToUser'];

		//Agent
		$arrNotify['is_agent_email']	 = ($bookingPrefModel->bkg_crp_send_email == 1) ? 1 : 0;
		$arrNotify['is_agent_phone']	 = ($bookingPrefModel->bkg_crp_send_sms == 1) ? 1 : 0;
		$arrNotify['agent_email']		 = ($bookingUserModel->bkg_crp_email != '') ? $bookingUserModel->bkg_crp_email : '';
		$arrNotify['agent_phone']		 = ($bookingUserModel->bkg_crp_phone != '') ? $bookingUserModel->bkg_crp_phone : '';
		$arrNotify['agent_name']		 = ($bookingUserModel->bkg_crp_name != '') ? $bookingUserModel->bkg_crp_name : '';
		$arrNotify['agent_country_code'] = ($bookingUserModel->bkg_crp_country_code != '') ? $bookingUserModel->bkg_crp_country_code : '91';

		//GozoManager
		$arrNotify['gozomanager_email']	 = ($agentModel->agt_copybooking_admin_email != '') ? $agentModel->agt_copybooking_admin_email : '';
		$arrNotify['gozomanager_phone']	 = ($agentModel->agt_copybooking_admin_phone != '') ? $agentModel->agt_copybooking_admin_phone : '';
		$arrNotify['gozomanager_name']	 = '';
		if ($agentModel->agt_copybooking_admin_id != '')
		{
			$adminModel						 = Admins::model()->findByPk($agentModel->agt_copybooking_admin_id);
			$arrNotify['gozomanager_email']	 = $adminModel->adm_email;
			// $arrNotify['gozomanager_phone'] = ''; //admin phone not exists;
			$arrNotify['gozomanager_name']	 = $adminModel->adm_fname;
		}

		//user
		$arrNotify['is_traveller_email'] = ($bookingPrefModel->bkg_trv_send_email == 1) ? 1 : 0;
		$arrNotify['is_traveller_sms']	 = ($bookingPrefModel->bkg_trv_send_sms == 1) ? 1 : 0;
		$arrNotify['trvl_email']		 = ($bookingModel->bkg_user_email != '') ? $bookingModel->bkg_user_email : '';
		$arrNotify['trvl_phone']		 = ($bookingModel->bkg_contact_no != '') ? $bookingModel->bkg_contact_no : '';
		$arrNotify['trvl_name']			 = $bookingModel->bkg_user_name . " " . $bookingModel->bkg_user_lname;
		$arrNotify['trvl_country_code']	 = ($bookingModel->bkg_country_code != '') ? $bookingModel->bkg_country_code : '91';

		//Email
		$emailArr = [];
		if ($arrNotify['is_agent_email'] == 1 && $arrNotify['agent_email'] != '')
		{
			$emailArr[$userType] = ['email' => $arrNotify['agent_email'], 'name' => $arrNotify['agent_name']];
		}
		if ($arrNotify['is_traveller_email'] == 1 && $arrNotify['trvl_email'] != '' && $sendToUser)
		{
			$emailArr[EmailLog::Consumers] = ['email' => $arrNotify['trvl_email'], 'name' => $arrNotify['trvl_name']];
		}
		if ($arrNotify['gozomanager_email'] != '')
		{
			$emailArr[EmailLog::Admin] = ['email' => $arrNotify['gozomanager_email'], 'name' => $arrNotify['gozomanager_name']];
		}


		//Phone
		$phoneArr = [];
		if ($arrNotify['is_agent_phone'] == 1 && $arrNotify['agent_phone'] != '')
		{
			$phoneArr[$userType] = ['phone' => $arrNotify['agent_phone'], 'country_code' => $arrNotify['agent_country_code']];
		}
		if ($arrNotify['gozomanager_phone'] != '')
		{
			$phoneArr[SmsLog::Admin] = ['phone' => $arrNotify['gozomanager_phone'], 'country_code' => '91'];
		}
		if ($arrNotify['is_traveller_sms'] == 1 && $arrNotify['trvl_phone'] != '' && $sendToUser)
		{
			$phoneArr[SmsLog::Consumers] = ['phone' => $arrNotify['trvl_phone'], 'country_code' => $arrNotify['trvl_country_code']];
		}

		return ['email' => $emailArr, 'sms' => $phoneArr];
	}

	public function getDetailsbyId($agtid)
	{
		/* @var $model Agents */
		$criteria			 = new CDbCriteria;
		$criteria->compare('agt_id', $agtid);
		$model				 = $this->find($criteria);
		$data				 = array_filter($model->attributes);
		$data['agt_type']	 = $model->agt_type;
//        $data['consumer_name'] = $model->getUsername();
//        $data['consumer_phone'] = $model->getContactNumber();
//        $data['consumer_alt_phone'] = $model->getAlternateNumber();
//        $data['route_name'] = $model->bkgFromCity->cty_name . '-' . $model->bkgToCity->cty_name;
//        $data['pick_date'] = DateTimeFormat::DateTimeToLocale($model->bkg_pickup_date);
//        if ($model->bkg_booking_type == 2) {
//            $data['return_date'] = DateTimeFormat::DateTimeToLocale($model->bkg_return_date);
//        }
//        $data['booking_type'] = $model->getBookingType($model->bkg_booking_type);
//        $data['payable_amount'] = ($model->bkg_trip_type == 1) ? $model->bkg_total_amount : '@â‚¹' . $model->bkg_rate_per_km . "/km";
//        $data['from_city'] = $model->bkgFromCity->cty_name;
//        $data['to_city'] = $model->bkgToCity->cty_name;
//        $data['trip_type'] = $model->getTripType();
//
//        $data['driver_name'] = $cabmodel->bcb_driver_name;
//        $data['cab_type'] = $model->bkgVehicleType->vht_model;
//        $data['cab_assigned'] = $cabmodel->bcbCab->vhcType->vht_model . ' ' . $cabmodel->bcbCab->vhc_number;
//        $data['vendor_name'] = $cabmodel->bcbVendor->vnd_name;
//        $data['status'] = $model->getBookingStatus($model->bkg_status);

		return $data;
	}

	public function getAgentsFromBooking($search = true, $agentType = '')
	{
		$condAgentType = " agt_type IN(0,1,2)";
		if ($agentType !== '')
		{
			$condAgentType = " agt_type='$agentType'";
		}
		$condAgentType .= " AND  agt_approved=1";
		if ($search)
		{
			$sql = "select agt_id as id,
			CONCAT( IFNULL(agt_company, ''),  IFNULL(IF(agt_company IS NOT NULL AND agt_company <> '', CONCAT(\" (\", IFNULL(agt_fname,''), ' ', IFNULL(agt_lname,''), \")\"), CONCAT(IFNULL(agt_fname,''), ' ', IFNULL(agt_lname,''))), ''), IF(agt_type = 0, '-TRAVEL', IF(agt_type = 1, '-CORPORATE', '-RESELLER'))) text from ( 
			SELECT  
			agt_id,
			agt_company,
			agt_fname,
			agt_lname,
			agt_type
			FROM     agents
			WHERE  EXISTS (SELECT  bkg_agent_id FROM booking WHERE agt_id=bkg_agent_id and  bkg_active = 1) AND agt_active > 0 AND $condAgentType) a 
			ORDER BY text";
		}
		else
		{
			$sql = "SELECT agt_id id, CONCAT( IFNULL(agt_company,''), IFNULL(IF(agt_company IS NOT NULL AND agt_company <> '',CONCAT(\" (\",agt_fname,' ',agt_lname,\")\"),CONCAT(agt_fname,' ',agt_lname)),''), IF(agt_type = 0,'-TRAVEL',IF(agt_type=1,'-CORPORATE','-RESELLER')) ) text FROM agents WHERE  agt_active=1 AND $condAgentType ORDER BY text";
		}
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		$data	 = CJSON::encode($rows);
		if ($data == '' || $data == null || $data == 'null')
		{
			$data = '[]';
		}
		return $data;
	}

	public function getCompanyType($firmid = 0)
	{
		$firmType = $this->company_type;
		if ($firmid > 0)
		{
			return $firmType[$firmid];
		}
		else
		{
			return $firmType;
		}
	}

	public function getCorporateCodes($tag = '')
	{
		$arrBillingType = DBUtil::command("SELECT agt_id id, CONCAT(IFNULL(agt_company,''),\" (\",IFNULL(agt_referral_code,''),\")\") text FROM agents WHERE agt_active = 1 AND agt_type = 1 AND agt_approved = 1")->queryAll();
//        foreach ($arr as $row) {
//            $arrBillingType[] = array("id" => $row['agt_id'], "text" => $row['agt_referral_code'] . " " . $row['agt_company']);
//        }
		if ($tag != '')
		{
			$arrBillingType['id'][$tag] = $arrBillingType['text'];
		}
		$data = CJSON::encode($arrBillingType);
		return $data;
	}

	public function calculateCost($baseFare)
	{
		$commission = $this->calculateCommission($baseFare);
		return ($baseFare - $commission);
	}

	public function calculateCommission($amount)
	{
		if ($this->agt_commission_value == 1)
		{
			$commission = round($amount * ($this->agt_commission / 100), 2);
		}
		else
		{
			$commission = $this->agt_commission;
		}
		return $commission;
	}

	public function calculateSellPrice($cost)
	{
		if ($this->agt_commission_value == 1)
		{
			$price = round($cost * 100 / (100 - $this->agt_commission));
		}
		else
		{
			$price = $cost + $this->agt_commission;
		}
		return $price;
	}

	public function getBaseDiscFare($quoteRate, $agtType = 2, $agentId)
	{
		if ($agtType == 0 || $agtType == 1)
		{
			$gozoAmount					 = $quoteRate->totalAmount - $quoteRate->vendorAmount; // $arrQuote['total_amt'] - $arrQuote['vendor_amount'];
			// $discAmount = round($arrQuote['base_amt'] * 0.05);
			$agentModel					 = Agents::model()->findByPk($agentId);
			$agentModel->agt_commission	 = ($agentModel->agt_commission == '') ? 0 : $agentModel->agt_commission;
			if ($agentModel->agt_commission_value == 1)
			{
				$discAmount = round($quoteRate->baseAmount * ($agentModel->agt_commission / 100));
			}
			else
			{
				$discAmount = $agentModel->agt_commission;
			}
			if ($gozoAmount > 0 && $discAmount > $gozoAmount)
			{
				$discAmount = round($gozoAmount);
			}
			$quoteRate->baseAmount									 = $quoteRate->baseAmount - $discAmount;
			$bookingsModel											 = new Booking();
			$bookingsModel->bkgInvoice								 = new BookingInvoice();
			$bookingsModel->bkgInvoice->bkg_base_amount				 = $quoteRate->baseAmount;
			$bookingsModel->bkgInvoice->bkg_driver_allowance_amount	 = $quoteRate->driverAllowance; //$arrQuote['driverAllowance'];
			$bookingsModel->bkgInvoice->bkg_toll_tax				 = $quoteRate->tollTaxAmount; // $arrQuote['toll_tax'];
			$bookingsModel->bkgInvoice->bkg_state_tax				 = $quoteRate->stateTax; // $arrQuote['state_tax'];
			$bookingsModel->bkgInvoice->bkg_vendor_amount			 = $quoteRate->vendorAmount;
			$bookingsModel->bkgInvoice->bkg_night_pickup_included	 = $quoteRate->isNightPickupIncluded;
			$bookingsModel->bkgInvoice->bkg_night_drop_included		 = $quoteRate->isNightDropIncluded;

			$bookingsModel->bkgInvoice->calculateConvenienceFee(0);
			$bookingsModel->bkgInvoice->populateAmount(true, false, true, false, $bookingsModel->bkg_agent_id);
			$quoteRate->gst			 = $bookingsModel->bkgInvoice->bkg_service_tax;
			$quoteRate->totalAmount	 = $bookingsModel->bkgInvoice->bkg_total_amount;
		}
		return $quoteRate;
	}

	public function getAgentBaseDiscFare($arrQuote, $agtType = 2, $agentId)
	{
		if ($agtType == 0 || $agtType == 1)
		{
			$gozoAmount					 = $arrQuote['total_amt'] - $arrQuote['vendor_amount'];
			// $discAmount = round($arrQuote['base_amt'] * 0.05);
			$agentModel					 = Agents::model()->findByPk($agentId);
			$agentModel->agt_commission	 = ($agentModel->agt_commission == '') ? 0 : $agentModel->agt_commission;
			if ($agentModel->agt_commission_value == 1)
			{
				$discAmount = round($arrQuote['base_amt'] * ($agentModel->agt_commission / 100));
			}
			else
			{
				$discAmount = $agentModel->agt_commission;
			}

			if ($gozoAmount > 0 && $discAmount > $gozoAmount)
			{
				$discAmount = round($gozoAmount);
			}
			$arrQuote['base_amt']									 = $arrQuote['base_amt'] - $discAmount;
			$bookingsModel											 = new Booking();
			$bookingsModel->bkgInvoice->bkg_base_amount				 = $arrQuote['base_amt'];
			$bookingsModel->bkgInvoice->bkg_driver_allowance_amount	 = $arrQuote['driverAllowance'];
			$bookingsModel->bkgInvoice->bkg_toll_tax				 = $arrQuote['toll_tax'];
			$bookingsModel->bkgInvoice->bkg_state_tax				 = $arrQuote['state_tax'];
			$bookingsModel->bkgInvoice->bkg_vendor_amount			 = $arrQuote['vendor_amount'];
			$bookingsModel->calculateConvenienceFee(0);
			$bookingsModel->populateAmount(true, false, true, false, $bookingsModel->bkg_agent_id);
			$arrQuote['service_tax']								 = $bookingsModel->bkgInvoice->bkg_service_tax;
			$arrQuote['total_amt']									 = $bookingsModel->bkgInvoice->bkg_total_amount;
		}
		return $arrQuote;
	}

	public function getShuttleBaseDiscFare($shuttleData, $cabType, $baseamount)
	{
		$agtType = $this->agt_type;
		if ($agtType == 2 || $agtType == 1)
		{
			$defMarkup	 = Quotation::model()->getCabDefaultMarkup($cabType);
			$gozoAmount	 = $shuttleData['slt_price_per_seat'] - $shuttleData['vendor_amount'] - $shuttleData['slt_gst'];
			$gozoMarkup	 = round($baseamount * ($defMarkup / 100));

			$this->agt_commission = ($this->agt_commission == '') ? 0 : $this->agt_commission;
			if ($this->agt_commission_value == 1)
			{
				$commAmount = round($baseamount * ($this->agt_commission / 100));
			}
			else
			{
				$commAmount = $this->agt_commission;
			}
			if ($gozoAmount > 0 && $commAmount > $gozoAmount && $agtType == 1)
			{
				$commAmount						 = round($gozoAmount);
				$shuttleData['slt_base_fare']	 = $baseamount - $commAmount;
			}
			if ($gozoMarkup > 0 && $commAmount > $gozoMarkup && $agtType == 2)
			{

				$diffAmount						 = $commAmount - $gozoMarkup;
				$commPerc						 = (($commAmount * 100) / ($baseamount)); //incase agtcommission is amount , not perc
				$reqDiff						 = $diffAmount * (1 + ($commPerc / 100));
				$shuttleData['slt_base_fare']	 = round($baseamount + $reqDiff);
			}
			$bivModel								 = new BookingInvoice();
			$bivModel->bkg_base_amount				 = $shuttleData['slt_base_fare'];
			$bivModel->bkg_driver_allowance_amount	 = $shuttleData['slt_driver_allowance'];
			$bivModel->bkg_toll_tax					 = $shuttleData['slt_toll_tax'];
			$bivModel->bkg_state_tax				 = $shuttleData['slt_state_tax'];
			$bivModel->bkg_vendor_amount			 = $shuttleData['vendor_amount'];
			$bivModel->calculateConvenienceFee(0);
			$bivModel->populateAmount(true, false, true, false, $this->agt_id);
			$shuttleData['slt_gst']					 = $bivModel->bkg_service_tax;
			$shuttleData['slt_price_per_seat']		 = $bivModel->bkg_total_amount;
		}
		return $shuttleData;
	}

	public function getRegistrationProgress($voterId, $driverLicense, $aadhar, $firstName, $type = 'data')
	{
		$sql = "SELECT
        agt.agt_id,agt.agt_fname,agt.agt_email,cty.cty_name,
        IF(
        agt.agt_approved != '',
        agt.agt_approved,
        'No'
        ) AS approved,
        IF(
        agt.agt_voter_id != '',
        agt.agt_voter_id,
        'No'
       ) AS voterPath,
       IF(
        agt.agt_aadhar != '',
        agt.agt_aadhar,
        'No'
       ) AS aadharPath,
       IF(
       agt.agt_driver_license != '',
       agt.agt_driver_license,
       'No'
       ) AS driverLicense,
       IF(
       agt.agt_trade_license != '',
       agt.agt_trade_license,
       'No'
       ) AS tradeLicense,
       IF(
       agt.agt_bank != '',
       agt.agt_bank,
       'No'
       ) as bankDeatils,
       agt.agt_create_date
       FROM agents agt
       INNER JOIN cities cty ON cty.cty_id = agt.agt_city AND cty.cty_active =1 WHERE 1=1";

		if ($voterId == 1)
		{
			$isVoterSql	 = " AND agt.agt_voter_id IS NOT NULL AND agt.agt_voter_id <>'' ";
			$sql		 .= $isVoterSql;
		}
		if ($driverLicense == 1)
		{
			$isdriverLicenseSql	 = " AND agt.agt_driver_license IS NOT NULL AND agt.agt_driver_license <>'' ";
			$sql				 .= $isdriverLicenseSql;
		}
		if ($aadhar == 1)
		{
			$IsAadhar	 = " AND agt.agt_aadhar IS NOT NULL AND agt.agt_aadhar <>'' ";
			$sql		 .= $IsAadhar;
		}
		if ($firstName != '')
		{
			$isNameSql	 = " AND (agt.agt_fname LIKE '%$firstName%' OR agt.agt_fname LIKE '%$firstName%')";
			$sql		 .= $isNameSql;
		}

		if ($type == 'data')
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['agt_name', 'agt_email', 'cty_name', 'approved', 'voterPath', 'aadharPath', 'driverLicense', 'tradeLicense', 'bankDeatils', 'agt_create_date'],
					'defaultOrder'	 => ''],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else if ($type == 'command')
		{
			$sql .= " ORDER BY agt.agt_create_date ASC";
			return DBUtil::queryAll($sql);
		}
	}

	public function getCancelChargeRule($bkgAmount)
	{
		$cancelCharge = 0;
		switch ($rule)
		{
			case 1:
				$cancelCharge				 = round($bkgAmount * 0.15);
				break;
			case 2:
				$cancelCharge				 = 500;
				break;
			default :
				$fifteenPercentBookingAmt	 = round($bkgAmount * 0.15);
				$maxCancelCharge			 = 500;
				$cancelCharge				 = ($maxCancelCharge > $fifteenPercentBookingAmt) ? $maxCancelCharge : $fifteenPercentBookingAmt;
				break;
		}
		return $cancelCharge;
	}

	public function getJSONAllPartnersbyQuery($query, $agt = '', $showInactive = '0')
	{
		$rows		 = $this->getAllPartnersbyQuery($query, $agt, $showInactive);
		$arrCities	 = array();
		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['agt_id'], "text" => $row['agt_name']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	public function getAllPartnersbyQuery($query = '', $agt = '', $onlyActive = '0')
	{
		$qry		 = '';
		$limitNum	 = 30;

		if ($agt != '')
		{
			$qry1		 = " AND 1 OR agt_id='$agt'";
			$limitNum	 = 29;
		}
		if ($query == '')
		{
			$qry .= " agt_id IN (SELECT bkg_agent_id FROM (SELECT bkg_agent_id, COUNT(*) as cnt FROM booking
		WHERE bkg_status IN (2,3,5,6,7,9) AND bkg_agent_id IS NOT NULL
                        GROUP BY bkg_agent_id ORDER BY cnt DESC LIMIT 0, $limitNum) a)";
		}
		else
		{
			$qry .= " concat(agt.agt_fname, ' ',agt.agt_lname) LIKE '%{$query}%' OR agt.agt_company LIKE '%{$query}%' ";
		}
		if ($onlyActive == '1')
		{
			$qry .= " AND agt.agt_active = 1";
		}
		$sql = "SELECT agt.agt_id, 
						TRIM(IF((agt.agt_company='' OR agt.agt_company IS NULL), 
								TRIM(CONCAT(IFNULL(agt.agt_fname,''), ' ', IFNULL(agt.agt_lname, ''))),
								CONCAT(agt.agt_company,' (', IFNULL(agt.agt_fname,''), ' ',IFNULL(agt.agt_lname,''),')',
										IF(agt_type=0, ' - TRAVEL', IF(agt_type=1, ' - CORPORATE', ' - RESELLER'))
							))) as agt_name
                FROM agents agt
                WHERE $qry $qry1 HAVING agt_name <>'' OR agt_name IS NOT NULL
                ORDER BY  agt.agt_fname,agt.agt_lname LIMIT 0, 20";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function checkEmail($agtid, $email)
	{
		$qry		 = "select count(*)as tot from agents where agt_id!= " . $agtid . " AND agt_email='" . $email . "' AND agt_active IN(1,3) ";
		$recordset	 = DBUtil::queryRow($qry);
		return $recordset;
	}

	public function getModificationMSG($diff, $user)
	{
		//message
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff['consumer_name'])
			{
				$msg .= ' Customer Name: ' . $diff['consumer_name'] . ',';
			}
			if ($diff['consumer_phone'])
			{
				$msg .= ' Customer Phone: ' . $diff['consumer_phone'] . ',';
			}

			if ($diff['bkg_user_email'])
			{
				$msg .= ' Customer Email: ' . $diff['bkg_user_email'] . ',';
			}
			if ($diff['consumer_alt_phone'])
			{
				$msg .= ' Alternate Phone: ' . $diff['consumer_alt_phone'] . ',';
			}
			if ($diff['route_name'])
			{
				$msg .= ' Route: ' . $diff['route_name'] . ',';
			}
			if ($diff['booking_type'])
			{
				$msg .= ' Booking Type: ' . $diff['booking_type'] . ',';
			}
			if ($diff['pick_date'])
			{
				$msg .= ' Pickup Date/Time: ' . $diff['pick_date'] . ',';
			}
			if ($diff['return_date'])
			{
				$msg .= ' Return Date/Time: ' . $diff['return_date'] . ',';
			}
			if ($diff['bkg_pickup_address'])
			{
				$msg .= ' Pickup Address: ' . $diff['bkg_pickup_address'] . ',';
			}
			if ($diff['brt_pickup_location'])
			{
				$msg .= ' Pickup Address: ' . $diff['brt_pickup_location'] . ',';
			}

			if ($diff['bkg_drop_address'])
			{
				$msg .= ' Drop Address: ' . $diff['bkg_drop_address'] . ',';
			}
			if ($diff['bkg_additional_charge'])
			{
				$msg .= ' Additional Charge: ' . $diff['bkg_additional_charge'] . ',';
			}
			if ($diff['payable_amount'])
			{
				$msg .= ' Payable Amount: ' . $diff['payable_amount'] . ',';
			}
			if ($diff['bkg_driver_allowance_amount'])
			{
				$msg .= ' Driver allowance: ' . $diff['bkg_driver_allowance_amount'] . ',';
			}
			if ($diff['bkg_rate_per_km_extra'])
			{
				$msg .= ' Extra rate: ' . $diff['bkg_rate_per_km_extra'] . ',';
			}

			if ($user != 'consumer')
			{
				if ($diff['bkg_instruction_to_driver_vendor'])
				{
					$msg .= ' Special Instruction: ' . $diff['bkg_instruction_to_driver_vendor'] . ',';
				}
			}
			if ($user == 'log')
			{
				if ($diff['bkg_vendor_amount'])
				{
					$msg .= ' Vendor Amount: ' . $diff['bkg_vendor_amount'] . ',';
				}
				if ($diff['bkg_total_amount'])
				{
					$msg .= ' Booking Amount: ' . $diff['bkg_total_amount'] . ',';
				}
				if ($diff['bkg_gozo_amount'])
				{
					$msg .= ' Gozo Amount: ' . $diff['bkg_gozo_amount'] . ',';
				}
				if ($diff['bkg_advance_amount'])
				{
					$msg .= ' Customer Advance: ' . round($diff['bkg_advance_amount']) . ',';
				}
				if ($diff['bkg_vendor_collected'])
				{
					$msg .= ' Vendor Collected: ' . $diff['bkg_vendor_collected'] . ',';
				}
				if ($diff['bkg_refund_amount'])
				{
					$msg .= ' Amount Refunded: ' . $diff['bkg_refund_amount'] . ',';
				}
				if ($diff['bkg_due_amount'])
				{
					$msg .= ' Customer Payment due: ' . round($diff['bkg_due_amount']) . ',';
				}
				if ($diff['bkg_trip_distance'])
				{
					$msg .= ' Kms Driven: ' . $diff['bkg_trip_distance'] . ',';
				}
				if ($diff['bkg_convenience_charge'] != '')
				{
					$msg .= ' COD Charge: ' . round($diff['bkg_convenience_charge']) . ',';
				}
				if ($diff['bkg_driver_allowance_amount'] != '')
				{
					$msg .= ' Driver Allowance: ' . round($diff['bkg_driver_allowance_amount']) . ',';
				}
				if ($diff['bkg_credits_used'])
				{
					$msg .= ' Credits Used: ' . $diff['bkg_credits_used'] . ',';
				}
				if ($diff['bkg_base_amount'])
				{
					$msg .= ' Base Amount: ' . $diff['bkg_base_amount'] . ',';
				}


				if ($diff['bkg_invoice'])
				{
					$msg .= ' Invoice Requirement Changed,';
				}
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function calculateProcessingFee($amount)
	{
		$processingFees	 = Config::get("vendor.account.processingFee");
		return round(((abs($amount) * $processingFees) >= 1) ? (abs($amount) * $processingFees) : 1);
	}

	public function updateCoins($amount, $agentId, $date = '', $bankLedgerID, $bankRefId = 0, $transDesc = '', $mode = 0)
	{
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = $amount;
		$accTransModel->act_date	 = ($date == '') ? new CDbExpression('NOW()') : $date;
		$accTransModel->act_type	 = Accounting::AT_PARTNER;
		$accTransModel->act_ref_id	 = $agentId;
		$accTransModel->act_remarks	 = $transDesc;
		//  $accTransModel->AddReceipt($bankLedgerID,Accounting::LI_PARTNER,$bankRefId,$agentId,$transDesc,Accounting::AT_ONLINEPAYMENT);
		$bankCharge					 = 0;
		if ($mode != PaymentResponse::TYPE_UPI)
		{
			$bankCharge = Agents::model()->calculateProcessingFee($amount);
		}
		$accTransModel->AddPartnerCoins($bankLedgerID, Accounting::LI_PARTNER, $bankRefId, $agentId, "Payment added successfully", $bankCharge);
	}

	/**
	 * Process the account entries after payment confirmation 
	 * @param integer $amount amount used in payment
	 * @param integer $partnerId Partner id
	 * @param string $date Date-time of transaction
	 * @param integer $bankLedgerID Ledger id of the payment type
	 * @param integer $bankRefId reference id of payment 
	 * @param string $transDesc Description of the response received 
	 * @param integer $mode Mode of payment (UPI/CC/DC/Online Banking)
	 * @param integer $paymentId Payment id received from the payment source
	 * @param integer $paymentCode Payment reference code from Gozo
	 * @return boolean
	 */
	public function processPayment($amount, $partnerId, $date, $bankLedgerID, $bankRefId = 0, $transDesc = '', $mode = 0, $paymentId = 0, $paymentCode = 0)
	{
		$accTransModel				 = new AccountTransactions();
		$accTransModel->act_amount	 = $amount;
		$accTransModel->act_date	 = ($date == '') ? new CDbExpression('NOW()') : $date;
		$accTransModel->act_type	 = Accounting::AT_PARTNER;
		$accTransModel->act_ref_id	 = $partnerId;
		$accTransModel->act_remarks	 = $transDesc;
		$bankCharge					 = 0;
		$remarks					 = "Payment received (ref# $paymentCode / $paymentId)";
		$bankChargeRemarks			 = '';
		if ($mode != PaymentResponse::TYPE_UPI)
		{
			$bankCharge			 = Agents::model()->calculateProcessingFee($amount);
			$bankChargeRemarks	 = "Bank charge deducted (ref# $paymentCode)";
		}
		$success = $accTransModel->AddPartnerPayment($partnerId, $bankLedgerID, $bankRefId, $amount, $bankCharge, $remarks, $bankChargeRemarks);
		return $success;
	}

	public function onRechargeUpdateBooking($bkgId)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$prevStatus	 = $model->bkg_status;
		$amount		 = $model->bkg_total_amount | 0;
		$desc		 = "Partner Coins added to booking by admin";
		$result		 = $model->updateAdvance($amount, '', PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, $desc);
		if (!$result)
		{
			return false;
		}
		$model->bkg_corporate_remunerator	 = 2;
		$model->bkg_status					 = 2;
		$model->save();
		if ($prevStatus == 1 && $model->bkg_status == 2)
		{
			emailWrapper::confirmBooking($model->bkg_id, UserInfo::TYPE_SYSTEM);
			$msgCom = new smsWrapper();
			$msgCom->gotBooking($model, UserInfo::TYPE_SYSTEM);
		}
	}

	public function getByAgentId($agtId, $viewType = 0)
	{
		//$defaultSort = 'vlg_created  DESC';
		$pageSize = 25;
		if ($viewType == 1)
		{
			$pageSize = 20;
		}
		$sql			 = "SELECT agent_log.*,
					(CASE agent_log.agl_usr_type
						WHEN 3 THEN 'System'
						WHEN 2 THEN CONCAT(admins.adm_fname,' ',admins.adm_lname)
						WHEN 1 THEN IF(agents.agt_company!=NULL,agents.agt_company,CONCAT(agents.agt_fname,' ',agents.agt_lname))
						ELSE '' END
					) as name,
					(CASE agent_log.agl_usr_type 
						WHEN 1 then 'Agent'
						WHEN 2 THEN 'Admin'
						WHEN 3 then 'System'
						WHEN 4 then 'Consumer'
						ELSE '' END
					) as type
					FROM `agent_log`
					JOIN `agents` ON agents.agt_id=agent_log.agl_agent_id
					LEFT JOIN `admins` ON admins.adm_id=agent_log.agl_usr_ref_id
					WHERE agent_log.agl_agent_id='$agtId'";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['agl_desc', 'agl_event_id', 'agl_created', 'name', 'type'],
				'defaultOrder'	 => 'agl_created  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	//validate agent 
	public function validateAgent($header)
	{
		$validate		 = true;
		$userId			 = trim($header['HTTP_X_REST_UID']);
		$checksum		 = trim($header['HTTP_X_REST_CHECKSUM']);
		$env			 = trim($header['HTTP_X_REST_ENV']);
		$user_details	 = Users::model()->findByPk($userId);

		$user_password	 = $user_details['usr_password'];
		$checksumstring	 = $userId . $user_password . $env;

		$originalChecksum = Users::model()->encrypt($checksumstring);
		if ($checksum != $originalChecksum)
		{
			$validate = false;
		}
		return $validate;
	}

	// crete checksum for first time login of an agent
	/* static function checksumChanelPartner($userId,$password,$env,$salt)
	  {
	  echo $salt = $salt.'|'.$userId.'|'.$password.'|'.$env;
	  return $salt;
	  } */


	/*
	 * Searching all agents by email.
	 */
	public function findByEmailInAll($email)
	{
		return $this->find('agt_username=:username OR agt_email=:username ', ['username' => $email]);
	}

	/*
	 * Searching all agents by phone.
	 */

	public function findByPhoneInAll($phone)
	{
		return $this->find('agt_phone=:phone ', ['phone' => $phone]);
	}

	/*
	 * Updating agent password by user password from user table in cpaa app.
	 */

	public function updateAgentPassword($agt_id, $password)
	{
		$sql = "UPDATE `agents` SET `agt_password` = '$password' WHERE `agents`.`agt_id` = $agt_id";
		return DBUtil::command($sql)->execute();
	}

	public function getCollection($partnerid = 0, $sort = 'agt_company')
	{
		$where = '';
		if ($partnerid > 0)
		{
			$where = "AND  agt_id = $partnerid";
		}
		$sql = "SELECT res.*,res.walletBal WalletBalance,
				if(res.ledgerBal-(res.walletBal) > 0, res.ledgerBal-(res.walletBal), 0) 'Receivable',
				if(res.ledgerBal-(res.walletBal) < 0, res.ledgerBal-(res.walletBal), 0) 'Payable'
				FROM 
					(
							SELECT 
							agt_id,
							agt_company,
							agt_fname,
							agt_lname,
							agt_type,
							agt_active,
							pts.pts_wallet_balance walletBal,
							pts.pts_ledger_balance ledgerBal,
							pts.pts_id
							FROM agents
							JOIN partner_stats AS pts
							   ON pts.pts_id = (SELECT pts1.pts_id
												FROM partner_stats AS pts1
												WHERE pts1.pts_agt_id = agents.agt_id
												ORDER BY pts1.pts_id ASC
												LIMIT 1)
							WHERE  pts.pts_wallet_balance IS NOT NULL AND pts.pts_wallet_balance<>0	AND pts.pts_ledger_balance IS NOT NULL AND pts.pts_ledger_balance<>0	AND agt_active = 1 $where
					) res
				ORDER BY $sort DESC";

		return DBUtil::query($sql, DBUtil::MDB());
	}

	public function getTotalCollection($partnerid = 0)
	{
		$where = '';
		if ($partnerid > 0)
		{
			$where = "AND  agt_id = $partnerid";
		}
		$sql = "SELECT SUM(res.walletBal) as totalWalletBal, 
				SUM(if(res.ledgerBal-(res.walletBal) > 0, res.ledgerBal-(res.walletBal), 0)) as totalReceived,
				SUM(if(res.ledgerBal-(res.walletBal) < 0, res.ledgerBal-(res.walletBal), 0)) as totalPayable
				FROM
					(
							SELECT 
							agt_id,
							agt_company,
							agt_fname,
							agt_lname,
							agt_type,
							agt_active,
							pts.pts_wallet_balance walletBal,
							pts.pts_ledger_balance ledgerBal,
							pts.pts_id
							FROM agents
							JOIN partner_stats AS pts
							   ON pts.pts_id = (SELECT pts1.pts_id
												FROM partner_stats AS pts1
												WHERE pts1.pts_agt_id = agents.agt_id
												ORDER BY pts1.pts_id ASC
												LIMIT 1)
							WHERE  pts.pts_wallet_balance IS NOT NULL AND pts.pts_wallet_balance<>0	AND pts.pts_ledger_balance IS NOT NULL AND pts.pts_ledger_balance<>0	AND agt_active = 1 $where
					) res ";

		return DBUtil::queryRow($sql, DBUtil::MDB());
	}

	public function linkToAgent($email, $phone, $userId)
	{
		$agents							 = Agents::model()->find('agt_email=:email OR agt_phone=:phone', ['email' => $email, 'phone' => $phone]);
		$users							 = Users::model()->find("user_id=:id", ['id' => $userId]);
		// link with other agent through   
		$agentUserModel					 = new AgentUsers();
		$agentUserModel->agu_user_id	 = $userId;
		$agentUserModel->agu_agent_id	 = $agents->agt_id;
		$agentUserModel->agu_role		 = 1;
		$agentUserModel->save();
		$users->usr_verification_code	 = '';
		if ($users->update())
		{
			$success = true;
		}
		else
		{
			$success = false;
		}
		return $success;
	}

	public function createToken($userId, $agentId)
	{
		$agtModel							 = Agents::model()->findByPk($agentId);
		$userHashId							 = Yii::app()->shortHash->hash($userId);
		$agentHashId						 = Yii::app()->shortHash->hash($agentId);
		$randNumber							 = rand();
		$token								 = trim($userHashId . '-' . $agentHashId . '/' . $randNumber);
		$agtModel->agt_id					 = $agentId;
		$agtModel->agt_verify_myacc_section	 = $token;
		$agtModel->update();
		return $token;
	}

	public function getAgentIdByAuthtoken($authtoken)
	{
		$sql = "SELECT 	agt_id,agt_verify_myacc_section FROM `agents` WHERE agt_verify_myacc_section='$authtoken'";
		return DBUtil::queryRow($sql);
	}

	public function getAgentCompany($userId)
	{
		$refId		 = 0;
		$refType	 = 0;
		$agtCompany	 = "";
		$dmodel		 = Drivers::model()->findByUserid($userId);
		if ($dmodel)
		{
			$refType	 = 2;
			$refId		 = $dmodel->drv_id;
			$agtCompany	 = $dmodel->drv_name . " (" . $dmodel->drv_code . ")";
		}
		else
		{
			$vmodel			 = Vendors::model()->getByUserId($userId);
			$refType		 = 1;
			$refId			 = $vmodel->vnd_id;
			$vndContactId	 = ContactProfile::model()->getByEntityId($refId, UserInfo::TYPE_VENDOR);

			$cmodel		 = Contact::model()->getContactDetails($vndContactId);
			$name		 = ($cmodel['ctt_business_name'] != "") ? $cmodel['ctt_business_name'] : ($cmodel['ctt_first_name'] . " " . $cmodel['ctt_last_name']);
			$agtCompany	 = $name . " (" . $vmodel->vnd_code . ")";
		}
		$result = array(
			'refType'	 => $refType,
			'refId'		 => $refId,
			'agtCompany' => $agtCompany
		);

		return $result;
	}

	public function getAgentApprovedStatus($userId)
	{
		$agtApproved = 0;
		$dmodel		 = Drivers::model()->findByUserid($userId);
		if ($dmodel)
		{
			$cmodel		 = Contact::model()->findByPk($dmodel->drv_contact_id);
			$agtApproved = ($cmodel->ctt_active == 1 && $dmodel->drv_approved == 1) ? 1 : 0;
		}
		else
		{
			$vmodel			 = Vendors::model()->getByUserId($userId);
			$vndContactId	 = ContactProfile::model()->getByEntityId($vmodel->vnd_id, UserInfo::TYPE_VENDOR);
			$cmodel			 = Contact::model()->findByPk($vndContactId);
			$agtApproved	 = ($cmodel->ctt_active == 1 && $vmodel->vnd_active == 1) ? 1 : 0;
		}

		return $agtApproved;
	}

	public function lockedOutstandingBalanceCron()
	{
		try
		{
			$sql		 = "SELECT agt_id,agt_code,agt_ref_id FROM `agents` WHERE `agt_ref_type` = 1 AND agt_ref_id IS NOT NULL AND agt_id = 232";
			$recordset	 = DBUtil::query($sql);
			foreach ($recordset as $value)
			{
				$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($value['agt_ref_id']);
				$agentAmount	 = AccountTransDetails::model()->accountTotalSummary($value['agt_id']);
				if ($agentAmount['totAmount'] != 0)
				{
					$amount						 = $agentAmount['totAmount'];
					$drRefId					 = $value['agt_id'];
					$vndStats					 = VendorStats::model()->getbyVendorId($value['agt_ref_id']);
					$vndStats->vrs_locked_amount += $amount;
					if (!$vndStats->save())
					{
						throw new Exception("locked Outstanding partner Balance failed. Amount: " . $amount . "PatnerId: " . $value['agt_id'] . "VendorId: " . $value['agt_ref_id']);
					}
					$drRemarks					 = "Outstanding balance Locked from agent partner (" . $agentAmount['agt_company'] . ") to operator (" . $vendorAmount['vnd_name'] . ", " . $vendorAmount['vnd_code'] . ")";
					$crRemarks					 = "Outstanding balance Locked to operator (" . $vendorAmount['vnd_name'] . ", " . $vendorAmount['vnd_code'] . ")";
					$crRefId					 = NULL;
					$drLedgerId					 = Accounting::LI_PARTNER;
					$drAcctType					 = Accounting::AT_PARTNER;
					$crLedgerID					 = Accounting::LI_OPERATOR;
					$crAccType					 = Accounting::AT_OPERATOR;
					$accTransModel				 = new AccountTransactions();
					$accTransModel->act_amount	 = $amount;
					$accTransModel->act_date	 = new CDbExpression('NOW()');
					$accTransModel->act_type	 = $crAccType;
					$accTransModel->act_ref_id	 = $crRefId;
					$accTransModel->addOutstandingBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, UserInfo::getInstance());
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	public function getAgentDetails($promoId)
	{
		$sql = "select agt_id, concat(agt_fname, ' ' ,agt_lname) as agt_name, agt_phone, agt_email,
				IF(prp_id IS NOT NULL,true,false) as alreadyExist,$promoId promo_id, prp_active
				FROM agents LEFT JOIN gift_card_partner ON prp_partner_id=agt_id AND prp_promo_id = $promoId  where agt_active ='1'";
		if (isset($this->search) && $this->search != "")
		{
			$sql1	 = "select 
						agt_id, concat(agt_fname, ' ' ,agt_lname) as agt_name, agt_phone, agt_email,
						IF(prp_id IS NOT NULL,true,false) as alreadyExist,$promoId promo_id, prp_active
					from 
						agents 
					LEFT JOIN gift_card_partner ON prp_partner_id=agt_id AND prp_promo_id = $promoId
					HAVING 
					(agt_name LIKE '%" . ($this->search) . "%') OR
					(agt_email LIKE '%" . ($this->search) . "%') OR (agt_phone LIKE '%" . ($this->search) . "%')";
			$agtIds	 = DBUtil::queryScalar($sql1, DBUtil::SDB());
			if ($agtIds != 0)
			{
				$sql .= " AND agt_id IN ($agtIds)";
			}
		}
		$sql			 .= " group by agt_id";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['agt_name'],
				'defaultOrder'	 => 'agt_create_date DESC'
			],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getAgentLog($agtid)
	{
		$qry = "select agt_log from agents  where agt_id =" . $agtid;
		return DBUtil::queryRow($qry, DBUtil::SDB());
	}

	public function logModified($oldData = "", $newData = "")
	{
		$arrAgent = Yii::app()->request->getParam('Agents');
		if (isset($arrAgent['agt_id']) && $arrAgent['agt_id'] > 0)
		{
			$model								 = $this->findByPk($arrAgent['agt_id']);
			$agt_city							 = $this->findCityById($arrAgent['agt_id']);
			$agtData							 = $model->attributes;
			$agtData['agt_city']				 = $agt_city[cty_name];
			$agtData['agt_license_expiry_date']	 = DateTimeFormat::DateTimeToDatePicker($model->agt_license_expiry_date);
		}
		else
		{
			$model = new Agents();
			if (isset($arrAgent['agt_id']))
			{
				unset($arrAgent['agt_id']);
			}
			$agtData = [];
		}
		$userInfo			 = UserInfo::getInstance();
		$oldCapturedData	 = array_filter($agtData);
		$model->attributes	 = $arrAgent;
		$newCapturedData	 = array_filter($model->attributes);
		if (isset($newCapturedData['agt_allow_smartmatch']))
		{
			$newCapturedData['agt_allow_smartmatch'] = $newCapturedData['agt_allow_smartmatch'][0];
		}
		else
		{
			$newCapturedData['agt_allow_smartmatch'] = 0;
		}
		if ($newData != "" && isset($newData) && $oldData != "" && isset($oldData))
		{
			$getDifference = array_diff_assoc($newData, $oldData);
		}
		else
		{
			$getDifference = array_diff_assoc($newCapturedData, $oldCapturedData);
		}
		$msg		 = "";
		$labelArr	 = Agents::model()->attributeLabels();
		if (count($getDifference) > 0)
		{
			$msg .= "Details Modified \n:::: Old Values";

			foreach ($getDifference as $key => $value)
			{
				$msg .= ':: ' . $labelArr[$key] . ': ' . (($oldData != "") ? $oldData[$key] : $oldCapturedData[$key]) . "\n";
			}
			$msg .= ":::: New Values";
			foreach ($getDifference as $key => $value)
			{
				$msg .= ':: ' . $labelArr[$key] . ': ' . (($newData != "") ? $newData[$key] : $newCapturedData[$key]) . "\n";
			}

			if ($model->agt_id != "")
			{

				AgentLog::model()->createLog($model->agt_id, $msg, $userInfo, AgentLog::AGENT_UPDATED, false, false);
			}
		}

		return ($newData != "" && isset($newData) && $oldData != "" && isset($oldData)) ? array_diff_assoc($oldData, $newData) : array_diff_assoc($oldCapturedData, $newCapturedData);
	}

	public function getPartnerPerformance($date1 = '', $date2 = '', $agtType = '', $countFilter = '', $type = '')
	{
		if ($date1 != '' && $date2 != '')
		{
			$dateCond = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}
		else
		{
			$dateCond = "bkg.bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
		}

		if ($agtType != '')
		{
			$cpFilter = " AND agents.agt_type IN(" . $agtType . ")";
		}
		else
		{
			$cpFilter = " AND agents.agt_type IN(0,1,2)";
		}

		if ($countFilter != '')
		{
			switch ($countFilter)
			{
				case 1:
					$cntFilter	 = " HAVING totalCompletedCount = 0  ";
					break;
				case 2:
					$cntFilter	 = " HAVING totalCompletedCount > 0  ";
					break;
				default:
					$cntFilter	 = " ";
					break;
			}
		}
		
		$sql = "SELECT 
				CASE WHEN
				stt.stt_zone = 1 THEN 'North' 
				WHEN stt.stt_zone = 2 THEN 'West' 
				WHEN stt.stt_zone = 3 THEN 'Central' 
				WHEN stt.stt_zone = 4 THEN 'South' 
				WHEN stt.stt_zone = 5 THEN 'East' 
				WHEN stt.stt_zone = 6 THEN 'North East' 
				WHEN stt.stt_zone = 7 THEN 'South' 
				ELSE '-'
				END AS Region,
				agt_fname,
				agt_lname,
				agt_company,
				agt_credit_limit, 	
				agt_create_date,
				SUM(IF($dateCond,1,0)) AS totalCompletedCount,
				SUM(IF($dateCond,biv.bkg_total_amount,0)) AS totalAmount,
				SUM(IF($dateCond,biv.bkg_gozo_amount,0)) AS totalGozoAmount,
				ROUND(
				( ( (SUM(
						IF($dateCond,
						   biv.bkg_gozo_amount,
						   0)))
				  / (SUM(
						IF($dateCond,
						   biv.bkg_total_amount,
						   0))))
				* 100),
				1)
				AS plpercent,
				COUNT(IF(bkg.bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 30 DAY), bkg_id, NULL) ) AS Count_30_Days,
				COUNT(IF(bkg.bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 90 DAY), bkg_id, NULL) ) AS Count_90_Days 
				FROM `agents` 
				INNER JOIN booking bkg ON bkg.bkg_agent_id = agt_id AND bkg.bkg_status IN(6,7) 
				INNER JOIN booking_invoice biv ON bkg.bkg_id = biv.biv_bkg_id
				INNER JOIN cities cty ON cty.cty_id = agents.agt_city
				LEFT JOIN states stt ON cty.cty_state_id = stt.stt_id
				WHERE agents.agt_active = 1 $cpFilter
				GROUP BY agents.agt_id 
				$cntFilter";

		if ($type == 'Command')
		{
			$sqlCount		 = "SELECT 
				agents.agt_id,
				SUM(IF($dateCond,1,0)) AS totalCompletedCount
				FROM `agents` 
				INNER JOIN booking bkg ON bkg.bkg_agent_id = agt_id AND bkg.bkg_status IN(6,7) 
				INNER JOIN booking_invoice biv ON bkg.bkg_id = biv.biv_bkg_id
				INNER JOIN cities cty ON cty.cty_id = agents.agt_city
				WHERE agents.agt_active = 1 $cpFilter
				GROUP BY agents.agt_id 
				$cntFilter";
			
//			echo "<br>Type: ".$type;
//			echo "<br>date1: ".$date1;
//			echo "<br>date2: ".$date2;
//			echo "<br>agtType: ".$agtType;
//			echo "<br>countFilter: ".$countFilter;
//			echo "<br><br>SqlCount: ".$sqlCount;
//			echo "<br><br>Sql: ".$sql;
//			die();
			
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB());
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['totalCompletedCount', 'Count_30_Days', 'Count_90_Days'],
					'defaultOrder'	 => 'totalCompletedCount DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$resultset = DBUtil::queryAll($sql, DBUtil::SDB());
			return $resultset;
		}
	}

//	public function updatePartnerWalletBalance($agentId)
//	{
//		try
//		{
//			$success	 = false;
//			$data		 = AccountTransactions::model()->getPartnerWalletBalance($agentId);
//			$agtModel	 = Agents::model()->findByPk($agentId);
//			if ($agtModel)
//			{
//				$agtModel->agt_payment_collect_flag			 = ($data['walletBalance'] < 0) ? 1 : 0;
//				$agtModel->agt_payment_outstanding_wallet	 = $data['walletBalance'];
//				$success									 = $agtModel->save();
//			}
//
//			return $success;
//		}
//		catch (Exception $ex)
//		{
//			return false;
//		}
//	}

	public static function getAvailableLimit($partnerId)
	{
		$model			 = Agents::model()->findByPk($partnerId);
		$getBalance		 = PartnerStats::getBalance($partnerId);
		$netBalance		 = $getBalance['pts_ledger_balance'];
		//$netBalance	 = AccountTransactions::checkPartnerBalance($partnerId);
		$availableLimit	 = $model->agt_effective_credit_limit - $netBalance;
		return $availableLimit;
	}

	public function getCollectionList()
	{
		$sql = "SELECT 
				agt_id,
				agt.agt_opening_deposit,
				agt.agt_grace_days,
				agt.agt_credit_limit creditLimit,
                agt.agt_effective_credit_limit,
				agt.agt_overdue_days,				
				SUM(adt.adt_amount) totTrans
                FROM agents agt				
				JOIN account_trans_details adt	ON agt.agt_id = adt.adt_trans_ref_id  AND adt.adt_active = 1  AND adt.adt_ledger_id = 15 
				JOIN account_transactions act	ON act.act_id = adt.adt_trans_id  AND act.act_active = 1
				WHERE 1	GROUP BY adt.adt_trans_ref_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function findByContactID($agtContactId)
	{
		$model = Agents::model()->find('agt_contact_id=:agt_contact_id AND agt_active >0', ['agt_contact_id' => $agtContactId]);
		return $model;
	}

	public static function CheckAgentApprovedTillDate($agtid)
	{
		$param	 = ['agtId' => $agtid];
		$sql	 = "SELECT IF(agt_approved_untill_date > NOW(),1,0) approved_untill_date FROM agents WHERE agt_id =:agtId";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $result;
	}

	public static function isApiKeyAvailable($agtId)
	{
		$param	 = ['agtId' => $agtId];
		$sql	 = "SELECT `agt_api_key` FROM `agents` WHERE `agt_id`=:agtId ";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		if ($result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function partnerMonthlyBalance($agentId, $params, $command = DBUtil::ReturnType_Provider)
	{
		$dataprovider	 = [];
		$fromDate		 = $params['from_date'];
		$toDate			 = $params['to_date'];
		$where			 = $where1			 = $where2			 = $where3			 = '';
		if ($agentId != '')
		{
			$where	 = " AND agt_id=$agentId";
			$where1	 = " AND bkg_agent_id=$agentId";
			$where2	 = " AND adt.adt_trans_ref_id=$agentId";
			$where3	 = " AND adt1.adt_trans_ref_id=$agentId";
		}
		$sql = "SELECT
				agt_id, agentName, pickupDate, FORMAT(BookingAmount,0) BookingAmount, FORMAT(ServedAmount,0) ServedAmount, FORMAT(AdvanceAmount,0) AdvanceAmount, 
				FORMAT(CancelCharges,0) CancelCharges, FORMAT(TotalBalance,0) TotalBalance,
				FORMAT(Wallet,0) Wallet, FORMAT(Bank,0) Bank, FORMAT(Compensation,0) Compensation, 
				FORMAT(OtherPayments,0) OtherPayments, FORMAT(Commission,0) Commission
				FROM (
					SELECT * FROM
					(
						SELECT DATE_FORMAT(dt, '%Y-%m') as ctDate, agt_id,
						IF(agt_company IS NOT NULL AND agt_company<>'', agt_company, CONCAT(agt_fname, ' ', agt_lname)) as agentName
						FROM calendar_table
						INNER JOIN agents
						WHERE (dt BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') $where
						GROUP BY ctDate, agt_id
					) ct

					LEFT JOIN
					(
						SELECT bkg_agent_id, DATE_FORMAT(bkg_pickup_date, '%Y-%m') as pickupDate,
						SUM(IF(bkg_status IN (2,3,5,6,7,9), biv.bkg_total_amount, 0)) BookingAmount,
						SUM(IF(bkg_status IN (2,3,5,6,7), biv.bkg_total_amount, 0)) ServedAmount,
						SUM(IF(bkg_status IN (2,3,5,6,7), biv.bkg_net_advance_amount, 0)) AdvanceAmount,
						SUM(IF(bkg_status=9, biv.bkg_net_advance_amount, 0)) CancelCharges
						FROM booking bkg
						INNER JOIN booking_invoice biv ON bkg.bkg_id=biv.biv_bkg_id AND bkg_status IN (2,3,5,6,7,9)
						WHERE 1 AND (bkg_pickup_date BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') $where1
						GROUP BY bkg_agent_id, pickupDate
					) as bkg ON pickupDate = ct.ctDate AND bkg_agent_id=agt_id

					LEFT JOIN (
						SELECT adt.adt_trans_ref_id pa_adt_trans_ref_id, DATE_FORMAT(act_date, '%Y-%m') as partnerDate,
						SUM(IF(abs(adt.adt_amount)<=abs(adt1.adt_amount), adt.adt_amount, adt1.adt_amount*-1)) as TotalBalance,
						SUM(IF(adt1.adt_ledger_id IN (49,26), IF(abs(adt.adt_amount)<=abs(adt1.adt_amount), adt.adt_amount, adt1.adt_amount*-1),0)) as Wallet,
						SUM(IF(adt1.adt_ledger_id IN (23,29,30), IF(abs(adt.adt_amount)<=abs(adt1.adt_amount), adt.adt_amount, adt1.adt_amount*-1),0)) as Bank,
						SUM(IF(adt1.adt_ledger_id IN (27,28), IF(abs(adt.adt_amount)<=abs(adt1.adt_amount), adt.adt_amount, adt1.adt_amount*-1),0)) as Compensation,
						SUM(IF(adt1.adt_ledger_id IN (1,16,17,18,19,20,21,32,46,53,58), IF(abs(adt.adt_amount)<=abs(adt1.adt_amount), adt.adt_amount, adt1.adt_amount*-1),0)) as OtherPayments
						FROM account_transactions act
						INNER JOIN account_trans_details adt ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 15 AND act.act_active=1 AND act.act_status = 1 AND adt.adt_active=1 AND adt.adt_status = 1
						INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id<>15 AND adt1.adt_active=1 AND adt1.adt_status = 1
						AND ((adt.adt_amount>0 AND adt1.adt_amount<0) OR (adt1.adt_amount>0 AND adt.adt_amount<0))
						WHERE 1 AND (act_date BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') $where2
						GROUP BY pa_adt_trans_ref_id, partnerDate
					) AS partnerAmount ON agt_id = partnerAmount.pa_adt_trans_ref_id AND bkg.pickupDate = partnerAmount.partnerDate

					LEFT JOIN (
						SELECT adt1.adt_trans_ref_id pc_adt_trans_ref_id, DATE_FORMAT(act_date, '%Y-%m') as pDate,
						SUM(IF(abs(adt.adt_amount)<=abs(adt1.adt_amount), adt.adt_amount, adt1.adt_amount*-1)) as Commission
						FROM account_transactions act
						INNER JOIN account_trans_details adt ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 35 AND act.act_active=1 AND act.act_status = 1 AND adt.adt_active=1 AND adt.adt_status = 1
						INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id IN (49,26,15) AND adt1.adt_active=1 AND adt1.adt_status = 1
						AND ((adt.adt_amount>0 AND adt1.adt_amount<0) OR (adt1.adt_amount>0 AND adt.adt_amount<0))
						WHERE 1 AND (act_date BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') $where3
						GROUP BY pc_adt_trans_ref_id, pDate
					) AS partnerComm On agt_id = partnerComm.pc_adt_trans_ref_id AND bkg.pickupDate = partnerComm.pDate

					WHERE bkg.pickupDate IS NOT NULL OR partnerAmount.partnerDate IS NOT NULL OR partnerComm.pDate IS NOT NULL
				) a";
		if ($command == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'pagination'	 => false,
				'sort'			 => ['attributes'	 => ['agt_id', 'agentName'],
					'defaultOrder'	 => 'pickupDate ASC'],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
	}

	public static function getByName($name)
	{
		$sql = "SELECT a.agt_id, a.agt_fname, a.agt_lname FROM agents as a
				WHERE 1 AND (a.agt_fname LIKE '%$name%' || a.agt_lname LIKE '%$name%')";
		#echo $sql;
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getById($id)
	{
		$sql = "SELECT a.agt_id, a.agt_fname, a.agt_lname,agt_company FROM agents as a
				WHERE 1 AND a.agt_id = $id AND a.agt_active = 1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * @param stdClass $jsonObj
	 * @param type $userId
	 * @return type
	 * @throws Exception
	 */
	public static function createQrAgent($jsonObj, $userId = '')
	{
		$agentId = '';
		$model	 = new Agents();
		$email	 = $jsonObj->qr_email;
		$phone	 = Filter::processPhoneNumber($jsonObj->qr_contact_number);
		$isValid = Filter::validatePhoneNumber($phone);
		if (!$isValid)
		{
			$model->addError($model->agt_phone, 'Invalid phone number.');
			return false;
		}

		$model->scenario	 = 'qrsignup';
		$chars				 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password			 = substr(str_shuffle($chars), 0, 4);
		$model->agt_password = md5($password);
		if ($email != "")
		{
			$model->agt_username = $email;
			$agentModel			 = Agents::model()->find('agt_email=:email ', ['email' => $email]);
			$agentId			 = $agentModel->agt_id;
		}
		if ($agentId == '' && $phone != '')
		{
			$model->agt_username = (trim($email) != '' ? trim($email) : $phone);
			$agentModel			 = Agents::model()->find('agt_phone=:phone ', ['phone' => $phone]);
			$agentId			 = $agentModel->agt_id;
		}
		if ($agentId > 0)
		{
			$ret = ContactProfile::updateAgentByUser($agentId, $userId);
			if (!$ret->getStatus())
			{
				throw new Exception($ret->getMessage());
			}
			goto end;
		}

		if (trim($email) != '')
		{
			$emailRecord = ContactEmail::getByEmail($email, '', '', '', 'limit 1');
		}

		if (trim($phone) != '')
		{
			$value		 = Filter::parsePhoneNumber($phone, $code, $number);
			$phoneRecord = ContactPhone::getByPhone($code . $number, '', '', '', 'limit 1');
		}

		$contactId = Contact::getIdByRecord($emailRecord, $phoneRecord);

		$name							 = explode(" ", $jsonObj->qr_contact_name);
		$model->agt_fname				 = $name[0];
		$model->agt_lname				 = $name[1];
		$model->agt_phone_country_code	 = $code;
		$model->agt_phone				 = $number;
		$model->agt_email				 = $email;
		$model->agt_approved			 = 1;
		$model->agt_commission_value	 = 2; //Yii::app()->params['agentDefCommissionValue'];
		$model->agt_commission			 = 100; //Yii::app()->params['agentDefCommission'];

		$result = CActiveForm::validate($model);
		if (!$result)
		{
			throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
		}

		$success = $model->save();
		if ($success)
		{
			/**
			 * add/update contact details
			 */
			// booking Type Outsation
			PartnerRuleCommission::saveData($model->agt_id);

			if ($contactId != "")
			{
				goto skipContactCreate;
			}
			$jsonObj									 = new stdClass();
			$jsonObj->profile->firstName				 = trim($model->agt_fname);
			$jsonObj->profile->lastName					 = trim($model->agt_lname);
			$jsonObj->profile->email					 = trim($model->agt_email);
			$jsonObj->profile->primaryContact->number	 = trim($model->agt_phone);
			$jsonObj->profile->primaryContact->code		 = trim($model->agt_phone_country_code);

			$returnSet				 = Contact::createContact($jsonObj, 0, UserInfo::TYPE_AGENT);
			$contactId				 = $returnSet->getData()['id'];
			skipContactCreate:
			$model->agt_contact_id	 = $contactId;
			$model->save();

			$model->agt_agent_id = "AGT00" . $model->agt_id;
			if ($userId == '')
			{
				$userModel						 = new Users();
				$userModel->usr_contact_id		 = $contactId;
				$userModel->usr_name			 = $model->agt_fname;
				$userModel->usr_lname			 = $model->agt_lname;
				$userModel->usr_email			 = $model->agt_email;
				$userModel->usr_mobile			 = $model->agt_phone;
				$userModel->usr_password		 = $model->agt_password;
				$userModel->usr_create_platform	 = 1;
				$userModel->usr_acct_type		 = 1;
				$userModel->scenario			 = 'agentQrjoin';
				if (!$userModel->save())
				{
					throw new Exception(CJSON::encode($userModel->getErrors()), ReturnSet::ERROR_INVALID_DATA);
				}

				$userId = $userModel->user_id;
			}
			#skipuser://
			$contactUser	 = ContactProfile::getCodeByCttId($contactId);
			$contactUserId	 = $contactUser['cr_is_consumer'];
			$userId			 = ($contactUserId == "" ? $userId : $contactUserId);
			if ($userId != "")
			{
				$agentUserModel					 = new AgentUsers();
				$agentUserModel->agu_agent_id	 = $model->agt_id;
				$agentUserModel->agu_user_id	 = $userId;
				$agentUserModel->agu_role		 = 1;
				$agentUserModel->save();
				if ($contactId)
				{
					//Updating contact profile table
					//ContactProfile::setProfile($contactId, UserInfo::TYPE_AGENT);
					ContactProfile::updateEntity($contactId, $model->agt_id, UserInfo::TYPE_AGENT);
				}
//				if ($contactUserId == "")
//				{
//					ContactProfile::setProfile($contactId, UserInfo::TYPE_CONSUMER);
//				}
				return $model->agt_id;
			}
		}

		end:
		return $agentId;
	}

	public static function getAllAgents($type = 0)
	{
		$where	 = "";
		$limit	 = "";
		if ($type == 1)
		{
			$where	 = " AND  agt_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 7 DAY),' 00:00:00') AND NOW() ";
			$limit	 = " LIMIT 0,1000";
		}
		$sql = "SELECT agt_id,agt_commission_value,agt_commission
                FROM agents 
                LEFT JOIN  partner_settings ON  partner_settings.pts_agt_id=agents.agt_id
                WHERE 1
                $where
                AND agents.agt_active =1
                AND (partner_settings.pts_additional_param IS NULL OR TRIM(partner_settings.pts_additional_param)  = '') $limit";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getAllPartnerRuleCommission($type = 0)
	{
		$where	 = "";
		$limit	 = "";
		if ($type == 1)
		{
			$where	 = " AND  agt_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 7 DAY),' 00:00:00') AND NOW() ";
			$limit	 = " LIMIT 0,1000";
		}
		$sql = "SELECT agt_id,agt_commission_value,agt_commission
                FROM agents 
                LEFT JOIN  partner_rule_commission ON  partner_rule_commission.prc_agent_id=agents.agt_id
                WHERE 1
                $where
                AND agents.agt_active =1
                AND partner_rule_commission.prc_id IS NULL $limit";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getAgentsFromQr()
	{

		$sql = "SELECT agt_id,agt_fname,agt_lname,IF(agt_company IS NOT NULL,agt_company,'Reseller') as Company, CONCAT(agt_fname,' ',IF(agt_lname IS NOT NULL,agt_lname,' '),' ','(',IF(agt_company IS NOT NULL,agt_company,'Reseller'),')') as text FROM agents
INNER JOIN qr_code ON qrc_agent_id  = agt_id
group by agt_id";

		$rows = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $row)
		{
			$datas[] = array("id" => $row['agt_id'], "text" => $row['text']);
		}
		$data = CJSON::encode($datas);
		return $data;
	}

	public function getAllDocsbyAgtId($agtid)
	{
		$param	 = ['agtId' => $agtid];
		$sql	 = "SELECT agt.agt_owner_photo,agt.agt_pan_card,agt.agt_aadhar,arl.arl_voter_id_path,agt.agt_company_add_proof,arl.arl_driver_license_path FROM agents agt
					LEFT JOIN  agent_rel arl  ON  arl.arl_agt_id=agt.agt_id
					WHERE agt.agt_id =:agtId";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $result;
	}

	public function getAgentById($agentArr = [])
	{
		$agentIDs = '';
		if (sizeof($agentArr) > 0)
		{
			$agentIDs .= ' agt_id IN (' . implode(',', $agentArr) . ')  ';
		}
		$activeStatus	 = 'bkg.bkg_status>=2 && bkg.bkg_status<=7';
		$sql			 = "SELECT 
							bkg.bkg_agent_id,
							count(bkg.bkg_id) totBookings,
							sum(if($activeStatus,1,0)) totActiveBookings,
							sum(if($activeStatus,biv.bkg_corporate_credit,0))  totCredit,
							sum(if($activeStatus,biv.bkg_agent_markup,0))  totCommission,
							cty.cty_name agt_city_name,
							agt.agt_type,
							agt.agt_lname,
							agt.agt_fname,
							agt.agt_company_type,
							agt.agt_owner_name,
							agt.agt_company,
							agt.agt_commission_value,
							agt.agt_commission,
							agt.agt_opening_deposit,
							agt.agt_phone,
							agt.agt_phone_country_code,
							agt.agt_phone_two,
							agt.agt_phone_three,
							agt.agt_email,
							agt.agt_email_two,
							agt.agt_fax,
							agt.agt_address,
							agt.agt_credit_limit,
							agt.agt_effective_credit_limit,
							agt.agt_bank,
							agt.agt_branch_name,
							agt.agt_ifsc_code,
							agt.agt_bank_account,
							agt.agt_active,
							agt.agt_create_date,
							agt.agt_agent_id,
							agt.agt_license_expiry_date,
							agt.agt_approved,
							CONCAT(admins.adm_fname,' ',admins.adm_lname) as approve_by_name,
							(CASE agt.agt_approved
                        WHEN 0 THEN 'Not Verified'
                 	WHEN 1 THEN 'Approved'
                 	WHEN 2 THEN 'Pending Approval'
                 	WHEN 3 THEN 'Rejected'
					WHEN 4 THEN 'Ready For Approval'
                END) as approve_status
						FROM agents agt
						LEFT JOIN booking bkg ON agt.agt_id = bkg.bkg_agent_id
						LEFT JOIN booking_invoice biv ON bkg.bkg_id=biv.biv_bkg_id
						LEFT JOIN cities cty ON cty.cty_id = agt.agt_city
						LEFT JOIN admins ON admins.adm_id=agt.agt_approved_by
						WHERE $agentIDs";
		return DBUtil::queryAll($sql);
	}

	/**
	 * check qr agent id by user id
	 * @param type $userId
	 * @return type
	 */
	public static function checkQrAgentByUser($userId)
	{
		$params	 = ['userId' => $userId];
		$sql	 = "SELECT cr.cr_is_partner FROM booking_user bui
				INNER JOIN contact_profile cr ON cr.cr_is_consumer = bui.bkg_user_id AND cr.cr_status = 1
				INNER JOIN qr_code ON qrc_agent_id = cr.cr_is_partner AND qrc_active = 1 AND qrc_agent_id IS NOT NULL
				where bui.bkg_user_id =:userId";
		$res	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $res;
	}

	/**
	 * 
	 * @param type $partnerid
	 * @param type $sort
	 * @return type
	 */
	public static function getCollectionReport($partnerid = 0, $sort = 'agt_company')
	{
		$where = '';
		if ($partnerid > 0)
		{
			$where = " AND agt_id = $partnerid";
		}

		$tempTable	 = "PartnerLedgerBalance_" . rand();
		$sqlCreate	 = " (INDEX my_index_name (adt_trans_ref_id))
						SELECT atd.adt_trans_ref_id adt_trans_ref_id,SUM(atd.adt_amount) ledgerBal 
						FROM account_trans_details atd
						INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 15
						WHERE act.act_active=1 AND atd.adt_active=1 AND act.act_status = 1 AND atd.adt_status=1
						GROUP BY atd.adt_trans_ref_id";

		DBUtil::createTempTable($tempTable, $sqlCreate, DBUtil::SDB());

		$tempTable1	 = "PartnerWalletBalance_" . rand();
		$sqlCreate1	 = " (INDEX my_index_name (adt_trans_ref_id))
						SELECT atd.adt_trans_ref_id adt_trans_ref_id,(SUM(atd.adt_amount) *-1) WalletBalance 
						FROM account_trans_details atd
						INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 49
						WHERE act.act_active=1 AND atd.adt_active=1 AND act.act_status = 1 AND atd.adt_status=1
						GROUP BY atd.adt_trans_ref_id";

		DBUtil::createTempTable($tempTable1, $sqlCreate1, DBUtil::SDB());

		$sql = "SELECT *,IF(r.WalletBalance IS NULL,0,r.WalletBalance) WalletBalance,
						 IF(IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)) > 0, IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)), 0) 'Receivable',
						 IF(IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)) < 0, IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)), 0) 'Payable'
			FROM agents agt
			INNER JOIN $tempTable a ON agt.agt_id = a.adt_trans_ref_id
			LEFT JOIN $tempTable1 r ON agt.agt_id = r.adt_trans_ref_id
			 WHERE ((a.ledgerBal IS NOT NULL AND a.ledgerBal <> 0) OR (r.WalletBalance IS NOT NULL AND r.WalletBalance <> 0))
			$where 
			GROUP BY agt.agt_id ORDER BY $sort DESC";

		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $partnerid
	 * @return type
	 */
	public static function getTotalCollectionReport($partnerid = 0)
	{
		$where = '';
		if ($partnerid > 0)
		{
			$where = " AND agt_id = $partnerid";
		}

		$tempTable	 = "PartnerLedgerBalance_" . rand();
		$sqlCreate	 = " (INDEX my_index_name (adt_trans_ref_id))
						SELECT atd.adt_trans_ref_id adt_trans_ref_id,SUM(atd.adt_amount) ledgerBal FROM account_trans_details atd
						INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 15
						WHERE act.act_active=1 AND atd.adt_active=1 AND act.act_status = 1 AND atd.adt_status=1
						GROUP BY atd.adt_trans_ref_id";
		DBUtil::createTempTable($tempTable, $sqlCreate, DBUtil::SDB());

		$tempTable1	 = "PartnerWalletBalance_" . rand();
		$sqlCreate1	 = " (INDEX my_index_name (adt_trans_ref_id))
						SELECT atd.adt_trans_ref_id adt_trans_ref_id,(SUM(atd.adt_amount) *-1) WalletBalance FROM account_trans_details atd
						INNER JOIN account_transactions act ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 49
						WHERE act.act_active=1 AND atd.adt_active=1 AND act.act_status = 1 AND atd.adt_status=1
						GROUP BY atd.adt_trans_ref_id";
		DBUtil::createTempTable($tempTable1, $sqlCreate1, DBUtil::SDB());

		$sql = "SELECT 
				SUM(IF(r.WalletBalance IS NULL,0,r.WalletBalance)) as totalWalletBal,
				SUM(IF(IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)) > 0, IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)), 0)) as totalReceived,
				SUM(IF(IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)) < 0, IF(a.ledgerBal IS NULL,0,a.ledgerBal)-(IF(r.WalletBalance IS NULL,0,r.WalletBalance)), 0)) as totalPayable
			FROM agents agt
			INNER JOIN $tempTable a ON agt.agt_id = a.adt_trans_ref_id
			LEFT JOIN $tempTable1 r ON agt.agt_id = r.adt_trans_ref_id
			 WHERE ((a.ledgerBal IS NOT NULL AND a.ledgerBal <> 0) OR (r.WalletBalance IS NOT NULL AND r.WalletBalance <> 0))
			$where 
			";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function getListActiveChannelPartner($model, $type = DBUtil::ReturnType_Provider)
	{
		$params = [];
		if ($model->bkg_pickup_date1 != null && $model->bkg_pickup_date2 != null)
		{
			$sqlPickupDate			 = " AND bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2";
			$params["pickupDate1"]	 = $model->bkg_pickup_date1;
			$params["pickupDate2"]	 = $model->bkg_pickup_date2;
		}
		$sql					 = "SELECT
				booking.bkg_agent_id,
				CONCAT(IF(agt_fname!='',agt_fname,''),' ',IF(agt_lname!='',agt_lname,'')) as Agent_Name,
				SUM(IF(booking.bkg_status IN (6,7),1,0)) AS Completed_Cnt,
				SUM(IF(booking.bkg_status IN (6,7),booking_invoice.bkg_gozo_amount,0)) AS Total_Gozo_Amount,
				SUM(IF(booking.bkg_status IN (9),1,0)) AS CancelledCnt,
				agt_company AS ChannelPartner,
				admins.gozen AS Gozo_Account_Manager,
				max(booking.bkg_create_date) AS Last_Booking_Received_On,
				TIMESTAMPDIFF(MONTH,MAX(booking.bkg_create_date),NOW()) AS Months_Since_Last_Booking
				FROM `booking`
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
				INNER JOIN agents ON agents.agt_id=booking.bkg_agent_id
				LEFT JOIN admins ON admins.adm_id=agents.agt_admin_id
				WHERE 1
				AND booking.bkg_status IN (6,7,9)
				AND bkg_agent_id IS NOT NULL 
				AND bkg_create_date BETWEEN :createDate1 AND :createDate2
				$sqlPickupDate GROUP BY booking.bkg_agent_id";
		$params["createDate1"]	 = $model->bkg_create_date1;
		$params["createDate2"]	 = $model->bkg_create_date2;
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp", DBUtil::SDB3(),$params);
			$dataprovider	 = new CSqlDataProvider($sql, array(
				"totalItemCount" => $count,
				"params"		 => $params,
				'db'			 => DBUtil ::SDB3(),
				"pagination"	 => array("pageSize" => 100),
				'sort'			 => array('attributes'	 => array('bkg_agent_id', 'Completed_Cnt', 'Total_Gozo_Amount', 'CancelledCnt', 'ChannelPartner'),
					'defaultOrder'	 => '')
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(),$params);
		}
	}

}
