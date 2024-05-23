<?php

namespace Stub\consumer;

class SOSRequest
{

	public $bookingId;
	public $comments;
	public $timeStamp;

	/** @var \Stub\common\Coordinates $location */
	public $location;

	/** @var \Stub\common\Platform $device */
	public $device;

	/** @var Booking $model */
	public function getModel(\Booking $model = null)
	{
		if (!$model)
		{
			return false;
		}
		$model->bkgTrack->bkg_sos_remarks	 = $this->comments;
		$model->bkgTrack->book_time			 = $this->timeStamp;
		$model->bkgTrack->bkg_sos_latitude	 = $this->location->latitude - 0.0;
		$model->bkgTrack->bkg_sos_longitude	 = $this->location->longitude - 0.0;
		$model->bkgTrack->bkg_sos_device_id	 = $this->device->uniqueId;
		return $model;
	}

}
