<?php

namespace Stub\common;

class VendorVehicle
{

	public $id;
	public $vndId;
	public $code;
	public $ownerValidDate;
	public $isDigital, $digitalFlag;
	public $active;

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\Vehicle $vehicle */
	public $vehicle;

	/** @var \Stub\common\Vendor $vendor */
	public $vendor;

	public function getModel($model = null)
	{

		$userInfo = \UserInfo::getInstance();
		if (!empty($this->id))
		{
			$model = \VendorVehicle::model()->findByPk($this->id);
		}
		if ($model == null)
		{

			$model = \VendorVehicle::getNewInstance();
		}



		$model->vvhc_id							 = $this->id;
		$model->vvhc_vhc_owner_auth_valid_date	 = $this->ownerValidDate;

		$model->vvhcVhc = $this->vehicle->getModelData($this->vehicle);

		$model->vvhc_vhc_id				 = $model->vvhcVhc->vhc_id;
		$model->vvhc_vnd_id				 = $userInfo->getEntityId();
		$model->vvhc_owner_contact_id	 = $model->vvhcVhc->vhc_owner_contact_id;

		$model->vvhc_owner_license_id = \Contact::model()->findByPk($model->vvhcVhc->vhc_owner_contact_id)->ctt_license_doc_id;

		$model->vvhc_owner_pan_id = \Contact::model()->findByPk($model->vvhcVhc->vhc_owner_contact_id)->ctt_pan_doc_id;

		$model->vvhc_lou_approved = 0; //pending

		return $model;
	}

	public function setModel($id = 0)
	{
		if ($id > 0)
		{
			$model = \VendorVehicle::model()->findByPk($id);
		}
		else
		{
			$model = new \Stub\common\VendorVehicle();
		}

		$this->id = (int) $model->vvhc_id;
//		$this->isDigitalAgree	 = (int)$model->vvhc_digital_is_agree;
//		$this->vndID			 = (int)$model->vvhc_vnd_id;
//		$this->vvhcActive		 = (int)$model->vvhc_active;
//		$this->digitalFlag = (int)$model->vvhc_active;;

		return $this;
	}

	public function setModelData($vndID, $vehicleId)
	{

		$model = \VendorVehicle::model()->findUndertakingByVndVhcId($vehicleId, $vndID);
		if ($model == null)
		{
			$model = new \VendorVehicle();
		}
		$vehicle		 = new \Stub\common\Vehicle();
		$vhcmodel		 = \Vehicles::model()->findByPk($vehicleId);
		$vehicle->setModelData($vhcmodel);
		$this->vehicle	 = $vehicle;

		$vendor			 = new \Stub\common\Vendor();
		$vendor->setModelData($vndID);
		$this->vendor	 = $vendor;

		$this->ownerValidDate	 = $model['vvhc_vhc_owner_auth_valid_date'];
		$this->id				 = (int) $model['vvhc_id'];
	}

}
