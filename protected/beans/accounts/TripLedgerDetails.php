<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TripLedgerDetails
 *
 * @author Deepak
 * 
 * 
 * @property \Beans\common\DateRange $dateRange
 * @property \Beans\common\PageRef $pageRef
 * @property integer $tripId 
 * @property integer $securityDeposit 
 * @property float $netBalance
 * @property \Beans\accounts\TransactionStatement $statements
 * 
 */

namespace Beans\accounts;

class TripLedgerDetails
{

	public $tripId;

	/** @var \Beans\common\DateRange $dateRange */
	public $dateRange;

	/** @var \Beans\common\PageRef $pageRef */
	public $pageRef;
	public $totalTripAmount; 
	public $totalTDSDeducted;
	public $tripCount;
	public $profitAmount;

	/** @var \Beans\accounts\TripStatement[] $tripStatements */
	public $statements;

	public static function setData($totalTripAmount, $totalTDSDeducted, $tripCount = 0, $profitAmount = 0)
	{
		$obj					 = new TripLedgerDetails();
		$obj->totalTripAmount	 = (int) $totalTripAmount;
		$obj->totalTDSDeducted	 = (int) $totalTDSDeducted; 
		$obj->tripCount			 = (int) $tripCount;
		$obj->profitAmount		 = (int) $profitAmount;
		return $obj;
	}

	public function getRequest()
	{
		$obj			 = new AccountLedgerDetails();
		$obj->dateRange	 = new \Beans\common\DateRange();
		$obj->dateRange	 = $this->dateRange;
		$size			 = 50;
		$obj->pageRef	 = \Beans\common\PageRef::getDefault($this->pageRef, $size);
		$obj->tripId	 = $this->tripId;
		return $obj;
	}
}
