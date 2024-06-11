<?php

class VendorCommand extends BaseCommand
{

	public function actionActiveVendorMessage()
	{
		Logger::create("command.vendor.activeVendorMsg start", CLogger::LEVEL_PROFILE);
		$data = Vendors::model()->getMissingPaperList();
		if (count($data) > 0)
		{
			$ctr = 1;
			foreach ($data as $row)
			{

				$vndId = $row['vnd_id'];
				if ($row['ctt_user_type'] == 1)
				{
					$userName = $row['contact_name'];
				}
				else
				{
					$userName = $row['ctt_business_name'];
				}
				$email	 = $row['vnd_email'];
				$subject = 'Complete your Car and Driver paperwork today';
				if (($row['total_vehicle'] > $row['total_vehicle_approved']) || ($row['total_driver'] > $row['total_driver_approved']))
				{
					$incompleteVehicle	 = ($row['total_vehicle'] - $row['total_vehicle_approved']);
					$incompleteDriver	 = ($row['total_driver'] - $row['total_driver_approved']);
					$body				 = 'Dear ' . $userName . ',<br/><br/>';
					$body				 .= 'Your account has ' . $incompleteVehicle . ' cars and ' . $incompleteDriver . ' drivers with incomplete paperwork.';
					$body				 .= '<br/>We need you to add the relevant paperwork for these cars and drivers.';
					$body				 .= '<br/><br/>Please add the paperwork and details for the commercial car and driver today.';
					$body				 .= '<br/><br/>Always deliver 5 star service and get customers to add review for your service. The higher your rating in our system, the more bookings you will receive from the system.';
					$body				 .= '<br/><br/>Thank you,
                                <br/>aaocab Team';
					/* var @model emailWrapper */
					$emailCom			 = new emailWrapper();
					$emailCom->paperworkDriverCarEmail($subject, $body, $userName, $email, $vndId);

					$carTxt		 = ($incompleteVehicle > 1) ? $incompleteVehicle . " Cars" : $incompleteVehicle . " Car";
					$driverTxt	 = ($incompleteDriver > 1) ? $incompleteDriver . " Drivers" : $incompleteDriver . " Driver";
					$message	 = "Your account has " . $carTxt . " and " . $driverTxt . " with incomplete paperwork.";
					$payLoadData = ['vendorId' => $vndId, 'EventCode' => Booking::CODE_MISSING_PAPERWORK];
					$success	 = AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, "Pending Car and Driver paperwork.");
					echo $vndId . " -[" . $row['contact_name'] . "]- " . $message . "\n";
				}
				$ctr++;
			}
		}
		Logger::create("command.vendor.activeVendorMsg end", CLogger::LEVEL_PROFILE);
		//$venActive = 1;
		//Vendors::model()->missingDriverCarInformation($venActive);
		//Vendors::model()->missingDriverCarNotification($venActive);
	}

	public function actionInactiveVendorMessage()
	{
		$venActive = 2;
		Vendors::model()->missingDriverCarInformation($venActive);
		Vendors::model()->missingDriverCarNotification($venActive);
	}

	public function actionVendorFreezeOnCreditLimit()
	{
		
	}

	/**
	 * @deprecated since version 16-07-2020
	 * @author rakesh
	 */
	public function actionSetEffectiveCreditLimitOld()
	{
		Logger::create("command.vendor.setEffectiveCreditLimit start", CLogger::LEVEL_PROFILE);
		$data			 = Vendors::getCollectionList();
		$vendorGraceDays = Yii::app()->params['vendorGraceDays'];
		$ctr			 = 0;
		foreach ($data as $d)
		{
			$model			 = Vendors::model()->resetScope()->findByPk($d['vnd_id']);
			$modelVendStats	 = $model->vendorStats;
			$modelVendPref	 = $model->vendorPrefs;

			$modelVendStats->setLockedAmount();

			$old_credit_limit			 = $modelVendStats->vrs_effective_credit_limit;
			//$paymentDueDays = $d['overdue'];
			$paymentDueDays				 = Vendors::model()->getOverdueDayByDateRange($d['vnd_id']);
			$vendorDue					 = Vendors::model()->getDueByPickupDate($d['vnd_id']);
			$modelVendStats->vrs_vnd_id	 = $d['vnd_id'];
			if ($paymentDueDays <= $vendorGraceDays)
			{
				$effective_credit_limit						 = $d['creditLimit'];
				//$model->vnd_effective_credit_limit = max([0, $effective_credit_limit]);
				$modelVendStats->vrs_effective_credit_limit	 = $effective_credit_limit + $vendorDue;
				$modelVendStats->vrs_effective_overdue_days	 = 0;
			}
			else
			{
				$overdueDays								 = ($paymentDueDays - $vendorGraceDays);
				$effective_credit_limit						 = round($d['creditLimit'] - ($overdueDays * 0.5 * $d['totTrans']));
				//$model->vnd_effective_credit_limit = max([0, min([$effective_credit_limit, $d['creditLimit']])]);
				$modelVendStats->vrs_effective_credit_limit	 = max([($d['vrs_security_amount'] + $vendorDue), min([$effective_credit_limit, $d['creditLimit']])]);
				$modelVendStats->vrs_effective_overdue_days	 = $overdueDays;
			}
			if ($modelVendStats->save())
			{
				//echo "Vendor Id -->" . $model->vnd_id . " - Old Effective Credit Limit -->" . $old_credit_limit . " - Effective Credit Limit -->" . $modelVendStats->vrs_effective_credit_limit . "\n";
				$log = $model->vnd_id . " - Old Effective Credit Limit -->" . $old_credit_limit . " - Effective Credit Limit -->" . $modelVendStats->vrs_effective_credit_limit;
				Logger::create($log, CLogger::LEVEL_TRACE);
				if ($old_credit_limit != $modelVendStats->vrs_effective_credit_limit)
				{
					//echo "\n::";
					//echo $credit_desc = 'Effective credit limit (Old Value : ' . $old_credit_limit . ', New Value : ' . $modelVendStats->vrs_effective_credit_limit . ')';
					$credit_desc = 'Effective credit limit (Old Value : ' . $old_credit_limit . ', New Value : ' . $modelVendStats->vrs_effective_credit_limit . ')';
					Logger::create($credit_desc, CLogger::LEVEL_TRACE);
					VendorsLog::model()->createLog($model->vnd_id, $credit_desc, UserInfo::getInstance(), VendorsLog::EFFECTIVE_CREDIT_LIMIT_UNSET, false, false);
				}
			}


			if ($modelVendStats->vrs_effective_credit_limit > $d['totTrans'] && $model->vnd_active != 2 && $modelVendPref->vnp_is_freeze == 1 && $modelVendPref->vnp_credit_limit_freeze == 1)
			{
				$modelVendPref->vnp_credit_limit_freeze = 0;
				if ($modelVendPref->save())
				{
					$success = Vendors::model()->updateFreeze($d['vnd_id']);
					if ($success)
					{
						$event_id	 = VendorsLog::VENDOR_UNFREEZE;
						$desc		 = "Vendor Unfreezed (Credit Limit restored)";
						Logger::create($desc, CLogger::LEVEL_INFO);
						VendorsLog::model()->createLog($model->vnd_id, $desc, UserInfo::getInstance(), $event_id, false, false);
					}
				}
			}
			$ctr++;
		}
		Logger::create("command.vendor.setEffectiveCreditLimit end", CLogger::LEVEL_PROFILE);
		//echo "\nTotal Count ->" . $ctr;
	}

	// morning corn set
	public function actionFreezeHalfLife()
	{
		Logger::create("command.vendor.freezeHalfLife start", CLogger::LEVEL_PROFILE);
		$data = Vendors::model()->getHalfLifeList();
		if ($data > 0)
		{
			$ctr = 0;
			foreach ($data as $d)
			{
				$amt					 = $d['totTrans'];
				$effectiveCreditLimit	 = $d['effectiveCreditLimit'];
				$freeze					 = (($amt > $effectiveCreditLimit && $amt > 1000) ? 1 : 0);

				$result = Vendors::model()->freezeVendor($d['vnd_id'], Vendors::FR_CREDIT_LIMIT_FREEZE, $freeze);

				if ($freeze == 1)
				{
					$amtTxt		 = 'Rs ' . $amt;
					$message	 = "Payment overdue. Pay $amtTxt to Gozo. Send your payments every week.";
					$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
					$success	 = AppTokens::model()->notifyVendor($d['vnd_id'], $payLoadData, $message, "OVERDUE PAYMENT");
					Logger::create($message, CLogger::LEVEL_INFO);
				}
			}
		}
		Logger::create("command.booking.freezeHalfLife end", CLogger::LEVEL_PROFILE);
	}

	public function actionFreezeOnRating()
	{
		Logger::create("command.vendor.freezeOnRating start", CLogger::LEVEL_PROFILE);
		$data = Vendors::model()->getLowRatingList();
		if ($data > 0)
		{
			$ctr = 0;
			foreach ($data as $d)
			{
				$result = Vendors::model()->freezeVendor($d['vnd_id'], Vendors::FR_LOW_RATING_FREEZE);
			}
		}
		Logger::create("command.vendor.freezeOnRating end", CLogger::LEVEL_PROFILE);
	}

	public function actionUnfreezeOnRating()
	{
		$data = Vendors::model()->unFreezeForRating();
	}

	public function actionSendSmsLastActive()
	{
		Logger::create("command.vendor.lastActiveSms start", CLogger::LEVEL_PROFILE);
		$day	 = 14;
		$rows	 = Vendors::model()->getListOfLastActive($day);
		if (count($rows) > 0)
		{
			for ($i = 0; $i < count($rows); $i++)
			{
				$vendorId	 = $rows[$i]['vnd_id'];
				$message	 = 'You have not logged into Gozo Partner app in ' . $day . ' days. You are missing business opportunities.';
				$smsLog		 = new smsWrapper();
				$return		 = $smsLog->sendAlertMessageVendor(91, $vendorId, $message, SmsLog::SMS_VENDOR_LAST_ACTIVE);
				if ($return == true)
				{
					$desc = "Sms sent for not logged in " . $day . " days";
					VendorsLog::model()->createLog($vendorId, $desc, UserInfo::getInstance(), VendorsLog::SMS, false, false);
				}
			}
		}
		Logger::create("command.vendor.lastActiveSms end", CLogger::LEVEL_PROFILE);
	}

	public function actionAdminFreeze()
	{
		Logger::create("command.vendor.adminFreeze start", CLogger::LEVEL_PROFILE);
		$model = new Vendors();
		$model->autoAdminFreezeNoAgreement(UserInfo::model());
		Logger::create("command.vendor.adminFreeze end", CLogger::LEVEL_PROFILE);
	}

	public function actionUpdateFreeze()
	{
		Logger::create("command.vendor.updateFreeze start", CLogger::LEVEL_PROFILE);
		$model	 = new Vendors();
		$success = $model->updateFreeze();
		Logger::create("command.vendor.adminFreeze end", CLogger::LEVEL_PROFILE);
	}

	public function actionFreezeOnWoAgreement()
	{
		$rows		 = Vendors::model()->getAllWoAgreementFile();
		$userInfo	 = UserInfo::getInstance();
		if ($rows > 0)
		{
			$ctr = 0;
			foreach ($rows as $d)
			{
				$result = Vendors::model()->freezeVendor($d['vnd_id'], Vendors::FR_DOC_PENDING_FREEZE);
				Logger::create($d['vnd_name'] . "==> Vendor is Administrative Freezed due to incomplete documentation or agreement", CLogger::LEVEL_INFO);
			}
		}
	}

	public function actionMissingVendorAlert()
	{

		$email = Yii::app()->params['leadAboveEmail'];
		Booking::model()->sentVendorAssignAlert($email, 1);
	}

	public function actionRegToComplete()
	{
		/* @var $model Vendors */
		Logger::create("command.vendor.completeRegistration start", CLogger::LEVEL_PROFILE);
		$model	 = new Vendors();
		$data	 = $model->fetchRegistrationProcessByInterval();
		if (count($data) > 0)
		{
			foreach ($data as $d)
			{
				/* @var $emailObj emailWrapper */
				$emailObj = new emailWrapper();
				$emailObj->completeVendorRegistration($d['vnd_id']);
			}
		}
		Logger::create("command.vendor.completeRegistration end", CLogger::LEVEL_PROFILE);
	}

	public function actionAutoAssignVendor()
	{
		
	}

	public function actionUpdateVendorsSummary()
	{
		/* @var $vmodels Vendors */
		Logger::create("command.vendor.updateVendorSummary start", CLogger::LEVEL_PROFILE);
		$vmodels = Vendors::model()->getAll();
		$ctr	 = 0;
		if (count($vmodels) > 0)
		{
			foreach ($vmodels as $model)
			{
				$data						 = Vendors::model()->getAvgBalanceByVendorId($model['vnd_id']);
				$avg30Days					 = ($data['avg30Days'] != '') ? $data['avg30Days'] : 0;
				$avg10Days					 = ($data['avg10Days'] != '') ? $data['avg10Days'] : 0;
				$new_vrs_model				 = VendorStats::model()->getbyVendorId($model['vnd_id']);
				$new_vrs_model->vrs_avg10	 = $avg30Days;
				$new_vrs_model->vrs_avg30	 = $avg10Days;
				if ($new_vrs_model->save())
				{
					echo "\n" . ($model['vnd_id']) . " -> Vendor -> " . $model['vnd_name'] . "-> AVG 30 Days -> [" . $avg30Days . "] AVG 10 Days -> [" . $avg10Days . "]\n";
				}
				$ctr++;
			}
		}
		echo "SUM Total ->" . $ctr;
		Logger::create("command.vendor.updateVendorSummary end", CLogger::LEVEL_PROFILE);
	}

	public function actionSetInvoiceDate()
	{
		Vendors::model()->updateInvoiceDate();
	}

	public function actionSendDigitalAgreement()
	{
		$check = Filter::checkProcess("vendor sendDigitalAgreement");
		if (!$check)
		{
			return;
		}
		Logger::info(":: Vendor-sendDigitalAgreement Started");
		$agmtRows = VendorAgreement::model()->findAllDigitalAgreementCopy();
		if (count($agmtRows) > 0)
		{
			foreach ($agmtRows as $row)
			{
				$vendorId = $row['vag_vnd_id'];
				if (Vendors::model()->saveForAgreementCopy($vendorId) == true)
				{
					$var = $row['vag_vnd_id'] . " - " . $row['vnd_name'] . " - Digital Agreemment Copy Saved.";
					Logger::trace($var);
				}
			}
		}

		$emailRows = VendorAgreement::model()->findAllDigitalAgreementEmail();
		if (count($emailRows) > 0)
		{
			foreach ($emailRows as $erow)
			{
				$vendorId = $erow['vag_vnd_id'];
				if (Vendors::model()->emailForAgreementCopy($vendorId) == true)
				{

					$var = $erow['vag_vnd_id'] . " - " . $erow['vnd_name'] . " - Digital Agreemment Mail";
					Logger::trace($var);
				}
			}
		}
		Logger::info(":: Vendor-sendDigitalAgreement End");
	}

	public function actionSaveAgreement()
	{
		$check = Filter::checkProcess("vendor saveAgreement");
		if (!$check)
		{
			return;
		}
		echo ":: Vendor-SaveAgreement Started";
		Logger::create("command.vendor.saveAgreement start", CLogger::LEVEL_PROFILE);
		$host	 = Yii::app()->params['host'];
		$baseURL = Yii::app()->params['fullBaseURL'];
		$rows	 = VendorAgmtDocs::model()->findAllByDate();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$vendorId	 = $row['vd_vnd_id'];
				$reqId		 = $row['vd_agmt_req_id'];
				$deviceId	 = $row['vd_agmt_device_id'];
				//$url = Yii::app()->createUrl('https://'.$host.'/admpnl/vendor/generateSoftCopyForVendor', ['vendorId' => urlencode($vendorId), 'reqId' => urlencode($reqId)]);
				$url		 = $baseURL . '/admpnl/vendor/generateSoftCopyForVendor?vendorId=' . urlencode($vendorId) . '&reqId=' . urlencode($reqId);
				$url		 = str_replace('./', '', $url);
				$data		 = Vendors::model()->file_get_contents_curl($url);
				/*
				  $ch = curl_init();
				  curl_setopt($ch, CURLOPT_URL, $url);
				  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				  $attachPath = curl_exec($ch);
				  curl_close($ch);
				 */
				//echo $row['vnd_name'] . " - " . $reqId . "\n";
				$log		 = $reqId . " - " . $row['vnd_name'] . " - Save Agreement";
				Logger::create($log, CLogger::LEVEL_INFO);
				VendorAgmtDocs::model()->updateStatusByVndReqId($vendorId, $reqId);
				VendorAgmtDocs::model()->deleteByVndReqDeviceId($vendorId, $reqId, $deviceId);
			}
		}
		Logger::create("command.vendor.saveAgreement end", CLogger::LEVEL_PROFILE);
		echo ":: Vendor-SaveAgreement End";
	}

	public function actionSendBulkInvoice()
	{
		$date1	 = date('Y-m-d', strtotime("-9 days"));
		$date2	 = date('Y-m-d', strtotime("-1 day"));

		$date1Inv	 = date('d/m/Y', strtotime("-9 days"));
		$date2Inv	 = date('d/m/Y', strtotime("-1 day"));
		$vendorData	 = Vendors::model()->getBySettleDate($date1, $date2);
		if (count($vendorData) > 0)
		{
			foreach ($vendorData as $vendor)
			{
				$vendorId	 = $vendor['vnd_id'];
				$model		 = VendorPref::model()->getByVendorId($vendorId);
				$ledgerLink	 = Yii::app()->createAbsoluteUrl('admpnl/vendor/generateLedgerForVendor?vendorId=' . urlencode($vendorId) . '&fromDate=' . urlencode($date1Inv) . '&toDate=' . urlencode($date2Inv) . '&email=1');
				$fileArray	 = [0 => ['URL' => $ledgerLink]];
				if ($invoice == 1)
				{
					$invoiceLink = Yii::app()->createAbsoluteUrl('admpnl/vendor/generateInvoiceForVendor?vendorId=' . urlencode($vendorId) . '&fromDate=' . urlencode($date1Inv) . '&toDate=' . urlencode($date2Inv) . '&email=1');
					$fileArray	 = [0 => ['URL' => $ledgerLink], 1 => ['URL' => $invoiceLink]];
				}
				$attachments = json_encode($fileArray);
				if (isset($vendor['eml_email_address']) && $vendor['eml_email_address'] != '')
				{
					$vendorAmount	 = $vendor['vendor_amount'];
					$body			 = 'Dear ' . $vendor['vnd_name'] . ',<br/><br/>
                                Attached attached invoice statement from ' . $date1Inv . ' to ' . $date2Inv . '.<br>';
					if (isset($vendorAmount) && $vendorAmount > 0)
					{
						$body .= 'Your payment for Rs. ' . $vendorAmount . '  is due immediately.';
					}
					$body	 .= '<br/><br/>Please note our bank details included below. Send all payments to the below address and intimate us with the details of your payment at accounts@aaocab.in';
					$body	 .= '<br/><br/>Beneficiary Name: <b>Gozo Technologies Private Limited</b>';
					$body	 .= '<br/>Bank: <b>HDFC BANK LTD</b>';
					$body	 .= '<br/>Branch: <b>Badshahpur, Gurgaon</b>';
					$body	 .= '<br/>A/C number: <b>50200020818192</b>';
					$body	 .= '<br/>IFSC Code: <b>HDFC0001098</b>';
					$body	 .= '<br/><br/>For all queries please write to accounts@aaocab.in <mailto:accounts@aaocab.in>';

					$body					 .= '<br/><br/>Thank you,';
					$body					 .= '<br/>Team aaocab';
					$subject				 = 'Gozo Invoice for ' . $vendor['vnd_name'] . ' from ' . $date1Inv . ' to ' . $date2Inv . '';
					$emailCom				 = new emailWrapper();
					$emailCom->vendorInvoiceEmail($subject, $body, $vendor['eml_email_address'], $ledgerPdf, $invoicePdf, $vendorId, $attachments);
					$mailCtr				 = ($mailCtr + 1);
					$model->vnp_invoice_date = date('Y-m-d');
					$model->vnp_settle_date	 = date('Y-m-d', strtotime("7 day"));
					echo $vendor['vnd_id'] . "-" . $subject . "\n";
					if ($model->save())
					{
						echo "Settle Date-" . $model->vnp_settle_date . "\n";
					}
					exit();
				}
			}
		}
	}

	public function actionBroadcastMsg()
	{

		$sql = 'SELECT DISTINCT vnd_id from vendors 
INNER JOIN app_tokens ON vendors.vnd_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type=2 AND app_tokens.apt_logout IS NULL AND app_tokens.apt_last_login>DATE_SUB(NOW(), INTERVAL 10 day) 
where vnd_active = 1 ORDER BY apt_last_login DESC';

		#$message = "https://youtu.be/qWf409Iy7UY";
		$message = "Please Visit:\n\nhttp://www.aaocab.com/message";

		$ids = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($ids as $id)
		{
			$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
			$success	 = AppTokens::model()->notifyVendor($id['vnd_id'], $payLoadData, $message, "Important Notification");
			echo "\n Sent notification to Vendor Id : " . $id['vnd_id'] . " - " . $message;
		}
	}

	public function actionUpdateCode()
	{
		$sql	 = "SELECT
					vnd_id
					FROM
						`vendors`
					WHERE
					(
						vendors.vnd_code IS NULL OR vendors.vnd_code = ''
					) AND vendors.vnd_active > 0";
		$vndIds	 = DBUtil::queryAll($sql);
		Logger::create("Total Vendors : " . count($vndIds), CLogger::LEVEL_TRACE);
		if (count($vndIds) > 0)
		{
			foreach ($vndIds as $vnd)
			{
				try
				{
					$success	 = false;
					$transaction = DBUtil::beginTransaction();
					$arr		 = Filter::getCodeById($vnd['vnd_id'], $type		 = 'vendor');
					if ($arr['success'] == 1)
					{
						$model			 = Vendors::model()->resetScope()->findByPk($vnd['vnd_id']);
						$model->vnd_code = $arr['code'];
						$model->scenario = 'updateCode';
						if ($model->update())
						{
							$success = true;
							if ($success == true)
							{
								DBUtil::commitTransaction($transaction);
								$updateData = $model->vnd_id . " - " . $model->vnd_code;
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
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
					Logger::create('ERRORS =====> : ' . "Exception :" . $e->getMessage() . " Errors :" . $errors, CLogger::LEVEL_ERROR);
				}
			}
		}
	}

	public function actionUpdateDriverScore()
	{
		$rows = VendorStats::model()->getDriverAppUseScore();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$errors = '';
				if ($row['vnd_id'] > 0)
				{
					$vndID	 = $row['vnd_id'];
					$model	 = VendorStats::model()->getbyVendorId($vndID);
					if (!$model)
					{
						$model				 = new VendorStats();
						$model->vrs_vnd_id	 = $vndID;
					}
					$model->vrs_use_drv_app = $row['score'];
					try
					{
						$transaction = DBUtil::beginTransaction();
						if ($model->save())
						{
							$statsRow						 = VendorStats::model()->getDriverAppUseLast10Score($model->vrs_vnd_id);
							$model->vrs_drv_app_last10_trps	 = $statsRow['score_last_days'];
							if ($model->save())
							{
								DBUtil::commitTransaction($transaction);
								echo "Vendor : " . $model->vrs_vnd_id . " Driver score : " . $model->vrs_use_drv_app . " Driver score for last 10 days : " . $model->vrs_drv_app_last10_trps . " \n";
							}
							else
							{
								$errors = "driver score last 10 days not saved.";
								throw new Exception($errors);
							}
						}
						else
						{
							$errors = "driver score not saved.";
							throw new Exception($errors);
						}
					}
					catch (Exception $ex)
					{
						DBUtil::rollbackTransaction($transaction);
						echo $errors . "\n";
					}
				}
			}
		}
	}

	public function actionUpdateAvgCabUsed()
	{
		$rows = VendorStats::model()->getAvgCabsByTrips();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$errors = '';
				if ($row['vnd_id'] > 0)
				{
					$vndID	 = $row['vnd_id'];
					$model	 = VendorStats::model()->getbyVendorId($vndID);
					if (!$model)
					{
						$model				 = new VendorStats();
						$model->vrs_vnd_id	 = $vndID;
					}
					$model->vrs_avg_cab_used = $row['avgTrips'];
					if ($model->save())
					{
						echo "Cab avg score updated :: " . $model->vrs_vnd_id . " - " . $model->vrs_avg_cab_used . "\n";
					}
				}
			}
		}
	}

	public function actionBroadcastNotification()
	{
		$image = "http://www.aaocab.com/images/2018/11/diwaliOffer.jpg";
		/* $sql  = 'SELECT DISTINCT vnd_id from vendors
		  INNER JOIN app_tokens ON vendors.vnd_id=app_tokens.apt_user_id AND app_tokens.apt_user_type=2
		  AND app_tokens.apt_logout IS NULL AND app_tokens.apt_last_login>DATE_SUB(NOW(), INTERVAL 10 day)
		  where vnd_active = 1
		  ORDER BY apt_last_login DESC'; */

		$sql	 = 'SELECT DISTINCT vnd_id from vendors
         INNER JOIN app_tokens ON vendors.vnd_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type=2 
		 AND app_tokens.apt_logout IS NULL AND app_tokens.apt_last_login>DATE_SUB(NOW(), INTERVAL 10 day)
         where vnd_active = 1 
		 ORDER BY apt_last_login DESC';
		$message = $image;
		//	 $message = "To enhance our Customer Service, OTP Compliance is being made mandatory.  From now onwards Non-compliance with OTP before starting the trip will attract a Penalty of Rs.250";
		$ids	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($ids as $id)
		{
			$payLoadData = ['EventCode' => Booking::CODE_BROADCAST_IMAGE];
			$success	 = AppTokens::model()->notifyVendor($id['vnd_id'], $payLoadData, $message, "Gozo Diwali Dhamaka");
			echo "\n Sent Image notification to Vendor Id : " . $id['vnd_id'] . " - " . $message;
		}
	}

	public function actionUpdateOutstanding()
	{
		$sql	 = "SELECT DISTINCT atd.adt_trans_ref_id 
				FROM account_trans_details atd 
				INNER JOIN account_transactions act ON act.act_id=atd.adt_trans_id AND act.act_active=1 AND act.act_status=1 
					AND atd.adt_active=1 AND atd.adt_status=1 AND atd.adt_ledger_id = 14 
				INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_active=1 AND atd1.adt_status=1 
					AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) 
				WHERE 1 AND act.act_date >= DATE_SUB(NOW(), INTERVAL 75 MINUTE)";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $res)
		{
			$vndId = $res['adt_trans_ref_id'];
			Vendors::model()->updateDetails($vndId);
			Logger::writeToConsole("VndId: " . $vndId);
		}
	}

	public function actionSendVendorAssignmentLink()
	{
		$sql = "SELECT DISTINCT vnd_id,eml_email_address,phn_phone_no,phn_phone_country_code
				FROM vendors
				INNER JOIN vendor_stats ON vrs_vnd_id=vnd_id
				INNER JOIN contact ON ctt_id = vnd_contact_id
				LEFT JOIN contact_email ON eml_contact_id = ctt_id AND eml_is_primary=1
				LEFT JOIN contact_phone ON phn_contact_id = ctt_id AND phn_is_primary=1
				WHERE 
				vnd_id IN (SELECT apt_entity_id FROM app_tokens WHERE apt_user_type=2 AND apt_last_login BETWEEN '2019-02-14' AND '2019-02-19 02:00:00' AND apt_entity_id IS NOT NULL)
				AND vnd_id NOT IN (SELECT apt_entity_id FROM app_tokens WHERE apt_user_type=2 AND apt_last_login > '2019-02-19 04:30:00' AND apt_entity_id IS NOT NULL) AND
				(vendor_stats.vrs_last_logged_in BETWEEN '2019-02-01' AND '2019-02-19 02:00:00' AND vnd_user_id IS NOT NULL) AND eml_email_address IS NOT NULL AND phn_phone_no IS NOT NULL AND vnd_active > 0";
		$res = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($res as $key => $val)
		{
			$emailModel	 = new emailWrapper();
			$emailModel->SendLink($val['vnd_id'], $val['eml_email_address']);
			$smsModel	 = new smsWrapper();
			$smsModel->sendLink($val['vnd_id'], $val['phn_phone_no'], $val['phn_phone_country_code']);
		}
	}

	public function actionUpdateCarAndDriverCount()
	{
		VendorStats::model()->updateCarTypeCount();
		VendorStats::model()->updateCountDrivers();
	}

	public function actionUpdateHomeZone()
	{
		$check = Filter::checkProcess("vendor updateHomeZone");
		if (!$check)
		{
			return;
		}
		VendorPref::updateHomeZone();
	}

	public function actionVerifyVendorStatus()
	{
		$vendors = Vendors::model()->getActiveVendor();
		foreach ($vendors as $key => $value)
		{
			$is_agreement	 = VendorStats::model()->statusCheckAgreement($value['vnd_id']);
			$is_document	 = VendorStats::model()->statusCheckDocument($value['vnd_id']);
			$is_car			 = VendorStats::model()->statusCheckVehicle($value['vnd_id']);
			$is_driver		 = VendorStats::model()->statusCheckDriver($value['vnd_id']);
			if ($is_agreement == 0 || $is_document == 0 || $is_car == 0 || $is_driver == 0)
			{
				$sql = "UPDATE vendors SET vnd_active = 3 WHERE vnd_id=" . $value['vnd_id'];
				$res = Yii::app()->db->createCommand($sql)->execute();
				if ($res > 0)
				{
					echo $value['vnd_id'] . " Updated Successfully";
					$desc = "Changing status Active to pending due to mandatory document missing";
					VendorsLog::model()->createLog($value['vnd_id'], $desc, UserInfo::getInstance(), VendorsLog::VENDOR_MODIFIED, false, false);
				}
				else
				{
					echo $value['vnd_id'] . " Updated Unsuccessfully";
				}
			}
		}
	}

	public function actionReadyApprovalScore()
	{
		$userInfo	 = UserInfo::getInstance();
		$rows		 = Document::getVendorDocListForR4A();
		Logger::create("Total vendors ready for approval : " . count($rows), CLogger::LEVEL_TRACE);
		if (count($rows) > 0)
		{
			$countDocs = 0;
			foreach ($rows as $row)
			{
				$arr = VendorStats::updateScoreR4A($row);
				$var = "Vendor : " . $row['vnd_id'] . " - total " . $arr['score'] . " are updated. - R4A Flag :" . $arr['r4a'];
				Logger::create($var, CLogger::LEVEL_INFO);
				echo $var . "\n";
			}
		}
	}

	public function actionSetGoldenTier()
	{
		$trips		 = 25;
		$rating		 = 4.0;
		$userInfo	 = UserInfo::getInstance();

		// set Golden tier if rating is > 4 after 25 trips
		$rows = VendorStats::getGoldenTierList($trips, $rating, 0);
		foreach ($rows as $row)
		{
			try
			{
				$returnSet = Vendors::updateVendorTire($row['vnd_id'], 1, $userInfo);
				if ($returnSet->getStatus())
				{
					$message = "Your vendor account is now upgraded to Gold Circle vendors group. Gold circle vendors get first priority on higher margin trips. To stay in Gold circle, focus on top quality service trip ratings and get customer ratings for all your trips. Great job! Welcome to Gold circle vendors group!";
					$title	 = "You qualify for Golden tier";
					Vendors::notificationForGoldenTier($row['vnd_id'], $message, $title);
				}
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole("Set: " . $ex->getMessage());
			}
		}

		// unset Golden tier if rating is <= 4 after 25 trips and vnd_rel_tier=1
		$rows = VendorStats::getGoldenTierList($trips, $rating, 1);
		foreach ($rows as $row)
		{
			try
			{
				$returnSet = Vendors::updateVendorTire($row['vnd_id'], 0, $userInfo);
				if ($returnSet->getStatus())
				{
					$message = "Your trip ratings have dropped. Your account is NOT IN the Gold Circle vendors group anymore.  To come back to the Gold circle, please request all customers to rate your trips and provide top quality service. Clean, well maintain car & family friendly driver is the way to get best ratings from customer";
					$title	 = "You have been demoted from Golden tier ";
					Vendors::notificationForGoldenTier($row['vnd_id'], $message, $title);
				}
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole("Unset: " . $ex->getMessage());
			}
		}
	}

	public function actionUpdateOrientation()
	{
		$rows = VendorPref::getListOrientationReq();
		Logger::create("Total Orientation set : " . count($rows), CLogger::LEVEL_TRACE);
		if (count($rows) > 0)
		{
			$ctr = 0;
			foreach ($rows as $row)
			{
				/* @var $modelVndPref VendorPref */
				$modelVndPref	 = new VendorPref();
				$return			 = $modelVndPref->setOrientationFlag($row['vnd_id'], $row['ctt_id']);
				if ($return['success'] == true)
				{
					$var = 'Vendor ID : ' . $row['vnd_id'] . " Message : " . $return['message'];
					Logger::create($var, CLogger::LEVEL_INFO);
					echo $var . "\n";
				}
				else
				{
					Logger::create("Errors Message : " . $return['message'], CLogger::LEVEL_ERROR);
				}
			}
		}
	}

	public function actionUpdateDormant()
	{
		VendorPref::model()->updateDormantFlag();
	}

	public function actionResetDormant()
	{
		VendorPref::model()->resetDormantFlag();
	}

	public function actionMarkCompleteTripReminder()
	{
		VendorStats::model()->updateTripPendingCount();
		VendorStats::model()->sendMarkCompleteReminder();
	}

	public function actionUpdateInventoryRequest()
	{
		$resetNMI = InventoryRequest::model()->updateInventoryRequest();
	}

	public function actionUpdateName()
	{
		$returnSet	 = new ReturnSet();
		$rows		 = Vendors::fetchApprovedList(true);
		foreach ($rows as $r)
		{
			$returnSet = Vendors::model()->updateVendorName($r['vnd_id']);
			if ($returnSet->isSuccess())
			{
				$var = "Vendor Name updated : " . $r['vnd_id'] . " - " . json_encode($returnSet->getData());
				Logger::create($var, CLogger::LEVEL_INFO);
			}
			else
			{
				$var = "Vendor Name not updated : " . json_encode($returnSet->getErrors());
				Logger::create($var, CLogger::LEVEL_ERROR);
			}
			echo $var . "\n";
		}
	}

	public function actionUpdateIdentity()
	{
		$sql			 = "SELECT COUNT(1) as cnt FROM `vendors` WHERE vendors.vnd_active>0 AND vendors.vnd_cat_type!=3";
		$totalCount		 = DBUtil::queryScalar($sql, DBUtil::SDB());
		$modeularLimit	 = ($totalCount % 500);
		$totalLimit		 = floor($totalCount / 500);
		$toLimit		 = 500;
		$fromLimit		 = 0;
		for ($i = 1; $i <= $totalLimit; $i++)
		{
			if ($i == 1)
			{
				$fromLimit	 = ($toLimit + 1);
				$limit		 = "LIMIT 0,$toLimit";
			}
			else if ($i == $totalLimit)
			{

				$limit		 = "LIMIT $fromLimit," . ($toLimit + $modeularLimit);
				$fromLimit	 = (($toLimit * $i) + 1);
			}
			else if ($i > 1)
			{
				$limit		 = "LIMIT $fromLimit,$toLimit";
				$fromLimit	 = (($toLimit * $i) + 1);
			}
			$sqlQuey[] = "SELECT vendors.vnd_id FROM `vendors` WHERE vendors.vnd_active>0 AND vendors.vnd_cat_type!=3 $limit";
		}

		foreach ($sqlQuey as $sql)
		{
			$varSql	 = "SQL executed : " . $sql;
			Logger::create($varSql, CLogger::LEVEL_INFO);
			echo "SQL executed : " . $sql . "\n";
			$rows	 = DBUtil::queryAll($sql, DBUtil::SDB());
			foreach ($rows as $r)
			{
				$returnSet	 = new ReturnSet();
				$returnSet	 = Vendors::model()->updateVendorCatType($r['vnd_id']);
				if ($returnSet->isSuccess())
				{
					$var = "VendorType updated : " . $r['vnd_id'] . " - " . json_encode($returnSet->getData());
					Logger::create($var, CLogger::LEVEL_INFO);
				}
				else
				{
					$var = "VendorType not updated : " . json_encode($returnSet->getErrors());
					Logger::create($var, CLogger::LEVEL_ERROR);
				}
				echo $var . "\n";
			}
		}
	}

	public function actionReleasePendingList()
	{
		$sql = "SELECT booking.bkg_id BookingId,booking_cab.bcb_vendor_amount bcbvendamt,booking_cab.bcb_id bcb_id FROM booking_cab
				INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id 
				WHERE bcb_pending_status = 1 AND bcb_trip_type = 1 AND booking.bkg_status IN(6,7) 
				AND booking_cab.bcb_trip_status = 5 AND booking.bkg_create_date >='2016-11-01'";

		$result = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($result as $value)
		{
			echo $value['BookingId'] . "-" . $value['bcbvendamt'] . "-" . $value['bcb_id'] . "\n";

			$bid		 = $value['BookingId'];
			$model		 = Booking::model()->findByPk($bid);
			$cabmodel	 = $model->getBookingCabModel();
			$cabmodel->setScenario('updatePendingStatus');
			$models		 = Booking::model()->getBookingModelsbyCab($cabmodel->bcb_id);
			$bkgIds		 = [];
			foreach ($models as $val)
			{
				$bkgIds[] = $val['bkg_id'];
			}

			//$userInfo	 = UserInfo::getInstance();
			$modelCab = new BookingCab();

			$modelCab->bcb_id			 = $value['bcb_id'];
			$modelCab->bcb_vendor_amount = $value['bcbvendamt'];
			$bcbModel					 = BookingCab::model()->findByPk($modelCab->bcb_id);
			$bcbModel->scenario			 = 'updateTripAmount';

			//$checkaccess = Yii::app()->user->checkAccess('changeVendorAmount');
			if (($modelCab->bcb_vendor_amount > $bcbModel->bcb_vendor_amount) || ($modelCab->bcb_vendor_amount <= $bcbModel->bcb_vendor_amount))
			{
				$transaction = DBUtil::beginTransaction();
				try
				{
					$bcbModel->updateTripAmount($modelCab->bcb_vendor_amount, $userInfo);
					if ($modelCab->bcb_vendor_amount != '')
					{
						$vndStatsModel	 = VendorStats::model()->getbyVendorId($bcbModel->bcb_vendor_id);
						$message		 = "Gozo has released payment of " . $bcbModel->bcb_vendor_amount . " to you today. Your withdrwable balance at time of release of payment was " . $vndStatsModel->vrs_withdrawable_balance . ". It normally takes between 2-24hours for you to receive the amount in your bank account";
						$payLoadData	 = ['vendorId' => $bcbModel->bcb_vendor_id, 'EventCode' => BookingLog::VENDOR_AMOUNT_RESET, 'tripId' => $bcbModel->bcb_id];
						$success		 = AppTokens::model()->notifyVendor($bcbModel->bcb_vendor_id, $payLoadData, $message, "Gozo releases payment to a vendor.");
					}
					$modelBookingCab					 = BookingCab::model()->findByPk($modelCab['bcb_id']);
					$modelBookingCab->setScenario('updatePendingStatus');
					$modelBookingCab->bcb_vendor_amount	 = $modelCab['bcb_vendor_amount'];
					$modelBookingCab->bcb_pending_status = 0;
					$success							 = $modelBookingCab->save();
					$lowestStatus						 = $modelBookingCab->getLowestBookingStatus();
					if ($modelBookingCab->bcbVendor && ($lowestStatus == 7 || $lowestStatus == 6))
					{

						$bkgamt	 = $model->bkgInvoice->bkg_total_amount;
						$amtdue	 = $bkgamt - $model->bkgInvoice->getTotalPayment();

						$model->bkgInvoice->bkg_due_amount	 = $amtdue;
						$vndamt								 = $modelBookingCab->bcb_vendor_amount;
						$gzamount							 = $model->bkgInvoice->bkg_gozo_amount;
						if ($gzamount == '')
						{
							$gzamount							 = $bkgamt - $vndamt;
							$model->bkgInvoice->bkg_gozo_amount	 = $gzamount;
						}
						$gzdue				 = $gzamount - $model->bkgInvoice->getAdvanceReceived();
						$vendorDue			 = $model->bkgInvoice->bkg_vendor_collected - $modelBookingCab->bcb_vendor_amount;
						//$userInfo			 = UserInfo::getInstance();
						$date				 = new DateTime($model->bkg_pickup_date);
						$duration			 = $model->bkg_trip_duration | 120;
						$date->add(new DateInterval('PT' . $duration . 'M'));
						$findmatchBooking	 = Booking::model()->getMatchBookingIdbyTripId($modelBookingCab->bcb_id);
						foreach ($findmatchBooking as $valBookingID)
						{
							if (AccountTransDetails::model()->revertVenTransOnEditAcc($modelBookingCab->bcb_id, $valBookingID['bkg_id'], Accounting::LI_TRIP, Accounting::LI_OPERATOR))
							{
								if ($valBookingID['bkg_vendor_collected'] > 0)
								{
									AccountTransactions::model()->AddVendorCollection($modelBookingCab->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $modelBookingCab->bcb_id, $valBookingID['bkg_id'], $modelBookingCab->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $modelBookingCab->bcb_trip_status);
								}
								AccountTransactions::model()->AddVendorPurchaseTrip($modelBookingCab->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $modelBookingCab->bcb_id, $valBookingID['bkg_id'], $modelBookingCab->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $modelBookingCab->bcb_trip_status);
							}
						}
						$model->bkgInvoice->scenario		 = 'vendor_collected_update';
						$model->bkgInvoice->bkg_gozo_amount	 = round($gzamount);
						$model->bkgInvoice->bkg_due_amount	 = $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getTotalPayment();

						$model->bkgInvoice->addCorporateCredit();
						$model->bkgInvoice->calculateConvenienceFee($model->bkgInvoice->bkg_convenience_charge);
						$model->bkgInvoice->calculateTotal();
						$model->bkgInvoice->calculateVendorAmount();
						// $model->bkg_account_flag = 1;
						$model->bkgInvoice->save();
					}
					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
				}
			}
		}
	}

#

	public function actionModifyStickyScore()
	{
		$sql = "SELECT vrs_vnd_id  FROM `vendor_stats` WHERE `vrs_sticky_score` IS NULL || `vrs_sticky_score` ='NAN' ORDER BY vrs_last_bkg_cmpleted DESC";

		$result = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($result as $res)
		{
			$vendorId = $res['vrs_vnd_id'];

			#$vendorId = 35576;
			$sql1 = "SELECT DATE_ADD(bkg_pickup_date, INTERVAL bkg_trip_duration MINUTE) as lastDate  FROM `booking_cab`
					INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id
					WHERE `bcb_vendor_id` = " . $vendorId . " AND bkg_status IN (6,7)
					ORDER BY lastDate DESC  LIMIT 0,1";

			$startDate = DBUtil::queryScalar($sql1);

			if ($startDate != "")
			{
				VendorStats::updateStickyScore($vendorId, $startDate);
			}
		}
	}

	public function actionUpdateTier()
	{

		$sccData = ServiceClass::getAll();
		$result	 = Vendors::model()->updateIsAllowedTier();
		foreach ($sccData as $scc)
		{
			Vendors::updateTier($scc['scc_odometer'], $scc['scc_id'], $scc['scc_model_year']);
			echo "=====Completed Class: {$scc['scc_id']}=========<br>";
		}
	}

	public function actionDriverAppUsed()
	{
		$date	 = date('Y-m-d 00:00:00');
		#$date =date('2020-02-24 00:00:00');
		$sql	 = "SELECT vrs_vnd_id FROM `vendor_stats` WHERE vrs_last_bkg_cmpleted > DATE_SUB('" . $date . "', INTERVAL 1 DAY)";
		$result	 = DBUtil::command($sql)->queryAll();
		foreach ($result as $res)
		{
			$vendorId = $res['vrs_vnd_id'];

			VendorStats::model()->driverAppused($vendorId);
		}
	}

	//* vendor penalty rating modified based on penalty calculation*/
	public function actionPenaltyRating()
	{

		//VendorStats::model()->penaltyRating();
		VendorStats::model()->updatePenaltyCount();
	}

	//* vendor totalMargin calculation one time using function*/
	public function actionDefaultMargin()
	{
		VendorStats::model()->calculateMargin();
	}

	public function actionDayWiseMargin()
	{
		//current booking vendor
		VendorStats::model()->calculatePerdayMargin();
	}

	public function actionCalcBidWinRate()
	{
		VendorStats::model()->calculateBidWinRate();
	}

	public function actionCalcOtpVerification()
	{

		$sql	 = "SELECT vrs_vnd_id FROM `vendor_stats` WHERE vrs_last_bkg_cmpleted > DATE_SUB(now(), INTERVAL 1 DAY)";
		$result	 = DBUtil::command($sql)->queryAll();
		foreach ($result as $res)
		{
			$vendorId = $res['vrs_vnd_id'];

			VendorStats::model()->calcOtpVerification($vendorId);
		}
	}

	public function actionPtnrDependency($interval = 14, $vndId = null)
	{
		$params	 = ["interval" => $interval];
		$cond	 = "";
		if ($vndId != null)
		{
			$cond			 = " AND bvr_vendor_id=:vndId";
			$params["vndId"] = $vndId;
		}

		$sql	 = "SELECT distinct(bvr_vendor_id) FROM `booking_vendor_request` WHERE  bvr_assigned_at > DATE_SUB(now(), INTERVAL :interval DAY) AND bvr_assigned=1 $cond";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		foreach ($result as $res)
		{
			try
			{
				$vendorId = $res['bvr_vendor_id'];
				VendorStats::updateDependency($vendorId);
				VendorStats::updateTripCount($vendorId);
			}
			catch (Exception $exc)
			{
				Logger::error($exc);
			}
		}
	}

	/**
	 * calculate vendor matrix within 90 days
	 */
	public function actionModifyVendorMatrix()//cron weekly 
	{

		$sql	 = "SELECT distinct(bvr_vendor_id) FROM `booking_vendor_request` WHERE bvr_assigned_at > DATE_SUB(now(), INTERVAL 14 DAY)";
		$result	 = DBUtil::query($sql, DBUtil::SDB());

		foreach ($result as $res)
		{
			$vendorId = $res['bvr_vendor_id'];

			VendorStats::updateDirectAcceptCount($vendorId);
			VendorStats::updateBidAcceptCount($vendorId);
			VendorStats::updateManualAcceptCount($vendorId);

			VendorStats::totalAcceptCount($vendorId);
			VendorStats::updateUnassignCountStepWise($vendorId);
		}
	}

	public function actionSetEffectiveCreditLimit($vnd = null)
	{
		Logger::writeToConsole("COMMAND VENDOR SetEffectiveCreditLimit STARTED");
		$data		 = Vendors::getCollectionList(0, 0, $vnd);
		$countData	 = $data->getRowCount();

		Logger::writeToConsole("Count: " . $countData);

		$vendorGraceDays = Yii::app()->params['vendorGraceDays'];

		$str = "Total Vendor Count: " . $countData;
		Logger::warning("CRON SetEffectiveCreditLimit STARTED: {$str}", true);

		$i = 0;
		foreach ($data as $d)
		{
			Logger::trace("Data - " . json_encode($d));
			$transaction = DBUtil::beginTransaction();
			try
			{
				$model					 = Vendors::model()->resetScope()->findByPk($d['vnd_id']);
				$modelVendStats			 = $model->vendorStats;
				$modelVendPref			 = $model->vendorPrefs;
				$pastduedays			 = $modelVendStats->vrs_effective_overdue_days;
				$old_credit_limit		 = $modelVendStats->vrs_effective_credit_limit;
				$defaultMinCreditLimit	 = Config::get('vendor.defaultMinCreditLimit');
				$actualBalance			 = $d['totTrans'];
				$modelVendStats->setLockedAmount();

				if ($actualBalance <= 0)
				{
					$modelVendStats->vrs_effective_credit_limit	 = ($d['creditLimit'] <= 0) ? $defaultMinCreditLimit : $d['creditLimit'];
					$modelVendStats->vrs_effective_overdue_days	 = 0;
					//  as a safety measure. the vendors credit limit will be increased by the system if his withdrawable amount is > than his security deposit but there is a cap of 25K. Even if Gozo owes him 1L, we will only increase his credit lmit upto a max of 25K in this case.
					$modelVendStats->vrs_credit_limit			 = max($d['vrs_security_amount'], min(($actualBalance * -1), 25000));
				}
				else
				{
					$pastduedays++;
					$modelVendStats->vrs_effective_overdue_days	 = $pastduedays;
					$securityLimit								 = round($d['vrs_security_amount'] * 0.75);

					if ($pastduedays <= $vendorGraceDays)
					{
						$defaultLimit								 = max(max($defaultMinCreditLimit, $d['creditLimit'], $securityLimit) - $actualBalance, 0);
						$modelVendStats->vrs_effective_credit_limit	 = $defaultLimit;
					}
					else
					{
						$defaultLimit = max($defaultMinCreditLimit, $securityLimit) - $actualBalance;

						$overduedays	 = $pastduedays - $vendorGraceDays;
						$effectiveLimit	 = max($d['creditLimit'] - ($overduedays * 0.5 * $actualBalance), 0);

						$modelVendStats->vrs_effective_credit_limit	 = max($defaultLimit, $effectiveLimit);
						$modelVendStats->vrs_credit_limit			 = max($d['creditLimit'], $d['vrs_security_amount'], 0);

						/// Send them reminder to pay while the account is stil unfreezed

						$amtTxt		 = '₹' . $d['totTrans'] . 'due. Last payment received ' . $pastduedays . ' days ago.';
						$message	 = "Pay to your Gozo account. $amtTxt ";
						$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
						$success	 = AppTokens::model()->notifyVendor($d['vnd_id'], $payLoadData, $message, "PAYMENT DUE");
					}
				}

				if ($modelVendStats->save())
				{
					$i++;
					if ($old_credit_limit != $modelVendStats->vrs_effective_credit_limit)
					{
						$credit_desc = 'Effective credit limit (Old Value : ' . $old_credit_limit . ', New Value : ' . $modelVendStats->vrs_effective_credit_limit . ')';
						VendorsLog::model()->createLog($model->vnd_id, $credit_desc, UserInfo::getInstance(), VendorsLog::EFFECTIVE_CREDIT_LIMIT_UNSET, false, false);
					}
					if ($d['creditLimit'] != $modelVendStats->vrs_credit_limit)
					{
						$credit_desc = 'Credit limit (Old Value : ' . $d['creditLimit'] . ', New Value : ' . $modelVendStats->vrs_credit_limit . ')';
						VendorsLog::model()->createLog($model->vnd_id, $credit_desc, UserInfo::getInstance(), VendorsLog::CREDIT_LIMIT_UNSET, false, false);
					}
				}
				Logger::trace(json_encode($modelVendStats->getAttributes()));
				Logger::trace(json_encode($modelVendPref->getAttributes()));
				#if vendor security and total transaction 0 then no freeze for vendor according to discution with AK sir
				if ($modelVendStats->vrs_effective_credit_limit < $actualBalance && $modelVendPref->vnp_credit_limit_freeze == 0)
				{
					$modelVendPref->vnp_credit_limit_freeze = 1;
					if ($modelVendPref->save())
					{
						$success = Vendors::model()->updateFreeze($d['vnd_id']);
						if ($success)
						{
							// vendor log for vendor freeze for credit limit
							$event_id	 = VendorsLog::VENDOR_FREEZE;
							$desc		 = "Vendor freezed. (Credit used > Eff Cr. limit)";
							VendorsLog::model()->createLog($model->vnd_id, $desc, UserInfo::getInstance(), $event_id, false, false);

							// Send Notification in case if we are freeazing his account
							$amtTxt		 = '₹' . $d['totTrans'];
							$message	 = "Payment overdue! Pay $amtTxt to Gozo account urgently.";
							$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
							$success	 = AppTokens::model()->notifyVendor($d['vnd_id'], $payLoadData, $message, "OVERDUE PAYMENT");
						}
					}
				}
				else if ($modelVendStats->vrs_effective_credit_limit > $actualBalance && $model->vnd_active != 2 && $modelVendPref->vnp_credit_limit_freeze == 1)
				{
					$modelVendPref->vnp_credit_limit_freeze = 0;
					if ($modelVendPref->save())
					{
						$success = Vendors::model()->updateFreeze($d['vnd_id']);
						if ($success)
						{
							$event_id	 = VendorsLog::VENDOR_UNFREEZE;
							$desc		 = "Vendor Unfreezed. (Credit use is now below Eff. Cr limit)";
							Logger::create($desc, CLogger::LEVEL_INFO);
							VendorsLog::model()->createLog($model->vnd_id, $desc, UserInfo::getInstance(), $event_id, false, false);

							$message	 = "Your account has been unfreezed. Your effective credit limit is ₹{$modelVendStats->vrs_effective_credit_limit}";
							$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
							$success	 = AppTokens::model()->notifyVendor($d['vnd_id'], $payLoadData, $message, "ACCOUNT UNFREEZED");
						}
					}
				}
				DBUtil::commitTransaction($transaction);

				Logger::writeToConsole("DONE VND ID: " . $d['vnd_id']);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($e);
				Logger::writeToConsole("VND ID: " . $d['vnd_id'] . ", ERROR: " . $e->getMessage());
			}
		}
		$str .= "Success Count: " . $i;

		Logger::warning("CRON SetEffectiveCreditLimit COMPLETED: {$str}", true);
	}

	public function actionSetLouRequiredFlag()
	{
		$vendors = Vendors::model()->getAll();
		foreach ($vendors as $vndIds)
		{
			$vndId		 = $vndIds['vnd_id'];
			$vvhcData	 = VendorVehicle::getcabForLouV1($vndId);
			foreach ($vvhcData as $vvhc)
			{
				$vvhcId						 = $vvhc['vvhc_id'];
				$model						 = VendorVehicle::model()->findByPk($vvhcId);
				$model->vvhc_is_lou_required = 1;
				$model->save();
			}
		}
	}

	public function actionUpdateDlMismatchedVendorsData()
	{
		$vendors = Contact::updateDlMismatchedVendorsData();

		echo "success";
	}

	public function actionUpdatePANMismatchedVendorsData()
	{
		$vendors = Contact::updatePANMismatchedVendorsData();

		echo "success";
	}

	/**
	 * This function used for stopping vendor amount to release based on Upcoming pickup date <=24 hrs
	 * pickupdate in the interval of previous 24 hours and next 24 hours
	 * @throws Exception invalid data
	 * @deprecated since 04-03-2021, Check actionStopVendorPaymentForOtherB2BPartners
	 */
	public function actionStopPaymentForOtherB2BPartners()
	{
		$sql	 = "SELECT bkg_id,
                    bcb_id
					FROM   booking
					INNER JOIN booking_cab
					ON booking_cab.bcb_id = booking.bkg_bcb_id
					WHERE  bkg_status IN ( 3, 5, 6)
                    AND  bkg_agent_id NOT IN (18190, 450, 1249, 3936, 25723) AND bkg_agent_id IS NOT NULL
                    AND bkg_pickup_date BETWEEN DATE_SUB(NOW(), INTERVAL 24 hour) AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
                    AND bkg_active = 1
                    AND bcb_lock_vendor_payment = 0";
		$rows	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $data)
		{
			if ($data['bcb_id'])
			{
				BookingCab::stopVendorPayment($data['bcb_id']);
			}
		}
	}

	public function actionSendCabDriverNotification()
	{
		$sql	 = "SELECT * FROM booking
				INNER JOIN booking_cab bcb ON booking.bkg_bcb_id = bcb.bcb_id
				INNER JOIN booking_trail ON booking_trail.btr_bkg_id = booking.bkg_id
				WHERE booking.bkg_status = 3 AND booking.bkg_pickup_date > NOW()";
		$rows	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $row)
		{
			$workingHours = Filter::CalcWorkingHour($row['bkg_assigned_at'], $row['bkg_pickup_date']);
			if ($workingHours > 12)
			{
				$timeLeft = "3 hrs";
			}
			else if ($workingHour > 8 && $workingHours <= 12)
			{
				$timeLeft = "2 hrs";
			}
			else if ($workingHour > 4 && $workingHours <= 8)
			{
				$timeLeft = "1 hr";
			}
			else if ($workingHour <= 4)
			{
				$timeLeft = "30 minutes";
			}
			$payLoadData = ['vendorId' => $row['bcb_vendor_id'], 'EventCode' => Booking::CODE_DRIVER_PENDING];
			$success	 = AppTokens::model()->notifyVendor($row['bcb_vendor_id'], $payLoadData, "Driver and Cab assignment pending. If not allocated within " . $timeLeft . " - we will unassign this trip", "TRIP ID:" . $row['bcb_id'] . ". Allocate Car & Driver NOW");

			$contactId	 = ContactProfile::getByEntityId($row['bcb_vendor_id'], UserInfo::TYPE_VENDOR);
			$ext		 = '91';
			$number		 = ContactPhone::getContactPhoneById($contactId);
			$contactNo	 = $ext . $number;
			$smsCount	 = SmsLog::getCountVendorAssignedSms($row['bkg_booking_id'], $contactNo, SmsLog::SMS_VENDOR_ASSIGNED);

			if ($smsCount == 0)
			{
				smsWrapper::vendorAssignment($ext, $number, $row['bkg_booking_id'], "Driver & Cab assignment pending for TRIP ID: " . $row['bcb_id'] . " If not allocated within " . $timeLeft . " - we will unassign this trip - aaocab", $row['bcb_vendor_id']);
			}
		}
	}

	public function actionStopVendorPaymentForOtherB2BPartners()
	{
		$sql	 = "SELECT DISTINCT adt_trans_ref_id
				FROM account_trans_details where
				adt_status = 1 AND adt_active = 1 AND adt_ledger_id = 15 AND  adt_modified >= DATE_SUB(NOW(), INTERVAL 90 DAY) 
				AND adt_modified >= '2020-04-01 00:00:00' AND adt_trans_ref_id NOT IN (18190,450,1249,3936,25723,34928)";
		$values	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($values as $val)
		{
			$id			 = $val['adt_trans_ref_id'];
			$getBalance	 = PartnerStats::getBalance($id);
			$agentModel	 = Agents::model()->findByPk($id);
			$resultRow	 = PartnerSettings::getValueById($id);
			If ($resultRow['pts_is_stop_vendor_payment'] == 1)
			{
				$agentModel->agt_effective_credit_limit = $agentModel->agt_credit_limit;
			}
			$partnerBalance	 = $getBalance['pts_ledger_balance'] - $getBalance['pts_wallet_balance'];
			$balance		 = $partnerBalance - $agentModel->agt_effective_credit_limit;

			$bkg_id		 = $pickupDate	 = null;
			$result		 = BookingCab::stopVendorPaymentForPartnerBooking($id, $balance);
			if ($result)
			{
				$bkg_id		 = $result['bkgId'];
				$pickupDate	 = $result['pickupDate'];
			}

			BookingCab::releasePartnerVendorPayments($id, $bkg_id, $pickupDate);
		}
	}

	/**
	 * create function to send reminder to the vendor
	 */
	public function actionRmndVndCollectRating()
	{
		$notificationFlag	 = 0;
		$sql				 = "SELECT distinct(bvr_vendor_id) FROM `booking_vendor_request` WHERE  bvr_assigned_at > DATE_SUB(now(), INTERVAL 30 DAY)";
		$result				 = DBUtil::query($sql, DBUtil::SDB());

		foreach ($result as $res)
		{
			$vendorId			 = $res['bvr_vendor_id'];
			$days				 = 30;
			$oneMnthBookingCount = Vendors::getVendorBookingCount($vendorId, $days);
			$oneMnthRatingCount	 = Ratings::getVendorRatingCount($vendorId, $days);
			if ($oneMnthBookingCount < 1)
			{
				goto next;
			}
			$bkgFiftyPercent = $oneMnthBookingCount / 2;
			$noRatingBooking = $oneMnthBookingCount - $oneMnthRatingCount;
			if ($bkgFiftyPercent > $oneMnthRatingCount)
			{
				$notificationFlag	 = 1;
				$msg				 = "You have completed " . $oneMnthBookingCount . "bookings in last 30 days but not received any rating for " . $noRatingBooking . " bookings.";
			}

			next:
			if ($notificationFlag < 1)
			{
				$lifeTimeBookingCount	 = Vendors::getVendorBookingCount($vendorId);
				$lifeTimeRatingCount	 = Ratings::getVendorRatingCount($vendorId);
				$lifeTimebkgFiftyPercent = $lifeTimeBookingCount / 2;
				$lifeTimenoRatingBooking = $lifeTimeBookingCount - $lifeTimeRatingCount;
				if ($lifeTimebkgFiftyPercent > $lifeTimeRatingCount)
				{
					$notificationFlag	 = 1;
					$msg				 = "You have completed " . $lifeTimeBookingCount . "bookings but" . $lifeTimenoRatingBooking . " bookings got no ratings.";
				}
			}
			if ($notificationFlag == 1)
			{
				$type	 = 15;
				$day	 = 10;

				$checkNotify = NotificationLog::getNotificationIntStatus($vendorId, $day, $type);
				if ($checkNotify < 1)
				{
					$payLoadData = ['vendorId' => $vendorId, 'EventCode' => Ratings::NoRating];
					$message	 = $msg . "
									No trip ratings is the same as bad ratings. 
									You need more 5 star ratings. Please request customers give you a 5star rating on every future trip.";
					$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Remind vendors to collect ratings from customers.");
				}
			}
		}
	}

	/*
	 * Set vendor coins of vendors
	 */

	public function actionSetVendorCoins()
	{
		VendorCoins::updateCoinDetails();
	}

	/**
	 * 
	 */
	public function actionBlockListing($interval = 14)
	{
		$params	 = ["interval" => $interval];
		$sql	 = "SELECT distinct(bvr_vendor_id) FROM `booking_vendor_request` WHERE  bvr_assigned_at > DATE_SUB(now(), INTERVAL :interval DAY) AND bvr_assigned=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		foreach ($result as $res)
		{
			$vendorId = $res['bvr_vendor_id'];
			VendorStats::blockListing($vendorId);
		}
	}

	// if no security deposit they cannot do credit bookings but let them do work
	public function actionFreezeVendorForSecurityDeposit()
	{
		$sql	 = "SELECT GROUP_CONCAT(vnd.vnd_id) as vndIds FROM vendors vnd 
				INNER JOIN vendor_pref vnp ON vnd.vnd_id = vnp.vnp_vnd_id 
				INNER JOIN vendor_stats vrs ON vnd.vnd_id = vrs.vrs_vnd_id 
				WHERE vnp.vnp_credit_limit_freeze = 0 AND vnd.vnd_active = 1 
				AND vrs.vrs_security_amount <= 0 AND vnd_create_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
		$vndIds	 = DBUtil::queryScalar($sql, DBUtil::SDB());
		if ($vndIds)
		{
			$sqlUpd	 = "UPDATE vendor_pref SET vnp_is_freeze = 1, vnp_cod_freeze = 1 WHERE vnp_vnd_id IN ($vndIds)";
			$result	 = DBUtil::execute($sqlUpd);
			if ($result)
			{
				$vendorIds = explode(',', $vndIds);
				foreach ($vendorIds as $vendorId)
				{
					$desc = "Vendor COD freezed. No Security deposit.";
					VendorsLog::model()->createLog($vendorId, $desc, UserInfo::getInstance(), VendorsLog::VENDOR_FREEZE, false, false);

					echo "\r\nVendor COD Freezed: " . $vendorId;
				}
			}
		}
	}

	/**
	 * This function is used get all booking completed count along with rating if he/she has got within last seven days for each vendor
	 */
	public function actionlastWeekVedorCompletedTrip()
	{
		$result = BookingCab::getlastWeekVedorCompletedTrip();
		foreach ($result as $row)
		{
			try
			{
				$ratingCount		 = $row['ratingCount'];
				$bookingCompleted	 = $row['tripCount'];
				$message			 = "Last week you have received ratings for only $ratingCount/$bookingCompleted completed bookings. Remind customers to give to 5 star ratings. Partners with high ratings move to our Gold Circle partner group. Gold circle partners receive high profit bookings & other benefits.";
				$title				 = "Get more reviews & improve your ratings";
				AppTokens::model()->notifyVendor($row['vnd_id'], ['EventCode' => Booking::CODE_VENDOR_TIER], $message, $title);
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole("Set: " . $ex->getMessage());
			}
		}
	}

	/**
	 * Function used to send notification for snooze vendor
	 * 
	 */
	public function actionNotifyGnowSnoozeVnd()
	{
		$bookingList = BookingVendorRequest::listSnoozeBooking();
		foreach ($bookingList as $booking)
		{

			$model = Booking::model()->findByPk($booking['bvr_booking_id']);

			$notify = new Stub\common\Notification();

			$notify->setGNowNotify($model);

			$payLoadData = json_decode(json_encode($notify->payload), true);

			$message						 = $notify->message;
			$cabType						 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
			$vndId							 = $booking['bvr_vendor_id'];
			$title							 = "$cabType required urgent";
			$userInfo						 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
			$desc							 = "Vendors notified for Gozo now:";
			BookingCab::gnowNotifyVendor($vndId, $payLoadData, $message, $title);
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::VENDOR_NOTIFIED_FOR_GOZONOW_BOOKING, false);
			// update snooze data for booking vendor request
			$bvrModel						 = BookingVendorRequest::model()->findByPk($booking['bvr_id']);
			$bvrModel->bvr_notification_sent = 1;
			$bvrModel->save();
		}
	}

	public function actionWeeklyGozoNowNotifyVendor()
	{
		$result = VendorPref::getGozoNowVendorList();
		foreach ($result as $row)
		{
			try
			{
				$vndId	 = $row['vnp_vnd_id'];
				$message = "Turn on Gozo NOW - you can make more ₹₹ for all Local & Outstation bookings with Gozo NOW. Open Gozo partner app, go to your profile page and turn on the GozoNow toggle on top right of that page. Learn more at https://aaocab.com/gozonow";
				$title	 = "Turn on Gozo NOW on your vendor app";
				AppTokens::model()->notifyVendor($vndId, ['EventCode' => BookingLog::ACTIVATE_GOZO_NOW], $message, $title);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateLastAcceptedBidDatetime()
	{
		$sql	 = "SELECT bvr_vendor_id,MAX(bvr_created_at) lastBidDate
				FROM `booking_vendor_request` 
				WHERE bvr_created_at > DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND bvr_accepted = 1
				GROUP by bvr_vendor_id";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			try
			{
				if ($row['bvr_vendor_id'] > 0 && ($row['lastBidDate'] != NULL || $row['lastBidDate'] != ''))
				{
					$vnd_id		 = $row['bvr_vendor_id'];
					$lastBidDate = $row['lastBidDate'];
					$query		 = " UPDATE  vendor_stats SET vrs_last_bid_datetime = '$lastBidDate' WHERE vrs_vnd_id = $vnd_id";
					DBUtil::execute($query);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	/**
	 * function used to enable gnow status after 7 days of gnow status disable
	 */
	public function actionModifyGnowStatus()
	{
		$sql	 = "SELECT vnp_vnd_id FROM vendor_pref 
				INNER JOIN vendors ON vendors.vnd_id =vendor_pref.vnp_vnd_id 
				WHERE vnp_gnow_status =0 
                AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) >=vnp_gnow_modify_time;";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			if ($row['vnp_vnd_id'] > 0)
			{
				$vndId	 = $row['vnp_vnd_id'];
				$params	 = ['vndId' => $vndId];
				$query	 = "UPDATE  vendor_pref SET vnp_gnow_status = 1, vnp_snooze_time =null WHERE vnp_vnd_id = :vndId";
				DBUtil::execute($query, $params);
			}
		}
	}

	public function actionGozoNowNotificationStats()
	{
		$records = VendorStats::gozoNowNotificationStats();
		foreach ($records as $row)
		{
			VendorStats::updateGozoNowNotificationStats($row);
		}
	}

	public function actionUpdateVendorToDCO($vndId = 0)
	{
		try
		{
			$results = Vendors::updateVendorToDCO($vndId);
			echo "Vendor To DCO: " . $results;
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}

	public function actionUpdateDCOToVendor($vndId = 0)
	{
		try
		{
			$results = Vendors::updateDCOToVendor($vndId);
			echo "DCO To Vendor: " . $results;
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
		}
	}
}
