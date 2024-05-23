<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class PayuController extends BaseController
{

	public $layout = 'column1';

	public function actionIntiate()
	{

		$accTransDetailsId	 = Yii::app()->request->getParam('apgid', 0);
		$payRequest			 = PaymentGateway::model()->getPGRequest($accTransDetailsId);


		$param_list					 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['key']			 = Yii::app()->payu->merchant_key;
		$param_list['txnid']		 = $payRequest->transaction_code;
		$param_list['productinfo']	 = $payRequest->description;
		$param_list['amount']		 = $payRequest->trans_amount;
		$param_list['firstname']	 = $payRequest->name;
		$param_list['address1']		 = $payRequest->billingAddress; //
		$param_list['city']			 = $payRequest->city;
		$param_list['state']		 = $payRequest->state;
		$param_list['country']		 = $payRequest->country;
		$param_list['zipcode']		 = $payRequest->postal;
		$param_list['email']		 = $payRequest->email; //Email ID of customer
		$param_list['phone']		 = $payRequest->mobile;

		$param_list['surl']				 = YII::app()->createAbsoluteUrl('payu/response');
		$param_list['furl']				 = YII::app()->createAbsoluteUrl('payu/response');
		$param_list['service_provider']	 = 'payu_paisa';
		//Here checksum string will return by getChecksumFromArray() function.
		$param_list1					 = Yii::app()->payu->getChecksumFromArray($param_list);
		$this->renderPartial('payu', array('param_list' => $param_list1));
	}

	public function actionIndex()
	{
		$param_list			 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['key']	 = Yii::app()->payu->merchant_key;
		/* @var $bkModel Booking */

		$transid	 = Yii::app()->request->getParam('transid', 0);
		$transModel	 = Transactions::model()->findByPk($transid);
		$bkgid		 = $transModel->trans_booking_id;
		$bkModel	 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$order_id					 = $transModel->trans_code;
		$param_list['txnid']		 = $order_id;
		$param_list['productinfo']	 = $bkModel->bkgFromCity->cty_name . '/' . $bkModel->bkgToCity->cty_name . '/' . $bkModel->bkg_booking_id;
		$param_list['amount']		 = $transModel->trans_amount;
		$param_list['firstname']	 = $bkModel->bkgUserInfo->bkg_bill_fullname;
		$param_list['address1']		 = $bkModel->bkgUserInfo->bkg_bill_address; //
		$param_list['city']			 = $bkModel->bkgUserInfo->bkg_bill_city; //Email ID of customer
		$param_list['state']		 = $bkModel->bkgUserInfo->bkg_bill_state;
		$param_list['country']		 = $bkModel->bkgUserInfo->bkg_bill_country;
		$param_list['zipcode']		 = $bkModel->bkgUserInfo->bkg_bill_postalcode;
		$param_list['email']		 = $bkModel->bkgUserInfo->bkg_bill_email;
		$param_list['phone']		 = $bkModel->bkgUserInfo->bkg_bill_contact;

		$param_list['surl']				 = YII::app()->createAbsoluteUrl('payu/response');
		$param_list['furl']				 = YII::app()->createAbsoluteUrl('payu/response');
		$param_list['service_provider']	 = 'payu_paisa';
		//Here checksum string will return by getChecksumFromArray() function.
		$param_list1					 = Yii::app()->payu->getChecksumFromArray($param_list);

		$this->renderPartial('payu', array('param_list' => $param_list1));
	}

	public function actionPartnerpaymentinitiate()
	{
		$param_list			 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['key']	 = Yii::app()->payu->merchant_key;


		$transid = Yii::app()->request->getParam('transid', 0);
		$bkgId	 = Yii::app()->request->getParam('bkgid', 0);

		$accTransId	 = Yii::app()->request->getParam('acctransid', 0);
		$gftId		 = Yii::app()->request->getParam('giftId', 0);

		if ($transid == 0)
		{
			$transid = $accTransId;
		}
		$paymentGateway	 = PaymentGateway::model()->findByPk($transid);
		$agentModel		 = Agents::model()->findByPk($paymentGateway->apg_trans_ref_id, 'agt_active=1');

		if (!$paymentGateway || !$agentModel)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$order_id = $paymentGateway->apg_code;

		$param_list['txnid']		 = $order_id;
		$param_list['productinfo']	 = 'partnercode/' . $agentModel->agt_agent_id;
		$param_list['amount']		 = abs($paymentGateway->apg_amount);

		$param_list['firstname'] = $agentModel->agt_fname . ' ' . $agentModel->agt_lname;
		$param_list['email']	 = $agentModel->agt_email;
		$param_list['phone']	 = $agentModel->agt_phone;
		if ($bkgId > 0)
		{
			$param_list['surl']	 = YII::app()->createAbsoluteUrl('payu/partnerresponse/bkgid/' . $bkgId);
			$param_list['furl']	 = YII::app()->createAbsoluteUrl('payu/partnerresponse/bkgid/' . $bkgId);
		}
		else if ($gftId > 0)
		{
			$param_list['surl']	 = YII::app()->createAbsoluteUrl('payu/partnerresponse/gftid/' . $gftId);
			$param_list['furl']	 = YII::app()->createAbsoluteUrl('payu/partnerresponse/gftid/' . $gftId);
		}
		else
		{
			$param_list['surl']	 = YII::app()->createAbsoluteUrl('payu/partnerresponse');
			$param_list['furl']	 = YII::app()->createAbsoluteUrl('payu/partnerresponse');
		}
		$param_list['service_provider']	 = 'payu_paisa';
		//Here checksum string will return by getChecksumFromArray() function.
		$param_list1					 = Yii::app()->payu->getChecksumFromArray($param_list);
		$this->renderPartial('payu', array('param_list' => $param_list1));
	}

	public function actionChecksum()
	{
		Yii::log(json_encode($_POST), CLogger::LEVEL_INFO);
		$checkSum = Yii::app()->paytm->getChecksumFromArray($_POST);
		Yii::log(json_encode(array("CHECKSUMHASH" => $checkSum, "ORDER_ID" => $_POST["ORDER_ID"], "payt_STATUS" => "1")), CLogger::LEVEL_INFO);
		echo json_encode(array("CHECKSUMHASH" => $checkSum, "ORDER_ID" => $_POST["ORDER_ID"], "payt_STATUS" => "1"));
		Yii::app()->end();
	}

	public function actionVerifychecksum()
	{
		$return_array						 = $_POST;
		$result								 = $this->processResponse();
		$return_array["IS_CHECKSUM_VALID"]	 = $result['IS_CHECKSUM_VALID'];
		unset($return_array["CHECKSUMHASH"]);
		$encoded_json						 = htmlentities(json_encode($return_array));
		Yii::log($encoded_json, CLogger::LEVEL_INFO);
		$this->renderPartial('verifyChecksum', ['encoded_json' => $encoded_json]);
	}

	public function actionSuccess()
	{
		$result = $this->processResponse();

		$hash = Yii::app()->shortHash->hash($result['bkid']);
		$this->redirect(array('payu/summary/bkgid/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}

	public function actionResponse()
	{
		$responseArr = $_POST;
		Logger::create("Response Data: " . json_encode($responseArr));
		$postData	 = array_filter($responseArr);
		$app		 = Yii::app()->request->getParam('app', 0);
		Logger::create("App123: " . json_encode($result));
		$result		 = PaymentGateway::model()->updatePGResponse($postData, PaymentType::TYPE_PAYUMONEY);
		Logger::create("Result123: " . json_encode($result));
		if ($app == 1)
		{
			return $result;
		}
		$hash = Yii::app()->shortHash->hash($result['bkid']);
		if ($result['success'])
		{
			$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
		}
		$this->redirect(array('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}

	public function actionPartnerresponse()
	{
		$bookingId	 = Yii::app()->request->getParam('bkgid', 0);
		$giftId		 = Yii::app()->request->getParam('gftid', 0);
		$result		 = ['success' => true];
		$postData	 = array_filter($_POST);
		$response	 = json_encode($postData);

		Logger::create("Data received by payu: " . $response);
		$status		 = $_POST["status"];
		$firstname	 = $_POST["firstname"];
		$amount		 = $_POST["amount"];
		$transCode	 = $_POST["txnid"];
		$posted_hash = $_POST["hash"];
		$txnid		 = $_POST["mihpayid"];
		$key		 = $_POST["key"];
		$productinfo = $_POST["productinfo"];
		$email		 = $_POST["email"];
		$salt		 = Yii::app()->payu->merchant_salt;

		$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $transCode . '|' . $key;

		$paymentGateway = PaymentGateway::model()->getByCode($transCode);

		$result['bkid']		 = $bookingId;
		$result['giftid']	 = $giftId;
		$result['tinfo']	 = $transCode;
		$hash				 = hash("sha512", $retHashSeq);

		if ($hash != $posted_hash)
		{
			echo "Invalid Transaction. Please try again";
		}
		else
		{
			if ($status == 'success')
			{
				if ($giftId > 0)
				{
					$paymentGateway->giftCardSuccessTransaction($response, 1, Accounting::AT_GIFTCARD, $giftId);
				}
				else
				{
					$paymentGateway->successTransaction($response, 1, Accounting::AT_PARTNER);
				}
				if ($bookingId > 0)
				{
					$params['blg_ref_id']	 = $paymentGateway->apg_id;
					Agents::model()->onRechargeUpdateBooking($bookingId);
					$hash					 = Yii::app()->shortHash->hash($result['bkid']);
					$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
				if ($giftId > 0)
				{
					$params['blg_ref_id']	 = $paymentGateway->apg_id;
					$hash					 = Yii::app()->shortHash->hash($result['giftid']);
					$this->redirect(array('agent/giftcard/add/gftId/' . $giftId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
			}
			else
			{

				$result['success'] = false;
				$paymentGateway->udpdateResponseByCode($response, 2);
				if ($bookingId > 0)
				{
					$params['blg_ref_id']	 = $paymentGateway->apg_id;
					$hash					 = Yii::app()->shortHash->hash($bookingId);
					$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
				if ($giftId > 0)
				{
					$params['blg_ref_id']	 = $paymentGateway->apg_id;
					$hash					 = Yii::app()->shortHash->hash($giftId);
					$this->redirect(array('agent/giftcard/add/gftId/' . $giftId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
			}
		}
		$this->redirect(array('agent/users/recharge/tinfo/' . $paymentGateway->apg_code));
	}

	public function actionVendorresponse()
	{
		Logger::create("Data received by payu: " . serialize($_POST));
		Yii::log("Data received by payu: " . serialize($_POST), CLogger::LEVEL_WARNING, 'system.api.images');
		$result = $this->processVendorResponse();
	}

	public function processResponse($postData)
	{
		$result = ['success' => true];

		$payResponse = Yii::app()->payu->parseResponse($postData);

		$response		 = $payResponse->response;
		$orderId		 = $payResponse->transaction_code;
		$transModel1	 = PaymentGateway::model()->getByCode($orderId);
		$result['bkid']	 = $transModel1->apg_booking_id;
		$result['tinfo'] = $orderId;

		if ($payResponse->payment_status == 1)
		{
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

	public function processVendorResponse()
	{

		Logger::create("Data received by payu1: " . json_encode($_POST));
		$result		 = ['success' => true];
		$postData	 = array_filter($_POST);
		$response	 = json_encode($postData);
		$status		 = $_POST["status"];
		$firstname	 = $_POST["firstname"];
		$amount		 = $_POST["amount"];
		$transCode	 = $_POST["txnid"];
		$posted_hash = $_POST["hash"];
		$txnid		 = $_POST["mihpayid"];
		$key		 = $_POST["key"];
		$productinfo = $_POST["productinfo"];
		$email		 = $_POST["email"];
		$salt		 = Yii::app()->payu->merchant_salt;

		$retHashSeq		 = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $transCode . '|' . $key;
		$paymentGateway	 = PaymentGateway::model()->getByCode($transCode);
		$result['tinfo'] = $transCode;
		$hash			 = hash("sha512", $retHashSeq);
		//echo $hash;
		if ($hash != $posted_hash)
		{
			echo "Invalid Transaction. Please try again";
		}
		else
		{
			if ($status == 'success')
			{
				//$vTransModel->udpdateResponseByCode($response, 1);
				$onlinePayment					 = 1;
				$paymentGateway->apg_user_type	 = BookingLog::Vendor;
				$paymentGateway->apg_user_id	 = Yii::app()->user->getId();
				$paymentGateway->successTransaction($response, 1, Accounting::AT_OPERATOR);
			}
			else
			{
				$result['success'] = false;
				$paymentGateway->udpdateResponseByCode($response, 2);
				if ($paymentGateway)
				{
					$params['blg_ref_id'] = $paymentGateway->apg_id;
					BookingLog::model()->createLog($paymentGateway->apg_booking_id, "Online payment failed ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
				}
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
		/* @var $transModel Transactions */
		if ($transactioId > 0)
		{
			$transid = $transactioId;
		}
		else
		{
			$transid = Yii::app()->request->getParam('transid', 0);
		}
		$transModel				 = Transactions::model()->findByPk($transid);
		$transModelold			 = Transactions::model()->findByPk($transModel->trans_ref_id);
		$paramList				 = array();
		$pmtId					 = $transModelold->trans_txn_id;
		$amount					 = -1 * $transModel->trans_amount;
		$responseArr			 = [];
		$url					 = Yii::app()->payu->refund_url;
		$data['merchantKey']	 = Yii::app()->payu->merchant_key;
		$data['paymentId']		 = $pmtId;
		$data['refundAmount']	 = trim($amount . '');

		$auth		 = Yii::app()->payu->merchant_authorization;
		$options	 = array(
			'http' => array(
				'header'		 => "Authorization: $auth",
				'method'		 => 'POST',
				'Authorization'	 => "$auth",
				'content'		 => http_build_query($data)
			),
		);
		$context	 = stream_context_create($options);
		$response	 = file_get_contents($url, false, $context);
		$responseArr = json_decode($response, true);

		if ($responseArr['status'] === 0)
		{

			Booking::model()->updateRefund($transModel->trans_code, $response, UserInfo::getInstance());
		}
		else
		{
			$result['success'] = false;
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
		/* @var $paymentGateway PaymentGateway */
		if ($transactioId > 0)
		{
			$transid = $transactioId;
		}
		else
		{
			$transid = Yii::app()->request->getParam('transid', 0);
			$manual	 = true;
		}
		$paymentGateway	 = PaymentGateway::model()->findByPk($transid);
		$oldTXNID		 = PaymentGateway::model()->findByPk($paymentGateway->apg_ref_id)->apg_txn_id;
		$url			 = Yii::app()->payu->refund_url;

		$data['paymentId']		 = $oldTXNID;
		$data['refundAmount']	 = (-1 * $paymentGateway->apg_amount);

		$responseArr = Yii::app()->payu->callAuthApi($url, $data);
		$response	 = json_encode($responseArr);

		if ($responseArr['status'] === 0)
		{

			Booking::model()->updateAccRefund($paymentGateway->apg_code, $response, UserInfo::getInstance());
			if ($manual)
			{
				$paymentGateway->refresh();
				$paymentGateway->apg_remarks = "Refund success";
				$arrRefundedModels[]		 = $paymentGateway;
				AccountTransactions::model()->addRefundTransaction($arrRefundedModels, 0, [], $paymentGateway->apg_trans_ref_id);
			}
			$isPaymentSuccess = 1;
		}
		else
		{
			$paymentGateway->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
			if ($paymentGateway)
			{
				$params['blg_ref_id'] = $paymentGateway->apg_id;
				BookingLog::model()->createLog($paymentGateway->apg_booking_id, "Online payment failed ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
			}
			$isPaymentSuccess = 2;
		}
		if ($transactioId > 0)
		{
			return true;
		}
		$this->redirect(array('/admin/transaction/list', 'isPaymentSuccess' => $isPaymentSuccess));
	}

	public function actionGetstatus()
	{
		$transid = Yii::app()->request->getParam('transid', 0);
		$tcode	 = Yii::app()->request->getParam('tcode', 0);
		$opt	 = Yii::app()->request->getParam('opt', 0);

		if ($transid > 0)
		{
			$pmodel	 = PaymentGateway::model()->findByPk($transid);
			$tcode	 = $pmodel->apg_code;
		}
		else
		{
			$pmodel = PaymentGateway::model()->getByCode($tcode);
		}
		$tmode	 = $pmodel->apg_mode;
		$url	 = Yii::app()->payu->status_query_url;
		if ($opt == 1)
		{
			$url = Yii::app()->payu->status_detail_query_url;
		}


		$data['merchantTransactionIds'] = $tcode;
//        $resArr                         = [];
		if ($tmode == 1)
		{
			$oldTransModel					 = PaymentGateway::model()->findByPk($pmodel->apg_ref_id);
			$data['merchantTransactionIds']	 = $oldTransModel->apg_code;
		}

		$url = Yii::app()->payu->status_query_url;

		$resArr = Yii::app()->payu->callAuthApi($url, $data);
		var_dump($resArr);
		return $resArr;
	}

}
