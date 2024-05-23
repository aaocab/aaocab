<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RouteResponse
{

	public $extraCharge;
	public $extraDistance;
	public $oldFare;
	public $fare;

	public function setData($model)
	{
		$data				 = $model->updateDistance;
		$this->extraCharge	 = $data['extra_charge'];
		$this->extraDistance = $data['additional_km'];
		$this->oldFare		 = $data['oldBaseFare'];
		$this->fare			 = (object) $data['fare'];
	}

}

