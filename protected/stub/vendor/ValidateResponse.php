<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\vendor;

/**
 * Description of ValidateResponse
 *
 * @author Roy
 */
class ValidateResponse extends \Stub\vendor\Auth
{

	public $userId, $sessionCheck,$versionCheck, $documentUpload, $agreementUpload, $message;

	/** @var \Stub\common\Platform $device */
	public $device;

	//put your code here
	/**
	 * 
	 * @param \AppTokens $tokenModel
	 * @param \Vendors $model
	 * @param type $userId
	 * @return $this
	 */
	public function setData(\AppTokens $tokenModel, \Vendors $model = null, $userId = 0, $info = [])
	{
		
		$this->userId			 = $userId;
		$this->sessionCheck		 = $info['sessionCheck'];
		$this->versionCheck		 = $info['versionCheck'];
		$this->message			 = $info['message'];
		$this->agreementUpload	 = $info['isAgreement'];
		$this->documentUpload	 = $info['isDocument'];
		$this->device->version	 = $tokenModel->apt_apk_version;
       #print_r($model);
		if ($model)
		{
			$this->setModelData($tokenModel->apt_token_id, $tokenModel->apt_apk_version, $model);
		}
		#return $this;
	}

}
