<?php

namespace Stub\spicejet;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreateResponse
{
	public $response;
    public $reference_number;
	public $verification_code;
	public $verification_type;
	public $TripStartOTP;
	public $TripEndOTP;
	public $error;
	public $code;
    

    public function setData($model = null)
    {
        $this->response->success			 = true;
		$this->response->reference_number	 = $model->bkg_booking_id;
		$this->response->verification_code	 = $model->bkgTrack->bkg_trip_otp;
		$this->response->verification_type	 = 'otp';
		$this->response->TripStartOTP		 = NULL;
		$this->response->TripEndOTP			 = NULL;
		$this->error						 = NULL;
		$this->code							 = NULL;
	}

}
