<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vendor
 *
 * @author Dev
 * 
 * @property string $id
 * @property string $code
 * @property string $type
 * @property \Beans\contact\Business $business
 * @property \Beans\contact\Person $owner
 * @property $imageURL
 * @property $rating
 * @property $joiningDate
 * @property $outStanding
 * @property \Beans\common\City $homeCity
 * @property \Beans\common\ValueObject  $status
 * @property \Beans\common\Document $agreement 
 */

namespace Beans;

class Vendor
{

	public $id;
	public $code;
	public $name;
	public $sedanCount;
	public $compactCount;
	public $suvCount;
	public $notes;
	public $totalTrips;
	public $outStanding;

	/** @var \Beans\contact\Business $business */
	public $business;

	/** @var \Beans\contact\Person $owner */
	public $owner;
	public $imageURL;
	public $rating;
	public $joiningDate;
	

	/** @var \Beans\common\City $homeCity */
	public $homeCity;

	/** @var \Beans\common\Zones $homeZones */
	public $homeZones;
	public $acceptedZones;

	/** @var \Beans\common\ValueObject $status */
	public $status;

	/** @var \Beans\common\Document $agreement */
	public $agreement;

	/** @var \Beans\common\Services $services */
	public $services;

	/** @var \Beans\vendor\Preference $preferences */
	public $preferences;

	/** @var \Beans\common\Documents $docStatus */
	public $docStatus;

	public function setData($vndId, $cttId)
	{
		$data = \Vendors::model()->getViewDetailbyId($vndId);
		if($data['vnd_active'] == 0)
		{
			return false;
		}
		$initialRating = 4.6;

		$this->id			 = (int) $vndId;
		$this->code			 = $data['vnd_code'];
		$this->name			 = $data['vnd_name'];
		$this->sedanCount	 = ($data['vnd_sedan_count'] > 0) ? (int) $data['vnd_sedan_count'] : 0;
		$this->compactCount	 = ($data['vnd_compact_count'] > 0) ? (int) $data['vnd_compact_count'] : 0;
		$this->suvCount		 = ($data['vnd_suv_count'] > 0) ? (int) $data['vnd_suv_count'] : 0;
		$this->notes		 = $data['vnd_notes'];
		$this->services		 = new \Beans\common\Services();
		$this->services->setServiceData($data);
		$this->homeCity		 = \Beans\common\City::setDetail($data);
		$this->setOwnerData($cttId);
		$status				 = $data['vnd_active'];
		$this->status		 = \Beans\common\ValueObject::setIdlabel($status, \Vendors::model()->vendorStatus[$status]);
		$this->rating		 = ($data['vnd_overall_rating'] == null || $data['vnd_overall_rating'] == " " ? $initialRating : $data['vnd_overall_rating']);
		$this->joiningDate	 = $data['vnd_create_date'];
	}

	public function setOwnerData($cttId)
	{
		$this->owner = new \Beans\contact\Person();
		$this->owner->setDataById($cttId);
	}

	public function setBusinessData($cttId)
	{
		$this->business = new \Beans\contact\Person();
		$this->business->setBusinessDataById($cttId);
	}

	public function setProfileDataV1($vndId)
	{

		$initialRating	 = 4.6;
		$data			 = \Vendors::model()->getViewDetailbyId($vndId);
		if($data['vnd_active'] == 0)
		{
			return false;
		}
		$status				 = $data['vnd_active'];
		$this->id			 = (int) $vndId;
		$this->code			 = $data['vnd_code'];
		$this->name			 = $data['vnd_name'];
		$this->sedanCount	 = ($data['vnd_sedan_count'] > 0) ? (int) $data['vnd_sedan_count'] : 0;
		$this->compactCount	 = ($data['vnd_compact_count'] > 0) ? (int) $data['vnd_compact_count'] : 0;
		$this->suvCount		 = ($data['vnd_suv_count'] > 0) ? (int) $data['vnd_suv_count'] : 0;
		$this->notes		 = $data['vnd_notes'];
//		$this->services		 = new \Beans\common\Services();
//		$this->services->setServiceData($data);

		$this->preferences = new \Beans\vendor\Preference();
		$this->preferences->setData($data);

		$this->acceptedZones = array_map('intval', explode(',', $data['vnd_accepted_zone']));
		if($data['ctt_city'] > 0)
		{
			$this->homeCity	 = \Beans\common\City::setDetail($data);
			$this->homeZones = \Beans\common\Zones::getListByCityId($data['ctt_city']);
		}

		$this->status		 = \Beans\common\ValueObject::setIdlabel($status, \Vendors::model()->vendorStatus[$status]);
		$this->rating		 = ($data['vnd_overall_rating'] == null || $data['vnd_overall_rating'] == " " ? $initialRating : $data['vnd_overall_rating']);
		$this->joiningDate	 = $data['vnd_create_date'];
	}

	/** @var \Vendors $vndModel */
	public static function setDataByModel($vndModel,$data=null,$infoflag=false)
	{
		
		$obj		 = new Vendor();
		$obj->id	 = (int) $vndModel->vnd_id;
		$obj->code	 = $vndModel->vnd_code;
		$obj->name	 = $vndModel->vnd_name;
		$status		 = $vndModel->vnd_active;
		if($status != null)
		{
			$obj->status = \Beans\common\ValueObject::setIdlabel($status, $vndModel->vendorStatus[$status]);
		}
		$obj->rating		 = $vndModel->vendorStats->vrs_vnd_overall_rating;
		$obj->joiningDate	 = $vndModel->vnd_create_date;
		if($infoflag == true)
		{
			$obj->oustanding = ($data->oustanding > 0 ? $data->oustanding:0);
		}
			$obj->appVersion = $data->appVersion;
			
		return $obj;
	}

	public function setStatus($statusData)
	{

		$obj->is_agreement	 = (int) $statusData['is_agreement'];
		$obj->is_document	 = (int) $statusData['is_document'];
		$obj->is_car		 = $statusData['is_car'];
		$obj->is_driver		 = $statusData['is_driver'];
		$obj->securityFlag	 = $statusData['securityFlag'];
		$obj->securityAmount = $statusData['securityAmount'];
		$obj->security_flag	 = $statusData['flag'];
		$obj->message		 = $statusData['message'];
		return $obj;
	}

	/**
	 * @param integer $vndId
	 * @return boolean|\Beans\Vendor
	 */
	public static function setDataForOffer($vndId)
	{
		$initialRating	 = 4.6;
		/* @var $model \Vendors */
		$model			 = \Vendors::model()->findByPk($vndId);

		if(!$model)
		{
			return false;
		}
		$obj			 = new \Beans\Vendor();
		$obj->id		 = (int) $model->vnd_id;
		$obj->code		 = $model->vnd_code;
		$obj->totalTrips = (int) $model->vendorStats->vrs_total_trips;
		$obj->rating	 = (float) ($model->vendorStats->vrs_vnd_overall_rating > 0) ? $model->vendorStats->vrs_vnd_overall_rating : $initialRating;
		return $obj;
	}

	public  function setInfo($data,$vndId)
	{
		$dataobj = \Filter::convertToObject($data);
		$model			 = \Vendors::model()->findByPk($vndId);
		$infoFlag		 = true;
		$this->vendor	 = \Beans\Vendor::setDataByModel($model,$dataobj,$infoFlag);
		$this->vendor->docStatus =  new \Beans\common\Documents();
		$this->vendor->docStatus->setDocumentStatus($data);
	}


	public function setMatrixDetails($data, $row)
	{


		$this->key = \Yii::app()->params['vendorMatrixkeys'][$data];
		
		if ($this->key == 'Partner status')
		{

			$this->value = self::$activeStatus[$row];
			goto tips;
		}
		
		if ($this->key == 'Partner level')
		{

			$this->value = self::$partnerType[$row];
			goto tips;
		}
		if ($this->key == 'Dependability')
		{

			$this->value = $row. '%';
			goto tips;
		}
		
		//$this->value = $row;
		tips:
		//$reasons = \Yii::app()->params['tipsVal'][2];
		$this->tips	 = \Yii::app()->params['tipsVal'][$this->key];
	}

	public function getMatrix($dataArr)
	{
		$data = 0;
		foreach ($dataArr as $row)
		{
			$data				 = $data + 1;
			$obj				 = new \Stub\vendor\InfoResponse();
			$obj->setMatrixDetails($data, $row);
			$this->dataList[]	 = $obj;
		}
	}

}
