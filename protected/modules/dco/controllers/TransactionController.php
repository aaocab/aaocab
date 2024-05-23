<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class TransactionController extends BaseController
{

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
					'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => [],
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
			$ri	 = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.status.render', function () {
			return $this->renderJSON($this->status());
		});

		$this->onRest('req.post.paymentInitiate.render', function () {
			return $this->renderJSON($this->paymentInitiate());
		});
        $this->onRest('req.post.paymentRequest.render', function () {
			return $this->renderJSON($this->paymentRequest());
		});
	}

	public function paymentInitiate()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj				 = CJSON::decode($requestData, false);
			$jsonMapper			 = new JsonMapper();
//			/* @var $obj \Beans\transaction\Payment */
			$obj				 = $jsonMapper->map($reqObj, new Beans\transaction\Payment());
			$obj->refId			 = $this->getVendorId();
			$obj->paymentType	 = \Accounting::AT_OPERATOR;
			$obj->method		 = ($obj->method) ? $obj->method : PaymentType::TYPE_RAZORPAY;

			$pmodel	 = $obj->getModel();
			$pgmodel = $pmodel->registerPayment();

			if (!$pgmodel->apg_id)
			{
				throw new Exception("Unknown Exception");
			}

			$payRequest		 = PaymentGateway::model()->getPGRequest($pgmodel->apg_id);
			$pgObject		 = Filter::GetPGObject($payRequest->payment_type);
			$pgData			 = (object) $pgObject->initiateRequest($payRequest);
			$pgData->secret	 = $pgObject->secret;

			if (!$pgData->success && !$pgData->key)
			{
				throw new Exception("Error occurred in payment initiation", ReturnSet::ERROR_INVALID_DATA);
			}
			/* @var $objPG \Beans\transaction\PayGateway */
			$response = \Beans\transaction\PayGateway::setResponseData($payRequest, $pgData);

			$returnSet->setData($response);
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

	public function paymentRequest()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data1	 = CJSON::decode($jsonval, true);
			$reqObj				 = CJSON::decode($jsonval, false);
			$entityId	 = $this->getVendorId();;
			$entityType	 = UserInfo::TYPE_VENDOR;
			$userId		 = UserInfo::getUserId();
			if (!$entityId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper			 = new JsonMapper();
		/** @var Beans\contact\Scq $reqData */
			$obj = $jsonMapper->map($reqObj, new \Beans\contact\Scq())->getRequest();
			Logger::trace("<===Request===>" . $data1);
			
			if (!$obj)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			
			$scqType	 = (int)$obj->queType;
			$contactId	 = ContactProfile::getByEntityId($entityId, $entityType);
			$followupId	 = ServiceCallQueue::getIdByUserId($userId, $scqType, $obj->ref_id);
			$success	 = false;
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['id' => $followupId, 'followupCode' => $followupCode, 'queNo' => $queNo, 'active' => 1, 'waitTime' => $waitTime, 'isNewFollowup' => false];
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
					if (isset($data1->ref_id) && trim($data1->ref_id) != '')
					{
						$model->scq_related_bkg_id =$data1->ref_id;
					}
					$platform				 = ServiceCallQueue::PLATFORM_VENDOR_APP;
					$returnSet				 = ServiceCallQueue::model()->create($model, $entityType, $platform);
					
					$result					 = $returnSet->getData();
					
					$result ['id']           =$result['followupId'] ; 
					

					$result['isNewFollowup'] = true;
					
					$returnSet->setData($result);
					if ($returnSet->getStatus())
					{
						$returnSet->setMessage('Your new payment request in process .');
					}
					Logger::trace("<===response===>" . $result);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		
		return $returnSet;
	}

	public function status()
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
			$transCode	 = $jsonObj->refCode;
			$orderId	 = $jsonObj->orderId;
			$vendorId	 = $this->getVendorId();

			if (!empty($orderId))
			{
				$transCode = PaymentGateway::getByRzpOrderId($orderId);
			}


			$count	 = 1;
			/** @var PaymentGateway $pgModel */
			$pgModel = PaymentGateway::model()->getByCode($transCode);

			if (!$pgModel)
			{
				throw new Exception('Data Not Found', ReturnSet::ERROR_INVALID_DATA);
			}
			if ($vendorId != $pgModel->apg_trans_ref_id)
			{
				throw new Exception('User mismatch', ReturnSet::ERROR_VALIDATION);
			}
			if ($pgModel->apg_status == 1)
			{
				goto skipAll;
			}
			checkPaymentStatus:

			$pgObject	 = Filter::GetPGObject($pgModel->apg_ptp_id);
			/** @var \PaymentResponse $payResponse */
			$payResponse = $pgObject->getPaymentStatus($pgModel);

			if (!$payResponse)
			{
				throw new Exception('No response received', ReturnSet::ERROR_INVALID_DATA);
			}

			if ($payResponse->payment_status != 1 && $count != 3)
			{
				$count += 1;
				goto checkPaymentStatus;
			}

			if ($payResponse->payment_status != 0 && $pgModel->apg_status == 0)
			{
				$transStatus = $pgModel->processTransaction($payResponse);
				$pgModel	 = PaymentGateway::model()->findByPk($pgModel->apg_id);
			}

			skipAll:
			if ($pgModel->apg_status == 1)
			{

				$vndModel		 = Vendors::model()->findByPk($vendorId);
				$runningAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
				if ($runningAmount['vendor_amount'] >= 0)
				{
					$msg = "payable to Gozo";
				}
				else
				{
					$msg = "payable to you";
				}
				$message	 = "Gozo received " . $pgModel->apg_amount . " in your vendor account " . $vndModel->vnd_name . ". Your balance at time of this credit is " . $runningAmount['vendor_amount'] . " ($msg)";
				$payLoadData = ['vendorId' => $vendorId, 'EventCode' => BookingLog::PAYMENT_COMPLETED];
				$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Payment received.");

				$returnSet->setMessage($message);
			}
			else
			{
				$eventcode	 = ($pgModel->apg_status == 2) ? BookingLog::PAYMENT_FAILED : BookingLog::PAYMENT_INITIATED;
				$status		 = ($pgModel->apg_status == 2) ? "failed" : "initiated";
				$message	 = "Your payment is $status. Please wait while we try again if your transaction was successful";
				$payLoadData = ['vendorId' => $vendorId, 'EventCode' => $eventcode];
				$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Payment $status.");

				$returnSet->setMessage($message);
			}
			$returnSet->setData($payResponse);
			$returnSet->setStatus(true);
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
