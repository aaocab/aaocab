<?php

namespace components\FirebaseCloudMessaging;

class Client implements ClientInterface
{

	const DEFAULT_API_URL								 = 'https://fcm.googleapis.com/fcm/send';
	const DEFAULT_TOPIC_ADD_SUBSCRIPTION_API_URL		 = 'https://iid.googleapis.com/iid/v1:batchAdd';
	const DEFAULT_TOPIC_REMOVE_SUBSCRIPTION_API_URL	 = 'https://iid.googleapis.com/iid/v1:batchRemove';

	private $apiKey;
	private $proxyApiUrl;

	/**
	 * add your server api key here
	 * read how to obtain an api key here: https://firebase.google.com/docs/server/setup#prerequisites
	 *
	 * @param string $apiKey
	 *
	 * @return \FirebaseCloudMessaging\Client
	 */
	public function setApiKey($apiKey)
	{
		$this->apiKey = $apiKey;
		return $this;
	}

	/**
	 * people can overwrite the api url with a proxy server url of their own
	 *
	 * @param string $url
	 *
	 * @return \FirebaseCloudMessaging\Client
	 */
	public function setProxyApiUrl($url)
	{
		$this->proxyApiUrl = $url;
		return $this;
	}

	/**
	 * sends your notification to the google servers and returns a guzzle repsonse object
	 * containing their answer.
	 *
	 * @param Message $message
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 * @throws \GuzzleHttp\Exception\RequestException
	 */
	public function send(Message $message)
	{
		$apiKey = sprintf('key=%s', $this->apiKey);
		return $this->post(
						$this->getApiUrl(),
						[
							'headers'	 => [
								"Authorization:{$apiKey}",
								'Content-Type:application/json'
							],
							'body'		 => json_encode($message)
						]
		);
	}

	/**
	 * @param integer $topic_id
	 * @param array|string $recipients_tokens
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function addTopicSubscription($topic_id, $recipients_tokens)
	{
		return $this->processTopicSubscription($topic_id, $recipients_tokens, self::DEFAULT_TOPIC_ADD_SUBSCRIPTION_API_URL);
	}

	/**
	 * @param integer $topic_id
	 * @param array|string $recipients_tokens
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function removeTopicSubscription($topic_id, $recipients_tokens)
	{
		return $this->processTopicSubscription($topic_id, $recipients_tokens, self::DEFAULT_TOPIC_REMOVE_SUBSCRIPTION_API_URL);
	}

	/**
	 * @param integer $topic_id
	 * @param array|string $recipients_tokens
	 * @param string $url
	 *
	 * @return \Message\ResponseInterface
	 */
	protected function processTopicSubscription($topic_id, $recipients_tokens, $url)
	{
		if (!is_array($recipients_tokens))
			$recipients_tokens = [$recipients_tokens];

		return $this->post(
						$url,
						[
							'headers'	 => [
								'Authorization'	 => sprintf('key = %s', $this->apiKey),
								'Content-Type'	 => 'application/json'
							],
							'body'		 => json_encode([
								'to'					 => '/topics/' . $topic_id,
								'registration_tokens'	 => $recipients_tokens,
							])
						]
		);
	}

	private function getApiUrl()
	{
		return isset($this->proxyApiUrl) ? $this->proxyApiUrl : self::DEFAULT_API_URL;
	}

	private function post($url, $data)
	{

		$header		 = $data['headers'];
		$bodyJson	 = $data['body'];
 
		$bodyArr = json_decode($bodyJson, false);

		if ($bodyArr->data->batchId != '' || $bodyArr->data->notificationId != '')
		{
			if ($bodyArr->data->notificationId > 0)
			{
				$bodyArr->messageID = 'NI_' . $bodyArr->data->notificationId;
			}
			else
			{
				$bodyArr->messageID = 'BI_' . $bodyArr->data->batchId;
			}
		}
		$body	 = json_encode($bodyArr);
//		echo "$body";
//exit;
		$curl	 = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => $body,
			CURLOPT_HTTPHEADER		 => $header,
		));

		$response	 = curl_exec($curl);
		$httpcode	 = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);
		$responseArr = json_decode($response, true);
		// Process result

		if ($httpcode != "200")
		{
			$responseArr['errorCode'] = $httpcode;
			//throw new FCMPushNotificationException($response, $httpcode);
		}

		return $responseArr;
	}

	private function post1($url, $data)
	{

		$header	 = $data['headers'];
		$body	 = $data['body'];

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => '{
		"priority": "HIGH",
		"data": {
		"title": "Sedan required urgent",
		"tripId": 2437528,
		"EventCode": 550,
		"filterCode": null,
		"Status": null,
		"message": "Sedan required at College More, EN Block, Sector V, Bidhannagar, West Bengal | on 16-02-2023 14:30 pm, One Way",
		"icon": "@drawable/logo",
		"sound": "default",
		"data": {
		"id": 1899499,
		"tripType": 1,
		"acceptAmount": 34033,
		"routes": [
		{
		"source": {
		"code": 30893,
		"address": "College More, EN Block, Sector V, Bidhannagar, West Bengal",
		"name": "Kolkata"
		},
		"destination": {
		"code": 30726,
		"name": "Ajmer"
		}
		}
		],
		"cabRate": {
		"cab": {
		"cabCategory": {
		"type": "Sedan",
		"catClass": "Value"
		}
		}
		},
		"tripDesc": "One Way ",
		"startDate": "2023-02-16",
		"startTime": "14:30:00",
		"tripId": 2437528,
		"routeName": "Kolkata, West Bengal - Ajmer, Rajasthan"
		},
		"isGozoNow": 1,
		"batchId": "24375281899499230216122810",
		"image": "https://firebase.google.com/images/social.png"
		},
		"to": "/topics/testVendor"
		}',
			CURLOPT_HTTPHEADER		 => array(
				'Content-Type: application/json',
				'Authorization: key = AAAADHwGjKY:APA91bGZdHlaMmP_zYej6qeesdcz3ivmEUujQjMZuVCGqk2043TCBEg5T-pe_mDQ0GYpxKQDQZqX2Fnmt-37YHsqMCb1cMfQ1ClxavibMI8bOc6NWDgqrQPvNH-Idfg5LYur6M2wrG9c'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

}
