<?php

namespace Stub\mmt;

class Cab
{

	public $id, $type, $model, $image, $seatingCapacity, $bagCapacity, $bigBagCapaCity, $isAssured;
	public $cabNo, $cabModel, $cabName, $vctType;

	public function setCabType($cabId, $onlyShowModel = false, $showModel = false)
	{
		/* @var $svcModel SvcClassVhcCat */
		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);
		if ($onlyShowModel == true)
		{
			$this->model = $svcModel->vct_desc;
			return $this;
		}
		$this->id				 = (int) $svcModel->scv_id;
		$this->type		         = "hatchback";
        $this->isAssured         = $svcModel->is_assured;
		//$this->type				 = $svcModel->label;
		$this->vehicle_image			 = \YII::app()->createAbsoluteUrl($svcModel->vct_image);
		$this->seat_capacity	 = (int) $svcModel->vct_capacity;
		$this->luggage_allowance		 = (int) $svcModel->vct_big_bag_capacity;
		//$this->bigBagCapaCity	 = (int) $svcModel->vct_big_bag_capacity;
		if ($showModel == true)
		{
			$this->vehicle_model = $svcModel->vct_desc;
	}
	}

	public function setModelData($carmodel)
	{
		$this->id				 = (int) $carmodel->scv_id;
		$this->type				 = $carmodel->label;
		$this->image			 = $carmodel->vct_image;
		$this->model			 = $carmodel->vct_desc;
		$this->seatingCapacity	 = (int) $carmodel->vct_capacity;
		$this->bagCapacity		 = (int) $carmodel->vct_small_bag_capacity;
		$this->bigBagCapaCity	 = (int) $carmodel->vct_big_bag_capacity;
		return $this;
	}

	public function setVehicleType($vhtId)
	{
		$vhtMapArr = array_flip(\VehicleTypes::mapVehicleTypenId());
		$this->setCabType($vhtMapArr[$vhtId]);
	}

	public function setData($model)
	{
		$modelBcb		 = $model->bkgBcb;
		$this->cabNo	 = $modelBcb->bcb_cab_number;
		$this->cabModel	 = \VehicleCategory::model()->getCabByBkgId($model->bkg_id);
		$this->cabName	 = \VehicleCategory::model()->getCabNameBkgId($model->bkg_id);
		$arrSvcData		 = \SvcClassVhcCat::model()->getVctSvcList("detail", 0, 0, $model->bkg_vehicle_type_id);
		$vehicleCatId	 = $arrSvcData['scv_vct_id'];
		$vehicleCatId	 = ($vehicleCatId != '') ? $vehicleCatId : '';
		$this->vctType	 = $this->cabTypes[$vehicleCatId];
	}

}
