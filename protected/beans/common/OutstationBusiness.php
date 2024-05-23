<?php

namespace Beans\common;

class OutstationBusiness
{

	public $oneWay;
	public $roundTrip;
	public $packages;

	public function setData($data)
	{
		$this->oneWay	 = ($data['vnp_oneway'] == 1) ? (int) $data['vnp_oneway'] : 0;
		$this->roundTrip = ($data['vnp_round_trip'] == 1) ? (int) $data['vnp_round_trip'] : 0;
		$this->packages	 = ($data['vnp_package'] == 1) ? (int) $data['vnp_package'] : 0;
	}

}
