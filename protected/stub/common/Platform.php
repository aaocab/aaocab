<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Platform
{

    public $type, $version, $osVersion, $uniqueId, $deviceName, $token, $authId, $lat, $long, $ip;
	public $platform;

	public function __construct($lat = '', $long = '')
    {
        if ($lat != '')
        {
            $this->lat = $lat;
        }

        if ($long != '')
        {
            $this->long = $long;
        }
    }

    public function getData(\BookingTrail $model = null)
    {
        /** @var BookingTrail $model */
        if ($model == null)
        {
            $model = new \BookingTrail();
        }
        $model->bkg_user_device = $this->deviceName;
        $model->bkg_user_ip     = $this->ip;
        //$model->bkg_platform    = $this->type;
        return $model;
    }

//	public function getBookingTrackLogData(\BookingTrackLog $model = null)
//	{
//
//	}

    /**
     * 
     * @param \AppTokens $appToken
     */
    public function setData(\AppTokens $appToken)
    {
        $this->version    = $appToken->apt_apk_version;
        $this->uniqueId   = $appToken->apt_device_uuid;
        $this->deviceName = $appToken->apt_device;
        $this->token      = $appToken->apt_device_token;
        $this->lat        = $appToken->apt_last_loc_lat;
        $this->long       = $appToken->apt_last_loc_long;
		$this->platform   = $appToken->apt_platform;
    }

    /**
     * 
     * @param \AppTokens $model
     * @return \AppTokens
     */
    public function getAppToken(\AppTokens $model = null)
    {
        if ($model == null)
        {
            $model = new \AppTokens();
        }
        $model->apt_device        = $this->deviceName;
        $model->apt_device_token  = $this->token;
        $model->apt_device_uuid   = $this->uniqueId;
        $model->apt_token_id      = $this->authId;
        $model->apt_apk_version   = $this->version;
        $model->apt_os_version    = $this->osVersion;
        $model->apt_last_loc_lat  = $this->lat;
        $model->apt_last_loc_long = $this->long;
		$model->apt_platform      = $this->platform;
        return $model;
    }

    /**
     * 
     * @return string
     */
    public function getVersionByPlatform()
    {
        $activeVersion = \AppTokens::getVersionByApp($this->type);
        return $activeVersion;
    }

    /** @deprecated since 2022-02-03 */
	public function getModel(\BookingTrackLog $model = null, $data)
	{
        /** @var BookingTrackLog $model */
        if ($model == null)
        {
            $model = new \BookingTrackLog();
        }

        return $model->btl_device_info = \CJSON::encode($data);
    }

	public function checkAppToken(\Drivers $model, $data)
	{
		$data;
		$model->drv_id;
		$checkAppToken = \AppTokens::model()->checkToken($data->uniqueId);

	$this;

//		$appTokenModel					 = new AppTokens();
//		$appTokenModel->apt_user_id		 = $userId;
//		$appTokenModel->apt_entity_id	 = $model->drv_id;
//		$appTokenModel->apt_token_id	 = $sessionId;
//		$appTokenModel->apt_device		 = $deviceData['deviceName'];
//		$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
//		$appTokenModel->apt_device_uuid	 = $deviceData['uniqueId'];
//		$appTokenModel->apt_user_type	 = 5;
//		$appTokenModel->apt_apk_version	 = $deviceData['version'];
//		$appTokenModel->apt_ip_address	 = $ipAddress;
//		$appTokenModel->apt_os_version	 = $deviceData['osVersion'];
//		$appTokenModel->apt_device_token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
//		$result							 = $appTokenModel->insert();
	}

}
