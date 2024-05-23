<?php

namespace Beans\transferz;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Itinerary extends Route
{

	public $startDate, $startTime, $returnDateTime = null;

	/** @var \Stub\common\Location $source */
	public $source;

	/** @var \Stub\common\Location $destination */
	public $destination;

	public function getModel(\BookingRoute $model = null)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}
		
		$model->brt_from_city_id			 = (int) $this->source->code;
		$model->brt_from_location			 = (strlen($this->source->address) < 255) ? $this->source->address : substr($this->source->address, 0, 254);
		$model->brt_from_latitude			 = (float) $this->source->coordinates->latitude;
		$model->brt_from_longitude			 = (float) $this->source->coordinates->longitude;
		$model->brt_from_place_lat			 = (float) $this->source->googlePlace->coordinates->latitude;
		$model->brt_from_place_long			 = (float) $this->source->googlePlace->coordinates->longitude;
		$model->brt_from_place_id			 = $this->source->googlePlace->placeID;
		$model->brt_from_formatted_address	 = $this->source->googlePlace->placeAddress;
		$model->brt_from_place_name			 = $this->source->googlePlace->placeName;
		$model->brt_from_place_type			 = $this->source->googlePlace->placeTypeIDs;
		$model->brt_from_city_is_airport	 = $this->source->isAirport;

		$model->brt_to_city_id			 = (int) $this->destination->code;
		$model->brt_to_location			 = (strlen($this->destination->address) < 255) ? $this->destination->address : substr($this->destination->address, 0, 254);
		$model->brt_to_latitude			 = (float) $this->destination->coordinates->latitude;
		$model->brt_to_longitude		 = (float) $this->destination->coordinates->longitude;
		$model->brt_to_place_lat		 = (float) $this->destination->googlePlace->coordinates->latitude;
		$model->brt_to_place_long		 = (float) $this->destination->googlePlace->coordinates->longitude;
		$model->brt_to_place_id			 = $this->destination->googlePlace->placeID;
		$model->brt_to_formatted_address = $this->destination->googlePlace->placeAddress;
		$model->brt_to_place_type		 = $this->destination->googlePlace->placeTypeIDs;
		$model->brt_to_place_name		 = $this->destination->googlePlace->placeName;
		$model->brt_to_city_is_airport	 = $this->destination->isAirport;

		$model->brt_pickup_datetime = $this->startDate . ' ' . $this->startTime;
		if($this->returnDateTime != null)
		{
			$citiesId = self::populateCityIds($model->brt_from_latitude, $model->brt_from_longitude, $model->brt_to_latitude, $model->brt_to_longitude);
			$model->brt_pickup_datetime	 = self::populateReturnPickupTime($citiesId['sourceCity'], $citiesId['destCity'], $this->returnDateTime);
		}

		$routeModel = $model->populateCities();

		if ($routeModel)
		{
			$model->brt_trip_duration = $routeModel->rut_estm_time;
		}


		$isAirportTransfer = $model->isAirportTransfer();
		if ($isAirportTransfer)
		{
			$model->tripType = 12;
		}

		$model->decodeAttributes();

		return $model;
	}

	public function setCustomerBookingrouteData($booking)
	{
		$this->source = new Location();
		$this->source->setData($booking->brt_from_city_id, $booking->brt_from_city_name, $booking->brt_from_location, $booking->brt_from_latitude, $booking->brt_from_longitude);

		$this->destination = new Location();
		$this->destination->setData($booking->brt_to_city_id, $booking->brt_to_city_name, $booking->brt_to_location, $booking->brt_to_latitude, $booking->brt_to_longitude);
	}

	/**
	 * This function is used for setting booking route
	 * @param type $route = Booking Route Model
	 */
	public function setModelData(\BookingRoute $route)
	{
		$this->startDate = date('Y-m-d', strtotime($route->brt_pickup_datetime));
		$this->startTime = date('H:i:s', strtotime($route->brt_pickup_datetime));
		$fromCityName	 = ($route->brt_from_city_name == null) ? $route->brtFromCity->cty_name : $route->brt_from_city_name;
		$toCityName		 = ($route->brt_to_city_name == null) ? $route->brtToCity->cty_name : $route->brt_to_city_name;
		$this->source	 = new Location();
		$this->source->setData($route->brt_from_city_id, $fromCityName, $route->brt_from_location, $route->brt_from_latitude, $route->brt_from_longitude, $route->brt_from_is_airport);

		$this->destination = new Location();
		$this->destination->setData($route->brt_to_city_id, $toCityName, $route->brt_to_location, $route->brt_to_latitude, $route->brt_to_longitude, $route->brt_to_is_airport);
	}

	public function setRatingModelData(\BookingRoute $route)
	{
		$this->source = new Location();
		$this->source->setData($route->brt_from_city_id, $route->brt_from_location, $route->brt_from_latitude, $route->brt_from_longitude);

		$this->destination = new Location();
		$this->destination->setData($route->brt_to_city_id, $route->brt_to_location, $route->brt_to_latitude, $route->brt_to_longitude);
	}

	public function getCabListModel(\BookingRoute $model = null)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}
		$model->brt_pickup_datetime = $this->startDate . ' ' . $this->startTime;

		$model->brt_from_city_id	 = (int) $this->source->code;
		$model->brt_from_location	 = $this->source->address;
		$model->brt_from_latitude	 = (float) $this->source->coordinates->latitude;
		$model->brt_from_longitude	 = (float) $this->source->coordinates->longitude;

		$model->brt_to_city_id	 = (int) $this->destination->code;
		$model->brt_to_location	 = $this->destination->address;
		$model->brt_to_latitude	 = (float) $this->destination->coordinates->latitude;
		$model->brt_to_longitude = (float) $this->destination->coordinates->longitude;

		return $model;
	}

	public static function setModelsData($routeData)
	{
		$routes = [];
		foreach ($routeData as $route)
		{
			$itinerary	 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$routes[]	 = $itinerary;
		}
		return $routes;
	}

	public function getRouteData(\Route $route = null)
	{
		$this->getData($route);
	}

	public function setGNowNotifyModelData(\BookingRoute $route)
	{
		$this->source			 = new Location();
		$this->source->code		 = (int) $route->brt_from_city_id;
		$this->source->name		 = $route->brtFromCity->cty_name;
		$this->source->address	 = $route->brt_from_location;

		$this->destination		 = new Location();
		$this->destination->code = (int) $route->brt_to_city_id;
		$this->destination->name = $route->brtToCity->cty_name;
	}

	public function setGNowNotifyModelShortData($routes)
	{
		$cnt					 = count($routes);
		$route0					 = $routes[0];
		$routeN					 = $routes[$cnt - 1];
		$this->source			 = new Location();
		$this->source->code		 = (int) $route0->brt_from_city_id;
		$this->source->name		 = $route0->brtFromCity->cty_name;
		$this->source->address	 = $route0->brt_from_location;

		$this->destination		 = new Location();
		$this->destination->code = (int) $routeN->brt_to_city_id;
		$this->destination->name = $routeN->brtToCity->cty_name;
	}

	public function setGNowWinBidNotifyModelData(\BookingRoute $route)
	{
		$this->source			 = new Location();
		$this->source->code		 = (int) $route->brt_from_city_id;
		$this->source->name		 = $route->brtFromCity->cty_name;
		$this->source->address	 = $route->brt_from_location;

		$this->destination			 = new Location();
		$this->destination->address	 = $route->brt_to_location;
		$this->destination->code	 = (int) $route->brt_to_city_id;
		$this->destination->name	 = $route->brtToCity->cty_name;
	}

	public function setBidListData($val)
	{
		$this->source			 = new Location();
		$this->source->code		 = (int) $val['from_city_id'];
		$this->source->name		 = $val['from_city_name'];
		$this->source->address	 = $val['from_address'];

		$this->destination			 = new Location();
		$this->destination->code	 = (int) $val['to_city_id'];
		$this->destination->name	 = $val['to_city_name'];
		$this->destination->address	 = $val['to_address'];
	}

	/**
	 * @param Itinerary[] $itinerary
	 * @return \BookingRoute[]
	 * */
	public static function getRouteModels($itinerary)
	{
		$routes = [];
		foreach ($itinerary as $route)
		{
			$brtModel	 = $route->getModel();
			$routes[]	 = $brtModel;
		}


		return $routes;
	}

	/**
	 * 
	 * @param type $stoppages
	 * @param type $pickupTime
	 * @param type $routeDuration
	 * @param type $endTime
	 * @param type $arrFinalRoute
	 * @return \BookingRoute
	 */
	public function getStoppageRouteData($stoppages, $pickupTime, $routeDuration, $endTime, $arrFinalRoute)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}

		$model->brt_from_location	 = $stoppages['source']['address'];
		$model->brt_from_latitude	 = (float) $stoppages['source']['latitude'];
		$model->brt_from_longitude	 = (float) $stoppages['source']['longitude'];

		$model->brt_to_location		 = $stoppages['destination']['address'];
		$model->brt_to_latitude		 = (float) $stoppages['destination']['latitude'];
		$model->brt_to_longitude	 = (float) $stoppages['destination']['longitude'];
		$routeModel					 = $model->populateCities();
		$model->brt_pickup_datetime	 = \BookingRoute::populatePickupTime($routeModel->rut_from_city_id, $routeModel->rut_to_city_id, $pickupTime, $routeDuration);

		$last = end($arrFinalRoute);
		if($last['source']['latitude'] == $model->brt_from_latitude)
		{
				$routeDuration				 = \Route::model()->getRouteDurationbyCities($routeModel->rut_from_city_id, $routeModel->rut_to_city_id);
				$dropoffDateTime			 = date('Y-m-d H:i:s', strtotime($endTime . ' - ' . $routeDuration . ' minute'));
				$model->brt_pickup_datetime	 = $dropoffDateTime;
		}

		$model->brt_return_date_date = $this->end_time;
		if ($routeModel)
		{
			$model->brt_trip_duration = $routeModel->rut_estm_time;
		}
		return $model;
	}

	/**
	 * 
	 * @param type $pickupCity
	 * @param type $dropCity
	 * @param type $returnTime
	 * @return DateTime
	 */
	public static function populateReturnPickupTime($pickupCity, $dropCity, $returnTime)
	{
		$routeDuration	 = \Route::model()->getRouteDurationbyCities($dropCity, $pickupCity);
		$pickupDateTime	 = date('Y-m-d H:i:s', strtotime($returnTime . ' -' . $routeDuration . ' minute'));
		return $pickupDateTime;
	}

	/**
	 * 
	 * @param type $fromLatitude
	 * @param type $fromLongitude
	 * @param type $toLatitude
	 * @param type $toLongitude
	 * @return array
	 */
	public static function populateCityIds($fromLatitude, $fromLongitude, $toLatitude, $toLongitude)
	{
		$sourcePlace			 = \Stub\common\Place::init($fromLatitude, $fromLongitude);
		$destPlace				 = \Stub\common\Place::init($toLatitude, $toLongitude);
		$ctyModel				 = \Cities::getByGeoBounds($sourcePlace, 15);
		if ($ctyModel)
		{
			$sourceCity = $ctyModel->cty_id;
		}

		$ctyModel = \Cities::getByNearestBound($sourcePlace);
		if ($ctyModel && $ctyModel->is_partial == 0)
		{
			$sourceCity = $ctyModel->cty_id;
			goto populateDestionationBound;
		}

		populateDestionationBound:
		$ctyModel = \Cities::getByGeoBounds($destPlace, 15);
		if ($ctyModel)
		{
			$destCity = $ctyModel->cty_id;
			goto skipDestionationNearestBound;
		}
		$ctyModel = \Cities::getByNearestBound($destPlace);
		if ($ctyModel)
		{
			$destCity = $ctyModel->cty_id;
		}

		skipDestionationNearestBound:
		if (empty($sourceCity))
		{
			$srcLtLngModel	 = \LatLong::model()->getDetailsByPlace($sourcePlace);
			$sourceCity		 = $srcLtLngModel->ltg_city_id;
		}

		if (empty($destCity))
		{
			$dstLtLngModel	 = \LatLong::model()->getDetailsByPlace($destPlace);
			$destinationCity		 = $dstLtLngModel->ltg_city_id;
		}
		return ['sourceCity' => $sourceCity, 'destCity' => $destCity];
	}

}
