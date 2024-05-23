<?php

class PickupAlertCommand extends BaseCommand
{

	protected $email_receipient;

	/**
	 * @deprecated since version 14-10-2019
	 * @author ramala
	 */
	public function actionDay()
	{
		$email = Yii::app()->params['PickupAlertEmail'];
		Booking::model()->sendPickupAlert($email, 1);
	}

	public function actionDriverNotificationAlert()
	{
		Logger::create("command.pickupAlert.driverNotification start", CLogger::LEVEL_PROFILE);
		Booking::model()->sentDriverAssignmentAlert();
		Logger::create("command.pickupAlert.driverNotification end", CLogger::LEVEL_PROFILE);
	}

	public function actionDriverMessageAlert()
	{
		$check = Filter::checkProcess("pickupAlert driverMessageAlert");
		if (!$check)
		{
			return;
		}
		echo ":: PickupAlert-driverMessageAlert Started";

		Logger::create("command.pickupAlert.driverMessageAlert start", CLogger::LEVEL_PROFILE);
		$logType = 10;
		BookingCab::model()->sentDriverMessageAlert($logType);
		//BookingCab::model()->sentCustomerMessageAlert($logType);
		Logger::create("command.pickupAlert.driverMessageAlert end", CLogger::LEVEL_PROFILE);
		echo ":: PickupAlert-driverMessageAlert End";
	}

	public function actionMissingVendor()
	{
		$email = Yii::app()->params['PickupAlertEmail'];
		Booking::model()->sentVendorAssignAlert($email);
	}

	/**
	 * @deprecated since version 14-10-2019
	 * @author ramala
	 */
	public function actionNight()
	{
		$email = Yii::app()->params['PickupAlertEmail'];
		Booking::model()->sendPickupAlert($email, 2);
	}

	public function actionVendor()
	{
		Logger::create("command.pickupAlert.vendor start", CLogger::LEVEL_PROFILE);
		Booking::model()->sendMissingDriverNotification(240);
		Logger::create("command.pickupAlert.vendor end", CLogger::LEVEL_PROFILE);
	}

	public function actionRemindPickup()
	{
		Booking::model()->sendPickupAlertVendorDriver(180, 1);
	}

	public function actionRemindVendor24()
	{
		Booking::model()->sendMissingDriverNotification(1440, 3);
	}

	public function actionVendorPickupDue36()
	{
		Booking::model()->sendVendorPickupDueSummary(2160, '');
	}

	public function actionVendorDriverNotification()
	{
		Booking::model()->sendDriverMissingNotification(960, '', 1);
	}

	public function actionSendCabAssignedEmail()
	{
		Logger::create("command.pickupAlert.cabAssignedEmail start", CLogger::LEVEL_PROFILE);
		/* @var $modelsub BookingSub */
		$modelsub	 = new BookingSub();
		$rows		 = $modelsub->getCabDetailsByHour(240);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$emailObj = new emailWrapper();
				$emailObj->cabAssignemail($row['bkg_id'], BookingLog::System);
				echo "\ncabAssignMail--->Bkg Id : " . $row['bkg_id'];
			}
		}
		Logger::create("command.pickupAlert.cabAssignedEmail end", CLogger::LEVEL_PROFILE);
	}

	public function actionOnTripHost()
	{
		$sql	 = "SELECT * FROM booking
            WHERE bkg_status IN (5) AND
				NOW() BETWEEN DATE_SUB(bkg_pickup_date, INTERVAL 30 MINUTE) AND DATE_ADD(bkg_pickup_date, INTERVAL 5 MINUTE)
				AND bkg_id NOT IN (SELECT blg_booking_id FROM booking_log WHERE blg_event_id=39)
			";
		$cdb	 = Yii::app()->db->createCommand($sql);
		$rows	 = $cdb->queryAll();
		foreach ($rows as $row)
		{
			smsWrapper::onTripHost($row);
			BookingLog::model()->createLog($row['bkg_id'], "On Trip host notification sent by System", UserInfo::getInstance(), BookingLog::ON_TRIP_HOST);
		}
	}

	public function actionOnTripPayment()
	{
		$sql	 = "SELECT * FROM booking
            WHERE bkg_status IN (5) AND
			NOW() BETWEEN DATE_ADD(bkg_pickup_date, INTERVAL 60 MINUTE) AND DATE_ADD(bkg_pickup_date, INTERVAL 120 MINUTE)
			AND bkg_id NOT IN (SELECT blg_booking_id FROM booking_log WHERE blg_event_id=40)
			";
		$cdb	 = Yii::app()->db->createCommand($sql);
		$rows	 = $cdb->queryAll();
		foreach ($rows as $row)
		{
			smsWrapper::onTripPayment($row);
			BookingLog::model()->createLog($row['bkg_id'], "On Trip payment notification sent by System", UserInfo::model(), BookingLog::ON_TRIP_PAYMENT);
		}
	}

	public function actionOnTripGuide()
	{
		$sql	 = "SELECT * FROM booking
                WHERE bkg_status IN (5) AND
			NOW() BETWEEN DATE_ADD(bkg_pickup_date, INTERVAL 120 MINUTE) AND DATE_ADD(bkg_pickup_date, INTERVAL 150 MINUTE)
			AND bkg_id NOT IN (SELECT blg_booking_id FROM booking_log WHERE blg_event_id=41)
			";
		$cdb	 = Yii::app()->db->createCommand($sql);
		$rows	 = $cdb->queryAll();
		foreach ($rows as $row)
		{
			smsWrapper::onTripGuide($row);
			BookingLog::model()->createLog($row['bkg_id'], "Local crew upsell notification sent by System", UserInfo::getInstance(), BookingLog::ON_TRIP_GUIDE);
		}
	}

	public function actionCustomerAlertBeforePickup()
	{
		$Interval	 = 2880;
		Logger::create("command.pickupAlert.customerAlertBeforePickup start", CLogger::LEVEL_PROFILE);
		/* @var $modelsub BookingSub */
		$modelsub	 = new BookingSub();
		$rows		 = $modelsub->reconfirmAlertInHrs($Interval);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ((isset($row['email_last_sent']) && $row['email_last_sent'] != NULL) OR ( isset($row['sms_last_sent']) && $row['sms_last_sent'] != NULL))
				{
					echo "Reconfirm Again #" . $row['bkg_id'];
					echo "\n";
					$modelsub->sendReconfirmEmail($row['bkg_id']);
					echo "Reconfirm Email Sent\n";
				}
				else
				{
					echo "Reconfirm #" . $row['bkg_id'];
					echo "\n";
					$modelsub->sendReconfirmEmail($row['bkg_id']);
					$modelsub->sendReconfirmSms($row['bkg_id']);
					echo "Reconfirm Email/Sms Sent\n";
				}
			}
		}
		Logger::create("command.pickupAlert.customerAlertBeforePickup end", CLogger::LEVEL_PROFILE);
	}

	public function actionBestPriceGuarantee()
	{
		/* @var $modelsub BookingSub */
		Logger::create("command.pickupAlert.bestPriceGuarantee start", CLogger::LEVEL_PROFILE);
		$modelsub	 = new BookingSub();
		$rows		 = $modelsub->reminderOnPriceGuarantee();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$day = '';
				if ($row['add90Day'] == $row['pickupDate'])
				{
					$day = 90;
				}
				else if ($row['add60Day'] == $row['pickupDate'])
				{
					$day = 60;
				}
				else if ($row['add45Day'] == $row['pickupDate'])
				{
					$day = 45;
				}
				else if ($row['add30Day'] == $row['pickupDate'])
				{
					$day = 30;
				}
				else if ($row['add15Day'] == $row['pickupDate'])
				{
					$day = 15;
				}
				else if ($row['add10Day'] == $row['pickupDate'])
				{
					$day = 10;
				}
				else if ($row['add5Day'] == $row['pickupDate'])
				{
					$day = 5;
				}
				/* @var $emailCom emailWrapper */
				$emailCom = new emailWrapper;
				$emailCom->priceGuaranteeMail($row['bkg_id'], $day, BookingLog::System);
			}
		}
		Logger::create("command.pickupAlert.bestPriceGuarantee end", CLogger::LEVEL_PROFILE);
	}

	public function actionPostAutoCancelBeforePickup()
	{
		$Interval	 = 24;
		/* @var $modelsub BookingSub */
		Logger::create("command.pickupAlert.autoCancelBeforePickup start", CLogger::LEVEL_PROFILE);
		$modelsub	 = new BookingSub();
		$rows		 = $modelsub->postAutoCancelBooking($Interval);

		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				if ($row['bkg_id'] != '')
				{
					$modelsub->updateOnAutoCancel($row['bkg_id']);
				}
			}
		}
		$bookings = Yii::app()->db->createCommand("SELECT * FROM `booking` as bkg
					 JOIN booking_trail as btr ON btr.btr_bkg_id=bkg_id
					 JOIN booking_user as bui ON bui.bui_bkg_id=bkg_id
					WHERE bkg.bkg_agent_id>0 AND bkg.bkg_status=1 
					AND btr.bkg_platform=5 AND NOW() > DATE_ADD(bkg.bkg_create_date,INTERVAL 90 MINUTE) 
					AND (bui.bkg_verification_code IS NOT NULL OR bui.bkg_verifycode_email IS NOT NULL)")->queryAll();
		if (count($bookings) > 0)
		{
			foreach ($bookings as $row)
			{
				if ($row['bkg_id'] != '')
				{
					$modelsub->unverifiedAutoCanel($row['bkg_id']);
				}
			}
		}
		Logger::create("command.pickupAlert.autoCancelBeforePickup end", CLogger::LEVEL_PROFILE);
	}

	public function actionPreAutoCancelBeforePickup()
	{
		$Interval	 = 4;
		/* @var $modelsub BookingSub */
		$modelsub	 = new BookingSub();
		$rows		 = $modelsub->preAutoCancelBooking($Interval);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$hash		 = Yii::app()->shortHash->hash($row['bkg_id']);
				$url		 = 'gozocabs.com/bkconfirm/' . $row['bkg_id'] . '/' . $hash;
				/* var @model emailWrapper */
				$emailCom	 = new emailWrapper();
				$emailCom->preAutoCancelbeforePickUpMail($row['bkg_id']);
				/* var @model smsWrappper */
				$msgCom		 = new smsWrapper();
				$smsChanges	 = 'Trip ' . $row['bkg_booking_id'] . ' may be auto-cancelled. Avoid cancellation. Reconfirm trip --> ' . $url . '.';
				$msgCom->preAutoCancelBeforePickupSms('91', $row['bkg_contact_no'], $row['bkg_booking_id'], $smsChanges, $row['bkg_id']);
			}
		}
	}

}

/* ===========================End============================================ */

    // $this->render('view');

