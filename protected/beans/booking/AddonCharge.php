<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdditionalCharges
 *
 * @author Dev
 * 
 * 
 * @property \Beans\booking\Addon $addon
 * @property integer $amount
 * @property string $description 
 * @property bool $isGSTApplicable
 */

namespace Beans\booking;

class AddonCharge
{

	/** @var \Beans\booking\Addon $addon */
	public $addon;
	public $amount;
	public $description;
	public $isGSTApplicable;

	public static function setByInvoiceModel(\BookingInvoice $invoiceModel)
	{
		$arrAddonDetails = $invoiceModel->getAddonDetailsByInvoiceModel();
		$dataList		 = AddonCharge::getList($arrAddonDetails);
		return $dataList;
	}

	public static function getList($arrAddonDetails)
	{
		$dataList = [];
		foreach ($arrAddonDetails as $data)
		{
			$dataList[] = AddonCharge::fillData($data);
		}
		return $dataList;
	}

	public static function fillData($data)
	{
		$obj					 = new AddonCharge();
		$obj->addon				 = \Beans\booking\Addon::setData($data);
		$obj->amount			 = (int) $data['cost'];
		$obj->description		 = $data['type'];
		$obj->isGSTApplicable	 = true;
		return $obj;
	}

}
