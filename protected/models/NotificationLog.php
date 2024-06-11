<?php

/**
 * This is the model class for table "notification_log".
 *
 * The followings are the available columns in table 'notification_log':
 * @property integer $ntl_id
 * @property integer $ntl_entity_type
 * @property integer $ntl_entity_id
 * @property string $ntl_title
 * @property string $ntl_message
 * @property string $ntl_expiry_date
 * @property string $ntl_payload
 * @property integer $ntl_event_code
 * @property integer $ntl_ref_type
 * @property integer $ntl_ref_id
 * @property string $ntl_apt_ids
 * @property integer $ntl_status
 * @property integer $ntl_active
 * @property string $ntl_created_on
 * @property string $ntl_sent_on
 */
class NotificationLog extends CActiveRecord
{

	const TYPE_CONSUMER							 = 1;
	const TYPE_VENDOR								 = 2;
	const TYPE_DRIVER								 = 3;
	const TYPE_ADMIN								 = 4;
	const CODE_CUSTOMER_NOTIFIED_DRIVER_ARRIVED	 = 700;
	const CODE_CUSTOMER_NOTIFIED_TRIP_START		 = 701;
	const CODE_CUSTOMER_NOTIFIED_DRIVER_UPDATE	 = 702;
	const CODE_CUSTOMER_NOTIFIED_TRIP_END			 = 703;
	const CODE_CUSTOMER_NOTIFIED_TRIP_CANCELLED	 = 704;
	const CODE_CUSTOMER_NOTIFIED_RATING_REQUEST	 = 705;
	const CODE_CUSTOMER_NOTIFIED_BOOKING_ACCEPT_GNOW	 = 706;
	const CODE_CUSTOMER_NOTIFIED_BOOKING_OFFER_GNOW	 = 707;

	const CODE_CUSTOMER_BOOKING_MODIFIED				 = 507;
	const CODE_CONSUMER_BROADCAST						 = 538;

	public $ntl_created_on1;
	public $ntl_created_on2;
	public $ntl_entity_type	 = [1 => 'Consumer', 2 => 'Vendor', 3 => "Driver", 4 => "Admin"];
	public $ntl_ref_type	 = [1 => 'General', 2 => 'Booking', 3 => "Trip", 4 => "Document", 5 => "CBR/SR"]; //[1 => 'Individual User', 2 => 'Agent', 3 => 'Corporate'];
	public $ntl_status_list	 = [0 => 'Pending', 1 => 'Success', 2 => 'Failed'];
	public $vndid, $drvid, $userid, $admid;
	public $ntl_date1, $ntl_date2, $gnowType, $bkgStatus, $bkgId, $bkgCreateType, $vndSelected, $isDuplicate, $transferzSelected;

	const CODE_VENDOR_ASSIGNED						 = 500;
	const CODE_CABDRIVER_ASSIGNED						 = 501;
	const CODE_COMPLETED								 = 502;
	const CODE_SETTLED								 = 503;
	const CODE_VENDOR_DENY							 = 504;
	const CODE_USER_CANCEL							 = 505;
	const CODE_PENDING								 = 506;
	const CODE_MODIFIED								 = 507;
	const CODE_VENDOR_REGISTER						 = 508;
	const CODE_DELETED								 = 509;
	const CODE_BROADCAST_IMAGE						 = 511;
	const CODE_VENDOR_BROADCAST						 = 512;
	const CODE_VENDOR_ADVANCE							 = 513;
	const CODE_VENDOR_BOOKING_REQUEST					 = 514;
	const CODE_DRIVER_PENDING							 = 516;
	const CODE_MISSING_PAPERWORK						 = 518;
	const CODE_CONSUMER_NOTIFICATION					 = 520;
	const CODE_DRIVER_BROADCAST						 = 521;
	const CODE_CHAT_MESSAGE							 = 522;
	const CODE_DRIVER_PICKUP_REMINDER					 = 523;
	const CODE_DRIVER_RATING_RECIEVED					 = 524;
	const CODE_VENDOR_UNASSIGNED						 = 525;
	const CODE_VENDOR_MARK_COMPLETE_REMINDER			 = 526;
	const CODE_VENDOR_TIER							 = 527;
	const CODE_DELEGATED_OM							 = 531;
	const CODE_BROADCAST_ADMIN						 = 532;
	const CODE_CABDRIVER_UNASSIGNED					 = 533;
	const CODE_TRIP_START_NOTIFICATION				 = 534;
	const CODE_TRIP_END_NOTIFICATION					 = 535;
	const CODE_VENDOR_CANCEL_NOTIFICATION				 = 536;
	const CODE_SOS_OFF_NOTIFICATION					 = 537;
	const CODE_ESCALATION_ON_NOTIFICATION				 = 539;
	const CODE_VENDOR_BOOST_NOTIFICATION				 = 540;
	const CODE_AUTOCANCEL_NOTIFICATION				 = 541;
	const CODE_MANUALASSIGNMENT_NOTIFICATION			 = 542;
	const CODE_CRITICALASSIGNMENT_NOTIFICATION		 = 543;
	const CODE_FOR_LOCK								 = 600;
	const CODE_VENDOR_GOZONOW_BOOKING_REQUEST			 = 550;
	const CODE_VENDOR_GOZONOW_BOOKING_ALLOCATED		 = 551;
	const CODE_VENDOR_GOZONOW_NOTIFIED_REJECTED_OFFER	 = 552;
	const CODE_PRICE_ANALYST							 = 553;
	const CODE_VENDOR_ASSIGN_CAB_DRIVER				 = 560;
	const CODE_DCO_NEW_CHAT_NOTIFIED			=	570;
	 

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'notification_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
		return array(
			array('ntl_entity_type, ntl_entity_id, ntl_message, ntl_event_code', 'required'),
			array('ntl_entity_type, ntl_entity_id, ntl_event_code, ntl_ref_type, ntl_ref_id, ntl_status, ntl_active', 'numerical', 'integerOnly' => true),
			array('ntl_title', 'length', 'max' => 255),
			array('ntl_message', 'length', 'max' => 1000),
			array('ntl_expiry_date, ntl_payload, ntl_sent_on,ntl_date1, ntl_date2,gnowType,bkgStatus,bkgId,vndSelected,transferzSelected', 'safe'),
			// The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
			array('ntl_id, ntl_entity_type, ntl_entity_id, ntl_title, ntl_message, ntl_expiry_date, ntl_payload, ntl_event_code, ntl_ref_type, ntl_ref_id, ntl_status, ntl_active, ntl_created_on, ntl_sent_on', 'safe', 'on' => 'search'),
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
			'ntl_id'			 => 'Ntl',
			'ntl_entity_type'	 => 'Entity Type',
			'ntl_entity_id'		 => 'Entity',
			'ntl_title'			 => 'Title',
			'ntl_message'		 => 'Message',
			'ntl_expiry_date'	 => 'Expiry Date',
			'ntl_payload'		 => 'Payload',
			'ntl_event_code'	 => 'Event Code',
			'ntl_ref_type'		 => 'Ref Type',
			'ntl_ref_id'		 => 'Id',
			'ntl_status'		 => 'Status',
			'ntl_active'		 => 'Active',
			'ntl_created_on'	 => 'Created On',
			'ntl_sent_on'		 => 'Sent On',
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

		$criteria->compare('ntl_id', $this->ntl_id);
		$criteria->compare('ntl_entity_type', $this->ntl_entity_type);
		$criteria->compare('ntl_entity_id', $this->ntl_entity_id);
		$criteria->compare('ntl_title', $this->ntl_title, true);
		$criteria->compare('ntl_message', $this->ntl_message, true);
		$criteria->compare('ntl_expiry_date', $this->ntl_expiry_date, true);
		$criteria->compare('ntl_payload', $this->ntl_payload, true);
		$criteria->compare('ntl_event_code', $this->ntl_event_code);
		$criteria->compare('ntl_ref_type', $this->ntl_ref_type);
		$criteria->compare('ntl_ref_id', $this->ntl_ref_id);
		$criteria->compare('ntl_status', $this->ntl_status);
		$criteria->compare('ntl_active', $this->ntl_active);
		$criteria->compare('ntl_created_on', $this->ntl_created_on, true);
		$criteria->compare('ntl_sent_on', $this->ntl_sent_on, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NotificationLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function eventList($eventCode = 0)
	{
		$eventlist = [
			550	 => 'Vendor > Headsup Notified > Booking Request',
			551	 => 'Vendor > Headsup Notified > Booking Allocated',
			552	 => 'Vendor > Headsup Notified > Rejected Offer',
			560	 => 'Vendor > Headsup Notified > Cab Driver Update Request',
			570	 => 'DCO > Headsup Notified > New Chat Message',
		];
		if ($eventCode > 0)
		{
			return $eventlist[$eventCode];
		}
		return $eventlist;
	}

	public static function createLog($aptModels, $param, $notificationStatus = '', $logId = '')
	{
		if ($logId != '')
		{
			$notificationLog = NotificationLog::model()->findByPk($logId);
			goto result;
		}
		$eventCode	 = $param['notifications']['EventCode'];
		$userType	 = $aptModels[0]->apt_user_type;
		$entityId	 = ($userType == 1) ? $aptModels[0]->apt_user_id : $aptModels[0]->apt_entity_id;
		$type		 = NotificationLog::entityTypeMapping($userType);
		$refVal		 = NotificationLog::getRefIdByPayLoad($param);
		if ($eventCode == 551)
		{
			if ($type == UserInfo::TYPE_VENDOR)
			{
				$tripId	 = $refVal[0];
				$logId	 = NotificationLog::getIdByTripVendorEvent($tripId, $entityId, $eventCode);
			}
			if ($type == UserInfo::TYPE_DRIVER)
			{
				if (isset($param['notifications']['bkgId']) && $param['notifications']['bkgId'] > 0)
				{
					$refVal = ['0' => $param['notifications']['bkgId'], 1 => 2];
				}
				$logId = NotificationLog::getIdByDriverEvent($refVal, $entityId, $eventCode);
			}
			if ($logId > 0)
			{
				$notificationLog = NotificationLog::model()->findByPk($logId);
				goto result;
			}
		}


		$notificationLog = new NotificationLog();

//        $entityId        = $aptModels[0]->apt_entity_id; 
		$aptId = AppTokens::getIdByEntity($entityId, $userType);

		$notificationLog->ntl_entity_type	 = $type;
		$notificationLog->ntl_entity_id		 = $entityId;
		$notificationLog->ntl_title			 = $param['notifications']['title'];
		$notificationLog->ntl_message		 = ($param['notifications']['message'] != '') ? $param['notifications']['message'] : $param['notifications']['body'];

		$notificationLog->ntl_payload	 = json_encode($param, JSON_UNESCAPED_SLASHES);
		$notificationLog->ntl_event_code = $eventCode;
		$notificationLog->ntl_ref_type	 = $refVal[1];

		$notificationLog->ntl_ref_id	 = $refVal[0];
		$notificationLog->ntl_apt_ids	 = json_encode($aptId);
		$notificationLog->ntl_created_on = new CDbExpression('NOW()');
		$notificationLog->ntl_status	 = 0;
		result:
		if ($notificationStatus)
		{
			$ntlStatus						 = $notificationStatus['fcm'];
			$ntlDecode						 = json_decode($ntlStatus);
			$status							 = $ntlDecode->success;
			$notificationLog->ntl_payload	 = json_encode($param, JSON_UNESCAPED_SLASHES);
			$notificationLog->ntl_sent_on	 = new CDbExpression('NOW()');
			if ($status > 2)
			{
				$status = 1;
			}
			$notificationLog->ntl_status = $status;
		}


		if ($notificationLog->validate())
		{
			$notificationLog->save();
		}
		else
		{
			$errors = json_encode($notificationLog->getErrors()) . " - " . json_encode($notificationLog->getErrors());
			throw new Exception($errors);
		}
		return $notificationLog->ntl_id;
	}

	/**
	 * 
	 * @param type $aptUserType usertype from app_tokens table
	 * @return type
	 */
	public static function entityTypeMapping($aptUserType)
	{
		switch ($aptUserType)
		{
			case 1:
				$type	 = UserInfo::TYPE_CONSUMER;
				break;
			case 2:
				$type	 = UserInfo::TYPE_VENDOR;
				break;
			case 4:
				$type	 = UserInfo::TYPE_AGENT;
				break;
			case 3:
			case 5:
				$type	 = UserInfo::TYPE_DRIVER;
				break;
			case 6:
				$type	 = UserInfo::TYPE_ADMIN;
				break;
		}
		return $type;
	}

	public static function getRefIdByPayLoad($param)
	{
		$notification = $param['notifications'];
		foreach ($notification as $key => $val)
		{
			$notificationKey = '';
			$keyVal			 = str_replace('_', '', str_replace(' ', '', strtolower($key)));
			if (($keyVal == 'bookingid' || $keyVal == 'bkgid' || $keyVal == 'tripid' || $keyVal == 'scqid') && $val != '')
			{
				switch ($keyVal)
				{
					case ($keyVal == 'bookingid' || $keyVal == 'bkgid'):
						$refType = 2;
						if (is_numeric($val) == false)
						{
							$bookingModel	 = Booking::model()->getBkgIdByBookingId($val);
							$val			 = $bookingModel->bkg_id;
						}

						$value	 = [$val, $refType];
						break;
					case 'tripid':
						$refType = 3;
						$value	 = [$val, $refType];
						break;
					case 'scqid':
						$refType = 5;
						$value	 = [$val, $refType];
						break;
				}
				return $value;
			}
		}
	}

	/*
	 * This function is used to get all the Entity Types of this model as in JSON format to show in select2
	 * return json array
	 */

	public function getJSONAllEntityType()
	{
		$rows		 = $this->ntl_entity_type;
		$arrEntity	 = array();
		foreach ($rows as $key => $row)
		{
			$arrEntity[] = array("id" => $key, "text" => $row);
		}
		$data = CJSON::encode($arrEntity);
		return $data;
	}

	/*
	 * This function is used to get all the Ref Types of this model as in JSON format to show in select2
	 * return json array
	 */

	public function getJSONAllRefType()
	{
		$rows	 = $this->ntl_ref_type;
		$arrRef	 = array();
		foreach ($rows as $key => $row)
		{
			$arrRef[] = array("id" => $key, "text" => $row);
		}
		$data = CJSON::encode($arrRef);
		return $data;
	}

	/*
	 * This function is used to get/List all the Notification log entries in list page
	 * param $requestDetails as array 
	 * return dataProvider
	 */

	public static function getNotificationLogList($requestDetails = null)
	{
		$createDate1 = $requestDetails->ntl_created_on1; //$requestDetails["ntl_created_on1"];
		$createDate2 = $requestDetails->ntl_created_on2; //$requestDetails["ntl_created_on2"];
		$refType	 = $requestDetails->ntl_ref_type; //$requestDetails["ntl_ref_type"];
		$entiyType	 = $requestDetails->ntl_entity_type; ////$requestDetails["ntl_entity_type"];
		$refId		 = $requestDetails->ntl_ref_id; ////$requestDetails["ntl_ref_id"];
		$vndId		 = $requestDetails->vndid;
		$drvId		 = $requestDetails->drvid;
		$usrId		 = $requestDetails->userid;
		$admId		 = $requestDetails->admid;
		$extraJoin	 = $select		 = $wheresql	 = '';

		if ($entiyType == NotificationLog::TYPE_VENDOR && $vndId != '')
		{
			if (is_numeric($vndId) == true)
			{
				$wheresql .= " AND ntl_entity_id = " . $vndId;
			}
			else
			{
				$wheresql .= " AND vnd_code LIKE '%$vndId%' ";
			}
		}
		if ($entiyType == NotificationLog::TYPE_DRIVER && $drvId != '')
		{
			if (is_numeric($drvId) == true)
			{
				$wheresql .= " AND ntl_entity_id = " . $drvId;
			}
			else
			{
				$wheresql .= " AND drv_code LIKE '%$drvId%' ";
			}
		}
		if ($entiyType == NotificationLog::TYPE_CONSUMER && $usrId != '')
		{
			$wheresql .= " AND ntl_entity_id = " . $usrId;
		}
		if ($entiyType == NotificationLog::TYPE_ADMIN && $admId != '')
		{
			$wheresql .= " AND ntl_entity_id = " . $admId;
		}
		if (!empty($createDate1) && !empty($createDate2))
		{
			$wheresql .= " AND ntl_created_on BETWEEN '" . $createDate1 . "' AND '" . $createDate2 . "'";
		}
		if (!empty($entiyType))
		{
			$wheresql .= " AND ntl_entity_type IN (" . $entiyType . ")";
		}
		if (!empty($refType))
		{
			$wheresql .= " AND ntl_ref_type = " . $refType;
		}
		if (!empty($refId))
		{
			$wheresql .= " AND ntl_ref_id = " . $refId;
		}

		$join = "LEFT JOIN vendors vnd ON vnd.vnd_id = notification_log.ntl_entity_id
                 LEFT JOIN drivers drv ON drv.drv_id = notification_log.ntl_entity_id";

		$getNotificationLogSql		 = "SELECT  ntl_id,
								CASE WHEN ntl_entity_type=1 THEN 'Consumer'
									 WHEN ntl_entity_type=2 THEN 'Vendor'
									 WHEN ntl_entity_type=3 THEN 'Driver'
									 WHEN ntl_entity_type=4 THEN 'Admin'
									 WHEN ntl_entity_type=5 THEN 'Agent'
									 WHEN ntl_entity_type=10 THEN 'System'
									 WHEN ntl_entity_type=6 THEN 'Corporate'
									 WHEN ntl_entity_type=11 THEN 'Internal'
								END as ntl_entity_type,
								CASE WHEN ntl_entity_type=1 THEN ntl_entity_id
									 WHEN ntl_entity_type=2 THEN vnd_code
									 WHEN ntl_entity_type=3 THEN drv_code
									 WHEN ntl_entity_type=4 THEN ntl_entity_id
								END as ntlentity,
								ntl_title,ntl_message,ntl_expiry_date, ntl_event_code,
								CASE WHEN ntl_ref_type=1 THEN 'General'
									 WHEN ntl_ref_type=2 THEN 'Booking'
									 WHEN ntl_ref_type=3 THEN 'Trip'
									 WHEN ntl_ref_type=4 THEN 'Document'
									 WHEN ntl_ref_type=5 THEN 'CBR/SR'
								END as ntl_ref_type,
								ntl_ref_id,ntl_apt_ids,ntl_entity_id,
								CASE WHEN ntl_status=0 THEN 'Pending'
									 WHEN ntl_status=1 THEN 'Success'
									 WHEN ntl_status=2 THEN 'Failed'
								END as ntl_status,
								ntl_active,
								ntl_created_on,ntl_sent_on $select 
								 FROM notification_log 
								 $join
								 WHERE 1=1 and ntl_active =1 
									$wheresql	";
//echo "<pre>".$getNotificationLogSql;die;
		$getNotificationLogCountSql	 = "SELECT  COUNT(*) FROM (SELECT ntl_id FROM notification_log $join WHERE 1=1 and ntl_active =1 " . $wheresql . ") abc";
		$count						 = DBUtil::command($getNotificationLogCountSql, DBUtil::SDB())->queryScalar();
		$dataprovider				 = new CSqlDataProvider($getNotificationLogSql, [
			"totalItemCount" => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['defaultOrder' => 'ntl_id DESC'],
			"pagination"	 =>
			[
				"pageSize" => 50
			],
		]);
		return $dataprovider;
	}

	public static function getDetails($entityId, $type, $getCount = false, $pageSize = 1, $pageCount = 1)
	{
		$limit1 = (($pageCount - 1) * $pageSize);

		$qry = "SELECT ntl.ntl_id, ntl.ntl_event_code, ntl.ntl_payload, ntl.ntl_title, ntl.ntl_message, ntl.ntl_created_on, ntl.ntl_is_read, 
                ntl_action_value, ntl_action_at, ntl_read_at
                FROM notification_log ntl  
                WHERE ntl.ntl_entity_id = :entId AND ntl.ntl_entity_type = :type
				ORDER BY ntl.ntl_id DESC limit $limit1, $pageSize";

		$countSql = "SELECT count(distinct ntl.ntl_id) 	FROM notification_log ntl WHERE ntl.ntl_entity_id = :entId AND ntl.ntl_entity_type = :type ";
		if ($getCount)
		{
			$result = DBUtil::queryScalar($countSql, DBUtil::SDB(), ["entId" => $entityId, "type" => $type]);
		}
		else
		{
			$result = DBUtil::query($qry, DBUtil::SDB(), ["entId" => $entityId, "type" => $type]);
		}

		return $result;
	}

	public static function getList($entityIds, $type, $id, $isNew)
	{
		$order = '';
		if ($id == 0 && $isNew == 1)
		{
			$order = " ORDER BY ntl.ntl_id DESC";
		}
		if ($isNew == 1 || ($isNew != 0 && $id == 0))
		{
			$where .= " AND ntl.ntl_id > $id ";
		}
		else
		{
			$where .= " AND ntl.ntl_id < $id ORDER BY ntl.ntl_id DESC";
		}
		$qry = "SELECT ntl.ntl_id, ntl.ntl_event_code, ntl.ntl_payload, ntl.ntl_title, ntl.ntl_message, ntl.ntl_created_on, ntl.ntl_is_read, 
                ntl_action_value, ntl_action_at, ntl_read_at
                FROM notification_log ntl  
                WHERE ntl.ntl_entity_id IN (:entId) AND ntl_show_vendor = 0 
				AND ntl.ntl_entity_type = :type $where
				$order limit 60";

		$result = DBUtil::query($qry, DBUtil::SDB(), ["entId" => $entityIds, "type" => $type]);
		return $result;
	}

	public static function updateReadNotification($value)
	{
		$success = true;
		$message = '';
		if (empty($value))
		{
			$success = false;
			goto resultSet;
		}

		/* @var $ntlModel NotificationLog */
		$ntlModel	 = NotificationLog::model()->find('ntl_id  = :id', array('id' => $value['id']));
		$isRead		 = $value['isRead'];
		if (!$ntlModel)
		{
			$success = false;
			$message = ('Data not found');
			goto skip;
		}
		if ($ntlModel->ntl_is_read != 1)
		{
			$ntlModel->ntl_is_read	 = $isRead;
			$ntlModel->ntl_read_at	 = new CDbExpression('NOW()');

			if (!$ntlModel->save())
			{
				$message = ('Unable to save data.');
				$success = false;
			}
		}
		resultSet:
		skip:
		return ['success' => $success, 'message' => $message];
	}

	public static function processNotificationValue($value)
	{
		$success = true;
		$message = '';
		if (empty($value))
		{
			$success = false;
			goto resultSet;
		}
		/* @var $ntlModel NotificationLog */
		$ntlModel = NotificationLog::model()->find('ntl_id = :id and ntl_status = 1', array('id' => $value['id']));
		if ($ntlModel)
		{
			$actionVal	 = json_encode($value['actionVal']);
			$actionDate	 = new CDbExpression('NOW()');
			if ($actionVal != '')
			{
				$ntlModel->ntl_action_value	 = $actionVal;
				$ntlModel->ntl_action_at	 = $actionDate;
			}
			if (!$ntlModel->save())
			{
				$message = ('Unable to save data.');
				$success = false;
			}
		}
		else
		{
			$message = ('Data not found');
			$success = false;
		}

		resultSet:
		return ['success' => $success, 'message' => $message];
	}

	public function getbyVendorId($vndid)
	{
		$sql = "SELECT *,(CASE ntl_status
                    WHEN 0 THEN 'Pending'
                    WHEN 1 THEN 'Success'
                    WHEN 2 THEN 'Failed'
                    ELSE '' END
                ) as status FROM notification_log WHERE ntl_entity_type = 2 AND ntl_entity_id = $vndid AND ntl_active=1";

		$count			 = DBUtil::queryScalar("SELECT COUNT(1) FROM ($sql) abc");
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => ['ntl_created_on'],
				'defaultOrder'	 => 'ntl_id   DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public static function updateLogById($value)
	{
		$success = true;
		$message = '';
		if (empty($value))
		{
			$success = false;
			goto resultSet;
		}
		/* @var $ntlModel NotificationLog */
		$ntlModel = NotificationLog::model()->find('ntl_id = :id', array('id' => $value['id']));
		if ($ntlModel)
		{
			$ntlModel->ntl_show_vendor = 1;

			if (!$ntlModel->save())
			{
				$message = ('Unable to save data.');
				$success = false;
			}
		}
		else
		{
			$message = ('Data not found');
			$success = false;
		}

		resultSet:
		return ['success' => $success, 'message' => $message];
	}

	public static function getIdForGozonow($vndId, $tripId)
	{
		$params	 = ['vndId' => $vndId, 'tripId' => $tripId];
		$sql	 = "SELECT ntl.ntl_id  FROM  notification_log ntl  
		WHERE ntl.ntl_ref_id = :tripId AND ntl.ntl_entity_id=:vndId  
		AND ntl.ntl_entity_type=2 AND ntl.ntl_event_code IN (550,551) 
		AND ntl.ntl_ref_type=3 AND ntl.ntl_is_read<>1 ORDER BY ntl_created_on DESC";
		$result	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $result;
	}

	public static function getAllDetailsGozonowByTripid($tripId)
	{
//		$params	 = ['tripId' => $tripId];
		$sql = "SELECT  ntl.*,vnd.*,bvr.bvr_bid_amount,bvr.bvr_accepted FROM  notification_log ntl  
		INNER JOIN vendors vnd ON vnd.vnd_id=ntl.ntl_entity_id
		LEFT JOIN booking_vendor_request bvr ON bvr.bvr_bcb_id = ntl.ntl_ref_id 
			AND  bvr.bvr_vendor_id=ntl.ntl_entity_id AND bvr.bvr_accepted >0 AND ntl.ntl_event_code = 550
		WHERE ntl.ntl_ref_id = $tripId 
		AND ntl.ntl_entity_type=2 AND ntl.ntl_event_code IN (550,551) 
		AND ntl.ntl_ref_type=3 ";
//		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);
//		return $result;

		$count			 = DBUtil::command("SELECT COUNT(*) FROM ($sql) abc")->queryScalar();
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'sort'			 => ['attributes'	 => [],
				'defaultOrder'	 => ['ntl_created_on DESC']],
			'pagination'	 => ['pageSize' => 20],
		]);

		return $dataprovider;
	}

	/**
	 * Function for archiving Notifications 
	 */
	public function archiveData($archiveDB, $upperLimit = 1000000, $lowerlimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerlimit;
		while ($chk)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(ntl_id) AS ntl_id FROM (SELECT ntl_id FROM notification_log WHERE 1 AND ntl_created_on < CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), ' 00:00:00') ORDER BY ntl_id LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					$sql	 = "INSERT INTO " . $archiveDB . ".notification_log (SELECT * FROM notification_log WHERE ntl_id IN ($resQ))";
					$rows	 = DBUtil::execute($sql);
					$a		 = $row;
					if ($rows > 0)
					{
						$sql = "DELETE FROM `notification_log` WHERE ntl_id IN ($resQ)";
						DBUtil::execute($sql);
					}
				}
				DBUtil::commitTransaction($transaction);
				$i += $limit;

				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	/**
	 * 
	 * @return \CSqlDataProvider
	 */
	public static function gnowOfferReport($model)
	{
//		$params	 = [];
		$ntlWhere		 = "";
		$where			 = "  AND (bpr.bkg_is_gozonow <> 0)";
		$bvrWhere		 = "";
		$fromDate		 = $model->ntl_date1;
		$toDate			 = $model->ntl_date2;
		$tripId			 = $model->ntl_ref_id;
		$bkgStatus		 = $model->bkgStatus;
		$gnowType		 = $model->gnowType;
		$bkgCreateType	 = $model->bkgCreateType;
		$bkgId			 = $model->bkgId;
		$vndSelected	 = $model->vndSelected;
		$transferzSelected = $model->transferzSelected;
		$isDuplicateFlag = $model->isDuplicate == null ? '1' : ($model->isDuplicate == 1 ? '1' : '0');
		if ($fromDate != '' && $toDate != '')
		{
			$where		 .= "  AND (bkg_gnow_created_at BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') ";
//			$where	 .= "  AND (bkg1.bkg_create_date BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') ";
//			$ntlWhere		 .= "  AND (ntl.ntl_created_on BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') ";
			$bvrWhere	 = "   AND bvr.bvr_created_at >= '" . $fromDate . " 00:00:00' ";
			$ntlWhere	 .= "  AND  ntl.ntl_created_on  >= '" . $fromDate . " 00:00:00'";
		}
		else
		{
			$where .= " AND 0";
			goto skipAll;
		}
		if ($tripId > 0)
		{
			$ntlWhere	 .= "  AND (ntl.ntl_ref_id = $tripId) ";
			$where		 .= "  AND (bkg1.bkg_bcb_id = $tripId) ";
		}
		if ($bkgId > 0)
		{
			$where .= "  AND (bkg1.bkg_id = $bkgId) ";
		}
		if (is_array($bkgStatus) && count($bkgStatus) > 0)
		{
			$bkgStatusStr	 = implode(',', $bkgStatus);
			$where			 .= "  AND (bkg1.bkg_status IN ($bkgStatusStr)) ";
		}
		if (is_array($gnowType) && count($gnowType) > 0)
		{
			$gnowTypeStr = implode(',', $gnowType);
			$where		 .= "  AND (bpr.bkg_is_gozonow IN ($gnowTypeStr)) ";
		}
		elseif ($gnowType != '' && count($gnowType) > 0)
		{
			$where .= "  AND (bpr.bkg_is_gozonow = $gnowType) ";
		}
		if (is_array($bkgCreateType) && count($bkgCreateType) > 0)
		{
			$bkgCreateTypeStr	 = implode(',', $bkgCreateType);
			$where				 .= "  AND (btr.bkg_create_user_type IN ($bkgCreateTypeStr)) ";
		}

		if (is_array($vndSelected) && count($vndSelected) > 0)
		{
			$vndSelectedStr	 = implode(',', $vndSelected);
			$where			 .= "  AND ( bvr_is_preferred_vendor IN ($vndSelectedStr)) ";
		}
		if (is_array($transferzSelected) && count($transferzSelected) > 0)
		{
			$transferzSelectedStr	 = Config::get('transferz.partner.id');
			$where			 .= "  AND ( bkg1.bkg_agent_id IN ($transferzSelectedStr)) ";
		}

		if ($model->isDuplicate != null)
		{
			$where .= $model->isDuplicate == 1 ? "  AND bkg_is_related_booking >=1 " : "  AND bkg_is_related_booking=0 ";
		}

		skipAll:
		$sqlCount = "SELECT bcb.bcb_id tripId	
					FROM booking bkg1 
                    INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg1.bkg_bcb_id					
					INNER JOIN booking_pref bpr ON bkg1.bkg_id = bpr.bpr_bkg_id AND bpr.bkg_is_gozonow IN (1,2)
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg1.bkg_id  
                    LEFT JOIN notification_log ntl 
						ON  ntl.ntl_ref_id =bcb.bcb_id AND ntl.ntl_event_code=550 AND ntl.ntl_ref_type=3 $ntlWhere
					LEFT JOIN booking_vendor_request bvr 
						ON bvr.bvr_is_gozonow =1 AND bvr.bvr_bcb_id=bcb.bcb_id  
					WHERE 1 $where
					GROUP BY tripId ";

		$sql		 = "SELECT bcb.bcb_id tripId, ntl_id,bcb.bcb_bkg_id1 bkgId1,  nl.totalSent, bpr.bkg_is_gozonow,
				delivered,isReceived,isRead, IF(bv.gnowBid IS NULL,0,bv.gnowBid) gnowBid,bidDeny, bidAmts,
				bcb.bcb_vendor_amount tripVendorAmount ,biv.bkg_vendor_amount bkgVendorAmount,
				biv.bkg_total_amount bkgTotalAmount,bkg1.bkg_status bkgStatus1,bvr_is_preferred_vendor,
				bkg1.bkg_pickup_date pickupDate, bkg1.bkg_create_date createDate,bkg_assign_mode,bkg_assigned_at,
				bkg_gnow_created_at,
				ntl_sent_on,IF(bv.gnowBid > 0,bvr_created,'NA') bvr_created,bkg_reconfirm_flag,bkg_create_user_type,
				bkg_is_related_booking AS isDuplicate,$isDuplicateFlag AS isDuplicateFlag,bkg1.bkg_booking_type as bkgType
				FROM  booking bkg1
                INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg1.bkg_bcb_id		
				INNER JOIN booking_pref bpr ON bkg1.bkg_id = bpr.bpr_bkg_id  AND bpr.bkg_is_gozonow IN (1,2)
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg1.bkg_id 
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg1.bkg_id  
                LEFT JOIN  
				(
					SELECT ntl.ntl_ref_id tripId, ntl.ntl_id,
					COUNT(DISTINCT ntl_id) totalSent,
					SUM(IF(ntl.ntl_status>0,1,0)) delivered, 
					SUM(IF(ntl.ntl_is_read IN (1,2),1,0)) isReceived ,
					SUM(IF(ntl.ntl_is_read =1,1,0)) isRead ,
					MAX(ntl.ntl_created_on) ntl_sent_on
					from notification_log ntl 
					WHERE  1 $ntlWhere  AND  ntl.ntl_event_code=550 AND ntl.ntl_ref_type=3
					GROUP BY ntl.ntl_ref_id
				) as nl ON nl.tripId = bcb.bcb_id  			 
				LEFT JOIN 
				(
					SELECT bvr.bvr_bcb_id tripId, 
					sum(if(bvr.bvr_bid_amount > 0 AND bvr.bvr_accepted=1,1,0)) gnowBid , 
					sum(if(bvr.bvr_bid_amount = 0 AND bvr.bvr_accepted=2,1,0)) bidDeny,
					sum(bvr.bvr_is_preferred_vendor) bvr_is_preferred_vendor , 
					GROUP_CONCAT(if(bvr.bvr_bid_amount > 0 AND bvr.bvr_accepted=1,bvr.bvr_bid_amount,NULL) SEPARATOR ',') bidAmts,
					MAX(bvr.bvr_created_at) bvr_created
					from booking_vendor_request bvr 
					WHERE bvr.bvr_is_gozonow =1 $bvrWhere 
					GROUP BY bvr.bvr_bcb_id
				) as bv ON bv.tripId=bcb.bcb_id  
				WHERE 1 $where
				GROUP BY tripId 
				";
		$countQuery	 = "SELECT COUNT(*) FROM ($sqlCount) abc";
		$count		 = DBUtil::queryScalar($countQuery, DBUtil::SDB());

		$dataprovider = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['tripId', 'pickupDate', 'createDate', 'isDuplicate'],
				'defaultOrder'	 => 'createDate DESC'],
			'pagination'	 => ['pageSize' => 100],
		]);

		return $dataprovider;
	}

	public static function getGNowOfferSummary($model)
	{

//		$params	 = [];
		$ntlWhere		 = "";
		$where			 = "  AND (bpr.bkg_is_gozonow <> 0)";
		$bvrWhere		 = "";
		$fromDate		 = $model->ntl_date1;
		$toDate			 = $model->ntl_date2;
		$tripId			 = $model->ntl_ref_id;
		$bkgStatus		 = $model->bkgStatus;
		$gnowType		 = $model->gnowType;
		$bkgCreateType	 = $model->bkgCreateType;
		$bkgId			 = $model->bkgId;
		$vndSelected	 = $model->vndSelected;
		$transferzSelected = $model->transferzSelected;
		$isDuplicateFlag = $model->isDuplicate == null ? '1' : ($model->isDuplicate == 1 ? '1' : '0');
		if ($fromDate != '' && $toDate != '')
		{
			$where		 .= "  AND (bkg_gnow_created_at BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 23:59:59') ";
			$bvrWhere	 = "   AND bvr.bvr_created_at >= '" . $fromDate . " 00:00:00' ";
			$ntlWhere	 .= "  AND  ntl.ntl_created_on  >= '" . $fromDate . " 00:00:00'";
		}
		else
		{
			$where .= " AND 0";
			goto skipAll;
		}
		if ($tripId > 0)
		{
			$ntlWhere	 .= "  AND (ntl.ntl_ref_id = $tripId) ";
			$where		 .= "  AND (bkg1.bkg_bcb_id = $tripId) ";
		}
		if ($bkgId > 0)
		{
			$where .= "  AND (bkg1.bkg_id = $bkgId) ";
		}
		if (is_array($bkgStatus) && count($bkgStatus) > 0)
		{
			$bkgStatusStr	 = implode(',', $bkgStatus);
			$where			 .= "  AND (bkg1.bkg_status IN ($bkgStatusStr)) ";
		}
		if (is_array($gnowType) && count($gnowType) > 0)
		{
			$gnowTypeStr = implode(',', $gnowType);
			$where		 .= "  AND (bpr.bkg_is_gozonow IN ($gnowTypeStr)) ";
		}
		elseif ($gnowType != '' && count($gnowType) > 0)
		{
			$where .= "  AND (bpr.bkg_is_gozonow = $gnowType) ";
		}
		if (is_array($bkgCreateType) && count($bkgCreateType) > 0)
		{
			$bkgCreateTypeStr	 = implode(',', $bkgCreateType);
			$where				 .= "  AND (btr.bkg_create_user_type IN ($bkgCreateTypeStr)) ";
		}

		if (is_array($vndSelected) && count($vndSelected) > 0)
		{
			$vndSelectedStr	 = implode(',', $vndSelected);
			$where			 .= "  AND ( bvr_is_preferred_vendor IN ($vndSelectedStr)) ";
		}

		if (is_array($transferzSelected) && count($transferzSelected) > 0)
		{
			$transferzSelectedStr	 = implode(',', $transferzSelected);
			$where			 .= "  AND ( bkg1.bkg_agent_id IN ($transferzSelectedStr)) ";
		}

		skipAll:
		$sql	 = "SELECT count(bcb.bcb_id) tripCount , 
				sum(nl.totalSent) totalSent, 
				sum(isReceived) isReceived, 
				sum(isRead) isRead 
				FROM  booking bkg1
                INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg1.bkg_bcb_id		
				INNER JOIN booking_pref bpr ON bkg1.bkg_id = bpr.bpr_bkg_id  AND bpr.bkg_is_gozonow IN (1,2)
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg1.bkg_id 
				INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg1.bkg_id  
                LEFT JOIN  
				(
					SELECT ntl.ntl_ref_id tripId, ntl.ntl_id,
					COUNT(DISTINCT ntl_id) totalSent,
					SUM(IF(ntl.ntl_status>0,1,0)) delivered, 
					SUM(IF(ntl.ntl_is_read IN (1,2),1,0)) isReceived ,
					SUM(IF(ntl.ntl_is_read =1,1,0)) isRead ,
					MAX(ntl.ntl_created_on) ntl_sent_on
					from notification_log ntl 
					WHERE  1 $ntlWhere  AND  ntl.ntl_event_code=550 AND ntl.ntl_ref_type=3
					GROUP BY ntl.ntl_ref_id
				) as nl ON nl.tripId = bcb.bcb_id  			 
				 LEFT JOIN 
				(
					SELECT bvr.bvr_bcb_id tripId, 
					sum(if(bvr.bvr_bid_amount > 0 AND bvr.bvr_accepted=1,1,0)) gnowBid , 
					sum(if(bvr.bvr_bid_amount = 0 AND bvr.bvr_accepted=2,1,0)) bidDeny,
					sum(bvr.bvr_is_preferred_vendor) bvr_is_preferred_vendor , 
					GROUP_CONCAT(if(bvr.bvr_bid_amount > 0 AND bvr.bvr_accepted=1,bvr.bvr_bid_amount,NULL) SEPARATOR ',') bidAmts,
					MAX(bvr.bvr_created_at) bvr_created
					from booking_vendor_request bvr 
					WHERE bvr.bvr_is_gozonow =1 $bvrWhere 
					GROUP BY bvr.bvr_bcb_id
				) as bv ON bv.tripId=bcb.bcb_id  
				WHERE 1 $where
				 
				";
		$count	 = DBUtil::queryRow($sql, DBUtil::SDB());
		return $count;
//		 var_dump($count);
//		echo $sql;
//		exit;
	}

	public static function getNotificationIntStatus($vendorId, $day, $type)
	{

		$params	 = ['vndId' => $vendorId, 'days' => $day, 'types' => $type];
		$sql	 = "SELECT  count(ntl_id)as counter FROM notification_log WHERE ntl_entity_id=:vndId AND ntl_status =1 AND ntl_event_code = :types  AND ntl_created_on BETWEEN (NOW() - INTERVAL :days DAY) AND NOW() ";

		$cnt = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $cnt;
	}

	public static function createVendorMultiLog($vndArr, $param, $batchId = '')
	{

		$eventCode = $param['notifications']['EventCode'];

		$userType	 = UserInfo::TYPE_VENDOR;
		$type		 = NotificationLog::entityTypeMapping($userType);
		$refVal		 = NotificationLog::getRefIdByPayLoad($param);
		foreach ($vndArr as $entityId)
		{
			$aptId = AppTokens::getIdByEntity($entityId, $userType);

			$notificationLog = new NotificationLog();

			$notificationLog->ntl_entity_type	 = $type;
			$notificationLog->ntl_entity_id		 = $entityId;
			$notificationLog->ntl_title			 = $param['notifications']['title'];
			$notificationLog->ntl_message		 = ($param['notifications']['message'] != '') ? $param['notifications']['message'] : $param['notifications']['body'];

			$notificationLog->ntl_payload	 = json_encode($param, JSON_UNESCAPED_SLASHES);
			$notificationLog->ntl_event_code = $eventCode;
			$notificationLog->ntl_ref_type	 = $refVal[1];

			$notificationLog->ntl_ref_id	 = $refVal[0];
			$notificationLog->ntl_apt_ids	 = json_encode($aptId);
			$notificationLog->ntl_created_on = new CDbExpression('NOW()');
			$notificationLog->ntl_status	 = 0;
			if ($batchId != '')
			{
				$notificationLog->ntl_batch_id = $batchId;
			}

			if ($notificationLog->validate())
			{

				$notificationLog->save();
			}
		}
	}

	/**
	 * 
	 * @param type $batchId
	 * @param type $vndId
	 * @return type
	 */
	public static function getIdByBatchIdVendorId($batchId, $vndId)
	{
		$params	 = ['batchId' => $batchId, 'vndId' => $vndId];
		$sql	 = "SELECT ntl.ntl_id  FROM  notification_log ntl  
		WHERE ntl.ntl_batch_id = :batchId AND ntl.ntl_entity_id=:vndId  
		AND ntl.ntl_entity_type=2 AND ntl.ntl_event_code IN (550,551) 
		AND ntl.ntl_ref_type=3 ORDER BY ntl_created_on DESC";
		$result	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $result;
	}

	/**
	 *  
	 * @param type $vndId
	 * @return type
	 */
	public static function getIdByTripVendorEvent($tripId, $vndId, $eventId)
	{
		$params	 = ['tripId' => $tripId, 'vndId' => $vndId, 'eventId' => $eventId];
		$sql	 = "SELECT ntl.ntl_id  FROM  notification_log ntl  
		WHERE ntl.ntl_ref_id=:tripId AND ntl.ntl_entity_id=:vndId  
		AND ntl.ntl_entity_type=2 AND ntl.ntl_event_code =:eventId 
		AND ntl.ntl_ref_type=3 ORDER BY ntl_created_on DESC";
		$result	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $result;
	}

	public static function getIdByDriverEvent($refVal, $entityId, $eventId)
	{
		$refId	 = $refVal[0];
		$refType = $refVal[1];
		$params	 = ['refId' => $refId, 'refType' => $refType, 'drvId' => $entityId, 'eventId' => $eventId];
		$sql	 = "SELECT ntl.ntl_id  FROM  notification_log ntl  
		WHERE ntl.ntl_ref_id=:refId AND ntl.ntl_ref_type=:refType 
		AND ntl.ntl_entity_id=:drvId  
		AND ntl.ntl_entity_type=3 AND ntl.ntl_event_code =:eventId 
		 ORDER BY ntl_created_on DESC";
		$result	 = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
		return $result;
	}

	//SELECT  DATE_SUB(now(), INTERVAL 30 MINUTE)

	/**
	 * function used to check notification send or not within specific time
	 * @param type $tripId
	 * @param type $vendorId
	 * @param type $eventCode
	 */
	public static function checkPrevNotificationCount($tripId, $vendorId, $eventCode)
	{
		$cnt	 = 0;
		$params	 = ['vndId' => $vendorId, 'tripId' => $tripId, 'eventCode' => $eventCode, 'time' => $time];
		$sql	 = "SELECT count(ntl_id) as counter FROM notification_log WHERE ntl_entity_id=:vndId  AND ntl_ref_id =:tripId AND  ntl_status =1 AND ntl_event_code = :eventCode  AND ntl_created_on BETWEEN (DATE_SUB(now(), INTERVAL :time MINUTE)) AND NOW() ";
		$cnt	 = DBUtil::command($sql, DBUtil::SDB())->queryScalar($params);
		return $cnt;
	}
	/**
	 * function used to check read vendor according to trip id
	 * @param type $bcbId
	 * @return type
	 */
	public static function getReadVendor($bcbId)
	{
		$params	 = ['bcbId' => $bcbId, 'isread' => 1, 'eventCode' => 550, 'reftype' =>3];
		$sql	 = "SELECT  GROUP_CONCAT(DISTINCT ntl_entity_id) vndIds
					FROM notification_log
					WHERE ntl_ref_id =:bcbId AND ntl_is_read =:isread AND ntl_event_code =:eventCode AND ntl_ref_type =:reftype
					GROUP BY ntl_ref_id";
		$rows	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $rows;
	}
}
