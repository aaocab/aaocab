<?php

/**
 * This is the model class for table "vendor_stats".
 *
 * The followings are the available columns in table 'vendor_stats':
 * @property integer $vrs_id
 * @property integer $vrs_vnd_id
 * @property string $vrs_last_logged_in
 * @property integer $vrs_credit_limit
 * @property integer $vrs_security_amount
 * @property string $vrs_security_receive_date
 * @property integer $vrs_credit_throttle_level
 * @property string $vrs_vnd_overall_rating
 * @property integer $vrs_vnd_total_trip
 * @property integer $vrs_trip_status_pending_count
 * @property integer $vrs_count_driver
 * @property integer $vrs_count_car
 * @property integer $vrs_approve_driver_count
 * @property integer $vrs_approve_car_count
 * @property integer $vrs_car_reg_compact_cnt
 * @property integer $vrs_car_reg_sedan_cnt
 * @property integer $vrs_car_reg_suv_cnt
 * @property integer $vrs_car_reg_tempo12_cnt
 * @property integer $vrs_car_reg_tempo15_cnt
 * @property integer $vrs_drivers_reg_cnt
 * @property integer $vrs_car_compact_avail_cnt
 * @property integer $vrs_car_suv_avail_cnt
 * @property integer $vrs_car_reg_tempo9_cnt
 * @property integer $vrs_car_sedan_avail_cnt
 * @property integer $vrs_car_tempo12_avail_cnt
 * @property integer $vrs_car_tempo15_avail_cnt
 * @property string $vrs_trust_score
 * @property integer $vrs_docs_score
 * @property integer $vrs_docs_r4a
 * @property integer $vrs_overall_score
 * @property integer $vrs_avg_cab_used
 * @property integer $vrs_no_of_star
 * @property integer $vrs_denied_duty_cnt
 * @property integer $vrs_use_drv_app
 * @property integer $vrs_drv_app_last10_trps
 * @property double $vrs_outstanding
 * @property integer $vrs_total_trips
 * @property integer $vrs_last_thirtyday_trips
 * @property integer $vrs_total_amount
 * @property integer $vrs_last_thirtyday_amount
 * @property string $vrs_last_trip_datetime
 * @property string $vrs_first_trip_datetime
 * @property integer $vrs_mark_vend_count
 * @property integer $vrs_effective_credit_limit
 * @property integer $vrs_effective_overdue_days
 * @property integer $vrs_driver_mismatch_count
 * @property double $vrs_avg10
 * @property double $vrs_avg30
 * @property double $vrs_locked_amount
 * @property double $vrs_withdrawable_balance
 * @property string $vrs_last_bkg_cmpleted 
 * @property integer $vrs_pending_cars
 * @property integer $vrs_pending_drivers
 * @property integer $vrs_rejected_cars
 * @property integer $vrs_rejected_drivers
 * @property string $vrs_last_loc_lat
 * @property string $vrs_last_loc_long
 * @property string $vrs_last_loc_device_id
 * @property string $vrs_last_loc_date
 * @property integer $vrs_OW_Count
 * @property integer $vrs_RT_Count
 * @property integer $vrs_AT_Count
 * @property integer $vrs_PT_Count
 * @property integer $vrs_FL_Count
 * @property integer $vrs_SH_Count
 * @property integer $vrs_CT_Count
 * @property integer $vrs_DR_4HR_Count
 * @property integer $vrs_DR_8HR_Count
 * @property integer $vrs_DR_12HR_Count
 * @property integer $vrs_AP_Count
 * @property double $vrs_dependency
 * @property string $vrs_last_bid_datetime
 * @property string $vrs_platform
 * @property float $vrs_margin
 * @property float $vrs_bid_win_percentage
 * @property float $vrs_dependency
 * @property float $vrs_boost_percentage
 * @property integer $vrs_delivered_cnt
 * @property string $vrs_delivered_at
 * @property integer $vrs_received_cnt
 * @property string $vrs_received_at
 * @property integer $vrs_read_cnt
 * @property string $vrs_read_at

 *
 * The followings are the available model relations:
 * @property Vendors $vrsVnd
 * @property integer $vrs_tot_bid
 * @property integer $vrs_denied_within_6
 * @property integer $vrs_denied_btwn_6_12
 * @property integer $vrs_denied_aft_12
 * @property integer $vrs_denied_pickdiff_12
 * @property integer $vrs_denied_pickdiff_12_24
 * @property integer $vrs_denied_pickdiff_gtr_24
 * 
 */
class VendorStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vendor_stats';
	}

	public $vrs_security_receive_date1;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vrs_vnd_id', 'required'),
			array('vrs_vnd_id, vrs_credit_limit, vrs_security_amount, vrs_credit_throttle_level, vrs_vnd_total_trip, vrs_count_driver, vrs_count_car,  vrs_car_reg_compact_cnt, vrs_car_reg_sedan_cnt, vrs_car_reg_suv_cnt, vrs_car_reg_tempo9_cnt,vrs_car_reg_tempo12_cnt, vrs_car_reg_tempo15_cnt, vrs_drivers_reg_cnt, vrs_car_compact_avail_cnt, vrs_car_suv_avail_cnt, vrs_car_sedan_avail_cnt, vrs_car_tempo12_avail_cnt, vrs_car_tempo15_avail_cnt, vrs_car_tempo9_avail_cnt, vrs_docs_score, vrs_overall_score, vrs_avg_cab_used, vrs_no_of_star, vrs_denied_duty_cnt, vrs_use_drv_app, vrs_drv_app_last10_trps, vrs_total_trips, vrs_last_thirtyday_trips, vrs_total_amount, vrs_last_thirtyday_amount, vrs_mark_vend_count, vrs_effective_credit_limit, vrs_effective_overdue_days, vrs_driver_mismatch_count,vrs_OW_Count,vrs_RT_Count,vrs_AT_Count,vrs_PT_Count,vrs_FL_Count,vrs_SH_Count,vrs_CT_Count,vrs_DR_4HR_Count,vrs_DR_8HR_Count,vrs_DR_12HR_Count,vrs_AP_Count', 'numerical', 'integerOnly' => true),
			array('vrs_outstanding, vrs_avg10, vrs_avg30', 'numerical'),
			array('vrs_vnd_overall_rating, vrs_trust_score', 'length', 'max' => 10),
			array('vrs_last_logged_in, vrs_security_receive_date, vrs_last_trip_datetime, vrs_first_trip_datetime, vrs_last_bid_datetime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vrs_security_receive_date1', 'date', 'format' => 'dd/MM/yyyy'),
			array('vrs_id, vrs_vnd_id, vrs_last_logged_in, vrs_credit_limit, vrs_security_amount, vrs_security_receive_date, vrs_credit_throttle_level, vrs_vnd_overall_rating, vrs_vnd_total_trip, vrs_count_driver, vrs_count_car,  vrs_approve_driver_count, vrs_approve_car_count, vrs_car_reg_compact_cnt, vrs_car_reg_sedan_cnt, vrs_car_reg_suv_cnt, vrs_car_reg_tempo9_cnt, vrs_car_reg_tempo12_cnt, vrs_car_reg_tempo15_cnt, vrs_drivers_reg_cnt, vrs_car_compact_avail_cnt, vrs_car_suv_avail_cnt, vrs_car_sedan_avail_cnt, vrs_car_tempo12_avail_cnt, vrs_car_tempo15_avail_cnt, vrs_car_tempo9_avail_cnt, vrs_trust_score, vrs_docs_score, vrs_overall_score, vrs_avg_cab_used, vrs_no_of_star, vrs_denied_duty_cnt, vrs_use_drv_app, vrs_drv_app_last10_trps, vrs_outstanding, vrs_total_trips, vrs_last_thirtyday_trips, vrs_total_amount, vrs_last_thirtyday_amount, vrs_last_trip_datetime, vrs_first_trip_datetime, vrs_mark_vend_count, vrs_effective_credit_limit, vrs_effective_overdue_days, vrs_driver_mismatch_count, vrs_avg10, vrs_avg30', 'safe', 'on' => 'search'),
			array('vrs_id, vrs_vnd_id, vrs_last_logged_in, vrs_credit_limit, vrs_security_amount, vrs_security_receive_date, vrs_credit_throttle_level, vrs_vnd_overall_rating, vrs_vnd_total_trip, vrs_count_driver, vrs_count_car,  vrs_approve_driver_count, vrs_approve_car_count, vrs_car_reg_compact_cnt, vrs_car_reg_sedan_cnt, vrs_car_reg_suv_cnt, vrs_car_reg_tempo9_cnt, vrs_car_reg_tempo12_cnt, vrs_car_reg_tempo15_cnt, vrs_drivers_reg_cnt, vrs_car_compact_avail_cnt, vrs_car_suv_avail_cnt, vrs_car_sedan_avail_cnt, vrs_car_tempo12_avail_cnt, vrs_car_tempo15_avail_cnt, vrs_car_tempo9_avail_cnt, vrs_trust_score, vrs_docs_score, vrs_overall_score, vrs_avg_cab_used, vrs_no_of_star, vrs_denied_duty_cnt, vrs_use_drv_app, vrs_drv_app_last10_trps, vrs_outstanding, vrs_total_trips, vrs_last_thirtyday_trips, vrs_total_amount, vrs_last_thirtyday_amount, vrs_last_trip_datetime, vrs_first_trip_datetime, vrs_mark_vend_count, vrs_effective_credit_limit, vrs_effective_overdue_days, vrs_driver_mismatch_count, vrs_avg10, vrs_avg30, vrs_locked_amount, vrs_withdrawable_balance, vrs_docs_r4a, vrs_last_bkg_cmpleted, vrs_trip_status_pending_count,vrs_pending_cars,vrs_pending_drivers,vrs_rejected_cars,vrs_rejected_drivers,vrs_last_loc_lat, vrs_last_loc_long, vrs_last_loc_device_id ,vrs_last_loc_date,vrs_OW_Count,vrs_RT_Count,vrs_AT_Count,vrs_PT_Count,vrs_FL_Count,vrs_SH_Count,vrs_CT_Count,vrs_DR_4HR_Count,vrs_DR_8HR_Count,vrs_DR_12HR_Count,vrs_AP_Count,vrs_coin,vrs_platform,vrs_margin,vrs_bid_win_percentage,vrs_dependency,vrs_boost_percentage,vrs_first_approve_date,vrs_last_approve_date,vrs_delivered_cnt,vrs_delivered_at,vrs_received_cnt,vrs_received_at,vrs_read_cnt,vrs_read_at', 'safe'),
			//array('vrs_security_amount', 'validateSecurityDate', 'on' => 'insert,update'),
			array('vrs_credit_throttle_level', 'length', 'max' => 100, 'on' => 'update'),
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
			'vrsVnd' => array(self::BELONGS_TO, 'Vendors', 'vrs_vnd_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'vrs_id'						 => 'Vrs',
			'vrs_vnd_id'					 => 'Vrs Vnd',
			'vrs_last_logged_in'			 => 'Vrs Last Logged In',
			'vrs_credit_limit'				 => 'Credit Limit',
			'vrs_security_amount'			 => 'Security Amount',
			'vrs_security_receive_date'		 => 'Security Receive Date',
			'vrs_security_receive_date1'	 => 'Security Receive Date',
			'vrs_credit_throttle_level'		 => 'Credit Throttle Level',
			'vrs_vnd_overall_rating'		 => 'Vnd Overall Rating',
			'vrs_vnd_total_trip'			 => 'Vnd Total Trip',
			'vrs_trip_status_pending_count'	 => 'Trip Status Pending Count',
			'vrs_count_driver'				 => 'Count Driver',
			'vrs_count_car'					 => 'Count Car',
			'vrs_car_reg_compact_cnt'		 => 'Vrs Car Reg Compact Cnt',
			'vrs_car_reg_sedan_cnt'			 => 'Vrs Car Reg Sedan Cnt',
			'vrs_car_reg_suv_cnt'			 => 'Vrs Car Reg Suv Cnt',
			'vrs_car_reg_tempo12_cnt'		 => 'Vrs Car Reg Tempo12 Cnt',
			'vrs_car_reg_tempo15_cnt'		 => 'Vrs Car Reg Tempo15 Cnt',
			'vrs_drivers_reg_cnt'			 => 'Vrs Drivers Reg Cnt',
			'vrs_car_compact_avail_cnt'		 => 'Vrs Car Compact Avail Cnt',
			'vrs_car_suv_avail_cnt'			 => 'Vrs Car Suv Avail Cnt',
			'vrs_car_sedan_avail_cnt'		 => 'Vrs Car Sedan Avail Cnt',
			'vrs_car_reg_tempo9_cnt'		 => 'Vrs Car Reg Tempo9 Cnt',
			'vrs_car_tempo12_avail_cnt'		 => 'Vrs Car Tempo12 Avail Cnt',
			'vrs_car_tempo15_avail_cnt'		 => 'Vrs Car Tempo15 Avail Cnt',
			'vrs_car_tempo9_avail_cnt'		 => 'Vrs Car Tempo9 Avail Cnt',
			'vrs_trust_score'				 => 'Trust Score',
			'vrs_docs_score'				 => 'Docs Score',
			'vrs_overall_score'				 => 'Overall Score',
			'vrs_avg_cab_used'				 => 'Avg Cab Used',
			'vrs_no_of_star'				 => 'No Of Star',
			'vrs_denied_duty_cnt'			 => 'Denied Duty Cnt',
			'vrs_use_drv_app'				 => 'Vrs Use Drv App',
			'vrs_drv_app_last10_trps'		 => 'Drv App Last10 Trps',
			'vrs_outstanding'				 => 'Outstanding',
			'vrs_total_trips'				 => 'Total Trips',
			'vrs_last_thirtyday_trips'		 => 'Last Thirtyday Trips',
			'vrs_total_amount'				 => 'Total Amount',
			'vrs_last_thirtyday_amount'		 => 'Last Thirtyday Amount',
			'vrs_last_trip_datetime'		 => 'Last Trip Datetime',
			'vrs_first_trip_datetime'		 => 'First Trip Datetime',
			'vrs_mark_vend_count'			 => 'Mark Vend Count',
			'vrs_effective_credit_limit'	 => 'Effective Credit Limit',
			'vrs_effective_overdue_days'	 => 'Effective Overdue Days',
			'vrs_driver_mismatch_count'		 => 'Driver Mismatch Count',
			'vrs_avg10'						 => 'Avg10',
			'vrs_avg30'						 => 'Avg30',
			'vrs_locked_amount'				 => 'Vendor Locked Amount',
			'vrs_withdrawable_balance'		 => 'Vendor Withdrawable Balance',
			'vrs_last_bkg_cmpleted '		 => 'Vendor Last Booking Completed',
			'vrs_first_approve_date '		 => 'Vendor First Approve Date',
			'vrs_last_approve_date '		 => 'Vendor Last Approve Date',
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

		$criteria->compare('vrs_id', $this->vrs_id);
		$criteria->compare('vrs_vnd_id', $this->vrs_vnd_id);
		$criteria->compare('vrs_last_logged_in', $this->vrs_last_logged_in, true);
		$criteria->compare('vrs_credit_limit', $this->vrs_credit_limit);
		$criteria->compare('vrs_security_amount', $this->vrs_security_amount);
		$criteria->compare('vrs_security_receive_date', $this->vrs_security_receive_date, true);
		$criteria->compare('vrs_credit_throttle_level', $this->vrs_credit_throttle_level);
		$criteria->compare('vrs_vnd_overall_rating', $this->vrs_vnd_overall_rating, true);
		$criteria->compare('vrs_vnd_total_trip', $this->vrs_vnd_total_trip);
		$criteria->compare('vrs_count_driver', $this->vrs_count_driver);
		$criteria->compare('vrs_count_car', $this->vrs_count_car);
		$criteria->compare('vrs_car_reg_compact_cnt', $this->vrs_car_reg_compact_cnt);
		$criteria->compare('vrs_car_reg_sedan_cnt', $this->vrs_car_reg_sedan_cnt);
		$criteria->compare('vrs_car_reg_suv_cnt', $this->vrs_car_reg_suv_cnt);
		$criteria->compare('vrs_car_reg_tempo9_cnt', $this->vrs_car_reg_tempo9_cnt);
		$criteria->compare('vrs_car_reg_tempo12_cnt', $this->vrs_car_reg_tempo12_cnt);
		$criteria->compare('vrs_car_reg_tempo15_cnt', $this->vrs_car_reg_tempo15_cnt);
		$criteria->compare('vrs_drivers_reg_cnt', $this->vrs_drivers_reg_cnt);
		$criteria->compare('vrs_car_compact_avail_cnt', $this->vrs_car_compact_avail_cnt);
		$criteria->compare('vrs_car_suv_avail_cnt', $this->vrs_car_suv_avail_cnt);
		$criteria->compare('vrs_car_sedan_avail_cnt', $this->vrs_car_sedan_avail_cnt);
		$criteria->compare('vrs_car_tempo12_avail_cnt', $this->vrs_car_tempo12_avail_cnt);
		$criteria->compare('vrs_car_tempo15_avail_cnt', $this->vrs_car_tempo15_avail_cnt);
		$criteria->compare('vrs_car_tempo9_avail_cnt', $this->vrs_car_tempo9_avail_cnt);
		$criteria->compare('vrs_trust_score', $this->vrs_trust_score, true);
		$criteria->compare('vrs_docs_score', $this->vrs_docs_score);
		$criteria->compare('vrs_overall_score', $this->vrs_overall_score);
		$criteria->compare('vrs_avg_cab_used', $this->vrs_avg_cab_used);
		$criteria->compare('vrs_no_of_star', $this->vrs_no_of_star);
		$criteria->compare('vrs_denied_duty_cnt', $this->vrs_denied_duty_cnt);
		$criteria->compare('vrs_use_drv_app', $this->vrs_use_drv_app);
		$criteria->compare('vrs_drv_app_last10_trps', $this->vrs_drv_app_last10_trps);
		$criteria->compare('vrs_outstanding', $this->vrs_outstanding);
		$criteria->compare('vrs_total_trips', $this->vrs_total_trips);
		$criteria->compare('vrs_last_thirtyday_trips', $this->vrs_last_thirtyday_trips);
		$criteria->compare('vrs_total_amount', $this->vrs_total_amount);
		$criteria->compare('vrs_last_thirtyday_amount', $this->vrs_last_thirtyday_amount);
		$criteria->compare('vrs_last_trip_datetime', $this->vrs_last_trip_datetime, true);
		$criteria->compare('vrs_first_trip_datetime', $this->vrs_first_trip_datetime, true);
		$criteria->compare('vrs_mark_vend_count', $this->vrs_mark_vend_count);
		$criteria->compare('vrs_effective_credit_limit', $this->vrs_effective_credit_limit);
		$criteria->compare('vrs_effective_overdue_days', $this->vrs_effective_overdue_days);
		$criteria->compare('vrs_driver_mismatch_count', $this->vrs_driver_mismatch_count);
		$criteria->compare('vrs_avg10', $this->vrs_avg10);
		$criteria->compare('vrs_avg30', $this->vrs_avg30);
		$criteria->compare('vrs_avg30', $this->vrs_locked_amount);
		$criteria->compare('vrs_first_approve_date', $this->vrs_first_approve_date);
		$criteria->compare('vrs_last_approve_date', $this->vrs_last_approve_date);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendorStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeValidate()
	{
		if ($this->vrs_security_receive_date1 != NULL)
		{
			$this->vrs_security_receive_date = DateTimeFormat::DatePickerToDate($this->vrs_security_receive_date1);
		}
		return parent::beforeValidate();
	}

	public function getBookingByVendorID($vendorId)
	{
		$sql	 = "SELECT COUNT(DISTINCT booking.bkg_id) as coutBooking, coutTrans,  booking_cab.bcb_vendor_id
					FROM `booking`
					INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
					LEFT JOIN
					(
					   SELECT COUNT(DISTINCT account_transactions.act_id) as coutTrans,adt_trans_ref_id
					   FROM `account_trans_details`
					   INNER JOIN `account_transactions` ON account_transactions.act_id=account_trans_details.adt_trans_id
					   AND account_trans_details.adt_status=1
					   AND account_transactions.act_status=1
					   AND account_trans_details.adt_ledger_id IN (14)
					   AND account_trans_details.adt_trans_ref_id= '$vendorId'
					   GROUP BY account_trans_details.adt_trans_ref_id
					) as account ON account.adt_trans_ref_id=booking_cab.bcb_vendor_id
					WHERE  booking.bkg_status IN (2,3,5,6,7,9) AND booking_cab.bcb_vendor_id= '$vendorId' ";
		$result	 = DBUtil::command($sql)->queryRow($sql);
		return $result;
	}

	public static function updateOutstanding($vndid)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);

		// Getting Merged VendorIds
		$vndIds = Vendors::getVndIdsByRefCode($vndid);
		if ($vndIds == null || $vndIds == "")
		{
			return false;
		}

		$success = false;
		try
		{
			$sql	 = "SELECT IFNULL(sum(adt_amount),0) outstanding
                FROM   account_trans_details 
                INNER JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
                WHERE  account_trans_details.adt_active = 1 
                AND account_transactions.act_active=1 
                AND account_transactions.act_status=1
                AND account_trans_details.adt_status = 1
                AND account_trans_details.adt_ledger_id = 14 AND act_date >= '2021-04-01 00:00:00' 
                AND account_trans_details.adt_trans_ref_id IN ({$vndIds})";
			$result	 = DBUtil::queryScalar($sql, DBUtil::MDB());

			$vndStatsModel = VendorStats::model()->getbyVendorId($vndid);
			if (!$vndStatsModel)
			{
				$vndStatsModel				 = new VendorStats();
				$vndStatsModel->vrs_vnd_id	 = $vndid;
			}
			$vndStatsModel->vrs_outstanding	 = $result | 0;
			$success						 = $vndStatsModel->save();
		}
		catch (Exception $ex)
		{
			$success = false;
		}

		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	public function getbyVendorId($vendorID)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('vrs_vnd_id', $vendorID);
		//$criteria->compare('vrs_active', 1);
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

	public function updateVendorOutstandingCron()
	{
		try
		{
			$sql	 = "SELECT IFNULL(sum(adt_amount),0) outstanding,account_trans_details.adt_trans_ref_id 
                FROM   account_trans_details 
                INNER JOIN account_transactions ON account_transactions.act_id = account_trans_details.adt_trans_id
                WHERE  account_trans_details.adt_active = 1 AND account_transactions.act_active=1
                AND account_trans_details.adt_status = 1 AND act_date >= '2021-04-01 00:00:00' 
                AND account_trans_details.adt_ledger_id = 14 AND account_trans_details.adt_trans_ref_id IS NOT NULL
				GROUP BY account_trans_details.adt_trans_ref_id";
			$result	 = DBUtil::command($sql)->queryAll($sql);

			foreach ($result as $value)
			{
				$vndStatsModel = VendorStats::model()->getbyVendorId($value['adt_trans_ref_id']);
				if (!$vndStatsModel)
				{
					$vndStatsModel				 = new VendorStats();
					$vndStatsModel->vrs_vnd_id	 = $value['adt_trans_ref_id'];
				}
				$vndStatsModel->vrs_outstanding = $value['outstanding'] | 0;
				$vndStatsModel->save();
			}

			$success = $vndStatsModel->save();
		}
		catch (Exception $ex)
		{
			
		}
	}

	public function getAvgCabsByTrips()
	{
		$sql = "SELECT bcb_vendor_id as vnd_id, coutTrips , coutCabs,  ROUND(coutTrips/coutCabs) AS avgTrips  
				FROM
				(
					SELECT
					   COUNT(DISTINCT booking_cab.bcb_id) AS coutTrips,
					   COUNT(
						   DISTINCT booking_cab.bcb_cab_id
					   ) AS coutCabs,
					   bcb_vendor_id
					FROM
					   `booking_cab`
					INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id
					AND booking.bkg_status IN(6, 7)
					AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
					WHERE
					   DATE(booking.bkg_create_date) > '2015-10-01'
					   AND DATE(booking.bkg_pickup_date) BETWEEN DATE_SUB(NOW(), INTERVAL 20 DAY) AND DATE(NOW()) 
					GROUP BY
					   booking_cab.bcb_vendor_id
					ORDER BY
					  booking.bkg_pickup_date
					DESC
				) a GROUP BY bcb_vendor_id";
		return DBUtil::queryAll($sql);
	}

	public function getDriverAppUseScore()
	{
		$sql = "SELECT v2.vnd_id as vnd_id,ROUND((IF(cntDriverBooking > 0, cntDriverBooking, 0) * 100) /  COUNT(1)) AS score
				FROM  `booking`
				INNER JOIN `booking_cab`
				ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1 AND booking.bkg_status IN(6, 7)
				INNER JOIN `vendors` v2  ON v2.vnd_id = booking_cab.bcb_vendor_id AND v2.vnd_active = 1 AND v2.vnd_id = v2.vnd_ref_code
				LEFT JOIN
				(
					SELECT   booking_cab.bcb_vendor_id, COUNT(DISTINCT booking_trail.btr_bkg_id) AS cntDriverBooking
					FROM `booking`
					INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id AND booking_trail.btr_active = 1
					INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1
					WHERE  booking_trail.btr_drv_score > 100 GROUP BY booking_cab.bcb_vendor_id
				) AS drv ON drv.bcb_vendor_id = v2.vnd_id
				WHERE  booking.bkg_create_date > '2015-10-01 00:00:00'	GROUP BY v2.vnd_id";
		return DBUtil::queryAll($sql, DBUtil::SDB());
	}

	public function getDriverAppUseLast10Score($vndId)
	{

		$sql = "SELECT
					bcb_vendor_id,
					SUM(btr_drv_score) AS ratingScore,
					COUNT(1) AS totalScore,
					ROUND(
						SUM(btr_drv_score) * 100 / COUNT(1)
					) AS score_last_days
				FROM
					(
					SELECT
						booking.bkg_id,
						booking_cab.bcb_vendor_id,
						IF(
							booking_trail.btr_drv_score > 100,
							1,
							0
						) AS btr_drv_score
					FROM
						`booking`
					INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1 AND booking.bkg_status IN(6, 7)
					INNER JOIN `vendors` ON vendors.vnd_id = booking_cab.bcb_vendor_id
					JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
					WHERE
						DATE(booking.bkg_create_date) > '2015-10-01' AND booking_cab.bcb_vendor_id = $vndId
					ORDER BY
						booking.bkg_create_date
					DESC
				LIMIT 0,
				10
				) a
				GROUP BY bcb_vendor_id";
		return DBUtil::queryRow($sql);
	}

	public function saveScore($val)
	{

		$vendorID		 = $val['bcb_vendor_id'];
		$numberOfTrip	 = $val['cnt'];
		$results		 = Ratings::CalculateRating($val, $vendorID);
		$star			 = $results["star"];
		$rating			 = $results["rating"];   // this is trust score value  
		$rating			 = ($rating > 10 ? 10 : $rating);
		$model			 = VendorStats::model()->getbyVendorId($vendorID);
		if ($results["countOld60"] > 0)
		{
			$model->vrs_vnd_overall_rating = round($rating / 2, 1);  // rating = trust score value /2
		}
		else
		{
			$model->vrs_vnd_overall_rating = ($rating > 5 ? 5 : $rating);  //According to AK (01-11-22) if old booking 0 then no division by 2
		}
		$model->vrs_vnd_total_trip	 = $numberOfTrip;
		$model->vrs_trust_score		 = round($rating, 2);
		$model->vrs_no_of_star		 = $star;
		$model->vrs_modified_date	 = new CDbExpression('NOW()');
		if ($model->save())
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

	public function statusCheckAgreement($vnd_id)
	{
		$version = Yii::app()->params['digitalagmtversion'];
		$sql	 = "SELECT
                (
                    CASE WHEN(
                        digital_flag = 1 AND version_chk = 1
                    ) THEN 1 WHEN(
                        soft_flag = 1 AND version_chk = 1
                    ) THEN 1 WHEN(
                        digital_flag = 0 OR version_chk = 0
                    ) THEN 0
                    END
                ) AS is_agmt
                FROM
                (
                    SELECT
                        IF(
                            (
                                vendor_agreement.vag_digital_sign != '' AND vendor_agreement.vag_digital_flag = 1
                            ),
                            1,
                            0
                        ) AS digital_flag,
                        IF(
                            (
                                vendor_agreement.vag_soft_path != '' AND vendor_agreement.vag_soft_flag = 1
                            ),
                            1,
                            0
                        ) AS soft_flag,
                        IF(
                            (
                                vendor_agreement.vag_digital_ver < $version
                            ),
                            0,
                            1
                        ) AS version_chk
                    FROM
                        `vendor_agreement`
                    WHERE
                        vendor_agreement.vag_vnd_id = $vnd_id
                ) a";

		return DBUtil::command($sql)->queryScalar();
	}

	public function statusCheckDocument($vndId)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')
						FROM   vendors v1
						       INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
						       INNER JOIN vendors v3 ON v3.vnd_id = v2.vnd_ref_code
						WHERE  v1.vnd_id = $vndId";
		$vndIds		 = DBUtil::queryScalar($sqlvndIds, DBUtil::SDB());
		if ($vndIds == null || $vndIds == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		DBUtil::getINStatement($vndIds, $bindString, $params);
		$sql = "SELECT
                   IF(
						(COUNT(distinct pandoc.doc_id)>=1) and
                        (
                            COUNT(voterdoc.doc_id) + COUNT(aadhardoc.doc_id)+COUNT(licensedoc.doc_id) 
                        ) >= 1,
                        1,
                        0
                    ) AS is_doc
            FROM
               `vendors`
            INNER JOIN contact_profile cp ON cp.cr_is_vendor = vendors.vnd_id AND cp.cr_status =1
			INNER JOIN contact ctt ON ctt.ctt_id = cp.cr_contact_id AND ctt.ctt_active =1 AND ctt.ctt_id = ctt.ctt_ref_code 
            LEFT JOIN `document` AS voterdoc ON voterdoc.doc_id = `ctt_voter_doc_id` AND voterdoc.doc_type = 2 AND voterdoc.doc_status IN (0,1) AND voterdoc.doc_active = 1
            LEFT JOIN `document` AS aadhardoc ON  aadhardoc.doc_id = `ctt_aadhar_doc_id` AND aadhardoc.doc_type = 3 AND aadhardoc.doc_status IN (0,1) AND aadhardoc.doc_active = 1 
			LEFT JOIN `document` AS licensedoc ON  licensedoc.doc_id = `ctt_license_doc_id` AND licensedoc.doc_type = 5 AND licensedoc.doc_status IN (0,1) AND licensedoc.doc_active = 1 
            LEFT JOIN `document` AS pandoc ON  pandoc.doc_id = `ctt_pan_doc_id` AND pandoc.doc_type = 4 AND pandoc.doc_status IN (0,1) AND pandoc.doc_active = 1
            WHERE vendors.vnd_id IN  ($bindString)  AND vendors.vnd_active>0 group by vendors.vnd_ref_code ";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
	}

	public function statusCheckVehicle($vnd_id)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')
						FROM   vendors v1
						       INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
						       INNER JOIN vendors v3 ON v3.vnd_id = v2.vnd_ref_code
						WHERE  v1.vnd_id = $vnd_id";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		$sql = "SELECT
                IF(
                    COUNT(DISTINCT vendor_vehicle.vvhc_id) >= 1,
                    1,
                    0
                ) AS is_car
                FROM
                    `vendor_vehicle`
                INNER JOIN `vendors` ON vendors.vnd_id = vendor_vehicle.vvhc_vnd_id AND vendors.vnd_active>0
                INNER JOIN `vehicles` ON vehicles.vhc_id= vendor_vehicle.vvhc_vhc_id AND vehicles.vhc_approved IN (0,1,2,4)
                WHERE vendor_vehicle.vvhc_active = 1 AND vendors.vnd_id IN($vndIds)  group by vendors.vnd_ref_code";
		return DBUtil::command($sql)->queryScalar();
	}

	/**
	 * function used to check vehicle disapproved status of particular vendor
	 * @param type $vndId
	 * @return int
	 */
	public static function vehicleStatus($vndId)
	{
		$vndIds				 = Vendors::relatedVndIds($vndId);
		$vehicleStat		 = 1;
		$totalVehicles		 = VendorVehicle::totalVehicle($vndIds);
		$rejectedVehicles	 = VendorVehicle::rejectedVehicle($vndIds);
		if ($totalVehicles != 0 && $totalVehicles == $rejectedVehicles)
		{
			$vehicleStat = 0;
		}

		return $vehicleStat;
	}

	public static function driverStatus($vndId)
	{
		$vndIds		 = Vendors::relatedVndIds($vndId);
		$driverStat	 = 1;

		$totalDriver	 = VendorDriver::totalDriver($vndIds);
		$rejectedDriver	 = VendorDriver::rejectedDriver($vndIds);

		if ($rejectedDriver < 1)
		{
			$rejectedDriver = 0;
		}
		if ($totalDriver == 0)
		{
			$driverStat = 0;
		}
		elseif ($totalDriver == $rejectedDriver)
		{
			$driverStat = 0;
		}
		else
		{
			$driverStat = 1;
		}

		return $driverStat;
	}

	public function statusCheckDriver($vnd_id)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')
						FROM   vendors v1
						       INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
						       INNER JOIN vendors v3 ON v3.vnd_id = v2.vnd_ref_code
						WHERE  v1.vnd_id = $vnd_id";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		$sql = "SELECT
                IF(
                    COUNT(DISTINCT vendor_driver.vdrv_id) >= 1,
                    1,
                    0
                ) AS is_driver
                FROM
                   `vendor_driver`
                INNER JOIN `vendors` ON vendors.vnd_id = vendor_driver.vdrv_vnd_id AND vendors.vnd_active>0 
                INNER JOIN `drivers` ON drivers.drv_id= vendor_driver.vdrv_drv_id  AND drivers.drv_approved IN (0,1,2)
				 WHERE vendor_driver.vdrv_active = 1 AND vendors.vnd_id IN($vndIds)  group by vendors.vnd_ref_code";
		return DBUtil::command($sql)->queryScalar();
	}

	public function getStatusByVndId($vnd_id)
	{
		$sqlvndIds	 = "select group_concat(v3.vnd_id SEPARATOR ',')
						FROM   vendors v1
						       INNER JOIN vendors v2 ON v2.vnd_id = v1.vnd_ref_code
						       INNER JOIN vendors v3 ON v3.vnd_id = v2.vnd_ref_code
						WHERE  v1.vnd_id = $vnd_id";
		$vndIds		 = DBUtil::command($sqlvndIds)->queryScalar();

		$sql = "SELECT
                    (
                        IF(voterdoc.doc_status = 0, 1, 0) +
                        IF(aadhardoc.doc_status = 0, 1, 0) +
                        IF(pandoc.doc_status = 0, 1, 0) +
                        IF(licencedoc.doc_status = 0,1,0)
                    ) AS cout_doc_pending,
                    (
                        IF(voterdoc.doc_status = 1, 1, 0) +
                        IF(aadhardoc.doc_status = 1, 1, 0) +
                        IF(pandoc.doc_status = 1, 1, 0) +
                        IF(licencedoc.doc_status = 1,1,0)
                    ) AS cout_doc_approved,
                    (
                        IF(voterdoc.doc_status = 2, 1, 0) +
                        IF(aadhardoc.doc_status = 2, 1, 0) +
                        IF(pandoc.doc_status = 2, 1, 0) +
                        IF(licencedoc.doc_status = 2,1,0)
                    ) AS cout_doc_rejected
                 FROM
                    `vendors`
                 INNER JOIN `contact` ON `ctt_id` = `vnd_contact_id`
                 LEFT JOIN `document` AS voterdoc
                 ON
                    voterdoc.doc_id = contact.ctt_voter_doc_id  AND voterdoc.doc_type = 2 AND voterdoc.doc_active = 1
                 LEFT JOIN `document` AS aadhardoc
                 ON
                    aadhardoc.doc_id = contact.ctt_aadhar_doc_id AND aadhardoc.doc_type = 3 AND aadhardoc.doc_active = 1
                 LEFT JOIN `document` AS pandoc
                 ON
                    pandoc.doc_id = contact.ctt_pan_doc_id AND pandoc.doc_type = 4 AND pandoc.doc_active = 1
                 LEFT JOIN `document` AS licencedoc
                 ON
                    licencedoc.doc_id = contact.ctt_license_doc_id AND licencedoc.doc_type = 5 AND licencedoc.doc_active = 1
                 WHERE
                    vendors.vnd_id IN($vndIds)  group by vendors.vnd_ref_code ";

		return DBUtil::queryRow($sql);
	}

	public static function fetchRating($vnd_id)
	{
		$sql = "SELECT vrs_vnd_overall_rating
					FROM `vendor_stats`  WHERE vrs_vnd_id='$vnd_id'";

		$row	 = DBUtil::queryRow($sql);
		$rating	 = ($row['vrs_vnd_overall_rating'] > 0) ? $row['vrs_vnd_overall_rating'] : '4.00';
		return $rating;
	}

	public function insertEmptyStats($type = 0, $vnd_id = 0)
	{
		// 0 : count cars and drivers for vendors
		// 1 : get last logged in for vendors

		switch ($type)
		{
			case 0:
				$where	 = $vnd_id > 0 ? " and  vnd_id=$vnd_id " : "";
				$sql	 = "SELECT
						vendors.vnd_id
						FROM `vendors` 
						LEFT JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id=vendors.vnd_id                           
						WHERE vendors.vnd_active > 0 AND vendor_stats.vrs_Id IS NULL $where
						GROUP By vendors.vnd_id";
				break;
			case 1:
				$where	 = $vnd_id > 0 ? " and  apt_entity_id=$vnd_id " : "";
				$sql	 = "SELECT   app_tokens.apt_entity_id as vnd_id, MAX(app_tokens.apt_last_login) AS last_login
						FROM     `app_tokens` LEFT JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = app_tokens.apt_entity_id
						WHERE    app_tokens.apt_user_type = 2 AND app_tokens.apt_user_id IS NOT NULL AND app_tokens.apt_entity_id IS NOT NULL AND
								 app_tokens.apt_status =1 AND vendor_stats.vrs_id IS NULL AND app_tokens.apt_entity_id > 0  $where
						GROUP BY vrs_vnd_id";
				break;
		}
		$rows	 = DBUtil::query($sql);
		$count	 = count($rows);
		if ($count > 0)
		{
			foreach ($rows as $row)
			{
				$model				 = new VendorStats();
				$model->vrs_vnd_id	 = $row['vnd_id'];
				$model->save();
			}
		}
		return $count;
	}

	public function countVendorVehicleTypeWise($vndId = 0)
	{
		$condition = '';
		if ($vndId > 0)
		{
			$condition = ' AND vv.vvhc_vnd_id =' . $vndId . ' ';
		}
		$sql	 = "SELECT   vv.vvhc_vnd_id, vcv.vcv_vct_id, COUNT(vcv.vcv_vct_id) AS cnt
					FROM     `vendor_vehicle` vv
							 INNER JOIN vehicles v
							   ON vv.vvhc_vhc_id = v.vhc_id AND vhc_active IN (1, 2, 3)  AND vv.`vvhc_active` = 1 $condition 
							 INNER JOIN vehicle_types vt ON v.vhc_type_id = vt.vht_id AND vt.vht_active = 1
							 INNER JOIN vcv_cat_vhc_type vcv ON vcv.vcv_vht_id = vt.vht_id AND vcv.vcv_vct_id NOT IN (4,10,12,13,14)
					GROUP BY vv.vvhc_vnd_id, vcv.vcv_vct_id";
		$result	 = DBUtil::queryAll($sql);
		return $result;
	}

	public function countVendorDriver($vndId = 0)
	{
		$condition = '';
		if ($vndId > 0)
		{
			$condition = 'AND vendor_driver.vdrv_vnd_id =' . $vndId;
		}
		$sql = "SELECT
                    vendor_driver.vdrv_vnd_id,
                    COUNT(DISTINCT d2.drv_id) AS count_drivers
                    FROM  drivers d2
                    INNER JOIN vendor_driver ON vendor_driver.vdrv_drv_id = d2.drv_id AND vendor_driver.vdrv_active > 0
                    WHERE
                        d2.drv_active > 0 $condition
                    GROUP BY
                        vendor_driver.vdrv_vnd_id ";
		return DBUtil::queryAll($sql);
	}

	public function updateCarTypeCount($vndId = 0)
	{
		$arrVendorCarData	 = array();
		$rows				 = VendorStats::model()->countVendorVehicleTypeWise($vndId);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ($row['vvhc_vnd_id'] > 0)
				{
					$vndID	 = $row['vvhc_vnd_id'];
					$carType = $row['vcv_vct_id'];
					$cnt	 = $row['cnt'];
					$field	 = "";
					switch ($carType)
					{
						case 1:
							$field	 = ' vrs_car_reg_compact_cnt =';
							break;
						case 2:
							$field	 = 'vrs_car_reg_suv_cnt =';
							break;
						case 3:
							$field	 = 'vrs_car_reg_sedan_cnt =';
							break;
						case 5:
							$field	 = 'vrs_car_reg_sedan_cnt = vrs_car_reg_sedan_cnt +';  //+= $row['cnt'];
							break;
						case 6:
							$field	 = 'vrs_car_reg_suv_cnt = vrs_car_reg_suv_cnt +'; // += $row['cnt'];
							break;
						case 7:
							$field	 = 'vrs_car_reg_tempo9_cnt ='; // += $row['cnt'];
							break;
						case 8:
							$field	 = 'vrs_car_reg_tempo12_cnt =';
							break;
						case 9:
							$field	 = 'vrs_car_reg_tempo15_cnt =';
							break;
						case 11;
							$field	 = 'vrs_car_reg_sedan_cnt = vrs_car_reg_sedan_cnt +';
							break;
						default:
							break;
					}
					$arrVendorCarData[$vndID][$field] = $cnt;
				}
			}
			foreach ($arrVendorCarData as $vendorId => $arrVendorCarCount)
			{
				$sql = "UPDATE vendor_stats SET ";
				foreach ($arrVendorCarCount as $vendorCarFieldName => $carCount)
				{
					$sql .= " $vendorCarFieldName $carCount,";
				}
				$sql = rtrim($sql, ',') . " WHERE vrs_vnd_id = $vendorId";
				DBUtil::command($sql)->execute();
			}
		}
	}

	public function updateCountDrivers($vndId = 0)
	{
		$result = VendorStats::model()->countVendorDriver($vndId);
		if (count($result) > 0)
		{
			foreach ($result as $value)
			{
				if ($value['vdrv_vnd_id'] > 0)
				{
					$params	 = array('vndId' => $value['vdrv_vnd_id'], 'countDrv' => $value['count_drivers']);
					$sql	 = "UPDATE `vendor_stats` SET `vrs_drivers_reg_cnt` = :countDrv WHERE vrs_vnd_id = :vndId";
					DBUtil::command($sql)->execute($params);
				}
			}
		}
	}

	public static function updateScoreR4A($arr)
	{
		$countDocs		 = 0;
		$isAgmtApprove	 = $isPanApprove	 = $isVoterApprove	 = $isAadharApprove = $isLicApprove	 = $isDocApprove	 = $isDriverCount	 = $isCarCount		 = 0;
		if (($arr['adoc_id'] > 0) && ($arr['ctt_aadhar_doc_id'] > 0) && ($arr['adoc_id'] == $arr['ctt_aadhar_doc_id']))
		{
			//$countDocs ++;
			$isAadharApprove = 1;
		}
		if (($arr['vdoc_id'] > 0) && ($arr['ctt_voter_doc_id'] > 0) && ($arr['vdoc_id'] == $arr['ctt_voter_doc_id']))
		{
			//$countDocs ++;
			$isVoterApprove = 1;
		}
		if ($isAadharApprove == 1 || $isVoterApprove == 1)
		{
			$countDocs++;
		}
		if (($arr['pdoc_id'] > 0) && ($arr['ctt_pan_doc_id'] > 0) && ($arr['pdoc_id'] == $arr['ctt_pan_doc_id']))
		{
			$countDocs++;
			$isPanApprove = 1;
		}
		if (($arr['ldoc_id'] > 0) && ($arr['ctt_license_doc_id'] > 0) && ($arr['ldoc_id'] == $arr['ctt_license_doc_id']))
		{
			$countDocs++;
			$isLicApprove = 1;
		}
		if (($arr['vag_id'] > 0) && ($arr['vag_digital_ver'] >= '171219'))
		{
			$countDocs++;
			$isAgmtApprove = 1;
		}
		if ($isVoterApprove == 1 || $isAadharApprove == 1 || $isLicApprove == 1)
		{
			$isDocApprove = 1;
		}
		if (isset($arr['vrs_approve_driver_count']) && $arr['vrs_approve_driver_count'] > 0)
		{
			$isDriverCount = 1;
		}
		if (isset($arr['vrs_approve_car_count']) && $arr['vrs_approve_car_count'] > 0)
		{
			$isCarCount = 1;
		}
		$modelv = VendorStats::model()->getbyVendorId($arr['vnd_id']);
		if (!$modelv)
		{
			$modelv				 = new VendorStats();
			$modelv->vrs_vnd_id	 = $arr['vnd_id'];
		}
		$modelv->vrs_docs_score	 = $countDocs;
		$modelv->vrs_docs_r4a	 = ($isAgmtApprove == 1 && $isDocApprove == 1 && $isPanApprove == 1 && $isDriverCount == 1 && $isCarCount == 1 && $arr['r4a'] == 1) ? 1 : 0;
		if ($modelv->save())
		{
			$arr = array('score'	 => $modelv->vrs_docs_score,
				'r4a'	 => $modelv->vrs_docs_r4a
			);
		}
		return $arr;
	}

	public function updateVerndorLastBkgComp($vndID)
	{
		$model							 = $this->getbyVendorId($vndID);
		$model->vrs_last_bkg_cmpleted	 = new CDbExpression('NOW()');
		$model->save();
	}

	public function setLockedAmount()
	{
		$success = false;
		try
		{
			$vendorId						 = $this->vrs_vnd_id;
			$rating							 = ($this->vrs_vnd_overall_rating > 0) ? round($this->vrs_vnd_overall_rating) : 4;
			/*
			 * logic,Get value of Vendors existing rating = VR and round it
			 * A = % of VendorTripAmount of all bookings he has completed in the last n days.  
			 * A = [ ( [ 5 - Round(VR,0) ] 10% ) +5% ] of VendorTripAmount of all bookings he has completed in the last 
			 * [ (Round([5 - VR ],0) 1 ) + 4 ] days.
			 */
			$percentage						 = (((5 - $rating) * 0.1) + 0.05);
			$days							 = (((5 - $rating) * 1) + 4);
			$pendingSettlementAmount		 = AccountTransactions::model()->getTotalVendorAmount($vendorId) | 0;
			$vendorRow						 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
			$vendorBalance					 = -1 * $vendorRow['vendor_amount'];
			$getLast5daysVendorAmount		 = AccountTransDetails::getVendorAmountforLastNDays($vendorId, $days, $percentage) | 0;
			$nextDays						 = 3;
			$getNext3daysGozoAmount			 = AccountTransDetails::getNextNdaysGozoAmount($vendorId, $nextDays);
			$maxval							 = max([$pendingSettlementAmount, $getLast5daysVendorAmount]);
			$totalSum						 = max([($maxval + $getNext3daysGozoAmount), $maxval]);
			$lockbalance					 = max([$totalSum - $vendorRow['vnd_security_amount'], 0]);
			$Withdrawable_Balance			 = ($vendorRow['vnp_is_freeze'] != 0 || $vendorRow['vnd_active'] != 1) ? 0 : max([$vendorBalance - $lockbalance, 0]);
			$this->vrs_locked_amount		 = $lockbalance;
			$this->vrs_withdrawable_balance	 = $Withdrawable_Balance;
			$success						 = $this->save();
			if (!$success)
			{
				throw new Exception();
			}
		}
		catch (Exception $ex)
		{
			$ex->getMessage();
		}
		return $success;
	}

	public function updateTripPendingCount()
	{
		$vendorIds	 = "";
		$sql		 = "SELECT
					booking_cab.bcb_vendor_id,
					COUNT(DISTINCT bkg_id) AS pending_count
				FROM
					booking
				INNER JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id
				INNER JOIN booking_pref ON booking.bkg_id = booking_pref.bpr_bkg_id
				INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
				WHERE
					bkg_status = 5 AND booking_track.bkg_ride_complete = 0 AND booking_cab.bcb_vendor_id IS NOT NULL AND DATE_ADD(
						bkg_pickup_date,
						INTERVAL(
							IF(
								`bkg_trip_duration` = '' OR `bkg_trip_duration` IS NULL,
								300,
								`bkg_trip_duration`
							)
						) MINUTE
					) < DATE(NOW()) 

				GROUP BY booking_cab.bcb_vendor_id
				ORDER BY
				   booking_cab.bcb_vendor_id ASC";
		$result		 = DBUtil::queryAll($sql, DBUtil::SDB());

		if (count($result) > 0)
		{
			foreach ($result as $key => $value)
			{
				$modelStat									 = $this->getbyVendorId($value['bcb_vendor_id']);
				$modelStat->vrs_trip_status_pending_count	 = $value['pending_count'];
				$modelStat->save();
				$vendorIds									 .= $value['bcb_vendor_id'] . ',';
			}
			$vendorIds = substr($vendorIds, 0, -1);

			$updatesql	 = "UPDATE vendor_stats SET vrs_trip_status_pending_count = 0 WHERE vrs_trip_status_pending_count > 0  AND vrs_vnd_id NOT IN (" . $vendorIds . ")";
			$success	 = DBUtil::command($updatesql)->execute();
		}
	}

	public function sendMarkCompleteReminder()
	{
		$sql	 = "SELECT `vrs_vnd_id` FROM `vendor_stats` WHERE `vrs_trip_status_pending_count` > 0 ";
		$result	 = DBUtil::queryAll($sql);

		if ($result != '')
		{
			foreach ($result as $key => $value)
			{
				$message	 = "Please remind your driver(s) to start and stop trip in Gozo Driver app. Use Driver app to avoid payment delays. Help us release payments quickly!";
				$payLoadData = ['Status' => '5', 'FilterCode' => 2, 'EventCode' => Booking::CODE_PENDING];
				$success	 = AppTokens::model()->notifyVendor($value['vrs_vnd_id'], $payLoadData, $message, "Trip status update pending. Provide updates");
				if ($success)
				{
					$userInfo	 = UserInfo::getInstance();
					/* @var $vndLog VendorsLog */
					$vndLog		 = new VendorsLog();
					$desc		 = "Vendor reminded to update trip status.";
					$event_id	 = VendorsLog::VENDOR_UPDATE_TRIP_STATUS_REMINDER;
					$vndLog->createLog($value['vrs_vnd_id'], $desc, $userInfo, $event_id, false, false);
					$log		 = $value['vrs_vnd_id'] . " -> " . $desc;
					echo "\n" . $log;
				}
			}
		}
	}

	/**
	 * 
	 * @param integer $trips
	 * @param float $rating
	 * @return query object
	 */
	public static function getGoldenTierList($trips = 25, $rating = 4.0, $type = 0)
	{
		$where = "";
		if ($type == 0)
		{
			$where = " AND vendor_stats.vrs_vnd_overall_rating > :rating AND vendor_stats.vrs_total_trips > :trips AND vendor_pref.vnp_deny_tire_upgrade = 0 AND vendors.vnd_rel_tier = 0 ";
		}
		else if ($type == 1)
		{
			$where = " 	AND vendor_stats.vrs_vnd_overall_rating < :rating AND vendor_stats.vrs_total_trips >:trips  AND vendors.vnd_rel_tier = 1 ";
		}
		$sql = "SELECT vendors.vnd_id
				FROM  `vendors`
				JOIN `vendor_stats` ON vendor_stats.vrs_vnd_id = vendors.vnd_id
				JOIN `vendor_pref` ON vendor_pref.vnp_vnd_id = vendors.vnd_id
				WHERE   1 AND vendors.vnd_active = 1  	$where";
		return DBUtil::query($sql, DBUtil::SDB(), ['rating' => $rating, 'trips' => $trips]);
	}

	public static function getTotalBookingCount($vndID)
	{
		$sql	 = "SELECT 
					COUNT(DISTINCT bkg_id) as countTotalBookings
				FROM
					booking
				INNER JOIN booking_cab ON booking.bkg_id = booking_cab.bcb_bkg_id1 AND booking_cab.bcb_vendor_id = $vndID
				INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
				WHERE
					booking.bkg_status = 3 OR(
						(
							booking_track.bkg_ride_start = 1 AND booking_track.bkg_ride_complete = 0 AND booking.bkg_status = 5
						) OR(
							booking_track.bkg_ride_start = 0 AND booking_track.bkg_ride_complete = 0 AND booking.bkg_status = 5
						)
					)
				ORDER BY
					`booking`.`bkg_id`
				DESC";
		$result	 = DBUtil::queryRow($sql);

		if ($result != '')
		{
			return $result['countTotalBookings'];
		}
		else
		{
			throw new Exception("No records found", 114);
		}
	}

	public static function getDriverAssignmentPending($vndID)
	{
		$sql	 = "SELECT
					COUNT(DISTINCT bkg_id) AS countNewAssignment
					FROM
						booking
					INNER JOIN booking_cab ON booking.bkg_id = booking_cab.bcb_bkg_id1 AND booking_cab.bcb_vendor_id = $vndID
					INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
					WHERE  booking.bkg_status = 3 AND booking_cab.bcb_id  = booking.bkg_bcb_id";
		$result	 = DBUtil::queryRow($sql);
		if ($result != '')
		{
			return $result['countNewAssignment'];
		}
		else
		{
			throw new Exception("No records found", 114);
		}
	}

	public static function getBookingOverDue($vndID)
	{
		$sql	 = "SELECT
					COUNT(DISTINCT`bkg_id`) as countOverDue
				FROM
					`booking`
				INNER JOIN booking_cab ON booking_cab.bcb_bkg_id1 = booking.bkg_id
				INNER JOIN booking_track ON booking_track.btk_bkg_id = booking.bkg_id
				WHERE
					(
						(
							booking.bkg_pickup_date < NOW() AND booking_track.bkg_ride_start = 0 AND booking_track.bkg_ride_complete = 0) OR(
								DATE_ADD(
									bkg_pickup_date,
									INTERVAL(
										IF(
											`bkg_trip_duration` = '' OR `bkg_trip_duration` IS NULL,
											300,
											`bkg_trip_duration`
										)
									) MINUTE
								) < DATE(NOW()) AND booking_track.bkg_ride_start = 1 AND booking_track.bkg_ride_complete = 0)
							) AND booking_cab.bcb_vendor_id = $vndID AND booking.bkg_status IN (3,5)";
		$result	 = DBUtil::queryRow($sql);
		if ($result != '')
		{
			return $result['countOverDue'];
		}
		else
		{
			throw new Exception("No records found", 114);
		}
	}

	public static function updateStickyScore($vendor_id, $date = null)
	{
		if ($date == null)
		{
			//$date = new CDbExpression('NOW()');
			$date = date('Y-m-d h:i:s');
		}
		$params = [
			'vendorId'	 => $vendor_id,
			'date'		 => $date
		];

		$sql2 = "SELECT COUNT(DISTINCT(bcb_cab_id)) as total_car FROM booking_cab
						WHERE bcb_vendor_id=:vendorId
							  AND bcb_id IN (SELECT bkg_bcb_id FROM booking WHERE bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 30 DAY) AND :date AND bkg_status IN (6,7))";

		$totalCar = DBUtil::command($sql2)->queryScalar($params);

		$sql3 = "SELECT SUM(GREATEST(CEIL(TIMESTAMPDIFF(HOUR, bkg_pickup_date, DATE_ADD(bkg_pickup_date, INTERVAL IF(bkg_trip_duration IS NULL OR bkg_trip_duration=0,10,bkg_trip_duration) MINUTE))/24),1)) AS total_days
					FROM booking_cab
                     INNER JOIN booking as bkg ON bkg.bkg_bcb_id = booking_cab.bcb_id
                     WHERE bcb_vendor_id=:vendorId  AND bkg_pickup_date BETWEEN DATE_SUB(:date, INTERVAL 30 DAY) AND :date AND bkg.bkg_status IN (6,7)";

		$tripDays = DBUtil::command($sql3)->queryScalar($params);

		$stickyScore = (($tripDays / $totalCar) > 16 ? (($tripDays / $totalCar) % 16) / 16 : (($tripDays / $totalCar) / 16));

		if ($totalCar >= 5)
		{
			$stickyScorePercentage = ($stickyScore * 100) / $totalCar;
		}
		else
		{
			$stickyScorePercentage = $stickyScore * 100;
		}
		$params = [
			'tripDays'		 => $tripDays,
			'totalCar'		 => $totalCar,
			'stickyScore'	 => round($stickyScorePercentage, 2),
			'vendorId'		 => $vendor_id
		];
		if ($tripDays != null && $totalCar != null)
		{
			$sqlUpdate = 'UPDATE `vendor_stats` SET vrs_total_completed_days_30 = :tripDays , vrs_total_vehicle_30 = :totalCar , vrs_sticky_score=:stickyScore WHERE vrs_vnd_id=:vendorId';

			$result = DBUtil::command($sqlUpdate)->execute($params);
			return $result;
		}
	}

	public static function showStickyScore($vendor_id)
	{
		$params = [
			'vendorId' => $vendor_id
		];

		$sql = "SELECT vrs_sticky_score FROM vendor_stats WHERE vrs_vnd_id =:vendorId";

		$vendorStickyScore = DBUtil::command($sql)->queryScalar($params);
		return $vendorStickyScore;
	}

	/* public function driverAppused($vendor_id)
	  {


	  $sql = "SELECT bkg_bcb_id, bkg_id,btl_event_platform,btl_event_type_id
	  FROM booking bkg
	  INNER JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id AND bcb.bcb_active = 1
	  LEFT JOIN booking_track_log ON `bkg_id` = btl_bkg_id
	  WHERE bkg.bkg_status IN (6,7) AND bkg.bkg_active = 1 AND bkg.bkg_pickup_date BETWEEN(NOW() - INTERVAL 180 DAY) AND NOW()
	  AND bcb.bcb_vendor_id =" . $vendor_id . "  GROUP BY bkg_bcb_id";


	  $record			 = DBUtil::command($sql)->queryAll();
	  $totalBooking	 = count($record);

	  $countPlatform = 0;
	  foreach ($record as $res)
	  {
	  $platform = $res['btl_event_platform'];
	  if ($platform == 5)
	  {
	  $countPlatform = $countPlatform + 1;
	  }
	  }
	  #echo $countPlatform.'####'.$totalBooking.'##########';
	  $driverAppPercentage = ($countPlatform / $totalBooking) * 100;
	  //echo round($driverAppPercentage,0,2);

	  if ($driverAppPercentage > 0)
	  {
	  $driverAppPercentage = (float) number_format($driverAppPercentage, 2);
	  $params				 = [
	  'appUsed'	 => $driverAppPercentage,
	  'vendorId'	 => $vendor_id
	  ];
	  $sqlUpdate			 = 'UPDATE `vendor_stats` SET  vrs_driver_app_used=:appUsed WHERE vrs_vnd_id=:vendorId';
	  $result				 = DBUtil::command($sqlUpdate)->execute($params);
	  return $result;
	  }
	  }
	 */

	public function driverAppused($vendor_id)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT bkg_id)AS booking_ids
			FROM booking bkg
			INNER JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id AND bcb.bcb_active = 1
			LEFT JOIN booking_track_log ON `bkg_id` = btl_bkg_id  
			WHERE bkg.bkg_status IN (6,7) AND bkg.bkg_active = 1 AND bkg.bkg_pickup_date BETWEEN(NOW() - INTERVAL 180 DAY) AND NOW()
			AND bcb.bcb_vendor_id =" . $vendor_id . " ";

		#echo $sql;
		$result = DBUtil::queryRow($sql);

		$resultString = $result['booking_ids'];

		$resultArr		 = explode(",", $resultString);
		# print_r($resultArr);
		$totalBooking	 = count($resultArr);
		if ($resultString != "")
		{
			$sql1				 = "SELECT distinct(btl_bkg_id) FROM booking_track_log  WHERE btl_bkg_id  IN(" . $resultString . ")  AND btl_event_platform=5 
                    AND booking_track_log.btl_event_type_id IN(101,104) GROUP BY btl_bkg_id HAVING COUNT(DISTINCT btl_event_type_id) = 2";
			$record				 = DBUtil::command($sql1)->queryAll();
			$totalDriverAppUse	 = count($record);
			$driverAppPercentage = ($totalDriverAppUse / $totalBooking) * 100;
			if ($driverAppPercentage > 0)
			{
				$driverAppPercentage = (float) number_format($driverAppPercentage, 2);
				$params				 = [
					'appUsed'	 => $driverAppPercentage,
					'vendorId'	 => $vendor_id
				];
				$sqlUpdate			 = 'UPDATE `vendor_stats` SET  vrs_driver_app_used=:appUsed WHERE vrs_vnd_id=:vendorId';
				$result				 = DBUtil::command($sqlUpdate)->execute($params);
				return $result;
			}
		}
	}

	/* @deprecated
	 * new function updatePenaltyCount
	 */

	public function penaltyRating()
	{
		$date	 = date('Y-m-d');
		$sql	 = "SELECT vrs_vnd_id FROM `vendor_stats` WHERE `vrs_last_bkg_cmpleted` BETWEEN DATE_SUB('" . $date . "', INTERVAL 7 DAY) AND '" . $date . "'";

		$record = DBUtil::command($sql)->queryAll();

		foreach ($record as $res)
		{
			$vendorId	 = $res['vrs_vnd_id'];
			$bookingId	 = array();

			$sql2 = "select  group_CONCAT(temp.bkg_id) as booking_ids FROM (          
          SELECT bkg.bkg_id FROM booking_cab 
					INNER JOIN booking as bkg ON bkg.bkg_bcb_id = booking_cab.bcb_id
					 WHERE bcb_vendor_id = " . $vendorId . "
					AND bkg.bkg_status in(5,6,7,9)
			
					ORDER BY bkg.`bkg_pickup_date` DESC
					LIMIT 20) temp";

			$result			 = DBUtil::queryRow($sql2);
			#print_r($result);
			$resultString	 = $result['booking_ids'];
			#echo $resultString;

			if ($resultString != "")
			{

				$sql3	 = "SELECT COUNT(penaltyId)as count FROM `booking_penalties` WHERE bookingId IN(" . $resultString . ") AND `penaltyReasonId` BETWEEN 1 AND 6 ";
				$penalty = DBUtil::queryRow($sql3);

				if ($penalty['count'] > 0)
				{
					$params = [
						'penaltyCount'	 => $penalty['count'],
						'vendorId'		 => $vendorId
					];

					$sqlUpdate	 = 'UPDATE `vendor_stats` SET  vrs_penalty_count=:penaltyCount WHERE vrs_vnd_id=:vendorId';
					$result		 = DBUtil::command($sqlUpdate)->execute($params);
				}
			}
		}
	}

	/**
	 *  calculate and update total penalty of active vendor who recently serve booking
	 */
	public function updatePenaltyCount()
	{


		$sql	 = "SELECT group_concat(bkg_id) as booking_ids, bcb.bcb_vendor_id FROM booking bkg 
		    INNER JOIN booking_cab bcb ON bkg_id = bcb_bkg_id1 
		    WHERE bcb_vendor_id > 0 AND bkg_status IN (2,3,5,6,7,9) AND bkg.bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 21 DAY) AND NOW() 
		    GROUP BY bcb.bcb_vendor_id ";
		$records = DBUtil::query($sql);
		foreach ($records as $row)
		{

			$bookingIds	 = $row['booking_ids'];
			$vendorId	 = $row['bcb_vendor_id'];

			$params2 = ['bookingIds' => $bookingIds,
				'vendor'	 => $vendorId];

			$sql2 = "SELECT COUNT(act.act_id) AS penalty_count
			FROM account_trans_details adt
		       INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 14 AND adt.adt_amount > 0
		       INNER JOIN account_trans_details adt1 ON act.act_id = adt1.adt_trans_id AND adt1.adt_ledger_id = 28 AND adt1.adt_amount < 0
		       WHERE act.act_active = 1 AND adt.adt_active = 1 AND adt1.adt_trans_ref_id IN($bookingIds) AND adt.adt_trans_ref_id =$vendorId AND adt1.adt_type=1";

			$penaltyCount = DBUtil::command($sql2)->queryScalar($params2);

			$this->updateVendorPenalty($penaltyCount, $vendorId);
		}
	}

	/**
	 * update penalty of particular vendor
	 * @param type $penaltyCount
	 * @param type $vendorId
	 */
	public function updateVendorPenalty($penaltyCount, $vendorId)
	{
		$params = ['penaltyCount'	 => $penaltyCount,
			'vendorId'		 => $vendorId];

		$sqlUpdate = 'UPDATE `vendor_stats` SET  vrs_penalty_count=:penaltyCount WHERE vrs_vnd_id=:vendorId';

		$result = DBUtil::command($sqlUpdate)->execute($params);
	}

	/**
	 * show vendor who serve booking for particular days 
	 * @param type $days
	 * @return vendorIds
	 */
	public function showVendorServeBooking($days)
	{
		$sql = "SELECT distinct(bcb.bcb_vendor_id)as vendor_id
		   FROM booking_cab bcb
		   INNER JOIN booking bkg ON bkg.bkg_bcb_id= bcb.bcb_id AND bkg. bkg_status IN (5,6,7)
		   AND bkg.bkg_create_date BETWEEN DATE_SUB(now(), INTERVAL $days DAY) AND now()";

		$records = DBUtil::query($sql);
		return $records;
	}

	public function calculateMargin()
	{
		$sql	 = "SELECT vnd_id FROM vendors WHERE vnd_active=1";
		$record	 = DBUtil::command($sql)->queryAll($sql);
		foreach ($record as $res)
		{

			$result = $this->calculateBookingMargin($res['vnd_id']);
			$this->updateVendorMarginBooking($result);
		}
	}

	public function updateVendorMarginBooking($result)
	{
		if ($result['totalBooking'] > 0)
		{
			$params = ['total_booking'	 => $result['totalBooking'],
				'vrs_margin'	 => round($result['margin'], 2),
				'vendorId'		 => $result['vendorId']];

			#print_r($params);

			$sqlUpdate	 = 'UPDATE `vendor_stats` SET  vrs_total_booking=:total_booking,vrs_margin=:vrs_margin WHERE vrs_vnd_id=:vendorId';
			$result		 = DBUtil::command($sqlUpdate)->execute($params);
		}
	}

	public function calculateBookingMargin($vendorId)
	{

		$sql				 = "SELECT count(biv_bkg_id)as totalBooking, sum(`bkg_gozo_amount`)as totalGozoAmount, sum(`bkg_base_amount`)as totalBaseAmmount FROM `booking_invoice` 
					WHERE `biv_bkg_id` IN (SELECT `bcb_bkg_id1` FROM `booking_cab` INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id 
					AND bkg_status IN (6,7) WHERE `bcb_vendor_id` = $vendorId) AND `bkg_base_amount` IS NOT NULL";
		#echo $sql;
		$res				 = DBUtil::queryRow($sql);
		$totalBooking		 = $res['totalBooking'];
		$gozoAmountTotal	 = $res['totalGozoAmount'];
		$baseAmmountTotal	 = $res['totalBaseAmmount'];
		$margin				 = ($gozoAmountTotal / $baseAmmountTotal) * 100;
		$arr				 = array("totalBooking" => $totalBooking, "margin" => $margin, "vendorId" => $vendorId);
		return $arr;
	}

	public function calculatePerdayMargin()
	{
		$date	 = date('Y-m-d H:i:s');
		#$date =date('2020-02-24 00:00:00');
		$sql	 = "SELECT vrs_vnd_id FROM `vendor_stats` WHERE vrs_last_bkg_cmpleted BETWEEN DATE_SUB('" . $date . "', INTERVAL 1 DAY) AND '" . $date . "'";

		$result = DBUtil::queryAll($sql);
		foreach ($result as $res)
		{
			$vendorId			 = $res['vrs_vnd_id'];
			//calculate indivisual vendor all booking margin for current date
			$resArr				 = $this->indMargin($vendorId);
			$currentBookingCount = $resArr['totalBooking'];
			$marginSum			 = $resArr['margin'];
			//calculation for margin
			if ($currentBookingCount > 0)
			{
				$sql1				 = "SELECT vrs_total_booking,vrs_margin FROM `vendor_stats` WHERE vrs_vnd_id=$vendorId ";
				$res				 = DBUtil::command($sql1)->queryRow();
				$vrsBookingCount	 = $res['vrs_total_booking'];
				$vrsmarginCount		 = $res['vrs_margin'];
				$totalBookingCount	 = $currentBookingCount + $vrsBookingCount;
				//calculation
				$calMargin			 = (($vrsBookingCount * $vrsmarginCount) + $marginSum) / $totalBookingCount;
				$result				 = array("totalBooking" => $totalBookingCount, "margin" => $calMargin, "vendorId" => $vendorId);
				$this->updateVendorMarginBooking($result);
			}
		}
	}

	public function indMargin($vendorId)
	{
		$date				 = date('Y-m-d H:i:s');
		//margin calculation
		$sql2				 = "SELECT `bkg_id` FROM `booking_cab` 
					INNER JOIN booking as bkg ON bkg.bkg_bcb_id = booking_cab.bcb_id
					 WHERE `bcb_vendor_id` = $vendorId
					AND bkg.bkg_status in(6,7) 
					AND bcb_end_time BETWEEN DATE_SUB('" . $date . "', INTERVAL 1 DAY) AND '" . $date . "'";
		$record				 = DBUtil::command($sql2)->queryAll($sql2);
		//total_number of booking
		$totalBooking		 = count($record);
		$booking_margin_sum	 = 0;
		foreach ($record as $res)
		{
			$bookingId			 = $res['bkg_id'];
			//calculate indivisual  booking margin
			$booking_margin		 = $this->indBookingMargin($bookingId);
			$booking_margin_sum	 = $booking_margin_sum + $booking_margin;
		}
		$arr = array("totalBooking" => $totalBooking, "margin" => $booking_margin_sum);

		return $arr;
		//sum of total margin
	}

	public function indBookingMargin($bookingId)
	{
		$sql		 = "SELECT bkg_gozo_amount, bkg_base_amount FROM `booking_invoice` WHERE `biv_bkg_id`=$bookingId";
		$res		 = DBUtil::queryRow($sql);
		$gozoAmount	 = $res['bkg_gozo_amount'];
		$baseAmmount = $res['bkg_base_amount'];
		$margin		 = ($gozoAmount / $baseAmmount) * 100;
		return $margin;
	}

	public function getStickyCount($date1 = '', $date2 = '', $zoneID = '', $region = '', $states = '', $type = '')
	{
		$zoneJoin	 = "";
		$stateJoin	 = "";
		if ($date1 != '' && $date2 != '')
		{
			$cond = "AND bkg.bkg_pickup_date BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
		}
		else
		{
			$cond = " AND bkg.bkg_pickup_date BETWEEN ('" . $date2 . "' - INTERVAL 30 DAY) AND '" . $date2 . "'";
		}
		if ($zoneID != '')
		{
			$zoneJoin	 = " INNER JOIN vendor_pref vnp ON vnd.vnd_id = vnp.vnp_vnd_id 
                             LEFT JOIN zones zon  ON vnp.vnp_home_zone = zon.zon_id 
                           ";
			$zonCond	 = ' AND zon.zon_id IN(' . $zoneID . ')';
		}
		else
		{
			$zonCond = '';
		}

		if ($region != '')
		{
			$stateFlag	 = " 
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1
			INNER JOIN contact ctt ON ctt.ctt_id =cp.cr_contact_id AND ctt.ctt_active =1 AND ctt.ctt_id = ctt.ctt_ref_code
			LEFT JOIN cities cty ON ctt.ctt_city = cty.cty_id
			LEFT JOIN states stt ON stt.stt_id = cty.cty_state_id
            ";
			$regCond	 = ' AND stt.stt_zone IN(' . $region . ')';
		}
		else
		{
			$regCond = '';
		}

		if ($states != '')
		{
			$stateFlag	 = " 
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1
			INNER JOIN contact ctt ON ctt.ctt_id =cp.cr_contact_id AND ctt.ctt_active =1 AND ctt.ctt_id = ctt.ctt_ref_code
			LEFT JOIN cities cty ON ctt.ctt_city = cty.cty_id
			LEFT JOIN states stt ON stt.stt_id = cty.cty_state_id 
			";
			$sttCond	 = 'AND stt.stt_id IN(' . $states . ')';
		}
		else
		{
			$sttCond = '';
		}
		$sql = "SELECT
			' ' AS region,
			vnd.vnd_name,
			stt.stt_name AS state,
				 vnp.vnp_home_zone,
			COUNT(DISTINCT bkg_bcb_id) AS Count_Trips,
			vrs.vrs_approve_car_count
			FROM booking bkg
			INNER JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id AND bcb.bcb_active = 1
			INNER JOIN vendors vnd ON bcb.bcb_vendor_id = vnd.vnd_id
			INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id 
			INNER JOIN vendor_pref vnp ON vnd.vnd_id = vnp.vnp_vnd_id
			INNER JOIN contact_profile cp ON cp.cr_is_vendor = vnd.vnd_id AND cp.cr_status =1
			INNER JOIN contact ctt ON ctt.ctt_id =cp.cr_contact_id AND ctt.ctt_active =1 AND ctt.ctt_id = ctt.ctt_ref_code
            LEFT JOIN cities cty ON ctt.ctt_city = cty.cty_id
				LEFT JOIN states stt ON stt.stt_id = cty.cty_state_id 
				LEFT JOIN zones zon  ON vnp.vnp_home_zone = zon.zon_id 
				WHERE bkg.bkg_status IN (6,7) AND bkg.bkg_active = 1 $cond $regCond $sttCond $zonCond
			GROUP BY vnd.vnd_id ";

		$sqlCount = "SELECT
					COUNT(*)
                FROM booking bkg
				INNER JOIN booking_cab bcb ON bkg.bkg_bcb_id = bcb.bcb_id AND bcb.bcb_active = 1
					INNER JOIN vendors vnd ON bcb.bcb_vendor_id = vnd.vnd_id
					$stateFlag
					$zoneJoin
					WHERE bkg.bkg_status IN (6,7) AND bkg.bkg_active = 1 $cond $regCond $sttCond $zonCond
					GROUP BY vnd.vnd_id ";
		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['Count_Trips', 'vrs_approve_car_count'],
					'defaultOrder'	 => 'Count_Trips DESC'], 'pagination'	 => ['pageSize' => 20],
			]);
			return $dataprovider;
		}
	}

	public function fetchMetric($vendorId)
	{
		$param	 = ['vendorId' => $vendorId];
		$sql	 = "SELECT vendors.vnd_active, vendors.vnd_rel_tier, vrs_vnd_overall_rating, vrs_total_completed_days_30, vrs_total_vehicle_30,
						vrs_vnd_overall_rating, vrs_sticky_score, vrs_driver_app_used, vrs_penalty_count, vrs_dependency, vrs_boost_percentage 
					FROM `vendor_stats` 
					INNER JOIN vendors ON vendors.vnd_id = vendor_stats.vrs_vnd_id 
					WHERE `vrs_vnd_id`=:vendorId";
		$result	 = DBUtil::queryRow($sql, null, $param);
		return $result;
	}

	public function getAcceptedByVendorList($date1 = '', $date2 = '', $type = '')
	{
		$sqlSelect		 = "SELECT 
				booking.bkg_id,
				booking.bkg_booking_id,
				vnd.vnd_id,
				vnd.vnd_name,
				vnp.vnp_is_freeze,
				booking.bkg_pickup_date,
				MAX(booking_log.blg_created) AS blg_created,
				vrs.vrs_approve_driver_count,
				vrs.vrs_approve_car_count,
				biv.bkg_vendor_amount,
				biv.bkg_total_amount";
		$sqlSelectCount	 = "SELECT 
				booking.bkg_id ";

		$sqlBody = "    FROM booking_cab
				INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_assign_mode = 2
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = booking.bkg_id AND btr.bkg_assign_mode = 2
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = booking.bkg_id
				INNER JOIN vendors vnd ON vnd.vnd_id = booking_cab.bcb_vendor_id
				INNER JOIN booking_log
				ON     booking_log.blg_booking_id = booking.bkg_id
					AND booking_log.blg_entity_id = vnd.vnd_id
			   INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
			   INNER JOIN vendor_pref vnp ON vnd.vnd_id = vnp.vnp_vnd_id
			   WHERE     1
			   AND booking_log.blg_user_type = 2
			   AND booking_log.blg_event_id = 7
			   AND booking.bkg_status IN (3, 5, 6, 7)
			   AND booking_log.blg_created BETWEEN '$date1' AND '$date2'
			  GROUP BY booking.bkg_id ";

		$sqlCount	 = $sqlSelectCount . $sqlBody;
		$sql		 = $sqlSelect . $sqlBody;
		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) a", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB(),
				'sort'			 => ['attributes'	 => ['blg_created', 'vrs_approve_car_count'],
					'defaultOrder'	 => 'blg_created DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public function calculateBidWinRate()
	{
		$date	 = date('Y-m-d');
		$sql	 = "SELECT Distinct(bvr_vendor_id)  FROM `booking_vendor_request` WHERE `bvr_created_at` BETWEEN DATE_SUB(now(), INTERVAL 30 DAY) AND now()";

		$record = DBUtil::command($sql)->queryAll();

		foreach ($record as $res)
		{
			$vendorId = $res['bvr_vendor_id'];
			$this->bidWinRate($vendorId);
		}
	}

	public function bidWinRate($vendorId)
	{
		$param				 = ['vendorId' => $vendorId];
		$sql				 = "SELECT GROUP_CONCAT(bvr_booking_id) AS allBookingIds, count(bvr_booking_id) AS totalCounter FROM `booking_vendor_request` WHERE bvr_vendor_id=:vendorId AND bvr_created_at>=CURRENT_DATE() - INTERVAL 30 DAY";
		$result				 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		$biddingPercentage	 = 0;
		$totalBidCount		 = $result['totalCounter'];
		if ($result['allBookingIds'] != "")
		{
			$param1				 = ['vendorId' => $vendorId];
			DBUtil::getINStatement($result['allBookingIds'], $bindString, $param2);
			$sql1				 = "SELECT count(bcb_bkg_id1) as counter FROM booking_cab WHERE bcb_bkg_id1 IN ($bindString)   AND bcb_vendor_id= :vendorId AND bcb_trip_status!=1";
			$totalWinBidCount	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), array_merge($param2, $param1));
			$biddingPercentage	 = ($totalWinBidCount / $totalBidCount) * 100;
			$biddingPercentage	 = round($biddingPercentage, 2);
			$params				 = ['biddingPercentage' => $biddingPercentage, 'vendorId' => $vendorId];
			$sqlUpdate			 = 'UPDATE `vendor_stats` SET  vrs_bid_win_percentage=:biddingPercentage WHERE vrs_vnd_id=:vendorId';
			DBUtil::execute($sqlUpdate, $params);
		}
	}

	/* corn name 

	 * @deprecated	
	 * new function calcDependency */

	public static function calculateDependency($vendorId)//dependability
	{
		$params				 = ['vendorId' => $vendorId, 'tripStatus' => '6'];
		$sql				 = "SELECT COUNT(bcb_id) as counter FROM booking_cab  WHERE bcb_vendor_id=:vendorId AND bcb_trip_status=:tripStatus AND bcb_start_time>=CURRENT_DATE() - INTERVAL 180 DAY";
		$result				 = DBUtil::queryRow($sql, null, $params);
		$totalCompletedTrip	 = $result['counter'];
		$param				 = ['vendor_id' => $vendorId, 'blg_event_id' => 8];
		$sql1				 = "SELECT count(blg_booking_id)as count  FROM `booking_log` WHERE `blg_event_id` =:blg_event_id AND blg_vendor_assigned_id =:vendor_id AND  blg_created >=CURRENT_DATE() - INTERVAL 180 DAY";
		$totalrejectedTrip	 = DBUtil::queryScalar($sql1, DBUtil::SDB(), $param);
		if ($totalCompletedTrip == 0 && $totalrejectedTrip > 0)
		{
			$dependency = 0;
		}
		else
		{
			if ($totalCompletedTrip == 0)
			{
				$dependency = 100;
			}
			else
			{
				$dependency	 = (1 - ($totalrejectedTrip / $totalCompletedTrip)) * 100;
				$dependency	 = round($dependency, 2);
				$dependency	 = max($dependency, 0);
			}
		}

		$params		 = ['dependency' => $dependency, 'completedTrip' => $totalCompletedTrip, 'rejectedTrip' => $totalrejectedTrip, 'vendorId' => $vendorId];
		$sqlUpdate	 = 'UPDATE `vendor_stats` SET  `vrs_dependency` =:dependency, `vrs_3mnth_completed_trip` =:completedTrip, `vrs_3mnth_rejected_trip` =:rejectedTrip WHERE vrs_vnd_id=:vendorId';
		DBUtil::execute($sqlUpdate, $params);
	}

	/** @return array() */
	public static function getVendorServingStats($vendorId, $days)
	{
		$maxAssigndate	 = BookingVendorRequest::getMaxAssignDate($vendorId);
		$params			 = ['vendorId' => $vendorId, 'day' => $days, 'assignDate' => $maxAssigndate];
		$sql			 = "SELECT v.vnd_ref_code, v1.vnd_name,
                                COUNT(DISTINCT bkg_id) as bookingAssigned,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=2, bkg_id, null)) as bookingDirectAccept,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=0, bkg_id, null)) as bookingBidAccept,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=1, bkg_id, null)) as bookingManualAccept,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=3, bkg_id, null)) as bookingGNowAccept,
                                COUNT(DISTINCT IF(bcb.bcb_vendor_id=bcb1.bcb_vendor_id, bkg_id, null)) as bookingServed,
                                COUNT(DISTINCT IF(bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bkg_id, null)) as bookingCancelled,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=0 AND bcb.bcb_vendor_cancel_type=2 AND bkg_status=9, bcb.bcb_id, NULL)) as BidDriverNoShowCount,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=0 AND bcb.bcb_vendor_cancel_type=1 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, NULL)) as BidLastMinuteCancellation,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=1 AND bcb.bcb_vendor_cancel_type=2 AND bkg_status=9, bcb.bcb_id, NULL)) as ManualDriverNoShowCount,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=1 AND bcb.bcb_vendor_cancel_type=1 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, NULL)) as ManualLastMinuteCancellation,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=2 AND bcb.bcb_vendor_cancel_type=2 AND bkg_status=9, bcb.bcb_id, NULL)) as DirectDriverNoShowCount,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=2 AND bcb.bcb_vendor_cancel_type=1 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, NULL)) as DirectLastMinuteCancellation,
								COUNT(DISTINCT IF(bcb.bcb_assign_mode=0 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, NULL)) as bookingBidCancelled,
								COUNT(DISTINCT IF(bcb.bcb_assign_mode=1 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, NULL)) as bookingManualCancelled,
                                COUNT(DISTINCT IF(bcb.bcb_assign_mode=2 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, null)) as bookingDirectCancelled,
								COUNT(DISTINCT IF(bcb.bcb_assign_mode=3 AND bcb.bcb_vendor_id<>bcb1.bcb_vendor_id, bcb.bcb_id, NULL)) as bookingGNowCancelled,
								vrs_dependency
							FROM booking_vendor_request bvr
                            INNER JOIN booking_cab bcb ON bcb.bcb_id=bvr.bvr_bcb_id AND bcb.bcb_vendor_id=bvr.bvr_vendor_id
							INNER JOIN booking bkg ON bkg.bkg_id=bcb.bcb_bkg_id1 AND bkg.bkg_status IN (2,3,5,6,7,9)
							INNER JOIN booking_cab bcb1 ON bcb1.bcb_id=bkg.bkg_bcb_id AND bcb1.bcb_vendor_id>0
							INNER JOIN vendors v ON v.vnd_id=bvr.bvr_vendor_id
							INNER JOIN vendors v1 ON v.vnd_ref_code=v1.vnd_id
							INNER JOIN vendor_stats ON vrs_vnd_id=v1.vnd_id
							WHERE bkg.bkg_pickup_date>=DATE_SUB(:assignDate, INTERVAL :day DAY) AND bvr.bvr_vendor_id=:vendorId
							GROUP BY v1.vnd_id";

		$result = DBUtil::queryRow($sql, DBUtil::SDB(), $params);

		return $result;
	}

	/**
	 * calculate vendor dependability
	 * @param type $vendorId
	 * @return int number of row updated 
	 */
	public static function calcDependency($vendorId)
	{
		$days		 = array(7, 30, 60);
		$score		 = [];
		$weightage	 = 0;
		$log		 = ["vendorId" => $vendorId];

		foreach ($days as $day)
		{
			$result							 = self::getVendorServingStats($vendorId, $day);
			$assignedTrip					 = $result['bookingAssigned'];
			$directAcceptedTrip				 = $result['bookingDirectAccept'];
			$bidAcceptedTrip				 = $result['bookingBidAccept'];
			$manualAcceptedTrip				 = $result['bookingManualAccept'];
			$servedTrip						 = $result['bookingServed'];
			$cancelTrip						 = $result['bookingCancelled'];
			$directCanceltrip				 = $result['bookingDirectCancelled'];
			$BidDriverNoShowCount			 = $result['BidDriverNoShowCount'];
			$BidLastMinuteCancellation		 = $result['BidLastMinuteCancellation'];
			$ManualDriverNoShowCount		 = $result['ManualDriverNoShowCount'];
			$ManualLastMinuteCancellation	 = $result['ManualLastMinuteCancellation'];
			$DirectDriverNoShowCount		 = $result['DirectDriverNoShowCount'];
			$DirectLastMinuteCancellation	 = $result['DirectLastMinuteCancellation'];
			$bidCanceltrip					 = $result['bookingBidCancelled'];
			$manualCanceltrip				 = $result['bookingManualCancelled'];
			$gnowCanceltrip					 = $result['bookingGNowCancelled'];

			$servingRatio = $servedTrip / max([$assignedTrip, 1]);

			switch ($day)
			{
				case 30:
					$acceptMinCount	 = 10;
					$cancelMinCount	 = 4;
					break;
				case 60:
					$acceptMinCount	 = 15;
					$cancelMinCount	 = 6;
					break;
				default:
					$acceptMinCount	 = 5;
					$cancelMinCount	 = 2;
					break;
			}

			if (!isset($score['direct']) && ($directAcceptedTrip >= $acceptMinCount || $directCanceltrip > $cancelMinCount))
			{
				$log["direct"] = ["Accepted"	 => $directAcceptedTrip, "Cancelled"	 => $directCanceltrip, "day"		 => $day,
					"lastMinute" => $DirectLastMinuteCancellation, "noShow"	 => $DirectDriverNoShowCount];

				$badCancel				 = $DirectDriverNoShowCount * 3 + $DirectLastMinuteCancellation * 1;
				$score['direct']		 = (1 - ((($directCanceltrip + $badCancel) * 1.5) / max($directAcceptedTrip - $directCanceltrip, 1))) * 0.45;
				$log["direct"]["score"]	 = $score['direct'];
				$weightage				 += 0.45;
			}

			if (!isset($score['manual']) && ($manualAcceptedTrip >= $acceptMinCount || $manualCanceltrip > $cancelMinCount))
			{
				$log["manual"]			 = ["Accepted"	 => $manualAcceptedTrip, "Cancelled"	 => $manualCanceltrip, "day"		 => $day,
					"lastMinute" => $ManualLastMinuteCancellation, "noShow"	 => $ManualDriverNoShowCount];
				$badCancel				 = $ManualDriverNoShowCount * 3 + $ManualLastMinuteCancellation * 1;
				$score['manual']		 = (1 - (($manualCanceltrip + $badCancel) * 1.5 / max(($manualAcceptedTrip - $manualCanceltrip), 1))) * 0.25;
				$log["manual"]["score"]	 = $score['manual'];
				$weightage				 += 0.25;
			}

			if (!isset($score['bid']) && ($bidAcceptedTrip >= ($acceptMinCount * 1.5) || $bidCanceltrip > ($cancelMinCount * 1.5)))
			{
				$log["bid"]			 = ["Accepted"	 => $bidAcceptedTrip, "Cancelled"	 => $bidCanceltrip, "day"		 => $day,
					"lastMinute" => $BidLastMinuteCancellation, "noShow"	 => $BidDriverNoShowCount];
				$badCancel			 = $BidDriverNoShowCount * 3 + $BidLastMinuteCancellation * 1;
				$score['bid']		 = (1 - (($bidCanceltrip + $badCancel) * 1.2) / max(($bidAcceptedTrip - $bidCanceltrip), 1)) * 0.3;
				$log["bid"]["score"] = $score['bid'];
				$weightage			 += 0.3;
			}

			if (count($score) >= 3)
			{
				break;
			}
		}


		$dependency = null;
		if (count($score) > 0)
		{
			$dependency = round(array_sum($score) * 100 / $weightage);
		}

		$data = ["day" => $day, "result" => $result, "score" => $dependency];
		if ($dependency != null && $result["vrs_dependency"] != $dependency)
		{
			$log["oldScore"] = $result["vrs_dependency"];
			$log["newScore"] = $dependency;
			Logger::writeToConsole(json_encode($log));
			if ($result["vrs_dependency"] > $dependency)
			{
				notificationWrapper::notifyDpndScoreReduce($vendorId, $result["vrs_dependency"], $dependency);
			}
		}
		Logger::writeToConsole(json_encode($log));
		return $data;
	}

	/**
	 * update dependency score of vendor
	 * @param type $dependency
	 * @param type $vendorId
	 */
	public static function updateDependency($vendorId)
	{

		$data				 = Self::calcDependency($vendorId);
		Logger::trace(json_encode($data) . " \n vendorId: " . $vendorId);
		$newDependencyScore	 = $data["score"];

		$vndStatsModel	 = VendorStats::model()->getbyVendorId($vendorId);
		$boostScore		 = $vndStatsModel->vrs_boost_dependency;

		if ($newDependencyScore > 0 && $boostScore > 0)
		{
			$boostScore = 0;
			VendorStats::updateBoostDependency($vendorId, 0);
		}
		if ($newDependencyScore == null)
		{
			$newDependencyScore = $vndStatsModel->vrs_dependency;
		}
		$newDependencyScore = $newDependencyScore + $boostScore;

		$params		 = ['dependency' => $newDependencyScore, 'vendorId' => $vendorId];
		$sqlUpdate	 = 'UPDATE `vendor_stats` SET  `vrs_dependency` = :dependency WHERE vrs_vnd_id=:vendorId';
		return DBUtil::execute($sqlUpdate, $params);
	}

	/**
	 * update 3 month trip count of vendor
	 * @param type $vendorId
	 */
	public static function updateTripCount($vendorId)
	{
		$days		 = 90;
		$result		 = self::getVendorServingStats($vendorId, $days);
		$servedTrip	 = $result['bookingServed'];
		$cancelTrip	 = $result['bookingCancelled'];
		$params		 = ['completedTrip' => $servedTrip, 'rejectedTrip' => $cancelTrip, 'vendorId' => $vendorId];
		$sqlUpdate	 = 'UPDATE `vendor_stats` SET  `vrs_3mnth_completed_trip` =:completedTrip, `vrs_3mnth_rejected_trip` =:rejectedTrip WHERE vrs_vnd_id=:vendorId';
		DBUtil::execute($sqlUpdate, $params);
	}

	public function updateBoostPercentage($vendorId, $boostPercentage)
	{

		$params = ['vrs_boost_percentage' => $boostPercentage, 'vendorId' => $vendorId];

		$sqlUpdate	 = 'UPDATE `vendor_stats` SET  vrs_boost_percentage=:vrs_boost_percentage WHERE vrs_vnd_id=:vendorId';
		$result		 = DBUtil::command($sqlUpdate)->execute($params);
	}

	public static function UpdateLedgerBalance($defaultDays = 3)
	{
		$param	 = ['days' => $defaultDays];
		$sql	 = "SELECT DISTINCT adt.adt_trans_ref_id		
					FROM account_trans_details adt
					INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id AND adt.adt_ledger_id = 14
					WHERE
						adt.adt_modified BETWEEN DATE_SUB(NOW(), INTERVAL :days DAY) AND NOW() AND adt.adt_type = 2
						AND adt.adt_trans_ref_id IS NOT NULL";

		$result = DBUtil::query($sql, DBUtil::SDB(), $param);

		foreach ($result as $row)
		{
			VendorStats::updateOutstanding($row['adt_trans_ref_id']);
		}
	}

	/**
	 * @param int|array() $duplicateVendorIds
	 * @param int $primaryVendorId
	 * @return ReturnSet|ReturnSet[] Description
	 *  */
	public static function transferSecurityAmount($duplicateVendorIds, $primaryVendorId)
	{
		if (is_array($duplicateVendorIds))
		{
			$result = [];
			foreach ($duplicateVendorIds as $value)
			{
				$result[$value] = self::transferSecurityAmount($value, $primaryVendorId);
			}
			return $result;
		}

		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ledgerSecurityBalance = AccountTransactions::getSecurityAmount($duplicateVendorIds);
			if ($ledgerSecurityBalance <= 0)
			{
				throw new Exception("Insufficient Security Balance", ReturnSet::ERROR_INVALID_DATA);
			}
			self::refundSecurityAmount($duplicateVendorIds, $ledgerSecurityBalance);

			self::chargeSecurityAmount($primaryVendorId, $ledgerSecurityBalance);
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 * @param int $vndId Vendor ID
	 * @param int $amount Security Amount
	 * @return boolean
	 * @throws Exception
	 */
	public static function refundSecurityAmount($vndId, $amount)
	{
		$transaction = DBUTil::beginTransaction();
		try
		{
			$param	 = ['vndId' => $vndId, "amount" => $amount];
			$sql	 = "UPDATE `vendor_stats` SET vrs_security_amount =(vrs_security_amount - :amount) WHERE vrs_vnd_id=:vndId";
			$cnt	 = DBUtil::execute($sql, $param);
			if ($cnt == 0)
			{
				throw new Exception("Can't update security amount in vendor stats", ReturnSet::ERROR_FAILED);
			}
			AccountTransactions::refundSecurityAmount($amount, $vndId);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $exc;
		}
		return true;
	}

	/**
	 * @return boolean
	 * @throws Exception
	 *  */
	public static function chargeSecurityAmount($vndId, $amount)
	{
		$transaction = DBUTil::beginTransaction();
		try
		{
			$vndModel							 = Vendors::model()->findByPk($vndId);
			$vndStatsModel						 = $vndModel->vendorStats;
			$vndStatsModel->vrs_security_amount	 += $amount;
			if (!$vndStatsModel->save())
			{
				throw new Exception(json_encode($vndStatsModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$actModel = AccountTransactions::chargeSecurityAmount($amount, $vndId, '', "");
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $exc;
		}
		return true;
	}

	/** check vendor outstanding positive or negative
	 * 
	 * @param type $vendorId
	 * @return boolean
	 */
	public static function checkOutstanding($vendorId)
	{

		$params	 = ['vendorId' => $vendorId];
		$sql	 = "SELECT vrs_withdrawable_balance FROM vendor_stats WHERE vrs_vnd_id=:vendorId";
		$result	 = DBUtil::queryRow($sql, null, $params);
		if ($result['vrs_withdrawable_balance'] > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * 
	 * @param int $vendorId
	 */
	public static function showvndbkg($vendorId)
	{
		$vndInfoSql = "SELECT vnd_name, vnd_cat_type,
					vnd_active,
					vnp_is_freeze,
					vnp_cod_freeze,
					vnp_accepted_zone,
					group_concat(zn.zon_name SEPARATOR ', ') accepted_zone_name,
					vnp_home_zone,
					zn1.zon_name home_zone_name,
					vnp_excluded_cities,
					vnp_oneway,
					vnp_round_trip,
					vnp_multi_trip,
					vnp_airport,
					vnp_package,
					vnp_flexxi,
					vnp_daily_rental,
					vnp_boost_enabled,
					IF(vnp_is_allowed_tier LIKE '%1%', 1, 0) value,
					IF(vnp_is_allowed_tier LIKE '%2%', 2, 0) valuePlus,
					IF(vnp_is_allowed_tier LIKE '%3%', 3, 0) plus,
					IF(vnp_is_allowed_tier LIKE '%4%', 4, 0) selectTier,
					IF(vnp_is_allowed_tier LIKE '%5%', 5, 0) selectPlus,
					IF(vnp_is_allowed_tier LIKE '%6%', 6, 0) CNG
				 FROM vendors 
					INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
					JOIN `home_service_zones` hsz  ON hsz.hsz_home_id=vnp_home_zone
					JOIN zones zn ON  zn.zon_id = hsz.hsz_service_id
					JOIN zones zn1 ON  zn1.zon_id = vnp_home_zone
				 WHERE vnd_id = $vendorId";

		$vndInfo = DBUtil::queryRow($vndInfoSql, DBUtil::SDB());
		echo "<br>";
		echo "Vendor info";
		echo "<br>";
		$viewA1	 = "<table border='1' style='border-collapse: collapse' cellpadding='5'>";
		$view1	 = '';
//		foreach ($vndInfo as $rowArr1)
//		{
		$view1	 .= "<tr>";
		$viewTh1 = "<tr>";
		foreach ($vndInfo as $h1 => $val1)
		{

			$h1		 = str_replace('vnp_', '', $h1);
			$h1		 = str_replace('vnd_', '', $h1);
			$view1	 .= "<td>$val1</td>";
			$viewTh1 .= "<th>$h1</th>";
		}
		$view1	 .= "</tr>";
		$viewTh1 .= "</tr>";
//		}
		$view1	 .= "</table>";
		echo $viewA1 . $viewTh1 . $view1;

		echo "<br> <br><br>";
		echo "Vendor Booking info";
		echo "<br>";

		$bkgTypeArr	 = Booking::model()->getBookingType(0);
		$page_no	 = 0;
		$filterModel = '{"bidStatus":0,"serviceType":"all","sort":"earliestBooking","date":null,"page":0,"page_size":200,"search_txt":null,"tierList":[{"id":1,"name":"value"},{"id":2,"name":"valuePlus"},{"id":3,"name":"select"}]}';
		$offSetCount = 100;
		$data		 = BookingVendorRequest::getPendingRequestV2($vendorId, $page_no, json_decode($filterModel, false), $offSetCount);
		$viewA		 = "<table border='1' style='border-collapse: collapse' cellpadding='5'>";
		$view		 = '';
		foreach ($data as $rowArr)
		{
			$view	 .= "<tr>";
			$viewTh	 = "<tr>";
			foreach ($rowArr as $h => $val)
			{
				$h	 = str_replace('bkg_', '', $h);
				$h	 = str_replace('btr_', '', $h);
				if ($h == 'bkg_booking_type')
				{
					$val = $bkgTypeArr[$val];
				}

				$view	 .= "<td>$val</td>";
				$viewTh	 .= "<th>$h</th>";
			}
			$view	 .= "</tr>";
			$viewTh	 .= "</tr>";
		}
		$view .= "</table>";
		echo $viewA . $viewTh . $view;
	}

	/** check vendor outstanding amount for freeze vendor
	 * 
	 * @param type $vendorId
	 * @return outstanding amount
	 */
	public static function frozenOutstanding($vendorId)
	{
		$outStanding = 0;

		$chekFrozenStatus = VendorPref::checkfrozen($vendorId);

		if ($chekFrozenStatus == 1)
		{

			$params		 = ['vendorId' => $vendorId];
			$sql		 = "SELECT vrs_outstanding FROM vendor_stats WHERE vrs_vnd_id=:vendorId";
			$result		 = DBUtil::queryRow($sql, null, $params);
			$outStanding = $result[vrs_outstanding];
		}
		return $outStanding;
	}

	/**
	 * Update count for vendor stats for direct accept
	 * @param int $vendorId
	 */
	public static function updateDirectAcceptCount($vendorId = '')
	{
		$resultcount = BookingCab::countVndBookingMode($vendorId, $mode		 = 2);

		$params	 = ['dataCount' => $resultcount, 'vndId' => $vendorId];
		$sql	 = "UPDATE vendor_stats  
							SET vrs_self_accept_90_days = :dataCount 
							WHERE vrs_vnd_id = :vndId";
		$res	 = DBUtil::execute($sql, $params);
	}

	/**
	 * Update count for vendor stats for bid accept
	 * @param int $vendorId
	 */
	public static function updateBidAcceptCount($vendorId = '')
	{
		$resultcount = BookingCab::countVndBookingMode($vendorId, $mode		 = 0);
		$params		 = ['dataCount' => $resultcount, 'vndId' => $vendorId];
		$sql		 = "UPDATE vendor_stats  
					SET vrs_bid_accept_90_days = :dataCount 
					WHERE vrs_vnd_id = :vndId";
		$res		 = DBUtil::execute($sql, $params);
	}

	/**
	 * Update count for vendor stats for manual accept
	 * @param int $vendorId
	 */
	public static function updateManualAcceptCount($vendorId = '')
	{
		$resultcount = BookingCab::countVndBookingMode($vendorId, $mode		 = 1);
		$params		 = ['dataCount' => $resultcount, 'vndId' => $vendorId];
		$sql		 = "UPDATE vendor_stats  
					SET vrs_manual_accept_90_days = :dataCount 
					WHERE vrs_vnd_id = :vndId";
		$res		 = DBUtil::execute($sql, $params);
	}

	/**
	 * Update count for vendor stats for total accept
	 * @param int result
	 * 
	 */
	public static function totalAcceptCount($vendorId = '')
	{
		$directAcptCount	 = BookingCab::countVndBookingMode($vendorId, $mode				 = 2);
		$bidAccptCount		 = BookingCab::countVndBookingMode($vendorId, $mode				 = 0);
		$manualAccptCount	 = BookingCab::countVndBookingMode($vendorId, $mode				 = 1);
		$gozoNowAccptCount	 = BookingCab::countVndBookingMode($vendorId, $mode				 = 3);
		$totalAcptCount		 = $directAcptCount + $bidAccptCount + $manualAccptCount + $gozoNowAccptCount;
		$params				 = ['dataCount' => $totalAcptCount, 'vndId' => $vendorId];
		$sql				 = "UPDATE vendor_stats  
					SET vrs_total_accept_90_days = :dataCount 
					WHERE vrs_vnd_id = :vndId";
		$res				 = DBUtil::execute($sql, $params);
	}

	public static function lastAccept_date($vendorId)
	{
		$params	 = ["vendorId" => $vendorId];
		$sql	 = "SELECT bvr_assigned_at  FROM `booking_vendor_request` WHERE `bvr_vendor_id` = :vendorid AND `bvr_accepted` = 1 AND `bvr_assigned` = 1 ORDER BY bvr_assigned_at DESC LIMIT 0,1";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		$params	 = ['acptdate' => $result, 'vndId' => $vendorId];
		$sql	 = "UPDATE vendor_stats  
					SET vrs_last_assign_date = :acptdate 
					WHERE vrs_vnd_id = :vndId";
		$res	 = DBUtil::execute($sql, $params);
	}

	/**
	 * updateAssignDate according to mode of assignment
	 * @param type $vndId
	 * @param type $mode
	 */
	public static function updateAssignDate($vndId, $mode)
	{

		$params	 = ["vendorId" => $vndId];
		$sql	 = "UPDATE vendor_stats SET ";

		switch ($mode)
		{
			case 0:
				$qry = " vrs_last_bid_assign_date =now()";
				break;
			case 1:
				$qry = " vrs_last_manual_assign_date =now()";
				break;
			case 2:
				$qry = " vrs_last_direct_assign_date =now()";
				break;
			case 3:
				$qry = " vrs_last_gozonow_assign_date =now()";
				break;
		}
		$sql .= "$qry WHERE vrs_vnd_id =:vendorId";
		$res = DBUtil::execute($sql, $params);
	}

	public function updateLastUnassignDate($vndID, $step, $userType)
	{
		$model = VendorStats::model()->getbyVendorId($vndID);
		if ($userType == 10)
		{
			$model->vrs_system_unassign_date = new CDbExpression('NOW()');
		}
		else
		{
			if ($step == 1)
			{
				$model->vrs_last_self_unassign_stage1_date = new CDbExpression('NOW()');
			}
			else
			{
				$model->vrs_last_self_unassign_stage2_date = new CDbExpression('NOW()');
			}
		}
		$model->save();
	}

	/**
	 * updateUnassignCountStepWise count unassign booking according to status and modify vendor stat unassign count 
	 * @param type $vndId
	 */
	public static function updateUnassignCountStepWise($vendorId)
	{
		$step1UnassignCount	 = BookingCab::countUnassignMode($vendorId, $mode				 = 1);

		$updateUnassignCount = VendorStats::updateUnassignCountStatus($vendorId, $mode				 = 1, $step1UnassignCount);

		$step2UnassignCount	 = BookingCab::countUnassignMode($vendorId, $mode				 = 2);

		$updateUnassignCount = VendorStats::updateUnassignCountStatus($vendorId, $mode				 = 2, $step2UnassignCount);

		$systemUnassignCount = BookingCab::countUnassignMode($vendorId, $mode				 = 0);

		$updateUnassignCount = VendorStats::updateUnassignCountStatus($vendorId, $mode				 = 0, $systemUnassignCount);
	}

	public static function updateUnassignCountStatus($vendorId, $mode, $unassignCount)
	{
		$params	 = ["vendorId" => $vendorId, 'unassignCount' => $unassignCount];
		$sql	 = "UPDATE vendor_stats SET ";

		switch ($mode)
		{
			case 0:
				$qry = " vrs_system_unassign_count =:unassignCount";
				break;
			case 1:
				$qry = " vrs_step1_unassign_count =:unassignCount";
				break;
			case 2:
				$qry = " vrs_step2_unassign_count =:unassignCount";
				break;
		}
		$sql .= "$qry WHERE vrs_vnd_id =:vendorId";
		$res = DBUtil::execute($sql, $params);
	}

	public function updateLastLocation($data)
	{
		$success	 = false;
		$vendorId	 = UserInfo::getEntityId();
		if ($vendorId != "" || $vendorId != NUll)
		{
			$model = VendorStats::model()->getbyVendorId($vendorId);
			if (!$model)
			{
				$model				 = new VendorStats();
				$model->vrs_vnd_id	 = $vendorId;
			}
			$model->vrs_last_loc_lat		 = $data['lat'];
			$model->vrs_last_loc_long		 = $data['lon'];
			$model->vrs_last_loc_date		 = new CDbExpression("now()");
			$model->vrs_last_loc_device_id	 = $data['deviceId'];
			if ($model->save())
			{
				$success = true;
			}
		}
		return $success;
	}

	public static function updateCoins($totalCoin, $vendorId)
	{
		$success = false;
		$model	 = VendorStats::model()->getbyVendorId($vendorId);
		if ($model)
		{
			$model->vrs_vnd_coin_cnt = $totalCoin;
			if ($model->save())
			{
				$success = true;
			}
		}
		return $success;
	}

	/**
	 * 
	 * @param type $criticalScore
	 * @param type $vendorId
	 * return boolean
	 */
	public static function checkDependency($criticalScore, $vendorId)
	{
		$statModel		 = VendorStats::model()->getbyVendorId($vendorId);
		$dependency		 = $statModel->vrs_dependency;
		$minDependency	 = 0;

		if ($criticalScore >= 0.92)
		{
			$minDependency = -300;
		}
		else if ($criticalScore >= 0.84)
		{
			$minDependency = -100;
		}
		elseif ($criticalScore >= 0.72)
		{
			$minDependency = -50;
		}

		return ($dependency >= $minDependency);
	}

	/**
	 * 
	 * @param integer $vendorId
	 * @return string
	 */
	public static function getDependencyMessage($vendorId = 0)
	{
		$statModel		 = VendorStats::model()->getbyVendorId($vendorId);
		$dependency		 = $statModel->vrs_dependency | 0;
//		$calculateDependency = ($dependency == '' ? 0 : $dependency);
		$dependencyMsg	 = '';
		if ($dependency < 0)
		{
			$dependencyMsg = "Your dependability score is very low. If you deny this booking after you direct accept, penalty will apply. Only Partners with high dependability can direct accept without risk of denial penalty.";
		}
		return $dependencyMsg;
	}

	/**
	 * 
	 * @param \Beans\common\Location $locObj
	 * @param String $deviceId
	 * @param int $vendorId
	 * @return \ReturnSet
	 */
	public static function updateLastLocationDCO($locObj, $deviceId, $vendorId = 0)
	{
		$returnSet	 = new ReturnSet();
		/** @var \Beans\common\Location $locObj */
		$coordinates = $locObj->coordinates;
		if (!is_numeric($coordinates->lat) || !is_numeric($coordinates->lng))
		{
			$error = ["Invalid data for coordinates"];
			$returnSet->setErrors($error, ReturnSet::ERROR_VALIDATION);
			goto skipAll;
		}
		$cData		 = ['lat' => $coordinates->lat, 'lon' => $coordinates->lng, 'loc_device_uuid' => $deviceId];
		$userInfo	 = UserInfo::getInstance();
		Location::addLocation($cData, $userInfo);

		if ($vendorId > 0 && UserInfo::getUserType() == UserInfo::TYPE_VENDOR)
		{
			$model = VendorStats::model()->getbyVendorId($vendorId);
			if (!$model)
			{
				$model				 = new VendorStats();
				$model->vrs_vnd_id	 = $vendorId;
			}
			/** @var \Beans\common\Coordinates $coordinates */
			$model->vrs_last_loc_lat		 = $coordinates->lat;
			$model->vrs_last_loc_long		 = $coordinates->lng;
			$model->vrs_last_loc_date		 = new CDbExpression("now()");
			$model->vrs_last_loc_device_id	 = $deviceId;
			$model->save();
		}
		$returnSet->setStatus(true);
		$returnSet->setMessage('Last location updated');
		skipAll:
		return $returnSet;
	}

	public static function calculateOutstandingBalance($vendorId)
	{
		$vendorModel	 = Vendors::model()->findByPk($vendorId);
		$securityAmount	 = $vendorModel->vendorStats->vrs_security_amount;
		$vndOutStanding	 = $vendorModel->vendorStats->vrs_outstanding;
		$outStanding	 = (($vndOutStanding * -1) + $securityAmount);
		return $outStanding;
	}

	/**
	 * function addBoostDependency return Type Boolean
	 * @param type $vndId
	 */
	public static function addBoostDependency($vndId)
	{

		$vndStatsModel		 = VendorStats::model()->getbyVendorId($vndId);
		$oldBoostedAmount	 = $vndStatsModel->vrs_boost_dependency;
		$actualDependency	 = $vndStatsModel->vrs_dependency;
		if ($actualDependency >= 0)
		{
			return false;
		}
		$score	 = ($actualDependency * -1) + 5;
		$model	 = VendorStats::updateBoostDependency($vndId, $score);
		if (!$model)
		{
			return false;
		}
		Vendors::vendorDependencyBoost($vndId);
//		notificationWrapper::notifyDpndBoostedVnd($vndId);

		return true;
	}

	public static function updateBoostDependency($vndId, $score)
	{
		try
		{
			$vndStatsModel						 = VendorStats::model()->getbyVendorId($vndId);
			$vndStatsModel->vrs_boost_dependency = $score;
			if (!$vndStatsModel->save())
			{
				throw ReturnSet::getModelValidationException($vndStatsModel);
			}
			if ($score == 0)
			{
				$desc = "Dependency score boosting removed.";
			}
			else
			{
				$desc = "Dependency score boosted by $score points";
			}
			VendorsLog::model()->createLog($vndId, $desc, null, VendorsLog::VENDOR_DEPENDENCY_BOOSTED, false, false);
		}
		catch (Exception $exc)
		{
			Logger::error($exc);
		}

		return $vndStatsModel;
	}

	public static Function ShowBoostedVendor()
	{
		$sql	 = "SELECT vrs_vnd_id  FROM `vendor_stats` WHERE vrs_boost_dependency >0";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public function negetiveDependencyList()
	{
		$sql	 = "SELECT DISTINCT vrs_vnd_id, vrs_dependency, vrs_boost_dependency,
					COUNT(DISTINCT bvr_bcb_id) as cnt
					FROM `vendor_stats`
					INNER JOIN vendors ON vendors.vnd_id = vendor_stats.vrs_vnd_id
					INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id= vendors.vnd_id
					INNER JOIN booking_vendor_request ON booking_vendor_request.bvr_vendor_id = vendors.vnd_id AND bvr_assigned =1 AND  bvr_assigned_at > (now() - INTERVAL 60 day)
					WHERE vrs_dependency BETWEEN -300 AND -1  
					AND vendor_pref.vnp_manual_freeze =0 AND vendors.vnd_active =1 AND vrs_boost_dependency=0
					GROUP BY vrs_vnd_id
					 HAVING cnt>1 ";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public static function checkOutstandingBalence($vendorId)
	{

		$params	 = ['vendorId' => $vendorId];
		$sql	 = "SELECT vrs_outstanding FROM vendor_stats WHERE vrs_vnd_id=:vendorId";
		$result	 = DBUtil::queryRow($sql, null, $params);

		if ($result['vrs_outstanding'] < 0)
		{
			return true;
		}

		return false;
	}

	public function getEligibleBoostDependencyList($minDependencyScore = -300)
	{
		$sql	 = "SELECT DISTINCT vrs_vnd_id, vrs_dependency, vrs_boost_dependency
					FROM `vendor_stats`
					INNER JOIN vendors ON vendors.vnd_id = vendor_stats.vrs_vnd_id
                    INNER JOIN vendor_pref ON vendor_pref.vnp_vnd_id= vendors.vnd_id
					WHERE vrs_dependency BETWEEN $minDependencyScore AND -1  
					AND vendor_pref.vnp_manual_freeze =0 AND vendors.vnd_active =1 AND vrs_boost_dependency=0
					GROUP BY vrs_vnd_id";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public static function gozoNowNotificationStats()
	{
		$sql = "SELECT
					ntl.ntl_entity_id vendorId,
					COUNT(DISTINCT ntl_id) totalSent,
					SUM(IF(ntl.ntl_status IN (0,1) ,1,0)) AS  vrs_delivered_cnt, 
					SUM(IF(ntl.ntl_is_read IN (1,2),1,0)) AS  vrs_received_cnt,
					SUM(IF(ntl.ntl_is_read =1,1,0)) AS  vrs_read_cnt,
					MAX(ntl.ntl_created_on) AS  vrs_delivered_at,
					MAX(IF(ntl.ntl_is_read IN (1,2),ntl.ntl_read_at,null)) AS  vrs_received_at ,
					MAX(IF(ntl.ntl_is_read =1,ntl.ntl_read_at,null))  AS  vrs_read_at
				FROM notification_log ntl 
				WHERE 1 
					AND ntl_active=1
					AND ntl_entity_type=2
					AND ntl_created_on BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
					AND ntl.ntl_event_code=550
					AND ntl.ntl_ref_type=3
				GROUP BY ntl.ntl_entity_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	public static function updateGozoNowNotificationStats($data)
	{
		$success = false;
		if ($data['vendorId'] > 0)
		{
			try
			{
				$vndStatsModel						 = VendorStats::model()->getbyVendorId($data['vendorId']);
				$vndStatsModel->vrs_delivered_cnt	 = $data['vrs_delivered_cnt'];
				$vndStatsModel->vrs_delivered_at	 = $data['vrs_delivered_at'];
				$vndStatsModel->vrs_received_cnt	 = $data['vrs_received_cnt'];
				$vndStatsModel->vrs_received_at		 = $data['vrs_received_at'];
				$vndStatsModel->vrs_read_cnt		 = $data['vrs_read_cnt'];
				$vndStatsModel->vrs_read_at			 = $data['vrs_read_at'];
				if (!$vndStatsModel->save())
				{
					throw new Exception(json_encode($vndStatsModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				$success = true;
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
		return $success;
	}

	public static function getTotalTrips($vndId, $days = 30)
	{
		$totalTrips	 = 0;
		$params		 = ['vendorId' => $vndId, 'days' => $days];
		$sql		 = "SELECT COUNT(bcb_id) as counter FROM booking_cab  
								INNER JOIN booking bkg ON bkg_bcb_id=bcb_id AND bkg_status IN (5,6,7)
								WHERE bcb_vendor_id=:vendorId AND bcb_start_time>=DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY)";
		$totalTrips	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $totalTrips;
	}

}
