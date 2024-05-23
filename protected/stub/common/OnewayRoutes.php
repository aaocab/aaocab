<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of OnewayRoutes
 *
 * @author Roy
 */
class OnewayRoutes extends Itinerary
{

	//put your code here

	public $image;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/**
	 * 
	 * @param array $routeData
	 * @return []
	 */
	public function setRoutesData($routeData, $imageList)
	{
		foreach ($routeData as $route)
		{
			$routeImage	 = $imageList[$route['routeName']];
			/* @var $onewayObj OnewayRoutes */
			$onewayObj	 = new \Stub\common\OnewayRoutes();
			$routeList[] = $onewayObj->setRouteData($route['routeModel'], $route['quotedDistance'], $route['baseAmount'], $route['routeName'], $routeImage);
		}
		return $routeList;
	}

	/**
	 * 
	 * @param \BookingRoute $route
	 * @param integer $quotedDistance
	 * @param integer $baseFare
	 * @param string $routeName
	 * @param string $routeImage
	 * @return $this
	 */
	public function setRouteData(\BookingRoute $route, $quotedDistance = null, $baseFare = null, $routeName = null, $routeImage = null)
	{
		$this->source = new Location();
		$this->source->setData($route->brt_from_city_id, $route->brt_from_city_name, $route->brt_from_location, $route->brt_from_latitude, $route->brt_from_longitude, $route->brt_from_is_airport);

		$this->destination = new Location();
		$this->destination->setData($route->brt_to_city_id, $route->brt_to_city_name, $route->brt_to_location, $route->brt_to_latitude, $route->brt_to_longitude, $route->brt_to_is_airport);

		$this->aliasName = $routeName;
		if ($quotedDistance)
		{
			$this->estimatedDistance = $quotedDistance;
		}
		if ($baseFare)
		{
			$qt							 = new \Quote();
			$qt->routeRates				 = new \RouteRates();
			$qt->routeRates->baseAmount	 = $baseFare;
			/* @var $fareObj \Stub\common\Fare */
			$fareObj					 = new \Stub\common\Fare();
			$this->fare					 = $fareObj->setQuoteRates($qt->routeRates, true);
		}
		$this->image = IMAGE_URL . '/' . $routeImage;
		return $this;
	}

}
