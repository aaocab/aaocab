<?php

/**
 * This is the model class for table "booking_track_log".
 *
 * The followings are the available columns in table 'booking_track_log':
 * @property integer $btl_id
 * @property integer $btl_appsync_id
 * @property integer $btl_bcb_id
 * @property integer $btl_bkg_id
 * @property integer $btl_user_id
 * @property integer $btl_user_type_id
 * @property integer $btl_event_type_id
 * @property integer $btl_event_platform
 * @property string $btl_coordinates
 * @property integer $btl_trip_late
 * @property string $btl_device_info
 * @property string $btl_remarks
 * @property string $btl_sync_time
 * @property string $btl_created
 * @property integer $btl_is_discrepancy
 * @property string $btl_discrepancy_remarks
 *
 * The followings are the available model relations:
 * @property Booking $btlBkg
 * @property BookingTrack $bkgTrack
 */
class BookingTrackLog extends CActiveRecord
{

	public $payDocModel;
	public $reff_id;
	public $event = [101 => 'Start', 104 => 'Stop', 107 => 'Selfie', 108 => 'Sanitizer', 503 => 'Voucher', 201 => 'Going for pickup', 203 => 'Arrived'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_track_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('btl_bkg_id', 'required'),
			array('btl_bcb_id, btl_bkg_id, btl_user_id, btl_user_type_id, btl_event_type_id, btl_event_platform', 'numerical', 'integerOnly' => true),
			array('btl_coordinates', 'length', 'max' => 255),
			array('btl_device_info', 'length', 'max' => 2000),
			array('btl_remarks', 'length', 'max' => 5000),
			array('btl_created', 'safe'),
			['btl_id', 'validateAppSync', 'on' => 'syncBooking'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('btl_id, btl_bcb_id, btl_bkg_id, btl_user_id, btl_user_type_id, btl_event_type_id, btl_event_platform, btl_coordinates,btl_trip_late, btl_device_info, btl_remarks, btl_created,btl_is_discrepancy,btl_discrepancy_remarks', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array
			(
			'btlBkg' => array(self::BELONGS_TO, 'Booking', 'btl_bkg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'btl_id'					 => 'Btl',
			'btl_bcb_id'				 => 'Btl Bcb',
			'btl_bkg_id'				 => 'Btl Bkg',
			'btl_user_id'				 => 'Btl User',
			'btl_user_type_id'			 => 'Btl User Type',
			'btl_event_type_id'			 => 'Btl Event Type',
			'btl_event_platform'		 => 'Btl Event Platform',
			'btl_coordinates'			 => 'Btl Coordinates',
			'btl_trip_late'				 => 'Btl Trip Late',
			'btl_device_info'			 => 'Btl Device Info',
			'btl_remarks'				 => 'Btl Remarks',
			'btl_sync_time'				 => 'Btl Sync Time',
			'btl_created'				 => 'Btl Created',
			'btl_is_discrepancy'		 => 'Btl Discrepancy',
			'btl_discrepancy_remarks'	 => 'Btl Discrepancy Remarks',
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

		$criteria->compare('btl_id', $this->btl_id);
		$criteria->compare('btl_bcb_id', $this->btl_bcb_id);
		$criteria->compare('btl_bkg_id', $this->btl_bkg_id);
		$criteria->compare('btl_user_id', $this->btl_user_id);
		$criteria->compare('btl_user_type_id', $this->btl_user_type_id);
		$criteria->compare('btl_event_type_id', $this->btl_event_type_id);
		$criteria->compare('btl_event_platform', $this->btl_event_platform);
		$criteria->compare('btl_coordinates', $this->btl_coordinates, true);
		$criteria->compare('btl_trip_late', $this->btl_trip_late, true);
		$criteria->compare('btl_device_info', $this->btl_device_info, true);
		$criteria->compare('btl_remarks', $this->btl_remarks, true);
		$criteria->compare('btl_created', $this->btl_created, true);
		$criteria->compare('btl_is_discrepancy', $this->btl_is_discrepancy, true);
		$criteria->compare('btl_discrepancy_remarks', $this->btl_discrepancy_remarks, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingTrackLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * Tracking booking & track data for location
	 */

	public function addLocation()
	{
		if (empty($this->btl_coordinates) || empty($this->btl_sync_time))
		{
			return false;
		}

		$deviceInfo = array();
		if ($this->btl_device_info != '')
		{
			$deviceInfo = CJSON::decode($this->btl_device_info, true);
		}

		$coordinates = explode(',', $this->btl_coordinates);

		$data					 = array();
		$data['lat']			 = $coordinates[0];
		$data['lon']			 = $coordinates[1];
		$data['timeStamp']		 = (strtotime($this->btl_sync_time) * 1000);
		$data['loc_ref_id']		 = $this->btl_bkg_id;
		$data['loc_ref_type']	 = Location::REF_TYPE_BOOKING;
		$data['loc_event_id']	 = $this->btl_event_type_id;
		$data['loc_desc']		 = (!empty(BookingTrack::getTripEventTitles($this->btl_event_type_id)) ? BookingTrack::getTripEventTitles($this->btl_event_type_id) : NULL);
		$data['loc_device_uuid'] = (!empty($deviceInfo['uniqueId']) ? $deviceInfo['uniqueId'] : NULL);

		Location::addLocation($data);
	}

	/**
	 * This function is used for handling all the tracking events related to a booking
	 * @param Stub\booking\SyncRequest $obj
	 * @return \Stub\booking\SyncResponse
	 */
	public function handleEvents($obj = null, $dco = null, $reqData =null)
	{
		#\Logger::create("Sync Request handleEvents : ", CLogger::LEVEL_INFO);

		switch ($this->btl_event_type_id)
		{

			case BookingTrack::OTP_VERIFY:
				$returnSet = $this->verifyTripOTP();
				break;

			case BookingTrack::TRIP_SELFIE:
				$returnSet	 = $this->uploadSelfie();
				break;
			case BookingTrack::TRIP_SANITIZER_KIT:
				$returnSet	 = $this->uploadSanitizer();
				break;
			case BookingTrack::TRIP_ARROGYA_SETU:
				$returnSet	 = $this->updateArrogyaSetu();
				break;
			case BookingTrack::TRIP_TERMS_AGREE:
				$returnSet	 = $this->updateTerms();
				break;

			case BookingTrack::TRIP_START:
				$returnSet = $this->startTrip($dco);
				break;

			case BookingTrack::TRIP_PAUSE:
				$returnSet = $this->Pause();
				break;

			case BookingTrack::TRIP_RESUME:
				$returnSet = $this->resume();
				break;

			case BookingTrack::TRIP_STOP:

				$returnSet = $this->stopTrip($obj, $dco);
				break;

			case BookingTrack::GOING_FOR_PICKUP:
				$returnSet = $this->pickupState();
				break;

			case BookingTrack::NOT_GOING_FOR_PICKUP:
				$returnSet = $this->pickupState();
				break;

			case BookingTrack::DRIVER_ARRIVED:
				$returnSet = $this->driverArrived();
				break;

			case BookingTrack::NO_SHOW:
				$returnSet	 = $this->noShow();
				break;
			case BookingTrack::WAIT:
				$returnSet	 = $this->updateWait();
				break;

			case BookingTrack::NO_SHOW_RESET:
				$returnSet = $this->noShowReset();
				break;

			case BookingTrack::SOS_START:
				$returnSet = $this->SOS();
				break;

			case BookingTrack::SOS_RESOLVED:
				$returnSet	 = $this->SOS();
				break;
			case BookingTrack::VOUCHER_UPLOAD:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::ODOMETER_START_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::ODOMETER_STOP_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::TOLL_TAX_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::STATE_TAX_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::PARKING_CHARGES_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::DUTY_SLIP_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::OTHERS_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack:: TRIP_POSITION:
				$returnSet	 = $this->tripPosition();
				break;
			case BookingTrack::CAB_FRONT_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::CAB_BACK_FILE:
				$returnSet	 = $this->voucherUpload();
				break;
			case BookingTrack::CAR_BREAKDOWN:
				$returnSet = $this->carBreakDown($reqData);
				break;
			
		}

		return $returnSet;
	}

	public function getEventIdByType($eventType)
	{
		switch ($eventType)
		{
			case 'BOARDED':
				$eventId = 101;
				break;
			case 'ALIGHT':
				$eventId = 104;
				break;
			case 'STARTED':
				$eventId = 201;
				break;
			case 'ARRIVED':
				$eventId = 203;
				break;
			case 'NOTBOARDED':
				$eventId = 204;
				break;
		}
		return $eventId;
	}

	/**
	 * 
	 * @param type $eventType
	 * @return int
	 */
	public function getEventIdByEventType($eventType)
	{
		switch ($eventType)
		{
			case 'leftForPickup':
				$eventId = 201;
				break;
			case 'arrived':
				$eventId = 203;
				break;
			case 'tripStart':
				$eventId = 101;
				break;
			case 'tripEnd':
				$eventId = 104;
				break;
			case 'driverPosition':
				$eventId = 111;
				break;
		}
		return $eventId;
	}

	/**
	 * 
	 * @param type $eventId
	 */
	public function getEventTypeById($eventId)
	{
		switch ($eventId)
		{
			case 201:
				$eventType	 = 'Left for pickup';
				break;
			case 203:
				$eventType	 = 'Arrived';
				break;
			case 101:
				$eventType	 = 'Trip start';
				break;
			case 104:
				$eventType	 = 'Trip stop';
				break;
			case 111:
				$eventType	 = 'Driver position';
				break;

			default:
				break;
		}

		return $eventType;
	}

	/**
	 * This function is used for verifying the OTP used for the ride
	 * @param type $platform
	 * @param type $bookingTrackModel
	 * @param type $bookingModel
	 * @return \ReturnSet
	 */
	public function verifyTripOTP($platform)
	{
		$returnSet = new ReturnSet();

		if (empty($platform))
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Mandatory data not passed");

			goto skipAllCode;
		}

		$success = false;

		$transaction = DBUtil::beginTransaction();
		try
		{
			$isOtpRequired = $this->btlBkg->bkgPref->bkg_trip_otp_required;

			//Checks whether otp is verification is required or not
			if (!$isOtpRequired)
			{
				$success = true;
				goto skipAll;
			}

			/**
			 * Checks whether the trip is already verified or not
			 * Else validate it
			 */
			if ($isOtpRequired && $this->btlBkg->bkgTrack->bkg_is_trip_verified)
			{
				DBUtil::commitTransaction($transaction);

				$success = true;

				goto skipAll;
			}

			$bCabModel	 = $this->btlBkg->bkgBcb; //booking Cab model
			$bookingId	 = $this->btl_bkg_id;

			$phoneNumber = $bCabModel->bcb_driver_phone;
			$driverId	 = $bCabModel->bcb_driver_id;

			$bModel = Booking::model()->findByPk($this->btl_bkg_id);

			$tmodel = TripOtplog::addNew($bookingId, $driverId, $platform, $this->btlBkg->bkgTrack->bkg_trip_otp, null, $phoneNumber);

			/*
			 * Checks whether OTP matches with the actual or not
			 */
			if ($bModel->bkgTrack->bkg_trip_otp != $this->btlBkg->bkgTrack->bkg_trip_otp)
			{
				$tmodel->trl_status = 2;
				$tmodel->save();

				if (!empty($phoneNumber))
				{
					$drvName = ($bCabModel->bcb_driver_name) ? $bCabModel->bcb_driver_name : $bCabModel->bcbDriver->drv_name;

					$msgCom = new smsWrapper();
					$msgCom->informDriverInvalidOTP($phoneNumber, $drvName, $this->btlBkg->bkg_booking_id);
				}

				$success = true;
				goto skipAll;
			}

			$this->btlBkg->bkgTrack->bkg_is_trip_verified	 = 1;
			$remarks										 = "";
			if ($this->btlBkg->bkgTrack->save())
			{
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

				$success		 = BookingCab::model()->pushPartnerTripStart($bookingId, $tmodel->trl_date);
				$penaltyType	 = PenaltyRules::PTYPE_LATE_OTP_VERIFICATION;
				$penaltyAmount	 = BookingTrack::getLatePenality($bookingId, $this->btlBkg->bkgTrack->bkg_trip_start_time, $penaltyType);

				if ($penaltyAmount > 0)
				{
					$vendorId	 = $bCabModel->bcb_vendor_id;
					$bookingId	 = $bModel->bkg_booking_id;
					$remarks	 = "Late OTP verification of booking #$bookingId";
					$result		 = AccountTransactions::checkAppliedPenaltyByType($bookingId, $penaltyType);
					if ($result)
					{
						AccountTransactions::model()->addVendorPenalty($bookingId, $vendorId, $penaltyAmount, $remarks, '', $penaltyType);
					}
				}

				if (!empty($phoneNumber))
				{
					$msgCom = new smsWrapper();
					$msgCom->MatchOTP($phoneNumber, $drvName, $bModel->bkg_booking_id);
				}

				DBUtil::commitTransaction($transaction);
				$success = true;

				goto skipAll;
			}

			$returnSet->setErrors($this->getErrors(), 1);
			skipAll:
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}


		$returnSet->setStatus($success);
		skipAllCode:

		return $returnSet;
	}

	/**
	 * This function is used for voucher upload
	 * @return \ReturnSet
	 */
	public function voucherUpload()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bookingTrackModel = $this->btlBkg->bkgTrack;
			if ($this->btl_user_type_id == 3)
			{
				$platformId	 = 2;
				$type		 = UserInfo::getUserTypeDesc($this->btl_user_type_id);
			}
			//Vendor
			if ($this->btl_user_type_id == 2)
			{
				$type = UserInfo::getUserTypeDesc($this->btl_user_type_id);
			}
			//Updating Booking Pay docs for checksum
			$payDocModel = $this->payDocModel;

			//Set scenario for duplicate checksum

			$payDocResponse = $payDocModel->savePayDocs();
			if (!$payDocResponse)
			{
				throw new Exception("Unable to Uploaded Voucher.", ReturnSet::ERROR_FAILED);
			}


			$voucherTypeName				 = BookingPayDocs::model()->getTypeByVoucherId($payDocModel->bpay_type);
			$desc							 = "Voucher Type : " . $voucherTypeName;
			$message						 = "$desc Uploaded Successfully by $type.";
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
			//Fetching old booking log mapping
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id];
			$eventId						 = $this->btl_event_type_id;
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$this->scenario					 = 'syncBooking';

			if ($this->save()) //Booking track log save
			{
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage(), null, $oldEventId, false, $params);

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				$cabId	 = $booking->bkgBcb->bcb_cab_id;
				$eventId = $this->btl_event_type_id;
				if ($eventId == 8 || $eventId == 9)
				{
					VehicleStats::updateVerifyFlag($cabId);
				}
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			else
			{
				$error = json_encode($this->getErrors());
				throw new Exception($error, ReturnSet::ERROR_FAILED);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 * This function is used for starting a specific booking trip
	 * @return \ReturnSet
	 */
	public function startTrip($dco = null)
	{
		Logger::setModelCategory(_CLASS_, _FUNCTION_);
		$returnSet = new ReturnSet();
		/**
		 * Check whether trip is already started or not
		 * If started then skip the below code and continue
		 * Else proceed with the work flow
		 */
		if ($this->btlBkg->bkgTrack->bkg_ride_start || $this->btlBkg->bkgTrack->bkg_ride_complete)
		{
			$returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
			$returnSet->setMessage("Trip already started");
			goto skipAll;
		}
		$nowTime	 = DBUtil::getCurrentTime(); //according to AKG driver unable to start trip before 3 hr date 23-05-2024
		$dur		 = 60;
		$gressTime	 = date("Y-m-d H:i:s", strtotime($nowTime . " $dur minutes"));
		if ($this->btlBkg->bkg_pickup_date > $gressTime)
		{
			throw new Exception("Trip should not be started before 1 hr of pickup time", ReturnSet::ERROR_FAILED);
		}
		$transaction = DBUtil::beginTransaction();
		try
		{

			$bookingTrackModel = $this->btlBkg->bkgTrack; //Taking the booking track
			//Driver
			if ($this->btl_user_type_id == 3)
			{
				$platformId = 2;
			}

			//vendor
			if ($this->btl_user_type_id == 2)
			{
				$platformId = 4;
			}

			$platformArr	 = TripOtplog::platformArr;
			$platformName	 = $platformArr[$platformId];
			//Response from booking track save
			$btResponse		 = $bookingTrackModel->markTripStart($platformName, $this->btl_event_type_id);
			if (!$btResponse)
			{
				throw new Exception("Unable to start", ReturnSet::ERROR_FAILED);
			}
			//Fetching old booking log mapping
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			if ($this->btlBkg->bkgBcb->bcb_vendor_id != Config::get("hornok.operator.id"))
			{
				//Updating Booking Pay docs for checksum
				$payDocModel = $this->payDocModel;

				#$payDocResponse               = $payDocModel->savePayDocs();
				#if (!($payDocResponse && $btResponse)) # commented due to checksum remove from vendor APP
				if ($dco <> 1)
				{
					if (!$payDocModel->save() && $btResponse)
					{
						throw new Exception("Unable to start", ReturnSet::ERROR_FAILED);
					}
				}
			}
			$message		 = "Otp Verified! Trip started successfully";
			$returnSet->setMessage($message);
			$this->scenario	 = 'syncBooking';
			if ($this->save()) //Booking track log save
			{
				$userInfo		 = UserInfo::getInstance();
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				$returnSet->setStatus(true);
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, $userInfo, $oldEventId, false, $params);
				$odometerRemarks = "Odometer start value  " . $this->btlBkg->bkgTrack->bkg_start_odometer;
				BookingLog::model()->createLog($this->btl_bkg_id, $odometerRemarks . '    at ' . $this->btl_sync_time, $userInfo, BookingLog::START_ODOMETER, false, $params);
			}
			else
			{
				if ($this->hasErrors())
				{
					throw new Exception(json_encode($this->getErrors()), 1);
				}
				else
				{
					throw new Exception("Some thing went wrong.", ReturnSet::ERROR_FAILED);
				}
			}
			if ($this->btlBkg->bkgBcb->bcb_vendor_id != Config::get("hornok.operator.id"))
			{
				BookingScheduleEvent::addPostEvent($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_sync_time);
			}
			notificationWrapper::customerNotifyTripStart($this->btl_bkg_id);

			DBUtil::commitTransaction($transaction);

			//$this->postEvent_Start($this->btl_bkg_id);
		}
		catch (Exception $ex)
		{

			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}

		skipAll:
		Logger::unsetModelCategory(_CLASS_, _FUNCTION_);
		return $returnSet;
	}

	/**
	 * This function is used for resetting the no show state
	 * @return \ReturnSet
	 */
	public function noShowReset()
	{
		$returnSet = new ReturnSet();

		$returnSet->getStatus(false);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$receivedDriverId	 = Drivers::getDriverId($this->btl_user_id);
			$receivedDate		 = ContactProfile::getEntitybyUserId($this->btl_user_id);
			$receivedDriverId	 = $receivedDate['cr_is_driver'];
			/**
			 * Checks whether driver pk exists or not
			 */
			if (empty($receivedDriverId))
			{
				goto skipAllCode;
			}

			/**
			 * Checks whether driverId and db ids are same or not
			 */
			if ($receivedDriverId != $this->btlBkg->bkgBcb->bcb_driver_id)
			{
				goto skipAllCode;
			}



			$this->btlBkg->bkgTrack->bkg_is_no_show		 = 0;
			$this->btlBkg->bkgTrack->bkg_no_show_time	 = null;
			$desc										 = "No Show not yet saved.\n\t\t" . json_encode($this->getErrors());
			if ($this->btlBkg->bkgTrack->save())
			{
				$desc = "Consumer No Show has been reset.";
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);

			skipAllCode:

			/**
			 * Write log
			 * 1 - Booking Log
			 * 2 - Booking track log
			 */
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id];
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$this->scenario					 = 'syncBooking';
			if ($this->save())
			{

				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $oldEventId, false, $params);
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
		}
		return $returnSet;
	}

	/**
	 * This function is used for updating the wait time
	 * @return \ReturnSet
	 */
	public function updateWait()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$desc = $this->btl_remarks;
			if (empty($this->btl_remarks))
			{
				$desc = "Cab will be late." . $this->btl_coordinates . " and " . $this->btl_sync_time;
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);

			/**
			 * Write log
			 * 1 - Booking Log
			 * 2 - Booking track log
			 */
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id];
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$this->scenario					 = 'syncBooking';
			if (!$this->save())
			{
				throw new Exception("Not Saved.", ReturnSet::ERROR_FAILED);
			}
			BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $oldEventId, false, $params);
			//BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

			$booking = Booking::model()->findByPk($this->btl_bkg_id);
			if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
			{
				$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
				$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
			}


			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
		}

		return $returnSet;
	}

	/**
	 * This function is used for resuming the trip
	 * @return \ReturnSet
	 */
	public function resume()
	{
		$returnSet		 = new ReturnSet();
		$bookingLogEvent = BookingLog::mapEvents();
		$oldEventId		 = $bookingLogEvent[$this->btl_event_type_id];

		$transaction = DBUtil::beginTransaction();
		try
		{
			$arrBookingIds	 = BookingSub::model()->getBkgsByFlexxiBkg($this->btl_bkg_id);
			$desc			 = "Trip resumed on " . $this->btl_coordinates . " at " . $this->btl_sync_time;
			if (empty(count($arrBookingIds)))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Trip resumed. No flexxi booking found");
				goto skipAllCode;
			}

			foreach ($arrBookingIds as $booking)
			{
				if ($booking["bkg_ride_complete"])
				{
					continue;
				}

				$desc = "Trip Resume." . $this->btl_coordinates . " and " . $this->btl_sync_time;

				$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
				$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
				BookingLog::model()->createLog($booking->bkg_id, $desc, null, $oldEventId, false, $params);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);
			skipAllCode:
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			/**
			 * Write log
			 * 1 - Booking Log
			 * 2 - Booking track log
			 */
			$this->scenario					 = 'syncBooking';
			if (!$this->save())
			{


				if ($this->hasErrors())
				{
					throw new Exception(json_encode($this->getErrors()), 1);
				}
				else
				{
					throw new Exception("Some thing went wrong.", ReturnSet::ERROR_FAILED);
				}
			}
			else
			{
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $oldEventId, false, $params);
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$errors = $ex->getMessage();

			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for updating the pickup state for a trip
	 * @return \ReturnSet
	 */
	public function pickupState()
	{
		$returnSet = new ReturnSet();

		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($this->btlBkg->bkgTrack->bkg_ride_start == 1)
			{
				$returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
				$returnSet->setMessage("Trip already started");
				goto skipAllCode;
			}


			$desc = "Driver left for pickup." . $this->btl_coordinates . " and " . $this->btl_sync_time;
			if ($this->btl_event_type_id == BookingTrack::NOT_GOING_FOR_PICKUP)
			{
				$desc = "Driver not going for pickup." . $this->btl_coordinates . " and " . $this->btl_sync_time;
				//@todo - Penalty function pending
			}


			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);
			skipAllCode:
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			/**
			 * Write
			 * 1 -	Booking Log
			 * 2 -	Booking trip track log
			 */
			$this->scenario					 = 'syncBooking';
			if ($this->save())
			{

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				$cabId	 = $booking->bkgBcb->bcb_cab_id;
				$eventId = $this->btl_event_type_id;
				if ($eventId == 8 || $eventId == 9)
				{
					VehicleStats::updateVerifyFlag($cabId);
				}
				$userInfo = UserInfo::getInstance();

				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				BookingLog::model()->createLog($this->btl_bkg_id, $desc, $userInfo, $oldEventId, false, $params);

				if (BookingTrack::GOING_FOR_PICKUP)
				{
					$typeAction = AgentApiTracking::TYPE_LEFT_FOR_PICKUP;
				}
				if ($this->btlBkg->bkgBcb->bcb_vendor_id != Config::get("hornok.operator.id"))
				{
					AgentMessages::model()->pushApiCall($this->btlBkg, $typeAction);
				}
//				$booking = Booking::model()->findByPk($this->btl_bkg_id);
//				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
//				{
//					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
//					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 1);
//				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$errors = $ex->getMessage();
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
		}

		return $returnSet;
	}

	/**
	 * This function is used for updating the arrival state.
	 * @return \ReturnSet
	 */
	public function driverArrived()
	{
		$returnSet = new ReturnSet();

		$nowTime	 = DBUtil::getCurrentTime(); //according to AKG driver unable to start trip before 3 hr date 23-05-2024
		$dur		 = 180;
		$gressTime	 = date("Y-m-d H:i:s", strtotime($nowTime . " $dur minutes"));
		if ($this->btlBkg->bkg_pickup_date > $gressTime)
		{

			throw new Exception("Driver should not be mark arrived before 3 hr of pickup time", ReturnSet::ERROR_FAILED);
		}
		if ($this->btlBkg->bkgTrack->bkg_ride_start == 1)
		{
			$returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
			$returnSet->setMessage("Trip already started");
			goto skipAllCode;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($this->btl_user_type_id == 3)
			{
				$platformId = 2;
			}

			//vendor
			if ($this->btl_user_type_id == 2)
			{
				$platformId = 4;
			}

			$platformArr	 = TripOtplog::platformArr;
			$platformName	 = $platformArr[$platformId];

			$bookingTrackModel							 = $this->btlBkg->bkgTrack; //Taking the booking track
			$bookingTrackModel->bkg_arrived_for_pickup	 = 1;

			if (!$bookingTrackModel->save())
			{
				throw new Exception("Unable to save arrival time", ReturnSet::ERROR_FAILED);
			}

			$returnArr = BookingTrail::model()->updateDriverScore($this->btl_bkg_id, $this->btl_event_type_id);

			if (!$returnArr["success"])
			{
				$returnSet->setMessage($returnArr["errors"]);
				goto skipAllCode;
			}

			//$desc				 = "Cab Arrived at " . $this->btl_coordinates . " at " . DateTimeFormat::SQLDateTimeToLocaleDateTime($this->btl_sync_time);
			$desc				 = "Cab Arrived at " . DateTimeFormat::SQLDateTimeToLocaleDateTime($this->btl_sync_time);
			$this->btl_remarks	 = $desc;
			if (!empty($this->btl_remarks))
			{
				$desc = $this->btl_remarks;
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);
			skipAllCode:

			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			/**
			 * Write
			 * 1 -	Booking Log
			 * 2 -	Booking trip track log
			 */
			$this->scenario					 = 'syncBooking';
			if ($this->save())
			{
				$userInfo = UserInfo::getInstance();
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				BookingLog::model()->createLog($this->btl_bkg_id, $this->btl_remarks, $userInfo, $oldEventId, false, $params);
			}
			else
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$transferzId = Config::get('transferz.partner.id');
			if ($this->btlBkg->bkg_agent_id != $transferzId)
			{
				notificationWrapper::customerNotifyDriverArrived($this);
			}
			DBUtil::commitTransaction($transaction);
			BookingScheduleEvent::addPostEvent($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_sync_time);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for notifying that customer is not available
	 * @return \ReturnSet
	 */
	public function noShow()
	{
		$returnSet = new ReturnSet();
		try
		{
			/* if ($this->btlBkg->bkgBcb->bcb_driver_id != UserInfo::getEntityId())
			  {
			  $returnSet->setMessage("Driver name not matched.\n\t\t");
			  goto skipAllCode;
			  } */
			$validateNoShow = $this->noShowValidation();
			if ($validateNoShow == false)
			{
				$returnSet->setMessage("Unable to set customer no show.");
				goto skipAllCode;
			}
			$noshowTime = $this->btlBkg->bkgTrack->bkg_no_show_time;

			$bookingTrackModel					 = $this->btlBkg->bkgTrack; //Taking the booking track
			$bookingTrackModel->bkg_is_no_show	 = 1;
			if ($bookingTrackModel->save())
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Consumer No Show has been set. Location : " . $this->btl_coordinates . "");
			}

			skipAllCode:
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;

			/**
			 * Write
			 * 1 -	Booking Log
			 * 2 -	Booking trip track log
			 */
			$this->scenario = 'syncBooking';
			if ($this->save())
			{
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage(), null, $oldEventId, false, $params);
				if ($this->btlBkg->bkg_agent_id != 18190)
				{
					$typeAction = AgentApiTracking::TYPE_NO_SHOW;
					AgentMessages::model()->pushApiCall($this->btlBkg, $typeAction);
				}

				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function noShowValidation()
	{
		$noShowTime			 = $this->btlBkg->bkgTrack->bkg_no_show_time;
		$lastEvent			 = $this->btlBkg->bkgTrack->btk_last_event;
		$bkgId				 = $this->btlBkg->bkg_id;
		$bkgStatus			 = $this->btlBkg->bkg_status;
		$startStatus		 = $this->btlBkg->bkgTrack->bkg_trip_start_time;
		$endStatus			 = $this->btlBkg->bkgTrack->bkg_trip_end_time;
		$currentNoShowTime	 = date('Y-m-d H:i:s');
		$lastNoShowResetTime = $this->fetchSyncTime($bkgId, 206);
		$lastNoShowTime		 = $this->fetchSyncTime($bkgId, 204);
		$validate			 = false;
		if ($bkgStatus == 5 AND ($startStatus == null AND $endStatus == null))
		{
			if ($lastNoShowResetTime == "" && $lastNoShowTime == "")
			{
				$validate = true;
			}
			else if ($lastNoShowResetTime != "" && $currentNoShowTime > $lastNoShowResetTime)
			{
				$validate = true;
			}
			else
			{
				$validate = false;
			}
		}
		return $validate;
	}

	public function fetchSyncTime($bkgId, $eventId)
	{
		$sql = "SELECT btl_sync_time  FROM `booking_track_log` WHERE `btl_bkg_id` = $bkgId AND btl_event_type_id = $eventId ORDER BY btl_id DESC LIMIT 0,1";
		return DBUtil::queryScalar($sql);
	}

	public function Pause()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$arrBookingIds					 = BookingSub::model()->getBkgsByFlexxiBkg($this->btl_bkg_id);
			$desc							 = "Trip paused on " . $this->btl_coordinates . " at " . $this->btl_sync_time;
			if (empty(count($arrBookingIds)))
			{
				$returnSet->setMessage("Trip paused. No flexxi booking found");
				goto skipAllCode;
			}

			foreach ($arrBookingIds as $booking)
			{
				if ($booking["bkg_ride_complete"])
				{
					continue;
				}
				//$desc = "Trip Pause." . $this->btl_coordinates . " and " . $this->btl_sync_time;
				$desc = "Trip Pause at " . $this->btl_sync_time;
				BookingLog::model()->createLog($booking->bkg_id, $desc, null, $oldEventId, false, $params);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);

			skipAllCode:
			/**
			 * Write
			 * 1 -	Booking Log
			 * 2 -	Booking trip track log
			 */
			$this->scenario = 'syncBooking';
			if (!$this->save())
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_FAILED);
			}
			BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage(), null, $oldEventId, false, $params);
			BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
			$booking = Booking::model()->findByPk($this->btl_bkg_id);
			if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
			{
				$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
				$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
			}




			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for updating the SOS state
	 * @return \ReturnSet
	 */
	public function SOS()
	{
		$returnSet = new ReturnSet();
		#$transaction = DBUtil::beginTransaction();
		try
		{
//			$sosCheck = BookingTrack::checkSOSTrigger($this->btl_bkg_id, $this->btl_event_type_id);
//			if ($sosCheck)
//			{
//				$returnSet->setStatus(true);
//				$desc = "S.O.S. already triggered";
//				goto skipSOS;
//			}

			$this->btlBkg->bkg_id	 = $this->btl_bkg_id;
			$deviceInfo				 = CJSON::decode($this->btl_device_info);
			if ($deviceInfo["uniqueId"])
			{
				$returnSet = $this->sendSOStoContacts();
			}
			$userInfo						 = UserInfo::getInstance();
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) $userInfo->getUserType();
			//Response from booking track save
			$bookingTrackModel				 = $this->btlBkg->bkgTrack;
			$btResponse						 = $bookingTrackModel->setSos($this->btl_event_type_id, $this->btl_coordinates);
			
			$var							 = explode(',', $this->btl_coordinates);
			if ($this->btl_event_type_id == 301)
			{
				//$desc = "S.O.S. activated  at Latitude:$var[0] Longitude:$var[1] on Date $this->btl_sync_time.";
				$desc = "S.O.S. activated at ". $this->btl_sync_time;
			}
			else
			{
				//$desc = "S.O.S. ended  at Latitude: $var[0] Longitude: $var[1] on Date $this->btl_sync_time.";
				$desc = "S.O.S. ended at $this->btl_sync_time.";
			}
			if ($this->btl_remarks == '')
			{
				$this->btl_remarks = $desc;
			}
			/**
			 * Write
			 * 1 -	Booking Log
			 * 2 -	Booking trip track log
			 */
			if ($btResponse)
			{
				if ($this->save())
				{
					BookingLog::model()->createLog($this->btl_bkg_id, $desc, $userInfo, $oldEventId, false, $params);

					$data			 = array();
					$data['bkg_id']	 = $this->btl_bkg_id;
					$data['lat']	 = $var[0];
					$data['lon']	 = $var[1];
					$sosContactList	 = Users::model()->sendNotificationToSosContact($this->btl_user_id, $data);
					$returnSet->setStatus(true);
					//Notification to OPS App
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$typeName		 = Booking::model()->userArr[$this->btl_user_type_id];
					$csrIds			 = Admins::model()->getCsrNotificationList();
					$bookingId		 = Booking::model()->getCodeById($this->btl_bkg_id);
					foreach ($csrIds as $csrId)
					{
						$csrUserId = $csrId['adm_id'];
						if ($this->btl_event_type_id == 301)
						{
							$payLoadData = ['bookingId' => $this->btl_bkg_id, 'EventCode' => BookingTrack::SOS_ON_NOTIFICATION];
							$title		 = "SOS triggered on  - " . $bookingId;
							$message	 = "S.O.S. trigger activated by $typeName for booking " . $bookingId;
						}
						else
						{
							$payLoadData = ['bookingId' => $this->btl_bkg_id, 'EventCode' => BookingTrack::SOS_OFF_NOTIFICATION];
							$title		 = "SOS triggered off - " . $bookingId;
							$message	 = "S.O.S. panic ended by $typeName for booking " . $bookingId;
						}
						$result = AppTokens::model()->notifyAdmin($csrUserId, $payLoadData, $notificationId, $message, $title);
					}
					Logger::create("SOS contact List ::" . CJSON::encode($sosContactList), CLogger::LEVEL_TRACE);

//===================================
					###########################################################################################################################
					$isSCQ = ServiceCallQueue::checkActiveCBRByBookingId($this->btl_bkg_id, $userInfo::getUserId(), ServiceCallQueue::TYPE_SOS);
					if ($isSCQ > 0 && $this->btl_event_type_id == 301)
					{
						goto skipscq;
					}
					############################################################################################################################
					if ($this->btl_event_type_id == 301)
					{
						$entityType = $userInfo->getUserType();
						$userId      = $userInfo->getUserId();
						$entityId	 = $userInfo->getEntityId();
						if($entityId=="" && $entityType ==1)
						{
							$entityId = $userId;
						}
						
						switch ($entityType)
						{
							case UserInfo::TYPE_DRIVER:
								//$entityId	 = $userInfo->getEntityId();
								$platform	 = ServiceCallQueue::PLATFORM_DRIVER_APP;
								break;
							case UserInfo::TYPE_CONSUMER:
								//$entityId	 = $userInfo->getEntityId();
								$platform	 = ServiceCallQueue::PLATFORM_CONSUMER_APP;
								break;
						}
						//$entityId	 = UserInfo::getEntityId();
						//$entityType	 = UserInfo::TYPE_DRIVER;

						$model = new ServiceCallQueue();

						$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_SOS;
						$model->scq_to_be_followed_up_with_value	 = ContactPhone::getPhoneNo($entityId, $entityType);
						$model->scq_creation_comments				 = "SOS triggered";
						$model->contactRequired						 = 1;
						$model->scq_to_be_followed_up_with_entity_id = ContactProfile::getByEntityId($entityId, $entityType);
						$bkgCode									 = Booking::model()->getCodeById($this->btl_bkg_id);
						$model->scq_related_bkg_id					 = $bkgCode;
						//$platform									 = ServiceCallQueue::PLATFORM_DRIVER_APP;
						$followreturnSet							 = ServiceCallQueue::model()->create($model, $entityType, $platform);
						$returnFollowupArr							 = $followreturnSet->getData();
						$followupId									 = $returnFollowupArr['followupId'];
						if ($followupId != null)
						{
							$logComments				 = $model->scq_creation_comments . " | FollowUp-CODE : " . $returnFollowupArr['followupCode'] . " | QueueNo : " . $returnFollowupArr['queNo'] . " | WaitingTime : " . $returnFollowupArr['waitTime'];
							$params['blg_ref_id']		 = $this->btl_bkg_id;
							$params['current_user_type'] = (int) $entityType;
							BookingLog::model()->createLog($this->btl_bkg_id, $logComments, $userInfo, BookingLog::FOLLOWUP_CREATE, false, $params);
						}
					}
					skipscq:
//====================================
				}
				else
				{
//					if ($this->errors)
//					{
//						$desc = $this->errors['btl_event_type_id'][0]; //$this->errors;
//					}



					if ($this->hasErrors())
					{
						throw new Exception(json_encode($this->getErrors()), 1);
					}
					else
					{
						throw new Exception("Some thing went wrong.", ReturnSet::ERROR_FAILED);
					}
				}
				skipSOS:
				$returnSet->setMessage($desc);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function sendSOStoContacts()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$eventId		 = $this->btl_event_type_id;
			$bkgId			 = $this->btl_bkg_id;
			$userId			 = $this->btl_user_id;
			$travellerName	 = $this->btlBkg->bkgUserInfo->bkg_user_fname . ' ' . $this->btlBkg->bkgUserInfo->bkg_user_lname;

			$driverId = $this->btlBkg->bkgBcb->bcb_driver_id;

			$vendorId = $this->btlBkg->bkgBcb->bcb_vendor_id;

			$sosContactList	 = Users::model()->getSosContactList($userId);
			$urlHash		 = Users::model()->createSOSHashUrl($this->btl_bkg_id, $userId);

			$url = Yii::app()->params["fullBaseURL"] . "/e?v=" . $urlHash;

			if ($this->btl_user_type_id == 3)
			{
				/**
				 * if Usertype is driver and have no contact list
				 * else
				 * driver have sos contact list
				 * else if
				 * userType is consumer and have sos contact list
				 */
				if (empty($sosContactList))
				{
					$returnSet = Vendors::model()->sendNotificationToVendor($bkgId, $driverId, $vendorId, $url, $eventId);
				}
				else
				{
					$returnSet	 = Vendors::model()->sendNotificationToVendor($bkgId, $driverId, $vendorId, $url, $eventId);
					$returnSet	 = Users::model()->sendNotificationToContact($bkgId, $sosContactList, $travellerName, $url, $eventId);
				}
			}
			elseif (!empty($sosContactList) && $this->btl_user_type_id == 1)
			{
				$returnSet = Users::model()->sendNotificationToContact($bkgId, $sosContactList, $travellerName, $url, $eventId);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function carBreakDown($reqData)
	{
		
	
		try
		{
			$bookingTrackModel				 = $this->btlBkg->bkgTrack; //Taking the booking track
			$eventId						 = BookingTrack::CAR_BREAKDOWN;
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$remarks  = $this->btl_remarks;
			$reqData->desc  = $remarks;
			$returnSet = new ReturnSet();
			$message		 = "Car breakdown event trigger";
			//$returnSet->setMessage($message);
			$transaction = DBUtil::beginTransaction();
			$this->scenario	 = 'syncBooking';
			if ($this->save()) //Booking track log save
			{
				$returnSet->setStatus(true);
				BookingLog::model()->createLog($this->btl_bkg_id, $message. '('.$reqData->desc.') at ' . $this->btl_sync_time, null, $eventId, false, $params);
				//BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				
				//create break down scq
				$returnSet = self::breakdownscq($reqData);
				DBUtil::commitTransaction($transaction);
				
			}
			else
			{
				if ($this->errors)
				{
					$modelExistError = $this->errors['btl_event_type_id'][0];
					$returnSet->setMessage($modelExistError);
					$returnSet->setStatus(true);
				}
			}
			return $returnSet;
			
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
			Logger::exception($ex);
		}	

		
	}

	public function breakDownScq($reqData)
	{
		
		//scq part
		$scqType		 = ServiceCallQueue::TYPE_DRIVER_CAR_BREAKDOWN;
		$bkgId           = $reqData->refId;
		$userId	         = UserInfo::getUserId();
		$entityType		 = UserInfo::TYPE_DRIVER;
		$cttId	         = ContactProfile::getByUserId($userId);
		$contactPhone   = ContactPhone::getNumber($cttId);
		
		$reqData->phone->fullNumber = $contactPhone['contactNumber'];
		//phone
		//desc
		$queType           = ServiceCallQueue::TYPE_DRIVER_CAR_BREAKDOWN;
		$reqData->queType = $queType;
		$followupId		 = ServiceCallQueue::getIdByUserId($userId, $scqType, $bkgId);
		$platform		 = ServiceCallQueue::PLATFORM_DCO_APP;
		$returnSet		 = ServiceCallQueue::generateModel($reqData, $cttId, $entityType, $scqType, $followupId, $platform);
		return $returnSet;
	}
	public function syncCoordinates()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$coordinate	 = explode(',', $this->btl_coordinates);
			$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'tracking';
			if (!is_dir($dir))
			{
				mkdir($dir);
			}

			$file		 = $dir . "/" . $this->btl_bkg_id . "_Tracking.csv";
			$file_name	 = "/" . $this->btl_bkg_id . "_Tracking.csv";

			if (!file_exists($file))
			{
				$handle		 = fopen($file, 'w');
				fputcsv($handle, array("SL", "Lattitude", "Longitude", "Event", "Sync Date"));
				$sl_count	 = 1;
			}
			else
			{
				$rows		 = file($file);
				$last_row	 = array_pop($rows);
				$lstdata	 = str_getcsv($last_row);
				$handle		 = fopen($file, 'a');
				$sl_count	 = $lstdata[0] + 1;
			}
			fputcsv($handle, array($sl_count, $coordinate[0], $coordinate[1], $this->btl_event_type_id, $this->btl_sync_time));
			fclose($handle);

			$path		 = 'tracking' . $file_name;
			$trackModel	 = $this->btlBkg->bkgTrack;
			$response	 = $trackModel->saveCSVPath($path);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("CSV save successfully.");
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("CSV not save successfully.");
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function getByBkg($bkg_id, $event)
	{
		$sql	 = "SELECT * FROM  booking_track_log btl where  btl.btl_bkg_id   = $bkg_id AND btl_event_type_id = {$event}";
		$data	 = DBUtil::queryAll($sql);
		return $data;
	}

	public function getDirverAppInfo($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT * FROM  booking_track_log ttg INNER JOIN booking bkg ON bkg.bkg_id = ttg.btl_bkg_id AND bkg_id=:bkgId ";
		$data	 = DBUtil::queryAll($sql, DBUtil:: SDB(), $params);
		return $data;
	}

	public function checkData($id)
	{
		$sql		 = "SELECT booking.bkg_id AS next_bkg_id,
                        booking.bkg_pickup_date,
                        booking.bkg_trip_duration
                        FROM
                        booking_cab
                        INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id
                        AND booking.bkg_active = 1
                        AND booking_cab.bcb_active = 1
                        AND booking.bkg_status IN(5)
                        INNER JOIN drivers ON drivers.drv_id = booking_cab.bcb_driver_id AND drivers.drv_active = 1
						INNER JOIN booking_pref ON booking.bkg_id = booking_pref.bpr_bkg_id
                        INNER JOIN booking_track ON booking.bkg_id = booking_track.btk_bkg_id
                        WHERE
                            1=1
                            AND booking_cab.bcb_driver_id in (SELECT d3.drv_id FROM drivers d1
          INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
          INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
          WHERE d1.drv_id='$id')
                            AND booking_track.bkg_is_no_show = 0
                            AND  booking_track.bkg_ride_complete=0

                        GROUP BY
                            booking.bkg_id
                        ORDER BY
                            booking.bkg_pickup_date ASC
                            LIMIT 0, 1";
		$recordset1	 = DBUtil::queryRow($sql);
		return ['bkg_id' => $recordset1['next_bkg_id'], 'flag' => 1];
	}

	public function getEventTypeByBkg($bkgId)
	{
		$sql = "SELECT `btl_event_type_id` AS ttg_event_type FROM `booking_track_log` WHERE `btl_bkg_id` = '$bkgId'  ORDER BY
					booking_track_log.`btl_created` DESC LIMIT 0,1";
		return DBUtil::command($sql)->queryScalar();
	}

	public function add($platform, $bkg_id, $event, $odoStartReading = '')
	{
		$bModel						 = Booking::model()->findByPk($bkg_id);
		$ttgModel					 = new BookingTrackLog();
		$ttgModel->btl_user_id		 = $bModel->bkgBcb->bcb_driver_id;
		$ttgModel->btl_user_type_id	 = $platform;
		$ttgModel->btl_bkg_id		 = $bkg_id;
		$ttgModel->btl_bcb_id		 = $bModel->bkg_bcb_id;
		$ttgModel->btl_event_type_id = $event;
		$ttgModel->btl_sync_time     = new CDbExpression('NOW()');
		$ttgModel->btl_created     = new CDbExpression('NOW()');
		$ttgModel->scenario			 = 'syncBooking';
		$ttgModel->save();
		return $ttgModel;
	}

	public function validateAppSync($attribute, $params)
	{
		$isExist = self::checkExistingAppSyncEvent($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_appsync_id);
		if (!$isExist)
		{
			$this->addError($attribute, "Event already sync");
		}
		return $isExist;
	}

	public static function checkExistingAppSyncEvent($bkgId, $eventId, $appSyncId)
	{
		$sql = "SELECT COUNT(*) as cnt FROM booking_track_log
                WHERE btl_bkg_id=$bkgId AND btl_event_type_id=$eventId
                AND btl_appsync_id ='$appSyncId'";

		$count = DBUtil::command($sql)->queryScalar();
		return ($count == 0);
	}

	public function getdetailByEvent($bkg_id, $event)
	{
		if ((trim($bkg_id) == null || trim($bkg_id) == "") || (trim($event) == null || trim($event) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array('bkg_id' => $bkg_id, 'event' => $event);
		$sql	 = "SELECT * FROM  booking_track_log btl where  btl.btl_bkg_id   = :bkg_id AND btl_event_type_id = :event ORDER  BY  btl_id DESC LIMIT 1";
		$data	 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		return $data;
	}

	public function getCoordinatesByEvent($bkg_id, $eventId)
	{
		$sql	 = "SELECT btl_coordinates FROM `booking_track_log` WHERE `btl_event_type_id` = $eventId AND `btl_bkg_id` = $bkg_id ";
		$result	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $result;
	}

	public function addByNonDriver($platform, $bkg_id, $event, $odoStartReading = '')
	{
		$bModel		 = Booking::model()->findByPk($bkg_id);
		$user_type	 = ($platform == 1) ? UserInfo::TYPE_DRIVER : $platform;
		if ($platform == 1)
		{
			$user_id = $bModel->bkgBcb->bcb_driver_id;
		}
		else if ($platform == UserInfo::TYPE_VENDOR)
		{
			$user_id = $bModel->bkgBcb->bcb_vendor_id;
		}
		else
		{
			$user_id = UserInfo::getUserId();
		}
		$ttgModel						 = new BookingTrackLog();
		$ttgModel->btl_user_id			 = $user_id;
		$ttgModel->btl_user_type_id		 = $user_type;
		$ttgModel->btl_bkg_id			 = $bkg_id;
		$ttgModel->btl_bcb_id			 = $bModel->bkg_bcb_id;
		$ttgModel->btl_event_type_id	 = $event;
		$ttgModel->btl_event_platform	 = $platform;
		$ttgModel->btl_created			 = new CDbExpression('NOW()');
		$ttgModel->insert();
		return $ttgModel;
	}

	public function getAppSyncIdByBkg($bkg_id, $checksum)
	{
		if ((trim($bkg_id) == null || trim($bkg_id) == "") || (trim($checksum) == null || trim($checksum) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array('bkg_id' => $bkg_id, 'checksum' => $checksum);
		$sql	 = "SELECT btl.`btl_appsync_id` AS appId FROM booking_track_log btl
                   INNER JOIN booking_pay_docs 	ON  booking_pay_docs.bpay_bkg_id = btl.btl_bkg_id AND btl.btl_doc_checksum = booking_pay_docs.bpay_checksum
				   WHERE  btl.btl_bkg_id = :bkg_id	AND    booking_pay_docs.bpay_checksum = :checksum";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}

	public function uploadSelfie()
	{
		$returnSet	 = new ReturnSet();
		/* if ($this->btlBkg->bkgTrack->bkg_ride_start)
		  {
		  $returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
		  $returnSet->setMessage("Trip already started");
		  goto skipAll;
		  } */
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bookingTrackModel				 = $this->btlBkg->bkgTrack; //Taking the booking track
			$eventId						 = BookingTrack::TRIP_SELFIE;
			$blgeventId						 = BookingLog::COVID_CHECK;
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$btResponse						 = $bookingTrackModel->updateSelfie($this->btlBkg->bkgTrack->btk_is_selfie);

			//Updating Booking Pay docs for checksum
			$payDocModel	 = $this->payDocModel;
			$payDocResponse	 = $payDocModel->savePayDocs();
			if (!($payDocResponse && $btResponse))
			{
				throw new Exception("Unable to start", ReturnSet::ERROR_FAILED);
			}
			$message		 = "Selfie taken!";
			$returnSet->setMessage($message);
			$this->scenario	 = 'syncBooking';
			if ($this->save())
			{
				$returnSet->setStatus(true);
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $blgeventId, false, $params);
				//BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			else
			{
				if ($this->errors)
				{
					$modelExistError = $this->errors['btl_event_type_id'][0];
					$returnSet->setMessage($modelExistError);
					$returnSet->setStatus(true);
				}
				//throw new Exception("Selfy already added", ReturnSet::ERROR_FAILED);
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
			Logger::exception($ex);
		}

		skipAll:
		return $returnSet;
	}

	public function uploadSanitizer()
	{
		$returnSet = new ReturnSet();
		if ($this->btlBkg->bkgTrack->bkg_ride_start)
		{
			$returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
			$returnSet->setMessage("Trip already started");
			goto skipAll;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bookingTrackModel				 = $this->btlBkg->bkgTrack; //Taking the booking track
			$eventId						 = BookingTrack::TRIP_SANITIZER_KIT;
			$blgeventId						 = BookingLog::COVID_CHECK;
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;

			$btResponse = $bookingTrackModel->updateCovidSafety($this->btlBkg->bkgTrack->btk_is_sanitization_kit);

			//Updating Booking Pay docs for checksum
			$payDocModel	 = $this->payDocModel;
			$payDocResponse	 = $payDocModel->savePayDocs();
			if (!($payDocResponse && $btResponse))
			{
				throw new Exception("Unable to start", ReturnSet::ERROR_FAILED);
			}
			$message		 = "Sanitization kit picture taken!";
			$returnSet->setMessage($message);
			$this->scenario	 = 'syncBooking';
			if ($this->save()) //Booking track log save
			{
				$returnSet->setStatus(true);
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $blgeventId, false, $params);
				//BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			else
			{
				if ($this->errors)
				{
					$modelExistError = $this->errors['btl_event_type_id'][0];
					$returnSet->setMessage($modelExistError);
					$returnSet->setStatus(true);
				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
			Logger::exception($ex);
		}

		skipAll:
		return $returnSet;
	}

	public function updateArrogyaSetu()
	{
		$returnSet = new ReturnSet();
		if ($this->btlBkg->bkgTrack->bkg_ride_start)
		{
			$returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
			$returnSet->setMessage("Trip already started");
			goto skipAll;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bookingTrackModel				 = $this->btlBkg->bkgTrack; //Taking the booking track
			$eventId						 = BookingTrack::TRIP_ARROGYA_SETU;
			$blgeventId						 = BookingLog::COVID_CHECK;
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$btResponse						 = $bookingTrackModel->updateArrogyaSetu($this->btlBkg->bkgTrack->btk_aarogya_setu);
			if (!($btResponse))
			{
				throw new Exception("Unable to start", ReturnSet::ERROR_FAILED);
			}
			$message		 = "Aarogya setu cheking done!";
			$returnSet->setMessage($message);
			$this->scenario	 = 'syncBooking';
			if ($this->save()) //Booking track log save
			{
				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				$cabId	 = $booking->bkgBcb->bcb_cab_id;
				$eventId = $this->btl_event_type_id;
				if ($eventId == 8 || $eventId == 9)
				{
					VehicleStats::updateVerifyFlag($cabId);
				}

				$returnSet->setStatus(true);
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $blgeventId, false, $params);
				//BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			else
			{
				if ($this->errors)
				{
					$modelExistError = $this->errors['btl_event_type_id'][0];
					$returnSet->setMessage($modelExistError);
					$returnSet->setStatus(true);
				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
			Logger::exception($ex);
		}

		skipAll:
		return $returnSet;
	}

	public function updateTerms()
	{
		$returnSet = new ReturnSet();
		if ($this->btlBkg->bkgTrack->bkg_ride_start)
		{
			$returnSet->setStatus(true); //Set true, to ensure the local db of app gets updated if not earlier
			$returnSet->setMessage("Trip already started");
			goto skipAll;
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bookingTrackModel				 = $this->btlBkg->bkgTrack; //Taking the booking track
			$eventId						 = BookingTrack::TRIP_TERMS_AGREE;
			$blgeventId						 = BookingLog::COVID_CHECK;
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$btResponse						 = $bookingTrackModel->updateTerms($this->btlBkg->bkgTrack->btk_safetyterm_agree);
			if (!($btResponse))
			{
				throw new Exception("Unable to start", ReturnSet::ERROR_FAILED);
			}
			$message		 = "Update terms and conditions!";
			$returnSet->setMessage($message);
			$this->scenario	 = 'syncBooking';
			if ($this->save()) //Booking track log save
			{
				$returnSet->setStatus(true);
				BookingLog::model()->createLog($this->btl_bkg_id, $returnSet->getMessage() . ' at ' . $this->btl_sync_time, null, $blgeventId, false, $params);
				//BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);

				$booking = Booking::model()->findByPk($this->btl_bkg_id);
				if (($this->btlBkg->bkgTrail->btr_is_datadiscrepancy) > ($booking->bkgTrail->btr_is_datadiscrepancy))
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$this->btlBkg->bkgTrail->addDiscrepancy($this->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
			}
			else
			{
				if ($this->errors)
				{
					$modelExistError = $this->errors['btl_event_type_id'][0];
					$returnSet->setMessage($modelExistError);
					$returnSet->setStatus(true);
				}
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
			Logger::exception($ex);
		}
		skipAll:
		return $returnSet;
	}

	public function getInfoByEvent($bkgId)
	{
		$params = ['bkgId' => $bkgId];

		$sql = "SELECT btl_sync_time,btl_coordinates,btl_created,btl_event_type_id,btl_user_type_id,count(btl_event_type_id)as countEvent ,btl_user_id,(CASE
                WHEN (btl_user_type_id='1') THEN 'Consumers'
                WHEN (btl_user_type_id='2') THEN 'Vendor'
                WHEN (btl_user_type_id='3') THEN 'Driver'
                WHEN (btl_user_type_id='4') THEN 'Gozo'
                WHEN (btl_user_type_id='5') THEN 'Agent'
                WHEN (btl_user_type_id='10') THEN 'System'
                WHEN (btl_user_type_id='6') THEN 'Corporate'
                END) as user_type  FROM   booking_track_log
				WHERE btl_event_type_id IN(201,202,203,205,206,101,102,103,301,302,104) AND btl_bkg_id =:bkgId GROUP BY btl_event_type_id ORDER BY btl_id ASC  ";
		return DBUtil::queryAll($sql, DBUtil::MDB(), $params);
	}

	public function getAllPreviousEventByBkgId($bkgId)
	{
		$sql	 = "SELECT * FROM booking_track_log WHERE btl_event_type_id NOT IN (204,206) AND btl_bkg_id = $bkgId ";
		$result	 = DBUtil::command($sql)->queryAll();
		return $result;
	}

	public static function getNoShowBooking()
	{

		$sql	 = "SELECT btk_bkg_id, bkg_no_show_time  FROM booking_track
                    LEFT JOIN agent_api_tracking  ON aat_booking_id=btk_bkg_id AND aat_type <> 12
                    WHERE bkg_is_no_show = 1 AND DATE_ADD(bkg_no_show_time , INTERVAL 30 MINUTE) >= DATE_SUB(now(), interval 45 minute) GROUP BY btk_bkg_id";
		$result	 = DBUtil::command($sql)->queryAll();
		return $result;
	}

	public function getdetailsByChecksum($bkg_id, $event, $checksum)
	{
		if ((trim($bkg_id) == null || trim($bkg_id) == "") || (trim($checksum) == null || trim($checksum) == ""))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$params	 = array('bkg_id' => $bkg_id, 'event' => $event, 'checksum' => $checksum);
		$sql	 = "SELECT * FROM  booking_track_log btl where  btl.btl_bkg_id = :bkg_id AND btl_event_type_id = :event AND btl.btl_doc_checksum = :checksum ORDER  BY  btl_id DESC LIMIT 1";
		$data	 = DBUtil::queryRow($sql, DBUtil::MDB(), $params);
		return $data;
	}

	/**
	 * To check Api Discrepancy
	 * @param integer $bkgId
	 * @param string $request
	 */
	public static function checkApiDiscrepancy($bkgId, $request)
	{
		$sql		 = "SELECT COUNT(DISTINCT(dul_event_id)) AS cnt, GROUP_CONCAT(DISTINCT(dul_event_id)) AS event_ids FROM drv_unsync_log WHERE dul_bkg_id =:bkgId GROUP BY dul_bkg_id";
		$unSyncData	 = DBUtil::command($sql)->bindParam(':bkgId', $bkgId)->queryRow();

		$sql1 = "SELECT COUNT(DISTINCT(btl_event_type_id)) FROM booking_track_log WHERE btl_bkg_id =" . $bkgId;
		if (!empty($unSyncData['event_ids']))
		{
			$sql1 .= " AND btl_event_type_id IN(" . $unSyncData['event_ids'] . ")";
		}
		$trackCount = DBUtil::queryScalar($sql1);

		if ($trackCount < $unSyncData['cnt'])
		{
			BookingTrail::updateDrvApiSyncErrorFlag($bkgId, true);
			//\Sentry\captureException("Driver API sync Error :=> Request => " . json_encode($request));
		}
		else
		{
			BookingTrail::updateDrvApiSyncErrorFlag($bkgId, false);
		}
	}

	/**
	 * To process all steps for Arrive
	 * @param integer $bkgId
	 * @return bool
	 */
	public static function postEventArrive($bkgId)
	{
		$event		 = BookingTrack::DRIVER_ARRIVED;
		$btlData	 = BookingTrackLog::getByBkg($bkgId, $event);
		$btlModel	 = BookingTrackLog::model()->findByPk($btlData[0]['btl_id']);

		if (!empty($btlModel))
		{
			if ($btlModel->btl_user_type_id == 3)
			{
				$platformId = 2;
			}
			//vendor
			if ($btlModel->btl_user_type_id == 2)
			{
				$platformId = 4;
			}
			$platformArr	 = TripOtplog::platformArr;
			$platformName	 = $platformArr[$platformId];
			$transaction	 = DBUtil::beginTransaction();
			$booking		 = Booking::model()->findByPk($btlModel->btl_bkg_id);
			try
			{
				if ($btlModel->btl_is_discrepancy > 0)
				{
					$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
					$btlModel->btlBkg->bkgTrail->addDiscrepancy($btlModel->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
				}
				else
				{
					/*  CLEAR DISPATCH  */
					ServiceCallQueue::clearDispatch(ServiceCallQueue::TYPE_DISPATCH, 3, $btlModel->btl_bkg_id, "Clear dispatch | Driver arrived");
				}
				Vendors::model()->amountPenalties($btlModel->btlBkg->bkgTrack, $platformName, $btlModel->btl_event_type_id, $btlModel);
				if ($btlModel->btlBkg->bkg_agent_id != '' && Agents::isApiKeyAvailable($btlModel->btlBkg->bkg_agent_id))
				{
					$typeAction = AgentApiTracking::TYPE_ARRIVED;
					AgentMessages::model()->pushApiCall($btlModel->btlBkg, $typeAction);
				}
				$updateStatus	 = BookingTrack::updateLastStatus($btlModel->btl_bkg_id, $btlModel->btl_event_type_id, $btlModel->btl_coordinates, $btlModel->btl_sync_time);
				Logger::trace("Save to bookingTrack Arrived" . $btlModel->btl_bkg_id . $updateStatus);
				$msg			 = "Check update last location 203 bkg:: " . $btlModel->btl_bkg_id . "event::" . $btlModel->btl_event_type_id . "result::" . $updateStatus;

				Logger::info($msg);

				DBUtil::commitTransaction($transaction);
				return true;
			}
			catch (Exception $exc)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($exc);
			}
		}
	}

	/**
	 * To process all steps for Start
	 * @param integer $bkgId
	 * @return bool
	 */
	public static function postEventStart($bkgId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$btlData	 = BookingTrackLog::model()->getByBkg($bkgId, BookingTrack::TRIP_START);
		$btlModel	 = BookingTrackLog::model()->findByPk($btlData[0]['btl_id']);
		if ($btlModel->btl_user_type_id == 3)
		{
			$platformId = 2;
		}
		//vendor
		if ($btlModel->btl_user_type_id == 2)
		{
			$platformId = 4;
		}
		$platformArr	 = TripOtplog::platformArr;
		$platformName	 = $platformArr[$platformId];
//		ServiceCallQueue::autoFURTripStarted($btlModel->btl_bkg_id);
//		ServiceCallQueue::autoFURTripStartedForB2B($btlModel->btl_bkg_id);
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			Vendors::model()->amountPenalties($btlModel->btlBkg->bkgTrack, $platformName, $btlModel->btl_event_type_id, $btlModel);
			$updateStatus	 = BookingTrack::updateLastStatus($btlModel->btl_bkg_id, $btlModel->btl_event_type_id, $btlModel->btl_coordinates, $btlModel->btl_sync_time);
			Logger::trace("Save to bookingTrack start " . $btlModel->btl_bkg_id . $updateStatus);
			$msg			 = "Check update last location 101 bkg:: " . $btlModel->btl_bkg_id . "event::" . $btlModel->btl_event_type_id . "result::" . $updateStatus;
			Logger::info($msg);
			$booking		 = Booking::model()->findByPk($btlModel->btl_bkg_id);
			// block vendor payment if kilometer range is above 50 Km. from Start to pickup Location
			if ($booking->bkg_id)
			{
				$bkgId				 = $booking->bkg_id;
				$calculateDistance	 = BookingRoute::calcDistance($bkgId);
				if ($calculateDistance > 50)
				{
					$remarks = "Payment Locked (Because of  arriving location from starting cab location is) " . $calculateDistance . " Km";
					Vendors::stopVendorPayment($bkgId, $remarks);
				}
			}
			if ($btlModel->btl_is_discrepancy > 0)
			{
				$escalationRemark	 = $escalationRemark	 = "Manual inspection of Driver app required. Driver's actual location did not match trip expected start location. Check if driver was trying to cheat the system. (Manual inspection needed by Dispatch team). If found OK then you can release payment. ";
				$btlModel->btlBkg->bkgTrail->addDiscrepancy($btlModel->btl_event_type_id, $escalationRemark, $booking->bkg_bcb_id, 0);
			}
			else
			{
				/*  CLEAR DISPATCH  */
				ServiceCallQueue::clearDispatch(ServiceCallQueue::TYPE_DISPATCH, 3, $btlModel->btl_bkg_id, "Clear dispatch | Trip start");
			}
			if ($btlModel->btlBkg->bkg_agent_id != '' && $btlModel->btlBkg->bkgTrack->bkg_ride_start == 1 && Agents::isApiKeyAvailable($btlModel->btlBkg->bkg_agent_id))
			{
				$typeAction = AgentApiTracking::TYPE_TRIP_START;
				AgentMessages::model()->pushApiCall($btlModel->btlBkg, $typeAction);
			}
			BookingTrail::model()->updateDriverScore($btlModel->btl_bkg_id, $btlModel->btl_event_type_id);
			DBUtil::commitTransaction($transaction);
			return true;
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	/**
	 *  To process all steps for Stop
	 * @param BookingTrackLog $model
	 */
	public static function postEventStop($bkgId)
	{
		$btlData	 = BookingTrackLog::model()->getByBkg($bkgId, BookingTrack::TRIP_STOP);
		$model		 = BookingTrackLog::model()->findByPk($btlData[0]['btl_id']);
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($model->btlBkg->bkg_status != 6)
			{
				/*  CLEAR auto fur for trip start for Upsell/B2B Post Pickup start */
				if ($model->btlBkg->bkg_agent_id == null && in_array($model->btlBkg->bkgSvcClassVhcCat->scv_scc_id, [1, 6]))
				{
					ServiceCallQueue::clearExisting(ServiceCallQueue::TYPE_UPSELL, 3, $bkgId, "Clear Upsell | Trip Stop");
				}
				else if ($model->btlBkg->bkg_agent_id == null && !in_array($model->btlBkg->bkgSvcClassVhcCat->scv_scc_id, [1, 6]))
				{
					ServiceCallQueue::clearExisting(ServiceCallQueue::TYPE_UPSELL_UPPERTIER, 3, $bkgId, "Clear Upsell | Trip Stop");
				}
				else if ($model->btlBkg->bkg_agent_id != null)
				{
					ServiceCallQueue::clearExisting(ServiceCallQueue::TYPE_B2B_POST_PICKUP, 3, $bkgId, "Autoclear Post pickup CBR @Trip Stop Event");
				}
				/*  CLEAR auto fur for trip start for Upsell/B2B Post Pickup ends */
			}
			else
			{
				$description								 = "Already marked completed manually before driver synced the data.";
				$trailModel									 = $model->btlBkg->bkgTrail;
				$trailModel->bkg_escalation_status			 = 1;
				$trailModel->btr_escalation_level			 = 2;
				$trailModel->btr_escalation_assigned_team	 = 9;
				$trailModel->btr_escalation_assigned_lead	 = 177;
				/* if extra charge then escalation */
				if ($model->btlBkg->bkgInvoice->bkg_extra_km_charge)
				{
					$trailModel->updateEscalation("System mark completed earlier than driver", UserInfo::model(), $description);
				}
				$success = BookingLog::model()->createLog($model->btl_bkg_id, $description, NULL, BookingLog::ALREADY_RIDE_COMPLETE);
			}
			$updateStatus	 = BookingTrack::updateLastStatus($model->btl_bkg_id, $model->btl_event_type_id, $model->btl_coordinates, $model->btl_sync_time);
			$msg			 = "Check update last location 104 bkg:: " . $btlModel->btl_bkg_id . "event::" . $btlModel->btl_event_type_id . "result::" . $updateStatus;

			Logger::trace("Save to bookingTrack stop" . $btlModel->btl_bkg_id . $updateStatus);
			if ($model->btlBkg->bkgTrack->bkg_ride_complete == 1 && $model->btlBkg->bkg_agent_id != '' && Agents::isApiKeyAvailable($model->btlBkg->bkg_agent_id))
			{
				$typeAction = AgentApiTracking::TYPE_TRIP_END;
				AgentMessages::model()->pushApiCall($model->btlBkg, $typeAction);
			}
			DBUtil::commitTransaction($transaction);
			return true;
		}
		catch (Exception $exc)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($exc);
		}
	}

	/**
	 * This function is used for stopping or ending the trip
	 * @param Stub\booking\SyncRequest $obj Description
	 * @return \ReturnSet
	 */
	public function stopTrip($obj = null, $dco = null)
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		Logger::setModelCategory(_CLASS_, _FUNCTION_);
		try
		{
			$btrModel			 = $this->btlBkg->bkgTrack;
			$bookingTrackModel	 = $btrModel;
			$bkgModel			 = $this->btlBkg;
			$vehicleModel		 = $bkgModel->bkgBcb->bcbCab;
			$bkgInvoiceModel	 = $bkgModel->bkgInvoice;

			if (!$btrModel->bkg_ride_start)
			{
				throw new Exception("Trip not started yet. Trip can't be stopped", ReturnSet::ERROR_FAILED);
			}
			if ($btrModel->bkg_ride_complete == 1 || in_array($bkgModel->bkg_status, [6, 7]))
			{
				Logger::trace("Trip already Completed booking id " . $bkgModel->bkg_id);
				$returnSet->setStatus(true);
				$returnSet->setMessage("Trip already Completed");
				goto skipAllCode;
			}

			$extraCharges = $obj->transaction->getExtraCharges();

			// Log Extra Charges
			$flgLogExtraCharges = self::logExtraCharges($bkgInvoiceModel->bkg_total_amount, $obj->transaction->amountCollected, $extraCharges);
			if ($flgLogExtraCharges)
			{
				Logger::pushTraceLogs();
				Logger::warning("ExtraCharges Data:  " . json_encode($extraCharges));
				Logger::warning("AmountCollected Data:  " . $obj->transaction->amountCollected);
			}

			// Checking when trip started by Admin & there is no start odometer and trip stop with extra km charges
			if ($extraCharges->kmCharges > 0 || $extraCharges->km > 0)
			{
				$rowStartTripEvent = $this->getdetailByEvent($bkgModel->bkg_id, BookingTrack::TRIP_START);
				if ($rowStartTripEvent && $rowStartTripEvent['btl_user_type_id'] == 4 && $btrModel->bkg_start_odometer == null)
				{
					$extraCharges->kmCharges = 0;
					$extraCharges->km		 = 0;
				}
			}

			BookingInvoice::prepareInvoice($this->btlBkg->bkg_id, $extraCharges, $obj->transaction->amountCollected);

			if ($this->btlBkg->bkgBcb->bcb_vendor_id != Config::get("hornok.operator.id"))
			{
				if ($dco != 1)
				{
					$payDocModel	 = $this->payDocModel;
					$payDocResponse	 = $payDocModel->savePayDocs();
					$bkgInvoiceModel->verifyInvoice();
				}
			}
			Logger::trace("stopTrip 1" . $this->btlBkg->bkg_id);
			$result = $bookingTrackModel->markTripEnd();
			Logger::trace("stopTrip 2" . $this->btlBkg->bkg_id . "result markTripEnd" . $result);
			if ($vehicleModel != '')
			{
				$vehicleModel->saveEndOdometer();
				try
				{
					$vehicleStats = VehicleStats::model()->getbyVehicleID($vehicleModel->vhc_id);
					if (!empty($vehicleStats))
					{
						$vehicleStats->vhs_last_completed_bkg_id			 = $this->btlBkg->bkg_id;
						$vehicleStats->vhs_last_completed_latlong			 = $btrModel->bkg_trip_end_coordinates;
						$vehicleStats->vhs_last_completed_date				 = $btrModel->bkg_trip_end_time;
						$vehicleStats->vhs_last_odometer_reading			 = $btrModel->bkg_end_odometer;
						$vehicleStats->vhs_last_odometer_reading_location	 = $btrModel->bkg_trip_end_coordinates;
						$vehicleStats->vhs_last_odometer_reading_date		 = $btrModel->bkg_trip_end_time;

						$vehicleStats->save();
						Logger::trace("vehicleStats save" . $vehicleStats->vhs_last_completed_bkg_id);
					}
				}
				catch (Exception $ex)
				{
					$returnSet = ReturnSet::setException($ex);
				}
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage("Trip completed successfully");
			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $bkgModel->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			$this->scenario					 = 'syncBooking';
			$userInfo		 = UserInfo::getInstance();
			if ($this->save())
			{
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				$odometerRemarks = " (Odometer end value:  " . $btrModel->bkg_end_odometer . ")";
				$descLog		 = $returnSet->getMessage() . ' at ' . $this->btl_sync_time . $odometerRemarks;
				BookingLog::model()->createLog($this->btl_bkg_id, $descLog, $userInfo, $oldEventId, false, $params);
			}
			else
			{
				throw new Exception(json_encode($this->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if ($this->btlBkg->bkgBcb->bcb_vendor_id != Config::get("hornok.operator.id"))
			{
				Logger::trace("addPostEvent" . $this->btl_bkg_id);
				BookingScheduleEvent::addPostEvent($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_sync_time);
			}
			notificationWrapper::customerNotifyTripCompleted($this->btl_bkg_id);
			$bkgModel->setAccountMismatchFlag($this->btl_bkg_id);
			DBUtil::commitTransaction($transaction);
			Logger::trace("stopTrip end" . $this->btlBkg->bkg_id);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		skipAllCode:
		Logger::unsetModelCategory(_CLASS_, _FUNCTION_);
		return $returnSet;
	}

	public static function logExtraCharges($totalAmount, $amountCollected, $extraCharges)
	{
		$result		 = false;
		$totAmt		 = $totalAmount * 2;
		$minAmount	 = min(50000, $totAmt);

		if ($minAmount > 0 && ($amountCollected > $minAmount || $extraCharges->kmCharges > $minAmount))
		{
			$result = true;
		}
		return $result;
	}

	/**
	 * 
	 * @param type $event
	 * @return string
	 */
	public static function getClassByTripEvent($event)
	{
		switch ($event)
		{
			case (in_array($event, [101, 302, 103])):
				$trackClass	 = "timeline-icon-success";
				break;
			case (in_array($event, [102, 204, 206, 301])):
				$trackClass	 = "timeline-icon-light";
				break;
			case (in_array($event, [104, 205])):
				$trackClass	 = "timeline-icon-danger";
				break;
			case 201:
				$trackClass	 = "timeline-arrow";
				break;
			case (in_array($event, [202, 203])):
				$trackClass	 = "timeline-icon-primary";
				break;
			default:
				break;
		}
		return $trackClass;
	}

	public static function getFileDocs($model, $data, $fileArr)
	{
		$fileType				 = $fileArr->refType;
		$paydocs				 = new BookingPayDocs();
		$paydocs->bpay_bkg_id	 = $model->btl_bkg_id;
		$paydocs->bpay_status	 = 2;
		$paydocs->bpay_app_type	 = UserInfo::$platform;
		$pdate					 = date("Y-m-d H:i:s", strtotime($data->createDate));

		switch ($fileType)
		{
			case SELFI_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::TRIP_SELFIE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case BookingTrack::TRIP_SANITIZER_KIT:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $data->refValue;
				$paydocs->bpay_type			 = BookingTrack::TRIP_SANITIZER_KIT;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case ODOMETER_START_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::ODOMETER_START_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case ODOMETER_STOP_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::ODOMETER_STOP_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case STATE_TAX_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::STATE_TAX_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case TOLL_TAX_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::TOLL_TAX_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case DUTY_SLIP_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::DUTY_SLIP_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case PARKING_CHARGES_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::PARKING_CHARGES_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case OTHERS_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::OTHERS_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;

			case CAB_FRONT_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::CAB_FRONT_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
			case CAB_BACK_FILE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $fileArr->refValue;
				$paydocs->bpay_type			 = BookingTrack::CAB_BACK_FILE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;
		}
		$model->payDocModel = $paydocs;
		return $model;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function getDocs($model, $data)
	{
		$paydocs				 = new BookingPayDocs();
		$paydocs->bpay_bkg_id	 = $model->btl_bkg_id;
		$paydocs->bpay_status	 = 2;
		$paydocs->bpay_app_type	 = UserInfo::$platform;
		$pdate					 = date("Y-m-d H:i:s", strtotime($data->createDate));

		switch ($model->btl_event_type_id)
		{
			case BookingTrack::TRIP_START:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $data->odometer->frontPath;
				$paydocs->bpay_checksum		 = $data->odometer->checksum;
				$paydocs->bpay_type			 = BookingTrack::TRIP_START;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;

			case BookingTrack::TRIP_SELFIE:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $data->selfie->frontPath;
				$paydocs->bpay_checksum		 = $data->selfie->checksum;
				$paydocs->bpay_type			 = BookingTrack::TRIP_SELFIE;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;

			case BookingTrack::TRIP_STOP:
				$paydocs->bpay_date			 = new CDbExpression("NOW()");
				$paydocs->bpay_image		 = $data->odometer->frontPath;
				$paydocs->bpay_checksum		 = $data->odometer->checksum;
				$paydocs->bpay_type			 = BookingTrack::TRIP_STOP;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;

			case BookingTrack::TRIP_SANITIZER_KIT:
				$paydocs->bpay_date			 = $pdate;
				$paydocs->bpay_image		 = $data->covidSafety->frontPath;
				$paydocs->bpay_checksum		 = $data->covidSafety->checksum;
				$paydocs->bpay_type			 = BookingTrack::TRIP_SANITIZER_KIT;
				$paydocs->bpay_device_info	 = $model->btl_device_info;
				break;

			case BookingTrack::VOUCHER_UPLOAD:
				$paydocs->bpay_date		 = $pdate;
				$paydocs->bpay_image	 = $data->odometer->frontPath;
				$paydocs->bpay_checksum	 = $data->odometer->checksum;
				$paydocs->bpay_type		 = $data->odometer->refValue;
				break;
		}
		$model->payDocModel = $paydocs;
		return $model;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function getDocsValue($model, $data)
	{
		$paydocs				 = new BookingPayDocs();
		$paydocs->bpay_bkg_id	 = $model->btl_bkg_id;
		$paydocs->bpay_status	 = 2;
		$paydocs->bpay_app_type	 = UserInfo::$platform;
		#$pdate                       = date("Y-m-d H:i:s", strtotime($data->createDate));
		$pdate					 = $data->createDate;
		switch ($model->btl_event_type_id)
		{
			case BookingTrack::TRIP_START:
				$paydocs->bpay_date	 = $pdate;
				$paydocs->bpay_type	 = BookingTrack::TRIP_START;

				break;

			case BookingTrack::TRIP_SELFIE:
				$paydocs->bpay_date		 = $pdate;
				$paydocs->bpay_image	 = $data->selfie->frontPath;
				$paydocs->bpay_checksum	 = $data->selfie->checksum;
				$paydocs->bpay_type		 = BookingTrack::TRIP_SELFIE;
				break;

			case BookingTrack::TRIP_STOP:
				$paydocs->bpay_date		 = new CDbExpression("NOW()");
				$paydocs->bpay_image	 = $data->odometer->frontPath;
				$paydocs->bpay_checksum	 = $data->odometer->checksum;
				$paydocs->bpay_type		 = BookingTrack::TRIP_STOP;
				break;

			case BookingTrack::TRIP_SANITIZER_KIT:
				$paydocs->bpay_date		 = $pdate;
				$paydocs->bpay_image	 = $data->covidSafety->frontPath;
				$paydocs->bpay_checksum	 = $data->covidSafety->checksum;
				$paydocs->bpay_type		 = BookingTrack::TRIP_SANITIZER_KIT;
				break;

			case BookingTrack::VOUCHER_UPLOAD:
				$paydocs->bpay_date		 = $pdate;
				$paydocs->bpay_image	 = $data->odometer->frontPath;
				$paydocs->bpay_checksum	 = $data->odometer->checksum;
				$paydocs->bpay_type		 = $data->odometer->refValue;
				break;
		}
		foreach ($data->data as $eventData)
		{
			if ($eventData->refType == 'StartOdoValue')
			{
				$paydocs->bpay_date	 = $pdate;
				$paydocs->bpay_image = $eventData->refValue;
			}
		}
		$model->payDocModel = $paydocs;
		return $model;
	}

	public static function getDocsData($data)
	{
		foreach ($data as $eventData)
		{
			if ($eventData->refType == 'StartOdoValue')
			{
				$paydocs->bpay_date	 = $pdate;
				$paydocs->bpay_image = $eventData->refValue;
			}
			return $paydocs;
		}
	}

	public static function showEventTypes($tripId)
	{
		$params = ['tripId' => $tripId];

		$sql = "SELECT btl_bkg_id, btl_event_type_id,btl_sync_time,btl_created FROM  booking_track_log   
			WHERE btl_bcb_id = :tripId ORDER BY btl_id ";

		$recordSet = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $recordSet;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return array
	 */
	public function getEventByBkgId($bkgId)
	{
		$params	 = ['bkgId' => $bkgId];
		$sql	 = "SELECT * FROM booking_track_log WHERE btl_event_type_id IN (201,203,101,104) AND btl_bkg_id = :bkgId ";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
		return $result;
	}

	/**
	 * 
	 * @param type $bkgModel
	 * @param type $cordinates
	 * @param type $response
	 * @param type $operatorId
	 * @param type $event
	 * @return boolean
	 */
	public static function updateLogDetails($bkgModel, $cordinates, $response, $operatorId, $event)
	{
		$sucess		 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$ttgModel				 = new BookingTrackLog();
			$ttgModel->btl_bkg_id	 = $bkgModel->bkg_id;
			$ttgModel->btl_bcb_id	 = $bkgModel->bkgBcb->bcb_id;
			switch ($event)
			{
				case BookingTrack::GOING_FOR_PICKUP:
					$ttgModel->btl_event_type_id = $event;
					$ttgModel->btl_coordinates	 = $cordinates;
					$ttgModel->btl_sync_time	 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					$ttgModel->btl_created		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					break;
				case BookingTrack::TRIP_STOP:
					$ttgModel->btl_event_type_id = $event;
					$ttgModel->btl_coordinates	 = $cordinates;
					$ttgModel->btl_sync_time	 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					$ttgModel->btl_created		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					break;
				case BookingTrack::TRIP_START:
					$ttgModel->btl_event_type_id = $event;
					$ttgModel->btl_coordinates	 = $cordinates;
					$ttgModel->btl_sync_time	 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					$ttgModel->btl_created		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					break;
				case BookingTrack::DRIVER_ARRIVED:
					$ttgModel->btl_event_type_id = $event;
					$ttgModel->btl_coordinates	 = $cordinates;
					$ttgModel->btl_sync_time	 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					$ttgModel->btl_created		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					break;
				default:
				//
			}

			if ($ttgModel->save())
			{
				$sucess = true;
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			Logger::exception($ex);
		}
		return $sucess;
	}

	/**
	 * This function is used for updating the driver position for a trip
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function tripPosition()
	{
		$returnSet = new ReturnSet();

		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$this->btlBkg->bkgTrack->bkg_ride_start)
			{
				throw new Exception("Trip not started yet.", ReturnSet::ERROR_FAILED);
			}
			if ($this->btlBkg->bkgTrack->bkg_ride_complete == 1 || in_array($bkgModel->bkg_status, [6, 7]))
			{
				throw new Exception("Trip already Completed.", ReturnSet::ERROR_FAILED);
			}

			$desc = "Trip position." . $this->btl_coordinates . " and " . $this->btl_sync_time;

			$returnSet->setStatus(true);
			$returnSet->setMessage($desc);

			$bookingLogEvent				 = BookingLog::mapEvents();
			$oldEventId						 = $bookingLogEvent[$this->btl_event_type_id]; //For booking log table
			$params["blg_booking_status"]	 = $this->btlBkg->bkg_status;
			$params['current_user_type']	 = (int) UserInfo::TYPE_DRIVER;
			/**
			 * Write
			 * 1 -	Booking Log
			 * 2 -	Booking trip track log
			 */
			$this->scenario					 = 'syncBooking';
			if ($this->save())
			{
				BookingTrack::updateLastStatus($this->btl_bkg_id, $this->btl_event_type_id, $this->btl_coordinates, $this->btl_sync_time);
				BookingLog::model()->createLog($this->btl_bkg_id, $desc, null, $oldEventId, false, $params);
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

	public function addRemarks($bkgId, $remarks, $cordinates, UserInfo $userInfo, $platform)
	{
		$bModel						 = Booking::model()->findByPk($bkgId);
		$model						 = new BookingTrackLog();
		$model->btl_user_id			 = $userInfo::getUserId();
		$model->btl_user_type_id	 = $userInfo::getUserType();
		$model->btl_bkg_id			 = $bkgId;
		$model->btl_bcb_id			 = $bModel->bkg_bcb_id;
		$model->btl_event_type_id	 = BookingTrack::REMARKS_ADDED;
		$model->btl_coordinates		 = $cordinates;
		$model->btl_remarks			 = $remarks;
		$model->btl_created			 = new CDbExpression('NOW()');
		$model->btl_event_platform	 = $platform;
		$model->scenario			 = 'addRemarks';
		$model->save();
		return $model;
	}

	public function saveData()
	{
		$this->btl_created	 = new CDbExpression('NOW()');
		$this->scenario		 = 'addRemarks';
		return $this->save();
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $event
	 * @return type
	 */
	public function getByBookingId($bkgId, $event)
	{
		$params	 = ['bkgId' => $bkgId, 'event' => $event];
		$sql	 = "SELECT btl_id FROM  booking_track_log btl where  btl.btl_bkg_id = $bkgId AND btl_event_type_id = {$event}";
		$data	 = DBUtil::queryScalar($sql, DBUtil:: SDB(), $params);
		return $data;
	}

}
