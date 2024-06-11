<?php

class DriverCommand extends BaseCommand
{

	public function actionBlockOnRating()
	{
		Logger::create("command.driver.blockOnRating start", CLogger::LEVEL_PROFILE);
		$data = Drivers::model()->getLowRatingList();
		if ($data > 0)
		{
			$ctr		 = 0;
			$event_id	 = DriversLog::DRIVER_FREEZE;
			foreach ($data as $d)
			{
				$model					 = Drivers::model()->resetScope()->findByPk($d['drv_ref_code']);
				$model->drv_is_freeze	 = 1;
				if ($model->save())
				{
					$desc = "Driver profile frozen due to low ratings (" . $model->drv_overall_rating . ")";
					DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), $event_id, false, false);
					echo $model->drv_id . " - " . $model->drv_name . " is frozen.\n";
				}
			}
		}
		Logger::create("command.driver.blockOnRating end", CLogger::LEVEL_PROFILE);
	}

	public function actionAutoRejectDocument()
	{
		Logger::create("command.driver.autoRejectDocument start", CLogger::LEVEL_PROFILE);
		$data = Drivers::model()->getExpriedPapersList();
		if ($data > 0)
		{
			$ctr = 0;
			foreach ($data as $d)
			{
				$drvdocModel = new Document();
				$drvdocModel->rejectDriverDocument($d['doc_id'], $d['drv_id'], $d['vendor_ids'], UserInfo::getInstance());
			}
		}
		Logger::create("command.driver.autoRejectDocument end", CLogger::LEVEL_PROFILE);
	}

	public function actionAutoApprove()
	{
		$check = Filter::checkProcess("driver autoApprove");
		if (!$check)
		{
			return;
		}
		Logger::create("command.driver.autoApprove Auto Approve Start", CLogger::LEVEL_PROFILE);

		$dataToApprove	 = Document::model()->findApproveList();
		$sumApprove		 = 0;

		if (count($dataToApprove) > 0)
		{
			foreach ($dataToApprove as $value)
			{

				$model	 = new Drivers();
				$success = $model->approve($value['drv_id'], UserInfo::getInstance());
				if ($success == true)
				{
					$message = "Driver [ " . $value['drv_name'] . " ] - is approved.";
					echo $message . "\n";

					$vendors = VendorDriver::model()->findVndIdsByDrvId($value['drv_id']);
					$vendors = explode(',', $vendors['vendor_ids']);
					if (count($vendors) > 0)
					{
						foreach ($vendors as $ven)
						{
							if ($value['drv_id'] != '' && $value['drv_name'] != '' && $ven)
							{
								$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
								AppTokens::model()->notifyVendor($ven, $payLoadData, $message, "Driver Approved");
							}
						}
					}
					Logger::create($message, CLogger::LEVEL_INFO);
					$sumApprove++;
				}
				else
				{
					echo json_encode($model->getErrors());
				}
			}
		}
		Logger::create("command.driver.autoApprove Auto Approve Done. Total " . $sumApprove, CLogger::LEVEL_PROFILE);
	}

	public function actionAutoDisapprove()
	{
		Logger::create("command.driver.autoDisapprove Auto Disapprove Start", CLogger::LEVEL_PROFILE);

		$dataToDisApprove	 = Document::model()->findDisapproveList();
		$sumNotApprove		 = 0;
		if (count($dataToDisApprove) > 0)
		{
			foreach ($dataToDisApprove as $value)
			{

				$vendors = VendorDriver::model()->findVndIdsByDrvId($value['drv_id']);
				//$vendors = explode(',', $vendors['vendor_ids']);
				$vendors = explode(',', $vendors);

				$success = Drivers::model()->disapprove($value['drv_id'], UserInfo::getInstance());
				if ($success == true)
				{

					$message = "Driver [ " . $value['drv_name'] . " ] rejected due to expired paper ( License ). Upload latest papers in Gozo partner app.";
					echo $message . "\n";
					if (count($vendors) > 0)
					{
						foreach ($vendors as $ven)
						{
							if ($value['drv_id'] != '' && $value['drv_name'] != '' && $ven)
							{
								$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
								AppTokens::model()->notifyVendor($ven, $payLoadData, $message, "Driver Rejected");
							}
						}
					}
					$sumNotApprove++;
				}
			}
		}
		Logger::create("command.driver.autoDisapprove Auto Disapprove Done. Total " . $sumNotApprove, CLogger::LEVEL_PROFILE);
	}

	public function actionBroadcastMsg_OLD()
	{
		Logger::create("command.driver.broadcastMsg start", CLogger::LEVEL_PROFILE);
		$sql = "SELECT DISTINCT drivers.drv_id
                FROM `app_tokens` 
                INNER JOIN `drivers` ON drivers.drv_id=app_tokens.apt_entity_id  
                WHERE app_tokens.apt_user_type=5 
                AND app_tokens.apt_status=1 
                AND app_tokens.apt_logout IS NULL 
                AND app_tokens.apt_last_login > DATE_SUB(NOW(), INTERVAL 10 DAY) 
                AND drivers.drv_paytm_phone IS NULL
                GROUP BY app_tokens.apt_entity_id";

		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		$message = "Add your PayTM account in My Profile on Driver app. If you use driver app and customer gives 5star review for the booking, Gozo will send you Rs. 100 directly to your PayTM. Give us your PayTM number in driver app under My Profile section";
		$title	 = "Add your PayTM account";
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$payLoadData = ['EventCode' => Booking::CODE_DRIVER_BROADCAST];
				$data1		 = $row['drv_id'] . " - " . $payLoadData . " - " . $message;
				Logger::create("Data to be sent: " . $data1, CLogger::LEVEL_INFO);
				$success	 = AppTokens::model()->notifyDriver($row['drv_id'], $payLoadData, $message, $title);
				Logger::create("After sent: " . serialize($success), CLogger::LEVEL_INFO);
				//echo "Sent to driver id:" . $row['drv_id'] . "\n";
			}
		}
		Logger::create("command.driver.broadcastMsg end", CLogger::LEVEL_PROFILE);
	}

	public function actionBroadcastMsg()
	{
		$sql	 = "SELECT drivers.drv_ref_code
			    FROM driver_stats
			    INNER JOIN `drivers` ON drivers.drv_id = driver_stats.drs_drv_id AND driver_stats.drs_active = 1 AND drs_last_logged_in > DATE_SUB(NOW(), INTERVAL 10 DAY)
			    WHERE drv_active = 1 AND drivers.drv_id = drivers.drv_ref_code
			    ORDER BY drs_last_logged_in DESC";
		$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
		$message = "Please Visit:\n\nhttp://www.aaocab.com/message";
		$title	 = "Important Notification";
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$payLoadData = ['EventCode' => Booking::CODE_DRIVER_BROADCAST];
				$success	 = AppTokens::model()->notifyDriver($row['drv_ref_code'], $payLoadData, "", $message, "", $title);
				echo "Sent to driver id:" . $row['drv_ref_code'] . "\n";
			}
		}
	}

	public function actionUpdateCode()
	{
		$sql	 = "SELECT drivers.drv_id
			    FROM `drivers`
			    WHERE  ( drivers.drv_code IS NULL OR drivers.drv_code = '') AND drivers.drv_name IS NOT NULL  AND drivers.drv_name != '' AND drivers.drv_active > 0
			    ORDER BY drivers.drv_id ASC";
		$drvIds	 = DBUtil::queryAll($sql, DBUtil::SDB());
		Logger::create("Total Drivers : " . count($drvIds), CLogger::LEVEL_TRACE);
		if (count($drvIds) > 0)
		{
			foreach ($drvIds as $drv)
			{
				try
				{

					$success	 = false;
					$transaction = DBUtil::beginTransaction();
					$arr		 = Filter::getCodeById($drv['drv_id'], $type		 = 'driver');
					if ($arr['success'] == 1)
					{
						$model			 = Drivers::model()->resetScope()->findByPk($drv['drv_id']);
						$model->drv_code = $arr['code'];
						$model->scenario = 'updateCode';
						if ($model->save())
						{
							$success = true;
							if ($success == true)
							{
								DBUtil::commitTransaction($transaction);
								$updateData = $model->drv_id . " - " . $model->drv_code;
								echo $updateData . " Updated\n";
								Logger::create('CODE DATA ===========>: ' . $updateData, CLogger::LEVEL_INFO);
							}
						}
						else
						{

							$errors = json_encode($model->getErrors());
							echo ($errors) . " Not Updated\n";
						}
					}
					else
					{
						$errors = "Could not generate unique code.";
						echo $errors . " Not Updated\n";
					}
				}
				catch (Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					Logger::create('ERRORS =====> : ' . "Exception :" . $e->getMessage() . " Errors :" . $errors, CLogger::LEVEL_ERROR);
				}
			}
		}
	}

	public function actionReadyApprovalScore()
	{
		$rows = Document::model()->getListReadyApproval();

		Logger::create("Total drivers ready for approval : " . count($rows), CLogger::LEVEL_TRACE);
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$drvId		 = $row['drv_id'];
				$docScore	 = $row['updateDocScore'];
				Logger::create("Driver Ready for approval : " . $drvId . ", " . $docScore, CLogger::LEVEL_TRACE);
				DriverDocs::model()->instantReadyForApproval($drvId, $docScore);
			}
		}
	}

	public function actionSenddocuploadlink()
	{
		DriverCabDocs::model()->sendSMStoUnapprovedDriver();
	}

	public function actionProcessapproveduploaded()
	{
		DriverCabDocs::model()->processApprovalStatus();
	}

	public function actionBroadcastNotification()
	{
		$image	 = "http://www.aaocab.com/images/2018/11/diwaliOffer.jpg";
		$sql	 = 'SELECT DISTINCT drivers.drv_id from drivers '
				. 'INNER JOIN app_tokens ON drivers.drv_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type=5'
				. ' AND app_tokens.apt_logout IS NULL AND app_tokens.apt_last_login>DATE_SUB(NOW(), INTERVAL 10 day) '
				. 'WHERE drivers.drv_active = 1 ORDER BY apt_last_login DESC ';
		$message = "DIWALI DHAMAKA";
		//	$message = "To enhance our Customer Service, OTP Compliance is being made mandatory.  From now onwards Non-compliance with OTP before starting the trip will attract a Penalty of Rs.250";
		$ids	 = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($ids as $id)
		{
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData	 = ['EventCode' => Booking::CODE_DRIVER_BROADCAST];
			$success		 = AppTokens::model()->notifyDriver($id['drv_id'], $payLoadData, $notificationId, $message, $image, "Gozo Cabs");
			echo "\n Sent Image notification to Driver Id : " . $id['drv_id'] . " - " . $message;
		}
	}

	public function actionResetTempApprovedDrivers()
	{
		Document::model()->resetTempApprovedDrivers();
	}

	public function actionUpdateDlMismatchedDriverData()
	{
		$vendors = Contact::updateDlMismatchedDriversData();
		echo "success";
	}

	public function actionUpdatePANMismatchedDriverData()
	{
		$vendors = Contact::updatePANMismatchedDriversData();
		echo "success";
	}

	/**
	 * Function for merging duplicate drivers of same contact Ids
	 */
	public function actionMerge()
	{
		$duplicateConIds = "SELECT drv_contact_id, count(1) AS cnt
				FROM drivers
				WHERE drv_contact_id IS NOT NULL AND drv_merge_on IS NULL AND drv_active > 0
				GROUP BY drv_contact_id
				HAVING cnt > 1
				ORDER BY cnt DESC;
			";

		$arrDuplicateIds = DBUtil::queryAll($duplicateConIds);

		if (empty($arrDuplicateIds))
		{
			exit();
		}

		foreach ($arrDuplicateIds as $value)
		{
			$duplicateId = [];
			$sql		 = "SELECT drv_id,drs_last_logged_in,drs_total_trips,drv_ref_code
						FROM drivers
						LEFT JOIN driver_stats ON drivers.drv_id = driver_stats.drs_drv_id
						INNER JOIN contact ON drivers.drv_contact_id = contact.ctt_id
						WHERE drv_id IN (SELECT drv_id
							FROM drivers
							WHERE drv_contact_id = " . $value['drv_contact_id'] . " AND drv_merge_on is NULL)
							ORDER BY drs_last_logged_in DESC, drs_total_trips DESC";
			$result1	 = DBUtil::query($sql);
			if (!empty($result1))
			{
				$index		 = 0;
				$primaryId	 = "";
				foreach ($result1 as $value)
				{
					if ($index == 0)
					{
						$primaryId = $value['drv_id'];
					}

					if ($primaryId !== $value['drv_id'])
					{
						Drivers::merge($primaryId, $value['drv_id']);
						echo "test id================" . $primaryId;
					}

					$index++;
				}
			}
		}
	}

	public function actionUpdateLastLocationToMMT()
	{
		$check = Filter::checkProcess("driver updateLastLocationToMMT");
		if (!$check)
		{
			return;
		}

		$data = BookingTrack::getOngoingBookings(18190);

		foreach ($data as $value)
		{
			$typeAction = AgentApiTracking::TYPE_UPDATE_LAST_LOCATION;
			AgentMessages::model()->pushApiCall(Booking::model()->findByPk($value['bkg_id']), $typeAction);
		}
	}

	public function actionUpdateLastLocationToSpicejet()
	{
		$check = Filter::checkProcess("driver UpdateLastLocationToSpicejet");
		if (!$check)
		{
			return;
		}

		$id		 = Config::get('spicejet.partner.id');
		$data	 = BookingTrack::getOngoingBookings($id);

		foreach ($data as $value)
		{
			$typeAction = AgentApiTracking::TYPE_UPDATE_LAST_LOCATION;
			AgentMessages::model()->pushApiCall(Booking::model()->findByPk($value['bkg_id']), $typeAction);
		}
	}

	public function actionUpdateLastLocationToQuickRide()
	{
		$id		 = Config::get('QuickRide.partner.id');
		$data	 = BookingTrack::getOngoingBookings($id);

		foreach ($data as $value)
		{
			$typeAction = AgentApiTracking::TYPE_UPDATE_LAST_LOCATION;
			AgentMessages::model()->pushApiCall(Booking::model()->findByPk($value['bkg_id']), $typeAction);
		}
	}

	//no show push MMT
	public function actionCustomerNoshowPushToMMT()
	{
		$data = BookingTrackLog::getNoShowBooking();

		foreach ($data as $values)
		{
			$params		 = array('bkgId' => $values['btk_bkg_id']);
			$qry		 = "SELECT COUNT(`aat_id`) as cnt FROM `agent_api_tracking`
                    WHERE `aat_booking_id`= :bkgId AND `aat_type` = 17 AND `aat_status`= 1";
			$dataCount	 = DBUtil::queryScalar($qry, DBUtil::MDB(), $params);

			if ($dataCount < 1)
			{
				$bmodel		 = Booking::model()->findByPk($values['btk_bkg_id']);
				$typeAction	 = AgentApiTracking::TYPE_NO_SHOW;
				$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
				echo "\nPUSHED BKGID: " . $data['btl_bkg_id'];
			}
		}
	}

	public function actionSendDriverAppLink()
	{
		$result = Booking::getAllBookingAssignToDriver();

		foreach ($result as $val)
		{
			$drvId	 = $val['bcb_driver_id'];
			$params	 = array('drvId' => $drvId);
			$value	 = "SELECT count(1) FROM `app_tokens` WHERE apt_user_type = 5 AND apt_entity_id = :drvId AND apt_status = 1 AND apt_device_token IS NOT NULL";
			$data	 = DBUtil::queryScalar($value, DBUtil::MDB(), $params);

			if ($data <= 0)
			{
				$ext		 = '91';
				$number		 = $val['phn_phone_no'];
				$msg		 = 'Dear User, download driver app using this url http://c.gozo.cab/hz2ys';
				$usertype	 = SmsLog::Driver;

				$sms	 = new Messages();
				$res	 = $sms->sendMessage($ext, $number, $msg, 0);
				smsWrapper::createLog($ext, $number, $val['bkg_booking_id'], $msg, $res, $usertype);
				$model	 = BookingTrail::model()->getbyBkgId($val['bkg_id']);
				if ($res != '')
				{
					$model->btr_driver_free_pickup_msg = 1;
					$model->save();
				}
			}
		}
	}

	/*
	 * Set driver coins of driver
	 */

	public function actionSetDriverCoins()
	{
		DriverCoins::updateCoinDetails();
	}

	public function actionHighRatingFreezeDriver()
	{
		$result = Drivers::getHighRatingFreezeDriverList();
		foreach ($result as $row)
		{
			$model					 = Drivers::model()->resetScope()->findByPk($row['drv_ref_code']);
			$model->drv_is_freeze	 = 0;
			if ($model->save())
			{
				$desc		 = "Driver unfreezed for ratings (" . $model->drv_overall_rating . ")";
				DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), DriversLog::DRIVER_UNFREEZE, false, false);
				$message	 = "Your driver profile has been unfreezed due to good ratings";
				AppTokens::model()->notifyDriver($model->drv_id, ['EventCode' => Booking::CODE_DRIVER_BROADCAST],"", $message,"", $desc);
			}
		}
	}

	/** used to send driver position to transferz */
    public function actionUpdateLastLocationTransferz()
    {
        $id		 = Config::get('transferz.partner.id');
		$data	 = BookingTrack::getOngoingBookings($id);

		foreach ($data as $value)
		{
			$typeAction = AgentApiTracking::TYPE_UPDATE_LAST_LOCATION;
			AgentMessages::model()->pushApiCall(Booking::model()->findByPk($value['bkg_id']), $typeAction);
		}
    }

}
