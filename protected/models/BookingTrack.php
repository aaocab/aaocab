<?php

/**
 * This is the model class for table "booking_track".
 *
 * The followings are the available columns in table 'booking_track':
 * @property integer $btk_id
 * @property integer $btk_bkg_id
 * @property integer $bkg_ride_start
 * @property integer $bkg_ride_complete
 * @property string $bkg_no_show_time  
 * @property string $bkg_trip_start_time
 * @property string $bkg_trip_end_time
 * @property string $bkg_trip_start_coordinates
 * @property string $bkg_trip_end_coordinates
 * @property integer $bkg_start_odometer
 * @property integer $bkg_end_odometer
 * @property integer $btk_start_platform
 * @property integer $btk_end_platform
 * @property string $bkg_trip_otp
 * @property integer $bkg_sos_sms_trigger
 * @property integer $bkg_is_trip_verified
 * @property integer $bkg_garage_time
 * @property string $bkg_reported_time
 * @property string $bkg_waiting_time
 * @property string $bkt_log_file_path
 * @property string $btl_created
 * @property string $btl_modified
 * @property string $bkg_sos_start_coordinates
 * @property string $bkg_sos_end_coordinates
 * @property integer $bkg_arrived_for_pickup
 * @property integer $btk_last_event
 * @property integer $btk_last_event_time
 * @property string $btk_last_coordinates
 * @property integer $btk_cust_details_viewed
 * @property string $btk_cust_details_viewed_datetime
 * @property integer $btk_drv_details_viewed
 * @property string $btk_drv_details_viewed_datetime
 * 
 * @property integer $bkg_vendor_pickup_confirm
 * @property integer $bkg_driver_pickup_confirm
 * 
 * The followings are the available model relations:
 * @property Booking $btkBkg
 */
class BookingTrack extends CActiveRecord
{

	const OTP_VERIFY			 = 100;
	const TRIP_START			 = 101;
	const TRIP_PAUSE			 = 102;
	const TRIP_RESUME			 = 103;
	const TRIP_STOP			 = 104;
	const GOING_FOR_PICKUP	 = 201; //sub
	const NOT_GOING_FOR_PICKUP = 202; //sub
	const DRIVER_ARRIVED		 = 203; //sub
	const NO_SHOW				 = 204; //sub
	const WAIT				 = 205;
	const NO_SHOW_RESET		 = 206; //sub
	const SOS_START			 = 301;
	const SOS_RESOLVED		 = 302;
	const CAR_BREAKDOWN		  = 303;
	const VOUCHER_UPLOAD		 = 503;
	const VOUCHER_DELETED		 = 504;
	const TRIP_SELFIE			 = 107;
	const TRIP_SANITIZER_KIT	 = 108;
	const TRIP_ARROGYA_SETU	 = 109;
	const TRIP_TERMS_AGREE	 = 110;
	const SOS_ON_NOTIFICATION	 = 533;
	const SOS_OFF_NOTIFICATION = 534;
	

//Extra evnts for DCO App

	const ODOMETER_START_FILE	 = 105;
	const ODOMETER_STOP_FILE	 = 106;
	const CAB_FRONT_FILE		 = 8;
	const CAB_BACK_FILE		 = 9;
	const CAB_LEFT_FILE		 = 153;
	const CAB_RIGHT_FILE		 = 154;
	const TOLL_TAX_FILE		 = 2;
	const STATE_TAX_FILE		 = 1;
	const PARKING_CHARGES_FILE = 3;
	const DUTY_SLIP_FILE		 = 5;
	const OTHERS_FILE			 = 4;
	const TOLL_TAX_FILE_DELETE		 = 261;
	const STATE_TAX_FILE_DELETE		 = 262;
	const PARKING_CHARGES_FILE_DELETE	 = 263;
	const DUTY_SLIP_FILE_DELETE		 = 264;
	const OTHERS_FILE_DELETE			 = 260;
	const FILE_UPLOAD					 = 300;
	const TRIP_POSITION				 = 111;
	const REMARKS_ADDED = 29;

	public $fromDate, $toDate, $partner, $bkg_sos_remarks, $book_time;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking_track';
	}

	public $documentType;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
//		return array(
//			array('btk_bkg_id', 'required'),
//			array('btk_bkg_id, bkg_is_driver_loggedIn, bkg_arrived_for_pickup, bkg_ride_start, bkg_ride_complete, bkg_start_odometer, bkg_end_odometer, bkg_is_trip_verified,bkg_garage_time', 'numerical', 'integerOnly' => true),
//			array('bkg_trip_start_lat, bkg_trip_end_lat', 'length', 'max' => 10),
//			array('bkg_trip_start_long, bkg_trip_end_long', 'length', 'max' => 11),
//			array('bkg_start_odometer_path', 'length', 'max' => 255),
//			array('bkg_end_odometer_path', 'length', 'max' => 500),
//			array('bkg_trip_start_time, bkg_trip_end_time', 'safe'),
//			array('bkg_trip_otp', 'length', 'max' => 10),
//			array('bkg_sos_device_id', 'checkSosApp', 'on' => 'validateSosApp'),
//			array('bkg_sos_remarks', 'checkSosWeb', 'on' => 'validateSosWeb'),
//			// The following rule is used by search().
//// @todo Please remove those attributes that should not be searched.
//			array('btk_id, btk_bkg_id, bkg_is_driver_loggedIn, bkg_arrived_for_pickup, bkg_ride_start, bkg_ride_complete, bkg_trip_start_time, bkg_trip_end_time, bkg_trip_start_lat, bkg_trip_start_long, bkg_trip_end_lat, bkg_trip_end_long, bkg_start_odometer, bkg_start_odometer_path, bkg_end_odometer, bkg_end_odometer_path, bkg_trip_otp, bkg_is_trip_verified, bkg_garage_time,fromDate,toDate,partner,bkg_trip_end_user_id,bkg_trip_end_user_type,bkg_sos_longitude,bkg_sos_latitude,bkg_sos_disable_datetime,bkg_sos_sms_trigger,bkg_sos_enable_datetime,bkg_sos_device_id,bkg_drv_sos_longitude,bkg_drv_sos_latitude,bkg_drv_sos_disable_datetime,bkg_drv_sos_sms_trigger,bkg_drv_sos_enable_datetime,bkg_drv_sos_device_id', 'safe'),
//		);
		return array(
			array('btk_bkg_id', 'required'),
			array('btk_bkg_id, bkg_ride_start, bkg_ride_complete, bkg_start_odometer, bkg_end_odometer, bkg_is_trip_verified, bkg_garage_time', 'numerical', 'integerOnly' => true),
			array('bkg_trip_otp', 'length', 'max' => 10),
			//array('bkg_start_odometer_path', 'length', 'max' => 255),
			//array('bkg_end_odometer_path', 'length', 'max' => 500),
			array('bkg_trip_start_time, bkg_trip_end_time, bkg_trip_start_coordinates, bkg_trip_end_coordinates, bkg_reported_time, bkg_waiting_time, bkt_log_file_path, btl_created, btl_modified,btk_last_event , btk_last_event_time, btk_last_coordinates,btk_is_selfie,btk_is_sanitization_kit,btk_aarogya_setu,btk_gpx_file,btk_gpx_s3_data', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('btk_id, btk_bkg_id, bkg_ride_start, bkg_ride_complete, bkg_no_show_time, bkg_trip_start_time, bkg_trip_end_time, bkg_trip_start_coordinates, bkg_trip_end_coordinates, bkg_start_odometer, bkg_end_odometer, btk_start_platform, btk_end_platform, bkg_trip_otp, bkg_is_trip_verified, bkg_garage_time, bkg_reported_time, bkg_waiting_time, bkt_log_file_path, btl_created, btl_modified, bkg_sos_start_coordinates, bkg_sos_end_coordinates, btk_cust_details_viewed, btk_cust_details_viewed_datetime, btk_drv_details_viewed, btk_drv_details_viewed_datetime,btk_is_selfie,btk_is_sanitization_kit,btk_aarogya_setu,bkg_arrived_for_pickup,btk_gpx_file,btk_gpx_s3_data', 'safe', 'on' => 'search'),
		);
	}

	public function checkIsVerified($attribute, $param)
	{
		if ($this->bkg_is_trip_verified == 1)
		{
			$this->addError("bkg_is_trip_verified", "Your OTP already verified.");
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
			'btkBkg' => array(self::BELONGS_TO, 'Booking', 'btk_bkg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'btk_id'					 => 'Btk',
			'btk_bkg_id'				 => 'Btk Bkg',
			'bkg_ride_start'			 => 'Bkg Ride Start',
			'bkg_ride_complete'			 => 'Bkg Ride Complete',
			'bkg_trip_start_time'		 => 'Bkg Trip Start Time',
			'bkg_trip_end_time'			 => 'Bkg Trip End Time',
			'bkg_trip_start_coordinates' => 'Bkg Trip Start Coordinates',
			'bkg_trip_end_coordinates'	 => 'Bkg Trip End Coordinates',
			'bkg_start_odometer'		 => 'Bkg Start Odometer',
			'bkg_end_odometer'			 => 'Bkg End Odometer',
			'btk_start_platform'		 => 'Btk Start Platform',
			'btk_end_platform'			 => 'Btk End Platform',
			'bkg_trip_otp'				 => 'Bkg Trip Otp',
			'bkg_is_trip_verified'		 => 'Bkg Is Trip Verified',
			'bkg_garage_time'			 => 'Bkg Garage Time',
			'bkg_reported_time'			 => 'Bkg Reported Time',
			'bkg_waiting_time'			 => 'Bkg Waiting Time',
			'bkt_log_file_path'			 => 'Bkt Log File Path',
			'btl_created'				 => 'Btl Created',
			'btl_modified'				 => 'Btl Modified',
			'btk_gpx_file'				 => 'Btk GPX File',
			'btk_gpx_s3_data'			 => 'Btk GPX S3 Data',
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

		$criteria->compare('btk_id', $this->btk_id);
		$criteria->compare('btk_bkg_id', $this->btk_bkg_id);
		$criteria->compare('bkg_ride_start', $this->bkg_ride_start);
		$criteria->compare('bkg_ride_complete', $this->bkg_ride_complete);
		$criteria->compare('bkg_trip_start_time', $this->bkg_trip_start_time, true);
		$criteria->compare('bkg_trip_end_time', $this->bkg_trip_end_time, true);
		$criteria->compare('bkg_trip_start_coordinates', $this->bkg_trip_start_coordinates, true);
		$criteria->compare('bkg_trip_end_coordinates', $this->bkg_trip_end_coordinates, true);
		$criteria->compare('bkg_start_odometer', $this->bkg_start_odometer);
		$criteria->compare('bkg_end_odometer', $this->bkg_end_odometer);
		$criteria->compare('btk_start_platform', $this->btk_start_platform);
		$criteria->compare('btk_end_platform', $this->btk_end_platform);
		$criteria->compare('bkg_trip_otp', $this->bkg_trip_otp, true);
		$criteria->compare('bkg_is_trip_verified', $this->bkg_is_trip_verified);
		$criteria->compare('bkg_garage_time', $this->bkg_garage_time);
		$criteria->compare('bkg_reported_time', $this->bkg_reported_time, true);
		$criteria->compare('bkg_waiting_time', $this->bkg_waiting_time, true);
		$criteria->compare('bkt_log_file_path', $this->bkt_log_file_path, true);
		$criteria->compare('btl_created', $this->btl_created, true);
		$criteria->compare('btl_modified', $this->btl_modified, true);
		$criteria->compare('btk_gpx_file', $this->btk_gpx_file, true);
		$criteria->compare('btk_gpx_s3_data', $this->btk_gpx_s3_data, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingTrack the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getByBkgId($bkgId)
	{
		$success	 = false;
		$criteria	 = new CDbCriteria;
		$criteria->compare('btk_bkg_id', $bkgId);
		$model		 = $this->find($criteria);
		if ($model)
		{
			$success = $model;
		}
		return $success;
	}

	public static function getTripEventTitles($eventId = null)
	{
		$eventTitles							 = array();
		$eventTitles[self::OTP_VERIFY]			 = 'Verify OTP';
		$eventTitles[self::TRIP_START]			 = 'Start trip';
		$eventTitles[self::TRIP_PAUSE]			 = 'Pause trip';
		$eventTitles[self::TRIP_RESUME]			 = 'Resume trip';
		$eventTitles[self::TRIP_STOP]			 = 'End trip';
		$eventTitles[self::GOING_FOR_PICKUP]	 = 'Left for pickup';
		$eventTitles[self::NOT_GOING_FOR_PICKUP] = 'Not going for pickup';
		$eventTitles[self::DRIVER_ARRIVED]		 = 'Driver arrived';
		$eventTitles[self::NO_SHOW]				 = 'Customer no show';
		$eventTitles[self::WAIT]				 = 'Cab will be late';
		$eventTitles[self::NO_SHOW_RESET]		 = 'Reset customer no show';
		$eventTitles[self::SOS_START]			 = 'SOS on';
		$eventTitles[self::SOS_RESOLVED]		 = 'SOS off';
		$eventTitles[self::VOUCHER_UPLOAD]		 = 'Upload voucher';
		$eventTitles[self::VOUCHER_DELETED]		 = 'Delete voucher';
		$eventTitles[self::TRIP_SELFIE]			 = 'Selfie image';
		$eventTitles[self::TRIP_SANITIZER_KIT]	 = 'Sanitizer kit image';
		$eventTitles[self::TRIP_ARROGYA_SETU]	 = 'Arrogya setu image';
		$eventTitles[self::TRIP_TERMS_AGREE]	 = 'Agree terms';

		if ($eventId > 0)
		{
			return (isset($eventTitles[$eventId]) ? $eventTitles[$eventId] : '');
		}

		return $eventTitles;
	}

	public function checkSosApp($attribute, $params)
	{
		$success = true;
		if (self::checkDeviceIdByBkg($this->btk_bkg_id, $this->bkg_sos_device_id) == 0)
		{
			$this->addError($attribute, "DeviceId does not matched.");
			$success = false;
		}
		return $success;
	}

	public function checkSosWeb($attribute, $params)
	{
		$success = true;
		if ($this->bkg_sos_remarks == '' || $this->bkg_sos_remarks == NULL)
		{
			$this->addError($attribute, "Please enter the remarks.");
			$success = false;
		}
		return $success;
	}

	public static function checkDeviceIdByBkg($bkgId, $sosDeviceId)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `booking_track` WHERE booking_track.btk_bkg_id='$bkgId' AND booking_track.bkg_sos_device_id='$sosDeviceId'";
		return DBUtil::command($sql)->queryScalar();
	}

	public function verifyOTP1($bkgId, $otp)
	{
		$success	 = false;
		$errors		 = [];
		$transaction = DBUtil::beginTransaction();
		try
		{

			$bookingTrack = BookingTrack::model()->find('btk_bkg_id=:bkg_id', ['bkg_id' => $bkgId]);
			if ($bookingTrack->bkg_is_trip_verified == 1)
			{
				$errors = "Your OTP already verified.";
				throw new Exception($errors);
			}
			if ($bookingTrack->bkg_trip_otp != $otp)
			{
				$errors = "Invalid OTP.";
				throw new Exception($errors);
			}
			else
			{
				$bookingTrack->bkg_is_trip_verified = 1;
				if ($bookingTrack->save())
				{
					$success = DBUtil::commitTransaction($transaction);
					$message = "OTP verified successfully.";
				}
				else
				{
					$errors = $bookingTrack->getErrors();
				}
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$message = $errors;
		}
		return ['success' => $success, 'errors' => $message];
	}

	public function sendTripOtp($bkgId, $sendOtp = false)
	{
		$bookingTrack = BookingTrack::model()->find('btk_bkg_id=:bkg_id', ['bkg_id' => $bkgId]);

		if ($bookingTrack->bkg_trip_otp == '')
		{
			$bookingTrack->bkg_trip_otp = strtolower(rand(100100, 999999));
		}
		return $bookingTrack;
	}

	public function getServedOTPReport($qry = [], $type = '')
	{
		$where = '';
		if ($qry['partner'] != '' && $qry['partner'] > 0)
		{
			$where .= " AND bkg_agent_id =" . $qry['partner'];
		}
		if ($qry['fromDate'] != '' && $qry['toDate'] != '')
		{
			$fromDate	 = $qry['fromDate'];
			$toDate		 = $qry['toDate'];
			$where		 .= " AND DATE(booking.bkg_pickup_date) BETWEEN '$fromDate' AND '$toDate' ";
		}
		else
		{
			$where .= " AND DATE_SUB(NOW(), INTERVAL 6 MONTH) < bkg_pickup_date ";
		}
		$sql = "SELECT   count(DISTINCT bkg_id) total_served, 
count(DISTINCT trl_bkg_id) otp_verified, 
sum(if(bkg_trip_otp_required = 1, 1, 0)) otp_required, 
sum(if(bkg_trip_otp > 0, 1, 0)) otp_sent, 
date_format(bkg_pickup_date, '%V, %Y') reportWeek, 
date_format(bkg_pickup_date, '%V') pWeek, year(bkg_pickup_date) pYear
FROM     booking
         JOIN booking_pref ON booking.bkg_id = booking_pref.bpr_bkg_id
         JOIN booking_track ON booking.bkg_id = btk_bkg_id
         LEFT JOIN trip_otplog ON trl_bkg_id = booking.bkg_id AND trl_status = 1
WHERE    bkg_status IN (5,6,7)   $where 
GROUP BY pYear, pWeek";
		if ($type == 'command')
		{
			return DBUtil::query($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => [],
					'defaultOrder'	 => 'pYear DESC,pWeek DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
	}

	public static function getLateOTPVerifyPenalty($bkgID, $ptype)
	{
		$penalty = 0;
		if ($bkgID > 0)
		{
			$sql = "SELECT TIMESTAMPDIFF(MINUTE, bkg_pickup_date, NOW()) dtsub
					FROM   booking WHERE  bkg_id = $bkgID AND bkg_status IN (5,6)";

			$pickLate	 = DBUtil::command($sql)->queryScalar();
			$arrRules	 = PenaltyRules::getRuleByPenaltyType($ptype);
			$penalty	 = PenaltyRules::calculatePenaltyCharge($ptype, $arrRules, '', $pickLate);
//			if ($pickLate > $arrRules['time']['diffrentTime'] && $pickLate <= $arrRules['time']['maximumTime'])
//			{
//				$penalty = $arrPenaltyCharge['minimumTimeCharge'];
//			}
//			if ($pickLate > $arrRules['time']['maximumTime'])
//			{
//				$penalty = $arrPenaltyCharge['diffrentTimeCharge'];
//			}
		}
		return $penalty;
	}

	/**
	 * This function finds the late penalty for late OTP verifications 
	 * @param type $bokingId	-	booking Id
	 * @param type $dateTime	-	Pickup date time from request
	 * @param type $ptype		-	Penalty type from request
	 * @return int
	 */
	public static function getLatePenality($bookingId, $dateTime, $ptype)
	{
		$penalty = 0;

		if (empty($bookingId))
		{
			return 0;
		}

		/**
		 * Finds the time difference in pickup date
		 */
		$findTimeDiffQuery	 = "
			SELECT TIMESTAMPDIFF(MINUTE, bkg_pickup_date, CAST('" . $dateTime . "'  AS DATETIME)) lateTime,
				TIMESTAMPDIFF(MINUTE, DATE_ADD(bkg_pickup_date, INTERVAL bkg_trip_duration MINUTE), CAST('" . $dateTime . "'  AS DATETIME)) completeTime
			FROM   booking 
			WHERE  bkg_id = $bookingId AND bkg_status IN (5,6)
		";
		$pickLate			 = DBUtil::queryRow($findTimeDiffQuery, DBUtil::SDB());
		$completeLateTime	 = $pickLate["completeTime"];
		$arrRules			 = PenaltyRules::getRuleByPenaltyType($ptype);
		$penalty			 = PenaltyRules::calculatePenaltyCharge($ptype, $arrRules, '', $completeLateTime);
//		if ($completeLateTime > $arrRules['time']['minimumTime'])
//		{
//			$penalty = $arrPenaltyCharge['maximumTimeCharge'];
//		}
		return $penalty;
	}

	public function stopTrip($platform, $trip_otp = '', $msg = '', $phoneNumber = '', $odoEndReading = '', $userInfo = null)
	{
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}
		$returnSet	 = new ReturnSet();
		$success	 = false;

		$platformArr	 = TripOtplog::platformArr;
		$platformName	 = $platformArr[$platform];
		if ($this->bkg_ride_start == 1)
		{
			if ($this->bkg_ride_complete != 1)
			{
				$returnSet = $this->verifyStopTripOTP($platform, $trip_otp, $msg, $phoneNumber, $odoEndReading);
				if ($returnSet->getStatus())
				{
					$bkg_id		 = $this->btk_bkg_id;
					$transaction = DBUtil::beginTransaction();
					try
					{
						$this->bkg_ride_complete		 = 1;
						$this->btk_end_platform			 = $platform;
						$this->bkg_trip_end_time		 = new CDbExpression('NOW()');
						$this->bkg_trip_end_user_id		 = $userInfo->userId;
						$this->bkg_trip_end_user_type	 = $userInfo->userType;

						if ($odoEndReading > 0)
						{
							$this->bkg_end_odometer = $odoEndReading;
						}

						if ($this->save())
						{
							$event		 = BookingTrack::TRIP_STOP;
							$btlModel	 = BookingTrackLog::model()->addByNonDriver($platform, $bkg_id, $event, $odoEndReading);
							if (!$btlModel)
							{
								throw new Exception('Error occurred while saving in BookingTrackLog:' . json_encode($btlModel->getErrors()));
							}
							##Update BookingTrack Last status
							$bkgId		 = $btlModel->btl_bkg_id;
							$eventId	 = $btlModel->btl_event_type_id;
							$coordinates = $btlModel->btl_coordinates;
							$dateTime	 = $btlModel->btl_sync_time;
							$btSuccess	 = BookingTrack::updateLastStatus($bkgId, $eventId, $coordinates, $dateTime);

							$returnSet->setStatus(true);
							DBUtil::commitTransaction($transaction);
						}
					}
					catch (Exception $ex)
					{
						DBUtil::rollbackTransaction($transaction);
						$returnSet->addError($ex->getMessage());
						$returnSet->setErrorCode($ex->getCode());

						$returnSet->setStatus(false);
					}
				}
				else
				{
					$desc = "Ride not stopped. Otp not matched";
					$returnSet->setStatus(false);
//					$returnSet->setData(['message' => $desc]);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$desc = "Ride already completed";
//				$returnSet->setData(['message' => "Ride already completed"]);
			}
		}
		else
		{
			$returnSet->setStatus(false);
			$desc = "Ride not started yet";
		}
		if ($returnSet->getStatus())
		{
			$success				 = true;
			$params['blg_ref_id']	 = BookingLog::REF_RIDE_COMPLETE;
			$desc					 = $returnSet->getData()['message'];
		}
		$desc	 = $desc . "($platformName)";
		$returnSet->setData(['message' => $desc]);
		$eventId = BookingLog::RIDE_STATUS;
		$bkg_id	 = $this->btk_bkg_id;
//		$bmodel				 = Booking::model()->findByPk($bkg_id);
//		$userInfo->userId	 = $userInfo->userId;
//		$userInfo->userType	 = $userInfo->userType;

		BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		return $returnSet;
	}

	public function startTrip($platform, $trip_otp = '', $msg = '', $phoneNumber = '', $odoStartReading = '')
	{
		Logger::profile("BOOKING TRACK START TRIP");

		$platformArr	 = TripOtplog::platformArr;
		$platformName	 = $platformArr[$platform];
		$userInfo		 = UserInfo::getInstance();

		$returnSet	 = new ReturnSet();
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			Logger::profile("bkg_id == " . $this->btk_bkg_id);
			Logger::profile("bkg_ride_start == " . $this->bkg_ride_start);

			if ($this->bkg_ride_start != 1)
			{
				$returnSet = $this->verifyOTP($platform, $trip_otp, $msg, $phoneNumber, $odoStartReading);
				if ($returnSet->getStatus())
				{
					$bookingModel		 = Booking::model()->findByPk($this->btk_bkg_id);
					$pickupDate			 = $this->btkBkg->bkg_pickup_date;
					$estimateStart		 = date("Y-m-d H:i:s", strtotime($pickupDate . "-30 minutes"));
					$tripDuration		 = $this->btkBkg->bkg_trip_duration;
					$nowTime			 = date("Y-m-d H:i:s");
					$estimateComplete	 = date("Y-m-d H:i:s", strtotime($pickupDate . "+ $tripDuration minutes"));
					if ($estimateStart < $nowTime && $nowTime > $estimateComplete && $platform != 2)
					{
						$this->bkg_ride_start		 = 1;
						$this->btk_start_platform	 = $platform;
						$this->bkg_trip_start_time	 = new CDbExpression('NOW()');
						if ($odoStartReading > 0)
						{
							$this->bkg_start_odometer = $odoStartReading;
						}
						if ($this->save())
						{

							$btlModel = BookingTrackLog::model()->addByNonDriver($platform, $bookingModel->bkg_id, 101, NULL);
							if (!$btlModel)
							{
								throw new Exception('Error occurred while saving in BookingTrackLog:' . json_encode($btlModel->getErrors()));
							}
							##Update BookingTrack Last status
							$bkgId		 = $btlModel->btl_bkg_id;
							$eventId	 = $btlModel->btl_event_type_id;
							$coordinates = $btlModel->btl_coordinates;
							$dateTime	 = $btlModel->btl_sync_time;
							$btSuccess	 = BookingTrack::updateLastStatus($bkgId, $eventId, $coordinates, $dateTime);

							$returnSet->setData(['message' => "OverDue Ride Started. OTP verified({$platformName})."]);
							$prows			 = PenaltyRules::getValueByPenaltyType(PenaltyRules::PTYPE_RIDE_START_OVERDUE);
							$penaltyAmount	 = $prows['plt_value'];
							
							if ($penaltyAmount > 0)
							{
								$vendor_id		 = $this->btkBkg->bkgBcb->bcb_vendor_id;
								$bkg_booking_id	 = $this->btkBkg->bkg_booking_id;
								$remarks		 = "Ride start overdue for booking ID #$bkg_booking_id";
								$penaltyType	 = PenaltyRules::PTYPE_RIDE_START_OVERDUE;
								$result			 = AccountTransactions::checkAppliedPenaltyByType($this->btk_bkg_id, $penaltyType);
								if ($result)
								{
									AccountTransactions::model()->addVendorPenalty($this->btk_bkg_id, $vendor_id, $penaltyAmount, $remarks, '', $penaltyType);
								}
							}
						}
					}
					else
					{
						$this->bkg_ride_start		 = 1;
						$this->btk_start_platform	 = $platform;
						$this->bkg_trip_start_time	 = new CDbExpression('NOW()');
						if ($odoStartReading > 0)
						{
							$this->bkg_start_odometer = $odoStartReading;
						}
						if ($this->save())
						{

							$btlModel = BookingTrackLog::model()->addByNonDriver($platform, $bookingModel->bkg_id, 101);

							if (!$btlModel)
							{
								throw new Exception('Error occurred while saving in BookingTrackLog:' . json_encode($btlModel->getErrors()));
							}
							##Update BookingTrack Last status
							$bkgId		 = $btlModel->btl_bkg_id;
							$eventId	 = $btlModel->btl_event_type_id;
							$coordinates = $btlModel->btl_coordinates;
							$dateTime	 = $btlModel->btl_sync_time;
							$btSuccess	 = BookingTrack::updateLastStatus($bkgId, $eventId, $coordinates, $dateTime);

							if ($bookingModel->bkgBcb->bcb_driver_id != "")
							{
								$userInfo		 = UserInfo::getInstance();
								$type			 = Booking::model()->userArr[$userInfo->userType];
								$message		 = "Booking " . $bookingModel->bkg_booking_id . " Started by $type";
								$image			 = NULL;
								$bookingID		 = $bookingModel->bkg_booking_id;
								$notificationId	 = substr(round(microtime(true) * 1000), -5);
								$payLoadData	 = ['EventCode' => Booking::CODE_TRIP_START_NOTIFICATION];
								AppTokens::model()->notifyDriver($bookingModel->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "Trip Started", $bookingID);
							}

							if ($bookingModel->bkgPref->bkg_trip_otp_required == 1)
							{
								$msg1 = '. OTP verified';
							}
							$returnSet->setData(['message' => "Ride started $msg1({$platformName})."]);
						}
					}

					if ($bookingModel->bkg_agent_id == 450 || $bookingModel->bkg_agent_id == 18190)
					{
						$typeAction = AgentApiTracking::TYPE_TRIP_START;
					}
					AgentMessages::model()->pushApiCall($bookingModel, $typeAction);
				}
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setData(['message' => "Ride already started($platformName)."]);
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
		}

		if ($returnSet->getStatus())
		{
			$success				 = true;
			$params['blg_ref_id']	 = BookingLog::REF_RIDE_START;
			$desc					 = $returnSet->getData()['message'];
		}
		else
		{
			$desc = "Trip not verified. Otp not matched($platformName)";
			$returnSet->setData(['message' => $desc]);
		}

		$eventId	 = BookingLog::RIDE_STATUS;
		$bkg_id		 = $this->btk_bkg_id;
		$bmodel		 = Booking::model()->findByPk($bkg_id);
		$userId		 = $bmodel->bkgBcb->bcb_vendor_id;
		$userType	 = UserInfo::TYPE_VENDOR;

		if ($platform == 1)
		{
			$userId		 = $bmodel->bkgBcb->bcb_driver_id;
			$userType	 = UserInfo::TYPE_DRIVER;
		}
		$userInfo->userId	 = $userId;
		$userInfo->userType	 = $userType;
		BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		return $returnSet;
	}

	/**
	 * This function is used for start trip action irrespective of user type
	 * @param type $eventModel
	 * @param type $userInfo
	 * @param type $bookingId
	 * @param type $bookingModel
	 * @param type $eventId
	 * @return array
	 */
	public static function startNewTrip($eventModel, $userInfo, $bookingId, $bookingModel, $eventId)
	{
		$returnSet = new ReturnSet();
		/**
		 * Check whether trip is already started or not
		 * If started then skip the below code and continue
		 * Else proceed with the work flow 
		 */
		if ($bookingModel->bkgTrack->bkg_ride_start && $bookingModel->bookingTrackLog->btl_event_type_id == $eventId)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Mandatory condition failed");

			goto skipAll;
		}

		/**
		 * Checks whether booking already completed or not
		 * If Yes, Then return else Process the flow
		 */
		if ($bookingModel->bkgTrack->bkg_ride_complete)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("This trip is already completed");

			goto skipAll;
		}

		$transaction = DBUtil::beginTransaction();
		try
		{
			//Update the driver score based on booking
			BookingTrail::model()->updateDriverScore($bookingId, $eventId);

			$bookingTrackModel = $bookingModel->bkgTrack; //Taking the booking track
			//Driver
			if ($userInfo->userType == 3)
			{
				$platformId = 2;
			}

			//vendor
			if ($userInfo->userType == 2)
			{
				$platformId = 4;
			}

			$platformArr	 = TripOtplog::platformArr;
			$platformName	 = $platformArr[$platformId];

			$returnSet = new ReturnSet();

			$success = true;
			$message = "Trip started already";

			if ($bookingTrackModel->bkg_ride_start != 1)
			{
				//Verify Trip OTP
				$returnSet = BookingTrack::verifyTripOTP($platformId, $eventModel, $userInfo, $bookingTrackModel, $bookingModel);

				if ($returnSet->getStatus())
				{
					$pickupDate = $bookingModel->bkg_pickup_date;

					/**
					 * Calculating the penalties whether it will be applied or not
					 */
					$eventTriggeredTime = $eventModel->bkgTrack->bkg_trip_start_time; //Received from App end

					$estimateStart		 = date("Y-m-d H:i:s", strtotime($pickupDate . "-30 minutes"));
					$tripDuration		 = $bookingModel->bkg_trip_duration;
					$estimateComplete	 = date("Y-m-d H:i:s", strtotime($pickupDate . "+ $tripDuration minutes"));
					
					if ($estimateStart < $eventTriggeredTime && $eventTriggeredTime > $estimateComplete)
					{
						$message		 = "OverDue Ride Started. OTP verified({$platformName}).";
						$prows			 = PenaltyRules::getValueByPenaltyType(PenaltyRules::PTYPE_RIDE_START_OVERDUE);
						$penaltyAmount	 = $prows['plt_value'];
						$bookingId1		 = $bookingModel->btkBkg->bkg_booking_id;
						$vendorId		 = $bookingModel->btkBkg->bkgBcb->bcb_vendor_id;
						$remarks		 = "Ride start overdue for booking ID #$bookingId1";
						$penaltyType	 = PenaltyRules::PTYPE_RIDE_START_OVERDUE;
						$result			 = AccountTransactions::checkAppliedPenaltyByType($bookingId, $penaltyType);
						if ($result)
						{
							AccountTransactions::model()->addVendorPenalty($bookingId, $vendorId, $penaltyAmount, $remarks, '', $penaltyType);
						}
					}


					$bookingTrackModel->bkg_ride_start				 = 1;
					$bookingTrackModel->bkg_trip_start_coordinates	 = $eventModel->bkgTrack->bkg_trip_start_coordinates;
					$bookingTrackModel->bkg_trip_start_time			 = $eventModel->bkgTrack->bkg_trip_start_time;
					$bookingTrackModel->bkg_start_odometer			 = $eventModel->bkgTrack->bkg_start_odometer;

					$bookingTrackModel->save();

					$message = "Otp Verified! Trip started successfully";
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


		$returnSet->setStatus($success);
		$returnSet->setMessage($message);

		skipAll:

		$bookingLogEvent = BookingLog::mapEvents();
		$oldEventId		 = $bookingLogEvent[$eventId]; //For booking log table

		/**
		 * Write
		 * 1 -	Booking Log
		 * 2 -	Booking trip track log
		 */
		BookingLog::model()->createLog($bookingId, $message, $userInfo, $oldEventId, false, $bookingModel->bkg_status);
		BookingTrackLog::addDetails($eventModel, $userInfo, $message);

		return $returnSet;
	}

	/**
	 * This function is used for verifying the OTP used for the ride
	 * @param type $platform
	 * @param type $eventModel
	 * @param type $userInfo
	 * @param type $bookingTrackModel
	 * @param type $bookingModel
	 * @return \ReturnSet
	 */
	public function verifyTripOTP($platform, $eventModel, $userInfo, $bookingTrackModel, $bookingModel)
	{
		$returnSet = new ReturnSet();

		if (empty($platform) || empty($eventModel) || empty($userInfo) || empty($bookingTrackModel) || empty($bookingModel))
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Mandatory data not passed");

			goto skipAllCode;
		}

		$success = false;

		$transaction = DBUtil::beginTransaction();
		try
		{
			$event			 = BookingTrack::TRIP_START;
			$isOtpRequired	 = $bookingModel->bkgPref->bkg_trip_otp_required;

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
			if ($isOtpRequired && $bookingTrackModel->bkg_is_trip_verified)
			{
				DBUtil::commitTransaction($transaction);

				$success = true;

				goto skipAll;
			}

			$bCabModel	 = $bookingModel->bkgBcb; //booking Cab model
			$bookingId	 = $bookingModel->bkg_id;

			$phoneNumber = $bCabModel->bcb_driver_phone;
			$otp		 = $eventModel->bkgTrack->bkg_trip_otp;

			$driverId = $bCabModel->bcb_driver_id;

			$tmodel = TripOtplog::addNew($bookingId, $driverId, $platform, $otp, $msg, $phoneNumber);

			if ($bookingTrackModel->bkg_trip_otp != $otp)
			{
				$tmodel->trl_status = 2;
				$tmodel->save();

				if (!empty($phoneNumber))
				{
					$drvName = ($bCabModel->bcb_driver_name) ? $bCabModel->bcb_driver_name : $bCabModel->bcbDriver->drv_name;

					$msgCom = new smsWrapper();
					$msgCom->informDriverInvalidOTP($phoneNumber, $drvName, $bookingModel->bkg_booking_id);
				}

				$returnSet->setStatus(false);
			}
			else
			{
				$bookingTrackModel->bkg_is_trip_verified = 1;
				$remarks								 = "";
				if ($bookingTrackModel->save())
				{
					$success		 = BookingCab::model()->pushPartnerTripStart($bookingId, $tmodel->trl_date);
					$penaltyType	 = PenaltyRules::PTYPE_LATE_OTP_VERIFICATION;
					$penaltyAmount	 = BookingTrack::getLatePenality($bookingId, $eventModel->bkgTrack->bkg_trip_start_time, $penaltyType);
					
					if ($penaltyAmount > 0)
					{
						$vendorId	 = $bCabModel->bcb_vendor_id;
						$bookingId	 = $bmodel->bkg_booking_id;
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
						$msgCom->MatchOTP($phoneNumber, $drvName, $bookingModel->bkg_booking_id);
					}

					DBUtil::commitTransaction($transaction);
					$success = true;

					goto skipAll;
				}

				$returnSet->setErrors($this->getErrors(), 1);
			}

			skipAll:
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
		}


		$returnSet->setStatus($success);
		skipAllCode:

		return $returnSet;
	}

	public function verifyStopTripOTP($platform, $otp, $msg, $phoneNumber, $odoEndReading = '')
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bkg_id	 = $this->btk_bkg_id;
			$bmodel	 = $this->btkBkg;

			$otp_required = $bmodel->bkgPref->bkg_trip_otp_required;

			if ($otp_required == 1)
			{
				if ($this->bkg_is_trip_verified != 1)
				{
					throw new Exception("Trip not started yet");
				}
				$bCabModel		 = $bmodel->bkgBcb;
				$bkg_booking_id	 = $bmodel->bkg_booking_id;
				$tmodel			 = TripOtplog::model()->add($bkg_id, $platform, $otp, $msg, $phoneNumber);
				if ($this->bkg_trip_otp != $otp)
				{
					$tmodel->trl_status = 2;
					$tmodel->save();
					if ($phoneNumber != '')
					{
						$drvName = ($bCabModel->bcb_driver_name) ? $bCabModel->bcb_driver_name : $bCabModel->bcbDriver->drv_name;
						$msgCom	 = new smsWrapper();
						$msgCom->informDriverInvalidStopOTP($phoneNumber, $drvName, $bkg_booking_id);
					}
					$returnSet->setStatus(false);
					$returnSet->addError('Invalid OTP.', 'bkg_trip_otp');
					$returnSet->setData(['message' => "Invalid OTP"]);
				}
				else
				{
					$returnSet->setStatus(true);
					$returnSet->setData(['message' => "Ride marked complete successfully"]);
				}
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setData(['message' => "Ride marked complete successfully"]);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
			$returnSet->setStatus(false);
		}
		return $returnSet;
	}

	public function verifyOTP($platform, $otp, $msg, $phoneNumber, $odoStartReading = '')
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bkg_id	 = $this->btk_bkg_id;
			$bmodel	 = Booking::model()->findByPk($bkg_id);
//			 $event		 = 215;
//			
//			//$ttgModel	 = TripTracking::model()->add($bkg_id, $event, $odoStartReading);
//			if ($platform == 2)
//			{
//				$ttgModel = BookingTrackLog::model()->add($platform, $bkg_id, $event, $odoStartReading);
//			}
//			else
//			{
//				$ttgModel = BookingTrackLog::model()->addByNonDriver($platform, $bkg_id, $event, $odoStartReading);
//			}


			$otp_required = $bmodel->bkgPref->bkg_trip_otp_required;

			if ($otp_required == 1 && $this->bkg_is_trip_verified == 0)
			{
				$bCabModel		 = $bmodel->bkgBcb;
				$bkg_booking_id	 = $bmodel->bkg_booking_id;
				$tmodel			 = TripOtplog::model()->add($bkg_id, $platform, $otp, $msg, $phoneNumber);
				if ($this->bkg_trip_otp != $otp)
				{
					$tmodel->trl_status = 2;
					$tmodel->save();
					if ($phoneNumber != '')
					{
						$drvName = ($bCabModel->bcb_driver_name) ? $bCabModel->bcb_driver_name : $bCabModel->bcbDriver->drv_name;
						$msgCom	 = new smsWrapper();
						$msgCom->informDriverInvalidOTP($phoneNumber, $drvName, $bkg_booking_id);
					}
					$returnSet->setStatus(false);
					$returnSet->addError('Invalid OTP.', 'bkg_trip_otp');
					$returnSet->setData(['message' => "Invalid OTP"]);
				}
				else
				{

					$this->bkg_is_trip_verified = 1;
					if ($this->save())
					{
						$success		 = BookingCab::model()->pushPartnerTripStart($bkg_id, $tmodel->trl_date);
						$penaltyType	 = PenaltyRules::PTYPE_LATE_OTP_VERIFICATION;
						$penaltyAmount	 = BookingTrack::getLateOTPVerifyPenalty($bkg_id, $penaltyType);
						
						if ($penaltyAmount > 0)
						{
							$vendor_id		 = $bCabModel->bcb_vendor_id;
							$bkg_booking_id	 = $bmodel->bkg_booking_id;
							$remarks		 = "Late OTP verification of booking #$bkg_booking_id";
							$result			 = AccountTransactions::checkAppliedPenaltyByType($bkg_id, $penaltyType);
							if ($result)
							{
								AccountTransactions::model()->addVendorPenalty($bkg_id, $vendor_id, $penaltyAmount, $remarks, '', $penaltyType);
							}
						}
						if ($phoneNumber != '')
						{
							$msgCom = new smsWrapper();
							$msgCom->MatchOTP($phoneNumber, $drvName, $bkg_booking_id, $bmodel->bkg_id, $otp);
						}
						DBUtil::commitTransaction($transaction);
						$returnSet->setStatus(true);
						$returnSet->setData(['message' => "OTP verified successfully."]);
					}
					else
					{
						$returnSet->setErrors($this->getErrors(), 1);
					}
				}
			}
			else
			{
				DBUtil::commitTransaction($transaction);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->addError($ex->getMessage());
			$returnSet->setErrorCode($ex->getCode());
		}
		return $returnSet;
	}

	/* @var $bkg_sos_sms_trigger 0=>sos not triggered, 1=> sos Trigger turned Off, 2=>sosTrigger turned on */

	public function saveSosLocation($sosSmsTrigger, $data, $userId)
	{
		$latitude						 = $data['lat'];
		$longitude						 = $data['lon'];
		$modelTrack						 = Booking::model()->findByPk($data['bkg_id']);
		$params['blg_booking_status']	 = $modelTrack->bkg_status;
		$userInfo						 = UserInfo::getInstance();
		$UserModel						 = Users::model()->findByPk($userId);
		$userName						 = $UserModel->usr_name . " " . $UserModel->usr_lname;
		$dateTime						 = date("Y-m-d H:i:s");
		$desc							 = "S.O.S. activated by $userName at Latitude:$latitude Longitude:$longitude on Date $dateTime.";
		if ($modelTrack)
		{
			$modelTrack->bkgTrack->btk_bkg_id				 = $data['bkg_id'];
			$modelTrack->bkgTrack->bkg_sos_device_id		 = $data['deviceId'];
			$modelTrack->bkgTrack->bkg_sos_latitude			 = $latitude - 0.0;
			$modelTrack->bkgTrack->bkg_sos_longitude		 = $longitude - 0.0;
			$modelTrack->bkgTrack->bkg_sos_enable_datetime	 = $dateTime;
			$isSosSmsTrigger								 = ($latitude == 0.0 || $longitude == 0.0) ? 1 : $sosSmsTrigger;
			$modelTrack->bkgTrack->bkg_sos_sms_trigger		 = $isSosSmsTrigger;
			$modelTrack->bkgTrack->save();
		}
		$eventId = BookingLog::SOS_TRIGGER_ON;
		BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, $eventId, false, $params);
		return $isSosSmsTrigger;
	}

//	public function pushMMt($bkg_id)
//	{
//		$ttgModel = TripTracking::model()->getByBkg($bkg_id, 215);
//		foreach ($ttgModel as $ttg)
//		{
//			echo $bkg_id	 = $ttg['ttg_bkg_id'];
//			$bmodel	 = Booking::model()->findByPk($ttg['ttg_bkg_id']);
//			$otp	 = $bmodel->bkgPref->bkg_trip_otp;
//
//			$tripStartTime			 = $ttg['ttg_created'];
//			$IP						 = \Filter::getUserIP();
//			$tmodel					 = new TripOtplog();
//			$tmodel->trl_platform	 = 4;
//			$tmodel->trl_ip			 = $IP;
//			$tmodel->trl_bkg_id		 = $bkg_id;
//			$tmodel->trl_otp		 = $otp;
//			$tmodel->trl_date		 = $tripStartTime;
//			$tmodel->save();
//
//			$bmodel->bkgTrack->bkg_is_trip_verified	 = 1;
//			$bmodel->bkgTrack->bkg_ride_start		 = 1;
//
//			if ($bmodel->bkgTrack->save())
//			{
//				$success = BookingCab::model()->pushPartnerTripStart($bkg_id, $tmodel->trl_date);
//				echo " :: Status: " . $success;
//				echo " \n\n ";
//			}
//
//			exit;
//		}
//	}

	public function pushMMt($bkg_id)
	{
		$btlModel = BookingTrackLog::model()->getByBkg($bkg_id, 101);
		foreach ($btlModel as $btl)
		{
			echo $bkg_id	 = $btl['btl_bkg_id'];
			$bmodel	 = Booking::model()->findByPk($btl['btl_bkg_id']);
			$otp	 = $bmodel->bkgPref->bkg_trip_otp;

			$tripStartTime			 = $btl['btl_created'];
			$IP						 = \Filter::getUserIP();
			$tmodel					 = new TripOtplog();
			$tmodel->trl_platform	 = 4;
			$tmodel->trl_ip			 = $IP;
			$tmodel->trl_bkg_id		 = $bkg_id;
			$tmodel->trl_otp		 = $otp;
			$tmodel->trl_date		 = $tripStartTime;
			$tmodel->save();

			$bmodel->bkgTrack->bkg_is_trip_verified	 = 1;
			$bmodel->bkgTrack->bkg_ride_start		 = 1;

			if ($bmodel->bkgTrack->save())
			{
				$success = BookingCab::model()->pushPartnerTripStart($bkg_id, $tmodel->trl_date);
				echo " :: Status: " . $success;
				echo " \n\n ";
			}

			exit;
		}
	}

	public function resolveSOS($data, $userInfo, $userName = '')
	{
		$success	 = false;
		$message	 = '';
		$sosAdmin	 = false;
		$bModel		 = Booking::model()->findByPk($data['bkg_id']);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$model		 = $bModel->bkgTrack;
			$dateTime	 = date("Y-m-d H:i:s");
			if ($model->bkg_sos_sms_trigger == 2)
			{
				$userId						 = $bModel->bkgUserInfo->bkg_user_id;
				$sosContactList				 = Users::model()->getSosContactList($userId);
//				if ($sosContactList == null)
//				{
//					throw new Exception("Emergency contacts not found.");
//				}
				//$model->bkg_sos_disable_datetime = $dateTime;
				// $model->bkg_sos_off_by           = $userInfo->userId;
				// $model->bkg_sos_off_type         = $userInfo->userType;
				$model->bkg_sos_sms_trigger	 = 1;
				// $model->bkg_sos_remarks          = $data['comment'];
				//$model->bkg_sos_device_id        = '';
				// $model->scenario                 = 'validateSosWeb';
				if ($model->validate() && $model->save())
				{
					$sosAdmin	 = true;
					$event		 = BookingTrack::SOS_RESOLVED;
					BookingTrackLog::model()->addByNonDriver(1, $model->btk_bkg_id, $event);

					$params['blg_booking_status']	 = $bModel->bkg_status;
					/* @var $modelAdmin Admins */
					$modelAdmin						 = Admins::model()->findById($userInfo->userId);
					$userName						 = $modelAdmin->adm_fname . ' ' . $modelAdmin->adm_lname;
					$desc							 = "S.O.S. ended by $userName at:  $dateTime. Remarks : " . $data['comment'];
					$eventId						 = BookingLog::SOS_TRIGGER_OFF;
					BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, $eventId, false, $params);
					foreach ($sosContactList As $value)
					{
						$emergencyUserName	 = $value['name'];
						$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
						$phoneNumber		 = substr($phone, -10);
						$emailAddress		 = $value['email'];
						$urlHash			 = Users::model()->createSOSHashUrl($data['bkg_id'], $userId);
						$url				 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
						$msg				 = "PANIC Situation resolved.Track $travellername current location at $url";
						if (strlen($phoneNumber) >= 10)
						{
							$msgCom		 = new smsWrapper();
							$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($data['bkg_id'], $phoneNumber, $msg);
						}
						if ($emailAddress != '')
						{
							$emailModel		 = new emailWrapper();
							$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($data['bkg_id'], $userName, $emergencyUserName, $emailAddress, $msg);
						}
						$sosSmsTrigger = ($sendSmsFlag > 0 || $sendEmailFlag > 0 || $sosAdmin == true) ? true : false;
						if ($sosSmsTrigger == false)
						{
							throw new Exception("Unable to send SMS notification to emergency contacts.");
						}
					}
					if ($sosAdmin)
					{
						$sosSmsTrigger = true;
					}
				}
				else
				{
					throw new Exception(json_encode($model->getErrors()));
				}
			}
			if ($model->bkg_drv_sos_sms_trigger == 2)
			{
				$drvModel						 = Drivers::model()->findById($bModel->bkgBcb->bcb_driver_id);
				$drvname						 = $drvModel->drv_name;
				$sosContactList					 = Users::model()->getSosContactList($drvModel->drv_user_id);
				// $model->bkg_drv_sos_disable_datetime = $dateTime;
				//  $model->bkg_sos_off_by               = $userInfo->userId;
				// $model->bkg_sos_off_type             = $userInfo->userType;
				$model->bkg_drv_sos_sms_trigger	 = 1;
				$model->bkg_sos_end_coordinates	 = '';
				// $model->bkg_sos_remarks              = $data['comment'];
				// $model->bkg_drv_sos_device_id        = '';
				if ($model->save())
				{
					$event							 = BookingTrack::SOS_RESOLVED;
					BookingTrackLog::model()->add(4, $model->btk_bkg_id, $event);
					$params['blg_booking_status']	 = $bModel->bkg_status;
					/* @var $modelAdmin Admins */
					$modelAdmin						 = Admins::model()->findById($userInfo->userId);
					$userName						 = $modelAdmin->adm_fname . ' ' . $modelAdmin->adm_lname;
					$desc							 = "S.O.S. ended by $userName on Date $dateTime. Remarks : " . $data['comment'];
					$eventId						 = BookingLog::SOS_TRIGGER_OFF;
					BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, $eventId, false, $params);
					foreach ($sosContactList As $value)
					{
						$emergencyUserName	 = $value['name'];
						$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
						$phoneNumber		 = substr($phone, -10);
						$emailAddress		 = $value['email'];
						$urlHash			 = Users::model()->createSOSHashUrl($data['bkg_id'], $userId);
						$url				 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
						$msg				 = "PANIC Situation resolved.Track $travellername current location at $url";
						if (strlen($phoneNumber) >= 10)
						{
							$msgCom		 = new smsWrapper();
							$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($data['bkg_id'], $phoneNumber, $msg);
						}
						if ($emailAddress != '')
						{
							$emailModel		 = new emailWrapper();
							$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($data['bkg_id'], $userName, $emergencyUserName, $emailAddress, $msg);
						}
					}
					$vModel			 = Vendors::model()->getDetailsbyId($bModel->bkgBcb->bcb_vendor_id);
					$vendorPhone	 = $vModel['vnd_phone'];
					$vendorEmail	 = $vModel['vnd_email'];
					$vendorName		 = $vModel['vnd_owner'];
					$msg			 = "PANIC Situation resolved.Track $drvname current location at $url";
					$msgCom			 = new smsWrapper();
					$sendSms		 = $msgCom->sendSmsToEmergencyContact($data['bkg_id'], $vendorPhone, $msg, $type);
					$emailModel		 = new emailWrapper();
					$sendEmail		 = $emailModel->sendEmailToEmergencyContact($data['bkg_id'], $drvname, $vendorName, $vendorEmail, $msg, $type);
					$sosSmsTrigger	 = ($sendSmsFlag > 0 || $sendEmailFlag > 0 || $sendSms > 0 || $sendEmail > 0 ) ? true : false;
					if ($sosSmsTrigger == false)
					{
						throw new Exception("Unable to send SMS notification to emergency contacts.");
					}
					//Send Notification to Driver
					if ($bModel->bkgBcb->bcb_driver_id != "")
					{
						$userInfo		 = UserInfo::getInstance();
						$type			 = Booking::model()->userArr[$userInfo->userType];
						$message		 = "SOS Off " . $bModel->bkg_booking_id . " by $type";
						$image			 = NULL;
						$bkgID			 = $bModel->bkg_booking_id;
						$notificationId	 = substr(round(microtime(true) * 1000), -5);
						$payLoadData	 = ['EventCode' => Booking::CODE_SOS_OFF_NOTIFICATION];
						AppTokens::model()->notifyDriver($bModel->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "SOS Off", $bkgID);
					}
				}
				else
				{
					throw new Exception(json_encode($model->getErrors()));
				}
			}

			if ($sosSmsTrigger == true)
			{
				$success = DBUtil::commitTransaction($transaction);
			}
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
			DBUtil::rollbackTransaction($transaction);
			Logger::create("errors => \n" . json_encode(stripslashes($message)), CLogger::LEVEL_INFO);
		}
		return ['success' => $success, 'message' => $message];
	}

	public function updateSosTriggerFlag($data, $userInfo, $userName = '')
	{
		/* @var $userInfo UserInfo  */
		$success		 = false;
		$message		 = '';
		$bModel			 = Booking::model()->findByPk($data['bkg_id']);
		$userId			 = ($userInfo->userType == 4) ? $bModel->bkgUserInfo->bkg_user_id : $userInfo->userId;
		$sosContactList	 = Users::model()->getSosContactList($userId);
		Logger::create("contactData => \n" . json_encode($sosContactList), CLogger::LEVEL_INFO);
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			$model = BookingTrack::model()->getByBkgId($data['bkg_id']);
			if ($userInfo->userType != 4)
			{
				$model->bkg_sos_latitude	 = $data['lat'];
				$model->bkg_sos_longitude	 = $data['lon'];
				$model->bkg_sos_device_id	 = $data['deviceId'];
			}
			$dateTime						 = date("Y-m-d H:i:s");
			$model->bkg_sos_disable_datetime = $dateTime;
			$model->bkg_sos_off_by			 = $userInfo->userId;
			$model->bkg_sos_off_type		 = $userInfo->userType;
			$model->bkg_sos_sms_trigger		 = 1;
			$model->bkg_sos_remarks			 = $data['comment'];
			Logger::create("data1 => \n" . json_encode($data), CLogger::LEVEL_INFO);
			$model->scenario				 = ($userInfo->userType == 4) ? 'validateSosWeb' : 'validateSosApp';
			if ($model->validate() && $model->save())
			{
				$params['blg_booking_status'] = $bModel->bkg_status;
				if ($userInfo->userType == 4)
				{
					/* @var $modelAdmin Admins */
					$modelAdmin	 = Admins::model()->findById($userInfo->userId);
					$userName	 = $modelAdmin->adm_fname . ' ' . $modelAdmin->adm_lname;
					$desc		 = "SOS ended by $userName on Date $dateTime. Remarks : " . $data['comment'];
				}
				else
				{
					$desc = "SOS ended by $userName at Latitude : " . $data['lat'] . " Longitude : " . $data['lon'] . " DeviceId : " . $data['deviceId'] . " on Date $dateTime";
				}
				$eventId	 = BookingLog::SOS_TRIGGER_OFF;
				BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, $eventId, false, $params);
				$sosContact	 = 0;
				foreach ($sosContactList As $value)
				{
					$emergencyUserName	 = $value['name'];
					$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
					$phoneNumber		 = substr($phone, -10);
					$emailAddress		 = $value['email'];
					$urlHash			 = Users::model()->createSOSHashUrl($data['bkg_id'], $userId);
					$url				 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
					$msg				 = "PANIC Situation resolved.Track $travellername current location at $url";
					if (strlen($phoneNumber) >= 10)
					{
						$msgCom		 = new smsWrapper();
						$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($data['bkg_id'], $phoneNumber, $msg);
					}
					if ($emailAddress != '')
					{
						$emailModel		 = new emailWrapper();
						$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($data['bkg_id'], $userName, $emergencyUserName, $emailAddress, $msg);
					}
					$sosContact		 = ($sosContact + 1);
					$sosSmsTrigger	 = ($sendSmsFlag > 0 || $sendEmailFlag > 0 ) ? true : false;
					if ($sosSmsTrigger == false)
					{
						throw new Exception("Unable to send SMS notification to emergency conatct.");
					}
				}
			}
			else
			{
				throw new Exception(json_encode($model->getErrors()));
			}
			if ($sosSmsTrigger == true)
			{
				self::updateBlankDeviceId($data['bkg_id']);
				$success = DBUtil::commitTransaction($transaction);
			}
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
			DBUtil::rollbackTransaction($transaction);
			Logger::create("errors => \n" . json_encode(stripslashes($message)), CLogger::LEVEL_INFO);
		}
		return ['success' => $success, 'message' => $message];
	}

	/**
	 * @param UserInfo $userInfo
	 * @param string $userName
	 * @return type
	 * @throws Exception
	 * @deprecated
	 */
	public function updateSosFlag($trackModel)
	{
		$userInfo = UserInfo::getInstance();

		$bModel	 = Booking::model()->findByPk($trackModel->btk_bkg_id);
		$userId	 = ($userInfo->userType == 4) ? $bModel->bkgUserInfo->bkg_user_id : $userInfo->userId;

		$UserModel	 = Users::model()->findByPk($userId);
		$userName	 = $UserModel->usr_name . " " . $UserModel->usr_lname;

		$sosContactList	 = Users::model()->getSosContactList($userId);
		$model			 = BookingTrack::model()->getByBkgId($trackModel->btk_bkg_id);
		if ($userInfo->userType != 4)
		{
			$model->bkg_sos_latitude	 = $trackModel->bkg_sos_latitude;
			$model->bkg_sos_longitude	 = $trackModel->bkg_sos_longitude;
			$model->bkg_sos_device_id	 = $trackModel->bkg_sos_device_id;
		}
		$dateTime					 = date("Y-m-d H:i:s");
		$model->bkg_sos_device_id	 = $trackModel->bkg_sos_device_id;
		$model->bkg_sos_sms_trigger	 = 1;
		$model->bkg_sos_off_by		 = $userId;
		$model->bkg_sos_off_type	 = $userInfo->userType;
		$model->bkg_sos_remarks		 = $trackModel->bkg_sos_remarks;
		$model->scenario			 = ($userInfo->userType == 4) ? 'validateSosWeb' : 'validateSosApp';

		if ($model->validate() && $model->save())
		{
			$params['blg_booking_status'] = $bModel->bkg_status;
			if ($userInfo->userType == 4)
			{
				/* @var $modelAdmin Admins */
				$modelAdmin	 = Admins::model()->findById($userInfo->userId);
				$userName	 = $modelAdmin->adm_fname . ' ' . $modelAdmin->adm_lname;
				$desc		 = "SOS ended by $userName on Date $dateTime. Remarks : " . $trackModel->bkg_sos_remarks;
			}
			else
			{
				$desc = "SOS ended by $userName at Latitude : " . $trackModel->bkg_sos_latitude . " Longitude : " . $trackModel->bkg_sos_longitude . " DeviceId : " . $trackModel->bkg_sos_device_id . " on Date $dateTime";
			}
			$eventId	 = BookingLog::SOS_TRIGGER_OFF;
			BookingLog::model()->createLog($trackModel->btk_bkg_id, $desc, $userInfo, $eventId, false, $params);
			$sosContact	 = 0;
			$message	 = $desc;
			foreach ($sosContactList as $value)
			{
				$emergencyUserName	 = $value['name'];
				$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
				$phoneNumber		 = substr($phone, -10);
				$emailAddress		 = $value['email'];
				$urlHash			 = Users::model()->createSOSHashUrl($trackModel->btk_bkg_id, $userId);
				$url				 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
				$msg				 = "PANIC Situation resolved.Track $userName current location at $url";
				if (strlen($phoneNumber) >= 10)
				{
					$msgCom		 = new smsWrapper();
					$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($trackModel->btk_bkg_id, $phoneNumber, $msg);
				}
				if ($emailAddress != '')
				{
					$emailModel		 = new emailWrapper();
					$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($trackModel->btk_bkg_id, $userName, $emergencyUserName, $emailAddress, $msg);
				}
				$sosContact		 = ($sosContact + 1);
				$sosSmsTrigger	 = ($sendSmsFlag > 0 || $sendEmailFlag > 0 ) ? true : false;
				if ($sosSmsTrigger == false)
				{
					throw new Exception("Unable to send SMS notification to emergency conatct.");
				}
			}
		}
		else
		{
			throw new Exception(json_encode($model->getErrors()));
		}


		if ($sosSmsTrigger == true)
		{
			self::updateBlankDeviceId($trackModel->btk_bkg_id);
			$success = true;
		}
		return ['success' => $success, 'message' => $message];
	}

	/* @var $bkgId integer */

	public static function updateBlankDeviceId($bkgId)
	{
		$sql = "UPDATE `booking_track` SET `bkg_sos_device_id`='' WHERE booking_track.btk_bkg_id='$bkgId'";
		return DBUtil::command($sql)->execute();
	}

	public static function countSosAlert()
	{
		$returnSet = Yii::app()->cache->get('countSosAlert');
		if ($returnSet === false)
		{
			$sql		 = "SELECT
					COUNT(
						DISTINCT booking_track.btk_bkg_id
					) AS cnt
				FROM
					`booking_track`
				INNER JOIN `booking` ON booking.bkg_id = booking_track.btk_bkg_id AND booking.bkg_status IN(2, 3, 5, 6, 7)
				WHERE booking.bkg_pickup_date BETWEEN (DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND (DATE_ADD(NOW(), INTERVAL 11 MONTH)) AND 
					(booking_track.bkg_sos_sms_trigger = 2 OR booking_track.bkg_drv_sos_sms_trigger = 2) LIMIT 0,1";
			$returnSet	 = DBUtil::queryScalar($sql, DBUtil::SDB(), [], 600, CacheDependency::Type_DashBoard);
			Yii::app()->cache->set('countSosAlert', $returnSet, 600);
		}
		return $returnSet;
	}

	public function populateFromQuote(Quote $quote)
	{
		$routeDuration			 = $quote->routeDuration;
		$this->bkg_garage_time	 = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
	}

	public function startOverDueTrip($bkgID, $platform = '', $userInfo = '')
	{
		$platformArr								 = TripOtplog::platformArr;
		$platformName								 = $platformArr[$platform];
		$userInfo									 = UserInfo::getInstance();
		$bookingModel								 = Booking::model()->findByPk($bkgID);
		$returnSet									 = new ReturnSet();
		$bookingModel->bkgTrack->bkg_ride_start		 = 1;
		$bookingModel->bkgTrack->btk_start_platform	 = $platform;
		if ($bookingModel->bkgTrack->save())
		{
			$eventId = BookingLog::RIDE_STATUS;
			$desc	 = "Overdue Ride started.";
			if ($platform == 2)
			{
				$userInfo->userId	 = $bookingModel->bkgBcb->bcb_driver_id;
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;
			}
			if ($platform == 4)
			{
				$userInfo->userId	 = $bookingModel->bkgBcb->bcb_vendor_id;
				$userInfo->userType	 = UserInfo::TYPE_VENDOR;
			}
			BookingLog::model()->createLog($bkgID, $desc, $userInfo, $eventId, $oldModel, $params);
			//BookingTrackLog::model()->addByNonDriver($platform, $bkgID, 101);
			BookingTrackLog::model()->addByNonDriver($platform, $bkgID, 101, $odoStartReading);
			$returnSet->setData(['message' => "Overdue Ride started. OTP not verified)."]);
			$returnSet->setStatus(true);
			if ($bookingModel->bkgBcb->bcb_driver_id != "")
			{
				$userInfo		 = UserInfo::getInstance();
				$type			 = Booking::model()->userArr[$userInfo->userType];
				$message		 = "Overdue Booking " . $bookingModel->bkg_booking_id . " Started by $type and OTP not verified";
				$image			 = NULL;
				$bookingID		 = $bookingModel->bkg_booking_id;
				$notificationId	 = substr(round(microtime(true) * 1000), -5);
				$payLoadData	 = ['EventCode' => Booking::CODE_TRIP_START_NOTIFICATION];
				AppTokens::model()->notifyDriver($bookingModel->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "Trip Started", $bookingID);
			}
			if ($bookingModel->bkg_booking_type != 7)
			{
				$prows			 = PenaltyRules::getValueByPenaltyType(PenaltyRules::PTYPE_RIDE_START_OVERDUE);
				$penaltyAmount	 = $prows['plt_value'];
				if ($penaltyAmount > 0)
				{
					$vendor_id		 = $bookingModel->bkgBcb->bcb_vendor_id;
					$bkg_booking_id	 = $bookingModel->bkg_booking_id;
					$remarks		 = "Ride start overdue for booking ID #$bkg_booking_id";
					$penaltyType	 = PenaltyRules::PTYPE_RIDE_START_OVERDUE;
					$result			 = AccountTransactions::checkAppliedPenaltyByType($bkgID, $penaltyType);
					if ($result)
					{
						AccountTransactions::model()->addVendorPenalty($bkgID, $vendor_id, $penaltyAmount, $remarks, '', $penaltyType);
					}
				}
			}
		}
		else
		{
			$returnSet->setData(['message' => "Unable to start ride."]);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $eventId
	 * @return boolean
	 * @throws Exception
	 */
	public function saveSOS($eventId = BookingLog::SOS_TRIGGER_ON)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		$userInfo	 = UserInfo::getInstance();
		try
		{
			if ($eventId == BookingLog::SOS_TRIGGER_ON)
			{
				$this->bkg_sos_enable_datetime	 = new CDbExpression("NOW()");
				$this->bkg_sos_on_by			 = $userInfo->userId;
				$this->bkg_sos_on_type			 = $userInfo->userType;
				$message						 = "S.O.S. activated by traveller at Coordinates: ({$this->bkg_sos_latitude},{$this->bkg_sos_longitude})";
			}
			else if ($eventId == BookingLog::SOS_TRIGGER_OFF)
			{
				$this->bkg_sos_disable_datetime	 = new CDbExpression("NOW()");
				$this->bkg_sos_off_by			 = $userInfo->userId;
				$this->bkg_sos_off_type			 = $userInfo->userType;
				$this->bkg_sos_sms_trigger		 = 1;
				$travellerName					 = $this->btkBkg->bkgUserInfo->bkgUser->usr_name . " " . $this->btkBkg->bkgUserInfo->bkgUser->usr_lname;
				$message						 = "SOS ended by $travellerName at Latitude : " . $this->bkg_sos_latitude . " Longitude : " . $this->bkg_sos_longitude . " DeviceId : " . $this->bkg_sos_device_id . " on Date " . date("Y-m-d H:i:s") . "";
			}
			if (!$this->save())
			{
				throw new Exception(CJSON::encode($this->getErrors(), ReturnSet::ERROR_VALIDATION));
			}

			BookingLog::model()->createLog($this->btk_bkg_id, $message, $userInfo, $eventId, false);
			DBUtil::commitTransaction($transaction);
			$success = true;
			$returnSet->setStatus($success);
			$returnSet->setMessage($message);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param integer $eventId
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function sendSOStoContacts($eventId = BookingLog::SOS_TRIGGER_ON)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$bkgId			 = $this->btkBkg->bkg_id;
			$userId			 = $this->btkBkg->bkgUserInfo->bkgUser->user_id;
			$sosContactList	 = $this->btkBkg->bkgUserInfo->bkgUser->getSosContactList($userId);
			$sosContact		 = 0;
			foreach ($sosContactList As $value)
			{
				$travellerName		 = $this->btkBkg->bkgUserInfo->bkgUser->usr_name . " " . $this->btkBkg->bkgUserInfo->bkgUser->usr_lname;
				$emergencyUserName	 = $value['name'];
				$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
				$phoneNumber		 = substr($phone, -10);
				$emailAddress		 = $value['email'];

				$sosContacts[]	 = $phoneNumber;
				$urlHash		 = Users::model()->createSOSHashUrl($bkgId, $userId);
				$url			 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;

				if ($eventId == BookingLog::SOS_TRIGGER_ON)
				{
					$msg = "$travellerName has pressed panic button and wants to notify you of the emergency. Track their location at $url urgently contact them. Gozo is also taking action.";
				}
				else if ($eventId == BookingLog::SOS_TRIGGER_OFF)
				{
					$msg = "PANIC Situation resolved.Track $travellerName current location at $url";
				}
				if (strlen($phoneNumber) >= 10)
				{
					$msgCom		 = new smsWrapper();
					$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($bkgId, $phoneNumber, $msg);
				}
				if ($emailAddress != '')
				{
					$emailModel		 = new emailWrapper();
					$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($bkgId, $travellerName, $emergencyUserName, $emailAddress, $msg);
				}
				$sosContact = ($sosContact + 1);
			}

			if ($sosContact > 0 && $eventId == BookingLog::SOS_TRIGGER_ON)
			{
				$this->bkg_sos_sms_trigger = 2;
				if (!$this->save())
				{
					throw new Exception(CJSON::encode($this->getErrors(), ReturnSet::ERROR_VALIDATION));
				}
				$success = true;
			}
			else if ($eventId == BookingLog::SOS_TRIGGER_OFF)
			{
				$success = true;
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	/**
	 * 
	 * @return \ReturnSet
	 */
	public function triggerSOS()
	{
		$returnSet = new ReturnSet();
		try
		{
			if ($this->bkg_sos_sms_trigger == 2)
			{
				$success = false;
				$message = 'The SOS has been already started';
				goto endSOS;
			}
			$response	 = $this->saveSOS(BookingLog::SOS_TRIGGER_ON);
			$success	 = $this->sendSOStoContacts(BookingLog::SOS_TRIGGER_ON);
			endSOS:
			$returnSet->setStatus($success);
			$returnSet->setMessage($message);
			return $returnSet;
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function updateSOS()
	{
		$returnSet	 = new ReturnSet();
		$userInfo	 = UserInfo::getInstance();
		try
		{
			if ($this->bkg_sos_sms_trigger == 1)
			{
				$success = false;
				$message = 'The SOS has been already turn off by GozoTeam';
				goto endSOS;
			}
			$response	 = $this->saveSOS(BookingLog::SOS_TRIGGER_OFF);
			$message	 = $response->getMessage();
			$success	 = $this->sendSOStoContacts(BookingLog::SOS_TRIGGER_OFF);
			if ($this->bkg_sos_sms_trigger == 1)
			{
				self::updateBlankDeviceId($this->btk_bkg_id);
			}
			endSOS:
			$returnSet->setStatus($success);
			$returnSet->setMessage($message);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for updating the coordinate details of every events
	 * in csv log file which will be further used for graphs or any other 
	 * activities
	 * 
	 * @param [Array] $syncDetails - File details
	 * @return $returnSet
	 */
	public function syncFiles($syncDetails, $userInfo)
	{
		$returnSet = new ReturnSet();

		$returnSet->setStatus(false);
		$returnSet->setMessage("Mandatory field not set");

		if (empty($syncDetails))
		{
			goto catchBlock;
		}

		try
		{
			$returnSet = BookingTrack::uploadFiles($syncDetails, $userInfo);
		}
		catch (Exception $e)
		{
			catchBlock:
			$returnSet = ReturnSet::setException($e);
		}

		return $returnSet;
	}

	public function uploadFiles($image, $imagetmp, $fileChecksum, $userType = '', $platformType = '')
	{
		$result		 = [];
		$success	 = false;
		$payDocModel = BookingPayDocs::model()->getRow($fileChecksum, $platformType);
		$msg		 = "Data from DriverApp FILE-payDocModel---" . $fileChecksum . "==bkgID" . $payDocModel['bpay_bkg_id'] . "checksum==" . $fileChecksum;
		if ($payDocModel['bpay_bkg_id'])
		{
			$path	 = $this->saveImage($image, $imagetmp, $payDocModel['bpay_bkg_id'], $type, $payDocModel['bpay_type'], $userType, $payDocModel['bpay_s3_data']);
			$msgNext = "Data from DriverApp FILE-PATH---" . $path;
			foreach ($path as $value)
			{
				$model				 = BookingPayDocs::model()->findByPk($payDocModel['bpay_id']);
				$model->bpay_image	 = $value;
				$model->bpay_status	 = 1;
				if ($userType == 2)
				{
					$model->bpay_app_type = 2;
				}
				else
				{
					$model->bpay_app_type = 5;
				}
				$model->save();
			}
			$typeName	 = Booking::model()->userArr[$userType];
			$success	 = true;
			$appSyncId	 = BookingTrackLog::model()->getAppSyncIdByBkg($model->bpay_bkg_id, $model->bpay_checksum);
			$message	 = "Saved Successfully by $typeName.";
		}
		else
		{

			$message = "Invalid CheckSum.";
			$msgNext = "Data from DriverApp FILE-ERROR---" . $message;
		}
		
		$result = ['message' => $message, 'model' => $model, 'success' => $success, 'appSyncId' => $appSyncId];
		return $result;
	}

	public function saveImage($image, $imagetmp, $bkgId, $type = 6, $imgfolderType = '', $userType = '', $s3dt = NULL)
	{
		try
		{
			$path	 = "";
			$DS		 = DIRECTORY_SEPARATOR;
			if ($image != '')
			{
				if ($userType)
				{
					$type = Booking::model()->userArr[$userType];
				}
				if ($imgfolderType == 101 || $imgfolderType == 104)
				{
					$dirFinal = 'odometer';
				}
				else
				{
					$dirFinal = 'voucher';
				}

				$mainRoot	 = Yii::app()->basePath . $DS;
				$rootpath	 = Yii::app()->basePath . $DS . 'doc';
				$dirFinal	 = $rootpath . $DS . 'bookings' . $DS . $bkgId . $DS . $type . $DS . $dirFinal;

				$file_path	 = $dirFinal;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . $DS . $file_name;

				if (file_exists($file_path) || $s3dt != NULL)
				{
					goto skipResize;
				}
				if (!is_dir($dirFinal))
				{
					mkdir($dirFinal, 0755, true);
				}

				$reSize = Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name);
				if ($reSize)
				{
					$path	 = substr($file_path, strlen($mainRoot));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		skipResize:
		return $result;
	}

	/**
	 * 
	 * @param type $model
	 * @return boolean
	 * @throws CHttpException
	 */
	public function markTripEnd()
	{
		$this->bkg_ride_complete = 1;
		$this->btk_end_platform	 = TripOtplog::Platform_DRIVERAPP;
		if (!$this->save())
		{
			throw new Exception("Failed to end trip: " . json_encode($this->errors), ReturnSet::ERROR_FAILED);
		}
		return true;
	}

	public function markTripStart($platformName, $event = null)
	{
		$returnSet = new ReturnSet();
		$returnSet->setStatus(false);

		$this->bkg_ride_start = 1;

		switch ($platformName)
		{
			case 'SMS':
				$this->btk_start_platform	 = 1;
				break;
			case 'Driver APP':
				$this->btk_start_platform	 = 2;
				break;
			case 'URL':
				$this->btk_start_platform	 = 3;
				break;
			case 'Partner APP':
				$this->btk_start_platform	 = 4;
				break;
			default:
				break;
		}
		if (!$this->save())
		{
			throw new Exception("Failed to start trip", ReturnSet::ERROR_FAILED);
		}

		$returnSet->setStatus(true);
		$msg = "Trip started by ({$platformName})."; //$response->getMessage();
		$returnSet->setMessage($msg);

		return $returnSet;
	}

	/**
	 * 
	 * @param integer $path
	 * @return boolean
	 * @throws Exception
	 */
	public function saveCSVPath($path)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$this->bkt_log_file_path = $path;
			if (!$this->save())
			{
				throw new Exception("Failed to save path", ReturnSet::ERROR_FAILED);
			}
			$success = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $event
	 * @return boolean
	 * @throws Exception
	 */
	public function setSos($event, $coordinates)
	{
		$success	 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($event == 301)
			{
				$this->startSOS($event, $coordinates);
			}
			if ($event == 302)
			{
				$this->stopSOS($event, $coordinates);
			}
			if (!$this->update())
			{
				throw new Exception("Sos triggered", ReturnSet::ERROR_FAILED);
			}
			$success = true;
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $exc)
		{
			throw new Exception("Sos triggered", ReturnSet::ERROR_FAILED);
			DBUtil::rollbackTransaction($transaction);
		}
		return $success;
	}

	public function startSOS($event, $coordinates)
	{
		$userInfo	 = UserInfo::getInstance();
		$sosFlag	 = ($event == 301) ? 2 : 1;
		if ($userInfo->userType == UserInfo::TYPE_DRIVER || $userInfo->userType == UserInfo::TYPE_VENDOR)
		{
			$this->bkg_drv_sos_sms_trigger = $sosFlag;
		}
		else if ($userInfo->userType == UserInfo::TYPE_CONSUMER)
		{
			$this->bkg_sos_sms_trigger = $sosFlag;
		}
		Logger::create("SOS Start ERROR : User Type :" . $userInfo->userType, CLogger::LEVEL_INFO);
		$this->bkg_sos_start_coordinates = $coordinates;
	}

	public function stopSOS($event, $coordinates)
	{
		$userInfo	 = UserInfo::getInstance();
		$sosFlag	 = ($event == 302) ? 1 : 2;
		if ($userInfo->userType == UserInfo::TYPE_DRIVER)
		{
			$this->bkg_drv_sos_sms_trigger = $sosFlag;
		}
		else
		{
			if ($userInfo->userType == 1)
			{
				$this->bkg_sos_sms_trigger = $sosFlag;
			}
		}
		Logger::create("SOS Stop ERROR : User Type :" . $userInfo->userType, CLogger::LEVEL_INFO);
		$this->bkg_sos_end_coordinates = $coordinates;
	}

	public function getOdometerReading($bkg_id)
	{
		$sql			 = "SELECT bkg_start_odometer FROM `booking_track` WHERE `btk_bkg_id` = $bkg_id";
		$start_odometer	 = DBUtil::command($sql)->queryScalar();
		return $start_odometer;
	}

	public static function updateLastStatus($bkgid, $eventId, $coordinates, $dateTime = '')
	{
		if (!in_array($eventId, [100, 101, 102, 103, 104, 201, 202, 203, 204, 205, 206, 111]))
		{
			return true;
		}
		$params = ['eventId' => $eventId, 'coordinates' => $coordinates, 'bkgid' => $bkgid];
		if ($dateTime != '')
		{
			$params['dateTime'] = $dateTime;
		}
		else
		{
			$params['dateTime'] = DBUtil::getCurrentTime();
		}
		$sql = "UPDATE booking_track SET btk_last_event =:eventId , btk_last_coordinates=:coordinates, btk_last_event_time = :dateTime WHERE btk_bkg_id =:bkgid";
		return DBUtil::execute($sql, $params);
	}

	public function updateSelfie($selfie)
	{
		$this->btk_is_selfie = $selfie;

		if (!$this->save())
		{
			throw new Exception("Selfie not taken", ReturnSet::ERROR_FAILED);
		}

		return true;
	}

	public function updateCovidSafety($sanitization)
	{
		$this->btk_is_sanitization_kit = $sanitization;

		if (!$this->save())
		{
			throw new Exception("Sanitization kit picture not taken", ReturnSet::ERROR_FAILED);
		}

		return true;
	}

	public function updateArrogyaSetu($flag)
	{

		$this->btk_aarogya_setu = $flag;

		if (!$this->save())
		{
			throw new Exception("Aarogya Setu information invalid ", ReturnSet::ERROR_FAILED);
		}

		return true;
	}

	public function updateTerms($flag)
	{

		$this->btk_safetyterm_agree = $flag;

		if (!$this->save())
		{
			throw new Exception("Terms and condition data  invalid ", ReturnSet::ERROR_FAILED);
		}

		return true;
	}

	public function getCovidDetails($bkg_id)
	{
		$sql	 = "SELECT `btk_is_selfie`,`btk_is_sanitization_kit`,`btk_aarogya_setu`,`btk_safetyterm_agree` FROM  booking_track where  btk_bkg_id   = $bkg_id  ORDER  BY  btk_id DESC LIMIT 1";
		$data	 = DBUtil::queryRow($sql);
		return $data;
	}

	public function updateDriverDetailsViewedFlag()
	{
		if ($this->btk_drv_details_viewed == 0)
		{
			$this->btk_drv_details_viewed			 = 1;
			$this->btk_drv_details_viewed_datetime	 = DBUtil::getCurrentTime();
			$desc									 .= "Customer viewed driver details. Cancellation charges will now apply if booking is cancelled";
			$eventid								 = BookingLog::DRIVER_DETAILS_VIEWED;
			$userInfo								 = UserInfo::getInstance();
			BookingLog::model()->createLog($this->btk_bkg_id, $desc, $userInfo, $eventid);
			if ($this->save())
			{
				$success = true;
			}
			else
			{
				$success = false;
			}
		}
		else
		{
			$success = false;
		}

		return $success;
	}

	public function updateCustomerDetailsViewedFlag()
	{
		if ($this->btk_cust_details_viewed != 1)
		{
			$this->btk_cust_details_viewed			 = 1;
			$this->btk_cust_details_viewed_datetime	 = DBUtil::getCurrentTime();
			$desc									 .= " Driver/Vendor viewed customer details .";
			$eventid								 = BookingLog::CUSTOMER_DETAILS_VIEWED;
			BookingLog::model()->createLog($this->btk_bkg_id, $desc, null, $eventid);
			if ($this->save())
			{
				$success = true;
			}
			else
			{
				$success = false;
			}
		}
		else
		{
			$success = false;
		}

		return $success;
	}

	/**
	 * This function finds the late penalty for arrived cab driver for pickup 
	 * @param type $bokingId	-	booking Id
	 * @param type $dateTime	-	Pickup date time from request
	 * @param type $ptype		-	Penalty type from request
	 * @return int
	 */
	public static function getLateArrivePenality($bookingId, $dateTime, $ptype)
	{
		$penalty = 0;

		if (empty($bookingId))
		{
			return 0;
		}

		/**
		 * Finds the time difference in pickup date
		 */
		$findTimeDiffQuery = "
			SELECT TIMESTAMPDIFF(MINUTE, bkg_pickup_date, CAST('" . $dateTime . "'  AS DATETIME)) lateTime
			FROM   booking 
			WHERE  bkg_id = $bookingId AND bkg_status IN (5,6)
		";

		$pickLate	 = DBUtil::queryRow($findTimeDiffQuery, DBUtil::SDB());
		$lateTime	 = $pickLate["lateTime"];
		$arrRules	 = PenaltyRules::getRuleByPenaltyType($ptype);
		$penalty	 = PenaltyRules::calculatePenaltyCharge($ptype, $arrRules, '', $lateTime);
//		if ($lateTime > $arrRules['time']['minimumTime'] && $lateTime <= $arrRules['time']['diffrentTime'])
//		{
//			$penalty = $arrPenaltyCharge['minimumTimeCharge'];
//		}
//		else if ($lateTime > $arrRules['time']['diffrentTime'] && $lateTime <= $arrRules['time']['maximumTime'])
//		{
//			$penalty = $arrPenaltyCharge['diffrentTimeCharge'];
//		}
//		else if ($lateTime > $arrRules['time']['maximumTime'])
//		{
//			$penalty = $arrPenaltyCharge['maximumTimeCharge'];
//		}

		return $penalty;
	}

	public static function checkCarVerifyByBkg($bkgId)
	{
		$sql = "SELECT bkg_force_verification FROM `booking_track` WHERE booking_track.btk_bkg_id='$bkgId' ";

		return DBUtil::command($sql)->queryScalar();
	}

	public static function getDiscrepancyPenality($distance, $vndAmount, $ptype)
	{
		$arrRules	 = PenaltyRules::getRuleByPenaltyType($ptype);
		$penalty	 = PenaltyRules::calculatePenaltyCharge($ptype, $arrRules, $vndAmount, $distance);
		//<= 3km: No penalty
		//$penalty = 0;
		//3 and <6: Rs 50 = 6km: 10% of VA
//		if($distance > $arrRules['range']['minimumDistance'] && $distance<=$arrRules['range']['maximumDistance'])
//		{
//			$penalty = $arrPenaltyCharge['minimumRangeCharge'];
//		}
//
//		if($distance > $arrRules['range']['maximumDistance'])
//		{
//			$penalty = $arrPenaltyCharge['maximumRangeCharge'];
//		}
		return $penalty;
	}

	/**
	 * This function finds ongoingBookings
	 * @param integer $drvID
	 * @return int
	 */
	public static function getOngoingBookings($agentId = 0)
	{
		switch ($agentId)
		{
			case 18190:
				$type	 = "MMT";
				break;
			case Config::get('spicejet.partner.id'):
				$type	 = "Spicejet";
				break;
			case Config::get('QuickRide.partner.id'):
				$type	 = "QuickRide";
				break;
			case Config::get('transferz.partner.id'):
				$type	 = "Transferz";
				break;
			default:
				break;
		}

		$methodName	 = "getOngoingBookings" . $type;
		$result		 = BookingTrack::$methodName($agentId);
		return $result;
	}

	public static function getOngoingBookingsMMT_OLD($agentId = 0)
	{
		if ($agentId == 18190)
		{
			$agentId = " AND bkg.bkg_agent_id = 18190";
		}

		$sql	 = "SELECT bkg.bkg_id FROM booking bkg
                            INNER JOIN booking_track btk ON bkg.bkg_id=btk.btk_bkg_id AND bkg_status=5 AND btk.bkg_ride_start = 1 
                            INNER JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id  
                            WHERE bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 90 MINUTE) AND btk.bkg_trip_end_time IS NULL $agentId
                                AND DATE_ADD(bkg.bkg_pickup_date, INTERVAL (bkg.bkg_trip_duration) MINUTE)>DATE_SUB(NOW(), INTERVAL 2 HOUR)
                                AND ((btk.bkg_trip_arrive_time IS NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 2 MINUTE))
                                       OR (btk.bkg_trip_arrive_time IS NOT NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 10 MINUTE))) GROUP BY btk.btk_bkg_id 
                             ";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public static function getOngoingBookingsMMT($agentId = 0)
	{
		$sql = "SELECT bkg_id 
				FROM booking 
				INNER JOIN booking_cab ON bcb_id = bkg_bcb_id 
				INNER JOIN booking_track ON bkg_id = btk_bkg_id AND bkg_status=5 AND bkg_agent_id = 18190 AND bkg_ride_complete = 0 
				INNER JOIN driver_stats ON bcb_driver_id = drs_drv_id 
				LEFT JOIN booking_track_log ON bkg_id = btl_bkg_id AND btl_event_type_id = 201 
				WHERE bkg_pickup_date < DATE_ADD(NOW(), INTERVAL 240 MINUTE) 
				AND drv_last_loc_date IS NOT NULL AND drv_last_loc_date > DATE_SUB(NOW(), INTERVAL 10 MINUTE) 
				AND (btl_event_type_id = 201 OR bkg_arrived_for_pickup = 1 OR bkg_ride_start = 1) 
				AND (
					(bkg_trip_end_time IS NULL AND bkg_booking_type NOT IN (2,3) AND DATE_ADD(bkg_pickup_date, INTERVAL (bkg_trip_duration + 60) MINUTE) > NOW()) 
					OR (bkg_trip_end_time IS NULL AND bkg_booking_type IN (2,3) AND DATE_ADD(bkg_pickup_date, INTERVAL (bkg_trip_duration + 120) MINUTE) > NOW()) 
					OR (bkg_trip_end_time IS NULL AND bkg_return_date IS NOT NULL AND DATE_ADD(bkg_return_date, INTERVAL 60 MINUTE) > NOW())
					OR (bkg_trip_end_time IS NOT NULL AND bkg_trip_end_time > DATE_SUB(NOW(), INTERVAL 5 MINUTE)) 
				)
				ORDER BY bkg_pickup_date";

		$result = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public static function getOngoingBookingsSpicejet($agentId = 0)
	{
		$spiceId = Config::get('spicejet.partner.id');
		if ($agentId == $spiceId)
		{
			$agentId = " AND bkg.bkg_agent_id = $spiceId";
		}

		$sql	 = "SELECT bkg.bkg_id FROM booking bkg
                            INNER JOIN booking_track btk ON bkg.bkg_id=btk.btk_bkg_id AND bkg_status=5 AND btk.bkg_ride_start = 1 
                            INNER JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id  
                            WHERE bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 90 MINUTE) AND btk.bkg_trip_end_time IS NULL $agentId
                                AND DATE_ADD(bkg.bkg_pickup_date, INTERVAL (bkg.bkg_trip_duration) MINUTE)>DATE_SUB(NOW(), INTERVAL 2 HOUR)
                                AND ((btk.bkg_trip_arrive_time IS NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 2 MINUTE))
                                       OR (btk.bkg_trip_arrive_time IS NOT NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 10 MINUTE))) GROUP BY btk.btk_bkg_id 
                             ";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public static function getOngoingBookingsQuickRide($agentId = 0)
	{
		$quickRideId = Config::get('QuickRide.partner.id');
		if ($agentId == $quickRideId)
		{
			$agentId = " AND bkg.bkg_agent_id = $quickRideId";
		}

		$sql	 = "SELECT bkg.bkg_id FROM booking bkg
                            INNER JOIN booking_track btk ON bkg.bkg_id=btk.btk_bkg_id AND bkg_status=5 AND btk.bkg_ride_start = 1 
                            INNER JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id  
                            WHERE bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 90 MINUTE) AND btk.bkg_trip_end_time IS NULL $agentId
                                AND DATE_ADD(bkg.bkg_pickup_date, INTERVAL (bkg.bkg_trip_duration) MINUTE)>DATE_SUB(NOW(), INTERVAL 2 HOUR)
                                AND ((btk.bkg_trip_arrive_time IS NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 2 MINUTE))
                                       OR (btk.bkg_trip_arrive_time IS NOT NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 10 MINUTE))) GROUP BY btk.btk_bkg_id 
                             ";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	public static function checkNoshowStatus($bkgId)
	{
		$sql	 = "SELECT * FROM booking_track WHERE btk_bkg_id = $bkgId AND btk_last_event = 204";
		$result	 = DBUtil::queryRow($sql);
		return $result;
	}

	/**
	 * This function finds the last on going booking by drv
	 * @param integer $drvID
	 * @return int
	 */
	public static function getOngoingBkgByDrv($drvID)
	{
		$param	 = array('driver_id' => $drvID);
		$sql	 = "SELECT bkg.bkg_id FROM booking bkg 
                    INNER JOIN booking_track btk ON btk.btk_bkg_id = bkg.bkg_id 
                    INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
                    WHERE bkg.bkg_status =5 AND bcb.bcb_driver_id = :driver_id  AND (btk.btk_last_event>0 AND btk.btk_last_event!=104) AND btk.bkg_ride_complete = 0
					ORDER BY bkg.bkg_id DESC
					LIMIT 1";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	public function trackLogList()
	{
		$eventlist = [
			100	 => 'OTP_VERIFY',
			101	 => 'TRIP_START',
			102	 => 'TRIP_PAUSE',
			103	 => 'TRIP_RESUME',
			104	 => 'TRIP_STOP',
			201	 => 'GOING_FOR_PICKUP',
			202	 => 'NOT_GOING_FOR_PICKUP',
			203	 => 'DRIVER_ARRIVED',
			204	 => 'NO_SHOW',
			205	 => 'WAIT',
			206	 => 'NO_SHOW_RESET',
			301	 => 'SOS_START',
			302	 => 'SOS_RESOLVED',
			503	 => 'VOUCHER_UPLOAD',
			504	 => 'VOUCHER_DELETED',
			107	 => 'TRIP_SELFIE',
			108	 => 'TRIP_SANITIZER_KIT',
			109	 => 'TRIP_ARROGYA_SETU',
			110	 => 'TRIP_TERMS_AGREE',
			533	 => 'SOS_ON_NOTIFICATION',
			534	 => 'SOS_OFF_NOTIFICATION',
			500	 => 'TRIP_FILE_UPLOAD'
		];

		asort($eventlist);
		return $eventlist;
	}

	public function trackLogListForDCO()
	{
		$eventlist = [
			105	 => 'ODOMETER_START_FILE',
			106	 => 'ODOMETER_STOP_FILE',
			151	 => 'CAB_FRONT_FILE',
			152	 => 'CAB_BACK_FILE',
			153	 => 'CAB_LEFT_FILE',
			154	 => 'CAB_RIGHT_FILE',
			161	 => 'TOLL_TAX_FILE',
			162	 => 'STATE_TAX_FILE',
			163	 => 'PARKING_CHARGES_FILE',
			164	 => 'DUTY_SLIP_FILE',
			261	 => 'TOLL_TAX_FILE_DELETE',
			262	 => 'STATE_TAX_FILE_DELETE',
			263	 => 'PARKING_CHARGES_FILE_DELETE',
			264	 => 'DUTY_SLIP_FILE_DELETE',
			260	 => 'OTHERS_FILE_DELETE',
			261	 => 'SELFI_FILE',
			262	 => 'SANITIZER_FILE'];
		asort($eventlist);
		return $eventlist;
	}

	/**
	 * 
	 * This function is used for updating coordinates
	 * @param object $data
	 * @param integer $bkgId
	 */
	public function updateLastLocation($data, $bkgId)
	{
		$coordinates						 = $data->coordinates->latitude . ',' . $data->coordinates->longitude;
		$model								 = BookingTrack::model()->find('btk_bkg_id = :bkgId', array('bkgId' => $bkgId));
		$model->btk_last_coordinates		 = $coordinates;
		$model->btk_last_coordinates_time	 = new CDbExpression('NOW()');
		$model->save();
	}

	public static function sendMessagesToLatestRideCompleted()
	{
		$sql = "SELECT bkg.bkg_id, bkg.bkg_booking_id, bkg.bkg_pickup_date 
				FROM booking bkg 
				INNER JOIN booking_track btk ON bkg.bkg_id = btk.btk_bkg_id 
				INNER JOIN booking_trail btr ON bkg.bkg_id = btr.btr_bkg_id 
				WHERE bkg.bkg_agent_id IS NULL AND bkg.bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
				AND (
					(btk.bkg_ride_complete = 1 AND (btk.bkg_trip_end_time BETWEEN DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND NOW()))
				OR
					(bkg.bkg_status IN (6,7) AND btr.bkg_followup_active = 0 AND (btr.btr_mark_complete_date BETWEEN DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND NOW()))
				)";

		$rideCompletedData = DBUtil::query($sql, DBUtil::SDB());

		foreach ($rideCompletedData as $v)
		{

			Booking::bookingReview($v['bkg_id'],0);
//			$response = WhatsappLog::bookingReviewToCustomer($v['bkg_id']);
//			if (!$response || $response['status'] == 3)
//			{
//				$status = smsWrapper::sendRatingLink($v['bkg_id'], $v['bkg_booking_id']);
//			}
		}
	}

	/*
	 * This function is for populating report for Region wise Vendor wise App usage report 
	 * @param $arr array of filters
	 * 
	 */

	public static function getVendorwiseAppusageDetails($arr = [])
	{
		$date1		 = $arr['bkg_pickup_date1'];
		$date2		 = $arr['bkg_pickup_date2'];
		$vendorIds	 = (!empty($arr['bcb_vendor_id']) ? $arr['bcb_vendor_id'] : "");
		$region		 = $arr['bkg_region'];
		//print_r($arr);die;
		$sqlByDate	 = '';
		$sqlRegion1	 = '';
		$sqlRegion2	 = '';
		$sqlVendor	 = '';
		if ($date1 != '' && $date2 != '')
		{
			$sqlByDate .= "AND bkg_pickup_date BETWEEN '$date1' AND '$date2'";
		}
		if (!empty($arr['bcb_vendor_id']) && $vendorIds != '')
		{
			//$vendorIds = (!empty($arr['bcb_vendor_id'])?explode(",",$arr['bcb_vendor_id']):"");
			$sqlVendor = " AND v1.vnd_id IN (" . $vendorIds . ")";
		}
		if (isset($region) && $region != '')
		{
			//$sqlRegion1	 .= "AND state1.stt_zone=$region";
			$sqlRegion .= " AND states.stt_zone =$region";
		}
		$sql		 = "SELECT v1.vnd_id, v1.vnd_code, v1.vnd_name, v1.vnd_ref_code, cities.cty_display_name, states.stt_name, states.stt_zone ,bkg_pickup_date,
					(CASE WHEN (states.stt_zone='1') THEN 'North'
								WHEN (states.stt_zone='2') THEN 'West'
								WHEN (states.stt_zone='3') THEN 'Central'
								WHEN (states.stt_zone='4') THEN 'South'
								WHEN (states.stt_zone='5') THEN 'East'
								WHEN (states.stt_zone='6') THEN 'North East'
							   END) AS vendor_region,
					GROUP_CONCAT(DISTINCT IF(apt.apt_id IS NULL AND btl.btl_bkg_id IS NULL, bkg_id, NULL) SEPARATOR ', ') AS not_loggedin,
					GROUP_CONCAT(DISTINCT IF(apt.apt_id IS NOT NULL AND btl.btl_bkg_id IS NULL, bkg_id, NULL) SEPARATOR ', ') AS not_left,
					GROUP_CONCAT(DISTINCT IF(btl.btl_bkg_id IS NOT NULL AND bkg_trip_arrive_time IS NULL, bkg_id, NULL) SEPARATOR ', ') AS not_arrived,
					GROUP_CONCAT(DISTINCT IF(bkg_trip_arrive_time IS NOT NULL AND bkg_trip_start_time IS NULL , bkg_id, NULL) SEPARATOR ', ') AS not_started,
					GROUP_CONCAT(DISTINCT IF(bkg_trip_start_time IS NOT NULL AND bkg_trip_end_time IS NULL, bkg_id, NULL) SEPARATOR ', ') AS not_ended,
					GROUP_CONCAT(DISTINCT IF(bkg_trip_arrive_time IS NOT NULL AND a1.aat_id IS NULL, bkg_id, NULL) SEPARATOR ', ') AS ArriveAPIFail,
					GROUP_CONCAT(DISTINCT IF(a1.aat_id IS NOT NULL AND bkg_trip_start_time IS NOT NULL AND a2.aat_id IS NULL, bkg_id, NULL) SEPARATOR ', ') AS StartAPIFail,
					GROUP_CONCAT(DISTINCT IF(bkg_trip_end_time IS NOT NULL AND a2.aat_id IS NOT NULL AND a3.aat_id IS NULL, bkg_id, NULL) SEPARATOR ', ') AS EndAPIFail,
					CONCAT(DATE_FORMAT(MIN(bkg_pickup_date),'%d-%m-%Y'), ' - ', DATE_FORMAT(MAX(bkg_pickup_date),'%d-%m-%Y')) AS date_range,
					COUNT(DISTINCT bkg_id) booking_count,
					COUNT(DISTINCT IF(apt.apt_id IS NULL, bkg_id, NULL)) AS not_loggedin_count,
					COUNT(DISTINCT IF(btl.btl_bkg_id IS NULL, bkg_id, NULL)) AS left_count,
					COUNT(DISTINCT IF(bkg_trip_arrive_time IS NULL, bkg_id, NULL)) AS arrived_count,
					COUNT(DISTINCT IF(bkg_trip_start_time IS NULL, bkg_id, NULL)) AS start_count,
					COUNT(DISTINCT IF(bkg_trip_end_time IS NULL, bkg_id, NULL)) AS end_count,
					ROUND(COUNT(DISTINCT IF(bkg_trip_arrive_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) AS arrived_percent,
					ROUND(COUNT(DISTINCT IF(bkg_trip_start_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) AS start_percent,
					ROUND(COUNT(DISTINCT IF(bkg_trip_end_time IS NULL, bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg_id),0) AS end_percent,
					ROUND(COUNT(DISTINCT IF(a1.aat_id IS NULL AND bkg_agent_id IN (18190), bkg_id, NULL)) * 100 / COUNT(DISTINCT IF(bkg_agent_id IN (18190), bkg_id, NULL)),0) AS arrived_api_percent,
					ROUND(COUNT(DISTINCT IF(a2.aat_id IS NULL AND bkg_agent_id IN (18190), bkg_id, NULL)) * 100 / COUNT(DISTINCT IF(bkg_agent_id IN (18190), bkg_id, NULL)),0) AS start_api_percent,
					ROUND(COUNT(DISTINCT IF(a3.aat_id IS NULL AND bkg_agent_id IN (18190), bkg_id, NULL)) * 100 / COUNT(DISTINCT IF(bkg_agent_id IN (18190), bkg_id, NULL)),0) AS end_api_percent
					FROM booking 
					INNER JOIN booking_track btk ON bkg_id = btk_bkg_id
					INNER JOIN booking_cab ON bcb_id=bkg_bcb_id 
					INNER JOIN vendors v ON vnd_id=bcb_vendor_id 
					INNER JOIN vendors v1 ON v.vnd_id=v1.vnd_ref_code 
					INNER JOIN contact_profile cp1 ON cp1.cr_is_vendor=v1.vnd_id 
					INNER JOIN contact c1 ON c1.ctt_id=cp1.cr_contact_id 
					LEFT JOIN cities ON c1.ctt_city=cities.cty_id 
					LEFT JOIN states ON cities.cty_state_id=states.stt_id 
					LEFT JOIN app_tokens apt ON apt.apt_entity_id=bcb_driver_id AND apt_last_login>DATE_SUB(bkg_pickup_date, INTERVAL 2 HOUR) AND apt_status=1 
					LEFT JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id AND btl.btl_event_type_id=201
					LEFT JOIN agent_api_tracking a4 ON bkg_id = a4.aat_booking_id AND a4.aat_type = 15 AND a4.aat_status=1
					LEFT JOIN agent_api_tracking a1 ON bkg_id = a1.aat_booking_id AND a1.aat_type = 18 AND a1.aat_status=1
					LEFT JOIN agent_api_tracking a2 ON bkg_id = a2.aat_booking_id AND a2.aat_type = 12 AND a2.aat_status=1
					LEFT JOIN agent_api_tracking a3 ON bkg_id = a3.aat_booking_id AND a3.aat_type = 13 AND a3.aat_status=1
					 WHERE bkg_status IN (5,6,7)							
							$sqlByDate
						AND bkg_vehicle_type_id NOT IN (5,6,75) $sqlVendor $sqlRegion
					GROUP BY v1.vnd_ref_code
					";
		//echo "<pre>".$sql;die;	
		$sqlCount	 = "SELECT v1.vnd_id
					FROM booking 
					INNER JOIN booking_track btk ON bkg_id = btk_bkg_id
					INNER JOIN booking_cab ON bcb_id=bkg_bcb_id 
					INNER JOIN vendors v ON vnd_id=bcb_vendor_id 
					INNER JOIN vendors v1 ON v.vnd_id=v1.vnd_ref_code 
					INNER JOIN contact_profile cp1 ON cp1.cr_is_vendor=v1.vnd_id 
					INNER JOIN contact c1 ON c1.ctt_id=cp1.cr_contact_id 
					LEFT JOIN cities ON c1.ctt_city=cities.cty_id 
					LEFT JOIN states ON cities.cty_state_id=states.stt_id 
					LEFT JOIN app_tokens apt ON apt.apt_entity_id=bcb_driver_id AND apt_last_login>DATE_SUB(bkg_pickup_date, INTERVAL 2 HOUR) AND apt_status=1 
					LEFT JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id AND btl.btl_event_type_id=201
					LEFT JOIN agent_api_tracking a4 ON bkg_id = a4.aat_booking_id AND a4.aat_type = 15 AND a4.aat_status=1
					LEFT JOIN agent_api_tracking a1 ON bkg_id = a1.aat_booking_id AND a1.aat_type = 18 AND a1.aat_status=1
					LEFT JOIN agent_api_tracking a2 ON bkg_id = a2.aat_booking_id AND a2.aat_type = 12 AND a2.aat_status=1
					LEFT JOIN agent_api_tracking a3 ON bkg_id = a3.aat_booking_id AND a3.aat_type = 13 AND a3.aat_status=1
					 WHERE bkg_status IN (5,6,7) 							
							$sqlByDate
						AND bkg_vehicle_type_id NOT IN (5,6,75) $sqlVendor $sqlRegion
					GROUP BY v1.vnd_ref_code";
		if ($type == 'command')
		{
			return DBUtil::queryAll($sql, DBUtil::SDB());
		}
		else
		{
			$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB())->queryScalar();
			$dataprovider	 = new CSqlDataProvider($sql, [
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['date_range', 'booking_count', 'not_loggedin_count', 'left_count', 'arrived_count',
						'start_count', 'end_count', 'arrived_percent', 'start_percent', 'end_percent', 'arrived_api_percent',
						'start_api_percent', 'end_api_percent'
					], 'defaultOrder'	 => 'bkg_pickup_date DESC'],
				'pagination'	 => ['pageSize' => 50],
			]);

			return $dataprovider;
		}
	}

	/**
	 * @param int $bkgId
	 * @param int $eventId
	 * @return int | false
	 *  */
	public static function checkSOSTrigger($bkgId, $eventId)
	{
		$sql	 = "SELECT * FROM `booking_track_log` WHERE btl_bkg_id = :bkgId AND btl_event_type_id = :eventId LIMIT 0,1";
		$params	 = ["bkgId" => $bkgId, "eventId" => $eventId];
		$sos	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $sos;
	}

	public static function getBookingTravelTime($bkgId)
	{
		$sql = "SELECT timestampdiff(minute,bkg_trip_start_time,bkg_trip_end_time) AS travelTime FROM `booking_track` WHERE bkg_ride_complete=1 AND bkg_trip_start_time IS NOT NULL AND bkg_trip_end_time IS NOT NULL AND btk_bkg_id=:bkgId";
		return DBUtil::queryScalar($sql, DBUtil::SDB(), ['bkgId' => $bkgId]);
	}

	public static function uploadAllToS3($limit = 1000)
	{
		while ($limit > 0)
		{
			$limit1 = min([1000, $limit]);

			// Server Id
			$serverId = Config::getServerID();
			if ($serverId == '' || $serverId <= 0)
			{
				Logger::writeToConsole('Server ID not found!!!');
				break;
			}

			$condFilePath = " AND (btk_gpx_s3_data IS NULL AND btk_gpx_file LIKE '%/doc/{$serverId}/bookings/%') ";

			$sql = "SELECT btk_bkg_id FROM booking_track WHERE 1 {$condFilePath} ORDER BY btk_bkg_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql);
			if ($res->getRowCount() == 0)
			{
				break;
			}

			foreach ($res as $row)
			{
				$bkgModel = Booking::model()->findByPk($row['btk_bkg_id']);
				$bkgModel->bkgTrack->uploadToS3();
			}

			$limit -= $limit1;
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3($removeLocal = true)
	{
		$spaceFile = null;
		try
		{
			$bkgTrackModel	 = $this;
			$path			 = $this->getLocalPath();

			if (!file_exists($path) || $this->btk_gpx_file == '')
			{
				if ($bkgTrackModel->btk_gpx_s3_data == '')
				{
					$bkgTrackModel->btk_gpx_s3_data = "{}";
					$bkgTrackModel->save();
				}
				return null;
			}
			$spaceFile = $bkgTrackModel->uploadToSpace($path, $this->getSpacePath($path), $removeLocal);

			$bkgTrackModel->btk_gpx_s3_data = $spaceFile->toJSON();
			$bkgTrackModel->save();
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFile;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($localFile, $spaceFile, $removeLocal = true)
	{
		$objSpaceFile = Storage::uploadFile(Storage::getGPXFileSpace(), $spaceFile, $localFile, $removeLocal);
		return $objSpaceFile;
	}

	public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function getLocalPath()
	{
		$filePath = $this->btk_gpx_file;

		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getBaseDocPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $this->btk_gpx_file;
		}

		return $filePath;
	}

	public function getSpacePath($localPath)
	{
		$fileName = basename($localPath);

		/** @var BookingTrack $this */
		$id			 = $this->btkBkg->bkg_id;
		$date		 = $this->btkBkg->bkg_pickup_date;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d");
		$path		 = "/{$dateString}/{$id}/{$fileName}";

		return $path;
	}

	public static function checkTripArrivedOnTime($pickupDate, $tripArrivedTime, $isArrivedForPickup)
	{
		$tripStartedOnTime	 = false;
		$pickupData			 = strtotime($pickupDate);
		$arrivedTime		 = strtotime($tripArrivedTime);

		if ($isArrivedForPickup == 1 && $arrivedTime <= $pickupData)
		{
			$tripStartedOnTime = true;
		}
		return $tripStartedOnTime;
	}

	/**
	 * 
	 * @param int $bkgId
	 * @param int $flag
	 * @return int
	 */
	public static function updateVendorReadyToPickupConfirmation($bkgId, $flag = 0)
	{
		$params	 = ['flag' => $flag, 'bkgId' => $bkgId];
		$sql	 = "UPDATE booking_track SET btk_vendor_pickup_confirm=:flag
  					WHERE btk_bkg_id=:bkgId";
		$numrows = DBUtil::execute($sql, $params);
		return $numrows;
	}

	/**
	 * 
	 * @param int $bkgId
	 * @param int $flag
	 * @return int
	 */
	public static function updateDriverReadyToPickupConfirmation($bkgId, $flag = 0)
	{
		$params	 = ['flag' => $flag, 'bkgId' => $bkgId];
		$sql	 = "UPDATE booking_track SET btk_driver_pickup_confirm=:flag
  					WHERE btk_bkg_id=:bkgId";
		$numrows = DBUtil::execute($sql, $params);
		return $numrows;
	}

	public static function getTripEvents($eventId = null)
	{
		$eventTitles						 = array();
		$eventTitles[self::TRIP_START]		 = 'TRIP_START';
		$eventTitles[self::TRIP_STOP]		 = 'TRIP_END';
		$eventTitles[self::GOING_FOR_PICKUP] = 'LEFT_FOR_PICKUP';
		$eventTitles[self::DRIVER_ARRIVED]	 = 'DRIVER_ARRIVED';
		if ($eventId > 0)
		{
			return (isset($eventTitles[$eventId]) ? $eventTitles[$eventId] : '');
		}
		return $eventTitles;
	}

	/**
	 * 
	 * @param type $agentId
	 * @return type
	 */
	public static function getOngoingBookingsTransferz($agentId = 0)
	{
		$transferzId = Config::get('transferz.partner.id');
		if ($agentId == $transferzId)
		{
			$agentId = " AND bkg.bkg_agent_id = $transferzId";
		}

		$sql	 = "SELECT bkg.bkg_id FROM booking bkg
                            INNER JOIN booking_track btk ON bkg.bkg_id=btk.btk_bkg_id AND bkg_status=5 AND btk.bkg_ride_start = 1 
                            INNER JOIN booking_track_log btl ON bkg_id = btl.btl_bkg_id  
                            WHERE bkg_pickup_date<DATE_ADD(NOW(), INTERVAL 90 MINUTE) AND btk.bkg_trip_end_time IS NULL $agentId
                                AND DATE_ADD(bkg.bkg_pickup_date, INTERVAL (bkg.bkg_trip_duration) MINUTE)>DATE_SUB(NOW(), INTERVAL 2 HOUR)
                                AND ((btk.bkg_trip_arrive_time IS NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 2 MINUTE))
                                       OR (btk.bkg_trip_arrive_time IS NOT NULL AND btk.btk_last_coordinates_time>DATE_SUB(NOW(), INTERVAL 10 MINUTE))) GROUP BY btk.btk_bkg_id 
                             ";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		return $result;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $cordinates
	 * @param type $response
	 * @param type $event
	 * @return boolean
	 */
	public static function updateTrackingDetails($model, $cordinates, $response, $event)
	{
		$sucess		 = false;
		$transaction = DBUtil::beginTransaction();
		try
		{
			$trackModel								 = $model->bkgTrack;
			#$trackModel->btk_bkg_id					= $model->bkg_id;
			$trackModel->btk_last_event				 = $event;
			$trackModel->btk_last_event_time		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
			$trackModel->btk_last_coordinates		 = $cordinates;
			$trackModel->btk_last_coordinates_time	 = Filter::convert_utc_to_general_format($response->locUpdatedTime);

			switch ($event)
			{
				case BookingTrack::TRIP_STOP:
					$trackModel->bkg_trip_end_time			 = Filter::convert_utc_to_general_format($response->actualEndTime);
					$trackModel->bkg_trip_end_coordinates	 = $cordinates;
					break;
				case BookingTrack::TRIP_START:
					$trackModel->bkg_ride_start				 = 1;
					$trackModel->bkg_trip_start_time		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					$trackModel->bkg_trip_start_coordinates	 = $cordinates;
					break;
				case BookingTrack::DRIVER_ARRIVED:
					$trackModel->bkg_trip_arrive_time		 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					$trackModel->bkg_arrived_for_pickup		 = 1;
					break;
				case BookingTrack::UPDATE_LAST_LOCATION:
					$trackModel->btk_last_coordinates_time	 = Filter::convert_utc_to_general_format($response->locUpdatedTime);
					break;
				default:
				//
			}

			if ($trackModel->save())
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

}
