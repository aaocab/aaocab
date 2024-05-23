<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UpdateRequest
{

    public $bookingId;
    public $amountPaid;

    /** @var \Stub\common\Person $traveller */
    public $traveller;

    /** @var \Stub\common\AdditionalInfo $addtionalInfo */
    public $additionalInfo;

    public function getModel($model = null)
    {
        if ($model == null)
        {
            $model = \Booking::getNewInstance();
        }
        $model->bkg_booking_id                 = $this->bookingId;
        $model->bkgInvoice->bkg_advance_amount = $this->amountPaid;
        $model->bkgUserInfo                    = $this->traveller->getModel($model->bkgUserInfo);
        $model->bkgAddInfo                     = $this->additionalInfo->getModel($model->bkgAddInfo);
        return $model;
    }

}
