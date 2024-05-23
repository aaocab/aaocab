<?php

namespace Stub\common;

class GooglePlace
{

	public $placeID, $placeName, $placeTypeIDs, $placeAddress;

	/** @var \Stub\common\Coordinates $coordinates */
	public $coordinates;

	public function setData($gPlaces)
	{
		$this->placeID		 = $gPlaces->placeID;
		$this->placeName	 = $gPlaces->placeName;
		$this->placeTypeIDs	 = $gPlaces->placeTypeIDs;
		$this->placeAddress	 = $gPlaces->placeAddress;
		if ($lat != null && $long != null)
		{
			$this->coordinates = new Coordinates($gPlaces->coordinates->latitude, $gPlaces->coordinates->longitude);
		}
	}

}
