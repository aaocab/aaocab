<?php

/**
 * This is the model class for table "driver_stats".
 *
 * The followings are the available columns in table 'driver_stats':
 * @property integer $drs_id
 * @property integer $drs_drv_id
 * @property string $drs_drv_overall_rating
 * @property integer $drs_total_trip
 * @property string $drs_trust_score
 * @property integer $drs_no_of_star
 * @property integer $drs_doc_score
 * @property integer $drs_no_of_star
 * @property integer $drs_total_trips
 * @property string $drs_last_trip_date
 * @property string $drv_last_loc_lat
 * @property string $drv_last_loc_long
 * @property string $drv_last_loc_device_id
 * @property string $drv_last_loc_date
 * @property string $drs_modified_date
 * @property integer $drs_active
 * @property integer $drs_lock_status
 * @property string $drs_lock_status_info
 * @property string $drs_reason
 * @property integer $drs_OW_Count
 * @property integer $drs_RT_Count
 * @property integer $drs_AT_Count
 * @property integer $drs_PT_Count
 * @property integer $drs_FL_Count
 * @property integer $drs_SH_Count
 * @property integer $drs_CT_Count
 * @property integer $drs_DR_4HR_Count
 * @property integer $drs_DR_8HR_Count
 * @property integer $drs_DR_12HR_Count
 * @property integer $drs_AP_Count
 * @property integer $drs_drv_coin_cnt
 */
class DriverStats extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'driver_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('drs_drv_id', 'required'),
			array('drs_drv_id, drs_total_trip, drs_no_of_star, drs_active,drs_OW_Count,drs_RT_Count,drs_AT_Count,drs_PT_Count,drs_FL_Count,drs_SH_Count,drs_CT_Count,drs_DR_4HR_Count,drs_DR_8HR_Count,drs_DR_12HR_Count,drs_AP_Count,drs_drv_coin_cnt', 'numerical', 'integerOnly' => true),
			array('drs_drv_overall_rating, drs_trust_score', 'length', 'max' => 10),
			array('drs_drv_id, drs_doc_score, drs_active', 'required', 'on' => 'updateReadyApproval'),
			array('drs_drv_id, drs_active', 'required', 'on' => 'updateStats'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('drs_id, drs_drv_id, drs_drv_overall_rating, drs_total_trip, drs_trust_score, drs_doc_score, drs_no_of_star, drs_total_trips, drs_last_trip_date, drv_last_loc_lat, drv_last_loc_long, drv_last_loc_device_id, drv_last_loc_date, drs_modified_date, drs_active,drs_lock_status,drs_lock_status_info,drs_reason,drs_OW_Count,drs_RT_Count,drs_AT_Count,drs_PT_Count,drs_FL_Count,drs_SH_Count,drs_CT_Count,drs_DR_4HR_Count,drs_DR_8HR_Count,drs_DR_12HR_Count,drs_AP_Count,drs_drv_coin_cnt', 'safe', 'on' => 'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'drs_id'				 => 'Drs',
			'drs_drv_id'			 => 'Drs Drv',
			'drs_drv_overall_rating' => 'Drs Drv Overall Rating',
			'drs_total_trip'		 => 'Drs Total Trip',
			'drs_trust_score'		 => 'Drs Trust Score',
			'drs_no_of_star'		 => 'Drs No Of Star',
			'drs_total_trips'		 => 'Drs No Of Trip',
			'drs_last_trip_date'	 => 'Drs No Of Trip Date',
			'drs_modified_date'		 => 'Drs Modified Date',
			'drs_active'			 => 'Drs Active',
			'drs_lock_status'		 => 'Drs lock',
			'drs_lock_status_info'	 => 'Drs Lonk Info',
			'drs_reason'			 => 'Drs Reason'
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

		$criteria->compare('drs_id', $this->drs_id);
		$criteria->compare('drs_drv_id', $this->drs_drv_id);
		$criteria->compare('drs_drv_overall_rating', $this->drs_drv_overall_rating, true);
		$criteria->compare('drs_total_trip', $this->drs_total_trip);
		$criteria->compare('drs_trust_score', $this->drs_trust_score, true);
		$criteria->compare('drs_no_of_star', $this->drs_no_of_star);
		$criteria->compare('drs_total_trips', $this->drs_total_trips);
		$criteria->compare('drs_last_trip_date', $this->drs_last_trip_date);
		$criteria->compare('drs_modified_date', $this->drs_modified_date, true);
		$criteria->compare('drs_active', $this->drs_active);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DriverStats the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getbyDriverId($driverID)
	{
		$criteria	 = new CDbCriteria;
		$criteria->compare('drs_drv_id', $driverID);
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

	public function saveScore($val)
	{
		$driverID = $val['bcb_driver_id'];

		$numberOfTrip	 = $val['cnt'];
		$results		 = Ratings::CalculateRating($val);
		$star			 = $results["star"];
		$rating			 = $results["rating"];   // this is trust score value  
		$model			 = DriverStats::model()->getbyDriverId($driverID);
		if ($model == null)
		{
			$model				 = new DriverStats();
			$model->drs_drv_id	 = $driverID;
		}
		$model->drs_active				 = 1;
		$model->drs_drv_overall_rating	 = round($rating/ 2, 1);  // rating = trust score value /2  
		$model->drs_total_trip			 = $numberOfTrip;
		$model->drs_trust_score			 = round($rating, 2);
		$model->drs_no_of_star			 = $star;
		$model->drs_modified_date		 = new CDbExpression('NOW()');
		if ($model->validate())
		{
			if ($model->save())
			{
				return $model;
			}
			else
			{
				return false;
			}
		}
		else
		{

			return false;
		}
	}

	public static function fetchRating($driverId)
	{
		$sql	 = "SELECT driver_stats.drs_drv_overall_rating
				FROM   `drivers`
					 JOIN `driver_stats` ON driver_stats.drs_drv_id = drivers.drv_id
			    WHERE  drivers.drv_id IN 
				(SELECT d3.drv_id FROM drivers d1
					INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
					INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$driverId') AND drivers.drv_id=drivers.drv_ref_code ";
		//$row	 = DBUtil::queryRow($sql);\
		$row	 = DBUtil::queryRow($sql);
		$rating	 = ($row['drs_drv_overall_rating'] > 0) ? $row['drs_drv_overall_rating'] : 4;
		return $rating;
	}

	public function insertEmplyStats($type, $drv_id)
	{
		switch ($type)
		{
			case 0:
				$where	 = $drv_id > 0 ? " and  drs_drv_id=$drv_id " : "";
				$where1	 = $drv_id > 0 ? " and  bcb_driver_id=$drv_id " : "";
				$sql	 = "SELECT	bcb_driver_id
						FROM `booking`
						INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 AND booking.bkg_active = 1 
						AND booking.bkg_status IN(5, 6, 7) AND booking_cab.bcb_driver_id IS NOT NULL $where1
						WHERE booking.bkg_create_date>= '2015-10-01' AND booking_cab.bcb_driver_id NOT IN (SELECT	driver_stats.drs_drv_id	FROM `driver_stats` WHERE driver_stats.drs_active = 1 $where	)
						GROUP BY booking_cab.bcb_driver_id";
				break;
			case 1:
				$where	 = $drv_id > 0 ? " and  drs_drv_id=$drv_id " : "";
				$sql	 = "SELECT	app_tokens.apt_entity_id AS bcb_driver_id
						FROM `app_tokens`
						LEFT JOIN `driver_stats` ON driver_stats.drs_drv_id = app_tokens.apt_entity_id 
						WHERE	app_tokens.apt_user_type = 5 AND app_tokens.apt_entity_id IS NOT NULL AND app_tokens.apt_status = 1 AND driver_stats.drs_id IS NULL $where
						GROUP BY bcb_driver_id";
				break;
		}
		$rows	 = DBUtil::queryAll($sql);
		$count	 = count($rows);
		if ($count > 0)
		{
			foreach ($rows as $row)
			{
				$model				 = new DriverStats();
				$model->scenario	 = 'updateStats';
				$model->drs_drv_id	 = $row['bcb_driver_id'];
				$model->drs_active	 = 1;
				if ($model->save())
				{
					$success = true;
				}
				else
				{
					$success = false;
				}
			}
		}
		return $count;
	}

	public function updateLastLocation($data)
	{
		$driverId	 = UserInfo::getEntityId();
		$model		 = DriverStats::model()->getbyDriverId($driverId);
		$bModel		 = Booking::model()->findByPk($data['bkg_id']);
		if (!$model)
		{
			$model				 = new DriverStats();
			$model->drs_drv_id	 = $driverId;
		}
		$model->drv_last_loc_lat	 = $data['lat'];
		$model->drv_last_loc_long	 = $data['lon'];
		if ($data['lat'] == "" || $data['lat'] == NULL)
		{
			\Sentry\captureMessage("No location update from app: " . json_encode($data));
		}
		$model->drv_last_loc_date		 = new CDbExpression("now()");
		$model->drv_last_loc_device_id	 = $data['deviceId'];
		$success						 = $model->save();
		if ($data['sosTriggered'] && $bModel->bkgTrack->bkg_drv_sos_sms_trigger == 2)
		{
			$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'drvsostriptracking';
			if (!is_dir($dir))
			{
				mkdir($dir);
			}
			$dirTrip = $dir . DIRECTORY_SEPARATOR . $driverId;
			if (!is_dir($dirTrip))
			{
				mkdir($dirTrip);
			}
			$dirFolderName = $dirTrip . DIRECTORY_SEPARATOR . $data['bkg_id'];
			if (!is_dir($dirFolderName))
			{
				mkdir($dirFolderName);
			}
			$date		 = date("Y-d-m H:i:s");
			$dataResult	 = [];
			$transaction = DBUtil::beginTransaction();
			try
			{
				if ($data != '' && $deviceId == $data['deviceId'] || $data['bkg_id'] != null)
				{
					$bModel										 = Booking::model()->findByPk($data['bkg_id']);
					$bModel->bkgTrack->bkg_trip_end_coordinates	 = $data['lat'] . ',' . $data['lon'];
					$bModel->bkgTrack->save();
					$updateRows									 = [];
					$file										 = $dirFolderName . "/drvSosTripTracking.csv";
					if (!file_exists($file))
					{
						$handle = fopen($file, 'w');
						fputcsv($handle, array("BkgId", "SOS_lat", "SOS_long", "Received_On", "Device_Id"));

						fputcsv($handle, array($data['bkg_id'], $data['lat'], $data['lon'], $date, $data['deviceId']));
						$updateRows[] = $data['bkg_id'];
						fclose($handle);
					}
					else
					{
						$handle			 = fopen($file, 'a');
						fputcsv($handle, array($data['bkg_id'], $data['lat'], $data['lon'], $date, $data['deviceId']));
						$updateRows[]	 = $data['bkg_id'];

						fclose($handle);
					}
					$success = true;
					$message = 'Last Row Id Inserted =' . $data['bkg_id'];
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$success = false;
				$message = $ex->getMessage();
				Logger::create("Errors.\n\t\t" . $message, CLogger::LEVEL_ERROR);
			}
		}
		$success = ($success == true) ? true : false;
		return $success;
	}

	public function saveDriverSosLocation($sosSmsTrigger, $data, $driverId)
	{
		$latitude	 = $data['lat'];
		$longitude	 = $data['lon'];
		$userInfo	 = UserInfo::getInstance();
		$drvModel	 = Drivers::model()->findByPk($driverId);
		$bModel		 = Booking::model()->findByPk($data['bkg_id']);
		$driverName	 = $drvModel->drv_name;
		$dateTime	 = date("Y-m-d H:i:s");
		$desc		 = "S.O.S. activated by $driverName at Latitude:$latitude Longitude:$longitude on Date $dateTime.";
		if ($bModel)
		{
			$isSosSmsTrigger								 = ($latitude == 0.0 || $longitude == 0.0) ? 1 : $sosSmsTrigger;
			$bModel->bkgTrack->bkg_drv_sos_latitude			 = $latitude - 0.0;
			$bModel->bkgTrack->bkg_drv_sos_longitude		 = $longitude - 0.0;
			$bModel->bkgTrack->bkg_drv_sos_enable_datetime	 = $dateTime;
			$bModel->bkgTrack->bkg_drv_sos_sms_trigger		 = $isSosSmsTrigger;
			$bModel->bkgTrack->bkg_drv_sos_device_id		 = $data['deviceId'];
			$bModel->bkgTrack->save();
		}
		$eventId = BookingLog::SOS_TRIGGER_ON;
		BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, $eventId, false);
		return $isSosSmsTrigger;
	}

	public function updateDriverSosTriggerFlag($data, $userId, $driverId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$latitude		 = $data['lat'];
		$longitude		 = $data['lon'];
		$bModel			 = Booking::model()->findByPk($data['bkg_id']);
		$sosContactList	 = Users::model()->getSosContactList($userId);
		$UserModel		 = Users::model()->findByPk($userId);
		$userInfo		 = UserInfo::getInstance();
		$drvModel		 = Drivers::model()->findById($driverId);
		$drvname		 = $drvModel->drv_name;
		$statsModel		 = $this->getbyDriverId($driverId);

		$sosLatitude	 = $statsModel->drv_last_loc_lat;
		$sosLongitude	 = $statsModel->drv_last_loc_long;
		$deviceId		 = $bModel->bkgTrack->bkg_drv_sos_device_id;
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			if ($bModel)
			{
				if ($deviceId == $data['deviceId'])
				{
					$bModel->bkgTrack->bkg_drv_sos_latitude			 = $data['lat'];
					$bModel->bkgTrack->bkg_drv_sos_longitude		 = $data['lon'];
					$bModel->bkgTrack->bkg_drv_sos_disable_datetime	 = date("Y-m-d H:i:s");
					$bModel->bkgTrack->bkg_drv_sos_sms_trigger		 = 1;
					$bModel->bkgTrack->bkg_drv_sos_device_id		 = $data['deviceId'];

					$params['blg_booking_status']	 = $bModel->bkg_status;
					$dateTime						 = $bModel->bkgTrack->bkg_drv_sos_disable_datetime;
					$desc							 = "S.O.S. ended by $drvname at Latitude: $latitude Longitude: $longitude on Date $dateTime.";
					$eventId						 = BookingLog::SOS_TRIGGER_OFF;
					BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, $eventId, false, $params);
					if ($bModel->bkgTrack->save())
					{
						if ($sosLatitude != 0.0 || $sosLongitude != 0.0)
						{
							foreach ($sosContactList As $value)
							{
								$emergencyUserName	 = $value['name'];
								$phone				 = str_replace('-', '', str_replace(' ', '', $value['phon_no']));
								$phoneNumber		 = substr($phone, -10);
								$emailAddress		 = $value['email'];
								$urlHash			 = Users::model()->createSOSHashUrl($data['bkg_id'], $userId);
								$url				 = Yii::app()->params['fullBaseURL'] . "/e?v=" . $urlHash;
								$msg				 = " PANIC Situation resolved.Track $drvname current location at $url";
								if (strlen($phoneNumber) >= 10)
								{
									$msgCom		 = new smsWrapper();
									$sendSmsFlag = $msgCom->sendSmsToEmergencyContact($data['bkg_id'], $phoneNumber, $msg, $type);
								}
								if ($emailAddress != '')
								{
									$emailModel		 = new emailWrapper();
									$sendEmailFlag	 = $emailModel->sendEmailToEmergencyContact($data['bkg_id'], $userName, $emergencyUserName, $emailAddress, $msg, $type);
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
							$sosSmsTrigger	 = ($sendSmsFlag != Null || $sendEmailFlag != Null || $sendSms != Null || $sendEmail != Null ) ? true : false;
						}
					}
					else
					{
						$sosSmsTrigger = false;
					}
				}
				else
				{
					$sosSmsTrigger = false;
				}
				DBUtil::commitTransaction($transaction);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $sosSmsTrigger;
	}

	public function getDriverSosStatus($driverId)
	{
		$sql	 = "SELECT
					COUNT(`bkg_drv_sos_sms_trigger`) as sos_count
				FROM
					`booking_track`
				INNER JOIN booking ON booking_track.btk_bkg_id = booking.bkg_id AND booking.bkg_status = 5
				INNER JOIN booking_cab ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking_cab.bcb_driver_id
                IN (SELECT d3.drv_id FROM drivers d1
						INNER JOIN drivers d2 ON d2.drv_id=d1.drv_ref_code
						INNER JOIN drivers d3 ON d3.drv_ref_code=d2.drv_id
				WHERE d1.drv_id='$driverId') WHERE `bkg_drv_sos_sms_trigger` = 2";
		$result	 = Yii::app()->db1->createCommand($sql)->queryScalar();
		return $result;
	}

	public function getOverallRatingbyDriverId($driverId)
	{
		$sql		 = "SELECT IFNULL(drs_drv_overall_rating,0) as driver_rating FROM driver_stats WHERE driver_stats.drs_drv_id=$driverId AND drs_drv_overall_rating >0";
		$recordset	 = DBUtil::queryRow($sql);
		return $recordset;
	}

	public function getPercentageDriverAppUsage($date1 = '', $date2 = '')
	{
		if ($date1 != null && $date2 != null)
		{
			$param = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}
		else
		{
			$param = ' bkg.bkg_pickup_date BETWEEN(NOW() - INTERVAL 30 DAY) AND NOW()';
		}

		$sql = "SELECT SUM(booking_count) total_booking_count, SUM(app_used_count) total_app_used_count FROM 
						(
						SELECT
						drv.drv_id,
						drv.drv_name,
						drv.drv_code,
						phn.phn_phone_no,
						ctt.ctt_city, 
						COUNT(DISTINCT bkg.bkg_id) AS booking_count, 
						COUNT(DISTINCT btl.btl_bkg_id) AS app_used_count 
						FROM
						booking bkg 
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bkg.bkg_status IN (6, 7) AND bcb.bcb_active = 1 AND bkg.bkg_active = 1 AND $param
						INNER JOIN drivers d ON d.drv_id = bcb.bcb_driver_id  AND d.drv_active = 1 
						LEFT JOIN booking_track_log btl ON bkg.bkg_id = btl.btl_bkg_id AND btl.btl_event_platform = 5
						INNER JOIN drivers drv ON  d.drv_id = drv.drv_ref_code AND drv.drv_active=1
						INNER JOIN contact_profile cp ON cp.cr_is_driver = drv.drv_id AND cp.cr_status =1 
						LEFT JOIN contact AS ctt ON ctt.ctt_id =cp.cr_contact_id AND ctt.ctt_id = ctt.ctt_ref_code AND ctt.ctt_active =1
						LEFT JOIN contact_phone phn ON phn.phn_contact_id = ctt.ctt_id AND phn.phn_is_primary = 1 
						GROUP BY bcb.bcb_driver_id ) a";

		$resultSet = DBUtil::queryRow($sql, DBUtil::SDB());
		return $resultSet;
	}

	public function getStartStopCountApp($date1 = '', $date2 = '')
	{
		if ($date1 != null && $date2 != null)
		{
			$param = " bkg.bkg_pickup_date BETWEEN '" . $date1 . " 00:00:00' AND '" . $date2 . " 23:59:59'";
		}
		else
		{
			$param = ' bkg.bkg_pickup_date BETWEEN(NOW() - INTERVAL 30 DAY) AND NOW()';
		}
		$sql = "SELECT
			COUNT(DISTINCT bkg_id) AS total_booking,
			COUNT(
			DISTINCT IF(
            btl.btl_event_type_id = 101,
            btl_bkg_id,
            NULL
			)
			) AS app_start,
			COUNT(
			DISTINCT IF(
            btl.btl_event_type_id = 104,
            btl_bkg_id,
            NULL
			)
			) AS app_end
			FROM
			booking bkg
			INNER JOIN booking_pref bpf ON bkg.bkg_id = bpf.bpr_bkg_id AND bpf.bkg_trip_otp_required = 1
			LEFT JOIN booking_track_log btl ON
			btl.btl_bkg_id = bkg.bkg_id AND btl.btl_event_platform = 5 AND btl.btl_event_type_id IN(101, 104)
			WHERE
			bkg.bkg_status IN(5, 6, 7) AND $param";

		$resultSet = DBUtil::queryRow($sql, DBUtil::SDB());
		return $resultSet;
	}

	public function updateStat($json, $driverId)
	{
		$lockInfo		 = json_encode(array_merge(["Device" => $json->device]));
		$lockreason		 = json_encode(array_merge(["BookingID" => $json->bookingId]));
		$existlockinfo	 = [];
		$existReason	 = [];
		$param			 = ['driverID' => $driverId];
		$sql			 = "SELECT drs_lock_status_info,drs_reason,drs_unsync_count FROM driver_stats WHERE drs_drv_id=:driverID";
		$res			 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
//		if ($res['drs_lock_status_info'] != NULL)
//		{
//			$existlockinfo = json_decode($res['drs_lock_status_info'], true);
//		}
		if ($res['drs_reason'] != NULL)
		{
			$existReason = json_decode($res['drs_reason'], true);
		}
		$countUnsync	 = $res['drs_unsync_count'] + 1;
		$existlockinfo[] = json_decode($lockInfo, true);
		$existReason[]	 = json_decode($lockreason, true);
		$params			 = ['lockStatus'	 => $json->unsynced_data,
			'lockInfo'		 => json_encode(array_unique($existlockinfo, SORT_REGULAR)),
			'reason'		 => json_encode(array_unique($existReason, SORT_REGULAR)),
			'countUnsync'	 => $countUnsync,
			'driverID'		 => $driverId];

		$sqlUpdate	 = "UPDATE driver_stats SET drs_lock_status= :lockStatus,drs_lock_status_info= :lockInfo,"
				. "drs_reason=:reason , drs_unsync_count=:countUnsync "
				. "WHERE drs_drv_id =:driverID";
		$result		 = DBUtil::execute($sqlUpdate, $params);
		return $result;
	}

	public function getSyncActivity($driverId)
	{
		$param		 = ['driverID' => $driverId];
		$sql		 = "SELECT drs_lock_status_info,drs_reason,drs_lock_status FROM driver_stats WHERE drs_drv_id=:driverID";
		$res		 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);
		$arra		 = json_decode($res['drs_lock_status_info'], true);
		$returnArr	 = ["lock" => $res['drs_lock_status'], "device" => $arra[count($arra) - 1]['Device']];
		return $returnArr;
	}

	public function getUpdatedData($bkgId)
	{
		$trackRecords = AgentApiTracking::getData($bkgId);
		if ($trackRecords)
		{
			$bookingModel		 = Booking::model()->findByPk($bkgId);
			$drvId				 = $bookingModel->bkgBcb->bcb_driver_id;
			$lastModifiedData	 = DriverStats::model()->getLastLocation($drvId);
			if ($lastModifiedData['drv_last_loc_lat'] != null || $lastModifiedData['drv_last_loc_lat'] != '')
			{

				$to_time	 = strtotime($trackRecords['aat_created_at']);
				$from_time	 = strtotime($lastModifiedData['drv_last_loc_date']);

				if ($to_time < $from_time)
				{
					$typeAction = AgentApiTracking::TYPE_UPDATE_LAST_LOCATION;
					AgentMessages::model()->pushApiCall($bookingModel, $typeAction);
				}
			}
		}
	}

	public function getLastLocation($driverId)
	{
		$params	 = ["drvId" => $driverId];
		$sql	 = "SELECT drv_last_loc_lat,drv_last_loc_long,drv_last_loc_date,drv_last_loc_device_id,drv_last_loc_date FROM driver_stats WHERE drs_drv_id=:drvId";
		return DBUtil::queryRow($sql, DBUtil::MDB(), $params);
	}

	public static function getRatingInfoById($driverId)
	{

//drs_no_of_star > 0 AND
        
		$params = ["drvId" => $driverId];
		$sql = "SELECT drs_drv_id,drs_no_of_star,drv_name,drv_code,IFNULL(drs_total_trip,IFNULL(drs_total_trips,0)) total_trip ,drs_drv_overall_rating
			FROM driver_stats drs
			JOIN drivers drv ON drv.drv_id = drs.drs_drv_id
			WHERE  drs_drv_id=:drvId";
		$res = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $res;
	}


	public static function updateCoins($totalCoin, $driverId)
	{
		$success = false;
		$model	 = DriverStats::model()->getbyDriverId($driverId);
		if ($model)
		{
			$model->drs_drv_coin_cnt = $totalCoin;
			if ($model->save())
			{
				$success = true;
			}
		}
		return $success;
	}

}
