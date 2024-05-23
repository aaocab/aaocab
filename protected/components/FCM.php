<?php

Yii::import('application.vendors.FCMPushNotification.*');

class FCM extends CComponent
{

	/**
	 * FCMPushNotification Send 
	 */
	public static function send($tokens, $text, $param = array())
	{
		$arrResult				 = array();
		$arrResult['success']	 = 0;
		$arrResult["reason"]	 = '';

		try
		{
			if ((is_array($tokens) && count($tokens) > 0) && (is_array($text) && count($text) > 0) && (is_array($param) && count($param) > 0))
			{
				$firebaseApiKey = Yii::app()->params['firebase']['apiKey'];

				$FCMPushNotification = new FCMPushNotification($firebaseApiKey);

				if (isset($param['notifications']))
				{
					$arrNotification = $param['notifications'];

					if (isset($arrNotification['message']))
					{

						$arrFCMNotification						 = array();
						$arrFCMNotification['title']			 = (isset($arrNotification['title']) ? $arrNotification['title'] : '');
						$arrFCMNotification['bookingId']		 = (isset($arrNotification['bookingId']) ? $arrNotification['bookingId'] : '');
						$arrFCMNotification['tripId']			 = (isset($arrNotification['tripId']) ? $arrNotification['tripId'] : '');
						$arrFCMNotification['notificationId']	 = $arrNotification['notificationId'];
						$arrFCMNotification['body']				 = $arrNotification['message'];
						$arrFCMNotification['image']			 = $arrNotification['image'];
						$arrFCMNotification['sound']			 = (isset($arrNotification['sound']) ? $arrNotification['sound'] : 'default');

						$arrFCMData = $arrNotification;

						$aPayload					 = array();
						$aPayload['data']			 = $arrFCMData;
 
						$aOptions						 = array();
						$aOptions['mutable_content']	 = true;
						$aOptions['content_available']	 = true;
						$aOptions['time_to_live']		 = 0;

						if (in_array($arrFCMData['EventCode'], [550, 551, 560]))
						{
							$aPayload['android']	 = ['priority' => 'high'];
							$aOptions['priority']	 = 'high';
						}
						else
						{
							$aPayload['notification'] = $arrFCMNotification;
						}


						$aResult = $FCMPushNotification->sendToDevices(
								$tokens, $aPayload, $aOptions
						);

						self::log($tokens, $text, $aPayload, $aOptions);

						$arrResult["success"]	 = $aResult["success"];
						$arrResult["results"]	 = $aResult["results"];
						$arrResult["reason"]	 = 'Message Sent';
					}
					else
					{
						$arrResult['reason'] = 'Message not sent';
					}
				}
				else
				{
					$arrResult['reason'] = 'Notification not sent';
				}
			}
			else
			{
				$arrResult['reason'] = 'Tokens/ Text/ Params not set';
			}
		}
		catch (Exception $e)
		{
			$arrResult['reason'] = $e->getMessage();
		}


		return json_encode($arrResult);
	}

	/**
	 * FCMPushNotification Log 
	 */
	public static function log($tokens, $text, $payloadData = array(), $args = array())
	{
		$payloadData = http_build_query($payloadData);
		$args		 = http_build_query($args);
		$tokens		 = is_array($tokens) ? implode(', ', $tokens) : $tokens;

		$msg = "Sending FCM push notifications to " . $tokens . "\n" .
				"message: {$text}\n" .
				"payload data: " . str_replace('&', ', ', $payloadData) . "\n" .
				"arguments: " . str_replace('&', ', ', $args);

		Logger::create($msg, CLogger::LEVEL_INFO);
	}

	public static function sendMulti($vndTokenList, $title, $message, $payLoadData)
	{
		$notification = [
			'notifications' => [
				'title'		 => $title,
				'tripId'	 => $payLoadData['tripId'],
				'EventCode'	 => $payLoadData['EventCode'],
				'filterCode' => $payLoadData['FilterCode'],
				'Status'	 => $payLoadData['Status'],
				'message'	 => $message,
				'icon'		 => '@drawable/logo',
				'sound'		 => 'default',
				'data'		 => $payLoadData['data']
			]
		];
		if (isset($payLoadData['isGozoNow']))
		{
			$notification['notifications']['isGozoNow'] = $payLoadData['isGozoNow'];
		}


		$result['fcm'] = FCM::send(explode(',', $vndTokenList), $payLoadData, $notification);
	}

}
