<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @property \Stub\common\Fare $fare
 * @property \Stub\common\Cab $cab
 */
class CabRate
{

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\Fare $fare */
	public $fare;
	public $distance;

	/** @var \Stub\common\Fare $discountedFare */
	public $discountedFare;
	public $cancellationPolicy;

	/** @var \Stub\common\Fare $coinsFare */
	public $coinsFare;

	/**
	 * 
	 * @param \Quote $quote
	 * @param boolean $showModel
	 * @param integer $userTotalCredit
	 */
	public function setQuote(\Quote $quote, $showModel = false)
	{
		$routes		 = $quote->routes[0];
		$userInfo	 = \UserInfo::getInstance();
		$agentId	 = $quote->partnerId; //$userInfo->userId;
		$isGozonow	 = ($quote->gozoNow) ? 1 : 0;

		$cancelRuleId = \CancellationPolicy::getCancelRuleId($agentId, $quote->cabType, $routes->brt_from_city_id, $routes->brt_to_city_id, $quote->tripType, $isGozonow);
		if ($cancelRuleId)
		{
			$cancelText = \CancellationPolicy::getCancelTimeText($cancelRuleId, $quote->pickupDate);
		}

		$this->cab					 = new Cab();
		$this->cab->setCabType($quote->cabType, false, $showModel, $cancelText);
		$this->fare					 = new Fare();
		$this->distance				 = $quote->routeDistance->quotedDistance;
//		$cancelRuleId				 = \CancellationPolicy::getCancelRuleId($agentId, $quote->cabType, $routes->brt_from_city_id, $routes->brt_to_city_id, $quote->tripType);
		$this->cancellationPolicy	 = \CancellationPolicyDetails::model()->findByPk($cancelRuleId)->cnp_code;

		$routeRates = clone $quote->routeRates;
//		$routeRates->discount		 = 0;
//		$routeRates->coinDiscount	 = 0;
		$routeRates->calculateTotal();
		$this->fare->setQuoteRates($routeRates, false, $quote);
		if ($quote->gozoNow)
		{
			$this->fare->setGNowQuoteRates($quote->routeRates);
		}

		if ($quote->partnerId == '' || $quote->partnerId == \Yii::app()->params['gozoChannelPartnerId'])
		{
			// B2C bookinbg 
			if ($routeRates->discount > 0 || $routeRates->coinDiscount > 0)
			{
				$this->discountedFare = new Fare();
				$this->discountedFare->setDiscountedQuoteRates($routeRates);
			}
		}

		//if($quote->partnerId == ''  || $quote->partnerId == \Yii::app()->params['gozoChannelPartnerId'])
		if ($userInfo->userType == \UserInfo::TYPE_CONSUMER)
		{
			// B2C bookinbg 
			$totalGozocoins = \UserCredits::getUserCoin($userInfo->userId);
			if ($totalGozocoins > 0 && !$quote->gozoNow)
			{
				$key			 = 'percentage';
				$usage			 = \Config::getValue("gozocoin.promo.usage", $key);
				$userCredituse	 = \UserCredits::getMaxApplicablePromoCredits($userInfo->userId, $routeRates->baseAmount, $usage / 100);
				$coinCanUse		 = $userCredituse['totalMaxApplicable'];

				$routeRates->baseAmount = ($routeRates->baseAmount - $coinCanUse);
				$routeRates->calculateTotal();

				$this->coinsFare				 = new Fare();
				$this->coinsFare->setQuoteRates($routeRates);
				$this->coinsFare->gozoCoins		 = (int) $coinCanUse;
				$this->coinsFare->totalAmount	 = (int) ($routeRates->baseAmount + $coinCanUse);
				return $this->coinsFare;
			}
		}
	}

	/**
	 * This function is used for setting the cab type and fare container
	 *
	 * @param type $cabId = scvId
	 * @param type $bkgInvoice = bkg Invoice
	 */
	public function setModelData($cabId, $bkgInvoice)
	{
		$this->cab	 = new Cab();
		$this->cab->setCabType($cabId);
		$this->fare	 = new Fare();
		$this->fare->setData($bkgInvoice);
	}

	/**
	 * This function is used for setting the cab type and fare container
	 *
	 * @param type $cabId = scvId
	 * @param type $bkgInvoice = bkg Invoice
	 */
	public function setModelData_v1($cabId, $bkgInvoice)
	{
		$this->cab	 = new Cab();
		$this->cab->setCabType_v1($cabId);
		$this->fare	 = new Fare();
		$this->fare->setData($bkgInvoice);
	}

}
