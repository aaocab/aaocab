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

class TravellerInfo
{
	public $passengerCount, $luggageCount, $driverComments, $flightNumber, $trainNumber, $language;

	public function setAdditionalInfo(\BookingAddInfo $model = null)
	{
		/** @var BookingAddInfo $model */
		if ($model == null)
		{
			$model = new \BookingAddInfo();
		}
		$model->bkg_no_person						 = (int) $this->passengerCount | 0;
		$model->bkg_num_large_bag					 = (int) $this->luggageCount | 0;
		$model->bkg_spl_req_driver_english_speaking	 = (int) $this->language | 0;
		$model->bkg_flight_no                        = $this->flightNumber | '';
		return $model;
	}

	public static function getData($data)
	{
		
	}
}
