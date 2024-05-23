<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class EbsController extends BaseController
{

	public $layout = 'column1';

	public function actionIntiate()
	{
		//$mode, $ACCOUNT_ID,$SECRET,$RETURN_URL, $CANCEL_URL;
		if (!Yii::app()->params['ebs'])
		{
			
		}
		$RETURN_URL			 = Yii::app()->createAbsoluteUrl("/ebs/response");
		$CANCEL_URL			 = Yii::app()->createAbsoluteUrl("/ebs/response", array());
		$ebsPayment			 = new EbsPayment($RETURN_URL, $CANCEL_URL);
		$accTransDetailsId	 = Yii::app()->request->getParam('accTransDetailId', 0);
//        $ebsopt            = Yii::app()->request->getParam('ebsopt', 1);
		$pGatewayModel		 = PaymentGateway::model()->findByPk($accTransDetailsId);
		$bkgid				 = $pGatewayModel->apg_booking_id;
		$bkmodel			 = Booking::model()->findByPk($bkgid);
		/* @var $bkmodel Booking */
		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$ebs_post = $ebsPayment->getParams(
				array(
					//   'payment_mode'	 => $ebsopt,
					'amount'		 => $pGatewayModel->apg_amount,
					'reference_no'	 => $pGatewayModel->apg_code,
					'description'	 => $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id,
					'name'			 => $bkmodel->bkgUserInfo->bkg_bill_fullname,
					'address'		 => $bkmodel->bkgUserInfo->bkg_bill_address,
					'city'			 => $bkmodel->bkgUserInfo->bkg_bill_city,
					'state'			 => $bkmodel->bkgUserInfo->bkg_bill_state,
					'postal_code'	 => $bkmodel->bkgUserInfo->bkg_bill_postalcode,
					'country'		 => $bkmodel->bkgUserInfo->bkg_bill_country,
					'phone'			 => $bkmodel->bkgUserInfo->bkg_bill_contact,
					'email'			 => $bkmodel->bkgUserInfo->bkg_bill_email
		));
		$this->render('ebs', array('ebs_post' => $ebs_post));

		$pGatewayModel->scenario = 'ebs_payment';
	}

	public function actionIndex()
	{
		//$mode, $ACCOUNT_ID,$SECRET,$RETURN_URL, $CANCEL_URL;
		if (!Yii::app()->params['ebs'])
		{
			
		}
		$RETURN_URL	 = Yii::app()->createAbsoluteUrl("/ebs/response");
		$CANCEL_URL	 = Yii::app()->createAbsoluteUrl("/ebs/response", array());
		$ebsPayment	 = new EbsPayment($RETURN_URL, $CANCEL_URL);
		$transid	 = Yii::app()->request->getParam('transid', 0);
		$ebsopt		 = Yii::app()->request->getParam('ebsopt', 1);
		$transModel	 = PaymentGateway::model()->findByPk($transid);
		$bkgid		 = $transModel->apg_trans_ref_id;
		$bkmodel	 = Booking::model()->findByPk($bkgid);
		/* @var $bkmodel Booking */
		if (!$bkgid)
		{
			throw new CHttpException(400, "Invalid Payment Request", 400);
		}

		$transModel->trans_bkhash = Yii::app()->shortHash->hash($bkgid);
		//$transModel->ebs_country	 = 'IN';

		$ebs_post = $ebsPayment->getParams(
				array(
					//   'payment_mode'	 => $ebsopt,
					'amount'		 => $transModel->apg_amount,
					'reference_no'	 => $transModel->apg_code,
					'description'	 => $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id,
					'name'			 => $bkmodel->bkgUserInfo->bkg_bill_fullname,
					'address'		 => $bkmodel->bkgUserInfo->bkg_bill_address,
					'city'			 => $bkmodel->bkgUserInfo->bkg_bill_city,
					'state'			 => $bkmodel->bkgUserInfo->bkg_bill_state,
					'postal_code'	 => $bkmodel->bkgUserInfo->bkg_bill_postalcode,
					'country'		 => $bkmodel->bkgUserInfo->bkg_bill_country,
					'phone'			 => $bkmodel->bkgUserInfo->bkg_bill_contact,
					'email'			 => $bkmodel->bkgUserInfo->bkg_bill_email
		));
		$this->render('ebs', array('ebs_post' => $ebs_post));

		$transModel->scenario = 'ebs_payment';

		//	$this->render('userinfo', array('model' => $transModel, 'bkmodel' => $bkModel, 'ebsopt' => $ebsopt));
	}

	public function actionResponse()
	{

		$responseArr = $_GET;
		$result		 = PaymentGateway::model()->updatePGResponse($responseArr, PaymentType::TYPE_EBS);
		$hash		 = Yii::app()->shortHash->hash($result['bkid']);
		if ($result['success'])
		{
		$this->redirect(array('booking/summary/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}
		$this->redirect(array('booking/paynow/action/done/id/' . $result['bkid'] . '/hash/' . $hash . '/tinfo/' . $result['tinfo']));
	}

	public function actionUserinfo()
	{
		$RETURN_URL	 = Yii::app()->createAbsoluteUrl("/ebs/response");
		$CANCEL_URL	 = Yii::app()->createAbsoluteUrl("/ebs/response", array());
		$ebsPayment	 = new EbsPayment($RETURN_URL, $CANCEL_URL);
		if (isset($_POST))
		{
			$transcode	 = $_POST['PaymentGateway']['apg_code'];
			$hash		 = $_POST['PaymentGateway']['apg_bkhash'];
			$transModel	 = PaymentGateway::model()->getByCode($transcode);
			if (!$transModel)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			if ($transModel->apg_booking_id != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$bkmodel = Booking::model()->findByPk($transModel->apg_booking_id);

			if (isset($_POST['PaymentGateway']))
			{
				$arr = Yii::app()->request->getParam('PaymentGateway');

				$transModel->attributes	 = $arr;
				$ebs_post				 = $ebsPayment->getParams(
						array(
							//    'payment_mode'	 => $transModel->ebsopt,
							'amount'		 => $transModel->apg_amount,
							'reference_no'	 => $transModel->apg_code,
							'description'	 => $bkmodel->bkgFromCity->cty_name . '/' . $bkmodel->bkgToCity->cty_name . '/' . $bkmodel->bkg_booking_id,
							'name'			 => $transModel->ebs_name,
							'address'		 => $transModel->ebs_address,
							'city'			 => $transModel->ebs_city,
							'state'			 => $transModel->ebs_state,
							'postal_code'	 => $transModel->ebs_postal_code,
							'country'		 => $transModel->ebs_country,
							'phone'			 => $transModel->ebs_phone,
							'email'			 => $transModel->ebs_email
				));
				$this->render('ebs', array('ebs_post' => $ebs_post));
			}
		}
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

	public function actionSummary()
	{
		$id			 = Yii::app()->request->getParam('bkgid');
		$hash		 = Yii::app()->request->getParam('hash');
		$transCode	 = Yii::app()->request->getParam('tinfo');
		$transArr	 = [];
		if ($id > 0)
		{
			$model = Booking::model()->findByPk($id);
		}
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if (!$model)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$transModel		 = PaymentGateway::model()->getByCode($transCode);
		$transDetails	 = $transModel->apg_response_details;
		$transArr		 = json_decode($transDetails, true);
		$tranStatus		 = $transArr['ResponseCode'];
		$transId		 = $transArr['TransactionID'];
		if ($transModel->apg_booking_id != $id)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$this->render('summary', ['model' => $model, 'succ' => $tranStatus, 'transid' => $transId]);
	}

	public function actionRefundacctrans($transactionId = 0)
	{
		if ($transactionId == 0)
		{
			$transid = Yii::app()->request->getParam('transid', 0);
			$manual	 = true;
		}
		else
		{
			$transid = $transactionId;
		}
		$transModel	 = PaymentGateway::model()->findByPk($transid);
		$paramList	 = array();
		$pmtid		 = PaymentGateway::model()->getPMTIDbyid($transModel->apg_ref_id);
		$oldOrderId	 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$amount		 = -1 * $transModel->apg_amount;

		$paramList['Amount']	 = $amount;
		$paramList['PaymentID']	 = $pmtid;

		$ebsPayment	 = new EbsPayment();
		$responseArr = $ebsPayment->getRefund($paramList);
		$response	 = json_encode($responseArr);

		if ($responseArr['transactionType'] == 'Processing' || $responseArr['transactionType'] == 'Refunded')
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
			$result['success'] = false;
			$transModel->udpdateCMDResponseByCodeForEBS($response);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->apg_id;
				BookingLog::model()->createLog($transModel->apg_booking_id, "Online payment failed ({$transModel->getPaymentType()} - {$transModel->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
			}
		}
		if ($transactionId != 0)
		{
			return true;
		}
		$this->redirect(array('/admin/transaction/list'));
	}

	public function actionGetstatus()
	{
		$transcode	 = Yii::app()->request->getParam('transcode', '');
		$RETURN_URL	 = Yii::app()->createAbsoluteUrl("/ebs/stresponse");
		$CANCEL_URL	 = Yii::app()->createAbsoluteUrl("/ebs/stresponse", array());
		$ebsPayment	 = new EbsPayment($RETURN_URL, $CANCEL_URL);
		$responseArr = $ebsPayment->getPaymentStatus($transcode);
		return $responseArr;
	}

	public function actionStresponse()
	{

		$RETURN_URL		 = Yii::app()->createAbsoluteUrl("/ebs/stresponse");
		$ebsPayment		 = new EbsPayment($RETURN_URL);
		$transDetails	 = $ebsPayment->parseResponse();
		return $transDetails;
	}

}
