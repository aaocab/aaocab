<?php

namespace Stub\common;

class IReadBooking
{

	public $bookingSource,
			$bookingFromCity,
			$bookingToCity,
			$bookingType,
			$bookingVehicleTypeId,
			$bookingQuotedAmount,
			$bookingAgentId,
			$bookingPickupDate,
			$bookingStage,
			$bkgUserID,
			$bookingBaseFare,
			$bookingTripDistance,
			$bookingDriverAllowance,
			$bookingTollTaxAmount,
			$bookingStateTax,
			$bookingParkingAmount,
			$bookingAirportEntryFee,
			$bookingSurgeFactorUsed,
			$bookingConfirmDate,
			$bookingId;

	/** @var IRBooking */
	public function setData($quote, $cabs)
	{
		$userInfo		 = \UserInfo::getInstance();
		if ($quote->platform)
		{
			$this->bookingSource = $quote->platform != null && is_int($quote->platform) ? $quote->platform : 0;
		}
		else
		{
			$this->bookingSource = $quote->sourceQuotation != null && is_int($quote->sourceQuotation) ? $quote->sourceQuotation : 0;
		}
		$this->bookingFromCity			 = $quote->sourceCity;
		$this->bookingToCity			 = $quote->destinationCity;
		$this->bookingType				 = $quote->tripType;
		$this->bookingVehicleTypeId		 = $cabs > 0 && !is_array($cabs) ? $cabs : 0;
		$this->bookingAgentId			 = $quote->partnerId != null ? $quote->partnerId : 0;
		$this->bookingQuotedAmount		 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->vendorAmount : 0;
		$this->bookingPickupDate		 = $quote->pickupDate;
		$this->bookingStage				 = $cabs > 0 && !is_array($cabs) ? 2 : 1;
		$this->bkgUserID				 = $userInfo->userId;
		$this->bookingBaseFare			 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->baseAmount : 0;
		$this->bookingTripDistance		 = $cabs > 0 && !is_array($cabs) ? $quote->routeDistance->tripDistance : 0;
		$this->bookingDriverAllowance	 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->driverAllowance : 0;
		$this->bookingTollTaxAmount		 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->tollTaxAmount : 0;
		$this->bookingStateTax			 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->stateTax : 0;
		$this->bookingParkingAmount		 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->parkingAmount : 0;
		$this->bookingAirportEntryFee	 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->airportEntryFee : 0;
		$this->bookingSurgeFactorUsed	 = $cabs > 0 && !is_array($cabs) ? $quote->cabQuotes[$cabs]->routeRates->surgeFactorUsed : 0;
		$this->bookingConfirmDate		 = null;
		$this->bookingId				 = 0;
		return $this;
	}

	/** @var IRBooking */
	public function setConfirmData($models)
	{
		$userInfo						 = \UserInfo::getInstance();
		$this->bookingSource			 = $models->bkgTrail->bkg_platform != null ? $models->bkgTrail->bkg_platform : null;
		$this->bookingFromCity			 = $models->bkg_from_city_id;
		$this->bookingToCity			 = $models->bkg_to_city_id;
		$this->bookingType				 = $models->bkg_booking_type;
		$this->bookingVehicleTypeId		 = $models->bkg_vehicle_type_id;
		$this->bookingAgentId			 = $models->bkg_agent_id != null ? $models->bkg_agent_id : 1249;
		$this->bookingQuotedAmount		 = $models->bkgInvoice->bkg_quoted_vendor_amount > 0 ? $models->bkgInvoice->bkg_quoted_vendor_amount : 0;
		$this->bookingFromCity			 = $models->bkg_from_city_id;
		$this->bookingPickupDate		 = $models->bkg_pickup_date;
		$this->bookingStage				 = 4;
		$this->bkgUserID				 = $userInfo->userId;
		$this->bookingBaseFare			 = $models->bkgInvoice->bkg_base_amount > 0 ? $models->bkgInvoice->bkg_base_amount : 0;
		$this->bookingTripDistance		 = $models->bkg_trip_distance > 0 ? $models->bkg_trip_distance : 0;
		$this->bookingDriverAllowance	 = $models->bkgInvoice->bkg_driver_allowance_amount > 0 ? $models->bkgInvoice->bkg_driver_allowance_amount : 0;
		$this->bookingTollTaxAmount		 = $models->bkgInvoice->bkg_toll_tax > 0 ? $models->bkgInvoice->bkg_toll_tax : 0;
		$this->bookingStateTax			 = $models->bkgInvoice->bkg_state_tax > 0 ? $models->bkgInvoice->bkg_state_tax : 0;
		$this->bookingParkingAmount		 = $models->bkgInvoice->bkg_parking_charge > 0 ? $models->bkgInvoice->bkg_parking_charge : 0;
		$this->bookingAirportEntryFee	 = $models->bkgInvoice->bkg_airport_entry_fee > 0 ? $models->bkgInvoice->bkg_airport_entry_fee : 0;
		$this->bookingSurgeFactorUsed	 = $models->bkgPf->bkg_surge_applied > 0 ? $models->bkgPf->bkg_surge_applied : 0;
		$this->bookingConfirmDate		 = date('Y-m-d H:i:s');
		$this->bookingId				 = $models->bkg_id;
		return $this;
	}

}
