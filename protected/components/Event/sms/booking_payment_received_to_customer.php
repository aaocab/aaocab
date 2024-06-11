<?php
try
{
	$bkgId  = trim($id);
	$msg		 = "Check payment message for booking " . $bkgId;
	$model		 = Booking::model()->findByPk($bkgId);
	$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
	$link		 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
	$paymentLink = Filter::shortUrl($link);
	$sms		 = new Messages();
	$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
	if ($response->getStatus())
	{
		$number	 = $response->getData()->phone['number'];
		$ext	 = $response->getData()->phone['ext'];
	}
	if ($model->bkgInvoice->bkg_wallet_used > 0)
	{
		$msg = $model->bkg_booking_id . ' | Deducted Rs.' . round($model->bkgInvoice->bkg_wallet_used) . ' from your Gozo wallet | See cab driver updates at ' . $paymentLink;
	}
	else
	{
		$msg = $model->bkg_booking_id . ' | Received Rs.' . round($model->bkgInvoice->bkg_advance_amount) . ' | See cab driver updates at ' . $paymentLink . ' - Gozocabs';
	}

	$templateName			 = 'booking_payment_received_to_customer';
	$arrSmsData['content']	 = $msg;
	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => $templateName, 'data' => $arrSmsData, 'status' => true));
} 
catch (Exception $ex) 
{
	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}
