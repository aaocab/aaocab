<?php

try
{
	$bkgId	 = trim($id);
	$model	 = Booking::model()->findByPk($bkgId);
	if ($model->bkg_reconfirm_flag != 1 || !in_array($model->bkg_status, [2, 3, 5]))
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
	$isConfirm = EmailLog::checkBookingConfirmed($bkgId);
	if ($model != '' && (($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0 || $isConfirm == 1) && $resend == 0))
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}

	// Structured Markup Data
	$arrStructureMarkupData	 = Booking::model()->getStructMarkupForBookingConfirmation($model);
	$jsonStructureMarkupData = json_encode($arrStructureMarkupData, JSON_UNESCAPED_SLASHES);

	//QrCode::processData($model->bkgUserInfo->bkg_user_id);

	$oldModel	 = clone $model;
	$usertype	 = EmailLog::Consumers;
	$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
	if ($response->getStatus())
	{
		$email = $response->getData()->email['email'];
	}
	$emailArr	 = [];
	$logArr		 = [];
	if ($model->bkg_agent_id > 0)
	{
		//remove comment when agent panel notificaion will be live
		$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::PAYMENT_CONFIRM);
		$logArr	 = $logArr1['email'];
		foreach ($logArr as $key => $value)
		{
			$emailArr[$value['email']] = $value['name'];
			if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
			{
				unset($emailArr[$value['email']]);
			}
		}
		// remove comment when agent panel notificaion will be live
	}
	if ($model->bkg_agent_id == null || $model->bkg_agent_id == '1249')
	{
		$curTime = Filter::getDBDateTime();
		BookingScheduleEvent::add($bkgId, BookingScheduleEvent::GENERATE_QR_CODE, 'Generate QR Code');
	}

	if ($email != '' || count($emailArr) > 0)
	{
		$bookingTrack	 = $model->bkgTrack;
		$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
		if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
		{
			$templateName = 'confirmbookingflexxi';
		}
		else
		{
			$templateName = 'confirmbookingrenew';
		}
		$bookarr['bkgId']					 = $model->bkg_id;
		$bookarr['userName']				 = $model->bkgUserInfo->getUsername();
		$bookarr['bookingId']				 = Filter::formatBookingId($model->bkg_booking_id);
		$bookarr['fromCity']				 = $model->bkgFromCity->cty_name;
		$bookarr['toCity']					 = $model->bkgToCity->cty_name;
		$bookarr['amount']					 = $model->bkgInvoice->bkg_total_amount;
		$bookarr['advance']					 = $model->bkgInvoice->bkg_advance_amount;
		$bookarr['due']						 = $model->bkgInvoice->bkg_due_amount;
		$bookarr['discount']				 = $model->bkgInvoice->bkg_discount_amount;
		$bookarr['pickupAddress']			 = $model->bkg_pickup_address;
		$bookarr['pickupFormattedMonthDate'] = date('jS M Y (l) ', strtotime($model->bkg_pickup_date));
		$bookarr['pickupTime']				 = date('h:i A', strtotime($model->bkg_pickup_date));
		$bookarr['returnDateTimeFormat']	 = date('jS M Y (l) h:i A', strtotime($model->bkg_return_date));
		$bookarr['cabType']					 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ')';
		$bookarr['tripDistance']			 = $model->bkg_trip_distance;
		$bookarr['expTimeCashBack']			 = $model->getExpTimeCashBack();
		$bookarr['primaryPhone']			 = $model->bkgUserInfo->bkg_contact_no;
		$bookarr['rate_per_km']				 = $model->bkgInvoice->bkg_rate_per_km;
		$bookarr['rate_per_km_extra']		 = $model->bkgInvoice->bkg_rate_per_km_extra;
		//	$bookarr['trip_type']				 = $model->bkg_trip_type;
		$bookarr['booking_type']			 = $model->bkg_booking_type;
		//	$bookarr['dropFormattedMonthDate']	 = date('jS M Y (l) ', strtotime($model->bkg_drop_time));
		//	$bookarr['dropTime']				 = date('h:i A', strtotime($model->bkg_drop_time));
		$bookarr['dropFormattedMonthDate']	 = date('jS M Y (l) ', strtotime($model->bkg_return_date));
		$bookarr['dropTime']				 = date('h:i A', strtotime($model->bkg_return_date));
		$bookarr['dropArea']				 = $model->bkg_drop_address;
		$bookarr['creditsused']				 = ($model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
		$bookarr['crpcreditused']			 = ($model->bkgInvoice->bkg_corporate_credit > 0) ? $model->bkgInvoice->bkg_corporate_credit : 0;
		if ($model->bkgInvoice->bkg_convenience_charge > 0)
		{
			$Model11			 = clone $model;
			$Model11->bkgInvoice->calculateConvenienceFee(0);
			$Model11->bkgInvoice->calculateTotal();
			//$Model11->bkgInvoice->populateAmount(false,true,true,false,$model->bkg_agent_id);
			$minpay				 = $Model11->bkgInvoice->calculateMinPayment();
			$maxTimeAdvPay		 = date('d/m/Y h:i A', strtotime($model->bkgTrail->bkg_payment_expiry_time));
			$amountWithoutCOD	 = $Model11->bkgInvoice->bkg_due_amount;
		}
		$bookarr['minpay']			 = $minpay;
		$bookarr['expirytime']		 = $maxTimeAdvPay;
		$bookarr['cod']				 = $model->bkgInvoice->bkg_convenience_charge;
		$bookarr['amountWithoutCOD'] = $amountWithoutCOD;
		$hash						 = Yii::app()->shortHash->hash($model->bkg_id);
		//$url						 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
		//$payurl						 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
//			$payurl						 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
		$payurl						 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
		$bookarr['payurl']			 = Filter::shortUrl($payurl);
		$refCodeUrl					 = "";
		if ($model->bkgUserInfo->bkg_user_id > 0)
		{
			$returnSet = Users::getReferUrl($model->bkgUserInfo->bkg_user_id);
			if ($returnSet->isSuccess())
			{
				$refCodeUrl = $returnSet->getData()['referUrl'];
			}
		}
		///////////////////////////////////////////
		$cancelTimes_new	 = CancellationPolicy::initiateRequest($model); //print_r($cancelTimes_new);
		//-------------------------------------
		$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
		$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
		$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
		$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS); //print_r($othertermsPoints);
		//print_r($model->bkgSvcClassVhcCat->scv_scc_id);die("dsfdsf");
		$priceRule			 = PriceRule::getByCity($model->bkg_route_city_ids, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_vct_id);

		$resheduledMsg = "";
		if ($model->bkgPref->bpr_rescheduled_from > 0)
		{
			$oldModel			 = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
			$oldFormatBookingId	 = Filter::formatBookingId($oldModel->bkg_booking_id);
			$resheduledMsg		 = "Resheduled From Booking Id: " . $oldFormatBookingId;
			$oldPickupdate		 = date('jS M Y (l) h:i A', strtotime($oldModel->bkg_pickup_date));
			$newPickupdate		 = date('jS M Y (l) h:i A', strtotime($model->bkg_pickup_date));

			$resheduledMsg = "Your booking has been rescheduled from {$oldPickupdate} (reference booking id - {$oldFormatBookingId}) to {$newPickupdate}.";
		}


		$subject = 'Reservation confirmed – Booking ID : ' . Filter::formatBookingId($model->bkg_booking_id);
		$lang	 = 'en_US';
		//$templateName	 = 'booking_details_to_customer';
//		$mail->setData(
//					array('refCodeUrl'				 => $refCodeUrl, 'model'						 => $model, 'payurl'					 => $payurl,
//						'arr'						 => $bookarr, 'otp'						 => $bookingTrack->bkg_trip_otp, 'jsonStructureMarkupData'	 => $jsonStructureMarkupData,
//						'cancelTimes_new'			 => $cancelTimes_new, "cancellationPoints"		 => $cancellationPoints,
//						'dosdontsPoints'			 => $dosdontsPoints, 'boardingcheckPoints'		 => $boardingcheckPoints,
//						'othertermsPoints'			 => $othertermsPoints, 'prarr'						 => $priceRule->attributes,
//						'email_receipient'			 => $email, 'userId'					 => $model->bkgUserInfo->bkg_user_id)
//			);	

		$params	 = array('refCodeUrl'				 => $refCodeUrl, 'model'						 => $model, 'payurl'					 => $payurl,
			'arr'						 => $bookarr, 'otp'						 => $bookingTrack->bkg_trip_otp, 'jsonStructureMarkupData'	 => $jsonStructureMarkupData,
			'cancelTimes_new'			 => $cancelTimes_new, "cancellationPoints"		 => $cancellationPoints,
			'dosdontsPoints'			 => $dosdontsPoints, 'boardingcheckPoints'		 => $boardingcheckPoints,
			'othertermsPoints'			 => $othertermsPoints, 'prarr'						 => $priceRule->attributes,
			'email_receipient'			 => $email, 'userId'					 => $model->bkgUserInfo->bkg_user_id,
			'resheduledMsg'				 => $resheduledMsg);
		//$mail->setData($params);						
		$msgBody = $templateName;

		echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => $templateName, 'subject' => $subject, 'body' => $msgBody, 'data' => $params, 'status' => true));
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