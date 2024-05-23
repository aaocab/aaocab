<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Stub\consumer;
/**
 * Description of AirportRequest
 *
 * @author Aman
 */
class AirportRequest
{
	public $tripType;
	
	/** @var \Stub\common\Itinerary[] $routes */
	public $routes;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = new \Booking();
		}
		$model->bkg_booking_type	 = $this->tripType;
        
		$routes						 = [];
		foreach ($this->routes as $route)
		{
			$routes[] = $route->getModel();
		}
		$rCount					 = count($routes);
		$model->bookingRoutes	 = $routes;
		$model->bkg_from_city_id = $routes[0]->brt_from_city_id;
		$model->bkg_to_city_id	 = $routes[$rCount - 1]->brt_to_city_id;
		$model->bkg_pickup_date	 = $model->bookingRoutes[0]->brt_pickup_datetime;
		return $model;
	}

}
