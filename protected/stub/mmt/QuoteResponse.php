<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuoteResponse
{

	/** @var \Stub\mmt\car_types[] $cabRate */
	public $car_types;
	public $distance_booked;
	public $is_part_payment_allowed;
	public $is_instant_search;
	public $is_instant_available;
	public $start_time;
	public $communication_type	 = 'PRE';
	public $verification_type;
	public $tnc;
	private $distanceBooked		 = [];

	public function cabList($quotes, $objData)
	{
		$this->car_types = [];
		/** @var \Quote $quote */
		foreach ($quotes as $quote)
		{
			if (!$quote->success)
			{
				continue;
			}
			if($quote->routeRates->partner_soldout == 1)
			{
				continue;
			}
			$cab_list								 = new \Stub\mmt\CabList();
			$showModel								 = true;
			$cab_list->setQuote($quote, $showModel, $objData);
			$this->car_types[]						 = $cab_list;
			$this->distanceBooked[$quote->cabType]	 = $quote->routeDistance->quotedDistance;

		}
		
		if(empty($this->car_types))
		{
			throw new \Exception("No cabs available for this route", \ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
	}

	public function setQuoteData($quotes, $objData = NULL)
	{
		$quote = current($quotes);

		if ($quote)
		{
			
			if ($quote->routeDistance->startDistance > 75)
			{
				throw new \Exception("No cabs available for this route", \ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$this->is_part_payment_allowed = true;
			
			$this->start_time        = $quote->pickupDate;
			$this->is_instant_search = false;

			if ($objData->one_way_distance != NULL)
			{
				if ($objData->trip_type == 'ONE_WAY' || count($objData->stopovers) > 0)
				{
					$lowestKm				 = min($quote->routeDistance->quotedDistance, $objData->one_way_distance);
					$this->distance_booked	 = $lowestKm;
				}
				else
				{
					$this->distance_booked = $quote->routeDistance->quotedDistance;
				}
			}
			else
			{
				$this->distance_booked = $quote->routeDistance->quotedDistance;
			}
		}
		$this->cabList($quotes, $objData);
		
		if ($objData->trip_type == 'ROUND_TRIP')
		{
			$this->distance_booked = min($this->distanceBooked);
		}
		$this->tnc = "https://www.gozocabs.com/terms";
	}



}
