<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cab
 *
 * @author Dev
 * 
 */

namespace Beans\transferz;

class PickupTime
{

	public $localTime, $timeZone;

	public function setData(\Booking $model = null)
	{
		/** @var Booking $model */
		if ($model == null)
		{
			$model = new \Booking();
		}

		$minTime = \Config::get('transferz.pickup.mintime');
		$objPickupDate	 = new \DateTime($this->localTime, new \DateTimeZone($this->timeZone));
		//$objPickupDate->add(new \DateInterval('PT' . $minTime . 'M'));
		$model->bkg_pickup_date		 = $objPickupDate->format('Y-m-d H:i:s');
		return $model;
	}

	public function setPendingData(\TransferzOffers $model = null)
	{
		/** @var TransferzOffers $model */
		if ($model == null)
		{
			$model = new \TransferzOffers();
		}

		$objPickupDate			 = new \DateTime($this->localTime, new \DateTimeZone($this->timeZone));
		$model->trb_pickup_date	 = $objPickupDate->format('Y-m-d H:i:s');
		$model->timeZone		 = $this->timeZone;
		return $model;
	}

	public static function getData($data)
	{
		
	}

}
