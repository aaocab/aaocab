<?php

namespace Beans\vendor;

/**
 * Description of BidRequest
 *
 * @author Dev
 * @property integer $id
 * @property string $routeName
 * @property string $startTime
 * @property string $endTime
 * @property int $totalDays
 * @property string $totalTripDuration
 * @property int $totalDistance
 * @property string $isMatched
 * @property string $vendorAmount
 * @property string $dependencyMsg
 * @property int $isBiddable
 * @property string $bidInfo
 * @property string $requestedCab
 */
class TripDetailResponse extends \Beans\booking\Trip
{

	public $id;
	public $routeName;
	public $startTime;
	public $endTime;
	public $totalDays;
	public $totalTripDuration;
	public $isMatched;
	public $vendorAmount;
	public $isBiddable;
	public $isGozoNow;
	public $dependencyMsg;
	public $bidInfo;
	public $eventTypes;
	/** @var \Beans\common\Cab $requestedCab */
	public $requestedCab;

	public static function setTripData($objData, $showFullDetails = false, $cttid = '')
	{
		$obj					 = new TripDetailResponse();
		$timeDurationInMinutes	 = \Filter::getTimeDiff($objData->trip_completion_time, $objData->bkg_pickup_date);

		$timeDuration = \Filter::getTimeDurationbyMinute($timeDurationInMinutes);

		$obj->id		 = (int) $objData->bcb_id;
		$obj->startTime	 = $objData->bkg_pickup_date;
		$obj->endTime	 = $objData->trip_completion_time;
		$tripDay		 = (int) ceil(($objData->bkg_trip_duration / 60) / 24);
		$obj->totalDays	 = $tripDay;

		$tripDayString			 = $tripDay . (($tripDay > 1) ? " days" : " day");
		$obj->totalTripDuration	 = $timeDuration . ' (' . $tripDayString . ')';
		/** @var \BookingCab $bcbModel */
			$bcbModel = \BookingCab::model()->findBypk($objData->bcb_id);
			$bkgModels	 = $bcbModel->bookings;
			$bkgModel	 = $bkgModels[0];
			
		if ($showFullDetails)
		{
			
			$obj		 = \Beans\booking\Trip::setByModel($bcbModel, $bkgModel, '', $cttid);
		}
		#$obj->cabType = \Beans\common\Cab::setData($bcbModel);
		
//		$routeName				 = \BookingRoute::getRouteFullNameByBcb($objData->bcb_id);
//		$obj->routeName			 = $routeName; //$objData->bkg_route_name;
		$obj->routeName = \BookingCab::getRouteNameListById($objData->bcb_id);

		$obj->isMatched		 = (int) $objData->bcb_trip_type;
		$obj->isBiddable	 = (int) $objData->is_biddable;
		
		$obj->vendorAmount	 = (int) $objData->vendor_ammount;
		if ($obj->isBiddable == 1 && $objData->dependencyMsg != '')
		{
			$obj->dependencyMsg = $objData->dependencyMsg;
		}
		
		$obj->isGozoNow			 = (int)$bkgModel->bkgPref->bkg_is_gozonow;
		$objBid					 = new \Beans\booking\BidInfo();
		$objBid->setData($objData);
		$obj->bidInfo			 = $objBid;
		$obj->totalTripDistance	 = (int) \BookingCab::getTotalTripDistanceById($objData->bcb_id);
		$objEvents		 = new \Beans\booking\SyncEvents();
		$resEvent	 = $objEvents->showEventTypes($objData->bcb_id);
		if(!empty($resEvent))
		{
		$obj->events     = $resEvent;
		}
		return $obj;
	}

	public function setResponseData($result, $dependencyMsg)
	{
		
		$data = [];
		foreach ($result as $res)
		{
			$objData = \Filter::convertToObject($res);

			$objData->dependencyMsg	 = $dependencyMsg;
			$obj					 = TripDetailResponse::setTripData($objData);
			$bkgObj					 = new PendingBookingResponse();

			$bkgObj->setTripData($objData);
			$obj->bookings[] = $bkgObj;

			$data[] = $obj;
		}
		return $data;
	}
	
	public function setPendingResponse($result, $dependencyMsg)
	{
		
		$data = [];
		foreach ($result as $res)
		{
			$objData = \Filter::convertToObject($res);

			$objData->dependencyMsg	 = $dependencyMsg;
			$obj					 = TripDetailResponse::setTripData($objData);
			$obj->requestedCab      = \Beans\common\Cab::setRequestedCab($objData);
			
			$bkgObj					 = new PendingBookingResponse();
			#print_r($bkgObj);
			$bkgObj->setTripData($objData);
			$data[] = $obj;
		}
		return $data;
	}

	public function getBidList($data)
	{
		$dataList = [];
		foreach ($data as $v)
		{
			$obj		 = new TripDetailResponse();
			$row		 = (is_array($v)) ? \Filter::convertToObject($v) : $v;
			$obj->getBidData($row);
			$dataList[]	 = $obj;
		}
		return $dataList;
	}

	public function getBidData($val)
	{
		$timeDurationInMinutes		 = \Filter::getTimeDiff($val->bcb_end_time, $val->bkg_pickup_date);
		$timeDuration				 = \Filter::getTimeDurationbyMinute($timeDurationInMinutes);
		$this->id					 = (int) $val->bkg_id;
		$routeName					 = \BookingRoute::getRouteFullNameByBcb($val->bvr_bcb_id);
		$this->routeName			 = $routeName;
		$this->startTime			 = $val->bkg_pickup_date;
		$this->endTime				 = $val->bcb_end_time;
		$this->totalDays			 = (int) ceil(($val->bkg_trip_duration / 60) / 24);
		$tripDay					 = \Filter::getTripDayByRoute($val->bkg_id);
		$this->totalTripDuration	 = $timeDuration . '( ' . $tripDay . ' day )';
		$objBid						 = new \Beans\booking\BidInfo();
		$objBid->previousBidAmount	 = $val->bvr_bid_amount;
		$bkgStatus					 = $val->bkg_status;
		$bidStatus					 = 2;
		if ($bkgStatus == 2)
		{
			$bidStatus = ($val->bvr_bid_amount > 0 && $val->bvr_is_gozonow == 1) ? 0 : 3;
		}
		if (in_array($bkgStatus, [3, 5, 6, 7]) && $val->bvr_bid_amount > 0 && $val->bcb_vendor_id == $val->bvr_vendor_id)
		{
			$bidStatus = 1;
		}
		$objBid->bidStatus			 = $bidStatus;
		
		$this->bidInfo				 = $objBid;
		$objPref					 = new \Beans\booking\Preferences();
		$objPref->isGozoNow			 = (int) $val->bvr_is_gozonow;
		$this->booking->preferences	 = $objPref;
	}

}
