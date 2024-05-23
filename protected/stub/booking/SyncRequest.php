<?php

namespace Stub\booking;

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * @property \Stub\common\PartnerTransactionDetails $transaction
 */
class SyncRequest
{

	public $bookingId;
	public $bookingTripId;
	public $appId;
	public $endUser;
	public $endUserTypeId;

	/** @var \Stub\common\Discrepancies[] $discrepancies */
	public $discrepancies;

	/** @var \Stub\common\Platform $device */
	public $device;
	public $type;
	public $isOtpVerified;
	public $totalWaitingTime;
	public $remarks;
	public $otpUsed;
	public $latePickup;
	public $dateTime;

	/** @var \Stub\common\PartnerTransactionDetails $transaction */
	public $transaction;

	/** @var \Stub\common\Coordinates $coordinate */
	public $coordinate;

	/** @var \Stub\common\Document $odometer */
	public $odometer;

	/** @var \Stub\common\Document $selfie */
	public $selfie;

	/** @var \Stub\common\Document $covidSafety */
	public $covidSafety; //sanitizer
	public $extraKm;
	public $extraKmCharge;
	public $extraMin;
	public $extraMinCharges;

	/**
	 * This function is used for initializing the default model
	 * @return \BookingTrackLog
	 */
	public function init($userInfo)
	{

		if (empty($userInfo))
		{
			$userInfo = \UserInfo::getInstance();
		}

		$model = new \BookingTrackLog();

		$dateTime = date("Y-m-d H:i:s", strtotime($this->dateTime));

		$model->btl_sync_time	 = $dateTime;
		$model->btl_created		 = new \CDbExpression('now()');

		$coordinate				 = new \Stub\common\Coordinates($this->coordinate->latitude, $this->coordinate->longitude);
		$model->btl_coordinates	 = $coordinate->latitude . "," . $coordinate->longitude;

		$model->btl_event_type_id	 = $this->type;
		$model->btl_event_platform	 = $userInfo->platform;
		$model->btl_user_type_id	 = empty($userInfo->userType) ? $userInfo->getUserType() : $userInfo->userType;
		$model->btl_user_id			 = empty($userInfo->userId) ? $userInfo->getUserId() : $userInfo->userId;
		$model->btl_bkg_id			 = $this->bookingId;

		$bookingModel		 = \Booking::model()->findByPk($this->bookingId);
		$model->btl_bcb_id	 = $bookingModel->bkg_bcb_id;

		if ($bookingModel->attributes['bkg_agent_id'] == 450)
		{
			$model->reff_id = $bookingModel->attributes['bkg_agent_ref_code'];
		}
		$model->btl_appsync_id	 = $this->appId;
		$model->btl_trip_late	 = $this->latePickup;
		$device					 = new \Stub\common\Platform();
		$model->btl_device_info	 = $device->getModel($model, $this->device);
		
		$model->btl_remarks		 = $this->remarks;

		if ($this->type == \BookingTrack::DRIVER_ARRIVED)
		{
			$model->btlBkg->bkgTrack->bkg_trip_arrive_time = $model->btl_sync_time;
		}
		if ($this->type == \BookingTrack::NO_SHOW)
		{
			$model->btlBkg->bkgTrack->bkg_no_show_time = $model->btl_sync_time;
		}
		if ($this->type == \BookingTrack::TRIP_SELFIE)
		{
			$model->btl_doc_checksum = $this->selfie->checksum;
		}
		if ($this->type == \BookingTrack::TRIP_SANITIZER_KIT)
		{
			$model->btl_doc_checksum = $this->covidSafety->checksum;
		}

		if ($this->type == \BookingTrack::TRIP_START || $this->type == \BookingTrack::VOUCHER_UPLOAD || $this->type == \BookingTrack::TRIP_STOP)
		{
			$model->btl_doc_checksum = $this->odometer->checksum;
		}

		if (!in_array($this->type, [\BookingTrack::TRIP_START, \BookingTrack::DRIVER_ARRIVED]))
		{
			goto skipDiscrepancies;
		}

		$distanceDiscrepancies	 = \Filter::calculateDistance($this->coordinate->latitude, $this->coordinate->longitude, $bookingModel->bookingRoutes[0]->brt_from_latitude, $bookingModel->bookingRoutes[0]->brt_from_longitude);
		$address1				 = explode(',', $bookingModel->bkg_pickup_address);
		$startKmLimit			 = (int) ( count($address1) > 3) ? 5 : \Config::get('ride.startkmlimit');

		if ($distanceDiscrepancies < $startKmLimit)
		{
			goto skipDiscrepancies;
		}

		$discrepanciesRemark = [];
		if ((!$this->discrepancies) && $distanceDiscrepancies > $startKmLimit)
		{
			$var				 = '[{"code": 1,"remarks": "Arrived location discrepancy"}]';
			$this->discrepancies = json_decode($var);
		}

		foreach ($this->discrepancies as $discrepancy)
		{
			$discrepancies			 = new \Stub\common\Discrepancies();
			$ee						 = $discrepancies->fillData($model, $discrepancy, $distanceDiscrepancies);
			$discrepanciesRemark[]	 = json_decode($model->btl_discrepancy_remarks, true);
		}
		$model->btl_discrepancy_remarks = json_encode(array_unique($discrepanciesRemark, SORT_REGULAR));

		$model->btlBkg->bkgTrail->btr_is_datadiscrepancy = $model->btlBkg->bkgTrail->btr_is_datadiscrepancy + $model->btl_is_discrepancy;

		$remarksarr = $model->btl_discrepancy_remarks;
		if ($model->btlBkg->bkgTrail->btr_datadiscrepancy_remarks != null)
		{
			$oldRemarksarr = json_decode($bookingModel->bkgTrail->btr_datadiscrepancy_remarks, true);
		}
		$oldRemarksarr											 = json_decode($remarksarr, true);
		$model->btlBkg->bkgTrail->btr_datadiscrepancy_remarks	 = json_encode(array_unique($oldRemarksarr, SORT_REGULAR));

		if ($bookingModel->bkgTrail->btr_datadiscrepancy_remarks != null)
		{
			$arr													 = array_merge(json_decode($bookingModel->bkgTrail->btr_datadiscrepancy_remarks, true), json_decode($model->btlBkg->bkgTrail->btr_datadiscrepancy_remarks, true));
			$unique													 = array_unique($arr, SORT_REGULAR);
			$model->btlBkg->bkgTrail->btr_datadiscrepancy_remarks	 = json_encode($unique);
		}
		skipDiscrepancies:
		return $model;
	}

	/**
	 * This function returns the model object required for start trip
	 * @return [object]
	 */
	public function startTrip($userInfo)
	{
		$model												 = $this->init($userInfo);
		//$model->btlBkg->bkgTrack->btk_bkg_id				 = $model->btl_bkg_id;
		$model->btlBkg->bkgTrack->bkg_trip_start_time		 = $model->btl_sync_time;
		$model->btlBkg->bkgTrack->bkg_trip_start_coordinates = $model->btl_coordinates;
		//$model->btlBkg->bkgTrack->bkg_trip_otp				 = $this->otpUsed;
		$model->btlBkg->bkgTrack->bkg_start_odometer		 = $this->odometer->refValue;
		//	$model->btlBkg->bkgTrack->bkg_is_trip_verified		 = $this->isOtpVerified;


		if (isset($this->isOtpVerified))
		{
			$model->btlBkg->bkgTrack->bkg_is_trip_verified = ($model->btlBkg->bkgPref->bkg_trip_otp_required == 1) ? $this->isOtpVerified : 0;
		}
		else
		{

			$model->btlBkg->bkgTrack->bkg_is_trip_verified = ($model->btlBkg->bkgPref->bkg_trip_otp_required == 1) ? 1 : 0;
		}

		$paydocs					 = new \BookingPayDocs();
		$paydocs->bpay_date			 = date("Y-m-d H:i:s", strtotime($this->dateTime));
		$paydocs->bpay_bkg_id		 = $model->btl_bkg_id;
		$paydocs->bpay_image		 = $this->odometer->frontPath;
		$paydocs->bpay_checksum		 = $this->odometer->checksum;
		$paydocs->bpay_status		 = 2;
		$paydocs->bpay_type			 = 101;
		$paydocs->bpay_app_type		 = \UserInfo::$platform;
		$device						 = new \Stub\common\Platform();
		$paydocs->bpay_device_info	 = $device->getModel($model, $this->device);
		$model->payDocModel			 = $paydocs;
		return $model;
	}

	/**
	 * This function returns the model object required for end trip
	 * @return [object]
	 */
	public function stopTrip($userInfo)
	{
		$model		 = $this->init($userInfo);
		$bkgModel	 = $model->btlBkg;
		$btrModel	 = $bkgModel->bkgTrack;
		$bcbModel	 = $bkgModel->bkgBcb;
		$vhcModel	 = $bcbModel->bcbCab;

		$btrModel->bkg_trip_end_time		 = $model->btl_sync_time;
		$btrModel->bkg_trip_end_coordinates	 = $model->btl_coordinates;
		$btrModel->bkg_end_odometer			 = $this->odometer->refValue;

		$vhcModel->vhc_end_odometer			 = $this->odometer->refValue;
		$vhcModel->vhc_odometer_modified_on	 = new \CDbExpression("NOW()");

		$btrModel->bkg_trip_end_user_id		 = $this->endUser;
		$btrModel->bkg_trip_end_user_type	 = $this->endUserTypeId;

		$paydocs					 = new \BookingPayDocs();
		$paydocs->bpay_date			 = new \CDbExpression("NOW()");
		$paydocs->bpay_bkg_id		 = $model->btl_bkg_id;
		$paydocs->bpay_image		 = $this->odometer->frontPath;
		$paydocs->bpay_checksum	     = $this->odometer->checksum;
		$paydocs->bpay_status		 = 2;
		$paydocs->bpay_type			 = 104;
		$paydocs->bpay_app_type		 = \UserInfo::$platform;
		$paydocs->bpay_device_info	 = json_encode($this->device);
		$model->payDocModel			 = $paydocs;
		return $model;
	}

	/**
	 * This function returns the model object required for voucher upload
	 * @return [object]
	 */
	public function voucherUpload($userInfo)
	{
		$model						 = $this->init($userInfo);
		$paydocs					 = new \BookingPayDocs();
		$paydocs->bpay_date			 = date("Y-m-d H:i:s", strtotime($this->dateTime));
		$paydocs->bpay_bkg_id		 = $model->btl_bkg_id;
		$paydocs->bpay_image		 = $this->odometer->frontPath;
		$paydocs->bpay_checksum		 = $this->odometer->checksum;
		$paydocs->bpay_status		 = 2;
		$paydocs->bpay_type			 = $this->odometer->refValue;
		$paydocs->bpay_app_type		 = \UserInfo::$platform;
		$device						 = new \Stub\common\Platform();
		$paydocs->bpay_device_info	 = $device->getModel($model, $this->device);
		$model->payDocModel			 = $paydocs;
		return $model;
	}

	/**
	 * This function returns the default model object required for other trip events
	 * @return \BookingTrackLog
	 */
	public function defaultModel($userInfo)
	{
		$model = $this->init($userInfo);

		return $model;
	}

	/**
	 * This function returns the model
	 * based on event types
	 */
	public function getModel($userInfo)
	{

		switch ($this->type)
		{

			case \BookingTrack::TRIP_SELFIE:

				$returnModel = $this->uoloadSelfie($userInfo);

				break;
			case \BookingTrack::TRIP_SANITIZER_KIT:

				$returnModel = $this->uoloadSanitizer($userInfo);

				break;

			case \BookingTrack::TRIP_ARROGYA_SETU:

				$returnModel = $this->updateArrogyaSetu($userInfo);

				break;

			case \BookingTrack::TRIP_TERMS_AGREE:

				$returnModel = $this->updateTerms($userInfo);

				break;

			case \BookingTrack::TRIP_START:

				$returnModel = $this->startTrip($userInfo);

				break;

			case \BookingTrack::TRIP_STOP:

				$returnModel = $this->stopTrip($userInfo);
				break;

			case \BookingTrack::VOUCHER_UPLOAD:

				$returnModel = $this->voucherUpload($userInfo);
				break;

			default :

				$returnModel = $this->defaultModel($userInfo);
				break;
		}

		return $returnModel;
	}

	/**
	 * This function returns the model object required for start trip
	 * @return [object]
	 */
	public function uoloadSelfie($userInfo)
	{

		$model = $this->init($userInfo);

		$model->btlBkg->bkgTrack->btk_is_selfie = $this->selfie->refValue;

		$paydocs					 = new \BookingPayDocs();
		$paydocs->bpay_date			 = date("Y-m-d H:i:s", strtotime($this->dateTime));
		$paydocs->bpay_bkg_id		 = $model->btl_bkg_id;
		$paydocs->bpay_image		 = $this->selfie->frontPath;
		$paydocs->bpay_checksum		 = $this->selfie->checksum;
		$paydocs->bpay_status		 = 2;
		$paydocs->bpay_type			 = 107;
		$paydocs->bpay_app_type		 = \UserInfo::$platform;
		$device						 = new \Stub\common\Platform();
		$paydocs->bpay_device_info	 = $device->getModel($model, $this->device);
		$model->payDocModel			 = $paydocs;
		return $model;
	}

	public function uoloadSanitizer($userInfo)
	{
		$model												 = $this->init($userInfo);
		$model->btlBkg->bkgTrack->btk_is_sanitization_kit	 = $this->covidSafety->refValue;

		$paydocs					 = new \BookingPayDocs();
		$paydocs->bpay_date			 = date("Y-m-d H:i:s", strtotime($this->dateTime));
		$paydocs->bpay_bkg_id		 = $model->btl_bkg_id;
		$paydocs->bpay_image		 = $this->covidSafety->frontPath;
		$paydocs->bpay_checksum		 = $this->covidSafety->checksum;
		$paydocs->bpay_status		 = 2;
		$paydocs->bpay_type			 = 108;
		$paydocs->bpay_app_type		 = \UserInfo::$platform;
		$device						 = new \Stub\common\Platform();
		$paydocs->bpay_device_info	 = $device->getModel($model, $this->device);
		$model->payDocModel			 = $paydocs;
		return $model;
	}

	public function updateArrogyaSetu($userInfo)
	{
		$model										 = $this->init($userInfo);
		$model->btlBkg->bkgTrack->btk_aarogya_setu	 = $this->remarks;
		return $model;
	}

	public function updateTerms($userInfo)
	{
		$model											 = $this->init($userInfo);
		$model->btlBkg->bkgTrack->btk_safetyterm_agree	 = $this->remarks;
		return $model;
	}

	public function setData($bkgTrcLog, $bkgmodel)
	{
		/** @var \ReturnSet */
		$this->appId				 = (int) $bkgTrcLog['btl_appsync_id'];
		$this->bookingId			 = (int) $bkgTrcLog['btl_bkg_id'];
		$cordinates					 = explode(",", $bkgTrcLog['btl_coordinates']);
		$this->coordinate->latitude	 = (double) $cordinates[0];
		$this->coordinate->longitude = (double) $cordinates[1];
		$this->dateTime				 = $bkgTrcLog['btl_sync_time'];
		$this->syncStatus			 = 1;
		$this->type					 = (int) $bkgTrcLog['btl_event_type_id'];
		$data						 = \BookingInvoice::getInvoiceExtraTime($this->bookingId);
		$this->extraKm				 = (int) $data['bkg_extra_km'];
		$this->extraKmCharge		 = $data['bkg_extra_km_charge'];
		$this->extraMin				 = (int) $data['bkg_extra_min'];
		$this->extraMinCharges		 = $data['bkg_extra_total_min_charge'];

		if ($bkgTrcLog['btl_is_discrepancy'] == 1)
		{
			$this->discrepancies[]			 = "";
			$this->discrepancies[0]->code	 = (int) $bkgTrcLog['btl_is_discrepancy'];
			if ($bkgTrcLog['btl_is_discrepancy'] == 1)
			{
				$this->discrepancies[0]->remarks = "Arrived location discrepancy";
			}
		}
		switch ($this->type)
		{
			case 101:
				$this->odometer->checksum	 = $bkgTrcLog['btl_doc_checksum'];
				$this->odometer->refValue	 = $bkgmodel->bkgTrack->bkg_start_odometer;
				break;
			case 104:
				$this->odometer->checksum	 = $bkgTrcLog['btl_doc_checksum'];
				$this->odometer->refValue	 = $bkgmodel->bkgTrack->bkg_start_odometer;
				break;
			case 107:
				$this->selfie->checksum		 = $bkgTrcLog['btl_doc_checksum'];
				$this->selfie->refValue		 = $bkgmodel->bkgTrack->btk_is_selfie;
				break;

			case 108:
				$this->covidSafety->checksum = $bkgTrcLog['btl_doc_checksum'];
				$this->covidSafety->refValue = $bkgmodel->bkgTrack->btk_is_sanitization_kit;
				break;

			case 109:
				$this->remarks = $bkgmodel->bkgTrack->btk_aarogya_setu;

				break;
			case 110:
				$this->remarks = $bkgmodel->bkgTrack->btk_safetyterm_agree;

				break;
		}
	}

}

?>