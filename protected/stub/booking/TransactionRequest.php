<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TransactionRequest
{

	public $refId;
	public $method;
	public $bookingId;
	public $amount;
	public $paymentType;
	

	/** @var \Stub\common\BillingDetails $billing */
	public $billing;

	public function getModel($model = null)
	{
		/** @var $model Booking */
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}
		$model->bkg_id						 = (int) $this->refId;
		$model->bkgUserInfo->ptype			 = (int) $this->method;
		$model->bkgInvoice->partialPayment	 = $this->amount;
		$model->bkgUserInfo					 = $this->billing->getData($model->bkgUserInfo);
		return $model;
	}
	
		/**
	 * 
	 * @return type
	 */
	public function getBillingData()
	{
		$model = $this->billing->getData();
		return $model;
	}

}
