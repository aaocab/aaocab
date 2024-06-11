<?php

/**
 * This is the model class for table "chat_log".
 *
 * The followings are the available columns in table 'chat_log':
 * @property integer $chl_id
 * @property integer $chl_cht_id
 * @property string $chl_msg
 * @property integer $chl_ref_id
 * @property integer $chl_ref_type
 * @property integer $chl_driver_visible
 * @property integer $chl_vendor_visible
 * @property integer $chl_customer_visible
 * @property integer $chl_admin_is_read
 * @property integer $chl_type
 * @property string $chl_log
 * @property integer $chl_active
 * @property string $chl_created
 * 
 * The followings are the available model relations:
 * @property Chats $chlCht
 */
class ChatLog extends CActiveRecord
{

	public $cht_id;
	//public $cht_entity_id;
	//public $cht_entity_type;
	public $cht_ref_id;
	public $cht_ref_type;
	public $refPlatform = ['Consumer' => 1, 'Vendor' => 2, 'Driver' => 3, 'Admin' => 4, 'Agent' => 5];

	const chatType = [0 => 'Message', 1 => 'Join Chat', 2 => 'Take Over', 3 => 'Leave Chat'];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'chat_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('chl_cht_id, chl_created', 'required'),
			array('chl_cht_id, chl_ref_id, chl_ref_type, chl_driver_visible, chl_vendor_visible, chl_customer_visible, chl_admin_is_read, chl_type, chl_active', 'numerical', 'integerOnly' => true),
			array('chl_msg', 'length', 'max' => 1000),
			array('chl_log', 'length', 'max' => 2000),
			array('chl_msg, chl_cht_id', 'required', 'on' => 'addMessage'),
			['chl_vendor_visible', 'validateVendor', 'on' => 'msgVendorValidate'],
			['chl_vendor_visible', 'validateVendor', 'on' => 'msgVendorValidate'],
			['chl_vendor_visible', 'validateDriver', 'on' => 'msgDriverValidate'],
			['chl_vendor_visible', 'validateVendor', 'on' => 'msgAdminValidate'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('chl_cht_id, chl_msg', 'required', 'on' => 'insert,update'),
			array('chl_id, chl_cht_id, chl_msg, chl_ref_id, chl_ref_type, chl_driver_visible, chl_vendor_visible, chl_customer_visible, chl_admin_is_read, chl_type, chl_log, chl_active, chl_created', 'safe', 'on' => 'search'),
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
			'chlCht' => array(self::BELONGS_TO, 'Chats', 'chl_cht_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'chl_id'				 => 'Chat Log',
			'chl_cht_id'			 => 'Chat',
			'chl_msg'				 => 'Chat Message',
			'chl_ref_id'			 => 'Chat Ref',
			'chl_ref_type'			 => '1:Consumer ; 2:Vendor ; 3:Driver ; 4:Admin ; 5:Agent',
			'chl_driver_visible'	 => 'Chat Driver Visible',
			'chl_vendor_visible'	 => 'Chat Vendor Visible',
			'chl_customer_visible'	 => 'Chat Customer Visible',
			'chl_admin_is_read'		 => 'Admin Is Read',
			'chl_type'				 => 'Message Type',
			'chl_log'				 => 'Chat Log',
			'chl_active'			 => 'Chat Active',
			'chl_created'			 => 'Chat Created',
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

		$criteria->compare('chl_id', $this->chl_id);
		$criteria->compare('chl_cht_id', $this->chl_cht_id);
		$criteria->compare('chl_msg', $this->chl_msg, true);
		$criteria->compare('chl_ref_id', $this->chl_ref_id);
		$criteria->compare('chl_ref_type', $this->chl_ref_type);
		$criteria->compare('chl_driver_visible', $this->chl_driver_visible);
		$criteria->compare('chl_vendor_visible', $this->chl_vendor_visible);
		$criteria->compare('chl_customer_visible', $this->chl_customer_visible);
		$criteria->compare('chl_admin_is_read', $this->chl_admin_is_read);
		$criteria->compare('chl_type', $this->chl_type);
		$criteria->compare('chl_log', $this->chl_log, true);
		$criteria->compare('chl_active', $this->chl_active);
		$criteria->compare('chl_created', $this->chl_created, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChatLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * getPlatform
	 */
	public function getPlatform($platform = '')
	{
		$arr = $this->refPlatform;
		if($platform == '')
		{
			$platform = 1;
		}
		return $arr[$platform];
	}

	/**
	 * validateVendor
	 */
	public function validateVendor($attribute, $params)
	{
		$success = true;
		if($this->chl_vendor_visible != 1 || $this->chl_vendor_visible == NULL)
		{
			$this->addError('chl_vendor_visible', "Please check vendor box before submit.");
			$success = false;
		}
		if($this->chl_msg == '' || $this->chl_msg == NULL)
		{
			$this->addError('chl_msg', "Message cannot be blank.");
			$success = false;
		}
		return $success;
	}

	/**
	 * validateDriver
	 */
	public function validateDriver($attribute, $params)
	{
		$success = true;

		if($this->chl_driver_visible != 1 || $this->chl_driver_visible == NULL)
		{
			$this->addError('chl_driver_visible', "Please check driver box before submit.");
		}
		if($this->chl_msg == '' || $this->chl_msg == NULL)
		{
			$this->addError('chl_msg', "Message cannot be blank.");
			$success = false;
		}
		return $success;
	}

	/**
	 * getCurrentMessageById
	 */
	public static function getCurrentMessageById($id)
	{
		$sql = "SELECT
				chats.cht_id,
				chat_log.chl_id,
				booking.bkg_id,
                booking.bkg_booking_id,
                booking_cab.bcb_vendor_id,
				chl_ref_id,
				chl_ref_type,
				cht_ref_id,
				cht_ref_type,

                UPPER
                (
                    CASE 
					WHEN chl_ref_type = 1 THEN 'Consumer' 
					WHEN chl_ref_type = 2 THEN 'Vendor' 
					WHEN chl_ref_type = 3 THEN 'Driver' 
					WHEN chl_ref_type = 4 THEN 'Admin' 
					WHEN chl_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_type,
                (
                    CASE 
					WHEN chl_ref_type = 1 THEN CONCAT(users.usr_name,' ', users.usr_lname) 
					WHEN chl_ref_type = 2 THEN vendors.vnd_name 
					WHEN chl_ref_type = 3 THEN drv_name 
					WHEN chl_ref_type = 4 THEN CONCAT(admins.adm_fname, ' ', admins.adm_lname) 
					WHEN chl_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_name,
				(
                    CASE 
						WHEN chl_ref_type = 1 THEN 'Customer Support' 
						WHEN chl_ref_type = 2 THEN 'Vendor Support' 
						WHEN chl_ref_type = 3 THEN 'Driver Support' 
						WHEN chl_ref_type = 4 THEN 'Partner Support' 
						WHEN chl_ref_type = 5 THEN 'Agent Support'
                    END
                ) as display_name,
                chat_log.chl_msg,
                chat_log.chl_created, 
				chat_log.chl_driver_visible,
				chat_log.chl_vendor_visible,
				chat_log.chl_customer_visible
                FROM
                `chats`
                INNER JOIN `chat_log` ON `chat_log`.chl_cht_id = chats.cht_id AND chat_log.chl_active = 1 AND chats.cht_active = 1  
				LEFT JOIN `booking` ON booking.bkg_id = chats.cht_ref_id AND chats.cht_ref_type = 0 AND booking.bkg_active = 1
                LEFT JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1
				LEFT JOIN `users` ON users.user_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 1
                LEFT JOIN `vendors` ON vendors.vnd_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 2
                LEFT JOIN `drivers` ON drv_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 3
				LEFT JOIN `admins` ON admins.adm_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 4
                WHERE chat_log.chl_id = $id";
		$row = DBUtil::queryRow($sql);

		//$row['chl_created'] = date("j-M-y, g:i:s A", strtotime($row['chl_created']));

		return $row;
	}

	/**
	 * validateArray
	 */
	public function validateArray()
	{
		return [1 => 'msgCustomerValidate', 2 => 'msgVendorValidate', 3 => 'msgDriverValidate', 4 => 'msgAdminValidate'];
	}

	/**
	 * setValidate
	 */
	public function setValidate($type)
	{
		$list = $this->validateArray();
		return $list[$type];
	}

	/**
	 * addMessage
	 */
	public function addMessage($entity_id, $message, $userInfo, $is_driver = 0, $is_vendor = 0, $is_customer = 0, $entity_type = 0, $arrData = array())
	{
		$success	 = true;
		$errors		 = '';
		$currDate	 = date("Y-m-d H:i:s");

		// Logged User Info
		$user_id	 = $userInfo->userId;
		$user_type	 = $userInfo->userType;

		// Validate Data Posted
		if(isset($user_type) && (!isset($arrData['chl_type']) || $arrData['chl_type'] == 0))
		{
			$scenario = $this->setValidate($user_type);
		}

		// Admin Read Count
		$chlType			 = 0;
		$ownerId			 = $user_id;
		$msgAdminIsRead		 = 1;
		$msgUnreadCountAdmin = 0;

		if(is_array($arrData) && count($arrData) > 0)
		{

			// If Posted By Vendor
			if(isset($arrData['source']) && $arrData['source'] == 'vendor')
			{
				$ownerId			 = 0;
				$msgAdminIsRead		 = 0;
				$msgUnreadCountAdmin = 1;
			}

			// Chat Log Type, other than posted msg
			if(isset($arrData['chl_type']) && $arrData['chl_type'] > 0)
			{
				$chlType = $arrData['chl_type'];
			}
		}

		// Chats
		$sql1 = "SELECT cht_id FROM `chats` WHERE cht_entity_id = $entity_id AND cht_entity_type = $entity_type AND cht_active = 1 ";

		$cht_id = DBUtil::command($sql1)->queryScalar();
		if(!$cht_id)
		{
			$objChats								 = new Chats();
			$objChats->cht_entity_id				 = $entity_id;
			$objChats->cht_entity_type				 = $entity_type;
			$objChats->cht_start_date				 = $currDate;
			$objChats->cht_last_date				 = $currDate;
			$objChats->cht_owner_id					 = $ownerId;
			$objChats->cht_unread_count_for_admin	 = $msgUnreadCountAdmin;
			$objChats->cht_status					 = 1;
			$objChats->cht_active					 = 1;

			if($objChats->save())
			{
				$cht_id = $objChats->cht_id;
			}
		}
		else
		{
			$chatModel								 = Chats::model()->findByPk($cht_id);
			$chatModel->cht_last_date				 = $currDate;
			$chatModel->cht_unread_count_for_admin	 = $chatModel->cht_unread_count_for_admin + $msgUnreadCountAdmin;

			// Updating owner, only when chat is not ended
			if($ownerId > 0 && $chatModel->cht_status == 1)
			{
				$chatModel->cht_owner_id = $ownerId;
			}

			$chatModel->save();
		}

		// ChatLog
		$model						 = new ChatLog();
		$model->scenario			 = $scenario;
		$model->chl_cht_id			 = $cht_id;
		$model->chl_msg				 = $message;
		$model->chl_ref_id			 = ($user_type == 4 ? $user_id : UserInfo::getEntityId());
		$model->chl_ref_type		 = $user_type;
		$model->chl_driver_visible	 = $is_driver;
		$model->chl_vendor_visible	 = $is_vendor;
		$model->chl_customer_visible = $is_customer;
		$model->chl_admin_is_read	 = $msgAdminIsRead;
		$model->chl_type			 = $chlType;
		$model->chl_active			 = 1;

		if($model->validate())
		{
			if($model->save())
			{
				$success = true;
				$chl_id	 = $model->chl_id;

				// Caching Last ChatLog Id
				Yii::app()->cache->set("chatMsg_" . $model->chl_cht_id, $chl_id, 3600, new CacheDependency('chatMsg')); // 1 Hr
				// Sending notification to vendor  for booking message
				if($model->chl_vendor_visible == 1 && $model->cht_entity_type == 0 && $model->chl_ref_type != 2)
				{
					$notifyCom = new notificationWrapper();
					$notifyCom->sendVndMessagingForBooking($model->chl_id);
				}
			}
		}
		else
		{
			$success = false;
			$errors	 = $model->getErrors();
		}

		$return = ['success' => $success, 'errors' => $errors, 'id' => $chl_id];
		return $return;
	}

	public static function addMessageV1($message, $refId, $refType, $userInfo, $is_driver = 0, $is_vendor = 0, $is_customer = 0, $arrData = [])
	{

		$user_id	 = $userInfo->userId;
		$user_type	 = $userInfo->userType;
		$entityId	 = ($user_type == 4 ) ? $user_id : UserInfo::getEntityId();

		// Validate Data Posted
		if(isset($user_type) && (!isset($arrData['chl_type']) || $arrData['chl_type'] == 0))
		{
			$scenario = ChatLog::model()->setValidate($user_type);
		}

		// Admin Read Count
		$chlType			 = 0;
		$ownerId			 = $user_id;
		$msgAdminIsRead		 = 1;
		$msgUnreadCountAdmin = 0;

		if(is_array($arrData) && count($arrData) > 0)
		{
			// If Posted By Vendor
			if(isset($arrData['source']) && ($arrData['source'] == 'vendor' || $arrData['source'] == 'driver'))
			{
				$ownerId			 = 0;
				$msgAdminIsRead		 = 0;
				$msgUnreadCountAdmin = 1;
			}

			// Chat Log Type, other than posted msg
			if(isset($arrData['chl_type']) && $arrData['chl_type'] > 0)
			{
				$chlType = $arrData['chl_type'];
			}
		}
		$where = '';

		if($user_type == 2)
		{
			$where = " AND cht_vendor_id=$entityId";
		}
		if($user_type == 3)
		{
			$where = " AND cht_driver_id=$entityId";
		}
		// Chats
		$sql1	 = "SELECT cht_id FROM `chats` 
				WHERE cht_ref_id = $refId 
					AND cht_ref_type = $refType 
					$where 
					AND cht_active = 1 ";
		$cht_id	 = DBUtil::queryScalar($sql1);

		$currDate = new CDbExpression('NOW()');

		if(!$cht_id)
		{
			$objChats								 = new Chats();
			$objChats->cht_ref_id					 = $refId;
			$objChats->cht_ref_type					 = $refType;
			$objChats->cht_start_date				 = $currDate;
			$objChats->cht_last_date				 = $currDate;
			$objChats->cht_owner_id					 = $ownerId;
			$objChats->cht_unread_count_for_admin	 = $msgUnreadCountAdmin;
			$objChats->cht_status					 = 1;
			$objChats->cht_active					 = 1;

			if($objChats->save())
			{
				$cht_id = $objChats->cht_id;
			}
			else
			{
				$success = false;
				$errors	 = $objChats->getErrors();
				goto skipAll;
			}
		}
		else
		{
			$chatModel								 = Chats::model()->findByPk($cht_id);
			$chatModel->cht_last_date				 = $currDate;
			$chatModel->cht_unread_count_for_admin	 = $chatModel->cht_unread_count_for_admin + $msgUnreadCountAdmin;

			// Updating owner, only when chat is not ended
			if($ownerId > 0 && $chatModel->cht_status == 1)
			{
				$chatModel->cht_owner_id = $ownerId;
			}

			$chatModel->save();
		}

		Chats::updateRoomMember($cht_id, $entityId, $user_type);

		// ChatLog
		$model						 = new ChatLog();
		$model->scenario			 = $scenario;
		$model->chl_cht_id			 = $cht_id;
		$model->chl_msg				 =  utf8_decode($message);
		$model->chl_ref_id			 = ($user_type == 4 ) ? $user_id : UserInfo::getEntityId();
		$model->chl_ref_type		 = $user_type;
		$model->chl_driver_visible	 = $is_driver;
		$model->chl_vendor_visible	 = $is_vendor;
		$model->chl_customer_visible = $is_customer;
		$model->chl_admin_is_read	 = $msgAdminIsRead;
		$model->chl_type			 = $chlType;
		$model->chl_active			 = 1;

		if($model->validate())
		{
			if($model->save())
			{
				$success			 = true;
				$chl_id				 = $model->chl_id;
				$model->cht_ref_type = $refType;
				// Caching Last ChatLog Id
				Yii::app()->cache->set("chatMsg_" . $model->chl_cht_id, $chl_id, 3600, new CacheDependency('chatMsg')); // 1 Hr
				// Sending notification to vendor  for booking message
				if($model->chl_vendor_visible == 1 && $model->cht_ref_type == 0 && (!in_array($model->chl_ref_type, [2, 3, 5]) ))
				{
					$notifyCom = new notificationWrapper();
					$notifyCom->sendVndMessagingForBooking($model->chl_id);

					\AppTokens::notifyDCOForAdminChat($model->chl_id);
				}
			}
		}
		else
		{
			$success = false;
			$errors	 = $model->getErrors();
		}
		skipAll:

		$return = ['success' => $success, 'errors' => $errors, 'id' => $chl_id];
		return $return;
	}

	/**
	 * setUpdateToggle
	 */
	public function setUpdateToggle($id, $type)
	{
		$success		 = false;
		$cht_ref_type	 = 0;
		$model			 = ChatLog::model()->findByPk($id);

		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->userId;
		$userType	 = $userInfo->userType;

		if($model->chatMsg->cht_owner_id == $userId)
		{
			$oldData = $model->attributes;
			switch($type)
			{
				case 1:
					$model->chl_customer_visible = ($model->chl_customer_visible > 0) ? 0 : 1;
					break;
				case 2:
					$model->chl_vendor_visible	 = ($model->chl_vendor_visible > 0) ? 0 : 1;
					break;
				case 3:
					$model->chl_driver_visible	 = ($model->chl_driver_visible > 0) ? 0 : 1;
					break;
			}
			$newData = $model->attributes;
			if($model->scenario == 'update')
			{
				$model->chl_log = $this->addLog($oldData, $newData, $id);
			}
			if($model->validate())
			{
				if($model->save())
				{
					if($model->chl_vendor_visible == 1 && $type == 2)
					{
						$notifyCom = new notificationWrapper();
						$notifyCom->sendVndMessagingForBooking($model->chl_id);
					}
					$cht_ref_type	 = $model->chatMsg->cht_ref_type;
					$success		 = true;
				}
			}
			else
			{
				$errors = $model->getErrors();
			}
		}
		return ['success' => $success, 'errors' => $errors, 'type' => $cht_ref_type];
	}

	/**
	 * getMessageLeftHtml
	 */
	public function getMessageLeftHtml()
	{
		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->userId;
		$userType	 = $userInfo->userType;

		$messageLeftHtml = '';
		$activeMsgList	 = $this->getActiveMessageByBkg();
		$ctr			 = 0;
		foreach($activeMsgList as $active)
		{
			$classAttReq = '';
			if(($active['cht_status'] == 0 || ($active['cht_status'] == 1 && $active['cht_unread_count_for_admin'] > 0)) && (trim($active['owner_name']) == '' || ($active['cht_unread_count_for_admin'] > 0 && $active['lastMsgTimeDiff'] >= 2)))
			{
				$active['owner_name']	 = '<b>Waiting for reply !!!</b>';
				$classAttReq			 = 'att_req';
			}

			if(trim($active['owner_name']) != '')
			{
				$active['owner_name'] .= "<br>";
			}

			$var			 = $active['bkg_booking_id'] . " (" . $active['cht_unread_count_for_admin'] . ") " . "<br> " . $active['owner_name'] . " (" . $active['created'] . ")" . "<br> " . $active['entity_name'];
			//	$messageLeftHtml .= '<li id=left_' . $active['cht_ref_id'] . ' onclick="$chat.setMessageBox(' . $active['cht_ref_id'] . ', ' . $active['cht_ref_type'] . ', ' . $active['cht_owner_id'] . ', ' . $userId . ', ' . $userType . ')" style="cursor:pointer;" class="' . $classAttReq . '">' . $var . '</li>';
			$messageLeftHtml .= '<li id=left_' . $active['cht_ref_id'] . ' onclick="$chat.setMessageBox(' . $active['cht_ref_id'] . ', ' . $active['cht_ref_type'] . ', ' . $active['cht_owner_id'] . ', ' . $userId . ', ' . $userType. ', 1' //. ', ' . $active['entity_id'] . ',' . $active['entity_type'] 
					. ')" style="cursor:pointer;" class="' . $classAttReq . '">' . $var . '</li>';
		}
		return $messageLeftHtml;
	}

	/**
	 * getMessageJsonByBkg
	 */
	public function getMessageJsonByBkg($refId, $refType = 0, $isVendor = 0, $isDriver = 0, $isConsumer = 0, $chtId = 0, $chlId = 0)
	{
		$userInfo = UserInfo::getInstance();

		$msgList	 = '';
		$lastChlId	 = 0;

		if($chtId > 0)
		{
		//	$lastChlId = Yii::app()->cache->get("chatMsg_" . $chtId);
		}

		if($refId > 0 && (($chtId == 0) || ($chtId > 0 && $lastChlId <> $chlId)))
		{
//			$msgList = $this->getMessagesByBkg($entity_id, $entity_type, $isVendor, $isDriver, $isConsumer, $chtId, $chlId);
			$msgList = $this->getMessagesByRef($refId, $refType, $userInfo, $isVendor, $isDriver, $isConsumer, $chtId, $chlId);
		}

		$leftMsgList = $this->getActiveMessageByBkg();

		return ['list' => $msgList, 'listLeftPanel' => $leftMsgList];
	}

	/**
	 * getMessagesByBkg
	 */
	public function getMessagesByBkg($entity_id, $entity_type = 0, $isVendor = 0, $isDriver = 0, $isConsumer = 0, $chtId = 0, $chlId = 0)
	{
		$sql = "SELECT
				chats.cht_id,
				chat_log.chl_id,
				chats.cht_ref_id,
				chats.cht_ref_type,
				chats.cht_status, 
				CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname) AS customer_name,
				chat_log.chl_msg,
				chat_log.chl_ref_type,
				chat_log.chl_driver_visible,
				chat_log.chl_vendor_visible,
				chat_log.chl_customer_visible,                    
				(
					CASE 
					WHEN chl_ref_type = 1 THEN 'Consumer' 
					WHEN chl_ref_type = 2 THEN 'Vendor' 
					WHEN chl_ref_type = 3 THEN 'Driver' 
					WHEN chl_ref_type = 4 THEN 'Admin' 
					WHEN chl_ref_type = 5 THEN 'Agent'
					END
				) AS ref_type,
                (
                    CASE 
					WHEN chl_ref_type = 1 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
                    WHEN chl_ref_type = 2 THEN vendors.vnd_name 
                    WHEN chl_ref_type = 3 THEN drv_name 
                    WHEN chl_ref_type = 4 THEN CONCAT(admins.adm_fname, ' ', admins.adm_lname) 
					WHEN chl_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_name,
				DATE_FORMAT(chat_log.chl_created, '%e-%b-%y, %l:%i %p') as chl_created,
				(
                    CASE 
						WHEN chl_ref_type = 1 THEN 'Customer Support' 
						WHEN chl_ref_type = 2 THEN 'Vendor Support' 
						WHEN chl_ref_type = 3 THEN 'Driver Support' 
						WHEN chl_ref_type = 4 THEN 'Partner Support' 
						WHEN chl_ref_type = 5 THEN 'Agent Support'
                    END
                ) as display_name, 
				chat_log.chl_admin_is_read, 
				chat_log.chl_type 
                FROM 				
				`chats`
				INNER JOIN `chat_log` ON  chat_log.chl_cht_id = chats.cht_id 
                LEFT JOIN `booking` ON booking.bkg_id = chats.cht_ref_id AND booking.bkg_active = 1 
                LEFT JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id 
                LEFT JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 
                LEFT JOIN `users` ON users.user_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 1 
				LEFT JOIN `vendors` ON vendors.vnd_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 2 and vendors.vnd_id= vendors.vnd_ref_code
				LEFT JOIN contact_profile cp on cp.cr_is_vendor = vendors.vnd_id and cp.cr_status =1
				LEFT JOIN contact on contact.ctt_id = cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code
                LEFT JOIN `drivers` ON drv_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 3 
				LEFT JOIN `admins` ON admins.adm_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 4 
                WHERE chats.cht_ref_id = $entity_id AND chats.cht_ref_type = $entity_type 
				AND chat_log.chl_active = 1 AND chats.cht_active = 1 ";

		$sql .= ($chtId > 0) ? " AND chat_log.chl_cht_id = " . $chtId : '';
		$sql .= ($chlId > 0) ? " AND chat_log.chl_id > " . $chlId : '';
		$sql .= ($isDriver > 0 && $entity_type == 0) ? " AND chat_log.chl_driver_visible=" . $isDriver : '';
		$sql .= ($isVendor > 0 && $entity_type == 0) ? " AND chat_log.chl_vendor_visible=" . $isVendor . " AND (bcb_vendor_id=vnd_id OR vnd_id IS NULL)" : '';

		$sql .= " ORDER BY chat_log.chl_id ASC";
		Logger::create('POST DATA ===========>: \n\t\t' . $sql . " == " . $userInfo->userType, CLogger::LEVEL_TRACE);
		return DBUtil::queryAll($sql);
	}

	/**
	 * getMessagesByRef
	 */
	public static function getMessagesByRef($refId, $refType = 0, $userInfo = null, $isVendor = 0, $isDriver = 0, $isConsumer = 0, $chtId = 0, $chlId = 0, $showPrev = false)
	{

		$where		 = '';
		$contactData = ContactProfile::getEntitybyUserId($userInfo->userId);
		if($userInfo->userType == UserInfo::TYPE_VENDOR)
		{
			$vendorId	 = ($contactData && $contactData['cr_is_vendor'] > 0) ? $contactData['cr_is_vendor'] : 0;
			$where		 = ($vendorId > 0) ? " AND chats.cht_vendor_id=$vendorId" : '';
		}

		if($userInfo->userType == UserInfo::TYPE_DRIVER)
		{
			$driverId	 = ($contactData && $contactData['cr_is_driver'] > 0) ? $contactData['cr_is_driver'] : 0;
			$where		 = ($driverId > 0) ? " AND chats.cht_driver_id=$driverId" : '';
		}

		$sql = "SELECT
				chats.cht_id,
				chat_log.chl_id,
				chats.cht_ref_id,
				chats.cht_ref_type,
				chats.cht_ref_id cht_entity_id,
				chats.cht_ref_type cht_entity_type,
				chats.cht_status, 
				chats.cht_start_date,
				CONCAT(booking_user.bkg_user_fname,' ',booking_user.bkg_user_lname) AS customer_name,
				chat_log.chl_msg,
				chat_log.chl_ref_id,
				chat_log.chl_ref_type,
				chat_log.chl_driver_visible,
				chat_log.chl_vendor_visible,
				chat_log.chl_customer_visible,                    
				(
					CASE 
					WHEN chl_ref_type = 1 THEN 'Consumer' 
					WHEN chl_ref_type = 2 THEN 'Vendor' 
					WHEN chl_ref_type = 3 THEN 'Driver' 
					WHEN chl_ref_type = 4 THEN 'Admin' 
					WHEN chl_ref_type = 5 THEN 'Agent'
					END
				) AS ref_type,
                (
                    CASE 
					WHEN chl_ref_type = 1 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
                    WHEN chl_ref_type = 2 THEN vendors.vnd_name 
                    WHEN chl_ref_type = 3 THEN drv_name 
                    WHEN chl_ref_type = 4 THEN CONCAT(admins.adm_fname, ' ', admins.adm_lname) 
					WHEN chl_ref_type = 5 THEN 'Agent'
					END
                ) AS ref_name,
				 
				chl_created,			 
				(
                    CASE 
						WHEN chl_ref_type = 1 THEN 'Customer Support' 
						WHEN chl_ref_type = 2 THEN 'Vendor Support' 
						WHEN chl_ref_type = 3 THEN 'Driver Support' 
						WHEN chl_ref_type = 4 THEN 'Partner Support' 
						WHEN chl_ref_type = 5 THEN 'Agent Support'
                    END
                ) as display_name, 
				chat_log.chl_admin_is_read, 
				chat_log.chl_type 
                FROM `chats`
				INNER JOIN `chat_log` ON  chat_log.chl_cht_id = chats.cht_id 
                LEFT JOIN `booking` ON booking.bkg_id = chats.cht_ref_id AND booking.bkg_active = 1 
                LEFT JOIN `booking_user` ON booking.bkg_id = booking_user.bui_bkg_id 
                LEFT JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking_cab.bcb_active = 1 
                LEFT JOIN `users` ON users.user_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 1 
				LEFT JOIN `vendors` ON vendors.vnd_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 2 and vendors.vnd_id= vendors.vnd_ref_code
				LEFT JOIN contact_profile cp on cp.cr_is_vendor = vendors.vnd_id and cp.cr_status =1
				LEFT JOIN contact on contact.ctt_id = cp.cr_contact_id and contact.ctt_active =1 and contact.ctt_id = contact.ctt_ref_code
                LEFT JOIN `drivers` ON drv_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 3 
				LEFT JOIN `admins` ON admins.adm_id = chat_log.chl_ref_id AND chat_log.chl_ref_type = 4 
                WHERE chat_log.chl_active = 1 AND chats.cht_active = 1 $where  ";
		$sql .= ($refId > 0) ? " AND chats.cht_ref_id = $refId AND chats.cht_ref_type = $refType" : '';
		$sql .= ($chtId > 0) ? " AND chat_log.chl_cht_id = " . $chtId : '';
		$sql .= ($chlId > 0) ? ($showPrev ? " AND chat_log.chl_id < $chlId " : " AND chat_log.chl_id > $chlId ") : '';

		$sql .= ($isDriver > 0 ) ? " AND chat_log.chl_driver_visible=" . $isDriver : '';
		$sql .= ($isVendor > 0 ) ? " AND chat_log.chl_vendor_visible=" . $isVendor . " AND (bcb_vendor_id=vnd_id OR vnd_id IS NULL)" : '';

		$sql .= " ORDER BY chat_log.chl_id ASC";
		return DBUtil::queryAll($sql);
	}

	/**
	 * 
	 * getActiveMessageByBkg
	 */
	public function getActiveMessageByBkg()
	{
		$sql1 = "SELECT 
				chats.cht_ref_id, 
				chats.cht_ref_type, 
				chats.cht_unread_count_for_admin,
				chats.cht_owner_id, 
				chats.cht_status, 
				(
				  CASE 
				  WHEN chats.cht_ref_type = 0 THEN booking.bkg_booking_id 
				 # WHEN chats.cht_ref_type = 1 THEN users.user_id 
				  WHEN chats.cht_ref_type = 2 THEN vnd.vnd_id 
				  WHEN chats.cht_ref_type = 3 THEN drv_id 
				  END
				) AS entity_id, 
				(
				  CASE 
					WHEN chats.cht_ref_type = 0 THEN booking.bkg_booking_id 
					#WHEN chats.cht_ref_type = 1 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
					WHEN chats.cht_ref_type = 2 THEN vnd.vnd_name 
					WHEN chats.cht_ref_type = 3 THEN drv_name 
					END
				) AS entity_name, 
				booking.bkg_booking_id, 
				cht_unread_count_for_admin AS cnt, 
				DATE_FORMAT(cht_last_date, '%d-%b-%y %l:%i %p') AS created,
				TIMESTAMPDIFF(MINUTE, cht_last_date, NOW()) AS lastMsgTimeDiff, 
				CONCAT(IFNULL(adm_fname, ''), ' ', IFNULL(adm_lname, '')) AS owner_name 
				FROM 
				`chats` 
				LEFT JOIN `booking` ON booking.bkg_id = chats.cht_ref_id AND chats.cht_ref_type = 0 AND booking.bkg_active = 1 
				#LEFT JOIN `users` ON users.user_id = chats.cht_ref_id AND chats.cht_ref_type = 1 AND users.usr_active = 1 
				LEFT JOIN `vendors` ON vendors.vnd_id = chats.cht_vendor_id  AND vendors.vnd_active = 1 
				LEFT JOIN `vendors` vnd ON vnd.vnd_id = vendors.vnd_ref_code
				LEFT JOIN `drivers` ON drv_id = chats.cht_driver_id AND drv_active = 1 
				LEFT JOIN `admins` ON admins.adm_id = chats.cht_owner_id 
				WHERE 
				chats.cht_active = 1 AND (cht_last_date > (NOW() - INTERVAL 24 HOUR)) 
				ORDER BY 
				cht_last_date DESC";

		$sql = "SELECT 
				chats.cht_ref_id, 
				chats.cht_ref_type, 
				chats.cht_unread_count_for_admin,
				chats.cht_owner_id, 
				chats.cht_status, 
				(
				  CASE 
				  WHEN chats.cht_vendor_id > 0 THEN vnd.vnd_id  
				  WHEN chats.cht_driver_id >0  THEN drv_id 
                  WHEN chats.cht_consumer_id >0  THEN users.user_id  
				  WHEN chats.cht_admin_id >0  THEN adm_id  
				  END
				) AS entity_id, 
				(
				  CASE 
				  WHEN chats.cht_vendor_id > 0 THEN 2 
				  WHEN chats.cht_driver_id >0  THEN 3 
                  WHEN chats.cht_consumer_id >0 THEN 1                     
				  END
				) AS entity_type, 
				(
				  CASE  
					WHEN chats.cht_consumer_id >0 THEN CONCAT(users.usr_name, ' ', users.usr_lname) 
					WHEN chats.cht_vendor_id > 0 THEN vnd.vnd_name                     
					WHEN chats.cht_driver_id >0  THEN drv_name 
					WHEN chats.cht_admin_id >0 THEN CONCAT(adm_fname, ' ', adm_lname) 
					END
				) AS entity_name, 
				booking.bkg_booking_id, 
				cht_unread_count_for_admin AS cnt, 
				DATE_FORMAT(cht_last_date, '%d-%b-%y %l:%i %p') AS created,
				TIMESTAMPDIFF(MINUTE, cht_last_date, NOW()) AS lastMsgTimeDiff, 
				CONCAT(IFNULL(adm_fname, ''), ' ', IFNULL(adm_lname, '')) AS owner_name 
				FROM `chats` 
				LEFT JOIN `booking` ON booking.bkg_id = chats.cht_ref_id AND chats.cht_ref_type = 0 AND booking.bkg_active = 1 
				LEFT JOIN `users` ON users.user_id = chats.cht_consumer_id AND users.usr_active = 1 
				LEFT JOIN `vendors` ON vendors.vnd_id = chats.cht_vendor_id  AND vendors.vnd_active = 1 
				LEFT JOIN `vendors` vnd ON vnd.vnd_id = vendors.vnd_ref_code
				LEFT JOIN `drivers` ON drv_id = chats.cht_driver_id AND drv_active = 1 
				LEFT JOIN `admins` ON admins.adm_id = chats.cht_admin_id 
				WHERE (chats.cht_ref_id > 0 ) AND
				chats.cht_active = 1 
				AND (cht_last_date > (NOW() - INTERVAL 24 HOUR)) 
				GROUP BY chats.cht_ref_id,entity_id,chats.cht_entity_id
				ORDER BY cht_last_date DESC";

		return DBUtil::queryAll($sql);
	}

	/**
	 * addLog
	 */
	public function addLog($oldData, $newData, $id)
	{
		$model = ChatLog::model()->findByPk($id);
		if($oldData)
		{
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$remark			 = $model->chl_log;
			$dt				 = date('Y-m-d H:i:s');
			$user			 = Yii::app()->user->getId();
			//if ($remark) {
			if(is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
			}
			else if(is_array($remark))
			{
				$newcomm = $remark;
			}
			if($newcomm == false)
			{
				$newcomm = array();
			}
			if(count($getDifference) > 0)
			{
				while(count($newcomm) >= 50)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));

				$log = CJSON::encode($newcomm);
				return $log;
			}
		}
		return $remark;
	}

	/**
	 * makeAdminread
	 */
	public function makeAdminread($refId, $refType)
	{
		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->userId;
		#$userType = $userInfo->userType;

		$sql	 = "SELECT cht_id, cht_unread_count_for_admin as val FROM `chats` WHERE cht_ref_id = $refId AND cht_ref_type = $refType AND cht_owner_id = $userId AND cht_active = 1";
		$data	 = DBUtil::queryAll($sql);

		foreach($data as $value)
		{
			if($value['val'] > 0)
			{
				$sql1 = " UPDATE chats SET cht_unread_count_for_admin = 0 WHERE cht_ref_id = $refId AND cht_ref_type = $refType";
				DBUtil::command($sql1)->execute();

				$sql2 = " UPDATE chat_log SET chl_admin_is_read = 1 WHERE chl_admin_is_read = 0 AND chl_cht_id = '" . $value['cht_id'] . "'";
				DBUtil::command($sql2)->execute();
			}
		}
	}
}
