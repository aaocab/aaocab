<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ChatController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function actions()
	{
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
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
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();

			foreach($ri as $value)
			{
				if(strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.add.render', function () {
			return $this->renderJSON($this->sync());
		});
		$this->onRest('req.post.sync.render', function () {
			return $this->renderJSON($this->sync());
		});
		$this->onRest('req.post.prev.render', function () {
			return $this->renderJSON($this->prev());
		});
	}

	public function sync()
	{
		$returnSet	 = new ReturnSet();
		$rawData	 = Yii::app()->request->rawBody;
		$transaction = null;
		try
		{
			if($rawData == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create('Request ===========>: \n\t\t' . " == " . $rawData, CLogger::LEVEL_TRACE);

			$userInfo	 = UserInfo::getInstance();
			$isDriver	 = 1;
			$isConsumer	 = 0;
			$isVendor	 = 0;
			$arrData	 = array();

			if($this->getVendorId(false) > 0 && $userInfo->userType == UserInfo::TYPE_VENDOR)
			{
				$arrData['source']	 = 'vendor';
				$isDriver			 = 0;
				$isVendor			 = 1;
			}
			if($this->getDriverId(false) > 0 && $userInfo->userType == UserInfo::TYPE_DRIVER)
			{
				$arrData['source']	 = 'driver';
				$isDriver			 = 1;
				$isVendor			 = 0;
			}

			$reqObj = CJSON::decode($rawData, false);

			$jsonMapper	 = new JsonMapper();
			/** @var Beans\common\Chat() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\common\Chat());

			$refId	 = $obj->room->refId;
			$refType = Chats::REF_TYPE_BOOKING;

			$message = $obj->message;
			$chtId	 = $obj->room->id | 0;
			$chlId	 = $obj->id | 0;

			if(isset($message) && trim($message) != '' && (in_array($userInfo->userType, [UserInfo::TYPE_VENDOR, UserInfo::TYPE_DRIVER])))
			{
				$transaction = DBUtil::beginTransaction();
				$result		 = ChatLog::addMessageV1($message, $refId, $refType, $userInfo, $isDriver, $isVendor, $isConsumer, $arrData);
				if($result['success'] == true)
				{
					$success = true;
					DBUtil::commitTransaction($transaction);
					if($reqObj->lastMessage)
					{
						$chlId = $result['id'] - 1;
					}
				}
				else if($result['success'] == false)
				{
					$errors = $result['errors'];
					throw new Exception("Not Validated: Errors=> " . json_encode($errors), ReturnSet::ERROR_VALIDATION);
				}
			}
			$msgList = ChatLog::getMessagesByRef($refId, $refType, $userInfo, $isVendor, $isDriver, $isConsumer, $chtId, $chlId);
			if(count($msgList) > 0)
			{
				$success = true;
			}
			else
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$chatList = \Beans\common\ChatRoom::getList($msgList);

			$returnSet->setStatus($success);
			$returnSet->setData($chatList);
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		Logger::create('Response ===========>: \n\t\t' . " == " . json_encode($returnSet->getData()), CLogger::LEVEL_TRACE);

		return $returnSet;
	}

	public function prev()
	{
		$returnSet	 = new ReturnSet();
		$rawData	 = Yii::app()->request->rawBody;
		$transaction = null;
		try
		{
			if($rawData == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$userInfo			 = UserInfo::getInstance();
			$isDriver			 = 1;
			$isConsumer			 = 0;
			$isVendor			 = 0;
			$userInfo->userType	 = UserInfo::TYPE_DRIVER;
			if($this->getVendorId(false) > 0)
			{
				$isDriver			 = 0;
				$isVendor			 = 1;
				$userInfo->userType	 = UserInfo::TYPE_VENDOR;
			}

			$reqObj = CJSON::decode($rawData, false);

			$jsonMapper	 = new JsonMapper();
			/** @var Beans\common\Chat() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\common\Chat());

			$refId	 = $obj->room->refId;
			$refType = Chats::REF_TYPE_BOOKING;

			$chtId	 = $obj->room->id | 0;
			$chlId	 = $obj->id | 0;

			$msgList = ChatLog::getMessagesByRef($refId, $refType, $userInfo, $isVendor, $isDriver, $isConsumer, $chtId, $chlId, true);
			if(count($msgList) > 0)
			{
				$success = true;
			}
			else
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$chatList = \Beans\common\ChatRoom::getList($msgList);

			$returnSet->setStatus($success);
			$returnSet->setData($chatList);
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}
}
