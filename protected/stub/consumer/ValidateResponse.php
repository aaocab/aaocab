<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\consumer;

class ValidateResponse extends \Stub\consumer\Session
{

	public $userId;
	public $message;
	public $versionCheck;
	public $sessionCheck;

	/** @var \Stub\common\Platform $device */
	public $device;

	//put your code here
	/**
	 * 
	 * @param \AppTokens $tokenModel
	 * @param \Users $model
	 * @param type $userId
	 * @param type $message
	 * @return $this
	 */
	public function setData(\AppTokens $tokenModel, \Users $model = null, $userId = 0, $info = [])
	{
		$this->versionCheck		 = $info['versionCheck'];
		$this->sessionCheck		 = $info['sessionCheck'];
		$this->message			 = $info['message'];
		$this->userId			 = $userId;
		$this->device->version	 = $tokenModel->apt_apk_version;
		$this->setModelData($tokenModel->apt_token_id, $model);
		return $this;
	}

	/** 
	 * 
	 * @param \AppTokens $model
	 * @param type $info
	 * @return $this
	 */
	public function setModel(\AppTokens $model, $info = [])
	{
		$this->versionCheck		 = $info['versionCheck'];
		$this->sessionCheck		 = $info['sessionCheck'];
		$this->message			 = $info['message'];
		$this->userId			 = $model->apt_user_id;
		$this->device->version	 = $model->apt_apk_version;
		if ($model)
		{
			$this->setModelData($model->apt_token_id, $model);
		}
		return $this;
	}

}
