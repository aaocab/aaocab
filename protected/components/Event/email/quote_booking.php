<?php

try
{
	$bkgId	 = trim($id);
	/* @var $model Booking */
	$model	 = Booking::model()->findByPk($bkgId);
	//if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0) || ($model->bkgInvoice->bkg_advance_amount > 0 && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)))
	{
		//goto skipAll;
	}

	$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
	if ($response->getStatus())
	{
		$email = $response->getData()->email['email'];
	}

	Logger::create("Agent booking email content check" . $email, CLogger::LEVEL_PROFILE);
	$usertype	 = EmailLog::Consumers;
	$username	 = $model->bkgUserInfo->getUsername();

	if ($email != '')
	{
		//$hash			 = Yii::app()->shortHash->hash($bkgid);
		$payurl			 = Filter::shortUrl(BookingUser::getPaymentLinkByEmail($model->bkg_id));
		$paydate		 = date('Y-m-d H:i:s', strtotime('+12 hour'));
		$date			 = date('d/m/Y', strtotime($paydate));
		$time			 = date('h:i A', strtotime($paydate));
		$workingHrdiff	 = Filter::CalcWorkingHour($model->bkg_create_date, $model->bkg_pickup_date);
		$refCodeUrl		 = "";
		if ($model->bkgUserInfo->bkg_user_id > 0)
		{
			$returnSet = Users::getReferUrl($model->bkgUserInfo->bkg_user_id);
			if ($returnSet->isSuccess())
			{
				$refCodeUrl = $returnSet->getData()['referUrl'];
			}
		}
		$bookarr['userName']	 = $model->bkgUserInfo->getUsername();
		$bookarr['bookingId']	 = Filter::formatBookingId($model->bkg_booking_id);

		//if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
		{
			QrCode::processData($model->bkgUserInfo->bkg_user_id);
			//$this->email_receipient = $email;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
			{
				goto skipAll;
			}

			$luggageCapacity	 = Stub\common\LuggageCapacity::init($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id, $model->bkgAddInfo->bkg_no_person);
			$cancelTimes_new	 = CancellationPolicy::initiateRequest($model); //print_r($cancelTimes_new);
			//-------------------------------------
			$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION);
			$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS);
			$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK);
			$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS);
			$priceRule			 = PriceRule::getByCity($model->bkg_route_city_ids, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_vct_id);

			$params = array('luggageCapacity'		 => $luggageCapacity,
				'refCodeUrl'			 => $refCodeUrl,
				'model'					 => $model,
				'arr'					 => $bookarr,
				'payurl'				 => $payurl,
				'email_receipient'		 => $email,
				'userId'				 => $model->bkgUserInfo->bkg_user_id,
				'otp'					 => $model->bkgTrack->bkg_trip_otp,
				'date'					 => $date . ' ' . $time,
				'timediff'				 => $workingHrdiff,
				'cancelTimes_new'		 => $cancelTimes_new,
				"cancellationPoints"	 => $cancellationPoints,
				'dosdontsPoints'		 => $dosdontsPoints,
				'boardingcheckPoints'	 => $boardingcheckPoints,
				'othertermsPoints'		 => $othertermsPoints,
				'prarr'					 => $priceRule->attributes);
			//-------------------------------------------

			$templateName = 'gotbookingrenew_new';

			$subject = ($model->bkg_status == 15) ? 'Your quotation request: ' . Filter::formatBookingId($model->bkg_booking_id) : 'Reservation received – Booking ID : ' . Filter::formatBookingId($model->bkg_booking_id);
			$lang	 = 'en_US';
			$msgBody = $templateName;
		}
	}
	else
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => $templateName, 'subject' => $subject, 'body' => $msgBody, 'data' => $params, 'status' => true));
}
catch (Exception $ex)
{
	skipAll:
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}