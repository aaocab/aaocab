<?php

namespace Stub\vendor;

class BookingListResponse
{

	// Booking
	public $id, $bookingId, $bcbId, $type, $pickupDate, $pickupTime, $returnDate, $returnTime, $distance, $totalDays, $agentId, $trip_completion_time, $packageId, $totalTripDuration;
	public $active, $createdDate, $createdTime, $reconfirm, $adminId, $leadId, $shuttleId, $tripCompletionDate, $tripCompletionTime, $routeNames;
	public $statusDesc, $isPromoter, $bookingModified, $instructionToDriverVendor, $agentName;
	public $statusCode, $cngAllowed, $reconfirmFlag, $noShow, $dutySlipRequired, $driverAppRequired, $assignedCabId, $isAgent, $otpRequired;
	public $typeId, $bookingType, $isMatched;
	public $bidRange;
	public $isGozoNow;

	/** @var \Stub\common\Location $source */
	public $source;

	/** @var \Stub\common\Location $destination */
	public $destination;

	/** @var \Stub\common\Itinerary[] $routes */
	//public $routes = [];

	/** @var \Stub\common\Person $profile */
	public $profile;

	/** @var \Stub\common\Person $traveller */
	public $traveller;
	public static $tripTypes = ['ONE WAY' => '1', 'ROUND TRIP' => '2', 'MULTI WAY' => '3', 'AIRPORT TRANSFER' => 4, 'AIRPORT TRANSFER' => 12];

	/**
	 * 
	 * @return $this
	 */

	/** @var Booking $model */
	public function setData($booking)
	{
		
		$timeDuration				 = \Filter::getTimeDurationbyMinute($booking->bkg_trip_duration);
		$tripDay					 = \Filter::getTripDayByRoute($booking->bkgIds);
		$this->id					 = (int) $booking->bkgIds;
		$this->bookingId			 = $booking->bkgBookingIds;
		$this->tripId				 = $booking->bcb_id;
		$this->routeNames			 = $booking->bkg_route_name;
		$this->pickupDate			 = date("Y-m-d", strtotime($booking->bkg_pickup_date));
		$this->pickupTime			 = date("H:i:s", strtotime($booking->bkg_pickup_date));
		$this->bookingType->key	= (int) self::$tripTypes[$booking->booking_type];
		//$this->bookingType->key		 = $booking->booking_type;
				
		$this->bookingType->value	 = $booking->booking_type;
		if ($booking->bkg_return_date != "")
		{
			$this->returnDate	 = date("Y-m-d", strtotime($booking->bkg_return_date));
			$this->returnTime	 = date("H:i:s", strtotime($booking->bkg_return_date));
		}
		$this->tripCompletionDate	 = date("Y-m-d", strtotime($booking->trip_completion_time));
		$this->tripCompletionTime	 = date("H:i:s", strtotime($booking->trip_completion_time));
		$this->distance				 = (int) $booking->bkg_trip_distance;
		$this->totalDays			 = (int) ceil(($booking->bkg_trip_duration / 60) / 24);
		$this->totalTripDuration	 = $timeDuration . '(' . $tripDay . 'day)';
		$this->type					 = $booking->booking_type;
		$this->isGozoNow			 = (int) $booking->isGozoNow;
		$this->status				 = (int) $booking->bkg_status;
		$this->statusCode			 = (int) $result['code'];
		$this->statusDesc			 = $result['desc'];
		$this->createdDate			 = date("Y-m-d", strtotime($booking->bkg_create_date));
		$this->createdTime			 = date("H:i:s", strtotime($booking->bkg_create_date));
		return $this;
	}

	public function setModelData($booking, $showtype = null)
	{

		$booking = \Filter::convertToObject($booking);
		//print_r($booking);
		$this->setData($booking);	 
		if ($booking->frm_city_code != "")
		{
			$this->setCustomerBookingrouteData($booking);
		}

		$this->fare->amount			 = (double) $booking->bkg_total_amount;
		$this->traveller->firstName	 = $booking->bkg_user_fname;
		$this->traveller->lastName	 = $booking->bkg_user_lname;
		/* particular data for pending request */

		return $this;
	}

	public function setPendingData($trip, $dependency = null)
	{

		$this->matchType				 = (int) $trip->matchType;
		$this->is_cng_allowed			 = (int) ($trip->cab_lavel_id == 2 ? 2 : $trip->is_cng_allowed);
		$this->businesstype				 = $trip->businesstype;
		$this->btr_manual_assign_date	 = $trip->btr_manual_assign_date;
		$this->btr_critical_assign_date	 = $trip->btr_critical_assign_date;
		$this->booking_priority_date	 = $trip->booking_priority_date;
		$this->smtScore					 = $trip->smtScore;
		//$this->is_biddable		 = ($trip->smtScore<0) ? 1 : 0;
		$this->is_biddable				 = (int) $trip->is_biddable;
		if ($trip->bkg_booking_type == 4 || $trip->bkg_booking_type == 12)
		{
			if ($dependency < 60)
			{
				$this->is_biddable = (int) 1;
			}
		}
		if ($trip->bkg_booking_type == 2 || $trip->bkg_booking_type == 3)
		{
			$this->bidAlertMsg ="Customers have the freedom to enhance their journey by modifying their route or including new locations, cities or sightseeing spots or local attractions during the ride within the designated timeframe. Customers will not be charged extra for any travel within the quoted distance. If the total distance exceed the initial quoted distance, an extra km charge will be applied.";
		}
		
		$this->is_agent = (int) $trip->is_agent;
		
       
			//$this->cab->model	 = $trip->cab_model;
			$svcModel = \SvcClassVhcCat::model()->findByPk($trip->bkg_vehicle_type_id);
			$catLabel = \SvcClassVhcCat::getCatrgoryLabel($trip->bkg_vehicle_type_id);
//			if ($svcModel->scv_model>0)
//			{
//				
//				$vehicleType = \VehicleTypes::getCabMakeModel($svcModel->scv_model);
//				
//				$this->cab->model = $vehicleType['vht_make'].' '.$vehicleType['vht_model'];
//			}
//			else
//			{
//				$this->cab->model = $trip->cab_model;
//			}
		$this->cab->model = $catLabel;
		if ($trip->vht_make != "")
		{
			$this->cab->carModel = $trip->vht_model;
		}
		if ($trip->cab_lavel == 'Select Plus')
		{
			$this->cab->tier = 'Select';
		}
		else
		{
			$this->cab->tier = $trip->cab_lavel;
		}
		if ($trip->vht_make != '' || $trip->vht_make != NULL)
			{
				$vhtTypeModel = '-' . $trip->vht_make . ' ' . $trip->vht_model;
			}
		$this->cab->cabTypeTier		 = $catLabel . ' (' . $trip->cab_lavel . $vhtTypeModel . ')';
		$this->cab->seatingCapacity	 = (int) $trip->seatingCapacity;
		$this->cab->bigBagCapacity	 = (int) $trip->bigBagCapacity;
		$this->cab->bagCapacity		 = (int) $trip->bagCapacity;
		//$this->recommended_vendor_amount = (double) $trip->recommended_vendor_amount;
		if ($trip->is_biddable == 0)
		{
			$recommended_vendor_amount = ( $trip->acptAmount > 0 ? $trip->acptAmount : $trip->recommended_vendor_amount);
		}
		else
		{
			$recommended_vendor_amount = $trip->recommended_vendor_amount;
		}
		$this->recommended_vendor_amount = (double) $recommended_vendor_amount; // for showing accept and recomend ammount same if is bidable =0 according to Ak sir (18/10/22)
		$this->isFlexxi					 = (int) $trip->isFlexxi;
		$this->is_assured				 = (int) $trip->is_assured;
		$this->isMatched				 = (int) $trip->is_matched;
		$this->max_bid_amount			 = (double) $trip->max_bid_amount;
		$this->min_bid_amount			 = (double) $trip->min_bid_amount;
		$this->bvr_bid_amount			 = (double) $trip->bvr_bid_amount;
		$this->acptAmount				 = (int) $trip->acptAmount;
		$this->is_payment_due			 = (int) $trip->payment_due;
		$this->payment_msg				 = $trip->payment_msg;
		$this->bkg_night_pickup_included = (int) $trip->bkg_night_pickup_included;
		$this->bkg_night_drop_included	 = (int) $trip->bkg_night_drop_included;
	}

	public function setCustomerBookingrouteData($booking)
	{
		$this->source = new \Stub\common\Location();
		$this->source->setData($booking->frm_city_code, $booking->frm_city);

		$this->destination = new \Stub\common\Location();
		$this->destination->setData($booking->to_city_code, $booking->to_city);
	}

	public function setTravelData($result, $showType = null, $vendorId = null)
	{
		$trip			 = \Filter::convertToObject($result);
		
		$timeDuration	 = \Filter::getTimeDurationbyMinute($trip->bkg_trip_duration);
		$tripDay		 = \Filter::getTripDayByRoute($trip->bkgIds);
		$this->id		 = (int) $trip->bcb_id;
		if ($trip->is_biddable == 0)
		{
			$recommended_vendor_amount = ( $trip->acptAmount > 0 ? $trip->acptAmount : $trip->recommended_vendor_amount);
		}
		else
		{
			$recommended_vendor_amount = $trip->recommended_vendor_amount;
		}
		$getBidRange = \BookingVendorRequest::getBidRange($recommended_vendor_amount, $trip->maxAllowableVendorAmount);
		if (!empty($getBidRange))
		{
			$this->bidRange = $getBidRange;
		}
		
		
		if( $trip->bcb_trip_type ==1)
		{
			$this->routeNames	 = implode(" - ",\BookingCab::getRouteNameListById($trip->bcb_id));
		}
		else
		{
			$this->routeNames	 = $trip->bkg_route_name;
		}
		
		$this->pickupDate	 = date("Y-m-d", strtotime($trip->bkg_pickup_date));
		$this->pickupTime	 = date("H:i:s", strtotime($trip->bkg_pickup_date));
		if ($booking->bkg_return_date != "")
		{
			$this->returnDate	 = date("Y-m-d", strtotime($trip->bkg_return_date));
			$this->returnTime	 = date("H:i:s", strtotime($trip->bkg_return_date));
		}
		$this->bookingType->key		 = (int) self::$tripTypes[$trip->booking_type];
		$this->bookingType->value	 = $trip->booking_type;
		$this->isGozoNow			 = (int) $trip->isGozoNow;
		$this->totalDays			 = (int) ceil(($trip->bkg_trip_duration / 60) / 24);
		$this->totalTripDuration	 = $timeDuration . '(' . $tripDay . 'day)';
		if ($vendorId != null && $result['is_biddable'] == 0)
		{
			$statModel			 = \VendorStats::model()->getbyVendorId($vendorId);
			$dependency			 = $statModel->vrs_dependency;
			$vendorDependency	 = ($dependency == '' ? 0 : $dependency);
			if ($vendorDependency < 0)
			{
				$this->dependencyMsg = "Your dependability score is very low. If you deny this booking after you direct accept, you will be penalized.Partners with high dependability can direct accept without risk of denial penalty.";
			}
		}
		if ($showType == 'pendingList')
		{
			$this->setPendingData($trip, $dependency);
		}
		$trip = new \Stub\vendor\BookingListResponse();

		$this->bookings[] = $trip->setModelData($result);
		return $this;
	}

}
