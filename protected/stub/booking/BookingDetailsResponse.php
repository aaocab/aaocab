<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BookingDetailsResponse
{

	public $bkg_id;
	public $bcb_id;
	public $bcb_vendor_id;
	public $bookingId;
	public $pickupDate;
	public $pickupTime;
	public $bkg_route_name;
	public $pickup_location;
	public $drop_location;
	public $pickup_datetime;
	public $special_instruction;
	public $vendor_assign_date;
	public $diff;
	public $call_id,$call_type;

	/** @var \Stub\common\Person $traveller */
	public $traveller;
	public $contact;

	/** @var \Stub\common\Cab[] $cabDetails; */
	public $cabDetails;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	public function setData($model)
	{

		$this->bkg_id			 = $model['bkg_id'];
		$this->bcb_id			 = $model['bcb_id'];
		$this->bcb_vendor_id	 = $model['bcb_vendor_id'];
		$this->bookingId		 = $model['bookingId'];
		$this->bkg_route_name	 = $model['bkg_route_name'];

		$this->pickup_location	 = $model['pickup_location'];
		$this->drop_location	 = $model['drop_location'];
		$this->pickup_datetime	 = $model['pickup_datetime'];

		$this->special_instruction	 = $model['special_instruction'];
		$this->vendor_assign_date	 = $model['vendor_assign_date'];
		$this->diff					 = $model['diff'];

		$this->traveller = new \Stub\common\Person();
		$this->traveller->SetCustomerData($model);

		$this->driver = new \Stub\common\Driver();
		$this->driver->setDriverdata($model);

		$cab				 = new \Stub\common\Cab();
		$cab->cabDetails($model);
		$this->cabDetails	 = $cab;

		$fare		 = new \Stub\common\Fare();
		$fare->setBasicFare($model);
		$this->fare	 = $fare;
	}
	
	public function setLimitData($model,$call_type)
	{

		$this->call_id			 = $model->bkg_id;
		$this->call_type			 = $call_type;
		$this->contact = new \Stub\common\Person();
		$this->contact->SetLeadData($model,$call_type);
		

	}
}
