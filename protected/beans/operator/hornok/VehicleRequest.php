<?php

namespace Beans\operator\hornok;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VehicleRequest
{
	public $category;
	public $color;
	public $hasCNG;
	public $isExists;
	public $isOwned;
	public $number;
	public $untilDate;
	public $year;
	public $operatorDrvId;

	public function setParams($data)
	{
		$vehicleTypeId = \VehicleTypes::getModelTypeByMake($data->vehicle->brand);
		$this->category->id	 = $vehicleTypeId['vht_id'];
		$this->color		 = $data->cab->color;
		$this->hasCNG		 = ($data->cab->fuelType == 'Diesel') ? 0 : 1;
		$this->category->fuelType      = ($data->cab->fuelType == 'Diesel') ? 0 : 1;
		$this->isExists		 = false;
		$this->isOwned		 = 1;
		$this->number		 = $data->cab->regNo;
		$this->untilDate	 = '2029-09-25 00:00:00';
		$this->year			 = $data->cab->manufacturingYear;
		$this->operatorDrvId = $data->driver->id;
		return $this;
	}

	public static function getInstance($data)
	{
		$obj = new static();
		$obj->setParams($data);
		$jsonData = json_encode($obj);
		return $jsonData;
	}
}
