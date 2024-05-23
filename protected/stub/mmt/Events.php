<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Events
{
    public $timestamp;
    public $status;
    public $device_id;
    
    public $location;

    /**
	 * This function is used to get Event details
	 * @param Booking $model
     * @return [object]
	 */
	public function setData($model, $eventType)
	{
		$eventId = \BookingTrackLog::model()->getEventIdByType($eventType);
		
        $location       = new  \Stub\mmt\Location();
        $location->setData($model->bkgTrack, $eventId);
        $this->location = $location;
		
        $this->timestamp = strtotime($model->bkg_pickup_date) * 1000;
        $this->status    = $eventType;
        $this->device_id = "98:0C:A5:BB:CC:17";
    }
}
