<?php

class ChatController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $pageTitle;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('index', 'updateToggle', 'chatLog', 'chatLogV1', 'takeover', 'details'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(''),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * actionUpdateToggle
	 */
	public function actionUpdateToggle()
	{
		$success	 = false;
		$entityId	 = Yii::app()->request->getParam('entityId');
		$entityType	 = Yii::app()->request->getParam('entityType');
		$chlId		 = Yii::app()->request->getParam('chlId');

		$model	 = new ChatLog();
		$result	 = $model->setUpdateToggle($chlId, $entityType);

		if($result['success'] == true)
		{
			$success = true;
		}
		$return = ['success' => $success, 'entityId' => $entityId, 'entityType' => $entityType, 'result' => $result];

		echo json_encode($return);
		Yii::app()->end();
	}

	/**
	 * actionIndex
	 */
	public function actionIndex()
	{
		$this->pageTitle = "Gozo Messaging";

		$refId	 = Yii::app()->request->getParam('bookingID');
		$refType = Yii::app()->request->getParam('entityType');

		$userInfo = UserInfo::getInstance();

		$model		 = new ChatLog();
		$chatModel	 = new Chats();

		if(isset($_REQUEST['ChatLog']))
		{

			try
			{
				$arr = Yii::app()->request->getParam('ChatLog');

				// Posted Data
				$cht_id				 = $arr['cht_id'];
//				$refId				 = ($refId > 0) ? $refId : $arr['cht_entity_id'];
//				$refType			 = ($refType > 0) ? $refType : $arr['cht_entity_type'];
				$refId				 = ($refId > 0) ? $refId : $arr['cht_ref_id'];
				$refType			 = ($refType > 0) ? $refType : $arr['cht_ref_type'];
				$message			 = $arr['chl_msg'];
				$is_driver_visible	 = $arr['chl_driver_visible'];
				$is_vendor_visible	 = $arr['chl_vendor_visible'];
				$is_customer_visible = $arr['chl_customer_visible'];

				// Data
				$arrData			 = array();
				$arrData['cht_id']	 = $cht_id;
				$transaction		 = DBUtil::beginTransaction();
				// Adding Message
//				$result	 = $model->addMessage($refId, $message, $userInfo, $is_driver_visible, $is_vendor_visible, $is_customer_visible, $refType, $arrData);
				$result				 = ChatLog::addMessageV1($message, $refId, $refType, $userInfo, $is_driver_visible, $is_vendor_visible, $is_customer_visible, $arrData);
				DBUtil::commitTransaction($transaction);
				$success			 = $result['success'];
				$errors				 = $result['errors'];

				if($result['success'] == true)
				{
					$jsonMessaging = ChatLog::model()->getMessageJsonByBkg($refId, $refType);
				}
			}
			catch(Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
			}
			if(Yii::app()->request->isAjaxRequest)
			{
				$result = [];
				foreach($errors as $attribute => $err)
				{
					$result[CHtml::activeId($model, $attribute)] = $err;
				}
				$data = ['success' => $success, 'errors' => $result, 'json' => json_encode($jsonMessaging['list']), 'jsonLeft' => json_encode($jsonMessaging['listLeftPanel'])];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		else
		{
			$messageLeftHtml = ChatLog::model()->getMessageLeftHtml();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('index', array('model' => $model, 'chatModel' => $chatModel, 'messageLeftHtml' => $messageLeftHtml, 'refId' => $refId, 'refType' => $refType, 'userInfo' => $userInfo), false, $outputJs);
	}

	public function action1Index()
	{
		$this->pageTitle = "Gozo Messaging";

		$entity_id	 = Yii::app()->request->getParam('bookingID');
		$entity_type = Yii::app()->request->getParam('entityType');

		$userInfo = UserInfo::getInstance();

		$model		 = new ChatLog();
		$chatModel	 = new Chats();

		if(isset($_REQUEST['ChatLog']))
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$arr = Yii::app()->request->getParam('ChatLog');

				// Posted Data
				$cht_id				 = $arr['cht_id'];
				$entity_id			 = ($entity_id > 0) ? $entity_id : $arr['cht_entity_id'];
				$entity_type		 = ($entity_type > 0) ? $entity_type : $arr['cht_entity_type'];
				$message			 = $arr['chl_msg'];
				$is_driver_visible	 = $arr['chl_driver_visible'];
				$is_vendor_visible	 = $arr['chl_vendor_visible'];
				$is_customer_visible = $arr['chl_customer_visible'];

				// Data
				$arrData			 = array();
				$arrData['cht_id']	 = $cht_id;

				// Adding Message
				$result = $model->addMessage($entity_id, $message, $userInfo, $is_driver_visible, $is_vendor_visible, $is_customer_visible, $entity_type, $arrData);

				$success = $result['success'];
				$errors	 = $result['errors'];

				if($result['success'] == true)
				{
					$jsonMessaging = ChatLog::model()->getMessageJsonByBkg($entity_id, $entity_type);
					DBUtil::commitTransaction($transaction);
				}
			}
			catch(Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
			}
			if(Yii::app()->request->isAjaxRequest)
			{
				$result = [];
				foreach($errors as $attribute => $err)
				{
					$result[CHtml::activeId($model, $attribute)] = $err;
				}
				$data = ['success' => $success, 'errors' => $result, 'json' => json_encode($jsonMessaging['list']), 'jsonLeft' => json_encode($jsonMessaging['listLeftPanel'])];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		else
		{
			$messageLeftHtml = ChatLog::model()->getMessageLeftHtml();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('index', array('model' => $model, 'chatModel' => $chatModel, 'messageLeftHtml' => $messageLeftHtml, 'entityId' => $entity_id, 'entityType' => $entity_type, 'userInfo' => $userInfo), false, $outputJs);
	}

	/**
	 * actionChatLog
	 */
	public function actionChatLog1()
	{
		$this->pageTitle = "Chat Log";
		$entity_id		 = Yii::app()->request->getParam('entityId');
		$entity_type	 = Yii::app()->request->getParam('entityType');
		$chtId			 = Yii::app()->request->getParam('chtId');
		$chlId			 = Yii::app()->request->getParam('chlId');
		if($entity_id)
		{
			/* @var $model ChatLog */
			$model = new ChatLog();

			// User Info
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$userType	 = $userInfo->userType;

			// Booking Details
			$topDetails = $this->topDetails();

			// Chat Details
			$chatDetails = Chats::model()->chatDetails($entity_id, $entity_type);

			// Chat Log
			$jsonMessaging = ChatLog::model()->getMessageJsonByBkg($entity_id, $entity_type, 0, 0, 0, $chtId, $chlId);

			$success = true;

			$data = ['success' => $success, /* 'model' => $model, */ 'json' => json_encode($jsonMessaging['list']), 'jsonLeft' => json_encode($jsonMessaging['listLeftPanel']), 'chatDetails' => $chatDetails, 'userId' => $userId, 'userType' => $userType, 'topDetails' => $topDetails];

			// Mark Read
			if($entity_id > 0)
			{
				ChatLog::model()->makeAdminread($entity_id, $entity_type);
			}
		}
		else
		{
			$success = false;
			$data	 = ['success' => $success];
		}
		echo json_encode($data);
	}

	/**
	 * actionChatLog
	 */
	public function actionChatLog()
	{
		$this->pageTitle = "Chat Log";
		$refId			 = Yii::app()->request->getParam('entityId');
		$refType		 = Yii::app()->request->getParam('entityType');
		$chtId			 = Yii::app()->request->getParam('chtId');
		$chlId			 = Yii::app()->request->getParam('chlId');
		$isClicked		 = Yii::app()->request->getParam('isClicked', 0);
		if($refId)
		{
			/* @var $model ChatLog */
			$model = new ChatLog();

			// User Info
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$userType	 = $userInfo->userType;

			// Booking Details
			$topDetails = $this->topDetails();

			// Chat Details
			$chatDetails = Chats::model()->chatDetails($refId, $refType);

			// Chat Log
			$jsonMessaging = ChatLog::model()->getMessageJsonByBkg($refId, $refType, 0, 0, 0, $chtId, $chlId);

			$success = true;

			$data = ['success' => $success, 'model' => $model, 'json' => json_encode($jsonMessaging['list']), 'jsonLeft' => json_encode($jsonMessaging['listLeftPanel']), 'chatDetails' => $chatDetails, 'userId' => $userId, 'userType' => $userType, 'topDetails' => $topDetails];

			// Mark Read
			if($refId > 0 && $isClicked)
			{
				ChatLog::model()->makeAdminread($refId, $refType);
			}
		}
		else
		{
			$leftMsgList = ChatLog::model()->getActiveMessageByBkg();
			$success	 = false;
			$data		 = ['success' => $success, 'jsonLeft' => json_encode($leftMsgList)];
		}
		echo json_encode($data);
	}

	/**
	 * actionTakeover
	 */
	public function actionTakeover()
	{
		$success		 = false;
		$chtId			 = Yii::app()->request->getParam('chtId');
		$refId			 = Yii::app()->request->getParam('entityId');
		$refType		 = Yii::app()->request->getParam('entityType');
		$ownerShipAct	 = Yii::app()->request->getParam('ownerShipAct');

		// User Info
		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->userId;

		// Chat Ended
		if($ownerShipAct == 3)
		{
			$userId = 0;
		}

		// Chat Data
		$arrChatData				 = array();
		$arrChatData['chtId']		 = $chtId;
		$arrChatData['userId']		 = UserInfo::getEntityId();
		$arrChatData['ownerShipAct'] = $ownerShipAct;

		// Updating Chat Ownership
		$model	 = new Chats();
		$result	 = $model->updateOwner($refId, $refType, $arrChatData);
		if($result == 1)
		{
			$userInfo = UserInfo::getInstance();

			$arrData			 = array();
			$arrData['chl_type'] = $ownerShipAct;

			$message = '';
			if($ownerShipAct == 1)
			{
				$message .= "Joined the chat.";
			}
			elseif($ownerShipAct == 2)
			{
				$message .= "Taken over the chat.";
			}
			elseif($ownerShipAct == 3)
			{
				$message .= "Ended the chat.";
			}

//			$chlModel	 = new ChatLog();
			//	$result		 = $chlModel->addMessage($entityId, $message, $userInfo, 0, 0, 0, $entityType, $arrData);
			$result = ChatLog::addMessageV1($message, $refId, $refType, $userInfo, 0, 0, 0, $arrData);

			$success = true;
		}

		$return = ['success' => $success, 'entityId' => $refId, 'entityType' => $refType, 'ownerId' => $userId, 'result' => $result];
		echo json_encode($return);
		Yii::app()->end();
	}

	/**
	 * actionDetails
	 */
	public function actionDetails()
	{
		$success	 = false;
		$entityId	 = Yii::app()->request->getParam('entityId');
		$entityType	 = Yii::app()->request->getParam('entityType', 0);

		$userInfo	 = UserInfo::getInstance();
		$userId		 = $userInfo->userId;
		$userType	 = $userInfo->userType;

		$chatDetails = Chats::model()->chatDetails($entityId, $entityType);

		if($chatDetails)
		{
			$success = true;
		}

		$return = ['success' => $success, 'entityId' => $entityId, 'entityType' => $entityType, 'userId' => $userId, 'userType' => $userType, 'chatDetails' => $chatDetails];
		echo json_encode($return);
		Yii::app()->end();
	}

	/**
	 * topDetails
	 */
	protected function topDetails()
	{
		$topDetails = false;

		$entityId	 = Yii::app()->request->getParam('entityId');
		$entityType	 = Yii::app()->request->getParam('entityType');

		if($entityType == 0)
		{
			// Booking Details
			$bookingDetails						 = Booking::model()->getDetailsbyId($entityId);
			$bookingDetails['bkg_trip_duration'] = Filter::getDurationbyMinute($bookingDetails['bkg_trip_duration']);
			$bookingDetails['details_link']		 = Yii::app()->createUrl("admin/booking/view", array("id" => $entityId));

			$topDetails = $bookingDetails;
		}

		return $topDetails;
	}
}
