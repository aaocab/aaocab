<?php

/**
 * This is the model class for table "booking_pref".
 *
 * The followings are the available columns in table 'booking_pref':
 * @property integer $bpr_id
 * @property integer $bpr_bkg_id
 * @property integer $bkg_account_flag
 * @property integer $bkg_manual_assignment
 * @property integer $bkg_critical_score
 * @property integer $bkg_tentative_booking
 * @property integer $bkg_email_alert_before_pickup
 * @property integer $bkg_settled_flag
 * @property integer $bkg_blocked_msg
 * @property integer $bkg_contact_gozo

 * @property integer $bkg_invoice
 * @property integer $bkg_send_email
 * @property integer $bkg_send_sms
 * @property integer $bkg_sms_alert_before_pickup
 * @property integer $bkg_crp_send_email
 * @property integer $bkg_crp_send_sms
 * @property integer $bkg_crp_send_app
 * @property integer $bkg_trv_send_email
 * @property integer $bkg_trv_send_sms
 * @property integer $bkg_trv_send_app
 * @property integer $bkg_keep_fresh_msg_cnt
 * @property integer $bkg_isfullpayment
 * @property integer $bkg_trip_otp_required
 * @property integer $bkg_adv_reminder_sms_count
 * @property string $bkg_adv_reminder_sms_datetime
 * @property integer $bkg_fs_address_change
 * @property integer $bkg_is_msg_matched_flexxi
 * @property integer $bpr_assignment_level
 * @property integer $bpr_assignment_id
 * @property string $bpr_skip_csr_assignment
 * @property integer $bkg_block_autoassignment
 * @property integer $bkg_is_confirm_cash
 * @property integer $bkg_autocancel
 * @property integer $bkg_duty_slip_required
 * @property integer $bkg_cng_allowed
 * @property integer $bkg_critical_assignment
 * @property integer $bkg_driver_app_required
 * @property integer $bkg_water_bottles_required
 * @property integer $bkg_is_cash_required
 * @property integer $bpr_vnd_recmnd
 * @property string  $bkg_pref_req_other
 * @property integer $bpr_is_flash
 * @property integer $bpr_uncommon_route
 * @property integer $bpr_credit_problem_cancel_flag
 * @property integer $bkg_cancel_rule_id
 * @property integer $bkg_is_fbg_confirm
 * @property integer $bkg_is_fbg_type
 * @property integer $bkg_is_gozonow
 * @property integer $bkg_is_corporate
 * @property integer $bpr_zone_type 
 * @property integer $bpr_row_identifier 
 * @property integer $bpr_zone_identifier
 * @property integer $bpr_askmanual_assignment
 * @property integer $bkg_penalty_flag
 * @property integer $bkg_is_warned
 * @property integer $bpr_mask_customer_no
 * @property integer $bpr_mask_driver_no 
 * @property integer $bpr_rescheduled_from
 * @property string $bkg_min_advance_params
 * 
 * The followings are the available model relations:
 * @property Booking $bprBkg
 * 
 */
class BookingPref extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_pref';
	}

	public $isFlexxi, $bkg_pref_other;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bpr_bkg_id', 'required'),
			array('bpr_bkg_id, bkg_account_flag, bkg_manual_assignment, bkg_tentative_booking, bkg_email_alert_before_pickup, bkg_settled_flag, bkg_blocked_msg, bkg_contact_gozo,  bkg_invoice, bkg_send_email, bkg_send_sms, bkg_sms_alert_before_pickup, bkg_crp_send_email, bkg_crp_send_sms, bkg_crp_send_app, bkg_trv_send_email, bkg_trv_send_sms, bkg_trv_send_app, bkg_keep_fresh_msg_cnt, bkg_isfullpayment, bkg_trip_otp_required, bkg_adv_reminder_sms_count, bkg_fs_address_change', 'numerical', 'integerOnly' => true),
			array('bkg_adv_reminder_sms_datetime,bpr_assignment_level,bpr_assignment_id,bkg_block_autoassignment,bkg_is_confirm_cash,bkg_autocancel,bkg_duty_slip_required,bkg_cng_allowed,bkg_driver_app_required,bkg_water_bottles_required,bkg_is_cash_required,bkg_trip_otp_required,bkg_pref_req_other,bpr_is_flash,bpr_uncommon_route,bkg_critical_score,bpr_assignment_fdate,bpr_assignment_ldate,bkg_is_gozonow,bpr_zone_type,bpr_row_identifier,bpr_zone_identifier,bpr_askmanual_assignment,bpr_mask_driver_no,bpr_mask_customer_no,bpr_bkg_hash,bkg_transferz_id,bpr_rescheduled_from,bkg_min_advance_params', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('bpr_id, bpr_bkg_id, bkg_account_flag, bkg_manual_assignment,bpr_skip_csr_assignment, bkg_tentative_booking, bkg_email_alert_before_pickup, bkg_settled_flag, bkg_blocked_msg, bkg_contact_gozo, bkg_invoice, bkg_send_email, bkg_send_sms, bkg_sms_alert_before_pickup, bkg_crp_send_email, bkg_crp_send_sms, bkg_crp_send_app, bkg_trv_send_email, bkg_trv_send_sms, bkg_trv_send_app, bkg_keep_fresh_msg_cnt, bkg_isfullpayment, bkg_trip_otp_required, bkg_adv_reminder_sms_count, bkg_adv_reminder_sms_datetime, bkg_fs_address_change,bpr_assignment_level,bpr_assignment_id,bkg_block_autoassignment,bkg_autocancel,bkg_critical_assignment,bkg_duty_slip_required,bkg_cng_allowed,bkg_driver_app_required,bpr_vnd_recmnd,bkg_critical_score,bpr_credit_problem_cancel_flag,bkg_cancel_rule_id,bkg_is_fbg_confirm,bkg_is_fbg_type,bkg_is_corporate,bpr_zone_type,bpr_row_identifier,bpr_zone_identifier,bpr_askmanual_assignment,bkg_penalty_flag, bkg_is_warned', 'safe', 'on' => 'search'),
			array('bkg_settled_flag', 'numerical'),
			array('bkg_account_flag', 'required', 'on' => 'accountflag'),
			array('bpr_bkg_id, bkg_is_confirm_cash', 'required', 'on' => 'confirmCash'),
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
			'bprBkg' => array(self::BELONGS_TO, 'Booking', 'bpr_bkg_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bpr_id'						 => 'ID',
			'bpr_bkg_id'					 => 'Booking ID',
			'bkg_account_flag'				 => 'Accounts review needed',
			'bkg_manual_assignment'			 => 'Manual Assignment',
			'bkg_critical_score'			 => 'Critical Score',
			'bkg_tentative_booking'			 => 'Tentative Booking', //    bkg_tentative_booking=2 while tentative booking get advance.
			'bkg_email_alert_before_pickup'	 => 'Bkg Email Alert Before Pickup',
			'bkg_settled_flag'				 => 'Bkg Settled Flag',
			'bkg_blocked_msg'				 => 'Bkg Blocked Msg',
			'bkg_contact_gozo'				 => 'Bkg Contact Gozo',
			'bkg_invoice'					 => 'Invoice Required',
			'bkg_send_email'				 => 'Send Email',
			'bkg_send_sms'					 => 'Send Sms',
			'bkg_sms_alert_before_pickup'	 => 'Bkg Sms Alert Before Pickup',
			'bkg_crp_send_email'			 => 'Send Corporate Email',
			'bkg_crp_send_sms'				 => 'Send Corporate SMS',
			'bkg_crp_send_app'				 => 'Send Corporate App',
			'bkg_trv_send_email'			 => 'Send Traveller Email',
			'bkg_trv_send_sms'				 => 'Send Traveller SMS',
			'bkg_trv_send_app'				 => 'Send Traveller Push Notifications',
			'bkg_keep_fresh_msg_cnt'		 => 'Keep Fresh Msg Cnt',
			'bkg_isfullpayment'				 => 'Isfullpayment',
			'bkg_trip_otp_required'			 => 'OTP is Required',
			'bkg_adv_reminder_sms_count'	 => 'Adv Reminder Sms Count',
			'bkg_adv_reminder_sms_datetime'	 => 'Adv Reminder Sms Datetime',
			'bkg_fs_address_change'			 => 'Fs Address Change',
			'bkg_duty_slip_required'		 => 'All receipts & duty slips required',
			'bkg_driver_app_required'		 => 'Use of Driver app is required',
			'bkg_water_bottles_required'	 => '2x 500ml water bottles required',
			'bkg_is_cash_required'			 => 'Do not ask customer for cash',
			'bkg_penalty_flag'				 => 'Penalty review needed',
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

		$criteria->compare('bpr_id', $this->bpr_id);
		$criteria->compare('bpr_bkg_id', $this->bpr_bkg_id);
		$criteria->compare('bkg_account_flag', $this->bkg_account_flag);
		$criteria->compare('bkg_manual_assignment', $this->bkg_manual_assignment);
		$criteria->compare('bkg_critical_score', $this->bkg_critical_score);
		$criteria->compare('bkg_tentative_booking', $this->bkg_tentative_booking);
		$criteria->compare('bkg_email_alert_before_pickup', $this->bkg_email_alert_before_pickup);
		$criteria->compare('bkg_settled_flag', $this->bkg_settled_flag);
		$criteria->compare('bkg_blocked_msg', $this->bkg_blocked_msg);
		$criteria->compare('bkg_contact_gozo', $this->bkg_contact_gozo);

		$criteria->compare('bkg_invoice', $this->bkg_invoice);
		$criteria->compare('bkg_send_email', $this->bkg_send_email);
		$criteria->compare('bkg_send_sms', $this->bkg_send_sms);
		$criteria->compare('bkg_sms_alert_before_pickup', $this->bkg_sms_alert_before_pickup);
		$criteria->compare('bkg_crp_send_email', $this->bkg_crp_send_email);
		$criteria->compare('bkg_crp_send_sms', $this->bkg_crp_send_sms);
		$criteria->compare('bkg_crp_send_app', $this->bkg_crp_send_app);
		$criteria->compare('bkg_trv_send_email', $this->bkg_trv_send_email);
		$criteria->compare('bkg_trv_send_sms', $this->bkg_trv_send_sms);
		$criteria->compare('bkg_trv_send_app', $this->bkg_trv_send_app);
		$criteria->compare('bkg_keep_fresh_msg_cnt', $this->bkg_keep_fresh_msg_cnt);
		$criteria->compare('bkg_isfullpayment', $this->bkg_isfullpayment);
		$criteria->compare('bkg_trip_otp_required', $this->bkg_trip_otp_required);
		$criteria->compare('bkg_adv_reminder_sms_count', $this->bkg_adv_reminder_sms_count);
		$criteria->compare('bkg_adv_reminder_sms_datetime', $this->bkg_adv_reminder_sms_datetime, true);
		$criteria->compare('bkg_fs_address_change', $this->bkg_fs_address_change);
		$criteria->compare('bkg_penalty_flag', $this->bkg_penalty_flag);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingPref the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBooking($bkg_id)
	{
		return $this->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $bkg_id]);
	}

	/* public function sendTripOtp($bkgId, $sendOtp = false)
	  {
	  //$bookingPref = BookingPref::model()->getByBooking($bkgId);
	  $bookingPref = BookingPref::model()->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $bkgId]);
	  if ($bookingPref == '')
	  {
	  $bookingPref			 = new BookingPref();
	  $bookingPref->bpr_bkg_id = $bkgId;
	  }
	  if ($bookingPref->bprBkg->bkgTrack->bkg_trip_otp == '')
	  {
	  $bookingPref->bprBkg->bkgTrack->bkg_trip_otp = strtolower(rand(100100, 999999));
	  }
	  return $bookingPref;
	  } */

	/**
	 * This function is now deprecated and this handle through cancellationPolicy component
	 * @return Array
	 */
	public function calculateRefundMMT($tripTimeDiff, $bkgAmount = 0, $totalAdvance = 0, $rule, $createTimeDiff, $bkgId)
	{
		$bookingModel	 = Booking::model()->findByPk($bkgId);
		$ruleId			 = ServiceClass::model()->findByPk($bookingModel->bkgSvcClassVhcCat->scv_scc_id);
		$rule			 = $ruleId->scc_cancel_rule;
//		if ($bookingModel->bkgPref->bkg_cancel_rule_id != NULL)
//		{
//			$rule = $bookingModel->bkgPref->bkg_cancel_rule_id;
//		}
//        $rule = 7;
//        if($bookingModel->bkgSvcClassVhcCat->scv_scc_id == 2) // Value+
//        {
//            $rule = 13; //11
//        }
//        elseif($bookingModel->bkgSvcClassVhcCat->scv_scc_id == 5) // Select Plus
//        {
//            $rule = 2;  //12
//        }

		$arrRules = $this->getCancelChargeRule($rule);

		$minCharge	 = ($arrRules['minCharge']['type'] == 1) ? round($bkgAmount * $arrRules['minCharge']['value']) : $arrRules['minCharge']['value'];
		$maxCharge	 = ($arrRules['maxCharge']['type'] == 1) ? round($bkgAmount * $arrRules['maxCharge']['value']) : $arrRules['maxCharge']['value'];
		$defCharge	 = ($arrRules['defcharge']['type'] == 1) ? round($bkgAmount * $arrRules['defcharge']['value']) : $arrRules['defcharge']['value'];

		$defCharge		 = max([$defCharge, $minCharge]);
		$maxCharge		 = ($maxCharge > 0) ? $maxCharge : $defCharge;
		$defCharge		 = min([$defCharge, $maxCharge]);
		$cancelCharge	 = 0;
		if (($tripTimeDiff >= $arrRules['minimumTime']) || abs($createTimeDiff) <= 15)
		{
			$cancelCharge = 0;
		}
		if ($tripTimeDiff < $arrRules['minimumTime'] && $tripTimeDiff >= $arrRules['noRefundTime'])
		{
			$cancelCharge = $defCharge;
		}
		if ($tripTimeDiff <= $arrRules['noRefundTime'])
		{
			$cancelCharge = ROUND((($totalAdvance * 0.5) > $defCharge) ? $totalAdvance * 0.5 : $defCharge);
		}

		if ($arrRules['penalizePartner'] == 0)
		{
			if ($cancelCharge > $totalAdvance)
			{
				$cancelCharge = $totalAdvance;
			}
		}


		if (CancelReasons::model()->excludeCancellationCharge($bookingModel->bkg_cancel_id))
		{
			$cancelCharge = 0;
		}

		$commission	 = $this->calculateCancelCommission($rule, $cancelCharge, $bookingModel);
		$refund		 = $totalAdvance - $cancelCharge;

		if ($bookingModel->bkgPref->bkg_is_fbg_type == 1 && $bookingModel->bkgPref->bkg_is_fbg_confirm == 0 && ($bookingModel->bkg_status == 2 || $bookingModel->bkg_status == 9))
		{
			$cancelCharge = 0;
		}

		return ['refund' => $refund, 'cancelCharge' => $cancelCharge, 'commission' => $commission];
	}

	/* ----Service Tier Phase 2 Checked------- */

	/**
	 * This function is now deprecated and this handle through cancellationPolicy component
	 * @return Array
	 */
	public function calculateRefund($tripTimeDiff, $bkgAmount = 0, $totalAdvance = 0, $rule = 1, $bkgId, $display = false)
	{
		$bookingModel = Booking::model()->findByPk($bkgId);

		if ($bookingModel->bkg_agent_id == '' || $bookingModel->bkg_agent_id == 0 || $bookingModel->bkg_agent_id == 1249)
		{
			$rule = 6; // Value
			if ($bookingModel->bkgSvcClassVhcCat->scv_scc_id == 2) // Value+
			{
				$rule = 9;
			}
			elseif ($bookingModel->bkgSvcClassVhcCat->scv_scc_id == 4) // Select
			{
				$rule = 10;
			}
		}

		if ($bookingModel->bkg_agent_id == 30228)
		{
			$rule = 8;
		}

		$this->isFlexxi	 = false;
		$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($bookingModel->bkg_vehicle_type_id);
		if ($bookingModel->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
		{
			$rule			 = 5;
			$this->isFlexxi	 = true;
		}

		$arrRules		 = $this->getCancelChargeRule($rule);
		$cancelCharge	 = $this->calculateCancellationCharge($arrRules, $bkgAmount, $tripTimeDiff, $totalAdvance, $bookingModel->bkg_cancel_id, $bookingModel->bkgTrack->btk_drv_details_viewed, $display);
		if (CancelReasons::model()->excludeCancellationCharge($bookingModel->bkg_cancel_id))
		{
			$cancelCharge	 = 0;
			$refund			 = $totalAdvance;
		}
		else
		{
			$refund = $totalAdvance - $cancelCharge;
		}
		$commission	 = $this->calculateCancelCommission($rule, $cancelCharge, $bookingModel);
		$promoAmount = $this->calculatePromotionalAmount($arrRules, $cancelCharge, $tripTimeDiff, $bookingModel);

		if ($refund < 0 && $arrRules['penalizePartner'] != 1)
		{
			$refund = 0;
		}

		return ['refund' => $refund, 'cancelCharge' => $cancelCharge, 'commission' => $commission, 'promoAmount' => $promoAmount];
	}

	/**
	 * This function is used for b2b Upcurve refund calculation as per their policy 
	 * @param type $tripTimeDiff
	 * @param type $bkgAmount
	 * @param type $totalAdvance
	 * @param int $rule
	 * @param type $bkgId
	 * @param type $display
	 * @return array 
	 */
	public function calculateUpcurveRefund($tripTimeDiff, $bkgAmount = 0, $totalAdvance = 0, $rule = 1, $createTimeDiff, $bkgId)
	{
		$bookingModel = Booking::model()->findByPk($bkgId);
		if ($bookingModel->bkg_agent_id == 3936)
		{
			$rule = 8;
		}
		$arrRules		 = $this->getCancelChargeRule($rule);
		$minCharge		 = ($arrRules['minCharge']['type'] == 1) ? round($bkgAmount * $arrRules['minCharge']['value']) : $arrRules['minCharge']['value'];
		$maxCharge		 = ($arrRules['maxCharge']['type'] == 1) ? round($bkgAmount * $arrRules['maxCharge']['value']) : $arrRules['maxCharge']['value'];
		$defCharge		 = ($arrRules['defcharge']['type'] == 1) ? round($bkgAmount * $arrRules['defcharge']['value']) : $arrRules['defcharge']['value'];
		$defCharge		 = max([$defCharge, $minCharge]);
		$cancelCharge	 = 0;
		if (($createTimeDiff >= $arrRules['minimumTime']))
		{
			$cancelCharge = 0;
		}
		if ($createTimeDiff < $arrRules['minimumTime'])
		{
			if ($bookingModel->bkgTrack->bkg_is_no_show == 1)
			{
				$cancelCharge = $defCharge;
				goto skipData;
			}
			else
			{
				$cancelCharge = $minCharge;
			}
		}
		if (CancelReasons::model()->excludeCancellationCharge($bookingModel->bkg_cancel_id))
		{
			$cancelCharge = 0;
		}
		skipData:
		$refund		 = $totalAdvance - $cancelCharge;
		$commission	 = $this->calculateCancelCommission($rule, $cancelCharge, $bookingModel);
		$promoAmount = $this->calculatePromotionalAmount($arrRules, $cancelCharge, $tripTimeDiff, $bookingModel);
		return ['refund' => $refund, 'cancelCharge' => $cancelCharge, 'commission' => $commission, 'promoAmount' => $promoAmount];
	}

	public function getCancelChargeRule($rule)
	{
		/*
		 * CommissionType: 0=>No Commission on cancellation, 1=>Commission only if cancel charge = Full Booking Amount, 2=>commission on Any Cancellation Fee
		 */


		$cancellionRules = [
			'1'	 => [//default for  agents
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.2, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.2, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 1440,
				'noRefundTime'		 => 120,
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
			'2'	 => [//default mmt, VALUE
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.20, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.20, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 120, //1440
				'noRefundTime'		 => 0, //1440
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 0
			],
			'3'	 => [//default savaari
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.2, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 500, 'type' => 2],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 1440,
				'noRefundTime'		 => 120,
				'penalizePartner'	 => 1,
				'commissionType'	 => 1,
				'applypromo'		 => 0
			],
			'4'	 => [//default selltm
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => 500, 'type' => 2],
				'defcharge'			 => ['value' => 0.15, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.15, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => 0.15, 'type' => 1],
				'minimumTime'		 => 240,
				'noRefundTime'		 => 60,
				'penalizePartner'	 => 1,
				'commissionType'	 => 1,
				'applypromo'		 => 0
			],
			'5'	 => [//default for promoter
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => 0.25, 'type' => 1],
				'defcharge'			 => ['value' => 0.25, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.25, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 1440,
				'noRefundTime'		 => 720,
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
			'6'	 => [//default for customers, B2C value
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.15, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.15, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 360, //1440,
				'noRefundTime'		 => 360,
				'penalizePartner'	 => 0,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
			'7'	 => [//MMT model wise data, SELECT
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.20, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.20, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 360, //1440
				'noRefundTime'		 => 0, //1440
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 0
			],
			'8'	 => [//Upcurve for  agents
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.25, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.25, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 240,
				'noRefundTime'		 => 0,
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 0
			],
			'9'	 => [//default for customers, B2C VALUE+
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.20, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.20, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 240, //1440,
				'noRefundTime'		 => 240,
				'penalizePartner'	 => 0,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
			'10' => [//default for customers, B2C, SELECT
				'minCharge'			 => ['value' => 500, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.20, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.20, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 120, //1440,
				'noRefundTime'		 => 120,
				'penalizePartner'	 => 0,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
			'11' => [//MMT VALUE+
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.2, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.2, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 480, //1440
				'noRefundTime'		 => 30, //1440
				'penalizePartner'	 => 1,
				'commissionType'	 => 2,
				'applypromo'		 => 0
			],
			'12' => [//MMT model wise data, SELECT PLUS
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.2, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.2, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 120, //1440
				'noRefundTime'		 => 30, //1440
				'penalizePartner'	 => 1,
				'commissionType'	 => 2,
				'applypromo'		 => 0
			],
			'13' => [//MMT model wise data, SELECT
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.20, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.20, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 240, //1440
				'noRefundTime'		 => 30, //1440
				'penalizePartner'	 => 1,
				'commissionType'	 => 2,
				'applypromo'		 => 0
			],
			'14' => [//Easy my trip for  agents
				'minCharge'			 => ['value' => null, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => null, 'type' => 2],
				'defcharge'			 => ['value' => 0.25, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.25, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 240,
				'noRefundTime'		 => 60,
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
			'15' => [//Ebix Cabs India for  agents
				'minCharge'			 => ['value' => 200, 'type' => 2], //type =1(percentage),type=2(value)
				'maxCharge'			 => ['value' => 500, 'type' => 2],
				'defcharge'			 => ['value' => 0.25, 'type' => 1],
				'noMinRefundCharge'	 => ['value' => 0.25, 'type' => 1],
				'noMaxRefundCharge'	 => ['value' => null, 'type' => 2],
				'minimumTime'		 => 120,
				'noRefundTime'		 => 60,
				'penalizePartner'	 => 1,
				'commissionType'	 => 0,
				'applypromo'		 => 1
			],
		];

		return $cancellionRules[$rule];
	}

	public function calculateCancellationCharge($arrRules, $bkgAmount, $tripTimeDiff, $totalAdvance, $reasonId, $drvDetailsViewed, $display = false)
	{

		$noRefundMaxCharge	 = ($arrRules['noMaxRefundCharge']['type'] == 1) ? round($bkgAmount * $arrRules['noMaxRefundCharge']['value']) : $arrRules['noMaxRefundCharge']['value'];
		$noRefundMinCharge	 = ($arrRules['noMinRefundCharge']['type'] == 1) ? round($bkgAmount * $arrRules['noMinRefundCharge']['value']) : $arrRules['noMinRefundCharge']['value'];
		$minCharge			 = ($arrRules['minCharge']['type'] == 1) ? round($bkgAmount * $arrRules['minCharge']['value']) : $arrRules['minCharge']['value'];
		$maxCharge			 = ($arrRules['maxCharge']['type'] == 1) ? round($bkgAmount * $arrRules['maxCharge']['value']) : $arrRules['maxCharge']['value'];
		$defCharge			 = ($arrRules['defcharge']['type'] == 1) ? round($bkgAmount * $arrRules['defcharge']['value']) : $arrRules['defcharge']['value'];

		$defCharge	 = ($defCharge < $minCharge) ? $minCharge : $defCharge;
		$defCharge	 = ($defCharge > $maxCharge && $maxCharge > 0) ? $maxCharge : $defCharge;

		SWITCH ($tripTimeDiff)
		{
			CASE ($tripTimeDiff >= $arrRules['minimumTime']):
				$cancelCharge	 = 0;
				break;
			CASE ($tripTimeDiff < $arrRules['minimumTime'] && $tripTimeDiff >= $arrRules['noRefundTime']):
				$cancelCharge	 = $defCharge;
				if ($this->isFlexxi)
				{
					$defCharge = min([$defCharge, $noRefundMinCharge, $totalAdvance]);
				}
				break;
			CASE ($tripTimeDiff < $arrRules['noRefundTime']):
				$defCharge = max([$defCharge, $noRefundMinCharge, $totalAdvance]);
				if ($defCharge > $noRefundMaxCharge && $noRefundMaxCharge > 0)
				{
					$defCharge = $noRefundMaxCharge;
				}
				$cancelCharge	 = $defCharge;
				break;
			default:
				$cancelCharge	 = 0;
				break;
		}
		if ($reasonId == 8)
		{
			$cancelCharge = 0;
		}
		if ($arrRules['penalizePartner'] == 0)
		{
			if ($cancelCharge > $totalAdvance && $display == false)
			{
				$cancelCharge = $totalAdvance;
			}
		}
		if ($cancelCharge == 0 && $drvDetailsViewed == 1)
		{
			$cancelCharge = round(0.25 * $bkgAmount);
		}
		return $cancelCharge;
	}

	public function calculateCancelCommission($ruleId, $cancelCharge, $bookingModel)
	{
		$arrRules = $this->getCancelChargeRule($ruleId);

		switch ($arrRules['commissionType'])
		{
			case 1:
				if ($cancelCharge == $bookingModel->bkgInvoice->bkg_total_amount)
				{
					$commission = $bookingModel->bkgAgent->calculateCommission($cancelCharge);
				}
				break;
			case 2:
				$commission	 = $bookingModel->bkgAgent->calculateCommission($cancelCharge);
				break;
			default:
				$commission	 = 0;
				break;
		}
		return $commission;
	}

	public function calculatePromotionalAmount($arrRules, $cancelCharge, $tripTimeDiff, $model)
	{
		$promoAmount = 0;
		if ($arrRules['applypromo'] == 1)
		{
			if ($model->bkg_agent_id == '' && $model->bkg_status != 1 && $model->bkg_status != 10 && $cancelCharge > 0 && $model->bkgUserInfo->bkg_user_id > 0 && $tripTimeDiff > 240)
			{
				$bkgid		 = $model->bkg_id;
				$qry		 = "SELECT * FROM `payment_gateway` WHERE apg_trans_ref_id = '$bkgid' AND apg_status = 1 AND apg_active = 1";
				$recordset	 = DBUtil::queryRow($qry);
				if (!$recordset)
				{
					$promoAmount = 0;
				}
				else
				{
					if ($model->bkgInvoice->bkg_advance_amount != 0 && $model->bkgInvoice->bkg_advance_amount < $cancelCharge)
					{
						$promoAmount = $model->bkgInvoice->bkg_advance_amount;
					}
					else
					{
						$promoAmount = $cancelCharge;
					}
				}
			}
		}
		return $promoAmount;
	}

	public function isAllowTripVerify($bkgId)
	{
		$sql = "SELECT IF(NOW()>=date_sub(bkg_pickup_date,INTERVAL 30 minute),1,0) FROM `booking` WHERE bkg_id=$bkgId";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getAssignmentRole()
	{
		$arr = [1 => 'CSR', 2 => 'OPM'];
		return $arr;
	}

	public function getAssignmentBookinID($csrId, $arrAccess)
	{
		$where = "";
		if (sizeof($arrAccess) > 0)
		{
			$arr	 = implode(",", $arrAccess);
			$where	 = "AND stt_zone IN ($arr)";
		}
		//AND bpr_assignment_level = 0 AND bpr_assignment_id = 0
		$sql = "SELECT `bkg_id`  FROM `booking`
				INNER JOIN booking_pref on bkg_id=bpr_bkg_id
				INNER JOIN booking_trail ON bkg_id=btr_bkg_id
				INNER JOIN  cities on bkg_from_city_id = cty_id
				INNER JOIN  states on cty_state_id = stt_id
				WHERE  bkg_reconfirm_flag = 1 AND bkg_status=2 AND (bpr_assignment_level = 0 
						OR (bpr_assignment_level = 1 AND bpr_assignment_id=$csrId)) 
						AND (`bkg_manual_assignment`=1 OR bkg_critical_assignment=1) AND `bkg_block_autoassignment`= 0
						AND (bpr_skip_csr_assignment IS NULL OR bpr_skip_csr_assignment < NOW())
				$where 
				ORDER BY  bpr_assignment_level DESC, bkg_critical_assignment DESC, 
					 bkg_critical_score DESC,  btr_is_dem_sup_misfire DESC,
				`bkg_pickup_date` ASC, `bkg_create_date` ASC LIMIT 1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getIdByBooking($bookingID)
	{
		$sql = "SELECT bpr_id FROM `booking_pref` WHERE bpr_bkg_id=$bookingID";
		return DBUtil::command($sql)->queryScalar();
	}

	public function setManualAssignment($bcbId)
	{
		$sql	 = "UPDATE booking_pref, booking SET bkg_manual_assignment=1 WHERE bkg_id=bpr_bkg_id AND bkg_bcb_id IN($bcbId) AND bkg_status=2";
		$result	 = DBUtil::command($sql)->execute();
		if ($result > 0)
		{
			BookingCab::model()->findByPk($bcbId)->createBookingLog('Marked for manual assignment', BookingLog::BOOKING_MANUAL_ASSIGNMENT, UserInfo::getInstance());
		}
		return $result;
	}

	public function setManualAssignMatched($bkgId)
	{
		$bookings	 = DBUtil::command("SELECT CONCAT(bsm_upbooking_id,',',GROUP_CONCAT(bsm_downbooking_id)) FROM booking_smartmatch WHERE bsm_upbooking_id = $bkgId AND bsm_active=1")->queryScalar();
		$sql		 = "UPDATE booking_pref, booking SET bkg_manual_assignment=1 WHERE bkg_id=bpr_bkg_id AND bkg_id IN ($bookings) AND bkg_status=2";
		$result		 = DBUtil::command($sql)->execute();
		if ($result > 0)
		{
			$arrBcb = DBUtil::command("SELECT bkg_bcb_id FROM booking WHERE bkg_id IN($bookings) AND bkg_status = 2")->queryAll();
			foreach ($arrBcb as $value)
			{
				BookingCab::model()->findByPk($value['bkg_bcb_id'])->createBookingLog('Marked for manual assignment', BookingLog::BOOKING_MANUAL_ASSIGNMENT, UserInfo::getInstance());
			}
		}
		return $result;
	}

	public function updateDutyRequireStatus($status, $bkgid)
	{
		$sql	 = "UPDATE booking_pref SET bkg_all_duty_slip_received='$status' WHERE bpr_bkg_id='$bkgid'";
		$result	 = DBUtil::command($sql)->execute();
		return $result;
	}

	public function UpdateDutySlipStatus($desc, $userInfo = null)
	{
		$eventList = BookingLog::eventList();
		if ($this->bkg_duty_slip_required == '1')
		{
			$eventid						 = BookingLog::DUTYSLIP_REQUIRED;
			$this->bkg_duty_slip_required	 = '1';
		}
		else
		{
			$eventid						 = BookingLog::DUTYSLIP_NOT_REQUIRED;
			$this->bkg_duty_slip_required	 = '0';
		}
		$this->save();
		$remark_dutySlip = $eventList[$eventid] . ': ' . $desc;
		BookingLog::model()->createLog($this->bpr_bkg_id, $remark_dutySlip, $userInfo, $eventid);
	}

	public function setAccountingFlag($desc, $userInfo)
	{
		$eventList				 = BookingLog::eventList();
		$eventId				 = BookingLog::SET_ACCOUNTING_FLAG;
		$remark					 = $eventList[$eventId] . ': ' . $desc;
		$this->bkg_account_flag	 = 1;
		if (!in_array($this->bprBkg->bkg_status, [8, 10]))
		{
			$this->save();
			BookingLog::model()->createLog($this->bpr_bkg_id, $remark, $userInfo, $eventId);
		}
	}

	public function setPenaltyFlag($desc, $userInfo)
	{
		if ($this->bkg_account_flag != 1)
		{
			return;
		}
		$eventList				 = BookingLog::eventList();
		$eventId				 = BookingLog::SET_ACCOUNTING_FLAG;
		$remark					 = $eventList[$eventId] . ': ' . $desc;
		$this->bkg_account_flag	 = 1;
		$this->bkg_penalty_flag	 = 1;
		if (!in_array($this->bprBkg->bkg_status, [8, 10]))
		{
			$this->save();
		}

		//BookingLog::model()->createLog($this->bpr_bkg_id, $remark, $userInfo, $eventId);
	}

	public function resetAssingment()
	{
		$this->bpr_assignment_level	 = 0;
		$this->bpr_assignment_id	 = 0;
		$this->save();
	}

	public function updateCriticalityScore($bkg_id = '')
	{
		$numrows = false;
		try
		{
			$where = '';
			if ($bkg_id > 0)
			{
				$where = " AND booking.bkg_id = {$bkg_id}";
			}


			$sql		 = "SELECT  booking_pref.bpr_id as bprId , CS(IFNULL(bkg_confirm_datetime, bkg_create_date), booking.bkg_pickup_date,booking_trail.btr_vendor_last_unassigned) as cs from  
							booking
							INNER JOIN booking_pref  ON  booking_pref.bpr_bkg_id=booking.bkg_id AND booking.bkg_pickup_date>NOW()
							INNER JOIN booking_trail  ON  booking_trail.btr_bkg_id=bkg_id
							WHERE 1 AND bkg_status IN(2, 15) $where";
			$fetchData	 = DBUtil::query($sql, DBUtil::SDB());
			foreach ($fetchData as $fetch)
			{
				$param	 = array('bprId' => $fetch['bprId'], 'cs' => $fetch['cs']);
				$sql	 = "UPDATE  booking_pref SET booking_pref.bkg_critical_score =:cs WHERE booking_pref.bpr_id=:bprId";
				$numrows += DBUtil::execute($sql, $param);
			}
			Logger::info("Total records updated: " . $numrows);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}
		return $numrows;
	}

	public static function processManualAssignments($bkg_id = '')
	{
		$autoManualAssingmentBookingArr	 = Booking::getManualAssignments($bkg_id);
		$i								 = 0;
		foreach ($autoManualAssingmentBookingArr as $value)
		{
			$bkid	 = $value['bkg_id'];
			$rows	 = BookingPref::updateManualAssignment($bkid);
			$i		 += $rows;
			BookingVendorRequest::autoVendorAssignments($value['bkg_bcb_id']);
		}
		Logger::info("processManualAssignments: Total records processed - $i");
	}

	public static function updateManualAssignment($bkgId)
	{
		if ($bkgId == '')
		{
			return false;
		}
		$sql		 = "UPDATE booking_pref, booking_trail
						SET booking_pref.bkg_manual_assignment =1 , booking_trail.btr_manual_assign_date= NOW()
						WHERE booking_trail.btr_bkg_id = $bkgId AND booking_pref.bpr_bkg_id = $bkgId";
		$rowUpdated	 = DBUtil::command($sql)->execute();
		$model		 = Booking::model()->findByPk($bkgId);
		$cf			 = $model->bkgPref->bkg_critical_score;
		if ($rowUpdated > 0)
		{
			$userInfo		 = UserInfo::getInstance();
			$params			 = [];
			$desc			 = "Marked as Manual Assignment CF : . $cf";
			$eventid		 = BookingLog::BOOKING_MANUAL_ASSIGNMENT;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, null, $params);
			$bookingId		 = $model->bkg_booking_id;
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData	 = ['bookingId' => $model->bkg_id, 'EventCode' => Booking::CODE_MANUALASSIGNMENT_NOTIFICATION];
			$title			 = "Assignment Need Help";
			$message		 = "Manual Assignment Marked for booking " . $bookingId;
			$regionId		 = States::model()->getZoenId($model->bkg_from_city_id);
			$omIdsByRegionId = Admins::model()->getCsrNotificationListByRegionId($regionId);
			foreach ($omIdsByRegionId as $omIdByRegionId)
			{
				$omIdByRegionId = $omIdByRegionId['adm_id'];
				AppTokens::model()->notifyAdmin($omIdByRegionId, $payLoadData, $notificationId, $message, $title);
			}
			//MaxAllowableVendorAmount
			$round = 2;
			Booking::updateMaxAllowableVendorAmount($bkgId, $round);
		}
		return $rowUpdated;
	}

	public static function processCriticalAssignments($bkg_id = '')
	{
		$i						 = 0;
		$criticalAssignmentsArr	 = Booking::getCriticalAssignments($bkg_id);
		foreach ($criticalAssignmentsArr as $value)
		{
			$bkid	 = $value['bkg_id'];
			$rows	 = BookingPref::model()->updateCriticalAssignment($bkid);
			$i		 += $rows;
		}
		Logger::info("processCriticalAssignments: Total records processed - $i");
	}

	public function updateCriticalAssignment($bkgId = '')
	{
		if ($bkgId == '')
		{
			return false;
		}
		$sql	 = "UPDATE  booking_pref,   booking_trail
					SET booking_pref.bkg_critical_assignment =1 , booking_trail.btr_critical_assign_date= NOW()
					WHERE booking_trail.btr_bkg_id = $bkgId AND booking_pref.bpr_bkg_id = $bkgId";
		$rows	 = DBUtil::command($sql)->execute();
		if ($rows > 0)
		{
			$model			 = Booking::model()->findByPk($bkgId);
			$bookingId		 = $model->bkg_booking_id;
			BookingTrail::model()->updateDBOamount($bkgId, $model->bkgInvoice->bkg_advance_amount);
			$userInfo		 = UserInfo::getInstance();
			$params			 = [];
			$desc			 = "Marked as Critical Assignment";
			$eventid		 = BookingLog::BOOKING_CRITICAL_ASSIGNMENT;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, null, $params);
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData	 = ['bookingId' => $model->bkg_id, 'EventCode' => Booking::CODE_CRITICALASSIGNMENT_NOTIFICATION];
			$title			 = "Assignment Need Help";
			$message		 = "Critical Assignment Marked for booking " . $bookingId;
			$regionId		 = States::model()->getZoenId($model->bkg_from_city_id);
			$omIdsByRegionId = Admins::model()->getCsrNotificationListByRegionId($regionId);
			foreach ($omIdsByRegionId as $omIdByRegionId)
			{
				$omIdByRegionId = $omIdByRegionId['adm_id'];
				AppTokens::model()->notifyAdmin($omIdByRegionId, $payLoadData, $notificationId, $message, $title);
			}
			//MaxAllowableVendorAmount
			$round = 3;
			Booking::updateMaxAllowableVendorAmount($bkgId, $round);
		}
		return $rows;
	}

	public function getWorkingHrsCreateToPickupByID($bkgID)
	{
		$sql = "SELECT CalcWorkingHour(bkg_create_date,bkg_pickup_date) as workingHours FROM booking WHERE bkg_id = $bkgID";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getquotedbookingByCF()
	{
		$sql = "SELECT bkg_id,bkg_user_id FROM booking
				INNER JOIN booking_trail ON booking.bkg_id=booking_trail.btr_bkg_id
				JOIN booking_user ON booking.bkg_id=booking_user.bui_bkg_id  
				WHERE bkg_status= 15  AND bkg_agent_id IS NULL AND ((booking_trail.bkg_quote_expire_date< NOW()) OR (booking_trail.bkg_quote_expire_max_date< NOW()))";
		return DBUtil::queryAll($sql);
	}

	public static function getFinalUnverifiedFollowup()
	{
		$sql = "SELECT booking.bkg_id, booking_pref.bkg_critical_score 
				FROM `booking` 
				INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id AND (booking_pref.bkg_critical_score > 0.5 AND booking_pref.bkg_critical_score < 0.75)	
				WHERE booking.bkg_status=1";
		return DBUtil::queryAll($sql);
	}

	public function loadDefault(Booking $bkgModel)
	{
		$routeDistance					 = $bkgModel->bkg_trip_distance;
		$this->bkg_driver_app_required	 = 1;
		$isCng							 = true;
		if ($bkgModel->bkg_vehicle_type_id > 0)
		{
			$isCng = ((SvcClassVhcCat::getClassById($bkgModel->bkg_vehicle_type_id) == (ServiceClass::CLASS_VALUE_CNG)) || (SvcClassVhcCat::getClassById($bkgModel->bkg_vehicle_type_id) == (ServiceClass::CLASS_VLAUE_PLUS))) ? true : false;
		}
		$this->bkg_cng_allowed = (($isCng) ? 1 : 0);
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param array $userInfo
	 * @return boolean
	 */
	public function resetConfirmCash($bkgId, $userInfo)
	{
		$success						 = false;
		/* @var $modelPref BookingPref */
		$modelPref						 = $this->getByBooking($bkgId);
		$modelPref->bkg_is_confirm_cash	 = 0;
		$modelPref->scenario			 = 'confirmCash';
		if ($modelPref->save())
		{
			$desc	 = "Reset booking from confirmed as Cash.";
			BookingLog::model()->createLog($modelPref->bpr_bkg_id, $desc, $userInfo, BookingLog::BOOKING_CASH_CONFIRMED_RESET, $oldModel);
			$success = true;
		}
		return $success;
	}

	public static function counterUncommonRoutes()
	{
		$returnSet = Yii::app()->cache->get('counterUncommonRoutes');
		if ($returnSet === false)
		{
			$sql = "SELECT
				IFNULL(COUNT(DISTINCT booking.bkg_id),0) AS cnt,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id = 450,
						1,
						0
					)
				),0) AS countMMT,
				IFNULL(SUM(
					IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450), 1, 0)
				),0) AS countB2B,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id IS NULL,
						1,
						0
					)
				),0) AS countB2C
				FROM `booking`
				INNER JOIN `booking_pref` ON booking.bkg_id = booking_pref.bpr_bkg_id AND booking.bkg_status IN (15, 2, 3, 5)
                WHERE bkg_pickup_date BETWEEN NOW() AND (DATE_ADD(NOW(), INTERVAL 7 DAY)) AND booking_pref.bpr_uncommon_route = 1 LIMIT 0,1";

			$returnSet = DBUtil::queryRow($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('counterUncommonRoutes', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getServedBookings()
	{
		DBUtil::command('TRUNCATE TABLE served_bookings')->execute();

		$sql	 = "SELECT count(booking.bkg_id) bkgcnt,booking.bkg_booking_id,booking.bkg_from_city_id bkgfromcity,booking.bkg_to_city_id bkgtocity  FROM booking
				WHERE booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 30 DAY ) AND NOW() AND booking.bkg_status IN (6,7)
				GROUP BY booking.bkg_from_city_id,booking.bkg_to_city_id
				HAVING bkgcnt>2";
		$rows	 = DBUtil::queryAll($sql);

		foreach ($rows as $records)
		{
			$model					 = new ServedBookings();
			$model->seb_from_city_id = $records['bkgfromcity'];
			$model->seb_to_city_id	 = $records['bkgtocity'];
			$model->seb_bkg_served	 = $records['bkgcnt'];
			$model->seb_created		 = new CDbExpression('NOW()');
			$success				 = $model->save();
			if ($success)
			{
				echo $records['bkg_booking_id'] . '---INSERTED---fromcity---' . $records['bkgfromcity'] . '---tocity---' . $records['bkgtocity'] . "\n";
			}
		}
	}

	public function setUncommonRouteFlag()
	{
		$sql	 = "SELECT bpr_id,bkg_booking_id,booking.bkg_from_city_id bkgfromcity,booking.bkg_to_city_id bkgtocity from booking
				INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id AND booking_pref.bpr_uncommon_route = 0
				WHERE bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 10 minute) AND NOW()";
		$rows	 = DBUtil::queryAll($sql);
		foreach ($rows as $records)
		{
			$checkRoute = ServedBookings::model()->find('seb_from_city_id=:fromcity AND seb_to_city_id=:tocity', ['fromcity' => $records['bkgfromcity'], 'tocity' => $records['bkgtocity']]);
			if ($checkRoute == '' || $checkRoute == null)
			{
				$model						 = BookingPref::model()->findByPk($records['bpr_id']);
				$model->bpr_uncommon_route	 = 1;
				$success					 = $model->save();
				if ($success)
				{
					echo $records['bkg_booking_id'] . '---Uncommon Route Flag On---fromcity---' . $records['bkgfromcity'] . '---tocity---' . $records['bkgtocity'] . "\n";
				}
			}
			else
			{
				echo 'no record found' . "\n";
			}
		}
	}

	public function getVendorCsrManualAssignment($fromDate = '', $toDate = '')
	{
		if ($fromDate != '' && $toDate != '')
		{
			$conditon = " WHERE  btr.bkg_assigned_at BETWEEN  '$fromDate 00:00:00' AND '$toDate 23:59:59' AND"
					. " bkg.bkg_status IN (3,5,6,7)";
		}
		else
		{
			$conditon = " WHERE  btr.bkg_assigned_at BETWEEN (NOW() - INTERVAL 30 DAY) AND NOW() AND"
					. " bkg.bkg_status IN (3,5,6,7)";
		}
		$result	 = array();
		$sql	 = "SELECT
			(vnd.vnd_id),
			vnd.vnd_name,
			adm.adm_id,
			CONCAT(
			    adm.adm_fname,
			    ' ',
			    adm.adm_lname
			) AS csr_name,
			COUNT(bkg.bkg_id) AS count_booking
		    FROM
			booking bkg
		    INNER JOIN booking_cab bcb ON
			bcb.bcb_bkg_id1 = bkg.bkg_id AND bcb.bcb_denied_reason_id = 0
		    INNER JOIN booking_trail btr ON
			btr.btr_bkg_id = bkg.bkg_id AND btr.bkg_assign_mode = 1
		    JOIN booking_log AS blg
		    ON
			blg.blg_id =(
			SELECT
			    blg1.blg_id
			FROM
			    booking_log AS blg1
			WHERE
			    blg1.blg_booking_id = bkg.bkg_id AND blg1.blg_event_id = 7
			ORDER BY
			    blg1.blg_created
			DESC
		    LIMIT 1
		    )
		    INNER JOIN admins adm ON
			adm.adm_id = blg.blg_user_id
		    INNER JOIN vendors vnd ON
			vnd.vnd_id = bcb.bcb_vendor_id
		    $conditon
		    GROUP BY
			vnd.vnd_id,
			blg.blg_user_id";

		$sql1 = "SELECT distinct (adm.adm_id),CONCAT(adm.adm_fname,' ',adm.adm_lname) AS csr_name  FROM booking bkg
		    INNER JOIN booking_cab bcb ON
			bcb.bcb_bkg_id1 = bkg.bkg_id AND bcb.bcb_denied_reason_id = 0
		    INNER JOIN booking_trail btr ON
			btr.btr_bkg_id = bkg.bkg_id AND btr.bkg_assign_mode = 1
		    JOIN booking_log AS blg
		    ON
			blg.blg_id =(
			SELECT
			    blg1.blg_id
			FROM
			    booking_log AS blg1
			WHERE
			    blg1.blg_booking_id = bkg.bkg_id AND blg1.blg_event_id = 7
			ORDER BY
			    blg1.blg_created
			DESC
		    LIMIT 1
		    )
		    INNER JOIN admins adm ON
			adm.adm_id = blg.blg_user_id
		    INNER JOIN vendors vnd ON
			vnd.vnd_id = bcb.bcb_vendor_id
		    $conditon
		    order by  adm.adm_id ";

		$result[0]	 = DBUtil::queryAll($sql, DBUtil::SDB());
		$result[1]	 = DBUtil::queryAll($sql1, DBUtil::SDB());
		return $result;
	}

	public function checkValidSMSTripStartRegion($phoneNumber, $desc)
	{
		/** @var Booking $bookingModel */
		$bookingModel	 = $this->bprBkg;
		$sourceCity		 = $bookingModel->bkg_from_city_id;
		$destCity		 = $bookingModel->bkg_to_city_id;

		$sourceState = $bookingModel->bkgFromCity->cty_state_id;
		$destState	 = $bookingModel->bkgToCity->cty_state_id;

		$sourceRegion	 = $bookingModel->bkgFromCity->ctyState->stt_zone;
		$destRegion		 = $bookingModel->bkgToCity->ctyState->stt_zone;

		if ($sourceState == 89 || $destState == 89 || $sourceRegion == 6 || $destRegion == 6)
		{
			return true;
		}
		$success = false;

		Logger::create($desc);
		$msgCom	 = new smsWrapper();
		$msg	 = "Dear Driver, $desc";
		$msgCom->sendSMStoDrivers($phoneNumber, $msg);
		return $success;
	}

	public static function countAutoCancelFlagOn()
	{
		$returnSet = Yii::app()->cache->get('countAutoCancelFlagOn');
		if ($returnSet === false)
		{
			$sql = "SELECT
				IFNULL(COUNT(DISTINCT booking.bkg_id),0) AS cnt,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id = 450,
						1,
						0
					)
				),0) AS countMMT,
				IFNULL(SUM(
					IF(
						booking.bkg_agent_id = 18190,
						1,
						0
					)
				),0) AS countIBIBO,
				IFNULL(SUM(
					IF((booking.bkg_agent_id > 0 AND booking.bkg_agent_id != 450 AND booking.bkg_agent_id != 18190), 1, 0)
				),0) AS countB2B
				
				FROM `booking`
				INNER JOIN `booking_trail` ON booking.bkg_id = booking_trail.btr_bkg_id
                WHERE bkg_status IN(2,3,5) AND bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH)) 
					AND booking_trail.btr_auto_cancel_value = 1  
					AND booking_trail.btr_auto_cancel_reason_id = 33 LIMIT 0,1";

			$returnSet = DBUtil::queryRow($sql, DBUtil::SDB());
			Yii::app()->cache->set('countAutoCancelFlagOn', $returnSet, 600, new CacheDependency("Type_DashBoard"));
		}
		return $returnSet;
	}

	public function fbgConfirm()
	{
		$model = $this->bprBkg;
		if ($model->bkgPref->bkg_is_fbg_type != 1 || $model->bkgPref->bkg_is_fbg_confirm > 0)
		{
			return;
		}


		$this->bkg_is_fbg_confirm = 1;
		if (!$this->save())
		{
			throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		$typeAction	 = AgentApiTracking::TYPE_REVERSE_BOOKING_ACCEPT;
		$response	 = AgentMessages::model()->pushApiCall($model, $typeAction);

		if ($response->status == 2)
		{
			$cancelReason		 = CancelReasons::getTFRCancelReason();
			$cancellation_reason = $cancelReason['cnr_reason'];
			$reasonId			 = $cancelReason['cnr_id'];
			$success			 = $model->canbooking($model->bkg_id, $cancellation_reason, $reasonId);
			return;
		}

		$result = $model->setReconfirm();
		if ($result != true)
		{
			$errors = $model->getErrors();
			throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
		}


		$resDetails	 = json_decode($response->response, true);
		$res		 = $resDetails['response'];
		if ($res['success'] == 1)
		{
			if ($res['passenger'] != '' || !empty($res['passenger']))
			{
				$success = BookingUser::updateUserInfo($model->bkg_id, $res);
			}
		}
		else
		{
			if ($res['code'] == 'TRIP_UNAVAILABLE')
			{
				if ($model->bkg_status != 9)
				{
					$cancelReason		 = CancelReasons::getTFRCancelReason();
					$cancellation_reason = $cancelReason['cnr_reason'];
					$reasonId			 = $cancelReason['cnr_id'];
					$success			 = $model->canbooking($model->bkg_id, $cancellation_reason, $reasonId);
				}
				else
				{
					throw new Exception('Booking is Already Cancelled', ReturnSet::ERROR_FAILED);
				}
			}
			else
			{
				return;
			}
		}
	}

	/**
	 * This function is used for b2b EasyMyTrip refund calculation as per their policy 
	 * @param type $tripTimeDiff
	 * @param type $bkgAmount
	 * @param type $totalAdvance
	 * @param int $rule
	 * @param type $bkgId
	 * @param type $display
	 * @return array 
	 */
	public function calculateEasyMyTripRefund($tripTimeDiff, $bkgAmount = 0, $totalAdvance = 0, $rule = 1, $createTimeDiff, $bkgId)
	{
		$bookingModel = Booking::model()->findByPk($bkgId);
		if ($bookingModel->bkg_agent_id == 18621)
		{
			$rule = 14;
		}
		$arrRules		 = $this->getCancelChargeRule($rule);
		$minCharge		 = ($arrRules['minCharge']['type'] == 1) ? round($bkgAmount * $arrRules['minCharge']['value']) : $arrRules['minCharge']['value'];
		$maxCharge		 = ($arrRules['maxCharge']['type'] == 1) ? round($bkgAmount * $arrRules['maxCharge']['value']) : $arrRules['maxCharge']['value'];
		$defCharge		 = ($arrRules['defcharge']['type'] == 1) ? round($bkgAmount * $arrRules['defcharge']['value']) : $arrRules['defcharge']['value'];
		$defCharge		 = max([$defCharge, $minCharge]);
		$cancelCharge	 = 0;
		if (($createTimeDiff >= $arrRules['minimumTime']))
		{
			$cancelCharge = 0;
		}
		if ($createTimeDiff < $arrRules['minimumTime'])
		{
			if ($bookingModel->bkgTrack->bkg_is_no_show == 1)
			{
				$cancelCharge = $defCharge;
				goto skipData;
			}
			else
			{
				$cancelCharge = $defCharge;
			}
		}
		if (CancelReasons::model()->excludeCancellationCharge($bookingModel->bkg_cancel_id))
		{
			$cancelCharge = 0;
		}
		skipData:
		$refund		 = $totalAdvance - $cancelCharge;
		$commission	 = $this->calculateCancelCommission($rule, $cancelCharge, $bookingModel);
		$promoAmount = $this->calculatePromotionalAmount($arrRules, $cancelCharge, $tripTimeDiff, $bookingModel);
		return ['refund' => $refund, 'cancelCharge' => $cancelCharge, 'commission' => $commission, 'promoAmount' => $promoAmount];
	}

	/**
	 * This function is used for b2b Ebix refund calculation as per their policy 
	 * @param type $tripTimeDiff
	 * @param type $bkgAmount
	 * @param type $totalAdvance
	 * @param int $rule
	 * @param type $bkgId
	 * @param type $display
	 * @return array 
	 */
	public function calculateEbixRefund($tripTimeDiff, $bkgAmount = 0, $totalAdvance = 0, $rule = 1, $createTimeDiff, $bkgId)
	{
		$bookingModel = Booking::model()->findByPk($bkgId);
		if ($bookingModel->bkg_agent_id == 30242)
		{
			$rule = 15;
		}
		$arrRules		 = $this->getCancelChargeRule($rule);
		$minCharge		 = ($arrRules['minCharge']['type'] == 1) ? round($bkgAmount * $arrRules['minCharge']['value']) : $arrRules['minCharge']['value'];
		$maxCharge		 = ($arrRules['maxCharge']['type'] == 1) ? round($bkgAmount * $arrRules['maxCharge']['value']) : $arrRules['maxCharge']['value'];
		$defCharge		 = ($arrRules['defcharge']['type'] == 1) ? round($bkgAmount * $arrRules['defcharge']['value']) : $arrRules['defcharge']['value'];
		$defCharge		 = max([$defCharge, $minCharge]);
		$cancelCharge	 = 0;
		if (($createTimeDiff >= $arrRules['minimumTime']))
		{
			$cancelCharge = 0;
		}
		if ($createTimeDiff < $arrRules['minimumTime'])
		{
			if ($bookingModel->bkgTrack->bkg_is_no_show == 1)
			{
				$cancelCharge = $defCharge;
				goto skipData;
			}
			else
			{
				$cancelCharge = $minCharge;
			}
		}
		if (CancelReasons::model()->excludeCancellationCharge($bookingModel->bkg_cancel_id))
		{
			$cancelCharge = 0;
		}
		skipData:
		$refund		 = $totalAdvance - $cancelCharge;
		$commission	 = $this->calculateCancelCommission($rule, $cancelCharge, $bookingModel);
		$promoAmount = $this->calculatePromotionalAmount($arrRules, $cancelCharge, $tripTimeDiff, $bookingModel);
		return ['refund' => $refund, 'cancelCharge' => $cancelCharge, 'commission' => $commission, 'promoAmount' => $promoAmount];
	}

	/**
	 * @param string|CDbExpression $createDate SQL DateTime Format
	 * @param string|CDbExpression $pickupDate SQL DateTime Format
	 * @return string SQL DateTime Format
	 */
	public function getRecommendedPaymentFollowupTime($createDate, $pickupDate)
	{
		$workingMinutes	 = Filter::CalcWorkingMinutes($createDate, $pickupDate);
		$interval		 = 60;
		switch (true)
		{
			case $workingMinutes > 1440:
				$interval	 = 180;
				break;
			case $workingMinutes > 720:
				$interval	 = 120;
				break;
			case $workingMinutes < 720:
				$interval	 = 90;
				break;
			case $workingMinutes < 540:
				$interval	 = 60;
				break;
			case $workingMinutes < 360:
			default:
				$interval	 = 30;
				break;
		}

		$time = Filter::addWorkingMinutes($interval, $createDate);

		if (Filter::CalcWorkingMinutes($time, $pickupDate) < 300)
		{
			$dateTime	 = DateTimeFormat::SQLDateTimeToDateTime($time)->add(new DateInterval('P30M'));
			$time		 = DateTimeFormat::DateTimeToSQLDateTime($dateTime);
		}

		return $time;
	}

	/**
	 * This function is used to update the flag for Driver App requirement Usage
	 * @param type (int) $bkgId
	 * @return type (int) $cnt
	 */
	public static function changeDrvAppRequirementStatus($bkgId = NULL)
	{
		$param	 = ['bkgId' => $bkgId];
		$model	 = self::model()->getByBooking($bkgId);
		if ($model->bkg_driver_app_required == 1)
		{
			$sql = "UPDATE booking_pref SET bkg_driver_app_required = 0 WHERE bpr_bkg_id=:bkgId";
			$cnt = DBUtil::execute($sql, $param);
		}
		else
		{
			$sql = "UPDATE booking_pref SET bkg_driver_app_required = 1 WHERE bpr_bkg_id=:bkgId";
			$cnt = DBUtil::execute($sql, $param);
		}
		return $cnt;
	}

	public static function activateManualGozonow($bkgId = NULL)
	{
		$param	 = ['bkgId' => $bkgId];
		$model	 = self::model()->getByBooking($bkgId);
		$cnt	 = 0;
		if ($model->bkg_is_gozonow == 0)
		{
			$sql = "UPDATE booking_pref SET bkg_is_gozonow = 2 WHERE bpr_bkg_id=:bkgId";
			$cnt = DBUtil::execute($sql, $param);

			$sql1	 = "UPDATE booking_trail SET bkg_gnow_created_at = NOW() WHERE btr_bkg_id=:bkgId";
			$cnt1	 = DBUtil::execute($sql1, $param);
		}
		return $cnt;
	}

	public static function getBookingZoneType($bkgId = 0)
	{
		$where = "";
		if ($bkgId > 0)
		{
			$where .= " AND bkg_id=$bkgId ";
		}

		$sql = "SELECT
                bkg_id,
                bkg_booking_type,
                bkg_vehicle_type_id,
                bkg_from_city_id,
                bkg_to_city_id,
				JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDDBPV2.DDBPV2rowIdentifier') AS rowIdentifier,
				ROUND(JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDDBPV2.DDBPV2askingGoingRatio'),2) AS askingGoingRatio,
                ROUND(JSON_VALUE(booking_price_factor.bkg_additional_param,'$.srgDDBPV2.DDBPV2goingRegularRatio'),2) AS goingRegularRatio,
                booking_price_factor.bkg_ddbpv2_surge_factor AS ddbpv2SurgeFactor
                FROM booking                    
                INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id
				INNER JOIN booking_price_factor ON booking_price_factor.bpf_bkg_id=booking.bkg_id 
                WHERE 1 
                AND booking.bkg_create_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND  CONCAT(CURDATE(),' 23:59:59')
                $where
                AND booking_pref.bpr_zone_type IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function allgetBookingZoneType($bkgId = 0)
	{
		$where = "";
		if ($bkgId > 0)
		{
			$where .= " AND bkg_id=$bkgId ";
		}
		$sql = "SELECT
                bkg_id,
                bkg_booking_type,
                bkg_vehicle_type_id,
                bkg_from_city_id,
                bkg_to_city_id
                FROM booking                    
                INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
                WHERE 1 
                AND bkg_booking_type IS NOT NULL
                AND bkg_vehicle_type_id IS NOT NULL
                AND booking.bkg_create_date BETWEEN '2015-10-01 00:00:00' AND '2022-05-21 23:59:59'
                AND booking_pref.bpr_zone_type IS NULL 
                ORDER BY bkg_id ASC  LIMIT 0,10000";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function allgetBookingZoneTypeDateWise($date)
	{
		$fromDate	 = $date . " 00:00:00";
		$toDate		 = $date . " 23:59:59";
		$sql		 = "SELECT
                    bkg_id,
                    bkg_booking_type,
                    bkg_vehicle_type_id,
                    bkg_from_city_id,
                    bkg_to_city_id
                    FROM booking                    
                    INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
                    WHERE 1 
                    AND bkg_status  IN (6,7) 
                    AND booking.bkg_create_date BETWEEN '$fromDate' AND '$toDate'
                    AND booking_pref.bpr_zone_type IS NOT NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getBookingRowIdentifierDateWise($date)
	{
		$fromDate	 = $date . " 00:00:00";
		$toDate		 = $date . " 23:59:59";
		$sql		 = "SELECT
                    bkg_id,
                    bkg_booking_type,
                    bkg_vehicle_type_id,
                    bkg_from_city_id,
                    bkg_to_city_id
                    FROM booking                    
                    INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
                    WHERE 1 
                    AND bkg_status  IN (6,7) 
                    AND bkg_booking_type IS NOT NULL
                    AND bkg_vehicle_type_id IS NOT NULL
                    AND booking.bkg_create_date BETWEEN '$fromDate' AND '$toDate'
                    AND booking_pref.bpr_row_identifier IS NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getBookingRowIdentifier($date)
	{
		$fromDate	 = $date . " 00:00:00";
		$toDate		 = $date . " 23:59:59";
		$sql		 = "SELECT
                    bkg_id,
                    bpr_row_identifier,
                    YEAR(bkg_create_date) AS Year 
                    FROM booking                    
                    INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
                    WHERE 1 
                    AND booking.bkg_create_date BETWEEN '$fromDate' AND '$toDate'
                    AND booking_pref.bpr_row_identifier IS NOT NULL";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function getBookingZoneIdentifier($date)
	{
		$fromDate	 = $date . " 00:00:00";
		$toDate		 = $date . " 23:59:59";
		$sql		 = "SELECT
                    bkg_id,
                    bpr_zone_identifier,
                    YEAR(bkg_create_date) AS Year 
                    FROM booking                    
                    INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
                    WHERE 1 
                    AND booking.bkg_create_date BETWEEN '$fromDate' AND '$toDate'
                    AND booking_pref.bpr_zone_identifier IS NOT NULL AND bpr_zone_identifier>0";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * Setting a booking log for blocked auto assignment
	 * @param object $model (Booking Model)  
	 */
	public static function setBlockAutoAssignmentLog($model)
	{
		if ($model instanceof Booking && $model->bkgPref->bkg_block_autoassignment == 1)
		{
			$desc = "Auto assignment is blocked";
			BookingLog::model()->createLog($model->bkg_id, $desc, null, BookingLog::BLOCK_AUTOASSIGNMENT, false);
		}
	}

	/** 	
	 * Updating blocked auto assignment
	 * @param object $model (Booking Model)	 
	 */
	public static function setBlockAutoAssignment($model)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			$blockedTypes = CJSON::decode(Config::get('BookingType.AutoAssignment.Blocked'));
			if ($model->bkgPref->bkg_block_autoassignment == 0 && in_array($model->bkg_booking_type, $blockedTypes))
			{
				$model->bkgPref->bkg_block_autoassignment = 1;
				$model->bkgPref->update();
				self::setBlockAutoAssignmentLog($model);
				DBUtil::commitTransaction($transaction);
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
		}
	}

	/** 	
	 * Get all the booking list for manual assignment triggered
	 * @return object query 
	 */
	public static function getAllManualTrigger()
	{
		$sql = "SELECT
				bkg_id,
				bkg_bcb_id
				FROM booking                    
				JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
				WHERE 1 
				AND bkg_create_date BETWEEN CURDATE() AND NOW()
				AND bkg_manual_assignment = 1 AND bkg_status=2";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function showAssignemntData($bkgId)
	{
		$sql	 = "SELECT bpr_assignment_id,bpr_assignment_level FROM booking_pref WHERE bpr_bkg_id=:bkgId ";
		$params	 = ['bkgId' => $bkgId];
		return DBUtil::queryRow($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param type $model
	 * @return boolean
	 */
	public static function maskDriverNumber($model)
	{
		$success			 = true;
		$model->bkg_agent_id = ($model->bkg_agent_id == NULL) ? 1249 : $model->bkg_agent_id;
		/* @var $partnerSettings PartnerSettings */
		$partnerSettings	 = PartnerSettings::getValueById($model->bkg_agent_id);
		if ($partnerSettings['pts_mask_driver_no'] == 1)
		{
			$pickupTimeInHrs = date('H', strtotime($model->bkg_pickup_date));
			if ($pickupTimeInHrs >= 4 && $pickupTimeInHrs <= 7 && $model->bkg_agent_id == 18190)
			{
				$success = false;
			}
		}
		return $success;
	}

	/**
	 * 
	 * @param type $model
	 * @return boolean
	 */
	public static function maskCustomerNumber($model)
	{
		$success	 = true;
		$numberCode	 = self::checkPhoneNumber($model->bkgUserInfo->bkg_contact_no);
		if ($numberCode == 0)
		{
			$success = false;
		}
		$model->bkg_agent_id = ($model->bkg_agent_id == NULL) ? 1249 : $model->bkg_agent_id;
		$partnerSettings	 = PartnerSettings::getValueById($model->bkg_agent_id);
		if ($partnerSettings['pts_mask_customer_no'] == 1)
		{
			$pickupTimeInHrs = date('H', strtotime($model->bkg_pickup_date));
			if ($pickupTimeInHrs >= 4 && $pickupTimeInHrs <= 7 && $model->bkg_agent_id == 18190)
			{
				$success = false;
			}
		}
		return $success;
	}

	/**
	 * 
	 * @param type $model
	 * @param int $driverNo
	 * @return int
	 */
	public static function getDriverNumber($model, $driverNo)
	{
		$cancelFee			 = CancellationPolicy::initiateRequest($model);
		$customerToDriver	 = CJSON::decode(Config::get('mask.customer.driver.number'), true);
		$driverNo			 = ($driverNo == '') ? $model->bkgBcb->bcb_driver_phone : $driverNo;
		if ((($model->bkg_agent_id == NULL || $model->bkg_agent_id == 1249) && $cancelFee->charges == 0) || $model->bkg_agent_id == 18190)
		{
			$driverNumber = ($model->bkgPref->bpr_mask_driver_no == 1) ? $customerToDriver['customerToDriver'] : $driverNo;
		}
		else
		{
			$driverNumber = $driverNo;
		}
		return $driverNumber;
	}

	/**
	 * 
	 * @param Booking $model
	 * @return boolean
	 */
	public static function isDriverDetailsViewable($model)
	{
		$cancelFee					 = CancellationPolicy::initiateRequest($model);
		$alreadyViewed				 = ($model->bkgTrack->btk_drv_details_viewed == 1);
		$isCancelChargesApplicable	 = ($cancelFee->charges > 0);
		$minPickupTimeLeft			 = Filter::CalcWorkingMinutes(Filter::getDBDateTime(), $model->bkg_pickup_date);
		$isMinPickupTimePassed		 = ($minPickupTimeLeft < 60);
		return ($isCancelChargesApplicable || $alreadyViewed || $isMinPickupTimePassed);
	}

	/**
	 * 
	 * @param type $model
	 * @param int $customerNo
	 * @return int
	 */
	public static function getCustomerNumber($model, $customerNo)
	{
		$cancelFee			 = CancellationPolicy::initiateRequest($model);
		$driverToCustomer	 = CJSON::decode(Config::get('mask.customer.driver.number'), true);
		$customerNo			 = ($customerNo == '') ? $model->bkgUserInfo->bkg_contact_no : $customerNo;
		if ((($model->bkg_agent_id == NULL || $model->bkg_agent_id == 1249) && $cancelFee->charges == 0) || $model->bkg_agent_id == 18190)
		{
			$customerNumber = ($model->bkgPref->bpr_mask_customer_no == 1) ? $driverToCustomer['driverToCustomer'] : $customerNo;
		}
		else
		{
			$customerNumber = $customerNo;
		}
		return $customerNumber;
	}

	/**
	 * 
	 * @param type $phone
	 * @return int
	 */
	public static function checkPhoneNumber($phone)
	{
		$isValid = Filter::validatePhoneNumber($phone);
		if ($isValid)
		{
			Filter::parsePhoneNumber($phone, $code, $number);
		}
		$result = ($code == 91) ? 1 : 0;
		return $result;
	}

	/**
	 * 
	 * @param type $bktype
	 * @return string
	 */
	public function getBookingTypeCode($bktype = 0)
	{
		$arrBktype = [
			1	 => 'ONE_WAY',
			2	 => 'ROUND_TRIP',
			3	 => 'MULTI_CITY',
			4	 => 'AIRPORT_TRANSFER',
			9	 => 'DAY_RENTAL_4HR-40KM',
			10	 => 'DAY_RENTAL_8HR-80KM',
			11	 => 'DAY_RENTAL_12HR-120KM',
			12	 => 'AIRPORT_PACKAGES'
		];
		if ($bktype != 0)
		{
			return trim($arrBktype[$bktype]);
		}
		else
		{
			return $arrBktype;
		}
	}

	/**
	 * 
	 * @param type $model
	 * @return string
	 */
	public function getAirportType($model)
	{
		switch (true)
		{
			case $model->bookingRoutes->brtFromCity->cty_is_airport:
				$type	 = "PICKUP";
				break;
			case $model->bookingRoutes->brtToCity->cty_is_airport:
				$type	 = "ARRIVAL";
				break;
			default:
				$type	 = "";
				break;
		}
		return $airport;
	}

	public static function getMinAdvanceParams($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT bkg_min_advance_params from booking_pref WHERE bpr_bkg_id=:bkgId";
		return DBUtil::queryScalar($sql, null, $params);
	}

	public static function updateMinAdvanceParams($bkgId, $minPerc, $bkgTotalAmount = 0, $minPayAmount = null)
	{
		$params = ['bkgId' => $bkgId];
		if (!$minPayAmount)
		{
			$minPayAmount = (int) round($minPerc * $bkgTotalAmount * 0.01);
		}
		if (!$minPayAmount)
		{
			return false;
		}

		$minAdvData = BookingPref::getMinAdvanceParams($bkgId);
		if ($minAdvData && $minPayAmount > 0)
		{
			$sql	 = "UPDATE booking_pref SET bkg_min_advance_params=JSON_SET(bkg_min_advance_params, '$.amount', $minPayAmount) WHERE bpr_bkg_id=:bkgId";
			$result	 = DBUtil::execute($sql, $params);
		}
		else
		{
			$data		 = ['type' => 1, 'value' => $minPerc, 'amount' => $minPayAmount];
			$jsonData	 = json_encode($data);
			$sql		 = "UPDATE booking_pref SET bkg_min_advance_params='$jsonData' WHERE bpr_bkg_id=:bkgId";
			$result		 = DBUtil::execute($sql, $params);
		}
		return $result;
	}

	public static function getAccountingFlagSet($bkgmodel)
	{
		$dateRange = '';

		if (!$bkgmodel->bkg_create_date1 || !$bkgmodel->bkg_create_date2)
		{
			$dateRange = " AND bkg.bkg_create_date > DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) ";
		}
		else
		{
			$fromDate	 = $bkgmodel->bkg_create_date1;
			$toDate		 = $bkgmodel->bkg_create_date2;
			$dateRange	 = " AND bkg.bkg_create_date<= '$toDate 23:59:59' AND bkg.bkg_create_date>= '$fromDate 00:00:00' ";
		}
		$sql = "SELECT bkg_id, bkg_booking_id, bkg_status, 
				DATE_FORMAT(bkg_create_date, '%Y-%m-%d %H:%i') AS bkg_create_date, 
				DATE_FORMAT(bkg_pickup_date, '%Y-%m-%d %H:%i') AS bkg_pickup_date, 
				CASE 
					WHEN bkg_status = 1 THEN 'Unverified' 
					WHEN bkg_status = 2 THEN 'New' 
					WHEN bkg_status = 3 THEN 'Assigned' 
					WHEN bkg_status = 4 THEN 'Confirmed' 
					WHEN bkg_status = 5 THEN 'Allocated' 
					WHEN bkg_status = 6 THEN 'Completed' 
					WHEN bkg_status = 7 THEN 'Settled' 
					WHEN bkg_status = 9 THEN 'Cancelled' 
					WHEN bkg_status = 10 THEN 'Unverified Cancelled' 
					ELSE 'Quoted' 
				END AS status,
				blg_desc, blg_user_type, blg_user_id 
				FROM booking bkg 
				INNER JOIN booking_pref ON bkg.bkg_id = bpr_bkg_id 
				INNER JOIN booking_log ON bkg.bkg_id = blg_booking_id AND blg_event_id = 65 
				WHERE bkg_account_flag = 1 AND bkg.bkg_status IN (1,2,3,5,6,7,9,15) 
				{$dateRange} 
				ORDER BY bkg.bkg_create_date ASC";

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB())->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 =>
				[],
				'defaultOrder'	 => "bkg_create_date ASC"],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function isFullCashAllowed($bkgId)
	{
		$model			 = Booking::model()->findByPk($bkgId);
		$isAllowedCash	 = false;
		if (in_array($model->bkg_status, [1, 15]))
		{
			if ($model->bkgUserInfo->bkg_user_id > 0)
			{
				$isAllowedCash = UserCategoryMaster::isCashBookingAllowed($model->bkgUserInfo->bkg_user_id, $model->bkg_booking_type); //user category bronze,silver,gold,platinum
			}
			if (in_array($model->bkg_booking_type, [4, 12]) && $model->bkg_transfer_type == 1 && ($model->bkg_agent_id == Config::get('Mobisign.partner.id') || $model->bkg_agent_id == null))
			{
				$isAllowedCash = true; //airport pickup b2c and mobisign
			}
			if ($model->bkg_agent_id == null && (Tags::isVIPBooking($model->bkg_id) > 0 || Tags::isVVIPBooking($model->bkg_id) > 0))
			{
				$isAllowedCash = true; //vip tagged customer
			}
			if ($model->bkgPref->bpr_rescheduled_from > 0)
			{
				$isAllowedCash = false; // rescheduled booking
			}
		}
		return $isAllowedCash;
	}

	/**
	 * 
	 * @param Booking $model OldModel
	 * @param Booking $newModel NewModel
	 */
	public static function getAttrParamsReschedule($model, $newModel)
	{
		if (!$model && !$newModel)
		{
			return false;
		}
		if ($model->bkgPref->bkg_cancel_rule_id != $newModel->bkgPref->bkg_cancel_rule_id)
		{
			$cancelDesc	 = CancellationPolicyDetails::model()->findByPk($newModel->bkgPref->bkg_cancel_rule_id)->cnp_desc;
			$cancelCode	 = CancellationPolicyDetails::getCodeById($newModel->bkgPref->bkg_cancel_rule_id);
		}
		if ($newModel->bkgInvoice->bkg_total_amount > $model->bkgInvoice->bkg_total_amount)
		{
			$totalAmount = (int) ($newModel->bkgInvoice->bkg_total_amount - $newModel->bkgInvoice->bkg_extra_charge);
		}
		if ($newModel->minPayExtra)
		{
			$minPaymentReq = (int) ($newModel->minPay);
		}
		$minPaymentDue		 = (int) ($newModel->minPayExtra);
		$refundFromExisting	 = (int) ($model->bkgInvoice->bkg_advance_amount - $newModel->rescheduleCharge);
		$charge				 = (int) $newModel->rescheduleCharge;

		$params = [
			'cancelCode'		 => $cancelCode,
			'cancelDesc'		 => $cancelDesc,
			'totalAmount'		 => $totalAmount,
			'minPaymentReq'		 => $minPaymentReq,
			'minPaymentDue'		 => $minPaymentDue,
			'refundFromExisting' => $refundFromExisting,
			'rescheduleCharge'	 => $charge];
		return $params;
	}

	public function applyAddonBenefit($adnType,$adnId,$defCanPolicyId = 0)
	{
		$bkgModel = $this->bprBkg;
		switch ($adnType)
		{
			case 1:
				$this->bkg_cancel_rule_id = ($adnId > 0) ? AddonCancellationPolicy::getCancelRuleById($adnId) : $defCanPolicyId;
				$success = $this->save();
				break;
			case 2:
				if ($adnId > 0)
				{
					$cabType	 = AddonCabModels::model()->findByPk($adnId)->acm_svc_id_to;
				}
				$bkgModel->bkg_vehicle_type_id	 = ($cabType > 0) ? $cabType : SvcClassVhcCat::model()->findByPk($bkgModel->bkg_vehicle_type_id)->scv_parent_id;
				$bkgModel->bkg_vht_id			 = SvcClassVhcCat::model()->findByPk($cabType)->scv_model;
				$success = $bkgModel->save();

				break;
			default:
				break;
		}
		return $success;
	}
	
	public static function getRescheduledBkgIdsByUserId($userId)
	{
		$arrRescheduledBookings = [];

		$sql = "SELECT bkg_id, bkg_booking_id, bpr_rescheduled_from, bkg_status  
				FROM booking 
				INNER JOIN booking_user ON bkg_id = bui_bkg_id 
				INNER JOIN booking_pref ON bpr_bkg_id = bkg_id AND bpr_rescheduled_from > 0 
				WHERE 1 AND bkg_pickup_date >= '2018-04-01 00:00:00' AND bkg_user_id = {$userId} 
				AND (`bkg_status` IN (2,3,5,6,7,9) OR (bkg_pickup_date > NOW() AND `bkg_status` IN (1,15)))";
		$row = DBUtil::query($sql);
		if ($res)
		{
			foreach ($res as $row)
			{
				$arrRescheduledBookings[$row['bpr_rescheduled_from']] = ['bkg_id' => $row['bkg_id'], 'bkg_booking_id' => $row['bkg_booking_id'], 'bkg_status' => $row['bkg_status']];
			}
		}

		return false;
	}

}
