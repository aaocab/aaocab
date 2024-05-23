<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Booking
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $code
 * @property \Beans\booking\Trip $trip
 * @property \Beans\booking\Route[] $bookings
 * @property string $quotedDistance
 * @property string $quotedTime
 * @property string $pickupDate
 * @property string $endDate
 * @property string $routeDesc
 * @property string $status
 * @property string $bkvnUrl
 * @property integer $cabVerifyFlag
 * @property \Beans\common\CabCategory $cabType
 * @property \Beans\common\Fare $fare
 * @property \Beans\common\ValueObject $tripType
 * @property \Beans\common\ValueObject $tripCategory
 * @property \Beans\contact\Person $traveller 
 * @property array $trackEvents
 * @property string $additionalInformation
 * @property \Beans\booking\Preferences $preferences
 * @property \Beans\common\AppPreference[] $appPref
 * @property \Beans\common\Tags $tags
 * @property \Beans\booking\AdditionalInfo  $additionalInfo
 */

namespace Beans;

class Booking
{

	public $id;
	public $code;

	/** @var \Beans\booking\Trip $trip */
	public $trip;

	/** @var \Beans\booking\Route[] $routes */
	public $routes;
	public $quotedDistance;
	public $quotedTime;
	public $pickupDate;
	public $endDate;
	public $routeDesc;
	public $bkvnUrl;
	public $status;
	public $otp;

	/** @var \Beans\booking\BillingDetails $billing */
	public $billing;

	/** @var \Beans\common\CabCategory $cabType */
	public $cabType;

	/** @var \Beans\booking\Fare $fare */
	public $fare;

	/** @var common\ValueObject $tripType */
	public $tripType;

	/** @var common\ValueObject $tripCategory */
	public $tripCategory;

	/** @var \Beans\contact\Person $traveller */
	public $traveller;

	/** @var \Beans\booking\TrackEvent[] $trackEvents */
	public $trackEvents;

	/** @var \Beans\booking\additionalInfo[] $trackEvents */
	public $additionalInfo;

	/** @var booking\Preferences $preferences */
	public $preferences;

	/** @var booking\Tags $tags */
	public $tags;

	/** @var \Beans\common\AppPreference[] $appPref */
	public $appPref;
	
	public $liveHelpPhone;

	/**
	 * 
	 * @param type $bkgId
	 */
	public function getById($bkgId)
	{
		$tripDataSet = \Booking::getById($bkgId);
		foreach ($tripDataSet as $tripData)
		{
			$this->setData($tripData);
		}
	}

	/**
	 * 
	 * @param type $tripId
	 */
	public function getByTripId($tripId)
	{
		$dataSet = \Booking::getByTripId($tripId);
		$data	 = [];
		foreach ($dataSet as $data)
		{
			$data[] = $this->setData($data);
		}
	}

	public function getByBookingModelList($bkgModels)
	{

		$data = [];
		foreach ($bkgModels as $bkgModel)
		{
			$data[] = $this->setData($bkgModel);
		}
		return $data;
	}

	/**
	 * 
	 * @param type $bkgData
	 * @return \Beans\Booking
	 */
	public static function setData($bkgData)
	{
		$obj				 = new Booking();
		$obj->id			 = (int) $bkgData->bkg_id;
		$obj->code			 = $bkgData->bkg_booking_id;
		
		$obj->pickupDate	 = $bkgData->bkg_pickup_date;
		$obj->endDate		 = $bkgData->bkg_return_date;
		$obj->quotedDistance = (int) $bkgData->bkg_trip_distance;
		$obj->quotedTime	 = (int) $bkgData->bkg_trip_duration;

		$obj->tripType	 = \Beans\common\ValueObject::setBookingTypeData($bkgData->bkg_booking_type);
		$obj->routeDesc	 = \BookingRoute::model()->getRouteName($bkgData->bkg_id);

		$obj->status = (int) $bkgData->bkg_status;

		if ($bkgData instanceof \Booking)
		{
			/** @var Booking $bkgData */
			$obj->preferences	 = \Beans\booking\Preferences::setByModel($bkgData);
			$obj->cabType		 = \Beans\common\CabCategory::setByModel($bkgData);
			$obj->fare			 = \Beans\booking\Fare::setByInvoiceModel($bkgData->bkgInvoice, $bkgData->bkg_status, $bkgData->bkg_booking_type);
			$obj->traveller		 = \Beans\contact\Person::setTravellerInfoByModel($bkgData->bkgUserInfo);
			$obj->additionalInfo = \Beans\booking\AdditionalInfo::setByAddInfoModel($bkgData->bkgAddInfo);
		}
		else
		{
			$obj->preferences	 = \Beans\booking\Preferences::setData($bkgData);
			$obj->cabType		 = \Beans\common\CabCategory::setData($bkgData);
			$obj->fare			 = \Beans\booking\Fare::setData($bkgData);
			$obj->traveller		 = \Beans\contact\Person::setTravellerInfoByModel($bkgData);
			$obj->additionalInfo = \Beans\booking\AdditionalInfo::setByAddInfoModel($bkgData);
		}



		$obj->routes = \Beans\booking\Route::setDataByBooking($bkgData->bkg_id);

		$vendorId = $bkgData->bkgBcb->bcb_vendor_id;
		if ($vendorId > 0)
		{
			$obj->bkvnUrl = \BookingSub::getBKVNUrl($bkgData->bkg_id, $vendorId);
		}

		$model		 = \Booking::model()->findByPk($bkgData->bkg_id);
		$obj->tags	 = \Beans\booking\Tags::setByModel($model);
		return $obj;
	}

	/**
	 * 
	 * @param type $tripData
	 * @return type
	 */
	public static function setBookingData($tripData)
	{
		$dataList = [];
		foreach ($tripData as $res)
		{
			$objData	 = (is_array($res)) ? \Filter::convertToObject($res) : $res;
			$bkgObj		 = \Beans\Booking::setData($objData);
			$dataList[]	 = $bkgObj;
		}
		return $dataList;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $view
	 * @return type
	 */
	public static function setDataById($bkgId, $view = '', $cttId = '', $hideDocDetails = false)
	{
		/** @var \Booking $model */
		$model				 = \Booking::model()->findByPk($bkgId);
		$obj				 = new \Beans\Booking();
		$obj->id			 = (int) $model->bkg_id;
		$obj->code			 = $model->bkg_booking_id;
		$cabId				 = $model->bkgBcb->bcb_cab_id;
		$cabVerify			 = \Vehicles::model()->getVerifyStatus($cabId, $model->bkg_pickup_date);
		$obj->cabVerifyFlag	 = $cabVerify;
		$showLiveHelp  = \Drivers::helplineDataShow($model->bkg_pickup_date, $model->bkg_return_date);
		if($showLiveHelp == 1)
		{
			$phone = \Config::get('gozo.liveHelp.number');
			$phone =array("code"=>"","number"=>$phone);
			$obj->liveHelpPhone[] = \Beans\contact\Phone::setUserPhone($phone);
		}
		$obj->quotedDistance = (int) $model->bkg_trip_distance;
		$obj->quotedTime	 = (int) $model->bkg_trip_duration;
		$obj->pickupDate	 = $model->bkg_pickup_date;
		$returnDate			 = $model->bkg_return_date;
		$pickupstrTime		 = strtotime($model->bkg_pickup_date);
		if ($returnDate == '')
		{
			$returnDate = date('Y-m-d H:i:s', strtotime('+' . $model->bkg_trip_duration . ' minutes', $pickupstrTime));
		}
		$obj->endDate	 = $returnDate;
		$otp			 = $model->bkgTrack->bkg_trip_otp;
		$obj->otp		 = $otp;
		if (!$model->bkgBcb->bcb_start_time)
		{
			$model->bkgBcb->bcb_start_time = $model->bkg_pickup_date;
		}
		$obj->trip		 = \Beans\booking\Trip::setByModel($model->bkgBcb, $model, $view, $cttId, $hideDocDetails);
		$obj->routeDesc	 = \BookingRoute::model()->getRouteName($model->bkg_id);
		$obj->status	 = (int) $model->bkg_status;

		$obj->tripType		 = \Beans\common\ValueObject::setBookingTypeData($model->bkg_booking_type);
		$obj->routes		 = \Beans\booking\Route::setDataByBooking($model->bkg_id);
		$obj->fare			 = \Beans\booking\Fare::setByInvoiceModel($model->bkgInvoice, $model->bkg_status, $model->bkg_booking_type);
		$obj->preferences	 = \Beans\booking\Preferences::setByModel($model);
		$obj->cabType		 = \Beans\common\CabCategory::setByModel($model);
		$obj->traveller		 = \Beans\contact\Person::setTravellerInfoByModel($model->bkgUserInfo);
		$obj->additionalInfo = \Beans\booking\AdditionalInfo::setByAddInfoModel($model->bkgAddInfo);
		$obj->tags			 = \Beans\booking\Tags::setByModel($model);
		$objEvents			 = new \Beans\booking\SyncEvents();
		$resEvent			 = $objEvents->showEventTypes($model->bkg_bcb_id);
		if (!empty($resEvent))
		{
			$obj->events	 = $resEvent;
			$objEventDetails = $objEvents->showEventDetails($model->bkgTrack);

			if (!empty($objEventDetails['tripStartTime']))
			{
				$obj->eventDetails = $objEventDetails;
			}
		}
		
			$vendorId = $model->bkgBcb->bcb_vendor_id;
			if ($vendorId > 0)
			{
				$obj->bkvnUrl = \BookingSub::getBKVNUrl($model->bkg_id, $vendorId);
			}
		
		return $obj;
	}
	
	public static function setList($bkgId)
	{
		/** @var \Booking $model */
		
		$model				 = \Booking::model()->findByPk($bkgId);
		$obj				 = new \Beans\Booking();
		$obj->id			 = (int) $model->bkg_id;
		$obj->code			 = $model->bkg_booking_id;
	
		$cabId				 = $model->bkgBcb->bcb_cab_id;
		$cabVerify			 = \Vehicles::model()->getVerifyStatus($cabId, $model->bkg_pickup_date);
		$obj->cabVerifyFlag	 = $cabVerify;
		
		$obj->quotedDistance = (int) $model->bkg_trip_distance;
		$obj->quotedTime	 = (int) $model->bkg_trip_duration;
		$obj->pickupDate	 = $model->bkg_pickup_date;
		$returnDate			 = $model->bkg_return_date;
		$pickupstrTime		 = strtotime($model->bkg_pickup_date);
		if ($returnDate == '')
		{
			$returnDate = date('Y-m-d H:i:s', strtotime('+' . $model->bkg_trip_duration . ' minutes', $pickupstrTime));
		}
		$obj->endDate	 = $returnDate;
		$otp			 = $model->bkgTrack->bkg_trip_otp;
		$obj->otp		 = $otp;
		if (!$model->bkgBcb->bcb_start_time)
		{
			$model->bkgBcb->bcb_start_time = $model->bkg_pickup_date;
		}
		$vendorBiddingAmount = \BookingVendorRequest::vendorBiddingAmount($model->bkg_bcb_id,$model->bkgBcb->bcb_vendor_id);
		
		if($vendorBiddingAmount>0)
		{
			$obj->bidAmount = $vendorBiddingAmount;
		}
		//$obj->trip		 = \Beans\booking\Trip::setByModel($model->bkgBcb, $model, $view, $cttId, $hideDocDetails);
		$obj->routeDesc	 = \BookingRoute::model()->getRouteName($model->bkg_id);
		$obj->status	 = (int) $model->bkg_status;

		//$obj->tripType		 = \Beans\common\ValueObject::setBookingTypeData($model->bkg_booking_type);
		$obj->routes		 = \Beans\booking\Route::setDataByBooking($model->bkg_id);
		
		
		$obj->preferences	 = \Beans\booking\Preferences::setByModel($model);
		$obj->tags			 = \Beans\booking\Tags::setByModel($model);
		$obj->cabType		 = \Beans\common\CabCategory::setByModel($model);
		$obj->additionalInfo = \Beans\booking\AdditionalInfo::setByAddInfoModel($model->bkgAddInfo);
		$vendorId = $model->bkgBcb->bcb_vendor_id;
		if ($vendorId > 0)
		{
			$obj->bkvnUrl = \BookingSub::getBKVNUrl($model->bkg_id, $vendorId);
		}
		
		if ($model->bkgBcb->bcb_driver_id>0)
		{
			$cttId				 = \ContactProfile::getByEntityId($model->bkgBcb->bcb_driver_id, \UserInfo::TYPE_DRIVER);
			$obj->driver = \Beans\Driver::setByContact($cttId);
			
		}
		if ($model->bkgBcb->bcb_cab_id>0)
		{
			$obj->cab	 = \Beans\common\Cab::setPrefferedByContact($cttId, $model->bkgBcb->bcb_cab_id);
			
		}
		$obj->actionFlag = \Beans\booking\trip::actionFlag($model);
		
		return $obj;
	}
	
	
	

}
