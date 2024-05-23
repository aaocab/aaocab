<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Promotions
 *
 * @author Roy
 * 
 * @property PromoDetails $promo
 * @property Fare $fare
 */
class Promotions
{

	//put your code here
	public $bookingId;
	public $eventType;  // [1 => Add Promo, 2=> Remove Promo, 3=> Add Gozocoins, 4=> Remove Gozocoins,5=> Wallet Applied,6=> Wallet Removed,7=> Addon Applied,8=> Addon Removed ] 
	public $gozoCoins;
	public $wallet;
	public $fare;
	public $promo;
	public $addonLabel = "";

	/**
	 *
	 * @param \Booking $model
	 * @return \Booking
	 */
	public function getData(\Booking $model = null)
	{
		if ($model == null)
		{
			$model = new \Booking('new');
		}
		$model->bkg_id = $this->bookingId;
		return $model;
	}

	/**
	 *
	 * @param \Booking $model
	 * @param \BookingInvoice $modelInvoice
	 * @param \Promos $promoModel
	 * @param integer $creditUsed
	 * @param string $message
	 * @return boolean|$this
	 */
	public function setData(\BookingInvoice $model, $eventType = 1)
	{
		if ($model == null)
		{
			return false;
		}
		$this->bookingId = (int) $model->biv_bkg_id;
		$this->eventType = $eventType;
		$this->fare		 = new Fare();
		$this->fare->setPromotionData($model);
		if ($model->bivPromos)
		{
			$this->promo = new PromoDetails();
			$this->promo->setModelData($model->bivPromos);
		}
		$this->wallet	 = $model->bkg_wallet_used;
		$this->gozoCoins = $model->getAppliedGozoCoins();
		$this->addonLabel = $model->addonLabel;
		return $this;
	}

	/** 
	 * 
	 * @param \BookingInvoice $model
	 * @param integer $eventType
	 * @return string
	 */
	public function getMessage(\BookingInvoice $model, $eventType=1)
	{
		$message = "";
		switch ($eventType)
		{
			case 1:
				if($model->bivPromos)
				{
					$message = \Promos::getDiscountMessage($model, $model->bivPromos);
				}
				break;
			case 2:
				$message = "Promo removed successfully.";
				break;
			case 3:
				$message = "{$model->getAppliedGozoCoins()} Gozo coins has been used";
				break;
			case 4:
				$message = "Gozo coins removed successfully.";
				break;
			case 5:
				$message .= "{$model->bkg_wallet_used} Wallet ballance applied";
				break;
			case 6:
				$message = "Gozo wallet removed successfully.";
				break;
			case 7:
				$message = "Addon applied successfully";
				break;
			case 8:
				$message = "Addon removed successfully";
				break;
		}
		return $message;
	}

}
