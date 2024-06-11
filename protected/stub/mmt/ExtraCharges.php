<?php

namespace Stub\mmt;

class ExtraCharges
{

	/** @var \Stub\mmt\ExtraCharge $night_charges */
	public $night_charges;

	/** @var \Stub\mmt\ExtraCharge $night_pickup */
	public $night_pickup;

	/** @var \Stub\mmt\ExtraCharge $night_drop */
	public $night_drop;

	/** @var \Stub\mmt\ExtraCharge $state_tax */
	public $state_tax;

	/** @var \Stub\mmt\ExtraCharge $toll_charges */
	public $toll_charges;

	/** @var \Stub\mmt\ExtraCharge $airport_toll */
	public $airport_toll;

	/** @var \Stub\mmt\ExtraCharge $airport_entry_fee */
	public $airport_entry_fee;

	/** @var \Stub\mmt\ExtraCharge $waiting_charges */
	public $waiting_charges;

	/** @var \Stub\mmt\ExtraCharge $parking_charges */
	public $parking_charges;

	public function setExtraCharges(\Quote $quote)
	{
		$rates					 = $quote->routeRates;
		$priceRule				 = $quote->priceRule;
		$startTime				 = \DateTimeFormat::getHour($priceRule->prr_night_start_time);
		$endTime				 = \DateTimeFormat::getHour($priceRule->prr_night_end_time);
		$driverNightCharge		 = ($priceRule->prr_night_driver_allowance > 0 ? $priceRule->prr_night_driver_allowance : $priceRule->prr_day_driver_allowance);
		//$isNightChargeIncluded	 = ($rates->isNightDropIncluded == 1 || $rates->isNightPickupIncluded == 1) ? true : false;
		if (in_array($quote->tripType, [12]))
		{
			$driverNightCharge = 0;
		}
		$this->night_charges				 = ExtraCharge::setNightCharges($rates->driverAllowance > 0, $driverNightCharge, $startTime, $endTime, $quote->tripType);
		
		$isNightChargeIncluded =				$this->night_charges->is_included_in_grand_total;

		if(in_array($quote->tripType, [2,3]))
		{
			$isNightChargeIncluded =				true;
		}

        $this->night_charges->is_applicable  = ($quote->tripType == 12) ? true: $isNightChargeIncluded;
		$this->state_tax					 = ExtraCharge::setCharges($rates->isStateTaxIncluded, $rates->stateTax, $quote->tripType);
		$this->state_tax->is_applicable		 = true;
		$this->toll_charges					 = ExtraCharge::setCharges($rates->isTollIncluded, $rates->tollTaxAmount, $quote->tripType); 
		$this->toll_charges->is_applicable	 = true;
		$this->waiting_charges				 = ExtraCharge::setWaitingCharges(0, 100, 45);

		$airportCharges							 = $quote->routeRates->isAirportEntryFeeIncluded;
		$airportEntryFeeFlag					 = 1;
		$this->airport_entry_fee				 = ExtraCharge::setCharges($airportCharges, $rates->airportEntryFee, $quote->tripType, $airportEntryFeeFlag);  
		$this->airport_entry_fee->is_applicable	 = ($quote->tripType == 12) ? true: (bool) $rates->isAirportChargeApplicable;
		$this->airport_toll						 = ExtraCharge::setCharges($airportCharges, 0, $quote->tripType);
        $this->airport_toll->is_applicable       = ($quote->tripType == 12) ? true: (bool) $rates->isAirportChargeApplicable;
		$this->parking_charges					 = ExtraCharge::setCharges($rates->isParkingIncluded, $rates->parkingAmount);
        $this->parking_charges->is_applicable    = (bool) $rates->isParkingIncluded; 
	}

	public function getExtraCharges($model, $from, $to)
	{
        $this->night_charges->getNightCharges($model, $from, $to);
		$this->toll_charges->getTollCharges($model);
		$this->state_tax->getStateTaxCharges($model);
		$this->parking_charges->getParkingCharges($model);
		$this->waiting_charges->getWaitingCharges($model);
        $this->airport_entry_fee->getAirportEntryCharges($model);
		return $model;
	}

    public function getCharges($model, $from, $to)
	{
		$this->toll_charges = new \Stub\mmt\ExtraCharge();
		$this->toll_charges->getToll($model);

		$this->state_tax = new \Stub\mmt\ExtraCharge();
		$this->state_tax->getStateTax($model);

		$this->parking_charges = new \Stub\mmt\ExtraCharge();
		$this->parking_charges->getParking($model);

		$this->waiting_charges = new \Stub\mmt\ExtraCharge();
		$this->waiting_charges->getWaiting($model);
	}

}
