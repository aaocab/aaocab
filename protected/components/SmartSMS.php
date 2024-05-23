<?php

class SmartSMS
{

	public static function sendMessage($ext, $num1, $data, $lang = Messages::MTYPE_ENGLISH, $dltId = '')
	{
		if ($ext == '92' || $ext == '+92')
		{
			return false;
		}
		$sendSMS	 = Yii::app()->params['sendSMS'];
		$demoNumber	 = isset(Yii::app()->params['demoNumber']) ? Yii::app()->params['demoNumber'] : '';
		$num		 = str_replace('-', '', $num1);
		$number		 = ($num != '') ? $num : $demoNumber;
		if (!$sendSMS)
		{
			return;
		}
		$mobile	 = $ext . $number;
		$cnt	 = UnsubscribePhoneno::checkBlockedNumber($mobile);
		if ($cnt > 0)
		{
			return 'Phone number blocked';
		}

		if ($ext == '91' || $ext == '+91' || $ext == '0' || $ext == '')
		{
			$response = self::sendLocalMessage($ext, $number, $data, $dltId, $lang);
		}
		else
		{
			$response = self::sendIntMessage($ext, $number, $data, $dltId, $lang);
		}
		return $response;
	}

	public static function sendLocalMessage($ext, $number, $data, $dltId = '', $lang = '')
	{
		$smartSmsSettings	 = Config::get('smartSms.settings');
		$smartSms			 = CJSON::decode($smartSmsSettings);
		$key				 = $smartSms['key'];
		$secret				 = $smartSms['secret'];
		$from				 = $smartSms['from'];
		$peid				 = $smartSms['peid'];
		$message			 = urlencode($data);
		$unicode			 = $lang == Messages::MTYPE_ENGLISH ? 'false' : 'true';
		$url				 = $smartSms['url'];
		$url				 .= "username={$key}&unicode={$unicode}&password={$secret}&from={$from}&to={$ext}{$number}&text={$message}&dltPrincipalEntityId={$peid}";
		if ($dltId != "")
		{
			$url .= "&dltContentId={$dltId}";
		}
		return self::send($url);
	}

	public static function sendIntMessage($ext, $number, $data, $dltId = '', $lang = '')
	{
		$smartSmsSettings	 = Config::get('smartSms.intSettings');
		$smartSms			 = CJSON::decode($smartSmsSettings);
		$key				 = $smartSms['key'];
		$secret				 = $smartSms['secret'];
		$from				 = $smartSms['from'];
		$peid				 = $smartSms['peid'];
		$message			 = urlencode($data);
		$unicode			 = $lang == Messages::MTYPE_ENGLISH ? 'false' : 'true';
		$url				 = $smartSms['url'];
		$url				 .= "username={$key}&unicode={$unicode}&password={$secret}&from={$from}&to={$ext}{$number}&text={$message}&dltPrincipalEntityId={$peid}";
		if ($dltId != "")
		{
			$url .= "&dltContentId={$dltId}";
		}
		return self::send($url);
	}

	public static function send($url)
	{
		try
		{
			$ch = curl_init();
			if (!$ch)
			{
				throw new Exception("Couldn't initialize a cURL handle", ReturnSet::ERROR_SERVER);
			}
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$curlresponse = curl_exec($ch);
			curl_close($ch);
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
		return $curlresponse;
	}

}
