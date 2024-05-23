<?php

namespace Stub\vendor;

class checkDuplicateVehicleRequest
{
	public $number;

	public function setModel(\Vehicles $model = null)
	{
		if($model = null)
		{
			$model = new \Vehicles();
		}
		$model->vhc_number = $this->number;
		
		return $model;
	}
}
