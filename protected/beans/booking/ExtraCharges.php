<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtraCharges
 *
 * @author Dev
 * 
 * @property string $type
 * @property string $unit
 * @property integer $amount
 * @property string $gst 
 */

namespace Beans\booking;

class ExtraCharges
{

	public $type; //Distance||Time
	public $unit;
	public $amount;
	public $gst;

	public static function setByInvoiceModel(\BookingInvoice $invoiceModel)
	{
		$dataList	 = [];
		$dataList[]	 = ExtraCharges::setExtraTimeCharges($invoiceModel);
		$dataList[]	 = ExtraCharges::setExtraDistanceCharges($invoiceModel);
		return $dataList;
	}

	public static function setExtraTimeCharges($invoiceModel)
	{
		$obj		 = new ExtraCharges();
		$obj->type	 = "Time";
		$obj->unit	 = $invoiceModel->bkg_extra_min;
		$obj->amount = (int) $invoiceModel->bkg_extra_total_min_charge;
		$obj->gst	 = null;
		return $obj;
	}

	public static function setExtraDistanceCharges($invoiceModel)
	{
		$obj		 = new ExtraCharges();
		$obj->type	 = "Distance";
		$obj->unit	 = $invoiceModel->bkg_extra_km;
		$obj->amount = (int) $invoiceModel->bkg_extra_km_charge;
		$obj->gst	 = null;
		return $obj;
	}

	public static function setExtraInputCharge($data)
	{
		$dataList = [];

		foreach($data as $extraChargesData)
		{
			if(($extraChargesData->type == 'time' || $extraChargesData->type == 'Time' || $extraChargesData->type == 'EXTRA_MIN') && $extraChargesData->amount != 0)
			{
				$dataList[] = ExtraCharges::setInputExtraTimeCharges($extraChargesData);
			}
			if(($extraChargesData->type == 'Distance' || $extraChargesData->type == 'EXTRA_KM') && $extraChargesData->amount != 0)
			{
				$dataList[] = ExtraCharges::setInputExtraDistanceCharges($extraChargesData);
			}
		}
		return $dataList;
	}

	public static function setInputExtraDistanceCharges($data)
	{
		$obj		 = new ExtraCharges();
		$obj->type	 = ($data->type == 'EXTRA_KM') ? "EXTRA_KM" : "Distance";
		$obj->unit	 = $data->unit;
		$obj->amount = (int) $data->amount;
		$obj->gst	 = null;

		return $obj;
	}

	public static function setInputExtraTimeCharges($data)
	{
		$obj		 = new ExtraCharges();
		$obj->type	 = ($data->type == 'EXTRA_MIN') ? "EXTRA_MIN" : "Time";
		$obj->unit	 = $data->unit;
		$obj->amount = (int) $data->amount;
		$obj->gst	 = null;

		return $obj;
	}
}
