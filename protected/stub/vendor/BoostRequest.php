<?php

namespace Stub\vendor;

class BoostRequest 
{
	public $address;
	public $street;
	public $cabList;
	public $cabid;
	public $state;
	public $pin;
	public $landMark;
	public $mobile;
	public function fillData($row)
	{
		
		$this->cabid = $row->id;
		
	}
	

	public function getModel(\VendorBoost $model = null)
	{
		//echo "hihi";exit;
		if($model ==null)
		{
			$model = new \VendorBoost();
		}
		
		$model->vbt_mailing_address = $this->address->street.','.$this->address->city.','.$this->address->state.', Pin: '.$this->address->pin.', Landmark: '.$this->address->landMark;
		
		foreach ($this->cabList as $row)
		{
			$cab_arr[] = $row->id;
		}
		$model->vbt_vhc_id =implode(",",$cab_arr);
		return $model;
		
		
	}

}
