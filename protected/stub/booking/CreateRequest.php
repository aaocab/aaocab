<?php

namespace Stub\booking;

use Stub\common\Platform;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreateRequest
{

	public $tnc;
	public $referenceId;
	public $tripType;
	public $cabType;
	public $isGozoNow;
//    public $advanceReceived;
//    public $totalAmount;
//    public $device;
//    public $ip;
	public $apkVersion;
	public $plateForm;
	public $shuttleId;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\Platform $platform */
	public $platform;

	/** @var \Stub\common\Itinerary[] $routes */
	public $routes;

	/** @var \Stub\common\Person $traveller */
	public $traveller;

	/** @var \Stub\common\Cab $cab */
	public $cab;

	/** @var \Stub\common\AdditionalInfo $addtionalInfo */
	public $additionalInfo;
	public $sendEmail	 = 1;
	public $sendSms		 = 1;
	public $packageId;
	public $pickupDate;
	public $cabServiceCategory, $cabServiceClass;

	public function setModel($model)
	{
		
	}

	public function getModel($model = null, $agentId = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}
		$model->bkgTrail->bkg_tnc_id = $this->tnc;
		$model->bkg_agent_ref_code	 = $this->referenceId;
		$model->bkg_shuttle_id		 = $this->shuttleId;
		//$model->bkgBcb->bcb_trip_type            = $this->tripType;
		$model->bkg_vehicle_type_id	 = $this->cabType;
		// $model->bkgInvoice->bkg_corporate_credit = $this->advanceReceived;
//        $model->bkgInvoice->bkg_total_amount     = $this->totalAmount;
//        $model->bkgTrail->bkg_user_device        = \UserLog::model()->getDevice();
//        $model->bkgTrail->bkg_user_ip            = \UserLog::model()->getIP();
		$spiceId					 = \Config::get('spicejet.partner.id');
		$sugerboxId					 = \Config::get('sugerbox.partner.id');
		if ($agentId == $spiceId || $agentId == $sugerboxId)
		{
			$model->bkgPref->bkg_autocancel						 = 1;
			//$model->bkgPref->bkg_block_autoassignment			 = 1;
			$model->bkgPref->bkg_driver_app_required			 = 0;
			$model->bkgTrail->btr_stop_increasing_vendor_amount	 = 1;
		}
		//	$model->bkgPref->bkg_is_gozonow	 = $this->isGozoNow;
		$model->bkgPref->bkg_send_email	 = $this->sendEmail;
		$model->bkgPref->bkg_send_sms	 = $this->sendSms;
		//$model->bkg_booking_type		 = $this->tripType;
		$userInfo						 = \UserInfo::getInstance();
		//$model->bkg_agent_id				 = ($userInfo->userId > 0) ? $userInfo->userId : null;
		$model->bkg_agent_id			 = $agentId;
		//$platformId						 = \Filter::getPlatform($userInfo->userId);
		//$model->bkgTrail->bkg_platform	 = $platformId;

		$model->bkgTrail->bkg_platform		 = ($model->bkg_agent_id > 0) ? \Booking::Platform_CPAPI : \Booking::Platform_App;
		$model->bkgTrail->bkg_user_device	 = \UserLog::model()->getDevice();

		if ($this->platform == null)
		{
			$this->platform = new Platform();
		}
		//$model->bkgTrail = $this->platform->getData($model->bkgTrail);
		if ($this->fare == null)
		{
			$this->fare = new \Stub\common\Fare();
		}
		$model->bkgInvoice = $this->fare->getData($model->bkgInvoice, $model);

		$svcModel				 = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
		$model->bkgCabCategory	 = $this->cabServiceCategory;
		$model->bkgCabClass		 = $this->cabServiceClass;

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
		$model->bkg_booking_type = $model->bookingRoutes[0]->tripType == 12 ? $model->bookingRoutes[0]->tripType : $this->tripType;
		if ($model->bkg_booking_type == 12 || $model->bkg_booking_type == 4)
		{
			$fcityModel					 = \Cities::model()->getDetailsByCityId($model->bkg_from_city_id);
			$model->bkg_transfer_type	 = ($fcityModel['cty_is_airport'] == 1) ? 1 : 2;
		}
		if (in_array($this->tripType, [2, 3, 9, 10, 11]))
		{
			$model->bkg_booking_type = $this->tripType;
		}
		$model->bkg_create_date = new \CDbExpression('now()');
		if ($model->bkg_booking_type != 5)
		{
			$isGozonow							 = $model->bkgPref->bkg_is_gozonow;
			$cancelRuleId						 = \CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModel->scv_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type, $isGozonow);
			$model->bkgPref->bkg_cancel_rule_id	 = $cancelRuleId;
			\Logger::info("cancelRuleId : " . $cancelRuleId);
		}
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

	/** @return \BookingTemp */
	public function getLeadModel($model = null)
	{
		if ($model == null)
		{
			$model = new \BookingTemp();
		}
		$model->bkg_vehicle_type_id	 = $this->cabType;
		$model->bkg_booking_type	 = $this->tripType;
		if ($this->fare == null)
		{
			$this->fare = new \Stub\common\Fare();
		}
		$model->bkg_is_gozonow = $this->isGozoNow;

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
		if ($this->platform == null)
		{
			$this->platform = new Platform();
		}
		$model->bkg_user_name			 = $this->traveller->firstName;
		$model->bkg_user_lname			 = $this->traveller->lastName;
		$model->bkg_user_email			 = $this->traveller->email;
		$model->bkg_country_code		 = (int) $this->traveller->primaryContact->code;
		$model->bkg_contact_no			 = $this->traveller->primaryContact->number;
		$model->bkg_alt_country_code	 = $this->traveller->alternateContact->code;
		$model->bkg_alternate_contact	 = $this->traveller->alternateContact->number;
		if ($this->tripType == 5)
		{
			$model->bkg_package_id	 = $this->packageId;
			$model->bkg_pickup_date	 = $this->pickupDate;
		}
		return $model;
	}

	/** @return \BookingTemp */
//	public function getLeadModel($model = null)
//	{
//		if ($model == null)
//		{
//			$model = new \BookingTemp();
//		}
//		$model->bkg_vehicle_type_id	 = $this->cabType;
//		$model->bkg_booking_type	 = $this->tripType;
//		if ($this->fare == null)
//		{
//			$this->fare = new \Stub\common\Fare();
//			
//		}
//
//		$routes = [];
//		foreach ($this->routes as $route)
//		{
//			$routes[] = $route->getModel();
//		}
//
//		$model->bookingRoutes	 = $routes;
//		$rCount					 = count($routes);
//		$model->bkg_from_city_id = $routes[0]->brt_from_city_id;
//		$model->bkg_to_city_id	 = $routes[$rCount - 1]->brt_to_city_id;
//		$model->bkg_pickup_date	 = $model->bookingRoutes[0]->brt_pickup_datetime;
//		if ($this->traveller == null)
//		{
//			$this->traveller = new \Stub\common\Person();
//		}
//		$model->bkg_user_name			 = $this->traveller->firstName;
//		$model->bkg_user_lname			 = $this->traveller->lastName;
//		$model->bkg_user_email			 = $this->traveller->email;
//		$model->bkg_country_code		 = (int) $this->traveller->primaryContact->code;
//		$model->bkg_contact_no			 = $this->traveller->primaryContact->number;
//		$model->bkg_alt_country_code	 = $this->traveller->alternateContact->code;
//		$model->bkg_alternate_contact	 = $this->traveller->alternateContact->number;
//		if ($this->tripType == 5)
//		{
//			$model->bkg_package_id	 = $this->packageId;
//			$model->bkg_pickup_date	 = $this->pickupDate;
//		}
//		return $model;
//	}
}
