<?php

namespace Stub\common;

class Route
{

	public $id;
	public $aliasName;
	public $actualDistance;
	public $actualTime;
	public $estimatedDistance;
	public $estimatedTime;

	public function getData(\Route $route=null)
	{
		if($route==null)
		{
			return false;
		}
		$this->id				 = (int) $route->rut_id;
		$this->aliasName		 = $route->rut_name;
		$this->actualDistance	 = (int) $route->rut_actual_distance;
		$this->actualTime		 = (int) $route->rut_actual_time;
		$this->estimatedDistance = (int) $route->rut_estm_distance;
		$this->estimatedTime	 = (int) $route->rut_estm_time;
	}

}
