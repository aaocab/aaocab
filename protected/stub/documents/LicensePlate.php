<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\documents;

/**
 * @property \Stub\common\Value $frontLicense
 * @property \Stub\common\Value $rearLicense 
 * @property \Stub\common\Documents1 $document
 */
class LicensePlate
{
	public $frontLicense;
	public $rearLicense;
	public $document;

	public function setData($model,$docData)
	{
		if($model = null)
		{
			$model = new \Vehicles();
		}
		$this->document->type = \Stub\common\Documents1::TYPE_LICENSE_PLATE;
		$this->document->expiryDateTime = null ;
		$this->document->frontPath = $docData['vhc_front_plate'];
		$this->document->backPath = $docData['vhc_rear_plate'];
		$this->document->status = (int) ($docData['vhc_front_plate_status']== 1 && $docData['vhc_rear_plate_status']== 1)? 1: 0;
		$this->frontLicense->value = $model->vhc_number ;
		$this->rearLicense->value = $model->vhc_number ;
		if($docData['vhc_front_plate'] != '' && $docData['vhc_front_plate_status'] == 1)
		{
			$this->frontLicense->isRequired =  false;
			$this->frontLicense->toBeVerified = true;
		}
		else
		{
			$this->frontLicense->isRequired  = true;
			$this->frontLicense->toBeVerified = false;
		}
		if($docData['vhc_rear_plate'] != '' && $docData['vhc_rear_plate_status'] == 1)
		{
			$this->rearLicense->isRequired =  false;
			$this->rearLicense->toBeVerified = true;
		}
		else
		{
			$this->rearLicense->isRequired  = true;
			$this->rearLicense->toBeVerified = false;
		}
		
		return $this;
	}
}
