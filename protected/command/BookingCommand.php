<?php

class BookingCommand extends BaseCommand
{

	private $email_receipient;

	public function actionLinkuser()
	{
//$bookModel = Booking::model()->resetScope();
		/* var model Booking */
		$book	 = new Booking();
		$models	 = $book->getNonUserBookings();
		foreach ($models as $model)
		{
			/* @var $model Booking */
//	$userModel = Users::model()->linkUserByEmail($model->bkg_user_email, $model->bkg_contact_no, $model->bkg_user_name, $model->bkg_user_lname, $model->bkg_country_code, $model->bkg_id, Booking::Platform_Admin, false);
			$criteria1		 = new CDbCriteria;
			$email			 = $model->bkg_user_email;
			$fname			 = $model->bkg_user_name;
			$lname			 = $model->bkg_user_lname;
			$country_code	 = $model->bkg_country_code;
			$phone			 = $model->bkg_contact_no;
			$bkg_id			 = $model->bkg_id;

			if ($email != '')
			{
				$criteria1->compare('usr_email', $email);
				$usrModel = Users::model()->find($criteria1);
				if ($usrModel)
				{
					$usrModel->isNew		 = false;
					echo $model->bkg_user_id		 = $usrModel->user_id;
					$model->bkg_user_email	 = trim($model->bkg_user_email);
					echo "\t'" . $model->bkg_user_email . "'\t" . $model->bkg_contact_no;
					$model->update();
					continue;
				}
			}
			$usrModel					 = new Users();
			$usrModel->isNew			 = true;
			$usrModel->scenario			 = 'insertonbooking';
			$usrModel->usr_name			 = $fname;
			$usrModel->usr_lname		 = $lname;
			$usrModel->usr_ip			 = Filter::getUserIP();
			$usrModel->usr_device		 = UserLog::model()->getDevice();
			$usrModel->usr_country_code	 = $country_code;
			$usrModel->usr_password		 = 'welcomeToGozo';
			if ($email != '' && $email != 'NULL' && $email != 'null')
			{
				$usrModel->usr_email	 = $email;
				$usrModel->usr_acct_type = '1';
			}
			else
			{
//				$usrModel->usr_email	 = $usrModel->generateEmailByPhone($country_code, $phone, $bkg_id);
//				$usrModel->usr_acct_type = '2';
				throw new Exception("Please enter valid email id");
			}
			if ($phone != '' && $phone != 'NULL')
			{
				$usrModel->usr_mobile = $phone;
			}
			$usrModel->usr_active			 = '1';
			$usrModel->usr_create_platform	 = $platform;
			$usrModel->save();
			$userModel						 = $usrModel;
			if ($userModel->hasErrors())
			{
				echo "-----";
				continue;
			}
			echo $model->bkg_user_id		 = $userModel->user_id;
			$model->bkg_user_email	 = trim($model->bkg_user_email);
			echo "\t'" . $model->bkg_user_email . "'\t" . $model->bkg_contact_no;
			$model->update();
			print_r($model->getErrors());
			if (!$userModel->isNew)
			{
				continue;
			}
//			$email = "ak@epitech.in";
			$bookingId	 = $model->bkg_booking_id;
			$message	 = "Thanks for your journey with aaocab in the past. For your convenience, an account has been created. "
					. "Please tap the URL below to set your password:\n";
			$hash		 = Yii::app()->shortHash->hash($model->bkg_user_id);
			$url		 = Yii::app()->createUrl('users/confirmsignup', ['id' => $model->bkg_user_id, 'hash' => $hash]);

			$url		 = substr($url, 1);
			$smsUrl		 = "aaocab.com" . $url;
			$emailUrl	 = "http://www." . $smsUrl . "";
			if ($email != '')
			{
				$emailMessage = $message . "<br>
					<br>" . $emailUrl . "
					<br>
					<br>-Team Gozo";

				$mail					 = new EIMailer();
				$userName				 = $model->bkg_user_name;
				$mail->clearView();
				$mail->clearLayout();
				$content				 = "<div style='text-align:left; margin:5px'><span style='text-align:left;'>Dear $userName</span>,
					<br>
					<br>$emailMessage</div>";
				$this->email_receipient	 = $email;
				ob_start();
				include Yii::getPathOfAlias("application.views.layouts.mail1") . ".php";
				$body					 = ob_get_contents();
				ob_get_clean();
				$mail->setBody($body);
				$mail->setTo($email, $userName);
				$subject				 = 'Welcome to aaocab.';
				$mail->setSubject($subject);
				if ($mail->sendAccountsEmail())
				{
					$delivered = "Email sent successfully";
				}
				else
				{
					$delivered = "Email not sent";
				}

				echo $usertype = EmailLog::Consumers;
				emailWrapper::createLog($email, $subject, $body, $bookingId, $usertype, $delivered);
			}
			if ($model != null && $userModel->usr_acct_type != 0)
			{
				/* @var $bkgModel Booking */
				$bkgModel		 = $model;
				$country_code	 = $bkgModel->bkg_country_code;
				$phone			 = $bkgModel->bkg_contact_no;
//	$phone = "9051799911";
//	$country_code = "91";
				$bookingId		 = $bkgModel->bkg_booking_id;
				if ($phone != '')
				{
					$msgCom	 = new smsWrapper();
					$link	 = 'aaocab.com' . Yii::app()->createUrl('users/confirmsignup', ['id' => $userModel->user_id, 'hash' => Yii::app()->shortHash->hash($userModel->user_id)]);
					$msgCom->confirmOldUserAccounts($country_code, $phone, $message . $smsUrl);
				}
			}

			echo $userid;
			echo "\t {$model->bkg_user_email}";
			echo "\t bkg_id--- {$model->bkg_id}  \n";
		}
	}

	public function actionNotifyBVR()
	{
//		$check = Filter::checkProcess("notifyBVR");
//		if (!$check)
//		{
//			return;
//		}
//		$vendorList = Vendors::model()->getPendingAppNotification();
//		if (count($vendorList) > 0)
//		{
//			foreach ($vendorList as $vendorRow)
//			{
//				$requestList = BookingVendorRequest::model()->getRequestedListNew1($vendorRow['vnd_id'], '', 0, 0, '', 1);
//				if(count($requestList) > 0)
//				{
//					$success = AppTokens::model()->notifyVendorBookingRequestOnce($vendorRow['vnd_id']);
//					if ($success)
//					{
//						BookingVendorRequest::model()->updateVendorLastReminder($vendorRow['vnd_id'], 1, 0);
//						foreach($requestList as $list)
//						{
//							$updateNotified = BookingTrail::model()->updateBookingByVendorNotified($list['bkgIds']);
//						}
//					}
//				}
//			}
//		}
		$check = Filter::checkProcess("notifyBVR");
		if (!$check)
		{
			return;
		}
		$bookingZones = Booking::model()->findBookingZoneForNotification();
		if ($bookingZones['zone'] != null || $bookingZones['city'] != null)
		{
			$bookingZonesArr = explode(',', $bookingZones['zone']);
			$vendorList		 = Vendors::model()->findVendorListForNotification();
			if (COUNT($vendorList) > 0)
			{
				foreach ($vendorList as $key => $val)
				{
					echo $val['vnd_id'] . "<br>";
					$vndNotify		 = 0;
					$vndAcceptedZone = explode(',', $val['vnp_accepted_zone']);
					$resultAcc		 = array_intersect($bookingZonesArr, $vndAcceptedZone);
					if (COUNT($resultAcc) > 0)
					{
						if ($val['vnp_excluded_cities'] != '')
						{
							$bookingCitiesArr	 = explode(',', $bookingZones['city']);
							$vndExcludedZone	 = explode(',', $val['vnp_excluded_cities']);
							$resultEx			 = array_intersect($bookingCitiesArr, $vndExcludedZone);
							if (COUNT($resultEx) == 0)
							{
								$vndNotify = 1;
							}
						}
						else
						{
							$vndNotify = 1;
						}
					}
					else
					{
						if ($val['vnd_home_zone'] != '')
						{
							$vndHomeZone = explode(',', $val['vnd_home_zone']);
							$resultHm	 = array_intersect($bookingZonesArr, $vndHomeZone);
							if (COUNT($resultHm) > 0)
							{
								$vndNotify = 1;
							}
						}
					}

					if ($vndNotify == 1)
					{
						$success = AppTokens::model()->notifyVendorBookingRequestOnce($val['vnd_id']);
						if ($success)
						{
							Vendors::model()->updateVendorAfterSendNotification($val['vnd_id']);
						}
					}
					else
					{
						echo "Notification Not Send to this (" . $val['vnd_id'] . ") vendor";
					}
				}
			}
		}
	}

	public function actionSendvendornextreq()
	{
		/* @var $model BookingSub */
		$model	 = new BookingSub();
//$rows	 = $model->getSmsNotification();
		$rows	 = $model->getSmsNotificationData();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$date	 = date('d/m/Y h:i A', strtotime($row['bkg_pickup_date']));
				$ext	 = ($row['vnd_phone_country_code'] != '') ? $row['vnd_phone_country_code'] : 91;
				$changes = 'Booking available ' . $row['from_city'] . ' to ' . $row['to_city'] . ' on  ' . $date . '. Login to app to accept booking';
				/* @var $model smsWrapper */
				$smsMsg	 = new smsWrapper();
				if ($smsMsg->sendIncomingBookingsForNotLoggedIn($ext, $row['vnd_phone'], $changes, $row['bvr_booking_id'], $row['bvr_vendor_id']))
				{
					$btrModel					 = BookingTrail::model()->findByPk($row['btr_id']);
					$btrModel->btr_vendor_sms	 = 1;
					$btrModel->save();
					echo "###" . $row['bvr_id'] . "### Req sent to vendor -> " . $row['bvr_vendor_id'] . " for Booking ID : " . $row['bvr_booking_id'] . "\n";
				}
			}
		}
	}

	public function actionGetPickupDetails()
	{
		$emailCom	 = new emailWrapper();
		$hours		 = 96;
		$emailCom->nextScheduledPickupReport($hours);
	}

	public function actionUpdateRelated()
	{
		$check = Filter::checkProcess("booking updateRelated");
		if (!$check)
		{
			return;
		}

		$now		 = DBUtil::queryScalar("SELECT NOW()");
		$sql		 = "UPDATE booking_trail,(SELECT t.bkg_id,
							(SELECT COUNT(*)
							 FROM booking bkg INNER JOIN booking_user bui ON bkg.bkg_id = bui.bui_bkg_id
							 WHERE     bkg.bkg_status IN (2,
														  3,
														  4,
														  5,
														  9,
														  13)
								   AND bkg.bkg_create_date > DATE_SUB(NOW(), INTERVAL 96 HOUR)
								   AND bkg.bkg_id <> t.bkg_id
								   AND bkg.bkg_from_city_id = t.bkg_from_city_id
								   AND bkg.bkg_to_city_id = t.bkg_to_city_id
								   AND (DATE(bkg.bkg_pickup_date) = DATE(t.bkg_pickup_date)
										OR DATE(bkg.bkg_create_date) = DATE(t.bkg_create_date))

								   AND ((bui.bkg_user_email <> '' AND bui.bkg_user_email = bui1.bkg_user_email) OR (bui.bkg_contact_no <> '' AND bui.bkg_contact_no = bui1.bkg_contact_no))
								   )
							   AS countRelated
					 FROM booking t INNER JOIN booking_user bui1 ON bui1.bui_bkg_id = t.bkg_id INNER JOIN booking_trail btr ON btr.btr_bkg_id = t.bkg_id
					 WHERE     btr.bkg_is_related_booking = 0
						   AND t.bkg_create_date > DATE_SUB(NOW(), INTERVAL 1 HOUR)
						   AND bkg_pickup_date > NOW()
						   AND bkg_status IN (2,
											  3,
											  4,
											  5)
					 HAVING countRelated > 0) bk1 SET booking_trail.bkg_is_related_booking=bk1.countRelated WHERE booking_trail.btr_bkg_id=bk1.bkg_id";
		$rowsUpdated = DBUtil::execute($sql);
	}

	public function actionRelateDuplicateLead()
	{
		$check = Filter::checkProcess("booking relateDuplicateLead");
		if (!$check)
		{
			return;
		}
		echo ":: Booking-relateDuplicateLead Started";
		Logger::create("command.booking.RelateDuplicateLeads start", CLogger::LEVEL_PROFILE);
		$data = BookingTemp::model()->updateRelated();
		Logger::create("command.booking.RelateDuplicateLeads end", CLogger::LEVEL_PROFILE);
		echo ":: Booking-relateDuplicateLead End";
	}

	public function actionActiveVendorNotification()
	{
		$venActive = 1;
		Vendors::model()->missingDriverCarNotification($venActive);
	}

	public function actionInactiveVendorNotification()
	{
		$venActive = 2;
		Vendors::model()->missingDriverCarNotification($venActive);
	}

	public function actionVendorFreezeOnCreditLimit()
	{
//obsolete
//	$sql		 = "UPDATE vendors ,
//            (SELECT b.vnd_id,b.vnd_name AS vendor_name,SUM(a.ven_trans_amount) AS vendorAmount,IF(b.vnd_credit_limit IS NULL,'50000',b.vnd_credit_limit) as creditLimit FROM `vendor_transactions` a LEFT JOIN `vendors` b ON a.trans_vendor_id=b.vnd_id WHERE b.vnd_active=1 AND b.vnd_id IS NOT NULL GROUP BY a.trans_vendor_id having vendorAmount > creditLimit) a
//            SET vnd_is_freeze=1 WHERE vendors.vnd_id=a.vnd_id";
//	$cdb		 = Yii::app()->db->createCommand($sql);
//	$rowsUpdated	 = $cdb->execute();
//	echo "vendorFreeze-->" . ($rowsUpdated);
//	echo "\n";
	}

	public function actionSendCustomerReturnTrip()
	{
		$bkgId		 = '';
		$add_days	 = 14;
		$fromDate	 = (date('Y-m-d', strtotime('Today') - (24 * 3600 * $add_days)));
		$toDate		 = date('Y-m-d', strtotime('Today'));
		Booking::model()->returnTripEmail($bkgId, $fromDate, $toDate);
	}

	public function actionCustomerReviewMail()
	{
		Logger::create("command.booking.customerReviewMail start", CLogger::LEVEL_PROFILE);
		$rows = Booking::model()->reminderOnCustomerReview();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ($row['bkg_user_email'] <> '')
				{
					$bkgId = $row['bkg_id'];
					notificationWrapper::customerReview($bkgId);
				}
			}
		}
		Logger::create("command.booking.customerReviewMail end", CLogger::LEVEL_PROFILE);
	}

	public function actionFinalAutomatedFollowup()
	{
		Logger::create("command.booking.finalAutomatedFollowup start", CLogger::LEVEL_TRACE);
		$rows = BookingSub::getFinalFollowup();
		foreach ($rows as $row)
		{
			$sql = "SELECT `CS`('" . $row['bkg_create_date'] . "', '" . $row['bkg_pickup_date'] . "') AS `CS`;";
			$cs	 = DButil::queryScalar($sql);
			if ($cs > 0.5 && $cs < 0.75 && $row['age'] > 8)
			{
				$var = "Bkg == > " . $row['bkg_id'] . " - CreateDate ==> " . $row['bkg_create_date'] . " - PickupDate ==> " . $row['bkg_pickup_date'] . " - age :" . $row['age'];
				Logger::create($var, CLogger::LEVEL_INFO);
				BookingTrail::finalFollowup($row['bkg_id'], true, true);
			}
		}
		Logger::create("command.booking.finalAutomatedFollowup end", CLogger::LEVEL_TRACE);
	}

	public function actionUnverifiedFollowup()
	{
		$check = Filter::checkProcess("booking unverifiedFollowup");
		if (!$check)
		{
			return;
		}

		Logger::create("command.booking.unverifiedFollowup start", CLogger::LEVEL_PROFILE);
		$rows = Booking::getUnverifiedFollowup();
		foreach ($rows as $row)
		{
			$bkgId = $row['bkg_id'];
			Logger::writeToConsole("BkgId: " . $bkgId);
			BookingTrail::unverifiedFollowup($bkgId, true, true);
		}
		Logger::create("command.booking.unverifiedFollowup end", CLogger::LEVEL_PROFILE);
	}

	public function actionLeadFollowup()
	{
		$check = Filter::checkProcess("booking leadFollowup");
		if (!$check)
		{
			return;
		}

		Logger::create("command.booking.leadFollowup start", CLogger::LEVEL_PROFILE);
		$rows = BookingTemp::getActiveLeads();
		foreach ($rows as $row)
		{
			$bkgId = $row['bkg_id'];
			BookingTemp::model()->leadFollowup($bkgId, true, false);
		}
		Logger::create("command.booking.leadFollowup end", CLogger::LEVEL_PROFILE);
	}

	public function actionMmtUnverified()
	{
		Logger::create("command.booking.mmtUnverified start", CLogger::LEVEL_PROFILE);
		$rows = BookingSub::model()->getMmtUnverified();

		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				/* @var $modelsub BookingSub */
				$modelsub = new BookingSub();
				$modelsub->unverifiedAutoCanel($row['bkg_id']);
				echo $row['bkg_id'] . "##### MMT Unverified Auto Cancel\n";
			}
		}

		$rows = BookingSub::model()->getTrainmanUnverified();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				/* @var $modelsub BookingSub */
				$modelsub = new BookingSub();
				$modelsub->unverifiedAutoCanel($row['bkg_id']);
				echo $row['bkg_id'] . "##### Trainman Unverified Auto Cancel\n";
			}
		}
		Logger::create("command.booking.mmtUnverified end", CLogger::LEVEL_PROFILE);
	}

	public function actionUnverifiedAutoCancel()
	{
		$check = Filter::checkProcess("booking unverifiedAutoCancel");
		if (!$check)
		{
			return;
		}

		$result = BookingSub::getUnverifiedForAutoCancel();

		foreach ($result as $row)
		{
			$bkgId = $row['bkg_id'];

			BookingSub::model()->unverifiedAutoCanel($bkgId);

			Logger::writeToConsole("BkgID: {$bkgId}");
		}
	}

	public function actionUnverifiedFollowupFeedback()
	{
		Logger::create("command.booking.unverifiedFollowupFeedback start", CLogger::LEVEL_PROFILE);
		/* var emailWrapper */
		$emailCom = new emailWrapper();
		$emailCom->unverifiedFeedbackMail();
		Logger::create("command.booking.unverifiedFollowupFeedback end", CLogger::LEVEL_PROFILE);
	}

	public function actionTentativeBooking()
	{
		Logger::create("command.booking.tentative start", CLogger::LEVEL_PROFILE);
		/* var emailWrapper */
		$emailCom = new emailWrapper();
		$emailCom->tentativeBookingMail();
		Logger::create("command.booking.tentative end", CLogger::LEVEL_PROFILE);
	}

	public function actionNonCommercialPickup()
	{
		Logger::create("command.booking.nonCommercialPickup start", CLogger::LEVEL_PROFILE);
		$emailCom	 = new emailWrapper();
		$hours		 = 96;
		$success	 = $emailCom->nextVehicleReport($hours);
		Logger::create("command.booking.nonCommercialPickup end", CLogger::LEVEL_PROFILE);
	}

	public function actionInactiveMails()
	{
		$emailLog = new EmailLog();
		$emailLog->sentInactiveMails();
	}

	public function actionInactiveSms()
	{
		$smsLog = new SmsLog();
		$smsLog->sentInactiveSms();
	}

	public function actionSendReviewMailOnCompleted()
	{
		$check = Filter::checkProcess("booking sendReviewMailOnCompleted");
		if (!$check)
		{
			return;
		}
		$bookModel	 = new Booking();
		$rows		 = $bookModel->getBookingOnMarkCompleted();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$emailCom = new emailWrapper();
				$emailCom->markComplete($row['bkg_id'], BookingLog::System);
				echo $row['bkg_id'] . " = PickUp Date and Duration =" . $row['pickup_date_duration'] . " => " . $row['today'] . "\n";
			}
		}
	}

	public function actionFixBookingTrip()
	{
		$sql	 = "SELECT bkg_id FROM `booking` WHERE bkg_status IN (2) AND bkg_bcb_id IS NULL";
		$rows	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $row)
		{
			try
			{
				$model							 = Booking::model()->findByPk($row['bkg_id']);
				$transaction					 = DBUtil::beginTransaction();
				$newCabModel					 = new BookingCab('matchtrip');
				$newCabModel->bcb_vendor_amount	 = $model->bkg_vendor_amount;
				$newCabModel->bcb_bkg_id1		 = $model->bkg_id;
				$newCabModel->bcb_trip_status	 = BookingCab::STATUS_VENDOR_UNASSIGNED;
				$newCabModel->save();
				BookingRoute::model()->setBookingCabStartEndTime($newCabModel->bcb_id, $newCabModel->bcb_bkg_id1);
				$model->bkg_bcb_id				 = $newCabModel->bcb_id;
				$model->bkg_status				 = Booking::STATUS_VERIFY;
				$model->bkg_vendor_request_cnt	 = 0;
				$model->scenario				 = 'cancel_delete';
				if ($model->validate())
				{
					$model->bkg_return_id	 = 0;
					$routeModels			 = $model->bookingRoutes;
					foreach ($routeModels as $routeModel)
					{
						$routeModel->scenario	 = 'unassignvendor';
						$routeModel->brt_bcb_id	 = $newCabModel->bcb_id;
						$routeModel->save();
					}
					$succResult["success"] = $model->save();
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					DBUtil::rollbackTransaction($transaction);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateProfit()
	{
		/* @var $modelsub BookingSub */
		/*
		  $modelsub = new BookingSub();
		  $data = $modelsub->fetchBkgIDByProfitability();
		  if (count($data) > 0) {
		  foreach ($data as $d) {
		  $return = $modelsub->updateProfitFlag($d['bkg_id']);
		  $isProfit = ($return > 0) ? "Non PROFIT" : "PROFIT";
		  echo $isProfit . "- ### -" . $d['bkg_id'];
		  echo "\n";
		  }
		  }
		 */
	}

	public function actionSmartAutoMatch()
	{
		
	}

	public function actionAllRouteUrls()
	{
		Route::model()->populateRoutes();
	}

	public function actionAllUrls()
	{
		Route::model()->populateExisting();
	}

	public function actionUpdateManualAssignmentOLD()
	{
		echo ":: Booking-UpdateManualAssignment Started";
		Logger::create("command.booking.manualAssignment start", CLogger::LEVEL_PROFILE);
		/* @var $booksub BookingSub */
		$booksub = new BookingSub();
		$rows	 = $booksub->getManualAssignmentBkg();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				/* @var $model Booking */
				$model		 = Booking::model()->findByPk($row['bkg_id']);
				$oldModel	 = clone $model;
				if ($model->bkg_status == 2)
				{
					$model->bkgPref->bkg_manual_assignment = 1;
					if ($model->bkgPref->save())
					{
						BookingLog::model()->createLog($model->bkg_id, 'Marked for manual assignment', UserInfo::getInstance(), BookingLog::BOOKING_MANUAL_ASSIGNMENT, $oldModel, false);
						echo $model->bkg_id . " - " . $model->bkg_booking_id . " - Manual Assignment\n";
					}
				}
			}
		}
		Logger::create("command.booking.manualAssignment end", CLogger::LEVEL_PROFILE);
		echo ":: Booking-UpdateManualAssignment End";
	}

	public function actionUpdateAgentRefCode()
	{
		$sql		 = "SELECT bkg_id, bkg_pickup_date FROM booking WHERE bkg_agent_id = 450 and bkg_status IN (2,3,5,6,7,9) and bkg_agent_ref_code IS NULL";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$query	 = "SELECT aat_response FROM agent_api_tracking WHERE aat_type = 8 and aat_response LIKE " . "'" . '%"hold_key":"' . $result['bkg_id'] . '"%' . "'" . " and aat_created_at < " . "'" . $result['bkg_pickup_date'] . "' LIMIT 0,1";
			$data	 = Yii::app()->db->createCommand($query)->queryRow();
			if ($data)
			{
				$request = CJSON::decode($data['aat_response'], true);
				$qry	 = "UPDATE booking SET bkg_agent_ref_code = '" . $request['booking_id'] . "' where bkg_id = " . $result['bkg_id'];
				Yii::app()->db->createCommand($qry)->execute();
			}
		}
	}

//Non Corporate Entry (Transaction Table)
	public function actionAccountingB2C()
	{
		$sql		 = "SELECT * FROM transactions WHERE trans_ptp_id NOT IN(1,5,7,8,13) AND trans_status = 1 AND trans_active=1 ";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['trans_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['trans_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['trans_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_BOOKING;
			$paymentGateway->apg_trans_ref_id		 = $result['trans_booking_id'];
			$paymentGateway->apg_code				 = $result['trans_code'];
			$paymentGateway->apg_mode				 = $result['trans_mode'];
			$paymentGateway->apg_remarks			 = $result['trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['trans_ipaddress'];
			$paymentGateway->apg_device_detail		 = $result['trans_device_detail'];
			$paymentGateway->apg_user_type			 = $result['trans_user_type'];
			$paymentGateway->apg_user_id			 = $result['trans_user_id'];
			$paymentGateway->apg_amount				 = $result['trans_amount'];
			$paymentGateway->apg_active				 = $result['trans_active'];
			$paymentGateway->apg_status				 = $result['trans_status'];
			$paymentGateway->apg_date				 = $result['trans_start_datetime'];
			$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
			$paymentGateway->apg_response_details	 = $result['trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['trans_txn'];
			$paymentGateway->apg_merchant_ref_id	 = $result['trans_merchant_ref_id'];
			$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
			$paymentGateway->apg_complete_datetime	 = $result['trans_complete_datetime'];

			if ($paymentGateway->save())
			{
				$accTransModel				 = new AccountTransactions();
				$accTransModel->act_amount	 = $paymentGateway->apg_amount;
				$accTransModel->act_date	 = $paymentGateway->apg_date;
				$accTransModel->act_type	 = Accounting::AT_BOOKING;
				$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
				$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
				$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_BOOKING, $paymentGateway->apg_id, $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_ONLINEPAYMENT, $paymentGateway->apg_user_type, $paymentGateway->apg_user_id, $result['trans_id']);
			}
		}
	}

//Non Corporate entry (Transaction table)
	public function actionAccounting_GozoCoin_Journal()
	{
		$sql		 = "SELECT * FROM transactions WHERE trans_ptp_id IN(5,7,8) AND trans_status = 1 AND trans_active=1 ";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['trans_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['trans_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['trans_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_BOOKING;
			$paymentGateway->apg_trans_ref_id		 = $result['trans_booking_id'];
			$paymentGateway->apg_code				 = $result['trans_code'];
			$paymentGateway->apg_mode				 = $result['trans_mode'];
			$paymentGateway->apg_remarks			 = $result['trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['trans_ipaddress'];
			$paymentGateway->apg_device_detail		 = $result['trans_device_detail'];
			$paymentGateway->apg_user_type			 = $result['trans_user_type'];
			$paymentGateway->apg_user_id			 = $result['trans_user_id'];
			$paymentGateway->apg_amount				 = $result['trans_amount'];
			$paymentGateway->apg_active				 = $result['trans_active'];
			$paymentGateway->apg_status				 = $result['trans_status'];
			$paymentGateway->apg_date				 = $result['trans_start_datetime'];
			$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
			$paymentGateway->apg_response_details	 = $result['trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['trans_txn'];
			$paymentGateway->apg_merchant_ref_id	 = $result['trans_merchant_ref_id'];
			$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
			$paymentGateway->apg_complete_datetime	 = $result['trans_complete_datetime'];

			$agentIds = 1249;

			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $paymentGateway->apg_amount;
			$accTransModel->act_date	 = $paymentGateway->apg_date;
			$accTransModel->act_type	 = Accounting::AT_BOOKING;
			$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
			$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_BOOKING, $agentIds, $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_PARTNER, $paymentGateway->apg_user_type, $paymentGateway->apg_user_id, $result['trans_id']);
		}
	}

//Non Corporate entry (Transaction table)
	public function actionAccounting_cash()
	{
		$sql		 = "SELECT * FROM transactions WHERE trans_ptp_id IN(1) AND trans_status = 1 AND trans_active=1";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['trans_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['trans_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['trans_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_BOOKING;
			$paymentGateway->apg_trans_ref_id		 = $result['trans_booking_id'];
			$paymentGateway->apg_code				 = $result['trans_code'];
			$paymentGateway->apg_mode				 = $result['trans_mode'];
			$paymentGateway->apg_remarks			 = $result['trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['trans_ipaddress'];
			$paymentGateway->apg_device_detail		 = $result['trans_device_detail'];
			$paymentGateway->apg_user_type			 = $result['trans_user_type'];
			$paymentGateway->apg_user_id			 = $result['trans_user_id'];
			$paymentGateway->apg_amount				 = $result['trans_amount'];
			$paymentGateway->apg_active				 = $result['trans_active'];
			$paymentGateway->apg_status				 = $result['trans_status'];
			$paymentGateway->apg_date				 = $result['trans_start_datetime'];
			$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
			$paymentGateway->apg_response_details	 = $result['trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['trans_txn'];
			$paymentGateway->apg_merchant_ref_id	 = $result['trans_merchant_ref_id'];
			$paymentGateway->apg_ref_id				 = $result['trans_ref_id'];
			$paymentGateway->apg_complete_datetime	 = $result['trans_complete_datetime'];

			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $paymentGateway->apg_amount;
			$accTransModel->act_date	 = $paymentGateway->apg_date;
			$accTransModel->act_type	 = Accounting::AT_BOOKING;
			$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
			$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_BOOKING, '', $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_BOOKING, $paymentGateway->apg_user_type, $paymentGateway->apg_user_id, $result['trans_id']);
		}
	}

//Non Corporate entry (Agent Transaction table)
	public function actionAccountingAgent_bank_cash_offline()
	{
		$sql		 = "SELECT * FROM agent_transactions WHERE agt_ptp_id IN (1,2) AND agt_trans_active = 1 AND agt_trans_status = 1 ";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['agt_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['agt_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['agt_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_PARTNER;
			$paymentGateway->apg_trans_ref_id		 = $result['agt_agent_id'];
			$paymentGateway->apg_code				 = $result['agt_trans_code'];
			$paymentGateway->apg_mode				 = $result['agt_trans_mode'];
			$paymentGateway->apg_remarks			 = $result['agt_trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['agt_trans_ipaddress'];
			$paymentGateway->apg_device_detail		 = $result['agt_trans_device_detail'];
			$paymentGateway->apg_user_id			 = $result['agt_trans_user_id'];
			$paymentGateway->apg_amount				 = $result['agt_trans_amount'];
			$paymentGateway->apg_active				 = $result['agt_trans_active'];
			$paymentGateway->apg_status				 = $result['agt_trans_status'];
			$paymentGateway->apg_date				 = $result['agt_trans_start_datetime'];
			$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
			$paymentGateway->apg_response_details	 = $result['agt_trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['agt_trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['agt_trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['agt_trans_txn_id'];
			$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
			$paymentGateway->apg_complete_datetime	 = $result['agt_trans_complete_datetime'];
			$paymentGateway->apg_type				 = $result['agt_trans_type'];

			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $paymentGateway->apg_amount;
			$accTransModel->act_date	 = $paymentGateway->apg_date;
			$accTransModel->act_type	 = Accounting::AT_PARTNER;
			$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
			$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_PARTNER, '', $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_PARTNER, $paymentGateway->apg_type, $paymentGateway->apg_user_id, $result['agt_trans_id']);
		}
	}

//Non Corporate entry (Agent Transaction table)
	public function actionAccountingAgent_ByBank_online()
	{
		$sql		 = "SELECT * FROM agent_transactions WHERE agt_ptp_id IN (3,6) AND agt_trans_active = 1 AND agt_trans_status = 1 ";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['agt_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['agt_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['agt_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_PARTNER;
			$paymentGateway->apg_trans_ref_id		 = $result['agt_agent_id'];
			$paymentGateway->apg_code				 = $result['agt_trans_code'];
			$paymentGateway->apg_mode				 = $result['agt_trans_mode'];
			$paymentGateway->apg_remarks			 = $result['agt_trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['agt_trans_ipaddress'];
			$paymentGateway->apg_device_detail		 = $result['agt_trans_device_detail'];
			$paymentGateway->apg_user_id			 = $result['agt_trans_user_id'];
			$paymentGateway->apg_amount				 = $result['agt_trans_amount'];
			$paymentGateway->apg_active				 = $result['agt_trans_active'];
			$paymentGateway->apg_status				 = $result['agt_trans_status'];
			$paymentGateway->apg_date				 = $result['agt_trans_start_datetime'];
			$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
			$paymentGateway->apg_response_details	 = $result['agt_trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['agt_trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['agt_trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['agt_trans_txn_id'];
			$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
			$paymentGateway->apg_complete_datetime	 = $result['agt_trans_complete_datetime'];
			$paymentGateway->apg_type				 = $result['agt_trans_type'];
			if ($paymentGateway->save())
			{
				$accTransModel				 = new AccountTransactions();
				$accTransModel->act_amount	 = $paymentGateway->apg_amount;
				$accTransModel->act_date	 = $paymentGateway->apg_date;
				$accTransModel->act_type	 = Accounting::AT_PARTNER;
				$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
				$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
				$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_PARTNER, $paymentGateway->apg_id, $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_ONLINEPAYMENT, $paymentGateway->apg_type, $paymentGateway->apg_user_id, $result['agt_trans_id']);
			}
		}
	}

//Corporate entry (Agent table)
	public function actionAccountingB2B()
	{
		$sql		 = "SELECT * FROM agent_transactions WHERE agt_ptp_id IN (13) AND agt_trans_active = 1 AND agt_trans_status = 1 AND agt_agent_id IS NOT NULL AND agt_trans_id BETWEEN 29829 AND 68704";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['agt_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['agt_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['agt_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_BOOKING;
			$paymentGateway->apg_trans_ref_id		 = $result['agt_booking_id'];
			$paymentGateway->apg_code				 = $result['agt_trans_code'];
			$paymentGateway->apg_mode				 = $result['agt_trans_mode'];
			$paymentGateway->apg_remarks			 = $result['agt_trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['agt_trans_ipaddress'];
			$paymentGateway->apg_device_detail		 = $result['agt_trans_device_detail'];
			$paymentGateway->apg_user_id			 = $result['agt_trans_user_id'];
			$paymentGateway->apg_amount				 = $result['agt_trans_amount'];
			$paymentGateway->apg_active				 = $result['agt_trans_active'];
			$paymentGateway->apg_status				 = $result['agt_trans_status'];
			$paymentGateway->apg_date				 = $result['agt_trans_start_datetime'];
			$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
			$paymentGateway->apg_response_details	 = $result['agt_trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['agt_trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['agt_trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['agt_trans_txn_id'];
			$paymentGateway->apg_ref_id				 = $result['agt_trans_ref_id'];
			$paymentGateway->apg_complete_datetime	 = $result['agt_trans_complete_datetime'];
			$paymentGateway->apg_type				 = $result['agt_trans_type'];

			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $paymentGateway->apg_amount;
			$accTransModel->act_date	 = $paymentGateway->apg_date;
			$accTransModel->act_type	 = Accounting::AT_BOOKING;
			$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
			$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_BOOKING, $result['agt_agent_id'], $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_PARTNER, $paymentGateway->apg_type, $paymentGateway->apg_user_id, $result['agt_trans_id']);
		}
	}

	public function actionSendAdvanceReminderSMS()
	{
		Logger::create("command.booking.sendAdvanceReminderSMS start", CLogger::LEVEL_PROFILE);
		$agentId	 = Yii::app()->params['gozoChannelPartnerId'];
		$sql		 = "SELECT bkg_id,bkg_booking_id,bkg_country_code,bkg_contact_no, bkg_create_date,
            bkg_pickup_date, bkg_advance_amount,
            bkg_from_city_id,bkg_to_city_id,
            fcty.cty_name fromCity,tcty.cty_name toCity,
            bkg_adv_reminder_sms_datetime, bkg_adv_reminder_sms_count,
                TIMESTAMPDIFF(HOUR, bkg_create_date, NOW()) creatediff,
                TIMESTAMPDIFF(HOUR, NOW(), bkg_pickup_date) pickupdiff
            FROM   booking bkg
				JOIN booking_user ON bkg.bkg_id = booking_user.bui_bkg_id
				JOIN booking_invoice ON bkg.bkg_id = booking_invoice.biv_bkg_id
                JOIN booking_pref bpr
                ON bkg.bkg_id = bpr.bpr_bkg_id
             JOIN `cities` fcty ON fcty.cty_id=bkg.bkg_from_city_id
             JOIN `cities` tcty ON tcty.cty_id=bkg.bkg_to_city_id
            WHERE  ((bkg_adv_reminder_sms_count = 0
                        AND TIMESTAMPDIFF(HOUR, bkg_create_date, NOW()) >= 1
                        AND TIMESTAMPDIFF(HOUR, NOW(), bkg_pickup_date) > 8)
                    OR (bkg_adv_reminder_sms_datetime IS NOT NULL
                        AND TIMESTAMPDIFF(HOUR, bkg_adv_reminder_sms_datetime, NOW()) >= 10
                        AND  bkg_adv_reminder_sms_count = 1))
                    AND bkg.bkg_status IN (2,3,5)
                    AND (bkg.bkg_agent_id = '' OR bkg.bkg_agent_id = 0 OR bkg.bkg_agent_id IS NULL OR  bkg.bkg_agent_id = $agentId )
                    AND bkg_advance_amount = 0
                    AND bkg_contact_no IS NOT NULL AND length(bkg_contact_no)  = 10
                    AND bkg_country_code = 91
                ";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($resultset) > 0)
		{
			foreach ($resultset as $row)
			{
				$bkgId		 = $row['bkg_id'];
				$bookingId	 = $row['bkg_booking_id'];
				$fromCity	 = $row['fromCity'];
				$toCity		 = $row['toCity'];

				$hash	 = Yii::app()->shortHash->hash($bkgId);
				$url	 = 'aaocab.com/bkpn/' . $bkgId . '/' . $hash;

				$smsContent	 = "Dear Customer, Your Booking " . $bookingId . " from " . $fromCity . " to " . $toCity . " has been created. Get Instant 5% DISCOUNT or, claim a 50% Cashback* in Gozo Coins by paying at least 15% Advance for this Booking. Pay by clicking on the link " . $url . " .- aaocab";
				$smsCom		 = new smsWrapper();

				$smsid = $smsCom->advanceReminderSMS($row['bkg_country_code'], $row['bkg_contact_no'], $bookingId, $bkgId, $smsContent);
				if ($smsid > 0)
				{
					$incCount	 = $row['bkg_adv_reminder_sms_count'] + 1;
					$qry		 = "UPDATE booking_pref SET bkg_adv_reminder_sms_datetime = NOW(), bkg_adv_reminder_sms_count=" . $incCount . " WHERE bpr_bkg_id = " . $row['bkg_id'];
					Yii::app()->db->createCommand($qry)->execute();
				}
			}
		}
		Logger::create("command.booking.sendAdvanceReminderSMS end", CLogger::LEVEL_PROFILE);
	}

//Vendor entry (Vendor Transaction table)
	public function actionAccountingVendor_cash_bank()
	{
		$sql		 = "SELECT * FROM vendor_transactions WHERE ven_ptp_id IN (1,2) AND ven_trans_active = 1 AND ven_trans_status = 1 AND ven_trans_id BETWEEN 58546 AND 76929";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['ven_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['ven_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['ven_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_OPERATOR;
			$paymentGateway->apg_trans_ref_id		 = $result['trans_vendor_id'];
			$paymentGateway->apg_code				 = $result['ven_trans_code'];
			$paymentGateway->apg_mode				 = $result['ven_trans_mode'];
			$paymentGateway->apg_remarks			 = $result['ven_trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['ven_trans_ipaddress'];
			$paymentGateway->apg_user_id			 = $result['ven_admin_id'];
			$paymentGateway->apg_amount				 = $result['ven_trans_amount'];
			$paymentGateway->apg_active				 = $result['ven_trans_active'];
			$paymentGateway->apg_status				 = $result['ven_trans_status'];
			$paymentGateway->apg_date				 = $result['ven_trans_date'];
			$paymentGateway->apg_response_details	 = $result['ven_trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['ven_trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['ven_trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['ven_trans_txn_id'];
			$paymentGateway->apg_complete_datetime	 = $result['ven_trans_complete_date'];
			$paymentGateway->apg_type				 = $result['ven_trans_type'];

			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $paymentGateway->apg_amount;
			$accTransModel->act_date	 = $paymentGateway->apg_date;
			$accTransModel->act_type	 = Accounting::AT_OPERATOR;
			$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
			$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_OPERATOR, '', $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_OPERATOR, $paymentGateway->apg_type, $paymentGateway->apg_user_id, $result['ven_trans_id']);
		}
	}

//Vendor entry (Vendor Transaction table)
	public function actionAccountingVendor_journal_gozocoin()
	{
		$sql		 = "SELECT * FROM vendor_transactions WHERE ven_ptp_id IN (7,8) AND ven_trans_active = 1 AND ven_trans_status = 1";
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i			 = 0;
		foreach ($resultset as $result)
		{
			$i++;
			echo "test=========================>" . $i;
			$trans									 = [];
			$paymentGateway							 = new PaymentGateway();
			$paymentGateway->apg_ptp_id				 = $result['ven_ptp_id'];
			$paymentGateway->apg_booking_id			 = $result['ven_booking_id'];
			$paymentGateway->apg_ledger_id			 = PaymentType::model()->ledgerList($result['ven_ptp_id']);
			$paymentGateway->apg_acc_trans_type		 = Accounting::AT_OPERATOR;
			$paymentGateway->apg_trans_ref_id		 = $result['trans_vendor_id'];
			$paymentGateway->apg_code				 = $result['ven_trans_code'];
			$paymentGateway->apg_mode				 = $result['ven_trans_mode'];
			$paymentGateway->apg_remarks			 = $result['ven_trans_remarks'];
			$paymentGateway->apg_ipaddress			 = $result['ven_trans_ipaddress'];
			$paymentGateway->apg_user_id			 = $result['ven_admin_id'];
			$paymentGateway->apg_amount				 = $result['ven_trans_amount'];
			$paymentGateway->apg_active				 = $result['ven_trans_active'];
			$paymentGateway->apg_status				 = $result['ven_trans_status'];
			$paymentGateway->apg_date				 = $result['ven_trans_date'];
			$paymentGateway->apg_response_details	 = $result['ven_trans_response_details'];
			$paymentGateway->apg_response_code		 = $result['ven_trans_response_code'];
			$paymentGateway->apg_response_message	 = $result['ven_trans_response_message'];
			$paymentGateway->apg_txn_id				 = $result['ven_trans_txn_id'];
			$paymentGateway->apg_complete_datetime	 = $result['ven_trans_complete_date'];
			$paymentGateway->apg_type				 = $result['ven_trans_type'];

			$accTransModel				 = new AccountTransactions();
			$accTransModel->act_amount	 = $paymentGateway->apg_amount;
			$accTransModel->act_date	 = $paymentGateway->apg_date;
			$accTransModel->act_type	 = Accounting::AT_OPERATOR;
			$accTransModel->act_ref_id	 = $paymentGateway->apg_trans_ref_id;
			$accTransModel->act_remarks	 = $paymentGateway->apg_remarks;
			$accTransModel->AddReceipt($paymentGateway->apg_ledger_id, Accounting::LI_OPERATOR, $result['trans_vendor_id'], $paymentGateway->apg_trans_ref_id, $paymentGateway->apg_remarks, Accounting::AT_OPERATOR, $paymentGateway->apg_type, $paymentGateway->apg_user_id, $result['ven_trans_id']);
		}
	}

	public function actionFlexxiBookingAlert()
	{
		$check = Filter::checkProcess("booking flexxiBookingAlert");
		if (!$check)
		{
			return;
		}
		echo ":: Booking-flexxiBookingAlert Started";
		$dataArrToCancel	 = Booking::model()->findFlexxiBookingOfLastMinute();
		$dataArrToConfirm	 = Booking::model()->findFlexxiBookingOfLastMinuteForConfirmation();

		$dataArrToCancel = Booking::model()->your_array_diff($dataArrToCancel, $dataArrToConfirm);

		foreach ($dataArrToConfirm as $bcbId)
		{
			$bkgIds = Booking::model()->getBkgIdByBcbIdForFlexxiMatch($bcbId['bkg_bcb_id']);
			foreach ($bkgIds as $bkgId)
			{
				$model		 = Booking::model()->findByPk($bkgId);
				$userName	 = $model->bkgUserInfo->getUsername();
				$changes	 = "Dear $userName, we matched your shared taxi request with other riders. A car has been assigned to your booking ID " . $model->bkg_booking_id . ".";
				$logDesc	 = "SMS sent to Consumers for Flexxi booking matched.";

				$msgCom = new smsWrapper();
				$msgCom->sentFlexxiMatchMessage($model->bkgUserInfo->bkg_country_code, $model->bkgUserInfo->bkg_contact_no, $model->bkg_booking_id, $changes, $logDesc);

				$emailObj = new emailWrapper();
				$emailObj->flexxiBookingMatched($bkgId);

				$model->bkgPref->bkg_is_msg_matched_flexxi = 1;
				$model->bkgPref->save();
			}
		}

		foreach ($dataArrToCancel as $bcbId)
		{
			$bkgIds = Booking::model()->getBkgIdByBcbIdForFlexxiMatch($bcbId['bkg_bcb_id']);
			foreach ($bkgIds as $bkgId)
			{
				$model		 = Booking::model()->findByPk($bkgId);
				$userInfo	 = UserInfo::getInstance();
				$text		 = "Autocancelled. No flexxi matched found";
				$bkgid		 = Booking::model()->canBooking($bkgId, $text, 28, $userInfo);
				BookingLog::model()->createLog($bkgid, $text, $userInfo, BookingLog::BOOKING_CANCELLED, $model);

				$userName	 = $model->bkgUserInfo->getUsername();
				$changes	 = "Dear $userName, we could not match other riders to your shared taxi request. Your booking $model->bkg_booking_id has now been cancelled.";
				$logDesc	 = " SMS sent on booking cancelled,No flexxi match found.";

				$msgCom = new smsWrapper();
				$msgCom->sentFlexxiMatchMessage($model->bkgUserInfo->bkg_country_code, $model->bkgUserInfo->bkg_contact_no, $model->bkg_booking_id, $changes, $logDesc);

				$emailObj = new emailWrapper();
				$emailObj->flexxiBookingMatched($bkgId, true);

				$model->bkgPref->bkg_is_msg_matched_flexxi = 1;
				$model->bkgPref->save();
			}
		}
		echo ":: Booking-flexxiBookingAlert End";
	}

	public function actionSettled()
	{
		Booking::model()->markSettled();
	}

	public function actionFlexxiAutoMatch()
	{
		$bookingSub	 = new BookingSub();
		$data		 = BookingSub::model()->getFlexxiBookingsToMatch();
		foreach ($data as $value)
		{
			$model		 = Booking::model()->findByPk($value['bkg_id']);
			$dataToMatch = $bookingSub->getMatchedFlexxiBookings($model, true);
			if ($dataToMatch['bkg_id'] > 0)
			{
				$bookingSub->machedFlexxiBooking($value['bkg_id'], $dataToMatch['bkg_id']);
			}
		}
	}

	public function actionNotifyCustomer()
	{
		$notifyData = BookingAlert::model()->getNotifyData();
		foreach ($notifyData as $key => $value)
		{
			$notifyBooking = Booking::model()->getBooingIdForNotifyCustomer($value['alr_from_date'], $value['alr_to_date'], $value['alr_from_city'], $value['alr_to_city']);
			if ($notifyBooking > 0)
			{
				$formCity	 = Cities::model()->getCityAndStateById($value['alr_from_city']);
				$toCity		 = Cities::model()->getCityAndStateById($value['alr_to_city']);
				$emailObj	 = new emailWrapper();
				$emailObj->flexxiBookingAlert($value['alr_name'], $value['alr_email'], $formCity, $toCity, $value['alr_from_date'], $value['alr_to_date'], $value['alr_bkg_id'], $value['alr_id'], $notifyBooking);
			}
		}
	}

//  TRANFER booking division data
//1
	public function actionUpdatelatlongBooking1()
	{
		try
		{
			$totRecords	 = DBUtil::queryScalar("SELECT count(*) FROM (SELECT brt_id FROM booking_route WHERE brt_active = 1 GROUP BY brt_bkg_id) a");
			$batches	 = ceil($totRecords / 100);
			for ($i = 0; $i <= $batches; $i++)
			{
				$trans1		 = DBUtil::beginTransaction();
				$offset		 = $i * 100;
				$sql		 = "SELECT brt_from_latitude, brt_from_longitude,brt_bkg_id FROM booking_route WHERE brt_active = 1 GROUP BY brt_bkg_id limit 100 offset $offset";
				$bkgRoutes	 = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($bkgRoutes as $value)
				{
					$fromLat		 = ($value['brt_from_latitude'] == '') ? 'null' : $value['brt_from_latitude'];
					$fromLong		 = ($value['brt_from_longitude'] == '') ? 'null' : $value['brt_from_longitude'];
					$succBkgTrail	 = Yii::app()->db->createCommand("UPDATE `booking1` SET `bkg_pickup_lat`= '" . $fromLat . "', `bkg_pickup_long`= '" . $fromLong . "' WHERE bkg_id=" . $value['brt_bkg_id'])->execute();
					echo " $offset Update success " . $value['brt_bkg_id'] . "<br>";
				}

				DBUtil::commitTransaction($trans1);
			}
		}
		catch (Exception $e)
		{
			if ($trans1 != null && $trans1 != '')
			{
				DBUtil::rollbackTransaction($trans1);
			}
			echo "trans rollbacked. " . $e->getMessage();
			return false;
		}
	}

	public function actionAutoAssignmentOld()
	{
		$userInfo	 = UserInfo::getInstance();
		$recordsets	 = BookingVendorRequest::model()->getAutoAssignData();
		foreach ($recordsets as $value)
		{
			$vendor_amount		 = $value['bcb_vendor_amount'];
			$bcb_id				 = $value['bcb_id'];
			$profit_amount		 = $value['gozoAmount'];
			$customerDue		 = $value['customerDue'];
			$upBookingId		 = $value['bsm_upbooking_id'];
			$downBookingId		 = $value['bsm_downbooking_id'];
			$maxVendorAmount	 = $vendor_amount + $profit_amount;
//$maxLossVendorAmount = ROUND($maxVendorAmount * 1.02);
			$maxLossVendorAmount = ROUND($maxVendorAmount * 0.98);
			$result				 = BookingVendorRequest::model()->getVendorIdAutoAssigned($vendor_amount, $bcb_id, $maxVendorAmount, $maxLossVendorAmount, $customerDue);
			if (!$result)
			{
				BookingPref::model()->setManualAssignment($bcb_id);
				continue;
			}
			echo "Booking ID: {$result['bvr_booking_id']} - $vendor_amount - {$result['bvr_bid_amount']} - $maxVendorAmount - {$value['MinBid']} - {$value['MaxBid']} - {$value['cntBid']} \r\n";
			$booking_id	 = $result['bvr_booking_id'];
			$remark		 = 'Auto Assigned';
			$vndId		 = $result['bvr_vendor_id'];

			/* NEW START */
			$bsmModel = BookingSmartmatch::model()->find('bsm_bcb_id=:id', ['id' => $bcb_id]);
			if ($bsmModel != null && $bsmModel->bsm_ismatched == 0)
			{
				$cabmodel					 = BookingCab::model()->findByPk($bcb_id);
				$bookingIds					 = BookingSmartmatch::model()->getMatchBooking($bsmModel->bsm_bcb_id);
				$arrBkgIds[]				 = $bookingIds['bsm_upbooking_id'];
				$arrBkgIds[]				 = $bookingIds['bsm_downbooking_id'];
				$bookingModel				 = Booking::model()->getBookingModelsbyIdsList($arrBkgIds);
				$cabmodel->bookings			 = $bookingModel;
				$cabmodel->bcb_vendor_amount = $vendor_amount;

				if ($cabmodel->validate())
				{
					$transaction = DBUtil::beginTransaction();
					if ($vendor_amount > 0)
					{
						$successVal = 0;
						foreach ($bookingModel as $model)
						{
							if ($model->bkg_bcb_id > 0 && $model->bkgBcb->bcb_vendor_id > 0)
							{

								$reason	 = "Vendor Cancelled for Trip Rematch";
								$bkgid	 = Booking::model()->canVendor($model->bkg_bcb_id, $reason, $userInfo);

								if ($bkgid)
								{
									DBUtil::commitTransaction($transaction);
								}
								else
								{
									DBUtil::rollbackTransaction($transaction);
									$successVal++;
								}
							}
							else if ($model->bkg_status > 2)
							{
								$successVal++;
							}
						}
						if ($successVal == 0)
						{
							$mergedCabModel->bcb_trip_type		 = 1;
							$mergedCabModel->bcb_matched_type	 = 1;
							$cabmodel->bcb_vendor_id			 = trim($vndId);
							try
							{
								if ($cabmodel->validate())
								{
									$cabmodel->save();
									BookingTrail::updateProfitFlag($cabmodel->bcb_id);

									foreach ($bookingModel as $model)
									{
										$model->bkg_bcb_id	 = $cabmodel->bcb_id;
										$model->scenario	 = 'updatestatus';
										$model->save();
									}
									$model	 = $bookingModel[0];
									$result	 = BookingCab::model()->assignVendor($bcb_id, $vndId, $result['bvr_bid_amount'], $remark, UserInfo::getInstance(), 1);

									if ($result->isSuccess())
									{
										BookingVendorRequest::model()->assignVendor($bcb_id, $vndId);
										BookingSmartmatch::model()->deactivateAllPreMatchedBooking($bookingIds['bsm_upbooking_id'], $bookingIds['bsm_downbooking_id'], $bsmModel->bsm_id);
									}
									else
									{
										echo json_encode($result);
									}


									$upbkgid				 = $bookingIds['bsm_upbooking_id'];
									$tripId					 = $cabmodel->bcb_id;
									$desc					 = "Smart Match (Manual) booking " . $bookingIds['bsm_upbooking_id'] . " with " . $bookingIds['bsm_downbooking_id'] . " by " . $userName;
									$eventid				 = BookingLog::SMART_MATCH;
									$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
									BookingLog::model()->createLog($upbkgid, $desc, $userInfo, $eventid, false, $params, '', $tripId);

									$dnbkgid = $bookingIds['bsm_downbooking_id'];
									$desc	 = "Smart Match (Manual) booking " . $bookingIds['bsm_downbooking_id'] . " with " . $bookingIds['bsm_upbooking_id'] . " by " . $userName;
									BookingLog::model()->createLog($dnbkgid, $desc, $userInfo, $eventid, false, $params, '', $tripId);
									DBUtil::commitTransaction($transaction);
								}
								else
								{
									DBUtil::rollbackTransaction($transaction);
								}
							}
							catch (Exception $ex)
							{
								DBUtil::rollbackTransaction($transaction);
								ReturnSet::setException($ex);
							}
						}
					}
				}

				/* NEW END */
			}
			else
			{
				$res = BookingCab::model()->assignVendor($bcb_id, $vndId, $result['bvr_bid_amount'], $remark, UserInfo::getInstance(), 1);
				if ($res->isSuccess())
				{
					BookingVendorRequest::model()->assignVendor($bcb_id, $vndId);
				}
				else
				{
					echo json_encode($res);
				}
			}
		}
	}

	public function actionAutoAssignment()
	{
		$check = Filter::checkProcess("autoAssignment");
		if (!$check)
		{
			return;
		}
//	BookingVendorRequest::model()->autoAssignVendorMatched();
		BookingVendorRequest::autoVendorAssignments();
	}

	public function actionAutoCancel()
	{
		$check = Filter::checkProcess("booking autoCancel");
		if (!$check)
		{
			return;
		}
		$autoCancelBookingArr = Booking::model()->getBookingForAutoCancel();
		foreach ($autoCancelBookingArr as $value)
		{
			$bkid		 = $value['bkg_id'];
			$userInfo	 = UserInfo::getInstance();
			$model		 = Booking::model()->findByPk($bkid);
			$reasonText	 = "Operators did not accept booking";
			$bkgid		 = Booking::model()->canBooking($bkid, $reasonText, 17, $userInfo);
			echo $bkgid . "booking cancel" . "\n";
			$oldModel	 = clone $model;
			$cf			 = $model->bkgPref->bkg_critical_score;
			$desc		 = "Booking auto cancel. (Reason: " . $reasonText . ") CF : " . $cf;
			$eventid	 = BookingLog::BOOKING_AUTOCANCEL;

			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
			$emailObj = new emailWrapper();
			$emailObj->bookingCancellationMail($bkgid);
		}
	}

	public function actionQuotedToUnverified()
	{
		$check = Filter::checkProcess("booking quotedToUnverified");
		if (!$check)
		{
			return;
		}

		$autoCancelQuoteBookingArr = Booking::model()->getQuoteBookingToUnverified();
		foreach ($autoCancelQuoteBookingArr as $val)
		{
			$bkgId			 = $val['bkg_id'];
			$bookingModel	 = Booking::model()->findByPk($bkgId);
			if ($bookingModel->bkg_status != 15)
			{
				break;
			}
			$bookingModel->bkg_status		 = 1;
			$bookingModel->bkg_booking_id	 = Booking::model()->generateBookingid($bookingModel);
			$bookingModel->update();

			$oldModel	 = clone $bookingModel;
//$userInfo					 = UserInfo::getInstance();
			$reasonText	 = "Quote expired";
			$desc		 = "Booking sent to unverified state.(Reason: " . $reasonText . ")";
			$eventid	 = BookingLog::QUOTE_CONVERT_TO_UNVERIFIED;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, $oldModel);

			$adminid									 = BookingLog::model()->getCSRId($bkgId);
			$bookingModel->bkgTrail->bkg_assign_csr		 = $adminid;
			$bookingModel->bkgTrail->bkg_confirm_type	 = BookingTrail::ConfirmType_UnverifiedQuote;
			$bookingModel->bkgTrail->update();
			$admin										 = Admins::model()->findByPk($adminid);
			$aname										 = $admin->adm_fname . ' ' . $admin->adm_lname;
			$desc										 = "CSR ($aname) Auto Assigned";
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::CSR_ASSIGN, false, false);
		}
	}

	public function actionMarkCriticalAssignment()
	{

		$check = Filter::checkProcess("booking markCriticalAssignment");
		if (!$check)
		{
			return;
		}

		echo ":: Booking-MarkCriticalAssignment Started";
		$autoCriticalBookingArr = Booking::model()->getBookingForCriticalAssignment();
		foreach ($autoCriticalBookingArr as $value)
		{
			$bkid = $value['bkg_id'];
			BookingPref::model()->updateCriticalAssignment($bkid);
		}
		echo ":: Booking-MarkCriticalAssignment End";
	}

	public function actionUpdatePartnerTripVerify()
	{
		$sql = "SELECT bkg.bkg_id,bkg.bkg_pickup_date, ttg.ttg_created, btk.bkg_trip_otp, bcb.bcb_driver_id
		FROM `booking` bkg
		INNER JOIN booking_track btk ON  btk.btk_bkg_id=bkg.bkg_id		
		INNER JOIN booking_cab bcb ON bcb.bcb_id= bkg.bkg_bcb_id
		INNER JOIN trip_tracking ttg ON ttg.ttg_bkg_id = bkg.bkg_id
		INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id
		LEFT JOIN trip_otplog trl ON trl.trl_bkg_id = bkg.bkg_id
		INNER JOIN booking_log blg ON blg.blg_booking_id = bkg.bkg_id
		WHERE btk.bkg_ride_start = 1 AND bpr.bkg_trip_otp_required =1
		AND btk.bkg_is_trip_verified =1
		AND bkg.bkg_pickup_date >= '2019-01-19 00:00:00'
		AND bkg.bkg_status IN (5,6,7) 
		AND trl.trl_id IS NULL 
		AND blg.blg_event_id=92 AND ttg.ttg_event_type=215 AND bkg.bkg_agent_id= 450 GROUP BY bkg.bkg_id";

		$data	 = Yii::app()->db->createCommand($sql)->queryAll();
		$i		 = 0;
		foreach ($data as $val)
		{
			$reciveDate			 = $val['ttg_created'];
			$model				 = new TripOtplog();
			$model->trl_date	 = $reciveDate;
			$model->trl_created	 = $reciveDate;
			$model->trl_platform = 4;
			$model->trl_drv_id	 = $val['bcb_driver_id'];
			$model->trl_bkg_id	 = $val['bkg_id'];
			$model->trl_otp		 = $val['bkg_trip_otp'];
			$model->save();
			BookingCab::model()->pushPartnerTripStart($val['bkg_id'], $val['ttg_created']);
			$i++;
		}
		echo 'total ' . $i . ' updated';
	}

	public function actionUpdateOtpTripStart()
	{
		$sql	 = "SELECT bkg.bkg_id,bkg.bkg_pickup_date,   btk.bkg_trip_otp, bcb.bcb_driver_id,bkg_status,aat_id,trl.trl_platform
		FROM `booking` bkg
		INNER JOIN booking_track btk ON  btk.btk_bkg_id=bkg.bkg_id		
		INNER JOIN booking_cab bcb ON bcb.bcb_id= bkg.bkg_bcb_id		 
		INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id
		INNER JOIN trip_otplog trl ON trl.trl_bkg_id = bkg.bkg_id		 
    	LEFT JOIN agent_api_tracking apt ON apt.aat_booking_id = bkg.bkg_id  and apt.aat_type = 11  AND aat_status=1
		WHERE btk.bkg_ride_start = 1 AND bpr.bkg_trip_otp_required =1
		AND btk.bkg_is_trip_verified =1
		AND bkg.bkg_pickup_date >= '2019-03-06 00:00:00'
		AND bkg.bkg_status IN (5,6,7) 	AND aat_id IS NULL	
		AND bkg.bkg_agent_id= 450 GROUP BY bkg.bkg_id";
		$rows	 = DBUtil:: queryAll($sql, DBUtil::SDB());
		foreach ($rows as $data)
		{
			$bkgid		 = $data['bkg_id'];
			$bmodel		 = Booking::model()->findByPk($bkgid);
			$typeAction	 = AgentApiTracking::TYPE_OTP_UPDATE;
			$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			echo " :: Status: " . $mmtResponse->status;
			echo " \n\n ";
		}
	}

	public function actionCriticalityscore()
	{
		$check = Filter::checkProcess("booking criticalityscore");
		if (!$check)
		{
			return;
		}
		echo ":: Booking-Criticalityscore Started";
		$criticalUpdate = BookingPref::model()->updateCriticalityScore();
		if ($criticalUpdate)
		{
			echo $criticalUpdate . " Running cron to compute criticality score ";
		}
		echo ":: Booking-Criticalityscore End";
	}

	public function actionUpdateTrail()
	{
		Logger::create("command.booking.updateTrail start", CLogger::LEVEL_PROFILE);
		$rows = BookingTrail::fetchBookingCancelCompletedToday1();
		foreach ($rows as $row)
		{
			$model = new BookingTrail();
			$model->updateAttr($row['bkg_id'], $row['bkg_completion_dt'], '', '0');
		}
		$var = "Total records updated " . count($rows) . "\n";
		Logger::create($var, CLogger::LEVEL_INFO);
		Logger::create("command.booking.updateTrail end", CLogger::LEVEL_PROFILE);
	}

	public function actionUpdateTrailLastYear()
	{
		$sql	 = "SELECT
				booking.bkg_id,
				booking.bkg_agent_id,
				booking.bkg_status,
				booking.bkg_trip_duration,
				booking.bkg_pickup_date,
				booking.bkg_create_date,
				DATE_ADD(
					booking.bkg_pickup_date,
					INTERVAL booking.bkg_trip_duration MINUTE
				) AS bkg_completion_dt
				FROM `booking`
				INNER JOIN `booking_cab` ON booking_cab.bcb_id = booking.bkg_bcb_id AND booking.bkg_active = 1 AND booking_cab.bcb_active = 1 
				 JOIN `booking_pref` ON booking_pref.bpr_bkg_id = booking.bkg_id 
				 JOIN `booking_trail` ON booking_trail.btr_bkg_id=booking.bkg_id
				WHERE booking.bkg_status IN(6,7,9) 
				AND booking_trail.btr_estimate_complete_date IS NULL
                AND DATE(
						DATE_ADD(
							booking.bkg_pickup_date,
							INTERVAL booking.bkg_trip_duration MINUTE
						)
					) BETWEEN '2018-01-01' AND '2019-03-25'
				GROUP BY booking.bkg_id  
				ORDER BY `bkg_completion_dt` DESC";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row)
		{
			$model = new BookingTrail();
			$model->updateAttr($row['bkg_id'], $row['bkg_completion_dt'], '', '0');
			echo $row['bkg_id'] . " -  updated booking trail\n";
		}
		$var = "Total records updated " . count($rows) . "\n";
		echo $var;
	}

	public function actionSmartmatch()
	{
		Logger::create("smartmatch start", CLogger::LEVEL_INFO);
		try
		{
			$matchedArray = BookingSub::getAutoSmartMatch();
			foreach ($matchedArray as $data)
			{
				$i = 0;

				$isAvailable = BookingSub::model()->validateMatch($data['up_bkg_id'], $data['down_bkg_id']);
				if (!$isAvailable)
				{
					continue;
				}


				$arrTotBookingAmounts	 = Booking::model()->getTotalBookingAmountsbyBookingIds([$data['up_bkg_id'], $data['down_bkg_id']]);
				$matchedVndAmtWithTSTax	 = $arrTotBookingAmounts['matched_vendor_amount'] + $arrTotBookingAmounts['totalTollTax'] + $arrTotBookingAmounts['stateTax'];

				if ($matchedVndAmtWithTSTax > $arrTotBookingAmounts['vendor_amount'] * 0.99)
				{
					echo $data['up_bkg_id'] . "==" . $data['down_bkg_id'] . " not profitable<br>";
					continue;
				}
				$bookingSmartMatch = BookingSmartmatch::model()->find('bsm_upbooking_id=:up AND bsm_downbooking_id=:down', ['up' => $data['up_bkg_id'], 'down' => $data['down_bkg_id']]);

				if ($bookingSmartMatch == '')
				{
//					if ($data['up_vendor_id'] > 0)
//					{
//						$reason	 = "Vendor Cancelled for Trip Rematch";
//						$bkgid	 = Booking::model()->canVendor($data['up_bkg_bcb_id'], $reason);
//						if (!$bkgid)
//						{
//							continue;
//						}
//					}
//					if ($data['down_vendor_id'] > 0)
//					{
//						$reason	 = "Vendor Cancelled for Trip Rematch";
//						$bkgid1	 = Booking::model()->canVendor($data['down_bkg_bcb_id'], $reason);
//						if (!$bkgid1)
//						{
//							continue;
//						}
//					}

					$bookingCab						 = new BookingCab('matchtrip');
					$bookingCab->bcb_bkg_id1		 = implode(",", [$data['up_bkg_id'], $data['down_bkg_id']]);
					$bookingCab->bcb_vendor_amount	 = ROUND($arrTotBookingAmounts['matched_vendor_amount']);
					$bookingCab->bcb_trip_type		 = 1;
					$bookingCab->bcb_matched_type	 = 2;
					if ($bookingCab->validate())
					{
						$transaction								 = DBUtil::beginTransaction();
						$bookingCab->save();
						BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $bookingCab->bcb_bkg_id1);
						$bookingSmartMatch							 = new BookingSmartmatch();
						$bookingSmartMatch->bsm_bcb_id				 = $bookingCab->bcb_id;
						$bookingSmartMatch->bsm_upbooking_id		 = $data['up_bkg_id'];
						$bookingSmartMatch->bsm_downbooking_id		 = $data['down_bkg_id'];
						$bookingSmartMatch->bsm_matchscore			 = $data['MatchScore'];
						$bookingSmartMatch->bsm_vendor_amt_original	 = ROUND($arrTotBookingAmounts['vendor_amount']);
						$bookingSmartMatch->bsm_vendor_amt_matched	 = ROUND($arrTotBookingAmounts['matched_vendor_amount']);
						$bookingSmartMatch->bsm_margin_original		 = ROUND($arrTotBookingAmounts['margin_original'], 2);
						$bookingSmartMatch->bsm_margin_matched		 = ROUND(($arrTotBookingAmounts['bkg_total_amount'] - $matchedVndAmtWithTSTax - $arrTotBookingAmounts['service_tax']) / $arrTotBookingAmounts['bkg_total_amount'], 2) * 100;
						$bookingSmartMatch->bsm_ismatched			 = 0;
						$bookingSmartMatch->bsm_gozo_amount_original = ROUND($arrTotBookingAmounts['gozo_amount']);
						$bookingSmartMatch->bsm_gozo_amount_matched	 = ROUND(($arrTotBookingAmounts['bkg_total_amount'] - $matchedVndAmtWithTSTax - $arrTotBookingAmounts['service_tax']));
						$bookingSmartMatch->bsm_trip_amount			 = ROUND($arrTotBookingAmounts['bkg_total_amount']);
						$bookingSmartMatch->bsm_up_vehicle_type		 = $data['up_vehicle_type'];
						$bookingSmartMatch->bsm_down_vehicle_type	 = $data['down_vehicle_type'];
						$bookingSmartMatch->save();
						$i++;
						echo $data['up_bkg_id'] . "==" . $data['down_bkg_id'] . "<br>";
						Logger::create("smartmatch created " . $i, CLogger::LEVEL_INFO);
						DBUtil::commitTransaction($transaction);
					}
				}
			}
		}
		catch (Exception $e)
		{
			Logger::create("smartmatch error" . $e->getMessage(), CLogger::LEVEL_ERROR);
			if ($transaction != '')
			{
				DBUtil::rollbackTransaction($transaction);
			}
		}
		Logger::create("smartmatch end", CLogger::LEVEL_INFO);
	}

	public function actionUpdateManualAssignment()
	{
		$check = Filter::checkProcess("booking updateManualAssignment");
		if (!$check)
		{
			return;
		}
		BookingPref::processManualAssignments();
	}

	public function actionPricelockForQT()
	{
		$check = Filter::checkProcess("booking pricelockForQT");
		if (!$check)
		{
			return;
		}

		$getBookings = BookingPref::model()->getquotedbookingByCF();
		foreach ($getBookings as $value)
		{
			$params	 = ['bkg_id' => $value['bkg_id']];
			$sql	 = "SELECT count(blg_id) as cnt FROM booking_log WHERE blg_booking_id=:bkg_id AND blg_event_id =134";
			$isExist = DBUtil::queryScalar($sql, DBUtil::MDB(), $params);
			if ($isExist == 0)
			{
				$emailCom = new emailWrapper();
				$emailCom->sendPriceLockLinkByUserId($value['bkg_user_id'], $value['bkg_id']);
			}
		}
	}

	public function actionCanSelfAssignedBooking()
	{

		$check = Filter::checkProcess("booking canSelfAssignedBooking");
		if (!$check)
		{
			return;
		}
		echo ":: Booking-canSelfAssignedBooking Started";
		Booking::model()->cancelSelfAssignedBookings();
		echo ":: Booking-canSelfAssignedBooking End";
	}

	public function actionCancelUnconfirmedCavBookings()
	{
		echo ":: Booking-CancelUnconfirmedCavBookings Started";
		CabAvailabilities::getUnconfirmedCavBookings();
		echo ":: Booking-CancelUnconfirmedCavBookings End";
	}

	public function actionUpdateStatus($bkgid = '')
	{
		$check = Filter::checkProcess("booking updateStatus");
		if (!$check)
		{
			return;
		}

		PaymentGateway::updateStatus($bkgid);
	}

	public function actionRetryFailedPaymentStatus($bkgid = '')
	{
		$check = Filter::checkProcess("booking retryFailedPaymentStatus");
		if (!$check)
		{
			return;
		}

		PaymentGateway::updateFailedStatus($bkgid);
	}

	public function actionLeadReportDataTransfer()
	{
		$db		 = Yii::app()->db;
		$sqlCnt	 = "SELECT COUNT(1) FROM booking WHERE bkg_agent_id IS NULL AND bkg_status IN (1,2,3,5,6,7,9,10,15)";
		$cnt	 = DBUtil::queryScalar($sqlCnt);
		for ($i = 0; $i < $cnt; $i = $i + 1000)
		{
			$sql	 = "SELECT bkg_id,bkg_status FROM booking WHERE  bkg_agent_id IS NULL AND bkg_status IN (1,2,3,5,6,7,9,10,15) ORDER BY bkg_id DESC LIMIT $i,1000";
			$rows	 = DBUtil::queryAll($sql);
			foreach ($rows as $row)
			{
				$sql		 = "SELECT * FROM booking_log WHERE blg_booking_id={$row['bkg_id']} AND blg_event_id IN (3,25,130) ORDER BY blg_id ASC LIMIT 1";
				$rowCreated	 = DBUtil::queryRow($sql);
				if (!$rowCreated)
				{
					continue;
				}
				$createUser		 = $rowCreated['blg_user_id'];
				$createUserType	 = $rowCreated['blg_user_type'];
				switch ($rowCreated['blg_event_id'])
				{
					case 130:
						$createType	 = BookingTrail::CreateType_Quoted;
						break;
					case 25:
						$createType	 = BookingTrail::CreateType_Lead;
						break;
					case 3:
					default:
						$createType	 = BookingTrail::CreateType_Self;
						if ($rowCreated['blg_user_type'] == 4)
						{
							$createType = BookingTrail::CreateType_Quoted;
						}
						break;
				}
				if (!in_array($row['bkg_status'], [2, 3, 5, 6, 7, 9]))
				{
					goto skipConfirm;
				}

				$sql			 = "SELECT *, IF(blg_event_id=5,1,0) as rank FROM booking_log WHERE blg_booking_id={$row['bkg_id']} AND blg_event_id IN (5, 55, 86, 87, 88) ORDER BY rank DESC, blg_id ASC LIMIT 1";
				$rowConfirmed	 = DBUtil::queryRow($sql);
				$confirmEvent	 = $rowConfirmed['blg_event_id'];
				$confirmUser	 = $rowConfirmed['blg_user_id'];
				$confirmUserType = $rowConfirmed['blg_user_type'];

				$sql				 = "SELECT blg_id FROM booking_log WHERE blg_booking_id={$row['bkg_id']} AND blg_event_id IN (131) LIMIT 1";
				$rowQuoteUnverified	 = DBUtil::queryRow($sql);
				if ($rowQuoteUnverified)
				{
					$confirmEvent = 131;
				}
				switch ($confirmEvent)
				{
					case 55:
						$confirmType = BookingTrail::ConfirmType_Self;
						if ($createType == BookingTrail::CreateType_Quoted)
						{
							$confirmType = BookingTrail::ConfirmType_Quote;
						}
						if ($createType == BookingTrail::CreateType_Lead)
						{
							$confirmType = BookingTrail::ConfirmType_Lead;
						}
						break;
					case 5:
						$confirmType = BookingTrail::ConfirmType_Unverified;
						if ($createType == BookingTrail::CreateType_Quoted)
						{
							$confirmType = BookingTrail::ConfirmType_Quote;
						}
						if ($createType == BookingTrail::CreateType_Lead && $createUser == $rowConfirmed['blg_user_id'])
						{
							$confirmType = BookingTrail::ConfirmType_Lead;
						}
						break;
					case 131:
						$confirmType = BookingTrail::ConfirmType_UnverifiedQuote;
						break;
					case 86:
					default:
						$confirmType = BookingTrail::ConfirmType_Unverified;
						if ($createType == BookingTrail::CreateType_Quoted)
						{
							$confirmType = BookingTrail::ConfirmType_Quote;
						}
						break;
				}

				$sql			 = "SELECT * FROM booking_log WHERE blg_booking_id={$row['bkg_id']} AND blg_event_id IN (5, 55) ORDER BY blg_id ASC LIMIT 1";
				$rowConfirmEvent = DBUtil::queryRow($sql);
				$confirmDate	 = $rowConfirmEvent['blg_created'];
				$confirmId		 = $rowConfirmEvent['blg_id'];

				if ($confirmId == '' && in_array($row['bkg_status'], [2, 3, 5, 6, 7, 9]))
				{
					goto skipConfirmFollowup;
				}
				$query = "";
				if (in_array($row['bkg_status'], [2, 3, 5, 6, 7, 9]))
				{
					$query = " AND blg_id<{$confirmId}";
				}

				$sql		 = "SELECT * FROM booking_log WHERE blg_booking_id={$row['bkg_id']} AND blg_user_type=4 AND blg_event_id IN (86, 87, 88, 130, 5, 3) $query ORDER BY blg_id DESC LIMIT 1";
				$rowFollowup = DBUtil::queryRow($sql);
				if (!$rowFollowup)
				{
					goto skipConfirmFollowup;
				}
				$confirmUser	 = $followupUser	 = $rowFollowup['blg_user_id'];
				$confirmUserType = 4;
				if (in_array($rowFollowup['blg_event_id'], [3, 130]))
				{
					goto skipConfirmFollowup;
				}
				$followupDate = $rowFollowup['blg_created'];

				$sql = "UPDATE booking_trail 
							SET btr_unv_followup_by=$followupUser, 	btr_unv_followup_time='$followupDate'
						WHERE btr_bkg_id={$row['bkg_id']}
					";
				$res = DBUtil::execute($sql);

				skipConfirmFollowup:

				if ($confirmType == '' || $confirmDate == '' || $confirmUserType == '' || $confirmUser == '' || $row['bkg_status'] == 10)
				{
					goto skipConfirm;
				}

				$sql = "UPDATE booking_trail 
							SET bkg_confirm_type=$confirmType, bkg_confirm_datetime='$confirmDate',
								bkg_confirm_user_type=$confirmUserType, bkg_confirm_user_id=$confirmUser
						WHERE btr_bkg_id={$row['bkg_id']}
					";
				$res = DBUtil::execute($sql);
				skipConfirm:

				if ($createType == '' || $createUserType == '' || $createUser == '')
				{
					goto skipCreate;
				}
				$sql = "UPDATE booking_trail 
							SET bkg_create_type=$createType, 
								bkg_create_user_type=$createUserType, bkg_create_user_id=$createUser
						WHERE btr_bkg_id={$row['bkg_id']}
					";
				$res = DBUtil::execute($sql);

				skipCreate:
			}
//	$i = $j;
			echo $i . "<br/>";
		}
	}

	public function actionVendorAutoAssignStartLog()
	{
		$check = Filter::checkProcess("booking vendorAutoAssignStartLog");
		if (!$check)
		{
			return;
		}
		echo ":: Booking-VendorAutoAssignStartLog Started";
		BookingTrail::startVendorAutoAssignment();
		echo ":: Booking-VendorAutoAssignStartLog Ends";
	}

	public function actionSetDemSupMisFire()
	{
		BookingTrail::setDemSupMisFire();
	}

	public function actionCriticalTripAmount()
	{
		$check = Filter::checkProcess("booking criticalTripAmount");
		if (!$check)
		{
			return;
		}

		BookingCab::model()->updateCriticalTripAmount();
	}

	public function actionProcessCriticalitySteps()
	{
		$check = Filter::checkProcess("booking processCriticalitySteps");
		if (!$check)
		{
			return;
		}
		BookingSub::model()->processCriticalitySteps();
	}

	public function actionSaveCountBidFloated()
	{
		VendorPref::setBidFloated();
	}

	public function actionUpdatettp()
	{
		BookingTrail::setTtpValues();
	}

	public function actionAssignedToOM()
	{
		BookingSub::model()->getAssignedtoOM();
	}

	public function actionAutoMarkCompleteBooking()
	{
		$check = Filter::checkProcess("booking autoMarkCompleteBooking");
		if (!$check)
		{
			return;
		}

		Booking::model()->autoMarkCompleteBookingCron();
	}

	public function actionUpdateRoutesDetail()
	{
		while (true)
		{
			$sql = "SELECT * FROM booking WHERE bkg_active=1 AND bkg_route_city_ids IS NULL ORDER BY bkg_id DESC LIMIT 0, 1000";
			$res = DBUtil::command($sql)->query();
			if (count($res) == 0)
			{
				break;
			}
			foreach ($res as $row)
			{
				$qry = "CALL prcUpdateBookingRouteDetails({$row['bkg_id']})";
				DBUtil::command($qry)->execute();
			}
			echo $i += 1000;
			echo " - ";
		}
	}

	public function actionGetServedBookingsLastThirtydays()
	{
		BookingPref::model()->getServedBookings();
	}

	public function actionSetUncommonRouteFlag()
	{
		$check = Filter::checkProcess("booking setUncommonRouteFlag");
		if (!$check)
		{
			return;
		}
		BookingPref::model()->setUncommonRouteFlag();
	}

	public function actionSendQuotedBookingSmsEmail()
	{
		$sql	 = "SELECT bkg_id, bkg_agent_id, bkg_contact_no FROM booking 
				INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg_id 
				WHERE bkg_status = 15 AND bkg_create_date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
		$results = DBUtil::queryAll($sql);
		foreach ($results as $value)
		{
			$emailCom = new emailWrapper();
			$emailCom->gotBookingemail($value['bkg_id'], UserInfo::TYPE_SYSTEM);

			// Sending Whatsapp to B2C user
//			if ($value['bkg_agent_id'] > 0 || $value['bkg_contact_no'] == '' || $value['bkg_contact_no'] == null)
//			{
//				continue;
//			}
//
//			$contactData = BookingUser::verifyBookingContact($value['bkg_id'], $value['bkg_contact_no']);
//			if (!$contactData || $contactData['phnIsVerified'] == 0)
//			{
//				continue;
//			}
//
			WhatsappLog::sendPaymentRequestForBkg($value['bkg_id']);
		}
	}

	public function actionReviewmailforTripComplete()
	{
		$nowTime	 = DBUtil::getCurrentTime();
		$dur		 = 30;
		$afterTime	 = date("Y-m-d H:i:s", strtotime($nowTime . " $dur minutes"));
#$sql		 = "SELECT * FROM `booking_track` WHERE `bkg_ride_complete` = 1 AND `bkg_trip_end_time` BETWEEN '$nowTime' AND '$afterTime' ORDER BY `bkg_trip_end_time` DESC";
		$sql		 = "SELECT btk_bkg_id FROM `booking_track` WHERE `bkg_ride_complete` = 1 AND `bkg_trip_end_time` BETWEEN DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND NOW() ORDER BY `bkg_trip_end_time` DESC";
		$recordsets	 = DBUtil::queryAll($sql);
		foreach ($recordsets as $value)
		{

			$emailCom	 = new emailWrapper();
			$logType	 = BookingLog::Vendor;
			$emailCom->markComplete($value['btk_bkg_id'], $logType);
		}
	}

	public function actionExecutePartnerWallet()
	{
		$check = Filter::checkProcess("booking executePartnerWallet");
		if (!$check)
		{
			return;
		}

		BookingInvoice::processPartnerWallet();
	}

	public function actionCreatePdfInvoice()
	{
		$check = Filter::checkProcess("booking createPdfInvoice");
		if (!$check)
		{
			return;
		}

		$query = "SELECT * FROM booking_invoice_request
				WHERE bir_request_status = 0 AND bir_request_type IN(1,3) ORDER BY bir_id ASC LIMIT 1";

		$requestdata = DBUtil::command($query, DBUtil::SDB())->queryAll();

		foreach ($requestdata as $data)
		{
			$reqid		 = $data['bir_id'];
			$reqTypeid	 = $data['bir_request_type'];

			$sql	 = "SELECT bip.bip_id AS bipId,
				bip.bip_bkg_id AS bkgId , 
				bip.bip_bir_id AS requestId
		FROM 
		booking_invoice_process AS bip
		INNER JOIN booking_invoice_request AS bir ON bir.bir_id = bip.bip_bir_id 
		WHERE bip.bip_bir_id = $reqid AND bir.bir_request_type = $reqTypeid
		AND bip.bip_status = 2 AND bir.bir_request_status = 0
		";
			$records = DBUtil::command($sql, DBUtil::SDB())->queryAll();
			$cntpdf	 = count($records);
			$i		 = 0;
			foreach ($records as $row)
			{
				if ($row['bkgId'] != '')
				{
					$bkgId				 = $row['bkgId'];
					$model				 = Booking::model()->findByPk($bkgId);
					$invoiceList		 = Booking::model()->getInvoiceByBooking($bkgId);
					$totPartnerCredit	 = AccountTransDetails::getTotalPartnerCredit($bkgId);
					$totAdvance			 = PaymentGateway::model()->getTotalAdvance($bkgId);
					$totAdvanceOnline	 = PaymentGateway::model()->getTotalOnlinePayment($bkgId);

					$html2pdf					 = Yii::app()->ePdf->mPdf();
					$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
					$html2pdf->writeHTML($css, 1);
					$html2pdf->setAutoTopMargin	 = 'stretch';

					$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');

					$htmlView = $this->renderFile(Yii::getPathOfAlias("application.modules.admin.views.agent.invoice.view") . ".php", array(
						'invoiceList'		 => $invoiceList,
						'totPartnerCredit'	 => $totPartnerCredit,
						'totAdvance'		 => $totAdvance,
						'totAdvanceOnline'	 => $totAdvanceOnline,
						'isPDF'				 => true,
						'isCommand'			 => true
							), true);

					$html2pdf->writeHTML($htmlView);

					$filename	 = $model->bkg_booking_id . '.pdf';
					$filePath	 = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'invoice/' . $row['requestId'];
//$filePath	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploads/invoice/' . $row['requestId'];
					if (!is_dir($filePath))
					{
						mkdir($filePath);
					}
					$file = $filePath . DIRECTORY_SEPARATOR . $filename;
					$html2pdf->Output($file, 'F');

					$processModel				 = BookingInvoiceProcess::model()->findByPk($row['bipId']);
					$processModel->bip_status	 = 1;
					$processModel->save();
				}
				$i++;
			}
			if ($i == $cntpdf)
			{
				$requestModel						 = BookingInvoiceRequest::model()->findByPk($reqid);
				$requestModel->bir_request_status	 = 2;
				$requestModel->save();
			}
		}
	}

	public function actionCreateZipFormate()
	{
		$check = Filter::checkProcess("booking createZipFormate");
		if (!$check)
		{
			return;
		}

		$sql	 = "SELECT * FROM booking_invoice_request 
					INNER JOIN agents ON agents.agt_id = bir_agent_id 
					WHERE bir_request_status = 2 
					ORDER BY bir_id ASC LIMIT 1";
		$records = DBUtil::command($sql, DBUtil::SDB())->queryAll();
		Logger::create("command.booking.createZipFormate h1111", CLogger::LEVEL_PROFILE);
		foreach ($records as $row)
		{
			$reqid	 = $row['bir_id'];
			$sql1	 = "SELECT 
		    bip.bip_id AS bipId,
		    bip.bip_bkg_id AS bkgId,
		    booking.bkg_pickup_date AS pickup,
		    booking.bkg_booking_id AS bookingId,
		    bip.bip_bir_id AS requestId
		FROM
		    booking_invoice_process AS bip
		INNER JOIN booking_invoice_request AS bir
		ON
		    bir.bir_id = bip.bip_bir_id AND bip.bip_bir_id = $reqid
		INNER JOIN booking ON booking.bkg_id = bip.bip_bkg_id
		WHERE
		    bip.bip_status = 1 AND bir_request_status = 2";

			$records1	 = DBUtil::command($sql1, DBUtil::SDB())->queryAll();
			Logger::create("command.booking.createZipFormate h2222", CLogger::LEVEL_PROFILE);
			$cnt		 = count($records1);
			$randval	 = rand(99999, 999999999);
			$zipname	 = 'Invoice' . $randval . '_' . $reqid . '.zip';
			$zip		 = new ZipArchive;

			$filePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'invoice';

			if ($zip->open($filePath . "/" . $zipname, ZipArchive::CREATE) === TRUE)
			{
				Logger::create("command.booking.createZipFormate h3333", CLogger::LEVEL_PROFILE);
				$i = 0;
				foreach ($records1 as $row1)
				{
					Logger::create("command.booking.createZipFormate h4444", CLogger::LEVEL_PROFILE);
					$filename = $row1['bookingId'] . '.pdf';
					$zip->addFromString(basename($filePath . "/" . $row1['requestId'] . "/" . $filename), file_get_contents($filePath . "/" . $row1['requestId'] . "/" . $filename));
					$i++;
				}
				$zip->close();
				Logger::create("command.booking.createZipFormate h5555", CLogger::LEVEL_PROFILE);
				if ($i == $cnt)
				{
					Logger::create("command.booking.createZipFormate h6666", CLogger::LEVEL_PROFILE);
//echo  $link					 = Yii::app()->createAbsoluteUrl('booking/downloaddocs', ['birId' => $row['bir_id'], 'filename' => $zipname]);
					$link								 = Yii::app()->params['fullBaseURL'] . '/booking/downloadDocs?birId=' . $row['bir_id'] . '&filename=' . $zipname;
					$requestModel						 = BookingInvoiceRequest::model()->findByPk($row['bir_id']);
					$requestModel->bir_request_status	 = 1;
					$requestModel->bir_download_link	 = $zipname;
					Logger::create("command.booking.createZipFormate h7777", CLogger::LEVEL_PROFILE);
					if ($requestModel->save())
					{
						Logger::create("command.booking.createZipFormate h8888", CLogger::LEVEL_PROFILE);
						if ($requestModel['bir_request_user_email'] != '' && $link != '')
						{
							Logger::create("command.booking.createZipFormate h9999", CLogger::LEVEL_PROFILE);
							$emailwrapper = new emailWrapper();
							$emailwrapper->sendDownloadInvoiceLink($requestModel['bir_request_user_email'], $link, $row['bir_request_user_id'], $row['bir_id'], $row['agt_company'], $row['bir_bkg_pickup_date_from'], $row['bir_bkg_pickup_date_to'], $requestModel['bir_request_type']);
						}
						Logger::create("command.booking.createZipFormate h101010", CLogger::LEVEL_PROFILE);
					}
				}
			}
		}
	}

	public function actionAutoCancelRule()
	{
		$check = Filter::checkProcess("booking autoCancelRule");
		if (!$check)
		{
			return;
		}
		// As per discussion with deepesh sir booking will mark for auto cancel  in only booking working hour 
		if (Filter::isWorkingHour())
		{
			$autoCancelRule = AutoCancelRule::model()->getList('List');
			AutoCancelRule::updateAutoCancelBooking($autoCancelRule);
		}
		AutoCancelRule::revertAutoCancelBooking();
	}

	public function actionGetBookingForAutoCancelRule()
	{
		$check = Filter::checkProcess("booking getBookingForAutoCancelRule");
		if (!$check)
		{
			return;
		}
		$autoCancelBookingArr = Booking::getBookingForAutoCancelRule();
		foreach ($autoCancelBookingArr as $value)
		{
			try
			{
				$bkid		 = $value['bkg_id'];
				$userInfo	 = UserInfo::model();
				$model		 = Booking::model()->findByPk($bkid);
				$reasonText	 = $value['cnr_reason'];
				$reasonId	 = $value['cnr_id'];
				if ($model->bkgPref->bkg_is_fbg_type == 1)
				{
					$reasonText		 = "Operators did not accept booking";
					$cancelReason	 = CancelReasons::getTFRCancelReason();
					$reasonText		 = $cancelReason['cnr_reason'];
					$reasonId		 = $cancelReason['cnr_id'];
				}
				$bkgid = Booking::model()->canBooking($bkid, $reasonText, $reasonId, $userInfo);
				if ($bkgid)
				{
					$oldModel	 = clone $model;
					$cf			 = $model->bkgPref->bkg_critical_score;
					$desc		 = "Booking auto cancel.(Reason: " . $reasonText . ")" . "CF : " . $cf . ". Auto cancel rule: " . $value['autoCancelRuleId'];
					$eventid	 = BookingLog::BOOKING_AUTOCANCEL;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
					$emailObj	 = new emailWrapper();
					$emailObj->bookingCancellationMail($bkgid);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionAutoUnassignVendor()
	{
		$check = Filter::checkProcess("booking autoUnassignVendor");
		if (!$check)
		{
			return;
		}
		// As per discussion with deepesh sir vendor will unassined only in booking hour
		if (Filter::isWorkingHour())
		{
			$bookingArr = Booking::getListToUnassignVendor();
			foreach ($bookingArr as $value)
			{
				try
				{
					$reason		 = 36;
					$vendorId	 = $value['bcb_vendor_id'];
					$reasontext	 = "Auto-unassign (System). Not allocated Cab/Driver in specified time";
					$result		 = Booking::model()->canVendor($value['bkg_bcb_id'], $reasontext, UserInfo::getInstance(), [$value['bkg_id']], $reason);
					if ($result['success'] != false)
					{
						$modelcab						 = BookingCab::model()->findByPk($value['bkg_bcb_id']);
						$modelcab->bcb_denied_reason_id	 = $reason;
						$modelcab->save();
						$unassignMode					 = 0;
						$updateUnassignDateMode			 = BookingCab::modifyUnassignMode($unassignMode, $value['bkg_bcb_id']);
						$unassignedTime					 = new CDbExpression('NOW()');
						$assignedTime					 = $value['bkg_assigned_at'];
						$pickupTime						 = $value['bkg_pickup_date'];
						$vendorAmount					 = $value['bcb_vendor_amount'];
						$acceptType						 = $value['bcb_assign_mode'];
						$unassignType					 = $value['bcb_vendor_unassign_mode'];
						#$isVendorDirectAccept			 = BookingLog::isVendorDirectAccept($vendorId, $value['bkg_id']);
						#$isVendorManuallyAssigned		 = BookingLog::isVendorManuallyAssigned($vendorId, $value['bkg_id']);

						$vrsModel = VendorStats::model()->getbyVendorId($vendorId);
						if ($vrsModel)
						{
							$dependencyScore = $vrsModel->vrs_dependency;
						}

						if (($acceptType == 2 || $acceptType == 1))
						{
							$amount = $modelcab::GetUnassignPenaltyCharge($unassignedTime, $assignedTime, $pickupTime, $vendorAmount, $acceptType, $dependencyScore);

							if ($amount > 0)
							{
								$penaltyType	 = PenaltyRules::PTYPE_NOT_ALLOCATED_CAB_DRIVER;
								$bkgID			 = $value['bkg_id'];
								$bkg_booking_id	 = $value['bkg_booking_id'];
								$remarks		 = "Booking accepted but Cab+Driver not allocated on time($bkg_booking_id)";
								$result			 = AccountTransactions::checkAppliedPenaltyByType($bkgID, $penaltyType);
								if ($result)
								{
									AccountTransactions::model()->addVendorPenalty($bkgID, $vendorId, $amount, $remarks, '', $penaltyType);
								}
							}
						}
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
	}

	public function actionGetunsuccesfulltrans()
	{

		$sql	 = "SELECT apg.apg_id, atd.adt_trans_ref_id, apg.apg_booking_id,adt_ledger_id,apg.apg_status, apg.apg_date, atd.adt_id
						FROM   payment_gateway  apg
							 LEFT JOIN account_trans_details atd 
						ON (apg.apg_id = atd.adt_trans_ref_id AND apg.apg_ledger_id = atd.adt_ledger_id  ) AND atd.adt_active = 1
						WHERE  apg.apg_date > DATE_SUB(NOW(), INTERVAL 2 DAY) 
						AND apg_start_datetime < DATE_SUB(NOW(), INTERVAL 15 MINUTE)
						AND  apg.apg_ledger_id IN (29,30,23,16,17,18,19,20,21,39,42,32,1,47,49,46,53,58) AND
						apg.apg_acc_trans_type = 1 AND apg.apg_mode= 2 AND apg.apg_status = 1 AND adt_id IS NULL";
		$result	 = DBUtil::query($sql);

		foreach ($result as $row)
		{
			$apgid	 = $row['apg_id'];
			$pgModel = PaymentGateway::model()->findByPk($apgid);

			$pgModel->processBookingPostPayment();
		}
	}

	public function actionUpdateRoutesLatlong()
	{

		$sql = "SELECT *  FROM `booking` WHERE booking.bkg_pickup_date>NOW() AND bkg_status IN(2,3,5,15)";
		$res = DBUtil::command($sql)->query();

		foreach ($res as $row)
		{
			$qry = "CALL prcUpdateBookingRouteDetails({$row['bkg_id']})";
			DBUtil::command($qry)->execute();
		}
	}

	public function actionGetScheduleBookingForConfirmMessages()
	{
		$check = Filter::checkProcess("booking getScheduleBookingForConfirmMessages");
		if (!$check)
		{
			return;
		}
		$ConfirmMessagesArr = BookingScheduleEvent::getScheduleBookingForConfirmMessages();
		foreach ($ConfirmMessagesArr as $value)
		{
			Booking::model()->confirmMessages($value['bse_bkg_id']);
		}
	}

	public function actionCancelFbg()
	{
		$data = BookingSub::getFbgBookings();
		foreach ($data as $value)
		{
			$cancelReason		 = CancelReasons::getTFRCancelReason();
			$cancellation_reason = $cancelReason['cnr_reason'];
			$reasonId			 = $cancelReason['cnr_id'];
			if ($value['bkg_status'] != 9)
			{
				Booking::model()->canbooking($model->bkg_id, $cancellation_reason, $reasonId);
			}
		}
	}

	public function actionGetPassengerDetails()
	{
		$check = Filter::checkProcess("booking getPassengerDetails");
		if (!$check)
		{
			return;
		}

		$sql	 = "SELECT
				booking.bkg_id
				FROM
				booking
				INNER JOIN booking_user ON booking_user.bui_bkg_id = booking.bkg_id AND (booking_user.bkg_contact_no IS NULL OR booking_user.bkg_contact_no = '') 
				WHERE
				booking.bkg_status IN(2, 3, 5)
				AND booking.bkg_agent_id = 18190
				AND booking.bkg_pickup_date BETWEEN DATE_SUB(NOW(),INTERVAL 30 MINUTE) AND DATE_ADD(NOW(), INTERVAL 1 HOUR)
				ORDER BY booking.bkg_pickup_date ASC LIMIT 50";
		$rows	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $data)
		{
			$bkgid		 = $data['bkg_id'];
			$bmodel		 = Booking::model()->findByPk($bkgid);
			$typeAction	 = AgentApiTracking::TYPE_GET_PASSENGER_DETAILS;
			$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			if ($mmtResponse->status == 1)
			{
				$resPassengerDetails			 = json_decode($mmtResponse->response, true);
				$passengerDetails				 = $resPassengerDetails['response'];
				$name							 = explode(" ", $passengerDetails['passenger']['name']);
				$bkgUserModel					 = $bmodel->bkgUserInfo;
				$bkgUserModel->scenario			 = 'validatePassengerInfo';
				$bkgUserModel->bkg_user_fname	 = (!isset($name[0]) ? "" : $name[0]);
				$bkgUserModel->bkg_user_lname	 = (!isset($name[1]) ? "" : $name[1]);
				$bkgUserModel->bkg_user_email	 = $passengerDetails['passenger']['email'];
				$bkgUserModel->bkg_contact_no	 = $passengerDetails['passenger']['phone_number'];
				$bkgUserModel->bkg_country_code	 = $passengerDetails['passenger']['country_code'];
				$cttId							 = Contact::createbyBookingUser($bkgUserModel);
				if ($cttId != '')
				{
					$bkgUserModel->bkg_contact_id = $cttId;
				}
				if (!$bkgUserModel->save())
				{
					$errors = $bkgUserModel->getErrors();
					Logger::create("Validate Errors : " . json_encode($errors));
					throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
				}
				else
				{
					echo "Passenger Details Updated for BKG ID:" . $bmodel->bkg_id;
				}
			}
		}
	}

	public function actionRepushApi()
	{
		$check = Filter::checkProcess("booking repushApi");
		if (!$check)
		{
			return;
		}

//Re-push driver details 
		echo "START Time(drv det) : " . (Filter::getExecutionTime());
		AgentApiTracking::pushFailedDriverDetailsGmt();
		echo "END Time(drv det) : " . (Filter::getExecutionTime());

//Re-push cancelled booking
		echo "START Time(cancel booking) : " . (Filter::getExecutionTime());
		//AgentApiTracking::pushCancelBooking();
		echo "END Time(cancel booking) : " . (Filter::getExecutionTime());

//Re-push left for pickup
		echo "START Time(left for pickup) : " . (Filter::getExecutionTime());
		AgentApiTracking::pushLeftForPickup();
		echo "END Time(left for pickup) : " . (Filter::getExecutionTime());

//Re-push arrived for pickup
		echo "START Time(arrived for pickup) : " . (Filter::getExecutionTime());
		AgentApiTracking::pushArrivedForTrip();
		echo "END Time(arrived for pickup) : " . (Filter::getExecutionTime());

//Re-push start trip
		echo "START Time(start trip) : " . (Filter::getExecutionTime());
		AgentApiTracking::pushStartTrip();
		echo "END Time(start trip) : " . (Filter::getExecutionTime());

//Re-push stop trip
		echo "START Time(stop trip) : " . (Filter::getExecutionTime());
		AgentApiTracking::pushStopTrip();
		echo "END Time(stop trip) : " . (Filter::getExecutionTime());

//Re-push fbg confirm
		echo "START Fbg Confirm : " . (Filter::getExecutionTime());
		AgentApiTracking::pushFbgConfirm();
		echo "END Fbg Confirm : " . (Filter::getExecutionTime());
	}

// this is onetime cron.
	public function actionExecuteRefund()
	{
		$sql	 = "SELECT
					bkg_id,
					btr.btr_cancel_date,
					bkg_pickup_date,
					TIMESTAMPDIFF(
						MINUTE,
						btr.btr_cancel_date,
						bkg_pickup_date
					) AS diff,
						biv.bkg_advance_amount - biv.bkg_refund_amount
					 AS cancel_fee
				FROM
					booking
				INNER JOIN booking_invoice biv ON
					bkg_id = biv.biv_bkg_id AND bkg_status = 9 AND bkg_agent_id = 18190
				INNER JOIN booking_trail btr ON
					bkg_id = btr.btr_bkg_id AND(
						biv.bkg_advance_amount - biv.bkg_refund_amount
					) > 0
				WHERE
					bkg_pickup_date >= '2020-04-01' AND TIMESTAMPDIFF( MINUTE,btr.btr_cancel_date,bkg_pickup_date
					) > 120 limit 50";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$bkg			 = $row['bkg_id'];
				$bkgcandate		 = $row['btr_cancel_date'];
				$model			 = Booking::model()->findByPk($bkg);
				$refundAmount	 = $model->bkgInvoice->bkg_advance_amount;
				$ledger			 = Accounting::LI_PARTNERWALLET;
				$remarks		 = "Refund on booking cancelation";
				if ($model->bkg_agent_id > 0)
				{
					AccountTransactions::UpdateInactiveStatus($bkg);
				}
				if ($refundAmount > 0)
				{
					$transaction = DBUtil::beginTransaction();
					$success	 = AccountTransactions::model()->refundBooking($bkgcandate, $refundAmount, $bkg, PaymentType::TYPE_AGENT_CORP_CREDIT, $remarks, null, UserInfo::model());
					if ($success)
					{
						echo "====" . $bkg . "====" . $refundAmount . "<br>";
					}
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
			}
		}
	}

// this is onetime cron.
	public function actionExecuteRefund2()
	{
		$sql	 = "SELECT * FROM `demo_data` WHERE is_status = 0";
		$records = DBUtil::query($sql, DBUtil::MDB());
		foreach ($records as $row)
		{
			$transaction = null;
			try
			{
				$model				 = Booking::model()->findByPk($row["bkg_id"]);
				$createTimeDiff		 = Filter::getTimeDiff($model->bkgTrail->btr_cancel_date, $model->bkg_create_date);
				$tripTimeDiff		 = Filter::getTimeDiff($model->bkg_pickup_date, $model->bkgTrail->btr_cancel_date);
				$totalAdvance		 = $model->bkgInvoice->bkg_advance_amount;
				$refundArr			 = BookingPref::model()->calculateRefundMMT($tripTimeDiff, $model->bkgInvoice->bkg_total_amount, $totalAdvance, 2, $createTimeDiff, $model->bkg_id);
				$cancelFee			 = $refundArr['cancelCharge'];
				$cancelFeeCharged	 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
				$refund				 = $cancelFeeCharged - $cancelFee;
				$transaction		 = DBUtil::beginTransaction();
				if ($refund > 0)
				{
					AccountTransactions::model()->AddCancellationCharge($row["bkg_id"], $model->bkgTrail->btr_cancel_date, $cancelFee);
					$success = AccountTransactions::model()->refundBooking($model->bkgTrail->btr_cancel_date, $refund, $row["bkg_id"], PaymentType::TYPE_AGENT_CORP_CREDIT, "Cancellation charge recalculated");
				}
				$query = "UPDATE `demo_data` SET is_status = 1 WHERE is_status = 0 AND bkg_id = {$row["bkg_id"]}";
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($ex);
			}
		}
	}

	public function actionProcessPartnerPendingAdvance()
	{
		$check = Filter::checkProcess("booking processPartnerPendingAdvance");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::pendingAdvanceProcess();
	}

	public function actionUpdateBalancebyBookingid()
	{
		$check = Filter::checkProcess("booking updateBalancebyBookingid");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::updateBalancebyBookingid();
	}

	public function actionProcessRefundEvent()
	{
		$check = Filter::checkProcess("booking processRefundEvent");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::processRefundEvent();
	}

	public function actionProcessMarkCompleteEvent()
	{
		$check = Filter::checkProcess("booking processMarkCompleteEvent");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::markCompleteEvent();
	}

	public function actionProcessDriverDetailsToCustomerEvent()
	{
		$check = Filter::checkProcess("booking processDriverDetailsToCustomerEvent");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::driverDetailsToCustomerEvent();
	}

	public function actionProcessPostVendorAssignment()
	{
		$check = Filter::checkProcess("booking processPostVendorAssignment");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::processVendorAssignment();
	}

	public function actionupdateSmtScore()
	{
		$check = Filter::checkProcess("booking updateSmtScore");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::updateSmtScore();
	}

	public function actionSendMessagesToLatestRideCompleted()
	{
		BookingTrack::sendMessagesToLatestRideCompleted();
	}

	public function actionNotifyVendorsForPendingBookings()
	{
		$check = Filter::checkProcess("booking notifyVendorsForPendingBookings");
		if (!$check)
		{
			return;
		}
		BookingCab::notifyVendorsForPendingBookings();
		BookingCab::notifyVendorsForPendingBookings(-1);  // for those whose (NOW and PICKUP) <12  and status =2  as per discussion with deepesh sir
	}

	public function actionFetchBookingCitiesStats()
	{
		$bookingCitiesStatsData = BookingCitiesStats::getList();
		foreach ($bookingCitiesStatsData as $bookingCitiesData)
		{
			try
			{
				BookingCitiesStats::model()->updateAttr($bookingCitiesData);
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

	public function actionFetchBookingStatsHourly()
	{
		Logger::info("\n*********************************** UpdateZoneVendorMapped Start *********************************************\n");
		$minPickupDate = BookingStats::getMinPickupdate();
		if ($minPickupDate != null)
		{
			$date				 = date('Y-m-d', strtotime('-1 day', strtotime($minPickupDate)));
			$fromdate			 = $date;
			$todate				 = $date;
			$bookingStatsData	 = BookingStats::getAllBookingStats($fromdate, $todate);
			$i					 = 0;
			foreach ($bookingStatsData as $bookingData)
			{
				Logger::info("\n*********************************** UpdateZoneVendorMapped ALTER  Start *********************************************\n");
				try
				{
					//$demFireDetails	 = BookingLog::model()->getByBookingIdEventId($bookingData['bks_bkg_id'], array(140));
					$demFireLogDate = BookingLog::getEventLogDate($bookingData['bks_bkg_id'], array(140));

					if ($demFireLogDate)
					{
						$bookingData['bks_demsup_misfire_date'] = $demFireLogDate;
					}


					$denyCount							 = BookingCab::getTotalL1L2BookingCount($bookingData['bks_bkg_id']);
					$bookingData['bks_l1_deny_count']	 = $denyCount['bks_l1_deny_count'] > 0 ? $denyCount['bks_l1_deny_count'] : 0;
					$bookingData['bks_l2_deny_count']	 = $denyCount['bks_l2_deny_count'] > 0 ? $denyCount['bks_l2_deny_count'] : 0;
					$fromZone							 = Zones::model()->getNearestZonebyCity($bookingData['bkg_from_city_id'])['zon_id'];
					$toZone								 = Zones::model()->getNearestZonebyCity($bookingData['bkg_to_city_id'])['zon_id'];
					$bookingData['bks_row_identifier']	 = $bookingData['bks_source_region'] . "-" . $fromZone . "-" . $toZone . "-" . $bookingData['bks_vehicle_type_id'] . "-" . $bookingData['bkg_booking_type'];

					$bidCount						 = BookingCab::model()->getTotalBidCountByBkg($bookingData['bks_bkg_id']);
					$bookingData['bks_bid_count']	 = $bidCount > 0 ? $bidCount : 0;

					$fromCity						 = ZoneCities::model()->getZonByCtyId($bookingData['bkg_from_city_id']);
					$bookingData['bks_msource_zone'] = $fromCity['zct_masterzone_id'] != null ? $fromCity['zct_masterzone_id'] : 0;

					$toCity									 = ZoneCities::model()->getZonByCtyId($bookingData['bkg_to_city_id']);
					$bookingData['bks_mdestination_zone']	 = $toCity['zct_masterzone_id'] != null ? $fromCity['zct_masterzone_id'] : 0;
					BookingStats::model()->updateAttr($bookingData);
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
					Logger::writeToConsole($ex->getMessage());
				}
				$i++;
				Logger::info("\n*********************************** Limit=$i *********************************************\n");
			}
		}
		Logger::info("\n*********************************** FetchBookingStats ENDS *********************************************\n");
	}

	public function actionCanbookingtestuser()
	{
		$check = Filter::checkProcess("booking canbookingtestuser");
		if (!$check)
		{
			return;
		}

		$email		 = 'newux@gozo.cab';
		$password	 = md5('UIUX@101');
		$userModel	 = Users::model()->find('usr_email=:email AND usr_password=:password', ['email' => $email, 'password' => $password]);
		$userId		 = $userModel->user_id;
		$sql		 = "SELECT
					bkg_id
				FROM
					`booking`
				INNER JOIN booking_user ON bui_bkg_id = bkg_id
				INNER JOIN booking_trail ON btr_bkg_id = bkg_id
				WHERE
					bkg_status IN(2) AND bkg_confirm_datetime < DATE_SUB(NOW(), INTERVAL 1 HOUR) AND bkg_user_id = {$userId}";
		$results	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $key => $value)
		{
			Booking::model()->canBooking($value['bkg_id'], "Test user cancel booking cron", 4);
		}
	}

	public function actionBlockassignmenttestuser()
	{
		$check = Filter::checkProcess("booking blockassignmenttestuser");
		if (!$check)
		{
			return;
		}

		$email		 = 'newux@gozo.cab';
		$password	 = md5('UIUX@101');
		$userModel	 = Users::model()->find('usr_email=:email AND usr_password=:password', ['email' => $email, 'password' => $password]);
		$userId		 = $userModel->user_id;
		$sql		 = "UPDATE
					booking_pref bpr,
					(
					SELECT
						booking_pref.bpr_bkg_id,
						booking_pref.bkg_block_autoassignment,
						bpr_id
					FROM
						booking
					INNER JOIN booking_pref ON bpr_bkg_id = bkg_id
					INNER JOIN `booking_user` ON booking_user.bui_bkg_id = bkg_id
					WHERE
						bkg_user_id = {$userId} AND bkg_pickup_date > NOW() AND bkg_block_autoassignment = 0 AND bkg_status IN(2, 15)) a
					SET
						bpr.bkg_block_autoassignment = 1
					WHERE
						bpr.bpr_id = a.bpr_id;";
		$results	 = DBUtil::execute($sql);
	}

	public function actionAutoPenaltyDriverNotLoggedIn()
	{
		BookingSub::penaltyDriverNotLoggedIn();
	}

	public function actionCancelExpiredGozoNow()
	{
		$check = Filter::checkProcess("booking cancelExpiredGozoNow");
		if (!$check)
		{
			return;
		}

		BookingTrail::cancelExpiredGozoNow();
	}

	public function actionUpdGozoAmt()
	{
		$sql	 = "SELECT DISTINCT bkg_bcb_id, bkg_gozo_amount FROM booking, booking_invoice WHERE bkg_id = biv_bkg_id AND bkg_status IN (6,7) AND bkg_pickup_date >= DATE_SUB(NOW(), INTERVAL 15 DAY)";
		$records = DBUtil::query($sql);
		foreach ($records as $row)
		{
			$sql1	 = "SELECT UpdateGozoAmount({$row['bkg_bcb_id']})";
			$numRows = DBUtil::queryScalar($sql1);

			if ($numRows > 0)
			{
				echo "\r\nbcb_id = " . $row['bkg_bcb_id'] . " - {$numRows} - {$row['bkg_gozo_amount']}";
			}
		}
	}

	public function actionMarkALLZoneType()
	{
		$result = BookingPref::allgetBookingZoneType();
		foreach ($result as $row)
		{
			try
			{
				$fromCity					 = $row['bkg_from_city_id'];
				$toCity						 = $row['bkg_to_city_id'];
				$scv_id						 = $row['bkg_vehicle_type_id'];
				$tripType					 = $row['bkg_booking_type'];
				$res						 = DynamicZoneSurge::getDZPPZoneType($fromCity, $toCity, $scv_id, $tripType);
				$model						 = BookingPref::model()->getByBooking($row['bkg_id']);
				$model->bpr_zone_type		 = $res['dzs_zone_type'] != null ? $res['dzs_zone_type'] : 3;
				$model->bpr_row_identifier	 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
				$model->bpr_zone_identifier	 = substr($model->bpr_row_identifier, 0, 13);
				if (!$model->save())
				{
					Logger::writeToConsole(json_encode($model->errors));
				}
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole($ex->getMessage());
				Logger::exception($ex);
			}
		}
	}

	public function actionSendMissedOffersNotificationToCustomers()
	{
		$check = Filter::checkProcess("booking sendMissedOffersNotificationToCustomers");
		if (!$check)
		{
			return;
		}

		BookingTrail::sendMissedOffersNotificationToCustomers();
	}

	public function actionFetchBookingStats()
	{
		$result = BookingStats::getAllBookingStats();
		foreach ($result as $row)
		{
			try
			{
				$models			 = BookingStats::model()->getByBooking($row['bks_bkg_id']);
				$denyCount		 = BookingCab::getTotalL1L2BookingCount($row['bks_bkg_id']);
				$traveltime		 = BookingTrack::getBookingTravelTime($row['bks_bkg_id']);
				//$demFireDetails	 = BookingLog::model()->getByBookingIdEventId($row['bks_bkg_id'], [BookingLog::DEMAND_SUPPLY_MISFIRE]);
				$demFireLogDate	 = BookingLog::getEventLogDate($row['bks_bkg_id'], [BookingLog::DEMAND_SUPPLY_MISFIRE]);
				if (!$models)
				{
					$models					 = new BookingStats();
					$models->bks_added_date	 = DBUtil::getCurrentTime();
				}
				$models->bks_travel_time			 = $traveltime ? $traveltime : NULL;
				$models->bks_va_norm_km				 = round($row['VA_per_km'], 2);
				$models->bks_va_norm_hr				 = $traveltime ? round((($row['VA_normalized_amount'] * 60) / ($traveltime)), 2) : NULL;
				$models->bks_l1_deny_count			 = $denyCount[0] > 0 ? $denyCount[0] : 0;
				$models->bks_l2_deny_count			 = $denyCount[1] > 0 ? $denyCount[1] : 0;
				$models->bks_row_identifier			 = $row['bks_row_identifier'];
				$models->bks_zone_identifier		 = $row['bks_zone_identifier'];
				$models->bks_city_identifier		 = $row['bks_city_identifier'];
				$models->bks_zone_type				 = $row['bks_zone_type'];
				$models->bks_source_region			 = $row['bks_source_region'];
				$models->bks_vehicle_type_id		 = $row['bks_vehicle_type_id'];
				$models->bks_demsup_misfire_date	 = ($demFireLogDate) ? $demFireLogDate : NULL;
				$bidCount							 = BookingCab::model()->getTotalBidCountByBkg($row['bks_bkg_id']);
				$models->bks_bid_count				 = $bidCount != null ? $bidCount : 0;
				$fromCity							 = ZoneCities::model()->getZonByCtyId($row['bkg_from_city_id']);
				$models->bks_msource_zone			 = $fromCity['zct_masterzone_id'] != null ? $fromCity['zct_masterzone_id'] : 0;
				$toCity								 = ZoneCities::model()->getZonByCtyId($row['bkg_to_city_id']);
				$models->bks_mdestination_zone		 = $toCity['zct_masterzone_id'] != null ? $toCity['zct_masterzone_id'] : 0;
				$models->bks_bkg_id					 = $row['bks_bkg_id'];
				$models->bks_vehicle_type_id		 = $row['bks_vehicle_type_id'];
				$models->bks_state_id				 = $row['bks_state_id'];
				$models->bks_city_id				 = $row['bks_city_id'];
				$models->bks_source_region			 = $row['bks_source_region'];
				$models->bks_bkg_status				 = $row['bks_bkg_status'];
				$models->bks_create_date			 = $row['bks_create_date'];
				$models->bks_pickup_date			 = $row['bks_pickup_date'];
				$models->bks_cancel_date			 = $row['bks_cancel_date'];
				$models->bks_fassignment_date		 = $row['bks_fassignment_date'];
				$models->bks_lassignment_date		 = $row['bks_lassignment_date'];
				$models->bks_diff_create_pickup		 = $row['bks_diff_create_pickup'];
				$models->bks_diff_cancel_pickup		 = $row['bks_diff_cancel_pickup'];
				$models->bks_diff_create_cancel		 = $row['bks_diff_create_cancel'];
				$models->bks_diff_pickup_fassignment = $row['bks_diff_pickup_fassignment'];
				$models->bks_diff_lassignment_pickup = $row['bks_diff_lassignment_pickup'];
				$models->bks_diff_create_fassignment = $row['bks_diff_create_fassignment'];
				$models->bks_diff_lassignment_create = $row['bks_diff_lassignment_create'];
				$models->bks_modified_date			 = DBUtil::getCurrentTime();
				$models->bks_active					 = 1;
				$models->bks_is_pending				 = $row['bks_is_pending'];
				$models->bks_is_complete			 = $row['bks_is_complete'];
				$models->bks_is_cancelled			 = $row['bks_is_cancelled'];
				$models->bks_is_quote				 = $row['bks_is_quote'];
				$models->bks_is_direct				 = $row['bks_is_direct'];
				$models->bks_is_manual				 = $row['bks_is_manual'];
				$models->bks_is_auto				 = $row['bks_is_auto'];
				$models->bks_create_pickup_bins		 = $row['bks_create_pickup_bins'];
				$models->bks_cancel_pickup_bins		 = $row['bks_cancel_pickup_bins'];
				$models->bks_create_cancel_bins		 = $row['bks_create_cancel_bins'];
				$models->bks_pickup_fassignment_bins = $row['bks_pickup_fassignment_bins'];
				$models->bks_lassignment_pickup_bins = $row['bks_lassignment_pickup_bins'];
				$models->bks_create_fassignment_bins = $row['bks_create_fassignment_bins'];
				$models->bks_lassignment_create_bins = $row['bks_lassignment_create_bins'];
				$models->bks_is_local				 = $row['bks_is_local'];
				if (!$models->save())
				{
					throw Exception(json_encode($models->errors), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
	}

	/**
	 * This function is used mark all booking as Manual trigger  where bkg_manual_assignment = 1 AND bkg_status=2 
	 * @return query Object
	 */
	public function actionAutoActivateManualTrigger()
	{
		$result = BookingPref::getAllManualTrigger();
		foreach ($result as $row)
		{
			try
			{
				if (BookingCab::setMaxOut($row['bkg_bcb_id'], 1))
				{
					$userInfo	 = UserInfo::getInstance();
					$desc		 = "Auto activated Manual trigger";
					$eventid	 = BookingLog::MANUAL_ASSIGNMENT_TRIGGERED;
					BookingLog::model()->createLog($row['bkg_id'], $desc, $userInfo, $eventid, false);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionNotifyReadyToPickup()
	{
		$check = Filter::checkProcess("booking notifyReadyToPickup");
		if (!$check)
		{
			return;
		}
		BookingCab::notifyReadyToPickup();
	}

	public function actionNotifyDriverReadyToPickup()
	{
		$check = Filter::checkProcess("booking notifyReadyToPickup");
		if (!$check)
		{
			return;
		}
		BookingCab::notifyDriverReadyToPickup();
	}

	public function actionProcessScheduledEvents()
	{
		$check = Filter::checkProcess("booking processScheduledEvents");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::processScheduledEvents();
	}

	public function actionNotifyVendorAssignCabDriver()
	{
		$result = BookingCab::notifyVendorForAssignCabDriver();
		foreach ($result as $row)
		{
			$bookingId	 = $row['bkg_id'];
			$tripId		 = $row['bkg_bcb_id'];
			$vendorId	 = $row['bcb_vendor_id'];
			$eventCode	 = \NotificationLog::CODE_VENDOR_ASSIGN_CAB_DRIVER;
			$count		 = NotificationLog::checkPrevNotificationCount($tripId, $vendorId, $eventCode);
			if ($count == 0)
			{
				$success = BookingCab::cabDrvAssignNotify($bookingId);
			}
		}
	}

	/* This function is dump all the referral booking which is created by qr code scan  */

	public function actionBookingReferal()
	{
		$result = BookingReferralTrack::getBookingForReferal();
		foreach ($result as $row)
		{
			try
			{
				$isBeneficiaryBookingExists = BookingReferralTrack::isBeneficiaryBookingExistsByBkgId($row['bkg_id']);
				if ($isBeneficiaryBookingExists == 0)
				{
					BookingReferralTrack::add($row);
					$refCode	 = QrCode::getCode($row['benefactorId']);
					$message	 = "Booking create by scanning QR Code: " . $refCode;
					$userInfo	 = UserInfo::model(UserInfo::TYPE_CONSUMER, $row['beneficiaryId']);
					BookingLog::model()->createLog($row['bkg_id'], $message, $userInfo, BookingLog::QR_SCAN);
				}
			}
			catch (Exception $ex)
			{
				ReturnSet::setException($ex);
			}
		}
	}

	/* This function is process the payment for QR code scan each for Beneficiary/Benefactor  */

	public function actionProcessReferalPayout()
	{
		$isReferralLive = (int) Config::get('isReferralLive');
		if ($isReferralLive == 1)
		{
			BookingReferralTrack::ProcessPayout();
		}
	}

	public function actionProcessTransferzBookings()
	{
		$check = Filter::checkProcess("booking processTransferzBookings");
		if (!$check)
		{
			return;
		}

		/* @var $process TransferzOffers */
		$process = TransferzOffers::model()->process();
	}

	public function actionCreateTransferzBookings()
	{
		$check = Filter::checkProcess("booking createTransferzBookings");
		if (!$check)
		{
			return;
		}

		/* @var $create TransferzOffers */
		$create = TransferzOffers::model()->create();
	}

	public function actionUpdateTransferzBookings()
	{
		$check = Filter::checkProcess("booking updateTransferzBookings");
		if (!$check)
		{
			return;
		}

		/* @var $update TransferzOffers */
		$update = TransferzOffers::model()->updateBooking();
	}

	/* This function is dump all the referral booking which is created by qr code scan  */

	public function actionCustomerOngoingTrip()
	{
		$check = Filter::checkProcess("booking CustomerOngoingTrip");
		if (!$check)
		{
			return;
		}

		$result = BookingSub::getCustomerOngoingTrip();
		foreach ($result as $row)
		{
			try
			{
				Logger::writeToConsole('BkgId: ' . $row['bkg_id']);
				$isExists = WhatsappLog::isCustomerOngoingTripExist(24, 1, $row['bkg_id']);
				if ($isExists == 0)
				{
					Users::notifyCustomerOngoingTrip($row['bkg_id']);
				}
			}
			catch (Exception $ex)
			{
				ReturnSet::setException($ex);
			}
		}
	}

	/**
	 * this daily cron job is specially created for kayak to send them booking report
	 */
	public function actionChannelPartnerPushReportDaily()
	{
		//Booking::channelPartnerPushReport('daily');
	}

	/**
	 * this monthly cron job is specially created for kayak to send them booking report
	 */
	public function actionChannelPartnerPushReportMonthly()
	{
		//Booking::channelPartnerPushReport('monthly');
	}

	/**
	 * function used to Check the booking cancel status with MMT team against any bookings and same updated on our portal
	 */
	public function actionCancelUnverifiedTFRBookings()
	{
		BookingSub::cancelUnverifiedTFRBookings();
	}

	/**
	 * this function is use for push airport booking to hornok operator
	 */
	public function actionPushBookingsToHornok()
	{

		//$tripType	 = [12,4,10,11];
		/* @var $bookingList booking */
		$bookingList = Booking::getBookings();
		foreach ($bookingList as $data)
		{
			$typeAction = OperatorApiTracking::CREATE_BOOKING;

			$bkgId			 = $data['bkg_id'];
			$bkgCreateDate	 = $data['bkg_create_date'];

			/** @var OperatorApiTracking $cnt */
			$cnt = OperatorApiTracking::checkDuplicateId($bkgId, $typeAction, $bkgCreateDate);
			if ($cnt == 0)
			{
				$model		 = Booking::model()->findByPk($bkgId);
				$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
				$objOperator = Operator::getInstance($operatorId);

				/* @var $objOperator Operator */
				$objOperator = $objOperator->holdBooking($bkgId, $operatorId);
			}
		}
	}

	public function actionProcessCustomerDetailsToOperatorEvent()
	{
		BookingScheduleEvent::customerDetailsToOperatorEvent();
	}

	/**
	 * used to send bid booking details once bid accepted for hornok operator
	 */
//	public function actionPushBidBookingsToHornok()
//	{
//		/* @var $bookingList booking */
//		$bookingList = Booking::getBookingByBidAccepted();
//		foreach ($bookingList as $data)
//		{
//			$operatorApiTracking = OperatorApiTracking::model()->findByPk($data['oat_id']);
//			$operatorApiTracking->oat_request_count = 1;
//			$operatorApiTracking->save();
//			$operatorApiTracking->refresh();
//			$model	 = booking::model()->findByPk($data['bkg_id']);
//			$data	 = OperatorApiTracking::checkDuplicateIdWithBidAcceptedBooking($data['bkg_id']);
//			if ($data['cnt'] > 0)
//            {
//				$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
//				$objOperator = Operator::getInstance($operatorId);
//
//				/* @var $objOperator Operator */
//				$objOperator = $objOperator->holdBooking($model->bkg_id, $operatorId);
//			}
//		}
//	}

	public function actionSendQuoteExpiryReminderToCustomer()
	{
		$agentId = Config::get('Kayak.partner.id');

		$sql	 = "SELECT bkg_id, bkg.bkg_create_date, bui.bkg_user_id, bui.bkg_country_code, bui.bkg_contact_no, bui.bkg_user_email 
					FROM `booking` bkg 
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id 
					INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
					WHERE 1 AND bkg.bkg_active=1 AND bkg.bkg_status = 15 AND (bkg.bkg_agent_id IS NULL OR bkg.bkg_agent_id IN ($agentId)) 
					AND bkg.bkg_create_date <= DATE_SUB(NOW(), INTERVAL 60 MINUTE) 
					AND (btr.bkg_quote_expire_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 360 MINUTE)) 
					ORDER BY bkg_id DESC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			$bkgId			 = $row['bkg_id'];
			$bkgCreateDate	 = $row['bkg_create_date'];
			$bkgUserId		 = $row['bkg_user_id'];
			$bkgCountryCode	 = $row['bkg_country_code'];
			$bkgContactNo	 = $row['bkg_contact_no'];
			$bkgUserEmail	 = $row['bkg_user_email'];

			$sqlCnt	 = "SELECT COUNT(1) cnt 
						FROM `booking` bkg 
						INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id 
						INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
						WHERE 1 AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7) AND bkg.bkg_agent_id IS NULL 
						AND btr.bkg_confirm_datetime >= '{$bkgCreateDate}' AND bkg.bkg_id <> {$bkgId} 
						AND (bui.bkg_user_id = {$bkgUserId} OR bui.bkg_contact_no = '{$bkgContactNo}' OR bui.bkg_user_email = '{$bkgUserEmail}')";
			$cnt	 = DBUtil::queryScalar($sqlCnt, DBUtil::SDB());
			if ($cnt > 0)
			{
				continue;
			}

			$phoneNo		 = $bkgCountryCode . $bkgContactNo;
			$sqlAlreadySent	 = "SELECT COUNT(1) cnt FROM whatsapp_log 
				WHERE whl_wht_id = 49 AND whl_phone_number = '{$phoneNo}' AND whl_created_date >= '{$bkgCreateDate}'";
			$cntSent		 = DBUtil::queryScalar($sqlAlreadySent, DBUtil::SDB());
			if ($cntSent > 1)
			{
				continue;
			}
			Booking::sendQuoteExpiryReminderToCustomer($bkgId);
		}
	}

	public function actionSendQuoteExpiryReminderToCustomerNew()
	{
		$agentId = Config::get('Kayak.partner.id');

		$sql	 = "SELECT bkg_id, bkg.bkg_create_date, bui.bkg_user_id, bui.bkg_country_code, bui.bkg_contact_no, bui.bkg_user_email 
					FROM `booking` bkg 
					INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id 
					INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
					WHERE 1 AND bkg.bkg_active=1 AND bkg.bkg_status = 15 AND (bkg.bkg_agent_id IS NULL OR bkg.bkg_agent_id IN ($agentId)) 
					AND btr.bkg_quote_expire_date IS NOT NULL 
					AND (bkg.bkg_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND DATE_SUB(NOW(), INTERVAL 15 MINUTE)) 
					ORDER BY bkg_id DESC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			$bkgId			 = $row['bkg_id'];
			$bkgCreateDate	 = $row['bkg_create_date'];
			$bkgUserId		 = $row['bkg_user_id'];
			$bkgCountryCode	 = $row['bkg_country_code'];
			$bkgContactNo	 = $row['bkg_contact_no'];
			$bkgUserEmail	 = $row['bkg_user_email'];

			$sqlCnt	 = "SELECT COUNT(1) cnt 
						FROM `booking` bkg 
						INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id 
						INNER JOIN booking_user bui ON bui.bui_bkg_id = bkg.bkg_id 
						WHERE 1 AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7) AND bkg.bkg_agent_id IS NULL 
						AND btr.bkg_confirm_datetime >= '{$bkgCreateDate}' AND bkg.bkg_id <> {$bkgId} 
						AND (bui.bkg_user_id = {$bkgUserId} OR bui.bkg_contact_no = '{$bkgContactNo}' OR bui.bkg_user_email = '{$bkgUserEmail}')";
			$cnt	 = DBUtil::queryScalar($sqlCnt, DBUtil::SDB());
			if ($cnt > 0)
			{
				continue;
			}

			$phoneNo		 = $bkgCountryCode . $bkgContactNo;
			$sqlAlreadySent	 = "SELECT COUNT(1) cnt FROM whatsapp_log 
				WHERE whl_wht_id = 49 AND whl_phone_number = '{$phoneNo}' AND whl_created_date >= '{$bkgCreateDate}'";
			$cntSent		 = DBUtil::queryScalar($sqlAlreadySent, DBUtil::SDB());
			if ($cntSent > 0)
			{
				continue;
			}
			Booking::sendQuoteExpiryReminderToCustomer($bkgId);
		}
	}

	/**
	 * use to send CBR for driver push custom events action perform
	 */
	public function actionDriverPushCustomEventsCBR()
	{
		/* @var $getList Booking */
		$getBookingList = Booking::getPartnerOneTheWayBookings();
		foreach ($getBookingList as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$driverCustomPushEventDuration	 = Config::get('driver.customPushEvents.duration');
					$data							 = CJSON::decode($driverCustomPushEventDuration);
					$model							 = Booking::model()->findByPk($val['bkg_id']);
					$lastEvent						 = $model->bkgTrack->btk_last_event;
					$pickupDate						 = $model->bkg_pickup_date;
					$tripEndTime					 = (new DateTime(date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date . ' + ' . $model->bkg_trip_duration . ' MINUTE'))))->format('Y-m-d H:i:s');
					$leftForPickupMinutes			 = $data['leftforpickupminutes'];
					$driverArrivedMinutes			 = $data['driverarrivedminutes'];
					$tripStartMinutes				 = $data['tripstartminutes'];
					$tripStopMinutes				 = $data['tripStopMinutes'];
					$currDateTime					 = Filter::getDBDateTime();
					$timeDiffMinutes				 = Filter::getTimeDiff($pickupDate, $currDateTime);
					$dateInterval					 = DateTimeFormat::SQLDateTimeToDateTime($pickupDate)->add(new DateInterval('PT' . $tripStartMinutes . 'M'));
					$afterPickupTime				 = DateTimeFormat::DateTimeToSQLDateTime($dateInterval);
					$dateIntervalForTripEnd			 = DateTimeFormat::SQLDateTimeToDateTime($tripEndTime)->add(new DateInterval('PT' . $tripStopMinutes . 'M'));
					$afterEndTime					 = DateTimeFormat::DateTimeToSQLDateTime($dateIntervalForTripEnd);
					$userInfo						 = UserInfo::getInstance();
					$driverId						 = $model->bkgBcb->bcb_driver_id;
					$entityType						 = UserInfo::TYPE_DRIVER;
					$code							 = '91';
					$getPhoneNo						 = ContactPhone::getPhoneNo($driverId, $entityType);
					$phone							 = $code . $getPhoneNo;
					$contactId						 = ContactProfile::getByEntityId($driverId, $entityType);
					if ($lastEvent > 0)
					{
						switch ($lastEvent)
						{
							case BookingTrack::GOING_FOR_PICKUP; // CBR for driver is not arrived yet
								if ($timeDiffMinutes <= (-1 * $driverArrivedMinutes))
								{
									$message = "Ask drive and process arrived event manually.";
									$isSCQ	 = ServiceCallQueue::checkCustomPushApiCbrByBookingId($model->bkg_id, $driverId, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API);
									if ($isSCQ > 0)
									{
										goto skipscq;
									}
									ServiceCallQueue::customPushApiGenerateCbr($phone, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API, $message, $contactId, $driverId, $model->bkg_id);
								}
								break;
							case BookingTrack::DRIVER_ARRIVED; // CBR for trip is not started yet
								if (strtotime($afterPickupTime) <= strtotime($currDateTime))
								{
									$message = "Ask drive and process start trip event manually.";
									$isSCQ	 = ServiceCallQueue::checkCustomPushApiCbrByBookingId($model->bkg_id, $driverId, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API);
									if ($isSCQ > 0)
									{
										goto skipscq;
									}
									ServiceCallQueue::customPushApiGenerateCbr($phone, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API, $message, $contactId, $driverId, $model->bkg_id);
								}
								break;
							case BookingTrack::TRIP_START; // CBR for trip is not end yet
								if (strtotime($afterEndTime) <= strtotime($currDateTime))
								{
									$message = "Ask drive and process end trip event manually.";
									$isSCQ	 = ServiceCallQueue::checkCustomPushApiCbrByBookingId($model->bkg_id, $driverId, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API);
									if ($isSCQ > 0)
									{
										goto skipscq;
									}
									ServiceCallQueue::customPushApiGenerateCbr($phone, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API, $message, $contactId, $driverId, $model->bkg_id);
								}
								break;
						}
					}
					else
					{
						// CBR for driver is not left for pickup yet
						if ($timeDiffMinutes <= $leftForPickupMinutes)
						{
							$message = "Ask driver and process left for pickup event manually.";
							$isSCQ	 = ServiceCallQueue::checkCustomPushApiCbrByBookingId($model->bkg_id, $driverId, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API);
							if ($isSCQ > 0)
							{
								goto skipscq;
							}
							ServiceCallQueue::customPushApiGenerateCbr($phone, ServiceCallQueue::TYPE_DRIVER_CUSTOM_PUSH_API, $message, $contactId, $driverId, $model->bkg_id);
						}
					}
					skipscq:
				}
			}
			catch (Exception $ex)
			{
				ReturnSet::exception($ex);
			}
		}
	}

	public function actionUserCheckRate()
	{
		$check = Filter::checkProcess("booking userCheckRate");
		if (!$check)
		{
			return;
		}
		$sql		 = 'SELECT bkg_id
						FROM `booking_temp` 
						WHERE 1 
							AND NOT EXISTS
							(
								SELECT bkg_id 
								FROM booking
								INNER JOIN booking_user ON booking_user.bui_bkg_id=booking.bkg_id
								WHERE 1
								AND booking.bkg_agent_id IS NULL
								AND booking.bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 4 HOUR) AND NOW() 
								AND booking.bkg_status IN (15,2,3,4,5,6,7,9,10)
								AND booking_user.bkg_user_id=booking_temp.bkg_user_id
							)
							AND booking_temp.bkg_pickup_date > DATE_ADD(NOW(),INTERVAL 1 HOUR)
							AND bkg_is_gozonow=0
							AND bkg_follow_up_status=0 AND bkg_user_id IS NOT NULL 
							AND bkg_ref_booking_id IS NULL AND bkg_contact_no IS NOT NULL 
							AND bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 4 HOUR) AND DATE_SUB(NOW(),INTERVAL 3 HOUR) 
						GROUP BY bkg_contact_no, bkg_from_city_id ORDER BY bkg_id DESC';
		$queryObject = DBUtil::query($sql, DBUtil::SDB());
		foreach ($queryObject as $value)
		{
			try
			{
				$model	 = BookingTemp::model()->findByPk($value['bkg_id']);
				$model->getRoutes();
				$objPage = BookFormRequest::createInstance();
				$objPage->setBookingModel($model);
				$objPage->populateQuote($model);
				$jsonObj = json_decode(json_encode($objPage->sortCategory()), true);
				$cabType = null;
				$fare	 = null;
				foreach ($jsonObj as $row)
				{
					$fare	 = $row['fare']['totalAmount'];
					$cabType = $row['cab']['type'];
					break;
				}
				if ($cabType != null && $fare != null)
				{
					BookingTemp::notifyUserCheckRate($value['bkg_id'], $cabType, $fare);
				}
			}
			catch (Exception $ex)
			{
				ReturnSet::setException($ex);
			}
		}
	}

	public function actionCancelBookingForDeepeshSir()
	{
		$check = Filter::checkProcess("booking CancelBookingForDeepeshSir");
		if (!$check)
		{
			return;
		}
		$userInfo			 = UserInfo::getInstance();
		$userId				 = 1081152;
		$blockAutoBookikng	 = Booking::blockAutoAssignmentForDeepeshSir($userId);
		foreach ($blockAutoBookikng as $value)
		{
			try
			{

				$bkgId			 = $value['bkg_id'];
				$bkgBookingId	 = $value['bkg_booking_id'];
				$count			 = Booking::updateBlockAutoAssignmentForDeepeshSir($value['bpr_id']);
				if ($count > 0)
				{
					BookingLog::model()->createLog($bkgId, 'BookingID: ' . $bkgBookingId . ' is blocked for auto assignment', $userInfo, BookingLog:: BLOCK_AUTOASSIGNMENT, false, false);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
		$autoCancelBookingArr = Booking::getBookingForCancelForDeepeshSir($userId);
		foreach ($autoCancelBookingArr as $value)
		{
			try
			{
				if (Booking::model()->canBooking($value['bkg_id'], 'Autocancelled.On time allocation failed.', 35, $userInfo))
				{
					$model		 = Booking::model()->findByPk($value['bkg_id']);
					BookingLog::model()->createLog($value['bkg_id'], "Booking auto cancel. Deepesh Sir testing booking", $userInfo, BookingLog::BOOKING_AUTOCANCEL, $model);
					$emailObj	 = new emailWrapper();
					$emailObj->bookingCancellationMail($value['bkg_id']);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateAddressReminder()
	{
		$check = Filter::checkProcess("booking updateAddressReminder");
		if (!$check)
		{
			return;
		}

		/* $userId = "129215";
		  $contactId = "166929";
		  $bkgId = "4473112";
		  $bookingId = "OW404473112";
		  $eventId = "47";

		  Users::remindToUpdateAddress($userId, $contactId, $bkgId, $bookingId, $eventId);
		  #Users::remindToUpdateAddress($userId, $contactId, $bookingId, $eventId, 1, 2);
		  #Users::remindToUpdateAddress($userId, $contactId, $bookingId, $eventId, 1, 3); */

		$arr	 = [];
		$arr[]	 = ['startHr' => 22, 'endHr' => 23, 'eventId' => 45];
		$arr[]	 = ['startHr' => 11, 'endHr' => 12, 'eventId' => 46];
		$arr[]	 = ['startHr' => 7, 'endHr' => 8, 'eventId' => 47];

		foreach ($arr as $arrCond)
		{
			$startHr = $arrCond['startHr'];
			$endHr	 = $arrCond['endHr'];
			$eventId = $arrCond['eventId'];

			$sql = "SELECT bkg_id, bkg_booking_id, bkg_user_id, cr_contact_id 
					FROM booking 
					INNER JOIN booking_user ON bui_bkg_id = bkg_id 
					INNER JOIN contact_profile ON cr_is_consumer = bkg_user_id 
					INNER JOIN cities fromCity ON bkg_from_city_id=fromCity.cty_id 
					INNER JOIN cities toCity ON bkg_to_city_id=toCity.cty_id 
					WHERE cr_status=1 AND bkg_status IN (2,3,5) AND (bkg_agent_id IS NULL OR bkg_agent_id = 1249) 
					AND (bkg_pickup_date BETWEEN DATE_ADD(NOW(), INTERVAL {$startHr} HOUR) AND DATE_ADD(NOW(), INTERVAL {$endHr} HOUR)) 
					AND (fromCity.cty_is_airport = 0 AND (bkg_pickup_address = '' OR bkg_pickup_address = fromCity.cty_name))";

			$res = DBUtil::query($sql, DBUtil::SDB());

			Logger::writeToConsole($sql);
			Logger::writeToConsole("Count: " . count($res));

			if ($res)
			{
				foreach ($res as $row)
				{
					$bkgId		 = $row['bkg_id'];
					$bookingId	 = $row['bkg_booking_id'];
					$userId		 = $row['bkg_user_id'];
					$contactId	 = $row['cr_contact_id'];

					Users::remindToUpdateAddress($userId, $contactId, $bkgId, $bookingId, $eventId);
				}
			}
		}
	}

	public function actionInquiryLastTravelReminder()
	{
		$check = Filter::checkProcess("booking inquiryLastTravelReminder");
		if (!$check)
		{
			return;
		}
		BookingTemp::NotificationInquiryLastTravelReminder();
	}

	public function actionQuoteExpiredReminder()
	{
		$check = Filter::checkProcess("booking quoteExpiredReminder");
		if (!$check)
		{
			return;
		}
		Booking::NotificationQuoteExpired();
	}

	public function actionQuoteExpiringReminder()
	{
		$check = Filter::checkProcess("booking quoteExpiringReminder");
		if (!$check)
		{
			return;
		}
		Booking::NotificationQuoteExpiring();
	}

}
