<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\booking;

class CreateResponse
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
	public $promoCodes;
	public $credits;
	public $id;
	public $reachInMinutes;
	//currentList $vars
	public $agentId;
	public $bkg_pickup_date, $bookingCreatedOn;
	public $bkg_no_person;
	public $bookingStatus, $flexxiType, $bookingModifiedOn, $bookingInstruction;
	public $tripId, $bcb_start_time, $bcb_end_time;
	public $startOdomreter, $endOdometer, $otp, $arrivedForPickup;
	public $isTripOtpRequired, $noShow, $isDutyslipRequired;
	public $isCngAllowed;
	public $routeName;
	public $verification_code;
	public $flightNumber;

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

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\Vehicle $vehicle */
	public $vehicle;

	/** @var \Beans\common\Dbo $dbo */
	public $dbo;

	/** @var DestinationNote $destinationNote */
	public $destinationNote;
	public $liveHelpPhone;
	
	/**
	 *
	 * @var \Stub\common\Addons[] $applicableAddons
	 */
	public $applicableAddons;

	public function setData(\Booking $model)
	{
		$result				 = $model->getBookingCodeStatus();
		$this->bookingId	 = $model->bkg_booking_id;
		$this->payUrl		 = \BookingUser::getPaymentLink($model->bkg_id, 'p');
		$this->referenceId	 = $model->bkg_agent_ref_code;
		$this->statusCode	 = (int) $result['code'];
		$statusDesc			 = $result['desc'];
		if ($model->bkg_reconfirm_flag == 0 && $model->bkg_status == 2)
		{
			$statusDesc = "Reconfirm Pending";
		}
		$this->statusDesc		 = $statusDesc;
		$this->tripType			 = $model->bkg_booking_type;
		$this->tripDesc			 = $model->getBookingType($model->bkg_booking_type);
		$this->cabType			 = (int) $model->bkg_vehicle_type_id;
		$this->startDate		 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime		 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->totalDistance	 = (int) $model->bkg_trip_distance;
		$this->estimatedDuration = (int) $model->bkg_trip_duration;
		$this->verification_code = $model->bkgTrack->bkg_trip_otp;
		$this->id				 = (int) $model->bkg_id;
		$this->isGozoNow		 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
		$this->verification_code = $model->bkgTrack->bkg_trip_otp;
//		$hash = Yii::app()->shortHash->hash($model->bkg_id);
//		$epassUploadLink = Yii::app()->createUrl('index/epass', array('bkgid' => $model->bkg_id,'hash' => $hash));
//		$this->link = $epassUploadLink;

		$routes = $model->bookingRoutes;
		foreach ($routes as $route)
		{
			$itinerary		 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$this->routes[]	 = $itinerary;
		}

		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate	 = $cabRate;

		if ($model->bkg_agent_id != null)
		{
			$this->partnerTransactionDetails = new \Stub\common\PartnerTransactionDetails();
			$this->partnerTransactionDetails->setModelData($model->bkgInvoice);
			//destination notes by Rituparana
			//$noteArrList = \DestinationNote::model()->showNoteApi($model->bkg_id, $showNoteTo= 5);
			//if ($noteArrList != false || $noteArrList != NULL)
			//{
			//$res		 = new \Stub\common\DestinationNote();
			//$responseDt = $res->getData($noteArrList);
			//foreach ($responseDt as $res)
			//{
			//$this->destinationNote = $res;
			//}
			//}
		}

		$this->transactions = new \Stub\common\PaymentState();
		$this->transactions->setModels($model->bkg_id);

		$this->dbo		 = new \Beans\common\Dbo();
		$this->dbo		 = \Beans\common\Dbo::getData($model->bkg_pickup_date, $model->bkg_status, $model);
	}

	public function setCurrentData(\Booking $model, $showCustomer = null,$showHelpLine=0)
	{
		$result				 = $model->getBookingCodeStatus();
		$this->bookingId	 = $model->bkg_booking_id;
		$this->referenceId	 = $model->bkg_agent_ref_code;
		$this->statusCode	 = (int) $result['code'];
		$statusDesc			 = $result['desc'];
		if ($model->bkg_reconfirm_flag == 0 && $model->bkg_status == 2)
		{
			$statusDesc = "Reconfirm Pending";
		}
		$this->statusDesc		 = $statusDesc;
		$this->tripType			 = $model->bkg_booking_type;
		$this->tripDesc			 = $model->getBookingType($model->bkg_booking_type);
		$this->cabType			 = (int) $model->bkg_vehicle_type_id;
		$this->startDate		 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime		 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->pickupDate		 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->pickupTime		 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->totalDistance	 = (int) $model->bkg_trip_distance;
		$this->estimatedDuration = (int) $model->bkg_trip_duration;
		$this->id				 = (int) $model->bkg_id;
		$this->tripId			 = $model->bkg_bcb_id;

		$this->bookingStatus		 = $model->bkg_status;
		$this->flexxiType			 = $model->bkg_flexxi_type;
		$this->bookingModifiedOn	 = date('Y-m-d H:i:s', strtotime($model->bkg_modified_on));
		$this->bookingCreatedOn		 = date('Y-m-d H:i:s', strtotime($model->bkg_create_date));
		$flightNumber                = ($model->bkgAddInfo->bkg_flight_no!=""?" FlightNumber-".$model->bkgAddInfo->bkg_flight_no:"");
		$this->bookingInstruction	 = $model->bkg_instruction_to_driver_vendor.''.$flightNumber;
		$this->flightNumber          = $model->bkgAddInfo->bkg_flight_no;
		$this->agentId				 = $model->bkg_agent_id;
		$this->isGozoNow			 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
		$this->bcb_start_time		 = $model->bkgBcb->bcb_start_time;
		$this->bcb_end_time			 = $model->bkgBcb->bcb_end_time;

		$this->startOdomreter	 = $model->bkgTrack->bkg_start_odometer;
		$this->endOdomreter		 = $model->bkgTrack->bkg_end_odometer;
		$this->otp				 = (int)($model->bkgPref->bkg_trip_otp_required > 0)? $model->bkgTrack->bkg_trip_otp : 0;// quick fox for app issue
		$this->arrivedForPickup	 = $model->bkgTrack->bkg_arrived_for_pickup;
		$this->noShow			 = $model->bkgTrack->bkg_is_no_show;

		$this->isTripOtpRequired	 = $model->bkgPref->bkg_trip_otp_required;
		$this->isDutyslipRequired	 = $model->bkgPref->bkg_duty_slip_required;
		$this->isCngAllowed			 = (int) $model->bkgPref->bkg_cng_allowed;
		$routes						 = $model->bookingRoutes;
		foreach ($routes as $route)
		{
			$itinerary		 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$this->routes[]	 = $itinerary;
		}

		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData_v1($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate	 = $cabRate;
		if ($model->bkgBcb->bcb_cab_id > 0)
		{
			$vehicle		 = new \Stub\common\Vehicle();
			$vehicle->setModel(\Vehicles::model()->findByPk($model->bkgBcb->bcb_cab_id));
			$this->vehicle	 = $vehicle;
		}
		if ($model->bkg_agent_id != null)
		{
			$this->partnerTransactionDetails = new \Stub\common\PartnerTransactionDetails();
			$this->partnerTransactionDetails->setModelData($model->bkgInvoice);
		}

		$this->transactions = new \Stub\common\PaymentState();
		$this->transactions->setModels($model->bkg_id);
		if ($model->bkgBcb->bcb_vendor_id > 0)
		{
			$this->vendor = new \Stub\common\Vendor();
			$this->vendor->setModelData($model->bkgBcb->bcb_vendor_id);
		}
		if ($model->bkgBcb->bcb_driver_id > 0)
		{
			$this->driver = new \Stub\common\Driver();
			$this->driver->setData(\Drivers::model()->findByPk($model->bkgBcb->bcb_driver_id));
		}

		$this->user = new \Stub\common\Person();
		if ($showCustomer != null)
		{
			$this->user->setModelData(\BookingUser::model()->getByBkgId($model->bkg_id));
		}
		else
		{

			$this->user->setModelData();
		}
		
		if($showHelpLine == 0)
		{
			$phone = \Config::get('gozo.liveHelp.number');
			$phone =array("code"=>"","number"=>$phone);
			$this->liveHelpPhone[] = \Beans\contact\Phone::setUserPhone($phone);
			//$this->contact->getPhoneModel($code,$phone);
		}
		
	}

}
