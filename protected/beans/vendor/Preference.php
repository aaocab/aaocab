<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Preference
 *
 * @author Dev
 * 
 * @property \Beans\vendor\TripTypeServices[] $approvedTripType
 * @property \Beans\vendor\TripTypeServices[] $pendingTripType
 * @property array() $requestedTripType
 * @property array() $removedTripType 
 */

namespace Beans\vendor;

class Preference
{

	/** @var \Beans\vendor\TripTypeServices[] $approvedTripType */
	public $approvedTripType;

	/** @var \Beans\vendor\TripTypeServices[] $pendingTripType */
	public $pendingTripType;
	public $requestedTripType;
	public $removedTripType;

	public function setData($data)
	{
		$this->approvedTripType	 = TripTypeServices::setApprovedData($data);
		$this->pendingTripType	 = TripTypeServices::setPendingData($data);
	}
}
