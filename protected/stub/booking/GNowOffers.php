<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GNowOffers
{

	public $count, $startedAt, $currentDateTime, $preferredPickupTime, $timerRemaining, $expireAt;

	/** @var \Stub\booking\GNowOfferList $offerList */
	public $offerList;

	public function setData($data)
	{
		$timerData = $data['timerStat'];

		$this->count				 = $data['cnt'];
		$this->currentDateTime		 = $timerData['currentDateTime'];
		$this->startedAt			 = $timerData['startedAt'];
		$this->preferredPickupTime	 = $timerData['preferredPickupTime'];
		$this->timerRemaining		 = $timerData['step1DiffSecs'];
		$this->expireAt				 = $data['expireAt'];

		$offerList		 = new \Stub\booking\GNowOfferList();
		$offers			 = $offerList->setData($data['dataList']['data']);
		$this->offers	 = $offers;
	}
}
