<?php

class Messages
{

	const EVOLGENCE		 = 1;
	const SMSCOUNTRY		 = 2;
	const SMSONEX			 = 3;
	const SMARTSMS		 = 4;
	const MTYPE_ENGLISH	 = 1;
	const MTYPE_HINDI		 = 2;

	private $_smsGateway;

	public function __construct($type = Messages::SMSONEX)
	{
		
	}

	public static function checkProviderType($ext)
	{
		$smsSender			 = Config::get('sms.sender');
		$senderData			 = CJSON::decode($smsSender);
		$isSmsRoutingLive	 = Config::get('isSmsRoutingLive');
		if ($ext == '91' || $ext == '+91' || $ext == '0' || $ext == '')
		{
			if ($isSmsRoutingLive)
			{
				$senderRoutes	 = Config::get('sms.routes');
				$routesData		 = CJSON::decode($senderRoutes);
				$providerDetails = SmsLog::getProviderDetails(SmsLog::SMS_NATIONAL_PROVIDER_TYPE);
				$flag			 = 0;
				if ($providerDetails['slg_provider_type_1'] >= 0 && $routesData['EVOLGENCE'] > 0 && $providerDetails['slg_provider_type_1'] < $routesData['EVOLGENCE'])
				{
					$flag			 = 1;
					$providerType	 = Messages::EVOLGENCE;
				}
				else if ($providerDetails['slg_provider_type_2'] >= 0 && $routesData['SMSCOUNTRY'] > 0 && $providerDetails['slg_provider_type_2'] < $routesData['SMSCOUNTRY'])
				{
					$flag			 = 1;
					$providerType	 = Messages::SMSCOUNTRY;
				}
				else if ($providerDetails['slg_provider_type_3'] >= 0 && $routesData['SMSONEX'] > 0 && $providerDetails['slg_provider_type_3'] < $routesData['SMSONEX'])
				{
					$flag			 = 1;
					$providerType	 = Messages::SMSONEX;
				}
				else if ($providerDetails['slg_provider_type_4'] >= 0 && $routesData['SMARTSMS'] > 0 && $providerDetails['slg_provider_type_4'] < $routesData['SMARTSMS'])
				{
					$flag			 = 1;
					$providerType	 = Messages::SMARTSMS;
				}

				if ($flag == 0)
				{
					$providerType = $senderData['LOCAL_SMS'];
				}
			}
			else
			{
				$providerType = $senderData['LOCAL_SMS'];
			}
		}
		else
		{
			if ($isSmsRoutingLive)
			{
				$senderRoutes	 = Config::get('sms.intRoutes');
				$routesData		 = CJSON::decode($senderRoutes);
				$providerDetails = SmsLog::getProviderDetails(SmsLog::SMS_INTERNATIONAL_PROVIDER_TYPE);
				$flag			 = 0;
				if ($providerDetails['slg_provider_type_1'] >= 0 && $routesData['EVOLGENCE'] > 0 && $providerDetails['slg_provider_type_1'] < $routesData['EVOLGENCE'])
				{
					$flag			 = 1;
					$providerType	 = Messages::EVOLGENCE;
				}
				else if ($providerDetails['slg_provider_type_2'] >= 0 && $routesData['SMSCOUNTRY'] > 0 && $providerDetails['slg_provider_type_2'] < $routesData['SMSCOUNTRY'])
				{
					$flag			 = 1;
					$providerType	 = Messages::SMSCOUNTRY;
				}
				else if ($providerDetails['slg_provider_type_3'] >= 0 && $routesData['SMSONEX'] > 0 && $providerDetails['slg_provider_type_3'] < $routesData['SMSONEX'])
				{
					$flag			 = 1;
					$providerType	 = Messages::SMSONEX;
				}
				else if ($providerDetails['slg_provider_type_4'] >= 0 && $routesData['SMARTSMS'] > 0 && $providerDetails['slg_provider_type_4'] < $routesData['SMARTSMS'])
				{
					$flag			 = 1;
					$providerType	 = Messages::SMARTSMS;
				}

				if ($flag == 0)
				{
					$providerType = $senderData['INTERNATONAL_SMS'];
				}
			}
			else
			{
				$providerType = $senderData['INTERNATONAL_SMS'];
			}
		}
		switch ($providerType)
		{
			case Messages::SMSCOUNTRY:
				$smsObject	 = new SMSCountry();
				break;
			case Messages::SMSONEX:
				$smsObject	 = new SMSOnex();
				break;
			case Messages::SMARTSMS:
				$smsObject	 = new SmartSMS();
				break;
			case Messages::EVOLGENCE:
				$smsObject	 = new EvolgenceSMS();
				break;
			default:
				$smsObject	 = new SmartSMS();
				break;
		}

		return $smsObject;
	}

	public static function checkProvider($providerClass)
	{
		$providerType = 0;
		if (get_class($providerClass) == 'SMSCountry')
		{
			$providerType = Messages::SMSCOUNTRY;
		}
		else if (get_class($providerClass) == 'EvolgenceSMS')
		{
			$providerType = Messages::EVOLGENCE;
		}
		else if (get_class($providerClass) == 'SMSOnex')
		{
			$providerType = Messages::SMSONEX;
		}
		else if (get_class($providerClass) == 'SmartSMS')
		{
			$providerType = Messages::SMARTSMS;
		}
		return $providerType;
	}

	public static function getProviderObject($providerType, $ext)
	{
		$isSmsRoutingLive = Config::get('isSmsRoutingLive');
		if (!$isSmsRoutingLive)
		{
			$smsSender	 = Config::get('sms.sender');
			$senderData	 = CJSON::decode($smsSender);
			if ($ext == '91' || $ext == '+91' || $ext == '0' || $ext == '')
			{
				$providerType = $senderData['LOCAL_SMS'];
			}
			else
			{
				$providerType = $senderData['INTERNATONAL_SMS'];
			}
		}
		switch ($providerType)
		{
			case Messages::SMSCOUNTRY:
				$smsObject	 = new SMSCountry();
				break;
			case Messages::SMSONEX:
				$smsObject	 = new SMSOnex();
				break;
			case Messages::SMARTSMS:
				$smsObject	 = new SmartSMS();
				break;
			case Messages::EVOLGENCE:
				$smsObject	 = new EvolgenceSMS();
				break;
			default:
				$smsObject	 = new SmartSMS();
				break;
		}
		return $smsObject;
	}

	public function sendMessage($ext, $num1, $data, $data_flag = 1, $lang = Messages::MTYPE_ENGLISH, $dltId = '', $provider_type = "")
	{
		if ($data_flag == 1)
		{
			return false;
		}
		else if (!Filter::processPhoneNumber($num1, $ext))
		{
			return ['smsProvider' => null, 'smsProviderResponse' => "Phone number not valid"];
		}
		else
		{
			$intSMS = 1;
			if ($ext != '' && ($ext != '91' && !$intSMS))
			{
				return false;
			}
			$providerClass	 = $provider_type != null ? self::getProviderObject($provider_type, $ext) : self::checkProviderType($ext);
			$smsProvider	 = self::checkProvider($providerClass);
			$result			 = $providerClass->sendMessage($ext, $num1, $data, $lang, $dltId);
			$response		 = ['smsProvider' => $smsProvider, 'smsProviderResponse' => $result];
			return $response;
		}
	}

}
