<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ContactController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

//public $layout = '//layouts/column2';

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
			$ri	 = array("/storeCallBackData", "/cancelCMB", "/checkCMBStatus");
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.post.storeCallBackData.render', function () {
			return $this->renderJSON($this->storeCallBackData());
		});

		$this->onRest('req.post.sendCallBackData.render', function () {
			return $this->renderJSON($this->sendCallBackData());
		});

		$this->onRest('req.post.checkCallBackData.render', function () {
			return $this->renderJSON($this->checkCallBackData());
		});

		$this->onRest('req.post.checkCMBStatus_v1.render', function () {
			return $this->renderJSON($this->checkCMBStatus_v1());
		});

		$this->onRest('req.post.cancelCMB_v1.render', function () {
			return $this->renderJSON($this->cancelCMB_v1());
		});
		$this->onRest('req.get.checkCMBStatus.render', function () {
			return $this->renderJSON($this->checkCMBStatus());
		});

		$this->onRest('req.get.cancelCMB.render', function () {
			return $this->renderJSON($this->cancelCMB());
		});
	}

	public function checkCMBStatus_v1()
	{
		$returnSet = new ReturnSet();

		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId	 = UserInfo::getUserId();
			$success = false;
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$refType	 = $jsonObj['refType'];
			$contactId	 = ContactProfile::getByEntityId($userId, $entityType);

			$followupId = ServiceCallQueue::getIdByUserId($userId, $refType);
			if (!$followupId)
			{
				$data = ['active' => 0];
				goto skipAll;
			}
			$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
			$queueData		 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
			$queNo			 = $queueData['queNo'];
			$waitTime		 = $queueData['waitTime'];
			$contactNumber	 = $fpModel->scq_to_be_followed_up_with_value;
			$data			 = [];
			$data			 = ['active' => 0];
			if ($followupId > 0)
			{
				$data = [
					'followupId'	 => (int) $followupId,
					'queNo'			 => $queNo,
					'followupCode'	 => $fpModel->scq_unique_code,
					'waitTime'		 => $waitTime, 'active'		 => 1];
			}
			skipAll:
			$success = true;
			$returnSet->setData($data);
			$returnSet->setStatus($success);
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function checkCallBackData()
	{
		$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$returnSet = new ReturnSet();
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId	 = UserInfo::getUserId();
			$success = false;
			AppTokens::validateToken($token);
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$refType	 = $jsonObj['refType'];
			$contactId	 = ContactProfile::getByEntityId($userId, $entityType);
			$followupId = ServiceCallQueue::getIdByUserId($userId, $refType);
			if (!$followupId)
			{
				$success = true;
				$data = ['active' => 0];
				$message = 'We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.';
				$response = \Beans\contact\Scq::setResponseData($data, $message);
				goto skipAll;
			}
			$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
			$queueData		 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
			$queNo			 = $queueData['queNo'];
			$waitTime		 = $queueData['waitTime'];
			$contactNumber	 = $fpModel->scq_to_be_followed_up_with_value;
			$data			 = [];
			$data			 = ['active' => 0];
			if ($followupId > 0)
			{
				$data = [
					'followupId'	 => (int) $followupId,
					'queNo'			 => $queNo,
					'followupCode'	 => $fpModel->scq_unique_code,
					'waitTime'		 => $waitTime, 'active'		 => 1];
				$message = 'We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.';	
			}
			$success = true;
			$response = new \Beans\contact\Scq();
			$response = \Beans\contact\Scq::setResponseData($data, $message);
			skipAll:
			$returnSet->setStatus($success);
			$returnSet->setData($response);
			
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}



	public function storeCallBackData()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data	 = CJSON::decode($jsonval, true);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['remarks'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId									 = UserInfo::getUserId();
			$entityType									 = UserInfo::TYPE_CONSUMER;
			$model										 = new ServiceCallQueue();
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			$model->scq_follow_up_queue_type			 = $data['refType'];
			$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
			$model->scq_creation_comments				 = trim($data['remarks']);
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = $entityId;
			$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
			if (isset($data['refId']) && trim($data['refId']) != '')
			{
				$model->scq_related_bkg_id = $data['refId'];
			}
			$returnSet = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_CONSUMER_APP);
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function sendCallBackData()
	{
		$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$returnSet = new ReturnSet();
			$jsonval = Yii::app()->request->rawBody;
			$data	 = CJSON::decode($jsonval, true);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			AppTokens::validateToken($token);
			$userId = UserInfo::getUserId();
			if (empty(trim($data['remarks'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId									 = $userId;
			$entityType									 = UserInfo::TYPE_CONSUMER;
			$model										 = new ServiceCallQueue();
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			$model->scq_follow_up_queue_type			 = $data['refType'];
			$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
			$model->scq_creation_comments				 = trim($data['remarks']);
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = $entityId;
			$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
			if (isset($data['refId']) && trim($data['refId']) != '')
			{
				$model->scq_related_bkg_id = $data['refId'];
			}
			$returnQueueSet = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_CONSUMER_APP);
			if ($returnQueueSet->getStatus())
			{
				$data = $returnQueueSet->getData();
				$message	 = "We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.";
				$response = new \Beans\contact\Scq();
				$response = \Beans\contact\Scq::setResponseData($data, $message);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setErrors($returnQueueSet->getErrors(),$returnQueueSet->getErrorCode());
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function cancelCMB_v1()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqId	 = $jsonObj['scqId'];
			$returnSet->setMessage("no callback scheduled for this user");
			//	$row	 = ServiceCallQueue::deactivateAllEntry($userId);
			$row	 = ServiceCallQueue::deactivateById($userId, $scqId);
			if ($row > 0)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("call back cancelled successfully");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function checkCMBStatus()
	{
		$returnSet = new ReturnSet();
		//	$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$userId	 = UserInfo::getUserId();
			$success = false;
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$contactId	 = ContactProfile::getByEntityId($userId, $entityType);
			$followupId	 = ServiceCallQueue::checkActiveCallback($userId);
			$data		 = [];
			$data		 = ['active' => 0];
			if ($followupId > 0)
			{
				$data	 = ['followupId' => $followupId, 'active' => 1];
				$success = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function cancelCMB()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$userId = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$returnSet->setMessage("no callback scheduled for this user");
			$row = ServiceCallQueue::deactivateAllEntry($userId);
			if ($row > 0)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("call back cancelled successfully");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
