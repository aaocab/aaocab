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
class TripDetailsResponse
{
    public $response;
    public $status;
    public $failure_reason;
    public $reference_number;
    public $verification_code;
	public $chauffeur_details;
	public $error;
    public $code;
    public $success;
	public $vehicle;
	public $tracking;
	public $last_coordinates;
	/** @var \Stub\mmt\ExtraCharges $extra_charges */
	public $extra_charges;


	/** @param \Booking $model */
    public function setData($model)
    {
        if ($model->bkg_status == 1)
        {
            $this->failure_reason = 'Quote Expired';
        }
        $this->reference_number = $model->bkg_id;
        if (!in_array($model->bkg_status, [8, 9, 10]) && $model->bkg_status != 1)
        {
            $this->verification_code = $model->bkgTrack->bkg_trip_otp; 
        }
		if($model->bkgBcb->bcb_driver_id != null)
		{
			$this->chauffeur_details = new \Stub\mmt\ChauffeurDetails();
			$this->chauffeur_details->getData($model);
		}

		$trackLog = \BookingTrackLog::getEventByBkgId($model->bkg_id);
		if($trackLog)
		{
			$data = [];
			$this->last_coordinates =			$model->bkgTrack->btk_last_coordinates;
			foreach ($trackLog as $value)
			{
				$trackingDetails								 = new \Stub\mmt\Tracking();
				$trackingDetails->getData($value,$model);
				$data[] = $trackingDetails;
			}
			$this->tracking						 = $data;
			array_push($this->last_coordinates,$data);
		}

		$this->extra_charges = new \Stub\mmt\ExtraCharges();
		$this->extra_charges->getCharges($model,$model->bkg_pickup_date, $model->bkg_return_date);
		
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
