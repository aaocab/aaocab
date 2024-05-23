<?php

namespace Stub\common;

class Coordinates
{

	public $latitude, $longitude;

	public function __construct($latitude, $longitude)
	{
		$this->latitude	 = (float) $latitude;
		$this->longitude = (float) $longitude;
	}

}
