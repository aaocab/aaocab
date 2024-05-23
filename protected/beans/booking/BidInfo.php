<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BidInfo
 *
 * @author Dev
 * 
 * @property double $smtScore
 * @property integer $maxBidAmount
 * @property integer $minBidAmount
 * @property integer $previousBidAmount
 * @property integer $acceptAmount
 * @property integer $recommendedAmount
 * 
 */

namespace Beans\booking;

class BidInfo
{

	public $smtScore;
	public $maxBidAmount;
	public $minBidAmount;
	public $previousBidAmount;
	public $acceptAmount;
	public $recommendedAmount;
	public $bidAlertMsg;
	public $bidRange;

	public function setData($data)
	{
		
		$this->smtScore			 = (double) $data->smtScore;
		$this->maxBidAmount		 = (int) $data->max_bid_amount;
		$this->minBidAmount		 = (int) $data->min_bid_amount;
		$this->previousBidAmount = (int) $data->bvr_bid_amount;
		$this->acceptAmount		 = (int) $data->acptAmount;
		$this->recommendedAmount = (int) $data->recommended_vendor_amount;
//		$this->bidRange			 = \Filter::convertToObject(\BookingVendorRequest::getBidRange($data->quoteVendorAmt));
		if($data->bkg_booking_type==2 || $data->bkg_booking_type==3)
		{
			$this->bidAlertMsg = "Customers have the freedom to enhance their journey by modifying their route or including new locations, cities or sightseeing spots or local attractions during the ride within the designated timeframe. Customers will not be charged extra for any travel within the quoted distance. If the total distance exceed the initial quoted distance, an extra km charge will be applied.";
		}
		if ($data->is_biddable == 0)
		{
			$recommended_vendor_amount = ( $data->acptAmount > 0 ? $data->acptAmount : $data->recommended_vendor_amount);
		}
		else
		{
			$recommended_vendor_amount = $data->recommended_vendor_amount;
		}
		$getBidRange = \BookingVendorRequest::getBidRange($recommended_vendor_amount, $data->maxAllowableVendorAmount);
		if (!empty($getBidRange))
		{
			$this->bidRange = $getBidRange;
		}
	}

	public function setAcceptAmount($data)
	{
		$this->acceptAmount = (int) ($data->acptAmount) ? $data->acptAmount : $data->acceptAmount;
	}

	public function setRecommendedAmount($data)
	{
		$this->recommendedAmount = (int) $data->recommended_vendor_amount;
	}

}
