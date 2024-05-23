<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CustomQuote
{

	public $id;
	public $city;

	/** @var \Stub\common\Cab $cab */
	public $cab;
	public $pickupDate;
	public $description;
	public $bookingType;
	public $noOfDays;
	public $dataList;

	public function fillData($row)
	{
		$this->id			 = (int) $row['cqt_id'];
		$this->city			 = $row['cty_name'];
		$this->cab			 = new \Stub\common\Cab();
		$this->cab->setCabType($row['cabtype']);
		$this->pickupDate	 = $row['cqt_pickup_date'];
		$this->description	 = $row['cqt_description'];
		$this->bookingType	 = $row['cqt_booking_type'];
		$this->noOfDays		 = (int) $row['cqt_no_of_days'];
	}

	public static function getList($quotes)
	{
		$data = [];

		foreach ($quotes as $quote)
		{
			$obj	 = new \Stub\vendor\CustomQuote();
			$obj->fillData($quote);
			$data[]	 = $obj;
		}

		return $data;
	}

	 

}
