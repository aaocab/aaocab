<?php

namespace Stub\spicejet;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreateRequest
{

	public $key;
	public $vendor_id;
	public $partner_name;
	public $order_reference_number;
	public $passenger_name;
	public $passenger_email;
	public $passenger_phone_number;
	public $source_name;
	public $source_city;
	public $source_latitude;
	public $source_longitude;
	public $start_time;
	public $destination_name;
	public $destination_city;
	public $destination_latitude;
	public $destination_longitude;
	public $total_distance;
	public $total_fare;
	public $is_part_payment;
	public $vehicle_type;
	public static $vehicleTypes	 = ['hatchback' => '1', 'sedan' => '3', 'suv' => '2'];
	public $is_airport_pickup;
	public $is_airport_drop;
	public $amount_to_be_collected;
	public $flight_number;
	public $add_ons				 = [''];
	public $flags				 = [];
	public $company				 = [];

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}

		$isAirportPickup					 = ($this->is_airport_pickup == true) ? 1 : 2;
		$model->bkg_transfer_type			 = $isAirportPickup;
		$model->bkgPref->bkg_autocancel      = 1;
		//$model->bkgPref->bkg_block_autoassignment = 1;
		$model->bkgPref->bkg_driver_app_required = 0;
		$model->bkg_agent_ref_code			 = $this->order_reference_number;
		$model->bkgUserInfo->bkg_user_fname	 = $this->passenger_name;
		$model->bkgUserInfo->bkg_user_email	 = $this->passenger_email;
		$model->bkgUserInfo->bkg_contact_no	 = $this->passenger_phone_number;

		$userInfo			 = \UserInfo::getInstance();
		$model->bkg_agent_id = $userInfo->userId;

		$platformId  = \Filter::getPlatform($userInfo->userId);
		$model->bkgTrail->bkg_platform           = $platformId;
		$model->bkgTrail->btr_stop_increasing_vendor_amount = 1;

		$route					 = $this->getRouteModel();
		$model->bkg_booking_type = 12;

		$model->bkg_from_city_id = $route->brt_from_city_id;
		$model->bkg_to_city_id	 = $route->brt_to_city_id;
		$cityCategory			 = \CitiesStats::getCategory($model->bkg_from_city_id);

		$routes[]							 = $route;
		$model->bookingRoutes				 = $routes;
		$cancellationRule					 = \CancellationPolicy::getPolicy($cityCategory, $svcModel->scv_scc_id);
		$model->bkgPref->bkg_cancel_rule_id	 = $cancellationRule['ruleId'];

		$model->bkgInvoice->bkg_total_amount = $this->total_fare;
		$cabType							 = self::$vehicleTypes[$this->vehicle_type];
		if ($route->brtFromCity->cty_is_airport == 1)
		{
			$atTransferType	 = 1;
			$airportId		 = $route->brt_from_city_id;
		}
		if ($route->brtToCity->cty_is_airport == 1)
		{
			$atTransferType	 = 2;
			$airportId		 = $route->brt_to_city_id;
		}
		$vehicleType = \PartnerSvcSettings::eligibleCabType($airportId,$model->bkg_agent_id,$atTransferType,$cabType);

		$model->bkg_vehicle_type_id = $vehicleType;

		$advanceAmount							 = $model->bkgInvoice->bkg_total_amount - $this->amount_to_be_collected;
		$model->bkgInvoice->bkg_advance_amount	 = 0;

		$model->bkgAddInfo->bkg_flight_no = $this->flight_number;
		return $model;
	}

	public function getRouteModel(\BookingRoute $model = null)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}

		if ($this->is_airport_pickup == true)
		{
			$minutesToPickup = \Filter::getTimeDiff($this->start_time);
			$minTime = \Config::get('spicejet.pickup.mintime');
			if($minutesToPickup < $minTime)
			{
				$startDate	 = new \DateTime($this->start_time);
				$startDate->add(new \DateInterval('PT' . $minTime . 'M'));
				$pickupDate	 = $startDate->format('Y-m-d H:i:s');
			}
			else{	
				$startDate	 = new \DateTime($this->start_time);
				$pickupDate	 = $startDate->format('Y-m-d H:i:s');
			}
			
		}
		else
		{
			$startDate	 = new \DateTime($this->start_time);
			$pickupDate	 = $startDate->format('Y-m-d H:i:s');
		}
		$currDateTime	 = \Filter::getDBDateTime();
		if($pickupDate < $currDateTime)
		{
			throw new \Exception("Time should be after ". $currDateTime, \ReturnSet::ERROR_INVALID_DATA);
		}


		$model->brt_from_location	 = $this->source_name . ', ' . $this->source_city;
		$model->brt_from_latitude	 = (float) $this->source_latitude;
		$model->brt_from_longitude	 = (float) $this->source_longitude;
		$model->brt_pickup_datetime	 = $pickupDate;

		if ($this->destination_latitude != '' || $this->destination_latitude != NULL)
		{
			$model->brt_to_location	 = $this->destination_name . ', ' . $this->destination_city;
			$model->brt_to_latitude	 = (float) $this->destination_latitude;
			$model->brt_to_longitude = (float) $this->destination_longitude;
		}
		else
		{
			$sourceCityId	 = \Cities::model()->getCityByLatLong($this->source_latitude, $this->source_longitude);
			$nearestCitilist = \Cities::model()->getJSONNearestAll($sourceCityId['cty_id'], 25, true, "", "", 1, "");
			$dropCityId		 = $nearestCitilist[0]['id'];
			$cityModel		 = \Cities::model()->findByPk($dropCityId);

			$model->brt_to_location	 = $cityModel->cty_name;
			$model->brt_to_latitude	 = (float) $cityModel->cty_lat;
			$model->brt_to_longitude = (float) $cityModel->cty_long;
		}


		$routeModel					 = $model->populateCities();
		$model->brt_trip_distance	 = $this->total_distance;
		$model->tripType			 = 12;
		return $model;
	}

}
