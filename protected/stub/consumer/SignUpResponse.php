<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\consumer;

class SignUpResponse extends \Stub\common\Consumer
{

	/** @var \Stub\common\Platform $device */
	public $device;
	public $authId, $isNewUser, $verifyData, $requestData;

	/**
	 * 
	 * @param \Users $model
	 * @return \Users
	 */
	public function setModelData($model = null, $userDevice = null, $authId = null)
	{
		/** @var $model Users */
		if ($model == NULL)
		{
			$model = new \Users();
		}
		$this->authId				 = $authId;
		$this->setDataSet($model);
		$this->device->version		 = $userDevice->version;
		$this->device->uniqueId		 = $userDevice->uniqueId;
		$this->device->deviceName	 = $userDevice->deviceName;
		$this->device->token		 = $userDevice->token;
		$this->device->lat			 = $userDevice->lat;
	}

	/**
	 * 
	 * @param \Users $model
	 * @param boolean $isNewUser
	 * @param string $authId
	 * @param boolean $isNewUser
	 * @param string $verifyData
	 * @param string $requestData
	 * @param string $provider
	 * @param string $username
	 * @return boolean
	 */
	public function setModel(\Users $model, $isNewUser, $authId = null, $verifyData = null, $requestData = null, $provider = null, $username = null)
	{
		if ($model == null)
		{
			return false;
		}
		$this->setData($model);
		$this->authId		 = $authId;
		$this->isNewUser	 = $isNewUser;
		$this->provider		 = $provider;
		$this->userName		 = $username;
		$this->verifyData	 = $verifyData;
		$this->requestData	 = $requestData;
		return $this;
	}

	/**
	 * 
	 * @param \Users $model
	 * @param boolean $isNewUser
	 * @param string $authId
	 * @param boolean $isNewUser
	 * @param string $verifyData
	 * @param string $requestData
	 * @param string $provider
	 * @param string $username
	 * @param Array $$setModelInfo
	 * @return boolean
	 */
	public function setModelInfo(\Users $model, $isNewUser, $authId = null, $verifyData = null, $requestData = null, $provider = null, $username = null, $isEmailOrPhone = null)
	{
		if ($model == null)
		{
			return false;
		}
		$this->setDataSet($model);
		$this->authId		 = $authId;
		$this->isNewUser	 = $isNewUser;
		$this->provider		 = $provider;
		$this->userName		 = $username;
		$this->verifyData	 = $verifyData;
		$this->requestData	 = $requestData;
		if ($isEmailOrPhone['type'] == 2)
		{
			$this->primaryContact->code		 = $isEmailOrPhone['phCode'];
			$this->primaryContact->number	 = $isEmailOrPhone['phNumber'];
		}
		return $this;
	}

}
