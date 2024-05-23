<?php

class VehicleCommand extends BaseCommand
{

	public function actionBlockOnRating()
	{
		Logger::create("command.vehicle.blockOnRating start", CLogger::LEVEL_PROFILE);
		$data = Vehicles::model()->getLowRatingList();
		if ($data > 0)
		{
			$ctr		 = 0;
			$event_id	 = VehiclesLog::VEHICLE_FREEZE;
			foreach ($data as $d)
			{
				$model					 = Vehicles::model()->resetScope()->findByPk($d['bcb_cab_id']);
				$model->vhc_is_freeze	 = 1;
				if ($model->update())
				{
					$desc = "Car frozen due to low ratings ( " . $model->vhc_overall_rating . "  )";
					VehiclesLog::model()->createLog($model->vhc_id, $desc, Userinfo::getInstance(), $event_id, false, false);
					echo $model->vhc_id . " - [ " . $model->vhc_number . " ] is frozen.\n";
				}
			}
		}
		Logger::create("command.vehicle.blockOnRating end", CLogger::LEVEL_PROFILE);
	}

	public function actionFreezeOnWoDocument()
	{
		$rows = Vehicles::model()->getAllWoDocumentFiles();
		if ($rows > 0)
		{
			$ctr = 0;
			foreach ($rows as $d)
			{
				$event_id				 = VehiclesLog::VEHICLE_FREEZE;
				$model					 = Vehicles::model()->resetScope()->findByPk($d['vhc_id']);
				$model->vhc_is_freeze	 = 1;
				if ($model->save())
				{
					$desc = "Cab frozen (incomplete documentation)";
					VehiclesLog::model()->createLog($model->vhc_id, $desc, Userinfo::getInstance(), $event_id, false, false);
					echo $model->vhc_number . "is frozen (missing documents)\n";
				}
			}
		}
	}

	public function actionAutoRemovalOnExp()
	{
		$data = Vehicles::model()->getAutoRemovelList();
		if ($data > 0)
		{
			$ctr = 0;
			foreach ($data as $d)
			{
				$missing_ids = explode(',', $d['missing_ids']);
				$vendors	 = explode(',', $d['vendorIds']);
				if (count($vendors) > 0)
				{
					foreach ($vendors as $ven)
					{
						$vmdoel		 = Vendors::model()->findByPk($ven);
						$message	 = "Car [ " . $d['vhc_number'] . " ] - " . $d['missing_docs'] . " expired. Please upload new document";
						$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
						//AppTokens::model()->notifyVendor($vmdoel->vnd_id, $payLoadData, $message, "Please upload new document");
					}
				}
				$vDocsData = VehicleDocs::model()->findAllByVhcId($d['vhc_id']);
				if (count($vDocsData) > 0)
				{
					foreach ($vDocsData as $vdocs)
					{
						if ((in_array($vdocs['vhd_type'], $missing_ids)) && $vdocs['vhd_status'] == 1)
						{
							/* @var $dmodel VehicleDocs */
							$dmodel = VehicleDocs::model()->findByPk($vdocs['vhd_id']);
							switch ($vdocs['vhd_type'])
							{
								case 1:
									$event_id	 = VehiclesLog::VEHICLE_INSURANCE_REJECT;
									$remarks	 = "Insurance auto-rejected";
									break;
								case 2:
									$event_id	 = VehiclesLog::VEHICLE_FRONT_LICENSE_REJECT;
									$remarks	 = "Front License plate auto-rejected";
									break;
								case 3:
									$event_id	 = VehiclesLog::VEHICLE_REAR_LICENSE_REJECT;
									$remarks	 = "Rear License plate auto-rejected";
									break;
								case 4:
									$event_id	 = VehiclesLog::VEHICLE_PUC_REJECT;
									$remarks	 = "PUC certificate auto-rejected";
									break;
								case 5:
									$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
									$remarks	 = "RC auto-rejected";
									break;
								case 6:
									$event_id	 = VehiclesLog::VEHICLE_PERMITS_REJECT;
									$remarks	 = "Commercial permit auto-rejected";
									break;
								case 7:
									$event_id	 = VehiclesLog::VEHICLE_FITNESS_REJECT;
									$remarks	 = "Fitness certificate auto-rejected";
									break;
							}
							$dmodel->vhd_remarks = $remarks;
							$dmodel->vhd_status	 = 2;
							if ($dmodel->save())
							{
								$user_type	 = BookingLog::System;
								$user_id	 = 0;
								VehiclesLog::model()->createLog($dmodel->vhd_vhc_id, $dmodel->vhd_remarks, UserInfo::getInstance(), $event_id, false, false);
								echo "Vehicle ID --->" . $dmodel->vhd_vhc_id . " - " . $dmodel->vhd_type . " - " . $remarks . "\n";
							}
						}
					}
				}
				/*
				  $model = Vehicles::model()->resetScope()->findByPk($d['vhc_id']);
				  $model->vhc_approved=2;
				  // dont change the approval status of the vehicle, why not simply freeze it so it can be unfrozen later
				  if($model->update())
				  {
				  $desc = "Car auto rejected --> Expired ".$d['missing_docs']."";
				  VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
				  echo $vmdoel->vnd_id. " - ".$vmdoel->vnd_name."\n";
				  echo $model->vhc_id ." - [ ". $model->vhc_number . " ] auto rejected (expired papers)  \n";
				  }
				 */
			} // end foreach 
		}
	}

	public function actionAutoRejectDocument()
	{
		Logger::create("command.vehicle.autoRejectDocument start", CLogger::LEVEL_PROFILE);
		$data = Vehicles::model()->getExpriedPapersList();
		if ($data > 0)
		{
			foreach ($data as $d)
			{
				$vhdId		 = $d['vhd_id'];
				$vendors	 = $d['vendor_ids'];
				$vendocModel = new VehicleDocs();
				//$vendocModel->rejectDocument($d['vhd_id'], $d['vendor_ids'],  UserInfo::getInstance());
				$vendocModel->rejectDocument($vhdId, $vendors, UserInfo::getInstance());
			}
		}
		Logger::create("command.vehicle.autoRejectDocument end", CLogger::LEVEL_PROFILE);
	}
	/**
	 * @deprecated 
	 * new function actionAutoApprove
	 * @return type
	 */
	public function actionAutoApproveOld()
	{

		$check = Filter::checkProcess("vehicle autoApprove");
		if (!$check)
		{
			return;
		}
		Logger::profile("command.vehicle.autoApprove start", CLogger::LEVEL_PROFILE);
		$sumApprove	 = 0;
		$res		 = VehicleDocs::findApproveList();
		if ($res->getRowCount() > 0)
		{
			foreach ($res as $row)
			{
				$key		 = "Approval Started for " . json_decode($row);
				#$vendors	 = explode(',', $row['vendorIds']);
				$vhcId		 = $row['vhd_vhc_id'];
				$vhcNumber	 = $row['vhc_number'];
				$vmodel		 = new Vehicles();
				$success	 = $vmodel->approve($vhcId, UserInfo::getInstance());

				if (!$success)
				{
					continue;
				}
				$message	 = "Cab [ " . $vhcNumber . " ] is approved.";
				Logger::info($message);
				$sumApprove	 = ($sumApprove + 1);
				$title  = "Cab approved";
				self::sendNotification($vhcId,$message,$title);
			}
		}
		Logger::info("command.vehicle.autoApprove Total Approve ->" . $sumApprove);
	}
	/**
	 * Use for 5 min corn
	 */
	public function actionAutoApprove()
	{
		$check = Filter::checkProcess("vehicle autoApprove");
        if (!$check)
        {
            return;
        }
		
		Vehicles::autoApprove();
	}
	/**
	 * @deprecated 
	 * new function actionAutoApprove and StatusModification
	 * @return type
	 */
	public static function sendNotification($vhcId,$message,$title)
	{
		$res = VendorVehicle::getLinkedVendors($vhcId);
		foreach ($res as $row)
		{
			$vndId		 = $row['vndId'];
			$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
			AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, $title);
		}
		return true;
	}
	/**
	 * @deprecated 
	 * new function StatusModification
	 * @return type
	 */
	public function actionAutoDisapprove()
	{
		$model			 = new VehicleDocs();
		$res			 = $model->findDisapproveList();
		$sumNotApprove	 = 0;
		if (!$res || $res->getRowCount() == 0)
		{
			return;
		}
		foreach ($res as $row)
		{
			$vhcId		 = $row['vhd_vhc_id'];
			#$vendors	 = explode(',', $row['vendorIds']);
			$vhcNumber	 = $row['vhc_number'];
			$vmodel		 = new Vehicles();
			$success	 = $vmodel->disapprove($vhcId, UserInfo::getInstance());
			if (!$success)
			{
				continue;
			}
			$message		 = "Car [" . $vhcNumber . "] is rejected (papers expired).";
			$title  = "Cab rejected";
			Logger::info($message);
			$sumNotApprove	 = ($sumNotApprove + 1);
			self::sendNotification($vhcId,$message,$title);
		}
	}
	
	
	public function actionVerifyStatus()
	{
		Vehicles::autoDisapprove();
		Vehicles::autoPendingApproval();
		Vehicles::autoApprove();
	}
	
	public function actionSendDigitalAgreement()
	{
		$vhcAgmtRows = VendorVehicle::model()->findAllDigitalAgreementCopy();
		if (count($vhcAgmtRows) > 0)
		{
			foreach ($vhcAgmtRows as $row)
			{
				$vvhcId	 = $row['vvhc_id'];
				$vhcId	 = $row['vvhc_vhc_id'];
				if (Vehicles::model()->saveForUndertakingCopy($vvhcId) == true)
				{
					$var = $row['vhc_number'] . " - " . $row['vnd_name'] . " - Digital Undertaking copy saved.";
					Logger::create($var, CLogger::LEVEL_INFO);
				}
			}
		}
	}

	public function actionUpdateCode()
	{
		$sql		 = "SELECT
					vehicles.vhc_id
					FROM
						`vehicles`
					WHERE
					(
						vehicles.vhc_code IS NULL OR vehicles.vhc_code = ''
					) AND vehicles.vhc_active > 0";
		$vehicleIds	 = DBUtil::queryAll($sql);
		Logger::create("Total Car: " . count($vehicleIds), CLogger::LEVEL_TRACE);
		if (count($vehicleIds) > 0)
		{
			foreach ($vehicleIds as $vhc)
			{
				try
				{
					$success	 = false;
					$transaction = DBUtil::beginTransaction();
					$arr		 = Filter::getCodeById($vhc['vhc_id'], $type		 = 'car');
					if ($arr['success'] == 1)
					{
						$model			 = Vehicles::model()->resetScope()->findByPk($vhc['vhc_id']);
						$model->vhc_code = $arr['code'];
						$model->scenario = 'updateCode';
						if ($model->save())
						{
							$success = true;
							if ($success == true)
							{
								DBUtil::commitTransaction($transaction);
								$updateData = $model->vhc_id . " - " . $model->vhc_code;
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
		$rows		 = VehicleDocs::getListReadyApproval();
		$userInfo	 = UserInfo::getInstance();
		Logger::create("Total vehicles ready for approval : " . count($rows), CLogger::LEVEL_TRACE);
		if (count($rows) > 0)
		{
			$count = 0;
			foreach ($rows as $row)
			{
				$log = "Vehicle ready for approval => Car Id : " . $row['vhc_id'] . " , R4A Score : " . $row['updateDocNumber'];
				echo $log . "\n";
				Logger::create($log, CLogger::LEVEL_INFO);
				VehicleDocs::model()->instantReadyForApproval($row['vhc_id'], $row['updateDocNumber']);
			}
		}
	}

	/**
	 * Update Vehicle Home City (one time)
	 */
	public function actionUpdateVehicleHomeCity()
	{
		Vehicles::model()->updateVehicleCity();
	}

	/**
	 * Vehicle State By Car Number Prefix
	 */
	public function actionUpdateVehicleState()
	{
		Vehicles::model()->updateVehicleState();
	}

	public function actionResetTempApprovedVehicles()
	{
		VehicleDocs::model()->resetTempApprovedVehicles();
	}

	public function actionUpdateTier()
	{
		$sccData = ServiceClass::getAll();
		$result	 = Vehicles::model()->updateIsAllowedTier();

		foreach ($sccData as $scc)
		{
			Vehicles::updateTier($scc['scc_odometer'], $scc['scc_id'], $scc['scc_model_year']);

			echo "=====Updated Class: {$scc['scc_id']}=========<br>";
		}
	}

	public function actionAddBoost()
	{
		Vehicles::model()->addBoost();
	}

	public function actionRemoveBoost()
	{
		Vehicles::removeBoost();
	}

	public function actionRejectBoost()
	{
		Vehicles::rejectBoost();
	}

	public function actionRemainingVehicleBoost()
	{
		$date		 = date('Y-m-d');
		$today		 = date('d-m-Y');
		$next_date	 = date('Y-m-d', strtotime($today . ' + 30 days'));

		$sql = "SELECT vhd_vhc_id, COUNT(vhd_vhc_id),vhd_appoved_at FROM vehicle_docs WHERE `vhd_active` =1 AND vhd_type IN (8,9,10,11)  AND `vhd_approve_by` IS NOT NULL 
                    AND `vhd_appoved_at` < '2020-09-28'
                    GROUP BY vhd_vhc_id HAVING COUNT(vhd_vhc_id) =4";

		$rows = DBUtil::query($sql, DBUtil::MDB(), $params);

		foreach ($rows as $val)
		{
			$vehicleId		 = $val['vhd_vhc_id'];
			$vhkStatsModel	 = VehicleStats::model()->getbyVehicleId($vehicleId);

			if (!empty($vhkStatsModel))
			{
				$vhkStatsModel->vhs_boost_enabled		 = 1;
				$vhkStatsModel->vhs_boost_approved_date	 = $date;
				$vhkStatsModel->vhs_boost_expiry_date	 = $next_date;
				$success								 = $vhkStatsModel->save();

				$vendorId		 = Vehicles::model()->getVendorByVehicleId($vehicleId);
				$updateVendor	 = VendorPref::model()->updateBoostCount($vendorId);
				$boostPercentage = Vehicles::calculateVendorBoost($vendorId);
				$updateVendor	 = VendorStats::model()->updateBoostPercentage($vendorId, $boostPercentage);
			}
		}
	}
	
	

}
