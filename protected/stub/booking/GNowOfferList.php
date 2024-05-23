<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GNowOfferList
{

	public $id, $offeredAt, $minimumArrivalTime, $arrivalDuration, $dataList;

	/** @var \Stub\common\Vendor $vendor */
	public $vendor;

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	public function setData($dataSet)
	{
		$dataList = [];
		foreach ($dataSet as $data)
		{
			$obj		 = new \Stub\booking\GNowOfferList();
			$obj->fillData($data);
			$dataList[]	 = $obj;
		}
		return $dataList;
	}

	public function fillData($data)
	{

		$this->id					 = (int) $data['bvr_id'];
		$this->offeredAt			 = $data['bvr_accepted_at'];
		$this->minimumArrivalTime	 = $data['reachingAtTime'];
		$this->arrivalDuration		 = ceil((strtotime($data['reachingAtTime']) - strtotime($data['bvr_accepted_at'])) / 60);

		$vendor				 = new \Stub\common\Vendor();
		$vendor->code		 = $data['vnd_code'];
		$vendor->rating		 = (double) $data['bvr_vendor_rating'];
		$vendor->totalTrips	 = (int) $data['totalTrips'];
		$this->vendor		 = $vendor;

		$vehicle			 = new \Stub\common\Vehicle();
		$vehicle->setVehicleFillData($data);
		$vehicle->totalTrips = (int) $data['vhs_total_trips'];
		$vehicle->hasCNG	 = (int) $data['vhc_has_cng'];

		$cab		 = new \Stub\common\Cab();
		$cab->type	 = $data['cab_type'];

		$vehicle->category	 = $cab;
		$this->vehicle		 = $vehicle;

		if (ISSET($data['drs_no_of_star']) && $data['drs_no_of_star'] > 0)
		{
			$driver				 = new \Stub\common\Driver();
			$driver->rating		 = (double) $data['drs_no_of_star'];
			$driver->name		 = $data['drv_name'];
			$driver->code		 = $data['drv_code'];
			$driver->totalTrips	 = (int) $data['total_trip'];
			$this->driver = $driver;
		}

		$fare				 = new \Stub\common\Fare();
		$fare->totalAmount	 = (int) $data['totalCalculated'];
		$this->fare			 = $fare;
	}

}
