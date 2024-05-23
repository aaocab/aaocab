<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PaymentDetails
{
    public $order_reference_number;
    public $supplier_reference_number;
    public $amount_to_be_collected;
    public $paid_online;
    public $paid_cash;
    public $min_payment_amount;
    public $passenger_details;
    public $chauffeur_details;
    public $vehicle_details;
    
    public $distance;
    public $start_time;
    public $end_time;
    public $source_name;
    public $destination_name;
    public $toll_charges;
    public $state_tax;
    public $total_driver_charges;
    public $total_fare;
    public $status_history = ["PENDING","STARTED","BOARDED"];
   
	
	/** @var \Stub\mmt\Events $started*/
	public $started;
	
	/** @var \Stub\mmt\Events $boarded*/
	public $boarded;
	
	/** @var \Stub\mmt\Events $alight*/
	public $alight;
	
    /**
	 * This function is used for getting payment details	  
	 * @param int $bkgId
     * @return [object]
	 */
	public function setData($data, $bkgId)
	{
        $model  = \Booking::model()->findByPk($bkgId);
        $model->bkgInvoice->bkg_total_amount = $this->total_fare;
        $model->bkgInvoice->bkg_advance_amount = $this->paid_online;
        //paid_cash
        //amount_to_be_collected
        //min_payment_amount
    }
    

	/**
	 * This function is used for getting payment details	  
	 * @param int $bkgId
     * @return [object]
	 */
	public function getData($bkgId)
	{
        $model                           = \Booking::model()->findByPk($bkgId);
        $this->order_reference_number    = $model->bkg_agent_ref_code;
        $this->supplier_reference_number = $model->bkg_id;
        $amtToBeCollected                = $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_advance_amount;
        $this->amount_to_be_collected    = $amtToBeCollected;
        $this->paid_online               = $model->bkgInvoice->bkg_advance_amount;
        $this->paid_cash                 = "";
		if($model->bkgSvcClassVhcCat->scv_id ==1 || $model->bkgSvcClassVhcCat->scv_id==2 || $model->bkgSvcClassVhcCat->scv_id==3)
		{
            $this->min_payment_amount    = $model->bkgInvoice->bkg_total_amount * 0.2;
		}
		else
		{
			$this->min_payment_amount   = $model->bkgInvoice->bkg_total_amount * 0.5;
		}
        
        /* @var $obj Stub\mmt\PassengerDetails */
        $passengerDetails        = new \Stub\mmt\PassengerDetails();
        $passengerDetails->getData($model);
        $this->passenger_details = $passengerDetails;

        /* @var $obj Stub\mmt\ChauffeurDetails */
        $chauffeurDetails        = new \Stub\mmt\ChauffeurDetails();
        $chauffeurDetails->setData($model);
        $this->chauffeur_details = $chauffeurDetails;

        /* @var $obj Stub\mmt\VehicleDetails */
        $vehicleDetails        = new \Stub\mmt\VehicleDetails();
        $vehicleDetails->setData($model);
        $this->vehicle_details = $vehicleDetails;

        $this->distance             = $model->bkg_trip_distance;
        $this->start_time           = $model->bkg_pickup_date;
        $this->end_time             = $model->bkg_return_date;
        $this->source_name          = $model->bkg_pickup_address;
        $this->destination_name     = $model->bkg_drop_address;
        $this->toll_charges         = $model->bkgInvoice->bkg_toll_tax;
        $this->state_tax            = $model->bkgInvoice->bkg_state_tax;
        $this->total_driver_charges = $model->bkgInvoice->bkg_driver_allowance_amount;
        $this->total_fare           = $model->bkgInvoice->bkg_total_amount;
        
        $started      = new \Stub\mmt\Events();
        $started->setData($model, 'STARTED');
        $this->started = $started;
		
		$boarded      = new \Stub\mmt\Events();
        $boarded->setData($model, 'BOARDED');
        $this->boarded = $boarded;
		
		$alight      = new \Stub\mmt\Events();
        $alight->setData($model, 'ALIGHT');
        $this->alight = $alight;
    }
    

}
