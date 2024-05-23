<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\consumer;

/**
 * Description of DeviceResponse
 *
 * @author Roy
 */
class DeviceResponse extends \Stub\consumer\Session
{
	//put your code here
	public $status;
	public $userId;
	public $message;
	public $versionCheck;
	public $sessionCheck;
	public $currentDate;
	/** @var \Stub\common\Platform $device */
	public $device;
	
	/** 
	 * 
	 * @param \AppTokens $model
	 * @param type $info
	 * @return $this
	 */
	public function setModel(\AppTokens $model, \Users $umodel = null, $info = [])
	{
		$this->versionCheck		 = $info['versionCheck'];
		$this->sessionCheck		 = $info['sessionCheck'];
		$this->message			 = $info['message'];
		$this->currentDate       = $info['currentDate'];
		$this->userId			 = $model->apt_user_id;
		$this->device->version	 = $model->apt_apk_version;
		$this->setModelData($model->apt_token_id, $umodel);
		return $this;
	}
}
