<?php

namespace Stub\vendor;

class ChangeRideStatus
{

	public $bookingId;
	public $tripId; // $bcb_id
	public $tripOtp;
	public $flag;

	public function getModel(\Booking $model = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}

		$model->bkg_id	 = (int)$this->bookingId;
		$model->bcb_id	 =(int) $this->tripId;
		$model->bkgTrack->bkg_trip_otp             = (int)$this->tripOtp;
		
		return $model;
	}

}
