<?php
namespace Stub\vendor;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class AuthRequest
{

	public $userName, $sProvider, $sIdentifier;

	/** @var \Stub\common\Platform $device */
	public $device;
	
		
	/** @var \Stub\common\Business $profile */
	public $profile;
	
	
	/** 
	 * 
	 * @param \Stub\vendor\Users $model
	 */
	public function getModel($model=null)
	{
		if($model==null)
		{
			/* @var $model Users */
			$model = new \Users();
		}
		$model->usr_name  = $this->profile->firstName;
		$model->usr_lname = $this->profile->lastName;
		$model->usr_email = $this->profile->email;
		$model->sProvider = $this->sProvider;
		$model->sIdentity = $this->sIdentifier;
		return $model;
	}

	

	/** @return \UserOAuth Model */
	public function getProviderModel()
	{
		if ($this->sIdentifier == null)
		{
			return false;
		}
		$model				 = new \UserOAuth();
		$model->provider	 = $this->sProvider;
		$model->identifier	 = $this->sIdentifier;
		return $model;
	}
	
	/** 
	 * 
	 * @return \AppTokens Model
	 */
	public function getTokenModel()
	{
		return $this->device->getAppToken();
	}
	
	/** @return \User Model */
	public function getUserModel()
	{
		$oauthModel = $this->getProviderModel();
		if ($oauthModel)
		{
			$userModel = Users::getModelBySocialAccount($oauthModel->identifier, $oauthModel->provider);
		}

		if ($userModel == null)
		{
			$userModel = \Users::validateModel($this->userName, $this->password);
		}

		return $userModel;
	}
        
        
        

}
