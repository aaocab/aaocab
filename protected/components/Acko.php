<?php

class Acko extends CComponent
{
	public $api_live = false;
	public $merchant_id;
	function __construct()
	{
		$this->init();
	}
	public function init()
	{

//		$this->txn_url		 = $domain . 'api/v1/co/pay/init';
//		$this->refund_url	 = $domain . 'api/v1/co/refund';
//		$this->request_url	 = $domain . 'api/v1/co/transaction/status';
//		$this->merchant_id	 = $config['merchant_id'];
//		$this->merchantKey	 = $config['merchantKey'];
	}

	public function initiateRequest($bkgid, $plan = "gozo_base")
	{
		$model			 = Booking::model()->findByPk($bkgid);
		$userName		 = trim($model->bkg_user_name . ' ' . $model->bkg_user_lname);
		$mobile			 = $model->bkg_contact_no;
		$rideType		 = $model->booking_types[$model->bkg_booking_type];
		$source			 = $model->bkg_pickup_address;
		$destination	 = $model->bkg_drop_address;
		$distance		 = $model->bkg_trip_distance;
		$tripDuration	 = $model->bkg_trip_duration;
		$carType		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc;
		$tripId			 = $model->bkg_bcb_id;
		$reqArr			 = [
		"plan"			 => "$plan",
		"name"			 => "$userName",
		"phone"			 => "$mobile",
		"ride_type"		 => "$rideType",
		"source"		 => "$source",
		"destination"	 => "$destination",
		"booked_on"		 => "2018-07-23T14:31:33.011011Z",
		"distance"		 => "$distance",
		"trip_duration"	 => "$tripDuration",
		"start_date"	 => "2018-07-26T14:31:33.011011Z",
		"end_date"		 => "2018-07-28T14:31:33.011011Z",
		"car_type"		 => "$carType",
		"trip_id"		 => "$tripId",
		"state"			 => "Haryana"
		];
		return $reqArr;
	}

	public function cancelRequest($bkgid)
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$policyId	 = "lCS8E_Vt2pBQI_luGAF0Ug";
		$plan		 = "gozo_gold";
		$type		 = "GozoCancellation";
		$reqArr		 = [
		"plan"			 => "$plan",
		"policy_id"		 => "$policyId",
		"type"			 => "$type",
		"cancelled_on"	 => "2018-07-21T22:22:27.873593Z",
		];
		return $reqArr;
	}

	public function emailRequest($bkgid)
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$emailId	 = $model->bkg_user_email;
		$policyId	 = "xxxx";
		$reqArr		 = [
		"email_address"	 => "$emailId",
		"policy_id"		 => "$policyId",
		];
		return $reqArr;
	}

	public function redirectionRequest($bkgid)
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$policyId	 = "xxxx";
		$reqArr		 = [
		"policy_id" => "$policyId",
		];
		return $reqArr;
	}

	public function claimRequest($bkgid)
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$policyId	 = "xxxx";
		$reqArr		 = [
		"policy_id" => "$policyId",
		];
		return $reqArr;
	}

}
