<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fare
 *
 * @author Dev
 * 
 * @property double $baseFare
 * @property double $gstRate
 * @property double $GST
 * @property \Beans\booking\Discount[] $discounts
 * @property \Beans\booking\AdditionalCharge[] $additionalCharges
 * @property \Beans\booking\AddonCharge[] $addons
 * @property \Beans\booking\ExtraChargesRule[] $extraChargesRules
 * @property \Beans\booking\ExtraCharges[] $extraCharges
 * @property double $GST
 * @property integer $totalAmount
 * @property integer $dueAmount
 * @property integer $driverCollected
 * @property double $quotedVA
 * @property double $vendorAmount
 * @property integer $netAdvance
 * @property string \Beans\booking\Transaction[] $transactions
 * @property double $creditLimit
 */

namespace Beans\booking;

class Fare
{

	public $baseFare;
	public $gstRate;
	public $GST;
	public $maxServiceFee;

	/** @var \Beans\booking\Discount[] $discounts */
	public $discounts = null;

	/** @var \Beans\booking\AdditionalCharge[] $additionalCharges */
	public $additionalCharges = null;

	/** @var \Beans\booking\AddonCharge[] $addonCharges */
	public $addonCharges = null;

	/** @var \Beans\booking\ExtraChargesRule[] $extraChargesRules */
	public $extraChargesRules = null;

	/** @var \Beans\booking\ExtraCharges[] $extraCharges */
	public $extraCharges = null;
	public $totalAmount;
	public $dueAmount;
	public $driverToCollected;
	public $quotedVA;
	public $vendorAmount;
	public $netAdvance;
	public $totalAdvanceAmount;

	/** @var \Beans\booking\Transaction $transactions */
	public $transactions = null;
	public $creditLimit	 = 0;

	public static function setData($data)
	{
		/** @var \BookingInvoice $data */
		$obj				 = new Fare();
		$obj->totalAmount	 = (int) $data->bkg_total_amount;
		$obj->dueAmount		 = (int) $data->bkg_due_amount;
		$obj->netAdvance	 = (int) $data->bkg_net_advance_amount;
		$obj->baseFare		 = (int) $data->bkg_base_amount;
		$obj->gstRate		 = (double) $data->bkg_service_tax_rate;
		$obj->GST			 = (double) $data->bkg_service_tax;

		$obj->discounts = \Beans\booking\Discount::setData($data);

		$bkgArr	 = explode(',', $data->bkgIds);
		$bkgId	 = ($data->bkgIds == '') ? $data->bkg_id : $bkgArr[0];

		$invoiceModel	 = \BookingInvoice::model()->getByBookingID($bkgId);
		$status			 = $invoiceModel->bivBkg->bkg_status;

		/** @var \Beans\booking\AdditionalCharge[] $additionalCharges */
		$obj->additionalCharges = \Beans\booking\AdditionalCharge::setByInvoiceModel($invoiceModel, '', $data->bkg_booking_type);

		/** @var \Beans\booking\AddonCharge[] $addonCharges */
		$obj->addonCharges = \Beans\booking\AddonCharge::setByInvoiceModel($invoiceModel);

		/** @var \Beans\booking\ExtraChargesRule[] $extraChargesRules */
		$obj->extraChargesRules = \Beans\booking\ExtraChargesRule::setByInvoiceModel($invoiceModel);

		/** @var \Beans\booking\ExtraCharges[] $extraCharges */
		if(in_array($status, [5, 6, 7]))
		{
			/** @var \Beans\booking\ExtraCharges[] $extraCharges */
			$obj->extraCharges = \Beans\booking\ExtraCharges::setByInvoiceModel($invoiceModel);
		}

		$obj->driverCollected	 = (int) $data->bkg_vendor_actual_collected;
		$obj->quotedVA			 = (int) $data->bkg_quoted_vendor_amount;
		$obj->vendorAmount		 = (int) $data->vendor_ammount;
		if($data->isGozoNow == 1)
		{
			$obj->maxServiceFee = self::getMaxServiceFee($data->vendor_ammount);
		}
		/** @var \Beans\booking\Transaction $transactions */
//		$obj->transactions = [];
		$obj->creditLimit = 0;
		return $obj;
	}

	public static function setStopData($data, $bookingId)
	{
		/** @var \BookingInvoicen $data */
		$data				 = json_decode($data);
		$obj				 = new Fare();
		$obj->totalAmount	 = (int) $data->totalAmount;
		$obj->dueAmount		 = (int) $data->dueAmount;
		$obj->netAdvance	 = (int) $data->netAdvance;
		$obj->baseFare		 = (int) $data->baseFare;
		$obj->gstRate		 = (double) $data->gstRate;
		$obj->GST			 = (double) $data->GST;

		$obj->discounts = \Beans\booking\Discount::setData($data->discounts);

		#$bkgArr	 = explode(',', $data->bkgIds);
		#$bkgId	 = ($data->bkgIds == '') ? $data->bkg_id : $bkgArr[0];
		$bkgId			 = $bookingId;
		$invoiceModel	 = \BookingInvoice::model()->getByBookingID($bkgId);
		$status			 = $invoiceModel->bivBkg->bkg_status;

		/** @var \Beans\booking\AdditionalCharge[] $additionalCharges */
		//$obj->additionalCharges = \Beans\booking\AdditionalCharge::setByInvoiceModel($invoiceModel);

		$obj->additionalCharges = \Beans\booking\AdditionalCharge::setByInput($data->additionalCharges);

		/** @var \Beans\booking\AddonCharge[] $addonCharges */
		$obj->addonCharges = \Beans\booking\AddonCharge::setByInvoiceModel($invoiceModel);

		/** @var \Beans\booking\ExtraChargesRule[] $extraChargesRules */
		$obj->extraChargesRules = \Beans\booking\ExtraChargesRule::setByInvoiceModel($invoiceModel);

		/** @var \Beans\booking\ExtraCharges[] $extraCharges */
		if(in_array($status, [5, 6, 7]))
		{
			/** @var \Beans\booking\ExtraCharges[] $extraCharges */
			//$obj->extraCharges = \Beans\booking\ExtraCharges::setByInvoiceModel($invoiceModel);
			$obj->extraCharges = \Beans\booking\ExtraCharges::setExtraInputCharge($data->extraCharges);
		}

		$obj->driverCollected	 = (int) $data->driverCollected;
		$obj->quotedVA			 = (int) $data->quotedVA;
		$obj->vendorAmount		 = (int) $data->vendorAmount;
		if($data->isGozoNow == 1)
		{
			$obj->maxServiceFee = self::getMaxServiceFee($data->vendor_ammount);
		}
		/** @var \Beans\booking\Transaction $transactions */
//		$obj->transactions = [];
		$obj->creditLimit = 0;
		return $obj;
	}

	/** @var \BookingInvoice $invoiceModel */
	public static function setByInvoiceModel($invoiceModel, $status, $bookingType = null, $tripvendorAmount = 0)
	{
		$obj			 = new Fare();
		$obj->baseFare	 = (int) $invoiceModel->bkg_base_amount;
		$obj->gstRate	 = (double) ($invoiceModel->bkg_service_tax_rate > 0 ? $invoiceModel->bkg_service_tax_rate : 0);
		$obj->GST		 = (double) ($invoiceModel->bkg_service_tax > 0 ? $invoiceModel->bkg_service_tax : 0);

		/** @var \Beans\booking\Discount[] $discounts */
		$obj->discounts			 = \Beans\booking\Discount::setData($invoiceModel);
		$obj->totalAmount		 = (int) $invoiceModel->bkg_total_amount;
		$obj->dueAmount			 = (int) $invoiceModel->bkg_due_amount;
		$obj->driverCollected	 = (int) $invoiceModel->bkg_vendor_actual_collected;
		$obj->quotedVA			 = (int) $invoiceModel->bkg_due_amount; // According to AK sir (18/03/2024)for quick fix this amount is replaced with due amount need to modify after app issue resolved.
		$obj->vendorAmount		 = (int) ($tripvendorAmount==0)?$invoiceModel->bkg_vendor_amount:$tripvendorAmount; // According to AK modify vendor amount to trip vendor amount
		$obj->netAdvance		 = (int) $invoiceModel->bkg_net_advance_amount;
		/** @var \Beans\booking\AdditionalCharge[] $additionalCharges */
		$obj->additionalCharges	 = \Beans\booking\AdditionalCharge::setByInvoiceModel($invoiceModel, '', $bookingType);
//
		/** @var \Beans\booking\AddonCharge[] $addonCharges */
		$obj->addonCharges		 = \Beans\booking\AddonCharge::setByInvoiceModel($invoiceModel);
//
		/** @var \Beans\booking\ExtraChargesRule[] $extraChargesRules */
		$obj->extraChargesRules	 = \Beans\booking\ExtraChargesRule::setByInvoiceModel($invoiceModel);
//
		if(in_array($status, [5, 6, 7]))
		{
			/** @var \Beans\booking\ExtraCharges[] $extraCharges */
			$obj->extraCharges = \Beans\booking\ExtraCharges::setByInvoiceModel($invoiceModel);
		}
		/** @var \Beans\booking\Transaction[] $transactions */
		$obj->transactions = \Beans\booking\Transaction::setByBooking($invoiceModel->biv_bkg_id);
//		$obj->creditLimit = 0;
		return $obj;
	}

	public static function getMaxServiceFee($vendorAmount)
	{
		$qt								 = new \Quote();
		$qt->routeRates					 = new \RouteRates();
		$qt->routeRates->vendorAmount	 = $vendorAmount;
		$margin							 = $qt->routeRates->getGNowRockBottomMargin();
		return $margin;
	}

	/** @var \BookingInvoice $invoiceModel */
	public static function getModel($invoiceModel, $status)
	{
		$obj					 = new Fare();
		$obj->vendorAmount		 = (int) $invoiceModel->bkg_vendor_amount;
		//$obj->totalAdvanceAmount = (int) $invoiceModel->bkg_net_advance_amount;
		$obj->driverToCollected	 = (int) $invoiceModel->bkg_total_amount - $invoiceModel->bkg_advance_amount - $invoiceModel->bkg_credits_used;
		return $obj;
	}

	/**
	 * 
	 * @param \BookingInvoicen $data
	 * @param type $bookingId
	 * @return \Beans\booking\Fare
	 */
	public static function setStop($data, $bookingId)
	{
		/** @var Fare $obj */
		$obj = new Fare();

		$bkgModel	 = \Booking::model()->findByPk($bookingId);
		$status		 = $bkgModel->bkg_status;

		$obj->additionalCharges = \Beans\booking\AdditionalCharge::setByInputData($data->additionalCharges);

		/** @var \Beans\booking\ExtraCharges[] $extraCharges */
		if(in_array($status, [5, 6, 7]))
		{
			/** @var \Beans\booking\ExtraCharges[] $extraCharges */
			$obj->extraCharges = \Beans\booking\ExtraCharges::setExtraInputCharge($data->extraCharges);
		}

		$obj->driverCollected = (int) $data->driverCollected;

		$obj->vendorAmount = (int) $data->vendorAmount;

		//$obj->creditLimit = 0;
		return $obj;
	}

	public static function getUpdateModel($invoiceModel, $status, $cabModel)
	{
		$obj					 = new Fare();
		$obj->vendorAmount		 = (int) $cabModel->bcb_vendor_amount;
		$obj->totalAdvanceAmount = (int) $invoiceModel->bkg_net_advance_amount;
		$obj->driverCollected	 = (int) $invoiceModel->bkg_vendor_actual_collected;
		return $obj;
	}

	/**
	 * 
	 * @param integer $totalAmount
	 * @param integer $vendorAmount
	 * @return \Beans\booking\Fare
	 */
	public static function setDataForOffer($totalAmount, $vendorAmount)
	{
		$obj				 = new \Beans\booking\Fare();
		$obj->totalAmount	 = (int) $totalAmount;
		$obj->vendorAmount	 = (int) $vendorAmount;
		unset($obj->creditLimit);
		return \Filter::removeNull($obj);
	}
}
