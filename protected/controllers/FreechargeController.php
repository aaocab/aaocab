<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class FreechargeController extends BaseController
{

	public $layout = 'column1';

	public function actionIntiate()
	{
		// $param_list = array();
		// Create an array having all required parameters for creating checksum.



		/* @var $bkModel Booking */

		$accTransDetailsId = Yii::app()->request->getParam('accTransDetailId', 0);

		$accTransModel	 = PaymentGateway::model()->findByPk($accTransDetailsId);
		$bkgid			 = $accTransModel->apg_booking_id;
		$bkmodel		 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}
		$frc		 = new Freecharge();
		$frc_post	 = $frc->getParams(
				array(
					'merchantId'	 => $frc->merchant_id,
					'merchantTxnId'	 => $accTransModel->apg_code,
					'amount'		 => $accTransModel->apg_amount,
					'channel'		 => 'WEB',
					'surl'			 => YII::app()->createAbsoluteUrl('freecharge/response', ['trns' => 'succ']),
					'currency'		 => 'INR',
					'furl'			 => YII::app()->createAbsoluteUrl('freecharge/response', ['trns' => 'fail']),
					'productInfo'	 => $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id,
					'pg'			 => 'CC',
					'customerName'	 => $bkmodel->bkgUserInfo->bkg_bill_fullname,
					'mobile'		 => $bkmodel->bkgUserInfo->bkg_bill_contact,
					'email'			 => $bkmodel->bkgUserInfo->bkg_bill_email
		));
		$txn_url	 = $frc->txn_url;


		$this->render('proceed', array('frc_post' => $frc_post, txn_url => $txn_url));
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
		$frc		 = new Freecharge();
		$frc_post	 = $frc->getParams(
				array(
					'merchantId'	 => $frc->merchant_id,
					'merchantTxnId'	 => $transModel->trans_code,
					'amount'		 => $transModel->trans_amount,
					'channel'		 => 'WEB',
					'surl'			 => YII::app()->createAbsoluteUrl('freecharge/response', ['trns' => 'succ']),
					'currency'		 => 'INR',
					'furl'			 => YII::app()->createAbsoluteUrl('freecharge/response', ['trns' => 'fail']),
					'productInfo'	 => $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id,
					'pg'			 => 'CC',
					'customerName'	 => $bkmodel->bkgUserInfo->bkg_bill_fullname,
					'mobile'		 => $bkmodel->bkgUserInfo->bkg_bill_contact,
					'email'			 => $bkmodel->bkgUserInfo->bkg_bill_email
		));
		$txn_url	 = $frc->txn_url;


		$this->render('proceed', array('frc_post' => $frc_post, txn_url => $txn_url));
	}

	public function actionResponse()
	{
		$responseArr = $_POST;
		$result		 = PaymentGateway::model()->updatePGResponse($responseArr, PaymentType::TYPE_FREECHARGE);
		$hash		 = Yii::app()->shortHash->hash($result['bkid']);
		if ($result['success'])
		{
		$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}
		$this->redirect(array('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}

	public function processResponse($responseArr)
	{
		$result						 = ['success' => true];
		$payResponse				 = Yii::app()->freecharge->parseResponse($responseArr);
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
		// $responseArr = Yii::app()->freecharge->initiateRefund($transid);



		$transModel = Transactions::model()->findByPk($transid);



		$amount								 = -1 * $transModel->trans_amount;
		//   $oldTransModel = Transactions::model()->getByCode($transModel->trans_merchant_ref_id);
		// $trdJson = $oldTransModel->trans_response_details;
		// $trdArr = json_decode($trdJson, true);
		$paramList							 = [];
		$paramList['merchantId']			 = Yii::app()->freecharge->merchant_id;
		$paramList['refundMerchantTxnId']	 = $transModel->trans_code;
		if ($trdArr['txnId'])
		{
			$paramList['txnId'] = $trdArr['txnId'];
		}
		else
		{
			$paramList['merchantTxnId'] = $transModel->trans_merchant_ref_id;
		}

		$paramList['refundAmount']	 = $amount . '';
		$paramListArr				 = Freecharge::getParams($paramList);
		$url						 = Yii::app()->freecharge->refund_url;
		$responseArr				 = Yii::app()->freecharge->callAPI($paramListArr, $url);
		$checksumVerified			 = Yii::app()->freecharge->verifyChecksum($responseArr);
//      array (size=6)
//  'checksum' => string '58a4250716785396634d92e548cac3f6bf79b263e4c5f8c86ca8e35060023d76' (length=64)
//  'merchantTxnId' => string '170331114404007' (length=15)
//  'refundMerchantTxnId' => string '170331121233014' (length=15)
//  'refundTxnId' => string 'XBFCyc9p6UVIH7_170331121233014_1' (length=32)
//  'refundedAmount' => string '50.00' (length=5)
//  'status' => string 'INITIATED' (length=9)

		$response = json_encode($responseArr);
		if ($checksumVerified == '1')
		{
			if (($responseArr['status'] == 'INITIATED' || $responseArr['status'] == 'SUCCESS' || strtolower($responseArr['status']) == 'completed' ) && $responseArr['refundedAmount'] > 0)
			{
				Booking::model()->updateRefund($transModel->trans_code, $response, UserInfo::getInstance());
			}
			else
			{
				$transModel->udpdateResponseByCode($response, Transactions::TXN_STATUS_FAILED);
				if ($transModel)
				{
					$params['blg_ref_id'] = $transModel->trans_id;
					BookingLog::model()->createLog($transModel->trans_booking_id, "Online payment failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
				}
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
		$tcode	 = Yii::app()->request->getParam('tcode', '');
		$tid	 = Yii::app()->request->getParam('tid', '');
		if ($tcode == '' && $tid != '')
		{
			$tcode = PaymentGateway::model()->getCodebyid($tid);
		}
		$result = Freecharge::getTxnStatus($tcode);
		var_dump($result);
		return $result;
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
		// $responseArr = Yii::app()->freecharge->initiateRefund($transid);



		$transModel = PaymentGateway::model()->findByPk($transid);



		$amount								 = -1 * $transModel->apg_amount;
		$paramList							 = [];
		$paramList['merchantId']			 = Yii::app()->freecharge->merchant_id;
		$paramList['refundMerchantTxnId']	 = $transModel->apg_code;
		if ($trdArr['txnId'])
		{
			$paramList['txnId'] = $trdArr['txnId'];
		}
		else
		{
			$paramList['merchantTxnId'] = $transModel->apg_merchant_ref_id;
		}

		$paramList['refundAmount']	 = $amount . '';
		$paramListArr				 = Freecharge::getParams($paramList);
		$url						 = Yii::app()->freecharge->refund_url;
		$responseArr				 = Yii::app()->freecharge->callAPI($paramListArr, $url);
		$checksumVerified			 = Yii::app()->freecharge->verifyChecksum($responseArr);
//      array (size=6)
//  'checksum' => string '58a4250716785396634d92e548cac3f6bf79b263e4c5f8c86ca8e35060023d76' (length=64)
//  'merchantTxnId' => string '170331114404007' (length=15)
//  'refundMerchantTxnId' => string '170331121233014' (length=15)
//  'refundTxnId' => string 'XBFCyc9p6UVIH7_170331121233014_1' (length=32)
//  'refundedAmount' => string '50.00' (length=5)
//  'status' => string 'INITIATED' (length=9)

		$response = json_encode($responseArr);
		if ($checksumVerified == '1')
		{
			if (($responseArr['status'] == 'INITIATED' || $responseArr['status'] == 'SUCCESS' || strtolower($responseArr['status']) == 'completed' ) && $responseArr['refundedAmount'] > 0)
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
		}
		if ($transactioId > 0)
		{
			return true;
		}
		$this->redirect(array('/admin/transaction/list'));
	}

}
