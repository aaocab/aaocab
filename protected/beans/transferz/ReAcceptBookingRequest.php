<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transferz;

class ReAcceptBookingRequest
{
	public $id, $code, $vehicleCategory, $distance, $distanceUnit, $duration, $hash;

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

	/** @var \Beans\transferz\MeetingPoints[] $meetingPoints */
	public $meetingPoints;


	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}
		$model->bkg_agent_id		 = \Config::get('transferz.partner.id');

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

		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
		$cancelRuleId					     = \CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModel->scv_id,$model->bkg_from_city_id ,$model->bkg_to_city_id,$model->bkg_booking_type);
		$model->bkgPref->bkg_cancel_rule_id	 = $cancelRuleId;

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
				if($this->addOns['SPECIAL_LUGGAGE'])
				{
					$model->bkgAddInfo->bkg_spl_req_carrier = 1;
				}
			}
		$model->bkgAddInfo->bkg_spl_req_other = json_encode($this->addOns);
		}
		return $model;
	}

	public function getRouteModel(\BookingRoute $model = null, $pickTime)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}

		$model->brt_pickup_datetime = $pickTime;
		$model->brt_from_location	 = $this->pickup->bookerEnteredAddress;
		$model->brt_from_latitude	 = (float) $this->pickup->latitude;
		$model->brt_from_longitude	 = (float) $this->pickup->longitude;

		$model->brt_to_location	 = $this->dropoff->address;
		$model->brt_to_latitude	 = (float) $this->dropoff->latitude;
		$model->brt_to_longitude = (float) $this->dropoff->longitude;

		$routeModel = $model->populateCities();
		$distance   =  (int) $this->distance;

		if ($routeModel)
		{
			$model->brt_trip_duration = $routeModel->rut_estm_time;
			$distance					 = max([$distance, $routeModel->rut_estm_distance]);
			$model->brt_trip_distance	 = $distance;
		}
		$isAirportTransfer			 = $model->isAirportTransfer();
		if ($isAirportTransfer)
		{
			$model->tripType = 12;
		}
		return $model;
	}

}
