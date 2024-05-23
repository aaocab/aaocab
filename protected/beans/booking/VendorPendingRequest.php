<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\booking;

/**
 * Description of VendorPendingRequest
 *
 * @author Dev
 * 
 * @property string $bidStatus
 * @property string $serviceType
 * @property string $pageCount
 * @property string $pageSize
 * @property string $sort
 * @property string $date
 * @property array  $tiers[]
 * @property string $zones
 * @property string $searchTxt
 * @property string $isGozoNow
 * @property string $dataList
 * @property string $tierList
 */
class VendorPendingRequest
{

	
	public $bidStatus;
	public $serviceType;
	public $pageCount, $pageSize;
	public $sort;
	public $date;
	public $tiers;
	public $zones;
	public $searchTxt;
	public $isGozoNow;
	public $dataList;
	public $tierList;

	public function setData(\stdClass $obj = null)
	{
		$obj->bidStatus		 = (!$this->bidStatus) ? $this->bid_status : $this->bidStatus;
		$obj->serviceType	 = (!$this->serviceType) ? $this->service_type : $this->serviceType;
		$obj->isGozoNow		 = $this->isGozoNow;
		$obj->sort			 = $this->sort;
		$obj->date			 = $this->date;
		$obj->pageCount		 = (!$this->pageCount && $this->pageCount !== 0) ? $this->page_no : $this->pageCount;
		$obj->pageSize		 = (!$this->pageSize) ? $this->page_size : $this->pageSize;
		$obj->searchTxt		 = (!$this->searchTxt) ? $this->search_txt : $this->searchTxt;
		$obj->tiers			 = $this->tiers;
		$obj->zones			 = $this->zones;

		$obj->tierList		 = \Beans\cab\ServiceTier::setTier($this->tiers);
		return $obj;
	}
	
	

	public function getData($result, $vendorId)
	{
		foreach ($result as $res)
		{
			$trip				 = new \Beans\vendor\PendingBookingResponse();
			$this->dataList[]	 = $trip->setTripData($res);
		}
		return $this;
	}

}
