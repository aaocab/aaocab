<?php

namespace Beans\ga4;

/**
 * Description of Purchase
 *
 * @author akhet
 * 
 * @property Item[] $items
 */
class Ecommerce
{

	public $currency, $transaction_id, $coupon, $value, $tax, $payment_type;

	/** @var Item[] $items */
	public $items;

	public static function purchase(\Booking $model)
	{
		$obj				 = self::beginCheckout($model);
		$obj->transaction_id = $model->bkg_booking_id;
		$promo				 = $model->bkgInvoice->bkg_promo1_code;
		if ($promo == '' && $model->bkgInvoice->bkg_credits_used > 0)
		{
			$promo = 'GOZO_COINS';
		}
		$obj->coupon = $promo;

		return $obj;
	}

	public static function beginCheckout(\Booking $model)
	{
		$obj				 = self::addToCart($model);
		$promo				 = $model->bkgInvoice->bkg_promo1_code;
		if ($promo == '' && ($model->bkgInvoice->bkg_credits_used > 0 ||
				($model->bkg_status	 = 15 && $model->bkgInvoice->bkg_temp_credits > 0)))
		{
			$promo = 'GOZO_COINS';
		}
		$obj->coupon = $promo;

		return $obj;
	}

	public static function addPaymentInfo(\Booking $model, $value, $type)
	{
		$obj				 = self::beginCheckout($model);
		$obj->value			 = $value;
		$obj->payment_type	 = $type;
		return $obj;
	}

	public static function addToCart(\Booking $model)
	{
		$obj			 = new static();
		$obj->currency	 = "INR";
		$obj->value		 = (int) $model->bkgInvoice->bkg_net_base_amount;
		$item			 = Item::populateBookingModel($model);
		$obj->items		 = [$item];
		return $obj;
	}
}
