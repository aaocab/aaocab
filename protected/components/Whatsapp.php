<?php

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

class Whatsapp extends CComponent
{

	public static function initiate()
	{
		$arrSettings	 = json_decode(Config::get("whatsapp.settings"), true);
		$fromPhoneNoId	 = $arrSettings['fromPhoneNoId'];
		$accessToken	 = $arrSettings['accessToken'];
		$canSendWhatsApp = $arrSettings['canSendWhatsApp'];
		if (!$canSendWhatsApp)
		{
			throw new Exception("Cannot send whatsapp.", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
		}
		$whatsappCloudApi = new WhatsAppCloudApi([
			'from_phone_number_id'	 => $fromPhoneNoId,
			'access_token'			 => $accessToken,
			'graph_version'			 => 'v17.0',
		]);

		return $whatsappCloudApi;
	}

	public static function sendTextMessage($phoneNo, $arrComponent)
	{
		$whatsapp = self::initiate();

		$body = ((is_array($arrComponent['body']) && isset($arrComponent['body'][0]['text'])) ? $arrComponent['body'][0]['text'] : $arrComponent['body']);

		return $whatsapp->sendTextMessage($phoneNo, $body);
	}

	public static function sendTemplateMessage($phoneNo, $arrComponent, $templateName, $lang)
	{
		$whatsapp = self::initiate();

		$header	 = (isset($arrComponent['header']) ? $arrComponent['header'] : []);
		$body	 = (isset($arrComponent['body']) ? $arrComponent['body'] : []);
		$buttons = (isset($arrComponent['buttons']) ? $arrComponent['buttons'] : []);

		$components = new Component($header, $body, $buttons);

		return $whatsapp->sendTemplate($phoneNo, $templateName, $lang, $components);
	}

	public function sendMessage($phoneNo, $arrComponent, $templateName = '', $arrData = [], $lang = 'en_US', $isDelay = false, $whlId = null)
	{
//		Whatsapp::writeSentMsgFile('sendMessage', 'a+');
		try
		{
			if (!$whlId)
			{
				$whlId = self::processSendMsgLog($phoneNo, $arrComponent, $templateName, $arrData);
			}
			if (!$isDelay)
			{
				if ($templateName && $templateName != '')
				{
					$response = self::sendTemplateMessage($phoneNo, $arrComponent, $templateName, $lang);
				}
				else
				{
					$response = self::sendTextMessage($phoneNo, $arrComponent);
				}

				$arrStatus = self::processSentMsgResponse($response);
				self::processSendMsgLog($phoneNo, $arrComponent, $templateName, $arrData, $response, $whlId);
			}
		}
		catch (Exception $ex)
		{
			$errMsg = $ex->getMessage();
			if ($whlId)
			{
				WhatsappLog::processLog(['whl_sent_response' => $errMsg, 'whl_status' => 3], '', $whlId);
			}
			$jsonError = json_decode($errMsg, true);
			if ($jsonError['error']['code'] != 131026)
			{
				$arrStatus = ['status' => 3, 'wamId' => '', 'error' => $errMsg];
				\Sentry\captureMessage(json_encode(['template' => $templateName, 'arrComponent' => $arrComponent, 'arrData' => $arrData, 'status' => 3, 'wamId' => $whlId]), null);
				Logger::error($ex);
				Logger::exception($ex);
			}
		}
		if ($whlId)
		{
			$arrStatus['whlId'] = $whlId;
		}
		return $arrStatus;
	}

	public static function processSentMsgResponse($response)
	{
		$status	 = 3;
		$wamId	 = '';
		$errMsg	 = '';

		#Whatsapp::writeSentMsgFile('processSentMsgResponse', 'a+');

		$arrResponse = json_decode($response->body(), true);

		#Whatsapp::writeSentMsgFile(json_encode($arrResponse), 'a+');

		if ($arrResponse)
		{
			if (isset($arrResponse['messages'][0]['id']))
			{
				$status	 = 2;
				$wamId	 = $arrResponse['messages'][0]['id'];
			}
		}

		#Whatsapp::writeSentMsgFile(json_encode($arrResponse), 'a+');

		return ['status' => $status, 'wamId' => $wamId, 'error' => $errMsg];
	}

	public static function processSendMsgLog($phoneNo, $arrComponent, $templateName = '', $arrData = [], $response = '', $whlId = '')
	{
		#Whatsapp::writeFile('processSendMsgLog');

		$logData	 = [];
		$arrStatus	 = [];

		if ($response != '')
		{
			$arrStatus = self::processSentMsgResponse($response);

			$logData['whl_wam_id']			 = $arrStatus['wamId'];
			$logData['whl_sent_response']	 = $response->body();
			$logData['whl_status']			 = $arrStatus['status'];
			if ($arrStatus['status'] == 2)
			{
				$logData['whl_sent_date'] = new CDbExpression('NOW()');
			}
			$whlId = WhatsappLog::processLog($logData, '', $whlId);
		}
		else
		{

			#Whatsapp::writeFile('TemplateId: ' . $templateId, 'a+');
			$templateId							 = (isset($arrData['templateId']) && $arrData['templateId'] > 0) ? $arrData['templateId'] : WhatsappLog::findByTemplateName($templateName, 'wht_id');
			$logData['whl_phone_number']		 = $phoneNo;
			$logData['whl_message_type']		 = WhatsappLog::MSG_TYPE_TEXT;
			$logData['whl_message']				 = json_encode($arrComponent['body']);
			$logData['whl_message_component']	 = json_encode($arrComponent);
			$logData['whl_created_by_type']		 = UserInfo::getUserType();
			$logData['whl_created_by_id']		 = UserInfo::getUserId();
			$logData['whl_created_date']		 = new CDbExpression('NOW()');
			$logData['whl_status']				 = 1;

			((isset($arrData['entity_type']) && $arrData['entity_type'] > 0) ? $logData['whl_entity_type']	 = $arrData['entity_type'] : '');
			((isset($arrData['entity_id']) && $arrData['entity_id'] > 0) ? $logData['whl_entity_id']	 = $arrData['entity_id'] : '');
			((isset($arrData['ref_type']) && $arrData['ref_type'] > 0) ? $logData['whl_ref_type']	 = $arrData['ref_type'] : '');
			((isset($arrData['ref_id']) && $arrData['ref_id'] > 0) ? $logData['whl_ref_id']		 = $arrData['ref_id'] : '');
			($templateId > 0 ? $logData['whl_wht_id']		 = $templateId : '');
			((isset($arrData['ref_replying_id']) && $arrData['ref_replying_id'] != null) ? $logData['whl_replying_id']	 = $arrData['ref_replying_id'] : null);
			((isset($arrData['ref_payload']) && $arrData['ref_payload'] > 0) ? $logData['whl_payload']		 = $arrData['ref_payload'] : null);
			$whlId						 = WhatsappLog::processLog($logData);
		}

		return $whlId;
	}

	public static function processReceivedMsgLog($arrData)
	{
		($arrData['wamId'] != '' ? $logData['whl_wam_id']				 = $arrData['wamId'] : '');
		$logData['whl_phone_number']		 = $arrData['phone_number'];
		$logData['whl_message_type']		 = $arrData['message_type'];
		$logData['whl_message']				 = iconv('ISO-8859-1', 'ASCII//TRANSLIT//IGNORE', $arrData['message']);
		$logData['whl_created_by_name']		 = iconv('ISO-8859-1', 'ASCII//TRANSLIT//IGNORE', $arrData['customer_name']);
		$logData['whl_created_by_type']		 = 1;
		$logData['whl_created_date']		 = $arrData['received_at'];
		$logData['whl_sent_date']			 = $arrData['sent_date'];
		$logData['whl_delivered_date']		 = $arrData['delivered_date'];
		$logData['whl_status']				 = 4;
		$logData['whl_gozo_phone_id']		 = $arrData['gozo_phone_id'];
		$logData['whl_gozo_phone_number']	 = $arrData['gozo_phone_number'];
		if ($arrData['message_type'] == WhatsappLog::MSG_TYPE_BUTTON)
		{
			$logData['whl_replying_id']	 = $arrData['replyingToMessageId'];
			$logData['whl_wht_id']		 = $arrData['templateId'];
			$logData['whl_payload']		 = $arrData['payload'];
			$logData['whl_ref_type']	 = $arrData['refType'];
			$logData['whl_ref_id']		 = $arrData['refId'];
		}
		return WhatsappLog::processLog($logData);
	}

	public static function processNotification($notification)
	{
		try
		{
//			self::writeFile('processNotification', 'a+');
//			self::writeFile($notification->imageId(), 'a+');
//			self::writeFile($notification->mimeType(), 'a+');
//			self::writeFile($notification->message(), 'a+');
//			self::writeFile('processNotification1', 'a+');

			if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\StatusNotification)
			{
				self::processNotificationStatus($notification);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Text)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_TEXT);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Media)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_MEDIA);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Contact)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_CONTACT);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Location)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_LOCATION);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Reaction)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_REACTION);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Button)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_BUTTON);
				\Sentry\captureMessage(json_encode(['WhatsappNotificationHookTrackButton' => $arrData]), null);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Interactive)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_INTERACTIVE);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Order)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_ORDER);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\System)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_SYSTEM);
			}
			else if ($notification instanceof Netflie\WhatsAppCloudApi\WebHook\Notification\Unknown)
			{
				$arrData = self::populateCommonData($notification, WhatsappLog::MSG_TYPE_UNKNOWN);
			}

			if ($arrData && is_array($arrData) && sizeof($arrData) > 0)
			{
				$whatsappId = self::processReceivedMsgLog($arrData);
				if (($arrData['phone_number'] == "918013269763") || ($arrData['phone_number'] == "918100786078"))
				{
					WhatsappLog::processWelcomeMessage($arrData);
				}
				WhatsappLog::processButtonAction($whatsappId, $arrData);
			}
		}
		catch (Exception $ex)
		{
			\Sentry\captureMessage(json_encode(['WhatsappNotificationHook' => $ex->getMessage(), "value" => json_encode($notification)]), null);
			Logger::exception($ex);
		}
	}

	public static function processNotificationStatus($notification)
	{
//		self::writeFile('StatusNotification', 'a+');

		$logData = [];

		$wamId		 = $notification->id();
		$status		 = $notification->status();
		$receivedAt	 = $notification->receivedAt()->format("Y-m-d H:i:s");

//		self::writeFile('wamId: ' . $wamId, 'a+');
//		self::writeFile('status: ' . $status, 'a+');
//		self::writeFile('receivedAt: ' . $receivedAt, 'a+');

		if ($status == 'sent')
		{
			$logData['whl_sent_date'] = $receivedAt;
		}
		else if ($status == 'delivered')
		{
			$logData['whl_delivered_date'] = $receivedAt;
		}
		else if ($status == 'read')
		{
			$logData['whl_read_date'] = $receivedAt;
		}
		else if ($status == 'failed')
		{
			$logData['whl_status'] = 3;
		}

		if (count($logData) > 0)
		{
			WhatsappLog::processLog($logData, $wamId);
		}

		if ($wamId == '' || count($logData) <= 0)
		{
			throw new Exception("Error in handling whatsapp notification");
		}
	}

	public static function populateCommonData($notification, $msgType)
	{
		$arrData						 = [];
		$arrData['wamId']				 = $notification->id();
		$arrData['message_type']		 = $msgType;
		$arrData['phone_number']		 = $notification->customer()->phoneNumber();
		$arrData['customer_name']		 = $notification->customer()->name();
		$arrData['received_at']			 = $notification->receivedAt()->format("Y-m-d H:i:s");
		$arrData['sent_date']			 = $notification->receivedAt()->format("Y-m-d H:i:s");
		$arrData['delivered_date']		 = $notification->receivedAt()->format("Y-m-d H:i:s");
		$arrData['gozo_phone_id']		 = $notification->businessPhoneNumberId();
		$arrData['gozo_phone_number']	 = $notification->businessPhoneNumber();

		if ($msgType == WhatsappLog::MSG_TYPE_BUTTON)
		{
			$arrData['message']				 = $notification->text();
			$arrData['payload']				 = $notification->payload();
			$arrData['replyingToMessageId']	 = $notification->context()->replyingToMessageId();
			$arrData['templateId']			 = $notification->payload();
			$row							 = WhatsappLog::detailsByWamId($arrData['replyingToMessageId']);
			$arrData['refType']				 = $row['whl_ref_type'];
			$arrData['refId']				 = $row['whl_ref_id'];
		}
		else if ($notification->message())
		{
			$arrData['message'] = $notification->message();
		}

		if ($msgType == WhatsappLog::MSG_TYPE_MEDIA)
		{
			$arrData['whl_media_id']	 = $notification->imageId();
			$arrData['whl_media_type']	 = $notification->mimeType();
		}

//		Whatsapp::writeFile('populateCommonData : ' . json_encode($arrData), 'a+');
		return $arrData;
	}

	public static function buildComponentBody($arrData)
	{
		$arrBody = [];
		foreach ($arrData as $key => $value)
		{
			$arrBody[] = ['type' => 'text', 'text' => $value];
		}

		return $arrBody;
	}

	public static function buildComponentButton($arrData, $type = 'button', $subType = 'url', $text = "text")
	{
		$arrButton	 = [];
		$textArr	 = explode(",", $text);
		$subTypeArr	 = explode(",", $subType);
		foreach ($arrData as $key => $value)
		{
			$arrButton[] = [
				'type'		 => $type,
				'sub_type'	 => $subTypeArr[$key],
				'index'		 => $key,
				'parameters' => [
					[
						'type'			 => $textArr[$key],
						$textArr[$key]	 => $value,
					]
				]
			];
		}
		return $arrButton;
	}

	public static function writeFile($text, $mode = 'a+', $fileName = 'data.txt')
	{
		$text	 .= PHP_EOL . PHP_EOL;
		$fp		 = fopen($fileName, $mode);
		fwrite($fp, $text);
		fclose($fp);
	}

	public static function writeSentMsgFile($text, $mode = 'a+', $fileName = 'sentMsg.txt')
	{
		$text	 .= PHP_EOL . PHP_EOL;
		$fp		 = fopen($fileName, $mode);
		fwrite($fp, $text);
		fclose($fp);
	}

	/**
	 * This function is used the send subscribe template  notification
	 * @param $arrData array
	 * @return array
	 */
	public static function processSubscribe($arrData)
	{
		$details	 = WhatsappLog::detailsByWamId($arrData['replyingToMessageId']);
		$arrDBData	 = ['entity_type' => $details['whl_entity_type'], 'entity_id' => $details['whl_entity_id'], 'ref_type' => $details['whl_ref_type'], 'ref_id' => $details['whl_ref_id']];
		$arrBody	 = Whatsapp::buildComponentBody([$arrData['customer_name'], "new trip request"]);
		$arrButton	 = Whatsapp::buildComponentButton([$arrData['templateId']], 'button', 'quick_reply', "payload");
		return WhatsappLog::send($arrData['phone_number'], 'thank_you_subscribe', $arrDBData, $arrBody, $arrButton, 'en_US');
	}

	/**
	 * This function is used the send response for subscribe
	 * @param $replyingId string
	 * @param $phoneNumber string
	 * @param $message string
	 * @return array
	 */
	public static function processTextMsg($replyingId, $phoneNumber, $message)
	{
		$details	 = WhatsappLog::detailsByWamId($replyingId);
		$arrDBData	 = ['entity_type' => $details['whl_entity_type'], 'entity_id' => $details['whl_entity_id'], 'ref_type' => $details['whl_ref_type'], 'ref_id' => $details['whl_ref_id'], 'ref_replying_id' => $details['whl_replying_id'], 'ref_payload' => $details['whl_payload']];
		$arrBody	 = Whatsapp::buildComponentBody([$message]);
		$arrButton	 = Whatsapp::buildComponentButton([]);
		return WhatsappLog::send($phoneNumber, '', $arrDBData, $arrBody, $arrButton, 'en_US');
	}

}
