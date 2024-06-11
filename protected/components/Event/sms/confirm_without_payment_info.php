<?php
try
{
	$success = true;
	$bkgId	 = trim($id);
	$model	 = Booking::model()->findByPk($bkgId);
	if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
	{
		$success = false;
	}
	if ($success == false)
	{
		goto skipAll;
	}

	$bookingId	 = $model->bkg_booking_id;
	$sms		 = new Messages();
	$minamount	 = $model->bkgInvoice->calculateMinPayment();
	$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
	$phone		 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);

	$paymentUrl	 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
	$paymentUrl	 = Filter::shortUrl($paymentUrl);

	$msg = 'Cab request received. Details at ' . $paymentUrl;
	if (($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '') && $model->bkgInvoice->bkg_due_amount > 0 && ($model->bkg_agent_id == 0 || $model->bkg_agent_id == ''))
	{
		$msg .= ". Pay at least $minamount online before " . $model->getExpTimeCashBack() . " to save up to 50%";
	}

	$bookingTrack = $model->bkgTrack;
	if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0 || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0')
	{
		$msg .= ". Additional " . $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes Journey Break Included";
	}
	$msg .= '.*Subject to T&Cs - aaocab';

	//user
	$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
	if ($response->getStatus())
	{
		$contactNo	 = $response->getData()->phone['number'];
		$countryCode = $response->getData()->phone['ext'];
	}
	else
	{
		goto skipAll;
	}
	$templateName			 = 'confirm_without_payment_info';
	$arrSmsData['content']	 = $msg;

	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => $templateName, 'data' => $arrSmsData, 'status' => $success));
}
catch (Exception $ex)
{
	skipAll:
	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}
