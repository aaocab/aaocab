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
			$ri	 = array('/generateCheckSum', '/txnStatus', '/transactionPayu');
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
		/**
		 * @deprecated new service txnStatus_v1
		 */
		$this->onRest('req.post.txnStatus.render', function () {
			return $this->renderJSON($this->txnStatus());
		});

		// Transaction By Payu Money
		$this->onRest('req.post.transactionPayu.render', function () {
			return $this->renderJSON($this->transactionPayu());
		});
		// Transaction By Razorpay
		$this->onRest('req.post.payment_initiate.render', function () {
			return $this->renderJSON($this->paymentInitiate());
		});
		// Transaction Status
		$this->onRest('req.post.txnStatus_v1.render', function () {
			return $this->renderJSON($this->txnStatus_v1());
		});
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
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
			switch ($pmodel->apg_acc_trans_type)
			{
				case 1:
					$userModel	 = $billingObj->getData();
					$refModel	 = $userModel;
					break;
				case 9:
					$vmodel		 = $billingObj->getVoucherData();
					$refModel	 = $vmodel;
					break;
			}
			$referenceId = $obj->refId;
			$paymentType = $obj->paymentType;

			$checksumObj = PaymentGateway::getCheckSum($referenceId, $paymentType, $pmodel, $refModel, $userInfo);

			if ($checksumObj->txnid != '' && $paymentType == 1)
			{
				$bkgModel = Booking::model()->findByPk($referenceId);
				$bkgModel->bkgInvoice->savePromoCoins($obj->promoDetails->promo->code, $obj->promoDetails->gozoCoins);
			}
			/* @var $response Stub\common\Payment */
			$response = new Stub\common\Payment();
			$response->setModel($referenceId, $paymentType, $checksumObj, $userModel, $vmodel);
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
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function paymentInitiate()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();

		try
		{
			/* @var $obj \Stub\common\Payment */
			$obj		 = Yii::app()->request->getJSONObject(new \Stub\common\Payment());
			$billingObj	 = $obj->billing;
			$promoObj	 = $obj->promoDetails;
			$pmodel		 = $obj->getModel();

			$response	 = PaymentGateway::initiatePayment($pmodel, $billingObj, $promoObj);
			/* @var $response Stub\common\PaymentGateway */
			$pgStub		 = new Stub\common\PaymentGateway();
			$response	 = (object) $response;
			$pgStub->setData($response);
			if ($pmodel->apg_acc_trans_type == 9)
			{
				$vmodel			 = $billingObj->getVoucherData();
				$pgStub->billing = new \Stub\common\BillingDetails();
				$pgStub->billing->setVoucherData($vmodel);
			}
			$returnSet->setData($pgStub);
			$returnSet->setStatus(true);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
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

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function txnStatus()
	{
		$returnSet = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
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
			if ($transModel['apg_status'] == 0 && $count != 3)
			{
				$count += 1;
				goto a;
			}

			if ($transModel->apg_status)
			{
				QuotesSituation::setConFirmData($transModel->apg_booking_id);
				QuotesZoneSituation::setConFirmData($transModel->apg_booking_id);
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

	public function txnStatus_v1()
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
			if ($transModel->apg_status)
			{
				QuotesSituation::setConFirmData($transModel->apg_booking_id);
				QuotesZoneSituation::setConFirmData($transModel->apg_booking_id);
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
			$returnSet->setStatus(false);
			$returnSet->setMessage($ex->getMessage());
			Logger::exception($ex);
		}
		return $returnSet;
	}

}
