<?php

class SMSOnex
{
	#public static $liveAPIUrl = 'https://203.212.70.200/smpp';

	public static $liveAPIUrl = 'https://103.229.250.200/smpp';

	public static function sendMessage($ext, $num1, $data, $lang = Messages::MTYPE_ENGLISH, $dltId='')
	{
		if ($ext == '92' || $ext == '+92')
		{
			return false;
		}
		$sendSMS	 = Yii::app()->params['sendSMS'];
		$demoNumber	 = isset(Yii::app()->params['demoNumber']) ? Yii::app()->params['demoNumber'] : '';
		$num		 = str_replace('-', '', $num1);
		$number		 = ($demoNumber == '') ? $num : $demoNumber;
		$number		 = $num;
		if (!$sendSMS)
		{
			return;
		}

		// Check Blocked Numbers
		$mobile	 = $ext . $number;
		$cnt	 = UnsubscribePhoneno::checkBlockedNumber($mobile);
		if ($cnt > 0)
		{
			return 'Phone number blocked';
		}

		if ($ext == '91' || $ext == '+91' || $ext == '0' || $ext == '')
		{
			$response = self::sendLocalMessage($ext, $number, $data, $dltId);
		}
		else
		{
			$response = self::sendIntMessage($ext, $number, $data);
		}

		return $response;
	}

	public function utf8_to_unicode($str)
	{
		$unicode	 = array();
		$values		 = array();
		$lookingFor	 = 1;
		for ($i = 0; $i < strlen($str); $i++)
		{
			$thisValue = ord($str[$i]);
			if ($thisValue < 128)
			{
				$number		 = dechex($thisValue);
				$unicode[]	 = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
			}
			else
			{
				if (count($values) == 0)
					$lookingFor	 = ( $thisValue < 224 ) ? 2 : 3;
				$values[]	 = $thisValue;
				if (count($values) == $lookingFor)
				{
					$number		 = ( $lookingFor == 3 ) ?
							( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
							( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64
							);
					$number		 = dechex($number);
					$unicode[]	 = (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
					$values		 = array();
					$lookingFor	 = 1;
				} // if
			} // if
		}
		return implode("", $unicode);
	}

	public static function sendLocalMessage($ext, $number, $data, $dltId='')
	{
//		if($dltId != '')
//		{
//			return self::sendLocalMessageWithDLT($ext, $number, $data, $dltId);
//		}
		
		$user		 = "gozocb";
		$password	 = "sms@2021";
		$senderid	 = "GOZOIN";

		$message = urlencode($data);

		$url = "https://103.229.250.200/smpp/sendsms?";
		$url .= "username={$user}&password={$password}&to={$ext}{$number}&from={$senderid}&text={$message}";

		return self::send($url);
	}
	
	public static function sendLocalMessageWithDLT($ext, $number, $data, $dltId)
	{
		$user		 = "gozocb.trans";
		$password	 = "34nNL";
		$senderid	 = "GOZOIN";

		$message = urlencode($data);

		$url = "https://pgapi.vispl.in/fe/api/v1/send?";
		$url .= "username={$user}&password={$password}&unicode=false&to={$number}&from={$senderid}&text={$message}";
		$url .= "&dltContentId={$dltId}";

		return self::send($url);
	}

	public static function sendIntMessage($ext, $number, $data)
	{
		$senderid = "GOZOIN";

		$message = urlencode($data);

		$arrHeader['apiUrl']	 = "/sendsms?to={$ext}{$number}&from=$senderid&text=$message";
		$arrHeader['apiToken']	 = self::getToken();

		if ($arrHeader['apiToken'] && $arrHeader['apiToken'] != '')
		{
			$exc = new Exception('SENDING INTERNATIONAL SMS');
			Logger::warning($exc, true);
			
			return self::callApi($arrHeader);
		}

		return false;
	}

	public static function send($url)
	{
		$ch = curl_init();
		if (!$ch)
		{
			die("Couldn't initialize a cURL handle");
		}
		$ret = curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		#curl_setopt($ch, CURLOPT_POSTFIELDS, "User=$user&passwd=$password&sid=$senderid&mobilenumber={$ext}{$number}&message=$message&mtype=$messagetype&DR=$DReports");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
		// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");

		$curlresponse = curl_exec($ch);

		// execute
		if (curl_errno($ch))
		//echo 'curl error : ' . curl_error($ch);
			if (empty($ret))
			{
				// some kind of an error happened
				die(curl_error($ch));
				curl_close($ch);
				// close cURL handler
			}
			else
			{
				$info = curl_getinfo($ch);
				curl_close($ch);
				// close cURL handler
				//echo "<br>";
				//echo $curlresponse;
				//echo "Message Sent Succesfully" ;
			}

		return $curlresponse;
	}

	public static function getToken()
	{
		$token			 = Config::get("onex.int.sms.token.value");
		$tokenExpiryDate = Config::get("onex.int.sms.token.expirydate");

		$expiryTime	 = strtotime($tokenExpiryDate);
		$pastTime	 = strtotime("+1 day");

		if ($pastTime > $expiryTime)
		{
			$token = self::generateToken($token);
		}

		return $token;
	}

	public static function generateToken($oldToken)
	{
		$arrHeader['apiUrl'] = '/api/sendsms/token?action=generate';

		if (isset($oldToken) && $oldToken != '')
		{
			$arrData['old_token'] = $oldToken;
		}
		else
		{
			$arrHeader['apiKey'] = Config::get("onex.int.sms.apikey");
		}

		$jsonResponse = self::callApi($arrHeader, $arrData);

		$arrToken = json_decode($jsonResponse, true);

		if (is_array($arrToken) && isset($arrToken['token']) && isset($arrToken['expiryDate']))
		{
			Config::updateValueByName('onex.int.sms.token.value', $arrToken['token']);
			Config::updateValueByName('onex.int.sms.token.expirydate', $arrToken['expiryDate']);

			return $arrToken['token'];
		}

		return false;
	}

	public static function callApi($header, $arrData = array())
	{
		$methodType	 = isset($header['apiMethod']) ? $header['apiMethod'] : "POST";
		$apiURL		 = self::$liveAPIUrl . $header['apiUrl'];

		$arrHeaders		 = array();
		$arrHeaders[]	 = 'Content-Type: application/json';

		if ($header['apiKey'] && isset($header['apiKey']))
		{
			$arrHeaders[] = 'apikey: ' . $header['apiKey'];
		}
		if ($header['apiToken'] && isset($header['apiToken']))
		{
			$arrHeaders[] = 'Authorization: Bearer ' . $header['apiToken'];
		}

		$ch = curl_init($apiURL);

		if (is_array($arrData) && count($arrData) > 0)
		{
			$jsonData = json_encode($arrData);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		}

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $methodType);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);

		$jsonResponse = curl_exec($ch);

		return $jsonResponse;
	}

}
