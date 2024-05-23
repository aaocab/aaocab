<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of City
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $name
 * @property string $fullName
 * @property \Beans\common\State $state
 * @property \Beans\common\Coordinates $coordinates
 * @property string $bounds 
 */

namespace Beans\common;

class City
{

	public $id;
	public $name;
	public $fullName;

	/** @var \Beans\common\State $state */
	public $state;

	/** @var \Beans\common\Coordinates $coordinates */
	public $coordinates;
	public $bounds;

	public function setData($data)
	{
		$this->id		 = (int) $data['ctt_city'];
		$this->name		 = $data['cty_name'];
		$objState		 = new \Beans\common\State();
		$objState->setData($data);
		$this->state	 = $objState;
		$this->address	 = "";
	}

	public static function setDetail($data)
	{
		$obj		 = new City();
		$obj->id	 = (int) $data['ctt_city'];
		$obj->name	 = $data['cty_name'];
		if($data['ctt_state'] > 0)
		{
			$objState	 = new \Beans\common\State();
			$objState->setData($data);
			$obj->state	 = $objState;
		}
		return $obj;
	}

	/**
	 * 
	 * @param \Cities $cityModel
	 */
	public static function getData($data)
	{
		$obj				 = new City();
		$obj->id			 = (int) $data['id'];
		$obj->name			 = $data['name'];
		if($data['lat']!="")
		{
			$objCoordinate		 = new \Beans\common\Coordinates();
			$coordinateArr		 = ['lat' => $data['lat'], 'lng' => $data['lng']];
			$obj->coordinates	 = $objCoordinate->setLatLng(json_decode(json_encode($coordinateArr), FALSE));
		}	
		return $obj;
	}

	public function setDataByModel(\Cities $cityModel)
	{
		$this->id	 = (int) $cityModel->cty_id;
		$this->name	 = $cityModel->cty_name;
	}

	public function setDataByArray($cityArr)
	{
		$this->id	 = (int) $cityArr['cty_id'];
		$this->name	 = $cityArr['cty_name'];
	}

	public function fillData($city)
	{
		$this->id	 = (int) $city->id;
		$this->name = $city->text;
	}

	public function getList($cityArr)
	{
		foreach($cityArr as $city)
		{
			$object		 = new \Beans\common\City();
			$object->fillData($city);
			$rowList[]	 = $object;
		}
		return $rowList;
	}

	public function getListByData($cityArr)
	{
		$rowList = [];
		foreach($cityArr as $cityRow)
		{
			$object		 = new \Beans\common\City();
			$object->setDataByArray($cityRow);
			$rowList[]	 = $object;
		}
		return $rowList;
	}

	public function getById($cityId)
	{

		$this->code	 = (int) $cityId;
		$modelCity	 = \Cities::model()->findByPk($this->code);
		$this->name	 = $modelCity->cty_name;
		return $this;
	}

	public function setIdName($id, $name)
	{
		$this->id	 = (int) $id;
		$this->name	 = $name;
	}
}
