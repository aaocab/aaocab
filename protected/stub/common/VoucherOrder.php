<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;


class VoucherOrder extends Voucher
{	
	public $orderNumber;
	public $purchaseDate;
	public $quantity;
	public $status;
	public $totalPrice;

		

	public function setList($result)
	{
		$data = [];
		foreach ($result as $res)
		{
			$voucherOrdObj = new VoucherOrder();
			$voucherOrdObj->setData($res);
			$data[]	 = $voucherOrdObj;
		}		
		return $data;
	}

	public function setData($result)
	{
		$this->id			 = \Yii::app()->shortHash->hash($result['vch_id']);
		$this->code			 = $result['vch_code'];
		$this->title		 = $result['vch_title'];
		$this->description	 = $result['vch_desc'];
		$this->price		 = (int) round($result['vch_selling_price']);	
		$this->orderNumber	 = $result['vor_number'];
		$this->quantity		 = $result['vod_vch_qty'];
		$this->purchaseDate	 = date("Y-m-d", strtotime($result['vor_date']));
		
		$this->status		 = \VoucherOrder::getStatus($result['vor_active']);
		$this->totalPrice	 = (int) round($result['vod_vch_price']);
	}
	

}
