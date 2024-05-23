<?php

namespace Beans\booking;

class Offers
{

	public $id;
	public $count;
	public $currentDateTime;
	public $startedAt;
	public $preferredPickupTime;
	public $timerRemaining;
	public $expireAt;

	/** @var \Beans\booking\Offer $offers[] */
	public $offers;

	/**
	 * 
	 * @param array $data
	 * @return \Beans\booking\Offers
	 */
	public static function setData($data)
	{
		$timerStatData	 = $data['timerStat'];
		$offerData		 = $data['dataList']['data'];

		$obj						 = new Offers();
		$obj->count					 = $data['cnt'];
		$obj->startedAt				 = $timerStatData['startedAt'];
		$obj->currentDateTime		 = $timerStatData['currentDateTime'];
		$obj->preferredPickupTime	 = $timerStatData['preferredPickupTime'];
		$obj->timerRemaining		 = $timerStatData['step1DiffSecs'];
		$obj->expireAt				 = $timerStatData['expireAt'];

		$obj->offers = \Beans\booking\Offer::setDataSet($offerData);

		return $obj;
	}

}
