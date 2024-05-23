<?php

namespace Beans\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

	/*
	*	Description of PartnerTransactionDetails
	*
	* @author Dev	
	*
	* @property string $commission;
	* @property string $markup;
	* @property string $creditsUsed;
	* @property string $advance;
	* @property string $additionalAmount;
	* @property string $discount;
	* @property string $extraTollTax;
	* @property string $extraCharge;
	* @property string $extraKmCharge;
	* @property string $extraKm;
	* @property string $extraStateTax;
	* @property string $parkingCharge;
	* @property string $amountCollected;
	* @property string $extraMin;
	* @property string $extraMinCharges;  
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
	
	
	public function setData($transObj)
	{
		$this->additionalAmount			= (int) $transObj->additionalAmount;
		$this->amountCollected			= (int) $transObj->driverCollected;
		$this->setExtraCharges($transObj->extraCharges);
		#$this->setExtra
		$this->setAdditionalCharges($transObj->additionalCharges);
		
		#$this->extraCharge				=  $transObj->extraCharges[0]->amount;
		#$this->extraKm					= (int) $transObj->extraKm;
		#$this->extraStateTax			= (int) $transObj->extraStateTax;
		#$this->extraTollTax				= (int) $transObj->extraTollTax;
		#$this->parkingCharge			= (int) $transObj->parkingCharge;
		
		
	}
	
	public function setExtraCharges($extraChargesArr)
	{

		foreach ($extraChargesArr as $extraCharges)
		{

			if ($extraCharges->type == 'Distance' || $extraCharges->type == 'EXTRA_KM')
			{
				$this->extraKm		 = (int) $extraCharges->unit;
				$this->extraKmCharge = $extraCharges->amount;
			}
			if ($extraCharges->type == 'Time' || $extraCharges->type == 'EXTRA_MIN')
			{
				$this->extraMin			 = (int) $extraCharges->unit;
				$this->extraMinCharges			= $extraCharges->amount;
			}
		}
	}

	public function setAdditionalCharges($additionalChargesArr)
	{
		foreach ($additionalChargesArr as $additionalCharges)
		{

			if (($additionalCharges->type == 1 || $additionalCharges->type == "TOLL_TAX" ) &&  $additionalCharges->isIncluded != 1)
			{
				$this->extraTollTax = (int) $additionalCharges->amount;
			}
			if (($additionalCharges->type == 2 || $additionalCharges->type == "STATE_TAX") &&  $additionalCharges->isIncluded != 1)
			{
				$this->extraStateTax = (int) $additionalCharges->amount;
			}
			if (($additionalCharges->type == 3 || $additionalCharges->type == "PARKING_CHARGE")&&  $additionalCharges->isIncluded != 1)
			{
				$this->parkingCharge = (int) $additionalCharges->amount;
			}
			if ($additionalCharges->type == 4 &&  $additionalCharges->isIncluded != 1)
			{
				$this->driverAllowance = (int) $additionalCharges->amount;
			}
		}
	}

	/**
	 * @return ExtraCharges  
	 */
	public function getExtraCharges()
	{
		$data								= $this;		
		$extraCharges						= new \Beans\common\ExtraCharges();
		$extraCharges->km					= empty($data->extraKm) ? 0 : $data->extraKm;
		$extraCharges->tollTax				= empty($data->extraTollTax) ? 0 : $data->extraTollTax;
		$extraKm							= (isset($data->extraCharge) ? $data->extraCharge:$data->extraKmCharge);		
		$extraCharges->kmCharges			= empty($extraKm) ? 0 : $extraKm;
		$extraCharges->stateTax				= empty($data->extraStateTax) ? 0 : $data->extraStateTax;
		$extraCharges->parking				= empty($data->parkingCharge) ? 0 : $data->parkingCharge;
		$extraCharges->extraMin				= empty($data->extraMin) ? 0 : ($data->extraMin>0 ? $data->extraMin :0);
		$extraCharges->extraMinCharges		= empty($data->extraMinCharges) ? 0 : $data->extraMinCharges;		
		return $extraCharges;
	}

}
