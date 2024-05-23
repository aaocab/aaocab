<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class TransactionController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'main';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';
	public $title		 = '';

//public $layout = '//layouts/column2';pre

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'generateInvoice'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => ['new', 'list'],
				'users'		 => ['@']
			],
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
			$ri	 = array(  '/transactionPayu');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		// Generate checkSum
		$this->onRest('req.post.generateCheckSum.render', function () {
			return $this->renderJSON($this->generateCheckSum());
		});
		// Transaction Status
		$this->onRest('req.post.txnStatus.render', function () {
			return $this->renderJSON($this->txnStatus());
			//old function = txnStatus, new function updatePaymentStatus
		});
// Transaction Status
		$this->onRest('req.post.txnStatus_v1.render', function () {
			return $this->renderJSON($this->updatePaymentStatus());
			//old function = txnStatus, new function updatePaymentStatus
		});
		//Request Payment FUR
		$this->onRest('req.post.requestPayment.render', function () {
			return $this->renderJSON($this->storeRequestPaymantFUR());
		});
		$this->onRest('req.post.storeRequestPaymentFUR_v1.render', function () {
			return $this->renderJSON($this->storeRequestPaymentFUR_v1());
		});

		$this->onRest('req.post.payment_initiate.render', function () {
			return $this->renderJSON($this->paymentInitiate());
		});
	}

	public function generateCheckSum()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		$userInfo	 = UserInfo::getInstance();
		$refModel	 = null;
		try
		{
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			/* @var $obj \Stub\common\Payment */
			$obj		 = Yii::app()->request->getJSONObject(new \Stub\common\Payment());
			$billingObj	 = $obj->billing;
			$pmodel		 = $obj->getModel();

			$refModel = array(
				'firstname'	 => $obj->billing->fullName,
				'email'		 => $obj->billing->email,
				'address'	 => $obj->billing->address,
				'city'		 => $obj->billing->city,
				'state'		 => $obj->billing->state,
				'country'	 => $obj->billing->country,
				'phone'		 => $obj->billing->primaryContact->number
			);

			$referenceId = $obj->refId;
			$paymentType = $obj->paymentType;

			$checksumObj = PaymentGateway::getCheckSum($referenceId, $paymentType, $pmodel, $refModel, $userInfo);

			/* @var $response Stub\common\Payment */
			$response = new Stub\common\Payment();
			$response->setModel($referenceId, $paymentType, $checksumObj);
			$returnSet->setData($response);
			$returnSet->setStatus(true);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/**
	 * @deprecated since version 21/04/2022  new service  updatePaymentStatus
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function txnStatus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			$transCode	 = $jsonObj->transId;
			$count		 = 1;
			a:
			$transModel	 = PaymentGateway::model()->getByCode($transCode);
			if (!$transModel)
			{
				throw new Exception('Invalid Data', ReturnSet::ERROR_INVALID_DATA);
			}
			else
			{
				$vendorId		 = UserInfo::getEntityId();
				$vndModel		 = vendors::model()->findByPk($vendorId);
				$bookingModel	 = Booking::model()->findByPk($transModel->apg_booking_id);
				$runningAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
				if ($runningAmount['vendor_amount'] >= 0)
				{
					$msg = "payable to Gozo";
				}
				else
				{
					$msg = "payable to you";
				}
				$message	 = "Gozo received " . $transModel->apg_amount . " in your vendor account " . $vndModel->vnd_name . ". Your balance at time of this credit is " . $runningAmount['vendor_amount'] . " ($msg)";
				$payLoadData = ['vendorId' => $vendorId, 'EventCode' => BookingLog::PAYMENT_COMPLETED, 'tripId' => $bookingModel->bkg_bcb_id];
				$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Payment received.");
			}
			if ($transModel['apg_status'] == 0 && $count != 3)
			{
				$count += 1;
				goto a;
			}
			$response	 = new \Stub\common\PaymentState();
			$response->setStatusData($transModel);
			$response	 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/*
	 * Old Services: requestPayment
	  New services: storeRequestPaymantFUR_v1
	 * 
	 */

	/**
	 * @deprecated since version 2-12-2021
	 */
	public function storeRequestPaymantFUR()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data1	 = CJSON::decode($jsonval, true);
			if (!$data1)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId	 = UserInfo::getEntityId();
			$entityType	 = UserInfo::TYPE_VENDOR;
			$userId		 = UserInfo::getUserId();
			if (!$entityId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$refType	 = ServiceCallQueue::TYPE_VENDOR_REQUEST_PAYMENT;
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			//$followupId	 = ServiceCallQueue::getIdByEntity($entityId);
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $refType);
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
				$data			 = ['followupId' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'active' => 1, 'waitTime' => $waitTime, 'isNewFollowup' => false];
				$success		 = true;
				$returnSet->setData($data);
				$returnSet->setStatus($success);
				$returnSet->setMessage('Your previous payment request already in process.');
			}
			else
			{

				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = ServiceCallQueue::TYPE_VENDOR_REQUEST_PAYMENT;
				$model->scq_to_be_followed_up_with_value	 = $data1['phone']['code'] . $data1['phone']['number'];
				$model->scq_creation_comments				 = "Vendor has requested to release his withdrawable balance.";
				$model->contactRequired						 = 1;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				if (isset($data1['ref_id']) && trim($data1['ref_id']) != '')
				{
					$model->scq_related_bkg_id = $data1['ref_id'];
				}
				$platform				 = ServiceCallQueue::PLATFORM_VENDOR_APP;
				$returnSet				 = ServiceCallQueue::model()->create($model, $entityType, $platform);
				$result					 = $returnSet->getData();
				$result['isNewFollowup'] = true;
				$returnSet->setData($result);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage('Your new payment request in process .');
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::info("Response : ===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function storeRequestPaymentFUR_v1()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data1	 = CJSON::decode($jsonval, true);
			//Logger::trace("<===Request===>" . $data1);
			if (!$data1)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId	 = UserInfo::getEntityId();
			$entityType	 = UserInfo::TYPE_VENDOR;
			$userId		 = UserInfo::getUserId();
			if (!$entityId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqType	 = $data1['scq_id'];
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType, $data1['ref_id']);
			$success	 = false;
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'active' => 1, 'waitTime' => $waitTime, 'isNewFollowup' => false];
				$success		 = true;
				$returnSet->setData($data);
				$returnSet->setStatus($success);
				$returnSet->setMessage('Your previous payment request already in process.');
			}
			else
			{
				
				$outStandingValidate = VendorStats::checkOutstandingBalence($entityId);
				
				if($outStandingValidate == false)
				{
					$success = false;
					throw new Exception(json_encode("You do not have sufficient balance in your account"), ReturnSet::ERROR_VALIDATION);
				}
				
					$model										 = new ServiceCallQueue();
					$model->scq_follow_up_queue_type			 = $scqType;
					$model->scq_to_be_followed_up_with_value	 = $data1['phone']['code'] . $data1['phone']['number'];
					//$model->scq_creation_comments				 = trim($data1['desc']);
					$model->contactRequired						 = 1;
					$model->scq_to_be_followed_up_with_entity_id = $entityId;
					$model->scq_to_be_followed_up_with_contact	 = $contactId;
					$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
					if (isset($data1['ref_id']) && trim($data1['ref_id']) != '')
					{
						$model->scq_related_bkg_id = $data1['ref_id'];
					}
					$platform				 = ServiceCallQueue::PLATFORM_VENDOR_APP;
					$returnSet				 = ServiceCallQueue::model()->create($model, $entityType, $platform);
					$result					 = $returnSet->getData();
					$result['isNewFollowup'] = true;
					$returnSet->setData($result);
					if ($returnSet->getStatus())
					{
						$returnSet->setMessage('Your new payment request in process .');
					}
				
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::info("Response : ===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function paymentInitiate()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			/* @var $obj \Stub\common\Payment */
			$obj	 = Yii::app()->request->getJSONObject(new \Stub\common\Payment());
			$pmodel	 = $obj->getModel();

			$paymentGateway = PaymentGateway::model()->add($pmodel->apg_ptp_id, $pmodel->apg_amount, null, $pmodel->apg_trans_ref_id, null, $pmodel->apg_acc_trans_type);
			if (!$paymentGateway)
			{
				throw new Exception("Error occurred in payment initiation");
			}
			$payRequest	 = PaymentGateway::model()->getPGRequest($paymentGateway->apg_id);
			$pgObject	 = Filter::GetPGObject($payRequest->payment_type);
			$response	 = $pgObject->initiateRequest($payRequest);
			if (!$response['success'])
			{
				throw new Exception("Error occurred in payment initiation");
			}
			
			/* @var $response Stub\common\PaymentGateway */
			$pgStub				 = new Stub\common\PaymentGateway();
			$response['secret']	 = $pgObject->secret;
			$response			 = (object) $response;
			$pgStub->setRazorPayData($response);

			$returnSet->setData($pgStub);
			$returnSet->setStatus(true);
			$returnSet->setMessage("Payment initiated successfully");

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
			$returnSet->setMessage($ex->getMessage());
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function updatePaymentStatus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			$paymentId	 = $jsonObj->paymentId;
			$orderId	 = $jsonObj->orderId;
			if (!$paymentId && !$orderId)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}
			$count = 1;

			checkPaymentStatus:


			$transcode = PaymentGateway::getByRzpOrderId($orderId);
			if (!$transcode && $orderId != "")
			{
				throw new Exception('Data Not Found', ReturnSet::ERROR_INVALID_DATA);
			}
			$pgObject	 = Filter::GetPGObject(PaymentType::TYPE_RAZORPAY);
			$return		 = $pgObject->fetchPaymentStatus($transcode, $paymentId);

			$transModel = PaymentGateway::model()->getByCode($return->transaction_code);
			if (!$transModel)
			{
				throw new Exception('Invalid Data', ReturnSet::ERROR_INVALID_DATA);
			}
			if ($transModel['apg_status'] == 0 && $count != 3)
			{
				$count += 1;
				goto checkPaymentStatus;
			}
			$transModel->processTransaction($return);

			$vendorId		 = UserInfo::getEntityId();
			$vndModel		 = vendors::model()->findByPk($vendorId);
			$runningAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
			if ($runningAmount['vendor_amount'] >= 0)
			{
				$msg = "payable to Gozo";
			}
			else
			{
				$msg = "payable to you";
			}
			$message	 = "Gozo received " . $transModel->apg_amount . " in your vendor account " . $vndModel->vnd_name . ". Your balance at time of this credit is " . $runningAmount['vendor_amount'] . " ($msg)";
			$payLoadData = ['vendorId' => $vendorId, 'EventCode' => BookingLog::PAYMENT_COMPLETED, 'tripId' => ""];
			$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Payment received.");
			// according to AK after payment vendor outstanding also updated
			 VendorStats::updateOutstanding($vendorId);
			$response	 = new \Stub\common\PaymentState();
			$response->setUpdatedStatusData($transModel);
			$response	 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::trace("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage($ex->getMessage());
			Logger::exception($ex);
		}
		return $returnSet;
	}

}
