<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Location
 *
 * @author Dev
 * 
 * @property \Beans\common\Coordinates $coordinates
 * @property string $address
 * @property string $geoPlace
 * @property array $types
 * @property string $bounds
 * @property string $pincode
 * @property \Beans\common\City $city
 * 
 */

namespace Beans\common;

class Location
{

	public $address;

	/** @var \Beans\common\City $city */
	public $city;

	/** @var \Beans\common\Coordinates $coordinates */
	public $coordinates;
	public $geoPlace;
	public $bounds;
	public $pincode;

	public static function setByCityData($data)
	{
		$objCity = \Beans\common\City::getData($data);
		return $objCity;
	}
	
	public static function setByRouteData($data,$bkgId=null)
	{
		
		$objData		 = (is_array($data)) ? \Filter::convertToObject($data) : $data;
		$obj			 = new Location();
		
		if($bkgId!=null)
		{
			$bkgModel	 = \Booking::model()->findByPk($bkgId);
			$status		 = $bkgModel->bkg_status;
		}
		if(!$bkgModel || $status>3)
		{
			$obj->address	 = $objData->location;
			$objLatLong			 = Coordinates::setData($objData);
			$obj->coordinates	 = $objLatLong;
		}
		else
		{
				$obj->address	               = 'xxxxxxx' . ', ' . $objData->city_name;
				$objShortLatLong			   = Coordinates::setShortData($objData);
				$obj->coordinates	           = $objShortLatLong;
		}

		$city	 = City::getData(['id' => $objData->city_id, 'name' => $objData->city_name]);
		$obj->city	 = $city;
		return $obj;
	}

	

	public static function setAddress($data)
	{
		$obj			 = new Location();
		$obj->address	 = $data['ctt_address'];
		if ($data['ctt_city'] > 0)
		{
			$objCity	 =\Beans\common\City::setDetail($data);
			$obj->city	 = $objCity;
		}
		return $obj;
	}
 public static function setAddressByCity($data,$addressObj)
    {
       $obj = new Location();

        $objCity          = \Beans\common\City::getData($data);
        $obj->city        = $objCity;
       // $obj              = new Location();
        $obj->address     = $addressObj->address;
        $objLatLong       = Coordinates::setData($addressObj->coordinates);
        $obj->coordinates = $objLatLong;
        return $obj;
    }
	
	public static function setLocationModel($model,$addressObj)
	{
		$model->drvContact->ctt_address      = $addressObj->address;
		$model->drvContact->ctt_city = $addressObj->city->id;
		$model->drvContact->ctt_state = $addressObj->city->state->id;
	}
	public static function setLocation ($model,$addressObj)
	{
		$model->ctt_address  = $addressObj->address;
		$model->ctt_city = $addressObj->city->id;
		$model->ctt_state = $addressObj->city->state->id;
		return $model;
		
	}
	
}