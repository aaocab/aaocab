<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\documents;

/**
 * @property \Stub\common\Value $expiryDate
 * @property \Stub\common\Value $registrationNo
 * @property \Stub\common\Person $owner
 * @property \Stub\common\Documents1[] $documents
 * @author Subhradip
 */
class RegistrationCertificate extends commonDocument
{
	/** @var \Stub\common\Value $expiryDate */
	public $expiryDate;
	/** @var \Stub\common\Value $registrationNo */
	public $registrationNo;
	/** @var \Stub\common\Person $owner */
	public $owner;
	/** @var \Stub\common\Documents1[] $documents */
	public $documents = array();

	public function __construct()
	{
		parent::__construct();
		$this->expiryDate->isRequired = true;
		$this->expiryDate->toBeVerified = false;
		unset($this->document);
	}

	/** 
	 * @param \VehicleDocs $vhdFront
	 * @param \VehicleDocs $vhdRear
	 * @param \Vehicle $model
	 */
	public function setData($model, $vhdFront, $vhdRear)
	{
		if ($model == null)
		{
			$model = new \Vehicles();
		}

		if ($vhdFront != null)
		{
			$frontRCDoc				 = new \Stub\common\Documents1();
			$frontRCDoc->type		 = (int) \VehicleDocs::TYPE_RC_FRONT;
			$frontRCDoc->isRequired	 = true;
			$this->documents[0]		 = $frontRCDoc;
			$this->documents[0]->setVehicleDocs($vhdFront);
			if($this->documents[0]->status == 1)
			{
				$this->documents[1]->isRequired = false;
			}
		}

		if ($vhdRear != null)
		{
			$rearRCDoc				 = new \Stub\common\Documents1();
			$rearRCDoc->type		 = (int) \VehicleDocs::TYPE_RC_REAR;
			$rearRCDoc->isRequired	 = true;
			$this->documents[1]		 = $rearRCDoc;
			$this->documents[1]->setVehicleDocs($vhdRear);
			if($this->documents[1]->status == 1)
			{
				$this->documents[1]->isRequired = false;
			}
		}
		$this->expiryDate->value		 = $model->vhc_reg_exp_date;
		if ($this->documents[0]->status == 1 && $this->documents[1]->status == 1)
		{
			$this->expiryDate->toBeVerified	 = true;
		}
		if ($model->vhc_owner_contact_id == NULL || $model->vhc_owner_contact_id == 0)
		{
			$this->owner->firstName	 = $model->vhc_reg_owner;
			$this->owner->lastName	 = $model->vhc_reg_owner_lname;
		}
		else
		{
			$contactModel	 = \Contact::model()->findByPk($model->vhc_owner_contact_id);
			$this->owner	 = new \Stub\common\Person();
			$this->owner->setPersonData($contactModel, NULL);
		}

		return $this;
	}

}
