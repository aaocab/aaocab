<?php

/**
 * This is the model class for table "operator_api_tracking".
 *
 * The followings are the available columns in table 'operator_api_tracking':
 * @property integer $oat_id
 * @property integer $oat_operator_id
 * @property string $oat_request
 * @property string $oat_response
 * @property integer $oat_type
 * @property integer $oat_from_city
 * @property integer $oat_to_city
 * @property integer $oat_booking_id
 * @property string $oat_pickup_date
 * @property integer $oat_booking_type
 * @property integer $oat_error_type
 * @property string $oat_error_msg
 * @property integer $oat_request_time
 * @property string $oat_ip_address
 * @property string $oat_created_at
 * @property integer $oat_status
 * @property integer $oat_request_count
 * @property string $oat_response_date
 * @property integer $oat_server_id
 * @property string $oat_s3_data
 * @property string $oat_ref_id
 * @property string $oat_source_lat
 * @property string $oat_source_long
 * @property string $oat_destination_lat
 * @property string $oat_destination_long
 * @property string $oat_additional_params
 */
class OperatorApiTracking extends CActiveRecord
{
	const GET_QUOTE					 = 1;
	const CREATE_BOOKING			 = 2;
	const UPDATE_BOOKING			 = 3;
	const CANCEL_BOOKING			 = 4;
	const ARRIVED_FOR_PICKUP		 = 5;
	const GOING_FOR_PICKUP			 = 6;
	const CONFIRM_BOOKING			 = 7;
	const TYPE_TRIP_START			 = 8;
	const TYPE_TRIP_END			     = 9;
	const CAB_DRIVER_ALLOCATION	     = 10;
	const UNASSIGN_VENDOR            = 11;
	const UPDATE_LAST_LOCATION       = 12;
	const ACCEPTED_BID_CONFIRM_BOOKING  = 13;
	const REASSIGN					 = 14;
	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'operator_api_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('oat_operator_id, oat_type, oat_from_city, oat_to_city, oat_booking_id, oat_booking_type, oat_error_type, oat_request_time, oat_status, oat_request_count, oat_server_id', 'numerical', 'integerOnly' => true),
//			array('oat_request, oat_response', 'length', 'max' => 10000),
//			array('oat_error_msg', 'length', 'max' => 1000),
//			array('oat_ip_address', 'length', 'max' => 100),
//			array('oat_ref_id', 'length', 'max' => 255),
			array('oat_id, oat_operator_id, oat_request, oat_response, oat_type, oat_from_city, oat_to_city, oat_booking_id, oat_source_lat, oat_source_long, oat_destination_lat, oat_destination_long, oat_additional_params, oat_pickup_date, oat_booking_type, oat_error_type, oat_error_msg, oat_request_time, oat_ip_address, oat_created_at, oat_status, oat_request_count, oat_response_date, oat_server_id, oat_s3_data, oat_ref_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('oat_id, oat_operator_id, oat_request, oat_response, oat_type, oat_from_city, oat_to_city, oat_booking_id, oat_source_lat, oat_source_long, oat_destination_lat, oat_destination_long, oat_additional_params, oat_pickup_date, oat_booking_type, oat_error_type, oat_error_msg, oat_request_time, oat_ip_address, oat_created_at, oat_status, oat_request_count, oat_response_date, oat_server_id, oat_s3_data, oat_ref_id', 'safe', 'on' => 'search'),
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
			'oat_id'				 => 'Oat',
			'oat_operator_id'		 => 'Oat Partner',
			'oat_request'			 => 'Oat Request',
			'oat_response'			 => 'Oat Response',
			'oat_type'				 => 'Oat Type',
			'oat_from_city'			 => 'Oat From City',
			'oat_to_city'			 => 'Oat To City',
			'oat_booking_id'		 => 'Oat Booking',
			'oat_pickup_date'		 => 'Oat Pickup Date',
			'oat_booking_type'		 => 'Oat Booking Type',
			'oat_source_lat'		 => 'Oat Source Lat',
			'oat_source_long'		 => 'Oat Source Long',
			'oat_destination_lat'	 => 'Oat Destination Lat',
			'oat_destination_long'	 => 'Oat Destination Long',
			'oat_error_type'		 => 'Oat Error Type',
			'oat_error_msg'			 => 'Oat Error Msg',
			'oat_request_time'		 => 'Oat Request Time',
			'oat_ip_address'		 => 'Oat Ip Address',
			'oat_created_at'		 => 'Oat Created At',
			'oat_status'			 => 'Oat Status',
			'oat_request_count'		 => 'Oat Request Count',
			'oat_response_date'		 => 'Oat Response Date',
			'oat_server_id'			 => 'Oat Server',
			'oat_s3_data'			 => 'Oat S3 Data',
			'oat_ref_id'			 => 'Oat Ref',
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

		$criteria->compare('oat_id', $this->oat_id);
		$criteria->compare('oat_operator_id', $this->oat_operator_id);
		$criteria->compare('oat_request', $this->oat_request, true);
		$criteria->compare('oat_response', $this->oat_response, true);
		$criteria->compare('oat_type', $this->oat_type);
		$criteria->compare('oat_from_city', $this->oat_from_city);
		$criteria->compare('oat_to_city', $this->oat_to_city);
		$criteria->compare('oat_booking_id', $this->oat_booking_id);
		$criteria->compare('oat_pickup_date', $this->oat_pickup_date, true);
		$criteria->compare('oat_booking_type', $this->oat_booking_type);
		$criteria->compare('oat_error_type', $this->oat_error_type);
		$criteria->compare('oat_error_msg', $this->oat_error_msg, true);
		$criteria->compare('oat_request_time', $this->oat_request_time);
		$criteria->compare('oat_ip_address', $this->oat_ip_address, true);
		$criteria->compare('oat_created_at', $this->oat_created_at, true);
		$criteria->compare('oat_status', $this->oat_status);
		$criteria->compare('oat_request_count', $this->oat_request_count);
		$criteria->compare('oat_response_date', $this->oat_response_date, true);
		$criteria->compare('oat_server_id', $this->oat_server_id);
		$criteria->compare('oat_s3_data', $this->oat_s3_data, true);
		$criteria->compare('oat_ref_id', $this->oat_ref_id, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OperatorApiTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This function is used to track operator records
	 * @param integer $typeAction
	 * @param object $dataList
	 * @param integer $operatorId
	 * @param model $model 
	 * @param DateTime $pickupDate 
	 * @return boolean|string
	 */
	public function add($typeAction, $dataList, $operatorId, $bkgId, $errorMsg = null)
	{
		/** @var Booking $model */
		$model = Booking::model()->findByPk($bkgId);
		try
		{
			$oatModel						 = new OperatorApiTracking();
			if($dataList->errorCode != NULL)
			{
				$oatModel->oat_error_type        = $dataList->errorCode;
				$oatModel->oat_error_msg         = $dataList->userMsg;
			}

			$oatModel->oat_type				 = $typeAction;
			$oatModel->oat_operator_id		 = $operatorId;
			$oatModel->oat_from_city		 = $model->bkgFromCity->cty_id;
			$oatModel->oat_to_city			 = $model->bkgToCity->cty_id;

			$oatModel->oat_source_lat		 = $model->bkgFromCity->cty_lat;
			$oatModel->oat_source_long		 = $model->bkgFromCity->cty_long;
			$oatModel->oat_destination_lat	 = $model->bkgToCity->cty_lat;
			$oatModel->oat_destination_long	 = $model->bkgToCity->cty_long;
			$oatModel->oat_pickup_date		 = $model->bkg_pickup_date;
			$oatModel->oat_booking_id		 = $bkgId;
			$oatModel->oat_booking_type		 = $model->bkg_booking_type;
			$oatModel->oat_additional_params = json_encode($dataList);
			
			$oatModel->oat_ip_address		 = Filter::getUserIP();
			$oatModel->oat_created_at		 = $oatModel->oat_created_at == null ? new CDbExpression('NOW()') : $oatModel->oat_created_at;
			$oatModel->oat_server_id		 = Config::getServerID();

			if ($oatModel->save())
			{
				$logPath = Filter::WriteFile($oatModel->getLogPath(), $oatModel->getFileName(), json_encode($dataList));
			}
		}
		catch (Exception $e)
		{
			$desc		 = $e->getMessage();
			$userInfo	 = UserInfo::getInstance();
			$eventId	 = BookingLog::OPERATOR_API_ERROR;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, FALSE);
		}
		return $oatModel;
	}

	public function getLogPath()
	{
		$path			 = Yii::app()->basePath;
		$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . Config::getServerID() . DIRECTORY_SEPARATOR . 'operator' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $this->oat_operator_id;
		$path			 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($this->oat_created_at), true);
		return $path;
	}

	public function getFileName()
	{
		$fileName = $this->oat_type . '_' . $this->oat_id . '_' . $this->oat_booking_type . '.apl';
		return $fileName;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////
	public function getLocalPath()
	{
		$filePath = $this->getLogPath() . DIRECTORY_SEPARATOR . $this->getFileName();
		return $filePath;
	}

	public function getSpacePath()
	{
		$agentId	 = $this->oat_operator_id;
		$event		 = $this->oat_type;
		$date		 = $this->oat_created_at;
		$id			 = $this->oat_id;
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
		$objSpaceFile	 = Storage::uploadText(Storage::getOperatorAPISpace(), $spaceFile, $localFile, $removeLocal);
		if ($saveJSON && $objSpaceFile != null)
		{
			$this->oat_s3_data = $objSpaceFile->toJSON();
			$this->save();
		}
		return $objSpaceFile;
	}

	/** @return SpacesAPI\File */
	public function getSpaceFile()
	{
		return Storage::getFile(Storage::getOperatorAPISpace(), $this->getSpacePath());
	}

	public function removeSpaceFile()
	{
		return Storage::removeFile(Storage::getOperatorAPISpace(), $this->getSpacePath());
	}

	public function getSignedURL()
	{
		$url = null;
		if ($this->oat_s3_data != null && $this->oat_s3_data != '{}')
		{
			$objSpaceFile		 = Stub\common\SpaceFile::populate($this->oat_s3_data);
			$url				 = $objSpaceFile->getURL(strtotime("+1 hour"));
			$this->oat_s3_data	 = $objSpaceFile->toJSON();
			$this->save();
		}
		return $url;
	}

	public static function uploadAllToS3($limit = 1000, $type = null)
	{
		while ($limit > 0)
		{
			$limit1 = min([1, $limit]);

			$condSql = " AND oat_type != 4 ";
			if ($type != null)
			{
				$condSql = " AND oat_type = {$type} ";
			}
			if ($type === 0)
			{
				$condSql = "";
			}
			$condSql .= " AND oat_server_id = " . Config::getServerID();

			$sql = "SELECT oat_id FROM operator_api_tracking WHERE oat_s3_data IS NULL $condSql ORDER BY oat_id DESC LIMIT 0, $limit1";
			$res = DBUtil::query($sql, DBUtil::MDB());
			if ($res->getRowCount() == 0)
			{
				break;
			}

			foreach ($res as $row)
			{
				/** @var operatorApiTracking $oatModel */
				$oatModel = OperatorApiTracking::model()->findByPk($row["oat_id"]);
				$oatModel->uploadToS3();
				Logger::writeToConsole($oatModel->oat_s3_data);
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3()
	{
		$oatModel = $this;
		if (!file_exists($oatModel->getLocalPath()))
		{
			if ($oatModel->oat_s3_data == '')
			{
				$oatModel->oat_s3_data = "{}";
				$oatModel->save();
			}
			return null;
		}
		$spaceFile = $oatModel->uploadToSpace();
		return $spaceFile;
	}

	public function updateData($response, $status = 0, $bookingId = null, $operatorId, $errorMsg = null, $errorCode = null)
	{
		$this->refresh();
		$time							 = Filter::getExecutionTime();
		if ($bookingId != '')
		{
			$bmodel					 = Booking::model()->findByPk($bookingId);
			$routes					 = [];
			$routes					 = $bmodel->bookingRoutes;
			$rCount					 = count($bmodel->bookingRoutes);
			$this->oat_from_city	 = $routes[0]->brt_from_city_id;
			$this->oat_to_city		 = $routes[$rCount - 1]->brt_to_city_id;
			$this->oat_pickup_date	 = $bmodel->bookingRoutes[0]->brt_pickup_datetime;
			$this->oat_booking_type	 = $bmodel->bkg_booking_type;
			$this->oat_booking_id	 = $bookingId;
		}
		$this->oat_request_time		 = (int) ($time);
		$this->oat_operator_id       = $operatorId;
		$this->oat_additional_params = json_encode($response);
		$this->oat_response_date	 = new CDbExpression('NOW()');
		$this->oat_status			 = $status;
		$this->oat_error_msg         = $errorMsg;     
		$this->oat_ref_id            = NULL;
		Filter::WriteFile($this->getLogPath(), $this->getFileName(), json_encode($response), true);
		$this->save();
	}

	public function updateRequestCountByOatId($oatId)
	{
		$sql = "SELECT oat_request_count FROM `operator_api_tracking` WHERE oat_id= $oatId";
		$row = DBUtil::queryRow($sql);
		if (count($row) == 1)
		{
			return ($row['oat_request_count'] + 1);
		}
	}

	/**
	 * 
	 * @param type $fixedFareId
	 * @return type oat_ref_id
	 */
	public static function getOrderRefId($bkgId,$type)
	{
		$param		= ['bkgId' => $bkgId,'oat_type'=>$type];
        $sql		= "SELECT oat_ref_id from operator_api_tracking WHERE oat_booking_id=:bkgId AND oat_type=:oat_type";
		$result		= DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
        return $result;
	}

	/**
	 * 
	 * @param type $bkgFixedFareId
	 * @return boolean
	 */
	public function checkOperatorBooking($bkgFixedFareId)
	{ 
		$success = false;
		$oatFixedFareId = self::getOrderRefId($bkgFixedFareId);
		if($oatFixedFareId['oat_ref_id'] == $bkgFixedFareId)
		{
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return boolean
	 */
	public static function checkOperatorByBkgId($bkgId)
	{
		$success = false;
		$param = ['bkgId' => $bkgId];
        $sql   = "SELECT oat_booking_id from operator_api_tracking WHERE bkgId=:bkgId";
        $row   = DBUtil::queryRow($sql, DBUtil::SDB(), $param);
        if($row['oat_booking_id'])
		{
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $type
	 * @return boolean
	 */
	public static function countTrackingDataByBkgIdAndType($bkgId,$type)
	{		
		$param		= ['bkgId' => $bkgId,'oat_type'=>$type];
        $sql		= "SELECT count(oat_id) from operator_api_tracking WHERE oat_booking_id=:bkgId AND oat_type=:oat_type";
        return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	/**
	 * 
	 * @param type $eventType
	 * @return int
	 */
	public function getActionType($eventType)
	{
		switch ($eventType)
		{
			case 'assign':
				$eventId = OperatorApiTracking::CAB_DRIVER_ALLOCATION;
				break;
			case 'reassign':
				$eventId = OperatorApiTracking::REASSIGN;
				break;
			case 'unassign':
				$eventId = OperatorApiTracking::UNASSIGN_VENDOR;
				break;
			case 'leftForPickup':
				$eventId = OperatorApiTracking::GOING_FOR_PICKUP;
				break;
			case 'arrived':
				$eventId = OperatorApiTracking::ARRIVED_FOR_PICKUP;
				break;
			case 'tripStart':
				$eventId = OperatorApiTracking::TYPE_TRIP_START;
				break;
			case 'tripEnd':
				$eventId = OperatorApiTracking::TYPE_TRIP_END;
				break;
			case 'driverPosition':
				$eventId = OperatorApiTracking::UPDATE_LAST_LOCATION;
				break;	
		}
		return $eventId;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return boolean
	 */
	public static function isBookingUpdateEvent($bkgId)
	{
		$param		= ['bkgId' => $bkgId];
		$success = false;
		$sql = "SELECT bkg.bkg_id FROM booking bkg INNER JOIN booking_cab bcb ON bcb.bcb_bkg_id1 = bkg.bkg_id RIGHT JOIN operator_api_tracking oat ON oat.oat_booking_id = bkg.bkg_id WHERE bkg.bkg_status IN (2,3) AND bcb.bcb_vendor_id = 73480 AND bkg.bkg_id = 1904185 AND bkg.bkg_booking_type IN (4,12) GROUP BY bkg.bkg_id";
		$result =  DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		if($result)
		{
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return boolean
	 */
	public static function isBookingCancelEvent($bkgId)
	{
		$param		= ['bkgId' => $bkgId];
		$success = false;
		$sql = "SELECT bkg.bkg_id FROM booking bkg INNER JOIN booking_cab bcb ON bcb.bcb_bkg_id1 = bkg.bkg_id RIGHT JOIN operator_api_tracking oat ON oat.oat_booking_id = bkg.bkg_id WHERE bkg.bkg_status IN (2,3) AND bcb.bcb_vendor_id = 73480 AND bkg.bkg_id = 1904185 GROUP BY bkg.bkg_id";
		$result =  DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
		if($result)
		{
			$success = true;
		}
		return $success;
	}

	public function getOperatorTrackingDataByBkgId()
	{
		$params			 = ['bkgId' => $this->oat_booking_id];
		$sql			 = "SELECT * FROM operator_api_tracking WHERE oat_booking_id =:bkgId";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['pat_created_at'],
				'defaultOrder'	 => 'oat_created_at  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getEventTypeById($eventId)
	{
		switch ($eventId)
		{
			case 1:
				$eventType	 = 'Get Quote';
				break;
			case 2:
				$eventType	 = 'Create Booking';
				break;
			case 3:
				$eventType	 = 'Update Booking';
				break;
			case 4:
				$eventType	 = 'Cancel Booking';
				break;
			case 5:
				$eventType	 = 'Arrived for Pickup';
				break;
			case 6:
				$eventType	 = 'Going for Pickup';
				break;
			case 7:
				$eventType	 = 'Confirm Booking';
				break;
			case 8:
				$eventType	 = 'Type Trip Start';
				break;
			case 9:
				$eventType   = 'Type Trip End';
				break;
			case 10:
				$eventType	 = 'Cab Driver Allocation';
				break;
			case 11:
				$eventType	 = 'Unassign Vendor';
				break;
			case 12:
				$eventType	 = 'Update Last Location';
				break;
			case 13:
				$eventType	 = 'Accepted Bid Confirm Booking';
				break;
			case 14:
				$eventType	 = 'Reassign';
				break;
		}
		return $eventType;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $type
	 * @return int | false
	 */
	public static function checkDuplicateId($bkgId, $type, $bkgCreateDate)
	{
		$param = ['bkgId' => $bkgId, 'oat_type' => $type, 'created_date' => $bkgCreateDate];

		$sql = "SELECT count(*) count 
			FROM operator_api_tracking 
			WHERE oat_booking_id=:bkgId AND oat_type=:oat_type 
			AND oat_created_at >= :created_date";

		return DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
	}

	/**
	 * 
	 * @param type $bkgId
	 * @return type
	 */
	public static function checkDuplicateIdWithBidAcceptedBooking($bkgId)
	{
		$param		= ['bkgId' => $bkgId];
		$sql = "SELECT COUNT(*) cnt, oat_id  FROM operator_api_tracking WHERE oat_booking_id=:bkgId AND oat_type = 7 AND oat_status = 2 AND oat_request_count <> 0";
		return DBUtil::queryRow($sql, DBUtil::SDB(), $param);
	}
	
}
