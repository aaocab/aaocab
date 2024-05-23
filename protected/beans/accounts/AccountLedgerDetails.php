<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountLedgerDetails
 *
 * @author Deepak
 * 
 * 
 * @property \Beans\common\DateRange $dateRange
 * @property \Beans\common\PageRef $pageRef
 * @property float $applicableTDS
 * @property float $deductedTDS
 * @property float $withdrawableBalance 
 * @property \Beans\common\ValueObject $withdrawableStatus 
 * @property integer $securityDeposit
 * @property float $closingBalance
 * @property float $netBalance
 * @property \Beans\common\TransactionStatement $statements
 * 
 */

namespace Beans\accounts;

class AccountLedgerDetails
{

	/** @var \Beans\common\DateRange $dateRange */
	public $dateRange;

	/** @var \Beans\common\PageRef $pageRef */
	public $pageRef;
	public $applicableTDS;
	public $deductedTDS;
	public $withdrawableBalance;

	/** @var \Beans\common\ValueObject $withdrawableStatus */
	public $withdrawableStatus;
	public $securityDeposit;
	public $openingBalance;
	public $closingBalance;
	public $netBalance;

	/** @var \Beans\accounts\TransactionStatement[] $statements */
	public $statements;

	public static function setData($vendorAmount, $openingBalance, $closingBalance, $tdsAmount)
	{
		$withdrawableStatusArr	 = [1 => "Payable", 2 => "Receivable"];
		$withdrawableStatus		 = ($vendorAmount['vendor_amount'] < 0) ? 1 : 2;

		$obj						 = new AccountLedgerDetails();
		$obj->applicableTDS			 = (int) round($tdsAmount['totalTDS']);
		$obj->deductedTDS			 = (int) round($tdsAmount['alreadypaid']) * -1;
		$obj->withdrawableBalance	 = (int) round($vendorAmount['withdrawable_balance']);
		$obj->withdrawableStatus	 = \Beans\common\ValueObject::setIdlabel($withdrawableStatus, $withdrawableStatusArr[$withdrawableStatus]);
		$obj->securityDeposit		 = (int) $vendorAmount['vnd_security_amount'];
		$obj->openingBalance		 = (int) $openingBalance;
		$obj->closingBalance		 = (int) round($vendorAmount['vendor_amount']);
		$obj->netBalance			 = (int) $closingBalance;
		return $obj;
	}

	public function getRequest()
	{
		$obj			 = new AccountLedgerDetails();
		$obj->dateRange	 = new \Beans\common\DateRange();
		$obj->dateRange	 = $this->dateRange;
		$size			 = 50;
		$obj->pageRef	 = \Beans\common\PageRef::getDefault($this->pageRef, $size);

		return $obj;
	}

}
