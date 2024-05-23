<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdditionalCharge
 *
 * @author Dev
 * 
 * @property integer $type 
 * @property string $description
 * @property integer $amount
 * @property bool $isGSTApplicable
 * @property integer $collectExtra
 * @property string $startTime
 * @property string $endTime
 * @property integer $isIncluded
 */

namespace Beans\booking;

class Tags
{

	public $type;  //Toll/State/Parking/DriverAllowance/NightPickup/NightDrop/Convenience Fee/COD Fee/others
	public $isIncluded;
	public $category;

	/**
	 * 
	 * @param \Booking $model
	 * @param type $type
	 * @return \Beans\booking\AdditionalCharge
	 */
	public static function setByModel(\Booking $model, $type = '')
	{
		$typeList	 = self::getList($type,$model);
		
		$addItional	 = [];
		foreach ($typeList as $key => $type)
		{
			$obj		 = new Tags();
			$obj->type	 = $key;
			switch ($type)
			{
				case "B2B":
					$obj->isB2B($model);
					break;
				case "OTPRequired":
					$obj->isOTPRequired($model);
					break;
				case "DriverAppRequired":
					$obj->isDriverAppRequired($model);
					break;
				case "CNGAllowed":
					$obj->isCNGAllowed($model);
					break;
				case "CNGWithRoofTop":
					$obj->isCNGWithRoofTop($model);
					break;

				default:
					break;
			}
			$addItional[] = $obj;
		}
		return $addItional;
	}

	public static function getList($type = '',$model)
	{
		/*$typeList = [
			1	 => "B2B",
			2	 => "OTPRequired",
			3	 => "DriverAppRequired",
			4	 => "CNGAllowed",
			5	 => "CNGWithRoofTop"
		];*/
		//to be enable when is included flag wil be used in app  according to AK  date 19-02-2023
		//temporaritly if included 0  no  data will be shown
		$serviceClass             = $model->bkgSvcClassVhcCat->scv_scc_id;
		$typeList = array();
		if($model->bkg_agent_id > 0 && $model->bkg_agent_id != 1249)
		{
			$typeList[] = "B2B";
		}
		if($model->bkgPref->bkg_trip_otp_required ==1)
		{
			$typeList[] =  "OTPRequired";
		}
		if($model->bkgPref->bkg_driver_app_required ==1)
		{
			$typeList[] = "DriverAppRequired";
		}
		if($model->bkgPref->bkg_cng_allowed == 1)
		{
			$typeList[] = "CNGAllowed";
		}
		if($model->bkgPref->bkg_cng_allowed == 1 && $serviceClass == \ServiceClass::CLASS_VLAUE_PLUS)
		{
			$typeList[] = "CNGWithRoofTop";
		}
		
		/*if ($model->bkgPref->bkg_driver_app_required == 1)
		{
			$key = array_search($type, $typeList);
			if ($key != null)
			{
				return [$key => $type];
			}
		}*/
		return $typeList;
	}

	/**
	 * 
	 * @param \Booking $model
	 */
	public function isB2B($model)
	{
		if($model->bkg_agent_id > 0 && $model->bkg_agent_id != 1249)
		{
		$this->description	 = "B2B";
		$this->category		 = 1;
		$this->isIncluded	 = (int) ($model->bkg_agent_id > 0 && $model->bkg_agent_id != 1249) ? 1 : 0;
		}
		
	}

	public function isOTPRequired($model)
	{
		
		
		$this->description	 = "OTP Required";
		$this->category		 = 2;
		$this->isIncluded	 = (int) $model->bkgPref->bkg_trip_otp_required;
		
	}

	public function isDriverAppRequired($model)
	{
		
		$this->description	 = "Driver App Required";
		$this->category		 = 1;
		$this->isIncluded	 = (int) $model->bkgPref->bkg_driver_app_required;
		
	}

	public function isCNGAllowed($model)
	{
		//to be enable when is included flag used in app
		
		$this->description	 = "CNG Allowed";
		$this->category		 = 3;
		$this->isIncluded	 = (int) ($model->bkgPref->bkg_cng_allowed == 1) ? 1 : 0;
		
	}

	public function isCNGWithRoofTop($model)
	{
		
		$serviceClass             = $model->bkgSvcClassVhcCat->scv_scc_id;
		$this->description	 = "Carrier required on rooftop ";
		$this->category		 = 4;
		$this->isIncluded	 = (int) ($model->bkgPref->bkg_cng_allowed == 1 && $serviceClass ==\ServiceClass::CLASS_VLAUE_PLUS) ? 1 : 0;
		
	}

}
