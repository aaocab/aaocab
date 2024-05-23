<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Route
 *
 * @author Dev
 * 
 * @property \Beans\common\Location $source
 * @property \Beans\common\Location $destination
 * @property string $distance
 * @property int $travelTime
 * @property string $pickupTime
 * @property string $endTime
 */

namespace Beans\booking;

class Route
{

	public $pickupTime;
	public $endTime;

	/** @var \Beans\common\Location $source */
	public $source;

	/** @var \Beans\common\Location $destination */
	public $destination;
	public $distance;
	public $travelTime;
    
    /** @var \Beans\common\Location $address */
	public $address;

	public function setData($routeData,$loaction ='',$type=1,$transferType=0)
    {
		$route    = new \Beans\booking\Route();
        $dataList = [];
        count($routeData);
        if ($type == 3)
        {
            foreach ($routeData as $key => $brtData1)
            {
                $brtData         = (is_array($brtData1)) ? \Filter::convertToObject($brtData1) : $brtData1;
                $sourceData      = ['lat' => $brtData->brt_from_latitude, 'lng' => $brtData->brt_from_longitude,'name' => $brtData->brt_from_location, 'id' => ($brtData->brt_from_city_id) ? ($brtData->brt_from_city_id) : ($brtData->source->city->id)];
             
                if($loaction[$key]["from_place"]==null)
                {
                    $from_place = $loaction[$key-1]["from_place"];
                }else{
                    $from_place = $loaction[$key]["from_place"];
                }
                $route->source   = \Beans\common\Location::setAddressByCity($sourceData, json_decode($from_place));
              
                $destData           = ['lat' => $brtData->brt_to_latitude, 'lng' => $brtData->brt_to_longitude,'name' => $brtData->brt_to_location, 'id' => ($brtData->brt_to_city_id) ? ($brtData->brt_to_city_id) : ($brtData->destination->city->id)];
                $route->destination = \Beans\common\Location::setAddressByCity($destData, json_decode($loaction[$key]["to_place"]));

                $route->pickupTime = $brtData->brt_pickup_datetime;
                $route->travelTime = $brtData->brt_trip_duration;
                $route->distance   = $brtData->brt_trip_distance;
                \Filter::removeNull($route);
                $dataList[] = $route;
            }
            return $dataList;
        }else if($type == 4)
        {
//            stdClass object {
//  airport => (string) 31966
//  place => (string) {"name":null,"alias":null,"coordinates":{"latitude":22.6527,"longitude":88.438},"types":null,"address":"dsfdsfdsfdsfdsfdsfds, OYO Hotel Jagannath International Near Netaji Subhash Chandra Bose International Airport, Hotel Jagannath International, Jessore Road, near Netaji Subash Chandra Bose International Airport, opposite Airport Gate, Manikpur, Rajbari, Dum Dum, Kolkata, West Bengal, India (fddfsfsdfsd)","place_id":"","bounds":null,"review":1}
//  brt_pickup_date_date => (string) 16/11/2023
//  brt_pickup_date_time => (string) 08:45 pm
//      }
            $brtData = (is_array($routeData)) ? \Filter::convertToObject($routeData) : $routeData;
            if ($transferType == 1)
            {
                $sourceData    = ['id' => ($brtData->airport) ? ($brtData->airport) : ($brtData->source->city->id)];
                $route         = new \Beans\booking\Route();
                $route->source = \Beans\common\Location::setAddressByCity($sourceData, json_decode($brtData->place));
            }
            else
            {
                $destData           = ['id' => ($brtData->airport) ? ($brtData->airport) : ($brtData->destination->city->id)];
                $route              = new \Beans\booking\Route();
                $route->destination = \Beans\common\Location::setAddressByCity($destData, json_decode($brtData->place));
            }

            $date              = \DateTimeFormat::DatePickerToDate($brtData->brt_pickup_date_date);
            $time              = date('H:i:00', strtotime($brtData->brt_pickup_date_time));
            $route->pickupTime = $date . " " . $time;
            return $route;
        }
        else
        {
            $brtData = (is_array($routeData)) ? \Filter::convertToObject($routeData) : $routeData;
       
            $sourceData    = ['id' => ($brtData->brt_from_city_id) ? ($brtData->brt_from_city_id) : ($brtData->source->city->id)];
                
            $route    = new \Beans\booking\Route();
            $route->source = \Beans\common\Location::setAddressByCity($sourceData, json_decode($loaction[0]["from_place"]));

            $destData           = ['id' => ($brtData->brt_to_city_id) ? ($brtData->brt_to_city_id) : ($brtData->destination->city->id)];
            $route->destination = \Beans\common\Location::setAddressByCity($destData, json_decode($loaction[0]["to_place"]));
            if ($route->pickupTime == null && $loaction == '')
            {
                $date              = \DateTimeFormat::DatePickerToDate($brtData->brt_pickup_date_date);
                $time              = date('H:i:00', strtotime($brtData->brt_pickup_date_time));
                $route->pickupTime = $date . " " . $time;
            }
            else
            {
                $route->pickupTime = $brtData->pickupTime;
            }
            if ($route->endTime == null && $loaction == '')
            {
                $date           = \DateTimeFormat::DatePickerToDate($brtData->brt_return_date_date);
                $time           = date('H:i:00', strtotime($brtData->brt_return_date_time));
                $route->endTime = $date . " " . $time;
            }
            else
            {
                $route->endTime = $brtData->endTime;
            }
           // \Filter::removeNull($route);
             return $route;
        }
           
    }

    public static function setDataByBkgIds($bkgids)
	{
		$dataList = [];

		$brtDataSet = \BookingRoute::getByBkgids($bkgids);
		if ($brtDataSet->getRowCount() == 0)
		{
			return false;
		}

		foreach ($brtDataSet as $data)
		{
			$route	 = new \Beans\booking\Route();
			$brtData = (is_array($data)) ? \Filter::convertToObject($data) : $data;

			$sourceData					 = ['id' => $brtData->fCityId, 'name' => $brtData->fCityName];
			$route->source->city		 = \Beans\common\Location::setByCityData($sourceData);
#
			$destData					 = ['id' => $brtData->tCityId, 'name' => $brtData->tCityName];
			$route->destination->city	 = \Beans\common\Location::setByCityData($destData);
#
			$route->pickupTime			 = $brtData->brt_pickup_datetime;
			$route->travelTime			 = $brtData->brt_trip_duration;
			$route->distance			 = $brtData->brt_trip_distance;
			$dataList[]					 = $route;
		}
		return $dataList;
	}

	public function setDataByBooking($bkgId)
	{
		$dataList = [];

		$brtDataSet = \BookingRoute::getDataByBkgid($bkgId);

		foreach ($brtDataSet as $brtData)
		{
			$route	 = new Route();
			$source	 = \Beans\common\Location::setByRouteData($brtData['source'],$bkgId);

			$route->source = $source;

			$destination		 = \Beans\common\Location::setByRouteData($brtData['dest'],$bkgId);
			$route->destination	 = $destination;
			$route->travelTime	 = (int) $brtData['brt_trip_duration'];
			$route->distance	 = (int) $brtData['brt_trip_distance'];
			$route->pickupTime	 = $brtData['brt_pickup_datetime'];
			$returnDate			 = date('Y-m-d H:i:s', strtotime('+' . $brtData['brt_trip_duration'] . ' minutes', strtotime($brtData['brt_pickup_datetime'])));
			$route->endTime		 = $returnDate;
			$dataList[]			 = $route;
		}
		return $dataList;
	}

    public function getData($param)
    {
        
    }
    
}
