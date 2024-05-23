<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TripStatement
 *
 * @author Deepak
 * 
 *   
 * @property integer $tripDate
 * @property integer $tripAmount
 * @property integer $tripDue
 * @property integer $collectedAmount; 
 * @property string $bookingId
 * @property string $tripId
 * @property string $description  
 * 
 * 
 */

namespace Beans\accounts;

class TripStatement
{

	public $tripDate;
	public $collectedAmount;
	public $tripAmount;
	public $tripDue;
	public $bookingId;
	public $tripId;
	public $description;

	public static function setData($data)
	{

//  [ven_trans_date] => (string) 2023-03-22 11:45:00
//  [bcb_trip_type] => (string) 0
//  [bcb_id] => (string) 2438587
//  [bkg_booking_id] => (string) OW301900461
//  [trip_amount] => (string) 2995
//  [trip_vendor_collected] => (string) 2458
//  [ven_trans_amount] => (string) 537
//  [tripDetails] => (string) [{"id": "OW301900461", "collected": 2458, "routes": "Kolkata - Asansol"}]
//  [bstatus] => (string) 6
//  [bkg_advance_amount] => (string) 1054
//  [from_city] => (string) Kolkata - Asansol


		$obj					 = new TripStatement();
		$obj->tripDate			 = $data['ven_trans_date'];
		$obj->tripAmount		 = (int) $data['trip_amount'];
		$obj->collectedAmount	 = (int) $data['trip_vendor_collected'];
		$obj->tripDue			 = (int) $data['trip_vendor_collected'] - $data['trip_amount'];
		$obj->description		 = $data['from_city'];

		$obj->bookingId	 = $data['bkg_booking_id'];
		$obj->tripId	 = (int) $data['bcb_id'];
		return $obj;
	}
}
