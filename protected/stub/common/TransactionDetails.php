<?php

namespace Stub\common;
/** @deprecated Check  */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class TransactionDetails
{

	public $bookingId;
	public $amount;
	public $paymentType;
	public $contact;
	public $country;

	/** @var \Stub\common\Person $billing */
	public $billing;
	public $paymentStatus;	
	/** @var \Stub\common\Transactions $statusDesc */
	public $statusDesc;	
	/** @var \Stub\common\Transactions $statusCode */
	public $statusCode;	

	public function getConsumerDetails($model = null)
	{
		/** @var $model BookingUser */
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}
		$model->bkg_id							 = $this->bookingId;
		$model->bkgUserInfo->ptype				 = $this->paymentType;
		$model->bkgInvoice->partialPayment		 = $this->amount;
		$model->bkgUserInfo->bkg_bill_fullname	 = $this->billing->fullName;
		$model->bkgUserInfo->bkg_bill_email		 = $this->billing->email;
		$model->bkgUserInfo->bkg_bill_country	 = $this->billing->billCountry;
		$model->bkgUserInfo->bkg_bill_contact	 = $this->billing->billContact;
		$model->bkgUserInfo->bkg_bill_address	 = $this->billing->address;
		$model->bkgUserInfo->bkg_bill_city		 = $this->billing->city;
		$model->bkgUserInfo->bkg_bill_postalcode = $this->billing->pincode;
		$model->bkgUserInfo->bkg_bill_state		 = $this->billing->state;
		return $model;
	}

	public function setData($model)
	{
		$this->bookingId	     = (int) $model['apg_booking_id'];		
		$this->paymentStatus     = ($model['apg_status'] == 1)? true : false; 
		$this->statusDesc        = $model['apg_remarks']; 
		$this->statusCode        = (int) $model['apg_status']; 
		return $this;
	}

}
