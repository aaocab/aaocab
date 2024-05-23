<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtraChargesRule
 *
 * @author Dev
 * 
 * @property string $type
 * @property string $graceUnits
 * @property string $ratePerUnit
 * @property string $perUnitSize
 * @property bool $isGSTApplicable
 */

namespace Beans\booking;

class ExtraChargesRule
{

	public $type; //Extra Km,Extra minutes 
	public $graceUnits;
	public $ratePerUnit;
	public $perUnitSize;
	public $isGSTApplicable;

	public static function setByInvoiceModel(\BookingInvoice $invoiceModel)
	{
		$dataList[]	 = ExtraChargesRule::setRulesForStartWaitTime($invoiceModel);
		$dataList[]	 = ExtraChargesRule::setRulesForEndExtraTime($invoiceModel);
		$dataList[]	 = ExtraChargesRule::setRulesForExtraDistance($invoiceModel);
		return $dataList;
	}

	/**
	 * 
	 * @param type $invoiceModel
	 * @return \Beans\booking\ExtraChargesRule
	 */
	public static function setRulesForStartWaitTime($invoiceModel)
	{
		$obj					 = new ExtraChargesRule();
		$obj->type				 = "startWaitTime";
		$obj->graceUnits		 = 0;
		$obj->ratePerUnit		 = (float) round($invoiceModel->bkg_extra_per_min_charge, 2);
		$obj->perUnitSize		 = (int) 1; //$invoiceModel->bkg_extra_total_min_charge;
		$obj->isGSTApplicable	 = true;
		return $obj;
	}

	/**
	 * 
	 * @param type $invoiceModel
	 * @return \Beans\booking\ExtraChargesRule
	 */
	public static function setRulesForEndExtraTime($invoiceModel)
	{
		$obj					 = new ExtraChargesRule();

		$cap                     = \Config::get('dayRental.timeSlot');
		$time_cap			     = json_decode($cap,true);
		
		$unit				     = $time_cap[0];
		$obj->type				 = "endExtraTime";
		$obj->graceUnits		 = 0;
		$obj->ratePerUnit		 = (float) round($invoiceModel->bkg_extra_per_min_charge*$unit, 2);
		$obj->perUnitSize		 = (int) $unit; //$invoiceModel->bkg_extra_total_min_charge;
		$obj->isGSTApplicable	 = true;
		return $obj;
	}

	public static function setRulesForExtraDistance($invoiceModel)
	{
		$obj					 = new ExtraChargesRule();
		$obj->type				 = "Distance";
		$obj->graceUnits		 = 0;
		$obj->ratePerUnit		 = (float) round($invoiceModel->bkg_rate_per_km_extra, 2);
		$obj->perUnitSize		 = (int) 1;
		$obj->isGSTApplicable	 = true;
		return $obj;
	}

}
