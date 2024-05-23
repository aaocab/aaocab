<?php

namespace Stub\common;

class LuggageCapacity
{

	public $noOfPersons, $largeBag, $smallBag;

	/**
	 * @param integer $vctId
	 * @param integer $sccId
	 * @return object
	 */
	public static function init($vctId, $sccId, $noOfPerson=0)
	{
		$vccArr				 = \Booking::model()->getLuggageCapacityDetails($vctId, $sccId);
		$obj				 = new LuggageCapacity();
		$obj->noOfPersons	 = $noOfPerson;
		$obj->largeBag		 = $vccArr['largeBag'];
		$obj->smallBag		 = $vccArr['smallBag'];
		return $obj;
	}

}
