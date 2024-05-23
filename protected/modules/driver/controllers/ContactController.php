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
			$ri	 = array("allAdmins", "/storeCMB", '/checkCMBStatus', '/storecmbUnsignedDriver', '/checkcmbStatusUnsignedDriver', '/cancelcmbUnsignedDriver');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.post.storeCMB.render', function () {
			return $this->renderJSON($this->storeCMB());
		});

		$this->onRest('req.post.checkCMBStatus.render', function () {
			return $this->renderJSON($this->checkCMBStatus());
		});
		$this->onRest('req.post.cancelCMB.render', function () {
			return $this->renderJSON($this->cancelCMB());
		});
		$this->onRest('req.post.updateVaccineStatus.render', function () {
			return $this->renderJSON($this->updateVaccineStatus());
		});
		$this->onRest('req.post.storecmbUnsignedDriver.render', function () {
			return $this->renderJSON($this->storecmbUnsignedDriver());
		});
		$this->onRest('req.post.checkcmbStatusUnsignedDriver.render', function () {
			return $this->renderJSON($this->checkcmbStatusUnsignedDriver());
		});
		$this->onRest('req.post.cancelcmbUnsignedDriver.render', function () {
			return $this->renderJSON($this->cancelcmbUnsignedDriver());
		});
		$this->onRest('req.get.allAdmins.render', function () {
			$driverModel = Admins::model()->getAdminlistReact();
			if ($driverModel != [])
			{
				$success = true;
				$error	 = null;
			}
			else
			{
				$success = false;
				$error	 = "Error occured while fetching list";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $driverModel
			]);
		});
	}

	public function storeCMB()
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
			$entityId	 = UserInfo::getEntityId();
			$entityType	 = UserInfo::TYPE_DRIVER;
			if (!$entityId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['desc'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$followupId = ServiceCallQueue::getIdByUserId(UserInfo::getUserId(), 6);
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$returnSet->setData(['followupId'	 => $followupId, 'followupCode'	 => $followupCode, 'queNo'			 => $queNo, 'waitTime'		 => $waitTime,
					'active'		 => 1]);
				$returnSet->setStatus(true);
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training and quality assurance purposes.');
			}
			else
			{
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DRIVER;
				$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				if (isset($data['refId']) && trim($data['refId']) != '')
				{
					$model->scq_related_bkg_id = $data['refId'];
				}
				$platform	 = ServiceCallQueue::PLATFORM_DRIVER_APP;
				$returnSet	 = ServiceCallQueue::model()->create($model, $entityType, $platform);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage('We will call you back shortly . All the calls will be recorded for training and quality assurance purposes.');
				}
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
		try
		{

			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$entityId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityType	 = UserInfo::TYPE_DRIVER;
			$refType	 = ServiceCallQueue::TYPE_DRIVER;
			$scqType	 = $jsonObj['scqType'];
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType);
			$success	 = false;
			$data		 = [];
			$data		 = ['active' => 0];
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId'	 => $followupId, 'followupCode'	 => $followupCode, 'queNo'			 => $queNo, 'waitTime'		 => $waitTime,
					'active'		 => 1];
				$success		 = true;
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training and quality assurance purposes.');
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
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$vendorId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqId	 = $jsonObj['scqId'];
			$row	 = ServiceCallQueue::deactivateById($userId, $scqId);
			if ($row > 0)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Call back cancelled successfully");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function updateVaccineStatus()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$entityId	 = UserInfo::getEntityId();
			$entityType	 = UserInfo::TYPE_DRIVER;
			$userId		 = UserInfo::getUserId();
			if (!$entityId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);

			$contactId = ContactProfile::getByEntityId($entityId, $entityType);
			if ($contactId > 0)
			{
				$returnSet = Contact::updateVaccineStaus($contactId, $jsonObj);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function storecmbUnsignedDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data	 = CJSON::decode($jsonval, true);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($data['phone']['number']))
			{
				throw new Exception("Phone Number is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($data['license']))
			{
				throw new Exception("Driving License is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['desc'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}

			$contactId = Contact::getIdByPhoneOrDL($data['license'], $data['phone']['number']);
			if (empty($contactId))
			{
				throw new Exception("Please register as Driver with us. Unable to get driver details.", ReturnSet::ERROR_INVALID_DATA);
			}
			$drvContactData	 = ContactProfile::getDriverData($contactId);
			$entityId		 = $drvContactData["entityId"];
			if (empty($entityId))
			{
				throw new Exception("Unable to get driver details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = $drvContactData["consumerId"];
			if (empty($userId))
			{
				throw new Exception("Unable to get driver details. Please enter registered Phone Number.", ReturnSet::ERROR_INVALID_DATA);
			}
			$followupId = ServiceCallQueue::getIdByUserId($userId, 6);
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$returnSet->setData(['followupId'	 => $followupId, 'followupCode'	 => $followupCode, 'queNo'			 => $queNo, 'waitTime'		 => $waitTime,
					'active'		 => 1]);
				$returnSet->setStatus(true);
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training and quality assurance purposes.');
			}
			else
			{
				$entityType									 = UserInfo::TYPE_DRIVER;
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_DRIVER;
				$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				$model->scq_created_by_uid					 = $userId;
				$platform									 = ServiceCallQueue::PLATFORM_DRIVER_APP;
				$returnSet									 = ServiceCallQueue::model()->create($model, $entityType, $platform);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage('We will call you back shortly . All the calls will be recorded for training and quality assurance purposes.');
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function checkcmbStatusUnsignedDriver()
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
			if (empty($jsonObj['scqType']))
			{
				throw new Exception("Invalid Call Data. ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($jsonObj['phone']['number']))
			{
				throw new Exception("Phone Number is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($jsonObj['license']))
			{
				throw new Exception("Driving License is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$contactId = Contact::getIdByPhoneOrDL($jsonObj['license'], $jsonObj['phone']['number']);
			if (empty($contactId))
			{
				throw new Exception("Please register as Driver with us. Unable to get driver details.", ReturnSet::ERROR_INVALID_DATA);
			}
			$drvContactData	 = ContactProfile::getDriverData($contactId);
			$entityId		 = $drvContactData["entityId"];
			if (empty($entityId))
			{
				throw new Exception("Unable to get driver details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = $drvContactData["consumerId"];
			if (empty($userId))
			{
				throw new Exception("Unable to get driver details. Please enter registered Phone Number.", ReturnSet::ERROR_INVALID_DATA);
			}

			$entityType	 = UserInfo::TYPE_DRIVER;
			$refType	 = ServiceCallQueue::TYPE_DRIVER;
			$scqType	 = $jsonObj['scqType'];
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType);
			$success	 = true;
			$data		 = [];
			$data		 = ['active' => 0];
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'waitTime' => $waitTime, 'active' => 1];
				$returnSet->setMessage('We will call you back shortly . All the calls will be recorded for training and quality assurance purposes.');
			}
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function cancelcmbUnsignedDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj) || empty($jsonObj['followupId']))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqId			 = $jsonObj['followupId'];
			$followupDetails = ServiceCallQueue::detail($scqId);
			if (empty($followupDetails))
			{
				throw new Exception("Invalid Call Data.", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId	 = $followupDetails['scq_created_by_uid'];
			$row	 = ServiceCallQueue::deactivateById($userId, $scqId);
			if ($row > 0)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Call back cancelled successfully");
			}
			else
			{
				$returnSet->setMessage("Call back cancellation failed");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
