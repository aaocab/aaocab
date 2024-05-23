<?php

try
{
	$bkgModel		 = Booking::model()->findByPk($id);
	$drvId			 = $bkgModel->bkgBcb->bcb_driver_id;
	$userName		 = $arrayData['userName'];
	$bookingId		 = $bkgModel->bkg_booking_id;
	$cabType		 = $bkgModel->bkgSvcClassVhcCat->scv_label;
	$cabNumber		 = $bkgModel->bkgBcb->bcb_cab_number;
	$driverName		 = $bkgModel->bkgBcb->bcbDriver->drv_name;
	$drvContactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
	$drvContact		 = ContactPhone::getContactPhoneById($drvContactId);
	$isPhone		 = Filter::parsePhoneNumber($drvContact, $drvCode, $drvNumber);
	$driverPhone	 = $drvCode . $drvNumber;
	$datePickupDate	 = new DateTime($bkgModel->bkg_pickup_date);
	$pickupTime		 = $datePickupDate->format('j/M/y h:i A');
	$fromCityName	 = $bkgModel->bkgFromCity->cty_name;
	$toCityName		 = $bkgModel->bkgToCity->cty_name;
	$buttonUrl		 = ltrim(BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id), '/');
	$paymentUrl		 = 'https://www.gozocabs.com/' . $buttonUrl;
	$arrWhatsAppData = [$userName, Filter::formatBookingId($bookingId), $cabType, $cabNumber, $driverName, $driverPhone, $pickupTime, $fromCityName, $paymentUrl];
	$templateName	 = 'driver_details_to_customer';
	if ($bkgModel->bkg_agent_id == Config::get('transferz.partner.id'))
	{
		$templateName	 = 'driver_details_to_customer_for_partner';
		$referenceCode	 = $bkgModel->bkg_agent_ref_code;
		$driverPhone	 = '+' . $drvCode . '-' . $drvNumber;
		if (is_numeric($bkgModel->bkg_agent_ref_code))
		{
			$partnerCode	 = TransferzOffers::getOffer($bkgModel->bkg_agent_ref_code);
			$referenceCode	 = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode;
		}
		$refId				 = ($referenceCode != '') ? $referenceCode : Filter::formatBookingId($bkgModel->bkg_booking_id);
		$assistanceContact	 = '+91-8017233722';
		$arrWhatsAppData	 = [$userName, $pickupTime, $refId, $fromCityName, $toCityName, $cabNumber, $driverName, $driverPhone, $assistanceContact];
	}
	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => $templateName, 'data' => $arrWhatsAppData, 'status' => true));
}
catch (Exception $ex)
{
	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}

