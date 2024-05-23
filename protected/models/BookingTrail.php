<?php

/**
 * This is the model class for table "booking_trail".
 *
 * The followings are the available columns in table 'booking_trail':
 * @property integer $btr_id
 * @property integer $btr_bkg_id
 * @property integer $btr_drv_score
 * @property integer $btr_active
 * @property integer $bkg_platform
 * @property integer $bkg_platform_ref_id
 * @property integer $bkg_rating
 * @property integer $bkg_review_email_count
 * @property integer $bkg_tnc_id
 * @property integer $bkg_tnc
 * @property string $bkg_tnc_time
 * @property string $bkg_tags
 * @property integer $bkg_follow_type_id
 * @property string $bkg_followup_date
 * @property string $bkg_followup_comment
 * @property integer $bkg_followup_active
 * @property integer $btr_unv_followup_by
 * @property integer $btr_unv_followup_time
 * @property integer $bkg_upsell_status
 * @property integer $bkg_escalation_status
 * @property integer $bkg_assign_csr
 * @property integer $bkg_is_related_booking
 * @property integer $bkg_vendor_request_cnt
 * @property string $bkg_user_ip
 * @property string $bkg_user_device
 * @property integer $bkg_non_profit_flag
 * @property integer $bkg_non_profit_override_flag
 * @property string $bkg_payment_expiry_time
 * @property integer $bkg_adv_reminder_email_cnt
 * @property integer $bkg_adv_reminder_sms_cnt
 * @property integer $bkg_assign_mode
 * @property string $bkg_assigned_at
 * @property integer $bkg_auto_assign_ctr
 * @property string $bkg_first_request_sent
 * @property integer $bkg_confirm_user_type
 * @property integer $bkg_confirm_user_id
 * @property integer $bkg_confirm_datetime
 * @property integer $bkg_create_user_type
 * @property integer $bkg_create_user_id
 * @property integer $bkg_create_type
 * @property integer $bkg_confirm_type
 * @property integer $bkg_cancel_user_type
 * @property integer $bkg_cancel_user_id
 * @property string $btr_cancel_date
 * @property string $btr_assigned_fdate
 * @property string $btr_cab_assign_fdate
 * @property string $btr_cab_assign_ldate
 * @property string $btr_mark_complete_date
 * @property integer $btr_cab_assigned_sent_email_cnt
 * @property string $btr_auto_assign_date
 * @property string $btr_manual_assign_date
 * @property string $btr_critical_assign_date
 * @property string $btr_vendor_assign_fdate
 * @property string $btr_vendor_assign_ldate
 * @property string $btr_driver_assign_fdate
 * @property string $btr_driver_assign_ldate
 * @property string $btr_escalation_fdate
 * @property string $btr_escalation_ldate
 * @property string $btr_escalation_remove_date
 * @property string $btr_reconfirm_date
 * @property string $btr_payment_receive_fdate
 * @property string $btr_payment_receive_ldate
 * @property integer $btr_count_vendor_assign
 * @property integer $btr_count_driver_assign
 * @property integer $btr_count_cab_assign
 * @property integer $btr_count_payment_received
 * @property integer $btr_count_escalation
 * @property string $btr_estimate_complete_date
 * @property integer $btr_driver_app_use
 * @property string $btr_last_cron_unv_followup
 * @property integer $btr_cron_unv_followup_ctr
 * @property integer $btr_unv_followup_link_open_cnt
 * @property string $btr_unv_followup_link_open_first_time
 * @property string $btr_last_cron_final_followup
 * @property integer $btr_cron_final_followup_ctr
 * @property string $btr_final_followup_link_open_first_time
 * @property integer $btr_final_followup_link_open_cnt
 * @property integer $btr_vendor_active_status
 * @property integer $btr_driver_approved_status
 * @property integer $btr_vehicle_approved_status
 * @property integer $btr_is_bid_started
 * @property string $btr_bid_start_time
 * @property integer $btr_is_dbo_applicable
 * @property integer $btr_dbo_amount
 * @property integer $btr_is_dem_sup_misfire
 * @property integer $btr_vendor_unassign_penalty
 * @property integer $btr_nmi_flag
 * @property integer $btr_nmi_requester_id
 * @property integer $btr_nmi_reason
 * @property integer $btr_bid_floated_logged_id
 * @property integer $btr_bid_floated
 * @property integer $btr_escalation_level
 * @property integer $btr_escalation_assigned_lead
 * @property integer $btr_escalate_info_all
 * @property integer $btr_escalation_assigned_team
 * @property integer $btr_car_fassign_ttp
 * @property integer $btr_car_lassign_ttp
 * @property integer $btr_driver_fassign_ttp
 * @property integer $btr_driver_lassign_ttp
 * @property string $bkg_quote_expire_date
 * @property string $bkg_quote_expire_max_date
 * @property string $btr_epass
 * @property integer $btr_auto_cancel_value
 * @property integer $btr_auto_cancel_reason_id
 * @property string $btr_auto_cancel_create_date
 * @property integer $btr_is_datadiscrepancy
 * @property string $btr_datadiscrepancy_remarks
 * @property string $btr_vendor_last_unassigned
 * @property int $btr_drv_api_sync_error
 * @property integer $btr_stop_increasing_vendor_amount
 * @property string $bkg_gnow_timer_log
 * @property string $bkg_gnow_timer_customer_last_sync
 * @property string $bkg_gnow_created_at
 * @property integer $btr_auto_cancel_rule_id
 * 
 * The followings are the available model relations:
 * @property Booking $btrBkg
 */
class BookingTrail extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public $follow_ups;
	public $bkg_followup_time, $locale_followup_date, $locale_followup_time, $btr_level_green, $bkg_quote_expire_time, $bkg_quote_expire_time_1, $booking_quote_expiry_1;
	public $btr_nmi_flag_var;
	public $cancelTime;
	public $booking_platform = ['1' => 'User', '2' => 'Admin', '3' => 'App', '4' => 'Agent', '5' => 'Partner Spot', '6' => 'Bot'];

	CONST ConfirmType_Quote			 = 1;
	CONST ConfirmType_Unverified		 = 2;
	CONST ConfirmType_Lead			 = 3;
	CONST ConfirmType_Self			 = 4;
	CONST ConfirmType_UnverifiedQuote	 = 5;
	CONST CreateType_Quoted			 = 1;
	CONST CreateType_Lead				 = 2;
	CONST CreateType_Self				 = 3;

	public $confirmType	 = [1 => 'Quote to New', 2 => 'Unverified to New', 3 => 'Lead to New', 4 => 'Direct New', 5 => 'Quote to Unverified'];
	public $nmiReason	 = [
		1	 => 'Booking came at the last minute',
		2	 => 'Previous vendor denied the pickup',
		3	 => 'Auto assign didnt happen because our Vendor amount was too low?',
		4	 => 'Supply problem. No Yellow-plate, mostly Private cars in source city',
		5	 => 'Other'];
	public $escalation	 = [
		//1=>['color' => 'None', 'levelDesc' => 'Booking was never escalated'],
		2	 => ['color' => '0-Green [IN CONTROL. BUT ESCALATION IS STILL ON]', 'levelDesc' => 'IN CONTROL. BUT ESCALATION IS STILL ON'],
		3	 => ['color' => '1-Blue [BEING SOLVED WITHIN THE TEAM]', 'levelDesc' => 'BEING SOLVED WITHIN THE TEAM'],
		4	 => ['color' => '2-Yellow [ESCALATED TO TEAM LEADER]', 'levelDesc' => 'ESCALATED TO TEAM LEADER'],
		5	 => ['color' => '3-Orange [INTER-DEPARTMENT ALERT]', 'levelDesc' => 'INTER-DEPARTMENT ALERT'],
		6	 => ['color' => '4-Red [EMERGENCY / TOP MANAGEMENT ALERT]', 'levelDesc' => 'EMERGENCY / TOP MANAGEMENT ALERT']
	];
	public $teamArr		 = [
		1	 => 'Accounts Team',
		2	 => 'Vendor Advocacy Team',
		3	 => 'Gozo Cares(Customer advocacy) team',
		4	 => 'Field Operations managers team',
		5	 => 'B2B Sales team',
		6	 => 'B2C Leads and Sales team',
		7	 => 'Vendor Onboarding Team',
		8	 => 'Call center support team',
		9	 => 'Floor Tls team',
		10	 => 'Customer support manager'
	];

	public function tableName()
	{
		return 'booking_trail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('btr_bkg_id', 'required'),
			array('btr_bkg_id, btr_drv_score, btr_active, bkg_platform, bkg_platform_ref_id, bkg_rating, bkg_review_email_count, bkg_tnc_id, bkg_tnc, bkg_follow_type_id, bkg_followup_active, bkg_upsell_status, bkg_escalation_status, bkg_assign_csr, bkg_is_related_booking, bkg_vendor_request_cnt, bkg_non_profit_flag, bkg_non_profit_override_flag, bkg_adv_reminder_email_cnt, bkg_adv_reminder_sms_cnt, btr_cab_assigned_sent_email_cnt', 'numerical', 'integerOnly' => true),
			array('bkg_tags', 'length', 'max' => 700),
			array('bkg_followup_comment', 'length', 'max' => 2000),
			array('bkg_user_ip', 'length', 'max' => 100),
			array('bkg_user_device', 'length', 'max' => 255),
			['bkg_tnc', 'validatetnc', 'on' => 'tnc,tncAgent'],
			['bkg_vendor_request_cnt', 'required', 'on' => 'vendor_request_counter'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('btr_id, btr_bkg_id, btr_drv_score, btr_active, bkg_platform, bkg_platform_ref_id, bkg_rating, bkg_review_email_count, bkg_tnc_id, bkg_tnc, bkg_tnc_time, bkg_tags, bkg_follow_type_id, bkg_followup_date, bkg_followup_comment, bkg_followup_active, bkg_upsell_status, bkg_escalation_status, bkg_assign_csr, bkg_is_related_booking, bkg_vendor_request_cnt, bkg_user_ip, bkg_user_device, bkg_non_profit_flag, bkg_non_profit_override_flag, bkg_payment_expiry_time, bkg_adv_reminder_email_cnt, bkg_adv_reminder_sms_cnt,bkg_followup_time,bkg_assign_mode,bkg_assigned_at,bkg_auto_assign_ctr,bkg_first_request_sent, btr_cab_assigned_sent_email_cnt,locale_followup_date,locale_followup_time,btr_epass,bkg_gnow_created_at', 'safe'),
			array('bkg_payment_expiry_time', 'required', 'on' => 'updatepaymentexpiry'),
			array('bkg_user_ip', 'length', 'max' => 100),
			array('bkg_followup_date, bkg_followup_comment', 'required', 'on' => 'followup_scope'),
			array('bkg_confirm_type, bkg_confirm_user_type,  bkg_confirm_datetime', 'required', 'on' => 'confirmBooking'),
			array('bkg_user_device', 'length', 'max' => 255),
			array('bkg_user_ip', 'length', 'max' => 100),
			array(' bkg_confirm_user_type,btr_unv_followup_by,btr_unv_followup_time,bkg_confirm_datetime,bkg_create_user_id,bkg_gnow_timer_log,
				bkg_confirm_type,bkg_confirm_user_id,bkg_create_user_type,bkg_cancel_user_type,bkg_cancel_user_id,btr_cancel_date,
				btr_assigned_fdate,btr_cab_assign_fdate,btr_cab_assign_ldate,btr_mark_complete_date,btr_auto_assign_date,btr_manual_assign_date,
				btr_critical_assign_date,btr_vendor_assign_fdate,btr_vendor_assign_ldate,btr_driver_assign_fdate,btr_driver_assign_ldate,
				btr_escalation_fdate,btr_escalation_ldate,btr_reconfirm_date,btr_payment_receive_fdate,btr_payment_receive_ldate,
				btr_count_vendor_assign,btr_count_driver_assign,btr_count_cab_assign,btr_count_payment_received,btr_count_escalation,
				btr_estimate_complete_date,btr_driver_app_use,btr_vendor_active_status,btr_driver_approved_status,btr_vehicle_approved_status,
				btr_is_bid_started,btr_bid_start_time,btr_is_dem_sup_misfire,btr_last_cron_unv_followup,btr_cron_unv_followup_ctr,btr_unv_followup_link_open_cnt,btr_unv_followup_link_open_first_time,
				btr_last_cron_final_followup,btr_cron_final_followup_ctr,btr_final_followup_link_open_cnt,btr_final_followup_link_open_first_time,btr_is_dbo_applicable,btr_dbo_amount,btr_vendor_unassign_penalty,
                btr_nmi_reason,btr_nmi_flag,btr_nmi_requester_id,btr_escalation_level,btr_escalation_assigned_lead,btr_escalate_info_all,btr_escalation_assigned_team,
                btr_escalation_remove_date,btr_car_fassign_ttp,btr_stop_increasing_vendor_amount,btr_car_lassign_ttp,btr_driver_fassign_ttp,btr_driver_lassign_ttp,bkg_quote_expire_date,btr_epass,btr_auto_cancel_value,btr_auto_cancel_reason_id,btr_auto_cancel_create_date,btr_is_datadiscrepancy,btr_datadiscrepancy_remarks,follow_ups,btr_vendor_last_unassigned,btr_drv_api_sync_error,bkg_gnow_timer_customer_last_sync,btr_auto_cancel_rule_id', 'safe'),
			array('btr_nmi_reason', 'required', 'on' => 'addnmireason'),
			// array('btr_escalation_level, btr_escalation_assigned_team,btr_escalation_assigned_lead', 'required', 'on' => 'escalation'),
			['bkg_escalation_status', 'validateEscalation', 'on' => 'escalation'],
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
			'btrBkg' => array(self::BELONGS_TO, 'Booking', 'btr_bkg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'btr_id'							 => 'Btr',
			'btr_bkg_id'						 => 'Btr Bkg',
			'btr_drv_score'						 => 'Btr Drv Score',
			'btr_active'						 => 'Btr Active',
			'bkg_platform'						 => 'Booking Platform',
			'bkg_platform_ref_id'				 => 'Bkg Platform Ref',
			'bkg_rating'						 => 'Rating',
			'bkg_review_email_count'			 => 'Bkg Review Email Count',
			'bkg_tnc_id'						 => 'Bkg Tnc',
			'bkg_tnc'							 => 'Bkg Tnc',
			'bkg_tnc_time'						 => 'Bkg Tnc Time',
			'bkg_tags'							 => 'Bkg Tags',
			'bkg_follow_type_id'				 => 'Bkg Follow Type',
			'bkg_followup_date'					 => 'Followup Date',
			'bkg_followup_comment'				 => 'Enter Followup Comment',
			'bkg_followup_active'				 => 'Require Followup',
			'bkg_upsell_status'					 => 'Bkg Upsell Status',
			'bkg_escalation_status'				 => 'Escalate booking to management',
			'bkg_assign_csr'					 => 'Bkg Assign Csr',
			'bkg_is_related_booking'			 => 'Is Related Booking',
			'bkg_vendor_request_cnt'			 => 'Bkg Vendor Request Cnt',
			'bkg_user_ip'						 => 'User Ip',
			'bkg_user_device'					 => 'User Device',
			'bkg_non_profit_flag'				 => 'Bkg Non Profit Flag',
			'bkg_non_profit_override_flag'		 => 'Bkg Non Profit Override Flag',
			'bkg_payment_expiry_time'			 => 'Bkg Payment Expiry Time',
			'bkg_adv_reminder_email_cnt'		 => 'Bkg Adv Reminder Email Cnt',
			'bkg_adv_reminder_sms_cnt'			 => 'Bkg Adv Reminder Sms Cnt',
			'bkg_followup_time'					 => 'followup time',
			'btr_cab_assigned_sent_email_cnt'	 => 'Cab Assigned Sent Email Cnt',
			'btr_nmi_flag'						 => 'Need more supply',
			'btr_nmi_reason'					 => 'NMI reason',
			'btr_escalation_level'				 => 'Escalation Level',
			'btr_escalate_info_all'				 => 'NOTIFY all floor TLs',
			'btr_nmi_flag_var'					 => 'Need more supply',
			'btr_vendor_last_unassigned'		 => 'Vendor last unassigned date',
			'btr_drv_api_sync_error'			 => 'Driver Api Sync Error',
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

		$criteria->compare('btr_id', $this->btr_id);
		$criteria->compare('btr_bkg_id', $this->btr_bkg_id);
		$criteria->compare('btr_drv_score', $this->btr_drv_score);
		$criteria->compare('btr_active', $this->btr_active);
		$criteria->compare('bkg_platform', $this->bkg_platform);
		$criteria->compare('bkg_platform_ref_id', $this->bkg_platform_ref_id);
		$criteria->compare('bkg_rating', $this->bkg_rating);
		$criteria->compare('bkg_review_email_count', $this->bkg_review_email_count);
		$criteria->compare('bkg_tnc_id', $this->bkg_tnc_id);
		$criteria->compare('bkg_tnc', $this->bkg_tnc);
		$criteria->compare('bkg_tnc_time', $this->bkg_tnc_time, true);
		$criteria->compare('bkg_tags', $this->bkg_tags, true);
		$criteria->compare('bkg_follow_type_id', $this->bkg_follow_type_id);
		$criteria->compare('bkg_followup_date', $this->bkg_followup_date, true);
		$criteria->compare('bkg_followup_comment', $this->bkg_followup_comment, true);
		$criteria->compare('bkg_followup_active', $this->bkg_followup_active);
		$criteria->compare('bkg_upsell_status', $this->bkg_upsell_status);
		$criteria->compare('bkg_escalation_status', $this->bkg_escalation_status);
		$criteria->compare('bkg_assign_csr', $this->bkg_assign_csr);
		$criteria->compare('bkg_is_related_booking', $this->bkg_is_related_booking);
		$criteria->compare('bkg_vendor_request_cnt', $this->bkg_vendor_request_cnt);
		$criteria->compare('bkg_user_ip', $this->bkg_user_ip, true);
		$criteria->compare('bkg_user_device', $this->bkg_user_device, true);
		$criteria->compare('bkg_non_profit_flag', $this->bkg_non_profit_flag);
		$criteria->compare('bkg_non_profit_override_flag', $this->bkg_non_profit_override_flag);
		$criteria->compare('bkg_payment_expiry_time', $this->bkg_payment_expiry_time, true);
		$criteria->compare('bkg_adv_reminder_email_cnt', $this->bkg_adv_reminder_email_cnt);
		$criteria->compare('bkg_adv_reminder_sms_cnt', $this->bkg_adv_reminder_sms_cnt);
		$criteria->compare('bkg_assign_mode', $this->bkg_assign_mode);
		$criteria->compare('bkg_assigned_at', $this->bkg_assigned_at, true);
		$criteria->compare('bkg_auto_assign_ctr', $this->bkg_auto_assign_ctr);
		$criteria->compare('bkg_first_request_sent', $this->bkg_first_request_sent, true);
		$criteria->compare('bkg_confirm_user_type', $this->bkg_confirm_user_type);
		$criteria->compare('bkg_confirm_user_id', $this->bkg_confirm_user_id);
		$criteria->compare('bkg_create_user_type', $this->bkg_create_user_type);
		$criteria->compare('bkg_cancel_user_type', $this->bkg_cancel_user_type);
		$criteria->compare('bkg_cancel_user_id', $this->bkg_cancel_user_id);
		$criteria->compare('btr_cancel_date', $this->btr_cancel_date, true);
		$criteria->compare('btr_assigned_fdate', $this->btr_assigned_fdate, true);
		$criteria->compare('btr_cab_assign_fdate', $this->btr_cab_assign_fdate, true);
		$criteria->compare('btr_cab_assign_ldate', $this->btr_cab_assign_ldate, true);
		$criteria->compare('btr_mark_complete_date', $this->btr_mark_complete_date, true);
		$criteria->compare('btr_cab_assigned_sent_email_cnt', $this->btr_cab_assigned_sent_email_cnt);
		$criteria->compare('btr_auto_assign_date', $this->btr_auto_assign_date, true);
		$criteria->compare('btr_manual_assign_date', $this->btr_manual_assign_date, true);
		$criteria->compare('btr_critical_assign_date', $this->btr_critical_assign_date, true);
		$criteria->compare('btr_vendor_assign_fdate', $this->btr_vendor_assign_fdate, true);
		$criteria->compare('btr_vendor_assign_ldate', $this->btr_vendor_assign_ldate, true);
		$criteria->compare('btr_driver_assign_fdate', $this->btr_driver_assign_fdate, true);
		$criteria->compare('btr_driver_assign_ldate', $this->btr_driver_assign_ldate, true);
		$criteria->compare('btr_escalation_fdate', $this->btr_escalation_fdate, true);
		$criteria->compare('btr_escalation_ldate', $this->btr_escalation_ldate, true);
		$criteria->compare('btr_reconfirm_date', $this->btr_reconfirm_date, true);
		$criteria->compare('btr_payment_receive_fdate', $this->btr_payment_receive_fdate, true);
		$criteria->compare('btr_payment_receive_ldate', $this->btr_payment_receive_ldate, true);
		$criteria->compare('btr_count_vendor_assign', $this->btr_count_vendor_assign);
		$criteria->compare('btr_count_driver_assign', $this->btr_count_driver_assign);
		$criteria->compare('btr_count_cab_assign', $this->btr_count_cab_assign);
		$criteria->compare('btr_count_payment_received', $this->btr_count_payment_received);
		$criteria->compare('btr_count_escalation', $this->btr_count_escalation);

		$criteria->compare('btr_driver_app_use', $this->btr_driver_app_use);
		$criteria->compare('btr_drv_api_sync_error', $this->btr_drv_api_sync_error);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingTrail the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeValidate()
	{
		if ($this->locale_followup_date != '')
		{
			$date					 = DateTimeFormat::DatePickerToDate($this->locale_followup_date);
			$time					 = date('H:i:s', strtotime($this->locale_followup_time));
			$this->bkg_followup_date = $date . ' ' . $time;
		}
		return parent::beforeValidate();
	}

	public function afterFind()
	{
		parent::afterFind();
		if ($this->bkg_followup_date != '')
		{
			$this->locale_followup_date	 = DateTimeFormat::DateTimeToDatePicker($this->bkg_followup_date);
			$this->locale_followup_time	 = DateTimeFormat::DateTimeToTimePicker($this->bkg_followup_date);
		}
	}

	public function validatetnc($attribute, $params)
	{
		if ($this->bkg_tnc == 0)
		{
			$this->addError('bkg_tnc', 'Please check Terms and Conditions before proceed.');
			return FALSE;
		}
	}

	public function setPaymentExpiryTime($date = '')
	{
		if ($date != '')
		{
			$pickDate = $date;
		}
		else
		{
			$pickDate = $this->btrBkg->bkg_pickup_date;
		}
		#$this->bkg_payment_expiry_time = new CDbExpression("GREATEST(DATE_ADD(NOW(), INTERVAL 1 HOUR), DATE_SUB('" . $pickDate . "', INTERVAL 6 HOUR))");

		$paymentExpiryTime				 = BookingTrail::calculatePaymentExpiryTime($this->btrBkg->bkg_create_date, $pickDate);
		$this->bkg_payment_expiry_time	 = $paymentExpiryTime;
	}

	public function updateVendorRequestCounter($bkgid)
	{

		$model							 = BookingTrail::model()->find('btr_bkg_id=:bkg', ['bkg' => $bkgid]);
		$preVal							 = $model->bkg_vendor_request_cnt | 0;
		$model->scenario				 = 'vendor_request_counter';
		$newVal							 = $preVal + 1;
		$model->bkg_vendor_request_cnt	 = $newVal;
		if ($model->bkg_first_request_sent == NULL || $model->bkg_first_request_sent == "")
		{
			$model->bkg_first_request_sent = new CDbExpression('NOW()');
		}
		$model->save();
		return $model;
	}

	public function updateCsr($bkgId, $csr)
	{
		$model					 = self::model()->getbyBkgId($bkgId);
		$model->bkg_assign_csr	 = $csr;
		$model->save();
	}

	public function getbyBkgId($bkgId)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('btr_bkg_id', $bkgId);
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

	public function updateDriverScoreByBkg($bkgId, $eventId)
	{
		$success = false;
		$errors	 = [];
		$model	 = BookingTrail::model()->getbyBkgId($bkgId);

		if ($model == null)
		{
			$model				 = new BookingTrail();
			$model->btr_bkg_id	 = $bkgId;
		}
		switch ($eventId)
		{
			case 93:
				$model->btr_drv_score	 = ($model->btr_drv_score + 20);
				break;
			case 215:
				$model->btr_drv_score	 = ($model->btr_drv_score + 50);
				break;
			case 216:
				$model->btr_drv_score	 = ($model->btr_drv_score + 50);
				break;
		}
		if ($model->validate())
		{
			if ($model->save())
			{
				$success = true;
			}
		}
		else
		{
			$errors = $model->getErrors();
		}
		return ['success' => $success, 'errors' => $errors];
	}

	/**
	 * This function is used for updating the driver score
	 * @param type $bkgId
	 * @param type $eventId
	 * @return type
	 */
	public function updateDriverScore($bkgId, $eventId)
	{
		//Default response
		$arrResponse = array
			(
			"success"	 => false,
			"errors"	 => array()
		);

		if (empty($bkgId) || empty($eventId))
		{
			return $arrResponse;
		}

		$model = BookingTrail::model()->getbyBkgId($bkgId);
		if ($model == null)
		{
			$model				 = new BookingTrail();
			$model->btr_bkg_id	 = $bkgId;
		}

		switch ($eventId)
		{
			case BookingTrack::DRIVER_ARRIVED:
				$model->btr_drv_score	 = ($model->btr_drv_score + 20);
				break;
			case BookingTrack::TRIP_START:
				$model->btr_drv_score	 = ($model->btr_drv_score + 50);
				break;
			case BookingTrack::TRIP_STOP:
				$model->btr_drv_score	 = ($model->btr_drv_score + 50);
				break;
		}


		if ($model->save())
		{
			$arrResponse["success"] = true;
		}
		else
		{
			$arrResponse["errors"] = $model->getErrors();
		}

		return $arrResponse;
	}

	public static function fetchAutoAssignByPickupdate()
	{
		$sql = "SELECT
				SUM(
				   IF(DATE(booking_trail.bkg_assigned_at) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as autoassigned_last_week_count,
				SUM(
				   IF(DATE(booking_trail.bkg_assigned_at) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				)  as autoassigned_wtd_count,
				SUM(
					IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as autoassigned_today_count,
				SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as autoassigned_today1_count,
				SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as autoassigned_today2_count,
				SUM(
					IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as autoassigned_tommrrow_count
				FROM `booking_trail` 
                INNER JOIN `booking` ON booking_trail.btr_bkg_id=booking.bkg_id  AND booking_trail.bkg_assign_mode=1
				WHERE booking.bkg_active=1 
				AND booking.bkg_status IN (3,5,6,7) 
				AND booking.bkg_create_date>'2015-10-01 00:00:00' 
                AND booking_trail.bkg_assigned_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY),' 00:00:00') AND CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 23:59:59')";
		return DBUtil::queryRow($sql);
	}

	public static function fetchManualAssignByPickupdate()
	{
		$sql = "SELECT
				SUM(
				   IF(DATE(booking_trail.bkg_assigned_at) BETWEEN (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY )) AND (DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())-1) DAY )),1,0)
				) as massigned_last_week_count,
				SUM(
				   IF(DATE(booking_trail.bkg_assigned_at) BETWEEN (DATE_SUB(CURDATE(),INTERVAL (dayofweek(CURDATE())-2) DAY )) AND CURDATE(),1,0)
				)  as massigned_wtd_count,
				SUM(
					IF(DATE_FORMAT(CURDATE(),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as massigned_today_count,
				SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as massigned_today1_count,
				SUM(
					IF(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as massigned_today2_count,
				SUM(
					IF(DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY),'%d%m%Y') = DATE_FORMAT(DATE(booking_trail.bkg_assigned_at),'%d%m%Y'),1,0)
				) as massigned_tommrrow_count
				FROM  `booking_trail` 
                INNER JOIN `booking` ON booking_trail.btr_bkg_id=booking.bkg_id  AND booking_trail.bkg_assign_mode=0
				WHERE booking.bkg_active=1 
				AND booking.bkg_status IN (3,5,6,7) 
				AND booking.bkg_create_date>'2015-10-01 00:00:00' 
                AND booking_trail.bkg_assigned_at BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL(dayofweek(CURDATE())+5) DAY),' 00:00:00') AND CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 23:59:59')";
		return DBUtil::queryRow($sql);
	}

	public static function getAssignmentReportHtml()
	{
		$autoData				 = BookingTrail::fetchAutoAssignByPickupdate();
		$manualData				 = BookingTrail::fetchManualAssignByPickupdate();
		$total_last_week_count	 = round($autoData['autoassigned_last_week_count'] + $manualData['massigned_last_week_count']);
		$total_wtd_count		 = round($autoData['autoassigned_wtd_count'] + $manualData['massigned_wtd_count']);
		$total_today2_count		 = round($autoData['autoassigned_today2_count'] + $manualData['massigned_today2_count']);
		$total_today1_count		 = round($autoData['autoassigned_today1_count'] + $manualData['massigned_today1_count']);
		$total_today_count		 = round($autoData['autoassigned_today_count'] + $manualData['massigned_today_count']);
		$total_tommrrow_count	 = round($autoData['autoassigned_tommrrow_count'] + $manualData['massigned_tommrrow_count']);
		$html					 = "<b>Assignment Report : </b>(<i> By pickupdate && status [5,6,7] </i>)
                 <table width='90%' border='1px' style=\"border-collapse: collapse;\" cellpadding='5'>
                    <tr>
                        <th width='20%'></th>
                        <th align='center'>Last Week</th>
                        <th align='center'>Week to Date</th>
                        <th align='center'>Today –2</th>
                        <th align='center'>Today –1</th>
                        <th align='center'>Today</th>
                        <th align='center'>Tomorrow</th>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Pickup Completed (Total)</b></td>
                        <td style='text-align:right'>" . $total_last_week_count . "</td>
                        <td style='text-align:right'>" . $total_wtd_count . "</td>
                        <td style='text-align:right'>" . $total_today2_count . "</td>
                        <td style='text-align:right'>" . $total_today1_count . "</td>
                        <td style='text-align:right'>" . $total_today_count . "</td>
                        <td style='text-align:right'>" . $total_tommrrow_count . "</td>
                    </tr>
					<tr>
                        <td style='text-align:left'><b>Pickup Completed (Auto-assigned)</b></td>
                        <td style='text-align:right'>" . $autoData['autoassigned_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $autoData['autoassigned_wtd_count'] . "</td>
                        <td style='text-align:right'>" . $autoData['autoassigned_today2_count'] . "</td>
                        <td style='text-align:right'>" . $autoData['autoassigned_today1_count'] . "</td>
                        <td style='text-align:right'>" . $autoData['autoassigned_today_count'] . "</td>
						<td style='text-align:right'>" . $autoData['autoassigned_tommrrow_count'] . "</td>
                    </tr>
                    <tr>
                        <td style='text-align:left'><b>Pickup Completed (Manual-assigned)</b></td>
                        <td style='text-align:right'>" . $manualData['massigned_last_week_count'] . "</td>
                        <td style='text-align:right'>" . $manualData['massigned_wtd_count'] . "</td>
                        <td style='text-align:right'>" . $manualData['massigned_today2_count'] . "</td>
                        <td style='text-align:right'>" . $manualData['massigned_today1_count'] . "</td>
                        <td style='text-align:right'>" . $manualData['massigned_today_count'] . "</td>
                        <td style='text-align:right'>" . $manualData['massigned_tommrrow_count'] . "</td>
                    </tr>";
		$html					 .= "</table><br/><br/>";
		return $html;
	}

	public function updateAssignMode()
	{
		$model							 = $this;
		$model->bkg_assign_mode			 = $this->btrBkg->bkgBcb->bcb_assign_mode;
		$model->bkg_assigned_at			 = new CDbExpression('NOW()');
		$model->bkg_auto_assign_ctr		 = $model->bkg_auto_assign_ctr + 1;
		$model->btr_vendor_active_status = $this->btrBkg->bkgBcb->bcbVendor->vnd_active;

		if ($model->btr_assigned_fdate == NULL || $model->btr_assigned_fdate == "")
		{
			$model->btr_assigned_fdate = new CDbExpression('NOW()');
		}
		return $model->save();
	}

	public function updateEscalation($desc, $userInfo, $escalationDesc = "")
	{
		$eventList = BookingLog::eventList();
		if ($this->bkg_escalation_status == '1')
		{
			$eventid					 = BookingLog::BOOKING_ESCALATION_SET;
			$this->bkg_escalation_status = '1';
		}
		else
		{
			$eventid							 = BookingLog::BOOKING_ESCALATION_UNSET;
			$this->bkg_escalation_status		 = '0';
			$this->btr_escalation_level			 = 2;
			$this->btr_escalation_assigned_lead	 = '';
			$this->btr_escalate_info_all		 = 0;
			$this->btr_escalation_assigned_team	 = '';
			$this->btr_escalation_remove_date	 = new CDbExpression('NOW()');
		}
		$this->btr_count_escalation = $this->btr_count_escalation + 1;
		if ($this->btr_escalation_fdate == NULL || $this->btr_escalation_fdate == "")
		{
			$this->btr_escalation_fdate = new CDbExpression('NOW()');
		}
		$this->btr_escalation_ldate	 = new CDbExpression('NOW()');
		$this->scenario				 = 'escalation';
		$this->validateEscalation();
		if ($this->hasErrors())
		{
			$errors = $this->getErrors();
			foreach ($errors as $key => $values)
			{
				$result = $values[0];
			}
		}
		else
		{
			$this->save();
			$remark_escalation	 = $eventList[$eventid] . ': ' . $escalationDesc . $desc;
			BookingLog::model()->createLog($this->btr_bkg_id, $remark_escalation, $userInfo, $eventid);
			$result				 = "Save to escalation ";
			if ($this->bkg_escalation_status == 1)
			{
				self::sendEscalationNotification();
			}
		}
		return $result;
	}

	public function sendEscalationNotification()
	{
		$typeName	 = Booking::model()->booking_platform[$this->bkg_platform];
		$bookingId	 = Booking::model()->getCodeById($this->btr_bkg_id);
		if ($this->bkg_escalation_status == 1)
		{
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData	 = ['bookingId' => $this->btr_bkg_id, 'EventCode' => Booking::CODE_ESCALATION_ON_NOTIFICATION];
			$title			 = "Escalation on - " . $bookingId;
			$message		 = "Escalation activated by $typeName for booking " . $bookingId;
			if ($this->btr_escalate_info_all == 1)
			{
				$csrIds = Admins::model()->getCsrNotificationList();
				foreach ($csrIds as $csrId)
				{
					$csrUserId = $csrId['adm_id'];
					AppTokens::model()->notifyAdmin($csrUserId, $payLoadData, $notificationId, $message, $title);
				}
			}
			if ($this->btr_escalation_assigned_lead != '')
			{
				AppTokens::model()->notifyAdmin($this->btr_escalation_assigned_lead, $payLoadData, $notificationId, $message, $title);
			}
		}
	}

	/* Depricated */

	public function updateUnverifiedFollowup()
	{
		$userInfo = UserInfo::getInstance();
		if ($userInfo->userType == UserInfo::TYPE_ADMIN && in_array($this->btrBkg->bkg_status, [1, 15]))
		{
			if ($this->bkg_assign_csr == $userInfo->userId)
			{
				$this->bkg_assign_csr = 0;
			}
			$this->btr_unv_followup_by	 = $userInfo->userId;
			$this->btr_unv_followup_time = new CDbExpression('NOW()');
			$this->save();
		}
	}

	public function setFollowup($desc, BookingTrail $oldModel, UserInfo $userInfo)
	{
		$success = false;
		$success = BookingLog::model()->createLog($this->btr_bkg_id, $desc, $userInfo, BookingLog::REMARKS_ADDED, false);
		return $success;
	}

	public function checkCabAssignmentEmailSendEligibility($model, $manualSendFlg = 0)
	{
		$bkgPickupDate			 = $model->bkg_pickup_date;
		$cabAssignedSentEmailCnt = $model->bkgTrail->btr_cab_assigned_sent_email_cnt;

		$now		 = date("Y-m-d H:i:s");
		$currDate	 = strtotime($now);
		$pickupDate	 = strtotime($bkgPickupDate);
		$diffMins	 = round(($pickupDate - $currDate) / 60, 0);

		if ($manualSendFlg == 1 || $cabAssignedSentEmailCnt == 0 || ($cabAssignedSentEmailCnt < 2 && $diffMins > 0 && $diffMins <= 240))
		{
			return true;
		}

		return false;
	}

	public function getPlatform($platform)
	{
		$allPlatform = BookingTrail::model()->booking_platform;
		$platform	 = $allPlatform[$platform];
		return $platform;
	}

	public static function fetchBookingCancelCompletedToday1()
	{
		$sql = "SELECT
				booking.bkg_id,
				booking.bkg_agent_id,
				booking.bkg_status,
				booking.bkg_trip_duration,
				booking.bkg_pickup_date,
				booking.bkg_create_date,
				DATE_ADD(
					booking.bkg_pickup_date,
					INTERVAL booking.bkg_trip_duration MINUTE
				) AS bkg_completion_dt,
				btk.btk_last_event
				FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1 
				INNER JOIN booking_track btk ON btk.btk_bkg_id = booking.bkg_id
				JOIN `booking_pref` ON booking_pref.bpr_bkg_id = booking.bkg_id 
				WHERE booking.bkg_status IN(5,6,7,9,10)  AND btk.btk_last_event != ''
				AND DATE(
						DATE_ADD(
							booking.bkg_pickup_date,
							INTERVAL booking.bkg_trip_duration MINUTE
						)
					) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))
				GROUP BY booking.bkg_id";
		return DBUtil::queryAll($sql);
	}

	/**
	 * 
	 * @param ineger $bkg_id
	 * @param integer $type  [ manual => 1 / critical => 2 / auto => 3 ]
	 * @return type
	 */
	public static function setAssignmentTime($bkg_id, $type = 'manual')
	{
		$sql = "SELECT
					MIN(booking_log.blg_created) as min_time
				FROM
					`booking_log`
				WHERE
					booking_log.blg_active = 1
					AND booking_log.blg_booking_id = '$bkg_id'";
		switch ($type)
		{
			case 'manual':
				$sql .= " AND booking_log.blg_event_id = 50";
				break;
			case 'critical':
				$sql .= " AND booking_log.blg_event_id = 123";
				break;
			case 'auto':
				$sql .= " AND booking_log.blg_event_id = 114";
				break;
		}
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * 
	 * @param integer $bkg_id
	 * @return array
	 */
	public static function setEscalationTime($bkg_id)
	{
		$sql = "SELECT
					MIN(booking_log.blg_created) AS fast_escalation_time,
					MAX(booking_log.blg_created) AS last_escalation_time,
					COUNT(1) as count_escalation,
					booking_log.blg_booking_id
				FROM
					`booking_log`
				WHERE
					booking_log.blg_active = 1 
					AND booking_log.blg_event_id IN (37,38)
					AND booking_log.blg_booking_id = '$bkg_id'";
		$row = DBUtil::queryRow($sql);
		return ['first_escalation_time'	 => $row['first_escalation_time'],
			'last_escalation_time'	 => $row['last_escalation_time'],
			'count_escalation'		 => $row['count_escalation']];
	}

	/**
	 * 
	 * @param type $bkg_id
	 * @return String
	 */
	public static function setCancellationTime($bkg_id)
	{
		$sql = "SELECT
					MAX(booking_log.blg_created)
				FROM
					`booking`
				INNER JOIN `booking_log` ON booking_log.blg_booking_id = booking.bkg_id 
				WHERE booking_log.blg_active = 1 
					AND booking_log.blg_event_id = 10 
					AND booking.bkg_active = 1 
					AND booking.bkg_status=9 
					AND booking_log.blg_booking_id='$bkg_id'";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * 
	 * @param type $bkg_id
	 * @return type
	 */
	public static function setReconfirmTime($bkg_id)
	{
		$sql = "SELECT
					MAX(booking_log.blg_created)
				FROM
					`booking_log`
				INNER JOIN `booking` ON booking.bkg_id = booking_log.blg_booking_id
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
				WHERE
					booking_log.blg_active=1 
					AND booking_log.blg_event_id = 74 
					AND booking_log.blg_booking_id='$bkg_id'";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 *
	 * @param integer $bkg_id
	 * @return array
	 */
	public static function setVendorAssignmentDetails($bkg_id)
	{
		$sql = "SELECT MIN(booking_log.blg_created) as first_vendor_assign,
                    MAX(booking_log.blg_created)  as last_vendor_assign,
					COUNT(1) as count_vendor_assign,
                    booking_log.blg_booking_id
                    FROM `booking_log`
                    WHERE booking_log.blg_booking_id='$bkg_id'
					AND booking_log.blg_active=1
					AND booking_log.blg_event_id=7";
		$row = DBUtil::queryRow($sql);
		return ['first_vendor_assign'	 => $row['first_vendor_assign'],
			'last_vendor_assign'	 => $row['last_vendor_assign'],
			'count_vendor_assign'	 => $row['count_vendor_assign']];
	}

	/**
	 *
	 * @param integer $bkg_id
	 * @return array
	 */
	public static function setDriverAssignmentDetails($bkg_id)
	{
		$sql = "SELECT
					MIN(booking_log.blg_created) AS first_driver_assign,
					MAX(booking_log.blg_created) AS last_driver_assign,
					COUNT(1) as count_driver_assign,
					booking_log.blg_booking_id
				FROM  `booking_log`
				WHERE booking_log.blg_active = 1
				AND booking_log.blg_event_id = 44
				AND booking_log.blg_booking_id = '$bkg_id'";
		$row = DBUtil::queryRow($sql);
		return ['first_driver_assign'	 => $row['first_driver_assign'],
			'last_driver_assign'	 => $row['last_driver_assign'],
			'count_driver_assign'	 => $row['count_driver_assign']];
	}

	/**
	 *
	 * @param integer $bkg_id
	 * @return array
	 */
	public static function setCabAssignmentDetails($bkg_id)
	{
		$sql = "SELECT
					MIN(booking_log.blg_created) AS first_cab_assign,
					MAX(booking_log.blg_created) AS last_cab_assign,
					COUNT(1) as count_cab_assign,
				booking_log.blg_booking_id
				FROM  `booking_log`
				WHERE booking_log.blg_booking_id = '$bkg_id'
				AND booking_log.blg_active = 1
				AND booking_log.blg_event_id = 46";
		$row = DBUtil::queryRow($sql);
		return ['first_cab_assign'	 => $row['first_cab_assign'],
			'last_cab_assign'	 => $row['last_cab_assign'],
			'count_cab_assign'	 => $row['count_cab_assign']];
	}

	/**
	 * 
	 * @param type $bkg_id
	 * @return array
	 */
	public static function setTripCompleteTime($bkg_id)
	{
		$sql = "SELECT bkg_id , MIN(logVendor.blg_created) as trip_complete_vendor  , MIN(logDriver.blg_created) as trip_complete_driver
				FROM `booking` 
				INNER JOIN `booking_track` ON booking_track.btk_bkg_id=booking.bkg_id AND booking.bkg_active=1 
				JOIN `booking_log` as logVendor ON logVendor.blg_booking_id=booking.bkg_id AND logVendor.blg_active=1 AND logVendor.blg_event_id=216 AND logVendor.blg_user_type=2  
				LEFT JOIN `booking_log` as logDriver ON logDriver.blg_booking_id=booking.bkg_id AND logDriver.blg_active=1 AND logDriver.blg_event_id=216 AND logDriver.blg_user_type=3
				WHERE booking.bkg_id='$bkg_id'";
		$row = DBUtil::queryRow($sql);
		return ['trip_complete_vendor'	 => $row['trip_complete_vendor'],
			'trip_complete_driver'	 => $row['trip_complete_driver']];
	}

	/**
	 * 
	 * @param integer $bkg_id
	 * @return string
	 */
	public static function setOTPMatchedVerifiedVendor($bkg_id)
	{
		$sql = "SELECT
					MAX(booking_log.blg_created) AS created
				FROM `booking`
				INNER JOIN `booking_track` ON booking_track.btk_bkg_id = booking.bkg_id AND booking.bkg_active = 1
				INNER JOIN `booking_log` ON booking.bkg_id = booking_log.blg_booking_id AND booking_log.blg_active = 1 AND booking_log.blg_event_id = 92 AND booking_log.blg_user_type = 2
				WHERE booking_track.bkg_is_trip_verified = 1 
				AND booking_track.bkg_trip_otp > 0
				AND booking_log.blg_desc LIKE '%OTP verified%' 
				AND booking.bkg_id = '$bkg_id'";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * 
	 * @param integer $bkg_id
	 * @return integer
	 */
	public static function setTripStatTime($bkg_id)
	{
		$sql = "SELECT MAX(booking_log.blg_created) 
				FROM `booking` 
				INNER JOIN `booking_track` ON booking_track.btk_bkg_id=booking.bkg_id AND booking.bkg_active=1
				INNER JOIN `booking_log` ON booking_log.blg_booking_id=booking.bkg_id AND booking_log.blg_event_id=215 AND booking_log.blg_user_type=3
				WHERE booking_log.blg_booking_id='$bkg_id'";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * 
	 * @param integer $bkg_id
	 * @return array
	 */
	public static function setPaymentCompleteTime($bkg_id)
	{
		$sql = "SELECT
					MAX(booking_log.blg_created) AS payment_received_ltime,
					MIN(booking_log.blg_created) AS payment_received_ftime,
					COUNT(1) as cnt_payment_received
				FROM
					`booking`
				INNER JOIN `booking_log` ON booking_log.blg_booking_id = booking.bkg_id AND booking_log.blg_event_id = 55 AND booking.bkg_active = 1
				WHERE booking.bkg_status IN(6, 7) AND booking.bkg_id = '$bkg_id'";
		$row = DBUtil::queryRow($sql);
		return ['payment_received_ftime' => $row['payment_received_ftime'],
			'payment_received_ltime' => $row['payment_received_ltime'],
			'cnt_payment_received'	 => $row['cnt_payment_received']];
	}

	/**
	 * 
	 * @param string $pickupDate
	 * @param integer $duration
	 * @return string
	 */
	public static function setCompletionDate($pickupDate, $duration)
	{
		$date	 = date($pickupDate);
		$newdate = strtotime('+' . $duration . ' minute', strtotime($date));
		$newdate = date('Y-m-d H:i:s', $newdate);
		return $newdate;
	}

	/**
	 * 
	 * @param integer $bkg_id
	 * @param string $bkg_completion_dt
	 * @param string $bkg_review_customer_dt
	 * @param integer $sendBy 0 => system | 1 => service
	 * @return boolean
	 */
	public function updateAttr($bkg_id, $bkg_completion_dt = '', $bkg_review_customer_dt = '', $sendBy = '0')
	{
		$success = false;
		$diff[]	 = '';
		$model	 = $this->getbyBkgId($bkg_id);
		if (!$model)
		{
			$model = new BookingTrail();
		}
		$model->btr_bkg_id = $bkg_id;
		switch ($sendBy)
		{
			case 0;
				$vendorData = BookingTrail::setVendorAssignmentDetails($bkg_id);
				if ($vendorData['first_vendor_assign'] != '' || $vendorData['last_vendor_assign'] != '')
				{
					$model->btr_vendor_assign_fdate	 = $vendorData['first_vendor_assign'];
					$model->btr_vendor_assign_ldate	 = ($vendorData['first_vendor_assign'] != $vendorData['last_vendor_assign']) ? $vendorData['last_vendor_assign'] : $vendorData['first_vendor_assign'];
					$model->btr_count_vendor_assign	 = $vendorData['count_vendor_assign'];
				}
				$driverData = BookingTrail::setDriverAssignmentDetails($bkg_id);
				if ($driverData['first_driver_assign'] != '' || $driverData['last_driver_assign'] != '')
				{
					$model->btr_driver_assign_fdate	 = $driverData['first_driver_assign'];
					$model->btr_driver_assign_ldate	 = ($driverData['first_driver_assign'] != $driverData['last_driver_assign']) ? $driverData['last_driver_assign'] : $driverData['first_driver_assign'];
					$model->btr_count_driver_assign	 = $driverData['count_driver_assign'];
				}
				$cabData = BookingTrail::setCabAssignmentDetails($bkg_id);
				if ($cabData['first_cab_assign'] != '' || $cabData['last_cab_assign'] != '')
				{
					$model->btr_cab_assign_fdate = $cabData['first_cab_assign'];
					$model->btr_cab_assign_ldate = ($cabData['first_cab_assign'] != $cabData['last_cab_assign']) ? $cabData['last_cab_assign'] : $cabData['first_cab_assign'];
					$model->btr_count_cab_assign = $cabData['count_cab_assign'];
				}
				$escalationData = BookingTrail::setEscalationTime($bkg_id);
				if ($escalationData['first_escalation_time'] != '' || $escalationData['last_escalation_time'] != '')
				{
					$model->btr_escalation_fdate = $escalationData['first_escalation_time'];
					$model->btr_escalation_ldate = ($escalationData['first_escalation_time'] != $escalationData['last_escalation_time']) ? $escalationData['last_escalation_time'] : $escalationData['first_escalation_time'];
					//$model->btr_count_escalation = $escalationData['count_escalation'];
				}
				/*
				  $compData = BookingTrail::setTripCompleteTime($bkg_id);
				  if ($compData['trip_complete_vendor'] != '' || $compData['trip_complete_driver'] != '')
				  {
				  $model->btr_trip_complete_vendor_date	 = $compData['trip_complete_vendor'];
				  $model->btr_trip_complete_driver_date	 = $compData['trip_complete_driver'];
				  } */
				$model->btr_manual_assign_date = BookingTrail::setAssignmentTime($bkg_id, 'manual');
				if ($model->btr_manual_assign_date != '')
				{
					$diff['btr_manual_assign_date'] = $model->btr_manual_assign_date;
				}
				$model->btr_critical_assign_date = BookingTrail::setAssignmentTime($bkg_id, 'critical');
				if ($model->btr_critical_assign_date != '')
				{
					$diff['btr_critical_assign_date'] = $model->btr_critical_assign_date;
				}
				$model->btr_auto_assign_date = BookingTrail::setAssignmentTime($bkg_id, 'auto');
				$model->btr_cancel_date		 = BookingTrail::setCancellationTime($bkg_id);
				$model->btr_reconfirm_date	 = BookingTrail::setReconfirmTime($bkg_id);
				//$model->btr_otp_matched_vendor_date  = BookingTrail::setOTPMatchedVerifiedVendor($bkg_id); 
				//$model->btr_trip_started_driver_date = BookingTrail::setTripStatTime($bkg_id);
				$model->btr_driver_app_use	 = Ratings::checkDriverAppUseBooking($bkg_id);
				if (isset($model->btrBkg->ratings[0]->rtg_customer_date) && $model->btrBkg->ratings[0]->rtg_customer_date != '')
				{
					//$model->btr_review_customer_date = $model->btrBkg->ratings[0]->rtg_customer_date;
				}
				if (isset($bkg_completion_dt) && $bkg_completion_dt != '')
				{
					$model->btr_estimate_complete_date = $bkg_completion_dt;
				}
				$paymentData = BookingTrail::setPaymentCompleteTime($bkg_id);
				if ($paymentData['payment_received_ftime'] != '' || $paymentData['payment_received_ltime'] != '')
				{
					$model->btr_payment_receive_fdate	 = $paymentData['payment_received_ftime'];
					$model->btr_payment_receive_ldate	 = ($paymentData['payment_received_ftime'] != $paymentData['payment_received_ltime']) ? $paymentData['payment_received_ltime'] : NULL;
					$model->btr_count_payment_received	 = $paymentData['cnt_payment_received'];
				}
				break;
			case 1:
				//$model->btr_review_customer_date = $bkg_review_customer_dt;
				break;
		}
		if ($model->validate() && $model->save())
		{
			$var	 = "Booking Id : " . $model->btr_bkg_id . " updated.\n";
			Logger::create($var, CLogger::LEVEL_INFO);
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param boolean $sendEmail
	 * @param boolean $sendSMS
	 * @return boolean
	 * @throws Exception
	 */
	public static function unverifiedFollowup($bkgId, $sendEmail = false, $sendSMS = false)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ctr = Booking::sendQuoteExpiryReminderToCustomer($bkgId);

//			if ($sendEmail)
//			{
//				$emailCom	 = new emailWrapper();
//				$return		 = $emailCom->unverifiedFollowupMail($bkgId);
//				if ($return)
//				{
//					$ctr = 1;
//				}
//			}
//
//			$response = WhatsappLog::sendQuoteExpiryReminderToCustomer($bkgId);
//			if ($sendSMS && (!$response || $response['status'] != 2))
//			{
//				$ext	 = 91;
//				$return	 = smsWrapper::unverifiedFollowup($ext, $bkgId);
//				if ($return)
//				{
//					$ctr = 1;
//				}
//			}
//			elseif ($response && $response['status'] == 2)
//			{
//				$ctr = 1;
//			}

			$model = BookingTrail::model()->getbyBkgId($bkgId);
			if (!$model)
			{
				$model				 = new BookingTrail();
				$model->btr_bkg_id	 = $bkgId;
			}

			$model->btr_last_cron_unv_followup	 = new CDbExpression("NOW()");
			$model->btr_cron_unv_followup_ctr	 += $ctr;

			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$success = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param boolean $sendEmail
	 * @param boolean $sendSMS
	 * @return boolean
	 * @throws Exception
	 */
	public static function finalFollowup($bkgId, $sendEmail = false, $sendSMS = false)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ctr = 0;
			if ($sendEmail)
			{
				$emailCom	 = new emailWrapper();
				$return		 = $emailCom->automatedFollowupMail($bkgId);
				if ($return)
				{
					$ctr = 1;
				}
			}

			if ($sendSMS)
			{
				$ctr	 = 1;
				$ext	 = 91;
				$msgCom	 = new smsWrapper();
				$return	 = $msgCom->automatedFollowup($ext, $bkgId);
				if ($return)
				{
					$ctr = 1;
				}
			}
			$model = BookingTrail::model()->getbyBkgId($bkgId);
			if (!$model)
			{
				$model				 = new BookingTrail();
				$model->btr_bkg_id	 = $bkgId;
			}

			$model->btr_last_cron_final_followup = new CDbExpression("NOW()");
			$model->btr_cron_final_followup_ctr	 += $ctr;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$success = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param array $userInfo
	 * @return array
	 * @throws Exception
	 */
	public static function updateUnverifiedCountTime($bkgId, $userInfo)
	{
		$success		 = false;
		$linkOpenCount	 = 0;
		try
		{
			$model = BookingTrail::model()->getbyBkgId($bkgId);
			if (!$model)
			{
				$model				 = new BookingTrail();
				$model->btr_bkg_id	 = $bkgId;
			}
			$model->btr_unv_followup_link_open_cnt += 1;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$linkOpenCount = $model->btr_unv_followup_link_open_cnt;
			if ($linkOpenCount == 1)
			{
				$model->btr_unv_followup_link_open_first_time = new CDbExpression("NOW()");
				if (!$model->save())
				{
					throw new Exception(json_encode($model->getErrors()), 2);
				}
			}
			BookingLog::model()->createLog($model->btr_bkg_id, "Automated unverified followup link opened by user", $userInfo, BookingLog::AUTOMATED_FOLLOWUP_OPEN, false, false);
			$success = true;
			$result	 = ['success' => $success, 'linkOpenCount' => $linkOpenCount];
		}
		catch (Exception $ex)
		{
			$errors		 = $ex->getMessage();
			$errorCode	 = $ex->getCode();
			$result		 = ['success'		 => $success,
				'linkOpenCount'	 => $linkOpenCount,
				'errors'		 => $errors,
				'code'			 => $errorCode];
		}
		return $result;
	}

	/**
	 * 
	 * @param UserInfo $userInfo
	 * @param int $bkgId (Optional if Model loaded)
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateConfirmType(UserInfo $userInfo, $bkg_id = null)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$returnSet	 = new ReturnSet();
		/* @var $setModel BookingTrail */
		$model		 = $this;
		if ($bkg_id > 0)
		{
			$model = BookingTrail::model()->getbyBkgId($bkg_id);
		}

		$confirmId	 = $model->btrBkg->bkgUserInfo->bkg_user_id;
		Logger::info("Booking user id" . $confirmId);
		$confirmType = $model->getConfirmType();
		Logger::info("booking confirm type" . $confirmType);
		Logger::info("booking user type" . $userInfo->userType);

		$model->bkg_confirm_type		 = $confirmType;
		$model->bkg_confirm_user_type	 = $userInfo->userType;
		$model->bkg_confirm_user_id		 = $userInfo->userType == 1 ? $confirmId : $userInfo->userId;
		$model->bkg_confirm_datetime	 = new CDbExpression('NOW()');
		$model->scenario				 = 'confirmBooking';
		if (!$model->save())
		{
			throw new Exception(json_encode($model->getErrors()), 1);
			Logger::info("booking error" . json_encode($model->getErrors()));
		}
		Logger::info("booking confirm successfully");
		$returnSet->setStatus(true);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param array $userInfo
	 * @return array
	 * @throws Exception
	 */
	public static function updateFinalFollowupCountTime($bkgId, $userInfo)
	{
		$success		 = false;
		$linkOpenCount	 = 0;
		try
		{
			$model = BookingTrail::model()->getbyBkgId($bkgId);
			if (!$model)
			{
				$model				 = new BookingTrail();
				$model->btr_bkg_id	 = $bkgId;
			}
			$model->btr_final_followup_link_open_cnt += 1;
			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$linkOpenCount = $model->btr_final_followup_link_open_cnt;
			if ($linkOpenCount == 1)
			{
				$model->btr_final_followup_link_open_first_time = new CDbExpression("NOW()");
				if (!$model->save())
				{
					throw new Exception(json_encode($model->getErrors()), 2);
				}
			}
			BookingLog::model()->createLog($model->btr_bkg_id, "Automated final unverified followup link opened by user", $userInfo, BookingLog::AUTOMATED_FOLLOWUP_OPEN, false, false);
			$success = true;
			$result	 = ['success' => $success, 'linkOpenCount' => $linkOpenCount];
		}
		catch (Exception $ex)
		{
			$errors		 = $ex->getMessage();
			$errorCode	 = $ex->getCode();
			$result		 = ['success' => $success, 'linkOpenCount' => $linkOpenCount, 'errors' => $errors, 'code' => $errorCode];
		}
		return $result;
	}

	public function getConfirmType()
	{
		$bkgStatus	 = $this->btrBkg->bkg_status;
		$createType	 = $this->bkg_create_type;
		$followUp	 = $this->getFollowup();
		if ($createType == BookingTrail::CreateType_Quoted)
		{
			$confirmType = BookingTrail::ConfirmType_UnverifiedQuote;
			if ($bkgStatus == 15)
			{
				$confirmType = BookingTrail::ConfirmType_Quote;
			}
		}
		else if ($createType == BookingTrail::CreateType_Self)
		{
			$confirmType = BookingTrail::ConfirmType_Self;
			if ($followUp)
			{
				$confirmType = BookingTrail::ConfirmType_Unverified;
			}
		}
		else if ($createType == BookingTrail::CreateType_Lead)
		{
			$confirmType = BookingTrail::ConfirmType_Lead;
		}
		return $confirmType;
	}

	public function getFollowup()
	{
		$sql = "SELECT blg_id FROM booking_log WHERE blg_booking_id={$this->btr_bkg_id} AND blg_event_id IN (86, 87, 88) ORDER BY blg_id DESC LIMIT 1";
		$res = DBUtil::command($sql)->queryScalar();
		return ($res > 0);
	}

	public function unapprovedCabDriverUsed($date1 = '', $date2 = '', $usedTime = '', $entityType = '', $type = '')
	{
		if ($date1 != '' && $date2 != '' && $date1 != '1970-01-01' && $date2 != '1970-01-01')
		{
			$condition .= " AND bcb_start_time BETWEEN '$date1 00:00:00' AND '$date2 23:59:59'";
		}
		if ($usedTime == 1 && $entityType != 1)
		{
			$condition .= " AND vhc_total_trips = 1";
		}
		if ($usedTime == 2 && $entityType != 1)
		{
			$condition .= " AND vhc_total_trips > 1";
		}
		if ($usedTime == 1 && $entityType == 1)
		{
			$condition .= " AND drv_total_trips = 1";
		}
		if ($usedTime == 2 && $entityType == 1)
		{
			$condition .= " AND drv_total_trips > 1";
		}
		if ($entityType != '' && $entityType == 1)
		{
			$sql = "SELECT 
				bcb_driver_id as entity_id,
				vnd_name,
				MIN(bcb_start_time) as first_time_used,
				IF(drv_total_trips > 1,MAX(bcb_start_time),'NA') as last_time_used,drv_total_trips as total_trips,
				COUNT(1) as cnt,
                IF(drv_approved = 1,'Approved',IF(drv_approved = 0,'Unapproved', IF(drv_approved = 2,'Pending',IF(drv_approved = 3, 'Rejected',IF(drv_approved = 4, 'Approved but Paper Expired',' '))))) as current_status
                FROM booking_trail
				INNER JOIN booking ON booking.bkg_id=booking_trail.btr_bkg_id AND booking.bkg_active=1  
                INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 
				INNER JOIN drivers ON drv_id = bcb_driver_id AND drv_active = 1 and drv_id=drv_ref_code
				INNER JOIN vendors ON vnd_id = booking_cab.bcb_vendor_id AND vnd_active = 1 and vnd_id = vnd_ref_code
				WHERE	booking.bkg_status IN(6,7) AND drv_total_trips IS NOT NULL AND booking.bkg_create_date > '2015-10-15 00:00:00' $condition
                AND booking_trail.btr_driver_approved_status <> 1	GROUP BY bcb_driver_id";

			$sqlCount = "SELECT 
				COUNT(*) as cnt
                FROM booking_trail
				INNER JOIN booking ON booking.bkg_id=booking_trail.btr_bkg_id AND booking.bkg_active=1  
                INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 
				INNER JOIN drivers ON drv_id = bcb_driver_id AND drv_active = 1 and drv_id=drv_ref_code
				INNER JOIN vendors ON vnd_id = booking_cab.bcb_vendor_id AND vnd_active = 1 and vnd_id = vnd_ref_code
				WHERE	booking.bkg_status IN(6,7) AND drv_total_trips IS NOT NULL AND booking.bkg_create_date > '2015-10-15 00:00:00' $condition
                AND booking_trail.btr_driver_approved_status <> 1	GROUP BY bcb_driver_id";
		}
		else
		{
			$sql = "SELECT 
						bcb_cab_id as entity_id,
						vnd_name,
						GROUP_CONCAT(booking.bkg_id SEPARATOR ',') as bkgs,
						MIN(bcb_start_time) as first_time_used,
						IF(vhc_total_trips > 1,MAX(bcb_start_time),'NA') as last_time_used,
						vhc_total_trips as total_trips,
						COUNT(1) as cnt,
						btr_vehicle_approved_status,
						IF(vhc_approved = 1,'Approved',IF(vhc_approved = 0,'Unapproved', IF(vhc_approved = 2,'Pending',IF(vhc_approved = 3, 'Rejected',IF(vhc_approved = 4, 'Approved but Paper Expired',' '))))) as current_status
						FROM booking_trail
						INNER JOIN booking ON booking.bkg_id=booking_trail.btr_bkg_id AND booking.bkg_active=1  
						INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 
						INNER JOIN vehicles ON vehicles.vhc_id=booking_cab.bcb_cab_id AND vhc_active = 1 
						INNER JOIN vendors ON vnd_id = booking_cab.bcb_vendor_id AND vnd_active = 1 and vnd_id = vnd_ref_code
						WHERE booking.bkg_status IN(6,7) AND vehicles.vhc_total_trips IS NOT NULL AND booking.bkg_create_date > '2015-10-15 00:00:00' $condition
						AND booking_trail.btr_vehicle_approved_status <> 1 GROUP BY bcb_cab_id  ";

			$sqlCount = "SELECT 
						COUNT(*) as cnt
						FROM booking_trail
						INNER JOIN booking ON booking.bkg_id=booking_trail.btr_bkg_id AND booking.bkg_active=1  
						INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 
						INNER JOIN vehicles ON vehicles.vhc_id=booking_cab.bcb_cab_id AND vhc_active = 1 
						INNER JOIN vendors ON vnd_id = booking_cab.bcb_vendor_id AND vnd_active = 1 and vnd_id = vnd_ref_code
						WHERE	booking.bkg_status IN(6,7) AND vehicles.vhc_total_trips IS NOT NULL AND booking.bkg_create_date > '2015-10-15 00:00:00' $condition
						AND booking_trail.btr_vehicle_approved_status <> 1 GROUP BY bcb_cab_id  ";
		}
		if ($type == 'command')
		{
			$recordSet = DBUtil::query($sql, DBUtil::SDB());
			return $recordSet;
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'pagination'	 => ['pageSize' => 50],
				'sort'			 => ['attributes'	 => ['entity_id', 'vnd_name', 'total_trips', 'first_time_used', 'last_time_used'],
					'defaultOrder'	 => 'bcb_id DESC'],
			]);
			return $dataprovider;
		}
	}

	public function getTopFollowup($csr = 0)
	{
		$sql = "SELECT bkg_id,
					CASE 
						WHEN bt.bkg_assign_csr=$csr THEN 50
						WHEN bt.btr_unv_followup_by=$csr THEN 10
						ELSE 0
					END
					as csrRank,
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 15 AND 30 THEN 70
						WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 30 AND 60 THEN 60
						WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 60 AND 120 THEN 45
						WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 120 AND 720 THEN 35
						WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 720 AND 1440 THEN 15
						WHEN TIMESTAMPDIFF(MINUTE, bkg_create_date, NOW()) BETWEEN 1440 AND 2880 THEN -10
						ELSE -20
					END AS timeRank,
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 300 AND 1440 THEN 50
						WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 1440 AND 2880 THEN 45
						WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 2880 AND 5760 THEN 40
						WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 5760 AND 8640 THEN 30
						WHEN TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) BETWEEN 8640 AND 11520 THEN 20
						ELSE 0
					END AS pickupRank, 
					CASE
						WHEN bi.bkg_gozo_amount > 2000 THEN (10)
						WHEN bi.bkg_gozo_amount BETWEEN 1000 AND 2000 THEN (5)
						WHEN bi.bkg_gozo_amount BETWEEN 500 AND 1000 THEN 3
						WHEN bi.bkg_gozo_amount BETWEEN 300 AND 500 THEN 1
						ELSE 0
					END AS amountRank,
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, bt.bkg_followup_date, NOW()) BETWEEN 0 AND 60 THEN 20
						WHEN TIMESTAMPDIFF(MINUTE, bt.bkg_followup_date, NOW()) BETWEEN 60 AND 720 THEN 15
						WHEN TIMESTAMPDIFF(MINUTE, bt.bkg_followup_date, NOW()) BETWEEN 720 AND 1440 THEN 10
						WHEN TIMESTAMPDIFF(MINUTE, bt.bkg_followup_date, NOW()) BETWEEN 1440 AND 2880 THEN 5
						WHEN bkg_followup_date IS NULL THEN 10
						ELSE -15
					END AS followup_rank,
					CASE
						WHEN CalcTimePassedRatio(bkg_create_date, bkg_pickup_date) < 0.2 THEN 40
						WHEN CalcTimePassedRatio(bkg_create_date, bkg_pickup_date) BETWEEN 0.2 AND 0.3 THEN 30
						ELSE 0
					END AS quoteRank
				FROM booking 
				INNER JOIN booking_trail bt ON booking.bkg_id= bt.btr_bkg_id 
				INNER JOIN  booking_invoice bi ON bi.biv_bkg_id = bt.btr_bkg_id
				WHERE bkg_status IN (1, 15) AND bkg_agent_id IS NULL AND (bt.bkg_assign_csr = 0 OR bt.bkg_assign_csr IS NULL OR bt.bkg_assign_csr=$csr)
					AND (bt.bkg_followup_date IS NULL OR bt.bkg_followup_date<NOW()) AND booking.bkg_pickup_date>DATE_ADD(NOW(), INTERVAL 3 HOUR)
				ORDER BY (csrRank + timeRank + amountRank + pickupRank + followup_rank + quoteRank) DESC, bkg_pickup_date ASC LIMIT 1";
		$row = DBUtil::queryRow($sql);
		return $row;
	}

	public static function getVendorAutoAssignmentList($bkgid = '')
	{
		$where = '';
		if ($bkgid > 0)
		{
			$where = " AND booking.bkg_id = {$bkgid}";
		}
		$sql	 = "SELECT  bkg_id FROM booking 
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id 
					INNER JOIN booking_pref ON bpr_bkg_id = bkg_id AND bkg_tentative_booking = 0
					LEFT JOIN calendar cln ON  cln.cln_date = date(booking.bkg_pickup_date)
					WHERE  booking.bkg_flexxi_type NOT IN (1,2) $where 
					AND ((CalcWorkingHour(now(),booking.bkg_pickup_date) BETWEEN 0 AND (14*25) OR bkg_critical_score>0.8 ) 
					OR cln.cln_pre_assignment = 1)
					AND booking.bkg_status = 2 AND bkg_active = 1 AND btr.btr_is_bid_started = 0 
					AND bkg_booking_type <> 7 AND booking.bkg_reconfirm_flag = 1 
 ";
		$column	 = DBUtil::command($sql)->queryColumn();
		return $column;
	}

	public static function startVendorAutoAssignment($bkg_id = '')
	{
		try
		{
			$dataArr = BookingTrail::getVendorAutoAssignmentList($bkg_id);
			$desc	 = 'Vendor auto-assignment process started';
			$eventid = BookingLog::VENDOR_AUTO_ASSIGNMENT_START;
			$i		 = 0;
			foreach ($dataArr as $bkgid)
			{
				$model = BookingTrail::model()->getbyBkgId($bkgid);
				if (!$model)
				{
					continue;
				}
				$model->btr_is_bid_started	 = 1;
				$model->btr_bid_start_time	 = new CDbExpression('NOW()');
				$bcbId						 = $model->btrBkg->bkgBcb->bcb_id;

				if ($model->save())
				{
					BookingCab::resetBidStartTime($bcbId);
					$userInfo	 = UserInfo::getInstance();
					$params		 = [];
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, null, $params);
					//MaxAllowableVendorAmount

					$round = 0;
					Booking::updateMaxAllowableVendorAmount($bkgid, $round);

					echo $desc . " for $bkgid <br>/n";
					$i++;
				}
			}
			Logger::info("startVendorAutoAssignment: Total Bid Started: $i");
		}
		catch (Exception $exc)
		{
			Logger::exception($exc);
		}
	}

	public static function setDemSupMisFire()
	{
		try
		{
			$dataArr = BookingVendorRequest::getDemSupMisfireList();
			$eventid = BookingLog::DEMAND_SUPPLY_MISFIRE;
			$desc	 = "Demand Supply Misfired. Needs immediate attention.";
			$i		 = 0;
			foreach ($dataArr as $data)
			{
				$bkgid = $data['bkg_id'];

				$model = BookingTrail::model()->getbyBkgId($bkgid);
				if (!$model)
				{
					echo "Booking   $bkgid not found in trail \n\n";
					continue;
				}
				$model->btr_is_dem_sup_misfire = 1;
				if ($model->save())
				{
					$i++;
					$userInfo	 = UserInfo::getInstance();
					$params		 = [];

					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, null, $params);
				}
				Logger::trace(" for $model->btr_bkg_id <br>\n");
			}
			Logger::info("setDemSupMisFire - Total records processed: $i");
		}
		catch (Exception $exc)
		{
			Logger::exception($exc);
		}
	}

	public static function getCountDemandSupplyMisfire()
	{

		$sql = "SELECT count(DISTINCT bkg.bkg_id) as count
				FROM `booking_trail` btr
				INNER JOIN `booking` bkg ON bkg.bkg_id=btr.btr_bkg_id AND bkg.bkg_status=2				 
				WHERE btr.btr_is_dem_sup_misfire=1 AND bkg.bkg_pickup_date > NOW() AND bkg_reconfirm_flag=1";
		return DBUtil::command($sql, DBUtil::SDB())->queryScalar();
	}

	/**
	 * @param int $groupType {0: None, 1: Assigned Date, 2: Pickup Date} 
	 */
	public static function getAssignmentStats($fromDate, $toDate, $groupType = 1)
	{
		$selectGroup = "";
		$param		 = ['fromDate' => $fromDate, 'toDate' => $toDate];
		if ($groupType == 1)
		{
			$selectGroup = "DATE(bkg_assigned_at) as date, ";
		}
		if ($groupType == 2)
		{
			$selectGroup = "DATE(bkg_pickup_date) as date, ";
		}
		$sql = "SELECT $selectGroup
				SUM(IF((bcb_is_max_out IN (1) AND bkg_assign_mode IN (1)), 1, 0)) AS manual_triggered_assignment,
				SUM(IF((bcb_is_max_out IN (0) AND bkg_assign_mode IN (1)), 1, 0)) AS manual_triggered_assignment_smt_not_used,
				SUM(IF((bkg_is_gozonow IN (1,2)) AND (bkg_assign_mode IN (1)), 1, 0)) AS gn_manual_assigned,

				SUM(IF((bkg_assign_mode IN (1)), 1, 0)) AS total_manual_assigned,
				SUM(IF((bkg_assign_mode IN (1) AND bkg_booking_type NOT IN (4,12)), 1, 0)) AS total_manual_assigned_non_AT,

				SUM(IF((bkg_assign_mode IN (0)), 1, 0)) AS total_auto_assigned,
				SUM(IF((bcb_is_max_out IN (1)) AND (bkg_assign_mode IN (0)), 1, 0)) AS manual_triggered_auto_assigned,
				SUM(IF((bcb_is_max_out IN (0)) AND (bkg_assign_mode IN (0)), 1, 0)) AS non_triggered_auto_assigned,
				SUM(IF((bkg_is_gozonow IN (1,2)) AND (bkg_assign_mode IN (0)), 1, 0)) AS gn_auto_assigned,
				SUM(IF((bkg_assign_mode IN (0) AND bkg_booking_type NOT IN (4,12)), 1, 0)) AS total_auto_assigned_non_AT,

				SUM(IF((bkg_assign_mode IN (2)), 1, 0)) AS total_direct_assigned,
				SUM(IF((bkg_assign_mode IN (2) AND bkg_booking_type NOT IN (4,12)), 1, 0)) AS total_direct_assigned_non_AT,

				SUM(IF((bkg_assign_mode IN (3)), 1, 0)) AS total_gozoNow_assigned,
				SUM(IF((bkg_assign_mode IN (3) AND bkg_booking_type NOT IN (4,12)), 1, 0)) AS total_gozoNow_assigned_non_AT,

				SUM(IF((bkg_assign_mode IN (0,1,2,3)), 1, 0)) AS total_assigned,
				SUM(IF((bkg_assign_mode IN (0,1,2,3) AND bkg_booking_type NOT IN (4,12)), 1, 0)) AS total_assigned_non_AT,
				SUM(IF((bkg_assign_mode IN (0,1,2,3) AND bkg_agent_id = 450), 1, 0)) AS total_assigned_mmt,
				SUM(IF((bkg_assign_mode IN (0,1,2,3) AND bkg_agent_id = 18190), 1, 0)) AS total_assigned_ibibo,
				SUM(IF((bkg_assign_mode IN (0,1,2,3) AND bkg_agent_id IS NULL), 1, 0)) AS total_assigned_b2c,
				SUM(IF((bkg_assign_mode IN (0,1,2,3) AND bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN(450,18190)), 1, 0)) AS total_assigned_b2bothers,
				
				SUM(IF((bkg_assign_mode IN (1) AND bkg_agent_id = 450), 1, 0)) AS total_manual_assigned_mmt,
				SUM(IF((bkg_assign_mode IN (1) AND bkg_agent_id = 18190), 1, 0)) AS total_manual_assigned_ibibo,
				SUM(IF((bkg_assign_mode IN (1) AND bkg_agent_id IS NULL), 1, 0)) AS total_manual_assigned_b2c,
				SUM(IF((bkg_assign_mode IN (1) AND bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN(450,18190)), 1, 0)) AS total_manual_assigned_b2bothers,
				
				SUM(IF((bkg_assign_mode IN (0) AND bkg_agent_id =18190), 1, 0)) AS total_auto_assigned_ibibo,
				SUM(IF((bkg_assign_mode IN (0) AND bkg_agent_id =450), 1, 0)) AS total_auto_assigned_mmt,
				SUM(IF((bkg_assign_mode IN (0) AND bkg_agent_id IS NULL), 1, 0)) AS total_auto_assigned_b2c,
				SUM(IF((bkg_assign_mode IN (0) AND bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN(450,18190)), 1, 0)) AS total_auto_assigned_b2bothers,

				SUM(IF((bkg_assign_mode IN (2) AND bkg_agent_id = 450), 1, 0)) AS total_direct_accept_mmt,
				SUM(IF((bkg_assign_mode IN (2) AND bkg_agent_id = 18190), 1, 0)) AS total_direct_accept_ibibo,
				SUM(IF((bkg_assign_mode IN (2) AND bkg_agent_id IS NULL), 1, 0)) AS total_direct_accept_b2c,
				SUM(IF((bkg_assign_mode IN (2) AND bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN(450,18190)), 1, 0)) AS total_direct_accept_b2bothers,

				SUM(IF((bkg_assign_mode IN (3) AND bkg_agent_id = 450), 1, 0)) AS total_gozoNow_accept_mmt,
				SUM(IF((bkg_assign_mode IN (3) AND bkg_agent_id = 18190), 1, 0)) AS total_gozoNow_accept_ibibo,
				SUM(IF((bkg_assign_mode IN (3) AND bkg_agent_id IS NULL), 1, 0)) AS total_gozoNow_accept_b2c,
				SUM(IF((bkg_assign_mode IN (3) AND bkg_agent_id IS NOT NULL AND bkg_agent_id NOT IN(450,18190)), 1, 0)) AS total_gozoNow_accept_b2bothers,

				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0,1,0)) as autoAssignLossCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as autoAssignLoss,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0,1,0)) as manualAssignLossCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as manualAssignLoss,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 ,1,0)) as directAssignLossCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as directAssignLoss,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 ,1,0)) as gozoNowAssignLossCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as gozoNowAssignLoss,

				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,1,0)) as autoAssignProfitCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as autoAssignProfit,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,1,0)) as manualAssignProfitCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as manualAssignProfit,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,1,0)) as directAssignProfitCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as directAssignProfit,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,1,0)) as gozoNowAssignProfitCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as gozoNowAssignProfit,

				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0,(bkg_gozo_amount- IFNULL(bkg_credits_used,0)),0)) as totalProfit,
				SUM(IF(bkg_reconfirm_flag = 1, (bkg_net_base_amount), 0)) AS totalAmount,

				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id IS NULL ,1,0)) AS autoAssignB2CLossCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id=450 ,1,0)) AS autoAssignB2BMMTLossCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id= 18190 ,1,0)) AS autoAssignB2BIBIBOLossCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS autoAssignB2BOTHERSLossCount,

				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id IS NULL ,1,0)) AS manualB2CLossCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id=450 ,1,0)) AS manualB2BMMTLossCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id= 18190 ,1,0)) AS manualB2BIBIBOLossCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS manualB2BOTHERSLossCount,

				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id IS NULL ,1,0)) AS directB2CLossCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id=450 ,1,0)) AS directB2BMMTLossCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id= 18190 ,1,0)) AS directB2BIBIBOLossCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS directB2BOTHERSLossCount,

				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id IS NULL ,1,0)) AS gozoNowB2CLossCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id=450 ,1,0)) AS gozoNowB2BMMTLossCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id= 18190 ,1,0)) AS gozoNowB2BIBIBOLossCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS gozoNowB2BOTHERSLossCount,

				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id IS NULL ,1,0)) AS allB2CLossCount,
				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id=450 ,1,0)) AS allB2BMMTLossCount,
				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id=18190 ,1,0)) AS allB2BIBIBOLossCount,
				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))<0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS allB2BOTHERSLossCount,

				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id IS NULL ,1,0)) AS autoAssignB2CProfitCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=450 ,1,0)) AS autoAssignB2BMMTProfitCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=18190 ,1,0)) AS autoAssignB2BIBIBOProfitCount,
				SUM(IF(bkg_assign_mode IN (0) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS autoAssignB2BOTHERSProfitCount,

				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id IS NULL ,1,0)) AS manualB2CProfitCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=450,1,0)) AS manualB2BMMTProfitCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=18190 ,1,0)) AS manualB2BIBIBOProfitCount,
				SUM(IF(bkg_assign_mode IN (1) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS manualB2BOTHERSProfitCount,

				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id IS NULL ,1,0)) AS directB2CProfitCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=450 ,1,0)) AS directB2BMMTProfitCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=18190 ,1,0)) AS directB2BIBIBOProfitCount,
				SUM(IF(bkg_assign_mode IN (2) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS directB2BOTHERSProfitCount,

				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id IS NULL ,1,0)) AS gozoNowB2CProfitCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=450 ,1,0)) AS gozoNowB2BMMTProfitCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=18190 ,1,0)) AS gozoNowB2BIBIBOProfitCount,
				SUM(IF(bkg_assign_mode IN (3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS gozoNowB2BOTHERSProfitCount,


				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id IS NULL ,1,0)) AS allB2CProfitCount,
				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=450 ,1,0)) AS allB2BMMTProfitCount,
				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id=18190 ,1,0)) AS allB2BIBIBOProfitCount,
				SUM(IF(bkg_assign_mode IN (0,1,2,3) AND (bkg_gozo_amount- IFNULL(bkg_credits_used,0))>=0 AND bkg_agent_id NOT IN(450,18190),1,0)) AS allB2BOTHERSProfitCount

				FROM `booking`
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id 
				INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
				INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
				INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id 
				WHERE booking.bkg_active = 1 AND booking.bkg_status IN (3,5,6,7) AND booking_trail.bkg_assigned_at BETWEEN :fromDate AND :toDate ";

		$recordset = DBUtil::queryAll($sql, DBUtil::SDB3(), $param);
		return $recordset;
	}

	/*
	 * @deprecated
	 */

//	public function updateDBO($pickupdt, $agentId = NULL)
//	{
//		$sdoSettings = Config::get('dbo.b2c.settings');
//		$bkgID		 = $this->btr_bkg_id;
//		if ($agentId > 0)
//		{
//			$sdoSettings = Config::get('dbo.b2b.mmt.settings');
//			goto skipMmt;
//		}
//
//		$bookingConfirmDT	 = $this->bkg_confirm_datetime;
//		$hrDiff				 = Filter::CalcWorkingHour($bookingConfirmDT, $pickupdt);
//		if (trim($pickupdt) == '' || empty($this->btrBkg->bkg_vehicle_type_id))
//		{
//			return;
//		}
//		skipMmt:
//
//		if (empty($sdoSettings))
//		{
//			return;
//		}
//		$result			 = CJSON::decode($sdoSettings);
//		$currentCabType	 = SvcClassVhcCat::getClassById($this->btrBkg->bkg_vehicle_type_id);
//		$settingsData	 = $result[$currentCabType];
//		if (!empty($settingsData))
//		{
//			$dboEnabled = $settingsData['enabled'];
//			if ($dboEnabled == 1)
//			{
//				$dboStartDate		 = strtotime($settingsData['startDate']);
//				$dboEndDate			 = strtotime($settingsData['endDate']);
//				$dboPickupDateTime	 = strtotime($pickupdt);
//				if ($agentId > 0)
//				{
//					if ($dboEndDate < $dboPickupDateTime || $dboStartDate > $dboPickupDateTime)
//					{
//						return;
//					}
//				}
//				else
//				{
//					if ($hrDiff <= 42 || $dboEndDate < $dboPickupDateTime || $dboStartDate > $dboPickupDateTime)
//					{
//						return;
//					}
//				}
//			}
//			else
//			{
//				return;
//			}
//		}
//		else
//		{
//			return;
//		}
//
//		$bookPrefModel = BookingPref::model()->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $this->btr_bkg_id]);
//		if ($bookPrefModel->bkg_is_fbg_type == 1)
//		{
//			return;
//		}
//		$this->btr_is_dbo_applicable = 1;
//		if ($this->save())
//		{
//			$this->updateDBOamt($bkgID, $agentId);
//		}
//	}

	/*
	 * @deprecated
	 */
//	public function updateDBOamt($bkgID, $agentId = NULL)
//	{
//
//		$sqlInvoice	 = "SELECT bkg_advance_amount,btr_is_dbo_applicable FROM booking_invoice,booking_trail
//                       WHERE booking_invoice.biv_bkg_id = $bkgID AND booking_trail.btr_bkg_id=$bkgID";
//		$res		 = DBUtil::command($sqlInvoice)->queryRow();
//
//		if ($res['btr_is_dbo_applicable'] == 1)
//		{
//			$advanceAmt	 = $res['bkg_advance_amount'];
//			$promiseAmt	 = ($advanceAmt > 5000) ? 5000 : $advanceAmt;
//			if ($agentId == '18190' || $agentId == 450)
//			{
//				$promiseAmt = ($advanceAmt > 2000) ? (2000 + $advanceAmt) : ($advanceAmt * 2);
//			}
//			$dboAmt = $promiseAmt;
//			if ($dboAmt > 0)
//			{
//				$sql	 = "UPDATE  booking_trail SET btr_dbo_amount = $dboAmt WHERE btr_bkg_id = $bkgID";
//				$result	 = DBUtil::command($sql)->execute();
//			}
//		}
//	}

	public function addVendorUnassignPenalty($bkgID, $amount)
	{
		$sql	 = "UPDATE  booking_trail SET btr_vendor_unassign_penalty = btr_vendor_unassign_penalty+$amount WHERE btr_bkg_id = $bkgID";
		$result	 = DBUtil::command($sql)->execute();
	}

	public static function updateProfitFlag($bcb_id)
	{
		BookingInvoice::updateGozoAmount($bcb_id);
		$sql	 = "UPDATE  booking_trail, booking_invoice, booking
					SET bkg_non_profit_flag = IF(bkg_gozo_amount>0,0,1) 
					WHERE bkg_id=btr_bkg_id AND bkg_id=biv_bkg_id AND bkg_bcb_id={$bcb_id}";
		$result	 = DBUtil::command($sql)->execute();
		return $result;
	}

	public function updateNMI($desc, BookingTrail $oldModel, UserInfo $userInfo)
	{
		$this->validate();
		$success = false;
		if (($oldModel->btr_nmi_flag != $this->btr_nmi_flag))
		{

			$eventList = BookingLog::eventList();
			if ($oldModel->btr_nmi_flag == 0)
			{
				InventoryRequest::model()->setData($this->btr_bkg_id);
				$this->btr_nmi_flag	 = 1;
				$eventid			 = BookingLog::NEED_MORE_INVENTORY_FLAG_ON;
			}
			else
			{

				$this->btr_nmi_flag	 = 0;
				$eventid			 = BookingLog::NEED_MORE_INVENTORY_FLAG_OFF;
			}
			$this->btr_nmi_requester_id = $userInfo->getUserId();
			if ($this->save())
			{
				$success	 = true;
				$desc_nmi	 = $eventList[$eventid] . ': ' . $desc;
				BookingLog::model()->createLog($this->btr_bkg_id, $desc_nmi, $userInfo, $eventid, false);
			}
		}
		return $success;
	}

	public function loadDefault(Booking $bkgModel)
	{
		$userInfo = UserInfo::getInstance();

		$partnerSetting = PartnerSettings::getValueById($bkgModel->bkg_agent_id);
		if ($partnerSetting['pts_is_payment_lock'] == 1 && $bkgModel->bkg_agent_id != NULL)
		{
			$this->bkg_payment_expiry_time = new CDbExpression("DATE_SUB(NOW(), INTERVAL 1 HOUR)");
		}
		else
		{
			#$this->bkg_payment_expiry_time = new CDbExpression("GREATEST(DATE_ADD(NOW(), INTERVAL 1 HOUR), DATE_SUB('" . $bkgModel->bkg_pickup_date . "', INTERVAL 6 HOUR))");

			$paymentExpiryTime				 = BookingTrail::calculatePaymentExpiryTime($bkgModel->bkg_create_date, $bkgModel->bkg_pickup_date);
			$this->bkg_payment_expiry_time	 = $paymentExpiryTime;
		}
		$this->bkg_create_type		 = BookingTrail::CreateType_Self;
		$this->bkg_create_user_type	 = $userInfo->userType;
		$this->bkg_create_user_id	 = $userInfo->userId;
	}

	public function addNMIreason($bkgID, $trailArr)
	{
		$userInfo					 = UserInfo::getInstance();
		$model						 = Booking::model()->findByPk($bkgID);
		$model->bkgTrail->scenario	 = 'addnmireason';
		if ($model->bkgTrail->btr_nmi_flag == 0 && $trailArr['btr_nmi_flag'] == 1)
		{
			$model->bkgTrail->btr_nmi_flag = $trailArr['btr_nmi_flag'];
			InventoryRequest::model()->setData($bkgID);
		}
		$model->bkgTrail->btr_nmi_requester_id	 = $userInfo->getUserId();
		$model->bkgTrail->btr_nmi_reason		 = $trailArr['btr_nmi_reason'];
		$result									 = CActiveForm::validate($model->bkgTrail);
		if ($result == '[]')
		{
			$success			 = $model->bkgTrail->save();
			$return['success']	 = true;
			if (!$success)
			{
				$error = $model->bkgTrail->getErrors();
			}
		}
		else
		{
			$return['success']	 = false;
			$error				 = $model->bkgTrail->getErrors();
			$return['error']	 = $error['btr_nmi_reason'][0];
			// $return['error']	 = $error;
		}
		echo CJSON::encode($return);
	}

	public function getEscalatiomLevel($type)
	{
		$arrSelectBox	 = array();
		$arr			 = $this->escalation;
		foreach ($arr as $key => $value)
		{
			$arrSelectBox[$key] = $value[$type];
		}
		return $arrSelectBox;
	}

	public function validateEscalation()
	{
		if ($this->bkg_escalation_status == 1)
		{
			if ($this->btr_escalation_level == 0)
			{
				$this->addError('btr_escalation_level', 'Please choose escalation level.');
				return FALSE;
			}
			if ($this->btr_escalation_assigned_lead == null)
			{
				$this->addError('btr_escalation_assigned_lead', 'Please choose assigned person.');
				return FALSE;
			}
		}
	}

	public static function setTtpValues()
	{
		$sql	 = "SELECT 
				btr_bkg_id,
				bkg_pickup_date,
				btr_cab_assign_fdate,
				btr_cab_assign_ldate,
				btr_driver_assign_fdate,
				btr_driver_assign_ldate
				FROM   booking_trail 
				JOIN booking  ON btr_bkg_id=bkg_id  
				WHERE booking.bkg_status in(3,5) OR  booking.bkg_pickup_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 3 DAY),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $val)
		{
			try
			{
				$params	 = array(
					'btr_bkg_id'				 => (int) $val['btr_bkg_id'],
					'bkg_pickup_date'			 => $val["bkg_pickup_date"],
					'btr_cab_assign_fdate'		 => $val["btr_cab_assign_fdate"],
					'btr_cab_assign_ldate'		 => $val["btr_cab_assign_ldate"],
					'btr_driver_assign_fdate'	 => $val["btr_driver_assign_fdate"],
					'btr_driver_assign_ldate'	 => $val["btr_driver_assign_ldate"]
				);
				$sql	 = "UPDATE booking_trail 					
					SET btr_car_fassign_ttp=IF(:btr_cab_assign_fdate IS NOT NULL, TIMESTAMPDIFF(MINUTE,:btr_cab_assign_fdate,:bkg_pickup_date),NULL),
					btr_car_lassign_ttp=IF(:btr_cab_assign_ldate IS NOT NULL,TIMESTAMPDIFF(MINUTE,:btr_cab_assign_ldate,:bkg_pickup_date),NULL),
					btr_driver_fassign_ttp=IF(:btr_driver_assign_fdate IS NOT NULL,TIMESTAMPDIFF(MINUTE,:btr_driver_assign_fdate,:bkg_pickup_date),NULL),
					btr_driver_lassign_ttp=IF(:btr_driver_assign_ldate IS NOT NULL,TIMESTAMPDIFF(MINUTE,:btr_driver_assign_ldate,:bkg_pickup_date),NULL)  
					WHERE  btr_bkg_id =:btr_bkg_id";
				DBUtil::execute($sql, $params);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function quoteExpiry($id, $quoteExpiryTime)
	{
		$success = false;
		if ($quoteExpiryTime != '')
		{
			$model							 = BookingTrail::model()->findByPk($id);
			$nowDateTime					 = Filter::getDBDateTime();
			$model->bkg_quote_expire_date	 = date('Y-m-d H:i:s', strtotime('+' . $quoteExpiryTime . ' minutes', strtotime($nowDateTime)));
			if ($model->save())
			{
				$success = true;
				return ['success' => $success];
			}
		}
		return $success;
	}

	public static function maxQuoteExpiry($bkgId)
	{
		$success			 = false;
		$model				 = Booking::model()->findByPk($bkgId);
		$createTime			 = $model->bkg_create_date;
		$hourdiff			 = BookingPref::model()->getWorkingHrsCreateToPickupByID($bkgId);
		$timeTwentyPercent	 = round($hourdiff * 0.2);
		$new_time			 = date("Y-m-d H:i:s", strtotime('+' . $timeTwentyPercent . ' hours', strtotime($createTime)));
		return $new_time;
	}

	public function variedQuoteExpiry($bkgId, $quoteExpiryDate)
	{
		$success = false;

		if ($bkgId > 0 && $quoteExpiryDate != '')
		{
			$model							 = BookingTrail::model()->find('btr_bkg_id=:id', ['id' => $bkgId]);
			//$model							 = BookingTrail::model()->findByPk($bkgId);
			$model->bkg_quote_expire_date	 = $quoteExpiryDate;
			if ($model->save())
			{
				$success = true;
			}
			result:
			return ['success' => $success];
		}
	}

	public function checkCancellationMailEligibity($model)
	{
		if ($model->bkg_status != 9 || $model->bkgTrail->bkg_cancellation_email_count < 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function uploadEpass($uploadedFile, $bkg_id, $prefix = 'document')
	{
		$fileName	 = $bkg_id . "-" . $prefix . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirBybkgId = $dir . DIRECTORY_SEPARATOR . $bkg_id;
		if (!is_dir($dirBybkgId))
		{
			mkdir($dirBybkgId);
		}

		$foldertoupload	 = $dirBybkgId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirBybkgId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $bkg_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	/**
	 * 
	 * @param Booking | BookingTrail | int $bkg
	 * @param int $status 0=>Not Updated, 1=>Updated
	 * @return BookingTrail
	 * @throws Exception
	 */
	public static function updateCancelStatus($bkg, $status, $remarks = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		Logger::trace("updateCancelStatus: status" . $status . "bkgId" . $bkg . "remarks" . $remarks);	
		if ($bkg)
		{
			$btrModel = BookingTrail::model()->find('btr_bkg_id=:id', ['id' => $bkg]);
		}
		if (!$btrModel)
		{
			throw new Exception("Invalid Booking", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::trace("updateCancelStatus: btr_auto_cancel_value" . $btrModel->btr_auto_cancel_value . "bkgId" . $bkg . "status" . $status);
		if ($btrModel->btr_auto_cancel_value == 1 && $status == 1)
		{
		Logger::trace("updateCancelStatus: step in 1" . $btrModel->btr_auto_cancel_value . "bkgId" . $bkg . "status" . $status);
			$hour					 = Yii::app()->params['partner']['balanceValidity']['cancelHour'];
			$autoCancelBeforeHour	 = date('Y-m-d H:i:s', strtotime('+' . $hour . ' hour'));
			if ($btrModel->btrBkg->bkg_pickup_date <= $autoCancelBeforeHour)
			{
				$userInfo	 = UserInfo::model();
				$reason		 = "Can not serve this booking as partner credit limit has been exceeded.";
				$reasonId	 = 33;
				Booking::model()->canBooking($btrModel->btrBkg->bkg_id, $reason, $reasonId, $userInfo);
		Logger::trace("updateCancelStatus: step in 2 canBooking" . $btrModel->btr_auto_cancel_value . "bkgId" . $bkg . "status" . $status);		
			}
		}
		if ($btrModel->btr_auto_cancel_value == $status && $btrModel->btr_auto_cancel_reason_id != 33)
		{
		Logger::trace("updateCancelStatus: step in 3 btr_auto_cancel_reason_id" . $btrModel->btr_auto_cancel_reason_id . "bkgId" . $bkg . "status" . $status);		
			goto skipUpdate;
		}

		$btrModel->btr_auto_cancel_value		 = $status;
		$btrModel->btr_auto_cancel_reason_id	 = 33;
		$btrModel->btr_auto_cancel_create_date	 = new CDbExpression('NOW()');
		if (!$btrModel->save())
		{
			throw new Exception(json_encode($btrModel->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		BookingLog::model()->createLog($btrModel->btr_bkg_id, $remarks, UserInfo::model(), 0);
		Logger::trace("updateCancelStatus: step out btr_auto_cancel_reason_id" . $btrModel->btr_auto_cancel_reason_id . "bkgId" . $bkg . "btr_auto_cancel_value" . $status);		
		skipUpdate:
	    Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $btrModel;
	}

	/**
	 * 
	 * @param BookingPayDocs | device | checksum
	 * @return BookingTrail
	 * @throws Exception
	 */
	public function addDiscrepancy($bpayType, $escalationRemark, $tripId, $isEscalate)
	{

		/**
		 * Set data discrepancy flag and remark
		 */
		if (!$this->save())
		{
			throw new Exception($this->hasErrors(), ReturnSet::ERROR_INVALID_DATA);
		}
		/**
		 * Stop vendor payment
		 */
//		$tripmodel = BookingCab::model()->findByPk($tripId);
//		if ($tripmodel->bcb_lock_vendor_payment == 0)
//		{
//			$tripmodel->bcb_lock_vendor_payment = 1;
//			if (!$tripmodel->save())
//			{
//				throw new Exception($tripmodel->hasErrors(), ReturnSet::ERROR_INVALID_DATA);
//			}
//			$lockDesc				 = "Payment Locked (Driver app data descrepencies) ";
//			$params['blg_ref_id']	 = $this->btr_bkg_id;
//			BookingLog::model()->createLog($this->btr_bkg_id, $lockDesc, UserInfo::model(), BookingLog::LOCKED_PAYMENT, false, $params);
//		}
		/* Set escalation */
		if ($isEscalate == 1)
		{
			$trailModel = BookingTrail::model()->findByPk($this->btr_id);
			if ($trailModel->bkg_escalation_status != 1 && $trailModel->btr_escalation_assigned_team != 4)
			{

				$this->bkg_escalation_status		 = 1;
				$this->btr_escalation_level			 = 3;
				$this->btr_escalation_assigned_lead	 = 575;
				$this->btr_escalation_assigned_team	 = 4;
				$msg								 = $this->updateEscalation("Driver app data descrepencies ", UserInfo::model(), $escalationRemark);
			}
		}
		return true;
	}

	public function getDBORefundable()
	{
		$amount						 = 0;
		$applicableDBOCompensation	 = CancelReasons::applicableDBOCompensation($this->btrBkg->bkg_cancel_id);
		if ($applicableDBOCompensation)
		{
			if ($this->btr_is_dbo_applicable == 1 && $this->btr_dbo_amount > 0 && $this->btrBkg->bkgInvoice->bkg_cust_compensation_amount == 0)
			{
				return $this->btr_dbo_amount;
			}
		}

		return $amount;
	}

	public static function unAssignCsr($bkgId)
	{
		$params	 = array('bkgId' => (int) $bkgId);
		$sql	 = "UPDATE booking_trail SET bkg_assign_csr = 0	 WHERE  btr_bkg_id =:bkgId";
		return DBUtil::execute($sql, $params);
	}

	public function saveApiSyncError($bkgId)
	{
		if ($bkgId != '')
		{
			$params	 = array('bkgId' => (int) $bkgId);
			$sql	 = "UPDATE booking_trail btr SET btr.btr_api_sync_error = 1	 WHERE  btr.btr_bkg_id =:bkgId AND btr.btr_api_sync_error = 0";
			return DBUtil::execute($sql, $params);
		}
	}

	public static function getBookingTrackDetails($qry = [], $type = '')
	{
		$where		 = '';
		$whereChk	 = '';
		if ($qry['bkg_pickup_date1'] != '' && $qry['bkg_pickup_date2'] != '')
		{
			$fromDate	 = $qry['bkg_pickup_date1'];
			$toDate		 = $qry['bkg_pickup_date2'];
			$where		 .= " AND DATE(b.bkg_pickup_date) BETWEEN '$fromDate' AND '$toDate' ";
		}
		else
		{
			$where .= " AND b.bkg_pickup_date BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) AND CURRENT_DATE";
		}
		if ($qry['bkg_agent_id'] != '')
		{
			$where .= " AND b.bkg_agent_id =" . $qry['bkg_agent_id'];
		}
		if ($qry['bkg_booking_id'] != '')
		{
			$where .= " AND (b.bkg_booking_id = '{$qry['bkg_booking_id']}' OR b.bkg_agent_ref_code='{$qry['bkg_booking_id']}' OR b.bkg_id = '{$qry['bkg_booking_id']}' )";
		}
		if ($qry['bkg_status'] != '' && $qry['bkg_status'] != '0')
		{
			$where .= " AND b.bkg_status = '" . $qry['bkg_status'] . "'";
		}
		$cnt = 0;
		if (!empty($qry['late_started']) && $qry['late_started'] == 1)
		{
			$whereChk = " (TIMESTAMPDIFF(MINUTE,b.bkg_pickup_date,btk.bkg_trip_start_time) >=30) ";
			$cnt++;
		}
		//Where condition added for Driver Arrival time check 
		if (!empty($qry['late_arrival']) && $qry['late_arrival'] == 1)
		{
			$whereChk = " (TIMESTAMPDIFF(MINUTE,b.bkg_pickup_date,btk.bkg_trip_arrive_time) >=30) ";
			$cnt++;
		}
		if (!empty($qry['start_location_mismatch']) && $qry['start_location_mismatch'] == 1)
		{
			$whereChk	 .= ($cnt > 0) ? ' OR ' : '';
			$whereChk	 .= " ((ROUND(b.bkg_pickup_lat,1) != ROUND(SUBSTRING_INDEX(btk.bkg_trip_start_coordinates,',',1),1) OR  ROUND(b.bkg_pickup_long,1) != ROUND(SUBSTRING_INDEX(btk.bkg_trip_start_coordinates,',',-1),1))) ";
			$cnt++;
		}
		if (!empty($qry['end_location_mismatch']) && $qry['end_location_mismatch'] == 1)
		{
			$whereChk	 .= ($cnt > 0) ? ' OR ' : '';
			$whereChk	 .= " ((ROUND(b.bkg_dropup_lat,1) != ROUND(SUBSTRING_INDEX(btk.bkg_trip_end_coordinates,',',1),1) OR  ROUND(b.bkg_dropup_long,1) != ROUND(SUBSTRING_INDEX(btk.bkg_trip_end_coordinates,',',-1),1))) ";
			$cnt++;
		}
		if ($cnt > 0)
		{
			$where .= " AND (" . $whereChk . ") ";
		}

		$penaltyRule = "'%{\"penaltyType\":210}%'";

		$sql	 = 'SELECT b.bkg_booking_id,b.bkg_id,b.bkg_route_city_names,
                                    b.bkg_agent_ref_code,
                                    b.bkg_agent_id,
                                    b.bkg_status,
                                    b.bkg_create_date,
                                    b.bkg_pickup_date,
									bcb_vendor_id,
									bcb_driver_id,
                                    btr.btr_vendor_assign_ldate,
                                    btr.btr_driver_assign_ldate,
                                    btr.btr_cab_assign_ldate,
                                    btk.bkg_trip_arrive_time,
                                    btk.bkg_trip_start_time,
                                    btk.bkg_trip_end_time,
                                    btk.bkg_trip_start_coordinates,
                                    btk.bkg_trip_end_coordinates,
                                    CONCAT(b.bkg_pickup_lat, ",", b.bkg_pickup_long) estPickupLatlong,
                                    CONCAT(b.bkg_dropup_lat, ",", b.bkg_dropup_long) estDropupLatlong,
                                    btk.btk_last_event, adt1.adt_amount
									FROM booking b
									INNER JOIN booking_cab ON booking_cab.bcb_id=b.bkg_bcb_id AND booking_cab.bcb_active=1 
                                    INNER JOIN booking_trail btr ON btr.btr_bkg_id = b.bkg_id 
                                    INNER JOIN booking_track btk ON btk.btk_bkg_id = b.bkg_id AND b.bkg_status IN (5,6,7,9,10) 
									LEFT JOIN account_trans_details adt ON adt.adt_trans_ref_id = b.bkg_id AND adt.adt_ledger_id =28 AND adt.`adt_addt_params` LIKE  ' . $penaltyRule . ' AND adt.adt_active = 1 AND adt.adt_status = 1 
                                    LEFT JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND act.act_active = 1 AND act.act_status = 1
									LEFT JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id =14 AND adt1.adt_type =2 AND adt1.adt_active = 1
                             WHERE  1 ' . $where;
		$count	 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		if ($type == 'Command')
		{
			$dataprovider = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['bcb_vendor_id', 'bcb_driver_id'],
					'defaultOrder'	 => 'bkg_booking_id DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::query($sql, DBUtil::SDB());
			return $recordset;
		}
	}

	/**
	 * To check if dbo refund is applicable for booking
	 * @param integer $bkgId
	 */
	public static function isDBORefundApplicable($bkgId)
	{
		$sql = "SELECT count(*) as m_count 
				FROM booking 
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id=booking.bkg_id 
				WHERE booking.bkg_agent_id IS NULL AND booking_trail.btr_is_dbo_applicable = 1 AND booking_trail.btr_dbo_amount > 0 AND booking.bkg_id=:bkgId";
		return DBUtil::command($sql)->bindParam(':bkgId', $bkgId)->queryScalar();
	}

	/**
	 * To set Driver Api Sync Error Flag
	 * @param integer $bkgId
	 * @param bool $status
	 */
	public static function updateDrvApiSyncErrorFlag($bkgId, $status)
	{
		$sql = "UPDATE booking_trail SET btr_drv_api_sync_error =:value WHERE btr_bkg_id=:bkgId";
		return DBUtil::execute($sql, ["value" => (int) $status, "bkgId" => $bkgId]);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param string $value
	 * @return type
	 */
	public static function updateGnowTimerLog($bkgId, $value)
	{
		$timerLogJson	 = BookingTrail::getGnowTimerLogList($bkgId);
		$timerLogList	 = [];
		if ($timerLogJson && $timerLogJson != '')
		{
			$timerLogList = json_decode($timerLogJson, true);
		}
		$timerLogList[]	 = json_decode($value, true);
		$data			 = json_encode($timerLogList);
		$params			 = array('value' => $data, 'bkgId' => (int) $bkgId);
		$sql			 = "UPDATE booking_trail SET bkg_gnow_timer_log =:value WHERE btr_bkg_id=:bkgId";
		return DBUtil::execute($sql, $params);
	}

	/**
	 * 
	 * @param integer $bkgId 
	 * @return type
	 */
	public static function getGnowTimerLog($bkgId)
	{
		$timerLogJson = BookingTrail::getGnowTimerLogList($bkgId);
		if ($timerLogJson && $timerLogJson != '')
		{
			$timerLogList	 = json_decode($timerLogJson, true);
			$latest_key		 = array_key_last($timerLogList);
			return json_encode($timerLogList[$latest_key]);
		}
		else
		{
			return $timerLogJson;
		}
	}

	/**
	 * 
	 * @param integer $bkgId 
	 * @return type
	 */
	public static function getGnowTimerLogList($bkgId)
	{
		$params	 = array('bkgId' => (int) $bkgId);
		$sql	 = "SELECT bkg_gnow_timer_log from booking_trail  WHERE btr_bkg_id=:bkgId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param string $value
	 * @return type
	 */
	public static function getUpdatedGnowTimerLog($bkgId)
	{
		$timerLog = [];

		$timerLogJson = BookingTrail::getGnowTimerLog($bkgId);
		if (!$timerLogJson)
		{
			$startTime	 = Filter::getDBDateTime();
			$timerLog	 = ['count' => 1, 'startTime' => $startTime];
		}
		else
		{
			$timerLog	 = json_decode($timerLogJson, true);
			$timerCount	 = $timerLog['count'];
			$startTime	 = Filter::getDBDateTime();
			$count		 = $timerCount + 1;

			$timerLog = ['count' => $count, 'startTime' => $startTime];
		}
		$timerLogJson	 = json_encode($timerLog);
		$success		 = BookingTrail::updateGnowTimerLog($bkgId, $timerLogJson);
		return $timerLogJson;
	}

	/**
	 * 
	 * @param int $bkgid (optional)
	 * @return type
	 */
	public static function getGozoNowCancellable($bkgid = '')
	{
		$where = '';
		if ($bkgid > 0)
		{
			$where = " AND bkg.bkg_id = $bkgid";
		}

		$sql = "SELECT bkg.bkg_id, bkg.bkg_bcb_id,  bkg.bkg_create_date,bkg_pickup_date, 
					bkg_status, apg.apg_id, bkg_advance_amount, btr.bkg_gnow_timer_log, 
					GREATEST(LEAST(DATE_ADD(bkg_create_date, INTERVAL 3 HOUR), DATE_SUB(bkg_pickup_date, INTERVAL 1 HOUR)),DATE_ADD(bkg_create_date, INTERVAL 30 MINUTE))  expireTime
					FROM booking_pref bpr
					JOIN booking bkg ON bpr.bpr_bkg_id = bkg.bkg_id AND bpr.bkg_is_gozonow = 1 
						AND bkg.bkg_status = 2 AND bkg.bkg_reconfirm_flag = 0
					JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
					JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id AND biv.bkg_advance_amount = 0
					LEFT JOIN payment_gateway apg ON apg.apg_booking_id = bkg.bkg_id AND apg.apg_status IN (0, 1)
					WHERE (GREATEST(LEAST(DATE_ADD(bkg_create_date, INTERVAL 3 HOUR), DATE_SUB(bkg_pickup_date, INTERVAL 1 HOUR)),DATE_ADD(bkg_create_date, INTERVAL 30 MINUTE)) < NOW()) 
						AND apg.apg_id IS NULL $where
					GROUP BY bkg.bkg_id
					LIMIT 100";

		$resDate = DBUtil::query($sql, DBUtil::SDB());
		return $resDate;
	}

	/**
	 * 
	 * @param int $bkgid (optional)
	 * @return type
	 */
	public static function cancelExpiredGozoNow($bkgid = '')
	{

		$resDate			 = BookingTrail::getGozoNowCancellable($bkgid);
		$userInfo			 = UserInfo::getInstance();
		$userInfo->userType	 = UserInfo::TYPE_SYSTEM;
		$reasonText			 = 'Booking auto-cancelled by system. Reconfirm not received';
		$desc				 = 'Booking auto-cancelled by system. Reconfirm not received';
		$eventId			 = BookingLog::AUTOCANCEL_BOOKING;
		foreach ($resDate as $data)
		{
			$returnSet = new ReturnSet();
			try
			{
				$timerLogJson = $data['bkg_gnow_timer_log'];
				if ($timerLogJson != NULL)
				{
					$timerLogList		 = json_decode($timerLogJson, true);
					$latest_key			 = array_key_last($timerLogList);
					$timerLastLog		 = $timerLogList[$latest_key];
					$timerLastLogTime	 = $timerLastLog['startTime'];

					$currentTime = DBUtil::getCurrentTime();

					$currDate				 = strtotime($currentTime);
					$timerLastLogTimeStamp	 = strtotime($timerLastLogTime);
					$diffMins				 = round(($currDate - $timerLastLogTimeStamp ) / 60, 0);
					if ($diffMins < 15)
					{
						continue;
					}
				}
				$bkgId	 = $data['bkg_id'];
				$model	 = Booking::model()->findByPk($bkgId);
				$success = Booking::model()->canBooking($model->bkg_id, $reasonText, 18, $userInfo);
				if ($success)
				{
					$oldModel = $model;

					$params['blg_booking_status']	 = $model->bkg_status;
					$userInfo						 = UserInfo::model();
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
				}
			}
			catch (Exception $e)
			{
				$success = false;
				if ($e->getCode() == 500)
				{
					$e = new Exception($e->getMessage(), ReturnSet::ERROR_FAILED);
				}

				$returnSet = ReturnSet::setException($e);
			}
			echo "$bkgId : cancelled. $success ";
		}
		return $success;
	}

	/** 	 
	 * @param integer $bkgId 
	 * @return type
	 */
	public static function updateGnowTimerCustomerLastSync($bkgId)
	{
		$params	 = array('bkgId' => (int) $bkgId);
		$sql	 = "UPDATE booking_trail SET bkg_gnow_timer_customer_last_sync = NOW() WHERE btr_bkg_id=:bkgId";
		return DBUtil::execute($sql, $params);
	}

	/** 	 
	 * @param integer $bkgId 
	 * @return type
	 * Sending sms to consumer for a new offer after 10 mins if consumer has closed the window
	 */
	public static function notifyConsumerForMissedNewGnowOffers($bkgId)
	{
		$ret		 = 0;
		//Checking if consumer has closed the window  10 mins before
		$timeDiff	 = 2;
		$res		 = BookingTrail::checkNewOfferStatusTimeDiff($timeDiff, $bkgId);
		if (!empty($res) && $res['diff'])
		{
			$ret = BookingTrail::notifyForNewGnowOffer($bkgId);
		}
		return $ret;
	}

	/** 	 
	 * @param integer $bkgId 
	 * @return type	   
	 */
	public static function checkNewOfferStatusTimeDiff($timeDiff, $bkgId)
	{
		$params	 = ['timeDiff' => $timeDiff, 'bkgId' => $bkgId];
		$sql	 = "SELECT IF(TIMESTAMPDIFF(MINUTE,btr.bkg_gnow_timer_customer_last_sync,NOW()) >= :timeDiff,1,0) as diff  
						FROM booking_trail btr
						INNER JOIN booking bkg ON  bkg.bkg_id = btr.btr_bkg_id   
						WHERE btr.bkg_gnow_timer_customer_last_sync IS NOT NULL 
						AND (TIMESTAMPDIFF(MINUTE,NOW(),bkg.bkg_pickup_date) > 15)
						AND btr.btr_bkg_id=:bkgId";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/** 	 
	 * @param integer $bkgId 
	 * @return type	   
	 */
	public static function notifyForNewGnowOffer($bkgId)
	{
		try
		{
			$ret = 0;
			$res = BookingLog::checkMissedGozoNowOfferNotified($bkgId);
			if (!$res)
			{
				Users::notifyConsumerForGozonow($bkgId);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
		return $ret;
	}

	/**
	 * @param integer $bkgId 	 
	 * @return type	   
	 */
	public static function getMissedOfferList($bkgId = '')
	{
		$params	 = [];
		$where	 = '';
		if (!empty($bkgId))
		{
			$params	 = ['bkgId' => $bkgId];
			$where	 = ' AND bkg.bkg_id = :bkgId';
		}
		$sql = "SELECT bkg.bkg_id
				FROM  booking bkg                
				INNER JOIN booking_pref bpr ON bkg.bkg_id = bpr.bpr_bkg_id AND bpr.bkg_is_gozonow=1
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
				INNER JOIN booking_vendor_request bvr ON  bvr.bvr_booking_id = bkg.bkg_id
				WHERE 
				bkg.bkg_status = 2 AND bvr.bvr_accepted = 1 AND bvr.bvr_is_gozonow =1 AND bvr.bvr_bid_amount > 0 AND bvr.bvr_active=1 AND bvr.bvr_special_remarks <> ''
				AND TIMESTAMPDIFF(SECOND,btr.bkg_gnow_timer_customer_last_sync,NOW()) BETWEEN 30 AND 600
				AND (TIMESTAMPDIFF(MINUTE,NOW(),bkg.bkg_pickup_date) > 60)
				AND  bvr.bvr_accepted_at > btr.bkg_gnow_timer_customer_last_sync $where
				GROUP BY bkg.bkg_id";

		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/** 	 
	 * @param integer $bkgId 	    
	 */
	public static function sendMissedOffersNotificationToCustomers($bkgId = '')
	{
		$data = BookingTrail::getMissedOfferList($bkgId);
		foreach ($data as $row)
		{
			BookingTrail::notifyForNewGnowOffer($row['bkg_id']);
		}
	}

	public static function getBookingIdsByConfirmDate($confirmDate)
	{
		$sql = "SELECT GROUP_CONCAT(btr_bkg_id) bkg_ids FROM booking_trail WHERE bkg_confirm_datetime >= '{$confirmDate}'";
		return DBUtil::queryScalar($sql);
	}

	public static function getBkgIdsByVndAssignUnassignDate($date)
	{
		$sql = "SELECT GROUP_CONCAT(btr_bkg_id) bkg_ids FROM booking_trail WHERE btr_vendor_assign_ldate >= '{$date}' OR btr_vendor_last_unassigned >= '{$date}'";
		return DBUtil::queryScalar($sql);
	}

	public static function getBookingIdsByCancelDate($cancelDate)
	{
		$sql = "SELECT GROUP_CONCAT(btr_bkg_id) bkg_ids FROM booking_trail WHERE btr_cancel_date >= '{$cancelDate}'";
		return DBUtil::queryScalar($sql);
	}

	public static function calculateQuoteExpiryTime($createDate, $pickupDate)
	{
		$mindiff			 = Filter::CalcWorkingMinutes($createDate, $pickupDate);
		$quoteExpiryDuration = round($mindiff * 0.2);
		$quoteExpiryData	 = Config::get('quote.expiry.settings');
		$expiryLimit		 = CJSON::decode($quoteExpiryData, true);
		$quoteExpiryMin		 = min(max($expiryLimit['min'], $quoteExpiryDuration), $expiryLimit['max']);
		$quoteExpireTime	 = date('Y-m-d H:i:s', strtotime('+' . $quoteExpiryMin . ' minutes', strtotime($createDate)));
		return $quoteExpireTime;
	}

	public static function extendTimeByQuoteExpireTime($bkgId)
	{
		$model			 = Booking::model()->findByPk($bkgId);
		$now			 = Filter::getDBDateTime();
		$quoteExpireTime = $model->bkgTrail->bkg_quote_expire_date;
		$bkgCreateDate	 = $model->bkg_create_date;
		$bkgPickupDate	 = $model->bkg_pickup_date;
		if ($model->bkg_status != 15)
		{
			return;
		}

		if ($quoteExpireTime == null)
		{
			$quoteExpireTime = self::calculateQuoteExpiryTime($bkgCreateDate, $bkgPickupDate);
		}

		$diff				 = round((strtotime($quoteExpireTime) - strtotime($now)) / 60);
		$quoteExpiryDuration = '30';
		if ($quoteExpiryDuration >= $diff && $diff > 0)
		{
			$success = BookingTrail::model()->quoteExpiry($model->bkgTrail->btr_id, $quoteExpiryDuration);
		}
		return $success;
	}

	public function updateCreateType($userInfo, $status, $leadId = 0)
	{
		$this->bkg_create_user_type	 = $userInfo->userType;
		$this->bkg_create_user_id	 = $userInfo->userId;
		if ($leadId > 0)
		{
			$this->bkg_create_type = BookingTrail::CreateType_Lead;
		}
		elseif ($status == 15)
		{
			$this->bkg_create_type = BookingTrail::CreateType_Quoted;
		}
		else
		{
			$this->bkg_create_type = BookingTrail::CreateType_Self;
		}
	}

	public function completeFollowup()
	{
		$currentDate				 = date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
		$currentTime				 = date('H:i:s', strtotime(date('Y-m-d H:i:s')));
		$this->bkg_followup_date	 = $currentDate . ' ' . $currentTime;
		$this->bkg_followup_comment	 = 'Follow Completed.';
		$this->bkg_followup_active	 = 0;
		if (!$this->save())
		{
			return false;
		}
		$userInfo	 = UserInfo::getInstance();
		$desc		 = 'Follow up flag complete @ ' . date('d/m/Y', strtotime($this->bkg_followup_date)) . ' at ' . date('h:i A', strtotime($this->bkg_followup_date)) . '.';
		BookingLog::model()->createLog($this->btr_bkg_id, $desc, $userInfo, BookingLog::FOLLOWUP_COMPLETE, false, false);

		return true;
	}

	/**
	 * 
	 * @param type $pickupDate
	 * @param type $advance
	 * @return type
	 * @throws Exception
	 */
	public function updateDBOStatus($pickupDate, $advance, $bkgModel = null)
	{
		try
		{
			$updateStatus		 = false;
			$checkDboApplicable	 = Booking::checkDboApplicable($pickupDate, $bkgModel);
			if ($checkDboApplicable)
			{
				$this->btr_is_dbo_applicable = 1;
				if (!$this->save())
				{
					throw new Exception("Unable to save dbo applicable");
				}
				$updateStatus = $this->updateDBOamount($this->btr_bkg_id, $advance);
			}
			return $updateStatus;
		}
		catch (Exception $ex)
		{
			$ex->getMessage();
		}
	}

	/**
	 * 
	 * @param type $bkgID
	 * @param type $advance
	 * @return type
	 */
	public function updateDBOamount($bkgID, $advance)
	{
		$success	 = false;
		$dboSettings = Config::get('dbo.settings');
		$data		 = CJSON::decode($dboSettings);
		$maxamount	 = $data['maxrefundable'];

		if ($advance > 0)
		{
			$refundableAmt = ($advance > $maxamount) ? $maxamount : $advance;
			if ($refundableAmt > 0)
			{
				$sql	 = "UPDATE  booking_trail SET btr_dbo_amount = $refundableAmt WHERE btr_bkg_id = $bkgID";
				$success = DBUtil::execute($sql);
			}
		}
		return $success;
	}

	public static function calculatePaymentExpiryTime($createDate, $pickupDate)
	{
		if (is_null($createDate) || $createDate == '')
		{
			$createDate = DBUtil::getCurrentTime();
		}
		else if ($createDate instanceof CDbExpression)
		{

			$sqltime = $createDate->expression;

			$sql = "SELECT $sqltime FROM dual";

			$createDate = DBUtil::queryScalar($sql);
		}
		return self::calculateQuoteExpiryTime($createDate, $pickupDate);
	}

	public static function getBookingTags()
	{
		$arr = Tags::getListByType(Tags::TYPE_BOOKING);
		return $arr;
	}
	public static function impBookingFollowup($bkgId, $notifyWhatsapp = false, $generateCBR = false)
	{
		if (Tags::isVVIPBooking($bkgId))
		{
		     Tags::notifyVVIPTaggedBooking($bkgId, $notifyWhatsapp, $generateCBR);
		}
		else if(Tags::isVIPBooking($bkgId))
		{
			Tags::notifyVIPTaggedBooking($bkgId, $notifyWhatsapp, $generateCBR);
		}
		else
		{
			$model = Booking::model()->findByPk($bkgId);
			if($model->bkgUserInfo->bkg_user_id>0)
			{
			  $rowUcm = UserCategoryMaster::getByUserId($model->bkgUserInfo->bkg_user_id);
			  $category = $rowUcm['ucm_id'];
			  if($category == 4)
			  {
				 Tags::notifyVIPTaggedBooking($bkgId, $notifyWhatsapp, $generateCBR,$rowUcm['ucm_label']);
					
			  }
			}
		}
	}
}
