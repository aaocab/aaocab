<?php

namespace Stub\common;

class Booking
{

	// Booking
	public $id, $bookingId, $type, $pickupDate, $pickupTime, $returnDate, $returnTime, $distance, $duration, $agentId, $packageId, $bcbId;
	public $active, $createdDate, $createdTime, $reconfirm, $adminId, $leadId, $shuttleId, $routeNames;
	public $statusDesc, $isPromoter, $bookingModified, $instructionToDriverVendor, $agentName;
	public $statusCode, $cngAllowed, $reconfirmFlag, $noShow, $dutySlipRequired, $driverAppRequired, $assignedCabId, $isAgent, $otpRequired;
	public $typeId,$bookingType;

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

	/** @var \Stub\common\CabRate[] $cabRate */
	public $cabRate;

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\Vehicle $car */
	public $car;

	/** @var \Stub\common\Driver $driver */
	public $driver;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\AdditionalInfo $additionalInfo */
	public $additionalInfo;

	/** @var \Stub\common\Transactions[] $transactions */
	public $transactions;

	/** @var \Stub\common\PartnerTransactionDetails $partnerTransactionDetails */
	public $partnerTransactions;

	/**
	 * 
	 * @return $this
	 */

	/** @var Booking $model */
	public function setData($booking)
	{
	
		$result = \Booking::model()->getBookingCodeStatus($booking->bkg_status);
		$this->id			 = (int) $booking->bkg_id;
		$this->bookingId	 = $booking->bkg_booking_id;
		$this->routeNames	 = json_decode($booking->bkg_route_city_names);
		$this->pickupDate	 = date("Y-m-d", strtotime($booking->bkg_pickup_date));
		$this->pickupTime	 = date("H:i:s", strtotime($booking->bkg_pickup_date));
		if ($booking->bkg_return_date != "")
		{
			$this->returnDate	 = date("Y-m-d", strtotime($booking->bkg_return_date));
			$this->returnTime	 = date("H:i:s", strtotime($booking->bkg_return_date));
		}
		$this->distance		 = (int) $booking->bkg_trip_distance;
		$this->duration		 = (int) $booking->bkg_trip_duration;
		$this->type			 = (int) $booking->bkg_booking_type;
		$this->status		 = (int) $booking->bkg_status;
		$this->statusCode	 = (int) $result['code'];
		$this->statusDesc	 = $result['desc'];
		$this->createdDate	 = date("Y-m-d", strtotime($booking->bkg_create_date));
		$this->createdTime	 = date("H:i:s", strtotime($booking->bkg_create_date));
		return $this;
	}

	public function setModelData($booking)
	{
		$this->setData($booking);
		$this->setCustomerBookingrouteData($booking);
		$this->fare->amount			 = (int) $booking->bkg_total_amount;
		$this->traveller->firstName	 = $booking->bkg_user_fname;
		$this->traveller->lastName	 = $booking->bkg_user_lname;
		return $this;
	}

	public function setCustomerBookingrouteData($booking)
	{
		$this->source = new Location();
		$this->source->setData($booking->frm_city_code, $booking->frm_city);

		$this->destination = new Location();
		$this->destination->setData($booking->to_city_code, $booking->to_city);
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
	public function fillCat($key,$res)
	{
		$this->id= $key;
		$this->name = $res;
	}
	public function setBookingCategory($model=null)
	{
		if($model==null)
		{
			$model	 = new \Booking();
		}
		$bkgType	 = array_unique($model->prefRateBooking_types);
		
		foreach ($bkgType as $key => $res)
		{
			$obj= new \Stub\common\Booking();
			$obj->fillCat($key,$res);
			$data->dataList[]	 = $obj;
		}
		return $data;
	}
}
