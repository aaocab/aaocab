<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of VoucherSubscriber
 *
 * @author Roy
 */
class VoucherSubscriber extends Person
{

	//put your code here

	public $purchaseBy;
	public $redeemBy;
	public $redeemCode;
	public $redeemDate;	

	/** @var \Stub\common\Voucher $voucher */
	public $voucher;

	public function setData(\VoucherSubscriber $model = null)
	{
		$this->voucher->id				 = $model->vsb_vch_id;
		$this->fullName					 = $model->vsb_name;
		$this->email					 = $model->vsb_email;
		$this->primaryContact->number	 = $model->vsb_phone;
		$this->purchaseBy				 = $model->vsb_purchased_by;
		$this->redeemBy					 = $model->vsb_redeemed_by;
		$this->redeemCode				 = $model->vsb_redeem_code;
		$this->redeemDate				 = $model->vsb_redeen_date;
	}	
	

}
