<?php
namespace Stub\common;

class Package
{
	public $packageId;
	public $packageName;
	public $packageImage;
	public $packageUrl;
	public $packageRate;
	public $fromCity;
	public $toCity;
	public $pickupDate;
	public $distance;
	public $duration;
	public $days;
	public $nights;

	public function setData($data)
	{
		$result = [];
		foreach ($data as $val)
		{
			$package	 = new \Stub\common\Package();
			$package->setDetailsData($val);
			$result[]	 = $package;
		}		
		return $result;
	}

	public function setDetailsData($value)
	{
		$this->packageId    = (int) $value['pck_id'];		
		$this->packageName  = $value['pck_name'];
		$this->packageImage = \Users::getImageUrl($value['pci_images']);
		$this->packageUrl   = \Users::getImageUrl('/packages/'.$value['pck_url'])."?app=1";
		$this->packageRate  = (int) $value['prt_package_rate'];
	}

	public function setRouteInfo($data)
	{
		$result = [];
		foreach ($data as $val)
		{
			$package	 = new \Stub\common\Package();
			$package->setRouteData($val);
			$result[]	 = $package;
		}		
		return $result;
	}
	public function setRouteData($val)
	{	
		$this->fromCity    = $val->pickup_city_name;		
		$this->toCity      = $val->drop_city_name;
		$this->pickupDate  = date('Y-m-d', strtotime($val->date));
		$this->distance    = (int) $val->distance;
		$this->duration    = (int) $val->duration;
		$this->days        = (int) $val->daycount;
		$this->nights      = (int) $val->nightcount;
	}
}
