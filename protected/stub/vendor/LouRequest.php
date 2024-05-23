<?php

namespace Stub\vendor;

class LouRequest 
{
	public $id;
	public $vndId;
	public $ownerValidDate;
	public $ownerEmail;

	/** @var \Stub\common\Vehicle $cab */
	public $cab;
  
	/** @var \Stub\common\Document $proof */
	public $proof;
	public $owner;



	public function getModel(\VendorVehicle $model = null)
	{
		$model->vvhc_vhc_owner_auth_valid_date	 = $this->ownerValidDate;
		$model->vvhc_id							 = $this->id;
		$model->vvhc_vnd_id						 = $this->vndId;
		$model->vvhc_vhc_id						 = $this->cab->id;
		$model->vvhc_vhc_owner					 = $this->owner;
		$cabModel								 = \Vehicles::model()->findByPk($this->cab->id);
		$model->vvhcVhc							 = $this->cab->setModel($cabModel);
		$this->owner					 = \ContactEmail::model()->findByEmailAddress($this->ownerEmail)->eml_contact_id;
		$model->vvhc_owner_contact_id	 = $this->owner;
		$model->vvhc_owner_proof = $this->proof;
		return $model;
	}

}
