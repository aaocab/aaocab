<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ChauffeurDetails
{
    public $name;
    public $mobile;
	public $vehicle;


	/**
	 * This function is used to get chauffeur details
	 * @param Booking $model
     * @return [object]
	 */
	public function setData($model)
	{
        $modelMergedVendor	 = \Vendors::model()->mergedVendorId($model->bkgBcb->bcb_vendor_id);
        $cttId = $modelMergedVendor->vnd_contact_id;
            if ($cttId != '')
            {
                $number = \ContactPhone::model()->getContactPhoneById($cttId);
                if (empty($number))
                {
                    $vndCtn	 = \ContactPhone::model()->findByContactID($cttId);
                    $number	 = $vndCtn[0]->phn_phone_no;
                }
            }
		$this->name		 = $modelMergedVendor->vnd_name;
        $this->mobile        = $number;
	}

	public function getData($model)
	{
		$modelDriver = \Drivers::model()->findByPk($model->bkgBcb->bcb_driver_id);
		$cttId		 = $modelDriver->drv_contact_id;
		if ($cttId != '')
		{
			$number = \ContactPhone::model()->getContactPhoneById($cttId);
			if (empty($number))
			{
				$drvCtn	 = \ContactPhone::model()->findByContactID($cttId);
				$number	 = $drvCtn[0]->phn_phone_no;
			}
		}
		$this->name		 = $modelDriver->drv_name;
		$this->mobile	 = \BookingPref::getDriverNumber($model, $number);

		$this->vehicle = new \Stub\mmt\Cab();
		$this->vehicle->setData($model);
	}

}
