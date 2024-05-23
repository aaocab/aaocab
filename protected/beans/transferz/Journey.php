<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cab
 *
 * @author Dev
 * 
 */

namespace Beans\transferz;

class Journey
{
	public $id, $code, $vehicleCategory, $distance, $distanceUnit, $duration ;
	public $addOns;

	/** @var \Beans\transferz\Address $pickup */
	public $pickup;

	/** @var \Beans\transferz\Address $dropoff */
	public $dropoff;
	
	/** @var \Beans\transferz\PickupTime $pickupTime */
	public $pickupTime;

	/** @var \Beans\transferz\AddOns[] $addOns */
	//public $addOns;
	
	/** @var \Beans\transferz\FareSummary $fareSummary */
	public $fareSummary;

	/** @var \Beans\transferz\TravellerInfo $travellerInfo */
	public $travellerInfo;


	public function setData(\Booking $model = null)
	{
		/* @var $model Booking */
		if ($model == null)
		{
			$model = new \Booking();
		}

		$model->bkg_vehicle_type_id	 = \TransferzOffers::vhcCategoryMapping($this->vehicleCategory);

		if ($this->pickupTime == null)
		{
			$this->pickupTime = new \Beans\transferz\PickupTime();
		}
		$model = $this->pickupTime->setData($model);

		$route = $this->getRouteModel(null, $model->bkg_pickup_date);
		$model->bkg_booking_type =     $route->tripType;
		$model->bkg_from_city_id	 = $route->brt_from_city_id;
		$model->bkg_to_city_id		 = $route->brt_to_city_id;
		$model->bkg_pickup_date		 = $route->brt_pickup_datetime;
		$model->bkg_pickup_address	 = $route->brt_from_location;
		$model->bkg_drop_address	 = $route->brt_to_location;
		$model->bkg_create_date		 = new \CDbExpression('now()');
		$model->bkg_trip_distance	 = $route->brt_trip_distance;
		$model->bkg_trip_duration	 = $route->brt_trip_duration;
		$model->requiredKMs          = $route->brt_trip_distance;

		$routes[]				 = $route;
		$model->bookingRoutes	 = $routes;

		if ($this->fareSummary == null)
		{
			$this->fareSummary = new \Beans\transferz\FareSummary();
		}
		$model->bkgInvoice = $this->fareSummary->setData($model->bkgInvoice);

		if ($this->travellerInfo == null)
		{
			$this->travellerInfo = new \Beans\transferz\TravellerInfo();
		}
		$model->bkgAddInfo = $this->travellerInfo->setAdditionalInfo($model->bkgAddInfo);

		if(count($this->addOns) > 0)
		{
			foreach($this->addOns as $data)
			{
				if($data == "SPECIAL_LUGGAGE")
				{
					$model->bkgAddInfo->bkg_spl_req_carrier = 1;
				}
			}
			$extraInstruction[] = "Kindly coordinate with customer, Write the customer name on paper , park the car in parking and go to arrival gate to pick up customer form arrival gate as its international customer call on WhatsApp";
			$this->addOns = array_merge($this->addOns, $extraInstruction);
			$model->bkgAddInfo->bkg_spl_req_other = json_encode($this->addOns);
		}
		return $model;
		
	}

	public function getPendingBookingModel(\TransferzOffers $model = null)
	{
		/* @var $model TransferzOffers */
		if ($model == null)
		{
			$model = new \TransferzOffers();
		}
		$model->trb_trz_journey_id	 = $this->id;
		$model->trb_trz_journey_code = $this->code;
		
		if ($this->pickupTime == null)
		{
			$this->pickupTime = new \Beans\transferz\PickupTime();
		}
		$model = $this->pickupTime->setPendingData($model);
		$model->trb_vehicle_type = \TransferzOffers::vhcCategoryMapping($this->vehicleCategory);

		$fromCity = \Cities::getCityByLatLng($this->pickup->latitude, $this->pickup->longitude);
		$toCity   = \Cities::getCityByLatLng($this->dropoff->latitude, $this->dropoff->longitude);
		$model->trb_from_city_id	 = $fromCity;
		$model->trb_to_city_id		 = $toCity;
		$model->trb_create_date		 = new \CDbExpression('NOW()');
		return $model;
		
	}

	public function getRouteModel(\BookingRoute $model = null, $pickTime)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}
	
		$model->brt_from_location	 = $this->pickup->bookerEnteredAddress;
		$model->brt_from_latitude	 = (float) $this->pickup->latitude;
		$model->brt_from_longitude	 = (float) $this->pickup->longitude;

		$model->brt_to_location	 = $this->dropoff->bookerEnteredAddress;
		$model->brt_to_latitude	 = (float) $this->dropoff->latitude;
		$model->brt_to_longitude = (float) $this->dropoff->longitude;

		$routeModel = $model->populateCities();
		$distance   =  (int) $this->distance;

		if($model->brtFromCity->cty_is_airport == 1)
		{
			$model->brt_pickup_datetime = date("Y-m-d H:i:s",strtotime("+15 minutes", strtotime($pickTime)));
		}
		else
		{
			$model->brt_pickup_datetime = $pickTime;
		}

		if ($routeModel)
		{
			$model->brt_trip_duration = $routeModel->rut_estm_time;
			//$distance					 = max([$distance, $routeModel->rut_estm_distance]);
			$model->brt_trip_distance	 = $distance;
		}
		$model->tripType	 = 12;
		$fcityModel			 = \Cities::model()->getDetailsByCityId($routeModel->rut_from_city_id);
		$model->transferType = ($fcityModel['cty_is_airport'] == 1) ? 1 : 2;
		return $model;
	}

}
