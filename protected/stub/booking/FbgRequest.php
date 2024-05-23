<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FbgRequest
{

    public $tnc;
    public $referenceId;
    public $tripType;
    public $cabType;
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

    /** @var \Stub\common\AdditionalInfo $addtionalInfo */
    public $additionalInfo;
    public $sendEmail = 1, $sendSms   = 1;
	public $packageId;
	public $pickupDate;

    public function getModel($model = null)
    {
        if ($model == null)
        {
            $model = \Booking::getNewInstance();
        }
        $model->bkgTrail->bkg_tnc_id             = $this->tnc;
        $model->bkg_agent_ref_code               = $this->referenceId;
		$model->bkg_shuttle_id					 = $this->shuttleId;
        $model->bkg_vehicle_type_id              = $this->cabType;
        $model->bkgPref->bkg_send_email          = $this->sendEmail;
        $model->bkgPref->bkg_send_sms            = $this->sendSms;
        $model->bkg_booking_type                 = $this->tripType;
        $userInfo								 = \UserInfo::getInstance();
		
		$model->bkg_agent_id					 = $userInfo->userId;
        $model->bkgTrail->bkg_platform           = $userInfo->userType;

        $model->bkgTrail                = $this->platform->getData($model->bkgTrail);
        $model->bkgInvoice              = $this->fare->getFbgData($model->bkgInvoice);

        $routes = [];
        foreach ($this->routes as $route)
        {
            $routes[] = $route->getModel();
        }
        $model->bookingRoutes    = $routes;
        $rCount                  = count($routes);
        $model->bkg_from_city_id = $routes[0]->brt_from_city_id;
        $model->bkg_to_city_id   = $routes[$rCount - 1]->brt_to_city_id;
        $model->bkg_pickup_date  = $model->bookingRoutes[0]->brt_pickup_datetime;
		$model->bkg_trip_duration =  $model->bookingRoutes[0]->brt_trip_duration;
        $model->bkg_trip_distance = $model->bookingRoutes[0]->brt_trip_distance;
		$model->bkg_pickup_address = $routes[0]->brt_from_location;
		$model->bkg_pickup_lat  = (float)$routes[0]->brt_from_latitude;
		$model->bkg_pickup_long  = (float) $routes[0]->brt_from_longitude;
		$model->bkg_drop_address  = $routes[$rCount - 1]->brt_to_location;
		$model->bkg_dropup_lat  = (float)$routes[$rCount - 1]->brt_to_latitude;
		$model->bkg_dropup_long  = (float)$routes[$rCount - 1]->brt_to_longitude;
		
        $model->bkgUserInfo      = $this->traveller->getModel($model->bkgUserInfo);
        $model->bkgAddInfo       = $this->additionalInfo->getModel($model->bkgAddInfo);
		if($this->tripType == 5) {
			$model->bkg_package_id   =  $this->packageId;
			$model->bkg_pickup_date  =  $this->pickupDate;
		}
        return $model;
    }

}
