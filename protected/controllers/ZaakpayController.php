<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ZaakpayController extends BaseController
{

	public $layout = 'column1';

	public function actionIntiate()
	{
		// $param_list = array();
		// Create an array having all required parameters for creating checksum.



		/* @var $bkModel Booking */

		$accTransDetailsId	 = Yii::app()->request->getParam('accTransDetailId', 0);
		$ebsopt				 = Yii::app()->request->getParam('ebsopt', 1);
		$accTransModel		 = PaymentGateway::model()->findByPk($accTransDetailsId);
		$bkgid				 = $accTransModel->apg_booking_id;
		$bkmodel			 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}
		$addressJson = json_encode($bkmodel->bkg_bill_address);
		$address	 = json_decode(str_replace('\r\n', ', ', $addressJson));
		$address	 = str_replace(',,', ',', $address);
		$zkpPayment	 = new Zaakpay();
		$zkp_post	 = $zkpPayment->getParams([
			'merchantIdentifier' => $zkpPayment->merchant_id,
			'orderId'			 => $accTransModel->apg_code,
			'returnUrl'			 => YII::app()->createAbsoluteUrl('zaakpay/response'),
			'buyerEmail'		 => $bkmodel->bkg_bill_email,
			'buyerFirstName'	 => $bkmodel->bkg_user_fname,
			'buyerLastName'		 => $bkmodel->bkg_user_lname,
			'buyerAddress'		 => $address,
			'buyerCity'			 => $bkmodel->bkg_bill_city,
			'buyerState'		 => $bkmodel->bkg_bill_state,
			'buyerCountry'		 => 'India',
			'buyerPincode'		 => $bkmodel->bkg_bill_postalcode,
			'buyerPhoneNumber'	 => $bkmodel->bkg_bill_contact,
			'txnType'			 => 1,
			'zpPayOption'		 => 1,
			'mode'				 => $zkpPayment->mode,
			'currency'			 => 'INR',
			'amount'			 => ($accTransModel->apg_amount * 100),
			'merchantIpAddress'	 => $bkmodel->bkg_user_ip,
			'txnDate'			 => date('Y-m-d', strtotime($bkmodel->bkg_create_date)),
			'purpose'			 => 1,
			'productDescription' => substr($bkmodel->bkgFromCity->cty_name, 0, 30) . '/' . substr($bkmodel->bkgToCity->cty_name, 0, 30) . '/' . $bkmodel->bkg_booking_id,
		]);
		$this->render('proceed', array('zkp_post' => $zkp_post));
	}

	public function actionIndex()
	{
		// $param_list = array();
		// Create an array having all required parameters for creating checksum.



		/* @var $bkModel Booking */

		$transid	 = Yii::app()->request->getParam('transid', 0);
		$ebsopt		 = Yii::app()->request->getParam('ebsopt', 1);
		$transModel	 = Transactions::model()->findByPk($transid);
		$bkgid		 = $transModel->trans_booking_id;
		$bkmodel	 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}
		$addressJson = json_encode($bkmodel->bkg_bill_address);
		$address	 = json_decode(str_replace('\r\n', ', ', $addressJson));
		$address	 = str_replace(',,', ',', $address);
		$zkpPayment	 = new Zaakpay();
		$zkp_post	 = $zkpPayment->getParams([
			'merchantIdentifier' => $zkpPayment->merchant_id,
			'orderId'			 => $transModel->trans_code,
			'returnUrl'			 => YII::app()->createAbsoluteUrl('zaakpay/response'),
			'buyerEmail'		 => $bkmodel->bkg_bill_email,
			'buyerFirstName'	 => $bkmodel->bkg_user_fname,
			'buyerLastName'		 => $bkmodel->bkg_user_lname,
			'buyerAddress'		 => $address,
			'buyerCity'			 => $bkmodel->bkg_bill_city,
			'buyerState'		 => $bkmodel->bkg_bill_state,
			'buyerCountry'		 => 'India',
			'buyerPincode'		 => $bkmodel->bkg_bill_postalcode,
			'buyerPhoneNumber'	 => $bkmodel->bkg_bill_contact,
			'txnType'			 => 1,
			'zpPayOption'		 => 1,
			'mode'				 => $zkpPayment->mode,
			'currency'			 => 'INR',
			'amount'			 => ($transModel->trans_amount * 100),
			'merchantIpAddress'	 => $bkmodel->bkg_user_ip,
			'txnDate'			 => date('Y-m-d', strtotime($bkmodel->bkg_create_date)),
			'purpose'			 => 1,
			'productDescription' => substr($bkmodel->bkgFromCity->cty_name, 0, 30) . '/' . substr($bkmodel->bkgToCity->cty_name, 0, 30) . '/' . $bkmodel->bkg_booking_id,
		]);
		$this->render('proceed', array('zkp_post' => $zkp_post));
	}

	public function actionResponse()
	{
		$r = file_get_contents("php://input");
		parse_str($r, $responseArr);
		//$arr1= $arr;
		//$responseArr = $_POST;
//        array(7) (
//  [orderId] => (string) 170329181626026
//  [responseCode] => (string) 100
//  [responseDescription] => (string) The transaction was completed successfully.
//  [checksum] => (string) 705132f2b7c7e7797c58455ecd1e49481740d870b2e6d1cfafc3118e061a478b
//  [amount] => (string) 41500
//  [paymentMethod] => (string) 401288
//  [cardhashid] => (string) CH101
//)
		// var_dump($responseArr);exit;

		$result	 = Zaakpay::processResponse($responseArr);
		$hash	 = Yii::app()->shortHash->hash($result['bkid']);
		$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
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
			$manual	 = true;
		}
		$transModel	 = PaymentGateway::model()->findByPk($transid);
		$paramList	 = array();

		$amount = -1 * $transModel->apg_amount;

		$paramList['merchantIdentifier'] = Yii::app()->zaakpay->merchant_id;
		$paramList['orderId']			 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$paramList['mode']				 = Yii::app()->zaakpay->mode;
		$paramList['description']		 = "Partial Refund of " . $amount;
		$paramList['updateDesired']		 = '22'; /* 7="Capture", 8="Cancel",14="Refunded", 22=”Partial Refund”. */
		$paramList['updateReason']		 = "Partial Refund of " . $amount;
		$paramList['amount']			 = trim($amount * 100);

		$responseArr = Zaakpay::initiateTxnRefund($paramList);


		$recd_checksum	 = $responseArr['checksum'];
		$all			 = Zaakpay::paramString($responseArr);
		$isValidChecksum = Zaakpay::verifyChecksum($recd_checksum, $all);
		$result			 = $responseArr;
		$result['flag']	 = false;
		$refundSuccArr	 = Yii::app()->zaakpay->successRefundArr;

		if ($isValidChecksum == '1')
		{
			$result['flag']	 = true;
			$response		 = json_encode($responseArr);
			if (in_array($responseArr['responsecode'], $refundSuccArr))
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
					BookingLog::model()->createLog($transModel->apg_booking_id, "Online refund failed ({$transModel->getPaymentType()} - {$transModel->apg_code})", UserInfo::getInstance(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
				}
			}
		}
		else
		{
			$responseArr['description']	 = 'Some error in transaction.Please Try again.';
			$response					 = json_encode($responseArr);
			$transModel->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->apg_id;
				BookingLog::model()->createLog($transModel->apg_booking_id, "Online refund failed ({$transModel->getPaymentType()} - {$transModel->apg_code})", UserInfo::getInstance(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
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
		$result	 = Zaakpay::getTxnStatus($tcode);
		var_dump($result);
		return $result;
	}

}
