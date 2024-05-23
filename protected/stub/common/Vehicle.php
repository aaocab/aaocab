<?php

namespace Stub\common;

/**
 *  @property \Stub\documents\CarInsurance $insurance 
 *  @property \Stub\documents\RegistrationCertificate $registrationCertificate
 *  @property \Stub\common\Documents1[] $liscensePlate
 *  @property \Stub\documents\PUCCertificate $pucCertificate
 *  @property \Stub\documents\CommercialPermit $commercialPermit
 *  @property \Stub\documents\CarFitnessCertificate $fitnessCertificate 
 */
class Vehicle extends Vendor
{

	public $isOwned, $isLinked, $isAttached, $frontplate, $rearPlate, $insuranceProof,
			$id, $model,
			$number,
			$status,
			$hasCNG, $hasElecric,
			$untilDate,
			$dataList,
			$data,
			$vehicleCatId,
			$name, $totalTrips,
			$roofTop, $type, $markCount, $isFreez, $color, $registration_number, $vehicle_type,
			$year, $isApprovred, $dop, $verify_check, $documentUpload,
			$isDigitalAgree, $vndID, $vvhcActive, $digitalFlag, $vehicleContact, $isCabAllowed, $message, $isDocumentUploaded;

	/** @var \Stub\documents\CarInsurance $insurance */
	public $insurance;

	/** @var \Stub\documents\RegistrationCertificate $registrationCertificate */
	public $registrationCertificate;

	/** @var \Stub\common\Documents1[] $licensePlate */
	public $licensePlate = array();

	/** @var \Stub\documents\PUCCertificate $pucCertificate */
	public $pucCertificate;

	/** @var  \Stub\documents\CommercialPermit $commercialPermit */
	public $commercialPermit;

	/** @var \Stub\documents\CarFitnessCertificate $fitnessCertificate */
	public $fitnessCertificate;

	/** @var \Stub\common\VendorVehicle $vendorVehicle */
	public $vendorVehicle;

	/** @var \Stub\common\Document $documents */
	public $documents;

	/** @var \Stub\common\Person $owner */
	public $owner;

	/** @var \Stub\common\Cab $category */
	public $category;

	public function fillData($row, $tripId = null)
	{
		$this->id				 = (int) $row['vhc_id'];
		//$this->type		 = $row['vhc_type_id'];
		$this->number			 = $row['vhc_number'];
		$this->model			 = $row['vht_make'] . ' ' . $row['vht_model'];
		$this->hasCNG			 = (int) $row['vhc_has_cng'];
		$this->hasElectric		 = (int) $row['vhc_has_electric'];
		$this->roofTop			 = (int) $row['vhc_has_rooftop_carrier'];
		$this->markCount		 = (int) $row['vhc_mark_car_count'];
		$this->isFreez			 = (int) $row['vhc_is_freeze'];
		$this->color			 = $row['vhc_color'];
		$this->year				 = $row['vhc_year'];
		$this->isApproved		 = (int) $row['vhc_approved'];
		$this->verify_check		 = (int) $row['verify_check'];
		$this->documentUpload	 = (int) $row['documentUpload'];

		$this->isOwned		 = (int) $row['vhc_owned_or_rented'];
		$this->isAttached	 = (int) $row['vhc_is_attached'];
		if (isset($row['isLinked']))
		{
			$this->isLinked = (int) $row['isLinked'];
		}

		if ($row['vhc_has_cng'] == null)
		{
			$this->hasCNG = -1;
		}
		$this->category		 = new \Stub\common\Cab();
		$this->category->setCabType($row['vct_id']);
		$this->vendorVehicle = new \Stub\common\VendorVehicle();
		$this->vendorVehicle->setModel($row['vvhc_id']);
		if ($tripId != null)
		{
			$chkAvailabily		 = \Vehicles::checkAvialability($tripId, $this->id);
			$this->isAvailable	 = $chkAvailabily;
			$checkApplicable	 = \Vehicles::checkApplicable($tripId, $this->id);
			$this->isApplicable	 = $checkApplicable;
		}
	}

	public function getList($dataArr, $tripId = null)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Vehicle();
			$obj->fillData($row, $tripId);
			$this->dataList[]	 = $obj;
		}
	}

	public function setVehicleFillData($row)
	{
		$this->number	 = $row['vhc_number'];
		$this->model	 = $row['vht_make'] . ' ' . $row['vht_model'];
	}

	/**
	 * 
	 * @param $model
	 * @return $this
	 */
	public function setModelData($model = null)
	{
		if ($model == null)
		{
			return false;
		}

		if (!$model instanceof Booking && isset($model->bkg_id) && $model->bkg_id > 0)
		{
			$bkgModel = \Booking::model()->findByPk($model->bkg_id);
		}

		$this->id	 = (int) $model->vhc_id;
		$svcModel	 = \VehicleCategory::model()->getTypeClassbyid($model->vhc_id);
		if ($model->vhc_has_cng == 1)
		{
			$fuelType = "CNG";
		}
		else if ($model->vhc_has_electric == 1)
		{
			$fuelType = "Electric";
		}
		else
		{
			$fuelType = "Diesel";
		}
		$type			 = $fuelType . ' ' . $svcModel['vct_label'];
		$this->number	 = $model->vhc_number;
		$this->model	 = $model->vhcType->vht_make . ' ' . $model->vhcType->vht_model;
		$this->status	 = $model->vhc_approved; //	0:not verified,1:approved,2:pending_approval(verified),3:disapproved,4: Approved but Insurance Expired

		if (empty(trim($this->model)))
		{
			$this->model = $model->vht_make . ' ' . $model->vht_model;
		}

		$this->model		 = $this->model . ' ' . $type;
		$this->category->id	 = (int) $model->vhc_type_id;
		$this->year			 = $model->vhc_year;
		$this->color		 = $model->vhc_color;
		$this->hasCNG		 = (int) $model->vhc_has_cng;
		$this->hasElectric	 = (int) $model->vhc_has_electric;
		$this->roofTop		 = (int) $model->vhc_has_rooftop_carrier;
		$taxexp				 = ($model->vhc_tax_exp_date != NULL) ? $model->vhc_tax_exp_date : NULL;
		$taxexp				 = (date("Y-m-d", strtotime($model->vhc_tax_exp_date)) != '1970-01-01') ? $model->vhc_tax_exp_date : NULL;
		$this->untilDate	 = $taxexp;

		$this->isOwned			 = $model->vhc_owned_or_rented;
		$this->isAttached		 = (int) $model->vhc_is_attached;
		$this->frontplate		 = $model->vhc_front_plate;
		$this->rearPlate		 = $model->vhc_rear_plate;
		$this->insuranceProof	 = $model->vhc_insurance_proof;
		$dop					 = ($model->vhc_dop != NULL) ? $model->vhc_dop : '';
		$dop					 = (date("Y-m-d", strtotime($model->vhc_dop)) != '1970-01-01') ? $model->vhc_dop : '';
		//$this->dop				 = $dop;

		$gozoPartnerId = \Yii::app()->params['gozoChannelPartnerId'];
		if ($bkgModel->bkg_agent_id == null || $bkgModel->bkg_agent_id == $gozoPartnerId)
		{
			$this->documents = new \Stub\common\VehicleDoc();
			$this->documents->setVehicleData($this->id);
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

		//$this->category = new \Stub\common\Vehicle();
		//$this->category->setVehicleModel($model->vhc_type_id);


		return $this;
	}

	/**
	 * 
	 * @param $model, $result
	 * @return $this
	 */
	public function setStatusData($model, $result)
	{
		$this->setModelData($model);
		if ($this->documents->Insurance->status == 1 || $this->documents->Insurance->status == 0)
		{
			$count = ($count + 1);
		}
		if ($this->documents->RegistrationCertificate->status == 1 || $this->documents->RegistrationCertificate->status == 0)
		{
			$count = ($count + 1);
		}
		$this->isDocumentUploaded	 = ($count == 2) ? true : false;
		$this->isCabAllowed			 = (!$result['success']) ? 0 : 1;
		$this->message				 = (!$result['success']) ? ['msg' => $result['msg']] : '';
		return $this;
	}

	/**
	 * @param $model
	 * @return $this
	 */
	public function setModel(\Vehicles $model)
	{
		if ($model == null)
		{
			$model = new \Vehicles();
		}
		$model					 = \Vehicles::model()->findByPk($model->vhc_id);
		$this->number			 = $model->vhc_number;
		$this->owner->firstName	 = $model->vhc_reg_owner;
		$this->owner->lastName	 = $model->vhc_reg_owner_lname;
		if ($model->vhc_owner_contact_id)
		{

			$contactModel	 = \Contact::model()->findByPk($model->vhc_owner_contact_id);
			$this->owner	 = new \Stub\common\Person();
			if (!empty($contactModel))
			{
				$this->owner->setPersonData($contactModel, NULL);
			}
		}
		//$this->vendorVehicle;

		return $this;
	}

	public function fillCat($row, $key)
	{
		if ($row['vct_label'] != "")
		{
			$this->id	 = (int) $key;
			$this->type	 = str_replace(PHP_EOL, '', $row);
		}
	}

	public function setVehicleCategory($dataArr)
	{

		foreach ($dataArr as $key => $row)
		{
			$obj				 = new \Stub\common\Vehicle();
			$obj->fillCat($row, $key);
			$this->dataList[]	 = $obj;
		}
		return $this;
	}

	public function setVehicleModel($vhcTypeId)
	{
		$model		 = \Vehicles::model()->getVModel($vhcTypeId);
		$this->model = $model;
	}

	public function getModelData($modelVehicle = null)
	{

		$returnSet = new \ReturnSet();
		if ($modelVehicle == null)
		{
			$modelVehicle = new \Vehicles();
		}
		if ($this->id > 0)
		{
			$modelVehicle = \Vehicles::model()->findByPk($this->id);
			goto skipAll;
		}
		// Update Only in case of adding cab
		//$modelVehicle->vhc_id					 = $this->id;
		$modelVehicle->vhc_reg_owner			 = $this->owner->firstName;
		$modelVehicle->vhc_reg_owner_lname		 = $this->owner->lastName;
		$modelVehicle->vhc_number				 = $this->number;
		$modelVehicle->vhc_has_cng				 = $this->hasCNG;
		$modelVehicle->vhc_has_rooftop_carrier	 = $this->roofTop;
		$modelVehicle->vhc_year					 = $this->year;
		$modelVehicle->vhc_color				 = $this->color;
		if ($this->category->id)
		{
			$modelVehicle->vhc_type_id = $this->category->id;
		}
		skipAll:
		// Update in case of both adding and updating info of cab
		$modelVehicle->vhc_tax_exp_date		 = $this->untilDate;
		$modelVehicle->vhc_owned_or_rented	 = $this->isOwned;
		//$modelVehicle->vhc_dop				 = $this->type;
		$modelVehicle->vhc_owner_contact_id	 = $this->owner->id;

		$cModel = $this->owner->init();

		$contactId = $cModel->processData();

		$modelVehicle->vhc_owner_contact_id = $contactId; //$cModel;//$contactId;//$this->owner->id;

		return $modelVehicle;
	}

	/**
	 * @param \Vehicles $vhcModel
	 *  
	 */
	public function init($vhcModel = null)
	{
		if ($vhcModel == null)
		{
			return false;
		}

		$this->id		 = (int) $vhcModel->vhc_id;
		$this->number	 = $vhcModel->vhc_number;
		$this->model	 = $vhcModel->vhcType->vht_make . ' ' . $vhcModel->vhcType->vht_model;

		$this->category->id	 = (int) $vhcModel->vhc_type_id;
		$this->year			 = (int) $vhcModel->vhc_year;
		$this->color		 = $vhcModel->vhc_color;
		$this->hasCNG		 = (int) $vhcModel->vhc_has_cng;
		$this->roofTop		 = (int) $vhcModel->vhc_has_rooftop_carrier;

		$taxexp	 = ($vhcModel->vhc_tax_exp_date != NULL) ? $vhcModel->vhc_tax_exp_date : NULL;
		$taxexp	 = (date("Y-m-d", strtotime($vhcModel->vhc_tax_exp_date)) != '1970-01-01') ? $vhcModel->vhc_tax_exp_date : NULL;

		$this->untilDate		 = $taxexp;
		$this->isOwned			 = (int) $vhcModel->vhc_owned_or_rented;
		$this->isAttached		 = (int) $vhcModel->vhc_is_attached;
		$this->frontplate		 = $vhcModel->vhc_front_plate;
		$this->rearPlate		 = $vhcModel->vhc_rear_plate;
		$this->insuranceProof	 = $vhcModel->vhc_insurance_proof;
		$dop					 = ($vhcModel->vhc_dop != NULL) ? $vhcModel->vhc_dop : '';
		$dop					 = (date("Y-m-d", strtotime($vhcModel->vhc_dop)) != '1970-01-01') ? $vhcModel->vhc_dop : '';

		$docData = \VehicleDocs::model()->getByVehicleId($vhcModel->vhc_id);

		// Insurance Doc
		$this->insurance = new \Stub\documents\CarInsurance();
		$this->insurance->setData($vhcModel, $docData[\VehicleDocs::TYPE_INSURANCE]);

		//RC Doc
		$this->registrationCertificate = new \Stub\documents\RegistrationCertificate();
		$this->registrationCertificate->setData($vhcModel, $docData[\VehicleDocs::TYPE_RC_FRONT], $docData[\VehicleDocs::TYPE_RC_REAR]);

		//License Plate
		$frontPlateDoc				 = new Documents1();
		$frontPlateDoc->type		 = (int) \VehicleDocs::TYPE_LICENSE_FRONT;
		$frontPlateDoc->isRequired	 = true;
		$this->licensePlate[0]		 = $frontPlateDoc;
		$this->licensePlate[0]->setVehicleDocs($docData[\VehicleDocs::TYPE_LICENSE_FRONT]);

		$backPlateDoc				 = new Documents1();
		$backPlateDoc->type			 = (int) \VehicleDocs::TYPE_LICENSE_BACK;
		$backPlateDoc->isRequired	 = true;
		$this->licensePlate[1]		 = $backPlateDoc;
		$this->licensePlate[1]->setVehicleDocs($docData[\VehicleDocs::TYPE_LICENSE_BACK]);

//		//PUC certificate
		$this->pucCertificate		 = new \Stub\documents\PUCCertificate();
		$this->pucCertificate->setData($vhcModel, $docData[\VehicleDocs::TYPE_PUC]);
//
//		//Commercial Permit
		$this->commercialPermit		 = new \Stub\documents\CommercialPermit();
		$this->commercialPermit->setData($vhcModel, $docData[\VehicleDocs::TYPE_COMERCIAL_PERMIT]);
//
//		//Fitness Certificate
		$this->fitnessCertificate	 = new \Stub\documents\CarFitnessCertificate();
		$this->fitnessCertificate->setData($vhcModel, $docData[\VehicleDocs::TYPE_FITNESS_CERTIFICATE]);

		return $this;
	}

	/**
	 * @param \Vehicles $modelVehicle
	 *  
	 */
	public function getNewModel($modelVehicle = null)
	{
		if ($modelVehicle == null)
		{
			$modelVehicle = new \Vehicles();
		}
		if ($this->id > 0)
		{
			$modelVehicle = \Vehicles::model()->findByPk($this->id);
			goto skipAll;
		}
		// Only In the case when we are adding a new vehicle;
		$modelVehicle->vhc_has_cng				 = $this->hasCNG;
		$modelVehicle->vhc_number				 = $this->number;
		$modelVehicle->vhc_has_rooftop_carrier	 = $this->roofTop;
		$modelVehicle->vhc_year					 = $this->year;
		$modelVehicle->vhc_color				 = $this->color;
		$modelVehicle->vhc_reg_owner			 = $this->registrationCertificate->owner->firstName;
		$modelVehicle->vhc_reg_owner_lname		 = $this->registrationCertificate->owner->lastName;
		if ($this->category->id)
		{
			$modelVehicle->vhc_type_id = $this->category->id;
		}
		skipAll:
		// In case of both adding and updating vehicles
		$modelVehicle->vhc_tax_exp_date		 = $this->untilDate;
		$modelVehicle->vhc_owned_or_rented	 = $this->isOwned;
		$modelVehicle->vhc_owner_contact_id	 = $this->registrationCertificate->owner->id;

		$vdocs = [];

		$modelVehicle->vhc_insurance_exp_date	 = $this->insurance->expiryDate->value;
		$insuranceModel							 = $this->insurance->document->getVhdModel();
		$vdocs[$insuranceModel->vhd_type]		 = $insuranceModel;

		$modelVehicle->vhc_pollution_exp_date	 = $this->pucCertificate->expiryDate->value;
		$pucModel								 = $this->pucCertificate->document->getVhdModel();
		$vdocs[$pucModel->vhd_type]				 = $pucModel;

		$modelVehicle->vhc_commercial_exp_date	 = $this->commercialPermit->expiryDate->value;
		$commercialModel						 = $this->commercialPermit->document->getVhdModel();
		$vdocs[$commercialModel->vhd_type]		 = $commercialModel;

		$modelVehicle->vhc_fitness_cert_end_date = $this->fitnessCertificate->expiryDate->value;
		$fitnessModel							 = $this->fitnessCertificate->document->getVhdModel();
		$vdocs[$fitnessModel->vhd_type]			 = $fitnessModel;

		$modelVehicle->vhc_reg_exp_date	 = $this->registrationCertificate->expiryDate->value;
		$rcFrontModel					 = $this->registrationCertificate->documents[0]->getVhdModel();
		$vdocs[$rcFrontModel->vhd_type]	 = $rcFrontModel;

		$rcRearModel					 = $this->registrationCertificate->documents[1]->getVhdModel();
		$vdocs[$rcRearModel->vhd_type]	 = $rcRearModel;

		$licenseFrontModel					 = $this->licensePlate[0]->getVhdModel();
		$vdocs[$licenseFrontModel->vhd_type] = $licenseFrontModel;

		$licenseRearModel					 = $this->licensePlate[1]->getVhdModel();
		$vdocs[$licenseRearModel->vhd_type]	 = $licenseRearModel;

		$modelVehicle->vehicleDocs	 = $vdocs;
		$cModel						 = $this->registrationCertificate->owner->init();
		$contactId					 = $cModel->processData();

		$modelVehicle->vhc_owner_contact_id = $contactId;

		return $modelVehicle;
	}

	/**
	 * 
	 * @param $model
	 * @return $this
	 */
	public function setSpicejetData($model = null)
	{
		if ($model == null)
		{
			return false;
		}
		$this->id					 = (int) $model->vhc_id;
		$this->name					 = $model->vhcType->vht_make . ' ' . $model->vhcType->vht_model;
		$this->color				 = $model->vhc_color;
		$this->registration_number	 = $model->vhc_number;
		$svcModel					 = \VehicleCategory::model()->getTypeClassbyid($model->vhc_id);
		$this->vehicle_type			 = $svcModel['vct_label'];
		return $this;
	}

	public function setInfo($model)
	{
		if ($model == null)
		{
			return false;
		}
		$this->number	 = $model->vhc_number;
		$this->model	 = $model->vhcType->vht_make . ' ' . $model->vhcType->vht_model;
		$this->color	 = $model->vhc_color;
		$this->hasCNG	 = (int) $model->vhc_has_cng;
		$this->setVehicleModel($model->vhc_type_id);
	}

	public function linkingData($row)
	{
		$this->id				 = (int) $row['vhc_id'];
		$this->number			 = $row['vhc_number'];
		$this->owner->firstName	 = $row['vhc_reg_owner'];
		$this->owner->lastName	 = $row['vhc_reg_owner_lname'];
		$this->model			 = $row['vht_make'] . ' ' . $row['vht_model'];
		$this->hasCNG			 = (int) $row['vhc_has_cng'];
		$this->isFreez			 = (int) $row['vhc_is_freeze'];
		$this->isApproved		 = (int) $row['vhc_approved'];

		$this->isOwned		 = (int) $row['vhc_owned_or_rented'];
		$this->isAttached	 = (int) $row['vhc_is_attached'];
		if (isset($row['isLOURequired']))
		{
			$this->isLOURequired = $row['isLOURequired'];
		}
		if (isset($row['isLinked']))
		{
			$this->isLinked = (int) $row['isLinked'];
		}

		if ($row['vhc_has_cng'] == null)
		{
			$this->hasCNG = -1;
		}
	}
}
