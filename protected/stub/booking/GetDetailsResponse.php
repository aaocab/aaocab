<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GetDetailsResponse
{

	public $partner;
	public $company;
	public $booking_id;
	public $bkgId;
	public $bookingId;
	public $orderRefId;
	public $statusDesc;
	public $statusCode;
	public $tripType;
	public $tripDistance;
	public $tripDuration;
	public $pickupDate;
	public $pickupTime;
	public $bookingDate;
	public $bookingTime;
	public $isRated;
	public $packageName;
	public $isPayable;
	public $isDrvDetailViewed;
	public $otp;
	public $isGozonow;
	public $sosFlag;
	public $isReschedule;

	/** @var \Beans\booking\BillingDetails $billing */
	public $billing;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes = [];

	/** @var \Stub\common\Person $traveller */
	public $traveller;

	/** @var \Stub\common\CabRate[] $cabRate */
	public $cabRate;

	/** @var \Stub\common\Vehicle $car */
	public $car;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\PartnerTransactionDetails $partnerTransactionDetails */
	public $partnerTransactionDetails;

	/** @var \Stub\common\PartnerTransactionDetails $transactionDetails */
	public $transactionDetails;
	public $payUrl;

	/** @var \Stub\common\Transactions[] $transactions */
	public $transactions;

	/** @var \Beans\common\Dbo $dbo */
	public $dbo;

	/**
	 * This function is used for setting booking details	  
	 * @param type $model = Booking Model
	 * @param type $type = Return type
	 * 		0 => Default
	 * 		1 => B2C
	 */
	public function setData(\Booking $model, $packageName = null)
	{
		$result					 = $model->getBookingCodeStatus();
		$this->bkgId			 = (int) $model->bkg_id;
		$this->bookingId		 = $model->bkg_booking_id;
		$this->statusCode		 = (int) $result['code'];
		$this->statusDesc		 = $result['desc'];
		$this->tripType			 = (int) $model->bkg_booking_type;
		$this->tripDistance		 = (int) $model->bkg_trip_distance;
		$this->tripDuration		 = \Filter::getDurationbyMinute($model->bkg_trip_duration);
		$this->pickupDate		 = date("Y-m-d", strtotime($model->bkg_pickup_date));
		$this->pickupTime		 = date("H:i:s", strtotime($model->bkg_pickup_date));
		$this->bookingDate		 = date("Y-m-d", strtotime($model->bkg_create_date));
		$this->bookingTime		 = date("H:i:s", strtotime($model->bkg_create_date));
		$this->isRated			 = \Ratings::isRatingPosted($model->bkg_id);
		$this->isPayable		 = (int) \BookingInvoice::isPayable($model->bkg_id);
		$this->isGozoNow		 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
		$this->isDrvDetailViewed = (int) $model->bkgTrack->btk_drv_details_viewed;
		$this->packageName		 = $packageName;
		$isReschedule			 = 0;
		if (in_array($model->bkg_status, [2, 3, 5]) && $model->bkgPref->bpr_rescheduled_from == 0 && $model->bkgPref->bkg_is_gozonow != 1 && $model->bkg_pickup_date > date('Y-m-d H:i:s'))
		{
			$isReschedule = 1;
		}
		$this->isReschedule = $isReschedule;

		$sosArray		 = \ReportIssue::checkStatusForSos($model->bkg_id);
		$this->sosFlag	 = (int) $sosArray['isSOS'];

		$routes = $model->bookingRoutes;
		foreach ($routes as $route)
		{
			$itinerary		 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$this->routes[]	 = $itinerary;
		}
		$this->traveller = new \Stub\common\Person();
		$this->traveller->setModelData($model->bkgUserInfo, false);

		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate	 = $cabRate;

		$driverObj	 = new \Stub\common\Driver();
		$driverData	 = [
			'drv_name'	 => $model->bkgBcb->bcbDriver->drv_name,
			'drv_phone'	 => (int) $model->bkgBcb->bcb_driver_phone,
			'bkg_id'	 => $model->bkg_id
		];
		$driverData	 = \Filter::convertToObject($driverData);
		if ($model->bkgBcb->bcbDriver->drv_name != '' && in_array($model->bkg_status, [6, 7]))
		{
			$this->driver = $driverObj->setModelData($driverData, false);
		}

		$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
		if ($model->bkgBcb->bcbCab->vhc_type_id === \Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = \OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
		}
		$carData = [
			'vhc_number' => $model->bkgBcb->bcbCab->vhc_number,
			'vht_make'	 => $model->bkgBcb->bcbCab->vhcType->vht_make,
			'vht_model'	 => $model->bkgBcb->bcbCab->vhcType->vht_model
		];
		$carData = \Filter::convertToObject($carData);
		$carObj	 = new \Stub\common\Vehicle();
		if ($model->bkgBcb->bcbCab->vhc_number != '')
		{
			$this->car = $carObj->setModelData($carData);
		}

		if ($model->bkg_agent_id > 0)
		{
			$this->partnerTransactionDetails = new \Stub\common\PartnerTransactionDetails();
			$this->partnerTransactionDetails->setModelData($model->bkgInvoice);
		}

		$trans				 = new \Stub\common\PaymentState();
		$this->transactions	 = $trans->setModels($model->bkg_id);

		$agentGatewayStatus	 = \BookingSub::model()->getAgentGatewayStatus($model->bkg_id);
		$gatewayStatus		 = $agentGatewayStatus['gateway'];
		if ($gatewayStatus == 1)
		{
			$hash			 = \Yii::app()->shortHash->hash($model->bkg_id);
			$paymentLink	 = $_SERVER['HTTP_HOST'] . '/bkpn/' . $model->bkg_id . '/' . $hash;
			$this->payUrl	 = $paymentLink;
			//$this->payUrl = \BookingUser::getPaymentLink($model->bkg_id);
		}
		else
		{
			$this->payUrl = \BookingUser::getPaymentLink($model->bkg_id, 'p');
		}

		$this->billing	 = new \Beans\booking\BillingDetails();
		$this->billing	 = \Beans\booking\BillingDetails::setModel($model->bkgUserInfo);

		$this->dbo	 = new \Beans\common\Dbo();
		$this->dbo	 = \Beans\common\Dbo::getData($model->bkg_pickup_date, $model->bkg_status);
	}

	public function setCabDriver(\Booking $model)
	{
		$result				 = $model->getBookingCodeStatus();
		$this->bookingId	 = $model->bkg_booking_id;
		$this->orderRefId	 = $model->bkg_agent_ref_code;

		$this->statusDesc	 = $result['desc'];
		$this->otp			 = $model->bkgTrack->bkg_trip_otp;

		$driverObj	 = new \Stub\common\Driver();
		$driverData	 = [
			'drv_name'	 => $model->bkgBcb->bcbDriver->drv_name,
			'drv_phone'	 => (int) $model->bkgBcb->bcb_driver_phone,
			'bkg_id'	 => $model->bkg_id
		];
		$driverData	 = \Filter::convertToObject($driverData);
		if ($model->bkgBcb->bcbDriver->drv_name != '')
		{
			$this->driver = $driverObj->setModelData($driverData, false);
		}

		$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
		if ($model->bkgBcb->bcbCab->vhc_type_id === \Config::get('vehicle.genric.model.id'))
		{
			/* @var $vehicleModel OperatorVehicle */
			$vehicleModel = \OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
		}
		$carData = [
			'vhc_number'		 => $model->bkgBcb->bcbCab->vhc_number,
			'vht_make'			 => $model->bkgBcb->bcbCab->vhcType->vht_make,
			'vht_model'			 => $vehicleModel,
			'vhc_id'			 => $model->bkgBcb->bcbCab->vhc_id,
			'vhc_has_cng'		 => $model->bkgBcb->bcbCab->vhc_has_cng,
			'vhc_has_electric'	 => $model->bkgBcb->bcbCab->vhc_has_electric,
			'bkg_id'			 => $model->bkg_id
		];
		$carData = \Filter::convertToObject($carData);
		$carObj	 = new \Stub\common\Vehicle();
		if ($model->bkgBcb->bcbCab->vhc_number != '')
		{
			$this->car = $carObj->setModelData($carData);
		}
		$this->routes = null;
	}

}
