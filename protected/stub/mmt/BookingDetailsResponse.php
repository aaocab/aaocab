<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Stub\mmt;

/**
 * Description of HoldResponse
 *
 * @author Admin
 */
class BookingDetailsResponse
{
    public $response;
    public $status;
    public $failure_reason;
    public $reference_number;
    public $verification_code;
    public $error;
    public $code;
    public $success;
    
    /** @param \Booking $model */
    public function setData($model)
    {
        $this->response->status = \Booking::model()->getBookingStatusForGOIBIBO($model->bkg_status);
        if ($model->bkg_status == 1)
        {
            $this->response->failure_reason = 'Quote Expired';
        }
        $this->response->reference_number = $model->bkg_id;
        if (!in_array($model->bkg_status, [8, 9, 10]) && $model->bkg_status != 1)
        {
            $this->response->verification_code = $model->bkgTrack->bkg_trip_otp; 
        }
        $this->error = NULL;
        $this->code  = NULL;
    }

    public function setMissingData()
    {
        $this->status                   = 'MISSING';
        $this->failure_reason           = 'Missing Booking';
    }
    
    public function setError(\ReturnSet $returnSet)
	{
		$this->code = $returnSet->getErrorCode();
		$message =implode(',',$returnSet->getErrors()) ;
		$this->error = $message;
	}
}
