<?php
try
{
	$bkgId  = $id;
	$bkgModel		 = Booking::model()->findByPk($id);
	if(!$bkgModel)
	{
		goto skipAll;
	}
	$arrUserDetails	 = WhatsappLog::getUserByBooking($bkgId, $bkgModel);
	$userId			 = $arrUserDetails['userId'];
	$userName		 = $arrUserDetails['userName'] != null && !empty(trim($arrUserDetails['userName'])) ? $arrUserDetails['userName'] : "User";
	// Phone No
	$phoneNo = WhatsappLog::getPhoneNoByBookingId($bkgId, $bkgModel);
	if (!$phoneNo)
	{
		goto skipAll;
	}
	$hash				 = Yii::app()->shortHash->hash($bkgId);
	$bookingId			 = Filter::formatBookingId($bkgModel->bkg_booking_id);
	$cabType			 = $bkgModel->bkgSvcClassVhcCat->scv_label;
	$pickupAddress		 = $bkgModel->bkgFromCity->cty_name;
	$dropAddress		 = $bkgModel->bkgToCity->cty_name;
	$pickupDate			 = DateTimeFormat::DateTimeToLocale($bkgModel->bkg_pickup_date);
	$tripType			 = trim($bkgModel->getBookingType($bkgModel->bkg_booking_type));
	$distance			 = $bkgModel->bkg_trip_distance . ' KM';
	$amount				 = Filter::moneyFormatter($bkgModel->bkgInvoice->bkg_total_amount);
	$advanceAmount		 = Filter::moneyFormatter($bkgModel->bkgInvoice->bkg_advance_amount);
	$drvId				 = $bkgModel->bkgBcb->bcb_driver_id;
	$cabId				 = $bkgModel->bkgBcb->bcb_cab_id;
	$pickupDiffMinutes	 = Filter::getTimeDiff($bkgModel->bkg_pickup_date);
	$link				 = Yii::app()->params['fullBaseURL'] . '/bkpn/' . $bkgId . '/' . $hash;
	$buttonUrl			 = 'bkpn/' . $bkgId . '/' . $hash;
	if (in_array($bkgModel->bkg_status, [5, 6, 7]) && $drvId > 0 && $cabId > 0)
	{
		if ($pickupDiffMinutes > 120)
		{
			$templateName	 = 'only_booking_details_to_customer';
			$lang			 = 'en_GB';
			$arrWhatsAppData = [$userName, $bookingId, $cabType, $pickupAddress, $dropAddress, $pickupDate, $tripType, $distance, $amount, $advanceAmount, $link];
		}
		else
		{
			$templateName	 = 'booking_details_to_customer';
			$lang			 = 'en_US';
			$cabNumber		 = $bkgModel->bkgBcb->bcb_cab_number;
			$driverName		 = $bkgModel->bkgBcb->bcb_driver_name;
			if (!$driverName && $bkgModel->bkgBcb->bcbDriver)
			{
				$drvDetails	 = Drivers::getByDriverId($drvId);
				$driverName	 = $drvDetails['ctt_first_name'] . ' ' . $drvDetails['ctt_last_name'];
			}
			$driverNumber	 = $bkgModel->bkgBcb->bcb_driver_phone;
			$arrWhatsAppData = [$userName, $bookingId, $cabType, $pickupAddress, $dropAddress, $pickupDate, $tripType, $distance, $amount, $advanceAmount, $cabNumber, $driverName, $driverNumber, $link];
		}
	}
	else
	{
		$templateName	 = 'only_booking_details_to_customer';
		$lang			 = 'en_GB';
		$arrWhatsAppData = [$userName, $bookingId, $cabType, $pickupAddress, $dropAddress, $pickupDate, $tripType, $distance, $amount, $advanceAmount, $link];
	}
//	$arrDBData	 = ['entity_type' => UserInfo::TYPE_CONSUMER, 'entity_id' => $userId, 'ref_type' => WhatsappLog::REF_TYPE_BOOKING, 'ref_id' => $bkgId];

	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => $templateName, 'data' => $arrWhatsAppData, 'lang' => $lang, 'status' => true));
}
catch (Exception $ex)
{
	skipAll:
	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => '', 'data' => [], 'lang' => '', 'status' => false));
}

