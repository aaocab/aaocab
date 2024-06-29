<?php

try
{
	$bkgId		 = trim($id);
	$bkgModel	 = Booking::model()->findByPk($bkgId);
	if (!$bkgModel)
	{
		goto skipAll;
	}

	$minPerc = Config::getMinAdvancePercent($bkgModel->bkg_agent_id, $bkgModel->bkg_booking_type, $bkgModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgModel->bkgPref->bkg_is_gozonow);
	$minPay	 = round($minPerc * $bkgModel->bkgInvoice->bkg_total_amount * 0.01);
	if ($minPayExtra > 0)
	{
		$minPay = $minPayExtra;
	}
	$userName		 = $bkgModel->bkgUserInfo->bkg_user_fname != null && !empty(trim($bkgModel->bkgUserInfo->bkg_user_fname)) ? $bkgModel->bkgUserInfo->bkg_user_fname : "User";
	$bookingId		 = $bkgModel->bkg_booking_id;
	$cabType		 = $bkgModel->bkgSvcClassVhcCat->scv_label;
	$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
	$toCityName		 = $bkgModel->bkgToCity->cty_name;
	$tripType		 = $bkgModel->getBookingType($bkgModel->bkg_booking_type);
	$tripDistance	 = $bkgModel->bkg_trip_distance."  KM";
	$totalAmt		 = $bkgModel->bkgInvoice->bkg_total_amount;

	$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
	$pickupTime		 = $datePickupDate->format('j/M/y h:i A');

	$buttonUrl	 = ltrim(BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id), '/');
	$paymentUrl	 = 'https://aao.cab/' . $buttonUrl;

	$phoneNo = WhatsappLog::getPhoneNoByBookingId($bkgModel->bkg_id);
	if (!$phoneNo)
	{
		goto skipAll;
	}
	$userId			 = $bkgModel->bkgUserInfo->bkg_user_id;
	$templateName	 = 'customer_booking_payment_request_v2';
	$lang			 = 'en_US';
	$arrWhatsAppData = [$userName, Filter::formatBookingId($bookingId), $cabType, $fromCityName, $toCityName, $pickupTime, $tripType, $tripDistance, Filter::moneyFormatter($totalAmt), Filter::moneyFormatter($minPay), $paymentUrl];
	$arrDBData		 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => $templateName, 'data' => $arrWhatsAppData, 'lang' => $lang, 'status' => true));
}
catch (Exception $ex)
{
	skipAll:
	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => '', 'data' => [], 'lang' => '', 'status' => false));
}