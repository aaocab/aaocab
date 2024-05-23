<?php

namespace stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RouteRequest
{

	public $bookingId;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = new \Booking();
		}
		$model->bkg_id	 = $this->bookingId;
		$routes			 = [];
		foreach ($this->routes as $route)
		{
			$routes[] = $route->getModel();
		}
		$model->bookingRoutes = $routes;
		return $model;
	}

}
