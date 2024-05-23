<?php
include_once(dirname(__FILE__) . '/BaseController.php');

class MobikwikController extends BaseController
{

	public $layout = 'column1';

	public function actionIntiate()
	{
		// $param_list = array();
		// Create an array having all required parameters for creating checksum.



		/* @var $bkModel Booking */



		$accTransDetailsId	 = Yii::app()->request->getParam('accTransDetailId', 0);
		$accTransModel		 = PaymentGateway::model()->findByPk($accTransDetailsId);
		$bkgid				 = $accTransModel->apg_booking_id;
		$bkmodel			 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{

			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$mbkPayment	 = new Mobikwik();
		$mbk_post	 = $mbkPayment->getParams(
				array(
					'amount'		 => round($accTransModel->apg_amount),
					'orderid'		 => $accTransModel->apg_code,
					'redirecturl'	 => YII::app()->createAbsoluteUrl('mobikwik/response'),
					'cell'			 => $bkmodel->bkgUserInfo->bkg_bill_contact,
					'email'			 => $bkmodel->bkgUserInfo->bkg_bill_email,
					'txn_url'		 => $mbkPayment->txn_url
		));
		$this->render('proceed', array('mbk_post' => $mbk_post));
	}

	public function actionIndex()
	{
		// $param_list = array();
		// Create an array having all required parameters for creating checksum.



		/* @var $bkModel Booking */


		$transid	 = Yii::app()->request->getParam('transid', 0);
		$transModel	 = Transactions::model()->findByPk($transid);
		$bkgid		 = $transModel->trans_booking_id;
		$bkmodel	 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}
		$mbkPayment	 = new Mobikwik();
		$mbk_post	 = $mbkPayment->getParams(
				array(
					'amount'		 => round($transModel->trans_amount),
					'orderid'		 => $transModel->trans_code,
					'redirecturl'	 => YII::app()->createAbsoluteUrl('mobikwik/response'),
					'cell'			 => $bkmodel->bkgUserInfo->bkg_bill_contact,
					'email'			 => $bkmodel->bkgUserInfo->bkg_bill_email,
					'txn_url'		 => $mbkPayment->txn_url
		));
		$this->render('proceed', array('mbk_post' => $mbk_post));
	}

	public function actionResponse()
	{
		$responseArr = $_POST;
		$result		 = PaymentGateway::model()->updatePGResponse($responseArr, PaymentType::TYPE_MOBIKWIK);
		$hash		 = Yii::app()->shortHash->hash($result['bkid']);
		if ($result['success'])
		{
		$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}
		$this->redirect(array('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}

	public function processResponse()
	{
		$result						 = ['success' => true];
		$payResponse				 = Yii::app()->mobikwik->parseResponse();
		$response					 = $payResponse->response;
		$orderId					 = $payResponse->transaction_code;
		$transModel1				 = PaymentGateway::model()->getByCode($orderId);
		$result['bkid']				 = $transModel1->apg_booking_id;
		$result['tinfo']			 = $orderId;
		$result['IS_CHECKSUM_VALID'] = 'N';
		if ($payResponse->payment_status == 1)
		{
			$result['IS_CHECKSUM_VALID'] = 'Y';
			$transModel1->apg_user_type	 = UserInfo::TYPE_CONSUMER;
			$transModel1->apg_user_id	 = Yii::app()->user->getId();
			$transModel1->successTransaction($response, 1, Accounting::AT_BOOKING);
		}
		else if ($payResponse->payment_status == 2)
		{
			$result['success'] = false;
			$transModel1->udpdateResponseByCode($response, 2);
			if ($transModel1)
			{
				$params['blg_ref_id'] = $transModel1->apg_id;
				BookingLog::model()->createLog($transModel1->apg_booking_id, "Online payment failed ({$transModel1->getPaymentType()} - {$transModel1->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
			}
		}
		return $result;
	}

	public function actionConfirm()
	{
		$id = Yii::app()->request->getParam('bkgid');
		if ($id > 0)
		{
			$model = Booking::model()->findByPk($id);
		}
		$this->render('confirm', ['model' => $model]);
	}

	public function actionError()
	{
		$id = Yii::app()->request->getParam('bkgid');
		if ($id > 0)
		{
			$model = Booking::model()->findByPk($id);
		}
		$this->render('error', ['model' => $model]);
	}

	public function actionRefund($transactioId = 0)
	{
		if ($transactioId > 0)
		{
			$transid = $transactioId;
		}
		else
		{
			$transid = Yii::app()->request->getParam('transid', 0);
		}
		$transModel	 = Transactions::model()->findByPk($transid);
		$paramList	 = array();
		//$oldtxnid = Transactions::model()->getTXNIDbyid($transModel->trans_ref_id);
		$amount		 = -1 * $transModel->trans_amount;


		$paramList['mid']		 = Yii::app()->mobikwik->merchant_id;
		$paramList['txid']		 = Transactions::model()->getCodebyid($transModel->trans_ref_id);
		$paramList['ispartial']	 = 'yes';
		$paramList['amount']	 = trim($amount . '');


		$responseArr = Yii::app()->mobikwik->initiateTxnRefund($paramList);

		$response = json_encode($responseArr);

		if ($responseArr['statuscode'] == '0')
		{

			Booking::model()->updateRefund($transModel->trans_code, $response, UserInfo::getInstance());
		}
		else
		{
			$transModel->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->trans_id;
				BookingLog::model()->createLog($transModel->trans_booking_id, "Online payment failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
			}
		}
		if ($transactioId > 0)
		{
			return true;
		}
		$this->redirect(array('/admin/transaction/list'));
	}

	public function actionRefundacctrans($transactioId = 0)
	{
		if ($transactioId > 0)
		{
			$transid = $transactioId;
		}
		else
		{
			$transid = Yii::app()->request->getParam('transid', 0);
			$manual	 = true;
		}
		$transModel	 = PaymentGateway::model()->findByPk($transid);
		$paramList	 = array();
		$amount		 = -1 * $transModel->apg_amount;


		$paramList['mid']		 = Yii::app()->mobikwik->merchant_id;
		$paramList['txid']		 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$paramList['ispartial']	 = 'yes';
		$paramList['amount']	 = trim($amount . '');


		$responseArr = Yii::app()->mobikwik->initiateTxnRefund($paramList);

		$response = json_encode($responseArr);

		if ($responseArr['statuscode'] == '0')
		{

			Booking::model()->updateAccRefund($transModel->apg_code, $response, UserInfo::getInstance());
			if ($manual)
			{
				$arrRefundedModels[] = $transModel;
				AccountTransactions::model()->addRefundTransaction($arrRefundedModels, 0, [], $transModel->apg_trans_ref_id);
			}
		}
		else
		{
			$transModel->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->apg_id;
				BookingLog::model()->createLog($transModel->apg_booking_id, "Online payment failed ({$transModel->getPaymentType()} - {$transModel->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
			}
		}
		if ($transactioId > 0)
		{
			return true;
		}
		$this->redirect(array('/admin/transaction/list'));
	}

	public function actionGetstatus()
	{
		$tcode	 = Yii::app()->request->getParam('transcode', '');
		$result	 = Mobikwik::getTxnStatus($tcode);
//	var_dump($result);
		return $result;
	}

}
