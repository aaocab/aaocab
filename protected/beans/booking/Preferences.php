<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Preferences
 *
 * @author Admin
 * 
 * 
 * @property string $isGozoNow
 * @property string $isCritical
 * @property string $isManual
 * @property string $driverAppRequired
 * @property string $autoAssignFlag 
 */

namespace Beans\booking;

class Preferences
{

	//put your code here

	public $isGozoNow, $isCritical, $isManual, $isDutySlipRequired, $isDriverAppRequired, $tripOTP;
	public $autoAssignFlag, $isCngAllowed, $isAgent, $isFlexxi, $isAssured, $isPaymentDue,
			$isNightPickupIncluded, $isNightDropIncluded, $paymentMsg;
	public $isTollTaxIncluded, $isStateTaxIncluded, $isParkingIncluded, $carrierRequired , $extraParkingCharge, $extraPerKmCharge , $extraPerMinCharge, $vendorExtraPerKmCharge;

	public static function setData($data)
	{
		$obj = new Preferences();

		$obj->isGozoNow				 = (int) $data->isGozoNow;
		$obj->isCngAllowed			 = (int) $data->is_cng_allowed;
		$obj->isAgent				 = (int) $data->is_agent;
		$obj->isFlexxi				 = (int) $data->isFlexxi;
		$obj->isAssured				 = (int) $data->is_assured;
		$obj->paymentMsg			 = $data->payment_msg;
		$obj->isNightPickupIncluded	 = (int) $data->bkg_night_pickup_included;
		$obj->isNightDropIncluded	 = (int) $data->bkg_night_drop_included;
		$obj->isPaymentDue			 = (int) $data->payment_due;
		$obj->isDutySlipRequired	 = (int) $data->bkg_duty_slip_required;
		$obj->isDriverAppRequired	 = (int) $data->is_duty_slip_required;

		return $obj;
	}

	/** @var \Booking $model */
	public static function setByModel($model)
	{
		$obj						 = new Preferences();
		$obj->isGozoNow				 = (int) ($model->bkgPref->bkg_is_gozonow == 1) ? 1 : 0;
		$obj->isCngAllowed			 = (int) ($model->bkgPref->bkg_cng_allowed == 1 && ($model->bkgAddInfo->bkg_num_large_bag < 2 || $model->bkgAddInfo->bkg_num_large_bag > 1 )) ? 1 : 0;
		$obj->isAgent				 = (int) ( $model->bkg_agent_id > 0 && $model->bkg_agent_id != 1249) ? 1 : 0;
		$obj->isFlexxi				 = (int) (in_array($model->bkg_flexxi_type, [1, 2]) ) ? 1 : 0;
		$obj->isAssured				 = (int) (in_array($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_id, [5, 6])) ? 1 : 0;
		$obj->isNightPickupIncluded	 = (int) $model->bkgInvoice->bkg_night_pickup_included;
		$obj->isNightDropIncluded	 = (int) $model->bkgInvoice->bkg_night_drop_included;
		$obj->isPaymentDue			 = (int) ($model->bkgInvoice->bkg_due_amount > 0) ? 1 : 0;
		$obj->isDutySlipRequired	 = (int) $model->bkgPref->bkg_duty_slip_required;
		$obj->isDriverAppRequired	 = (int) $model->bkgPref->bkg_driver_app_required;
		return $obj;
	}

	
	/** @var \Booking $model */
	public static function setByModelHornOk($model)
	{
		$obj						 = new Preferences();
		$obj->tripOTP				 = (int) $model->bkgTrack->bkg_trip_otp;
		$obj->isNightPickupIncluded	 = (int) $model->bkgInvoice->bkg_night_pickup_included;
		$obj->isNightDropIncluded	 = (int) $model->bkgInvoice->bkg_night_drop_included;
		$obj->isTollTaxIncluded		 = (int) $model->bkgInvoice->bkg_is_toll_tax_included;
		$obj->isStateTaxIncluded	 = (int) $model->bkgInvoice->bkg_is_state_tax_included;
		$obj->isParkingIncluded		 = (int) $model->bkgInvoice->bkg_is_parking_included;
		$obj->extraParkingCharge	 = (int) $model->bkgInvoice->bkg_parking_charge;
		$obj->extraPerKmCharge		 = $model->bkgInvoice->bkg_extra_km_charge;
		$obj->extraPerMinCharge		 = $model->bkgInvoice->bkg_extra_per_min_charge;
		//$obj->vendorExtraPerKmCharge  = $model->bkgInvoice->bkg_vendor_collected;
		return $obj;
	}
}
