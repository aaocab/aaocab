<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\documents;

/**
 * @property \Stub\common\Value $expiryDate
 * @property \Stub\common\Documents1 $document
 */
class CarFitnessCertificate extends commonDocument
{
	/** @var \Stub\common\Value $expiryDate */
	public $expiryDate;
	/** @var \Stub\common\Documents1 $document */
	public $document;


	public function __construct()
	{
		parent::__construct();
		$this->expiryDate->isRequired = true;
		$this->expiryDate->toBeVerified = false;

		$this->document->type	 = \VehicleDocs::TYPE_INSURANCE;
		$this->document->isRequired = true;
	}
	/** 
	 * @param \VehicleDocs $vhdModel
	 * @param \Vehicle $model
	 */
	public function setData($model, $vhdModel)
	{
		if($model == null)
		{
			$model = new \Vehicles();
		}
		if($vhdModel!= null)
		{
			$this->document->setVehicleDocs($vhdModel);
		}
		if($this->document->status == 1)
		{
			$this->document->isRequired = false;
		}
		$this->expiryDate->value = $model->vhc_fitness_cert_end_date;
		if($this->document->status == 1 && $this->expiryDate->value != "")
		{
			$this->expiryDate->toBeVerified = true;
		}
				
		return $this;
	}
}
