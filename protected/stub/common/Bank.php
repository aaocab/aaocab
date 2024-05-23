<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bank
 *
 * @author Roy
 */
class Bank
{

	//put your code here
	public $name;
	public $accountNumber;
	public $branchName;
	public $beneficiaryName;
	public $ifsc;
	public $accountType;
	
	/** 
	 * 
	 * @param \Contact $model
	 * @return boolean|$this
	 */
	public function setData(\Contact $model)
	{
		if ($model == null)
		{
			return false;
		}
		$this->name				 = $model->ctt_bank_name;
		$this->accountNumber	 = $model->ctt_bank_account_no;
		$this->branchName		 = $model->ctt_bank_branch;
		$this->beneficiaryName	 = $model->ctt_beneficiary_name;
		$this->ifsc				 = $model->ctt_bank_ifsc;
		$this->accountType		 = $model->ctt_account_type;
		return $this;
	}

	/** 
	 * 
	 * @param \Contact $model
	 * @return \Contact
	 */
	public function getData(\Contact $model)
	{
		$model->ctt_bank_name		 = $this->name;
		$model->ctt_bank_account_no	 = $this->accountNumber;
		$model->ctt_bank_branch		 = $this->branchName;
		$model->ctt_beneficiary_name = $this->beneficiaryName;
		$model->ctt_bank_ifsc		 = $this->ifsc;
		$model->ctt_account_type	 = $this->accountType;
		return $model;
	}

}
