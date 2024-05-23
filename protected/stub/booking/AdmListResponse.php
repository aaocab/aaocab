<?php

namespace Stub\booking;

class AdmListResponse {
    public $dataList = [];
    

    public function getData(\CDbDataReader $result, $criType)
    {
        foreach ($result as $res)
        {
                $booking		 = new \Stub\common\Booking();
                $this->dataList[]	 = $booking->SetPreference($res, $criType);
        }      
        return $this;
    }
}
