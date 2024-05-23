<?php

namespace Beans\cab;

/**
 * Model
 *
 * 	@property string $id
 * @property string $make
 * @property string $model
 * @property string $year
 */
class Model
{

	public $id;
	public $make;
	public $model;
	public $year;

	public static function setData($data)
	{
		$data		 = (is_array($data)) ? \Filter::convertToObject($data) : $data;
		$obj		 = new Model();
		$obj->id	 = (int)$data->vht_id;
		$obj->make	 = $data->vht_make;
		$obj->model	 = $data->vht_model;
		return $obj;
	}
}

?>