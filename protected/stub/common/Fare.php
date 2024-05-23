<?php

namespace Stub\common;

class Fare
{

	public $baseFare, $driverAllowance, $extraPerMinCharge, $gst, $gstRate, $tollIncluded, $stateTaxIncluded, $stateTax, $vendorAmount, $vendorCollected;
	public $tollTax, $nightPickupIncluded, $nightDropIncluded, $parkingCharge, $parkingIncluded;
	public $additionalAmount, $discount, $extraPerKmRate, $serviceTax, $advanceReceived, $customerPaid, $dueAmount, $totalAmount, $gozoCoins, $promoCoins;
	public $amount, $minPay, $minPayPercent, $credit, $netBaseFare, $airportChargeIncluded, $airportEntryFee;
	public $additionalChargeRemark, $additionalCharge, $time_cap, $extra_time_rate;
	public $minBaseFare, $maxBaseFare, $minVendorAmount, $maxVendorAmount, $minTotalAmount, $maxTotalAmount, $advanceSlab, $slab, $promo;
	public $promos;

	/** @var \Beans\transaction\AdvanceSlabs $advanceSlabs */
	public $advanceSlabs;

	public function getData(\BookingInvoice $model = null, $bkgModel = null)
	{
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}
		$model->bkg_corporate_credit = $this->advanceReceived;
		$model->bkg_advance_amount	 = $this->advanceReceived;
		$model->bkg_total_amount	 = $this->totalAmount;

		/**
		 * Checking for Spice Jet for excluding all taxes
		 */
		$spiceId	 = \Config::get('spicejet.partner.id');
		$sugerboxId	 = \Config::get('sugerbox.partner.id');
		if (!empty($bkgModel) && ($spiceId == $bkgModel->bkg_agent_id || $sugerboxId == $bkgModel->bkg_agent_id) && in_array($bkgModel->bkg_booking_type, [4, 12]))
		{
			$model->bkg_is_airport_fee_included	 = 0;
			$model->bkg_airport_entry_fee		 = 0;
			$model->bkg_is_toll_tax_included	 = 0;
			$model->bkg_toll_tax				 = 0;
			$model->bkg_is_state_tax_included	 = 0;
			$model->bkg_state_tax				 = 0;
			$model->bkg_is_parking_included		 = 0;
			$model->bkg_parking_charge			 = 0;
			$model->bkg_night_pickup_included	 = 0;
			$model->bkg_night_drop_included		 = 0;
		}
		return $model;
	}

	/**
	 *
	 * @param \RouteRates $rates
	 */
	public function setQuoteData(\RouteRates $rates, $quote = null)
	{
		$staxrate					 = \BookingInvoice::getGstTaxRate($quote->partnerId, $quote->tripType);
		$this->baseFare				 = (int) $rates->getGrossBaseAmount();
		$this->netBaseFare			 = (int) $rates->getNetBaseAmount();
		$this->discount				 = (int) $rates->discount;
		$this->gst					 = (float) $rates->gst;
		$this->gstRate				 = $staxrate;
		$this->driverAllowance		 = (int) $rates->driverAllowance;
		$this->airportChargeIncluded = (int) $rates->isAirportEntryFeeIncluded;
		$this->airportEntryFee		 = (int) $rates->airportEntryFee;
		$this->tollIncluded			 = (int) $rates->isTollIncluded;
		$this->stateTaxIncluded		 = (int) $rates->isStateTaxIncluded;
		$this->stateTax				 = (int) $rates->stateTax;
		$this->tollTax				 = (int) $rates->tollTaxAmount;
		$this->nightPickupIncluded	 = (int) $rates->isNightPickupIncluded;
		$this->nightDropIncluded	 = (int) $rates->isNightDropIncluded;
		$this->extraPerKmRate		 = (float) $rates->ratePerKM;
		$this->extraPerMinCharge	 = (int) $rates->extraPerMinCharge;
		$this->vendorAmount			 = (int) $rates->vendorAmount;
		$this->totalAmount			 = (int) $rates->totalAmount;
		$this->gozoCoins			 = (int) ($rates->gozoCoins > 0) ? $rates->gozoCoins : 0;
		$this->promoCoins			 = $rates->coinDiscount;
		$this->promo				 = $rates->promoRow['prm_code'];

//		if ($rates->discount > 0)
//		{
//			if ($rates->promoRow['prm_id'] > 0)
//			{
//				$promoObj		 = new \Stub\common\PromoDetails();
//				$promoData[]	 = $promoObj->setDetails($rates->promoRow);
//				$this->promos	 = $promoData;
//			}
//		}

		/**
		 * Checking for Spice Jet for excluding all taxes
		 */
		$spiceId	 = \Config::get('spicejet.partner.id');
		$sugerboxId	 = \Config::get('sugerbox.partner.id');
		if (!empty($quote) && ($spiceId == $quote->partnerId || $sugerboxId == $quote->partnerId) && in_array($quote->tripType, [4, 12]))
		{
			$this->airportChargeIncluded = $rates->isAirportEntryFeeIncluded;
			$this->airportEntryFee		 = $rates->airportEntryFee;
			$this->tollIncluded			 = 0;
			$this->tollTax				 = 0;
			$this->stateTaxIncluded		 = 0;
			$this->stateTax				 = 0;
			$this->parkingIncluded		 = $rates->isParkingIncluded;
			$this->parkingCharge		 = 0;
			$this->nightPickupIncluded	 = 0;
			$this->nightDropIncluded	 = 0;
		}
	}

	/**
	 *
	 * @param \RouteRates $rates
	 */
	public function setGNowQuoteRates(\RouteRates $rates)
	{
		$this->minBaseFare		 = (int) $rates->minBaseAmount;
		$this->maxBaseFare		 = (int) $rates->maxBaseAmount;
		$this->minVendorAmount	 = (int) $rates->minVendorAmount;
		$this->maxVendorAmount	 = (int) $rates->maxVendorAmount;
		$this->minTotalAmount	 = (int) $rates->minTotalAmount;
		$this->maxTotalAmount	 = (int) $rates->maxTotalAmount;
	}

	/**
	 *
	 * @param \RouteRates $rates
	 * @param boolean $isReturn
	 * @return $this
	 */
	public function setQuoteRates(\RouteRates $rates, $isReturn = false, $quote = null)
	{
		$this->setQuoteData($rates, $quote);
		if ($isReturn == true)
		{
			return $this;
		}
	}

	/**
	 *
	 * @param \RouteRates $rates
	 * @return boolean
	 */
	public function setDiscountedQuoteRates(\RouteRates $rates)
	{
		if ($rates->discount == null)
		{
			return false;
		}
		$this->discount = (int) $rates->discount;
		if ($rates->promoRow['prm_id'] > 0)
		{
			$promoObj		 = new \Stub\common\PromoDetails();
			$promoData[]	 = $promoObj->setDetails($rates->promoRow);
			$this->promos	 = $promoData;
		}
		//$promoDiscount	 = $rates->discount;
		$rates->calculateTotal();
		$this->setQuoteData($rates);
	}

	public function setInvoiceData(\BookingInvoice $bkgInvoice)
	{
		$model						 = \Booking::model()->findByPk($bkgInvoice->biv_bkg_id);
		$carmodel					 = $model->bkgSvcClassVhcCat;
		$sccId						 = $carmodel->scc_ServiceClass->scc_id;
		$obj						 = $this;
		$obj->baseFare				 = (int) $bkgInvoice->bkg_base_amount;
		$obj->netBaseFare			 = (int) ($bkgInvoice->bkg_base_amount - $bkgInvoice->bkg_discount_amount);
		$obj->gst					 = (double) ($bkgInvoice->bkg_service_tax > 0 ? $bkgInvoice->bkg_service_tax : 0);
		//$obj->gstRate				 = ($bkgInvoice->bkg_cgst > 0 && $bkgInvoice->bkg_sgst > 0) ? (int) ($bkgInvoice->bkg_cgst + $bkgInvoice->bkg_sgst) : $bkgInvoice->bkg_igst;
		$rate						 = ($bkgInvoice->bkg_service_tax_rate > 0 ) ? $bkgInvoice->bkg_service_tax_rate : 0;
		$obj->gstRate				 = (double) $rate;
		$obj->driverAllowance		 = (int) $bkgInvoice->bkg_driver_allowance_amount | 0;
		$obj->airportEntryFee		 = (int) $bkgInvoice->bkg_airport_entry_fee;
		// @deprecated airportFee
		$obj->airportFee			 = (int) $bkgInvoice->bkg_airport_entry_fee;
		$obj->airportChargeIncluded	 = (int) $bkgInvoice->bkg_is_airport_fee_included;
		$obj->tollIncluded			 = (int) $bkgInvoice->bkg_is_toll_tax_included;
		$obj->stateTaxIncluded		 = (int) $bkgInvoice->bkg_is_state_tax_included;
		$obj->tollTax				 = (int) $bkgInvoice->bkg_toll_tax;
		$obj->stateTax				 = (int) $bkgInvoice->bkg_state_tax;
		$obj->nightPickupIncluded	 = (int) $bkgInvoice->bkg_night_pickup_included;
		$obj->nightDropIncluded		 = (int) $bkgInvoice->bkg_night_drop_included;
		$obj->gozoCoins				 = (int) $bkgInvoice->bkg_credits_used;
		$obj->extraPerKmRate		 = (float) $bkgInvoice->bkg_rate_per_km_extra;
		$obj->extraPerMinCharge		 = (int) $bkgInvoice->bkg_extra_per_min_charge;
		$obj->discount				 = (int) $bkgInvoice->bkg_discount_amount;
		$obj->totalAmount			 = (int) $bkgInvoice->bkg_total_amount;
		$obj->addOnCharge			 = (int) $bkgInvoice->bkg_addon_charges;
		$obj->parkingCharge			 = (int) $bkgInvoice->bkg_parking_charge;
		$obj->parkingIncluded		 = (int) $bkgInvoice->bkg_is_parking_included;
		//$minimunPay				 =  round(($bkgInvoice->bkg_total_amount * 15) / 100);
		$minimunPay					 = $bkgInvoice->calculateMinPayment();
		$obj->minPay				 = (int) $minimunPay;
		$obj->minPayPercent			 = \Config::getMinAdvancePercent($bkgInvoice->bivBkg->bkg_agent_id, $bkgInvoice->bivBkg->bkg_booking_type, $bkgInvoice->bivBkg->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgInvoice->bivBkg->bkgPref->bkg_is_gozonow);

		$dueAmount		 = in_array($model->bkg_status, [2, 3, 5, 6, 7]) ? $bkgInvoice->bkg_due_amount : ($bkgInvoice->bkg_total_amount - $bkgInvoice->bkg_net_advance_amount);
		$obj->dueAmount	 = (int) $dueAmount;

//		$minPerc				 = \Config::getMinAdvancePercent($bkgInvoice->bivBkg->bkg_agent_id, $bkgInvoice->bivBkg->bkg_booking_type, $bkgInvoice->bivBkg->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgInvoice->bivBkg->bkgPref->bkg_is_gozonow);
//		$maxPaymentWithDiscount	 = round($bkgInvoice->bkg_total_amount) - $bkgInvoice->getAdvanceReceived();
//		$defaultAmount			 = ($bkgInvoice->bkg_advance_amount > 0) ? $maxPaymentWithDiscount : $minimunPay;
//		$arrPartPayPercent		 = array_unique([$minPerc, 50, 100]);
//		$paymentOptions			 = \Config::get('payment.setting');
//		$arrPaymentOptions		 = json_decode($paymentOptions, true);
//		foreach ($arrPartPayPercent as $key => $value)
//		{
//			$key				 = array_search($value, array_column($arrPaymentOptions, 'percentage'));
//			$payOption			 = $arrPaymentOptions[$key];
//			$arr['percentage']	 = $payOption['percentage'];
//			$arr['value']		 = round($maxPaymentWithDiscount * $payOption['percentage'] / 100);
//			$arr['label']		 = $payOption['label'] . " (" . $payOption['percentage'] . "%)";
//			$paymentLevels[]	 = $arr;
//		}
//		$obj->advanceSlab = $paymentLevels;
//		if ($bkgInvoice->bivBkg->bkg_agent_id == null)
//		{
		$obj->customerPaid	 = (int) ($bkgInvoice->bkg_advance_amount - ($bkgInvoice->bkg_refund_amount > 0 ? $bkgInvoice->bkg_refund_amount : 0));
		$obj->promoCoins	 = (int) $bkgInvoice->bkg_promo1_coins;

		$promoObj = new \Stub\common\PromoDetails();

		$promoObj->setModelData($bkgInvoice->bivPromos);
		$this->promos[] = $promoObj;

		if ($obj->advanceSlab == null)
		{
			$obj->advanceSlab	 = \BookingInvoice::getSlabs($bkgInvoice, $obj->slab, $obj->walletUsed);
			$obj->slab			 = null;
		}
//		}
		/* if ($bkgInvoice->bivBkg->bkg_agent_id == 18190 || $bkgInvoice->bivBkg->bkg_agent_id == 3936)
		  {
		  $obj->totalAmount = (int)($bkgInvoice->bkg_total_amount - $bkgInvoice->bkg_advance_amount);
		  } */

		$obj->additionalChargeRemark = $bkgInvoice->bkg_additional_charge_remark;
		$timeDiff					 = json_decode(\Config::get("dayRental.timeSlot"));

		#$obj->extraTimeCap						 = $timeDiff->$svcClassId;
		$obj->extraTimeCap		 = $bkgInvoice::calculateExtraTimeCap($sccId);
		$obj->additionalCharge	 = (int) $bkgInvoice->bkg_additional_charge;
		$obj->extraPerMinRate	 = (int) ($bkgInvoice->bkg_extra_per_min_charge == NULL ? 0 : $bkgInvoice->bkg_extra_per_min_charge);
	}

	/** 		$promoDiscount	 = $rates->discount;
	 *
	 * @param \BookingInvoice $bkgInvoice
	 */
	public function setData(\BookingInvoice $bkgInvoice)
	{
		$this->setInvoiceData($bkgInvoice);
		$this->vendorAmount		 = (int) $bkgInvoice->bkg_vendor_amount;
		$this->vendorCollected	 = (int) $bkgInvoice->bkg_vendor_collected;
		return $this;
	}

	public function setDiscountedData(\BookingInvoice $bkgInvoice)
	{
		$promoDiscount = (int) $bkgInvoice->bkg_discount_amount;
		if ($promoDiscount == null)
		{
			return false;
		}
		$this->discount = $promoDiscount;
		$bkgInvoice->calculateTotal();
		$this->setData($bkgInvoice);
	}

	/**
	 *
	 * @param \BookingInvoice $bkgInvoice
	 * @return $this
	 * @deprecated
	 */
	public function setBasicData($bkgInvoice)
	{
		$this->baseFare			 = (int) $bkgInvoice->base_amount;
		$this->serviceTax		 = (int) $bkgInvoice->service_tax;
		$this->driverAllowance	 = (int) $bkgInvoice->driver_allowance;

		$this->dueAmount	 = (int) $bkgInvoice->due_amount;
		$this->totalAmount	 = (int) $bkgInvoice->total_amount;
		$this->minPay		 = (int) $bkgInvoice->minPayable;
	}

	/**
	 * 
	 * @param \BookingInvoice $bkgInvoice
	 * @param \Beans\common\WalletPayment $paymentSlab
	 */
	public function setWalletData(\BookingInvoice $bkgInvoice, \Beans\transaction\AdvanceSlabs $advanceSlab = null, $walletUsed = 0)
	{
		$this->walletUsed	 = $walletUsed;
		$this->slab			 = $advanceSlab;
		if (in_array($bkgInvoice->bivBkg->bkg_status, [2, 3, 5, 6, 7]))
		{
			$bkgInvoice->refresh();
		}
		$this->setData($bkgInvoice);
//		$bkgInvoice->calculateTotal();
		$this->gozoCoins	 = (int) $bkgInvoice->getAppliedGozoCoins();
		$this->dueAmount	 = (int) $bkgInvoice->bkg_due_amount;
		$this->netBaseFare	 = (int) ($bkgInvoice->bkg_base_amount - $bkgInvoice->bkg_discount_amount);
	}

	/**
	 * 
	 * @param \BookingInvoice $bkgInvoice
	 */
	public function setPromotionData(\BookingInvoice $bkgInvoice)
	{
		$this->setData($bkgInvoice);
//		$useGozoCredits = ($bkgInvoice->bkg_temp_credits >0) ? $bkgInvoice->bkg_temp_credits :  $creditUsed;
//		$this->gozoCoins = (int) $useGozoCredits;
//		$this->dueAmount = (int) ($bkgInvoice->bkg_total_amount - $bkgInvoice->bkg_advance_amount - $useGozoCredits);
		$this->gozoCoins	 = (int) $bkgInvoice->getAppliedGozoCoins();
		$this->dueAmount	 = (int) $bkgInvoice->bkg_due_amount;
		$this->netBaseFare	 = (int) ($bkgInvoice->bkg_base_amount - $bkgInvoice->bkg_discount_amount);
	}

	/**
	 *
	 * @param \BookingInvoice $bkgInvoice
	 * @return $this
	 */
	public function setInvoicePromoData(\BookingInvoice $bkgInvoice)
	{
		$this->setInvoiceData($bkgInvoice);
		$this->netBaseFare = (int) ($bkgInvoice->bkg_base_amount - $bkgInvoice->bkg_discount_amount);
	}

	/**
	 *
	 * @param \BookingInvoice $bkgInvoice
	 * @return $this
	 */
	public function setInvoiceCreditData(\BookingInvoice $bkgInvoice, $result)
	{
		$this->setData($bkgInvoice);
		$this->gozoCoins = (int) $bkgInvoice->bkg_temp_credits;
		$this->dueAmount = (int) ($bkgInvoice->bkg_total_amount - $bkgInvoice->bkg_advance_amount - $bkgInvoice->bkg_temp_credits);

		$this->stateTax		 = (int) $bkgInvoice->bkg_state_tax;
		$this->netBaseFare	 = (int) ($bkgInvoice->bkg_base_amount - $result->credits_used);
	}

	public function setShuttleData($quote)
	{
		$this->baseFare			 = (int) $quote['slt_base_fare'];
		$this->gst				 = (float) $quote['slt_gst'];
		$this->driverAllowance	 = (int) $quote['slt_driver_allowance'];
		$this->stateTax			 = (int) $quote['slt_state_tax'];
		$this->tollTax			 = (int) $quote['slt_toll_tax'];
		$this->vendorAmount		 = (int) $quote['slt_vendor_amount'];
		$this->totalAmount		 = (int) $quote['slt_price_per_seat'];
	}

	public function setShuttleRates($quote)
	{
		$this->setShuttleData($quote);
		if ($isReturn == true)
		{
			return $this;
		}
	}

	public function getFbgData(\BookingInvoice $model = null)
	{
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}
		$model->bkg_corporate_credit		 = $this->advanceReceived;
		$model->bkg_advance_amount			 = $this->advanceReceived;
		$model->bkg_total_amount			 = $this->totalAmount;
		$model->bkg_toll_tax				 = $this->tollTax;
		$model->bkg_is_toll_tax_included	 = $this->tollIncluded;
		$model->bkg_is_state_tax_included	 = $this->stateTaxIncluded;
		$model->bkg_state_tax				 = $this->stateTax;
		$model->bkg_driver_allowance_amount	 = $this->driverAllowance;
		$model->bkg_parking_charge			 = $this->parkingCharge;
		$model->bkg_is_parking_included		 = $this->parkingIncluded;
		$model->bkg_rate_per_km_extra		 = $this->extraPerKmRate;
		$model->bkg_extra_per_min_charge	 = $this->extraPerMinCharge;
		$model->bkg_vendor_amount			 = $this->vendorAmount; //round($this->totalAmount * 0.75);
		//GST reverse Cal
		$userInfo							 = \UserInfo::getInstance();
		$staxrate							 = \BookingInvoice::getGstTaxRate($userInfo->userId, $model->bivBkg->bkg_booking_type);
		$gst								 = round($this->baseFare * $staxrate * 0.01);
		$model->bkg_base_amount				 = round($this->baseFare - $gst);
		$model->bkg_service_tax				 = $gst;
		return $model;
	}

	public function getTemp($param)
	{
		
	}

}
