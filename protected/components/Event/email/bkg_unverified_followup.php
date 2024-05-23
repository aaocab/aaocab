<?php

try
{
	$model		 = Booking::model()->findByPk($id);
	$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
	if ($response->getStatus())
	{
		$email = $response->getData()->email['email'];
	}
	if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)) || $model->bkg_agent_id > 0)
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
	if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
	{
		throw new Exception("Unscribed email", ReturnSet::ERROR_VALIDATION);
	}

	if (isset($email) && $email != '')
	{
		$params['userId']			 = $model->bkgUserInfo->bkg_user_id;
		$params['emailRecepient']	 = $email;
		$params['url']				 = Filter::shortUrl(BookingUser::getPaymentLink($id, 'e'));
		$params['bookingId']		 = Filter::formatBookingId($model->bkg_booking_id);
		$params['full_name']		 = $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname;
		$params['tripType']			 = Booking::model()->getBookingType($model->bkg_booking_type);
		$params['cabType']			 = $model->bkgSvcClassVhcCat->scv_label;
		$params['pickupTime']		 = date("d/M/y", strtotime($model->bkg_pickup_date)) . " " . date("h:i A", strtotime($model->bkg_pickup_date));
		$params['pickupLocation']	 = $model->bkg_pickup_address;
		$params['dropoffLocation']	 = $model->bkg_drop_address;
		$params['totalFare']		 = $model->bkgInvoice->bkg_total_amount;
		$createTime					 = $model->bkg_create_date;
		$hourdiff					 = BookingPref::model()->getWorkingHrsCreateToPickupByID($model->bkg_id);
		$timeTwentyPercent			 = round($hourdiff * 0.2);
		$new_time2					 = date("Y-m-d H:i:s", strtotime('+' . $timeTwentyPercent . ' hours', strtotime($createTime)));
		$new_time					 = ($model->bkgTrail->bkg_quote_expire_date != '') ? $model->bkgTrail->bkg_quote_expire_date : $new_time2;
		$params['expireOn']			 = date('jS M Y (D) h:i A', strtotime($new_time));
		$minPerc					 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bkgPref->bkg_is_gozonow);
		$minamount					 = round($minPerc * $model->bkgInvoice->bkg_total_amount * 0.01);
		$params['minPayment']		 = $minamount;
		$params['contactUs']		 = '+91-90518-77000';
		$subject					 = 'Quote ID:' . Filter::formatBookingId($model->bkg_booking_id) . ' | Fares are about to rise';
		echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => "bkg_unverified_followup", 'subject' => $subject, 'data' => $params, 'status' => true));
	}
	else
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
}
catch (Exception $ex)
{
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}