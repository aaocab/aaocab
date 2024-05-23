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

	$resheduledMsg = "";
	if ($model->bkgPref->bpr_rescheduled_from > 0)
	{
		$oldModel = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
		if (in_array($oldModel->bkg_status, [9, 10]) && in_array($model->bkg_status, [1, 15, 2]))
		{
			$resheduledMsg = "Booking Resheduled From Booking Id: " . Filter::formatBookingId($oldModel->bkg_booking_id);
		}
	}

	if ($model->bkg_agent_id > 0)
	{
		Logger::create("Agent booking email test 2:\t" . "agent_id" . $model->bkg_agent_id, CLogger::LEVEL_PROFILE);
		$hash	 = Yii::app()->shortHash->hash($bkgid);
		$payurl	 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
		$payurl	 = Filter::shortUrl($payurl);
		$params	 = array('model' => $model, 'payurl' => $payurl, 'otp' => $model->bkgTrack->bkg_trip_otp,, 'arr' => ['bookingId' => Filter::formatBookingId($model->bkg_booking_id)], 'resheduledMsg' => $resheduledMsg);

		$subject		 = 'Reservation received – Booking ID : ' . Filter::formatBookingId($model->bkg_booking_id);
		$templateName	 = 'gotbooking_agent';
	}
	else
	{

		QrCode::processData($model->bkgUserInfo->bkg_user_id);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return false;
		}
		//------------------------------------------------------------
		$luggageCapacity	 = Stub\common\LuggageCapacity::init($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id, $model->bkgAddInfo->bkg_no_person);
		$workingHrdiff		 = Filter::CalcWorkingHour($model->bkg_create_date, $model->bkg_pickup_date);
		$cancelTimes_new	 = CancellationPolicy::initiateRequest($model);
		$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
		$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
		$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
		$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS); //print_r($othertermsPoints);
		$params				 = array('luggageCapacity'		 => $luggageCapacity, 'arr'					 => ['bookingId' => Filter::formatBookingId($model->bkg_booking_id)],
			'refCodeUrl'			 => $refCodeUrl, 'model'					 => $model,
			'payurl'				 => $payurl, 'email_receipient'		 => $email, 'userId'				 => $model->bkgUserInfo->bkg_user_id,
			'otp'					 => $model->bkgTrack->bkg_trip_otp,
			'date'					 => $date . ' ' . $time, 'timediff'				 => $workingHrdiff,
			'cancelTimes_new'		 => $cancelTimes_new, "cancellationPoints"	 => $cancellationPoints,
			'dosdontsPoints'		 => $dosdontsPoints, 'boardingcheckPoints'	 => $boardingcheckPoints,
			'othertermsPoints'		 => $othertermsPoints, 'prarr'					 => $priceRule->attributes, 'resheduledMsg'			 => $resheduledMsg);

		$subject		 = 'Reservation received – Booking ID : ' . Filter::formatBookingId($model->bkg_booking_id);
		$templateName	 = 'gotbookingrenew_new';
	}

	$msgBody = $templateName;

	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => $templateName, 'subject' => $subject, 'body' => $msgBody, 'data' => $params, 'status' => true));
}
catch (Exception $ex)
{
	skipAll:
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}


