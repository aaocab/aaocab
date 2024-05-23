<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PartnerTransactionDetails
{

	public $commission;
	public $markup;
	public $creditsUsed;
	public $advance;
	public $additionalAmount;
	public $discount;
	public $extraTollTax;
	public $extraCharge;
	public $extraKmCharge;
	public $extraKm;
	public $extraStateTax;
	public $parkingCharge;
	public $amountCollected;
	public $extraMin;
	public $extraMinCharges;
	

	public function setModelData(\BookingInvoice $bkgInvoice)
	{
		$this->commission		 = (int) $bkgInvoice->bkg_agent_commission;
		$this->markup			 = (int) $bkgInvoice->bkg_agent_markup;
		$this->creditsUsed		 = (int) $bkgInvoice->bkg_credits_used;
		$this->advance			 = (int) $bkgInvoice->bkg_corporate_credit;
		$this->additionalAmount	 = (int) $bkgInvoice->bkg_additional_charge;
		$this->discount			 = (int) $bkgInvoice->bkg_discount_amount;
	}

	/** @return \BookingInvoice */
	public function getModel(\BookingInvoice $model = null)
	{
		$data = $this;
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}
		
		$model->bkg_extra_toll_tax			 = empty($data->extraTollTax) ? 0 : $data->extraTollTax;
		#$model->bkg_extra_km_charge			 = empty($data->extraCharge) ? 0 : $data->extraCharge;
		$model->bkg_extra_km_charge			 = empty($data->extraKmCharge) ? 0 : $data->extraKmCharge;
		$model->bkg_extra_km			     = empty($data->extraKm) ? 0 : $data->extraKm;
		$model->bkg_extra_state_tax			 = empty($data->extraStateTax) ? 0 : $data->extraStateTax;
		$model->bkg_parking_charge			 = empty($data->parkingCharge) ? 0 : $data->parkingCharge;
		$model->bkg_vendor_actual_collected	 = empty($data->amountCollected) ? 0 : $data->amountCollected;
		$model->bkg_extra_min			     = empty($data->extraMin) ? 0 : $data->extraMin;
		$model->bkg_extra_total_min_charge	 = empty($data->extraMinCharges) ? 0 : $data->extraMinCharges;
		return $model;
	}

	/**
	 * @return ExtraCharges  
	 */
	public function getExtraCharges()
	{

		$data					 = $this;
		
		$extraCharges			 = new ExtraCharges();
		$extraCharges->km		 = empty($data->extraKm) ? 0 : $data->extraKm;
		$extraCharges->tollTax	 = empty($data->extraTollTax) ? 0 : $data->extraTollTax;
		$extraKm                 = (isset($data->extraCharge)?$data->extraCharge:$data->extraKmCharge);
		
		//$extraCharges->kmCharges = empty($data->extraCharge) ? 0 : $data->extraCharge;
		$extraCharges->kmCharges = empty($extraKm) ? 0 : $extraKm;
		$extraCharges->stateTax	 = empty($data->extraStateTax) ? 0 : $data->extraStateTax;
		$extraCharges->parking	 = empty($data->parkingCharge) ? 0 : $data->parkingCharge;
		$extraCharges->extraMin	 = empty($data->extraMin) ? 0 : $data->extraMin;
		$extraCharges->extraMinCharges	 = empty($data->extraMinCharges) ? 0 : $data->extraMinCharges;
		
		return $extraCharges;
	}

}
