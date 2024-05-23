<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\booking;

class FbgResponse
{

	public $bookingId;
	public $referenceId;
	public $statusDesc;
	public $statusCode;
	public $tripType;
	public $tripDesc;
	public $cabType;
	public $startDate;
	public $startTime;
	public $tripEndTime;
	public $totalDistance;
	public $estimatedDuration;
	public $id;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes = [];

	/** @var \Stub\common\CabRate $cabRate */
	public $cabRate;

	/** @var \Stub\common\PartnerTransactionDetails $partnerTransactionDetails */
	public $partnerTransactionDetails;
	public $payUrl;

	/** @var \Stub\common\Transactions $transactions */
	public $transactions;

	/** @var \Stub\common\Driver $driver */
	public $driver;

/** @var \Stub\common\Vendor $vendor */
	public $vendor;

/** @var \Stub\common\Person $user */
	public $user;

/** @var \Stub\common\Cab $category */
	public $category;
/** @var \Stub\common\Vehicle $vehicle */
	public $vehicle;

	public function setData(\Booking $model)
	{
		$result				 = $model->getBookingCodeStatus();
		$this->bookingId		 = $model->bkg_booking_id;		
		$this->referenceId		 = $model->bkg_agent_ref_code;
		$this->statusCode	 = (int) $result['code'];
		$this->statusDesc	 = $result['desc'];
		$this->tripType			 = $model->bkg_booking_type;
		$this->tripDesc			 = $model->getBookingType($model->bkg_booking_type);
		$this->cabType			 = (int) $model->bkg_vehicle_type_id;
		$this->startDate		 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime		 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->totalDistance	 = (int) $model->bkg_trip_distance;
		$this->estimatedDuration = (int) $model->bkg_trip_duration;
		$this->id		         = (int) $model->bkg_id;

		$routes = $model->bookingRoutes;
        foreach ($routes as $route)
        {
            $itinerary      = new \Stub\common\Itinerary();
            $itinerary->setModelData($route);
            $this->routes[] = $itinerary;
        }

		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate = $cabRate;

		if ($model->bkg_agent_id != null)
		{
			$this->partnerTransactionDetails = new \Stub\common\PartnerTransactionDetails();
			$this->partnerTransactionDetails->setModelData($model->bkgInvoice);
		}
		
		$this->transactions = new \Stub\common\PaymentState();
		$this->transactions->setModels($model->bkg_id);
	}

}
