<?php

namespace Beans\vendor;

class TripTypeServices
{

	public $airportTransfers;
	public $dailyRental;
	public $oneWay;
	public $roundTrip;
	public $packages;

	public function setData($data)
	{
		$this->airportTransfers	 = ($data['vnp_airport'] == 1) ? (int) $data['vnp_airport'] : 0;
		$this->dailyRental		 = ($data['vnp_daily_rental'] == 1) ? (int) $data['vnp_daily_rental'] : 0;
		$this->oneWay			 = ($data['vnp_oneway'] == 1) ? (int) $data['vnp_oneway'] : 0;
		$this->roundTrip		 = ($data['vnp_round_trip'] == 1) ? (int) $data['vnp_round_trip'] : 0;
		$this->packages			 = ($data['vnp_package'] == 1) ? (int) $data['vnp_package'] : 0;
	}

	public static function setApprovedData($data)
	{
		$services = \VendorPref::getServiceListByValue($data, 1);
		return $services;
	}

	public function setPendingData($data)
	{
		$services = \VendorPref::getServiceListByValue($data, 0);
		return $services;
	}

}
