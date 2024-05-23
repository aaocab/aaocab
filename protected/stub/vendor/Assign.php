<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Assign
{

	public $driverContactNumber;
	public $driverId;
	public $vehicleId;
    public $tripId;
	
	public function getData()
	{
		$model->bcb_cab_id       =  (int)$this->vehicleId;
        $model->bcb_driver_phone =  $this->driverContactNumber;
        $model->bcb_driver_id    =  (int)$this->driverId;
		$model->bcb_id           =  (int)$this->tripId;
		return $model;
	}
}
