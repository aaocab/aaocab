<?php

namespace Stub\vendor;

/**
 * Description of Vendor Trip Details  Response
 *
 * @author Maiti
 */
class TripDetailsResponse
{

	public $isPromoter;
	public $isGozoNow;
	public $bookingId;
	public $tripType;
	public $tripDistance;
	public $tripDuration;
	public $pickupDate;
	public $pickupTime;
	public $bookingDate;
	public $bookingTime;
	public $rideStart;
	public $rideComplete;
	public $instructionToDriverVendor;
	public $isAgent;
	public $agentName;
	public $otpRequired;
	public $isBiddable;
	public $isDutySlipRequired;
	public $isDriverAppRequired;
	public $isCngAllowed;

	/** @var \Stub\common\Person $traveller */
	public $traveller;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes = [];

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\CabRate[] $cabRate */
	public $cabRate;
	public $transactions;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Vehicle $car */
	public $car;

	/**
	 * @param \Booking $models
	 */
	public function setData(\Booking $model)
	{
		$this->bookingId				 = $model->bkg_booking_id;
		$this->tripType					 = (int) $model->bkg_booking_type;
		$this->isPromoter				 = (int) ($model->bkg_flexxi_type > 1) ? 1 : 0;
		$this->isGozoNow				 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
		$this->tripDuration				 = $model->bkg_trip_duration;
		$this->tripDistance				 = (int) $model->bkg_trip_distance;
		$this->pickupDate				 = date("Y-m-d", strtotime($model->bkg_pickup_date));
		$this->pickupTime				 = date("H:i:s", strtotime($model->bkg_pickup_date));
		$this->bookingDate				 = date("Y-m-d", strtotime($model->bkg_create_date));
		$this->bookingTime				 = date("H:i:s", strtotime($model->bkg_create_date));
		$this->rideStart				 = (int) $model->bkgTrack->bkg_ride_start;
		$this->rideComplete				 = (int) $model->bkgTrack->bkg_ride_complete;
		$this->instructionToDriverVendor = $model->bkg_instruction_to_driver_vendor;
		$this->isAgent					 = ($model->bkg_agent_id > 0) ? (int) 1 : 0;
		$this->agentName				 = ($model->bkg_agent_id > 0) ? B2B : UBER;
		$this->otpRequired				 = (int) ($model->bkgTrack->bkg_is_trip_verified > 1) ? 1 : 2;
		$this->isBiddable				 = (int) ($model->bkg_reconfirm_flag > 1) ? 1 : 0;
		$this->isDutySlipRequired		 = (int) $model->bkgPref->bkg_duty_slip_required;
		$this->isDriverAppRequired		 = (int) $model->bkgPref->bkg_driver_app_required;
		$this->isCngAllowed				 = (int) $model->bkgPref->bkg_cng_allowed;

		$routes = $model->bookingRoutes;
		foreach ($routes as $route)
		{
			$itinerary		 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$this->routes[]	 = $itinerary;
		}

		$this->traveller = new \Stub\common\Person();
		$this->traveller->setModelData($model->bkgUserInfo);

		$driverData = [
			'drv_id'	 => $model->bkgBcb->bcbDriver->drv_id,
			'drv_code'	 => $model->bkgBcb->bcbDriver->drv_code,
			'drv_name'	 => $model->bkgBcb->bcbDriver->drv_name,
			'drv_phone'	 => (int) $model->bkgBcb->bcb_driver_phone,
			'drv_phone'	 => (int) $model->bkgBcb->bcb_driver_phone
		];

		if ($model->bkgBcb->bcbDriver->drv_id != '')
		{
			$this->driver = new \Stub\common\Driver();
			$this->driver->fillData($driverData);
		}

		$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
		if($model->bkgBcb->bcbCab->vhc_type_id === \Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = \OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
		}
		$carData = [
			'vhc_number' => $model->bkgBcb->bcbCab->vhc_number,
			'vht_make'	 => $model->bkgBcb->bcbCab->vhcType->vht_make,
			'vht_model'	 => $vehicleModel,
		];
		if ($model->bkgBcb->bcbCab->vhc_number != '')
		{
			$this->car = new \Stub\common\Vehicle();
			$this->car->setVehicleFillData($carData);
		}

		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate	 = $cabRate;

		$trans				 = new \Stub\common\PaymentState();
		$this->transactions	 = $trans->setModels($model->bkg_id);
	}

}
