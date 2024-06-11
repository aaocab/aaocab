<?php

/**
 * This is the model class for table "app_tokens".
 *
 * The followings are the available columns in table 'app_tokens':
 * @property string $apt_id
 * @property string $apt_user_id
 * @property string $apt_token_id
 * @property string $apt_device
 * @property string $apt_device_uuid
 * @property string $apt_date
 * @property string $apt_last_login
 * @property integer $apt_status
 * @property String $apt_apk_version
 * @property String $apt_os_version
 * @property String $apt_platform
 * @property String $apt_last_loc_lat
 * @property String $apt_last_loc_long
 * @property String $apt_device_token
 * @property String $apt_entity_id
 */
class AppTokens extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_tokens';
	}

	const CODE_CHAT_MESSAGE	 = 522;
	const Platform_Android	 = 3;
	const Platform_Ios		 = 4;
	const Platform_Driver		 = 5;
	const Platform_DCO		 = 7;

	public $apt_last_login1;
	public $apt_last_login2;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apt_user_id, apt_token_id, apt_device', 'required', 'on' => 'insert'),
			array('apt_status', 'numerical', 'integerOnly' => true),
			array('apt_user_id', 'length', 'max' => 11),
			//array('apt_token_id', 'length', 'max' => 200),
			array('apt_device, apt_device_uuid', 'length', 'max' => 255),
			array('apt_last_login', 'safe'),
			array('apt_device_token', 'required', 'on' => 'fcm'),
			array('apt_token_id, apt_device_token', 'required', 'on' => 'updateFcm'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('apt_id,apt_apk_version,apt_os_version, apt_user_id, apt_token_id, apt_device, apt_device_uuid,apt_platform, apt_date, apt_last_loc_lat, apt_last_loc_long,apt_last_login, apt_status,apt_logout,apt_ip_address,apt_apn_id', 'safe'),
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
			'apt_id'			 => 'Apt',
			'apt_user_id'		 => 'User',
			'apt_token_id'		 => 'Token',
			'apt_device'		 => 'Device',
			'apt_device_uuid'	 => 'Device Uuid',
			'apt_date'			 => 'Date',
			'apt_last_login'	 => 'Last Login',
			'apt_status'		 => '1:active;0:deleted',
			'apt_apk_version'	 => 'version',
			'apt_os_version'	 => 'os version',
			'apt_apn_id'		 => 'Device Token'
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

		$criteria->compare('apt_id', $this->apt_id, true);
		$criteria->compare('apt_user_id', $this->apt_user_id, true);
		$criteria->compare('apt_token_id', $this->apt_token_id, true);
		$criteria->compare('apt_device', $this->apt_device, true);
		$criteria->compare('apt_device_uuid', $this->apt_device_uuid, true);
		$criteria->compare('apt_date', $this->apt_date, true);
		$criteria->compare('apt_last_login', $this->apt_last_login, true);
		$criteria->compare('apt_status', $this->apt_status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppTokens the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function appDetails($executive_id)
	{
		$model = self::model()->find('apt_user_id=:id AND apt_status=:status', array('id' => $executive_id, 'status' => 1));
		return $model;
	}

	/** @return AppTokens  */
	public function getByToken($token)
	{
		return self::model()->find('apt_token_id=:token AND apt_status=1', array('token' => $token));
	}

	public function listapps()
	{
		$criteria			 = new CDbCriteria;
		$criteria->select	 = ['*'];

		$criteria->order = 'apt_id DESC';
		$dataProvider	 = new CActiveDataProvider($this, array('criteria' => $criteria, 'sort' => $sort, 'pagination' => array('pageSize' => 2000)));
		return $dataProvider;
	}

	public function getAppValidations($data, $id)
	{
		$success	 = false;
		$apptoken	 = AppTokens::model()->find('apt_user_id=:userID AND apt_token_id=:sessionID AND apt_status=1', array('userID' => $id, 'sessionID' => $data['apt_token_id']));
		if ($apptoken != '')
		{
			$apptoken->attributes = $data;
			if ($apptoken->validate())
			{
				try
				{
					$success = $apptoken->update();
				}
				catch (Exception $e)
				{
					$success = false;
					$apptoken->addError('apt_id', $e->getMessage());
				}
			}
		}
		return $success;
	}

	/**
	 * 
	 * @param type $entity
	 * @param type $platformType
	 * @return string
	 */
	public static function getMinimumAppVersion($entity, $platformType = 0)
	{
		$entityType = 'consumer';
		switch ($entity)
		{
			case 1:
				$entityType	 = 'consumer';
				break;
			case 2:
				$entityType	 = 'vendor';
				break;
			case 5:
				$entityType	 = 'driver';
				break;
			case 6:
				$entityType	 = 'ops';
				break;
			case 7:
				$entityType	 = 'dco';
				break;
			default:
				break;
		}
		return Yii::app()->params['checkVersion'][$entityType][$platformType];
	}

	/**
	 * 
	 * @param type $token
	 * @param type $existingVersion
	 * @param type $userId
	 * @return boolean
	 */
	public static function verify($token, $existingVersion, $userId, $activeVersion)
	{
		$success		 = true;
		$versionCheck	 = false;
		$sessionCheck	 = false;
		$result			 = [];
		$message		 = 'Validation Done';
		$isVersionCheck	 = self::validateVersion($existingVersion, $activeVersion);
		if (!$isVersionCheck)
		{
			$message = "Invalid version.";
			$success = false;
		}
		$versionCheck	 = true;
		$result			 = ['success'		 => $success,
			'message'		 => $message,
			'versionCheck'	 => $versionCheck,
			'sessionCheck'	 => $sessionCheck];
		if (!$success)
		{
			return $result;
		};

		$isValidate = self::validateToken($token);
		if (!$isValidate)
		{
			$message = "Unauthorized user.";
			$success = false;
		}
		$sessionCheck	 = true;
		$result			 = ['success'		 => $success,
			'message'		 => $message,
			'versionCheck'	 => $versionCheck,
			'sessionCheck'	 => $sessionCheck];
		if (!$success)
		{
			return $result;
		};
		/* @var $result AppTokens */
		$result = self::validatePlatform($userId, $token);
		if (!$result)
		{
			$message = "Unauthorized Platform.";
			$success = false;
		}
		$result = ['success'		 => $success,
			'message'		 => $message,
			'versionCheck'	 => $versionCheck,
			'sessionCheck'	 => $sessionCheck];
		return $result;
	}

	/**
	 * 
	 * @param type $token
	 * @param type $existingVersion
	 * @param type $userId
	 * @return boolean
	 */
	public static function verifiApp($userModel, $userId, $activeVersion)
	{
		$token			 = $userModel->apt_token_id;
		$existingVersion = $userModel->apt_apk_version;
		#echo $existingVersion.'<br>'.$activeVersion.'<br>';exit;
		$success		 = true;
		$versionCheck	 = false;
		$sessionCheck	 = false;
		$result			 = [];
		$message		 = 'Validation Done';
		$isVersionCheck	 = self::validateVersion($existingVersion, $activeVersion);

		if (!$isVersionCheck)
		{
			$message = "Invalid version.";
			$success = false;
		}
		$versionCheck	 = true;
		$result			 = ['success'		 => $success,
			'message'		 => $message,
			'versionCheck'	 => $versionCheck,
			'sessionCheck'	 => $sessionCheck];

		if (!$success)
		{
			return $result;
		};

		$isValidate = self::validateToken($token);

		if (!$isValidate)
		{
			$message = "Unauthorized user.";
			$success = false;
		}
		$sessionCheck	 = true;
		$result			 = ['success'		 => $success,
			'message'		 => $message,
			'versionCheck'	 => $versionCheck,
			'sessionCheck'	 => $sessionCheck];

		if (!$success)
		{
			return $result;
		};
		/* @var $result AppTokens */

		$result = self::validatePlatform($userId, $token);
		if (!$result)
		{
			$message = "Unauthorized Platform.";
			$success = false;
		}

		$result = ['success'		 => $success,
			'message'		 => $message,
			'versionCheck'	 => $versionCheck,
			'sessionCheck'	 => $sessionCheck];
		#print_r($result);

		return $result;
	}

	public function getAppMultiLoginStatus($driverId)
	{
		$success	 = false;
		$apptoken	 = AppTokens::model()->find('apt_entity_id=:drvID AND apt_status=1', array('drvID' => $driverId));
		if ($apptoken != '')
		{
			if (count($apptoken) > 0)
			{
				$sql	 = "UPDATE `app_tokens` SET `apt_status`=0 WHERE `apt_entity_id`= $driverId   AND `apt_user_type`= 5 AND `apt_status`= 1";
				$result	 = DBUtil::command($sql)->execute();
				$success = ($result > 0) ? true : false;
			}
			else
			{
				$success = false;
			}
		}
		return $success;
	}

	public function getArrayByOS($aptModels)
	{
		$arrApn = array();
		foreach ($aptModels as $aptModel)
		{
			$arrApn[1][$aptModel->apt_user_type][] = $aptModel->apt_device_token; //$arrApn[$aptModel->apt_os_type][$aptModel->apt_user_type][]
		}
		Yii::log('push notifiaction os type and user type', CLogger::LEVEL_INFO);
		return array_filter($arrApn);
	}

	public function sendNotifications($aptModels, $message, $param)
	{
		if (!Yii::app()->params['sendAppNotification'])
		{
			return true;
		}
		$arrApn		 = $this->getArrayByOS($aptModels);
		$apnGcm		 = Yii::app()->gcm;
		$apnsGcm	 = Yii::app()->apnsGcm;
		$apnsGcmUser = Yii::app()->apnsGcmUser;
		$result		 = [];
		/* @var $apnGcm YiiApnsGcm */
		/* @var $apnGcmUser YiiApnsGcm */
		if ($aptModels)
		{
			$logId										 = NotificationLog::createLog($aptModels, $param);
			$param['notifications']['notificationId']	 = (int) $logId;
		}
		if (count($arrApn[1][6]) > 0)
		{
			Logger::create('push notification just before send: (' . $message . ')', CLogger::LEVEL_TRACE);
			$result['fcm'] = FCM::send($arrApn[1][6], $message, $param);
			//	$result['gcm'] = $apnsGcmUser->sendMulti(YiiApnsGcm::TYPE_GCM, $arrApn[1][1], $message, $param);
			Logger::create('push notification just after send' . json_encode($result['fcm']), CLogger::LEVEL_TRACE);
		}
		if (count($arrApn[1][5]) > 0)
		{
			Logger::create('push notification just before send: (' . $message . ')', CLogger::LEVEL_TRACE);
			//$result['gcm'] = $apnsGcm->sendMulti(YiiApnsGcm::TYPE_GCM, $arrApn[1][5], $message, $param);
			$result['fcm'] = FCM::send($arrApn[1][5], $message, $param);
			Logger::create('push notification just after send' . json_encode($result['fcm']), CLogger::LEVEL_TRACE);
		}
		if (count($arrApn[1][2]) > 0)
		{
			Logger::create('push notification just before send: (' . $message . ')', CLogger::LEVEL_TRACE);
			//$result['gcm'] = $apnsGcm->sendMulti(YiiApnsGcm::TYPE_GCM, $arrApn[1][2], $message, $param);
			$result['fcm'] = FCM::send($arrApn[1][2], $message, $param);
			Logger::create('push notification just after send' . json_encode($result['fcm']), CLogger::LEVEL_TRACE);
		}
		if (count($arrApn[1][1]) > 0)
		{
			Logger::create('push notification just before send: (' . $message . ')', CLogger::LEVEL_TRACE);
			$result['fcm'] = FCM::send($arrApn[1][1], $message, $param);
			//	$result['gcm'] = $apnsGcmUser->sendMulti(YiiApnsGcm::TYPE_GCM, $arrApn[1][1], $message, $param);
			Logger::create('push notification just after send' . json_encode($result['fcm']), CLogger::LEVEL_TRACE);
		}

		if ($result)
		{
			NotificationLog::createLog($aptModels, $param, $result, $logId);
		}

		return $result;
	}

	public function notifyVendorBookingRequest($vendor_id, $bcb_id)
	{
		$cabModel	 = BookingCab::model()->findByPk($bcb_id);
		$payLoadData = ['tripId' => $cabModel->bcb_id, 'EventCode' => Booking::CODE_VENDOR_BOOKING_REQUEST];
		$success	 = $this->notifyVendor($vendor_id, $payLoadData, "A new booking has been requested", "A new booking has been requested");
		return $success;
	}

	public function notifyVendorBookingRequestOnce($vendor_id)
	{
		$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BOOKING_REQUEST];
		$success	 = $this->notifyVendor($vendor_id, $payLoadData, "A new booking has been requested", "A new booking has been requested");
		return $success;
	}

	public function notifyVendor($vendor_id, $data, $message, $title, $isDriverPending = false, $logginDayCount = 0)
	{
		$daysCount		 = ($logginDayCount > 0) ? $logginDayCount : 5;
		AppTokens::$db	 = DBUtil::SDB();
		$appTokenModel	 = AppTokens::model()->findAll("apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL '$daysCount' DAY) AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type", ['status' => 1, 'type' => 2, 'id' => $vendor_id]);
		AppTokens::$db	 = DBUtil::MDB();
		if ($isDriverPending == true)
		{
			return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'tripId' => $data['tripId'], 'EventCode' => $data['EventCode'], 'filterCode' => $data['FilterCode'], 'Status' => $data['Status'], 'message' => $message, 'icon' => '@drawable/logo', 'sound' => 'default']]);
		}
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'tripId' => $data['tripId'], 'EventCode' => $data['EventCode'], 'filterCode' => $data['FilterCode'], 'Status' => $data['Status'], 'message' => $message, 'icon' => '@drawable/logo', 'sound' => 'default']]);

		//$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 5 DAY) AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 2, 'id' => $vendor_id]);
	}

	public function notifyVendorChat($vendor_id, $data, $message, $title, $chat_args)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$appTokenModel	 = AppTokens::model()->findAll('apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 1 DAY) AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 2, 'id' => $vendor_id]);
		Logger::trace("vendorId: " . $vendor_id);
		$return			 = AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'message' => $message, 'bkg_id' => $chat_args['bkg_id'], 'EventCode' => $chat_args['event_code'], 'message' => $chat_args['chl_msg'], 'display_name' => $chat_args['display_name'], 'ref_name' => $chat_args['ref_name'], 'ref_type' => $chat_args['ref_type'], 'chl_created' => $chat_args['chl_created'], 'cht_id' => $chat_args['cht_id'], 'chl_id' => $chat_args['chl_id'], 'icon' => '@drawable/logo', 'sound' => 'default']]);
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $return;
	}

	public function notifyDriver($driver_id, $data, $notificationId, $message, $image, $title, $bkgID = 0, $logginDayCount = 0)
	{
		/* @var $appTokenModel AppTokens */
		$daysCount		 = ($logginDayCount > 0) ? $logginDayCount : 20;
		$appTokenModel	 = AppTokens::model()->findAll("apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL '$daysCount' DAY) AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type", ['status' => 1, 'type' => 5, 'id' => $driver_id]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'Booking Id' => $bkgID, 'notificationId' => $notificationId, 'EventCode' => $data['EventCode'], 'message' => $message, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyDriverChat($driver_id, $data, $message, $title, $bookingId)
	{
		/* @var $appTokenModel AppTokens */
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 5, 'id' => $driver_id]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'message' => $message, 'bookingId' => $bookingId, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyVendorRegister($token, $username)
	{
		$data			 = ['username' => $username, 'EventCode' => Booking::CODE_VENDOR_REGISTER];
		$title			 = 'Your gozo account has been approved';
		$message		 = 'Your gozo account has been approved';
		$appTokenModel	 = AppTokens::model()->findAll('apt_status=:status AND apt_device_token=:token AND apt_user_type=:type', ['status' => 1, 'type' => 2, 'token' => $token]);
		AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'message' => $message, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyConsumer($user, $data, $notificationId, $message, $title, $image = '')
	{
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 1, 'id' => $user]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'bookingId' => $data['bookingId'], 'notificationId' => $notificationId, 'EventCode' => $data['EventCode'], 'message' => $message, 'image' => $image, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyNonLoggedConsumer($aptId, $data, $notificationId, $message, $title, $image = '')
	{
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 1, 'id' => $aptId]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'bookingId' => $data['bookingId'], 'notificationId' => $notificationId, 'EventCode' => $data['EventCode'], 'message' => $message, 'image' => $image, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyAdmin($user, $data, $notificationId, $message, $title, $image = '')
	{
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 6, 'id' => $user]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'bookingId' => $data['bookingId'], 'notificationId' => $notificationId, 'EventCode' => $data['EventCode'], 'message' => $message, 'image' => $image, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyConsumerChat($user, $data, $message, $title, $bookingId)
	{
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 1, 'id' => $user]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'message' => $message, 'bookingId' => $bookingId, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function getByUserTypeAndUserId($user_id, $user_type)
	{
		$sql = "SELECT * FROM `app_tokens` WHERE `apt_user_id` = $user_id AND `apt_user_type` = $user_type AND `apt_status` = 1 ORDER BY `apt_id` DESC LIMIT 0,1";

		return DBUtil::queryRow($sql);
	}

	public function getAllByUserTypeAndUserId($user_id, $user_type)
	{
		$sql = "SELECT * FROM `app_tokens` WHERE `apt_user_id` = $user_id AND `apt_user_type` = $user_type AND `apt_status` = 1 ORDER BY `apt_id` DESC LIMIT 0,1";
		return DBUtil::queryAll($sql);
	}

	/**
	 * 
	 * @param integer $user_id
	 * @param integer $user_type
	 * @return boolean
	 */
	public static function deactivateByUserIdandUserType($user_id, $user_type)
	{
		if ($user_id > 0 && $user_type > 0)
		{
			$params	 = ['userId' => $user_id, 'userType' => $user_type];
			$sql	 = "UPDATE `app_tokens` set apt_status = 0,apt_logout=NOW() WHERE `apt_user_id` = :userId AND `apt_user_type` = :userType AND `apt_status` = 1 ";
			$rows	 = DBUtil::execute($sql, $params);
			return $rows;
		}
		return false;
	}

	/**
	 * @param integer $entity_id
	 * @param integer $entity_type
	 * @return boolean
	 */
	public static function deactivateByEntityIdandEntityType($entity_id, $entity_type)
	{
		if ($entity_id > 0 && $entity_type > 0)
		{
			$params	 = ['entityId' => $entity_id, 'userType' => $entity_type];
			$sql	 = "UPDATE `app_tokens` set apt_status = 0,apt_logout=NOW() WHERE `apt_entity_id` = :entityId AND `apt_user_type` = :userType AND `apt_status` = 1 ";
			$rows	 = DBUtil::execute($sql, $params);
			return $rows;
		}
		return false;
	}

	public function getByTokenId($token_id, $user_type)
	{
		$sql = "SELECT * FROM `app_tokens` WHERE `apt_token_id` = '$token_id' AND `apt_user_type` = $user_type AND `apt_status` = 1 ORDER BY `apt_id` DESC LIMIT 0,1";
		return DBUtil::queryRow($sql);
	}

	public function getAppToken($device_id)
	{
		return self::model()->findAll('apt_device_uuid=:device and apt_user_type=:type', array('device' => $device_id, 'type' => 5));
	}

	public function getActiveAppTokenByDeviceUser($device_id, $user)
	{
		return self::model()->findAll('apt_device_uuid=:device and apt_user_type=:type  and apt_status=1 ', array('device' => $device_id, 'type' => $user));
	}

	public function checkVendorLastLogin($vnd_id)
	{
		$sql = "SELECT IF(cnt>0,1,0) as is_last_login FROM
                (
                    SELECT COUNT(1) as cnt FROM `app_tokens` WHERE app_tokens.apt_user_id='.$vnd_id.' AND app_tokens.apt_user_type=2
                    AND app_tokens.apt_last_login >= DATE_SUB(NOW(),INTERVAL 36 HOUR)
                    ORDER BY app_tokens.apt_id DESC
                ) a";
		return DBUtil::queryScalar($sql);
	}

	/**
	 * 
	 * @param \UserInfo $usr
	 * @param AppTokens $device
	 * @param String $activeVersion
	 * @param integer $platform
	 * @return AppTokens
	 * @throws Exception
	 */
	public static function registerToken(\UserInfo $usr, $device = null, $activeVersion = null, $platform = 0)
	{//using in DCO and conApp
		$userInfo	 = UserInfo::getInstance();
		$model		 = new AppTokens();
		if ($device instanceof AppTokens)
		{
			$model = $device;
		}
		if (!$model->apt_token_id)
		{
			$tokenId				 = self::generateAuthToken($userInfo->userId, $model->apt_device, $model->apt_device_uuid, $model->apt_apk_version);
			$model->apt_token_id	 = md5($tokenId);
			$model->apt_user_type	 = ($usr->getUserType() == null) ? 10 : $usr->getUserType();
			$model->apt_user_id		 = $usr->getUserId();
			$model->apt_ip_address	 = \Filter::getUserIP();
		}
		else
		{
			$model = self::validateToken($model->apt_token_id);
			if ($usr->getEntityId() > 0)
			{
				$model->apt_entity_id = $usr->getEntityId();
			}
			$model->apt_user_id		 = $usr->getUserId();
			$model->apt_last_login	 = new CDbExpression('NOW()');
			$model->apt_apk_version	 = $device->apt_apk_version;
		}

		if ($platform > 0)
		{
			$model->apt_platform = (int) $platform;
		}

		if ($activeVersion != null)
		{
			$isVersionCheck = self::validateVersion($model->apt_apk_version, $activeVersion);
			if (!$isVersionCheck)
			{
				goto endRegisterToken;
			}
		}
		self::removeDuplicateDevice($model->apt_device_uuid, $model->apt_device_token, $model->apt_user_type, $usr->getUserId());

		if (!$model->save())
		{
			throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		endRegisterToken:
		return $model;
	}

	/**
	 * 
	 * @param GWebUser $user
	 * @param AppTokens $device
	 * @return \AppTokens
	 * @throws Exception
	 */
	public static function Register(GWebUser $user, $device = null)
	{//not any where
		$model = new AppTokens();
		if ($device instanceof AppTokens)
		{
			$model = $device;
		}
		$tokenId = $model->apt_token_id;
		if (!$tokenId)
		{
			$tokenId					 = self::generateAuthToken($user->getId(), $model->apt_device, $model->apt_device_uuid, $model->apt_apk_version);
			$apkModel					 = new AppTokens();
			$apkModel->apt_device_uuid	 = $model->apt_device_uuid;
			$apkModel->apt_device_token	 = $model->apt_device_token;
			$apkModel->apt_device		 = $model->apt_device;
			$apkModel->apt_apk_version	 = $model->apt_apk_version;
			$apkModel->apt_token_id		 = $tokenId;
			$apkModel->apt_user_type	 = $user->getUserType();
			$apkModel->apt_user_id		 = $user->getId();
			$apkModel->apt_ip_address	 = \Filter::getUserIP();
		}
		else
		{
			$apkModel					 = self::validateToken($tokenId);
			$apkModel->apt_entity_id	 = $user->getEntityID();
			$apkModel->apt_last_login	 = new CDbExpression('NOW()');
		}

		self::removeDuplicateDevice($apkModel->apt_device_uuid, $apkModel->apt_device_token, $user->getUserType(), $user->getId());
		if (!$apkModel->save())
		{
			throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		return $apkModel;
	}

	public static function addToken($entityId, $deviceData)
	{//vendor app
		$returnSet = new ReturnSet();
		try
		{
			$model					 = new AppTokens();
			$model->apt_entity_id	 = $entityId;
			$model->apt_user_type	 = 2;
			if ($deviceData instanceof \Stub\common\Platform)
			{
				$appTokenModel			 = $deviceData->getAppToken();
				$model->apt_device		 = $appTokenModel->apt_device;
				$model->apt_device_uuid	 = $appTokenModel->apt_device_uuid;
				$model->apt_apk_version	 = $appTokenModel->apt_apk_version;
				$model->apt_os_version	 = $appTokenModel->apt_os_version;
				$model->apt_device_token = $appTokenModel->apt_device_token;
			}

			$tokenId				 = Yii::app()->getSession()->getSessionId();
			$model->apt_token_id	 = $tokenId;
			$model->apt_ip_address	 = \Filter::getUserIP();
			$model->apt_last_login	 = new CDbExpression('NOW()');
//			self::removeDuplicateDevice($model->apt_device_uuid, $model->apt_device_token, $model->apt_user_type);

			$model->scenario = ($checkFcm == true) ? 'fcm' : '';

			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$returnSet->setStatus(true);
			$returnSet->setData($tokenId);
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param integer $userType
	 * @param integer $entityId
	 * @param \Stub\common\Platform $deviceData
	 * @return \AppTokens
	 */
	public static function Add($userId, $userType, $entityId = null, $deviceData = [], $checkFcm = false)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			//$sessionId				 = Yii::app()->getSession()->getSessionId();
			$model					 = new AppTokens();
			$model->apt_user_id		 = $userId;
			$model->apt_user_type	 = $userType;
			$model->apt_entity_id	 = $entityId;
			//$model->apt_token_id	 = $sessionId;
			if ($deviceData instanceof \Stub\common\Platform || $deviceData instanceof \Beans\common\DeviceInfo)
			{
				//$deviceData->getAppToken();
				$appTokenModel			 = $deviceData->getAppToken();
				$model->apt_device		 = $appTokenModel->apt_device;
				$model->apt_device_uuid	 = $appTokenModel->apt_device_uuid;
				$model->apt_apk_version	 = $appTokenModel->apt_apk_version;
				$model->apt_os_version	 = $appTokenModel->apt_os_version;
				$model->apt_device_token = $appTokenModel->apt_device_token;
			}
			else
			{
				$model->apt_device		 = $deviceData['device_info'];
				$model->apt_device_uuid	 = $deviceData['device_id'];
				$model->apt_apk_version	 = $deviceData['apk_version'];
				$model->apt_os_version	 = $deviceData['os_version'];
				$model->apt_device_token = $deviceData['apt_device_token'];
			}
			//$tokenId				 = self::generateAuthToken($userId, $model->apt_device, $model->apt_device_uuid, $model->apt_apk_version);
			$tokenId				 = Yii::app()->getSession()->getSessionId();
			$model->apt_token_id	 = $tokenId;
			$model->apt_ip_address	 = \Filter::getUserIP();
			$model->apt_last_login	 = new CDbExpression('NOW()');

			$model->scenario = ($checkFcm == true) ? 'fcm' : '';
			if ($model->save())
			{
				DBUtil::commitTransaction($transaction);
				return $model;
			}
			else
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			self::removeDuplicateDevice($model->apt_device_uuid, $model->apt_device_token, $model->apt_user_type, $userId);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($ex);
			return false;
		}
	}

	/**
	 * 
	 * @param String $deviceId
	 * @param String $token
	 * Changes by Puja
	 */
	/* public static function removeDuplicateDevice($deviceId, $token,$type=0)
	  {
	  if ($type == 5)
	  {
	  $appToken = AppTokens::model()->findAll(' apt_user_type:=type AND (apt_device_uuid=:device OR apt_device_token=:deviceToken)', array('type' => $type, 'device' => $deviceId, 'deviceToken' => $token));
	  //$appToken = AppTokens::model()->findAll('apt_device_uuid=:device OR apt_device_token=:deviceToken', array('device' => $deviceId, 'deviceToken' => $token));
	  foreach ($appToken as $app)
	  {
	  $app->apt_status = 0;
	  $app->update();
	  }
	  }
	  } */

	public static function removeDuplicateConsumerDevice($deviceId, $token, $userType = 1, $userId = null)
	{
		return 0;
	}

	public static function removeDuplicateDCODevice($deviceId, $token, $userType = 2, $userId = null)
	{

		$contactData = ContactProfile::getEntitybyUserId($userId);

		$vndId	 = $contactData['cr_is_vendor'];
		$drvId	 = $contactData['cr_is_driver'];
		if ($vndId > 0)
		{
			$userType	 = UserInfo::TYPE_VENDOR;
			$succ1		 = self::removeDuplicateVendorDevice($deviceId, $token, $userType, $userId);
		}
		if ($drvId > 0)
		{
			$userType		 = UserInfo::TYPE_DRIVER;
			$succ2			 = self::removeDuplicateDriverDevice($deviceId, $token, $userType, $userId);
			$userTypeVendor	 = UserInfo::TYPE_VENDOR;
			$succ3			 = self::removeDuplicateSession($deviceId, $token, $userTypeVendor, $userId, AppTokens::Platform_DCO);
		}
		return true;
	}

	public static function removeDuplicateDriverDevice($deviceId, $token, $userType, $userId = null)
	{
		$userType = '3,5';
		return self::removeDuplicateSession($deviceId, $token, $userType, $userId);
	}

	public static function removeDuplicateVendorDevice($deviceId, $token, $userType, $userId = null)
	{
		return 0;
	}

	/*
	 * Changes by Abhishek/ Roy
	 */

	public static function removeDuplicateDevice($deviceId, $token, $userType = UserInfo::TYPE_CONSUMER, $userId = null)
	{
		if ($userId == null || $userId == 0)
		{
			return;
		}
		elseif ($userType == UserInfo::TYPE_CONSUMER)
		{
			return self::removeDuplicateConsumerDevice($deviceId, $token, $userType, $userId);
		}
		elseif ($userType == UserInfo::TYPE_VENDOR)
		{
			return self::removeDuplicateDCODevice($deviceId, $token, $userType, $userId);
		}
		elseif (in_array($userType, [3, 5]))
		{
			return self::removeDuplicateDCODevice($deviceId, $token, $userType, $userId);
		}
		else
		{
			return self::removeDuplicateSession($deviceId, $token, $userType, $userId);
		}
	}

	public static function removeDuplicateSession($deviceId, $token, $userType = UserInfo::TYPE_CONSUMER, $userId = null, $platform = null)
	{
		if ($userId == null || $userId == 0)
		{
			return;
		}
		if ($userType == 0)
		{
			return;
		}

		$params	 = [
			'userId'		 => $userId,
			'deviceToken'	 => $token,
			'deviceId'		 => $deviceId
		];
		$where	 = '';
		if ($platform > 0)
		{
			$params['platform']	 = $platform;
			$where				 = ' AND apt_platform=:platform';
		}

		$sql = "UPDATE app_tokens SET apt_status=0 WHERE apt_user_id=:userId 
				AND apt_status<>0 AND (apt_device_token<>:deviceToken OR apt_device_token IS NULL)
				AND apt_user_type IN ($userType) AND apt_device_uuid<>:deviceId $where";

		$numrows = DBUtil::execute($sql, $params);
		return $numrows;
	}

	/**
	 * 
	 * @param string $currentVersion   (using apt_apk_version )
	 * @param string $activeVersion
	 * @return boolean
	 */
	public function validateVersion($currentVersion, $activeVersion)
	{
		$success = false;
		if (version_compare($currentVersion, $activeVersion) >= 0)
		{
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param string $token
	 * @return array
	 */
	public static function verifyToken($token)
	{
		$success = true;
		/* @var $model AppTokens */
		$model	 = AppTokens::model()->getByToken($token);
		if (!$model)
		{
			$message = "Unauthorized Token.";
			$success = false;
		}
		$result = ['success' => $success, 'message' => $message];
		return $result;
	}

	/**
	 * 
	 * @param string $currentVersion
	 * @param string $activeVersion
	 * @return array
	 */
	public static function verifyVerison($currentVersion, $activeVersion)
	{
		$success = false;
		if (version_compare($currentVersion, $activeVersion) >= 0)
		{
			$success = true;
			$message = '';
		}
		else
		{
			$message = 'Invalid Version';
		}
		$result = ['success' => $success, 'message' => $message];
		return $result;
	}

	/**
	 * 
	 * @param string $token
	 * @return type
	 * @throws Exception
	 */
	public static function validateToken($token)
	{
		$appToken	 = false;
		$appToken	 = AppTokens::model()->find('apt_token_id = :token and apt_status = 1', array('token' => $token));

		if (!$appToken)
		{
			Logger::trace($token);
			Logger::warning("Unauthorised Token", true);
			throw new CHttpException(401, "Unauthorised token.", 401);
		}
		return $appToken;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param string $token
	 * @return type
	 * @throws Exception
	 */
	public static function validatePlatform($userId, $token)
	{
		$success	 = false;
		$appToken	 = AppTokens::model()->find('apt_user_id=:userID AND apt_token_id=:sessionID AND apt_status=1', array('userID' => $userId, 'sessionID' => $token));
		if (!$appToken)
		{
			throw new Exception("Unauthorised user. ", ReturnSet::ERROR_INVALID_DATA);
		}
		return $appToken;
	}

	/**
	 * 
	 * @param string $authToken
	 * @param string $fcmToken
	 * @return boolean
	 */
	public static function updateFcm($authToken, $fcmToken)
	{
		$success = true;
		$message = '';
		if (empty($authToken) && empty($fcmToken))
		{
			$success = false;
			goto resultSet;
		}
		/* @var $appModel AppTokens */
		$appModel = AppTokens::model()->find('apt_token_id = :token and apt_status = 1', array('token' => $authToken));
		if (!$appModel)
		{
			$message = ('Unauthorised token');
			$success = false;
			goto resultSet;
		}
		$appModel->apt_device_token	 = $fcmToken;
		$appModel->scenario			 = 'updateFcm';
		if (!$appModel->save())
		{
			$message = ('Unable to save data.');
			$success = false;
		}
		self::deactivateExpiredFCMToken($authToken, $fcmToken);

		resultSet:
		return ['success' => $success, 'message' => $message];
	}

	/**
	 * 
	 * @param string $app
	 * @return string
	 */
	public static function getVersionByApp($app)
	{
		if (!$app)
		{
			return false;
		}
		if (\AppTokens::Platform_Android == $app)
		{
			$activeVersion = Config::get("Version.Android.consumer"); // Yii::app()->params['versionCheck']['consumer'];
		}
		else if (\AppTokens::Platform_Ios == $app)
		{
			$activeVersion = Config::get("Version.Ios.consumer"); //Yii::app()->params['versionCheck']['consumerios'];
		}
		return $activeVersion;
	}

	/**
	 * 
	 * @param integer $userId
	 * @param string $deviceName
	 * @param string $deviceUniqueId
	 * @param string $deviceApkVersion
	 * @return boolean
	 */
	public static function generateAuthToken($userId = null, $deviceName = null, $deviceUniqueId, $deviceApkVersion = null)
	{
		if (empty($deviceName))
		{
			return false;
		}
		if (empty($deviceUniqueId))
		{
			return false;
		}
		if (empty($deviceApkVersion))
		{
			return false;
		}
		//This payload is being used for generating the auth token
		$payLoad = array(
			"userId"			 => $userId,
			"deviceInfo"		 => $deviceName,
			"deviceId"			 => $deviceUniqueId,
			"deviceOsVersion"	 => $deviceApkVersion
		);
		return Yii::app()->JWT->generateToken($payLoad);
	}

	public function checkToken($uniqueId)
	{
		$appToken = AppTokens::model()->findAll('apt_device_uuid=:device and apt_user_type=:type', array('device' => $uniqueId, 'type' => 5));
		foreach ($appToken as $app)
		{
			if (count($app) > 0)
			{
				$app->apt_status = 0;
				$app->update();
			}
		}
		return true;
	}

	/**
	 * @param \Stub\common\Platform $deviceData
	 * @return AppTokens 
	 */
	public function addLogin($driverId, $userId, $deviceData, $ipAddress, $token, $fcmToken)
	{
		Logger::create("driver temporaryLogin  driverId: {$driverId}, userId {$userId}, token {$token}", CLogger::LEVEL_INFO);
		$row = self::model()->getByTokenId($token, AppTokens::Platform_Driver);
		if ($row)
		{
			Logger::create("driver temporaryLogin  if getByTokenId  apt_id: " . $row['apt_id'], CLogger::LEVEL_INFO);
			$appTokenModel = AppTokens::model()->findByPk($row['apt_id']);
		}

		if (!$appTokenModel || $appTokenModel->apt_status == 0)
		{
			$appTokenModel					 = new AppTokens();
			$appTokenModel->apt_token_id	 = $token;
			$appTokenModel->apt_device		 = $deviceData->deviceName;
			$appTokenModel->apt_device_uuid	 = $deviceData->uniqueId;
			$appTokenModel->apt_os_version	 = $deviceData->osVersion;
			$appTokenModel->apt_status		 = 1;
		}

		$appTokenModel->apt_user_id		 = $userId;
		$appTokenModel->apt_entity_id	 = $driverId;
		$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
		$appTokenModel->apt_user_type	 = AppTokens::Platform_Driver;
		$appTokenModel->apt_apk_version	 = $deviceData->version;
		$appTokenModel->apt_ip_address	 = $ipAddress;
		if ($fcmToken != '')
		{
			$appTokenModel->apt_device_token = $fcmToken;
		}
		if (!$appTokenModel->save())
		{
			Logger::create("driver temporaryLogin  if apptoken not saved", CLogger::LEVEL_INFO);
			throw new Exception(json_encode($appTokenModel->getErrors()), ReturnSet::ERROR_FAILED);
		}
		return $appTokenModel;
	}

	public function getByDriverId($drvId, $date1, $date2, $user_type = 'driver')
	{
		$params		 = array('drvId' => $drvId);
		$pageSize	 = 25;
		if ($date1 != '' && $date2 != '')
		{
			$params['date1'] = $date1;
			$params['date2'] = $date2;
			$dateCond		 = " AND apt_last_login BETWEEN :date1  AND :date2";
		}
		$cond = '';
		if ($user_type == "driver")
		{
			$cond = ' AND `apt_entity_id` =:drvId AND apt_user_type  = 5';
		}
		else
		{
			$cond = ' AND `apt_user_id` =:drvId '; // AND apt_user_type  = 1
		}
		$sql			 = "SELECT *  FROM `app_tokens` WHERE 1=1 $cond  $dateCond";
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			"params"		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['apt_device', 'apt_last_login'],
				'defaultOrder'	 => 'apt_last_login  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	public function opsTimeOverLogOut($admId)
	{
		$success = false;
		$param	 = array('admId' => $admId);
		$sql	 = "SELECT * FROM app_tokens WHERE apt_user_type = 6 AND apt_user_id =:admId ORDER BY apt_id DESC LIMIT 0,1";
		$result	 = DBUtil::queryRow($sql, DBUtil::MDB(), $param);

		if ($result != '')
		{
			$model = AppTokens::model()->findByPk($result['apt_id']);
			if ($model->apt_status == 1)
			{
				$model->apt_status	 = 0;
				$model->apt_logout	 = Filter::getDBDateTime();
				if ($model->update())
				{
					$success = true;
				}
			}
		}
		return $success;
	}

	public function opsUserIds()
	{
		$sql = "SELECT DISTINCT(apt_user_id)  FROM app_tokens WHERE apt_user_type = 6 AND apt_last_login BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59') ORDER BY apt_id DESC";
		return DBUtil::queryAll($sql, DBUtil::MDB());
	}

	/*
	 * Changes all token status to zero, except current token
	 * 
	 * @param integer $entityId 
	 * @param string $token 
	 * @param integer $userType	 
	 */

	public static function logoutAllPreviousSessions($entityId, $token, $userType = 1)
	{
		$appToken = AppTokens::model()->findAll('apt_entity_id=:entityId AND apt_user_type=:userType AND apt_device_token<>:deviceToken',
				array('entityId' => $entityId, 'deviceToken' => $token, 'userType' => $userType));
		foreach ($appToken as $app)
		{
			$app->apt_status = 0;
			$app->update();
		}
	}

	/*
	 * Changes all token status to zero, except current token
	 * 
	 * @param integer $entityId 
	 * @param string $token 
	 * @param integer $userType	 
	 */

	public static function logoutSessionsForAuthToken($entityId, $token, $userType = 1)
	{
		$appToken = AppTokens::model()->findAll('apt_entity_id=:entityId AND apt_user_type=:userType AND apt_token_id<>:aptToken',
				array('entityId' => $entityId, 'aptToken' => $token, 'userType' => $userType));
		foreach ($appToken as $app)
		{
			$app->apt_status = 0;
			$app->update();
		}
	}

	public static function logoutUserTypeOnDevice($deviceId, $userType = '0')
	{
		if (!$userType || $userType == 0)
		{
			return 0;
		}
		$params = [
			//'userType'	 => $userType,
			'deviceId' => $deviceId
		];

		$sql	 = "UPDATE app_tokens SET apt_status=0 WHERE apt_status<>0 				
				AND apt_user_type IN ($userType) AND apt_device_uuid=:deviceId";
		$numrows = DBUtil::execute($sql, $params);
		return $numrows;
	}

	public static function getAppUsage($id, $type)
	{
		$cond = '';
		if ($type == 3 || $type == 5)
		{
			$resPrimary = Contact::getRelatedPrimaryListByType($id, $type, true);
 
			$userId	 = $resPrimary['cr_is_consumer'];
			$userIds = Users::getRelatedIds($userId);

			$cond	 = "AND app_tokens.apt_user_id IN({$userIds}) AND apt_user_type  IN (2,3,5)";
			$params	 = [];
		}
		if ($type == 2)
		{
			$cond	 = 'AND app_tokens.apt_entity_id =:id AND apt_user_type  = :type';
			$params	 = array('id' => $id, 'type' => $type);
		}
		if ($type == 1)
		{
			$cond	 = 'AND app_tokens.apt_user_id =:id AND apt_user_type  = :type';
			$params	 = array('id' => $id, 'type' => $type);
		}
		$pageSize	 = 25;
		$sql		 = "SELECT apt_id,apt_device, apt_date, apt_logout, apt_device_uuid, apt_apk_version, apt_os_version,
				apt_status,apt_token_id,apt_last_login,apt_last_loc_lat,apt_last_loc_long,
				CASE WHEN apt_user_type = '1' THEN 'Customer'
					 WHEN apt_user_type = '2' THEN 'Vendor'
					 WHEN apt_user_type IN (3,5) THEN 'Driver'
				END as 'apt_user_type'
			   FROM app_tokens
			   WHERE 1=1 " . $cond;
		 
		$count			 = DBUtil::queryScalar("SELECT COUNT(*) FROM ($sql) abc", DBUtil::SDB(), $params);
		$dataprovider	 = new CSqlDataProvider($sql, [
			'totalItemCount' => $count,
			"params"		 => $params,
			'db'			 => DBUtil::SDB(),
			'sort'			 => ['attributes'	 => ['apt_device', 'apt_last_login'],
				'defaultOrder'	 => 'apt_last_login  DESC'], 'pagination'	 => ['pageSize' => $pageSize],
		]);
		return $dataprovider;
	}

	/**
	 * 
	 * Get entity id (drv_id) from login info
	 * @param integer $userId
	 * @param integer $type
	 * @return integer
	 */
	public static function getEntityByUserInfo($userID, $type)
	{
		$params	 = ['apt_user_id' => $userID, 'apt_user_type' => $type];
		$sql	 = "SELECT   apt_entity_id 
					FROM  app_tokens   
					WHERE 1 AND apt_entity_id IS NOT NULL AND apt_user_id =:apt_user_id  AND apt_status =1  AND apt_user_type =:apt_user_type order by apt_id  desc   LIMIT 0,1 ";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $res;
	}

	/**
	 * 
	 * This function is used for updating User and Device data
	 * @param string $authToken
	 * @param string $device_token
	 * @param integer $userId
	 * @param integer $entityId	 
	 * @return array
	 */
	public static function updateUserAndDevice($authToken, $device_token, $userId, $entityId)
	{
		$success = true;
		$message = '';
		if (empty($authToken) || empty($device_token))
		{
			$success = false;
			$message = 'Invalid token';
			goto resultSet;
		}
		/* @var $appModel AppTokens */
		$appModel = AppTokens::model()->find('apt_token_id = :token and apt_entity_id = :entity_id and apt_status = 1', array('token' => $authToken, 'entity_id' => $entityId));
		if (!$appModel)
		{
			$message = 'Unauthorised token';
			$success = false;
			goto resultSet;
		}
		$appModel->apt_device_token	 = $device_token;
		$appModel->apt_user_id		 = $userId;
		$appModel->scenario			 = 'updateFcm';
		if (!$appModel->save())
		{
			$message = 'Unable to save data.';
			$success = false;
		}
		resultSet:
		return ['success' => $success, 'message' => $message];
	}

	/**
	 * 
	 * This function is used for updating coordinates
	 * @param object $data
	 * @param string $authToken	 
	 * @return bool
	 */
	public static function updateLastLocation($data, $authToken)
	{
		$entityId	 = UserInfo::getEntityId();
		$appModel	 = AppTokens::model()->find('apt_token_id = :token and apt_entity_id = :entity_id and apt_status = 1', array('token' => $authToken, 'entity_id' => $entityId));
		if ($appModel)
		{
			$appModel->apt_last_loc_lat	 = $data->coordinates->latitude;
			$appModel->apt_last_loc_long = $data->coordinates->longitude;
			$appModel->apt_date			 = new CDbExpression('NOW()');
			if ($appModel->save())
			{
				$status = true;
			}
			return $status;
		}
	}

	/**
	 * 
	 * @param int $vendorId
	 * @param int $hourDuration
	 * @return type
	 */
	public static function isVendorLoggedIn($vendorId, $hourDuration = 24)
	{
		$params	 = array('vndId' => $vendorId, 'hourDuration' => $hourDuration);
		$sql	 = "SELECT apt.apt_id FROM `app_tokens` apt 
				WHERE apt.apt_entity_id= :vndId AND apt.apt_user_type=2 AND apt.apt_status = 1 
				AND apt.apt_device_token IS NOT NULL 
				AND  apt.apt_last_login >= DATE_SUB(NOW(),INTERVAL :hourDuration HOUR)
				";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $res;
	}

	public static function getIdByEntity($entityId, $entityType)
	{
		$params	 = array('entityId' => $entityId, 'entityType' => $entityType);
		$sql	 = "SELECT apt_id FROM `app_tokens` apt 
				WHERE apt.apt_entity_id= :entityId AND apt.apt_user_type= :entityType AND apt.apt_status = 1 
                AND apt.apt_device_token IS NOT NULL";
		$res	 = DBUtil::command($sql, DBUtil::SDB())->queryColumn($params);
		return $res;
	}

	public function checkDriverLastLogin($drvId)
	{
		$sql = "SELECT COUNT(1) as cnt FROM `app_tokens` WHERE app_tokens.apt_entity_id='.$drvId.' AND app_tokens.apt_user_type IN (3,5) AND app_tokens.apt_status=1";
		return DBUtil::queryScalar($sql);
	}

	/**
	 * This function will return last login details for any entity type
	 * @param int $entityId
	 * @param int $entityType
	 * @return type array
	 */
	public static function getLastLogin($entityId, $entityType)
	{
		$sql = "SELECT apt_date,apt_entity_id,apt_entity_id  FROM `app_tokens` WHERE app_tokens.apt_entity_id=:apt_entity_id AND app_tokens.apt_user_type=:apt_user_type AND app_tokens.apt_status=0 ORDER BY `apt_id` DESC LIMIT 1,1";
		return DBUtil::queryRow($sql, DBUtil::SDB(), ['apt_user_type' => $entityType, 'apt_entity_id' => $entityId]);
	}

	/**
	 * @param integer $user_id
	 * @param integer $userType
	 * @param array $data
	 * @param string $message
	 * @param string $title
	 * @return boolean
	 */
	public function notifyUserOld($user_id, $userType, $data, $message, $title)
	{
		AppTokens::$db = DBUtil::SDB();
		if ($userType != 1)
		{
			return false;
		}
		$appTokenModel	 = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 1, 'id' => $user_id]);
		AppTokens::$db	 = DBUtil::MDB();
		$notification	 = [
			'notifications' => [
				'title'		 => $title,
				'tripId'	 => $data['tripId'],
				'EventCode'	 => ($data['EventCode'] == "" ? $data['eventCode'] : $data['EventCode']),
				'bookingId'	 => ($data['id'] == "" ? $data['data']['id'] : $data['data']['id']),
				'filterCode' => $data['FilterCode'],
				'Status'	 => $data['Status'],
				'message'	 => $message,
				'icon'		 => '@drawable/logo',
				'sound'		 => 'default',
				'data'		 => $data['data']
			]
		];
		if (isset($data['isGozoNow']))
		{
			$notification['notifications']['isGozoNow'] = $data['isGozoNow'];
		}
		return AppTokens::model()->sendNotifications($appTokenModel, $data, $notification);
	}

	/**
	 * @param integer $user_id
	 * @param integer $userType
	 * @param array $payLoadData
	 * @param string $message
	 * @param string $title
	 * @return boolean
	 */
	public function notifyUser($user_id, $userType, $payLoadData, $message, $title)
	{
		if ($userType != 1)
		{
			return false;
		}

		$daysCount		 = 365;
		$hourDuration	 = $daysCount * 24;
		$apptokenList	 = AppTokens::getDeviceTokenListByEntity($user_id, $userType, $hourDuration);

		$payLoadData['message']	 = $message;
		$payLoadData['title']	 = $title;
		$notification			 = [
			'notifications' => [
				'title'		 => $title,
				'EventCode'	 => $payLoadData['EventCode'],
				'body'		 => $message,
				'message'	 => $message,
			]
		];

		if (count($apptokenList) > 0)
		{

			$aptModel[] = new AppTokens();

			$aptModel[0]->apt_user_type	 = $userType;
			$aptModel[0]->apt_user_id	 = $user_id;
			$aptModel[0]->apt_entity_id	 = $user_id;

			$logId							 = NotificationLog::createLog($aptModel, $notification);
			$payLoadData['notificationId']	 = (int) $logId;

			$resultArr = FirebaseMessaging::sendToDevice($apptokenList, $payLoadData, $notification);
			return $resultArr;
		}
	}

	/**
	 * This function will be used to send notification for all entityType
	 * @param type $entity_id
	 * @param type $data
	 * @param type $message
	 * @param type $title 
	 * @return type bool
	 */
	public function notifyEntity($entity_id, $userType, $data, $message, $title, $checkLogin = true)
	{
		$logginDayCount	 = 0;
		$daysCount		 = ($logginDayCount > 0) ? $logginDayCount : 5;
		AppTokens::$db	 = DBUtil::SDB();
		if ($checkLogin == true)
		{
			$condition = "AND apt_last_login>=DATE_SUB(NOW(), INTERVAL '$daysCount' DAY)";
		}
		$appTokenModel = AppTokens::model()->findAll("apt_status=:status $condition AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type", ['status' => 1, 'type' => $userType, 'id' => $entity_id]);

		AppTokens::$db	 = DBUtil::MDB();
		$notification	 = [
			'notifications' => [
				'title'		 => $title,
				'tripId'	 => $data['tripId'],
				'EventCode'	 => ($data['EventCode'] == "" ? $data['eventCode'] : $data['EventCode']),
				'filterCode' => $data['FilterCode'],
				'Status'	 => $data['Status'],
				'message'	 => $message,
				'icon'		 => '@drawable/logo',
				'sound'		 => 'default',
				'data'		 => $data['data']
			]
		];
		if (isset($data['isGozoNow']))
		{
			$notification['notifications']['isGozoNow'] = $data['isGozoNow'];
		}
		return AppTokens::model()->sendNotifications($appTokenModel, $data, $notification);
	}

	public function notifyEntityNew($entity_id, $userType, $payLoadData, $message, $title)
	{
		$daysCount		 = 5;
		$hourDuration	 = $daysCount * 24;
		$apptokenList	 = AppTokens::getDeviceTokenListByEntity($entity_id, $userType, $hourDuration);

//		AppTokens::$db	 = DBUtil::SDB();
//		$appTokenModel	 = AppTokens::model()->findAll("apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL '$daysCount' DAY) AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type ORDER BY apt_last_login desc", ['status' => 1, 'type' => $userType, 'id' => $entity_id]);
//		AppTokens::$db	 = DBUtil::MDB();

		$payLoadData['message']	 = $message;
		$payLoadData['title']	 = $title;

		$notification = [
			'notifications' => [
				'title'		 => $title,
				'tripId'	 => $payLoadData['tripId'],
				'EventCode'	 => $payLoadData['EventCode'],
				'filterCode' => $payLoadData['FilterCode'],
				'body'		 => $message,
				'message'	 => $message,
			]
		];

//		$tokens = [];

		if (count($apptokenList) > 0)
		{

			$aptModel[] = new AppTokens();

			$aptModel[0]->apt_user_type	 = $userType;
			$aptModel[0]->apt_user_id	 = $entity_id;
			$aptModel[0]->apt_entity_id	 = $entity_id;

			$logId							 = NotificationLog::createLog($aptModel, $notification);
			$payLoadData['notificationId']	 = (int) $logId;

			$resultArr = FirebaseMessaging::sendToDevice($apptokenList, $payLoadData, $notification);
			return $resultArr;
		}


//		return AppTokens::model()->sendNotifications($appTokenModel, $data, $notification);
	}

	public static function getByTokens($deviceToken, $userTokenId = '')
	{
		$strUserToken = '';
		if ($userTokenId != '' && $userTokenId != null)
		{
			$strUserToken = " AND apt_token_id = '{$userTokenId}' ";
		}

		$where = " apt_status=1 AND apt_device_token = '{$deviceToken}' {$strUserToken}";

		return AppTokens::model()->find($where, array('order' => 'apt_id DESC'));
	}

	/**
	 * 
	 * @param int $driverId
	 * @return type
	 */
	public static function isDriverLoggedIn($driverId)
	{
		$params	 = array('drvId' => $driverId);
		$sql	 = "SELECT apt.apt_id FROM app_tokens apt 
				WHERE apt.apt_entity_id= :drvId AND apt.apt_user_type = 5 AND apt.apt_status = 1 
				";
		$res	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $params);
		return $res;
	}

	/**
	 * This function is used for sending notification while closing call back
	 * @param type $entityId
	 * @param type $data
	 * @param type $message
	 * @param type $title
	 * @param type $type
	 * @return type
	 */
	public static function callBackNotification($entityId, $data, $message, $title, $type)
	{
		$transaction = DBUtil::beginTransaction();
		try
		{
			switch ($type)
			{
				case UserInfo::TYPE_VENDOR:
					$id	 = "apt_entity_id";
					break;
				case UserInfo::TYPE_CONSUMER:
					$id	 = "apt_user_id";
					break;
				default:
					break;
			}
			$appTokenModel	 = AppTokens::model()->findAll("apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 5 DAY) AND apt_device_token IS NOT NULL AND $id =:id AND apt_user_type=:type", ['status' => 1, 'type' => $type, 'id' => $entityId]);
			$notification	 = [
				'notifications' => [
					'title'		 => $title,
					'EventCode'	 => $data['EventCode'],
					'message'	 => $message,
					'icon'		 => '@drawable/logo',
					'sound'		 => 'default',
				]
			];
			if (isset($data['scqId']))
			{
				$notification['notifications']['scqId'] = $data['scqId'];
			}
			$result = AppTokens::model()->sendNotifications($appTokenModel, $data, $notification);
			DBUtil::commitTransaction($transaction);
			return $result;
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
			return false;
		}
	}

	/**
	 * 
	 * @param Booking $model
	 */
	public static function notifyMulti($model, $vndTokenList, $batchId)
	{
		/** @var Stub\common\Notification $notify */
		/** @var Booking $model */
		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$title		 = $notify->title;

		$notification = [
			'notifications' => [
				'title'		 => $title,
				'tripId'	 => $payLoadData['tripId'],
				'EventCode'	 => $payLoadData['EventCode'],
				'filterCode' => $payLoadData['FilterCode'],
				'Status'	 => $payLoadData['Status'],
				'message'	 => $message,
				'icon'		 => '@drawable/logo',
				'sound'		 => 'default',
				'data'		 => $payLoadData['data']
			]
		];
		if (isset($payLoadData['isGozoNow']))
		{
			$notification['notifications']['isGozoNow'] = $payLoadData['isGozoNow'];
		}
		$notification['notifications']['batchId'] = $batchId;

		$result						 = FCM::send(explode(',', $vndTokenList), $payLoadData, $notification);
		$resultArr					 = json_decode($result, true);
		$resultArr['notification']	 = $notification;
		return $resultArr;
	}

	public static function notifyMultiNew($model, $vndTokenList, $batchId = '')
	{
		/** @var Stub\common\Notification $notify */
		/** @var Booking $model */
		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);

		$message = $notify->message;
		$title	 = $notify->title;

		$payLoadData['message']	 = $message;
		$payLoadData['title']	 = $title;

		$notification['notifications'] = $notify->setNotificationBody();

		$notification['notifications']['tripId'] = $payLoadData['tripId'];

		if ($batchId != '')
		{
			$payLoadData['batchId'] = $batchId;
		}

		$resultArr = FirebaseMessaging::sendToDevice(explode(',', $vndTokenList), $payLoadData, $notification);

		$resultArr['notification']			 = $notification;
		$resultArr['notification']['data']	 = $payLoadData;
		return $resultArr;
	}

	/**
	 * 
	 * @param AppTokens $device
	 * @param UserInfo $userInfo	
	 * @param string $activeVersion
	 * @return type
	 * @throws Exception
	 */
	public static function registerDevice(\AppTokens $device, \UserInfo $userInfo, $activeVersion = null, $platform = 0)
	{

		$model		 = new AppTokens();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($device instanceof AppTokens)
			{
				$model = $device;
			}

			$tokenId				 = self::generateAuthToken($userInfo->userId, $model->apt_device, $model->apt_device_uuid, $model->apt_apk_version);
			$model->apt_token_id	 = md5($tokenId);
			$model->apt_user_type	 = ($userInfo->getUserType() == null) ? 10 : $userInfo->getUserType();
			$model->apt_user_id		 = $userInfo->getUserId();
			$model->apt_ip_address	 = \Filter::getUserIP();
			if ($platform > 0)
			{
				$model->apt_platform = $platform;
			}
			if ($activeVersion != null)
			{
				$isVersionCheck = self::validateVersion($model->apt_apk_version, $activeVersion);
				if (!$isVersionCheck)
				{
					$message = "Invalid version.";
					throw new Exception(CJSON::encode($message), ReturnSet::ERROR_VALIDATION);
				}
			}
			if (!$model->save())
			{
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			self::deactivateExpiredToken($model->apt_device_uuid, $model->apt_token_id, $userInfo->getUserType());
			if ($model->apt_device_token != '')
			{
				AppTokens::deactivateExpiredFCMToken($model->apt_token_id, $model->apt_device_token);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $ex;
		}
		return $model;
	}

	/**
	 * 
	 * @param type $deviceId
	 * @param type $token
	 * @param type $userType
	 * @return type
	 */
	public static function deactivateExpiredToken($deviceId, $token, $userType = 0)
	{
		$sql	 = "UPDATE app_tokens SET apt_status = 0 
		WHERE apt_device_uuid=:device AND 
		apt_user_type=:userType AND apt_token_id<>:token 
		AND apt_status = 1";
		$numrows = DBUtil::execute($sql, ['device' => $deviceId, 'token' => $token, 'userType' => $userType]);
		return $numrows;
	}

	/**
	 * 
	 * @param type $authToken
	 * @param type $fcmToken
	 * @return int
	 */
	public static function deactivateExpiredFCMToken($authToken, $fcmToken)
	{
		$sql = "UPDATE app_tokens SET apt_status = 0 
  	WHERE apt_token_id <> :token AND apt_device_token=:deviceToken AND apt_status = 1";

		$numrows = DBUtil::execute($sql, ['token' => $authToken, 'deviceToken' => $fcmToken]);
		return $numrows;
	}

	/**
	 * 
	 * @param type $token
	 * @return type
	 */
	public static function getUserById($token)
	{
		$appToken = AppTokens::validateToken($token);

		$userModel = Users::model()->findByPk($appToken->apt_user_id);
		if ($userModel)
		{
			$appToken->apt_last_login = new CDbExpression('NOW()');
			$appToken->save();
		}
		return $userModel;
	}

	/**
	 * 
	 * @param string $jwtToken
	 * @return boolean
	 */
	public static function logoutDCO($jwtToken)
	{
		$appRecord = AppTokens::getModelByJWT($jwtToken);
		if ($appRecord)
		{
			$appRecord->apt_status	 = 0;
			$appRecord->apt_logout	 = new CDbExpression('NOW()');
			$success				 = $appRecord->save();
		}
		return $success;
	}

	/**
	 * 
	 * @param string $jwtToken
	 * @return boolean | \AppTokens
	 */
	public static function getModelByJWT($jwtToken)
	{
		$res		 = Yii::app()->JWT->decodeToken($jwtToken);
		$deviceToken = $res->token;
		Logger::trace("deviceToken: " . json_encode($res));
		$info		 = \UserInfo::getInstance();
		Logger::trace("userInfo: " . json_encode($info));
		$userId		 = \UserInfo::getUserId();

		$appRecord = AppTokens::model()->find('apt_token_id = :token 
 		AND apt_user_id = :userid', array('token' => $deviceToken, 'userid' => $userId));
		return $appRecord;
	}

	/**
	 * Function for archiving app token
	 */
	public function archiveData($archiveDB, $upperLimit = 1000000, $lowerLimit = 1000)
	{
		$i			 = 0;
		$chk		 = true;
		$totRecords	 = $upperLimit;
		$limit		 = $lowerLimit;
		while ($chk)
		{
			Logger::writeToConsole("While");
			$transaction = "";
			try
			{
				$sql	 = "SELECT GROUP_CONCAT(apt_id) AS apt_id FROM (SELECT apt_id FROM app_tokens WHERE 1 AND apt_date <= DATE_SUB(NOW(),INTERVAL 1 YEAR) AND apt_status = 0 ORDER BY apt_id ASC LIMIT 0, $limit) as temp";
				$resQ	 = DBUtil::queryScalar($sql);
				if (!is_null($resQ) && $resQ != '')
				{
					Logger::writeToConsole("INSERT");
					$transaction = DBUtil::beginTransaction();
					DBUtil::getINStatement($resQ, $bindString, $params);
					$sql		 = "INSERT INTO " . $archiveDB . ".app_tokens (SELECT * FROM app_tokens WHERE apt_id IN ($bindString))";
					$rows		 = DBUtil::execute($sql, $params);
					if ($rows > 0)
					{
						Logger::writeToConsole("DELETE");
						$sql = "DELETE FROM `app_tokens` WHERE apt_id IN ($bindString)";
						DBUtil::execute($sql, $params);
						DBUtil::commitTransaction($transaction);
						Logger::writeToConsole("COMMITTED");
					}
					else
					{
						DBUtil::rollbackTransaction($transaction);
						Logger::writeToConsole("ROLLBACK");
					}
				}

				$i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
				Logger::writeToConsole("ERROR: " . $e->getMessage());
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	public static function notifyVendorGnowBooking($vendorId, $bcbId, $message = '', $title = '')
	{
		$payLoadData = ['tripId' => $bcbId, 'EventCode' => Booking::CODE_VENDOR_BOOKING_REQUEST];
		$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param type $entityId
	 * @param type $entityType
	 * @param type $hourDuration
	 * @return type
	 */
	public static function getDeviceTokenListByEntity($entityId, $entityType, $hourDuration = 60)
	{
		$params = ['userType' => $entityType, 'entityId' => $entityId];

		switch ($entityType)
		{
			case UserInfo::TYPE_VENDOR:
				$id	 = "apt_entity_id";
				break;
			case UserInfo::TYPE_CONSUMER:
				$id	 = "apt_user_id";
				break;
			default:
				$id	 = "apt_entity_id";
				break;
		}

		$sql	 = " SELECT DISTINCT apt_device_token 
	FROM `app_tokens` apt  
	WHERE apt.apt_status = 1 
	AND apt.$id>0 AND (apt.apt_device_token IS NOT NULL AND apt.apt_device_token !='')
	AND apt.apt_last_login >= DATE_SUB(NOW(),INTERVAL $hourDuration HOUR)
	AND $id=:entityId AND apt_user_type=:userType
	ORDER BY apt.apt_last_login DESC ";
		$data	 = DBUtil::command($sql)->queryColumn($params);
		return $data;
	}

	public static function updateEntityByUserId($userId, $entityId)
	{
		$params = ['userId' => $userId, 'entityId' => $entityId];

		$sql	 = 'UPDATE `app_tokens` apt SET apt_entity_id=:entityId WHERE apt_user_id=:userId AND apt_status = 1';
		$numrows = DBUtil::execute($sql, $params);
		return $numrows;
	}

	public static function getLatestTokenByEntity($entityId, $entityType)
	{
		$sql = "SELECT apt_token_id FROM `app_tokens` WHERE `apt_entity_id` = $entityId AND `apt_user_type` IN ($entityType) AND `apt_status` = 1 ORDER BY `apt_last_login` DESC LIMIT 0,1";

		return DBUtil::queryScalar($sql);
	}

	public static function getEntity($token)
	{
		$param	 = ['tokenId' => $token];
		$sql	 = "SELECT apt_entity_id FROM `app_tokens` WHERE `apt_token_id` = :tokenId 
				AND  `apt_status` = 1 ORDER BY `apt_last_login` DESC LIMIT 0,1";

		return DBUtil::queryScalar($sql, DBUtil::MDB(), $param);
	}

	public static function setAppNotificationParams($entityType, $entityId, $eventCode, $title, $tripId = null, $bookingId = null)
	{
		return array(
			'entityType'	 => $entityType,
			'entityId'		 => $entityId,
			'eventCode'		 => $eventCode,
			'title'			 => $title,
			'tripId'		 => $tripId,
			'bookingId'		 => $bookingId,
			'notificationId' => substr(round(microtime(true) * 1000), -5)
		);
	}

	public static function sendNotification($appNotificationParams, $payLoadData, $msg)
	{
		AppTokens::$db	 = DBUtil::SDB();
		$appTokenModel	 = AppTokens::model()->findAll("apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 5  DAY) AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type", ['status' => 1, 'type' => $appNotificationParams->entityType, 'id' => $appNotificationParams->entityId]);
		AppTokens::$db	 = DBUtil::MDB();
		return AppTokens::model()->sendNotifications($appTokenModel, $payLoadData, ['notifications' => ['notificationId' => $appNotificationParams->notificationId, 'title' => $appNotificationParams->title, 'tripId' => $appNotificationParams->tripId, 'bookingId' => $appNotificationParams->bookingId, 'EventCode' => $appNotificationParams->eventCode, 'message' => $msg, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public static function updateDeviceSettings($token, $data)
	{
		$params	 = ['tokenId' => $token, 'data' => $data];
		$sql	 = 'UPDATE `app_tokens` apt SET apt_device_setting=:data WHERE apt_token_id=:tokenId AND apt_status = 1';
		$numrows = DBUtil::execute($sql, $params);
		return $numrows;
	}

	/**
	 * 
	 * @param int $msgId
	 * @param int $entityId
	 * @param int $batchId
	 * @return bool|ArrayObject
	 */
	public static function notifyDCOForAdminChat($msgId, $entityId = 0, $batchId = '')
	{
		$dataRow = \ChatLog::getCurrentMessageById($msgId);
		if ($dataRow['bcb_vendor_id'] > 0)
		{
			$entityId = $dataRow['bcb_vendor_id'];
		}
		$userType	 = 2;
		$platform	 = AppTokens::Platform_DCO;
		$tokenRow	 = \AppTokens::getFCMTokenListByEntity($entityId, $userType, $platform);
		if (!$tokenRow)
		{
			return false;
		}
		$tokenList = $tokenRow['aptTokens'];

		$chatData = \Beans\common\Chat::setNotificationData($dataRow);

		$notify = new \Beans\common\Notification();
		$notify->setDCONotifyForAdminChat($chatData);

		$resultArr = \AppTokens::sendNotificationToTokens($notify, $tokenList, $batchId);

//		$notification	 = $resultArr['notification'];
//		$dcoLogArrList	 = explode(',', $entityId);
//		NotificationLog::createVendorMultiLog($dcoLogArrList, $notification, $batchId);
		return $resultArr;
	}

	/**
	 * 
	 * @param \Beans\common\Notification $notify
	 * @param type $tokenList
	 * @param type $batchId
	 * @return type
	 */
	public static function sendNotificationToTokens(\Beans\common\Notification $notify, $tokenList, $batchId = '')
	{

		/** @var Beans\common\Notification $notify */
		$payLoadData = json_decode(json_encode($notify->payload), true);

		$message = $notify->message;
		$title	 = $notify->title;

		$payLoadData['message']	 = $message;
		$payLoadData['title']	 = $title;

		$notification['notifications'] = $notify->setNotificationBody();

		$notification ['notifications']['tripId']	 = $payLoadData['tripId'];
		$notification ['notifications']['bkgId']	 = $payLoadData['bkgId'];

		if ($batchId != '')
		{
			$payLoadData['batchId'] = $batchId;
		}

		$resultArr = FirebaseMessaging::sendToDevice(explode(',', $tokenList), $payLoadData, $notification);

		$resultArr['notification']			 = $notification;
		$resultArr['notification']['data']	 = $payLoadData;

		return $resultArr;
	}

	/**
	 * 
	 * @param int $entityId
	 * @param int $entityType
	 * @return type
	 */
	public static function getFCMTokenListByEntity($entityId, $entityType, $platform = null)
	{
		$where	 = '';
		$params	 = ['entityId' => $entityId, 'entityType' => $entityType];
		if ($platform > 0)
		{
			$where				 = " AND apt.apt_platform = :platform";
			$params['platform']	 = $platform;
		}
		$sql	 = "SELECT apt.apt_entity_id, apt.apt_user_id, apt.apt_user_type,
				COUNT(DISTINCT apt_device_token) cntToken,
				GROUP_CONCAT(DISTINCT apt_device_token) aptTokens,
				apt_platform
			FROM `app_tokens` apt
			WHERE apt.apt_entity_id = :entityId AND apt.apt_status = 1 AND apt.apt_entity_id > 0 
				AND apt.apt_device_token IS NOT NULL 				
				AND apt.apt_last_login >= DATE_SUB(NOW(), INTERVAL 48 HOUR) 
			AND apt.apt_user_type = :entityType $where ";
		$data	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $data;
	}
}
