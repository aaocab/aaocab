<?php

namespace Stub\vendor;


class getVehicleDetailsRequest
{
	public $id;

	public function setModel(\Vehicles $model = null)
	{
		if($model = null)
		{
			$model = new \Vehicles();
		}
		$model->vhc_id = $this->id;
		
		return $model;
	}
}
