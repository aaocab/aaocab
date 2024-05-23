<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Promo
 *
 * @author Roy
 * 
 * @property integer $bookingId
 * @property integer $eventType
 * @property integer $gozoCoins
 * @property integer $wallet
 * @property Promo $promo
 * @property Fare $fare
 */

namespace Beans\common;

class Promotions
{

	//put your code here
	public $bookingId;
	public $eventType;  // [1 => Add Promo, 2=> Remove Promo, 3=> Add Gozocoins, 4=> Remove Gozocoins,5=> Wallet Applied,6=> Wallet Removed,7=> Addon Applied,8=> Addon Removed ] 
	public $gozoCoins;
	public $wallet;

	/** @var \Stub\common\Fare */
	public $fare;

	/** @var \Beans\common\PromoDetails */
	public $promo;

	/** @var \Beans\transaction\AdvanceSlabs $advanceSlabs */
	public $advanceSlabs;

	/**
	 * 
	 * @param \BookingInvoice $model
	 * @return boolean|$this
	 */
	public static function populateData(\BookingInvoice $model)
	{
		if ($model == null)
		{
			return false;
		}
		$eventType			 = 0;
		$obj				 = new Promotions();
		$obj->bookingId		 = $model->bivBkg->bkg_id;
		$obj->promo			 = new \Beans\common\PromoDetails();
		$promoData			 = ['prm_code' => $model->bkg_promo1_code];
		$obj->promo::setDetails($promoData);
		if ($model->bkg_promo1_code != '')
		{
			$eventType = 1;
		}
		$obj->gozoCoins = $model->bkg_temp_credits;
		if ($model->bkg_temp_credits > 0)
		{
			$eventType = 3;
		}
		$obj->eventType = $eventType;
		return $obj;
	}

}
