<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class BtreeController extends BaseController
{

	public $layout = 'column1';

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
		$transModel			 = PaymentGateway::model()->findByPk($transid);
		$paramList			 = array();
		$OldTransaction		 = PaymentGateway::model()->findByPk($transModel->apg_ref_id);
		$txnid				 = $OldTransaction->apg_txn_id;
		//$oldOrderId = $OldTransaction->trans_code;
		$amount				 = -1 * $transModel->apg_amount;
		$dollarToRupeeRate	 = Yii::app()->params['dollarToRupeeRate'];
		$damount			 = round($amount / $dollarToRupeeRate, 2);
		$error				 = '';
		$payment1			 = new BraintreeCCForm('transaction');
		try
		{
			$transactionResponse = Braintree_Transaction::refund($txnid, $damount);
		}
		catch (Exception $e)
		{
			$error = $e->getMessage();
		}
		catch (Exception $e)
		{
			$error = $e->getMessage();
		}
		$message = $transactionResponse->message;
		$success = $transactionResponse->success;

		$responseMessage = ($error != '') ? $error : $transactionResponse->transaction->status . $message;

		$response = json_encode($transactionResponse);

		if ($success)
		{
			$succresponseArr = array_values((array) $transactionResponse->transaction);
			$response		 = json_encode($succresponseArr[0]);
			Booking::model()->updateAcctRefund($transModel->apg_code, $response, UserInfo::getInstance());
			if ($manual)
			{
				$arrRefundedModels[] = $transModel;
				AccountTransactions::model()->addRefundTransaction($arrRefundedModels, 0, [], $transModel->apg_trans_ref_id);
			}
		}
		else
		{
			$resArray			 = ['success' => $success, 'message' => $responseMessage];
			$response			 = json_encode($resArray);
			$result['success']	 = false;
			$transModel->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);

			if ($transModel)
			{
				$params['blg_ref_id']	 = $transModel->apg_id;
				$resmsg					 = ($transModel->apg_response_message == '') ? '' : '; Response : ' . $transModel->apg_response_message;
				BookingLog::model()->createLog($transModel->apg_booking_id, "Refund Process Failed ({$transModel->getPaymentType()} - {$transModel->apg_code})$resmsg", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
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

		$transcode = Yii::app()->request->getParam('transcode', '');

		$payment1 = new BraintreeCCForm('transaction');

		$transModel	 = PaymentGateway::model()->getByCode($transcode);
		$error		 = '';
		try
		{
			$transaction = Braintree_Transaction::find($transModel->apg_txn_id);
		}
		catch (Exception $e)
		{
			$error = $e->getMessage();
		}
//        $tr=  json_encode($transaction, true);
//        $tr=(array) $transaction;
//        var_dump($tr[key($tr)]);
//        foreach ($tr as $a=>$k){
//            echo $k;
//        }
		echo $response = ($error != '') ? $error : $transaction->status;
		return $response;
	}

	public function actionStresponse()
	{

		$RETURN_URL		 = Yii::app()->createAbsoluteUrl("/ebs/stresponse");
		$ebsPayment		 = new EbsPayment($RETURN_URL);
		$transDetails	 = $ebsPayment->parseResponse();
		return $transDetails;
	}

}
