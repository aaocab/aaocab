<?php

namespace Stub\common;

class Location
{

	public $code, $isAirport, $address, $name, $placeName;

	/** @var \Stub\common\Coordinates $coordinates */
	public $coordinates;

	/** @var \Stub\common\GooglePlace $googlePlace */
	public $googlePlace;

	public function setData($code, $name, $address = null, $lat = null, $long = null, $isAirport = null, $placeName = null)
	{

		$this->code		 = (int) $code;
		$this->name		 = $name;
		$this->isAirport = $isAirport;

		$addressStr = ($placeName != '') ? $placeName . ", " . $address : $address;

		$this->address = ($addressStr != "") ? $addressStr : $name;
		if ($lat != null && $long != null)
		{
			$this->coordinates = new Coordinates($lat, $long);
		}
	}

}
