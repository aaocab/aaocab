<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\common;


class Category
{
	
	public $skuId;
	public $name;
	/** @var \Beans\common\AllowedModels[] $allowedModels */
	public $allowedModels;

	public static function setData($data)
	{
		$obj					 = new Category();
		$obj->skuId				 = $data->category->skuId;
		$obj->name				 = $data->category->name;
		$obj->allowedModels[]	 = \Beans\common\AllowedModels::setModel($data);
		return $obj;
	}
}

