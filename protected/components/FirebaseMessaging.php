<?php

use components\FirebaseCloudMessaging\Client;
use components\FirebaseCloudMessaging\Message;
use components\FirebaseCloudMessaging\Recipient\Device;
use components\FirebaseCloudMessaging\Recipient\Topic;
use components\FirebaseCloudMessaging\Notifications;

class FirebaseMessaging extends CComponent
{

	public static function sendToDevice($deviceTokenList, $payLoadData, $notificationData)
	{
		$firebaseApiKey	 = Yii::app()->params['firebase']['apiKey'];
		$client			 = new Client();
		$client->setApiKey($firebaseApiKey);

		$message = new Message();

		$message->addRecipients($deviceTokenList);

//		foreach ($deviceTokenList as $deviceToken)
//		{
//			$message->addRecipient(new Device($deviceToken));
//		}

		if(in_array($payLoadData['EventCode'], [550, 551, 560, 570]))
		{
			$message->setPriority('HIGH');
		}
		else
		{
			$objNotification = json_decode(json_encode($notificationData['notifications']), false);
			$notification	 = new Notifications($objNotification->title);
			$notification->setBody($objNotification->body);
			$message->setNotification($notification);
		}
		$message->setData($payLoadData);

		$response	 = [];
		$response	 = $client->send($message);
		return $response;
	}

	public static function sendToDevices($deviceTokenList, $payLoadData, $notificationData)
	{
		$firebaseApiKey	 = Yii::app()->params['firebase']['apiKey'];
		$client			 = new Client();
		$client->setApiKey($firebaseApiKey);

		$message = new Message();

		$objNotification = json_decode(json_encode($notificationData['notifications']), false);
		$notification	 = new Notification($objNotification->title, $objNotification->message);

		if($payLoadData['EventCode'] == 550 || $payLoadData['EventCode'] == 551)
		{
			$message->setPriority('HIGH');
			$payLoadData['EventCode'] += 100;
		}
		$message
				->addRecipients($deviceTokenList)
				->setNotification($notification)
				->setData($payLoadData);

		$response = $client->send($message);
		return $response;
	}

	public static function sendToTopic($topic, $payLoadData, $notificationData)
	{
		$firebaseApiKey	 = Yii::app()->params['firebase']['apiKey'];
		$client			 = new Client();
		$client->setApiKey($firebaseApiKey);

		$message		 = new Message();
		$message->setPriority('high');
		$message->addRecipient(new Topic($topic));
		$objNotification = json_decode(json_encode($notificationData['notifications']), false);
		$notification	 = new Notification($objNotification->title);

		$message
				->setNotification($notification)
				->setData($payLoadData);

		$response = $client->send($message);
		return $response;
	}

	public static function sendToTopics($topics, $payLoadData, $notificationData)
	{
		$firebaseApiKey	 = Yii::app()->params['firebase']['apiKey'];
		$client			 = new Client();
		$client->setApiKey($firebaseApiKey);
		$objNotification = json_decode(json_encode($notificationData['notifications']), false);
		$notification	 = new Notification($objNotification->title);

		$message = new Message();
		$message->setPriority('high');

		$conditionArr = [];
		foreach($topics as $topic)
		{
			$message->addRecipient(new Topic($topic));
			$conditionArr[] = '%s';
		}
		$conditionStr = implode(' && ', $conditionArr);

		$message
				->setNotification($notification)
				->setData($payLoadData)
				->setCondition($conditionStr);
		$response = $client->send($message);
		return $response;
	}

	public static function notifyForBooking($model, $vndTokenList, $batchId)
	{
//$model		 = Booking::model()->findByPk(1899499);
		$notify		 = new Stub\common\Notification();
		$notify->setGNowNotify($model);
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notify->message;
		$cabType	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$title		 = "$cabType required urgent";

		$notification = [
			'notifications' => [
				'title'		 => $title,
				'tripId'	 => $payLoadData['tripId'],
				'EventCode'	 => $payLoadData['EventCode'],
				'filterCode' => $payLoadData['FilterCode'],
				'Status'	 => $payLoadData['Status'],
				'body'		 => $message,
			]
		];
		if(isset($payLoadData['isGozoNow']))
		{
			$notification['notifications']['isGozoNow'] = $payLoadData['isGozoNow'];
		}
		$notification['notifications']['batchId'] = $batchId;

		$respArr = FirebaseMessaging::sendToDevice($vndTokenList, $payLoadData, $notification);
	}
}
