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
			$ri	 = array('/storecmbUnsignedVendor', '/checkcmbStatusUnsignedVendor', '/cancelcmbUnsignedVendor', '/storecmbUnsignedVendorV1');
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

		$this->onRest('req.get.checkCMBStatus.render', function () {
			return $this->renderJSON($this->checkCMBStatus());
		});
		$this->onRest('req.post.cancelCMB.render', function () {
			return $this->renderJSON($this->cancelCMB());
		});

		$this->onRest('req.post.checkCMBStatus_v1.render', function () {
			return $this->renderJSON($this->checkCMBStatus_v1());
		});
		$this->onRest('req.post.cancelCMB_v1.render', function () {
			return $this->renderJSON($this->cancelCMB_v1());
		});

		$this->onRest('req.post.storecmbUnsignedVendor.render', function () {
			return $this->renderJSON($this->storecmbUnsignedVendor());
		});
		$this->onRest('req.post.cancelcmbUnsignedVendor.render', function () {
			return $this->renderJSON($this->cancelcmbUnsignedVendor());
		});
		$this->onRest('req.post.storeCMB_v1.render', function () {
			return $this->renderJSON($this->storeCMB_v1());
		});
		$this->onRest('req.post.storecmbUnsignedVendorV1.render', function () {
			return $this->renderJSON($this->storecmbUnsignedVendorV1());
		});
	}

	/*
	 * Old Services: storeCMB
	  New services: storeCMB_v1
	 * 
	 */

	/**
	 * @deprecated since version 2-12-2021
	 */
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
			$entityType	 = UserInfo::TYPE_VENDOR;
			if (!$entityId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['desc'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$model										 = new ServiceCallQueue();
			$model->scq_follow_up_queue_type			 = trim($data['isfollowup']) == 1 ? ServiceCallQueue::TYPE_EXISTING_VENDOR : ServiceCallQueue::TYPE_PENALITY_DISPUTE;
			$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
			$model->scq_creation_comments				 = trim($data['desc']);
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = $entityId;
			$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
			$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
			if (isset($data['ref_id']) && trim($data['ref_id']) != '')
			{
				$model->scq_related_bkg_id = $data['ref_id'];
			}
			$platform	 = ServiceCallQueue::PLATFORM_VENDOR_APP;
			$returnSet	 = ServiceCallQueue::model()->create($model, $entityType, $platform);
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage('We will call you back shortly . All the calls will be recorded for training are quality assurance purposes.');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @deprecated since version 2-12-2021
	 */
	public function checkCMBStatus_v1()
	{
		$returnSet = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
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
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityType	 = UserInfo::TYPE_VENDOR;
			$refType	 = ServiceCallQueue::TYPE_EXISTING_VENDOR;
			$scqType	 = $jsonObj['scqType'];
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			//$followupId	 = ServiceCallQueue::getIdByEntity($entityId);


			$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType);
//			$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
//			$queueData		 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
//			$queNo			 = $queueData['queNo'];
//			$waitTime		 = $queueData['waitTime'];
//			$contactNumber	 = $fpModel->scq_to_be_followed_up_with_value;




			$success = false;
			$data	 = [];
			$data	 = ['active' => 0];
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
				$returnSet->setMessage("We will call back as soon as possible. \n All the calls will be recorded for training are quality assurance purposes. If your issues get resolved before our call, you can 'click' the cancel below.");
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
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqId = $jsonObj['scqId'];

			$row = ServiceCallQueue::deactivateById($userId, $scqId);
			//$row = ServiceCallQueue::deactivateAllEntry($vendorId);
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

	public function cancelCMB()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$vendorId = UserInfo::getEntityId();
			if (!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$row = ServiceCallQueue::deactivateAllEntry($vendorId);
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
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$entityId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$entityId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityType	 = UserInfo::TYPE_VENDOR;
			$refType	 = ServiceCallQueue::TYPE_EXISTING_VENDOR;
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			$followupId	 = ServiceCallQueue::getIdByEntity($entityId);
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
				$returnSet->setMessage('We will call you back shortly .  All the calls will be recorded for training are quality assurance purposes.');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to create a call back request in sign in process
	 * @return type (obj) $returnSet
	 * @throws Exception
	 */
	public function storecmbUnsignedVendor()
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
			if (empty($data['scq_id']))
			{
				throw new Exception("Invalid Call Data. ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($data['phone']['number']))
			{
				throw new Exception("Phone Number is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['desc'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$contactId = Contact::getIdByNumberOrDL($data['license'], $data['phone']['number']);
			if (empty($contactId))
			{
				throw new Exception("Please register as Vendor with us. Unable to get vendor details.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vndContactData	 = ContactProfile::getProfileByCttId($contactId);
			$entityId		 = $vndContactData["cr_is_vendor"];
			if (empty($entityId))
			{
				throw new Exception("Unable to get vendor details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = $vndContactData["cr_is_consumer"];
			if (empty($userId))
			{
				throw new Exception("Unable to get vendor details. Please enter registered Phone Number.", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqType	 = $data['scq_id'];
			$entityType	 = UserInfo::TYPE_VENDOR;
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType);
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'waitTime' => $waitTime, 'active' => 1];
				$returnSet->setMessage('Call Back request has already been initiated please cancel  it to create it again . All the calls will be recorded for training and quality assurance purposes.');
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = $data['scq_id'];
				$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				$model->scq_created_by_uid					 = $userId;

				$platform	 = ServiceCallQueue::PLATFORM_VENDOR_APP;
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

	/**
	 * This function is used to create a call back request in sign in process
	 * @return type (obj) $returnSet
	 * @throws Exception
	 */
	public function storecmbUnsignedVendorV1_old()
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
			if (empty(trim($data['code'])))
			{
				throw new Exception("Vendor code is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}

			$vndCode = $data['code'];

			$entityId = Vendors::getVndIdByCode($vndCode);
			if (empty($entityId))
			{
				throw new Exception("Unable to get vendor details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			//$contactId = Contact::getIdByNumberOrDL($data['license'], $data['phone']['number']);
			$contactId		 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_VENDOR);
			$vndContactData	 = ContactProfile::getProfileByCttId($contactId);
			$userId			 = $vndContactData["cr_is_consumer"];
			if (empty($userId))
			{
				throw new Exception("Unable to get vendor details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqType	 = $data['scq_id'];
			$entityType	 = UserInfo::TYPE_VENDOR;
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType);
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'waitTime' => $waitTime, 'active' => 1];
				$returnSet->setMessage('Call Back request has already been initiated please cancel  it to create it again . All the calls will be recorded for training and quality assurance purposes.');
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = $data['scq_id'];
				$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
				$code										 = $arrPhoneByPriority['phn_phone_country_code'];
				$number										 = $arrPhoneByPriority['phn_phone_no'];
				$model->scq_to_be_followed_up_with_value	 = $code . $number;
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				$model->scq_created_by_uid					 = $userId;

				$platform	 = ServiceCallQueue::PLATFORM_VENDOR_APP;
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

	public function storecmbUnsignedVendorV1()
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
			if (empty(trim($data['code'])))
			{
				throw new Exception("Vendor code is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}

			$vndCode	 = $data['code'];
			$entityId	 = Vendors::getVndIdByCode($vndCode);
			if (empty($entityId))
			{
				$code		 = "91";
				$phoneRecord = ContactPhone::getByPhone($code . $vndCode, '', '', '', 'limit 1');
				foreach ($phoneRecord as $contactPhone)
				{
					$entityId = $contactPhone['vendorId'];
				}
			}

			if (empty($entityId))
			{
				$emailRecord = ContactEmail::getByEmail($vndCode, '', '', '', 'limit 1');
				foreach ($emailRecord as $contactEmail)
				{
					$entityId = $contactEmail['vendorId'];
				}
			}

			if (empty($entityId))
			{
				throw new Exception("Unable to get vendor details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			//$contactId = Contact::getIdByNumberOrDL($data['license'], $data['phone']['number']);
			$contactId		 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_VENDOR);
			$vndContactData	 = ContactProfile::getProfileByCttId($contactId);
			$userId			 = $vndContactData["cr_is_consumer"];
			if (empty($userId))
			{
				throw new Exception("Unable to get vendor details. Please register with us.", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqType	 = $data['scq_id'];
			$entityType	 = UserInfo::TYPE_VENDOR;
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType);
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'waitTime' => $waitTime, 'active' => 1];
				$returnSet->setMessage('Call Back request has already been initiated please cancel  it to create it again . All the calls will be recorded for training and quality assurance purposes.');
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = $data['scq_id'];
				$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
				$code										 = $arrPhoneByPriority['phn_phone_country_code'];
				$number										 = $arrPhoneByPriority['phn_phone_no'];
				$model->scq_to_be_followed_up_with_value	 = $code . $number;
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = $contactId;
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				$model->scq_created_by_uid					 = $userId;

				$platform	 = ServiceCallQueue::PLATFORM_VENDOR_APP;
				$returnSet	 = ServiceCallQueue::model()->create($model, $entityType, $platform);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage("We will call back as soon as possible. \n All the calls will be recorded for training are quality assurance purposes. If your issues get resolved before our call, you can 'click' the cancel below.");
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to cancel call back request
	 * @return type (obj) $returnSet
	 * @throws Exception $ex
	 */
	public function cancelcmbUnsignedVendor()
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

	/**
	 * This function is used for creating call back request
	 * @return $returnSet
	 * @throws Exception
	 */
	public function storeCMB_v1()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data	 = CJSON::decode($jsonval, true);
			Logger::trace("<===Request===>" . $data);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId	 = UserInfo::getEntityId();
			$entityType	 = UserInfo::TYPE_VENDOR;
			if (!$entityId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['desc'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			//$refType	 = ServiceCallQueue::TYPE_EXISTING_VENDOR;
			$scqType = $data['scq_id'];
			if (in_array($scqType, [11]))
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType, $data['ref_id']);
			}
			else if (in_array($scqType, [6]))
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, 9);
			}
			else
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType);
			}
			$success = false;
			if (in_array($scqType, [23]))
			{
				$outStandingValidate = VendorStats::checkOutstanding($entityId);
				if ($outStandingValidate == false)
				{

					$returnSet->setMessage("Your call back request has not been initiated. You do not have sufficient balance in your account.");
					goto end;
				}
			}

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
				$returnSet->setData($data);
				$returnSet->setStatus($success);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage('Call back request has already been initiated please cancel  it to create it again . All the calls will be recorded for training and quality assurance purposes.');
				}
			}
			else
			{
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = $scqType == 6 ? ServiceCallQueue::TYPE_IMNTERNAL : $scqType;
				$model->scq_to_be_followed_up_with_value	 = $data['phone']['code'] . $data['phone']['number'];
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->scq_creation_comments				 = filter_var($model->scq_creation_comments, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				if ($scqType == 6)
				{
					$model->scq_to_be_followed_up_by_type	 = 1;
					$model->scq_to_be_followed_up_by_id		 = 9;
				}
				if (isset($data['ref_id']) && trim($data['ref_id']) != '')
				{
					$model->scq_related_bkg_id = $data['ref_id'];
				}
				$platform	 = ServiceCallQueue::PLATFORM_VENDOR_APP;
				$returnSet	 = ServiceCallQueue::model()->create($model, $entityType, $platform);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage("We will call back as soon as possible. \n All the calls will be recorded for training are quality assurance purposes. If your issues get resolved before our call, you can 'click' the cancel below.");
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		end:
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

}
