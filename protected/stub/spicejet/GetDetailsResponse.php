<?php

namespace Stub\spicejet;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class GetDetailsResponse
{
	public $partner;
	public $company;
	public $booking_id;

	/** @var \Stub\common\Driver $chauffeur */
	public $chauffeur;

	/** @var \Stub\common\Vehicle $vehicle */
	public $vehicle;

	/** @var booking $model */
	public function setCabDriver(\Booking $model)
	{
		$driverModel = \Drivers::model()->findByPk($model->bkgBcb->bcb_driver_id);
		$contactModel = \Contact::model()->findByPk($driverModel->drv_contact_id);
		$vehicleModel = \Vehicles::model()->findByPk($model->bkgBcb->bcb_cab_id);

		$this->partner   = 'GOZO CABS';
		$this->company   = 'spicejet';
		$this->booking_id = $model->bkg_agent_ref_code;

		
		$this->chauffeur	 = new \Stub\common\Driver();
		if ($model->bkgBcb->bcbDriver->drv_name != '')
		{
			$this->chauffeur->setSpicejetData($driverModel, $contactModel);
		}

		/* @var $obj Stub\common\Vehicle */
		$this->vehicle	 = new \Stub\common\Vehicle();
		if ($model->bkgBcb->bcbCab->vhc_number != '')
		{
			$this->vehicle->setSpicejetData($vehicleModel);
		}
	}
}
