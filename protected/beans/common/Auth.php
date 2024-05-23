<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author Deepak 
 * 
 * @property string $loginType
 * @property integer $userName
 * @property string $password
 * @property string $provider
 * @property integer $identifier
 * @property string $accessToken
 * @property string $sessionData
 * @property integer $email
 * @property string $familyName
 * @property string $givenName
 * @property integer $displayName
 * @property string $grantedScopes
 * @property string $firstName
 * @property string $lastName
 * @property string $gender
 * @property \Beans\common\DeviceInfo $device
 * 
 * @property string $bkgId; 
 * @property string $phoneNumber;
 * @property string $otp;
 * @property string $encCode;
 */

namespace Beans\common;

class Auth
{

	public $loginType;  // OTP/Social/Password
	public $userName;
	public $password;
	public $provider;
	public $identifier;
	public $accessToken;
	public $sessionData;
	public $familyName;
	public $givenName;
	public $displayName;
	public $grantedScopes;
	// TempLogin
	public $bkgId;
	public $phoneNumber;
	public $otp;
	public $encCode;

	/** @var \Beans\common\DeviceInfo $device */
	public $device;

	/** @var \Beans\contact\Person $person */
	public $person;

	/**
	 * 
	 * @return \Users
	 */
	public function getUserModel()
	{
		if (!empty($this->userName))
		{
			$userEmail	 = $this->userName;
			$userPass	 = md5($this->password);
		}
		else if (!empty($this->person->email))
		{
			$userEmail	 = $this->person->email;
			$userPass	 = $this->password;
		}
		$model						 = new \Users();
		$model->usr_email			 = $userEmail;
		$model->usr_password		 = $userPass;
		$model->usr_referred_code	 = $this->referredCode;
		$model->usr_name			 = $this->person->firstName;
		$model->usr_lname			 = $this->person->lastName;
		#$model->usr_country_code	 = $this->person->primaryContact->code;
		$model->usr_mobile			 = $this->person->phone;
		return $model;
	}

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

	public function getSocialLink()
	{
		$model					 = new \SocialAuth();
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
		$this->initiateSocialAuth();

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

//	/**
//	 * 
//	 * @param string $authId
//	 * @param integer $userId
//	 */
//	public function setCustomerData($authId, $userId = 0)
//	{
//		$this->authId	 = $authId;
//		$this->consumer	 = new \Stub\common\Consumer();
//		$this->consumer->setConsumerData($userId);
//	}

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
