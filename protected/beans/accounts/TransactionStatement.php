<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransactionStatement
 *
 * @author Deepak
 * 
 * 
 * @property integer $id
 * @property string $transDate
 * @property string $createdDate
 * @property integer $amount
 * @property string $ledgerName
 * @property string $refValue
 * @property string $refType
 * @property string $description
 * @property integer totalPenaltyWaivedOff
 * @property Array $flags
 * @property float $runningBalance
 * @property string $doneBy
 * 
 */

namespace Beans\accounts;

class TransactionStatement
{

	public $id;
	public $transDate;
	public $createdDate;
	public $amount;
	public $ledgerName;
	public $refValue;
	public $refType;
	public $description;
	public $totalPenaltyWaivedOff;
	public $flags; //[1=>Redeem/Remove Penalty,2=>Raise Dispute];
	public $doneBy;
	public $runningBalance;
	public $remainingAmount;
	public $lockedAmount;
	public $paymentFlag;
	public $type;
	public static function setData($data)
	{
		$flags = [];
		if ($data['penaltyRemovable'] == 1)
		{
			$flags[] = 1;
		}
		if ($data['raiseDispute'] == 1)
		{
			$flags[] = 2;
		}

		$obj				 = new TransactionStatement();
		$obj->id			 = (int) $data['act_id'];
		$obj->transDate		 = $data['act_date'];
		$obj->createdDate	 = $data['act_created'];
		$obj->amount		 = (int) $data['transAmount'];
		$obj->ledgerName	 = $data['ledgerName'];
		$obj->refValue		 = (int) $data['refId'];
		$obj->refType		 = \AccountTransDetails::getAccountType($data['refType']);
		$obj->description	 = $data['transRemarks'];
		if ($data['refLedger'] == 28)
		{
			$obj->totalPenaltyWaivedOff	 = (int) $data['totalPenaltyWaivedOff'];
			$obj->flags					 = count($flags) > 0 ? $flags : null;
		}

		$obj->doneBy		 = $data['adm_name'];
		$obj->runningBalance = (int) $data['vendorRunningBalance'];
		return $obj;
	}
	
	public static function showData($adjustableAmount,$bidType)
	{	
		
		$obj->remainingAmount	 = $adjustableAmount;
		$obj->paymentFlag		 = 1;
		$obj->type               = $bidType;
		return $obj;
	}

}
