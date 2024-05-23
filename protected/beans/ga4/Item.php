<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Beans\ga4;

/**
 * Description of Items
 *
 * @author akhet
 */
class Item
{

	//put your code here
	public $item_id, $item_name, $coupon, $discount, $index, $location_id, $price, $quantity;
	public $item_brand, $item_category, $item_category2, $item_category3, $item_category4, $item_category5, $item_variant;

	public static function populateBookingModel(\Booking $model)
	{
		$obj			 = new Item();
		$obj->item_name	 = $model->getGAItemName();
		$obj->item_id	 = $model->getGAItemId();

		$obj->item_category3 = $model->bkgFromCity->cty_name;
		$obj->price			 = (int) $model->bkgInvoice->bkg_net_base_amount;
		$obj->item_category	 = $model->booking_type[$model->bkg_booking_type];
		$obj->item_category2 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$obj->item_variant	 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label;
		
		$promo				 = $model->bkgInvoice->bkg_promo1_code;
		if ($promo == '' && ($model->bkgInvoice->bkg_credits_used > 0 || 
				($model->bkg_status=15 && $model->bkgInvoice->bkg_temp_credits > 0)))
		{
			$promo = 'GOZO_COINS';
		}
		$obj->coupon = $promo;
		
		return $obj;
	}
}
