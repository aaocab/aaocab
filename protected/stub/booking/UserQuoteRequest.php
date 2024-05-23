<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\booking;

/**
 * Description of UserQuoteRequest
 *
 * @author Roy
 */
class UserQuoteRequest
{

	/** @var \Stub\common\Person $userInfo */
	public $userInfo;

	/** @var \Stub\booking\QuoteRequest $Itinerary */
	public $Itinerary;
	/**
	 @var $model \Booking
	 */
	public function getModelData()
	{
		$model = $this->Itinerary->getModel();
		return $model;
	}

}
