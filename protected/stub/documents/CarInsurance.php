<?php

namespace Stub\documents;

/**
 *  @property \Stub\common\Value $expiryDate 
 *  @property \Stub\common\Value $policyNumber 
 *  @property \Stub\common\Value $company 
 *  @property \Stub\common\Documents1 $document
 */
class CarInsurance extends commonDocument
{
	/** @var  \Stub\common\Value $policyNumber */
	public $policyNumber;
	/** @var \Stub\common\Documents1 $company */
	public $company;

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
		if ($model == null)
		{
			$model = new \Vehicles();
		}
		if($vhdModel != null)
		{
			$this->document->setVehicleDocs($vhdModel);
		}
		if($this->document->status == 1)
		{
			$this->document->isRequired = false;
		}
		$this->expiryDate->value		 = $model->vhc_insurance_exp_date;
		if ($this->document->status == 1 && $this->expiryDate->value != "")
		{
			$this->expiryDate->toBeVerified	 = true;
		}

		return $this;
	}
	public function getModel($vhdModel = null)
	{
		if($vhdModel == null)
		{
			$vhdModel = new \VehicleDocs();
		}
		$this->document->getModel($vhdModel);
		
		return $vhdModel;
		
	}

}
