<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zones
 *
 * @author Deepak
 * 
 * 
 * @property \Beans\common\Zone[] $zone  
 */

namespace Beans\common;

class Zones
{

	public function setData($data)
	{
		$this->id	 = (int) $data['zon_id'];
		$this->name	 = $data['zon_name'];
	}

	public static function setDetail($data)
	{
		$obj		 = new Zone();
		$obj->id	 = (int) $data['zon_id'];
		$obj->name	 = $data['zon_name'];

		return $obj;
	}

	public static function getData($data)
	{
		$obj		 = new Zone();
		$obj->id	 = (int) $data['id'];
		$obj->name	 = $data['name'];
		return $obj;
	}

	public function setListByModel(\Zones $zoneModel)
	{
		$this->zones = new Zones();
		$zones		 = [];
		foreach ($zoneModel as $row)
		{
			$obj		 = new Zone();
			$obj->id	 = (int) $row->zon_id;
			$obj->name	 = $row->zon_name;
			$zones[]	 = $obj;
		}
		$this->zones = $zones;
	}

	public static function getListByCityId($cityId)
	{
		$dataReader	 = \Zones::getListByCityId($cityId);
//		$obj		 = new Zones();
		$zones		 = [];
		foreach ($dataReader as $row)
		{
			$zones[] = Zone::setDetail($row);
		}
		return $zones;
	}

	public static function setListByData($zoneData)
	{
		$zones = [];
		foreach ($zoneData as $row)
		{
			$zones[] = Zone::setDetail($row);
		}
		return $zones;
	}

	public static function getByData($zoneData)
	{
		$zones = [];
		foreach ($zoneData as $row)
		{
			$zones[] = Zone::getData($row);
		}
		return $zones;
	}

}
