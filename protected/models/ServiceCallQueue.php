<?php

/**
 * This is the model class for table "service_center_queue".
 *
 * The followings are the available columns in table 'service_call_queue':
 * @property string $scq_id
 * @property string $scq_create_date
 * @property integer $scq_created_by_type
 * @property integer $scq_created_by_uid
 * @property string $scq_disposition_date
 * @property integer $scq_disposed_by_uid
 * @property integer $scq_to_be_followed_up_by_type
 * @property integer $scq_to_be_followed_up_by_id
 * @property integer $scq_to_be_followed_up_with_type
 * @property integer $scq_to_be_followed_up_with_value
 * @property integer $scq_to_be_followed_up_with_entity_type
 * @property integer $scq_to_be_followed_up_with_entity_id
 * @property integer $scq_to_be_followed_up_with_entity_rating
 * @property string $scq_follow_up_date_time
 * @property integer $scq_follow_up_priority
 * @property string $scq_creation_comments
 * @property string $scq_disposition_comments
 * @property integer $scq_follow_up_queue_type
 * @property integer $scq_related_bkg_id
 * @property integer $scq_related_lead_id
 * @property string $scq_assigned_date_time
 * @property string $scq_assigned_uid
 * @property integer $scq_status
 * @property integer $scq_prev_or_originating_followup
 * @property integer $scq_time_since_create
 * @property integer $scq_time_to_pickup
 * @property integer $scq_value_non_admin_contact_id
 * @property integer $scq_platform
 * @property string $scq_reason_id
 * @property string $scq_priority_score
 * @property string $scq_unique_code
 * @property string $scq_queue_no
 * @property string $scq_waittime
 * @property integer $scq_active
 * @property string $scq_additional_param
 * @property integer $scq_assignment_count
 * @property double $scq_time_to_assign
 * @property double $scq_time_to_close
 * @property integer $scq_disposed_team_id
 * @property integer $scq_preferred_csr
 */
class ServiceCallQueue extends CActiveRecord
{
##Queue Type

	const TYPE_NEW_BOOKING							 = 1;
	const TYPE_EXISTING_BOOKING						 = 2;
	const TYPE_NEW_VENDOR_ATTACHMENT					 = 3;
	const TYPE_EXISTING_VENDOR						 = 4;
	const TYPE_ADVOCACY								 = 5;
	const TYPE_DRIVER									 = 6;
	const TYPE_PAYMENT_FOLLOWUP						 = 7;
	const TYPE_CORPORATE_SALES						 = 8;
	const TYPE_IMNTERNAL								 = 9;
	const TYPE_SOS									 = 10;
	const TYPE_PENALITY_DISPUTE						 = 11;
	const TYPE_UPSELL									 = 12;
	const TYPE_VENDOR_ADVOCACY						 = 13;
	const TYPE_DISPATCH								 = 14;
	const TYPE_VENDOR_APPROVAl						 = 15;
	const TYPE_NEW_LEAD_BOOKING						 = 16;
	const TYPE_NEW_QUOTE_BOOKING						 = 17;
	const TYPE_B2B_POST_PICKUP						 = 18;
	const TYPE_BAR									 = 19;
	const TYPE_NEW_LEAD_BOOKING_INTERNATIONAL			 = 20;
	const TYPE_NEW_QUOTE_BOOKING_INTERNATIONAL		 = 21;
	const TYPE_FBG									 = 22;
	const TYPE_VENDOR_REQUEST_PAYMENT					 = 23;
	const TYPE_UPSELL_UPPERTIER						 = 24;
	const TYPE_BOOKING_COMPLETE_REVIEW				 = 25;
	const TYPE_APP_HELP_TECH_SUPPORT					 = 26;
	const TYPE_GOZONOW								 = 27;
	const TYPE_AGENT_NOT_SERVED_BOOKING				 = 28;
	const TYPE_AUTO_FOLLOWUP_LEAD						 = 29;
	const TYPE_DOCUMENT_APPROVAL						 = 30;
	const TYPE_VENDOR_APPROVAL_ZONE_BASED_INVENTORY	 = 31;
	const TYPE_CSA									 = 32;
	const TYPE_AIRPORT_DAILYRENTAL					 = 33;
	const TYPE_LAST_MIN_BOOKING						 = 34;
	const TYPE_PRICE_HIGH								 = 35;
	const TYPE_DRIVER_NOSHOW							 = 36;
	const TYPE_CUSTOMER_NOSHOW						 = 37;
	const TYPE_MMT_SUPPORT							 = 38;
	const TYPE_DRIVER_CAR_BREAKDOWN					 = 39;
	const TYPE_VENDOR_ASSIGN							 = 40;
	const TYPE_CUSTOMER_BOOKING_CANCEL				 = 41;
	const TYPE_NEW_SPICE_LEAD_BOOKING					 = 42;
	const TYPE_NEW_SPICE_QUOTE_BOOKING				 = 43;
	const TYPE_NEW_SPICE_LEAD_BOOKING_INTERNATIONAL	 = 44;
	const TYPE_NEW_SPICE_QUOTE_BOOKING_INTERNATIONAL	 = 45;
	const TYPE_VENDOR_DUE_AMOUNT						 = 46;
	const TYPE_DRIVER_CUSTOM_PUSH_API					 = 52;
//Driver DCO  
	const TYPE_DRIVER_START_TRIP_ISSUE				 = 47;
	const TYPE_DRIVER_STOP_TRIP_ISSUES				 = 48;
	const TYPE_DRIVER_APP_OTP_VALIDATION_ISSUE		 = 49;
	const TYPE_DRIVER_APP_SYNC_ISSUE					 = 50;
	const TYPE_BOOKING_RESCHEDULE						 = 51;
	const TYPE_VVIP_BOOKING							 = 53;
	const TYPE_SAFETY									 = 54;
	const TYPE_DRIVER_BEHAVIOUR						 = 55;
	const TYPE_CAR_ISSUES								 = 56;
	const TYPE_RIDE_ISSUES							 = 57;
	const TYPE_PAYMENT_ISSUES							 = 58;
	const TYPE_OTHERS									 = 99;
	const queueList									 = [
		1	 => 'New',
		2	 => 'Existing',
		3	 => 'Attachment',
		4	 => 'Vendor',
		5	 => 'Advocacy',
		6	 => 'Driver',
		7	 => 'Payment Followup',
		8	 => 'Corporate Sales',
		9	 => 'Internal',
		10	 => 'SOS',
		11	 => 'Penality Dispute',
		12	 => 'Upsell',
		13	 => 'Vendor Advocacy',
		14	 => 'Dispatch',
		15	 => 'Vendor Approval',
		16	 => 'New Lead Booking',
		17	 => 'New Quote Booking',
		18	 => 'B2B Post pickup',
		19	 => 'Booking At Risk(BAR)',
		20	 => 'New Lead Booking(International)',
		21	 => 'New Quote Booking(International)',
		22	 => 'FBG',
		23	 => 'Vendor Payment Request',
		24	 => 'Upsell UpperTier',
		25	 => 'Booking complete review',
		26	 => 'Apps Help & Tech support',
		27	 => 'Gozo Now',
		28	 => 'Agents Unserved Booking',
		29	 => 'Auto Lead Followup',
		30	 => "Document Approval",
		31	 => 'Vendor Approval Zone Based Inventory',
		32	 => 'Critical and stress (risk) assignments(CSA)',
		33	 => 'Airport DailyRental',
		34	 => 'Last Min Booking',
		35	 => 'Price High',
		36	 => 'Driver NoShow',
		37	 => 'Customer NoShow',
		38	 => 'Mmt Support',
		39	 => 'Driver Car BreakDown',
		40	 => 'Vendor Assign',
		41	 => 'Cusormer Booking Cancel',
		42	 => 'Spice Lead Booking',
		43	 => 'Spice Quote Booking',
		44	 => 'Spice Lead Booking International',
		45	 => 'Spice Quote Booking International',
		46	 => 'Vendor Due Amount',
		54	 => 'Safety',
		55	 => 'Driver Behaviour',
		56	 => 'Car Issues',
		57	 => 'Ride Issues',
		58	 => 'Payment Issues'
	];
	const SUB_FOLLOW_UP_REQUEST						 = 1;
	const SUB_QUOTE_CREATED_FOLLOWUP					 = 2;
	const SUB_LEADS									 = 3;
	const SUB_REFOLLOWUP								 = 4;

##Platform
	const Platform_BOT			 = 0;
	const PLATFORM_WEB_DESKTOP	 = 1;
	const PLATFORM_WEB_MOBILE		 = 2;
	const PLATFORM_VENDOR_APP		 = 3;
	const PLATFORM_CONSUMER_APP	 = 4;
	const PLATFORM_DRIVER_APP		 = 5;
	const PlatForm_DIRECT_CALL	 = 6;
	const PLATFORM_ADMIN_CALL		 = 7;
	const PLATFORM_DCO_APP		 = 8;
	const PLATFORM_WHATSAPP		 = 9;
	const PLATFORM_SYSTEM			 = 10;
	const platformList			 = [
		0	 => 'Bot',
		1	 => 'Web(desktop)',
		2	 => 'Web(mobile)',
		3	 => 'Vendor app',
		4	 => 'Consumer app',
		5	 => 'Driver app',
		6	 => 'Direct call',
		7	 => 'Admin call',
		8	 => 'DCO app',
		10	 => 'System',
	];
	const priorityList			 = [1 => 'Best Effort', 2 => 'Low', 3 => 'Medium', 4 => 'High', 5 => 'Very Urgent'];
	const maxWaitHourByPriority	 = [1 => 24, 2 => 12, 3 => 6, 4 => 2, 5 => 1];
//
	const referenceTypeList		 = [1 => 'Booking', 2 => 'Trip', 3 => 'Transaction'];
//
	const REF_BOOKING				 = 1;
	const REF_TRIP				 = 2;
	const REF_TRANSACTION			 = 3;
//

	const FollowupWithList = [1 => "Customer", 5 => 'Agent', 2 => 'Vendor', 3 => 'Driver', 6 => 'Internal'];

	public $followupWith, $event_id, $event_by, $queueType, $date1, $date2, $isMycall,
			$contactRequired, $isBooking, $weekDays, $isGozonow,
			$followUpTimeOpt, $followUpby, $isFollowUpOpen,
			$followUptype, $followupPerson, $person_unique_code,
			$scq_to_be_followed_up_with_drv,
			$scq_to_be_followed_up_with_agt, $scq_to_be_followed_up_with_cust, $followupWithTeam, $scq_notification;
	public $from_date;
	public $to_date;
	public $date;
	public $dateType;
	public $bookingType;
	public $dateTystoreCMBDatapeArr = [1 => 'Close Date', 2 => 'Create Date'];
	public $requestedBy, $adminId, $isGozen, $custId, $vendId, $drvId, $agntId, $countrycode, $fromDate, $toDate, $restrictCurrentTime, $selfCreated, $fullContactNumber;
	public $bkg_pickup_date1, $bkg_pickup_date2, $region, $assignMode, $isManual, $isCritical, $isMobile, $isAndroid, $isIOS, $teamList, $force, $bkgtypes, $regions;

	/**
	 * @scqType for internal/external
	 */
	public $isDue24, $locale_followup_time, $locale_followup_date, $subQueue, $scqType, $csrSearch, $search, $isCreated, $isClosed, $isFlag = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service_call_queue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('scq_created_by_type, scq_to_be_followed_up_with_type, scq_to_be_followed_up_with_value, scq_to_be_followed_up_with_entity_type, scq_to_be_followed_up_with_entity_rating, scq_follow_up_priority, scq_follow_up_queue_type, scq_status, scq_platform,scq_priority_score', 'required'),
			array('scq_to_be_followed_up_by_id', 'validateFollowupAdd', 'on' => 'followAdd'),
			array('scq_created_by_type, scq_created_by_uid, scq_disposed_by_uid, scq_to_be_followed_up_with_type, scq_to_be_followed_up_with_value, scq_to_be_followed_up_with_entity_type, scq_to_be_followed_up_with_entity_id, scq_to_be_followed_up_with_entity_rating, scq_follow_up_priority, scq_follow_up_queue_type, scq_related_lead_id, scq_status, scq_prev_or_originating_followup, scq_time_since_create, scq_time_to_pickup, scq_value_non_admin_contact_id, scq_platform', 'numerical', 'integerOnly' => true),
			array('scq_id', 'length', 'max' => 10),
			array('scq_creation_comments, scq_disposition_comments', 'length', 'max' => 5000),
			array('scq_disposition_date, scq_follow_up_date_time, scq_assigned_date_time, bkg_pickup_date1,bkg_pickup_date2, regions', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('scq_id, scq_create_date, scq_created_by_type, scq_created_by_uid, scq_disposition_date, scq_disposed_by_uid, scq_to_be_followed_up_by_type, scq_to_be_followed_up_by_id, scq_to_be_followed_up_with_type, scq_to_be_followed_up_with_value, scq_to_be_followed_up_with_entity_type, scq_to_be_followed_up_with_entity_id, scq_to_be_followed_up_with_entity_rating, scq_follow_up_date_time, scq_follow_up_priority, scq_creation_comments, scq_disposition_comments, scq_follow_up_queue_type, scq_related_bkg_id, scq_related_lead_id, scq_assigned_date_time,scq_assigned_uid,scq_status, scq_prev_or_originating_followup, scq_time_since_create, scq_time_to_pickup, scq_value_non_admin_contact_id, scq_platform,scq_reason_id,scq_priority_score,scq_unique_code,scq_queue_no,scq_waittime,scq_additional_param,scq_to_be_followed_up_with_contact,scq_active,scq_ref_type,scq_time_to_assign,scq_time_to_close,scq_disposed_team_id,scq_preferred_csr,scq_agent_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'scq_id'									 => 'Id',
			'scq_create_date'							 => 'Create Date',
			'scq_created_by_type'						 => 'Created By Type',
			'scq_created_by_uid'						 => 'Created By Uid',
			'scq_disposition_date'						 => 'Disposition Date',
			'scq_disposed_by_uid'						 => 'Disposed By Uid',
			'scq_to_be_followed_up_by_type'				 => 'To Be Followed Up By Type',
			'scq_to_be_followed_up_by_id'				 => 'To Be Followed Up By',
			'scq_to_be_followed_up_with_type'			 => 'To Be Followed Up With Type',
			'scq_to_be_followed_up_with_value'			 => 'To Be Followed Up With Value',
			'scq_to_be_followed_up_with_entity_type'	 => 'To Be Followed Up With Entity Type',
			'scq_to_be_followed_up_with_entity_id'		 => 'To Be Followed Up With Entity',
			'scq_to_be_followed_up_with_entity_rating'	 => 'To Be Followed Up With Entity Rating',
			'scq_follow_up_date_time'					 => 'Follow Up Date Time',
			'scq_follow_up_priority'					 => 'Follow Up Priority',
			'scq_creation_comments'						 => 'Instructions',
			'scq_disposition_comments'					 => 'Disposition Comments',
			'scq_follow_up_queue_type'					 => 'Follow Up Queue Type',
			'scq_related_bkg_id'						 => 'Related Booking Id',
			'scq_related_lead_id'						 => 'Related Lead',
			'scq_assigned_date_time'					 => 'Assigned Date Time',
			'scq_assigned_uid'							 => 'Assigned Id',
			'scq_status'								 => 'Status',
			'scq_prev_or_originating_followup'			 => 'Prev Or Originating Followup',
			'scq_time_since_create'						 => 'Time Since Create',
			'scq_time_to_pickup'						 => 'Time To Pickup',
			'scq_value_non_admin_contact_id'			 => 'Value Non Admin Contact',
			'scq_platform'								 => 'Platform',
			'scq_reason_id'								 => 'Reason Id ',
			'scq_priority_score'						 => 'Priority Score',
			'scq_unique_code'							 => 'Unique Code',
			'scq_queue_no'								 => 'Queue No',
			'scq_waittime'								 => 'Wait time',
			'search'									 => 'Search By (Booking Id, Followup Id,Followup Comment)'
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

		$criteria->compare('scq_id', $this->scq_id, true);
		$criteria->compare('scq_create_date', $this->scq_create_date, true);
		$criteria->compare('scq_created_by_type', $this->scq_created_by_type);
		$criteria->compare('scq_created_by_uid', $this->scq_created_by_uid);
		$criteria->compare('scq_disposition_date  ', $this->scq_disposition_date, true);
		$criteria->compare('scq_disposed_by_uid', $this->scq_disposed_by_uid);
		$criteria->compare('scq_to_be_followed_up_by_type ', $this->scq_to_be_followed_up_by_type);
		$criteria->compare('scq_to_be_followed_up_by_id', $this->scq_to_be_followed_up_by_id);
		$criteria->compare('scq_to_be_followed_up_with_type', $this->scq_to_be_followed_up_with_type);
		$criteria->compare('scq_to_be_followed_up_with_value', $this->scq_to_be_followed_up_with_value);
		$criteria->compare('scq_to_be_followed_up_with_entity_type', $this->scq_to_be_followed_up_with_entity_type);
		$criteria->compare('scq_to_be_followed_up_with_entity_id', $this->scq_to_be_followed_up_with_entity_id);
		$criteria->compare('scq_to_be_followed_up_with_entity_rating', $this->scq_to_be_followed_up_with_entity_rating);
		$criteria->compare('scq_follow_up_date_time', $this->scq_follow_up_date_time, true);
		$criteria->compare('scq_follow_up_priority', $this->scq_follow_up_priority);
		$criteria->compare('scq_creation_comments', $this->scq_creation_comments, true);
		$criteria->compare('scq_disposition_comments', $this->scq_disposition_comments, true);
		$criteria->compare('scq_follow_up_queue_type', $this->scq_follow_up_queue_type);
		$criteria->compare('scq_related_bkg_id', $this->scq_related_bkg_id);
		$criteria->compare('scq_related_lead_id', $this->scq_related_lead_id);
		$criteria->compare('scq_assigned_date_time', $this->scq_assigned_date_time, true);
		$criteria->compare('scq_assigned_uid', $this->scq_assigned_uid, true);
		$criteria->compare('scq_status', $this->scq_status);
		$criteria->compare('scq_prev_or_originating_followup ', $this->scq_prev_or_originating_followup);
		$criteria->compare('scq_time_since_create', $this->scq_time_since_create);
		$criteria->compare('scq_time_to_pickup', $this->scq_time_to_pickup);
		$criteria->compare('scq_value_non_admin_contact_id', $this->scq_value_non_admin_contact_id);
		$criteria->compare('scq_platform', $this->scq_platform);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ServiceCenterQueue the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used for checking  service call queue  active  for particular contact id
	 * @param type contactId
	 * @return integer
	 */
	public static function getIdByContact($contactId)
	{
		$params	 = ['contactId' => $contactId];
		$sql	 = "SELECT   scq_id
					FROM  service_call_queue
					WHERE scq_to_be_followed_up_with_entity_id =:contactId  AND scq_active=1  AND scq_status IN (1,3) order by scq_id desc   LIMIT 0,1 ";
		$res	 = DBUtil::queryScalar($sql, null, $params);
		return $res;
	}

	/**
	 * This function is used for checking  service call queue  active  for particular contact id
	 * @param type contactId
	 * @return integer
	 */
	public static function getIdByEntity($entityId)
	{
		$params	 = ['entityId' => $entityId];
		$sql	 = "SELECT   scq_id
					FROM  service_call_queue
					WHERE scq_to_be_followed_up_with_entity_id =:entityId  AND scq_active=1  AND scq_status IN (1,3) order by scq_id desc   LIMIT 0,1 ";
		$res	 = DBUtil::queryScalar($sql, null, $params);
		return $res;
	}

	/**
	 * This function is used for checking  service call queue  active  for particular user
	 * @param type $userId
	 * @return integer
	 */
	public static function checkActiveCallback($userId, $reftype = 0)
	{
		$params	 = ['userId' => $userId];
		$where	 = "";
		if ($reftype > 0)
		{
			$params['scq_follow_up_queue_type']	 = $reftype;
			$where								 = " AND scq_follow_up_queue_type =:scq_follow_up_queue_type ";
		}
		$sql = "SELECT    scq_id
					FROM     service_call_queue
					WHERE    scq_created_by_uid =:userId  AND scq_active=1 $where AND scq_status IN (1,3)   AND TIMESTAMPDIFF(HOUR, scq_create_date , NOW()) < 48";
		$res = DBUtil::queryScalar($sql, null, $params);
		return $res;
	}

	/**
	 * This function is used for getting service call queue id
	 * @param type $userId
	 * @return integer
	 */
	public static function getIdByUserId($userId, $reftype = 0, $bkgId = 0)
	{
		$params	 = ['userId' => $userId];
		$where	 = "";
		$where1	 = "";
		if ($bkgId > 0)
		{
			$params['scq_related_bkg_id']	 = $bkgId;
			$where1							 = " AND scq_related_bkg_id =:scq_related_bkg_id";
		}
		if ($reftype > 0)
		{
			$params['scq_follow_up_queue_type']	 = $reftype;
			$where								 = " AND scq_follow_up_queue_type =:scq_follow_up_queue_type ";
		}
		$sql = "SELECT   scq_id FROM  service_call_queue 
					WHERE scq_created_by_uid =:userId  $where $where1  AND scq_status IN (1,3) AND scq_active=1    
						ORDER BY scq_id desc	LIMIT 0,1 ";
		$res = DBUtil::queryScalar($sql, null, $params);
		return $res;
	}

	/**
	 * This function is used for getting Reason List
	 * @param type $reason
	 * @return integer/Array
	 */
	public static function getReasonList($reason = 0)
	{
		$reasons = [
			1	 => "New Booking",
			2	 => "Existing Booking",
			3	 => "New Vendor Attachment",
			4	 => "Existing Vendor",
			15	 => "Vendor Approval",
			16	 => "New Lead Booking",
			17	 => "New Quote Booking",
		];
		if ($reason > 0)
		{
			return $reasons[$reason];
		}
		return $reasons;
	}

	/**
	 * This function is used for data setup for service call queue
	 * @param ServiceCallQueue model
	 * @param int entityId
	 * @param int entityType - [Optional]
	 * @param int platform	-     [Optional]
	 * @param int teamIdbyForm	- [Optional]
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function create($model, $entityType = UserInfo::TYPE_CONSUMER, $platform = ServiceCallQueue::PLATFORM_WEB_DESKTOP)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		try
		{
			$userId = UserInfo::getUserId();
			/** @var ServiceCallQueue $model */
			if ($model->scq_related_bkg_id != null)
			{
				$model->scq_related_bkg_id	 = Booking::getBookingId($model->scq_related_bkg_id);
				$bkgModel					 = Booking::model()->findByPk($model->scq_related_bkg_id);
			}
			if ($userId == '')
			{
				$userId = $bkgModel->bkgUserInfo->bkg_user_id;
			}
			if ($model->scq_created_by_type == null)
			{

				$model->scq_created_by_type = UserInfo::getUserType();
			}
			$model->scq_created_by_uid = $model->scq_created_by_uid != null ? $model->scq_created_by_uid : $userId;
			if ($model->scq_created_by_type == UserInfo::TYPE_SYSTEM)
			{
				$model->scq_created_by_uid = 0;
			}

			if ($model->scq_to_be_followed_up_with_type === null)
			{
				$model->scq_to_be_followed_up_with_type = 2;
			}
			$queNoEst										 = self::countWaitingByReftype($model->scq_follow_up_queue_type);
			$model->scq_queue_no							 = $queNoEst;
			$model->scq_waittime							 = self::calculateWaitingTimeByReftype($model->scq_follow_up_queue_type, $queNoEst);
			$model->scq_unique_code							 = date('ymd') . rand(100, 999);
			$model->scq_to_be_followed_up_with_entity_type	 = $model->isFlag == "1" ? $entityType : ($model->scq_follow_up_queue_type == 4 ? UserInfo::TYPE_VENDOR : $entityType);
			$model->scq_to_be_followed_up_with_entity_rating = -1;

			if ($model->scq_related_bkg_id != null && ( Tags::isVVIPBooking($model->scq_related_bkg_id) || Tags::isVIPBooking($model->scq_related_bkg_id)))
			{
				$model->scq_follow_up_priority = 5;
			}
			if ($model->scq_follow_up_queue_type == ServiceCallQueue::TYPE_NEW_BOOKING)
			{
				$userCatRow = UserCategoryMaster::getByUserId(UserInfo::getUserId());
				if (is_array($userCatRow) && in_array($userCatRow['ucm_id'], [3, 4]))
				{
					$model->scq_follow_up_priority = 5;
				}
			}

			if ($model->scq_follow_up_priority == null)
			{
				$model->scq_follow_up_priority = ServiceCallQueue::getServiceCallPriority($model);
			}
			$model->scq_creation_comments	 = iconv('ISO-8859-1', 'ASCII//TRANSLIT//IGNORE', $model->scq_creation_comments);
			$model->scq_status				 = 1;
			$model->scq_platform			 = ($model->scq_platform) ? $model->scq_platform : $platform;
			$model->scq_follow_up_date_time	 = $model->scq_follow_up_date_time != null ? $model->scq_follow_up_date_time : DBUtil::getCurrentTime();
			$model->scenario				 = 'followAdd';

			$dynamicstring	 = $model->scq_to_be_followed_up_with_value;
			$newstring		 = substr($dynamicstring, -10);
//			if ($model->scq_to_be_followed_up_with_value != '' && $newstring == '7550596097')
//			{
//				Logger::pushTraceLogs();
//				Logger::warning("Invalid SCQ data entered", true);
//				$returnSet->addError("Invalid SCQ data entered");
//				return $returnSet;
//			}
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			else
			{
				$uniqueCode				 = self::generateUniqueCode($model->scq_id);
				$model->scq_unique_code	 = $uniqueCode;
				$model->save();
			}
			$data = [];
			if ($model->scq_id > 0)
			{
				$waitData	 = ServiceCallQueue::getActiveWaitingTimeById($model->scq_id);
				$queNo		 = $waitData['rank'] | 0;
				$waitTime	 = $waitData['totalWaitMinutes'];

				$model->scq_queue_no = $queNo;
				$model->scq_waittime = $waitTime;
				$model->save();

				$data	 = [
					'followupId'	 => (int) $model->scq_id,
					'queNo'			 => $model->scq_queue_no,
					'followupCode'	 => $model->scq_unique_code,
					'waitTime'		 => $model->scq_waittime,
					'active'		 => $model->scq_active
				];
				$success = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for validating  service Queue call before saving
	 * @param type ServiceCallQueue model
	 * @return boolean
	 */
	public function validateFollowupAdd($attribute, $params)
	{
		$refId = $this->scq_related_bkg_id;
		if ($this->scq_follow_up_queue_type == 2 && !in_array($this->scq_platform, [ServiceCallQueue::PLATFORM_ADMIN_CALL, ServiceCallQueue::PLATFORM_WHATSAPP]))
		{
			$bookingCode = BookingSub::getCodebyUserIdnId($this->scq_created_by_uid, $this->scq_related_bkg_id);
			if (!$bookingCode && ($this->force == null || !$this->force))
			{
				$this->addError($attribute, 'Invalid Booking id');
				return false;
			}
		}
		if ($this->scq_follow_up_queue_type == 4 && $this->scq_related_bkg_id != '')
		{
			$vnd	 = ContactProfile::getEntityById($this->scq_to_be_followed_up_with_contact, UserInfo::TYPE_VENDOR);
			$vndid	 = $vnd['id'];
			if (!BookingSub::getCodebyVndIdnId($vndid, $this->scq_related_bkg_id) && ($this->force == null || !$this->force))
			{
				$this->addError($attribute, 'Invalid Booking id');
				return false;
			}
		}

		if (($this->followupPerson != 1 && $this->scq_to_be_followed_up_with_value != Yii::app()->params['scqToCustomerforMMT']))
		{

			if ($this->scq_to_be_followed_up_with_type == 2 && !Filter::validatePhoneNumber($this->scq_to_be_followed_up_with_value))
			{
				$entityType	 = UserInfo::TYPE_VENDOR;
				$vnd		 = ContactProfile::getEntityById($this->scq_to_be_followed_up_with_contact, UserInfo::TYPE_VENDOR);
				$vndid		 = $vnd['id'];
				$bookingCode = BookingSub::getCodebyVndIdnId($vndid, $refId);
				if (!$bookingCode)
				{
					$this->addError($attribute, 'Invalid phone number');
					return false;
				}
			}
		}
//		if($this->contactRequired == 1 && (!$this->scq_to_be_followed_up_by_id || trim($this->scq_to_be_followed_up_by_id) == '' || $this->scq_to_be_followed_up_by_id == 0))
//		{
//			$this->addError($attribute, 'Contact is needed to be saved.');
//			return false;
//		}
		return true;
	}

	/**
	 * This function is used for deactivate  service Queue call
	 * @param type int
	 * @return int
	 */
	public static function deactivateAllEntry($userId)
	{
		$sql = "UPDATE service_call_queue SET scq_status = 0,scq_active=0 WHERE scq_to_be_followed_up_with_entity_id =:userId AND scq_status IN (1,3) ";
		return DBUtil::command($sql)->execute(['userId' => $userId]);
	}

	/**
	 * This function is used for deactivate  service Queue call
	 * @param type int
	 * @return int
	 */
	public static function deactivateById($userdId, $scq_id)
	{
		$params		 = [
			'scq_id'	 => $scq_id,
			'remarks'	 => "Self CBR closed",
		];
		$where		 = '';
		$addParams	 = '';
		if ($userdId > 0)
		{
			$params['userdId']				 = $userdId;
			$params['scq_additional_param']	 = json_encode(array('userdId' => $userdId));
			$where							 = "  AND scq_created_by_uid =:userdId";
			$addParams						 = ",scq_additional_param=:scq_additional_param ";
		}
		$sql = "UPDATE service_call_queue 
		SET scq_status = 0,
			scq_active=0,
			scq_disposition_comments = :remarks $addParams
			WHERE scq_id =:scq_id $where AND scq_status IN (1,3) ";
		return DBUtil::command($sql)->execute($params);
	}

	/**
	 * This function is used for count waiting time for particular contact id
	 * @param type int
	 * @return int
	 */
	public static function countWaitingFollowupByContact($contactId)
	{
		$params	 = ['contactId' => $contactId];
		$sql	 = "SELECT   scq_id
					FROM  service_call_queue
					WHERE scq_to_be_followed_up_with_contact =:contactId  AND scq_status IN (1,3) AND scq_active=1   AND TIMESTAMPDIFF(HOUR, scq_create_date , NOW()) < 48 order by scq_id desc   LIMIT 0,1 ";
		$res	 = DBUtil::queryScalar($sql, null, $params);
		if ($res > 0)
		{
			$res = self::countWaitingFollowupById($res);
		}
		return $res;
	}

	/**
	 * This function is used for getting ll bookingtemp/led log  for cron to dump in service call queue
	 * @return array
	 */
	public static function getAllScqData()
	{
		$sql = "SELECT
				bkg_id,
				bkg_user_id,
				IF(bkg_user_id IS NOT NULL ,bkg_user_id, IF(bkg_user_email IS NOT NULL,bkg_user_email, IF(bkg_contact_no IS NOT NULL,bkg_contact_no,NULL))) AS user_id,
				bkg_create_date,
				CONCAT('+',bkg_country_code,bkg_contact_no) as  bkg_contact_no
				FROM booking_temp
				WHERE 1
				AND bkg_create_date <= DATE_SUB(NOW(), INTERVAL 20 MINUTE)
				AND (HOUR(NOW()) <= 21 OR (HOUR(NOW()) > 21 AND TIMESTAMPDIFF(MINUTE,bkg_create_date, now()) < 45))
				AND (bkg_assigned_to = 0 OR bkg_assigned_to IS NULL)
				AND bkg_follow_up_status IN (0,1, 2, 3, 15, 16, 20,21)
				AND bkg_pickup_date > NOW()
				AND (bkg_contact_no <> '' OR bkg_log_phone <> '')
				GROUP BY user_id";
		return DBUtil::query($sql, DBUtil::MDB());
	}

	/**
	 * This function is used for fetching the follow up details
	 * @param type $refId			- [Optional]
	 * @param type $teamId			- [Optional]
	 * @param type $entityTypeId	- [Optional]
	 * @param type $followUpStatus	- [Optional]
	 * @param type $contactId	    - [Optional]
	 * @return SQL_DATA_READER
	 */
	public static function fetchList($fwpId = 0, $refId = 0, $isMycall = 0, $contactId = 0, $userId = 0)
	{

		$platformString	 = '';
		$platFormArr	 = ServiceCallQueue::platformList;
		foreach ($platFormArr as $p => $q)
		{
			$platformString .= 'WHEN scq_platform = ' . $p . ' THEN "' . $q . '" ';
		}
		$qrySelect	 = 'SELECT
							IF(scq_status IN (1,3),1,0) AS scqActive,
							scq_to_be_followed_up_with_type, scq_to_be_followed_up_with_value, scq_to_be_followed_up_by_type,
							scq_to_be_followed_up_by_id, scq_prev_or_originating_followup, IF(scq_id=' . $fwpId . ',1,0) as refOrder,
							scq_id flwUpId, ' . $isMycall . ' AS isMycall, ctt.ctt_id AS cttId, ctt.ctt_name AS contactName,
							scq_follow_up_queue_type AS followUpTypeId, scq_disposition_comments AS  flwRemarks,
							scq_related_bkg_id AS followUpRefId, teams.tea_id AS teamId, scq_additional_param,
							teams.tea_name AS teamName, 
							CASE
									' . $platformString . '
							END  AS dataSource,
							CASE
								WHEN scq_follow_up_queue_type = 1 THEN "New Booking"
								WHEN scq_follow_up_queue_type = 2 THEN "Existing Booking"
								WHEN scq_follow_up_queue_type = 3 THEN "New Vendor Attachment"
								WHEN scq_follow_up_queue_type = 4 THEN "Vendor Support"
								WHEN scq_follow_up_queue_type = 5 THEN "Customers Advocacy"
								WHEN scq_follow_up_queue_type = 6 THEN "Driver Support/Line"
								WHEN scq_follow_up_queue_type = 7 THEN "Payment Followup"
								WHEN scq_follow_up_queue_type = 9 THEN "Service Requests"
								WHEN scq_follow_up_queue_type = 11 THEN "Penality Dispute"
								WHEN scq_follow_up_queue_type = 10 THEN "SOS"
								WHEN scq_follow_up_queue_type = 12 THEN "UpSell(CNG/Value)"
								WHEN scq_follow_up_queue_type = 13 THEN "Vendor Advocacy"
								WHEN scq_follow_up_queue_type = 14 THEN "Dispatch"
								WHEN scq_follow_up_queue_type = 15 THEN "Vendor Approval"
								WHEN scq_follow_up_queue_type = 16 THEN "New Lead Booking"
								WHEN scq_follow_up_queue_type = 17 THEN "New Quote Booking"
								WHEN scq_follow_up_queue_type = 18 THEN "B2B Post Pickup"
								WHEN scq_follow_up_queue_type = 19 THEN "Booking At Risk(Bar)"
								WHEN scq_follow_up_queue_type = 20 THEN "New Lead Booking(International)"
								WHEN scq_follow_up_queue_type = 21 THEN "New Quote Booking(International)"
								WHEN scq_follow_up_queue_type = 22 THEN "FBG"
								WHEN scq_follow_up_queue_type = 23 THEN "Vendor Payment Request"
								WHEN scq_follow_up_queue_type = 24 THEN "Upsell(Value+/Select)"
								WHEN scq_follow_up_queue_type = 25 THEN "Booking Complete Review"
								WHEN scq_follow_up_queue_type = 26 THEN "Apps Help & Tech support"
								WHEN scq_follow_up_queue_type = 27 THEN "Gozo Now"
								WHEN scq_follow_up_queue_type = 29 THEN "Auto Lead Followup"
								WHEN scq_follow_up_queue_type = 30 THEN "Document Approval"
								WHEN scq_follow_up_queue_type = 31 THEN "Vendor Approval  Zone Based Inventory"
								WHEN scq_follow_up_queue_type = 32 THEN "Critical and stress (risk) assignments(CSA)"
								WHEN scq_follow_up_queue_type = 33 THEN	"Airport DailyRental"
								WHEN scq_follow_up_queue_type = 34 THEN	"Last Min Booking"
								WHEN scq_follow_up_queue_type = 35 THEN	"Price High"
								WHEN scq_follow_up_queue_type = 36 THEN	"Driver NoShow"
								WHEN scq_follow_up_queue_type = 37 THEN	"Customer NoShow"
								WHEN scq_follow_up_queue_type = 38 THEN	"MMT Support"
								WHEN scq_follow_up_queue_type = 39 THEN	"Driver Car BreakDown"
								WHEN scq_follow_up_queue_type = 40 THEN	"Vendor Assign"
								WHEN scq_follow_up_queue_type = 41 THEN	"Cusomer Booking Cancel"
								WHEN scq_follow_up_queue_type = 42 THEN	"Spice Lead Booking"
								WHEN scq_follow_up_queue_type = 43 THEN	"Spice Quote Booking"
								WHEN scq_follow_up_queue_type = 44 THEN	"Spice Lead Booking International"
								WHEN scq_follow_up_queue_type = 45 THEN	"Spice Quote Booking International"
								WHEN scq_follow_up_queue_type = 46 THEN	"Vendor Due Amount"
								WHEN scq_follow_up_queue_type = 51 THEN	"Booking Reschedule"
								WHEN scq_follow_up_queue_type = 53 THEN	"VIP/VVIP Booking"
								
							END AS followUpType,
							CASE
								WHEN scq_to_be_followed_up_with_entity_type = 1 THEN "Customer"
								WHEN scq_to_be_followed_up_with_entity_type = 2 THEN "Vendor"
								WHEN scq_to_be_followed_up_with_entity_type = 3 THEN "Driver"
								WHEN scq_to_be_followed_up_with_entity_type = 5 THEN "Agent"
								WHEN scq_to_be_followed_up_with_entity_type = 6 THEN "Corporate"
							END AS callerType,
							scq_creation_comments AS callerQuery, scq_assigned_uid AS csrId,
							gozen AS csrName, scq_follow_up_date_time AS flwPreferedTime,
							scq_status, scq_ref_type,
							CASE
								WHEN scq_status = 0 THEN "Inactive"
								WHEN scq_status = 1 THEN "Active"
								WHEN scq_status = 2 THEN "Closed"
								WHEN scq_status = 3 THEN "Partial Closed"
							END AS followUpStatus, ctp.* ';
		$qryCount	 = 'SELECT count(1) ';
		$qryFrom	 = ' FROM   `service_call_queue`';
		$qryJoin	 = ' LEFT JOIN contact ctt ON ctt.ctt_id = scq_to_be_followed_up_with_contact	AND ctt.ctt_active = 1
						LEFT JOIN contact_profile ctp ON ctt.ctt_id = ctp.cr_contact_id	AND ctp.cr_status = 1
						LEFT JOIN teams  ON teams.tea_id = scq_to_be_followed_up_by_id AND scq_to_be_followed_up_by_type=1 AND teams.tea_status = 1
						LEFT JOIN admins adm ON adm.adm_id = scq_assigned_uid';

		$qryWhere = ' WHERE  1 AND scq_active=1 ';
		if ($fwpId > 0)
		{
			$qryWhere .= " AND scq_id = $fwpId";
		}
		if ($isMycall > 0)
		{
			$qryWhere .= " AND ( scq_assigned_uid = " . $userId . ") AND scq_status IN  (1,3)";
		}

		if ($refId > 0)
		{
			$qryWhere .= " AND ( scq_related_bkg_id = " . $refId . " OR scq_related_lead_id = " . $refId . "  )";
		}
		if ($contactId > 0 && $isMycall > 0)
		{
			$qryWhere .= " OR (( scq_to_be_followed_up_with_contact = " . $contactId . " ) AND scq_status=2 AND scq_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 2 MONTH), ' 00:00:00') AND NOW())";
		}


		$sql			 = $qrySelect . $qryFrom . $qryJoin . $qryWhere;
		$getCount		 = $qryCount . $qryFrom . $qryWhere;
		$count			 = DBUtil::queryScalar($getCount, $isMycall ? DBUtil::MDB() : DBUtil::SDB());
		$dataprovider	 = new CSqlDataProvider($sql, array(
			"totalItemCount" => $count,
			'db'			 => $isMycall ? DBUtil::MDB() : DBUtil::SDB(),
			"pagination"	 => array("pageSize" => 50),
			'sort'			 => array('defaultOrder' => 'scqActive DESC,refOrder DESC,scq_id DESC')
		));
		return $dataprovider;
	}

	public static function updateState($refId, $csr, $statusId, $remarks = null, $reFollowup = 0)
	{
		return self::updateStatus($refId, $csr, $statusId, $remarks, $reFollowup);
	}

	public function detail($scq_id)
	{
		$sql = "SELECT * FROM service_call_queue WHERE scq_id =:scq_id AND scq_active=1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), ['scq_id' => $scq_id]);
	}

	/**
	 * This function is used for updating the followup status
	 * @param type $refId
	 * @param type $csr
	 * @param type $statusId
	 * @param type $remarks
	 * @return type
	 */
	public static function updateStatus($refId, $csr, $statusId, $remarks = null, $reFollowup = 0)
	{
		$where	 = "";
		$active	 = "";
		$team	 = null;
		$model	 = ServiceCallQueue::model()->findByPk($refId);
		if ($statusId == 4)
		{
			$scq_prev_or_originating_followup	 = $model->scq_prev_or_originating_followup;
			$scq_prev_or_originating_followup	 = ($scq_prev_or_originating_followup != null && $scq_prev_or_originating_followup > 0) ? $scq_prev_or_originating_followup : $refId;
			$statusId							 = $reFollowup == 0 ? 2 : 3;
		}
		if ($model->scq_assigned_uid == null)
		{
			$where .= " scq_assigned_uid=:csr,scq_assigned_date_time=NOW(),scq_disposition_date = NOW(), ";
		}
		else
		{
			$where .= "  scq_disposition_date = NOW(), ";
		}
		if ($statusId == 0)
		{
			$active = " scq_active=0, ";
		}
		if ($statusId == 2)
		{
			$teamId = Teams::getMultipleTeamid($csr);
			foreach ($teamId as $teamId)
			{
				$team = $teamId['tea_id'];
				break;
			}
		}
		$sql = "UPDATE service_call_queue SET scq_disposed_by_uid =:csr,
                scq_status = :statusId,
                scq_time_to_close=TIMESTAMPDIFF(MINUTE, scq_assigned_date_time,NOW()),
                scq_disposed_team_id=:team,
                $active
                $where
                scq_disposition_comments = :remarks
                WHERE scq_id  =:refId";
		return DBUtil::execute($sql, ['refId' => $refId, 'csr' => $csr, 'statusId' => $statusId, 'remarks' => $remarks, 'team' => $team]);
	}

	/**
	 * This function is used for showing data for call back request list
	 * @param type $bkg
	 * @return type  array
	 */
	public static function getfollowUpsByBkg($bkg)
	{
		$type	 = '';
		$typeArr = UserInfo::getEntityList();
		foreach ($typeArr as $p => $q)
		{
			$type .= 'WHEN scq_to_be_followed_up_with_entity_type = ' . $p . ' THEN "' . $q . '" ';
		}
		$params	 = ['scq_related_bkg_id' => $bkg];
		$sql	 = "SELECT
					CASE
						WHEN scq_created_by_type = 1 THEN IFNULL(usr_name, '')
						WHEN scq_created_by_type = 2 THEN IFNULL(usr_name, '')
						WHEN scq_created_by_type = 3 THEN IFNULL(usr_name, '')
						WHEN scq_created_by_type = 4 THEN IFNULL(adm1.adm_fname, '')
						WHEN scq_created_by_type = 5 THEN IFNULL(agt_fname, '')
						WHEN scq_created_by_type = 10 THEN 'System'
					END AS adm_fname,

					CASE
						WHEN scq_created_by_type = 1 THEN IFNULL(usr_lname, '')
						WHEN scq_created_by_type = 2 THEN IFNULL(usr_lname, '')
						WHEN scq_created_by_type = 3 THEN IFNULL(usr_lname, '')
						WHEN scq_created_by_type = 4 THEN IFNULL(adm1.adm_lname, '')
						WHEN scq_created_by_type = 5 THEN IFNULL(agt_lname, '')
						WHEN scq_created_by_type = 10 THEN ''
					END AS adm_lname,
                    CONCAT(IFNULL(admc.adm_fname, ''), ' ', IFNULL(admc.adm_lname, '')) as closed_by,
					scq_id,
					tea_id,
					if(scq_to_be_followed_up_by_type=2,adm.gozen,tea_name) as tea_name,
					DATE(scq_follow_up_date_time) as fwpPrefdt,
					TIME(scq_follow_up_date_time) as fwpPreftm ,
					scq_creation_comments,
					CASE " . $type . " END as followupWith,
					scq_status,
					scq_disposition_comments,
					scq_creation_comments,
                    scq_to_be_followed_up_with_value,
                    scq_assigned_uid,
                    adm1.adm_id as created_adm_id,
                    scq_create_date,
                    scq_disposition_date
					FROM service_call_queue
					LEFT  JOIN  teams ON tea_id  = scq_to_be_followed_up_by_id and scq_to_be_followed_up_by_type=1
					LEFT  JOIN  admins adm ON adm.adm_id  = scq_to_be_followed_up_by_id and scq_to_be_followed_up_by_type=2
					LEFT JOIN   admins adm1  ON adm1.adm_id  = scq_created_by_uid AND  scq_created_by_type=4
                    LEFT JOIN   admins admc  ON admc.adm_id  = scq_disposed_by_uid
					LEFT JOIN   users ON user_id  = scq_created_by_uid AND  scq_created_by_type IN (1,2,3)
					LEFT JOIN   agents ON agt_id   = scq_created_by_uid AND  scq_created_by_type =5
					WHERE scq_related_bkg_id=:scq_related_bkg_id AND scq_active=1 ";
		return DBUtil::queryAll($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for adding data for admin panel
	 * @param type $bkgmodel
	 * @param type $followupDtTime
	 * @param type $refType
	 * @param type $parentId
	 * @param type  $desc
	 * @return type  int
	 */
	public static function add_v1($bkgmodel, $followupDtTime, $refType, $parentId = 0, $desc = "")
	{
		$returnSet										 = new ReturnSet();
		$model											 = new ServiceCallQueue();
		$model->contactRequired							 = 0;
		$model->scq_created_by_type						 = UserInfo::getUserType();
		$model->scq_created_by_uid						 = UserInfo::getUserId();
		$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_CONSUMER;
		$model->scq_to_be_followed_up_with_entity_id	 = $bkgmodel->bkgUserInfo->bkg_user_id > 0 ? $bkgmodel->bkgUserInfo->bkg_user_id : 0;
		$model->scq_to_be_followed_up_with_entity_rating = -1;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = $bkgmodel->mycallPage == 1 ? 5 : Teams::getTeamIdFromCached($refType);
		$model->scq_to_be_followed_up_with_type			 = 2;
		$model->scq_to_be_followed_up_with_value		 = $bkgmodel->bkgUserInfo->bkg_contact_no;
		$model->scq_to_be_followed_up_with_contact		 = $bkgmodel->bkgUserInfo->bkg_contact_id;
		$model->scq_creation_comments					 = $desc;
		if ($followupDtTime != null)
		{
			$model->scq_follow_up_date_time = $followupDtTime;
		}
		if ($parentId != null)
		{
			$model->scq_prev_or_originating_followup = $parentId;
		}
		$model->scq_related_bkg_id		 = $bkgmodel->bkg_id;
		$model->scq_ref_type			 = 2;
		$model->scq_follow_up_queue_type = $bkgmodel->mycallPage == 1 ? 7 : $refType;
		$model->scq_status				 = 1;
		$model->subQueue				 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
		$returnSet						 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		return $returnSet;
	}

	/**
	 * This function is used for getting priority level for call back request
	 * @param type $queueType
	 * @param type $bookingId
	 * @return type  int
	 */
	public static function getServiceCallPriority($serviceModel)
	{
		if ($serviceModel->followUpTimeOpt == 1)
		{
			$priorityLevel = 4;
			goto skipCheck;
		}
		switch ($serviceModel->scq_follow_up_queue_type)
		{
			case ServiceCallQueue::TYPE_NEW_BOOKING:
				$priorityLevel	 = ServiceCallQueue::getSubServiceCallPriority($serviceModel->subQueue);
				break;
			case ServiceCallQueue::TYPE_NEW_LEAD_BOOKING:
			case ServiceCallQueue::TYPE_NEW_QUOTE_BOOKING:
			case ServiceCallQueue::TYPE_NEW_LEAD_BOOKING_INTERNATIONAL:
			case ServiceCallQueue::TYPE_NEW_QUOTE_BOOKING_INTERNATIONAL:
				$priorityLevel	 = 3;
				break;

			case ServiceCallQueue::TYPE_VVIP_BOOKING:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_EXISTING_BOOKING:
				$model			 = Booking::model()->findByPk($serviceModel->scq_related_bkg_id);
				$hour			 = (DBUtil::getTimeDiff($model->bkg_pickup_date, DBUtil::getCurrentTime()) / 60);
				if (($model->bkg_status == 2 || $model->bkg_status == 3 ) && $hour < 12)
				{
					$priorityLevel = 4;
				}
				else if ($model->bkgTrack->bkg_ride_start == 1 && $model->bkgTrack->bkg_ride_complete == 0)
				{
					$priorityLevel = 5;
				}
				else
				{
					$priorityLevel = 3;
				}
				break;

			case ServiceCallQueue::TYPE_NEW_VENDOR_ATTACHMENT:
				$priorityLevel = 3;
				break;

			case ServiceCallQueue::TYPE_EXISTING_VENDOR:
				$model	 = Booking::model()->findByPk($bookingId);
				$hour	 = (DBUtil::getTimeDiff($model->bkg_pickup_date, DBUtil::getCurrentTime()) / 60);
				if (($model->bkg_status == 2 || $model->bkg_status == 3 ) && $hour < 12)
				{
					$priorityLevel = 4;
				}
				else if ($model->bkgTrack->bkg_ride_start == 1 && $model->bkgTrack->bkg_ride_complete == 0)
				{
					$priorityLevel = 4;
				}
				else
				{
					$priorityLevel = 3;
				}
				break;

			case ServiceCallQueue::TYPE_ADVOCACY:
				$priorityLevel = 3;
				break;

			case ServiceCallQueue::TYPE_SOS:
				$priorityLevel = 5;
				break;

			case ServiceCallQueue::TYPE_DRIVER:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_PENALITY_DISPUTE:
				$priorityLevel	 = 1;
				break;
			case ServiceCallQueue::TYPE_UPSELL_UPPERTIER:
			case ServiceCallQueue::TYPE_UPSELL:
				$priorityLevel	 = 1;
				break;
			case ServiceCallQueue::TYPE_DISPATCH:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_VENDOR_APPROVAl:
				$priorityLevel	 = 3;
				break;
			case ServiceCallQueue::TYPE_B2B_POST_PICKUP:
				$priorityLevel	 = 1;
				break;
			case ServiceCallQueue::TYPE_BAR:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_CSA:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_FBG:
				$priorityLevel	 = 3;
				break;
			case ServiceCallQueue::TYPE_GOZONOW:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_DOCUMENT_APPROVAL:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_LAST_MIN_BOOKING:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_PRICE_HIGH:
				$priorityLevel	 = 3;
				break;
			case ServiceCallQueue::TYPE_DRIVER_CAR_BREAKDOWN:
				$priorityLevel	 = 5;
				break;

			case ServiceCallQueue::TYPE_VENDOR_ASSIGN:
				$priorityLevel	 = 5;
				break;
			case ServiceCallQueue::TYPE_CUSTOMER_BOOKING_CANCEL:
				$priorityLevel	 = 5;
				break;

			default:
				$priorityLevel = 1;
				break;
		}
		skipCheck:
		return $priorityLevel;
	}

	/**
	 * This function is used for getting Sub level for call back request
	 * @param type $queueType
	 * @param type $bookingId
	 * @return type  int
	 */
	public static function getSubServiceCallPriority($subQueue)
	{
		switch ($subQueue)
		{
			case ServiceCallQueue::SUB_FOLLOW_UP_REQUEST:
				$priorityLevel = 4;
				break;

			case ServiceCallQueue::SUB_QUOTE_CREATED_FOLLOWUP:
				$priorityLevel = 3;
				break;

			case ServiceCallQueue::SUB_LEADS:
				$priorityLevel = 1;
				break;

			case ServiceCallQueue::SUB_REFOLLOWUP:
				$priorityLevel = 0;
				break;

			default:
				$priorityLevel = 0;
				break;
		}

		return $priorityLevel;
	}

	/**
	 * This function is used for getting all gozens as in json string
	 * @param type $query
	 * @param type $admuser
	 * @return type Json
	 */
	public function getGozensbyQuery($query, $admuser = '')
	{
		$rows		 = $this->getGozen($query, $admuser);
		$arrCities	 = array();
		foreach ($rows as $row)
		{
			$arrCities[] = array("id" => $row['adm_id'], "text" => $row['gozen']);
		}
		$data = CJSON::encode($arrCities);
		return $data;
	}

	/**
	 * This function is used for getting all gozens list
	 * @param type $query
	 * @param type $admuser
	 * @return type  array
	 */
	public function getGozen($query = '', $admuser = '')
	{
		$qry	 = '';
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');
		if ($admuser != '')
		{
			$qry1 = " AND  adm_id in ($admuser)";
		}
		if ($query != '')
		{

			$qry .= " AND gozen  LIKE $bindString0 ";
		}
		if ($admuser != '')
		{
			$sql = "SELECT adm_id ,gozen,adm_fname FROM admins  WHERE adm_active=1 $qry1";
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$sql = "SELECT adm_id ,gozen,adm_fname, MATCH (adm_fname) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score
		,IF(adm_fname  LIKE $bindString0,1,0) AS startRank	FROM admins  WHERE adm_active=1  $qry $qry1 ORDER BY adm_user ASC LIMIT 0,15 ";

			return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
		}
	}

	/**
	 * This function is used for getting all vendor  json string
	 * @param type $query
	 * @param type $admuser
	 * @return type  json
	 */
	public function getvndsbyQuery($query, $vndId = '')
	{
		$rows	 = $this->getVnds($query, $vndId);
		$arrVnd	 = array();
		foreach ($rows as $row)
		{
			$arrVnd[] = array("id" => $row['vnd_id'], "text" => $row['vnd_name'] . " " . $row['vnd_code']);
		}
		$data = CJSON::encode($arrVnd);
		return $data;
	}

	/**
	 * This function is used for getting all vendor list
	 * @param type $query
	 * @param type $admuser
	 * @return type  array
	 */
	public function getVnds($query = '', $vndId = '')
	{
		$qry	 = '';
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');
		if ($vndId != '')
		{
			$qry1 = " AND  vnd_id   in ($vndId)";
		}
		if ($query != '')
		{

			$qry .= " AND (vnd_name   LIKE $bindString0 OR vnd_code  LIKE $bindString0)";
		}
		if ($vndId != '')
		{
			$sql = "SELECT vnd_id  ,vnd_name,vnd_code  FROM vendors  WHERE vnd_active IN (1,3) $qry1";
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$sql = "SELECT vnd_id  ,vnd_name ,vnd_code, MATCH (vnd_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score
		,IF(vnd_name   LIKE $bindString0,1,0) AS startRank	FROM vendors  WHERE vnd_active IN (1,3) $qry $qry1 ORDER BY vnd_name  ASC LIMIT 0,15 ";

			return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
		}
	}

	/**
	 * This function is used for getting all drivers json string
	 * @param type $query
	 * @param type $admuser
	 * @return type  json
	 */
	public function getdrvsbyQuery($query, $drvId = '')
	{

		$rows	 = $this->getDrvs($query, $drvId);
		$arrdrv	 = array();
		foreach ($rows as $row)
		{
			$arrdrv[] = array("id" => $row['drv_id'], "text" => $row['drv_name'] . " " . $row['drv_code']);
		}
		$data = CJSON::encode($arrdrv);
		return $data;
	}

	/**
	 * This function is used for getting all drivers list string
	 * @param type $query
	 * @param type $admuser
	 * @return type  array
	 */
	public function getDrvs($query = '', $drvId = '')
	{
		$qry	 = '';
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');
		if ($drvId != '')
		{
			$qry1 = " AND  drv_id    in ($drvId)";
		}
		if ($query != '')
		{

			$qry .= " AND (drv_name LIKE $bindString0 OR drv_code  LIKE $bindString0) ";
		}
		if ($drvId != '')
		{
			$sql = "SELECT drv_id  ,drv_name,drv_code   FROM drivers  WHERE drv_active =1 $qry1";
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$sql = "SELECT drv_id   ,drv_name  ,drv_code, MATCH (drv_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score
		,IF(drv_name LIKE $bindString0,1,0) AS startRank	FROM drivers  WHERE drv_active  =1  $qry $qry1 ORDER BY drv_name   ASC LIMIT 0,15 ";

			return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
		}
	}

	/**
	 * This function is used for getting all drivers Json string
	 * @param type $query
	 * @param type $admuser
	 * @return type  Json
	 */
	public function getCustomerbyQuery($query, $admuser = '')
	{

		$rows	 = $this->getCustomer($query, $admuser);
		$arrUser = array();
		foreach ($rows as $row)
		{
			$arrUser[] = array("id" => $row['user_id'], "text" => $row['usr_name'] . " " . $row['usr_lname'] . " " . $row['phn_phone_no']);
		}
		$data = CJSON::encode($arrUser);
		return $data;
	}

	/**
	 * This function is used for getting all drivers list
	 * @param type $query
	 * @param type $admuser
	 * @return type  array
	 */
	public function getCustomer($query = '', $admuser = '')
	{
		$qry	 = '';
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		($query == null || $query == "") ? DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '"') : DBUtil::getLikeStatement($query, $bindString1, $params2, '"', '*"');
		if ($admuser != '')
		{
			$qry1 = " AND  user_id    in ($admuser)";
		}
		if ($query != '')
		{

			$qry .= " AND (usr_name LIKE $bindString0) OR (phn_phone_no LIKE $bindString0) ";
		}
		if ($admuser != '')
		{

			$sql = "SELECT user_id,usr_name,usr_lname,phn_phone_no FROM `users`
                    INNER JOIN contact_profile ON `usr_contact_id` = cr_contact_id
                    INNER JOIN contact ON cr_contact_id = ctt_id
					INNER JOIN contact_phone ON phn_contact_id  = ctt_id AND phn_active =1 AND  (phn_is_verified =1 OR phn_is_primary =1) $qry1";
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$sql = "SELECT user_id,usr_name,usr_lname, MATCH (usr_name) AGAINST ($bindString1 IN BOOLEAN MODE)  AS score
		,IF(usr_name    LIKE $bindString0,1,0) AS startRank,phn_phone_no 	FROM users INNER JOIN contact_profile ON `usr_contact_id` = cr_contact_id INNER JOIN contact ON cr_contact_id= ctt_id INNER JOIN contact_phone ON phn_contact_id  = ctt_id AND phn_active =1 AND  (phn_is_verified =1 OR phn_is_primary =1) WHERE usr_active  =1  $qry $qry1 ORDER BY usr_name   ASC LIMIT 0,500 ";
			return DBUtil::query($sql, DBUtil::SDB(), array_merge($params1, $params2));
		}
	}

	/**
	 * This function is used for processing of  service queue assignment
	 * @param integer $csr
	 * @param integer $teamId
	 * @return Model  of service_call_queue
	 */
	public static function processAssignment($csr, $teamId = 0, $unverifiedAccess = 1, $newAccess = 1, $highValueAccess = 1, $isRetailSalesQueue = 1, $followupQueue = 0, $isMultipleAllowed = 0)
	{
		try
		{

			$scqId	 = self::assignTopQueue($csr, $teamId, $unverifiedAccess, $newAccess, $highValueAccess, $isRetailSalesQueue, $followupQueue, $isMultipleAllowed);
			$model	 = ServiceCallQueue::model()->findByPk($scqId);
			switch ((int) $teamId)
			{
				case 1:
					$refType	 = $model->scq_follow_up_queue_type;
					$callType	 = 1;
					if ($model->scq_related_lead_id != null)
					{
						$refType			 = 1;
						$callType			 = 1;
						BookingTemp::assignLD($model->scq_related_lead_id, $csr);
						$resultLD			 = BookingTemp::model()->getUserbyId($model->scq_related_lead_id);
						$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultLD["bkg_user_id"], $resultLD["email"], $resultLD['bkg_contact_no']);
						$getRelatedLead		 = [];
						$lead				 = "";
						foreach ($getRelatedLeadIds as $leadArr)
						{
							$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id']);
							if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
							{
								$lead						 .= $leadArr['bkg_id'] . ",";
								$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
							}
						}
						BookingTemp::assignedIds($getRelatedLead, $csr, $model->scq_related_lead_id);
						$data = json_encode(array('bookingTempReleated' => rtrim($lead, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}
					else if ($model->scq_related_bkg_id != null)
					{
						$refType			 = 2;
						$callType			 = 2;
						Booking::assignQT($model->scq_related_bkg_id, $csr);
						$resultQT			 = Booking::model()->getUserbyIdNew($model->scq_related_bkg_id);
						$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no']);
						$lead				 = "";
						$getRelatedLead		 = [];
						foreach ($getRelatedLeadIds as $leadArr)
						{
							$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id']);
							if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
							{
								$lead						 .= $leadArr['bkg_id'] . ",";
								$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
							}
						}
						BookingTemp::assignedIds($getRelatedLead, $csr, $model->scq_related_bkg_id);
						$getRelatedQuoteIds	 = Booking::getRelatedIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no']);
						$quote				 = "";
						$getRelatedQuote	 = [];
						foreach ($getRelatedQuoteIds as $quoteArr)
						{
							$quote						 .= $quoteArr['bkg_id'] . ",";
							$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
						}
						Booking::assignedIds($getRelatedQuote, $csr, $refId);
						$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}
					else
					{
						$refType	 = 3;
						$callType	 = $model->scq_follow_up_queue_type;
						$contactId	 = ($model->scq_to_be_followed_up_with_contact == null) ? 0 : $model->scq_to_be_followed_up_with_contact;
						$arrProfile	 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_CONSUMER);
						if (!empty($arrProfile["id"]))
						{
							$userId = $arrProfile['id'];
						}
						$contactEmail	 = ContactEmail::getEmailByBookingUserId($userId);
						$code			 = 91;
						if ($model->scq_to_be_followed_up_with_type == 2)
						{
							$phone = $model->scq_to_be_followed_up_with_value;
						}
						else
						{
							$phone = ContactPhone::getContactNumber($contactId);
						}
						$phone				 = preg_replace('/[^0-9\-]/', '', $phone);
						Filter::parsePhoneNumber($phone, $code, $custUserPhone);
						$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($userId, $contactEmail, $custUserPhone);
						$lead				 = "";
						$getRelatedLead		 = [];
						foreach ($getRelatedLeadIds as $leadArr)
						{
							$lead						 .= $leadArr['bkg_id'] . ",";
							$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
						}
						BookingTemp::assignedIds($getRelatedLead, $csr, $refId);
						$getRelatedQuoteIds	 = Booking::getRelatedIds($userId, $contactEmail, $custUserPhone);
						$quote				 = "";
						$getRelatedQuote	 = [];
						foreach ($getRelatedQuoteIds as $quoteArr)
						{
							$quote						 .= $quoteArr['bkg_id'] . ",";
							$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
						}
						Booking::assignedIds($getRelatedQuote, $csr, $refId);
						$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}

					break;
				case 5:

					if ($model->scq_agent_id > 0)
					{
						$agentId	 = $model->scq_agent_id;
						$refType	 = $model->scq_follow_up_queue_type;
						$callType	 = 1;
						if ($model->scq_related_lead_id != null)
						{
							$refType			 = 1;
							$callType			 = 1;
							BookingTemp::assignLD($model->scq_related_lead_id, $csr);
							$resultLD			 = BookingTemp::model()->getUserbyId($model->scq_related_lead_id, $agentId);
							$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultLD["bkg_user_id"], $resultLD["email"], $resultLD['bkg_contact_no'], $agentId);
							$getRelatedLead		 = [];
							$lead				 = "";
							foreach ($getRelatedLeadIds as $leadArr)
							{
								$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
								if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
								{
									$lead						 .= $leadArr['bkg_id'] . ",";
									$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
								}
							}
							BookingTemp::assignedIds($getRelatedLead, $csr, $model->scq_related_lead_id, $agentId);
							$data = json_encode(array('bookingTempReleated' => rtrim($lead, ",")));
							ServiceCallQueue::updateAdditonalParam($scqId, $data);
						}
						else if ($model->scq_related_bkg_id != null)
						{
							$refType			 = 2;
							$callType			 = 2;
							Booking::assignQT($model->scq_related_bkg_id, $csr);
							$resultQT			 = Booking::model()->getUserbyIdNew($model->scq_related_bkg_id, $agentId);
							$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no'], $agentId);
							$lead				 = "";
							$getRelatedLead		 = [];
							foreach ($getRelatedLeadIds as $leadArr)
							{
								$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
								if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
								{
									$lead						 .= $leadArr['bkg_id'] . ",";
									$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
								}
							}
							BookingTemp::assignedIds($getRelatedLead, $csr, $model->scq_related_bkg_id, $agentId);
							$getRelatedQuoteIds	 = Booking::getRelatedIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no'], $agentId);
							$quote				 = "";
							$getRelatedQuote	 = [];
							foreach ($getRelatedQuoteIds as $quoteArr)
							{
								$quote						 .= $quoteArr['bkg_id'] . ",";
								$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
							}
							Booking::assignedIds($getRelatedQuote, $csr, $refId, $agentId);
							$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
							ServiceCallQueue::updateAdditonalParam($scqId, $data);
						}
						else
						{
							$refType	 = 3;
							$callType	 = $model->scq_follow_up_queue_type;
							$contactId	 = ($model->scq_to_be_followed_up_with_contact == null) ? 0 : $model->scq_to_be_followed_up_with_contact;
							$arrProfile	 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_CONSUMER);
							if (!empty($arrProfile["id"]))
							{
								$userId = $arrProfile['id'];
							}
							$contactEmail	 = ContactEmail::getEmailByBookingUserId($userId);
							$code			 = 91;
							if ($model->scq_to_be_followed_up_with_type == 2)
							{
								$phone = $model->scq_to_be_followed_up_with_value;
							}
							else
							{
								$phone = ContactPhone::getContactNumber($contactId);
							}
							$phone				 = preg_replace('/[^0-9\-]/', '', $phone);
							Filter::parsePhoneNumber($phone, $code, $custUserPhone);
							$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($userId, $contactEmail, $custUserPhone, $agentId);
							$lead				 = "";
							$getRelatedLead		 = [];
							foreach ($getRelatedLeadIds as $leadArr)
							{
								$lead						 .= $leadArr['bkg_id'] . ",";
								$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
							}
							BookingTemp::assignedIds($getRelatedLead, $csr, $refId, $agentId);
							$getRelatedQuoteIds	 = Booking::getRelatedIds($userId, $contactEmail, $custUserPhone, $agentId);
							$quote				 = "";
							$getRelatedQuote	 = [];
							foreach ($getRelatedQuoteIds as $quoteArr)
							{
								$quote						 .= $quoteArr['bkg_id'] . ",";
								$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
							}
							Booking::assignedIds($getRelatedQuote, $csr, $refId, $agentId);
							$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
							ServiceCallQueue::updateAdditonalParam($scqId, $data);
						}
					}
					else
					{
						$refType			 = 3;
						$callType			 = $model->scq_follow_up_queue_type;
						Booking::assignQT($model->scq_related_bkg_id, $csr);
						$resultQT			 = Booking::model()->getUserbyIdNew($model->scq_related_bkg_id);
						$getRelatedExistings = Booking::getRelatedExistings($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no']);
						$lead				 = "";
						$getRelatedArr		 = [];
						foreach ($getRelatedExistings as $ids)
						{
							$lead						 .= $ids['bkg_id'] . ",";
							$getRelatedArr[]['bkg_id']	 = $ids['bkg_id'];
						}
						Booking::assignRelatedExisting($getRelatedArr, $csr, $model->scq_related_bkg_id);
						$data = json_encode(array('booking' => rtrim($lead, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}
					break;
				case 50:
					$agentId	 = $model->scq_agent_id;
					$refType	 = $model->scq_follow_up_queue_type;
					$callType	 = 1;
					if ($model->scq_related_lead_id != null)
					{
						$refType			 = 1;
						$callType			 = 1;
						BookingTemp::assignLD($model->scq_related_lead_id, $csr);
						$resultLD			 = BookingTemp::model()->getUserbyId($model->scq_related_lead_id, $agentId);
						$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultLD["bkg_user_id"], $resultLD["email"], $resultLD['bkg_contact_no'], $agentId);
						$getRelatedLead		 = [];
						$lead				 = "";
						foreach ($getRelatedLeadIds as $leadArr)
						{
							$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
							if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
							{
								$lead						 .= $leadArr['bkg_id'] . ",";
								$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
							}
						}
						BookingTemp::assignedIds($getRelatedLead, $csr, $model->scq_related_lead_id, $agentId);
						$data = json_encode(array('bookingTempReleated' => rtrim($lead, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}
					else if ($model->scq_related_bkg_id != null)
					{
						$refType			 = 2;
						$callType			 = 2;
						Booking::assignQT($model->scq_related_bkg_id, $csr);
						$resultQT			 = Booking::model()->getUserbyIdNew($model->scq_related_bkg_id, $agentId);
						$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no'], $agentId);
						$lead				 = "";
						$getRelatedLead		 = [];
						foreach ($getRelatedLeadIds as $leadArr)
						{
							$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
							if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
							{
								$lead						 .= $leadArr['bkg_id'] . ",";
								$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
							}
						}
						BookingTemp::assignedIds($getRelatedLead, $csr, $model->scq_related_bkg_id, $agentId);
						$getRelatedQuoteIds	 = Booking::getRelatedIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no'], $agentId);
						$quote				 = "";
						$getRelatedQuote	 = [];
						foreach ($getRelatedQuoteIds as $quoteArr)
						{
							$quote						 .= $quoteArr['bkg_id'] . ",";
							$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
						}
						Booking::assignedIds($getRelatedQuote, $csr, $refId, $agentId);
						$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}
					else
					{
						$refType	 = 3;
						$callType	 = $model->scq_follow_up_queue_type;
						$contactId	 = ($model->scq_to_be_followed_up_with_contact == null) ? 0 : $model->scq_to_be_followed_up_with_contact;
						$arrProfile	 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_CONSUMER);
						if (!empty($arrProfile["id"]))
						{
							$userId = $arrProfile['id'];
						}
						$contactEmail	 = ContactEmail::getEmailByBookingUserId($userId);
						$code			 = 91;
						if ($model->scq_to_be_followed_up_with_type == 2)
						{
							$phone = $model->scq_to_be_followed_up_with_value;
						}
						else
						{
							$phone = ContactPhone::getContactNumber($contactId);
						}
						$phone				 = preg_replace('/[^0-9\-]/', '', $phone);
						Filter::parsePhoneNumber($phone, $code, $custUserPhone);
						$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($userId, $contactEmail, $custUserPhone, $agentId);
						$lead				 = "";
						$getRelatedLead		 = [];
						foreach ($getRelatedLeadIds as $leadArr)
						{
							$lead						 .= $leadArr['bkg_id'] . ",";
							$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
						}
						BookingTemp::assignedIds($getRelatedLead, $csr, $refId, $agentId);
						$getRelatedQuoteIds	 = Booking::getRelatedIds($userId, $contactEmail, $custUserPhone, $agentId);
						$quote				 = "";
						$getRelatedQuote	 = [];
						foreach ($getRelatedQuoteIds as $quoteArr)
						{
							$quote						 .= $quoteArr['bkg_id'] . ",";
							$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
						}
						Booking::assignedIds($getRelatedQuote, $csr, $refId, $agentId);
						$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
						ServiceCallQueue::updateAdditonalParam($scqId, $data);
					}


					break;
				case 3:
					$refType	 = 3;
					$callType	 = 5;
					break;
				case 9:
					$refType	 = 3;
					switch ($model->scq_follow_up_queue_type)
					{
						case 4:
							$callType	 = 3;
							break;
						case 3:
							$callType	 = 5;
							break;
						case 11:
							$callType	 = 3;
							break;
						default:
							$callType	 = $model->scq_follow_up_queue_type;
							break;
					}
					break;
				default :
					break;
			}
			CallStatus::model()->addMyCall($model->scq_id, $refType, $callType, 91, $model->scq_to_be_followed_up_with_value, 1);
		}
		catch (Exception $exc)
		{
			throw $exc;
		}
		return $model;
	}

	/**
	 * This function is used for assignment of call back request to csr
	 * @param type $csr
	 * @param type $teamId
	 * @return type  ReturnSet
	 */
	public static function process($csr, $teamId = null, $unverifiedAccess = 1, $newAccess = 1, $highValueAccess = 1, $isRetailSalesQueue = 1, $followupQueue = 0, $isMultipleAllowed = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet = new ReturnSet();
		try
		{
			if (empty($csr))
			{
				throw new Exception("CSR not found", ReturnSet::ERROR_FAILED);
			}

			$scqId		 = self::checkAssignment($csr);
			Logger::info("SCQ ID " . $scqId);
			$model		 = ($scqId > 0 && $isMultipleAllowed == 0 ) ? ServiceCallQueue::model()->findByPk($scqId) : self::processAssignment($csr, $teamId, $unverifiedAccess, $newAccess, $highValueAccess, $isRetailSalesQueue, $followupQueue, $isMultipleAllowed);
			/* @var $response Stub\common\ServiceCall */
			$response	 = new Stub\common\ServiceCall();
			$response->setData($model);
			$returnSet->setData($response);
			$returnSet->setStatus(true);
			$returnSet->setMessage("Leads has been assigned to you");
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		skipAll:
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	/**
	 * This function is used for checking assignment for any csr
	 * @param integer $csr
	 * @return integer  scq_id
	 */
	public static function checkAssignment($csr)
	{
		$scq_id = 0;
		if ($csr == '')
		{
			goto end;
		}
		$params = ['csr' => $csr];

		$sql	 = "SELECT scq_id FROM service_call_queue WHERE scq_assigned_uid=:csr AND scq_status IN (1,3)  AND scq_active=1 ";
		$scq_id	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		end:
		return $scq_id;
	}

	/**
	 * This function is used to get top queue for the particular CSR/Team
	 * @param integer $csr
	 * @param integer $teamId
	 * @return array first row of service_call_queue
	 */
	public static function getTopQueue($csr, $teamId, $isNewBookingEligible = true, $defaultEligibleScore = 80, $isRetailSalesQueue = 1, $followupQueue = 0)
	{
		$discardNewBkgSQL = "";
		if (in_array((int) date('H'), array(21, 22, 23, 0, 1, 2, 3, 4, 5, 6)))
		{
			$isNewBookingEligible = true;
		}

		if (!$isNewBookingEligible)
		{
			$discardNewBkgSQL .= " AND ((TIMESTAMPDIFF(MINUTE,scq_create_date,NOW())>=5 AND scq_follow_up_queue_type=1 
											AND ($defaultEligibleScore + TIMESTAMPDIFF(MINUTE, scq_create_date, NOW()))>110) 
										OR (scq_follow_up_queue_type NOT IN (1,16,17)) 
										OR (scq_follow_up_queue_type IN (16,17) AND scq_priority_score<=($defaultEligibleScore + TIMESTAMPDIFF(MINUTE, scq_create_date, NOW())))) ";
		}
		if ($isRetailSalesQueue == 0)
		{
			$discardNewBkgSQL .= " AND (scq_priority_score<=100 AND scq_follow_up_queue_type IN (16,17,20,21,34)) ";
		}
		$model	 = AdminProfiles::model()->getByAdminID($csr);
		$where	 = ($followupQueue != 0 && $model->adp_auto_allocated == 0) ? " AND ( scq_follow_up_queue_type IN ($followupQueue)) " : " AND ( (  scq_follow_up_queue_type<>9)   OR  ( scq_to_be_followed_up_by_type = 1  AND scq_to_be_followed_up_by_id = :team)	)	";
		$where1	 = ($followupQueue != 0 && $model->adp_auto_allocated == 0) ? " AND ( scq_follow_up_queue_type IN ($followupQueue)) " : "";
		$params	 = ['team' => $teamId, 'csr' => $csr];
		$sql	 = "SELECT * FROM
					(
						 (
							SELECT 'Queue' AS type, 2 AS typeId,IF(scq_preferred_csr>0 && scq_preferred_csr=:csr,1,0) AS preferredCsrFlag, service_call_queue.*
							FROM service_call_queue
							INNER JOIN team_queue_mapping tqm ON tqm.tqm_queue_id = scq_follow_up_queue_type AND tqm.tqm_active=1 AND (tqm.tqm_tea_id=:team)
							LEFT JOIN booking_trail ON btr_bkg_id=scq_related_bkg_id AND scq_follow_up_queue_type=7 AND bkg_create_user_type=4
							WHERE  1
							AND scq_status IN (1, 3)
							AND scq_active=1
							AND scq_follow_up_date_time IS NOT NULL
							AND scq_assigned_uid IS NULL							
							AND scq_follow_up_date_time <= DATE_ADD(NOW(), INTERVAL IF(scq_follow_up_queue_type<>7 OR (scq_follow_up_queue_type=7 AND btr_bkg_id IS NOT NULL AND bkg_create_user_id=:csr), 0, -15) MINUTE)
							$where
							AND scq_create_date <= DATE_ADD(NOW(), INTERVAL IF( (scq_preferred_csr>0 AND scq_preferred_csr=:csr) OR (scq_preferred_csr=0),0,-15) MINUTE)
							$discardNewBkgSQL
							ORDER BY tqm.tqm_priority ASC, tqm.tqm_queue_weight DESC,preferredCsrFlag DESC, scq_follow_up_priority DESC,
								scq_priority_score DESC, scq_follow_up_date_time ASC, scq_create_date ASC
							LIMIT 0, 1
						 )
						 UNION
						 (
							SELECT 'CSR' AS type,1 AS typeId,IF(scq_preferred_csr>0 && scq_preferred_csr=:csr,1,0) AS preferredCsrFlag, service_call_queue.*
							FROM service_call_queue
							WHERE     1
							AND scq_status IN (1, 3)
							AND scq_active=1
							AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL
							AND scq_to_be_followed_up_by_type = 2 AND scq_to_be_followed_up_by_id = :csr
							AND scq_assigned_uid IS NULL
							$where1
							AND scq_create_date <= DATE_ADD(NOW(), INTERVAL IF( (scq_preferred_csr>0 AND scq_preferred_csr=:csr) OR (scq_preferred_csr=0),0,-15) MINUTE)
							ORDER BY  preferredCsrFlag DESC,scq_follow_up_priority DESC, scq_priority_score DESC,scq_follow_up_date_time ASC, scq_create_date ASC
							LIMIT 0, 1
						 )
						 UNION
						 (
							SELECT 'TEAM' AS type, 3 AS typeId,IF(scq_preferred_csr>0 && scq_preferred_csr=:csr,1,0) AS preferredCsrFlag, service_call_queue.*
							FROM service_call_queue
							LEFT JOIN booking_trail ON btr_bkg_id=scq_related_bkg_id AND scq_follow_up_queue_type=7 AND bkg_create_user_type=4
							WHERE     1
							AND scq_status IN (1, 3)
							AND scq_active=1
							AND scq_follow_up_date_time <= DATE_ADD(NOW(), INTERVAL IF(scq_follow_up_queue_type<>7 OR (scq_follow_up_queue_type=7 AND btr_bkg_id IS NOT NULL AND bkg_create_user_id=:csr), 0, -15) MINUTE)
							AND scq_follow_up_date_time IS NOT NULL
							AND scq_to_be_followed_up_by_type = 1 AND scq_to_be_followed_up_by_id = :team
							AND scq_follow_up_queue_type = 9
							AND scq_assigned_uid IS NULL
							AND scq_create_date <= DATE_ADD(NOW(), INTERVAL IF( (scq_preferred_csr>0 AND scq_preferred_csr=:csr) OR (scq_preferred_csr=0),0,-15) MINUTE)
							$discardNewBkgSQL
							$where1
							ORDER BY  preferredCsrFlag DESC,scq_follow_up_priority DESC, scq_priority_score DESC, scq_follow_up_date_time ASC, scq_create_date ASC
							LIMIT 0, 1
						 )
					 ) TEMP
					 ORDER BY typeId ASC,preferredCsrFlag DESC, scq_follow_up_priority DESC, scq_priority_score DESC, scq_follow_up_date_time ASC,
						 scq_create_date ASC
					 LIMIT 0, 1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for assigning top  service call queue
	 * @param integer $csr
	 * @param integer $teamId
	 * @return integer scq_id
	 */
	public static function assignTopQueue($csr, $teamId, $unverifiedAccess = 1, $newAccess = 1, $highValueAccess = 1, $isRetailSalesQueue = 1, $followupQueue = 0, $isMultipleAllowed = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$row		 = array();
		$isBarFlag	 = 0;
		$isCsaFlag	 = 0;
		$adminid	 = 0;
		$bkid		 = 0;

		$eligibleScore = "105";
		if ($unverifiedAccess == 0)
		{
			$eligibleScore = "105";
		}

		if ($newAccess == 0)
		{
			$eligibleScore = "85";
		}
		$isEligibleForNewLead	 = self::checkCSRLeadEligibility($csr);
		$isEligibleForNewLead	 = ($isEligibleForNewLead && ($unverifiedAccess == 1 && $newAccess == 1));

		if (self::getLeadCount($isEligibleForNewLead, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed') && ServiceCallQueue::isAllowedLead($teamId) > 0)
		{
			self::updatePendingLeads($csr, 1, $unverifiedAccess, $newAccess, $highValueAccess);
		}
		// for BAR Booking
		if (ServiceCallQueue::isAllowedBAR($teamId) > 0)
		{
			$type				 = $teamId == 4 ? 0 : 1;
			$region				 = Admins::getRegionId($csr);
			$serveBookingType	 = Admins::getAdminsServeBookingType($csr);
			$data				 = self::getDataForBARQueue($type, $region, $serveBookingType);
			$count				 = self::canCBRAdded('19,33', $data['bkg_id']);
			if ($data && $count == 0)
			{
				$data['desc']	 = "Manual action needed. Dispatch team should escalate to field if you need their help. Booking will auto cancel of vendor not assigned in time";
				$returnSet		 = self::addBARQueue($data, $csr, 0);
				if ($returnSet->getStatus())
				{
					$row['scq_id']	 = $returnSet->getData()['followupId'];
					$isBarFlag		 = 1;
					$adminid		 = $csr;
					$bkid			 = $data['bkg_id'];
				}
			}
		}

		// for DTM Booking
		if (ServiceCallQueue::isAllowedCSA($teamId) > 0)
		{
			$region				 = Admins::getRegionId($csr);
			$serveBookingType	 = Admins::getAdminsServeBookingType($csr);
			$data				 = ServiceCallQueue::getCSAdData($region, $serveBookingType);
			if ($data)
			{
				$data['Controller']	 = "Lead Controller";
				$data['desc']		 = "Dispatch team had escalate to field Operations for your help. Booking will auto cancel of vendor not assigned in time";
				$returnSet			 = ServiceCallQueue::addCSAQueue($data, $teamId, 0);
				if ($returnSet->getStatus())
				{
					$isCsaFlag		 = 1;
					$row['scq_id']	 = $returnSet->getData()['followupId'];
					$adminid		 = $csr;
					$bkid			 = $data['bkg_id'];
				}
			}
		}

		// for Followup Dispatch  Booking
		if (ServiceCallQueue::isAllowedFollowupDispatch($teamId) > 0 && ServiceCallQueue::getFollowupDispatchCount() < (int) Config::get('SCQ.maxDisPatchAllowed'))
		{
			$result = ServiceCallQueue::getFollowupDispatch((int) Config::get('SCQ.maxDisPatchAllowed'));
			foreach ($result as $rows)
			{
				try
				{
					ServiceCallQueue::autoFURDriverLate($rows['bkg_id']);
				}
				catch (Exception $ex)
				{
					Logger::exception($ex->getMessage());
				}
			}
		}

		if (empty($row))
		{
			$row = self::getTopQueue($csr, $teamId, $isEligibleForNewLead, $eligibleScore, $isRetailSalesQueue, $followupQueue);
		}

		if (empty($row) || $row['scq_id'] == null)
		{
			Logger::info("isEligibleForNewLead:  $isEligibleForNewLead, CSR: $csr, Team: $teamId , Data: " . json_encode($row));
			throw new Exception("Sorry no leads are avaliable for you.", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$count		 = self::getAssignmentCount($csr, $teamId, $isEligibleForNewLead);
		$returnCount = self::assign($row['scq_id'], $csr, $count, $isMultipleAllowed);
		if ($returnCount > 0)
		{
			self::assignTeam($row['scq_id'], $teamId);
		}

		if ($returnCount && $isBarFlag == 1)
		{
			$bookingmodel								 = Booking::model()->findByPk($bkid);
			$bookingmodel->bkgPref->bpr_assignment_level = 1;
			$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;
			if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
			{
				$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
			}
			$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
			if ($bookingmodel->bkgPref->save())
			{
				$admin	 = Admins::model()->findByPk($adminid);
				$aname	 = $admin->adm_fname;
				$desc	 = "CSR (" . $aname . ") Allocated By " . $aname . " (Auto)";
				BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog :: CSR_ALLOCATE, false, false);
			}
			$type				 = $teamId == 4 ? 0 : 1;
			$bookingmodelDetails = Booking::getBookingByCityZoneWise($bookingmodel->bkg_from_city_id, $type);
			foreach ($bookingmodelDetails as $bookingRow)
			{
				$count = self::canCBRAdded('19,33', $bookingRow['bkg_id']);
				if ($count == 0)
				{
					$bookingRow['desc']	 = " Related Manual action needed. Dispatch team should escalate to field if you need their help. Booking will auto cancel of vendor not assigned in time";
					$returnSet			 = self::addBARQueue($bookingRow, $csr, 0);
					if ($returnSet->getStatus())
					{
						$bookingmodel								 = Booking::model()->findByPk($bookingRow['bkg_id']);
						$bookingmodel->bkgPref->bpr_assignment_level = 1;
						$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;
						if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
						{
							$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
						}
						$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
						if ($bookingmodel->bkgPref->save())
						{
							$admin	 = Admins::model()->findByPk($adminid);
							$aname	 = $admin->adm_fname;
							$desc	 = "CSR (" . $aname . ") Allocated By " . $aname . " (Auto)";
							BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog :: CSR_ALLOCATE, false, false);
						}
						$scqId		 = $returnSet->getData()['followupId'];
						$count		 = self::getAssignmentCount($csr, $teamId, $isEligibleForNewLead);
						$returnCount = self::assign($scqId, $csr, $count, 1);
						if ($returnCount > 0)
						{
							self::assignTeam($scqId, $teamId);
						}
					}
				}
			}


			// get booking from above booking zone wise
			$bookingDestinationDetails	 = Booking::getBookingDestinationZoneByCityZoneWise($bookingmodel->bkg_from_city_id, $type);
			$toZones					 = $bookingDestinationDetails['toZones'];
			$fromZones					 = $bookingDestinationDetails['fromZones'];
			if ($toZones != null && $fromZones != null)
			{
				$bookingmodelDetails = Booking::getBookingSourceZoneWise($fromZones, $toZones, $type);
				foreach ($bookingmodelDetails as $bookingRow)
				{
					$count = self::canCBRAdded('19,33', $bookingRow['bkg_id']);
					if ($count == 0)
					{
						$bookingRow['desc']	 = " Related Manual action needed. Dispatch team should escalate to field if you need their help. Booking will auto cancel of vendor not assigned in time";
						$returnSet			 = self::addBARQueue($bookingRow, $csr, 0);
						if ($returnSet->getStatus())
						{
							$bookingmodel								 = Booking::model()->findByPk($bookingRow['bkg_id']);
							$bookingmodel->bkgPref->bpr_assignment_level = 1;
							$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;
							if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
							{
								$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
							}
							$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
							if ($bookingmodel->bkgPref->save())
							{
								$admin	 = Admins::model()->findByPk($adminid);
								$aname	 = $admin->adm_fname;
								$desc	 = "CSR (" . $aname . ") Allocated By " . $aname . " (Auto)";
								BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog :: CSR_ALLOCATE, false, false);
							}
							$scqId		 = $returnSet->getData()['followupId'];
							$count		 = self::getAssignmentCount($csr, $teamId, $isEligibleForNewLead);
							$returnCount = self::assign($scqId, $csr, $count, 1);
							if ($returnCount > 0)
							{
								self::assignTeam($scqId, $teamId);
							}
						}
					}
				}
			}
		}

		if ($returnCount && $isCsaFlag == 1)
		{
			$bookingmodel								 = Booking::model()->findByPk($bkid);
			$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;
			$bookingmodel->bkgPref->bpr_assignment_level = 2;
			if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
			{
				$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
			}
			$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
			if ($bookingmodel->bkgPref->save())
			{
				$admin	 = Admins::model()->findByPk($adminid);
				$aname	 = $admin->adm_fname;
				$desc	 = "CSR (" . $aname . ") Allocated By " . $aname . " (Auto)";
				;
				BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog :: CSR_ALLOCATE, false, false);
			}
		}

		$isMultiQueueAllowed = Config::get('SCQ.isMultiQueueAllowed');
		if ($returnCount && $isMultiQueueAllowed)
		{
			$teamIds = Teams::getMultipleTeamid($csr);
			foreach ($teamIds as $teamId)
			{
				self::assignMutipleQueue($row['scq_id'], $teamId);
			}
		}

		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $row['scq_id'];
	}

	/**
	 * This function is used for assignment of  service call queue to particular csr
	 * @param integer $csr
	 * @param integer $teamId
	 * @param integer $count
	 * @return integer scq_id
	 */
	private static function assign($scqId, $csr, $count, $flag = 0)
	{
		$id = ServiceCallQueue::checkAssignment($csr);
		if ($id > 0 && $flag == 0)
		{
			throw new Exception("Failed to assign queue id: {$id }", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		else
		{
			$sql	 = "UPDATE service_call_queue SET scq_assigned_uid =:csr,scq_assignment_count=:scq_assignment_count,scq_assigned_date_time = NOW(),scq_time_to_assign=TIMESTAMPDIFF(MINUTE, scq_follow_up_date_time,NOW()) WHERE scq_id =:scq_id AND scq_active=1";
			$numrows = DBUtil::execute($sql, ['csr' => $csr, 'scq_id' => $scqId, 'scq_assignment_count' => $count]);
			if ($numrows == 0)
			{

				throw new Exception("Failed to assign queue ids => {$scqId }", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			return ($numrows > 0);
		}
	}

	/**
	 * This function is used for fetching  assign call back for particular  csr
	 * @param type $csr
	 * @return type  array
	 */
	public static function fetchAssignLeads($csr)
	{
		$params	 = ['csr' => $csr];
		$sql	 = "SELECT *  FROM service_call_queue WHERE 1 AND scq_status IN (1,3)  AND  scq_assigned_uid =:csr AND scq_active=1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for fetching booking  or general followup remarks
	 * @return type  int
	 */
	public function capture($requestdt, $ex_scqId = NULL)
	{
		$mmtNumber		 = Yii::app()->params['scqToCustomerforMMT'];
		$model			 = new ServiceCallQueue();
		$success		 = false;
		$post			 = $requestdt->getPost('ServiceCallQueue');
		$bookingId		 = $post['scq_related_bkg_id'];
		$scqType		 = $post['scqType'];
		$entityType		 = 0;
		$entityId		 = 0;
		$followupPerson	 = $post['followupPerson'];
		$vnd_id			 = $requestdt->getPost('Vendors')['vnd_id'];
		$agt_id			 = $requestdt->getPost('Agents')['agt_id'];
		$drv_id			 = $requestdt->getPost('Drivers')['drv_id'];
		$usr_id			 = $requestdt->getPost('Users')['user_id'];
		if ($vnd_id != "")
		{
			$entityType	 = UserInfo::TYPE_VENDOR;
			$entityId	 = $vnd_id;
		}
		if ($agt_id != "")
		{
			$entityType	 = UserInfo::TYPE_AGENT;
			$entityId	 = $agt_id;
		}
		if ($drv_id != "")
		{
			$entityType	 = UserInfo::TYPE_DRIVER;
			$entityId	 = $drv_id;
		}
		if ($usr_id != "")
		{
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$entityId	 = $usr_id;
		}

		if ($entityId != '')
		{
			$contactId = ContactProfile::getByEntityId($entityId, $entityType);
		}
		$model->scq_prev_or_originating_followup = $ex_scqId;
		$followupPriority						 = $post['followUpTimeOpt'];
		$model->followUpTimeOpt					 = $post['followUpTimeOpt'];
		$followupDt								 = date('Y-m-d', strtotime(str_replace("/", "-", $post['locale_followup_date']))) . " " . date("h:i:s", strtotime($post['locale_followup_time']));
		$model->scq_follow_up_date_time			 = $followupDt;

		if ($followupPriority == 1 || $followupPriority == 2)
		{
			$model->scq_follow_up_date_time = new CDbExpression('NOW()');
		}
		$followUpby								 = $post['followUpby'];
		$model->scq_to_be_followed_up_by_type	 = ($followUpby == 1) ? 1 : 2;
		$teams									 = 0;
		if ($model->scq_to_be_followed_up_by_type == 1)
		{
			$teams			 = $post['scq_to_be_followed_up_by_id'];
			$bookingModel	 = Booking::model()->findbyPk($bookingId);
			$teamids		 = Admins::getTeamid(UserInfo::getUserId());
			if ((($teams == $teamids) && in_array($teamids, [4, 48])))
			{
				$teams = 41;
			}
			else if ($teams == -1 && (in_array($bookingModel->bkg_booking_type, [4, 9, 10, 11, 12, 14, 15])))
			{
				$teams = 48;
			}
			else if ($teams == -1 && (!in_array($bookingModel->bkg_booking_type, [4, 9, 10, 11, 12, 14, 15])))
			{
				$teams = 4;
			}
			$model->scq_to_be_followed_up_by_id = $teams;
		}
		else
		{
			$teams								 = Admins::getTeamid(trim($requestdt->getPost('Admins')['adm_id']));
			$model->scq_to_be_followed_up_by_id	 = $requestdt->getPost('Admins')['adm_id'];
		}
		if ($agt_id == 18190 && $followupPerson == 1)
		{
			$model->scq_to_be_followed_up_with_contact	 = 0;
			$model->scq_to_be_followed_up_with_value	 = $mmtNumber;
			$model->scq_to_be_followed_up_with_type		 = 2;
		}
		else
		{
			if ($post['scqType'] == 1)
			{
				$entityType										 = UserInfo::TYPE_ADMIN;
				$model->scq_to_be_followed_up_with_entity_type	 = $entityType;
				$model->scq_to_be_followed_up_with_entity_id	 = 0;
				$model->scq_to_be_followed_up_with_type			 = 0;
				$model->scq_to_be_followed_up_with_value		 = 0;
			}
			else
			{
				$model->scq_to_be_followed_up_with_entity_type	 = $entityType;
				$model->scq_to_be_followed_up_with_entity_id	 = $entityId;
				$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
				if ($arrPhoneByPriority != null)
				{
					$model->scq_to_be_followed_up_with_type	 = 2;
					$model->scq_to_be_followed_up_with_value = $arrPhoneByPriority['phn_phone_no'];
				}
				else
				{
					$model->scq_to_be_followed_up_with_type	 = 1;
					$model->scq_to_be_followed_up_with_value = $contactId;
				}
				$model->scq_to_be_followed_up_with_contact = $contactId;
				if ($scqType == 2 && $model->scq_to_be_followed_up_by_id == 1)
				{
					$model->scq_follow_up_queue_type = 1;
				}
			}
		}
		if ($bookingId)
		{
			$model->scq_related_bkg_id = $bookingId;
		}
		else
		{
			$model->scq_priority_score = ($scqType == 2 && $model->scq_to_be_followed_up_by_id == 1) ? 100 : 10;
		}
		$model->scq_follow_up_priority					 = ($scqType == 2 && $model->scq_to_be_followed_up_by_id == 1) ? 5 : 2;
		$model->scq_creation_comments					 = $post['scq_creation_comments'];
		$userModel										 = UserInfo::getInstance();
		$model->scq_created_by_type						 = UserInfo::TYPE_ADMIN;
		$model->scq_created_by_uid						 = $userModel->userId;
		$model->scq_to_be_followed_up_with_entity_rating = -1;
		$model->scq_follow_up_queue_type				 = ($scqType == 2 && $model->scq_to_be_followed_up_by_id == 1) ? 1 : 9;
		$model->scq_status								 = 1;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;

		$result		 = $model->create($model, $entityType, $platform	 = ServiceCallQueue::PLATFORM_ADMIN_CALL);
		$returnArr	 = $result->getData();
		$followupId	 = $returnArr['followupId'];
		/**
		 * This function is used to upload multiple file in a service queue documents
		 */
		CallBackDocuments::model()->upload($followupId);
		if ($model->scq_related_bkg_id != null && $followupId != null)
		{
			$params['blg_ref_id'] = $bookingId;
			BookingLog::model()->createLog($bookingId, $model->scq_creation_comments, UserInfo::getInstance(), BookingLog:: FOLLOWUP_CREATE, false, $params);
		}



		return $followupId;
	}

	/**
	 * This function is service call report for different type of queue
	 * @param type $date1
	 * @param type $date2
	 * @return type  array of queue
	 */
	public static function scqreport($date1, $date2)
	{
		$result	 = array();
		$data	 = array();
		$params	 = array('date1' => $date1, 'date2' => $date2);
		$sql	 = 'SELECT
				scq_follow_up_queue_type,
                CASE
					WHEN scq_follow_up_queue_type = 1 THEN "New Booking"
					WHEN scq_follow_up_queue_type = 2 THEN "Existing Booking"
					WHEN scq_follow_up_queue_type = 3 THEN "New Vendor Attachment"
					WHEN scq_follow_up_queue_type = 4 THEN "Vendor Support"
					WHEN scq_follow_up_queue_type = 5 THEN "Customers Advocacy"
					WHEN scq_follow_up_queue_type = 6 THEN "Driver Support/Line"
					WHEN scq_follow_up_queue_type = 7 THEN "Payment Followup"
					WHEN scq_follow_up_queue_type = 9 THEN "Service Requests"
					WHEN scq_follow_up_queue_type = 11 THEN "Penality Dispute"
					WHEN scq_follow_up_queue_type = 10 THEN "SOS"
					WHEN scq_follow_up_queue_type = 12 THEN "UpSell(CNG/Value)"
					WHEN scq_follow_up_queue_type = 13 THEN "Vendor Advocacy"
					WHEN scq_follow_up_queue_type = 14 THEN "Dispatch"
					WHEN scq_follow_up_queue_type = 15 THEN "Vendor Approval"
					WHEN scq_follow_up_queue_type = 16 THEN "New Lead Booking"
					WHEN scq_follow_up_queue_type = 17 THEN "New Quote Booking"
					WHEN scq_follow_up_queue_type = 18 THEN "B2B Post Pickup"
					WHEN scq_follow_up_queue_type = 19 THEN "Booking At Risk(Bar)"
					WHEN scq_follow_up_queue_type = 20 THEN "New Lead Booking(International)"
					WHEN scq_follow_up_queue_type = 21 THEN "New Quote Booking(International)"
					WHEN scq_follow_up_queue_type = 22 THEN "FBG"
					WHEN scq_follow_up_queue_type = 23 THEN "Vendor Payment Request"
					WHEN scq_follow_up_queue_type = 24 THEN "Upsell(Value+/Select)"
					WHEN scq_follow_up_queue_type = 25 THEN "Booking Complete Review"
					WHEN scq_follow_up_queue_type = 26 THEN "Apps Help & Tech support"
					WHEN scq_follow_up_queue_type = 27 THEN "Gozo Now"
					WHEN scq_follow_up_queue_type = 29 THEN "Auto Lead Followup"
					WHEN scq_follow_up_queue_type = 30 THEN "Document Approval"
					WHEN scq_follow_up_queue_type = 31 THEN "Vendor Approval  Zone Based Inventory"
					WHEN scq_follow_up_queue_type = 32 THEN "Critical and stress (risk) assignments(CSA)"
					WHEN scq_follow_up_queue_type = 33 THEN	"Airport DailyRental"
					WHEN scq_follow_up_queue_type = 34 THEN	"Last Min Booking"
					WHEN scq_follow_up_queue_type = 35 THEN	"Price High"
					WHEN scq_follow_up_queue_type = 36 THEN	"Driver NoShow"
					WHEN scq_follow_up_queue_type = 37 THEN	"Customer NoShow"
					WHEN scq_follow_up_queue_type = 38 THEN	"MMT Support"
					WHEN scq_follow_up_queue_type = 39 THEN	"Driver Car BreakDown"
					WHEN scq_follow_up_queue_type = 40 THEN	"Vendor Assign"
					WHEN scq_follow_up_queue_type = 41 THEN	"Cusomer Booking Cancel"
					WHEN scq_follow_up_queue_type = 42 THEN	"Spice Lead Booking"
					WHEN scq_follow_up_queue_type = 43 THEN	"Spice Quote Booking"
					WHEN scq_follow_up_queue_type = 44 THEN	"Spice Lead Booking International"
					WHEN scq_follow_up_queue_type = 45 THEN	"Spice Quote Booking International"
					WHEN scq_follow_up_queue_type = 46 THEN	"Vendor Due Amount"
					WHEN scq_follow_up_queue_type = 51 THEN	"Booking Reschedule"
					WHEN scq_follow_up_queue_type = 52 THEN	"Custom Push API"
					WHEN scq_follow_up_queue_type = 53 THEN	"VIP/VVIP Booking"

			    END AS followUpType,
                CASE
					WHEN scq_follow_up_queue_type = 1 THEN 7
					WHEN scq_follow_up_queue_type = 2 THEN 10
					WHEN scq_follow_up_queue_type = 3 THEN 9
					WHEN scq_follow_up_queue_type = 4 THEN 15
					WHEN scq_follow_up_queue_type = 5 THEN 3
					WHEN scq_follow_up_queue_type = 6 THEN 15
                    WHEN scq_follow_up_queue_type = 7 THEN 10
                    WHEN scq_follow_up_queue_type = 9 THEN 0
					WHEN scq_follow_up_queue_type = 10 THEN 15
					WHEN scq_follow_up_queue_type = 11 THEN 15
					WHEN scq_follow_up_queue_type = 12 THEN 10
					WHEN scq_follow_up_queue_type = 13 THEN 20
					WHEN scq_follow_up_queue_type = 14 THEN 13
					WHEN scq_follow_up_queue_type = 15 THEN 9
					WHEN scq_follow_up_queue_type = 16 THEN 7
					WHEN scq_follow_up_queue_type = 17 THEN 7
					WHEN scq_follow_up_queue_type = 18 THEN 10
					WHEN scq_follow_up_queue_type = 19 THEN 13
					WHEN scq_follow_up_queue_type = 20 THEN 43
					WHEN scq_follow_up_queue_type = 21 THEN 43
					WHEN scq_follow_up_queue_type = 22 THEN 40
					WHEN scq_follow_up_queue_type = 23 THEN 8
					WHEN scq_follow_up_queue_type = 24 THEN 10
					WHEN scq_follow_up_queue_type = 25 THEN 15
					WHEN scq_follow_up_queue_type = 26 THEN 10
					WHEN scq_follow_up_queue_type = 27 THEN 10
					WHEN scq_follow_up_queue_type = 29 THEN 44
					WHEN scq_follow_up_queue_type = 30 THEN 9
					WHEN scq_follow_up_queue_type = 31 THEN 47
					WHEN scq_follow_up_queue_type = 32 THEN 48 
					WHEN scq_follow_up_queue_type = 33 THEN	49
					WHEN scq_follow_up_queue_type = 34 THEN	44
					WHEN scq_follow_up_queue_type = 35 THEN	10
					WHEN scq_follow_up_queue_type = 36 THEN	3
					WHEN scq_follow_up_queue_type = 37 THEN	20
					WHEN scq_follow_up_queue_type = 38 THEN 10
					WHEN scq_follow_up_queue_type = 39 THEN 10
					WHEN scq_follow_up_queue_type = 40 THEN 13
					WHEN scq_follow_up_queue_type = 41 THEN 10
					WHEN scq_follow_up_queue_type = 42 THEN 51
					WHEN scq_follow_up_queue_type = 43 THEN 51
					WHEN scq_follow_up_queue_type = 44 THEN 51
					WHEN scq_follow_up_queue_type = 45 THEN 51
					WHEN scq_follow_up_queue_type = 46 THEN	52
					WHEN scq_follow_up_queue_type = 51 THEN	10
					WHEN scq_follow_up_queue_type = 52 THEN	10
					WHEN scq_follow_up_queue_type = 53 THEN	10

			    END AS cdt_id,
				SUM(IF(scq_create_date  BETWEEN :date1 AND :date2,1,0 )) as total_cbr_created_today,
				SUM(IF(scq_status IN (1,3) AND scq_create_date < :date2,1,0 )) as cbr_active_all,
				SUM(IF(scq_create_date  < :date1 AND scq_status IN (1,3) ,1,0 )) AS cbr_active_created_before_today,
				SUM(IF((scq_create_date  BETWEEN :date1 AND :date2) AND scq_status IN (1,3) ,1,0 )) as cbr_active_created_today,
				SUM(IF((scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status=2 ,1,0 )) as cbr_closed_today,
				SUM(IF(scq_platform=7  AND scq_create_date BETWEEN :date1 AND :date2,1,0 )) as total_cbr_created_today7,
				SUM(IF(scq_platform=7  AND scq_status IN (1,3)   AND scq_create_date < :date2,1,0 )) as cbr_active_all7,
				SUM(IF(scq_platform=7  AND scq_create_date < :date1 AND scq_status IN (1,3) ,1,0 )) AS cbr_active_created_before_today7,
				SUM(IF((scq_platform=7 AND scq_create_date BETWEEN :date1 AND :date2) AND scq_status IN (1,3),1,0 )) as cbr_active_created_today7,
				SUM(IF(scq_platform=7  AND scq_follow_up_date_time<=NOW() AND scq_create_date < :date2 AND scq_assigned_uid IS NULL  AND scq_status IN (1,3) ,1,0)) as  total_assignaable_now7,
				SUM(IF((scq_platform=7 AND scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status=2 ,1,0 )) as cbr_closed_today7,
			    SUM(IF(scq_platform=6  AND scq_create_date BETWEEN :date1 AND :date2,1,0 )) as total_cbr_created_today6,
				SUM(IF(scq_platform=6  AND scq_status IN (1,3) AND scq_create_date < :date2,1,0 )) as cbr_active_all6,
				SUM(IF(scq_platform=6  AND scq_create_date < :date1 AND scq_status  IN (1,3),1,0 )) AS cbr_active_created_before_today6,
				SUM(IF((scq_platform=6 AND scq_create_date BETWEEN :date1 AND :date2) AND scq_status  IN (1,3),1,0 )) as cbr_active_created_today6,
				SUM(IF(scq_platform=6  AND scq_follow_up_date_time<=NOW() AND scq_create_date < :date2  AND scq_assigned_uid IS NULL    AND scq_status IN (1,3),1,0)) as  total_assignaable_now6,
				SUM(IF((scq_platform=6 AND scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status=2 ,1,0 )) as cbr_closed_today6,
				COUNT( DISTINCT IF( scq_assigned_uid IS  NOT NULL   AND (scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status=2 ,scq_assigned_uid,NULL)) as  total_csr_today,
				SUM(IF(scq_assigned_uid IS NULL AND scq_follow_up_date_time<=NOW() AND scq_create_date < :date2  AND scq_status IN (1,3) ,1,0)) as  total_assignaable_now ,
				COUNT( DISTINCT IF( scq_assigned_uid IS NOT NULL AND scq_create_date <:date2  AND scq_status IN (1,3),scq_assigned_uid,NULL)) AS  total_assigned_csr_today
                FROM `service_call_queue`
				LEFT JOIN admins  ON adm_id = scq_assigned_uid
			    WHERE 1  AND scq_create_date >=DATE(DATE_SUB(:date1, INTERVAL 30 DAY)) AND scq_active=1  GROUP BY scq_follow_up_queue_type ';
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used to get no .of online  csr  by category and department id
	 * @param type $cdt_id
	 * @return type  int
	 */
	public static function getOnlineByCatDepart($cdt_id)
	{
		$params	 = array('cdt_id' => $cdt_id);
		$sql	 = 'SELECT
                    COUNT(DISTINCT  adm_id) as cnt
                    FROM admins adm
                    JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id AND  adm.adm_active=1
                    JOIN
                    (
                        SELECT
                        ado_admin_id,
                        MAX(ado_time) as ado_time
                        FROM `admin_onoff`
                        WHERE 1 
                        GROUP BY ado_admin_id
                    ) aadmin_onoff on aadmin_onoff.ado_admin_id = adm.adm_id
                    INNER Join admin_onoff admOnOff ON admOnOff.ado_admin_id=aadmin_onoff.ado_admin_id AND aadmin_onoff.ado_time=admOnOff.ado_time AND admOnOff.ado_status=1
                    JOIN
                    (
                            SELECT
                            admin_profiles.adp_adm_id,
                            JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT("$[", pseudo_rows.row, "].cdtWeight"))) AS cdtWeight,
                            JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT("$[", pseudo_rows.row, "].cdtId"))) AS cdtId
                            FROM admin_profiles
                            JOIN pseudo_rows
                            WHERE 1
                            HAVING cdtId IS NOT NULL
                    ) temp ON temp.adp_adm_id=adp.adp_adm_id
                    JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
                    JOIN teams ON cdt.cdt_tea_id = teams.tea_id
                    JOIN departments ON cdt_dpt_id = departments.dpt_id
                    JOIN categories ON cdt_cat_id = categories.cat_id
                    WHERE cdt.cdt_id =:cdt_id
                    AND
                    (
                            (tea_start_time IS NULL AND tea_stop_time IS NULL)
                                    OR
                            (tea_start_time < tea_stop_time AND CURRENT_TIME() BETWEEN tea_start_time AND tea_stop_time)
                                    OR
                            (tea_stop_time < tea_start_time AND CURRENT_TIME() < tea_start_time AND CURRENT_TIME() < tea_stop_time)
                                    OR
                            (tea_stop_time < tea_start_time AND CURRENT_TIME() > tea_start_time)
                    )
                    ORDER BY temp.cdtWeight ASC LIMIT 0,1';
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is service call details report for different type of queue
	 * @param type $date1
	 * @param type $date2
	 * @param type $searchArr
	 * @param type $searchCsr
	 * @param type $dateType
	 * @return type dataprovider
	 */
	public function cbrDetailsReport($followModel, $userInfo, $type = '')
	{
		$param	 = array();
		$where	 = "";
		$inner	 = "";
		if ($followModel->requestedBy > 0)
		{
			switch ($followModel->requestedBy)
			{
				case 1:
					$where	 .= " AND scq_to_be_followed_up_with_entity_type=$followModel->requestedBy AND scq_to_be_followed_up_with_entity_id=$followModel->custId  ";
					break;
				case 2:
					$where	 .= " AND scq_to_be_followed_up_with_entity_type=$followModel->requestedBy AND scq_to_be_followed_up_with_entity_id=$followModel->vendId  ";
					break;
				case 3:
					$where	 .= " AND scq_to_be_followed_up_with_entity_type=$followModel->requestedBy AND scq_to_be_followed_up_with_entity_id=$followModel->drvId  ";
					break;
				case 4:
					$where	 .= " AND scq_to_be_followed_up_with_entity_type=$followModel->requestedBy AND scq_to_be_followed_up_with_entity_id=$followModel->adminId  ";
					break;
				case 5:
					$where	 .= " AND scq_to_be_followed_up_with_entity_type=$followModel->requestedBy AND scq_to_be_followed_up_with_entity_id=$followModel->agntId  ";
					break;

				default:
					break;
			}
		}
		if ($followModel->scq_to_be_followed_up_by_type > 0)
		{
			switch ($followModel->scq_to_be_followed_up_by_type)
			{
				case 2:
					$where .= " AND scq_disposed_by_uid=$followModel->isGozen AND scq_active= 1 AND scq_status= 2  ";
					break;
				default:
					break;
			}
		}
		if ($followModel->isCreated > 0)
		{
			$csr			 = $userInfo->userId;
			$type			 = $userInfo->userType;
			$where			 .= " AND (scq_created_by_uid=:csr AND scq_created_by_type=4) ";
			$param['csr']	 = $csr;
		}

		if ($followModel->queueType != "" && $followModel->queueType > 0)
		{
			$where				 .= " AND scq_follow_up_queue_type=:queueType ";
			$param['queueType']	 = $followModel->queueType;
		}
		if ($followModel->scq_to_be_followed_up_by_id > 0 && $followModel->event_id != 6)
		{
			$teamsQueue	 = TeamQueueMapping::getQueueIdByTeamId($followModel->scq_to_be_followed_up_by_id);
			$queueIds	 = "";
			foreach ($teamsQueue as $queue)
			{
				$queueIds .= $queue['tqm_queue_id'] . ",";
			}
			DBUtil::getINStatement(rtrim($queueIds, ","), $bindStringQueue, $queueParams);
			$param	 = array_merge($param, $queueParams);
			$where	 .= " AND scq_follow_up_queue_type IN ({$bindStringQueue}) ";
		}
		if ($followModel->csrSearch != null)
		{
			DBUtil::getINStatement($followModel->csrSearch, $bindString1, $params2);
			$param	 = array_merge($param, $params2);
			$where	 .= " AND scq_assigned_uid IN ({$bindString1}) ";
		}
		if (($followModel->event_id != null && $followModel->event_id > 0 ) && ($followModel->event_by != "" && $followModel->event_by > 0))
		{
			switch ($followModel->event_by)
			{
				case 1:
					switch ($followModel->event_id)
					{
						case 1:
							$where			 .= " AND scq_create_date  BETWEEN :date1 AND :date2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 2:
							$where			 .= " AND scq_status IN (1, 3) AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;
						case 3:
							$where			 .= " AND scq_create_date < :date1 AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							break;
						case 4:
							$where			 .= " AND (scq_create_date BETWEEN :date1 AND :date2) AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 5:
							$where			 .= " AND scq_assigned_uid IS NULL AND scq_follow_up_date_time<=NOW() AND scq_create_date < :date2 AND scq_status IN (1, 3) ";
							$param['date2']	 = $followModel->date2;
							break;
						case 6:
							$where			 .= " AND (scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status = 2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							if ($followModel->csrSearch != null)
							{
								DBUtil::getINStatement($followModel->csrSearch, $bindString3, $params3);
								$param	 = array_merge($param, $params3);
								$where	 .= " AND scq_disposed_by_uid IN ({$bindString3}) ";
							}
							if ($followModel->scq_to_be_followed_up_by_id > 0)
							{
								$sqlinner	 = "SELECT GROUP_CONCAT( DISTINCT adp.adp_adm_id) AS ids
											FROM service_call_queue
											JOIN `admin_profiles` adp ON adp.adp_adm_id = service_call_queue.scq_assigned_uid AND scq_disposed_by_uid=scq_assigned_uid AND service_call_queue.scq_status=2
											JOIN
											(
												SELECT admin_profiles.adp_adm_id,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id
											JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
											JOIN teams ON cdt.cdt_tea_id = teams.tea_id
											WHERE 1 AND teams.tea_id=:teamId";
								$admIds		 = DBUtil::queryScalar($sqlinner, DBUtil::MDB(), ['teamId' => $followModel->scq_to_be_followed_up_by_id]);
								$admIds		 = $admIds != null ? $admIds : "-1";
								DBUtil::getINStatement($admIds, $bindString4, $params4);
								$param		 = array_merge($param, $params4);
								$where		 .= " AND scq_disposed_by_uid IN ({$bindString4}) ";
							}

							break;
						case 7:
							$where			 .= " AND scq_assigned_uid IS NOT NULL  AND scq_status IN  (1,3)  AND scq_create_date < :date2  ";
							$param['date2']	 = $followModel->date2;
							break;

						default:
							break;
					}
					break;
				case 2:
					switch ($followModel->event_id)
					{
						case 1:
							$where			 .= " AND scq_platform NOT IN (6,7) AND scq_create_date  BETWEEN :date1 AND :date2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 2:
							$where			 .= "  AND scq_platform NOT IN (6,7) AND scq_status IN (1, 3) AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;
						case 3:
							$where			 .= "  AND scq_platform NOT IN (6,7) AND scq_create_date < :date1 AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							break;
						case 4:
							$where			 .= "  AND scq_platform NOT IN (6,7) AND (scq_create_date BETWEEN :date1 AND :date2) AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 5:
							$where			 .= "  AND scq_platform NOT IN (6,7) AND scq_follow_up_date_time<=NOW() AND  scq_assigned_uid IS NULL AND scq_create_date < :date2 AND scq_status IN (1, 3) ";
							$param['date2']	 = $followModel->date2;
							break;
						case 6:
							$where			 .= "  AND scq_platform NOT IN (6,7) AND (scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status = 2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							if ($followModel->csrSearch != null)
							{
								DBUtil::getINStatement($followModel->csrSearch, $bindString3, $params3);
								$param	 = array_merge($param, $params3);
								$where	 .= " AND scq_disposed_by_uid IN ({$bindString3}) ";
							}
							if ($followModel->scq_to_be_followed_up_by_id > 0)
							{
								$sqlinner	 = "SELECT GROUP_CONCAT(DISTINCT adp.adp_adm_id) AS ids FROM service_call_queue
											JOIN `admin_profiles` adp ON adp.adp_adm_id = service_call_queue.scq_assigned_uid AND scq_disposed_by_uid=scq_assigned_uid AND service_call_queue.scq_status=2
											JOIN
											(
												SELECT admin_profiles.adp_adm_id,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id
											JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
											JOIN teams ON cdt.cdt_tea_id = teams.tea_id
											WHERE 1 AND teams.tea_id=:teamId";
								$admIds		 = DBUtil::queryScalar($sqlinner, DBUtil::MDB(), ['teamId' => $followModel->scq_to_be_followed_up_by_id]);
								$admIds		 = $admIds != null ? $admIds : "-1";
								DBUtil::getINStatement($admIds, $bindString4, $params4);
								$param		 = array_merge($param, $params4);
								$where		 .= " AND scq_disposed_by_uid IN ({$bindString4}) ";
							}
							break;
						case 7:
							$where			 .= " AND scq_assigned_uid IS NOT NULL  AND scq_status IN  (1,3)   AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;

						default:
							break;
					}
					break;
				case 3:
					switch ($followModel->event_id)
					{
						case 1:
							$where			 .= "  AND scq_platform=7 AND scq_create_date  BETWEEN :date1 AND :date2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 2:
							$where			 .= " AND scq_platform=7  AND scq_status IN (1, 3) AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;
						case 3:
							$where			 .= " AND scq_platform=7  AND scq_create_date < :date1 AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							break;
						case 4:
							$where			 .= " AND scq_platform=7  AND (scq_create_date BETWEEN :date1 AND :date2) AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 5:
							$where			 .= " AND scq_platform=7 AND scq_assigned_uid IS NULL AND scq_follow_up_date_time<=NOW() AND scq_create_date < :date2 AND scq_status IN (1, 3) ";
							$param['date2']	 = $followModel->date2;
							break;
						case 6:
							$where			 .= " AND scq_platform=7  AND (scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status = 2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							if ($followModel->csrSearch != null)
							{
								DBUtil::getINStatement($followModel->csrSearch, $bindString3, $params3);
								$param	 = array_merge($param, $params3);
								$where	 .= " AND scq_disposed_by_uid IN ({$bindString3}) ";
							}
							if ($followModel->scq_to_be_followed_up_by_id > 0)
							{
								$sqlinner	 = "SELECT GROUP_CONCAT(DISTINCT adp.adp_adm_id) AS ids FROM service_call_queue
											JOIN `admin_profiles` adp ON adp.adp_adm_id = service_call_queue.scq_assigned_uid AND scq_disposed_by_uid=scq_assigned_uid AND service_call_queue.scq_status=2
											JOIN
											(
												SELECT admin_profiles.adp_adm_id,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id
											JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
											JOIN teams ON cdt.cdt_tea_id = teams.tea_id
											WHERE 1 AND teams.tea_id=:teamId";
								$admIds		 = DBUtil::queryScalar($sqlinner, DBUtil::MDB(), ['teamId' => $followModel->scq_to_be_followed_up_by_id]);
								$admIds		 = $admIds != null ? $admIds : "-1";
								DBUtil::getINStatement($admIds, $bindString4, $params4);
								$param		 = array_merge($param, $params4);
								$where		 .= " AND scq_disposed_by_uid IN ({$bindString4}) ";
							}
							break;
						case 7:
							$where			 .= " AND scq_assigned_uid IS NOT NULL  AND scq_status IN  (1,3)  AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;

						default:
							break;
					}
					break;
				case 4:
					switch ($followModel->event_id)
					{
						case 1:
							$where			 .= " AND scq_platform=6 AND scq_create_date  BETWEEN :date1 AND :date2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 2:
							$where			 .= "  AND scq_platform=6 AND scq_status IN (1, 3) AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;
						case 3:
							$where			 .= "  AND scq_platform=6 AND scq_create_date < :date1 AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							break;
						case 4:
							$where			 .= "  AND scq_platform=6 AND (scq_create_date BETWEEN :date1 AND :date2) AND scq_status IN (1, 3) ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							break;
						case 5:
							$where			 .= "  AND scq_platform=6 AND  scq_assigned_uid IS NULL AND scq_follow_up_date_time<=NOW() AND scq_create_date < :date2 AND scq_status IN (1, 3) ";
							$param['date2']	 = $followModel->date2;
							break;
						case 6:
							$where			 .= "  AND scq_platform=6 AND (scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status = 2 ";
							$param['date1']	 = $followModel->date1;
							$param['date2']	 = $followModel->date2;
							if ($followModel->csrSearch != null)
							{
								DBUtil::getINStatement($followModel->csrSearch, $bindString3, $params3);
								$param	 = array_merge($param, $params3);
								$where	 .= " AND scq_disposed_by_uid IN ({$bindString3}) ";
							}
							if ($followModel->scq_to_be_followed_up_by_id > 0)
							{
								$sqlinner	 = "SELECT GROUP_CONCAT(DISTINCT adp.adp_adm_id) AS ids FROM service_call_queue
											JOIN `admin_profiles` adp ON adp.adp_adm_id = service_call_queue.scq_assigned_uid AND scq_disposed_by_uid=scq_assigned_uid AND service_call_queue.scq_status=2
											JOIN
											(
												SELECT admin_profiles.adp_adm_id,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id
											JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
											JOIN teams ON cdt.cdt_tea_id = teams.tea_id
											WHERE 1 AND teams.tea_id=:teamId";
								$admIds		 = DBUtil::queryScalar($sqlinner, DBUtil::MDB(), ['teamId' => $followModel->scq_to_be_followed_up_by_id]);
								$admIds		 = $admIds != null ? $admIds : "-1";
								DBUtil::getINStatement($admIds, $bindString4, $params4);
								$param		 = array_merge($param, $params4);
								$where		 .= " AND scq_disposed_by_uid IN ({$bindString4}) ";
							}
							break;
						case 7:
							$where			 .= " AND scq_assigned_uid IS NOT NULL  AND scq_status IN  (1,3)   AND scq_create_date < :date2 ";
							$param['date2']	 = $followModel->date2;
							break;

						default:
							break;
					}
					break;
				default:
					break;
			}
		}
		else if ($followModel->event_id != null && $followModel->event_id > 0)
		{
			switch ($followModel->event_id)
			{
				case 1:
					$where			 .= " AND scq_create_date  BETWEEN :date1 AND :date2 ";
					$param['date1']	 = $followModel->date1;
					$param['date2']	 = $followModel->date2;
					break;
				case 2:
					$where			 .= " AND scq_status IN (1, 3) AND scq_create_date < :date2 ";
					$param['date2']	 = $followModel->date2;
					break;
				case 3:
					$where			 .= " AND scq_create_date < :date1 AND scq_status IN (1, 3) ";
					$param['date1']	 = $followModel->date1;
					break;
				case 4:
					$where			 .= " AND (scq_create_date BETWEEN :date1 AND :date2) AND scq_status IN (1, 3) ";
					$param['date1']	 = $followModel->date1;
					$param['date2']	 = $followModel->date2;
					break;
				case 5:
					$where			 .= " AND scq_assigned_uid IS NULL AND scq_follow_up_date_time<=NOW()  AND scq_create_date < :date2 AND scq_status IN (1, 3) ";
					$param['date2']	 = $followModel->date2;
					break;
				case 6:
					$where			 .= " AND (scq_disposition_date BETWEEN :date1 AND :date2) AND scq_status = 2 ";
					$param['date1']	 = $followModel->date1;
					$param['date2']	 = $followModel->date2;
					if ($followModel->csrSearch != null)
					{
						DBUtil::getINStatement($followModel->csrSearch, $bindString3, $params3);
						$param	 = array_merge($param, $params3);
						$where	 .= " AND scq_disposed_by_uid IN ({$bindString3}) ";
					}
					if ($followModel->scq_to_be_followed_up_by_id > 0)
					{
						$sqlinner	 = "SELECT GROUP_CONCAT(DISTINCT adp.adp_adm_id) AS ids FROM service_call_queue
											JOIN `admin_profiles` adp ON adp.adp_adm_id = service_call_queue.scq_assigned_uid AND scq_disposed_by_uid=scq_assigned_uid AND service_call_queue.scq_status=2
											JOIN
											(
												SELECT admin_profiles.adp_adm_id,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
												JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
												FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
											)temp ON temp.adp_adm_id=adp.adp_adm_id
											JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
											JOIN teams ON cdt.cdt_tea_id = teams.tea_id
											WHERE 1 AND teams.tea_id=:teamId";
						$admIds		 = DBUtil::queryScalar($sqlinner, DBUtil::MDB(), ['teamId' => $followModel->scq_to_be_followed_up_by_id]);
						$admIds		 = $admIds != null ? $admIds : "-1";
						DBUtil::getINStatement($admIds, $bindString4, $params4);
						$param		 = array_merge($param, $params4);
						$where		 .= " AND scq_disposed_by_uid IN ({$bindString4}) ";
					}

					break;
				case 7:
					$where			 .= " AND scq_assigned_uid IS NOT NULL  AND scq_status IN  (1,3)   AND scq_create_date < :date2 ";
					$param['date2']	 = $followModel->date2;
					break;

				default:
					break;
			}
		}
		else if ($followModel->event_by != null && $followModel->event_by > 0)
		{
			switch ($followModel->event_by)
			{
				case 2:
					$where .= " AND scq_platform NOT IN (6,7) ";
					if ($followModel->date2 != null)
					{
						$where			 .= " AND scq_create_date<=:date2 ";
						$param['date2']	 = $followModel->date2;
					}
					break;
				case 3:
					$where .= "  AND scq_platform=7  ";
					if ($followModel->date2 != null)
					{
						$where			 .= " AND scq_create_date<=:date2 ";
						$param['date2']	 = $followModel->date2;
					}
					break;
				case 4:
					$where .= "  AND scq_platform=6 ";
					if ($followModel->date2 != null)
					{
						$where			 .= " AND scq_create_date<=:date2 ";
						$param['date2']	 = $followModel->date2;
					}
					break;
				default:
					if ($followModel->date2 != null && $followModel->date1 != null)
					{
						$where			 .= "AND scq_create_date >=:date1 AND scq_create_date<=:date2 ";
						$param['date2']	 = $followModel->date2;
						$param['date1']	 = $followModel->date1;
					}
					break;
			}
		}
		else if ($followModel->date2 != null && $followModel->date1 != null)
		{
			$where			 .= "AND scq_create_date >=:date1 AND scq_create_date<=:date2 ";
			$param['date2']	 = $followModel->date2;
			$param['date1']	 = $followModel->date1;
		}

		if ($followModel->bookingType == 1)
		{
			$inner .= "  INNER JOIN booking ON booking.bkg_id=service_call_queue.scq_related_bkg_id AND  bkg_reconfirm_flag=0 ";
		}
		else if ($followModel->bookingType == 2)
		{
			$inner .= "  INNER JOIN booking ON booking.bkg_id=service_call_queue.scq_related_bkg_id AND booking.bkg_status IN (2,3,4,5,6,7,9) AND  bkg_reconfirm_flag=1";
		}


		$sql = "SELECT
					scq_id AS FollowupId,
					scq_assignment_count AS currentAssignmentCount,
                   IF(
						scq_to_be_followed_up_with_contact IS NOT NULL,
						scq_to_be_followed_up_with_contact,
						IF(scq_to_be_followed_up_with_type=1,scq_to_be_followed_up_with_value,0)
                    ) AS CustomerContactId,
                    CONCAT(users.usr_name,' ',users.usr_lname) AS contactName,
					scq_related_bkg_id AS ItemID,
					CASE
					WHEN scq_follow_up_queue_type = 1 THEN 'New Booking'
					WHEN scq_follow_up_queue_type = 2 THEN 'Existing Booking'
					WHEN scq_follow_up_queue_type = 3 THEN 'New Vendor Attachment'
					WHEN scq_follow_up_queue_type = 4 THEN 'Vendor Support/Line'
					WHEN scq_follow_up_queue_type = 5 THEN 'Customer Advocacy/Gozo Cares'
					WHEN scq_follow_up_queue_type = 6 THEN 'Driver Support/Line'
					WHEN scq_follow_up_queue_type = 7 THEN 'Payment Followup'
					WHEN scq_follow_up_queue_type = 9 THEN 'Service Requests'
					WHEN scq_follow_up_queue_type = 11 THEN 'Penalty Disputes'
					WHEN scq_follow_up_queue_type = 10 THEN 'SOS/Emergency'
					WHEN scq_follow_up_queue_type = 12 THEN 'UpSell(CNG/Value)'
					WHEN scq_follow_up_queue_type = 13 THEN 'Vendor Advocacy'
					WHEN scq_follow_up_queue_type = 14 THEN 'Dispatch'
					WHEN scq_follow_up_queue_type = 15 THEN 'Vendor Approval'
					WHEN scq_follow_up_queue_type = 16 THEN 'New Lead Booking'
					WHEN scq_follow_up_queue_type = 17 THEN 'New Quote Booking'
					WHEN scq_follow_up_queue_type = 18 THEN 'B2B Post Pickup'
					WHEN scq_follow_up_queue_type = 19 THEN 'Booking At Risk(Bar)'
					WHEN scq_follow_up_queue_type = 20 THEN 'New Lead Booking(International)'
					WHEN scq_follow_up_queue_type = 21 THEN 'New Quote Booking(International)'
					WHEN scq_follow_up_queue_type = 22 THEN 'FBG'
					WHEN scq_follow_up_queue_type = 23 THEN 'Vendor Payment Request'
					WHEN scq_follow_up_queue_type = 24 THEN 'Upsell(Value+/Select)'
					WHEN scq_follow_up_queue_type = 25 THEN 'Booking Complete Review'
					WHEN scq_follow_up_queue_type = 26 THEN 'Apps Help & Tech support'
					WHEN scq_follow_up_queue_type = 27 THEN 'Gozo Now'
					WHEN scq_follow_up_queue_type = 29 THEN 'Auto Lead Followup'
					WHEN scq_follow_up_queue_type = 30 THEN 'Document Approval'
					WHEN scq_follow_up_queue_type = 31 THEN 'Vendor Approval  Zone Based Inventory'
					WHEN scq_follow_up_queue_type = 32 THEN 'Critical and stress (risk) assignments(CSA)'
					WHEN scq_follow_up_queue_type = 33 THEN	'Airport DailyRental'
					WHEN scq_follow_up_queue_type = 34 THEN	'Last Min Booking'
					WHEN scq_follow_up_queue_type = 35 THEN	'Price High'
					WHEN scq_follow_up_queue_type = 36 THEN	'Driver NoShow'
					WHEN scq_follow_up_queue_type = 37 THEN	'Customer NoShow'
					WHEN scq_follow_up_queue_type = 38 THEN	'MMT Support'
					WHEN scq_follow_up_queue_type = 39 THEN	'Driver Car BreakDown'	
					WHEN scq_follow_up_queue_type = 40 THEN	'Vendor Assign'
					WHEN scq_follow_up_queue_type = 41 THEN	'Cusomer Booking Cancel'
					WHEN scq_follow_up_queue_type = 42 THEN	'Spice Lead Booking'
					WHEN scq_follow_up_queue_type = 43 THEN	'Spice Quote Booking'
					WHEN scq_follow_up_queue_type = 44 THEN	'Spice Lead Booking International'
					WHEN scq_follow_up_queue_type = 45 THEN	'Spice Quote Booking International'
					WHEN scq_follow_up_queue_type = 46 THEN	'Vendor Due Amount'
					WHEN scq_follow_up_queue_type = 51 THEN	'Booking Reschedule'
					WHEN scq_follow_up_queue_type = 53 THEN	'VIP/VVIP Booking'

			        END AS followUpType,
					scq_assigned_uid AS csrId,
					adm.gozen AS csrName,
					adp.adp_emp_code AS empCode,
					scq_create_date  AS createDate,
					scq_assigned_date_time AS assignedDate,
					scq_disposition_date AS closedDate,
					adm1.gozen AS ClosedCsrName,
					adp1.adp_emp_code  AS ClosedCsrempCode,
					adm2.gozen AS CreatedCsrName,
					adp2.adp_emp_code  AS CreatedCsrempCode,
					adp2.adp_adm_id,
					scq_created_by_type,
					scq_created_by_uid,
					scq_follow_up_date_time AS followUpdDate,
					vnd_name,
					vnd_id,
					CONCAT(usr_name,' ',usr_lname ) AS usr_name,
					user_id,
					drv_name,
					drv_id,
					scq_creation_comments,
                    scq_disposition_comments,
					scq_additional_param
					FROM service_call_queue
					$inner
					LEFT JOIN admins adm ON adm.adm_id = scq_assigned_uid
					LEFT JOIN admin_profiles  adp ON adm.adm_id = adp.adp_adm_id
					LEFT JOIN admins adm1 ON adm1.adm_id = scq_disposed_by_uid
					LEFT JOIN admin_profiles adp1   ON adm1.adm_id =adp1.adp_adm_id
					LEFT JOIN admins adm2 ON adm2.adm_id = scq_created_by_uid  AND  scq_created_by_type=4
					LEFT JOIN admin_profiles  adp2 ON adm2.adm_id = adp2.adp_adm_id  AND  scq_created_by_type=4
					LEFT JOIN contact_profile cp ON scq_created_by_uid = cp.cr_is_consumer
					LEFT JOIN vendors  ON  vendors.vnd_id = cp.cr_is_vendor AND  scq_created_by_type=2
					LEFT JOIN users    ON    users.user_id  = cp.cr_is_consumer AND  scq_created_by_type IN(2,3)
					LEFT JOIN drivers  ON   drivers.drv_id = cp.cr_is_driver AND  scq_created_by_type=3
					WHERE 1 $where AND scq_create_date >='2021-01-01 00:00:00' AND scq_active=1  GROUP BY scq_id";
		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB(), $param);
		}
		else
		{
			$count			 = DBUtil::queryScalar(" SELECT COUNT(*) FROM ($sql ) temp", DBUtil::MDB(), $param);
			$dataprovider	 = new CSqlDataProvider($sql, array(
				"totalItemCount" => $count,
				"params"		 => $param,
				'db'			 => DBUtil::MDB(),
				"pagination"	 => array("pageSize" => 700),
				'sort'			 => array('attributes' => array('ItemID', 'createDate', 'assignedDate', 'closedDate'), 'defaultOrder' => 'closedDate ASC')
			));
			return $dataprovider;
		}
	}

	public function getCBRDetailbyId($contactid, $from = '')
	{
		$joinstr = '';
		$whrcond = '';

		if ($from != "" && $from == "Vendor")
		{
			$whrcond = " AND cp.cr_is_vendor =" . $contactid;
		}
		else if ($from != "" && $from == "Driver")
		{
			$whrcond = " AND cp.cr_is_driver =" . $contactid;
		}
		else if ($from != "" && $from == "Consumer")
		{
			$whrcond = " AND cp.cr_is_consumer =" . $contactid;
		}
		$sql			 = "SELECT
					CASE
                       WHEN scq.scq_created_by_type = 1 THEN IFNULL(usr_name, '')
                        WHEN scq.scq_created_by_type = 2 THEN IFNULL(u.usr_name, '')
                        WHEN scq.scq_created_by_type = 3 THEN IFNULL(u.usr_name, '')
                       WHEN scq.scq_created_by_type = 10 THEN 'System'
                    END AS user_fname,
                    CASE
                        WHEN scq.scq_created_by_type = 1 THEN IFNULL(u.usr_lname, '')
                        WHEN scq.scq_created_by_type = 2 THEN IFNULL(u.usr_lname, '')
                        WHEN scq.scq_created_by_type = 3 THEN IFNULL(u.usr_lname, '')
                        WHEN scq.scq_created_by_type = 10 THEN ''
                    END AS user_lname,
					tea_name,
					scq.scq_id,
                	scq.scq_follow_up_date_time AS scq_follow_up_date_time,
                    CASE
					WHEN scq.scq_to_be_followed_up_with_entity_type =1 THEN 'Consumer'
					WHEN scq.scq_to_be_followed_up_with_entity_type =2 THEN 'Vendor'
                    WHEN scq.scq_to_be_followed_up_with_entity_type =3 THEN 'Driver'
                    END as followupWith,
                    scq.scq_status AS scq_status,
                    scq.scq_disposition_comments AS scq_disposition_comments,
                    scq.scq_creation_comments as scq_creation_comments,
                    scq.scq_to_be_followed_up_with_value AS scq_to_be_followed_up_with_value,
                    scq.scq_assigned_uid  as scq_assigned_uid ,
					CASE
						WHEN scq_status = 0 THEN 'Inactive'
						WHEN scq_status = 1 THEN 'Active'
						WHEN scq_status = 2 THEN 'Closed'
                        WHEN scq_status = 3 THEN 'Partial Closed'
					END AS status
					FROM `service_call_queue` as scq
					JOIN contact_profile as cp on cp.cr_is_consumer = scq_created_by_uid
					JOIN users as u on u.user_id= cp.cr_is_consumer
					LEFT  JOIN  teams ON tea_id  = scq_to_be_followed_up_by_id and scq_to_be_followed_up_by_type=1
					WHERE 1 =1  " . $whrcond . " and scq_active=1";
		$count			 = DBUtil::queryScalar(" SELECT COUNT(*) FROM ($sql ) temp", DBUtil::MDB());
		$dataprovider	 = new CSqlDataProvider($sql, array(
			"totalItemCount" => $count,
			'db'			 => DBUtil ::MDB(),
			"pagination"	 => array("pageSize" => 700),
			'sort'			 => array('attributes' => array(), 'defaultOrder' => 'scq_follow_up_date_time DESC')
		));
		return $dataprovider;
	}

	/**
	 * @return CDbCommand
	 */
	public static function getCSRLeadPeformanceCommand($fromDate, $toDate, $csr = 0, $teamLead = 0)
	{
		$scqFromDateSql	 = $scqToDateSql	 = "";
		$bkgFromDateSql	 = $bkgToDateSql	 = "";
		$scqCSRSql		 = $bkgCSRSql		 = "";
		$scqLeadSql		 = "";
		$params			 = [];
		$calendarDays	 = `Total_Days`;

		if ($fromDate != '')
		{
			$scqFromDateSql		 = " AND scq_assigned_date_time>=:fromDate";
			$bkgFromDateSql		 = " AND bkg_create_date>=:fromDate";
			$params[":fromDate"] = $fromDate;
		}


		if ($toDate != '')
		{
			$scqToDateSql		 = " AND scq_assigned_date_time<=:toDate";
			$bkgToDateSql		 = " AND bkg_create_date<=:toDate";
			$params[":toDate"]	 = $toDate;
		}

		if ($fromDate != '' && $toDate != '')
		{
			$calendarDays = " abs(DATEDIFF(:toDate, :fromDate)) + 1";
		}

		if ($csr > 0)
		{
			$scqCSRSql		 = " AND scq_assigned_uid=:csr";
			$bkgCSRSql		 = " AND bkg_create_user_id=:csr";
			$params["csr"]	 = $csr;
		}
		if ($teamLead > 0)
		{
			$scqLeadSql			 = " AND (atl.adm_id=:teamLead OR admins.adm_id=:teamLead)";
			$params["teamLead"]	 = $teamLead;
		}

		$sql = "SELECT a.*,`Median Followup Time` as Median_Followup_Time, `Quote_Created`, `Quotation Created (Unqiue Customer)` as Quotation_Created_Unqiue_Customer,
                        (bookingActive + Booking_Cancelled) AS `Booking_Confirmed`, `Booking_Confirmed_Unqiue_Customer`, `Total_Gozo_Amount`, 	`Booking_Cancelled`, `Booking_Served`, `Gozo_Amount_Earned`,
						CSRPerformanceScore1(`BookingConfirmed`, `Followups_points`, `Total_Days`, $calendarDays, `Total_Gozo_Amount`) as performanceScore,
						other_followups, payment_followups, self_payment_followups
                    FROM (
						SELECT admins.adm_id,  CONCAT(admins.gozen, ' (', admins.adm_user,')') as 'CSR_Name',
						CONCAT(atl.adm_fname, ' ', atl.adm_lname, ' (',  atl.adm_user, ')') as TeamLeader,
							COUNT(DISTINCT DATE(scq_assigned_date_time)) as 'Total_Days',
							ROUND(AVG(TIMESTAMPDIFF(MINUTE, service_call_queue.scq_assigned_date_time, service_call_queue.scq_disposition_date))) as 'Average_Followup_Time',
							MAX(TIMESTAMPDIFF(MINUTE, service_call_queue.scq_assigned_date_time, service_call_queue.scq_disposition_date)) as 'Max_Followup_Time',
							SUM(TIMESTAMPDIFF(MINUTE, service_call_queue.scq_assigned_date_time, service_call_queue.scq_disposition_date)) as 'Total_Followup_Time',
							COUNT(1) as 'Total_Follow_ups',
							ROUND(SUM(CASE
								WHEN scq_follow_up_queue_type IN (1) THEN 1.5
								WHEN scq_follow_up_queue_type IN (17,21) AND scq_priority_score>=120 THEN 2.25
								WHEN scq_follow_up_queue_type IN (17,21) AND scq_priority_score BETWEEN 100 AND 120 THEN 1.75
								WHEN scq_follow_up_queue_type IN (17,21) AND scq_priority_score<=100 THEN 1.25
								WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score>105 THEN 1
								WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score BETWEEN 80 AND 100 THEN 0.75
								WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score BETWEEN 60 AND 80 THEN 0.5
								WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score<60 THEN 0.25
								ELSE 1
								END
							)) AS Followups_points,
							SUM(IF(scq_follow_up_queue_type IN (16,20) AND service_call_queue.scq_related_lead_id IS NOT NULL,1,0)) as 'Lead_Followup',
							SUM(IF(scq_follow_up_queue_type IN (17,21) AND service_call_queue.scq_related_bkg_id IS NOT NULL,1,0)) as 'Quote_Followup',
							SUM(IF(scq_follow_up_queue_type=1 AND scq_platform IN (0,1,2,6),1,0)) as 'Call_Back_Request'
                        FROM `service_call_queue`
                        INNER JOIN admins ON scq_assigned_uid=admins.adm_id
						INNER JOIN admin_profiles adp ON adp.adp_adm_id=admins.adm_id 
						LEFT JOIN admins atl ON adp.adp_team_leader_id=atl.adm_id 
                        WHERE scq_follow_up_queue_type IN (1,16,17,20,21)  AND scq_active=1
							$scqFromDateSql $scqToDateSql $scqCSRSql $scqLeadSql
                        GROUP BY admins.adm_id ) a
                    LEFT JOIN (
                        SELECT bkg_create_user_id, COUNT(1) as 'Quote_Created',
							COUNT(DISTINCT bkg_user_id) as 'Quotation Created (Unqiue Customer)',
							SUM(IF(bkg_status IN (2,3,5,6,7),1,0)) AS bookingActive,
							SUM(IF(bkg_advance_amount>0 AND bkg_status IN (9), 1, 0)) as 'Booking_Cancelled',
							(SUM(IF(bkg_status IN (2,3,5,6,7),1,0)) 
								+ LEAST(ROUND(SUM(IF(bkg_advance_amount>0 AND bkg_status IN (2,3,5,6,7,9),1,0))*0.12), 
									SUM(IF(bkg_advance_amount>0 AND bkg_status IN (9), 1, 0)))) AS BookingConfirmed,

							SUM(IF(bkg_status IN (5,6,7) AND bkg_pickup_date<NOW(), 1, 0)) as 'Booking_Served',
							SUM(IF(bkg_status IN (3,5,6,7) AND bkg_pickup_date<NOW(), bkg_gozo_amount-bkg_credits_used, 0)) as 'Gozo_Amount_Earned',
							COUNT(DISTINCT IF(bkg_status IN (2,3,5,6,7,9), bkg_user_id, null)) as 'Booking_Confirmed_Unqiue_Customer',
							SUM(IF(bkg_status IN (2,3,5,6,7), bkg_gozo_amount-bkg_credits_used, 0)) as 'Total_Gozo_Amount'
                        FROM booking
                        INNER JOIN booking_trail ON bkg_id=btr_bkg_id AND booking_trail.bkg_create_user_type=4
                        INNER JOIN booking_user ON bui_bkg_id=bkg_id
                        INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
                        WHERE bkg_status IN (1,15,2,3,5,6,7,9)  AND bkg_agent_id IS NULL
								$bkgFromDateSql $bkgToDateSql $bkgCSRSql
                        GROUP BY bkg_create_user_id
					) b ON a.adm_id=b.bkg_create_user_id
					INNER JOIN (
                        SELECT date, scq_assigned_uid, `Median Followup Time` FROM (
							SELECT DATE_FORMAT(scq_assigned_date_time, '%Y-%m') as date, scq_assigned_uid,
								ROUND(PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY TIMESTAMPDIFF(MINUTE, service_call_queue.scq_assigned_date_time, service_call_queue.scq_disposition_date))
										OVER (PARTITION BY DATE_FORMAT(scq_create_date, '%Y-%m'),scq_assigned_uid)) as 'Median Followup Time'
							FROM `service_call_queue`
							WHERE scq_follow_up_queue_type IN (1,16,17)  AND scq_active=1
									$scqFromDateSql $scqToDateSql $scqCSRSql) a
							GROUP BY scq_assigned_uid
                    ) c ON a.adm_id=c.scq_assigned_uid
					LEFT JOIN (
							SELECT DATE_FORMAT(scq_assigned_date_time, '%Y-%m') as date, scq_assigned_uid,
								COUNT(DISTINCT scq_id) as other_followups,
								COUNT(DISTINCT IF(scq_follow_up_queue_type=7, scq_id, null)) as payment_followups,
								COUNT(DISTINCT IF(scq_follow_up_queue_type=7 AND scq_assigned_uid=booking_trail.bkg_create_user_id, scq_id, null)) as self_payment_followups
							FROM `service_call_queue`
							LEFT JOIN booking on bkg_id=scq_related_bkg_id
							LEFT JOIN booking_trail ON bkg_id=btr_bkg_id AND booking_trail.bkg_create_user_type=4
							WHERE scq_follow_up_queue_type NOT IN (1,16,17,20,21)
									$scqFromDateSql $scqToDateSql $scqCSRSql
							GROUP BY scq_assigned_uid
                    ) d ON a.adm_id=d.scq_assigned_uid
				";

		$command		 = DBUtil::command($sql, DBUtil::SDB());
		$command->params = $params;
		return $command;
	}

	public function csrLeadPerformanceReport($followModel, $userInfo = '', $type = DBUtil::ReturnType_Provider)
	{
		$fromDate	 = "$followModel->from_date 00:00:00";
		$toDate		 = "$followModel->to_date 23:59:59";
		$teamLead	 = "$followModel->adminId";

		$command = self::getCSRLeadPeformanceCommand($fromDate, $toDate, 0, $teamLead);

		$count = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($command, array(
				"totalItemCount" => $count,
				"params"		 => $command->params,
				'db'			 => DBUtil ::SDB(),
				"pagination"	 => array("pageSize" => 50),
				'sort'			 => array('attributes'	 => array('adm_id', 'CSR_Name', 'Total_Days', 'Average_Followup_Time', 'Max_Followup_Time', 'Total_Followup_Time',
						'Total_Follow_ups', 'Lead_Followup', 'Quote_Followup', 'Call_Back_Request', 'Median_Followup_Time', 'Followups_points',
						'Quote_Created', 'Quotation_Created_Unqiue_Customer', 'Booking_Confirmed', 'Booking_Confirmed_Unqiue_Customer', 'Total_Gozo_Amount', 'performanceScore'),
					'defaultOrder'	 => 'performanceScore DESC, Booking_Confirmed DESC, Followups_points DESC')
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($command->getText(), DBUtil::SDB(), $command->params);
		}
	}

	/**
	 * This function is service call close report for different type of queue
	 * @param type $date1
	 * @param type $date2
	 * @param type $date
	 * @param type $queueType
	 * @return type dataprovider
	 */
	public static function cbrCloseReport($date, $date1, $date2, $queueType = 1, $teamId = 0)
	{

		$params	 = array('date1' => $date1, 'date2' => $date2);
		$where	 = "";
		if ($teamId > 0)
		{
			$sqlinner	 = "SELECT GROUP_CONCAT(DISTINCT adp.adp_adm_id) AS ids FROM service_call_queue
							JOIN `admin_profiles` adp ON adp.adp_adm_id = service_call_queue.scq_assigned_uid AND scq_disposed_by_uid=scq_assigned_uid AND service_call_queue.scq_status=2
							JOIN
							(
								SELECT admin_profiles.adp_adm_id,
								JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
								JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
								FROM admin_profiles JOIN pseudo_rows WHERE 1 HAVING cdtId IS NOT NULL
							)temp ON temp.adp_adm_id=adp.adp_adm_id
							JOIN `cat_depart_team_map` cdt ON temp.cdtId=cdt.cdt_id
							JOIN teams ON cdt.cdt_tea_id = teams.tea_id
							WHERE 1 AND teams.tea_id=:teamId";
			$admIds		 = DBUtil::queryScalar($sqlinner, DBUtil::MDB(), ['teamId' => $teamId]);
			$admIds		 = $admIds != null ? $admIds : "-1";
			DBUtil::getINStatement($admIds, $bindString4, $params1);
			$params		 = array_merge($params1, $params);
			$where		 .= " AND scq_disposed_by_uid IN ({$bindString4}) ";
		}
		if ($queueType > 0)
		{
			$qType				 = $queueType;
			$where				 .= " AND scq_follow_up_queue_type = :queueType  ";
			$params['queueType'] = $queueType;
		}
		else
		{
			$qType = "''";
		}

		$sql = "SELECT
							COUNT(scq_disposed_by_uid) AS cnt,
							'' AS time,
							'$date1' as fromDate,
							'$date2' as toDate,
							'$date' as date,
							scq_follow_up_queue_type,
                                                        $qType AS queueType,
							CASE
							WHEN scq_follow_up_queue_type = 1 THEN 'New Booking'
							WHEN scq_follow_up_queue_type = 2 THEN 'Existing Booking'
							WHEN scq_follow_up_queue_type = 3 THEN 'New Vendor Attachment'
							WHEN scq_follow_up_queue_type = 4 THEN 'Vendor Support/Line'
							WHEN scq_follow_up_queue_type = 5 THEN 'Customer Advocacy/Gozo Cares'
							WHEN scq_follow_up_queue_type = 6 THEN 'Driver Support/Line'
							WHEN scq_follow_up_queue_type = 7 THEN 'Payment Followup'
							WHEN scq_follow_up_queue_type = 9 THEN 'Service Requests'
							WHEN scq_follow_up_queue_type = 11 THEN 'Penalty Disputes'
							WHEN scq_follow_up_queue_type = 10 THEN 'SOS/Emergency'
							WHEN scq_follow_up_queue_type = 12 THEN 'UpSell(CNG/Value)'
							WHEN scq_follow_up_queue_type = 13 THEN 'Vendor Advocacy'
							WHEN scq_follow_up_queue_type = 14 THEN 'Dispatch'
							WHEN scq_follow_up_queue_type = 15 THEN 'Vendor Approval'
							WHEN scq_follow_up_queue_type = 16 THEN 'New Lead Booking'
							WHEN scq_follow_up_queue_type = 17 THEN 'New Quote Booking'
							WHEN scq_follow_up_queue_type = 18 THEN 'B2B Post Pickup'
							WHEN scq_follow_up_queue_type = 19 THEN 'Booking At Risk(Bar)'
							WHEN scq_follow_up_queue_type = 20 THEN 'New Lead Booking(International)'
							WHEN scq_follow_up_queue_type = 21 THEN 'New Quote Booking(International)'
							WHEN scq_follow_up_queue_type = 22 THEN 'FBG'
							WHEN scq_follow_up_queue_type = 23 THEN 'Vendor Payment Request'
							WHEN scq_follow_up_queue_type = 24 THEN 'Upsell(Value+/Select)'
							WHEN scq_follow_up_queue_type = 25 THEN 'Booking Complete Review'
							WHEN scq_follow_up_queue_type = 26 THEN 'Apps Help & Tech support'
							WHEN scq_follow_up_queue_type = 27 THEN 'Gozo Now'
							WHEN scq_follow_up_queue_type = 29 THEN 'Auto Lead Followup'
							WHEN scq_follow_up_queue_type = 30 THEN 'Document Approval'
							WHEN scq_follow_up_queue_type = 31 THEN 'Vendor Approval  Zone Based Inventory'
							WHEN scq_follow_up_queue_type = 32 THEN 'Critical and stress (risk) assignments(CSA)'
							WHEN scq_follow_up_queue_type = 33 THEN	'Airport DailyRental'
							WHEN scq_follow_up_queue_type = 34 THEN	'Last Min Booking'
							WHEN scq_follow_up_queue_type = 35 THEN	'Price High'
							WHEN scq_follow_up_queue_type = 36 THEN	'Driver NoShow'
							WHEN scq_follow_up_queue_type = 37 THEN	'Customer NoShow'
							WHEN scq_follow_up_queue_type = 38 THEN	'MMT Support'
							WHEN scq_follow_up_queue_type = 39 THEN	'Driver Car BreakDown'
							WHEN scq_follow_up_queue_type = 40 THEN	'Vendor Assign'
							WHEN scq_follow_up_queue_type = 41 THEN	'Cusomer Booking Cancel'
							WHEN scq_follow_up_queue_type = 42 THEN	'Spice Lead Booking'
							WHEN scq_follow_up_queue_type = 43 THEN	'Spice Quote Booking'
							WHEN scq_follow_up_queue_type = 44 THEN	'Spice Lead Booking International'
							WHEN scq_follow_up_queue_type = 45 THEN	'Spice Quote Booking International'
							WHEN scq_follow_up_queue_type = 46 THEN	'Vendor Due Amount'
							WHEN scq_follow_up_queue_type = 51 THEN	'Booking Reschedule'
							WHEN scq_follow_up_queue_type = 53 THEN	'VIP/VVIP Booking'

							END AS ItemType,
							''  as onlineTime,
							''  as time_toassigned,
							scq_assigned_uid  AS csrId,
							CONCAT(adm_fname, ' ', adm_lname) AS csrName
							FROM `service_call_queue`
							JOIN admins ON adm_id = scq_disposed_by_uid
							WHERE     1
							$where
							AND scq_create_date  >= '2021-01-01 00:00:00'
							AND (scq_disposition_date BETWEEN :date1 AND :date2)
							AND scq_active=1
							GROUP BY scq_disposed_by_uid";

		$count			 = DBUtil::queryScalar(" SELECT COUNT(*) FROM ($sql ) temp", DBUtil::MDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, array(
			"totalItemCount" => $count,
			"params"		 => $params,
			'db'			 => DBUtil ::MDB(),
			"pagination"	 => array("pageSize" => 50),
			'sort'			 => array('defaultOrder' => 'csrId DESC')
		));
		return $dataprovider;
	}

	/**
	 * gets currently Serving CBR create date
	 * @param type $queueType
	 * @return type date
	 */
	public static function currentlyServingCBR($queueType = 0)
	{
		$returnSet = Yii::app()->cache->get('currentlyServingCBR_' . $queueType);
		if ($returnSet === false)
		{
			$param = array();
			if ($queueType > 0)
			{
				$param['queueType']	 = $queueType;
				$where				 = " AND scq_follow_up_queue_type=:queueType ";
			}
			$sql		 = "SELECT scq_create_date
                        FROM `service_call_queue`
                        WHERE 1
                        AND scq_status IN (1, 3)
                        AND scq_assigned_uid IS NOT NULL
                        AND scq_create_date >= '2021-01-01 00:00:00'
                        AND scq_active=1
                        $where
                        ORDER BY scq_assigned_date_time DESC
                        LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param, 60, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('currentlyServingCBR_' . $queueType, $returnSet, 60);
		}
		return $returnSet;
	}

	/**
	 * gets the count of all active call back request
	 * @return type int
	 */
	public static function countAllActiveCBR()
	{
		$returnSet = Yii::app()->cache->get('countAllActiveCBR');
		if ($returnSet === false)
		{
			$sql		 = "SELECT  COUNT(*) as active_cbr_count FROM `service_call_queue` WHERE 1 AND scq_follow_up_queue_type != 15 AND scq_status IN (1,3) AND scq_active=1 AND scq_create_date >='2021-01-01 00:00:00'";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 60, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countAllActiveCBR', $returnSet, 60);
		}
		return $returnSet;
	}

	/**
	 * gets the data for  internal call back request
	 * @param int $csrTeam teamid
	 * @param int $csr admin id
	 * @param bool $isDue24
	 * @param bool $name $ownFollowup
	 * @return CSqlDataProvider
	 */
	public function getInternals($isDue24 = 0, $search = null, $isFollowUpOpen = 1, $command = false)
	{

		$params	 = [];
		$where	 = "";
		if ($this->requestedBy > 0)
		{
			switch ($this->requestedBy)
			{
				case 1:
					$where	 .= " AND scq_created_by_type=$this->requestedBy AND scq_created_by_uid=$this->custId  ";
					break;
				case 2:
					$where	 .= " AND scq_created_by_type=$this->requestedBy AND scq_created_by_uid=$this->vendId  ";
					break;
				case 3:
					$where	 .= " AND scq_created_by_type=$this->requestedBy AND scq_created_by_uid=$this->drvId  ";
					break;
				case 4:
					$where	 .= " AND scq_created_by_type=$this->requestedBy AND scq_created_by_uid=$this->adminId  ";
					break;
				case 5:
					$where	 .= " AND scq_created_by_type=$this->requestedBy AND scq_created_by_uid=$this->agntId  ";
					break;

				default:
					break;
			}
		}
		if ($this->scq_to_be_followed_up_by_type > 0)
		{
			switch ($this->scq_to_be_followed_up_by_type)
			{
				case 1:
					$where	 .= " AND scq_to_be_followed_up_by_type=$this->scq_to_be_followed_up_by_type AND scq_to_be_followed_up_by_id=$this->scq_to_be_followed_up_by_id  ";
					break;
				case 2:
					$where	 .= " AND scq_to_be_followed_up_by_type=$this->scq_to_be_followed_up_by_type AND scq_to_be_followed_up_by_id=$this->isGozen  ";
					break;
				default:
					break;
			}
		}

		if ($isFollowUpOpen == 1)
		{
			$whereDue24	 = $isDue24 == 1 ? " AND scq_follow_up_date_time < DATE_ADD(NOW(),INTERVAL 24 HOUR)" : "";
			$where		 .= " AND scq_status IN (1,3) ";
		}
		else
		{
			$where .= " AND scq_status IN (2) ";
		}


		if ($search != null)
		{
			DBUtil::getLikeStatement($search, $bindString1, $params1);
			DBUtil::getLikeStatement($search, $bindString2, $params2);
			DBUtil::getLikeStatement($search, $bindString3, $params3);
			$params	 = array_merge($params, $params1, $params2, $params3);
			$where	 .= " AND ( scq_id LIKE $bindString1 OR scq_related_bkg_id LIKE $bindString2 OR scq_creation_comments LIKE $bindString3) ";
		}
		$sql = "SELECT service_call_queue.*,ctt.ctt_id AS cttId,admins.gozen,
							CASE
							WHEN scq_to_be_followed_up_with_entity_type = 1 THEN 'Customer'
							WHEN scq_to_be_followed_up_with_entity_type = 2 THEN 'Vendor'
							WHEN scq_to_be_followed_up_with_entity_type = 3 THEN 'Driver'
							WHEN scq_to_be_followed_up_with_entity_type = 4 THEN 'Admin'
							WHEN scq_to_be_followed_up_with_entity_type = 5 THEN 'Agent'
							WHEN scq_to_be_followed_up_with_entity_type = 6 THEN 'Corporate'
							END AS callerType,
							ctt.ctt_name AS contactName
							FROM service_call_queue
							LEFT JOIN contact ctt ON ctt.ctt_id = scq_to_be_followed_up_with_contact AND ctt.ctt_active = 1
							LEFT JOIN contact_profile cr ON  ctt.ctt_id=cr.cr_contact_id
							LEFT JOIN admins  ON  adm_id=scq_to_be_followed_up_by_id  and scq_to_be_followed_up_by_type=2
							WHERE 1 AND scq_follow_up_queue_type=9  AND scq_active=1   $where $whereDue24   GROUP BY scq_id    ";

		if (!$command)
		{
			$count			 = DBUtil::queryScalar(" SELECT COUNT(*) FROM ($sql ) abc", DBUtil::MDB(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'params'		 => $params,
				'db'			 => DBUtil ::MDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes' => ['scq_create_date'], 'defaultOrder' => 'scq_create_date  DESC'],
				'pagination'	 => ['pageSize' => 200],
			]);

			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql . " ORDER BY scq_create_date  DESC", DBUtil::MDB(), $params);
		}
	}

	/**
	 * this function is used to check active service call queue by using phone number
	 * @param int $phone teamid
	 * @param int $refType
	 * @return int
	 */
	public static function checkActiveCallbackByContactNumber($phone, $refType = 0)
	{
		$params	 = ['phone' => $phone];
		$qrySTr	 = '';
		if ($refType > 0)
		{
			$params['refType']	 = $refType;
			$qrySTr				 = ' AND scq_follow_up_queue_type=:refType ';
		}
		$sql = "SELECT  scq_id
				FROM service_call_queue
				WHERE scq_to_be_followed_up_with_type = 2 AND scq_active=1 AND  SUBSTRING(scq_to_be_followed_up_with_value,-10) = SUBSTRING(:phone,-10) AND LENGTH(scq_to_be_followed_up_with_value) > 9  AND scq_status IN (1,3) $qrySTr";
		return DBUtil::queryScalar($sql, null, $params);
	}

	/**
	 * this function is used to check active service call queue by using contact id
	 * @param int $contactId
	 * @param int $refType
	 * @param int $phone
	 * @return int
	 */
	public static function checkActiveCallbackByContactId($contactId, $refType = 0, $phone = '')
	{
		$params	 = ['contactId' => $contactId, 'phone' => $phone];
		$qrySTr	 = '';
		if ($refType > 0)
		{
			$params['refType']	 = $refType;
			$qrySTr				 = ' AND scq_follow_up_queue_type=:refType ';
		}

		$sql = "SELECT    scq_id
				FROM     service_call_queue
				WHERE    (
							(scq_to_be_followed_up_with_value =:contactId AND scq_to_be_followed_up_with_type = 1  )
							 OR
                            (scq_to_be_followed_up_with_contact =:contactId)
                             OR
							(scq_to_be_followed_up_with_value=:phone AND ''<>:phone AND scq_to_be_followed_up_with_type = 2)
                         ) AND scq_status IN (1,3) AND scq_active=1 $qrySTr";
		return DBUtil::queryScalar($sql, null, $params);
	}

	/**
	 * this function is used to setting the data for new queue service call queue  from dialer module
	 * @param array  $req
	 * @return ReturnSet
	 */
	public static function setNewCallBackData($req)
	{
		$message	 = 'There is an existing callback request for you. We will call you back shortly.';
		$returnSet	 = new ReturnSet();
		try
		{
			$callerNumber	 = $req['callerNumber'];
			$userId			 = $req['userId'];
			$refType		 = 1;
			$success		 = false;
			$phone			 = $callerNumber;
			$phone			 = trim(str_replace(' ', '', $phone));
			$phone			 = preg_replace('/[^0-9\-]/', '', $phone);
			if (!Filter::validatePhoneNumber($phone))
			{
				throw new Exception('Invalid phone number', ReturnSet::ERROR_INVALID_DATA);
			}
			$contactId	 = $userId > 0 ? ContactProfile::getByUserId($userId) : ContactPhone::getContactid($phone);
			$followupId	 = !$contactId ? ServiceCallQueue::checkActiveCallbackByContactNumber($phone) : ServiceCallQueue::checkActiveCallbackByContactId($contactId);
			if ($followupId > 0)
			{
				$returnSet->setMessage($message);
				goto skipNewAdd;
			}
			Filter::parsePhoneNumber($phone, $code, $number);
			$model										 = new ServiceCallQueue();
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			$model->scq_follow_up_queue_type			 = $refType;
			$model->scq_created_by_uid					 = $userId;
			$model->scq_to_be_followed_up_with_value	 = $code . $number;
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = $userId;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_by_type		 = 1;
			$model->scq_to_be_followed_up_by_id			 = Teams::getTeamIdFromCached($model->scq_follow_up_queue_type);
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PlatForm_DIRECT_CALL);
			$dt											 = $returnSet->getData();
			$followupId									 = $dt['followupId'];
			skipNewAdd:
			$data										 = [];
			if ($followupId > 0)
			{
				$fpModel	 = ServiceCallQueue::model()->findByPk($followupId);
				$queueData	 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
				$queNo		 = $queueData['queNo'];
				$uniqueCode	 = $fpModel->scq_unique_code;
				$waitTime	 = $queueData['waitTime'];
				$data		 = ['uniqueCode' => $uniqueCode, 'followupId' => $followupId, 'queNo' => $queNo, 'waitTime' => $waitTime];
				$success	 = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			
		}
		skipAll:
		return $returnSet;
	}

	/**
	 * this function is used to setting the data for new queue service call queue  from dialer module
	 * @param array  $req
	 * @return ReturnSet
	 */
	public static function setVendorCallBackData($req)
	{
		$message	 = 'There is an existing callback request for you. We will call you back shortly.';
		$returnSet	 = new ReturnSet();
		try
		{
			$callerNumber	 = $req['callerNumber'];
			$vendorId		 = $req['vendorId'];
			$refType		 = 2;
			$success		 = false;
			$phone			 = $callerNumber;
			$phone			 = trim(str_replace(' ', '', $phone));
			$phone			 = preg_replace('/[^0-9\-]/', '', $phone);
			if (!Filter::validatePhoneNumber($phone))
			{
				throw new Exception('Invalid phone number', ReturnSet::ERROR_INVALID_DATA);
			}
			$contactId	 = ($vendorId > 0) ? ContactProfile::getByVndId($vendorId) : ContactPhone::getContactid($phone);
			$followupId	 = !$contactId ? ServiceCallQueue::checkActiveCallbackByContactNumber($phone) : ServiceCallQueue::checkActiveCallbackByContactId($contactId);
			if ($followupId > 0)
			{
				$returnSet->setMessage($message);
				goto skipNewAdd;
			}
			Filter::parsePhoneNumber($phone, $code, $number);
			$model										 = new ServiceCallQueue();
			$model->scq_created_by_type					 = UserInfo::TYPE_VENDOR;
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_EXISTING_VENDOR;
			$model->scq_created_by_uid					 = $vendorId;
			$model->scq_to_be_followed_up_with_value	 = $code . $number;
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = $vendorId;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_by_type		 = 1;
			$model->scq_to_be_followed_up_by_id			 = Teams::getTeamIdFromCached($model->scq_follow_up_queue_type);
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PlatForm_DIRECT_CALL);
			$dt											 = $returnSet->getData();
			$followupId									 = $dt['followupId'];
			skipNewAdd:
			$data										 = [];
			if ($followupId > 0)
			{
				$fpModel	 = ServiceCallQueue::model()->findByPk($followupId);
				$queueData	 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
				$queNo		 = $queueData['queNo'];
				$uniqueCode	 = $fpModel->scq_unique_code;
				$waitTime	 = $queueData['waitTime'];
				$data		 = ['uniqueCode' => $uniqueCode, 'followupId' => $followupId, 'queNo' => $queNo, 'waitTime' => $waitTime];
				$success	 = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		skipAll:
		return $returnSet;
	}

	/**
	 * this function is used to count the waiting time by queue type  for service call queue
	 * @param int   $refType
	 * @return int
	 */
	public static function countWaitingByReftype($refType)
	{
		$params	 = ['refType' => $refType];
		$sql	 = "SELECT count(distinct scq_id)
					FROM service_call_queue
					WHERE scq_follow_up_queue_type = :refType 
					AND scq_follow_up_date_time<=NOW() AND scq_status IN (1,3)";
		return DBUtil::queryScalar($sql, null, $params);
	}

	/**
	 * this function is used to calculate  the waiting time by queue type  for service call queue
	 * @param int   $refType
	 * @param int   $queNo
	 * @return float
	 */
	public static function calculateWaitingTimeByReftype($refType, $queNo = 0)
	{
		Logger::create('queNoval:' . $queNo);
		$durationHour		 = 4;
		$totalWaitingLeads	 = ($queNo > 0) ? $queNo : self::countWaitingByReftype($refType);
		$medianCallDuration	 = self::getMedianCallDurationbyRef($refType);
		$totalCSRonline		 = self::getOnlineCSRByRef($refType, $durationHour);
		if ($totalCSRonline == 0)
		{
			$totalCSRonline = self::getTotalCallingCSRByRef($refType, $durationHour);
		}
		if ($totalCSRonline > 0)
		{
			$waittime	 = round($medianCallDuration * $totalWaitingLeads / $totalCSRonline);
			$waittime	 = ($waittime < 2) ? 2 : $waittime;
		}
		else
		{
			$waittime = 60;
		}
		return $waittime;
	}

	/**
	 * this function is used to get median time by queue type  for service call queue
	 * @param int   $refType
	 * @return float
	 */
	public static function getMedianCallDurationbyRef($refType)
	{
		$medianCallDurationJson	 = Config::get('CMB.call.stats');
		$medianCallDurationArr	 = json_decode($medianCallDurationJson, true);
		return $medianCallDurationArr[$refType];
	}

	/**
	 * this function is used count no.of online "CSR"  by queue type
	 * @param int   $refType
	 * @return float
	 */
	public static function getOnlineCSRByRef($reftype, $durationHour = 1)
	{
		$teamid	 = Teams::getTeamIdFromCached($reftype);
		$params	 = array('teamid' => $teamid, 'duration' => $durationHour);
		$sql	 = 'SELECT
					COUNT(DISTINCT  adm_id) as cnt
					FROM admins adm
					JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id AND  adm.adm_active=1
					JOIN
					(
						SELECT  ado_admin_id, ado_status, ado_time
						FROM `admin_onoff`
						WHERE 1 AND ado_status = 1 AND
						ado_time > DATE_SUB(NOW(),INTERVAL :duration HOUR)
						GROUP BY ado_admin_id
						ORDER BY ado_time desc
					) aadmin_onoff on aadmin_onoff.ado_admin_id = adm.adm_id
					 JOIN
					(
						SELECT
						admin_profiles.adp_adm_id,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT("$[", pseudo_rows.row, "].cdtWeight"))) AS cdtWeight,
						JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT("$[", pseudo_rows.row, "].cdtId"))) AS cdtId
						FROM admin_profiles
						JOIN pseudo_rows
						WHERE 1
						HAVING cdtId IS NOT NULL
					) temp ON temp.adp_adm_id=adp.adp_adm_id
					JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
					JOIN teams ON cdt.cdt_tea_id = teams.tea_id
					WHERE  teams.tea_id =:teamid
					AND
					(
						(tea_start_time IS NULL AND tea_stop_time IS NULL)
							OR
						(tea_start_time < tea_stop_time AND CURRENT_TIME() BETWEEN tea_start_time AND tea_stop_time)
							OR
						(tea_stop_time < tea_start_time AND CURRENT_TIME() < tea_start_time AND CURRENT_TIME() < tea_stop_time)
							OR
						(tea_stop_time < tea_start_time AND CURRENT_TIME() > tea_start_time)
					)
					ORDER BY temp.cdtWeight ASC LIMIT 0,1';
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * this function is used count calling  count  by queue type
	 * @param int   $refType
	 * @return float
	 */
	public static function getTotalCallingCSRByRef($reftype, $durationHour = 1)
	{
		$params	 = array('refType' => $reftype, 'duration' => $durationHour);
		$sql	 = 'SELECT
					COUNT(DISTINCT scq_assigned_uid)
					FROM  service_call_queue
					WHERE  scq_follow_up_queue_type = :refType AND scq_active=1  AND scq_status IN (1,3) AND scq_follow_up_date_time > DATE_SUB(NOW(),INTERVAL :duration HOUR)';
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * this function is used get queue number for service call queue
	 * @param int   $refType
	 * @param int   $fwpId
	 * @return float
	 */
	public static function getQueueNumber($fwpId, $refType = 0)
	{
		$queNo = 0;
		if ($fwpId > 0)
		{
			$data		 = ServiceCallQueue::getActiveWaitingTimeById($fwpId);
			$queNo		 = $data['rank'] | 0;
			$waitTime	 = $data['totalWaitMinutes'];
			$scq_active	 = (int) $data['scq_active'] | 0;
		}
//		
//		Logger::setModelCategory(__CLASS__, __FUNCTION__);
//		Logger::create('$fwpId:' . $fwpId . ' :: $refType:' . $refType);
//		$params = ['refType' => $refType, 'scq_id' => $fwpId];
//
//		$sql = "SELECT scq_id,
//			scq_follow_up_date_time,scq_active,
//			rank FROM
//			(
//				SELECT scq_id, scq_follow_up_date_time,scq_active,
//				rank() OVER (  ORDER by scq_follow_up_date_time
//			) AS 'rank'
//			FROM `service_call_queue`
//			WHERE `scq_follow_up_date_time` <= NOW() AND scq_active=1 
//				AND scq_status IN (1,3) AND scq_follow_up_queue_type=:refType ) a 
//			WHERE scq_id = :scq_id";
//
//		$row		 = DBUtil ::queryRow($sql, null, $params);
//		Logger::create('$row:' . json_encode($row));
//		$queNo		 = 0;
//		$scq_active	 = 0;
//		if($row)
//		{
//			$queNo		 = (int) $row['rank'];
//			$scq_active	 = (int) $row['scq_active'];
//		}
//		Logger::create('queNosupplied:' . $queNo);
//		$waitTime = self::calculateWaitingTimeByReftype($refType, $queNo);

		if ($queNo == 0)
		{
			$waitTime = 2;
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return ['queNo' => $queNo, 'waitTime' => $waitTime, 'scq_active' => $scq_active];
	}

	/**
	 *  this function is used to setting the data for existing  queue service call queue  from dialer module
	 * @param int $refType
	 * @param int $fwpId
	 * @return float
	 */
	public static function setExistingCallBackData($req)
	{
		$returnSet = new ReturnSet();
		try
		{
			$refType		 = ServiceCallQueue::TYPE_EXISTING_BOOKING;
			$bkgID			 = $req['bkgID'];
			$callerNumber	 = $req['callerNumber'];
			$userNumber		 = $req['userNumber'];
			$force			 = false;
			if (trim($userNumber) == '')
			{
				$userNumber = $callerNumber;
			}
			$success = false;
			$phone	 = $userNumber;
			if (!Filter::validatePhoneNumber($phone))
			{
				throw new Exception('Invalid phone number', ReturnSet::ERROR_VALIDATION);
			}
			Filter::parsePhoneNumber($phone, $code, $number);
			Filter::parsePhoneNumber($callerNumber, $callCode, $callnumber);
			$phoneNumber = $callCode . $callnumber;
			if ($force)
			{
				goto skipValidate;
			}
			if (strlen($bkgID) < 7)
			{
				throw new Exception('At least last 7 digits of the booking id is required.', ReturnSet::ERROR_VALIDATION);
			}
			$bookingId = BookingSub::getbyBookingLastDigits($bkgID, 6);
			if (!$bookingId)
			{
				throw new Exception('Booking not found.', ReturnSet::ERROR_VALIDATION);
			}
			$contactData = BookingUser::verifyBookingContact($bookingId, $number);
			if (!$contactData)
			{
				throw new Exception('Number not  linked with the booking', ReturnSet::ERROR_VALIDATION);
			}
			$bookingCode = $contactData['bkg_booking_id'];
			$bkgStatus	 = $contactData['bkg_status'];
			$contactId	 = $contactData['callerContactId'];
			$followupId	 = ServiceCallQueue::checkActiveCallbackByContactId($contactId, $refType, $phoneNumber);
			if ($followupId > 0)
			{
				$returnSet->setMessage('There is an existing callback request for you. We will call you back shortly.');
				goto skipNewAdd;
			}
			skipValidate:
			$model										 = new ServiceCallQueue();
			$model->scq_created_by_uid					 = $contactData['bkg_user_id'];
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			$model->scq_follow_up_queue_type			 = $refType;
			$model->scq_to_be_followed_up_with_value	 = $phoneNumber;
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = $contactData['bkg_user_id'];
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_by_type		 = 1;
			$model->scq_to_be_followed_up_by_id			 = Teams::getTeamIdFromCached($model->scq_follow_up_queue_type);
			if ($force)
			{
				$model->scq_creation_comments = "Customer had provide this as booking id: $bkgID";
			}

			if (!empty($bookingCode))
			{
				if (!in_array($bkgStatus, [2, 3, 5, 6, 7, 9]) && $refType == 2)
				{
					$model->scq_follow_up_queue_type		 = 1;
					$model->scq_to_be_followed_up_by_type	 = 1;
					$model->scq_to_be_followed_up_by_id		 = Teams::getTeamIdFromCached($model->scq_follow_up_queue_type);
				}
				$model->scq_related_bkg_id = $bookingCode;
			}
			$returnSet	 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PlatForm_DIRECT_CALL);
			$dt			 = $returnSet->getData();
			$followupId	 = $dt['followupId'];
			skipNewAdd:
			$data		 = [];
			if ($followupId > 0)
			{
				$fpModel	 = ServiceCallQueue::model()->findByPk($followupId);
				$queueData	 = self::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
				$queNo		 = $queueData['queNo'];
				$waitTime	 = $queueData['waitTime'];
				$uniqueCode	 = $fpModel->scq_unique_code;
				$data		 = ['uniqueCode' => $uniqueCode, 'followupId' => $followupId, 'queNo' => $queNo, 'waitTime' => $waitTime];
				$success	 = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		skipAll:
		return $returnSet;
	}

	/**
	 * gets the count of internal active call back request
	 * @return type int
	 */
	public static function countInternalActiveCBR()
	{
		$csr		 = UserInfo::getUserId();
		$returnSet	 = Yii::app()->cache->get('countInternalActiveCBR_' . $csr);
		if ($returnSet === false)
		{
			$csrTeam	 = Admins::getTeamid($csr);
			$params		 = ['team_id' => $csrTeam, 'csr' => $csr];
			$where		 = "  AND ((scq_to_be_followed_up_by_type=1 AND scq_to_be_followed_up_by_id = :team_id) OR (scq_to_be_followed_up_by_type=2 AND scq_to_be_followed_up_by_id = :csr))";
			$sql		 = "SELECT  COUNT(*) as active_cbr_count FROM `service_call_queue`
					WHERE 1 AND scq_status IN (1,3)
                    AND scq_follow_up_queue_type=9
					AND scq_create_date >='2021-01-01 00:00:00'
					AND scq_active=1
					AND scq_follow_up_date_time  < DATE_ADD(NOW(),INTERVAL 24 HOUR) $where";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil ::SDB(), $params, 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countInternalActiveCBR_' . $csr, $returnSet, 600);
		}
		return $returnSet;
	}

	/**
	 * gets the count of internal active call back request by admin
	 * @return type int
	 */
	public static function countInternalActiveCBRbyAdminID()
	{
		$csr	 = UserInfo::getUserId();
		$params	 = ['adm_id' => $csr];
		$where	 = " AND scq_to_be_followed_up_by_id = :adm_id AND scq_to_be_followed_up_by_type=2";
		$sql	 = "SELECT  COUNT(*) as active_cbr_count_byId FROM `service_call_queue`
					WHERE 1 AND scq_status IN (1,3)  AND scq_follow_up_queue_type=9
					 AND scq_create_date >='2021-01-01 00:00:00'
					 AND scq_active=1
				   AND scq_follow_up_date_time  < DATE_ADD(NOW(),INTERVAL 24 HOUR)$where";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * gets the count of internal active call back request by team
	 * @return type int
	 */
	public static function countInternalActiveCBRbyTeam()
	{
		$csr	 = UserInfo::getUserId();
		$csrTeam = Admins::getTeamid($csr);
		$params	 = ['team_id' => $csrTeam];
		$where	 = " AND scq_to_be_followed_up_by_id = :team_id";
		$sql	 = "SELECT  COUNT(*) as active_cbr_count_byId FROM `service_call_queue`
					WHERE 1 AND scq_status IN (1,3) AND scq_follow_up_queue_type=9
					AND scq_create_date >='2021-01-01 00:00:00'
					AND scq_active=1
				    AND scq_follow_up_date_time  < DATE_ADD(NOW(),INTERVAL 24 HOUR)$where";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * update median call durations
	 * @return type int
	 */
	public static function updateCallingDurationMedian()
	{
		$data		 = self::getCallingDurationMedian();
		$jsonData	 = json_encode($data);
		if (trim($jsonData) != '')
		{
			$param	 = ['val' => $jsonData];
			$sql	 = "UPDATE config set cfg_value=:val WHERE cfg_name='CMB.call.stats'";
			DBUtil::command($sql)->execute($param);
		}
	}

	/**
	 * get median call durations
	 * @return type int
	 */
	public static function getCallingDurationMedian()
	{
		$refTypeArr	 = self::getReasonList();
		$data		 = [];
		foreach ($refTypeArr as $reftype => $val)
		{
			$param			 = ['refType' => $reftype];
			$sql			 = "SELECT  ROUND(AVG(g.callDuration)) medVal from (
				SELECT  scq_id ,scq_follow_up_queue_type,scq_follow_up_date_time,scq_assigned_date_time ,
					@rownum := @rownum + 1 as row_number,
					((timestampdiff(MINUTE,scq_assigned_date_time,scq_follow_up_date_time))+1)  callDuration
					FROM `service_call_queue`
					cross join (select @rownum := 0) r
						WHERE `scq_assigned_date_time` > date_sub(NOW(), INTERVAL 24 HOUR) AND scq_status IN (1,3)

						AND scq_assigned_date_time IS NOT NULL
						AND scq_follow_up_date_time IS NOT NULL
						AND scq_follow_up_queue_type=:refType
						AND scq_active=1
						ORDER by  callDuration
				) as g
				WHERE g.row_number IN (FLOOR((@rownum+1) / 2) , CEIL((@rownum+1) / 2))";
			$data[$reftype]	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
		}
		return $data;
	}

	public static function FollowupBackupScript($fwpId, $algId)
	{
		$params	 = ["fwpId" => $fwpId];
		$sql	 = "  SELECT
				fwp_created as scq_create_date,
				CASE
					WHEN fwp_platform = 1 AND fwp_ref_type=3 THEN 2
					WHEN fwp_platform = 1 AND fwp_ref_type IN (1,2) THEN 1
					WHEN fwp_platform = 1 THEN 1
					WHEN fwp_platform = 2 THEN 1
					WHEN fwp_platform = 3 THEN 2
					WHEN fwp_platform = 4 THEN 1
					WHEN fwp_platform = 7 THEN 4
					ELSE 1
				END  AS  scq_created_by_type,
				CASE
					WHEN fwp_platform=7 THEN fwp_user_ref_id
					WHEN fwp_platform<>7 AND fwp_ref_type IN (1,2,3) THEN IFNULL(cp.cr_is_consumer, cp1.cr_is_consumer)
					WHEN fwp_platform<>7 AND fwp_ref_type IN (4) THEN IFNULL(cp.cr_is_vendor, cp1.cr_is_vendor)
					ELSE 0
				END AS scq_created_by_uid,
				alg_closed_at AS scq_disposition_date,
				IF(fwp_status=1 and fwp_follow_up_status=4,fwp_assigned_csr,null) AS scq_disposed_by_uid,
				IF(fwp_team_id IS NULL,2,1) AS scq_to_be_followed_up_by_type,
				fwp_team_id AS scq_to_be_followed_up_by_id,
				IF(fwp_contact_type=1,1,2) AS scq_to_be_followed_up_with_type,
				IF(fwp_contact_type=2,fwp_contact_phone_no,  IF ( fwp_contact_id IS NOT NULL , fwp_contact_id,0)) AS scq_to_be_followed_up_with_value,
				fwp_call_entity_type AS scq_to_be_followed_up_with_entity_type,
				fwp_contact_id AS  scq_to_be_followed_up_with_contact,
				CASE
					WHEN fwp_ref_type IN (1,2,3) THEN IFNULL(cp.cr_is_consumer, cp1.cr_is_consumer)
					WHEN fwp_ref_type IN (4) THEN IFNULL(cp.cr_is_vendor, cp1.cr_is_vendor)
					ELSE IFNULL(cp.cr_is_consumer,cp1.cr_is_consumer)
				END AS scq_to_be_followed_up_with_entity_id,
				-1 AS scq_to_be_followed_up_with_entity_rating,
				fwp_prefered_time AS   scq_follow_up_date_time,
				1 AS scq_follow_up_priority,
				fwp_desc AS scq_creation_comments,
				fwp_csr_remarks AS scq_disposition_comments,
				fwp_ref_type AS scq_follow_up_queue_type,
				fwp_ref_id AS scq_related_bkg_id,
				alg_adm_user_id scq_assigned_uid,
				alg_created AS  scq_assigned_date_time,
				IF(fwp_status=1 and fwp_follow_up_status=4,2,if(fwp_status=0,0,1)) AS  scq_status,
				fwp_parent_id AS  scq_prev_or_originating_followup,
				TIMESTAMPDIFF(MINUTE, fwp_created, IFNULL(alg_created,NOW())) AS scq_time_since_create,
				NULL AS scq_time_to_pickup,
				NULL AS scq_value_non_admin_contact_id,
				fwp_platform AS scq_platform,
				0 AS scq_reason_id,
				0 AS scq_priority_score,
                fwp_unique_code AS scq_unique_code
				FROM follow_ups
				LEFT JOIN assign_log ON fwp_id=alg_ref_id AND alg_ref_type=3
				LEFT JOIN contact_profile cp ON cp.cr_contact_id=fwp_contact_id AND cp.cr_status=1
				LEFT JOIN contact c1 ON cr_contact_id=c1.ctt_id
				LEFT JOIN contact c2 ON c1.ctt_ref_code=c2.ctt_id
				LEFT JOIN contact_profile cp1 ON cp1.cr_contact_id=c2.ctt_id AND cp1.cr_status=1
				WHERE fwp_id=:fwpId ";
		if ($algId != "")
		{
			$sql			 .= " AND alg_id=:algId";
			$params["algId"] = $algId;
		}
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	public static function AssgnLogBackupScript($algId)
	{
		$params["algId"] = $algId;
		$sql			 = "  SELECT
				alg_created AS scq_create_date,
				1 AS  scq_created_by_type,
				IF(alg_ref_type=1, IF(booking_temp.bkg_user_id IS NOT NULL, booking_temp.bkg_user_id,0)  ,if(booking_user.bkg_user_id IS NOT NULL, booking_user.bkg_user_id,0)) AS scq_created_by_uid,
				alg_closed_at AS scq_disposition_date,
				IF(alg_active = 1 AND alg_status = 0, alg_adm_user_id, null) AS scq_disposed_by_uid,
				1 AS scq_to_be_followed_up_by_type,
				CASE
					WHEN alg_event_id = 100 THEN 1
					WHEN alg_event_id = 101 THEN 5
					WHEN alg_event_id = 102 THEN 3
					WHEN alg_event_id = 103 THEN 9
					WHEN alg_event_id = 104 THEN 9
				ELSE 1
				END  AS scq_to_be_followed_up_by_id,
				2 AS scq_to_be_followed_up_with_type,
				IF(alg_ref_type=1, IF(booking_temp.bkg_contact_no IS NOT NULL, booking_temp.bkg_contact_no, 0),
				IF(booking_user.bkg_contact_no IS NOT NULL, booking_user.bkg_contact_no, 0)) AS scq_to_be_followed_up_with_value,
				1 AS scq_to_be_followed_up_with_entity_type,
                cp1.cr_contact_id AS scq_to_be_followed_up_with_contact,
				cp1.cr_is_consumer AS scq_to_be_followed_up_with_entity_id,
				-1 AS scq_to_be_followed_up_with_entity_rating,
				alg_created AS scq_follow_up_date_time,
				1 AS scq_follow_up_priority,
				alg_desc  AS scq_creation_comments,
	            alg_notes AS  scq_disposition_comments,
				CASE
				  WHEN alg_event_id = 100 THEN 1
				  WHEN alg_event_id = 101 THEN 2
				  WHEN alg_event_id = 102 THEN 3
				  WHEN alg_event_id = 103 THEN 12
				  WHEN alg_event_id = 104 THEN 4
				ELSE 1
				END  AS scq_follow_up_queue_type,
				if(alg_ref_type = 1, alg_ref_id, 0)   AS scq_related_lead_id,
				if(alg_ref_type = 2, alg_ref_id, 0)  AS scq_related_bkg_id,
				alg_adm_user_id  AS scq_assigned_uid,
				alg_created      AS scq_assigned_date_time,
				IF(alg_active = 1 AND alg_status = 1, 1, 2) AS scq_status,
				NULL AS scq_prev_or_originating_followup,
				TIMESTAMPDIFF(MINUTE, IF(alg_ref_type=1, booking_temp.bkg_create_date, booking.bkg_create_date), alg_created) AS scq_time_since_create,
				TIMESTAMPDIFF(MINUTE, IF(alg_ref_type=1, booking_temp.bkg_pickup_date, booking.bkg_pickup_date), alg_created) AS scq_time_to_pickup,
				NULL AS scq_value_non_admin_contact_id,
               IF ( alg_ref_type=1,

                  CASE
					WHEN booking_temp.bkg_platform = 1 THEN 1
					WHEN booking_temp.bkg_platform = 2 THEN 7
					WHEN booking_temp.bkg_platform = 3 THEN 4
				  ELSE 1
                    END ,
                CASE
					WHEN booking_trail.bkg_platform = 1 THEN 1
					WHEN booking_trail.bkg_platform = 2 THEN 7
					WHEN booking_trail.bkg_platform = 3 THEN 4
					WHEN booking_trail.bkg_platform = 4 THEN 1
					WHEN booking_trail.bkg_platform = 5 THEN 1
					WHEN booking_trail.bkg_platform = 6 THEN 0
				ELSE 1
                 END
                ) AS scq_platform,
				0 AS scq_reason_id,
				0 AS scq_priority_score
				FROM assign_log
				LEFT JOIN booking_temp on assign_log.alg_ref_id = booking_temp.bkg_id and alg_ref_type=1
				LEFT JOIN booking on assign_log.alg_ref_id = booking.bkg_id and alg_ref_type=2
				LEFT JOIN booking_user on booking_user.bui_bkg_id = booking.bkg_id
	            LEFT JOIN booking_trail on booking_trail.btr_bkg_id = booking.bkg_id
				LEFT JOIN contact_profile cp ON IF(booking_user.bkg_contact_id IS NULL, booking_user.bkg_user_id=cp.cr_is_consumer ,cp.cr_contact_id=booking_user.bkg_contact_id)  AND cp.cr_status=1
				LEFT JOIN contact_profile cp0 ON cp0.cr_is_consumer=booking_temp.bkg_user_id AND cp0.cr_status=1
				LEFT JOIN contact c1 ON IF(alg_ref_type=1, cp0.cr_contact_id, cp.cr_contact_id)=c1.ctt_id
				LEFT JOIN contact c2 ON c1.ctt_ref_code=c2.ctt_id
				LEFT JOIN contact_profile cp1 ON cp1.cr_contact_id=c2.ctt_id AND cp1.cr_status=1
				WHERE 1 and alg_ref_type in (1,2) AND alg_id=:algId";
		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	public static function SCqBackupScript()
	{
		$i	 = 0;
//		while (true)
//		{
//			$sql = "SELECT alg_id, fwp_id FROM follow_ups LEFT JOIN assign_log ON fwp_id=alg_ref_id AND alg_ref_type=3 AND fwp_status=1 AND alg_active=1 ORDER BY fwp_id DESC LIMIT $i, 2000";
//			$i	 += 2000;
//			Logger::info($sql);
//			$res = DBUtil::query($sql, DBUtil::SDB());
//			if ($res->getRowCount() == 0)
//			{
//				break;
//			}
//			Logger::info(count($res));
//			foreach ($res as $row)
//			{
//				$result = self::FollowupBackupScript($row['fwp_id'], $row['alg_id']);
//				foreach ($result as $rows)
//				{
//					try
//					{
//						Logger::info($row['fwp_id']);
//						$model						 = new ServiceCallQueue();
//						$model->contactRequired		 = 0;
//						$model->subQueue			 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
//						$model->scq_additional_param = json_encode(array('alg_id' => $row['alg_id'], 'fwp_id' => $row['fwp_id']));
//						$model->attributes			 = $rows;
//						if (!$model->save())
//						{
//							throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
//						}
//						break;
//					}
//					catch (Exception $ex)
//					{
//						Logger::exception($ex);
//						Logger::writeToConsole($ex->getMessage());
//					}
//				}
//			}
//			265623
//		}
		$i	 = 0;
		while (true)
		{
			$sql = "SELECT alg_id,alg_ref_type  FROM assign_log WHERE alg_ref_type IN (1,2) AND alg_active=1 AND alg_id<257273 ORDER BY  alg_id DESC LIMIT $i, 2000";
			$res = DBUtil::query($sql, DBUtil::SDB());
			$i	 += 2000;
			if ($res->getRowCount() == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				$result = self::AssgnLogBackupScript($row['alg_id']);
				foreach ($result as $rows)
				{
					try
					{
						$model						 = new ServiceCallQueue();
						$model->contactRequired		 = 0;
						$model->scq_additional_param = json_encode(array('alg_id' => $row['alg_id']));
						$model->scq_ref_type		 = $row['alg_ref_type'];
						$model->attributes			 = $rows;
						if (!$model->save())
						{
							throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
						}
						break;
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
						Logger::writeToConsole($ex->getMessage());
					}
				}
			}
		}
	}

	/**
	 * generate unique  code for service call request
	 * @return type string
	 */
	public static function generateUniqueCode($scq_id)
	{
		$strid	 = str_pad($scq_id, 7, 0, STR_PAD_LEFT);
		$strCode = date('ymd') . rand(100, 999) . $strid;
		return $strCode;
	}

	public static function updatePendingLeads($csr, $type, $unverifiedAccess = 1, $newAccess = 1, $highValueAccess = 1)
	{
		$limit	 = 0;
		$model	 = new ServiceCallQueue();
		while (true)
		{
			$success = false;
			$rows	 = BookingTemp::getPendingLeads($csr, $limit, $type, $unverifiedAccess, $newAccess, $highValueAccess);
			foreach ($rows as $row)
			{
				if ($row['type'] == 2)
				{
					if (ServiceCallQueue::isRelatedQuoteExist($row['bkg_id']) == 0)
					{
						$model = self::updateLead($row);
						if ($model->scq_id > 0)
						{
							$success = true;
							break;
						}
					}
				}
				else if ($row['type'] == 1)
				{
					$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($row['bkg_id']);
					if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
					{
						$model = self::updateLead($row);
						if ($model->scq_id > 0)
						{
							$success = true;
							break;
						}
					}
				}
			}
			$limit++;
			if ($success || $limit > 2)
			{
				break;
			}
		}
		return $model;
	}

	/**
	 * @param array $row
	 * @return static
	 */
	public static function updateLead($row, $type = 0, $agentId = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::info("Row Value  " . json_encode($row));
		$count = ServiceCallQueue::checkLeadExist($row['bkg_contact_no'], $row['type'], $agentId);
		Logger::info("checkLeadExist  " . $count);
		if ($count == 0)
		{
			try
			{

				$model											 = new ServiceCallQueue();
				$model->contactRequired							 = 0;
				$model->followupPerson							 = 1;
				$model->scq_to_be_followed_up_with_type			 = 2;
				$model->scq_to_be_followed_up_with_value		 = $row['bkg_contact_no'];
				$model->scq_to_be_followed_up_with_entity_type	 = 1;
				$model->scq_to_be_followed_up_with_entity_id	 = $row['bkg_user_id'] != null ? $row['bkg_user_id'] : 0;
				$model->scq_to_be_followed_up_with_entity_rating = -1;
				$model->subQueue								 = $row['type'] == 2 ? ServiceCallQueue::SUB_QUOTE_CREATED_FOLLOWUP : ServiceCallQueue::SUB_LEADS;
				$model->scq_priority_score						 = ($row['VVIPRank'] + $row['VIPRank'] + $row['loginRank'] + $row['csrRank'] + $row['timeRank'] + $row['advanceRank'] + $row['pickupRank'] + $row['followup_rank']);
				if ($row ['bkg_country_code'] == null || $row ['bkg_country_code'] == "91")
				{
					if ($row['type'] == 2)
					{
						$model->scq_ref_type		 = 2;
						$model->scq_related_bkg_id	 = $row['bkg_id'];
						if ($agentId > 0)
						{
							$model->scq_follow_up_queue_type = ServiceCallQueue::TYPE_NEW_SPICE_QUOTE_BOOKING;
						}
						else
						{
							$model->scq_follow_up_queue_type = $row['lastMinBooking'] == 1 ? ServiceCallQueue::TYPE_LAST_MIN_BOOKING : ServiceCallQueue::TYPE_NEW_QUOTE_BOOKING;
						}
					}
					else
					{
						$model->scq_ref_type		 = 1;
						$model->scq_related_lead_id	 = $row['bkg_id'];
						if ($agentId > 0)
						{
							$model->scq_follow_up_queue_type = ServiceCallQueue::TYPE_NEW_SPICE_LEAD_BOOKING;
						}
						else
						{
							$model->scq_follow_up_queue_type = $row['lastMinBooking'] == 1 ? ServiceCallQueue::TYPE_LAST_MIN_BOOKING : ServiceCallQueue::TYPE_NEW_LEAD_BOOKING;
						}
					}
				}
				else
				{

					if ($row['type'] == 2)
					{
						$model->scq_ref_type		 = 2;
						$model->scq_related_bkg_id	 = $row['bkg_id'];
						if ($agentId > 0)
						{
							$model->scq_follow_up_queue_type = ServiceCallQueue::TYPE_NEW_SPICE_QUOTE_BOOKING_INTERNATIONAL;
						}
						else
						{
							$model->scq_follow_up_queue_type = $row['lastMinBooking'] == 1 ? ServiceCallQueue::TYPE_LAST_MIN_BOOKING : ServiceCallQueue::TYPE_NEW_QUOTE_BOOKING_INTERNATIONAL;
						}
					}
					else
					{
						$model->scq_ref_type		 = 1;
						$model->scq_related_lead_id	 = $row['bkg_id'];
						if ($agentId > 0)
						{
							$model->scq_follow_up_queue_type = ServiceCallQueue::TYPE_NEW_SPICE_LEAD_BOOKING_INTERNATIONAL;
						}
						else
						{
							$model->scq_follow_up_queue_type = $row['lastMinBooking'] == 1 ? ServiceCallQueue::TYPE_LAST_MIN_BOOKING : ServiceCallQueue::TYPE_NEW_LEAD_BOOKING_INTERNATIONAL;
						}
					}
				}
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($model->scq_to_be_followed_up_with_entity_id);
				$model->scq_status							 = 1;
				$model->scq_reason_id						 = 2;
				$model->scq_agent_id						 = $row['bkg_agent_id'] != null ? $row['bkg_agent_id'] : 0;
				$model->scq_creation_comments				 = $row['desc'] != null ? $row['desc'] : null;
				$platform									 = $type == 1 ? ServiceCallQueue::PLATFORM_SYSTEM : ServiceCallQueue::PLATFORM_ADMIN_CALL;
				ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, $platform);
			}
			catch (Exception $ex)
			{
				Logger::trace("Serivice::updateLead : " . $ex->getMessage());
				Logger::exception($ex);
				Logger::writeToConsole($ex->getMessage());
			}
			Logger::unsetModelCategory(__CLASS__, __FUNCTION__);

			return $model;
		}
	}

	public static function getCallTypeName($scqId)
	{
		$callTypeName	 = '';
		$model			 = ServiceCallQueue::model()->findByPk($scqId);
		switch ((int) $model->scq_follow_up_queue_type)
		{
			case 42:
			case 43:
			case 44:
			case 45:
			case 34:
			case 21:
			case 21:
			case 16:
			case 17:
			case 1: //Lead

				$callTypeName = "New Booking  Callback Request";
				if ($model->scq_related_lead_id != null)
				{
					$callTypeName = "Lead";
				}
				else if ($model->scq_related_bkg_id != null)
				{
					$bModel = Booking::model()->findByPk($model->scq_related_bkg_id);
					if (in_array($bModel->bkg_status, [1, 15]))
					{
						$callTypeName = "Quotation";
					}
					if (in_array($bModel->bkg_status, [2, 3, 5, 6, 7, 9]))
					{
						$callTypeName = "Existing Booking";
					}
				}
				break;
			case 2:
				/** @var Booking $bModel */
				$bModel = Booking::model()->findByPk($model->scq_related_bkg_id);
				if (in_array($bModel->bkg_status, [1, 15]))
				{
					$callTypeName = "Quotation";
				}
				if (in_array($bModel->bkg_status, [2, 3, 5, 6, 7, 9]))
				{
					$callTypeName = "Existing Booking";
				}
				break;
			case 3:
				$callTypeName	 = $callTypeStr . ' New Vendor Attachment Callback Request';
				break;
			case 53:
				$callTypeName	 = ' VIP/VVIP  Callback Request';
				break;
			case 4:
				$callTypeName	 = $callTypeStr . ' Existing Vendor Callback Request';
				break;
		}
		return $callTypeName;
	}

	/**
	 * gets the count by ref id
	 * @param type $refId
	 * @return type
	 */
	public static function getCountByRefId($refId)
	{
		$params	 = ['refId' => $refId];
		$sql	 = "SELECT COUNT(*) FROM service_call_queue WHERE scq_id =:refId AND scq_status IN (1,3) AND scq_active=1";
		return DBUtil::command($sql)->queryScalar($params);
	}

	/**
	 * gets the count by ref id
	 * @param type $refId
	 * @return type
	 */
	public static function getCountByCsrId($csrId, $ref_type = 0, $refId = 0)
	{
		$params	 = ['csrId' => $csrId];
		$where	 = '';
		if ($refId > 0)
		{
			$params['refId'] = $refId;
			if ($ref_type == 1)
			{
				$where = ' AND scq_related_lead_id =:refId ';
			}
			else
			{
				$where = ' AND scq_related_bkg_id =:refId ';
			}
		}
		$sql = "SELECT COUNT(*) FROM service_call_queue WHERE scq_assigned_uid =:csrId $where AND scq_status =1 AND scq_active=1 ";
		return DBUtil::queryScalar($sql, null, $params);
	}

	/**
	 * This function is used for processing the followup request
	 * @param type $jsonObject
	 * @return \ReturnSet
	 */
	public static function processData($jsonObject)
	{
		$returnSet										 = new ReturnSet();
		$userId											 = UserInfo::getUserId();
		$userModel										 = Users::model()->findByPk($userId);
		$teamId											 = Teams::getByRefType($jsonObject->refTypeId);
		$contactId										 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
		$entityId										 = $jsonObject->refTypeId == 4 ? ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR) : ContactProfile::getEntityById($contactId, UserInfo::TYPE_CONSUMER);
		$model											 = new ServiceCallQueue();
		$model->contactRequired							 = 0;
		$model->scq_created_by_type						 = 1;
		$model->scq_created_by_uid						 = $userId;
		$model->scq_to_be_followed_up_with_entity_type	 = 1;
		$model->scq_to_be_followed_up_with_entity_id	 = $entityId > 0 ? $entityId : 0;
		$model->scq_to_be_followed_up_with_entity_rating = -1;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = $teamId;
		$model->scq_to_be_followed_up_with_type			 = 2;
		$model->scq_to_be_followed_up_with_value		 = $userModel->usr_mobile;
		$model->scq_to_be_followed_up_with_contact		 = $contactId;
		$model->scq_creation_comments					 = $jsonObject->refDesc;
		if ($jsonObject->bkgCode != null)
		{
			$model->scq_related_bkg_id = $jsonObject->bkgCode;
		}
		$model->scq_follow_up_queue_type = $jsonObject->refTypeId;
		$model->scq_status				 = 1;
		$model->subQueue				 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
		$returnSet						 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::Platform_BOT);
		$dt								 = $returnSet->getData();
		$followupId						 = $dt['followupId'];
		$returnSet->setMessage("Your requested has been send to the respective department. You will receive a call back on your verified registered number");
		$returnSet->setData($followupId);

		return $returnSet;
	}

	/**
	 * This function is used for getting no fo fresh are avalibale for you
	 * @param type $csr
	 * @return int
	 */
	public static function getCountByCsr($csr)
	{
		$params	 = ['csr' => $csr];
		$sql	 = "SELECT COUNT(*) as tot FROM service_call_queue
				WHERE scq_assigned_uid=:csr AND DATE(scq_assigned_date_time)= CURDATE() AND  scq_active=1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function all scq for score update
	 * @return queryObject array
	 */
	public static function ScqPriorityScore()
	{
		$sql = "SELECT scq_id,scq_related_bkg_id ,scq_related_lead_id
				FROM service_call_queue
				WHERE     1
				AND scq_assigned_uid IS NULL
				AND scq_status = 1
				AND scq_follow_up_date_time <= NOW()
				AND scq_active=1
                AND scq_follow_up_queue_type NOT IN (16,17,20,21,14,40,42,43,44,45)
				AND ((scq_related_bkg_id IS NOT NULL  AND scq_related_bkg_id >0)  OR (scq_related_lead_id IS NOT NULL AND scq_related_lead_id >0))";
		return DBUtil::query($sql, DBUtil::MDB());
	}

	/**
	 * This function is used to update score
	 * @return queryObject array
	 */
	public static function updatePriorityScore($row)
	{
		$booking = $row['scq_related_lead_id'] != null ? BookingTemp::model()->findByPk($row['scq_related_lead_id']) : Booking::model()->findByPk($row['scq_related_bkg_id']);
		if ($booking)
		{
			$bookingPickupDate	 = $booking->bkg_pickup_date;
			$bookingCreateDate	 = $booking->bkg_create_date;
			$now				 = date("Y-m-d h:i:s");
			$pickupTimeDiff		 = (DBUtil::getTimeDiff($now, $bookingPickupDate) / 60);
			$createTimeDiff		 = (DBUtil::getTimeDiff($bookingCreateDate, $now) / 60);
			$result				 = DBUtil::command("CALL getSCQScore(:p1,:p2,@p3,@p4,@p5)", DBUtil::MDB())->execute(["p1" => $createTimeDiff, "p2" => $pickupTimeDiff]);
			$res				 = DBUtil::queryRow("SELECT @p3 AS `createRange`, @p4 AS `pickupRange`, @p5 AS `Score`", DBUtil::MDB());
			$model				 = ServiceCallQueue::model()->findByPk($row['scq_id']);
			if ($model)
			{
				$model->scq_time_since_create	 = ( $res['createRange'] * 100);
				$model->scq_time_to_pickup		 = ( $res['pickupRange'] * 100);
				$model->scq_priority_score		 = $res['Score'];
				$model->contactRequired			 = 0;
				if (!$model->save())
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
				}
			}
		}
	}

	/**
	 * This function will get you all scq data for  payment related followup
	 * @return queryObject array
	 */
	public static function getAllPaymentFollowup()
	{
		$sql = "SELECT scq_id,
				bkg_reconfirm_flag,
				bkg_agent_id,
				scq_related_bkg_id,
				IF( (btr.bkg_quote_expire_date< NOW()) OR (btr.bkg_quote_expire_max_date< NOW()),1,0)  AS quoteExpired,
				IF((bkg_status NOT IN (15)),1,0) AS statusExpired,
				IF( (btr.bkg_payment_expiry_time< NOW()) OR (btr.bkg_payment_expiry_time< NOW()),1,0)  AS paymentExpired
				FROM service_call_queue
				LEFT JOIN booking on booking.bkg_id=service_call_queue.scq_related_bkg_id
				LEFT JOIN booking_trail btr ON btr.btr_bkg_id = booking.bkg_id
				WHERE  1
				AND scq_assigned_uid IS NULL
				AND scq_status = 1
				AND scq_follow_up_queue_type=7
				AND scq_active=1";
		return DBUtil::query($sql, DBUtil::MDB());
	}

	/**
	 * @return array|false  keys => CSR_Name, Total_Days, Average_Followup_Time, Max_Followup_Time,
	 * 							Total_Followup_Time, Total_Follow_ups, Median_Followup_Time,
	 * 							Quote_Created, Quotation_Created_Unqiue_Customer, Booking_Confirmed,
	 * 							Booking_Confirmed_Unqiue_Customer, Total_Gozo_Amount, performanceScore
	 *  */
	public static function getLeadStatsByCSR($csr, $fromDate, $toDate)
	{
		$command = self::getCSRLeadPeformanceCommand($fromDate, $toDate, $csr);
		$row	 = DBUtil::queryScalar($command->getText(), $command->connection, $command->params);

		return $row;
	}

	public static function checkCSRLeadEligibility($csr, $duration = 120)
	{
		$isEligible				 = false;
		$fromDate				 = new CDbExpression("DATE_SUB(NOW(), INTERVAL $duration MINUTE)");
		$toDate					 = new CDbExpression("NOW()");
		$data					 = self::getLeadStatsByCSR($csr, $fromDate, $toDate);
		$avgConversionPerLead	 = $data ["Total_Follow_ups"] / (($data ["Booking_Confirmed"] == 0) ? 1 : $data["Booking_Confirmed"]);
		if ($data ["Booking_Confirmed"] > 0 && $avgConversionPerLead < 16)
		{
			$isEligible = true;
		}
		else if ($avgConversionPerLead < 12)
		{
			$isEligible = true;
		}

		if (!$isEligible && $duration == 120)
		{
			$isEligible = self::checkCSRLeadEligibility($csr, 240);
		}

		return $isEligible;
	}

	/**
	 * This function is used to get service queue phone number
	 * @return queryObject array
	 */
	public static function getCallerNumber($csr)
	{
		$params	 = ['csr' => $csr];
		$sql	 = "SELECT scq_to_be_followed_up_with_value,scq_to_be_followed_up_with_type,scq_id
				    FROM service_call_queue  WHERE scq_assigned_uid=:csr AND scq_status IN (1,3) AND scq_active=1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used to update additonal params in Service call queue
	 * @return queryObject array
	 */
	public static function updateAdditonalParam($scq_id, $data)
	{
		$sql = "UPDATE service_call_queue SET scq_additional_param =:scq_additional_param WHERE scq_id =:scq_id AND scq_active=1";
		DBUtil::execute($sql, ['scq_id' => $scq_id, 'scq_additional_param' => $data]);
	}

	/**
	 * This function is used for processing of  service queue assignment
	 * @param integer $csr
	 * @param integer $teamId
	 * @return Model  of service_call_queue
	 */
	public static function processUnAssignment($csr)
	{
		$returnSet = new ReturnSet();
		try
		{
			$scqId = self::checkAssignment($csr);
			if (!$scqId)
			{
				throw new Exception("Service queueId not valid", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$model		 = ServiceCallQueue::model()->findByPk($scqId);
			$transaction = DBUtil::beginTransaction();
			switch ((int) $model->scq_follow_up_queue_type)
			{
				case 43:
				case 43:
				case 44:
				case 45:
				case 34:
				case 16:
				case 17:
				case 20:
				case 21:
				case 1:
					$agentId = $model->scq_agent_id != null ? $model->scq_agent_id : 0;
					if ($model->scq_related_lead_id != null)
					{
						$jsonDecode		 = json_decode($model->scq_additional_param);
						$bookingTempsIds = $jsonDecode->bookingTempReleated;
						BookingTemp::unassignLD($model->scq_related_lead_id, $csr);
						BookingTemp::unassignedIds($bookingTempsIds, $csr, $model->scq_related_lead_id, $agentId);
					}
					else if ($model->scq_related_bkg_id != null)
					{
						$jsonDecode		 = json_decode($model->scq_additional_param);
						$bookingTempsIds = $jsonDecode->bookingTempReleated;
						$bookingIds		 = $jsonDecode->bookingReleated;
						Booking::unassignQT($model->scq_related_bkg_id, $csr);
						BookingTemp::unassignedIds($bookingTempsIds, $csr, $model->scq_related_lead_id, $agentId);
						Booking::unassignedIds($bookingIds, $csr, $model->scq_related_bkg_id, $agentId);
					}
					else
					{
						$jsonDecode		 = json_decode($model->scq_additional_param);
						$bookingTempsIds = $jsonDecode->bookingTempReleated;
						$bookingIds		 = $jsonDecode->bookingReleated;
						BookingTemp::unassignedIds($bookingTempsIds, $csr, $model->scq_related_lead_id, $agentId);
						Booking::unassignedIds($bookingIds, $csr, $model->scq_related_bkg_id, $agentId);
					}
					break;
				case 7:
				case 9:
				case 53:
				case 2:
					$jsonDecode	 = json_decode($model->scq_additional_param);
					$bookingIds	 = $jsonDecode->booking;
					Booking::unassignQT($model->scq_related_bkg_id, $csr);
					Booking::unassignRelatedExisting($bookingIds, $csr, $model->scq_related_bkg_id);
					break;
				default :
					break;
			}
			$count = self::countScq($csr);
			if ($count > 0)
			{
				$cbrDetails = self::getAllActiveCBRByCsrId($csr);
				foreach ($cbrDetails as $value)
				{
					ServiceCallQueue::unassign($value['scq_id']);
					CallStatus::updateStatus($value['scq_id'], $csr);
				}
			}
			else
			{
				ServiceCallQueue::unassign($scqId);
				CallStatus ::updateStatus($scqId, $csr);
			}

			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setMessage("csr has been unassigned successfully");
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for unassignment of  service call queue to particular scq id
	 * @param integer $scqId
	 * @return integer
	 */
	private static function unassign($scqId)
	{
		$sql	 = "UPDATE service_call_queue SET scq_assigned_uid =null,scq_additional_param=null,`scq_status`=1, `scq_assigned_date_time` = null WHERE scq_id =:scq_id";
		$numrows = DBUtil::execute($sql, ['scq_id' => $scqId]);
		if ($numrows == 0)
		{
			throw new Exception(" Failed to unassign queue id: {$scqId }", ReturnSet::ERROR_FAILED);
		}
		return ($numrows > 0);
	}

	/**
	 * This function is used for creating  auto FUR for Trip Started
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURTripStarted($bookingId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet	 = new ReturnSet();
		$queueType	 = ServiceCallQueue::TYPE_UPSELL . "," . ServiceCallQueue::TYPE_UPSELL_UPPERTIER;
		$count		 = ServiceCallQueue::countQueueByBkgId($bookingId, $queueType);
		/* Auto FUR Trip started Start */
		if ($count == 0)
		{
			$bookingModel	 = Booking::model()->findByPk($bookingId);
			$fromDate		 = $bookingModel->bkgTrack->bkg_trip_start_time;
			$userId			 = $bookingModel->bkgUserInfo->bkg_user_id;
			$toDate			 = Filter::getDBDateTime();
			$timediff		 = DBUtil::getTimeDiff($fromDate, $toDate);
			if ($timediff <= 60 && $bookingModel->bkg_agent_id == null)
			{
				$model = new ServiceCallQueue();
				try
				{
					$contactId									 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
					$code										 = $bookingModel->bkgUserInfo->bkg_country_code;
					$number										 = $bookingModel->bkgUserInfo->bkg_contact_no;
					$model->contactRequired						 = 1;
					Filter::parsePhoneNumber($number, $code, $phone);
					$model->scq_to_be_followed_up_with_value	 = $code . $phone;
					$model->scq_to_be_followed_up_with_contact	 = $contactId;
					$model->scq_to_be_followed_up_with_entity_id = $userId;
					$model->scq_follow_up_queue_type			 = in_array($bookingModel->bkgSvcClassVhcCat->scv_scc_id, [1, 6]) ? ServiceCallQueue::TYPE_UPSELL : ServiceCallQueue::TYPE_UPSELL_UPPERTIER;
					$model->scq_related_bkg_id					 = $bookingId;
					$model->scq_creation_comments				 = "Customers trip has already started. Call them and ask how the trip is going and ensure all is well. If customer is unhappy - create a immediate service request for Customer support team against the same booking ID. Escalate the booking to Customer support leader if issue is serious. If things are going well - ask customer if they need any booking and you can create for them. Create a service request for Retail sales to follow up with this same customer. Remind the customer - they can get 20% of their trip cost back as cash if they refer a friend to travel with us within the next 5 days.";
					$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
				}
				catch (Exception $ex)
				{
					$returnSet = ReturnSet::setException($ex);
				}
			}
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);

		return $returnSet;
	}

	/**
	 * Count by queue type for particular booking id
	 * @param string $bookingId
	 * @param string $queueType
	 * @return type int
	 */
	public static function countQueueByBkgId($bkgId, $queueType, $type = null)
	{
		$where		 = "";
		$whereClosed = "";
		if ($type == "notRating")
		{
			$where .= " AND JSON_VALUE(`scq_additional_param`,'$.rating') IS NULL";
		}
		if ($type == "IsRated")
		{
			$where .= " AND JSON_VALUE(`scq_additional_param`,'$.rating')=1";
		}
		if ($type == "closed")
		{
			$whereClosed = " OR  scq_status IN (2) ";
		}
		$queueType		 = (string) $queueType;
		DBUtil::getINStatement($queueType, $bindString, $params);
		$params['bkgId'] = $bkgId;
		$sql			 = "SELECT  COUNT(*) as active_cbr_count FROM `service_call_queue` WHERE 1 AND scq_follow_up_queue_type IN ($bindString) AND ( scq_status IN (1,3) $whereClosed ) AND scq_active=1 AND   scq_related_bkg_id=:bkgId $where";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for creating  auto FUR for poor rating
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURRating($bookingId)
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$userId			 = $bookingModel->bkgUserInfo->bkg_user_id;
		$returnSet		 = new ReturnSet();
		if ($bookingModel->bkg_agent_id == null && ($bookingModel->ratings[0]->rtg_customer_overall < 4 && $bookingModel->ratings[0]->rtg_customer_overall != '' && $bookingModel->ratings[0]->rtg_customer_overall > 0))
		{
			$model = new ServiceCallQueue();
			try
			{
				$contactId									 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
				$code										 = $bookingModel->bkgUserInfo->bkg_country_code;
				$number										 = $bookingModel->bkgUserInfo->bkg_contact_no;
				$model->contactRequired						 = 1;
				Filter::parsePhoneNumber($number, $code, $phone);
				$model->scq_to_be_followed_up_with_value	 = $code . $phone;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->scq_to_be_followed_up_with_entity_id = $userId;
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_ADVOCACY;
				$model->scq_related_bkg_id					 = $bookingId;
				$model->scq_creation_comments				 = "Customer has given a poor review. Please call customer. Find out the issue and take action to bring back customer delight";
				$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for poor rating
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURDriverAppNotUsed($bookingId)
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$driverId		 = $bookingModel->bkgBcb->bcb_driver_id;
		$returnSet		 = new ReturnSet();
		$model			 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $driverId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DRIVER;
			$model->scq_related_bkg_id					 = $bookingId;
			$model->scq_additional_param				 = json_encode(array('DriverAppNotUsed' => '1'));
			$model->isFlag								 = "1";
			$model->scq_creation_comments				 = "Trip is not completed in driver app. Call Driver and find out why it was not completed on time. Driver must complete on time else penalty will be applicable.";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_DRIVER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for poor rating
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURDriverLateold($bookingId)
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$driverId		 = $bookingModel->bkgBcb->bcb_driver_id;
		$returnSet		 = new ReturnSet();
		$model			 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $driverId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DISPATCH;
			$model->scq_related_bkg_id					 = $bookingId;
			$model->scq_creation_comments				 = "Driver location is either not known or too far and looks like he wont reach pickup on time. Call driver. Talk to him and make sure trip is on time. If it looks like Driver will be late, then inform customer immediately that there may be a trip delay or find a alternate car by talking to the vendor.";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_DRIVER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for poor rating
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURDriverLate($bookingId)
	{
		$comment	 = "Driver location is either not known or too far and looks like he wont reach pickup on time. Call driver. Talk to him and make sure trip is on time. If it looks like Driver will be late, then inform customer immediately that there may be a trip delay or find a alternate car by talking to the vendor.";
		$returnSet	 = ServiceCallQueue::autoFURDriver($bookingId, $comment);
		return $returnSet;
	}

	public static function autoFURDriver($bookingId, $comment = '')
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$driverId		 = $bookingModel->bkgBcb->bcb_driver_id;
		$returnSet		 = new ReturnSet();
		$model			 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $driverId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DISPATCH;
			$model->scq_related_bkg_id					 = $bookingId;
			$model->scq_creation_comments				 = $comment;
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_DRIVER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function autoFURDriverDenyToGo($bookingId)
	{
		$comment	 = "Driver says he is not going for pickup. Call driver. Talk to him and make sure trip is on time. If it looks like Driver will be late, then inform customer immediately that there may be a trip delay or find a alternate car by talking to the vendor.";
		$returnSet	 = ServiceCallQueue::autoFURDriver($bookingId, $comment);
		return $returnSet;
	}

	/**
	 * This function is used for clear scq depends on
	 * @param string $queueType
	 * @param string $ref_type
	 * @param string $ref_id
	 * @param string $ref_id
	 * @return $returnSet
	 */
	public static function clearExisting($queueType, $ref_type, $ref_id, $comments)
	{

		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		$data		 = [];
		try
		{
			$scqId = self::getIdByQueueAndReference($queueType, $ref_type, $ref_id);
			if (!$scqId)
			{
				$data	 = ['comments' => "No scq found due to this category"];
				$success = true;
				goto skipClear;
			}
			$model							 = ServiceCallQueue::model()->findByPk($scqId);
			$model->scq_status				 = 2;
			$model->scq_disposition_comments = $comments != NULL ? $comments : "Auto closing followup";
			$model->scq_disposition_date	 = new CDbExpression('NOW()');
			$model->scq_disposed_by_uid		 = 0;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
			}
			if ($model->scq_id > 0)
			{
				$data	 = [
					'followupId' => (int) $model->scq_id,
					'status'	 => $model->scq_status,
					'comments'	 => $model->scq_disposition_comments];
				$success = true;
			}
			skipClear:
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * Fetching scq for clearExisting by
	 * @param string $queueType
	 * @param string $ref_type
	 * @param string $ref_id
	 * @return type int
	 */
	public static function getIdByQueueAndReference($queueType, $ref_type, $ref_id)
	{
		$sql = "SELECT  scq_id FROM `service_call_queue` WHERE 1 AND scq_active=1 AND scq_follow_up_queue_type=:queueType AND scq_related_bkg_id=:refId AND  scq_ref_type=:refType AND scq_status = 1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['refId' => $ref_id, 'queueType' => $queueType, 'refType' => $ref_type]);
	}

	public static function clearDispatch($queueType, $ref_type, $ref_id, $comments)
	{
		$result = self::clearExisting($queueType, $ref_type, $ref_id, $comments);

		return $result;
	}

	/**
	 * This function is used for creating  auto FUR for poor rating
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURRatingVendorAdvocacy($bookingId)
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$vendorId		 = $bookingModel->bkgBcb->bcb_vendor_id;
		$returnSet		 = new ReturnSet();
		if (($bookingModel->ratings[0]->rtg_customer_overall < 4 && $bookingModel->ratings[0]->rtg_customer_overall != '' && $bookingModel->ratings[0]->rtg_customer_overall > 0))
		{
			$model = new ServiceCallQueue();
			try
			{
				$contactId									 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
				$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
				$code										 = $arrPhoneByPriority['phn_phone_country_code'];
				$number										 = $arrPhoneByPriority['phn_phone_no'];
				$model->contactRequired						 = 1;
				Filter::parsePhoneNumber($number, $code, $phone);
				$model->scq_to_be_followed_up_with_value	 = $code . $phone;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->scq_to_be_followed_up_with_entity_id = $vendorId;
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VENDOR_ADVOCACY;
				$model->scq_related_bkg_id					 = $bookingId;
				$model->scq_creation_comments				 = "Customer has given a poor review. Please call vendor. Find out the issue and take action to bring back customer delight";
				$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for poor rating
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoVendorApproval($row)
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = $row['vnd_id'];
		$contactId	 = $row['ctt_id'];
		$vnd_name	 = $row['vnd_name'];
		$model		 = new ServiceCallQueue();
		try
		{
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $vendorId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VENDOR_APPROVAL_ZONE_BASED_INVENTORY;
			$model->scq_creation_comments				 = "Vendor ready for approval: $vnd_name";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			
		}
		return $returnSet;
	}

	/**
	 * Count by queue type for particular booking id
	 * @param string $bookingId
	 * @param string $queueType
	 * @return type int
	 */
	public static function checkDuplicateAutoApprovalForVendor($vendorId, $queueType)
	{
		$sql = "SELECT  COUNT(*) as active_cbr_count FROM `service_call_queue` WHERE 1 AND scq_follow_up_queue_type=:queueType AND scq_status  IN (1,2,3) AND  scq_to_be_followed_up_with_entity_type=2 AND scq_to_be_followed_up_with_entity_id=:vendorId";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['vendorId' => $vendorId, 'queueType' => $queueType]);
	}

	/**
	 * get Lead Count
	 * @return type int
	 */
	public static function getLeadCount($isEligibleForNewLead = true, $elgibileScore = 80, $agentId = 0)
	{
		$where	 = $isEligibleForNewLead ? " AND scq_priority_score>$elgibileScore" : " AND scq_priority_score <=($elgibileScore + TIMESTAMPDIFF(MINUTE, scq_create_date, NOW()))";
		$where	 .= $agentId == 0 ? " AND scq_follow_up_queue_type IN (16,17) " : " AND scq_follow_up_queue_type IN (42,43,44,45) ";
		$sql	 = "SELECT  COUNT(1) as cnt FROM `service_call_queue`
					WHERE 1 $where  AND scq_active=1 AND scq_reason_id=2 AND scq_assigned_uid IS NULL  AND scq_status IN (1,3)
					AND scq_follow_up_queue_type <> 9 AND scq_ref_type IN (1,2)  AND scq_follow_up_date_time <= NOW()";
		return DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	/**
	 * get team name by queue id  from team queue mapping
	 * @return string
	 */
	public static function getQueueNameByQueueId($queueId)
	{
		$sql = "SELECT tqm_tea_name FROM `team_queue_mapping` WHERE `tqm_queue_id` = :queueId AND `tqm_active` = 1 ORDER BY tqm_priority ASC,tqm_queue_weight DESC";
		return DBUtil::query($sql, DBUtil::SDB(), ['queueId' => $queueId]);
	}

	/**
	 * This function will return all the  queue that is older than 1 days from now
	 * @return queryObject array
	 */
	public static function getAllDataByQueueId($queueIds, $createdByType = 10)
	{
		$queueIds				 = (string) $queueIds;
		DBUtil::getINStatement($queueIds, $bindString, $params);
		$params['createdByType'] = $createdByType;
		$innerJoin				 = "";
		$where					 = "";
		if ($createdByType == 4)
		{
			$innerJoin = "  INNER JOIN booking ON booking.bkg_id=service_call_queue.scq_related_bkg_id  AND bkg_status NOT IN (2) ";
		}
		else
		{
			$where = " AND scq_assigned_uid IS NULL ";
		}
		$sql = "SELECT  service_call_queue.scq_id
				FROM `service_call_queue`
				$innerJoin
				WHERE 1
				AND scq_follow_up_queue_type IN ($bindString)
                AND CURDATE() > scq_create_date
				AND scq_status=1
				AND scq_active=1
				$where
				AND scq_created_by_type=:createdByType";
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * Get all Statistical  Data for internal queue for each team
	 * @return queryObject array
	 */
	public static function getStaticalDataByQueueId($queueId)
	{
		$sql = 'SELECT tea_name,
				COUNT(scq_id) AS openCount,
				SUM(IF(scq_create_date < CURDATE(), 1, 0)) AS overdueCount,
				MIN(scq_create_date) AS oldestDueDate
				FROM `service_call_queue`
				INNER JOIN teams ON tea_id = scq_to_be_followed_up_by_id	AND scq_to_be_followed_up_by_type = 1 AND scq_to_be_followed_up_by_id > 0
				WHERE     1
						AND scq_follow_up_queue_type =:queueId
						AND scq_create_date >= "2021-01-01 00:00:00"
						AND scq_status IN (1, 3)
						AND scq_active=1
						GROUP BY tea_id
				UNION
				SELECT "Individual" AS tea_name,
					   COUNT(scq_id) AS openCount,
					   SUM(IF(scq_create_date < CURDATE(), 1, 0)) AS overdueCount,
					   MIN(scq_create_date) AS oldestDueDate
				FROM `service_call_queue`
				WHERE  1
					  AND scq_to_be_followed_up_by_type = 2
					  AND scq_to_be_followed_up_by_id > 0
					  AND scq_follow_up_queue_type = 9
					  AND scq_active=1
					  AND scq_create_date >= "2021-01-01 00:00:00"
					  AND scq_status IN (1,3)
				GROUP BY scq_to_be_followed_up_by_type';
		return DBUtil::query($sql, DBUtil::MDB(), array('queueId' => $queueId));
	}

	/**
	 * check if lead exist for giving phone or lead id
	 * @return type int
	 */
	public static function isRelatedLeadExist($leadId, $agentId = 0)
	{
		$param	 = array('scq_related_lead_id' => $leadId);
		$where	 = $agentId == 0 ? " AND scq_follow_up_queue_type IN (16,17,20,21,34) " : " AND scq_follow_up_queue_type IN (42,43,44,45) ";
		$sql	 = "SELECT COUNT(1) as cnt, SUM(IF(scq_related_lead_id=:scq_related_lead_id,1,0)) as isQueuedUp 
				FROM `service_call_queue` 
				WHERE 1 AND (scq_related_lead_id=:scq_related_lead_id || (scq_additional_param IS NOT NULL 
					AND  CONCAT(',', JSON_VALUE(`scq_additional_param`,'$.bookingTempReleated'), ',') REGEXP CONCAT(',(', REPLACE(:scq_related_lead_id, ',', '|'), '),'))) 
					$where AND scq_active =1  AND scq_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 4 Day), ' 00:00:00') AND NOW()  
					AND scq_ref_type IN (1,2) AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $param);
	}

	/**
	 * check if lead id assigned to the csr
	 * @return type int
	 */
	public static function validateAssignedLeadCSR($leadId, $csrId)
	{
		$param	 = array('scq_related_lead_id' => $leadId, 'csrId' => $csrId);
		$sql	 = 'SELECT COUNT(1) as cnt FROM `service_call_queue` WHERE 1
						AND (scq_related_lead_id = :scq_related_lead_id || CONCAT(",",IF(JSON_VALUE(`scq_additional_param`,"$.bookingTempReleated")="" OR  JSON_VALUE(`scq_additional_param`,"$.bookingTempReleated") IS NULL,0,JSON_VALUE(`scq_additional_param`,"$.bookingTempReleated")), ",") REGEXP CONCAT(",(", REPLACE(:scq_related_lead_id, ",", "|"), "),"))
						AND scq_follow_up_queue_type IN (1,16,17,20,21,34) AND scq_active =1 AND scq_ref_type IN (1,2) AND scq_status IN (1,3) AND scq_assigned_uid = :csrId
						AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL';
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
	}

	/**
	 * check if quote exist for giving phone or quupdateLeadote id
	 * @return type int
	 */
	public static function isRelatedQuoteExist($quoteId, $agentId = 0)
	{
		$where	 = $agentId == 0 ? " AND scq_follow_up_queue_type IN (17,21,34) " : " AND scq_follow_up_queue_type IN (43,45) ";
		$param	 = array('scq_related_bkg_id' => $quoteId);
		$sql	 = 'SELECT  COUNT(*) as cnt FROM `service_call_queue` WHERE 1
						AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL
						AND (scq_related_bkg_id = :scq_related_bkg_id || CONCAT(",", IFNULL(JSON_VALUE(`scq_additional_param`,"$.bookingReleated"), "0"), ",") REGEXP CONCAT(",(", REPLACE( :scq_related_bkg_id, ",", "|"), "),"))
						AND scq_active =1 AND scq_ref_type =2 ' . $where;
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
	}

	/**
	 * Get all Statistical  Data for CBR Details Report
	 * @return queryObject array
	 */
	public static function getCbrStaticalDetailsData($fromdate, $todate)
	{
		$params = array();
		if ($fromdate != "" && $todate != null)
		{
			$params['fromdate']	 = $fromdate . " 00:00:00";
			$params['todate']	 = $todate . " 23:59:59";
		}
		$sql = '
				SELECT
					"Team Wise" As Type,
					team_queue_mapping.tqm_tea_id AS scq_to_be_followed_up_by_id,
					scq_follow_up_queue_type,
					CASE
						WHEN scq_follow_up_queue_type = 1 THEN "New Booking"
						WHEN scq_follow_up_queue_type = 2 THEN "Existing Booking"
						WHEN scq_follow_up_queue_type = 3 THEN "New Vendor Attachment"
						WHEN scq_follow_up_queue_type = 4 THEN "Vendor Support"
						WHEN scq_follow_up_queue_type = 5 THEN "Customers Advocacy"
						WHEN scq_follow_up_queue_type = 6 THEN "Driver Support/Line"
						WHEN scq_follow_up_queue_type = 7 THEN "Payment Followup"
						WHEN scq_follow_up_queue_type = 9 THEN "Service Requests"
						WHEN scq_follow_up_queue_type = 11 THEN "Penality Dispute"
						WHEN scq_follow_up_queue_type = 10 THEN "SOS"
						WHEN scq_follow_up_queue_type = 12 THEN "UpSell(CNG/Value)"
						WHEN scq_follow_up_queue_type = 13 THEN "Vendor Advocacy"
						WHEN scq_follow_up_queue_type = 14 THEN "Dispatch"
						WHEN scq_follow_up_queue_type = 15 THEN "Vendor Approval"
						WHEN scq_follow_up_queue_type =16 THEN "New Lead Booking"
						WHEN scq_follow_up_queue_type = 17 THEN "New Quote Booking"
						WHEN scq_follow_up_queue_type = 18 THEN "B2B Post Pickup"
						WHEN scq_follow_up_queue_type = 19 THEN "Booking At Risk(Bar)"
						WHEN scq_follow_up_queue_type = 20 THEN "New Lead Booking(International)"
						WHEN scq_follow_up_queue_type = 21 THEN "New Quote Booking(International)"
						WHEN scq_follow_up_queue_type = 22 THEN "FBG"
						WHEN scq_follow_up_queue_type = 23 THEN "Vendor Payment Request"
						WHEN scq_follow_up_queue_type = 24 THEN "Upsell(Value+/Select)"
						WHEN scq_follow_up_queue_type = 25 THEN "Booking Complete Review"
						WHEN scq_follow_up_queue_type = 26 THEN "Apps Help & Tech support"
						WHEN scq_follow_up_queue_type = 27 THEN "Gozo Now"
						WHEN scq_follow_up_queue_type = 29 THEN "Auto Lead Followup"
						WHEN scq_follow_up_queue_type = 30 THEN "Document Approval"
						WHEN scq_follow_up_queue_type = 31 THEN "Vendor Approval  Zone Based Inventory"
						WHEN scq_follow_up_queue_type = 32 THEN "Critical and stress (risk) assignments(CSA)"
						WHEN scq_follow_up_queue_type = 33 THEN	"Airport DailyRental"
						WHEN scq_follow_up_queue_type = 34 THEN	"Last Min Booking"
						WHEN scq_follow_up_queue_type = 35 THEN	"Price High"
						WHEN scq_follow_up_queue_type = 36 THEN	"Driver NoShow"
						WHEN scq_follow_up_queue_type = 37 THEN	"Customer NoShow"
						WHEN scq_follow_up_queue_type = 38 THEN	"MMT Support"
						WHEN scq_follow_up_queue_type = 39 THEN	"Driver Car BreakDown"
						WHEN scq_follow_up_queue_type = 40 THEN	"Vendor Assign"
						WHEN scq_follow_up_queue_type = 41 THEN	"Cusomer Booking Cancel"
						WHEN scq_follow_up_queue_type = 42 THEN	"Spice Lead Booking"
						WHEN scq_follow_up_queue_type = 43 THEN	"Spice Quote Booking"
						WHEN scq_follow_up_queue_type = 44 THEN	"Spice Lead Booking International"
						WHEN scq_follow_up_queue_type = 45 THEN	"Spice Quote Booking International"
						WHEN scq_follow_up_queue_type = 46 THEN	"Vendor Due Amount"
						WHEN scq_follow_up_queue_type = 51 THEN	"Booking Reschedule"
						WHEN scq_follow_up_queue_type = 53 THEN	"VIP/VVIP Booking"

					END AS followUpType,
					SUM(IF(scq_follow_up_date_time<=NOW() AND scq_create_date < :todate AND scq_assigned_uid IS NULL  AND scq_status=1 ,1,0)) AS assignableNowCount,
					SUM(IF(scq_disposed_by_uid IS NULL AND scq_assigned_uid IS NOT NULL ,1,0) ) AS assignedCount,
                                        SUM(IF(scq_assigned_uid IS NOT NULL ,1,0) ) AS totalAssignedCount,
					SUM(IF(scq_disposed_by_uid IS NOT NULL AND scq_assigned_uid IS NOT NULL ,1,0) ) AS closedCount,
					"" AS team_name,
                                        SUM(IF(scq_disposed_by_uid IS NULL AND scq_assigned_uid IS NOT NULL ,TIMESTAMPDIFF(MINUTE, scq_follow_up_date_time, scq_assigned_date_time),0) ) AS AssignedMinute,
                                        SUM(IF(scq_assigned_uid IS NOT NULL ,TIMESTAMPDIFF(MINUTE, scq_follow_up_date_time, scq_assigned_date_time),0) ) AS TotalAssignedMinute,
					SUM(IF(scq_disposed_by_uid IS NOT NULL AND scq_assigned_uid IS NOT NULL ,TIMESTAMPDIFF(MINUTE, scq_assigned_date_time, scq_disposition_date),0) ) AS ClosedMinute
				FROM `service_call_queue`
				INNER JOIN team_queue_mapping ON team_queue_mapping.tqm_queue_id=scq_follow_up_queue_type and team_queue_mapping.tqm_active=1
					WHERE 1
					AND scq_create_date >="2021-01-01 00:00:00"
					AND scq_active=1
					AND scq_create_date BETWEEN :fromdate AND :todate
					AND scq_follow_up_queue_type<>9
				GROUP BY team_queue_mapping.tqm_tea_id

                UNION

                SELECT
                        "Service Request" As Type,
                        scq_to_be_followed_up_by_id,
                        scq_follow_up_queue_type,
                        "Service Requests" AS followUpType,
                        SUM(IF(scq_follow_up_date_time<=NOW() AND scq_create_date <:todate AND scq_assigned_uid IS NULL  AND scq_status=1 ,1,0)) AS assignableNowCount,
                        SUM(IF(scq_disposed_by_uid IS NULL AND scq_assigned_uid IS NOT NULL ,1,0) ) AS assignedCount,
                        SUM(IF(scq_assigned_uid IS NOT NULL ,1,0) ) AS totalAssignedCount,
                        SUM(IF(scq_disposed_by_uid IS NOT NULL AND scq_assigned_uid IS NOT NULL ,1,0) ) AS closedCount,
                        team_queue_mapping.tqm_tea_name AS team_name,
                        SUM(IF(scq_assigned_uid IS NOT NULL ,TIMESTAMPDIFF(MINUTE, scq_follow_up_date_time, scq_assigned_date_time),0) ) AS TotalAssignedMinute,
                        SUM(IF(scq_disposed_by_uid IS NULL AND scq_assigned_uid IS NOT NULL ,TIMESTAMPDIFF(MINUTE, scq_follow_up_date_time, scq_assigned_date_time),0) ) AS AssignedMinute,
                        SUM(IF(scq_disposed_by_uid IS NOT NULL AND scq_assigned_uid IS NOT NULL ,TIMESTAMPDIFF(MINUTE, scq_assigned_date_time, scq_disposition_date),0) ) AS ClosedMinute
                FROM `service_call_queue`
                LEFT JOIN team_queue_mapping ON team_queue_mapping.tqm_tea_id=scq_to_be_followed_up_by_id AND scq_to_be_followed_up_by_type=1
                WHERE 1
                        AND scq_create_date >="2021-01-01 00:00:00"
                        AND scq_active=1
                        AND scq_to_be_followed_up_by_type=1
                        AND scq_create_date BETWEEN :fromdate AND :todate
                        AND scq_follow_up_queue_type=9
                GROUP BY scq_to_be_followed_up_by_id';
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * Get all Statistical  Data for CBR Details Report
	 * @return queryObject array
	 */
	public static function getCbrStaticalCloseData($fromdate, $todate)
	{
		$params = array();
		if ($fromdate != "" && $todate != null)
		{
			$params['fromdate']	 = $fromdate . " 00:00:00";
			$params['todate']	 = $todate . " 23:59:59";
		}
		$sql = 'SELECT
					"Team Wise" As Type,
					COUNT("scq_disposed_by_uid") AS totalCloseCount,
					COUNT(DISTINCT IF(scq_disposed_by_uid IS NOT NULL, scq_disposed_by_uid, NULL)) AS uniqueCsrCount,
					scq_follow_up_queue_type,
					scq_to_be_followed_up_by_id,
					"" AS team_name
				FROM `service_call_queue`
				WHERE     1
					AND scq_create_date  >= "2021-01-01 00:00:00"
					AND (scq_disposition_date BETWEEN  :fromdate AND :todate)
					AND scq_active=1
					AND scq_to_be_followed_up_by_type=1
					AND scq_follow_up_queue_type<>9
				GROUP BY scq_to_be_followed_up_by_id

				UNION

				SELECT
					"Service Request" As Type,
					COUNT("scq_disposed_by_uid") AS totalCloseCount,
					COUNT(DISTINCT IF(scq_disposed_by_uid IS NOT NULL, scq_disposed_by_uid, NULL)) AS uniqueCsrCount,
					scq_follow_up_queue_type,
					scq_to_be_followed_up_by_id,
					team_queue_mapping.tqm_tea_name AS team_name
				FROM `service_call_queue`
				LEFT JOIN team_queue_mapping ON team_queue_mapping.tqm_tea_id=scq_to_be_followed_up_by_id AND scq_to_be_followed_up_by_type=1
				WHERE     1
					AND scq_create_date  >= "2021-01-01 00:00:00"
					AND (scq_disposition_date BETWEEN  :fromdate AND :todate)
					AND scq_active=1
					AND scq_to_be_followed_up_by_type=1
					AND scq_follow_up_queue_type=9
				GROUP BY scq_to_be_followed_up_by_id';
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for storing the call back data  for re-followup
	 * @param string $refId
	 * @param string $date
	 * @return $returnSet
	 */
	public static function addReFollowup($refId, $date, $followupWith, $followupTeam)
	{
		$obj = ServiceCallQueue::model()->findByPk($refId);
		try
		{
			$entityId				 = $obj->scq_to_be_followed_up_with_entity_id;
			$entityType				 = $obj->scq_to_be_followed_up_with_entity_type;
			$model					 = new ServiceCallQueue();
			$model->contactRequired	 = (($obj->scq_follow_up_queue_type == ServiceCallQueue::TYPE_BAR) || ($obj->scq_follow_up_queue_type == ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL)) ? 0 : 1;
			if (($obj->scq_follow_up_queue_type == ServiceCallQueue::TYPE_BAR) || ($obj->scq_follow_up_queue_type == ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL))
			{
				$model->followupPerson					 = 1;
				$model->scq_to_be_followed_up_with_type	 = 0;
			}
			$model->scq_follow_up_queue_type = $obj->scq_follow_up_queue_type;
			if ($followupWith == 1)
			{
				$model->followupPerson					 = 1;
				$model->scq_to_be_followed_up_by_type	 = 2;
				$model->scq_to_be_followed_up_by_id		 = UserInfo::getUserId();
			}
			else if ($followupWith == 2)
			{
				$model->scq_to_be_followed_up_by_type	 = 1;
				$model->scq_to_be_followed_up_by_id		 = $followupTeam;
				$model->scq_follow_up_queue_type		 = ServiceCallQueue::TYPE_IMNTERNAL;
			}
			else
			{
				$model->followupPerson					 = 1;
				$model->scq_to_be_followed_up_by_type	 = $obj->scq_to_be_followed_up_by_type;
				$model->scq_to_be_followed_up_by_id		 = $obj->scq_to_be_followed_up_by_id;
			}
			$model->scq_to_be_followed_up_with_type		 = $obj->scq_to_be_followed_up_with_type;
			$model->scq_to_be_followed_up_with_value	 = $obj->scq_to_be_followed_up_with_value;
			$model->scq_creation_comments				 = "[Rescheduled fur]." . $obj->scq_disposition_comments;
			$model->scq_to_be_followed_up_with_entity_id = $entityId;
			$model->scq_to_be_followed_up_with_contact	 = $obj->scq_to_be_followed_up_with_contact;
			$model->subQueue							 = ServiceCallQueue::SUB_REFOLLOWUP;
			$model->scq_related_bkg_id					 = $obj->scq_related_bkg_id;
			$model->scq_related_lead_id					 = $obj->scq_related_lead_id;
			$model->scq_follow_up_date_time				 = $date;
			$model->isFlag								 = "1";
			$model->scq_reason_id						 = $obj->scq_reason_id;
			$platform									 = $obj->scq_platform;
			$model->scq_prev_or_originating_followup	 = $obj->scq_id;
			$model->scq_ref_type						 = $obj->scq_ref_type;
			$returnSet									 = ServiceCallQueue::model()->create($model, $entityType, $platform);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for Trip Started For B2B
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURTripStartedForB2B($bookingId)
	{
		$returnSet	 = new ReturnSet();
		$count		 = ServiceCallQueue::countQueueByBkgId($bookingId, ServiceCallQueue::TYPE_B2B_POST_PICKUP);
		if ($count == 0)
		{
			$bookingModel	 = Booking::model()->findByPk($bookingId);
			$driverId		 = $bookingModel->bkgBcb->bcb_driver_id;
			$userId			 = $bookingModel->bkgUserInfo->bkg_user_id;
			$fromDate		 = $bookingModel->bkgTrack->bkg_trip_start_time;
			$toDate			 = Filter::getDBDateTime();
			$timediff		 = DBUtil::getTimeDiff($fromDate, $toDate);
			$isUpperTier	 = in_array($bookingModel->bkgSvcClassVhcCat->scv_scc_id, [1, 6]) ? 0 : 1;
			if ($timediff <= 60 && $bookingModel->bkg_agent_id != null)
			{
				$model = new ServiceCallQueue();
				try
				{
					$contactId									 = $isUpperTier == 0 ? ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER) : ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
					$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
					$code										 = $isUpperTier == 0 ? $arrPhoneByPriority['phn_phone_country_code'] : $bookingModel->bkgUserInfo->bkg_country_code;
					$number										 = $isUpperTier == 0 ? $arrPhoneByPriority['phn_phone_no'] : $bookingModel->bkgUserInfo->bkg_contact_no;
					$model->contactRequired						 = 1;
					Filter::parsePhoneNumber($number, $code, $phone);
					$model->scq_to_be_followed_up_with_value	 = $code . $phone;
					$model->scq_to_be_followed_up_with_contact	 = $contactId;
					$model->scq_to_be_followed_up_with_entity_id = $isUpperTier == 0 ? $driverId : $userId;
					$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_B2B_POST_PICKUP;
					$model->scq_related_bkg_id					 = $bookingId;
					$model->scq_creation_comments				 = $isUpperTier == 0 ? "B2B trip has already started. Call driver and ask how the trip is going and ensure all is well.Ask driver if he can have you talk to customer. And confirm with customer that everything is going well.If customer is unhappy - create a immediate service request for Customer support team against the same booking ID. Escalate the booking to Customer support leader if issue is serious.If things are going well - ask customer if there is anything else you need help with. (do not directly try to offer to create a booking. If customer asks then you can create a follow up for retail sales team but you should not directly offer to create a booking)" : "Customers trip has already started. Call them and ask how the trip is going and ensure all is well. If customer is unhappy - create a immediate service request for Customer support team against the same booking ID. Escalate the booking to Customer support leader if issue is serious. If things are going well - ask customer if they need any booking and you can create for them. Create a service request for Retail sales to follow up with this same customer. Remind the customer - they can get 20% of their trip cost back as cash if they refer a friend to travel with us within the next 5 days.";
					$entityType									 = $isUpperTier == 0 ? UserInfo::TYPE_DRIVER : UserInfo::TYPE_CONSUMER;
					$returnSet									 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
				}
				catch (Exception $ex)
				{
					$returnSet = ReturnSet::setException($ex);
				}
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  internal followup
	 * @var booking $bkgModel
	 * @return $returnSet
	 */
	public static function createByPartner($bkgModel)
	{
		$userModel										 = UserInfo::getInstance();
		$model											 = new ServiceCallQueue();
		$model->followupPerson							 = 1;
		$model->scq_created_by_type						 = UserInfo::TYPE_INTERNAL;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = 5;
		$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_INTERNAL;
		$model->scq_to_be_followed_up_with_entity_id	 = $bkgModel->bkg_agent_id;
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 5;
		$model->scq_creation_comments					 = "Partner has requested to cancel booking but Driver has arrived and trip already started from our end";
		$model->scq_related_bkg_id						 = $bkgModel->bkg_id;
		$model->scq_to_be_followed_up_with_entity_id	 = 0;
		$model->scq_to_be_followed_up_with_value		 = 0;
		$model->scq_to_be_followed_up_with_type			 = 0;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 2;
		$entityType										 = UserInfo::TYPE_SYSTEM;
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
	}

	/**
	 * getting count  for  Originating followup
	 * @param int  $scqId
	 * @return type int
	 */
	public static function countOriginatingfollowup($scqId)
	{
		$sql = "SELECT  COUNT(*) as cnt FROM `service_call_queue` WHERE 1 AND scq_prev_or_originating_followup = :scqId AND scq_active =1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['scqId' => $scqId]);
	}

	/**
	 * getting leads count at the time assignment
	 * @param int  $scqId
	 * @return type int
	 */
	public static function getAssignmentCount($csr, $teamId, $isNewBookingEligible = true)
	{
		$discardNewBkgSQL = "";
		if (!$isNewBookingEligible)
		{
			$discardNewBkgSQL = " AND scq_follow_up_queue_type<>1 AND scq_follow_up_priority < 35 ";
		}
		if ($csr > 0 && $teamId > 0)
		{
			$params	 = ['team' => $teamId, 'csr' => $csr];
			$sql	 = "SELECT COUNT('scq_id') As Cnt
						FROM service_call_queue
						INNER JOIN team_queue_mapping tqm ON tqm.tqm_queue_id = scq_follow_up_queue_type AND tqm.tqm_active=1 AND (tqm.tqm_tea_id=:team)
						LEFT JOIN booking_trail ON btr_bkg_id=scq_related_bkg_id AND scq_follow_up_queue_type=7 AND bkg_create_user_type=4
						WHERE  1
						AND scq_status IN (1, 3)
						AND scq_active=1
						AND scq_follow_up_date_time IS NOT NULL
						AND scq_assigned_uid IS NULL
						AND scq_follow_up_date_time <= DATE_ADD(NOW(), INTERVAL IF(scq_follow_up_queue_type<>7 OR (scq_follow_up_queue_type=7 AND btr_bkg_id IS NOT NULL AND bkg_create_user_id=:csr), 0, -15) MINUTE)
						AND
						(
						  ( scq_to_be_followed_up_by_type = 1   AND scq_follow_up_queue_type<>9)
						   OR
						  ( scq_to_be_followed_up_by_type = 1  AND scq_to_be_followed_up_by_id = :team AND scq_follow_up_queue_type=9)
						)
						$discardNewBkgSQL";
			$count	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
			return $count == 0 ? $count : $count - 1;
		}
		else
		{
			return -1;
		}
	}

	/**
	 * This function is used getting lead for BAR Queue
	 * @return integer booking Id
	 */
	public static function getDataForBARQueue($type = 0, $region = null, $serveBookingType = null)
	{
		$whereRegion		 = "";
		$params				 = array();
		$whereBookingType	 = "";
		$params1			 = array();

		if ($region != null && $region != "")
		{
			$region		 = is_string($region) ? $region : strval($region);
			DBUtil::getINStatement($region, $bindString, $params);
			$whereRegion = " AND states.stt_zone IN ({$bindString}) ";
		}

		if ($serveBookingType != null && $serveBookingType != "")
		{
			$bookingType		 = is_string($serveBookingType) ? $serveBookingType : strval($serveBookingType);
			DBUtil::getINStatement($bookingType, $bindString1, $params1);
			$whereBookingType	 = " AND booking.bkg_booking_type IN ({$bindString1})";
		}

		$whereInner	 = $type == 0 ? "" : " INNER JOIN booking_trail ON booking_trail.btr_bkg_id=bpr_bkg_id  ";
		$workingHour = $type == 0 ? "" : " OR ( CalcWorkingHour(NOW(), bkg_pickup_date) <= 2  AND TIMESTAMPDIFF(MINUTE, bkg_confirm_datetime,NOW())>60 ) ";
		$orderBy	 = $type == 0 ? " ORDER BY   bkg_critical_assignment DESC, bkg_manual_assignment DESC, bkg_critical_score DESC,booking.bkg_pickup_date ASC,bkg_create_date ASC " : " ORDER BY pickUpPriority DESC,bkg_critical_assignment DESC, bkg_manual_assignment DESC, bkg_critical_score DESC,booking.bkg_pickup_date ASC,bkg_create_date ASC ";
		$sql		 = "SELECT * FROM
                (
                    (
						SELECT bkg_id, bkg_booking_type, bpr_assignment_id, 1 AS type, IF(CalcWorkingHour(NOW(), bkg_pickup_date) <= 4,1,0) AS pickUpPriority
						FROM `booking_pref`
						$whereInner
						INNER JOIN booking	ON booking.bkg_id = bpr_bkg_id AND bpr_assignment_level IN (0,1)  AND (bkg_manual_assignment=1 OR  bkg_critical_assignment=1 $workingHour  )
						INNER JOIN  cities on bkg_from_city_id = cty_id AND cities.cty_active=1
						INNER JOIN  states on cty_state_id = stt_id AND states.stt_active='1'
						LEFT JOIN service_call_queue ON service_call_queue.scq_related_bkg_id=booking.bkg_id AND scq_follow_up_queue_type IN (19,33,32)  AND scq_status IN (1,3)  AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL
						WHERE 1
						AND bkg_is_fbg_type=0
						$whereRegion
						$whereBookingType
						AND ( bpr_skip_csr_assignment IS NULL OR (bpr_skip_csr_assignment IS NOT NULL AND bpr_skip_csr_assignment<=NOW()))
						AND bpr_askmanual_assignment=0 AND bkg_pickup_date >= NOW() AND  bkg_status=2 AND scq_id IS NULL
						$orderBy  LIMIT 0,1
                    )
                    UNION
                    (
						SELECT bkg_id, bkg_booking_type, bpr_assignment_id, 2 AS type, IF(CalcWorkingHour(NOW(), bkg_pickup_date) <= 4,1,0) AS pickUpPriority
						FROM `booking_pref`
						INNER JOIN booking ON booking.bkg_id = bpr_bkg_id AND bpr_assignment_level IN (0,1)
						INNER JOIN booking_trail ON booking.bkg_id = booking_trail.btr_bkg_id AND booking_trail.btr_is_dem_sup_misfire=1 AND  bkg_reconfirm_flag=1 
						INNER JOIN  cities on bkg_from_city_id = cty_id AND cities.cty_active=1
						INNER JOIN  states on cty_state_id = stt_id AND states.stt_active='1'
						LEFT JOIN service_call_queue ON service_call_queue.scq_related_bkg_id=booking.bkg_id AND scq_follow_up_queue_type IN (19,33,32)  AND scq_status IN (1,3) AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL
						WHERE 1 
						AND bkg_is_fbg_type=0
						$whereRegion
						$whereBookingType
						AND ( bpr_skip_csr_assignment IS NULL OR (bpr_skip_csr_assignment IS NOT NULL AND bpr_skip_csr_assignment<=NOW()))
						AND bpr_askmanual_assignment=0 AND bkg_pickup_date >= NOW() AND  bkg_status=2 AND scq_id IS NULL
						$orderBy  LIMIT 0,1
                    )
                ) temp	WHERE 1 ORDER BY temp.type ASC  LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), array_merge($params, $params1));
	}

	public static function isAllowedLead($teamId)
	{
		$sql = "SELECT COUNT(*) as cnt FROM team_queue_mapping WHERE tqm_tea_id=:teamId AND tqm_queue_id IN (16,17,20,21) AND tqm_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), array('teamId' => $teamId));
	}

	public static function isAllowedBAR($teamId)
	{
		$sql = "SELECT COUNT(*) as cnt FROM team_queue_mapping WHERE tqm_tea_id=:teamId AND tqm_queue_id IN (19,33) AND tqm_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), array('teamId' => $teamId));
	}

	/**
	 * This function is used for creating  auto FUR for Trip Started For B2B
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function addBARQueue($data, $csr, $allocationType = 0)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$model->contactRequired							 = 0;
			$model->followupPerson							 = 1;
			$model->scq_to_be_followed_up_with_type			 = 0;
			$model->scq_to_be_followed_up_with_value		 = 0;
			$model->scq_to_be_followed_up_with_contact		 = 0;
			$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_ADMIN;
			$model->scq_to_be_followed_up_with_entity_id	 = 0;
			$model->scq_to_be_followed_up_with_entity_rating = -1;
			if ($data['bpr_assignment_id'] > 0 && $data ['type'] == 1)
			{
				$model->scq_to_be_followed_up_by_type	 = 2;
				$model->scq_to_be_followed_up_by_id		 = $csr;
			}
			$model->scq_follow_up_queue_type = ServiceCallQueue::TYPE_BAR;
			$bookingType					 = $data['bkg_booking_type'];
			if (in_array($bookingType, [4, 9, 10, 11, 12, 14, 15]))
			{
				$model->scq_follow_up_queue_type = ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL;
			}
			$model->scq_related_bkg_id		 = $data['bkg_id'];
			$model->scq_platform			 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
			$model->scq_creation_comments	 = $data['desc'] != null ? $data['desc'] : "Operator is still not assigned. Manual action needed. Dispatch team should escalate to field if you need their help. Booking will auto cancel of vendor not assigned in time";
			$entityType						 = UserInfo::TYPE_ADMIN;
			$model->scq_additional_param	 = json_encode(array('allocationType' => $allocationType));
			$returnSet						 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * get BAR Count
	 * @return type int
	 */
	public static function getBARCount()
	{
		$sql = "SELECT  COUNT(*) as cnt FROM `service_call_queue` WHERE 1 AND scq_active=1 AND scq_assigned_uid IS NULL  AND scq_status IN (1,3) AND scq_follow_up_queue_type IN (19,33) AND scq_follow_up_date_time <= NOW()";
		return DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	/**
	 * This function is used for creating  Penalized Customer followup
	 * @var booking $bkgId and $userInfo
	 * @return $returnSet
	 */
	public static function addFollowupForPenalizedCustomer($bkgId)
	{
		$userInfo										 = UserInfo::getInstance();
		$bkgModel										 = Booking::model()->findByPk($bkgId);
		$model											 = new ServiceCallQueue();
		$model->followupPerson							 = 1;
		$model->scq_created_by_type						 = $userInfo->userType;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = 5;
		$model->scq_to_be_followed_up_with_entity_type	 = $userInfo->userType;
		$model->scq_to_be_followed_up_with_entity_id	 = 0;
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 3;
		$model->scq_creation_comments					 = "Customer have been penalized, please followup details.";
		$model->scq_related_bkg_id						 = $bkgId;
		$model->scq_to_be_followed_up_with_value		 = 0;
		$model->scq_to_be_followed_up_with_type			 = 0;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 2;
		$entityType										 = UserInfo::TYPE_SYSTEM;
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
	}

	/**
	 * This function is used for creating  Penalized Vendor followup
	 * @var booking $bkgId and $userInfo
	 * @return $returnSet
	 */
	public static function addFollowupForPenalizedVendor($bkgId)
	{
		$userInfo										 = UserInfo::getInstance();
		$bkgModel										 = Booking::model()->findByPk($bkgId);
		$model											 = new ServiceCallQueue();
		$model->followupPerson							 = 1;
		$model->scq_created_by_type						 = $userInfo->userType;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = 9;
		$model->scq_to_be_followed_up_with_entity_type	 = $userInfo->userType;
		$model->scq_to_be_followed_up_with_entity_id	 = 0;
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 3;
		$model->scq_creation_comments					 = "Vendor have been penalized, please followup details.";
		$model->scq_related_bkg_id						 = $bkgId;
		$model->scq_to_be_followed_up_with_value		 = 0;
		$model->scq_to_be_followed_up_with_type			 = 0;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 2;
		$entityType										 = UserInfo::TYPE_SYSTEM;
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
	}

	/**
	 * get team primary id by queue id  from team queue mapping
	 * @return teamId
	 */
	public static function getTeamPrimaryIdByQueueId($queueId)
	{
		$sql = "SELECT tqm_tea_id  FROM `team_queue_mapping` WHERE `tqm_queue_id` = :queueId AND `tqm_active` = 1 ORDER BY tqm_priority";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['queueId' => $queueId]);
	}

	/**
	 * Count by queue type for particular booking id if it unassign or not
	 * @param string $bookingId
	 * @param string $queueType
	 * @return type int
	 */
	public static function countUnAssignQueueByBkgId($bkgId, $queueIds)
	{
		$queueIds		 = (string) $queueIds;
		DBUtil::getINStatement($queueIds, $bindString, $params);
		$params['bkgId'] = $bkgId;
		$sql			 = "SELECT  scq_id FROM `service_call_queue` WHERE 1 AND scq_follow_up_queue_type IN ($bindString) AND scq_status=1 AND scq_active=1 AND scq_related_bkg_id=:bkgId ORDER BY scq_id DESC LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for processing of  service queue un assignment
	 * @param integer $csr
	 * @param integer $scqId
	 * @return Model of service_call_queue
	 */
	public static function UnAssignment($csr, $scqId)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = ServiceCallQueue::model()->findByPk($scqId);
			if (!$model)
			{
				throw new Exception("Service queueId not valid", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$transaction = DBUtil::beginTransaction();
			switch ((int) $model->scq_follow_up_queue_type)
			{

				case 42:
				case 43:
				case 44:
				case 45:
				case 34:
				case 16:
				case 17:
				case 20:
				case 21:
				case 1:
					if ($model->scq_related_lead_id != null)
					{
						$jsonDecode		 = json_decode($model->scq_additional_param);
						$bookingTempsIds = $jsonDecode->bookingTempReleated;
						BookingTemp::unassignLD($model->scq_related_lead_id, $csr);
						BookingTemp::unassignedIds($bookingTempsIds, $csr, $model->scq_related_lead_id);
					}
					else if ($model->scq_related_bkg_id != null)
					{
						$jsonDecode		 = json_decode($model->scq_additional_param);
						$bookingTempsIds = $jsonDecode->bookingTempReleated;
						Booking::unassignQT($model->scq_related_bkg_id, $csr);
						BookingTemp::unassignedIds($bookingTempsIds, $csr, $model->scq_related_lead_id);
					}
					else
					{
						$jsonDecode		 = json_decode($model->scq_additional_param);
						$bookingTempsIds = $jsonDecode->bookingTempReleated;
						BookingTemp::unassignedIds($bookingTempsIds, $csr, $model->scq_related_lead_id);
					}
					break;
				case 7:
				case 9:
				case 53:
				case 2:
					$jsonDecode	 = json_decode($model->scq_additional_param);
					$bookingIds	 = $jsonDecode->booking;
					Booking::unassignQT($model->scq_related_bkg_id, $csr);
					Booking::unassignRelatedExisting($bookingIds, $csr, $model->scq_related_bkg_id);
					break;
				default :
					break;
			}
			ServiceCallQueue::unassign($scqId);
			CallStatus::updateStatus($scqId, $csr);
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setMessage("csr has been unassigned successfully");
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
			
		}
		return $returnSet;
	}

	/* This function is used for count no.of lead assign to csr
	 * @param type $csrId
	 * @return type int
	 */

	public static function countScq($csrId)
	{
		$params	 = ['csrId' => $csrId];
		$sql	 = "SELECT COUNT(*) FROM service_call_queue WHERE scq_assigned_uid =:csrId  AND scq_status IN(1,3) AND scq_active=1 ";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/* This function is used for fetching  assign call back for particular  csr alomg with status
	 * @param type $csr
	 * @param type $status
	 * @return type  array
	 */

	public static function fetchAssignLeadsByStatus($csr, $status)
	{
		$params	 = ['csr' => $csr, 'status' => $status];
		$sql	 = "SELECT *  FROM service_call_queue WHERE 1 AND scq_status=:status AND  scq_assigned_uid =:csr AND scq_active=1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/* This function is used for fetching  csr  MIN/MAX/AVG time to close lead
	 * @param type $csr
	 * @param type $queueType
	 * @param type $fromdate
	 * @param type $todate
	 * @return type  array
	 */

	public static function getStaticalDataByCsr($csr, $queueType, $fromdate, $todate)
	{

		if ($queueType == '')
		{
			$params	 = ['csr' => $csr, 'fromdate' => $fromdate, 'todate' => $todate];
			$sql	 = "SELECT ROUND(MIN(minutes/60),1) as MinTime,
					ROUND(AVG(minutes/60),1) as AvgTime,
					ROUND(MAX(minutes/60),1) as MaxTime,
					ROUND(SUM(minutes/60),1) AS TotalTime
					FROM
					(
						SELECT
						TIMESTAMPDIFF(SECOND, scq_assigned_date_time, scq_disposition_date) AS minutes
						FROM `service_call_queue`
						WHERE 1
						AND scq_create_date >= '2021-01-01 00:00:00'
						AND (scq_disposition_date BETWEEN :fromdate AND :todate)
						AND scq_active = 1
						AND scq_assigned_uid = scq_disposed_by_uid
						AND scq_disposed_by_uid = :csr
						AND scq_status = 2
					)TEMP";
			return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		}
		else
		{
			$params	 = ['csr' => $csr, 'queueType' => $queueType, 'fromdate' => $fromdate, 'todate' => $todate];
			$sql	 = "SELECT ROUND(MIN(minutes/60),1) as MinTime,
					ROUND(AVG(minutes/60),1) as AvgTime,
					ROUND(MAX(minutes/60),1) as MaxTime,
					ROUND(SUM(minutes/60),1) AS TotalTime
					FROM
					(
						SELECT
						TIMESTAMPDIFF(SECOND, scq_assigned_date_time, scq_disposition_date) AS minutes
						FROM `service_call_queue`
						WHERE 1
						AND scq_create_date >= '2021-01-01 00:00:00'
						AND (scq_disposition_date BETWEEN :fromdate AND :todate)
						AND scq_active = 1
						AND scq_follow_up_queue_type = :queueType
						AND scq_assigned_uid = scq_disposed_by_uid
						AND scq_disposed_by_uid = :csr
						AND scq_status = 2
					)TEMP";
			return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		}
	}

	/**
	 * This function is used for creating  auto FUR for FBG
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURFBG($bookingId)
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$vendorId		 = $bookingModel->bkgBcb->bcb_vendor_id;
		$returnSet		 = new ReturnSet();
		$model			 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $vendorId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_FBG;
			$model->scq_related_bkg_id					 = $bookingId;
			$model->scq_creation_comments				 = "FBG";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating auto FUR For DemMisFire
	 * @param string $bookingId
	 * @param string $team
	 * @return $returnSet
	 */
	public static function autoFURForDemMisFire($bkgId, $team)
	{
		$userInfo										 = UserInfo::getInstance();
		$model											 = new ServiceCallQueue();
		$model->followupPerson							 = 0;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = $team;
		$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_INTERNAL;
		$model->scq_to_be_followed_up_with_entity_id	 = 0;
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 3;
		$model->scq_creation_comments					 = "Operator is still not assigned. Manual action needed. Dispatch team should escalate to field if you need their help. Booking will auto cancel of vendor not assigned in time";
		$model->scq_related_bkg_id						 = $bkgId;
		$model->scq_to_be_followed_up_with_value		 = 0;
		$model->scq_to_be_followed_up_with_type			 = 0;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 3;
		$entityType										 = UserInfo::TYPE_SYSTEM;
		$model->scq_additional_param					 = json_encode(array('DemMisFire' => 1));
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		return $returnSet;
	}

	/**
	 * This function is used for getting all for cancelling auto FUR For DemMisFire
	 * @return query Objects
	 */
	public static function getAllDataByDemMisFire()
	{
		$sql = "SELECT scq_id
                FROM `booking`
                JOIN service_call_queue ON  service_call_queue.scq_related_bkg_id = booking.bkg_id
                WHERE 1
                AND bkg_status NOT IN (2)
                AND (scq_follow_up_queue_type IN (19,33) OR JSON_VALUE(`scq_additional_param`,'$.DemMisFire')=1)
                AND CURDATE() > scq_create_date
                AND scq_status=1
                AND scq_active=1
                AND scq_assigned_uid IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * Count by Dem Mis Fire for particular booking id
	 * @param string $bookingId
	 * @return type int
	 */
	public static function countDemMisFireByBkgId($bkgId, $type = 0)
	{
		if ($type == 0)
		{
			$sql = "SELECT  scq_id FROM `service_call_queue` WHERE 1 AND scq_active=1 AND scq_assigned_uid IS NULL AND  (scq_follow_up_queue_type IN  (33,19) OR JSON_VALUE(`scq_additional_param`,'$.DemMisFire')=1) AND scq_related_bkg_id=:bkgId ORDER BY scq_id DESC LIMIT 0,1";
			return DBUtil::queryRow($sql, DBUtil::MDB(), ['bkgId' => $bkgId]);
		}
		else
		{
			$sql = "SELECT  COUNT(scq_id) AS Id FROM `service_call_queue` WHERE 1 AND scq_active=1  AND (scq_follow_up_queue_type IN (33,19) OR JSON_VALUE(`scq_additional_param`,'$.DemMisFire')=1 ) AND scq_status IN (1, 3) AND scq_related_bkg_id=:bkgId ";
			return DBUtil::queryScalar($sql, DBUtil::MDB(), ['bkgId' => $bkgId]);
		}
	}

	/**
	 * This function is used for creating  auto FUR for customer if he/she not being provide  review after 1 day  completion of booking
	 * @param string $bookingId
	 * @param string $userId
	 * @param string $bkg_country_code
	 * @param string $bkg_contact_no
	 * @return $returnSet
	 */
	public static function autoFURRatingForBooking($bookingId, $userId, $bkg_country_code, $bkg_contact_no)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
			$code										 = $bkg_country_code;
			$number										 = $bkg_contact_no;
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $userId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_ADVOCACY;
			$model->scq_related_bkg_id					 = $bookingId;
			$model->scq_additional_param				 = json_encode(array('rating' => 1));
			$model->scq_creation_comments				 = "This was an upper tier trip. Customer received an SMS to give a review but has not responded yet. We need to know if they had a good trip or if there were any issues. we need to score 5* on every upper tier trip.";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for getting all for cancelling auto FUR For Rating
	 * @return query Objects
	 */
	public static function getAllDataFORAutoFurForRating()
	{
		$sql = "SELECT scq_id
				FROM `booking`
				JOIN ratings ON ratings.rtg_booking_id = booking.bkg_id AND rtg_customer_overall IS NOT NULL AND rtg_active = 1
				JOIN service_call_queue ON  service_call_queue.scq_related_bkg_id = booking.bkg_id
				WHERE 1
				AND booking.bkg_status IN (6, 7)
				AND JSON_VALUE(`scq_additional_param`,'$.rating')=1
				AND scq_status=1
				AND scq_active=1
				AND scq_assigned_uid IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * This function is used for getting scq id for rating booking
	 * @return integer
	 */
	public static function getScqDetailsForRating($bkgId)
	{
		$sql = "SELECT scq_id FROM  service_call_queue  	WHERE 1 AND scq_related_bkg_id=:bkgId AND JSON_VALUE(`scq_additional_param`,'$.rating')=1	AND scq_status=1 AND scq_active=1 AND scq_assigned_uid IS NULL";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['bkgId' => $bkgId]);
	}

	/**
	 * getting  Originating followup id
	 * @param int  $scqId
	 * @return type int
	 */
	public static function getOriginatingfollowup($scqId)
	{
		$sql = "SELECT  scq_id  FROM `service_call_queue` WHERE 1 AND scq_prev_or_originating_followup = :scqId AND scq_active =1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['scqId' => $scqId]);
	}

	/**
	 * getting  if lead exist or not
	 * @param string contact no
	 * @param string Follow up Type
	 * @return type int
	 */
	public static function checkLeadExist($contact_no, $type, $agentId = 0)
	{

		$where = $type == 1 ? " AND scq_follow_up_queue_type IN (16,20) " : " AND scq_follow_up_queue_type IN (17,21) ";
		if ($agentId > 0)
		{
			$where = $type == 1 ? " AND scq_follow_up_queue_type IN (42,44) " : " AND scq_follow_up_queue_type IN (43,45) ";
		}
		$sql = "SELECT COUNT(*) AS cnt
                FROM `service_call_queue`
                WHERE 1
                AND scq_to_be_followed_up_with_type = 1
                AND scq_to_be_followed_up_with_value = :scq_to_be_followed_up_with_value
                AND scq_active = 1 AND scq_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND NOW()  $where ";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['scq_to_be_followed_up_with_value' => $contact_no]);
	}

	/**
	 * marked Follow up  inactive
	 * @param string scqId
	 * @return type int
	 */
	public static function markScqInactive($scqId)
	{
		$sql	 = "UPDATE service_call_queue SET scq_status =0,`scq_active`=0 WHERE scq_id =:scq_id AND scq_status IN (1,3) AND scq_active=1";
		$numrows = DBUtil::execute($sql, ['scq_id' => $scqId]);
		if ($numrows == 0)
		{
			throw new Exception("Failed to marked inactive queue id:  {$scqId }", ReturnSet::ERROR_FAILED);
		}
		return ( $numrows > 0);
	}

	/**
	 * This function is used for getting all team json string
	 * @param type $query
	 * @param type $admuser
	 * @return type  json
	 */
	public function getTeamsbyQuery($query, $teamId = '')
	{

		$rows		 = $this->getTeams($query, $teamId);
		$arrTeams	 = array();
		foreach ($rows as $row)
		{
			$arrTeams[] = array("id" => $row['tea_id'], "text" => $row['tea_name']);
		}
		$data = CJSON::encode($arrTeams);
		return $data;
	}

	/**
	 * This function is used for getting all team list string
	 * @param type $query
	 * @param type $admuser
	 * @return type  array
	 */
	public function getTeams($query = '', $teamId = '')
	{
		$qry	 = '';
		$query	 = ($query == null || $query == "") ? "" : $query;
		DBUtil::getLikeStatement($query, $bindString0, $params1);
		if ($teamId != '')
		{
			$qry1 = " AND tea_id in ($teamId)";
		}
		if ($query != '')
		{

			$qry .= " AND (tea_name LIKE $bindString0) ";
		}
		if ($teamId != '')
		{
			$sql = "SELECT tea_id, tea_name FROM teams  WHERE tea_status =1 $qry1";
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$sql = "SELECT tea_id, tea_name FROM teams  WHERE tea_status  =1  $qry $qry1 ORDER BY tea_id ASC LIMIT 0,15 ";

			return DBUtil::query($sql, DBUtil::SDB(), $params1);
		}
	}

	public static function addBookingCompleteReview($bkgId, $driverId, $desc)
	{
		$scqModel										 = new ServiceCallQueue();
		$contactId										 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
		$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
		$code											 = $arrPhoneByPriority['phn_phone_country_code'];
		$number											 = $arrPhoneByPriority['phn_phone_no'];
		Filter::parsePhoneNumber($number, $code, $phone);
		$scqModel->scq_to_be_followed_up_with_value		 = $code . $phone;
		$scqModel->contactRequired						 = 1;
		$scqModel->scq_to_be_followed_up_with_type		 = 2;
		$scqModel->scq_to_be_followed_up_with_contact	 = $contactId;
		$scqModel->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_BOOKING_COMPLETE_REVIEW;
		$scqModel->scq_creation_comments				 = trim($desc);
		$scqModel->scq_to_be_followed_up_with_entity_id	 = 0;
		$scqModel->scq_to_be_followed_up_by_type		 = 1;
		$scqModel->scq_related_bkg_id					 = $bkgId;

		$platform = ServiceCallQueue::PLATFORM_DRIVER_APP;
		ServiceCallQueue::model()->create($scqModel, UserInfo:: TYPE_ADMIN, $platform);

		return $scqModel;
	}

	/**
	 * This function is used for fetching all leads for csr
	 * @param type $isEligibleForNewLead
	 * @param type $elgibileScore
	 * @return type 
	 * NONE
	 */
	public static function updatePendingLeadsCron($isEligibleForNewLead, $elgibileScore, $agentId = 0)
	{
		$limit	 = 0;
		$flag	 = 0;
		$model	 = new ServiceCallQueue();
		while (true)
		{
			$rows = BookingTemp::getPendingLeadsCron($limit, $isEligibleForNewLead, $elgibileScore, $agentId);
			foreach ($rows as $row)
			{
				if ($row['type'] == 2)
				{
					$isRelatedQuoteCnt = ServiceCallQueue::isRelatedQuoteExist($row['bkg_id'], $agentId);
					if ($isRelatedQuoteCnt == 0)
					{
						$model = self::updateLead($row, 1, $agentId);
						if ($model->scq_id > 0)
						{
							self::addAdditionalParams($model, $agentId);
						}
					}
					else
					{
						Booking::stopDuplicateQuote($row['bkg_id']);
						Logger::writeToConsole("Related Quote found ($isRelatedQuoteCnt): " . json_encode($row));
					}
				}
				else if ($row['type'] == 1)
				{
					$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($row['bkg_id'], $agentId);
					if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
					{
						$model = self::updateLead($row, 1, $agentId);
						if ($model->scq_id > 0)
						{
							self::addAdditionalParams($model, $agentId);
						}
					}
					elseif ($rowLeadStatus['isQueuedUp'] == 0)
					{
						BookingTemp::model()->inactivateDuplicateLeadById($row['bkg_id']);
					}
					else
					{
						BookingTemp::model()->inactivateDuplicateLeadById($row['bkg_id']);
						Logger::writeToConsole("Related Leads found ($isRelatedQuoteCnt): " . json_encode($row));
					}
				}
				if (ServiceCallQueue::getLeadCount($isEligibleForNewLead, $elgibileScore, $agentId) > (int) Config::get('SCQ.maxLeadAllowed'))
				{
					$flag = 1;
					break;
				}
			}
			$limit++;
			if ($flag == 1 || $limit > 2)
			{
				break;
			}
		}
	}

	/** this function is used to check for duplicate leads at the time for cron process
	 * @param query $model
	 * @return static
	 */
	public static function addAdditionalParams($model, $agentId = 0)
	{
		if ($model->scq_related_lead_id != null)
		{
			$resultLD			 = BookingTemp::model()->getUserbyId($model->scq_related_lead_id, $agentId);
			$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultLD["bkg_user_id"], $resultLD["email"], $resultLD['bkg_contact_no'], $agentId);
			$getRelatedLead		 = [];
			$lead				 = "";
			foreach ($getRelatedLeadIds as $leadArr)
			{
				$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
				if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
				{
					$lead						 .= $leadArr['bkg_id'] . ",";
					$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
				}
			}
			$data = json_encode(array('bookingTempReleated' => rtrim($lead, ",")));
			ServiceCallQueue::updateAdditonalParam($model->scq_id, $data);
		}
		else if ($model->scq_related_bkg_id != null)
		{
			$resultQT			 = Booking::model()->getUserbyIdNew($model->scq_related_bkg_id, $agentId);
			$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no'], $agentId);
			$lead				 = "";
			$getRelatedLead		 = [];
			foreach ($getRelatedLeadIds as $leadArr)
			{
				$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
				if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
				{
					$lead						 .= $leadArr['bkg_id'] . ",";
					$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
				}
			}
			$getRelatedQuoteIds	 = Booking::getRelatedIds($resultQT["bkg_user_id"], $resultQT["bkg_user_email"], $resultQT['bkg_contact_no'], $agentId);
			$quote				 = "";
			$getRelatedQuote	 = [];
			foreach ($getRelatedQuoteIds as $quoteArr)
			{
				if (ServiceCallQueue::isRelatedQuoteExist($quoteArr['bkg_id'], $agentId) == 0)
				{
					$quote						 .= $quoteArr['bkg_id'] . ",";
					$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
				}
			}
			$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
			ServiceCallQueue::updateAdditonalParam($model->scq_id, $data);
		}
		else
		{
			$contactId	 = ($model->scq_to_be_followed_up_with_contact == null) ? 0 : $model->scq_to_be_followed_up_with_contact;
			$arrProfile	 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_CONSUMER);
			if (!empty($arrProfile["id"]))
			{
				$userId = $arrProfile['id'];
			}
			$contactEmail	 = ContactEmail::getEmailByBookingUserId($userId);
			$code			 = 91;
			if ($model->scq_to_be_followed_up_with_type == 2)
			{
				$phone = $model->scq_to_be_followed_up_with_value;
			}
			else
			{
				$phone = ContactPhone::getContactNumber($contactId);
			}
			$phone				 = preg_replace('/[^0-9\-]/', '', $phone);
			Filter::parsePhoneNumber($phone, $code, $custUserPhone);
			$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($userId, $contactEmail, $custUserPhone, $agentId);
			$lead				 = "";
			$getRelatedLead		 = [];
			foreach ($getRelatedLeadIds as $leadArr)
			{
				$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($leadArr['bkg_id'], $agentId);
				if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
				{
					$lead						 .= $leadArr['bkg_id'] . ",";
					$getRelatedLead[]['bkg_id']	 = $leadArr['bkg_id'];
				}
			}
			$getRelatedQuoteIds	 = Booking::getRelatedIds($userId, $contactEmail, $custUserPhone, $agentId);
			$quote				 = "";
			$getRelatedQuote	 = [];
			foreach ($getRelatedQuoteIds as $quoteArr)
			{
				if (ServiceCallQueue::isRelatedQuoteExist($quoteArr['bkg_id'], $agentId) == 0)
				{
					$quote						 .= $quoteArr['bkg_id'] . ",";
					$getRelatedQuote[]['bkg_id'] = $quoteArr['bkg_id'];
				}
			}
			$data = json_encode(array('bookingTempReleated' => rtrim($lead, ","), 'bookingReleated' => rtrim($quote, ",")));
			ServiceCallQueue::updateAdditonalParam($model->scq_id, $data);
		}
	}

	/**
	 * This function is used for creating  auto FUR  for gozo now
	 * @param string $bcbId
	 * @return $returnSet
	 */
	public static function autoFURGozoNow($bcbId)
	{
		$bcbModel	 = BookingCab::model()->findByPk($bcbId);
		$vendorId	 = $bcbModel->bcb_vendor_id;
		$bkgId		 = $bcbModel->bookings[0]->bkg_id;
		$scqId		 = ServiceCallQueue::getScqIdGozoNowForVendor($bkgId, $vendorId, ServiceCallQueue::TYPE_GOZONOW);
		$returnSet	 = new ReturnSet();
		if ($scqId > 0)
		{
			$scqDetails	 = ServiceCallQueue::model()->detail($scqId);
			$data		 = [];
			if ($scqDetails['scq_id'] > 0)
			{
				$data	 = ['followupId' => (int) $scqDetails['scq_id'], 'queNo' => $scqDetails['scq_queue_no'], 'followupCode' => $scqDetails['scq_unique_code'], 'waitTime' => $scqDetails['scq_waittime']];
				$success = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		else
		{
			$model = new ServiceCallQueue();
			try
			{
				$contactId									 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
				$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
				$code										 = $arrPhoneByPriority['phn_phone_country_code'];
				$number										 = $arrPhoneByPriority['phn_phone_no'];
				$model->contactRequired						 = 1;
				Filter::parsePhoneNumber($number, $code, $phone);
				$model->scq_to_be_followed_up_with_value	 = $code . $phone;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->scq_to_be_followed_up_with_entity_id = $vendorId;
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_GOZONOW;
				$model->scq_related_bkg_id					 = $bkgId;
				$model->scq_creation_comments				 = "There are some problem with vendor. Please call to know the reason";
				$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}


		return $returnSet;
	}

	/**
	 * This function is used to get service call queue  for given booking,vendorId and queue type
	 * @param string $bkgId
	 * @param string $vendorId
	 * @param string $queueType
	 * @return type int
	 */
	public static function getScqIdGozoNowForVendor($bkgId, $vendorId, $queueType)
	{
		$sql = "SELECT  scq_id FROM `service_call_queue` WHERE 1 AND scq_related_bkg_id=:bkgId AND scq_follow_up_queue_type=:queueType AND scq_status  IN (1,3) AND scq_active=1 AND scq_to_be_followed_up_with_entity_type=2 AND scq_to_be_followed_up_with_entity_id=:vendorId";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['bkgId' => $bkgId, 'vendorId' => $vendorId, 'queueType' => $queueType]);
	}

	/**
	 * This function is used for getting all lead/quote
	 * @return query Objects
	 */
	public static function getAllDataByLead()
	{
		$sql = "SELECT
                scq_id,
                TIMESTAMPDIFF(HOUR,scq_create_date, NOW()) AS scqCreateHours,
                scq_priority_score
                FROM `service_call_queue`
                WHERE     1
                AND scq_follow_up_queue_type IN (16,17,20,21,34,42,43,44,45)
                AND NOW() > scq_create_date
                AND scq_status = 1
                AND scq_active = 1
                AND scq_assigned_uid IS NULL HAVING scqCreateHours>=1";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * This function is used to update Priority Score in Service call queue by scq id
	 * @return type int
	 */
	public static function updateScqPriorityScore($scq_id, $priorityScore)
	{
		$sql = "UPDATE service_call_queue SET scq_priority_score =:scq_priority_score WHERE scq_id =:scq_id AND scq_active=1";
		DBUtil::execute($sql, ['scq_id' => $scq_id, 'scq_priority_score' => $priorityScore]);
	}

	/**
	 * @param array $row
	 * @return static
	 */
	public static function updateAutoAssignLead($row, $type = 0)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::info("Row Value  " . json_encode($row));
		$count = ServiceCallQueue::checkLeadExist($row['bkg_contact_no'], $row['type']);
		if ($count == 0)
		{
			try
			{
				$model											 = new ServiceCallQueue();
				$model->contactRequired							 = 0;
				$model->followupPerson							 = 1;
				$model->scq_to_be_followed_up_by_type			 = 1;
				$model->scq_to_be_followed_up_by_id				 = 0;
				$model->scq_to_be_followed_up_with_type			 = 2;
				$model->scq_to_be_followed_up_with_value		 = $row['bkg_contact_no'];
				$model->scq_to_be_followed_up_with_entity_type	 = 1;
				$model->scq_to_be_followed_up_with_entity_id	 = $row['bkg_user_id'] != null ? $row['bkg_user_id'] : 0;
				$model->scq_to_be_followed_up_with_entity_rating = -1;
				$model->subQueue								 = $row['type'] == 2 ? ServiceCallQueue::SUB_QUOTE_CREATED_FOLLOWUP : ServiceCallQueue::SUB_LEADS;
				$model->scq_priority_score						 = ($row['csrRank'] + $row['timeRank'] + $row['advanceRank'] + $row['pickupRank'] + $row['followup_rank']);
				if ($row['type'] == 2)
				{
					$model->scq_ref_type		 = 2;
					$model->scq_related_bkg_id	 = $row['bkg_id'];
				}
				else
				{
					$model->scq_ref_type		 = 1;
					$model->scq_related_lead_id	 = $row['bkg_id'];
				}
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_AUTO_FOLLOWUP_LEAD;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($model->scq_to_be_followed_up_with_entity_id);
				$model->scq_status							 = 1;
				$model->scq_reason_id						 = 2;
				$model->scq_creation_comments				 = $row['desc'] != null ? $row['desc'] : null;
				$platform									 = $type == 1 ? ServiceCallQueue::PLATFORM_SYSTEM : ServiceCallQueue::PLATFORM_ADMIN_CALL;
				ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, $platform);
			}
			catch (Exception $ex)
			{
				Logger::trace("Serivice::updateLead : " . $ex->getMessage());
				Logger::exception($ex);
				Logger::writeToConsole($ex->getMessage());
			}
			Logger::unsetModelCategory(__CLASS__, __FUNCTION__);

			return $model;
		}
	}

	/*
	 * @param string $vendorId
	 * @param string $queueType
	 * @return type int
	 */

	public static function checkDuplicateDocumetApprovalForVendor($vendorId, $queueType)
	{
		$sql = "SELECT  COUNT(*) as active_cbr_count FROM `service_call_queue` WHERE 1 AND scq_follow_up_queue_type=:queueType AND scq_status IN (1,3) AND scq_active=1 AND  scq_to_be_followed_up_with_entity_type=2 AND scq_to_be_followed_up_with_entity_id=:vendorId";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['vendorId' => $vendorId, 'queueType' => $queueType]);
	}

	/**
	 * This function is used for creating  auto FUR for document approval for driver and cabs
	 * @param string $vendorId
	 * @return $returnSet
	 */
	public static function autoFURDocumentApproval($row)
	{

		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($row['vnd_id'], UserInfo::TYPE_VENDOR);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $row['vnd_id'];
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DOCUMENT_APPROVAL;
			$model->scq_creation_comments				 = "Vendor has uploaded document for driver/cab";
			$model->scq_additional_param				 = json_encode(array('driverContactId' => $row['driverContactId'] != null ? $row['driverContactId'] : 0, 'vehicleIds' => $row['vehicleIds'] != NULL ? $row['vehicleIds'] : 0));
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for Trip Started For B2B Hour
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURTripStartedForB2BHour($bookingId)
	{
		$returnSet	 = new ReturnSet();
		$count		 = ServiceCallQueue::countQueueByBkgId($bookingId, ServiceCallQueue::TYPE_B2B_POST_PICKUP);
		if ($count == 0)
		{
			$bookingModel	 = Booking::model()->findByPk($bookingId);
			$driverId		 = $bookingModel->bkgBcb->bcb_driver_id;
			$userId			 = $bookingModel->bkgUserInfo->bkg_user_id;
			$fromDate		 = $bookingModel->bkgTrack->bkg_trip_start_time;
			$toDate			 = Filter::getDBDateTime();
			$timediff		 = DBUtil::getTimeDiff($fromDate, $toDate);
			$isUpperTier	 = in_array($bookingModel->bkgSvcClassVhcCat->scv_scc_id, [1, 6]) ? 0 : 1;
			$model			 = new ServiceCallQueue();
			try
			{
				$contactId									 = $isUpperTier == 0 ? ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER) : ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
				$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
				$code										 = $isUpperTier == 0 ? $arrPhoneByPriority['phn_phone_country_code'] : $bookingModel->bkgUserInfo->bkg_country_code;
				$number										 = $isUpperTier == 0 ? $arrPhoneByPriority['phn_phone_no'] : $bookingModel->bkgUserInfo->bkg_contact_no;
				$model->contactRequired						 = 1;
				Filter::parsePhoneNumber($number, $code, $phone);
				$model->scq_to_be_followed_up_with_value	 = $code . $phone;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->scq_to_be_followed_up_with_entity_id = $isUpperTier == 0 ? $driverId : $userId;
				$model->scq_follow_up_queue_type			 = in_array($bookingModel->bkgSvcClassVhcCat->scv_scc_id, [1, 6]) ? ServiceCallQueue::TYPE_B2B_POST_PICKUP : ServiceCallQueue::TYPE_UPSELL_UPPERTIER;
				$model->scq_related_bkg_id					 = $bookingId;
				$model->scq_creation_comments				 = $isUpperTier == 0 ? "B2B trip has already started. Call driver and ask how the trip is going and ensure all is well.Ask driver if he can have you talk to customer. And confirm with customer that everything is going well.If customer is unhappy - create a immediate service request for Customer support team against the same booking ID. Escalate the booking to Customer support leader if issue is serious.If things are going well - ask customer if there is anything else you need help with. (do not directly try to offer to create a booking. If customer asks then you can create a follow up for retail sales team but you should not directly offer to create a booking)" : "Customers trip has already started. Call them and ask how the trip is going and ensure all is well. If customer is unhappy - create a immediate service request for Customer support team against the same booking ID. Escalate the booking to Customer support leader if issue is serious. If things are going well - ask customer if they need any booking and you can create for them. Create a service request for Retail sales to follow up with this same customer. Remind the customer - they can get 20% of their trip cost back as cash if they refer a friend to travel with us within the next 5 days.";
				$entityType									 = $isUpperTier == 0 ? UserInfo::TYPE_DRIVER : UserInfo::TYPE_CONSUMER;
				$returnSet									 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating auto FUR for Trip Started Hour
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURTripStartedHour($bookingId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet	 = new ReturnSet();
		$queueType	 = ServiceCallQueue::TYPE_UPSELL . "," . ServiceCallQueue::TYPE_UPSELL_UPPERTIER;
		$count		 = ServiceCallQueue::countQueueByBkgId($bookingId, $queueType);
		/* Auto FUR Trip started Start */
		if ($count == 0)
		{
			$bookingModel	 = Booking::model()->findByPk($bookingId);
			$fromDate		 = $bookingModel->bkgTrack->bkg_trip_start_time;
			$userId			 = $bookingModel->bkgUserInfo->bkg_user_id;
			$toDate			 = Filter::getDBDateTime();
			$timediff		 = DBUtil::getTimeDiff($fromDate, $toDate);
			$model			 = new ServiceCallQueue();
			try
			{
				$contactId									 = ContactProfile::getByEntityId($userId, UserInfo::TYPE_CONSUMER);
				$code										 = $bookingModel->bkgUserInfo->bkg_country_code;
				$number										 = $bookingModel->bkgUserInfo->bkg_contact_no;
				$model->contactRequired						 = 1;
				Filter::parsePhoneNumber($number, $code, $phone);
				$model->scq_to_be_followed_up_with_value	 = $code . $phone;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->scq_to_be_followed_up_with_entity_id = $userId;
				$model->scq_follow_up_queue_type			 = in_array($bookingModel->bkgSvcClassVhcCat->scv_scc_id, [1, 6]) ? ServiceCallQueue::TYPE_UPSELL : ServiceCallQueue::TYPE_UPSELL_UPPERTIER;
				$model->scq_related_bkg_id					 = $bookingId;
				$model->scq_creation_comments				 = "Customers trip has already started. Call them and ask how the trip is going and ensure all is well. If customer is unhappy - create a immediate service request for Customer support team against the same booking ID. Escalate the booking to Customer support leader if issue is serious. If things are going well - ask customer if they need any booking and you can create for them. Create a service request for Retail sales to follow up with this same customer. Remind the customer - they can get 20% of their trip cost back as cash if they refer a friend to travel with us within the next 5 days.";
				$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for document approval for driver and cabs
	 * @param string $vendorId
	 * @return $returnSet
	 */
	public static function autoFURVendorUpdateService($vendorIds)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($vendorIds, UserInfo::TYPE_VENDOR);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $vendorIds;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VENDOR_APPROVAl;
			$model->scq_creation_comments				 = "Vendor has requested for his changed in his service update";
			$model->scq_additional_param				 = json_encode(array('VendorUpdateService' => $vendorIds));
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo::TYPE_VENDOR, ServiceCallQueue::PLATFORM_VENDOR_APP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * check if any vendor update service exists or not
	 * @return type int
	 */
	public static function isVendorServiceExists($vndId)
	{
		$param	 = array('vndId' => $vndId);
		$sql	 = 'SELECT COUNT(*) as cnt FROM `service_call_queue` 
                WHERE 1
                AND scq_to_be_followed_up_with_entity_type=2
                AND scq_to_be_followed_up_with_entity_id=:vndId
                AND json_extract(`scq_additional_param`,"$.VendorUpdateService")=:vndId
                AND scq_follow_up_queue_type=15
                AND scq_active =1 
                AND scq_status IN (1,3)';
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
	}

	/**
	 * This function is used to return/show the previous and next service call in case of rescheduling
	 * @param type $scqId
	 * @return array 
	 */
	public static function getPrevAndForwardScq($scqId)
	{
		$param1	 = array('scqId' => $scqId);
		$prevSql = "SELECT scq_prev_or_originating_followup FROM service_call_queue WHERE scq_id =:scqId";
		$prevScq = DBUtil::queryScalar($prevSql, DBUtil::SDB(), $param1);
		$param2	 = array('nextScqId' => $scqId);
		$nextSql = "SELECT scq_id FROM service_call_queue WHERE scq_prev_or_originating_followup =:nextScqId";
		$nextScq = DBUtil::queryScalar($nextSql, DBUtil::SDB(), $param2);
		return ['prevScq' => $prevScq, 'nextScq' => $nextScq];
	}

	/**
	 * This function is used check if team is allowed to CSA leads
	 * @param type $teamId
	 * @return int
	 */
	public static function isAllowedCSA($teamId)
	{
		$sql = "SELECT COUNT(*) as cnt FROM team_queue_mapping WHERE tqm_tea_id=:teamId AND tqm_queue_id IN (32) AND tqm_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), array('teamId' => $teamId));
	}

	/**
	 * This function is used for creating  auto FUR for Trip Started For B2B
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function addCSAQueue($data, $team, $allocationType = 0)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$model->contactRequired							 = 0;
			$model->followupPerson							 = 0;
			$model->scq_to_be_followed_up_by_type			 = 1;
			$model->scq_to_be_followed_up_by_id				 = $team;
			$model->scq_to_be_followed_up_with_type			 = 0;
			$model->scq_to_be_followed_up_with_value		 = 0;
			$model->scq_to_be_followed_up_with_contact		 = 0;
			$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_ADMIN;
			$model->scq_to_be_followed_up_with_entity_id	 = 0;
			$model->scq_to_be_followed_up_with_entity_rating = -1;
			$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_CSA;
			$model->scq_related_bkg_id						 = $data['bkg_id'];
			$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
			$model->scq_creation_comments					 = $data['desc'] != null ? $data['desc'] : "Operator is still not assigned. Manual action needed. Dispatch team had escalate to field Operations for your help. Booking will auto cancel of vendor not assigned in time";
			$entityType										 = UserInfo::TYPE_SYSTEM;
			$model->scq_additional_param					 = json_encode(array('DTM' => 1, 'Region' => $data['stt_zone'], 'Path' => $data['Controller'], 'allocationType' => $allocationType));
			$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used getting lead for CSA Queue
	 * @return integer booking Id
	 */
	public static function getDataForCSAQueue($fromDate, $toDate)
	{
		$param	 = ['fromDate' => $fromDate, 'toDate' => $toDate];
		$sql	 = "SELECT bkg_id,bkg_booking_type,stt_zone
                    FROM `booking_pref`
                    INNER JOIN booking ON booking.bkg_id = bpr_bkg_id
					INNER JOIN  cities on bkg_from_city_id = cty_id
					INNER JOIN  states on cty_state_id = stt_id
                    WHERE 1
                    AND bkg_pickup_date BETWEEN :fromDate AND :toDate
                    AND bkg_active=1
                    AND booking.bkg_status IN (2)
					AND ( bpr_assignment_level IN (2,3) OR bkg_critical_assignment=1)
					GROUP BY bkg_id
                    ORDER BY bkg_critical_assignment DESC, bkg_manual_assignment DESC, bkg_critical_score DESC,booking.bkg_pickup_date ASC,bkg_create_date ASC";
		return DBUtil::query($sql, DBUtil::SDB(), $param);
	}

	public static function getScqIdForCSA($bkgId)
	{
		$sql = "SELECT scq_id
                FROM `booking`
                JOIN service_call_queue ON  service_call_queue.scq_related_bkg_id = booking.bkg_id
                WHERE 1
                AND bkg_id=:bkg_id
                AND bkg_pickup_date<CURDATE()
                AND bkg_status NOT IN (2)
                AND (scq_follow_up_queue_type IN (19,33) OR JSON_VALUE(`scq_additional_param`,'$.DemMisFire')=1)
                AND CURDATE() > scq_create_date
                AND scq_status=1
                AND scq_active=1
                AND scq_assigned_uid IS NULL";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['bkg_id' => $bkgId]);
	}

	/**
	 * get queue name by queue id  from team queue mapping
	 * @return string
	 */
	public static function getQueueByQueueId($queueId)
	{
		$sql = "SELECT tqm_queue_name FROM `team_queue_mapping` WHERE `tqm_queue_id` = :queueId AND `tqm_active` = 1 ORDER BY tqm_priority ASC,tqm_queue_weight DESC";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['queueId' => $queueId]);
	}

	/**
	 * check if any vendor payment cbr exists or not
	 * @return type int
	 */
	public static function isVendorPaymentExists($vndId)
	{
		$param	 = array('vndId' => $vndId);
		$sql	 = 'SELECT COUNT(*) as cnt FROM `service_call_queue` 
                WHERE 1
                AND scq_to_be_followed_up_with_entity_type=2
                AND scq_to_be_followed_up_with_entity_id=:vndId
                AND scq_follow_up_queue_type=23
                AND scq_active =1 
                AND scq_status=1';
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
	}

	/**
	 * This function is used to close vendor payment cbr  for any particular vendor 
	 * @return type int
	 */
	public static function closeVendorPaymentRequest($vndIds)
	{
		DBUtil::getINStatement($vndIds, $bindString, $params);
		$sql = "UPDATE service_call_queue 
                SET scq_disposed_by_uid =10,
                scq_status = 0,
                scq_time_to_close=TIMESTAMPDIFF(MINUTE, scq_assigned_date_time,NOW()),
                scq_active=0,
                scq_assigned_uid=10,
                scq_assigned_date_time=NOW(),
                scq_disposition_date = NOW(), 
                scq_disposition_comments ='Your payment has been processed .It will be credited into your bank account within 1-2 business day'
                WHERE 1 
                AND scq_assigned_uid IS NULL
                AND scq_to_be_followed_up_with_entity_type=2
                AND scq_to_be_followed_up_with_entity_id IN ({$bindString})
                AND scq_follow_up_queue_type=23
                AND scq_active=1 
                AND scq_status=1";
		return DBUtil::execute($sql, $params);
	}

	/**
	 * This function is used to get list of online  csr  by category and department id
	 * @param type $cdt_id
	 * @return type  query object
	 */
	public static function getOnlineCsr($cdt_id)
	{
		$where	 = "";
		$params	 = array();
		if ($cdt_id > 0)
		{
			$params['cdt_id']	 = $cdt_id;
			$where				 = " AND  cdt.cdt_id =:cdt_id ";
		}
		$sql = "SELECT
                    DISTINCT adm.adm_id,
                    adm.gozen,
                    scq_id,
                    teams.tea_name
                    FROM admins adm
                    JOIN `admin_profiles` adp ON adp.adp_adm_id = adm.adm_id AND  adm.adm_active=1
                    JOIN
                    (
                        SELECT
                        ado_admin_id,
                        MAX(ado_time) as ado_time
                        FROM `admin_onoff`
                        WHERE 1 
                        GROUP BY ado_admin_id
                    ) aadmin_onoff on aadmin_onoff.ado_admin_id = adm.adm_id
                    INNER Join admin_onoff admOnOff ON admOnOff.ado_admin_id=aadmin_onoff.ado_admin_id AND aadmin_onoff.ado_time=admOnOff.ado_time AND admOnOff.ado_status=1
                    JOIN
                    (
                            SELECT
                            admin_profiles.adp_adm_id,
                            JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtWeight'))) AS cdtWeight,
                            JSON_UNQUOTE(JSON_EXTRACT(admin_profiles.adp_cdt_id, CONCAT('$[', pseudo_rows.row, '].cdtId'))) AS cdtId
                            FROM admin_profiles
                            JOIN pseudo_rows
                            WHERE 1
                            HAVING cdtId IS NOT NULL
                    ) temp ON temp.adp_adm_id=adp.adp_adm_id
                    JOIN `cat_depart_team_map` cdt  ON temp.cdtId=cdt.cdt_id
                    JOIN teams ON cdt.cdt_tea_id = teams.tea_id
                    JOIN departments ON cdt_dpt_id = departments.dpt_id
                    JOIN categories ON cdt_cat_id = categories.cat_id
                    LEFT JOIN service_call_queue ON service_call_queue.scq_assigned_uid=adm.adm_id AND service_call_queue.scq_active=1 AND service_call_queue.scq_status IN (1,3)
                    WHERE 1 $where
                    AND
                    (
                            (tea_start_time IS NULL AND tea_stop_time IS NULL)
                                    OR
                            (tea_start_time < tea_stop_time AND CURRENT_TIME() BETWEEN tea_start_time AND tea_stop_time)
                                    OR
                            (tea_stop_time < tea_start_time AND CURRENT_TIME() < tea_start_time AND CURRENT_TIME() < tea_stop_time)
                                    OR
                            (tea_stop_time < tea_start_time AND CURRENT_TIME() > tea_start_time)
                    )";
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * @param array $data
	 * @param string $groupBy
	 * @param string $csrSearch
	 * @param boolean $groupByCSR
	 * @return \CSqlDataProvider
	 */
	public static function csrLeadConversionReport($data = [], $groupBy = 'date', $csrSearch = '', $groupByCSR = false)
	{
		$fromDate	 = $data['from_date'] . ' 00:00:00';
		$toDate		 = $data['to_date'] . ' 23:59:59';

		$bkgCSRSearch			 = '';
		$scqCSRSearch			 = '';
		$groupBySCQ				 = "";
		$groupByBkg				 = "";
		$joinSCQ				 = "";
		$joinBkg				 = "";
		$mobileApp				 = "";
		$tempMobileApp			 = "";
		$weekDayqry				 = '';
		$bkgWeekDayqry			 = "";
		$btWeekDayqry			 = "";
		$scqWeekDayqry			 = "";
		$isGozonowSelected		 = '';
		$isGozonowTempSelected	 = '';
		$scqLeadSql				 = $bkgAdminSql			 = "";
		$dateFormat				 = ['date' => '%Y-%m-%d', 'week' => '%x-%v', 'month' => '%Y-%m', 'hour' => '%Y-%m-%d %H'];
		$date					 = $dateFormat[$groupBy];
		$params					 = [];
		if ($groupBy == 'hour' || $groupBy == 'date')
		{
			$restrictSCQQry			 = " AND TIME(scq_assigned_date_time)<=CURRENT_TIME()";
			$restrictBkgQry			 = " AND TIME(bkg_create_date)<=CURRENT_TIME()";
			$restrictBkgConfirmQry	 = " AND TIME(bkg_confirm_datetime)<=CURRENT_TIME()";
		}
		else if ($groupBy == 'week')
		{
			$restrictSCQQry			 = " AND WEEKDAY(scq_assigned_date_time)<=WEEKDAY(:toDate)";
			$restrictBkgQry			 = " AND WEEKDAY(bkg_create_date)<=WEEKDAY(:toDate)";
			$restrictBkgConfirmQry	 = " AND WEEKDAY(bkg_confirm_datetime)<=WEEKDAY(:toDate)";
		}
		else if ($groupBy == 'month')
		{
			$restrictSCQQry			 = " AND DAYOFMONTH(scq_assigned_date_time)<=DAYOFMONTH(:toDate)";
			$restrictBkgQry			 = " AND DAYOFMONTH(bkg_create_date)<=DAYOFMONTH(:toDate)";
			$restrictBkgConfirmQry	 = " AND DAYOFMONTH(bkg_confirm_datetime)<=DAYOFMONTH(:toDate)";
		}

		if ($data['restrictCurrentTime'] != 1)
		{
			$restrictSCQQry			 = "";
			$restrictBkgQry			 = "";
			$restrictBkgConfirmQry	 = "";
		}

		$params['time']			 = (($data['restrictCurrentTime'] == 1) ? 0 : 1);
		$params['selfCreated']	 = (($data['selfCreated'] == 1) ? 0 : 1);
		$params['format']		 = $date;
		$params['fromDate']		 = $fromDate;
		$params['toDate']		 = $toDate;
		$teamLead				 = $data['teamLead'];
		$isMobile				 = $data['isMobile'];
		$isGozonow				 = $data['isGozonow'];
		$weekDays				 = $data['weekDays'];
		$bkgTypes				 = $data['bkgTypes'];
		$regions				 = $data['regions'];
		$isAndroid				 = $data['isAndroid'];
		$isIOS					 = $data['isIOS'];

		if (count($regions) > 0)
		{

			$stateIds	 = States::getIdsByRegion($regions);
			$regionQry	 = " AND cty.cty_state_id IN ($stateIds)";
			$cityBkgJoin = "LEFT JOIN cities cty1 ON cty1.cty_id=bkg.bkg_from_city_id";
			$cityBtJoin	 = "LEFT JOIN cities cty2 ON cty2.cty_id=bt.bkg_from_city_id";
			$cityJoin	 = "INNER JOIN cities cty ON cty.cty_id=bkg_from_city_id $regionQry";

			$scqRegion_bkg	 = " AND (bkg.bkg_id IS NULL OR cty1.cty_state_id IN ($stateIds)) ";
			$scqRegion_bt	 = " AND (bt.bkg_id IS NULL OR cty2.cty_state_id IN ($stateIds)) ";
		}



		if (count($bkgTypes) > 0)
		{
			$bkgTypeStr			 = implode(",", $bkgTypes);
			$scqBookingType_bkg	 = " AND (bkg.bkg_id IS NULL OR bkg.bkg_booking_type IN ($bkgTypeStr)) ";
			$scqBookingType_bt	 = " AND (bt.bkg_id IS NULL OR bt.bkg_booking_type IN ($bkgTypeStr)) ";
			$bookingType		 = " AND bkg_booking_type IN ($bkgTypeStr) ";
			$bookingType_bq		 = " AND bq.bkg_booking_type IN ($bkgTypeStr) ";
			$bookingType_bt		 = " AND bt.bkg_booking_type IN ($bkgTypeStr) ";
			$bookingType_b		 = " AND b.bkg_booking_type IN ($bkgTypeStr) ";
		}
		if ($csrSearch != '')
		{
			$params['csrsearch'] = $csrSearch;
			$bkgCSRSearch		 = " AND ((btr.bkg_create_user_type=4 AND btr.bkg_create_user_id=:csrsearch) OR (btr.bkg_confirm_user_type=4 AND btr.bkg_confirm_user_id=:csrsearch))";
			$scqCSRSearch		 = "AND scq_assigned_uid=:csrsearch";
		}
		if ($teamLead != '')
		{
			$params["teamLead"] = $teamLead;

			$adminIds = AdminProfiles::getAdmIdByTeamLeader($teamLead);
			if (!$adminIds)
			{
				$adminIds = 0;
			}
			$scqLeadSql	 = " AND (scq.scq_assigned_uid IN ({$adminIds})) ";
			$bkgAdminSql = " AND (bkg_admin_id IN ({$adminIds})) ";
		}
		if ($groupByCSR)
		{
			$groupBySCQ	 = ", scq_assigned_uid";
			$groupByBkg	 = ", bkg_create_user_id";
			$joinSCQ	 = " AND scq.scq_assigned_id=scqUID";
			$joinBkg	 = " AND scq.scq_assigned_id=bkg_create_user_id";
		}

		if ($isAndroid == true || $isIOS == true)
		{
			$isMobile = true;
		}

		if ($isMobile)
		{
			$mobileApp		 = "  AND (btr.bkg_platform = 3) ";
			$tempMobileApp	 = " AND (bkg_platform = 3)";

			if ($isAndroid && $isIOS == false)
			{
				$mobileApp		 .= " AND btr.bkg_user_device NOT LIKE '%iOS%'";
				$tempMobileApp	 .= " AND bkg_user_device NOT LIKE '%iOS%'";
			}

			if ($isIOS && $isAndroid == false)
			{
				$mobileApp		 .= " AND btr.bkg_user_device LIKE '%iOS%'";
				$tempMobileApp	 .= " AND bkg_user_device LIKE '%iOS%'";
			}
		}
		$scqGozoNow = '';
		if ($isGozonow)
		{
			$isGozonowSelected		 = "  AND (bpr.bkg_is_gozonow = 1) ";
			$isGozonowTempSelected	 = "  AND (bkg_is_gozonow = 1) ";
			$scqGozoNow				 = ",27";
		}
		if (is_array($weekDays) && count($weekDays) > 0)
		{
			$weekDaysStr			 = implode(',', $weekDays);
			$weekDayqry				 = "  AND FIND_IN_SET(DAYOFWEEK(ct.dt), '{$weekDaysStr}') ";
			$btWeekDayqry			 = "  AND FIND_IN_SET(DAYOFWEEK(bkg_create_date), '{$weekDaysStr}') ";
			$bkgConfirmWeekDayqry	 = "  AND FIND_IN_SET(DAYOFWEEK(btr.bkg_confirm_datetime), '{$weekDaysStr}') ";
			$bkgCreateWeekDayqry	 = "  AND FIND_IN_SET(DAYOFWEEK(bkg_create_date), '{$weekDaysStr}') ";
			$scqWeekDayqry			 = "  AND FIND_IN_SET(DAYOFWEEK(scq_assigned_date_time), '{$weekDaysStr}') ";
		}

		$sql			 = " 
				WITH recursive Date_Ranges AS (
					SELECT CAST(:fromDate AS DATETIME) as dt
					UNION ALL
					SELECT IF('$groupBy'='hour', dt +  INTERVAL 1 HOUR, dt + INTERVAL 1 DAY)
					FROM Date_Ranges
					WHERE dt < :toDate AND dt<NOW())
				SELECT DATE_FORMAT(ct.dt, :format) as date, ROUND(quoteMedian) as quoteMedian, 
					IFNULL(totalCSR,0) AS totalCSR, IFNULL(cnt,0) as cnt, IFNULL(quoteCount,0) AS quoteCount,
					IFNULL(leadCount1,0) AS leadCount1, IFNULL(leadCount2,0) AS leadCount2,
					IFNULL(leadCount3,0) AS leadCount3, IFNULL(callbackCount,0) AS callbackCount,
					IFNULL(FollowupsPoints,0) AS FollowupsPoints,
					IFNULL(cntQuoteCreated, 0) AS quoteCreated, cntSelfUniqueQuoteCreated, 
					IFNULL(cntBkg,0) as bookingConfirmed, ROUND(leadMedian) AS leadMedian,
					IFNULL(cntBkgSelf,0) as bookingConfirmedSelf, IFNULL(cntBkgAdmin,0) as bookingConfirmedAdmin,
					IFNULL(cntBkgCreated,0) as bookingCreated, IFNULL(cntBkgCreatedSelf,0) as bookingCreatedSelf,
					IFNULL(cntBkgCreatedAdmin,0) as bookingCreatedAdmin, ROUND(callbackMedian) as callbackMedian, 
					IFNULL(LeadPoints,0) AS LeadPoints, IFNULL(cntLeadsCreated,0) AS cntLeadsCreated
				FROM Date_Ranges ct
				LEFT JOIN (
					SELECT DATE_FORMAT(scq_assigned_date_time, :format) as scqDate, 
						COUNT(DISTINCT scq_id) as cnt, COUNT(DISTINCT scq_assigned_uid) as totalCSR,
						SUM(IF(scq_follow_up_queue_type IN (16,20) AND scq_priority_score>105, 1, 0)) as leadCount1,  
						SUM(IF(scq_follow_up_queue_type IN (16,20) AND scq_priority_score BETWEEN 81 AND 105, 1, 0)) as leadCount2,  
						SUM(IF(scq_follow_up_queue_type IN (16,20) AND scq_priority_score<80, 1, 0)) as leadCount3,  SUM(IF(scq_follow_up_queue_type IN (1), 1, 0)) as callbackCount,
						ROUND(SUM(CASE
							WHEN scq_follow_up_queue_type IN (1) THEN 1.5
							WHEN scq_follow_up_queue_type IN (17,21) AND scq_priority_score>=120 THEN 2.25
							WHEN scq_follow_up_queue_type IN (17,21) AND scq_priority_score BETWEEN 100 AND 120 THEN 1.75
							WHEN scq_follow_up_queue_type IN (17,21) AND scq_priority_score<=100 THEN 1.25
							WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score>105 THEN 1
							WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score BETWEEN 80 AND 100 THEN 0.75
							WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score BETWEEN 60 AND 80 THEN 0.5
							WHEN scq_follow_up_queue_type IN (16,20) AND scq_priority_score<60 THEN 0.25
							ELSE 1
							END
						)) AS FollowupsPoints
					FROM service_call_queue scq 
					LEFT JOIN booking_temp bt ON bt.bkg_id=scq.scq_related_lead_id AND scq_follow_up_queue_type IN (16,20)
					$cityBtJoin
					LEFT JOIN booking bkg ON bkg.bkg_id=scq.scq_related_bkg_id AND scq_follow_up_queue_type IN (17,21)
					$cityBkgJoin
					WHERE scq.scq_follow_up_queue_type IN (1,16,17,20,21$scqGozoNow) 
						AND scq_active=1 
						$scqCSRSearch $scqLeadSql $scqWeekDayqry $scqBookingType_bkg $scqBookingType_bt $scqRegion_bkg $scqRegion_bt
						AND scq_assigned_date_time BETWEEN :fromDate AND :toDate 
						$restrictSCQQry
					GROUP BY scqDate
				) scq ON DATE_FORMAT(ct.dt, :format)=scq.scqDate
				LEFT JOIN (
					SELECT DATE_FORMAT(scq_assigned_date_time, :format) as scqDate, 
						COUNT(DISTINCT bq.bkg_id) as quoteCount
					FROM service_call_queue scq 
					INNER JOIN booking bq on bkg_id=scq_related_bkg_id AND scq_follow_up_queue_type IN (17,21)
					$cityJoin
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id=bq.bkg_id
					INNER JOIN booking_trail btr ON btr.btr_bkg_id=bq.bkg_id
					WHERE scq.scq_follow_up_queue_type IN (17,21) 
						AND scq_active=1 $scqWeekDayqry $mobileApp $isGozonowSelected $scqCSRSearch $bookingType_bq
						AND scq_assigned_date_time BETWEEN :fromDate AND :toDate 
						$restrictSCQQry
					GROUP BY scqDate
				) scqQuote ON DATE_FORMAT(ct.dt, :format)=scqQuote.scqDate
				LEFT JOIN (SELECT DISTINCT DATE_FORMAT(scq_assigned_date_time, :format) as sdate,
								PERCENTILE_CONT(0.5) WITHIN GROUP ( ORDER BY TIMESTAMPDIFF(MINUTE, scq.scq_create_date, scq.scq_assigned_date_time)) OVER (PARTITION BY DATE_FORMAT(scq_assigned_date_time, :format)) as callbackMedian
							FROM service_call_queue scq
							WHERE scq.scq_assigned_date_time BETWEEN :fromDate AND :toDate AND scq.scq_follow_up_queue_type=1 AND scq_active=1 
								$scqCSRSearch $scqWeekDayqry
								$restrictSCQQry
						) b ON DATE_FORMAT(ct.dt, :format)=sdate
				LEFT JOIN (
							SELECT DISTINCT DATE_FORMAT(scq_assigned_date_time, :format) as qdate,
									MEDIAN(TIMESTAMPDIFF(MINUTE, bkg_create_date, scq.scq_assigned_date_time)) OVER (PARTITION BY DATE_FORMAT(scq_assigned_date_time, :format)) as quoteMedian
							FROM service_call_queue scq
							INNER JOIN booking b ON scq.scq_related_bkg_id=b.bkg_id AND bkg_create_date>=DATE_SUB(scq_create_date, INTERVAL 16 HOUR)
							$cityJoin
							WHERE scq.scq_assigned_date_time BETWEEN :fromDate AND :toDate AND scq.scq_follow_up_queue_type IN (17,21$scqGozoNow) 
								AND scq_active=1 $scqCSRSearch $scqLeadSql $scqWeekDayqry $bookingType_b
								$restrictSCQQry
						) c ON DATE_FORMAT(ct.dt, :format)=qdate
				LEFT JOIN (
							SELECT DISTINCT DATE_FORMAT(scq_assigned_date_time, :format) as ldate,
									MEDIAN(TIMESTAMPDIFF(MINUTE, bkg_create_date, scq.scq_assigned_date_time)) OVER (PARTITION BY DATE_FORMAT(scq_assigned_date_time, :format)) as leadMedian
							FROM service_call_queue scq
							INNER JOIN booking_temp bt ON scq.scq_related_lead_id=bt.bkg_id AND scq.scq_follow_up_queue_type IN (16,20$scqGozoNow) 
									AND bkg_create_date>=DATE_SUB(scq_create_date, INTERVAL 16 HOUR)
							$cityJoin
							WHERE scq.scq_assigned_date_time BETWEEN :fromDate AND :toDate AND scq_active=1 
									$scqCSRSearch $scqLeadSql $scqWeekDayqry $bookingType_bt
									$restrictSCQQry
						) d ON DATE_FORMAT(ct.dt, :format)=ldate
				LEFT JOIN (
							SELECT DATE_FORMAT(bkg_confirm_datetime, :format) as cdate, COUNT(bkg_id) as cntBkg,
								COUNT(IF(btr.bkg_create_user_type=1, bkg_id, NULL)) as cntBkgSelf, COUNT(IF(btr.bkg_create_user_type<>1, bkg_id, NULL)) as cntBkgAdmin 
							FROM booking
							INNER JOIN booking_trail btr ON btr.btr_bkg_id=bkg_id 
							INNER JOIN booking_pref bpr ON bkg_id=bpr.bpr_bkg_id
							INNER JOIN booking_invoice biv ON biv.biv_bkg_id=bkg_id
							$cityJoin
							WHERE (bkg_status IN (2,3,5,6,7) OR (bkg_status=9 AND bkg_advance_amount>0))
								AND bkg_agent_id IS NULL AND bkg_confirm_datetime BETWEEN :fromDate AND :toDate  AND bkg_reconfirm_flag=1
								$bkgCSRSearch $bkgAdminSql $mobileApp $isGozonowSelected $bkgConfirmWeekDayqry $bookingType
								$restrictBkgConfirmQry
							GROUP BY cdate
						) e ON DATE_FORMAT(ct.dt, :format)=cdate
				LEFT JOIN (
							SELECT DATE_FORMAT(bkg_create_date, :format) as qtdate, COUNT(IF(btr.bkg_create_user_type=4, bkg_id, NULL)) as cntQuoteCreated,
								COUNT(DISTINCT IF(btr.bkg_create_user_type=1, bkg_user_id, NULL)) as cntSelfUniqueQuoteCreated,
								COUNT(IF((bkg_status IN (2,3,5,6,7) OR (bkg_status=9 AND bkg_advance_amount>0)) AND bkg_reconfirm_flag=1, bkg_id, NULL)) as cntBkgCreated, 
								COUNT(IF((bkg_status IN (2,3,5,6,7) OR (bkg_status=9 AND bkg_advance_amount>0)) AND bkg_reconfirm_flag=1 AND btr.bkg_create_user_type=1, bkg_id, NULL)) as cntBkgCreatedSelf,
								COUNT(IF((bkg_status IN (2,3,5,6,7) OR (bkg_status=9 AND bkg_advance_amount>0)) AND bkg_reconfirm_flag=1 AND btr.bkg_create_user_type=4, bkg_id, NULL)) as cntBkgCreatedAdmin 
							FROM booking
							INNER JOIN booking_trail btr ON btr.btr_bkg_id=bkg_id 
							INNER JOIN booking_pref bpr ON bkg_id=bpr.bpr_bkg_id
							INNER JOIN booking_user bui ON bui_bkg_id=bpr.bpr_bkg_id
							INNER JOIN booking_invoice biv ON biv.biv_bkg_id=bkg_id
							$cityJoin
								AND (bkg_status IN (1,15,10,2,3,5,6,7,9))
								AND bkg_agent_id IS NULL AND bkg_create_date BETWEEN :fromDate AND :toDate
								$bkgCSRSearch $mobileApp $isGozonowSelected $bkgAdminSql $bkgCreateWeekDayqry $bookingType
								$restrictBkgQry 
							GROUP BY qtdate
				) g ON DATE_FORMAT(ct.dt, :format)=qtdate
				LEFT JOIN (
					SELECT DATE_FORMAT(bkg_create_date, :format) as hdate, 
						COUNT(DISTINCT IFNULL(bkg_user_id, booking_temp.bkg_contact_no)) as cntLeadsCreated,
						ROUND(SUM(CASE
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 180 AND 400 THEN 3
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 90 AND 180 THEN 2
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 400 AND 600 THEN 3
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 600 AND 2880 THEN 2.75
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 2.5
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 2
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 1.75
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 11520 AND 28800 THEN 1.25
							WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, bkg_pickup_date) BETWEEN 28800 AND 43200 THEN 1
							ELSE 1 END
						)) AS LeadPoints
					FROM booking_temp 
					$cityJoin
					WHERE bkg_agent_id IS NULL AND bkg_create_date BETWEEN :fromDate AND :toDate $restrictBkgQry
						AND bkg_follow_up_status NOT IN (7,14) $tempMobileApp $isGozonowTempSelected $btWeekDayqry $bookingType
					GROUP BY hdate
				) h ON DATE_FORMAT(ct.dt, :format)=hdate
				WHERE ct.dt BETWEEN :fromDate AND :toDate $weekDayqry
				GROUP BY date";
		//var_dump($params); echo $sql; exit;
		$command		 = DBUtil::command($sql, DBUtil::SDB());
		$command->params = $params;
		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
		$dataProvider	 = new CSqlDataProvider($command, [
			"params"		 => $params,
			"totalItemCount" => $count,
			"params"		 => $command->params,
			'db'			 => DBUtil ::SDB(),
			'pagination'	 => array('pageSize' => 50),
			'sort'			 => ['attributes'	 => ['date', 'cnt', 'bookingConfirmed', 'LeadPoints', 'cntLeadsCreated', 'FollowupsPoints', 'bookingCreated', 'quoteCreated', 'totalCSR'],
				'defaultOrder'	 => 'date DESC'
			],
		]);
		return $dataProvider;
	}

	/**
	 * get last min lead Count
	 * @return type int
	 */
	public static function getLastMinLeadCount()
	{
		$sql = "SELECT  COUNT(1) as cnt 
				FROM `service_call_queue`
				WHERE 1 
				AND scq_active=1
				AND	scq_assigned_uid IS NULL 
				AND scq_status=1
				AND ( scq_to_be_followed_up_by_type = 1 OR scq_to_be_followed_up_by_type IS NULL)
				AND scq_follow_up_queue_type IN (34)
				AND scq_ref_type IN (1,2) 
				AND scq_follow_up_date_time <= NOW()";
		return DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	/**
	 * This function is used for fetching all last min leads for csr
	 * @param type $isEligibleForNewLead
	 * @param type $elgibileScore
	 * @return type NONE
	 */
	public static function updateLastMinPendingLeadsCron()
	{
		$limit	 = 0;
		$flag	 = 0;
		$model	 = new ServiceCallQueue();
		while (true)
		{
			$rows = BookingTemp::getLastMinPendingLeadsCron($limit);
			foreach ($rows as $row)
			{
				$row['desc'] = "Last mintue booking.";
				if ($row['type'] == 2)
				{
					$isRelatedQuoteCnt = ServiceCallQueue::isRelatedQuoteExist($row['bkg_id']);
					if ($isRelatedQuoteCnt == 0)
					{
						$model = self::updateLead($row, 1);
						if ($model->scq_id > 0)
						{
							self::addAdditionalParams($model);
						}
					}
				}
				else if ($row['type'] == 1)
				{
					$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($row['bkg_id']);
					if ($rowLeadStatus == false || $rowLeadStatus['cnt'] == 0)
					{
						$model = self::updateLead($row, 1);
						if ($model->scq_id > 0)
						{
							self::addAdditionalParams($model);
						}
					}
					elseif ($rowLeadStatus['isQueuedUp'] == 0)
					{

						BookingTemp::model()->inactivateDuplicateLeadById($row['bkg_id']);
					}
				}
				if (ServiceCallQueue::getLastMinLeadCount() > (int) Config::get('SCQ.maxLeadAllowed'))
				{
					$flag = 1;
					break;
				}
			}
			$limit++;
			if ($flag == 1 || $limit > 2)
			{
				break;
			}
		}
	}

	/**
	 * This function is used for creating  auto FUR for booking cancellation due to price issue
	 * @param type $row array
	 * @return $returnSet
	 */
	public static function autoFURForBookingCancellation($row)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($row['bkg_user_id'], UserInfo::TYPE_CONSUMER);
			$code										 = $row['bkg_country_code'];
			$number										 = $row['bkg_contact_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $row['bkg_contact_id'];
			$model->scq_to_be_followed_up_with_entity_id = $row['bkg_user_id'];
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_PRICE_HIGH;
			$model->scq_related_bkg_id					 = $row['bkg_id'];
			$model->scq_additional_param				 = json_encode($row);
			$model->scq_creation_comments				 = $row['bkg_cancel_delete_reason'];
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_SYSTEM);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function autoFurForFreezeVendor($row)
	{
		$model											 = new ServiceCallQueue();
		$contactId										 = ContactProfile::getByEntityId($row['vnd_id'], UserInfo::TYPE_VENDOR);
		$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
		$code											 = $arrPhoneByPriority['phn_phone_country_code'];
		$number											 = $arrPhoneByPriority['phn_phone_no'];
		Filter::parsePhoneNumber($number, $code, $phone);
		$model->followupPerson							 = 1;
		$model->scq_created_by_type						 = UserInfo::TYPE_SYSTEM;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = 9;
		$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_VENDOR;
		$model->scq_to_be_followed_up_with_entity_id	 = $row['vnd_id'];
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 3;
		$model->scq_creation_comments					 = "Vendor has been blocked due to payment issue.Vendor need to pay:" . $row['totTrans'];
		$model->scq_to_be_followed_up_with_value		 = $code . $phone;
		$model->scq_to_be_followed_up_with_type			 = 2;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 2;
		$model->scq_to_be_followed_up_with_contact		 = $contactId;
		$model->scq_additional_param					 = json_encode(array('isBlockedForPayment' => 1, 'payableAmt' => $row['totTrans'], 'vnd_id' => $row['vnd_id'], 'lastTripCompletedDate' => $row['last_trip_completed_date']));
		$entityType										 = UserInfo::TYPE_SYSTEM;
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type
	 */
	public static function getDispatchCsrByBookingId($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT scq_assigned_uid ,if(scq_status IN (1),1,0) AS type
					FROM `service_call_queue` WHERE `scq_follow_up_queue_type` IN (33,19,32) AND `scq_status` IN(1,3) 
					AND `scq_active` = 1 AND `scq_related_bkg_id` =:bkgId AND scq_assigned_uid IS NOT NULL 
					ORDER BY type DESC, scq_id DESC LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * 
	 * @param type $data
	 * @param type $csr
	 * @return type
	 */
	public static function createSelfReassignment($data, $csr, $allocationType = 0)
	{
		try
		{
			$allocationMsg	 = $allocationType == 0 ? " Auto " : " Manually ";
			$transaction	 = DBUtil::beginTransaction();
			$returnSet		 = self::addBARQueue($data, $csr, $allocationType);
			if ($returnSet->getStatus())
			{
				$row['scq_id']	 = $returnSet->getData()['followupId'];
				$isBarFlag		 = 1;
				$adminid		 = $csr;
				$bkid			 = $data['bkg_id'];
			}
			$teamId					 = 0;
			$isEligibleForNewLead	 = true;
			$count					 = self::getAssignmentCount($csr, $teamId, $isEligibleForNewLead);
			$returnCount			 = self::assign($row['scq_id'], $csr, $count);
			if ($returnCount > 0)
			{
				self::assignTeam($row['scq_id'], $teamId);
			}
			if ($returnCount && $isBarFlag == 1)
			{
				$bookingmodel								 = Booking::model()->findByPk($bkid);
				$bookingmodel->bkgPref->bpr_assignment_level = 1;
				$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;
				if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
				{
					$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
				}
				$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
				$bookingmodel->bkgPref->save();
				if ($bookingmodel->bkgPref->save())
				{
					$admin	 = Admins::model()->findByPk($adminid);
					$aname	 = $admin->adm_fname;
					$desc	 = "CSR (" . $aname . ") Reallocated By " . $aname . " ($allocationMsg) ";
					BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog :: CSR_REALLOCATE, false, false);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
			return false;
		}
		return $row['scq_id'];
	}

	/**
	 * This function is used for fetching  assign call back for particular  csr
	 * @param type $csr
	 * @return type  array
	 */
	public static function fetchLeads($csr)
	{
		$params	 = ['csr' => $csr];
		$sql	 = "SELECT scq_id,scq_disposition_comments  FROM service_call_queue WHERE 1 AND scq_status=3 AND  scq_assigned_uid =:csr AND scq_active=1";
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used for creating  auto FUR  for gozo now
	 * @param string $bcbId
	 * @return $returnSet
	 */
	public static function autoFURManualAssignment($bkgId, $vendorId, $desc)
	{
		$model											 = new ServiceCallQueue();
		$model->followupPerson							 = 1;
		$model->scq_created_by_type						 = UserInfo::TYPE_ADMIN;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = 18;
		$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_VENDOR;
		$model->scq_to_be_followed_up_with_entity_id	 = $vendorId;
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 5;
		$model->scq_creation_comments					 = $desc;
		$model->scq_to_be_followed_up_with_type			 = 0;
		$model->scq_related_bkg_id						 = $bkgId;
		$model->scq_to_be_followed_up_with_value		 = 0;
		$model->scq_to_be_followed_up_with_contact		 = 0;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 3;
		$model->scq_to_be_followed_up_with_contact		 = 0;
		$entityType										 = UserInfo::TYPE_ADMIN;
		$model->scq_additional_param					 = json_encode(array('manualAssignmentAsk' => 1));
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		return $returnSet;
	}

	/**
	 * Fetching scq  details
	 * @param string $queueType
	 * @param string $bkgId
	 * @param string $csrId
	 * @return type int
	 */
	public static function getDetailsByQueueBkgCsr($queueIds, $bkgId, $csrId, $check = 0, $status = "1,3")
	{
		if ($check == 1)
		{
			$cond = "";
		}
		else
		{
			$cond = " OR  scq_assigned_uid IS NOT NULL";
		}
		$queueIds		 = (string) $queueIds;
		DBUtil::getINStatement($queueIds, $bindString, $params);
		$params['bkgId'] = $bkgId;
		$params['csrId'] = $csrId;
		$sql			 = "SELECT  scq_id FROM `service_call_queue` WHERE 1 AND scq_active=1 AND scq_follow_up_queue_type IN ($bindString) AND scq_related_bkg_id=:bkgId AND (scq_assigned_uid=:csrId $cond) AND scq_status IN($status)";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * Fetching scq  details for manual assignment ask
	 * @return type query object
	 */
	public static function autoCloseManaualAssignment()
	{
		$sql = "SELECT 
				scq_id,
				bkg_id
				FROM booking
				INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id 
				INNER JOIN service_call_queue ON service_call_queue.scq_related_bkg_id=booking.bkg_id
				WHERE 1 
				AND scq_status IN (1,3)
				AND scq_active=1
				AND service_call_queue.scq_to_be_followed_up_by_type=1
				AND service_call_queue.scq_to_be_followed_up_by_id =18
				AND scq_follow_up_queue_type=9
				AND booking.bkg_status<>2";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public function getDispatchPerformance()
	{
		$fromDate	 = $toDate		 = "";
		$where		 = "";
		$where2		 = "";
		$sqlJoin	 = "";

		if ($this->from_date != '' && $this->to_date != '')
		{
			$fromDate	 = $this->from_date . ' 00:00:00';
			$toDate		 = $this->to_date . ' 23:59:59';
		}
		if ($this->bkg_pickup_date1 != '' && $this->bkg_pickup_date2 != '')
		{
			$pickupDate1 = $this->bkg_pickup_date1 . ' 00:00:00';
			$pickupDate2 = $this->bkg_pickup_date2 . ' 23:59:59';
		}
		if ($this->adminId > 0)
		{
			$where .= " AND (atl.adm_id=$this->adminId OR admins.adm_id=$this->adminId) ";
		}
		if ($pickupDate1 != '' && $pickupDate2 != '')
		{
			$where2 .= " AND (bkg_pickup_date BETWEEN '{$pickupDate1}' AND '{$pickupDate2}') ";
		}

		if ($this->region != '')
		{
			$sqlJoin	 .= " INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id AND c1.cty_active=1 
						INNER JOIN states s1 ON s1.stt_id=c1.cty_state_id AND s1.stt_active = '1' ";
			$strRegion	 = implode(',', $this->region);
			$where2		 .= " AND s1.stt_zone IN ($strRegion) ";
		}

		if ($this->assignMode != '')
		{
			$strAssignMode	 = implode(',', $this->assignMode);
			$where2			 .= " AND bcb_assign_mode IN ($strAssignMode) ";
		}

		$whereCritical = [];

		if ($this->isManual > 0)
		{
			$whereCritical[] = "(bpr.bkg_manual_assignment=$this->isManual AND bpr.bkg_critical_assignment=0)";
		}

		if ($this->isCritical > 0)
		{
			$whereCritical[] = "bpr.bkg_critical_assignment=$this->isCritical";
		}

		if (count($whereCritical) > 0)
		{
			$where2 .= " AND (" . implode(" OR ", $whereCritical) . ")";
		}

		$params				 = [];
		$params['fromDate']	 = $fromDate;
		$params['toDate']	 = $toDate;

		$sql			 = "SELECT CONCAT(admins.adm_fname, ' ', admins.adm_lname, ' (',  admins.adm_user, ')') as CSR, CONCAT(atl.adm_fname, ' ', atl.adm_lname, ' (',  atl.adm_user, ')') as TeamLeader,
					a.*, 
					a2.AllocatedUserID,
					IFNULL(a2.TotalAllocated,0) as TotalAllocated,
					IFNULL(a2.ManualAssign,0) as ManualAssign,
					IFNULL(a2.AutoAssign,0) as AutoAssign,
					a2.AllocatedAssigned,
					a2.AllocatedGozoCancelled
				FROM admins
				INNER JOIN admin_profiles adp ON adp.adp_adm_id=admins.adm_id 
				LEFT JOIN admins atl ON adp.adp_team_leader_id=atl.adm_id 
				LEFT JOIN (

				   SELECT  a1.adm_id as AssignedUserID,
						COUNT(DISTINCT bkg_id) as TotalAssigned,
						GROUP_CONCAT(DISTINCT bkg_id) as TotalBkgIds,
						COUNT(DISTINCT IF(bkg_manual_assignment=0, bkg_id, null)) AS nonManualAssigned,
						IFNULL(ROUND(SUM(IF(bkg_manual_assignment=0, biv.bkg_gozo_amount-biv.bkg_credits_used, 0))*100/SUM(IF(bkg_manual_assignment=0, biv.bkg_net_base_amount, 0))),0) AS nonManualAssignedMargin,
						COUNT(DISTINCT IF(bkg_gozo_amount-bkg_credits_used>0, bkg_id, null)) AS profitAssigned,
						COUNT(DISTINCT IF(bkg_gozo_amount-bkg_credits_used<0, bkg_id, null)) AS lossAssigned,
						IFNULL(ROUND(COUNT(DISTINCT IF(bkg_gozo_amount-bkg_credits_used<0, bkg_id, null))*100/COUNT(DISTINCT bkg_id)),0) AS lossPercent,
						IFNULL(ROUND(SUM(IF(bkg_gozo_amount-bkg_credits_used<0 AND bkg_status<>9, biv.bkg_gozo_amount-biv.bkg_credits_used, 0))*100/SUM(IF(bkg_gozo_amount-bkg_credits_used<0 AND bkg_status<>9, biv.bkg_net_base_amount, 0))),0) AS lossMargin,
						SUM(IF(bkg_gozo_amount-bkg_credits_used<0 AND bkg_status<>9, biv.bkg_gozo_amount-biv.bkg_credits_used, 0)) AS lossGozoAmount,
						SUM(IF(bkg_status<>9,biv.bkg_gozo_amount-biv.bkg_credits_used,0)) as gozoAmount,
						ROUND(SUM(IF(bkg_status<>9, biv.bkg_gozo_amount-biv.bkg_credits_used,0))*100 / SUM(IF(bkg_status<>9, biv.bkg_net_base_amount,0)), 1) as netMargin,
						COUNT(DISTINCT IF(bkg_status=9 AND bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38), bkg_id, NULL)) as gozoCancelled
					FROM booking
					INNER JOIN booking_invoice biv ON bkg_id=biv_bkg_id
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id=bkg_id
					INNER JOIN booking_vendor_request bvr ON bvr.bvr_booking_id=bkg_id AND bvr.bvr_assigned_at IS NOT NULL
					INNER JOIN booking_cab ON bcb_id=bvr_bcb_id AND bcb_vendor_id=bvr_vendor_id AND bkg_bcb_id=bcb_id
					INNER JOIN admins a1 ON bcb_assigned_csr=a1.adm_id $sqlJoin
					WHERE bkg_status IN (3,5,6,7,9)
						AND bvr_assigned_at BETWEEN :fromDate AND :toDate AND bkg_pickup_date >= :fromDate $where2
					GROUP BY `a1`.`adm_id`
				) a ON admins.adm_id=AssignedUserID
				LEFT JOIN (
					SELECT  scq_assigned_uid AS AllocatedUserID,
						IFNULL(COUNT(DISTINCT bkg_id),0) as TotalAllocated,
						COUNT(DISTINCT IF(JSON_VALUE(scq.scq_additional_param, '$.allocationType' )=0 AND scq.scq_additional_param IS NOT NULL, bkg_id,null)) as 'AutoAssign',
						COUNT(DISTINCT IF(JSON_VALUE(scq.scq_additional_param, '$.allocationType' )=1 AND scq.scq_additional_param IS NOT NULL , bkg_id,null)) as 'ManualAssign',
						IFNULL(COUNT(DISTINCT IF(scq.scq_assigned_uid=bcb_assigned_csr, bkg_id, null)),0) AS AllocatedAssigned,
						IFNULL(COUNT(DISTINCT IF(bkg_status=9 AND bcb_assigned_csr IS NULL AND bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38), bkg_id, NULL)),0) as AllocatedGozoCancelled
					FROM booking
					INNER JOIN booking_invoice ON bkg_id=biv_bkg_id
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id=bkg_id
					INNER JOIN booking_cab ON bcb_id=bkg_bcb_id 
					INNER JOIN `service_call_queue` scq ON scq_related_bkg_id=bkg_id AND  scq_follow_up_queue_type IN (19,33,32) AND scq.scq_assigned_uid<>10 $sqlJoin
					WHERE  scq_assigned_date_time BETWEEN :fromDate AND :toDate $where2
					GROUP BY scq_assigned_uid
				) a2 ON admins.adm_id=AllocatedUserID

				WHERE (AssignedUserID IS NOT NULL OR AllocatedUserID IS NOT NULL) $where";
		$command		 = DBUtil::command($sql, DBUtil::SDB3());
		$command->params = $params;
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$command->getText()} ) temp", DBUtil::SDB(), $command->params);
		$dataProvider	 = new CSqlDataProvider($command, [
			"params"		 => $params,
			"totalItemCount" => $count,
			"params"		 => $command->params,
			'db'			 => DBUtil ::SDB3(),
			'pagination'	 => array('pageSize' => 100),
			'sort'			 => [
				'attributes'	 => ['CSR', 'TotalAssigned', 'nonManualAssigned', 'nonManualAssignedMargin', 'profitAssigned', 'TeamLeader',
					'lossAssigned', 'lossPercent', 'lossMargin', 'lossGozoAmount', 'gozoAmount', 'netMargin', 'gozoCancelled',
					'UnallocatedAssigned' => '(TotalAssigned-IFNULL(AllocatedAssigned,0))', 'TotalAllocated', 'AllocatedAssigned', 'AllocatedGozoCancelled'
				],
				'defaultOrder'	 => 'TotalAssigned DESC'
			],
		]);

		return $dataProvider;
	}

	public static function getScqIdByBookingId($bkgId, $csrId)
	{
		$params	 = ['bkgId' => $bkgId, 'csrId' => $csrId];
		$sql	 = "SELECT scq_id,scq_created_by_uid 
					FROM `service_call_queue` WHERE `scq_status` IN(1,3) 
					AND `scq_active` = 1 AND `scq_related_bkg_id` =:bkgId AND scq_assigned_uid IS NOT NULL
					AND scq_assigned_uid = :csrId
					ORDER BY scq_id DESC LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	public static function addDispatchAllocateCsr($adminid, $bookingmodel, $adminTeamId, $allocationType = 0)
	{
		$data		 = ['bkg_id' => $bookingmodel->bkg_id, 'bkg_booking_type' => $bookingmodel->bkg_booking_type, 'bpr_assignment_id' => 0, 'type' => 1];
		$returnSet	 = ServiceCallQueue::addBARQueue($data, $adminid, $allocationType);
		$scqId		 = $returnSet->getData()['followupId'];
		if (in_array($adminTeamId, [48, 4]))
		{
			$count		 = self::getAssignmentCount($adminid, $adminTeamId, false);
			$returnCount = self::assign($scqId, $adminid, $count, 1);
			if ($returnCount > 0)
			{
				self::assignTeam($scqId, $adminTeamId);
			}
		}
		else
		{
			$count		 = self::getAssignmentCount($adminid, $adminTeamId, false);
			$returnCount = self::assign($scqId, $adminid, $count, 0);
			if ($returnCount > 0)
			{
				self::assignTeam($scqId, $adminTeamId);
			}
		}


		return $returnCount;
	}

	/**
	 * This function is used for checking dispatch call queue
	 * @param type bookingId
	 * @return integer
	 */
	public static function checkExistDispatchCsr($bkgId)
	{
		$params	 = ['bookingId' => $bkgId];
		$sql	 = "SELECT   scq_id
					FROM  service_call_queue
					WHERE scq_related_bkg_id =:bookingId  AND scq_active=1 AND scq_status IN (1,3) AND scq_follow_up_queue_type IN (19,33) AND scq_assigned_uid IS NOT NULL ";
		$res	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $res;
	}

	/**
	 * This function is used getting lead for BAR Queue
	 * @return integer booking Id
	 */
	public static function getCSAdData($region, $serveBookingType = null)
	{
		$where	 = "";
		$params1 = array();
		if ($serveBookingType != null && $serveBookingType != "")
		{
			$bookingType = is_string($serveBookingType) ? $serveBookingType : strval($serveBookingType);
			DBUtil::getINStatement($bookingType, $bindString1, $params1);
			$where		 = " AND booking.bkg_booking_type IN ({$bindString1})";
		}

		$regions = is_string($region) ? $region : strval($region);
		DBUtil::getINStatement($regions, $bindString, $params);

		$sql = "SELECT bkg_id,stt_zone,IF(bpr_assignment_level IN (2,3),1,0) AS levelScore
                    FROM `booking_pref`
                    INNER JOIN booking ON booking.bkg_id = bpr_bkg_id
					INNER JOIN  cities on bkg_from_city_id = cty_id AND cities.cty_active=1
					INNER JOIN  states on cty_state_id = stt_id AND states.stt_active='1'
					LEFT JOIN service_call_queue ON service_call_queue.scq_related_bkg_id=booking.bkg_id AND scq_follow_up_queue_type IN (19,33,32)  AND scq_status IN (1,3)  AND scq_follow_up_date_time <= NOW() AND scq_follow_up_date_time IS NOT NULL
                    WHERE 1
					AND bkg_is_fbg_type=0
					AND bpr_askmanual_assignment=0
					AND scq_id IS NULL
					AND ( bpr_skip_csr_assignment IS NULL OR (bpr_skip_csr_assignment IS NOT NULL AND bpr_skip_csr_assignment<=NOW()))
                    AND bkg_pickup_date BETWEEN NOW() AND CONCAT(DATE_ADD(CURDATE(),INTERVAL 5 DAY), ' 23:59:59')
                    AND bkg_active=1
					AND states.stt_zone IN ({$bindString})
					$where
                    AND booking.bkg_status=2
					AND (bpr_assignment_level IN (2,3) OR bkg_critical_assignment=1 OR bkg_manual_assignment=1)
					GROUP BY bkg_id
                    ORDER BY levelScore DESC,bkg_critical_assignment DESC, bkg_manual_assignment DESC, bkg_critical_score DESC,booking.bkg_pickup_date ASC,bkg_create_date ASC
					LIMIT 0,1";
		return DBUtil::queryRow($sql, DBUtil::MDB(), array_merge($params, $params1));
	}

	/**
	 * This function is used to allocated CSA
	 * @param type $data
	 * @param type $csr
	 * @param type $teamId
	 * @return type int scqId
	 */
	public static function selfAllocatCBR($data, $csr, $teamId, $type = 0, $allocationType = 0)
	{
		try
		{
			$returnSet = self::addCSAQueue($data, $teamId, $allocationType);
			if ($returnSet->getStatus())
			{
				$row['scq_id']	 = $returnSet->getData()['followupId'];
				$isCsaFlag		 = 1;
				$adminid		 = $csr;
				$bkid			 = $data['bkg_id'];
			}
			$isEligibleForNewLead	 = true;
			$count					 = self::getAssignmentCount($csr, $teamId, $isEligibleForNewLead);
			$returnCount			 = self::assign($row['scq_id'], $csr, $count, 1);
			if ($returnCount > 0)
			{
				self::assignTeam($row['scq_id'], $teamId);
			}
			if ($returnCount && $isCsaFlag == 1)
			{
				$bookingmodel								 = Booking::model()->findByPk($bkid);
				$bookingmodel->bkgPref->bpr_assignment_level = $type == 1 ? 3 : 2;
				$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;
				if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
				{
					$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
				}
				$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');
				if ($bookingmodel->bkgPref->save())
				{
					$admin		 = Admins::model()->findByPk($adminid);
					$aname		 = $admin->adm_fname;
					$desc		 = $type == 1 ? "BookingID:  " . $bookingmodel->bkg_booking_id . " Self Assigned By " . $aname : "CSR (" . $aname . ") allocated By " . $aname . " (Auto)";
					;
					$eventCode	 = $type == 1 ? BookingLog::SELF_ASSIGN : BookingLog :: CSR_ALLOCATE;
					BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), $eventCode, false, false);
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			return false;
		}
		return $row['scq_id'];
	}

	/**
	 * fetch admin id who ask for Manual assign approval
	 * @param type $bkgId
	 * @param type $vendorID
	 * @return type int
	 */
	public static function getScqDetailsManualAssignApproval($bkgId, $vendorID)
	{
		$sql = "SELECT scq_created_by_uid FROM  service_call_queue WHERE 1 AND scq_related_bkg_id=:bkgId AND JSON_VALUE(`scq_additional_param`,'$.manualAssignmentAsk')=1	AND scq_status=1 AND scq_to_be_followed_up_with_entity_id=:vendorId ORDER BY scq_id DESC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), ['bkgId' => $bkgId, 'vendorId' => $vendorID]);
	}

	/**
	 * Function for archiving service call queue
	 */
	public function archiveData($archiveDB, $upperLimit = 100000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(scq_id) AS scq_id FROM (SELECT scq_id FROM service_call_queue WHERE 1 AND scq_create_date < CONCAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), ' 23:59:59') AND scq_status IN (0,2) ORDER BY scq_id ASC LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$transaction = DBUtil::beginTransaction();
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql		 = "INSERT INTO " . $archiveDB . ".service_call_queue (SELECT * FROM service_call_queue WHERE scq_id IN ($bindString))";
					$rows		 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						$sql = "DELETE FROM `service_call_queue` WHERE scq_id IN ($bindString)";
						DBUtil::execute($sql, $params);
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
					}
				}

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	/**
	 * This function is used to add no show for customer/driver for the booking
	 * @param type $bkgId
	 * @param type $flag  1=>Driver No show  2=>Customer No Show
	 * @return type  ReturnSet
	 */
	public static function addNoShowCBR($bkgId, $flag)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$bookingmodel									 = Booking::model()->findByPk($bkgId);
			$partnerType									 = $bookingmodel->bkg_agent_id != null ? 1 : 0;
			$contactId										 = ($flag == 1) ? (ContactProfile::getByEntityId($bookingmodel->bkgBcb->bcb_driver_id, UserInfo::TYPE_DRIVER)) : ($partnerType == 1 ? ContactProfile::getByEntityId($bookingmodel->bkgBcb->bcb_vendor_id, UserInfo::TYPE_VENDOR) : -1);
			$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
			$code											 = $flag == 1 ? $arrPhoneByPriority['phn_phone_country_code'] : $bookingmodel->bkgUserInfo->bkg_country_code;
			$number											 = $flag == 1 ? $arrPhoneByPriority['phn_phone_no'] : $bookingmodel->bkgUserInfo->bkg_contact_no;
			$model->contactRequired							 = 0;
			$model->followupPerson							 = 1;
			$model->scq_to_be_followed_up_with_value		 = $code . $number;
			$model->scq_to_be_followed_up_with_type			 = 2;
			$model->scq_to_be_followed_up_with_entity_type	 = $flag == 1 ? UserInfo::TYPE_DRIVER : ($partnerType == 1 ? UserInfo::TYPE_VENDOR : UserInfo::TYPE_CONSUMER);
			$model->scq_to_be_followed_up_with_entity_id	 = $flag == 1 ? $bookingmodel->bkgBcb->bcb_driver_id : ($partnerType == 1 ? $bookingmodel->bkgBcb->bcb_vendor_id : $bookingmodel->bkgUserInfo->bkg_user_id);
			$model->scq_to_be_followed_up_with_entity_rating = -1;
			$model->scq_follow_up_queue_type				 = $flag == 1 ? ServiceCallQueue::TYPE_DRIVER_NOSHOW : ServiceCallQueue::TYPE_CUSTOMER_NOSHOW;
			$model->scq_related_bkg_id						 = $bookingmodel->bkg_id;
			$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
			$model->scq_creation_comments					 = $flag == 1 ? "Driver no show" : "Customer no show";
			$model->scq_follow_up_priority					 = 5;
			$model->isFlag									 = 1;
			$entityType										 = $flag == 1 ? UserInfo::TYPE_DRIVER : ($partnerType == 1 ? UserInfo::TYPE_VENDOR : UserInfo::TYPE_CONSUMER);
			$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function followupForPenalized($cancelCharges, $cancelId, $bkgId)
	{
		$cusPenalizedRule	 = CancelReasons::getCustomerPenalizeRuleById($cancelId);
		$venPenalizedRule	 = CancelReasons::getVendorPenalizeRuleById($cancelId);
		$cancelCharge		 = ($cusPenalizedRule <= 1) ? 0 : $cancelCharges;
		if ($cusPenalizedRule == 3 && $cancelCharge > 0)
		{
			ServiceCallQueue::addFollowupForPenalizedCustomer($bkgId);
		}
		if ($venPenalizedRule == 3 && $cancelCharge > 0)
		{
			ServiceCallQueue::addFollowupForPenalizedVendor($bkgId);
		}
	}

	/**
	 * This function is used to add CBR for MMT Support 
	 * @param type $bkgId
	 * @param type $desc
	 * @return type ReturnSet
	 */
	public static function mmtSupportCBR($bkgId, $desc = "Mmt Support")
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$bookingmodel = Booking::model()->findByPk($bkgId);
			if (!in_array($bookingmodel->bkg_agent_id, array(18190, 450)))
			{
				$returnSet->setMessage(" B2C/Other Channel Partner ");
				$returnSet->setStatus(false);
			}
			else if (ServiceCallQueue::countQueueByBkgId($bkgId, ServiceCallQueue::TYPE_MMT_SUPPORT, 'closed') > 0)
			{
				$returnSet->setMessage(" CBR already exist");
				$returnSet->setStatus(false);
			}
			else
			{
				$contactId										 = ContactProfile::getByEntityId($bookingmodel->bkg_agent_id, UserInfo::TYPE_AGENT);
				$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
				$code											 = $arrPhoneByPriority['phn_phone_country_code'];
				$number											 = $arrPhoneByPriority['phn_phone_no'];
				$model->contactRequired							 = 0;
				$model->followupPerson							 = 1;
				$model->scq_to_be_followed_up_with_value		 = $code . $number;
				$model->scq_to_be_followed_up_with_type			 = 2;
				$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_AGENT;
				$model->scq_to_be_followed_up_with_entity_id	 = $bookingmodel->bkg_agent_id;
				$model->scq_to_be_followed_up_with_entity_rating = -1;
				$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_MMT_SUPPORT;
				$model->scq_related_bkg_id						 = $bookingmodel->bkg_id;
				$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
				$model->scq_creation_comments					 = $desc;
				$model->scq_follow_up_priority					 = 5;
				$model->isFlag									 = 1;
				$entityType										 = UserInfo::TYPE_AGENT;
				$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * Fetching scq  details for auto close dispatch followup
	 * @return type query object
	 */
	public static function autoCloseDispatchFollowUp()
	{
		$sql = "SELECT 
				scq_id
				FROM service_call_queue
				INNER JOIN booking on booking.bkg_id=service_call_queue.scq_related_bkg_id
				WHERE 1
				AND booking.bkg_pickup_date <CURDATE()
				AND scq_follow_up_queue_type IN (6,12,14,18,24)
				AND scq_status=1 
				AND scq_active=1
				AND scq_assigned_uid IS NULL
				AND scq_create_date <CURDATE()";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * Fetching scq  details for auto close dispatch followup after 1 hour
	 * @return type query object
	 */
	public static function getAutoCloseDispatch($queueIds, $mins)
	{
		$queueIds		 = (string) $queueIds;
		DBUtil::getINStatement($queueIds, $bindString, $params);
		$params['mins']	 = $mins;
		$sql			 = "SELECT 
							scq_id
							FROM service_call_queue
							INNER JOIN booking ON booking.bkg_id=service_call_queue.scq_related_bkg_id
							WHERE 1
							AND NOW()>=DATE_ADD(booking.bkg_pickup_date,INTERVAL :mins MINUTE) 
							AND scq_follow_up_queue_type IN ($bindString)
							AND scq_status=1 
							AND scq_active=1
							AND scq_assigned_uid IS NULL";
		return DBUtil::query($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function is used check if team is allowed to Followup Dispatch leads
	 * @param type $teamId
	 * @return int
	 */
	public static function isAllowedFollowupDispatch($teamId)
	{
		$sql = "SELECT COUNT(*) as cnt FROM team_queue_mapping WHERE tqm_tea_id=:teamId AND tqm_queue_id IN (14) AND tqm_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(true), array('teamId' => $teamId));
	}

	/**
	 * This function is used get one row for Followup Dispatch queue
	 * @return row array
	 */
	public static function getFollowupDispatch($limit = 3)
	{
		$sql = "SELECT 
					bkg.bkg_id,
					CASE
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) < 30 THEN 20
					  WHEN TIMESTAMPDIFF(MINUTE,  NOW(),bkg_pickup_date) BETWEEN 30 AND 60 THEN 15
					  WHEN TIMESTAMPDIFF(MINUTE, NOW(),bkg_pickup_date) BETWEEN 60 AND 120 THEN 5					
					  ELSE 0
					END AS timeRank,
					CASE
					  WHEN btk.bkg_arrived_for_pickup = 1 THEN -10
					  WHEN btl.btl_id IS NOT NULL THEN 10
					  WHEN apt.apt_id IS NOT NULL THEN 15
					ELSE 20 
					END AS statusRank,       
					IF(bkg_agent_id  IN (18190,450,30228),5,0) AS partnerRank,
					IF(btk_vendor_pickup_confirm=1 AND apt.apt_id IS NULL, -10, IF(btk_vendor_pickup_confirm=1 AND apt.apt_id IS NOT NULL, -50,0)) AS loginRank 
				FROM booking bkg
				JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id
				JOIN booking_track btk ON  btk.btk_bkg_id = bkg.bkg_id
				LEFT JOIN service_call_queue ON service_call_queue.scq_related_bkg_id =bkg.bkg_id AND service_call_queue.scq_follow_up_queue_type=14 
				AND service_call_queue.scq_create_date BETWEEN CURDATE() AND NOW() AND scq_active=1
				LEFT JOIN booking_track_log btl ON  btl.btl_bkg_id = bkg.bkg_id AND btl.btl_event_type_id = 201
				LEFT JOIN app_tokens apt ON  apt.apt_entity_id = bcb.bcb_driver_id AND apt.apt_status = 1 AND apt.apt_user_type = 5 AND	apt.apt_last_login>DATE_SUB(NOW(), INTERVAL 110 MINUTE)
				WHERE 1
					AND scq_id IS NULL
					AND bkg.bkg_status = 5
					AND (bkg.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND  DATE_ADD(NOW(), INTERVAL 110 MINUTE))
					AND (btk_driver_pickup_confirm=0 AND (btk_vendor_pickup_confirm=0 OR apt.apt_id IS NULL))
				GROUP BY bkg.bkg_id
				ORDER BY(timeRank+statusRank+partnerRank+loginRank) DESC, bkg.bkg_pickup_date ASC LIMIT 0,$limit";
		return DBUtil::query($sql, DBUtil::MDB());
	}

	/**
	 * get Followup Dispatch  details by booking id
	 * @param type $bkgId
	 * @return row array
	 */
	public static function getDispatchDetails($bkgId)
	{
		$sql = 'SELECT * FROM 
				(				
					(
						SELECT 
							null AS "DATE",
							CASE
								WHEN btk_vendor_pickup_confirm =1 THEN "Vendor( Accepted )"
								WHEN btk_vendor_pickup_confirm =2 THEN "Vendor( Denied/Error occured )"
								WHEN btk_driver_pickup_confirm =1  THEN "Driver( Accepted )"
								WHEN btk_driver_pickup_confirm =2 THEN "Driver( Denied/Error occured )"
								ELSE ""
							END AS "Platform"
						FROM booking_track
						WHERE 1 
							AND btk_bkg_id=:bkgId
							AND (btk_vendor_pickup_confirm > 0 || btk_driver_pickup_confirm  > 0)
					)
					UNION 
					(
						SELECT 
						service_call_queue.scq_disposition_date AS "DATE",
						CONCAT("Admin( ",IF(admins.gozen IS NOT NULL,admins.gozen,"System")," )") AS "Platform"
						FROM booking_track
							JOIN service_call_queue ON service_call_queue.scq_related_bkg_id=booking_track.btk_bkg_id
							LEFT JOIN admins ON admins.adm_id=service_call_queue.scq_disposed_by_uid 
						WHERE 1 
							AND service_call_queue.scq_related_bkg_id=:bkgId
							AND scq_status IN (2,3)
							AND service_call_queue.scq_follow_up_queue_type=14
						LIMIT 0,1
					)
				) AS  TEMP WHERE 1 ORDER BY   TEMP.DATE DESC LIMIT 0,1';
		return DBUtil::queryRow($sql, DBUtil::MDB(), ['bkgId' => $bkgId]);
	}

	public static function getFollowupDispatchCount()
	{
		$sql = "SELECT  COUNT(1) as cnt FROM `service_call_queue`
					WHERE 1 
					AND scq_active=1 
					AND scq_assigned_uid IS NULL 
					AND scq_status=1
					AND ( scq_to_be_followed_up_by_type = 1 OR scq_to_be_followed_up_by_type IS NULL)
					AND scq_follow_up_queue_type IN(14)
					AND scq_follow_up_date_time <= NOW()";
		return DBUtil::queryScalar($sql, DBUtil::MDB());
	}

	/**
	 * Fetching scq  details for auto close vendor assign  followup
	 * @return type query object
	 */
	public static function autoCloseVendorAssignedFollowup()
	{
		$sql = "SELECT 
				scq_id
				FROM service_call_queue
				INNER JOIN booking on booking.bkg_id=service_call_queue.scq_related_bkg_id
				WHERE 1
				AND ( (booking.bkg_pickup_date > NOW()) || bkg_status IN (5,6,7,9,10,2))
				AND scq_follow_up_queue_type IN (40)
				AND scq_status=1 
				AND scq_active=1
				AND scq_assigned_uid IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * This function is used for creating  auto FUR for vendor assigned 
	 * @param string $bookingId
	 * @return $returnSet
	 */
	public static function autoFURVendorAssign($bookingId)
	{
		$bookingModel	 = Booking::model()->findByPk($bookingId);
		$vendorId		 = $bookingModel->bkgBcb->bcb_vendor_id;
		$returnSet		 = new ReturnSet();
		$model			 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $vendorId;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VENDOR_ASSIGN;
			$model->scq_related_bkg_id					 = $bookingId;
			$model->scq_creation_comments				 = "Ask the vendor to assign driver/cab details as soon as posible. ";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_VENDOR, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * Fetching scq details for auto close gozo now followup whose pickup date has past
	 * @return type query object
	 */
	public static function getAutoCloseGozoNow()
	{
		$sql = "SELECT 
				scq_id
				FROM service_call_queue
				INNER JOIN booking on booking.bkg_id=service_call_queue.scq_related_bkg_id
				WHERE 1
				AND booking.bkg_pickup_date <=NOW()
				AND scq_follow_up_queue_type IN (27)
				AND scq_status=1 
				AND scq_active=1
				AND scq_assigned_uid IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * Marking lead close based on bkg id 
	 * @return type boolean
	 */
	public static function autoCloseRelatedLeadQuote($bkgId, $queueId = "16,17,20,21,34")
	{
		try
		{
			$where					 = "";
			$model					 = Booking::model()->findByPk($bkgId);
			$refBkgId				 = BookingTemp::model()->getLeadbyRefBookingid($bkgId)->bkg_id;
			$params					 = array();
			$params['contact_no']	 = $model->bkgUserInfo->bkg_country_code != null ? $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no : $model->bkgUserInfo->bkg_contact_no;
			$params['bkgId']		 = $model->bkg_id;
			$params['userId']		 = $model->bkgUserInfo->bkg_user_id;
			if ($refBkgId != null)
			{
				$params['refBkgId']	 = $refBkgId;
				$where				 .= " OR (scq_related_lead_id=:refBkgId) ";
			}

			$sql		 = "SELECT scq_id 
					FROM service_call_queue
					WHERE 	1
					AND scq_assigned_uid IS NULL 
					AND scq_status=1
					AND scq_follow_up_queue_type IN ($queueId) 
					AND scq_active =1
					AND scq_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 2 DAY),' 00:00:00') AND NOW()
					AND
					(
						(scq_to_be_followed_up_with_entity_type=1 AND scq_to_be_followed_up_with_entity_id=:userId)
						OR (scq_to_be_followed_up_with_type=2 AND scq_to_be_followed_up_with_value=:contact_no) 
						OR (scq_related_bkg_id=:bkgId)
						$where
					)";
			$scqDetails	 = DBUtil::query($sql, DBUtil::MDB(), $params);
			foreach ($scqDetails as $row)
			{
				ServiceCallQueue::updateStatus($row['scq_id'], 10, 0, "Booking already confirmed/Payment serviced");
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}
		return true;
	}

	/**
	 * Fetching previous csr allocated 
	 * @return type int
	 */
	public static function getPreferredCsr($phone, $userId)
	{
		$sql = "SELECT * FROM 
				(
					SELECT 	scq_assigned_uid,
					scq_id
					FROM service_call_queue
					WHERE 1
					AND service_call_queue.scq_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 day), ' 00:00:00') AND  NOW()
					AND scq_follow_up_queue_type IN (16,17,20,21,1,34)
					AND scq_status IN (1,2,3)
					AND scq_active=1
					AND scq_assigned_uid IS NOT NULL
					AND scq_to_be_followed_up_with_type=2 
					AND scq_to_be_followed_up_with_value=:phone
						
					UNION 

					SELECT 
					scq_assigned_uid,
					scq_id
					FROM service_call_queue
					WHERE 1
					AND service_call_queue.scq_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 day), ' 00:00:00') AND  NOW()
					AND scq_follow_up_queue_type IN (16,17,20,21,1,34)
					AND scq_status IN (1,2,3)
					AND scq_active=1
					AND scq_assigned_uid IS NOT NULL
					AND scq_to_be_followed_up_with_entity_type=1 
					AND scq_to_be_followed_up_with_entity_id=:userId
				) TEMP WHERE 1 ORDER BY TEMP.scq_id DESC";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['phone' => $phone, 'userId' => $userId]);
	}

	public static function deactivateByFollowUpId($userId, $unique_code)
	{
		$sql = "UPDATE service_call_queue SET scq_status = 0,scq_active=0,scq_disposition_comments = :remarks  WHERE scq_unique_code =:unique_code AND scq_created_by_uid =:userId AND scq_status IN (1,3) ";
		return DBUtil::command($sql)->execute(['userId' => $userId, 'unique_code' => $unique_code, 'remarks' => "Self CBR closed"]);
	}

	/**
	 * Fetching Dispatch Follow Up 
	 * @return Both Query OBject/ Data Provider
	 */
	public static function DispatchFollowUp($fromDate, $toDate, $command = DBUtil::ReturnType_Provider)
	{
		$sql = "SELECT 
				GROUP_CONCAT(DISTINCT TEMP.bkg_id) AS bkg_id,
				TEMP.bkg_pickup_date,
				IF(TEMP.AUTO=1,1,0) AS AUTO,
				IF(TEMP.MANUAL=1,1,0) AS MANUAL,
				TEMP.GOZEN,
				IF(TEMP.MANUAL=1,TEMP.CLOSEDATE,null) AS ClosedDate
				FROM 
				(
					SELECT 
						booking.bkg_id,
						booking.bkg_pickup_date,
						IF(btk_vendor_pickup_confirm  IN (1,2) || btk_driver_pickup_confirm IN (1,2),1,0) AS AUTO,
						0 AS MANUAL,
						'' AS GOZEN,
						'' AS CLOSEDATE
					FROM booking
						INNER JOIN booking_track ON booking_track.btk_bkg_id=booking.bkg_id
					WHERE 1 
						AND bkg_status  IN (3,5,6,7,9) 
						AND booking.bkg_pickup_date BETWEEN :fromDate AND :toDate
					UNION 

					SELECT 
						booking.bkg_id,
						booking.bkg_pickup_date,
						0 AS AUTO,
						1 AS MANUAL,
						admins.gozen AS GOZEN,
						service_call_queue.scq_assigned_date_time AS CLOSEDATE
					FROM booking
						INNER JOIN booking_track ON booking_track.btk_bkg_id=booking.bkg_id
						INNER JOIN service_call_queue ON service_call_queue.scq_related_bkg_id=booking.bkg_id 
						INNER JOIN admins on admins.adm_id=service_call_queue.scq_assigned_uid
					WHERE 1 
						AND scq_follow_up_queue_type=14 
						AND scq_assigned_uid IS NOT NULL 
						AND scq_status IN (2,3) 
						AND scq_active=1
						AND booking.bkg_pickup_date BETWEEN :fromDate AND :toDate
			) AS TEMP WHERE 1  GROUP BY TEMP.bkg_id ORDER BY TEMP.MANUAL DESC";

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$sql} ) temp", DBUtil::SDB(), ['fromDate' => $fromDate, 'toDate' => $toDate]);
			$dataProvider	 = new CSqlDataProvider($sql, [
				"params"		 => ['fromDate' => $fromDate, 'toDate' => $toDate],
				"totalItemCount" => $count,
				'db'			 => DBUtil ::SDB(),
				'pagination'	 => array('pageSize' => 100),
				'sort'			 => [
					'attributes'	 => ['bkg_id', 'bkg_pickup_date', 'AUTO', 'MANUAL', 'GOZEN', 'CLOSEDATE'],
					'defaultOrder'	 => 'bkg_pickup_date DESC'
				],
			]);
			return $dataProvider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil ::SDB(), ['fromDate' => $fromDate, 'toDate' => $toDate]);
		}
	}

	/**
	 * This function is used for creating  auto FUR for booking cancellation  from customer
	 * @param type $row array
	 * @return $returnSet
	 */
	public static function autoFURCustomerBookingCancellation($bkgId, $message)
	{
		$bookingModel	 = Booking::model()->findByPk($bkgId);
		$returnSet		 = new ReturnSet();
		$model			 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($bookingModel->bkgUserInfo->bkg_user_id, UserInfo::TYPE_CONSUMER);
			$code										 = $bookingModel->bkgUserInfo->bkg_country_code;
			$number										 = $bookingModel->bkgUserInfo->bkg_contact_no;
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $bookingModel->bkgUserInfo->bkg_user_id;
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_CUSTOMER_BOOKING_CANCEL;
			$model->scq_related_bkg_id					 = $bookingModel->bkg_id;
			$model->scq_creation_comments				 = $message;
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function serviceCallQueueByTeam($followModel, $type = DBUtil::ReturnType_Provider)
	{
		$fromDate	 = "$followModel->from_date 00:00:00";
		$toDate		 = "$followModel->to_date 23:59:59";
		if (count($followModel->teamList) > 0)
		{
			$teamLead = implode(",", $followModel->teamList);
		}

		$command = self::getServiceCallQueueCommand($fromDate, $toDate, $teamLead);

		$count = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$command->getText()} ) temp", DBUtil::SDB3(), $command->params);
		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($command, array(
				"totalItemCount" => $count,
				"params"		 => $command->params,
				'db'			 => DBUtil ::SDB3(),
				"pagination"	 => array("pageSize" => 50),
				'sort'			 => array('attributes'	 => array('scq_id'),
					'defaultOrder'	 => 'CreateDate DESC')
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($command->getText(), DBUtil::SDB3(), $command->params);
		}
	}

	public static function getServiceCallQueueCommand($fromDate, $toDate, $teamLead)
	{
		if ($fromDate != '')
		{
			$createFromDays		 = " AND scq_create_date>=:fromDate";
			$params[":fromDate"] = $fromDate;
		}


		if ($toDate != '')
		{
			$createToDate		 = " AND scq_create_date<=:toDate";
			$params[":toDate"]	 = $toDate;
		}

		if ($teamLead > 0)
		{
			$scqLeadSql			 = " AND scq_to_be_followed_up_by_id IN (:teamLead)";
			$params[":teamLead"] = $teamLead;
		}

		$sql = "SELECT scq_id AS FollowupId, scq_related_bkg_id AS ItemID, scq_follow_up_date_time AS followUpdDate,
					CASE WHEN scq_follow_up_queue_type = 1 THEN 'New Booking' 
					WHEN scq_follow_up_queue_type = 2 THEN 'Existing Booking' 
					WHEN scq_follow_up_queue_type = 3 THEN 'New Vendor Attachment' 
					WHEN scq_follow_up_queue_type = 4 THEN 'Vendor Support/Line' 
					WHEN scq_follow_up_queue_type = 5 THEN 'Customer Advocacy/Gozo Cares' 
					WHEN scq_follow_up_queue_type = 6 THEN 'Driver Support/Line' 
					WHEN scq_follow_up_queue_type = 7 THEN 'Payment Followup' 
					WHEN scq_follow_up_queue_type = 9 THEN 'Service Requests'
					WHEN scq_follow_up_queue_type = 11 THEN 'Penalty Disputes' 
					WHEN scq_follow_up_queue_type = 10 THEN 'SOS/Emergency' 
					WHEN scq_follow_up_queue_type = 12 THEN 'UpSell(CNG/Value)' 
					WHEN scq_follow_up_queue_type = 13 THEN 'Vendor Advocacy' 
					WHEN scq_follow_up_queue_type = 14 THEN 'Dispatch' 
					WHEN scq_follow_up_queue_type = 15 THEN 'Vendor Approval' 
					WHEN scq_follow_up_queue_type = 16 THEN 'New Lead Booking' 
					WHEN scq_follow_up_queue_type = 17 THEN 'New Quote Booking' 
					WHEN scq_follow_up_queue_type = 18 THEN 'B2B Post Pickup' 
					WHEN scq_follow_up_queue_type = 19 THEN 'Booking At Risk(Bar)' 
					WHEN scq_follow_up_queue_type = 20 THEN 'New Lead Booking(International)' 
					WHEN scq_follow_up_queue_type = 21 THEN 'New Quote Booking(International)' 
					WHEN scq_follow_up_queue_type = 22 THEN 'FBG' 
					WHEN scq_follow_up_queue_type = 23 THEN 'Vendor Payment Request' 
					WHEN scq_follow_up_queue_type = 24 THEN 'Upsell(Value+/Select)' 
					WHEN scq_follow_up_queue_type = 25 THEN 'Booking Complete Review' 
					WHEN scq_follow_up_queue_type = 26 THEN 'Apps Help & Tech support' 
					WHEN scq_follow_up_queue_type = 27 THEN 'Gozo Now' 
					WHEN scq_follow_up_queue_type = 29 THEN 'Auto Lead Followup' 
					WHEN scq_follow_up_queue_type = 30 THEN 'Document Approval' 
					WHEN scq_follow_up_queue_type = 31 THEN 'Vendor Approval Zone Based Inventory' 
					WHEN scq_follow_up_queue_type = 32 THEN 'Critical and stress (risk) assignments(CSA)' 
					WHEN scq_follow_up_queue_type = 33 THEN 'Airport DailyRental'
					WHEN scq_follow_up_queue_type = 46 THEN	'Vendor Due Amount'
					WHEN scq_follow_up_queue_type = 51 THEN	'Booking Reschedule'
					WHEN scq_follow_up_queue_type = 53 THEN	'VIP/VVIP Booking'

				END AS QueueType,
				scq_create_date AS CreateDate,
				CASE WHEN scq_created_by_type = 1 THEN CONCAT(usr_name, ' ', usr_lname) 
					WHEN scq_created_by_type = 2 THEN CONCAT(vnd_name, '/', vnd_code) 
					WHEN scq_created_by_type = 3 THEN CONCAT(drv_name, '/', drv_code) 
					WHEN scq_created_by_type = 4  THEN CONCAT(adm2.gozen, '/', adp2.adp_emp_code)
					WHEN scq_created_by_type = 10 THEN 'System'
				END AS 'Create By',
				scq_creation_comments AS 'Creation Comment',
				CONCAT(adm.gozen, '(', adp.adp_emp_code, ')') AS 'Assigned CSR(Employee ID)',
				scq_assigned_date_time AS 'Assign Date',
				IF(
					scq_assigned_date_time IS NOT NULL,
					TIMESTAMPDIFF(
						MINUTE,
						scq_create_date,
						scq_assigned_date_time
					),
					0
				) AS 'Time to Assign(Mintue)',
				CONCAT(
					scq_disposition_date,
					'(',
					CONCAT(
						adm1.gozen,
						'/',
						adp1.adp_emp_code
					),
					')'
				) AS 'Closed Date (CSR)',
				IF(
					scq_disposition_date IS NOT NULL,
					TIMESTAMPDIFF(
						MINUTE,
						scq_assigned_date_time,
						scq_disposition_date
					),
					0
				) AS 'Time to Close(Mintue)',
				scq_disposition_comments AS 'Disposition Comments'
				FROM service_call_queue
				LEFT JOIN admins adm ON adm.adm_id = scq_assigned_uid
				LEFT JOIN admin_profiles adp ON adm.adm_id = adp.adp_adm_id
				LEFT JOIN admins adm1 ON adm1.adm_id = scq_disposed_by_uid
				LEFT JOIN admin_profiles adp1 ON adm1.adm_id = adp1.adp_adm_id
				LEFT JOIN admins adm2 ON adm2.adm_id = scq_created_by_uid AND scq_created_by_type = 4
				LEFT JOIN admin_profiles adp2 ON adm2.adm_id = adp2.adp_adm_id AND scq_created_by_type = 4
				LEFT JOIN contact_profile cp ON scq_created_by_uid = cp.cr_is_consumer
				LEFT JOIN vendors ON vendors.vnd_id = cp.cr_is_vendor AND scq_created_by_type = 2
				LEFT JOIN users ON users.user_id = cp.cr_is_consumer AND scq_created_by_type IN(2, 3)
				LEFT JOIN drivers ON drivers.drv_id = cp.cr_is_driver AND scq_created_by_type = 3
				WHERE 1 $createFromDays $createToDate $scqLeadSql AND scq_to_be_followed_up_by_type = 1 AND scq_active = 1
				GROUP BY scq_id";

		$command		 = DBUtil::command($sql, DBUtil::SDB3());
		$command->params = $params;
		return $command;
	}

	public function serviceCallQueueByClosedDate($followModel, $type = DBUtil::ReturnType_Provider)
	{
		$fromDate	 = "$followModel->from_date 00:00:00";
		$toDate		 = "$followModel->to_date 23:59:59";
		if (count($followModel->teamList) > 0)
		{
			$teamLead = implode(",", $followModel->teamList);
		}

		$command = self::getServiceCallQueueClosedDateCommand($fromDate, $toDate, $teamLead);

		$count = DBUtil::queryScalar("SELECT COUNT(*) FROM ({$command->getText()} ) temp", DBUtil::SDB3(), $command->params);
		if ($type == DBUtil::ReturnType_Provider)
		{
			$dataprovider = new CSqlDataProvider($command, array(
				"totalItemCount" => $count,
				"params"		 => $command->params,
				'db'			 => DBUtil ::SDB3(),
				"pagination"	 => array("pageSize" => 50),
				'sort'			 => array('attributes'	 => array('scq_id'),
					'defaultOrder'	 => 'CreateDate DESC')
			));
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($command->getText(), DBUtil::SDB3(), $command->params);
		}
	}

	public static function getServiceCallQueueClosedDateCommand($fromDate, $toDate, $teamLead)
	{
		if ($fromDate != '')
		{
			$createFromDays		 = " AND scq_disposition_date>=:fromDate";
			$params[":fromDate"] = $fromDate;
		}


		if ($toDate != '')
		{
			$createToDate		 = " AND scq_disposition_date<=:toDate";
			$params[":toDate"]	 = $toDate;
		}

		if ($teamLead > 0)
		{
			$scqLeadSql			 = " AND scq_disposed_team_id IN(:teamLead)";
			$params[":teamLead"] = $teamLead;
		}

		$sql = "SELECT scq_id AS FollowupId, scq_related_bkg_id AS ItemID, scq_follow_up_date_time AS followUpdDate,
					CASE WHEN scq_follow_up_queue_type = 1 THEN 'New Booking' 
						WHEN scq_follow_up_queue_type = 2 THEN 'Existing Booking' 
						WHEN scq_follow_up_queue_type = 3 THEN 'New Vendor Attachment' 
						WHEN scq_follow_up_queue_type = 4 THEN 'Vendor Support/Line' 
						WHEN scq_follow_up_queue_type = 5 THEN 'Customer Advocacy/Gozo Cares' 
						WHEN scq_follow_up_queue_type = 6 THEN 'Driver Support/Line' 
						WHEN scq_follow_up_queue_type = 7 THEN 'Payment Followup' 
						WHEN scq_follow_up_queue_type = 9 THEN 'Service Requests' 
						WHEN scq_follow_up_queue_type = 11 THEN 'Penalty Disputes' 
						WHEN scq_follow_up_queue_type = 10 THEN 'SOS/Emergency' 
						WHEN scq_follow_up_queue_type = 12 THEN 'UpSell(CNG/Value)' 
						WHEN scq_follow_up_queue_type = 13 THEN 'Vendor Advocacy' 
						WHEN scq_follow_up_queue_type = 14 THEN 'Dispatch' 
						WHEN scq_follow_up_queue_type = 15 THEN 'Vendor Approval' 
						WHEN scq_follow_up_queue_type = 16 THEN 'New Lead Booking' 
						WHEN scq_follow_up_queue_type = 17 THEN 'New Quote Booking' 
						WHEN scq_follow_up_queue_type = 18 THEN 'B2B Post Pickup' 
						WHEN scq_follow_up_queue_type = 19 THEN 'Booking At Risk(Bar)' 
						WHEN scq_follow_up_queue_type = 20 THEN 'New Lead Booking(International)' 
						WHEN scq_follow_up_queue_type = 21 THEN 'New Quote Booking(International)' 
						WHEN scq_follow_up_queue_type = 22 THEN 'FBG' 
						WHEN scq_follow_up_queue_type = 23 THEN 'Vendor Payment Request' 
						WHEN scq_follow_up_queue_type = 24 THEN 'Upsell(Value+/Select)' 
						WHEN scq_follow_up_queue_type = 25 THEN 'Booking Complete Review' 
						WHEN scq_follow_up_queue_type = 26 THEN 'Apps Help & Tech support' 
						WHEN scq_follow_up_queue_type = 27 THEN 'Gozo Now' 
						WHEN scq_follow_up_queue_type = 29 THEN 'Auto Lead Followup' 
						WHEN scq_follow_up_queue_type = 30 THEN 'Document Approval' 
						WHEN scq_follow_up_queue_type = 31 THEN 'Vendor Approval Zone Based Inventory' 
						WHEN scq_follow_up_queue_type = 32 THEN 'Critical and stress (risk) assignments(CSA)' 
						WHEN scq_follow_up_queue_type = 33 THEN 'Airport DailyRental'
						WHEN scq_follow_up_queue_type = 46 THEN	'Vendor Due Amount'
						WHEN scq_follow_up_queue_type = 51 THEN	'Booking Reschedule'
						WHEN scq_follow_up_queue_type = 53 THEN	'VIP/VVIP Booking'

					END AS QueueType,
					scq_create_date AS CreateDate,
					CASE WHEN scq_created_by_type = 1 THEN CONCAT(usr_name, ' ', usr_lname) 
						WHEN scq_created_by_type = 2 THEN CONCAT(vnd_name, '/', vnd_code)
						WHEN scq_created_by_type = 3 THEN CONCAT(drv_name, '/', drv_code)
						WHEN scq_created_by_type = 4 THEN CONCAT(adm2.gozen, '/', adp2.adp_emp_code)
						WHEN scq_created_by_type = 10 THEN 'System'
					END AS 'Create By',
				scq_creation_comments AS 'Creation Comment',
				CONCAT(adm.gozen, '(', adp.adp_emp_code, ')') AS 'Assigned CSR(Employee ID)',
				scq_assigned_date_time AS 'Assign Date',
				IF(
					scq_assigned_date_time IS NOT NULL,
					TIMESTAMPDIFF(
						MINUTE,
						scq_create_date,
						scq_assigned_date_time
					),
					0
				) AS 'Time to Assign(Mintue)',
				CONCAT(
					scq_disposition_date,
					'(',
					CONCAT(
						adm1.gozen,
						'/',
						adp1.adp_emp_code
					),
					')'
				) AS 'Closed Date (CSR)',
				IF(
					scq_disposition_date IS NOT NULL,
					TIMESTAMPDIFF(
						MINUTE,
						scq_assigned_date_time,
						scq_disposition_date
					),
					0
				) AS 'Time to Close(Mintue)',
				scq_disposition_comments AS 'Disposition Comments'
				FROM service_call_queue
				LEFT JOIN admins adm ON adm.adm_id = scq_assigned_uid
				LEFT JOIN admin_profiles adp ON adm.adm_id = adp.adp_adm_id
				LEFT JOIN admins adm1 ON adm1.adm_id = scq_disposed_by_uid
				LEFT JOIN admin_profiles adp1 ON adm1.adm_id = adp1.adp_adm_id
				LEFT JOIN admins adm2 ON adm2.adm_id = scq_created_by_uid AND scq_created_by_type = 4
				LEFT JOIN admin_profiles adp2 ON adm2.adm_id = adp2.adp_adm_id AND scq_created_by_type = 4
				LEFT JOIN contact_profile cp ON scq_created_by_uid = cp.cr_is_consumer
				LEFT JOIN vendors ON vendors.vnd_id = cp.cr_is_vendor AND scq_created_by_type = 2
				LEFT JOIN users ON users.user_id = cp.cr_is_consumer AND scq_created_by_type IN(2, 3)
				LEFT JOIN drivers ON drivers.drv_id = cp.cr_is_driver AND scq_created_by_type = 3
				WHERE 1 $createFromDays $createToDate $scqLeadSql
					AND scq_status IN(2) AND scq_active = 1 GROUP BY scq_id";

		$command		 = DBUtil::command($sql, DBUtil::SDB3());
		$command->params = $params;
		return $command;
	}

	/**
	 * 
	 * @param \Beans\contact\Scq $reqData
	 * @param int $cttId
	 * @param int $entityType
	 * @param int $scqType
	 * @param int $followupId
	 * @param int $platform
	 * @param array() $additionalParams
	 * @return \Beans\contact\Scq
	 * @throws Exception
	 */
	public static function generateModel(\Beans\contact\Scq $reqData, $cttId, $entityType, $scqType, $followupId = 0, $platform = ServiceCallQueue::PLATFORM_DCO_APP, $additionalParams = [])
	{
		$returnSet = new ReturnSet();
		if ($followupId > 0)
		{
			$fpModel = ServiceCallQueue::model()->findbyPk($followupId);

			$queueData				 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
			$fpModel->scq_queue_no	 = $queueData['queNo'];
			$fpModel->scq_waittime	 = $queueData['waitTime'];
			$fpModel->scq_active	 = $queueData['scq_active'];
			$success				 = true;
			$message				 = 'Call back request has already been initiated please cancel it to create it again . All the calls will be recorded for training and quality assurance purposes.';
			$data					 = \Beans\contact\Scq::setResponse($fpModel, $message);
		}
		else
		{
			/** @var ServiceCallQueue $model */
			$model										 = new ServiceCallQueue();
			$model->scq_follow_up_queue_type			 = $scqType == 6 ? ServiceCallQueue::TYPE_IMNTERNAL : $scqType;
			$model->scq_to_be_followed_up_with_value	 = $reqData->phone->fullNumber;
			$model->scq_creation_comments				 = trim($reqData->desc);
			$model->scq_creation_comments				 = filter_var($model->scq_creation_comments, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = UserInfo::getEntityId();
			$model->scq_created_by_uid					 = UserInfo::getUserId();
			$model->scq_to_be_followed_up_with_contact	 = $cttId;
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			if ($scqType == 6)
			{
				$model->scq_to_be_followed_up_by_type	 = 1;
				$model->scq_to_be_followed_up_by_id		 = 9;
			}

			if (isset($reqData->refId) && trim($reqData->refId) != '')
			{
				$model->scq_related_bkg_id = $reqData->refId;
			}
			if (!empty($additionalParams))
			{
				$params = $additionalParams;

				$model->scq_additional_param = json_encode($params);
			}
			$returnSet = $model->create($model, $entityType, $platform);
			if ($model->hasErrors())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_INVALID_DATA);
			}
			$message = "We will call back as soon as possible. \n All the calls will be recorded for training are quality assurance purposes. If your issues get resolved before our call, you can 'click' the cancel below.";
			$data	 = \Beans\contact\Scq::setResponse($model, $message);
			$success = true;
		}
		$returnSet->setData($data);
		$returnSet->setMessage($message);
		$returnSet->setStatus($success);
		return $returnSet;
	}

	/**
	 * This function is used for assign multiple queue for a particular queue
	 * @param type $scqId 
	 * @param type $teamId 
	 * @return boolean
	 */
	public static function assignMutipleQueue($scqId, $teamId)
	{
		$success = false;
		$model	 = ServiceCallQueue::model()->findbyPk($scqId);
		if ($model && $model->scq_assigned_uid && ( $model->scq_to_be_followed_up_with_value > 0 || $model->scq_to_be_followed_up_with_entity_id > 0))
		{
			$followedValue	 = $model->scq_to_be_followed_up_with_value;
			$entityId		 = $model->scq_to_be_followed_up_with_entity_id;
			$entityType		 = $model->scq_to_be_followed_up_with_entity_type;
			$csrId			 = $model->scq_assigned_uid;
			$sql			 = "SELECT 
								scq_id
							FROM service_call_queue
							JOIN
                            (
								SELECT 
								tqm_queue_id,
								tqm_priority,
								tqm_queue_weight
								FROM `team_queue_mapping`
								WHERE 1 
									AND `tqm_tea_id` =:teamId 
									AND tqm_active=1 
								ORDER BY tqm_priority ASC,tqm_queue_weight DESC
                            ) tqm ON tqm.tqm_queue_id = scq_follow_up_queue_type
							WHERE  1
								AND scq_status=1
								AND service_call_queue.scq_follow_up_queue_type <> 9
								AND scq_active=1
								AND scq_follow_up_date_time IS NOT NULL								
								AND scq_follow_up_date_time <= NOW()
								AND scq_assigned_date_time IS NULL
								AND scq_assigned_uid IS NULL
								AND
								(
									(scq_to_be_followed_up_with_type=1 AND scq_to_be_followed_up_with_value=:followedValue AND scq_to_be_followed_up_with_value>0) 
										OR 
									(scq_to_be_followed_up_with_type=2 AND scq_to_be_followed_up_with_value=:followedValue AND scq_to_be_followed_up_with_value>0) 
										OR
									(scq_to_be_followed_up_with_entity_type=:entityType AND scq_to_be_followed_up_with_entity_id=:entityId AND scq_to_be_followed_up_with_entity_id>0)
								)
							ORDER BY 
								tqm.tqm_priority ASC,
								tqm.tqm_queue_weight DESC,
								scq_follow_up_priority DESC,
								scq_priority_score DESC, 
								scq_follow_up_date_time ASC,
								scq_create_date ASC";
			$details		 = DBUtil::query($sql, DBUtil::MDB(), ['followedValue' => $followedValue, 'entityId' => $entityId, 'entityType' => $entityType, 'teamId' => $teamId]);
			foreach ($details as $row)
			{
				try
				{
					$count		 = self::getAssignmentCount($csrId, $teamId, true);
					$returnCount = self::assign($row['scq_id'], $csrId, $count, 1);
					if ($returnCount > 0)
					{
						self::assignTeam($row['scq_id'], $teamId);
					}
				}
				catch (Exception $ex)
				{
					ReturnSet::setException($ex);
				}
			}
			return $success;
		}
		return $success;
	}

	/**
	 * This function will return all active scqId
	 * @param type $csrId 
	 * @return type qyeryObject
	 */
	public static function getAllActiveCBRByCsrId($csrId)
	{
		$sql = "SELECT  scq_id FROM `service_call_queue` WHERE 1 AND scq_active=1 AND scq_assigned_uid IS NOT NULL AND scq_assigned_uid=:csrId  AND scq_status IN (1,3)";
		return DBUtil::query($sql, DBUtil::MDB(), ['csrId' => $csrId]);
	}

	/**
	 * This function will use to check cbr can be added into our database or not
	 * @param type $queueType 
	 * @param type $bkgId 
	 * @return type int
	 */
	public static function canCBRAdded($queueType, $bkgId)
	{
		$queue			 = (string) $queueType;
		DBUtil::getINStatement($queue, $bindString, $params);
		$params['bkgId'] = $bkgId;
		$sql			 = "SELECT COUNT(scq_id) as cnt FROM `service_call_queue` WHERE  1 AND scq_related_bkg_id =:bkgId AND scq_follow_up_queue_type IN ($bindString) AND scq_active=1 AND scq_status IN (1,3) AND scq_follow_up_date_time <= NOW()";
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	/**
	 * This function will create for CBR for existing booking team
	 * @param type $bkgId 
	 * @return type returnSet Object
	 */
	public static function autoFURCustomerOngoingTrip($bkgId, $msg)
	{
		try
		{
			$bookingModel	 = Booking::model()->findByPk($bkgId);
			$days			 = Filter::getDaysCount($bookingModel->bkg_pickup_date, date('Y-m-d H:i:s'));
			if ($days <= 180)
			{
				$model										 = new ServiceCallQueue();
				$countryCode								 = ($bookingModel->bkgUserInfo->bkg_country_code != '') ? $bookingModel->bkgUserInfo->bkg_country_code : '91';
				$model->scq_to_be_followed_up_with_value	 = $countryCode . str_replace(' ', '', $bookingModel->bkgUserInfo->bkg_contact_no);
				$model->scq_created_by_type					 = UserInfo::TYPE_CONSUMER;
				$model->scq_created_by_uid					 = $bookingModel->bkgUserInfo->bkg_user_id;
				$model->contactRequired						 = 1;
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_EXISTING_BOOKING;
				$model->scq_to_be_followed_up_with_entity_id = $bookingModel->bkgUserInfo->bkg_user_id;
				$model->scq_related_bkg_id					 = $bookingModel->bkg_id;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($bookingModel->bkgUserInfo->bkg_user_id, UserInfo::TYPE_CONSUMER);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				$model->followupPerson						 = 1;
				$model->scq_creation_comments				 = $msg;
				$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo::TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WHATSAPP);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating  auto FUR for document approval for driver and cabs
	 * @param string $vendorId
	 * @return $returnSet
	 */
	public static function autoFURVendorDueAmount($row)
	{
		$returnSet	 = new ReturnSet();
		$model		 = new ServiceCallQueue();
		try
		{
			$contactId									 = ContactProfile::getByEntityId($row['vndId'], UserInfo::TYPE_VENDOR);
			$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
			$code										 = $arrPhoneByPriority['phn_phone_country_code'];
			$number										 = $arrPhoneByPriority['phn_phone_no'];
			$model->contactRequired						 = 1;
			Filter::parsePhoneNumber($number, $code, $phone);
			$model->scq_to_be_followed_up_with_value	 = $code . $phone;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->scq_to_be_followed_up_with_entity_id = $row['vndId'];
			$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VENDOR_DUE_AMOUNT;
			$model->scq_creation_comments				 = "Call the vendor  and ask him to pay the " . $row['balance'] . " money which owes to gozo";
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo:: TYPE_SYSTEM, ServiceCallQueue::PLATFORM_WEB_DESKTOP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param int $userId
	 * @param \Beans\contact\Scq $reqData
	 */
	public static function getExistingFollowupId($userId, $reqData)
	{
		$queType = $reqData->queType;
		$bkgId	 = null;
		switch ($queType)
		{
			case 11:
				if ($reqData->refType == ServiceCallQueue::REF_BOOKING && $reqData->refId > 0)
				{
					$bkgId = $reqData->refId;
				}

				break;
			case 6:
				$queType = 9;
				break;

			default:

				break;
		}

		$followupId = ServiceCallQueue::getIdByUserId($userId, $queType, $bkgId);
		return $followupId;
	}

	/**
	 * This function will create for CBR for existing booking team
	 * @param type $phone 
	 * @param type $queueType 
	 * @param type $message 
	 * @param type $userId 
	 * @param type $bkgId 
	 * @param type $force 
	 * @return type returnSet Object
	 */
	public static function autoFURCustomerBookingTrip($phone, $queueType, $message, $contactId = null, $userId = null, $bkgId = null, $force = false)
	{
		try
		{
			$model										 = new ServiceCallQueue();
			$model->force								 = $force;
			$model->scq_to_be_followed_up_with_value	 = $phone;
			$model->scq_created_by_type					 = UserInfo::TYPE_CONSUMER;
			$model->scq_created_by_uid					 = $userId;
			$model->contactRequired						 = 1;
			$model->scq_follow_up_queue_type			 = $queueType;
			$model->scq_to_be_followed_up_with_entity_id = $userId;
			$model->scq_related_bkg_id					 = $bkgId;
			$model->scq_to_be_followed_up_with_contact	 = $contactId;
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			$model->followupPerson						 = 1;
			$model->scq_creation_comments				 = $message;
			$returnSet									 = ServiceCallQueue::model()->create($model, UserInfo::TYPE_CONSUMER, ServiceCallQueue::PLATFORM_WHATSAPP);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for checking  service call queue  active  for particular phone number
	 * @param type $userId
	 * @param type $reftype
	 * @return integer
	 */
	public static function checkActiveCBRByPhone($phone, $reftype = 0)
	{
		$params	 = ['phone' => $phone];
		$where	 = "";
		if ($reftype > 0)
		{
			$params['scq_follow_up_queue_type']	 = $reftype;
			$where								 = " AND scq_follow_up_queue_type =:scq_follow_up_queue_type ";
		}
		$sql = "SELECT scq_id	FROM     service_call_queue	WHERE  1 AND scq_to_be_followed_up_with_type =1	AND   scq_to_be_followed_up_with_value  =:phone	AND scq_active=1 $where AND scq_status IN (1,3) AND scq_assigned_uid  IS NULL ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * This function is used for checking service call queue is active or not regarding the booking and followup queue type 
	 * @param type $bkgId
	 * @param type $userId
	 * @param type $refType
	 * @return integer
	 */
	public static function checkActiveCBRByBookingId($bkgId, $userId, $refType)
	{
		$params	 = ['bkgId' => $bkgId, 'userId' => $userId, 'refType' => $refType];
		$sql	 = "SELECT scq_id FROM service_call_queue WHERE 1 AND scq_created_by_uid =:userId AND scq_related_bkg_id =:bkgId AND scq_follow_up_queue_type =:refType AND scq_status IN(1,3) AND scq_active=1";
		return DBUtil::queryScalar($sql, DButil::SDB(), $params);
	}

	/**
	 * This function is used for checking driver custom push event service call queue is active or closed or not regarding the booking and followup queue type 
	 * @param type $bkgId
	 * @param type $drvId
	 * @param type $refType
	 * @return integer
	 */
	public static function checkCustomPushApiCbrByBookingId($bkgId, $drvId, $refType)
	{
		$params	 = ['bkgId' => $bkgId, 'drvId' => $drvId, 'refType' => $refType];
		$sql	 = "SELECT scq_id FROM service_call_queue WHERE 1 AND scq_to_be_followed_up_with_entity_id =:drvId AND scq_related_bkg_id =:bkgId AND scq_follow_up_queue_type =:refType AND scq_status IN(1,3) AND scq_active=1";
		return DBUtil::queryScalar($sql, DButil::SDB(), $params);
	}

	/**
	 * 
	 * @param type $phone
	 * @param type $queueType
	 * @param type $message
	 * @param type $contactId
	 * @param type $userId
	 * @param type $bkgId
	 * @return type
	 */
	public static function customPushApiGenerateCbr($phone, $queueType, $message, $contactId = null, $driverId, $bkgId = null)
	{
		try
		{
			$model											 = new ServiceCallQueue();
			$model->scq_to_be_followed_up_with_value		 = $phone;
			$model->scq_to_be_followed_up_with_type			 = 2;
			$model->scq_created_by_type						 = UserInfo::TYPE_SYSTEM;
			$model->scq_to_be_followed_up_with_entity_rating = -1;
			$model->scq_follow_up_queue_type				 = $queueType;
			$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_DRIVER;
			$model->scq_to_be_followed_up_with_entity_id	 = $driverId;
			$model->scq_created_by_uid						 = $model->scq_to_be_followed_up_with_entity_id;
			$model->scq_related_bkg_id						 = $bkgId;
			$model->scq_to_be_followed_up_with_contact		 = $contactId;
			$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
			$model->scq_follow_up_priority					 = 5;
			$model->followupPerson							 = 1;
			$model->contactRequired							 = 0;
			$model->scq_creation_comments					 = $message;
			$returnSet										 = ServiceCallQueue::model()->create($model, UserInfo::TYPE_DRIVER, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function notifyTaggedBooking($bkgId, $comment)
	{
		$scqId = self::checkActiveCBRByBookingId($bkgId, 0, ServiceCallQueue::TYPE_VVIP_BOOKING);
		if ($scqId > 0)
		{
			return true;
		}
		$modelBooking								 = Booking::model()->findByPk($bkgId);
		$model										 = new ServiceCallQueue();
		$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VVIP_BOOKING;
		$model->scq_to_be_followed_up_by_type		 = 1;
		$model->scq_to_be_followed_up_by_id			 = 5;
		$model->scq_related_bkg_id					 = $bkgId;
		$model->scq_to_be_followed_up_with_value	 = $modelBooking->bkgUserInfo->bkg_country_code . $modelBooking->bkgUserInfo->bkg_contact_no;
		$model->scq_creation_comments				 = "Auto generated Call Back Request for {$comment} customer booking";
		$model->scq_created_by_type					 = UserInfo::TYPE_SYSTEM;
		$model->scq_created_by_uid					 = 0;
		$model->scq_to_be_followed_up_with_entity_id = $modelBooking->bkgUserInfo->bkg_user_id;
		$pickupTimeMinus4hour						 = date('Y-m-d H:i:s', strtotime("-4 hour", strtotime($modelBooking->bkg_pickup_date)));
		if ($pickupTimeMinus4hour > Filter::getDBDateTime())
		{
			$model->scq_follow_up_date_time = $pickupTimeMinus4hour;
		}
		ServiceCallQueue::model()->create($model, UserInfo::TYPE_CONSUMER, ServiceCallQueue::PLATFORM_SYSTEM);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $userId
	 * @param type $issueId
	 * @return type
	 */
	public static function checkActiveCBRByIssueId($bkgId, $userId, $issueId, $queueId)
	{
		$params	 = ['bkgId' => $bkgId, 'userId' => $userId, 'issueId' => $issueId, 'queueId' => $queueId];
		$sql	 = "SELECT scq_id FROM service_call_queue WHERE 1 AND scq_created_by_uid =:userId AND scq_related_bkg_id =:bkgId AND JSON_EXTRACT(scq_additional_param, '$.issueId') =:issueId AND scq_follow_up_queue_type =:queueId AND scq_status IN(1,3) AND scq_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public static function closeTaggedBookingCBR($bkgId)
	{
		$refType = ServiceCallQueue::TYPE_VVIP_BOOKING; // vip/vvip booking
		$scqId	 = self::checkActiveCBRByBookingId($bkgId, 0, $refType);
		if ($scqId > 0)
		{
			ServiceCallQueue::updateStatus($scqId, 10, 0, "CBR expired On booking mark complete");
		}
	}

	/**
	 * 
	 * @param type $teamId
	 * @param type $durationHour
	 * @return type
	 */
	public static function getTotalClosedCallingByTeam($teamId, $durationHour = 1)
	{
		$params = array('teamId' => $teamId, 'duration' => $durationHour);

		$sql = 'SELECT COUNT(DISTINCT scq_assigned_uid) countCSR, COUNT(DISTINCT scq_id) countClosedCalls
				FROM  service_call_queue
				WHERE  scq_to_be_followed_up_by_id = :teamId 
					AND	scq_to_be_followed_up_by_type=1 
					AND scq_active=1 AND scq_status IN (2) 
					AND scq_follow_up_date_time > DATE_SUB(NOW(),INTERVAL :duration HOUR)';
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	/**
	 * 
	 * @param int $scqId
	 * @return string
	 */
	public static function getTeamsById($scqId)
	{
		$params	 = array('scqId' => $scqId);
		$sql	 = 'SELECT GROUP_CONCAT(tqm.tqm_tea_id) teamIds FROM `service_call_queue` scq
			INNER JOIN team_queue_mapping tqm ON
			tqm.tqm_queue_id=scq.scq_follow_up_queue_type
			AND tqm.tqm_active = 1
			WHERE `scq_id` = :scqId';
		return DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
	}

	public static function processActiveWaitingTimeById($scqId, $maxWaitTime = 240)
	{
		$teamIds = self::getTeamsById($scqId);
		if (!$teamIds)
		{
			return false;
		}

		DBUtil::getINStatement($teamIds, $bindString, $params);
		$params['scqId'] = $scqId;

		$sql = "SELECT * ,IFNULL(cntSCQClosedSelected,0) cntSCQClosedSelected, 
			IF(cntSCQClosedSelected IS NULL , $maxWaitTime, CEIL(rank * 60/cntSCQClosedSelected)) as totalWaitMinutes 
		FROM (
		SELECT DISTINCT teamId, queueId, scq_id,scq_active, rank FROM (
            SELECT  tqm_tea_id teamId,tqm_queue_id queueId, scq_id,scq_active,
				RANK() OVER (PARTITION BY tqm_tea_id ORDER BY  tqm_priority ASC, tqm_queue_weight DESC, scq_follow_up_priority DESC, scq_priority_score DESC, scq_follow_up_date_time ASC) as rank
			FROM  service_call_queue
			INNER JOIN team_queue_mapping ON 
				((scq_to_be_followed_up_by_id IS NULL OR (scq_to_be_followed_up_by_type=1 AND scq_to_be_followed_up_by_id=tqm_tea_id))
					AND team_queue_mapping.tqm_queue_id=service_call_queue.scq_follow_up_queue_type)
			WHERE tqm_tea_id IN ($bindString)
				AND scq_status IN (1,3) AND scq_active = 1 AND scq_assigned_uid IS NULL
		) scqRank WHERE scq_id=:scqId ) a
		LEFT JOIN (
			SELECT cdt_tea_id,
				GROUP_CONCAT(DISTINCT ap.adp_adm_id) AS CSRActive,
				COUNT(DISTINCT ap.adp_adm_id) AS cntCSRActive,
				COUNT(DISTINCT scq_id) AS cntSCQClosedSelected
			FROM  service_call_queue scq
			INNER JOIN admin_profiles ap ON ap.adp_adm_id=scq_assigned_uid
			INNER JOIN cat_depart_team_map cdt ON JSON_CONTAINS(ap.adp_cdt_id, JSON_object('cdtId',cdt.cdt_id))
			WHERE  scq_active=1  AND scq_status IN (2) AND cdt.cdt_tea_id IN ($bindString)
				AND scq_follow_up_date_time > DATE_SUB(NOW(),INTERVAL 1 HOUR)
			GROUP BY cdt.cdt_tea_id
		) b ON a.teamId=b.cdt_tea_id ";
		$res = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		return $res;
	}

	public static function getRankById($scqId)
	{
		$teamIds = self::getTeamsById($scqId);

		if (!$teamIds)
		{
			return false;
		}
		DBUtil::getINStatement($teamIds, $bindString, $params);
		$params['scqId'] = $scqId;

		$sql = " 
		SELECT DISTINCT teamId, scq_id, rank FROM (
            SELECT  tqm_tea_id teamId, scq_id,
				RANK() OVER (PARTITION BY tqm_tea_id ORDER BY  tqm_priority ASC, tqm_queue_weight DESC, scq_follow_up_priority DESC, scq_priority_score DESC, scq_follow_up_date_time ASC) as rank
			FROM  service_call_queue
			INNER JOIN team_queue_mapping ON 
				((scq_to_be_followed_up_by_id IS NULL OR (scq_to_be_followed_up_by_type=1 AND scq_to_be_followed_up_by_id=tqm_tea_id))
					AND team_queue_mapping.tqm_queue_id=service_call_queue.scq_follow_up_queue_type)
			WHERE tqm_tea_id IN ($bindString)
				AND scq_status IN (1,3) AND scq_active = 1 AND scq_assigned_uid IS NULL
		) scqRank WHERE scq_id=:scqId  ";
		$res = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		return $res;
	}

	public static function getActiveWaitingTimeById($scqId)
	{
		$scqModel			 = ServiceCallQueue::model()->findByPk($scqId);
		/** @var ServiceCallQueue $scqModel */
		$maxWaitList		 = ServiceCallQueue::maxWaitHourByPriority;
		$priority			 = $scqModel->scq_follow_up_priority;
		$maxWaitMinutes		 = $maxWaitList[$priority] * 60;
		$data				 = self::processActiveWaitingTimeById($scqId, $maxWaitMinutes);
		$totalWaitMinutes	 = $data['totalWaitMinutes'] | 0;
		$queueRank			 = $data['rank'];
		if (!$totalWaitMinutes)
		{
			$dataRank			 = self::getRankById($scqId);
			$queueRank			 = $dataRank['rank'];
			$teamId				 = $dataRank['teamId'];
			$dataTeam			 = ServiceCallQueue::getTotalClosedCallingByTeam($teamId);
			$countCSR			 = $dataTeam['countCSR'];
			$countClosedCalls	 = $dataTeam['countClosedCalls'];
			$totalWaitMinutes	 = ($countClosedCalls > 0) ? ceil($queueRank * 60 / $countClosedCalls) : $maxWaitMinutes;
			if (!$data)
			{
				$data = [];
			}
			if (!$dataRank)
			{
				$dataRank = [];
			}
			$data = $data + $dataRank + $dataTeam;
		}
		$totalWaitMinutes			 = ($totalWaitMinutes <= 2) ? 2 : $totalWaitMinutes;
		$totalWaitMinutes			 = ($totalWaitMinutes > $maxWaitMinutes ) ? $maxWaitMinutes : $totalWaitMinutes;
		$data['totalWaitMinutes']	 = $totalWaitMinutes;
		return Filter::removeNull($data);
	}

	/**
	 * This function is used for assignment of  service call queue to particular csr
	 * @param integer $csr
	 * @param integer $teamId
	 * @param integer $count
	 * @return integer scq_id
	 */
	private static function assignTeam($scqId, $teamId)
	{
		$model = ServiceCallQueue::model()->detail($scqId);
		if ($model->scq_to_be_followed_up_by_type > 0 && $model->scq_to_be_followed_up_by_id > 0)
		{
			return;
		}
		else
		{
			$sql	 = "UPDATE service_call_queue SET scq_to_be_followed_up_by_type =1,scq_to_be_followed_up_by_id=:teamId WHERE scq_id =:scq_id AND scq_active=1";
			$numrows = DBUtil::execute($sql, ['teamId' => $teamId, 'scq_id' => $scqId]);
			if ($numrows == 0)
			{
				return;
			}
			return ($numrows > 0);
		}
	}

	/**
	 * function use to close or deleted service call que
	 * @param type $scqId
	 * @param type $csrId
	 */
	public static function updateVendorSCQ($scqId, $csrId)
	{
		$status	 = ($csrId > 0 ? 2 : 0);
		$params	 = ['scqId' => $scqId];
		$sql	 = "UPDATE service_call_queue SET scq_status = $status WHERE scq_id = :scqId ";
		DBUtil::execute($sql, $params);
	}

	/**
	 * function to show vendor whose all documents are approve or
	 * @param type $vendorId
	 */
	public static function showUpdateVendorSCQ($vendorId)
	{
		$params = ['vndid' => $vendorId];

		$sql = "SELECT scq_id,
					(IFNULL(docvoter.doc_active,0) + IFNULL(docaadhar.doc_active,0) +IFNULL(docpan.doc_active,0) +IFNULL(doclicence.doc_active,0) +IFNULL(docpolicever.doc_active,0)) as activeDoc,`scq_preferred_csr`
					FROM `service_call_queue`
					INNER JOIN vendors ON vendors.vnd_id = service_call_queue.scq_to_be_followed_up_with_entity_id AND service_call_queue.scq_to_be_followed_up_with_entity_type=2  
					INNER JOIN contact_profile AS cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status =1 
					INNER JOIN contact ON contact.ctt_id = cp.cr_contact_id AND contact.ctt_id = contact.ctt_ref_code AND contact.ctt_active =1
					LEFT JOIN document as docvoter ON contact.ctt_voter_doc_id = docvoter.doc_id AND contact.ctt_voter_doc_id > 0 AND docvoter.doc_type = 2 AND docvoter.doc_active = 1 AND docvoter.doc_status=0  
					LEFT JOIN document as docaadhar ON contact.ctt_aadhar_doc_id = docaadhar.doc_id AND contact.ctt_aadhar_doc_id > 0 AND docaadhar.doc_type = 3 AND docaadhar.doc_active = 1 AND docaadhar.doc_status=0   AND docaadhar.doc_created_at BETWEEN CURDATE() AND NOW()
					LEFT JOIN document as docpan ON contact.ctt_pan_doc_id = docpan.doc_id AND contact.ctt_pan_doc_id > 0 AND docpan.doc_type = 4 AND docpan.doc_active = 1  AND docpan.doc_status=0  AND docpan.doc_created_at BETWEEN CURDATE() AND NOW()
					LEFT JOIN document as doclicence ON contact.ctt_license_doc_id = doclicence.doc_id AND contact.ctt_license_doc_id > 0 AND doclicence.doc_type = 5 AND doclicence.doc_active = 1  AND doclicence.doc_status=0  AND doclicence.doc_created_at BETWEEN CURDATE() AND NOW()
					LEFT JOIN document as docpolicever ON contact.ctt_police_doc_id = docpolicever.doc_id AND contact.ctt_police_doc_id > 0 AND docpolicever.doc_type = 7 AND docpolicever.doc_active = 1  AND docpolicever.doc_status=0  AND docpolicever.doc_created_at BETWEEN CURDATE() AND NOW() 
					WHERE scq_follow_up_queue_type =30 AND scq_status =1 AND scq_active=1 AND scq_to_be_followed_up_with_entity_id =:vndid HAVING activeDoc=0  
					ORDER BY `service_call_queue`.`scq_preferred_csr`  DESC";
		##exit;
		$scq = DBUtil::query($sql, DBUtil::SDB(), $params);

		foreach ($scq as $scqData)
		{
			self::updateVendorSCQ($scqData['scq_id'], $scqData['scq_preferred_csr']);
		}
	}

	/**
	 * This function is used for creating  driver collected extra amount
	 * @var booking $bkgId and $userInfo
	 * @return $returnSet
	 */
	public static function addFollowupForExtraDriverCollected($bkgId)
	{
		$modelUser										 = BookingUser::model()->getByBkgId($bkgId);
		$model											 = new ServiceCallQueue();
		$contactId										 = ContactProfile::getByUserId($modelUser->bkg_user_id);
		$model->followupPerson							 = 1;
		$model->scq_created_by_type						 = UserInfo::TYPE_SYSTEM;
		$model->scq_to_be_followed_up_by_type			 = 1;
		$model->scq_to_be_followed_up_by_id				 = 5;
		$model->scq_to_be_followed_up_with_entity_type	 = 1;
		$model->scq_to_be_followed_up_with_entity_id	 = $modelUser->bkg_user_id;
		$model->scq_to_be_followed_up_with_contact		 = $contactId;
		$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
		$model->scq_follow_up_priority					 = 3;
		$model->scq_creation_comments					 = "Kindly validate with customer and driver the total payment made/received against this trip.";
		$model->scq_related_bkg_id						 = $bkgId;
		$model->scq_to_be_followed_up_with_value		 = $modelUser->bkg_contact_no;
		$model->scq_to_be_followed_up_with_type			 = 2;
		$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
		$model->scq_ref_type							 = 2;
		$entityType										 = UserInfo::TYPE_SYSTEM;
		$returnSet										 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
	}

}
