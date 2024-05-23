<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Vendor
 *
 * @author Abhishek Khetan
 */
class Vendor extends Person
{

	public $id, $code, $uniqueName, $type, $tier, $catType, $status,$totalTrips,
 $approveStatus,$dataList,$firmType;


	/** @var \Stub\common\Person $contact */
	public $contact;

	/** @var \Stub\common\Business $business */
	public $business;

	/**
	 * 
	 * @param \Vendors $model
	 * @return $this
	 */
	public function setData(\Vendors $model = null)
	{
		if ($model == null)
		{
			$model = \Vendors::model()->findByPk($this->id);
		}
		$this->id			 = $model->vnd_id;
		$this->code			 = $model->vnd_code;
		$this->uniqueName	 = $model->vnd_name;
		$this->tier			 = $model->vnd_rel_tier;
		$this->type			 = $model->vnd_type;
		$this->catType		 = $model->vnd_cat_type;
		$this->status		 = $model->vendorPrefs->vnp_is_freeze;
		$this->approveStatus = $model->vnd_active;
		$vndContact			 = $model->vndContact;
		if ($vndContact->ctt_user_type == 2)
		{
			$this->business	 = new \Stub\common\Business();
			$this->business->setData($vndContact);
			$vndContact		 = $vndContact->contactOwner;
		}

//else
		//{
		//	$this->contact = new \Stub\common\Person();
		//	$this->contact->setContactData();
		//}
		else
		{
			$this->contact = new \Stub\common\Person();
			$this->contact->setPersonData($vndContact);
		}
		return $this;
	}

	public function setModelData($id = 0)
	{
		if ($id > 0)
		{
			$model = \Vendors::model()->findByPk($id);
		}
		else
		{
			$model = new \Stub\common\Vendor();
		}
		$this->id			 = (int)$model->vnd_id;
		$this->code			 = $model->vnd_code;
		$this->uniqueName	 = $model->vnd_name;
		$this->tier			 = (int)$model->vnd_rel_tier;
		$this->type			 = (int)$model->vnd_type;
		$this->catType		 = (int)$model->vnd_cat_type;
		$this->status		 = (int)$model->vendorPrefs->vnp_is_freeze;
		$this->approveStatus = (int)$model->vnd_active;
		$this->firmType= (int)$model->vnd_firm_type;
		return $this;
	}

	public function fillContactData($row)
	{
		$this->id = $row["cr_is_vendor"];
		$this->code = $row["vnd_code"];
		$this->license = $row["ctt_license_no"];
	}

	public function fillData($row)
	{
		$this->id			 = (int) $row['vnd_id'];
		$this->uniqueName	 = $row['vnd_name'];
		$this->code			 = $row['vnd_code'];
	}

	public function setVendorData($dataArr)
	{
		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Vendor();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}
	public function basicVendorData($data)
	{
		
		 $this->approveStatus=$data['vnd_active'];
		 $this->uniqueName=$data['vnd_name'];
		 $this->code=$data['vnd_code'];
	}
	
	
	public function setVndData($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj				 = new \Stub\common\Vendor();
			$obj->allData($row);
			$this->data[]	 = $obj;
		}
		return $this->data;
	}
	public function allData($row)
	{
		$this->id = (int) $row['vnd_id'];
		$this->text	 = $row['ctt_first_name'].' '.$row['ctt_last_name'];
	}

}
