<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Coordinates
 *
 * @author Dev
 * 
 * @property string $lat
 * @property string $lng
 * @property string $altitude
 * @property string $accuracy
 */

namespace Beans\common;

class Coordinates
{

	public $lat;
	public $lng;
	public $altitude;
	public $accuracy;

	public static function setData($data)
	{
		$obj			 = new Coordinates();
		$obj->lat		 = (double) $data->latitude;
		$obj->lng		 = (double) $data->longitude;
		$obj->altitude	 = $data->altitude;
		$obj->accuracy	 = $data->accuracy;
		return $obj;
	}
	public static function setShortData($data)
	{
		$obj		 = new Coordinates();
		$obj->lat	 = round($data->latitude,2);
		$obj->lng	 = round($data->longitude,2);
		return $obj;
	}
	public static function setLatLng($data)
	{
		$obj		 = new Coordinates();
		$obj->lat	 = (double) $data->lat;
		$obj->lng	 = (double) $data->lng;
		return $obj;
	}

	public static function getCoordinateData(\BookingTrackLog $model = null, $data)
	{
		if ($model == null)
		{
			$model = new \BookingTrackLog();
		}
		return $model->btl_coordinates = $data->lat . "," . $data->lng;
	}

	public function getCoordinateString()
	{
		return $this->lat . "," . $this->lng;
	}

	public static function setLatLngValues($data)
	{
		$obj		 = new Coordinates();
		$obj->lat	 =  $data['bkg_pickup_lat'];
		$obj->lng	 =  $data['bkg_pickup_long'];
		return $obj;
	}
}
