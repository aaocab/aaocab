<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transaction
 *
 * @author Dev
 * 
 * 
 * @property string $id
 * @property string $mode
 * @property string $amount
 * @property string $description
 * @property string $date
 * @property string $refNumber
 * @property string $merchantRefCode
 * @property string $status
 */

namespace Beans\booking;

class Transaction
{
	public $id;
	public $mode;
	public $amount;
	public $description;
	public $date;
	public $refNumber;
	public $merchantRefCode;
	public $status;
	public $endOdometre;
	public $bkgId;
	/** @var \Beans\common\Coordinates $coordinates */
	public $coordinates;
			



	public static function setByBooking($bkgId)
	{
		$transactions	 = [];
		$dataReader		 = \PaymentGateway::fetchTransactionsByBooking($bkgId);

		$modeArr	 = \PaymentGateway::model()->paymentModeArr;
		$statusArr	 = \PaymentGateway::model()->paymentStatus;
		foreach ($dataReader as $value)
		{

			$tObj					 = new Transaction();
			$tObj->id				 = (int) $value['apg_id'];
			$tObj->mode				 = $modeArr[(int) $value['apg_mode']];
			$tObj->amount			 = (int) $value['apg_amount'];
			$tObj->description		 = $value['apg_remarks'];
			$tObj->date				 = $value['apg_date'];
			$tObj->refNumber		 = $value['apg_code'];
			$tObj->merchantRefCode	 = ($value['apg_txn_id']!='')? $value['apg_txn_id']:'NA';
			$tObj->status			 = $statusArr[(int) $value['apg_status']];
			$transactions[]			 = $tObj;
		}
		return $transactions;
	}
	
	

}
