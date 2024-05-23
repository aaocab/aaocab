<?php

namespace Stub\common;

/**
 * @property \Stub\common\Location $source
 * @property \Stub\common\Location $destination
 * @property \Stub\common\Itinerary[] $routes
 * @property \Stub\common\CabRate[] $cabRate
 * @property \Stub\common\Cab $cab
 * @property \Stub\common\Person $traveller
 * @property \Stub\common\Vehicle $car
 * @property \Stub\common\Driver $driver
 * @property \Stub\common\Fare $fare
 * @property \stub\common\Addons $addons
 */
class Booking
{

	// Booking
	public $id, $bookingId, $type, $tripType, $transferType, $pickupDate, $pickupTime, $returnDate, $returnTime, $distance, $duration, $agentId, $packageId, $bcbId;
	public $active, $createdDate, $createdTime, $reconfirm, $adminId, $leadId, $shuttleId, $routeNames;
	public $statusDesc, $isPromoter, $bookingModified, $instructionToDriverVendor, $agentName;
	public $statusCode, $cngAllowed, $reconfirmFlag, $noShow, $dutySlipRequired, $driverAppRequired, $assignedCabId, $isAgent, $otpRequired;
	public $typeId, $bookingType, $isGozoNow, $totalTripDuration, $cabType, $cabModel, $addonDetails, $travellBy, $defLeadId;
	public $suggestedOfferRange, $minAmount, $maxAmount;
	public $acceptAmount;
	public $pickupDiffMinutes;
	public $isconvertedToDR = 0;
	public $isReschedule;

	/** @var \Stub\common\Location $source */
	public $source;

	/** @var \Stub\common\Location $destination */
	public $destination;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes = [];

	/** @var \Stub\common\Person $profile */
	public $profile;

	/** @var \Stub\common\Person $traveller */
	public $traveller;

	/** @var \Stub\common\CabRate[] $cabRate */
	public $cabRate;

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\Vehicle $car */
	public $car;

	/** @var \Stub\common\Vehicle $car */
	public $vehicle;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\Addons $addons */
	public $addons;

	/** @var \Stub\common\AdditionalInfo $additionalInfo */
	public $additionalInfo;

	/** @var \Stub\common\Transactions[] $transactions */
	public $transactions;

	/** @var \Stub\common\PartnerTransactionDetails $partnerTransactionDetails */
	public $partnerTransactions;

	/** @var \Stub\common\Preference $preference */
	public $preference;

	/** @var \Stub\common\Zone $zone */
	public $zone;

	/** @var \Stub\common\State $region */
	public $region;

	/**
	 *
	 * @return $this
	 */

	/** @var Booking $model */
	public function setData($booking)
	{
		$result				 = \Booking::model()->getBookingCodeStatus($booking['bkg_status']);
		$this->id			 = (int) $booking['bkg_id'];
		$this->bookingId	 = $booking['bkg_booking_id'];
		$this->routeNames	 = json_decode($booking['bkg_route_city_names']);
		$this->pickupDate	 = date("Y-m-d", strtotime($booking['bkg_pickup_date']));
		$this->pickupTime	 = date("H:i:s", strtotime($booking['bkg_pickup_date']));
		if ($booking['bkg_return_date'] != "")
		{
			$this->returnDate	 = date("Y-m-d", strtotime($booking['bkg_return_date']));
			$this->returnTime	 = date("H:i:s", strtotime($booking['bkg_return_date']));
		}
		$this->distance		 = (int) $booking['bkg_trip_distance'];
		$this->duration		 = (int) $booking['bkg_trip_duration'];
		$this->type			 = (int) $booking['bkg_booking_type'];
		$this->status		 = (int) $booking['bkg_status'];
		$this->statusCode	 = (int) $result['code'];
		$this->statusDesc	 = $result['desc'];
		$this->createdDate	 = date("Y-m-d", strtotime($booking['bkg_create_date']));
		$this->createdTime	 = date("H:i:s", strtotime($booking['bkg_create_date']));
		$isReschedule = 0;
		if (in_array($booking['bkg_status'], [2, 3, 5]) && $booking['bpr_rescheduled_from'] == 0 && $booking['bkg_is_gozonow'] != 1 && $booking['bkg_pickup_date'] > date('Y-m-d H:i:s'))
		{
			$isReschedule =1 ;
		}
		$this->isReschedule  =  $isReschedule;
		return $this;
	}

	public function getPickupDate()
	{
		return $this->routes[0]->startDate . " " . $this->routes[0]->startTime;
	}

	public function SetPreference($booking, $criType)
	{
		$this->setData($booking);
		$this->preference = new \Stub\common\Preference();
		$this->preference->setData($booking, $criType);
		if ($criType != 37)
		{
			$this->zone		 = new \Stub\common\Zone();
			$this->zone->setData($booking['bkg_from_city_id']);
			$this->region	 = new \Stub\common\State();
			$this->region->setData($booking['stt_zone']);
		}
		return $this;
	}

	public function setModelData($booking)
	{
		$this->setData($booking);
		$this->setCustomerBookingrouteData($booking);
		
		/* @var $model \Booking */
		$model = \Booking::model()->findByPk($booking['bkg_id']);
		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate	 = $cabRate;

		$this->fare->amount			 = (int) $booking['bkg_total_amount'];
		$this->traveller->firstName	 = $booking['bkg_user_fname'];
		$this->traveller->lastName	 = $booking['bkg_user_lname'];
		return $this;
	}

	public function setCustomerBookingrouteData($booking)
	{
		$this->source = new Location();
		$this->source->setData($booking['frm_city_code'], $booking['frm_city']);

		$this->destination = new Location();
		$this->destination->setData($booking['to_city_code'], $booking['to_city']);
	}

	/* @deprecated */

	public function setCustomerBookingListData($booking)
	{
		$this->setData($booking);
		$this->totalAmt		 = $booking->bkg_total_amount;
		$this->userFirstName = $booking->bkg_user_fname;
		$this->userLastName	 = $booking->bkg_user_lname;
		$this->setCustomerBookingrouteData($booking);
		return $this;
	}

	public function fillCat($key, $res)
	{
		$this->id	 = $key;
		$this->name	 = $res;
	}

	public function setBookingCategory($model = null)
	{
		if ($model == null)
		{
			$model = new \Booking();
		}
		$bkgType = array_unique($model->prefRateBooking_types);

		foreach ($bkgType as $key => $res)
		{
			$obj				 = new \Stub\common\Booking();
			$obj->fillCat($key, $res);
			$data->dataList[]	 = $obj;
		}
		return $data;
	}

	public function setGNowPayload(\Booking $model)
	{
		/** @var \Booking $model */
		$this->routeNames			 = json_decode($model->bkg_route_city_names);
		$this->pickupDate			 = date("Y-m-d", strtotime($model->bkg_pickup_date));
		$this->pickupTime			 = date("H:i:s", strtotime($model->bkg_pickup_date));
		$this->type					 = (int) $model->bkg_booking_type;
		$this->source				 = new Location();
		$this->source->address		 = $model->bkg_pickup_address;
		$this->destination			 = new Location();
		$this->destination->address	 = $model->bkg_drop_address;
		$this->cab					 = new Cab();
		$this->cab->cabCategory		 = new CabCategory();
		$carmodel					 = $model->bkgSvcClassVhcCat;

		$this->cab->cabCategory->id			 = (int) $carmodel->scv_id;
		$this->cab->cabCategory->type		 = $carmodel->scc_VehicleCategory->vct_label;
		$this->cab->cabCategory->catClass	 = $carmodel->scc_ServiceClass->scc_label;
//		$this->cab->cabCategory->setData($carmodel->scv_id);
	}

	public function setBidData($val)
	{
		$this->id			 = (int) $val['bkg_id'];
		$this->pickupDate	 = date("Y-m-d", strtotime($val['bkg_pickup_date']));
		$this->pickupTime	 = date("H:i:s", strtotime($val['bkg_pickup_date']));
		$this->status		 = (int) $val['bkg_status'];
		$itinerary			 = new \Stub\common\Itinerary();
		$itinerary->setBidListData($val);
		$this->routes[]		 = $itinerary;

		$cab				 = new Cab();
		$cab->cabCategory	 = new CabCategory();
		$cab->cabCategory->setData($val['scv_id']);
		$this->cabRate		 = new CabRate();
		$this->cabRate->cab	 = $cab;
		$msg				 = "";
		$statusDesc			 = \Booking::model()->getActiveBookingStatus($val['bkg_status']);
		$notAllocatedMsg	 = "Not allocated to you";
		switch ($val['bkg_status'])
		{
			case 2:
				$msg = ($val['bvr_bid_amount'] > 0) ? ( ($val['bvr_is_gozonow'] == 1) ? "Offer made to customer" : "Make Gozo now offer to customer" ) : "Make offer to customer";
				break;
			case 9:
				$msg = $notAllocatedMsg;
				break;
			default :
				$msg = ($val['bcb_vendor_id'] != $val['bvr_vendor_id']) ? $notAllocatedMsg : $statusDesc;
				break;
		}
		$this->statusDesc = ucfirst($msg);
	}

	public function setNotificationData(\Booking $model, $event = null)
	{
		$this->id		 = (int) $model->bkg_id;
		$this->bookingId = $model->bkg_booking_id;
		$this->tripType	 = (int) $model->bkg_booking_type;
		$this->tripDesc	 = $model->getBookingType($model->bkg_booking_type);
		$this->startDate = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->tripId	 = (int) $model->bkg_bcb_id;
		$rtArr			 = json_decode($model->bkg_route_city_names);
		$this->routeName = implode(' - ', $rtArr);
		$routes			 = $model->bookingRoutes;
		if ($event == \Booking::CODE_VENDOR_GOZONOW_BOOKING_ALLOCATED)
		{
			$this->reachInMinutes = $model->reachInMinutes;
			foreach ($routes as $route)
			{
				$itinerary		 = new \Stub\common\Itinerary();
				$itinerary->setGNowWinBidNotifyModelData($route);
				$this->routes[]	 = $itinerary;
			}
		}
		if ($event == \Booking::CODE_VENDOR_GOZONOW_BOOKING_REQUEST)
		{
//			foreach ($routes as   $route)
//			{
			$itinerary			 = new \Stub\common\Itinerary();
			$itinerary->setGNowNotifyModelShortData($routes);
			$this->routes[]		 = $itinerary;
			$this->acceptAmount	 = (int) $model->bkgBcb->bcb_vendor_amount;
//			}
		}
		if ($event == \NotificationLog::CODE_VENDOR_ASSIGN_CAB_DRIVER)
		{
			$this->pickupDiffMinutes = $model->reachInMinutes;
		}


		$carmodel									 = $model->bkgSvcClassVhcCat;
		$this->cabRate								 = new \Stub\common\CabRate();
		$this->cabRate->cab->cabCategory->type		 = $carmodel->scc_VehicleCategory->vct_label;
		$this->cabRate->cab->cabCategory->catClass	 = $carmodel->scc_ServiceClass->scc_label;
	}

	public function setAllocatedGnowData(\Booking $model, $event = null)
	{
		$this->id			 = (int) $model->bkg_id;
		$this->bookingId	 = $model->bkg_booking_id;
		$this->tripType		 = (int) $model->bkg_booking_type;
		$this->tripDesc		 = $model->getBookingType($model->bkg_booking_type);
		$this->startDate	 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime	 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->tripId		 = (int) $model->bkg_bcb_id;
		$result				 = $model->getBookingCodeStatus();
		$this->bookingId	 = $model->bkg_booking_id;
		$this->referenceId	 = $model->bkg_agent_ref_code;
		$this->statusCode	 = (int) $result['code'];
		$statusDesc			 = $result['desc'];
		if ($model->bkg_reconfirm_flag == 0 && $model->bkg_status == 2)
		{
			$statusDesc = "Reconfirm Pending";
		}
		$this->statusDesc			 = $statusDesc;
		$this->otp					 = $model->bkgTrack->bkg_trip_otp;
		$this->arrivedForPickup		 = $model->bkgTrack->bkg_arrived_for_pickup;
		$this->noShow				 = $model->bkgTrack->bkg_is_no_show;
		$this->isDriverAppRequired	 = (int) $model->bkgPref->bkg_driver_app_required;
		$this->isTripOtpRequired	 = $model->bkgPref->bkg_trip_otp_required;
		$this->isDutyslipRequired	 = $model->bkgPref->bkg_duty_slip_required;
		$this->isCngAllowed			 = (int) $model->bkgPref->bkg_cng_allowed;
		$this->isGozoNow			 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
		$this->bcb_start_time		 = $model->bkgBcb->bcb_start_time;
		$this->bcb_end_time			 = $model->bkgBcb->bcb_end_time;
		$timeDuration				 = \Filter::getTimeDurationbyMinute($model->bkg_trip_duration);
		$tripDay					 = \Filter::getTripDayByRoute($model->bkg_id);
		$this->totalTripDuration	 = $timeDuration . '(' . $tripDay . 'day)';
		$this->cabType				 = (int) $model->bkg_vehicle_type_id;
		$this->startDate			 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime			 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->totalDistance		 = (int) $model->bkg_trip_distance;
		$this->estimatedDuration	 = (int) $model->bkg_trip_duration;

		$rtArr			 = json_decode($model->bkg_route_city_names);
		$this->routeName = implode(' - ', $rtArr);

		$this->setItinerary($model);
		$this->setCabRate($model);

		$this->setDriverInfo($model);
		$this->setVehicleInfo($model);
		$this->setUserInfo($model);
//		$this->user = new \Stub\common\Person();
//		$this->user->setModelData(\BookingUser::model()->getByBkgId($model->bkg_id));
	}

	public function setDetails(\Booking $model)
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

		$timeDuration			 = \Filter::getTimeDurationbyMinute($model->bkg_trip_duration);
		$tripDay				 = \Filter::getTripDayByRoute($model->bkg_id);
		$this->totalTripDuration = $timeDuration . '(' . $tripDay . 'day)';

		$this->statusDesc		 = $statusDesc;
		$this->tripType			 = $model->bkg_booking_type;
		$this->tripDesc			 = $model->getBookingType($model->bkg_booking_type);
		$this->cabType			 = (int) $model->bkg_vehicle_type_id;
		$this->startDate		 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$this->startTime		 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$this->totalDistance	 = (int) $model->bkg_trip_distance;
		$this->estimatedDuration = (int) $model->bkg_trip_duration;
		$this->id				 = (int) $model->bkg_id;
		$this->tripId			 = $model->bkg_bcb_id;

		$this->bookingStatus		 = $model->bkg_status;
		$this->flexxiType			 = $model->bkg_flexxi_type;
		$this->bookingModifiedOn	 = date('Y-m-d H:i:s', strtotime($model->bkg_modified_on));
		$this->bookingCreatedOn		 = date('Y-m-d H:i:s', strtotime($model->bkg_create_date));
		$this->bookingInstruction	 = $model->bkg_instruction_to_driver_vendor;
		$this->agentId				 = $model->bkg_agent_id;
		$this->isGozoNow			 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
		if ($model->bkgPref->bkg_is_gozonow != 0 && $model->bkgPf->bkg_additional_param != '')
		{
			$addParamJson							 = $model->bkgPf->bkg_additional_param;
			$addParamArr							 = json_decode($addParamJson, true);
			$suggestedOfferRange					 = $addParamArr['vndGnowOfferSuggestion'];
			$this->suggestedOfferRange->minAmount	 = $suggestedOfferRange['minVendorAmount'];
			$this->suggestedOfferRange->maxAmount	 = $suggestedOfferRange['maxVendorAmount'];
		}


		$this->bcb_start_time	 = $model->bkgBcb->bcb_start_time;
		$this->bcb_end_time		 = $model->bkgBcb->bcb_end_time;

		$this->startOdomreter		 = $model->bkgTrack->bkg_start_odometer;
		$this->endOdomreter			 = $model->bkgTrack->bkg_end_odometer;
		$this->otp					 = $model->bkgTrack->bkg_trip_otp;
		$this->arrivedForPickup		 = $model->bkgTrack->bkg_arrived_for_pickup;
		$this->noShow				 = $model->bkgTrack->bkg_is_no_show;
		$this->isDriverAppRequired	 = (int) $model->bkgPref->bkg_driver_app_required;
		$this->isTripOtpRequired	 = $model->bkgPref->bkg_trip_otp_required;
		$this->isDutyslipRequired	 = $model->bkgPref->bkg_duty_slip_required;
		$this->isCngAllowed			 = (int) $model->bkgPref->bkg_cng_allowed;
		if($model->bkg_booking_type==2 || $model->bkg_booking_type==3)
		{
			$this->bidAlertMsg = "Customers have the freedom to enhance their journey by modifying their route or including new locations, cities or sightseeing spots or local attractions during the ride within the designated timeframe. Customers will not be charged extra for any travel within the quoted distance. If the total distance exceed the initial quoted distance, an extra km charge will be applied.";
		}
		$this->setItinerary($model);
		$this->setCabRate($model);
		$this->setVehicleInfo($model);

		if ($model->bkg_agent_id != null)
		{
			$this->partnerTransactionDetails = new \Stub\common\PartnerTransactionDetails();
			$this->partnerTransactionDetails->setModelData($model->bkgInvoice);
		}

		$this->transactions = new \Stub\common\PaymentState();
		$this->transactions->setModels($model->bkg_id);

		$this->setVendorInfo($model);
		$this->setDriverInfo($model);
		$this->setUserInfo($model);
	}

	private function setVendorInfo($model)
	{
		if ($model->bkgBcb->bcb_vendor_id > 0)
		{
			$this->vendor = new \Stub\common\Vendor();
			$this->vendor->setModelData($model->bkgBcb->bcb_vendor_id);
		}
	}

	private function setVehicleInfo($model)
	{
		if ($model->bkgBcb->bcb_cab_id > 0)
		{
			$vhcmodel = $model->bkgBcb->bcbCab;

			$vehicle		 = new \Stub\common\Vehicle();
			$vehicle->setInfo($vhcmodel);
			$this->vehicle	 = $vehicle;
		}
	}

	private function setDriverInfo($model)
	{
		if ($model->bkgBcb->bcb_driver_id > 0)
		{
			$this->driver = new \Stub\common\Driver();
			$this->driver->setData($model->bkgBcb->bcbDriver);
		}
	}

	private function setUserInfo($model)
	{
		$this->user = new \Stub\common\Person();
		$this->user->setModelData($model->bkgUserInfo);
	}

	private function setItinerary($model)
	{
		$routes = $model->bookingRoutes;
		foreach ($routes as $route)
		{
			$itinerary		 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$this->routes[]	 = $itinerary;
		}
	}

	public function getJSONEncoded()
	{
		$jsonData = json_encode(\Filter::removeNull($this));
		return $jsonData;
	}

	public function getEncodedData()
	{
		return \Filter::encrypt($this->getJSONEncoded());
	}

	private function setCabRate($model)
	{
		$cabRate		 = new \Stub\common\CabRate();
		$cabRate->setModelData_v1($model->bkg_vehicle_type_id, $model->bkgInvoice);
		$this->cabRate	 = $cabRate;
	}

	/** @param \BookingTemp |  \Booking $model */
	public static function setModel($model)
	{
		$obj = new static();
		if ($model instanceof \BookingTemp)
		{

			$obj->isGozoNow	 = (int) $model->bkg_is_gozonow;
			$obj->travellBy	 = $model->bkg_traveller_type;
		}
		else
		{
			$obj->isGozoNow	 = (int) ($model->bkgPref->bkg_is_gozonow == 0) ? 0 : 1;
			$obj->travellBy	 = $model->bkgUserInfo->bkg_traveller_type;
		}


		$obj->bookingId = $model->bkg_booking_id;

		$obj->tripType			 = $model->bkg_booking_type;
		$obj->transferType		 = $model->bkg_transfer_type;
		$obj->tripDesc			 = $model->getBookingType($model->bkg_booking_type);
		$obj->cabType			 = (int) $model->bkg_vehicle_type_id;
		$obj->cabModel			 = (int) $model->bkg_vht_id;
		$obj->cab				 = new \Stub\common\Cab();
		$obj->cab->cabCategory	 = new CabCategory();
		$obj->cab->cabCategory->setData($obj->cabType);
		$obj->cab->categoryId	 = $obj->cab->cabCategory->scvVehicleId;
		$obj->startDate			 = date('Y-m-d', strtotime($model->bkg_pickup_date));
		$obj->startTime			 = date('H:i:s', strtotime($model->bkg_pickup_date));
		$obj->totalDistance		 = (int) $model->bkg_trip_distance;
		$obj->estimatedDuration	 = (int) $model->bkg_trip_duration;
		$obj->id				 = (int) $model->bkg_id;
		$obj->qrId				 = (int) $model->bkg_qr_id;
		$obj->addonDetails		 = $model->bkgAddonDetails;
		$obj->agentId			 = $model->bkg_agent_id;
		$obj->partnerReferenceId = $model->bkg_partner_ref_id;
		//  $obj->travellBy          = $model->bkgTravellBy;
		if(($model->bkg_agent_id == \Config::get('Kayak.partner.id') || $obj->agentId == \Config::get('Kayak.partner.id')) && $model->bkg_return_date!='')
		{
			$obj->returnDate         = $model->bkg_return_date;
			$obj->isconvertedToDR	 = $model->isconvertedToDR;
		}
		$obj->profile			 = new Person();
		if ($model instanceof \BookingTemp)
		{
			$obj->profile->setTempModelData($model);
		}
		$routes = $model->bookingRoutes;
		foreach ($routes as $route)
		{
			$itinerary		 = new \Stub\common\Itinerary();
			$itinerary->setModelData($route);
			$obj->routes[]	 = $itinerary;
		}
		return $obj;
	}

	/**
	 * @param \BookingTemp $model Description
	 * @return \BookingTemp 
	 */
	public function getLeadModel($model = null)
	{
		if ($this->id > 0 && $model == null)
		{
			$model = \BookingTemp::model($this->id);
		}
		if (!$model)
		{
			$model				 = new \BookingTemp();
			$model->bkg_agent_id = \Yii::app()->request->cookies['gozo_agent_id']->value;
			$model->bkg_partner_ref_id = \Yii::app()->request->cookies['gozo_partner_ref_id']->value;
		}
		if($this->partnerReferenceId != null)
		{
			$model->bkg_partner_ref_id = $this->partnerReferenceId;
		}
		$model->bkg_vehicle_type_id	 = $this->cabType;
		$model->bkg_vht_id			 = $this->cabModel;
		$model->bkg_booking_type	 = $this->tripType;
		$model->bkg_transfer_type	 = $this->transferType;
		if ($this->fare == null)
		{
			$this->fare = new \Stub\common\Fare();
		}
		$model->bkg_is_gozonow = $this->isGozoNow;

		$model->bkg_qr_id	 = \Yii::app()->request->cookies['gozo_qr_id']->value;
		$routes				 = [];
		foreach ($this->routes as $route)
		{
			$routes[] = $route->getModel();
		}
		if($model->bkg_agent_id == \Config::get('Kayak.partner.id')  && $this->returnDate!='' && in_array($model->bkg_booking_type, [2,3]) && count($routes) == 1)
		{
			$rtModel = $this->routes[0];
			if($model->bkg_booking_type==3)
			{
				$rtModel->source->setData($rtModel->destination->code, $rtModel->destination->name, $rtModel->destination->address, $rtModel->destination->coordinates->latitude, $rtModel->destination->coordinates->longitude, $rtModel->destination->isAirport);
			}
			$rtModel->returnDateTime = $this->returnDate;
			$routes[] = $rtModel->getModel();
		}
		if ($this->profile instanceof Person)
		{
			$this->profile->getTempModel($model);
		}
	 
		$model->bookingRoutes = $routes;
//		if($this->addons != null)
//		{
//			$model->bkgAddonDetails[] = ['adn_type' => $this->addons->type,'adn_id' => $this->addons->id,'adn_value' => $this->addons->charge];
//		}
		if ($this->addonDetails != null)
		{
			$model->bkgAddonDetails = $this->addonDetails;
		}
//$obj->travellBy	 = $model->bkgUserInfo->bkg_traveller_type;
		$model->bkgTravellBy	 = $this->travellBy;	
$model->bkg_traveller_type	 = $this->travellBy;	
		$model->isconvertedToDR = $this->isconvertedToDR;
		$rCount					 = count($routes);
		$model->bkg_from_city_id = $routes[0]->brt_from_city_id;
		$model->bkg_to_city_id	 = $routes[$rCount - 1]->brt_to_city_id;
		$model->bkg_pickup_date	 = $model->bookingRoutes[0]->brt_pickup_datetime;
		if(($model->bkg_agent_id == \Config::get('Kayak.partner.id') || $this->agentId == \Config::get('Kayak.partner.id')) && $this->returnDate!='')
		{
			$model->bkg_return_date  = $this->returnDate;
			$model->bookingRoutes[0]->parseReturnDateTime($model->bkg_return_date);
		}
		$model->bkg_user_device	 = \UserLog::model()->getDevice();
		$model->bkg_user_ip		 = \Filter::getUserIP();
		end:
		return $model;
	}

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}
		$model->bkgTrail->bkg_tnc_id	 = $this->tnc;
		$model->bkg_agent_ref_code		 = $this->referenceId;
		$model->bkg_shuttle_id			 = $this->shuttleId;
		$model->bkg_vehicle_type_id		 = $this->cabType;
		$model->bkgPref->bkg_is_gozonow	 = $this->isGozoNow;
		$model->bkgPref->bkg_send_email	 = $this->sendEmail;
		$model->bkgPref->bkg_send_sms	 = $this->sendSms;
		$model->bkg_booking_type		 = $this->tripType;
		$userInfo						 = \UserInfo::getInstance();

		$platformId						 = \Filter::getPlatform($userInfo->userId);
		$model->bkgTrail->bkg_platform	 = $platformId;

		if ($this->platform == null)
		{
			$this->platform = new Platform();
		}

		if ($this->fare == null)
		{
			$this->fare = new \Stub\common\Fare();
		}
		$model->bkgInvoice = $this->fare->getData($model->bkgInvoice);

		$routes = [];
		foreach ($this->routes as $route)
		{
			$routes[] = $route->getModel();
		}
		$model->bookingRoutes	 = $routes;
		$rCount					 = count($routes);
		$model->bkg_from_city_id = $routes[0]->brt_from_city_id;
		$model->bkg_to_city_id	 = $routes[$rCount - 1]->brt_to_city_id;
		$model->bkg_pickup_date	 = $model->bookingRoutes[0]->brt_pickup_datetime;
		if ($this->traveller == null)
		{
			$this->traveller = new \Stub\common\Person();
		}
		$model->bkgUserInfo = $this->traveller->getModel($model->bkgUserInfo);
		if ($this->additionalInfo == null)
		{
			$this->additionalInfo = new \Stub\common\AdditionalInfo();
		}
		$model->bkgAddInfo = $this->additionalInfo->getModel($model->bkgAddInfo);
		if ($this->tripType == 5)
		{
			$model->bkg_package_id	 = $this->packageId;
			$model->bkg_pickup_date	 = $this->pickupDate;
		}

		return $model;
	}

	/**
	 * @return static
	 * @throws \Exception
	 */
	public static function getDecodedObject($data)
	{
		$rDataObj = \Filter::decrypt($data);
		if ($rDataObj == false)
		{
			throw new \Exception("Invalid Data", 400);
		}



		$jsonMapper	 = new \JsonMapper();
		$obj		 = $jsonMapper->map($rDataObj, new static());
		return $obj;
	}

}
