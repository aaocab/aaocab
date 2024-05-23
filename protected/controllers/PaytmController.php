
<?php
include_once(dirname(__FILE__) . '/BaseController.php');

class PaytmController extends BaseController
{

	public $layout = 'column1';

	public function actionIntiate()
	{
		$accTransDetailsId	 = Yii::app()->request->getParam('accTransDetailId', 0);
		$accTransModel		 = PaymentGateway::model()->findByPk($accTransDetailsId);
		$bkgid				 = $accTransModel->apg_booking_id;
		$bkModel			 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$booking_info				 = ($bkModel->bkgUserInfo->bkg_user_id == '') ? $bkModel->bkgUserInfo->bkg_booking_id : $bkModel->bkgUserInfo->bkg_user_id;
		$param_list['REQUEST_TYPE']	 = 'DEFAULT';
		$order_id					 = $accTransModel->apg_code;
		$param_list					 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['MID']			 = Yii::app()->paytm->merchant_id;

		$param_list['ORDER_ID']			 = $order_id;
		$param_list['CUST_ID']			 = date('His') . '-' . $booking_info;
		$param_list['INDUSTRY_TYPE_ID']	 = Yii::app()->paytm->industry_type_id;
		$param_list['CHANNEL_ID']		 = Yii::app()->paytm->channel_id;
		$param_list['TXN_AMOUNT']		 = $accTransModel->apg_amount;
		$param_list['WEBSITE']			 = Yii::app()->paytm->website;
		$param_list['MOBILE_NO']		 = $bkModel->bkgUserInfo->bkg_contact_no; //Mobile number of customer
		$param_list['EMAIL']			 = $bkModel->bkgUserInfo->bkg_user_email; //Email ID of customer
		$param_list['CALLBACK_URL']		 = YII::app()->createAbsoluteUrl('paytm/response');
		/* @var $bkModel Booking */

		// $param_list['REQUEST_TYPE'] = 'DEFAULT';
		//  $param_list['TXN_AMOUNT'] = $bkModel->bkg_amount; //INR
//        $param_list['MOBILE_NO']    = $bkModel->bkg_contact_no; //Mobile number of customer
//        $param_list['EMAIL']        = $bkModel->bkg_user_email; //Email ID of customer
		//Here checksum string will return by getChecksumFromArray() function.
		$checkSum = Yii::app()->paytm->getChecksumFromArray($param_list);

		$param_list['CHECKSUMHASH'] = $checkSum;

		//paytm processing begin
		$this->renderPartial('paytm', array('param_list' => $param_list));
	}

	public function actionIndex()
	{
		$param_list						 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['MID']				 = Yii::app()->paytm->merchant_id;
		$param_list['INDUSTRY_TYPE_ID']	 = Yii::app()->paytm->industry_type_id;
		$param_list['CHANNEL_ID']		 = Yii::app()->paytm->channel_id;
		$param_list['WEBSITE']			 = Yii::app()->paytm->website;
		/* @var $bkModel Booking */

		$transid	 = Yii::app()->request->getParam('transid', 0);
		$transModel	 = Transactions::model()->findByPk($transid);
		$bkgid		 = $transModel->trans_booking_id;
		$bkModel	 = Booking::model()->findByPk($bkgid);

		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$booking_info				 = ($bkModel->bkg_user_id == '') ? $bkModel->bkg_booking_id : $bkModel->bkg_user_id;
		$param_list['REQUEST_TYPE']	 = 'DEFAULT';
		$order_id					 = $transModel->trans_code;
		$param_list['ORDER_ID']		 = $order_id;
		//  $param_list['TXN_AMOUNT'] = $bkModel->bkg_amount; //INR
		$param_list['TXN_AMOUNT']	 = $transModel->trans_amount;
		$param_list['CUST_ID']		 = date('His') . '-' . $booking_info;
		$param_list['MOBILE_NO']	 = $bkModel->bkgUserInfo->bkg_contact_no; //Mobile number of customer
		$param_list['EMAIL']		 = $bkModel->bkgUserInfo->bkg_user_email; //Email ID of customer
		$param_list['CALLBACK_URL']	 = YII::app()->createAbsoluteUrl('paytm/response');
		//Here checksum string will return by getChecksumFromArray() function.
		$checkSum					 = Yii::app()->paytm->getChecksumFromArray($param_list);

		$param_list['CHECKSUMHASH'] = $checkSum;

		//paytm processing begin
		$this->renderPartial('paytm', array('param_list' => $param_list));
	}

	public function actionPartnerpaymentinitiate()
	{
		$param_list						 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['MID']				 = Yii::app()->paytm->merchant_id;
		$param_list['INDUSTRY_TYPE_ID']	 = Yii::app()->paytm->industry_type_id;
		$param_list['CHANNEL_ID']		 = Yii::app()->paytm->channel_id;
		$param_list['WEBSITE']			 = Yii::app()->paytm->website;
		/* @var $bkModel Booking */

		$transid = Yii::app()->request->getParam('transid', 0);
		$bkgid	 = Yii::app()->request->getParam('bkgid', 0);
		
		$accTransId = Yii::app()->request->getParam('acctransid', 0);
		$gftId	 = Yii::app()->request->getParam('giftId', 0);

		if ($transid == 0)
		{
			$transid = $accTransId;
		}
		if ($transid != '')
		{
			$paymentGateway	 = PaymentGateway::model()->findByPk($transid);
			$agentModel		 = Agents::model()->findByPk($paymentGateway->apg_trans_ref_id, 'agt_active=1');
			if (!$paymentGateway || !$agentModel)
			{
				throw new CHttpException(400, "Invalid Payment Request", 400);
			}
			$booking_info				 = $paymentGateway->apg_trans_ref_id;
			$param_list['REQUEST_TYPE']	 = 'DEFAULT';
			$order_id					 = $paymentGateway->apg_code;
			$param_list['ORDER_ID']		 = $order_id;
			$param_list['TXN_AMOUNT']	 = abs($paymentGateway->apg_amount);
			$param_list['CUST_ID']		 = date('His') . '-' . $booking_info;
			$param_list['MOBILE_NO']	 = $agentModel->agt_phone; //Mobile number of customer
			$param_list['EMAIL']		 = $agentModel->agt_email; //Email ID of customer
			if ($bkgid > 0)
			{
				$param_list['CALLBACK_URL'] = YII::app()->createAbsoluteUrl('paytm/resaccpartner/bkgid/' . $bkgid);
			}
			else if($gftId > 0)
			{
				$param_list['CALLBACK_URL']	 = YII::app()->createAbsoluteUrl('paytm/resaccpartner/gftid/' . $gftId);
			}
			else
			{
				$param_list['CALLBACK_URL'] = YII::app()->createAbsoluteUrl('paytm/resaccpartner');
			}
		}


		//Here checksum string will return by getChecksumFromArray() function.
		$checkSum = Yii::app()->paytm->getChecksumFromArray($param_list);

		$param_list['CHECKSUMHASH'] = $checkSum;

		//paytm processing begin
		$this->renderPartial('paytm', array('param_list' => $param_list));
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

	public function actionResponse()
	{
		$responseArr = $_POST;
		$result		 = PaymentGateway::model()->updatePGResponse($responseArr, PaymentType::TYPE_PAYTM);
		$hash		 = Yii::app()->shortHash->hash($result['bkid']);
		if ($result['success'])
		{
		$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}
		$this->redirect(array('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}

	public function actionPartnerresponse()
	{
		$bookingId	 = Yii::app()->request->getParam('bkgid');
		$result		 = ['success' => true];
		$transCode	 = $_POST['ORDERID'];
		$transStatus = $_POST['STATUS'];
		$resCode	 = $_POST['RESPCODE'];

		//Reconfirming data from server
		$paramList					 = [];
		$paramList['MID']			 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']		 = $transCode;
		$responseArr				 = Yii::app()->paytm->getTxnStatus($paramList);
		$response					 = json_encode($responseArr);
		$agentTransModel			 = AgentTransactions::model()->getByCode($responseArr['ORDERID']);
		$result['bkid']				 = $bookingId;
		$result['tinfo']			 = $transCode;
		$param_list					 = $_POST;
		$paytm_checksum				 = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
		//verify checksum
		$isValidChecksum			 = Yii::app()->paytm->verifychecksum_e($param_list, $paytm_checksum);
		$result['IS_CHECKSUM_VALID'] = 'N';
		// if checksum matched
		if ($isValidChecksum === true && $responseArr['STATUS'] == $transStatus && $responseArr['TXNAMOUNT'] == $_POST['TXNAMOUNT'])
		{
			if ($responseArr['STATUS'] == 'TXN_SUCCESS')
			{
				$result['IS_CHECKSUM_VALID'] = 'Y';
				$onlinePayment				 = 1;
				$agentTransModel->udpdateResponseByCode($response, 1);
				AgentTransactions::model()->addProcessingCharge($agentTransModel->agt_agent_id, $agentTransModel->agt_trans_amount, $agentTransModel->agt_trans_id);
				if ($bookingId > 0)
				{
					$params['blg_ref_id']	 = $agentTransModel->agt_trans_id;
					AgentTransactions::model()->onRechargeUpdateBooking($bookingId);
					$hash					 = Yii::app()->shortHash->hash($result['bkid']);
					$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $agentTransModel->agt_trans_code));
				}
			}
			elseif ($responseArr['STATUS'] == 'TXN_FAILURE' || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
			{
				$result['success'] = false;
				$agentTransModel->udpdateResponseByCode($response, 2);
				if ($bookingId > 0)
				{
					$hash					 = Yii::app()->shortHash->hash($result['bkid']);
					$params['blg_ref_id']	 = $agentTransModel->agt_trans_id;
					$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $agentTransModel->agt_trans_code));
				}
			}
		}
		else
		{
			$result['success'] = false;
			$agentTransModel->udpdateResponseByCode($response, 2);
			if ($bookingId > 0)
			{
				$hash					 = Yii::app()->shortHash->hash($result['bkid']);
				$params['blg_ref_id']	 = $agentTransModel->agt_trans_id;
				$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $agentTransModel->agt_trans_code));
			}
		}
		$this->redirect(array('agent/users/recharge/tinfo/' . $agentTransModel->agt_trans_code));
	}

	public function actionResaccpartner()
	{
		$bookingId	 = Yii::app()->request->getParam('bkgid');
		$giftId		 = Yii::app()->request->getParam('gftid', 0);
		$result		 = ['success' => true];
		$transCode	 = $_POST['ORDERID'];
		$transStatus = $_POST['STATUS'];
		$resCode	 = $_POST['RESPCODE'];

		//Reconfirming data from server
		$paramList					 = [];
		$paramList['MID']			 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']		 = $transCode;
		$responseArr				 = Yii::app()->paytm->getTxnStatus($paramList);
		$response					 = json_encode($responseArr);
		$paymentGateway				 = PaymentGateway::model()->getByCode($responseArr['ORDERID']);
		$result['bkid']				 = $bookingId;
		$result['giftid']			 = $giftId;
		$result['tinfo']			 = $transCode;
		$param_list					 = $_POST;
		$paytm_checksum				 = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
		//verify checksum
		$isValidChecksum			 = Yii::app()->paytm->verifychecksum_e($param_list, $paytm_checksum);
		$result['IS_CHECKSUM_VALID'] = 'N';
		// if checksum matched
		if ($isValidChecksum === true && $responseArr['STATUS'] == $transStatus && $responseArr['TXNAMOUNT'] == $_POST['TXNAMOUNT'])
		{
			if ($responseArr['STATUS'] == 'TXN_SUCCESS')
			{
				$result['IS_CHECKSUM_VALID'] = 'Y';
				$onlinePayment				 = 1;
				// $paymentGateway->udpdateResponseByCode($response, 1);
				// $paymentGateway->refresh();
				if($giftId > 0 )
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
					//Agents::model()->onRechargeUpdateBooking($bookingId);
					$hash					 = Yii::app()->shortHash->hash($result['giftid']);
					$this->redirect(array('agent/giftcard/add/giftid/' . $giftId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
			}
			elseif ($responseArr['STATUS'] == 'TXN_FAILURE' || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
			{
				$result['success'] = false;
				$paymentGateway->udpdateResponseByCode($response, 2);
				if ($bookingId > 0)
				{
					$hash = Yii::app()->shortHash->hash($result['bkid']);
					$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
				if ($giftId > 0)
				{
					$hash = Yii::app()->shortHash->hash($result['giftid']);
					$this->redirect(array('agent/giftcard/add/giftid/' . $giftId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
				}
			}
		}
		else
		{
			$result['success'] = false;
			$paymentGateway->udpdateResponseByCode($response, 2);
			if ($bookingId > 0)
			{
				$hash = Yii::app()->shortHash->hash($result['bkid']);
				$this->redirect(array('agent/booking/spotsummary/bkgId/' . $bookingId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
			}
			if ($giftId > 0)
			{
				$hash = Yii::app()->shortHash->hash($result['giftid']);
				$this->redirect(array('agent/giftcard/add/giftid/' . $giftId . '/hash/' . $hash . '/tinfo/' . $paymentGateway->apg_code));
			}
		}

		$this->redirect(array('agent/users/recharge/tinfo/' . $paymentGateway->apg_code));
	}

	public function actionAppresponse()
	{
		$return_array			 = $_POST;
		Logger::create("Return Data: " . json_encode($return_array));
		$transCode = $return_array['ORDERID'];
		Logger::create("Transaction Code: " . $transCode);
		$paramList				 = [];		 
		$paramList['ORDERID']	 = $transCode;
		$responseArr			 = Yii::app()->paytm->getTxnStatus($paramList);
		Logger::create("Response Data: " . json_encode($responseArr));
		$result					 = PaymentGateway::model()->updatePGResponse($responseArr, 3);
		Logger::create("Result: " . json_encode($result));

		$return_array["IS_CHECKSUM_VALID"]	 = $result['IS_CHECKSUM_VALID'];
		unset($return_array["CHECKSUMHASH"]);
		$encoded_json						 = htmlentities(json_encode($return_array));
		Yii::log($encoded_json, CLogger::LEVEL_INFO);
		$this->renderPartial('verifyChecksum', ['encoded_json' => $encoded_json]);
	}

	public function actionVendorappresponse()
	{
		$return_array						 = $_POST;
		$result								 = $this->processVendorResponse();
		$return_array["IS_CHECKSUM_VALID"]	 = $result['IS_CHECKSUM_VALID'];
		unset($return_array["CHECKSUMHASH"]);
		$encoded_json						 = htmlentities(json_encode($return_array));
		Yii::log($encoded_json, CLogger::LEVEL_INFO);
		$this->renderPartial('verifyChecksum', ['encoded_json' => $encoded_json]);
	}

	public function processResponse()
	{
		$result = ['success' => true];

		$payResponse				 = Yii::app()->paytm->parseResponse();
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

	public function processVendorResponse()
	{
		$result						 = ['success' => true];
		$transCode					 = $_POST['ORDERID'];
		$transStatus				 = $_POST['STATUS'];
		$resCode					 = $_POST['RESPCODE'];
		$paramList					 = [];
		$paramList['MID']			 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']		 = $transCode;
		$responseArr				 = Yii::app()->paytm->getTxnStatus($paramList);
		$response					 = json_encode($responseArr);
		$transModel1				 = PaymentGateway::model()->getByCode($responseArr['ORDERID']);
		$result['tinfo']			 = $transCode;
		$param_list					 = $_POST;
		$paytm_checksum				 = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
		$isValidChecksum			 = Yii::app()->paytm->verifychecksum_e($param_list, $paytm_checksum);
		$result['IS_CHECKSUM_VALID'] = 'N';
		if ($isValidChecksum === true && $responseArr['STATUS'] == $transStatus && $responseArr['TXNAMOUNT'] == $_POST['TXNAMOUNT'])
		{
			if ($responseArr['STATUS'] == 'TXN_SUCCESS')
			{
				$result['IS_CHECKSUM_VALID'] = 'Y';
				$onlinePayment				 = 1;
				$transModel1->apg_user_type	 = BookingLog::Vendor;
				$transModel1->apg_user_id	 = Yii::app()->user->getId();
				$transModel1->successTransaction($response, 1, Accounting::AT_OPERATOR);
			}
			elseif ($responseArr['STATUS'] == 'TXN_FAILURE' || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
			{
				$result['success'] = false;
				$transModel1->udpdateResponseByCode($response, 2);
				if ($transModel1)
				{
					$params['blg_ref_id'] = $transModel1->apg_id;
					BookingLog::model()->createLog($transModel1->apg_booking_id, "Online payment failed ({$transModel1->getPaymentType()} - {$transModel1->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
				}
			}
		}
		else
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
		$transModel					 = Transactions::model()->findByPk($transid);
		$paramList					 = array();
		$oldtxnid					 = Transactions::model()->getTXNIDbyid($transModel->trans_ref_id);
		$amount						 = -1 * $transModel->trans_amount;
		$paramList['MID']			 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']		 = Transactions::model()->getCodebyid($transModel->trans_ref_id);
		$paramList['TXNTYPE']		 = 'REFUND';
		$paramList['REFUNDAMOUNT']	 = trim($amount . '');
		$paramList['TXNID']			 = $oldtxnid;
		$paramList['REFID']			 = $transModel->trans_code;
		$responseRefundArr			 = Yii::app()->paytm->initiateTxnRefund($paramList);



		// $response1 = json_encode($responseRefundArr);
		//verify response
		$paramListRes			 = [];
		$paramListRes['MID']	 = Yii::app()->paytm->merchant_id;
		$paramListRes['ORDERID'] = $paramList['ORDERID'];
		$paramListRes['REFID']	 = $transModel->trans_code;
		$responseArr1			 = Yii::app()->paytm->getTxnRefundStatus($paramListRes);
		//  $responseArr = $responseArr1['REFUND_LIST'][0];
		// $responseArr =$responseRefundArr;
		$responseArr			 = ($responseArr1['REFUND_LIST'][0]) ? $responseArr1['REFUND_LIST'][0] : $responseArr1;


		$response = json_encode($responseArr);
		//
		if ($responseArr['STATUS'] == 'TXN_SUCCESS')
		{
			Booking::model()->updateRefund($transModel->trans_code, $response, UserInfo::getInstance());
		}
		elseif ($responseArr['STATUS'] == 'TXN_FAILURE' || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
		{
			$result['success'] = false;
			$transModel->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->trans_id;
				BookingLog::model()->createLog($transModel->trans_booking_id, "Online refund failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
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
		$transModel					 = PaymentGateway::model()->findByPk($transid);
		$paramList					 = array();
		$oldtxnid					 = PaymentGateway::model()->getTXNIDbyid($transModel->apg_ref_id);
		$amount						 = -1 * $transModel->apg_amount;
		$paramList['MID']			 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']		 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$paramList['TXNTYPE']		 = 'REFUND';
		$paramList['REFUNDAMOUNT']	 = trim($amount . '');
		$paramList['TXNID']			 = $oldtxnid;
		$paramList['REFID']			 = $transModel->apg_code;
		$responseRefundArr			 = Yii::app()->paytm->initiateTxnRefund($paramList);

		// $response1 = json_encode($responseRefundArr);
		//verify response
		$paramListRes			 = [];
		$paramListRes['MID']	 = Yii::app()->paytm->merchant_id;
		$paramListRes['ORDERID'] = $paramList['ORDERID'];
		$paramListRes['REFID']	 = $transModel->apg_code;
		$responseArr1			 = Yii::app()->paytm->getTxnRefundStatus($paramListRes);
		//  $responseArr = $responseArr1['REFUND_LIST'][0];
		// $responseArr =$responseRefundArr;
		$responseArr			 = ($responseArr1['REFUND_LIST'][0]) ? $responseArr1['REFUND_LIST'][0] : $responseArr1;

		$response = json_encode($responseArr);
		//
		if ($responseArr['STATUS'] == 'TXN_SUCCESS')
		{
			Booking::model()->updateAccRefund($transModel->apg_code, $response, UserInfo::getInstance());
			if ($manual)
			{
				$arrRefundedModels[] = $transModel;
				AccountTransactions::model()->addRefundTransaction($arrRefundedModels, 0, [], $transModel->apg_trans_ref_id);
			}
		}
		elseif ($responseArr['STATUS'] == 'TXN_FAILURE' || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
		{
			$result['success'] = false;
			$transModel->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->apg_id;
				BookingLog::model()->createLog($transModel->apg_booking_id, "Online refund failed ({$transModel->getPaymentType()} - {$transModel->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
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
		$paramList				 = [];
		$transCode				 = Yii::app()->request->getParam('code');
		$paramList['MID']		 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']	 = $transCode;
		$responseArr			 = Yii::app()->paytm->getTxnStatus($paramList);
		$response				 = json_encode($responseArr);
	}

}
