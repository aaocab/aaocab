<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Zone
{

	public $id, $name;

	public function setData($id = '')
	{
		$model		 = \ZoneCities::model()->getZonByCtyId($id);
		$this->id	 = (int) $model['zon_id'];
		$this->name	 = $model['zon_name'];
		return $this;
	}

	public function fillCat($row)
	{
		$this->id		 = (int) $row['zon_id'];
		$this->zonetype	 = $row['zon_name'];
	}

	public function setZone($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Zone();
			$obj->fillCat($row);
			$this->dataList[]	 = $obj;
		}
		return $this;
	}

	public function mapZone($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Zone();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
		return $this;
	}

	public function fillData($row)
	{
		$this->id	 = (int) $row['id'];
		$this->name	 = $row['name'];
	}

}
