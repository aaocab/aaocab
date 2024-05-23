<?php

namespace Stub\vendor;

class PackageRequest 
{
	public $packageType;
	public $address;
	public $street;
	public $cabList;
	public $cabid;
	public $state;
	public $pin;
	public $landMark;
	public $mobile;
	public $id;
	public $trackingNumber;
	public $receiveDate;
	public $receiveCount;
	public $description;
	
	

	public function getModel(\VendorPackages $model = null)
	{
		if($model ==null)
		{
			$model = new \VendorPackages;
		}
		$model->vpk_type=($this->packageType==""?1:$this->packageType);
		$model->vpk_mailing_address = $this->address->street.','.$this->address->city.','.$this->address->state.', Pin: '.$this->address->pin.', Landmark: '.$this->address->landMark;
		
		foreach ($this->cabList as $row)
		{
			$cab_arr[] = $row->id;
		}
		$model->vpk_vhc_id =implode(",",$cab_arr);
		
		return $model;
	}
	public function getReceive($model=null)
	{
	
		if($model ==null)
		{
			$model = new \VendorPackages;
		}
		$model		 	= \VendorPackages::model()->findByPk($this->id);
		$model->vpk_id= $this->id;
		$model->vpk_tracking_number =$this->trackingNumber;
		$model->vpk_received_date =$this->receiveDate.' '.'00:00:00';
		$model->vpk_receive_desc = $this->description;
		$model->vpk_received_status=1;
	
		
		return $model;
	}

	
}
