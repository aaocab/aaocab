<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeviceInfo
 *
 * @author Dev
 * 
 * @property string $uuid
 * @property string $fcmToken
 * @property string $sessionToken
 * @property string $apkVersion
 * @property string $osVersion
 * @property string $model
 * @property string $brand
 * @property string $lat
 * @property string $long
 * @property string $status 
 */

namespace Beans\common;

class DeviceInfo
{

	public $uuid;
	public $fcmToken;
	public $sessionToken;
	public $apkVersion;
	public $osVersion;
	public $model;
	public $brand;
	public $lat;
	public $long;
	public $status = 0;

	public function init(\AppTokens $appToken = null)
	{
		if ($appToken == null)
		{
			$model = new \AppTokens();
		}
		else
		{
			$this->status	 = $appToken->apt_status;
			$appTokenDevice	 = $appToken->apt_device;
			$this->model	 = strtok($appTokenDevice, ' ');
			$this->brand	 = strstr($appTokenDevice, ' ');
		}


		return $model;
	}

	/**
	 * 
	 * @param \AppTokens $appToken
	 */
	public function setData(\AppTokens $appToken, $sendFcm = true)
	{
		$this->apkVersion	 = $appToken->apt_apk_version;
		$this->uuid			 = $appToken->apt_device_uuid;
		$appTokenDevice		 = $appToken->apt_device;
		$this->model		 = strtok($appTokenDevice, ' ');
		$this->brand		 = strstr($appTokenDevice, ' ');
		$this->sessionToken	 = $appToken->apt_token_id;
		$this->osVersion	 = $appToken->apt_os_version;
		$this->lat			 = $appToken->apt_last_loc_lat;
		$this->long			 = $appToken->apt_last_loc_long;
		$this->status		 = $appToken->apt_status;
		if ($sendFcm)
		{
			$this->fcmToken = $appToken->apt_device_token;
		}
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
		$model->apt_device			 = $this->model . ' ' . $this->brand;
		$model->apt_device_uuid		 = $this->uuid;
		$model->apt_token_id		 = $this->sessionToken;
		$model->apt_apk_version		 = $this->apkVersion;
		$model->apt_os_version		 = $this->osVersion;
		$model->apt_last_loc_lat	 = $this->lat;
		$model->apt_last_loc_long	 = $this->long;
		$model->apt_device_token	 = $this->fcmToken;
		return $model;
	}

	/**
	 * 
	 * @param \AppTokens $appToken
	 */
	public function setSessToken(\AppTokens $appToken)
	{
		$this->sessionToken	 = $appToken->apt_token_id;
		$this->status		 = $appToken->apt_status;
		return $this;
	}

}
