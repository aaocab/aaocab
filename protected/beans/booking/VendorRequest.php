<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\booking;

/**
 * Description of VendorRequest
 *
 * @author Deepak
 * 
 * @property array $offerTypes[]   //[1=>offered, 2 =>denied,3=>new] 
 * @property array $serviceType[]  //[0=>'all',100=> 'local',200=>'outstation']
 * @property integer $sortid  //[1=>'newestBooking',2=>'earliestBooking']
 * @property \Beans\common\DateRange $pickupDateRange
 * @property \Beans\common\DateRange $createDateRange
 * @property \Beans\common\PageRef $pageRef
 * @property array  $tiers[]  //[1=>'Value',2=>'Value+',4=>'Select',6=>'Economy'
 * @property string $zones
 * @property string $keyword
 * @property string $isGozoNow 
 */
class VendorRequest
{

	public $offerTypes;  //[1=>new, 2 =>denied,3=>offered] 
	public $serviceType;
	public $serviceTypes; //[0=>'all',100=> 'local',200=>'outstation']
	public $sortid; //[1=>'newestBooking',2=>'earliestBooking']
	public $sort;

	/** @var \Beans\common\DateRange $pickupDateRange */
	public $pickupDateRange;

	/** @var \Beans\common\PageRef $pageRef */
	public $pageRef;
	public $tiers; //[1=>'Value',2=>'Value+',4=>'Select',6=>'Economy'
	public $zones;
	public $keyword;
	public $isGozoNow;
	public $serviceTypeList	 = [0 => 'all', 100 => 'local', 200 => 'outstation'];
	public $sortTypeList	 = [1 => 'newestBooking', 2 => 'earliestBooking'];

	public function processRequest(\Beans\booking\VendorRequest $obj = null)
	{
		if($this->sort)
		{
			$this->sort = $this->sortTypeList[$this->sort];
		}
//		if(empty($this->offerTypes))
//		{
//			$this->offerTypes[] = 1;
//		}
		$this->getServiceType();
		$pageRef		 = \Beans\common\PageRef::getDefault($this->pageRef);
		$obj->pageCount	 = $pageRef->pageCount;
		$obj->pageSize	 = $pageRef->pageSize;
		$obj->denyStatus = 0; // will be modify if search apply
		if(in_array(2,$this->offerTypes))
		{
			$obj->denyStatus = 1;
		}
		return $obj;
	}

	public function getServiceType()
	{
		$masterServices = [100, 200];

		if(count(array_intersect($this->serviceTypes, $masterServices)) == count($masterServices))
		{
			unset($this->serviceTypes);
			$this->serviceType = $this->serviceTypeList[0];
		}
		foreach($this->serviceTypes as $service)
		{
			if(in_array($service, $masterServices))
			{
				$this->serviceType = $this->serviceTypeList[$service];
			}
		}
		$this->serviceTypes = array_diff($this->serviceTypes, $masterServices);
	}
}
