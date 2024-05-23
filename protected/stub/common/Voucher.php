<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Voucher
 *
 * @author Roy
 */
class Voucher
{

	public $id;
	public $code;
	public $title;
	public $description;
	public $price;
	public $validFromDate, $validFromTime;
	public $validToDate, $validToTime;

	public function setData(\Vouchers $model = null)
	{
		$this->id			 = (int) $model->vch_id;
		$this->code			 = $model->vch_code;
		$this->title		 = $model->vch_title;
		$this->description	 = $model->vch_desc;
		$this->price		 = (int) round($model->vch_selling_price);
		$this->validFromDate = date("Y-m-d", strtotime($model->vch_valid_from));
		$this->validFromTime = date("H:i:s", strtotime($model->vch_valid_from));
		$this->validToDate	 = date("Y-m-d", strtotime($model->vch_valid_to));
		$this->validToTime	 = date("H:i:s", strtotime($model->vch_valid_to));
		return $this;
	}

	//put your code here

	public function setList($result)
	{
		$data = [];
		foreach ($result as $res)
		{
			$voucher = new Voucher();
			$voucher->setListData($res);
			$data[]	 = $voucher;
		}
		return $data;
	}

	public function setListData($result)
	{
		$this->id			 = \Yii::app()->shortHash->hash($result['vch_id']);
		$this->code			 = $result['vch_code'];
		$this->title		 = $result['vch_title'];
		$this->description	 = $result['vch_desc'];
		$this->price		 = (int) round($result['vch_selling_price']);
		$this->validFromDate = date("Y-m-d", strtotime($result['vch_valid_from']));
		$this->validFromTime = date("H:i:s", strtotime($result['vch_valid_from']));
		$this->validToDate	 = date("Y-m-d", strtotime($result['vch_valid_to']));
		$this->validToTime	 = date("H:i:s", strtotime($result['vch_valid_to']));
	}

}
