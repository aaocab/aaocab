<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of AdvanceSlabs
 * @author Roy
 * @property integer $percentage
 * @property integer $value
 * @property string $level
 * @property integer $isSelected
 */

namespace Beans\transaction;

class AdvanceSlabs
{

	public $percentage;
	public $value;
	public $level;
	public $isSelected;

	 /** 
	  * 
	  * @param Array $data
	  * @return \Beans\transaction\AdvanceSlabs
	  */
	public static function setElement($data)
	{
		$obj			 = new AdvanceSlabs();
		$obj->percentage = (int) $data['percentage'];
		$obj->value		 = (int) $data['value'];
		$obj->label		 = $data['label'];
		$obj->isSelected = (int) $data['isSelected'];
		return $obj;
	}

}
