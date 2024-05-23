<?php
try
{
		/*
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			return false;
		}
		$booking_id	 = $model->bkg_booking_id;
		$sms		 = new Messages();
		$hash		 = Yii::app()->shortHash->hash($model->bkg_id);

		//$paymentUrl = 'gozocabs.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
		$paymentUrl	 = 'gozocabs.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
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
		 * 
		 */
		$success = true;
		$bkgId  = trim($id);
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			$success = false;
		}
		if($success==false)
		{
			goto skipAll;
		}
		$bookingId	 = $model->bkg_booking_id;
		$paymentUrl	 = 'gozocabs.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$msg		 = 'Booking ' . $bookingId . ' quoted. Pay at ' . $paymentUrl . ' to reconfirm.';
		$bookingTrack	 = $model->bkgTrack;
		// user
		$response		 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
		}
		else
		{
			goto skipAll;
		}
		$templateName			 = 'quote_booking';
		$arrSmsData['content']	 = $msg;
		echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => $templateName, 'data' => $arrSmsData, 'status' => $success));
} 
catch (Exception $ex) 
{
	skipAll:	
	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}