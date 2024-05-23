<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SocialResponse
 *
 * @author Deepak/Souvik
 *  
 * @property string $provider
 * @property integer $identifier
 * @property string $accessToken

 */

namespace Beans\common;

class SocialResponse
{

	public $provider;
	public $identifier;
	public $accessToken;

	/** @return \UserOAuth|\SocialAuth */
	public function getSocialModel()
	{
		/**
		 * Sets default provider
		 */
		if (empty($this->provider))
		{
			$this->provider = \SocialAuth::Provider_Gozocabs;
		}

		if ($this->provider == \SocialAuth::Provider_Gozocabs)
		{
			return $this->getUserModel();
		}
		$model		 = $this->initiateSocialAuth();
		$token		 = $this->accessToken;
		$validate	 = $model->validateToken($token);
		if (!$validate)
		{
			throw new \Exception("Token mismatched", \ReturnSet::ERROR_VALIDATION);
		}
		return $model;
	}

	public function getSocialProfile()
	{
		/**
		 * Sets default provider
		 */
		if (empty($this->provider))
		{
			$this->provider = \SocialAuth::Provider_Gozocabs;
		}

		if ($this->provider == \SocialAuth::Provider_Gozocabs)
		{
			return $this->getUserModel();
		}
		$model		 = $this->initiateSocialAuth();
		$token		 = $this->accessToken;
		$provider	 = $model->connectProvider($token);
		$userProfile = $provider->getUserProfile();
		if (!$userProfile)
		{
			throw new \Exception("Token mismatched or expired", \ReturnSet::ERROR_VALIDATION);
		}

		return $userProfile;
	}

	public function initiateSocialAuth()
	{
		$model				 = new \SocialAuth();
		$model->provider	 = $this->provider;
		$model->identifier	 = $this->identifier;

		return $model;
	}

	public function getAuthModel()
	{
		$model = $this->initiateSocialAuth();

		switch ($model->provider)
		{
			case \SocialAuth::Provider_Google:

				break;

			case \SocialAuth::Provider_Facebook:
				break;
		}
	}

	public function setAuthData()
	{
		$model			 = $this->initiateSocialAuth();
		$model->device	 = new \Beans\common\DeviceInfo();
	}

	/**
	 * 
	 * @return \Users
	 */
	public function getUserLoginModel()
	{
		if (!empty($this->userName))
		{
			$userEmail	 = $this->userName;
			$userPass	 = $this->password;
		}
		$model						 = new \Users('login');
		$model->email				 = $userEmail;
		$model->usr_email			 = $userEmail;
		$model->usr_password		 = $userPass;
		$model->usr_create_platform	 = \Users::Platform_App;
		return $model;
	}
}
