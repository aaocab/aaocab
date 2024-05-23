<?php

namespace Stub\common;

class Value
{

	public $value;
	public $isRequired, $toBeVerified;

	public function getModel($model= null)
	{
		if($model == null)
		{
			$model = new \VehicleDocs();
		}
		$this->value = $model->vhd_file;
		$this->toBeVerified = ($model->vhd_status != 1)? true : false;
		$this->isRequired   = ($model->vhd_file == null || $model->vhd_file == '')? true : false;
	}

}
