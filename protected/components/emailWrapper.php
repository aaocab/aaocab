<?php

use components\Event\EventSchedule;

class emailWrapper
{

	public $email_receipient;

	public function confirmBookingEmailByUserId($userId, $bookingId = '', $logType = '')
	{
		/* @var $$usermodel Users */
		$usermodel	 = Users::model()->findByPk($userId);
		$email		 = ContactEmail::model()->getEmailByUserId($userId);
		$fName		 = $usermodel->usr_name;
		if ($bookingId != '')
		{
			$bookModel		 = Booking::model()->getBkgIdByBookingId($bookingId);
			$bookPrefModel	 = BookingPref::model()->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $bookModel->bkg_id]);
			$refId			 = $bookModel->bkg_id;
			$refType		 = EmailLog::REF_BOOKING_ID;
		}
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		if ((!$usermodel && ($bookPrefModel->bkg_blocked_msg == 1 || $bookPrefModel->bkg_send_email == 0)) || $bookModel->bkg_agent_id > 0)
		{
			return false;
		}
		//$email = $usermodel->usr_email;
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			// Sent Email to customer for LINK
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('signuplink');
			$bookarr['userName']	 = $fName;
			$bookarr['vlink']		 = Filter::shortUrl(Yii::app()->createAbsoluteUrl('users/confirmsignup', ['id' => $usermodel->user_id, 'hash' => Yii::app()->shortHash->hash($usermodel->user_id)]));
			$mail->setData(array('arr' => $bookarr));
			$mail->setLayout('mail');
			$mail->setTo($email, $fName);
			$subject				 = 'Welcome to aaocab.';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refId		 = '';
			$refType	 = '';

			$elgId = emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered, EmailLog::EMAIl_USER_ACCOUNT_CONFIRM, EmailLog::REF_BOOKING_ID, $bookModel->bkg_id, EmailLog::SEND_SERVICE_EMAIL);
			if ($elgId != '')
			{
				$desc		 = "Email sent for User Account Confirmation.";
				$eventId	 = BookingLog::EMAIL_SENT;
				$oldModel	 = '';
				if ($bookingId != '')
				{
					$oldModel = clone $bookModel;
				}
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $bookModel->bkg_status;
				BookingLog::model()->createLog($bookModel->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
		}
	}

	public function confirmUserAccount($userId)
	{
		$usermodel	 = Users::model()->findByPk($userId);
		$fName		 = $usermodel->usr_name;
		$email		 = ContactEmail::model()->getEmailByUserId($userId);

		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		//$email = $usermodel->usr_email;
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('signuplink');
			$arrInfo['userName']	 = $fName;
			$arrInfo['vlink']		 = Yii::app()->createAbsoluteUrl('users/confirmsignup', ['id' => $usermodel->user_id, 'hash' => Yii::app()->shortHash->hash($usermodel->user_id)]);
			$mail->setData(array('arr' => $arrInfo));
			$mail->setLayout('mail');
			$mail->setTo($email, $fName);
			$subject				 = 'Welcome to aaocab.';
			$mail->setSubject($subject);
			if ($mail->sendMail(1))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refId		 = '';
			$refType	 = '';
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered, EmailLog::EMAIl_USER_ACCOUNT_CONFIRM, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	public function getAgentPassChange($agent_id, $password)
	{

		$model		 = Agents::model()->findByPk($agent_id);
		$agent_id	 = $model->agt_id;
		$email		 = $model->agt_email;

		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		$referalCode			 = $model->agt_referral_code;
		$this->email_receipient	 = $email;
		$agentEmail				 = $model->agt_email;
		$agentName				 = ucfirst($model->agt_owner_name);
		//$link = Yii::app()->createAbsoluteUrl('index/activate', array('id' => $agent_id, 'key' => $key));
		$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
		//$mail->setView('fmail');
		$mail->setData(
				array(
					'email' => $email
				)
		);

		$mail->setLayout('mail');
		//$mail->setFrom('jsrankesh.singh@gmail.com', 'Info aaocab');
		$mail->setTo($email, $agentEmail);
		$mail->setSubject('Welcome to the Gozo B2B travel network');

		$body = 'Dear ' . $agentName . ',<br/>
                Thanks for joining the Gozo sales family. Your application is being considered for a "Authorized Reseller" status. <br/>
                You can start creating bookings immediately.<br/>
                We have created a username and password for you <br/><br/>

                <br/>Your username: ' . $agentEmail . '
                <br/>Your temporary password: ' . $password . '
                <br/>Your Agent Referral code: ' . $referalCode . '
                <br/>
                <br/><b>Next steps -</b>
                <br/>1. Go to the <a href="http://www.aaocab.com/agent">Gozo Travel parnter portal</a>
                <br/>2. Use your username and password to login
                <br/>3. Goto Partner profile and update your information.
                <br/>4. You can start to create bookings.
                <br/>If you have any questions or have any special requests please email us directly at channel@aaocab.in
                <br/><br/>Regards,
                <br/>Gozo B2B sales
                ';

		$mail->setBody($body);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$subject	 = 'Welcome to the Gozo B2B travel network';
		$usertype	 = EmailLog::Agent;
		emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, EmailLog::EMAIL_AGENT_FORGOT_PASS, EmailLog::REF_AGENT_ID, $agent_id, EmailLog::SEND_ACCOUNT_EMAIL, 0);
	}

	public function sendVerificationAgent($email, $code)
	{
		$this->email_receipient	 = $email;
		//if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		//{
		//    return false;
		//}
		$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
		$mail->setData(
				array(
					'email' => $email
				)
		);
		$mail->setLayout('mail');
		$mail->setTo($email);
		$mail->setSubject('Verification Code');
		$body					 = 'Here is the verification code you requested.<br/><br/>
                ' . $code;
		$mail->setBody($body);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$subject	 = 'Verification Code';
		$usertype	 = EmailLog::Agent;
		emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL, 0);
	}

	public function gotBookingemail($bkgid, $logType = '', $agent_id = '', $only_corporate = '', $arrAgtConfirm = [])
	{
		Logger::create("Agent booking assignment test 3:\t" . $bkgid . "agent_id" . $agent_id, CLogger::LEVEL_PROFILE);
		$model		 = Booking::model()->findByPk($bkgid);
		$userInfo	 = UserInfo::getInstance();

		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0) || ($model->bkgInvoice->bkg_advance_amount > 0 && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)))
		{
			return false;
		}

		/* @var $model Booking */
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		Logger::create("Agent booking email content check" . $email, CLogger::LEVEL_PROFILE);
		$usertype	 = EmailLog::Consumers;
		$username	 = $model->bkgUserInfo->getUsername();

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
			Logger::create("Agent booking email test 1:\t" . "agent_id" . $model->bkg_agent_id, CLogger::LEVEL_PROFILE);
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				Logger::create("Agent booking email test 2:\t" . "email array value==>" . $value['name'], CLogger::LEVEL_PROFILE);
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
		}
		Logger::create("Agent booking email array" . $emailArr[$value['email']], CLogger::LEVEL_PROFILE);
		$resheduledMsg = "";
		if ($model->bkgPref->bpr_rescheduled_from > 0)
		{
			$oldModel = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
			if (in_array($oldModel->bkg_status, [9, 10]) && in_array($model->bkg_status, [1, 15, 2]))
			{
				$resheduledMsg = "Booking Resheduled From Booking Id: " . $oldModel->bkg_booking_id;
			}
		}
		if ($email != '' || count($emailArr) > 0)
		{
			Logger::create("Agent booking email test 2:\t" . "agent_id" . $model->bkg_agent_id, CLogger::LEVEL_PROFILE);
			//$bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
			//send mail
			$hash	 = Yii::app()->shortHash->hash($bkgid);
			//$payurl	 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bkgid, 'hash' => $hash]);
			$payurl	 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			$payurl	 = Filter::shortUrl($payurl);
			if (count($emailArr) > 0)
			{
				Logger::create("Agent booking count email", CLogger::LEVEL_PROFILE);
				foreach ($logArr as $key => $value)
				{
					$email = $value['email'];
					if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
					{
						continue;
					}
					$username				 = $value['name'];
					$this->email_receipient	 = $email;
					$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
					$mail->setTo($email, $username);
					$mail->setView('gotbooking_agent');
					$mail->setData(
							array('model' => $model, 'payurl' => $payurl, 'otp' => $model->bkgTrack->bkg_trip_otp, 'resheduledMsg' => $resheduledMsg)
					);
					$mail->setLayout('mail1');
					$subject				 = 'Reservation received – Booking ID : ' . $model->bkg_booking_id;
					$eventId				 = EmailLog::EMAIL_BOOKING_CREATED;

					$mail->setSubject($subject);
					Logger::create("Agent booking check email", CLogger::LEVEL_PROFILE);
					if ($mail->sendMail(1))
					{
						Logger::create("Agent booking email sent successfully :\t" . "subject->" . $subject, CLogger::LEVEL_PROFILE);
						$delivered = "Email sent successfully";
					}
					else
					{
						Logger::create("Agent booking email not sent:\t" . "subject->" . $subject, CLogger::LEVEL_PROFILE);
						$delivered = "Email not sent";
					}
					//email log
					$body		 = $mail->Body;
					$refType	 = EmailLog::REF_BOOKING_ID;
					$refId		 = $model->bkg_id;
					$usertype	 = $key;
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, 1);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				QrCode::processData($model->bkgUserInfo->bkg_user_id);
				$this->email_receipient = $email;
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$mail				 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
				$mail->setTo($email, $username);
				//------------------------------------------------------------
				$luggageCapacity	 = Stub\common\LuggageCapacity::init($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id, $model->bkgAddInfo->bkg_no_person);
				$workingHrdiff		 = Filter::CalcWorkingHour($model->bkg_create_date, $model->bkg_pickup_date);
				$cancelTimes_new	 = CancellationPolicy::initiateRequest($model);
				$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
				$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
				$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
				$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS); //print_r($othertermsPoints);
				$arr				 = array('luggageCapacity'		 => $luggageCapacity,
					'refCodeUrl'			 => $refCodeUrl, 'model'					 => $model,
					'payurl'				 => $payurl, 'email_receipient'		 => $email, 'userId'				 => $model->bkgUserInfo->bkg_user_id,
					'otp'					 => $model->bkgTrack->bkg_trip_otp,
					'date'					 => $date . ' ' . $time, 'timediff'				 => $workingHrdiff,
					'cancelTimes_new'		 => $cancelTimes_new, "cancellationPoints"	 => $cancellationPoints,
					'dosdontsPoints'		 => $dosdontsPoints, 'boardingcheckPoints'	 => $boardingcheckPoints,
					'othertermsPoints'		 => $othertermsPoints, 'prarr'					 => $priceRule->attributes, 'resheduledMsg'			 => $resheduledMsg);
				//-------------------------------------------
				$mail				 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
				$mail->setTo($email, $username);
				//	$mail->setView('gotbookingrenew');
				$mail->setView('gotbookingrenew_new');
				//---------------------------------------------------------------------
				/* 	$mail->setData(
				  array('luggageCapacity'=>$luggageCapacity,'refCodeUrl' => $refCodeUrl, 'model' => $model,
				 *  'payurl' => $payurl, 'email_receipient' => $email, 'otp' => $model->bkgTrack->bkg_trip_otp, 'date' => $date . ' ' . $time, 'timediff' => $workingHrdiff)
				  ); */
				//$mail->setView('gotbookingrenew');
				/* 	$mail->setData(
				  array('luggageCapacity' => $luggageCapacity, 'model' => $model, 'payurl' => $payurl, 'email_receipient' => $email, 'otp' => $model->bkgTrack->bkg_trip_otp, 'timediff' => $workingHrdiff)
				  ); */
				$mail->setData($arr);
				$mail->setLayout('mail1');
				$subject			 = 'Reservation received – Booking ID : ' . $model->bkg_booking_id;
				$eventId			 = EmailLog::EMAIL_BOOKING_CREATED;

				$mail->setSubject($subject);
				if ($mail->sendMail(1))
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
				//email log
				$body	 = $mail->Body;
				$refType = EmailLog::REF_BOOKING_ID;
				$refId	 = $model->bkg_id;
				$elgId	 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog:: SEND_SERVICE_EMAIL, 1);
			}

			//booking log
			if ($model->bkg_id != '')
			{
				$desc = "Email sent on Booking Created.";

				$eventId						 = BookingLog::EMAIL_SENT;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				if (count($emailArr) > 0 && $model->bkg_agent_id > 0)
				{
					BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
				}
				else if ($email != '' && $model->bkg_agent_id == '')
				{
					BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
				}
			}
		}
	}

	public function gotBookingemailCpaa($userData, $bkgid, $logType = '', $agent_id = '', $only_corporate = '', $arrAgtConfirm = [])
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$userInfo	 = UserInfo::getInstance();
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0) || ($model->bkgInvoice->bkg_advance_amount > 0 && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)))
		{
			return false;
		}
		/* @var $model Booking */
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
		if ($response->getStatus())
		{
			$email		 = $response->getData()->email['email'];
			$contactNo	 = $response->getData()->phone['number'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		$usertype	 = EmailLog::Consumers;
		$username	 = $userData->getUsername();
		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
			//$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO);			
			$logArr1['email'][0] = $email;
			$logArr1['sms'][0]	 = $contactNo;
			$logArr				 = $logArr1['email'];

			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
		}
		if ($email != '' || count($emailArr) > 0)
		{
			//send mail
			$hash	 = Yii::app()->shortHash->hash($bkgid);
			//$payurl	 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bkgid, 'hash' => $hash]);
			$payurl	 = BookingUser::getPaymentLinkByEmail($bkgid);
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$email = $value['email'];
					if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
					{
						continue;
					}
					$username				 = $value['name'];
					$this->email_receipient	 = $email;
					$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
					$mail->setTo($email, $username);
					$mail->setView('gotbooking_agent');
					$mail->setData(
							array('model' => $model, 'payurl' => $payurl, 'otp' => $model->bkgTrack->bkg_trip_otp)
					);
					$mail->setLayout('mail1');
					$subject				 = 'Reservation received – Booking ID : ' . $model->bkg_booking_id;
					$eventId				 = EmailLog::EMAIL_BOOKING_CREATED;
					$mail->setSubject($subject);
					if ($mail->sendMail(1))
					{
						$delivered = "Email sent successfully";
					}
					else
					{
						$delivered = "Email not sent";
					}
					//email log	
					$body		 = $mail->Body;
					$refType	 = EmailLog::REF_BOOKING_ID;
					$refId		 = $model->bkg_id;
					$usertype	 = $key;
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				}
			}
		}
	}

	/**
	 * 
	 * @param integer $bkgid
	 * @param integer $logType
	 * @param integer $resend
	 * @return boolean
	 */
	public static function confirmBooking($bkgid, $logType = '', $resend = 0)
	{
		$model = Booking::model()->findByPk($bkgid);
		if ($model->bkg_reconfirm_flag != 1 || !in_array($model->bkg_status, [2, 3, 5]))
		{
			return false;
		}
		$isConfirm = EmailLog::checkBookingConfirmed($bkgid);
		if ($model != '' && (($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0 || $isConfirm == 1) && $resend == 0))
		{
			return false;
		}

		// Structured Markup Data
		$arrStructureMarkupData	 = Booking::model()->getStructMarkupForBookingConfirmation($model);
		$jsonStructureMarkupData = json_encode($arrStructureMarkupData, JSON_UNESCAPED_SLASHES);

		//QrCode::processData($model->bkgUserInfo->bkg_user_id);

		$oldModel	 = clone $model;
		$usertype	 = EmailLog::Consumers;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
//remove comment when agent panel notificaion will be live

			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::PAYMENT_CONFIRM);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
// remove comment when agent panel notificaion will be live
		}
		if ($model->bkg_agent_id == null || $model->bkg_agent_id == '1249')
		{
			$userId			 = $model->bkgUserInfo->bkg_user_id;
			$uniqueQrCode	 = QrCode::getCode($userId);
			if ($uniqueQrCode == null || $uniqueQrCode == "")
			{
				BookingScheduleEvent::add($bkgid, BookingScheduleEvent::GENERATE_QR_CODE, 'Generate QR Code');
				$contactId = Users::getContactByUserId($userId);
				if ($contactId > 0)
				{
					QrCode::saveCodeById($userId, $contactId);
				}
			}
		}

		if ($email != '' || count($emailArr) > 0)
		{
			$bookingTrack	 = $model->bkgTrack;
			//$this->email_receipient	 = $email;
			$mail			 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
			if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
			{
				$mail->setView('confirmbookingflexxi');
			}
			else
			{
				$mail->setView('confirmbookingrenew');
			}
			$bookarr['userName']				 = $model->bkgUserInfo->getUsername();
			$bookarr['bookingId']				 = $model->bkg_booking_id;
			$bookarr['fromCity']				 = $model->bkgFromCity->cty_name;
			$bookarr['toCity']					 = $model->bkgToCity->cty_name;
			$bookarr['amount']					 = $model->bkgInvoice->bkg_total_amount;
			$bookarr['advance']					 = $model->bkgInvoice->bkg_advance_amount;
			$bookarr['due']						 = $model->bkgInvoice->bkg_due_amount;
			$bookarr['discount']				 = $model->bkgInvoice->bkg_discount_amount;
			$bookarr['pickupAddress']			 = $model->bkg_pickup_address;
			$bookarr['pickupFormattedMonthDate'] = date('jS M Y (l) ', strtotime($model->bkg_pickup_date));
			$bookarr['pickupTime']				 = date('h:i A', strtotime($model->bkg_pickup_date));
			$bookarr['returnDateTimeFormat']	 = date('jS M Y (l) h:i A', strtotime($model->bkg_return_date));
			$bookarr['cabType']					 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ')';
			$bookarr['tripDistance']			 = $model->bkg_trip_distance;
			$bookarr['expTimeCashBack']			 = $model->getExpTimeCashBack();
			$bookarr['primaryPhone']			 = $model->bkgUserInfo->bkg_contact_no;
			$bookarr['rate_per_km']				 = $model->bkgInvoice->bkg_rate_per_km;
			$bookarr['rate_per_km_extra']		 = $model->bkgInvoice->bkg_rate_per_km_extra;
			//	$bookarr['trip_type']				 = $model->bkg_trip_type;
			$bookarr['booking_type']			 = $model->bkg_booking_type;
			//	$bookarr['dropFormattedMonthDate']	 = date('jS M Y (l) ', strtotime($model->bkg_drop_time));
			//	$bookarr['dropTime']				 = date('h:i A', strtotime($model->bkg_drop_time));
			$bookarr['dropFormattedMonthDate']	 = date('jS M Y (l) ', strtotime($model->bkg_return_date));
			$bookarr['dropTime']				 = date('h:i A', strtotime($model->bkg_return_date));
			$bookarr['dropArea']				 = $model->bkg_drop_address;
			$bookarr['creditsused']				 = ($model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
			$bookarr['crpcreditused']			 = ($model->bkgInvoice->bkg_corporate_credit > 0) ? $model->bkgInvoice->bkg_corporate_credit : 0;
			if ($model->bkgInvoice->bkg_convenience_charge > 0)
			{
				$Model11			 = clone $model;
				$Model11->bkgInvoice->calculateConvenienceFee(0);
				$Model11->bkgInvoice->calculateTotal();
				//$Model11->bkgInvoice->populateAmount(false,true,true,false,$model->bkg_agent_id);
				$minpay				 = $Model11->bkgInvoice->calculateMinPayment();
				$maxTimeAdvPay		 = date('d/m/Y h:i A', strtotime($model->bkgTrail->bkg_payment_expiry_time));
				$amountWithoutCOD	 = $Model11->bkgInvoice->bkg_due_amount;
			}
			$bookarr['minpay']			 = $minpay;
			$bookarr['expirytime']		 = $maxTimeAdvPay;
			$bookarr['cod']				 = $model->bkgInvoice->bkg_convenience_charge;
			$bookarr['amountWithoutCOD'] = $amountWithoutCOD;
			$hash						 = Yii::app()->shortHash->hash($model->bkg_id);
			//$url						 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
			//$payurl						 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
//			$payurl						 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			$payurl						 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			$bookarr['payurl']			 = Filter::shortUrl($payurl);
			$refCodeUrl					 = "";
			if ($model->bkgUserInfo->bkg_user_id > 0)
			{
				$returnSet = Users::getReferUrl($model->bkgUserInfo->bkg_user_id);
				if ($returnSet->isSuccess())
				{
					$refCodeUrl = $returnSet->getData()['referUrl'];
				}
			}
			///////////////////////////////////////////
			$cancelTimes_new	 = CancellationPolicy::initiateRequest($model); //print_r($cancelTimes_new);
			//-------------------------------------
			$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
			$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
			$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
			$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS); //print_r($othertermsPoints);
			//print_r($model->bkgSvcClassVhcCat->scv_scc_id);die("dsfdsf");
			$priceRule			 = PriceRule::getByCity($model->bkg_route_city_ids, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_vct_id);
			///////////////////////////////////////////
			$resheduledMsg		 = "";
			if ($model->bkgPref->bpr_rescheduled_from > 0)
			{
				$oldModel		 = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
				$resheduledMsg	 = "Resheduled From Booking Id: " . $oldModel->bkg_booking_id;
				$oldPickupdate	 = date('jS M Y (l) h:i A', strtotime($oldModel->bkg_pickup_date));
				$newPickupdate	 = date('jS M Y (l) h:i A', strtotime($model->bkg_pickup_date));
				$resheduledMsg	 = "Your booking has been rescheduled from {$oldPickupdate} (reference booking id - {$oldModel->bkg_booking_id}) to {$newPickupdate}.";
			}
			$mail->setData(
					array('refCodeUrl'				 => $refCodeUrl, 'model'						 => $model, 'payurl'					 => $payurl,
						'arr'						 => $bookarr, 'otp'						 => $bookingTrack->bkg_trip_otp, 'jsonStructureMarkupData'	 => $jsonStructureMarkupData,
						'cancelTimes_new'			 => $cancelTimes_new, "cancellationPoints"		 => $cancellationPoints,
						'dosdontsPoints'			 => $dosdontsPoints, 'boardingcheckPoints'		 => $boardingcheckPoints,
						'othertermsPoints'			 => $othertermsPoints, 'prarr'						 => $priceRule->attributes,
						'email_receipient'			 => $email, 'userId'					 => $model->bkgUserInfo->bkg_user_id, 'resheduledMsg'				 => $resheduledMsg)
			);
			$mail->setLayout('mail1');
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == 0 || $model->bkg_agent_id == '')
			{
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
			}
			$mail->setSubject('Reservation confirmed – Booking ID : ' . $model->bkg_booking_id);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}

			//email log
			$body	 = $mail->Body;
			$subject = 'Reservation confirmed – Booking ID : ' . $model->bkg_booking_id;
			$refType = EmailLog::REF_BOOKING_ID;
			$refId	 = $model->bkg_id;
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_BOOKING_CONFIRM, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, 0);
				}
			}
			else if ($model->bkg_agent_id == '')
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_BOOKING_CONFIRM, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, 0);
			}
			//booking log
			if ($model->bkg_id != '' && $elgId != '')
			{
				$desc							 = "Email sent on Booking Confirmed.";
				$eventId						 = BookingLog::EMAIL_SENT;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;

				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventId, $oldModel, $params);
			}
		}
	}

	public function cabAssignemail($bkgid, $logType = '')
	{
		$model = Booking::model()->with('bkgFromCity', 'bkgToCity')->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		else if (!$model->bkgTrail->checkCabAssignmentEmailSendEligibility($model))
		{
			return false;
		}

		$cabModel	 = $model->getBookingCabModel();
		$oldModel	 = clone $model;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::CAB_ASSIGNED);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
		}

		if ($email != '' || count($emailArr) > 0)
		{

			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$email					 = $value['email'];
					$username				 = $value['name'];
					$usertype				 = $key;
					$this->email_receipient	 = $email;
					/* @var $model Booking */
					$mail					 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
					$mail->clearView();
					$mail->clearLayout();
					$arr['b2b']				 = false;
					if ($model->bkg_agent_id > 0 && ($usertype == EmailLog::Agent || $usertype == EmailLog::Corporate || $usertype == EmailLog::Consumers))
					{
						$arr['b2b'] = true;
					}
					$content = include Yii::getPathOfAlias("application.views.mail") . '/assignedcab.php';
					ob_start();
					include Yii::getPathOfAlias("application.views.layouts") . '/mail.php';
					$message = ob_get_contents();
					ob_end_clean();
					$mail->setTo($email, $username);

					$subject	 = 'Cab assigned – Booking ID :  ' . $model->bkg_booking_id;
					$mail->setSubject($subject);
					$mail->Body	 = $message;
					if ($mail->sendMail())
					{
						$delivered = "Email sent successfully";
					}
					else
					{
						$delivered = "Email not sent";
					}
					$body	 = $mail->Body;
					$refType = EmailLog::REF_BOOKING_ID;
					$refId	 = $model->bkg_id;

					$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_CAB_ASSIGNED, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$this->email_receipient = $email;
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				/* @var $model Booking */
				$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
				$mail->clearView();
				$mail->clearLayout();
				$content	 = include Yii::getPathOfAlias("application.views.mail") . '/assignedcab.php';
				ob_start();
				include Yii::getPathOfAlias("application.views.layouts") . '/mail.php';
				$message	 = ob_get_contents();
				ob_end_clean();
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
				$subject	 = 'Cab assigned – Booking ID :  ' . $model->bkg_booking_id;
				$mail->setSubject($subject);
				$mail->Body	 = $message;
				if ($mail->sendMail())
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
				//echo "\n delivered====>".$delivered;
				$body		 = $mail->Body;
				$usertype	 = EmailLog::Consumers;
				$refType	 = EmailLog::REF_BOOKING_ID;
				$refId		 = $model->bkg_id;
				$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_CAB_ASSIGNED, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			}

			//booking log
			if ($model->bkg_id != '' && $elgId != '')
			{
				// Updated Booking Trail Flg
				/* $objBookingTrail = BookingTrail::model()->getbyBkgId($bkgid);
				  $objBookingTrail->btr_cab_assigned_sent_email_cnt = $objBookingTrail->btr_cab_assigned_sent_email_cnt + 1;
				  $objBookingTrail->save(); */

				$model->bkgTrail->btr_cab_assigned_sent_email_cnt += 1;
				$model->bkgTrail->save();

				$desc							 = "Email sent for Cab Assigned.";
				$eventId						 = BookingLog::EMAIL_SENT;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
		}
	}

	public function markComplete($bkgid, $logType = '')
	{
		$model = Booking::model()->findByPk($bkgid);

		$isRating = $model->ratings->rtg_id;
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0 || $isRating > 0))
		{
			return false;
		}

		$cabmodel	 = $model->getBookingCabModel();
		$oldModel	 = clone $model;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
			//remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::RATING_AND_REVIEWS);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
			//remove comment when agent panel notificaion will be live
		}

		if ($email != '' || count($emailArr) > 0)
		{
			$bookarr					 = [];
			$bookarr['userName']		 = $model->bkgUserInfo->getUsername();
			$bookarr['bookingId']		 = $model->bkg_booking_id;
			$uniqueid					 = Booking::model()->generateLinkUniqueid($bkgid);
			//$link						 = 'https://gozo.cab/r/' . $uniqueid;
			//$bookarr['reviewlink']		 = Filter::shortUrl($link);
			$link						 = 'https://' . Yii::app()->params['host'] . '/r/' . $uniqueid;
			$bookarr['reviewlink']		 = $link;
			$bookarr['email_receipient'] = $email;
			$bookarr['userId']			 = $model->bkgUserInfo->bkg_user_id;

			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$data					 = $bookarr;
			$content				 = include Yii::getPathOfAlias("application.views.mail") . '/howdidwedo.php';
			ob_start();
			include Yii::getPathOfAlias("application.views.layouts") . '/mail.php';
			$message				 = ob_get_contents();
			ob_end_clean();
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$subject	 = 'Your trip with Gozo. How did we do?';
			$mail->setSubject($subject);
			$mail->Body	 = $message;
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;

			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_MARK_COMPLETE, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_MARK_COMPLETE, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
			}

			//booking log
			if ($model->bkg_id != '' && $elgId != '')
			{
				$desc							 = "Email sent for review request on booking completed.";
				$eventId						 = BookingLog::EMAIL_SENT;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
				$update							 = Booking::model()->countReviewEmail($model->bkg_id);
			}
		}
	}

	public function markCompleteCommand($bkgid)
	{
		$model = Booking::model()->findByPk($bkgid);
		if (($model != '' && $model->bkgPref->bkg_blocked_msg == 1) || $model->bkg_agent_id > 0)
		{
			return;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return false;
		}

		$delivered = '';
		if ($email != '')
		{

			$this->email_receipient	 = $email;
			$uniqueid				 = Booking::model()->generateLinkUniqueid($bkgid);
			//$link					 = 'http://www.aaocab.com/rating/bookingreview?&uniqueid=' . $uniqueid;
			$link					 = 'https://' . Yii::app()->params['host'] . '/r/' . $uniqueid;
			//$bookarr['reviewlink'] = $link;
			//$this->email_receipient = $email;
			//$mail = new YiiMailer();
			$mail					 = new EIMailer();
			$mail->clearView();
			$mail->clearLayout();
			$body					 = 'Dear ' . $model->bkgUserInfo->getUsername() . ',<br/><br/>
            Thanks for traveling with aaocab ( Booking ID :' . $model->bkg_booking_id . '). We would love to hear your feedback on how we did.
            <br/><br/>Please tell us what you think about the car, driver, our customer service and any suggestion you have for us to do better.
            <br/><br/><a href="' . $link . '">' . $link . '</a>
            <br/><br/>Regards,<br/>aaocab Support<br/>+91-90518-77-000';

			$mail->setBody($body);

			//$mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			$subject = 'Your trip with Gozo. How did we do?';
			$mail->setSubject($subject);
//            if ($mail->Send()) {
//                $delivered = "Email sent successfully";
//            }
//            else {
//                $delivered = "Email not sent";
//            }
			echo "\n";
			echo $delivered;

			$usertype = EmailLog::Consumers;

			emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered);

			//  emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered);
		}
	}

	public function remainderBookCab($user_name = '', $user_email = '', $user_id = '')
	{

		if ($user_email <> '')
		{
			$fbLink	 = "<a href='http://www.facebook.com/aaocab'>Like</a>";
			$subject = "Book your Gozo again...";
			$body	 = 'Dear ' . $user_name . ',<br/><br/>
            Thanks for being a Gozo customer. its been over 3 months since your last trip.
            <br/>Dont work so hard, its time to take a vacation again. There is so much of India you can see...
            <br/>We have continued to expand our network and there are so many more cities your Gozo goes to now. Use this Rs. 250/- off coupon before date (+10days)
            <br/><br/>If you can not take a trip send this coupon to a friend to use. If they travel we will give you 250/- for a future trip anyways.
            <br/><br/>We count on you to help spread the word.
            <br/>' . $fbLink . ' us on facebook.
            <br/>Tell your friends about Gozo.';

			$email = $user_email;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_PROMOTIONAL))
			{
				return false;
			}

			$mail	 = new EIMailer();
			//$mail->clearView();
			//$mail->clearLayout();
			$mail->setTo($email);
			$mail->setBody($body);
			$mail->setLayout('mail');
			$mail->setSubject($subject);
			$success = $mail->sendServicesEmail(0);
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_BOOK_GOZO_AGAIN;
			$refType	 = EmailLog::REF_USER_ID;
			$refId		 = $user_id;
			$delay_time	 = 0;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, $delay_time);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function reviewFeedbackemail($bkgid)
	{
		// $model = new Booking();
		$model		 = Booking::model()->findByPk($bkgid);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		if (($model != '' && $model->bkgPref->bkg_blocked_msg == 1) || $model->bkg_agent_id > 0)
		{
			return;
		}
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_RATING))
		{
			return;
		}
		//  $email = 'deepakk.sonthalia@gmail.com';
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		if ($email != '')
		{
			$bookarr				 = [];
			$bookarr['userName']	 = $model->bkgUserInfo->getUsername();
			$bookarr['bookingId']	 = $model->bkg_booking_id;
			$bookingid				 = $model->bkg_booking_id;
			$crdt					 = date('YmdHis', strtotime($model->bkg_create_date));
			$bkgid					 = str_pad($model->bkg_id, 6, 0, STR_PAD_LEFT);
			//$link					 = Yii::app()->createAbsoluteUrl('rating/bookingreview?&uniqueid=' . $bookingid . $crdt . $bkgid);
			$link					 = 'https://' . Yii::app()->params['host'] . '/r/' . $bookingid . $crdt . $bkgid;
			$bookarr['reviewlink']	 = $link;
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('feedbackemail');
			$mail->setData(
					array(
						'arr' => $bookarr,
			));

			$mail->setLayout('mail');
			// $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			$subject = 'Review Request – Booking ID : ' . $model->bkg_booking_id;
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, '', '', EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	public function verificationEmail($bkgid, $logType = '')
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$email	 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		//if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		//{
		//    return false;
		//
		/* @var $model Booking */
		if ($email != '')
		{
			$bookarr			 = [];
			$bookarr['userName'] = $model->bkgUserInfo->getUsername();

			$bookarr['vcode']		 = $model->bkgUserInfo->bkg_verification_code;
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setView('verification');
			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$mail->setLayout('mail');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			$subject				 = 'aaocab -  Reconfirm and make payment for ' . $model->bkg_booking_id;
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_BOOKING_VERIFICATION_CODE, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			if ($model->bkg_id != '')
			{
				$desc = "Email sent on Verification Code - aaocab.";

				$eventId						 = BookingLog::EMAIL_SENT;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
		}
	}

	public function verificationEmail1($bkgid, $logType = '')
	{
		$model		 = Booking::model()->findByPk($bkgid);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		$eHashCode = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);

		//if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		//{
		//    return false;
		//

		/* @var $model Booking */
		if ($email != '')
		{
			$bookarr				 = [];
			$bookarr['userName']	 = $model->bkgUserInfo->getUsername();
			$hash					 = Yii::app()->shortHash->hash($model->bkg_id);
			//   $verifylink = 'aaocab.com/bkver/' . $model->bkg_id . '/' . $hash;
			//if($model->bkgUserInfo->bkg_verifycode_email != '' && $model->bkgUserInfo->bkg_verifycode_email!= NULL){
			//$verifylink				 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash, 'e' => $eHashCode]);
//			}
//			else
//			{
//				$verifylink				 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
//			}
			$verifylink				 = Filter::shortUrl(BookingUser::getPaymentLinkByEmail($model->bkg_id));
			$bookarr['vlink']		 = $verifylink;
			$bookarr['vcode']		 = $model->bkgUserInfo->bkg_verifycode_email;
			$bookarr['bkgId']		 = $model->bkg_booking_id;
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setView('verification');
			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$mail->setLayout('mail');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			$subject				 = 'Gozo Cabs - Reconfirm and make payment for ' . $model->bkg_booking_id . '.';
			$mail->setSubject($subject);
			$status					 = 0;
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered	 = "Email not sent";
				$status		 = 1;
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_BOOKING_VERIFICATION_CODE, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $status);
			if ($model->bkg_id != '')
			{
				$desc = "Email sent on Verification Code - aaocab.";

				$eventId						 = BookingLog::EMAIL_SENT;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
		}
	}

	public function signupEmail($userid)
	{
		$model	 = Users::model()->findByPk($userid);
		/* @var $model Users */
		$email	 = ContactEmail::model()->getEmailByUserId($userid);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		if ($email != '')
		{
			// $email = 'abhishek@epitech.in';
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('signup');
			$bookarr['userName']	 = $model->usr_name . ' ' . $model->usr_lname;
			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$mail->setLayout('mail');
			//  $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $model->usr_name . ' ' . $model->usr_lname);
			$subject				 = 'Welcome to aaocab';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refType	 = EmailLog::REF_USER_ID;
			$refId		 = $model->user_id;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	public function referEmail($fname, $lname, $referTo, $referredBy, $refcode, $amount)
	{
		$model	 = Users::model()->findByPk($referredBy);
		$email	 = $referTo;
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_PROMOTIONAL))
		{
			return false;
		}
		if ($email != '' && $model != '')
		{
			$inviteLink				 = Filter::shortUrl(Yii::app()->createAbsoluteUrl('/invite', ['refcode' => $refcode]));
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setLayout('mail');
			$mail->Html				 = true;

			$mail->Body	 = "Dear Friend,<br><br>I wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service.<br>
aaocab is India’s leader in long distance taxi travel. Please visit <a href=" . $inviteLink . ">" . $inviteLink . "</a> to register and get a credit of 50 points towards your future travel needs.</br></br></br>
Regards,<br>
aaocab Team";
			//$mail->Body = $model->usr_name . ' ' . $model->usr_lname . ' has invited you to join aaocab. Please click on the link below to register and create your Gozo account<br><a href=' . Yii::app()->createAbsoluteUrl('/signup', ['refcode' => $refcode, 'mail' => $email, 'fname' => $fname, 'lname' => $lname]) . '>click here</a> and gain points worth Rs.' . $amount;
			$mail->setTo($email, $fname . " " . $lname);
			$subject	 = 'aaocab';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo $delivered;
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	public function refererCreditedEmail($referer, $referredName, $amount)
	{
		/* @var $model Users */
		$model	 = Users::model()->findByPk($referer);
		$email	 = ContactEmail::model()->getEmailByUserId($referer);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_PROMOTIONAL))
		{
			return false;
		}
		if ($email != '' && $model != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setLayout('mail');
			$mail->Html				 = true;
			$mail->Body				 = 'Congratulations !<br>We are happy to announce that ' . $referredName . ' took his/her first Gozo ride today. Your successful Gozo invite earns you Gozo Coins worth Rs.' . $amount . '. You may now redeem these Gozo Coins against your bookings with us.';
			$mail->setTo($email, $model->usr_name . " " . $model->usr_lname);
			$mail->setData(["email_receipient" => $email, 'userId' => $referer]);
			$subject				 = 'aaocab';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	public function sendPromocode($email, $code, $bkgid, $expdate, $damount)
	{
		$model = Booking::model()->findByPk($bkgid);
		if ($model->bkg_agent_id > 0)
		{
			return false;
		}
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_PROMOTIONAL))
		{
			return false;
		}
		$name = $model->bkgUserInfo->getUsername();
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('promocodetouser');
			$mail->setData(
					array(
						'code'			 => $code,
						'username'		 => $name,
						'expiry_date'	 => $expdate,
						'damount'		 => $damount
			));
			$mail->setLayout('mail');
			// $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $name);
			$subject				 = 'Special promotion code from aaocab';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_PROMO_CODE, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			if ($model->bkg_id != '' && $elgId != '')
			{
				$desc							 = "Email sent on sent promotion code.";
				$eventid						 = BookingLog::EMAIL_SENT;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid, $oldModel, $params);
			}
		}
	}

	public static function emailReport($arr)
	{
		$input	 = "Good Morning";
		//    $rand_key = array_rand($input, 1);
		$msg	 = $input . ',' .
				'<br/><br/>Report of business done:<br/><br/>' .
				'Bookings made:<br/>' .
				'<table border="1px" cellpadding="5"><tr><th>Booking Date</th><th>Count</th><th>Revenue</th><th>Commission</th><th>Advance Count</th><th>Advance Amount</th></tr>';
		for ($i = 0; $i < sizeof($arr['booking']); $i++)
		{
			$row = $arr['booking'][$i];
			$msg .= "<tr><td>" . $row['booking_date'] . "</td><td>" . $row['count_booking'] . "</td><td>" . $row['revenue'] . "</td><td>" . $row['commission'] . "</td><td>" . $row['advance_count'] . "</td><td>" . $row['advance_amount'] . "</td></tr>";
		}
		$msg .= "</table><br/>";
		$msg .= 'Journeys done:<br/>' . "<table border='1px' cellpadding='5'><tr><th>Pickup Date</th><th>Count</th><th>Revenue</th><th>Commission</th><th>Advance Count</th><th>Advance Amount</th></tr>";
		for ($i = 0; $i < sizeof($arr['earning']); $i++)
		{
			$row = $arr['earning'][$i];
			$msg .= "<tr><td>" . $row['pickup_date'] . "</td><td>" . $row['count_booking'] . "</td><td>" . $row['revenue'] . "</td><td>" . $row['commission'] . "</td><td>" . $row['advance_count'] . "</td><td>" . $row['advance_amount'] . "</td></tr>";
		}
		$msg .= "</table><br/>";
		$msg .= "Bookings made yesterday:<br/>" . "<table border='1px' cellpadding='5'><tr><th>ID</th><th>From</th><th>To</th><th>Type</th><th>Amount</th><th>Pickup</th><th>Created</th><th>Advance Paid</th></tr>";
		for ($i = 0; $i < sizeof($arr['yesterday']); $i++)
		{
			$row = $arr['yesterday'][$i];
			$msg .= "<tr><td>" . $row['bid'] . "</td><td>" . $row['fromc'] . "</td><td>" . $row['toc'] . "</td><td>" . $row['btype'] . "</td><td>" . $row['amount'] . "</td><td>" . $row['ptime'] . "</td><td>" . $row['created'] . "</td><td>" . $row['advance_amount'] . "</td></tr>";
		}
		$msg		 .= "</table><br/>";
		$msg_plain	 = $msg;
		if ($arr['action'] == "email")
		{
			$msg	 .= "<br/> Click <a href = 'http://www.aaocab.com/index/businessemail?action=print'>here</a> to see report any time";
			$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo('leadership@aaocab.in', 'Leaders aaocab');
			$mail->setBody($msg);
			$subject = 'Daily Business Report';
			$mail->setSubject($subject);
			if ($mail->sendServicesEmail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $msg;
			$usertype	 = EmailLog::Admin;
			$email		 = 'leadership@aaocab.in';

			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
		}
		else if ($arr['action'] == "print")
		{
			print_r($msg);
		}
	}

	public static function createLog($address, $subject, $body, $booking_id, $usertype, $delivered, $emailType = "", $refType = "1", $refId = "", $email_type = '', $delay_time = 1, $attachments = 0)
	{
		/* var $model EmailLog */
		$emailLog				 = new EmailLog();
		//$emailLog->elg_from_name = Yii::app()->params['mail']['Accounts'];
		//$emailLog->elg_from_email = Yii::app()->params['mail']['noReplyMail'];
		$emailLog->elg_mail_type = EmailLog::SEND_ACCOUNT_EMAIL;
		if ($address != '')
		{
			$emailLog->elg_address		 = $address;
			$emailLog->elg_subject		 = $subject;
			$trmBody					 = Html2Text::convert($body);
			$emailLog->elg_content		 = $trmBody;
			$emailLog->elg_booking_id	 = $booking_id;

			if ($booking_id != "")
			{
				$emailLog->elg_ref_id = $booking_id;
			}
			if ($refId != "")
			{
				$emailLog->elg_ref_id = $refId;
			}
			$emailLog->elg_ref_type	 = $refType;
			$emailLog->elg_recipient = $usertype;
			$emailLog->elg_delivered = $delivered;
			if ($emailType != "")
			{
				$emailLog->elg_type = $emailType;
			}
			if ($delay_time == 0)
			{
				$emailLog->elg_status = 1;
			}
			else
			{
				$emailLog->elg_status_date	 = new CDbExpression('DATE_ADD(NOW(), INTERVAL ' . $delay_time . ' MINUTE)');
				$emailLog->elg_status		 = 2;
			}
			//if($from_name!='') $emailLog->elg_from_name = $from_name;
			//if($from_email!='') $emailLog->elg_from_email = $from_email;
			$emailLog->elg_mail_type = $email_type;
			if ($attachments != "")
			{
				$emailLog->elg_attachments = $attachments;
			}

			if ($emailLog->save())
			{
				if ($body != "")
				{
					// Server Id
					$serverId = Config::getServerID();

					$uniqueId				 = $emailLog->elg_id;
					$path					 = Yii::app()->basePath;
					$fileName				 = $refType . '_' . $uniqueId . '_' . $emailType . '.gml';
					$mainfoldername			 = $path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $serverId . DIRECTORY_SEPARATOR . 'mails';
					$subFolderDay			 = $mainfoldername . DIRECTORY_SEPARATOR . Filter::createFolderPrefix(strtotime($date));
					$logPath				 = Filter::WriteFile($subFolderDay, $fileName, $body);
					$dbPath					 = explode("doc", $logPath);
					$model					 = EmailLog::model()->resetScope()->findByPk($uniqueId);
					$model->elg_file_path	 = $dbPath[1];
					$model->elg_booking_id	 = $booking_id;
					$model->update();
				}
			}
			else
			{
				print_r($emailLog->getErrors());
			}
		}
		return $emailLog->elg_id;
	}

	public function signupEmailInfo($userid, $pass)
	{
		$model	 = Users::model()->findByPk($userid);
		/* @var $model Booking */
		$email	 = ContactEmail::model()->getEmailByUserId($userid);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		if ($email != '')
		{
			// $email = 'abhishek@epitech.in';
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setView('signupinfo');
			$bookarr['userName']	 = $model->usr_email;
			$bookarr['password']	 = $pass;

			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$mail->setLayout('mail');
			//  $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			$subject = 'Welcome to aaocab';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refType	 = EmailLog::REF_USER_ID;
			$refId		 = $model->user_id;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function meterdownSignup($userid, $pass)
	{
		$model	 = Vendors::model()->findByPk($userid);
		/* @var $model Vendors */
		$email	 = ContactEmail::model()->getEmailByUserId($userid);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		if ($email != '')
		{
			// $email = 'abhishek@epitech.in';
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setView('meterdownsignup');
			$mail->clearLayout();
			$bookarr['Name']		 = $model->vnd_name;
			$bookarr['password']	 = $pass;
			$bookarr['userName']	 = $model->vnd_username;
			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$subject				 = 'Your Meter Down account is ready';
			//$mail->setLayout('mail');
			// $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $model->vnd_name);
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::MeterDown;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function reply($name, $email, $reply, $type, $id)
	{
		if ($email != '')
		{
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
			{
				return false;
			}
			$body	 = "Dear $name," .
					"<br><br>$reply";
			$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			//   $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email);
			$mail->setBody($body);
			$subject = "Re: Feedback for Gozo Trip (Booking Id: $id)";
			$mail->setSubject($subject);
			$success = $mail->sendMail();
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			if ($type == 1)
			{
				$usertype = EmailLog::Consumers;
			}
			else
			{
				$usertype = EmailLog::Vendor;
			}
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
			return $success;
		}
		else
		{
			return false;
		}
	}

	public function signupEmailAgent($userid, $isWeb = 0, $pswd = "")
	{
		$model	 = Agents::model()->findByPk($userid);
		/* @var $model Booking */
		$email	 = $model->agt_email;
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return false;
		}
		if ($email != '')
		{
			$toName					 = $model->agt_fname;
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_AGENT_EMAIL);
			$mail->setView('signupagent');
			$bookarr['userName']	 = $model->agt_fname;
			$bookarr['email']		 = $model->agt_email;
			$bookarr['password']	 = $pswd;
			$bookarr['isWeb']		 = $isWeb;
			$bookarr['type']		 = $model->agt_type;
			$bookarr['loginUrl']	 = Yii::app()->createAbsoluteUrl('/agent');
			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$mail->setLayout('mail1');
			//  $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $toName);
			$subject				 = 'Welcome to the Gozo B2B travel network';
			$mail->setSubject($subject);
			// if ($mail->sendMail(0)) {
			$status					 = 0;
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered	 = "Email not sent";
				$status		 = 1;
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Agent;
			if ($model->agt_type == 1)
			{
				$usertype = EmailLog::Corporate;
			}
			$refId	 = $model->agt_id;
			$refType = EmailLog::REF_AGENT_ID;
			emailWrapper::createLog($email, $subject, $body, "", EmailLog::Agent, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL, $status);
		}
	}

	/**
	 * 
	 * @param integer $rid
	 * @param array $emailParams
	 * @return boolean
	 */
	public function reviewNotification($rid, $emailParams)
	{
		Logger::create("Start ReviewNotification Mail : rating_id \t" . $rid, CLogger::LEVEL_INFO);
		/* @var $model Ratings */
		$success = false;
		$elgId	 = 0;
		if (isset($rid) && $rid > 0)
		{
			$email					 = 'info@gozo.cab';
			$this->email_receipient	 = $email;
			if (($emailParams['rtg_customer_overall'] < 4) && ($emailParams['rtg_customer_driver'] <> NULL && $emailParams['rtg_customer_driver'] < 5))
			{
				$review['rtg_driver_good_attr']	 = $emailParams['rtg_driver_good_attr'];
				$review['rtg_driver_bad_attr']	 = $emailParams['rtg_driver_bad_attr'];
			}
			if (($emailParams['rtg_customer_overall'] < 4) && ($emailParams['rtg_customer_car'] <> NULL && $emailParams['rtg_customer_car'] < 4))
			{
				$review['rtg_car_good_attr'] = $emailParams['rtg_car_good_attr'];
				$review['rtg_car_bad_attr']	 = $emailParams['rtg_car_bad_attr'];
			}
			if (($emailParams['rtg_customer_overall'] < 4) && ($emailParams['rtg_customer_csr'] <> NULL && $emailParams['rtg_customer_csr'] < 5))
			{
				$review['rtg_csr_good_attr'] = $emailParams['rtg_csr_good_attr'];
				$review['rtg_csr_bad_attr']	 = $emailParams['rtg_csr_bad_attr'];
			}
			if ($emailParams['rtg_platform_exp'] == 1)
			{
				$csrPlatform = "Yes";
			}
			else if ($emailParams['rtg_platform_exp'] == 0)
			{
				$csrPlatform = "No";
			}
			else if ($emailParams['rtg_platform_exp'] == 2)
			{
				$csrPlatform = "Didn't use";
			}
			$review['rtg_platform_exp_cmt']	 = ($emailParams['rtg_platform_exp_cmt'] <> '') ? $emailParams['rtg_platform_exp_cmt'] : '';
			$review['contact_gozo']			 = ($emailParams['bkg_contact_gozo'] > 0) ? 'Yes' : 'No';
			if ($emailParams['bkg_agent_id'] > 0)
			{
				$review['booking_type']	 = 'B2B';
				$review['agent_name']	 = $emailParams['agt_name'];
			}
			else
			{
				$review['booking_type']	 = 'B2C';
				$review['agent_name']	 = $emailParams['agt_name'];
			}
			$review['booking_id']				 = $emailParams['bkg_booking_id'];
			$review['rtg_customer_recommend']	 = $emailParams['rtg_customer_recommend'];
			$review['rtg_customer_overall']		 = $emailParams['rtg_customer_overall'];
			$review['rtg_customer_driver']		 = $emailParams['rtg_customer_driver'];
			$review['rtg_customer_csr']			 = $emailParams['rtg_customer_csr'];
			$review['rtg_customer_car']			 = $emailParams['rtg_customer_car'];
			$review['rtg_customer_review']		 = $emailParams['rtg_customer_review'];
			$review['date']						 = date('d-m-Y h:i A ', strtotime($emailParams['rtg_customer_date']));
			$review['platform']					 = $csrPlatform;
			$review['csr_cmt']					 = ($emailParams['rtg_csr_cmt'] <> '') ? $emailParams['rtg_car_cmt'] : '';
			$review['rtg_customer_email']		 = $emailParams['bkg_user_email'];
			$review['vnd_code']					 = $emailParams['vnd_code'];
			$review['drv_code']					 = $emailParams['drv_code'];
			$review['vhc_code']					 = $emailParams['vhc_code'];
			$review['vht_model']				 = $emailParams['vht_model'];
			$review['vht_make']					 = $emailParams['vht_make'];
			$review['vhc_number']				 = $emailParams['vhc_number'];
			$review['user_name']				 = $emailParams['user_name'];
			$review['customer_route']			 = $emailParams['customer_route'];
			$review['bkg_country_code']			 = $emailParams['bkg_country_code'];
			$review['bkg_contact_no']			 = $emailParams['bkg_contact_no'];
			$review['vendor_rating']			 = $emailParams['vendor_rating'];
			$review['driver_rating']			 = $emailParams['driver_rating'];
			$review['driver_is_approved']		 = $emailParams['driver_is_approved'];
			$review['vehicle_is_approved']		 = $emailParams['vehicle_is_approved'];
			$review['vhc_is_commercial']		 = $emailParams['vhc_is_commercial'];
			$review['total_trip_by_car']		 = $emailParams['total_trip_by_car'];
			$review['first_trip_date']			 = $emailParams['first_trip_date'];
			$review['last_trip_date']			 = $emailParams['last_trip_date'];
			$review['user_rating']				 = $emailParams['user_rating'];
			$review['total_trip']				 = ($emailParams['total_trip'] > 0) ? $emailParams['total_trip'] : 0;
			$review['bkg_booking_id']			 = $emailParams['bkg_booking_id'];
			$review['bkg_user_email']			 = $emailParams['bkg_user_email'];
			$review['rtg_csr_cmt']				 = ($emailParams['rtg_csr_cmt'] <> '') ? $emailParams['rtg_csr_cmt'] : '';
			$review['rtg_car_cmt']				 = ($emailParams['rtg_car_cmt'] <> '') ? $emailParams['rtg_car_cmt'] : '';
			$review['rtg_driver_cmt']			 = ($emailParams['rtg_driver_cmt'] <> '') ? $emailParams['rtg_driver_cmt'] : '';

			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->clearView();
			$mail->setView('customer_review');
			$mail->setData(
					array(
						'arr' => $review
			));
			$mail->clearLayout();
			$mail->setLayout('mail1');
			$mail->setTo($email);
			$mail->setBcc(Yii::app()->params['adminEmail'], 'Leadership - aaocab');  // Yii::app()->params['adminEmail'], 'Leadership - aaocab'
			if ($emailParams['bkg_user_email'] != '')
			{
				$replyToEmail	 = $emailParams['bkg_user_email'];
				$replyToName	 = ($emailParams['user_name']);
				$mail->addReplyTo($replyToEmail, $replyToName);
			}
			//$mail->setBody($body);
			$bookingId	 = $emailParams['bkg_booking_id'];
			$subject	 = "Customer review for Booking Id: $bookingId";
			$mail->setSubject($subject);
			$success	 = $mail->sendMail(0);
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Admin;
			$emailType	 = EmailLog::EMAIL_CSR_NOTIFY_REVIEW_MAIL;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $emailParams['bkg_id'];
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, 0);
			Logger::create("End ReviewNotification Mail : elg_id \t" . $elgId, CLogger::LEVEL_INFO);
			$success	 = true;
		}
		return $elgId;
	}

	/**
	 * 
	 * @param integer $rtgId
	 * @param string $uniqueId
	 * @return boolean
	 */
	public function reviewNotificationVendor($rtgId, $uniqueId = '')
	{
		Logger::create("Start reviewNotificationVendor Mail : rating_id \t" . $rtgId, CLogger::LEVEL_INFO);
		$success	 = false;
		$data		 = Ratings::model()->getDriverVendorDetailsById($rtgId);
		//$bookData	 = Booking::model()->getBkgIdByBookingId($data['bkg_booking_id']);
		$bookData	 = Booking::model()->findByPk($data['bkg_id']);
		if ($bookData != '' && $bookData->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		if (isset($data['vnd_email']) && $data['vnd_email'] != '')
		{
			if (Unsubscribe::isUnsubscribed($data['vnd_email'], Unsubscribe::CAT_RATING))
			{
				return false;
			}
			$email					 = $data['vnd_email'];
			$name					 = $data['vnd_owner'];
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setLayout('mail');
			$mail->setTo($email, $name);
			$subject				 = 'Review received for booking ID ' . $data['bkg_booking_id'] . '';
			$comment				 = Ratings::model()->getCommentByOverallRating($data['rtg_customer_overall']);
			//$link					 = 'http://' . Yii::app()->params['host'] . '/rating/bookingreview?&uniqueid=' . $uniqueId;
			$link					 = 'http://' . Yii::app()->params['host'] . '/r/' . $uniqueId;
			$link					 = Filter::shortUrl($link);
			$body					 = 'Dear ' . $name . ',<br/><br/>';
			$body					 .= '' . $data['rtg_customer_overall'] . ' (' . $comment . ') review received for booking ID ' . $data['bkg_booking_id'] . '';
			$body					 .= '<br/>See link ' . $link . ' for details.';
			$body					 .= '<br/><br/>Thank you,
                   <br/>Team Gozo';
			$mail->setBody($body);
			$mail->setSubject($subject);
			$success				 = $mail->sendMail();
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Vendor;
			$emailType	 = EmailLog::EMAIL_VENDOR_NOTIFY_CUSTOMER_MAIL;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$refId		 = $data['vnd_id'];
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $data['bkg_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			Logger::create("End reviewNotificationVendor Mail : rating_id \t" . $rtgId, CLogger::LEVEL_INFO);
			$success	 = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param intger $rtgId
	 * @param string $uniqueId
	 * @return boolean
	 */
	public function reviewNotificationDriver($rtgId, $uniqueId = '')
	{
		Logger::create("Start reviewNotificationDriver Mail : rating_id \t" . $rtgId, CLogger::LEVEL_INFO);
		$success	 = false;
		$data		 = Ratings::getDriverRatingByRtgId($rtgId);
		//$bookData	 = Booking::model()->getBkgIdByBookingId($data['bkg_booking_id']);
		$bookData	 = Booking::model()->findByPk($data['bkg_id']);
		if ($bookData != '' && $bookData->bkgPref->bkg_blocked_msg == 1)
		{
			return;
		}
		if (isset($data['drv_email']) && $data['drv_email'] != '')
		{
			if (Unsubscribe::isUnsubscribed($data['drv_email'], Unsubscribe::CAT_BOOKING))
			{
				return false;
			}
			$email					 = $data['drv_email'];
			$name					 = $data['drv_name'];
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setLayout('mail');
			$mail->setTo($email, $name);
			$subject				 = 'Review received for booking ID ' . $data['bkg_booking_id'] . '';
			$comment				 = Ratings::model()->getCommentByOverallRating($data['rtg_customer_overall']);
			//$link					 = 'http://' . Yii::app()->params['host'] . '/rating/bookingreview?&uniqueid=' . $uniqueId;
			$link					 = 'http://' . Yii::app()->params['host'] . '/r/' . $uniqueId;
			$body					 = 'Dear ' . $name . ',<br/><br/>';
			$body					 .= '' . $data['rtg_customer_overall'] . ' (' . $comment . ') review received for booking ID ' . $data['bkg_booking_id'] . '';
			$body					 .= '<br/>See link ' . Filter::shortUrl($link) . ' for details.';
			$body					 .= '<br/><br/>Thank you,
                   <br/>Team Gozo';
			$mail->setBody($body);
			$mail->setSubject($subject);
			$success				 = $mail->sendMail();
			$delivered				 = ($success) ? "Email sent successfully" : "Email not sent";
			$usertype				 = EmailLog::Driver;
			$emailType				 = EmailLog::EMAIL_DRIVER_NOTIFY_CUSTOMER_MAIL;
			$refType				 = EmailLog::REF_DRIVER_ID;
			$refId					 = $data['drv_id'];
			$elgId					 = emailWrapper::createLog($email, $subject, $body, $data['bkg_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			Logger::create("End reviewNotificationDriver Mail : elg_id \t" . $elgId, CLogger::LEVEL_INFO);
			$success				 = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $rid
	 * @param string $uniqueId
	 * @return boolean
	 */
	public function reviewStarNotification($rid, $uniqueId = '')
	{
		Logger::create("Start StarNotification Mail : rating_id \t" . $rid, CLogger::LEVEL_INFO);
		$success	 = false;
		$ratingRow	 = Ratings::model()->getDetailsByRatingId($rid);
		if ($ratingRow['agent_id'] > 0 || $ratingRow['corporate_id'] > 0)
		{
			return false;
		}
		if (isset($ratingRow['bkg_user_email']) && $ratingRow['bkg_user_email'] <> '')
		{
			$email					 = $ratingRow['bkg_user_email'];
			$name					 = $ratingRow['usr_name'];
			$this->email_receipient	 = $email;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_RATING))
			{
				return false;
			}
			$bookingId			 = trim($ratingRow['booking_id']);
			$driverName			 = trim($ratingRow['driver_name']);
			$user				 = trim($ratingRow['usr_name']);
			$usrReferCode		 = trim($ratingRow['usr_refer_code']);
			$customerRecommend	 = trim($ratingRow['rtg_customer_recommend']);
			$comment			 = trim($ratingRow['rtg_customer_review']);

			$ratingLinkList	 = Ratings::reviewButtonLink($uniqueId, $usrReferCode, $comment);
			$link			 = $ratingLinkList['inviteLink'];
			$mailBody		 = "Dear Friend,%0D%0DI traveled with aaocab and and loved it. Try Gozo with the URL below and both you and I will get Rs. 200 credit for our next trip.%0D$link%0DHere is my review from my trip "
					. $ratingRow['booking_id'] . ':%0D "'
					. $comment . '"%0D%0DRegards%0D' . $ratingRow['usr_name'];

			$ratingLinkList['customer_recommend']	 = $customerRecommend;
			$ratingLinkList['comment']				 = $comment;
			$ratingLinkList['user']					 = $user;
			$ratingLinkList['driver_name']			 = $driverName;
			$ratingLinkList['mailBody']				 = $mailBody;
			$ratingLinkList['bkg_id']				 = $ratingRow['bkg_id'];
			$ratingLinkList['usr_refer_code']		 = $usrReferCode;
			$ratingLinkList['hash']					 = Yii::app()->shortHash->hash($ratingRow['bkg_id']);

			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->clearView();
			$mail->setView('reviewstarnotification');
			$mail->setData(
					array(
						'arr' => $ratingLinkList
			));
			$mail->clearLayout();
			$mail->setLayout('mail1');
			$mail->setTo($email);
			$subject = "We got your feedback on $bookingId. Please give us just 1 more minute!";
			$mail->setSubject($subject);
			$success = $mail->sendMail(0);
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Admin;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $ratingRow['bkg_id'];
			$emailType	 = EmailLog::EMAIL_USER_NOTIFY_REVIEW_MAIL;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, 0);
			Logger::create("End StarNotification Mail : elg_id \t" . $elgId, CLogger::LEVEL_INFO);
			$success	 = true;
		}
		return $success;
	}

	public function paymentLink($bkgid, $minPayExtra = 0)
	{
		$model = Booking::model()->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email		 = $response->getData()->email['email'];
			$firstName	 = $response->getData()->email['firstName'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		$usertype = EmailLog::Consumers;

		$delivered = '';

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::SEND_PAYMENT_LINK);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
			// return false;
		}


		if ($email != '' || count($emailArr) > 0)
		{
			$this->email_receipient	 = $email;
			$booking_id				 = $model->bkg_booking_id;
			//  changes
			$advance				 = 0;
			if ($model->bkgInvoice->bkg_advance_amount > 0)
			{
				$advance = round($model->bkgInvoice->bkg_advance_amount);
				$due	 = round($model->bkgInvoice->bkg_due_amount);
			}
			$hash	 = Yii::app()->shortHash->hash($bkgid);
			$getUrl	 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			$url	 = Filter::shortUrl($getUrl);
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
					$amountStr = ' Amount payable Rs.' . $model->bkgInvoice->bkg_total_amount;
				}
			}
			if ($model->bkgInvoice->bkg_corporate_credit > 0)
			{
				$amountStr = ' Amount due Rs.' . $model->bkgInvoice->bkg_due_amount;
			}
			if ($minPayExtra > 0)
			{
				$amountStr = ' For rescheduling minimum amount payable is Rs.' . $minPayExtra . '';
			}
			if ($model->bkgInvoice->bkg_convenience_charge > 0 && ($model->bkgInvoice->bkg_corporate_credit == '' || $model->bkgInvoice->bkg_corporate_credit == 0))
			{
				$Model11			 = clone $model;
				$Model11->bkgInvoice->calculateConvenienceFee(0);
				$Model11->bkgInvoice->calculateTotal();
				//$feeWithoutConvenience = $Model11->bkg_due_amount;
				$minamount			 = $Model11->bkgInvoice->calculateMinPayment();
				$minamtWithoutTax	 = $model->bkgInvoice->bkg_convenience_charge;
				$minamtWithTax		 = Filter::getServiceTax($minamtWithoutTax, $model->bkg_agent_id, $model->bkg_booking_type);
				$codFee				 = $minamtWithoutTax + $minamtWithTax;
				/*
				  $amountStr .= ". Waive off your 'collect-on-delivery' fee (Rs." . $codFee . ") by paying at least Rs." . $minamount . " advance payment before " . $Model11->getExpTimeCashBack() . ".
				  Revised total fare will be " . $Model11->bkgInvoice->bkg_due_amount . ". to save upto 50%";
				 * 
				 */
			}
			//changes
			$body	 = 'Hello ' . $firstName .
					',<br/><br/>Thank you for choosing aaocab! For your Booking ID: ' . $booking_id . ',' . $amountStr .
					', please click on the following link to make an online payment.' .
					'<br/><a href="' . $url . '">' . $url . '</a>
            <br/><br/>Regards,<br/>Team Gozo';
			$mail	 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setBody($body);
			if (count($emailArr) > 0)
			{

				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
			}

			$mail->setLayout('mail');

			$subject = 'Payment link – Booking ID : ' . $model->bkg_booking_id;
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body	 = $mail->Body;
			$refType = EmailLog::REF_BOOKING_ID;
			$refId	 = $model->bkg_id;
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL, 0);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL, 0);
			}
		}
	}

	public function paymentFailedAlert($bkgId, $message = '')
	{

		$model					 = Booking::model()->findByPk($bkgId);
		$delivered				 = '';
		$email					 = 'info@aaocab.com';
		$this->email_receipient	 = $email;
		$response				 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
		if ($response->getStatus())
		{
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$email		 = $response->getData()->email['email'];
		}
		if ($email != '' && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0))
		{

			$booking_id	 = $model->bkg_booking_id;
			$platform	 = $model->bkgTrail->bkg_platform;
			$platform	 = $model->booking_platform[$platform];
//            if ($platform == 1) {
//                $platform = 'User';
//            } else if ($platform == 2) {
//                $platform = 'Admin';
//            } else if ($platform == 3) {
//                $platform = 'App';
//            }
			$route		 = '( ' . $model->bkgFromCity->cty_name . ' - ' . $model->bkgToCity->cty_name . ' )';
			$body		 = 'A booking initiated by customer for Booking id:' . $booking_id . ' .<br/>Details are as follows :-<br/>';
			$body		 .= 'Name : ' . $firstName . '  ' . $lastName . ' <br/>';
			$body		 .= 'Route : ' . $route . ' <br/>';
			$body		 .= 'Pickup Date : ' . date('d-m-Y h:i A ', strtotime($model->bkg_pickup_date)) . ' <br/>';
			$body		 .= 'Booking Date : ' . date('d-m-Y h:i A ', strtotime($model->bkg_create_date)) . ' <br/>';
			$body		 .= 'Contact Number :' . $countryCode . '  ' . $contactNo . ' <br/>';
			$body		 .= 'Contact Email : ' . $email . ' <br/>';
			$body		 .= 'Platform : ' . $platform . ' <br/>';
			$body		 .= 'Amount : Rs. ' . $model->bkgInvoice->bkg_due_amount . ' <br/>';
			$body		 .= 'Advance Amount : Rs. ' . $model->bkgInvoice->bkg_advance_amount . ' <br/>';
			$body		 .= 'Reason : ' . $message . ' <br/>';
			$body		 .= '<br/><br/>Thank you,<br/>Regards,<br/>Team Gozo';

			//  $mail = new YiiMailer();
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setBody($body);

			//$mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, 'Info aaocab');
			$mail->setLayout('mail');
			$subject = 'Payment Failed – Booking ID : ' . $model->bkg_booking_id;
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Admin;
			$emailType	 = EmailLog::EMAIL_PAYMENT_FAILED;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	public static function leadReport($arr)
	{
		$msg		 = 'Lead Report:<br/><br/>' .
				'<table border="1px" cellpadding="5"><tr><th>Total Pending Leads</th><th>New Unverified</th><th>Unverified Leads</th><th>New Leads</th><th>Other Leads</th><th>Pickup Leads</th><th>Todays Followed Up</th></tr>';
		$msg		 .= "<tr><td>" . $arr['total_pending_leads'] . "</td><td>" . $arr['new_unverified'] . "</td><td>" . $arr['unverified_leads'] . "</td><td>" . $arr['new_leads'] . "</td><td>" . $arr['other_leads'] . "</td><td>" . $arr['pickup_leads'] . "</td><td>" . $arr['today_followed_up'] . "</td></tr>";
		$msg		 .= "</table><br/>";
		$msg_plain	 = $msg;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
		$mail->clearView();
		$mail->clearLayout();
		$mail->setFrom('info@aaocab.com', 'Info aaocab');
		$mail->setTo('leadership@aaocab.in', 'Leadership aaocab');
		$mail->setBody($msg);
		$subject	 = 'Lead Report';
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$body		 = $msg;
		$usertype	 = EmailLog::Admin;
		$email		 = 'sanjay@aaocab.com';
		emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL, 0);
	}

	public static function dailyReport($arr = '')
	{

		$month		 = date("m", strtotime(date('Y-m-d')));
		$month1		 = date("m", strtotime(" -1 months"));
		$month2		 = date("m", strtotime(" -2 months"));
		$monthAvg	 = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
		$month1Avg	 = cal_days_in_month(CAL_GREGORIAN, $month1, date('Y'));
		$month2Avg	 = cal_days_in_month(CAL_GREGORIAN, $month2, date('Y'));

		$yearFromDate	 = date("Y-01-01");
		$monthFromDate	 = date("Y-m-01");
		$toDate			 = date("Y-m-d");
		$filterObj		 = new Filter();
		$MTDDays		 = ($filterObj->dateCount($monthFromDate, $toDate) + 1);
		$YTDDays		 = ($filterObj->dateCount($yearFromDate, $toDate) + 1);

		/* @var $modelsub BookingSub */
		$msg = '<h3><b>Date & Time of Report:</b> ' . date("l") . ', ' . date("j F Y") . ' at ' . date("G:i") . '</h3><br/><br/>';

		$modelsub	 = new BookingSub();
		/* @var $modelven Vendors */
		$modelven	 = new Vendors();
		$msg		 .= $modelsub->getBusinessReportHtml();
		$msg		 .= BookingTrail::getAssignmentReportHtml();
		$msg		 .= $modelsub->getBusinessTrendReportHtml();
		$msg		 .= $modelsub->getRevenueReportHtml();
		$msg		 .= $modelsub->getRevenueReportHtmlByPickup();
		$msg		 .= $modelven->getReceivablePendingHtml();

		$msg .= $modelsub->getPartnerReportHtml1();
		$msg .= $modelsub->getPartnerReportHtml2();

		$msg	 .= $modelsub->getDistributionByBookingTypeHtml();
		$msg	 .= $modelsub->getVendorAssignmentReportHtml();
		$msg	 .= $modelsub->getRegionalBookingDistributionHtml();
		$msg	 .= $modelsub->getSmartMatchHtml();
		$msg	 .= $modelsub->getActiveBookingHtml();
		$msg	 .= $modelsub->getBookingCreatedPatternHtml();
		$msg	 .= $modelsub->getBookingCancellationPatternHtml();
		$msg	 .= $modelsub->getPLTrendReportHtml();
		$msg	 .= $modelsub->getAdvancePaymentReportHtml();
		$msg	 .= $modelsub->getNewRepeatCustomerHtml();
		$msg	 .= $modelsub->getLifetimeTripReportHtml();
		$msg	 .= $modelsub->getBookingByRatingReportHtml();
		$msg	 .= $modelsub->getBookingByPlatformReportHtml();
		$msg	 .= $modelsub->getBusinessSourceZoneHtml();
		$msg	 .= $modelsub->getBusinessDestinationZoneHtml();
		//$msg .= $modelsub->getCancellationBookingReportHtml();
		$msg	 .= $modelsub->getInventoryMetricsReportHtml();
		$msg	 .= $modelsub->getBookingBySourceReportHtml();
		$msg	 .= $modelsub->getCancelReasonReportHtml();
		$msg	 .= $modelsub->getNonProfitBookingsByMtdHtml();
		$msg	 .= $modelsub->getCancellationTrendReportHtml();
		$msg	 .= $modelsub->getBookingByZoneReportHtml();
		$msg	 .= $modelsub->getZoneCancellationReportHtml();
		$msg	 .= $modelsub->getZoneCancellationReportHtml('to');
		$subject = 'Daily Report ';
		$body	 = $msg;
		$mail	 = EIMailer::getInstance(EmailLog::SEND_DAILY_EMAIL);
		$mail->clearView();
		$mail->clearLayout();
		$mail->setBody($body);
		$mail->setTo(Yii::app()->params['adminEmail'], 'Leaders aaocab');
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		echo $delivered;
	}

	public function cancellationDaily()
	{
		$msg		 = '<h3><b>Date & Time of Report:</b> ' . date("l") . ', ' . date("j F Y") . ' at ' . date("G:i") . '</h3><br/><br/>';
		/* @var $modelsub BookingSub */
		$modelsub	 = new BookingSub();
		$msg		 .= $modelsub->getCancellationReasonReportHtml();
		$msg		 .= $modelsub->getCancellationSourceReportHtml();
		$msg		 .= $modelsub->getCancellationBookingReportHtml();
		$subject	 = 'Booking Cancellation Report';
		$body		 = $msg;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_DAILY_EMAIL);
		$mail->clearView();
		$mail->clearLayout();
		$mail->setBody($body);
		$mail->setTo(Yii::app()->params['leadAboveEmail'], 'Leaders aaocab');
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		echo $delivered;
	}

	public function accountReceivableWeekly()
	{
		$msg		 = '<h3><b>Date & Time of Report:</b> ' . date("l") . ', ' . date("j F Y") . ' at ' . date("G:i") . '</h3><br/>';
		/* @var $modelven Vendors */
		$modelven	 = new Vendors();
		$msg		 .= $modelven->getReceivablePendingHtml();
		$msg		 .= $modelven->getReceivablePendingByVendorHtml();
		$subject	 = 'Account receivables (Weekly update)';
		$body		 = $msg;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_VENDOR_BATCH_EMAIL);
		$bccMail	 = Yii::app()->params['adminEmail'];
		$mail->clearView();
		$mail->clearLayout();
		$mail->setBody($body);
		$mail->setTo(Yii::app()->params['adminEmail'], 'Leadership - aaocab');
		$mail->setBcc(Yii::app()->params['adminUserEmail'], 'Roy - aaocab');
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		echo $delivered;
	}

	public function vendorUpdate($vnd_id, $messageText, $messageType)
	{
		$model		 = Vendors::model()->findByPk($vnd_id);
		//$email	 = $model->vndContact->emlContact->eml_email_address;
		$contactId	 = ContactProfile::getByEntityId($vnd_id, UserInfo::TYPE_VENDOR);
		$email		 = ContactEmail::getPrimaryEmail($contactId);
		$delivered	 = '';
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->clearView();
			$mail->clearLayout();

			$mail->setTo($email, $email);
			$body	 = 'Dear ' . $model->vnd_name . ',<br/><br/>
                <h3><b>Important Update from aaocab- ' . $messageText . '</b></h3>
                <br/><br/>Regards,<br/>aaocab Support<br/>+91-90518-77-000';
			$mail->setBody($body);
			$subject = 'Important Update from aaocab.';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo "\n";
			echo $delivered;
			$usertype = EmailLog::Vendor;
			emailWrapper::createLog($email, $subject, $body, $messageType, $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function driverUpdate($drv_id, $messageText, $messageType)
	{
		$model		 = Drivers::model()->findByPk($drv_id);
		//$email		 = $model->drv_email;
		$email		 = ContactEmail::model()->getContactEmailById($model->drv_contact_id);
		$delivered	 = '';
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}

		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$mail->setTo($email, $email);
			$body					 = 'Dear ' . $model->drv_name . ',<br/><br/>
                        <h3><b>Important Update from aaocab- ' . $messageText . '</b></h3>
                        <br/><br/>Regards,<br/>aaocab Support<br/>+91-90518-77-000';
			$mail->setBody($body);
			$subject				 = 'Important Update from aaocab.';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo "\n";
			echo $delivered;
			$usertype = EmailLog::Driver;
			emailWrapper::createLog($email, $subject, $body, $messageType, $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function snapshotReport()
	{
		$email		 = Yii::app()->params['adminEmail'];
		//$email='deepak@epitech.in';
		$delivered	 = '';
		if ($email != '')
		{
			$data					 = BookingTemp::model()->reportSnapshot();
			$this->email_receipient	 = $email;
			$lastMonday				 = $data['lastMonday'];
			$snapshot				 = "<u>Booking Highlights</u>"
					. "<br/><br/>Number of New state: " . $data['new']
					. "<br/>Number of Assigned state: " . $data['assigned']
					. "<br/>Number of On-the way: " . $data['onTheWay']
					. "<br/>Number of Completed but not settled: " . $data['completed']
					. "<br/><br/><br/>"
					. "Lead Pending (to be followed up): " . $data['pendingLeads']
					. "<br/><br/>Aggregate avg lead closure times from last Monday ($lastMonday): " . round($data['avgLeadClosingDay'], 1) . "days";
			$body					 = "$snapshot";

			//  $mail = new YiiMailer();
			$mail	 = new EIMailer();
			$mail->setBody($body);
			//$mail->setFrom("info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, 'Admins');
			$mail->clearView();
			$mail->clearLayout();
			$subject = 'Snapshot Report';
			$mail->setSubject($subject);
			if ($mail->sendAccountsEmail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
//			$body = $mail->Body;
//			$usertype = EmailLog::Admin;
			//	emailWrapper::createLog($email, $subject, $body, '', $usertype, $delivered);
			//echo $delivered;
		}
	}

	public function signupReferEmail($userid, $creditVal)
	{
		$model	 = Users::model()->findByPk($userid);
		/* @var $model Users */
		$email	 = ContactEmail::model()->getEmailByUserId($userid);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}

		if ($email != '')
		{
			// $email = 'abhishek@epitech.in';
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('signuprefer');
			$bookarr['userName']	 = $model->usr_name . ' ' . $model->usr_lname;
			$bookarr['ref_amount']	 = $creditVal;
			$mail->setData(
					array(
						'arr' => $bookarr,
			));
			$mail->setLayout('mail');
			//  $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $model->usr_name . ' ' . $model->usr_lname);
			$subject				 = 'Welcome to aaocab';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_SERVICE_EMAIL);
		}
	}

	/**
	 * 
	 * @param integer $bkgid
	 * @param type $invoiceView
	 * @return boolean
	 */
	public function sendInvoice($bkgid, $invoiceView = 0)
	{
		$return	 = false;
		$model	 = Booking::model()->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		if ($model->bkg_status == 9)
		{
			$return = $this->bookingCancellationMail($bkgid);
		}
		if (in_array($model->bkg_status, [5, 6, 7]))
		{
			$return = $this->bookingCompletedMail($model);
		}

		return $return;
	}

	/**
	 * @param Booking $model
	 * @return boolean
	 */
	public function bookingCompletedMail($model)
	{
		/* @var $model Booking */
		$usertype	 = EmailLog::Consumers;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0 && $model->bkg_agent_id != Yii::app()->params['gozoChannelPartnerId'])
		{
			//remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::INVOICE);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
			//remove comment when agent panel notificaion will be live
		}


		$invoiceLink		 = Filter::shortUrl(BookingInvoice::getInvoiceUrl($model->bkg_id));
		$reviewLink			 = Filter::shortUrl(BookingInvoice::getReviewUrl($model->bkg_id));
		$payURL				 = Filter::shortUrl(BookingUser::getPaymentLinkByEmail($model->bkg_id));
		$uniqueid			 = Booking::model()->generateLinkUniqueid($model->bkg_id);
		$link				 = 'https://' . Yii::app()->params['host'] . '/r/' . $uniqueid;
		$data				 = ['agent_ref_code' => $model->bkg_agent_ref_code,
			'bkg_status'	 => $model->bkg_status,
			'bkg_agent_id'	 => $model->bkg_agent_id,
			'booking_id'	 => $model->bkg_booking_id,
			'bkg_user_fname' => $model->bkgUserInfo->bkg_user_fname,
			'bkg_user_lname' => $model->bkgUserInfo->bkg_user_lname
		];
		$data['pickup_date'] = date('d/m/Y h:i A', strtotime($model->bkg_pickup_date));
		$data['reviewLink']	 = "<a href='$reviewLink' target='_blank'>click</a>";
		$data['invoiceLink'] = "<a href='$invoiceLink' target='_blank'>Invoice</a>";
		$data['payURL']		 = "<a href='" . $payURL . "#rating" . "' target='_blank'>click</a>";

		$data['reviewlink'] = $link;
		if ($data != '' && count($data) > 0)
		{
			$email					 = $model->bkgUserInfo->bkg_user_email;
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->clearView();
			$mail->setLayout('mail');
			$subject				 = 'Your Invoice and Valuable Feedback Request';
			if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setView('customer_invoice');
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$mail->setData(['data' => $data, "email_receipient" => $email, "userId" => $model->bkgUserInfo->bkg_user_id]);
				$mail->setSubject($subject);
				if ($mail->sendMail(0))
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
			}
			else
			{
				if (count($emailArr) > 0)
				{
					foreach ($logArr as $key => $value)
					{
						$usertype = $key;
						if (in_array($usertype, [6, 7]))
						{
							$mail->setView('partner_invoice');
							$subject = 'Partner Invoice – Booking ID : ' . $model->bkg_booking_id;
						}
						else
						{
							$mail->setView('customer_invoice');
							$subject = 'Invoice – Booking ID : ' . $model->bkg_booking_id;
						}
						$email = $value['email'];

						$toArr					 = [];
						$toArr[$value['email']]	 = $value['name'];
						$mail->setTo($toArr);
						$mail->setData(['data' => $data, "email_recepient" => $value['email']]);
						$mail->setSubject($subject);
						if ($mail->sendMail(0))
						{
							$delivered = "Email sent successfully";
						}
						else
						{
							$delivered = "Email not sent";
						}
					}
				}
			}

			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_INVOICE;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			}
			if ($elgId != '')
			{
				BookingLog::model()->createLog($model->bkg_id, "Invoice sent to customer email.", UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param Booking $model
	 * @return boolean
	 * @deprecated
	 */
	public function generateCancellationInvoice($model)
	{
		/* @var $model Booking */
		$usertype	 = EmailLog::Consumers;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0 && $model->bkg_agent_id != Yii::app()->params['gozoChannelPartnerId'])
		{
			//remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::INVOICE);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
		}
		if ($email != '' || count($emailArr) > 0)
		{
			$this->email_receipient	 = $email;
			$uniqueid				 = Booking::model()->generateLinkUniqueid($model->bkg_id);
			$link					 = Yii::app()->params['fullBaseURL'] . '/r/' . $uniqueid;
			$hash					 = Yii::app()->shortHash->hash($model->bkg_id);

			$fileLink	 = Yii::app()->params['fullBaseURL'] . '/booking/invoice?bkgId=' . $model->bkg_id . '&hash=' . $hash . '&email=1';
			$fileLink	 = Filter::shortUrl($fileLink);
			$fileArray	 = [0 => ['URL' => $fileLink]];

			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			if ($model->bkg_agent_id > 0)
			{
				$mail->setView('partner_invoice');
			}
			else
			{
				$mail->setView('customer_invoice');
			}
			$params['bookingId']	 = $model->bkg_booking_id;
			$params['invoiceLink']	 = $fileLink;
			$params['refCode']		 = ($model->bkg_agent_id > 0) ? $model->bkg_agent_ref_code : '';
			$mail->setData(
					array(
						'data'				 => $params, "email_receipient"	 => $email, 'userId'			 => $model->bkgUserInfo->bkg_user_id
			));
			$mail->clearView();
			$mail->setLayout('mail');
			$subject				 = 'Cancellation Invoice – Booking ID : ' . $model->bkg_booking_id;
			$mail->setSubject($subject);
			$delivered_flag			 = 0;
			$refType				 = EmailLog::REF_BOOKING_ID;
			$refId					 = $model->bkg_id;
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype = $key;
					if ($usertype == 6 || $usertype == 7)
					{
						$subject = 'Partner Cancellation Invoice – Booking ID : ' . $model->bkg_booking_id;
					}
					else
					{
						$subject = 'Cancellation Invoice – Booking ID : ' . $model->bkg_booking_id;
					}
					$email = $value['email'];

					$toArr					 = [];
					$toArr[$value['email']]	 = $value['name'];
					$mail->setView('partner_invoice');
//                    $mail->setTo($emailArr);
					$mail->setTo($toArr);
					$mail->setData(['data' => $params, "email_receipient" => $email, 'userId' => $model->bkgUserInfo->bkg_user_id]);
					if ($mail->sendMail())
					{
						$delivered_flag	 = 1;
						$delivered		 = "Email sent successfully";
					}
					else
					{
						$delivered_flag	 = 0;
						$delivered		 = "Email not sent";
					}
					$elgId = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_INVOICE, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 1);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0 || $model->bkg_agent_id == Yii::app()->params['gozoChannelPartnerId'])
			{
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$mail->setView('customer_invoice');
				$mail->setData(
						array(
							'data'				 => $params, "email_receipient"	 => $email, 'userId'			 => $model->bkgUserInfo->bkg_user_id
				));
				//$mail->setData(["email_recepient" => $email]);
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
				if ($mail->sendMail(0))
				{
					$delivered_flag	 = 1;
					$delivered		 = "Email sent successfully";
				}
				else
				{
					$delivered_flag	 = 0;
					$delivered		 = "Email not sent";
				}
				$elgId = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, EmailLog::EMAIL_INVOICE, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 1);
			}


			$attachments = json_encode($fileArray);
			if ($elgId != '' && $model->bkg_agent_id == null)
			{
				BookingLog::model()->createLog($model->bkg_id, "Cancellation Invoice sent to customer.", UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function nextVehicleReport($hours)
	{
		$success	 = false;
		//$email = Yii::app()->params['adminEmail'];
		$email		 = 'vendormanagement@aaocab.in';
		$email2		 = 'supply-team@aaocab.in';
		$delivered	 = '';
		if ($email != '')
		{
			$bdata = Booking::model()->getPickupInfoVehicles($hours);
			if (count($bdata) == 0)
			{
				goto end;
			}
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setTo($email, 'Admins');
			$mail->clearView();
			$style					 = ' style="padding: 5px;"';
			$style1					 = ' style="padding: 5px; text-align:center"';
			/* @var $model Booking */
			$tot					 = count($bdata);

			$body1 .= '<h3>Alert: Unapproved cars assigned for pickup in the next ' . $hours . ' hours</h3><br>
				Total ' . $tot . ' record(s) found <br>
			<table border="1" style="border-collapse: collapse">
			<thead>
			<tr>
				<th' . $style . '>	SL No.	</th>
				<th' . $style . '>	Booking ID	 </th>
				<th' . $style . '>	Customer Name</th>
				<th' . $style . '>	Customer Phone	</th>
				<th' . $style . '>	Pickup City	</th>
				<th' . $style . '>	Drop City</th>
				<th' . $style . '>	Pickup Datetime</th>
				<th' . $style . '>	Amount</th>
				<th' . $style . '>	Advance</th>
				<th' . $style . '>	Vendor Name</th>
				<th' . $style . '>	Vendor Phone</th>
				<th' . $style . '>	Driver Name</th>
				<th' . $style . '>	Driver Phone</th>
                                <th' . $style . '>	Driver Rating</th>
                                <th' . $style . '>	Driver Total Trips</th>
                                <th' . $style . '>	Cab Total Trips</th>
				<th' . $style . '>	Driver Approved</th>
				<th' . $style . '>	Cab Approved</th>
                                <th' . $style . '>	Reconfirmed</th>
                                <th' . $style . '>	Is Commercial</th>
				</tr>
				</thead>
				<tbody>';

			$body2	 = "";
			$k		 = 0;

			if (count($bdata) > 0)
			{
				foreach ($bdata as $b)
				{
					$model			 = Booking::model()->findByPk($b['bkg_id']);
					$vehicleModel	 = $model->bkgBcb->bcbCab->vhcType->vht_model;
					if ($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
					{
						$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
					}
					$cabmodel = $model->getBookingCabModel();

					$vndModel	 = Vendors::model()->findByPk($cabmodel->bcbVendor->vnd_id);
					$contactId	 = ContactProfile::getByEntityId($cabmodel->bcbVendor->vnd_id, UserInfo::TYPE_VENDOR);
					$phone		 = ContactPhone::getContactPhoneById($contactId);
					$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
					if ($response->getStatus())
					{
						$contactNo	 = $response->getData()->phone['number'];
						$countryCode = $response->getData()->phone['ext'];
					}
					$ucontact	 = ($contactNo != "") ? '+' . $countryCode . "-" . $contactNo : '';
					$pdate		 = date('d/m/Y h:i A', strtotime($model->bkg_pickup_date));
					$vname		 = ($cabmodel->bcb_vendor_id != "") ? $cabmodel->bcbVendor->vnd_name : '';
					$vphone		 = ($cabmodel->bcb_vendor_id != "") ? $phone : '';
					$dname		 = ($cabmodel->bcb_driver_id != '') ? $cabmodel->bcb_driver_name : '';
					$dphone		 = ($cabmodel->bcb_driver_id != '') ? $cabmodel->bcb_driver_phone : '';
					$cabType	 = ($cabmodel->bcb_cab_id != '') ? $vehicleModel : '';
					$cNumber	 = ($cabmodel->bcb_cab_id != '') ? $cabmodel->bcbCab->vhc_number : '';

					$driverRating	 = ($cabmodel->bcb_driver_rating != '') ? $cabmodel->bcb_driver_rating : '';
					$driverTrips	 = ($cabmodel->bcb_driver_rating != '') ? $cabmodel->bcb_driver_trips : '';
					$cabRating		 = ($cabmodel->bcb_cab_rating != '') ? $cabmodel->bcb_cab_rating : '';
					$cabTrips		 = ($cabmodel->bcb_cab_trips != '') ? $cabmodel->bcb_cab_trips : '';
					$bookAmount		 = ($model->bkgInvoice->bkg_total_amount > 0 && $model->bkgInvoice->bkg_total_amount > 0.00) ? 'Rs. ' . $model->bkgInvoice->bkg_total_amount : '0';
					$advanceAmount	 = ($model->bkgInvoice->bkg_advance_amount > 0 && $model->bkgInvoice->bkg_advance_amount > 0.00) ? 'Rs. ' . $model->bkgInvoice->bkg_advance_amount : '0';

					//				$driverStatus	 = $cabmodel->bcbVendor->vendorDrivers->drv_approved;
					//				$vehicleStatus	 = $cabmodel->bcbVendor->vendorVehicles->vhc_approved;

					$driverStatus	 = $cabmodel->bcbDriver->drv_approved;
					$vehicleStatus	 = $cabmodel->bcbCab->vhc_approved;

					$driverApproved	 = ($driverStatus == 1 ) ? 'Y' : 'N';
					$vehicleApproved = ($vehicleStatus == 1 ) ? 'Y' : 'N';
					$reconfirmStatus = trim($b['reconfirm_status']);
					$commercial		 = $b['is_commercial'];
					$body2			 .= '<tr>
					<td' . $style1 . '>' . ($k + 1) . '.</td>
					<td' . $style . '>' . $model->bkg_booking_id . '</td>
					<td' . $style . '>' . $model->bkgUserInfo->getUsername() . '</td>
					<td' . $style . '>' . $ucontact . '</td>
					<td' . $style . '>' . $model->bkgFromCity->cty_name . '</td>
					<td' . $style . '>' . $model->bkgToCity->cty_name . '</td>
					<td' . $style . '>' . $pdate . '</td>
									<td' . $style . '>' . $bookAmount . '</td>
									<td' . $style . '>' . $advanceAmount . '</td>
					<td' . $style . '>' . $vname . '</td>
					<td' . $style . '>' . $vphone . '</td>
					<td' . $style . '>' . $dname . '</td>
					<td' . $style . '>' . $dphone . '</td>
					<td' . $style . '>' . $driverRating . '</td>
									<td' . $style . '>' . $driverTrips . '</td>
									<td' . $style . '>' . $cabTrips . '</td>
									<td' . $style . '>' . $driverApproved . '</td>
									<td' . $style . '>' . $vehicleApproved . '</td>
									<td' . $style . '>' . $reconfirmStatus . '</td>
									<td' . $style . '>' . $commercial . '</td>
					</tr>';
					$k				 = ($k + 1);
				}
			}
			else
			{
				$body2 .= '<tr ><td' . $style1 . '></td><td' . $style . ' colspan="19">No records yet found.</td></tr>';
			}
			$body3	 = '</tbody></table>';
			$body	 = $body1 . $body2 . $body3;
			$mail->setBody($body);
			$mail->addCC($email2, 'Supply Team');
			$mail->clearLayout();
			$subject = 'Alert: Unapproved cars assigned for pickup in the next ' . $hours . ' hours';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered	 = "Email sent successfully";
				$success	 = true;
			}
			else
			{
				$delivered = "Email not sent";
			}
		}
		end:
		return $success;
	}

	public function nextScheduledPickupReport($hours)
	{
		//$email = Yii::app()->params['adminEmail'];
		$email = 'vendormanagement@aaocab.in';

		$delivered = '';
		if ($email != '')
		{
			$bdata = Booking::model()->getPickupInfoNext($hours);

			$this->email_receipient = $email;

			$mail = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setTo($email, 'Admins');

			$mail->clearView();
			$style	 = ' style="padding: 5px;"';
			$style1	 = ' style="padding: 5px; text-align:center"';
			/* @var $model Booking */
			$tot	 = count($bdata);

			$body1 .= '<h3>Pickup Details for Next ' . $hours . ' Hours</h3><br>
				Total ' . $tot . ' record(s) found <br>
			<table border="1" style="border-collapse: collapse">
			<thead>
			<tr>
				<th' . $style . '>	SL No.	</th>
				<th' . $style . '>	Booking ID	 </th>
				<th' . $style . '>	Customer Name</th>
				<th' . $style . '>	Customer Phone	</th>
				<th' . $style . '>	Pickup City	</th>
				<th' . $style . '>	Drop City</th>
				<th' . $style . '>	Pickup Datetime</th>
                                <th' . $style . '>	Tentative</th>
				<th' . $style . '>	Amount</th>
				<th' . $style . '>	Advance</th>
				<th' . $style . '>	Vendor Name</th>
				<th' . $style . '>	Vendor Phone</th>
                                <th' . $style . '>	Vendor Rating</th>
				<th' . $style . '>	Driver Name</th>
				<th' . $style . '>	Driver Phone</th>
                                <th' . $style . '>	Driver Rating</th>
                                <th' . $style . '>	Driver Total Trips</th>
                                <th' . $style . '>	Cab Total Trips</th>
				<th' . $style . '>	Driver Approved</th>
				<th' . $style . '>	Cab Approved</th>
                                <th' . $style . '>	Reconfirmed</th>
				</tr>
				</thead>
				<tbody>';

			$body2	 = "";
			$k		 = 0;
			foreach ($bdata as $b)
			{
				$k				 = ($k + 1);
				$model			 = Booking::model()->findByPk($b['bkg_id']);
				$vehicleModel	 = $model->bkgBcb->bcbCab->vhcType->vht_model;
				if ($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
				{
					$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
				}
				$cabmodel	 = $model->getBookingCabModel();
				$vndModel	 = Vendors::model()->findByPk($cabmodel->bcbVendor->vnd_id);
				$contactId	 = ContactProfile::getByEntityId($cabmodel->bcbVendor->vnd_id, UserInfo::TYPE_VENDOR);
				$phone		 = ContactPhone::getContactPhoneById($contactId);
				$ucontact	 = ($model->bkgUserInfo->bkg_contact_no != "") ? '+' . $model->bkgUserInfo->bkg_country_code . "-" . $model->bkgUserInfo->bkg_contact_no : '';
				$pdate		 = date('d/m/Y h:i A', strtotime($model->bkg_pickup_date));
				$isTentative = ($model->bkgPref->bkg_tentative_booking == 1) ? 'Yes' : 'No';
				$vname		 = ($cabmodel->bcb_vendor_id != "") ? $cabmodel->bcbVendor->vnd_name : '';
				$vphone		 = ($cabmodel->bcb_vendor_id != "") ? $phone : '';

				$vrating = ($vndModel->vendorStats->vrs_vnd_overall_rating != '') ? $vndModel->vendorStats->vrs_vnd_overall_rating : '';
				$dname	 = ($cabmodel->bcb_driver_id != '') ? $cabmodel->bcb_driver_name : '';
				$dphone	 = ($cabmodel->bcb_driver_id != '') ? $cabmodel->bcb_driver_phone : '';
				$cabType = ($cabmodel->bcb_cab_id != '') ? $vehicleModel : '';
				$cNumber = ($cabmodel->bcb_cab_id != '') ? $cabmodel->bcbCab->vhc_number : '';

				$driverRating	 = ($cabmodel->bcb_driver_rating != '') ? $cabmodel->bcb_driver_rating : '';
				$driverTrips	 = ($cabmodel->bcb_driver_rating != '') ? $cabmodel->bcb_driver_trips : '';
				$cabRating		 = ($cabmodel->bcb_cab_rating != '') ? $cabmodel->bcb_cab_rating : '';
				$cabTrips		 = ($cabmodel->bcb_cab_trips != '') ? $cabmodel->bcb_cab_trips : '';
				$bookAmount		 = ($model->bkgInvoice->bkg_total_amount > 0 && $model->bkgInvoice->bkg_total_amount > 0.00) ? 'Rs. ' . $model->bkgInvoice->bkg_total_amount : '0';
				$advanceAmount	 = ($model->bkgInvoice->bkg_advance_amount > 0 && $model->bkgInvoice->bkg_advance_amount > 0.00) ? 'Rs. ' . $model->bkgInvoice->bkg_advance_amount : '0';

				$driverStatus	 = $cabmodel->bcbDriver->drv_approved;
				$vehicleStatus	 = $cabmodel->bcbCab->vhc_approved;
				$driverApproved	 = ($driverStatus == 1 ) ? 'Y' : 'N';
				$vehicleApproved = ($vehicleStatus == 1 ) ? 'Y' : 'N';
				$reconfirmStatus = trim($b['reconfirm_status']);
				$body2			 .= '<tr>
				<td' . $style1 . '>' . ($k) . '.</td>
				<td' . $style . '>' . $model->bkg_booking_id . '</td>
				<td' . $style . '>' . $model->bkgUserInfo->getUsername() . '</td>
				<td' . $style . '>' . $ucontact . '</td>
				<td' . $style . '>' . $model->bkgFromCity->cty_name . '</td>
				<td' . $style . '>' . $model->bkgToCity->cty_name . '</td>
				<td' . $style . '>' . $pdate . '</td>
                                <td' . $style . ' style="text-align:center">' . $isTentative . '</td>
                                <td' . $style . '>' . $bookAmount . '</td>
                                <td' . $style . '>' . $advanceAmount . '</td>
				<td' . $style . '>' . $vname . '</td>
				<td' . $style . '>' . $vphone . '</td>
                                <td' . $style . ' style="text-align:center">' . $vrating . '</td>
				<td' . $style . '>' . $dname . '</td>
				<td' . $style . '>' . $dphone . '</td>
				<td' . $style . ' style="text-align:center">' . $driverRating . '</td>
                                <td' . $style . '>' . $driverTrips . '</td>
                                <td' . $style . '>' . $cabTrips . '</td>
                                <td' . $style . ' style="text-align:center">' . $driverApproved . '</td>
                                <td' . $style . ' style="text-align:center">' . $vehicleApproved . '</td>
                                <td' . $style . '>' . $reconfirmStatus . '</td>
				</tr>';
			}
			$body3	 = '</tbody></table>';
			$body	 = $body1 . $body2 . $body3;
			$mail->setBody($body);
			$mail->clearLayout();
			$subject = 'Pickup Details for Next ' . $hours . ' Hours';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo $delivered;
		}
	}

	public function vendorVerifyCabDriverLink($vendor)
	{
		$model	 = Vendors::model()->findByPk($vendor);
		/* @var $model Vendors */
		$email	 = $model->vnd_email;
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		if ($email != '')
		{
			// $email = 'abhishek@epitech.in';
			$this->email_receipient	 = $email;
			// $mail = new YiiMailer();
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			//  $mail->setView('signup');
			$vendorName				 = $model->vnd_name;
			$mail->setLayout('mail');
			//  $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $vendorName);
			$subject				 = 'Verification link from Gozo Cabs';
			$body					 = "Please tap on the link below to verify your cab(s) and driver(s)<br><br><a href=" . Yii::app()->createAbsoluteUrl('vendor/vehicle/vehiclelist', ['id' => $vendor, 'code' => Yii::app()->shortHash->hash($vendor)]) . ">" . Yii::app()->createAbsoluteUrl('vendor/vehicle/vehiclelist', ['id' => $vendor, 'code' => Yii::app()->shortHash->hash($vendor)]) . "</a>";
			$mail->isHTML(true);
			$mail->setSubject($subject);
			$mail->setBody($body);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Vendor;

			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function vendorWelcomeEmail($email, $user_id, $password)
	{
		$model					 = Users::model()->resetScope()->findByPk($user_id);
		$vendorName				 = $model->usr_name;
		$this->email_receipient	 = $email;
		$mail					 = EIMailer::getInstance(EmailLog::SEND_VENDOR_BATCH_EMAIL);
		$mail->setLayout('mail');
		$mail->AddReplyTo("vendor@aaocab.in", "Gozo vendor support");
		$mail->setTo($email, $vendorName);
		$subject				 = 'Your username & password';
		$msg					 = 'Dear ' . $name . ',<br/>Thank you for joining the Gozo Cabs supply partner network.<br/>We have created a username and password for you.'
				. '<br/>Your username: ' . $vendorName . '<br/>Password: ' . $password . '<br/><br/><a href="https://play.google.com/store/apps/details?id=com.aaocab.vendor">Download the Gozo Partner app from the Android play store</a>'
				. '<br/><br/>Login to the Gozo partner app with the above username and password.';

		$mail->setTo($email, $vendorName);
		$mail->isHTML(true);
		$mail->setSubject($subject);
		$mail->setBody($msg);
		if ($mail->sendMail())
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$body		 = $mail->Body;
		$usertype	 = EmailLog::Vendor;
		$refId		 = $model->vnd_id;
		$refType	 = EmailLog::REF_VENDOR_ID;
		emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_VENDOR_BATCH_EMAIL);
	}

	public function attachTaxiMail($vendor, $password)
	{
		$model		 = Vendors::model()->resetScope()->findByPk($vendor);
		/* @var $model Vendors */
		$contactId	 = ContactProfile::getByEntityId($model->vnd_id, UserInfo::TYPE_VENDOR);
		$email		 = ContactEmail::model()->getContactEmailById($contactId);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}

		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$vendorName				 = $model->vndContact->getName();
			$vendorCompany			 = $vendorName;

			if ($vendorName != '' || $vendorName != NULL)
			{
				$name = $vendorName;
			}
			else
			{
				$name = $email;
			}


			$agreementLink	 = '<a href="http://www.aaocab.com/operator_agreement.pdf" target="_blank">Agreement</a>';
			$mail->setLayout('mail');
			$mail->AddReplyTo("vendor@aaocab.in", "Gozo vendor support");
			//  $mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $vendorName);
			$subject		 = 'Welcome to Gozo cabs Vendor partner network';
			$msg			 = 'Dear ' . $name . ',<br/>Thank you for joining the Gozo Cabs supply partner network.'
					. '<br/><br/><a href="https://play.google.com/store/apps/details?id=com.aaocab.vendor">Download the Gozo Partner app from the play store</a>'
					. '<br/><br/>Login to the Gozo partner app using your google login, then<br/>1. Update your profile & upload vendor documents in the vendor profile section of the app<br/>2. Add information and upload papers for car(s) & driver(s)<br/>3. Wait for our team to activate your profile<br>4. We will start sending you business after your account is activated.<br/><br/>Please add information about ONLY the cars that you own and the  drivers that will drive under your partner account.<br/><p></p><p></p><br/><br/><br/><p><b>We will need the following information for car -</b></p>'
					. '1. Clear & readable picture of RC<br/>2. Clear & readable picture of Insurance<br/>3. Clear & readable picture of fitness certificate<br/>'
					. '4. Picture of Commercial permit<br/>5. Picture of FRONT and BACK of car with readable license plate<br/>6. Clear & reaable picture of PUC<br/><br/><p><b>For driver, we need - </b></p>1. Passport photo of Driver<br/>2. Clear & readable picture of Driver\'s license<br/>'
					. '3. Two types of address proof of driver (PAN card and Voter ID)<br/><br/><br/><p><b>For Vendor we will need - </b></p>'
					. '1. Clear picture of Vendor\'s Driver license<br>'
					. '2. Clear picture of Vendor PAN card<br/>'
					. '3. Information of Bank account where you want us to send account settlment payments<br/>'
					. '4. A security deposit of ₹2000/- payable to Gozo Technologies Private limited<br/>'
					. '5. Two address proof for vendor (Voter ID  or Aadhar card or Driver license or PAN card)<br/>'
					. '<p>If you have any questions<a href="mailto:vendor@aaocab.in">email us</a></p>';
			$mail->isHTML(true);
			$mail->setSubject($subject);
			$mail->setBody($msg);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Vendor;
			$refId		 = $model->vnd_id;
			$refType	 = EmailLog::REF_VENDOR_ID;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function paperworkDriverCarEmail($emailSubject, $emailBody, $vendorName = '', $vendorEmail = '', $vendorID = '')
	{
		if (isset($vendorEmail) && $vendorEmail != '')
		{
			$mail		 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$body		 = $emailBody;
			$userName	 = $vendorName;
			$email		 = $vendorEmail;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
			{
				return false;
			}
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setTo($email, $userName);
			$subject = $emailSubject;
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Vendor;
			$emailType	 = EmailLog::EMAIL_MISSING_DRIVER_CAR;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$refId		 = $vendorID;
			emailWrapper::createLog($email, $subject, $body, '', $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			echo $delivered . "-" . $userName;
			echo "\n";
			return true;
		}
		else
		{
			return false;
		}
	}

	public function beforePickUpMail($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		//$hourDiff = round((strtotime($model->bkg_pickup_date) - strtotime(date('Y-m-d H:i:s')))/3600);
		$pickupTime	 = strtotime($model->bkg_pickup_date);
		$timeLeft	 = round(($pickupTime - time()) / 3600);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email		 = $response->getData()->email['email'];
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		$userName	 = $firstName . ' ' . $lastName;
		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
//remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::REMINDER_ADVANCE);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
// remove comment when agent panel notificaion will be live
		}
		if ((isset($email) && $email != '') || count($emailArr) > 0)
		{
			$hash					 = Yii::app()->shortHash->hash($bkgId);
			$reconfirm_url			 = 'https://aaocab.com/bkconfirm/' . $bkgId . '/' . $hash;
			//$payment_link			 = 'https://aaocab.com/bkpn/' . $bkgId . '/' . $hash;
			$payment_link			 = BookingUser::getPaymentLinkByEmail($bkgId);
			$subject				 = '[Action required] Get your bags ready. Your Gozo is going to pick you in ' . $timeLeft . ' hours.';
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setView('reconfirmbooking');
			$mail->setData(
					array('model'			 => $model,
						'pay_url'		 => $payment_link,
						'reconfirm_url'	 => $reconfirm_url
					)
			);
			$mail->setLayout('mail1');
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $userName);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::RECONFIRM_BEFORE_PICKUP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;

			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
			}
			//booking log
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Reconfirm Request Sent", UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			echo $delivered . "-[" . $model->bkg_booking_id . "]-" . $userName;
			echo "\n";
			return true;
		}
		else
		{
			return false;
		}
	}

	public function priceGuaranteeMail($bkgId, $day, $logType = '')
	{
		$model = Booking::model()->findByPk($bkgId);
		if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)) || $model->bkg_agent_id > 0)
		{
			return false;
		}
		$oldModel	 = clone $model;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return false;
		}

		if ($email != '')
		{
			echo $email . "-" . $bkgId . "\n";

			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('priceguarantee');
			$bookarr['userName']	 = $model->bkgUserInfo->getUsername();
			$bookarr['day']			 = $day;
			$hash					 = Yii::app()->shortHash->hash($model->bkg_id);
			$url					 = 'aaocab.com' . Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
			//$payurl = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => $hash]);
			//$payurl					 = 'https://aaocab.com/bkpn/' . $model->bkg_id . '/' . $hash;
			$payurl					 = Filter::shortUrl(BookingUser::getPaymentLinkByEmail($model->bkg_id));
			$mail->setData(
					array('model' => $model, 'payurl' => $payurl, 'arr' => $bookarr)
			);
			$subject				 = 'Booking ' . $model->bkg_booking_id . ' Pay now. Best price guaranteed.';
			$mail->setLayout('mail1');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			//email log
			$body	 = $mail->Body;
			$refType = EmailLog::REF_BOOKING_ID;
			$refId	 = $model->bkg_id;
			$elgId	 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, EmailLog::Consumers, $delivered, EmailLog::EMAIL_PRICE_GUARANTEE, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, 0);
			if ($elgId != '')
			{
				/* @var $bookPref BookingPref */
				$modelBookPref1 = BookingPref::model()->getByBooking($model->bkg_id);
				if ($modelBookPref1 != '')
				{
					$modelBookPref = $modelBookPref1;
				}
				else
				{
					$modelBookPref = new BookingPref();
				}
				$modelBookPref->bpr_bkg_id				 = $model->bkg_id;
				$modelBookPref->bkg_keep_fresh_msg_cnt	 = ($modelBookPref->bkg_keep_fresh_msg_cnt + 1);
				if (!$modelBookPref->save())
				{
					throw new Exception("Failed to save booking preferences");
				}

				//booking log
				if ($model->bkg_id != '')
				{
					$desc							 = $subject;
					$eventId						 = BookingLog::EMAIL_SENT;
					$params							 = [];
					$params['blg_ref_id']			 = $elgId;
					$params['blg_booking_status']	 = $model->bkg_status;
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
				}
			}
			echo $delivered . " - " . $model->bkg_id . "\n";
		}
	}

	public function preAutoCancelbeforePickUpMail($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$pickupTime	 = strtotime($model->bkg_pickup_date);
		$timeLeft	 = round(($pickupTime - time()) / 3600);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email		 = $response->getData()->email['email'];
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
		}
		//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
		$userName	 = $firstName . ' ' . $lastName;
		$hash		 = Yii::app()->shortHash->hash($bkgId);
		$url		 = 'aaocab.com/bkconfirm/' . $bkgId . '/' . $hash;

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
//remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::CANCEL_TRIP);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
// remove comment when agent panel notificaion will be live
		}

		if ((isset($email) && $email != '') || count($emailArr) > 0)
		{
			$hash		 = Yii::app()->shortHash->hash($bkgId);
			$bookingId	 = $model->bkg_booking_id;
			$body		 = 'Dear ' . $userName . ',<br/>Your trip ' . $model->bkg_booking_id . ' will cancelled becuase we have not  received trip reconfirmation.'
					. '<br/>If you feel this cancellation was done in ERROR, please click the link below to RECONFIRM THE TRIP WITHIN THE NEXT 2 HOURS.'
					. '<br/>Reconfirmation link -->  ' . $url . '.';
			$body		 .= '<br/><br/>Thank you,<br/>aaocab Team';
			$subject	 = 'Your trip ' . $bookingId . ' will soon be cancelled. Reconfirm not received.';
			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == 0 || $model->bkg_agent_id == '')
			{
				$mail->setTo($email, $userName);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::PRE_AUTO_CANCEL_BEFORE_PICKUP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;

			//email log
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, 0);
			}

			//booking log
			BookingLog::model()->createLog($bkgId, "Auto cancel warning has been sent", UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			echo $delivered . "[$model->bkg_booking_id]" . "-" . $userName;
			echo "\n";
			return true;
		}
		else
		{
			return false;
		}
	}

	public function remainderReturnTripMail($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0) || $model->bkg_agent_id > 0)
		{
			return false;
		}
		$status		 = 6;
		$booktype	 = 1;
		$sql		 = "SELECT booking.bkg_id,booking.bkg_booking_id,booking_user.bkg_user_fname,booking_user.bkg_user_lname,booking_user.bkg_country_code,booking_user.bkg_contact_no,booking_user.bkg_user_email,booking.bkg_status,booking_invoice.bkg_total_amount,booking_invoice.bkg_advance_amount,booking_invoice.bkg_due_amount,booking.bkg_pickup_address,booking.bkg_drop_address,booking.bkg_pickup_date,booking.bkg_instruction_to_driver_vendor,fromCity.cty_name as from_city,toCity.cty_name as to_city,fromBrt.brt_pickup_datetime as fromPickupDateTime,toBrt.brt_pickup_datetime as toPickupDateTime
                        FROM
                        (SELECT brt_bcb_id, MIN(brt_id) as minBrt, MAX(brt_id) as maxBrt FROM booking_route
                            WHERE brt_active=1 GROUP BY brt_bcb_id) brtBCB
                                INNER JOIN booking_route fromBrt ON fromBrt.brt_id=minBrt
                                INNER JOIN booking_route toBrt ON toBrt.brt_id=maxBrt
                                INNER JOIN booking_cab ON bcb_id=brtBCB.brt_bcb_id
                                INNER JOIN booking ON bkg_bcb_id=bcb_id
                                INNER JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id
                                INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id=booking.bkg_id
                                 JOIN cities fromCity ON fromCity.cty_id=fromBrt.brt_from_city_id
                                 JOIN cities toCity ON toCity.cty_id=toBrt.brt_to_city_id WHERE booking.bkg_status=$status AND booking.bkg_booking_type=$booktype AND booking.bkg_id=$bkgId";
		$data		 = Yii::app()->db->createCommand($sql)->queryRow();
		if (count($data) > 0)
		{
			$body	 = 'Dear ' . $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'] . ',<br/>';
			$body	 .= '<br/>Thanks for traveling with Gozo on your trip ' . $data['bkg_booking_id'] . ' from ' . $data['from_city'] . ' to ' . $data['to_city'] . ' .';
			$body	 .= '<br/>Please reply to this email with your next trip details. As a thank you for traveling with us, we will provide you a special fare for your next trip if booked within next 24 hours.';
			$body	 .= '<br/>You can also book on our app directly for our best rates and enter code AGAIN250 to avail this offer.';
			$body	 .= '<br/><br/>Thank you,
                            <br/>Team Gozo';

			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$userName	 = $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'];
			$email		 = $row['bkg_user_email'];
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_PROMOTIONAL))
			{
				return false;
			}

			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setTo($email, $userName);
			$subject = 'Let us take you on your next journey';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::EMAIL_RETURN_TRIP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $data['bkg_id'];
			emailWrapper::createLog($email, $subject, $body, '', $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$this->setReturnTripStatus($data['bkg_id']);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function vendorInvoice($vndId, $date1, $date2, $vendorAmount = 0, $attachments)
	{
		$model = Vendors::model()->findByPk($vndId);
		if (isset($model->vnd_email) && $model->vnd_email != '')
		{
			$subject = 'Gozo Invoice for ' . $model->vnd_name . ' from ' . $date1 . ' to ' . $date2 . '';
			$body	 = 'Dear ' . $model->vnd_owner . ',<br/><br/>
                        Attached attached invoice statement from ' . $date1 . ' to ' . $date2 . '.<br>';
			if (isset($vendorAmount) && $vendorAmount > 0)
			{
				$body .= 'Your payment of ₹' . $vendorAmount . ' is DUE NOW.';
			}
			//$body .= '<br/><br/>Please have our Bank Details embedded in the body of the mail accompanying Invoice / Account Statement being mailed to Vendors, from State Bank of India to :';
			$body .= '<br/><br/>Please note our bank details below. Send all payments to the below address and inform us with the details of your payment at accounts@aaocab.in';

			$body	 .= '<br/><br/>Beneficiary Name: <b>Gozo Technologies Private Limited</b>';
			$body	 .= '<br/>Bank: <b>HDFC BANK LTD</b>';
			$body	 .= '<br/>Branch: <b>Badshahpur, Gurgaon</b>';
			$body	 .= '<br/>A/c number: <b>50200020818192</b>';
			$body	 .= '<br/>IFSC Code: <b>HDFC0001098</b>';
			$body	 .= '<br/><br/>For all queries please write to accounts@aaocab.in <mailto:accounts@aaocab.in>';

			$body	 .= '<br/><br/>Thank you,';
			$body	 .= '<br/>Team Gozo';

			$mail		 = EIMailer::getInstance(EmailLog::SEND_VENDOR_BATCH_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$sentEmail	 = $model->vnd_email;
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Vendor;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$refId		 = $model->vnd_id;
			$elgId		 = emailWrapper::createLog($sentEmail, $subject, $body, '', $usertype, $delivered, "", $refType, $refId, EmailLog::SEND_VENDOR_BATCH_EMAIL, 1, $attachments);
			if ($elgId != '')
			{
				VendorsLog::model()->createLog($vendorId, $subject, UserInfo::model(), VendorsLog::MAIL, false, ['vlg_ref_id' => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function vendorInvoiceEmail($emailSubject, $emailBody, $toEmail, $file = '', $file2 = '', $vendorId = '', $attachments, $elg_type = '', $isAgreement = '')
	{

		if (isset($toEmail) && $toEmail != '')
		{
			$subject	 = $emailSubject;
			$body		 = $emailBody;
			$mail		 = EIMailer::getInstance(EmailLog::SEND_VENDOR_BATCH_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$sentEmail	 = $toEmail;
			//$fileArray = [0 => $file, 1 => $file2];
			//$attachments = json_encode($fileArray);
			if (Unsubscribe::isUnsubscribed($sentEmail, Unsubscribe::CAT_TRANSACTIONAL))
			{
				return false;
			}

			$mail->setTo($sentEmail, 'aaocab Admin');
			$bccMail = ($isAgreement == 1) ? Yii::app()->params['mail']['agreementMail']['Username'] : Yii::app()->params['mail']['info']['Username'];
			$mail->setBcc($bccMail);
			$mail->setBody($body);
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Vendor;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$refId		 = $vendorId;
			$elgId		 = emailWrapper::createLog($sentEmail, $subject, $body, '', $usertype, $delivered, $elg_type, $refType, $refId, EmailLog::SEND_VENDOR_BATCH_EMAIL, 1, $attachments);
			if ($elgId != '')
			{
				VendorsLog::model()->createLog($vendorId, $subject, UserInfo::model(), VendorsLog::MAIL, false, ['vlg_ref_id' => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function agentAgreementEmail($emailSubject, $emailBody, $toEmail, $file = '', $file2 = '', $agentId = '', $attachments, $elg_type = '', $isAgreement = '')
	{

		if (isset($toEmail) && $toEmail != '')
		{
			$subject	 = $emailSubject;
			$body		 = $emailBody;
			$mail		 = EIMailer::getInstance(EmailLog::SEND_VENDOR_BATCH_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$sentEmail	 = $toEmail;
			//$fileArray = [0 => $file, 1 => $file2];
			//$attachments = json_encode($fileArray);
			if (Unsubscribe::isUnsubscribed($sentEmail, Unsubscribe::CAT_TRANSACTIONAL))
			{
				return false;
			}

			$mail->setTo($sentEmail, 'aaocab Admin');
			$bccMail = ($isAgreement == 1) ? Yii::app()->params['mail']['agreementMail']['Username'] : Yii::app()->params['mail']['info']['Username'];
			$mail->setBcc($bccMail);
			$mail->setBody($body);
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Agent;
			$refType	 = EmailLog::REF_AGENT_ID;
			$refId		 = $agentId;
			$elgId		 = emailWrapper::createLog($sentEmail, $subject, $body, '', $usertype, $delivered, $elg_type, $refType, $refId, EmailLog::SEND_AGENT_EMAIL, 1, $attachments);
			if ($elgId != '')
			{
				
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function planDelayedEmail($bkgId, $rescheduleDate, $rescheduleTime, $rescheduleAddr)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
		}
		if ($model->bkg_id != '')
		{
			$fromCity		 = $model->bkgFromCity->cty_name;
			$toCity			 = $model->bkgToCity->cty_name;
			$customerName	 = ($firstName . " " . $lastName);
			$msg			 = 'We are writing to inform you of some changes made to your trip. If you did not request these, contact our support center.<br/><br/> <b>New details:</b><br/><br/>' .
					'<table border="1px" cellpadding="5" width="75%">
                        <tr><td>Customer Name : </td><td>' . $customerName . '</td></tr>
                        <tr><td>Route </td><td>(' . $fromCity . ' to ' . $toCity . ')</td></tr>
                        <tr><td>Original Pickup date</td><td>' . date("d/m/Y", strtotime($model->bkg_pickup_date)) . '</td></tr>
                        <tr><td>Original Pickup time</td><td>' . date('g:i A', strtotime($model->bkg_pickup_date)) . '</td></tr>
                        <tr><td>Original Pickup address</td><td>' . $model->bkg_pickup_address . '</td></tr>
                        <tr><td>Original Drop address</td><td>' . $model->bkg_drop_address . '</td></tr>
                        <tr><td>New pickup date</td><td>' . $rescheduleDate . '</td></tr>
                        <tr><td>New pickup time</td><td>' . $rescheduleTime . '</td></tr>
                        <tr><td>New pickup address</td><td>' . $rescheduleAddr . '</td></tr>
                    </table><br/>';
			$mail			 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$toMail			 = Yii::app()->params['bookingEmail'];
			$mail->setTo($toMail, 'Gozo Cabs');
			$mail->setBody($msg);
			$subject		 = '[Booking ID: ' . $model->bkg_booking_id . '] | Changes made to your planned pickup';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body	 = $msg;
			$refType = EmailLog::REF_USER_ID;
			$refId	 = $model->bkg_id;
			emailWrapper::createLog($toMail, $subject, $body, $model->bkg_booking_id, EmailLog::Admin, $delivered, EmailLog::EMAIL_RESCHEDULE_REQUEST, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function refundFromWalletToSource($bkgId, $refundAmt, $paymentType)
	{
		$data	 = BookingSub::getUserDetails($bkgId);
		$transID = PaymentGateway::getTXNIDbyBkgId($data['bkg_id'], 1);
		if ($data != '' && count($data) > 0)
		{
			$model		 = Booking::model()->findByPk($bkgId);
			$body		 = 'Dear ' . $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'] . ',<br/>';
			$body		 .= '<br/>You have requested that the amount credited to your Gozo account for Booking ID  ' . $data['bkg_booking_id'] . ' be refunded to you at - ';
			$body		 .= "<br/>Account Type: $paymentType";
			$body		 .= "<br/>We have processed the refund for ₹$refundAmt";
			$body		 .= "<br/><br/>Please be patient as sometimes it may take upto 15 business days for your bank to release these funds to you.";
			$body		 .= "<br/>Gozo's transaction confirmation number for this refund is: $transID";
			$body		 .= "<br/>If you have any questions, write to us at accounts@aaocab.in.";
			$body		 .= '<br/><br/>Regards, Team Gozo';
			$subject	 = 'Refund issued. Booking ID:' . $data['bkg_booking_id'];
			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$userName	 = $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'];
			$email		 = $data['bkg_user_email'];
			$usertype	 = EmailLog::Consumers;
			$bccMail	 = Yii::app()->params['cancellationEmail'];
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $userName);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}

			$emailType	 = EmailLog::EMAIL_REFUND_WALLET_TO_SOURCE;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $data['bkg_id'];
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $data['bkg_booking_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
				$model->bkgTrail->bkg_cancellation_email_count += 1;
				$model->bkgTrail->save();
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function refundFromWalletToBank($refId, $refundAmt, $txnId, $paymentType, $refType)
	{
		if ($refType == 1)
		{
			$userModel		 = Users::model()->findByPk($refId);
			$contactModel	 = Contact::model()->findByPk($userModel->usr_contact_id);
			$emailAdd		 = ContactEmail::getEmailByUserId($refId);
		}
		else
		{
			$vendorModel	 = Vendors::model()->findByPk($refId);
			$contactId		 = ContactProfile::getByEntityId($vendorModel->vnd_id, UserInfo::TYPE_VENDOR);
			$contactModel	 = Contact::model()->findByPk($contactId);
			$emailAdd		 = ContactEmail::getById($contactId);
		}
		if ($contactModel)
		{
			$body		 = 'Dear ' . $contactModel->ctt_first_name . ' ' . $contactModel->ctt_last_name . ',<br/>';
			$body		 .= '<br/>You have requested that the amount credited to your Gozo account be transfered to your bank account';
			$body		 .= "<br/>Account Type: $paymentType";
			$body		 .= "<br/>We have processed the refund for ₹$refundAmt";
			$body		 .= "<br/><br/>Please be patient as sometimes it may take upto 15 business days for your bank to release these funds to you.";
			$body		 .= "<br/>Our transaction confirmation number for this refund is: $txnId";
			$body		 .= "<br/>If you have any questions, write to us at accounts@aaocab.in.";
			$body		 .= '<br/><br/>Regards, Team Gozo';
			$subject	 = 'REFUND PROCESSED: Check your bank account';
			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$userName	 = $contactModel->ctt_first_name . ' ' . $contactModel->ctt_last_name;
			$email		 = $emailAdd;
			if ($refType == 1)
			{
				$usertype = EmailLog::Consumers;
			}
			else
			{
				$usertype = EmailLog::Vendor;
			}
			$bccMail = Yii::app()->params['cancellationEmail'];
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}

			$emailType = EmailLog::EMAIL_REFUND_WALLET_TO_BANK;
			if ($refType == 1)
			{
				$refType = EmailLog::REF_USER_ID;
				$refId	 = $refId;
			}
			else
			{
				$refType = EmailLog::REF_VENDOR_ID;
				$refId	 = $refId;
			}
			$elgId = emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function bookingCancellationMail($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if (!BookingTrail::model()->checkCancellationMailEligibity($model))
		{
			return false;
		}
//		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
//		{
//			return false;
//		}

		$data = BookingSub::getCancellationDetails($bkgId);

		if ($data['bkg_cancel_charge'] == 0)
		{
			return false;
		}

		$cancelDate	 = $model->bkgTrail->btr_cancel_date;
		$pickupDate	 = $model->bkg_pickup_date;

		$serviceDesc = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		$sccDesc	 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label;

		$cabType = $serviceDesc . ' (' . $sccDesc . ')';

		if ($data != '' && count($data) > 0)
		{
			$transID				 = PaymentGateway::getTXNIDbyBkgId($data['bkg_id'], 1);
			$emailArr				 = [];
			$logArr					 = [];
			//check driver info show or not
			$data['cabDriverShow']	 = BookingLog::chkDriverDetailsShow($bkgId);
			if ($model->bkg_agent_id > 0)
			{
				//remove comment when agent panel notificaion will be live
				$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::CANCEL_TRIP);
				$logArr	 = $logArr1['email'];
				foreach ($logArr as $key => $value)
				{
					$emailArr[$value['email']] = $value['name'];
					if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
					{
						unset($emailArr[$value['email']]);
					}
				}
				// remove comment when agent panel notificaion will be live
			}
			$invoiceLink = Filter::shortUrl(BookingInvoice::getInvoiceUrl($bkgId));
			$fileArray	 = [0 => ['URL' => $invoiceLink]];

			$data['cancellationCharge']	 = ($data['bkg_cancel_charge'] > 0 ) ? ($data['bkg_cancel_charge'] + $data['bkg_cancel_gst']) : 0;
			$data['useUserWallet']		 = Config::get('user.useWallet');
			$data['invoiceLink']		 = "<a href='$invoiceLink' target='_blank'>Invoice</a>";
			$data['bkg_refund_amount']	 = $data['bkg_refund_amount'];
			$data['cancellationDate']	 = $cancelDate;
			$data['pickupDate']			 = $pickupDate;
			$data['cabType']			 = $cabType;
			//  put all gozo side reason id in an array
			$reasonIds					 = [9, 16, 17, 18, 21, 22];
			$data['reasonIds']			 = $reasonIds;
			//$data['bkg_cancel_id']		 = $data['bkg_cancel_id'];

			$email	 = $data['bkg_user_email'];
			//$this->email_receipient	 = $email;
			$mail	 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$bccMail = Yii::app()->params['cancellationEmail'];
			$mail->clearView();
			$mail->setLayout('mail');
			$subject = 'Cancellation Invoice - Booking ID: ' . $model->bkg_booking_id;
			if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setView('customer_invoice');
				$mail->setTo($email, $model->bkgUserInfo->getUsername());

				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$mail->setData(['data' => $data, "email_receipient" => $email, 'userId' => $model->bkgUserInfo->bkg_user_id]);

				$mail->setSubject($subject);
				if ($mail->sendMail(0))
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
			}
			else
			{
				if (count($emailArr) > 0)
				{
					foreach ($logArr as $key => $value)
					{
						$usertype = $key;
						if (in_array($usertype, [6, 7]))
						{
							$mail->setView('partner_invoice');
							$subject = 'Partner Cancellation Invoice – Booking ID: ' . $model->bkg_booking_id;
						}
						else
						{
							$mail->setView('customer_invoice');
							$subject = 'Cancellation Invoice – Booking ID : ' . $model->bkg_booking_id;
						}
						$email = $value['email'];

						$toArr					 = [];
						$toArr[$value['email']]	 = $value['name'];
						$mail->setTo($toArr);
						$mail->setData(['data' => $data, "email_receipient" => $value['email'], 'userId' => $model->bkgUserInfo->bkg_user_id]);
						$mail->setSubject($subject);
						if ($mail->sendMail(0))
						{
							$delivered = "Email sent successfully";
						}
						else
						{
							$delivered = "Email not sent";
						}
					}
				}
			}


			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_CANCEL_TRIP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $data['bkg_id'];
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$logMessage	 = 'Cancellation Invoice sent to partner email.';
					$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$logMessage	 = 'Cancellation Invoice sent to customer email.';
				$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			}
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, $logMessage, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
				$model->bkgTrail->bkg_cancellation_email_count += 1;
				$model->bkgTrail->save();
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function cancellationRefundMail($bkgId, $refundAmount)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$eventIds	 = [10, 82, 122];
		$eventIds	 = implode(',', $eventIds);
		$sql		 = "SELECT 	booking.bkg_id,
                booking.bkg_booking_id,
                booking_user.bkg_user_fname,
		        booking_user.bkg_user_email,
                booking_user.bkg_user_lname,
                booking.bkg_cancel_id,
                booking.bkg_cancel_delete_reason,
                blg_user_type,
                blg_desc,
                cancel_reasons.cnr_reason,(
                    CASE WHEN blg_user_type=1 THEN 'Consumer'
                         WHEN blg_user_type=4 THEN 'Admin'
                         WHEN blg_user_type=10 THEN 'System'
                     END
                ) as user_type
                FROM `booking`
                INNER JOIN `booking_user` ON `bui_bkg_id` = `booking`.bkg_id
                INNER JOIN (
                    SELECT booking_log.blg_user_type, booking_log.blg_booking_id, booking_log.blg_desc
                    FROM `booking_log`
                    WHERE booking_log.blg_event_id IN ($eventIds)
                )blg ON blg.blg_booking_id=booking.bkg_id
                LEFT JOIN `cancel_reasons` ON cancel_reasons.cnr_id=booking.bkg_cancel_id AND cancel_reasons.cnr_active=1
                WHERE booking.bkg_id=$bkgId AND booking.bkg_status IN(9)";
		$data		 = Yii::app()->db->createCommand($sql)->queryRow();
		if ($data != '' && count($data) > 0)
		{
			$transID	 = PaymentGateway::getTXNIDbyBkgId($data['bkg_id'], 1);
			$emailArr	 = [];
			$logArr		 = [];
			if ($model->bkg_agent_id > 0)
			{
				//remove comment when agent panel notificaion will be live
				$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::CANCEL_TRIP);
				$logArr	 = $logArr1['email'];
				foreach ($logArr as $key => $value)
				{
					$emailArr[$value['email']] = $value['name'];
					if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
					{
						unset($emailArr[$value['email']]);
					}
				}
				// remove comment when agent panel notificaion will be live
			}

			$body		 = 'Dear ' . $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'] . ',<br/>';
			$body		 .= '<br/>The cancellation for your booking ' . $data['bkg_booking_id'] . ' has been processed.';
			$body		 .= '<br/>A refund of ₹' . $refundAmount . ' will be issued to the same instrument that you used to make the advance payment.';
			$body		 .= '<br/><br/>Our transaction reference ID is ' . $transID . '.';
			$body		 .= '<br/>Please be patient as sometimes it may take between 3-15 business days for your bank/payments processor to release these funds back to you.';
			$body		 .= '<br/><br/>If you wish you inquire about the refund, you may follow up with them using the above transaction number we have provided.';
			$body		 .= '<br/><br/>Thank you,
                            <br/>Team Gozo';
			$subject	 = 'Cancellation & Refund processed for booking ID ' . $data['bkg_booking_id'];
			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$userName	 = $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'];
			//$email		 = $data['bkg_user_email'];
			$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
			if ($response->getStatus())
			{
				$email = $response->getData()->email['email'];
			}
			//$email		 = ContactEmail::model()->getEmailByBookingUserId($model->bkgUserInfo->bui_id);
			$usertype	 = EmailLog::Consumers;
			$bccMail	 = Yii::app()->params['cancellationEmail'];
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $userName);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$mail->setBcc($bccMail);
			//$subject = 'Your booking ID ' . $data['bkg_booking_id'] . ' has been cancelled by ' . $data['user_type'] . '';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				//echo $data['bkg_booking_id']." ===> mail sent \n";
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}

			$emailType	 = EmailLog::EMAIL_CANCEL_TRIP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $data['bkg_id'];
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $data['bkg_booking_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			}
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function cancellationWithoutRefundMail($bkgId, $refundAmount)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$eventIds	 = [10, 82, 122];
		$eventIds	 = implode(',', $eventIds);
		$sql		 = "SELECT 	booking.bkg_id,
                booking.bkg_booking_id,
				booking_user.bkg_user_fname,
		        booking_user.bkg_user_email,
                booking_user.bkg_user_lname,
                booking.bkg_cancel_id,
                booking.bkg_cancel_delete_reason,
                blg_user_type,
                blg_desc,
                cancel_reasons.cnr_reason,(
                    CASE WHEN blg_user_type=1 THEN 'Consumer'
                         WHEN blg_user_type=4 THEN 'Admin'
                         WHEN blg_user_type=10 THEN 'System'
                     END
                ) as user_type
                FROM `booking`
                INNER JOIN `booking_user` ON `bui_bkg_id` = `booking`.bkg_id
                INNER JOIN (
                    SELECT booking_log.blg_user_type, booking_log.blg_booking_id, booking_log.blg_desc
                    FROM `booking_log`
                    WHERE booking_log.blg_event_id IN ($eventIds)
                )blg ON blg.blg_booking_id=booking.bkg_id
                LEFT JOIN `cancel_reasons` ON cancel_reasons.cnr_id=booking.bkg_cancel_id AND cancel_reasons.cnr_active=1
                WHERE booking.bkg_id=$bkgId AND booking.bkg_status IN(9)";
		$data		 = Yii::app()->db->createCommand($sql)->queryRow();
		if ($data != '' && count($data) > 0)
		{
			$transID	 = PaymentGateway::getTXNIDbyBkgId($data['bkg_id'], 1);
			$emailArr	 = [];
			$logArr		 = [];
			if ($model->bkg_agent_id > 0)
			{
				//remove comment when agent panel notificaion will be live
				$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::CANCEL_TRIP);
				$logArr	 = $logArr1['email'];
				foreach ($logArr as $key => $value)
				{
					$emailArr[$value['email']] = $value['name'];
					if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
					{
						unset($emailArr[$value['email']]);
					}
				}
				// remove comment when agent panel notificaion will be live
			}

			$body		 = 'Dear ' . $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'] . ',<br/>';
			$body		 .= '<br/>The cancellation for your booking ' . $data['bkg_booking_id'] . ' has been processed on our end.';
			$body		 .= '<br/><br/>As per the terms and conditions, this booking is not eligible for a refund.';
			$body		 .= '<br/><br/>Should you have any questions please write to us at accounts@aaocab.in or you may request a call back at aaocab.com.';
			$body		 .= '<br/><br/>Regards,
                            <br/>Team Gozo';
			$subject	 = 'Cancellation for Booking ID:' . $data['bkg_booking_id'];
			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$userName	 = $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'];
			$email		 = $data['bkg_user_email'];
			$usertype	 = EmailLog::Consumers;
			$bccMail	 = Yii::app()->params['cancellationEmail'];
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $userName);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$mail->setBcc($bccMail);
			//$subject = 'Your booking ID ' . $data['bkg_booking_id'] . ' has been cancelled by ' . $data['user_type'] . '';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				//echo $data['bkg_booking_id']." ===> mail sent \n";
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}

			$emailType	 = EmailLog::EMAIL_CANCEL_TRIP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $data['bkg_id'];
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $data['bkg_booking_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
			}
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function bookingAutoCancellationMail($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email		 = $response->getData()->email['email'];
			$firstName	 = $response->getData()->email['firstName'];
			$lastName	 = $response->getData()->email['lastName'];
		}
		if ($email != '' && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0))
		{
			$userName = $firstName . ' ' . $lastName;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
			{
				return false;
			}

			$mail	 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$subject = 'Booking ID:' . $model->bkg_booking_id . ' cancelled. No reconfirmation received.';
			$body	 = 'Dear ' . $userName . ',<br/>';
			$body	 .= '<br/>We did not receive your reconfirmation even after sending multiple reminders. <br/><br/>Booking ID:' . $model->bkg_booking_id . '  IS NOW CANCELLED.  <br/>We require you to reconfirm all cash based bookings 24hours before pickup. Request a call back from aaocab.com if you still need a cab & plan to travel. T&Cs apply.';
			$body	 .= '<br/><br/>Thank you,
                            <br/>Team Gozo';
			$bccMail = Yii::app()->params['cancellationEmail'];
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setTo($email, $userName);
			$mail->setBcc($bccMail);
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userType	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_CANCEL_TRIP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $userType, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);

			if ($elgId != '')
			{
				BookingLog::model()->createLog($model->bkg_id, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	public function remainderAdvancedMail($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		if (($model != '' && $model->bkg_blocked_msg == 1 ) || $model->bkg_agent_id > 0)
		{
			return;
		}
		$sql	 = "SELECT booking.bkg_id,booking.bkg_booking_id,booking_user.bkg_user_fname,booking_user.bkg_user_lname,booking_user.bkg_user_email,booking.bkg_booking_type,booking_invoice.bkg_base_amount,booking_invoice.bkg_additional_charge,booking_invoice.bkg_service_tax,booking_invoice.bkg_discount_amount,booking_invoice.bkg_advance_amount,booking_invoice.bkg_total_amount,booking.bkg_pickup_date,DATE_FORMAT(booking.bkg_pickup_date,'%T') as bkg_pickup_time,booking.bkg_create_date,fromCity.cty_name as from_city,toCity.cty_name as to_city,
                vendors.vnd_name,vendors.vnd_owner,drivers.drv_name,vehicles.vhc_number,vehicle_types.vht_make,vehicle_types.vht_model
                FROM `booking`
                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id
                AND booking_cab.bcb_active=1
                INNER JOIN `booking_user` ON booking_user.bui_bkg_id=booking.bkg_id
                INNER JOIN `booking_invoice` ON booking_invoice.biv_bkg_id=booking.bkg_id
                LEFT JOIN `vendors` ON vendors.vnd_id=booking_cab.bcb_vendor_id
                LEFT JOIN `drivers` ON drivers.drv_id=booking_cab.bcb_driver_id
                LEFT JOIN `vehicles` ON vehicles.vhc_id=booking_cab.bcb_cab_id
                LEFT JOIN `vehicle_types` ON vehicle_types.vht_id=vehicles.vhc_type_id
                LEFT JOIN `cities` as `fromCity` ON fromCity.cty_id=booking.bkg_from_city_id
                LEFT JOIN `cities` as `toCity` ON toCity.cty_id=booking.bkg_to_city_id
                WHERE booking.bkg_id=$bkgId";
		$data	 = Yii::app()->db->createCommand($sql)->queryRow();
		if (count($data) > 0)
		{
			$body		 = 'Dear ' . $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'] . ',<br/>';
			$body		 .= '<br/>We are just writing to remind that you have a reservation with us on <date>';
			$body		 .= '<br/><br/>If there is anything we can help you with, please feel free to give us a call';
			$body		 .= '<br/><br/>Details of your booking: include all details from the booking confirmation email';
			$body		 .= '<br/><br/>Progress on your booking:';
			$body		 .= '<br/>Reservation Number: ' . $data['bkg_booking_id'] . '';
			$body		 .= '<br/>Reservation Made: <DATE XXX> ';
			$body		 .= '<br/>Booking confirmation received: <DATE XXX>';
			$body		 .= '<br/>Operator Assigned:  <DATE XXX>';
			$body		 .= '<br/>Vehicle and Driver identified: <DATE XXX> or to be done by';
			$body		 .= '<br/>Vehicle and Driver information: ' . $data['vht_model'] . ' ' . $data['vhc_number'] . ' - ' . $data['drv_name'] . ' or to be emailed & SMS 3 hours before pickup';
			$body		 .= '<br/><br/>Thank you,
                            <br/>Team Gozo';
			$mail		 = new EIMailer();
			$userName	 = $data['bkg_user_fname'] . ' ' . $data['bkg_user_lname'];
			$email		 = $data['bkg_user_email'];
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
			{
				return false;
			}

			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setTo($email, $userName);
			$subject = 'We look forward to serving you in ' . $data['from_city'] . ' on <date>';
			$mail->setSubject($subject);
			if ($mail->sendServicesEmail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_REMINDER_ADVANCE;
			$refType	 = EmailLog::REF_USER_ID;
			$refId		 = $bkgId;
			emailWrapper::createLog($email, $subject, $body, $data['bkg_booking_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			return true;
		}
		else
		{
			return false;
		}
	}

	public function attachOperatorMail($email, $phone = '', $company_name)
	{
		if ($email != '')
		{
			$this->email_receipient = $email;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
			{
				return false;
			}

			$mail		 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setLayout('mail');
			$mail->setTo($email, '');
			$vendorLink	 = "<a href='http://www.aaocab.com/vendor/join'>http://www.aaocab.com/vendor/join</a>";
			$subject	 = 'Join the Gozo taxi operator network';
			$msg		 = 'Hi ' . $company_name . ',';
			$msg		 .= '<br/>Gozo cabs is a taxi company operating all across India. One of our customers has asked that we contact you and ask you to join our taxi operator network.'
					. '<br/><br/>Gozo matches customers with taxi operators across the country. We need you to provide high quality service and we keep sending you more business for providing good service.'
					. '<br/>it does not cost you anything to join our network of approved operators.'
					. '<br/>By joining the Gozo network, you can also receive bookings from your local customers and book taxi for your customers anywhere in India on the Gozo network.'
					. '<br/><br/>If you are interested in joining us please click on the link below.'
					. '<br/>' . $vendorLink . '';
			$mail->isHTML(true);
			$mail->setSubject($subject);
			$mail->setBody($msg);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Vendor;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
		}
	}

	public function linkCorporateOTP($userModel, $email)
	{
		if ($email != '')
		{
			/* @var $userModel Users */
			$this->email_receipient = $email;
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
			{
				return false;
			}

			$mail		 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setLayout('mail');
			$mail->setTo($email, '');
			$subject	 = 'OTP request for Gozo Corporate account';
			$username	 = $userModel->usr_name . " " . $userModel->usr_lname . "(" . $userModel->usr_email . ")";
			$msg		 = $username . ' wants to register in your Gozo Corporate account.<br>Approval OTP is ' . $userModel->usr_verification_code;
			$mail->isHTML(true);
			$mail->setSubject($subject);
			$mail->setBody($msg);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Corporate;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL, 0);
		}
	}

	public function automatedFollowupMail($bkgId)
	{
		$success = false;
		$model	 = Booking::model()->findByPk($bkgId);

		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)) || $model->bkg_agent_id > 0)
		{
			return $success;
		}
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return $success;
		}

		if (isset($email) && $email != '')
		{
			$fromCity		 = $model->bkgFromCity->cty_name;
			$toCity			 = $model->bkgToCity->cty_name;
			$params			 = array(
				'bkg_id'			 => $model->bkg_id,
				'from_city'			 => $fromCity,
				'to_city'			 => $toCity,
				'deal_pickup_date'	 => Filter::getDateFormatted($model->bkg_pickup_date),
				'deal_base_fare'	 => round($model->bkgInvoice->bkg_base_amount));
			$params['url']	 = LeadFollowup::getUnvFollowupURL($model->bkg_id, 'e');
			$mail			 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setView('unverified_amazing_deal');
			$mail->setData(array('arr' => $params));
			$mail->setLayout('mail2');
			$mail->setTo($email);
			$subject		 = 'We found a great price for your trip from ' . $fromCity . ' to ' . $toCity . ' on ' . Filter::getDateFormatted($model->bkg_pickup_date);
			$mail->setSubject($subject);
			$delivered		 = "Email not sent";
			$delay_time		 = 0;
			$delayFlag		 = ($delay_time == 0) ? 0 : 1;
			if ($mail->sendMail($delayFlag))
			{
				$delivered = "Email sent successfully";
			}
			$userType	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_UNVERIFIED_FINAL_FOLLOWUP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $userType, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delayFlag);
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Final Followup Request Sent", UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
			echo $delivered . "-" . $bkgId;
			echo "\n";
			//$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, EmailLog::Consumers, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			//$success	 = true;
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public function unverifiedFollowupMail($bkgId)
	{
		$success	 = false;
		$model		 = Booking::model()->findByPk($bkgId);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)) || $model->bkg_agent_id > 0)
		{
			return $success;
		}
		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return $success;
		}

		if (isset($email) && $email != '')
		{
			$fromCity				 = $model->bkgFromCity->cty_name;
			$toCity					 = $model->bkgToCity->cty_name;
			$params['url']			 = Filter::shortUrl(BookingUser::getPaymentLink($bkgId, 'e'));
			$params['booking_id']	 = $model->bkg_booking_id;

			$params['user_name']		 = $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname;
			$params['trip_type']		 = $model->getBookingType($model->bkg_booking_type);
			$params['cab_type']			 = $model->bkgSvcClassVhcCat->scv_label;
			$params['pickup_time']		 = date("d/M/y", strtotime($model->bkg_pickup_date)) . " " . date("h:i A", strtotime($model->bkg_pickup_date));
			$params['pickup_location']	 = $model->bkg_pickup_address;
			$params['dropoff_location']	 = $model->bkg_drop_address;
			$params['total_fare']		 = $model->bkgInvoice->bkg_total_amount;
			$createTime					 = $model->bkg_create_date;
			$hourdiff					 = BookingPref::model()->getWorkingHrsCreateToPickupByID($model->bkg_id);
			$timeTwentyPercent			 = round($hourdiff * 0.2);
			$new_time2					 = date("Y-m-d H:i:s", strtotime('+' . $timeTwentyPercent . ' hours', strtotime($createTime)));
			$new_time					 = ($model->bkgTrail->bkg_quote_expire_date != '') ? $model->bkgTrail->bkg_quote_expire_date : $new_time2;
			$params['expire_on']		 = date('jS M Y (D) h:i A', strtotime($new_time));
			$minPerc					 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bkgPref->bkg_is_gozonow);
			$minamount					 = round($minPerc * $model->bkgInvoice->bkg_total_amount * 0.01);
			$params['min_payment']		 = $minamount;
			$params['contact_us']		 = '+91-90518-77000';

			$mail	 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setView('bkg_unverified_followup');
			$mail->setData(array('arr' => $params, 'email_recepient' => $email, 'id' => $model->bkgUserInfo->bkg_user_id));
			$mail->setLayout('mail1');
			$mail->setTo($email);
			//$subject		 = 'Book cab from ' . $fromCity . ' to ' . $toCity . '. How can we help?';
			$subject = 'Quote ID:' . $model->bkg_booking_id . ' | Fares are about to rise';
			$mail->setSubject($subject);

			$delivered	 = "Email not sent";
			$delay_time	 = 0;
			$delayFlag	 = ($delay_time == 0) ? 0 : 1;
			if ($mail->sendMail($delayFlag))
			{
				$delivered = "Email sent successfully";
			}
			$userType	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_UNVERIFIED_FOLLOWUP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $userType, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delayFlag);
			if ($elgId != '')
			{
				BookingLog::model()->createLog($bkgId, "Unverified Followup Request Sent", UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			}
//			echo $delivered . "-" . $bkgId;
//			echo "\n";
			//$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, EmailLog::Consumers, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			//$success	 = true;
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public function leadFollowup($bkgId)
	{
		$success = false;
		/* @var $model BookingTemp */
		$model	 = BookingTemp::model()->findByPk($bkgId);
		$email	 = $model->bkg_user_email;
		$userId	 = $model->bkg_user_id;

		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
		{
			return $success;
		}

		if (isset($email) && $email != '')
		{
			$params['confirm']	 = Booking::getConfirmBookingByContact($email);
			$fromCity			 = Cities::getName($model->bkg_from_city_id);
			$toCity				 = Cities::getName($model->bkg_to_city_id);
			$params['url']		 = Filter::shortUrl(LeadFollowup::getLeadURL($bkgId, 'e'));
			$params['type']		 = 'lead';

			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$mail->setView('unverified_followup');
			$mail->setData(array('arr' => $params, 'email_recepient' => $email, 'id' => $userId));
			$mail->setLayout('mail2');
			$mail->setTo($email);
			$subject	 = 'Ride from ' . $fromCity . ' => ' . $toCity . '. How can we help?';
			$mail->setSubject($subject);
			$delivered	 = "Email not sent";
			$delay_time	 = 0;
			$delayFlag	 = ($delay_time == 0) ? 0 : 1;
			if ($mail->sendMail($delayFlag))
			{
				$delivered = "Email sent successfully";
			}
			$userType	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_LEAD_FOLLOWUP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $model->bkg_id;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $userType, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delayFlag);
			if ($elgId != '')
			{
				$userInfo		 = UserInfo::getInstance();
				$followStatus	 = $model->bkg_follow_up_status;
				LeadLog::model()->createLog($model->bkg_id, "Automatic Lead followup email sent", $userInfo, '', $followStatus, BookingLog::EMAIL_SENT);
			}
			echo $delivered . "-" . $bkgId;
			echo "\n";
			//$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, EmailLog::Consumers, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			//$success	 = true;
			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param integer $bkgId
	 * @return boolean
	 */
	public static function sendCabDriverDetailsToCustomer($bkgId)
	{
		$model			 = Booking::model()->findByPk($bkgId);
		$vehicleModel	 = $model->bkgBcb->bcbCab->vhcType->vht_model;
		if ($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
		{
			$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
		}
		if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)))
		{
			return false;
		}
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$this->email_receipient	 = $email;
		$paymentLink = BookingUser::getPaymentLinkByEmail($model->bkg_id);
		$phoneNumber = Booking::getMaskNoForCustToDriver($model);
		$bookarr	 = ['userName'		 => $model->bkgUserInfo->getUsername(), 'driver'		 => $model->bkgBcb->bcb_driver_name, 'driver_phone'	 => $phoneNumber,
			'car'			 => $vehicleModel, 'car_number'	 => $model->bkgBcb->bcbCab->vhc_number, 'booking_id'	 => $model->bkg_booking_id, 'payment_link'	 => $paymentLink];

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
			$logArr1 = BookingMessages::model()->getMessageSettings($model->bkg_id, AgentMessages::CAB_DRIVER_DETAIL);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
		}
		if ($email != '' || count($emailArr) > 0)
		{
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('cabdriverinfo');
			$mail->setData(array('arr' => $bookarr, 'email_receipient' => $email, 'userId' => $model->bkgUserInfo->bkg_user_id));
			$mail->setLayout('mail');
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $model->bkgUserInfo->getUsername());
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$subject = 'Cab & Driver for Booking ID:' . $model->bkg_booking_id;
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$emailType	 = EmailLog::EMAIL_CAB_DRIVER_DETAIL;
					$refType	 = EmailLog::REF_BOOKING_ID;
					$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $bkgId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$usertype	 = EmailLog::Consumers;
				$emailType	 = EmailLog::EMAIL_CAB_DRIVER_DETAIL;
				$refType	 = EmailLog::REF_BOOKING_ID;
				$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $bkgId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			}
		}

		if ($elgId != '')
		{
			BookingLog::model()->createLog($bkgId, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			return true;
		}
		return false;
	}

	public function tentativeBookingMail()
	{

		$email		 = Yii::app()->params['leadAboveEmail'];
		$delivered	 = '';
		if ($email != '')
		{
			$bdata					 = Booking::model()->getTentativeBookings();
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setTo($email, 'Leaders aaocab');
			$mail->clearView();
			$style					 = ' style="padding: 5px;"';
			$style1					 = ' style="padding: 5px; text-align:center"';
			/* @var $model Booking */
			$tot					 = count($bdata);
			$body1					 .= '<h3>Tentative Booking List as on (' . date('d/m/Y h:i A', strtotime(date('Y-m-d'))) . ')</h3><br>
				Total ' . $tot . ' record(s) found <br>
			<table border="1" style="border-collapse: collapse">
			<thead>
			<tr>
				<th' . $style . '>	SL No.	</th>
				<th' . $style . '>	Created on</th>
				<th' . $style . '>	PickUp Date</th>
                                <th' . $style . '>	Booking Id</th>
				<th' . $style . '>	Customer Name</th>
				<th' . $style . '>	Email</th>
				<th' . $style . '>	Phone</th>
				<th' . $style . '>	From</th>
				<th' . $style . '>	To</th>
				<th' . $style . '>	Amount</th>
				<th' . $style . '>	Advance</th>
				<th' . $style . '>	Car Type</th>
				<th' . $style . '>	Vendror</th>
				<th' . $style . '>	Driver</th>
			</tr>
		</thead>
            <tbody>';
			$body2					 = "";
			$k						 = 0;
			if (count($bdata) > 0)
			{
				foreach ($bdata as $b)
				{
					$pickupTime		 = date('h:i A', strtotime($b['bkg_pickup_date']));
					$createDate		 = date('d/m/Y h:i A', strtotime($b['bkg_create_date']));
					$pickupDate		 = date('d/m/Y h:i A', strtotime($b['bkg_pickup_date']));
					$name			 = ($b['customer_name'] != "") ? $b['customer_name'] : '';
					$email			 = ($b['bkg_user_email'] != "") ? $b['bkg_user_email'] : '';
					$contact		 = ($b['bkg_contact_no'] != "") ? $b['bkg_contact_no'] : '';
					$fromCity		 = ($b['frm_city_name'] != "") ? $b['frm_city_name'] : '';
					$toCity			 = ($b['to_city_name'] != "") ? $b['to_city_name'] : '';
					$vendorName		 = ($b['vnd_owner'] != "" && $b['vnd_owner'] != NULL) ? $b['vnd_owner'] : '';
					$driverName		 = ($b['drv_name'] != "" && $b['drv_name'] != NULL) ? $b['drv_name'] : '';
					$carType		 = ($b['vht_model'] != "" && $b['vht_model'] != NULL) ? $b['vht_model'] : '';
					$bookAmount		 = ($b['bkg_total_amount'] > 0 && $b['bkg_total_amount'] > 0.00) ? 'Rs. ' . $b['bkg_total_amount'] : '0';
					$advanceAmount	 = ($b['bkg_advance_amount'] > 0 && $b['bkg_advance_amount'] > 0.00) ? 'Rs. ' . $b['bkg_advance_amount'] : '0';
					$body2			 .= '<tr>
				<td' . $style1 . '>' . ($k + 1) . '.</td>
				<td' . $style . '>' . $createDate . '</td>
				<td' . $style . '>' . $pickupDate . '</td>
				<td' . $style . '>' . $b['bkg_booking_id'] . '</td>
				<td' . $style . '>' . $name . '</td>
				<td' . $style . '>' . $email . '</td>
				<td' . $style . '>' . $contact . '</td>
                                <td' . $style . '>' . $fromCity . '</td>
                                <td' . $style . '>' . $toCity . '</td>
				<td' . $style . '>' . $bookAmount . '</td>
				<td' . $style . '>' . $advanceAmount . '</td>
				<td' . $style . '>' . $carType . '</td>
				<td' . $style . '>' . $vendorName . '</td>
				<td' . $style . '>' . $driverName . '</td>
                            </tr>';
					$k++;
				}
			}
			else
			{
				$body2 .= '<tr>
                            <td' . $style1 . ' colspan="14">No Records Yet Found.</td>
                          </tr>';
			}
			$body3	 = '</tbody></table>';
			$body	 = $body1 . $body2 . $body3;
			$mail->setBody($body);
			$mail->clearLayout();
			$subject = 'Tentative Bookings List';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}

			echo $delivered;
		}
	}

	public function unverifiedFeedbackMail()
	{
		$email		 = Yii::app()->params['leadsonthefence'];
		$delivered	 = '';
		if ($email != '')
		{
			//$bdata					 = Booking::model()->getUnverifiedFollowupList();
			$bdata					 = LeadFollowup::getLeadsByMail();
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_VENDOR_BATCH_EMAIL);
			$mail->setTo($email);
			$mail->clearView();
			$style					 = ' style="padding: 5px;"';
			$style1					 = ' style="padding: 5px; text-align:center"';
			/* @var $model Booking */
			$tot					 = count($bdata);
			$body1					 .= '<h3>Leads/Unverified Customers  - as on (' . date('d/m/Y h:i A', strtotime(date('Y-m-d H:i:s'))) . ')</h3><br>
				Total ' . $tot . ' record(s) found <br>
			<table border="1" style="border-collapse: collapse">
			<thead>
			<tr>
				<th' . $style . '>	SL No.	</th>
				<th' . $style . '>	Customer Name</th>
				<th' . $style . '>	Booking Id</th>
				<th' . $style . '>	Bkg Amount</th>
				<th' . $style . '>	Created On</th>
				<th' . $style . '>	From</th>
				<th' . $style . '>	To</th>
				<th' . $style . '>	Travel Date</th>
				<th' . $style . '>	Cab Type</th>
				<th' . $style . '>	Competitor Quote</th>
				<th' . $style . '>	Price Was High</th>
				<th' . $style . '>	Will Book Later</th>
				<th' . $style . '>	Tentative Requested</th>
				<th' . $style . '>	Other</th>
				<th' . $style . '>	Comments</th>
				<th' . $style . '>	Call me please</th>	
			</tr>
		</thead>
            <tbody>';
			$body2					 = "";
			$k						 = 0;
			if (count($bdata) > 0)
			{
				foreach ($bdata as $b)
				{
					$pickupTime	 = date('h:i A', strtotime($b['bkg_pickup_time']));
					$createDate	 = date('d/m/Y h:i A', strtotime($b['createDate']));
					$pickupDate	 = date('d/m/Y h:i A', strtotime($b['pickupDate']));
					$name		 = ($b['user_fullname'] != "") ? $b['user_fullname'] : '';
					$email		 = ($b['user_email'] != "") ? $b['user_email'] : '';
					$contact	 = ($b['user_phone'] != "") ? $b['user_phone'] : '';
					$fromCity	 = ($b['fromCity'] != "") ? $b['fromCity'] : '';
					$toCity		 = ($b['toCity'] != "") ? $b['toCity'] : '';
					$bookAmount	 = ($b['bkgAmount'] > 0) ? 'Rs. ' . $b['bkgAmount'] : '0';
					$leadAmount	 = ($b['lfu_amount'] > 0) ? 'Rs. ' . $b['lfu_amount'] : '0';
					$body2		 .= '<tr>
						<td' . $style1 . '>' . ($k + 1) . '.</td>
						<td' . $style . '>' . $name . '</td>
						<td' . $style . '>' . $b['bookingId'] . '</td>
						<td' . $style . '>' . $bookAmount . '</td>
						<td' . $style . '>' . $createDate . '</td>
						<td' . $style . '>' . $fromCity . '</td>
						<td' . $style . '>' . $toCity . '</td>
						<td' . $style . '>' . $pickupDate . '</td>
						<td' . $style . '></td>
						<td' . $style . '>' . $leadAmount . '</td>
						<td' . $style . '>' . $b['price_was_high'] . '</td>
						<td' . $style . '>' . $b['will_book_later'] . '</td>
						<td' . $style . '>' . $b['will_book_later_tentative'] . '</td>
						<td' . $style . '>' . $b['other'] . '</td>
						<td' . $style . '>' . $b['other_comment'] . '</td>
						<td' . $style . '>' . $b['call_me_please'] . '</td>	
					</tr>';
					$k++;
				}
			}
			else
			{
				$body2 .= '<tr>
                            <td' . $style1 . ' colspan="16">No Records Yet Found.</td>
                          </tr>';
			}
			$body3	 = '</tbody></table>';
			$body	 = $body1 . $body2 . $body3;
			$mail->setBody($body);
			$mail->clearLayout();
			//$subject = 'Unverified Customers Feedback as on (' . date('d/m/Y h:i A', strtotime(date('Y-m-d'))) . ')';
			$subject = 'Leads/Unverified feedback as of (' . date('d/m/Y h:i A', strtotime(date('Y-m-d'))) . ')';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo $delivered;
		}
	}

	public function returnTripEmail($bkgId = '')
	{
		$status	 = 6;
		$oneWay	 = 1;
		if ($bkgId != '')
		{
			$model = Booking::model()->findByPk($bkgId);
			if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
			{
				return false;
			}
			$sql = "SELECT booking.bkg_id,booking.bkg_booking_id,booking_user.bkg_user_fname,booking_user.bkg_user_lname,booking_user.bkg_contact_no,booking_user.bkg_user_email,
				booking.bkg_status,booking.bkg_pickup_date,fromCity.cty_name as from_city,toCity.cty_name as to_city
                FROM `booking`
                INNER JOIN `booking_cab` ON booking_cab.bcb_id=booking.bkg_bcb_id AND booking_cab.bcb_active=1
				JOIN `booking_user` ON booking_user.bui_bkg_id=booking.bkg_id
				JOIN `booking_pref` ON booking_pref.bpr_bkg_id=booking.bkg_id
                JOIN `cities` as fromCity ON fromCity.cty_id=booking.bkg_from_city_id
                JOIN `cities` as  toCity ON toCity.cty_id=booking.bkg_to_city_id
                WHERE bkg_status=$status AND bkg_booking_type=$oneWay
                AND (
                    booking_cab.bcb_vendor_id IS NOT NULL AND booking_cab.bcb_driver_id IS NOT NULL AND booking_cab.bcb_cab_id IS NOT NULL
                )
                AND booking_pref.bkg_return_trip_remind=0  AND booking.bkg_id=$bkgId";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
//			if (isset($row['bkg_trip_type']) && $row['bkg_trip_type'] == 1)
//			{

			$emailArr	 = [];
			$logArr		 = [];
			if ($model->bkg_agent_id > 0)
			{
				$logArr1 = BookingMessages::model()->getMessageSettings($bkgId, AgentMessages::MSG_RETURN_OR_ONWARD_TRIP);
				$logArr	 = $logArr1['email'];
				foreach ($logArr as $key => $value)
				{
					$emailArr[$value['email']] = $value['name'];
					if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
					{
						unset($emailArr[$value['email']]);
					}
				}
			}

			$body	 = 'Dear ' . $row['bkg_user_fname'] . ' ' . $row['bkg_user_lname'] . ',<br/>';
			$body	 .= '<br/>Thanks for traveling with Gozo on your trip ' . $row['bkg_booking_id'] . ' from ' . $row['from_city'] . ' to ' . $row['to_city'] . ' .';
			$body	 .= '<br/>Please reply to this email with your onward trip details to avail a promotional rate for your return or onward trip as a repeat customer.';
			$body	 .= '<br/>You can also book on our app directly for our best rates and enter code AGAIN250 to avail this offer.';
			$body	 .= '<br/><br/>Thank you,
                                <br/>aaocab Team';

			$mail		 = EIMailer::getInstance(EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			$userName	 = $row['bkg_user_fname'] . ' ' . $row['bkg_user_lname'];
			//$email		 = $row['bkg_user_email'];
			$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
			if ($response->getStatus())
			{
				$email = $response->getData()->email['email'];
			}
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$mail->setTo($email, $userName);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			$subject = 'Use Gozo for your onwards journey';
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_RETURN_TRIP;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$refId		 = $row['bkg_id'];
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $row['bkg_booking_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $row['bkg_booking_id'], $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			}

			if ($elgId != '')
			{
				Booking::model()->setReturnTripStatus($row['bkg_id'], $elgId);
			}
			return true;
//			}
//			else
//			{
//				return false;
//			}
		}
		else
		{
			return false;
		}
	}

	public function completeVendorRegistration($vnd_id)
	{
		/* @var $model Vendor */
		$model			 = new Vendors();
		$model->vnd_id	 = $vnd_id;
		$row			 = $model->getRegistrationProgress('command');

		if (isset($row['eml_email_address']) && $row['eml_email_address'] != '')
		{

			$email = $row['eml_email_address'];
			if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
			{
				return false;
			}

			$vendorName				 = ($row['vnd_name'] != '') ? $row['vnd_name'] : '';
			$this->email_receipient	 = $email;
			$subject				 = 'Complete your application so we can start sending you business';
			$body					 = 'Dear ' . $vendorName . ',<br/><br/>';
			$body					 .= 'Your operator application is incomplete.<br/>';
			$body					 .= 'Operator application ID: ' . dechex($row['vnd_id']) . ' <br/><br/>';
			$body					 .= '<br/><b>Step 1</b>: <del>Register to work with Gozo</del>  <b>DONE</b>';
			$partnerLoginStr		 = ($row['last_login'] != '') ? '<del>Download Gozo Partner app and login</del>  <b>DONE</b>' : 'Download Gozo Partner app and login';
			$voterStr				 = ($row['voterPath'] != 'No') ? '<del>Upload your Voter ID in Gozo partner app</del>  <b>DONE</b>' : 'Upload your Voter ID in Gozo partner app';
			$aadhaarStr				 = ($row['aadhaarPath'] != 'No') ? '<del>Upload your Aadhar card in Gozo partner app</del>  <b>DONE</b>' : 'Upload your Aadhar card in Gozo partner app';
			$panStr					 = ($row['panPath'] != 'No') ? '<del>Upload your Pan ID in Gozo partner app</del>  <b>DONE</b>' : 'Upload your Pan ID in Gozo partner app';
			$licenseStr				 = ($row['licensePath'] != 'No') ? '<del>Upload your Driver License in Gozo partner app</del>  <b>DONE</b>' : 'Upload your Driver License in Gozo partner app';
			$agreementStr			 = ($row['agreementPath'] != 'No') ? '<del>Upload a signed copy of Gozo Operator Agreement in Gozo partner app</del>  <b>DONE</b>' : 'Upload a signed copy of Gozo Operator Agreement in Gozo partner app';
			$bankStr				 = ($row['bank_details'] != 'No') ? '<del>Provide us your bank details to pay you in Gozo partner app</del>  <b>DONE</b>' : 'Provide us your bank details to pay you in Gozo partner app';
			$carAddedStr			 = ($row['cars_added'] > 0) ? '<del>Add atleast 1 car to your account - including car paperwork</del>  <b>DONE</b>' : 'Add atleast 1 car to your account - including car paperwork';
			$driverAddedStr			 = ($row['drivers_added'] > 0) ? '<del>Add atleast 1 driver to your account - including driver license information</del>  <b>DONE</b>' : 'Add atleast 1 driver to your account - including driver license information';
			$body					 .= '<br/><b>Step 2</b>: ' . $partnerLoginStr;
			$body					 .= '<br/><b>Step 3</b>: ' . $voterStr;
			$body					 .= '<br/><b>Step 4</b>: ' . $aadhaarStr;
			$body					 .= '<br/><b>Step 5</b>: ' . $panStr;
			$body					 .= '<br/><b>Step 6</b>: ' . $licenseStr;
			$body					 .= '<br/><b>Step 7</b>: ' . $agreementStr;
			$body					 .= '<br/><b>Step 8</b>: ' . $bankStr;
			$body					 .= '<br/><b>Step 9</b>: ' . $carAddedStr;
			$body					 .= '<br/><b>Step 10</b>: ' . $driverAddedStr;

			$body	 .= '<br/><br/>See As soon as all above items are submitted, we will approve your account within 1-2 days
.';
			$body	 .= '<br/>Thank you,<br/>Gozo Operator relations team';
			$body	 .= '<br><br>Received this email in error or do not want to continue?';
			$body	 .= '<a href="https://aaocab.com/aborted/' . $row['vnd_id'] . '" target="_blank"> Click here</a>';
			$body	 .= ' to delete your application';

			$mail = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setTo($row['eml_email_address'], $vendorName);
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Vendor;
			$emailType	 = EmailLog::EMAIL_REG_COMPLETE_FASTER;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$refId		 = $row['vnd_id'];
			$elgId		 = emailWrapper::createLog($email, $subject, $body, '', $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL, 0);

			echo $delivered . " - " . $email . " - " . $vendorName;
			echo "\n";
		}
	}

	public function gotBookingAgentUser($bkgid)
	{
		Logger::create("Agent booking assignment test 4:\t" . $bkgid, CLogger::LEVEL_PROFILE);
		$logType = 1;
		$model	 = Booking::model()->findByPk($bkgid);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		/* @var $model Booking */
		//$email		 = $model->bkgUserInfo->bkg_user_email;
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$usertype	 = EmailLog::Consumers;
		$username	 = $model->bkgUserInfo->getUsername();

		$emailArr	 = [];
		$logArr		 = [];
		if ($model->bkg_agent_id > 0)
		{
//remove comment when agent panel notificaion will be live
			$logArr1 = BookingMessages::model()->getMessageSettings($bkgid, AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO);
			$logArr	 = $logArr1['email'];
			foreach ($logArr as $key => $value)
			{
				$emailArr[$value['email']] = $value['name'];
				if (Unsubscribe::isUnsubscribed($value['email'], Unsubscribe::CAT_BOOKING))
				{
					unset($emailArr[$value['email']]);
				}
			}
// remove comment when agent panel notificaion will be live
		}

		if ($email != '' || count($emailArr) > 0)
		{

			//send mail
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('gotbooking_agentuser');
			$hash					 = Yii::app()->shortHash->hash($bkgid);
			//$payurl					 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bkgid, 'hash' => $hash]);
			$payurl					 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			//$bkgPrefModel			 = BookingPref::model()->getByBooking($model->bkg_id);
			$mail->setData(
					array('model' => $model, 'payurl' => $payurl, 'otp' => $model->bkgTrack->bkg_trip_otp)
			);
			$mail->setLayout('mail1');

			if (count($emailArr) > 0)
			{
				$mail->setTo($emailArr);
			}
			else if ($model->bkg_agent_id == 0 || $model->bkg_agent_id == '')
			{
				$mail->setTo($email, $username);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			else
			{
				return false;
			}
			$subject = 'Reservation received – Booking ID : ' . $model->bkg_booking_id;
			$eventId = EmailLog::EMAIL_BOOKING_CREATED;

			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}


			//email log
			$body	 = $mail->Body;
			$refType = EmailLog::REF_BOOKING_ID;
			$refId	 = $model->bkg_id;
			if (count($emailArr) > 0)
			{
				foreach ($logArr as $key => $value)
				{
					$usertype	 = $key;
					$email		 = $value['email'];
					$elgId		 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
				}
			}
			else if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog:: SEND_SERVICE_EMAIL);
			}

			return $elgId;
		}
	}

	public function vendorjoinEmail($arr, $cityName, $vendorId, $password)
	{
		$body	 = "<h3><b>New Vendor Signed Up</b></h3>" .
				"<b>Vendor Name : </b>" . trim($arr['first_name'] . " " . $arr['last_name']) .
				//	"<br/><b>Company Name : </b>" . trim($arr['vnd_company']) .
				"<br/><b>Vendor Phone : </b>" . trim($arr['phn_phone_no']) .
				"<br/><b>Vendor Email : </b>" . trim($arr['eml_email_address']) .
				"<br/><b>Vendor City : </b>" . $cityName;
		$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
		$mail->setLayout('mail');
		$mail->AddReplyTo($arr['eml_email_address'], $arr['first_name']);
		//	$mail->setFrom('info@aaocab.com', 'Info aaocab');
		$mail->setTo('vendors@aaocab.in', 'Gozo Operator Team');
		$mail->setBody($body);
		if ($arr['vnp_cars_own'] == 1)
		{
			$mail->setSubject("New DCO sign up in ($cityName)");
		}
		else
		{
			$mail->setSubject("New vendor signed up in ($cityName)");
		}
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$usertype	 = EmailLog::Admin;
		$email2		 = 'vendors@aaocab.in';
		if ($arr['vnp_cars_own'] == 1)
		{
			$subject = "New DCO sign up in ($cityName)";
		}
		else
		{
			$subject = "New vendor signed up in ($cityName)";
		}
		emailWrapper::createLog($email2, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL, 0);
		$this->attachTaxiMail($vendorId, $password);
	}

	public function agentJoinEmail($cityName, $agentId, $password = '')
	{
		$model		 = Agents::model()->findByPk($agentId);
		$agent_name	 = $model->agt_fname . " " . $model->agt_lname;
		$body		 = "<h3><b>New Agent sign up in ('$cityName')</b></h3>" .
				"<b>Agent name : </b>" . trim($model->agt_fname . " " . $model->agt_lname) .
				"<br/><b>Phone : </b>" . trim($model->agt_phone) .
				"<br/><b>Email : </b>" . trim($model->agt_email) .
				"<br/><b>City : </b>" . $cityName;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_AGENT_EMAIL);
		$email		 = 'agents@aaocab.in';
		$subject	 = "New Agent signed up in ($cityName)";
		$mail->setLayout('mail1');
		if ($model->agt_email != '')
		{
			$mail->AddReplyTo($model->agt_email, $agent_name);
		}
		$mail->setTo(array($email => 'Gozo Agent Services'));
		$mail->setBody($body);
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$refType = EmailLog::REF_AGENT_ID;
		$refId	 = $model->agt_id;
		emailWrapper::createLog($email, $subject, $body, "", EmailLog::Agent, $delivered, '', $refType, $refId, EmailLog::SEND_AGENT_EMAIL, 0);
	}

	public function fschangeaddress($bkgId)
	{
		$model = Booking::model()->findByPk($bkgId);
		/* @Var $model Booking */
		if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)))
		{
			return false;
		}

		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$this->email_receipient	 = $email;
		$bookarr				 = ['userName'	 => $model->bkgUserInfo->getUsername(),
			'booking_id' => $model->bkg_booking_id,
			'pickup'	 => $model->bkg_pickup_address,
			'seat'		 => $model->bkgAddInfo->bkg_no_person,
			'pickupTime' => $model->bkg_pickup_date,
			'gender'	 => Users::model()->genderList[$model->bkgUserInfo->bkgUser->usr_gender]];

		$emailArr	 = [];
		$logArr		 = [];

		if ($email != '' && $model->bkgUserInfo->bkgUser != '')
		{
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('flexxi_addresschange');
			$mail->setData(array('arr' => $bookarr));
			$mail->setLayout('mail');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());

			$subject = 'Pickup Address & Time for your upcoming FLEXXI share ride from' . Cities::getName($model->bkg_from_city_id) . ' to ' . Cities::getName($model->bkg_to_city_id);
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_CAB_DRIVER_DETAIL;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $model->bkg_id, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
		}

		if ($elgId != '')
		{
			BookingLog::model()->createLog($model->bkg_id, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			return true;
		}
		return false;
	}

	public function flexxiBookingMatched($bkgId, $isCancel = false)
	{
		$model = Booking::model()->with('bkgUserInfo', 'bkgPref')->findByPk($bkgId);
		/* @Var $model Booking */
		if (($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0)))
		{
			return false;
		}
		$promoterId = BookingSub::model()->getPromoterIdForFlexxiBooking($model->bkg_bcb_id);
		if ($promoterId == '')
		{
			$promoterModel = Booking::model()->findByPk($model->bkg_id);
		}
		else
		{
			$promoterModel = Booking::model()->findByPk($promoterId);
		}
		//$email			 = $model->bkgUserInfo->bkg_user_email;
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$this->email_receipient	 = $email;
		$bookarr				 = ['userName'	 => $model->bkgUserInfo->getUsername(),
			'booking_id' => $model->bkg_booking_id];

		$emailArr	 = [];
		$logArr		 = [];

		if ($email != '' && $model->bkgUserInfo->bkgUser != '')
		{
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			if ($isCancel == false)
			{
				$mail->setView('flexxi_matched');
			}
			else
			{
				$mail->setView('cancel_flexxi_matched');
			}
			$mail->setData(array('arr' => $bookarr));
			$mail->setLayout('mail');
			$mail->setTo($email, $model->bkgUserInfo->getUsername());
			if ($isCancel == false)
			{
				$subject = 'We found a match! Your FLEXXI Share booking ' . $model->bkg_booking_id . ' is now confirmed from ' . Cities::getName($model->bkg_from_city_id) . ' to ' . Cities::getName($model->bkg_to_city_id);
			}
			else
			{
				$subject = 'Sorry! We could find riders to share a taxi with you for booking ' . $model->bkg_booking_id;
			}
			$mail->setSubject($subject);
			if ($mail->sendMail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_FLEXXI_MATCHED;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $model->bkg_booking_id, $usertype, $delivered, $emailType, $refType, $model->bkg_id, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
		}

		if ($elgId != '')
		{
			if ($isCancel)
			{
				$subject = "Email sent on booking cancelled, No flexxi share match found.";
			}
			BookingLog::model()->createLog($model->bkg_id, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
			return true;
		}
		return false;
	}

	public function flexxiBookingAlert($name, $email, $fromCity, $toCity, $fromDate, $toDate, $bkg_id, $id, $count)
	{
		/* @Var $model Booking */
		$hash					 = Yii::app()->shortHash->hash($bkg_id);
		$url					 = "https://aaocab.com/bknw/$bkg_id/$hash/$id";
		$this->email_receipient	 = $email;
		$bookarr				 = ['userName'		 => $name,
			'fromCity'		 => $fromCity,
			'toCity'		 => $toCity,
			'fromDate'		 => $fromDate,
			'toDate'		 => $toDate,
			'noOfBookings'	 => $count,
			'url'			 => $url
		];

		$emailArr	 = [];
		$logArr		 = [];

		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$mail->setView('flexxi_alert');
			$mail->setData(array('arr' => $bookarr));
			$mail->setLayout('mail1');
			$mail->setTo($email, $name);
			$subject = 'ALERT – Gozo SHARE Match found';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Consumers;
			$emailType	 = EmailLog::EMAIL_FLEXXI_ALERT;
			$refType	 = EmailLog::REF_BOOKING_ID;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, null, $usertype, $delivered, $emailType, $refType, null, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			return true;
		}

//		if ($elgId != '')
//		{
//			if ($isCancel)
//			{
//				$subject = "Email sent on booking cancelled, No flexxi match found.";
//			}
//			BookingLog::model()->createLog($model->bkg_id, $subject, UserInfo::model(), BookingLog::EMAIL_SENT, false, ["blg_ref_id" => $elgId]);
//			return true;
//		}
		return false;
	}

	public function reportBookingMail()
	{

		$email		 = Yii::app()->params['adminEmail'];
		$delivered	 = '';
		if ($email != '')
		{
			$bdata					 = BookingSub::model()->getBookingsByPickupAddress();
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setTo(Yii::app()->params['adminEmail'], 'Leaders aaocab');
			$mail->clearView();
			$style					 = ' style="padding: 5px;"';
			$style1					 = ' style="padding: 5px; text-align:center"';
			$tot					 = count($bdata);
			$body1					 .= '<h3>Booking Pickup/Drop Address as on (' . date('d/m/Y h:i A', strtotime(date('Y-m-d'))) . ')</h3><br>
				Total ' . $tot . ' record(s) found <br>
			<table border="1" style="border-collapse: collapse">
			<thead>
			<tr>
				<th' . $style . '>	SL No.	</th>
				<th' . $style . '>	Booking Id</th>
				<th' . $style . '>	From City</th>
                <th' . $style . '>	To City</th>
				<th' . $style . '>	Pickup Address</th>
				<th' . $style . '>	Drop Address</th>
				<th' . $style . '>	Type/Name</th>
			</tr>
		</thead>
            <tbody>';
			$body2					 = "";
			$k						 = 0;
			if (count($bdata) > 0)
			{
				foreach ($bdata as $b)
				{
					$body2 .= '<tr>
				<td' . $style1 . '>' . ($k + 1) . '.</td>
				<td' . $style . '>' . $b['bkg_booking_id'] . '</td>
				<td' . $style . '>' . $b['from_city'] . '</td>
				<td' . $style . '>' . $b['to_city'] . '</td>
				<td' . $style . '>' . $b['bkg_pickup_address'] . '</td>
				<td' . $style . '>' . $b['bkg_drop_address'] . '</td>
				<td' . $style . '>' . $b['B2B/B2C'] . ' (' . $b['Name'] . ')' . '</td>
				</tr>';
					$k++;
				}
			}
			else
			{
				$body2 .= '<tr>
                            <td' . $style1 . ' colspan="14">No Records Yet Found.</td>
                          </tr>';
			}
			$body3	 = '</tbody></table>';
			$body	 = $body1 . $body2 . $body3;
			$mail->setBody($body);
			$mail->clearLayout();
			$subject = 'Bookings Pickup / Drop Address';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
		}
	}

//	public function adminVendorSignupEmail($model)
//	{
//		$cityModel	 = Cities::model()->findByPk($model->vnd_city);
//		$body		 = "<h3><b>New Vendor Signed Up</b></h3>" .
//				"<b>Vendor Name : </b>" . $model->vnd_owner .
//				"<br/><b>Company Name : </b>" . $model->vnd_company .
//				"<br/><b>Vendor Phone : </b>" . $model->vnd_phone .
//				"<br/><b>Vendor Email : </b>" . $model->vnd_email .
//				"<br/><b>Vendor City : </b>" . $cityModel->cty_name;
//		$mail		 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
//		$mail->setLayout('mail');
//		$mail->AddReplyTo($model->vnd_email, $model->vnd_owner);
//		$mail->setTo(array('vendors@aaocab.in' => 'Gozo Operator Team'));
////		$mail->setFrom('info@aaocab.com', 'Info aaocab');
////		$mail->setTo(array('team@aaocab.in' => 'Team aaocab', 'info@aaocab.com' => 'Info aaocab'));
//		$mail->setBody($body);
//
//		$mail->setSubject("New vendor signed up");
//		if ($mail->sendMail())
//		{
//			$delivered = "Email sent successfully";
//		}
//		else
//		{
//			$delivered = "Email not sent";
//		}
//		$usertype	 = EmailLog::Admin;
//		$email2		 = 'vendors@aaocab.in';
//		$subject	 = 'New vendor signed up';
//		$refType	 = EmailLog::REF_AGENT_ID;
//		$refId		 = $model->vnd_id;
//		//$this->createLog($email1, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
//		$this->createLog($email2, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
//		$this->attachTaxiMail($model->vnd_id, $model->vnd_password1);
//	}


	public function adminVendorSignupEmail($model, $contid)
	{
		$contactModel = Contact::model()->getContactDetails($contid);
		//if($model->vnd_firm_type == 2 || $model->vnd_firm_type == 3 || $model->vnd_firm_type == 4){
		if ($contactModel['ctt_user_type'] == 1)
		{
			$vendorName	 = "<b>Vendor Name : </b>" . $contactModel['ctt_first_name'] . '' . $contactModel['ctt_last_name'];
			$companyName = "";
		}
		else
		{
			$vendorName	 = "";
			$companyName = "<br/><b>Company Name : </b>" . $contactModel['ctt_business_name'];
		}


		$cityModel	 = Cities::model()->findByPk($contactModel['ctt_city']);
		$body		 = "<h3><b>New Vendor Signed Up</b></h3>" .
				$vendorName . $companyName .
				"<br/><b>Vendor Phone : </b>" . $contactModel['phn_phone_no'] .
				"<br/><b>Vendor Email : </b>" . $contactModel['eml_email_address'] .
				"<br/><b>Vendor City : </b>" . $cityModel->cty_name;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
		$mail->setLayout('mail');
		$mail->AddReplyTo($contactModel['eml_email_address'], $contactModel['ctt_business_name']);
		$mail->setTo('vendors@aaocab.in', 'Gozo Operator Team');
		// dco needs to include here
		$subject	 = "New vendor signed up in ($cityModel->cty_name)";
//     //$mail->setTo(array('vendors@aaocab.in' => 'Gozo Operator Team'));		
//		$mail->setFrom('info@aaocab.com', 'Info aaocab');
//		$mail->setTo(array('team@aaocab.in' => 'Team aaocab', 'info@aaocab.com' => 'Info aaocab'));
		$mail->setBody($body);
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$usertype	 = EmailLog::Admin;
		$email2		 = 'vendors@aaocab.in';
		$subject	 = 'New vendor signed up';
		$refType	 = EmailLog::REF_AGENT_ID;
		$refId		 = $model->vnd_id;
		$delay_time	 = 0;
		//$this->createLog($email1, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL);
		$this->createLog($email2, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL, $delay_time);
		$this->attachTaxiMail($model->vnd_id, $model->vnd_password1);
	}

	public function reportCompletedBookingReport()
	{
		$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
		if ($mail->From != '')
		{
			$bdata	 = BookingSub::model()->getCompletedBookingsToday2();
			//$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			//$mail->setTo(Yii::app()->params['gozocaresEmail'], 'Gozocares aaocab');
			//$mail->clearView();
			$style	 = ' style="padding: 5px;"';
			$style1	 = ' style="padding: 5px; text-align:center"';
			$tot	 = count($bdata);
			$body1	 .= '<h3>Bookings Completed on (' . date('d/m/Y h:i A', strtotime(date('Y-m-d') . '-2 days')) . ')</h3><br>
				Total ' . $tot . ' record(s) found <br>
					<table border="1" style="border-collapse: collapse">
					<thead>
						<tr>
							<th' . $style . '>	SL No.	</th>
							<th' . $style . '>	Booking Id</th>
							<th' . $style . '>	Customer Name</th>
							<th' . $style . '>	Route</th>
							<th' . $style . '>	Start Date</th>
							<th' . $style . '>	Phone No.</th>
							<th' . $style . '>	Overall Rating</th>
							<th' . $style . '>	Email</th>
							<th' . $style . '>	called  & talked</th>
							<th' . $style . '>	call-back</th>	
						</tr>
					</thead>
            <tbody>';
			$body2	 = "";
			$k		 = 0;
			if (count($bdata) > 0)
			{
				foreach ($bdata as $b)
				{
					$body2 .= '<tr>
				<td' . $style1 . '>' . ($k + 1) . '.</td>
				<td' . $style . '>' . $b['bkg_booking_id'] . '</td>
				<td' . $style . '>' . $b['CustName'] . '</td>	
				<td' . $style . '>' . $b['route'] . '</td>
				<td' . $style . '>' . $b['pickup_date'] . '</td>
				<td' . $style . '>' . $b['PhoneNumber'] . '</td>
				<td' . $style . '>' . $b['rtg_customer_overall'] . '</td>
				<td' . $style . '>' . $b['bkg_user_email'] . '</td>
				<td' . $style . '>' . '     ' . '</td>
				<td' . $style . '>' . '     ' . '</td>	
				</tr>';
					$k++;
				}
			}
			else
			{
				$body2 .= '<tr>
                            <td' . $style1 . ' colspan="10">No Records Yet Found.</td>
                          </tr>';
			}
			$body3	 = '</tbody></table>';
			$body	 = $body1 . $body2 . $body3;
			$subject = 'Completed bookings (today-2)';
			$mail	 = EIMailer::getInstance(EmailLog::SEND_DAILY_EMAIL);
			$mail->clearView();
			$mail->clearLayout();
			$mail->setBody($body);
			$mail->setTo(Yii::app()->params['gozocaresEmail'], 'Gozocares aaocab');
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo $delivered;
		}
	}

	public function suggestRoute($suggestRutId, $userId)
	{
		$model		 = Route::model()->findByPk($suggestRutId);
		$userModel	 = Users::model()->findByPk($userId);
		if ($model != '')
		{
			//$email			 = $userModel->usr_email;
			$email					 = ContactEmail::model()->getEmailByUserId($userId);
			$usrName				 = $userModel->usr_name . " " . $userModel->usr_lname;
			$this->email_receipient	 = $email;
			$bookarr				 = ['userName'	 => $usrName,
				'fromCity'	 => $model->rutFromCity->cty_name,
				'toCity'	 => $model->rutToCity->cty_name
			];

			$emailArr	 = [];
			$logArr		 = [];

			if ($email != '' && $userModel != '')
			{
				$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
				$mail->setView('suggest_route');
				$mail->setData(array('arr' => $bookarr));
				$mail->setLayout('mail');
				$mail->setTo($email, $usrName);

				$subject = 'Special surprise. Go from ' . $model->rutFromCity->cty_name . ' to ' . $model->rutToCity->cty_name;
				$mail->setSubject($subject);
				if ($mail->sendMail())
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
				$usertype	 = EmailLog::Consumers;
				$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
				$refType	 = EmailLog::REF_USER_ID;
				$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $booking_id	 = '', $usertype, $delivered, $emailType, $refType, $userModel->user_id, EmailLog::SEND_CONSUMER_BATCH_EMAIL);
			}
			return false;
		}
		else
		{
			return false;
		}
	}

	public function SendLink($vndId, $emailAddress)
	{
		$vndModel				 = Vendors::model()->findByPk($vndId);
		$userName				 = $vndModel->vndContact->getName();
		$hash					 = Yii::app()->shortHash->hash($vndId);
		$email					 = $emailAddress;
		$this->email_receipient	 = $emailAddress;
		$bookarr				 = ['userName'	 => $userName,
			'hash'		 => $hash,
			'vndId'		 => $vndId
		];

		$emailArr	 = [];
		$logArr		 = [];

		if ($email != '')
		{
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('social_link');
			$mail->setData(array('arr' => $bookarr));
			$mail->setLayout('mail2');
			$mail->setTo($email, $usrName);

			$subject = 'Gozo Partner. Link your social account.';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Vendor;
			$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
			$refType	 = EmailLog::REF_USER_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $booking_id	 = '', $usertype, $delivered, $emailType, $refType, $userModel->user_id, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
		}
		return false;
	}

	public function SendLinkOtp($vndId, $emailAddress, $otp)
	{
		$vndModel				 = Vendors::model()->findByPk($vndId);
		$userName				 = $vndModel->vndContact->getName();
		$hash					 = Yii::app()->shortHash->hash($vndId);
		$createStamp			 = time();
		$vendorLink				 = 'https://gozo.cab/vsl/' . $hash . '/' . $createStamp;
		$email					 = $emailAddress;
		$this->email_receipient	 = $emailAddress;
		$bookarr				 = ['userName'	 => $userName,
			'otp'		 => $otp,
			'link'		 => $vendorLink
		];

		$emailArr	 = [];
		$logArr		 = [];

		if ($email != '')
		{
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('reg_social_link');
			$mail->setData(array('arr' => $bookarr));
			$mail->setLayout('mail2');
			$mail->setTo($email, $usrName);

			$subject = 'Gozo Partner. Link your social account.';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$usertype	 = EmailLog::Vendor;
			$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
			$refType	 = EmailLog::REF_USER_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, '', $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
		}
		return false;
	}

	public static function mailToApproveVendor($params)
	{
		$success = false;
		$toEmail = $params['email'];
		if (isset($toEmail) && $toEmail != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setView('approve_vendor');
			$mail->setData(array('arr' => $params));
			$mail->setLayout('mail2');
			$mail->setTo($toEmail, $params['full_name']);
			$mail->addReplyTo(array('vendors@aaocab.in' => 'Gozo Operator Team'));
			$subject = 'Congratulations! Your vendor account is approved.';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$userType	 = EmailLog::Vendor;
			$emailType	 = EmailLog::EMAIL_VENDOR_APPROVE;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($toEmail, $subject, $mail->Body, '', $userType, $delivered, $emailType, $refType, $params['vnd_id'], EmailLog::SEND_ACCOUNT_EMAIL, $delay_time);
			$success	 = true;
		}
		return $success;
	}

	public function sendEmailToEmergencyContact($bkgId, $userName, $emergencyUserName, $email, $msg, $type = 0)
	{
		$bModel					 = Booking::model()->findByPk($bkgId);
		$bookingId				 = $bModel->bkg_booking_id;
		$this->email_receipient	 = Yii::app()->params['gozoSOSEmail'];
		$usrArr					 = ['bkgId'			 => $bookingId, 'userName'		 => $userName, 'SosUserName'	 => $emergencyUserName, 'msg'			 => $msg, 'emailAddress'	 => $email
		];
		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('sos_sms_trigger');
			$mail->setData(array('arr' => $usrArr));
			$mail->setLayout('mail2');
			$mail->setTo($email, $userName);
			$mail->addReplyTo(array('sos@aaocab.in' => 'SOS Gozo Team'));
			$subject = ' ' . $userName . '(BKGID ' . $bookingId . ') has pressed Panic button';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$usertype	 = $userInfo->userType;
			$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
			$refType	 = EmailLog::REF_USER_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $bookingId, $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
			return $elgId;
		}
	}

	public function gotCreateQuoteBookingemail($bkgid, $logType = '', $delay = true)
	{
		$model = Booking::model()->findByPk($bkgid);
		//$userInfo	 = UserInfo::getInstance();

		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0) || ($model->bkgInvoice->bkg_advance_amount > 0 && ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)))
		{
			return false;
		}

		/* @var $model Booking */
		//$email		 = $model->bkgUserInfo->bkg_user_email;
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		Logger::create("Agent booking email content check" . $email, CLogger::LEVEL_PROFILE);
		$usertype	 = EmailLog::Consumers;
		$username	 = $model->bkgUserInfo->getUsername();

		if ($email != '')
		{
			$hash			 = Yii::app()->shortHash->hash($bkgid);
			//$payurl	 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bkgid, 'hash' => $hash]);
			$payurl			 = Filter::shortUrl(BookingUser::getPaymentLinkByEmail($model->bkg_id));
			$paydate		 = date('Y-m-d H:i:s', strtotime('+12 hour'));
			$date			 = date('d/m/Y', strtotime($paydate));
			$time			 = date('h:i A', strtotime($paydate));
			$workingHrdiff	 = Filter::CalcWorkingHour($model->bkg_create_date, $model->bkg_pickup_date);
			$refCodeUrl		 = "";
			if ($model->bkgUserInfo->bkg_user_id > 0)
			{
				$returnSet = Users::getReferUrl($model->bkgUserInfo->bkg_user_id);
				if ($returnSet->isSuccess())
				{
					$refCodeUrl = $returnSet->getData()['referUrl'];
				}
			}

			if ($model->bkg_agent_id == '' || $model->bkg_agent_id == 0)
			{
				QrCode::processData($model->bkgUserInfo->bkg_user_id);
				$this->email_receipient = $email;
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
				$luggageCapacity	 = Stub\common\LuggageCapacity::init($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id, $model->bkgAddInfo->bkg_no_person);
				$cancelTimes_new	 = CancellationPolicy::initiateRequest($model); //print_r($cancelTimes_new);
				//-------------------------------------
				$cancellationPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_CANCELLATION); //print_r($cancellationPoints);
				$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
				$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
				$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS); //print_r($othertermsPoints);
				//print_r($model->bkgSvcClassVhcCat->scv_scc_id);die("dsfdsf");
				$priceRule			 = PriceRule::getByCity($model->bkg_route_city_ids, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_vct_id);

				$arr	 = array('luggageCapacity'		 => $luggageCapacity,
					'refCodeUrl'			 => $refCodeUrl, 'model'					 => $model,
					'payurl'				 => $payurl, 'email_receipient'		 => $email, 'userId'				 => $model->bkgUserInfo->bkg_user_id,
					'otp'					 => $model->bkgTrack->bkg_trip_otp,
					'date'					 => $date . ' ' . $time, 'timediff'				 => $workingHrdiff,
					'cancelTimes_new'		 => $cancelTimes_new, "cancellationPoints"	 => $cancellationPoints,
					'dosdontsPoints'		 => $dosdontsPoints, 'boardingcheckPoints'	 => $boardingcheckPoints,
					'othertermsPoints'		 => $othertermsPoints, 'prarr'					 => $priceRule->attributes);
				//-------------------------------------------
				$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
				$mail->setTo($email, $username);
				//	$mail->setView('gotbookingrenew');
				$mail->setView('gotbookingrenew_new');
				/* 	$mail->setData(
				  array('luggageCapacity'=>$luggageCapacity,'refCodeUrl' => $refCodeUrl, 'model' => $model, 'payurl' => $payurl, 'email_receipient' => $email, 'otp' => $model->bkgTrack->bkg_trip_otp, 'date' => $date . ' ' . $time, 'timediff' => $workingHrdiff)
				  ); */
				$mail->setData($arr);
				$mail->setLayout('mail1');

				$subject	 = ($model->bkg_status == 15) ? 'Your quotation request: ' . $model->bkg_booking_id : 'Reservation received – Booking ID : ' . $model->bkg_booking_id;
				$eventId	 = EmailLog::EMAIL_BOOKING_CREATED;
				$mail->setSubject($subject);
				$queueFlag	 = ($delay) ? 1 : 0;
				if ($mail->sendMail($queueFlag))
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
				//email log
				$body	 = $mail->Body;
				$refType = EmailLog::REF_BOOKING_ID;
				$refId	 = $model->bkg_id;
				$elgId	 = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL, $queueFlag);
			}

			//booking log
			if ($model->bkg_id != '')
			{
				$desc = "Email sent on Booking Created.";
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
				$eventId						 = BookingLog::EMAIL_SENT;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				if ($email != '' && $model->bkg_agent_id == '')
				{
					BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
				}
			}
		}
	}

	public function sendPriceLockLinkByUserId($userId, $bookingId)
	{
		/* @var $$usermodel Users */
		$usermodel	 = Users::model()->findByPk($userId);
		$bookingCode = Booking::model()->getCodeById($bookingId);
		$userInfo	 = UserInfo::getInstance();
		$model		 = Booking::model()->findByPk($bookingId);

		$hash			 = Yii::app()->shortHash->hash($bookingId);
		$eHash			 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
		//$payment_link			 = 'https://aaocab.com/bkpn/' . $bookingId . '/e/' . $eHash;
		$payment_link	 = 'https://aaocab.com/bkpn/' . $bookingId . '/' . $hash . '/e/' . $eHash;
		//$email		 = $usermodel->usr_email;
		$email			 = ContactEmail::model()->getEmailByUserId($userId);
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			// Sent Email to customer for LINK
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('priceLocklink');
			$bookarr['userName']	 = $usermodel->usr_name;
			$bookarr['bookingCode']	 = $bookingCode;
			$bookarr['paymentLink']	 = $payment_link;
			$mail->setData(array('arr' => $bookarr, 'email_recepient' => $email, 'id' => $userId));
			$mail->setLayout('mail2');
			$mail->setTo($email);
			$subject				 = 'Price lock for your booking ' . $bookingCode . ' has expired.';

			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$refType	 = EmailLog::SEND_SERVICE_EMAIL;
			$refId		 = $bookingId;
			$usertype	 = EmailLog::Admin;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered, EmailLog::EMAIL_ALERT_CRITICALITY, $refType, $refId, EmailLog:: SEND_SERVICE_EMAIL, 0);

			$desc			 = "Price lock expiry email reminder sent for quoted booking";
			$eventidBooking	 = BookingLog::PRICE_LOCK_EXPIRY;
			BookingLog::model()->createLog($bookingId, $desc, $userInfo, $eventidBooking, $model);
		}
	}

	public function sendTripOtp($bkgId, $userName, $email, $msg, $type = 0)
	{

		//$this->email_receipient	 = Yii::app()->params['gozoSOSEmail'];
		$usrArr = ['bkgId' => $bkgId, 'userName' => $userName, 'msg' => $msg, 'emailAddress' => $email];
		if ($email != '')
		{
//			$bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $bkgId]);
//			$userId			 = $bkgUserModel->bkg_user_id;
			/* @var $model Booking */
			$model	 = Booking::model()->getByCode($bkgId);
			$userId	 = $model->bkgUserInfo->bkg_user_id;
			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('resent_trip_otp');
			$mail->setData(array('arr' => $usrArr, 'email_recepient' => $email, 'id' => $userId));
			$mail->setLayout('mail2');
			$mail->setTo($email, $userName);
			//$mail->addReplyTo(array('sos@aaocab.in' => 'SOS Gozo Team'));
			$subject = 'TRIP OTP FOR (Booking Id - ' . $bkgId . ')';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$usertype	 = SmsLog::Consumers;
			$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
			$refType	 = EmailLog::REF_USER_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $bkgId, $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
			return $elgId;
		}
	}

	public function sendGiftCard($gftSubscriberId, $gftCodeArr, $agentId)
	{
		$model		 = GiftCardSubscriber::model()->findByPk($gftSubscriberId);
		$agtModel	 = Agents::model()->findByPk($agentId);
		$email		 = $model->gcs_email_address;

		$this->email_receipient	 = $email;
		$userName				 = ucfirst($model->gcs_name);
		$agtName				 = $agtModel->agt_fname . ' ' . $agtModel->agt_lname;
		$quantity				 = $model->gcs_quantity;
		//$link = Yii::app()->createAbsoluteUrl('index/activate', array('id' => $agent_id, 'key' => $key));
		$mail					 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
		//$mail->setView('fmail');
		$mail->setData(
				array(
					'email' => $email
				)
		);

		$mail->setLayout('mail');
		//$mail->setFrom('jsrankesh.singh@gmail.com', 'Info aaocab');
		$mail->setTo($email);
		$mail->setSubject('Welcome to the Gozo');
		$style	 = ' style="padding: 5px;"';
		$style1	 = ' style="padding: 5px; text-align:center"';
		$body1	 = 'Dear ' . $userName . ',<br/>
                <br/>' . $agtName . ' has sent you ' . $quantity . ' gift card(s) for Gozo Cabs. <br/>
				Please Use the codes below to add these gift cards to your Gozo Cabs wallet.<br/>
				<br/>
				<table border="1" style="border-collapse: collapse">
				<thead>
				<tr>
					<th' . $style . '>	SL No.	</th>
					<th' . $style . '>	Gift Card Code</th>
					<th' . $style . '>	Card Redemption Value</th>
				</tr>
			</thead>
				<tbody>';
		$body2	 = "";
		$k		 = 0;
		if (count($gftCodeArr) > 0)
		{
			foreach ($gftCodeArr as $b)
			{
				$body2 .= '<tr>
					<td' . $style1 . '>' . ($k + 1) . '.</td>
					<td' . $style . '>' . $b . '</td>
					<td' . $style . '>' . $model->gcs_value_amount . '</td>
					</tr>';
				$k++;
			}
		}
		else
		{
			$body2 .= '<tr>
								<td' . $style1 . ' colspan="14">No Records Yet Found.</td>
							  </tr>';
		}
		$body3 = '</tbody></table>';

		$body4	 = '<br/>
                <br/><b>How to use the Gift cards you have received:</b>
                <br/>1. Login to your user profile on <a href="http://www.aaocab.com">aaocab.com</a>
                <br/>2. Go to your wallet.
                <br/>3. Click on add Gift card.
                <br/>4. Enter the Gift card code you have received and add its value to your Gozo wallet.
				<br/>5. Now when you create a booking, on the payment page you can Redeem the available wallet balance shown in your account.
                <br/>If you have any questions or have any special requests please email us directly at channel@aaocab.in
                <br/><br/>Regards,
                <br/>Gozo Team
                ';
		$body	 = $body1 . $body2 . $body3 . $body4;

		$mail->setBody($body);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$subject	 = 'Welcome to the Gozo Technologies Pvt Ltd';
		$usertype	 = EmailLog::Agent;
		emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, "", EmailLog::REF_AGENT_ID, $agentId, EmailLog::SEND_ACCOUNT_EMAIL, 0);
	}

	/**
	 * 
	 * @param type $email - Email address
	 * @param type $contactId - Email contact id
	 * @param type $sourceType - UserType CONSUMER = 1,VENDOR = 2 ,DRIVER = 3 , ADMIN = 4, AGENT = 5;
	 * @param type $refType - EmailLogType Consumers= 1,Vendor = 2 ,Driver = 3 , Admin = 4,MeterDown = 5,Agent = 6,Corporate = 7 							 = 7;
	 * @param type $refId  - Email ref id 
	 * @param type $templateStyle Email Template Style - NOTIFY_OLD_CON_TEMPLATE = 1 , NEW = 2, MODIFY TEMPLATE = 4;
	 * @param type $tempPkId Temporary Contact id
	 * @param type $vndId Vendor id
	 * @return boolean
	 */
	public function sendVerificationLink($email, $contactId, $sourceType, $refType, $refId, $templateStyle, $tempPkId = 0, $vndId = 0, $delay = true)
	{
		$vndName			 = "";
		$success			 = false;
		$cttHash			 = Yii::app()->shortHash->hash($contactId);
		$templateStyleHash	 = Yii::app()->shortHash->hash($templateStyle);
		$vndIdHash			 = Yii::app()->shortHash->hash($vndId);
		$cttModel			 = Contact::model()->findByPk($contactId);
		$userName			 = $cttModel->ctt_first_name . ' ' . $cttModel->ctt_last_name;
		$refType			 = ($refType != "") ? $refType : UserInfo::TYPE_CONSUMER;
		$entityId			 = ContactProfile::getEntityById($contactId, $refType);
		if (!empty($cttModel->ctt_business_name))
		{
			$userName = $cttModel->ctt_business_name;
		}
		// Unset is_expired flag 
		$param	 = ['email' => $email];
		$sql	 = "UPDATE `contact_email` SET eml_is_expired = 0 WHERE `eml_email_address` =:email";
		$cntRow	 = DBUtil::execute($sql, $param);

		switch ($templateStyle)
		{
			case Contact::NOTIFY_OLD_CON_TEMPLATE:
				$tempPkId		 = Yii::app()->shortHash->hash($tempPkId);
				$vndId			 = UserInfo::getEntityId();
				$vndIdHash		 = Yii::app()->shortHash->hash($vndId);
				$vendorModel	 = Vendors::model()->findByPk($vndId);
				$vndArry		 = explode('_', $vendorModel->vnd_name);
				$vndName		 = $vndArry[0];
				$vndHash		 = base64_encode($vndName);
				$templateStyle	 = Contact::NOTIFY_OLD_CON_TEMPLATE;
				$urlHash		 = trim($cttHash . '_' . $templateStyleHash . '_' . $tempPkId . '_' . $vndHash . '_' . $vndIdHash);
				break;
			case Contact::NEW_CON_TEMPLATE:
				$emailHash		 = base64_encode($email);
				if ($vndId)
				{
					$urlHash = trim($cttHash . '_' . $templateStyleHash . "_" . $emailHash . "_" . $vndIdHash);
				}
				else
				{
					$urlHash = trim($cttHash . '_' . $templateStyleHash . "_" . $emailHash);
				}
				break;
			case Contact::MODIFY_CON_TEMPLATE:
				$emailHash	 = base64_encode($email);
				$urlHash	 = trim($cttHash . '_' . $templateStyleHash . "_" . $emailHash);

			default:
				break;
		}
		$url	 = Yii::app()->createAbsoluteUrl('verify', ['id' => $urlHash]);
		$arrVal	 = ['userName'		 => $userName,
			'link'			 => $url,
			'templateStyle'	 => $templateStyle,
			'email'			 => $email,
			'userType'		 => $userType,
			'vndName'		 => $vndName
		];
		if ($email != '')
		{
			$mail = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('verifyContact');
			$mail->setData(array('arr' => $arrVal, 'email_receipient' => $email, 'userId' => $entityId['id'], 'refType' => $refType));
			$mail->setLayout('mail');
			$mail->setTo($email, $userName);

			$subject	 = 'confirm your email id.';
			$mail->setSubject($subject);
			$queueFlag	 = ($delay) ? 1 : 0;
			if ($mail->sendMail($queueFlag))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body = $mail->Body;

			$elgId = emailWrapper::createLog($email, $subject, $body, null, $sourceType, $delivered, "", $refType, $refId, EmailLog::SEND_ACCOUNT_EMAIL, $queueFlag);
			if ($elgId > 0)
			{
				$success = true;
			}
		}
		return $success;
	}

	public function sendRescheduleTime($bkgId, $oldPickupTime, $userName, $email)
	{
		$mail			 = EIMailer::getInstance(EmailLog::SEND_ADMIN_EMAIL);
		$model			 = Booking::model()->findByPk($bkgId);
		$hash			 = Yii::app()->shortHash->hash($bkgId);
		$eHash			 = Yii::app()->shortHash->hash($model->bkgUserInfo->bkg_verifycode_email);
		$payment_link	 = 'https://aaocab.com/bkpn/' . $bkgId . '/' . $hash . '/e/' . $eHash;

		$mail->setData(array('email_receipient' => $email, 'userId' => $model->bkgUserInfo->bkg_user_id, 'email' => $email));

		$mail->setLayout('mail');
		//$mail->setFrom('', 'Info aaocab');
		$mail->setTo($email);
		$subject = 'Booking ID ' . $model->bkg_booking_id . ' Updated. Pickup time rescheduled';
		$mail->setSubject($subject);
		$body	 = 'Dear ' . $userName . ',<br/>
                <br/> This is to notify you that we have updated the pickup time for your booking ' . $model->bkg_booking_id . '.  <br/>
				If this change was not made upon your request, please contact us immediately. <br/><br/>
				Booking details: <br/>
				Name: ' . $userName . '<br/>
				From Address: ' . $model->bkg_pickup_address . '<br/>
				To Address: ' . $model->bkg_drop_address . '<br/>

				Car Category: ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(<strong>' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . '</strong>)<br/>
				Pickup date and time: ' . date("d/m/Y h:i A", strtotime($model->bkg_pickup_date)) . '( Previous Time:' . $oldPickupTime . ')<br/><br/>
				For live details and status updates on your trip please ' . $payment_link . ' <br/>

				Regards,<br/> 
				Team Gozo<br/>
                ';
		$mail->setBody($body);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$userInfo	 = UserInfo::getInstance();
		$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
		$usertype	 = SmsLog::Consumers;
		$delay_time	 = 0;
		$bookingId	 = $model->bkg_booking_id;

		//$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $bkgId, $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
		$elgId = emailWrapper::createLog($email, $subject, $mail->Body, $bookingId, $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
		return false;
	}

	public static function sendCancelFlagNotificationToPartner($bookingIds, $partnerId)
	{
		$agtmodel	 = Agents::model()->findByPk($partnerId);
		$email		 = $agtmodel->agt_email;
		$company	 = $agtmodel->agt_company;
		$name		 = $agtmodel->agt_fname . ' ' . $agtmodel->agt_lname;
		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_AGENT_EMAIL);
			$mail->setLayout('mail');
			$mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $name);
			$subject = 'INSUFFICIENT FUNDS, Upcoming bookings are subject to auto-cancel';

			$body	 = 'Dear ' . $name . ',<br/><br/>';
			$body	 .= 'Following booking IDs are subject to auto-cancel as your partner account does not have insufficient funds.' . $data['bkg_booking_id'] . '';
			$body	 .= '<br/>Booking IDS: ' . $bookingIds;
			$body	 .= '<br/>Please deposit additional funds if you would like this booking not to get cancelled.';
			$body	 .= '<br/><br/>Thank you,
				    <br/>Regards, Gozo Administration team';
			$mail->setBody($body);
			$mail->setSubject($subject);
			$success = $mail->sendMail();
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Agent;
			$emailType	 = EmailLog::EMAIL_AGENT_NOTIFY_CANCEL_FLAG;
			$refType	 = EmailLog::REF_AGENT_ID;
			$refId		 = $partnerId;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $partnerId, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_AGENT_EMAIL);

			$success = true;
		}
		return $success;
	}

	public static function sendDownloadInvoiceLink($email, $link, $adminId, $jobid, $agentName, $date1, $date2, $reqType)
	{
		if ($email != '' && $link != '')
		{
			$name	 = 'Admin';
			$mail	 = EIMailer::getInstance(EmailLog::SEND_ADMIN_EMAIL);
			$mail->setLayout('mail');
			$mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $name);

			if ($reqType == 1)
			{
				$sub = 'Invoice';
			}
			if ($reqType == 2)
			{
				$sub = 'Trip Summary sheet';
			}
			if ($reqType == 3)
			{
				$sub = 'Proforma';
			}

			$subject = 'Job ID ' . $jobid . ': ' . $sub . ' download is ready';

			$body	 = 'Dear ' . $name . ',<br/><br/>';
			$body	 .= 'Your ' . $sub . ' request job id ' . $jobid . ' is now complete.';
			$body	 .= '<br/>Criteria: Agent name: ' . $agentName;
			$body	 .= '<br/>Pickup date range: ' . $date1 . ' to ' . $date2;
			$body	 .= '<br/>Booking type: All completed & Cancelled bookings';
			$body	 .= '<br/>File: <a href="' . $link . '" target="_blank">Download file here</a>. This file will auto-delete after 7 days.';
			$body	 .= '<br/><br/>Regards, Gozo Administration team';
			$mail->setBody($body);
			$mail->setSubject($subject);
			$success = $mail->sendMail();
			if ($success)
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$usertype	 = EmailLog::Admin;
			$emailType	 = EmailLog::EMAIL_INVOICE;
			$refType	 = EmailLog::REF_ADMIN_ID;
			$refId		 = $adminId;
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $partnerId, $usertype, $delivered, $emailType, $refType, $refId, EmailLog::SEND_AGENT_EMAIL);

			$success = true;
		}
		return $success;
	}

	/**
	 * 
	 * @param VoucherSubscriber $model
	 * @return boolean
	 */
	public static function sendVoucherRedeemCode($model)
	{
		/* @var $model VoucherSubscriber */
		$email		 = $model->vsb_email;
		$userName	 = $model->vsb_name;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
		$mail->setView('redeemCode');
		$mail->setData(array('arr' => $model));
		$mail->setLayout('mail2');
		$mail->setTo($email, $userName);
		$subject	 = 'Your aaocab.com order of ' . $model->vsbVch->vch_title;
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$userInfo	 = UserInfo::getInstance();
		$emailType	 = EmailLog::EMAIL_VOUCHER_SUBSCRIBER;
		$refType	 = EmailLog::REF_USER_ID;
		$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, null, "", $delivered, $emailType, $refType, $userInfo->userId, EmailLog::Consumers);
		return $elgId;
	}

	/**
	 * 
	 * @param $model VoucherOrder
	 * @return type
	 */
	public static function sendVoucherConfirmation($model)
	{
		$email		 = $model->vor_email;
		$userName	 = $model->vor_name;
		$mail		 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
		$mail->setView('voucherConfirmation');
		$mail->setData(array('arr' => $model));
		$mail->setLayout('mail2');
		$mail->setTo($email, $userName);
		$subject	 = 'Confirmation of your Gozo Cabs Digital Order Number ' . $model->vor_number;
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$userInfo	 = UserInfo::getInstance();
		$emailType	 = EmailLog::EMAIL_VOUCHER_CONFIRM;
		$refType	 = EmailLog::REF_USER_ID;
		$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, null, "", $delivered, $emailType, $refType, $userInfo->userId, EmailLog::Consumers);
		return $elgId;
	}

	public static function sendResetPasswordLink($userId = NULL, $agentId = NULL, $userType = NULL, $refId = NULL, $sourceType = NULL)
	{
		$success = false;
		$cttId	 = 0;
		if (empty($userId))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		if ($agentId)
		{
			$cttId = ContactProfile::getByEntityId($agentId, UserInfo::TYPE_AGENT);
		}
		$contactId = ContactProfile::getByUserId($userId) ? ContactProfile::getByUserId($userId) : $cttId;
		if (empty($contactId) || $contactId == 0)
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$cttHash		 = Yii::app()->shortHash->hash($contactId);
		$contactEmail	 = ContactEmail::model()->findByConId($contactId);
		$email			 = $contactEmail[0]->eml_email_address;
		$emailHash		 = base64_encode($email);
		$useridHash		 = Yii::app()->shortHash->hash($userId);
		$agentidHash	 = Yii::app()->shortHash->hash($agentId);
		$contactModel	 = Contact::model()->findByPk($contactId);
		$usersModel		 = Users::model()->findByPk($userId);
		$userName		 = $contactModel->ctt_first_name;
		if ($agentId)
		{
			$urlHash = trim($useridHash . '_' . $emailHash . "_" . $cttHash . "_" . $agentidHash);
		}
		else
		{
			$urlHash = trim($useridHash . '_' . $emailHash . "_" . $cttHash);
		}
		$param	 = ['email' => $email];
		$sql	 = "UPDATE `contact_email` SET eml_is_expired = 0 WHERE `eml_email_address` =:email";
		$cntRow	 = DBUtil::execute($sql, $param);
		$url	 = Yii::app()->createAbsoluteUrl('resetpassword', ['id' => $urlHash]);
		$arrVal	 = ['userName'	 => $userName,
			'link'		 => $url,
			'email'		 => $email,
		];
		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('verifyaccount_reset_password');
			$mail->setData(array('arr' => $arrVal));
			$mail->setLayout('mail2');
			$mail->setTo($email, $userName);
			$subject = 'Reset your Password';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body	 = $mail->Body;
			$elgId	 = emailWrapper::createLog($email, $subject, $body, null, $userType, $delivered, "", 0, $refId, EmailLog::SEND_ACCOUNT_EMAIL, 0);
			if ($elgId > 0)
			{
				$success = true;
			}
		}
		return $success;
	}

	public static function sendOtp($email, $otp)
	{
		$usrArr = ['email'	 => $email,
			'otp'	 => $otp
		];
		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('cus_otp');
			$mail->setData(array('arr' => $usrArr));
			$mail->setLayout('mail2');
			$mail->setTo($email, $userName);
			//$mail->addReplyTo(array('sos@aaocab.in' => 'SOS Gozo Team'));
			$subject = 'Login OTP ';
			$mail->setSubject($subject);
			$success = $mail->sendMail(0);
			if ($success)
			{
				$delivered = "Email sent successfully.";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$usertype	 = SmsLog::Consumers;
			$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
			$refType	 = EmailLog::REF_USER_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, '', $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
			return $success;
		}
	}

	public static function sendCustomEmail($vndId, $email, $message, $subject)
	{
		$usrArr = ['message' => $message
		];
		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
			$mail->setView('vendor_link');
			$mail->setData(array('arr' => $usrArr));
			$mail->setLayout('mail2');
			$mail->setTo($email);
			$mail->setSubject($subject);
			$success = $mail->sendMail(0);
			if ($success)
			{
				$delivered = "Email sent successfully.";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$usertype	 = SmsLog::Admin;
			$emailType	 = EmailLog::EMAIL_CUSTOM;
			$refType	 = EmailLog::REF_VENDOR_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, $booking_id	 = '', $usertype, $delivered, $emailType, $refType, $vndId, EmailLog::SEND_CUSTOM_EMAIL, $delay_time);
			return $success;
		}
	}

	public static function emailVerificationOtp($email, $otp)
	{
		$usrArr = ['email'	 => $email,
			'otp'	 => $otp
		];
		if ($email != '')
		{
			$mail	 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('verifyOtp');
			$mail->setData(array('arr' => $usrArr));
			$mail->setLayout('mail2');
			$mail->setTo($email);
			//$mail->addReplyTo(array('sos@aaocab.in' => 'SOS Gozo Team'));
			$subject = 'Verify OTP ';
			$mail->setSubject($subject);
			$success = $mail->sendMail(0);
			if ($success)
			{
				$delivered = "Email sent successfully.";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$userInfo	 = UserInfo::getInstance();
			$usertype	 = SmsLog::Consumers;
			$emailType	 = EmailLog::EMAIL_ROUTE_SUGGEST_RE1;
			$refType	 = EmailLog::REF_USER_ID;
			$delay_time	 = 0;
			$elgId		 = emailWrapper::createLog($email, $subject, $mail->Body, '', $usertype, $delivered, $emailType, $refType, $userInfo->userId, EmailLog::SEND_CONSUMER_BATCH_EMAIL, $delay_time);
			return $success;
		}
	}

	public function signupUserCredential($userId, $password, $isAutoGenerated = true)
	{
		$usermodel	 = Users::model()->findByPk($userId);
		$fName		 = $usermodel->usr_name;
		$lName		 = $usermodel->usr_lname;
		$email		 = $usermodel->usr_email;

		if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_ACCOUNT_UPDATE_AND_INFO))
		{
			return false;
		}
		//$email = $usermodel->usr_email;
		if ($email != '')
		{
			$this->email_receipient			 = $email;
			$mail							 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('userCredential');
			$arrInfo['userName']			 = $fName . ' ' . $lName;
			$arrInfo['password']			 = $password;
			$arrInfo['link']				 = Yii::app()->createAbsoluteUrl('users/changePassword');
			$arrInfo['isAutoGeneratedPass']	 = $isAutoGenerated;
			$mail->setData(array('arr' => $arrInfo, 'email_receipient' => $email, 'userId' => $userId));
			$mail->setLayout('mail');
			$mail->setTo($email, $fName);
			$subject						 = 'Welcome to aaocab.';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$deliveredArr	 = ['flag' => 1, 'msg' => "Email sent successfully"];
				$delivered		 = "Email sent successfully";
			}
			else
			{
				$deliveredArr	 = ['flag' => 0, 'msg' => "Email not sent"];
				$delivered		 = "Email not sent";
			}

			$body		 = $mail->Body;
			$usertype	 = EmailLog::Consumers;
			$refId		 = '';
			$refType	 = '';
			$elgId		 = emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered, EmailLog::EMAIl_USER_ACCOUNT_CONFIRM, $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			return $deliveredArr;
		}
	}

	public function slaveSinkMail($email, $connectionString, $isSlaverRunning, $delayedByTime, $rowProcessList)
	{
		$returnSet = Yii::app()->cache->get('sendMail');
		if ($returnSet === false)
		{
			if ($email != '')
			{
				$this->email_receipient	 = $email;
				$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
				$mail->setView('slaveSink');
				$mail->setData(array('time' => date("Y-m-d H:i:s"), 'connectionString' => $connectionString, 'isSlaverRunning' => $isSlaverRunning, 'delayedByTime' => $delayedByTime, 'processList' => $rowProcessList));
				$mail->setLayout('mail');
				$mail->setTo($email);
				$subject				 = 'Slave database is down or sync problem';
				$mail->setSubject($subject);
				if ($mail->sendMail(0))
				{

					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}
				$body		 = $mail->Body;
				$usertype	 = EmailLog::EMAIL_CUSTOM;
				emailWrapper::createLog($email, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
			}
			Yii::app()->cache->set('sendMail', 1, 3600);
		}
	}

	public function mailGnowCreated($bkgId)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$userInfo	 = UserInfo::getInstance();
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		/* @var $model Booking */
		//$email		 = $model->bkgUserInfo->bkg_user_email;
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$usertype	 = EmailLog::Consumers;
		$username	 = $model->bkgUserInfo->getUsername();

		if ($model->bkg_agent_id == null || $model->bkg_agent_id == '1249')
		{
			$userId			 = $model->bkgUserInfo->bkg_user_id;
			$uniqueQrCode	 = QrCode::getCode($userId);
			if ($uniqueQrCode == null || $uniqueQrCode == "")
			{
				BookingScheduleEvent::add($bkgId, BookingScheduleEvent::GENERATE_QR_CODE, 'Generate QR Code');
				$contactId = Users::getContactByUserId($userId);
				if ($contactId > 0)
				{
					QrCode::saveCodeById($userId, $contactId);
				}
			}
		}

		if ($email != '')
		{
			//send mail
			$this->email_receipient	 = $email;
			$dosdontsPoints			 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
			$boardingcheckPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
			$othertermsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS);
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('gnowcreated');
			$hash					 = Yii::app()->shortHash->hash($bkgId);
			$payurl					 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			$mail->setData(
					array('model'					 => $model, 'payurl'				 => $payurl, 'dosdontsPoints'		 => $dosdontsPoints, 'boardingcheckPoints'	 => $boardingcheckPoints,
						'othertermsPoints'		 => $othertermsPoints, 'email_receipient'		 => $email, 'userId'				 => $model->bkgUserInfo->bkg_user_id)
			);
			$mail->setLayout('mail1');

			if ($model->bkg_agent_id == 0 || $model->bkg_agent_id == '')
			{
				$mail->setTo($email, $username);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			else
			{
				return false;
			}
			$subject = 'New booking request received – Booking ID : ' . $model->bkg_booking_id;
			$eventId = EmailLog::EMAIL_BOOKING_CREATED;

			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}


			//email log
			$body	 = $mail->Body;
			$refType = EmailLog::REF_BOOKING_ID;
			$refId	 = $model->bkg_id;

			$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog:: SEND_SERVICE_EMAIL);
			if ($elgId != '')
			{
				$desc		 = "Email sent for Gnow booking.";
				$eventId	 = BookingLog::EMAIL_SENT;
				$oldModel	 = '';
				if ($bkgId != '')
				{
					$oldModel = clone $model;
				}
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
			return $elgId;
		}
	}

	public function mailGnowOfferReceived($bkgId)
	{
		$model		 = Booking::model()->findByPk($bkgId);
		$userInfo	 = UserInfo::getInstance();
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
		{
			return false;
		}
		$bidCount	 = BookingVendorRequest::getOfferCountByTrip($model->bkg_bcb_id);
		/* @var $model Booking */
		//$email		 = $model->bkgUserInfo->bkg_user_email;
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);

		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		$usertype			 = EmailLog::Consumers;
		$username			 = $model->bkgUserInfo->getUsername();
		$dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS); //print_r($dosdontsPoints);
		$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK); //print_r($boardingcheckPoints);
		$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS);
		if ($email != '')
		{
			//send mail
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('gnowbidrequest');
			$payurl					 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
			$mail->setData(
					array('model'					 => $model, 'payurl'				 => $payurl, 'bidcount'				 => $bidCount, 'dosdontsPoints'		 => $dosdontsPoints, 'boardingcheckPoints'	 => $boardingcheckPoints,
						'othertermsPoints'		 => $othertermsPoints, 'email_receipient'		 => $email, 'userId'				 => $model->bkgUserInfo->bkg_user_id)
			);
			$mail->setLayout('mail1');

			if ($model->bkg_agent_id == 0 || $model->bkg_agent_id == '')
			{
				$mail->setTo($email, $username);
				if (Unsubscribe::isUnsubscribed($email, Unsubscribe::CAT_BOOKING))
				{
					return false;
				}
			}
			else
			{
				return false;
			}
			$subject = 'New offer received for your request – Booking ID : ' . $model->bkg_booking_id;
			$eventId = EmailLog::EMAIL_BOOKING_CREATED;

			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}


			//email log
			$body	 = $mail->Body;
			$refType = EmailLog::REF_BOOKING_ID;
			$refId	 = $model->bkg_id;

			$elgId = emailWrapper::createLog($email, $subject, $body, $model->bkg_booking_id, $usertype, $delivered, $eventId, $refType, $refId, EmailLog:: SEND_SERVICE_EMAIL);
			if ($elgId != '')
			{
				$desc		 = "Email sent for Gnow booking.";
				$eventId	 = BookingLog::EMAIL_SENT;
				$oldModel	 = '';
				if ($bkgId != '')
				{
					$oldModel = clone $model;
				}
				$params							 = [];
				$params['blg_ref_id']			 = $elgId;
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
			}
			return $elgId;
		}
	}

	public function userReferral($email, $name, $amt, $qrCode)
	{
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
			$mail->setView('userReferral');
			$mail->setData(array('name' => $name, 'amt' => $amt, 'qrCode' => $qrCode));
			$mail->setLayout('mail');
			$mail->setTo($email);
			$subject				 = 'Earn 10% back on that trip you just completed!';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{

				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::EMAIL_CUSTOM;
			emailWrapper::createLog($email, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
		}
	}

	public function userReferred($email, $name, $referalName, $qrCode)
	{
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
			$mail->setView('userreffered');
			$mail->setData(array('name' => $name, 'referalName' => $referalName, 'qrCode' => $qrCode));
			$mail->setLayout('mail');
			$mail->setTo($email);
			$subject				 = 'More cash back for you!';
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{

				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::EMAIL_CUSTOM;
			emailWrapper::createLog($email, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
		}
	}

	public function transferz($journeyCode)
	{
		$emailData				 = ['ankesh@gozo.cab', 'kaushal.goenka@aaocab.in'];
		$email					 = $emailData[0];
		$this->email_receipient	 = $email;
		$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
		$mail->setView('transferz');
		$mail->setData(array('journeyCode' => $journeyCode));
		$mail->setLayout('mail1');
		$mail->setTo($emailData);
		$subject				 = 'transferz offer received';
		$mail->setSubject($subject);
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$body		 = $mail->Body;
		$usertype	 = EmailLog::EMAIL_CUSTOM;
		emailWrapper::createLog($email, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
	}

	public function bookingReportToPartner($subject, $filePath, $email)
	{
		$this->email_receipient	 = $email;
		$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
		$mail->setLayout('mail2');
		$mail->setTo($email);
		$mail->setSubject($subject);
		$mail->setAttachment($filePath);
		$mail->setBody("<div>Hi Team,<br> Please find the attached sheet for booking details.<br>-Teams aaocab</div>");
		if ($mail->sendMail(0))
		{
			$delivered = "Email sent successfully";
		}
		else
		{
			$delivered = "Email not sent";
		}
		$body = $mail->Body;
		emailWrapper::createLog($this->email_receipient, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
	}

	public function sendResetPasswordLinkWithOTP($email, $otp, $link, $username, $user_id)
	{
		$flag					 = false;
		$this->email_receipient	 = $email;
		$mail					 = new YiiMailer();
		$mail					 = EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
		$mail->setView('fmailweb');
		$mail->setData(
				array(
					'username'			 => $username,
					'link'				 => $link,
					'otp'				 => $otp,
					'email_receipient'	 => $email
		));

		$mail->setLayout('mail');
		$mail->setFrom(Yii::app()->params['mail']['noReplyMail'], 'Info aaocab');
		$mail->setTo($email, $username);
		$mail->setSubject('Reset your Password');
		if ($mail->sendMail(0))
		{
			$delivered	 = "Email sent successfully";
			$flag		 = true;
		}
		else
		{
			$delivered = "Email not sent";
		}
		$body		 = $mail->Body;
		$usertype	 = EmailLog::Consumers;
		$subject	 = 'Reset your Password';
		$refId		 = $user_id;
		$refType	 = EmailLog::REF_USER_ID;
		emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
		return $flag;
	}

	/**
	 * This function is used process Email  
	 * @param Object $obj
	 * @param string $message
	 * @param Array $contentParams
	 * @param Object $receiverParams
	 * @param Object $eventScheduleParams
	 * @return boolean
	 */
	public static function process($obj, $message, $contentParams = [], $receiverParams = null, $eventScheduleParams = null)
	{
		$returnSet	 = new ReturnSet();
		$time		 = $eventScheduleParams->event_sequence == TemplateMaster::SEQ_EMAIL_CODE ? $eventScheduleParams->schedule_time : 0;
		if ($time > 0 && $eventScheduleParams->event_schedule == 1)
		{
			ScheduleEvent::add($eventScheduleParams->ref_id, $eventScheduleParams->ref_type, $eventScheduleParams->event_id, $eventScheduleParams->remarks, $eventScheduleParams->addtional_data, $time, TemplateMaster::SEQ_EMAIL_CODE);
			$returnSet->setStatus(true);
			$returnSet->setData(['type' => TemplateMaster::SEQ_EMAIL_CODE]);
		}
		else
		{
			$emailParams	 = emailWrapper::setEmailParams($receiverParams->email, $receiverParams->ref_id, $receiverParams->email_layout, $receiverParams->email_reply_to, $receiverParams->email_reply_name, $receiverParams->email_type, $receiverParams->email_user_type, $receiverParams->email_ref_type, $receiverParams->email_ref_id, $receiverParams->email_log_instance, $receiverParams->email_delay_time);
			$toEmail		 = $emailParams['email'];
			$bookingId		 = $emailParams['bookingId'];
			$emailLayout	 = $emailParams['emailLayout'];
			$emailReplyTo	 = $emailParams['emailReplyTo'];
			$emailReplyName	 = $emailParams['emailReplyName'];
			$emailType		 = $emailParams['emailType'];
			$userType		 = $emailParams['emailUserType'];
			$refType		 = $emailParams['emailRefType'];
			$refId			 = $emailParams['emailRefId'];
			$emailLog		 = $emailParams['emailLogInstance'];
			$delayTime		 = $emailParams['emailDelayTime'];

			$mail = EIMailer::getInstance($emailLog);

			Logger::writeToConsole("Mail Data Filename: " . $obj->filename);
			if ($obj->filename != null)
			{
				$fileParams = array('id' => $contentParams['primaryId'], 'arrayData' => $contentParams);
				if (Yii::app() instanceof CConsoleApplication)
				{
					Logger::trace("CConsoleApplication");
					$data = Yii::app()->command->renderFile(Yii::getPathOfAlias("application.components.Event.email.{$obj->filename}") . ".php", $fileParams, true);
				}
				else
				{
					Logger::trace("web");
					$data = Yii::app()->controller->renderFile(Yii::getPathOfAlias("application.components.Event.email.{$obj->filename}") . ".php", $fileParams, true);
				}
				#Logger::writeToConsole("Data: ".json_decode($data));
				$dataObj = json_decode($data);
				if (!$dataObj || !$dataObj->status || $dataObj == null)
				{
					#$returnSet->setStatus($dataObj->status);
					$returnSet->setStatus(false);
					$returnSet->setData(['type' => TemplateMaster::SEQ_EMAIL_CODE]);
					goto skipAll;
				}
				#Logger::writeToConsole("DataXX: ".json_decode($dataObj->data));
				$emailArr = (array) $dataObj->data;

				Logger::writeToConsole("EmailArr: " . json_decode($emailArr));
				if (isset($emailArr['model']))
				{
					unset($emailArr['model']);
				}

				$emailRecepient = ($emailArr['emailRecepient'] != "") ? $emailArr['emailRecepient'] : $emailArr['email_receipient'];
				$mail->setData(array('email_receipient' => $emailRecepient, 'userId' => $emailArr['userId'], 'params' => $emailArr));
				$mail->setTo($toEmail, $emailArr['full_name']);
				if ($dataObj->subject != null)
				{
					$obj->title = $dataObj->subject;
				}

				Logger::writeToConsole("TemplateName: " . $dataObj->templateName);
				if ($dataObj->body != '')
				{
					if ($dataObj->templateName != '')
					{
						//$mail->viewPath = "/application/components/email/view/";
						$mail->setPath(true, $eventScheduleParams->ref_type);
						$mail->setView($dataObj->templateName);
					}
					$mail->setBody($dataObj->body);
				}
				else
				{
					$msg = TemplateMaster::prepareTemplate($obj, $emailArr);
					$mail->setBody($msg);
				}
			}
			else
			{
				$setParams	 = ['email_receipient' => $toEmail];
				$userId		 = ($refType == EmailLog::REF_USER_ID) ? $refId : null;
				if ($userId > 0)
				{
					$setParams = ['email_receipient' => $toEmail, 'userId' => $userId];
				}
				$mail->setData($setParams);
				$mail->setTo($toEmail, $contentParams['userName']);
				$mail->setBody($message);
			}

			Logger::writeToConsole("EmailSubject: " . $obj->title);
			$subject = TemplateMaster::replaceVariablesInTemplate($obj->title, $contentParams);
			$mail->setSubject($subject);
			$mail->setLayout($emailLayout);
			if ($emailReplyName != null && $emailReplyTo != null)
			{
				$mail->addReplyTo(array($emailReplyTo => $emailReplyName));
			}
			Logger::writeToConsole("Sent: ");
			$delivered	 = $mail->sendMail(0) ? "Email sent successfully" : "Email not sent";
			$elgId		 = emailWrapper::createLog($toEmail, $subject, $mail->Body, $refId, $userType, $delivered, $emailType, $refType, $bookingId, $emailLog, $delayTime);
			if ($elgId > 0 && $eventScheduleParams->ref_id != null && $eventScheduleParams->ref_id > 0)
			{
				MarketingMessageTracker::add($eventScheduleParams->ref_type, $eventScheduleParams->ref_id, $contentParams['eventId'], TemplateMaster::SEQ_EMAIL_CODE, 2);
			}
			$returnSet->setStatus($elgId > 0 ? true : false);
			$returnSet->setData(['type' => TemplateMaster::SEQ_EMAIL_CODE, 'id' => $elgId]);
		}
		skipAll:
		return $returnSet;
	}

	public static function setEmailParams($email = null, $bookingId = null, $emailLayout = null, $emailReplyTo = null, $emailReplyName = null, $emailType = null, $emailUserType = null, $emailRefType = null, $emailRefId = null, $emailLogInstance = null, $delayTime = 0)
	{
		return array(
			'email'				 => $email,
			'bookingId'			 => $bookingId,
			'emailLayout'		 => $emailLayout,
			'emailReplyTo'		 => $emailReplyTo,
			'emailReplyName'	 => $emailReplyName,
			'emailType'			 => $emailType,
			'emailUserType'		 => $emailUserType,
			'emailRefType'		 => $emailRefType,
			'emailRefId'		 => $emailRefId,
			'emailLogInstance'	 => $emailLogInstance,
			'emailDelayTime'	 => $delayTime
		);
	}

	public function sendingMarketingReport($totalCount, $totalSend, $totalWhatsappSend, $totalEmailSend, $sendingStartTime, $sendingEndTime)
	{
		$email = Config::get('leadershipMail');
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
			$mail->setView('marketingreport');
			$mail->setData(array('totalCount' => $totalCount, 'totalSend' => $totalSend, 'totalWhatsappSend' => $totalWhatsappSend, 'totalEmailSend' => $totalEmailSend, 'sendingStartTime' => $sendingStartTime, 'sendingEndTime' => $sendingEndTime));
			$mail->setLayout('mail');
			$mail->setTo($email);
			$date					 = DBUtil::getCurrentTime();
			$subject				 = "B2C marketing activity emial report on $date ";
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{

				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body = $mail->Body;
			emailWrapper::createLog($email, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
		}
	}

	public function userReferredBy($email, $name, $referalName, $qrCode)
	{
		if ($email != '')
		{
			$this->email_receipient	 = $email;
			$mail					 = EIMailer::getInstance(EmailLog::SEND_CUSTOM_EMAIL);
			$mail->setView('userrefferedby');
			$mail->setData(array('name' => $name, 'referalName' => $referalName, 'qrCode' => $qrCode));
			$mail->setLayout('mail');
			$mail->setTo($email);
			$subject				 = "$referalName has sent you discounted travel on Gozo Cabs";
			$mail->setSubject($subject);
			if ($mail->sendMail(0))
			{

				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body = $mail->Body;
			emailWrapper::createLog($email, $subject, $body, "", EmailLog::EMAIL_CUSTOM, $delivered);
		}
	}

}
