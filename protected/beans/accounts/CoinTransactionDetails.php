<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinTransactionDetails
 *
 * @author Deepak
 * 
 * 
 * @property \Beans\common\DateRange $dateRange
 * @property \Beans\common\PageRef $pageRef 
 * @property float $netBalance
 * @property \Beans\accounts\CoinStatement $statements
 * 
 */

namespace Beans\accounts;

class CoinTransactionDetails
{

	public $tripId;

	/** @var \Beans\common\DateRange $dateRange */
	public $dateRange;

	/** @var \Beans\common\PageRef $pageRef */
	public $pageRef;
	public $netBalance;

	/** @var \Beans\accounts\CoinStatement[] $statements */
	public $statements;

	public static function setData($transDetails, $netBalance)
	{

		$obj			 = new CoinTransactionDetails();
		$obj->netBalance = (int) $netBalance;
		$obj->statements = \Beans\accounts\CoinStatement::getList($transDetails);
		return $obj;
	}

	public function getRequest()
	{
		$obj			 = new CoinTransactionDetails();
		$obj->dateRange	 = new \Beans\common\DateRange();
		$obj->dateRange	 = $this->dateRange;
		$size			 = 50;
		$obj->pageRef	 = \Beans\common\PageRef::getDefault($this->pageRef, $size);
		$obj->tripId	 = $this->tripId;
		return $obj;
	}

}
