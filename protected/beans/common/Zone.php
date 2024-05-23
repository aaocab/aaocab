<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zone
 *
 * @author Deepak
 * 
 * @property integer $id
 * @property string $name
 * @property string $fullName
 * @property \Beans\common\State $state
 * @property \Beans\common\Coordinates $coordinates
 * @property string $bounds 
 */

namespace Beans\common;

class Zone
{

	public $id;
	public $name;

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

	public function setDataByModel(\Zones $zoneModel)
	{
		$this->id	 = (int) $zoneModel->zon_id;
		$this->name	 = $zoneModel->zon_name;
	}

	 

}
