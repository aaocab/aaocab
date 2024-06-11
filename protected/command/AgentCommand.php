<?php

class AgentCommand extends BaseCommand
{

	public function actionPushApiCall()
	{
		$rows = PartnerApiDetails::model()->resendApiPushData();
		foreach ($rows as $key => $value)
		{
			$bkgId		 = $value['pat_booking_id'];
			$typeAction	 = $value['pat_type'];
			$patId		 = $value['pat_id'];
			$model		 = Booking::model()->findByPk($bkgId);
			AgentMessages::model()->pushApiCall($model, $typeAction, $patId);
		}
	}

	public function actionRePushApiCall()
	{
		$rows = AgentApiTracking::getPendingApiPushData();
		foreach ($rows as $value)
		{
			$bkgId		 = $value['booking_id'];
			$typeAction	 = $value['atype'];
			$patId		 = $value['at_id'];
			$model		 = Booking::model()->findByPk($bkgId);
			AgentMessages::model()->pushApiCall($model, $typeAction, $patId);
		}
	}

	public function actionPushMissingCabDriverDataApiCall()
	{
		$rows = AgentApiTracking::getCabDriverUpdateData();
		foreach ($rows as $value)
		{
			$bkgid	 = $value['bkg_id'];
			$success = BookingCab::model()->pushPartnerCabDriver($bkgid);
			$msg	 = ($success) ? "Booking id : $bkgid   successfully updated" : "Booking id : $bkgid  not  updated";
			echo $msg;
		}
	}

	Public function actionUpdateBookingPrefForEMT()
	{
		$sql		 = "SELECT bkg_id, bkg_agent_id FROM `booking` WHERE `bkg_agent_id` = 30228";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			$bkgId		 = $data['bkg_id'];
			$sql		 = "SELECT bpr_bkg_id , bpr_id FROM booking_pref WHERE bpr_bkg_id  = $bkgId AND bkg_send_sms= 0";
			$recordSet	 = DBUtil::queryAll($sql, DBUtil::MDB());
			if (count($recordSet) > 0)
			{
				$prefModel					 = BookingPref::model()->findByPk($recordSet[0]['bpr_id']);
				$prefModel->bkg_send_sms	 = 1;
				$prefModel->bkg_send_email	 = 1;
				$prefModel->save();
			}
		}
	}

	public function actionUpdateBookingMessages()
	{
		$sql		 = "SELECT bkg_id, bkg_agent_id FROM `booking` WHERE `bkg_agent_id` = 30228";
		$dataList	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($dataList as $data)
		{
			$bkgId		 = $data['bkg_id'];
			$sql		 = "SELECT * FROM booking_messages WHERE bkg_booking_id  = $bkgId";
			$recordSet	 = DBUtil::queryAll($sql, DBUtil::MDB());
			if (count($recordSet) == 0)
			{
				BookingMessages::model()->setDefaultAgentNotificationForBooking($data['bkg_agent_id'], $data['bkg_id']);
			}
		}
	}

	public function actionDataTransfer()
	{

		$range_sql	 = "SELECT COUNT(*) FROM `agent_api_tracking1`";
		$range		 = DBUtil::queryScalar($range_sql);
		for ($i = 0; $i < $range; $i++)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{


				echo "start";
//			$j = $i + 1;
//			if (($range - $i) > 1000)
//			{
//				$i = $i + 1000;
//			}
//			else
//			{
//				$i = $range - $i;
//			}
				$sql1	 = "SELECT aat_id FROM `agent_api_tracking1` ORDER BY aat_id DESC LIMIT 0,1";
				$row1	 = DBUtil::queryScalar($sql1);

//			$sql	 = "INSERT INTO `agent_api_details`(`aad_aat_id`, `aad_request`, `aad_response`) SELECT aat_id, aat_request, aat_response  FROM `agent_api_tracking1` LIMIT $j,$i";
//			$row	 = Yii::app()->db->createCommand($sql)->execute();
//			$sql1	 = "INSERT INTO `agent_api_tracking`(`aat_id`, `aat_agent_id`, `aat_temp_id`,`aat_type`,`aat_from_city`,`aat_to_city`,`aat_booking_id`,`aat_pickup_date`,`aat_from_mmt_code`,`aat_to_mmt_code`,`aat_booking_type`,`aat_error_type`,`aat_request_time`,`aat_ip_address`,`aat_created_at`) SELECT aat_id, aat_agent_id, aat_temp_id,aat_type,aat_from_city,aat_to_city,aat_booking_id,aat_pickup_date,aat_from_mmt_code,aat_to_mmt_code,aat_booking_type,aat_error_type,aat_request_time,aat_ip_address,aat_created_at  FROM `agent_api_tracking1`  WHERE aat_id NOT IN (SELECT aat_id FROM `agent_api_tracking`) ORDER BY aat_id ASC LIMIT $j,$i";
//			$row1	 = Yii::app()->db->createCommand($sql1)->execute();
//			
//			}
				echo "==" . $row1 . "==";
				if ($row1 > 0)
				{
					$sql2	 = "INSERT INTO `agent_api_details`(`aad_aat_id`, `aad_request`, `aad_response`) SELECT aat_id, aat_request, aat_response  FROM `agent_api_tracking1` WHERE aat_id = $row1";
					$cdb	 = Yii::app()->db->createCommand($sql2);
					$row2	 = $cdb->execute();
					if ($row2 == 1)
					{
						$sql3	 = "INSERT INTO `agent_api_tracking`(`aat_id`, `aat_agent_id`, `aat_temp_id`,`aat_type`,`aat_from_city`,`aat_to_city`,`aat_booking_id`,`aat_pickup_date`,`aat_from_mmt_code`,`aat_to_mmt_code`,`aat_booking_type`,`aat_error_type`,`aat_request_time`,`aat_ip_address`,`aat_created_at`) SELECT aat_id, aat_agent_id, aat_temp_id,aat_type,aat_from_city,aat_to_city,aat_booking_id,aat_pickup_date,aat_from_mmt_code,aat_to_mmt_code,aat_booking_type,aat_error_type,aat_request_time,aat_ip_address,aat_created_at  FROM `agent_api_tracking1` WHERE aat_id= $row1";
						$cdb1	 = Yii::app()->db->createCommand($sql3);
						$row3	 = $cdb1->execute();
						if ($row3 == 1)
						{
							$sql4	 = "DELETE FROM `agent_api_tracking1` WHERE aat_id=$row1";
							$cdb2	 = Yii::app()->db->createCommand($sql4);
							$row4	 = $cdb2->execute();
							if ($row4 != 1)
							{
								throw new Exception("error");
							}
						}
						else
						{
							throw new Exception("error");
						}
					}
					else
					{
						throw new Exception("error");
					}
				}
				else
				{
					throw new Exception("error");
				}


				DBUtil::commitTransaction($transaction);

				echo "===" . $i . "====";
			}
			catch (Exception $e)
			{
				echo "RollBack Id:-" . $row1;
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionUpdateCode()
	{
		$sql	 = "SELECT agents.agt_id FROM `agents` WHERE (agents.agt_code IS NULL OR agents.agt_code='') AND agents.agt_active=1";
		$agtIds	 = DBUtil::queryAll($sql);

		Logger::trace("Total Agents : " . count($agtIds));
		if (count($agtIds) > 0)
		{
			foreach ($agtIds as $agt)
			{
				try
				{
					$success	 = false;
					$transaction = DBUtil::beginTransaction();
					$arr		 = Filter::getCodeById($agt['agt_id'], $type		 = 'agent');
					if ($arr['success'] == 1)
					{
						$model			 = Agents::model()->findByPk($agt['agt_id']);
						$model->agt_code = $arr['code'];
						$model->scenario = 'updateCode';
						if ($model->save())
						{
							$success = true;
							if ($success == true)
							{
								DBUtil::commitTransaction($transaction);
								$updateData = $model->agt_id . " - " . $model->agt_code;
								echo $updateData . " Updated\n";
								Logger::trace('CODE DATA ===========>: ' . $updateData);
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

	public function actionMmtdriverupdate()
	{
		$sql = "SELECT aat.*,aad.*,bcb_cab_number,bcb_driver_name,bcb_driver_phone,
				bkg_id, bkg_agent_ref_code, bkg_trip_otp,bkg_agent_id,bkg_country_code
				from agent_api_tracking aat
				 JOIN agent_api_details aad ON aad_aat_id = aat_id
				 JOIN booking bkg ON bkg_id = aat_booking_id
				JOIN booking_cab bcb ON bkg_bcb_id = bcb_id
                JOIN booking_track ON  bkg_id = btk_bkg_id  
				WHERE aat_type = 9 and aat_status = 2	 	  
				  AND DATE_SUB(NOW(), INTERVAL 24 HOUR)< aat_created_at AND NOW() < aat_pickup_date 
				AND bkg_status between 2 AND 5	
				   ";

		$bmodels = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($bmodels as $bmodel)
		{

			if ($bmodel['bkg_agent_id'] == 450)
			{
//				$bookingPref = BookingPref::model()->getByBooking($bmodel['bkg_id']);
				$mmtResponse = false;
				for ($count = 0; $count < 2 && !$mmtResponse; $count++)
				{
					$driver_phone = $bmodel['bcb_driver_phone'];
					if ($bmodel['bkg_country_code'] == 91)
					{
						$driver_phone = Yii::app()->params['customerToDriverforMMT'];
					}

					$apiURL				 = 'http://www.aaocab.com/mmtproxy.php';
					$requestParamList	 = [
						"type"				 => "driverDetail",
						"booking_id"		 => "{$bmodel['bkg_agent_ref_code']}",
						"vendor_booking_id"	 => $bmodel['bkg_id'],
						"cab_number"		 => $bmodel['bcb_cab_number'],
						"driver_name"		 => $bmodel['bcb_driver_name'],
						"driver_mobile"		 => $driver_phone,
						"otp"				 => $bmodel['bkg_trip_otp']
					];
					$jsonData			 = json_encode($requestParamList);
					$ch					 = curl_init($apiURL);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json'
							)
					);
					$jsonResponse		 = curl_exec($ch);
					$responseList		 = json_decode($jsonResponse, true);

					$partnerResponse = new PartnerResponse();
					if (isset($responseList['success']) && $responseList['success'] == true)
					{
						$partnerResponse->status = 1;
					}
					else if (isset($responseList['vendor_response']) && $responseList['vendor_response']['is_success'] == true)
					{
						$partnerResponse->status = 1;
					}
					else if (isset($responseList['status']) && $responseList['status'] == 'success')
					{
						$partnerResponse->status = 1;
					}
					else
					{
						$partnerResponse->status = 2;
					}
					$partnerResponse->response = json_encode($responseList);

					$mmtResponse = ($partnerResponse->status == 1) ? true : false;

					$aatModel			 = AgentApiTracking::model()->findByPk($bmodel['aat_id']);
					$time				 = Filter::getExecutionTime();
					$aadModel			 = AgentApiDetails::model()->findByPk($bmodel['aad_id']);
					$aatModel->aadModel	 = $aadModel;
					$aatModel->updateResponse($partnerResponse, $bmodel['bkg_id'], $partnerResponse->status, $errorType, $errorMsg, $time);

					if ($mmtResponse)
					{
						$description = 'Updated Successfully';
					}
					else
					{
						$description = 'Failed to Update';
					}
					BookingLog::model()->createLog($bmodel['bkg_id'], $description, UserInfo::model(), BookingLog::MMT_CAB_DRIVER_UPDATE, $oldModel, $params);
				}
			}
		}
	}

	public function actionLockedOutstandingBalance()
	{
		Agents::model()->lockedOutstandingBalanceCron();
	}

	public function actionSyncAdvanceLedger()
	{
		$sql = "SELECT booking.bkg_id, bkg_agent_id, bkg_pickup_date, (bkg_advance_amount-bkg_refund_amount) as advance, amount FROM booking 
				INNER JOIN booking_cab ON bkg_bcb_id=bcb_id AND bkg_status IN (2,3,5,6,7) AND bkg_create_date>DATE_SUB(NOW(), INTERVAL 30 DAY) AND bkg_agent_id IS NOT NULL
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id  
				LEFT JOIN (

							SELECT bkg_id, SUM(atd1.adt_amount) as amount FROM account_trans_details atd 
							INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_ledger_id IN (16,17,18,19,20,21,23,26,29,30,36,46,47,39,1,42)
							INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_ledger_id=15 AND act.act_date>DATE_SUB(NOW(), INTERVAL 30 DAY)
					INNER JOIN booking ON booking.bkg_id=atd.adt_trans_ref_id GROUP BY bkg_id
				) a ON a.bkg_id=booking.bkg_id 
				WHERE  a.amount IS NULL AND (bkg_advance_amount-bkg_refund_amount)>0  
				ORDER BY `advance`  DESC";

		$res = DBUtil::query($sql, DBUtil::SDB());
		foreach ($res as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$actModel				 = AccountTransactions::advanceReceived($row['bkg_pickup_date'], PaymentType::TYPE_AGENT_CORP_CREDIT, $row['bkg_agent_id'], $row['advance'], Accounting::AT_BOOKING, $row['bkg_id'], "Partner Wallet Used");
				AccountTransactions::model()->PartnerCoinsUsed($row['bkg_agent_id'], $row['advance'], $row['bkg_pickup_date'], $row['bkg_id'], Accounting::AT_BOOKING, "Partner Wallet Used", UserInfo::model());
				$params					 = [];
				$params['blg_ref_id']	 = $actModel->act_id;
				BookingLog::model()->createLog($row['bkg_id'], "Partner Wallet Used - Payment Added", UserInfo::model(), BookingLog::PAYMENT_COMPLETED, '', $params);
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				echo json_encode($row) . "\n" . $e->getTraceAsString() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionSetEffectiveCreditLimit()
	{
		$data = Agents::model()->getCollectionList();
		foreach ($data as $d)
		{
			try
			{
				$model					 = Agents::model()->resetScope()->findByPk($d['agt_id']);
				$pastduedays			 = $model->agt_overdue_days;
				$old_credit_limit		 = $model->agt_effective_credit_limit;
				$agentGraceDays			 = $model->agt_grace_days;
				$row					 = PartnerSettings::getValueById($d['agt_id']);
				$rotating_credit_limit	 = $row['pts_rotating_credit_limit'] != null ? $row['pts_rotating_credit_limit'] : 0;
				$today					 = DBUtil::getCurrentTime();
				$lastBookingLedgerDate	 = AccountTransactions::getLastBookingLedgerDate($d['agt_id']);
				if ($d['totTrans'] <= $rotating_credit_limit && ((DBUtil::getTimeDiff($lastBookingLedgerDate, $today)) / 1440) <= $model->agt_grace_days)
				{
					$model->agt_effective_credit_limit	 = max((-1 * $d['totTrans']), $d['creditLimit']);
					$model->agt_overdue_days			 = 0;
				}
				else
				{
					$pastduedays++;
					$model->agt_overdue_days = $pastduedays;
					if ($pastduedays <= $agentGraceDays)
					{
						$model->agt_effective_credit_limit = $d['creditLimit'];
					}
					else if ($d['totTrans'] > ($d['creditLimit'] * 0.75))
					{
						$overduedays						 = $pastduedays - $agentGraceDays;
						$model->agt_effective_credit_limit	 = $d['creditLimit'] - ($overduedays * 0.5 * $d['totTrans']) <= 0 ? 0 : $d['creditLimit'] - ($overduedays * 0.5 * $d['totTrans']);
					}
				}
				if ($model->save())
				{
					if ($old_credit_limit != $model->agt_effective_credit_limit)
					{
						$credit_desc = 'Effective credit limit (Old Value : ' . $old_credit_limit . ', New Value : ' . $model->agt_effective_credit_limit . ')';
						AgentLog::model()->createLog($model->agt_id, $credit_desc, UserInfo::getInstance(), AgentLog::EFFECTIVE_CREDIT_LIMIT_UNSET, false, false);
					}
				}
			}
			catch (Exception $e)
			{
				Logger::exception($e);
			}
		}
	}

	/**
	 * This is used to create call back for those agent who hasn't served any booking for certain date range
	 */
	public function actionGetBookingDetailsByDate()
	{
		$sql = "SELECT agt_id.
				agt_phone,
				FROM   agents
				WHERE  agents.agt_approved = 1
				AND agents.agt_active = 1
				AND NOT EXISTS (SELECT bkg_agent_id
				FROM   booking
				WHERE  booking.bkg_agent_id = agents.agt_id
				AND booking.bkg_create_date BETWEEN Date_sub(Now(), INTERVAL 5 DAY) AND	Now()
				AND bkg_agent_id IS NOT NULL
				AND bkg_agent_id <> 1249
				GROUP  BY bkg_agent_id) LIMIT 1 ";

		$records = DBUtil::query($sql, DBUtil::MDB());

		foreach ($records as $key => $row)
		{
			try
			{
				$contactId										 = ContactProfile::getByEntityId($row['agt_id'], UserInfo::TYPE_AGENT);
				$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
				$code											 = $arrPhoneByPriority['phn_phone_country_code'];
				$number											 = $arrPhoneByPriority['phn_phone_no'];
				Filter::parsePhoneNumber($number, $code, $phone);
				$model											 = new ServiceCallQueue();
				$model->contactRequired							 = 1;
				$model->scq_to_be_followed_up_with_value		 = $code . $phone;
				$model->scq_to_be_followed_up_with_contact		 = $contactId;
				$model->scq_to_be_followed_up_with_entity_id	 = $row['agt_id'];
				$model->scq_to_be_followed_up_with_entity_type	 = UserInfo::TYPE_AGENT;
				$model->scq_to_be_followed_up_with_entity_rating = -1;
				$model->scq_created_by_type						 = UserInfo::TYPE_SYSTEM;
				$model->scq_to_be_followed_up_by_type			 = 2;
				$model->scq_to_be_followed_up_by_id				 = 327; // Currently assigned to csr Monu Only	
				$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_AGENT_NOT_SERVED_BOOKING;
				$model->scq_creation_comments					 = "Agents Booking Not created Within certain date range";
				$returnSet										 = ServiceCallQueue::model()->create($model, UserInfo::TYPE_AGENT, ServiceCallQueue::PLATFORM_ADMIN_CALL);
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
				\Sentry\captureMessage(json_encode($ex), null);
			}
		}
	}

	public function actionAddPartnerSetting()
	{
		$records = Agents::getAllAgents($type	 = 1);
		foreach ($records as $value)
		{
			try
			{
				$partnerModel = PartnerSettings::model()->getbyPartnerId($value['agt_id']);
				if (!$partnerModel)
				{
					$outStationDetail				 = PartnerRuleCommission::getMinAmountByType("1", $value['agt_id']);
					$localDetail					 = PartnerRuleCommission::getMinAmountByType("2", $value['agt_id']);
					$model							 = new PartnerSettings();
					$model->pts_agt_id				 = $value['agt_id'];
					$model->pts_outstation_count	 = 0;
					$model->pts_local_count			 = 0;
					$partnerCombineArr				 = array();
					$partnerCombineArr["outstation"] = array('isApplied' => 0, 'id' => $outStationDetail['prc_id'], 'commissionType' => $outStationDetail['prc_commission_type'], "commissionValue" => $outStationDetail['prc_commission_value']);
					$partnerCombineArr["local"]		 = array('isApplied' => 0, 'id' => $localDetail['prc_id'], 'commissionType' => $localDetail['prc_commission_type'], "commissionValue" => $localDetail['prc_commission_value']);
					$model->pts_additional_param	 = json_encode($partnerCombineArr);

					if (!$model->save())
					{
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
				}
				elseif ($partnerModel->pts_additional_param == null || trim($partnerModel->pts_additional_param) == "")
				{
					$outStationDetail					 = PartnerRuleCommission::getMinAmountByType("1", $value['agt_id']);
					$localDetail						 = PartnerRuleCommission::getMinAmountByType("2", $value['agt_id']);
					$partnerModel->pts_agt_id			 = $value['agt_id'];
					$partnerModel->pts_outstation_count	 = 0;
					$partnerModel->pts_local_count		 = 0;
					$partnerCombineArr					 = array();
					$partnerCombineArr["outstation"]	 = array('isApplied' => 0, 'id' => $outStationDetail['prc_id'], 'commissionType' => $outStationDetail['prc_commission_type'], "commissionValue" => $outStationDetail['prc_commission_value']);
					$partnerCombineArr["local"]			 = array('isApplied' => 0, 'id' => $localDetail['prc_id'], 'commissionType' => $localDetail['prc_commission_type'], "commissionValue" => $localDetail['prc_commission_value']);
					$partnerModel->pts_additional_param	 = json_encode($partnerCombineArr);

					if (!$partnerModel->save())
					{
						throw new Exception(CJSON::encode($partnerModel->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
				}
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
	}

	public function actionAddPartnerRuleCommision()
	{
		$records = Agents::getAllPartnerRuleCommission($type	 = 1);
		foreach ($records as $value)
		{
			try
			{
				// booking Type Outsation
				$partnerModelOutStation = PartnerRuleCommission::model()->getbyPartnerId($value['agt_id'], 1);
				if (!$partnerModelOutStation)
				{
					$outStationDetail			 = PartnerRuleCommission::getMinAmountByType("1", $value['agt_id']);
					$model						 = new PartnerRuleCommission();
					$model->prc_agent_id		 = $value['agt_id'];
					$model->prc_booking_type	 = 1;
					$model->prc_booking_count	 = 0;
					$model->prc_commission_type	 = $outStationDetail['prc_commission_type'];
					$model->prc_commission_value = $outStationDetail['prc_commission_value'];
					$model->prc_created_at		 = DBUtil::getCurrentTime();
					$model->prc_update_at		 = DBUtil::getCurrentTime();
					$model->prc_active			 = 1;

					if (!$model->save())
					{
						throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
				}

				// booking Type Local
				$partnerModelLocal = PartnerRuleCommission::model()->getbyPartnerId($value['agt_id'], 2);
				if (!$partnerModelLocal)
				{
					$localDetail				 = PartnerRuleCommission::getMinAmountByType("2", $value['agt_id']);
					$model						 = new PartnerRuleCommission();
					$model->prc_agent_id		 = $value['agt_id'];
					$model->prc_booking_type	 = 2;
					$model->prc_booking_count	 = 0;
					$model->prc_commission_type	 = $localDetail['prc_commission_type'];
					$model->prc_commission_value = $localDetail['prc_commission_value'];
					$model->prc_created_at		 = DBUtil::getCurrentTime();
					$model->prc_update_at		 = DBUtil::getCurrentTime();
					$model->prc_active			 = 1;
					if (!$model->save())
					{
						throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
				}
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
	}

	public function actionMMTDataCreated($minDays = -1, $maxDays = 0)
	{
		$check = Filter::checkProcess("agent mMTDataCreated");
		if (!$check)
		{
			return;
		}

		for ($i = $minDays; $i <= $maxDays; $i++)
		{
			$date = date("Y-m-d", strtotime("$i day", time()));

			$tempTable = "tmpMMTDataCreated_" . rand();

			$sql = "SELECT DATE_FORMAT(aat_created_at, '%Y-%m-%d') mdc_date, 
					aat_from_city mdc_from_city_id, aat_to_city mdc_to_city_id, aat_booking_type mdc_booking_type, 
					SUM(IF(aat_type = 2 AND aat_error_type IS NULL, 1, 0)) as mdc_search_count, 
					SUM(IF(aat_type = 8 AND aat_error_type IS NULL, 1, 0)) as mdc_hold_count, 
					SUM(IF(aat_type = 3 AND aat_error_type IS NULL, 1, 0)) as mdc_confirm_count, 
					SUM(IF(aat_type = 2 AND aat_error_type IN (105,107), 1, 0)) as mdc_search_blocked_count, 
					SUM(IF(aat_type = 2 AND aat_error_type NOT IN (105,107) AND aat_error_type IS NOT NULL, 1, 0)) as mdc_search_error_count 
					FROM agent_api_tracking  
					WHERE aat_type IN (2,3,8) AND aat_created_at BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59' 
					AND aat_from_city > 0 AND aat_to_city > 0 AND aat_booking_type IN (1,2,3) 
					GROUP BY mdc_date, mdc_from_city_id, mdc_to_city_id, mdc_booking_type ";
			DBUtil::createTempTable($tempTable, $sql, DBUtil::MDB());

			$sqlIns = "INSERT INTO mmt_data_created (mdc_date, mdc_from_city_id, mdc_to_city_id, mdc_booking_type, 
					mdc_search_count, mdc_hold_count, mdc_confirm_count, mdc_search_blocked_count, mdc_search_error_count) 
					SELECT a.* FROM (SELECT * FROM {$tempTable}) as a 
					ON DUPLICATE KEY UPDATE mdc_search_count=a.mdc_search_count, mdc_hold_count=a.mdc_hold_count, mdc_confirm_count=a.mdc_confirm_count, 
					mdc_search_blocked_count=a.mdc_search_blocked_count, mdc_search_error_count=a.mdc_search_error_count";
					
			DBUtil::execute($sqlIns);

			DBUtil::dropTempTable($tempTable, DBUtil::MDB());
		}
	}

	public function actionMMTDataPickup($minDays = 0, $maxDays = 15)
	{
		$check = Filter::checkProcess("agent mMTDataPickup");
		if (!$check)
		{
			return;
		}

		for ($i = $minDays; $i <= $maxDays; $i++)
		{
			$date = date("Y-m-d", strtotime("$i day", time()));

			$tempTable = "tmpMMTDataPickup_" . rand();

			$sql = "SELECT DATE_FORMAT(aat_pickup_date, '%Y-%m-%d') mdp_date, 
					aat_from_city mdp_from_city_id, aat_to_city mdp_to_city_id, aat_booking_type mdp_booking_type, 
					SUM(IF(aat_type = 2 AND aat_error_type IS NULL, 1, 0)) as mdp_search_count, 
					SUM(IF(aat_type = 8 AND aat_error_type IS NULL, 1, 0)) as mdp_hold_count, 
					SUM(IF(aat_type = 3 AND aat_error_type IS NULL, 1, 0)) as mdp_confirm_count, 
					SUM(IF(aat_type = 2 AND aat_error_type IN (105,107), 1, 0)) as mdp_search_blocked_count, 
					SUM(IF(aat_type = 2 AND aat_error_type NOT IN (105,107) AND aat_error_type IS NOT NULL, 1, 0)) as mdp_search_error_count 
					FROM agent_api_tracking 
					WHERE aat_type IN (2,3,8) AND aat_pickup_date BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59' 
					AND aat_from_city > 0 AND aat_to_city > 0 AND aat_booking_type IN (1,2,3) 
					GROUP BY mdp_date, mdp_from_city_id, mdp_to_city_id, mdp_booking_type";

			Logger::writeToConsole($date);
			Logger::writeToConsole($sql);

			DBUtil::createTempTable($tempTable, $sql, DBUtil::MDB());

			$sqlIns = "INSERT INTO mmt_data_pickup (mdp_date, mdp_from_city_id, mdp_to_city_id, mdp_booking_type, 
					mdp_search_count, mdp_hold_count, mdp_confirm_count, mdp_search_blocked_count, mdp_search_error_count) 
					SELECT a.* FROM (SELECT * FROM {$tempTable} ) as a 
					ON DUPLICATE KEY UPDATE mdp_search_count=a.mdp_search_count, mdp_hold_count=a.mdp_hold_count, mdp_confirm_count=a.mdp_confirm_count,  
					mdp_search_blocked_count=a.mdp_search_blocked_count, mdp_search_error_count=a.mdp_search_error_count";
			DBUtil::execute($sqlIns);

			DBUtil::dropTempTable($tempTable, DBUtil::MDB());
		}
	}
}
