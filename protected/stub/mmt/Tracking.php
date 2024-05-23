<?php

namespace Stub\mmt;

class Tracking
{
	public $event_type, $latitude, $longitude, $timestamp;

	public function getData($data,$model)
	{
		$eventType			 = \BookingTrack::getTripEvents($data['btl_event_type_id']);
		$this->event_type	 = $eventType;
		$triplogDetails		 = \BookingTrackLog::model()->getdetailByEvent($model->bkg_id, $data['btl_event_type_id']);
		$coordinate			 = explode(',', $triplogDetails['btl_coordinates']);
		$this->latitude		 = $coordinate[0];
		$this->longitude	 = $coordinate[1];
		$this->tripStartTime = strtotime($triplogDetails['btl_sync_time']) * 1000;

	}
}
