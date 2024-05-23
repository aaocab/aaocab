<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of JWT
 *
 * @author Dev
 */
class JWTokens
{

	/**
	 * 
	 * @param type $token
	 * @return type
	 * @throws type
	 * @throws Exception
	 */
	public static function generateToken($token)
	{
		if (!$token)
		{
			throw Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		$appToken = AppTokens::model()->find('apt_token_id = :token AND apt_status=1', array('token' => $token));
		if (!$appToken)
		{
			throw new Exception("Token not active", ReturnSet::ERROR_INVALID_DATA);
		}
		$payLoad		 = new \Beans\common\JWToken();
		$validDuration	 = 30 * 24 * 60 * 60;
		$payLoad->setByAppToken($appToken, $validDuration);
		$data			 = Filter::removeNull($payLoad);
		$JWToken		 = Yii::app()->JWT->encode($data);
		return $JWToken;
	}

	/**
	 * 
	 * @param string $jwtToken
	 * @return \Beans\common\JWToken
	 * @throws Exception
	 */
	public static function validateAppTokenOld($jwtToken)
	{
		//$timeNow = time(); 
		$timeNow = strtotime(Filter::getDBDateTime());
		try
		{
			$data = Yii::app()->JWT->decodeToken($jwtToken);
		}
		catch (Exception $e)
		{
			throw $e;
		}
		/** @var \Beans\common\JWToken $tokenData */
		$tokenExpTime = $data->exp;
		if ($tokenExpTime < $timeNow)
		{
			throw new Exception("Token expired ", ReturnSet::ERROR_UNAUTHORISED);
		}
		return $data;
	}

	/**
	 * 
	 * @param string $jwtToken
	 * @return \Beans\common\JWToken
	 * @throws Exception
	 */
	public static function validateAppToken($jwtToken)
	{
		$timeNow		 = strtotime(Filter::getDBDateTime());
		$data			 = Yii::app()->JWT->decodeToken($jwtToken);
		/** @var \Beans\common\JWToken $tokenData */
		$tokenExpTime	 = $data->exp;

		if ($tokenExpTime < $timeNow)
		{
			throw new Exception("Token expired ", ReturnSet::ERROR_UNAUTHORISED);
		}
		return $data;
	}

	/**
	 * 
	 * @param type $userId
	 * @return type 
	 * @throws Exception
	 */
	public static function generateUserToken($userId)
	{
		$model			 = Users::model()->findByPk($userId);
		$payLoad		 = new \Beans\common\JWToken();
		$payLoad->setByUsers($model);
		$data			 = Filter::removeNull($payLoad);
		$validDuration	 = 30 * 24 * 60 * 60;
		$JWToken		 = \Yii::app()->JWT->encode($data, $validDuration);
		return $JWToken;
	}

	public static function decode($jwtToken)
	{
		$tks = \explode('.', $jwtToken);
		if (\count($tks) != 3)
		{
			throw new UnexpectedValueException('Wrong number of segments');
		}
		list($headb64, $bodyb64, $cryptob64) = $tks;
		$payload = Firebase\JWT\JWT::jsonDecode(Firebase\JWT\JWT::urlsafeB64Decode($bodyb64));
		return $payload;
	}
}
