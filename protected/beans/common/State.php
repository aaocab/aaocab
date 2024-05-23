<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of State
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $name
 * @property string $country
 */

namespace Beans\common;

class State
{

	public $id;
	public $name;
	public $country;

	public function setData($data)
	{
		$this->id	 = (int) (($data['ctt_state']) ? $data['ctt_state'] : $data['stt_id']);
		$this->name	 = $data['stt_name'];
	}

	public function fillData($state)
	{
		$this->id	 = (int) $state->id;
		$this->label = $state->text;
	}

	public function getList($stateArr)
	{
		foreach($stateArr as $city)
		{
			$object		 = new \Beans\common\State();
			$object->fillData($city);
			$rowList[]	 = $object;
		}
		return $rowList;
	}

	public static function setIdName($data)
	{
		$obj->isDefault			 = 1;
		$obj->benificiaryName	 = $data['ctt_beneficiary_name'];
		return $obj;
	}

	public static function setStateIdName($data)
	{
		$obj		 = new State();
		$obj->id	 = $data->id;
		$obj->name	 = $data->text;
		return $obj;
	}

	public static function setBasicInfo($dataObj)
	{
		$obj		 = new State();
		$obj->id	 = $dataObj->stt_id;
		$obj->name	 = $dataObj->stt_name;
		return $obj;
	}
}
