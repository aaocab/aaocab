<?php

namespace Stub\common;

/**
 * @property Coordinates $coordinates
 * @property \Location\Bounds $bounds Description
 *  */
class Cities
{

	public $id, $code, $name, $state;
	public $coordinates, $bounds, $radius;

	public static function getList(\CDbDataReader $reader)
	{
		$arr = [];
		foreach ($reader as $row)
		{
			$obj		 = new Cities();
			$obj->id	 = $row['cty_id'];
			$obj->name	 = $row['cty_display_name'];
			$obj->state	 = $row['stt_name'];
			$arr[]		 = $obj;
		}

		return $arr;
	}

	public function getIdName($cityId)
	{

		$this->code	 = (int) $cityId;
		$modelCity	 = \Cities::model()->findByPk($this->code);
		$this->name	 = $modelCity->cty_name;
		return $this;
	}

	public function fillData($row)
	{


		$this->code	 = $row['cty_id'];
		$this->name	 = $row['cty_name'];
	}

	public function CityStateList($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Cities();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}

	/** 
	 * @param int|\Cities $city 
	 * @return Cities */
	public static function getGeometricDetails($city)
	{
		$obj = new Cities();
		if (!$city instanceof \Cities)
		{
			$city = \Cities::model()->findByPk($city);
		}
		$obj->id			 = $city->cty_id;
		$obj->name			 = $city->cty_name;
		$obj->coordinates	 = new Coordinates($city->cty_lat, $city->cty_long);
		$obj->bounds		 = json_decode($city->cty_bounds);
		$obj->radius		 = $city->cty_radius;
		return $obj;
	}

}
