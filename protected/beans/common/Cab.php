<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cab
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $code
 * @property \Beans\common\CabCategory $category
 * @property string $tiers
 * @property string $number
 * @property string $color
 * @property string $images
 * @property string $fuelType
 * @property integer $seatingCapacity
 * @property integer $smallBagCapacity
 * @property integer $bigBagCapacity
 * @property integer $manufacturingYear
 * @property integer $hasCarrier
 * @property string $fitnessExpiry
 * @property string $insuranceExpiry
 * @property string $pucExpiry
 * @property string $commercialExpiry
 * @property string $registrationCertificateExpiry
 * @property string $taxExpiry 
 * @property integer $hasAC	
 * @property integer $hasCng
 * @property \Beans\contact\Person $owner 
 * @property \Beans\common\Document[] $documents
 * @property \Beans\cab\Model $cabModel
 * @property \Beans\common\ValueObject $status
 */

namespace Beans\common;

class Cab
{

	public $id;
	public $code;

	/** @var \Beans\common\CabCategory $category */
	public $category;
	public $tiers;
	public $number;
	public $color;
	public $images;
	public $desc;
	public $fuelType;
	public $seatingCapacity;
	public $smallBagCapacity;
	public $bigBagCapacity;
	public $manufacturingYear;
	public $hasCarrier;
	public $fitnessExpiry, $insuranceExpiry, $pucExpiry, $taxExpiry,
			$commercialExpiry, $registrationCertificateExpiry;
	public $hasAC;

	/** @var \Beans\cab\Model $cabModel */
	public $cabModel;

	/** @var \Beans\common\Document[]  $documents */
	public $documents;
	public $isBadVehicle;
	public $isFreeze;
	public $year;
	public $hasCng;
	public $isAttached;
	public $isOwner;
	public $owner;

	/** @var \Beans\contact\Person $owner */
	public $isLOURequired;

	/** @var \Beans\cab\LOU $LOU */
	public $LOU;
	public $hasRooftopCarrier;
	public $isApproved;
	public $dateOfPurchase;
	public $hasSignedDigitalAgree;
	public $isDocumentUploaded;
	public $rating;

	/** \Beans\common\ValueObject $status */
	public $status;

	public static function setData($data)
	{
		$obj					 = new Cab();
		$obj->seatingCapacity	 = (int) $data->seatingCapacity;
		$obj->smallBagCapacity	 = (int) $data->bagCapacity;
		$obj->bigBagCapacity	 = (int) $data->bigBagCapacity;
		$obj->category			 = \Beans\common\CabCategory::setData($data);
		return $obj;
	}

	/** @var \Vehicles $vhcModel */
	public static function setDataByCabModel($vhcModel,$bcbModel=null)
	{
		$obj		 = new Cab();
		$obj->id	 = (int) $vhcModel->vhc_id;
		$obj->code	 = $vhcModel->vhc_code;
		$obj->tiers	 = $vhcModel->vhc_is_allowed_tier;
		$obj->number = $vhcModel->vhc_number;
		$obj->hasCng = $vhcModel->vhc_has_cng;
		$obj->color	 = $vhcModel->vhc_color;

		if($vhcModel->vhcType->vht_image != '')
		{
			$basePath	 = \Yii::app()->params['fullAPIBaseURL'];
			$obj->images = $basePath . $vhcModel->vhcType->vht_image;
		}
		$obj->fuelType						 = $vhcModel->vhcType->vht_fuel_type;
		$obj->seatingCapacity				 = (int) $vhcModel->vhcType->vht_capacity;
		$obj->smallBagCapacity				 = (int) $vhcModel->vhcType->vht_bag_capacity;
		$obj->bigBagCapacity				 = (int) $vhcModel->vhcType->vht_big_bag_capacity;
		$obj->manufacturingYear				 = (int) $vhcModel->vhc_year;
		$obj->hasCarrier					 = (int) $vhcModel->vhc_has_rooftop_carrier | 0;
		$obj->fitnessExpiry					 = $vhcModel->vhc_fitness_cert_end_date;
		$obj->pucExpiry						 = $vhcModel->vhc_pollution_exp_date;
		$obj->insuranceExpiry				 = $vhcModel->vhc_insurance_exp_date;
		$obj->commercialExpiry				 = $vhcModel->vhc_commercial_exp_date;
		$obj->taxExpiry						 = $vhcModel->vhc_tax_exp_date;
		$obj->registrationCertificateExpiry	 = $vhcModel->vhc_reg_exp_date;
		$obj->hasAC							 = 1;

		$obj->category = \Beans\common\CabCategory::setByVhtModel($vhcModel->vhcType,true,$bcbModel);

		return $obj;
	}

	/**
	 * 
	 * @param type $dataArr
	 * @return \Beans\common\Cab
	 */
	public static function getList($dataArr)
	{
		foreach($dataArr as $row)
		{
			$obj		 = new Cab();
			$row		 = (is_array($row)) ? \Filter::convertToObject($row) : $row;
			$obj->fillData($row);
			$dataList[]	 = $obj;
		}
		return $dataList;
	}

	/**
	 * 
	 * @param type $data
	 */
	public function fillData($data)
	{
		$this->id				 = (int) $data->vhc_id;
		$this->number			 = strtoupper(str_replace('  ', ' ', trim($data->vhc_number)));
		$this->color			 = $data->vhc_color;
		$this->category			 = \Beans\common\CabCategory::setByDataModel($data);
		$this->isBadVehicle		 = (int) $data->vhc_mark_car_count;
		$this->isFreeze			 = (int) $data->vhc_is_freeze;
		$this->year				 = (int) $data->vhc_year;
		$this->hasCng			 = (int) $data->vhc_has_cng;
		$this->hasRooftopCarrier = (int) $data->vhc_has_rooftop_carrier;
		$this->isApproved		 = (int) $data->vhc_approved;
		if($data->vhc_dop != '' && date('Y-m-d', strtotime($data->vhc_dop)) != '1970-01-01')
		{
			$this->dateOfPurchase = date('Y-m-d', strtotime($data->vhc_dop));
		}
		$this->hasSignedDigitalAgree = (int) $data->vvhc_digital_is_agree;
		$this->isDocumentUploaded	 = (int) $data->documentUpload;
	}

	/**
	 * 
	 * @param \Vehicles $model
	 * @return \Beans\common\Cab
	 */
	public static function setByModel(\Vehicles $model, $vndId = 0, $linkWithVnd = false)
	{
		$obj				 = new Cab();
		$obj->id			 = (int) $model->vhc_id;
		$obj->code			 = $model->vhc_code;
		$obj->number		 = strtoupper($model->vhc_number);
		$obj->color			 = $model->vhc_color;
		$obj->category		 = \Beans\common\CabCategory::setByVhtModel($model->vhcType, false);
		$obj->cabModel		 = \Beans\cab\Model::setData($model->vhcType);
		$statusLabelList	 = \Vehicles::approveStatusList;
		$approveStatus		 = (int) $model->vhc_approved;
		$statusLabel		 = $statusLabelList[$approveStatus];
		$obj->status		 = \Beans\common\ValueObject::setIdlabel($approveStatus, $statusLabel);
		$obj->isBadVehicle	 = (int) $model->vhc_mark_car_count;
		$obj->isFreeze		 = (int) $model->vhc_is_freeze;
		$obj->year			 = (int) $model->vhc_year;
		$obj->hasCng		 = (int) $model->vhc_has_cng;
		$obj->isAttached	 = (int) $model->vhc_is_attached;

		$obj->owner = \Beans\contact\Person::setFirstLastName($model->vhc_reg_owner, $model->vhc_reg_owner_lname);
		if($vndId > 0)
		{
			$vendorVehicleModel = \VendorVehicle::model()->findByVndVhcId($vndId, $model->vhc_id);
		}
		$obj->hasRooftopCarrier	 = (int) $model->vhc_has_rooftop_carrier;
		$obj->isApproved		 = (int) $model->vhc_approved;
		if($model->vhc_dop != '' && date('Y-m-d', strtotime($model->vhc_dop)) != '1970-01-01')
		{
			$obj->dateOfPurchase = date('Y-m-d', strtotime($model->vhc_dop));
		}
		if($model->vhcType->vht_image != '')
		{
			$basePath	 = \Yii::app()->params['fullAPIBaseURL'];
			$obj->images = $basePath . '/' . $model->vhcType->vht_image;
		}
		$obj->fuelType						 = $model->vhcType->vht_fuel_type;
		$obj->seatingCapacity				 = (int) $model->vhcType->vht_capacity;
		$obj->smallBagCapacity				 = (int) $model->vhcType->vht_bag_capacity;
		$obj->bigBagCapacity				 = (int) $model->vhcType->vht_big_bag_capacity;
		$obj->manufacturingYear				 = (int) $model->vhc_year;
		$obj->hasCarrier					 = (int) $model->vhc_has_rooftop_carrier | 0;
		$obj->fitnessExpiry					 = $model->vhc_fitness_cert_end_date;
		$obj->pucExpiry						 = $model->vhc_pollution_exp_date;
		$obj->registrationCertificateExpiry	 = $model->vhc_reg_exp_date;
		$obj->taxExpiry						 = $model->vhc_tax_exp_date;
		$obj->insuranceExpiry				 = $model->vhc_insurance_exp_date;
		$obj->commercialExpiry				 = $model->vhc_commercial_exp_date;
		$obj->documents						 = \Beans\common\Document::setCabDoc($model->vhc_id);
		$obj->isLOURequired					 = 0;
		$obj->isOwner						 = (int) $model->vhc_owned_or_rented;

		if($vendorVehicleModel)
		{
			if(($obj->isOwner != 1) || ($obj->isOwner == 1 && !$linkWithVnd))
			{
				$vendorVehicleModel->vvhc_is_lou_required	 = 1;
				$obj->isLOURequired							 = 1;
			}
			$obj->isLOURequired	 = (int) $vendorVehicleModel->vvhc_is_lou_required;
			$obj->LOU			 = new \Beans\cab\LOU();
			$obj->LOU->setDataByModel($vendorVehicleModel);
		}
		return $obj;
	}

	/**
	 * 
	 * @param type $data
	 */
	public function setDataValue($data)
	{
		$this->name = $data->scv_label;
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function setCabId($id)
	{
		$obj	 = new Cab();
		$obj->id = (int) $id;
		return \Filter::removeNull($obj);
	}

	/**
	 * 
	 * @param type $cttid
	 * @param type $vehicleTypeId
	 * @return type
	 */
	public static function setPrefferedByContact($cttid, $vehicleTypeId)
	{
		
		$obj	 = new Cab();
		$cabId	 = \Vehicles::getPrefferedByContact($cttid, $vehicleTypeId);
		if($cabId)
		{
			$vhcModel	 = \Vehicles::model()->findByPk($cabId);
			$obj		 = Cab::setDataByCabModel($vhcModel);
		}
		return $obj;
	}
	public static function setcab($cabId,$bcbModel=null)
	{
		
		$obj	 = new Cab();
		
		if($cabId)
		{
			$vhcModel	 = \Vehicles::model()->findByPk($cabId);
			$obj		 = Cab::setDataByCabModel($vhcModel,$bcbModel);
		}
		return $obj;
	}

	/**
	 * 
	 * @param type $data
	 * @return \Beans\common\Cab
	 */
	public static function setRequestedCab($data)
	{
		$obj				 = new Cab;
		$obj->cabCategory	 = \Beans\common\CabCategory::setData($data);
		return $obj;
	}

	/** @var \Vehicles $model */

	/**
	 * 
	 * @return \Vehicles
	 */
	public function setModel()
	{
		if($model == null)
		{
			$model = new \Vehicles();
		}
		if($this->id > 0)
		{
			$model = \Vehicles::model()->findByPk($this->id);
		}
		$model->vhc_number					 = $this->number;
		$model->vhc_color					 = $this->color;
		$model->vhc_has_cng					 = (int) $this->vhc_has_cng;
		$model->vhc_has_rooftop_carrier		 = (int) $this->vhc_has_rooftop_carrier | 0;
		$model->vhc_fitness_cert_end_date	 = $this->fitnessExpiry;
		$model->vhc_pollution_certificate	 = $this->pucExpiry;
		$model->vhc_year					 = $this->manufacturingYear;
//		$model->vhc_owned_or_rented			 = 1;
		$vehicleTypeId						 = \VehicleTypes::getModelTypeByMake($this->category->allowedModels[0]->model);
		$model->vhc_type_id					 = ($vehicleTypeId['vht_id'] != null) ? $vehicleTypeId['vht_id'] : \Config::get('vehicle.genric.model.id');
		return $model;
	}

	/**
	 * 
	 * @param type $data
	 * @param type $systemChkSum
	 */
	public function setDocData($data, $systemChkSum)
	{
		$this->cabNumber = $data->cabId;
		$this->bookingId = (int) $data->bkgId;
		$this->type		 = $data->type;
		$this->chkSum	 = $systemChkSum;
	}

	/**
	 * 
	 * @return \Beans\common\Cab
	 */
	public function setCabNumber()
	{
		$obj		 = new Cab;
		$obj->number = $this->number;
		return $obj;
	}

	/**
	 * 
	 * @param \Vehicles $model
	 * @return \Vehicles
	 */
	public function setNumberForModel(\Vehicles $model = null)
	{
		if($model == null)
		{
			$model = new \Vehicles();
		}
		$model->vhc_number = $this->number;
		if($this->id > 0)
		{
			$model->vhc_id = $this->id;
		}
		return $model;
	}

	/**
	 * 
	 * @param \Vehicles $model
	 * @return \Vehicles
	 */
	public function setInfoDataForModel(\Vehicles $model = null)
	{
		if($model == null)
		{
			$model = new \Vehicles();
		}
		if($this->id > 0)
		{
			$model->vhc_id = $this->id;
		}
		$model->vhc_number		 = strtoupper(str_replace('  ', ' ', trim($this->number)));
		$model->vhc_year		 = $this->year;
		$model->vhc_color		 = $this->color;
		$model->vhc_type_id		 = $this->cabModel->id;
		$model->vhc_description	 = $this->desc;
		if($this->dateOfPurchase != '')
		{
			$model->vhc_dop = $this->dateOfPurchase . " 00:00:00";
		}
		$model->vhc_owned_or_rented = 1;
		if($this->isOwner != '')
		{
			$model->vhc_owned_or_rented	 = $this->isOwner;
			$model->vhc_reg_owner		 = $this->owner->firstName;
			$model->vhc_reg_owner_lname	 = $this->owner->lastName;
		}
		return $model;
	}

	/**
	 * 
	 * @param array $data
	 * @return \Beans\common\Cab
	 */
	public static function setDataForOffer($data)
	{
		$initialRating	 = 5;
		$obj			 = new \Beans\common\Cab();
		$obj->id		 = (int) $data['vhc_id'];
		$obj->number	 = $data['vhc_number'];
		$obj->rating	 = (int) ($data['vhc_overall_rating'] > 0) ? $data['vhc_overall_rating'] : $initialRating;
		$obj->cabModel	 = \Beans\cab\Model::setData($data);

		return $obj;
	}

	/**
	 * 
	 * @param \Vehicles $model
	 * @return bool|\Vehicles
	 */
	public function setModelForUpdate(\Vehicles $model = null)
	{
		if($model == null && $this->id > 0)
		{
			$model = \Vehicles::model()->findByPk($this->id);
		}
		if(!$model)
		{
			return false;
		}
		$model->vhc_has_cng					 = $this->hasCng;
		$model->vhc_has_rooftop_carrier		 = $this->hasRooftopCarrier;
		$model->vhc_color					 = $this->color;
		$model->vhc_insurance_exp_date		 = $this->insuranceExpiry;
		$model->vhc_pollution_exp_date		 = $this->pucExpiry;
		$model->vhc_fitness_cert_end_date	 = $this->fitnessExpiry;
		$model->vhc_tax_exp_date			 = $this->taxExpiry;
		$model->vhc_commercial_exp_date		 = $this->commercialExpiry;
		$model->vhc_reg_exp_date			 = $this->registrationCertificateExpiry;
		if($this->dateOfPurchase != '')
		{
			$model->vhc_dop = $this->dateOfPurchase . " 00:00:00";
		}
		if($this->isOwner != '')
		{
			$model->vhc_owned_or_rented	 = $this->isOwner;
			$model->vhc_reg_owner		 = $this->owner->firstName;
			$model->vhc_reg_owner_lname	 = $this->owner->lastName;
		}
		return $model;
	}

	public function setLOUData($vhcModel, $vndId, $linkId = null)
	{
		/** @var \VendorVehicle $model */
		if(!empty($linkId))
		{
			$model = \VendorVehicle::model()->findByPk($linkId);
		}
		if($model == null)
		{
			$model = \VendorVehicle::getNewInstance();
		}

		$model->vvhc_vhc_owner_auth_valid_date	 = $this->LOU->validTill;
		$model->vvhcVhc							 = $vhcModel;

		$model->vvhc_vhc_id	 = $model->vvhcVhc->vhc_id;
		$model->vvhc_vnd_id	 = $vndId;
		if($this->owner->id > 0)
		{
			$model->vvhc_owner_contact_id	 = $this->owner->id;
			$model->vvhc_owner_license_id	 = \Contact::model()->findByPk($this->owner->id)->ctt_license_doc_id;
			$model->vvhc_owner_pan_id		 = \Contact::model()->findByPk($this->owner->id)->ctt_pan_doc_id;
		}
		$model->vvhc_vhc_owner = $this->owner->setFullName();

		$model->vvhc_lou_approved	 = 0;
		$model->vvhc_active			 = 1;
		if(!$model->vvhc_lou_created_date)
		{
			$model->vvhc_lou_created_date = new \CDbExpression('now()');
		}

		return $model;
	}

	public static function getLOUData(\Vehicles $model, $vndId = 0)
	{
		$obj			 = new Cab();
		$obj->id		 = (int) $model->vhc_id;
		$obj->code		 = $model->vhc_code;
		$obj->number	 = strtoupper($model->vhc_number);
		$obj->isAttached = (int) $model->vhc_is_attached;
		$obj->isOwner	 = (int) $model->vhc_owned_or_rented;
		$obj->owner		 = \Beans\contact\Person::setFirstLastName($model->vhc_reg_owner, $model->vhc_reg_owner_lname);
		if($vndId > 0)
		{
			$vendorVehicleModel	 = \VendorVehicle::model()->findByVndVhcId($vndId, $model->vhc_id);
			$obj->isLOURequired	 = (int) $vendorVehicleModel->vvhc_is_lou_required;
		}
		if($vendorVehicleModel)
		{
			$obj->LOU = new \Beans\cab\LOU();
			$obj->LOU->setDataByModel($vendorVehicleModel);
		}
		return $obj;
	}

	public function setBasicInfo($data)
	{
		$this->id				 = (int) $data->vhc_id;
		$this->number			 = strtoupper(str_replace('  ', ' ', trim($data->vhc_number)));
		$this->color			 = $data->vhc_color;
		$this->category			 = \Beans\common\CabCategory::setByDataModel($data);
		 
		$this->year				 = (int) $data->vhc_year;
	 
	}
}
