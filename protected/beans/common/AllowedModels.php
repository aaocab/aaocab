<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AllowedModels
 *
 * @author Dev
 * 
 * @property int $id 
 * @property string $make 
 * @property string $model
 * @property string $category 
 * @property string $name
 * 
 */

namespace Beans\common;

class AllowedModels
{

	public $id;
	public $make;
	public $model;
	public $category;
	public $skuId;
	public $name;

	public static function setByModel($vhtModel)
	{
		$obj			 = new AllowedModels();
		$obj->make		 = $vhtModel->vht_make;
		$obj->model		 = $vhtModel->vht_model;
		$obj->category	 = $vhtModel->vht_VcvCatVhcType->vcv_VehicleCategory->vct_label;
		return $obj;
	}

	public static function setSKU($model)
	{
		$obj		 = new AllowedModels();
		$obj->name	 = $model->bkgSvcClassVhcCat->scv_label;
		$obj->skuId	 = $model->bkgSvcClassVhcCat->scv_code;
		return $obj;
	}

	public static function setTypeRow($row)
	{
		$obj		 = new AllowedModels();
		$obj->id	 = (int)$row['vht_id'];
		$obj->make	 = $row['vht_make'];
		$obj->model	 = $row['vht_model'];
		return $obj;
	}

	public static function setTypeList($data)
	{
		$dataList = [];
		foreach ($data as $row)
		{
			$dataList[] = self::setTypeRow($row);
		}
		return $dataList;
	}
}
