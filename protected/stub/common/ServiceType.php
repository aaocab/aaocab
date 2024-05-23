<?php

namespace Stub\common;

class ServiceType
{

    public $vnp_oneway, $vnp_round_trip, $vnp_multi_trip, $vnp_airport, $vnp_package, $vnp_flexxi, $vnp_daily_rental, $vnp_tempo_traveller, $vnp_lastmin_booking;

    /** @var ServiceType */
    public function setData($json)
    {

        $this->vnp_oneway          = $json->vnp_oneway != null ? $json->vnp_oneway : "-1";
        $this->vnp_round_trip      = $json->vnp_round_trip != null ? $json->vnp_round_trip : "-1";
        $this->vnp_multi_trip      = $json->vnp_multi_trip != null ? $json->vnp_multi_trip : "-1";
        $this->vnp_airport         = $json->vnp_airport != null ? $json->vnp_airport : "-1";
        $this->vnp_package         = $json->vnp_package != null ? $json->vnp_package : "-1";
        $this->vnp_flexxi          = $json->vnp_flexxi != null ? $json->vnp_flexxi : "-1";
        $this->vnp_daily_rental    = $json->vnp_daily_rental != null ? $json->vnp_daily_rental : "-1";
        $this->vnp_tempo_traveller = $json->vnp_tempo_traveller != null ? $json->vnp_tempo_traveller : "-1";
        $this->vnp_lastmin_booking = $json->vnp_lastmin_booking != null ? $json->vnp_lastmin_booking : "-1";
        return $this;
    }

}
