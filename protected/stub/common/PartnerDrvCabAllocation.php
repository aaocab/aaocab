<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PartnerDrvCabAllocation
{

	public $bookingId;
	public $bookingStatus;
	public $bookingStatusCode;
	public $driverName;
	public $driverMobile;
	public $cabNo;
	public $cabModel;
	public $otp;

	/** @var \Stub\common\Vehicle $car */
	public $car;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/**
	 * This function is used for setting booking details	  
	 * @param type $model = Booking Model
	 */
	public function setData($model)
	{
		$this->bookingId		 = $model->bookingId;
		$this->bookingStatus	 = $model->bookingStatus;
		$this->bookingStatusCode = $model->bookingStatusCode;
		$obj				 = new \Stub\common\PartnerDrvCabAllocation();
		$obj->setDriverData($model);
		$this->driverdata	 = $obj;
	}
	public function setDriverData($model)
	{
		$this->driverName	 = $model->driverName;
		$this->driverMobile	 = $model->driverMobile;
		$this->cabNo		 = $model->cabNo;
		$this->cabModel		 = $model->cabModel;
		$this->otp			 = $model->otp;

		$driverObj	 = new \Stub\common\Driver();
		$driverData	 = [
			'drv_name'	 => $model->bkgBcb->bcbDriver->drv_name,
			'drv_phone'	 => (int) $model->bkgBcb->bcb_driver_phone
		];
		$driverData	 = \Filter::convertToObject($driverData);
		if ($model->bkgBcb->bcbDriver->drv_name != '')
		{
			$this->driver = $driverObj->setModelData($driverData, false);
		}
	}

}
