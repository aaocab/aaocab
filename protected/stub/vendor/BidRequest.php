<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BidRequest
{

	public $bkgId;
	public $bcbId;
	public $vendorId;
	public $vendorBidAmt;
	public $vendorBidDate;
	public $bookingPickupDate;
	public $rowIdentifier;
	public $tollTax;
	public $stateTax;
	public $tripDistance;

	/** @var VendorBid Request */
	public function setData($bkgId, $bcbId, $vendorId, $vendorBidAmt, $bookingPickupDate, $tollTax, $stateTax, $tripDistance, $rowIdentifier)
	{
		$this->bkgId			 = $bkgId;
		$this->bcbId			 = $bcbId;
		$this->vendorId			 = $vendorId;
		$this->vendorBidAmt		 = $vendorBidAmt > 0 ? $vendorBidAmt : 0;
		$this->vendorBidDate	 = date('Y-m-d H:i:s');
		$this->bookingPickupDate = $bookingPickupDate;
		$this->rowIdentifier	 = $rowIdentifier > 0 ? $rowIdentifier :null;
		$this->tollTax			 = $tollTax > 0 ? $tollTax : 0;
		$this->stateTax			 = $stateTax > 0 ? $stateTax : 0;
		$this->tripDistance		 = $tripDistance > 0 ? $tripDistance : 0;
		return $this;
	}

}
