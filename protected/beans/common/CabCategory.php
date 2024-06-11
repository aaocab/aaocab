<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cab
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $name
 * @property \Beans\common\FuelType $fuleType
 * @property \Beans\common\AllowedModels[] $allowedModels 
 */

namespace Beans\common;

class CabCategory
{

	public $id;
	public $name;

	/** @var \Beans\common\FuelType $fuelType */
	public $fuelType;

	/** @var \Beans\common\AllowedModels[] $allowedModels */
	public $allowedModels;

	public static function setData($data)
	{
		$obj = new CabCategory();

		//$obj->name				 = $data->cab_lavel;
		$obj->name				 = ($data->bkg_status > 2 ? $data->scv_label : $data->cab_lavel);
		$level					 = "({$data->cab_lavel})";
		$data->scv_label		 = trim(str_replace($level, "", $data->scv_label));
		$objCabModel			 = new \Beans\common\Cab();
		$objCabModel->setDataValue($data);
		$obj->allowedModels[]	 = $objCabModel;
		return $obj;
	}

	/** @var \Booking $model */
	public static function setByModel($model)
	{
		$obj		 = new CabCategory();
		$obj->id	 = (int) $model->bkg_vehicle_type_id;
		$obj->name	 = $model->bkgSvcClassVhcCat->scv_label;

		return $obj;
	}

	/**
	 * 
	 * @param \VehicleTypes $vhtModel
	 * @return type
	 */
	public static function setByVhtModel($vhtModel, $showAllowedModel = true, $bcbModel = null)
	{


		$obj	 = new CabCategory();
		$obj->id = (int) $vhtModel->vht_VcvCatVhcType->vcv_VehicleCategory->vct_id;
		if (!empty($bcbModel))
		{
			$bookingModel	 = $bcbModel->bookings;
			$vhcModel		 = $bookingModel[0]->bkgSvcClassVhcCat->scv_label;
		}
		$obj->name = $vhtModel->vht_VcvCatVhcType->vcv_VehicleCategory->vct_label . '(' . $vhcModel . ')';

		//$obj->name	 = $vhtModel->vht_name;
		if ($showAllowedModel)
		{
			$obj->allowedModels[] = \Beans\common\AllowedModels::setByModel($vhtModel);
		}
		return $obj;
	}

	public static function setByDataModel($vhtModel, $showAllowedModel = true)
	{
		$obj		 = new CabCategory();
		$obj->id	 = (int) $vhtModel->vct_id;
		$obj->name	 = $vhtModel->vct_label;
		if ($showAllowedModel)
		{
			$obj->allowedModels[] = \Beans\common\AllowedModels::setByModel($vhtModel);
		}
		return $obj;
	}

	/**
	 * 
	 * @param \VehicleTypes $vhtModel
	 * @return type
	 */
	public static function setSkuId($model)
	{
		$obj					 = new CabCategory();
		$obj->allowedModels[]	 = \Beans\common\AllowedModels::setSKU($model);
		return $obj;
	}
}
