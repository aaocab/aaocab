<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PartnerDrvStatPush
{

	public $bookingId;
    public $orderRefId;
	public $bookingStatus;
	public $bookingStatusCode;

	/** 1:start; 2:Stop * */
	public $tripStatus;
	public $startDate;
	public $startTime;
	public $endDate;
	public $endTime;

	public $partner;
	public $company;
	public $booking_id;
	public $latitude;
	public $longitude;
	public $timestamp;
	public $device_id;
	public $reason;
	public $extra_travelled_km;
	public $extra_travelled_fare;
	public $total_travelled_fare;
	public $night_charges;
	public $total_travelled_km;
	public $amount_to_be_collected;
	public $advance_amount_paid;
	public $extraTollTax;
	public $extraStateTax;

	public $baseFare;
    public $extrakm;
    public $extrakmCharge;
    public $extraMinutes;
    public $extraMinCharge;
    public $discount;
    public $driverAllowance;
    public $gst;
    public $airportEntryFee;

	/** @var \Stub\common\Coordinates $coordinates */
	public $coordinates;

	/**
	 * This function is used for setting booking details	  
	 * @param type $model = Booking Model
	 */
	public function setData($model)
	{
		$this->bookingId		 = $model->bookingId;
        $this->orderRefId        = $model->orderRefId;
		$this->bookingStatus	 = $model->bookingStatus;
		$obj					 = new \Stub\common\PartnerDrvStatPush();
		$obj->setTripData($model);
		$this->tripdata			 = $obj;
	}

	/**
	 * 
	 * @param type $model = Booking Model
	 */
	public function setTripEndData($model)
	{
		$this->bookingId		 = $model->bookingId;
        $this->orderRefId        = $model->orderRefId;
		$this->bookingStatus	 = $model->bookingStatus;
		$this->extra_travelled_km	 = $model->extrakm;
		$this->extra_travelled_fare	 = $model->extrakmCharge;
		$this->total_travelled_fare	 = $model->totalAmount;
		$this->night_charges		 = $model->nightCharges;
		$this->total_travelled_km    = $model->total_travelled_km;
		$this->amount_to_be_collected = $model->amountToBeCollected;
		$this->advance_amount_paid    = $model->advanceAmount;
		$this->extraTollTax           = $model->tollCharges;
		$this->extraStateTax          = $model->stateTaxCharges;
		
		$this->baseFare			 = $model->baseFare;
		$this->extrakm			 = $model->extrakm;
		$this->extrakmCharge	 = $model->extrakmCharge;
		$this->extraMinutes		 = $model->extraMinutes;
		$this->extraMinCharge	 = $model->extraMinCharge;
		$this->discount			 = $model->discount;
		$this->driverAllowance	 = $model->driverAllowance;
		$this->extraTollTax		 = $model->extraTollTax;
		$this->extraStateTax	 = $model->extraStateTax;
		$this->gst				 = $model->gst;
		$this->airportEntryFee	 = $model->airportEntryFee;

		$obj					 = new \Stub\common\PartnerDrvStatPush();
		$obj->setTripData($model);
		$this->tripdata			 = $obj;
	}

	public function setTripData($model)
	{
		$this->tripStatus = $model->tripStatus;
		if ($this->tripStatus == 1)
		{
			$this->startDate = date("Y-m-d", strtotime($model->tripStartTime));
			$this->startTime = date("H:i:s", strtotime($model->tripStartTime));
		}
		else if($this->tripStatus == 2)
		{
			$this->endDate	 = date("Y-m-d", strtotime($model->tripStartTime));
			$this->endTime	 = date("H:i:s", strtotime($model->tripStartTime));
		}
		$this->coordinates->latitude	 = $model->lattitude;
		$this->coordinates->longitude	 = $model->longitude;
	}

	/**
	 * This function is used for setting details	  
	 * @param type $model = Booking Model
	 */
	public function setTripStartSJData($model)
	{
		$this->partner				 = 'GOZO CABS';
		$this->spicejet				 = 'spicejet';
		$this->booking_id			 = $model->orderRefId;
		$this->latitude				 = $model->lattitude;
		$this->longitude			 = $model->longitude;
		$this->timestamp			 = strtotime($model->tripStartTime) * 1000;
		$this->device_id			 = '192.1.168.3';
		$this->reason				 = 'reason';
		$this->extra_travelled_km	 = 0;
		$this->extra_travelled_fare	 = 0;
		$this->total_travelled_fare	 = 0;
		$this->night_charges		 = 0;
	}

	/**
	 * This function is used for setting details	  
	 * @param type $model = Booking Model
	 */
	public function setStopSJData($model)
	{
		$this->partner				 = 'GOZO CABS';
		$this->spicejet				 = 'spicejet';
		$this->booking_id			 = $model->orderRefId;
		$this->latitude				 = $model->lattitude;
		$this->longitude			 = $model->longitude;
		$this->timestamp			 = strtotime($model->tripStartTime) * 1000;
		$this->device_id			 = '192.1.168.3';
		$this->reason				 = 'reason';
		$this->extra_travelled_km	 = $model->extrakm;
		$this->extra_travelled_fare	 = $model->extrakmCharge;
		$this->total_travelled_fare	 = $model->totalAmount;
		$this->night_charges		 = $model->nightCharges;
		$this->total_travelled_km    = $model->total_travelled_km;
		$this->amount_to_be_collected = $model->amountToBeCollected;
		$this->advance_amount_paid    = $model->advanceAmount;
		$this->endDate                = $model->tripEndDateTime;
		$this->tripStatus             = ($model->bookingStatusCode == 6) ? "Completed" : "Allocated";

		$this->baseFare			 = $model->baseFare;
		$this->extrakm			 = $model->extrakm;
		$this->extrakmCharge	 = $model->extrakmCharge;
		$this->extraMinutes		 = $model->extraMinutes;
		$this->extraMinCharge	 = $model->extraMinCharge;
		$this->discount			 = $model->discount;
		$this->driverAllowance	 = $model->driverAllowance;
		$this->extraTollTax		 = $model->extraTollTax;
		$this->extraStateTax	 = $model->extraStateTax;
		$this->gst				 = $model->gst;
		$this->airportEntryFee	 = $model->airportEntryFee;
	}

	/**
	 * This function is used for setting details	  
	 * @param type $model = Booking Model
	 */
	public function setSJpickupData($model, $flag = NULL)
	{
		$this->partner				 = 'GOZO CABS';
		$this->booking_id			 = $model->orderRefId;
		$this->latitude				 = $model->lattitude;
		$this->longitude			 = $model->longitude;
		$this->timestamp			 = strtotime($model->tripStartTime) * 1000;
		$this->device_id			 = '192.1.168.3';
		if($flag == null)
		{
			$this->total_travelled_fare	 = $model->totalAmount;
		}
	}
}
