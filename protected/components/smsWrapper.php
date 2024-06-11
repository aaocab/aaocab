<?php

use components\Event\EventSchedule;

class smsWrapper
{

	const DLT_OTP_TEMPID						 = '1707164507767476278';
	const DLT_APP_OTP_TEMPID					 = '1707167593837081659';
	const DLT_OTP_FORGOTPASSWORD				 = '1707169382484217014';
	const DLT_BOOK_NOT_PAID_TEMPID			 = '1707163877514305654';
	const DLT_BOOK_NOT_COMPLETE_TEMPID		 = '1707167575520371352';
	const DLT_BOOK_PAY_LINK_TEMPID			 = '1707163877604897959';
	const DLT_BOOK_STARTS_IN_HRS_TEMPID		 = '1707165546762671269';
	const DLT_BOOK_DETAILS_TEMPID				 = '1707163897527607308';
	const DLT_VERIFY_PHONE_LINK_TEMPID		 = '1707163877623643267';
	const DLT_VERIFY_PHONE_OTP_TEMPID			 = '1407162124354303921';
	const DLT_TRIP_START_OTP_TEMPID			 = '1707163879647771453';
	const DLT_PARTNER_ATTACH_SOCIAL_OTP_TEMPID = '1707163879645925632';

	public static function sendVerification($ext, $number, $vCode, $bkgid, $logType = '')
	{

		/* var $model Booking */
		$pHashCode	 = Yii::app()->shortHash->hash($vCode);
		$model		 = Booking::model()->findByPk($bkgid);
		if ($model != '' || $model->bkg_agent_id > 0)
		{
			return false;
		}
		if ($number != '')
		{
			$bookModel	 = Booking::model()->getBkgIdByBookingId($bkgid);
			$sms		 = new Messages();
			$hash		 = Yii::app()->shortHash->hash($bookModel->bkg_id);

			// $verifylink = 'aaocab.com/bkver/' . $bookModel->bkg_id . '/' . $hash
			//if($bookModel->bkgUserInfo->bkg_verification_code != '' && $bookModel->bkgUserInfo->bkg_verification_code != NULL){
			//$verifylink	 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bookModel->bkg_id, 'hash' => $hash, 'p' => $pHashCode]);
//			}
//			else
//			{
//				$verifylink	 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bookModel->bkg_id, 'hash' => $hash]);
//			}
			//   $msg = 'Gozocabs Your Verification Code is:' . $vCode.' and Verification Link is '.$verifylink. '. Please enter it in the space provided in the website.';
			//  $msg = 'Your Verification Code is ' . $vCode.'. Please enter it in the space provided in the website or in the following link: '.$verifylink.'. Gozocabs.';
			//$msg		 = 'Your OTP is ' . $vCode . '. Please enter it on the website or at ' . $verifylink . '. -Gozocabs.';
			$verifylink	 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($bookModel->bkg_id);
			$msg		 = 'Bkg ' . $bookModel->bkg_booking_id . ' created. Pay at ' . $verifylink . ' within 90mins to reconfirm booking - Gozocabs';

			if ($bookModel->bkgTrail->bkg_platform == 3)
			{
				$msg = "Gozocabs Your Verification Code is: $vCode . Please enter it in the space provided in the website.";
			}

			$res		 = $sms->sendMessage($ext, $number, $msg, 0);
			$usertype	 = SmsLog::Consumers;
			$smstype	 = SmsLog::SMS_BOOKING_VERIFICATION_CODE;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $bookModel->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bkgid, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
			if ($slgId != '')
			{
				$desc = "Sms sent on Verification Code - Gozocabs.";
				if ($logType != '')
				{
					$userType = $logType;
					switch ($logType)
					{
						case '1':
							$userId	 = $bookModel->bkgUserInfo->bkg_user_id;
							break;
						case '4':
							$userId	 = UserInfo::getInstance()->getUserId();
							break;
						case '10':
							$userId	 = '0';
							break;
					}
				}
				else
				{
					$userType	 = UserInfo::getInstance()->getUserType();
					$userId		 = UserInfo::getInstance()->getUserId();
				}
				$eventId						 = BookingLog::SMS_SENT;
				$params							 = [];
				$params['blg_ref_id']			 = $slgId;
				$params['blg_booking_status']	 = $bookModel->bkg_status;
				BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public static function confirmUserAccounts($ext, $number, $bkgid, $changes, $logType = '')
	{
		/* var $model Booking */
		$bookModel		 = Booking::model()->getBkgIdByBookingId($bkgid);
		$bookPrefModel	 = BookingPref::model()->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $bookModel->bkg_id]);
		if (($bookModel != '' && ($bookPrefModel->bkg_blocked_msg == 1 || $bookPrefModel->bkg_send_sms == 0)) || $bookModel->bkg_agent_id > 0)
		{
			return false;
		}
		$bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $bookModel->bkg_id]);
		$customerName	 = $bkgUserModel->bkg_user_fname . " " . $bkgUserModel->bkg_user_lname;
		$sms			 = new Messages();
		//$msg = 'Dear Customer, Thank you for choosing Gozocabs. We have now created a Gozo Account on your behalf. Please click on the link to confirm your credentials. ' . $changes . ' - Gozocabs';
		$msg			 = 'Dear ' . $customerName . ', Thanks for creating your booking. To confirm that you created this booking click ' . $changes . ' else booking will be auto-cancelled. - Gozocabs';
		$res			 = $sms->sendMessage($ext, $number, $msg);
		$refType		 = SmsLog::REF_BOOKING_ID;
		$refId			 = $bookModel->bkg_id;
		$slgId			 = smsWrapper::createLog($ext, $number, $bkgid, $msg, $res, SmsLog::Consumers, "", SmsLog::SMS_USER_ACCOUNT_CONFIRM, $refType, $refId);
		if ($slgId != '' && $bookModel->bkg_id != '')
		{
			$desc = "SMS sent for User Account Confirmation.";
			if ($logType != '')
			{
				$userType = $logType;
				switch ($logType)
				{
					case '1':
						$userId	 = $bkgUserModel->bkg_user_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			$eventId						 = BookingLog::SMS_SENT;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function confirmOldUserAccounts($ext, $number, $msg, $bookingId)
	{
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Consumers;
		smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype);
	}

	public static function sendForgotPassCode($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to reset password of your Meter Down account.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::MeterDown;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function sendForgotPassCodeAgent($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to reset password of your Gozocabs Agent Account.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Agent;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function sendForgotPassCodeVendor($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to reset password of your Gozocabs Vendor Account.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function sendDriverConfermation($ext, $number, $msg)
	{
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Driver;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function sendVerificationAgent($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to verify your phone number.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Agent;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function sendForgotPassCodeDriver($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to reset password of your Gozocabs Driver Account.';
		$res		 = $sms->sendMessage($ext, $number, $msg, 0);
		$usertype	 = SmsLog::Driver;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function sendForgotPassCodeGozo($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to reset password of your GozoCabs account.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Agent;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	/**
	 *
	 * @param integer $bkgId
	 * @param integer $resend
	 * @return boolean
	 */
	public static function confirmBooking($bkgId, $userInfo = '', $resend = false)
	{
		Logger::create("Agent booking assignment test 6:\t", CLogger::LEVEL_PROFILE);
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if ($model->bkg_reconfirm_flag != 1 || !in_array($model->bkg_status, [2, 3, 5]))
		{
			return false;
		}
		$isConfirm = SmsLog::checkBookingConfirmed($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0 || $isConfirm == 1) && $resend == false)
		{
			return false;
		}
		$booking_id	 = $model->bkg_booking_id;
		$sms		 = new Messages();
		$minamount	 = $model->bkgInvoice->calculateMinPayment();
		$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
		$phone		 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);

		$paymentUrl	 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$paymentUrl	 = Filter::shortUrl($paymentUrl);
		//$msg		 = 'Cab request received: View ' . $paymentUrl . ' for booking details';
		$msg		 = 'Cab request received.Details at ' . $paymentUrl;
		if (($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '') && $model->bkgInvoice->bkg_due_amount > 0 && ($model->bkg_agent_id == 0 || $model->bkg_agent_id == ''))
		{
			//$msg .= ". Pay at least Rs.$minamount online before " . $model->getExpTimeCashBack() . " to save up to 50%";
			$msg .= " .Pay min Rs.$minamount online before " . $model->getExpTimeCashBack() . " to save upto 50%";
		}
		$bookingTrack = $model->bkgTrack;
		if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0 || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0')
		{
			$msg .= ". Additional " . $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes Journey Break Included";
		}
		$msg .= '.*Subject to T&Cs - Gozocabs';

		//user
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
		}
		$usertype = SmsLog::Consumers;

		//agent
		$logArr	 = [];
		$smstype = SmsLog::SMS_BOOKING_CREATED;
		$refId	 = $model->bkg_id;
		$refType = SmsLog::REF_BOOKING_ID;
		if ($model->bkg_agent_id > 0)
		{
// remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO);
			$logArr	 = $logArr1['sms'];
// remove comment when agent panel notificaion will be live
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$countryCode = $value['country_code'];
					$contactNo	 = $value['phone'];
					$res		 = $sms->sendMessage($countryCode, $contactNo, $msg, 0);
					$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
				}
			}
		}
		else
		{
			$res	 = $sms->sendMessage($countryCode, $contactNo, $msg, 0);
			$slgId	 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
		}
		//booking log
		if ($slgId != '')
		{
			if (!$userInfo)
			{
				$userInfo			 = new UserInfo();
				$userInfo->userId	 = 0;
				$userInfo->userType	 = 10;
			}
			$desc							 = "Sms sent on Booking confirmed.";
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	public static function gotBooking($model, $logType = '', $arrConfAgtBook = [])
	{
		Logger::create("Agent booking assignment test 6:\t", CLogger::LEVEL_PROFILE);
		/* @var $model Booking */
		//$model = Booking::model()->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$booking_id	 = $model->bkg_booking_id;
		$sms		 = new Messages();
		$minamount	 = $model->bkgInvoice->calculateMinPayment();
		$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
		$phone		 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verification_code);

//		if($model->bkgUserInfo->bkg_verification_code != '' && $model->bkgUserInfo->bkg_verification_code != NULL)
//		{
		//$paymentUrl	 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash, 'p' => $phone]);
//		}
//		else
//		{
//			$paymentUrl	 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
//		}
		$paymentUrl	 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$paymentUrl	 = Filter::shortUrl($paymentUrl);
		//$msg		 = 'Cab request received: View ' . $paymentUrl . ' for booking details';
		$msg		 = 'Cab request received. Details at ' . $paymentUrl;
		if (($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '') && $model->bkgInvoice->bkg_due_amount > 0 && ($model->bkg_agent_id == 0 || $model->bkg_agent_id == ''))
		{
			//$msg .= ". Pay at least Rs.$minamount online before " . $model->getExpTimeCashBack() . " to save up to 50%";
			$msg .= ". Pay at least $minamount online before " . $model->getExpTimeCashBack() . " to save up to 50%";
		}
		//$bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
		$bookingTrack = $model->bkgTrack;
//		if ($bookingTrack->bkg_trip_otp != '')
//		{
//			$msg .= ". Use OTP: $bookingTrack->bkg_trip_otp at the time of pickup, please dont share this OTP before boarding the cab ";
//		}
		if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0 || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0')
		{
			$msg .= ". Additional " . $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes Journey Break Included";
		}
		$msg .= '.*Subject to T&Cs - Gozocabs';

		//user
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
		}
		$usertype = SmsLog::Consumers;

		//agent
		$logArr	 = [];
		$smstype = SmsLog::SMS_BOOKING_CREATED;
		$refId	 = $model->bkg_id;
		$refType = SmsLog::REF_BOOKING_ID;
		if ($model->bkg_agent_id > 0)
		{
// remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO);
			$logArr	 = $logArr1['sms'];
// remove comment when agent panel notificaion will be live
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$countryCode = $value['country_code'];
					$contactNo	 = $value['phone'];
					$res		 = $sms->sendMessage($countryCode, $contactNo, $msg, 0);
					$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
				}
			}
		}
		else
		{
			$res	 = $sms->sendMessage($countryCode, $contactNo, $msg, 0);
			$slgId	 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
		}



		//booking log
		if ($slgId != '')
		{
			$desc = "Sms sent on Booking Created.";
			if ($logType != '')
			{
				$userType = $logType;
				switch ($logType)
				{
					case '1':
						$userId	 = $model->bkgUserInfo->bkg_user_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '5':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	public static function gotBookingCpp($model, $logType = '', $arrConfAgtBook = [])
	{
		/* @var $model Booking */
		//$model = Booking::model()->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$booking_id	 = $model->bkg_booking_id;
		$sms		 = new Messages();
		$minamount	 = $model->bkgInvoice->calculateMinPayment();
		$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
		//$paymentUrl	 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
		$paymentUrl	 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$paymentUrl	 = Filter::shortUrl($paymentUrl);
		//$msg		 = 'Cab request received: View ' . $paymentUrl . ' for booking details';
		$msg		 = 'Cab request received .Details at ' . $paymentUrl;
		if (($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '') && $model->bkgInvoice->bkg_due_amount > 0 && ($model->bkg_agent_id == 0 || $model->bkg_agent_id == ''))
		{
			//$msg .= ". Pay at least Rs.$minamount online before " . $model->getExpTimeCashBack() . " to save up to 50%";
			$msg .= " .Pay at least Rs.$minamount online before " . $model->getExpTimeCashBack() . " to save up to 50%";
		}
		//$bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
		$bookingTrack = $model->bkgTrack;
		if ($bookingTrack->bkg_trip_otp != '')
		{
			$msg .= ". Use OTP: $bookingTrack->bkg_trip_otp at the time of pickup, please dont share this OTP before boarding the cab ";
		}
		if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0 || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0')
		{
			$msg .= ". Additional " . $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes Journey Break Included";
		}
		$msg .= '.*Subject to T&Cs - Gozocabs';

		//user
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
		}
		$usertype = SmsLog::Consumers;

		//agent
		$logArr	 = [];
		$smstype = SmsLog::SMS_BOOKING_CREATED;
		$refId	 = $model->bkg_id;
		$refType = SmsLog::REF_BOOKING_ID;
		if ($model->bkg_agent_id > 0)
		{
			// remove comment when agent panel notificaion will be live
			$logArr1			 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO);
			$logArr1['sms'][0]	 = $contactNo;
			$logArr				 = $logArr1['sms'];

			// remove comment when agent panel notificaion will be live
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					//$countryCode = $value['country_code'];
					//$contactNo	 = $value['phone'];
					$res		 = $sms->sendMessage($countryCode, $contactNo, $msg, 0);
					$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
				}
			}
		}
		else
		{
			$res	 = $sms->sendMessage($countryCode, $contactNo, $msg, 0);
			$slgId	 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
		}



		//booking log
		if ($slgId != '')
		{
			$desc = "Sms sent on Booking Created.";
			if ($logType != '')
			{
				$userType = $logType;
				switch ($logType)
				{
					case '1':
						$userId	 = $model->bkgUserInfo->bkg_user_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '5':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	public static function assignVendor($ext, $number, $locationA, $locationB, $cabType, $date, $time, $amount, $booking_type, $bookingID, $vendorAmt, $advance, $due, $bkgId = false, $logType, $tollTax = "", $stateTax = "")
	{
		$sms		 = new Messages();
		/* @var $model $bookModel */
		$bookModel	 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $bookModel->getBookingCabModel();
		if ($bookModel != '' && ($bookModel->bkgPref->bkg_blocked_msg == 1))
		{
			return false;
		}

		$hashBkgId	 = Yii::app()->shortHash->hash($bookModel->bkg_id);
		$hashVndId	 = Yii::app()->shortHash->hash($cabmodel->bcb_vendor_id);
		$vendorLink	 = 'aaocab.com/bkvn/' . $hashBkgId . '/' . $hashVndId;

		//$msg = 'Booking ID: ' . $bookingID . '  ## See Details & Terms at ' . $vendorLink . ' ';
		$msg = 'Booking ID: ' . $bookingID . '. See Details & Terms at ' . $vendorLink . ' - Gozocabs';
		if ($bookModel->bkgAddInfo->bkg_spl_req_lunch_break_time != 0 || $bookModel->bkgAddInfo->bkg_spl_req_lunch_break_time != '0')
		{
			$msg .= ". Additional " . $bookModel->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes Journey Break Included";
		}
		//$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		$smstype	 = SmsLog::SMS_VENDOR_ASSIGNED;
		$refId		 = $bookModel->bkg_id;
		$refType	 = SmsLog::REF_BOOKING_ID;
		//$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, "", $smstype, $refType, $refId);
		if ($slgId != '')
		{
			/* $desc							 = "Sms sent on Vendor Assigned.";
			  $userInfo						 = UserInfo::getInstance();
			  $eventId						 = BookingLog::SMS_SENT;
			  $params							 = [];
			  $params['blg_ref_id']			 = $slgId;
			  $params['blg_booking_status']	 = $bookModel->bkg_status;
			  BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params); */
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function msgToUserBookingConfirmed($model, $type, $bkgId = false, $logType = '')
	{
		/* @var $model Booking */

		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$number	 = $response->getData()->phone['number'];
			$ext	 = $response->getData()->phone['ext'];
		}
		$amount = $model->bkgInvoice->bkg_total_amount;
//		if ($model->bkg_trip_type == 2)
//		{
//			$amount = $model->bkg_rate_per_km . 'Per Km';
//		}
		if ($model->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = round($model->bkgInvoice->bkg_advance_amount);
		}
		if ($type == 1)
		{
			$sendContact = true;
			$cabNumber	 = $model->bkgBcb->bcbCab->vhc_number;
			$driverName	 = $model->bkgBcb->bcb_driver_name;
			$driverPhone = '+91' . $model->bkgBcb->bcb_driver_phone;
			$cabType	 = $model->bkgBcb->bcbCab->vhcType->resetScope()->getVehicleModel();
		}
		else
		{

			$sendContact = false;
			$cabNumber	 = ($model->bkgBcb->bcb_cab_number > 0) ? $model->bkgBcb->bcb_cab_number : $model->bkgBcb->bcbCab->vhc_number;
			$driverName	 = $model->bkgBcb->bcb_driver_name;
			$driverPhone = '+91' . $model->bkgBcb->bcb_driver_phone;
			$cabType	 = $model->bkgBcb->bcbCab->vhcType->resetScope()->getVehicleModel();
		}
		$bookingId	 = $model->bkg_booking_id;
		$date		 = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
		$time		 = date('h:i A', strtotime($model->bkg_pickup_date));
		$address	 = $model->bkg_pickup_address;
		$dueAmount	 = $amount - $model->bkgInvoice->getTotalPayment();
		$due		 = round($dueAmount);

		$sms			 = new Messages();
		$bookingModel	 = Booking::model()->getBkgIdByBookingId($bookingId);
		if ($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		/* var $bookingModel Booking */
		$hash			 = Yii::app()->shortHash->hash($bookingModel->bkg_id);
		//$paymentLink	 = 'aaocab.com/bkpn/' . $bookingModel->bkg_id . '/' . $hash;
		$paymentLink	 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($bookingModel->bkg_id);
		$cabModel		 = $bookingModel->getBookingCabModel();
		$vehicleModel	 = $cabmodel->bcbCab->vhcType->vht_model;
		if ($cabmodel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = OperatorVehicle::getCabModelName($cabmodel->bcb_vendor_id, $cabmodel->bcb_cab_id);
		}
		$driver			 = $cabModel->bcbDriver->drv_name;
		$driver_phone	 = $cabModel->bcb_driver_phone;
		$car			 = $vehicleModel;
		$bkgId			 = $bookingModel->bkg_id;

		//$bookingPref = BookingPref::model()->getByBooking($bookingModel->bkg_id);
		if ($bookingModel->bkgTrack->bkg_trip_otp != '')
		{
			$msgOTP = " Use OTP: " . $bookingModel->bkgTrack->bkg_trip_otp . " at pickup don't share OTP before boarding the cab ";
		}

		$dltId = '';
		if ($sendContact == TRUE)
		{
//			$stateZone = $bookingModel->bkgFromCity->ctyState->stt_zone;
			//if (($bookingModel->bkg_agent_id == null || $bookingModel->bkg_agent_id == '' || $bookingModel->bkg_agent_id == Yii::app()->params['gozoChannelPartnerId'] || (in_array($stateZone, [4, 7]) && $bookingModel->bkg_agent_id == 450) ) && $bookingModel->bkg_country_code == 91)
//			if ( $bookingModel->bkg_agent_id == 450 && $bookingModel->bkg_country_code == 91)
			if ($bookingModel->bkgUserInfo->bkg_country_code == 91)
			{
				$driver_phone = Yii::app()->params['customerToDriver'];
				if ($bookingModel->bkg_agent_id == 450 || $bookingModel->bkg_agent_id == 18190)
				{
					$driver_phone = Yii::app()->params['customerToDriverforMMT'];
				}
			}
			$msg = 'BKGID: ' . $bookingId . ' is ALL SET. Your cab and driver are now allocated. View details at ' . $paymentLink . ' - Gozocabs';
		}
		else
		{
			$msg	 = 'BKGID: ' . $bookingId . ' is ALL SET. For details click ' . $paymentLink . $msgOTP . ' - Gozocabs';
			$dltId	 = self::DLT_BOOK_DETAILS_TEMPID;
		}


		$logArr		 = [];
		$usertype	 = SmsLog::Consumers;
		$smstype	 = SmsLog::SMS_USER_CAB_DETAILS_UPDATED;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookingModel->bkg_id;

		if ($bookingModel->bkg_agent_id != NULL && $bookingModel->bkg_agent_id > 0)
		{
			// remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::CAB_ASSIGNED);
			$logArr	 = $logArr1['sms'];
			// remove comment when agent panel notificaion will be live
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$countryCode = $value['country_code'];
					$contactNo	 = $value['phone'];
					$res		 = $sms->sendMessage($countryCode, $contactNo, $msg);
					$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $bookingId, $msg, $res, $usertype, "", $smstype, $refType, $refId);
				}
			}
		}
		else
		{
			$res	 = $sms->sendMessage($ext, $number, $msg, 1, 1, $dltId);
			$slgId	 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, "", $smstype, $refType, $refId);
		}

		//booking log
		if ($slgId != '')
		{
			$desc							 = "SMS sent on Cab assigned.";
			$userInfo						 = UserInfo::getInstance();
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookingModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookingModel->bkg_status;
			BookingLog::model()->createLog($bookingModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function informVendorCustomerCancelled($ext, $number, $bookingId, $cabType, $cityA, $cityB, $date, $time, $cancelStatus = null)
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingId);
		if ($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$sms		 = new Messages();
		//$msg		 = 'Customer Cancelled BKGID: ' . $bookingId . ' from ' . $cityA . ' to ' . $cityB . ' at ' . $time . ' on ' . $date . '. Gozocabs';
		//$msg		 = 'Booking ' . $bookingId . ' from ' . $cityA . ' to ' . $cityB . ' at ' . $time . ' on ' . $date . ' was unassigned.';
		$fromCity	 = Cities::getShortNameByCity($cityA);
		$toCity		 = Cities::getShortNameByCity($cityB);
		if ($cancelStatus == 1)
		{
			$response	 = WhatsappLog::tripCancelToVendorDriver($bookingModel->bkg_id, UserInfo::TYPE_VENDOR);
			$msg		 = 'Customer cancelled booking ' . $bookingId . ' from ' . $fromCity . ' to ' . $toCity . ' at ' . $time . ' on ' . $date . ' - Gozocabs';
		}
		else
		{
			Vendors::unassignedTripFromVendor($bookingModel->bkg_id);
//			$response	 = WhatsappLog::unassignedTripFromVendor($bkgId, $number);
//			$msg		 = 'Booking ' . $bookingId . ' from ' . $fromCity . ' to ' . $toCity . ' at ' . $time . ' on ' . $date . ' was unassigned - Gozocabs';
		}

		if ($response['status'] == 3)
		{
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Vendor;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $bookingModel->bkg_id;
			smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, '', '', $refType, $refId);
		}
	}

	public static function informVendorCustomerCancelledNew($ext, $number, $bookingId, $cnrid)
	{

		$bkgModel = Booking::model()->getByCode($bookingId);
		if (!$bkgModel)
		{
			$bkgModel = Booking::model()->findByPk($bookingId);
		}
		if ($bkgModel != '' && $bkgModel->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		$sms = new Messages();
		$msg = CancelReasons::model()->getSMSTemplate(CancelReasons::USER_TYPE_VENDOR, $cnrid, $bookingId);
		if ($msg != '')
		{
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Vendor;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $bkgModel->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, '', '', $refType, $refId);
			if ($slgId != '')
			{
				$desc		 = 'SMS to vendor for Booking Cancellation';
				$userType	 = BookingLog::System;
				$userId		 = 0;
				$oldModel	 = clone $bkgModel;
				$params		 = array('blg_ref_id'		 => $slgId,
					'blg_booking_status' => $bkgModel->bkg_status);
				BookingLog::model()->createLog($bkgModel->bkg_id, $desc, $userInfo, BookingLog::SMS_SENT, $oldModel, $params);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function informDriverCustomerCancelled($ext, $number, $bookingId, $cabType, $cityA, $cityB, $date, $time)
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingId);
		if ($bookingModel != '' && $bookingModel->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		$sms		 = new Messages();
		$msg		 = 'Customer Cancelled Booking: ID ' . $bookingId . ', ' . $cabType . ' from ' . $cityA . ' to ' . $cityB . ' at ' . $time . ' on ' . $date . '. Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Driver;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookingModel->bkg_id;
		smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function informDriverCustomerCancelledNew($ext, $driverId, $bookingId, $bkgId, $status)
	{
		$model	 = Drivers::model()->findByPk($driverId);
		$number	 = ContactPhone::model()->getContactPhoneById($model->drv_contact_id);
		$sms	 = new Messages();
		$msg	 = 'We regret to inform that ' . $bookingId . ' has been cancelled by the customer - Gozocabs';
		if ($number != '')
		{
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Driver;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $bkgId;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, '', '', $refType, $refId);
			if ($slgId != '')
			{
				$desc		 = 'SMS to driver for Booking Cancellation';
				$userType	 = BookingLog::System;
				$userId		 = 0;
				$params		 = array('blg_ref_id' => $slgId, 'blg_booking_status' => $status);
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::SMS_SENT, '', $params);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function informCustomerCancelled($ext, $number, $bookingId, $crid)
	{
		$model = Booking::model()->getByCode($bookingId);
		if (!$model)
		{
			$model = Booking::model()->findByPk($bookingId);
		}
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$sms		 = new Messages();
		$msg		 = CancelReasons::model()->getSMSTemplate(CancelReasons::USER_TYPE_CUSTOMER, $crid, $bookingId);
		$refType	 = SmsLog::REF_USER_ID;
		$refId		 = $model->bkgUserInfo->bkg_user_id;
		$usertype	 = SmsLog::Consumers;
		if ($msg != '')
		{
			$logArr	 = [];
			$slgId	 = '';
			if ($model->bkg_agent_id > 0)
			{
				// remove comment when agent panel notificaion will be live
				$logArr1 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::CANCEL_TRIP);
				$logArr	 = $logArr1['sms'];
				// remove comment when agent panel notificaion will be live
				if (count($logArr) > 0)
				{
					foreach ($logArr as $key => $value)
					{
						$usertype	 = $key;
						$countryCode = $value['country_code'];
						$contactNo	 = $value['phone'];
						$res		 = $sms->sendMessage($countryCode, $contactNo, $msg);
						$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $bookingId, $msg, $res, $usertype, "", "", $refType, $refId);
					}
				}
			}
			else
			{
				$delayTime	 = 0;
				$res		 = $sms->sendMessage($ext, $number, $msg, $delayTime);
				$slgId		 = \smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, "", "", $refType, $refId, $delayTime);
			}

			//booking log
			if ($slgId != '')
			{
				$desc		 = 'SMS to customer for Booking Cancellation';
				$userInfo	 = UserInfo::model();
				$oldModel	 = clone $model;
				$params		 = array('blg_ref_id' => $slgId, 'blg_booking_status' => $model->bkg_status);
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::SMS_SENT, $oldModel, $params);
			}
		}
	}

	public static function informUpdateToVendor($ext, $number, $bookingID, $messageText, $name)
	{
		$model = Booking::model()->getByCode($bookingID);
		if (!$model)
		{
			$model = Booking::model()->findByPk($bookingID);
		}
		if ($model != '' && $model->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		$cabModel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $name . ', ' . 'Important update from Gozocabs- ' . $messageText . ', Regards.';
		//$msg		 = 'Dear ' . Vendors::model()->getName() . ', ' . 'Important update from Gozocabs- ' . $messageText . ', Regards.';
		//$msg = 'Dear Vendor, Booking ID: ' . $bookingID . ' has been modified. New Details: ' . $messageText . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_VENDOR_ID;
		$refId		 = $cabModel->bcb_vendor_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function informUpdateToDriver($ext, $number, $bookingID, $messageText, $name)
	{
		$model = Booking::model()->getByCode($bookingID);
		if (!$model)
		{
			$model = Booking::model()->findByPk($bookingID);
		}
		if ($model != '' && $model->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		$cabModel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $name . ', ' . 'Important update from Gozocabs- ' . $messageText . ', Regards.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Driver;
		$refType	 = SmsLog::REF_DRIVER_ID;
		$refId		 = $cabModel->bcb_driver_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function informDriverBlocked($ext, $bookingID, $number, $name, $drvname, $rating)
	{
		$model		 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$message	 = 'your driver ' . $drvname . ' is rated LOW ' . $rating . '* by the customer for Booking ID ' . $bookingID . ' ,He is now blocked by Gozo. Contact call center urgently!';
		$msg		 = 'Dear ' . $name . ', A NEW RATING- ' . $message . '-GOZO';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_DRIVER_ID;
		$refId		 = $cabmodel->bcb_driver_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function informCarBlocked($ext, $bookingID, $number, $name, $carnum, $rating)
	{
		$model		 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$message	 = 'your car ' . $carnum . ' is rated LOW ' . $rating . '* by the customer for Booking ID ' . $bookingID . ' ,It is now blocked by Gozo. Contact call center urgently!';
		$msg		 = 'Dear ' . $name . ', A NEW RATING- ' . $message . '-GOZO';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_VENDOR_ID;
		$refId		 = $cabmodel->bcb_vendor_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function sendAppreciationMessageDriver($ext, $number, $type, $bookingID, $name)
	{
		$model		 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $name . ', ' . 'A NEW RATING - you are rated HIGH 5* by the customer for the Booking ID- ' . $bookingID . ', GREAT JOB! -GOZO';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Driver;
		$refType	 = SmsLog::REF_DRIVER_ID;
		$refId		 = $cabmodel->bcb_driver_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function sendAppreciationMessageVendor($ext, $number, $type, $bookingID, $name, $drivername)
	{
		$model		 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $name . ', ' . 'A NEW RATING - your driver ' . $drivername . 'is rated HIGH 5* by the customer for the Booking ID- ' . $bookingID . ', GREAT JOB! -GOZO';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_VENDOR_ID;
		$refId		 = $cabmodel->bcb_vendor_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public function sendAlertMessageVendor($ext, $vendorId, $message, $smstype = '')
	{
		$success	 = false;
		$model		 = Vendors::model()->findByPk($vendorId);
		$contactId	 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
		if (!$contactId)
		{
			$contactId = $model->vnd_contact_id;
		}
		$number = ContactPhone::getContactPhoneById($contactId);
		if ($number != '')
		{
			$sms		 = new Messages();
			$res		 = $sms->sendMessage($ext, $number, $message);
			$usertype	 = SmsLog::Vendor;
			$refType	 = SmsLog::REF_VENDOR_ID;
			$refId		 = $model->vnd_id;
			smsWrapper::createLog($ext, $number, '', $message, $res, $usertype, '', $smstype, $refType, $refId);
			$success	 = true;
		}
		return $success;
	}

	public static function pickupOfferDriver($ext, $number, $bookingID, $messageText, $name)
	{
		$model		 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $model->getBookingCabModel();
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $name . ', ' . $messageText . '-Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Driver;
		$refType	 = SmsLog::REF_DRIVER_ID;
		$refId		 = $cabmodel->bcb_driver_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

	public static function pickupOfferCustomer($ext, $number, $bookingID, $message, $customerName)
	{
		$model = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($model->bkg_agent_id > 0)
		{
			return;
		}
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $customerName . ', ' . $message . '-Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Customer;
		$refType	 = SmsLog::REF_USER_ID;
		$refId		 = $model->bkg_user_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

//    public static function informVendorTaxiAction($ext, $number, $cab_number, $bookingID) {
//        $sms = new send_sms();
//        $msg = 'Cab ID ' . $cab_number . " updated Successfully";
//        $res = $sms->sendMessage($ext, $number, $msg);
//        smsWrapper::createLog($ext, $number, $bookingID, $msg, $res);
//    }
	public static function pickupDetailsToDriver($ext, $number, $userId, $driver_name, $cust_phone, $cust_name, $pickup_address, $date_time, $amount, $bookingID, $usertype = SmsLog::Driver, $counter = "", $logType = null, $sendContact = false)
	{
		$bookModel	 = Booking::model()->find('bkg_booking_id=:id', ['id' => $bookingID]);
		$cabmodel	 = $bookModel->getBookingCabModel();
		if ($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		$sms = new Messages();
		if ($usertype == '2'):
			$smstype = SmsLog::SMS_VENDOR_CAB_DETAILS_UPDATED_NO_CONTACT;
		endif;
		if ($usertype == '3'):
			$smstype = SmsLog::SMS_DRIVER_CAB_DETAILS_UPDATED_NO_CONTACT;
		endif;
		$due	 = round($bookModel->bkgInvoice->bkg_due_amount);
		$tags	 = [];
		if ($bookModel->bkgTrail->bkg_tags != '')
		{
			$tags = explode(',', $bookModel->bkgTrail->bkg_tags);
		}
		if ($bookModel->bkgInvoice->bkg_advance_amount < $bookModel->bkgInvoice->bkg_total_amount)
		{
			$amtStr = ', Collect Amount: Rs ' . $amount;
		}
		else
		{
			$amtStr = '';
		}
		if ($bookModel->bkgInvoice->bkg_corporate_credit > 0 && $bookModel->bkgInvoice->bkg_corporate_remunerator == 2)
		{
			$amtStr = '';
		}
		$hashBkgId		 = Yii::app()->shortHash->hash($bookModel->bkg_id);
		$hashVndId		 = Yii::app()->shortHash->hash($cabmodel->bcb_vendor_id);
		$vendorLink		 = 'aaocab.com/bkvn/' . $hashBkgId . '/' . $hashVndId;
		$hash			 = Yii::app()->shortHash->hash($bookModel->bkg_id);
		$tripUrl		 = 'https://aaocab.com/vtrip/' . $bookModel->bkg_id . '/' . $hash;
		$additionalInfo	 = (trim($bookModel->getFullInstructions()) == '') ? '' : ',
				 NOTE: ' . rtrim($bookModel->getFullInstructions(), '.');
//		$bookingExt6	 = substr($bookingID, -6);
		//$prefmodel		 = BookingPref::model()->getByBooking($bookModel->bkg_id);
		$otpRequired	 = $bookModel->bkgPref->bkg_trip_otp_required;
		$route			 = BookingRoute::model()->getRouteNameByBookingId($bookModel->bkg_id);
		$msg			 = "BKGID: " . $bookingID . '| Route: ' . $route . '| Time: ' . $date_time . '| Address: ' . $pickup_address . '|' . $amtStr . '| See ' . $vendorLink . '';
//		if ($otpRequired == 0)
//		{
//			$msg .= '';
//		}
//		else
//		{
//			$msg .= '.Please verify OTP before you start the trip using driver app or go to ' . $tripUrl . ' or SMS \'START ' . $bookModel->bkg_id . ' <OTP>\' TO 8340000181   - Gozocabs';
//		}

		if ($sendContact)
		{
			if (($bookModel->bkg_agent_id == null || $bookModel->bkg_agent_id == '' || $bookModel->bkg_agent_id == Yii::app()->params['gozoChannelPartnerId']) && $bookModel->bkgUserInfo->bkg_country_code == 91)
			{
				//$cust_phone = Yii::app()->params['driverToCustomer'];
				$cust_phone = BookingPref::getCustomerNumber($bookModel, $bookModel->bkgUserInfo->bkg_contact_no);
			}

			$alternate	 = ($bookModel->bkgUserInfo->bkg_alt_contact_no != '') ? 'Alternate No.: ' . $bookModel->bkgUserInfo->bkg_alt_contact_no . ',' : '';
			$isTollTax	 = ($bookModel->bkgInvoice->bkg_is_toll_tax_included == 1) ? 'Toll Tax(Included)|' : '';
			$isStateTax	 = ($bookModel->bkgInvoice->bkg_is_state_tax_included == 1) ? 'State Tax(Included)|' : '';
			$parking	 = ($bookModel->bkgInvoice->bkg_parking_charge > 0) ? 'Parking(Included)|' : '';
			if ($bookModel->bkgInvoice->bkg_driver_allowance_amount != 0)
			{
				if ($bookModel->bkgInvoice->bkg_night_pickup_included == 1)
				{
					$nightPickup = ",Night Pickup(Included)";
				}
				if ($bookModel->bkgInvoice->bkg_night_drop_included == 1)
				{
					$nightDrop = ",Night Drop(Included)";
				}
			}


			$msg = 'BKGID: ' . $bookingID . ' |Route: ' . $route . ' |Pickup Time: ' . $date_time . $isTollTax . $isStateTax . $parking . $amtStr . $nightPickup . $nightDrop . ' |
					CLICK ' . $vendorLink . ' for details';
//			if ($otpRequired == 0)
//			{
//				$msg .= '';
//			}
//			else
//			{
//				$msg .= '.
//					Must use Driver app. If no app, Enter OTP at ' . $tripUrl . ' or SMS \'START ' . $bookModel->bkg_id . ' <OTP>\' TO 8340000181 - Gozocabs';
//			}
// Must use Driver app. Get OTP from customer to start the trip. If no app, Enter OTP at https://aaocab.com/vtrip/312912/Foeau or SMS 'START 312912 <OTP>' TO 8340000181 - Gozocabs
// Please verify OTP before you start the trip using driver app or go to ' . $tripUrl . ' or SMS \'START ' . $bookModel->bkg_id . ' <OTP>\' TO 8340000181 - Gozocabs';
			if ($usertype == '2'):
				$smstype = SmsLog::SMS_VENDOR_CAB_DETAILS_UPDATED;
			endif;
			if ($usertype == '3'):
				$smstype = SmsLog::SMS_DRIVER_CAB_DETAILS_UPDATED;
			endif;
		}
		$res	 = $sms->sendMessage($ext, $number, $msg);
		$refType = SmsLog::REF_BOOKING_ID;
		$refId	 = $bookModel->bkg_id;
		$slgId	 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, $counter, $smstype, $refType, $refId);
		//$model = new Booking();
		//$bookData = $model->getBkgIdByBookingId($bookingID);
		if ($slgId != '')
		{
			$userInfo = UserInfo::getInstance();
			if ($usertype == '3')
			{
				$desc							 = "SMS to driver for Cab Details Updation.";
				$eventId						 = BookingLog::SMS_SENT;
				$oldModel						 = clone $bookModel;
				$params							 = [];
				$params['blg_ref_id']			 = $slgId;
				$params['blg_booking_status']	 = $bookModel->bkg_status;
				BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
			if ($usertype == '2')
			{
				$desc							 = "SMS to vendor for Cab Details Updation.";
				$eventId						 = BookingLog::SMS_SENT;
				$oldModel						 = clone $bookModel;
				$params							 = [];
				$params['blg_ref_id']			 = $slgId;
				$params['blg_booking_status']	 = $bookModel->bkg_status;
				BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function pickupReminder($ext, $number, $driver_name, $cust_phone, $cust_name, $pickup_address, $date_time, $amount, $bookingID, $usertype = SmsLog::Driver, $counter = "", $delay = 0)
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if (($bookingModel != '' && $bookingModel->bkgPref->bkg_blocked_msg == 1) || $bookingModel->bkg_agent_id > 0)
		{
			return;
		}
		$sms = new Messages();
//	if ($bookingModel->bkg_agent_id == null || $bookingModel->bkg_agent_id == '' || $bookingModel->bkg_agent_id == Yii::app()->params['gozoChannelPartnerId'])
//	{
		if ($bookingModel->bkgUserInfo->bkg_country_code == 91)
		{
			//$cust_phone = Yii::app()->params['driverToCustomer'];
			$cust_phone = BookingPref::getCustomerNumber($bookingModel, $bookingModel->bkgUserInfo->bkg_contact_no);
			if ($bookingModel->bkg_agent_id == 450 || $bookingModel->bkg_agent_id == 18190)
			{
				//$cust_phone = Yii::app()->params['driverToCustomerforMMT'];
				$cust_phone = BookingPref::getCustomerNumber($bookingModel, $bookingModel->bkgUserInfo->bkg_contact_no);
			}
		}
//	}
		//$msg = 'Dear ' . $driver_name . ", Immediate pickup reminder - Name: " . $cust_name . ', Phone: ' . $cust_phone . ', Pickup Time: ' . $date_time . ', Address: ' . $pickup_address . ', Amount: Rs.' . $amount . ' - Gozocabs';
		$msg	 = 'IMMEDIATE PICKUP -- BKGID: ' . $bookingID . ' |Customer - ' . $cust_name . ' |Ph: ' . $cust_phone . ' |Time:' . $date_time . ' |Address: ' . $pickup_address . ' |Collect: Rs.' . $amount . '';
		$res	 = $sms->sendMessage($ext, $number, $msg, $delay);
		$refType = SmsLog::REF_BOOKING_ID;
		$refId	 = $bookingModel->bkg_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, $counter, '', $refType, $refId);
	}

	public static function remindVendorUpdateDetails($ext, $number, $cabType, $cityA, $cityB, $date, $time, $bookingID, $dueAmount = "", $instructions = "", $counter = "")
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 && $bookingModel->bkgPref->bkg_send_sms == 0))
		{
			return;
		}
		$dueAmount	 = ($dueAmount > 0) ? $dueAmount : round($bookingModel->bkgInvoice->bkg_due_amount);
		$refId		 = $bookingModel->bkg_id;
		$refType	 = 1;
		$sms		 = new Messages();
		//$msg = 'Driver Information overdue: Booking: ID ' . $bookingID . ', ' . $cabType . ' from ' . $cityA . ' to ' . $cityB . ' at ' . $time . ' on ' . $date . ", Amount: $amount, Instructions: $instructions" . ' - Gozocabs';
		$msg		 = 'Driver Information overdue: BKGID: ' . $bookingID . ', ' . $cabType . ' from ' . $cityA . ' to ' . $cityB . ' at ' . $time . ' on ' . $date . ", Amount to collect: $dueAmount, Instructions: $instructions" . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg, 0);
		$usertype	 = SmsLog::Vendor;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, $counter, '', $refType, $refId, 0);
	}

	public static function remindVendorPickupDue($ext, $number, $msg, $time, $vname, $count, $counter = "", $delay = 0, $vendor_id = 0)
	{
		//$sms		 = new Messages();
		//$bookingID	 = '';
		//$time		 = DateTimeFormat::DateTimeToLocale($time);
		$msg = "Attention: " . $count . " PICKUP(S) TO BE DONE IN NEXT 36 hours. Please assign driver immediately if not done yet.\n\r\n\r\n\r";
		//		. $msg;
		//	echo $msg;
		//$msg = "Dear Vendor, Pickup summary as on $time : $msg - Gozocabs";
		//$res		 = $sms->sendMessage($ext, $number, $msg, $delay);
		//$usertype	 = SmsLog::Vendor;
		//smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, $counter, '', '', '', $delay);
		if ($vendor_id > 0)
		{
			$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
			$success	 = AppTokens::model()->notifyVendor($vendor_id, $payLoadData, $msg, "PICKUP REMINDER");
		}
	}

	public static function onTripHost($bkg_row)
	{
		$model = Booking::model()->findByPk($bkg_row['bkg_id']);
		if (($model != '' && $model->bkgPref->bkg_blocked_msg == 1) || $model->bkg_agent_id > 0)
		{
			return false;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$phone		 = $response->getData()->phone['number'];
			$ext		 = $response->getData()->phone['ext'];
			$userName	 = $response->getData()->phone['userName'];
		}
		echo $ext		 = $ext ? $ext : $bkg_row['bkg_country_code'];
		echo "\t";
		echo $phone		 = $phone ? $phone : $bkg_row['bkg_contact_no'];
		echo "\t";
		$userName	 = $userName ? $userName : $bkg_row['bkg_user_name'];
		echo $message	 = "Dear {$userName}, I'm Kiran, your on-trip host at Gozocabs. Your safety & convenience is our highest priority. Call me at +91-33-66283901 if you need any help during this trip.";
		echo "\n";
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $phone, $message);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $model->bkg_id;
		smsWrapper::createLog($ext, $phone, $bkg_row['bkg_id'], $message, $res, $usertype, '', '', $refType, $refId);
	}

	public static function onTripGuide($bkg_row)
	{
		$model = Booking::model()->findByPk($bkg_row['bkg_id']);
		if (($model != '' && $model->bkgPref->bkg_blocked_msg == 1) || $model->bkg_agent_id > 0)
		{
			return false;
		}
		$cModel		 = Cities::model()->findByPk($bkg_row['bkg_to_city_id']);
		$city_name	 = $cModel->cty_name;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$phone		 = $response->getData()->phone['number'];
			$ext		 = $response->getData()->phone['ext'];
			$userName	 = $response->getData()->phone['userName'];
		}
		echo $ext		 = $ext ? $ext : $bkg_row['bkg_country_code'];
		echo "\t";
		echo $phone		 = $phone ? $phone : $bkg_row['bkg_contact_no'];
		echo "\t";
		$userName	 = $userName ? $userName : $bkg_row['bkg_user_name'];
		echo $message	 = "Dear {$userName}, This is in reference to your booking ID {$bkg_row['bkg_booking_id']}. Gozocabs local crew in $city_name can arrange tours and "
		. "other travel services for you if needed. Please call us at at +91-90518-77000 for any help needed in $city_name";
		echo "\n";
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $phone, $message);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $model->bkg_id;
		smsWrapper::createLog($ext, $phone, $bkg_row['bkg_id'], $message, $res, $usertype, '', '', $refType, $refId);
	}

	public static function onTripPayment($bkg_row)
	{
		$model = Booking::model()->findByPk($bkg_row['bkg_id']);
		if (($model != '' && $model->bkgPref->bkg_blocked_msg == 1) || $model->bkg_agent_id > 0)
		{
			return false;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$phone		 = $response->getData()->phone['number'];
			$ext		 = $response->getData()->phone['ext'];
			$userName	 = $response->getData()->phone['userName'];
		}
		$userName	 = $userName ? $userName : $bkg_row['bkg_user_name'];
		$amount		 = $bkg_row['bkg_total_amount'];
		$advance	 = $bkg_row['bkg_advance_amount'];

		if ($advance < $amount)
		{
			$due	 = $amount - $model->getTotalPayment();
			//$url	 = 'aaocab.com/bkpn/' . $bkg_row['bkg_id'] . '/' . Yii::app()->shortHash->hash($bkg_row['bkg_id']);
			$url	 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($bkg_row['bkg_id']);
			$message = "Dear {$userName}, Your remaining balance is Rs. $due payable to the driver by end of this trip. Click on following URL if you prefer to pay the balance by credit card. Your comfort and trust are important to us. $url";
		}
		else
		{
			$message = "Dear {$userName}, Your trip has been paid for in FULL.NO additional amount is due (except applicable parking charges). Your comfort and trust are important to us.";
		}
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $phone, $message);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $model->bkg_id;
		smsWrapper::createLog($ext, $phone, $bkg_row['bkg_id'], $message, $res, $usertype, '', '', $refType, $refId);
	}

	public function advancePaymentToDriver($bkgId, $logType = '')
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkg_status != 5))
		{
			return;
		}
		$cabmodel = $model->getBookingCabModel();
		if ($cabmodel->bcb_driver_phone != '')
		{
			$sms			 = new Messages();
			$ext			 = $model->bkgUserInfo->bkg_country_code;
			$driverNumber	 = $cabmodel->bcb_driver_phone;
			$bookingId		 = $model->bkg_booking_id;
			$balAmount		 = $model->bkgInvoice->bkg_due_amount;
			//$msg = 'Update from Gozo about : ' . $bookingId . '  Customer ' . $customer . ' has paid Rs. ' . $advAmount . ' advance. Balance to be collected is Rs. ' . $balAmount . '';
			$msg			 = 'Booking  ID: ' . $bookingId . '   Driver to collect Rs. ' . $balAmount . ' from customer';
			$res			 = $sms->sendMessage($ext, $driverNumber, $msg);
			$usertype		 = SmsLog::Driver;
			$smstype		 = SmsLog::SMS_DRIVER_ONLINE_PAYMENT;
			$refType		 = SmsLog::REF_BOOKING_ID;
			$refId			 = $model->bkg_id;
			$slgId			 = smsWrapper::createLog($ext, $driverNumber, $bookingId, $msg, $res, $usertype, "", $smstype, $refType, $refId);
			if ($slgId != '')
			{
				$desc = "Sms sent to driver for advance payment.";

				$eventId						 = BookingLog::SMS_SENT;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_ref_id']			 = $slgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), $eventId, $oldModel, $params);
			}
			return true;
		}
		return false;
	}

	public function advancePaymentToVendor($bkgId, $logType = '')
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || !in_array($model->bkg_status, [3, 5])))
		{
			return false;
		}
		$cabmodel = $model->getBookingCabModel();
		if ($cabmodel->bcb_vendor_id != '')
		{
			$sms		 = new Messages();
			$ext		 = $model->bkgUserInfo->bkg_country_code;
			//$number		 = $cabmodel->bcbVendor->vnd_phone;
			$vndModel	 = Vendors::model()->findByPk($cabmodel->bcbVendor->vnd_id);
			$contactId	 = ContactProfile::getByEntityId($cabmodel->bcbVendor->vnd_id, UserInfo::TYPE_VENDOR);
			if (!$contactId)
			{
				$contactId = $vndModel->vnd_contact_id;
			}
			$number		 = ContactPhone::getContactPhoneById($contactId);
			$bookingId	 = $model->bkg_booking_id;
			$balAmount	 = $model->bkgInvoice->bkg_due_amount;
			//$msg = 'Update from Gozo about : ' . $bookingId . '  Customer ' . $customer . ' has paid Rs. ' . $advAmount . ' advance. Balance to be collected is Rs. ' . $balAmount . '';
			$msg		 = 'Booking  ID: ' . $bookingId . '   Driver to collect Rs. ' . $balAmount . ' from customer';
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Vendor;
			$smstype	 = SmsLog::SMS_VENDOR_ONLINE_PAYMENT;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, "", $smstype, $refType, $refId);
			if ($slgId != '')
			{
				$desc							 = "Sms sent to vendor for advance payment.";
				//$userType = BookingLog::Vendor;
				//$userId = $model->bkg_driver_id;
				$eventId						 = BookingLog::SMS_SENT;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_ref_id']			 = $slgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), $eventId, $oldModel, $params);
			}
			return true;
		}
		return false;
	}

	public static function informCustBookingModTime($ext, $number, $bookingID, $date, $time)
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if (($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0)) || $bookingModel->bkg_agent_id > 0)
		{
			return false;
		}
		$sms		 = new Messages();
		$msg		 = 'Dear Customer, Your BKGID: ' . $bookingID . ' has been modified. New Pickup Date: ' . $date . ', Time: ' . $time . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookingModel->bkg_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
		if ($slgId > 0)
		{
			return true;
		}
	}

	public static function informChangesToCustomer($ext, $number, $bookingID, $changes, $logType = '')
	{

		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		/* @var $bookingModel Booking */
		$hash		 = Yii::app()->shortHash->hash($bookingModel->bkg_id);
		//$paymentLink = 'aaocab.com/bkpn/' . $bookingModel->bkg_id . '/' . $hash;
		$paymentLink = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($bookingModel->bkg_id);
		$sms		 = new Messages();
		//$msg = 'Dear Customer, Your Booking ID: ' . $bookingID . ' has been modified. New Details: ' . $changes . ' - Gozocabs';
		$msg		 = 'UPDATE TO BKGID: ' . $bookingID . '  ## See NEW Details & Terms at ' . $paymentLink . '';

		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookingModel->bkg_id;

		$logArr = [];
		if ($bookingModel->bkg_agent_id > 0)
		{
			// remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bookingModel->bkg_id, AgentMessages::BOOKING_EDIT);
			$logArr	 = $logArr1['sms'];
			// remove comment when agent panel notificaion will be live
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$countryCode = $value['country_code'];
					$contactNo	 = $value['phone'];
					$res		 = $sms->sendMessage($countryCode, $contactNo, $msg);
					$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $bookingID, $msg, $res, $usertype, "", "", $refType, $refId);
				}
			}
		}
		else
		{
			$res	 = $sms->sendMessage($ext, $number, $msg);
			$slgId	 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
		}

		//booking log
		if ($slgId != '' && $slgId != '')
		{
			$desc							 = "SMS sent customer for Booking Modification.";
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookingModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookingModel->bkg_status;
			BookingLog::model()->createLog($bookingModel->bkg_id, $desc, UserInfo::getInstance(), $eventId, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function informChangesToVendor($ext, $number, $bookingID, $changes, $logType = '')
	{
		/* @var $bookModel Booking */
		$bookModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1)
		{
			return false;
		}
		$cabmodel	 = $bookModel->getBookingCabModel();
		/* @var $cabmodel BookingCab */
		//$hash		 = Yii::app()->shortHash->hash($bookModel->bkg_id);
		$hashBkgId	 = Yii::app()->shortHash->hash($bookModel->bkg_id);
		$hashVndId	 = Yii::app()->shortHash->hash($cabmodel->bcb_vendor_id);
		$vendorLink	 = 'aaocab.com/bkvn/' . $hashBkgId . '/' . $hashVndId;
		//$paymentLink = 'aaocab.com/bkvendor/' . $bookModel->bkg_id . '/' . $hash; / Duplicate link created
		$sms		 = new Messages();
		//$msg = 'Dear Vendor, Booking ID: ' . $bookingID . ' has been modified. New Details: ' . $changes . ' - Gozocabs';
		$msg		 = 'UPDATE TO Booking ID: ' . $bookingID . '  ## See NEW Details & Terms at ' . $vendorLink . '';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$logType	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookModel->bkg_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $logType, '', '', $refType, $refId);
		if ($slgId != '')
		{
			$desc							 = "SMS sent vendor for Booking Modification.";
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, UserInfo::getInstance(), $eventId, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function informChangesToDriver($ext, $number, $bookingID, $changes, $logType = '')
	{

		$bookModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1)
		{
			return false;
		}
		/* @var $bookModel Booking */
		$cabmodel = $bookModel->getBookingCabModel();
		/* @var $cabmodel BookingCab */
//		$hash		 = Yii::app()->shortHash->hash($bookModel->bkg_id);
//		$paymentLink = 'aaocab.com/bkvendor/' . $bookModel->bkg_id . '/' . $hash;

		$hashBkgId	 = Yii::app()->shortHash->hash($bookModel->bkg_id);
		$hashVndId	 = Yii::app()->shortHash->hash($cabmodel->bcb_vendor_id);
		$vendorLink	 = 'aaocab.com/bkvn/' . $hashBkgId . '/' . $hashVndId;
		$sms		 = new Messages();
		$msg		 = 'UPDATE TO Booking ID: ' . $bookingID . '  ## See NEW Details & Terms at ' . $vendorLink . '';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$userType	 = SmsLog::Driver;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookModel->bkg_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $userType, '', '', $refType, $refId);
		if ($slgId != '')
		{
			$desc = "SMS sent driver for Booking Modification.";
			if ($userType != '')
			{
				switch ($userType)
				{
					case '1':
						$userId	 = $bookModel->bkgUserInfo->bkg_user_id;
						break;
					case '3':
						$userId	 = $cabmodel->bcb_driver_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, UserInfo::getInstance(), $eventId, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function sentFeedbackSmsVendor($ext, $number, $bookingID, $changes)
	{
		$bookModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1)
		{
			return false;
		}
		$cabmodel	 = $bookModel->getBookingCabModel();
		$sms		 = new Messages();
		$msg		 = 'Dear Vendor, ' . $changes . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookModel->bkg_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
		if ($slgId != '')
		{
			$desc = "SMS sent to vendor for Feedback.";
			if ($usertype != '')
			{
				switch ($usertype)
				{
					case '1':
						$userId	 = $bookModel->bkgUserInfo->bkg_user_id;
						break;
					case '2':
						$userId	 = $cabmodel->bcb_vendor_id;
						break;
					case '3':
						$userId	 = $cabmodel->bcb_driver_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	public static function sentFlexxiMatchMessage($ext, $number, $bookingID, $changes, $desc)
	{
		$bookModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if ($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1)
		{
			return false;
		}
		$sms		 = new Messages();
		$msg		 = $changes . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookModel->bkg_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
		if ($slgId != '')
		{
			if ($usertype != '')
			{
				$userId = $bookModel->bkgUserInfo->bkg_user_id;
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	public static function sentFeedbackSmsDriver($ext, $number, $bookingID, $changes)
	{
		/* @var $model Booking */
		$bookModel	 = Booking::model()->getBkgIdByBookingId($bookingID);
		$cabmodel	 = $bookModel->getBookingCabModel();
		if ($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1)
		{
			return false;
		}
		$sms		 = new Messages();
		$msg		 = 'Dear Driver, ' . $changes . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Driver;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookModel->bkg_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
		//$bookModel = new Booking();
		//$bookData = $bookModel->getBkgIdByBookingId($bookingID);
		//$cabmodel = $bookModel->getBookingCabModel();
		$userId		 = $cabmodel->bcb_driver_id;
		if ($slgId != '')
		{
			$desc							 = "SMS sent to driver for Feedback.";
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	public static function sentFeedbackSmsCustomer($ext, $number, $bookingID, $changes)
	{
		/* var $model Booking */
		//$bookModel = new Booking();
		$bookModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if (($bookModel != '' && $bookModel->bkgPref->bkg_blocked_msg == 1) || $bookModel->bkg_agent_id > 0)
		{
			return;
		}
		$sms		 = new Messages();
		$msg		 = 'Dear Customer, ' . $changes . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_USER_ID;
		$refId		 = $bookModel->bkgUserInfo->bkg_user_id;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
		if ($slgId != '')
		{
			$desc = "SMS sent to customer for Feedback.";
			if ($usertype != '')
			{
				$userType = $userType;
				switch ($usertype)
				{
					case '1':
						$userId	 = $bookModel->bkgUserInfo->bkg_user_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookModel->bkg_status;
			BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

//    public static function informCustBookingModTime1($ext, $number, $bookingID, $date, $time) {
//        $sms = new send_sms();
//        $msg = 'Dear Customer, Your Booking ID: ' . $bookingID . ' has been modified. New Return Date: ' . $date . ', Time: ' . $time . ' - Gozocabs';
//        $sms->sendMessage(array('number' => $number, 'msg' => $msg));
//        $class = "smsWrapper";
//        $class::updateSmsInSystem($bookingID, $msg, "CBUT", $number);
//    }
//    //Dear Customer, Your Booking ID: * has been modified. New Pickup Date: *, Time: * Gozocabs
//
	public static function informCustBookingModAddress($ext, $number, $bookingID, $pickupAddress)
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingID);
		if (($bookingModel != '' && $bookingModel->bkgPref->bkg_blocked_msg == 1) || $bookingModel->bkg_agent_id > 0)
		{
			return;
		}
		$sms		 = new Messages();
		$msg		 = 'Dear Customer, Your Booking ID: ' . $bookingID . ' has been modified. New Pickup Address: ' . $pickupAddress . ' - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookingModel->bkg_id;
		smsWrapper::createLog($ext, $number, $bookingID, $msg, $res, $usertype, '', '', $refType, $refId);
	}

//    //Dear Customer, Your Booking ID: * has been modified. New Pickup Address: * Gozocabs
//
//    public static function sendDiscountCouponCustomer($ext, $number, $discount, $place, $couponCode, $bookingID) {
//        $sms = new send_sms();
//        $msg = 'Dear Customer, Avail ' . $discount . ' off on food items at ' . $place . '. Show SMS at the counter before billing. Coupon code: ' . $couponCode . ' - Gozocabs';
//        $sms->sendMessage(array('number' => $number, 'msg' => $msg));
//        $class = "smsWrapper";
//        $class::updateSmsInSystem($bookingID, $msg, "CBGD", $number);
//    }
	//Dear *, Avail * on food items at *. Show SMS at the counter before billing. Coupon code: *


	public static function sendPaymentLink($bkgid, $minPayExtra = 0)
	{
		$model = Booking::model()->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$sms		 = new Messages();
		$booking_id	 = $model->bkg_booking_id;
		//changes
		//  changes
		$advance	 = 0;
		if ($model->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = round($model->bkgInvoice->bkg_advance_amount);
			$due	 = round($model->bkgInvoice->bkg_due_amount);
		}

		$maxTimeAdvPay = date('d/m/Y h:i A', strtotime($model->bkgTrail->bkg_payment_expiry_time));

		$hash		 = Yii::app()->shortHash->hash($bkgid);
		//$url	 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $bkgid, 'hash' => $hash]);
		#$url		 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$url		 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$url		 = Filter::shortUrl($url);
		$amountStr	 = '';
		if ($advance > 0)
		{
			if ($model->bkgInvoice->bkg_credits_used > 0)
			{
				$strCredit = ', Gozo Coins Used: ' . $model->bkgInvoice->bkg_credits_used;
			}
			$amountStr = ' Advance Paid: Rs.' . $advance . $strCredit . ', Amount due: Rs.' . $due;
		}
		else
		{
			if ($model->bkgInvoice->bkg_credits_used > 0)
			{
				$amountStr = ' Gozo Coins Used: ' . $model->bkgInvoice->bkg_credits_used . ', Amount due Rs.' . round($model->bkgInvoice->bkg_due_amount);
			}
			else
			{
				$amountStr = ' Amount payable Rs.' . $model->bkgInvoice->bkg_total_amount . ' - Gozocabs';
			}
		}
		if ($model->bkgInvoice->bkg_corporate_credit > 0)
		{
			$amountStr = ' Amount due ' . round($model->bkgInvoice->bkg_due_amount);
		}

		if ($model->bkgInvoice->bkg_convenience_charge > 0 && ($model->bkgInvoice->bkg_corporate_credit == '' || $model->bkgInvoice->bkg_corporate_credit == 0))
		{
			$Model11	 = clone $model->bkgInvoice;
			$Model11->calculateConvenienceFee(0);
			$Model11->calculateTotal();
			$minamount	 = $Model11->calculateMinPayment();

			$amountStr .= ". Waive off COD fee (Rs." . $model->bkgInvoice->bkg_convenience_charge . ") by paying Rs." . $minamount . " in advance before " . $maxTimeAdvPay . " to save upto 50%";
		}

		if ($minPayExtra > 0)
		{
			$amountStr = "For rescheduling minimum amount payable is Rs." . $minPayExtra;
		}
		//changes
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$userName	 = $response->getData()->phone['userName'];
		}
		$msg		 = 'Dear ' . $userName . ', for your Booking ID: ' . $booking_id . '.' . $amountStr . ',Click ' . $url . ' make the payment.';
		$usertype	 = SmsLog::Consumers;

		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $model->bkg_id;
		$delay_time	 = 0;

		$logArr = [];
		if ($model->bkg_agent_id > 0)
		{
			// remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::SEND_PAYMENT_LINK);
			$logArr	 = $logArr1['sms'];
			// remove comment when agent panel notificaion will be live
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$countryCode = $value['country_code'];
					$contactNo	 = $value['phone'];

					$res	 = $sms->sendMessage($countryCode, $contactNo, $msg, $delay_time);
					$slgId	 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", "", $refType, $refId, $delay_time);
				}
			}
		}
		else
		{
			$res = $sms->sendMessage($countryCode, $contactNo, $msg, $delay_time);
			smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, '', '', $refType, $refId, $delay_time);
		}
	}

	public static function createLog($ext, $number, $bkgid = "", $msg, $res, $usertype, $counter = "", $smstype = "", $refType = "1", $refId = "", $delay_time = 1)
	{
		$smsLog = new SmsLog();
		if ($bkgid != "")
		{
			$smsLog->booking_id = $bkgid;
		}
		if ($bkgid != "")
		{
			$smsLog->slg_ref_id = $bkgid;
		}
		if ($refId != "")
		{
			$smsLog->slg_ref_id = $refId;
		}
		$smsLog->slg_ref_type		 = $refType;
		$smsLog->message			 = $msg;
		$smsLog->delivery_response	 = $res['smsProviderResponse'];
		$smsLog->slg_provider_type	 = $res['smsProvider'];
		$smsLog->recipient			 = $usertype;
		$smsLog->slg_phn_code		 = $ext;
		$smsLog->slg_phn_number		 = $number;
		$smsLog->number				 = $ext . str_replace('-', '', $number);

		if ($counter != "")
		{
			$smsLog->counter = $counter;
		}
		else
		{
			$smsLog->counter = 0;
		}
		if ($smstype != "")
		{
			$smsLog->slg_type = $smstype;
		}
		if ($delay_time == 0)
		{
			$smsLog->status = 1;
		}
		else
		{
			$smsLog->date_sent	 = new CDbExpression('DATE_ADD(NOW(), INTERVAL ' . $delay_time . ' MINUTE)');
			$smsLog->status		 = 2;
		}
		if ($smsLog->validate())
		{
			$smsLog->save();
		}
		else
		{
			print_r($smsLog->getErrors());
			exit();
		}
		return $smsLog->id;
	}

	public static function sentPaperworkSmsVendor($ext, $number, $vendorID, $changes)
	{
		if ($number != '')
		{
			$sms		 = new Messages();
			$msg		 = 'Dear Vendor, ' . $changes . ' - Gozocabs';
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Vendor;
			$smstype	 = SmsLog::SMS_MISSING_DRIVER_CAR;
			$refType	 = SmsLog::REF_VENDOR_ID;
			$refId		 = $vendorID;
			smsWrapper::createLog($ext, $number, '', $msg, $res, $usertype, "", $smstype, $refType, $refId);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function beforePickUpSmsCustomer($ext, $number, $bookingId, $changes, $bkgId = '')
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingId);
		if (($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0)))
		{
			return false;
		}
		if ($number != '' && ($bookingModel->bkg_agent_id == '' || $bookingModel->bkg_agent_id == 0))
		{
			$sms		 = new Messages();
			$msg		 = $changes;
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Consumers;
			$smstype	 = SmsLog::RECONFIRM_BEFORE_PICKUP;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $bookingModel->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, "", $smstype, $refType, $refId);
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Reconfirm Request Sent", UserInfo::model(), BookingLog::SMS_SENT, false, ["blg_ref_id" => $slgId]);
			}
			//Booking::model()->setBeforePickupSmsStatus($bkgId);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function preAutoCancelBeforePickupSms($ext, $number, $bookingId, $changes, $bkgId = '')
	{
		$bookingModel = Booking::model()->getBkgIdByBookingId($bookingId);
		if (($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0)) || $bookingModel->bkg_agent_id > 0)
		{
			return false;
		}
		if ($number != '')
		{
			//$model = Booking::model()->findByPk($bkgId);
			$oldModel	 = clone $bookingModel;
			$sms		 = new Messages();
			$msg		 = $changes;
			$res		 = $sms->sendMessage($ext, $number, $msg, 0);
			$usertype	 = SmsLog::Consumers;
			$smstype	 = SmsLog::PRE_AUTO_CANCEL_BEFORE_PICKUP;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $bookingModel->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingModel->bkg_booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Auto cancel warning has been sent.", UserInfo::model(), BookingLog::SMS_SENT, $oldModel, ["blg_ref_id" => $slgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function compensateDriverForOnRating($ext, $rtgId)
	{
		$data		 = Ratings::model()->getDriverVendorDetailsById($rtgId);
		$bkgId		 = $data['bkg_id'];
		$bookingId	 = $data['bkg_booking_id'];
		$drvId		 = $data['drv_id'];
		$drvName	 = $data['drv_name'];
		$number		 = $data['drv_phone'];
		$drvApproved = $data['drv_approved'];
		$credit		 = Yii::app()->params['driverCredit'];
		if ($drvApproved == 1)
		{
			$changes = 'आपकी पिछली ५ स्टार ट्रिप ' . $bookingId . ' के लिए आपको गोज़ो Rs ' . $credit . ' का बोनस देना चाहती है. यह बोनस पाने के लिए,  आप अपनी अगली ट्रिप पर गोज़ो ड्राईवर मोबाइल app use करे ';
		}
		else
		{
			$changes = 'आपकी पिछली ५ स्टार ट्रिप ' . $bookingId . ' के लिए आपको गोज़ो Rs ' . $credit . ' का बोनस देना चाहती है. यह बोनस पाने के लिए, अपने गाडी, ड्राईवर और बैंक के पेपर गोज़ो के साथ तुरंत भर्ती करे.';
		}
		//$filterObj = new Filter();
		//$sms_msg = str_replace('%u', '',$filterObj->utf8_to_unicode($changes));
		//$changes = 'for every booking id where we offer him a bonus, we need to track it either in the booking table or in the driver table....better to log such that we knwo that he got Rs. 50 for booking ID XXXX';
		//$ext, $number, $rtgId, $changes
		//$encoded_text = mb_convert_encoding($changes, "UTF-8", "auto");
		//$sms_msg = urlencode($encoded_text);
		//$number = $drvNumber;
		if ($number != '')
		{
			$sms		 = new Messages();
			$msg		 = $changes . ' - Gozocabs';
			$res		 = $sms->sendMessage($ext, $number, $msg, 1, $lang		 = Messages::MTYPE_HINDI);
			$usertype	 = SmsLog::Driver;
			$smstype	 = SmsLog::COMPENSATE_DRIVER_ON_RATING;
			$refType	 = SmsLog::REF_DRIVER_ID;
			$refId		 = $drvId;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $usertype, "", $smstype, $refType, $refId);
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Review Information has been sent to driver.", UserInfo::model(), BookingLog::SMS_SENT, false, ["blg_ref_id" => $slgId]);
			}
			Drivers::model()->updateDriverCredit($drvId, $credit);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function adhereToQualityForNewDriver($ext, $bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && $model->bkgPref->bkg_blocked_msg == 1)
		{
			return false;
		}
		$cabmodel		 = $model->getBookingCabModel();
		$drvContactId	 = $cabmodel->bcbDriver->drv_contact_id;
		$number			 = ContactPhone::model()->getContactPhoneById($drvContactId);
		if ($number != '')
		{
			$oldModel	 = clone $model;
			$msg		 = '' . $cabmodel->bcbDriver->drv_name . ' - TRIP ' . $model->bkg_booking_id . ' - गोज़ो के लीये क्वालिटी सर्विस बहुत जरुरी है. ग्राहक को टॉप क्वालिटी सर्विस दीजिए. यदि आपको प्राइसिंग, टोल या कोई भी बुकिंग रिलेटेड तकलीफ हो तो गोज़ो से संपर्क करें. ग्राहक भगवान सामान होता है. ५ स्टार सर्विस देने के लये गोज़ो आपको स्पॉट बोनस देगी.';
			$sms		 = new Messages();
			$res		 = $sms->sendMessage($ext, $number, $msg, 0, Messages::MTYPE_HINDI);
			$usertype	 = SmsLog::Driver;
			$smstype	 = SmsLog::DRIVER_ADHERE_TO_QUALITY;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $model->bkg_booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, '0');
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Quality sms sent to driver.", UserInfo::model(), BookingLog::SMS_SENT, $oldModel, ["blg_ref_id" => $slgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function automatedFollowup($ext, $bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0 || $model->bkg_agent_id > 0))
		{
			return false;
		}
		/* @var $model Booking */
		$oldModel		 = clone $model;
		$bookingId		 = $model->bkg_booking_id;
		$chargesOff		 = 250;
		$baseAmount		 = $model->bkgInvoice->bkg_base_amount;
		$fromCity		 = $model->bkgFromCity->cty_name;
		$toCity			 = $model->bkgToCity->cty_name;
		$pickupDate		 = Filter::getDateFormatted($model->bkg_pickup_date);
		$startingText	 = '₹' . $baseAmount . ' for ' . $fromCity . ' -> ' . $toCity . ' on ' . $pickupDate;
		$changes		 = 'Last minute deal.' . $startingText . ' Hurry, goto ' . Filter::shortUrl(LeadFollowup::getUnvFollowupURL($bkgId, 'p'));
		$response		 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$number	 = $response->getData()->phone['number'];
			$ext	 = $response->getData()->phone['ext'];
		}
		if ($number != '')
		{
			$sms		 = new Messages();
			$msg		 = $changes;
			$res		 = $sms->sendMessage($ext, $number, $msg, 0);
			$userType	 = SmsLog::Consumers;
			$smsType	 = SmsLog::SMS_UNVERIFIED_FINAL_FOLLOWUP;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $userType, "", $smsType, $refType, $refId, 0);
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Final Followup Request Sent.", UserInfo::model(), BookingLog::SMS_SENT, $oldModel, ["blg_ref_id" => $slgId]);
			}
			echo "Final Followup sms - " . $bkgId;
			echo "\n";
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 
	 * @param integer $ext
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function unverifiedFollowup($ext, $bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0 || $model->bkg_agent_id > 0))
		{
			return false;
		}
		/* @var $model Booking */
		$oldModel	 = clone $model;
		$bookingId	 = $model->bkg_booking_id;
		$chargesOff	 = 250;
		$changes	 = 'You have not paid yet & taxi prices are about to go up! Tell us how we can help? ' . Filter::shortUrl(LeadFollowup::getURL($bkgId, 'p'));
		//$changes     = 'The price lock for your quote '.$bookingId.'  is about to expire. Confirm booking before prices rise.' . Filter::shortUrl(BookingUser::getPaymentLink($bkgId, 'p'));
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$number	 = $response->getData()->phone['number'];
			$ext	 = $response->getData()->phone['ext'];
		}

		$number	 = str_replace('-', '', $number);
		$phoneNo = $ext . $number;
		Filter::parsePhoneNumber($phoneNo, $ext, $number);

		if ($number != '' && $ext == '91')
		{
			$sms		 = new Messages();
			$msg		 = $changes . ' - Gozocabs';
			$res		 = $sms->sendMessage($ext, $number, $msg, 0, 1, self::DLT_BOOK_NOT_PAID_TEMPID);
			$userType	 = SmsLog::Consumers;
			$smsType	 = SmsLog::SMS_UNVERIFIED_FOLLOWUP;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $userType, "", $smsType, $refType, $refId, 0);
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Unverified Followup Request Sent.", UserInfo::model(), BookingLog::SMS_SENT, $oldModel, ["blg_ref_id" => $slgId]);
			}
			echo "Unverified Followup sms - " . $bkgId;
			echo "\n";
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 * @param integer $ext
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function leadFollowup($ext, $bkgId)
	{
		/* @var $model Booking */
		$model		 = BookingTemp::model()->findByPk($bkgId);
		$oldModel	 = clone $model;
		$bookingId	 = $model->bkg_booking_id;
		$chargesOff	 = 250;
		//#$changes	 = 'You left your booking incomplete. Tell us how we can help? ' . LeadFollowup::getLeadURL($bkgId, 'p') . ' - Gozocabs';
		$changes	 = "You didn't complete your booking. Tell us why " . Filter::shortUrl(LeadFollowup::getLeadURL($bkgId, 'p')) . ". Use SAVE20 Promo and get discount upto 20% - Gozocabs";
		$number		 = $model->bkg_contact_no;
		if ($number != '')
		{
			$sms		 = new Messages();
			$msg		 = $changes;
			$res		 = $sms->sendMessage($ext, $number, $msg, 0, 1, self::DLT_BOOK_NOT_COMPLETE_TEMPID);
			$userType	 = SmsLog::Consumers;
			$smsType	 = SmsLog::SMS_LEAD_FOLLOWUP;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $userType, "", $smsType, $refType, $refId, 0);
			if ($slgId != '')
			{
				$userInfo		 = UserInfo::getInstance();
				$followStatus	 = $model->bkg_follow_up_status;
				LeadLog::model()->createLog($model->bkg_id, "Automatic Lead followup SMS sent", $userInfo, '', $followStatus, BookingLog::SMS_SENT);
			}
			echo "Lead Followup sms - " . $bkgId;
			echo "\n";
			return true;
		}
		else
		{
			return false;
		}
	}

	public function promotionalCashback($bkgId)
	{
		/* @var $model Booking */
		$model = Booking::model()->findByPk($bkgId);
		if (($model != '' && $model->bkgInvoice->bkg_advance_amount != 0) || $model->bkg_agent_id > 0)
		{
			return false;
		}
		$oldModel	 = clone $model;
		$bookingId	 = $model->bkg_booking_id;
		$hash		 = Yii::app()->shortHash->hash($bkgId);
		//$url		 = 'aaocab.com/bkpn/' . $bkgId . '/' . $hash;
		$url		 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($bkgId);
		$minPay		 = $model->bkgInvoice->calculateMinPayment();
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$number		 = $response->getData()->phone['number'];
			$ext		 = $response->getData()->phone['ext'];
			$userName	 = $response->getData()->phone['userName'];
		}
		$changes = 'Hello ' . $userName . ', your Trip ID ' . $bookingId . ' with Gozo for travel from ' . $model->bkgFromCity->cty_name . ' to ' . $model->bkgToCity->cty_name . ' on ' . DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date) . ' is confirmed. Save up to 50% by paying at least Rs.' . $minPay . ' online. Click on the following link to pay - ' . $url . '. Link will expire soon. T&C apply.';
		if ($number != '' && $ext != '')
		{
			$sms = new Messages();
			$msg = $changes;

			$res		 = $sms->sendMessage($ext, $number, $msg, 0);
			$userType	 = SmsLog::Consumers;
			$smsType	 = SmsLog::SMS_PROMOTIONAL_CASHBCK;
			$refType	 = SmsLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $bookingId, $msg, $res, $userType, "", $smsType, $refType, $refId, 0);
			if ($slgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Promotional Cashback Sent.", UserInfo::model(), BookingLog::SMS_SENT, $oldModel, ["blg_ref_id" => $slgId]);
			}
			return $res;
		}
		else
		{
			return false;
		}
	}

	public function sendIncomingBookingsForNotLoggedIn($ext, $number = '', $changes, $bkg_id, $vendor_id)
	{
		if ($number != '' && $ext != '')
		{
			$sms		 = new Messages();
			$msg		 = $changes;
			$model		 = Booking::model()->findByPk($bkg_id);
			$oldModel	 = clone $model;
			$res		 = $sms->sendMessage($ext, $number, $msg, 0);
			$userType	 = SmsLog::Vendor;
			$smsType	 = SmsLog::SMS_VENDOR_INCOMING_BOOKINGS;
			$refType	 = SmsLog::REF_VENDOR_ID;
			$refId		 = $vendor_id;
			$slgId		 = smsWrapper::createLog($ext, $number, $model->bkg_booking_id, $msg, $res, $userType, "", $smsType, $refType, $refId, 0);
			if ($slgId != '')
			{
				$sub = "Vendor sms notification for incoming bookings";
				BookingLog::model()->createLog($bkg_id, $sub, UserInfo::model(), BookingLog::SMS_SENT, $oldModel, ["blg_ref_id" => $slgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function evolgencemsg($ext, $number, $msg)
	{
		$sms = new Evolgencemsg();
		$res = $sms->sendMessage($ext, $number, $msg);
	}

	public function linkCorporateOTP($country_code, $phone, $otp, $username)
	{
		$sms		 = new Messages();
		//  $msg = 'Your Corporate account verification code is '.$otp.' -Gozocabs.';
		$msg		 = $username . ' wants to register in your Gozo Corporate account. Approval OTP is ' . $otp;
		//  $username.' wants to register in your Gozo Corporate account. Approval OTP is '.$otp.'OR click this link aaocab.com/abc/fhhf to approve';
		$res		 = $sms->sendMessage($country_code, $phone, $msg);
		$usertype	 = SmsLog::Corporate;
		$refId		 = smsWrapper::createLog($country_code, $phone, "", $msg, $res, $usertype, "", '');
		return $res;
	}

	public function linkBookingOTP($country_code, $phone, $otp, $username, $bkgId)
	{
		$sms		 = new Messages();
		$msg		 = "Gozocabs Your Verification Code is: $otp . Please enter it in the space provided in the website.";
		$res		 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype	 = SmsLog::Vendor;
		$refId		 = smsWrapper::createLog($country_code, $phone, $bkgId, $msg, $res, $usertype, "", '', "1", "", 0);
		return $res;
	}

	public function linkDriverOTP($country_code, $phone, $otp, $username)
	{
		$sms		 = new Messages();
		$msg		 = "Gozocabs driver Verification Code is: $otp . Please enter it in the space provided in the website.";
		$res		 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype	 = SmsLog::Driver;
		$refId		 = smsWrapper::createLog($country_code, $phone, "", $msg, $res, $usertype, "", '', "1", "", 0);
		return $res;
	}

	public function sentSmsToForzenVendor($ext, $number, $vendorId, $changes = '', $amt = 0)
	{
		if ($number != '')
		{
			$sms		 = new Messages();
			//$msg = 'PAYMENT OVERDUE. PAY ' . $amt . ' to GOZOCABS ACCT TO CONTINUE RECEIVING BOOKINGS';
			//$msg = 'Payment overdue. Pay ' . $amt . ' to Gozo. Send your payments every week.';
			$amtTxt		 = 'Rs ' . $amt;
			$msg		 = "Payment overdue. Pay $amtTxt to Gozo. Send your payments every week.";
			$res		 = $sms->sendMessage($ext, $number, $msg);
			$usertype	 = SmsLog::Vendor;
			$smstype	 = SmsLog::VENDOR_FROZEN;
			$refId		 = $vendorId;
			$refType	 = SmsLog::REF_VENDOR_ID;
			$slgId		 = smsWrapper::createLog($ext, $number, '', $msg, $res, $usertype, "", $smstype, $refType, $refId);
			if ($slgId != '')
			{
				$event_id = VendorsLog::SMS;
				VendorsLog::model()->createLog($refId, 'Sms sent on Vendor Freeze', UserInfo::getInstance(), $event_id, false, ['vlg_ref_id' => $slgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function informVendorOnBlocknFreezed($ext, $vndnumber, $messageText, $vndname, $vndid, $smstype = SmsLog::VENDOR_BLOCKED)
	{
		$sms		 = new Messages();
		$msg		 = 'Dear ' . $vndname . ', ' . $messageText . '-Gozocabs';
		$res		 = $sms->sendMessage($ext, $vndnumber, $msg);
		$usertype	 = SmsLog::Vendor;

		$refType = SmsLog::REF_VENDOR_ID;
		smsWrapper::createLog($ext, $vndnumber, '', $msg, $res, $usertype, '', $smstype, $refType, $vndid);
	}

	public function paymentSuccessMsgCustomer($bkgId, $amt = 0)
	{
		$msg		 = "Check payment message for booking " . $bkgId;
		$model		 = Booking::model()->findByPk($bkgId);
		$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
		//$paymentLink	 = 'aaocab.com/bkpn/' . $bookingModel->bkg_id . '/' . $hash;
		//$paymentLink = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$link		 = 'http://www.aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$paymentLink = Filter::shortUrl($link);
		$sms		 = new Messages();
		//$msg	 = 'Your payment of Rs.' . round($amt) . '/- on Gozocabs against Booking ID: ' . $model->bkg_booking_id . ' was successful. Please contact us at info@aaocab.com if you have any queries. Gozocabs.';
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$number	 = $response->getData()->phone['number'];
			$ext	 = $response->getData()->phone['ext'];
		}
		#$msg = $model->bkg_booking_id . '| Paid Rs.' . round($amt) . '|  See live updates at ' . $paymentLink;
		if ($model->bkgInvoice->bkg_wallet_used > 0)
		{
			$msg = $model->bkg_booking_id . ' | Deducted Rs.' . round($model->bkgInvoice->bkg_wallet_used) . ' from your Gozo wallet | See cab driver updates at ' . $paymentLink;
		}
		else
		{
			$msg = $model->bkg_booking_id . ' | Received Rs.' . round($model->bkgInvoice->bkg_advance_amount) . ' | See cab driver updates at ' . $paymentLink . ' - Gozocabs';
		}

		if ($model->bkg_agent_id > 0)
		{
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::PAYMENT_CONFIRM);
			$logArr	 = $logArr1['sms'];
			if (count($logArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$countryCode = $value['country_code'];
					$contactNo	 = $value['phone'];
					$isDelay	 = 0;
					$res		 = $sms->sendMessage($countryCode, $contactNo, $msg, $isDelay);
					$slgId		 = smsWrapper::createLog($countryCode, $contactNo, $model->bkg_booking_id, $msg, $res, $usertype, "", SmsLog::SMS_PAYMENT_SUCCESS, SmsLog::REF_BOOKING_ID, $model->bkg_id, $isDelay);
				}
			}
		}
		else if ($number != '' && $ext != '' && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0))
		{
			$isDelay = 0;
			$res	 = $sms->sendMessage($ext, $number, $msg, $isDelay);
			$slgId	 = smsWrapper::createLog($ext, $number, $model->bkg_booking_id, $msg, $res, SmsLog::Consumers, "", SmsLog::SMS_PAYMENT_SUCCESS, SmsLog::REF_BOOKING_ID, $model->bkg_id, $isDelay);
		}

		if ($slgId != '')
		{
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, "Sms sent on Advance Payment.", UserInfo::model(), BookingLog::SMS_SENT, false, $params);
		}

		return $res;
	}

//    public static function sendMessage($ext, $number, $message, $bookingID) {
//        $sms = new send_sms();
//        $sms->sendMessage(array('number' => $number, 'msg' => $message));
//        $class = "smsWrapper";
//        $class::updateSmsInSystem($bookingID, $message, "OM", $number);
//    }


	public function matchOTP($phone, $drvName, $bkgid, $bkid = '', $otp = '')
	{

		$country_code	 = 91;
		$sms			 = new Messages();
		$msg			 = "OTP verified successfully for $bkgid. You can now start the trip. ";
		if ($bkid != '' && $otp != '')
		{
			$msg .= "To stop the trip SMS 'STOP " . $bkid . ' ' . $otp . " <ODOMETER>' TO 8340000181 ";
		}
		$msg		 .= '- Gozocabs';
		$res		 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype	 = SmsLog::Corporate;
		$refId		 = smsWrapper::createLog($country_code, $phone, $bkgid, $msg, $res, $usertype, "", '', '', '', 0);
		return $refId;
	}

	public function informDriverInvalidSMSFormat($phone)
	{

		$country_code	 = 91;
		$sms			 = new Messages();
		$msg			 = "Dear Driver, please send SMS in correct format. ie. 'START <LAST 7 DIGIT BOOKING ID> <OTP>'. - Gozocabs";
		$res			 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype		 = SmsLog::Corporate;
		$refId			 = smsWrapper::createLog($country_code, $phone, '', $msg, $res, $usertype, "", '', '', '', 0);
		return $refId;
	}

	public function informDriverWrongBookingidinSMS($phone)
	{

		$country_code	 = 91;
		$sms			 = new Messages();
		$msg			 = "Dear Driver, booking id sent is incorrect. Please send last 6 digit of your booking id in valid format. ie. 'START <LAST 6 DIGIT BOOKING ID> <OTP>' . - Gozocabs";
		$res			 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype		 = SmsLog::Corporate;
		$refId			 = smsWrapper::createLog($country_code, $phone, '', $msg, $res, $usertype, "", '', '', '', 0);
		return $refId;
	}

	public function informDriverInvalidOTP($phone, $drvName, $bkgid)
	{
		$country_code	 = 91;
		$sms			 = new Messages();
		$msg			 = "Dear $drvName, OTP is not verified for your booking $bkgid. Send correct OTP in valid format to start the trip - Gozocabs";
		$res			 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype		 = SmsLog::Corporate;
		$refId			 = smsWrapper::createLog($country_code, $phone, $bkgid, $msg, $res, $usertype, "", '', '', '', 0);
		return $refId;
	}

	public function informDriverInvalidStopOTP($phone, $drvName, $bkgid)
	{
		$country_code	 = 91;
		$sms			 = new Messages();
		$msg			 = "Dear $drvName, OTP is not verified for your booking $bkgid. Send correct OTP in valid format to stop the trip - Gozocabs";
		$res			 = $sms->sendMessage($country_code, $phone, $msg, 0);
		$usertype		 = SmsLog::Corporate;
		$refId			 = smsWrapper::createLog($country_code, $phone, $bkgid, $msg, $res, $usertype, "", '', '', '', 0);
		return $refId;
	}

	public function sendSMStoVendors($phone, $msg, $isDelay = 1, $bkgId = '')
	{
		$country_code	 = 91;
		$sms			 = new Messages();
		$res			 = $sms->sendMessage($country_code, $phone, $msg, $isDelay, Messages::MTYPE_ENGLISH, '', 4);
		$usertype		 = SmsLog::Vendor;
		$refId			 = smsWrapper::createLog($country_code, $phone, $bkgId, $msg, $res, $usertype, "", '', '', '', $isDelay);
		return $refId;
	}

	public function sendSMStoDrivers($phone, $msg)
	{
		$isDelay		 = 0;
		$country_code	 = 91;
		$sms			 = new Messages();
		$res			 = $sms->sendMessage($country_code, $phone, $msg, $isDelay);
		$usertype		 = SmsLog::Driver;
		$refId			 = smsWrapper::createLog($country_code, $phone, '', $msg, $res, $usertype, "", '', '', '', $isDelay);
		return $refId;
	}

	public static function sendVerificationlLinkAgent($ext, $number, $vCode)
	{

		$sms		 = new Messages();
		$msg		 = 'Your code is ' . $vCode . '. Use this code to link your acount with other agent.';
		$res		 = $sms->sendMessage($ext, $number, $msg);
		$usertype	 = SmsLog::Agent;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public function advanceReminderSMS($country_code, $phone, $bookingId, $bkg_id, $msg)
	{
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($country_code, $phone, $msg);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = smsWrapper::createLog($country_code, $phone, $bookingId, $msg, $res, $usertype, "", '', $refType, $bkg_id);
		if ($refId != '')
		{
			$desc					 = "Payment Reminder SMS sent";
			$eventId				 = BookingLog::SMS_SENT;
			$params					 = [];
			$params['blg_ref_id']	 = $refId;
			BookingLog::model()->createLog($bkg_id, $desc, UserInfo::model(), $eventId, false, $params);
		}
		return $refId;
	}

	public function sendMessagesOnEditBooking($bkgId, $getOldDifference, $oldData, $newData, $oldModel)
	{
		$model = Booking::model()->findByPk($bkgId);
		try
		{
			$isRealtedBooking						 = $model->findRelatedBooking($model->bkg_id);
			$model->bkgTrail->bkg_is_related_booking = ($isRealtedBooking) ? 1 : 0;
			$getDifference							 = array_diff_assoc($newData, $oldData);
			$changesForConsumer						 = $model->getModificationMSG($getDifference, 'consumer');
			$changesForVendor						 = $model->getModificationMSG($getDifference, 'vendor');
			$changesForDriver						 = $model->getModificationMSG($getDifference, 'driver');
			$response								 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
			if ($response->getStatus())
			{
				$phone	 = $response->getData()->phone['number'];
				$ext	 = $response->getData()->phone['ext'];
			}
			$bookingID	 = $model->bkg_booking_id;
			$cModel		 = $model->bkgBcb;
			$vndModel	 = Vendors::model()->findByPk($cModel->bcbVendor->vnd_id);
			$contactId	 = ContactProfile::getByEntityId($vndModel->vnd_id, UserInfo::TYPE_VENDOR);
			if (!$contactId)
			{
				$contactId = $vndModel->vnd_contact_id;
			}
			$number = ContactPhone::getContactPhoneById($contactId);

			if (($model->bkg_agent_id == '' || $model->bkg_agent_id == null || $model->bkg_agent_id == 0) && $model->lead_id > 0)
			{
				if ($model->new_remark != '')
				{
					$model->new_remark = $model->new_remark . " . Booking not confirmed due to unverified contact information";
				}
				else
				{
					$model->new_remark = "Booking not confirmed due to unverified contact information";
				}
			}
			if ($model->bkg_id != '' && $model->new_remark != '')
			{
				$desc							 = trim($model->new_remark);
				$userInfo						 = UserInfo::getInstance();
				$eventId						 = BookingLog::REMARKS_ADDED;
				$bkg_status						 = $model->bkg_status;
				$params							 = [];
				$params['blg_booking_status']	 = $bkg_status;
				$params['blg_remark_type']		 = '1';
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
			$cabModel = $model->getBookingCabModel();
			if ($phone != '' && trim($changesForConsumer) != '')
			{
//				$logType = BookingLog::System;
//				$this->informChangesToCustomer($ext, $phone, $bookingID, $changesForConsumer, $logType);
			}
			if ($cabModel->bcb_driver_phone != '' && trim($changesForDriver) != '')
			{
				$logType = BookingLog::System;
				$this->informChangesToDriver('91', $cabModel->bcb_driver_phone, $bookingID, $changesForDriver, $logType);
			}
			if ($number != '' && trim($changesForVendor) != '')
			{
				$logType = BookingLog::System;
				$this->informChangesToVendor('91', $number, $bookingID, $changesForVendor, $logType);
			}
			if ($cabModel->bcb_vendor_id != '' && $model->bkg_status > 2 && trim($changesForVendor) != '')
			{
				$tripStatus		 = $cabModel->getLowestBookingStatusByTrip($cabModel->bcb_id, $cabModel->bcb_pending_status);
				$tripBkgStatus	 = 0;
				if ($tripStatus)
				{
					$tripBkgStatus = $tripStatus;
				}
				$payLoadData = ['tripId' => $cabModel->bcb_id, 'Status' => $tripBkgStatus, 'EventCode' => Booking::CODE_MODIFIED];
				$success	 = AppTokens::model()->notifyVendor($cabModel->bcb_vendor_id, $payLoadData, $changesForVendor, $model->bkg_booking_id . " details has been modified.");
			}
//			if ($model->bkgUserInfo->bkg_user_id != '' && trim($changesForConsumer) != '')
//			{
//				$notificationId	 = substr(round(microtime(true) * 1000), -5);
//				$payLoadData1	 = ['bookingId' => $model->bkg_booking_id, 'EventCode' => Booking::CODE_MODIFIED];
//				$success1		 = AppTokens::model()->notifyConsumer($model->bkgUserInfo->bkg_user_id, $payLoadData1, $notificationId, $changesForConsumer, $model->bkg_booking_id . " details has been modified.");
//			}

			return $model->new_remark;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public static function informAddressChangesToFlexxiFS($ext, $number, $bkgId, $changes = '', $logType = '')
	{
		$bookingModel = Booking::model()->findByPk($bkgId);
		if ($bookingModel != '' && ($bookingModel->bkgPref->bkg_blocked_msg == 1 || $bookingModel->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		/* @var $bookingModel Booking */
		$sms = new Messages();
		$msg = 'For your Booking ID: ' . $bookingModel->bkg_booking_id . ', be ready at pickup point, ' . $bookingModel->bkg_pickup_address . ' at ' . DateTimeFormat::DateTimeToDatePicker($bookingModel->bkg_pickup_date) . " " . DateTimeFormat::DateTimeToTimePicker($bookingModel->bkg_pickup_date) . '. Do not be late. Car leaves in exactly 15 mins.';

		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = $bookingModel->bkg_id;

		$res	 = $sms->sendMessage($ext, $number, $msg, 0);
		$slgId	 = smsWrapper::createLog($ext, $number, $bookingModel->bkg_booking_id, $msg, $res, $usertype, '', '', $refType, $refId);

		//booking log
		if ($slgId != '' && $slgId != '')
		{
			$desc							 = "SMS sent to flexxi subscriber for setting pickup address.";
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $bookingModel;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $bookingModel->bkg_status;
			BookingLog::model()->createLog($bookingModel->bkg_id, $desc, UserInfo::getInstance(), $eventId, $oldModel, $params);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function sentBonusToDriver($ext, $driverId, $bonus, $bkgId)
	{
		$model		 = Drivers::model()->findByPk($driverId);
		$getbkgid	 = Booking::model()->findByPk($bkgId);
		$bookingid	 = $getbkgid->bkg_booking_id;
		$contactid	 = ContactProfile::getByDrvId($driverId);
		$number		 = ContactPhone::model()->getContactPhoneById($contactid);
		//$number		 = $model->drv_phone;
		if ($number != '')
		{
			$sms	 = new Messages();
			$msg	 = 'You have received Rs. ' . $bonus . ' bonus for 5star review on your account. Your current bonus balance is Rs. ' . $bonus . ' Provide your bank account to Gozo in Gozo Driver app to receive your bonus payment';
			$res	 = $sms->sendMessage($ext, $number, $msg);
			$refType = SmsLog::REF_DRIVER_ID;
			$refId	 = $driverId;
			$slgId	 = smsWrapper::createLog($ext, $number, $bookingid, $msg, $res, SmsLog::Driver, "", SmsLog::SMS_DRIVER_BONUS, $refType, $refId);
			return $slgId;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function for sending SMS to unregistered vendors
	 * @param $objBooking
	 * @param $uoId
	 * @param $uoPhone
	 * @param $modelBuv
	 */
	public function sendSMSToUnregisteredVendors($objBooking, $uoId, $uoPhone, $buvId, $ext)
	{
		/* @var $objBooking Booking */
		//$flgSMSSend = false;
		$slgId = 0;
		if ($objBooking && $uoPhone != '' && $uoId > 0)
		{
			$ext			 = ($ext != '') ? $ext : 91;
			$bookingID		 = $objBooking->bkg_booking_id;
			$fromCity		 = $objBooking->bkgFromCity->cty_name;
			$toCity			 = $objBooking->bkgToCity->cty_name;
			$cabType		 = VehicleCategory::model()->getCabByBkgId($objBooking->bkg_id);
			$refType		 = 1;
			$refId			 = $objBooking->bkg_id;
			$url			 = 'gozo.cab/get/' . Yii::app()->shortHash->hash($buvId) . " ";
			$unsubscribeUrl	 = 'gozo.cab/DND/' . Yii::app()->shortHash->hash($uoId) . " ";
			$msg			 = '' . $cabType . ' required ' . $fromCity . ' to ' . $toCity . '. Tell your price at ' . $url . '. To unsubscribe go to ' . $unsubscribeUrl . '.';
			if ($uoPhone != '')
			{
				$delayTime	 = 1;
				$sms		 = new Messages();
				$res		 = $sms->sendMessage($ext, $number, $msg, $delayTime);
				$slgId		 = smsWrapper::createLog($ext, $uoPhone, $bookingID, $msg, $res, SmsLog::UnregVendor, "", SmsLog::SMS_FOR_UNREGISTERED_VENDORS, $refType, $refId, $delayTime);
			}
		}
		return $slgId;
	}

	public function sendLink($vndId, $number, $ext)
	{
		$sms		 = new Messages();
		$vndHash	 = Yii::app()->shortHash->hash($vndId);
		$vendorLink	 = 'http://www.aaocab.com/vndsl/' . $vndId . '/' . $vndHash;
		$ext		 = ($ext != '') ? $ext : 91;
		$msg		 = 'Our Gozo Partner App update requires linking of your Gozo Account with your Social Account (Google or Facebook). Please visit ' . $vendorLink . ' to update and enable your social login to the App. GozoCabs.';
		$res		 = $sms->sendMessage($ext, $number, $msg, 0);
		$usertype	 = SmsLog::Vendor;
		$smstype	 = SmsLog::SMS_VENDOR_ASSIGNED;
		$refId		 = $vndId;
		$refType	 = SmsLog::SMS_FOR_UNREGISTERED_VENDORS;
		$slgId		 = smsWrapper::createLog($ext, $number, '', $msg, $res, $usertype, "", $smstype, $refType, $refId);
	}

	public function sendLinkOtp($vndId, $number, $ext, $otp)
	{
		$sms = new Messages();
		$ext = ($ext != '') ? $ext : 91;
		$msg = 'Please enter this OTP: ' . $otp . ' in your Partner App to attach your social account - Gozocabs';
		$res = $sms->sendMessage($ext, $number, $msg, 0, 1, self::DLT_PARTNER_ATTACH_SOCIAL_OTP_TEMPID);

		$userInfo	 = UserInfo::getInstance();
		$usertype	 = SmsLog::Vendor;
		$smstype	 = SmsLog::SMS_VENDOR_ASSIGNED;
		$refId		 = $vndId;
		$refType	 = SmsLog::SMS_FOR_UNREGISTERED_VENDORS;
		$slgId		 = smsWrapper::createLog($ext, $number, '', $msg, $res, $usertype, '0', $smstype, $refType, $refId);
	}

	public function sendSmsToEmergencyContact($bkgId, $phone, $url)
	{
		$bModel			 = Booking::model()->findByPk($bkgId);
		$bookingId		 = $bModel->bkg_booking_id;
		$oldModel		 = $bModel;
		$isDelay		 = 0;
		$country_code	 = 91;
		$sms			 = new Messages();
		$res			 = $sms->sendMessage($country_code, $phone, $url, $isDelay);
		$usertype		 = SmsLog::Consumers;
		$refId			 = smsWrapper::createLog($country_code, $phone, $bookingId, $url, $res, $usertype, "", '', $oldModel, '', $isDelay);
		return $refId;
	}

	public static function gotCreateQuoteBookingsms($bkgId, $logType = '', $arrConfAgtBook = [])
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$booking_id	 = $model->bkg_booking_id;
		$sms		 = new Messages();
		$hash		 = Yii::app()->shortHash->hash($model->bkg_id);

		//$paymentUrl = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
		$paymentUrl	 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		#$paymentUrl	 = Filter::shortUrl($paymentUrl);
		$msg		 = 'Booking ' . $booking_id . ' quoted. Pay at ' . $paymentUrl . ' to reconfirm.';

		$bookingTrack	 = $model->bkgTrack;
		//user
		$response		 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
		}
		$usertype = SmsLog::Consumers;

		//agent
		$logArr	 = [];
		$smstype = SmsLog::SMS_BOOKING_CREATED;
		$refId	 = $model->bkg_id;
		$refType = SmsLog::REF_BOOKING_ID;

		$res	 = $sms->sendMessage($countryCode, $contactNo, $msg, 0, 1, self::DLT_BOOK_PAY_LINK_TEMPID);
		$slgId	 = smsWrapper::createLog($countryCode, $contactNo, $booking_id, $msg, $res, $usertype, "", $smstype, $refType, $refId, 0);

		//booking log
		if ($slgId != '')
		{
			$desc = "Sms sent on Booking Created.";
			if ($logType != '')
			{
				$userType = $logType;
				switch ($logType)
				{
					case '1':
						$userId	 = $model->bkgUserInfo->bkg_user_id;
						break;
					case '4':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '5':
						$userId	 = UserInfo::getInstance()->getUserId();
						break;
					case '10':
						$userId	 = '0';
						break;
				}
			}
			else
			{
				$userType	 = UserInfo::getInstance()->getUserType();
				$userId		 = UserInfo::getInstance()->getUserId();
			}
			$eventId						 = BookingLog::SMS_SENT;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_ref_id']			 = $slgId;
			$params['blg_booking_status']	 = $model->bkg_status;
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
		}
	}

	static function sendTripOtp($bookingId, $ext, $phoneNumber, $msgOTP, $dltId = '')
	{
		$isDelay	 = 0;
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $phoneNumber, $msgOTP, $isDelay, 1, $dltId);
		$usertype	 = SmsLog::Consumers;
		$slgId		 = smsWrapper::createLog($ext, $phoneNumber, $bookingId, $msgOTP, $res, $usertype, "", '', $oldModel, '', $isDelay);
		return $slgId;
	}

	/**
	 *
	 * @param type $contactId - Phone contact id
	 * @param type $number - Phone number
	 * @param type $sourceType - UserType CONSUMER = 1,VENDOR = 2 ,DRIVER = 3 , ADMIN = 4, AGENT = 5;
	 * @param type $refType - SmsType Consumers= 1,Vendor = 2 ,Driver = 3 , Admin = 4,MeterDown = 5,Agent = 6,Corporate = 7
	 * @param type $refId - SMS ref id
	 * @param type $templateStyle - Phone Template Style - NOTIFY_OLD_CON_TEMPLATE = 1 , NEW = 2, MODIFY TEMPLATE = 4;
	 * @param type $tempPkId - Temporary Contact id
	 * @param type $ext - Country Code
	 * @param type $otp - Phone OTP
	 * @param type $vndId - vendor id
	 * @return \ReturnSet
	 */
	public static function sendOtpForVerification($contactId, $number = '', $sourceType, $refType, $refId, $templateStyle, $tempPkId = 0, $ext, $otp, $vndId = 0)
	{
		$returnset	 = new ReturnSet();
		$cttModel	 = Contact::model()->findByPk($contactId);

		$userName = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		if (!empty($cttModel->ctt_business_name))
		{
			$userName = $cttModel->ctt_business_name;
		}
		try
		{
			if (!$cttModel)
			{
				return $returnset;
			}
			$cttHash			 = Yii::app()->shortHash->hash($contactId);
			$hashOtp			 = Yii::app()->shortHash->hash($otp);
			$templateStyleHash	 = Yii::app()->shortHash->hash($templateStyle);
			$tempPkHash			 = Yii::app()->shortHash->hash($tempPkId);
			$vndIdHash			 = Yii::app()->shortHash->hash($vndId);
			$ext				 = ($ext != '') ? $ext : 91;
			/* if ($vndId)
			  {
			  $url = 'https://aaocab.com' . Yii::app()->createUrl('verifyPhone', ['id' => $cttHash, 'otp' => $hashOtp, 'ts' => $templateStyleHash, 'vnd' => $vndIdHash]);
			  }
			  else
			  {
			  $url = 'https://aaocab.com' . Yii::app()->createUrl('verifyPhone', ['id' => $cttHash, 'otp' => $hashOtp, 'ts' => $templateStyleHash]);
			  } */

			$arrUrlParams = ['id' => $cttHash, 'otp' => $hashOtp, 'ts' => $templateStyleHash];
			if ($vndId)
			{
				$arrUrlParams['vnd'] = $vndIdHash;
			}

			$url = 'http://www.aaocab.com' . Yii::app()->createUrl('verifyPhone', $arrUrlParams);

			$dltId = '';
			switch ($templateStyle)
			{
				case Contact::NOTIFY_OLD_CON_TEMPLATE:
					$tempPkId		 = Yii::app()->shortHash->hash($tempPkId);
					$vendorModel	 = Vendors::model()->findByPk(UserInfo::getEntityId());
					$vndArry		 = explode('_', $vendorModel->vnd_name);
					$vndName		 = $vndArry[0];
					$vndHash		 = base64_encode($vndName);
					$templateStyle	 = Contact::NOTIFY_OLD_CON_TEMPLATE;
					$userType		 = ($userType == '2') ? 'Vendor' : 'Driver';
					$url			 = 'http://www.aaocab.com' . Yii::app()->createUrl('verifyPhone', ['id' => $cttHash, 'otp' => $hashOtp, 'ts' => $templateStyleHash, 'tpk' => $tempPkHash, 'v' => $vndHash]);
					$msg			 = 'Dear' . ' ' . $userName . '' .
							' Your Phone number ' . $ext . '' . $number . ' is being added by ' . $vndName . ' as a' . $userType . ' to Gozo Cabs' .
							' To allow click here >>  : ' . $url . '- Gozocabs';

					break;
				case Contact::NEW_CON_TEMPLATE:
					$msg	 = 'Dear' . ' ' . $userName . '' .
							' Please click on this : ' . $url . ' to confirm your phone number. Once complete, you can use the phone to activate your account. - Gozocabs';
					$dltId	 = self::DLT_VERIFY_PHONE_LINK_TEMPID;
					break;
				case Contact::MODIFY_CON_TEMPLATE:
					$numHash = base64_encode($number);
					$url	 = 'http://www.aaocab.com' . Yii::app()->createUrl('verifyPhone', ['id' => $cttHash, 'otp' => $hashOtp, 'ts' => $templateStyleHash, 'num' => $numHash]);
					$msg	 = 'Dear' . ' ' . $userName . '' .
							' Please click on this :' . $url . ' to modify your phone number. - Gozocabs';
					break;
				default:
					break;
			}
			if ($number != '')
			{

				$sms	 = new Messages();
				$res	 = $sms->sendMessage($ext, $number, $msg, 0, 1, $dltId);
				//$smsType = SmsLog::SMS_VENDOR_ASSIGNED;
				$refId	 = $contactId;
				$slgId	 = smsWrapper::createLog($ext, $number, '', $msg, $res, $sourceType, "", "", $refType, $refId, 0);
				if ($slgId)
				{
					$returnset->setStatus(true);
				}
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->addError($e->getMessage());
		}
		return $returnset;
	}

	public static function sendRescheduleTime($bookingId, $ext, $phoneNumber, $msg)
	{
		$isDelay	 = 0;
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $phoneNumber, $msg, $isDelay);
		$usertype	 = SmsLog::Consumers;
		$slgId		 = smsWrapper::createLog($ext, $phoneNumber, $bookingId, $msg, $res, $usertype, "", '', $oldModel, '', $isDelay);
		return $slgId;
	}

	public static function sendResetPasswordLink($userId, $usertype)
	{
		$success = false;
		if (empty($userId))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$contactId = ContactProfile::getByUserId($userId);

		$cttHash		 = Yii::app()->shortHash->hash($contactId);
		$contactEmail	 = ContactEmail::model()->findByConId($contactId);
		$contactPhone	 = ContactPhone::model()->findByConId($contactId);
		$email			 = $contactEmail[0]->eml_email_address;
		$number			 = $contactPhone[0]->phn_phone_no;
		$ext			 = $contactPhone[0]->phn_phone_country_code;
		$numberHash		 = base64_encode($number);
		$useridHash		 = Yii::app()->shortHash->hash($userId);
		$contactModel	 = Contact::model()->findByPk($contactId);
		$usersModel		 = Users::model()->findByPk($userId);
		$userName		 = $contactModel->ctt_first_name;

		$urlHash = trim($useridHash . '_' . $numberHash . "_" . $cttHash);
		$url	 = Yii::app()->createAbsoluteUrl('resetpassword', ['id' => $urlHash]);

		$sms = new Messages();
		$msg = 'Dear ' . $userName . ' Your link is ' . $url . ' for reset password.';

		$res = $sms->sendMessage($ext, $number, $msg, 0);
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype);
		return $res;
	}

	public static function vendorAssignment($ext, $number, $bookingID, $messageText, $vndId)
	{
		$sms		 = new Messages();
		$res		 = $sms->sendMessage($ext, $number, $messageText, 0);
		$usertype	 = SmsLog::Vendor;
		$refType	 = SmsLog::REF_VENDOR_ID;
		$smstype	 = SmsLog::SMS_VENDOR_ASSIGNED;
		$slgId		 = smsWrapper::createLog($ext, $number, $bookingID, $messageText, $res, $usertype, '', $smstype, $refType, $vndId, 0);
		return $slgId;
	}

	/**
	 * This function is used for sending rating link to consumers
	 * @param integer $bkgId
	 * @param string $bookingId
	 * @return bool
	 */
	public static function sendRatingLink($bkgId, $bookingId)
	{
		$status				 = false;
		$uniqueid			 = Booking::model()->generateLinkUniqueid($bkgId);
		$link				 = 'https://aaocab.com/';
		$link				 .= Yii::app()->createUrl('/r/' . $uniqueid);
		$slink				 = Filter::shortUrl($link);
		$dataUser			 = BookingUser::model()->getByBkgId($bkgId);
		$phn_phone_no		 = $dataUser['bkg_contact_no'];
		$phn_country_code	 = $dataUser['bkg_alt_country_code'];
		if (!empty($phn_phone_no) && !empty($phn_country_code))
		{
			$isDelay	 = 0;
			$msg		 = "How was your ride " . $bookingId . ">> " . $slink . " Rate now. You get 100 Gozo coins & we will reward team members who do good - Gozocabs";
			$sms		 = new Messages();
			$res		 = $sms->sendMessage($phn_country_code, $phn_phone_no, $msg, $isDelay);
			$usertype	 = SmsLog::Consumers;
			$slgId		 = smsWrapper::createLog($phn_country_code, $phn_phone_no, $bookingId, $msg, $res, $usertype, "", "", "", "", $isDelay);
			$status		 = ($slgId) ? true : false;
		}
		return $status;
	}

	public function sendQrOtp($qr, $contactNumber, $otp, $name)
	{
		$ext		 = '91';
		$sms		 = new Messages();
		//$msg		 = 'Dear ' . $name . ', ' . 'Use OTP: '.$otp.' for QR code activation, please dont share this OTP ';
		$msg		 = '??raYour OTP for phone number verification is ' . $otp . '';
		$res		 = $sms->sendMessage($ext, $contactNumber, $msg, 0);
		$usertype	 = SmsLog::Agent;
		$slgId		 = smsWrapper::createLog($ext, $contactNumber, '', $msg, $res, $usertype, "", "", "", "", "");
		$status		 = ($slgId) ? true : false;
		return $status;
	}

	/**
	 * 
	 * @param type $ext
	 * @param type $number
	 * @param type $otp
	 * @param type $type SmsLog::SMS_LOGIN_REGISTER || SmsLog::SMS_FORGET_PASSWORD
	 * @param type $isCerf
	 * @param type $platform
	 * @return type
	 */
	public static function sendOtp($ext, $number, $otp, $type, $isCerf = 0, $platform = Booking::Platform_App)
	{
		$cacheOTPKey = "sendOtp::Ctr {$number}";
		$cacheObj	 = Yii::app()->cache->get($cacheOTPKey);
		if ($cacheObj == null)
		{
			$cacheObj = 0;
		}
		else
		{
			$cacheObj++;
//			Logger::trace(session_id());
//			Logger::trace($cacheObj);
//			Logger::warning("Multiple sendOtp tried", true);
			return false;
		}

		if ($GLOBALS["OTPCtr"] == null)
		{
			$GLOBALS["OTPCtr"] = 0;
		}

		if ($GLOBALS["OTPCtr"] > 0)
		{
			Logger::trace("Counter: {$GLOBALS["OTPCtr"]}");
			Logger::warning("multiple otp sent", true);
		}

		//$dltId =  self::DLT_OTP_TEMPID;
		$dltId			 = "";
		$isCerfAllowed	 = Config::get("cerf.int.sms.isAllowed.value");
		$msg			 = $otp . ' is your Gozo OTP for login. Do not share it with anyone.';
		if ($type == SmsLog::SMS_FORGET_PASSWORD)
		{
			//$dltId = self::DLT_OTP_FORGOTPASSWORD;
			$msg = $otp . ' is your Gozo OTP for Forgot Password. Do not share it with anyone - Gozocabs';
		}
		if ($isCerfAllowed && $isCerf)
		{
			$res = SMSCerf::sendMessage($ext, $number, $msg, 0, $platform);
		}
		else
		{
			$sms = new Messages();
			$res = $sms->sendMessage($ext, $number, $msg, 0, 1, $dltId, 4);
		}
		$usertype = SmsLog::Consumers;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype, 0, $type, "1", "", 0);
		Yii::app()->cache->set($cacheOTPKey, $cacheObj, 10, new CacheDependency("CustomLog"));
		return $res;
	}

	public static function gnowOfferReminderSMS($ext, $phone, $bookingId, $bkgId)
	{
		$sms		 = new Messages();
		$isDelay	 = 0;
		$hash		 = Yii::app()->shortHash->hash($bkgId);
		$url		 = 'https://' . Yii::app()->params['host'] . Yii::app()->createUrl('gznow/' . $bkgId . '/' . $hash);
		$url1		 = Filter::shortUrl($url);
		$msg		 = "A new offer found for your booking. Check this link " . $url1;
		$res		 = $sms->sendMessage($ext, $phone, $msg, $isDelay);
		$usertype	 = SmsLog::Consumers;
		$refType	 = SmsLog::REF_BOOKING_ID;
		$refId		 = smsWrapper::createLog($ext, $phone, $bookingId, $msg, $res, $usertype, '', '', $refType, $bkgId, $isDelay);
		if ($refId != '')
		{
			BookingLog::missedGozoNowOfferNotified($bkgId, $refId);
		}
		return $refId;
	}

	public function sendLinkVendor($vndId, $number, $ext, $link)
	{
		$sms		 = new Messages();
		$ext		 = ($ext != '') ? $ext : 91;
		$msg		 = 'Our Gozo Partner App update requires linking of your Gozo Account with your Social Account (Google or Facebook). Please visit ' . $link . ' to update and enable your social login to the App. GozoCabs.';
		$res		 = $sms->sendMessage($ext, $number, $msg, 0);
		$usertype	 = SmsLog::Vendor;
		$smstype	 = SmsLog::SMS_TUTORIAL_LINK;
		$refId		 = $vndId;
		$slgId		 = smsWrapper::createLog($ext, $number, '', $msg, $res, $usertype, "", $smstype, "", $refId);
	}

	public function sendApproveVendor($vndId, $number, $ext)
	{
		$sms		 = new Messages();
		$ext		 = ($ext != '') ? $ext : 91;
		$msg		 = 'Congratulations! Your vendor account is approved - Gozocabs';
		$res		 = $sms->sendMessage($ext, $number, $msg, 0);
		$usertype	 = SmsLog::Vendor;
		$smstype	 = SmsLog::SMS_APPROVE_VENDOR;
		$refId		 = $vndId;
		$slgId		 = smsWrapper::createLog($ext, $number, '', $msg, $res, $usertype, "", $smstype, "", $refId);
	}

	public static function sendSlaveSyncSMS($ext, $phone, $connectionString, $isSlaverRunning, $delayedByTime)
	{
		$returnSet = Yii::app()->cache->get('sendSMS');
		if ($returnSet === false)
		{
			$sendSyncSmsNumber = explode(",", $phone);
			foreach ($sendSyncSmsNumber as $value)
			{
				$sms		 = new Messages();
				$currentDate = date("Y-m-d H:i:s");
				$host		 = explode("=", explode(";", $connectionString)[0])[1];
				$isRunning	 = $isSlaverRunning ? "Yes" : "No";
				$msg		 = 'Database server issue. Date: ' . $currentDate . ', IP:' . $host . ', Down: ' . $isRunning . ', Delay: ' . $delayedByTime . ' Second - Gozocabs';
				$res		 = $sms->sendMessage($ext, $value, $msg, 0);
				$usertype	 = null;
				smsWrapper::createLog($ext, $value, "", $msg, $res, $usertype);
			}
			Yii::app()->cache->set('sendSMS', 1, 3600);
		}
	}

	/**
	 * 
	 * @param type $ext
	 * @param type $number
	 * @param type $otp
	 * @param type $type SmsLog::SMS_LOGIN_REGISTER || SmsLog::SMS_FORGET_PASSWORD
	 * @return type
	 */
	public static function sendOtpWEBOTP($ext, $number, $otp, $type)
	{
		$msg = $otp . ' is your Gozo OTP for login. Do not share it with anyone. 

@www.aaocab.com #' . $otp;
		if ($type == SmsLog::SMS_FORGET_PASSWORD)
		{
			$msg = $otp . ' is your Gozo OTP for Forgot Password. Do not share it with anyone. @www.aaocab.com #' . $otp;
		}
		$isCerfAllowed = Config::get("cerf.int.sms.isAllowed.value");
		if ($isCerfAllowed)
		{
			$res = SMSCerf::sendMessage($ext, $number, $msg, 0);
		}
		else
		{
			$sms = new Messages();
			$res = $sms->sendMessage($ext, $number, $msg, 0, 1, self::DLT_APP_OTP_TEMPID, 4);
		}
		$usertype = SmsLog::Consumers;
		smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype, 0, $type, "1", "", 0);
		return $res;
	}

	public static function sendCancelOTP($ext, $number, $otp, $bkgid, $type)
	{
		$isCerfAllowed = Config::get("cerf.int.sms.isAllowed.value");
		if ($isCerfAllowed)
		{
			$msg = $otp . 'is the OTP for cancel your booking. Do not share it with anyone - Gozocabs';
			$res = SMSCerf::sendMessage($ext, $number, $msg, 0);
		}
		else
		{
			$sms = new Messages();
			$msg = $otp . ' is the OTP for cancel your booking. Do not share it with anyone - Gozocabs';
			$res = $sms->sendMessage($ext, $number, $msg, 0, 1, self::DLT_APP_OTP_TEMPID, 4);
		}
		$usertype = SmsLog::Consumers;
		smsWrapper::createLog($ext, $number, $bkgid, $msg, $res, $usertype, 0, $type, "1", "", 0);
		return $res;
	}

	public static function setSmsParams($ext, $number, $bkgid = "", $usertype, $counter = "", $smstype = "", $refType = "1", $refId = "", $delay_time = 1, $dltId = '', $provider_type = "")
	{
		return array(
			'ext'			 => $ext,
			'number'		 => $number,
			'usertype'		 => $usertype,
			'refType'		 => $refType,
			'smstype'		 => $smstype,
			'bookingID'		 => $bkgid,
			'refId'			 => $refId,
			'counter'		 => $counter,
			'delay_time'	 => $delay_time,
			'dtlId'			 => $dltId,
			'provider_type'	 => $provider_type
		);
	}

	/**
	 * This function is used process SMS  
	 * @param Object $obj
	 * @param string $message
	 * @param Array $contentParams
	 * @param Object $receiverParams
	 * @param Object $eventScheduleParams
	 * @return boolean
	 */
	public static function process($obj, $message, $contentParams = [], $receiverParams = null, $eventScheduleParams = null)
	{
		Logger::trace("SMS message: {$message}");
		$returnSet	 = new ReturnSet();
		$time		 = $eventScheduleParams->event_sequence == TemplateMaster::SEQ_SMS_CODE ? $eventScheduleParams->schedule_time : 0;
		if ($time > 0 && $eventScheduleParams->event_schedule == 1)
		{
			ScheduleEvent::add($eventScheduleParams->ref_id, $eventScheduleParams->ref_type, $eventScheduleParams->event_id, $eventScheduleParams->remarks, $eventScheduleParams->addtional_data, $time, TemplateMaster::SEQ_SMS_CODE);
			$returnSet->setStatus(true);
			$returnSet->setData(['type' => TemplateMaster::SEQ_SMS_CODE]);
		}
		else
		{
			if ($obj->filename != null)
			{
				if (Yii::app() instanceof CConsoleApplication)
				{
					$data = Yii::app()->command->renderFile(Yii::getPathOfAlias("application.components.Event.sms.{$obj->filename}") . ".php", array('id' => $contentParams['primaryId'], 'arrayData' => $contentParams), true);
				}
				else
				{
					$data = Yii::app()->controller->renderFile(Yii::getPathOfAlias("application.components.Event.sms.{$obj->filename}") . ".php", array('id' => $contentParams['primaryId'], 'arrayData' => $contentParams), true);
				}
				$dataObj = json_decode($data);
				if (!$dataObj->status)
				{
					$returnSet->setStatus($dataObj->status);
					$returnSet->setData(['type' => TemplateMaster::SEQ_SMS_CODE]);
					goto skipAll;
				}
				$smsArr		 = (array) $dataObj->data;
				$message	 = $smsArr['content'];
				$obj->dtlId	 = $smsArr['dltId'];
			}
			$smsParams	 = smsWrapper::setSmsParams($receiverParams->ext, $receiverParams->number, $receiverParams->bkg_id, $receiverParams->entity_type, '', $receiverParams->sms_type, $receiverParams->ref_type, $receiverParams->ref_id, 0, $obj->dtlId, $obj->provider_type);
			$sms		 = new Messages();
			$res		 = $sms->sendMessage($smsParams['ext'], $smsParams['number'], $message, 0, Messages::MTYPE_ENGLISH, $smsParams['dtlId'], $smsParams['provider_type']);
			$slgId		 = smsWrapper::createLog($smsParams['ext'], $smsParams['number'], $smsParams['bookingID'], $message, $res, $smsParams['usertype'], $smsParams['counter'], $smsParams['smstype'], $smsParams['refType'], $smsParams['refId'], $smsParams['delay_time']);
			$returnSet->setStatus($slgId > 0 ? true : false);
			$returnSet->setData(['type' => TemplateMaster::SEQ_SMS_CODE, 'id' => $slgId]);
			Logger::trace("SMS returnSet: " . json_encode($returnSet));
		}
		skipAll:
		return $returnSet;
	}

}
