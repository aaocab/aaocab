<?php

/**
 * This is the model class for table "partner_api_tracking".
 *
 * The followings are the available columns in table 'partner_api_tracking':
 * @property integer $pat_id
 * @property integer $pat_agent_id
 * @property integer $pat_temp_id
 * @property integer $pat_type
 * @property integer $pat_from_city
 * @property integer $pat_to_city
 * @property integer $pat_booking_id
 * @property string $pat_pickup_date
 * @property integer $pat_booking_type
 * @property integer $pat_error_type
 * @property string $pat_error_msg
 * @property integer $pat_request_time
 * @property string $pat_ip_address
 * @property string $pat_created_at
 * @property string $pat_status
 * @property string $pat_request_count
 * @property string $pat_response_date
 */
class PartnerApiTracking extends CActiveRecord
{

	const VENDOR_DRIVER_ALLOCATION = 1;
	const VENDOR_COMPLETE			 = 2;
	const VENDOR_CANCELLATION		 = 3;
	const GET_QUOTE				 = 4;
	const CREATE_BOOKING			 = 5;
	const GET_DETAILS				 = 6;
	const GET_CANCELLATION_LIST	 = 7;
	const CANCEL					 = 8; //Cancel from admin panel//
	const UPDATE_BOOKING			 = 9;
	const GET_CITIES				 = 10;
	const CONFIRM_BOOKING			 = 11;
	const TYPE_TRIP_START			 = 12;
	const TYPE_TRIP_END			 = 13;
	const TYPE_FBG_BOOKING		 = 14;
	const TYPE_PENDING_BOOKING   = 20;
	const TYPE_DECLINE_BOOKING   = 16;

	public $padModel;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'partner_api_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pat_type, pat_created_at', 'required'),
			array('pat_agent_id, pat_temp_id, pat_type, pat_from_city, pat_to_city, pat_booking_id, pat_booking_type, pat_error_type', 'numerical', 'integerOnly' => true),
			array('pat_error_msg', 'length', 'max' => 1000),
			array('pat_ip_address', 'length', 'max' => 100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pat_id, pat_agent_id, pat_temp_id, pat_type, pat_from_city, pat_to_city, pat_booking_id, pat_pickup_date, pat_booking_type, pat_error_type, pat_error_msg, pat_request_time, pat_ip_address, pat_created_at', 'safe', 'on' => 'search'),
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
			'pat_id'			 => 'Pat',
			'pat_agent_id'		 => 'Pat Agent',
			'pat_temp_id'		 => 'Pat Temp',
			'pat_type'			 => 'Pat Type',
			'pat_from_city'		 => 'Pat From City',
			'pat_to_city'		 => 'Pat To City',
			'pat_booking_id'	 => 'Pat Booking',
			'pat_pickup_date'	 => 'Pat Pickup Date',
			'pat_booking_type'	 => 'Pat Booking Type',
			'pat_error_type'	 => 'Pat Error Type',
			'pat_error_msg'		 => 'Pat Error Msg',
			'pat_request_time'	 => 'Pat Request Time',
			'pat_ip_address'	 => 'Pat Ip Address',
			'pat_created_at'	 => 'Pat Created At',
			'pat_server_id'		 => 'Pat Server Id',
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

		$criteria->compare('pat_id', $this->pat_id);
		$criteria->compare('pat_agent_id', $this->pat_agent_id);
		$criteria->compare('pat_temp_id', $this->pat_temp_id);
		$criteria->compare('pat_type', $this->pat_type);
		$criteria->compare('pat_from_city', $this->pat_from_city);
		$criteria->compare('pat_to_city', $this->pat_to_city);
		$criteria->compare('pat_booking_id', $this->pat_booking_id);
		$criteria->compare('pat_pickup_date', $this->pat_pickup_date, true);
		$criteria->compare('pat_booking_type', $this->pat_booking_type);
		$criteria->compare('pat_error_type', $this->pat_error_type);
		$criteria->compare('pat_error_msg', $this->pat_error_msg, true);
		$criteria->compare('pat_request_time', $this->pat_request_time);
		$criteria->compare('pat_ip_address', $this->pat_ip_address, true);
		$criteria->compare('pat_created_at', $this->pat_created_at, true);
		$criteria->compare('pat_server_id', $this->pat_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PartnerApiTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function add($typeAction, $dataList, $agentId, $model, $pickupDate)
	{
		/** @var Booking $model */
		try
		{
			//$time						 = Filter::getExecutionTime();
			$patModel					 = new PartnerApiTracking();
			$patModel->pat_type			 = $typeAction;
			$patModel->pat_agent_id		 = $agentId;

			if($typeAction != PartnerApiTracking::TYPE_PENDING_BOOKING)
			{
				$patModel->pat_from_city	 = $model->bookingRoutes[0]->brt_from_city_id;
				$patModel->pat_to_city		 = $model->bookingRoutes[0]->brt_to_city_id;
				$patModel->pat_booking_id	 = $model->bkg_id;
				$patModel->pat_pickup_date	 = $model->bookingRoutes[0]->brt_pickup_datetime;
				$patModel->pat_booking_type	 = $model->bkg_booking_type;
			}
			else
			{
				$patModel->pat_from_city	 = $model->trb_from_city_id;
				$patModel->pat_to_city		 = $model->trb_to_city_id;
				$patModel->pat_pickup_date	 = $model->trb_pickup_date;
				$patModel->pat_booking_type	 = 12; 
			}

			//$patModel->pat_request_time	 = (int) ($time * 1000);
			$patModel->pat_ip_address	 = \Filter::getUserIP();
			$patModel->pat_created_at	 = $patModel->pat_created_at == null ? new CDbExpression('NOW()') : $patModel->pat_created_at;
			$patModel->pat_server_id	 = Config::getServerID();

			if ($patModel->save())
			{
				$request = json_encode($dataList);
				$logPath = Filter::WriteFile($patModel->getLogPath(), $patModel->getFileName(), $request);
			}
			$patModel->save();
			return $patModel;
		}
		catch (Exception $e)
		{
			$desc		 = $e->getMessage();
			$userInfo	 = UserInfo::getInstance();
			$eventId	 = BookingLog::PARTNER_API_ERROR;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, FALSE);
		}
	}

	public function getLogPath()
	{
		$path			 = Yii::app()->basePath;
		$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . Config::getServerID() . DIRECTORY_SEPARATOR . 'partner' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $this->pat_agent_id;
		$path			 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($this->pat_created_at), true);
		return $path;
	}

	public function getFileName()
	{
		$fileName = $this->pat_type . '_' . $this->pat_id . '_' . $this->pat_booking_type . '.apl';
		return $fileName;
	}

	public function updateData($response, $status = 0, $bookingId = null, $errorType = null, $errorMsg = null, $time = null)
	{
		$this->refresh();
		$userInfo = UserInfo::getInstance();
		if ($bookingId != '')
		{
			$bmodel					 = Booking::model()->findByPk($bookingId);
			$routes					 = [];
			$routes					 = $bmodel->bookingRoutes;
			$rCount					 = count($bmodel->bookingRoutes);
			$this->pat_from_city	 = $routes[0]->brt_from_city_id;
			$this->pat_to_city		 = $routes[$rCount - 1]->brt_to_city_id;
			$this->pat_pickup_date	 = $bmodel->bookingRoutes[0]->brt_pickup_datetime;
			$this->pat_booking_type	 = $bmodel->bkg_booking_type;
		}
		$this->pat_response_date = new CDbExpression('NOW()');
		$this->pat_error_type	 = $errorType;
		$this->pat_error_msg	 = $errorMsg;
		$this->pat_booking_id	 = $bookingId;
		$this->pat_status		 = $status;
		$this->pat_request_time  = $time;
		$this->pat_request_count = ($status > 0) ? $this->updateRequestCountByPatId($this->pat_id) : 0;
		Filter::WriteFile($this->getLogPath(), $this->getFileName(), json_encode($response), true);
		if (!$this->save())
		{
			throw new Exception("Failed to update partner API data", ReturnSet::FAILED_TO_UPDATE_DATA);
		}
		if ($status == 2 && $bookingId > 0)
		{
			$desc	 = "Partner API Sync Error ( " . $errorMsg . " )";
			$eventId = BookingLog::PARTNER_API_SYNC_ERROR;
			BookingLog::model()->createLog($bookingId, $desc, $userInfo, $eventId, false);
			BookingTrail::model()->saveApiSyncError($bookingId);
		}
	}

	public function updateRequestCountByPatId($patId)
	{
		$sql = "SELECT pat_request_count FROM `partner_api_tracking` WHERE pat_id= $patId";
		$row = DBUtil::queryRow($sql);
		if (count($row) == 1)
		{
			return ($row['pat_request_count'] + 1);
		}
	}

	public function countPartnerAPIQuoteBooking($result = [])
	{
		$agentId	 = "SELECT distinct pat_agent_id FROM `partner_api_tracking` WHERE  pat_agent_id IS NOT NULL";
		$data		 = DBUtil::command($agentId)->queryAll();
		$partnerIds	 = array();
		foreach ($data as $val)
		{
			array_push($partnerIds, $val['pat_agent_id']);
		}
		$partnerIds = implode(',', $partnerIds);

		$sevenDaysRecords = "SELECT DATE_FORMAT(pat_created_at, '%Y-%m-%d') AS created,pat_type,pat_agent_id,COUNT(pat_id) AS quotes
							        FROM partner_api_tracking WHERE pat_agent_id IN($partnerIds)  AND pat_status = 1 AND pat_type IN (4,5) AND pat_created_at >= (CURDATE() - INTERVAL 7 DAY) GROUP BY created,pat_agent_id,pat_type";

		$tewntyFourHourRecords	 = "SELECT DATE_FORMAT(pat_created_at, '%Y-%m-%d') AS created,pat_type,pat_agent_id,COUNT(IF(pat_type=4,pat_id,null)) as total_quotes,COUNT(IF(pat_type=5,pat_id,null)) as total_booking
							        FROM partner_api_tracking pat WHERE pat_agent_id IN($partnerIds) AND pat.pat_type IN (4,5) AND pat.pat_status =1 AND pat.pat_created_at  >= (CURDATE() - INTERVAL 1 DAY) GROUP BY pat.pat_agent_id, created";
		$sevenDaysDataList		 = DBUtil::command($sevenDaysRecords)->queryAll();
		$twentyFourHoursDataList = DBUtil::command($tewntyFourHourRecords)->queryAll();
		$date					 = date('Y-m-d');
		$dataQuote				 = [];
		$dataBooking			 = [];
		$quotes_24hour			 = [];
		$booking_24hours		 = [];
		$result					 = [];
		foreach ($sevenDaysDataList as $value)
		{
			if ($value['pat_type'] == 4)
			{
				$quotes = $value['quotes'];
			}
			if ($value['pat_type'] == 5)
			{
				$booking = $value['quotes'];
			}
			$dataQuote[$value['pat_agent_id']][]	 = $quotes;
			$dataBooking[$value['pat_agent_id']][]	 = $booking;
		}

		foreach ($dataQuote as $k => $val)
		{
			$maxQuotes[$k]		 = (max($val) > 0) ? max($val) : 0;
			$minQuotes[$k]		 = (min($val) > 0) ? min($val) : 0;
			$sumQuotes[$k]		 = (array_sum($val) > 0) ? array_sum($val) : 0;
			$medianQuotes[$k]	 = Filter::calculateMedian($val) . '';
			$medianQuotes[$k]	 = ($medianQuotes[$k] > 0) ? $medianQuotes[$k] : 0;
			$sql3				 = "INSERT INTO partner_stats (pts_agt_id,pts_created_date,pts_max_7days_quotes,pts_min_7days_quotes,pts_total_7days_quotes,pts_median_7days_quotes) VALUES($k,'$date',$maxQuotes[$k],$minQuotes[$k],$sumQuotes[$k],$medianQuotes[$k])";
			$result				 = DBUtil::command($sql3)->execute();
		}

		foreach ($dataBooking as $r => $bkg)
		{
			$maxBooking[$r]		 = (max($bkg) > 0) ? max($bkg) : 0;
			$minBooking[$r]		 = (min($bkg) > 0) ? min($bkg) : 0;
			$sumBooking[$r]		 = (array_sum($bkg) > 0) ? array_sum($bkg) : 0;
			$medianBooking[$r]	 = Filter::calculateMedian($bkg) . '';
			$medianBook[$r]		 = ($medianBooking[$r] > 0) ? $medianBooking[$r] : 0;
			$sqlPartner			 = "SELECT pts_agt_id FROM partner_stats WHERE pts_agt_id =$r ";
			$patId				 = DBUtil::command($sqlPartner)->queryAll();
			if ($patId)
			{
				$sqlbook = "UPDATE partner_stats SET  pts_max_7days_booking =$maxBooking[$r], pts_min_7days_booking =$minBooking[$r], pts_total_7days_booking=$sumBooking[$r],pts_median_7days_booking=$medianBook[$r]
                            WHERE pts_agt_id =$r AND pts_created_date = '$date'";
				$result	 = DBUtil::command($sqlbook)->execute();
			}
			else
			{
				$sqlbook = "INSERT INTO partner_stats(pts_agt_id,pts_created_date,pts_max_7days_booking,pts_min_7days_booking,pts_total_7days_booking,pts_median_7days_booking) VALUES($r,'$date',$maxBooking[$r],$minBooking[$r],$sumBooking[$r],$medianBook[$r])";
				$result	 = DBUtil::command($sqlbook)->execute();
			}
		}
		foreach ($twentyFourHoursDataList AS $value1)
		{
			$quotes_24hour	 = $value1['total_quotes'];
			$booking_24hours = $value1['total_booking'];
			$partnerId		 = $value1['pat_agent_id'];
			$sqlPartner		 = "SELECT pts_agt_id FROM partner_stats WHERE pts_agt_id =$partnerId";
			$patId			 = DBUtil::command($sqlPartner)->execute();
			if ($patId)
			{
				$updatePartnerStatsQry	 = "UPDATE partner_stats SET  pts_24hours_quotes =$quotes_24hour, pts_24hours_booking =$booking_24hours
                 WHERE pts_agt_id =$partnerId  AND pts_created_date = '$date'";
				$result					 = DBUtil::command($updatePartnerStatsQry)->execute();
			}
			else
			{
				$updatePartnerStatsQry	 = "INSERT INTO partner_stats(pts_agt_id,pts_created_date,pts_24hours_quotes,pts_24hours_booking) VALUES($partnerId,'$date',$quotes_24hour,$booking_24hours)";
				$result					 = DBUtil::command($updatePartnerStatsQry)->execute();
			}
		}
		return $result;
	}

	public function getPartnerTrackingDataByBkgId()
	{
		$params			 = ['bkgId' => $this->pat_booking_id];
		$sql			 = "SELECT * FROM partner_api_tracking WHERE pat_booking_id =:bkgId";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['pat_created_at'],
				'defaultOrder'	 => 'pat_created_at  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getEventTypeById($eventId)
	{
		switch ($eventId)
		{
			case 1:
				$eventType	 = 'Vendor Driver Allocation';
				break;
			case 2:
				$eventType	 = 'Vendor Complete';
				break;
			case 3:
				$eventType	 = 'Vendor Cancellation';
				break;
			case 4:
				$eventType	 = 'Get Quote';
				break;
			case 5:
				$eventType	 = 'Create Booking';
				break;
			case 6:
				$eventType	 = 'Get Details';
				break;
			case 7:
				$eventType	 = 'Cancel List';
				break;
			case 8:
				$eventType	 = 'Cancel Booking';
				break;
			case 11:
				$eventType	 = 'Confirm Booking';
				break;
			case 12:
				$eventType	 = 'Trip Start(boarded)';
				break;
			case 13:
				$eventType	 = 'Trip End(alight)';
				break;
			case 14:
				$eventType	 = 'FBG Booking';
				break;
			case 10:
				$eventType	 = 'Update (transferz pushing data to us)';
				break;
			case 15:
				$eventType	 = 'Left for Pick Up(start)';
				break;
			case 18:
				$eventType	 = 'Arrived(arrived)';
				break;
		}
		return $eventType;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////
	public function getLocalPath()
	{
		$filePath = $this->getLogPath() . DIRECTORY_SEPARATOR . $this->getFileName();
		return $filePath;
	}

	public function getSpacePath()
	{
		$agentId	 = $this->pat_agent_id;
		$event		 = $this->pat_type;
		$date		 = $this->pat_created_at;
		$id			 = $this->pat_id;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d/H/i");
		$path		 = "{$agentId}/{$event}/{$dateString}/{$id}.apl";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($removeLocal = true, $saveJSON = true)
	{
		$spaceFile		 = $this->getSpacePath();
		$localFile		 = $this->getLocalPath();
		$objSpaceFile	 = Storage::uploadText(Storage::getPartnerAPISpace(), $spaceFile, $localFile, $removeLocal);
		if ($saveJSON && $objSpaceFile != null)
		{
			$this->pat_s3_data = $objSpaceFile->toJSON();
			$this->save();
		}
		return $objSpaceFile;
	}

	/** @return SpacesAPI\File */
	public function getSpaceFile()
	{
		return Storage::getFile(Storage::getPartnerAPISpace(), $this->getSpacePath());
	}

	public function removeSpaceFile()
	{
		return Storage::removeFile(Storage::getPartnerAPISpace(), $this->getSpacePath());
	}

	public function getSignedURL()
	{
		$url = null;
		if ($this->pat_s3_data != null && $this->pat_s3_data != '{}')
		{
			$objSpaceFile		 = Stub\common\SpaceFile::populate($this->pat_s3_data);
			$url				 = $objSpaceFile->getURL(strtotime("+1 hour"));
			$this->pat_s3_data	 = $objSpaceFile->toJSON();
			$this->save();
		}
		return $url;
	}

	public static function uploadAllToS3($limit = 1000, $type = null)
	{
		while ($limit > 0)
		{
			$limit1 = min([1000, $limit]);

			$condSql = " AND pat_type != 4 ";
			if ($type != null)
			{
				$condSql = " AND pat_type = {$type} ";
			}
			if ($type === 0)
			{
				$condSql = "";
			}
			$condSql .= " AND pat_server_id = " . Config::getServerID();

			$sql = "SELECT pat_id FROM partner_api_tracking WHERE pat_s3_data IS NULL $condSql ORDER BY pat_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql, DBUtil::MDB());
			if ($res->getRowCount() == 0)
			{
				break;
			}

			foreach ($res as $row)
			{

				/** @var partnerApiTracking $patModel */
				$patModel = PartnerApiTracking::model()->findByPk($row["pat_id"]);
				$patModel->uploadToS3();
				Logger::writeToConsole($patModel->pat_s3_data);
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3()
	{
		$patModel = $this;
		if (!file_exists($patModel->getLocalPath()))
		{
			if ($patModel->pat_s3_data == '')
			{
				$patModel->pat_s3_data = "{}";
				$patModel->save();
			}
			return null;
		}
		$spaceFile = $patModel->uploadToSpace();
		return $spaceFile;
	}

}
