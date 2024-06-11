<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuoteRequest
{

	public $cabId;
	public $start_time;
	public $end_time;
	public $distance;
	public $distance_booked;
	public $one_way_distance;
	public $is_instant_search;
	public $stopovers = [];

	/** @var \Stub\mmt\Location $srcPoint */
	public $source;

	/** @var \Stub\mmt\Location $destPoint */
	public $destination;

	/** @var \Stub\common\Itinerary[] $stopage */
	public $stopage;

	/** @var \Stub\mmt\Fare $fare */
	public $fare;
	public $vehicle_type;
	public $cabType;
	public $trip_type;
	public $package_id;
	public $search_id;
	public static $tripTypes = ['ONE_WAY' => '1', 'ROUND_TRIP' => '2', 'LOCAL_RENTAL' => '3'];
	public static $cabTypes	 = ['hatchback' => '1', 'sedan' => '3', 'suv' => '2', 'hatchback' => '14', 'suv' => '15', 'sedan' => '16', 'suv' => '50', 'suv' => '51'];
	public $vendor_id;
	public $partner_name;
	public $search_tags		 = [];
	public $vehicle_details;
	public static $packageId = ['PKG_40_4' => '9', 'PKG_80_8' => '10', 'PKG_120_12' => '11'];

	/** @return \Booking */
	public function getModel($model = null, $objData = NULL)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
			\Logger::profile("New Instance Initiated");
		}
		if (!array_key_exists($this->trip_type, self::$tripTypes))
		{
			throw new \Exception("Invalid Trip Type", \ReturnSet::ERROR_INVALID_DATA);
		}

		$model->bkg_agent_id		   = 18190;
		$platformId					   = \Filter::getPlatform($model->bkg_agent_id);
		$model->bkgTrail->bkg_platform = $platformId;
		if (isset($this->vehicle_details->sku_id))
		{
			$svcModel = \SvcClassVhcCat::getBySKU($this->vehicle_details->sku_id);
			if ($svcModel)
			{
				$model->bkg_vehicle_type_id = $svcModel->scv_id;
				$model->bkg_vht_id			= $svcModel->scv_model;
			}
		}
		else
		{
			$cabType					= self::$cabTypes[$this->vehicle_type];
			$model->bkg_vehicle_type_id = $cabType;
		}
		$tripType = self::$tripTypes[$this->trip_type];
		if (!in_array($tripType, [1, 2]))
		{
			$tripType = self::$packageId[$this->package_id];
			if (!$tripType)
			{
				throw new \Exception("Package (" . $this->package_id . ") not supported", \ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}

		$model->bkg_booking_type = count($this->stopovers) > 0 ? 3 : $tripType;
		$model->bkg_create_date	 = new \CDbExpression('now()');
		$model->bkg_pickup_date	 = $this->start_time;
		$model->bkg_return_date	 = $this->end_time;
		$model->search_tags		 = $this->search_tags;

		if (in_array('CO', $this->search_tags))
		{
			$model->bkgPref->bkg_is_corporate = 1;
		}

		if ($this->one_way_distance > 0 && $tripType == 1)
		{
			$this->distance			  = $this->one_way_distance;
			$model->bkg_trip_distance = $this->one_way_distance;
		}
		elseif ($tripType == 2)
		{
			if ($this->distance > 0)
			{
				$model->bkg_trip_distance = $this->distance;
				$model->requiredKMs		  = $this->distance;
			}
			else if ($this->one_way_distance > 0)
			{
				$model->bkg_trip_distance = $this->one_way_distance * 2;
			}
		}
		elseif ($model->bkg_booking_type == 3)
		{
			if ($this->distance > 0)
			{
				$model->bkg_trip_distance = $this->distance;
				$model->requiredKMs		  = $this->distance;
			}
			else if ($this->one_way_distance > 0)
			{
				$model->bkg_trip_distance = $this->one_way_distance;
			}
		}


		$route = $this->getRouteModel(null, $model, $objData);

		if (in_array($model->bkg_booking_type, [2, 3, 9, 10, 11]))
		{
			$route->useHyperLocation = false;
		}

		$model->bkg_from_city_id = $route->brt_from_city_id;
		$model->bkg_to_city_id	 = $route->brt_to_city_id;

		$isMMTNewCancellationEnable = \Config::get('isMMT.newCabcellationPolicy.enable');
		if ($isMMTNewCancellationEnable == 0)
		{
			$cityCategory						= \CitiesStats::getCategory($model->bkg_from_city_id);
			$cancellationRule					= \CancellationPolicy::getPolicy($cityCategory, $svcModel->scv_scc_id);
			$model->bkgPref->bkg_cancel_rule_id = $cancellationRule['ruleId'];
		}
		else
		{
			if ($model->bkg_vehicle_type_id != NULL)
			{
				$svcModel							= \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
				$cancelRuleId						= \CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModel->scv_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type, $isGozonow							= 0, $fromTopZoneCat						= true);
				$model->bkgPref->bkg_cancel_rule_id = $cancelRuleId;
			}
		}

		$routes[] = $route;
		if ($tripType == 2)
		{
			$obj					 = new \BookingRoute();
			$route					 = $obj->getReturnRoute($routes, $this->end_time);
			$model->bkg_to_city_id	 = $route->brt_to_city_id;
			$model->bkg_drop_address = $route->brt_to_location;
			$model->bkg_dropup_lat	 = $route->brt_to_latitude;
			$model->bkg_dropup_long	 = $route->brt_to_longitude;
			$routes[]				 = $route;
			\Logger::profile("Return Route Populated");
		}

		// Multi cities
		if (count($this->stopovers) > 0)
		{
			$routes = $this->getMultiCityRoute($routes);
			if ($this->trip_type == 'ROUND_TRIP')
			{
				$model->bkg_booking_type = 2;
			}
			else
			{
				$model->bkg_booking_type = 3;
			}
		}

		$model->bookingRoutes = $routes;
		$model->search		  = $this->search_id;
		return $model;
	}

	public function getRouteModel(\BookingRoute $model = null, $bkgModel, $objData)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}

		$userInfo = \UserInfo::getInstance();
		$agentId  = $userInfo->userId;

		$model->brt_from_location  = \Cities::model()->clean($this->source->address);
		$model->brt_from_latitude  = (float) $this->source->latitude;
		$model->brt_from_longitude = (float) $this->source->longitude;
		$model->brt_from_place_id  = $this->source->place_id;

		if ($this->trip_type != 'LOCAL_RENTAL')
		{
			$model->brt_to_location	 = \Cities::model()->clean($this->destination->address);
			$model->brt_to_latitude	 = (float) $this->destination->latitude;
			$model->brt_to_longitude = (float) $this->destination->longitude;
			$model->brt_to_place_id	 = $this->destination->place_id;
		}
		else
		{
			$model->brt_to_location	 = \Cities::model()->clean($this->source->address);
			$model->brt_to_latitude	 = (float) $this->source->latitude;
			$model->brt_to_longitude = (float) $this->source->longitude;
			$model->brt_to_place_id	 = $this->source->place_id;
		}

		if ($this->trip_type == 'ROUND_TRIP')
		{
			$model->brt_to_location		 = \Cities::model()->clean($this->source->address);
			$model->brt_return_date_date = $this->end_time;
		}
		$routeModel = $model->populateCities();

		if ($routeModel)
		{
			$model->brt_trip_duration = $routeModel->rut_estm_time;
		}

		if ($routeModel && $this->trip_type == 'ROUND_TRIP')
		{
			$distance				  = max([$this->one_way_distance, $routeModel->rut_estm_distance]);
			$model->brt_trip_distance = $distance;
		}

		if ($this->trip_type == 'ONE_WAY' && $this->distance > 0)
		{
			$model->brt_trip_distance = $this->distance;
			$isAirportTransfer		  = $model->isAirportTransfer();
			if ($isAirportTransfer)
			{
				if ($routeModel->rut_from_city_id)
				{
					$model->tripType									   = 12;
					$fcityModel											   = \Cities::model()->getDetailsByCityId($routeModel->rut_from_city_id);
					$bkgModel->bkg_transfer_type						   = ($fcityModel['cty_is_airport'] == 1) ? 1 : 2;
					$bkgModel->bkg_booking_type							   = 12;
					$bkgModel->bkgTrail->btr_stop_increasing_vendor_amount = 1;
				}
				else
				{
					throw new \Exception("Please select valid source city", \ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
		}

		$tier = 0;
		if ($bkgModel->bkg_vehicle_type_id > 0)
		{
			$svcModel = \SvcClassVhcCat::model()->findByPk($bkgModel->bkg_vehicle_type_id);
			$tier	  = $svcModel->scv_scc_id;
		}

		$bkgModel->bkg_from_city_id = $routeModel->rut_from_city_id;

		$currentTime = \Filter::getDBDateTime();

		$response		= $bkgModel->checkTime($bkgModel);
		$minutes_to_add = $response->timeDifference + \Config::get('instantsearch.pickup.mintime');

		$time = new \DateTime($currentTime);
		$time->add(new \DateInterval('PT' . $minutes_to_add . 'M'));

		$nextAvailableTime = $time->format('Y-m-d H:i:s');

		$currDateTime	= \Filter::getDBDateTime();
		$workingMinDiff = \Filter::CalcWorkingMinutes($currDateTime, $bkgModel->bkg_pickup_date);

		$responseWM					= new \stdClass();
		$responseWM->isAllowed		= true;
		$responseWM->timeDifference = \Config::get('working.minute.difference') + \Config::get('instantsearch.pickup.mintime');

		if ($workingMinDiff < $responseWM->timeDifference)
		{
			$responseWM->isAllowed = false;
		}
		$nextAvailableTimeWM = \Filter::addWorkingMinutes($responseWM->timeDifference, $currentTime);

		if ((!$response->isAllowed || !$responseWM->isAllowed) && ($objData->distance == NULL))
		{
			$model->brt_pickup_datetime = (strtotime($nextAvailableTime) > strtotime($nextAvailableTimeWM)) ? $nextAvailableTime : $nextAvailableTimeWM;
//			if ($objData->is_instant_search == true || $objData->is_instant_search != NULL)
//			{
			$dateTime					= new \DateTime($model->brt_pickup_datetime);
			$date						= \booking::roundUpToMinuteInterval($dateTime, 15);
			$model->brt_pickup_datetime = $date->format("Y-m-d H:i:s");
			//}
		}
		else
		{
			$model->brt_pickup_datetime = $this->start_time;
		}
		return $model;
	}

	public function getMultiCityRoute($routes)
	{
		$routes		 = array();
		$source		 = json_decode(json_encode($this->source));
		$destination = json_decode(json_encode($this->destination));
		$arrStopages = $this->stopovers;

		array_unshift($arrStopages, $source);
		array_push($arrStopages, $destination);

		$arrFinalRoute = array();
		if (count($arrStopages) > 0)
		{
			foreach ($arrStopages as $key => $jsonRoute)
			{
				$nextKey = $key + 1;
				if (isset($arrStopages[$nextKey]))
				{
					$setKey								   = sizeof($arrFinalRoute);
					$arrFinalRoute[$setKey]['source']	   = json_decode(json_encode($jsonRoute), true);
					$arrFinalRoute[$setKey]['destination'] = json_decode(json_encode($arrStopages[$nextKey]), true);
				}
			}
		}
		$i					 = 0;
		$routeDuration		 = 0;
//		///////////////////
		$arrCount			 = count($arrFinalRoute);
		$sourceLatitude		 = $arrFinalRoute[0]['source']['latitude'];
		$destinationLatitude = $arrFinalRoute[$arrCount - 1]['destination']['latitude'];
		if ($this->trip_type == 'ROUND_TRIP')
		{
			if ($arrCount > 1)
			{
				$finalArr							= [];
				$finalArr[$arrCount]['source']		= $arrFinalRoute[$arrCount - 1]['destination'];
				$finalArr[$arrCount]['destination'] = $arrFinalRoute[0]['source'];
				$arrFinalRoute						= array_merge($arrFinalRoute, $finalArr);
			}
		}
		foreach ($arrFinalRoute as $value)
		{

			$this->stopage			 = new \Stub\common\Itinerary();
			$route					 = $this->stopage->getStoppageRouteData($value, $this->start_time, $routeDuration, $this->end_time, $arrFinalRoute);
			$route->useHyperLocation = false;
			$routes[]				 = $route;

			$this->start_time  = $routes[$i]->brt_pickup_datetime;
			$nextRouteDuration = $routes[$i]->brt_trip_duration;
			$i++;
			$prevRouteDuration = ($i == 1) ? 0 : $routes[$i - 1]->brt_trip_duration;
			$routeDuration	   = $nextRouteDuration + $routeDuration;
			$tripEndTime	   = date('Y-m-d H:i:s', strtotime($this->start_time . ' + ' . $nextRouteDuration . ' minute'));
			$this->start_time  = $tripEndTime;
		}

		if ($this->end_time < $tripEndTime)
		{
			throw new \Exception("Trip can not be completed on provided time estimate", \ReturnSet::ERROR_INVALID_DATA);
		}
		return $routes;
	}
}
