<?php

class SMSCerf
{

	/**
	 * This function is used for sending message from CERF
	 * @param type $ext
	 * @param type $number
	 * @param type $message
	 * @param type $token
	 * @return curl response
	 */
	public static function sendMessage($ext, $number, $message, $dataFlag, $platform = Booking::Platform_App)
	{
		$sendSMS = Yii::app()->params['sendSMS'];
		if ($dataFlag == 1 || !$sendSMS)
		{
			return false;
		}
		$arrData['mobile']		 = $ext . $number;
		$arrData['message']		 = $message;
		$arrData['senderId']	 = Config::get('cerf.int.sms.senderId.value');
		$arrData['workflowId']	 = $platform == 3 ? Config::get("cerf.int.sms.WorkflowIdApp.value") : Config::get("cerf.int.sms.WorkflowIdWeb.value");
		$arrData['params']		 = new stdClass;
		$arrHeader['apiUrl']	 = Config::get("cerf.int.sms.sms_url.value");
		$arrHeader['apiMethod']	 = "POST";
		$arrHeader['XTenantID']	 = Config::get("cerf.int.sms.XTenantID.value");
		$arrHeader['apiToken']	 = self::getToken();
		if ($arrHeader['apiToken'] && $arrHeader['apiToken'] != '')
		{
			return self::callApi($arrHeader, $arrData);
		}
		return false;
	}

	/**
	 * This function is used getting token which  required for sending sms 	
	 * @return string
	 */
	public static function getToken()
	{
		$token			 = Yii::app()->cache->get("cerf.int.sms.token.value") !== false ? Yii::app()->cache->get("cerf.int.sms.token.value") : Config::getValueByName("cerf.int.sms.token.value");
		$tokenExpiryDate = Yii::app()->cache->get("cerf.int.sms.token.expirydate") !== false ? Yii::app()->cache->get("cerf.int.sms.token.expirydate") : Config::getValueByName("cerf.int.sms.token.expirydate");
		$expiryTime		 = strtotime($tokenExpiryDate);
		$pastTime		 = strtotime(date("Y-m-d H:i:s"));
		if ($pastTime >= $expiryTime)
		{
			$token = self::generateToken(date("Y-m-d H:i:s", strtotime("+1 hours")));
		}
		return $token;
	}

	/**
	 * This function is used  for generating token 
	 * @param type $expiryDateTime 
	 * @return string
	 */
	public static function generateToken($expiryDateTime)
	{
		$arrData['username']	 = Config::get("cerf.int.sms.username.value");
		$arrData['password']	 = Config::get("cerf.int.sms.password.value");
		$arrHeader['apiUrl']	 = Config::get('cerf.int.sms.auth_url.value');
		$arrHeader['apiMethod']	 = "POST";
		$jsonResponse			 = self::callApi($arrHeader, $arrData);
		$arrToken				 = json_decode($jsonResponse, true);

		if (is_array($arrToken) && !$arrToken['error'] && $arrToken['code'] == "SUCCESS")
		{
			Config::updateValueByName('cerf.int.sms.token.value', $arrToken['data']['token']);
			Config::updateValueByName('cerf.int.sms.token.expirydate', date("Y-m-d H:i:s", strtotime("+1 hours")));
			Yii::app()->cache->set('cerf.int.sms.token.value', $arrToken['data']['token'], 60 * 60, new CacheDependency('cerf.int.sms.token.value'));
			Yii::app()->cache->set('cerf.int.sms.token.expirydate', $expiryDateTime, 60 * 60, new CacheDependency('cerf.int.sms.token.expirydate'));
			return $arrToken['data']['token'];
		}
		return false;
	}

	/**
	 * This function is used  calling curl for sending sms/auth login for CERF 
	 * @param type $header 
	 * @param type $arrData 
	 * @return json String
	 */
	public static function callApi($header, $arrData = array())
	{
		$methodType		 = $header['apiMethod'];
		$apiURL			 = $header['apiUrl'];
		$arrHeaders		 = array();
		$arrHeaders[]	 = 'Content-Type: application/json';
		$ch				 = curl_init($apiURL);
		if ($header['apiToken'] && isset($header['apiToken']))
		{
			$arrHeaders[] = 'Authorization: Bearer ' . $header['apiToken'];
		}
		if ($header['XTenantID'] && isset($header['XTenantID']))
		{
			$arrHeaders[] = 'X-TenantID: ' . $header['XTenantID'];
		}
		if (is_array($arrData) && count($arrData) > 0)
		{
			$jsonData = json_encode($arrData);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		}
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $methodType);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);
		$jsonResponse = curl_exec($ch);
		return $jsonResponse;
	}

}
