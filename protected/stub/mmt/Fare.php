<?php

namespace Stub\mmt;

class Fare
{

	public $base_fare, $per_km_charge, $per_km_extra_charge, $per_extra_min_charge, $driver_charge_per_day, $is_refundable, $approx_distance, $total_days_charged;
	public $service_tax_percent, $service_tax, $gst_percent, $gst, $total_amount, $is_all_inclusive, $is_service_tax_paid_by_customer, $min_km_per_day;
	public $state_tax, $total_driver_charges, $total_fare, $amount_to_be_collected, $seller_discount;

	/** @var \Stub\mmt\ExtraCharges $extraCharges */
	//public $extraCharges;
    
    /** @var \Stub\mmt\ExtraCharges $extra_charges */
	public $extra_charges;

	/** @var \Stub\mmt\ExtraTimeFare $extra_time_fare */
	public $extra_time_fare;

	public function getData(\BookingInvoice $model = null)
	{
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}
		$model->bkg_corporate_credit = $this->advanceReceived;
        $model->bkg_total_amount	 = $this->totalAmount;
		return $model;
	}

	/**
	 * 
	 * @param \Quote $quote
	 */
	public function setQuoteData($quote)
	{
		$rates = $quote->routeRates;
        $route = $quote->routes[0];
		
		$serviceTaxRate = \BookingInvoice::getGstTaxRate($quote->partnerId, $quote->tripType);
		$staxRate = (1 + ($serviceTaxRate / 100));
		
		$this->base_fare             = (int) $rates->baseAmount + round($rates->gst);
        $this->driver_charge_per_day = (int) ($quote->priceRule->prr_day_driver_allowance > 0 ? $quote->priceRule->prr_day_driver_allowance : $quote->priceRule->prr_night_driver_allowance);
        $this->seller_discount       = round($quote->routeRates->discount * $staxRate);
		//$this->per_extra_min_charge     = $quote->routeRates->extraPerMinCharge;

		//raental we are sending this node
		if (in_array($quote->tripType, [9, 10, 11]))
		{
			$svcModelCat			 = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $quote->skuId);
			$applicableTime			 = \BookingInvoice::calculateExtraTimeCap($svcModelCat->scc_id);
			$this->extra_time_fare	 = new ExtraTimeFare();
			$this->extra_time_fare->setExtraTimeFare($quote->routeRates->extraPerMinCharge, $applicableTime);
		}

        $costPerKm	 = $rates->costPerKM;
        $fixedRateKm=  \GoMmt::getFixedRate($route->brtFromCity->cty_id, $quote->tripType, $quote->cabType);
        if($fixedRateKm > 0)
        {
            $ratePerKM = $fixedRateKm;
        }
        else
        {
			$ratePerKM = ($quote->tripType == 12) ? $rates->ratePerKM : (ceil($rates->ratePerKM * 1.02 * 1.11 * 1.06 * $staxRate * 2) / 2);
        }
		
		//$ratePerKM = $rates->addMarkupCostPerKm();

		if($ratePerKM == 0 && $rates->ratePerKM > 0)
		{
            $ratePerKM = $rates->ratePerKM;
		}

		$this->min_km_per_day		 = (int) $quote->priceRule->prr_min_km_day;
		$this->per_km_charge		 = (float) $ratePerKM;
		$this->per_km_extra_charge	 = (float) $ratePerKM;

		$this->total_days_charged = $quote->routeDuration->calendarDays;

		$this->total_driver_charges = (float) $rates->driverAllowance;
		if ($quote->tripType == 1)
		{
			$this->base_fare			 += $this->total_driver_charges;
			$this->total_driver_charges	 = 0;
		}

		//$this->state_tax = (float) $rates->stateTax;

		$this->extra_charges = new ExtraCharges();
		$this->extra_charges->setExtraCharges($quote);
	}

	/**
	 * 
	 * @param \Booking $model
	 */
	public function setInvoiceData($model)
	{

		$this->approx_distance	 = (int) $model->bkg_trip_distance;
		$this->gst				 = (float) $model->bkgInvoice->bkg_service_tax;
		$this->gst_percent		 = (float) $model->bkgInvoice->bkg_service_tax_rate;
		$this->total_amount		 = $model->bkgInvoice->bkg_total_amount;
	}
    
    public function populateDataOLD(\BookingInvoice $model = null, $from, $to)
	{
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}
        //GST reverse Cal
        $gst = round($this->base_fare * 0.05);
		$model->bkg_base_amount = round($this->base_fare - $gst);
        $model->bkg_service_tax = $gst;
        $model->bkg_rate_per_km_extra	 = $this->per_km_extra_charge;
        $model->bkg_total_amount = $this->total_fare;
        //$model->bkg_advance_amount = $this->total_fare - $this->amount_to_be_collected;
		$model->bkg_vendor_amount  = round($this->total_fare * 0.75);
        $this->extra_charges->getExtraCharges($model, $from, $to);
		$model->bkg_driver_allowance_amount = $this->total_driver_charges;
		return $model;
	}
/** 
 *  */
	public function populateData(\BookingInvoice $model = null, $from, $to, $partnerId, $tripType)
	{
		/** @var BookingInvoice $model */
		if ($model == null)
		{
			$model = new \BookingInvoice();
		}
		//GST reverse Cal
		$staxRate = \BookingInvoice::getGstTaxRate($partnerId, $tripType);

		$gst							 = round($this->base_fare * $staxRate * 0.01);
		//$driverAllowenceGst				 = round($this->total_driver_charges * $staxRate * 0.01);
		$model->bkg_base_amount			 = round($this->base_fare - $gst);
		$model->bkg_service_tax			 = $gst; // + $driverAllowenceGst;
		$model->bkg_rate_per_km_extra	 = $this->per_km_extra_charge;
		$model->bkg_total_amount		 = $this->total_fare;
		//$model->bkg_advance_amount = $this->total_fare - $this->amount_to_be_collected;
		$model->bkg_vendor_amount		 = round($this->total_fare * 0.75);
		$this->extra_charges->getExtraCharges($model, $from, $to);
		//$model->bkg_driver_allowance_amount = round($this->total_driver_charges - $driverAllowenceGst);
		return $model;
	}

}
