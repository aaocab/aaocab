<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinStatement
 *
 * @author Deepak
 * 
 * 
 * @property integer $id
 * @property string $transDate
 * @property string $createdDate
 * @property integer $amount 
 * @property string $type
 * @property string $refValue
 * @property string $refType
 * @property integer $tripId
 * @property string $description 
 * @property string $doneBy
 * @property string $userType
 * 
 * 
 */

namespace Beans\accounts;

class CoinStatement
{

	public $id;
	public $transDate;
	public $createdDate;
	public $amount;
	public $type;
	public $refValue;
	public $refType;
	public $tripId;
	public $description;
	public $doneBy;
	public $userType;

	public static function getList($transDetails)
	{
		$objTrans = [];
		foreach ($transDetails as $transRow)
		{
			$objTrans[] = CoinStatement::fillRow($transRow);
		}
		return $objTrans;
	}

	public static function fillRow($transRow)
	{
		$vncTypeArr			 = \VendorCoins::vncType;
		$vncRefTypeArr		 = \VendorCoins::vncRefType;
		$obj				 = new CoinStatement();
		$obj->id			 = (int) $transRow['vnc_id'];
		$obj->transDate		 = $transRow['vnc_modified_at'];
		$obj->createdDate	 = $transRow['vnc_created_at'];
		$obj->amount		 = (int) $transRow['vnc_value'];
		$obj->type			 = $vncTypeArr[$transRow['vnc_type']];
		$obj->refValue		 = (int) $transRow['vnc_ref_id'];
		$obj->refType		 = $vncRefTypeArr[$transRow['vnc_ref_type']];
		$obj->tripId		 = $transRow['tripId'];
		$obj->description	 = $transRow['vnc_desc'];
		$obj->doneBy		 = $transRow['doneBy'];
		$obj->userType		 = \UserInfo::getUserTypeDesc($transRow['vnc_user_type']);
		return $obj;
	}

}
