<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Order
 *
 * @author Roy
 */
class Order extends BillingDetails
{

	//put your code here

	public $number;
	public $sessionId;
	public $userId;
	public $apgId;
	public $totalAmount;
	public $purchaseDate;
	public $purchaseTime;

	/** @var \Stub\common\VoucherSubscriber $subscriber */
	public $subscriber;

	public function setData(\VoucherOrder $model = null)
	{
		$this->number		 = $model->vor_number;
		$this->sessionId	 = $model->vor_sess_id;
		$this->userId		 = (int) $model->vor_user_id;
		$this->apgId		 = $model->vor_apg_id;
		$this->purchaseDate	 = date("Y-m-d", strtotime($model->vor_date));
		$this->purchaseTime	 = date("H:i:s", strtotime($model->vor_date));
		$this->totalAmount	 = (int) $model->vor_total_price;

		$this->fullName	 = $model->vor_bill_fullname;
		$this->address	 = $model->vor_bill_address;
		$this->pincode	 = $model->vor_bill_postalcode;
		$this->city		 = $model->vor_bill_city;
		$this->state	 = $model->vor_bill_state;
		$this->country	 = $model->vor_bill_country;

		$this->subscriber->email					 = $model->vsb_email;
		$this->subscriber->primaryContact->number	 = $model->vor_phone;
	}

}
