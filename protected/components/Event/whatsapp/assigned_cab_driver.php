<?php

Logger::trace("file start : " . $id);
try
{
	$model				 = BookingCab::model()->findByPk($id);
	$bkgModels			 = $model->bookings;
	$arrWhatsAppData	 = [];
	$arrWhatsAppData[]	 = $id;
	$arrWhatsAppData[]	 = '₹ ' . $model->bcb_vendor_amount;
	$vndId				 = $model->bcb_vendor_id;
	foreach ($bkgModels as $bkgModel)
	{
		$bkgId				 = $bkgModel->bkg_id;
		$bookingId			 = $bkgModel->bkg_booking_id;
		$cabType			 = $bkgModel->bkgSvcClassVhcCat->scv_label;
		$fromCityName		 = $bkgModel->bkgFromCity->cty_name;
		$toCityName			 = $bkgModel->bkgToCity->cty_name;
		$tripType			 = Booking::model()->getBookingType($bkgModel->bkg_booking_type);
		$tripDistance		 = $bkgModel->bkg_trip_distance . ' KM';
		$amtToCollect		 = '₹ ' . $bkgModel->bkgInvoice->bkg_due_amount;
		$datePickupDate		 = new DateTime($bkgModel->bkg_pickup_date);
		$pickupTime			 = $datePickupDate->format('j/M/y h:i A');
		$hashBkgId			 = Yii::app()->shortHash->hash($bkgId);
		$hashVndId			 = Yii::app()->shortHash->hash($vndId);
		$bkvnLink			 = "http://www.aaocab.com/bkvn/{$hashBkgId}/{$hashVndId}";
		$arrWhatsAppData[]	 = Filter::formatBookingId($bookingId);
		$arrWhatsAppData[]	 = $tripType;
		$arrWhatsAppData[]	 = $cabType;
		$arrWhatsAppData[]	 = $pickupTime;
		$arrWhatsAppData[]	 = $fromCityName;
		$arrWhatsAppData[]	 = $toCityName;
		$arrWhatsAppData[]	 = $tripDistance;
		$arrWhatsAppData[]	 = $amtToCollect;
		$arrWhatsAppData[]	 = $bkvnLink;
	}
	$lang			 = 'en_US';
	$templateName	 = 'assigned_trip_to_vendor';
	if (count($bkgModels) > 1)
	{
		$templateName = 'assigned_match_trip_to_vendor';
	}
	Logger::trace("file data : " . json_encode($arrWhatsAppData));
	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => $templateName, 'data' => $arrWhatsAppData, 'status' => true));
}
catch (Exception $ex)
{
	Logger::trace("file level : " . $ex->getMessage());
	echo json_encode(array('type' => TemplateMaster::SEQ_WHATSAPP_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}
Logger::trace("file ends : " . $id);
