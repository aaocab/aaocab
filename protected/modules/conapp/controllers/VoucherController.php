<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class VoucherController extends BaseController
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
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/list'); // 

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		// VOUCHER LIST
		$this->onRest('req.get.list.render', function() {
			return $this->renderJSON($this->getList());
		});

		// VOUCHER CHECKOUT
		$this->onRest('req.post.checkout.render', function() {
			return $this->renderJSON($this->getCheckout());
		});



		// VOUCHER PAYMENT SUMMARY
		$this->onRest('req.post.summary.render', function() {
			return $this->renderJSON($this->getSummary());
		});

		// VOUCHER ORDERS
		$this->onRest('req.post.orders.render', function() {
			return $this->renderJSON($this->getOrders());
		});

		// REDEEM VOUCHER 
		$this->onRest('req.post.redeem.render', function() {
			return $this->renderJSON($this->getRedeem());
		});
	}

	/**
	 * 
	 * @return ReturnSet
	 */
	public function getList()
	{
		$returnSet = new ReturnSet();
		try
		{

			$data = Vouchers::getRecords();
			$returnSet->setStatus(true);
			if (count($data) > 0)
			{
				$response	 = new \Stub\common\Voucher();
				$response	 = $response->setList($data);
				$response	 = Filter::removeNull($response);
				$returnSet->setData($response);
			}
			else
			{
				$msg = "Currently no vouchers are available. Please keep checking";
				$returnSet->setMessage($msg);
				$returnSet->setErrors(ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			Logger::info("Message : " . $returnSet->getMessage());
			Logger::info("Response : " . json_encode($returnSet));
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getCheckout()
	{
		$returnSet	 = new ReturnSet();
		$orderNumber = "";
		$authToken	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$userId		 = UserInfo::getUserId();

		$data = Yii::app()->request->rawBody;
		if (!$data)
		{
			$e			 = new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			$returnSet	 = ReturnSet::setException($e);
			goto skipCheckout;
		}
		Logger::info("Request : " . $data);
		$jsonObj	 = CJSON::decode($data, false);
		$resultCart	 = json_decode(json_encode($jsonObj->Cart->items), true);
		foreach ($resultCart as $k => $v)
		{
			$resultCart[$k]['id'] = Yii::app()->shortHash->unhash($v['id']);
		}
		$cartData	 = Vouchers::getCart($resultCart);
		$cartBalance = Vouchers::getCartBalance($resultCart);
		$result		 = VoucherOrder::updateData($cartData, $cartBalance, $authToken);
		if ($result->isSuccess())
		{
			$orderNumber = $result->getData()['orderNumber'];
		}
		else
		{
			$errors		 = $result->getErrors();
			$e			 = new Exception($errors[0], ReturnSet::ERROR_VALIDATION);
			$returnSet	 = ReturnSet::setException($e);
			$returnSet->setMessage($e->getMessage());
			goto skipCheckout;
		}

		$model = VoucherOrder::model()->getByCode($orderNumber);
		if (!$model || $cartData == null)
		{
			$e			 = new Exception("Invalid data.", ReturnSet::ERROR_INVALID_DATA);
			$returnSet	 = ReturnSet::setException($e);
			goto skipCheckout;
		}
		$model->updateBillingDetails($userId);

		$response	 = new \Stub\common\Cart();
		$response	 = $response->setData($cartData, $cartBalance, $orderNumber, $model->vor_id);
		$response	 = Filter::removeNull($response);
		$returnSet->setStatus(true);
		$returnSet->setData($response);
		return $returnSet;
		Logger::info("Response : " . json_encode($returnSet));

		skipCheckout:

		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getSummary()
	{
		$returnSet	 = new ReturnSet();
		$authToken	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$userInfo	 = UserInfo::getInstance();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::info("Request : " . $data);
			$jsonObj = CJSON::decode($data, false);
			$id		 = $jsonObj->refId;
			if ($id > 0)
			{
				$model = VoucherOrder::model()->findByPk($id);
			}
			if (!$model)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$response	 = new \Stub\common\Cart();
			$response	 = $response->setSummaryData($model);
			$response	 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::info("Response : " . json_encode($returnSet));
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getOrders()
	{
		$returnSet	 = new ReturnSet();
		$authToken	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$pageNumber	 = 0;
		$limitRange	 = Yii::app()->params['listPerPage'];
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			$pageCount	 = $jsonObj->page;
			if ($pageCount > 1)
			{
				$pageNumber = (($pageCount - 1) * $limitRange);
			}
			$pagiParams = ['pageNumber' => $pageNumber, 'limitRange' => $limitRange];

			$userId	 = UserInfo::getUserId();  //2728; //499441; //374;
			$list	 = VoucherOrder::getHistory($userId, $pagiParams);
			if (count($list) > 0)
			{
				$returnSet->setStatus(true);
				$response	 = new \Stub\common\VoucherOrder();
				$response	 = $response->setList($list);
				$response	 = Filter::removeNull($response);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(true);
				$msg = 'Sorry! No Records Found.';
				$returnSet->setMessage($msg);
			}
			Logger::info("Response : " . json_encode($returnSet));
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getRedeem()
	{
		$returnSet	 = new ReturnSet();
		$authToken	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;  // '{"code":"YRL23184VQ6A5PEW"}';
		Logger::info("Request : " . $data);
		$jsonObj	 = CJSON::decode($data, false);
		$cardCode	 = $jsonObj->code;
		$res		 = VoucherSubscriber::redeem($cardCode, UserInfo::getUserId());
		if ($res->isSuccess())
		{
			$returnSet->setStatus(true);
			$msg = $res->getMessage();
			$returnSet->setMessage($msg);
		}
		else
		{
			$returnSet->setStatus(false);
			$error = ($res->getErrors()[0]) ? $res->getErrors()[0] : 'Invalid Voucher';
			$returnSet->setMessage($error);
		}
		Logger::info("Response : " . json_encode($returnSet));
		return $returnSet;
	}

}
