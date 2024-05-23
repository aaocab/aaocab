<?php

namespace Stub\vendor;

/**
 * vendor Pending request
 *
 *
 */
class VendorPendingRequest
{

	public $bid_status;
	public $service_type;
	public $sort;
	public $date;
	public $tier;
	public $zones;
	public $isGozoNow;

	/** @var \Stub\vendor\ServiceTier $tier */
	public $tierList;
	public $isNew;
	public $source;
	public $matchType;
	public $is_cng_allowed;
	public $businesstype;
	public $bcb_id;
	public $bkgIds;
	public $bkgBookingIds;
	public $bkg_route_name;
	public $bcb_bid_start_time;
	public $btr_manual_assign_date;
	public $btr_critical_assign_date;
	public $booking_priority_date;
	public $booking_type;
	public $is_biddable;
	public $is_agent;
	public $cab_model;
	public $recommended_vendor_amount;
	public $cab_lavel;
	public $max_bid_amount;
	public $min_bid_amount;
	public $is_assured;
	public $bkg_pickup_date;
	public $bkg_return_date;
	public $trip_completion_time;
	public $bvr_bid_amount;
	public $payment_due;
	public $payment_msg;
	public $bkg_night_pickup_included;
	public $bkg_night_drop_included;
	public $dataList;
	public $page_size;
	public $page_no;
	public $search_txt;
	public $denyStatus;
	public static $tripTypes = ['ONE_WAY' => '1', 'ROUND_TRIP' => '2']; //

	public function setData($model = null)
	{
		$obj->bidStatus		 = $this->bid_status;
		$obj->serviceType	 = $this->service_type;
		$obj->isGozoNow		 = $this->isGozoNow;
		$obj->sort			 = $this->sort;
		$obj->date			 = $this->date;
		$obj->page			 = $this->page_no;
		$obj->page_size		 = $this->page_size;
		$obj->search_txt	 = $this->search_txt;
		$obj->tierList		 = ServiceTier::setTier($this->tier);
		$obj->zones			 = $this->zones;
		$obj->denyStatus	 = $this->denyStatus;
		
		return $obj;
	}

	public function getData($result,$vendorId = null,$model = null)
	{
		$showType = "pendingList";
		$denyStatus = $model->denyStatus;
		foreach ($result as $res)
		{
			#print_r($res['bvr_accepted']);
			
			if($denyStatus == 0 )
			{
				if($res['bvr_accepted']!=2)
				{
				$trip = new \Stub\vendor\BookingListResponse();

				$this->dataList[] = $trip->setTravelData($res,$showType,$vendorId);
				}
			}
			else
			{
				$trip = new \Stub\vendor\BookingListResponse();
				$this->dataList[] = $trip->setTravelData($res,$showType,$vendorId);
			}
			
			
			
		}
		return $this;
	}

}
