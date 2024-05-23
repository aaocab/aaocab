<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of CartItems
 *
 * @author Roy
 */
class CartItems
{

	//put your code here
	public $sessionId;
	public $id;
	public $quantity;
	public $price;
	public $totalPrice;
	public $tax;
	public $title;
	public $description;
	public $code;
	public $name;
    public $email;

	public function setData($item)
	{				
		$this->id			 = $item['id'];
		$this->code			 = $item['code'];
		$this->title		 = $item['title'];
		$this->description	 = $item['desc'];
		$this->totalPrice	 = (int) round($item['price']);	
		$this->price		 = (int) round($item['price'] / $item['qty']);
		$this->quantity		 = $item['qty'];	
		$this->name			 = $item['name'];
		$this->email		 = $item['email'];		
	}

	public function setList($result)
	{
		$data = [];
		foreach ($result as $res)
		{
			$cartObj = new CartItems(); 
			$cartObj->setData($res);
			$data[]	 = $cartObj;
		}	
		return $data;	 
	}
	
	public function setSummaryList($result)
	{
		$data = [];
		foreach ($result as $res)
		{
			$cartObj = new CartItems();
			$cartObj->setSummary($res);
			$data[]	 = $cartObj;
		}	
		return $data;	 
	}
	
	public function setSummary($item)
	{		
		$this->title		 = $item->vodVch->vch_title;
		$this->description	 = $item->vodVch->vch_desc;		
		$this->price		 = $item->vod_vch_price;
		$this->quantity		 = (int) $item->vod_vch_qty;
	}

}
