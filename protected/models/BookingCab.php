<?php
/**
 * This is the model class for table "booking_cab".
 *
 * The followings are the available columns in table 'booking_cab':
 * @property integer $bcb_id
 * @property integer $bcb_vendor_id
 * @property integer $bcb_brt_id
 * @property string $bcb_bkg_id1
 * @property integer $bcb_trip_status
 * @property double $bcb_vendor_rating
 * @property integer $bcb_vendor_trips
 * @property integer $bcb_vendor_amount
 * @property integer $bcb_vendor_collected
 * @property integer $bcb_driver_id
 * @property double $bcb_driver_rating
 * @property integer $bcb_driver_trips
 * @property string $bcb_driver_name
 * @property string $bcb_driver_phone
 * @property integer $bcb_cab_id
 * @property double $bcb_cab_rating
 * @property integer $bcb_cab_trips
 * @property string $bcb_cab_number
 * @property string $bcb_start_time
 * @property string $bcb_end_time
 * @property integer $bcb_trip_kms
 * @property string $bcb_start_lat
 * @property string $bcb_start_long
 * @property string $bcb_end_lat
 * @property string $bcb_end_long
 * @property integer $bcb_active
 * @property integer $bcb_denied_reason_id
 * @property integer $bcb_trip_type
 * @property integer $bcb_matched_type
 * @property integer $bcb_pending_status
 * @property string $bcb_created
 * @property integer $bcb_assign_mode
 * @property integer $bcb_first_assingment_type
 * @property integer $bcb_last_assingment_type
 * @property integer $bcb_sent_vendor_count
 * @property integer $bcb_minBid
 * @property integer $bcb_max_allowable_vendor_amount
 * @property integer $bcb_maxBid
 * @property integer $bcb_medianBid
 * @property string $bcb_first_request_sent
 * @property integer $bcb_lock_vendor_payment
 * @property string $bcb_cab_assignmenttime
 * @property integer $bcb_is_bid_started
 * @property string $bcb_bid_start_time
 * @property integer $bcb_last_vendor_amount
 * @property integer $bcb_notification_sent
 * @property string $bcb_notify_vendor_info
 * @property string $bcb_additional_params
 * @property integer $bcb_assigned_csr
 * @property integer $bcb_assigned_cs_flag
 * @property string $bcb_vendor_ref_code
 * @property integer $bcb_block_autounassignment
 * 
 * The followings are the available model relations:
 * @property Booking[] $bookings
 * @property Vendors $bcbVendor
 * @property Drivers $bcbDriver
 * @property Vehicles $bcbCab


 */
class BookingCab extends CActiveRecord
{

	public $bkg_driver_cab_message, $chk_user_msg, $bcb_assign_id, $tripStartRegion, $bcb_bcb_id1, $bcb_epass;
	public $isVendorCabFleet, $isVendorDriverFleet;
	public $pre_cab_type, $post_cab_type, $post_cab_msg, $bkg_cab_match_message;
	public $bcb_drv_lic_number, $bcb_drv_lic_exp_date, $bcb_drv_licence_path;
	public $recommended_vendor_amount;
	public $event_by		 = 1;
	public $Cab_trip_type	 = [0 => 'Normal', 1 => 'Matched'];
	public $tripStatus		 = [
		1	 => 'Vendor Unassigned',
		2	 => 'Vendor Assigned',
		3	 => 'Cab Driver Assigned',
		4	 => 'Trip Started',
		5	 => 'Trip Partially Completed',
		6	 => 'Trip Completed'
	];
	public $bcb_assign_mode	 = [0 => 'Auto', 1 => 'Manual', 2 => 'Direct', 3 => 'Gozonow'];

	//  public $booking_id,$vendor_name,$vendor_amount,$routename,$quoted_vendor_amount,$quoted_gozoamount,$gozoAmount,$gozoUnmatchedAmount,$pickupDate,$agent_name;

	const STATUS_VENDOR_UNASSIGNED		 = 1;
	const STATUS_VENDOR_ASSIGNED			 = 2;
	const STATUS_CAB_DRIVER_ASSIGNED		 = 3;
	const STATUS_TRIP_STARTED				 = 4;
	const STATUS_TRIP_PARTIALLY_COMPLETED	 = 5;
	const STATUS_TRIP_COMPLETED			 = 6;

	public function defaultScope()
	{
		$ta	 = $this->getTableAlias(false, false);
		$arr = array(
			'condition' => $ta . ".bcb_active = 1 ",
		);
		return $arr;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_cab';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
//   array('bcb_vendor_id', 'required'),
			array('bcb_vendor_id, bcb_vendor_trips, bcb_driver_id, bcb_driver_trips,bcb_trip_status, bcb_cab_id, bcb_cab_trips,bcb_vendor_amount, bcb_vendor_collected, bcb_active,bcb_pending_status', 'numerical', 'integerOnly' => true),
			array('bcb_vendor_rating, bcb_driver_rating,bcb_trip_type, bcb_cab_rating, bcb_matched_type', 'numerical'),
			['bcb_active', 'required', 'on' => 'deactivate'],
			array('bcb_driver_name', 'length', 'max' => 255),
			array('bcb_driver_phone', 'length', 'max' => 20),
			array('bcb_vendor_id', 'required', 'on' => 'assignvendor'),
			array('bcb_vendor_id', 'checkVendorAssignmentAccess', 'on' => 'assignvendor'),
			array('bcb_vendor_id', 'checkBookingStatus', 'on' => 'assignvendor'),
			array('bcb_vendor_id', 'checkAssignedBookingStatus', 'on' => 'assigncabdriver'),
			array('bcb_trip_status', 'required', 'on' => 'updatetripstatus'),
			array('bcb_vendor_amount', 'required', 'on' => 'updatePendingStatus,matchtrip'),
			array('bcb_id', 'checkBookingStatus', 'on' => 'matchtrip'),
			array('bcb_pending_status', 'required', 'on' => 'markpending'),
			array('bkg_driver_cab_message', 'checkRemark', 'on' => 'assigncabdriver'),
			array('bcb_cab_id', 'checkCabMessage', 'on' => 'assigncabdriver'),
			array('bcb_driver_id,bcb_cab_id', 'required', 'on' => 'assigncabdriver'),
			array('bcb_driver_id', 'checkDriverAssignmentAccess', 'on' => 'assigncabdriver'),
			array('bcb_driver_id', 'checkDriverAssignmentAccessForUber', 'on' => 'assigncabdriver'),
			array('bcb_driver_id', 'checkDriverTimeOverlap', 'on' => 'assigncabdriver'),
			array('bcb_cab_id', 'checkCabAssignmentAccess', 'on' => 'assigncabdriver'),
			array('bcb_cab_id', 'checkCabAssignmentAccessForUber', 'on' => 'assigncabdriver'),
			array('bcb_cab_id', 'checkCabTimeOverlap', 'on' => 'assigncabdriver'),
			array('bcb_cab_id', 'checkServiceClassCondition', 'on' => 'assigncabdriver'),
			//	array('bcb_drv_lic_number', 'checkDriverLicAccess', 'on' => 'assigncabdriver'),
			//	array('bcb_drv_lic_exp_date', 'checkDriverLicExpAccess', 'on' => 'assigncabdriver'),
			array('bcb_id', 'vendorValidate', 'on' => 'unassignvendor'),
			array('bcb_id', 'vendorSelfUnassignValidate', 'on' => 'unassignvendorbyself'),
			array('bcb_id, $bcb_bkg_id1, bcb_vendor_id,bcb_drv_lic_number,bcb_drv_lic_exp_date, bkg_driver_cab_message, bkg_cab_match_message, pre_cab_type, post_cab_type,post_cab_msg, 
				bcb_vendor_amount,bcb_trip_status, bcb_vendor_collected,bcb_cab_number, chk_user_msg, bcb_vendor_rating, bcb_vendor_trips, bcb_driver_id, bcb_driver_rating, bcb_driver_trips,
				 bcb_driver_name, bcb_driver_phone, bcb_cab_id, bcb_cab_rating, bcb_cab_trips, bcb_active,bcb_denied_reason_id, bcb_created,bcb_matched_type,bcb_assign_mode,
				 bcb_is_bid_started,bcb_bid_start_time,
				bcb_sent_vendor_count,bcb_minBid,bcb_maxBid,bcb_medianBid,bcb_first_request_sent,bcb_lock_vendor_payment,bcb_cab_assignmenttime,bcb_max_allowable_vendor_amount,bcb_first_assingment_type,bcb_last_assingment_type,bcb_last_vendor_amount,bcb_notification_sent,bcb_notify_vendor_info,bcb_additional_params,bcb_assigned_csr,bcb_assigned_cs_flag,bcb_vendor_ref_code,bcb_block_autounassignment', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('bcb_id, $bcb_bkg_id1,  bcb_vendor_id, bcb_vendor_rating,bcb_trip_type,bcb_trip_status, bcb_vendor_trips, bcb_driver_id, bcb_driver_rating, bcb_driver_trips, 
				bcb_driver_name, bcb_driver_phone, bcb_cab_id,bcb_pending_status, bcb_cab_rating, bcb_cab_trips, bcb_start_time, bcb_end_time, bcb_trip_kms, bcb_start_lat, bcb_start_long, 
				bcb_end_lat, bcb_end_long, bcb_active, bcb_created, bcb_matched_type, bcb_denied_reason_id, bcb_assigned_csr,bcb_assigned_cs_flag', 'safe', 'on' => 'search'),
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
			'bookings'	 => array(self::HAS_MANY, 'Booking', 'bkg_bcb_id'),
			'bcbVendor'	 => array(self::BELONGS_TO, 'Vendors', 'bcb_vendor_id'),
			'bcbDriver'	 => array(self::BELONGS_TO, 'Drivers', 'bcb_driver_id'),
			'bcbCab'	 => array(self::BELONGS_TO, 'Vehicles', 'bcb_cab_id'),
				//     'bcbBkg' => array(self::BELONGS_TO, 'Booking', 'bcb_bkg_id'),
//	'bookingRoutes' => array(self::HAS_MANY, 'BookingRoute', 'brt_bcb_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bcb_id'				 => 'Bcb',
			'bcb_vendor_id'			 => 'Vendor',
			'bcb_vendor_rating'		 => 'Vendor Rating',
			'bcb_vendor_trips'		 => 'Vendor Trips',
			'bcb_vendor_amount'		 => 'Vendor Amount',
			'bcb_vendor_collected'	 => 'Vendor Collected',
			'bcb_driver_id'			 => 'Driver',
			'bcb_driver_rating'		 => 'Driver Rating',
			'bcb_driver_trips'		 => 'Driver Trips',
			'bcb_driver_name'		 => 'Driver Name',
			'bcb_driver_phone'		 => 'Driver Phone',
			'bcb_cab_id'			 => 'Cab',
			'bcb_cab_rating'		 => 'Cab Rating',
			'bcb_cab_trips'			 => 'Cab Trips',
			'bcb_cab_number'		 => 'Cab Number',
			'bcb_active'			 => 'Active',
			'bcb_created'			 => 'Created',
			'chk_user_msg'			 => 'Send Sms to',
			'bcb_vendor_ref_code'	 => 'Vendor Ref Code',
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

		$criteria->compare('bcb_id', $this->bcb_id);
		$criteria->compare('bcb_vendor_id', $this->bcb_vendor_id);
		$criteria->compare('bcb_vendor_rating', $this->bcb_vendor_rating);
		$criteria->compare('bcb_vendor_trips', $this->bcb_vendor_trips);
		$criteria->compare('bcb_vendor_amount', $this->bcb_vendor_amount);
		$criteria->compare('bcb_vendor_collected', $this->bcb_vendor_collected);
		$criteria->compare('bcb_driver_id', $this->bcb_driver_id);
		$criteria->compare('bcb_driver_rating', $this->bcb_driver_rating);
		$criteria->compare('bcb_driver_trips', $this->bcb_driver_trips);
		$criteria->compare('bcb_driver_name', $this->bcb_driver_name, true);
		$criteria->compare('bcb_driver_phone', $this->bcb_driver_phone, true);
		$criteria->compare('bcb_cab_id', $this->bcb_cab_id);
		$criteria->compare('bcb_cab_rating', $this->bcb_cab_rating);
		$criteria->compare('bcb_cab_trips', $this->bcb_cab_trips);
		$criteria->compare('bcb_cab_number', $this->bcb_cab_number, true);
		$criteria->compare('bcb_active', $this->bcb_active);
		$criteria->compare('bcb_created', $this->bcb_created, true);
		$criteria->comapre('bcb_vendor_ref_code', $this->bcb_vendor_ref_code, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingCab the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBkgid($bkgid)
	{
		$criteria	 = new CDbCriteria;
		$model		 = $this->findAll($criteria);
		if ($model)
		{
			return $model;
		}
		return FALSE;
	}

	public function getCustomerDues()
	{
		$customerDue = 0;
		foreach ($this->bookings as $bkgModel)
		{
			/* @var $bkgModel Booking */
			$customerDue += $bkgModel->bkgInvoice->bkg_due_amount;
		}
		return $customerDue;
	}

	public function getVendorDues()
	{
		$customerDues = $this->getCustomerDues();
		return ($this->bcb_vendor_amount - $customerDues);
	}

	public function checkDriverLicAccess($attribute, $param)
	{
		if (($this->bcbDriver->drvContact->ctt_license_no == NULL) || ($this->bcbDriver->drvContact->ctt_license_no == ''))
		{
			$status = $this->bcbDriver->getDriverApproveStatus();
			if ($status == false && ($this->bcb_drv_lic_number == '' || $this->bcb_drv_lic_number == NULL))
			{
				$this->addError($attribute, "License Number is required. Make sure this is correct number as on license. Wrong information will attract penalty.");
				return false;
			}
			else
			{
				if ($this->bcb_drv_lic_number != '')
				{
					$sql	 = "SELECT DISTINCT drv_id
								FROM  `drivers`
								INNER JOIN contact ON contact.ctt_id=drv_contact_id
								INNER JOIN `vendor_driver` ON drivers.drv_id = vendor_driver.vdrv_drv_id AND vendor_driver.vdrv_active = 1
								WHERE drv_active = 1 AND contact.ctt_license_no = '$this->bcb_drv_lic_number'";
					$result	 = DBUtil::command($sql)->queryScalar();
					if ($status == false && $result > 0)
					{
						$this->addError($attribute, "Licence Number already exists.");
						return false;
					}
				}
			}
			return true;
		}
		return true;
	}

	public function checkDriverLicExpAccess($attribute, $param)
	{
		if (($this->bcbDriver->drv_lic_exp_date == NULL) || ($this->bcbDriver->drv_lic_exp_date == '') || ($this->bcbDriver->drv_lic_exp_date == '1970-01-01'))
		{
			$status = $this->bcbDriver->getDriverApproveStatus();
			if ($status == false && ($this->bcb_drv_lic_exp_date == NULL || $this->bcb_drv_lic_exp_date == '' || $this->bcb_drv_lic_exp_date == '1970-01-01'))
			{
				$this->addError($attribute, "Licence expiry date is required and must match driver license picture.");
				return false;
			}
		}
		return true;
	}

	public function checkBookingStatus($attribute, $param)
	{
		$bookings = $this->bookings;
		foreach ($bookings as $bkgModel)
		{
			if ($bkgModel->bkgBcb->bcb_matched_type == 1 || $bkgModel->bkgBcb->bcb_pending_status == 1)
			{
				if (!in_array($bkgModel->bkg_status, [2, 9]))
				{
					$this->addError($attribute, "Booking ({$bkgModel->bkg_booking_id}) not active or already assigned or cancelled");
					return false;
				}
			}
			else
			{
				if ($bkgModel->bkg_status != 2)
				{
					$this->addError($attribute, "Booking ({$bkgModel->bkg_booking_id}) not active or already assigned");
					return false;
				}
			}
		}
		return true;
	}

	public function checkAssignedBookingStatus($attribute, $param)
	{
		$bookings = $this->bookings;
		foreach ($bookings as $bkgModel)
		{
			if (($bkgModel->bkgBcb->bcb_matched_type == 1) || ($bkgModel->bkgBcb->bcb_pending_status == 1))
			{
				return true;
			}
			else
			{
				if (!in_array($bkgModel->bkg_status, [3, 5]))
				{
					$this->addError($attribute, "Booking ({$bkgModel->bkg_booking_id}) already assigned");
					return false;
				}
			}
		}
		return true;
	}

	public function checkVendorAssignmentAccess($attribute, $param)
	{
		$status = $this->bcbVendor->vendorPrefs->getApproveSatus();
		if ($status)
		{
			goto result;
		}

		// outstanding functionality removed due to new feature vendor can pay  outstanding balence at the time of direct accept According to Ak 15-06-23.
		/* $checkOutstanding = VendorStats::checkOutstanding($this->bcbVendor->vnd_id); 

		  /*$checkOutstanding = VendorStats::checkOutstanding($this->bcbVendor->vnd_id); // this checking remove for security amount feature in vendor app

		  if ($checkOutstanding)
		  {
		  //check vendor due status
		  $chekVendorDue = $this->chkVendorDue($this->bcb_id);

		  if ($chekVendorDue)
		  {
		  goto result;
		  }
		  } */
		$this->tripStartRegion	 = $this->bookings[0]->bkgFromCity->ctyState->stt_zone;
		$checkAccess			 = Filter::checkAssignmentAccess($this->tripStartRegion);
		if (!$checkAccess)
		{
			$vndStat = $this->bcbVendor->vendorPrefs->getStatusDesc();
			$this->addError($attribute, 'Assignment failed. Account ' . $vndStat);
			return false;
		}
		result:
		return true;
	}

	public function chkVendorDue($bcbId)
	{
		$params	 = ['bcbId' => $bcbId];
		$sql	 = "SELECT (bcb.bcb_vendor_amount -SUM(biv.bkg_total_amount-biv.bkg_net_advance_amount)) as vendor_due FROM booking_cab bcb
			        INNER JOIN booking bkg ON bkg.bkg_bcb_id=bcb.bcb_id
			        INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
			         WHERE bcb.bcb_id =  :bcbId
			         GROUP BY bcb.bcb_id";

		$res = DBUtil::queryScalar($sql, '', $params);

		if ($res > 0)
		{
			return true;
		}

		return false;
	}

	public function checkDriverAssignmentAccess($attribute, $param)
	{
//if($this->bcbDrive->drv_active == 1 && $this->bcbDrive->drv_approved == 1 && $this->bcbDrive->drv_is_freeze == 0){
//
//}
		$operatorId = Config::get('hornok.operator.id');
		if ($this->bcb_vendor_id == $operatorId)
		{
			goto result;
		}

		$status = $this->bcbDriver->getDriverApproveStatus();
		if ($status)
		{
			goto result;
		}
//		$this->tripStartRegion	 = $this->bookings[0]->bkgFromCity->ctyState->stt_zone;
//		$checkAccess			 = Filter::checkAssignmentAccess($this->tripStartRegion);
		$checkAccess = Yii::app()->user->checkAccess('CriticalAssignment');
		if (!$status && !$checkAccess)
		{
			$this->addError($attribute, "Can not assign unapproved/freezed/blocked driver");
			return false;
		}
		$driverRatingTrips		 = Booking::model()->getRatingTrips('driver', $this->bcb_driver_id);
		$this->bcb_driver_rating = $driverRatingTrips['rating'];
		$this->bcb_driver_trips	 = $driverRatingTrips['trips'];
		$this->bcb_driver_name	 = $this->bcbDriver->drv_name;
		$this->bcb_driver_phone	 = ( $this->bcb_driver_phone == '') ? $this->bcbDriver->drv_phone : $this->bcb_driver_phone;

		if ($driverRatingTrips['rating'] <= 2.5 && !$checkAccess)
		{
			$this->addError($attribute, 'The driver rating is low ');
			return false;
		}

		result:
		return true;
	}

	public function checkCabAssignmentAccess($attribute, $param)
	{
		$operatorId = Config::get('hornok.operator.id');
		if ($this->bcb_vendor_id == $operatorId)
		{
			goto result;
		}

		$vehicleModel = $this->bcbCab;
		if (!$vehicleModel)
		{
			$this->addError($attribute, 'Can not assign inactive cabs');
			return false;
		}
		$status			 = $vehicleModel->getVehicleApproveStatus();
		$hasCngAllowed	 = $this->isCngAllowed($this->bcb_bkg_id1, $this->bcbCab);
		$checkVhcModel	 = $this->checkSimilarModel($this->bcb_bkg_id1, $this->bcbCab);
		if ($status && $hasCngAllowed['success'] && $checkVhcModel['success'])
		{
			goto result;
		}

		if (!$checkVhcModel['success'])
		{
			$this->addError($attribute, $checkVhcModel['msg']);
			return false;
		}
//		$this->tripStartRegion	 = $this->bookings[0]->bkgFromCity->ctyState->stt_zone;
//		$checkAccess			 = Filter::checkAssignmentAccess($this->tripStartRegion);
		$checkAccess = Yii::app()->user->checkAccess('CriticalAssignment');
		if (!$status && !$checkAccess)
		{
			$this->addError($attribute, 'Can not assign unapproved/freezed/blocked cabs');
			return false;
		}
		if (!$hasCngAllowed['success'])
		{
			$this->addError($attribute, $hasCngAllowed['msg']);

			return false;
		}
		$vehicleRatingTrips		 = Booking::model()->getRatingTrips('car', $this->bcb_cab_id);
		$this->bcb_cab_rating	 = $vehicleRatingTrips['rating'];
		$this->bcb_cab_trips	 = $vehicleRatingTrips['trips'];
		$this->bcb_cab_number	 = $vehicleRatingTrips['number'];

		if ($vehicleRatingTrips['rating'] <= 2.5 && !$checkAccess)
		{
			$this->addError($attribute, 'The cab rating is low.');
			return false;
		}

		result:
		return true;
	}

	public function checkServiceClassCondition($attribute, $param)
	{
		$operatorId = Config::get('hornok.operator.id');
		if ($this->bcb_vendor_id == $operatorId)
		{
			return true;
		}
		$cabYear		 = $this->bcbCab->vhc_year;
		$bookingModel	 = Booking::model()->findByPk($this->bcb_bkg_id1);
		$vehicleTypeId	 = $bookingModel->bkg_vehicle_type_id;
		$sccId			 = SvcClassVhcCat::model()->getClassById($vehicleTypeId);
		$srvClassModel	 = ServiceClass::model()->findByPk($sccId);
		$now			 = date('Y');
		$diff			 = ($now - $cabYear);
		/* if ($this->bcbCab->vhc_end_odometer != "" && $this->bcbCab->vhc_odometer_modified_on != "")
		  {
		  $odometer	 = Vehicles::getEstimatedOdometer($this->bcbCab->vhc_end_odometer, $this->bcbCab->vhc_odometer_modified_on);
		  $rules		 = ServiceClass::model()->findByPk($sccId);
		  if ($odometer > $rules->scc_odometer)
		  {
		  $this->addError($attribute, "Odometer reading of this vehicle must be less than {$rules->scc_odometer}");
		  return false;
		  }
		  } */
//		if ($diff > $srvClassModel->scc_model_year)
//		{
//			$this->addError($attribute, "Cab should be {$srvClassModel->scc_model_year} year or newer car");
//			return false;
//		}
		/*  the below code show if dop found in vehicle then it will check vhc_dop of  life of the cab and if it exceed the life of value
		 * and value+ then give error accordingly */


		$cabregdate = $this->bcbCab->vhc_dop;
		if (!empty($cabregdate) && $cabregdate != '')
		{
			$date1		 = date("Y-m-d", strtotime($cabregdate));
			$ystr		 = "-" . $srvClassModel->scc_model_year . " year";
			$date2		 = date("Y-m-d", strtotime($ystr));
			$datediff	 = strtotime($date2) - strtotime($date1);
			if ($datediff > 0)
			{
				$this->addError($attribute, "Cab should be less than {$srvClassModel->scc_model_year} year or newer car");
				return false;
			}
		}
		else if ($diff >= $srvClassModel->scc_model_year)
		{
			$this->addError($attribute, "Cab should be less than {$srvClassModel->scc_model_year} year or newer car");
			return false;
		}

		if ($this->bcbCab->vhc_has_cng == 1 && $srvClassModel->scc_is_cng == 0 && $bookingModel->bkgPref->bkg_cng_allowed == 0)
		{
			$this->addError($attribute, "Can't assign CNG cab for this booking.");
			return false;
		}
//        if ($this->bcbCab->vhc_has_cng == 0 && $sccId == 6)
//		{
//			$this->addError($attribute, "Only CNG cab is allowed for this booking.");
//			return false;
//		}
		$driverRatingTrips = DriverStats::model()->getOverallRatingbyDriverId($this->bcb_driver_id);
		if ($driverRatingTrips['driver_rating'] < 4 && $sccId == 3)
		{
			$this->addError($attribute, 'The driver rating is low ');
			return false;
		}

		result:
		return true;
	}

	public function checkDriverAssignmentAccessForUber($attribute, $param)
	{
		$uberAgentId = Yii::app()->params['uberAgentId'];
		$agents		 = $this->getAgentsByBcbId($this->bcb_id);
		$status		 = true;
		foreach ($agents as $agent)
		{
			if (($agent['bkg_agent_id'] != null || $agent['bkg_agent_id'] != '') && $agent['bkg_agent_id'] == $uberAgentId)
			{
				$status = $this->bcbDriver->getDriverApproveStatusForUber();
			}
		}

		if (!$status)
		{
			$this->addError($attribute, 'This Driver is not approved for uber booking');
		}
		return $status;
	}

	public function checkCabAssignmentAccessForUber($attribute, $param)
	{
		$uberAgentId = Yii::app()->params['uberAgentId'];
		$agents		 = $this->getAgentsByBcbId($this->bcb_id);
		$status		 = true;
		foreach ($agents as $agent)
		{
			if (($agent['bkg_agent_id'] != null || $agent['bkg_agent_id'] != '') && $agent['bkg_agent_id'] == $uberAgentId)
			{
				$status = $this->bcbCab->getVehicleApproveStatusForUber();
			}
		}

		if (!$status)
		{
			$this->addError($attribute, 'This Cab is not approved for uber booking');
		}
		return $status;
	}

	public function checkCabTimeOverlap($attribute, $param)
	{
		$overlapTrips	 = $this->checkCabActiveTripTiming();
		$userInfo		 = UserInfo::getInstance();
		if ($userInfo->userType != 4)
		{
			if ($overlapTrips > 0)
			{
				$this->addError($attribute, 'The cab is already assigned to another booking during this time period');
				return false;
			}
		}
		return true;
	}

	public function checkDriverTimeOverlap($attribute, $param)
	{
		$overlapTrips	 = $this->checkDriverActiveTripTiming();
		$userInfo		 = UserInfo::getInstance();
		if ($userInfo->userType != 4)
		{
			if ($overlapTrips > 0)
			{
				$this->addError($attribute, 'The Driver is already serving another booking during this time period');
				return false;
			}
		}
		return true;
	}

	public function checkRemark($attribute, $param)
	{
		if (trim($this->bcb_cab_id) != '' || trim($this->bcb_driver_id) != '')
		{
//            $markedBadDriver = Drivers::model()->checkDriverMarkCount($this->bcb_driver_id);
//            $markedBadVehicle = Vehicles::model()->checkVehicleMarkCount($this->bcb_cab_id);

			$operatorId = Config::get('hornok.operator.id');
			if ($this->bcb_vendor_id == $operatorId)
			{
				return true;
			}

			$drvmodel	 = Drivers::model()->getById($this->bcb_driver_id);
			$vhcModel	 = Vehicles::model()->findByPk($this->bcb_cab_id);

			$markedBadDriver	 = ($drvmodel->drv_mark_driver_count > 0) ? $drvmodel->drv_mark_driver_count : 0;
			$markedBadVehicle	 = ($vhcModel->vhc_mark_car_count > 0) ? $vhcModel->vhc_mark_car_count : 0;
			$isDrvApproved		 = $drvmodel->drv_approved;
			$isVhcApproved		 = $vhcModel->vhc_approved;

//Check duration overlapping
			$bcbid		 = $this->bcb_id;
			$cabid		 = $this->bcb_cab_id;
			$bmodels	 = $this->bookings;
			$pickupTime	 = $bmodels[0]->bkg_pickup_date;
			$dropTime	 = date('Y-m-d H:i:s', strtotime($bmodels[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));

			foreach ($bmodels as $bmodel)
			{
				$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
				$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
				$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
			}
//			$overlapTrips = BookingCab::model()->checkCabActiveTripTiming($cabid, $bcbid, $pickupTime, $dropTime);
//			if ($overlapTrips > 0)
//			{
//				if ($this->bkg_driver_cab_message == '')
//				{
//					$this->addError('bkg_driver_cab_message', 'This Cab is already assigned to other booking for the trip duration..
//                            Please specify the reason for assigning it. ');
//				}
//				return false;
//			}

			if ($markedBadDriver > 0 || $markedBadVehicle > 0)
			{
				if ($this->bkg_driver_cab_message == '')
				{
					$this->addError('bkg_driver_cab_message', 'Remark is required');
				}
				return false;
			}
			if ($isDrvApproved != 1 || $isVhcApproved != 1)
			{
				if ($this->bkg_driver_cab_message == '')
				{
					$this->addError('bkg_driver_cab_message', 'Cab/Driver is unapproved.');
				}
				return false;
			}
		}
		return true;
	}

	public function checkCabMessage($attribute, $param)
	{
		$operatorId = Config::get('hornok.operator.id');
		if ($this->bcb_vendor_id == $operatorId)
		{
			return true;
		}

		$pre_cab	 = VehicleTypes::model()->getCarByCarType($this->pre_cab_type);
		$post_cab	 = VehicleTypes::model()->getCarByCarType($this->post_cab_type);

		$vehicleModel = $this->bcbCab->vhcType->vht_model;
		if ($this->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = OperatorVehicle::getCabModelName($this->bcb_vendor_id, $this->bcb_cab_id);
		}

		switch ($this->pre_cab_type)
		{
			case 1:  //compact
				$return				 = (in_array($this->post_cab_type, [1, 2, 3])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 2:  // suv
				$return				 = (in_array($this->post_cab_type, [2, 13])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 3:  //sedan 
				$return				 = (in_array($this->post_cab_type, [2, 3])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 4:  //tempo traveller
				$return				 = (in_array($this->post_cab_type, [4])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 5:  // assured Dzire
				//$return				 = ($this->bcbCab->vhc_type_id == 4) ? true : false;
				$return				 = ($this->bcbCab->vhc_type_id == 4) || (in_array($this->post_cab_type, [2, 3])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : " . $vehicleModel : '';
				break;
			case 6: // assured innova
				$return				 = in_array($this->bcbCab->vhc_type_id, [22, 24, 65]) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : " . $vehicleModel : '';
				break;
			case 7:  // tempo traveller 9 seater
				$return				 = (in_array($this->post_cab_type, [7, 8, 9, 10])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 8:  // tempo traveller 12 seater
				$return				 = (in_array($this->post_cab_type, [8, 9, 10])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 9:  //tempo traveller 15 seater
				$return				 = (in_array($this->post_cab_type, [9, 10])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 10:  // tempo travelller 19 seater
				$return				 = (in_array($this->post_cab_type, [10])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 11:  // shared sedan
				$return				 = (in_array($this->post_cab_type, [2, 3, 11])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 12:  //tempo traveller 26 seater
				$return				 = (in_array($this->post_cab_type, [12])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 13:  // SUV 7+1
				$return				 = (in_array($this->post_cab_type, [2, 13])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
			case 14:  // tempo traveller 17 seater
				$return				 = (in_array($this->post_cab_type, [14, 10, 12])) ? true : false;
				$this->post_cab_msg	 = ($return == false) ? "Cannot assign. This booking is for category : $pre_cab. Car being assigned belongs to category : $post_cab" : '';
				break;
		}
		if ($return == false)
		{
			$this->addError('bcb_cab_id', $this->post_cab_msg);
			return false;
		}
		return true;
	}

	public function vendorValidate($attribute, $param)
	{
		$model	 = $this;
		$bModels = $model->bookings;
		$succ	 = true;
		foreach ($bModels as $bModel)
		{
			if (!in_array($bModel->bkg_status, [3, 4, 5, 9]))
			{

				$model->addError('bcb_id', 'Unassign failed. Please refresh your booking list & try again');
				$succ = false;
				break;
			}
		}
		return $succ;
	}

	public function vendorSelfUnassignValidate($attribute, $param)
	{
		$model	 = $this;
		$bModels = $model->bookings;
		$succ	 = true;
		foreach ($bModels as $bModel)
		{
			if (in_array($bModel->bkg_status, [3, 4, 5]))
			{
				$diff = floor((strtotime($bModel->bkg_pickup_date) - time()) / 3600);
				if ($diff < 4)
				{
					$model->addError('Error', 'Too late. Less than 4 hours left for pickup');
					$succ = false;
					break;
				}
			}
			else
			{
				$model->addError('Error', 'Unassign failed. Please refresh your booking list & try again');
				$succ = false;
				break;
			}
		}
		return $succ;
	}

	/** @return ReturnSet */
	public function assignVendor($tripId, $vndId, $tripAmount = null, $remarks = '', UserInfo $userInfo = null, $assignMode = 0, $smt_score = null, $directAcpt = null)
	{
		$sccBkgId	 = false;
		/* update smt Trip */
		$transaction = DBUtil::beginTransaction();
		$return		 = ["success" => true];
		$returnSet	 = new ReturnSet();
		$model		 = $this->findByPk($tripId);
		try
		{
			$oldTripVendorAmount	 = $model->bcb_vendor_amount;
			$model->scenario		 = 'assignvendor';
			$model->bcb_vendor_id	 = $vndId;
			$model->bcb_assign_mode	 = $assignMode;
			// show bid time
			$bidTime				 = BookingVendorRequest::showBidTime($tripId, $vndId);
			$bidTime				 = strtotime($bidTime);
			$futureDate				 = $bidTime + (60 * 5);
			$currentDate			 = time();
			if ($futureDate != null && $futureDate != "" && $currentDate <= $futureDate && $assignMode == 0)
			{
				$model->bcb_assign_mode = 2;
			}
			if ($model->bcb_first_assingment_type == NULL && $model->bcb_bkg_id1 != NULL)
			{
				$firstAssignment					 = BookingCab::getFirstAssignmentType($model->bcb_bkg_id1);
				$model->bcb_first_assingment_type	 = $firstAssignment != null ? $firstAssignment : $assignMode;
			}
			else
			{
				$model->bcb_first_assingment_type = $assignMode;
			}
			$model->bcb_last_assingment_type = $assignMode;
			$model->bcb_cab_assignmenttime	 = $this->getCabAssignmentTime();
			foreach ($model->bookings as $bModel)
			{
				$CSarr[] = $bModel->bkgPref->bkg_critical_score;
			}
			$model->bcb_last_critical_score = max($CSarr);
			if ($tripAmount > 0)
			{
				$model->bcb_vendor_amount = $tripAmount;
			}
			$vendorRatingTrips			 = Booking::model()->getRatingTrips('vendor', $vndId);
			$model->bcb_vendor_rating	 = trim($vendorRatingTrips['rating']);
			$model->bcb_vendor_trips	 = trim($vendorRatingTrips['trips']);

			if (Config::get('hornok.operator.id') != $vndId)
			{
				$result = CActiveForm::validate($model, null, false);
				if ($model->getErrors())
				{
					throw new Exception(json_encode($model->getErrors()), 1);
				}
			}

			$res = $model->save();
			if (!$res)
			{
				throw new Exception(json_encode($model->getErrors()), 1);
			}
			$valBooking	 = true;
			$cntBooking	 = count($model->bookings);

			foreach ($model->bookings as $bmodel)
			{
				if ($bmodel->bkgPref->bkg_manual_assignment == 1 && $cntBooking > 1)
				{
					$valBooking = false;
				}
			}
			$model->updateCab();

			$bookings = $model->bookings;
			foreach ($bookings as $bkgModel)
			{
				if ($bkgModel->bkg_status == 2)
				{
					try
					{
						$serviceResult = ServiceCallQueue::countDemMisFireByBkgId($bkgModel->bkg_id);
						if ($serviceResult['scq_id'] > 0)
						{
							ServiceCallQueue::updateStatus($serviceResult['scq_id'], 10, 0, "CBR expired.No action taken");
						}
						if ($bkgModel->bkgPref->bpr_askmanual_assignment == 1)
						{
							$bkgModel->bkgPref->bpr_askmanual_assignment = 0;
							$bkgModel->bkgPref->save();
						}

						BookingTrack::updateVendorReadyToPickupConfirmation($bkgModel->bkg_id);
					}
					catch (Exception $ex)
					{
						ReturnSet::setException($ex);
					}
					$bkgModel->assignVendor($model, $remarks, $userInfo, $assignMode, $valBooking, $smt_score);
					$sccBkgId = $bkgModel->bkg_id;
				}
			}
			// in case of manual asign make entry in booking vendor request
			if ($assignMode == 1)
			{
				$status = 'manualAssign';
			}

			if (Config::get('hornok.operator.id') != $vndId)
			{
				$success = BookingVendorRequest::model()->createRequest($model->bcb_vendor_amount, $tripId, $vndId, $status);
			}

			if ($sccBkgId > 0)
			{
				$blgCsrId	 = BookingLog::getCsrIdByVndId($model->bcb_vendor_id, $sccBkgId);
				$scqCsrId	 = ServiceCallQueue::getDispatchCsrByBookingId($sccBkgId);
				$csrId		 = ($blgCsrId == '' ? $scqCsrId['scq_assigned_uid'] : $blgCsrId);
				if (($csrId == '' || $csrId == null) && $userInfo->userType == 4)
				{
					$csrId = $userInfo->userId;
				}
				$manualFlag			 = $bmodel->bkgPref->bkg_manual_assignment;
				$criticalFlag		 = $bmodel->bkgPref->bkg_critical_assignment;
				$csFlag				 = ($criticalFlag == 1 ? 2 : ($manualFlag == 1 ? 1 : 0));
				$csrUpdateSuccess	 = BookingCab::updateAssignedCsr($tripId, $csrId, $csFlag);
			}
			//calculate vendor coin amount for showing coin earning scope for particular booking"
			/* $totalCoin = VendorCoins::model()->calculateCoin($bookingId);
			  $message = "You will be able to earn upto ".$totalCoin." coins for 5 star rating if you received from customer.";
			  $return["coinMsg"] = $message; */
			// update assign date according to accept mode
			VendorStats::updateAssignDate($vndId, $assignMode);
			DBUtil::commitTransaction($transaction);

			$dataArray		 = array('vendorId' => $vndId, 'tripId' => $tripId, 'tripAmount' => $model->bcb_vendor_amount, 'isDirectAccept' => $directAcpt, 'userInfo' => $userInfo);
			$assignedData	 = CJSON::encode($dataArray);
			$bkdIds			 = explode(",", $model->bcb_bkg_id1);
			BookingScheduleEvent::add($bkdIds[0], BookingScheduleEvent::POST_VENDOR_ASSIGNMENT, "Post vendor Assigned", $assignedData);

			/* Skip this process for quickRide */


			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet			 = ReturnSet::setException($e);
			$return["success"]	 = false;
			$code				 = $e->getCode();
			$message			 = $e->getMessage();

			if ($code == 1)
			{
				$message = json_decode($message);
			}

			$return["errors"] = $returnSet->getErrors();
		}
		result:
		return $returnSet;
	}

	public function assignCabDriver($cabId, $driverId, $cab_type, UserInfo $userInfo = null)
	{
		Logger::trace("cab data==>" . $cabId . $driverId . $cab_type);
		$model = $this;
		if ($cab_type != '')
		{
			$this->pre_cab_type	 = $cab_type;
			//$this->post_cab_type = $model->bcbCab->vhcType->vht_car_type;
			$this->post_cab_type = $model->bcbCab->vhcType->vht_VcvCatVhcType->vcv_vct_id;
		}
		$model->scenario		 = 'assigncabdriver';
		$bmodels				 = $model->bookings;
		$model->bcb_driver_id	 = $driverId;
		Preg_match("/\d*(\d{10})/", $this->bcb_driver_phone, $match);
		if (empty($match))
		{
			$model->addError('bcb_driver_id', 'Driver Phone No is missing.');
			return false;
		}
		$model->bcb_driver_phone = $match[1];
		$model->bcb_cab_id		 = $cabId;
		$model->bcb_cab_number	 = strtoupper($this->bcbCab->vhc_number);
		$model->bcb_trip_status	 = BookingCab::STATUS_CAB_DRIVER_ASSIGNED;
		Logger::trace("cab driver data==>" . $model->bcb_cab_id . $model->bcb_driver_id);

		$transaction = DBUtil::beginTransaction();
		try
		{

			$validate = $model->save();
			if (!$validate)
			{
				$errors		 = $model->getErrors();
				$errorStr	 = $errors[key($errors)][0];
				if (trim($errorStr) == '')
				{
					$errorStr = 'Validation Failed';
				}
				throw new Exception($errorStr, 10);
			}

			$chk	 = $this->chk_user_msg;
			$user	 = $vendor	 = $driver	 = false;
			if ($chk != null && is_array($chk))
			{
				$user	 = false; //(in_array(0, $chk)) ? true : false;
				$driver	 = false; //(in_array(1, $chk)) ? true : false;
				$vendor	 = (in_array(2, $chk)) ? true : false;
			}
			$desc	 = "Cab and driver updated";
			$eventid = BookingLog::CAB_DETAILS_UPDATED;
			if ($userInfo->userType == 4)
			{
				$overlapTrips = $model->checkCabActiveTripTiming();
				if ($overlapTrips > 0)
				{
					$desc = "Cab and driver updated with already on the way warning";
				}
			}
			foreach ($bmodels as $bmodel)
			{
				try
				{
					$queueId		 = ServiceCallQueue::TYPE_BAR . "," . ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL;
					$serviceResult	 = ServiceCallQueue::countUnAssignQueueByBkgId($bmodel->bkg_id, $queueId);
					if ($serviceResult['scq_id'] > 0)
					{
						ServiceCallQueue::updateStatus($serviceResult['scq_id'], 10, 0, "CBR expired.No action taken");
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
				$bmodel->assigncabdriver($userInfo, $desc, $eventid);

				$model->sendCabAssignMessage($user, $driver, $vendor, $bmodel->bkg_id, $bmodel->checkMinimumPickupTime(), $userInfo->userType);
				$driver	 = false;
				$vendor	 = false;
				continue;
			}
			DBUtil::commitTransaction($transaction);

			if (!$model->bcbCab->getVehicleApproveStatus())
			{
				try
				{
					$bmodel->bkgTrail->refresh();
					$datetime								 = date('d/m/Y h:i A', strtotime('+24 HOUR'));
					$desc									 = "Papers to be submitted for car $cabNumber  by $datetime";
					$bmodel->bkgTrail->bkg_follow_type_id	 = 10;
					$bmodel->bkgTrail->bkg_followup_date	 = new CDbExpression('DATE_ADD(NOW(),INTERVAL 36 HOUR)');
					$bmodel->bkgTrail->bkg_followup_comment	 = $desc;
					$bmodel->bkgTrail->bkg_followup_active	 = 1;
					if ($bmodel->save())
					{
						$bmodel->bkgTrail->save();
						$status				 = $bmodel->bkg_status;
						$userInfo			 = UserInfo::model();
						$userInfo->userType	 = UserInfo::TYPE_SYSTEM;
						BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, BookingLog::FOLLOWUP_ASSIGN, false, false);
						$success			 = true;
					}
					else
					{
						$success = false;
					}
					$payLoadData2	 = ['tripId' => $model->bcb_id, 'EventCode' => Booking::CODE_MISSING_PAPERWORK];
					$success		 = AppTokens::model()->notifyVendor($model->bcb_vendor_id, $payLoadData2, $desc, "Paperwork required for car $cabNumber");
				}
				catch (Exception $ex)
				{
					$success = false;
				}
			}
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			if ($e->getCode() != 10)
			{
				$model->addError('bkg_id', $e->getMessage());
				$errorDesc = $e->getMessage();
				$model->logFailedCabDriverAssignment($errorDesc, $userInfo);
			}
			return false;
		}
		return true;
	}

	public function sendCabAssignMessage($user = false, $driver = false, $vendor = true, $bkgid, $sendContact = false, $logType = '', $notifyVendor = true, $userType = '', $needArr = [])
	{
		$bmodel		 = Booking::model()->findByPk($bkgid);
		$model		 = $bmodel->getBookingCabModel();
		$oldModel	 = clone $bmodel;

		$amount = $bmodel->bkgInvoice->bkg_total_amount;
		if ($model->bcb_vendor_id != '' && $bmodel->event_by != 2 && $notifyVendor)
		{
			$bcount		 = count($model->bookings);
			$first_city	 = Cities::getName($model->bookings[0]->bkg_from_city_id);
			$pickup_date = date("d M Y h:i A", strtotime($bmodel->bkg_pickup_date)); //$this->getPickupDateTime("d M Y h:i A", $bmodel->bkg_pickup_date);
			$last_city	 = Cities::getName($model->bookings[$bcount - 1]->bkg_to_city_id);
			$payLoadData = ['tripId' => $model->bcb_id, 'EventCode' => Booking::CODE_CABDRIVER_ASSIGNED];
			$success	 = AppTokens::model()->notifyVendor($model->bcb_vendor_id, $payLoadData, "Trip Id: " . $model->bcb_id . ", " . $first_city . "-" . $last_city . ", " . $pickup_date, "Cab details has been updated (" . $model->bcb_id . ")");
		}
		if ($bmodel->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = round($bmodel->bkgInvoice->bkg_advance_amount);
		}
		$dueAmount	 = $amount - $bmodel->bkgInvoice->getTotalPayment();
		$due		 = round($dueAmount);
		$contactData = Drivers::findByDriverId($model->bcb_driver_id);
		if (empty($contactData))
		{
			$driverName	 = $model->bcb_driver_name;
			$driverPhone = '+91' . $model->bcb_driver_phone;
		}
		else
		{
			$driverName	 = $contactData['ctt_first_name'] . ' ' . $contactData['ctt_last_name'];
			$cntDetails	 = Contact::getContactDetails($contactData['ctt_id']);
			$driverPhone = '+91' . $cntDetails['phn_phone_no'];
		}
		$bookingId	 = $bmodel->bkg_booking_id;
		$cabNumber	 = $model->bcbCab->vhc_number;
		$date		 = DateTimeFormat::DateTimeToDatePicker($bmodel->bkg_pickup_date);
		$time		 = date('h:i A', strtotime($bmodel->bkg_pickup_date));
		$cabType	 = $model->bcbCab->vhcType->resetScope()->getVehicleModel();
		$address	 = $bmodel->bkg_pickup_address;

		$ext		 = $bmodel->bkgUserInfo->bkg_country_code;
		$unumbernew	 = $bmodel->bkgUserInfo->bkg_contact_no;

		if ($bmodel->bkg_agent_id == 30228)
		{
			$user = true;
		}


		$sendSms	 = 1;
		$sendEmail	 = 1;
		if (count($needArr) > 0)
		{
			$sendSms	 = $needArr['send_sms'];
			$sendEmail	 = $needArr['send_email'];
		}

		notificationWrapper::driverDetailsToCustomer($bmodel->bkg_id, false, $sendSms, UserInfo::TYPE_SYSTEM);

//		if ($unumbernew != '' && $user && $bmodel->bkgPref->bkg_send_sms == 1 && $sendSms == 1)
//		{
//			$msgCom		 = new smsWrapper();
//			$logType1	 = UserInfo::TYPE_SYSTEM;
//			$msgCom->msgToUserBookingConfirmed($bmodel, $type		 = 2, '', $logType1);
//		}
		$dnumber	 = (!empty($cntDetails['phn_phone_no'])) ? $cntDetails['phn_phone_no'] : $model->bcb_driver_phone;
		$dext		 = '91';
		$vendorPhone = ContactPhone::getContactPhoneById($model->bcbVendor->vndContact->ctt_id); //$model->bcbVendor->vnd_phone;
		$vendorext	 = '91';
		$vendorName	 = $model->bcbVendor->vndContact->getName();
		$vendorName	 = ($vendorName != '') ? $vendorName : $model->bcbVendor->vnd_name;
		$cust_name	 = $bmodel->bkgUserInfo->getUsername();
		$cust_number = "+" . $ext . $unumbernew;

		// Pickup Date check for UBER agent
		$vendor_pickup_date	 = $model->getPickupDateTime("Y-m-d H:i:s", $bmodel->bkg_pickup_date);
		$date_time			 = DateTimeFormat::DateTimeToLocale($vendor_pickup_date);
		$amountToCollect	 = $due;
		if ($model->bcb_driver_id != '')
		{
			$driverDet = $driverName . " - " . $driverPhone;
			if ($logType != 10)
			{
				BookingLog::model()->createLog($bmodel->bkg_id, 'Driver( ' . $driverDet . ' )', UserInfo::getInstance(), BookingLog::DRIVER_ASSIGNED, false, false);
			}
		}
		if ($model->bcb_cab_id != '')
		{
			$vehicleModel = $model->bcbCab->vhcType->vht_model;
			if ($model->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
			{
				$vehicleModel = OperatorVehicle::getCabModelName($model->bcb_vendor_id, $model->bcb_cab_id);
			}

			$cab	 = $vehicleModel . " - " . $model->bcbCab->vhc_number;
			$event	 = ($model->bcbCab->vhcStat->vhs_is_partition == 1) ? BookingLog::CAB_PARTITIONED : BookingLog::CAB_ASSIGNED;
			if ($logType != 10)
			{
				BookingLog::model()->createLog($bmodel->bkg_id, 'Cab( ' . $cab . ' )', UserInfo::getInstance(), $event, false, false);
			}
		}
		if ($model->bcb_driver_phone != '' && $driver)
		{
			$drvid	 = $model->bcb_driver_id;
			$type	 = SmsLog::Driver;
			$msgCom	 = new smsWrapper();
			$logType = UserInfo::TYPE_SYSTEM;
			$msgCom->pickupDetailsToDriver($dext, $dnumber, $drvid, $driverName, $cust_number, $cust_name, $address, $date_time, $amountToCollect, $bookingId, $type, '', $logType, $sendContact);
		}
//		if (ContactPhone::getContactPhoneById($model->bcbVendor->vndContact->ctt_id) != '' && $vendor)
//		{
//			$vendorid	 = $model->bcb_vendor_id;
//			$type		 = SmsLog::Vendor;
//			$msgCom		 = new smsWrapper();
//			$logType	 = UserInfo::TYPE_SYSTEM;
//			$msgCom->pickupDetailsToDriver($vendorext, $vendorPhone, $vendorid, $vendorName, $cust_number, $cust_name, $address, $date_time, $amountToCollect, $bookingId, $type, '', $logType, $sendContact);
//		}
		if ($bmodel->bkgUserInfo->bkg_user_email != '' && $user && $bmodel->bkgPref->bkg_send_email == 1 && $sendEmail == 1)
		{
//			$emailCom1	 = new emailWrapper();
//			$logType	 = UserInfo::TYPE_SYSTEM;
//			$emailCom1->cabAssignemail($bmodel->bkg_id, $logType);
		}
	}

	public static function getB2CBookingDataForNextNHours()
	{
		$sql	 = "SELECT bkg_id FROM booking WHERE bkg_status = 5 AND (bkg_agent_id IS NULL OR bkg_agent_id = 1249 OR bkg_agent_id = '') AND bkg_pickup_date BETWEEN DATE_ADD(NOW(), INTERVAL 2 HOUR) AND DATE_ADD(NOW(), INTERVAL 14 HOUR)";
		$dataSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataSet;
	}

	public function deactivate()
	{
		$this->bcb_active	 = 0;
		$this->scenario		 = 'deactivate';
		return $this->save();
	}

	public function getLowestBookingStatusByTrip($bcb_id, $bcbpending = 0)
	{
		// $lowestStatus = 10;
		$sql	 = "SELECT MIN(bkg_status) bkg_status
                FROM booking
                JOIN booking_cab ON bkg_bcb_id = bcb_id AND bcb_pending_status = $bcbpending
                WHERE bkg_bcb_id = $bcb_id AND bkg_status IN (2,3,5,6,7) AND bcb_active = 1";
		$result	 = DBUtil::command($sql)->queryScalar();
		if ($result)
		{
			return $result;
		}
		return false;
	}

	public function getLowestBookingStatus($bcbpending = 0)
	{
		$bcb_id	 = $this->bcb_id;
		$status	 = $this->getLowestBookingStatusByTrip($bcb_id, $bcbpending);
		return $status;
	}

	public function getTripTotalBookingAmounts()
	{
		$bcb_id	 = $this->bcb_id;
		$sql	 = "SELECT  group_concat(IFNULL(bcb_vendor_amount,0)) bcb_vendor_amount ,
                            round(sum(bkg_total_amount)) bkg_total_amount,
                            round(sum(ifnull(bkg_advance_amount, 0))) bkg_advance_amount,
                            round(sum(bkg_due_amount)) bkg_due_amount,
                            round(sum(bkg_vendor_amount)) bkg_vendor_amount,
                            round(sum(bkg_refund_amount)) bkg_refund_amount,
                            round(sum(bkg_credits_used)) bkg_credits_used,
                            round(sum(bkg_corporate_credit)) bkg_corporate_credit,
                            round(sum(bkg_vendor_collected)) bkg_vendor_collected
                   FROM     booking
                   JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id AND bcb_active = 1
                   WHERE    bkg_bcb_id = $bcb_id AND bkg_status IN (2, 3, 5, 6, 7)
                   GROUP BY bkg_bcb_id";
		$dataSet = DBUtil::queryRow($sql);
		return $dataSet;
	}

	public function getVendorTDSAmount($vnd = 0)
	{
		$vnd = ($vnd == 0) ? $this->bcb_vendor_amount : $vnd;
		if ($this->bcb_id)
		{
			$vendorID		 = $this->bcb_vendor_id;
			$vendorAmount	 = $vnd;
			$tdsPercent		 = Vendors::model()->getTDSPercentByVendorId($vendorID);
			$tdsAmount		 = round(($vendorAmount * $tdsPercent) / 100);
		}
		return 0;
	}

	public function markTripPending()
	{
		if (count($this->bookings) > 1)
		{
			$this->bcb_pending_status	 = 1;
			$this->scenario				 = 'markpending';
			return $this->save();
		}
		return true;
	}

	public function updatePendingStatus($status = 0)
	{
		$bookings					 = $this->getActiveBookings();
		$this->bcb_pending_status	 = $status;
		$this->scenario				 = '$status';
		$userInfo					 = UserInfo::model();

		foreach ($bookings as $val)
		{
			if ($this->bcb_pending_status == 1)
			{
				$bkgId					 = $val['bkg_id'];
				$desc					 = "Placing booking in " . "( " . "smart Match - Pending" . " )";
				$eventid				 = BookingLog::SMART_MATCH;
				$params['blg_ref_id']	 = BookingLog::REF_MATCH_PENDING;
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, false, $params);
			}
			else
			{
				$bkgId					 = $val['bkg_id'];
				$desc					 = "Placing booking in " . "( " . "smart Match - Pending" . " )" . "exiting pending list";
				$eventid				 = BookingLog::SMART_MATCH;
				$params['blg_ref_id']	 = BookingLog::REF_MATCH_PENDING;
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, false, $params);
			}
		}

		return $this->save();
	}

	public function updateTripTypeStatus($tripStatus = 0)
	{
		$userInfo			 = UserInfo::model();
		$bookings			 = $this->getActiveBookings();
		$this->bcb_trip_type = $tripStatus;
		$this->scenario		 = 'tripStatus';
		foreach ($bookings as $val)
		{
			if ($this->bcb_trip_type == 0)
			{
				$bkgId										 = $val['bkg_id'];
				$desc										 = "Smart Match - Match Broken";
				$eventid									 = BookingLog::SMART_MATCH;
				$params['blg_ref_id']						 = BookingLog::REF_MATCH_BROKEN;
				// add followup for match broken (smart match booking); 
				$model										 = new ServiceCallQueue();
				$model->contactRequired						 = 0;
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DISPATCH;
				$model->scq_to_be_followed_up_with_value	 = 0;
				$model->scq_to_be_followed_up_with_type		 = 0;
				$model->scq_creation_comments				 = trim($desc);
				$model->scq_to_be_followed_up_with_entity_id = 0;
				$model->scq_to_be_followed_up_by_type		 = 1;
				$model->scq_to_be_followed_up_by_id			 = 4;
				$model->scq_to_be_followed_up_with_contact	 = 0;
				$model->scq_related_bkg_id					 = $bkgId;
				$platform									 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
				ServiceCallQueue::model()->create($model, UserInfo::TYPE_ADMIN, $platform);
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, false, $params);
			}
		}

		return $this->save();
	}

	public function getTripEndDatetime()
	{
		$bcb_id	 = $this->bcb_id;
		$sql	 = "SELECT bkg_id,bkg_pickup_date,bkg_trip_duration,  booking_route.brt_pickup_datetime,
               booking_route.brt_trip_duration,

               (  booking_route.brt_pickup_datetime
                + INTERVAL IFNULL(booking_route.brt_trip_duration,
                                  bkg.bkg_trip_duration) MINUTE)
                  AS estimatedRouteEndDateTime FROM `booking` bkg
                  JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
                   JOIN (SELECT max(brt_id) AS maxBrt, brt_bkg_id
                       FROM booking_route
                      WHERE brt_active = 1
                     GROUP BY brt_bkg_id) brtMax
                  ON brtMax.brt_bkg_id = bkg.bkg_id
               JOIN booking_route
                  ON     booking_route.brt_id = brtMax.maxBrt AND booking_route.brt_bkg_id = bkg.bkg_id
                     AND booking_route.brt_active = 1
                  WHERE bcb_active = 1 AND bkg_status IN (3,5,6,7) AND bcb_id = $bcb_id
                  ORDER BY estimatedRouteEndDateTime DESC";
		$dataSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataSet[0]['estimatedRouteEndDateTime'];
	}

	/**
	 * function use to notify 
	 * return type boolean
	 * @param type $assignMode
	 */
	public function vendorNotificationQue($assignMode = 0, $vndId)
	{
		$bookingCabModel = $this->bookings;
		$success		 = false;
		$msg			 = "You have won Trip ID " . $this->bcb_id;
		if ($this->bcb_cab_id == "")
		{
			$remainingWorkingHrs = BookingCab::model()->getRemainingWorkingHours($this->bcb_id);
			$nowTime			 = DBUtil::getCurrentTime();
			if ($remainingWorkingHrs['hours'] > 12)
			{
				$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 3 HOUR'));
			}
			else if ($remainingWorkingHrs['hours'] < 12 && $remainingWorkingHrs['hours'] > 8)
			{
				$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 2 HOUR'));
			}
			else if ($remainingWorkingHrs['hours'] < 8 && $remainingWorkingHrs['hours'] > 4)
			{
				$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 1 HOUR'));
			}
			else if ($remainingWorkingHrs['hours'] < 4)
			{
				$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 30 MINUTE'));
			}
			if ($assignMode == 1)
			{
				$msg = "You have won Trip ID " . $this->bcb_id . ". Assign Approved Car & Driver before " . $endTime;
			}
			else
			{
				$msg = "You have won Trip ID " . $this->bcb_id . ". Assign Approved Car & Driver before " . $endTime;
			}
		}

		$data						 = array();
		$vendorId					 = $vndId;
		$data['bcn_title']			 = "A new booking has been assigned";
		$data['bcn_message']		 = $msg;
		$data['bcn_vendor']			 = array($vendorId);
		$data ['bcn_user_type']		 = 2;
		$minutes					 = 2;
		$date						 = filter::getDBDateTime();
		$currentDate				 = strtotime($date);
		$futureDate					 = $currentDate + (60 * $minutes);
		$notificationTime			 = date("Y-m-d H:i:s", $futureDate);
		$data['bcn_schedule_for']	 = $notificationTime;
		$model						 = new BroadcastNotification();

		$model->bcn_form_input	 = CJSON::encode($data);
		$model->bcn_schedule_for = $notificationTime;
		$model->bcn_user_type	 = 1;
		$model->bcn_query		 = BroadcastNotification::buildVendorNotificationQuery($data);
		if ($model->save())
		{
			$success = true;
		}
	}

	public function notifyAssignVendor($assignMode = 0)
	{
		$bcount				 = count($this->bookings);
		$first_city			 = Cities::getName($this->bookings[0]->bkg_from_city_id);
		$pickup_date		 = date("d M Y h:i A", strtotime($this->bookings[0]->bkg_pickup_date)); //$this->getPickupDateTime("Y-m-d h:i A", $this->bookings[0]->bkg_pickup_date);
		$last_city			 = Cities::getName($this->bookings[$bcount - 1]->bkg_to_city_id);
		$bkg_id				 = $this->bookings[0]->bkg_booking_id;
		$msgDt				 = date("d M Y h:i A", strtotime($this->bcb_cab_assignmenttime));
		$remainingWorkingHrs = BookingCab::model()->getRemainingWorkingHours($this->bcb_id);
		$nowTime			 = DBUtil::getCurrentTime();
		if ($remainingWorkingHrs['hours'] > 12)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 3 HOUR'));
		}
		else if ($remainingWorkingHrs['hours'] < 12 && $remainingWorkingHrs['hours'] > 8)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 2 HOUR'));
		}
		else if ($remainingWorkingHrs['hours'] < 8 && $remainingWorkingHrs['hours'] > 4)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 1 HOUR'));
		}
		else if ($remainingWorkingHrs['hours'] < 4)
		{
			$endTime = date('d/M/Y-h:iA', strtotime($nowTime . ' + 30 MINUTE'));
		}
		if ($assignMode == 1)
		{
			$msg = "You have won Trip ID " . $this->bcb_id . ". Assign Approved Car & Driver before " . $endTime;
		}
		else
		{
			$msg = "You have won Trip ID " . $this->bcb_id . ". Assign Approved Car & Driver before " . $endTime;
		}

		##### notify for winner msg.
		//$msg = BookingVendorRequest::showBidRankAfterBidalocation($this->bcb_id,$this->bcb_vendor_id);
		$msg1 = BookingVendorRequest::showBidRankForWinner($this->bcb_id, $this->bcb_vendor_id);

		$payLoadData = ['tripId' => $this->bcb_id, 'EventCode' => Booking::CODE_VENDOR_ASSIGNED];

		$success = AppTokens::model()->notifyVendor($this->bcb_vendor_id, $payLoadData, $msg, "A new booking has been assigned");
		$success = AppTokens::model()->notifyVendor($this->bcb_vendor_id, $payLoadData, $msg1, "A new booking has been assigned");

		$this->sendSmsForVendorAssignment($endTime, $bkg_id);
		return $success;
	}

	public function sendSmsForVendorAssignment($endTime, $bkg_id)
	{
		$ext								 = '91';
		$contactId							 = ContactProfile::getByEntityId($this->bcb_vendor_id, UserInfo::TYPE_VENDOR);
		$number								 = ContactPhone::getContactPhoneById($contactId);
		$message							 = "You have won Trip ID " . $this->bcb_id . " Assign Approved Car & Driver before " . $endTime . " - Gozocabs";
		$params								 = [];
		$params['blg_vendor_assigned_id']	 = $this->bcb_vendor_id;
		$params['blg_driver_id']			 = $this->bcb_driver_id;
		$params['blg_vehicle_id']			 = $this->bcb_cab_id;
		$params['blg_booking_status']		 = $this->bookings[0]->bkg_status;

		// WhatsappLog
		$response = WhatsappLog::tripAssignedToVendor($this->bcb_id);
		if (!$response || $response['status'] == 3)
		{
			$slgId = smsWrapper::vendorAssignment($ext, $number, $bkg_id, $message, $this->bcb_vendor_id);
			if ($slgId)
			{
				$params['blg_ref_id'] = $slgId;
				BookingLog::model()->createLog($this->bcb_bkg_id1, "Sms sent to vendor for new assigned booking", UserInfo::getInstance(), BookingLog::SMS_SENT, null, $params);
			}
		}
	}

	public function getPickupDateTime($format = "Y-m-d H:i:s", $pickupdate = null, $agentId = null)
	{
		$pickupDate	 = $pickupdate == null ? $this->bookings[0]->bkg_pickup_date : $pickupdate;
		$bkgAgentId	 = $agentId == null ? $this->bookings[0]->bkg_agent_id : $agentId;

		// UBER Agent Check
		$uberAgentId = Yii::app()->params['uberAgentId'];
		if ($uberAgentId > 0 && $bkgAgentId == $uberAgentId)
		{
			$objDate	 = new DateTime($pickupDate, new DateTimeZone('Asia/Kolkata'));
			$objDate->modify('-30 minutes');
			$pickupDate	 = $objDate->format($format);
		}

		return $pickupDate;
	}

	public function getTripsVendor()
	{
		$returnSet = Yii::app()->cache->get('getTripsVendor');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                INNER JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
				INNER JOIN `vendors` v1 ON vendors.vnd_ref_code=v1.vnd_id
				INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = v1.vnd_id
                WHERE 1 AND booking.bkg_status IN (2,3,5)
                AND booking_cab.bcb_vendor_id IS NOT NULL
                AND vrs.vrs_vnd_overall_rating <= 3 LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getTripsVendor', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getTripGozoAmountByBkgID($bkgId)
	{
		$sql = "SELECT bcb_id, count(*) as cnt, SUM(booking_invoice.bkg_total_amount - booking_invoice.bkg_service_tax) as GMV, 
				(SUM(booking_invoice.bkg_total_amount - booking_invoice.bkg_service_tax - IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(booking_invoice.bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), IFNULL(agents.agt_commission, 0)),0)) - booking_cab.bcb_vendor_amount) as gozoAmount, 
				(SUM(booking_invoice.bkg_total_amount - booking_invoice.bkg_service_tax - booking_invoice.bkg_vendor_amount - IF(agents.agt_type=2,IF(agents.agt_commission_value = 1, ROUND(booking_invoice.bkg_base_amount * IFNULL(agents.agt_commission, 0) * 0.01), agents.agt_commission ),0))) as gozoUnmatchedAmount,
				MIN(booking.bkg_pickup_date) as createDate 
				FROM `booking_cab` 
				INNER JOIN booking 
				ON booking_cab.bcb_id=booking.bkg_bcb_id 
				INNER JOIN booking_invoice ON booking.bkg_id=booking_invoice.biv_bkg_id
				AND 
				bcb_id IN (SELECT bkg_bcb_id FROM booking WHERE bkg_id=$bkgId)
                    LEFT JOIN agents ON bkg_agent_id=agents.agt_id
                    GROUP BY bcb_id";
		$row = DBUtil::queryRow($sql);
		return $row;
	}

	public function getTripsDriver()
	{
		$returnSet = Yii::app()->cache->get('getTripsDriver');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1
                INNER JOIN `drivers` ON drivers.drv_id=booking_cab.bcb_driver_id
				INNER JOIN drivers d2 ON d2.drv_id =drivers.drv_ref_code
                WHERE 1 AND booking.bkg_status IN (2,3,5) AND booking_cab.bcb_active=1
                AND booking_cab.bcb_vendor_id IS NOT NULL
                AND booking_cab.bcb_driver_id IS NOT NULL
                AND d2.drv_overall_rating<=3 LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getTripsDriver', $returnSet, 600);
		}
		return $returnSet;
	}

	public static function getMissingCarDocs()
	{
		$sql = "SELECT COUNT(DISTINCT booking.bkg_id) AS cnt
				FROM `booking_cab`
				INNER JOIN `booking` ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND booking.bkg_status IN(2, 3, 5)
				INNER JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id AND vehicles.vhc_active = 1
				WHERE booking_cab.bcb_active = 1 AND vehicles.vhc_approved <> 1 LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public static function getMissingDriverDocs()
	{
		$returnSet = Yii::app()->cache->get('getMissingDriverDocs');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT booking.bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status IN (2,3,5) 
                INNER JOIN `drivers` ON drivers.drv_id=booking_cab.bcb_driver_id AND drivers.drv_active=1 
				INNER JOIN drivers d2 ON d2.drv_id =drivers.drv_ref_code
                WHERE bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH)) 
				AND	booking_cab.bcb_active=1 AND d2.drv_approved<>1 LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getMissingDriverDocs', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getCountVendorUnassigned5days()
	{
		$returnSet = Yii::app()->cache->get('getCountVendorUnassigned5days');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_status=2
                WHERE booking_cab.bcb_driver_id IS NULL
                AND booking.bkg_create_date < DATE_SUB(NOW(),INTERVAL 48 HOUR)
                LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountVendorUnassigned5days', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getCountVendorUnassigned24hrs()
	{
		$returnSet = Yii::app()->cache->get('getCountVendorUnassigned24hrs');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(DISTINCT bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_status=2
                WHERE booking_cab.bcb_driver_id IS NULL
                AND booking.bkg_pickup_date BETWEEN NOW() AND  DATE_ADD(NOW(),INTERVAL 24 HOUR) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountVendorUnassigned24hrs', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getCountVendorFloating24hrs()
	{
		$returnSet = Yii::app()->cache->get('getCountVendorFloating24hrs');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(booking.bkg_id) as count
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id
                AND booking.bkg_active=1
                AND booking.bkg_status IN (2)
                WHERE booking_cab.bcb_active=1
                AND booking_cab.bcb_id IN 
                (
                            SELECT DISTINCT booking_vendor_request.bvr_bcb_id
                            FROM `booking_vendor_request`
                            WHERE booking_vendor_request.bvr_accepted=0
                            AND booking_vendor_request.bvr_created_at > DATE_SUB(NOW(),INTERVAL 24 HOUR)
                            GROUP BY booking_vendor_request.bvr_booking_id
                        ) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountVendorFloating24hrs', $returnSet, 600);
		}
		return $returnSet;
	}

	public function getActiveBookingsCount()
	{
		$bookings	 = [];
		$bookings	 = $this->bookings;
		$counts		 = count($bookings);
		$activecount = 0;
		if ($counts > 0)
		{
			foreach ($bookings as $booking)
			{
				if (in_array($booking->bkg_status, [2, 3, 5, 6]))
				{
					$activecount++;
				}
			}
		}
		return $activecount;
	}

	public function getActiveBookings()
	{
		$bookings		 = [];
		$bookings		 = $this->bookings;
		$counts			 = count($bookings);
		$activebookings	 = [];
		if ($counts > 0)
		{
			foreach ($bookings as $booking)
			{
				if (in_array($booking->bkg_status, [2, 3, 5, 6]))
				{
					$activebookings[] = $booking;
				}
			}
		}
		return $activebookings;
	}

	public function sentDriverMessageAlert($logType = '')
	{


		$sql	 = "SELECT 
							bkg_id,
							bcb_vendor_id,
							bcb_driver_id,
							sms_last_date_sent
					FROM (
					SELECT 
							bkg.bkg_id,
							booking_cab.bcb_vendor_id,
							booking_cab.bcb_driver_id,
							max(sms_log.date_sent) AS sms_last_date_sent
					FROM booking AS bkg
					INNER JOIN booking_cab   ON bkg_bcb_id = bcb_id AND bkg.bkg_active = 1
					JOIN sms_log AS sms_log ON   sms_log.booking_id = bkg.bkg_booking_id  AND sms_log.slg_type IN (4, 5)
					WHERE     1
					AND bkg.bkg_status IN (5)
					AND bkg.bkg_pickup_date > NOW()
					AND (   DATE_ADD(NOW(), INTERVAL 4 HOUR) >= bkg.bkg_pickup_date
					OR (CalcWorkingHour(now(), bkg.bkg_pickup_date) <= 4)
					OR (    (   HOUR(bkg.bkg_pickup_date) >= 19
                          OR HOUR(bkg.bkg_pickup_date) <= 9)
					AND DATE_ADD(NOW(), INTERVAL 12 HOUR) >=
						bkg.bkg_pickup_date))
					GROUP BY bkg.bkg_id) bkgs
					WHERE DATE_SUB(NOW(), INTERVAL 6 HOUR) >= bkgs.sms_last_date_sent";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($rows) == 0)
		{
			return false;
		}
		$ctr = 0;
		foreach ($rows as $row)
		{
			echo "###########BookingId#############" . $row['bkg_id'] . "\n";
			$user	 = $vendor	 = $driver	 = false;
			if ($row['bcb_driver_id'] != null)
			{
				$driver = true;
			}
			if ($row['bcb_vendor_id'] != null)
			{
				$vendor = true;
			}
			BookingCab::model()->sendCabAssignMessage($user, $driver, $vendor, $row['bkg_id'], true, $logType);
		}
	}

	/* -- Customer Alert message -- */

	public function sentCustomerMessageAlert($logType = '')
	{
		$sql	 = "SELECT    
			    DISTINCT bkg.bkg_id
			    			    , if(bpr.bkg_send_sms = 0 OR (slg.date_sent) IS NOT NULL OR  bui.bkg_contact_no IS NULL OR  bui.bkg_contact_no = '', 0, 1) need_sms_send
			    , if(bpr.bkg_send_email = 0 OR elg.elg_status = 1 OR  bui.bkg_user_email IS NULL OR bui.bkg_user_email = '', 0, 1) need_email_send
			    FROM     booking bkg
			    JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
			    JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id
			    LEFT JOIN  (select booking_id,date_sent,id from sms_log slg where slg_type = 3 AND DATE_SUB(NOW(),interval 3 month)<=date_sent group by booking_id order by date_sent desc) slg  ON slg.booking_id = bkg.bkg_booking_id
			    LEFT JOIN  (select  elg_id,elg_status ,elg_booking_id,elg_status_date from email_log where elg_type = 3 AND DATE_SUB(NOW(),interval 3 month)<=elg_created  group by elg_booking_id order by elg_status_date desc) elg  ON elg.elg_booking_id = bkg.bkg_booking_id
			    WHERE    bkg.bkg_status = 5 AND bkg.bkg_pickup_date > NOW() 
			    AND (DATE_ADD(NOW(), INTERVAL 6 HOUR) >= bkg.bkg_pickup_date 
			    OR (CalcWorkingHour(now(), bkg.bkg_pickup_date) <= 4) 
			    OR (( HOUR(bkg.bkg_pickup_date) >=20 OR HOUR(bkg.bkg_pickup_date) <=8 ) AND DATE_ADD(NOW(), INTERVAL 12 HOUR)>= bkg.bkg_pickup_date)) 
			    AND (bpr.bkg_send_sms = 1 OR bpr.bkg_send_email = 1)
			    AND ((DATE_SUB(NOW(), INTERVAL 6 HOUR) >= slg.date_sent OR slg.id IS NULL) OR (DATE_SUB(NOW(), INTERVAL 6 HOUR) >=  elg.elg_status_date OR  elg.elg_status = 2 OR  elg.elg_status IS NULL OR elg.elg_id IS NULL))
			    ORDER BY bkg_pickup_date DESC";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		if (count($rows) == 0)
		{
			return false;
		}
		$vendor	 = $driver	 = false;
		foreach ($rows as $row)
		{
			$user	 = false;
			$sendArr = [];
			if ($row['need_sms_send'] == 1 || $row['need_email_send'] == 1)
			{
				$sendArr['send_sms']	 = $row['need_sms_send'];
				$sendArr['send_email']	 = $row['need_email_send'];
				$user					 = false;
			}
			BookingCab::model()->sendCabAssignMessage($user, $driver, $vendor, $row['bkg_id'], true, $logType, false, '', $sendArr);
		}
	}

	public function checkCabActiveTripTiming()
	{
		$pickupTime	 = $this->bookings[0]->bkg_pickup_date;
		$dropTime	 = date('Y-m-d H:i:s', strtotime($this->bookings[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
		foreach ($this->bookings as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$sql	 = "SELECT bkg.bkg_id, bcb.bcb_cab_id, vhc.vhc_number,bkg.bkg_status, bkg.bkg_pickup_date pickupDate,
                (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE) tripEndTime,
                bkg.bkg_trip_duration  FROM booking_cab bcb
              JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
			  JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id
              JOIN vehicles vhc ON vhc.vhc_id = bcb.bcb_cab_id
         WHERE bcb.bcb_cab_id IS NOT NULL AND bkg.bkg_status IN (5)
         AND bcb.bcb_cab_id = {$this->bcb_cab_id} AND bcb.bcb_id <> {$this->bcb_id} AND btk.bkg_ride_complete<>1
         GROUP BY bcb.bcb_id;";
		$overLap = 0;
		$rows	 = DBUtil::queryAll($sql);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if (($row['pickupDate'] <= $pickupTime && $row['tripEndTime'] >= $pickupTime) ||
						($row['pickupDate'] <= $dropTime && $row['tripEndTime'] >= $dropTime) ||
						($row['pickupDate'] >= $pickupTime && $row['tripEndTime'] <= $dropTime))
				{
					$overLap++;
				}
			}
		}
		return $overLap;
	}

	public function checkDriverActiveTripTiming()
	{
		$pickupTime	 = $this->bookings[0]->bkg_pickup_date;
		$dropTime	 = date('Y-m-d H:i:s', strtotime($this->bookings[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
		foreach ($this->bookings as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$sql	 = "SELECT bkg.bkg_id, bcb.bcb_driver_id, drv.drv_id ,bkg.bkg_status, bkg.bkg_pickup_date pickupDate,
			    (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE) tripEndTime,
			    bkg.bkg_trip_duration  FROM booking_cab bcb
				    JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
				    JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id
					JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id
			    WHERE bcb.bcb_driver_id IS NOT NULL AND bkg.bkg_status IN (5)
			    AND bcb.bcb_driver_id={$this->bcb_driver_id} AND bcb.bcb_id <> {$this->bcb_id} AND btk.bkg_ride_complete<>1
			    GROUP BY bcb.bcb_id;";
		$overLap = 0;
		$rows	 = DBUtil::queryAll($sql); //DBUtil::queryAll($sql);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if (($row['pickupDate'] <= $pickupTime && $row['tripEndTime'] >= $pickupTime) ||
						($row['pickupDate'] <= $dropTime && $row['tripEndTime'] >= $dropTime) ||
						($row['pickupDate'] >= $pickupTime && $row['tripEndTime'] <= $dropTime))
				{
					$overLap++;
				}
			}
		}
		return $overLap;
	}

	public function getVendorDetailById($bcb_id)
	{
		$sql = "SELECT booking_cab.bcb_vendor_id,MIN(booking.bkg_pickup_date) as pickup_datetime,
                booking.bkg_gozo_amount,MAX(booking_log.blg_created) as vendor_assign_date
                FROM `booking_cab`
                INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking_cab.bcb_active=1
                LEFT JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
                LEFT JOIN `booking_log` ON booking_log.blg_booking_id=booking.bkg_id AND booking_log.blg_active=1 AND booking_log.blg_event_id IN (7)
                WHERE booking_cab.bcb_id=$bcb_id GROUP BY booking_cab.bcb_id";
		return DBUtil::queryRow($sql);
	}

//	public function getLossTrips($pickdate1 = '', $pickdate2 = '')
//	{
//		$cond = "";
//		if ($pickdate1 != '' && $pickdate2 != '')
//		{
//			$cond = " DATE(pickupDate) BETWEEN '$pickdate1' AND '$pickdate2'";
//		}
//		else
//		{
//			$cond = " pickupDate>=DATE_SUB(NOW(), INTERVAL 36 HOUR)  AND pickupDate <= DATE_ADD(NOW(),INTERVAL 36 HOUR) ";
//		}
//
//		$sql			 = "SELECT
//                bcb_id,
//                GROUP_CONCAT(CONCAT(booking.bkg_booking_id,'-',booking.bkg_id)) booking_id,
//                booking.bkg_id bkg_id,
//                COUNT(*) AS cnt,
//                vendors.vnd_name vendor_name,
//                bcb_trip_type,
//                bkg_agent_id,
//                bcb_vendor_amount vendor_amount,
//                GROUP_CONCAT(CONCAT(c1.cty_name,'-',c2.cty_name),' ') routename,
//                SUM(booking.bkg_vendor_amount) quoted_vendor_amount,
//                IF(agents.agt_company IS NULL OR agents.agt_company='',CONCAT(agents.agt_fname,' ',agents.agt_lname),agents.agt_company) agent_name,
//                SUM(
//                    bkg_total_amount - bkg_service_tax
//                ) AS GMV,
//                (
//                    SUM(
//                        bkg_total_amount - bkg_service_tax - IF(
//                            agents.agt_type = 2,
//                            ROUND(
//                                bkg_base_amount * agents.agt_commission * 0.01
//                            ),
//                            0
//                        )
//                    ) - booking_cab.bcb_vendor_amount
//                ) AS gozoAmount,
//                (
//                    SUM(
//                        bkg_total_amount - bkg_service_tax - booking.bkg_vendor_amount - IF(
//                            agents.agt_type = 2,
//                            ROUND(
//                                bkg_base_amount * agents.agt_commission * 0.01
//                            ),
//                            0
//                        )
//                    )
//                ) AS gozoUnmatchedAmount,
//                MIN(booking.bkg_pickup_date) AS pickupDate
//            FROM
//                `booking_cab`
//            INNER JOIN booking ON booking_cab.bcb_id = booking.bkg_bcb_id AND bkg_status IN(2, 3, 5, 6, 7) AND bcb_active=1
//            JOIN cities c1 ON c1.cty_id = booking.bkg_from_city_id 
//			JOIN cities c2 ON c2.cty_id = booking.bkg_to_city_id
//			LEFT JOIN agents ON bkg_agent_id = agents.agt_id
//			LEFT JOIN vendors ON bcb_vendor_id = vendors.vnd_id 
//            GROUP BY
//                bcb_id
//            HAVING
//               $cond AND  gozoAmount < 0 ";
//		$countQuery		 = "SELECT COUNT(*) FROM ($sql) a";
//		$countCommand	 = DBUtil::command($countQuery);
//		$count			 = $countCommand->queryScalar();
//		$dataprovider	 = new CSqlDataProvider($sql, [
//			'totalItemCount' => $count,
//			'sort'			 => ['attributes'	 => ['pickupDate', 'gozoUnmatchedAmount', 'gozoAmount', 'quoted_vendor_amount', 'vendor_amount'],
//				'defaultOrder'	 => 'pickupDate DESC'
//			], 'pagination'	 => ['pageSize' => 50]
//		]);
//		return $dataprovider;
//	}

	public function getBkgIdByTripId($bcbId)
	{
		$param	 = ['bcbId' => $bcbId];
		$sql	 = "SELECT GROUP_CONCAT(booking.bkg_id SEPARATOR ',')  as bkg_ids
            FROM  `booking_cab`
            INNER JOIN `booking` ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking_cab.bcb_active=1 AND booking.bkg_active=1
			WHERE bcb_id = :bcbId
            GROUP BY booking_cab.bcb_id";

		return DBUtil::queryRow($sql, DBUtil::SDB(), $param);
	}

	public function pushPartnerTripStart($bkgid, $startTime)
	{
		$bmodel	 = Booking::model()->findByPk($bkgid);
		$success = false;
		if ($bmodel->bkg_agent_id == 450 || $bmodel->bkg_agent_id == 18190)
		{
			$typeAction	 = AgentApiTracking::TYPE_OTP_UPDATE;
			$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			if ($mmtResponse->status == 1)
			{
				$success = true;
			}
		}
		return $success;
	}

	public function pushPartnerCabDriver($bkgid)
	{
		$bmodel	 = Booking::model()->findByPk($bkgid);
		$success = false;
		if ($bmodel->bkg_agent_id == 450 || $bmodel->bkg_agent_id == 18190)
		{
			$typeAction	 = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
			$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			if ($mmtResponse->status == 1)
			{
				$success = true;
			}
		}
		return $success;
	}

	public function updateTripAmount($amount, $userInfo = null, $notification = true)
	{
		$trans = DBUtil::beginTransaction();
		try
		{
			if (!$this->validate())
			{
				throw new Exception(json_encode($this->getErrors()));
			}
			$oldValue						 = $this->bcb_vendor_amount;
			$this->bcb_vendor_amount		 = $amount;
			$this->bcb_last_vendor_amount	 = $oldValue;
			$this->save();
			$bookings						 = $this->bookings;
			$bkgIds							 = [];
			$bkgStatuses					 = [];
			$dates							 = [];

			foreach ($bookings as $booking)
			{
				$pickupdate		 = new DateTime($booking->bkg_pickup_date);
				$pickupdate->add(new DateInterval('PT' . $booking->bkg_trip_duration . 'M'));
				$dates[]		 = $pickupdate->format('Y-m-d H:i:s');
				$bkg_id[]		 = $booking->bkg_id;
				$bkgStatuses[]	 = $booking->bkg_status;
				$desc			 = "Trip Vendor Amount Reset - old value (" . $oldValue . ")  - New Value ( " . $amount . " ) For Trip ID - " . $this->bcb_id . " ";
				$eventid		 = BookingLog::VENDOR_AMOUNT_RESET;
				BookingLog::model()->createLog($booking->bkg_id, $desc, $userInfo, $eventid);
			}
//            }
			BookingTrail::updateProfitFlag($this->bcb_id);
			if ($this->bcbVendor && (in_array(6, $bkgStatuses) || in_array(7, $bkgStatuses)))
			{
				$pickupDate	 = max($dates);
				AccountTransactions::model()->purchaseTrip($pickupDate, $this->bcb_id, $this->bcb_vendor_id, $amount, $userInfo	 = null);
			}
			if (Config::get('hornok.operator.id') != $this->bcb_vendor_id && $notification == true)
			{
//				$this->notifyAssignVendor();
//				BookingCab::notifyTripAmountReset($this->bcb_id, $amount, 0);
			}
			DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			throw $e;
		}
	}

	public function setBookingCabInfo($bkgmodel)
	{
		$bkgid			 = $bkgmodel->bkg_id;
		$bkgbcbid		 = $bkgmodel->bkg_bcb_id;
		$bkgvendoramount = $bkgmodel->bkgInvoice->bkg_vendor_amount;
		if ($bkgbcbid > 0)
		{
			$bcbModel = Booking::model()->getBookingCabModel();
		}
		if ($bcbModel == '' || $bcbModel == null)
		{
			$bcbModel = new BookingCab();
		}
		$bcbModel->bcb_vendor_amount = $bkgvendoramount;
		$bcbModel->bcb_bkg_id1		 = $bkgid;
		if (!$bcbModel->save())
		{
			throw new Exception("Failed to Save Route Data.");
		}
		$bkgbcbid = $bcbModel->bcb_id;
		BookingRoute::model()->setBookingCabStartEndTime($bkgbcbid, $bkgid);
		BookingRoute::model()->linkAllBookingwithVendor($bkgid, $bcbModel->bcb_id);
		return $bkgbcbid;
	}

	public function getAgentsByBcbId($bcb_id)
	{
		$sql = "SELECT bkg_id,bkg_agent_id FROM booking WHERE bkg_bcb_id=$bcb_id";
		return DBUtil::queryAll($sql);
	}

	public function createBookingLog($desc, $type, $userInfo = null)
	{
		foreach ($this->bookings as $bModel)
		{
			BookingLog::model()->createLog($bModel->bkg_id, $desc, $userInfo, $type, null, false);
		}
	}

	public function updateCab()
	{
		$sql							 = "SELECT count(DISTINCT bvr_vendor_id) as cnt, GROUP_CONCAT(DISTINCT IF(bvr_bid_amount>0, bvr_bid_amount, '')) as bids, IFNULL(MIN(IF(bvr_bid_amount>0, bvr_bid_amount, null)),0) as MIN, IFNULL(MAX(IF(bvr_bid_amount>0, bvr_bid_amount, null)),0) as MAX, SUM(IF(bvr_bid_amount>0, 1, 0)) as totalBid  FROM booking_vendor_request WHERE bvr_bcb_id={$this->bcb_id} AND bvr_active=1 ";
		$row							 = DBUtil::queryRow($sql);
		$model							 = $this;
		$model->bcb_sent_vendor_count	 = $row['cnt'];
		$model->bcb_minBid				 = $row['MIN'];
		$model->bcb_maxBid				 = $row['MAX'];
		$bids							 = array_filter(explode(",", $row['bids']));
		$model->bcb_medianBid			 = Filter::calculateMedian($bids);
		$model->save();
	}

	public function updateVendorRequestCounter($tripID)
	{
		$bcbModel = BookingCab::model()->findByPk($tripID);
		if ($bcbModel->bcb_first_request_sent == NULL || $bcbModel->bcb_first_request_sent == "")
		{
			$bcbModel->bcb_first_request_sent = new CDbExpression('NOW()');
		}
		return $bcbModel->save();
	}

	public function getAsssignVendorList($bkgid)
	{
		$sql		 = "SELECT DISTINCT blg_vendor_assigned_id,v2.vnd_name FROM booking_log
					INNER JOIN vendors ON vendors.vnd_id = booking_log.blg_vendor_assigned_id
					INNER JOIN vendors v2 ON v2.vnd_id =  vendors.vnd_ref_code
					WHERE blg_event_id IN(7,600) AND blg_booking_id = '$bkgid' AND blg_vendor_assigned_id IS NOT NULL  GROUP BY vendors.vnd_id
					ORDER BY `blg_id` DESC
					";
		$recordset	 = DBUtil::queryAll($sql);
		return $recordset;
	}

	public function confirmSmartMatch($bcbId, $upBkgId, $downBkgId)
	{
		$trans = DBUtil::beginTransaction();
		try
		{
			$upBooking				 = Booking::model()->findByPk($upBkgId);
			$upBooking->bkg_bcb_id	 = $bcbId;
			if (!$upBooking->save())
			{
				throw new Exception("Failed to save", 1);
			}
			$success = BookingRoute::model()->linkAllBookingwithVendor($upBkgId, $bcbId);
			if (!$success)
			{
				throw new Exception("Failed to save", 1);
			}

			$downBooking			 = Booking::model()->findByPk($downBkgId);
			$downBooking->bkg_bcb_id = $bcbId;
			if (!$downBooking->save())
			{
				throw new Exception("Failed to save", 1);
			}
			$success = BookingRoute::model()->linkAllBookingwithVendor($downBkgId, $bcbId);
			if (!$success)
			{
				throw new Exception("Failed to save", 1);
			}

			BookingTrail::updateProfitFlag($bcbId);

			$desc					 = "Smart Match (Auto) booking " . $upBkgId . " with " . $downBkgId;
			$eventid				 = BookingLog::SMART_MATCH;
			$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
			$userInfo				 = UserInfo::getInstance();
			BookingLog::model()->createLog($upBkgId, $desc, $userInfo, $eventid, false, $params, '', $bcbId);
			BookingLog::model()->createLog($downBkgId, $desc, $userInfo, $eventid, false, $params, '', $bcbId);
			DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			throw new Exception($e->getMessage(), 1);
		}
	}

	public function penaltyToVendor($bcbId, $vendorId, $bkgid, $vendor_amt, $total_booking_amount, $dependencyfactor = "")
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		try
		{
			$transaction = DBUtil::beginTransaction();
			$bkgids		 = explode(",", $bkgid);
			$success	 = false;
			if ($bcbId > 0 && $bkgid != '')
			{
				$penaltyRow		 = BookingSub::model()->getHourDetailsForPenaltyByTrip($bcbId);
				$unassignedTime	 = new CDbExpression('NOW()');
				$assignedTime	 = $penaltyRow['vendor_assign_date'];
				$pickupTime		 = $penaltyRow['pickup_datetime'];
				$vendorAmount	 = $vendor_amt;
				$acceptType		 = $penaltyRow['bcb_assign_mode'];
				$dependencyScore = 0;
				$vrsModel		 = VendorStats::model()->getbyVendorId($vendorId);
				if ($bkgid > 0)
				{
					$model			 = Booking::model()->findByPk($bkgid);
					$booking_pref	 = $model->bkgPref;
					$tfrStatus		 = $booking_pref->bkg_is_fbg_type;
					if ($tfrStatus == 1)
					{
						$acceptType = 3;
					}
				}

				if ($vrsModel)
				{
					$dependencyScore = ($vrsModel->vrs_dependency == null ? 0 : $vrsModel->vrs_dependency);
				}
				$amount = Self::GetUnassignPenaltyCharge($unassignedTime, $assignedTime, $pickupTime, $vendorAmount, $acceptType, $dependencyScore);

				$strRemarks = '';
				Logger::trace("penaltyRow: " . "amount: " . $amount);

				if ($penaltyRow > 0)
				{
					Logger::trace("Penalty Row in: bcbid: " . $bcbId . "vendorId: " . $vendorId . "bkgid: " . $bkgid);
					$penaltyType = PenaltyRules::PTYPE_VENDOR_UNASSIGNED;
					//$arrRules	 = PenaltyRules::getRuleByPenaltyType($penaltyType);
					//$amount		 = PenaltyRules::calculatePenaltyCharge($penaltyType, $arrRules, '', '', '', $penaltyRow, $total_booking_amount);

					$remarks = 'Penalized (₹' . $amount . ') for cancellation ' . $strRemarks . ' of Trip ID: ' . $bcbId . ' / Booking ID: ' . $bkgid . ', Trip Vendor Amount: ₹' . $vendor_amt . ',Vendor dependability score:' . $dependencyScore;

					if ($amount > 0)
					{
						$result = AccountTransactions::checkAppliedPenaltyByType($bcbId, $penaltyType);
						if ($result)
						{
							Logger::trace("amount in: bcbid: " . $bcbId . "vendorId: " . $vendorId . "bkgid: " . $bkgid);
							$addVendorPenalty = AccountTransactions::model()->addVendorPenaltyByTrip($bcbId, $vendorId, $amount, $remarks, $penaltyType);
						}
					}
					if ($addVendorPenalty)
					{
						Logger::trace("addVendorPenalty in: bcbid: " . $bcbId . "vendorId: " . $vendorId . "bkgid: " . $bkgid);
						foreach ($bkgids as $bk_id)
						{
							BookingTrail::model()->addVendorUnassignPenalty($bk_id, $amount);
							$desc = 'Penalized (₹' . $amount . ') for cancellation  ' . $strRemarks . ' of Trip ID: ' . $bcbId . '| Trip Vendor amount: ₹' . $vendor_amt . ', Vendor dependability score: ' . $dependencyScore;
							BookingLog::model()->createLog($bk_id, $desc, UserInfo::model(), BookingLog::VENDOR_PANALIZED, false);
						}
						$success = true;
					}
					else
					{
						$success = true;
					}
					Logger::trace("addVendorPenalty out: bcbid: " . $bcbId . "vendorId: " . $vendorId . "bkgid: " . $bkgid . "success" . $success);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::Error('Error in penalty To Vendor:' . $ex->getMessage());
			$success = false;
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $success;
	}

	public function lockVendorPayment($description = '')
	{
		$this->bcb_lock_vendor_payment	 = 1;
		$this->save();
		$desc							 = "Locked Vendor Payment";
		if (trim($description) != '')
		{
			$desc = $description;
		}
		$eventid = BookingLog::LOCKED_PAYMENT;
		$this->createBookingLog($desc, $eventid);
	}

	public function releaseVendorPayment($description = '')
	{
		$this->bcb_lock_vendor_payment	 = 0;
		$this->save();
		$desc							 = "Vendor Payment Released";
		if (trim($description) != '')
		{
			$desc = $description;
		}
		$eventid = BookingLog::RELEASED_PAYMENT;
		$this->createBookingLog($desc, $eventid);
	}

	public static function updateVendorPayment($entityType, $entityId)
	{
		if ($entityType == 1)
		{
			$where = " AND bcb_cab_id={$entityId}";
		}
		else
		{
			$where = " AND bcb_driver_id={$entityId}";
		}

		$sql = "    SELECT DISTINCT bcb_id FROM booking_cab 
					INNER JOIN booking ON bcb_id=bkg_bcb_id
					INNER JOIN drivers ON bcb_driver_id = drv_id AND drv_active = 1
					INNER JOIN vehicles ON bcb_cab_id = vhc_id AND vhc_active = 1
					WHERE bkg_status IN (5,6,7,9) AND bcb_lock_vendor_payment=1 $where
			    ";

		$rows = DBUtil::queryAll($sql);
		foreach ($rows as $row)
		{
			$model = BookingCab::model()->findByPk($row['bcb_id']);

			$approved = $model->isCabDriverApproved();
			if ($approved)
			{
				$model->releaseVendorPayment();
			}
			else
			{
				$model->lockVendorPayment();
			}
		}
	}

	public function isCabDriverApproved()
	{
		$result = false;
		if ($this->bcbDriver != '' && $this->bcbCab != '')
		{
			$result = ($this->bcbDriver->isApproved() && $this->bcbCab->isApproved());
		}
		return $result;
	}

	public function getRevenueBreakup($bcbId)
	{
		$sql = "SELECT bcb_id, bcb_vendor_amount, SUM(bkg_total_amount) as totalAmount, max(bkg_manual_assignment) as manualFlag,
					max(bkg_critical_assignment) as criticalFlag, MIN(bkg_pickup_date) AS pickupDate,
					max(bkg_critical_score) as criticalScore, bcb_max_allowable_vendor_amount,
					SUM(bkg_vendor_amount) as totalQuotedVendorAmount, SUM(bkg_net_advance_amount) as totalAdvance,
					SUM(ROUND(IF(agents.agt_type=2,IFNULL(agt_commission,0),0) * IF(agt_commission_value=2,1,(bkg_base_amount - bkg_discount_amount)*.01))) as totalCommission,
					(SUM(bkg_total_amount - IFNULL(bkg_advance_amount,0)+ IFNULL(bkg_refund_amount,0) - IFNULL(bkg_credits_used,0))) as customerDue,
					(SUM(bkg_total_amount-bkg_service_tax) - bcb_vendor_amount - SUM(ROUND(IF(agents.agt_type=2,IFNULL(agt_commission,0),0) * IF(agt_commission_value=2,1,(bkg_base_amount - bkg_discount_amount)*.01)))) as gozoAmount
				FROM booking_cab 
				INNER JOIN booking ON bcb_id=bkg_bcb_id 
				INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
				INNER JOIN booking_pref ON bpr_bkg_id=bkg_id
				LEFT JOIN agents ON agt_id=bkg_agent_id
				WHERE bcb_id=$bcbId
				GROUP BY bcb_id";

		$row = DBUtil::queryRow($sql);
		return $row;
	}

	public function getCabAssignmentTime()
	{
		$pickup				 = $this->bcb_start_time;
		$now				 = Filter::getDBDateTime();
		$pickupPrevious5	 = date("Y-m-d H:i:s", strtotime(" -5 hour", strtotime($pickup)));
		$nextHr				 = date("Y-m-d H:i:s", strtotime(" +1 hour", strtotime($now)));
		$set1				 = MAX($pickupPrevious5, $nextHr);
		$pickupPrevious1	 = date("Y-m-d H:i:s", strtotime(" -1 hour", strtotime($pickup)));
		$previous10Min		 = date("Y-m-d H:i:s", strtotime(" +15 minute", strtotime($now)));
		$set2				 = MAX($pickupPrevious1, $previous10Min);
		return $cabAssignmentTime	 = MIN($set1, $set2);
	}

	public static function getGozoAmount($bcbid)
	{
		$sql = "SELECT bcb_id, (SUM(bkg_total_amount-bkg_service_tax) - bcb_vendor_amount - SUM(ROUND(IF(agents.agt_type=2,IFNULL(agt_commission,0),0) * IF(agt_commission_value=2,1,(bkg_base_amount - bkg_discount_amount)*.01)))) as gozoAmount
				FROM booking_cab 
				INNER JOIN booking ON bcb_id=bkg_bcb_id AND bkg_status=2 
				INNER JOIN booking_invoice ON biv_bkg_id=bkg_id				 
				LEFT JOIN agents ON agt_id=bkg_agent_id
				WHERE bcb_id=$bcbid
				GROUP BY bcb_id
			";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		return $row;
	}

	public static function getServiceTierId($tripId)
	{
		$param			 = ["tripId" => $tripId];
		$sqlTierId		 = "SELECT scv_scc_id 
						FROM booking_cab 
						INNER JOIN booking ON bcb_id=bkg_bcb_id
						INNER JOIN svc_class_vhc_cat svcVhc ON svcVhc.scv_id = bkg_vehicle_type_id  
						INNER JOIN service_class ON scc_id=scv_scc_id
						WHERE bcb_id=:tripId ORDER BY scc_rank DESC";
		$serviceTierId	 = DBUtil::queryScalar($sqlTierId, DBUtil::SDB(), $param);
		return $serviceTierId;
	}

	public static function getVendorAmountWithMargin($tripId, $margin)
	{
		$param	 = ["tripId" => $tripId, "margin" => $margin];
		$sql	 = "SELECT ROUND(SUM(bkg_total_amount-bkg_service_tax-bkg_partner_commission) - SUM(bkg_net_base_amount)*:margin*0.01) as vendorAmount 
				FROM booking 
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				WHERE bcb_id=:tripId
				GROUP BY bcb_id
			";

		$vendorAmount = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		return $vendorAmount;
	}

	public function updateMaxAllowableVendorAmount($bcbid, $round)
	{
		if ($round !== '')
		{
			$param					 = ["cabId" => $bcbid];
			$roundNo				 = 'round' . $round;
			$serviceTierId			 = self::getServiceTierId($bcbid);
			$marginTolerance		 = Config::getMarginToleranceFactor($serviceTierId);
			$lossFactor				 = $marginTolerance[$roundNo];
			$baseFare				 = " bkg_net_base_amount";
			$baseMarginTolerance	 = " (($baseFare) * $lossFactor *0.01) ";
			$partnerCommission		 = " bkg_partner_commission";
			$unassignPenalty		 = " 0"; //" (btr_vendor_unassign_penalty) * 0.25";
			$doubleBackedAmount		 = "  IF(bkg_critical_score>0.98,btr_dbo_amount,0)";
			$totalAmountTolerance	 = " SUM(bkg_total_amount - bkg_service_tax - $partnerCommission  - $baseMarginTolerance)";
			$MaxVALogic				 = '0';
			if ($round == 1)
			{
				$MaxVALogic = $totalAmountTolerance;
			}
			if ($round == 2)
			{
				$MaxVALogic = $totalAmountTolerance;
			}
			if ($round == 3)
			{
				$mmtBaseMarginTolerance	 = $baseMarginTolerance; // " -1 * LEAST(bkg_total_amount*0.25, 1000) ";
				$MaxVALogic				 = " (SUM(bkg_total_amount - bkg_service_tax - $partnerCommission  - 								
								 IF(bkg_agent_id IN (450, 18190), $mmtBaseMarginTolerance, LEAST(-1 * $doubleBackedAmount * 0.5, $baseMarginTolerance))) +
								$unassignPenalty )";
			}

			$sql = "SELECT bcb_id,  round(GREATEST($MaxVALogic, IFNULL(bcb_max_allowable_vendor_amount, 0), bcb_vendor_amount)) maxAllowableVAmount 
						FROM booking_cab 
						INNER JOIN booking ON bcb_id=bkg_bcb_id AND bkg_status=2 
						INNER JOIN booking_invoice ON biv_bkg_id=bkg_id		
						INNER JOIN booking_trail btr ON  btr.btr_bkg_id = booking.bkg_id 
						INNER JOIN booking_pref  ON bpr_bkg_id = bkg_id
						LEFT JOIN agents ON agt_id=bkg_agent_id
						WHERE bcb_id=:cabId
						GROUP BY bcb_id  ";

			$row				 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
			$maxAllowableVAmount = round($row['maxAllowableVAmount']);
			BookingCab::model()->setMaxAllowableVendorAmount($bcbid, $maxAllowableVAmount);
			return $row;
		}
		return false;
	}

	public function setMaxAllowableVendorAmount($bcbid, $maxAllowableVAmount)
	{
		if ($bcbid > 0)
		{
			$sql	 = " UPDATE booking_cab 
			SET bcb_max_allowable_vendor_amount = $maxAllowableVAmount
				WHERE bcb_id= $bcbid ";
			$result	 = DBUtil::command($sql)->execute();
			return $result;
		}
		return false;
	}

	public static function resetBidStartTime($bcbId)
	{
		$sql = "UPDATE booking_cab 
			JOIN booking ON bkg_bcb_id = bcb_id
			JOIN booking_trail ON btr_bkg_id = bkg_id
			SET bcb_bid_start_time=NOW(), bcb_is_bid_started=1
				WHERE bcb_id={$bcbId} AND btr_is_bid_started=1";
		$res = DBUtil::command($sql)->execute();
		return $res;
	}

	public static function increaseVABy($bkgNetBaseAmt, $gozoAmt, $criticalScore, $trackParam = null, $pickupDate = null)
	{
		$step		 = null;
		$lastStep	 = 0;
		$incVABy	 = 0;
		if ($trackParam == null)
		{
			$trackParam = [];
		}
		$log = [];
		if (isset($trackParam["tripCriticalStep"]))
		{
			$lastStep = $trackParam["tripCriticalStep"];
		}
		$safeMarginExtra = 1;
		if ($pickupDate != null && strtotime($pickupDate) >= strtotime('2022-12-27 00:00:00') && strtotime($pickupDate) <= strtotime('2022-12-27 23:59:59'))
		{
			$safeMarginExtra = 0.8;
		}

		$currMarginPercent = floor((($gozoAmt / $bkgNetBaseAmt) * 100));

		$wholeCS = ($criticalScore * 100);

		#echo "<br><br>increaseVABy - lastStep == ".$lastStep;
		#echo "<br>increaseVABy - currMarginPercent == ".$currMarginPercent;


		$arrTripVAIncreaseSetting = self::getTripVAIncreaseSetting($wholeCS);

		if (is_array($arrTripVAIncreaseSetting) && $arrTripVAIncreaseSetting != null)
		{
			$step						 = $arrTripVAIncreaseSetting['step'];
			$currMargin					 = $arrTripVAIncreaseSetting['currMargin'];
			$lossMargin					 = $arrTripVAIncreaseSetting['lossMargin'];
			$safeMargin					 = null;
			$incVABy					 = 0;
			$log["step"]				 = $step;
			$log["baseAmount"]			 = $bkgNetBaseAmt;
			$log["gozoAmt"]				 = $gozoAmt;
			$log["currMarginPercent"]	 = $currMarginPercent;
			if (isset($arrTripVAIncreaseSetting['safeMargin']))
			{
				$safeMargin = $arrTripVAIncreaseSetting['safeMargin'];
				if ($safeMargin >= 5)
				{
					$safeMargin = $safeMargin * $safeMarginExtra;
				}

				$safeGozoAmount			 = round($bkgNetBaseAmt * $safeMargin * 0.01);
				$incVABy				 = max($gozoAmt - $safeGozoAmount, 0);
				$log["safeGozoAmount"]	 = $safeGozoAmount;
				$log["safeIncVABy"]		 = $incVABy;
			}

			if ($step != null && $step > $lastStep && $currMarginPercent >= $currMargin)
			{

				$inc					 = round((($gozoAmt * $lossMargin) / 100));
				$log["marginIncVABy"]	 = $inc;

				$incVABy = max($inc, $incVABy);
			}

			if ($incVABy <> 0)
			{
				Logger::writeToConsole("increaseVABy: " . json_encode($log));
			}
		}

		return ['incVABy' => $incVABy, 'step' => $step];
	}

	public static function getTripVAIncreaseSetting($criticalScore)
	{
		$value		 = null;
		$arr		 = array();
		$arr[65]	 = ['step' => 1, 'cs' => 0.65, 'currMargin' => 13, 'lossMargin' => 10];
		$arr[72]	 = ['step' => 2, 'cs' => 0.72, 'currMargin' => 11, 'lossMargin' => 15, 'safeMargin' => 14];
		$arr[80]	 = ['step' => 3, 'cs' => 0.80, 'currMargin' => 9, 'lossMargin' => 20, 'safeMargin' => 9];
		$arr[88]	 = ['step' => 4, 'cs' => 0.88, 'currMargin' => 4, 'lossMargin' => 30, 'safeMargin' => 5];
		$arr[92]	 = ['step' => 5, 'cs' => 0.92, 'currMargin' => 2, 'lossMargin' => 60, 'safeMargin' => 1];
		$arr[96]	 = ['step' => 6, 'cs' => 0.96, 'currMargin' => 0, 'lossMargin' => 100, 'safeMargin' => -3];
		$arr[100]	 = ['step' => 7, 'cs' => 1, 'currMargin' => 0, 'lossMargin' => 100, 'safeMargin' => -7];

		if ($criticalScore > 0)
		{
			foreach ($arr as $key => $arrValue)
			{
				if ($criticalScore >= $key)
				{
					$value = $arrValue;
				}
			}
		}

		return $value;
	}

	public static function getAdditionalParams($data, $key = '')
	{
		$value	 = null;
		$data	 = trim($data);
		if ($data != '')
		{
			$arrData = CJSON::decode($data, true);

			if ($key != '')
			{
				if (isset($arrData[$key]))
				{
					$value = $arrData[$key];
				}
			}
			else
			{
				$value = $arrData;
			}
		}

		return $value;
	}

	public static function setAdditionalParams($data, $key, $value)
	{
		$arrData = [];
		$data	 = trim($data);
		if ($data != '' && $data != null)
		{
			$arrData = CJSON::decode($data, true);
		}

		if ($key != '')
		{
			$arrData[$key] = $value;
		}

		$arrData = CJSON::encode($arrData);

		return $arrData;
	}

	public static function updateCriticalTripAmountNew($bcb = '')
	{
		$where = '';
		if ($bcb > 0)
		{
			$where = " AND bcb_id = {$bcb}";
		}

		$sql = "SELECT bcb_id, bcb_vendor_amount, bcb_additional_params, GROUP_CONCAT(bkg_id) as bkgIds, bpr.bkg_is_fbg_type, bcb_max_allowable_vendor_amount,
				MAX(bkg_critical_score) AS criticalScore, SUM(bkg_net_base_amount) bkgNetBaseAmt, MIN(bkg_pickup_date) AS pickupDate,
				IF(MIN(bkg_pickup_date) BETWEEN '2023-04-07 00:00:00' AND '2023-04-08 00:00:00' AND bcb_additional_params IS NULL AND MIN(CalcWorkingMinutes(bkg_create_date, NOW())) > 120, 1, 0) AS overrideCS
				FROM booking 
				JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg_id 
				JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id 
				JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id 
				JOIN booking_cab bcb ON bcb.bcb_id = bkg_bcb_id 
				LEFT JOIN agents ON agt_id=bkg_agent_id 
				WHERE  bkg_status = 2 $where AND bkg_reconfirm_flag = 1 
					AND  bkg_pickup_date > NOW() AND bkg_critical_score IS NOT NULL 
					AND btr_is_bid_started = 1 AND bpr.bkg_is_fbg_type >=0 AND btr_stop_increasing_vendor_amount = 0 AND bkg_critical_score > 0.5 
				GROUP by bcb_id 
				HAVING TIMESTAMPDIFF(MINUTE, MAX(bkg_create_date),NOW())>60";

		$bookingRes = DBUtil::query($sql);

		foreach ($bookingRes as $booking)
		{
			$log				 = [];
			$bkgIds				 = $booking['bkgIds'];
			$bcbId				 = $booking['bcb_id'];
			$bcbVendorAmount	 = $booking['bcb_vendor_amount'];
			$additionalParams	 = $booking['bcb_additional_params'];
			$criticalScore		 = $booking['criticalScore'];
			$bkgNetBaseAmt		 = $booking['bkgNetBaseAmt'];
			$isOverrideCS		 = $booking['overrideCS'];
			$pickupDate			 = $booking['pickupDate'];
			$isFBG				 = $booking["bkg_is_fbg_type"];
			$maxVendorAmount	 = $booking["bcb_max_allowable_vendor_amount"];

			if ($isOverrideCS == 1)
			{
				if ($criticalScore > 0.5 && $criticalScore < 0.65)
				{
					$criticalScore = 0.65;
				}
				elseif ($criticalScore > 0.66 && $criticalScore < 0.72)
				{
					$criticalScore = 0.72;
				}
				elseif ($criticalScore > 0.73 && $criticalScore < 0.8)
				{
					$criticalScore = 0.8;
				}
			}

			if ($isFBG == 1)
			{
				$criticalScore = min($criticalScore, 0.88);
			}

			// Updating GozoAmount
			BookingInvoice::updateGozoAmount($bcbId);
			$log["tripId"] = $bcbId;

			// Get Trip Critical Step
			$tripCriticalStep	 = self::getAdditionalParams($additionalParams);
			$log["step"]		 = $tripCriticalStep;

			// Trip Gozo Amount
			$tripGozoAmount			 = BookingInvoice::getGozoAmountByTripId($bcbId);
			$log["tripGozoAmount"]	 = $tripGozoAmount;
			// Increase VendorAmount By
			$arrIncreaseVABy		 = self::increaseVABy($bkgNetBaseAmt, $tripGozoAmount, $criticalScore, $tripCriticalStep, $pickupDate);
			$increaseVABy			 = $arrIncreaseVABy['incVABy'];
			$step					 = $arrIncreaseVABy['step'];
			$log["increaseVABy"]	 = $increaseVABy;
			$log["step"]			 = $step;
			$newTripVendorAmount	 = $bcbVendorAmount + $increaseVABy;

			if ($increaseVABy > 0 && $newTripVendorAmount <= $maxVendorAmount)
			{
				Logger::writeToConsole("BkgIds: $bkgIds");
				// Update New Trip Amount
				$cabModel					 = BookingCab::model()->findByPk($bcbId);
				$newTripVendorAmount		 = $bcbVendorAmount + $increaseVABy;
				$cabModel->updateTripAmount($newTripVendorAmount, UserInfo::getInstance());
				$log["newTripVendorAmount"]	 = $newTripVendorAmount;
				// Update Additional Params
				$additionalParams			 = self::setAdditionalParams($additionalParams, 'tripCriticalStep', $step);

				$cabModel->refresh();
				$cabModel->bcb_additional_params = $additionalParams;
				$cabModel->save();

				// Updating GozoAmount
				BookingInvoice::updateGozoAmount($bcbId);

				// Auto Vendor Assignments
				BookingVendorRequest::autoVendorAssignments($bcbId);

				// Notify Vendor
				BookingCab::processPendingBulkNotifications($bcbId, false, false);
			}

			Logger::writeToConsole(json_encode($log));
		}
	}

	/* if(bkg_is_flg_type=1(trans booking): "vendor amount or max_allowable_vendor amount will not updated)" */

	// This function below is used to increase the TripVA as we progress through the AutoAssign steps/stages

	public function updateCriticalTripAmount($bcb = '')
	{
		$where = '';
		if ($bcb > 0)
		{
			$where = " AND bcb_id = {$bcb}";
		}
		/* we've subtracted the credits used  in the sql below for ZeroMarginVA */
		$sql = "
				SELECT bcb_id,  
					MAX(btr_is_bid_started)  isAutoAssign, MAX(bkg_manual_assignment)  isManualAssign,
					MAX(bkg_critical_assignment) isCriticalAssign, bcb_bid_start_time,
					MAX(bkg_critical_score) AS criticalScore,
					MAX(btr_bid_start_time) btr_bid_start_time, MIN(bkg_pickup_date) bkg_pickup_date,
					SUM(bkg_vendor_amount) bkg_vendor_amount,
					SUM(bkg_total_amount - bkg_service_tax - bkg_credits_used - IF(agents.agt_type=2,IF(biv.bkg_cp_comm_type = 1, ROUND((bkg_base_amount-bkg_discount_amount) * IFNULL(biv.bkg_cp_comm_value, 0) * 0.01), IFNULL(biv.bkg_cp_comm_value, 0)),0)) as ZeroMarginVA, 
					bcb_vendor_amount, bcb_max_allowable_vendor_amount, NOW() curval,
					CalcCriticalityDate(0.88, MAX(bkg_create_date), MIN(bkg_pickup_date)) cDate88,
					MIN(btr_manual_assign_date) btr_manual_assign_date,
					MIN(btr_critical_assign_date) btr_critical_assign_date,
					bpr.bkg_is_fbg_type,
					CASE
						WHEN MAX(bkg_critical_assignment)=1 THEN TIMESTAMPDIFF(MINUTE,GREATEST(MIN(btr_critical_assign_date),IFNULL(bcb_bid_start_time,MAX(btr_bid_start_time))), MIN(bkg_pickup_date))
						WHEN MAX(bkg_manual_assignment)=0 THEN TIMESTAMPDIFF(MINUTE,IFNULL(bcb_bid_start_time,MAX(btr_bid_start_time)),CalcCriticalityDate(0.84, MAX(bkg_create_date), MIN(bkg_pickup_date)))
						WHEN MAX(bkg_manual_assignment)=1 THEN TIMESTAMPDIFF(MINUTE,GREATEST(MIN(btr_manual_assign_date),IFNULL(bcb_bid_start_time,MAX(btr_bid_start_time))),CalcCriticalityDate(0.88, MAX(bkg_create_date), MIN(bkg_pickup_date)))
					END as gap,
					CASE
						WHEN MAX(bkg_critical_assignment)=1 THEN TIMESTAMPDIFF(MINUTE,GREATEST(MIN(btr_critical_assign_date),IFNULL(bcb_bid_start_time,MAX(btr_bid_start_time))), NOW())
						WHEN MAX(bkg_manual_assignment)=0 THEN TIMESTAMPDIFF(MINUTE,IFNULL(bcb_bid_start_time,MAX(btr_bid_start_time)), NOW())
						WHEN MAX(bkg_manual_assignment)=1 THEN TIMESTAMPDIFF(MINUTE,GREATEST(MIN(btr_manual_assign_date),IFNULL(bcb_bid_start_time,MAX(btr_bid_start_time))), NOW())
					END as gapPast
				FROM   booking
				JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg_id
				JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id
				JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id
				JOIN booking_cab bcb ON bcb.bcb_id = bkg_bcb_id
				LEFT JOIN agents ON agt_id=bkg_agent_id
				WHERE  bkg_status = 2 $where AND bkg_reconfirm_flag = 1
					AND  bkg_pickup_date > NOW() AND bkg_critical_score IS NOT NULL
					AND btr_is_bid_started = 1 AND bpr.bkg_is_fbg_type =0 AND btr_stop_increasing_vendor_amount = 0
				GROUP by bcb_id HAVING TIMESTAMPDIFF(MINUTE, MAX(bkg_create_date),NOW())>60 ";

		$bookingRes		 = DBUtil::queryAll($sql);
		$manualFactor	 = Config::get("booking.score.manual");
		$criticalFactor	 = Config::get("booking.score.critical");
		foreach ($bookingRes as $booking)
		{
			$isManual		 = $booking['isManualAssign'];
			$isCritical		 = $booking['isCriticalAssign'];
			$criticalScore	 = $booking['criticalScore'];

			$round	 = ($isCritical == 1) ? 3 : (($isManual == 1) ? 2 : (($criticalScore > 0.6) ? 1 : 0));
			$tripId	 = $booking['bcb_id'];

			BookingCab::updateCriticalTripAmountNew($tripId);

//			$resVA			 = $this->updateMaxAllowableVendorAmount($tripId, $round);
//			if ($newTripVendorAmount > $booking['bcb_vendor_amount'])
//			{
//				//			$cabModel->updateTripAmount($newTripVendorAmount, UserInfo::getInstance());
//				// DSA: VendorAmount is not being increased here anymore, maybe we'll re-enable this later  but leave it off for now.
//				Logger::info($tripId . ':: Old trip amount:' . $booking['bcb_vendor_amount'] . ' :: New amount' . $newTripVendorAmount . "\n");
//				BookingVendorRequest::autoVendorAssignments($tripId);
//			}
		}
	}

	public function createTrip($arrBkgIds, $vendorAmount)
	{
		try
		{
			if (is_array($arrBkgIds) && count($arrBkgIds) > 0)
			{
				$sql	 = "SELECT GROUP_CONCAT(bkg.bkg_id) as multiple_bkg_id, MIN(bkg.bkg_pickup_date) as start_time, 
						DATE_ADD(bkg.bkg_pickup_date, INTERVAL bkg.bkg_trip_duration MINUTE) as end_time  
						FROM booking bkg INNER JOIN booking_invoice biv ON bkg.bkg_id = biv.biv_bkg_id 
						WHERE bkg_id IN (" . implode(',', $arrBkgIds) . ") AND bkg_active = 1 ";
				$result	 = DBUtil::queryRow($sql);
				if ($result)
				{
					$bcbModel					 = $this;
					$bcbModel->bcb_bkg_id1		 = $result['multiple_bkg_id'];
					$bcbModel->bcb_start_time	 = $result['start_time'];
					$bcbModel->bcb_end_time		 = $result['end_time'];
					$bcbModel->bcb_vendor_amount = $vendorAmount;
					$bcbModel->bcb_trip_status	 = 1;
					$bcbModel->bcb_active		 = 1;
					$bcbModel->save();

					return $bcbModel->bcb_id;
				}
			}
			else
			{
				throw new Exception("Invalid booking ids", 1);
			}
		}
		catch (Exception $ex)
		{
			$this->addError("bcb_id", $ex->getMessage());
			return false;
		}
	}

	public function isCngAllowed($bkgId, $vhcModel = '')
	{
		$success			 = true;
		$msg				 = "";
		$sql				 = "SELECT bkg_cng_allowed , bai.bkg_num_large_bag AS luggage, scv.scv_scc_id FROM booking
								INNER JOIN booking_pref bpf ON booking.bkg_id = bpf.bpr_bkg_id
								INNER JOIN booking_add_info bai ON booking.bkg_id = bai.bad_bkg_id
								INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
								WHERE `bkg_id` = '$bkgId'";
		$rows				 = DBUtil::queryRow($sql, DBUtil::SDB());
		$hasCng				 = ($this->bcbCab->vhc_has_cng == '') ? $vhcModel->vhc_has_cng : $this->bcbCab->vhc_has_cng;
		$hasRooftopCarrier	 = ($this->bcbCab->vhc_has_rooftop_carrier == '') ? $vhcModel->vhc_has_rooftop_carrier : $this->bcbCab->vhc_has_rooftop_carrier;
		if ($rows['bkg_cng_allowed'] == 0 && $hasCng == 1)
		{
			$success = false;
			$msg	 = "CNG car not allowed for this booking";
		}

		if ($rows['bkg_cng_allowed'] == 1)
		{
			if ($hasCng != null && $hasCng != 0)
			{
				if ($hasCng == 1 && $rows['luggage'] < 2)
				{
					$success = true;
				}

				if ($hasCng == 1 && $hasRooftopCarrier == 1 && $rows['luggage'] > 1)
				{
					$success = true;
				}
				else if ($hasRooftopCarrier == 0 && $rows['luggage'] > 1)
				{
					$success = false;
					$msg	 = " CNG car not allowed as it has no rooftop carrier";
				}
			}
			else if ($rows['bkg_cng_allowed'] == 1 && $hasCng == 0)
			{
				$success = true;
			}
			else
			{
				$success = false;
				$msg	 = " Cab has no cng";
			}
			if ($rows['scv_scc_id'] == ServiceClass::CLASS_VLAUE_PLUS)
			{
				if ($hasRooftopCarrier == 0 && $hasCng == 1)
				{
					$success = false;
					$msg	 = "CNG car not allowed for this booking as it has no rooftop carrier";
				}
				else
				{
					$success = true;
				}
			}
		}

		$result = ['success' => $success, 'msg' => $msg];
		return $result;
	}

	public function RemovePendingList()
	{
		$sql = "SELECT booking.bkg_id BookingId,booking_cab.bcb_vendor_amount bcbvendamt,booking_cab.bcb_id bcb_id FROM booking_cab
				INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id 
				WHERE bcb_pending_status = 1 AND bcb_trip_type = 1 AND booking.bkg_status IN(6,7) 
				AND booking_cab.bcb_trip_status = 5 AND booking.bkg_create_date >='2016-11-01' limit 0,5";

		$result = DBUtil::queryAll($sql);
		foreach ($result as $value)
		{
			//echo $value['BookingId'] . "<br>";

			$bid		 = $value['BookingId'];
			$model		 = Booking::model()->findByPk($bid);
			$cabmodel	 = $model->getBookingCabModel();
			$cabmodel->setScenario('updatePendingStatus');
			$models		 = Booking::model()->getBookingModelsbyCab($cabmodel->bcb_id);
			$bkgIds		 = [];
			foreach ($models as $val)
			{
				$bkgIds[] = $val['bkg_id'];
			}

			//$userInfo	 = UserInfo::getInstance();
			$modelCab = new BookingCab();

			$modelCab->bcb_id			 = $value['bcb_id'];
			$modelCab->bcb_vendor_amount = $value['bcbvendamt'];
			$bcbModel					 = BookingCab::model()->findByPk($modelCab->bcb_id);
			$bcbModel->scenario			 = 'updateTripAmount';

			//$checkaccess = Yii::app()->user->checkAccess('changeVendorAmount');
			if (( $modelCab->bcb_vendor_amount > $bcbModel->bcb_vendor_amount) || ($modelCab->bcb_vendor_amount <= $bcbModel->bcb_vendor_amount))
			{
				$transaction = DBUtil::beginTransaction();
				try
				{
					$bcbModel->updateTripAmount($modelCab->bcb_vendor_amount, $userInfo);

					$modelBookingCab					 = BookingCab::model()->findByPk($modelCab['bcb_id']);
					$modelBookingCab->setScenario('updatePendingStatus');
					$modelBookingCab->bcb_vendor_amount	 = $modelCab['bcb_vendor_amount'];
					$modelBookingCab->bcb_pending_status = 0;
					$success							 = $modelBookingCab->save();
					$lowestStatus						 = $modelBookingCab->getLowestBookingStatus();
					if ($modelBookingCab->bcbVendor && ($lowestStatus == 7 || $lowestStatus == 6))
					{

						$bkgamt	 = $model->bkgInvoice->bkg_total_amount;
						$amtdue	 = $bkgamt - $model->bkgInvoice->getTotalPayment();

						$model->bkgInvoice->bkg_due_amount	 = $amtdue;
						$vndamt								 = $modelBookingCab->bcb_vendor_amount;
						$gzamount							 = $model->bkgInvoice->bkg_gozo_amount;
						if ($gzamount == '')
						{
							$gzamount							 = $bkgamt - $vndamt;
							$model->bkgInvoice->bkg_gozo_amount	 = $gzamount;
						}
						$gzdue				 = $gzamount - $model->bkgInvoice->getAdvanceReceived();
						$vendorDue			 = $model->bkgInvoice->bkg_vendor_collected - $modelBookingCab->bcb_vendor_amount;
						//$userInfo			 = UserInfo::getInstance();
						$date				 = new DateTime($model->bkg_pickup_date);
						$duration			 = $model->bkg_trip_duration | 120;
						$date->add(new DateInterval('PT' . $duration . 'M'));
						$findmatchBooking	 = Booking::model()->getMatchBookingIdbyTripId($modelBookingCab->bcb_id);
						foreach ($findmatchBooking as $valBookingID)
						{
							if (AccountTransDetails::model()->revertVenTransOnEditAcc($modelBookingCab->bcb_id, $valBookingID['bkg_id'], Accounting::LI_TRIP, Accounting::LI_OPERATOR))
							{
								if ($valBookingID['bkg_vendor_collected'] > 0)
								{
									AccountTransactions::model()->AddVendorCollection($modelBookingCab->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $modelBookingCab->bcb_id, $valBookingID['bkg_id'], $modelBookingCab->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $modelBookingCab->bcb_trip_status);
								}
								AccountTransactions::model()->AddVendorPurchaseTrip($modelBookingCab->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $modelBookingCab->bcb_id, $valBookingID['bkg_id'], $modelBookingCab->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $modelBookingCab->bcb_trip_status);
							}
						}
						$model->bkgInvoice->scenario		 = 'vendor_collected_update';
						$model->bkgInvoice->bkg_gozo_amount	 = round($gzamount);
						$model->bkgInvoice->bkg_due_amount	 = $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getTotalPayment();

						$model->bkgInvoice->addCorporateCredit();
						$model->bkgInvoice->calculateConvenienceFee($model->bkgInvoice->bkg_convenience_charge);
						$model->bkgInvoice->calculateTotal();
						$model->bkgInvoice->calculateVendorAmount();
						// $model->bkg_account_flag = 1;
						$model->bkgInvoice->save();
					}
					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
				}
			}
		}
	}

	public function checkSimilarModel($bkgId, $bcbCabModel)
	{
		$bkgModel	 = Booking::model()->findByPk($bkgId);
		$bkgVhtId	 = $bkgModel->bkg_vht_id;
		$vhcTypeId	 = $bcbCabModel->vhc_type_id;
		$scvModel	 = SvcClassVhcCat::model()->findByPk($bkgModel->bkg_vehicle_type_id);
		$sccId		 = $scvModel->scv_scc_id;
		if ($scvModel->scv_model > 0)
		{
			$bkgVhtId = $scvModel->scv_model;
		}
		$vhcTypeModel	 = VehicleTypes::model()->findByPk($bkgVhtId);
		$vhcModel		 = $vhcTypeModel->vht_make . ' ' . $vhcTypeModel->vht_model;

		$success = true;
		$msg	 = $sccId;

//		if ($sccId == 4 || $sccId == 5)
//		{
		if ($bkgVhtId != $vhcTypeId && $bkgVhtId > 0)
		{
			if ($bkgVhtId == 22 && $vhcTypeId == 65) // vehicleType inova=22 inovacrysta=65
			{
				$success = true;
				goto res;
			}
			$success = false;
			$msg	 = "Must be assign " . $vhcModel . " for this booking";
		}
		//}
		res:
		//return $success;
		return $result = ['success' => $success, 'msg' => $msg];
	}

	public static function getDetailsByBkgid($bkgid)
	{
		$sql = //	"SELECT	
//	bkg.bkg_status,
//	bkg.bkg_id,
//    drivers.drv_name,
//    vehicles.vhc_number,
//    vehicles.vhc_year,
//    vehicles.vhc_owner_contact_id,
//   	states.stt_name,
//	drivers_info.drv_state,
//	vehicle_types.vht_model,
//	vehicles.vhc_reg_owner
//	FROM
//		booking_cab bc
//	INNER JOIN booking bkg ON
//		bkg.bkg_bcb_id = bc.bcb_id
//	INNER JOIN drivers ON drivers.drv_id = bc.bcb_driver_id
//	LEFT JOIN drivers_info ON drivers_info.drv_id = drivers.drv_id
//	LEFT JOIN states ON states.stt_id = drivers_info.drv_state
//	INNER JOIN vehicles ON vehicles.vhc_id = bc.bcb_cab_id
//	INNER JOIN vehicle_types ON vehicle_types.vht_id = vehicles.vhc_type_id
//	WHERE
//    bkg.bkg_id = $bkgid AND bkg.bkg_active = 1 AND bc.bcb_cab_id IS NOT NULL AND bc.bcb_driver_id IS NOT NULL";

				"SELECT 
	drivers.drv_name,
	states.stt_name,
	contact.ctt_license_no,
	vehicles.vhc_number,
	vehicles.vhc_year, 
	vehicles.vhc_reg_owner,
	vehicle_types.vht_model,
	bkg.bkg_status,
	bkg.bkg_id     
	FROM   booking_cab bc
	INNER JOIN booking bkg ON bkg.bkg_bcb_id = bc.bcb_id
			INNER JOIN drivers ON drivers.drv_id = bc.bcb_driver_id AND drivers.drv_id = drivers.drv_ref_code AND drivers.drv_active =1
			INNER JOIN contact_profile AS cp on cp.cr_is_driver = drivers.drv_id AND cp.cr_status =1
			INNER JOIN contact ON cp.cr_contact_id = contact.ctt_id AND contact.ctt_active =1 AND contact.ctt_id = contact.ctt_ref_code
	INNER JOIN states ON states.stt_id = contact.ctt_state
	INNER JOIN vehicles ON vehicles.vhc_id = bc.bcb_cab_id
	INNER JOIN vehicle_types ON vehicle_types.vht_id = vehicles.vhc_type_id
	WHERE  bkg.bkg_id = $bkgid AND bkg.bkg_active = 1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	public static function setMaxOut($bcbid, $bcbIsMaxOut)
	{
		if ($bcbid > 0)
		{
			$sql	 = " UPDATE booking_cab SET bcb_is_max_out = $bcbIsMaxOut	WHERE bcb_id= $bcbid ";
			$result	 = DBUtil::command($sql)->execute();
			return $result;
		}
		return false;
	}

	public function getRemainingWorkingHours($bcbId)
	{
		$params	 = ['bcbID' => $bcbId];
		$sql	 = "SELECT
				bkg_id,
				bkg_bcb_id,
				bcb_vendor_id,
				CalcWorkingHour( booking_trail.bkg_assigned_at,booking.bkg_pickup_date) as hours,
				booking_trail.bkg_assigned_at
				FROM booking
				INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id
				WHERE 1
				AND booking_cab.bcb_vendor_id > 0
				AND booking.bkg_status IN (3)
				AND booking_cab.bcb_cab_id IS NULL
				AND booking_cab.bcb_driver_id IS NULL
				AND bcb_id=:bcbID";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	public function getCountVendorBlockPayment()
	{
		$returnSet = Yii::app()->cache->get('getCountVendorBlockPayment');
		if ($returnSet === false)
		{
			$sql		 = "SELECT COUNT(*) FROM (SELECT COUNT(*) AS count FROM `vendors` v
							INNER JOIN vendors v1 ON v1.vnd_id = v.vnd_ref_code
							INNER JOIN contact_profile cp on cp.cr_is_vendor = v1.vnd_id and cp.cr_status =1
							INNER JOIN contact cnt on cnt.ctt_id = cp.cr_contact_id and cnt.ctt_active =1 and cnt.ctt_id =cnt.ctt_ref_code
                        INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = v1.vnd_id 
                        INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = v1.vnd_id 
                        JOIN contact_email eml ON cnt.ctt_id = eml.eml_contact_id 
                        JOIN contact_phone phn ON cnt.ctt_id = phn.phn_contact_id 
                        LEFT JOIN(SELECT DISTINCT (vnd.vnd_id), bcb.bcb_lock_vendor_payment FROM `booking_cab` bcb 
                        INNER JOIN `booking` bkg ON bkg.bkg_bcb_id = bcb.bcb_id AND bkg.bkg_active=1 
                        INNER JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id AND vnd.vnd_active>0 WHERE bcb.bcb_lock_vendor_payment = 1 
                        GROUP BY vnd.vnd_id) AS lock_payment ON lock_payment.vnd_id=v1.vnd_id WHERE 1 AND v1.vnd_active IN (1,2,3,4) AND v1.vnd_id IN 
                        (SELECT vnd_id FROM `booking_cab` bcb 
                        INNER JOIN `booking` bkg ON bkg.bkg_bcb_id = bcb.bcb_id AND bkg.bkg_active=1 AND  bkg.bkg_status IN(3,5,6,7)
                        INNER JOIN vendors vnd ON vnd.vnd_id = bcb.bcb_vendor_id AND vnd.vnd_active>0 WHERE bcb.bcb_lock_vendor_payment = 1) GROUP BY v1.vnd_ref_code) abc ";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('getCountVendorBlockPayment', $returnSet, 600);
		}
		return $returnSet;
	}

	public static function stopVendorPayment($bcbId = NULL)
	{
		$bcbModel							 = self::model()->findByPk($bcbId);
		$bcbModel->bcb_lock_vendor_payment	 = 1;
		if ($bcbModel->save())
		{
			$desc	 = "Locked Vendor Payment";
			$eventid = BookingLog::LOCKED_PAYMENT;
			BookingLog::model()->createLog($bcbModel->bcb_bkg_id1, $desc, null, $eventid, null, false);
		}
	}

	public static function stopVendorPaymentForPartnerBooking($partnerId, $balance = 0)
	{
		$value		 = [];
		$condition	 = '';

		$params		 = ['partnerId' => $partnerId];
		$sql		 = "SELECT bcb_id, MIN(bkg_pickup_date) as pickupDate, min(bkg_id) as minBkg 
						FROM booking bkg 
						INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id 
						INNER JOIN booking_cab ON bkg_bcb_id=bcb_id 
						WHERE bkg.bkg_agent_id =:partnerId AND bkg.bkg_active = 1 AND bkg.bkg_status IN (3,5,6,7) 
						AND bkg.bkg_booking_type NOT IN(4,12)
						AND bkg_pickup_date >= '2020-04-01 00:00:00' AND booking_cab.bcb_lock_vendor_payment != 2
						GROUP  BY bcb_id ORDER BY pickupDate DESC, minBkg DESC";
		$recordsets	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		$value		 = false;

		foreach ($recordsets as $data)
		{
			if ($balance <= 0)
			{
				break;
			}
			$value	 = ['bkgId' => $data['minBkg'], 'pickupDate' => $data['pickupDate']];
			$bcbId	 = $data["bcb_id"];
			$due	 = self::stopPartnerVendorPayments($bcbId);
			if ($due > 0)
			{
				$balance -= $due;
			}
		}

		return $value;
	}

	public static function stopPartnerVendorPayments($bcbId)
	{
		$vendorDue	 = 0;
		$params		 = ['bcbId' => $bcbId];
		$sql		 = "SELECT bcb_lock_vendor_payment, (bcb_vendor_amount - SUM(bkg_total_amount - bkg_net_advance_amount)) as vendorDue
				FROM booking bkg 
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
				INNER JOIN booking_cab ON bcb_id=bkg_bcb_id
				WHERE bcb_id=:bcbId AND bkg.bkg_active = 1 AND bkg.bkg_status IN (3,5,6,7) AND bkg_pickup_date >= '2020-04-01 00:00:00' 
				GROUP  BY bcb_id HAVING vendorDue > 0";
		$row		 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		if ($row)
		{
			$vendorDue		 = $row["vendorDue"];
			$isPaymentLocked = $row["bcb_lock_vendor_payment"];

			if ($isPaymentLocked != 1)
			{
				$model = BookingCab::model()->findByPk($bcbId);
				$model->lockVendorPayment('Vendor payment locked due to outstanding partner balance');
			}
		}

		return $vendorDue;
	}

	public static function releasePartnerVendorPayments($pId, $bkgId = null, $pickupDate = null)
	{
		$params	 = ['partnerId' => $pId];
		$sql	 = "SELECT DISTINCT bcb_id FROM booking_cab 
				INNER JOIN booking ON bkg_bcb_id=bcb_id AND bkg_status IN (3,5,6,7) AND bcb_lock_vendor_payment=1
				WHERE bkg_agent_id=:partnerId";
		if ($bkgId != null)
		{
			$sql			 .= " and bkg_id<:bkgId";
			$params['bkgId'] = $bkgId;
		}
		if ($pickupDate != null)
		{
			$sql					 .= " AND bkg_pickup_date<=:pickupDate";
			$params['pickupDate']	 = $pickupDate;
		}
		$result = DBUtil::query($sql, DBUtil::SDB(), $params);
		foreach ($result as $res)
		{
			$bcbId = $res['bcb_id'];

			$model = BookingCab::model()->findByPk($bcbId);
			$model->releaseVendorPayment();
		}
	}

	public static function getTripIdByBkgId($bkgId)
	{
		$param	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT * FROM `booking_cab` WHERE FIND_IN_SET($bkgId,bcb_bkg_id1)";
		$record	 = DBUtil::query($sql, DBUtil::SDB(), $param);
		$arr	 = [];
		foreach ($record as $value)
		{
			$arr[] = $value['bcb_id'];
		}
		return $arr;
	}

	public static function getNextTripByVndId($vndId)
	{
		$param	 = ['vndId' => $vndId];
		$sql	 = "SELECT booking.bkg_id, booking.bkg_booking_id, booking.bkg_pickup_date, booking.bkg_booking_type, booking.bkg_from_city_id, booking.bkg_to_city_id
                    FROM  booking_cab
                    INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id
                    AND   booking_cab.bcb_active = 1
                    WHERE booking_cab.bcb_vendor_id =:vndId
                    AND   booking.bkg_status IN (3,4,5)
                    AND   booking.bkg_active = 1
                    AND   booking.bkg_pickup_date >= NOW()
                    ORDER BY  booking.bkg_pickup_date ASC LIMIT 1";
		$record	 = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
		return $record;
	}

	public function GetUnassignPenaltyCharge($unassignedTime, $assignedTime, $pickupTime, $vendorAmount, $acceptType, $dependencyScore)
	{
		if ($unassignedTime == "")
		{
			$unassignedTime = new CDbExpression('NOW()');
		}
		$sql	 = "SELECT GetUnassignPenaltyCharge($unassignedTime, '$assignedTime', '$pickupTime', $vendorAmount, $acceptType, $dependencyScore) as charge FROM dual";
		$record	 = DBUtil::command($sql)->queryScalar();
		return $record;
	}

	public function GetUnassignPenaltySlabs($unassignedTime, $assignedTime, $pickupTime, $vendorAmount, $acceptType, $dependencyScore)
	{
		$sql	 = "SELECT GetUnassignPenaltySlabs('$assignedTime', '$pickupTime', $vendorAmount, $acceptType, $dependencyScore) as slabs FROM dual";
		$record	 = DBUtil::command($sql)->queryScalar();
		return $record;
	}

	/**
	 * function checkPrvCabid show  last but one bookingcab id of a particular bookingId  
	 * @param type $bookingId
	 * @return type Int return bcbId
	 */
	public static function checkPrvCabid($bookingId)
	{


		$sql = "SELECT bcb_id from booking_cab WHERE bcb_bkg_id1 =:bookingId ORDER BY bcb_id DESC LIMIT 1,1";
		return DBUtil::command($sql)->queryScalar(['bookingId' => $bookingId]);
	}

	/**
	 * function modifyAssignMode use to modify assign mode of existing booking
	 * @param type $userType
	 * @param type $mode
	 */
	public static function modifyAssignMode($userType, $mode)
	{
		$sql	 = "SELECT blg_booking_id,bcb.bcb_assign_mode,bcb.bcb_id FROM booking_log blg
                    INNER JOIN booking bkg ON bkg.bkg_id = blg.blg_booking_id
                    INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb.bcb_vendor_id = blg.blg_vendor_assigned_id AND bcb.bcb_trip_status IN(2,3,4,5,6)
                    WHERE `blg_user_type` = $userType AND `blg_event_id` = 7 AND bcb_assign_mode<>$mode  ORDER BY `blg_id` DESC";
		$results = DBUtil::query($sql);
		foreach ($results as $value)
		{
			$bkgId		 = $value['blg_booking_id'];
			$bcbId		 = $value['bcb_id'];
			$assignMode	 = $value['bcb_assign_mode'];

			$sqlUpdate = 'UPDATE `booking_cab` SET bcb_assign_mode = "' . $mode . '"  WHERE bcb_id= "' . $bcbId . '"';

			$result = DBUtil::command($sqlUpdate)->execute();

			$sql2	 = "SELECT bkg_bcb_id FROM booking WHERE bkg_id=$bkgId";
			$row	 = DBUtil::queryRow($sql2);
			if ($bcbId == $row['bkg_bcb_id'])
			{
				$sqlUpdate = 'UPDATE `booking_trail` SET bkg_assign_mode = "' . $mode . '"  WHERE btr_bkg_id= "' . $bkgId . '"';

				$result = DBUtil::command($sqlUpdate)->execute();
			}
		}
	}

	/**
	 * 
	 * @param array() $params
	 * @param string $orderby
	 * @param int $partnerId
	 * @return \CSqlDataProvider
	 */
	public static function getAssignmentData($params, $orderby = 'date', $partnerId = 0, $includeB2c = false, $excludeAT = false, $nonAPIPartner = 0, $mmtbookings = 0, $command = DBUtil::ReturnType_Provider)
	{

		$from_date			 = $params['from_date'];
		$to_date			 = $params['to_date'];
		$fromCreateDate		 = $params['fromCreateDate'];
		$toCreateDate		 = $params['toCreateDate'];
		$bkgType			 = $params['bkgTypes'];
		$gnowType			 = $params['gnowType'];
		$nonProfitable		 = $params['nonProfitable'];
		$weekDays			 = $params['weekDays'];
		$zones				 = $params['zones'];
		$region				 = $params['region'];
		$state				 = $params['state'];
		$assignedFrom		 = $params['assignedFrom'];
		$assignedTo			 = $params['assignedTo'];
		$local				 = $params['local'];
		$outstation			 = $params['outstation'];
		$bkg_vehicle_type_id = $params['bkg_vehicle_type_id'];
		$assignMode			 = $params['assignMode'];
		$manualAssignment	 = $params['manualAssignment'];
		$criticalAssignment	 = $params['criticalAssignment'];
		$includeTFR			 = $params['includeTFR'];

		$where	 = '';
		$sqlJoin = '';

		$includeCondition = [];

		if ($includeTFR == 0)
		{
			$where .= " AND (bpr.bkg_manual_assignment='0' OR bkg_status<>2 OR bpr.bkg_is_fbg_type <> 1)";
		}

		if ($includeB2c)
		{
			$includeCondition[] = "(bkg_agent_id IS NULL OR bkg_agent_id = 1249 OR bkg_agent_id = '')";
		}
		if ($partnerId > 0)
		{
			$includeCondition[] = "(bkg_agent_id IN ($partnerId))";
		}
		if ($nonAPIPartner == 1)
		{
			$includeCondition[] = "(btr.bkg_platform NOT IN (7,9,10) AND bkg_agent_id IS NOT NULL)";
		}
		if ($mmtbookings == 1)
		{
			$includeCondition[] = "(bkg_agent_id IN (450,18190))";
		}

		if (count($includeCondition) > 0)
		{
			$where .= " AND (" . implode(" OR ", $includeCondition) . ")";
		}

		if ($excludeAT)
		{
			$where .= " AND bkg_booking_type NOT IN (4,12) ";
		}

		if ($nonProfitable == '1')
		{
			$where .= " AND (biv.bkg_gozo_amount - biv.bkg_credits_used)<0";
		}
		elseif ($nonProfitable == '2')
		{
			$where .= " AND (biv.bkg_gozo_amount - biv.bkg_credits_used)>0";
		}

		if (count($bkgType) > 0)
		{

			$bkgTypeStr	 = implode(",", $bkgType);
			$where		 .= " AND bkg_booking_type IN ($bkgTypeStr) ";
		}

		if (is_array($gnowType) && count($gnowType) > 0)
		{
			$gnowTypeStr = implode(',', $gnowType);
			$where		 .= "  AND (bpr.bkg_is_gozonow IN ($gnowTypeStr)) ";
		}

		$bkgTypeCondition = [];
		if ($local)
		{
			$bkgTypeCondition[] = "(bkg_booking_type IN (4,6,7,9,10,11,12,14,15))";
		}
		if ($outstation)
		{
			$bkgTypeCondition[] = "(bkg_booking_type IN (1,2,3,5,8))";
		}
		if (count($bkgTypeCondition) > 0)
		{
			$where .= " AND (" . implode(" OR ", $bkgTypeCondition) . ")";
		}

		if ($from_date != '' && $to_date != '')
		{
			$pickupDate = " AND (bkg_pickup_date BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59')";
		}

		if ($fromCreateDate != '' && $toCreateDate != '')
		{
			$createDate = " AND (bkg_create_date BETWEEN '$fromCreateDate 00:00:00' AND '$toCreateDate 23:59:59')";
		}

		if ($assignedFrom != '' && $assignedTo != '')
		{
			$where .= " AND (btr.bkg_assigned_at BETWEEN '$assignedFrom 00:00:00' AND '$assignedFrom 23:59:59')";
		}

		if (is_array($weekDays) && count($weekDays) > 0)
		{
			$weekDaysStr = implode(',', $weekDays);

			$where .= "  AND FIND_IN_SET(DAYOFWEEK(bkg_pickup_date), '{$weekDaysStr}') ";
		}

		if (count($zones) > 0)
		{
			$sqlJoin .= " INNER JOIN zone_cities zc ON zc.zct_cty_id=bkg_from_city_id AND zc.zct_active=1 
					INNER JOIN zones z ON z.zon_id=zc.zct_zon_id AND z.zon_active=1 ";

			$zonesStr	 = implode(",", $zones);
			$where		 .= " AND z.zon_id IN ($zonesStr) ";
		}

		if ($region != '' || $state != '')
		{
			$sqlJoin .= " INNER JOIN cities c1 ON c1.cty_id=bkg_from_city_id AND c1.cty_active=1 
						INNER JOIN states s1 ON s1.stt_id=c1.cty_state_id AND s1.stt_active = '1' ";

			if ($region != '')
			{
				if (is_array($region) && array_search("4", $region) !== false && array_search("7", $region) === false)
				{
					$region[] = "7";
				}
				$region		 = ($region == '4') ? '4,7' : $region;
				$strRegion	 = implode(',', $region);
				$where		 .= " AND s1.stt_zone IN ($strRegion) ";
			}
			if ($state != '')
			{
				$strState	 = implode(',', $state);
				$where		 .= " AND s1.stt_id IN ($strState) ";
			}
		}
		if (count($bkg_vehicle_type_id) > 0)
		{
			$vtype	 = implode(",", $bkg_vehicle_type_id); //Added the code block
			$where	 .= " AND (scv_id IN ($vtype) OR scv_parent_id IN ($vtype))";
		}

		if ($assignMode != null)
		{
			if ($assignMode == '0')
			{
				$where .= " AND (booking_cab.bcb_assign_mode IN (0))";
			}
			else if ($assignMode == '1')
			{
				$where .= " AND (booking_cab.bcb_assign_mode IN (1))";
			}
		}

		if ($manualAssignment > 0)
		{
			$where .= " AND bpr.bkg_manual_assignment='1'";
		}

		if ($criticalAssignment > 0)
		{
			$where .= " AND bpr.bkg_critical_assignment='1'";
		}


		$dataSelect = "SELECT   
			DATE_FORMAT(bkg_pickup_date, '%Y-%m-%d') AS date,	DATE_FORMAT(bkg_pickup_date, '%x-%v') AS week,	
			CONCAT(DATE_FORMAT(bkg_pickup_date, '%x-%v'), '\n',DATE_FORMAT(MIN(bkg_pickup_date), '%D %b'),' - ',DATE_FORMAT(MAX(bkg_pickup_date), '%D %b')) as weekLabel,
			DATE_FORMAT(bkg_pickup_date, '%b-%Y') AS monthname,	DATE_FORMAT(bkg_pickup_date, '%Y-%m') AS month, 
			'$orderby' groupType, COUNT(DISTINCT IF(bkg_status IN (2,3,5,6,7), bkg_id, NULL)) AS totalBooking,
			COUNT(DISTINCT IF(bkg_status IN (2,3,5,6,7) AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, bkg_id, NULL)) AS totalLossBooking,
			COUNT(IF(bkg_status=2 AND bkg_manual_assignment=1 AND bkg_critical_assignment=0, bkg_id, NULL)) as cntManual,
			COUNT(IF(bkg_status=2 AND bkg_critical_assignment=1, bkg_id, NULL)) as cntCritical,
			COUNT(IF(bkg_status IN (9) AND bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38), bkg_id, NULL)) AS totalGozoCancelled,
			COUNT(IF(bkg_status IN (9) AND bkg_cancel_id IN (3,9,16,17,19,20,22,26,28,29,30,33,34,35,36,38) AND bkg_agent_id=18190, bkg_id, NULL)) AS MMTGozoCancelled,
			COUNT(IF(bkg_status IN (9), bkg_id, NULL)) AS totalCancelled,
			COUNT(IF(bkg_status IN (2), bkg_id, NULL)) AS totalUnassigned, 
			COUNT(IF(bkg_status IN (3,5,6,7), bkg_id, NULL)) AS totalAssigned,
			COUNT(DISTINCT IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1, bkg_id, NULL)) AS countManualMargin,
			COUNT(DISTINCT IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1, bkg_id, NULL)) AS countAutoMargin,
			COUNT(DISTINCT IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2, bkg_id, NULL)) AS countDirectMargin,
			COUNT(DISTINCT IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 0, bkg_id, NULL)) AS countBidMargin,
			SUM(IF(bkg_status IN (2,3,5,6,7), (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS gozoAmount, 
			SUM(IF(bkg_status IN (2,3,5,6,7) AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS gozoLossAmount, 
			SUM(IF(bkg_status IN (3,5,6,7), (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS AssignedGozoAmount, 
			SUM(IF(bkg_status IN (2), bkg_net_base_amount, 0)) AS UnassignedNetBaseAmount, 
			SUM(IF(bkg_status IN (2,3,5,6,7), bkg_net_base_amount, 0)) AS netBaseAmount, 
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1, 1, 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7),1,0)), 2) AS ManualAssignPercent, 
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1, bkg_net_base_amount, 0)), 2) AS ManualMargin, 
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS ManualGozoAmount,
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, bkg_net_base_amount, 0)), 2) AS ManualLossMargin, 
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS ManualLossGozoAmount,
			COUNT(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, bkg_id, NULL)) AS ManualLossBookingCount,
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1, 1, 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7),1,0)), 2) AS AutoAssignPercent, 
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1, bkg_net_base_amount, 0)), 2) AS AutoMargin, 
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS AutoGozoAmount,
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, bkg_net_base_amount, 0)), 2) AS AutoLossMargin, 
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode <> 1 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS AutoLossGozoAmount,
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 0, 1, 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7),1,0)), 2) AS BidAssignPercent, 
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 0, bkg_net_base_amount, 0)), 2) AS BidAssignMargin, 
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS BidGozoAmount, 
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2, 1, 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7),1,0)), 2) AS DirectAssignPercent, 
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2, bkg_net_base_amount, 0)), 2) AS DirectAssignMargin, 
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS DirectGozoAmount,
			ROUND(SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, bkg_net_base_amount, 0)), 2) AS DirectAssignLossMargin,
			SUM(IF(bkg_status IN (3,5,6,7) AND bcb_assign_mode = 2 AND (biv.bkg_gozo_amount - biv.bkg_credits_used) < 0, (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) AS DirectLossGozoAmount,
			ROUND(SUM(IF(bkg_status IN (3,5,6,7), (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (3,5,6,7), bkg_net_base_amount, 0)), 2) AS AssignedMargin,
			ROUND(SUM(IF(bkg_status IN (2), (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (2), bkg_net_base_amount, 0)), 2) AS UnassignedMargin,
			ROUND(SUM(IF(bkg_status IN (2,3,5,6,7), (biv.bkg_gozo_amount - biv.bkg_credits_used), 0)) * 100 / SUM(IF(bkg_status IN (2,3,5,6,7), bkg_net_base_amount,0)), 2) AS TotalMargin ";

		$countSelect = "SELECT  DATE_FORMAT(bkg_pickup_date, '%Y-%m-%d') AS date 
					,DATE_FORMAT(bkg_pickup_date, '%U-%Y') AS week
					,DATE_FORMAT(bkg_pickup_date, '%m-%Y') AS month ";
		$sqlBody	 = "
						FROM     booking
                                 INNER JOIN svc_class_vhc_cat scv ON scv.scv_id = booking.bkg_vehicle_type_id
								 INNER JOIN booking_cab ON bkg_bcb_id = bcb_id AND bkg_status IN (2, 3, 5, 6, 7, 9)
								 INNER JOIN booking_invoice biv ON biv_bkg_id = bkg_id 
								 INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id
								 INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg_id $sqlJoin
						WHERE bkg_reconfirm_flag = 1 $pickupDate $createDate $where 
						GROUP BY $orderby
						";
		$sqlData	 = $dataSelect . $sqlBody;
		$sqlCount	 = $countSelect . $sqlBody;

		if ($command == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sqlData, [
				'totalItemCount' => $count,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes'	 => ['date', 'totalBooking', 'gozoAmount', 'netBaseAmount', 'ManualAssignPercent', 'totalGozoCancelled', 'AutoAssignPercent', 'TotalMargin'],
					'defaultOrder'	 => 'date DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sqlData, DBUtil::SDB3());
		}
	}

	/**
	 * function countVndBookingMode count different mode of booking(auto,manual,direct) for particular vendor
	 * @param type $vendorId
	 * @param type $mode
	 * @return type int bcb_id
	 */
	public static function countVndBookingMode($vendorId, $mode, $days = 90)
	{
		$params = ['vndId' => $vendorId, 'mode' => $mode, 'days' => $days];

		$sql = "SELECT count(DISTINCT(bcb.bcb_id)) counter FROM booking_cab bcb "
				. "INNER JOIN booking bkg ON bkg.bkg_id = bcb.bcb_bkg_id1 AND bkg.bkg_create_date >= DATE_SUB(curdate(), INTERVAL :days DAY)"
				. "WHERE bcb.bcb_vendor_id=:vndId AND bcb.bcb_assign_mode=:mode";

		$rows = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $rows;
	}

	/**
	 * modifyUnassignMode according to step unasign mode added
	 * @param type $step
	 * @param type $bcbId
	 * @return type
	 */
	public static function modifyUnassignMode($step, $bcbId)
	{

		$sql = "UPDATE booking_cab SET bcb_vendor_unassign_mode =$step, bcb_vendor_unassign_datetime = now() WHERE bcb_id  =$bcbId";
		$res = DBUtil::execute($sql);
		return $res;
	}

	/**
	 *  getVendorUnassignStep function show unassign mode according to trip Id
	 * @param type $bcbid
	 * @return int
	 */
	public static function getVendorUnassignStep($bcbid)
	{
		$sql = "SELECT booking_cab.bcb_driver_id , booking_cab.bcb_cab_id 
                FROM `booking_cab`
                WHERE booking_cab.bcb_id=$bcbid";
		$row = DBUtil::queryRow($sql);

		if ($row['bcb_driver_id'] != NULL && $row['bcb_driver_id'] != 0 && $row['bcb_cab_id'] != NULL && $row['bcb_cab_id'] != 0)
		{
			$step = 2;
		}
		else
		{
			$step = 1;
		}
		return $step;
	}

	/**
	 * countUnassignMode unassign mode count of particular vendor within 90 days
	 * @param type $vendorId
	 * @param type $mode
	 * @param type $days
	 * @return type
	 */
	public static function countUnassignMode($vendorId, $mode, $days = 90)
	{
		$params = ['vndId' => $vendorId, 'mode' => $mode, 'days' => $days];

		$sql = "SELECT count(DISTINCT(bcb.bcb_id)) counter FROM booking_cab bcb "
				. "INNER JOIN booking bkg ON bkg.bkg_id = bcb.bcb_bkg_id1 AND bkg.bkg_create_date >= DATE_SUB(curdate(), INTERVAL :days DAY)"
				. "WHERE bcb_vendor_id=:vndId AND bcb_vendor_unassign_mode=:mode AND bcb_vendor_unassign_datetime IS NOT NULL";

		$rows = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);

		return $rows;
	}

	/**
	 * get Booking Cab Details by booking Id
	 * @param type $bkgId
	 * @return array
	 */
	public static function getBookingCabDetailsByBkgID($bkgId)
	{
		$sql = "SELECT 
                temp.* ,
                (SELECT bcb_vendor_amount  FROM `booking_cab` WHERE bcb_id=temp.MAXBcbId) AS LVendorAmount,
                (SELECT bcb_vendor_amount  FROM `booking_cab` WHERE bcb_id=temp.MINBcbId) AS FVendorAmount,
                (SELECT `bcb_vendor_id`  FROM `booking_cab` WHERE bcb_id=temp.MAXBcbId) AS LVendorID,
                (SELECT bcb_vendor_id  FROM `booking_cab` WHERE bcb_id=temp.MINBcbId) AS FVendorID
                FROM 
                (
                    SELECT COUNT(*) AS TotalVendorAssignedCount,
                    MAX(bcb_created) AS  LVendorAssignmentDate,
                    MIN(bcb_created) AS  FVendorAssignmentDate ,
                    MAX(bcb_id) AS MAXBcbId,
                    MIN(bcb_id) AS MINBcbId
                    FROM `booking_cab` WHERE 1  AND FIND_IN_SET(:bkgId,`bcb_bkg_id1`) AND bcb_vendor_id > 0
                ) temp";

		$rows = DBUtil::queryRow($sql, DBUtil::SDB(), ['bkgId' => $bkgId]);
		return $rows;
	}

////////////////////////////////////	

	/** 	   
	 * @return CDbDataReader
	 */
	public static function getPendingTrips($tripid = '')
	{

		$criticalScore	 = 0.70;
		$params			 = [];
		$where			 = '';
		if ($tripid > 0)
		{
			$params['tripid']	 = $tripid;
			$where				 = ' AND bcb.bcb_notification_sent = 0 AND bcb.bcb_id =:tripid';
		}
		else if ($tripid == -1)   // when time diff is less than 12 hrs then we can activate gozo now
		{
			$where = "  AND bcb.bcb_notification_sent = 0 AND 
			(
					(TIMESTAMPDIFF(MINUTE,NOW(),bkg.bkg_pickup_date)<=720)
	
			)";
		}
		else
		{
			$params['criticalScore'] = $criticalScore;
			$where					 = " AND bcb.bcb_notification_sent = 0 AND (bpr.bkg_critical_score >= :criticalScore 
					OR (bpr.bkg_critical_score > 0.65 AND btr.btr_is_dem_sup_misfire = 1) 
					OR (bpr.bkg_critical_score > 0.5 AND bkg_pickup_date < '2022-10-26')
					OR btr.btr_nmi_flag=1 )";
		}

		$sql	 = "SELECT group_concat(DISTINCT bkg.bkg_id) bkgIds,
					bkg.bkg_bcb_id tripId,bkg.bkg_from_city_id,	bkg.bkg_to_city_id, bkg.bkg_pickup_date,
					min(bkg.bkg_pickup_date) first_pickup_date,
					max(bkg.bkg_pickup_date) last_pickup_date,
					bpr.bkg_critical_score
				FROM booking_cab bcb
				   INNER JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id
				   INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id 
				   INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id 
				WHERE 
				   bkg.bkg_status = 2 AND bkg.bkg_pickup_date > NOW()
				   AND bpr.bkg_block_autoassignment = 0 
				   AND bkg.bkg_reconfirm_flag = 1
				   $where
				GROUP BY bcb.bcb_id  
				ORDER BY bkg_critical_score DESC,bkg.bkg_pickup_date ASC";
		$data	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param int $bcbId
	 * @param type $sort
	 * @return CDbDataReader
	 */
	public static function getTripDetails($bcbId)
	{

		$params = ['bcbId' => $bcbId];

		$sql = "SELECT DISTINCT bkg.bkg_id, bkg.bkg_booking_id, bkg.bkg_bcb_id tripId, bkg.bkg_from_city_id,bcb.bcb_trip_type,
						bkg.bkg_to_city_id, brt.brt_from_city_id, brt.brt_to_city_id,brt.brt_pickup_datetime,brt.brt_trip_duration,
						fcty.cty_name fromCityName,tcty.cty_name toCityName,
						 bkg.bkg_vehicle_type_id, vct_label cabType, bkg.bkg_booking_type,
						bcb.bcb_vendor_amount tripVendorAmount, bkg.bkg_pickup_date, bpr.bkg_critical_score, bpr.bkg_is_gozonow 
					FROM booking_cab bcb
					INNER JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id
					INNER JOIN booking_route brt ON brt.brt_bcb_id = bcb.bcb_id
					INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id
					INNER JOIN svc_class_vhc_cat scv ON bkg.bkg_vehicle_type_id = scv.scv_id
					INNER JOIN vehicle_category vct ON vct.vct_id = scv.scv_vct_id AND vct.vct_active > 0      
					INNER JOIN cities fcty ON fcty.cty_id = brt.brt_from_city_id
					INNER JOIN cities tcty ON tcty.cty_id = brt.brt_to_city_id
					WHERE bkg.bkg_bcb_id = :bcbId AND bpr.bkg_block_autoassignment = 0 
					GROUP BY brt_id
					ORDER BY brt.brt_pickup_datetime ";

		$data = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * To send sms|notification to vendors for bookings
	 */
	public static function notifyVendorsForPendingBookings($tripid = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$bookingTypeArr = Booking::model()->booking_type;

		$bkgQueryList	 = BookingCab::getPendingTrips($tripid);
		$cnt			 = $bkgQueryList->getRowCount();
		Logger::trace("getPendingTrips : $cnt records");

		foreach ($bkgQueryList as $bcb)
		{
			$tripFlag = ($tripid == -1) ? false : true;
			BookingCab::processPendingBulkNotifications($bcb['tripId'], $tripFlag);

			/** @var CDbDataReader $tripDetailQry */
//			$tripDetailQry	 = BookingCab::getTripDetails($bcb['tripId']);
//			$routeCount		 = $tripDetailQry->getRowCount();
//			Logger::trace($bcb['tripId'] . " getTripDetails : $routeCount records");
//			if ($routeCount == 0)
//			{
//				continue;
//			}
//			$tripFlag		 = $tripid;
//			$tripDetailAll	 = $tripDetailQry->readAll();
//			$res			 = BookingCab::processPendingNotifications($tripDetailAll, $bookingTypeArr, $tripFlag);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 * 
	 * @param array $tripDetailAll
	 * @param array $bookingTypeArr
	 * @return int
	 */
	public static function processPendingNotifications($tripDetailAll, $bookingTypeArr, $tripFlag = 0)
	{
		$totSmS				 = 0;
		$totAppNotified		 = 0;
		$totSourceHomeZone	 = 0;
		$totDestHomeZone	 = 0;
		$totAcceptedZone	 = 0;
		$totVendorsNotified	 = 0;

		$routeCount	 = count($tripDetailAll);
		$tripDetail	 = $tripDetailAll[0];
		$tripId		 = $tripDetail['tripId'];
		$bookingId	 = $tripDetail['bkg_booking_id'];
		$bkgId		 = $tripDetail['bkg_id'];
		$bkgFromCity = $tripDetail['bkg_from_city_id'];

		$fromCityName	 = $tripDetail['fromCityName'];
		$toCityName		 = $tripDetailAll[$routeCount - 1]['toCityName'];
		$bkgToCity		 = $tripDetailAll[$routeCount - 1]['bkg_to_city_id'];
		$tripType		 = $bookingTypeArr[$tripDetail['bkg_booking_type']];

		if ($tripDetail['bcb_trip_type'] == 1)
		{
			$tripType = 'Matched Trip';
		}

		$cabType		 = $tripDetail['cabType'];
		$vehicleTypeId	 = $tripDetail['bkg_vehicle_type_id'];

		$pickupTime = DateTimeFormat::SQLDateTimeToLocaleDateTime($tripDetail['bkg_pickup_date']);

		$tripVendorAmount = $tripDetail['tripVendorAmount'];

		$bcbhash = Yii::app()->shortHash->hash($tripId);

		$appliedVendors = BookingVendorRequest::getAppliedVendors($tripId);

		$cabTypeList = Vehicles::getVhcTypeFromScv($vehicleTypeId);
		$vendors	 = Vendors::getByPickupCitynCabType($bkgFromCity, $bkgToCity, $cabTypeList, $appliedVendors);

		$totVendorFound = $vendors->getRowCount() | 0;

		Logger::trace($tripId . " getByPickupCitynCabType : " . $totVendorFound . " records");
		foreach ($vendors as $vendor)
		{
			$succSent	 = 0;
			$vndId		 = $vendor['vnd_id'];
			$vndhash	 = Yii::app()->shortHash->hash($vndId);

			/* $message	 = "Cab required ($cabType):
			  $fromCityName - $toCityName ($tripType)
			  $pickupTime
			  Amount: ₹$tripVendorAmount
			  Use Gozo Partner App to accept/bid
			  gozo.cab/bkvn1/$bcbhash/$vndhash"; */

			$message	 = "Cab required ($cabType): $fromCityName - $toCityName ($tripType) $pickupTime Amount: $tripVendorAmount Please use your Gozo Partner App to accept the bid gozo.cab/bkvn1/$bcbhash/$vndhash";
			$isLoggedIn	 = AppTokens::isVendorLoggedIn($vndId, 48);
			if ($isLoggedIn)
			{
//				$payLoadData = ['tripId' => $tripId, 'EventCode' => Booking::CODE_VENDOR_BOOKING_REQUEST];
				//$success	 = AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, "A new booking has been requested");

				$success = BookingCab::sendNotificationsAsGnow($tripId, $vndId);

				if ($success)
				{
					$totAppNotified++;
					$totVendorsNotified++;
					$succSent = 1;
				}
			}
			else
			{
				if ((($totSmS + $totAppNotified) >= 50 && $totSmS > 15) || $totSmS >= 30)
				{
					continue;
				}
				// if tripFlag=-1 then we will not send sms to vendor for gozo now
				if ($tripFlag != -1 && $tripDetail['bkg_is_gozonow'] != 1)
				{
					$vndPhone = $vendor['vnd_phone'];
					Filter::parsePhoneNumber($vndPhone, $code, $phnumber);
					if ($phnumber > 0)
					{
						$msgCom		 = new smsWrapper();
						$msgCom->sendSMStoVendors($phnumber, $message, 1, $bookingId);
						$totSmS++;
						$totVendorsNotified++;
						$succSent	 = 1;
					}
				}
				$success = BookingCab::sendNotificationsAsGnow($tripId, $vndId);
				if ($success)
				{
					$totAppNotified++;
					$totVendorsNotified++;
					$succSent = 1;
				}
			}
//			$cntSent++;
			switch ($vendor['isHomeZone'])
			{
				case 1:

					$totSourceHomeZone++;
					break;
				case 2:
					$totDestHomeZone++;
					break;

				default:
					$totAcceptedZone++;
					break;
			}

			Logger::trace($message);
		}


		$dbDateTime	 = Filter::getDBDateTime();
		$logArr		 = [
			'totSmS'				 => $totSmS,
			'totAppNotified'		 => $totAppNotified,
			'totSourceHomeZone'		 => $totSourceHomeZone,
			'totDestHomeZone'		 => $totDestHomeZone,
			'totAcceptedZone'		 => $totAcceptedZone,
			'totalVendorsNotified'	 => $totVendorsNotified,
			'totalVendorFound'		 => $totVendorFound,
			'notifiedDateTime'		 => $dbDateTime
		];

		$notifyVendor		 = new \Stub\booking\NotifyVendor();
		$notifyVendor->setData($logArr);
		$notificationInfo	 = json_encode($notifyVendor);

		Logger::writeToConsole($tripId . ': ' . $notificationInfo);
		Logger::trace($notificationInfo);

		$res = BookingCab::updatePendingVendorNotificationInfo($tripId, $notificationInfo);

		$cntRow		 = BookingPref::activateManualGozonow($bkgId);
		$userInfo	 = UserInfo::model();
		if ($cntRow)
		{
			$descStr = "GozoNOW auto-activated";
			BookingLog::model()->createLog($bkgId, $descStr, $userInfo, BookingLog::ACTIVATE_GOZO_NOW, false);
		}

		$model = Booking::model()->findByPk($bkgId);
		if ($model->bkgTrail->btr_nmi_flag == 0 && $totVendorsNotified < 5)
		{
			$oldTrailModel					 = clone $model->bkgTrail;
			$nmidesc						 = "WHY - Not enough vendors found";
			$model->bkgTrail->btr_nmi_flag	 = 1;
			$model->bkgTrail->updateNMI($nmidesc, $oldTrailModel, $userInfo);
		}


		return $res;
	}

	public static function sendNotificationsAsGnow($tripId, $vndId)
	{
		$succSent	 = 0;
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$bookings	 = $bcbmodel->bookings;
		$routeCount	 = count($bookings);

		if ($routeCount != 1)
		{
			return false;
		}
		$model = $bookings[0];

		$bkgId = $model->bkg_id;

		$vehicleTypeId	 = $model->bkg_vehicle_type_id;
		$bkgFromCity	 = $model->bkg_from_city_id;
		$bkgToCity		 = $model->bkg_to_city_id;

		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$title		 = "$cabType required urgent";

		$result	 = BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
		$resArr	 = json_decode($result['fcm'], true);
		if ($resArr['success'] > 0)
		{
			$succSent = 1;
		}

		return $succSent;
	}

	/**
	 * To send sms|notification to vendors for bookings
	 */
	public static function notifyVendorsByGnowNotification($tripid = '')
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);

		$bkgQueryList	 = BookingCab::getPendingTrips($tripid);
		$cnt			 = $bkgQueryList->getRowCount();
		Logger::trace("getPendingTrips : $cnt records");

		foreach ($bkgQueryList as $bcb)
		{
			$tripId = $bcb['tripId'];

			$res = BookingCab::gnowNotifyBulk($tripId);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 * 
	 * @param int $tripId 
	 * @return int
	 */
	public static function processPendingWithGnowNotifications($tripId)
	{
		$totSmS				 = 0;
		$totAppNotified		 = 0;
		$totSourceHomeZone	 = 0;
		$totDestHomeZone	 = 0;
		$totAcceptedZone	 = 0;
		$totVendorsNotified	 = 0;

		/** @var CDbDataReader $tripDetailQry */
		/** @var BookingCab $bcbmodel */
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$bookings	 = $bcbmodel->bookings;
		$routeCount	 = count($bookings);

		if ($routeCount != 1)
		{
			return false;
		}
		$model = $bookings[0];

		$bkgId = $model->bkg_id;

		$timerLogJson = BookingTrail::getGnowTimerLog($bkgId);
		if (!$timerLogJson)
		{
			$startTime = Filter::getDBDateTime();

			$timerLog		 = ['count' => 1, 'startTime' => $startTime];
			$timerLogJson	 = json_encode($timerLog);
			BookingTrail::updateGnowTimerLog($bkgId, $timerLogJson);
		}

		$success = BookingPref::activateManualGozonow($bkgId);

		$descStr = ($success == 0) ? "GozoNOW activated for the booking" : "Vendor(s) re-notified for GozoNOW";
//		$userInfo	 = UserInfo::model();
		BookingLog::model()->createLog($bkgId, $descStr, UserInfo::model(), BookingLog::ACTIVATE_GOZO_NOW, false);

		$vehicleTypeId	 = $model->bkg_vehicle_type_id;
		$bkgFromCity	 = $model->bkg_from_city_id;
		$bkgToCity		 = $model->bkg_to_city_id;

		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$title		 = "$cabType required urgent";

		$appliedVendors = BookingVendorRequest::getGnowAppliedVendors($tripId);

		$cabTypeList = Vehicles::getVhcTypeFromScv($vehicleTypeId);
		$vendors	 = Vendors::getByPickupCitynCabType($bkgFromCity, $bkgToCity, $cabTypeList, $appliedVendors);
		$cntSent	 = 0;
		$cntSucc	 = 0;

		$totVendorFound = $vendors->getRowCount() | 0;

		Logger::trace($tripId . " getByPickupCitynCabType : " . $totVendorFound . " records");
		foreach ($vendors as $vendor)
		{
			$succSent	 = 0;
			$vndId		 = $vendor['vnd_id'];

			$isLoggedIn = AppTokens::isVendorLoggedIn($vndId, 48);
			if ($isLoggedIn)
			{

				$result	 = BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
				$resArr	 = json_decode($result['fcm'], true);
				if ($resArr['success'] > 0)
				{
					$totAppNotified++;
					$totVendorsNotified++;
					$succSent = 1;
				}
			}
			else
			{
				if (($totSmS + $totAppNotified) >= 50)
				{
					continue;
				}
				$vndPhone = $vendor['vnd_phone'];
				Filter::parsePhoneNumber($vndPhone, $code, $phnumber);
				if ($phnumber > 0)
				{
					$msgCom		 = new smsWrapper();
					$msgCom->sendSMStoVendors($phnumber, $message, 1, $bkgId);
					$totSmS++;
					$totVendorsNotified++;
					$succSent	 = 1;
				}
			}

			if ($succSent == 1)
			{
				$params = [
					'tripId' => $tripId,
					'bkgId'	 => $bkgId,
				];
				BookingVendorRequest::notifiedGNowEntry($params, $vndId);
			}
			$cntSent++;
			switch ($vendor['isHomeZone'])
			{
				case 1:
					$totSourceHomeZone++;
					break;
				case 2:
					$totDestHomeZone++;
					break;

				default:
					$totAcceptedZone++;
					break;
			}

			Logger::trace($message);
		}
		$desc		 = "Vendors notified for Gozo now: Sent=$cntSent, Notified=$totVendorsNotified, App=$totAppNotified, SMS=$totSmS";
		$userInfo	 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
		BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::VENDOR_NOTIFIED_FOR_GOZONOW_BOOKING, false);

		$dbDateTime	 = Filter::getDBDateTime();
		$logArr		 = [
			'totSmS'				 => $totSmS,
			'totAppNotified'		 => $totAppNotified,
			'totSourceHomeZone'		 => $totSourceHomeZone,
			'totDestHomeZone'		 => $totDestHomeZone,
			'totAcceptedZone'		 => $totAcceptedZone,
			'totalVendorsNotified'	 => $totVendorsNotified,
			'totalVendorFound'		 => $totVendorFound,
			'notifiedDateTime'		 => $dbDateTime
		];

		$notifyVendor		 = new \Stub\booking\NotifyVendor();
		$notifyVendor->setData($logArr);
		$notificationInfo	 = json_encode($notifyVendor);

		Logger::writeToConsole($tripId . ': ' . $notificationInfo);
		Logger::trace($notificationInfo);
		$res = BookingCab::updatePendingVendorNotificationInfo($tripId, $notificationInfo);
		return $res;
	}

	/**
	 * 
	 * @param int $tripId
	 * @param string $infoJson
	 * @return int
	 */
	public static function updatePendingVendorNotificationInfo($tripId, $infoJson)
	{
		$params	 = ['tripId' => $tripId, 'infoJson' => $infoJson];
		$sql	 = "UPDATE booking_cab 
				SET bcb_notify_vendor_info =:infoJson,
				bcb_notification_sent = 1 				
				WHERE bcb_id =:tripId";
		$res	 = DBUtil::execute($sql, $params);
		return $res;
	}

	/**
	 * get First Vendor Amount by booking Id and vendor id
	 * @param int $bkgId
	 * @param int $vendorId
	 * @return int
	 */
	public static function getFirstVendorAmountByBkgId($bkgId, $vendorId)
	{
		$sql = "SELECT bcb_vendor_amount FROM `booking_cab` WHERE 1  AND FIND_IN_SET(:bkgId,`bcb_bkg_id1`) AND bcb_vendor_id > 0 and bcb_vendor_id=:bcb_vendor_id ORDER BY `bcb_id` ASC;";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['bkgId' => $bkgId, 'bcb_vendor_id' => $vendorId]);
	}

	public static function checkVendorTripRelation($tripId, $vndId)
	{
		$params = ['tripId' => $tripId, 'vndId' => $vndId];

		$sql = "SELECT  bkg.bkg_id,bcb.bcb_vendor_id,bkg.bkg_status
					FROM booking_cab bcb
					INNER JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id 					 
					WHERE bkg.bkg_bcb_id = :tripId  					
					AND ((bkg_status IN (3,5,6,7) AND bcb_vendor_id=:vndId) 
						OR bkg_status = 2)
					   ";

		$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public static function assignPreferredVendorDriverCab($tripId)
	{
		$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
		if (!$dataexist)
		{
			throw new Exception("Cab / Driver data is not provided", 1);
		}
		$vendorId			 = $dataexist['bvr_vendor_id'];
		$dataString			 = $dataexist['bvr_special_remarks'];
		$dataArr			 = json_decode($dataString, true);
		$bidAmount			 = $dataexist['bvr_bid_amount'];
		$driverId			 = $dataArr['driverId'];
		$cabId				 = $dataArr['cabId'];
		$bkgId				 = $dataexist['bvr_booking_id'];
		$drvphone			 = $dataArr['driverMobile'];
		$reachingAtMinutes	 = $dataArr['reachingAtMinutes'];
		$reachingAtTime		 = $dataArr['reachingAtTime'];

		/** @var BookingCab $bCabModel */
		/** @var Booking $bModel */
		$bModel		 = Booking::model()->findByPk($bkgId);
		$bCabModel	 = $bModel->bkgBcb;
		$remarks	 = "Vendor assigned for Gozo Now";
		$userInfo	 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
		$assignMode	 = 3;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bModel->updatePickupDate($reachingAtTime);
			$bCabModel->refresh();
			$result = $bCabModel->assignVendor($tripId, $vendorId, $bidAmount, $remarks, $userInfo, $assignMode);
			if ($result->isSuccess())
			{
				$bModel						 = Booking::model()->findByPk($bkgId);
				$bCabModel					 = $bModel->bkgBcb;
				$bCabModel->bcb_vendor_id;
				$bCabModel->bcb_driver_phone = $drvphone;
				$bCabModel->bcb_cab_id		 = $cabId;
				$bCabModel->bcb_driver_id	 = $driverId;
				$cab_type					 = $bModel->bkgSvcClassVhcCat->scv_vct_id;
				$success					 = $bCabModel->assignCabDriver($cabId, $driverId, $cab_type, $userInfo);
				$bModel->refresh();
				$bCabModel->refresh();
				if ($success)
				{
					DBUtil::commitTransaction($transaction);
					/* Allocation  notification stopped */
					//BookingCab::gnowWinBidNotify($tripId);
				}
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
		}
	}

	/**
	 * get First assignemnt type
	 * @param int $bkgId
	 * @param int $vendorId
	 * @return int
	 */
	public static function getFirstAssignmentType($bkgIds)
	{
		$sql = "SELECT bcb_first_assingment_type  FROM `booking_cab` WHERE 1  AND bcb_bkg_id1 like '%$bkgIds%'  AND bcb_vendor_id > 0 ORDER BY `bcb_id` ASC LIMIT 0,1";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	public static function gnowNotifyBulk($tripId, $excludeNotified = true, $entityIds = [], $hourDuration = 60)
	{
		/** @var BookingCab $bcbmodel */
		/** @var Booking $model */
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);
		if (!$model)
		{
			$success	 = false;
			$sentSucc	 = 'Error in booking';
			goto endOfProcessing;
		}
		$bkgId			 = $model->bkg_id;
		$vehicleTypeId	 = $model->bkg_vehicle_type_id;
		$bkgFromCity	 = $model->bkg_from_city_id;
		$bkgToCity		 = $model->bkg_to_city_id;

		if ($model->bkg_status != 2)
		{
			$success	 = false;
			$sentSucc	 = 'Cannot send notification at this booking status';
			goto endOfProcessing;
		}

		$onlyGozoNow		 = true;
		$notifiedVendorList	 = [];
		if ($excludeNotified)
		{
			$notifiedVendors	 = BookingVendorRequest::getAppliedVendors($tripId);
			$notifiedVendorList	 = explode(',', $notifiedVendors); //Notified vendor id array
		}

		if (empty($entityIds))
		{
			$fromZone	 = ZoneCities::getZonesByCity($bkgFromCity);
			$toZone		 = ZoneCities::getZonesByCity($bkgToCity);
//			$fromZoneArr = explode(",", $fromZone);
//			$toZoneArr	 = explode(",", $toZone);
//			$zoneIds	 = implode(",", array_unique(array_merge($fromZoneArr, $toZoneArr)));

			$distanceRange	 = 50;
			$accptedZoneIds	 = Zones::getServiceZoneList($fromZone, $distanceRange);
			$zoneIds		 = trim($accptedZoneIds . ',' . $toZone, ',');
			$data			 = Location::getVendorDriverByZoneIds($zoneIds);

			if ($data['cntVendor'] > 0)
			{
				$vendorIds1 = $data['vendorIds'];
			}
			if ($data['cntDriver'] > 0)
			{
				$vendorIds2 = VendorDriver::getVndByDrvId($data['driverIds']);
			}
			$vIdStr	 = trim($vendorIds1 . ',' . $vendorIds2, ','); //Eligible vendor id list in comma separated string
			$vIdList = explode(',', $vIdStr); //Eligible vendor id list in array
		}
		else
		{
			$vIdList = $entityIds;
		}
		$vndArr	 = array_unique(array_diff($vIdList, $notifiedVendorList)); //Filtering out vendors whom notification sent
		$vIds	 = implode(',', $vndArr); //Vendor id list in comma separated string whom no notification sent

		$vndData	 = Vendors::getGroupListByCabType($vehicleTypeId, $vIds, $onlyGozoNow, $hourDuration, $model->bkg_booking_type); //Final vnd data 
		$sentSucc	 = BookingCab::sendBulkGNowNotification($model, $vndData);
		BookingCab::gnowNotifyNotLoggedin($tripId);
		endOfProcessing:
		return $sentSucc;
	}

	public static function gnowNotify($tripId, $entityIds = [])
	{
		/** @var BookingCab $bcbmodel */
		/** @var Booking $model */
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);

		$vehicleTypeId	 = $model->bkg_vehicle_type_id;
		$bkgFromCity	 = $model->bkg_from_city_id;
		$bkgToCity		 = $model->bkg_to_city_id;
		if ($model->bkg_status != 2)
		{
			$success			 = false;
			$result['message']	 = 'Cannot send notification at this booking status';
			goto endOfProcessing;
		}

		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$title		 = "$cabType required urgent";
		if (empty($entityIds))
		{

			$fromZone	 = ZoneCities::getZonesByCity($bkgFromCity);
			$toZone		 = ZoneCities::getZonesByCity($bkgToCity);
			$fromZoneArr = explode(",", $fromZone);
			$toZoneArr	 = explode(",", $toZone);
			$zoneIds	 = implode(",", array_unique(array_merge($fromZoneArr, $toZoneArr)));

			$data	 = Location::getVendorDriverByZoneIds($zoneIds);
			$vendors = [];
			if ($data['cntVendor'] > 0)
			{
				$vendorIds		 = $data['vendorIds'];
				$onlyGozoNow	 = true;
				$cabTypeList	 = Vehicles::getVhcTypeFromScv($vehicleTypeId);
				$vendorsObject	 = Vendors::getByCabType($cabTypeList, $vendorIds, $onlyGozoNow);
				foreach ($vendorsObject as $vendor)
				{
					$vndId		 = $vendor['vnd_id'] != null ? $vendor['vnd_id'] : $vendor;
					$vendors[]	 = array('vnd_id' => $vndId);
				}
			}
			if ($data['cntDriver'] > 0)
			{
				$vendorIds		 = VendorDriver::getVndByDrvId($data['driverIds']);
				$onlyGozoNow	 = true;
				$cabTypeList	 = Vehicles::getVhcTypeFromScv($vehicleTypeId);
				$vendorsObject	 = Vendors::getByCabType($cabTypeList, $vendorIds, $onlyGozoNow);
				foreach ($vendorsObject as $vendor)
				{
					$vndId		 = $vendor['vnd_id'] != null ? $vendor['vnd_id'] : $vendor;
					$vendors[]	 = array('vnd_id' => $vndId);
				}
			}
			$notifiedVendors	 = BookingVendorRequest::getAppliedVendors($tripId);
			Logger::trace("notified vendors" . $notifiedVendors);
			$notifiedVendorList	 = explode(',', $notifiedVendors);
		}
		else
		{
			$vendors = $entityIds;
		}
		$cntSent	 = 0;
		$cntSucc	 = 0;
		$userType	 = UserInfo::TYPE_VENDOR;
		foreach ($vendors as $vendor)
		{
			$vndId = $vendor['vnd_id'] != null ? $vendor['vnd_id'] : $vendor;
			Logger::trace("notified vendor id" . $vndId);

			$isLoggedIn = AppTokens::isVendorLoggedIn($vndId, 480);
			if ($isLoggedIn && !in_array($vndId, $notifiedVendorList))
			{
				$cntSent++;
				Logger::trace("loggedin all message" . $message . '///title:' . $title . '////Vndid:' . $vndId . '///Payloaddata:' . $payLoadData);
				$result	 = AppTokens::model()->notifyEntity($vndId, $userType, $payLoadData, $message, $title);
//				$result	 = BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
				$resArr	 = json_decode($result['fcm'], true);
				Logger::trace("Fcm Data:" . $resArr);
				$cntSucc += ($resArr['success'] > 0) ? 1 : 0;
				$params	 = [
					'tripId' => $tripId,
					'bkgId'	 => $model->bkg_id,
				];
				Logger::trace("Vnd Id:" . $vndId);
				BookingVendorRequest::notifiedGNowEntry($params, $vndId);
				Logger::trace("VendorId" . $vndId);
			}
		}
		$userInfo	 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
		$desc		 = "Vendors notified for Gozo now: Sent=$cntSent, Success=$cntSucc";
		BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::VENDOR_NOTIFIED_FOR_GOZONOW_BOOKING, false);

//Creating NMI request
		if ($model->bkgTrail->btr_nmi_flag == 0 && $cntSent < 5)
		{
			$oldTrailModel					 = clone $model->bkgTrail;
			$nmidesc						 = "WHY - Not enough vendors found. Need to make GNOW active";
			$model->bkgTrail->btr_nmi_flag	 = 1;
			$model->bkgTrail->updateNMI($nmidesc, $oldTrailModel, $userInfo);
		}
//##
		endOfProcessing:
		return $desc;
	}

	public static function sendSampleGnowNotify($tripId, $vndId)
	{
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);
		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$title		 = "$cabType required urgent";
		BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
	}

	/**
	 * 
	 * @param int $vndId
	 * @param string $payLoadData
	 * @param string $message
	 * @param string $title
	 * @return type
	 */
	public static function gnowNotifyVendor($vndId, $payLoadData, $message, $title)
	{

		$entity_id	 = $vndId;
		$userType	 = UserInfo::TYPE_VENDOR;

		$success = AppTokens::model()->notifyEntityNew($entity_id, $userType, $payLoadData, $message, $title);
//		$success = AppTokens::model()->notifyEntity($entity_id, $userType, $payLoadData, $message, $title);
		return $success;
	}

	public static function notifyVendor($vndId, $payLoadData, $message, $title)
	{

		$entity_id	 = $vndId;
		$userType	 = UserInfo::TYPE_VENDOR;

		$success	 = AppTokens::model()->notifyEntity($entity_id, $userType, $payLoadData, $message, $title, $checkLogin	 = false);

		return $success;
	}

	/**
	 * 
	 * @param int $drvId
	 * @param string $payLoadData
	 * @param string $message
	 * @param string $title
	 * @return type
	 */
	public static function gnowNotifyDriver($drvId, $payLoadData, $message, $title)
	{

		$entity_id	 = $drvId;
		$userType	 = AppTokens::Platform_Driver;

		$success = AppTokens::model()->notifyEntity($entity_id, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param type $tripId
	 * @return type
	 */
	public static function gnowWinBidNotify($tripId)
	{
		/** @var BookingCab $bcbmodel */
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);

		$success = true;

//        if($model->bkg_cav_id > 0 && $model->bkgPref->bpr_is_flash == 1) 
//        {
//            goto skipBkgStatus;
//        }

		if ($model->bkg_status != 5)
		{
			$success	 = false;
			$sentSucc	 = 'Cannot send notification at this booking status';
			goto endOfProcessing;
		}

		//skipBkgStatus:

		$notify		 = new Stub\common\Notification();
		$notify->setGNowWinBidNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$title		 = $notify->title;
		$vndId		 = $model->bkgBcb->bcb_vendor_id;

		$sentSucc = BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
		endOfProcessing:
		return $sentSucc;
	}

	public static function cabDrvAssignNotify($bookingId)
	{
		$model		 = Booking::model()->findByPk($bookingId);
		$notify		 = new Stub\common\Notification();
		$notify->setCabDrvAssignNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$title		 = $notify->title;
		$vndId		 = $model->bkgBcb->bcb_vendor_id;

		$success = BookingCab::notifyVendor($vndId, $payLoadData, $message, $title);

		return $success;
	}

	/**
	 * 
	 * @param type $tripId
	 * @return type
	 */
	public static function readyToGoDriverNotify($tripId)
	{
		/** @var BookingCab $bcbmodel */
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);

		$notify		 = new Stub\common\Notification();
		$notify->setDriverReadyToGoNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$title		 = $notify->title;

		$drvId = $model->bkgBcb->bcb_driver_id;

		$success = BookingCab::gnowNotifyDriver($drvId, $payLoadData, $message, $title);

		return $success;
	}

	public function checkVehicleActiveTripTiming($vehicleId)
	{
		$pickupTime	 = $this->bookings[0]->bkg_pickup_date;
		$dropTime	 = date('Y-m-d H:i:s', strtotime($this->bookings[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
		foreach ($this->bookings as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$sql	 = "SELECT bkg.bkg_id, bcb.bcb_cab_id, vhc.vhc_number,bkg.bkg_status, bkg.bkg_pickup_date pickupDate,
                (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE) tripEndTime,
                bkg.bkg_trip_duration  FROM booking_cab bcb
              JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
              JOIN vehicles vhc ON vhc.vhc_id = bcb.bcb_cab_id
         WHERE bcb.bcb_cab_id IS NOT NULL AND bkg.bkg_status IN (5)
         AND bcb.bcb_cab_id = $vehicleId AND bcb.bcb_id <> {$this->bcb_id}
         GROUP BY bcb.bcb_id;";
		$overLap = 0;
		$rows	 = DBUtil::query($sql);

		foreach ($rows as $row)
		{
			if (($row['pickupDate'] <= $pickupTime && $row['tripEndTime'] >= $pickupTime) ||
					($row['pickupDate'] <= $dropTime && $row['tripEndTime'] >= $dropTime) ||
					($row['pickupDate'] >= $pickupTime && $row['tripEndTime'] <= $dropTime))
			{
				$overLap++;
			}
		}

		return $overLap;
	}

	/**
	 * This function will give all the bid count placed on the particular booking id
	 * @param int $bkgId
	 * @return int
	 */
	public static function getTotalBidCountByBkg($bkgId)
	{
		$sql = "SELECT COUNT(1) AS cnt FROM `booking_vendor_request` WHERE 1 AND `bvr_bid_amount`>0 AND bvr_booking_id=:bvr_booking_id";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['bvr_booking_id' => $bkgId]);
	}

	/**
	 * This function is used to send notification to multiple vendors
	 * @param type $tripId
	 * @param type $entityIds
	 * @return type
	 */
	public static function sendNotificationData($tripId, $entityIds)
	{
		/** @var BookingCab $bcbmodel */
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);

		$vehicleTypeId	 = $model->bkg_vehicle_type_id;
		$bkgFromCity	 = $model->bkg_from_city_id;
		$bkgToCity		 = $model->bkg_to_city_id;

		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$title		 = "$cabType required urgent";

		foreach ($entityIds as $vndId)
		{
			$success = BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
			$params	 = [
				'tripId' => $tripId,
				'bkgId'	 => $model->bkg_id,
			];
			$result	 = BookingVendorRequest::notifiedGNowEntry($params, $vndId);
		}
		return $result;
	}

	/**
	 * This function will give all the L1/L2 count placed on the particular booking id
	 * @param int $bkgId
	 * @return int
	 */
	public static function getTotalL1L2BookingCount($bkgIds)
	{
		$sql = "SELECT COUNT(IF(bcb_vendor_unassign_mode=1,1,null)) AS bks_l1_deny_count,COUNT(IF(bcb_vendor_unassign_mode=2,1,null)) AS bks_l2_deny_count FROM `booking_cab` WHERE 1  AND bcb_bkg_id1 like '%$bkgIds%'  AND bcb_vendor_id > 0 AND bcb_vendor_unassign_mode>=1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * This function will give all vendor whose upcoming booking over next 3 days
	 * @return string
	 */
	public static function getVendorList()
	{
		$sql = "SELECT 
                GROUP_CONCAT(v2.vnd_id) AS vendorId
                FROM booking
                INNER JOIN booking_cab ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status=3 
                INNER JOIN vendors ON vendors.vnd_id=booking_cab.bcb_vendor_id AND vendors.vnd_active=1 AND booking_cab.bcb_vendor_id >0
                INNER JOIN vendors v2 ON v2.vnd_id =vendors.vnd_ref_code
                WHERE bkg_pickup_date BETWEEN NOW() AND (DATE_ADD(NOW(), INTERVAL 5 DAY)) 
                AND booking_cab.bcb_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	/**
	 * This function will give all driver whose upcoming booking over next 3 days
	 * @return string
	 */
	public static function getDriverList()
	{
		$sql = "SELECT 
                GROUP_CONCAT(d2.drv_id) AS driverId
                FROM booking
                INNER JOIN booking_cab ON booking.bkg_bcb_id=booking_cab.bcb_id AND booking.bkg_active=1 AND booking.bkg_status=5 
                INNER JOIN drivers ON drivers.drv_id=booking_cab.bcb_driver_id AND drivers.drv_active=1 AND booking_cab.bcb_driver_id>0
                INNER JOIN drivers d2 ON d2.drv_id =drivers.drv_ref_code
                WHERE bkg_pickup_date BETWEEN NOW() AND (DATE_ADD(NOW(), INTERVAL 5 DAY)) 
                AND booking_cab.bcb_active=1";
		return DBUtil::queryScalar($sql, DBUtil::SDB());
	}

	/**
	 * Booking min max allowed bid amount
	 */
	public function getMinMaxAllowedBidAmount()
	{
		$isGNowBkg		 = false;
		$bkgVendorAmount = 0;
		$minBid			 = 0;
		$maxBid			 = 0;

		// Is Bookings GNow
		$bkgModels = $this->bookings;
		foreach ($bkgModels as $bkgModel)
		{
			$bkgVendorAmount += $bkgModel->bkgInvoice->bkg_vendor_amount;

			if (!$isGNowBkg && $bkgModel->bkgPref->bkg_is_gozonow > 0)
			{
				$isGNowBkg = $bkgModel->bkgPref->bkg_is_gozonow;
			}
		}

		// Min Bid
		$minBid = round($this->bcb_vendor_amount * 0.70);

		// Max Bid
		if ($isGNowBkg == 1)
		{
			//$maxBid = ($bkgVendorAmount * 2);
			$addParamJson		 = $bkgModel->bkgPf->bkg_additional_param;
			$addParamArr		 = json_decode($addParamJson, true);
			$suggestedOfferRange = $addParamArr['vndGnowOfferSuggestion'];
			if ($suggestedOfferRange)
			{
				$minAmount	 = $suggestedOfferRange['minVendorAmount'];
				$maxBid		 = $suggestedOfferRange['maxVendorAmount'];
			}
			if ($maxBid == 0)
			{
				$maxBid = ($bkgModel->bkgInvoice->bkg_total_amount * 1.25);
			}
		}
		else
		{
			$maxAllowedVABidAmount	 = $this->bcb_max_allowable_vendor_amount * 1.5;
			$tripVABidAmount		 = $this->bcb_vendor_amount * 2;
			$bkgVABidAmount			 = $bkgVendorAmount * 2;
			$maxBid					 = max($maxAllowedVABidAmount, $tripVABidAmount, $bkgVABidAmount);
		}

		$arrAllowedBids = ['minBid' => $minBid, 'maxBid' => $maxBid, 'isGNowBkg' => $isGNowBkg];

		return $arrAllowedBids;
	}

	/**
	 * getFirstBkgByTripId
	 * @param $bcbId
	 */
	public static function getFirstBkgByTripId($bcbId, $bkgStatus = null)
	{
		$cond = "";
		if ($bkgStatus != null)
		{
			$cond = " AND bkg_status IN (" . implode(',', $bkgStatus) . ") ";
		}

		$sql = "SELECT bkg_id, bkg_pickup_date, TIMESTAMPDIFF(MINUTE, NOW(), bkg_pickup_date) as pickupTimeDiff 
                FROM booking 
                INNER JOIN booking_cab ON bcb_id = bkg_bcb_id AND bkg_active=1 
				WHERE bcb_id = {$bcbId} {$cond}
				ORDER BY bkg_pickup_date ASC
				LIMIT 0, 1";
		return DBUtil::queryRow($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $tripId
	 */
	public static function fetchVendorRelatedAmount($tripId)
	{
		$params	 = ['tripId' => $tripId];
		/*
		  $sql	 = "SELECT bcb_id, SUM(bkg_gozo_amount) as gozoAmount, bcb_vendor_amount, bcb_max_allowable_vendor_amount,
		  IF(bcb_max_allowable_vendor_amount>0, bcb_max_allowable_vendor_amount, (SUM(bkg_total_amount-bkg_service_tax-bkg_partner_commission))) maxAllowedAmount
		  FROM booking_cab
		  INNER JOIN booking ON bcb_id=bkg_bcb_id
		  INNER JOIN booking_invoice ON biv_bkg_id=bkg_id
		  WHERE bcb_id=:tripId GROUP BY bcb_id ";
		 */
		$sql	 = "SELECT bcb_id, SUM(bkg_gozo_amount) as gozoAmount, bcb_vendor_amount, bcb_max_allowable_vendor_amount, IF(bcb_max_allowable_vendor_amount>0, bcb_max_allowable_vendor_amount, (SUM(bkg_total_amount-(bkg_service_tax*1.5)-bkg_partner_commission-bkg_credits_used))) maxAllowedAmount 
		FROM booking_cab 
		INNER JOIN booking ON bcb_id=bkg_bcb_id 
		INNER JOIN booking_invoice ON biv_bkg_id=bkg_id 
		WHERE bcb_id=:tripId GROUP BY bcb_id ";
		$result	 = DBUtil::queryRow($sql, null, $params);
		return $result;
	}

	/**
	 * This function is used get all booking completed count along with rating if he/she has got  within last seven days for each vendor
	 * @return query Object
	 */
	public static function getlastWeekVedorCompletedTrip()
	{
		$sql = "SELECT 
				COUNT(bcb_id) AS tripCount,
				SUM(IF(ratings.rtg_customer_overall IS NOT NULL,1,NULL)) AS ratingCount,
				booking_cab.bcb_vendor_id AS vnd_id
				FROM booking 
				JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id
				JOIN booking_trail ON booking_trail.btr_bkg_id=booking.bkg_id
				JOIN ratings ON ratings.rtg_booking_id=booking.bkg_id
				WHERE 1
				AND booking_cab.bcb_vendor_id >0 
				AND bkg_status IN  (5,6,7)
				AND booking_trail.btr_mark_complete_date BETWEEN (CURDATE() - INTERVAL 1 WEEK) AND CURDATE()
				GROUP BY booking_cab.bcb_vendor_id";
		return DBUtil::query($sql, DBUtil::SDB());
	}

	/**
	 * 
	 * @param type $vndId
	 * @param type $bkgstatus
	 * @return type
	 */
	public static function getByVendor($vndId, $bkgstatus = '')
	{
		$params	 = ["vndId" => $vndId];
		$where	 = '';
		if ($bkgstatus != '')
		{
			$statusArrRaw	 = explode(',', $bkgstatus);
			$statusArr		 = array_filter(array_unique($statusArrRaw));
			$statusStr		 = implode(',', $statusArr);

			$where = " AND bkg.bkg_status IN ($statusStr)";
		}
		$dataSql = "SELECT * FROM booking_cab bcb
				INNER JOIN booking bkg on bkg.bkg_bcb_id =bcb.bcb_id AND bkg.bkg_status NOT IN (1,7,8,9,10,11,12,13,14,15)				 
				WHERE bcb.bcb_vendor_id= :vndId $where";
		return DBUtil::query($dataSql, DBUtil::SDB(), $params);
	}

	public static function validateTripForBidding($tripId, $vendorId, $isDirectAccept = false)
	{

//direct accept
		$cabModel	 = BookingCab::model()->findByPk($tripId);
		$bModels	 = $cabModel->bookings;

		if (COUNT($bModels) == 0)
		{
			$bModels			 = BookingSmartmatch::model()->getBookings($tripId);
			$cabModel->bookings	 = $bModels;
		}

		$vendorModel	 = Vendors::model()->findByPk($vendorId);
		$securityAmount	 = $vendorModel->vendorStats->vrs_security_amount;
		$codFreeze		 = $vendorModel->vendorPrefs->vnp_cod_freeze;
		foreach ($bModels as $bModel)
		{
			/* if ($bModel->bkg_agent_id == $spicejetId && $bModel->bkg_reconfirm_flag <> 1)
			  {
			  $bModel->addError('bkg_id', "Sorry! This booking is not confirmed yet");
			  $error = $bModel->getErrors();
			  throw new Exception(json_encode($error), 1);
			  } */
//							if ($securityAmount < 0 && $codFreeze == 1)
//							{
//								$bModel->addError('bkg_id', "Your security amount is low and Your Gozo Account is freezed. You do not have permission to serve that booking.");
//								$error = $bModel->getErrors();
//								throw new Exception(json_encode($error), 1);
//							}
//							if ($securityAmount > 0 && $codFreeze == 1 && $bModel->bkgInvoice->bkg_corporate_remunerator != 2)
//							{
//
//								$bModel->addError('bkg_id', "Your Gozo Account is freezed.You do not have permission to serve that booking.");
//								$error = $bModel->getErrors();
//								throw new Exception(json_encode($error), 1);
//							}

			if ($bModel->bkg_reconfirm_flag <> 1)
			{
				$bModel->addError('bkg_id', "Sorry! This booking is not confirmed yet");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}
			$bookingType = $bModel->bkg_booking_type;
			$dataCount	 = VendorPref::checkApprovedService($vendorId, $bookingType);
			if ($dataCount < 1)
			{

				$bModel->addError("bkg_id", "You do not have permission to serve this booking.");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}


			$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
			if ($isVendorUnassigned)
			{
				$bModel->addError("bkg_id", "You already denied this booking. Cannot bid on it again.");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}

			if ($bModel->bkg_status != 2)
			{
				$bModel->addError('bkg_id', "Oops! This booking is already assigned.");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
//break;
			}
			if ($bModel->bkgPref->bkg_block_autoassignment == 1)
			{
				$bModel->addError('bkg_id', "Oops! This booking cannot be direct accepted.");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}

			/* if (!Vehicles::checkVehicleAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id))
			  {
			  $bModel->addError('bkg_id', "Oops! You have no cab for this booking");
			  $error = $bModel->getErrors();
			  throw new Exception(json_encode($error), 1);
			  } */
			if (!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
			{
				$bModel->addError('bkg_id', "Oops! You have no driver for this booking");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}
			$booking_class = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
			if (!Vehicles::checkVehicleclass($vendorId, $booking_class))
			{
				$bModel->addError('bkg_id', "Oops! You have no cab in same class of this booking");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}
			$chkOutStanding = VendorStats::frozenOutstanding($vendorId);
			if ($chkOutStanding > 1500)
			{
				$bModel->addError('bkg_id', "Oops! Your payment is overdue. Please settle your Gozo accounts ASAP.");
				$error = $bModel->getErrors();
				throw new Exception(json_encode($error), 1);
			}
			if ($bModel->bkgPref->bkg_critical_score < 0.7)
			{
				$statModel			 = VendorStats::model()->getbyVendorId($vendorId);
				$dependency			 = $statModel->vrs_dependency;
				$calculateDependency = ($dependency == '' ? 0 : $dependency);
				if ($calculateDependency < 0)
				{

					$bModel->addError('bkg_id', "Dependability score low. Direct accept not available for you. To improve dependability score, do not refuse booking after you accept.");
					$error = $bModel->getErrors();
					throw new Exception(json_encode($error), 1);
				}
			}
		}
	}

	public static function validateVendorTripForBidding($tripId, $vendorId)
	{
		$returnSet	 = new ReturnSet();
		$cabModel	 = BookingCab::model()->findByPk($tripId);
		/** @var BookingCab $cabModel */
		$bModels	 = $cabModel->bookings;
		$errors		 = [];
		if (sizeof($bModels) == 0)
		{
			$bModels			 = BookingSmartmatch::model()->getBookings($tripId);
			$cabModel->bookings	 = $bModels;
		}
		if (COUNT($bModels) == 0 || $bModels == 0)
		{
			$errors[] = "Sorry!! No booking found";
			goto getError;
		}

		//validateVendor

		$vendorModel	 = Vendors::model()->findByPk($vendorId);
		$vnpCodFreeze	 = $vendorModel->vendorPrefs->vnp_cod_freeze;
		$isDocApprove	 = (($vendorModel->vnd_active == 1) || ($vendorModel->vendorPrefs->vnp_is_orientation > 0)) ? true : false;
		$isApproveCar	 = ($vendorModel->vendorStats->vrs_approve_car_count > 0) ? true : false;
		$isApproveDriver = ($vendorModel->vendorStats->vrs_approve_driver_count > 0) ? true : false;

		$cashBkgValidation = BookingInvoice::checkCODBkg($bModel->bkg_id, $vnpCodFreeze);
		if ($cashBkgValidation == false)
		{
			$errors[] = "Sorry! You do not have permission to accept cash booking.";
			goto getError;
		}
		if ($isDocApprove == false)
		{
			$errors[] = "Check documents. Your documents are missing or not yet approved.";
			goto getError;
		}

		if ($isApproveCar == false)
		{
			$errors[] = "Get 1 car approved before we can send you business.";
			goto getError;
		}
		if ($isApproveDriver == false)
		{
			$errors[] = "Get 1 driver approved before we can send you business.";
			goto getError;
		}
		if ($vendorModel->vnd_active == 2)
		{
			$errors[] = "Your Gozo account is blocked. Please contact Gozo vendor team.";
			goto getError;
		}

		foreach ($bModels as $bModel)
		{
			/** @var Booking $bModel */
			$booking_class	 = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
			$bookingType	 = $bModel->bkg_booking_type;
			if ($bModel->bkg_status != 2)
			{
				$errors[] = "Booking is not in assignable status";
				goto getError;
			}
			if (!Vehicles::checkVehicleclass($vendorId, $booking_class))
			{
				$errors[] = "Check your approved cars. None in this service class";
				goto getError;
			}
			$dataCount = VendorPref::checkApprovedService($vendorId, $bookingType);
			if ($dataCount < 1)
			{
				$errors[] = "No permisssion to serve this booking";
				goto getError;
			}

			$check_availability = Vehicles::checkVehicleAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id);
			if ($check_availability != "")
			{
				$errors[] = $check_availability;
				goto getError;
			}

			if ($bModel->bkg_reconfirm_flag <> 1 && $bModel->bkgPref->bkg_is_gozonow != 1)
			{
				$bModel->addError('bkg_id', "Sorry! This booking is not confirmed yet");
				goto getError;
			}

			$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
			if ($isVendorUnassigned)
			{
				$bModel->addError("bkg_id", "You already denied this booking. Cannot bid on it again.");
				goto getError;
			}
		}

		if (!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
		{
			$bModel->addError('bkg_id', "Oops! You have no driver for this booking");
			goto getError;
		}

		$chkOutStanding = VendorStats::frozenOutstanding($vendorId);
		if ($chkOutStanding > 1500)
		{
			$bModel->addError('bkg_id', "Oops! Your payment is overdue. Please settle your Gozo accounts ASAP.");
			goto getError;
		}



		$returnSet->setStatus(true);
		getError:
		if ($bModel && $bModel->hasErrors())
		{
			$errors[] = $bModel->getErrors();
		}
		if (sizeof($errors) > 0)
		{
			throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @param \BookingCab $cabModel
	 * @param \Beans\booking\BidAction $bidActionObj
	 * @throws Exception
	 */
	public static function validateGNowTripForBidding($cabModel, $bidActionObj, $vendorId)
	{
		$returnSet	 = new ReturnSet();
		/** @var \Beans\booking\BidAction $bidActionObj */
		$bidAmount	 = ceil($bidActionObj->amount);

		$reachMinutes = $bidActionObj->reachingAfterMinutes;

		//$vendorId	 = UserInfo::getEntityId();
		$driverId	 = $bidActionObj->getDriverId();
		$cabId		 = $bidActionObj->getCabId();
		$tripId		 = $bidActionObj->tripId;
		try
		{
			if ($driverId == '' || $cabId == '')
			{
				$error = "Please provide driver and cab details";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
			if (isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				$error = "Booking already assigned to other partner";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if ($bidAmount == '' || $bidAmount == 0)
			{
				$error = "Please re-check the bid amount.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}

			$lastOffer = BookingVendorRequest::getMinimumGNowOfferAmountbyVendor($tripId, $vendorId);
			if ($lastOffer && $lastOffer <= $bidAmount)
			{
				$error = "Current bid is higher than your previous bid(s). Try again.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			$maxAllowableVndAmt	 = $cabModel->bcb_max_allowable_vendor_amount;
			$maxVndAmt			 = ($maxAllowableVndAmt > 0) ? $maxAllowableVndAmt : $cabModel->bcb_vendor_amount;

			$arrAllowedBids = $cabModel->getMinMaxAllowedBidAmount();
			#if ($maxVndAmt < $bidAmount && $isAdminGozoNow == 1)
			if ($arrAllowedBids['minBid'] > $bidAmount)
			{
				$error = "Bid amount is too small. Check your bid.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if ($arrAllowedBids['maxBid'] < $bidAmount)
			{
				$error = "Bid amount is too high. Check your bid.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if ($reachMinutes == '' || $reachMinutes <= 0)
			{
				$error = "Please enter the valid duration by which you will reach";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param BookingCab $cabModel
	 * @param \Beans\booking\BidAction $bidActionObj
	 * @return type
	 */
	public static function validateGNowDriverCabForBidding($cabModel, $bidActionObj, $vendorId)
	{
		$returnSet	 = new ReturnSet();
		$bidAmount	 = ceil($bidActionObj->amount);

		$reachMinutes	 = $bidActionObj->reachingAfterMinutes;
		$dnow			 = Filter::getDBDateTime();

		$reachingAT = date('Y-m-d H:i:s', strtotime($dnow . '+' . $reachMinutes . ' MINUTE'));

		//$vendorId	 = UserInfo::getEntityId();
		$driverId	 = $bidActionObj->getDriverId();
		$cabId		 = $bidActionObj->getCabId();
		$drvPhone	 = $bidActionObj->getDriverMobile();
		$tripId		 = $bidActionObj->tripId;

		Filter::parsePhoneNumber($drvPhone, $code, $driverMobile);
		if ($driverMobile == '')
		{
			$error = "Please provide valid driver mobile number";
			throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
		}


		$cabModel->scenario = 'assigncabdriver';

		$cabModel->bcb_driver_phone	 = $driverMobile;
		$cabModel->bcb_cab_id		 = $cabId;
		$cabModel->bcb_driver_id	 = $driverId;

		$bModels	 = $cabModel->bookings;
		$bkgId		 = $bModels[0]->bkg_id;
		$cab_type	 = $bModels[0]->bkgSvcClassVhcCat->scv_vct_id;
		try
		{
			if ($cabModel->bcbCab->vhc_approved != 1)
			{
				$error = "Cab is not approved";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if (!$cabModel->bcbCab->getVehicleApproveStatus())
			{
				$error = "Cab is freezed";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if (!$cabModel->bcbDriver->getDriverApproveStatus())
			{
				$error = "Driver is not approved";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			$vvhcModel = VendorVehicle::model()->findByVndVhcId($vendorId, $cabId);
			if (!$vvhcModel && $vvhcModel->vvhc_active != 1)
			{
				$error = "Cab is not attached with you. Please sign LOU.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if ($cab_type != '')
			{
				$cabModel->pre_cab_type	 = $cab_type;
				$cabModel->post_cab_type = $cabModel->bcbCab->vhcType->vht_VcvCatVhcType->vcv_vct_id;
			}

			Preg_match("/\d*(\d{10})/", $cabModel->bcb_driver_phone, $match);
			if (empty($match))
			{
				$cabModel->addError('bcb_driver_id', 'Driver Phone No is missing.');
				return false;
			}

			$cabModel->bcb_driver_phone = $match[1];

			$cabModel->bcb_cab_number	 = strtoupper($cabModel->bcbCab->vhc_number);
			$cabModel->bcb_trip_status	 = BookingCab::STATUS_CAB_DRIVER_ASSIGNED;
			$bModels[0]->bkg_status		 = 3;
			$validated					 = $cabModel->validate();
			if (!$validated)
			{
				$errorsList = $cabModel->getErrors();
				throw new Exception(json_encode($errorsList), ReturnSet::ERROR_VALIDATION);
			}

			foreach ($bModels as $bModel)
			{
				$bModel->refresh();
				$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
				if ($isVendorUnassigned)
				{

					$error = "You were unassigned from / denied this trip before. So you cannot bid on it again.";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				if ((strtotime($bModel->bkg_pickup_date) + 30) < strtotime($reachingAT) || strtotime($bModel->bkg_pickup_date) < strtotime($dnow))
				{
					$error = "Oops! Looks like you will not reach the pickup ontime";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				if ($bModel->bkg_status != 2)
				{
					$error = "Oops! The booking is already taken by another partner. Please be quicker next time";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				if ($bModel->bkgPref->bkg_block_autoassignment == 1)
				{
					$error = "Oops! This booking cannot be direct accepted.";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				if (Vehicles::checkVehicleAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time, $bModel->bkg_from_city_id, $bModel->bkg_to_city_id, $bModel->bkgSvcClassVhcCat->scv_vct_id) != '')
				{
					$error = "Oops! You have no cab for this booking";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				if (!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
				{
					$error = "Oops! You have no driver for this booking";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				$booking_class = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
				if (!Vehicles::checkVehicleclass($vendorId, $booking_class))
				{
					$error = "Oops! You have no cab matching this booking class";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}
				$chkOutStanding = VendorStats::frozenOutstanding($vendorId);
				if ($chkOutStanding > 1500)
				{
					$bModel->addError('bkg_id', "Oops! Your payment is overdue. Please settle your Gozo accounts.");
					$error = $bModel->getErrors();
					throw new Exception(json_encode($error), 1);
				}
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param BookingCab $cabModel
	 * @param \Beans\booking\BidAction $bidActionObj
	 * @return type
	 */
	public static function processGNowAcceptBidding($cabModel, $bidActionObj, $vendorId)
	{
		$returnSet = new ReturnSet();

		BookingCab::validateGNowTripForBidding($cabModel, $bidActionObj, $vendorId);
		BookingCab::validateGNowDriverCabForBidding($cabModel, $bidActionObj, $vendorId);

		$bidAmount = ceil($bidActionObj->amount);

		$reachMinutes	 = $bidActionObj->reachingAfterMinutes;
		$dnow			 = Filter::getDBDateTime();

		$reachingAT = date('Y-m-d H:i:s', strtotime($dnow . '+' . $reachMinutes . ' MINUTE'));

		//	$vendorId		 = UserInfo::getEntityId();
		$driverId		 = $bidActionObj->getDriverId();
		$cabId			 = $bidActionObj->getCabId();
		$driverMobile	 = $bidActionObj->getDriverMobile();
		$tripId			 = $bidActionObj->tripId;

		$bModels = $cabModel->bookings;
		$bkgId	 = $bModels[0]->bkg_id;

		try
		{
			$params = [
				'tripId'			 => $tripId,
				'bkgId'				 => $bkgId,
				'bidAmount'			 => $bidAmount,
				'isAccept'			 => true,
				'driverId'			 => $driverId,
				'driverMobile'		 => $driverMobile,
				'cabId'				 => $cabId,
				'reachingAtMinutes'	 => $reachMinutes,
				'reachingAtTime'	 => $reachingAT
			];

			//$success = BookingVendorRequest::storeGNowRequest($params, $vendorId);

			$bidModel = BookingVendorRequest::storeGNowRequest($params, $vendorId);

			//if ($success)
			if ($bidModel->bvr_id > 0)
			{

				$drvCntId		 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
				$drvCntDetails	 = Contact::getContactDetails($drvCntId);
				$driverName		 = $drvCntDetails['ctt_first_name'] . ' ' . $drvCntDetails['ctt_last_name'];
				if (empty(trim($driverName)))
				{
					$drvDetails	 = Drivers::getDriverInfo($driverId);
					$driverName	 = $drvDetails['drv_name'];
				}
				$cabDetails	 = Vehicles::getDetailbyid($cabId);
				$cabNumber	 = $cabDetails['vhc_number'];
				$desc		 = "Vendor offer received: Bid amount = &#x20B9;$bidAmount, reaching at = $reachingAT, cab number = $cabNumber, driver name = $driverName ($driverMobile)";
				BookingLog::model()->createLog($bkgId, $desc, UserInfo::getInstance(), BookingLog::BID_SET, false);

				BookingTrail::notifyConsumerForMissedNewGnowOffers($bkgId);
				$emailObj	 = new emailWrapper();
				$emailResult = $emailObj->mailGnowOfferReceived($bkgId);
				notificationWrapper::customerNotifyBookingForGNow($bidModel);

				$returnSet->setStatus(true);
				$returnSet->setMessage("Request processed successfully");
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param BookingCab $cabModel
	 * @param \Beans\booking\BidAction $bidActionObj
	 * @return type
	 */
	public static function processGNowDenyBidding($cabModel, $bidActionObj, $vendorId)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			/** @var BookingCab $cabModel */
			/** @var Booking $bModels */
			$bModels	 = $cabModel->bookings;
			$bkgId		 = $bModels[0]->bkg_id;
			/** @var \Beans\booking\BidAction $bidActionObj */
			$tripId		 = $bidActionObj->tripId;
			$reasonId	 = $bidActionObj->getDenyReasonId();
			$params		 = [
				'tripId'	 => $tripId,
				'bkgId'		 => $bkgId,
				'reasonId'	 => $reasonId,
				'isAccept'	 => false
			];
			//$vendorId	 = UserInfo::getEntityId();
			Logger::trace('<===process Request===>' . CJSON::encode($params));
			$bvrModels	 = BookingVendorRequest::storeGNowRequest($params, $vendorId);

			if ($bvrModels)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Request processed successfully");
			}

			$ntlId = NotificationLog::getIdForGozonow($vendorId, $tripId);
			if ($ntlId > 0)
			{
				$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
				$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
			}
			$userInfo = UserInfo::getInstance();
			Logger::trace('<===response===>' . CJSON::encode($returnSet));
			DBUtil::commitTransaction($transaction);

			$reasonDesc	 = BookingSub::getGNowBidDenyReasonList($reasonId);
			$desc		 = "Vendor denied offer. Reason: $reasonDesc";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_DENY, false);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param type $tripId
	 * @param type $reason
	 * @param type $reasonId
	 * @param type $bType
	 * @return boolean
	 * @throws Exception
	 */
	public static function unassignDCO($tripId, $reason, $reasonId)
	{
		$bType = [3, 5];
		if (empty($tripId))
		{
			$msg = "Invalid Trip Id !!!";
			throw new Exception(json_encode($msg), ReturnSet::ERROR_VALIDATION);
		}

		$tripModel	 = BookingCab::model()->findByPk($tripId);
		$bkgData	 = BookingCab::getFirstBkgByTripId($tripId, $bType);
		if (empty($tripModel) || empty($bkgData))
		{
			$msg = "Invalid Trip !!!";
			throw new Exception(json_encode($msg), ReturnSet::ERROR_VALIDATION);
		}

		$bkgId			 = $bkgData['bkg_id'];
		$pickupTimeDiff	 = $bkgData['pickupTimeDiff'];
		$bkgModel		 = Booking::model()->findByPk($bkgId);
		if ($pickupTimeDiff <= 30)
		{
			$msg = "FAIL!! Cannot reject at last minute. Contact support.";
			throw new Exception(json_encode($msg), ReturnSet::ERROR_VALIDATION);
		}
		$result = Booking::model()->canVendor($tripId, $reason, UserInfo::getInstance(), [], $reasonId);
		if (!$result['success'])
		{
			$msg = $result['errors'];
			throw new Exception(json_encode($msg), ReturnSet::ERROR_VALIDATION);
		}

		if ($pickupTimeDiff <= 120)
		{
			// Booking auto cancel flag
			$bkgModel->bkgPref->bkg_autocancel = 1;
			$bkgModel->bkgPref->save();
		}

		$tripModel->bcb_denied_reason_id = $reasonId;
		if (!$tripModel->save())
		{
			$msg = "Failed to update BookingCab Model";
			throw new Exception(json_encode($msg), ReturnSet::ERROR_VALIDATION);
		}

		VendorProfile::addCancelAttr($bkgModel, $tripModel, $reason);
		return true;
	}

	public static function checkCabDriverBeforeAssignment($tripId, $vehicleId, $driverId, $drvcontactNumber)
	{
		$tripModel = BookingCab::model()->findByPk($tripId);
		if (empty($tripModel))
		{
			throw new Exception(json_encode("Invalid Trip"), ReturnSet::ERROR_VALIDATION);
		}
		$bkgObj		 = $tripModel->bookings[0];
		$pickupdt	 = $bkgObj->bkg_pickup_date;
		$dateDiff	 = ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($pickupdt)) / 60);
		if ($dateDiff >= 0)
		{
			throw new Exception(json_encode("Pickup time has already passed. Please contact support."), ReturnSet::ERROR_VALIDATION);
		}

		$vndId = UserInfo::getEntityId();
		if ($vndId != $tripModel->bcb_vendor_id)
		{
			throw new Exception(json_encode("Sorry, unable to assign."), ReturnSet::ERROR_VALIDATION);
		}

		$tripModel->bcb_cab_id		 = $vehicleId;
		$tripModel->bcb_driver_phone = $drvcontactNumber;
		$tripModel->bcb_driver_id	 = $driverId;
		$tripModel->event_by		 = 2;

		$typeId			 = $bkgObj->bkg_vehicle_type_id;
		$cabTypeModel	 = SvcClassVhcCat::model()->findByPk($typeId);
		$modelVehicles	 = Vehicles::model()->findByPk($tripModel->bcb_cab_id);
		$modelDriver	 = Drivers::model()->findByPk($tripModel->bcb_driver_id);

		if ($modelVehicles->vhc_approved == 3)
		{
			throw new Exception(json_encode("Cab not approved. Cannot assign."), ReturnSet::ERROR_VALIDATION);
		}
		if ($modelDriver->drv_approved == 3)
		{
			throw new Exception(json_encode("Driver not approved. Cannot assign."), ReturnSet::ERROR_VALIDATION);
		}

		$isCng			 = $modelVehicles->vhc_has_cng;
		#$hasRooftopCarrier	 = $modelVehicles->vhc_has_rooftop_carrier;
		$cabType		 = $cabTypeModel->scv_vct_id;
		$sccClass		 = $cabTypeModel->scv_scc_id;
		$isCngAllowed	 = $bkgObj->bkgPref->bkg_cng_allowed;

		if ($isCngAllowed == 0)
		{
			if ($sccClass == 2 && $isCng == 1)
			{
				throw new Exception(json_encode("CNG cab not allowed for this booking."), ReturnSet::ERROR_VALIDATION);
			}
		}

		return [$tripModel, $cabType];
	}

	/**
	 * 
	 * @param array $tripDetailAll
	 * @param array $bookingTypeArr
	 * @return int
	 */
	public static function processPendingBulkNotifications($tripId, $sendSMS = true, $excludeNotified = true, $entityIds = [])
	{

		/**
		 *  @var BookingCab $bcbmodel 
		 *  @var Booking $model 
		 * 	@var Booking $toBkgModel 
		 */
		$returnSet	 = new ReturnSet();
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$bkgCount	 = count($bcbmodel->bookings);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);
		if ($model->bkg_status != 2)
		{
			$message = 'Cannot send notification at this booking status';
			$returnSet->setMessage($message);
			goto endOfProcessing;
		}

		$toBkgModel = $model;

		$tripType = $model->getBookingType($model->bkg_booking_type);
		if ($bkgCount > 1 && $bcbmodel->bcb_trip_type == 1)
		{
			#Arranging bookings according to their pickups
			if ($bcbmodel->bookings[0]->bkg_pickup_date < $bcbmodel->bookings[$bkgCount - 1]->bkg_pickup_date)
			{
				$toBkgModel = Booking::model()->findByPk($bcbmodel->bookings[$bkgCount - 1]->bkg_id);
			}
			else
			{
				$model = Booking::model()->findByPk($bcbmodel->bookings[$bkgCount - 1]->bkg_id);
			}
			$tripType = 'Matched Trip';
		}

		$bkgId			 = $model->bkg_id;
		$vehicleTypeId	 = $model->bkg_vehicle_type_id;

		$bookingId	 = $model->bkg_booking_id;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;

		$bkgFromCity	 = $model->bkg_from_city_id;
		$fromCityName	 = $model->bkgFromCity->cty_name;

		$bkgToCity	 = $toBkgModel->bkg_to_city_id;
		$toCityName	 = $toBkgModel->bkgToCity->cty_name;

		$datePickupDate		 = new DateTime($model->bkg_pickup_date);
		$pickupTime			 = $datePickupDate->format('j/F/Y g:i A');
		$tripVendorAmount	 = $bcbmodel->bcb_vendor_amount;
		$onlyGozoNow		 = true;

		$notifiedVendorList = [];
		if ($excludeNotified)
		{
			$appliedVendors		 = BookingVendorRequest::getAppliedVendors($tripId);
			$notifiedVendorList	 = explode(',', $appliedVendors); //Notified vendor id array
		}

		if (empty($entityIds))
		{
			$fromZone	 = ZoneCities::getZonesByCity($bkgFromCity);
			$toZone		 = ZoneCities::getZonesByCity($bkgToCity);

			$mergeZoneIDs = trim($fromZone . ',' . $toZone, ',');

			$distance		 = 200;
			$acceptedZones	 = Zones::getServiceZoneList($mergeZoneIDs, $distance);

			$vIdStr = Vendors::getListByZoneIds($acceptedZones, 12);

			$vIdList = explode(',', $vIdStr); //Eligible vendor id list in array
		}
		else
		{
			$vIdList = $entityIds;
		}

		$totAppNotified	 = 0;
		$vndArr			 = array_diff($vIdList, $notifiedVendorList); //Filtering out vendors whom notification sent
		if (empty($vndArr))
		{
			goto skipNotification;
		}
		$vIds = implode(',', $vndArr); //Vendor id list in comma separated string whom no notification sent

		$hourDuration	 = 120;
		$vehicleTypeZero = 0; //Dont want to specify cab type
		$vndData		 = Vendors::getGroupListByCabType($vehicleTypeZero, $vIds, $onlyGozoNow, $hourDuration); //Final vnd data 

		$countVnd = $vndData['cntVnd']; //vendor count

		if ($countVnd > 0)
		{
			$totAppNotified = BookingCab::sendBulkGNowNotification($model, $vndData, true); //Send Bulk notification
		}
		skipNotification:
		###now send sms to offline vendors

		$totSmS				 = 0;
		$totSourceHomeZone	 = 0;
		$totDestHomeZone	 = 0;
		$totAcceptedZone	 = 0;
		$totVendorFound		 = $totAppNotified;
		$totVendorsNotified	 = $totAppNotified;
		if (!$sendSMS)
		{// if tripFlag=-1 then we will not send sms to vendor for gozo now
			goto skipSMS;
		}
		$bcbhash = Yii::app()->shortHash->hash($tripId);

		$cabTypeList = Vehicles::getVhcTypeFromScv($vehicleTypeId);
		$vendors	 = Vendors::getByPickupCitynCabType($bkgFromCity, $bkgToCity, $cabTypeList, $appliedVendors);

		$totVendorFound = $vendors->getRowCount() | 0;

		Logger::trace($tripId . " getByPickupCitynCabType : " . $totVendorFound . " records");
		foreach ($vendors as $vendor)
		{
			$succSent	 = 0;
			$vndId		 = $vendor['vnd_id'];
			$vndhash	 = Yii::app()->shortHash->hash($vndId);
			$bkvnUrl	 = "https://gozo.cab/bkvn1/{$bcbhash}/{$vndhash}";
			$success	 = false;
			$message	 = "Cab required ($cabType): $fromCityName - $toCityName ($tripType) $pickupTime Amount: $tripVendorAmount Please use your Gozo Partner App to accept the bid gozo.cab/bkvn1/$bcbhash/$vndhash";
			$isLoggedIn	 = AppTokens::isVendorLoggedIn($vndId, 72);
			if (!$isLoggedIn)
			{
				if ($model->bkgPref->bkg_is_gozonow != 1)
				{
					$phnumber	 = '';
					$code		 = '';
					$vndPhone	 = $vendor['vnd_phone'];
					Filter::parsePhoneNumber($vndPhone, $code, $phnumber);
					if ($phnumber > 0)
					{
						$templateName	 = 'bid_for_new_booking_notification_to_partner_with_stop_reminder_v2';
						$lang			 = 'en_US';
						$arrWhatsAppData = [$cabType, $fromCityName, $toCityName, $pickupTime, Filter::moneyFormatter($tripVendorAmount), $tripType, $bkvnUrl];
						$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_TRIP, 'ref_id' => $tripId];
						$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
						$templateId		 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
						$arrButton		 = Whatsapp::buildComponentButton([$templateId], 'button', 'quick_reply', "payload");
						$unsubscribe	 = UnsubscribePhoneno::checkBlockedNumber(($code . $vndPhone), 2, $templateId);
						if ($totSmS <= 30)
						{
							if ($unsubscribe == 0)
							{
								$response	 = WhatsappLog::send(($code . $phnumber), $templateName, $arrDBData, $arrBody, $arrButton, $lang);
								$success	 = ($response['status'] == 2 && $response['wamId'] != null) ? true : false;
								if ($success)
								{
									$row = ["initiateBy" => WhatsappInitiateTrack::INITIATE_BY_GOZO, "initiateType" => WhatsappInitiateTrack::INITIATE_TYPE_UTILITY, "templateId" => $templateId, "phoneNumber" => ($code . $vndPhone)];
									WhatsappInitiateTrack::add($row);
									WhatsappInitiateTrack::updateStatus($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
								}
								else
								{
									$msgCom = new smsWrapper();
									$msgCom->sendSMStoVendors($phnumber, $message, 0, $bookingId);
								}
							}
							else if ($unsubscribe > 0)
							{
								$msgCom = new smsWrapper();
								$msgCom->sendSMStoVendors($phnumber, $message, 0, $bookingId);
							}
						}
						else if ($totSmS > 30 && $unsubscribe == 0)
						{
							$isInitiateAlive = WhatsappInitiateTrack::isInitiateAlive(WhatsappInitiateTrack::INITIATE_BY_GOZO, WhatsappInitiateTrack::INITIATE_TYPE_UTILITY, ($code . $vndPhone));
							if ($isInitiateAlive)
							{
								$response	 = WhatsappLog::send(($code . $vndPhone), $templateName, $arrDBData, $arrBody, $arrButton, $lang);
								$success	 = ($response['status'] == 2 && $response['wamId'] != null) ? true : false;
								if ($success)
								{
									$row = ["initiateBy" => WhatsappInitiateTrack::INITIATE_BY_GOZO, "initiateType" => WhatsappInitiateTrack::INITIATE_TYPE_UTILITY, "templateId" => $templateId, "phoneNumber" => ($code . $vndPhone)];
									WhatsappInitiateTrack::add($row);
									WhatsappInitiateTrack::updateStatus($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
								}
							}
						}

						$totSmS++;
						$totVendorsNotified++;
						$succSent = 1;
					}
				}
			}
			switch ($vendor['isHomeZone'])
			{
				case 1:

					$totSourceHomeZone++;
					break;
				case 2:
					$totDestHomeZone++;
					break;

				default:
					$totAcceptedZone++;
					break;
			}

			Logger::trace($message);
		}

		skipSMS:
		$dbDateTime	 = Filter::getDBDateTime();
		$logArr		 = [
			'totSmS'				 => $totSmS,
			'totAppNotified'		 => $totAppNotified,
			'totSourceHomeZone'		 => $totSourceHomeZone,
			'totDestHomeZone'		 => $totDestHomeZone,
			'totAcceptedZone'		 => $totAcceptedZone,
			'totalVendorsNotified'	 => $totVendorsNotified,
			'totalVendorFound'		 => $totVendorFound,
			'notifiedDateTime'		 => $dbDateTime
		];

		$notifyVendor		 = new \Stub\booking\NotifyVendor();
		$notifyVendor->setData($logArr);
		$notificationInfo	 = json_encode($notifyVendor);

		Logger::writeToConsole($tripId . ': ' . $notificationInfo);
		Logger::trace($notificationInfo);
		try
		{
			$res = BookingCab::updatePendingVendorNotificationInfo($tripId, $notificationInfo);
		}
		catch (Exception $exc)
		{
			Logger::exception($exc);
		}

		$cntRow		 = BookingPref::activateManualGozonow($bkgId);
		$userInfo	 = UserInfo::model();
		if ($cntRow)
		{
			$descStr = "GozoNOW auto-activated";
			BookingLog::model()->createLog($bkgId, $descStr, $userInfo, BookingLog:: ACTIVATE_GOZO_NOW, false);
		}

		if ($model->bkgTrail->btr_nmi_flag == 0 && $totVendorsNotified < 5)
		{
			$oldTrailModel					 = clone $model->bkgTrail;
			$nmidesc						 = "WHY - Not enough vendors found";
			$model->bkgTrail->btr_nmi_flag	 = 1;
			$model->bkgTrail->updateNMI($nmidesc, $oldTrailModel, $userInfo);
		}
		$returnSet->setStatus(true);
		$returnSet->setData($notificationInfo);
		endOfProcessing:
		return $returnSet;
	}

	/**
	 * 
	 * @param \Booking $model
	 * @param array $vndData
	 * @return type
	 */
	public static function sendBulkGNowNotification($model, $vndData, $returnCountOnly = false)
	{
		$tripId			 = $model->bkg_bcb_id;
		$bkgId			 = $model->bkg_id;
		$vndList		 = $vndData['vndIds']; //vendor list in comma separated string
//		$vndTokenList	 = $vndData['aptTokens']; //Token list in comma separated string
		$vndTokenList	 = $vndData['aptTokenVendor'];
		$dcoTokenList	 = $vndData['aptTokenDCO'];

		$vndIdList	 = $vndData['entIdVendor']; //vendor list in comma separated string
		$dcoIdList	 = $vndData['entIdDCO']; //vendor list in comma separated string

		$countVnd = $vndData['cntVnd']; //vendor count

		$countVndToken	 = $vndData['cntVndToken']; //vendor token count
		$countDCOToken	 = $vndData['cntDCOToken']; //DCO token count
		$rndHash		 = date('ymdHis');
		$batchId		 = "{$tripId}{$bkgId}{$rndHash}";
		$cntSent		 = 0;
		$cntSucc		 = 0;
		if ($countVnd > 0)
		{
			$cntVndSucc	 = 0;
			$cntDCOSucc	 = 0;

			if ($countVndToken > 0)
			{

				$resVndArr		 = AppTokens::notifyMulti($model, $vndTokenList, $batchId);
				$cntVndSucc		 = ($resVndArr['success'] > 0) ? $resVndArr['success'] : 0;
				$notification	 = $resVndArr['notification'];
				$vndLogArrList	 = explode(',', $vndIdList);
				NotificationLog::createVendorMultiLog($vndLogArrList, $notification, $batchId);
			}
			if ($countDCOToken > 0)
			{
				$resDCOArr		 = AppTokens::notifyMultiNew($model, $dcoTokenList, $batchId);
				$cntDCOSucc		 = ($resDCOArr['success'] > 0) ? $resDCOArr['success'] : 0;
				$notification	 = $resDCOArr['notification'];
				$dcoLogArrList	 = explode(',', $dcoIdList);
				NotificationLog::createVendorMultiLog($dcoLogArrList, $notification, $batchId);
			}
			$cntSent = $countVnd;
			$cntSucc = $cntVndSucc + $cntDCOSucc;

			$params		 = [
				'tripId' => $tripId,
				'bkgId'	 => $bkgId,
			];
			$vndArrList	 = explode(',', $vndList);
			BookingVendorRequest::notifiedMultiGNowEntry($vndArrList, $params);
		}
		$gNowInitiateMode	 = '';
		$gNowStatus			 = $model->bkgPref->bkg_is_gozonow;
		$gNowModeArr		 = [1 => 'customer intiated', 2 => 'activated'];
		$gNowInitiateMode	 = $gNowModeArr[$gNowStatus];

		$desc		 = "Vendors notified(Bulk) for $gNowInitiateMode Gozo now : Sent=$cntSent, Success=$countVnd";
		$userInfo	 = UserInfo::model(UserInfo:: TYPE_SYSTEM, 0);
		BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: VENDOR_NOTIFIED_FOR_GOZONOW_BOOKING, false);

		if ($returnCountOnly)
		{
			return $cntSucc;
		}
		return $desc;
	}

	/**
	 * 
	 * @param type $tripId
	 * @param type $csrId
	 * @param type $csFlag
	 * @return boolean
	 * @throws Exception
	 */
	public static function updateAssignedCsr($tripId, $csrId = null, $csFlag)
	{
		$success = false;
		if ($csrId > 0)
		{
			$model						 = BookingCab::model()->findByPk($tripId);
			$model->bcb_assigned_csr	 = $csrId;
			$model->bcb_assigned_cs_flag = $csFlag;
			$result						 = $model->save();
			if (!$result)
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$success = true;
		}

		return $success;
	}

	/**
	 * modify can cancel type flag (lastminCancel=1 goof off=2)
	 * @param type $bcbId
	 * @param type $flag
	 */
	public static function updateVendorCanceltype($bcbId, $flag)
	{
		$bookingCabModel						 = BookingCab::model()->findByPk($bcbId);
		$bookingCabModel->bcb_vendor_cancel_type = $flag;
		$res									 = $bookingCabModel->save();
	}

	/**
	 * function use to check time difference between pickup and cancel
	 * @param type $pickup_date
	 * @param type $bcbid
	 */
	public static function lastminCancelFlagUpdate($pickupDate, $bcbId, $vendorId)
	{
		$flag					 = 0;
		date_default_timezone_set("Asia/Calcutta");
		$cancelTime				 = date('Y-m-d H:i:s');
		$pickupCancelDifference	 = round(abs(strtotime($pickupDate) - strtotime($cancelTime)) / 60, 2) . " minute";
		$assignTime				 = BookingVendorRequest::getAssignDate($bcbId, $vendorId);
		$assignCancelDifference	 = round(abs(strtotime($assignTime) - strtotime($cancelTime)) / 60, 2) . " minute";
		if ($pickupCancelDifference < 90 || $assignCancelDifference > 15)
		{
			$flag = 1;
			#BookingCab::updateVendorCanceltype($bcbId,$flag);
		}
		return $flag;
	}

	public function getCabAssignBkgId()
	{
		$pickupTime	 = $this->bookings[0]->bkg_pickup_date;
		$dropTime	 = date('Y-m-d H:i:s', strtotime($this->bookings[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
		foreach ($this->bookings as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$sql = "SELECT bkg.bkg_booking_id, bkg.bkg_pickup_date pickupDate,
                (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE) tripEndTime,
                bkg.bkg_trip_duration  FROM booking_cab bcb
              JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
              JOIN vehicles vhc ON vhc.vhc_id = bcb.bcb_cab_id
         WHERE bcb.bcb_cab_id IS NOT NULL AND bkg.bkg_status IN (5)
         AND bcb.bcb_cab_id = {$this->bcb_cab_id} AND bcb.bcb_id <> {$this->bcb_id}
         GROUP BY bcb.bcb_id;";

		$rows = DBUtil::query($sql);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if (($row['pickupDate'] <= $pickupTime && $row['tripEndTime'] >= $pickupTime ) ||
						($row['pickupDate'] <= $dropTime && $row['tripEndTime'] >= $dropTime ) ||
						($row['pickupDate'] >= $pickupTime && $row['tripEndTime'] <= $dropTime))
				{
					$bkgIds[] = $row['bkg_booking_id'];
				}
			}
		}
		return implode(',', $bkgIds);
	}

	public function getDriverAssignBkgId()
	{
		$pickupTime	 = $this->bookings[0]->bkg_pickup_date;
		$dropTime	 = date('Y-m-d H:i:s', strtotime($this->bookings[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
		foreach ($this->bookings as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$sql	 = "SELECT bkg.bkg_booking_id, bkg.bkg_pickup_date pickupDate,
			    (bkg.bkg_pickup_date + INTERVAL IFNULL(bkg.bkg_trip_duration, 0) MINUTE) tripEndTime,
			    bkg.bkg_trip_duration  FROM booking_cab bcb
				    JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
				    JOIN drivers drv ON drv.drv_id = bcb.bcb_driver_id
			    WHERE bcb.bcb_driver_id IS NOT NULL AND bkg.bkg_status IN (5)
			    AND bcb.bcb_driver_id={$this->bcb_driver_id} AND bcb.bcb_id <> {$this->bcb_id}
			    GROUP BY bcb.bcb_id;";
		$overLap = 0;
		$rows	 = DBUtil::query($sql); //DBUtil::queryAll($sql);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if (($row['pickupDate'] <= $pickupTime && $row['tripEndTime'] >= $pickupTime ) ||
						($row['pickupDate'] <= $dropTime && $row['tripEndTime'] >= $dropTime ) ||
						($row['pickupDate'] >= $pickupTime && $row['tripEndTime'] <= $dropTime))
				{
					$bkgIds[] = $row['bkg_booking_id'];
				}
			}
		}
		return implode(',', $bkgIds);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $drvId
	 * @return type
	 */
	public static function checkDriverBookingRelation($bkgId, $drvId)
	{
		$params = ['bkgId' => $bkgId, 'drvId' => $drvId];

		$sql = "SELECT  bkg.bkg_id 
					FROM booking_cab bcb
					INNER JOIN booking bkg ON bcb.bcb_id = bkg.bkg_bcb_id 					 
					WHERE bkg.bkg_id = :bkgId  					
					AND   bkg_status IN (5,6,7)   AND bcb_driver_id=:drvId  ";

		$data = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $data;
	}

	/**
	 * 
	 * @param int $trip (optional)
	 * @return CDbDataReader
	 */
	public static function getTripsForReadyToPickup($trip = '')
	{
		$where	 = '';
		$param	 = [];
		if ($trip > 0)
		{
			$param	 = ['tripId' => $trip];
			$where	 = " AND bkg.bkg_bcb_id=:tripId";
		}
		$sql = "SELECT bkg.bkg_bcb_id,bkg.bkg_pickup_date,bkg.bkg_status FROM booking bkg
				INNER JOIN booking_cab bcb ON bcb.bcb_id =bkg.bkg_bcb_id
				INNER JOIN booking_track btk ON btk.btk_bkg_id=bkg.bkg_id 
				INNER JOIN booking_trail btr ON btr.btr_bkg_id=bkg.bkg_id 
			WHERE btk.btk_vendor_pickup_confirm =0 AND btk.btk_driver_pickup_confirm = 0 AND
				(bkg.bkg_status=5 
					OR ( bkg.bkg_status=3 
						AND (btr.bkg_assigned_at IS NULL 
							OR btr.bkg_assigned_at < DATE_SUB(NOW(), INTERVAL 30 MINUTE)))) 
			 	AND bkg.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 120 MINUTE) 
				$where
			ORDER by bkg.bkg_pickup_date";
		return DBUtil::query($sql, DBUtil::SDB(), $param);
	}

	/**
	 * 
	 * @param int $trip (optional)
	 * @return CDbDataReader
	 */
	public static function notifyReadyToPickup($trip = '')
	{
		$dataSet = BookingCab::getTripsForReadyToPickup($trip);
		foreach ($dataSet as $data)
		{
			$tripId	 = $data['bkg_bcb_id'];
			$success = BookingCab::gnowWinBidNotify($tripId);
			echo "$tripId : sent. " . json_encode($success);
		}
	}

	/**
	 * 
	 * @param int $trip (optional)
	 * @return CDbDataReader
	 */
	public static function getTripsForDriverReadyToPickup($trip = '')
	{
		$where	 = '';
		$param	 = [];
		if ($trip > 0)
		{
			$param	 = ['tripId' => $trip];
			$where	 = " AND bkg.bkg_bcb_id=:tripId";
		}
		$sql = "SELECT bkg.bkg_bcb_id,bkg.bkg_pickup_date,bkg.bkg_status FROM booking bkg
				INNER JOIN booking_cab bcb ON bcb.bcb_id =bkg.bkg_bcb_id
				INNER JOIN booking_track btk ON btk.btk_bkg_id=bkg.bkg_id 
				INNER JOIN booking_trail btr ON btr.btr_bkg_id=bkg.bkg_id 
			WHERE btk.btk_vendor_pickup_confirm =0 AND btk.btk_driver_pickup_confirm = 0 AND 
				( bkg.bkg_status=5 
				AND (btr.bkg_assigned_at IS NULL 
					OR btr.bkg_assigned_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)))
			 	AND bkg.bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 135 MINUTE) 
				$where
			ORDER by bkg.bkg_pickup_date";
		return DBUtil::query($sql, DBUtil::SDB(), $param);
	}

	/**
	 * 
	 * @param int $trip (optional)
	 * @return CDbDataReader
	 */
	public static function notifyDriverReadyToPickup($trip = '')
	{
		$dataSet = BookingCab::getTripsForDriverReadyToPickup($trip);
		foreach ($dataSet as $data)
		{
			$tripId	 = $data['bkg_bcb_id'];
			$success = BookingCab::readyToGoDriverNotify($tripId);
			echo "$tripId : sent. " . json_encode($success);
		}
	}

	/**
	 * 

	 */
	public static function driverBidNotification($tripId, $entityIds = [])
	{

		/**
		 *  @var BookingCab $bcbmodel 
		 *  @var Booking $model 
		 * 	@var Booking $toBkgModel 
		 */
		$returnSet	 = new ReturnSet();
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$bkgCount	 = count($bcbmodel->bookings);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);
		if ($model->bkg_status != 2)
		{
			$message = 'Cannot send notification at this booking status';
			$returnSet->setMessage($message);
			goto endOfProcessing;
		}
		$toBkgModel = $model;

		$tripType = $model->getBookingType($model->bkg_booking_type);
		if ($bkgCount > 1 && $bcbmodel->bcb_trip_type == 1)
		{
			#Arranging bookings according to their pickups
			if ($bcbmodel->bookings[0]->bkg_pickup_date < $bcbmodel->bookings[$bkgCount - 1]->bkg_pickup_date)
			{
				$toBkgModel = Booking::model()->findByPk($bcbmodel->bookings[$bkgCount - 1]->bkg_id);
			}
			else
			{
				$model = Booking::model()->findByPk($bcbmodel->bookings[$bkgCount - 1]->bkg_id);
			}
			$tripType = 'Matched Trip';
		}

		$bkgId			 = $model->bkg_id;
		$vehicleTypeId	 = $model->bkg_vehicle_type_id;

		$bookingId	 = $model->bkg_booking_id;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;

		$bkgFromCity	 = $model->bkg_from_city_id;
		$fromCityName	 = $model->bkgFromCity->cty_name;

		$bkgToCity	 = $toBkgModel->bkg_to_city_id;
		$toCityName	 = $toBkgModel->bkgToCity->cty_name;

		$pickupTime			 = DateTimeFormat::SQLDateTimeToLocaleDateTime($model->bkg_pickup_date);
		$tripVendorAmount	 = $bcbmodel->bcb_vendor_amount;
		$onlyGozoNow		 = true;

		if (empty($entityIds))
		{
			$fromZone	 = ZoneCities::getZonesByCity($bkgFromCity);
			$toZone		 = ZoneCities::getZonesByCity($bkgToCity);

			$mergeZoneIDs = trim($fromZone . ',' . $toZone, ',');

			$distance		 = 200;
			$acceptedZones	 = Zones::getServiceZoneList($mergeZoneIDs, $distance);

			$drvIdStr = Drivers::getListByZoneIds($acceptedZones);

			$drvIdList = explode(',', $drvIdStr); //Eligible vendor id list in array
		}
		else
		{
			$drvIdList	 = $entityIds;
			$drvIdStr	 = implode(',', $drvIdList);
		}

		$totAppNotified = 0;

		$hourDuration = 120;

		$drvTokenData = Drivers::getTokenGroupListByIds($drvIdStr, $hourDuration);

		$countDrv = $drvTokenData['cntDrv']; //driver count
		if ($countDrv > 0)
		{
			$drvIdList = $drvTokenData['drvIds'];

			$rndHash		 = date('ymdHis');
			$batchId		 = "{$tripId}{$bkgId}{$rndHash}";
			$drvTokenList	 = $drvTokenData['aptTokens'];
			$resArr			 = AppTokens::notifyMulti($model, $drvTokenList, $batchId);

			$cntSent		 = $countDrv;
			$cntSucc		 = ($resArr['success'] > 0) ? $resArr['success'] : 0;
			$notification	 = $resArr['notification'];
			$params			 = [
				'tripId' => $tripId,
				'bkgId'	 => $bkgId,
			];
			$drvArrList		 = explode(',', $drvIdList);

			NotificationLog::createVendorMultiLog($drvArrList, $notification, $batchId);
		}
		$desc		 = "Drivers notified(Bulk headsup)  : Sent=$cntSent, Success=$cntSucc";
		$userInfo	 = UserInfo::model(UserInfo:: TYPE_SYSTEM, 0);
		BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: VENDOR_NOTIFIED_FOR_GOZONOW_BOOKING, false);

		$dbDateTime	 = Filter::getDBDateTime();
		$logArr		 = [
			'totAppNotified'	 => $cntSucc,
			'notifiedDateTime'	 => $dbDateTime
		];

		$returnSet->setStatus(true);
		$returnSet->setData($logArr);
		endOfProcessing:
		return $returnSet;
	}

	/**
	 * calculate last trip start date
	 * @param type $driverId
	 * @return type
	 */
	public static function showLastStartTime($driverId)
	{
		$lastdate	 = "";
		$sql		 = "SELECT bcb.bcb_id as lastTripId
				FROM booking_cab bcb 
				INNER JOIN booking bkg On bkg.bkg_bcb_id = bcb.bcb_id
				INNER JOIN booking_track btk ON btk.btk_bkg_id=bkg.bkg_id AND (btk.bkg_ride_start=1 OR btk.bkg_trip_arrive_time <> null)
				WHERE bcb.bcb_driver_id = :driverId ORDER BY bkg.bkg_pickup_date DESC LIMIT 0,1";
		$lastTripid	 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['driverId' => $driverId]);
		if ($lastTripid > 0)
		{
			$sql		 = "SELECT min(bkg.bkg_pickup_date) as date
				FROM booking bkg 
				INNER JOIN booking_cab bcb ON bcb.bcb_id= bkg.bkg_bcb_id
				WHERE bkg.bkg_bcb_id = :lastTripId";
			$lastdate	 = DBUtil::queryScalar($sql, DBUtil::SDB(), ['lastTripId' => $lastTripid]);
		}
		return $lastdate;
	}

	public static function getRouteNameListById($tripId)
	{
		$params		 = ['tripId' => $tripId];
		$sql		 = "SELECT bkg.bkg_route_city_names FROM booking bkg  
			WHERE bkg.bkg_bcb_id = :tripId AND bkg_status IN(1,15,2,3,5,6,7) ORDER BY bkg.bkg_pickup_date";
		$recordSet	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		$routes		 = [];
		foreach ($recordSet as $data)
		{
			$routes = array_merge($routes, json_decode($data['bkg_route_city_names']));
		}

		return $routes;
	}

	public static function getTotalTripDistanceById($tripId)
	{
		$params	 = ['tripId' => $tripId];
		$sql	 = "SELECT sum(bkg.bkg_trip_distance) totalDistance FROM booking bkg  
			WHERE bkg.bkg_bcb_id = :tripId AND bkg_status IN(1,15,2,3,5,6,7)  ";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $res;
	}

	public static function setReadyToGo($tripId, $vendorId)
	{
		$returnSet		 = new ReturnSet();
		$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
		if (!$isAccessible)
		{
			throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
		}
		$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
		if (isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
		{
			throw new Exception("Booking already assigned to other vendor", ReturnSet::ERROR_VALIDATION);
		}

		$bcabModel	 = BookingCab::model()->findByPk($tripId);
		$bModels	 = $bcabModel->bookings;
		$eventId	 = BookingLog::ONTHEWAY_FOR_PICKUP;
		$desc		 = "Driver is ready to go for pickup";
		$userInfo	 = UserInfo::getInstance();
		foreach ($bModels as $bookingModel)
		{
			BookingTrack::updateVendorReadyToPickupConfirmation($bookingModel->bkg_id, 1);
			BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
		}
		$returnSet->setStatus(true);
		$returnSet->setMessage('Thank You, Customer is waiting at pickup point');
		$ntlId = NotificationLog::getIdForGozonow($vendorId, $tripId);
		if ($ntlId > 0)
		{
			$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
			$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
		}
		return $returnSet;
	}

	public static function setGnowSomeProblemToGo($tripId, $vendorId)
	{
		$returnSet		 = new ReturnSet();
		$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
		if (!$isAccessible)
		{
			throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
		}
		$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
		if (isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
		{
			throw new Exception("Booking already assigned to other vendor", ReturnSet::ERROR_VALIDATION);
		}
		$bcabModel	 = BookingCab::model()->findByPk($tripId);
		$bModels	 = $bcabModel->bookings;

		foreach ($bModels as $bookingModel)
		{
			BookingTrack::updateVendorReadyToPickupConfirmation($bookingModel->bkg_id, 2);
		}
		$returnSet	 = ServiceCallQueue::autoFURGozoNow($tripId);
		$ntlId		 = NotificationLog::getIdForGozonow($vendorId, $tripId);
		if ($ntlId > 0)
		{
			$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
			$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
		}
		$result					 = $returnSet->getData();
		$result['isNewFollowup'] = true;
		$returnSet->setData($result);
		if ($returnSet->getStatus())
		{
			$returnSet->setMessage('Request for call back is generated.');
		}
		return $returnSet;
	}

	/**
	 * This function is used for validate condition of airport booking to increase MaxallowedVndAmnt
	 * @param int $bkgId
	 */
	public static function validateApBkgMaxAllowVndAmnt($bkgId)
	{
		//$bkgId = 1899467;
		$createFlag			 = 0;
		$validateMaxAllowed	 = 0;
		$model				 = Booking::model()->findByPk($bkgId);
		$bookingType		 = $model->bkg_booking_type;
		$bcbId				 = $model->bkg_bcb_id;
		$pickupTime			 = strtotime($model->bkg_pickup_date);
		$createDate			 = $model->bkg_create_date;
		$currentHour		 = date("H");
		$currentTime		 = time();

		if ($currentHour >= 7)
		{
			$startDate	 = new DateTime('today');
			$endDate	 = new DateTime('tomorrow');
		}
		else
		{
			$startDate	 = new DateTime('yesterday');
			$endDate	 = new DateTime('today');
		}

		$startTime	 = strtotime($startDate->format('Y-m-d') . ' 21:00');
		$endTime	 = strtotime($endDate->format('Y-m-d') . ' 07:00');

		if (time() - strtotime($createDate) > 3599)
		{
			$createFlag = 1;
		}


		if (($currentTime >= $startTime && $currentTime <= $endTime ) && ($bookingType == 4 || $bookingType == 12 ) && ($pickupTime >= $startTime && $pickupTime <= $endTime ) && ($createFlag == 1))
		{

			$validateMaxAllowed = 1;
		}
		return $validateMaxAllowed;
	}

	/**
	 * function use to show the list of vendor who still not assign cab or driver
	 * @return type
	 */
	public static function notifyVendorForAssignCabDriver()
	{
		$params = ['minutes' => 30];

		$sql = "SELECT bkg_id,bkg_booking_id,bkg_bcb_id,bcb_vendor_id,bcb_vendor_amount, bkg_assigned_at,bcb_assign_mode,bkg_pickup_date 
		FROM booking 
		INNER JOIN booking_cab ON booking_cab.bcb_id = booking.bkg_bcb_id 
		INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id 
		INNER JOIN booking_pref ON booking_pref.bpr_bkg_id = booking.bkg_id 
		WHERE 1 
		AND booking_cab.bcb_vendor_id > 0 
		AND booking.bkg_status IN (3) 
		AND (
		  booking_trail.bkg_assigned_at <= SubWorkingMinutes(30, NOW()) 
		  OR (
			bkg_assigned_at < DATE_SUB(NOW(), INTERVAL 30 MINUTE) 
			AND bkg_pickup_date < addWorkingMinutes(30, NOW())
		  )
		) 
		AND bkg_pickup_date < addWorkingMinutes(
		  CASE WHEN CalcWorkingMinutes(
			booking_trail.bkg_assigned_at, booking.bkg_pickup_date
		  )> 720 THEN 240 WHEN CalcWorkingMinutes(
			booking_trail.bkg_assigned_at, booking.bkg_pickup_date
		  )> 480 THEN 180 WHEN CalcWorkingMinutes(
			booking_trail.bkg_assigned_at, booking.bkg_pickup_date
		  )> 240 THEN 120 WHEN CalcWorkingMinutes(
			booking_trail.bkg_assigned_at, booking.bkg_pickup_date
		  )< 240 THEN 60 ELSE 60 END, 
		  booking_trail.bkg_assigned_at	)";

		return DBUtil::query($sql, DBUtil::SDB(), $params);
	}

	/**
	 * 
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function assignmentReport($model, $type = DBUtil::ReturnType_Provider)
	{
		$sqlStates		 = $sqlBookingType	 = $sqlServiceClass = '';

		$sql					 = "SELECT
					bcb_id AS 'trip_id',
					bkg_id AS 'booking_ids',
					bkg_create_date AS 'create_date', 
					bkg_pickup_date AS 'pickup_date', 
					IF(
					btr_is_bid_started = 1,
					btr_bid_start_time,
					0
				) AS 'bid_start_date', 
				bcb_cab_assignmenttime AS 'Driver_Cab_Assigned_Date', 
					bkg_assigned_at AS 'Vendor_Assigned_Date',
					bkg_critical_score AS 'Critical Score', IF(
					bkg_manual_assignment = 1,
					btr_manual_assign_date,
					'NA'
				) AS 'manual_assign_date', 
					IF(
					bkg_critical_assignment = 1,
					btr_critical_assign_date,
					'NA'
				) AS 'critical_assign_date', 
				IF(btr_is_dem_sup_misfire = 1,'ON','OFF'
				) AS 'demSup_misfire',
				IF(bkg_reconfirm_flag = 1, 'YES', 'NO') AS 'reconfirm',
				bkg_vendor_amount AS 'booking_vendor_amount',
				bcb_vendor_amount AS 'trip_vendor_amount',
				bkg_advance_amount AS 'booking_advanced_amount',
				bkg_total_amount AS 'booking_total_amount',
				bkg_gozo_amount AS 'gozo_amount',
				ROUND(temp.avgBid, 2) AS 'avg_bid_amount',
				ROUND(temp.maxBid, 2) AS 'max_bid_amount',
				ROUND(temp.minBid, 2) AS 'min_bid_amount',
				temp.bidCount AS 'bid_count',
				svc_class_vhc_cat.scv_label AS 'scv_label',
				CASE WHEN bcb_assign_mode = 0 THEN 'AUTO' WHEN bcb_assign_mode = 1 THEN 'MANUAL' WHEN bcb_assign_mode = 2 THEN 'DIRECT ACCEPT' ELSE '-'
				END AS 'assigned_mode',
				bkg_booking_type,
				states.stt_zone
				FROM `booking_cab`
				INNER JOIN `booking` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1
				INNER JOIN `cities` ON cities.cty_id = booking.bkg_from_city_id
				INNER JOIN `states` ON states.stt_id = cities.cty_state_id
				INNER JOIN `svc_class_vhc_cat` ON svc_class_vhc_cat.scv_id = booking.bkg_vehicle_type_id
				INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id = booking.bkg_id
				INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN(
					SELECT bvr_bcb_id,
						AVG(bvr_bid_amount) AS avgBid,
						MAX(bvr_bid_amount) AS maxBid,
						MIN(bvr_bid_amount) AS minBid,
						COUNT(bvr_vendor_id) AS bidCount
					FROM
						`booking_vendor_request`
					WHERE
						1 AND bvr_accepted = 1
					GROUP BY
						bvr_bcb_id
				) temp
				ON
					temp.bvr_bcb_id = booking_cab.bcb_id
				WHERE
					booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2";
		$params["pickupDate1"]	 = $model->bkg_pickup_date1;
		$params["pickupDate2"]	 = $model->bkg_pickup_date2;
		if ($model->bkg_create_date1 != '' && $model->bkg_create_date2 != '')
		{
			$sql					 .= " AND booking.bkg_create_date BETWEEN :createDate1 AND :createDate2";
			$params["createDate1"]	 = $model->bkg_create_date1;
			$params["createDate2"]	 = $model->bkg_create_date2;
		}
		if ($model->bkg_assigned_date1 != '' && $model->bkg_assigned_date2 != '')
		{
			$sql					 .= " AND booking_trail.bkg_assigned_at BETWEEN :assignedDate1 AND :assignedDate2";
			$params["assignedDate1"] = $model->bkg_assigned_date1;
			$params["assignedDate2"] = $model->bkg_assigned_date2;
		}
		if ($model->bkg_region != '')
		{
			$paramsRegion	 = DBUtil::getINStatement($model->bkg_region, $bindString1, $paramsRegion);
			$sql			 .= " AND states.stt_zone IN ($bindString1)";
			$params			 = $params + $paramsRegion;
		}
		if ($model->bkg_booking_type != '')
		{
			$paramsBookingType	 = DBUtil::getINStatement($model->bkg_booking_type, $bindString2, $paramsBookingType);
			$sql				 .= " AND booking.bkg_booking_type IN ($bindString2)";
			$params				 = $params + $paramsBookingType;
		}
		if ($model->bkg_service_class != '')
		{
			$paramsServiceClass	 = DBUtil::getINStatement($model->bkg_service_class, $bindString3, $paramsServiceClass);
			$sql				 .= " AND svc_class_vhc_cat.scv_scc_id IN ($bindString3)";
			$params				 = $params + $paramsServiceClass;
		}
		$sql .= " GROUP BY booking_cab.bcb_id";
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp ", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				"params"		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['trip_id', 'booking_ids', 'create_date', 'pickup_date', 'Driver_Cab_Assigned_Date', 'Vendor_Assigned_Date', 'booking_vendor_amount', 'trip_vendor_amount', 'booking_advanced_amount', 'booking_total_amount', 'gozo_amount', 'avg_bid_amount', 'max_bid_amount', 'min_bid_amount'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	/**
	 * @param Booking $model
	 * @param integer $type
	 * @return \CSqlDataProvider
	 */
	public static function manualReport($data, $type = DBUtil::ReturnType_Provider)
	{

		$sql					 = "SELECT
					bcb_id,
					bkg_id,
					bkg_create_date,
					bkg_pickup_date,
					vendors.vnd_name,
					vendors.vnd_id,
					drivers.drv_name,
					drivers.drv_id,
					vehicles.vhc_number,
					vehicles.vhc_id,
					bid_count,
					booking_invoice.bkg_gozo_amount,
					booking_trail.bkg_assign_csr,
					booking_trail.bkg_assigned_at,
					CONCAT(admins.adm_fname,' ',admins.adm_lname) as assign_csr
				FROM `booking_cab`
				INNER JOIN `booking` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1
				INNER JOIN `booking_pref` ON booking_pref.bpr_bkg_id = booking.bkg_id
				INNER JOIN `booking_trail` ON booking_trail.btr_bkg_id = booking.bkg_id
				INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id = booking.bkg_id
				INNER JOIN `vendors` ON vendors.vnd_id = booking_cab.bcb_vendor_id
				INNER JOIN `vehicles` ON vehicles.vhc_id = booking_cab.bcb_cab_id
				INNER JOIN `drivers` ON drivers.drv_id = booking_cab.bcb_driver_id 
				LEFT JOIN `admins` ON admins.adm_id=booking_trail.bkg_assign_csr
				INNER JOIN(
					SELECT bvr_bcb_id,
						COUNT(bvr_vendor_id) AS bid_count
					FROM
						`booking_vendor_request`
					WHERE
						1 AND bvr_accepted = 1
					GROUP BY
						bvr_bcb_id
				) temp
				ON
					temp.bvr_bcb_id = booking_cab.bcb_id
				WHERE booking.bkg_status IN(3, 5, 6, 7, 9) AND booking.bkg_pickup_date BETWEEN :pickupDate1 AND :pickupDate2";
		$params["pickupDate1"]	 = $data['bkg_pickup_date1'];
		$params["pickupDate2"]	 = $data['bkg_pickup_date2'];
		if ($data['bkg_assigned_date1'] != '' && $data['bkg_assigned_date2'] != '')
		{
			$sql					 .= " AND booking_trail.bkg_assigned_at BETWEEN :assignedDate1 AND :assignedDate2";
			$params["assignedDate1"] = $data['bkg_assigned_date1'];
			$params["assignedDate2"] = $data['bkg_assigned_date2'];
		}

		if ($data['is_Assigned'] != null)
		{
			if ($data['is_Assigned'] == '0')
			{
				$sql .= " AND (booking_cab.bcb_assign_mode IN (0))";
			}
			else if ($data['is_Assigned'] == '1')
			{
				$sql .= " AND (booking_cab.bcb_assign_mode IN (1))";
			}
		}

		if ($data['is_Manual'] > 0)
		{
			$sql .= " AND booking_pref.bkg_manual_assignment='1'";
		}

		if ($data['is_Critical'] > 0)
		{
			$sql .= " AND booking_pref.bkg_critical_assignment='1'";
		}

		if ($data['bkg_admin_id'] > 0)
		{
			$sql .= " AND booking_trail.bkg_assign_csr=" . $data['bkg_admin_id'];
		}

		$searchTags = $data['search_tags'];
		if (count($searchTags) > 0 && $searchTags != '')
		{
			$arr = [];
			foreach ($searchTags as $tags)
			{
				$arr[] = "FIND_IN_SET($tags,REPLACE(bkg_tags,' ',''))";
			}
			$search2[] = "(" . implode(' OR ', $arr) . ")";

			$sql .= " AND " . implode(" AND ", $search2);
		}

		$sql .= " GROUP BY booking_cab.bcb_id";
		if ($type == DBUtil::ReturnType_Provider)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql ) temp ", DBUtil::SDB3(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				"params"		 => $params,
				'db'			 => DBUtil::SDB3(),
				'sort'			 => ['attributes' => ['bcb_id', 'bkg_id', 'bkg_create_date', 'bkg_pickup_date', 'bkg_assigned_at', 'bkg_gozo_amount', 'bid_count'], 'defaultOrder' => ''],
				'pagination'	 => ['pageSize' => 100],
			]);
			return $dataprovider;
		}
		else
		{
			return DBUtil::query($sql, DBUtil::SDB3(), $params);
		}
	}

	public function logFailedVendorAssignment($errorDesc, $userInfo = null, $vendorId = null, $bidAmount = null)
	{
		$bModels = $this->bookings;
		$eventId = BookingLog::VENDOR_ASSIGNMENT_FAILED;
		$desc	 .= "Vendor assignment failed. Reason: $errorDesc";
		if ($bidAmount > 0)
		{
			$desc .= "Bid amount: $bidAmount";
		}

		foreach ($bModels as $bookingModel)
		{
			$totalnumber = BookingLog::model()->checkErrorExistance($bookingModel->bkg_id, $vendorId, $eventId);

			if ($totalnumber < 1)
			{
				BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId, false, $params);
			}
		}
	}

	public function logFailedCabDriverAssignment($errorDesc, $userInfo = null)
	{
		$bModels = $this->bookings;
		$eventId = BookingLog::CAB_DRIVER_ASSIGNMENT_FAILED;
		$desc	 = "Cab / driver assignment failed. Reason: $errorDesc";
		foreach ($bModels as $bookingModel)
		{
			BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId);
		}
	}

	public static function getBkgIdsById($bcbid)
	{
		$param	 = ['bcbId' => $bcbid];
		$sql	 = "SELECT bcb_bkg_id1 from booking_cab WHERE bcb_id = :bcbId";
		$data	 = DBUtil::queryScalar($sql, null, $param);
		return $data;
	}

	public static function gnowNotifyNotLoggedin($tripId, $entityIds = [])
	{

		/**
		 *  @var BookingCab $bcbmodel 
		 *  @var Booking $model 
		 * 	@var Booking $toBkgModel 
		 */
		$returnSet	 = new ReturnSet();
		$bcbmodel	 = BookingCab::model()->findByPk($tripId);
		$bkgCount	 = count($bcbmodel->bookings);
		$model		 = Booking::model()->findByPk($bcbmodel->bookings[0]->bkg_id);
		if ($model->bkg_status != 2)
		{
			$message = 'Cannot send notification at this booking status';
			$returnSet->setMessage($message);
			goto endOfProcessing;
		}

		$toBkgModel	 = $model;
		$tripType	 = $model->getBookingType($model->bkg_booking_type);
		if ($bkgCount > 1 && $bcbmodel->bcb_trip_type == 1)
		{
			#Arranging bookings according to their pickups
			if ($bcbmodel->bookings[0]->bkg_pickup_date < $bcbmodel->bookings[$bkgCount - 1]->bkg_pickup_date)
			{
				$toBkgModel = Booking::model()->findByPk($bcbmodel->bookings[$bkgCount - 1]->bkg_id);
			}
			else
			{
				$model = Booking::model()->findByPk($bcbmodel->bookings[$bkgCount - 1]->bkg_id);
			}
			$tripType = 'Matched Trip';
		}

		$vehicleTypeId = $model->bkg_vehicle_type_id;

		$cabType = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;

		$bkgFromCity	 = $model->bkg_from_city_id;
		$fromCityName	 = $model->bkgFromCity->cty_name;

		$bkgToCity	 = $toBkgModel->bkg_to_city_id;
		$toCityName	 = $toBkgModel->bkgToCity->cty_name;

		$datePickupDate		 = new DateTime($model->bkg_pickup_date);
		$pickupTime			 = $datePickupDate->format('j/F/Y g:i A');
		$tripVendorAmount	 = $bcbmodel->bcb_vendor_amount;
		$onlyGozoNow		 = true;

		$notifiedVendorList = [];

		$appliedVendors		 = BookingVendorRequest::getAppliedVendors($tripId);
		$notifiedVendorList	 = explode(',', $appliedVendors); //Notified vendor id array


		$bcbhash	 = Yii::app()->shortHash->hash($tripId);
		$distance	 = 50;
		$cabTypeList = Vehicles::getVhcTypeFromScv($vehicleTypeId);
		$vendors	 = Vendors::getByPickupCitynCabType($bkgFromCity, $bkgToCity, $cabTypeList, $appliedVendors, $distance);

		$totVendorFound = $vendors->getRowCount() | 0;
		if ($totVendorFound == 0)
		{
			goto endOfProcessing;
		}

		Logger::trace($tripId . " getByPickupCitynCabType : " . $totVendorFound . " records");

		$templateName	 = 'bid_for_new_booking_notification_to_partner_with_stop_reminder_v2';
		$lang			 = 'en_US';
		$templateId		 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
		$arrButton		 = Whatsapp::buildComponentButton([$templateId], 'button', 'quick_reply', "payload");
		$isDelay		 = true;
		foreach ($vendors as $vendor)
		{
			$vndId = $vendor['vnd_id'];
			try
			{
				$succSent = 0;

				$vndhash	 = Yii::app()->shortHash->hash($vndId);
				$bkvnUrl	 = "https://gozo.cab/bkvn1/{$bcbhash}/{$vndhash}";
				$success	 = false;
				$message	 = "Cab required ($cabType): $fromCityName - $toCityName ($tripType) $pickupTime Amount: $tripVendorAmount Please use your Gozo Partner App to accept the bid gozo.cab/bkvn1/$bcbhash/$vndhash";
				$isLoggedIn	 = AppTokens::isVendorLoggedIn($vndId, 72);
				if (!$isLoggedIn && !in_array($vndId, $notifiedVendorList))
				{
					$phnumber	 = '';
					$code		 = '';
					$vndPhone	 = $vendor['vnd_phone'];
					Filter::parsePhoneNumber($vndPhone, $code, $phnumber);
					if ($phnumber > 0)
					{

						$arrWhatsAppData = [$cabType, $fromCityName, $toCityName, $pickupTime, Filter::moneyFormatter($tripVendorAmount), $tripType, $bkvnUrl];
						$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_TRIP, 'ref_id' => $tripId];
						$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);

						$unsubscribe = UnsubscribePhoneno::checkBlockedNumber(($code . $vndPhone), 2, $templateId);
						if ($totSmS <= 30 && $unsubscribe == 0)
						{
							$response	 = WhatsappLog::send(($code . $phnumber), $templateName, $arrDBData, $arrBody, $arrButton, $lang, $isDelay);
							$success	 = ($response && $response['status'] == 2 && $response['wamId'] != null) ? true : false;
						}

						$totSmS++;
						$succSent	 = 1;
						$params		 = [
							'tripId' => $tripId,
							'bkgId'	 => $model->bkg_id,
						];
						$result		 = BookingVendorRequest::notifiedGNowEntry($params, $vndId);
					}

					Logger::trace($message);
				}
			}
			catch (Exception $e)
			{
				Logger::create("Failed to send gnow whatsapp to vendor : $vndId ({$e->getMessage()})", CLogger::LEVEL_ERROR);
			}
		}
		$returnSet->setStatus(true);
		endOfProcessing:
		return $returnSet;
	}

	

}
