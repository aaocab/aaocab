<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountInfo
 *
 * @author Dev
 * 
 * 
 * @property integer $id
 * @property string $type
 * @property string $accountNumber
 * @property string $accountName
 * @property string $ifscCode
 * @property string $branchName
 * @property integer $isDefault
 * 
 */

namespace Beans\common;

class AccountInfo
{

	public $id;
	public $type;
	public $accountNumber;
	public $accountName;
	public $ifscCode;
	public $branchName;
	public $isDefault;
	public $benificiaryName;

	public static function setData($data)
	{
		$obj					 = new \Beans\common\AccountInfo();
		$obj->type				 = ($data['ctt_account_type'] == 0) ? 'Saving' : 'Current';
		$obj->accountNumber		 = $data['ctt_bank_account_no'];
		$obj->accountName		 = $data['ctt_bank_name'];
		$obj->ifscCode			 = $data['ctt_bank_ifsc'];
		$obj->branchName		 = $data['ctt_bank_branch'];
		$obj->isDefault			 = 1;
		$obj->benificiaryName	 = $data['ctt_beneficiary_name'];
		return $obj;
	}

}
