<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Quotes
 *
 * @author Dev
 * 
 * @property integer $id 
 * @property \Beans\Booking $booking
 * @property string $remarks
 * @property string $city
 * @property string $cab
 * @property string $pickupDate
 * @property string $bookingType
 * @property string $noOfDays
 * @property string $isInterested
 * @property string $description
 */

namespace Beans\booking;

class Quotes
{
	public $id;

	/** @var \Beans\Booking $booking */
	public $booking;

	public $remarks;	
	public $city;
	public $cab;
	public $pickupDate;
	public $bookingType;
	public $noOfDays;
	public $isInterested;
	public $description;	
}
