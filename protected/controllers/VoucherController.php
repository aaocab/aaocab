<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VoucherController
 *
 * @author Roy
 */
include_once(dirname(__FILE__) . '/BaseController.php');

class VoucherController extends BaseController
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,pickup,rates', // we only allow deletion via POST request
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
				'actions'	 => array('index', 'delete', 'getdropcitylist', 'Payment',
					'getdroploc', 'getavailable'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'detail', 'cart', 'checkout', 'payment', 'summary', 'orderHistory', 'orders', 'del', 'redeem', 'paynow'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'), 'users'		 => array('admin'),
			),
			['allow', 'actions' => ['invoice'], 'users' => ['*']],
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
	}

	public function actionIndex()
	{
		$this->checkV2Theme();
		$this->pageTitle = 'Available Travel passes & vouchers';
		$data			 = Vouchers::getRecords();
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('list', array('data' => $data), false, $outputJs);
	}

	public function actionDetail()
	{
		$this->checkV2Theme();
		$this->pageTitle = 'Available Travel passes & vouchers';
		$hashVoucherId	 = Yii::app()->request->getParam('voucherId');
		$voucherId		 = Yii::app()->shortHash->unHash($hashVoucherId);
		$errors			 = '';
		try
		{
			/* @var $vchModel Vouchers */
			$vchModel		 = Vouchers::model()->findByPk($voucherId);
			$this->pageTitle = $vchModel->vch_title;
			$model			 = new VoucherSubscriber();
			if (isset($_POST['VoucherSubscriber']))
			{
				$arr				 = Yii::app()->request->getParam('VoucherSubscriber');
				$model->vsb_vch_id	 = $vchModel->vch_id;
				$model->vsb_name	 = $arr['vsb_name'];
				$model->vsb_phone	 = $arr['vsb_phone'];
				$model->vsb_email	 = $arr['vsb_email'];
				$model->vsb_qty		 = $arr['vsb_qty'];
				$model->scenario	 = 'addToCart';
				if (!$model->validate())
				{
					throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				Vouchers::setCart($model->vsb_qty, $vchModel->vch_selling_price, $model, Yii::app()->session['_voucher_sess_id']);
				$this->redirect(array('voucher/cart'));
			}
			$model->vsb_name	 = Yii::app()->session['_voucher_sub_name'];
			$model->vsb_phone	 = Yii::app()->session['_voucher_sub_phone'];
			$model->vsb_email	 = Yii::app()->session['_voucher_sub_email'];
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('details', array('model'			 => $model, 'errors'		 => $errors,
			'voucherModel'	 => $vchModel), false, $outputJs);
	}

	public function actionCart()
	{
		$this->checkV2Theme();
		$this->pageTitle = 'Shopping cart';
		$model			 = new Vouchers();
		$sessionId		 = Yii::app()->session['_voucher_sess_id'];
		$cartData		 = Vouchers::getCart(Vouchers::getCartSessData());
		$cartBalance	 = Vouchers::getCartBalance(Vouchers::getCartSessData());
		$errors			 = '';
		if (Yii::app()->request->getParam('btnCheckout') == 'Checkout')
		{
			if (Yii::app()->user->isGuest)
			{
				$this->redirect(array('/signin'));
			}

			$returnSet = VoucherOrder::updateData($cartData, $cartBalance, $sessionId);
			if ($returnSet->isSuccess())
			{
				$orderNumber = $returnSet->getData()['orderNumber'];
				$this->redirect(['vpay/' . $orderNumber]);
			}
			else
			{
				$errors = ($returnSet->getErrors());
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");

		$this->$method('cartDetails', array('model'			 => $model,
			'cartData'		 => $cartData,
			'cartBalance'	 => $cartBalance,
			'errors'		 => $errors), false, $outputJs);
	}

	public function actionDel()
	{
		$this->checkV2Theme();
		$hashVoucherId	 = Yii::app()->request->getParam('voucherId');
		$voucherId		 = Yii::app()->shortHash->unHash($hashVoucherId);
		$sessionCart	 = Vouchers::getCartSessData();
		unset($sessionCart[$voucherId]);
		$cartData		 = Vouchers::getCart($sessionCart, true);
		$cartBalance	 = Vouchers::getCartBalance($sessionCart);
		$data			 = ['cartData' => $cartData, 'cartBalance' => $cartBalance];
		echo CJSON::encode($data);
	}

	public function actionCheckout()
	{
		$this->checkV2Theme();
		$this->pageTitle = 'Checkout';
		if (Yii::app()->user->isGuest)
		{
			$this->redirect(array('users/view'));
		}
		$model		 = new Users();
		$sessionCart = Vouchers::getCartSessData();
		$orderNumber = Yii::app()->request->getParam('order');
		$cartData	 = Vouchers::getCart($sessionCart);
		$cartBalance = Vouchers::getCartBalance($sessionCart);
		/* @var $model VoucherOrder */
		$model		 = VoucherOrder::model()->getByCode($orderNumber);
		if (!$model || $cartData == null)
		{
			throw new Exception('Invalid data', ReturnSet::ERROR_INVALID_DATA);
		}
		$userId = Yii::app()->user->getId();
		$model->updateBillingDetails($userId);
		$walletBalance	 = UserWallet::getBalance(UserInfo::getUserId());
		
		$this->renderAuto("checkout" . $this->layoutSufix, ['model' => $model,
			'cartData' => $cartData,
			'cartBalance' => $cartBalance,
			'walletBalance'=>$walletBalance,
			'data' => $data]);
	}

	public function actionPayment()
	{
		$this->checkV2Theme();
		if (Yii::app()->user->isGuest)
		{
			$this->redirect(array('users/view'));
		}
		try
		{
			$returnSet	 = new ReturnSet();
			$id			 = Yii::app()->request->getParam('id');
			$hash		 = Yii::app()->request->getParam('hash');
			if ($id == null)
			{
				$vorVal	 = Yii::app()->request->getParam('VoucherOrder');
				$id		 = $vorVal['vor_id'];
				$hash	 = Yii::app()->shortHash->hash($vorVal['vor_id']);
			}
			if (isset($_REQUEST['VoucherOrder']))
			{
				$arr					 = Yii::app()->request->getParam('VoucherOrder');
				$paymentType			 = Yii::app()->request->getParam('VoucherOrder')['paymentType'];
				$payPayment				 = Yii::app()->request->getParam('VoucherOrder')['partialPayment'];
				$payubolt				 = Yii::app()->request->getParam('VoucherOrder')['payubolt'];
				$userInfo				 = UserInfo::getInstance();
				/* @var $modelOrder VoucherOrder */
				$modelOrder				 = VoucherOrder::model()->findByPk($id);
				$modelOrder->scenario	 = 'pay';
				if (!$modelOrder)
				{
					throw new Exception('Invalid data', ReturnSet::ERROR_INVALID_DATA);
				}
				$modelOrder->vor_active		 = 2;
				$modelOrder->vor_total_price = $payPayment;
				$result						 = CActiveForm::validate($modelOrder);
				if ($result == '[]')
				{

					$transaction = DBUtil::beginTransaction();
					if (!$modelOrder->save())
					{
						$return['success']	 = false;
						$return['error']	 = CJSON::decode($result);
						throw new Exception(CJSON::encode($return), ReturnSet::ERROR_VALIDATION);
					}

					$return['success']	 = true;
					$return['id']		 = $id;
					$return['hash']		 = $hash;
					if ($paymentType > 0 && $payPayment > 0)
					{
						$paymentGateway = PaymentGateway::model()->add($paymentType, $payPayment, null, $modelOrder->vor_id, $userInfo, Accounting::AT_VOUCHER, $modelOrder->vor_id);
						if ($paymentGateway)
						{
							if (PaymentType::isOnline($paymentType))
							{
								$url = $paymentGateway->paymentUrl;
							}

							if ($payubolt == 1 && Yii::app()->request->isAjaxRequest)
							{
								$apg_id		 = $paymentGateway->apg_id;
								$payRequest	 = PaymentGateway::model()->getPGRequest($apg_id);
								$pgObject	 = Filter::GetPGObject($payRequest->payment_type);

								$return = $pgObject->initiateRequest($payRequest);

								$return['success'] = true;
								echo CJSON::encode($return);
								$transaction->commit();
								Yii::app()->end();
							}



							if ($paymentType == PaymentType::TYPE_INTERNATIONAL_CARD)
							{
								$ccData			 = $_POST['BraintreeCCForm'];
								$btreeResponse	 = $this->btreeResponse($payment, $modelOrder, $ccData, $paymentGateway);
								$url			 = $btreeResponse['url'];
								$result			 = $btreeResponse['result'];
								$success		 = $btreeResponse['success'];
								if (!$success)
								{
									$modelOrder->addError('bkg_id', 'Error in payment. Please try again.');
									$return['success']		 = false;
									$return['errormessage']	 = $btreeResponse['resArray']['message'];
								}
							}
							$return['url'] = $url;
							DBUtil::commitTransaction($transaction);
						}
					}
				}
				else
				{
					$return['success']	 = false;
					$return['error']	 = CJSON::decode($result);
					throw new Exception(CJSON::encode($return), ReturnSet::ERROR_VALIDATION);
					DBUtil::rollbackTransaction($transaction);
				}
			}

			echo CJSON::encode($return);
			Yii::app()->end();
		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
			Yii::app()->end();
		}
	}

	public function actionOrders()
	{
		$this->checkV3Theme();
		$this->layout	 = 'column2';
		$list			 = VoucherOrder::getHistory(UserInfo::getUserId());
		$pageSize		 = Yii::app()->params['listPerPage'];
		$usersList		 = new CArrayDataProvider($list, array('pagination' => array('pageSize' => Yii::app()->params['listPerPage']),));
		$models			 = $usersList->getData();

		$this->pageTitle = "My Orders (Vouchers)";
		$this->renderAuto("orderList" . $this->layoutSufix, ['models' => $models, 'usersList' => $usersList]);
	}

	public function actionSummary()
	{
		$this->checkV2Theme();
		$this->pageTitle = 'Confirmation summary';
		$id				 = Yii::app()->request->getParam('id');
		$hash			 = Yii::app()->request->getParam('hash');
		$transCode		 = Yii::app()->request->getParam('tinfo');
		if ($id > 0)
		{
			/* @var $model VoucherOrder */
			$model = VoucherOrder::model()->findByPk($id);
		}
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		if (!$model)
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		$this->renderAuto("summary" . $this->layoutSufix, ['model' => $model]);
	}

	public function actionPaynow()
	{
		$this->checkV2Theme();
		try
		{
			$returnSet	 = new ReturnSet();
			$id			 = Yii::app()->request->getParam('id');
			$hash		 = Yii::app()->request->getParam('hash');
			if (isset($_REQUEST['VoucherOrder']))
			{
				$arr					 = Yii::app()->request->getParam('VoucherOrder');
				$paymentType			 = Yii::app()->request->getParam('VoucherOrder')['paymentType'];
				$payPayment				 = Yii::app()->request->getParam('VoucherOrder')['partialPayment'];
				$payubolt				 = Yii::app()->request->getParam('VoucherOrder')['payubolt'];
				$userInfo				 = UserInfo::getInstance();
				/* @var $modelOrder VoucherOrder */
				$modelOrder				 = VoucherOrder::model()->findByPk($id);
				$modelOrder->scenario	 = 'pay';

				if (!$modelOrder)
				{
					throw new Exception('Invalid data', ReturnSet::ERROR_INVALID_DATA);
				}

				$result = CActiveForm::validate($modelOrder);
				if ($result == '[]')
				{

					$transaction = DBUtil::beginTransaction();

					$modelOrder->vor_active		 = 2;
					$modelOrder->vor_total_price = $payPayment;
					if (!$modelOrder->save())
					{
						$return['success']	 = false;
						$return['error']	 = CJSON::decode($result);
						throw new Exception(CJSON::encode($return), ReturnSet::ERROR_VALIDATION);
					}

					$return['success']	 = true;
					$return['id']		 = $id;
					$return['hash']		 = $hash;
					if ($paymentType > 0 && $payPayment > 0)
					{

						$paymentGateway = PaymentGateway::model()->add($paymentType, $payPayment, null, $modelOrder->vor_id, $userInfo, Accounting::AT_VOUCHER);
						if ($paymentGateway)
						{
							//BookingLog::model()->createLog($modelOrder->bkg_id, "Online payment initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_INITIATED, '', $params);
							if (PaymentType::isOnline($paymentType))
							{
								$url = $paymentGateway->paymentUrl;
							}

							if ($payubolt == 1 && Yii::app()->request->isAjaxRequest)
							{
								$apg_id		 = $paymentGateway->apg_id;
								$payRequest	 = PaymentGateway::model()->getPGRequest($apg_id);
								$pgObject	 = Filter::GetPGObject($payRequest->payment_type);

								$return = $pgObject->initiateRequest($payRequest);

								$return['success'] = true;
								echo CJSON::encode($return);
								$transaction->commit();
								Yii::app()->end();
							}



							if ($paymentType == PaymentType::TYPE_INTERNATIONAL_CARD)
							{
								$ccData			 = $_POST['BraintreeCCForm'];
								$btreeResponse	 = $this->btreeResponse($payment, $modelOrder, $ccData, $paymentGateway);
								$url			 = $btreeResponse['url'];
								$result			 = $btreeResponse['result'];
								$success		 = $btreeResponse['success'];
								if (!$success)
								{
									$modelOrder->addError('bkg_id', 'Error in payment. Please try again.');
									$return['success']		 = false;
									$return['errormessage']	 = $btreeResponse['resArray']['message'];
								}
							}
							$return['url'] = $url;
							DBUtil::commitTransaction($transaction);
						}
					}
				}
				else
				{
					$return['success']	 = false;
					$return['error']	 = CJSON::decode($result);
					throw new Exception(CJSON::encode($return), ReturnSet::ERROR_VALIDATION);
					DBUtil::rollbackTransaction($transaction);
				}
			}
		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
			Yii::app()->end();
		}
	}

	public function actionRedeem()
	{
		$this->checkV3Theme();
        if (Yii::app()->user->isGuest)
		{
			$this->redirect('/signin');
		}
		$this->layout	 = 'column2';
		$this->pageTitle = 'Redeem Voucher';
                
		$success		 = false;
		$cardCode		 = Yii::app()->request->getParam('vCode');
		if (isset($_POST['btnRedeem']) && isset($cardCode))
		{					
			$returnSet = VoucherSubscriber::redeem($cardCode, UserInfo::getUserId());
			if($returnSet->isSuccess())
			{
				Yii::app()->user->setFlash('success', $returnSet->getMessage());
			}
			else
			{
				$error = $returnSet->getErrors()[0];
				Yii::app()->user->setFlash('error', $error);
			}
		}

		$this->render('redeemvoucher', array());
	}

}
