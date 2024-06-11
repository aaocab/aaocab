<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trip
 *
 * @author Dev
 * 
 * 
 * @property integer $id
 * @property \Beans\Booking $bookings 
 * @property \Beans\Vendor $vendor
 * @property \Beans\Driver $driver
 * @property \Beans\common\Cab $cab 
 * @property string $tripAmount
 * @property string $assignedDate
 * @property string $unassignPenaltySlabs
 * @property string $totalTripDistance
 * @property string $totalDays
 * @property string $totalTime
 * @property string $startTime
 * @property string $endTime
 * @property string $routeName
 */

namespace Beans\booking;

class Trip
{

	public $id;
	public $routeName;

	/** @var \Beans\Vendor $vendor */
	public $vendor;

	/** @var \Beans\Driver $driver */
	public $driver;

	/** @var \Beans\common\Cab $cab */
	public $cab;
	public $vendorAmount;
	public $assignedDate;
	public $unassignPenaltySlabs;
	public $totalTripDistance;
	public $totalDays;
	public $totalTime;
	public $startTime;
	public $endTime;
	public $totalTripDuration;
	public $isMatched;
	public $isOTPRequired;
	public $bidAlertMsg;
	public $bidInfo;

	/** @var \Beans\Booking $bookings */
	public $bookings;

	public function getByVendor($vndId)
	{
		$tripDataSet = \BookingCab::getByVendor($vndId);
		foreach ($tripDataSet as $tripData)
		{
			$this->setData($tripData);
		}
	}

	public function setData($tripData)
	{
		$this->id	 = $tripData['bcb_id'];
		$bookings	 = new \Beans\Booking();
		$bookings->getByTripId($tripData['bcb_id']);
	}

	public static function setDetailsByData($tripData)
	{
		$bookings = new \Beans\Booking();
		$bookings->getByTripId($tripData['bcb_id']);
	}

	public function setBidAcceptData($data)
	{
		$this->id	 = $data['bcb_id'];
		$bidInfo	 = new \Beans\booking\BidInfo();
		$bidInfo->setAcceptAmount($data);
	}

	public static function setTrip($tripId, $panaltySlab = null)
	{

		$obj		 = new Trip();
		$obj->id	 = (int) $tripId;
		$bcbModel	 = \BookingCab::model()->findByPk($tripId);

		$vendorAmount		 = $bcbModel->bcb_vendor_amount;
		$obj->vendorAmount	 = (int) $vendorAmount;
		$obj->startTime		 = $bcbModel->bcb_start_time;
		$obj->endTime		 = $bcbModel->bcb_end_time;

		$durationInMinutes	 = \Filter::getTimeDiff($bcbModel->bcb_end_time, $bcbModel->bcb_start_time);
		$timeDuration		 = \Filter::getTimeDurationbyMinute($durationInMinutes);

		$daysCount = \Filter::getDaysCount($bcbModel->bcb_start_time, $bcbModel->bcb_end_time);

		$obj->totalDays = (int) $daysCount;

		$obj->totalTime = (int) $durationInMinutes;

		$tripDayString			 = $daysCount . (($daysCount > 1) ? " days" : " day");
		$obj->totalTripDuration	 = $timeDuration . ' (' . $tripDayString . ')';
		$obj->routeName			 = \BookingCab::getRouteNameListById($tripId);
		$obj->penaltySlabs		 = $panaltySlab;
		$bkgModels				 = $bcbModel->bookings;
		foreach ($bkgModels as $key => $bkg)
		{

			$obj->bookings[] = \Beans\Booking::setData($bkg);
		}
		if ($bcbModel['bcb_driver_id'] > 0)
		{
			$cttId = \ContactProfile::getByEntityId($bcbModel->bcb_driver_id, \UserInfo::TYPE_DRIVER);
			if ($cttId > 0)
			{
				$obj->driver = \Beans\Driver::setByContact($cttId, false);
			}
		}

		if ($bcbModel->bcb_cab_id > 0)
		{
			//$obj->cab	 = \Beans\common\Cab::setPrefferedByContact($cttId, $bcbModel->bcb_cab_id);
			$obj->cab = \Beans\common\Cab::setcab($bcbModel->bcb_cab_id, $bcbModel);
		}
		return $obj;
	}

	/** @var \BookingCab $bcbModel */

	/** @var \Booking $bkgModel */
	public static function setByModel($bcbModel, $bkgModel, $view = '', $cttid = '', $hideDocDetails = false)
	{
		$obj				 = new Trip();
		$hideDocDetails		 = ($view == 'driver');
		$obj->id			 = (int) $bcbModel->bcb_id;
		$vendorAmount		 = $bcbModel->bcb_vendor_amount;
		$obj->vendorAmount	 = (int) $vendorAmount;
		$obj->startTime		 = $bcbModel->bcb_start_time;
		$obj->endTime		 = $bcbModel->bcb_end_time;

		$durationInMinutes	 = \Filter::getTimeDiff($bcbModel->bcb_end_time, $bcbModel->bcb_start_time);
		$timeDuration		 = \Filter::getTimeDurationbyMinute($durationInMinutes);

		$daysCount = \Filter::getDaysCount($bcbModel->bcb_start_time, $bcbModel->bcb_end_time);

		$obj->totalDays = (int) $daysCount;

		$obj->totalTime = (int) $timeDuration;

		$tripDayString			 = $daysCount . (($daysCount > 1) ? " days" : " day");
		$obj->totalTripDuration	 = $timeDuration . ' (' . $tripDayString . ')';
		$obj->isMatched			 = (int) $bcbModel->bcb_trip_type;
		$obj->isOTPRequired		 = (int) $bkgModel->bkgPref->bkg_trip_otp_required;

		if ($bkgModel->bkg_booking_type == 2 || $bkgModel->bkg_booking_type == 3)
		{
			$obj->bidAlertMsg = "Customers have the freedom to enhance their journey by modifying their route or including new locations, cities or sightseeing spots or local attractions during the ride within the designated timeframe. Customers will not be charged extra for any travel within the quoted distance. If the total distance exceed the initial quoted distance, an extra km charge will be applied.";
		}

		if ($bkgModel->bkg_status == 2 && $bkgModel->bkgPref->bkg_is_gozonow == 1 && $cttid > 0)
		{
			$obj->driver = \Beans\Driver::setByContact($cttid);
			$obj->cab	 = \Beans\common\Cab::setPrefferedByContact($cttid, $bkgModel->bkg_vehicle_type_id);
		}
		if ($bcbModel->recommended_vendor_amount > 0)
		{
			$bidInfo		 = new \Beans\booking\BidInfo();
			$bidInfo->setRecommendedAmount($bcbModel);
			$obj->bidInfo	 = $bidInfo;
		}
		if ($bcbModel->bcb_vendor_id > 0)
		{
			$obj->assignedDate = $bkgModel->bkgTrail->bkg_assigned_at;

			$unassignedTime	 = '';
			$assignedTime	 = $bkgModel->bkgTrail->bkg_assigned_at;

			$pickupTime		 = $bcbModel->bcb_start_time;
			$acceptType		 = $bcbModel->bcb_assign_mode;
			$dependencyScore = $bcbModel->bcbVendor->vendorStats->vrs_dependency | 0;
			if ($assignedTime != "")
			{
				//$unassignPenaltySlabs		 = $bcbModel->GetUnassignPenaltySlabs($unassignedTime, $assignedTime, $pickupTime, $vendorAmount, $acceptType, $dependencyScore);
				//$obj->unassignPenaltySlabs	 = json_decode($unassignPenaltySlabs);
			}
			if ($view != 'driver')
			{
				/** @var \Beans\Vendor $vendor */
				$obj->vendor = \Beans\Vendor::setDataByModel($bcbModel->bcbVendor);
			}
			if ($bcbModel->bcb_driver_id > 0)
			{
				/** @var \Beans\Driver $driver */
				$obj->driver = \Beans\Driver::setDataByModel($bcbModel->bcbDriver, $bcbModel->bcb_driver_phone, $hideDocDetails);
			}
			if ($bcbModel->bcb_cab_id > 0)
			{
				/** @var \Beans\common\Cab $cab */
				$obj->cab = \Beans\common\Cab::setDataByCabModel($bcbModel->bcbCab);
			}
		}
		return $obj;
	}

	public static function actionFlag(\Booking $model)
	{
		$isGozonow	 = $model->bkgPref->bkg_is_gozonow;
		$flags		 = [];
		$typeList	 = [
			1	 => "canceledAllowed",
			2	 => "Reassign"
		];
		foreach ($typeList as $key => $type)
		{
			$obj = new Trip();

			switch ($type)
			{
				case "canceledAllowed":
					$obj->canAllowed($isGozonow);
					break;

				case "Reassign":
					$obj->Reassign($isGozonow);
					break;

				default:
					break;
			}
			$addItional[] = $obj;
		}
		return $addItional;
	}

	public function canAllowed($isGozonow)
	{
		$this->label = "canceledAllowed";
		$this->id	 = (int) (($isGozonow == 1) ? 0 : 1);
	}

	public function Reassign($isGozonow)
	{
		$this->label = "reassign";
		$this->id	 = (int) (($isGozonow == 1) ? 0 : 1);
	}

	public function setCabDriver($data)
	{
		$data		 = $data->trip;
		$obj->tripId = (int) $data->id;
		if ($data->cab)
		{
			$obj->cab = \Beans\common\Cab::setCabId($data->cab->id);
		}
		if ($data->driver && $data->driver->phone[0]->number != "")
		{
			$obj->driver = \Beans\Driver::setDataForGNow($data->driver->id, $data->driver->phone[0]->number);
		}
		return $obj;
	}

	public function setTripList($recordSet)
	{
		foreach ($recordSet as $data)
		{
			$tripId			 = $data['bcb_id'];
			$penaltySlabs	 = $data['cancelSlabs'];
			$datalist[]		 = self::setTrip($tripId, $penaltySlabs);
		}
		return $datalist;
	}

	public static function getBidList($data)
	{
		$dataList = [];
		foreach ($data as $row)
		{
			$obj		 = new Trip();
			$obj->getBidData($row);
			$dataList[]	 = $obj;
		}
		return $dataList;
	}

	public function getBidData($v)
	{
		$val = (is_array($v)) ? \Filter::convertToObject($v) : $v;

		$objBid					 = new \Beans\booking\BidInfo();
		$isGozoNow				 = ($val->bkg_is_gozonow != 0) ? 1 : 0;
		$objBid->setDataByBidModel($val, $val->bkg_status, $val->bcb_vendor_id, $isGozoNow);
		$this->bidInfo			 = $objBid;
		$timeDurationInMinutes	 = \Filter::getTimeDiff($val->bcb_end_time, $val->bkg_pickup_date);
		$timeDuration			 = \Filter::getTimeDurationbyMinute($timeDurationInMinutes);
		$this->id				 = (int) $val->bkg_bcb_id;
//		$routeName				 = \BookingRoute::getRouteFullNameByBcb($val->bvr_bcb_id);
//		$this->routeName		 = $routeName;
		$this->startTime		 = $val->bkg_pickup_date;
		$this->endTime			 = $val->bcb_end_time;
		$this->totalDays		 = (int) ceil(($val->bkg_trip_duration / 60) / 24);
		$tripDay				 = \Filter::getTripDayByRoute($val->bkg_id);
		$this->totalTripDuration = $timeDuration . '( ' . $tripDay . ' day )';

		$this->bookings[] = \Beans\Booking::setData($val, false);
	}
}
