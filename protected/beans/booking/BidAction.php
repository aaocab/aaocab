<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BidAction
 *
 * @author Dev
 * 
 * @property integer $action
 * @property integer $amount
 * @property integer $tripId  
 * @property \Beans\common\Cab $cab 
 * @property \Beans\Driver $driver
 * @property integer $reachingAfterMinutes 
 * @property \Beans\common\ValueObject $reason
 * 
 * 
 */

namespace Beans\booking;

class BidAction
{

	public $tripId;
	public $action; //0=>Deny,1=>Bid,2=>Direct Accept
	public $amount;

	/** @var \Beans\common\Cab $cab */
	public $cab;

	/** @var \Beans\Driver $driver */
	public $driver;
	public $reachingAfterMinutes;

	/** @var \Beans\common\ValueObject $reason */
	public $reason;

	public function __construct($data)
	{
		$this->action = (int) $data->action;

		$this->tripId = (int) $data->tripId;
		if ($this->action == 0)
		{
			$this->reason->id = $data->reason->id;
			return;
		}
		$this->amount = (int) $data->amount;
		if ($data->cab)
		{
			$this->cab = \Beans\common\Cab::setCabId($data->cab->id);
		}
		if ($data->driver)
		{
			$this->driver = \Beans\Driver::setDataForGNow($data->driver->id, $data->driver->phone[0]->number);
		}
		$this->reachingAfterMinutes = $data->reachingAfterMinutes;
	}

	public function setData($data)
	{
		$this->action	 = (int) $data->action;
		$this->amount	 = (int) $data->amount;
		$this->tripId	 = (int) $data->tripId;
	}

	public function setAcceptData($data, $isGozoNow = false)
	{
		$this->action	 = (int) $data->action;
		$this->amount	 = (int) $data->amount;
		$this->tripId	 = (int) $data->tripId;
		if ($isGozoNow)
		{
			$this->setGNOwAcceptData($data);
		}
	}

	public function setAcceptAmount($data)
	{
		$this->acceptAmount = (int) $data->acceptAmount;
	}

	public function setGNOwAcceptData($data)
	{
		$this->cab					 = \Beans\common\Cab::setCabId($data->cab->id);
		$this->driver				 = \Beans\Driver::setDataForGNow($data->driver->id, $data->driver->phone[0]->number);
		$this->reachingAfterMinutes	 = $data->reachingAfterMinutes;
	}

	public function getDriverId()
	{
		return $this->driver->id;
	}

	public function getDriverMobile()
	{
		/** @var \Beans\Driver $driver */
		return $this->driver->phone[0]->fullNumber;
	}

	public function getCabId()
	{
		return $this->cab->id;
	}

	public function getDenyReasonId()
	{
		return $this->reason->id;
	}

}
