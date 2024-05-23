<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Place
 *
 * @author Admin
 * @property Coordinates $coordinates
 */
class Place
{

	public $name, $alias;
	public $coordinates;
	public $types;
	public $address;
	public $place_id;
	public $bounds;
	public $review;

	public static function initGoogePlace($placeObj)
	{
		$obj			 = new Place();
		$obj->name		 = $placeObj->address_components[0]->long_name;
		$obj->alias		 = $placeObj->address_components[0]->short_name;
		$obj->place_id	 = $placeObj->place_id;
		$obj->types		 = $placeObj->types;
		$obj->address	 = $placeObj->formatted_address;

		$geometry			 = $placeObj->geometry;
		$latitude			 = $geometry->location->lat;
		$longitude			 = $geometry->location->lng;
		$obj->coordinates	 = new Coordinates($latitude, $longitude);

		$obj->bounds = $geometry->viewport;
		if (isset($geometry->bounds))
		{
			$obj->bounds = $geometry->bounds;
		}
		$obj->review = self::isReview($placeObj);
		return $obj;
	}

	public static function isReview($placeObj)
	{
		$placeTypes = ($placeObj->types == null) ? [] : $placeObj->types;
		
		$review	 = 0;
		if (($key	 = array_search('political', $placeTypes)) !== false)
		{
			unset($placeObj->types[$key]);
		}

		foreach ($placeTypes as $type)
		{
			if ($review == 1)
			{
				break;
			}
			foreach ($placeObj->address_components as $component)
			{
				if (in_array($type, $component))
				{
					$review = 1;
					break;
				}
			}
		}
		return $review;
	}

	public function isUnderLocality()
	{
		$check = $this->isCountry() || $this->isAdminLevel1() || $this->isAdminLevel2() || $this->isLocality() || $this->isAdminLevel3();

//		if ($check)
//		{
//			$area	 = \Filter::calculateAreaByBounds($this->bounds);
//			$check	 = ($area > 4);
//		}

		return !$check;
	}

	public function isPOI()
	{
		return (is_array($this->types) && in_array('point_of_interest', $this->types));
	}

	public function isAirport()
	{
		return (is_array($this->types) && in_array('airport', $this->types));
	}

	public function isSubLocality3()
	{
		return (is_array($this->types) && in_array('sub_locality_level_3', $this->types));
	}

	public function isSubLocality2()
	{
		return (is_array($this->types) && in_array('sub_locality_level_3', $this->types));
	}

	public function isSubLocality1()
	{
		return (is_array($this->types) && in_array('sub_locality_level_3', $this->types));
	}

	public function isLocality()
	{
		return (is_array($this->types) && in_array('locality', $this->types));
	}

	public function isAdminLevel3()
	{
		return (is_array($this->types) && in_array('administrative_area_level_3', $this->types));
	}

	public function isAdminLevel2()
	{
		return (is_array($this->types) && in_array('administrative_area_level_2', $this->types));
	}

	public function isAdminLevel1()
	{
		return (is_array($this->types) && in_array('administrative_area_level_1', $this->types));
	}

	public function isCountry()
	{
		return (is_array($this->types) && in_array('country', $this->types));
	}

	public static function init($lat, $long, $placeId = '', $address = '', $types = null)
	{
		$obj				 = new Place();
		$obj->types			 = $types;
		$obj->place_id		 = trim($placeId);
		$obj->address		 = trim($address);
		$obj->coordinates	 = new Coordinates(trim($lat), trim($long));
		$obj->review		 = 1;
		return $obj;
	}

	public static function initGoogleRoute($lat, $long, $placeId = '', $address = '', $name = '')
	{
		$obj				 = new Place();
		$obj->name			 = $name;
		$obj->place_id		 = trim($placeId);
		$obj->address		 = trim($address);
		$obj->coordinates	 = new Coordinates(trim($lat), trim($long));
		$obj->review		 = 1;
		return $obj;
	}

	/**
	 * @param \LatLong $ltgModel 
	 * @return Place 
	 */
	public static function getLatLongModel($ltgModel)
	{
		$obj				 = new Place();
		$obj->name			 = $ltgModel->ltg_name;
		$obj->place_id		 = trim($ltgModel->ltg_place_id);
		$obj->address		 = trim($ltgModel->ltg_locality_address);
		$obj->coordinates	 = new Coordinates(trim($ltgModel->ltg_lat), trim($ltgModel->ltg_long));
		$obj->types			 = json_decode($ltgModel->ltg_types);
		$obj->bounds		 = json_decode($ltgModel->ltg_bounds);
		return $obj;
	}


public static function initCustomGoogePlace($placeObj,$rawAddress="")
	{
		$obj			 = new Place();
		$obj->name		 = $placeObj->name;
		$obj->alias		 = $placeObj->alias;
		$obj->place_id	 = $placeObj->place_id;
		$obj->types		 = $placeObj->types;
		$obj->address	 = ($rawAddress!="") ? ($rawAddress) : ($placeObj->address);

		$geometry			 = $placeObj->geometry;
		$latitude			 = $placeObj->coordinates->latitude;
		$longitude			 = $placeObj->coordinates->longitude;
		$obj->coordinates	 = new Coordinates($latitude, $longitude);

		$obj->bounds = $geometry->viewport;
		if (isset($geometry->bounds))
		{
			$obj->bounds = $geometry->bounds;
		}
		$obj->review = self::isReview($placeObj);
		return $obj;
	}

}
