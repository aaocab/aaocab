<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ContactController extends BaseController
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
			$ri	 = array('/unsignedCMB', '/cancelCMB', '/registerLoginIssue', '/dropCMB');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.post.unsignedCMB.render', function () {
			return $this->renderJSON($this->unsignedCMB());
		});
		//new version of 'unsignedCMB' with beans
		$this->onRest('req.post.registerLoginIssue.render', function () {
			return $this->renderJSON($this->registerLoginIssue());
		});
		$this->onRest('req.post.cancelCMB.render', function () {
			return $this->renderJSON($this->cancelCMB());
		});
		//new version of 'cancelCMB' with beans
		$this->onRest('req.post.dropCMB.render', function () {
			return $this->renderJSON($this->dropCMB());
		});

		$this->onRest('req.post.storeCMB.render', function () {
			return $this->renderJSON($this->storeCMB());
		});
		//new version of 'storeCMB' with beans
		$this->onRest('req.post.createCMB.render', function () {
			return $this->renderJSON($this->createCMB());
		});
		$this->onRest('req.post.raisePenaltyDispute.render', function () {
			return $this->renderJSON($this->raisePenaltyDispute());
		});

		//new version of 'raisePenaltyDispute' with beans
		$this->onRest('req.post.raiseTransactionDispute.render', function () {
			return $this->renderJSON($this->raiseTransactionDispute());
		});

		$this->onRest('req.get.getQueueList.render', function () {
			return $this->renderJSON($this->getQueueList());
		});
	}

	public function unsignedCMB()
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
			$scqType	 = ServiceCallQueue::TYPE_VENDOR_APPROVAl;
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
				$message		 = "We will call back as soon as possible.\n
All the calls will be recorded for training are quality assurance purposes.\n
If your issues get resolved before our call, you can click the 'CANCEL' below.";
				$returnSet->setMessage($message);
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = $scqType;
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

				$platform	 = ServiceCallQueue::PLATFORM_DCO_APP;
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

//New version dropCMB

	/**
	 * @deprecated
	 * @return type
	 * @throws Exception
	 */
	public function cancelCMB()
	{
		$returnSet = new ReturnSet();
		try
		{
			// authorozation chacking remove due to cancel will be done outside also before login.
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			$scqId	 = $jsonObj['scqId'];
			if ($scqId == "")
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$scqDetails	 = ServiceCallQueue::model()->findByPk($scqId);
			$userId		 = $scqDetails->scq_created_by_uid;
//			if (empty($userId))
//			{
//				throw new Exception("No user found", ReturnSet::ERROR_INVALID_DATA);
//			}

			$row = ServiceCallQueue::deactivateById($userId, $scqId);

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

//old version cancelCMB
	public function dropCMB()
	{
		$returnSet = new ReturnSet();
		try
		{
// authorozation checking remove due to cancel will be done outside also before login.
			$jsonval = Yii::app()->request->rawBody;

			if (!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}



			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\contact\Scq $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\contact\Scq());
			$scqId	 = $reqData->id;

			if ($scqId == "")
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}

			$scqDetails	 = ServiceCallQueue::model()->findByPk($scqId);
			$userId		 = $scqDetails->scq_created_by_uid;

			if ($scqDetails->scq_follow_up_queue_type != ServiceCallQueue::TYPE_VENDOR_APPROVAl && !$userId)
			{
				throw new Exception("Invalid service request", ReturnSet::ERROR_INVALID_DATA);
			}
			$row = 0;
			if ($scqDetails->scq_status != 0)
			{
				$row = ServiceCallQueue::deactivateById($userId, $scqId);
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Call back already cancelled");
			}
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

	public function storeCMB()
	{
		$returnSet = new ReturnSet();
		try
		{

			$cttId	 = $this->getContactId();
			$userId	 = UserInfo::getUserId();

			$jsonval = Yii::app()->request->rawBody;

			if (!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\contact\Scq $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\contact\Scq())->getRequest();
			$scqType = $reqObj->queType;
			if($scqType == "")
			{
				$scqType = $reqObj->scq_id;
			}
			if (in_array($scqType, [11]))
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType, $reqData->refId);
			}
			else if (in_array($scqType, [6]))
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, 9);
			}
			else
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType);
			}
			if (in_array($scqType, [23]) && UserInfo::getUserType() == UserInfo::TYPE_VENDOR)
			{
				$vendorId			 = $this->getVendorId();
				$outStandingValidate = VendorStats::checkOutstanding($vendorId);
				if ($outStandingValidate == false)
				{

					$returnSet->setMessage("Your call back request has not been initiated. You do not have sufficient balance in your account.");
					goto end;
				}
			}

			$platform	 = ServiceCallQueue::PLATFORM_DCO_APP;
			$entityType	 = UserInfo::getUserType();
			$returnSet	 = ServiceCallQueue::generateModel($reqData, $cttId, $entityType, $scqType, $followupId, $platform);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		end:
		return $returnSet;
	}

	public function raisePenaltyDispute()
	{
		$returnSet = new ReturnSet();
		try
		{
			$cttId	 = $this->getContactId();
			$userId	 = UserInfo::getUserId();
			$jsonval = Yii::app()->request->rawBody;

			if (!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\contact\Scq $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\contact\Scq());
			$actId	 = $reqData->transactionId;

			$phoneList	 = ContactPhone::model()->findByContactID($cttId);
			$vndId		 = $this->getVendorId(false);
			$reqData->setObjPhone($phoneList[0]);
			$bkgId		 = AccountTransactions::getBookingInfoById($actId, $vndId);

			if (!$bkgId)
			{
				throw new Exception("No booking found.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqData->ref_id = $bkgId;
			$scqType		 = ServiceCallQueue::TYPE_PENALITY_DISPUTE;
			$followupId		 = ServiceCallQueue::getIdByUserId($userId, $scqType, $bkgId);
			$platform		 = ServiceCallQueue::PLATFORM_DCO_APP;
			$entityType		 = UserInfo::getUserType();
			$addParams		 = ['act_id' => $actId, 'bkg_id' => $bkgId];
			$returnSet		 = ServiceCallQueue::generateModel($reqData, $cttId, $entityType, $scqType, $followupId, $platform, $addParams);

			$message = "You have disputed the penalty charge.\nIn most cases the team will remove the penalty. If required the Gozo team will contact you.";
			$returnSet->setMessage($message);

			$returnSet->getData()->message = $message;
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		end:
		return $returnSet;
	}

	public function getQueueList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$cttId	 = $this->getContactId();
			$entType = UserInfo::getUserType();
			if (empty($cttId))
			{
				throw new Exception("No contact found", ReturnSet::ERROR_INVALID_DATA);
			}
			$reasonList	 = QueueMaster::getListByEntityType($entType);
//			$reasonList		 = ServiceCallQueue::queueList;
			$dataArr	 = [];

			foreach ($reasonList as $value)
			{
				$dataArr[] = \Beans\common\ValueObject::setIdlabel($value['id'], $value['label']);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($dataArr);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function raiseTransactionDispute()
	{
		$returnSet = new ReturnSet();
		try
		{
			$cttId	 = $this->getContactId();
			$userId	 = UserInfo::getUserId();
			$jsonval = Yii::app()->request->rawBody;

			if (!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\contact\Scq $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\contact\Scq());
			$actId	 = $reqData->refId;
			if (!$actId)
			{
				throw new Exception(json_encode("Transaction id not specified "), ReturnSet::ERROR_VALIDATION);
			}
			$reqData->refType = ServiceCallQueue::REF_TRANSACTION;

			$phoneList	 = ContactPhone::model()->findByContactID($cttId);
			$vndId		 = $this->getVendorId(false);
			$reqData->setObjPhone($phoneList[0]);
			$bkgId		 = AccountTransactions::getBookingInfoById($actId, $vndId);

			if (!$bkgId)
			{
				throw new Exception("No booking found.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqData->refId					 = $bkgId;
			$scqType						 = ServiceCallQueue::TYPE_PENALITY_DISPUTE;
			$followupId						 = ServiceCallQueue::getIdByUserId($userId, $scqType, $bkgId);
			$platform						 = ServiceCallQueue::PLATFORM_DCO_APP;
			$entityType						 = UserInfo::getUserType();
			$addParams						 = ['act_id' => $actId, 'bkg_id' => $bkgId];
			$returnSet						 = ServiceCallQueue::generateModel($reqData, $cttId, $entityType, $scqType, $followupId, $platform, $addParams);
			$message						 = "You have disputed the penalty charge.\n
	In most cases the team will remove the penalty. If required the Gozo team will contact you.";
			$returnSet->setMessage($message);
			$returnSet->getData()->message	 = $message;
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		end:
		return $returnSet;
	}

//new vetsion with beans
	public function createCMB()
	{
		$returnSet = new ReturnSet();
		try
		{
			$cttId	 = $this->getContactId();
			$userId	 = UserInfo::getUserId();

			$jsonval = Yii::app()->request->rawBody;
			if (!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\contact\Scq $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\contact\Scq());
			$queType = $reqData->queType;
			if ($queType == '')
			{
				throw new Exception("No que type selected ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (in_array($queType, [23]) && UserInfo::getUserType() == UserInfo::TYPE_VENDOR)
			{
				$vendorId			 = $this->getVendorId();
				$outStandingValidate = VendorStats::checkOutstanding($vendorId);
				if ($outStandingValidate == false)
				{

					$returnSet->setMessage("Your call back request has not been initiated. You do not have sufficient balance in your account.");
					goto end;
				}
			}

			if (in_array($queType, [47, 48, 49, 50]) || UserInfo::getUserType() == UserInfo::TYPE_DRIVER)
			{
				$queType			 = ServiceCallQueue::TYPE_DRIVER;
				$reqData->queType	 = $queType;
			}
			$followupId = ServiceCallQueue::getExistingFollowupId($userId, $reqData);

			$platform	 = ServiceCallQueue::PLATFORM_DCO_APP;
			$entityType	 = UserInfo::getUserType();
			$returnSet	 = ServiceCallQueue::generateModel($reqData, $cttId, $entityType, $queType, $followupId, $platform);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		end:
		return $returnSet;
	}

	public function registerLoginIssue()
	{
		$returnSet = new ReturnSet();
		try
		{

			$jsonval = Yii::app()->request->rawBody;

			if (!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var Beans\contact\Scq $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\contact\Scq());
			$queType = 15;

			$entityType	 = UserInfo::TYPE_VENDOR;
			$followupId	 = ServiceCallQueue::checkActiveCallbackByContactNumber($reqData->phone->fullNumber, $queType);
			$platform	 = ServiceCallQueue::PLATFORM_DCO_APP;
			$returnSet	 = ServiceCallQueue::generateModel($reqData, $cttId, $entityType, $queType, $followupId, $platform);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
