<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Slabs
 * @author Roy
 * 
 * @property integer $percentage
 * @property integer $value
 * @property string $level
 * @property integer $isSelected
 */

namespace Beans\common;

class Slabs
{

	public $percentage;
	public $value;
	public $level;
	public $isSelected;

	/**
	 * 
	 * @param array $data
	 * @return \Beans\common\Slabs
	 */
	public function setElement($data)
	{
		$obj			 = new Slabs();
		$obj->percentage = (int) $data['percentage'];
		$obj->value		 = (int) $data['value'];
		$obj->label		 = $data['label'];
		$obj->isSelected = (int) $data['isSelected'];
		return $obj;
	}

}
