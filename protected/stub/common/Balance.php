<?php

namespace Stub\common;

class Balance
{

	//put your code here
	public $id;	   // ucr_id
	public $type;	 // ucr_type          1=>promo, 2=>refund, 3=>referral, 4=>booking, 5=>others
	public $typeDescription;   // ucr_type_desc
	public $userId;	  // ucr_user_id
	public $amount;	  // ucr_value	       amount
	public $description;	// ucr_desc          description
	public $creditUsed;	 // ucr_used
	public $creditMaxUse;   // ucr_max_use
	public $creditMaxUseType;  // ucr_maxuse_type   1:10% of booking amt,2:50% of full credits,3:Full credits,4:7% of booking amount
	public $isActiveStatus;	// ucr_status
	public $createdDate;	// ucr_created       created1
	public $createdTime;	// ucr_created       created1
	public $validUptoDate;  // ucr_validity
	public $validUptoTime;  // ucr_validity
	public $isCreditUsed;
	public $isCreditRemove;
	public $remarks;

	public function setModelData($userCredit)
	{
		$this->type				 = (int) $userCredit->ucr_type;
		$this->typeDescription	 = $userCredit->ucr_type_desc;
		$this->creditUsed		 = (int) $userCredit->amount;
		$this->description		 = $userCredit->description;
		$this->creditMaxUseType	 = (int) $userCredit->ucr_maxuse_type;
		$this->creditMaxUse		 = (int) $userCredit->ucr_max_use;
		$this->isActiveStatus	 = (int) $userCredit->STATUS;
		$this->createdDate		 = date("Y-m-d", strtotime($userCredit->created1));
		$this->createdTime		 = date("H:i:s", strtotime($userCredit->created1));
		$this->validUptoDate	 = date("Y-m-d", strtotime($userCredit->ucr_validity));
		$this->validUptoTime	 = date("H:i:s", strtotime($userCredit->ucr_validity));
		return $this;
	}

	public function setCreditData($userCredit)
	{		
		$this->isCreditUsed			 = (int) $userCredit->isGozoCoinsApplied;
		$this->refundCredits		 = (int) $userCredit->refundCredits;
		$this->isGozoCoinsApplied	 = (int) $userCredit->isGozoCoinsApplied;
		$this->creditUsed			 = (int) $userCredit->creditused;
		$this->isCreditRemove		 = (int) $userCredit->creditRemove;
	}
	
	public function setWalletData($walletData)
	{	
		$this->createdDate			= date("Y-m-d", strtotime($walletData['created']));
		$this->createdTime			= date("H:i:s", strtotime($walletData['created']));
		$this->amount				= (int)$walletData['adt_amount'] * (-1);
		$this->remarks				= $walletData['act_remarks'];		
		return $this;
	}

}
