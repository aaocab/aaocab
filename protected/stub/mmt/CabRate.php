<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CabRate
{

	/** @var \Stub\mmt\Cab $cab */
	public $cab;

	/** @var \Stub\mmt\Fare $fare */
	public $fare;
	
	public function setQuote(\Quote $quote, $showModel = false)
	{
		
		$this->cab	 = new Cab();
		$this->cab->setCabType($quote->cabType, false, $showModel);
		
		$this->fare	 = new Fare();
		$this->fare->setQuoteRates($quote->routeRates);
		$this->discountedFare = new Fare();
		$this->discountedFare->setDiscountedQuoteRates($quote->routeRates);
		
	}

	/**
	 * This function is used for setting the cab type and fare container
	 * 
	 * @param type $cabId = scvId
	 * @param type $bkgInvoice = bkg Invoice
	 */
	public function setModelData($cabId, $bkgInvoice)
	{
		$this->cab	 = new Cab();
		$this->cab->setCabType($cabId);
		$this->fare	 = new Fare();
		$this->fare->setData($bkgInvoice);
	}

}
