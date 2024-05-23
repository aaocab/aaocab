<?php

/**
 * This is the model class for table "agent_api_tracking".
 *
 * The followings are the available columns in table 'agent_api_tracking':
 * @property integer $aat_id
 * @property integer $aat_agent_id
 * @property integer $aat_temp_id
 * @property string $aat_request
 * @property string $aat_response
 * @property integer $aat_type
 * @property integer $aat_from_city
 * @property integer $aat_to_city
 * @property integer $aat_booking_id
 * @property string $aat_ref_id
 * @property string $aat_pickup_date
 * @property string $aat_from_mmt_code
 * @property string $aat_to_mmt_code
 * @property integer $aat_booking_type
 * @property integer $aat_error_type
 * @property string $aat_error_msg
 * @property string $aat_ip_address
 * @property integer $aat_request_time
 * @property string $aat_created_at
 * @property string $aat_status
 * @property string $aat_request_count
 * @property string $aat_response_date
 * @property string $aat_s3_data
 * @property string $aat_ref_id
 */
class AgentApiTracking extends CActiveRecord
{

	const TYPE_CITY_LIST						 = 1;
	const TYPE_GET_QUOTE						 = 2;
	const TYPE_CREATE_BOOKING					 = 3;
	const TYPE_GET_DETAILS					 = 4;
	const TYPE_CANCELLATION_LIST				 = 5;
	const TYPE_CANCEL_BOOKING					 = 6;
	const TYPE_GET_TNC						 = 7;
	const TYPE_HOLD_BOOKING					 = 8;
	const TYPE_CAB_DRIVER_UPDATE				 = 9;
	const TYPE_UPDATE_BOOKING					 = 10;
	const TYPE_OTP_UPDATE						 = 11;
	const TYPE_TRIP_START						 = 12;
	const TYPE_TRIP_END						 = 13;
	const TYPE_TRIP_CANCELLED					 = 14;
	const TYPE_LEFT_FOR_PICKUP				 = 15;
	const TYPE_CAB_DRIVER_REASSIGN			 = 16;
	const TYPE_NO_SHOW						 = 17;
	const TYPE_ARRIVED						 = 18;
	const TYPE_UPDATE_PASSENGER_DETAILS		 = 19;
	const TYPE_GET_PASSENGER_DETAILS			 = 20;
	const TYPE_UPDATE_LAST_LOCATION			 = 21;
	const TYPE_GET_REVIEW						 = 22;
	const TYPE_REVERSE_BOOKING_CREATE			 = 23;
	const TYPE_UNAVAILABLE_BOOKING			 = 24;
	const TYPE_REVERSE_BOOKING_ACCEPT			 = 25;
	const TYPE_TELEGRAM_AUTHENTICATION		 = 26;
	const TYPE_PAYMENT_DETAILS				 = 27;
	const TYPE_ADD_PAYMENT					 = 28;
	const TYPE_TELEGRAM_VENDOR_AUTHENTICATION	 = 29;
	const TYPE_TRIP_DETAILS        = 30;
	const TYPE_TRIP_COMPLETE				= 31; 

	public $aadModel, $totalHoldAssuredInnova, $aat_hours, $aat_pickup_date1, $aat_pickup_date2, $sourcezone, $destinationzone,
			$from_zone, $to_zone, $from_state, $to_state, $region, $from_date, $to_date, $datafor;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agent_api_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aat_type, aat_created_at', 'required'),
			array('aat_agent_id, aat_temp_id, aat_type, aat_from_city, aat_to_city, aat_booking_id, aat_booking_type, aat_error_type, aat_request_time', 'numerical', 'integerOnly' => true),
			array('aat_request, aat_response', 'length', 'max' => 10000),
			array('aat_from_mmt_code, aat_to_mmt_code', 'length', 'max' => 16),
			array('aat_error_msg', 'length', 'max' => 1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aat_id, aat_agent_id, aat_temp_id, aat_request, aat_response, aat_type, aat_from_city, aat_to_city, aat_booking_id,
				aat_pickup_date, aat_from_mmt_code, aat_to_mmt_code, aat_booking_type, aat_error_type, aat_error_msg, aat_request_time,
				aat_ip_address, aat_created_at, aat_pickup_date1, aat_pickup_date2, from_zone, to_zone, from_state, to_state, region, aat_server_id, aat_s3_data', 'safe', 'on' => 'search'),
			array('aat_id, aat_agent_id, aat_temp_id, aat_request, aat_response, aat_type, aat_from_city, aat_to_city, aat_booking_id,
				aat_pickup_date, aat_from_mmt_code, aat_to_mmt_code, aat_booking_type, aat_error_type, aat_error_msg, aat_request_time,
				aat_ip_address, aat_created_at, aat_pickup_date1, aat_pickup_date2, from_zone, to_zone, from_state, to_state, region, aat_server_id, aat_s3_data', 'safe'),
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
			'aat_id'		 => 'Aat ID',
			'aat_agent_id'	 => 'Aat Agent',
			'aat_temp_id'	 => 'Aat Temp',
			'aat_request'	 => 'Aat Request',
			'aat_response'	 => 'Aat Response',
			'aat_type'		 => '1 => \'cityList\', 2 => \'getQuote\', 3 => \'createBooking\', 4 => \'getDetails\', 5 => \'cancellationList\', 6 => \'cancelBooking\', 7 => \'getTnc\'',
			'aat_created_at' => 'Aat Created At',
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

		$criteria->compare('aat_id', $this->aat_id);
		$criteria->compare('aat_agent_id', $this->aat_agent_id);
		$criteria->compare('aat_temp_id', $this->aat_temp_id);
		$criteria->compare('aat_request', $this->aat_request, true);
		$criteria->compare('aat_response', $this->aat_response, true);
		$criteria->compare('aat_type', $this->aat_type);
		$criteria->compare('aat_created_at', $this->aat_created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentApiTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getTopMmtRoutes($active = 1, $limit = 100)
	{
		$condition	 = "(hold_count>=1 OR create_count>0)";
		$c1Join		 = "";
		$c2Join		 = "";

		if ($active == 1)
		{

		}
		else
		{
			$c1Join		 = " AND c1.cty_is_airport=0";
			$c2Join		 = " AND c2.cty_is_airport=0";
			$condition	 = "(hold_count=0 AND create_count=0)";
		}
		if ($limit != "")
		{
			$limit = "LIMIT 0, $limit";
		}
		$qry		 = "SELECT CONCAT(c1.cty_name,' - ',c2.cty_name) as rut_name, a.total_route_count, a.search_count, a.hold_count, a.create_count
                from route r1
                    INNER JOIN cities c1 ON c1.cty_id=r1.rut_from_city_id $c1Join
					INNER JOIN cities c2 ON c2.cty_id=r1.rut_to_city_id $c2Join
                join (
                    SELECT a1.aat_from_city, a1.aat_to_city, COUNT(DISTINCT a1.aat_id) as total_route_count, SUM(IF(a1.aat_type = 2, 1, 0)) as search_count, SUM(IF(a1.aat_type = 8, 1, 0)) as hold_count, SUM(IF(a1.aat_type = 3, 1, 0)) as create_count
                    FROM agent_api_tracking a1 WHERE a1.aat_type IN (2,3,8) AND aat_created_at > DATE_SUB(NOW(),INTERVAL 24 HOUR) AND
                    a1.aat_from_city IS NOT NULL AND a1.aat_to_city IS NOT NULL AND (aat_pickup_date<DATE_ADD(aat_created_at, INTERVAL 10 DAY) OR aat_type<>2) AND a1.aat_error_type IS NULL AND aat_booking_type=1 AND TIMESTAMPDIFF(HOUR, aat_created_at, aat_pickup_date)>3
                    GROUP BY a1.aat_from_city,a1.aat_to_city HAVING $condition ORDER BY hold_count DESC, create_count DESC, search_count DESC LIMIT 0,600
                    ) a ON a.aat_from_city = r1.rut_from_city_id AND a.aat_to_city = r1.rut_to_city_id WHERE r1.rut_active = 1 ORDER BY hold_count DESC, search_count DESC $limit";
		$recordset	 = DBUtil::queryAll($qry, DBUtil::SDB3());
		return $recordset;
	}

	public static function getTopCityNotFound()
	{
//        $sql = "SELECT SUM(ctynotfoundcount) totcount, IF( a.aat_from_city IS NULL, c1.mmt_cty_name, c2.mmt_cty_name ) citynotfound FROM ( SELECT COUNT(*) ctynotfoundcount, aat_from_city, aat_to_city, aat_from_mmt_code, aat_to_mmt_code FROM `agent_api_tracking` WHERE aat_error_type = 1 AND aat_booking_type = 1 AND aat_type IN(2, 8) AND aat_created_at > DATE_SUB(NOW(),
//                  INTERVAL 24 HOUR)  AND ( aat_from_city IS NULL OR aat_to_city IS NULL )  GROUP BY aat_from_mmt_code, aat_to_mmt_code ORDER BY COUNT(*) DESC ) a  LEFT JOIN mmt_city c1 ON c1.mmt_cty_code = a.aat_from_mmt_code LEFT JOIN mmt_city c2 ON c2.mmt_cty_code = a.aat_to_mmt_code GROUP BY citynotfound having citynotfound IS NOT NULL AND totcount>1 ORDER BY totcount DESC,citynotfound ASC";
		$sql		 = "SELECT COUNT(*) totalcount,mmt_city.mmt_cty_name citynotfound FROM ((SELECT aat_id,aat_from_city, aat_to_city, aat_from_mmt_code a
         FROM   agent_api_tracking
         WHERE  aat_error_type = 1 AND aat_created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)  AND  aat_booking_type = 1 AND aat_type IN (2, 8) AND aat_from_city IS NULL)
         UNION ALL
         (SELECT aat_id, aat_from_city, aat_to_city, aat_to_mmt_code a
         FROM agent_api_tracking
         WHERE aat_error_type = 1 AND aat_created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)  AND  aat_booking_type = 1 AND aat_type IN (2, 8) AND aat_to_city IS NULL)) b LEFT JOIN mmt_city ON a=mmt_city.mmt_cty_code GROUP BY  a having citynotfound IS NOT NULL  ORDER BY totalcount DESC,citynotfound ASC";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB3());
		return $recordset;
	}

	public static function getTopRouteNotFound()
	{
		$sql		 = "SELECT sum(ctynotfoundcount) tot,rutname FROM (SELECT COUNT(*) ctynotfoundcount, CONCAT(c1.cty_name,'-',c2.cty_name) rutname, aat_from_mmt_code,aat_to_mmt_code,aat_from_city,aat_to_city FROM `agent_api_tracking` LEFT JOIN cities c1 ON c1.cty_id=aat_from_city LEFT JOIN cities c2 ON c2.cty_id=aat_to_city WHERE aat_error_type = 2 AND aat_booking_type = 1 AND aat_created_at > DATE_SUB(NOW(),
                  INTERVAL 24 HOUR) AND aat_type IN(2, 8) AND( aat_from_city IS NOT NULL AND aat_to_city IS NOT NULL ) GROUP BY aat_from_mmt_code,aat_to_mmt_code) a GROUP BY a.rutname HAVING tot>4 order by tot DESC,rutname DESC";
		$recordset	 = DBUtil::queryAll($sql, DBUtil::SDB3());
		return $recordset;
	}

	public static function updateRequestCountByaatId($aatId)
	{
		$model		 = AgentApiTracking::model()->findByPk($aatId);
		//$sql = "SELECT `aat_request_count` FROM `agent_api_tracking` WHERE aat_id= $aatId";
		//$row = DBUtil::queryScalar($sql, DBUtil::SDB());
//        if ($model->aat_request_count > 0)
//        {
		$eventCount	 = $model->aat_request_count;
		$totalCount	 = $eventCount + 1;
		return $totalCount;
		//}
	}

	public function add($aatType, $request, $model, $ipAddress, $toMmtCode = null, $fromMmtCode = null)
	{

		$aatModel					 = new AgentApiTracking();
		$aatModel->aat_type			 = $aatType;
		$aatModel->aat_agent_id		 = 450;
		$aatModel->aat_from_city	 = (int) $model->bkg_from_city_id;
		$aatModel->aat_to_city		 = (int) $model->bkg_to_city_id;
		$aatModel->aat_pickup_date	 = $model->bkg_pickup_date;
		$aatModel->aat_booking_type	 = $model->bkg_booking_type;
		if (array_key_exists("mmtBookingId", $request))
		{
			if ($request['mmtBookingId'] != '')
			{
				$aatModel->aat_ref_id = $request['mmtBookingId'];
			}
			else if ($request['bookingId'] != '')
			{
				$aatModel->aat_ref_id = $request['bookingId'];
			}
			else
			{
				$aatModel->aat_ref_id = NULL;
			}
		}
		else
		{
			if ($request['bookingId'] != '')
			{
				$aatModel->aat_ref_id = $request['bookingId'];
			}
			else
			{
				$aatModel->aat_ref_id = NULL;
			}
		}

		if ($aatModel->aat_ref_id == '' && $model->bkg_agent_ref_code != '')
		{
			$aatModel->aat_ref_id = $model->bkg_agent_ref_code;
		}
		if ($aatModel->aat_booking_id == '')
		{
			$aatModel->aat_booking_id = $model->bkg_id;
		}

		$aatModel->aat_to_mmt_code	 = $toMmtCode;
		$aatModel->aat_from_mmt_code = $fromMmtCode;
		$aatModel->aat_ip_address	 = $ipAddress;
		$aatModel->aat_created_at	 = new CDbExpression("NOW()");
		if ($aatModel->save())
		{
			$request = json_encode($request);
			$logPath = Filter::WriteFile($aatModel->getLogPath(), $aatModel->getFileName(), $request);
		}
		$aatModel->save();
		return $aatModel;
	}

	public function add1($aatType, $request, $model, $ipAddress, $toMmtCode = null, $fromMmtCode = null)
	{
		$aatModel = new AgentApiTracking();
		//Last Location event log
		if ($model->bkg_id != null && $aatType == AgentApiTracking::TYPE_UPDATE_LAST_LOCATION)
		{
			$aatId = AgentApiTracking::getLastLocationEventByBooking($model->bkg_id, $aatType);
			if ($aatId != '')
			{
				$aatModel = AgentApiTracking::model()->findByPk($aatId);
			}
			else
			{
				$aatModel					 = new AgentApiTracking();
				$aatModel->aat_created_at	 = new CDbExpression("NOW()");
			}
		}

//        if($aatModel == null)
//        {
//            $aatModel = new AgentApiTracking();
//        }

		if ($aatModel->isNewRecord)
		{
			$aatModel->aat_type			 = $aatType;
			$aatModel->aat_agent_id		 = 18190;
			$aatModel->aat_from_city	 = (int) $model->bkg_from_city_id;
			$aatModel->aat_to_city		 = (int) $model->bkg_to_city_id;
			$aatModel->aat_pickup_date	 = $model->bkg_pickup_date;
			$aatModel->aat_booking_type	 = $model->bkg_booking_type;
			$aatModel->aat_created_at	 = new CDbExpression("NOW()");
			$aatModel->aat_server_id	 = Config::getServerID();

			if (in_array($aatType, [2, 3, 4, 6, 8, 19, 22, 21, 23, 24, 25, 27, 28, 14]))
			{
				if (array_key_exists("order_reference_number", $request))
				{
					if ($request->order_reference_number != '')
					{
						$aatModel->aat_ref_id = $request->order_reference_number;
					}
				}
				else
				{
					$aatModel->aat_ref_id = $model->search;
				}
			}
			else
			{
				//////////////////
				if (array_key_exists("mmtBookingId", $request))
				{
					if ($request['mmtBookingId'] != '')
					{
						$aatModel->aat_ref_id = $request['mmtBookingId'];
					}
					else if ($request['booking_id'] != '')
					{
						$aatModel->aat_ref_id = $request['booking_id'];
					}
					else
					{
						$aatModel->aat_ref_id = NULL;
					}
				}
				else
				{
					if ($request['booking_id'] != '')
					{
						$aatModel->aat_ref_id = $request['booking_id'];
					}
					else
					{
						$aatModel->aat_ref_id = NULL;
					}
				}
				//////////////////
			}
			if ($aatModel->aat_ref_id == '' && $model->bkg_agent_ref_code != '')
			{
				$aatModel->aat_ref_id = $model->bkg_agent_ref_code;
			}
			if ($aatModel->aat_booking_id == '')
			{
				$aatModel->aat_booking_id = $model->bkg_id;
			}

			$aatModel->aat_to_mmt_code	 = $toMmtCode;
			$aatModel->aat_from_mmt_code = $fromMmtCode;
			$aatModel->aat_ip_address	 = $ipAddress;
		}
		if ($aatModel->save())
		{
			$request = json_encode($request);
			$logPath = Filter::WriteFile($aatModel->getLogPath(), $aatModel->getFileName(), $request);
		}
		return $aatModel;
	}

	public function getLogPath()
	{
		$path			 = Yii::app()->basePath;
		$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . Config::getServerID() . DIRECTORY_SEPARATOR . 'partner' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $this->aat_agent_id;
		$path			 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($this->aat_created_at), true);
		return $path;
	}

	public function getFileName()
	{
		$fileName = $this->aat_type . '_' . $this->aat_id . '_' . $this->aat_booking_type . '.apl';
		return $fileName;
	}

	public function updateResponse($response, $aatBookingId = null, $status = 0, $errorType = 0, $errorMessage = '', $time)
	{
		$this->refresh();
		$userInfo				 = UserInfo::getInstance();
		$this->aat_response_date = new CDbExpression('NOW()');
		$this->aat_status		 = $status;
		$this->aat_request_count = ($status > 0) ? AgentApiTracking::updateRequestCountByaatId($this->aat_id) : 0;
		$this->aat_booking_id	 = (int) $aatBookingId;
		$this->aat_error_type	 = $errorType;
		$this->aat_error_msg	 = $errorMessage;
		$this->aat_s3_data		 = null;
		$this->aat_request_time	 = (int) $time;
		$this->save();
		if ($status == 2)
		{
			$desc	 = "Partner API Sync Error ( " . $errorMessage . " )";
			$eventId = BookingLog::PARTNER_API_SYNC_ERROR;
			BookingLog::model()->createLog($aatBookingId, $desc, $userInfo, $eventId, false);
			BookingTrail::model()->saveApiSyncError($aatBookingId);
		}

		Filter::WriteFile($this->getLogPath(), $this->getFileName(), json_encode($response), true);
	}

	public static function getBookingType()
	{
		$type = [
			'1'	 => 'One way',
			'2'	 => 'Round',
			'4'	 => 'Airport'
		];
		foreach ($type as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		return CJSON::encode($arrJSON);
	}
	
	public static function getPriceAnalysisListFilterOption()
	{
		$type = [
			'cities' => 'Cities',
			'zones'	 => 'Zones',
			'zone'	 => 'Zone'
		];
		foreach ($type as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		return CJSON::encode($arrJSON);
	}

	/* ----Service Tier Phase 2 Checked------- */

	public function getPriceanalysisList()
	{
		if ($this->aat_booking_type != '')
		{
			$bkgType .= 'AND aat.aat_booking_type="' . $this->aat_booking_type . '"';
		}
		if ($this->sourcezone != '')
		{
			$sZone .= 'AND fzc.zct_zon_id="' . $this->sourcezone . '"';
		}
		if ($this->destinationzone != '')
		{
			$dZone .= 'AND tzc.zct_zon_id="' . $this->destinationzone . '"';
		}
		/*if ($this->region != '')
		{
			if (in_array(4, $this->region))
			{
				$this->region[] = 7;
			}
			$regions = implode(',', $this->region);

			$fRegion .= ' AND tst.stt_zone IN (' . $regions . ') ';
		}*/
		$sql			 = "SELECT aat.aat_booking_type,
							CONCAT(fromCity.cty_name, ' - ', toCity.cty_name) AS rutName,
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, null)) AS totalRequest,
							ROUND(SUM(IF(aat.aat_type = 2, 1, 0))/50,0) as range1,
							COUNT(DISTINCT IF(aat.aat_type = 8, aat_id, null)) AS totalHold,
							ROUND(SUM(IF(aat.aat_type = 8, 1, 0))/
							SUM(IF(aat.aat_type = 2, 1, 0)),2) as ratio,
							SUM(IF(aat.aat_type = 3, 1, 0)) AS totalConfirmed,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::COMPACT_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldCompact,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::COMPACT_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedCompact,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SUV_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldSUV,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SUV_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedSUV,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SEDAN_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SEDAN_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_DZIRE_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldAssuredSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_DZIRE_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedAssuredSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_INNOVA_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldAssuredInnova,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_INNOVA_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedAssuredInnova,
							(CASE
								WHEN (aat.aat_type = '2') THEN 'Get Quote'
								WHEN (aat.aat_type = '3') THEN 'Create Booking'
								WHEN (aat.aat_type = '8') THEN 'Hold Booking'
							 END)
							   AS requestType
					   FROM agent_api_tracking aat
						INNER JOIN cities fromCity ON fromCity.cty_id = aat.aat_from_city
						INNER JOIN cities toCity ON toCity.cty_id = aat.aat_to_city
						INNER JOIN zone_cities fzc ON fzc.zct_cty_id=aat.aat_from_city AND fzc.zct_active=1 $sZone
						INNER JOIN zone_cities tzc ON tzc.zct_cty_id=aat.aat_to_city AND tzc.zct_active=1 $dZone
						INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id AND fz.zon_active=1
						INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id AND tz.zon_active=1
						LEFT JOIN booking bkg ON bkg.bkg_id = aat.aat_booking_id
						WHERE     aat.aat_type IN (2, 3, 8)  AND aat_error_type IS NULL $bkgType
						AND (aat.aat_pickup_date) BETWEEN '{$this->aat_pickup_date1}' AND '{$this->aat_pickup_date2}'
						AND aat_created_at > DATE_SUB(NOW(), INTERVAL {$this->aat_hours} HOUR)
						GROUP BY aat.aat_from_city, aat.aat_to_city HAVING totalRequest > 1";
		$sqlCount		 = "SELECT
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, null)) AS totalRequest
							FROM agent_api_tracking aat
							INNER JOIN cities fromCity ON fromCity.cty_id = aat.aat_from_city
							INNER JOIN cities toCity ON toCity.cty_id = aat.aat_to_city
							INNER JOIN zone_cities fzc ON fzc.zct_cty_id=aat.aat_from_city AND fzc.zct_active=1 $sZone
							INNER JOIN zone_cities tzc ON tzc.zct_cty_id=aat.aat_to_city AND tzc.zct_active=1 $dZone
							INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id AND fz.zon_active=1
							INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id AND tz.zon_active=1
							LEFT JOIN booking bkg ON bkg.bkg_id = aat.aat_booking_id
							WHERE     aat.aat_type IN (2, 3, 8)  AND aat_error_type IS NULL $bkgType
							AND (aat.aat_pickup_date) BETWEEN '{$this->aat_pickup_date1}' AND '{$this->aat_pickup_date2}'
							AND aat_created_at > DATE_SUB(NOW(), INTERVAL {$this->aat_hours} HOUR)
							GROUP BY aat.aat_from_city, aat.aat_to_city HAVING totalRequest > 1";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB3(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['prr_trip_type'],
				'defaultOrder'	 => 'totalRequest DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getPriceanalysisListByfZonetZone()
	{
		if ($this->aat_booking_type != '')
		{
			$bkgType .= 'AND aat.aat_booking_type="' . $this->aat_booking_type . '"';
		}
		if ($this->sourcezone != '')
		{
			$sZone .= 'AND fzc.zct_zon_id="' . $this->sourcezone . '"';
		}
		if ($this->destinationzone != '')
		{
			$dZone .= 'AND tzc.zct_zon_id="' . $this->destinationzone . '"';
		}
		/*if ($this->region != '')
		{
			if (in_array(4, $this->region))
			{
				$this->region[] = 7;
			}
			$regions = implode(',', $this->region);

			$fRegion .= ' AND tst.stt_zone IN (' . $regions . ') ';
		}*/
		$sql = "SELECT aat.aat_booking_type,
							CONCAT(fz.zon_name, ' - ', tz.zon_name) AS rutName,
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, NULL)) AS totalRequest,
							COUNT(DISTINCT IF(aat.aat_type = 8, aat_id, NULL)) AS totalHold,
							ROUND(COUNT(DISTINCT IF(aat.aat_type = 8, aat_id, NULL))/
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, NULL)),2) as ratio,
							COUNT(DISTINCT IF(aat.aat_type = 3, aat_id, NULL)) AS totalConfirmed,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::COMPACT_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldCompact,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::COMPACT_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedCompact,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SUV_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldSUV,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SUV_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedSUV,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SEDAN_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SEDAN_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_DZIRE_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldAssuredSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_DZIRE_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedAssuredSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_INNOVA_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldAssuredInnova,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_INNOVA_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedAssuredInnova,
							(CASE
								WHEN (aat.aat_type = '2') THEN 'Get Quote'
								WHEN (aat.aat_type = '3') THEN 'Create Booking'
								WHEN (aat.aat_type = '8') THEN 'Hold Booking'
							 END)
							   AS requestType
							FROM agent_api_tracking aat
							INNER JOIN cities fromCity ON fromCity.cty_id = aat.aat_from_city
							INNER JOIN cities toCity ON toCity.cty_id = aat.aat_to_city
							INNER JOIN zone_cities fzc ON fzc.zct_cty_id=aat.aat_from_city $sZone
							INNER JOIN zone_cities tzc ON tzc.zct_cty_id=aat.aat_to_city $dZone
							INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
							INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
							LEFT JOIN booking bkg ON bkg.bkg_id = aat.aat_booking_id
							WHERE     aat.aat_type IN (2, 3, 8)  AND aat_error_type IS NULL $bkgType
							AND (aat.aat_pickup_date) BETWEEN '{$this->aat_pickup_date1}' AND '{$this->aat_pickup_date2}'
							AND aat_created_at > DATE_SUB(NOW(), INTERVAL {$this->aat_hours} HOUR)
							GROUP BY fz.zon_id, tz.zon_id HAVING totalRequest > 2";

		$sqlCount		 = "SELECT
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, NULL)) AS totalRequest
							FROM agent_api_tracking aat
							INNER JOIN cities fromCity ON fromCity.cty_id = aat.aat_from_city
							INNER JOIN cities toCity ON toCity.cty_id = aat.aat_to_city
							INNER JOIN zone_cities fzc ON fzc.zct_cty_id=aat.aat_from_city $sZone
							INNER JOIN zone_cities tzc ON tzc.zct_cty_id=aat.aat_to_city $dZone
							INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
							INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
							LEFT JOIN booking bkg ON bkg.bkg_id = aat.aat_booking_id
							WHERE     aat.aat_type IN (2, 3, 8)  AND aat_error_type IS NULL $bkgType
							AND (aat.aat_pickup_date) BETWEEN '{$this->aat_pickup_date1}' AND '{$this->aat_pickup_date2}'
							AND aat_created_at > DATE_SUB(NOW(), INTERVAL {$this->aat_hours} HOUR)
							GROUP BY fz.zon_id, tz.zon_id HAVING totalRequest > 2";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB3(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['prr_trip_type'],
				'defaultOrder'	 => 'totalRequest DESC'],
			'pagination'	 => ['pageSize' => 30],
		]);
		return $dataprovider;
	}

	/* @var $model AgentApiTracking */

	public function getPriceanalysisListByfZone()
	{
		if ($this->aat_booking_type != '')
		{
			$bkgType .= 'AND aat.aat_booking_type="' . $this->aat_booking_type . '"';
		}
		if ($this->sourcezone != '')
		{
			$sZone .= 'AND fzc.zct_zon_id="' . $this->sourcezone . '"';
		}
		if ($this->destinationzone != '')
		{
			$dZone .= 'AND tzc.zct_zon_id="' . $this->destinationzone . '"';
		}
		/*if ($this->region != '')
		{
			if (in_array(4, $this->region))
			{
				$this->region[] = 7;
			}
			$regions = implode(',', $this->region);

			$fRegion .= ' AND tst.stt_zone IN (' . $regions . ') ';
		}*/
		$sql			 = "SELECT aat.aat_booking_type,
							CONCAT(fz.zon_name) AS rutName,
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, NULL)) AS totalRequest,
							COUNT(DISTINCT IF(aat.aat_type = 8, aat_id, NULL)) AS totalHold,
							ROUND(COUNT(DISTINCT IF(aat.aat_type = 8, aat_id, NULL))/
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, NULL)),2) as ratio,
							COUNT(DISTINCT IF(aat.aat_type = 3, aat_id, NULL)) AS totalConfirmed,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::COMPACT_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldCompact,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::COMPACT_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedCompact,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SUV_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldSUV,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SUV_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedSUV,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SEDAN_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::SEDAN_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_DZIRE_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldAssuredSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_DZIRE_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedAssuredSedan,
							COUNT(
							   DISTINCT IF(aat.aat_type = 8 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_INNOVA_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalHoldAssuredInnova,
							COUNT(
							   DISTINCT IF(aat.aat_type = 3 AND bkg.bkg_vehicle_type_id = " . VehicleCategory::ASSURED_INNOVA_ECONOMIC . ",
										   aat_id,
										   NULL))
							   AS totalConfirmedAssuredInnova,
							(CASE
								WHEN (aat.aat_type = '2') THEN 'Get Quote'
								WHEN (aat.aat_type = '3') THEN 'Create Booking'
								WHEN (aat.aat_type = '8') THEN 'Hold Booking'
							 END)
							   AS requestType
							FROM agent_api_tracking aat
							INNER JOIN cities fromCity ON fromCity.cty_id = aat.aat_from_city
							INNER JOIN cities toCity ON toCity.cty_id = aat.aat_to_city
							INNER JOIN zone_cities fzc ON fzc.zct_cty_id=aat.aat_from_city $sZone
							INNER JOIN zone_cities tzc ON tzc.zct_cty_id=aat.aat_to_city $dZone
							INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
							INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
							LEFT JOIN booking bkg ON bkg.bkg_id = aat.aat_booking_id
							WHERE     aat.aat_type IN (2, 3, 8)  AND aat_error_type IS NULL $bkgType
							AND (aat.aat_pickup_date) BETWEEN '$this->aat_pickup_date1' AND '$this->aat_pickup_date2'
							AND aat_created_at > DATE_SUB(NOW(), INTERVAL $this->aat_hours HOUR)
							GROUP BY fz.zon_id HAVING totalRequest > 5";
		$sqlCount		 = "SELECT
							COUNT(DISTINCT IF(aat.aat_type = 2, aat_id, NULL)) AS totalRequest
							FROM agent_api_tracking aat
							INNER JOIN cities fromCity ON fromCity.cty_id = aat.aat_from_city
							INNER JOIN cities toCity ON toCity.cty_id = aat.aat_to_city
							INNER JOIN zone_cities fzc ON fzc.zct_cty_id=aat.aat_from_city $sZone
							INNER JOIN zone_cities tzc ON tzc.zct_cty_id=aat.aat_to_city $dZone
							INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
							INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
							LEFT JOIN booking bkg ON bkg.bkg_id = aat.aat_booking_id
							WHERE     aat.aat_type IN (2, 3, 8)  AND aat_error_type IS NULL $bkgType
							AND (aat.aat_pickup_date) BETWEEN '$this->aat_pickup_date1' AND '$this->aat_pickup_date2'
							AND aat_created_at > DATE_SUB(NOW(), INTERVAL $this->aat_hours HOUR)
							GROUP BY fz.zon_id HAVING totalRequest > 5";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sqlCount) abc", DBUtil::SDB3());
		$dataprovider	 = new CSqlDataProvider($sql, [
			'db'			 => DBUtil::SDB3(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['prr_trip_type'],
				'defaultOrder'	 => 'totalRequest DESC'],
			'pagination'	 => ['pageSize' => 30],
		]);
		return $dataprovider;
	}
	
	
	/**
	 * Function for Archive Agent Api Tracking Data
	 * @param $archiveDB
	 */
	public static function archiveData($archiveDB)
	{
		$transaction = null;
		try
		{
			$i			 = 0;
			$chk		 = true;
			$totRecords	 = 2000000;
			$limit		 = 1000;
			while ($chk)
			{
				// Get Quote & Detail
				$transaction = DBUtil::beginTransaction();
				$sql	 = "SELECT GROUP_CONCAT(aat_id) as aat_id FROM (
						SELECT aat_id FROM `agent_api_tracking`
						WHERE (aat_type IN (2,4) AND aat_pickup_date IS NOT NULL AND (aat_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH)))) 
						ORDER BY aat_id LIMIT 0, $limit
					) as tmp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`agent_api_tracking` (SELECT * FROM `agent_api_tracking` WHERE aat_id IN ($resQ))";
					$rows	 = DBUtil::command($sql)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `agent_api_tracking` WHERE aat_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				// Get Cancelled, Hold Booking & OTP Updates
				$transaction = DBUtil::beginTransaction();
				$sql	 = "SELECT GROUP_CONCAT(aat_id) as aat_id FROM (
								SELECT aat_id FROM `agent_api_tracking`
								WHERE (aat_type IN (6,8,11) AND aat_pickup_date IS NOT NULL AND (aat_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 3 MONTH)))) 
								AND `aat_created_at` < DATE_SUB(NOW(), INTERVAL 18 MONTH) 
								ORDER BY aat_id LIMIT 0, $limit
							) as tmp";
				$resC	 = DBUtil::queryScalar($sql);
				if (!is_null($resC) && $resC != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`agent_api_tracking` (SELECT * FROM `agent_api_tracking` WHERE aat_id IN ($resC))";
					$rows	 = DBUtil::command($sql)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `agent_api_tracking` WHERE aat_id IN ($resC)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				// Get Create Booking, Cab Driver Update, Update Booking
				$transaction = DBUtil::beginTransaction();
				$sql	 = "SELECT GROUP_CONCAT(aat_id) as aat_id FROM (
								SELECT aat_id FROM `agent_api_tracking`
								WHERE (aat_type IN (3,9,10) AND aat_pickup_date IS NOT NULL AND (aat_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 24 MONTH)))) 
								AND `aat_created_at` < DATE(CONCAT((DATE_FORMAT(CURDATE(), '%Y') - 2) , '-01-01')) 
								ORDER BY aat_id LIMIT 0, $limit
							) as tmp";
				$resB	 = DBUtil::queryScalar($sql);
				if (!is_null($resB) && $resB != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`agent_api_tracking` (SELECT * FROM `agent_api_tracking` WHERE aat_id IN ($resB))";
					$rows	 = DBUtil::command($sql)->execute();

					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `agent_api_tracking` WHERE aat_id IN ($resB)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);
				
				// Older Records
				$transaction = DBUtil::beginTransaction();
				$sql	 = "SELECT GROUP_CONCAT(aat_id) as aat_id FROM (
								SELECT aat_id FROM `agent_api_tracking`
								WHERE 1 AND aat_created_at IS NOT NULL AND `aat_created_at` < '2022-07-01 00:00:00' 
								ORDER BY aat_id LIMIT 0, $limit
							) as tmp";
				$resA	 = DBUtil::queryScalar($sql);
				if (!is_null($resA) && $resA != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".`agent_api_tracking` (SELECT * FROM `agent_api_tracking` WHERE aat_id IN ($resA))";
					$rows	 = DBUtil::command($sql)->execute();
					
					if ($rows > 0)
					{
						$sql	 = "DELETE FROM `agent_api_tracking` WHERE aat_id IN ($resA)";
						$rowsDel = DBUtil::command($sql)->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				$i += $limit;
				if (($resQ <= 0 && $resC <= 0 && $resB <= 0 && $resA <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
			DBUtil::rollbackTransaction($transaction);
			Logger::error('archiveData == Exception ' . $e->getMessage());
		}
	}

	/**
	 * Function for Archive Agent Api Tracking Data
	 * @param $archiveDB
	 */
	public static function archiveData_NEW($archiveDB)
	{
		try
		{
			$i			 = 0;
			$chk		 = true;
			$totRecords	 = 100;
			$limit		 = 50;
			while ($chk)
			{
				// Get Quote & Detail
				$sql	 = "SELECT * FROM `agent_api_tracking`
						WHERE (aat_type IN (2,4) AND (aat_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH))))  
						ORDER BY aat_id LIMIT 0, $limit";
				$resQ	 = DBUtil::queryAll($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					self::transferToArchive($resQ);
				}

				// Get Cancelled, Hold Booking & OTP Updates
				/*$sql	 = "SELECT * FROM `agent_api_tracking`
						WHERE (aat_type IN (6,8,11) AND (aat_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 3 MONTH)))) 
						ORDER BY aat_id LIMIT 0, $limit";
				$resC	 = DBUtil::queryAll($sql);
				if (!is_null($resC) && $resC != '')
				{
					self::transferToArchive($resC);
				}

				// Get Create Booking, Cab Driver Update, Update Booking
				$sql	 = "SELECT * FROM `agent_api_tracking`
						WHERE (aat_type IN (3,9,10) AND (aat_pickup_date < DATE(DATE_SUB(NOW(), INTERVAL 6 MONTH)))) 
						ORDER BY aat_id LIMIT 0, $limit";
				$resB	 = DBUtil::queryAll($sql);
				if (!is_null($resB) && $resB != '')
				{
					self::transferToArchive($resB);
				}*/

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
			Logger::error('archiveData == Exception ' . $e->getMessage());
		}
	}

	public static function transferToArchive($rows)
	{
		Logger::writeToConsole('transferToArchive');
		$objADB	 = DBUtil::ADB();
		$resIns	 = false;
		$resDel	 = false;

//		$transactionMDB	 = DBUtil::beginTransaction();

		$schema	 = AgentApiTracking::model()->getTableSchema();
		$columns = $schema->columns;
		try
		{
			if (!$rows || count($rows) == 0)
			{
				return false;
			}

			Logger::writeToConsole('transferToArchive == IN');
			$arrCols	 = Filter::getRowsAndColumns($rows, $columns, 'aat_id');
			$fields		 = $arrCols['fields'];
			$sqlValues	 = $arrCols['sqlValues'];
			$arrAatIds	 = $arrCols['arrIDs'];

			if (count($fields) > 0 && count($sqlValues) > 0 && count($arrAatIds) > 0)
			{
				$sqlField	 = "(" . implode(", ", $fields) . ")";
				$sqlValue	 = implode(", ", $sqlValues);
			
				$transactionADB	 = DBUtil::beginTransaction($objADB);
				$sqlIns	 = "INSERT INTO `agent_api_tracking` $sqlField VALUES $sqlValue ON DUPLICATE KEY UPDATE aat_agent_id = aat_agent_id ";
				$resIns	 = DBUtil::command($sqlIns, $objADB)->execute();
				DBUtil::commitTransaction($transactionADB);
				Logger::writeToConsole('=========');
				Logger::writeToConsole($sqlIns);
				
				$strAatIds	 = implode(',', $arrAatIds);
				Logger::writeToConsole("OLD strAatIds == ".$strAatIds);
				
				$sqlSel = "SELECT aat_id FROM `agent_api_tracking` WHERE aat_id IN ($strAatIds)";
				$res	 = DBUtil::query($sqlSel, $objADB);
				$ids = [];
				foreach ($res as $row)
				{
					$ids[] = $row["aat_id"];
				}
				$newAatIds = implode(",", $ids);
				$res = null;
				$ids = null;
				Logger::writeToConsole("NEW newAatIds == ".$newAatIds);
				
				$sqlDel		 = "DELETE FROM `agent_api_tracking` WHERE aat_id IN ($newAatIds)";
				$resDel		 = DBUtil::command($sqlDel, DBUtil::MDB())->execute();
				Logger::writeToConsole($sqlDel);

//				if ($resDel)
//				{
//					Logger::writeToConsole('COMMIT');
//					DBUtil::commitTransaction($transactionMDB);
//				}
//				else
//				{
//					Logger::writeToConsole('ROLLBACK');
//					DBUtil::rollbackTransaction($transactionMDB);
//					DBUtil::rollbackTransaction($transactionADB);
//				}
			}
		}
		catch (Exception $ex)
		{
//			DBUtil::rollbackTransaction($transactionMDB);
//			DBUtil::rollbackTransaction($transactionADB);
			
			ReturnSet::setException($ex);
		}

		return ($resIns && $resDel) ? true : false;
	}

	public static function getPendingApiPushData()
	{
		$sql	 = "  SELECT aat.aat_id at_id, aat.aat_agent_id agent_id,
                    aat.aat_type atype, aat.aat_booking_id booking_id, SUM(IF(aat_status=1,1,0)) as success
                    FROM   agent_api_tracking aat
                    INNER JOIN booking bkg ON bkg_id = aat.aat_booking_id  AND  aat_type = 9
                    WHERE  1
                    AND bkg_status = 5
                    AND bkg_pickup_date > DATE_SUB(NOW(), INTERVAL 48 HOUR) AND bkg_agent_id=450
                    GROUP BY bkg_id HAVING success=0
					";
		$rows	 = DBUtil::queryAll($sql);
		return $rows;
	}

	/**
	 * Function for Populating Top Demand Routes Table
	 */
	public static function populateTopDemandRoutes()
	{
		$sql	 = "TRUNCATE TABLE top_demand_routes;";
		$sql	 .= "INSERT INTO top_demand_routes
				SELECT aat.aat_from_city, aat.aat_to_city, DATE(aat.aat_pickup_date) as demand_date,
				COUNT(IF(aat_type=2,aat_id,null)) as total_request, COUNT(IF(aat_type=8,aat_id,null)) as total_hold,
				COUNT(IF(aat_type=3,aat_id,null)) as total_confirmed
				FROM agent_api_tracking aat
				WHERE aat.aat_type IN (2,3,8) AND aat.aat_from_city > 0 AND aat.aat_pickup_date BETWEEN CURDATE() AND DATE(DATE_ADD(NOW(), INTERVAL 10 DAY))
				GROUP BY aat.aat_from_city, aat.aat_to_city, demand_date HAVING total_request > 5";
		$rows	 = DBUtil::command($sql)->execute();
		return $rows;
	}

	/**
	 * Function for Getting Top Demand Routes
	 * @param $params
	 */
	public static function fetchTopDemandRoutes($params)
	{
		$where = '1 ';

		if ($params['from_zone'] > 0)
		{
			$where .= ' AND fzc.zct_zon_id = ' . $params['from_zone'];
		}
		if ($params['to_zone'] > 0)
		{
			$where .= ' AND tzc.zct_zon_id = ' . $params['to_zone'];
		}

		if ($params['from_state'] > 0)
		{
			$where .= ' AND fst.stt_id = ' . $params['from_state'];
		}
		if ($params['to_state'] > 0)
		{
			$where .= ' AND tst.stt_id = ' . $params['to_state'];
		}
		if ($params['region'] > 0)
		{
			$where .= ' AND fst.stt_zone = ' . $params['region'];
		}

		$sql = "SELECT fz.zon_name as from_zone_name, tz.zon_name as to_zone_name,
				SUM(total_request) as total_request,
				SUM(total_confirmed) as total_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 0 DAY),'%d'),total_request,0)) as day1_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 0 DAY),'%d'),total_confirmed,0)) as day1_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%d'),total_request,0)) as day2_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%d'),total_confirmed,0)) as day2_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 2 DAY),'%d'),total_request,0)) as day3_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 2 DAY),'%d'),total_confirmed,0)) as day3_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 3 DAY),'%d'),total_request,0)) as day4_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 3 DAY),'%d'),total_confirmed,0)) as day4_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 4 DAY),'%d'),total_request,0)) as day5_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 4 DAY),'%d'),total_confirmed,0)) as day5_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 5 DAY),'%d'),total_request,0)) as day6_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 5 DAY),'%d'),total_confirmed,0)) as day6_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 6 DAY),'%d'),total_request,0)) as day7_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 6 DAY),'%d'),total_confirmed,0)) as day7_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%d'),total_request,0)) as day8_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%d'),total_confirmed,0)) as day8_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 8 DAY),'%d'),total_request,0)) as day9_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 8 DAY),'%d'),total_confirmed,0)) as day9_confirmed,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 9 DAY),'%d'),total_request,0)) as day10_request,
				SUM(IF(DATE_FORMAT(demand_date,'%d')=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 9 DAY),'%d'),total_confirmed,0)) as day10_confirmed
				FROM `top_demand_routes` tfr
				INNER JOIN cities fc ON tfr.aat_from_city=fc.cty_id
				INNER JOIN states fst ON fc.cty_state_id=fst.stt_id
				INNER JOIN cities tc ON tfr.aat_to_city=tc.cty_id
				INNER JOIN states tst ON tc.cty_state_id=tst.stt_id
				INNER JOIN zone_cities fzc ON fzc.zct_cty_id=tfr.aat_from_city AND fzc.zct_active=1
				INNER JOIN zone_cities tzc ON tzc.zct_cty_id=tfr.aat_to_city AND tzc.zct_active=1
				INNER JOIN zones fz ON fz.zon_id=fzc.zct_zon_id
				INNER JOIN zones tz ON tz.zon_id=tzc.zct_zon_id
				WHERE $where
				GROUP BY fzc.zct_zon_id, tzc.zct_zon_id
				ORDER BY total_request DESC";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [],
				'defaultOrder'	 => 'total_request DESC'],
			'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getMissingCabDriverData()
	{
		$sql	 = "SELECT   *
FROM     (SELECT   bkg_id, bkg_booking_id, bkg_agent_ref_code,
                bkg_pickup_date, aat_pickup_date, bkg_create_date,
                group_concat(DISTINCT aat_type) aat_type
          FROM     booking bkg INNER JOIN agent_api_tracking aat ON aat.aat_booking_id = bkg.bkg_id
          WHERE    bkg_pickup_date >= '2019-01-19 00:00:00' AND bkg_agent_id = 450 AND bkg_status IN (5, 6, 7)
          GROUP BY bkg_id) a
WHERE    NOT FIND_in_set(9, aat_type)
ORDER BY bkg_create_date DESC";
		$dataSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataSet;
	}

	public static function getCabDriverUpdateData()
	{
		$sql	 = "SELECT distinct bkg_id  FROM booking
			WHERE bkg_status IN (5,6,7)
			AND booking.bkg_pickup_date >=  DATE_SUB(NOW(), INTERVAL 1 DAY)
			AND booking.bkg_agent_id=450
			AND bkg_id NOT IN (
			SELECT DISTINCT aat_booking_id
			FROM agent_api_tracking aat
			WHERE aat.aat_type=9 AND aat.aat_status=1 )
	";
		$dataSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataSet;
	}

	public static function getBookingDataForNextNHours()
	{
		$sql	 = "SELECT bkg_id FROM booking WHERE bkg_status = 5 AND bkg_agent_id = 18190 AND bkg_pickup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 14 HOUR)";
		$dataSet = DBUtil::queryAll($sql, DBUtil::SDB());
		return $dataSet;
	}

	public static function pushFailedDriverDetailsGmt()
	{
		$sql		 = "SELECT
						`bkg_id`, bkg_create_date
					FROM
						`booking`
					WHERE
						`bkg_status` IN(5) AND `bkg_agent_id`= 18190 AND`bkg_pickup_date` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND DATE_ADD(NOW(), INTERVAL 4 HOUR)
					ORDER BY bkg_pickup_date ASC";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			$params		 = array('bkgId' => $data['bkg_id'], 'createDate' => $data['bkg_create_date']);
			$qry		 = "SELECT COUNT(`aat_id`) AS cnt FROM `agent_api_tracking`
					WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = 9 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
			if ($dataCount < 1)
			{
				$bmodel		 = Booking::model()->findByPk($data['bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
				$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			}
		}
	}

	public static function pushCancelBooking()
	{
		$sql		 = "SELECT
                        `bkg_id`,bkg_create_date
                    FROM
                        `booking`
                    INNER JOIN booking_pref ON bpr_bkg_id = bkg_id
                    WHERE
                        `bkg_status` IN(9) AND `bkg_agent_id` = 18190 AND bkg_is_fbg_type <> 1 AND `bkg_pickup_date` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW()
                        ORDER BY bkg_pickup_date ASC LIMIT 10";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			echo "\n\n\n==== Found BKGID: " . $data['bkg_id'];

			$params = array('bkgId' => $data['bkg_id'], 'createDate' => $data['bkg_create_date']);

			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = 14 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);

			echo "\nCOUNT: " . $dataCount;

			$bkgId		 = $data['bkg_id'];
			$qry2		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= $bkgId AND `aat_type` = 6 AND `aat_status`= 1";
			$dataCount2	 = DBUtil::queryScalar($qry2, DBUtil::MDB());
			
			$qry3		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= $bkgId AND `aat_type` = 17 AND `aat_status`IN(1,2)";
			$dataCount3	 = DBUtil::queryScalar($qry3, DBUtil::MDB());

			if ($dataCount2 == 0 && $dataCount3 == 0 )
			{
				if ($dataCount < 1)
				{
					$bmodel		 = Booking::model()->findByPk($data['bkg_id']);
					$typeAction	 = AgentApiTracking::TYPE_TRIP_CANCELLED;
					$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
					echo "\nPUSHED BKGID: " . $data['bkg_id'];
				}
			}
		}
	}

	public static function pushLeftForPickup()
	{
		$sql		 = "SELECT
						`btl_bkg_id`,bkg_create_date
					FROM
						`booking_track_log`
					INNER JOIN booking ON booking.bkg_id = booking_track_log.btl_bkg_id AND booking.bkg_status = 5
					WHERE
						`btl_event_type_id` = 201 AND booking.bkg_agent_id = 18190 AND `btl_created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW()";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			echo "\n\n\n==== Found BKGID: " . $data['btl_bkg_id'];
			$params		 = array('bkgId' => $data['btl_bkg_id'], 'createDate' => $data['bkg_create_date']);
			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = 15 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
			echo "\nCOUNT: " . $dataCount;
			if ($dataCount < 1)
			{
				$bmodel		 = Booking::model()->findByPk($data['btl_bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_LEFT_FOR_PICKUP;
				$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
				echo "\nPUSHED BKGID: " . $data['btl_bkg_id'];
			}
		}
	}

	public static function pushArrivedForTrip()
	{
		$sql		 = "SELECT
						`btl_bkg_id`,bkg_create_date
					FROM
						`booking_track_log`
					INNER JOIN booking ON booking.bkg_id = booking_track_log.btl_bkg_id AND booking.bkg_status = 5
					WHERE
						`btl_event_type_id` = 203 AND booking.bkg_agent_id = 18190 AND `btl_created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW()";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			echo "\n\n\n==== Found BKGID: " . $data['btl_bkg_id'];
			$params		 = array('bkgId' => $data['btl_bkg_id'], 'createDate' => $data['bkg_create_date']);
			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = 18 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
			echo "\nCOUNT: " . $dataCount;
			if ($dataCount < 1)
			{
				$bmodel		 = Booking::model()->findByPk($data['btl_bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_ARRIVED;
				$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
				echo "\nPUSHED BKGID: " . $data['btl_bkg_id'];
			}
		}
	}

	public static function pushStartTrip()
	{
		$sql		 = "SELECT
						`btl_bkg_id`,bkg_create_date
					FROM
						`booking_track_log`
					INNER JOIN booking ON booking.bkg_id = booking_track_log.btl_bkg_id AND booking.bkg_status = 5
					WHERE
						`btl_event_type_id` = 101 AND booking.bkg_agent_id = 18190 AND `btl_created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW()";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			echo "\n\n\n==== Found BKGID: " . $data['btl_bkg_id'];
			$params		 = array('bkgId' => $data['btl_bkg_id'], 'createDate' => $data['bkg_create_date']);
			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = 12 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
			echo "\nCOUNT: " . $dataCount;
			if ($dataCount < 1)
			{
				$bmodel		 = Booking::model()->findByPk($data['btl_bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_TRIP_START;
				$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
				echo "\nPUSHED BKGID: " . $data['btl_bkg_id'];
			}
		}
	}


	public static function pushStopTrip()
	{
		$sql		 = "SELECT
						`btl_bkg_id`,bkg_create_date
					FROM
						`booking_track_log`
					INNER JOIN booking ON booking.bkg_id = booking_track_log.btl_bkg_id AND booking.bkg_status IN (5,6)
					WHERE
						`btl_event_type_id` = 104 AND booking.bkg_agent_id = 18190 AND `btl_created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW()";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			echo "\n\n\n==== Found BKGID: " . $data['btl_bkg_id'];
			$params		 = array('bkgId' => $data['btl_bkg_id'], 'createDate' => $data['bkg_create_date']);
			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND aat_created_at >= :createDate AND `aat_type` = 13 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
			echo "\nCOUNT: " . $dataCount;
			if ($dataCount < 1 && $dataCount != 1)
			{
				echo "\nEnter: ";
				$bmodel		 = Booking::model()->findByPk($data['btl_bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_TRIP_END;
				$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
				echo "\nPUSHED BKGID: " . $data['btl_bkg_id'];
			}
		}
	}

	public static function pushFbgConfirm()
	{
		$sql		 = "SELECT bkg.bkg_id,bkg.bkg_status FROM booking bkg
                     INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id
                     WHERE bpr.bkg_is_fbg_type =1 AND bkg.bkg_agent_id = 18190 AND bpr.bkg_is_fbg_confirm = 1
                     AND bkg.bkg_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 6 HOUR) AND NOW() AND bkg_status IN (2,3)";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			$params		 = array('bkgId' => $data['bkg_id']);
			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND `aat_type` = 25 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);
			echo "\nCOUNT: " . $dataCount;
			if ($dataCount < 1)
			{
				$bmodel		 = Booking::model()->findByPk($data['bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_REVERSE_BOOKING_ACCEPT;
				$response	 = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
				echo "\nPUSHED BKGID: " . $data['bkg_id'];
			}
		}
	}

	public function getEventTypeById($eventId)
	{
		switch ($eventId)
		{
			case 2:
				$eventType	 = 'GetQuote';
				break;
			case 3:
				$eventType	 = 'Confirm Booking';
				break;
			case 4:
				$eventType	 = 'Get Booking Details';
				break;
			case 6:
				$eventType	 = 'Cancelled BY MMT';
				break;
			case 8:
				$eventType	 = 'Create/Hold Booking';
				break;
			case 9:
				$eventType	 = 'Cab Driver Update';
				break;
			case 12:
				$eventType	 = 'Trip Start(boarded)';
				break;
			case 13:
				$eventType	 = 'Trip End(alight)';
				break;
			case 14:
				$eventType	 = 'Cancelled BY GOZO';
				break;
			case 15:
				$eventType	 = 'Left for Pick Up(start)';
				break;
			case 16:
				$eventType	 = 'Cab Driver Reassign(reassign)';
				break;
			case 17:
				$eventType	 = 'No Show(not_boarded)';
				break;
			case 18:
				$eventType	 = 'Arrived(arrived)';
				break;
			case 19:
				$eventType	 = 'Passenger Details Update (MMT Pushing Data to US)';
				break;
			case 20:
				$eventType	 = 'Passenger Details Update (We are pulling the data from MMT)';
				break;
			case 21:
				$eventType	 = 'Update Last Location';
				break;
			case 22:
				$eventType	 = 'Get Review';
				break;
			case 22:
				$eventType	 = 'Update (transferz pushing data to us)';
				break;
		}
		return $eventType;
	}

	public function getTypeList()
	{
		$data = [
			9	 => 'Cab Driver Update',
			12	 => 'Trip Start',
			13	 => 'Trip End',
			15	 => 'Left for Pick Up',
			16	 => 'Cab Driver Reassign',
			17	 => 'No Show',
			18	 => 'Arrived',
			21	 => 'Update Last Location'
		];
		return $data;
	}

	public function getSingleType($type)
	{
		$list = $this->getTypeList();
		return $list[$type];
	}

	public function getMmtReports($type)
	{
		$this->aat_type	 = $type;
		$fromDate		 = $this->from_date . " 00:00:00";
		$toDate			 = $this->to_date . " 23:59:59";
		$params			 = ['fromDate' => $fromDate, 'toDate' => $toDate, 'type' => $type];

		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND aat_created_at BETWEEN :fromDate AND :toDate";
		}
		if ($this->aat_type > 0)
		{
			$where .= " AND aat_type =:type";
		}
		$sql = "SELECT DATE_FORMAT(agent_api_tracking.aat_created_at, '%Y-%m-%d')
				AS aat_created_at,
				aat_type,
			 FLOOR(HOUR(agent_api_tracking.aat_created_at) / 24)
				AS hour,
			 COUNT(DISTINCT agent_api_tracking.aat_ref_id)
				AS requestCount,
			 COUNT(
				DISTINCT IF(agent_api_tracking.aat_status = 1,
							agent_api_tracking.aat_ref_id,
							NULL))
				AS successCount,
			 COUNT(
				DISTINCT IF(agent_api_tracking.aat_status=2,
							agent_api_tracking.aat_ref_id,
							NULL))
				AS failedCount,
			 ROUND(
				  COUNT(
					 DISTINCT IF(agent_api_tracking.aat_status=2,
								 agent_api_tracking.aat_ref_id,
								 NULL))
				* 100
				/ COUNT(DISTINCT agent_api_tracking.aat_ref_id))
				AS errorPercent
			  FROM agent_api_tracking
				   INNER JOIN
				   (SELECT max(aat_id) AS aat_id
					FROM `agent_api_tracking`
					 WHERE 1=1 {$where}
					GROUP BY DATE_FORMAT(aat_created_at, '%Y-%m-%d'), aat_ref_id) temp
					  ON temp.aat_id = agent_api_tracking.aat_id
			  GROUP BY DATE_FORMAT(agent_api_tracking.aat_created_at, '%Y-%m-%d')
			  ORDER BY agent_api_tracking.aat_id DESC";

		if ($type == AgentApiTracking::TYPE_GET_REVIEW)
		{
			$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
			$dataprovider	 = new CSqlDataProvider($sql, [
				'params'		 => $params,
				'db'			 => DBUtil::SDB(),
				'totalItemCount' => $count,
				'sort'			 => ['attributes'	 => ['aat_created_at'],
					'defaultOrder'	 => 'aat_created_at  DESC'], 'pagination'	 => ['pageSize' => 50],
			]);
			return $dataprovider;
		}
		else
		{
			$recordset = DBUtil::queryAll($sql, DBUtil::SDB(), $params);
			return $recordset;
		}
	}

	public function getMmtData()
	{
		$fromDate	 = $this->from_date . " 00:00:00";
		$toDate		 = $this->to_date . " 23:59:59";
		$params		 = ['fromDate' => $fromDate, 'toDate' => $toDate];

		if ($fromDate != '' && $toDate != '')
		{
			$where .= " AND bkg.bkg_pickup_date >=:fromDate AND bkg.bkg_pickup_date  <:toDate";
		}

		$sql = "SELECT DATE_FORMAT(bkg.bkg_pickup_date,'%Y') date,
			    CONCAT(DATE(MIN(bkg.bkg_pickup_date)), ' - ', DATE(MAX(bkg.bkg_pickup_date))) AS maxMinPickUpDate,
				COUNT(DISTINCT bkg.bkg_id) AS bookingCount,
				COUNT(DISTINCT IF(btk.bkg_trip_arrive_time IS NULL, bkg.bkg_id, NULL))  AS  arrivedCount,
				COUNT(DISTINCT IF(btk.bkg_trip_start_time IS NULL, bkg.bkg_id, NULL))  AS  startCount,
				COUNT(DISTINCT IF(btk.bkg_trip_end_time IS NULL, bkg.bkg_id, NULL))  AS endCount,
				ROUND(COUNT(DISTINCT IF(btk.bkg_trip_arrive_time IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS arrivedPercent,
				ROUND(COUNT(DISTINCT IF(btk.bkg_trip_start_time IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS startPercent,
				ROUND(COUNT(DISTINCT IF(btk.bkg_trip_end_time IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS endPercent,
				ROUND(COUNT(DISTINCT IF(a4.aat_id IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS leftForPickupAPIPercent,
				ROUND(COUNT(DISTINCT IF(a1.aat_id IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS arrivedAPIPercent,
				ROUND(COUNT(DISTINCT IF(a2.aat_id IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS startAPIPercent,
				ROUND(COUNT(DISTINCT IF(a3.aat_id IS NULL, bkg.bkg_id, NULL)) * 100 / COUNT(DISTINCT bkg.bkg_id),0)  AS endAPIPercent
				FROM booking bkg
				INNER JOIN booking_track btk ON bkg.bkg_id = btk.btk_bkg_id
				LEFT JOIN tmpAAT a4 ON bkg.bkg_id = a4.aat_booking_id AND a4.aat_type = 15 AND a4.aat_error_type IS NULL
				LEFT JOIN tmpAAT a1 ON bkg.bkg_id = a1.aat_booking_id AND a1.aat_type = 18 AND a1.aat_error_type IS NULL
				LEFT JOIN tmpAAT a2 ON bkg.bkg_id = a2.aat_booking_id AND a2.aat_type = 12 AND a2.aat_error_type IS NULL
				LEFT JOIN tmpAAT a3 ON bkg.bkg_id = a3.aat_booking_id AND a3.aat_type = 13 AND a3.aat_error_type IS NULL
				WHERE bkg.bkg_agent_id = 18190 AND bkg.bkg_status IN (5,6,7) $where
				GROUP BY date";

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['date'],
				'defaultOrder'	 => 'date  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public static function getData($bkgId)
	{
		$sql		 = "SELECT aat_created_at  FROM agent_api_tracking WHERE aat_type = 21 AND aat_booking_id = $bkgId ORDER BY aat_id DESC LIMIT 0,1";
		$dataList	 = DBUtil::queryRow($sql, DBUtil::MDB());
		return $dataList;
	}

	/**
	 * This function is used to get last update location against booking
	 * @param integer $bkgId
	 * @return integer aat_id
	 */
	public static function getLastLocationEventByBooking($bkgId, $aatType)
	{
		return self::getLastEventByBooking($bkgId, $aatType);
	}

	public static function getLastEventByBooking($bkgId, $eventId)
	{
		$params	 = ["bkgId" => $bkgId, "eventId" => $eventId];
		$sql	 = "SELECT aat_id FROM agent_api_tracking
                    WHERE aat_booking_id= :bkgId AND aat_type= :eventId
                    ORDER BY aat_id DESC LIMIT 0,1";

		$aatId = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $aatId;
	}

	public function getReportList()
	{
		$list = [
			'1'	 => 'MMT Push Report',
			'2'	 => 'MMT Review Report',
			'3'	 => 'Account Mismatch Report',
			'4'	 => 'Advance Mismatch Report',
			'5'	 => 'MMT API Report',
			'6'	 => 'Vendor Trip Purchase Missing',
			'7'	 => 'Vendor Trip Purchase Multiple Entries',
			'8'	 => 'Driver Cash Collected Missing',
			'9'	 => 'Driver Cash Collected Multiple Entries',
			'10' => 'Booking Advance Multiple Entries',
			'11' => 'Partner Commission Multiple Entries',
			'12' => 'Partner Commission Missing',
			'13' => 'Partner Receivable Reports',
			'14' => 'Penalty Type Reports',
			'15' => 'Booking Amount Mismatch Reports',
			'16' => 'Driver Collection Mismatch Report'

		];
		return $list;
	}

	public function getMmtTrackingDataByBkgId()
	{
		$params = ['bkgId' => $this->aat_booking_id];

		// Getting Search Ref Id
		$sqlSearchRefId	 = "SELECT aat_ref_id FROM agent_api_tracking WHERE aat_booking_id =:bkgId AND aat_type = 8
							UNION
							SELECT aat_ref_id FROM gozo_archive.agent_api_tracking WHERE aat_booking_id =:bkgId AND aat_type = 8";
		$searchRefId	 = DBUtil::queryScalar($sqlSearchRefId, DBUtil::SDB(), $params);

		// SQL
		$sql = "SELECT * FROM agent_api_tracking WHERE aat_booking_id =:bkgId
				UNION
				SELECT * FROM gozo_archive.agent_api_tracking WHERE aat_booking_id =:bkgId";
		if ($searchRefId)
		{
			$sql .= " OR (aat_ref_id = '{$searchRefId}' AND aat_type = 2) ";
		}

		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'params'		 => $params,
			'db'			 => DBUtil::SDB(),
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['aat_created_at'],
				'defaultOrder'	 => 'aat_created_at  DESC'], 'pagination'	 => ['pageSize' => 50],
		]);
		return $dataprovider;
	}

	public function getFromArchieveById($aatId)
	{
		if (!$aatId)
		{
			return false;
		}

		$params = ['aatId' => $aatId];

		$sql = "SELECT * FROM gozo_archive.agent_api_tracking WHERE aat_id =:aatId";
		#$model = DBUtil::query($sql, DBUtil::SDB(), $params);

		$model = $this->findBySql($sql, $params);

		return $model;
	}

	public function testScript()
	{
		$requestsJson = '
		  [
			[
			  {
				"source": {
				  "place_id": "ChIJN1BUz54CDTkRScM5FUjyFhA",
				  "address": "34\/5, Block 5, WEA, Karol Bagh, New Delhi, Delhi 110005, India",
				  "latitude": 28.647563999999999140300133149139583110809326171875,
				  "longitude": 77.1894800000000032014213502407073974609375
				},
				"destination": {
				  "place_id": "ChIJgeJXTN9KbDkRCS7yDDrG4Qw",
				  "address": "Jaipur, Rajasthan, India",
				  "latitude": 26.9124335999999999557985574938356876373291015625,
				  "longitude": 75.7872708999999957768523017875850200653076171875
				},
				"trip_type": "ONE_WAY",
				"start_time": "2021-07-07 09:15:00",
				"search_id": "5e2841f57a5758005a811370"
			  }
			],
			[
			  {
				"source": {
				  "place_id": "ChIJN1BUz54CDTkRScM5FUjyFhA",
				  "address": "34\/5, Block 5, WEA, Karol Bagh, New Delhi, Delhi 110005, India",
				  "latitude": 28.647563999999999140300133149139583110809326171875,
				  "longitude": 77.1894800000000032014213502407073974609375
				},
				"destination": {
				  "place_id": "ChIJgeJXTN9KbDkRCS7yDDrG4Qw",
				  "address": "Jaipur, Rajasthan, India",
				  "latitude": 26.9124335999999999557985574938356876373291015625,
				  "longitude": 75.7872708999999957768523017875850200653076171875
				},
				"trip_type": "ONE_WAY",
				"start_time": "2021-07-07 09:15:00",
				"search_id": "5e2841f57a5758005a811370"
			  }
			]
		  ]';

		$reqArrays	 = CJSON::decode($requestsJson, true);
		$count		 = 0;
		foreach ($reqArrays as $reqArray)
		{
			$count++;
			echo "<br><b># $count</b><br>";
			foreach ($reqArray as $key => $value)
			{
				$requests = json_encode($value);

				$data		 = $requests;
				$jsonMapper	 = new JsonMapper();
				$jsonObj	 = CJSON::decode($data, false);

				// Response
				$response = new Stub\mmt\Response();
				try
				{
					/** @var \Stub\mmt\QuoteRequest $obj */
					$obj = $jsonMapper->map($jsonObj, new Stub\mmt\QuoteRequest());

					/** @var Booking $model */
					$model		 = $obj->getModel();
					//Logger::profile("Model Retreived");
					// AgentApiTracking
					$aatType	 = AgentApiTracking::TYPE_GET_QUOTE;
					$aatModel	 = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
					if (!$model->validate())
					{
						throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
					// Quote
					$quotData = Quote::populateFromModel($model, [1, 2, 3, 14, 15, 16, 61, 63, 64, 66], false, false);
					//unset($quotData);
					if (empty($quotData))
					{
						Logger::warning("No cabs rate available");
						//Logger::setModelCategory("Quote", "populateFromModel");
						$quotData = Quote::populateFromModel($model, [1, 2, 3, 14, 15, 16, 61, 63, 64, 66], false, false);
						//Logger::unsetModelCategory("Quote", "populateFromModel");
						if (empty($quotData))
						{
							throw new Exception("No cabs available for this route", ReturnSet::ERROR_NO_RECORDS_FOUND);
						}
					}
					// Response
					$quoteResponse	 = new Stub\mmt\QuoteResponse();
					$quoteResponse->setQuoteData($quotData, $jsonObj);
					$data			 = Filter::removeNull($quoteResponse);
					if (empty($quoteResponse->car_types))
					{
						logger::pushTraceLogs();
						Logger::trace("Quote Data:  " . json_encode($quotData));
						Logger::trace("Quote Response: " . json_encode($data));
					}
					$response->setData($data);
					Logger::profile("Success");
				}
				catch (Exception $e)
				{
					Logger::warning("Failed to get GMT quote: " . $e->getMessage());
					$ret = ReturnSet::setException($e);
					$response->setError($ret);
				}
				//Update AgentApiTracking
				if (!$aatModel)
				{
					$aatType	 = AgentApiTracking::TYPE_GET_QUOTE;
					$aatModel	 = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
					$status		 = 2;
				}
				else
				{
					$status = 1;
				}
				$time		 = Filter::getExecutionTime();
				$error_type	 = $response->code;
				$error_msg	 = $response->error;
				$aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
				Logger::info("Response: " . json_encode($response));
				echo "Response: " . json_encode($response);
				echo "\n";
				echo "============================================================================================================================================== ";
			}
		}
	}

	public function getLocalPath()
	{
		$filePath = $this->getLogPath() . DIRECTORY_SEPARATOR . $this->getFileName();
		return $filePath;
	}

	public function getSpacePath()
	{
		$agentId	 = $this->aat_agent_id;
		$event		 = $this->aat_type;
		$date		 = $this->aat_created_at;
		$id			 = $this->aat_id;
		$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d/H/i");
		$path		 = "{$agentId}/{$event}/{$dateString}/{$id}.apl";
		return $path;
	}

	/**
	 * @return Stub\common\SpaceFile
	 */
	public function uploadToSpace($removeLocal = true, $saveJSON = true)
	{
		$spaceFile	 = $this->getSpacePath();
		$localFile	 = $this->getLocalPath();

		if ($this->aat_s3_data != '' && Filter::isJSON($this->aat_s3_data))
		{
			$objSpaceFile1 = \Stub\common\SpaceFile::populate($this->aat_s3_data);
			if ($objSpaceFile1->isExist())
			{
				$spaceFileObj = $objSpaceFile1->getFile();
				if (file_exists($localFile))
				{
					$content		 = file_get_contents($localFile);
					$spaceContent	 = $spaceFileObj->getContents();
					if ($content == $spaceContent)
					{
						goto skipAppend;
					}

					$content = $spaceContent . '\n-----------------\n' . $content;
					$handler = fopen($localFile, 'w');
					$res	 = fwrite($handler, $content);
					fclose($handler);
				}
			}
		}
		skipAppend:
		$objSpaceFile = Storage::uploadText(Storage::getPartnerAPISpace(), $spaceFile, $localFile, $removeLocal);
		if ($saveJSON && $objSpaceFile != null)
		{
			$this->aat_s3_data = $objSpaceFile->toJSON();
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
		if ($this->aat_s3_data != null && $this->aat_s3_data != '{}')
		{
			$objSpaceFile		 = Stub\common\SpaceFile::populate($this->aat_s3_data);
			$url				 = $objSpaceFile->getURL(strtotime("+1 hour"));
			$this->aat_s3_data	 = $objSpaceFile->toJSON();
			$this->save();
		}
		return $url;
	}

	public static function uploadAllToS3($limit = 100, $type = null, $start = 0, $threads = 5)
	{
		$limit1 = 0;
		while ($limit > 0)
		{
			if($limit1 > 0)
			{
				sleep(5);
			}
			
			$limit1 = min([1000, $limit]);

			$condSql = " AND aat_type != 2 ";
			if ($type != null)
			{
				$condSql = " AND aat_type = {$type} ";
			}
			if ($type === 0)
			{
				$condSql = "";
			}
			$condSql .= " AND aat_server_id = " . Config::getServerID();

			$sql = "SELECT aat_id FROM agent_api_tracking WHERE aat_s3_data IS NULL $condSql ORDER BY aat_id DESC LIMIT $start, $limit1";
			Logger::writeToConsole("Limit: $limit1 , Limit: $limit");
			$res = DBUtil::query($sql, DBUtil::SDB());
			if ($res->getRowCount() == 0)
			{
				Logger::writeToConsole("ROW COUNT: 0");
				break;
			}

			if ($threads <= 1)
			{
				$threads							 = 1;
				Spatie\Async\Pool::$forceSynchronous = true;
			}

			$binaryPath = Config::getFileParam("php_bin");

			$pool	 = \components\AsyncPool\Pool::create()->withBinary($binaryPath)->sleepTime(2000)->concurrency($threads)->autoload(PUBLIC_PATH . "/bootstrap.php");
			$i		 = 0;
			foreach ($res as $row)
			{
				$i++;
				$aatId	 = $row["aat_id"];
				$process = $pool->add(function () use ($aatId) {
									/** @var AgentApiTracking $aatModel */
									$aatModel = AgentApiTracking::model()->findByPk($aatId);
									if($aatModel->aat_s3_data == '')
									{
										$aatModel->uploadToS3();
									}
									return $aatModel;
								}, 1024 * 1024 * 1024 * 10)
								->then(function ($aatModel) {
									Logger::writeToConsole($aatModel->aat_s3_data);
								})->catch(function ($exception) {
					Logger::exception(new Exception($exception));
				});
			}
			$pool->wait();
			$limit -= $limit1;
			Logger::flush();
			Logger::writeToConsole("LIMIT LEFT: $limit");
		}
	}

	public static function reuploadAllToS3($limit = 1000, $type = '14')
	{
		$start = 0;
		while ($limit > 0)
		{
			$condSql = " AND aat_type != 2 ";
			if ($type != null)
			{
				$condSql = " AND aat_type = {$type} ";
			}
			if ($type === 0)
			{
				$condSql = "";
			}

			$sql	 = "SELECT aat_id FROM agent_api_tracking WHERE aat_s3_data IS NOT NULL $condSql ORDER BY aat_id DESC LIMIT $start, 1000";
			$res	 = DBUtil::query($sql, DBUtil::MDB());
			$count	 = $res->getRowCount();
			Logger::writeToConsole($sql . ":: COUNT - $count");
			if ($count == 0)
			{
				break;
			}

			$pool = \components\AsyncPool\Pool::create()->withBinary('php')->sleepTime(2000)->concurrency(5)->autoload(PUBLIC_PATH . "/bootstrap.php");

			foreach ($res as $row)
			{
				$aatId	 = $row["aat_id"];
				$process = $pool->add(function () use ($aatId) {
									/** @var AgentApiTracking $aatModel */
									$aatModel = AgentApiTracking::model()->findByPk($aatId);
									if (!file_exists($aatModel->getLocalPath()))
									{
										if ($aatModel->aat_s3_data == '')
										{
											$aatModel->aat_s3_data = "{}";
											$aatModel->save();
										}
										return $aatModel;
									}
									$spaceFile = $aatModel->uploadToSpace();
									return $aatModel;
								}, 1024 * 1024 * 1024 * 10)
								->then(function ($aatModel) {
									Logger::writeToConsole($aatModel->aat_s3_data);
								})->catch(function ($exception) {
					Logger::exception($exception);
				});
			}
			$pool->wait();
			$start	 += 1000;
			$limit	 -= 1000;
			Logger::flush();
		}
	}

	public static function uploadArchiveToS3($limit = 1000, $type = null, $start = 0)
	{
		$i = 0;
		while ($limit > 0)
		{
			$limit1 = min([2500, $limit]);

			$condSql = " AND aat_type != 2 ";
			if ($type != null)
			{
				$condSql = " AND aat_type = {$type} ";
			}
			if ($type === 0)
			{
				$condSql = "";
			}

			$sql = "SELECT aat_id, aat_agent_id, aat_type, aat_created_at, aat_booking_type  FROM gozo_archive.agent_api_tracking WHERE aat_s3_data IS NULL $condSql ORDER BY aat_id DESC LIMIT $start, $limit1";

//			$success = DBUtil::waitForSlaveSync(DBUtil::SDB());
//			if (!$success)
//			{
//				break;
//			}
			$res = DBUtil::query($sql, DBUtil::MDB());
			if ($res->getRowCount() == 0)
			{
				Logger::writeToConsole($sql . " \nFinish");
				break;
			}

			foreach ($res as $row)
			{

				$agentId	 = $row['aat_agent_id'];
				$event		 = $row['aat_type'];
				$date		 = $row['aat_created_at'];
				$id			 = $row['aat_id'];
				$bookingType = $row['aat_booking_type'];
				$dateString	 = DateTimeFormat::SQLDateTimeToDateTime($date)->format("Y/m/d/H/i");
				$spaceFile	 = "{$agentId}/{$event}/{$dateString}/{$id}.apl";

				$path			 = Yii::app()->basePath;
				$mainfoldername	 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'partner' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . $agentId;
				$logPath		 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($date), true);

				$fileName	 = $event . '_' . $id . '_' . $bookingType . '.apl';
				$localFile	 = $logPath . DIRECTORY_SEPARATOR . $fileName;
				$sql		 = "UPDATE gozo_archive.agent_api_tracking SET aat_s3_data=:data WHERE aat_id=:id";
				$params		 = ['id' => $id, 'data' => '{}'];
				if (!file_exists($localFile))
				{
					DBUtil::execute($sql, $params);
					Logger::writeToConsole($params['data']);
					continue;
				}

				$objSpaceFile = Storage::uploadText(Storage::getPartnerAPISpace(), $spaceFile, $localFile);

				if ($objSpaceFile == null)
				{
					continue;
				}

				$params['data'] = $objSpaceFile->toJSON();
				DBUtil::execute($sql, $params);
				Logger::writeToConsole("{$i}:" . $params['data']);
				$i++;
			}

			$limit -= $limit1;
			Logger::flush();
		}
	}

	/** @return Stub\common\SpaceFile */
	public function uploadToS3()
	{
		$aatModel = $this;
		if (!file_exists($aatModel->getLocalPath()))
		{
			if ($aatModel->aat_s3_data == '')
			{
				$aatModel->aat_s3_data = "{}";
				$aatModel->save();
			}
			return null;
		}
		$spaceFile = $aatModel->uploadToSpace();
		return $spaceFile;
	}

	public static function getCountBySearchId($searchId, $duration = 1440)
	{
		$sql = "SELECT COUNT(1) cnt FROM `agent_api_tracking` 
				WHERE `aat_type` = 8 AND `aat_created_at` > DATE_SUB(NOW(),INTERVAL $duration MINUTE) 
				AND aat_ref_id = '{$searchId}'";
		$res = DBUtil::queryScalar($sql, DBUtil::SDB());
		return $res;
	}

}
