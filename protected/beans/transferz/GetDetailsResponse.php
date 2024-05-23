<?php

namespace Beans\transferz;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class GetDetailsResponse
{
	public $phoneNumber;
	public $notifyBySms;
	public $senderName;
	public $latitude;
	public $longitude;
	public $name;

	/** @var booking $model */
	public function assignDriver(\Booking $model)
	{		
		$this->phoneNumber   = '+91'.$model->bkgBcb->bcb_driver_phone;
		$this->notifyBySms   = false;
		$this->name          = $model->bkgBcb->bcbDriver->drv_name;
	}

	/**
	 * This function is used for setting details	  
	 * @param type $model = Booking Model
	 */
	public function setTransferzPickupData($model)
	{
		$this->latitude				 = $model->lattitude;
		$this->longitude			 = $model->longitude;
	}
}
