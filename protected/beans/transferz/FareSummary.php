<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cab
 *
 * @author Dev
 * 
 */

namespace Beans\transferz;

class FareSummary
{
	public $includingVat, $excludingVat, $vat, $currency;

	public function setData(\BookingInvoice $model = null)
	{
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}

		$currencyCredentials = json_decode(\Config::get('currency_exchange_rate'), true);
		$model->bkg_total_amount = round($this->excludingVat * $currencyCredentials[$this->currency]);
		$model->bkg_advance_amount = round($this->excludingVat * $currencyCredentials[$this->currency]);
		$model->bkg_is_parking_included = 1;
		return $model;
	}

	public static function Data($data)
	{
		//$this->code = $data->code;
	}

}
