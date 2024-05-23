<?php

class JWT extends CApplicationComponent
{

	public $key;

	public function init()
	{
		$basePath = dirname(__FILE__);
	}

	public function encode($payload)
	{
		return Firebase\JWT\JWT::encode($payload, $this->key);
	}

	public function decode($msg)
	{
		return Firebase\JWT\JWT::decode($msg, $this->key, array('HS256'));
	}

	/**
	 * This function is used for generating the token for the
	 * @param [Array] $payLoad
	 * @return [string] $jwtToken
	 */
	public function generateToken($payLoad, $validity = 3600)
	{
//		$validity		 = 3600; //In seconds
		$createdDateTime = round(microtime(true) * 1000);//date("Y-m-d h:i:sa");
		$issuedTime		 = time();
		$expiryTime		 = $issuedTime + $validity;

		$userId			 = $payLoad["userId"];
		$deviceInfo		 = $payLoad["deviceInfo"];
		$deviceId		 = $payLoad["deviceId"];
		$deviceOsVersion = $payLoad["deviceOsVersion"];

		$token		 = array
			(
			"websiteLink"	 => "https://www.gozocabs.com/",
			"data"			 => array
				(
				"userId"			 => $userId,
				"deviceInfo"		 => $deviceInfo,
				"deviceId"			 => $deviceId,
				"deviceOsVersion"	 => $deviceOsVersion,
				"createdDateTime"	 => $createdDateTime,
				"issuedTime"		 => $issuedTime,
				"expiryTime"		 => $expiryTime
			),
			"randomNumber"	 => rand(100000000, 999999999)
			//"randomNumber"	 => 656465484;
		);
		$jwtToken	 = $this->encode($token);
		return $jwtToken;
	}

	/**
	 * This 
	 * @param type $authToken
	 * @return type
	 */
	public function decodeToken($authToken)
	{
		if (empty($authToken))
		{
			return [];
		}

		$tokenData = $this->decode($authToken);

		return $tokenData;
	}

}
