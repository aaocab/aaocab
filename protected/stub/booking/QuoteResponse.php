<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** @property \Stub\common\CabRate[] $cabRate */
class QuoteResponse
{

	public $startDate;
	public $startTime;
	public $quotedDistance;
	public $estimatedDuration;
	public $shuttleId;
	public $isGozonow;
	public $defLeadId;

	/** @var \Stub\common\CabRate[] $cabRate */
	public $cabRate;

	/** @var \Stub\common\PromoDetails $promo */
	public $promo;

	/** @var \Stub\common\User $user */
	public $user;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Beans\common\Dbo $dbo */
	public $dbo;

	/** @var  Description */
	public function setData($quotes)
	{

		$totalGozocoins	 = \UserCredits::getUserCoin(\UserInfo::getUserId());
		$i				 = 0;
		foreach ($quotes as $quote)
		{
			if ($quote->success)
			{
				if ($quote->cabType != 27 && $quote->cabType != 28 && $quote->cabType != 29)
				{
					\Logger::trace("CabType {$quote->cabType} - " . json_encode($quote->gozoNow));

					$this->isGozonow = (int) ($quote->gozoNow) ? 1 : 0;
					if ($i == 0)
					{
						$this->startDate		 = date('Y-m-d', strtotime($quote->pickupDate));
						$this->startTime		 = date('H:i:s', strtotime($quote->pickupDate));
						$this->quotedDistance	 = (int) $quote->routeDistance->quotedDistance;
						$this->estimatedDuration = (int) $quote->routeDuration->totalMinutes;
						$this->isGozonow		 = (int) ($quote->gozoNow) ? 1 : 0;
						$this->dbo		 = new \Beans\common\Dbo();
						$this->dbo		 = \Beans\common\Dbo::getData($quote->pickupDate);

						if ($quote->partnerId == null || $quote->partnerId == 1249 || $quote->partnerId == 0)
						{
							$this->user->totalGozocoins = $totalGozocoins;
						}
						$i++;
					}
					$cabRate		 = new \Stub\common\CabRate();
					$promos			 = new \Stub\common\PromoDetails();
					$showModel		 = true;
					$cabRate->setQuote($quote, $showModel);
					$this->cabRate[] = $cabRate;
					if ($quote->routeRates->promoRow['prm_id'] > 0)
					{
						$this->promo = $promos->setDetails($quote->routeRates->promoRow);
					}
				}
			}
		}
		return $this;
	}

	public function setShuttleData($quote)
	{
		$this->startDate = date('Y-m-d', strtotime($quote['slt_pickup_datetime']));
		$this->startTime = date('H:i:s', strtotime($quote['slt_pickup_datetime']));
		$this->shuttleId = $quote['slt_id'];
		$this->fare		 = new \Stub\common\Fare();
		$this->fare->setShuttleRates($quote);
	}

}
