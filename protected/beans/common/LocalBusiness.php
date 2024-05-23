<?php

namespace Beans\common;

class LocalBusiness
{

	public $airportTransfers;
	public $dailyRental;

	public function setData($data)
	{
		$this->airportTransfers	 = ($data['vnp_airport'] == 1) ? (int) $data['vnp_airport'] : 0;
		$this->dailyRental		 = ($data['vnp_daily_rental'] == 1) ? (int) $data['vnp_daily_rental'] : 0;
	}

}
