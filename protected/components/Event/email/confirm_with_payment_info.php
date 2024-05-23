<?php

try
{
	$bkgId		 = trim($id);
	$model		 = Booking::model()->findByPk($bkgId);
	$userInfo	 = UserInfo::getInstance();
	if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0) || ($model->bkgInvoice->bkg_advance_amount > 0 && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)))
	{
		return false;
	}

	/* @var $model Booking */
	$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
	if ($response->getStatus())
	{
		$email = $response->getData()->email['email'];
	}
	//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
	Logger::create("Agent booking email content check" . $email, CLogger::LEVEL_PROFILE);
	$usertype	 = EmailLog::Consumers;
	$username	 = $model->bkgUserInfo->getUsername();

	//if ($model->bkg_agent_id > 0)
	{
		Logger::create("Agent booking email test 2:\t" . "agent_id" . $model->bkg_agent_id, CLogger::LEVEL_PROFILE);
		$hash					 = Yii::app()->shortHash->hash($bkgid);
		$payurl					 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
		$payurl					 = Filter::shortUrl($payurl);
		$bookarr['bookingId']	 = Filter::formatBookingId($model->bkg_booking_id);

		$params = array('model' => $model, 'payurl' => $payurl, 'otp' => $model->bkgTrack->bkg_trip_otp, 'arr' => ['bookingId' => Filter::formatBookingId($model->bkg_booking_id)]);

		$subject		 = 'Reservation received – Booking ID : ' . Filter::formatBookingId($model->bkg_booking_id);
		$templateName	 = 'gotbooking_agentuser';
	}

	$msgBody = $templateName;
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => $templateName, 'subject' => $subject, 'body' => $msgBody, 'data' => $params, 'status' => true));
}
catch (Exception $ex)
{
	skipAll:
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}


