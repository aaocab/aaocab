<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreditRequest
{

    public $bookingId;
    public $credits;
    public $event;

    public function getModel(\Booking $model = null)
    {
        if ($model == null)
        {
            $model = \Booking::getNewInstance();
        }
      
        $model->bkg_id                      = $this->bookingId;
        return $model;
    }
   

}
