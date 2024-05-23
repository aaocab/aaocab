<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Cart
 *
 * @author Roy
 */
class Cart
{
	//put your code here
	public $slNumber;
	
	/** @var \Stub\common\CartItems $items */
	public $items;
	public $balance;
	public $orderNumber;
	public $orderID;
	/** @var \Stub\common\BillingDetails $billing */
	public $billing;


	public function setData($result,$balance,$orderNumber=null,$orderId=null)
	{
		$objItems      = new CartItems();		
		$this->items   = $objItems->setList($result,$balance);
		$this->balance = $balance;
		if(!empty($orderNumber))
		{
			$this->orderNumber = $orderNumber;
		}
		if(!empty($orderId))
		{
			$this->orderID = $orderId;
		}
		return $this;
	}
	
	public function setSummaryData($result)
	{
		$this->balance   = $result->vor_total_price;	
		$objItems        = new CartItems();		
		$this->items     = $objItems->setSummaryList($result->voucherOrderDetails);
		$billObj         = new BillingDetails();		
		$this->billing   = $billObj->setVoucherSummery($result);
		return $this;
	}
	
}
