<?php

namespace Stub\common;

/**
 * @property \Stub\common\CabCategory $cabCategory
 */
class Cab
{

	public $id, $type, $categoryId, $category, $sClass, $instructions,
			$model, $image, $seatingCapacity, $bagCapacity, $bigBagCapaCity, $isAssured,
			$cab_type, $cab_number, $cab_assigned, $cabModel, $fuelType;

	/** @var \Stub\common\CabCategory $cabCategory */
	public $cabCategory;

	public function setCabType($cabId, $onlyShowModel = false, $showModel = false, $cancelText = null)
	{
		/* @var $svcModel SvcClassVhcCat */
		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);
		if ($onlyShowModel == true)
		{
			$this->model = $svcModel->vct_desc;
			return $this;
		}

		$var = explode('/', $svcModel->vct_image);

		$this->id				 = (int) $svcModel->scv_id;
		$this->isAssured		 = $svcModel->is_assured;
		$this->type				 = $svcModel->scv_label;
		$this->category			 = \SvcClassVhcCat::getCatrgoryLabel($svcModel->scv_id);
		$this->categoryId		 = $svcModel->vct_id;
		$this->sClass			 = $svcModel->scc_label;
		//$this->scvParent		 = $svcModel->scv_parent_id;
		$this->image			 = \YII::app()->createAbsoluteUrl($svcModel->vct_image);
		$this->seatingCapacity	 = (int) $svcModel->vct_capacity;
		$this->bagCapacity		 = (int) $svcModel->vcsc_small_bag;
		$this->bigBagCapaCity	 = (int) $svcModel->big_bag;

		$userInfo = \UserInfo::getInstance();

		if ($userInfo->userId == 30228)
		{
			$instructionsArray	 = ["Car will be of any model in car category you choose"];
			$instructions		 = json_encode($instructionsArray);
			$this->instructions	 = json_decode($instructions);
		}
		else
		{
			if ($cancelText == null)
			{
				$this->instructions = json_decode($svcModel->scc_desc);
			}
			else
			{
				$instructionsArray	 = json_decode($svcModel->scc_desc);
				$instructions[0]	 = "Free cancellation " . $cancelText;
				$instructions[1]	 = $instructionsArray[0];
				$this->instructions	 = $instructions;
			}
		}
		if ($showModel == true)
		{
			if (in_array($svcModel->scv_id, [1, 2, 3]))
			{
				$fuelType		 = "Diesel/Petrol";
				$this->fuelType	 = $fuelType;
			}
			else
			{
				$fuelType		 = "CNG";
				$this->fuelType	 = $fuelType;
			}

			$this->model = $svcModel->vct_desc . ' ' . $fuelType;
		}
		$this->cabCategory = new CabCategory();
		$this->cabCategory->setData($cabId);
	}

	public function setCabType_v1($cabId, $onlyShowModel = false, $showModel = false)
	{
		/* @var $svcModel SvcClassVhcCat */
		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);

		if ($onlyShowModel == true)
		{
			$this->model = $svcModel->vct_desc;
			return $this;
		}

		$var = explode('/', $svcModel->vct_image);

		//$this->id				 = (int) $svcModel->scv_id;
		$this->isAssured		 = $svcModel->is_assured;
		//	$this->type				 = $svcModel->label;
		//$this->sClass			 = $svcModel->scc_label;
		$this->image			 = IMAGE_URL . '/' . $var[1] . '/' . $var[2]; //\YII::app()->createAbsoluteUrl($svcModel->vct_image);
		$this->seatingCapacity	 = (int) $svcModel->vct_capacity;
		$this->bagCapacity		 = (int) $svcModel->vcsc_small_bag;
		$this->bigBagCapaCity	 = (int) $svcModel->big_bag;
		$this->instructions		 = json_decode($svcModel->scc_desc);
		if ($showModel == true)
		{
			$this->model = $svcModel->vct_desc;
		}

		$this->cabCategory = new CabCategory();
		$this->cabCategory->setData($cabId);
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

	public function fillData($row)
	{
		$this->id	 = (int) $row['vhc_number'];
		$this->type	 = $row['car_type'];
		$this->model = $row['vht_model'];
		//$this->model		 = $row['vhc_insurance_exp_date'];
	}

	public function getList($dataArr)
	{
		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Cab();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}

	public function cabDetails($row)
	{
		$this->cab_number	 = $row['cab_number'];
		$this->cab_type		 = $row['cab_type'];
		$this->cab_assigned	 = $row['cab_assigned'];
	}

	public function mapCategoryServiceClass()
	{
		/* @var $svcModel SvcClassVhcCat */
		$categories				 = [];
		$classes				 = [];
		$categoryServiceClasses	 = [];

		$svcVctResult = \SvcClassVhcCat::mapAllCategoryServiceClass($categoryServiceClasses, $classes, $categories);
		return ['rel' => $categoryServiceClasses, 'classes' => array_keys($classes), 'categories' => array_keys($categories)];
	}

	public function setCategory($cabId)
	{
		$this->cabCategory = new CabCategory();
		$this->cabCategory->setData($cabId);
	}
}
