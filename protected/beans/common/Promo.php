<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Promo
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $code
 * @property string $description
 */

namespace Beans\common;

class Promo
{

	public $id;
	public $code;
	public $description;

	/** @var \BookingInvoice $invoiceModel */
	public static function setByModel($invoiceModel)
	{
		$obj		 = new Promo();
		$promoId	 = ($invoiceModel->bkg_promo1_id > 0) ? $invoiceModel->bkg_promo1_id : $invoiceModel->bkg_promo2_id;
		$obj->id	 = (int) $promoId;
		$obj->code	 = ($invoiceModel->bkg_promo1_code != '') ? $invoiceModel->bkg_promo1_code : $invoiceModel->bkg_promo2_code;
//		$obj->description	 = $invoiceModel->bkg_promo1_code . $invoiceModel->bkg_promo2_code;
		return $obj;
	}

}
