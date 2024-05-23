<?php

/**
 * This is the model class for table "booking_log".
 *
 * The followings are the available columns in table 'booking_log':
 * @property integer $blg_id
 * @property integer $blg_ref_id 
 * @property integer $blg_booking_id
 * @property integer $blg_user_id
 * @property integer $blg_user_type
 * @property integer $blg_entity_id
 * @property integer $blg_entity_type
 * @property string $blg_desc
 * @property integer $blg_event_id
 * @property integer $blg_event_id2
 * @property integer $blg_vendor_assigned_id
 * @property integer $blg_driver_assigned_id
 * @property integer $blg_vehicle_assigned_id
 * @property integer $blg_booking_status
 * @property integer $blg_remark_type
 * @property integer $blg_mark_car
 * @property integer $blg_mark_driver
 * @property integer $blg_mark_customer
 * @property integer $blg_mark_vendor
 * @property string $blg_admin_id
 * @property integer $blg_vendor_id
 * @property integer $blg_admin_id
 * @property string $blg_created
 * @property integer $blg_active
 * @property integer $blg_reason_view
 * @property string $blg_reason_text
 * @property integer $blg_query_from
 * @property integer $blg_query_via
 * @property string $blg_additional_params
 * The followings are the available model relations:
 * @property Admins $blgAdmin
 * @property Vendors $blgVendor
 * @property Drivers $blgDriver
 * @property Users $blgUser
 * @property integer $blg_trip_id
 */
class BookingLog extends CActiveRecord
{

	const Consumers	 = 1;
	const Vendor		 = 2;
	const Driver		 = 3;
	const Admin		 = 4;
	const Agent		 = 5;
	const System		 = 10;
	const Corporate	 = 6;

	public $recipient_arr		 = ['1' => 'Consumer', '2' => 'Vendor', '3' => 'Driver', '4' => 'Admin', '5' => 'Agent'];
	public $markRemarkBad		 = ['1' => 'General', '2' => 'Car', '3' => 'Driver', '4' => 'Vendor', '5' => 'Customer'];
	public $reasonViewBooking	 = ['1' => 'Received Query', '2' => 'Investigating Issue', '3' => 'Other Reason'];
	public $receivedQueryFrom	 = ['1' => 'Customer', '2' => 'Vendor', '3' => 'Car', '4' => 'Driver', '5' => 'Agent/Partner', '6' => 'Internal'];
	public $receivedQueryVia	 = ['1' => 'Phone', '2' => 'Ticket#', '3' => 'Web Chat', '4' => 'Gozo Messaging', '5' => 'Internal'];
	public $feedbackAbout		 = ['1' => 'Consumer', '2' => 'Vendor', '3' => 'Vehicle', '4' => 'Driver'];
	public $blg_created1, $blg_created2, $executive, $adm_fname, $adm_lname, $total_assigned, $vendor_name, $blg_addl_desc, $reasondesc_investigation, $reasondesc_other, $titcket_no, $escalation_status, $bkg_pickup_date1, $bkg_pickup_date2, $from_date, $to_date, $groupBy, $disabled_by;

	const BOOKING_VIEWED_1MIN							 = 200;
	const BOOKING_VIEWED								 = 705;
	const LEAD_CREATED								 = 1;
	const LEAD_MODIFIED								 = 2;
	const BOOKING_CREATED								 = 3;
	const BOOKING_MODIFIED							 = 4;
	const BOOKING_VERIFIED							 = 5;
	const BOOKING_UNVERIFIED							 = 6;
	const QUOTE_CREATED								 = 130;
	const QUOTE_CONVERT_TO_UNVERIFIED					 = 131;
	const VENDOR_ASSIGNED								 = 7;
	const VENDOR_UNASSIGNED							 = 8;
	const VENDOR_ASSIGNMENT_FAILED					 = 160;
	const CAB_DRIVER_ASSIGNMENT_FAILED				 = 161;
	const BOOKING_DELETED								 = 9;
	const BOOKING_CANCELLED							 = 10;
	const CAB_DETAILS_UPDATED							 = 11;
	const CAB_DETAILS_DELETED							 = 12;
	const SMS_SENT									 = 13;
	const EMAIL_SENT									 = 14;
	const VENDOR_REMINDED_FOR_DRIVER_INFORMATION		 = 15;
	const BOOKING_MARKED_COMPLETED					 = 16;
	const BOOKING_MARKED_SETTLED						 = 17;
	const BOOKING_MARKED_COMPLETED_WITH_AMOUNT		 = 18;
	const REVIEW_LINK_SENT							 = 19;
	const DISCOUNT_CODE_SENT							 = 20;
	const BOOKING_DETAILS_COPIED						 = 21;
	const BOOKING_REVIEWED_BY_USER					 = 22;
	const BOOKING_REVIEWED_BY_VENDOR					 = 23;
	const BOOKING_REVIEWED_BY_CSR						 = 24;
	const LEAD_CONVERTED_TO_BOOKING					 = 25;
	const BOOKING_CONVERTED_TO_LEAD					 = 26;
	const RECIEPT_GENERATED							 = 27;
	const RECIEPT_SENT								 = 28;
	const REMARKS_ADDED								 = 29;
	const CSR_REPLIED_TO_CUSTOMER						 = 30;
	const CSR_REPLIED_TO_VENDOR						 = 31;
	const VENDOR_DENIED_BOOKING						 = 32;
	const CONFIRMATION_MAIL_SMS_SENT_MANUALLY			 = 33;
	const PAYMENT_LINK_SENT_MANUALLY					 = 34;
	const BOOKING_UPSELL_SET							 = 35;
	const BOOKING_UPSELL_UNSET						 = 36;
	const BOOKING_ESCALATION_SET						 = 37;
	const BOOKING_ESCALATION_UNSET					 = 38;
	const ON_TRIP_HOST								 = 39;
	const ON_TRIP_PAYMENT								 = 40;
	const ON_TRIP_GUIDE								 = 41;
	const EMAIL										 = 42;
	const SMS											 = 43;
	const DRIVER_ASSIGNED								 = 44;
	const DRIVER_UNASSIGNED							 = 45;
	const CAB_ASSIGNED								 = 46;
	const CAB_UNASSIGNED								 = 47;
	const BOOKING_NON_PROFITABLE_SET					 = 48;
	const BOOKING_NON_PROFITABLE_OVERRRIDE_SET		 = 49;
	const BOOKING_MANUAL_ASSIGNMENT					 = 50;
	const CAB_PARTITIONED								 = 51;
	const CANCEL_REASON_CHANGED						 = 269;
	const CNG_ALLOWED									 = 273;
	// const EMAIL_BOOKING_CREATED = 44;
	// const SMS_BOOKING_CREATED = 45;
	// const EMAIL_VENDOR_ASSIGNED = 46;
	// const SMS_VENDOR_ASSIGNED = 47;
	// const EMAIL_USER_CAB_DETAILS_UPDATED = 48;
	// const SMS_USER_CAB_DETAILS_UPDATED = 49;
	// const SMS_VENDOR_CAB_DETAILS_UPDATED = 50;
	// const SMS_DRIVER_CAB_DETAILS_UPDATED = 51;
	// const EMAIL_BOOKING_CONFIRMED = 52;
	const ACCOUNT_REMARKS								 = 53;
	const PAYMENT_INITIATED							 = 54;
	const PAYMENT_COMPLETED							 = 55;
	const PAYMENT_FAILED								 = 56;
	const REFUND_PROCESS_INITIATED					 = 57;
	const REFUND_PROCESS_COMPLETED					 = 58;
	const REFUND_PROCESS_PENDING						 = 59;
	const REFUND_PROCESS_FAILED						 = 60;
	const REMARK_BAD_CUSTOMER_ADDED					 = 61;
	const REMARK_BAD_CAB_DRIVER_ADDED					 = 62;
	const REFUND_GOOZOCOIN_INITIATED					 = 63;
	const REFUND_GOZOCOIN_COMPLETED					 = 64;
	// const SMS_SENT_FEEDBACK_DRIVER = 63;
	// const SMS_SENT_FEEDBACK_VENDOR = 64;
	const SET_ACCOUNTING_FLAG							 = 65;
	const UNSET_ACCOUNTING_FLAG						 = 66;
	const VENDOR_CONFIRMED_BOOKING					 = 68;
	const ACCOUNTS_DETAILS_MODIFIED					 = 69;
	const REMARK_BAD									 = 70;   // REMARK_BAD ( Vendor / Driver / Car / Customer
	const RATING_APPROVE								 = 71;
	const RATING_UNAPPROVE							 = 72;
	// const EMAIL_REMIND_RETURN_TRIP = 71;
	//const EMAIL_CUSTOMER_BEFORE_PICKUP = 72;
	// const SMS_CUTSOMER_BEFORE_PICKUP = 73;
	const RECONFIM_BOOKING							 = 74;
	const RESCHEDULE_BOOKING							 = 75;
	const TENTATIVE_BOOKING_FLAG						 = 76;
	const UNVERIFIED_FOLLOWUP_PRICE_HIGH				 = 77;
	const UNVERIFIED_FOLLOWUP_LOOKING					 = 78;
	const UNVERIFIED_FOLLOWUP_LOOKING_TENTATIVE		 = 79;
	const UNVERIFIED_FOLLOWUP_OTHER					 = 80;
	const UPDATE_PAYMENT_EXPIRY						 = 81;
	const AUTOCANCEL_BOOKING							 = 82;
	const AUTOCANCEL_UV_BOOKING						 = 83;
	const CAB_DRIVER_DETAILS_SENT						 = 84;
	// const UNVERIFIED_EMAIL = 83;
	// const UNVERIFIED_SMS = 84;
	const CSR_ASSIGN									 = 85;
	const FOLLOWUP_ASSIGN								 = 86;
	const FOLLOWUP_CHANGE								 = 87;
	const FOLLOWUP_COMPLETE							 = 88;
	const NO_SHOW										 = 89;
	const MESSAGE_STATUS								 = 90;
	const SMART_MATCH									 = 91;
	const RIDE_STATUS									 = 92;
	const ARRIVED_FOR_PICKUP							 = 93;
	const BID_SET										 = 94;
	const BID_DENY									 = 194;
	const AGENT_CREDIT_APPLIED						 = 95;
	const AGENT_COMMISSION_APPLIED					 = 96;
	const MMT_CAB_DRIVER_UPDATE						 = 97;
	const SET_ACCOUNTING_FLAG_AGENT					 = 98;
	const REMARKS_ADDED_AGENT							 = 101;
	const BOOKING_SETTLED_AGENT						 = 103;
	const ONTHEWAY_FOR_PICKUP							 = 104;
	const VENDOR_PANALIZED							 = 105;
	const VENDOR_PENALTY_REVERTED						 = 407;
	const VOUCHER_UPLOAD								 = 106;
	const VOUCHER_DELETED								 = 107;
	const LAZYPAY_ELIGIBILITY_CHECK					 = 110;
	const BONUS_DRIVER								 = 111;
	const REDEEM_BONUS_DRIVER							 = 112;
	const SUPPLY_MESSAGE								 = 113;
	const AUTO_ASSIGNMENT								 = 114;
	const ESCALATE_OM									 = 115;
	const CSR_ALLOCATE								 = 116;
	const SELF_ASSIGN									 = 117;
	const BLOCK_AUTOASSIGNMENT						 = 118;
	const UNBLOCK_AUTOASSIGNMENT						 = 119;
	const ENABLE_AUTOCANCEL							 = 120;
	const STOP_AUTOCANCEL								 = 121;
	const BOOKING_AUTOCANCEL							 = 122;
	const BOOKING_CRITICAL_ASSIGNMENT					 = 123;
	const DUTYSLIP_REQUIRED							 = 124;
	const DUTYSLIP_NOT_REQUIRED						 = 125;
	const VOUCHER_APPROVED							 = 126;
	const VOUCHER_REJECTED							 = 127;
	const SOS_TRIGGER_ON								 = 128;
	const SOS_TRIGGER_OFF								 = 129;
	const CAB_DRIVER_APPROVED							 = 133;
	const CAB_DRIVER_UNAPPROVED						 = 132;
	const PRICE_LOCK_EXPIRY							 = 134;
	const RELEASED_PAYMENT							 = 135;
	const LOCKED_PAYMENT								 = 136;
	const BOOKING_CASH_CONFIRMED						 = 137;
	const VENDOR_AUTO_ASSIGNMENT_START				 = 138;
	const DEMAND_SUPPLY_MISFIRE						 = 140;
	const UNVERIFIED_CONVERT_TO_QUOTE					 = 141;
	const BOOKING_CASH_CONFIRMED_RESET				 = 142;
	const Dynamic_Price								 = 150;
	const BOOKING_AMOUNT_MODIFICATION					 = 151;
	const PARTNER_WALLET_AUTO_CREDIT					 = 152;
	const BONUS_REFERRAL								 = 153;
	const STOP_MAX_ALLOWABLE_VENDOR_AMOUNT			 = 154;
	const CSR_REALLOCATE								 = 155;
	/* Driver App */
	const TRIP_PAUSED									 = 251;
	const TRIP_RESUME									 = 252;
	const TRIP_LATE									 = 253;
	const REF_RIDE_START								 = 215;
	const REF_RIDE_COMPLETE							 = 216;
	const NO_SHOW_RESET								 = 217;
	const VENDOR_AMOUNT_RESET							 = 102;
	const REF_MATCH_FOUND								 = 201;
	const REF_MATCH_BROKEN							 = 202;
	const REF_MATCH_PENDING							 = 203;
	const SNOOZE_ASSINGMENT							 = 204;
	const CUSTOMER_DENIED_BOOKING						 = 205;
	const PARTNER_COMMISSION_CHANGED					 = 206;
	const NEED_MORE_INVENTORY_FLAG_ON					 = 207;
	const NEED_MORE_INVENTORY_FLAG_OFF				 = 208;
	const AUTOMATED_FOLLOWUP_OPEN						 = 209;
	const SIGNATURE_UPLOAD							 = 210;
	const ALREADY_RIDE_COMPLETE						 = 222;
	/* added by praveen */
	const DRIVER_NOT_GOING							 = 260;
	const DRIVER_NOT_FINDING_LOCATION					 = 261;
	const DRIVER_APP_USAGE							 = 265;
	/* added by subhradip */
	const QUOTE_EXPIRED								 = 266;
	const REDEEMED_VENDOR_COIN						 = 267;
	const COMPENSATION_PROCESS_COMPLETED				 = 268;
	const COMPENSATION_REMOVE							 = 270;
	const COMPENSATION_ADDED							 = 271;
	const QR_SCAN										 = 272;
	/*
	  const REMARK_BAD_VENDOR = 70;
	  const REMARK_BAD_CAR = 71;
	  const REMARK_BAD_DRIVER = 72;
	  const REMARK_BAD_CUSTOMER = 73;
	 * 
	 */
	const BOOKING_LAST_ACTION_REVERTED				 = 99;
	const BOOKING_PROMO								 = 100;
	const REF_PROMO_APPLIED							 = 211;
	const REF_PROMO_REMOVED							 = 212;
	const REF_PROMO_GOZOCOINS_APPLIED					 = 213;
	const REF_PROMO_GOZOCOINS_REMOVED					 = 214;
	const REF_PROMO_USE								 = 218;
	const REF_PROMO_GOZOCOINS_USE						 = 219;
	const ADD_TO_CUSTOMER_WALLET						 = 220;
	const DEDUCTED_CUSTOMER_WALLET					 = 221;
	const PARTNER_API_ERROR							 = 400;
	const MATCH_FLEXXI_BOOKING						 = 401;
	const GIFT_CARD									 = 402;
	const REFUND_APPROVED								 = 404;
	const REFUND_DISAPPROVED							 = 405;
	const RESCHEDEULE_PICKUP_TIME						 = 409;
	const DRIVER_DETAILS_VIEWED						 = 410;
	const CUSTOMER_DETAILS_VIEWED						 = 411;
	const MANUAL_ASSIGNMENT_TRIGGERED					 = 412;
	const COVID_CHECK									 = 500;
	const DATA_DISCREPANCY							 = 501;
	const CAB_VERIFIED								 = 502;
	const START_ODOMETER								 = 503;
	const END_ODOMETER								 = 504;
	const BOOST_VERIFIED								 = 505;
	const FOLLOWUP_CREATE								 = 186;
	const PARTNER_API_SYNC_ERROR						 = 187;
	const CSR_UNASSIGN								 = 506;
	Const DRIVER_APP_NOT_USED							 = 507;
	Const SMARTMATCH_BREAK							 = 250;
//Gozo now 
	const ACTIVATE_GOZO_NOW							 = 549;
	const VENDOR_NOTIFIED_FOR_GOZONOW_BOOKING			 = 550;
	const BID_TIMER_START								 = 551;
	const BID_TIMER_RESTART							 = 552;
	const DISPLAYED_TO_CUSTOMER						 = 553;
	const VENDOR_OFFER_ACCEPTED						 = 554;
	const SEARCH_TIMEOUT								 = 555;
	const ASKMANUAL_ASSIGNMENT						 = 556;
	const GOZONOW_CUSTOMER_REJECTED_OFFER				 = 560;
	const CUSTOMER_NOTIFIED_FOR_MISSED_GOZONOW_OFFER	 = 561;
	const BOOKING_SCHEDULE_EVENT_PROCESS_FAILED		 = 562;
	const BOOKING_ONE_TIME_ADJUSTMENT					 = 563;
	const BOOKING_EVENT_STATS_FAILED					 = 562;
	const CODE_DCO_NEW_CHAT_NOTIFIED			=	570;
	const BOOKING_DIRECT_ACCEPT						 = 600;
	const BOOKING_BLOCKED_LOCATION					 = 601;
	const BOOKING_ADDRESS_SUCCESSFULLY				 = 602;
	const BOOKING_CAR_BREAKDOWN						 = 700;
	const INITIAL_INFO_CHANGED						 = 701;  //picktime,address,cabtype,contact info changed event
	const OPERATOR_API_ERROR							 = 702;
	const BOOKING_TRAVELLER_INFO_CHNAGED			= 703;
	const DRIVER_CUSTOM_EVENT_TRIGGERED_MANNUALY		= 704;
	const CAR_BREAKDOWN = 303;
		

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('blg_booking_id', 'required', 'on' => 'insert'),
			array('blg_ref_id', 'required', 'on' => 'update_ref'),
			array('blg_desc', 'required', 'on' => 'addremarks'),
			array('blg_desc', 'required', 'on' => 'addmarkremark,profitability'),
			array('blg_remark_type,blg_desc', 'required', 'on' => 'upsell,escalation'),
			array('blg_desc', 'required', 'on' => 'cngallowed'),
			array('blg_booking_id,blg_event_id, blg_vendor_assigned_id, blg_driver_assigned_id, blg_vehicle_assigned_id, blg_booking_status, blg_vendor_id, blg_admin_id', 'numerical', 'integerOnly' => true, 'min' => 0),
			array('blg_desc', 'length', 'max' => 2000),
			array('blg_created,blg_event_id,blg_event_id2,blg_user_type, blg_vendor_assigned_id, blg_driver_assigned_id, blg_vehicle_assigned_id, blg_booking_status,blg_remark_type,blg_mark_car,blg_mark_driver,blg_mark_customer,blg_mark_vendor,blg_ref_id,blg_trip_id,blg_entity_id,blg_entity_type,blg_reason_view,blg_reason_text,blg_query_from,blg_query_via,blg_additional_params', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('blg_id, blg_booking_id, blg_desc, blg_admin_id, blg_event_id2, blg_vendor_id,blg_event_id, blg_vendor_assigned_id, blg_driver_assigned_id, blg_vehicle_assigned_id, blg_booking_status, blg_created,blg_active,blg_user_type,blg_user_id,blg_ref_id,blg_addl_desc,blg_trip_id,blg_entity_id,blg_entity_type,blg_reason_view,blg_reason_text,blg_query_from,blg_query_via,blg_additional_params', 'safe', 'on' => 'search'),
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
			'blgAdmin'			 => array(self::BELONGS_TO, 'Admins', 'blg_user_id'),
			'blgVendor'			 => array(self::BELONGS_TO, 'Vendors', 'blg_vendor_id'),
			'blgUser'			 => array(self::BELONGS_TO, 'Users', 'blg_user_id'),
			'blgDriver'			 => array(self::BELONGS_TO, 'Drivers', 'blg_entity_id'),
			'blgBooking'		 => array(self::BELONGS_TO, 'Booking', 'blg_booking_id'),
			'blgAgent'			 => array(self::BELONGS_TO, 'Agents', 'blg_entity_id'),
			'blgEntityVendor'	 => array(self::BELONGS_TO, 'Vendors', 'blg_entity_id'),
				//'blgEntityDriver'	 => array(self::BELONGS_TO, 'Drivers', 'blg_entity_id'),
//				'blgAdmin'	 => array(self::BELONGS_TO, 'Admins', 'blg_user_id'),
//			'blgVendor'	 => array(self::BELONGS_TO, 'Vendors', 'blg_user_id'),
//			'blgUser'	 => array(self::BELONGS_TO, 'Users', 'blg_user_id'),
//			'blgDriver'	 => array(self::BELONGS_TO, 'Drivers', 'blg_user_id'),
//			'blgBooking' => array(self::BELONGS_TO, 'Booking', 'blg_booking_id'),
//			'blgAgent'	 => array(self::BELONGS_TO, 'Agents', 'blg_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'blg_id'					 => 'ID',
			'blg_booking_id'			 => 'Booking ID',
			'blg_desc'					 => 'Description',
			'blg_event_id'				 => 'Event ID',
			'blg_vendor_assigned_id'	 => 'Vendor Assigned',
			'blg_driver_assigned_id'	 => 'Driver Assigned',
			'blg_vehicle_assigned_id'	 => 'Vehicle Assigned',
			'blg_booking_status'		 => 'Booking Status',
			'blg_admin_id'				 => 'Admin ID',
			'blg_created'				 => 'Created Date',
			'blg_remark_type'			 => 'Remark Type',
			'blg_mark_car'				 => 'Mark car bad',
			'blg_mark_driver'			 => 'Mark driver bad',
			'blg_mark_customer'			 => 'Mark customer bad',
			'blg_mark_vendor'			 => 'Mark vendor bad',
			'blg_user_type'				 => 'User Type',
			'blg_addl_desc'				 => 'Press | for next',
			'blg_reason_text'			 => 'Reason for viewing booking',
			'blg_reason_view'			 => 'Reason for viewing booking',
			'blg_query_from'			 => 'Received query from',
			'blg_query_via'				 => 'Received query via',
			'blg_additional_params'		 => 'Additional Params'
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

		$criteria			 = new CDbCriteria;
		$criteria->compare('blg_id', $this->blg_id, true);
		$criteria->compare('blg_booking_id', $this->blg_booking_id);
		$criteria->compare('blg_desc', $this->blg_desc, true);
		$criteria->compare('blg_event_id', $this->blg_event_id);
		$criteria->compare('blg_vendor_assigned_id', $this->blg_vendor_assigned_id);
		$criteria->compare('blg_driver_assigned_id', $this->blg_driver_assigned_id);
		$criteria->compare('blg_vehicle_assigned_id', $this->blg_vehicle_assigned_id);
		$criteria->compare('blg_booking_status', $this->blg_booking_status);
		$criteria->compare('blg_admin_id', $this->blg_admin_id, true);
		$criteria->compare('blg_created', $this->blg_created, true);
		$criteria->together	 = true;
		return new CActiveDataProvider($this->together(), array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBookingId($bkgId, $platform = 0)
	{
		//$bkgId = '1865527,1865526';
		$criteria = new CDbCriteria;
		//$criteria->compare('blg_booking_id', $bkgId);
		//$criteria->compare('blg_booking_id IN ('.$bkgId.')');
		if ($this->blg_event_id != '')
		{
			$criteria->compare('blg_event_id', $this->blg_event_id);
		}
		if (Yii::app()->user->checkAccess('ConfidentialLog') == false)
		{
			$criteria->addCondition('blg_event_id NOT IN (150,102)');
		}

		$criteria->addCondition("blg_booking_id IN ($bkgId)");

		$csrId = UserInfo::getInstance()->getUserId();
		if (Yii::app()->controller->module->id == 'rcsr')
		{
			$criteria->compare('blg_admin_id', $csrId);
		}
		$criteria->compare('blg_user_type', $this->blg_user_type);
		$criteria->compare('blg_desc', $this->blg_desc, true);
		$criteria->compare('blg_active', 1);
		$criteria->with	 = ['blgAdmin', 'blgVendor', 'blgDriver', 'blgBooking', 'blgAgent'];
		$criteria->order = 'blg_id DESC, blg_created ASC, blg_event_id ASC';
		if ($platform > 0)
		{
			return $this->findAll($criteria);
		}
		else
		{
			$sort				 = new CSort;
			$sort->attributes	 = array(
				'blg_desc', 'blg_event_id', 'blg_remark_type', 'blg_created'
			);
			$dataProvider		 = new CActiveDataProvider($this->resetScope()->together(), array('criteria' => $criteria, 'sort' => $sort, 'pagination' => ['pageSize' => 30]));
			//$dataProvider = new CActiveDataProvider($this, array('criteria' => $criteria, 'sort' => $sort, 'pagination' => ['pageSize' => 30]));
			return $dataProvider;
		}
	}

	public function getByBookingIdEventId($bkgId, $eventIds)
	{
		$eventIds	 = implode(',', $eventIds);
		$sql		 = "SELECT * FROM `booking_log` WHERE 1=1";
		if ($bkgId != '')
		{
			$sql .= " AND `blg_booking_id`=" . $bkgId;
		}
		if ($eventIds != '')
		{
			$sql .= " AND `blg_event_id` IN (" . $eventIds . ")";
		}
		return DBUtil::queryAll($sql);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $eventIdList
	 * @return type
	 */
	public static function getEventLogDate($bkgId, $eventIdList)
	{
		$eventIds	 = implode(',', $eventIdList);
		$sql		 = "SELECT blg_created FROM `booking_log` WHERE 1=1";
		if ($bkgId != '')
		{
			$sql .= " AND `blg_booking_id`=" . $bkgId;
		}
		if ($eventIds != '')
		{
			$sql .= " AND `blg_event_id` IN (" . $eventIds . ")";
		}
		$sql .= " ORDER BY blg_id DESC ";
		return DBUtil::queryScalar($sql);
	}

	public function createLog1($bkgid, $desc, UserInfo $userInfo = null)
	{
		$bookingLog					 = new BookingLog();
		$bookingLog->blg_booking_id	 = $bkgid;
		$bookingLog->blg_desc		 = $desc;
		if ($userInfo->userType == 2)
		{
			$bookingLog->blg_vendor_id = $user_id;
		}
		elseif ($userInfo->userType == 4)
		{
			$bookingLog->blg_admin_id = $user_id;
		}
		$bookingLog->save();
	}

	public function eventList1()
	{
		
	}

	public function refEventList()
	{
		$eventlist = [
			201	 => 'Match Found',
			202	 => 'Match Broken',
			203	 => 'Match Pending',
			211	 => 'Promo Applied',
			212	 => 'Promo Removed',
			213	 => 'GozoCoins Applied',
			214	 => 'GozoCoins Removed',
			218	 => 'Promo Used',
			219	 => 'GozoCoins Used',
			215	 => 'Ride Started',
			216	 => 'Ride Completed',
			409	 => 'Reschedule Pickup Time',
		];
		asort($eventlist);
		return $eventlist;
	}

	/**
	 * This function is for making the mapping related to booking log table
	 * TODO: Need to sync with trip track event types if needed. 
	 * For reference: BookingTrack model
	 */
	public static function mapEvents()
	{
		$eventList = array
			(
			101	 => 215, //Start
			102	 => 251, //Pause
			103	 => 252, //Resume
			104	 => 216, //Stop
			201	 => 104, //Going For Pickup
			202	 => 202, //Not Going For Pickup
			203	 => 93, //Arrived
			204	 => 89, //No Show
			205	 => 253, //Wait
			206	 => 217, //No Show Reset
			301	 => 128, //SOS Start
			302	 => 129, //SOS Resolved
			503	 => 106, //Voucher Uploaded
			504	 => 107, //Voucher Deleted
			111	 => 511  //Trip Position
		);

		return $eventList;
	}

	public function eventList()
	{
		$eventlist = [
			1	 => 'Lead > Created',
			2	 => 'Lead > Modified',
			3	 => 'Booking > Created',
			4	 => 'Booking > Modified',
			5	 => 'Booking > Verified',
			6	 => 'Booking > Unverified',
			7	 => 'Assignment > Vendor Assigned',
			8	 => 'Assignment > Vendor Unassigned',
			9	 => 'Booking > Deleted',
			10	 => 'Booking > Cancelled',
			130	 => 'Booking > Quote created',
			131	 => 'Booking > Quote expired to Unverified',
			11	 => 'Assignment > Cab Details Updated',
			12	 => 'Assignment > Cab Details Deleted',
			13	 => 'Communication > SMS Sent',
			14	 => 'Communication > Email Sent',
			15	 => 'Assignment > Vendor Reminded for Driver Information',
			16	 => 'Booking > Marked Completed',
			17	 => 'Booking > Marked Settled',
			18	 => 'Booking > Marked Completed with Amount',
			19	 => 'Communication > Review Link Sent',
			20	 => 'Communication > Discount Code sent',
			21	 => 'Booking > Details copied',
			22	 => 'Ratings > Reviewed by User',
			23	 => 'Ratings > Reviewed by Vendor',
			24	 => 'Ratings > Reviewed by CSR',
			25	 => 'Lead > Converted to Booking',
			26	 => 'Booking > Converted to Lead',
			27	 => 'Booking > Receipt generated',
			28	 => 'Communication > Receipt sent',
			29	 => 'Action > Remarks added',
			30	 => 'Communication > CSR Replied to Customer',
			31	 => 'Communication > CSR Replied to Vendor',
			32	 => 'Assignment > Vendor denied booking',
			33	 => 'Communication > Confirmation mail-sms sent manually',
			34	 => 'Communication > Payment link sent manually',
			35	 => 'Booking > Upsell (Set)',
			36	 => 'Booking > Upsell (Reset)',
			37	 => 'Booking > Escalation (Set)',
			38	 => 'Booking > Escalation (Reset)',
			44	 => 'Assignment > Driver Assigned',
			45	 => 'Assignment > Driver Unassigned',
			46	 => 'Assignment > Cab Assigned',
			47	 => 'Assignment > Cab Unassigned',
			48	 => 'Booking > Not Profitable',
			49	 => 'Action > Profitability override',
			50	 => 'Assignment > Manual Assignment',
			51	 => 'Assignment > Partitioned cab assigned',
			53	 => 'Action > Accounts > Remarks',
			54	 => 'Payment > Initiated',
			55	 => 'Payment > Completed',
			56	 => 'Payment > Failed',
			57	 => 'Payment > Refund Process Initiated',
			58	 => 'Payment > Refund Process Completed',
			59	 => 'Payment > Refund Process Pending',
			60	 => 'Payment > Refund Process Failed',
			63	 => 'Payment > Refund Gozocoin Initiated',
			64	 => 'Payment > Refund Gozocoin Completed',
			65	 => 'Accounts > Flag (Set)',
			66	 => 'Accounts > Flag (Reset)',
			69	 => 'Accounts > Details modified',
			70	 => 'Ratings > Bad Marked',
			71	 => 'Ratings > Rating Approved',
			72	 => 'Ratings > Rating Rejected',
			73	 => 'Communication > SMS Customer before pickup',
			74	 => 'Booking > Reconfirmed',
			75	 => 'Booking > Rescheduled',
			76	 => 'Booking > Tentative (Set)',
			77	 => 'Automated Lead > Followup > Price high',
			78	 => 'Automated Lead > Followup > Just looking',
			79	 => 'Automated Lead > Followup > Tentative plans',
			80	 => 'Automated Lead > Followup > Other',
			81	 => 'Action > Payment expiry updated',
			82	 => 'Autocancel > Booking',
			83	 => 'Autocancel > Unverified Booking',
			84	 => 'Communication > Driver/Cab Details Sent',
			85	 => 'Assignment > CSR Assigned',
			86	 => 'Action > Followup Assign',
			87	 => 'Action > Followup Change',
			88	 => 'Action > Followup Complete',
			89	 => 'Booking Updates > No Show',
			90	 => 'Message Status',
			91	 => 'Action > Smart Match',
			92	 => 'Booking Updates > Ride Status Changed',
			93	 => 'Booking Updates > Driver arrived for pickup',
			94	 => 'Assignment > Bid Set',
			194	 => 'Assignment > Bid Deny',
			95	 => 'Accounts > Agent Credit Applied',
			96	 => 'Accounts > Agent Commission Applied',
			97	 => 'Booking Updates > MMT Cab Driver Update',
			98	 => 'Accounts > Flag (Set) By Agent',
			101	 => 'Action > Remarks added By Agent',
			102	 => 'Accounts > Vendor Amount (Reset)',
			103	 => 'Booking Updates > Marked Settled By Agent',
			100	 => 'Promo',
			104	 => 'Booking Updates  > Trip On the way',
			105	 => 'Penalty > Vendor Penalized',
			106	 => 'Booking Updates > Voucher Upload',
			107	 => 'Booking Updates > Voucher Deleted',
			110	 => 'Payment > LazyPay Eligibilty Check',
			111	 => 'Bonus > to driver',
			112	 => 'Bonus > redeemed by driver',
			113	 => 'Supply > message to 3rd party vendor(s)',
			114	 => 'Assignment > Manual assignment owner changed',
			115	 => 'Assignment > Delegated to Operation Manager',
			116	 => 'Assignment > Allocate CSR',
			117	 => 'Assignment > Self assigned by Operation Manager',
			118	 => 'Assignment > AutoAssignment Blocked',
			119	 => 'Assignment > AutoAssignment Enabled',
			120	 => 'Auto Cancel > Enabled',
			121	 => 'Auto Cancel > Blocked',
			122	 => 'Booking > Auto Cancel (Triggered)',
			123	 => 'Booking > Critical Assignment',
			124	 => 'Duty Slip Required',
			125	 => 'Duty Slip Not Required',
			126	 => 'Booking Updates > Voucher Approved',
			127	 => 'Booking Updates > Voucher Rejected',
			128	 => 'Panic/SOS > Set',
			129	 => 'Panic/SOS > Reset',
			133	 => 'Approval > Cab/Driver Approved',
			132	 => 'Approval > Cab/Driver Rejected',
			134	 => 'Communication > Price lock expiry email sent',
			200	 => 'Booking > Viewed (No Remarks)',
			135	 => 'Vendor Payment > released',
			136	 => 'Vendor Payment > Stop',
			137	 => 'Action > Confirm as Cash (Set)',
			138	 => 'Assignment > Auto Assignment started',
			140	 => 'Action > Demand Supply Misfire (Set)',
			141	 => 'Booking > Unverified Convert to Qoute',
			142	 => 'Action > Confirm as cash (Reset)',
			//150 => 'Pricing > Dynamic Price', 
			151	 => 'Booking > Amount Modification',
			152	 => 'Accounts > Partner wallet auto-credit',
			153	 => 'Bonus > to Referer',
			154	 => 'Assignment > VA auto-increase halted',
			155	 => 'Assignment > Reallocate CSR',
			205	 => 'Customer Denied Booking > as not his/her.',
			206	 => 'Accounts > Partner Commission Changed',
			207	 => 'Action > NMI > Set',
			208	 => 'Action > NMI > Unset',
			209	 => 'Action > Automated Lead > followup',
			210	 => 'Booking Updates > Customer Signature',
			/* Driver App */
			251	 => 'Booking Updates > Trip Paused',
			252	 => 'Booking Updates > Trip Resume',
			253	 => 'Booking Updates > Trip Late',
			215	 => 'Booking Updates > Trip Start',
			216	 => 'Booking Updates > Trip Completed',
			217	 => 'Booking Updates > No Show (Reset)',
			220	 => 'Payment > Added to customer wallet',
			221	 => 'Payment > Deducted from customer wallet',
			204	 => 'Assignment > Snooze CSR Allocation',
			265	 => 'Booking Updates > Driver App Usage',
			268	 => 'Compensation Process Completed',
			270	 => 'Remove Compensation',
			269	 => 'Cancel Reason Changed',
			404	 => 'Payment > Refund Approved',
			405	 => 'Payment > Refund Disapproved',
			407	 => 'Penalty > Vendor Penalty reverted',
			410	 => 'Action > Driver details viewed',
			411	 => 'Action > Customer details viewed',
			412	 => 'Assignment > Manual Assignment triggered',
			500	 => 'Action > Safety for COVID19',
			501	 => 'Booking Updates > Data Discrepancy',
			502	 => 'Booking Updates > Cab Verification',
			203	 => 'Booking Updates > Driver Arrived',
			201	 => 'Booking Updates > Going For Pickup',
			204	 => 'Booking Updates > No Show (Set)',
			205	 => 'Booking Updates > Wait',
			202	 => 'Booking Updates > Not Going For Pickup',
			503	 => 'Booking Updates > Start odometer reading',
			504	 => 'Booking Updates > End odometer reading',
			266	 => 'Booking > Quote expired manually',
			186	 => "Action > Followup Created",
			187	 => "Error > Partner API Sync",
			549	 => "Gozo NOW > Activated",
			550	 => "Gozo NOW > Vendor notified",
			551	 => "Gozo NOW > Bid timer start",
			552	 => "Gozo NOW > Bid timer reset",
			553	 => "Displayed to customer",
			554	 => "Vendor offer accepted",
			555	 => "Search timed out",
			556	 => "Assignment > Manual Assignment Ask",
			560	 => "Gozo NOW > Customer rejected offer",
			561	 => "Gozo NOW > Customer notified of missed offer",
			563	 => "Accounts > Onetime Price Adjustment",
			570	 => "DCO > Headsup Notified > New Chat Message",
			600	 => "Assignment > Direct Accept",
			601	 => "Booking > Blocked Location",
			602	 => "Booking > Address update successfully",
			271	 => "Compensation added",
			700	 => 'Action >Car breakdown',
			272	 => 'QR Scan Booking',
			160	 => 'Booking > Vendor Assignment Failed',
			161	 => 'Booking > Cab Driver Assignment Failed',
			511	 => 'Booking Updates > Trip Position',
			701	 => 'Initial Info Changed',
			273	 => 'Booking > Cng Allowed (Set)',
			703  => 'Booking > Traveller info updated',
			704  => "Driver Custom Event triggered manually",
			303 => "Booking Updates > Car Breakdown "
		];
		asort($eventlist);
		return $eventlist;
	}

	public function confidentialList()
	{
		$eventlist = [
			150	 => 'Pricing > Dynamic Price',
			102	 => 'Accounts > Vendor Amount (Reset)',
		];
		return $eventlist;
	}

	public function getConfidentialList($eventId)
	{
		$list = $this->confidentialList();
		return $list[$eventId];
	}

	public function driverEventList($evntType)
	{
		switch ($evntType)
		{
			case 203:
				$evntType	 = " Driver arrived for pickup ";
				break;
			case 204:
				$evntType	 = " No Show ";
				break;
			case 206:
				$evntType	 = " No Show Reset Value Against Booking ";
				break;
			case 101:
				$evntType	 = " Trip Started ";
				break;
			case 104:
				$evntType	 = " Trip Completed ";
				break;
			case 102:
				$evntType	 = " Trip Paused ";
				break;
			case 103:
				$evntType	 = " Trip Resume ";
				break;
			case 205:
				$evntType	 = " Trip Late ";
				break;
			case 201:
				$evntType	 = " Going For Pickup ";
				break;
			case 301:
				$evntType	 = " SOS ON ";
				break;
			case 302:
				$evntType	 = " SOS OFF ";
				break;
			case 107:
				$evntType	 = " Trip Selfie ";
				break;
			case 108:
				$evntType	 = " Trip Sanitizer Kit ";
				break;
			case 109:
				$evntType	 = " Trip Arrogya Setu ";
				break;
			case 110:
				$evntType	 = " Trip Terms Agree ";
				break;
			case 503:
				$evntType	 = " Trip Voucher Upload";
				break;
		}
		$returnData['event_type'] = $evntType;
		return $returnData;
	}

	public function logList()
	{
		$logList = [
			1	 => 'Consumers',
			2	 => 'Vendor',
			3	 => 'Driver',
			4	 => 'Admin',
			5	 => 'Agent',
			6	 => 'Corporate',
			10	 => 'System'
		];
		asort($logList);
		return $logList;
	}

	public function getEventByEventId($eventId)
	{
		$list = $this->eventList();
		return $list[$eventId];
	}

	public function getRefEventByRefEventId($eventId)
	{
		$list = $this->refEventList();
		return $list[$eventId];
	}

	public function getEventByUserType($userType)
	{
		$list = $this->eventList();
		return $list[$userType];
	}

	public function remarkListType()
	{
		$remarkList = [
			1	 => 'General',
			2	 => 'Car',
			3	 => 'Driver',
			4	 => 'Vendor',
			5	 => 'Customer'];
		return $remarkList;
	}

	public function getRemarksByListType($remarkId)
	{
		$list = $this->remarkListType();
		return $list[$remarkId];
	}

	public function checkDuplicateRemark($bookingId, $desc, $adminId)
	{

		$sql			 = "Select COUNT(*) FROM `booking_log` WHERE `blg_booking_id`=$bookingId AND blg_active=1 AND blg_event_id=" . BookingLog::REMARKS_ADDED . "  AND `blg_desc` LIKE :desc AND `blg_admin_id`=$adminId";
		/* @var $cdb CDbCommand */
		$sqlCommand		 = DBUtil::command($sql);
		$desc			 = trim($desc);
		$params[':desc'] = "%$desc%";
		$count			 = $sqlCommand->queryScalar($params);
		return ($count > 0);
	}

	public function updateDuplicateRemark($bookingId, $desc, $adminId)
	{

		$sql			 = "UPDATE `booking_log` SET `blg_active`=0 WHERE `blg_booking_id`=$bookingId  AND `blg_desc` LIKE :desc AND `blg_admin_id`=$adminId";
		/* @var $cdb CDbCommand */
		$sqlCommand		 = DBUtil::command($sql);
		$desc			 = trim($desc);
		$params[':desc'] = "%$desc%";
		$res			 = $sqlCommand->execute($params);
		return $res;
	}

	public static function getUserID($user_type)
	{
		if ($user_type == UserInfo::TYPE_SYSTEM)
		{
			$user_id = '0';
		}
		else
		{
			$user_id = UserInfo::getInstance()->getUserId();
		}
	}

	public function createLog($bkgid, $desc, UserInfo $userInfo = null, $eventid, $oldModel = false, $params = false, $eventId2 = '', $tripId = "")
	{
		$success = false;
		$errors	 = '';
		if ($userInfo == null)
		{
			$userInfo = UserInfo::model();
		}

		$bookingLog					 = new BookingLog();
		$bookingLog->blg_booking_id	 = $bkgid;

		$bookingLog->blg_desc = $desc;
		if ($tripId != ""):
			$bookingLog->blg_trip_id = $tripId;
		endif;

		if ($userInfo->userType != ""):
			$bookingLog->blg_user_type = $userInfo->userType;
		endif;

		if ($userInfo->userId != ""):
			$bookingLog->blg_user_id = $userInfo->userId;
		endif;

		$isDCOApp = null;
		if ($userInfo->getEntityId() != null)
		{
			$isDCOApp					 = ($userInfo->getUser()->getStateKeyPrefix() == '_dco');
			$bookingLog->blg_entity_id	 = $userInfo->getEntityId();
			//$bookingLog->blg_entity_type = 2;
			$userType					 = ($userInfo->getUser()->getStateKeyPrefix() == '_vendor') ? 2 : 3;
			$userType					 = ($userInfo->getUser()->getStateKeyPrefix() == '_dco') ? 2 : $userType;
			$userType					 = ($userInfo->getUser()->getStateKeyPrefix() == '_dco' && UserInfo::getUserType() == UserInfo::TYPE_DRIVER) ? UserInfo::TYPE_DRIVER : $userType;


			if($userInfo->getUser()->getStateKeyPrefix() == '_consumer')
			{
				$userType = UserInfo::TYPE_CONSUMER;
			}
			$bookingLog->blg_entity_type = $userType;
		}
		if ($userInfo->userType == 2)
		{
			$bookingLog->blg_vendor_id = $userInfo->getEntityId();
			if ($isDCOApp && isset($params['blg_driver_id']) && $params['blg_driver_id'] > 0)
			{
				$bookingLog->blg_driver_assigned_id	 = $params['blg_driver_id'];
				$bookingLog->blg_user_type			 = 3;
			}
		}
		if ($userInfo->userType == 3)
		{
			$bookingLog->blg_driver_assigned_id = $userInfo->getEntityId();
		}
		if ($userInfo->userType == 4)
		{
			$bookingLog->blg_admin_id = $userInfo->userId;
		}

		$bookingLog->blg_event_id = $eventid;
		if ($eventId2 != '')
		{
			$bookingLog->blg_event_id2 = $eventId2;
		}
		if ($oldModel)
		{
			$bookingLog->blg_booking_status		 = $oldModel->bkg_status;
			$bookingLog->blg_vendor_assigned_id	 = $oldModel->bkgBcb->bcb_vendor_id;
			$bookingLog->blg_driver_assigned_id	 = $oldModel->bkgBcb->bcb_driver_id;
			$bookingLog->blg_vehicle_assigned_id = $oldModel->bkgBcb->bcb_cab_id;
		}
		if (isset($params['blg_booking_status'])):
			$bookingLog->blg_booking_status = $params['blg_booking_status'];
		endif;
		if (isset($params['blg_remark_type'])):
			$bookingLog->blg_remark_type = $params['blg_remark_type'];
		endif;
		if (isset($params['blg_mark_car'])):
			$bookingLog->blg_mark_car = $params['blg_mark_car'];
		//Vehicles::model()->updateVehicleMarkCount($params['blg_mark_car']);
		endif;
		if (isset($params['blg_mark_driver'])):
			$bookingLog->blg_mark_driver = $params['blg_mark_driver'];
		//Drivers::model()->updateDriverMarkCount($params['blg_mark_driver']);
		endif;
		if (isset($params['blg_mark_vendor'])):
			$bookingLog->blg_mark_vendor = $params['blg_mark_vendor'];
		//Drivers::model()->updateDriverMarkCount($params['blg_mark_driver']);
		endif;
		if (isset($params['blg_mark_customer'])):
			$bookingLog->blg_mark_customer = $params['blg_mark_customer'];
		//Users::model()->updateUserMarkCount($params['blg_mark_customer']);
		endif;
		if (isset($params['blg_created'])):
			$bookingLog->blg_created = $params['blg_created'];
		endif;
		if (isset($params['blg_ref_id'])):
			$bookingLog->blg_ref_id = $params['blg_ref_id'];
		endif;
		if (isset($params['blg_vendor_assigned_id'])):
			$bookingLog->blg_vendor_assigned_id = $params['blg_vendor_assigned_id'];
		endif;
		if (isset($params['blg_vendor_id'])):
			$bookingLog->blg_vendor_id = $params['blg_vendor_id'];
		endif;
		if (isset($params['blg_driver_id'])):
			$bookingLog->blg_driver_assigned_id = $params['blg_driver_id'];

		endif;
		if (isset($params['blg_vehicle_id'])):
			$bookingLog->blg_vehicle_assigned_id = $params['blg_vehicle_id'];
		endif;
		if (isset($params['blg_entity_type'])):
			$bookingLog->blg_entity_type = $params['blg_entity_type'];

		endif;

		if (isset($params['additionlalParams']))
		{
			$additionlalParams					 = CJSON::encode($params['additionlalParams']);
			$bookingLog->blg_additional_params	 = $additionlalParams;
		}


//                if ($params['blg_reason_view'] != ''):
//			$bookingLog->blg_reason_view = $params['blg_reason_view'];
//		endif;
//                if ($params['blg_query_from'] != ''):
//			$bookingLog->blg_query_from = $params['blg_query_from'];
//		endif;
//                if ($params['blg_query_via'] != ''):
//			$bookingLog->blg_query_via = $params['blg_query_via'];
//		endif;
//		if ($params['blg_remark_type'] != ''):
//			$bookingLog->blg_remark_type = $params['blg_remark_type'];
//		endif;
//                if ($params['blg_is_followup'] != ''):
//			$bookingLog->blg_is_followup = $params['blg_is_followup'];
//		endif;
//                if ($params['blg_followup_date'] != ''):
//			$bookingLog->blg_followup_date = $params['blg_followup_date'];
//		endif;
//                if ($params['blg_followup_time'] != ''):
//			$bookingLog->blg_followup_time = $params['blg_followup_time'];
//		endif;
//                if ($params['blg_reason_text'] != ''):
//			$bookingLog->blg_reason_text = $params['blg_reason_text'];
//		endif;

		if (isset($params['blg_active'])):
			$bookingLog->blg_active = $params['blg_active'];
		endif;
		if (($bookingLog->blg_event_id == 37 || $bookingLog->blg_event_id == 136 ) && ($userInfo->userType == 3))
		{
			$bookingLog->blg_entity_id	 = NULL;
			$bookingLog->blg_entity_type = NULL;
			$bookingLog->blg_user_type	 = NULL;
		}
		if ($bookingLog->validate())
		{

			if ($bookingLog->save())
			{
				$success = true;
				if ($bkgid > 0 && $userInfo->userType == 4 && $eventid > 0)
				{
					BookingLogCsr::updateCsrDetails($bkgid, $userInfo->userId, $eventid);
				}
			}
			else
			{
				$getErrors = json_encode($bookingLog->getErrors());
			}
		}
		else
		{
			$getErrors = json_encode($bookingLog->getErrors());
		}
		return $success;
		/*
		  try
		  {
		  $transaction = DBUtil::beginTransaction();
		  if ($bookingLog->validate())
		  {

		  if ($bookingLog->save())
		  {
		  $success = DBUtil::commitTransaction($transaction);
		  }
		  else
		  {
		  $getErrors = json_encode($bookingLog->getErrors());
		  }
		  }
		  else
		  {
		  $getErrors = json_encode($bookingLog->getErrors());
		  }
		  }
		  catch (Exception $ex)
		  {
		  $errors = $getErrors;
		  DBUtil::rollbackTransaction($transaction);

		  }
		  return ['success' => $success, 'errors' => $errors];
		 * 
		 */
	}

	public function getUserdataByType($userType)
	{
		//$doneBy = "System";
		//$response = Contact::referenceUserData($this->blgBooking->bkgUserInfo->bui_id, 1);
		$response = self::getUserContact($this->blgBooking->bkgUserInfo->bui_id);
		if ($response->getStatus())
		{
			$firstName	 = $response->getData()['firstName']; //$response->getData()->email['firstName'];
			$lastName	 = $response->getData()['lastName']; //$response->getData()->email['lastName'];
		}

		switch ($userType)
		{
			case 1:
				$user		 = $firstName . " " . $lastName;
				$userType	 = " Consumer ";
				break;
			case 2:
				$user		 = ($this->blgVendor->vnd_name != '') ? $this->blgVendor->vnd_name : $this->blgEntityVendor->vnd_name;
//				if($user=='' && $this->blg_entity_type==2){
//					$user = $this->blgEntityVendor->vnd_name;
//				}
				$userType	 = " Vendor ";
				break;
			case 3:
				$user		 = $this->blgDriver->drv_name;
				$userType	 = " Driver ";
				break;
			case 4:
				$user		 = $this->blgAdmin->adm_fname . ' ' . $this->blgAdmin->adm_lname;
				$userType	 = " Admin ";
				break;
			case 5:
//                $user = $this->blgUser->usr_name . " " . $this->blgUser->usr_lname;
//                $userType = " Travel Agent ";
//                if($user==''){
//                  $user = $this->blgAgent->agt_fname . " " . $this->blgAgent->agt_lname;  
//                  if($this->blgAgent->agt_type==1){
//                      $userType = " Corporate "; 
//                  }
//                  if($this->blgAgent->agt_type==2){
//                      $userType = " Reseller Agent "; 
//                  }
//                }
				$relAgent	 = $this->blgBooking->bkgAgent;
				$user		 = $relAgent->agt_company . " (" . $relAgent->agt_fname . " " . $relAgent->agt_lname . ")";
				$userType	 = " Agent ";
				if ($relAgent->agt_type == 1)
				{
					$userType = " Corporate ";
				}
				if ($relAgent->agt_type == 0)
				{
					$userType = " Travel Agent ";
				}
				if ($relAgent->agt_type == 2)
				{
					$userType = " Reseller Agent ";
				}

				break;
			case 10:
				$user		 = "System";
				$userType	 = " System ";
				break;
			default:
				$user		 = "System";
				$userType	 = " System ";
				break;
		}
		$returnData['user']		 = $user;
		$returnData['user_type'] = $userType;
		return $returnData;
	}

	//	public function getTransactionLogWithEmptyRef()
//	{
//		$criteria	 = new CDbCriteria();
//		$criteria->addCondition('blg_event_id >= 54 AND blg_event_id <=60 AND blg_ref_id IS NULL');
//		$model		 = $this->findAll($criteria);
//		return $model;
//	}

	/**
	 * 
	 * @param type $buiId
	 * @return \ReturnSet
	 */
	public static function getUserContact($buiId)
	{
		$returnset		 = new ReturnSet();
		$model			 = BookingUser::model()->findByPk($buiId);
		$cttId			 = ContactProfile::getByEntityId($model->bkg_user_id, 1);
		$contactModel	 = Contact::model()->findByPk($cttId);
		$firstName		 = $contactModel->ctt_first_name;
		$lastName		 = $contactModel->ctt_last_name;

		$data = ['firstName' => $firstName, 'lastName' => $lastName];
		$returnset->setData($data);
		$returnset->setStatus(true);
		return $returnset;
	}

	public function getTransactionLogWithEmptyRef()
	{
		$sql		 = "SELECT blg_id,blg_desc FROM `booking_log` WHERE blg_event_id >= 54 AND blg_event_id <=60 AND blg_ref_id IS NULL";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function updateRefidbyid($blgid, $tid)
	{
		$model				 = $this->findByPk($blgid);
		$model->blg_ref_id	 = $tid;
		$model->scenario	 = 'update_ref';
		$res				 = $model->save();
		return $res;
	}

	public function markedbadByBookingLog($usrId = 0, $drvId = 0, $vhcId = 0)
	{
		$Val = '"';
		$sql = "SELECT
				a.`blg_remark_type`
				, a.`blg_mark_car`
				, a.`blg_mark_driver`
				, a.`blg_mark_customer`
				, a.`blg_event_id`
				, a.`blg_desc`
				, a.`blg_driver_assigned_id`
				, a.`blg_vehicle_assigned_id`
				, b.`bkg_booking_id`
				, bui.`bkg_user_fname`
				, bui.`bkg_user_lname`
				, b.`bkg_from_city_id`
				, b.`bkg_to_city_id`
				, b.`bkg_pickup_date`
				, REPLACE(JSON_EXTRACT(`bkg_route_city_names`, '$[0]'), '$Val', '') AS from_city_name
				, REPLACE(JSON_EXTRACT(`bkg_route_city_names`, CONCAT('$[', JSON_LENGTH(`bkg_route_city_names`) - 1, ']')), '$Val', '') AS to_city_name
				FROM   `booking_log` a
				JOIN `booking` b ON b.bkg_id = a.blg_booking_id
				JOIN booking_user AS bui ON bui.bui_bkg_id = b.bkg_id
				LEFT JOIN users e ON e.user_id = bui.bkg_user_id
				WHERE  1 ";
		if ($usrId > 0)
		{
			$sql .= " AND (e.user_id=" . $usrId . " AND e.usr_mark_customer_count>0 AND a.blg_mark_customer=1)";
		}
		if ($drvId > 0)
		{
			$sql .= " AND (a.blg_mark_driver=1 AND a.blg_driver_assigned_id=" . $drvId . ") ";
		}
		if ($vhcId > 0)
		{
			$sql .= " AND (a.blg_mark_car=1 AND a.blg_vehicle_assigned_id=" . $vhcId . ")";
		}
		return DBUtil::queryAll($sql);
	}

	public function getDailyAssignedCount($type = DBUtil::ReturnType_Provider)
	{
		$where = " AND  blg_created BETWEEN '" . $this->blg_created1 . " 00:00:00' AND '" . $this->blg_created2 . " 23:59:59' AND blg_event_id=7 AND blg_user_type=4 ";
		if ($this->adm_fname != null)
		{
			$where .= " AND adm_fname like '%$this->adm_fname%' ";
		}
		if ($this->adm_lname != null)
		{
			$where .= " AND adm_lname like '%$this->adm_lname%' ";
		}
		$sql		 = " SELECT * FROM ( SELECT COUNT(*) AS total_assigned, adm_fname ,adm_lname FROM booking_log LEFT JOIN admins ON adm_id = booking_log.blg_user_id WHERE 1 $where  GROUP BY blg_user_id) a ";
		$sqlCount	 = "SELECT COUNT(*) AS total_assigned FROM booking_log	LEFT JOIN admins ON adm_id = booking_log.blg_user_id WHERE 1 $where GROUP BY blg_user_id";
		$count		 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();

		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => array('attributes' =>
					array(
						'adm_fname'		 => ['asc' => 'adm_fname asc', 'desc' => 'adm_fname desc'],
						'adm_lname'		 => ['asc' => 'adm_lname asc', 'desc' => 'adm_lname desc'],
						'total_assigned' => ['asc' => 'total_assigned asc', 'desc' => 'total_assigned desc'],
					)
				),
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
	}

	public function getMarkedBadByBooking($bookingId, $markedBad = 'customer')
	{
		switch ($markedBad)
		{
			case 'customer':
				$sql = "SELECT COUNT(1) as Count FROM `booking_log` WHERE `blg_booking_id`='" . $bookingId . "' AND `blg_mark_customer`='1'";
				break;
			case 'driver':
				$sql = "SELECT COUNT(1) as Count FROM `booking_log` WHERE `blg_booking_id`='" . $bookingId . "' AND `blg_mark_driver`='1'";
				break;
			case 'car':
				$sql = "SELECT COUNT(1) as Count FROM `booking_log` WHERE `blg_booking_id`='" . $bookingId . "' AND `blg_mark_car`='1'";
				break;
			case 'vendor':
				$sql = "SELECT COUNT(1) as Count FROM `booking_log` WHERE `blg_booking_id`='" . $bookingId . "' AND `blg_mark_vendor`='1'";
				break;
		}
		$data = DBUtil::queryRow($sql);
		if ($data['Count'] > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function getCommentTraceByDriverId($drvId, $eventId, $bkgId = null)
	{
		if ((trim($drvId) == "" || trim($drvId) == null) || (trim($eventId) == "" || trim($eventId) == null))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$param		 = ["drvId" => trim($drvId), "eventId" => trim($eventId)];
		$bkgIdQry	 = '';
		if ($bkgId > 0)
		{
			$param["bkgId"]	 = trim($bkgId);
			$bkgIdQry		 = " AND bkg.bkg_id =:bkgId";
		}
		$sql = "SELECT blg.blg_desc, blg.blg_created, bkg.bkg_id,
						(CASE 
						WHEN (blg.blg_user_type='1') THEN 'Consumers'
						WHEN (blg.blg_user_type='2') THEN 'Vendor'
						WHEN (blg.blg_user_type='3') THEN 'Driver'
						WHEN (blg.blg_user_type='4') THEN 'Gozo'
						WHEN (blg.blg_user_type='5') THEN 'Agent'
						WHEN (blg.blg_user_type='10') THEN 'System'
						WHEN (blg.blg_user_type='6') THEN 'Corporate'
						END) AS user_type 
					FROM booking_log blg
					INNER JOIN booking bkg ON bkg.bkg_id =blg.blg_booking_id AND bkg.bkg_active = 1
					INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 AND blg.blg_user_type=3
					JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id  AND drv.drv_active = 1
					WHERE drv.drv_id  IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
						WHERE d1.drv_id=:drvId) AND drv.drv_id = drv.drv_ref_code 
							AND blg.blg_event_id =:eventId $bkgIdQry
							AND blg_driver_assigned_id  IN (SELECT d3.drv_id FROM drivers d1
								INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
								INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
								WHERE d1.drv_id=:drvId)";

		$recordset = DBUtil::query($sql, DBUtil::MDB(), $param);
		return $recordset;
	}

	public static function getCommentTraceByDCO($vndId, $drvId, $bkgId = null)
	{
		$bkgIdQry	 = '';
		$param		 = ["vndId" => trim($vndId), "drvId" => trim($drvId)];
		if ($bkgId > 0)
		{
			$param["bkgId"]	 = trim($bkgId);
			$bkgIdQry		 = " AND bkg.bkg_id =:bkgId";
		}
		$sql = "SELECT blg.blg_desc, blg.blg_created, bkg.bkg_id ,
                (CASE 
                WHEN (blg.blg_user_type='1') THEN 'Consumers'
                WHEN (blg.blg_user_type='2') THEN 'Vendor'
                WHEN (blg.blg_user_type='3') THEN 'Driver'
                WHEN (blg.blg_user_type='4') THEN 'Gozo'
                WHEN (blg.blg_user_type='5') THEN 'Agent'
                WHEN (blg.blg_user_type='10') THEN 'System'
                WHEN (blg.blg_user_type='6') THEN 'Corporate'
                END) as user_type 
			FROM booking_log blg
			INNER JOIN booking bkg ON bkg.bkg_id =blg.blg_booking_id AND bkg.bkg_active = 1
			INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1 
				WHERE  blg.blg_event_id = 29 $bkgIdQry
				AND ((blg.blg_entity_type = 2 AND blg.blg_entity_id=:vndId)
					OR (blg.blg_entity_type = 3 AND blg.blg_entity_id=:drvId))
				AND bcb.bcb_driver_id = :drvId 	
			ORDER BY blg.blg_created DESC
			 ";

		$recordset = DBUtil::query($sql, DBUtil::MDB(), $param);
		return $recordset;
	}

	public function getComment($bkgId, $drvId, $eventId)
	{
		if ((trim($bkgId) == "" || trim($bkgId) == null) || (trim($drvId) == "" || trim($drvId) == null) || (trim($eventId) == "" || trim($eventId) == null))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$param = array("bkgId" => trim($bkgId), "drvId" => trim($drvId), "eventId" => trim($eventId));

		$sql		 = "SELECT blg.blg_desc, blg.blg_created,
                (CASE 
                WHEN (blg.blg_user_type='1') THEN 'Consumers'
                WHEN (blg.blg_user_type='2') THEN 'Vendor'
                WHEN (blg.blg_user_type='3') THEN 'Driver'
                WHEN (blg.blg_user_type='4') THEN 'Gozo'
                WHEN (blg.blg_user_type='5') THEN 'Agent'
                WHEN (blg.blg_user_type='10') THEN 'System'
                WHEN (blg.blg_user_type='6') THEN 'Corporate'
                END) as user_type 
                FROM booking_log blg
                INNER JOIN booking bkg ON bkg.bkg_id =blg.blg_booking_id AND bkg.bkg_active = 1
                INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_active = 1
                JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id  AND drv.drv_active = 1
				WHERE drv.drv_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id=:drvId) AND drv.drv_id = drv.drv_ref_code AND blg.blg_event_id =:eventId AND bkg.bkg_id =:bkgId ORDER BY blg_created DESC LIMIT 0,1";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::MDB(), $param);
		return $recordset;
	}

	public function logRouteProcessed($resQt, $bkg_id = '',$transferType = '')
	{
		$processedTripTypeId = $resQt->processedTripType;
		$processedTripType	 = trim(Booking::model()->getBookingType($processedTripTypeId));
		$servingRoute		 = $resQt->servingRoute;
		$startCity = Cities::model()->findByPk($servingRoute['start']);
		$startRoute			 = $startCity->cty_name;
		$endRoute			 = Cities::model()->findByPk($servingRoute['end'])->cty_name;
		$getroute			 = $resQt->routeDistance->routeDesc;

		if (trim($getroute[0]) !== trim($startRoute))
		{
			array_unshift($getroute, $startRoute);
		}

		if (trim($getroute[count($getroute) - 1]) !== trim($endRoute))
		{
			array_push($getroute, $endRoute);
		}
		if($processedTripTypeId == 4 && $transferType == 2 && $startCity->cty_is_airport==1)
		{
			$getroute0 = $getroute[0];
			$getroute[0] = $getroute[1];
			$getroute[1] = $getroute0;
		}
		$routeFollowed	 = trim(implode(' - ', $getroute));
		$processedRoute	 = "$routeFollowed  ($processedTripType)";
		return $processedRoute;
		//BookingLog::model()->createLogRouteProcessed($bkg_id, $processedRoute);
	}

	public function createLogRouteProcessed($bkg_id, $processedRoute)
	{
		$userInfo	 = UserInfo::model();
		$eventid	 = BookingLog::BOOKING_CREATED;
		BookingLog::model()->createLog($bkg_id, $processedRoute, $userInfo, $eventid, false, $params);
	}

	public function informChangesLog($bkgid, $changesForConsumer, $changesForVendor, $changesForDriver)
	{
		$booking = Booking::model()->findByPk($bkgid);
		$msgCom	 = new smsWrapper();
		$ext	 = $booking->bkgUserInfo->bkg_country_code;
		$phone	 = $booking->bkgUserInfo->bkg_contact_no;

		$bookingID	 = $booking->bkg_booking_id;
		$cabmodel	 = $booking->bkgBcb;
		if ($phone != '' && trim($changesForConsumer) != '' && $user)
		{
			$logType = BookingLog::System;
			$msgCom->informChangesToCustomer($ext, $phone, $bookingID, $changesForConsumer, $logType);
		}
		if ($cabmodel->bcb_driver_phone != '' && trim($changesForDriver) != '' && $driver)
		{
			$logType = BookingLog::System;
			$msgCom->informChangesToDriver('91', $cabmodel->bcb_driver_phone, $bookingID, $changesForDriver, $logType);
		}
		$vndContactId = ContactProfile::model()->getByEntityId($cabmodel->bcbVendor->vnd_id, UserInfo::TYPE_VENDOR);
		if (!$vndContactId)
		{
			$vndContactId = $cabmodel->bcbVendor->vnd_contact_id;
		}
		$phone = ContactPhone::model()->getContactPhoneById($vndContactId);
		if ($phone != '' && trim($changesForVendor) != '' && $vendor)
		{
			$logType = BookingLog::System;
			$msgCom->informChangesToVendor('91', $phone, $bookingID, $changesForVendor, $logType);
		}
	}

	public function maintainLogForCabDriverDetails($model, $emailSent, $smsSent)
	{
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$email		 = $response->getData()->email['email'];
		}
		if ($emailSent == 1 || $smsSent == 1)
		{
			if ($emailSent == 1 && $smsSent == 1)
			{
				$desc = "Cab / Driver details were sent to customer at phone: " . $contactNo . " and email: " . $email . "";
			}
			else if ($emailSent == 1 && $smsSent == 0)
			{
				$desc = "Cab / Driver details were sent to customer at email: " . $email . "";
			}
			else if ($emailSent == 0 && $smsSent == 1)
			{
				$desc = "Cab / Driver details were sent to customer at phone: " . $contactNo . "";
			}
			$params['blg_booking_status']	 = $model->bkg_status;
			$userInfo						 = UserInfo::getInstance();
			$this->createLog($model->bkg_id, $desc, $userInfo, BookingLog::CAB_DRIVER_DETAILS_SENT, $oldModel, $params);
		}
	}

	/**
	 * Function for archiving booking log data
	 * @param $archiveDB
	 */
	public function archiveData($archiveDB)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = 500000;
		$limit		 = 1000;
		while ($chk)
		{
			try
			{
				$transaction = DBUtil::beginTransaction();
				$sql = "SELECT GROUP_CONCAT(blg_id) as blg_id FROM (
							SELECT `blg_id` FROM `booking_log` 
							WHERE `blg_event_id` IN (705,100,2,6,12,13,14,15,19,20,22,23,24,26,27,28,30,31,33,34,35,36,37,38,39,40,41,42,43,48,49,50,68,71,72,74,75,76,77,78,79,80,81,84,85,86,87,88,90,92,94,550,200,56,194,104)
							AND `blg_created` < CONCAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), ' 23:59:59') 
							ORDER BY `blg_id` LIMIT 0, $limit
						) as temp";

				$resQ = DBUtil::command($sql)->queryScalar();
				if (!is_null($resQ) && $resQ != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`booking_log` (SELECT * FROM `booking_log` WHERE blg_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();
					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `booking_log` WHERE blg_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);
				
				$transaction = DBUtil::beginTransaction();
				$sql = "SELECT GROUP_CONCAT(blg_id) as blg_id FROM (
							SELECT `blg_id` FROM `booking_log` 
							WHERE `blg_created` < '2022-04-01 00:00:00' AND blg_event_id NOT IN (3,7,11,16,44,46,55,74,128,129,137) 
							ORDER BY `blg_id` LIMIT 0, $limit
						) as temp";
				$resA = DBUtil::command($sql)->queryScalar();
				if (!is_null($resA) && $resA != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`booking_log` (SELECT * FROM `booking_log` WHERE blg_id IN ($resA))";
					$rows	 = DBUtil::command($sql)->execute();
					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `booking_log` WHERE blg_id IN ($resA)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);
				
				$i += $limit;
				if (($resQ <= 0 && $resA <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				echo "\r\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function updateViewStatus($model, $bkgid, $desc, UserInfo $userInfo = null, $eventid)
	{
		if ($userInfo == null)
		{
			$userInfo = UserInfo::model();
		}
		$bookingLog					 = $model;
		$bookingLog->blg_booking_id	 = $bkgid;
		$bookingLog->blg_desc		 = $desc;
		if ($userInfo->userType != ""):
			$bookingLog->blg_user_type = $userInfo->userType;
		endif;
		if ($userInfo->userId != ""):
			$bookingLog->blg_user_id = $userInfo->userId;
		endif;
		if ($userInfo->userType == 4)
		{
			$bookingLog->blg_admin_id = $userInfo->userId;
		}
		$bookingLog->blg_event_id = $eventid;
		$bookingLog->save();
		return $bookingLog->blg_id;
	}

	public function isExistViewStatus($bkgid, UserInfo $userInfo = null, $eventid)
	{
		$sql	 = "SELECT blg_id FROM `booking_log` WHERE `blg_user_id` = " . $userInfo->userId . " AND `blg_booking_id` = " . $bkgid . " AND `blg_event_id` = " . $eventid . " AND blg_active=1 ORDER BY blg_id DESC LIMIT 1";
		$blgID	 = DBUtil::command($sql)->queryScalar();
		return $blgID;
	}

	public function getCSRId($bkgId)
	{
		$sql = "SELECT booking_log.blg_user_id FROM booking_log WHERE 
				booking_log.blg_booking_id = $bkgId AND booking_log.blg_event_id = 130 
				AND booking_log.blg_user_type = 4 AND booking_log.blg_active = 1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function buildTxt($supportTitcketno = "")
	{
		$arr			 = $this->attributes;
		$addtionalDesc	 = trim($arr['blg_addl_desc']);

		$desc_short			 = " NOTES: " . trim($arr['blg_desc']);
		$reasonViewBooking	 = $this->reasonViewBooking;
		$receivedQueryFrom	 = $this->receivedQueryFrom;
		$receivedQueryVia	 = $this->receivedQueryVia;
		$reasonText			 = "";
		if ($arr['blg_reason_text'])
		{
			$reasonText = " (" . $arr['blg_reason_text'] . ") ";
		}
		if ($arr['blg_reason_view'])
		{
			$extdesc1 = "WHY - " . $reasonViewBooking[$arr['blg_reason_view']] . $reasonText . " ;";
		}if ($arr['blg_query_from'])
		{
			$extdesc2 = "FROM - " . $receivedQueryFrom[$arr['blg_query_from']] . " ;";
		}if ($arr['blg_query_via'])
		{
			$extdesc3 = "VIA - " . $receivedQueryVia[$arr['blg_query_via']] . $supportTitcketno . " ;";
		}
		$str		 = $extdesc1 . $extdesc2 . $extdesc3;
		$allextdesc	 = substr($str, 0, -1);

		if ($arr['blg_reason_view'] == "" && $arr['blg_query_from'] == "" && $arr['blg_query_via'] == "")
		{
			$firstSeparator	 = "";
			$lastSeparator	 = "";
		}
		else
		{
			$firstSeparator	 = " [";
			$lastSeparator	 = "]";
		}
		$desc = $firstSeparator . $allextdesc . $lastSeparator . $desc_short;
		return $desc;
	}

	public static function getRescheduleTimeLog($bkgId, $attr = '')
	{
		$sql = "select blg_id from booking_log where blg_booking_id = $bkgId and blg_ref_id = '409' AND blg_event_id = '4'";
		if ($attr != '')
		{
			$sql = "select $attr from booking_log where blg_booking_id = $bkgId and blg_ref_id = '409' AND blg_event_id = '4'";
		}
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	public function getGozoCancelCounReport($date1, $date2)
	{
		$params	 = ['date1' => $date1, 'date2' => $date2];
		$sql	 = "SELECT DATE_FORMAT(booking.bkg_pickup_date, '%Y-%m') date,
					COUNT(DISTINCT booking.bkg_id) AS GozoCancelCount
					FROM booking
					INNER JOIN booking_invoice     ON booking_invoice.biv_bkg_id = booking.bkg_id
					INNER JOIN booking_cab ON bcb_id = bkg_bcb_id
					INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id
					INNER JOIN users ON users.user_id = booking_user.bkg_user_id
					INNER JOIN booking_log on booking.bkg_id = booking_log.blg_booking_id and booking_log.blg_user_type<>1 and booking_log.blg_event_id in (10,82)
					WHERE  booking.bkg_status IN (9) AND booking.bkg_pickup_date BETWEEN :date1 AND :date2   AND booking.bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) GROUP BY date";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public function getInfoByEvent($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT blg_created,blg_event_id,blg_user_type,blg_user_id,blg_desc, (CASE 
                WHEN (blg_user_type='1') THEN 'Consumers'
                WHEN (blg_user_type='2') THEN 'Vendor'
                WHEN (blg_user_type='3') THEN 'Driver'
                WHEN (blg_user_type='4') THEN 'Gozo'
                WHEN (blg_user_type='5') THEN 'Agent'
                WHEN (blg_user_type='10') THEN 'System'
                WHEN (blg_user_type='6') THEN 'Corporate'
                END) as user_type  FROM  booking_log WHERE blg_event_id IN(130,137,7,46,44,10)
 AND blg_booking_id= :bkgId   GROUP BY blg_event_id ORDER BY blg_id DESC  ";
		return DBUtil::queryAll($sql, DBUtil::SDB(), $params);
	}

	public function getDetailByEvent($event, $bkgId)
	{
		$params	 = ['bkgId' => $bkgId, 'event' => $event];
		$sql	 = "SELECT blg_created,blg_event_id,blg_user_type,blg_user_id,blg_desc, (CASE 
                WHEN (blg_user_type='1') THEN 'Consumers'
                WHEN (blg_user_type='2') THEN 'Vendor'
                WHEN (blg_user_type='3') THEN 'Driver'
                WHEN (blg_user_type='4') THEN 'Gozo'
                WHEN (blg_user_type='5') THEN 'Agent'
                WHEN (blg_user_type='10') THEN 'System'
                WHEN (blg_user_type='6') THEN 'Corporate'
                END) as user_type  FROM  booking_log WHERE blg_event_id = :event
 AND blg_booking_id= :bkgId    ORDER BY blg_id DESC LIMIT 1  ";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public static function isVendorUnAssigned($vndID, $bkgId)
	{
		$params	 = ['bkgId' => $bkgId, 'event' => 8, 'vendorId' => $vndID];
		$sql	 = "SELECT count(blg_id) cnt FROM `booking_log` WHERE `blg_booking_id` = :bkgId AND `blg_event_id` = :event AND `blg_vendor_assigned_id` = :vendorId";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		if ($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function UnassignedVendors($bookingId)
	{

		$sql = "select blg_vendor_id FROM booking_log
                                WHERE 
                                blg_booking_id =$bookingId AND blg_event_id =8 AND blg_vendor_id IS NOT NULL";

		$vndArr = DBUtil::queryAll($sql, DBUtil::SDB());

		foreach ($vndArr as $k => $arr)
		{
			$vendorArr[] = $arr['blg_vendor_id'];
		}
		$vndStr = implode(",", $vendorArr);
		return $vndStr;
	}

	public static function isVendorDirectAccept($vndID, $bkgId)
	{
		$params	 = ['bkgId' => $bkgId, 'event' => 7, 'vendorId' => $vndID, 'userType' => 2];
		$sql	 = "SELECT count(blg_id) cnt FROM `booking_log` WHERE `blg_user_type` = :userType AND `blg_booking_id` = :bkgId AND `blg_event_id` = :event AND `blg_vendor_assigned_id` = :vendorId";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		if ($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function isVendorManuallyAssigned($vndID, $bkgId)
	{
		$params	 = ['bkgId' => $bkgId, 'event' => 7, 'vendorId' => $vndID, 'userType' => 4];
		$sql	 = "SELECT count(blg_id) cnt FROM `booking_log` WHERE `blg_user_type` = :userType AND `blg_booking_id` = :bkgId AND `blg_event_id` = :event AND `blg_vendor_assigned_id` = :vendorId";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		if ($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function chkDriverDetailsShow($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		//$eventId = '84,410';
		$sql	 = "SELECT count(blg_id) cnt FROM `booking_log` WHERE  `blg_booking_id` = :bkgId AND `blg_event_id` IN (84,410)";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		if ($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param integer $bkgId
	 * @return bool 
	 */
	public static function gnowOfferDisplayedToCustomer($bkgId)
	{
		$cKey				 = "gnowOfferDisplayedToCustomer_" . $bkgId;
		$bookingShowEvent	 = Yii::app()->cache->get($cKey);
		if (empty($bookingShowEvent))
		{
			$desc = "Offer displayed to customer";
			BookingLog::model()->createLog($bkgId, $desc, UserInfo::getInstance(), BookingLog::DISPLAYED_TO_CUSTOMER, false);
			Yii::app()->cache->set($cKey, 1, 21600);
		}
		return true;
	}

	/**
	 * @param integer $bkgId
	 * @param array $timerStat
	 * @return bool 
	 */
	public static function gnowOfferSearchTimeout($bkgId, $timerStat)
	{
		$cKey			 = "gnowTimeOut_" . $bkgId . "_" . $timerStat['stepValidation'];
		$showTimerData	 = Yii::app()->cache->get($cKey);
		$desc			 = "Search timed out ";
		if (empty($showTimerData))
		{
			$logDeatils	 = BookingLog::getLatestByBookingIdEventId($bkgId, BookingLog::SEARCH_TIMEOUT);
			$diffseconds = time() - strtotime($logDeatils['blg_created']);
			if ($diffseconds > 50)
			{
				BookingLog::model()->createLog($bkgId, $desc, UserInfo::getInstance(), BookingLog::SEARCH_TIMEOUT, false);
				Yii::app()->cache->set($cKey, 1, 21600);
			}
		}
		return true;
	}

	public static function getRemarksHistoryByAgent($bkgId, $userId)
	{
		$sql			 = "SELECT booking_log.blg_desc as remarks, booking_log.blg_created createdate
				FROM `booking_log`
				WHERE booking_log.blg_active = 1 AND booking_log.blg_event_id IN (101) AND booking_log.blg_user_id = {$userId} AND booking_log.blg_booking_id = {$bkgId}";
		$sqlCount		 = "SELECT COUNT(booking_log.blg_id) FROM `booking_log`
				    WHERE booking_log.blg_active = 1 AND booking_log.blg_event_id IN (101) AND booking_log.blg_user_id = {$userId}
                    AND booking_log.blg_booking_id = {$bkgId}";
		$count			 = DBUtil::command($sqlCount, DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
		]);
		return $dataprovider;
	}

	/**
	 * @param integer $bkgId
	 * @param integer $eventId
	 * @return array 
	 */
	public static function getLatestByBookingIdEventId($bkgId, $eventId)
	{
		$sql	 = "SELECT blg_user_id,blg_entity_type,blg_entity_id,blg_desc,blg_created FROM booking_log WHERE blg_booking_id=:bkgId AND blg_event_id=:eventId ORDER BY blg_id DESC";
		$params	 = ['bkgId' => $bkgId, 'eventId' => $eventId];
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * @param integer $bkgId	
	 * @return integer 
	 */
	public static function checkMissedGozoNowOfferNotified($bkgId)
	{
		$eventId = BookingLog::CUSTOMER_NOTIFIED_FOR_MISSED_GOZONOW_OFFER;
		$params	 = ['bkgId' => $bkgId, 'eventId' => $eventId];
		$sql	 = "SELECT count(blg_id) from booking_log where blg_event_id =:eventId AND blg_booking_id=:bkgId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * @param integer $bkgId	
	 * 
	 */
	public static function missedGozoNowOfferNotified($bkgId, $refId, $isWhatsApp = false)
	{
		$params					 = [];
		$params['blg_ref_id']	 = $refId;
		$desc					 = "Consumer is notified for a new gozonow offer";
		if ($isWhatsApp)
		{
			$desc = $desc . ' by WhatsApp';
		}
		$eventId = BookingLog::CUSTOMER_NOTIFIED_FOR_MISSED_GOZONOW_OFFER;
		BookingLog::model()->createLog($bkgId, $desc, null, $eventId, false, $params);
	}

	/**
	 * 
	 * @param type $vndCode
	 * @param type $bkgId
	 * @return type
	 */
	public static function getCsrIdByVndId($vndId, $bkgId)
	{
		$eventId = BookingLog::ASKMANUAL_ASSIGNMENT;
		$params	 = ['vndId' => $vndId, 'eventId' => $eventId, 'bkgId' => $bkgId];
		$sql	 = "SELECT blg_admin_id FROM `booking_log` WHERE `blg_booking_id` =:bkgId AND `blg_event_id` =:eventId AND blg_vendor_assigned_id =:vndId ORDER BY blg_id DESC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * show all adminId according to event
	 * @param type $eventId
	 * @param type $bkgId
	 */
	public static function getDataByEventId($eventId, $bkgId)
	{

		$sql	 = "SELECT blg_user_id FROM `booking_log` WHERE `blg_event_id` = :eventId  AND blg_booking_id=:bkgId ORDER BY blg_id DESC";
		$params	 = ['eventId' => $eventId, 'bkgId' => $bkgId];
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public function getAppNotRequired()
	{
		$where = "";
		if ($this->bkg_pickup_date1 != '' && $this->bkg_pickup_date2 != '')
		{
			$pickupDate1 = $this->bkg_pickup_date1 . ' 00:00:00';
			$pickupDate2 = $this->bkg_pickup_date2 . ' 23:59:59';
		}
		if ($pickupDate1 != '' && $pickupDate2 != '')
		{
			$where .= " AND (bkg.bkg_pickup_date BETWEEN '{$pickupDate1}' AND '{$pickupDate2}') ";
		}
		if ($this->vendor_name != '')
		{
			$where .= " AND vnd.vnd_id=$this->vendor_name ";
		}

		$query			 = "SELECT CONCAT(adm.adm_fname, ' ', adm.adm_lname, ' (',  adm.adm_user, ')') as adminName, bkg.bkg_id , bkg.bkg_booking_id, bkg.bkg_pickup_date,
					vnd.vnd_name, drv.drv_name, drs.drs_total_trips, drs.drs_last_logged_in
				FROM booking_log blg
				INNER JOIN booking bkg ON blg.blg_booking_id=bkg.bkg_id 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id=bkg.bkg_id AND bpr.bkg_driver_app_required = 0
				INNER JOIN admins adm ON adm.adm_id=blg.blg_user_id AND adm.adm_active = 1
				INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id
				INNER JOIN vendors vnd ON vnd.vnd_id=bcb.bcb_vendor_id AND vnd.vnd_active = 1 
                LEFT JOIN drivers drv ON drv.drv_id=bcb.bcb_driver_id AND drv.drv_active = 1
				LEFT JOIN driver_stats drs ON drs.drs_drv_id = drv.drv_id
				WHERE blg.blg_event_id = 265
				";
		$sql			 = $query . $where;
		$count			 = DBUtil::queryScalar(" SELECT COUNT(*) FROM ($sql ) temp", DBUtil::SDB());
		$dataProvider	 = new CSqlDataProvider($sql, [
			"params"		 => $params,
			"totalItemCount" => $count,
			'db'			 => DBUtil ::SDB(),
			'pagination'	 => array('pageSize' => 100),
			'sort'			 => [
				'attributes'	 => ['bkg_booking_id'],
				'defaultOrder'	 => 'bkg_pickup_date DESC'
			],
		]);

		return $dataProvider;
	}

	public function DriverAppNotRequiredDetails()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND bkg_pickup_date BETWEEN '$fromDate' AND '$toDate'";
		}
		switch ($this->groupBy)
		{
			case 'executive':
				$sql = "SELECT adm_id, CONCAT(adm_fname, ' ', adm_lname) executive_name, COUNT(1) cnt,bkg_pickup_date 
					FROM booking bkg 
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bpr.bkg_driver_app_required = 0 
					INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
					INNER JOIN cities cty ON cty.cty_id = bkg.bkg_from_city_id 
					INNER JOIN booking_log blg ON blg.blg_booking_id = bkg.bkg_id 
					INNER JOIN admins adm ON adm.adm_id = blg.blg_user_id 
					LEFT JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id 
					WHERE blg_event_id = 265 AND bkg_status IN (2,3,5,6,7) $where
					GROUP BY adm_id
					ORDER BY cnt DESC ";
				break;
			case 'vendor':
				$sql = "SELECT vnd_code, vnd_name VendorName, COUNT(1) cnt, bkg_pickup_date 
					FROM booking bkg 
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bpr.bkg_driver_app_required = 0 
					INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
					INNER JOIN cities cty ON cty.cty_id = bkg.bkg_from_city_id 
					INNER JOIN booking_log blg ON blg.blg_booking_id = bkg.bkg_id 
					INNER JOIN admins adm ON adm.adm_id = blg.blg_user_id 
					LEFT JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id 
					WHERE blg_event_id = 265 AND bkg_status IN (2,3,5,6,7) $where
					GROUP BY vnd_id
					ORDER BY cnt DESC ";
				break;
			case 'zone':
				$sql = "SELECT CONCAT(adm_fname, ' ', adm_lname) executive_name, zon_name, COUNT(DISTINCT bkg_id) cnt , bkg_pickup_date
					FROM booking bkg 
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bpr.bkg_driver_app_required = 0 
					INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
					INNER JOIN cities cty ON cty.cty_id = bkg.bkg_from_city_id 
					INNER JOIN zone_cities zc ON zc.zct_cty_id = cty.cty_id 
					INNER JOIN zones ON zon_id = zct_zon_id 
					INNER JOIN booking_log blg ON blg.blg_booking_id = bkg.bkg_id 
					INNER JOIN admins adm ON adm.adm_id = blg.blg_user_id 
					LEFT JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id 
					WHERE blg_event_id = 265 AND bkg_status IN (2,3,5,6,7) $where
					GROUP BY adm_id, zct_zon_id 
					ORDER BY adm_id, cnt DESC ";
				break;
			case 'booking':
				$sql = "SELECT CONCAT(adm_fname, ' ', adm_lname) executive_name, bkg_booking_id, bkg_pickup_date, bkg_id 
					FROM booking bkg 
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id AND bpr.bkg_driver_app_required = 0 
					INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
					INNER JOIN cities cty ON cty.cty_id = bkg.bkg_from_city_id 
					INNER JOIN booking_log blg ON blg.blg_booking_id = bkg.bkg_id 
					INNER JOIN admins adm ON adm.adm_id = blg.blg_user_id 
					LEFT JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id 
					WHERE blg_event_id = 265 AND bkg_status IN (2,3,5,6,7) $where
					ORDER BY adm_id ";
				break;
			default:
				break;
		}

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes' => ['bkg_pickup_date', 'cnt'], 'defaultOrder' => 'bkg_pickup_date DESC'],
			'pagination'	 => ['pageSize' => 100],
		]);
		return $dataprovider;
	}

	public function checkExistance($bkgId, $userId, $errorId)
	{
		$params	 = ['user_id' => $userId, 'booking_id' => $bkgId];
		$sql	 = "SELECT JSON_EXTRACT(blg_additional_params,'$.vnd_err_id') as errVal FROM booking_log WHERE blg_booking_id=:booking_id AND blg_user_id =:user_id";

		$result	 = DBUtil::query($sql, DBUtil::MDB(), $params);
		$success = false;
		foreach ($result as $res)
		{
			$err = $res['errVal'];
			if ($err == $errorId)
			{

				$success = true;
				break;
			}
		}
		return $success;
	}

	public function checkErrorExistance($bkgId, $vendorId, $eventId)
	{
		$sql = "SELECT count(blg_id)as counter FROM `booking_log` WHERE blg_booking_id = :bkgId AND blg_entity_id = :vendorId AND blg_event_id =:eventId AND blg_entity_type=2  AND  now() - interval 30 minute < blg_created  ORDER BY `blg_id` DESC";

		$params = ['eventId' => $eventId, 'bkgId' => $bkgId, 'vendorId' => $vendorId];
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public static function getInitialInfoChangedDate($bkgId)
	{
		$eventRef	 = DBUtil::getINStatement([BookingLog::BOOKING_MODIFIED, BookingLog::BOOKING_ADDRESS_SUCCESSFULLY], $bindEventString, $eventRef);
		$paramRef	 = DBUtil::getINStatement([BookingLog::INITIAL_INFO_CHANGED, BookingLog::RESCHEDEULE_PICKUP_TIME], $bindRefString, $paramRef);

		$sql = "SELECT blg_created FROM `booking_log` WHERE blg_booking_id = :bkgId AND blg_event_id IN($bindEventString) AND blg_ref_id IN({$bindRefString}) ORDER BY `blg_id` DESC";

		$params = ['bkgId' => $bkgId] + $eventRef + $paramRef;
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public static function checkExistingLog($bkgId, $eventId, $createdAt = null, $refId = null, $desc = '')
	{
		$whereArr	 = [];
		$where		 = '';
		$params		 = ['bkgId' => $bkgId, 'eventId' => $eventId];
		if ($refId != '')
		{
			$whereArr[] = " blg_ref_id = $refId";
		}
		if ($refId != '')
		{
			$whereArr[] = " blg_desc LIKE '%$desc%'";
		}
		$whereStr = implode(' OR ', $whereArr);
		if ($whereStr != '')
		{
			$where = ' AND (' . $whereStr . ')';
		}
		if ($createdAt != '')
		{
			$where .= " AND blg_created > '$createdAt'";
		}
		$sql = "SELECT blg_id  FROM `booking_log` WHERE `blg_booking_id` = :bkgId AND `blg_event_id` = :eventId $where";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function checkEventsDoneByAdmin($arrEventIds, $bkgId)
	{
		$eventIds = implode(',', $arrEventIds);

		$sql = "SELECT 
				SUM(IF(blg_event_id=93 AND blg_user_type=4, 1, 0)) cabArrivedByAdmin,
				SUM(IF(blg_event_id=215 AND blg_user_type=4, 1, 0)) rideStartedByAdmin
				FROM `booking_log` WHERE `blg_event_id` IN ({$eventIds}) AND blg_booking_id={$bkgId}
				GROUP BY blg_booking_id";

		return DBUtil::queryRow($sql, DBUtil::SDB());
	}
    public function accountingFlagData($fromDate,$toDate)
    {
        $sql          = "SELECT 
				DATE_FORMAT(blg_created, '%Y-%m-%d') as date, 
				SUM(IF(blg_event_id = 65, 1, 0)) as accFlgSetCnt, 
				SUM(IF(blg_event_id = 66, 1, 0)) as accFlgUnsetCnt 
				FROM booking_log 
				WHERE blg_event_id IN (65,66) AND blg_created BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59' 
				GROUP BY date";
        $count        = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
        $dataprovider = new CSqlDataProvider($sql, [
            'totalItemCount' => $count,
            'sort'           => ['defaultOrder' => 'date DESC'],
            'pagination'     => ['pageSize' => 50],
        ]);

        return $dataprovider;
    }

}
