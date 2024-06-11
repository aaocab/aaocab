<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;

class notificationWrapper
{

	public function sendVndMessagingForBooking($id)
	{
		$currentRow = ChatLog::getCurrentMessageById($id);

		$vendorId	 = $currentRow['bcb_vendor_id'];
		$bkgId		 = $currentRow['bkg_id'];
		$bookingId	 = $currentRow['bkg_booking_id'];
		$message	 = $currentRow['chl_msg'];

		$title = "New Message for " . $bookingId;

		$payLoadData = ['EventCode' => Booking::CODE_CHAT_MESSAGE];

		$chatArgs = array(
			'bkg_id'		 => $bkgId,
			'event_code'	 => Booking::CODE_CHAT_MESSAGE,
			'cht_id'		 => $currentRow['cht_id'],
			'chl_id'		 => $currentRow['chl_id'],
			'chl_msg'		 => $currentRow['chl_msg'],
			'ref_name'		 => $currentRow['ref_name'],
			'ref_type'		 => $currentRow['ref_type'],
			'chl_created'	 => $currentRow['chl_created'],
			'display_name'	 => $currentRow['display_name']
		);

		$success = AppTokens::model()->notifyVendorChat($vendorId, $payLoadData, $message, $title, $chatArgs);
	}

	public function notifyVendorChat($vendor_id, $data, $message, $title, $bookingId)
	{
		/* @var $appTokenModel AppTokens */
		//$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_user_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 2, 'id' => $vendor_id]);
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 2, 'id' => $vendor_id]);
//		echo $data;
//		exit();
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'message' => $message, 'bookingId' => $bookingId, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	public function notifyDriverChat($driver_id, $data, $message, $title, $bookingId)
	{
		/* @var $appTokenModel AppTokens */
		$appTokenModel = AppTokens::model()->findAll('apt_status=:status AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type', ['status' => 1, 'type' => 5, 'id' => $driver_id]);
		return AppTokens::model()->sendNotifications($appTokenModel, $data, ['notifications' => ['title' => $title, 'message' => $message, 'bookingId' => $bookingId, 'icon' => '@drawable/logo', 'sound' => 'default']]);
	}

	/**
	 * @param BookingTrackLog $btlModel
	 * @return boolean
	 */
	public static function customerNotifyDriverArrived($btlModel)
	{
		$success = false;
		try
		{
			/* @var $model Booking */
			$model = $btlModel->btlBkg;
			if (!$model)
			{
				return false;
			}
			$notify = new Stub\common\Notification();
			$notify->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_DRIVER_ARRIVED);

			$userId		 = $model->bkgUserInfo->bkg_user_id;
			$userType	 = UserInfo::TYPE_CONSUMER;
			$payLoadData = json_decode(json_encode($notify->payload), true);
			$message	 = "Cab Arrived at " . DateTimeFormat::SQLDateTimeToLocaleDateTime($btlModel->btl_sync_time);
			$title		 = "Driver arrived";

			$success = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		}
		catch (Exception $exc)
		{
			Logger::error($exc);
		}

		return $success;
	}

	/**
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function customerNotifyTripStart($bkgId)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notifyObj = new Stub\common\Notification();
		$notifyObj->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_TRIP_START);

		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notifyObj->payload), true);
		$message	 = "Trip started successfully";
		$title		 = "Trip Started";
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param Booking $bkgModel
	 * @return boolean
	 */
	public static function customerNotifyDriverDetails($bkgId = null, $bkgModel = null)
	{
		if ($bkgModel)
		{
			/* @var $model Booking */
			$model = clone $bkgModel;
			goto skipBkgModel;
		}
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		skipBkgModel:
		$notify = new Stub\common\Notification();
		$notify->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_DRIVER_UPDATE);

		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = "(" . $model->bkg_booking_id . ") " . $model->bkgFromCity->cty_name . " to " . $model->bkgToCity->cty_name . " on " . $model->bkg_pickup_date . ", Driver/ Cab details updated.";
		$title		 = "Driver details updated.";

		$success = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function customerNotifyBookingCancelled($bkgId, $crid = null)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notifyObj = new Stub\common\Notification();
		$notifyObj->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_TRIP_CANCELLED);

		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notifyObj->payload), true);
		//$message	 = "(" . $bookingModel->bkg_booking_id . ") " . $bookingModel->bkgFromCity->cty_name . " to " . $bookingModel->bkgToCity->cty_name . " on " . $bookingModel->bkg_pickup_date, $bookingModel->bkg_booking_id . " booking cancelled";
		$message	 = CancelReasons::model()->getSMSTemplate(CancelReasons::USER_TYPE_CUSTOMER, $crid, $model->bkg_id);
		$title		 = "Booking has been cancelled.";
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function customerNotifyTripCompleted($bkgId)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notify		 = new Stub\common\Notification();
		$notify->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_TRIP_END);
		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = "(" . $model->bkg_booking_id . ") " . $model->bkgFromCity->cty_name . " to " . $model->bkgToCity->cty_name . " on " . $model->bkg_pickup_date . ", " . $model->bkg_booking_id . " has been marked as completed";
		$title		 = "Trip completed";
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function customerNotifyBookingAcceptGNow($bkgId)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notify		 = new Stub\common\Notification();
		$notify->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_BOOKING_ACCEPT_GNOW);
		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = "A new offer found for your booking.";
		$title		 = "Trip Requested";
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function customerNotifyRating($bkgId)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notify = new Stub\common\Notification();
		$notify->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_NOTIFIED_RATING_REQUEST);

		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = "Thanks for traveling with Gozocabs ( Booking ID : " . $model->bkg_booking_id . "). We would love to hear your feedback on how we did.";
		$title		 = "Rating requested";
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param string $notificationMessage
	 * @param string $notificationTitle
	 * @return boolean
	 */
	public static function customerNotifyBroadcast($bkgId, $notificationMessage, $notificationTitle)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notify = new Stub\common\Notification();
		$notify->setNotifyCustomer($model, NotificationLog::CODE_CONSUMER_BROADCAST);

		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $notificationMessage;
		$title		 = $notificationTitle;
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param array $changes
	 * @return boolean
	 */
	public static function customerNotifyBookingModified($bkgId, $changes)
	{
		$model = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$notify = new Stub\common\Notification();
		$notify->setNotifyCustomer($model, NotificationLog::CODE_CUSTOMER_BOOKING_MODIFIED);

		$userId		 = $model->bkgUserInfo->bkg_user_id;
		$userType	 = UserInfo::TYPE_CONSUMER;
		$payLoadData = json_decode(json_encode($notify->payload), true);
		$message	 = $changes . "," . $model->bkg_booking_id . " details has been modified.";
		$title		 = "Booking Modified";
		$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		return $success;
	}

	/**
	 * @param integer $bkgId
	 * @param boolean $isEmail
	 * @param boolean $isSms
	 * @param array $userInfo
	 * @return boolean
	 */
	public static function driverDetailsToCustomer($bkgId, $isEmail = false, $isSms = false, $userInfo = UserInfo::TYPE_SYSTEM)
	{
		$result = false;
		try
		{
			/* @var $model Booking */
			$model = Booking::model()->findByPk($bkgId);
			if (!$model)
			{
				return false;
			}
			$contactNum = $model->bkgUserInfo->bkg_contact_no;
			// sms sent
			if ($contactNum != '' && $model->bkgPref->bkg_send_sms == 1 && $isSms == 1)
			{
				smsWrapper::msgToUserBookingConfirmed($model, $type = 2, '', $userInfo);
			}
			// email sent
			if ($isEmail)
			{
				emailWrapper::sendCabDriverDetailsToCustomer($bkgId);
			}
			$result = self::customerNotifyDriverDetails($bkgId, null);
		}
		catch (Exception $ex)
		{
			Logger::error($ex);
		}

		// notification sent
		return $result;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param boolean $isSms
	 * @param boolean $isEmail
	 * @param string $CustNumbercode
	 * @param string $CustNumbernew
	 * @param integer $reasonId
	 */
	public static function customerBookingCancelled($bkgId, $isSms = false, $isEmail = false, $CustNumbercode, $CustNumbernew, $reasonId)
	{
		$result = false;
		try
		{
			if ($isSms && $CustNumbernew != '')
			{

				$response = WhatsappLog::bookingCancelledByCustomer($bkgId, $reasonId, $CustNumbernew);
				if (!$response || $response['status'] == 3)
				{
					// sms sent
					smsWrapper::informCustomerCancelled($CustNumbercode, $CustNumbernew, $bkgId, $reasonId);
				}
			}

			if ($isEmail)
			{
				// email sent
				emailWrapper::bookingCancellationMail($bkgId);
			}

			$result = self::customerNotifyBookingCancelled($bkgId, $reasonId);
		}
		catch (Exception $ex)
		{
			Logger::error($ex);
		}
		return $result;
	}

	/**
	 * 
	 * @param integer $bkgId
	 */
	public static function customerReview($bkgId)
	{
		$result = false;
		try
		{
			// email sent
			$emailCom	 = new emailWrapper();
			$emailCom->markComplete($bkgId, BookingLog::System);
			// notification sent
			$result		 = self::customerNotifyRating($bkgId);
		}
		catch (Exception $ex)
		{
			Logger::error($ex);
		}
		return $result;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param array $changesForConsumer
	 */
	public static function customerBookingModified($bkgId, $changesForConsumer, $smsSent = false)
	{
		$result = false;
		try
		{
			$model = Booking::model()->findByPk($bkgId);
			if (!$model)
			{
				return false;
			}
			$ext		 = $model->bkgUserInfo->bkg_country_code;
			$phone		 = $model->bkgUserInfo->bkg_contact_no;
			$bookingID	 = $model->bkg_booking_id;
			if ($smsSent)
			{
				// sent sms
				smsWrapper::informChangesToCustomer($ext, $phone, $bookingID, $changesForConsumer, UserInfo::TYPE_SYSTEM);
			}
			$result = self::customerNotifyBookingModified($bkgId, $changesForConsumer);
		}
		catch (Exception $ex)
		{
			Logger::error($ex);
		}

		// sent notification
		return $result;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @param boolean $smsSent
	 */
	public static function customerBookingAcceptGNow($bkgId, $smsSent = false)
	{
		$success = self::customerNotifyBookingAcceptGNow($bkgId);

		if ($smsSent)
		{
			BookingTrail::notifyConsumerForMissedNewGnowOffers($bkgId);
		}
	}

	/**
	 * Send Mail to Beneficiary regarding  cash he/she earned in 
	 * @param integer $beneficiaryId
	 * @return type returnSet
	 */
	public static function userReferral($beneficiaryId, $payoutAmt)
	{
		$returnSet = new ReturnSet();
		try
		{
			$email		 = Users::model()->findByPk($beneficiaryId)->usr_email;
			$name		 = Contact::model()->getByUserId($beneficiaryId)->ctt_name;
			$emailCom	 = new emailWrapper();
			$amt		 = (int) $payoutAmt;
			$qrCode		 = QrCode::getCode($beneficiaryId);
			if ($qrCode != null && $email != null)
			{
				$emailCom->userReferral($email, $name, $amt, $qrCode);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * Send Mail to Beneficiary regarding  cash he/she earned in 
	 * @param integer $benefactorId
	 * @param integer $beneficiaryId
	 * @return type returnSet
	 */
	public static function userReferred($benefactorId, $beneficiaryId)
	{
		$returnSet = new ReturnSet();
		try
		{
			$email				 = Users::model()->findByPk($benefactorId)->usr_email;
			$cttBenefactorModel	 = Contact::model()->getByUserId($benefactorId);
			$cttBeneficiaryModel = Contact::model()->getByUserId($beneficiaryId);
			$emailCom			 = new emailWrapper();
			$name				 = $cttBenefactorModel->ctt_name;
			$qrCode				 = QrCode::getCode($benefactorId);
			$referalName		 = $cttBeneficiaryModel->ctt_name;
			if ($qrCode != null && $email != null)
			{
				$emailCom->userReferred($email, $name, $referalName, $qrCode);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used the send normal WhatApp plain text message when any CSR marked booking as Delegated TO Operation Manager OR  DTM
	 * @param $details array
	 * @param $phoneNumber string
	 * @param $message string
	 * @param $lang string
	 * @return array
	 */
	public static function notifyDTMBooking($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		$bookingDetails	 = Booking::getRegionBookingType($bkgId);
		$adminList		 = Admins::getAdminByRegionWiseBookingType('4,27,28,29,30,36,48', $bookingDetails['region'], $bookingDetails['bookingType']);
		foreach ($adminList as $value)
		{
			try
			{
				$datePickupDate	 = new DateTime($bookingDetails['bkg_pickup_date']);
				$pickupTime		 = $datePickupDate->format('j/F/Y g:i A');
				$cabType		 = $bookingDetails['vct_label'];
				$fromCityName	 = $bookingDetails['fromCityName'];
				$toCityName		 = $bookingDetails['toCityName'];
				$tripType		 = $bookingDetails['tripType'];
				$criticalScore	 = $bookingDetails['criticalScore'];
				Filter::parsePhoneNumber($value['adm_phone'], $code, $number);
				if ($value['adm_phone'] == null || trim($value['adm_phone']) == "")
				{
					goto skipAll;
				}
				$contentParams		 = array(
					'eventId'		 => "25",
					'cabType'		 => $cabType,
					'fromCityName'	 => $fromCityName,
					'toCityName'	 => $toCityName,
					'pickupTime'	 => $pickupTime,
					'criticalScore'	 => $criticalScore,
					'tripType'		 => $tripType,
					'bkgId'			 => $bkgId
				);
				$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_ADMIN, $value['adm_id'], WhatsappLog::REF_TYPE_TRIP, $bookingDetails['bkg_bcb_id'], $bookingDetails['bkg_booking_id'], $code, $number, null, 1, null, null, $bkgId);
				$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::DTM_BOOKING, "DTM Booking", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
				$responseArr		 = MessageEventMaster::processPlatformSequences(25, $contentParams, $receiverParams, $eventScheduleParams);
				foreach ($responseArr as $response)
				{
					if ($response['success'] && $response['type'] == 1)
					{
						$templateId	 = WhatsappLog::findByTemplateNameLang('delegate_to_operation_manager_booking', 'en_US', 'wht_id');
						$row		 = ["initiateBy" => WhatsappInitiateTrack::INITIATE_BY_GOZO, "initiateType" => WhatsappInitiateTrack::INITIATE_TYPE_USER, "templateId" => $templateId, "phoneNumber" => ($code . $number)];
						WhatsappInitiateTrack::add($row);
						WhatsappInitiateTrack::updateStatus($row['initiateBy'], $row['initiateType'], $row['phoneNumber']);
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
			skipAll:
		}
	}

	public static function notifyDpndBoostedVnd($vndId)
	{
		$message	 = "Dear Partner,
		We have temporarily boosted your dependency score. This means that you will have more chances of getting bookings and you can also accept them directly.
		We would like to remind you that refusing bookings once you are assigned them will reduce your dependency score again. Please do not refuse bookings unless you have a legitimate reason to do so.
		Thank you for your cooperation.";
		$contactId	 = ContactProfile::getByVendorId($vndId);
		$phoneNo	 = ContactPhone::getContactPhoneById($contactId);
		if ($phoneNo == "")
		{
			goto notify;
		}
		$templateName	 = 'partner_boosted_dependency_score';
		$lang			 = 'en_GB';
		$arrWhatsAppData = [];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId];
		$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton		 = Whatsapp::buildComponentButton([]);
		WhatsappLog::send($phoneNo, $templateName, $arrDBData, $arrBody, $arrButton, $lang);
		notify:
		$payLoadData	 = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
		AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, "Dependency Boost");
	}

	public static function notifyDpndScoreReduce($vndId, $oldScore, $newScore)
	{
		$msg		 = "Your dependency score has decreased from " . $oldScore . " to " . $newScore . ". We would like to remind you that declining bookings once they are assigned to you will further reduce your dependency score. "
				. "Please only decline bookings if you have a legitimate reason to do so. A higher dependency score increases your chances of receiving bookings at better rates.";
		$payLoadData = ['EventCode' => Booking::CODE_DEPENDECY_SCORE_MODIFIED];
		AppTokens::model()->notifyVendor($vndId, $payLoadData, $msg, "Dependency score reduce");
	}

	/**
	 * This function is used to send notifications for customer has made payment for a particular booking
	 * @param string $bkgId
	 * @return boolean
	 */
	public static function notifyBookingPaymentReceivedCustomer($bkgId, $isSchedule = 0, $schedulePlatform = null)
	{
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
		}
		if (!$bkgModel)
		{
			return false;
		}
		if ($bkgModel->bkgInvoice->bkg_advance_amount == 0)
		{
			return false;
		}

		$entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		$contactId	 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
		$cttModel	 = Contact::model()->findByPk($contactId);
		$userName	 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		if (!empty($cttModel->ctt_business_name))
		{
			$userName = $cttModel->ctt_business_name;
		}
		$lastPaymentReceived = AccountTransactions::getLastPaymentReceived($bkgId);
		$paymentAmount		 = ($lastPaymentReceived > 0) ? 'Rs. ' . $lastPaymentReceived : 'Rs. ' . $bkgModel->bkgInvoice->bkg_advance_amount;
		$bookingId			 = $bkgModel->bkg_booking_id;
		$bookingAmt			 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_total_amount;
		$totalAdvanceAmount	 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_advance_amount;
		$dueAmount			 = 'Rs. ' . $bkgModel->bkgInvoice->bkg_due_amount;
		$buttonUrl			 = 'bkpn/' . $bkgId . '/' . Yii::app()->shortHash->hash($bkgId);
		$paymentLink		 = Filter::shortUrl('http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($bkgModel->bkg_id));
		$response			 = Contact::referenceUserData($bkgModel->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$number	 = $response->getData()->phone['number'];
			$ext	 = $response->getData()->phone['ext'];
		}
		else
		{
			$number	 = $bkgModel->bkgUserInfo->bkg_contact_no;
			$ext	 = $bkgModel->bkgUserInfo->bkg_country_code;
		}

		if ($bkgModel->bkgInvoice->bkg_wallet_used > 0)
		{
			$msg = $bkgModel->bkg_booking_id . ' | Deducted Rs.' . round($bkgModel->bkgInvoice->bkg_wallet_used) . ' from your Gozo wallet | See cab driver updates at ' . $paymentLink;
		}
		else
		{
			$msg = $bkgModel->bkg_booking_id . ' | Received Rs.' . round($bkgModel->bkgInvoice->bkg_advance_amount) . ' | See cab driver updates at ' . $paymentLink . ' - Gozocabs';
		}

		$contentParams		 = array(
			'eventId'			 => "7",
			'userName'			 => $bkgModel->bkgUserInfo->bkg_user_fname,
			'paymentAmount'		 => $paymentAmount,
			'bookingId'			 => Filter::formatBookingId($bookingId),
			'bookingAmt'		 => $bookingAmt,
			'totalAdvanceAmount' => $totalAdvanceAmount,
			'dueAmount'			 => $dueAmount,
			'msg'				 => $msg
		);
		$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $bkgModel->bkgUserInfo->bkg_user_id, WhatsappLog::REF_TYPE_BOOKING, $bkgId, $bkgId, $ext, $number, null, 1, null, SmsLog::SMS_PAYMENT_SUCCESS, $buttonUrl, "emailWrapper::confirmBooking($bkgId);");
		$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::BOOKING_PAYMENT_RECEIVED_CUSTOMER, "Booking Payment Received Customer", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
		MessageEventMaster::processPlatformSequences(7, $contentParams, $receiverParams, $eventScheduleParams);
	}

	/**
	 * This function is used process Email  
	 * @param Object $obj
	 * @param Array $contentParams
	 * @param Object $receiverParams
	 * @return boolean
	 */
	public static function processEmail($obj, $contentParams = [], $receiverParams = null)
	{
		$success = false;
		if ($receiverParams->emailCallBack != null && $receiverParams->emailCallBack != "")
		{
			$response = eval('return ' . $receiverParams->emailCallBack);
			if ($response)
			{
				$success = true;
			}
		}
		else
		{
			$success = TemplateMaster::replaceVariablesInTemplate($obj->content, $contentParams);
		}
		return $success;
	}

	/**
	 * This function is used process App Notification 
	 * @param Object $obj
	 * @param string $message
	 * @param Array $contentParams
	 * @param Object $receiverParams
	 * @param Object $eventScheduleParams
	 * @return boolean
	 */
	public static function process($obj, $message, $receiverParams = null, $eventScheduleParams = null)
	{
		$returnSet	 = new ReturnSet();
		$returnSet->setStatus(false);
		$returnSet->setData(['type' => 0]);
		$time		 = $eventScheduleParams->event_sequence == TemplateMaster::SEQ_APP_CODE ? $eventScheduleParams->schedule_time : 0;
		if ($time > 0 && $eventScheduleParams->event_schedule == 1)
		{
			ScheduleEvent::add($eventScheduleParams->ref_id, $eventScheduleParams->ref_type, $eventScheduleParams->event_id, $eventScheduleParams->remarks, $eventScheduleParams->addtional_data, $time, TemplateMaster::SEQ_APP_CODE);
			$returnSet->setStatus(true);
			$returnSet->setData(['type' => TemplateMaster::SEQ_APP_CODE]);
		}
		else
		{
			$appNotificationParams	 = AppTokens::setAppNotificationParams($receiverParams->entity_type, $receiverParams->entity_id, $receiverParams->app_event_code, $obj->title, $receiverParams->ref_id, $receiverParams->bkg_id);
			$payLoadData			 = ['vendorId' => $appNotificationParams['entityId'], 'tripId' => $appNotificationParams['tripId'], 'EventCode' => $appNotificationParams['eventCode'], 'bookingId' => $appNotificationParams['bookingId']];
			$appTokenModel			 = AppTokens::model()->findAll("apt_status=:status AND apt_last_login>=DATE_SUB(NOW(), INTERVAL 30 DAY) AND apt_device_token IS NOT NULL AND apt_entity_id=:id AND apt_user_type=:type", ['status' => 1, 'type' => $appNotificationParams['entityType'], 'id' => $appNotificationParams['entityId']]);
			$response				 = AppTokens::model()->sendNotifications($appTokenModel, $payLoadData, ['notifications' => ['notificationId' => $appNotificationParams['notificationId'], 'title' => $appNotificationParams['title'], 'tripId' => $appNotificationParams['tripId'], 'bookingId' => $appNotificationParams['bookingId'], 'EventCode' => $appNotificationParams['eventCode'], 'message' => $message, 'icon' => '@drawable/logo', 'sound' => 'default']]);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData(['type' => TemplateMaster::SEQ_APP_CODE]);
			}
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param BookingVendorRequest $bvrModel
	 * @return boolean
	 */
	public static function customerNotifyBookingForGNow($bvrModel)
	{
		try
		{
			if (!$bvrModel)
			{
				return false;
			}
			$notify		 = \Beans\common\Notification::setNotifyCustomer($bvrModel, NotificationLog::CODE_CUSTOMER_NOTIFIED_BOOKING_OFFER_GNOW);
			$model		 = \Booking::model()->findByPk($bvrModel->bvr_booking_id);
			$userId		 = $model->bkgUserInfo->bkg_user_id;
			$userType	 = UserInfo::TYPE_CONSUMER;
			$payLoadData = json_decode(json_encode($notify), true);
			$message	 = "A new offer found for your booking.";
			$title		 = "Trip Requested";
			$success	 = AppTokens::model()->notifyUser($userId, $userType, $payLoadData, $message, $title);
		}
		catch (Exception $ex)
		{
			Logger::error($ex);
		}

		return $success;
	}

}
