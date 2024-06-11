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
	public $cngAllowed;

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
		$bcbModel				 = \BookingCab::model()->findBypk($objData->bcb_id);
		$bkgModels				 = $bcbModel->bookings;
		$bkgModel				 = $bkgModels[0];
		$obj->cngAllowed		 = (int) $bkgModel->bkgPref->bkg_cng_allowed;
		$obj->tripType			 = \Beans\common\ValueObject::setBookingTypeData($bkgModel->bkg_booking_type);
		if ($showFullDetails)
		{

			$obj = \Beans\booking\Trip::setByModel($bcbModel, $bkgModel, '', $cttid);
		}
		#$obj->cabType = \Beans\common\Cab::setData($bcbModel);
//		$routeName				 = \BookingRoute::getRouteFullNameByBcb($objData->bcb_id);
//		$obj->routeName			 = $routeName; //$objData->bkg_route_name;
		$obj->routeName = \BookingCab::getRouteNameListById($objData->bcb_id);

		$obj->isMatched	 = (int) $objData->bcb_trip_type;
		$obj->isBiddable = (int) $objData->is_biddable;

		$obj->vendorAmount = (int) $objData->vendor_ammount;
		if ($obj->isBiddable == 1 && $objData->dependencyMsg != '')
		{
			$obj->dependencyMsg = $objData->dependencyMsg;
		}

		$obj->isGozoNow			 = (int) $bkgModel->bkgPref->bkg_is_gozonow;
		$objBid					 = new \Beans\booking\BidInfo();
		$objBid->setData($objData);
		$obj->bidInfo			 = $objBid;
		$obj->totalTripDistance	 = (int) \BookingCab::getTotalTripDistanceById($objData->bcb_id);
		$objEvents				 = new \Beans\booking\SyncEvents();
		$resEvent				 = $objEvents->showEventTypes($objData->bcb_id);
		if (!empty($resEvent))
		{
			$obj->events = $resEvent;
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
			$obj->requestedCab		 = \Beans\common\Cab::setRequestedCab($objData);

			$bkgObj	 = new PendingBookingResponse();
			#print_r($bkgObj);
			$bkgObj->setTripData($objData);
			$data[]	 = $obj;
		}
		return $data;
	}

	
}
