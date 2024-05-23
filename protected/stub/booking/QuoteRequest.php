<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 */


/** 
 * 
 * @property \Stub\common\Itinerary[] $routes
 * 
 */
class QuoteRequest
{

	public $tripType;
	public $cabType;
	public $packageId;
	public $pickupDate;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes;

	/** @return \Booking */
	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = new \Booking();
		}
		$cabType					 = (count($this->cabType) == 0) ? null : $this->cabType;
		//$model->bkg_booking_type	 = $this->tripType;
		$model->bkg_vehicle_type_id	 = $cabType;
		$model->bkg_create_date		 = new \CDbExpression('now()');
		$routes						 = [];
		foreach ($this->routes as $route)
		{
			$routes[] = $route->getModel();
		}
		$rCount					 = count($routes);
		$userInfo = \UserInfo::getInstance();
		if (in_array($userInfo->userType, [4, 5]))
		{
			$model->bkg_agent_id			 = $userInfo->userId;
			$model->bkgTrail->bkg_platform	 = $userInfo->userType;
		}
		if($model->bkg_agent_id == \Config::get('Kayak.partner.id'))
		{
			
			$model->bkg_return_date  = $routes[$rCount - 1]->brt_pickup_datetime;
			$duration							 = \Route::model()->getRouteDurationbyCities($routes[$rCount - 1]->brt_from_city_id, $routes[$rCount - 1]->brt_to_city_id);
			$routes[$rCount - 1]->brt_pickup_datetime = date('Y-m-d H:i:s', strtotime($model->bkg_return_date . ' -' . $duration . ' minute'));
		}
		$model->bookingRoutes	 = $routes;
		$model->bkg_from_city_id = $routes[0]->brt_from_city_id;
		$model->bkg_to_city_id	 = $routes[$rCount - 1]->brt_to_city_id;
		$model->bkg_pickup_date	 = $model->bookingRoutes[0]->brt_pickup_datetime;
		$model->bkg_booking_type = $model->bookingRoutes[0]->tripType == 12 ? $model->bookingRoutes[0]->tripType : $this->tripType;
		if ($model->bkg_booking_type == 12 || $model->bkg_booking_type == 4)
		{
			$fcityModel					 = \Cities::model()->getDetailsByCityId($model->bkg_from_city_id);
			$model->bkg_transfer_type	 = ($fcityModel['cty_is_airport'] == 1) ? 1 : 2;
		}
		if(in_array($this->tripType, [2,3,9,10,11]))
		{
			$model->bkg_booking_type = $this->tripType;
		}
		if ($this->tripType == 5)
		{
			$model->bkg_package_id	 = $this->packageId;
			$model->bkg_pickup_date	 = $this->pickupDate;
		}
		

		// TO BE CHANGE
		$spiceId = \Config::get('spicejet.partner.id');
		$sugerboxId = \Config::get('sugerbox.partner.id');
		if ($model->bkg_agent_id == $spiceId || $model->bkg_agent_id == $sugerboxId)
		{
			if ($routes[0]->brtFromCity->cty_is_airport == 1)
			{
				$atTransferType	 = 1;
				$airportId		 = $model->bkg_from_city_id;
			}
			if ($routes[$rCount - 1]->brtToCity->cty_is_airport == 1)
			{
				$atTransferType	 = 2;
				$airportId		 = $model->bkg_to_city_id;
			}

			$vehicleType				 = \PartnerSvcSettings::eligibleCabType($airportId, $model->bkg_agent_id, $atTransferType, $cabType[0]);
			if($vehicleType)
			{
				$model->bkg_vehicle_type_id	 = $vehicleType;
			}
		}

		return $model;
	}

}
