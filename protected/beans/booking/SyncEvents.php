<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SyncBooking
 *
 * @author Dev
 * 
 * @property integer $id
 * @property integer $refId
 * @property string $createDate
 * @property string $eventType
 * @property \Beans\common\DeviceTrack $deviceTrack 
 */

namespace Beans\booking;

class SyncEvents
{

	public $id;
	public $refId;
	public $createDate;
	public $eventType;
	public $lastUpdate;
	public $userType;

	/** @var \Beans\common\DeviceTrack $deviceTrack */
	public $deviceTrack;

	/** @var \Beans\booking\TrackEvent[] $trackEvents  */
	public $trackEvents;

	public static function setData($data)
	{
		$obj			 = new SyncInfo();
		$obj->id		 = $data->id;
		$obj->syncDate	 = $data->syncDate;
//		return $obj;
	}

	public function showEventTypes($tripId)
	{
		//$emailData = \ContactEmail::model()->findByContactID($cttId);
		$syncData = \BookingTrackLog::showEventTypes($tripId);
		return $this->setList($syncData);
	}

	public function setList($syncData)
	{
		$data = [];
		foreach ($syncData as $res)
		{
			$obj	 = new SyncEvents();
			$obj->setsyncData($res);
			$data[]	 = $obj;
		}
		return $data;
	}

	public function setsyncData($res)
	{
		$this->refId		 = $res['btl_bkg_id'];
		$this->eventType	 = (int) $res['btl_event_type_id'];
		$this->createDate	 =   $res['btl_created'];
	}

	public function showEventDetails($trackModel)
	{
		$data = [];

		$data['startOdometer']	 = $trackModel->bkg_start_odometer;
		$data ['endOdometer']	 = $trackModel->bkg_end_odometer;
		$data ['tripStartTime']	 = $trackModel->bkg_trip_start_time;
		return $data;
	}

	public function setEventSyncData($data, $eventType)
	{
		$this->refId	 = $data['bkg_id'];
		$this->eventType = $eventType;
		if ($this->DeviceTrack == null)
		{
			$this->deviceTrack = new \Beans\common\DeviceTrack();
		}
		$this->deviceTrack->setData($data);
		$orgDate = $data['bkg_pickup_date_date'];  
		$date = str_replace('/', '-', $orgDate);  
		$newDate = date("Y-m-d", strtotime($date));
		$time = date('H:i:s', strtotime($data['bkg_pickup_date_time']));

		$pickupDateTime = $newDate.' '.$time;
		$duration = rand(1, 2);
		$dateInterval	 = \DateTimeFormat::SQLDateTimeToDateTime($pickupDateTime)->add(new \DateInterval('PT' . $duration . 'M'));
		$lastUpdatedPickupTime	 = \DateTimeFormat::DateTimeToSQLDateTime($dateInterval);
		$this->lastUpdate = $lastUpdatedPickupTime;
		$this->userType = 4;
	}

}
