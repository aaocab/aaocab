<?php

namespace Beans\cab;

/**
 * LOU
 * 
 */
class LOU
{

	public $validTill;
	public $vendor;

	/** @var \Beans\Vendor vendor */

	/**
	 * 
	 * @param \VendorVehicle $vendorVehicleModel
	 */
	public function setDataByModel(\VendorVehicle $vendorVehicleModel)
	{
		//$this->validTill = $vendorVehicleModel->vvhc_vhc_owner_auth_valid_date;
		$this->vendor	 = \Beans\Vendor::setDataByModel($vendorVehicleModel->vvhcVnd);
	}
}
 