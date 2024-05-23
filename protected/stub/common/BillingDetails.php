<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BillingDetails extends \Stub\common\Person
{

	/**
	 * 
	 * @param \BookingUser $model
	 * @return \BookingUser
	 */
	public function getData($model = null)
	{
		if ($model == null)
		{
			$model = new \BookingUser();
		}
		$model->bkg_bill_fullname	 = $this->fullName;
		$model->bkg_bill_email		 = $this->email;
		$model->bkg_bill_address	 = $this->address;
		$model->bkg_bill_postalcode	 = $this->pincode;
		$model->bkg_bill_country	 = $this->country;
		$model->bkg_bill_state		 = $this->state;
		$model->bkg_bill_city		 = $this->city;
		$model->bkg_bill_contact	 = (int) $this->primaryContact->number;
		return $model;
	}

	/**
	 * 
	 * @param \BookingUser $model
	 * @return $this
	 */
	public function setData(\BookingUser $model)
	{
		$this->fullName					 = $model->bkg_bill_fullname;
		$this->email					 = $model->bkg_bill_email;
		$this->address					 = $model->bkg_bill_address;
		$this->pincode					 = $model->bkg_bill_postalcode;
		$this->city						 = $model->bkg_bill_city;
		$this->state					 = $model->bkg_bill_state;
		$this->country					 = $model->bkg_bill_country;
		$this->primaryContact->number	 = (int) $model->bkg_bill_contact;
		return $this;
	}

	public function setInfo(\BookingUser $model)
	{
		$this->fullName					 = ($model->bkg_bill_fullname != '') ? $model->bkg_bill_fullname : $model->bkg_user_fname . " " . $model->bkg_user_lname;
		$this->email					 = ($model->bkg_bill_email != '') ? $model->bkg_bill_email : $model->bkg_user_email;
		$this->address					 = $model->bkg_bill_address;
		$this->pincode					 = $model->bkg_bill_postalcode;
		$this->city						 = $model->bkg_bill_city;
		$this->state					 = $model->bkg_bill_state;
		$this->country					 = $model->bkg_bill_country;
		$this->primaryContact->code		 = 91;
		$this->primaryContact->number	 = (int) ($model->bkg_bill_contact != '') ? $model->bkg_bill_contact : $model->bkg_contact_no;
		return $this;
	}

	/**
	 * 
	 * @param \VoucherOrder $model
	 * @return \VoucherOrder
	 */
	public function getVoucherData($model = null)
	{
		if ($model == null)
		{
			$model = new \VoucherOrder();
		}
		$model->vor_bill_fullname	 = $this->fullName;
		$model->vor_bill_email		 = $this->email;
		$model->vor_bill_address	 = $this->address;
		$model->vor_bill_postalcode	 = $this->pincode;
		$model->vor_bill_country	 = $this->country;
		$model->vor_bill_state		 = $this->state;
		$model->vor_bill_city		 = $this->city;
		$model->vor_bill_contact	 = (int) $this->primaryContact->number;
		return $model;
	}

	/**
	 * 
	 * @param \VoucherOrder $model
	 * @return $this
	 */
	public function setVoucherData(\VoucherOrder $model)
	{
		$this->fullName					 = $model->vor_bill_fullname;
		$this->email					 = $model->vor_bill_email;
		$this->address					 = $model->vor_bill_address;
		$this->pincode					 = $model->vor_bill_postalcode;
		$this->city						 = $model->vor_bill_city;
		$this->state					 = $model->vor_bill_country;
		$this->country					 = $model->vor_bill_country;
		$this->primaryContact->number	 = (int) $model->vor_bill_contact;
		return $this;
	}

	public function setVoucherSummery(\VoucherOrder $model)
	{
		$this->fullName					 = $model->vor_bill_fullname;
		$this->email					 = $model->vor_bill_email;
		$this->pincode					 = $model->vor_bill_postalcode;
		$this->city						 = $model->vor_bill_city;
		$this->state					 = $model->vor_bill_state;
		$this->primaryContact->number	 = (int) $model->vor_bill_contact;
		return $this;
	}

}
