<?php

namespace Stub\common;

class AirportCities extends \Stub\common\Cities
{

	public $radius;

	/** @var \Stub\common\Coordinates $coordinates */
	public $coordinates;

	public function getData($data)
	{
		$arr = [];
		foreach ($data as $row)
		{
			$obj						 = new $this;
			$obj->id					 = (int) $row['id'];
			$obj->name					 = $row['text'];
			$obj->radius				 = (int) $row['radius'];
			$obj->coordinates->latitude	 = (double) $row['lat'];
			$obj->coordinates->longitude = (double) $row['lng'];
			$arr[]						 = $obj;
		}
		return $arr;
	}

}
