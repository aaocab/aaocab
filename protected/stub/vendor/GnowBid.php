<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GnowBid
{

	public $dataList;
	public $bidAmount;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Vehicle $cab */
	public $cab;
	public $reachingAfterMinutes;
	public $reachingAt;
	public $tripId, $id;
	public $bidStatus;

	/** @var \Stub\common\Booking $booking */
	public $bookings;

	public function fillData($row)
	{
		$this->bidAmount			 = (int) $row['bidAmount'];
		$this->driver				 = new \Stub\common\Driver();
		$this->cab					 = new \Stub\common\Vehicle();
		$this->cab->fillData($row['cab']);
		$this->reachingAfterMinutes	 = $row['reachingAfterMinutes'];
		$this->tripId				 = $row['tripId'];
	}

	public function setData()
	{
		$obj						 = new \stdClass();
		$obj->bidAmount				 = $this->bidAmount;
		$obj->tripId				 = $this->tripId;
		$obj->driverId				 = $this->driver->id;
		$obj->driverMobile			 = $this->driver->primaryContact->code . $this->driver->primaryContact->number;
		$obj->cabId					 = $this->cab->id;
		$obj->reachingAfterMinutes	 = $this->reachingAfterMinutes;
		return $obj;
	}

	public function getBidList($data)
	{
		foreach ($data as $v)
		{
			$obj				 = new \Stub\vendor\GnowBid();
			$obj->getBidData($v);
			$this->dataList[]	 = $obj;
		}
	}

	public function getBidData($val)
	{
		$bidStatusList	 = [0 => 'Offer made to customer', 1 => ''];
		$this->id		 = (int) $val['bkg_bcb_id'];
		$this->bidAmount = (int) $val['bvr_bid_amount'];
		$this->gnowBid = (int) $val['bvr_is_gozonow'];
		$bkgStatus		 = $val['bkg_status'];
		$bidStatus		 = 2;
		if ($bkgStatus == 2)
		{
			$bidStatus = ($this->bidAmount > 0 && $this->gnowBid==1) ? 0 : 3;
		}

		if (in_array($bkgStatus, [3, 5, 6, 7]) && $this->bidAmount > 0 && $val['bcb_vendor_id'] == $val['bvr_vendor_id'])
		{
			$bidStatus = 1;
		}

		$this->bidStatus	 = $bidStatus;
		$bookings			 = new \Stub\common\Booking();
		$bookings->setBidData($val);
		$this->bookings[]	 = $bookings;
	}

	public function setAllocatedBidData($val)
	{
		$dataBidString	 = $val['bvr_special_remarks'];
		$dataBidArr		 = json_decode($dataBidString);
		$reachTime		 = $dataBidArr->reachingAtTime;
		$timeDiffMinutes			 = \Filter::getTimeDiff($reachTime);
		$this->bidAmount			 = (int) $val['bvr_bid_amount'];
		$this->reachingAfterMinutes	 = (int) $timeDiffMinutes;
		$this->reachingAt			 = $reachTime;
	}

}
