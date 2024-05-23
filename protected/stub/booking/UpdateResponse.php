<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UpdateResponse
{

    public $bookingId, $message;

    public function setData($model,$result)
    {
        $this->bookingId = $model->bkg_booking_id;
        $this->message   = $result->errors;
    }

}
