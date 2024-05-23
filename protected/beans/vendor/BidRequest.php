<?php

namespace Beans\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of BidRequest
 *
 * @author Dev
 * @property integer $bkgId
 * @property integer $bcbId
 * @property integer $vendorId
 * @property integer $vendorBidAmt
 * @property string $vendorBidDate
 * @property string $bookingPickupDate
 * @property string $rowIdentifier
 * @property integer $tollTax
 * @property integer $stateTax
 * @property integer $tripDistance
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
	public static function setData($bkgId, $bcbId, $vendorId, $vendorBidAmt, $bookingPickupDate, $tollTax, $stateTax, $tripDistance, $rowIdentifier)
	{
		$obj					 = new BidRequest();
		$obj->bkgId				 = $bkgId;
		$obj->bcbId				 = $bcbId;
		$obj->vendorId			 = $vendorId;
		$obj->vendorBidAmt		 = $vendorBidAmt > 0 ? $vendorBidAmt : 0;
		$obj->vendorBidDate		 = date('Y-m-d H:i:s');
		$obj->bookingPickupDate	 = $bookingPickupDate;
		$obj->rowIdentifier		 = $rowIdentifier > 0 ? $rowIdentifier : null;
		$obj->tollTax			 = $tollTax > 0 ? $tollTax : 0;
		$obj->stateTax			 = $stateTax > 0 ? $stateTax : 0;
		$obj->tripDistance		 = $tripDistance > 0 ? $tripDistance : 0;
		return \Filter::removeNull($obj);
	}
}
