<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Discount
 *
 * @author Dev
 * 
 * @property integer $amount
 * @property \Beans\common\Promo $promo
 * @property string $description
 */

namespace Beans\booking;

class Discount
{

	public $amount;

	/** @var \Beans\common\Promo $promo */
	public $promo;
	public $description;

	/** @var \BookingInvoice $data */
	public static function setData($data)
	{
		$obj		 = new Discount();
		$obj->amount = (int)$data->bkg_discount_amount;
		$promoId	 = ($data->bkg_promo1_id > 0) ? $data->bkg_promo1_id : $data->bkg_promo2_id;
		if ($promoId > 0)
		{
			$obj->promo = \Beans\common\Promo::setByModel($data);
		}
		return $obj;
	}

}
