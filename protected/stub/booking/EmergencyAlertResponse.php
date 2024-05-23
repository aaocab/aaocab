<?php
namespace Stub\booking;

class EmergencyAlertResponse
{
    public $bookingId, $userName,$driverName,$cabNumber,$sosRoute,$sosLatitude,$sosLongitude,$lastTrackedLocation,$sosDateTime,$sosEnableDate,$sosEnableTime;

    public function setData($booking,$track)
    {		
        $this->bookingId             = $booking['bookingId'];
		$this->userName              = $booking['user_name'];
		$this->driverName            = $booking['driver_name'];
		$this->cabNumber			 = $booking['cab_number'];
		$this->sosRoute              = $booking['bkg_route_name'];
		$this->sosLatitude           = $track->bkg_sos_latitude;
		$this->sosLongitude          = $track->bkg_sos_longitude;
		$this->lastTrackedLocation   = $this->sosLatitude . ',' . $this->sosLongitude;
		//$this->sosDateTime           = $track->bkg_sos_enable_datetime;
		$this->sosEnableDate		 = date('Y-m-d', strtotime($this->sosDateTime));
		$this->sosEnableTime         = date('H:i:s', strtotime($this->sosDateTime));
    }
}
