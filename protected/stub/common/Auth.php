<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Auth
{

	public $userName;
	public $password;
	public $provider;
	public $identifier;
	public $accessToken;
	public $session_data;
	public $email;
	public $familyName;
	public $givenName;
	public $displayName;
	public $grantedScopes;
	public $firstName;
	public $lastName;
	public $gender;
	public $otp;
	public $requestData;
	public $verifyData;

	/** @var \Stub\common\Platform $device */
	public $device;

	/** @var \Beans\common\Verification[] $verifications */
	public $verifications;

	/**
	 * 
	 * @return \Users
	 */
	public function getUserModel($forceSignup = false)
	{
		if (!empty($this->userName))
		{
			$isEmail = \Filter::validateEmail($this->userName);
			$isPhone = false;
			if (!$isEmail)
			{
				$isPhone = \Filter::validatePhoneNumber($this->userName);
			}
			if ($isEmail)
			{
				$userEmail = $this->userName;
				if (!empty($this->profile->email))
				{
					$userEmail = $this->profile->email;
				}
				if ($forceSignup)
				{
					$userPhone = $this->profile->primaryContact->number;
				}
			}
			if ($isPhone)
			{
				$userPhone = $this->userName;
				if (!empty($this->profile->primaryContact->number))
				{
					$userPhone = $this->profile->primaryContact->number;
				}
				if ($forceSignup)
				{
					$userEmail = $this->profile->email;
				}
			}
			$userPass = md5($this->password);
		}
		else
		{
			$userEmail	 = $this->profile->email;
			$userPass	 = ($this->password);
		}



		$model						 = new \Users();
		$model->username			 = $this->userName;
		$model->usr_email			 = $userEmail;
		$model->usr_password		 = $userPass;
		$model->repeat_password		 = $userPass;
		$model->usr_referred_code	 = $this->referredCode;
		$model->usr_name			 = $this->profile->firstName;
		$model->usr_lname			 = $this->profile->lastName;
		$model->usr_country_code	 = $this->profile->primaryContact->code;
		$model->usr_mobile			 = $userPhone;
		return $model;
	}

	/** @return \Users|\SocialAuth */
	public function getSocialModel($forceSignup = false)
	{
		/**
		 * Sets default provider
		 */
		if (empty($this->provider))
		{
			$this->provider = \SocialAuth::Provider_aaocab;
		}

		if ($this->provider == \SocialAuth::Provider_aaocab)
		{
			return $this->getUserModel($forceSignup);
		}
		$model		 = $this->initiateSocialAuth();
		$token		 = $this->accessToken;
		$validate	 = $model->validateToken($token);
		if (!$validate)
		{
			throw new Exception("Token mismatched", \ReturnSet::ERROR_VALIDATION);
		}
		return $model;
	}

	/**
	 * 
	 * @return Auth
	 */
	public function getVerificationData()
	{
		$model->otp			 = $this->otp;
		$model->requestData	 = $this->requestData;
		$model->verifyData	 = $this->verifyData;
		return $model;
	}

	public function getSocialLink()
	{
		#print_r($this);
		$model->provider		 = $this->provider;
		$model->identifier		 = $this->identifier;
		$model->email			 = $this->email;
		$model->familyName		 = $this->familyName;
		$model->givenName		 = $this->givenName;
		$model->displayName		 = $this->displayName;
		$model->grantedScopes	 = $this->grantedScopes;
		$model->firstname		 = $this->firstName;
		$model->lastName		 = $this->lastName;
		$model->gender			 = $this->gender;

		return $model;
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

		switch ($this->provider)
		{
			case \SocialAuth::Provider_Google:

				break;

			case \SocialAuth::Provider_Facebook:
				break;
		}
	}

	/**
	 * 
	 * @return \AppTokens Model
	 */
	public function getTokenModel()
	{
		return $this->device->getAppToken();
	}

	/**
	 * 
	 * @param string $authId
	 * @param integer $userId
	 */
	public function setCustomerData($authId, $userId = 0)
	{
		$this->authId	 = $authId;
		$this->consumer	 = new \Stub\common\Consumer();
		$this->consumer->setConsumerData($userId);
	}

	public function setUserData($provider, $username)
	{
		$this->provider	 = $provider;
		$this->userName	 = $username;
	}

}
