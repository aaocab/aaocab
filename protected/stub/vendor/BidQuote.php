<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BidQuote
{

	public $id;
	public $amount;
	public $remarks;
	public $isInterested;
	public $minBidAmount;
	public $bidDifference;
	public $vendorRating;
	public $vendorBid;
	public $vendorRanking;
	public $vendorScore;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = new \VendorQuote();
		}
		$model->vqt_cqt_id		 = $this->id;
		$model->vqt_vendor_id	 = \UserInfo::getEntityId();
		$model->isInterested	 = $this->isInterested;
		$model->vqt_amount		 = trim($this->amount);
		$model->vqt_description	 = $this->remarks;
		return $model;
	}

	public function setBidRank($data)
	{

		$this->minBidAmount	 = $data['bvr_min_bid_amount'];
		$this->bidDifference = ($data['bvr_bid_amount'] - $data['bvr_min_bid_amount']);
		$this->vendorRating	 = $data['bvr_vendor_rating'];
		$this->vendorBid	 = $data['bvr_bid_amount'];
		$this->vendorRanking = $data['bvr_rank'];
		$this->vendorScore	 = $data['bvr_score'];
	}

}
