<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TripLedgerStatement
 *
 * @author Deepak
 * 
 *  
 * @property integer $id
 * @property string $transDate
 * @property string $createdDate
 * @property integer $amount
 * @property integer $tripAmount
 * @property string $ledgerName
 * @property integer $refId;
 * @property string $refType;
 * @property string $bookingId
 * @property string $tripId
 * @property string $description  
 * 
 * 
 */

namespace Beans\accounts;

class TripLedgerStatement
{

	public $id;
	public $transDate;
	public $createdDate;
	public $amount;
	public $tripAmount;
	public $ledgerName;
	public $refValue;
	public $refType;
	public $bookingId;
	public $tripId;
	public $description;

	public static function setData($data)
	{

  
		$obj				 = new TripLedgerStatement();
		$obj->id			 = (int) $data['act_id'];
		$obj->transDate		 = $data['act_date'];
		$obj->createdDate	 = $data['act_created'];
		$obj->amount		 = (int) $data['transAmount'];
		$obj->ledgerName	 = $data['ledgerName'];
		$obj->refValue		 = (int) $data['refId'];
		$obj->refType		 = \AccountTransDetails::getAccountType($data['refType']);
		$obj->description	 = $data['act_remarks'];
		$obj->bookingId		 = $data['bookingId'];
		$obj->tripId		 = (int) $data['tripId'];
		return $obj;
	}

}
