<?php

use components\Event\Events;
use components\Event\EventSchedule;
use components\Event\EventReceiver;

class OneTimeCommand extends BaseCommand
{

	/**
	 * updating area select model with default values one time
	 */
	public function actionUpdateASM()
	{
		$areaId = [1, 2, 3, 4, 5, 6, 7];
		foreach ($areaId as $area)
		{
			$sqlTemp	 = "SELECT vht_id, vht_make, vht_model FROM  vehicle_types vht where vht.vht_active = 1";
			$recordApr	 = DBUtil::query($sqlTemp, DBUtil::SDB());
			foreach ($recordApr as $record)
			{
				$model					 = new AreaSelectModel();
				$model->asm_area_type	 = 4;
				$model->asm_area_id		 = $area;
				$model->asm_markup_type	 = 2;
				$model->asm_markup		 = 0;
				$model->asm_model_id	 = $record['vht_id'];
				if (!$model->save())
				{
					throw new Exception("Error adding area select model.");
				}
				else
				{
					echo 'Select Model Id:' . $model->asm_id . '<br>';
				}
			}
		}
	}

	public function actionVendorAccounts()
	{
		$sql = "SELECT bkg_id  FROM account_transactions act
				INNER JOIN account_trans_details atd ON act.act_id=atd.adt_trans_id 
						AND atd.adt_ledger_id=13 AND act.act_active=1 AND atd.adt_active=1
				INNER JOIN account_trans_details atd1 ON act.act_id=atd1.adt_trans_id AND atd1.adt_active=1 AND atd1.adt_ledger_id=14
				INNER JOIN account_ledger ON atd1.adt_ledger_id=account_ledger.ledgerId
				RIGHT JOIN booking ON bkg_id=atd.adt_trans_ref_id
				INNER JOIN booking_invoice ON bkg_id=biv_bkg_id AND bkg_status IN (5,6,7) AND bkg_vendor_collected>0
				WHERE act_id IS NULL AND bkg_pickup_date BETWEEN '2019-04-01 00:00:00' AND '2020-03-31 23:59:59'";

		$rows = DBUtil::query($sql);

		foreach ($rows as $row)
		{
			$bkgId			 = $row["bkg_id"];
			$bkgModel		 = Booking::model()->findByPk($bkgId);
			$date			 = $bkgModel->bkg_pickup_date;
			$vendorId		 = $bkgModel->bkgBcb->bcb_vendor_id;
			$datetime		 = ($date != '') ? $date : new CDbExpression('NOW()');
			$remarks		 = "Amount collected by operator";
			$vendorCollected = $bkgModel->bkgInvoice->bkg_vendor_collected;
			if ($vendorCollected === 0)
			{
				continue;
			}
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $vendorCollected, $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkgId, Accounting::LI_BOOKING, (-1 * $vendorCollected));
			AccountTransactions::model()->add($accTransDetArr, $datetime, $vendorCollected, $bkgId, Accounting::AT_BOOKING, $remarks);
		}
	}

	public function actionPCMatch()
	{
		$sql	 = "SELECT * FROM `tmpPartnerCreditsMismatch` WHERE vendor<>0";
		$rows	 = DBUtil::query($sql);

		foreach ($rows as $row)
		{
			$bkgModel = Booking::model()->findByPk($row["bkg_id"]);
			if ($bkgModel->bkg_agent_id == null)
			{
				continue;
			}
			$amount = ($row['vendor'] + $row["booking"]);
			if ($amount < 0)
			{
				$remarks = "Partner Coins added to booking";
			}
			else
			{
				$remarks = "Partner coins refunded";
			}
			AccountTransactions::model()->addCoinsToBooking($bkgModel->bkg_pickup_date, $bkgModel->bkg_agent_id, $bkgModel->bkg_id, $amount, $remarks);
		}
	}

	public function actionUpdateRouteDistance()
	{
		$sql = "SELECT rut_id, rut_from_city_id, rut_to_city_id, rut_name, rut_actual_distance, rut_estm_distance, CalcDistance(fc.cty_lat, fc.cty_long, tc.cty_lat, tc.cty_long) as distance FROM `route`
				INNER JOIN cities fc ON fc.cty_id=route.rut_from_city_id AND fc.cty_active=1
				INNER JOIN cities tc ON tc.cty_id=route.rut_to_city_id  AND tc.cty_active=1
				WHERE rut_active=1 AND rut_estm_distance<CalcDistance(fc.cty_lat, fc.cty_long, tc.cty_lat, tc.cty_long)*1";

		$res = DBUtil::query($sql);

		foreach ($res as $row)
		{
			Logger::writeToConsole("Id: " . $row['rut_id']);

			$fcityModel	 = Cities::model()->findByPk($row['rut_from_city_id']);
			$dcityModel	 = Cities::model()->findByPk($row['rut_to_city_id']);
			$isIP		 = false;
			$result		 = ["success" => false];
			try
			{
				if ($fcityModel->cty_lat != '' && $dcityModel->cty_lat != '')
				{
					$isIP		 = true;
					$sourcePlace = \Stub\common\Place::init($fcityModel->cty_lat, $fcityModel->cty_long);
					$destPlace	 = \Stub\common\Place::init($dcityModel->cty_lat, $dcityModel->cty_long);
					$dmxModel	 = DistanceMatrix::getByCoordinates($sourcePlace, $destPlace);
					if ($dmxModel)
					{
						$distance		 = $actualDistance	 = $dmxModel->dmx_distance;
						$time			 = $actualTime		 = $dmxModel->dmx_duration;
						$result			 = ["success" => true];
					}
				}

				if ($result['success'])
				{
					/** @var Route $rutModel */
					$rutModel						 = Route::model()->findByPk($row['rut_id']);
					echo "\n{$rutModel->rut_id} == {$rutModel->rut_estm_distance}::{$rutModel->rut_estm_time}\t{$rutModel->rut_actual_distance}::{$rutModel->rut_actual_time}\t{$distance}::{$time}  ({$row['distance']}) - ({$row['rut_name']})\n";
					$rutModel->rut_estm_distance	 = $distance;
					$rutModel->rut_estm_time		 = $time;
					$rutModel->rut_actual_distance	 = $actualDistance;
					$rutModel->rut_actual_time		 = $actualTime;
					$rutModel->rut_active			 = 1;
					if ($rutModel->rut_name == "")
					{
						$rutModel->rut_name = $this->generateAlias($rutModel->rut_from_city_id, $rutModel->rut_to_city_id);
					}
					$rutModel->save();
				}
			}
			catch (Exception $e)
			{
				Logger::exception($e);
			}
		}
	}

	public function actionModifyManuallyAssignedMode()
	{
		$userType	 = 4;
		$assignMode	 = 1;
		BookingCab::modifyAssignMode($userType, $assignMode);
	}

	public function actionModifyDirectAcceptMode()
	{
		$userType	 = 2;
		$assignMode	 = 2;
		BookingCab::modifyAssignMode($userType, $assignMode);
	}

	public function actionUnlinkVehicleDocFiles()
	{
		$sql = "SELECT vhd_id FROM vehicle_docs WHERE (vhd_vhc_id = 0 OR vhd_vhc_id IS NULL) AND (vhd_s3_data IS NULL AND vhd_file!='' AND vhd_file IS NOT NULL) ORDER BY vhd_id DESC LIMIT 0, 10000";
		$res = DBUtil::query($sql);
		foreach ($res as $row)
		{
			$vhdModel	 = VehicleDocs::model()->findByPk($row['vhd_id']);
			echo "\r\n\r\nvhd_file == " . $vhdfile	 = $vhdModel->vhd_file;

			$filePath = $this->getLocalPath($vhdfile);
			if (file_exists($filePath))
			{
				echo "\r\n\r\nfilePath == " . $filePath;
				unlink($filePath);
			}

			echo "\r\n\r\nDEL == " . $sqlDel = "DELETE FROM vehicle_docs WHERE vhd_id = " . $row['vhd_id'];
			DBUtil::execute($sqlDel);
		}
	}

	public function getLocalPath($filePath)
	{
		$filePath = implode("/", explode(DIRECTORY_SEPARATOR, $filePath));

		$filePath = ltrim($filePath, '/attachments');

		$filePath = implode(DIRECTORY_SEPARATOR, explode("/", $filePath));

		$filePath = $this->getBaseDocPath() . $filePath;

		if (!file_exists($filePath))
		{
			$filePath = APPLICATION_PATH . $filePath;
		}

		return $filePath;
	}

	public function getBaseDocPath()
	{
		return PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
	}

	public function actionUpdGozoAmt()
	{
		$sql	 = "SELECT DISTINCT bkg_bcb_id, bkg_gozo_amount FROM booking, booking_invoice WHERE bkg_id = biv_bkg_id AND bkg_status IN (6,7) AND bkg_pickup_date BETWEEN '2023-06-01 00:00:00' AND '2023-06-30 23:59:59'";
		$records = DBUtil::query($sql);

		foreach ($records as $row)
		{
			#echo "<br>==================<br>BkgId == " . $row['bkg_bcb_id'];


			$sql1	 = "SELECT UpdateGozoAmount({$row['bkg_bcb_id']})";
			$numRows = DBUtil::queryScalar($sql1);

			if ($numRows > 0)
			{
				echo "\nbcb_id = " . $row['bkg_bcb_id'] . " - {$numRows} - {$row['bkg_gozo_amount']}";
			}
		}
	}

	public function actionUpdateInvoice()
	{
		$sql = "SELECT * FROM test.booking_invoice_new WHERE bkg_status = 0 ORDER BY biv_id ASC";
		$res = DBUtil::query($sql);
		foreach ($res as $row)
		{
			$bivid					 = $row['biv_id'];
			$model					 = BookingInvoice::model()->findByPk($bivid);
			$model->bkg_base_amount	 = $row['New_Base_Fare'];
			$model->bkg_service_tax	 = $row['New_GST'];
			if ($model->save())
			{
				$query = "UPDATE test.booking_invoice_new SET bkg_status =1 WHERE biv_id = $bivid";
				DBUtil::execute($query);

				echo "\r\nbkgId == " . $bkgId = $row['biv_bkg_id'] . "bivid == " . $bivid . "base == " . $row['New_Base_Fare'] . "gst == " . $row['New_GST'];
			}
		}
	}

	public function actionUpdateGozoAmount()
	{
		$sql	 = "SELECT biv_id, bkg_bcb_id, bkg_gozo_amount FROM test.booking_invoice_new tbin
				INNER JOIN gozodb.booking bkg ON bkg.bkg_id = tbin.biv_bkg_id 
				INNER JOIN gozodb.booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
				WHERE tbin.bkg_status = 1 ORDER BY tbin.biv_id ASC LIMIT 0, 5";
		$records = DBUtil::query($sql);

		foreach ($records as $row)
		{
			echo "\r\n Bcb_id : " . $row['bkg_bcb_id'];

			$sql1	 = "SELECT UpdateGozoAmount({$row['bkg_bcb_id']})";
			$numRows = DBUtil::queryScalar($sql1);

			echo " == numRows : " . $numRows;

			if ($numRows > 0)
			{
				$bivid	 = $row['biv_id'];
				$query	 = "UPDATE test.booking_invoice_new SET bkg_status =2 WHERE biv_id = $bivid";
				DBUtil::execute($query);

				echo " == bkg_gozo_amount : " . $row['bkg_gozo_amount'];
			}

			echo " Ended ";
		}
	}

	public function actionNotifyVendorBankUpdate()
	{
		$sql = "SELECT vnd_id, vnd_code, vnd_name 
				FROM vendors 
				INNER JOIN `contact_profile` ON cr_is_vendor = vnd_id AND cr_status = 1 AND vnd_active = 1 
				INNER JOIN contact ON ctt_id = cr_contact_id AND ctt_active = 1 
				WHERE 1 AND cr_status = 1 AND cr_is_vendor > 0 AND ctt_id = ctt_ref_code AND vnd_id = vnd_ref_code 
				AND (
					vnd_id IN (
						SELECT DISTINCT bcb_vendor_id 
						FROM `booking` bkg 
						INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id AND bcb_active=1 
						WHERE 1 AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7) AND bkg.bkg_pickup_date >= '2022-02-01 00:00:00' 
					)
					OR 
					vnd_id IN (
						SELECT DISTINCT scq_to_be_followed_up_with_entity_id FROM `service_call_queue` WHERE `scq_follow_up_queue_type` = 23
						AND scq_create_date BETWEEN '2022-02-01 00:00:00' AND NOW()
						AND scq_to_be_followed_up_with_entity_type=2
						AND scq_prev_or_originating_followup IS NULL
					)
				)
				GROUP by cr_is_vendor";

		$data = DBUtil::query($sql);

		if (count($data) > 0)
		{
			$ctr = 1;
			foreach ($data as $row)
			{
				$vndId	 = $row['vnd_id'];
				$vndCode = $row['vnd_code'];

				$message = "Dear Partner, please check and update your bank  account details to ensure they are correct so that timely payments could continue hassle free.";

				$payLoadData = ['vendorId' => $vndId, 'EventCode' => Booking::CODE_VENDOR_BROADCAST];
				$success	 = AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, "Important Notification");

				echo "\r\nID: {$vndId}, Code: {$vndCode}, Status: " . (int) $success;

				$ctr++;
			}

			echo "\r\n\r\nCOUNT: {$ctr}";
		}
	}

	/**
	 * This function is used auto fur for trip start trip (1 hour early and 1 hour after trip start)
	 */
	public function actionOperatingServices()
	{
		$i		 = 0;
		$chk	 = true;
		$limit	 = 1000;
		while ($chk)
		{
			$sql	 = "SELECT vnp_vnd_id,vnp_oneway,vnp_round_trip,vnp_multi_trip,vnp_airport,vnp_package,vnp_flexxi,vnp_daily_rental,vnp_tempo_traveller,vnp_lastmin_booking FROM vendor_pref WHERE 1 AND vnp_admin_approved_services IS NULL OR  vnp_vnd_requested_services IS NULL   LIMIT $i, $limit";
			$details = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					$model								 = VendorPref::model()->getbyVendorId($row['vnp_vnd_id']);
					$json_array							 = array(
						"vnp_oneway"			 => $row['vnp_oneway'],
						"vnp_round_trip"		 => $row['vnp_round_trip'],
						"vnp_multi_trip"		 => $row['vnp_multi_trip'],
						"vnp_airport"			 => $row['vnp_airport'],
						"vnp_package"			 => $row['vnp_package'],
						"vnp_flexxi"			 => $row['vnp_flexxi'],
						"vnp_daily_rental"		 => $row['vnp_daily_rental'],
						"vnp_tempo_traveller"	 => $row['vnp_tempo_traveller'],
						"vnp_lastmin_booking"	 => $row['vnp_lastmin_booking']
					);
					$model->vnp_vnd_requested_services	 = json_encode($json_array);
					$model->vnp_admin_approved_services	 = json_encode($json_array);
					if (!$model->save())
					{
						Filter::writeToConsole(json_encode($model->errors));
					}
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
					Logger::writeToConsole($ex->getMessage());
				}
			}
			$i += $limit;
			Logger::info("\n*********************************** Start *********************************************\n");
			Logger::info($i);
			if ($details->rowCount == 0)
			{
				break;
			}
		}
	}

	/**
	 * This function is used auto fur for trip start trip (1 hour early and 1 hour after trip start)
	 */
	public function actionRatePerKilomter()
	{
		$i		 = 0;
		$chk	 = true;
		$limit	 = 1000;
		while ($chk)
		{
			$sql	 = "SELECT 
                        booking.bkg_id,
                        ROUND(booking_invoice.bkg_base_amount/booking.bkg_trip_distance,2) AS 'RatePerKilometer'
                        FROM booking
                        INNER JOIN booking_invoice ON  booking_invoice.biv_bkg_id=booking.bkg_id
                        WHERE 1 
                        AND booking.bkg_trip_distance <> 0
                        AND biv_quote_base_rate_km IS NULL
                        AND booking.bkg_create_date BETWEEN  '2019-01-01 00:00:00' AND  '2022-05-05 23:59:59' ORDER BY booking.bkg_id ASC LIMIT $i, $limit";
			$details = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					$model							 = BookingInvoice::model()->getByBookingID($row['bkg_id']);
					$model->biv_quote_base_rate_km	 = $row['RatePerKilometer'];
					if (!$model->save())
					{
						Filter::writeToConsole(json_encode($model->errors));
					}
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
					Logger::writeToConsole($ex->getMessage());
				}
			}
			$i += $limit;
			Logger::info("\n*********************************** Start *********************************************\n");
			Logger::info($i);
			if ($details->rowCount == 0)
			{
				break;
			}
		}
	}

	public function actionInsertDZPP90Day()
	{
		Logger::info("\n*********************************** InsertDZPP90Day Start *********************************************\n");
		$i		 = 0;
		$chk	 = true;
		$limit	 = 1000;
		while ($chk)
		{
			$sql = "SELECT 
                        temp.RowIdentifier AS RowIdentifier,
                        temp.Region,
                        temp.RegionId,
                        temp.FromZoneId,
                        temp.FromZoneName,
                        temp.FromMasterZone,
                        temp.FromMasterZoneId,
                        temp.ToZoneId,
                        temp.ToZoneName,
                        temp.ToMasterZone,
                        temp.ToMasterZoneId,
                        temp.CountBooking,
                        IF((temp.CountBooking/90)>1,'1',IF(((temp.CountBooking/90)>0.5 AND (temp.CountBooking/90)<=1 ),'2','3')) AS ZoneType,
                        temp.Profit,
                        temp.scv_label,
                        temp.scv_id,
                        temp.scv_scc_id,
                        temp.booking_type,
                        temp.SourceIsMaster,
                        temp.DestIsMaster, 
                        temp.target_boost AS TargetBoost,
                        temp.DZPP_Applied AS DZPP_Applied,
                        (ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) AS TargetMargin,
                        (ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+temp.DZPP_Applied) AS DifffromGoal,
                        ROUND(if((ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+temp.DZPP_Applied)>0,if(temp.CountBooking > 4,(1+((ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+temp.DZPP_Applied))/100),(1+(temp.CountBooking *(ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+temp.DZPP_Applied))/400)),1),2) AS DZPP,
                        CURDATE() AS CreateDate
                        FROM
                        (
                            SELECT 
                            dzs_row_identifier AS RowIdentifier,
                            dzs_regionname AS  Region,                        
                            dzs_regionid AS  RegionId,
                            dzs_fromzoneid AS  FromZoneId,
                            dzs_fromzonename AS   FromZoneName,
                            dzs_frommasterzone AS  FromMasterZone,
                            dzs_frommasterzoneid AS  FromMasterZoneId,
                            dzs_tozoneid AS  ToZoneId,
                            dzs_tozonename AS  ToZoneName,
                            dzs_tomasterzoneid AS  ToMasterZone,
                            dzs_fromzoneid AS  ToMasterZoneId,
                            SUM(dzs_countbooking) AS CountBooking,
                            round((SUM(dzs_countbooking * dzs_profit) / SUM(dzs_countbooking)), 2) AS Profit,
                            dzs_scv_label AS  scv_label,
                            dzs_scv_id AS scv_id,
                            dzs_scv_scc_id AS scv_scc_id,
                            dzs_booking_type as booking_type,
                            IF(SUM(dzs_countbooking)>90,11,IF(SUM(dzs_countbooking)>45,13.5,16)) AS SourceIsMaster,
                            IF(SUM(dzs_countbooking)>90,11,IF(SUM(dzs_countbooking)>45,13.5,16)) AS DestIsMaster,
                            ROUND(SUM(dzs_target_boost * dzs_countbooking ) / SUM(dzs_countbooking), 2)  AS target_boost,
                            ROUND(SUM(dzs_dzpp_applied * dzs_countbooking) / SUM(dzs_countbooking), 2)  AS DZPP_Applied  
                            FROM dynamic_zone_surge_1day
                            WHERE dzs_active = 1 AND dzs_createdate BETWEEN  (CURDATE() - INTERVAL 90 DAY) AND CURDATE()
                            GROUP BY dzs_row_identifier 
                            ORDER BY dzs_id ASC 
                            LIMIT $i, $limit
                        ) temp ORDER BY temp.profit DESC";

			Logger::info("\n*********************************** InsertDZPP90Day Sql Start *********************************************\n");
			Logger::info($sql);
			Logger::info("\n*********************************** InsertDZPP90Day Sql Ends *********************************************\n");
			$details = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					$days = Rate::lastModifidedDays($row['FromZoneId'], $row['ToZoneId'], $row['scv_id']);
					if (!$days)
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge` (`dzs_row_identifier`,`dzs_regionname`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_zone_type`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`) VALUES 
                                ('" . $row['RowIdentifier'] . "','" . $row['Region'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['FromZoneName'] . "', '" . $row['FromMasterZone'] . "', '" . $row['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row['ToMasterZone'] . "', '" . $row['ToMasterZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row['booking_type'] . "', '" . $row['DestIsMaster'] . "', '" . $row['SourceIsMaster'] . "', '" . $row['TargetMargin'] . "', '" . $row['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "');";
					}
					else
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge` (`dzs_row_identifier`,`dzs_regionname`,`dzs_rate_update_days`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_zone_type`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`) VALUES 
                                ('" . $row['RowIdentifier'] . "','" . $row['Region'] . "','" . $days . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['FromZoneName'] . "', '" . $row['FromMasterZone'] . "', '" . $row['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row['ToMasterZone'] . "', '" . $row['ToMasterZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row['booking_type'] . "', '" . $row['DestIsMaster'] . "', '" . $row['SourceIsMaster'] . "', '" . $row['TargetMargin'] . "', '" . $row['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "');";
					}

					Filter::writeToConsole($sqldata);
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
					Logger::writeToConsole($ex->getMessage());
				}
			}
			$i += $limit;
			Logger::info("\n*********************************** InsertDZPP90Day Count Start *********************************************\n");
			Logger::info($i);
			Logger::info("\n*********************************** InsertDZPP90Day Count Ends *********************************************\n");
			if ($details->rowCount == 0)
			{
				break;
			}
		}

		Logger::info("\n*********************************** InsertDZPP90Day Delete Start *********************************************\n");
		Logger::info($dropQry);
		$dropQry = "DELETE FROM `dynamic_zone_surge` WHERE dzs_createdate < CURDATE()";
		DBUtil::execute($dropQry);
		Logger::info("\n*********************************** InsertDZPP90Day Delete Ends *********************************************\n");

		Logger::info("\n*********************************** InsertDZPP90Day Type_Surge Start *********************************************\n");
		Yii::app()->cache->set(CacheDependency::buildCacheId(CacheDependency::Type_Surge), time());
		Logger::info("\n*********************************** InsertDZPP90Day Type_Surge Start *********************************************\n");

		Logger::info("\n*********************************** InsertDZPP90Day Ends *********************************************\n");
	}

	public function actionAddPartnerSetting()
	{
		$records = Agents::getAllAgents($type	 = 0);
		foreach ($records as $value)
		{
			try
			{
				$partnerModel = PartnerSettings::model()->getbyPartnerId($value['agt_id']);
				if (!$partnerModel)
				{
					$model							 = new PartnerSettings();
					$model->pts_agt_id				 = $value['agt_id'];
					$model->pts_outstation_count	 = 0;
					$model->pts_local_count			 = 0;
					$partnerCombineArr				 = array();
					$partnerCombineArr["outstation"] = array('isApplied' => 0, 'id' => 0, 'commissionType' => $value['agt_commission_value'], "commissionValue" => $value['agt_commission']);
					$partnerCombineArr["local"]		 = array('isApplied' => 0, 'id' => 0, 'commissionType' => $value['agt_commission_value'], "commissionValue" => $value['agt_commission']);
					$model->pts_additional_param	 = json_encode($partnerCombineArr);
					if (!$model->save())
					{
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
				}
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole($ex->getMessage());
				$returnSet = ReturnSet::setException($ex);
			}
		}
	}

	public function actionAddPartnerRuleCommision()
	{
		$records = Agents::getAllPartnerRuleCommission($type	 = 0);
		foreach ($records as $value)
		{
			try
			{
				// booking Type Outsation
				$partnerModel = PartnerRuleCommission::model()->getbyPartnerId($value['agt_id'], 1);
				if (!$partnerModel)
				{
					$model						 = new PartnerRuleCommission();
					$model->prc_agent_id		 = $value['agt_id'];
					$model->prc_booking_type	 = 1;
					$model->prc_booking_count	 = 0;
					$model->prc_commission_type	 = $value['agt_commission_value'];
					$model->prc_commission_value = $value['agt_commission'];
					$model->prc_created_at		 = DBUtil::getCurrentTime();
					$model->prc_update_at		 = DBUtil::getCurrentTime();
					$model->prc_active			 = 1;

					if (!$model->save())
					{
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
				}


				// booking Type Local
				$partnerModel = PartnerRuleCommission::model()->getbyPartnerId($value['agt_id'], 2);
				if (!$partnerModel)
				{
					$model						 = new PartnerRuleCommission();
					$model->prc_agent_id		 = $value['agt_id'];
					$model->prc_booking_type	 = 2;
					$model->prc_booking_count	 = 0;
					$model->prc_commission_type	 = $value['agt_commission_value'];
					$model->prc_commission_value = $value['agt_commission'];
					$model->prc_created_at		 = DBUtil::getCurrentTime();
					$model->prc_update_at		 = DBUtil::getCurrentTime();
					$model->prc_active			 = 1;
					if (!$model->save())
					{
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
				}
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole($ex->getMessage());
				$returnSet = ReturnSet::setException($ex);
			}
		}
	}

	public function actionMarkALLZoneType()
	{
		$sql	 = "SELECT
                bkg_id,
                bkg_booking_type,
                bkg_vehicle_type_id,
                bkg_from_city_id,
                bkg_to_city_id,
                bkg_create_date
                FROM booking                    
                INNER JOIN booking_pref ON booking_pref.bpr_bkg_id=booking.bkg_id                    
                WHERE 1 
                AND bkg_booking_type IS NOT NULL
                AND bkg_vehicle_type_id IS NOT NULL
                AND booking.bkg_create_date BETWEEN '2015-10-01 00:00:00' AND '2022-05-21 23:59:59'
                AND booking_pref.bpr_zone_type IS NOT  NULL
                AND booking_pref.bpr_row_identifier  IS NULL
                ORDER BY bkg_id DESC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
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

	public function actionTdsReverted()
	{
		$sql		 = "Select * from test.vndTdsDetails5 where tds_status = 0";
		$records	 = DBUtil::query($sql, DBUtil::SDB());
		$userInfo	 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$id			 = $row['id'];
				$vendorId	 = $row['adt_trans_ref_id'];
				$date		 = $row['act_date'];
				$amount		 = $row['act_amount'];
				$remarks	 = "Provisional TDS reverted for trip - " . $row['act_ref_id'];
				$tripid		 = $row['act_ref_id'];
				$adtParam	 = '{"type":"TDS_REVERTED"}';

				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_TRIP, $tripid, Accounting::LI_TDS, $amount, $remarks, 0, null, null, $adtParam);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $amount), $remarks, 0, null, null, $adtParam);
				$success			 = AccountTransactions::model()->add($accTransDetArr, $date, $amount, $tripid, Accounting::AT_TRIP, $remarks, $userInfo);

				if ($success)
				{
					$query = "UPDATE test.vndTdsDetails5 SET tds_status=1 WHERE id=$id";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
				}
				echo "\r\nDone===id=" . $id . "== vendorId =" . $vendorId . "== amount =" . $amount;
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionInsertDURP1Day()
	{
		$begin	 = new DateTime("2022-07-18 00:00:00");
		$end	 = new DateTime("2021-12-03 00:00:00");
		for ($j = $begin; $j >= $end; $j->modify('-1 day'))
		{
			$date				 = $j->format("Y-m-d");
			$fromDate			 = $date . " 00:00:00";
			$toDate				 = $date . " 23:59:59";
			$param				 = array();
			$param['fromDate']	 = $fromDate;
			$param['toDate']	 = $toDate;
			Logger::writeToConsole($date);
			$sql				 = "SELECT 
                    SUM(TEMP.bookingcnt) AS bookingcnt,
                    SUM(TEMP.Vendorcnt) AS Vendorcnt,
                    TEMP.zon_id,
                    TEMP.zon_name,
                    TEMP.createDate,
                    TEMP.updateDate
                    FROM 
                    (
                        SELECT 
                        COUNT(bkg_id) AS bookingcnt,
                         0 AS Vendorcnt,
                        zones.zon_id,
                        zones.zon_name,
                        '$date' AS createDate,
                        '$date' AS updateDate
                        FROM  booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id
                        INNER JOIN cities ON cities.cty_id=booking.bkg_from_city_id
                        INNER JOIN zone_cities ON zone_cities.zct_cty_id=cities.cty_id
                        INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id 
                        WHERE 1 
                        AND booking.bkg_status IN (6,7) 
                        AND booking.bkg_pickup_date BETWEEN  :fromDate AND  :toDate
                        AND booking.bkg_active=1
                        GROUP BY zones.zon_id 

                        UNION 

                        SELECT 
                         0 AS bookingcnt,
                        COUNT(apt_entity_id) AS Vendorcnt,
                        zones.zon_id,
                        zones.zon_name,
                        '$date' AS createDate,
                        '$date' AS updateDate
                        FROM  booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id
                        INNER JOIN cities ON cities.cty_id=booking.bkg_from_city_id
                        INNER JOIN zone_cities ON zone_cities.zct_cty_id=cities.cty_id
                        INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id 
                        INNER JOIN zone_vendor_map ON zone_vendor_map.zvm_zon_id= zones.zon_id AND  zone_vendor_map.zvm_zone_type=1
                        INNER JOIN app_tokens ON app_tokens.apt_entity_id=zone_vendor_map.zvm_vnd_id 
                        WHERE 1 
                        AND booking.bkg_status IN (6,7) 
                        AND zone_vendor_map.zvm_active=1
                        AND booking.bkg_pickup_date BETWEEN  :fromDate AND  :toDate
                        AND app_tokens.apt_user_type =2 
                        AND app_tokens.apt_date  BETWEEN  :fromDate AND  :toDate
                        AND booking.bkg_active=1
                        GROUP BY zones.zon_id
                    ) TEMP WHERE 1 GROUP BY TEMP.zon_id";
			$details			 = DBUtil::query($sql, DBUtil::SDB(), $param);
			foreach ($details as $row)
			{
				try
				{
					$sqldata = "INSERT INTO `dynamic_uncommon_route_1day` (`dur_zone_id`,`dur_zone_name`,`dur_booking_count`, `dur_vendor_count`, `dur_createdate`, `dur_updatedate`, `dur_active`) VALUES 
                                ('" . $row['zon_id'] . "','" . $row['zon_name'] . "','" . $row['bookingcnt'] . "','" . $row['Vendorcnt'] . "', '" . $row['createDate'] . "', '" . $row['updateDate'] . "', '1')";
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
				}
			}
		}
	}

	public function actionUpdateUserQRCode()
	{
		$check = Filter::checkProcess("onetime updateUserQRCode");
		if (!$check)
		{
			return;
		}
		Users::updateQRCodeById(10);
	}

	public function actionTestQR()
	{
		$qrLink			 = 'http://www.aaocab.com?sid=CX220731818&loc=1';
		$qrLink			 = 'Test';
		$uniqueQrCode	 = 'CX220731818';
		$contactId		 = 1304771;

		QrCode::generateCode($qrLink, $uniqueQrCode, $contactId);
	}
	
	public function actionEverestFleetQR()
	{
		$qrLink			 = 'https://gozo.cab/c/gozo-vndfleet';
		$uniqueQrCode	 = 'gozo-vndfleet';
		$contactId		 = 1116119;

		QrCode::generateCode($qrLink, $uniqueQrCode, $contactId);
	}

	/**
	 * 
	 * @throws Exception
	 */
	public function actionCorrectionPhnCode()
	{
		$sql = "SELECT * FROM `contact_phone` WHERE LENGTH(phn_phone_no)>10 AND phn_phone_country_code = 91 AND phn_active=1";
		$res = DBUtil::query($sql);
		if ($res)
		{
			foreach ($res as $row)
			{

				try
				{
					$phoneID = $row['phn_id'];
					$phoneNo = trim($row['phn_phone_no']);
					$phoneNo = ($phoneNo[0] != '+') ? ('+' . $phoneNo) : ($phoneNo);
					echo "==phn_id:" . $phoneID . "==phoneNo:" . $phoneNo;

					$isPhoneValid = Filter::validatePhoneNumber($phoneNo);
					if ($isPhoneValid)
					{

						Filter::parsePhoneNumber($phoneNo, $code, $phone);

						echo "====code:" . $code . "==Number:" . $phone;
					}
					echo "\r\n";
					if ($code > 0 && $phone > 0)
					{
						$phnModel							 = ContactPhone::model()->findByPk($phoneID);
						$phnModel->phn_phone_no				 = $phone;
						$phnModel->phn_phone_country_code	 = $code;
						if (!$phnModel->save())
						{
							throw new Exception($phnModel->getErrors());
						}
					}
				}
				catch (Exception $ex)
				{
					echo "\r\n" . $ex->getMessage();
				}
			}
		}
	}

	/**
	 * 
	 * @throws Exception
	 */
	public function actionUpdateAdditionalParamJson()
	{
		$returnSet	 = new ReturnSet();
		$sql		 = "Select DISTINCT qrc_agent_id from qr_code WHERE qrc_active =1 AND qrc_agent_id > 0";
		$records	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $record)
		{
			$agentId		 = $record['qrc_agent_id'];
			$partnerRuleData = "Select prc_agent_id, GROUP_CONCAT(IF(prc_booking_type = 1,prc_id,null)) outstation,
								GROUP_CONCAT(IF(prc_booking_type = 2,prc_id,null)) local from partner_rule_commission
								WHERE prc_active = 1 AND prc_agent_id =:agentId AND prc_agent_Id NOT IN(39179) GROUP BY prc_agent_id";
			$dataRow		 = DBUtil::queryRow($partnerRuleData, DBUtil::SDB(), ['agentId' => $agentId]);

			if ($dataRow['prc_agent_id'] != '')
			{
				$ptsModel = PartnerSettings::model()->find('pts_agt_id = :agentid', array('agentid' => $agentId));
				if ($ptsModel)
				{
					$ptsAdditionalObj = json_decode($ptsModel->pts_additional_param);

					$outCommissionId = $ptsAdditionalObj->outstation->id;

					$ptsAdditionalObj->outstation->id				 = $dataRow['outstation'];
					$ptsAdditionalObj->outstation->commissionType	 = 2;
					$ptsAdditionalObj->outstation->commissionValue	 = 100;

					$localCommissionId = $ptsAdditionalObj->local->id;

					$ptsAdditionalObj->local->id				 = $dataRow['local'];
					$ptsAdditionalObj->local->commissionType	 = 2;
					$ptsAdditionalObj->local->commissionValue	 = 100;
					$jsonAdditionalObj							 = json_encode($ptsAdditionalObj);

					$ptsModel->pts_additional_param	 = $jsonAdditionalObj;
					$transaction					 = DBUtil::beginTransaction();
					try
					{
						if (!$ptsModel->save())
						{
							throw new Exception($qrModel->getErrors());
						}
						DBUtil::commitTransaction($transaction);
						echo "\r\nAGENT ID: {$agentId}";
					}
					catch (Exception $ex)
					{
						echo "\r\n" . $ex->getMessage();
						$returnSet->setStatus(false);
						DBUtil::rollbackTransaction($transaction);
					}
				}
			}
		}
	}

	public function actionCorrectISDCode()
	{
		$sql = "SELECT phn_id, phn_contact_id, u.user_id, u.usr_country_code, u.usr_mobile, phn_phone_country_code, phn_phone_no,
					u.usr_mobile_verify, cph.phn_is_verified
				FROM contact_phone cph
				INNER JOIN contact_profile cpr ON cph.phn_contact_id=cpr.cr_contact_id AND cph.phn_active=1 AND cpr.cr_status=1
				INNER JOIN users u ON u.user_id=cpr.cr_is_consumer
				WHERE cph.phn_phone_country_code='91' AND u.usr_country_code<>'' AND u.usr_country_code<>'+91' AND phn_is_verified=1
					AND u.usr_country_code IS NOT NULL AND cph.phn_phone_country_code<>u.usr_country_code AND cph.phn_phone_no=u.usr_mobile 
				";

		$res = DBUtil::query($sql, DBUtil::SDB());

		foreach ($res as $row)
		{
			$phnId		 = $row["phn_id"];
			$phnCode	 = $row["phn_phone_country_code"];
			$phnNumber	 = $row["phn_phone_no"];
			$usrCode	 = $row["usr_country_code"];
			$usrNumber	 = $row["usr_mobile"];

			$fullUsrNumber	 = $usrCode . $usrNumber;
			$phoneNo		 = Filter::processPhoneNumber($fullUsrNumber);

			$phoneNo1 = Filter::processPhoneNumber($phnNumber);

			if ((!$phoneNo && !$phoneNo1) || $phoneNo1 !== false || $phoneNo == false)
			{
				continue;
			}

			$obj = Filter::parsePhoneNumber($phoneNo, $code, $number);

			$phnModel							 = ContactPhone::model()->findByPk($phnId);
			$phnModel->phn_phone_country_code	 = $code;
			$phnModel->phn_phone_no				 = $number;
			$phnModel->save();

			$desc = "{$usrCode}-{$usrNumber} | {$phnCode}-{$phnNumber} => {$phnModel->phn_phone_country_code}-{$phnModel->phn_phone_no}";
			Logger::writeToConsole($desc);
		}
	}

	public function actionCheckVerifiedPhone()
	{
		$sql = "SELECT phn_id, phn_phone_country_code, phn_phone_no
				FROM contact_phone cph
				WHERE phn_is_verified=0 AND phn_active=1
				";

		$res = DBUtil::query($sql, DBUtil::SDB());

		foreach ($res as $row)
		{
			$phnId		 = $row["phn_id"];
			$phnCode	 = $row["phn_phone_country_code"];
			$phnNumber	 = $row["phn_phone_no"];

			$fullUsrNumber = $phnCode . $phnNumber;

			$phoneNo = Filter::processPhoneNumber($fullUsrNumber);

			$phoneNo1 = Filter::processPhoneNumber($phnNumber);

			if ($phoneNo1 !== false || $phoneNo !== false)
			{
				continue;
			}

			$phnModel				 = ContactPhone::model()->findByPk($phnId);
			$phnModel->phn_active	 = 8;
			$phnModel->save(false);

			$params	 = ["phnId" => $phnId];
			$sql	 = "UPDATE contact_phone SET phn_active=8 WHERE phn_id=:phnId";
			$numrows = DBUtil::execute($sql, $params);

			$desc = "{$phnId} : $numrows - {$phnCode}-{$phnNumber} => {$phnModel->phn_verified_date} - " . json_encode($phnModel->getErrors());
			Logger::writeToConsole($desc);
		}
	}

	/**
	 * function used for activate customer
	 * @throws Exception
	 */
	public function actionActivateCustomerQr()
	{
		$sql	 = "Select * from test.cx_qr WHERE status =0 LIMIT 0,50";
		$records = DBUtil::query($sql);
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$entityId	 = 77;
				$entity_type = 4;
				$adminId	 = 77;

				$number	 = trim($row['QRNumber']);
				$name	 = trim($row['UserName']);
				$email	 = trim($row['UserEmail']);
				$mobile	 = trim($row['UserMobile']);

				$sql1		 = "SELECT qrc_id from qr_code WHERE qrc_status IN (1,2) AND qrc_code = '{$number}'";
				$records1	 = DBUtil::queryRow($sql1);
				if (!$records1 || empty($records1))
				{
					$message = "No Qr number exist" . $number;
					throw new Exception($message);
				}
				$qrId = $records1['qrc_id'];

				$jsonObjAssign = [
					"qr_contact_name"	 => $name,
					"qr_contact_number"	 => $mobile,
					"qr_id"				 => $qrId,
					"qr_loc_lat"		 => null,
					"qr_loc_long"		 => null,
					"qr_loc_name"		 => null,
					"qr_email"			 => $email];

				$activate = QrCode::addActivation($jsonObjAssign, $entityId, $number);
				DBUtil::commitTransaction($transaction);
				if (!$activate)
				{
					$message = "Activation not done successfully";

					throw new Exception($message);
					throw new Exception($message);
				}
				else
				{
					$qrModel = QrCode::model()->findByPk($qrId);
					$userId	 = ContactProfile::getUserByAgentId($qrModel->qrc_agent_id);
					$params	 = ["userId" => $userId, "userType" => 1, "qrId" => $qrId];
					$where	 = " AND qrc_id=:qrId";

					$sql	 = "UPDATE qr_code qrc SET  qrc.qrc_ent_type = :userType , qrc_ent_id =:userId WHERE  1 $where";
					$result	 = DBUtil::command($sql)->execute($params);
				}

				$qry = "UPDATE  test.cx_qr SET  status = 1 WHERE QRNumber = '$number'";
				DBUtil::execute($qry);

				//DBUtil::commitTransaction($transaction);

				$message = "\r\nAllocation and activation done successfully for - {$number}";
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$qry = "UPDATE  test.cx_qr SET  status = 2 WHERE QRNumber = '$number'";
				DBUtil::execute($qry);
				echo "\r\nError == " . $ex->getMessage();
			}
		}
	}

	public function actionGozoCoinLedgerChange()
	{
		$sql	 = "SELECT * FROM test.gozoCoinLedger1 WHERE gcl_status = 0";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$adtId					 = $row['adt_0'];
				$adtModel				 = AccountTransDetails::model()->findByPk($adtId);
				$adtModel->adt_ledger_id = Accounting::LI_PROMOTIONS_MARKETING;
				$success				 = $adtModel->save();

				if ($success)
				{
					$adtId	 = $row['adt_0'];
					$query	 = "UPDATE test.gozoCoinLedger1 SET gcl_status=1 WHERE adt_0=$adtId";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					throw new Exception($adtModel->getErrors());
				}
				echo "\r\nDone AdtId: {$adtId}";
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				echo "\r\nError AdtId: {$adtId}, Error == " . $ex->getMessage();
			}
		}
	}

	public function actionPartnerBadDeptandCompensationEntry()
	{
		$sql	 = "SELECT AgentID,Balance,status,take_action,Compensation_BadDebt FROM test.channel_partner_closing_balance WHERE status = 0 AND take_action IN(1,2)";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$take_action = $row['take_action'];
				if ($take_action == 1)
				{
					$date = '2021-03-31 00:00:00';
				}
				else
				{
					$date = '2021-04-01 00:00:00';
				}
				$amount		 = -1 * $row['Balance'];
				$remarks	 = "Accounting adjustment";
				$partnerId	 = $row['AgentID'];

				$userInfo		 = UserInfo::getInstance();
				$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_PARTNER, $date, $amount, $remarks, $partnerId, $userInfo);
				$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNER, Accounting::AT_PARTNER, $partnerId, '', $remarks);
				if ($take_action == 1)
				{
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_BAD_DEBT, Accounting::AT_PARTNER, $partnerId, '', $remarks);
				}
				else
				{
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_COMPENSATION, Accounting::AT_PARTNER, $partnerId, '', $remarks);
				}
				$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_PARTNER);

				if ($status)
				{
					$query = "UPDATE test.channel_partner_closing_balance SET status=1 WHERE  AgentID =$partnerId AND status =0";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
				}
				echo "<br>Done Agent_ID===" . $partnerId . "===amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionVendorBadDeptandCompensationEntry()
	{
		$sql	 = "SELECT VendorID,Balance,status,take_action,Compensation_BadDebt FROM test.vendorsclosingbalance WHERE status = 0 AND take_action IN(1,2)";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$take_action = $row['take_action'];
				if ($take_action == 1)
				{
					$date = '2021-03-31 00:00:00';
				}
				else
				{
					$date = '2021-04-01 00:00:00';
				}
				$amount			 = -1 * $row['Balance'];
				$remarks		 = "Accounting adjustment";
				$vndId			 = $row['VendorID'];
				$take_action	 = $row['take_action'];
				$userInfo		 = UserInfo::getInstance();
				$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndId, $userInfo);
				$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndId, '', $remarks);
				if ($take_action == 1)
				{
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_BAD_DEBT, Accounting::AT_OPERATOR, $vndId, '', $remarks);
				}
				else
				{
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_COMPENSATION, Accounting::AT_OPERATOR, $vndId, '', $remarks);
				}
				$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);

				if ($status)
				{
					$query = "UPDATE test.vendorsclosingbalance SET status=1 WHERE  VendorID =$vndId AND status =0";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
				}
				echo "<br>Done VendorID===" . $vndId . "===amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionVndPaymentBankcharge()
	{
		$sql	 = "SELECT * FROM test.vendorPaymentBankCharges WHERE vpbc_staus=0";
		$rows	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($rows as $data)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$actid			 = $data['act_id'];
				$bankLedgerID	 = $data['apg_ledger_id'];
				$payeeLedgerID	 = Accounting::LI_OPERATOR;
				$bankRefId		 = $data['apg_id'];
				$payeeRefId		 = $data['apg_trans_ref_id'];
				$accType		 = Accounting::AT_ONLINEPAYMENT;
				$addtParams		 = $data['apg_remarks'];
				$bankCharge		 = ($data['adt_amount'] * -1);

				$qry	 = "UPDATE `account_transactions` SET `act_active` = 0 WHERE `account_transactions`.`act_id` = $actid";
				$success = DBUtil::execute($qry);
				if ($success)
				{
					$acctrans				 = new AccountTransactions();
					$acctrans->act_amount	 = $data['act_amount'];
					$acctrans->act_date		 = $data['act_date'];
					$acctrans->act_ref_id	 = $payeeRefId;
					$acctrans->act_type		 = Accounting::AT_OPERATOR;
					$userInfo				 = UserInfo::model(UserInfo::TYPE_VENDOR, UserInfo::getEntityId());
					$acctrans->AddVendorReceipt($bankLedgerID, $payeeLedgerID, $bankRefId, $payeeRefId, $addtParams, $accType, $bankCharge, $userInfo);

					$query = "UPDATE test.vendorPaymentBankCharges SET vpbc_staus=1 WHERE  act_id = $actid AND vpbc_staus =0";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);

					echo "\r\nDone act_id===" . $actid . "===AMOUNT===" . $data['act_amount'] . "BANKCHARGE" . $bankCharge;
				}
				else
				{
					throw new Exception("act_id===" . $actid . "===AMOUNT===" . $data['act_amount'] . "BANKCHARGE" . $bankCharge);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionUnfreezeVendor()
	{
		$vndIds = '2640,78182,35180,57518,33793,47140,28898,62692,43,13297,32293,27178,20459,40456,25674,17277,75761,58207,33227,48860,27029,38471,6319,226,5587,30463,14631,27506,18501,20947,28474,59087,5746,48397,69880,61550,41089,2770,30386,30272,25517,9373,34108,8455,31085,16115,37626,12106,1055,34746,69268,69517,57416,37173,32259,10154,66639,4735,4741,22145,25383,30189,11462,20179,33690,30901,33491,44241,68529,5499,10462,5080,75690,28483,74976,36605,77089,38275,76165,44906,76745,57731,40107,5972,35728,50488,76522,34795,63277,61798,57282,32074,77121,57508,60196,78277,1305,907,215,913,3293,11284,2577,16415,8132,20093,11930,13229,29506,20491,12628,32295,4887,8518,38483,38130,37458,40438,14207,37080,33529,27649,25724,4795,48196,35679,59052,33082,37371,58200,60645,35062,11452,17999,45747,28079,50441,58318,58124,63591,62068,31177,59843,61357,29657,44752,63917,30294,17951,73741,75171,58902,61961,36563,18969,1023,173,8060,13285,29946,77990,30427,7032,58550,46735,65994,65164,67231,47306,69977,73176,68917,58353,56941,47696,34484,70884,32788,8731,40761,26751,912,65624,9620,66717,76323,76438,67186,58717,37511,64035,39733,66047,27315,77302,61881,59487,62623,73391,32025,64178,44224,66970,5877,43784,34357,69232,74439,69099,76520,56933,41223,26234,33951,21717,77419,19285,72490,64488,20175,39301,70083,66109,77653,50866,62929,74641,44507,75954,72286,25374,43220,33150,3030,10429,78720,67157,4623,74095,57838,58118,63945,34458,51073,45637,8298,224,746,630,1839,2598,678,794,1676,2848,356,4002,6540,3230,4676,5559,4781,6802,2515,180,15687,16699,2296,19283,1377,12102,5668,26272,1112,15215,25526,2503,4182,10465,835,6654,6227,16015,7913,26641,25296,17617,15799,9437,24437,5717,31817,4028,1110,10236,22461,28164,13069,3549,23085,11730,9655,3725,29725,20695,3848,27965,27740,11704,12318,32171,2836,15909,28697,21983,25521,34215,37662,40643,24627,1378,943,5004,20301,20367,39972,38186,35709,37155,34035,29536,24829,45884,46286,41013,10407,10761,32063,33658,8331,29012,48906,735,29233,4403,10426,29003,42162,7531,27060,10362,19921,35196,36344,2145,15507,58138,39208,46245,22267,32313,8952,27087,44390,46506,28924,60606,58760,48100,33309,41895,36417,4073,29144,25118,5076,9122,39170,37630,45697,64215,57926,30834,58126,32232,5599,75214,14889,46910,10290,45360,33085,823,329,1684,1415,722,1494,2167,204,3785,1563,2187,4642,1790,5703,8159,1562,2767,8373,8153,5418,5341,8746,3998,1768,9313,12686,14569,9802,7028,25994,2924,5088,8782,18069,4509,3396,8084,48532,8249,12929,17073,14145,4511,10476,2406,28032,18993,17681,12481,11833,30494,17567,18445,33273,27675,34488,27758,29032,34062,36107,33298,30093,28220,15133,27336,28132,38089,12842,44002,46012,63444,64858,65722,65223,48068,63767,65558,59889,65259,61753,59717,68721,41361,50439,69037,66874,73300,59643,72657,66786,21439,63460,281,2519,1274,2513,62722,3525,18741,40988,35431,37051,30338,22931,1461,29693,65,769,658,1353,1311,13001,3768,15583,8566,21157,3421,13555,13421,25864,23873,11495,21753,3228,10089,13378,25773,28717,20659,27592,10666,29159,5900,28187,17669,27047,25797,26240,20809,3865,27009,6561,13306,16617,11394,3640,27096,28158,255,894,3511,10971,10816,20795,25175,26204,7447,66659,3975,14043,19253,18195,27884,67086,25375,2262,10832,26375,46310,75055,15353,58002,23903,29698,63667,8125,17211,37094,9846,15497,13447,13339,5087,7411,218,237,666,751,777,832,915,975,906,1060,1057,101,719,1045,970,75,1201,228,973,1239,1925,923,2114,2172,2155,1484,2341,1350,1349,1033,2505,2516,2716,2468,1448,1337,2857,100,311,2931,1121,2056,1845,2550,3255,2617,1354,2389,3664,3612,2782,1458,950,3694,4274,4421,3682,2563,4521,2601,4805,4786,4545,1200,4723,3943,2586,5189,5531,5074,3642,5568,2769,5130,5588,6003,5903,5086,4510,6893,2967,7255,1800,5411,3312,7500,6631,3544,2538,6102,3907,3529,2674,9695,7856,3212,2274,1405,4946,4604,10224,10052,10639,2349,1858,11264,5742,5440,10112,6402,10322,7462,6923,3443,711,9477,7882,3318,2057,7681,936,14615,4986,10640,9278,1106,11140,5466,4163,1431,6740,9809,3610,17375,11388,6833,10829,12994,22783,23107,13699,17173,869,20731,12739,24983,6104,9131,10484,25269,25311,1001,24635,5707,25534,25929,19815,25233,24009,24909,3395,26732,11226,14147,5573,7074,10072,27027,16605,3493,21459,26765,8889,26074,17659,20797,7086,11385,7076,28210,10326,8940,8533,5151,27467,29590,29688,6502,17225,3419,10195,11160,7750,16065,9419,13367,17963,29551,5040,10890,3795,29585,28777,29133,5921,23389,10381,15061,15553,24968,20823,29024,16039,26809,18139,24803,12803,25893,28923,12959,12245,29090,28540,22851,26878,29589,29756,2270,28317,4572,11969,16935,28839,26405,26691,26503,12307,2709,30161,27460,17407,18115,8842,28073,26009,27451,28114,23421,12515,8865,28487,327,13296,23295,18635,28263,28916,12559,23813,9502,29768,22803,3933,15791,10733,24651,12894,28639,9163,26400,26353,23229,10521,28143,5648,28159,16211,28284,23463,12790,2281,4497,1889,25801,1609,24974,25465,16811,14669,8062,259,25648,125,27218,27646,663,726,1159,1242,257,1504,1482,2266,2535,1052,1100,2430,2409,1286,1897,3316,3020,3083,1682,3190,3506,4554,1583,3665,1000,1343,4309,4475,4870,2683,5081,5593,4430,6012,5026,3390,3275,5980,3306,5359,5898,2401,5071,6342,7513,3265,3441,5878,7253,8856,7669,8446,3864,3565,1986,5974,10464,7517,11469,6500,10347,5291,3398,10431,10616,3495,12382,7167,9415,14007,5743,4650,5817,16353,3258,14905,19453,17251,16157,19151,13831,8349,4844,774,2919,6853,8824,8443,20135,26189,5866,6409,28699,21323,16797,21877,5584,2641,6381,4422,6846,6545,5208,12593,13633,17157,27017,9939,1172,5264,8727,10865,10193,8770,20775,22927,17145,26726,3454,27731,17847,28617,7533,14203,10495,4744,28854,8400,18545,11375,28623,29741,3957,18801,18157,21253,8800,27810,23567,11403,26075,16011,9797,3167,41002,22445,29268,13330,26178,27101,20751,26398,25178,8013,24871,9191,3186,8870,12117,12931,15153,27187,7617,127,28291,3164,24417,58266,192,35489,4613,47235,662,10882,14103,75774,21555,670,14715,74231,69279,2708,2912,5504,6676,6929,8034,8264,11499,11866,12968,13735,18509,19529,20335,25277,25327,26731,28084,28388,29180,29681,29747,30354,31478,31555,32575,34036,36372,36798,37194,37658,38432,38526,38664,38970,39391,39592,41910,42951,43011,43444,43570,43721,44030,45371,45449,46583,46692,47322,47329,47764,47766,48038,48740,48821,48967,49355,50388,56780,54652,58153,58395,58799,58918,59202,59360,59408,59919,60483,60495,60906,61113,61920,62311,62647,62748,62955,63381,63485,63650,63889,64005,64461,64695,65202,65265,65515,66217,66227,66386,66414,66538,66956,67080,67329,67341,67366,67407,67549,67764,68074,68108,68118,68233,68330,68480,68940,68944,69848,70141,70440,70597,71974,72699,72770,72772,74078,76373,1341,1634,4238,4855,5460,6117,7091,9462,10235,11174,11550,12736,12904,21167,25307,25862,25997,27142,27887,28883,29127,8231,9194,15623,15823,28290,30874,35067,36298,36650,38042,42884,46278,48809,50240,60147,64444,64694,69394,70688,72504,6367,8103,20217,23825,26813,26831,29720,30894,36244,36780,38550,40650,44629,44681,47161,48534,62062,63166,68387,74358,191,1018,2026,3078,12818,15317,24467,25036,26932,27164,29599,30418,32976,33774,38176,41164,44348,44475,44599,45618,45661,45669,47324,48851,58031,62835,64562,64746,65135,70609,77880,997,2326,2758,7528,7776,9450,12291,13435,15729,21053,30203,30607,30833,42709,45359,45957,46615,58030,59131,59536,61022,63680,66917,69423,69644,46,3427,4069,8705,10008,16689,19635,25002,26450,27328,27892,29631,30602,33458,36132,40815,58801,59509,61872,64129,67268,67859,67913,68458,70250,71535,73493,76112,76240,77372,77869,78729,78937,79229';

		$sqlUpd	 = "UPDATE vendor_pref SET vnp_is_freeze = 0, vnp_credit_limit_freeze = 0, vnp_cod_freeze = 0, vnp_manual_freeze = 0, vnp_low_rating_freeze = 0, vnp_doc_pending_freeze = 0 WHERE vnp_vnd_id IN ($vndIds)";
		$result	 = DBUtil::execute($sqlUpd);
		if ($result)
		{
			$vendorIds = explode(',', $vndIds);
			foreach ($vendorIds as $vendorId)
			{
				$desc = "Vendor unfreezed.";
				VendorsLog::model()->createLog($vendorId, $desc, UserInfo::getInstance(), VendorsLog::VENDOR_UNFREEZE, false, false);

				echo "\r\nVendor Freezed: " . $vendorId;
			}
		}
	}

	public function actionVendorCompensationEntry()
	{
		$sql = "SELECT 
					vnd.vnd_id,
					vrs.vrs_outstanding
					FROM test.vendorcompensationunfreezed vcf 
					INNER JOIN vendors vnd ON vnd.vnd_id = vcf.vnd_id 
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id 
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id 
					WHERE vcf.take_action IN(2) AND vnd.vnd_active = 1
					 AND vrs_outstanding >0
					AND vnp_is_freeze = 1 limit 0,1 ";

		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$date			 = '2022-09-13 10:00:00';
				$amount			 = -1 * $row['vrs_outstanding'];
				$remarks		 = "Accounting adjustment";
				$vndId			 = $row['vnd_id'];
				$userInfo		 = UserInfo::getInstance();
				$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndId, $userInfo);
				$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndId, '', $remarks);
				$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_COMPENSATION, Accounting::AT_OPERATOR, $vndId, '', $remarks);
				$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);
				$unfreez		 = "UPDATE vendor_pref SET vnp_is_freeze=0 WHERE vnp_vnd_id =$vndId ";
				$status1		 = DBUtil::execute($unfreez);
				if ($status && $status1)
				{
					$query = "UPDATE test.vendorcompensationunfreezed SET vnd_status=1 WHERE vnd_id =$vndId AND vnd_status =0";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
				}
				echo "\r\nDone VendorID===" . $vndId . "===amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "\r\nError == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionUpdateCriticalTripAmountNew()
	{
		BookingCab::updateCriticalTripAmountNew();
	}

	public function actionUpdateMaxAllowableVA($limit = 500)
	{
		$sql = "SELECT COUNT(1) FROM (
					SELECT bkg_id, UpdateGozoAmount(bkg_bcb_id) as isUpdated FROM booking 
					WHERE bkg_status IN (2,3,5) AND bkg_pickup_date>='2023-05-01' ) a WHERE isUpdated>0";

		$numrows = DBUtil::queryScalar($sql);

		Logger::writeToConsole("Rows updated: {$numrows}");

		$sql = "SELECT bkg_id, bcb_id, bcb_vendor_amount, SUM(bkg_gozo_amount-LEAST(IFNULL(bkg_credits_used,0), ROUND(0.15 * bkg_net_base_amount))) gozoAmount, SUM(bkg_net_base_amount) bkgNetBaseAmt, 
					((SUM(bkg_gozo_amount-LEAST(IFNULL(bkg_credits_used,0), ROUND(0.15 * bkg_net_base_amount))) / SUM(bkg_net_base_amount)) * 100) as margin, MAX(bkg_critical_score) AS criticalScore,
					bcb_max_allowable_vendor_amount, SUM(IF(bkg_critical_score>0.98, btr_dbo_amount, 0)) as dboAmount,
 					IF(MIN(bkg_pickup_date) BETWEEN '2023-05-06 00:00:00' AND '2023-05-08 00:00:00' AND bcb_additional_params IS NULL AND MIN(CalcWorkingMinutes(bkg_create_date, NOW())) > 120, 1, 0) AS overrideCS,
					IF(MAX(bpr.bkg_critical_assignment)=1, 2, IF(MAX(bpr.bkg_manual_assignment)=1,1,0)) AS criticalFlag, bkg_is_fbg_type
			FROM booking 
			JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg_id 
			JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id 
			JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id 
			JOIN booking_cab bcb ON bcb.bcb_id = bkg_bcb_id 
			WHERE  bkg_status = 2 AND bkg_reconfirm_flag = 1 AND bkg_pickup_date > NOW() AND bkg_critical_score IS NOT NULL 
			AND btr_is_bid_started = 1 AND bpr.bkg_is_fbg_type >=0 AND btr_stop_increasing_vendor_amount = 0 
			AND bkg_pickup_date < DATE_ADD(NOW(), INTERVAL 3 DAY)
			GROUP by bcb_id 
			HAVING criticalScore >= 0.5 LIMIT 0, $limit";

		$res = DBUtil::query($sql);

		foreach ($res as $row)
		{
			$log						 = [];
			$bcbId						 = $row['bcb_id'];
			BookingInvoice::updateGozoAmount($bcbId);
			$bkgNetBaseAmt				 = $row['bkgNetBaseAmt'];
			$gozoAmount					 = $row['gozoAmount'];
			$maxAllowableVendorAmount	 = $row['bcb_max_allowable_vendor_amount'];
			$vendorAmount				 = $row['bcb_vendor_amount'];
			$criticalFlag				 = $row['criticalFlag'];
			$criticalScore				 = $row['criticalScore'];
			$dboAmount					 = $row['dboAmount'];
			$isOverrideCS				 = $row['overrideCS'];
			$isFBG						 = $row['bkg_is_fbg_type'];
			$margin						 = 0.2;
			$bkgId						 = $row['bkg_id'];
			$validateMargin				 = 0;
			//incase of airport booking upd ate 
			$validateMaxAllowed			 = BookingCab::validateApBkgMaxAllowVndAmnt($bkgId);

			if ($isOverrideCS == 1)
			{
				$criticalScore = min(1, round($criticalScore * 1.1, 2));
				if ($criticalScore > 0.9)
				{
					$criticalFlag = 2;
				}
			}


			if ($validateMaxAllowed == 1)
			{
				$validateMargin = 0.05;
			}


			if ($criticalFlag == 2 && $criticalScore >= 1)
			{
				$margin = -0.08;
			}
			else if ($criticalFlag == 2 && $criticalScore >= 0.96)
			{
				$margin = -0.06;
			}
			else if ($criticalFlag == 2 && $criticalScore >= 0.92)
			{
				$margin = -0.03;
			}
			else if ($criticalFlag == 2)
			{
				$margin = 0;
			}


			if ($criticalFlag == 1 && $criticalScore >= 0.88)
			{
				$margin = 0.03;
			}
			else if ($criticalFlag == 1 && $criticalScore >= 0.82)
			{
				$margin = 0.06;
			}
			else if ($criticalFlag == 1)
			{
				$margin = 0.08;
			}


			if ($criticalFlag == 0 && $criticalScore >= 0.6)
			{
				$margin = 0.12;
			}
			else if ($criticalFlag == 0 && $criticalScore >= 0.72)
			{
				$margin = 0.10;
			}
			else if ($criticalFlag == 0 && $criticalScore >= 0.80)
			{
				$margin = 0.08;
			}
			if ($validateMargin > 0)
			{
				$margin = min($validateMargin, $margin);
			}
			if ($isFBG == 1)
			{
				$margin = max(0.05, $margin);
			}



			$log["margin"] = $margin;

			$minGozoAmt = round($bkgNetBaseAmt * $margin);
			if ($dboAmount != 0)
			{
				$minGozoAmt = round(min($minGozoAmt, $dboAmount * 0.5 * -1));
			}
			$log["minGozoAmt"] = $minGozoAmt;

			if ($gozoAmount <= $minGozoAmt)
			{
				//	Logger::writeToConsole(json_encode($row) . "\n" . json_encode($log));
				continue;
			}

			$incMaxVABy = min(($gozoAmount - $minGozoAmt), 700);

			$log["incMaxVABy"] = $incMaxVABy;

			$newMaxAllowableVA = max(($vendorAmount + $incMaxVABy), $maxAllowableVendorAmount);

			$log["newMaxAllowableVA"] = $newMaxAllowableVA;

			if ($newMaxAllowableVA <= $maxAllowableVendorAmount)
			{
				//		Logger::writeToConsole(json_encode($row) . "\n" . json_encode($log));
				continue;
			}

			//	Logger::writeToConsole(json_encode($row) . "\n" . json_encode($log));
			BookingCab::model()->setMaxAllowableVendorAmount($bcbId, $newMaxAllowableVA);

			BookingVendorRequest::autoVendorAssignments($bcbId);
		}
	}

	public function actionAutoVendorAssignments($bcbId = 0)
	{
		BookingVendorRequest::autoVendorAssignments($bcbId);
	}

	public function actionSetMaxOut($limit = 500)
	{
		$check = Filter::checkProcess("onetime setMaxOut");
		if (!$check)
		{
			return;
		}

		$sql = "SELECT bkg_bcb_id, bkg_id, bkg_pickup_date
				FROM booking 
				INNER JOIN booking_cab bcb ON bcb.bcb_id = bkg_bcb_id 
				INNER JOIN booking_trail btr ON btr.btr_bkg_id = bkg_id 
				INNER JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg_id 
				WHERE bkg_status = 2 AND bkg_reconfirm_flag = 1 AND bkg_pickup_date < DATE_ADD(NOW(), INTERVAL 2 DAY) 
				AND btr_is_bid_started = 1 AND bpr.bkg_is_fbg_type =0 AND btr_stop_increasing_vendor_amount = 0 
				AND bkg_critical_score > 0.8 AND bcb_is_max_out = 0 
				LIMIT 0, $limit";

		$result = DBUtil::query($sql);
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

	public function actionAutoFurForFreezeVendor()
	{
		$sql	 = "SELECT   
					vnd.vnd_id,
					vnd.vnd_name,
					vnd.vnd_code,
					ctt.ctt_id AS contact_id,
					SUM(atd.adt_amount) totTrans,
					MAX(vrs.vrs_last_bkg_cmpleted) AS last_trip_completed_date
					FROM  vendors vnd
					INNER JOIN vendor_stats vrs ON vrs.vrs_vnd_id = vnd.vnd_id
					INNER JOIN contact_profile AS ctp ON ctp.cr_is_vendor = vnd.vnd_id AND ctp.cr_status = 1
					INNER JOIN contact AS ctt	ON ctt.ctt_id = ctp.cr_contact_id AND ctt.ctt_active = 1 AND ctt.ctt_id = ctt.ctt_ref_code
					INNER JOIN vendor_pref vnp ON vnp.vnp_vnd_id = vnd.vnd_id
					INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = vnd.vnd_id AND atd.adt_active = 1
					INNER JOIN account_transactions act	ON act.act_id = atd.adt_trans_id AND atd.adt_ledger_id = 14 AND atd.adt_type = 2 AND act.act_active= 1
					WHERE 1 
					AND vnp_is_freeze=1 
					AND vnd.vnd_id = vnd.vnd_ref_code
					AND vrs.vrs_last_bkg_cmpleted BETWEEN '2018-01-01 00:00:00' AND '2020-12-31 23:59:59'
					GROUP BY vnd.vnd_ref_code
					HAVING totTrans >=1";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			try
			{
				ServiceCallQueue::autoFurForFreezeVendor($row);
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

	public function actionReleaseLockPayment()
	{
		$totAmount	 = 0;
		$userInfo	 = UserInfo::getInstance();

		$sql = "SELECT bkg_id, bkg_bcb_id, bcb_vendor_amount 
				FROM `booking` bkg 
				INNER JOIN booking_cab bcb ON bcb.bcb_id=bkg.bkg_bcb_id AND bcb_active=1 
				WHERE bkg.bkg_status IN (2,3,5,6,7) AND bcb.bcb_lock_vendor_payment = 1 
				AND bkg.bkg_agent_id = 34928 ORDER BY bkg_pickup_date ASC LIMIT 0, 50";
		$res = DBUtil::query($sql);
		if ($res)
		{
			foreach ($res as $row)
			{
				$bkgid		 = $row['bkg_id'];
				$bcbid		 = $row['bkg_bcb_id'];
				$statusType	 = 2;

				$model							 = BookingCab::model()->findByPk($bcbid);
				$model->bcb_lock_vendor_payment	 = $statusType;

				if ($model->save())
				{
					$totAmount += $row['bcb_vendor_amount'];

					$desc	 = "Payment Release (Manual) Booking Id - " . $bkgid;
					$eventid = BookingLog::RELEASED_PAYMENT;

					$params['blg_ref_id'] = $bkgid;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, false, $params);

					echo "\r\nBkgId: " . $bkgid;
				}

				if ($totAmount >= 15000)
				{
					echo "\r\nBREAKED";
					break;
				}
			}
		}
	}

	public function actionUpdateCommissionIBIBO()
	{
		$sql	 = "SELECT * FROM test.updateCommissionIBIBOaug WHERE status = 0";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$model			 = Booking::model()->findByPk($row['bkg_id']);
			$agentCommission = $model->bkgInvoice->bkg_partner_commission;
			//$model->calAgentCommission();

			if ($agentCommission > 0)
			{
				$addCommission = AccountTransactions::model()->AddCommission($model->bkg_pickup_date, $model->bkg_id, $model->bkg_agent_id, $agentCommission);
				if ($addCommission)
				{
					$bkg_id	 = $model->bkg_id;
					$query	 = "UPDATE test.updateCommissionIBIBOaug SET status = 1 WHERE bkg_id = $bkg_id";
					DBUtil::execute($query);
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);
					echo 'BookingId = ' . $row['bkg_id'] . ' Commission = ' . $agentCommission . ' Success = ' . $addCommission . '\n';
				}
			}
		}
	}

	public function actionUpdateRefundAmtForCancelBooking()
	{
//		$sql	 = "SELECT bkg_id,bkg_create_date,bkg_pickup_date,btr_cancel_date,bkg_net_advance_amount,bkg_cancel_id,
//					cnr_reason,TIMESTAMPDIFF(MINUTE,bkg_create_date,btr_cancel_date) cancelTimeDiff
//				FROM `booking`
//				INNER JOIN booking_invoice ON biv_bkg_id = bkg_id AND bkg_net_advance_amount > 0
//				INNER JOIN booking_trail ON btr_bkg_id = bkg_id
//				INNER JOIN cancel_reasons cnr ON cnr_id = bkg_cancel_id
//				WHERE `bkg_pickup_date` > '2022-08-09 00:00:00' AND `bkg_status` = 9 AND bkg_agent_id = 18190 
//				AND TIMESTAMPDIFF(MINUTE,bkg_confirm_datetime,btr_cancel_date) < 30";	
		$sql	 = "SELECT * FROM test.addRefundEventIBIBO WHERE status = 0 ";
		$result	 = DBUtil::query($sql);
		foreach ($result as $row)
		{
			$model		 = Booking::model()->findByPk($row['bse_bkg_id']);
			$dataArray	 = array('refundAmount' => $row['refund']);
			$refundData	 = CJSON::encode($dataArray);
			BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);
			$bkg_id		 = $row['bse_bkg_id'];
			$query		 = "UPDATE test.addRefundEventIBIBO SET status = 1 WHERE bse_bkg_id = $bkg_id";
			DBUtil::execute($query);
			echo "\r\nBkgId: " . $model->bkg_id;
		}
	}

	public function actionMobisignRemoveCanChargeAndRefund()
	{
		$sql	 = "SELECT bkg_id,can_charges FROM test.mobisigncancellationlist WHERE statusUpdate = 0  ORDER BY bkg_id ASC";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$removeCancellationCharge	 = AccountTransactions::removeCancelationCharge($row['bkg_id']);
			$dataArray					 = array('refundAmount' => $row['can_charges']);
			$refundData					 = CJSON::encode($dataArray);
			BookingScheduleEvent::add($row['bkg_id'], BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);

			if ($removeCancellationCharge)
			{
				$bkg_id	 = $row['bkg_id'];
				$query	 = "UPDATE test.mobisigncancellationlist SET statusUpdate = 1 WHERE bkg_id = $bkg_id";
				DBUtil::execute($query);
				echo "\r\nBkgId: " . $row['bkg_id'];
			}
		}
	}

	public function actionSetCoin()
	{
		$sql	 = "SELECT bkg_id FROM `booking` WHERE `bkg_pickup_date` >= '2022-10-01 00:00:00' AND bkg_status IN (6,7) ORDER BY bkg_id ASC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			$bkgId = $row['bkg_id'];

			VendorCoins::processCoinForBooking($bkgId);
		}
	}

	public function actionMobisignRemoveCanChargeBookingInvoice()
	{
		$sql	 = "SELECT bkg_id,can_charges FROM test.mobisigncancellationlist WHERE statusUpdate = 1 ORDER BY bkg_id ASC LIMIT 0,1";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$bkg_id	 = $row['bkg_id'];
			$query	 = "UPDATE `booking_invoice` SET `bkg_cancel_charge` = null WHERE `booking_invoice`.`biv_id` = $bkg_id";
			$result	 = DBUtil::execute($query);
			if ($result)
			{
				$query = "UPDATE test.mobisigncancellationlist SET statusUpdate = 2 WHERE bkg_id = $bkg_id";
				DBUtil::execute($query);
				echo "\r\nBkgId: " . $bkg_id;
			}
		}
	}

	/*
	 * update coin by 1000
	 */

	public function actionUpdatePromotionGiftCoin()
	{
		exit;
		$giftCoinAmount	 = 300;
		$userId			 = "";
		$getEligibleUser = UserCredits::getEligibleUser($userId, $giftCoinAmount);
		foreach ($getEligibleUser as $row)
		{
			$addToCoin	 = 1000;
			$validity	 = date('Y-m-d H:i:s', strtotime('+1 year'));
			$maxUseType	 = 1;
			UserCredits::addAmount($row['user_id'], 1, $addToCoin, "Promotional coins credited", $maxUseType, NULL, $validity);
			echo "\r\nUserID: " . $row['user_id'] . "==coin==" . $addToCoin;
		}
		Logger::writeToConsole('\r\n\r\nDONE actionUpdatePromotionGiftCoin');
	}

	public function actionUpdatePromotionGiftCoinUpdate()
	{

		$giftCoinAmount	 = 1000;
		$userId			 = "";
		$getEligibleUser = UserCredits::getEligibleUser($userId, $giftCoinAmount);
		foreach ($getEligibleUser as $row)
		{
			$userGozoCoin	 = $row['coinHave'] | 0;
			$addToCoin		 = ($userGozoCoin >= 0 && $userGozoCoin < 500) ? 1000 : 500;
			$validity		 = date('Y-m-d H:i:s', strtotime('+1 year'));
			$maxUseType		 = 1;
			UserCredits::addAmount($row['user_id'], 1, $addToCoin, "Promotional coins credited", $maxUseType, NULL, $validity);
			echo "UserID: " . $row['user_id'] . "\t ==existCoin   ==" . $userGozoCoin . " \t :: addedCoin==" . $addToCoin;
			echo "\r\n";
		}
		Logger::writeToConsole('\r\n\r\nDONE actionUpdatePromotionGiftCoin');
	}

	public function actionVendorCancelBookingRefundIBIBO()
	{
		$sql	 = "SELECT * FROM test.cancel_refund_ibibo WHERE status = 0";
		$result	 = DBUtil::query($sql);
		foreach ($result as $row)
		{
			try
			{
				$userInfo	 = UserInfo::getInstance();
				$model		 = Booking::model()->findByPk($row['bkg_id']);
				$refund		 = round($model->bkgInvoice->bkg_advance_amount * 0.105);
				$canCharge	 = ($model->bkgInvoice->bkg_advance_amount - $refund);
				$dataArray	 = array('refundAmount' => $refund);
				$refundData	 = CJSON::encode($dataArray);
				BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);
				$bkg_id		 = $model->bkg_id;
				AccountTransactions::removeRefund($bkg_id);
				AccountTransactions::removeCancelationCharge($bkg_id);
				$success	 = AccountTransactions::AddCancellationCharge($row['bkg_id'], $model->bkg_pickup_date, $canCharge, $userInfo);
				if ($success)
				{
					$query = "UPDATE test.cancel_refund_ibibo SET status = 1 WHERE bkg_id = $bkg_id";
					DBUtil::execute($query);
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);
					echo "\r\nBkgId: " . $row['bkg_id'];
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateB2cAdvance()
	{
		$sql	 = "SELECT act.act_id,bkg.bkg_id AS bkg_id,bkg.bkg_pickup_date,bv.bkg_advance_amount,act.act_amount AS actAmt
					FROM
						account_trans_details atd
					INNER JOIN account_transactions act ON
						atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND act.act_active = 1 AND atd.adt_ledger_id = 47 AND atd.adt_amount > 0
					INNER JOIN account_trans_details atd1 ON
						act.act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_type = 1 AND atd1.adt_ledger_id = 13 AND atd1.adt_amount < 0
					INNER JOIN booking bkg ON
						bkg.bkg_id = atd1.adt_trans_ref_id
					INNER JOIN booking_invoice bv ON
						bv.biv_bkg_id = atd1.adt_trans_ref_id
					INNER JOIN payment_gateway apg ON
						apg.apg_booking_id = atd1.adt_trans_ref_id AND apg.apg_status = 1
					WHERE
						bkg.bkg_pickup_date >= '2023-06-01 00:00:00' AND bkg.bkg_status IN(2, 3, 5, 6, 7, 9) AND
						atd.adt_id IS NOT NULL AND atd1.adt_id IS NOT NULL AND bv.bkg_advance_amount = 0";
		$result	 = DBUtil::query($sql);
		foreach ($result as $row)
		{
			try
			{
				if ($row['bkg_id'] > 0)
				{
					$model									 = Booking::model()->findByPk($row['bkg_id']);
					$model->bkgInvoice->bkg_advance_amount	 = round($row['actAmt']);
					$model->bkgInvoice->bkg_due_amount		 = round($model->bkgInvoice->bkg_total_amount - $row['actAmt']);
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update bkg_advance_amount :: Amount" . $row['actAmt'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionUpdateCommissionMOBISIGN()
	{
//		$sql	 = "SELECT bkg_id,ROUND(biv.bkg_partner_commission) commission,atd.adt_amount adtAmount 
//						FROM booking
//						INNER JOIN booking_invoice biv ON biv.biv_bkg_id = bkg_id
//						INNER JOIN account_trans_details atd ON atd.adt_trans_ref_id = bkg_id AND atd.adt_active = 1 
//							AND atd.adt_ledger_id = 35
//						WHERE bkg_agent_id=34928 AND bkg_status IN(6,7) 
//							AND bkg_create_date >= '2022-04-01 00:00:00' AND bkg_create_date <= '2022-10-31 23:59:59'
//						HAVING commission <> adtAmount";

		$sql = "SELECT
					bkg_id,
					ROUND(biv.bkg_partner_commission) commission,
					atd.adt_amount adtAmount
				FROM
					booking
				INNER JOIN booking_invoice biv ON
					biv.biv_bkg_id = bkg_id
				INNER JOIN account_trans_details atd ON
					atd.adt_trans_ref_id = bkg_id AND atd.adt_active = 1 AND atd.adt_ledger_id = 35
				WHERE
					bkg_agent_id = 34928 AND bkg_status IN(6,7) AND bkg_id IN(2901399,2905042,2905331,2913035,2914268,2914411,2916514,2917510,2918498,2918837,2919066,2919665,2920190,2920686,2921112,2921931,2923771,2924430,2924792,2924999,2925099,2925126,2925311,2925425,2925537)";

		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$model			 = Booking::model()->findByPk($row['bkg_id']);
			$agentCommission = $model->bkgInvoice->bkg_partner_commission;
			if ($agentCommission > 0)
			{
				$addCommission = AccountTransactions::model()->AddCommission($model->bkg_pickup_date, $model->bkg_id, $model->bkg_agent_id, $agentCommission);
				if ($addCommission)
				{
					//$bkg_id	 = $model->bkg_id;
					//$query	 = "UPDATE test.mobisign_bookings SET status = 1 WHERE bkg_id = $bkg_id";
					//DBUtil::execute($query);
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);
					echo 'BookingId = ' . $row['bkg_id'] . ' Commission = ' . $agentCommission . ' Success = ' . $addCommission . '\n';
				}
			}
		}
	}

	public function actionDefaultDependencyScore()
	{
		$sql = "SELECT vrs_vnd_id, vrs_vnd_total_trip, SUM(vrs_system_unassign_count+vrs_step1_unassign_count+vrs_step2_unassign_count) as total_unassign FROM vendor_stats  GROUP by vrs_vnd_id ";

		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			if ($row['vrs_vnd_total_trip'] == NULL && $row['vrs_vnd_total_trip'] == 0 && $row['total_unassign'] == NULL && $row['total_unassign'] == 0)
			{
				$vnd_id	 = $row['vrs_vnd_id'];
				$sql	 = " UPDATE  vendor_stats SET vrs_dependency=60 WHERE vrs_vnd_id = $vnd_id";
				DBUtil::execute($sql);
			}
		}
	}

	public function actionRedoRedeemCoins($limit = 200)
	{
		$sql = "SELECT DISTINCT act_id FROM test.RevertVendorCoins WHERE act_active=0 LIMIT 0, $limit";

		$res = DBUtil::query($sql);

		foreach ($res as $row)
		{
			$actId		 = $row["act_id"];
			$actModel	 = AccountTransactions::model()->findByPk($actId);
			$result		 = VendorCoins::redeemPenalty($actId, $actModel->act_ref_id);
			Logger::writeToConsole(json_encode($result));
		}
	}

	public function actionPartnerClosing()
	{
		$sql	 = "SELECT * FROM test.partnerClosing_1 WHERE status=0 limit 100";
		$results = DBUtil::query($sql, DBUtil::SDB());

		foreach ($results as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$crRefId	 = $row['partnerid'];
				$drRefId	 = $row['partnerid'];
				$crRefType	 = Accounting::AT_PARTNER;
				$drRefType	 = Accounting::AT_PARTNER;
				$crLedgerId	 = Accounting::LI_CLOSING;
				$drLedgerId	 = Accounting::LI_PARTNER;
				$crLedgerId1 = Accounting::LI_PARTNER;
				$drLedgerId1 = Accounting::LI_OPENING;
				#$amount		 = (($row['closingbalance'] < 0) ? ($row['closingbalance'] * -1) : $row['closingbalance']);
				#$amount		 = (($row['closingbalance'] < 0) ? $row['closingbalance'] : ($row['closingbalance'] * -1));
				#$amount		 = ($row['closingbalance'] * -1);
				$closingDate = '2021-03-31 23:59:59';
				$openingDate = '2021-04-01 00:00:00';
				$remarks	 = "Closing balance";
				$remarks1	 = "Opening balance";
				$success	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $drLedgerId, $crLedgerId, $row['closingbalance'], $closingDate, $remarks, $userInfo);
				$success1	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $drLedgerId1, $crLedgerId1, $row['closingbalance'], $openingDate, $remarks1, $userInfo);
				if (!$success || !$success1)
				{
					throw new Exception("\r\nFailed adt_trans_ref_id===" . $crRefId . "===Amount===" . $amount);
				}
				$query = "UPDATE test.partnerClosing_1 SET status=1 WHERE  partnerid = $crRefId AND status =0";
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
				echo "\r\nDone adt_trans_ref_id===" . $crRefId . "===Amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "\r\nError == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionPartnerWalletClosing()
	{
		$sql	 = "SELECT * FROM test.partnerWalletClosing_1 WHERE status=0 limit 1";
		$results = DBUtil::query($sql, DBUtil::SDB());

		foreach ($results as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$crRefId	 = $row['partnerid'];
				$drRefId	 = $row['partnerid'];
				$crRefType	 = Accounting::AT_PARTNER;
				$drRefType	 = Accounting::AT_PARTNER;

				$crLedgerId	 = Accounting::LI_CLOSING;
				$drLedgerId	 = Accounting::LI_PARTNERWALLET;

				$crLedgerId1 = Accounting::LI_PARTNERWALLET;
				$drLedgerId1 = Accounting::LI_OPENING;

				$amount		 = (($row['closingbalance'] < 0) ? ($row['closingbalance'] * -1) : $row['closingbalance']);
				$closingDate = '2021-03-31 23:59:59';
				$openingDate = '2021-04-01 00:00:00';
				$remarks	 = "Closing balance";
				$remarks1	 = "Opening balance";
				$success	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId, $drLedgerId, $amount, $closingDate, $remarks);
				$success1	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId1, $drLedgerId1, $row['closingbalance'], $openingDate, $remarks1);
				if (!$success || !$success1)
				{
					throw new Exception("\r\nFailed adt_trans_ref_id===" . $crRefId . "===Amount===" . $amount);
				}
				$query = "UPDATE test.partnerWalletClosing_1 SET status=1 WHERE  partnerid = $crRefId AND status =0";
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
				echo "\r\nDone adt_trans_ref_id===" . $crRefId . "===Amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "\r\nError == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionVenderClosing()
	{
		$sql	 = "SELECT * FROM test.vendorClosing_1 WHERE status=0 limit 1";
		$results = DBUtil::query($sql, DBUtil::SDB());

		foreach ($results as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$crRefId	 = $row['vendorid'];
				$drRefId	 = $row['vendorid'];
				$crRefType	 = Accounting::AT_OPERATOR;
				$drRefType	 = Accounting::AT_OPERATOR;
				$crLedgerId	 = Accounting::LI_CLOSING;
				$drLedgerId	 = Accounting::LI_OPERATOR;
				$crLedgerId1 = Accounting::LI_OPERATOR;
				$drLedgerId1 = Accounting::LI_OPENING;
				$amount		 = (($row['closingbalance'] < 0) ? ($row['closingbalance'] * -1) : $row['closingbalance']);
				$closingDate = '2021-03-31 23:59:59';
				$openingDate = '2021-04-01 00:00:00';
				$remarks	 = "Closing balance";
				$remarks1	 = "Opening balance";
				$success	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId, $drLedgerId, $amount, $closingDate, $remarks, $userInfo);
				$success1	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId1, $drLedgerId1, $row['closingbalance'], $openingDate, $remarks1, $userInfo);
				if (!$success || !$success1)
				{
					throw new Exception("\r\nFailed adt_trans_ref_id===" . $crRefId . "===Amount===" . $amount);
				}
				$query = "UPDATE test.vendorClosing_1 SET status=1 WHERE  vendorid = $crRefId AND status =0";
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
				echo "\r\nDone adt_trans_ref_id===" . $crRefId . "===Amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "\r\nError == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionArchive()
	{
		$archiveDB	 = 'gozo_archive';
		#$ledgerarr = [15,49,26,34,14];
		$ledgerarr	 = [14];
		foreach ($ledgerarr as $ledger)
		{
			AccountTransactions::model()->archiveDataByLedger($archiveDB, $ledger);
		}

		//AccountTransactions::model()->archiveDataPartnerWallet($archiveDB);
		//AccountTransactions::model()->archiveDataVendorLedger($archiveDB);
	}

	public function actionUpdateClosingBal()
	{
		$date = '2021-04-01 00:00:00';
		AccountTransactions::closeLedgerBalance($date, 34);
	}

	public function actionUpdateOutstanding()
	{
		try
		{
			$sql	 = "SELECT adt.adt_trans_ref_id
			FROM
				account_trans_details adt
			INNER JOIN account_transactions act ON act.act_id = adt.adt_trans_id
			INNER JOIN vendors vnd ON vnd.vnd_id = adt.adt_trans_ref_id AND vnd.vnd_ref_code = vnd.vnd_id
			WHERE adt.adt_ledger_id = 14 AND adt.adt_trans_ref_id IS NOT NULL
				 AND adt.adt_active = 1 
				 AND adt.adt_status = 1 
				 AND act.act_active = 1 
				 AND act.act_status = 1                     
			GROUP BY adt.adt_trans_ref_id limit 0,1";
			$result	 = DBUtil::query($sql, DBUtil::SDB(), $param);

			foreach ($result as $row)
			{
				VendorStats::updateOutstanding($row['adt_trans_ref_id']);
			}
		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();

			Logger::exception($ex);
		}
	}

	public function actionSendSMSForDCOApp($number = "9831100164")
	{
		for ($x = 0; $x < 10; $x++)
		{
			#$sql	 = "SELECT * FROM test.temp_contacts ORDER BY tpc_id ASC LIMIT 0, 1000";
			$sql	 = "SELECT * FROM test.sms_driver WHERE sent = 0 ORDER BY smd_id ASC LIMIT 0, 1000";
			$result	 = DBUtil::query($sql);
			foreach ($result as $data)
			{
				echo "\r\n" . $smd_id	 = $data['smd_id'];
				echo "\r\n" . $number	 = $data['phn_phone_no'];

				$ext = "91";
				#$number	 = "9831100164";
				$sms = new Messages();
				$msg = 'Dear Driver Partner, thank you for signing up. Download the app using https://c.gozo.cab/rMnyi and complete the profile to receive cab bookings - aaocab';
				$res = $sms->sendMessage($ext, $number, $msg, 0);

				echo "\r\n" . $sqlUpd = "UPDATE test.sms_driver SET sent = 1 WHERE smd_id = {$smd_id}";
				DBUtil::execute($sqlUpd);

				#$usertype	 = SmsLog::Driver;
				#$smstype	 = SmsLog::SMS_TUTORIAL_LINK;
				#smsWrapper::createLog($ext, $number, "", $msg, $res, $usertype, 0, $smstype, "1", "", 0);
				#var_dump($res);
			}
		}
	}

	public function actionEMTbookings()
	{
		$sql	 = "SELECT * FROM test.emtbooking where status = 0 limit 10";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$model			 = Booking::model()->findByPk($row['Booking_ID']);
			$bkg_pickup_date = $model->bkg_pickup_date;
			$bkg_agent_id	 = $model->bkg_agent_id;
			$canCharge		 = round($model->bkgInvoice->bkg_advance_amount - $model->bkgInvoice->bkg_refund_amount);
			$Cancel_Charge	 = $row['Cancel_Charge'];
			$refund			 = round($model->bkgInvoice->bkg_advance_amount - $Cancel_Charge);
			if ($canCharge != $Cancel_Charge)
			{
				AccountTransactions::removeRefund($row['Booking_ID']);
				AccountTransactions::removeCancellationCharge($row['Booking_ID']);
				if ($refund > 0)
				{
					$dataArray	 = array('refundAmount' => $refund);
					$refundData	 = CJSON::encode($dataArray);
					BookingScheduleEvent::add($row['Booking_ID'], BookingScheduleEvent::REFUND_PROCESS, "Refund on booking cancelation", $refundData);
				}
				if ($Cancel_Charge > 0)
				{
					AccountTransactions::AddCancellationCharge($row['Booking_ID'], $bkg_pickup_date, $Cancel_Charge);
				}

				$bkg_id	 = $row['Booking_ID'];
				$query	 = "UPDATE test.emtbooking SET status = 1 WHERE Booking_ID = $bkg_id";
				DBUtil::execute($query);

				BookingInvoice::updateGozoAmount($model->bkg_bcb_id);
			}

			echo "\r\nBkgId: " . $row['Booking_ID'];
		}
	}

	public function actionUpdateCommissionEMT()
	{
		$sql	 = "SELECT * FROM test.emtCommissionReportMayMid2024 WHERE status = 0";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$model			 = Booking::model()->findByPk($row['bkg_id']);
			$agentCommission = $row['bkg_partner_commission'];
			if ($agentCommission > 0)
			{
				$addCommission = AccountTransactions::model()->AddCommission($model->bkg_pickup_date, $model->bkg_id, $model->bkg_agent_id, $agentCommission);
				if ($addCommission)
				{
					$bkg_id	 = $model->bkg_id;
					$query	 = "UPDATE test.emtCommissionReportMayMid2024 SET status = 1 WHERE bkg_id = $bkg_id";
					DBUtil::execute($query);
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);
				}
				echo 'BookingId = ' . $row['bkg_id'] . ' Commission = ' . $agentCommission . ' Success = ' . $addCommission . '\n';
			}
		}
	}

	public function actionUpdatePartnerCommissionValue()
	{
		$sql	 = "SELECT * FROM test.patComm WHERE status = 0 Limit 10";
		$records = DBUtil::query($sql, DBUtil::SDB());

		foreach ($records as $row)
		{
			$bookingModel	 = Booking::model()->findByPk($row['bkg_id']);
			$amount			 = $bookingModel->bkgInvoice->bkg_partner_commission;

			$success = AccountTransactions::model()->AddCommission($bookingModel->bkg_pickup_date, $bookingModel->bkg_id, $bookingModel->bkg_agent_id, $amount);
			if ($success)
			{
				$bkgId	 = $bookingModel->bkg_id;
				$query	 = "UPDATE test.patComm SET status = 1 WHERE bkg_id = $bkgId";
				DBUtil::execute($query);
				BookingInvoice::updateGozoAmount($bookingModel->bkg_bcb_id);
			}
			echo '\r\nBookingId = ' . $row['bkg_id'] . ' Commission = ' . $amount . ' Success = ' . $success . '\n';
		}
	}

	public function actionSetVendorTDSData()
	{
		try
		{
			$transaction = DBUtil::beginTransaction();
			$sql		 = "SET @i:=0;
				INSERT into test.vendor_tds_sess_2022_2023(vts_id, vts_vnd_id, vts_tripamt, vts_tds_amount, vts_tds_paid, vts_create_date, vts_active) 
				SELECT @i:=@i+1 AS  vts_id, temps.* FROM (
					SELECT temp.vnd_ref_code as vts_vnd_id, tripamt, 
					SUM(temp.tdsamount) AS vts_tds_amount, 0,
					vts_create_date,
					vts_active
					FROM (
						SELECT
						vnd.vnd_id, vnd.vnd_ref_code, vnd.vnd_code, vnd.vnd_name,
						SUM(IF(atd.adt_ledger_id = 22, atd.adt_amount, 0)) AS tripamt,
						SUM(IF(atd.adt_ledger_id IN(55), (-1 * atd.adt_amount), 0)) AS tdsamount,
						NOW() as vts_create_date,
						'0' as vts_active
						FROM account_trans_details atd
						INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1 AND act.act_active = 1 AND act.act_status = 1 AND atd.adt_ledger_id IN(55,22) 
						INNER JOIN account_trans_details atd1 ON act.act_id = atd1.adt_trans_id AND atd1.adt_active = 1 AND atd1.adt_status = 1 AND atd1.adt_ledger_id = 14 
						INNER JOIN vendors AS vnd ON vnd.vnd_id = atd1.adt_trans_ref_id AND vnd.vnd_id = vnd.vnd_ref_code 
						WHERE act.act_date BETWEEN '2022-04-01 00:00:00' AND '2023-03-31 23:59:59' 
						GROUP BY atd1.adt_trans_ref_id
					) temp 
					GROUP BY temp.vnd_ref_code 
				) temps";
			DBUtil::command($sql)->execute();
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public function actionProcessVendorTDS()
	{
		$totalReunded		 = 0;
		$maxRefundAllowed	 = 100000;
		$transaction		 = null;
		$userInfo			 = null;
		$limit				 = 700;

		#$vndIds = " AND vndtds.vts_vnd_id IN (75406,6841) ";
		$vndIds = "";

		$sql = "SELECT vts_vnd_id, vts_tds_amount, vts_tds_paid, 
				DATE_FORMAT(vrs_last_trip_datetime, '%Y-%m-%d') trip_date, vrs_last_thirtyday_trips 
				FROM test.vendor_tds_sess_2022_2023 vndtds 
				INNER JOIN gozodb.vendor_stats vrs ON vrs_vnd_id = vts_vnd_id 
				WHERE vndtds.vts_tds_amount >= 1 AND vndtds.vts_active = 0 AND vndtds.vts_tripamt < 500000 {$vndIds} 
				ORDER BY trip_date DESC, vrs_last_thirtyday_trips DESC 
				LIMIT 0, $limit ";

		$result = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $val)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				if ($totalReunded >= $maxRefundAllowed)
				{
					break;
				}

				$totalReunded	 += $val['vts_tds_amount'];
				$deductAmount	 = $val['vts_tds_amount'];
				$vendorId		 = $val['vts_vnd_id'];

				$datetime			 = '2023-03-31 23:59:59';
				$remarks			 = "Provisional deducted amount for prospective TDS refunded";
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $deductAmount), $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_TDS, $deductAmount, $remarks);
				$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $deductAmount, $vendorId, Accounting::AT_OPERATOR, $remarks, UserInfo::model());
				if (!$success)
				{
					throw new Exception("Unable to refund TDS for Vendor $vendorId of amount ₹$deductAmount");
				}

				$sqlUpdate = "UPDATE test.vendor_tds_sess_2022_2023 
								SET vts_active=1, vts_tds_paid = {$deductAmount} 
								WHERE vts_vnd_id = {$vendorId}";
				DBUtil::command($sqlUpdate)->execute();
				DBUtil::commitTransaction($transaction);

				$message	 = "Provisional deducted amount of Rs. $deductAmount for prospective TDS refunded";
				$payLoadData = ['vendorId' => $vendorId, 'EventCode' => Booking::CODE_VENDOR_BROADCAST];
				$success	 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "TDS Refunded");

				echo "Success: Vendor $vendorId - " . $remarks . "\n\n";
			}
			catch (Exception $e)
			{
				echo $e->getMessage() . "\n\n";
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionUpdateAdtRemarks()
	{
		$limit	 = 10000;
		$sql	 = "SELECT adt_id, adt_remarks, adt_addt_params  
				FROM account_trans_details atd
				INNER JOIN account_transactions act ON atd.adt_trans_id = act.act_id AND atd.adt_active = 1 
					AND act.act_active = 1 AND act.act_status = 1 AND atd.adt_ledger_id IN (55)
				WHERE adt_ledger_id = 55 AND act.act_date BETWEEN '2022-04-01 00:00:00' AND '2023-03-31 23:59:59' 
				AND (adt_remarks LIKE '%TDS deducted against trip purchased%' AND adt_addt_params LIKE '%TDS deducted against trip purchased%') 
				ORDER BY adt_id ASC 
				LIMIT 0, $limit";

		$result = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $val)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$adtModel	 = AccountTransDetails::model()->findByPk($val['adt_id']);
				$refId		 = $adtModel->adt_trans_ref_id;
				$oldRemarks	 = "TDS deducted against trip purchased (" . $refId . ")";
				$newRemarks	 = "Provisional deduction for future possible TDS against trip purchased (" . $refId . ")";

				if ($val['adt_remarks'] == $oldRemarks)
				{
					$adtModel->adt_remarks = $newRemarks;
				}
				if ($val['adt_addt_params'] == $oldRemarks)
				{
					$adtModel->adt_addt_params = $newRemarks;
				}

				if (!$adtModel->save())
				{
					throw new Exception("Failed to update remarks.");
				}
				else
				{
					echo "\r\nRemarks update for trip Id: " . $refId . ", AdtId: " . $val['adt_id'];
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	/**
	 * Function for Archiving Backup Data From Tables
	 */
	public function actionArchiveBackup()
	{
		$check = Filter::checkProcess("onetime archiveBackup");
		if (!$check)
		{
			return;
		}

		$archiveDB		 = 'gozo_archive';
		$archiveBkpDB	 = 'archive_backup';

		$transaction = null;
		try
		{
			$i			 = 0;
			$chk		 = true;
			$totRecords	 = 100000;
			$limit		 = 500;
			while ($chk)
			{
				// Get Quote & Detail
				$transaction = DBUtil::beginTransaction();

				$sql	 = "SELECT GROUP_CONCAT(aat_id) as aat_id FROM (
						SELECT aat_id FROM " . $archiveDB . ".`agent_api_tracking`
						WHERE (aat_pickup_date IS NOT NULL AND (aat_pickup_date < '2022-03-31 23:59:59')) 
						LIMIT 0, $limit
					) as tmp";
				$resQ	 = DBUtil::queryScalar($sql, DBUtil::ADB());
				if (!is_null($resQ) && $resQ != '')
				{
					#echo "\n";
					#Logger::writeToConsole($resQ);

					$sql	 = "DELETE FROM " . $archiveBkpDB . ".`agent_api_tracking` WHERE aat_id IN ($resQ)";
					$rowsDel = DBUtil::command($sql, DBUtil::ADB())->execute();

					$sql	 = "INSERT INTO " . $archiveBkpDB . ".`agent_api_tracking` (SELECT * FROM " . $archiveDB . ".`agent_api_tracking` WHERE aat_id IN ($resQ))";
					$rows	 = DBUtil::command($sql, DBUtil::ADB())->execute();

					if ($rows > 0)
					{
						echo "\nDEL";
						$sql	 = "DELETE FROM " . $archiveDB . ".`agent_api_tracking` WHERE aat_id IN ($resQ)";
						$rowsDel = DBUtil::command($sql, DBUtil::ADB())->execute();
					}
				}
				DBUtil::commitTransaction($transaction);

				echo " = " . $i += $limit;
				if (($resQ <= 0) || $totRecords <= $i)
				{
					break;
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			echo "\r\n";
			DBUtil::rollbackTransaction($transaction);
			Logger::error('archiveData == Exception ' . $e->getMessage());
		}
	}

	/**
	 * Function for Archiving Backup Data From Tables
	 */
	public function actionDelArchiveBackup()
	{
		$resQ	 = "16211113,16211114,16211115,16211116,16211117,16211118,16211119,16211120,16211121,16211122,16211123,16211124,16211125,16211126,16211127,16211128,16211129,16211130,16211131,16211132,16211133,16211134,16211135,16211136,16211137,16211138,16211139,16211140,16211141,16211142,16211143,16211144,16211145,16211146,16211147,16211148,16211149,16211150,16211151,16211152,16211153,16211154,16211155,16211156,16211157,16211158,16211159,16211160,16211161,16211162,16211163,16211164,16211165,16211166,16211167,16211168,16211169,16211170,16211171,16211172,16211173,16211174,16211175,16211176,16211177,16211178,16211179,16211180,16211181,16211182,16211183,16211184,16211185,16211186,16211187,16211188,16211189,16211190,16211191,16211192,16211193,16211194,16211195,16211196,16211197,16211198,16211199,16211200,16211201,16211202,16211203,16211204,16211205,16211206,16211207,16211208,16211209,16211210,16211211,16211212,16211213,16211214,16211215,16211216,16211217,16211218,16211219,16211220,16211221,16211222,16211223,16211224,16211225,16211226,16211227,16211228,16211229,16211230,16211231,16211232,16211233,16211234,16211235,16211236,16211237,16211238,16211239,16211240,16211241,16211242,16211243,16211244,16211245,16211246,16211247,16211248,16211249,16211250,16211251,16211252,16211253,16211254,16211255,16211256,16211257,16211258,16211259,16211260,16211261,16211262,16211263,16211264,16211265,16211266,16211267,16211268,16211269,16211270,16211271,16211272,16211273,16211274,16211275,16211276,16211277,16211278,16211279,16211280,16211281,16211282,16211283,16211284,16211285,16211286,16211287,16211288,16211289,16211290,16211291,16211292,16211293,16211294,16211295,16211296,16211297,16211298,16211299,16211300,16211301,16211302,16211303,16211304,16211305,16211306,16211307,16211308,16211309,16211310,16211311,16211312,16211313,16211314,16211315,16211316,16211317,16211318,16211319,16211320,16211321,16211322,16211323,16211324,16211325,16211326,16211327,16211328,16211329,16211330,16211331,16211332,16211333,16211334,16211335,16211336,16211337,16211338,16211339,16211340,16211341,16211342,16211343,16211344,16211345,16211346,16211347,16211348,16211349,16211350,16211351,16211352,16211353,16211354,16211355,16211356,16211357,16211358,16211359,16211360,16211361,16211362,16211363,16211364,16211365,16211366,16211367,16211368,16211369,16211370,16211371,16211372,16211373,16211374,16211375,16211376,16211377,16211378,16211379,16211380,16211381,16211382,16211383,16211384,16211385,16211386,16211387,16211388,16211389,16211390,16211391,16211392,16211393,16211394,16211395,16211396,16211397,16211398,16211399,16211400,16211401,16211402,16211403,16211404,16211405,16211406,16211407,16211408,16211409,16211410,16211411,16211412,16211413,16211414,16211415,16211416,16211417,16211418,16211419,16211420,16211421,16211422,16211423,16211424,16211425,16211426,16211427,16211428,16211429,16211430,16211431,16211432,16211433,16211434,16211435,16211436,16211437,16211438,16211439,16211440,16211441,16211442,16211443,16211444,16211445,16211446,16211447,16211448,16211449,16211450,16211451,16211452,16211453,16211454,16211455,16211456,16211457,16211458,16211459,16211460,16211461,16211462,16211463,16211464,16211465,16211466,16211467,16211468,16211469,16211470,16211471,16211472,16211473,16211474,16211475,16211476,16211477,16211478,16211479,16211480,16211481,16211482,16211483,16211484,16211485,16211486,16211487,16211488,16211489,16211490,16211491,16211492,16211493,16211494,16211495,16211496,16211497,16211498,16211499,16211500,16211501,16211502,16211503,16211504,16211505,16211506,16211507,16211508,16211509,16211510,16211511,16211512,16211513,16211514,16211515,16211516,16211517,16211518,16211519,16211520,16211521,16211522,16211523,16211524,16211525,16211526,16211527,16211528,16211529,16211530,16211531,16211532,16211533,16211534,16211535,16211536,16211537,16211538,16211539,16211540,16211541,16211542,16211543,16211544,16211545,16211546,16211547,16211548,16211549,16211550,16211551,16211552,16211553,16211554,16211555,16211556,16211557,16211558,16211559,16211560,16211561,16211562,16211563,16211564,16211565,16211566,16211567,16211568,16211569,16211570,16211571,16211572,16211573,16211574,16211575,16211576,16211577,16211578,16211579,16211580,16211581,16211582,16211583,16211584,16211585,16211586,16211587,16211588,16211589,16211590,16211591,16211592,16211593,16211594,16211595,16211596,16211597,16211598,16211599,16211600,16211601,16211602,16211603,16211604,16211605,16211606,16211607,16211608,16211609,16211610,16211611,16211612,16211613,16211614,16211615,16211616,16211617,16211618,16211619,16211620,16211621,16211622,16211623,16211624,16211625,16211626,16211627,16211628,16211629,16211630,16211631,16211632,16211633,16211634,16211635,16211636,16211637,16211638,16211639,16211640,16211641,16211642,16211643,16211644,16211645,16211646,16211647,16211648,16211649,16211650,16211651,16211652,16211653,16211654,16211655,16211656,16211657,16211658,16211659,16211660,16211661,16211662,16211663,16211664,16211665,16211666,16211667,16211668,16211669,16211670,16211671,16211672,16211673,16211674,16211675,16211676,16211677,16211678,16211679,16211680,16211681,16211682,16211683,16211684,16211685,16211686,16211687,16211688,16211689,16211690,16211691,16211692,16211693,16211694,16211695,16211696,16211697,16211698,16211699,16211700,16211701,16211702,16211703,16211704,16211705,16211706,16211707,16211708,16211709,16211710,16211711,16211712,16211713,16211714,16211715,16211716,16211717,16211718,16211719,16211720,16211721,16211722,16211723,16211724,16211725,16211726,16211727,16211728,16211729,16211730,16211731,16211732,16211733,16211734,16211735,16211736,16211737,16211738,16211739,16211740,16211741,16211742,16211743,16211744,16211745,16211746,16211747,16211748,16211749,16211750,16211751,16211752,16211753,16211754,16211755,16211756,16211757,16211758,16211759,16211760,16211761,16211762,16211763,16211764,16211765,16211766,16211767,16211768,16211769,16211770,16211771,16211772,16211773,16211774,16211775,16211776,16211777,16211778,16211779,16211780,16211781,16211782,16211783,16211784,16211785,16211786,16211787,16211788,16211789,16211790,16211791,16211792,16211793,16211794,16211795,16211796,16211797,16211798,16211799,16211800,16211801,16211802,16211803,16211804,16211805,16211806,16211807,16211808,16211809,16211810,16211811,16211812,16211813,16211814,16211815,16211816,16211817,16211818,16211819,16211820,16211821,16211822,16211823,16211824,16211825,16211826,16211827,16211828,16211829,16211830,16211831,16211832,16211833,16211834,16211835,16211836,16211837,16211838,16211839,16211840,16211841,16211842,16211843,16211844,16211845,16211846,16211847,16211848,16211849,16211850,16211851,16211852,16211853,16211854,16211855,16211856,16211857,16211858,16211859,16211860,16211861,16211862,16211863,16211864,16211865,16211866,16211867,16211868,16211869,16211870,16211871,16211872,16211873,16211874,16211875,16211876,16211877,16211878,16211879,16211880,16211881,16211882,16211883,16211884,16211885,16211886,16211887,16211888,16211889,16211890,16211891,16211892,16211893,16211894,16211895,16211896,16211897,16211898,16211899,16211900,16211901,16211902,16211903,16211904,16211905,16211906,16211907,16211908,16211909,16211910,16211911,16211912,16211913,16211914,16211915,16211916,16211917,16211918,16211919,16211920,16211921,16211922,16211923,16211924,16211925,16211926,16211927,16211928,16211929,16211930,16211931,16211932,16211933,16211934,16211935,16211936,16211937,16211938,16211939,16211940,16211941,16211942,16211943,16211944,16211945,16211946,16211947,16211948,16211949,16211950,16211951,16211952,16211953,16211954,16211955,16211956,16211957,16211958,16211959,16211960,16211961,16211962,16211963,16211964,16211965,16211966,16211967,16211968,16211969,16211970,16211971,16211972,16211973,16211974,16211975,16211976,16211977,16211978,16211979,16211980,16211981,16211982,16211983,16211984,16211985,16211986,16211987,16211988,16211989,16211990,16211991,16211992,16211993,16211994,16211995,16211996,16211997,16211998,16211999,16212000,16212001,16212002,16212003,16212004,16212005,16212006,16212007,16212008,16212009,16212010,16212011,16212012,16212013,16212014,16212015,16212016,16212017,16212018,16212019,16212020,16212021,16212022,16212023,16212024,16212025,16212026,16212027,16212028,16212029,16212030,16212031,16212032,16212033,16212034,16212035,16212036,16212037,16212038,16212039,16212040,16212041,16212042,16212043,16212044,16212045,16212046,16212047,16212048,16212049,16212050,16212051,16212052,16212053,16212054,16212055,16212056,16212057,16212058,16212059,16212060,16212061,16212062,16212063,16212064,16212065,16212066,16212067,16212068,16212069,16212070,16212071,16212072,16212073,16212074,16212075,16212076,16212077,16212078,16212079,16212080,16212081,16212082,16212083,16212084,16212085,16212086,16212087,16212088,16212089,16212090,16212091,16212092,16212093,16212094,16212095,16212096,16212097,16212098,16212099,16212100,16212101,16212102,16212103,16212104,16212105,16212106,16212107,16212108,16212109,16212110,16212111,16212112,16212113,16212114,16212115,16212116,16212117,16212118,16212119,16212120,16212121,16212122,16212123,16212124,16212125,16212126,16212127,16212128,16212129,16212130,16212131,16212132,16212133,16212134,16212135,16212136,16212137,16212138,16212139,16212140,16212141,16212142,16212143,16212144,16212145,16212146,16212147,16212148,16212149,16212150,16212151,16212152,16212153,16212154,16212155,16212156,16212157,16212158,16212159,16212160,16212161,16212162,16212163,16212164,16212165,16212166,16212167,16212168,16212169,16212170,16212171,16212172,16212173,16212174,16212175,16212176,16212177,16212178,16212179,16212180,16212181,16212182,16212183,16212184,16212185,16212186,16212187,16212188,16212189,16212190,16212191,16212192,16212193,16212194,16212195,16212196,16212197,16212198,16212199,16212200,16212201,16212202,16212203,16212204,16212205,16212206,16212207,16212208,16212209,16212210,16212211,16212212,16212213,16212214,16212215,16212216,16212217,16212218,16212219,16212220,16212221,16212222,16212223,16212224,16212225,16212226,16212227,16212228,16212229,16212230,16212231,16212232,16212233,16212234,16212235,16212236,16212237,16212238,16212239,16212240,16212241,16212242,16212243,16212244,16212245,16212246,16212247,16212248,16212249,16212250,16212251,16212252,16212253,16212254,16212255,16212256,16212257,16212258,16212259,16212260,16212261,16212262,16212263,16212264,16212265,16212266,16212267,16212268,16212269,16212270,16212271,16212272,16212273,16212274,16212275,16212276,16212277,16212278,16212279,16212280,16212281,16212282,16212283,16212284,16212285,16212286,16212287,16212288,16212289,16212290,16212291,16212292,16212293,16212294,16212295,16212296,16212297,16212298,16212299,16212300,16212301,16212302,16212303,16212304,16212305,16212306,16212307,16212308,16212309,16212310,16212311,16212312,16212313,16212314,16212315,16212316,16212317,16212318,16212319,16212320,16212321,16212322,16212323,16212324,16212325,16212326,16212327,16212328,16212329,16212330,16212331,16212332,16212333,16212334,16212335,16212336,16212337,16212338,16212339,16212340,16212341,16212342,16212343,16212344,16212345,16212346,16212347,16212348,16212349,16212350,16212351,16212352,16212353,16212354,16212355,16212356,16212357,16212358,16212359,16212360,16212361,16212362,16212363,16212364,16212365,16212366,16212367,16212368,16212369,16212370,16212371,16212372,16212373,16212374,16212375,16212376,16212377,16212378,16212379,16212380,16212381,16212382,16212383,16212384,16212385,16212386,16212387,16212388,16212389,16212390,16212391,16212392,16212393,16212394,16212395,16212396,16212397,16212398,16212399,16212400,16212401,16212402,16212403,16212404,16212405,16212406,16212407,16212408,16212409,16212410,16212411,16212412,16212413,16212414,16212415,16212416,16212417,16212418,16212419,16212420,16212421,16212422,16212423,16212424,16212425,16212426,16212427,16212428,16212429,16212430,16212431,16212432,16212433,16212434,16212435,16212436,16212437,16212438,16212439,16212440,16212441,16212442,16212443,16212444,16212445,16212446,16212447,16212448,16212449,16212450,16212451,16212452,16212453,16212454,16212455,16212456,16212457,16212458,16212459,16212460,16212461,16212462,16212463,16212464,16212465,16212466,16212467,16212468,16212469,16212470,16212471,16212472,16212473,16212474,16212475,16212476,16212477,16212478,16212479,16212480,16212481,16212482,16212483,16212484,16212485,16212486,16212487,16212488,16212489,16212490,16212491,16212492,16212493,16212494,16212495,16212496,16212497,16212498,16212499,16212500,16212501,16212502,16212503,16212504,16212505,16212506,16212507,16212508,16212509,16212510,16212511,16212512,16212513,16212514,16212515,16212516,16212517,16212518,16212519,16212520,16212521,16212522,16212523,16212524,16212525,16212526,16212527,16212528,16212529,16212530,16212531,16212532,16212533,16212534,16212535,16212536,16212537,16212538,16212539,16212540,16212541,16212542,16212543,16212544,16212545,16212546,16212547,16212548,16212549,16212550,16212551,16212552,16212553,16212554,16212555,16212556,16212557,16212558,16212559,16212560,16212561,16212562,16212563,16212564,16212565,16212566,16212567,16212568,16212569,16212570,16212571,16212572,16212573,16212574,16212575,16212576,16212577,16212578,16212579,16212580,16212581,16212582,16212583,16212584,16212585,16212586,16212587,16212588,16212589,16212590,16212591,16212592,16212593,16212594,16212595,16212596,16212597,16212598,16212599,16212600,16212601,16212602,16212603,16212604,16212605,16212606,16212607,16212608,16212609,16212610,16212611,16212612,16212613,16212614,16212615,16212616,16212617,16212618,16212619,16212620,16212621,16212622,16212623,16212624,16212625,16212626,16212627,16212628,16212629,16212630,16212631,16212632,16212633,16212634,16212635,16212636,16212637,16212638,16212639,16212640,16212641,16212642,16212643,16212644,16212645,16212646,16212647,16212648,16212649,16212650,16212651,16212652,16212653,16212654,16212655,16212656,16212657,16212658,16212659,16212660,16212661,16212662,16212663,16212664,16212665,16212666,16212667,16212668,16212669,16212670,16212671,16212672,16212673,16212674,16212675,16212676,16212677,16212678,16212679,16212680,16212681,16212682,16212683,16212684,16212685,16212686,16212687,16212688,16212689,16212690,16212691,16212692,16212693,16212694,16212695,16212696,16212697,16212698,16212699,16212700,16212701,16212702,16212703,16212704,16212705,16212706,16212707,16212708,16212709,16212710,16212711,16212712,16212713,16212714,16212715,16212716,16212717,16212718,16212719,16212720,16212721,16212722,16212723,16212724,16212725,16212726,16212727,16212728,16212729,16212730,16212731,16212732,16212733,16212734,16212735,16212736,16212737,16212738,16212739,16212740,16212741,16212742,16212743,16212744,16212745,16212746,16212747,16212748,16212749,16212750,16212751,16212752,16212753,16212754,16212755,16212756,16212757,16212758,16212759,16212760,16212761,16212762,16212763,16212764,16212765,16212766,16212767,16212768,16212769,16212770,16212771,16212772,16212773,16212774,16212775,16212776,16212777,16212778,16212779,16212780,16212781,16212782,16212783,16212784,16212785,16212786,16212787,16212788,16212789,16212790,16212791,16212792,16212793,16212794,16212795,16212796,16212797,16212798,16212799,16212800,16212801,16212802,16212803,16212804,16212805,16212806,16212807,16212808,16212809,16212810,16212811,16212812,16212813,16212814,16212815,16212816,16212817,16212818,16212819,16212820,16212821,16212822,16212823,16212824,16212825,16212826,16212827,16212828,16212829,16212830,16212831,16212832,16212833,16212834,16212835,16212836,16212837,16212838,16212839,16212840,16212841,16212842,16212843,16212844,16212845,16212846,16212847,16212848,16212849,16212850,16212851,16212852,16212853,16212854,16212855,16212856,16212857,16212858,16212859,16212860,16212861,16212862,16212863,16212864,16212865,16212866,16212867,16212868,16212869,16212870,16212871,16212872,16212873,16212874,16212875,16212876,16212877,16212878,16212879,16212880,16212881,16212882,16212883,16212884,16212885,16212886,16212887,16212888,16212889,16212890,16212891,16212892,16212893,16212894,16212895,16212896,16212897,16212898,16212899,16212900,16212901,16212902,16212903,16212904,16212905,16212906,16212907,16212908,16212909,16212910,16212911,16212912,16212913,16212914,16212915,16212916,16212917,16212918,16212919,16212920,16212921,16212922,16212923,16212924,16212925,16212926,16212927,16212928,16212929,16212930,16212931,16212932,16212933,16212934,16212935,16212936,16212937,16212938,16212939,16212940,16212941,16212942,16212943,16212944,16212945,16212946,16212947,16212948,16212949,16212950,16212951,16212952,16212953,16212954,16212955,16212956,16212957,16212958,16212959,16212960,16212961,16212962,16212963,16212964,16212965,16212966,16212967,16212968,16212969,16212970,16212971,16212972,16212973,16212974,16212975,16212976,16212977,16212978,16212979,16212980,16212981,16212982,16212983,16212984,16212985,16212986,16212987,16212988,16212989,16212990,16212991,16212992,16212993,16212994,16212995,16212996,16212997,16212998,16212999,16213000,16213001,16213002,16213003,16213004,16213005,16213006,16213007,16213008,16213009,16213010,16213011,16213012,16213013,16213014,16213015,16213016,16213017,16213018,16213019,16213020,16213021,16213022,16213023,16213024,16213025,16213026,16213027,16213028,16213029,16213030,16213031,16213032,16213033,16213034,16213035,16213036,16213037,16213038,16213039,16213040,16213041,16213042,16213043,16213044,16213045,16213046,16213047,16213048,16213049,16213050,16213051,16213052,16213053,16213054,16213055,16213056,16213057,16213058,16213059,16213060,16213061,16213062,16213063,16213064,16213065,16213066,16213067,16213068,16213069,16213070,16213071,16213072,16213073,16213074,16213075,16213076,16213077,16213078,16213079,16213080,16213081,16213082,16213083,16213084,16213085,16213086,16213087,16213088,16213089,16213090,16213091,16213092,16213093,16213094,16213095,16213096,16213097,16213098,16213099,16213100,16213101,16213102,16213103,16213104,16213105,16213106,16213107,16213108,16213109,16213110,16213111,16213112,17705867,26112137,26112171,26112293,29255034,45195105,46898534,49312926,49333698,49366701,49512078,49522611,49771535,49776360,49796837,49832759,49889564,49993285,49998165,50011317,50061871,50137404,50137412,50170055,50420887,50442164,50454960,50638846,50638865,50806386,50890877,50967712,50967748,50999993,51015150,51040277,51304326,51623060,51694332,52009348,52282898,52312498,52312501,52815956,53098579,53098670,53276545,53277996,53775952,53812534,53865038,53865045,53865072,53884516,54130973,54619985,54642898,54644923,54649011,54649095,55025497,55564838,55674606,55674683,55951635,56475461,57406468,57888547,57888571,57888579,57888582,58587533,58915902,59020690,59307647,59474861,59537770,59564514,59714688,59999836,60221436,60256725,60311968,60690209,60690231,60959016,61510951,62180605,62180622,62180876,62267535,63508813,63645043,63808693,64660024,69887244";
		$arrRes	 = explode(',', $resQ);
		foreach ($arrRes as $value)
		{
			echo "\r\nDEL = " . $sql	 = "DELETE FROM gozo_archive.`agent_api_tracking` WHERE aat_id = {$value}";
			$rowsDel = DBUtil::command($sql, DBUtil::ADB())->execute();
		}
	}

	public function actionSendWhatsappMsg()
	{
		$sql	 = "SELECT
						vnd_id,
						ctt_name AS vnd_name,
						GROUP_CONCAT(distinct contact_phone.phn_full_number ORDER BY phn_whatsapp_verified DESC,phn_is_primary DESC LIMIT 0,1 ) AS vnd_phone,
						states.stt_zone,
						states.stt_id
					FROM  vendors 
						INNER JOIN vendor_stats ON vendor_stats.vrs_vnd_id=vendors.vnd_id
						INNER JOIN contact_profile ON contact_profile.cr_is_vendor=vendors.vnd_id
						INNER JOIN contact_phone ON contact_phone.phn_contact_id=contact_profile.cr_contact_id   
						INNER JOIN contact ON contact.ctt_id=contact_profile.cr_contact_id 
						INNER JOIN cities ON cities.cty_id=contact.ctt_city 
						INNER JOIN states ON states.stt_id=cities.cty_state_id 
                        INNER JOIN app_tokens ON app_tokens.apt_entity_id=vendors.vnd_id and app_tokens.apt_user_type=2 and app_tokens.apt_platform <> 7
					WHERE 1 
						AND vrs_last_logged_in IS NOT NULL AND  vrs_last_logged_in < DATE_SUB(CURDATE(), INTERVAL 25 DAY)
                        AND phn_is_verified=1
						AND contact_phone.phn_active=1
						AND cities.cty_active=1
						AND contact.ctt_id=contact.ctt_ref_code
						AND states.stt_active='1' 
						AND ctt_city is NOT NULL  
						AND ctt_city > 0
						AND contact_profile.cr_status=1
						AND contact.ctt_active=1
						AND vnd_id=vnd_ref_code
						AND vnd_is_dco=1
						AND vnd_active=1 
						AND states.stt_id IN (94,86,85)
						AND vnd_registered_platform=0
						AND states.stt_zone IS NOT NULL
					GROUP BY vnd_id";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{

			echo "\nPhone: " . $row['vnd_phone'];
			if (in_array($row['stt_zone'], array(1, 2, 3, 5, 6)))
			{
				$whtIdArr = ['2', '3'];
				foreach ($whtIdArr as $whtId)
				{
					$templateDetails = WhatsappLog::getTemplateNameById($whtId);
					$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
					$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
					$arrWhatsAppData = [$url];
					$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
					$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
					$arrButton		 = Whatsapp::buildComponentButton([$hash]);
					$lang			 = $templateDetails['wht_lang_code'];
					WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
				}
			}
			else
			{
				switch ($row['stt_id'])
				{
					case 80:
						// State:Andhra Pradesh  Lang:Telugu Code:te DONE
						$whtIdArr = ['2', '5'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					case 91:
						// State:Karnataka  Lang:Kannada Code:kn DONE
						$whtIdArr = ['2', '7'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					case 92:
						// State:Kerala  Lang:Malayalam Code:ml
						$whtIdArr = ['2', '6'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					case 103:
						// State:Tamil Nadu  Lang:Tamil Code:ta  DONE
						$whtIdArr = ['2', '4'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					case 110:
						// Andaman and Nicobar Islands Lang:Tamil Code:ta
						$whtIdArr = ['2', '4'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					case 112:
						// State:Puducherry Lang:Tamil Code:ta
						$whtIdArr = ['2', '4'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					case 115:
						// State:Telangana Lang:Telugu Code:te
						$whtIdArr = ['2', '5'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
						break;
					default :
						$whtIdArr = ['2', '3'];
						foreach ($whtIdArr as $whtId)
						{
							$templateDetails = WhatsappLog::getTemplateNameById($whtId);
							$hash			 = Yii::app()->shortHash->hash($row['vnd_id']);
							$url			 = Yii::app()->params['fullBaseURL'] . '/dct/' . $hash;
							$arrWhatsAppData = [$url];
							$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $row['vnd_id']];
							$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
							$arrButton		 = Whatsapp::buildComponentButton([$hash]);
							$lang			 = $templateDetails['wht_lang_code'];
							WhatsappLog::send($row['vnd_phone'], $templateDetails['wht_template_name'], $arrDBData, $arrBody, $arrButton, $lang);
						}
				}
			}
		}
		WhatsappLog::updateIsWhatsappVerified();
	}

	public function actionVendorAccountSettled()
	{
		//$sql		 = "SELECT * FROM test.`vendorcollectionreport_500` WHERE `write_off` = 0";
		$sql		 = "SELECT * FROM test.`vendorProcessList_23` WHERE `process` = 1 AND update_status = 0";
		$recordsets	 = DBUtil::query($sql);
		foreach ($recordsets as $res)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$vndid			 = $res['Vendor_ID'];
				$balance		 = $res['Balance'];
				$amount			 = ($balance < 0) ? (-1 * $balance) : $balance;
				$userInfo		 = UserInfo::getInstance();
				$remarks		 = ($balance < 0) ? '(A) Cash Paid' : '(A) Cash Received';
				$date			 = '2022-04-01 11:00:00';
				$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_OPERATOR, $date, $amount, $remarks, $vndid, $userInfo);

				if ($balance < 0)
				{
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndid, '', $remarks);
					$crTrans = AccountTransDetails::getInstance(Accounting::LI_CASH, Accounting::AT_OPERATOR, $vndid, '', $remarks);
				}
				else
				{
					$crTrans = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndid, '', $remarks);
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_CASH, Accounting::AT_OPERATOR, $vndid, '', $remarks);
				}
				$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);

				if ($status)
				{
					//$query = "UPDATE test.vendorcollectionreport_500 SET status = 1,write_off = 2 WHERE `write_off` = 0 AND Vendor_ID = $vndid AND status = 0";
					$query = "UPDATE test.vendorProcessList_23 SET update_status = 1 WHERE `process` = 1 AND vnd_id = $vndid AND update_status = 0";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					throw new Exception("Error in updating!!!");
				}
				echo "\r\nDone Vendor_ID===" . $vndid . "===Amount===" . $amount;
			}
			catch (Exception $ex)
			{
				echo "\r\nError == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionIncompleteBookingOffer()
	{
		$promoId = Config::get('incomplete.booking.user.promo.id');
		echo "\n\nSQL: " . $sql	 = "SELECT MAX(bkg_id) bkg_id, bkg_user_id, MAX(bkg_pickup_date) bkg_pickup_date, bkg_user_email, 
				bkg_country_code, bkg_contact_no, bkg_user_name, bkg_user_lname, pru_id, 
				DATEDIFF(MAX(bkg_pickup_date), NOW()) dayDiff 
				FROM `booking_temp` 
				LEFT JOIN promo_users ON pru_ref_type = 0 AND pru_ref_id = bkg_user_id AND pru_promo_id = {$promoId} 
					AND pru_valid_from <= CURRENT_TIMESTAMP AND pru_valid_upto >= CURRENT_TIMESTAMP 
				WHERE 1 AND bkg_status IN (0,1,2,3,20,21) AND bkg_follow_up_by IS NULL AND bkg_user_id > 0 AND pru_id IS NULL 
				AND bkg_contact_no IS NOT NULL AND bkg_contact_no <> '' AND bkg_booking_type IN (1,2) 
				AND (bkg_pickup_date BETWEEN DATE_ADD(NOW(), INTERVAL 12 HOUR) AND DATE_ADD(NOW(), INTERVAL 96 HOUR)) 
				AND (bkg_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 48 HOUR) AND DATE_SUB(NOW(), INTERVAL 12 HOUR)) 
				GROUP BY bkg_contact_no";

		$res = DBUtil::query($sql);
		foreach ($res as $row)
		{
			echo "\n==============================";
			echo "\nBkgID: " . $bkgId		 = $row['bkg_id'];
			$userId		 = $row['bkg_user_id'];
			echo "\nPickupDate: " . $pickupDate	 = $row['bkg_pickup_date'];
			$userEmail	 = $row['bkg_user_email'];
			echo "\nCountryCode: " . $countryCode = ($row['bkg_country_code'] == '' ? '91' : $row['bkg_country_code']);
			echo "\nContactNo: " . $contactNo	 = $row['bkg_contact_no'];
			$userFName	 = $row['bkg_user_name'];
			$userLName	 = $row['bkg_user_lname'];
			$dayDiff	 = ($row['dayDiff'] > 1 ? round($row['dayDiff'] / 2) : 1);
			$fullName	 = $userFName;
			$fullName	 = (trim($fullName) != '' ? $fullName : 'User');

			// Service Call Queue
			$rowLeadStatus = ServiceCallQueue::isRelatedLeadExist($bkgId);
			echo "\nSCQ Cnt: " . $rowLeadStatus['cnt'];
			if ($rowLeadStatus['cnt'] > 0 || $dayDiff <= 0)
			{
				continue;
			}

			// Booking
			$sqlCnt = "SELECT COUNT(1) cnt FROM `booking` 
						INNER JOIN booking_user ON bui_bkg_id = bkg_id 
						WHERE 1 AND bkg_agent_id IS NULL  
						AND ((bkg_status IN (2,3,5) AND bkg_pickup_date > NOW()) 
							OR (bkg_status IN (1,15) AND (bkg_create_date BETWEEN DATE_SUB(NOW(), INTERVAL 12 HOUR) AND NOW())))
						AND (bkg_user_id = '{$userId}' OR bkg_user_email = '{$userEmail}' OR bkg_contact_no = '{$contactNo}')";

			echo "\nBKG Cnt: " . $cnt = DBUtil::queryScalar($sqlCnt);
			if ($cnt > 0)
			{
				continue;
			}

			// Checking already in Promo User
			echo "\nPromoUser: " . $pruUser = PromoUsers::model()->getUserApplicable($userId, $promoId, 1, 0, 0, 0);
			if (!$pruUser)
			{
				echo "\nTill: " . $validTill = date('Y-m-d H:i:s', strtotime("+{$dayDiff} day"));
				if (strtotime($validTill) > strtotime($pickupDate))
				{
					$validTill = $pickupDate;
				}

				echo "\nValidTill: " . $validTill;

				// Add Promo User
				$res = PromoUsers::addUser($promoId, $userId, 0, 1, date('Y-m-d H:i:s'), $validTill);
				if ($res)
				{
					Users::notifyReminderForIncompletedLeads($userId, ($countryCode . $contactNo), $bkgId, $fullName, $isSchedule			 = 0, $schedulePlatform	 = null);
				}
			}
		}
	}

//	public function actionFetchQrCode()
//	{
//		$sql	 = "SELECT qrc_id,qrc_code FROM qr_code WHERE 1 AND qrc_response_status=0 AND qrc_short_url IS NULL ORDER BY qrc_id DESC LIMIT 0,100";
//		$details = DBUtil::query($sql, DBUtil::SDB());
//		foreach ($details as $value)
//		{
//			$response	 = QrCode::urlShorten($value['qrc_code'], $timeOut	 = 10000);
//			if ($response != null)
//			{
//				$response	 = urldecode($response);
//				$arrResponse = explode("&keyword", $response);
//				$sqlUpdate	 = "UPDATE qr_code SET qrc_response_status=1,qrc_short_url=:response WHERE 1 AND qrc_id=:qrcId";
//				DBUtil::execute($sqlUpdate, ['response' => $arrResponse[0], 'qrcId' => $value['qrc_id']]);
//			}
//		}
//	}

	public function actionUpdateTestQrCode()
	{
		$sql	 = "SELECT keyword FROM test.yourls_url WHERE new=1 LIMIT 0,1";
		$details = DBUtil::query($sql, DBUtil::SDB());
		foreach ($details as $value)
		{
			$keyword = trim($value['keyword']);

			Logger::writeToConsole("Key: " . $keyword);
			$response	 = QrCode::qrUrlShorten($keyword, $timeOut	 = 60000);
			if ($response['errorCode'] == 200)
			{
				Logger::writeToConsole(", UPDATE");
				$sqlUpdate = "UPDATE test.yourls_url SET new=2 WHERE 1 AND keyword='{$keyword}'";
				DBUtil::execute($sqlUpdate);
			}

			Logger::writeToConsole(json_encode($response));
		}
	}

	public function actionUpdateCitiesByLatLong()
	{
		$sql	 = "SELECT cty_id FROM cities WHERE cty_is_airport = 1 AND cty_active = 1 AND cty_service_active = 1 ORDER BY cty_id ASC";
		$record	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($record as $val)
		{
			$cnt	 = 0;
			$strData = "";
			echo "\nCityId: " . $val['cty_id'];

			$query			 = "SELECT ltg_id, ltg_lat, ltg_long FROM lat_long WHERE ltg_city_id = {$val['cty_id']} AND ltg_active = 1";
			$latLongRecords	 = DBUtil::query($query, DBUtil::SDB());
			foreach ($latLongRecords as $data)
			{
				$latitude	 = $data['ltg_lat'];
				$longitude	 = $data['ltg_long'];
				$ltgId		 = $data['ltg_id'];

				$cityId = Cities::getCityByLatLng($latitude, $longitude);
				if (!$cityId || $cityId == null)
				{
					continue;
				}

				$model = LatLong::model()->findByPk($ltgId);
				if ($model->ltg_city_id == $cityId)
				{
					continue;
				}

				try
				{
					$model->ltg_city_id = $cityId;
					$model->save();

					$cnt++;
					$strData .= $ltgId . "," . $val['cty_id'] . "," . $cityId . PHP_EOL;
				}
				catch (Exception $ex)
				{
					echo "\r\nError == " . $ex->getMessage();
				}
			}

			Filter::writeLog($strData, true, 'city_bound_latlong.txt');
			echo ", DONE CNT: " . $cnt;
		}
	}

	public function actionRevertGstMMT()
	{
		$sql	 = "SELECT bkg_id FROM test.mmtGstRevertCancelBooking WHERE update_status = 0 ORDER BY bkg_id LIMIT 0,1";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				echo "\nBkgId: " . $row['bkg_id'];
				$model = Booking::model()->findByPk($row['bkg_id']);
				if ($model)
				{
					$sTax									 = $model->bkgInvoice->bkg_service_tax;
					$model->bkgInvoice->bkg_advance_amount	 = ($model->bkgInvoice->bkg_advance_amount - $model->bkgInvoice->bkg_service_tax);
					$model->bkgInvoice->bkg_corporate_credit = ($model->bkgInvoice->bkg_corporate_credit - $model->bkgInvoice->bkg_service_tax);
					$model->bkgInvoice->bkg_service_tax		 = 0;
					$model->bkgInvoice->bkg_service_tax_rate = 0;
					$model->bkgInvoice->calculateTotal_1();
					if (!$model->bkgInvoice->save())
					{
						$query = "UPDATE test.mmtGstRevertCancelBooking SET update_status = 2 WHERE bkg_id = " . $row['bkg_id'];
						DBUtil::execute($query);

						throw new Exception("Failed to update BkgId ::" . $row['bkg_id'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}
					$model->refresh();
					// refund service tax in accounts table
					$bkg_id			 = $model->bkg_id;
					$remarks		 = "GST reverted booking to wallet bkgid:" . $bkg_id;
					$date			 = $model->bkg_pickup_date;
					$amount			 = $sTax;
					$partnerId		 = $model->bkg_agent_id;
					$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
					$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
					$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
					$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
					if (!$status)
					{
						$query = "UPDATE test.mmtGstRevertCancelBooking SET update_status = 2 WHERE bkg_id = {$bkg_id}";
						DBUtil::execute($query);

						throw new Exception("Unable to revert gst to wallet bkgid:" . $bkg_id);
					}
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);

					$query = "UPDATE test.mmtGstRevertCancelBooking SET update_status = 1 WHERE bkg_id = {$bkg_id}";
					DBUtil::execute($query);

					echo ", DONE";
				}
			}
			catch (Exception $ex)
			{
				echo ", ERROR";
				echo $ex->getMessage() . "\n\n";
			}
		}
	}

	public function actionUpdatePartnerAirportRates()
	{
		$sql = "SELECT * FROM partner_airport_transfer WHERE pat_active = 1 AND pat_vehicle_type IN (1,2,3) ORDER BY pat_id ASC";
		$res = DBUtil::query($sql);
		foreach ($res as $rec)
		{
			echo "\n================\noldPatId = " . $oldPatId				 = $rec['pat_id'];
			$cityId					 = $rec['pat_city_id'];
			$transferType			 = $rec['pat_transfer_type'];
			$vehicleType			 = $rec['pat_vehicle_type'];
			echo "\nvendorAmount = " . $vendorAmount			 = $rec['pat_vendor_amount'];
			echo "\ntotalFare = " . $totalFare				 = $rec['pat_total_fare'];
			$minKM					 = $rec['pat_minimum_km'];
			echo "\nextraPerKMRate = " . $extraPerKMRate			 = $rec['pat_extra_per_km_rate'];
			$partnerId				 = $rec['pat_partner_id'];
			$isAirportFeeIncluded	 = $rec['is_airport_fee_included'];

			echo "\n\nnewVendorAmount = " . $newVendorAmount	 = round(($vendorAmount * 1.15), -1);
			echo "\nnewTotalFare = " . $newTotalFare		 = round(($totalFare * 1.15), -1);
			echo "\nnewExtraPerKMRate = " . $newExtraPerKMRate	 = round($extraPerKMRate * 1.15);

			$newVehicleType = 0;
			if ($vehicleType == 1)
			{
				$newVehicleType = 14;
			}
			elseif ($vehicleType == 2)
			{
				$newVehicleType = 15;
			}
			elseif ($vehicleType == 3)
			{
				$newVehicleType = 16;
			}

			if ($newVehicleType == 0)
			{
				continue;
			}

			$strPartnerId = " AND pat_partner_id IS NULL ";
			if ($partnerId > 0)
			{
				$strPartnerId = " AND pat_partner_id = {$partnerId} ";
			}

			try
			{
				$sql1	 = "SELECT pat_id FROM partner_airport_transfer 
						WHERE 1 AND pat_city_id = {$cityId} AND pat_transfer_type = {$transferType} 
						AND pat_vehicle_type = {$newVehicleType} {$strPartnerId}";
				$patId	 = DBUtil::queryScalar($sql1);
				if ($patId)
				{
					echo "\nFound";
					$objPAT = PartnerAirportTransfer::model()->findByPk($patId);
				}
				else
				{
					echo "\nNot Found";
					$objPAT						 = new PartnerAirportTransfer();
					$objPAT->pat_city_id		 = $cityId;
					$objPAT->pat_transfer_type	 = $transferType;
					$objPAT->pat_vehicle_type	 = $newVehicleType;
					$objPAT->pat_log			 = '';
					$objPAT->pat_created_on		 = date("Y-m-d H:i:s");

					if ($partnerId > 0)
					{
						$objPAT->pat_partner_id = $partnerId;
					}
				}

				echo "\npat_id = " . $objPAT->pat_id;
				$objPAT->pat_vendor_amount		 = $newVendorAmount;
				$objPAT->pat_total_fare			 = $newTotalFare;
				$objPAT->pat_minimum_km			 = $minKM;
				$objPAT->pat_extra_per_km_rate	 = $newExtraPerKMRate;
				$objPAT->pat_active				 = 1;
				$objPAT->pat_modified_on		 = date("Y-m-d H:i:s");
				$objPAT->is_airport_fee_included = $isAirportFeeIncluded;
				$objPAT->scenario				 = 'update';
				$objPAT->save();
				echo "\nDONE";
			}
			catch (Exception $ex)
			{
				echo "\nErr: " . $ex->getMessage();
			}
		}
	}

	public function actionTopRouteCities_OLD()
	{
		$topRoutes			 = Route::getTopRoutesByRegion(5);
		$topCities			 = Cities::getTopCityByRegion(5);
		$topAirportTransfer	 = Cities::getTopCityByRegion(5, 1);
		foreach ($topRoutes as $route)
		{
			$sql	 = "SELECT rut_id, rut_name FROM  route
                 WHERE (rut_from_city_id = " . $route['fromCityId'] . " AND rut_to_city_id = " . $route['toCityId'] . ")
                 OR (rut_from_city_id = " . $route['toCityId'] . " AND rut_to_city_id = '" . $route['fromCityId'] . "')";
			$result	 = DBUtil::queryRow($sql);

			$routeData = "INSERT INTO `top_route_cities` (`trc_type`, `trc_type_id`,`trc_type_path`,`trc_region`, `trc_state`, `trc_total_enquiries`, `trc_total_served`) VALUES
                    ('1','" . $result['rut_id'] . "', '" . $result['rut_name'] . "','" . $route['zoneid'] . "','" . $route['stateid'] . "', 0,'" . $route['cnt'] . "');";
			DBUtil::execute($routeData);
		}

		foreach ($topCities as $cities)
		{
			$cityData = "INSERT INTO `top_route_cities` (`trc_type`, `trc_type_id`,`trc_type_path`,`trc_region`, `trc_state`, `trc_total_enquiries`, `trc_total_served`) VALUES
                    ('2','" . $cities['ctyId'] . "', '" . $cities['cty_alias_path'] . "','" . $cities['stt_zone'] . "','" . $cities['stateid'] . "', 0,'" . $cities['cnt'] . "');";
			DBUtil::execute($cityData);
		}

		foreach ($topAirportTransfer as $airportTransfer)
		{
			$airportData = "INSERT INTO `top_route_cities` (`trc_type`, `trc_type_id`,`trc_type_path`,`trc_region`, `trc_state`, `trc_total_enquiries`, `trc_total_served`) VALUES
                    ('3','" . $airportTransfer['ctyId'] . "', '" . $airportTransfer['cty_alias_path'] . "','" . $airportTransfer['stt_zone'] . "','" . $airportTransfer['stateid'] . "', 0,'" . $airportTransfer['cnt'] . "');";
			DBUtil::execute($airportData);
		}
	}

	public function actionPopulateTopRouteCities()
	{
		$sqlRoutes	 = "SELECT fromCity.cty_id fromCityId, toCity.cty_id toCityId, fromCity.cty_name fromCityName, 
						toCity.cty_name toCityName, COUNT(DISTINCT bkg.bkg_id) as cnt, fromCity.cty_alias_path aliaspath, 
						stt.stt_zone regionId, fromCity.cty_state_id stateid
					FROM booking bkg 
					INNER JOIN cities fromCity ON bkg.bkg_from_city_id=fromCity.cty_id AND fromCity.cty_is_airport=0 
						AND fromCity.cty_active = 1 AND fromCity.cty_service_active = 1 
					INNER JOIN cities toCity ON bkg.bkg_to_city_id=toCity.cty_id AND toCity.cty_is_airport=0 
						AND toCity.cty_active = 1 AND toCity.cty_service_active = 1 
					INNER JOIN states stt ON stt.stt_active = '1' AND stt.stt_id = fromCity.cty_state_id  
					WHERE bkg.bkg_status IN (6,7) AND bkg.bkg_active=1 AND bkg.bkg_booking_type = 1 
						AND bkg.bkg_create_date > DATE_SUB(NOW(), INTERVAL 365 DAY) 
						AND bkg.bkg_from_city_id <> bkg.bkg_to_city_id
					GROUP BY bkg.bkg_from_city_id, bkg.bkg_to_city_id 
					HAVING cnt > 5 ORDER BY cnt DESC LIMIT 0, 500";
		$topRoutes	 = DBUtil::query($sqlRoutes);
		foreach ($topRoutes as $route)
		{
			$sql	 = "SELECT rut_id, rut_name FROM route
                 WHERE rut_active = 1 AND rut_from_city_id = " . $route['fromCityId'] . " AND rut_to_city_id = " . $route['toCityId'];
			$result	 = DBUtil::queryRow($sql);

			if ($result)
			{
				$routeData = "INSERT INTO `top_route_cities` (`trc_type`, `trc_type_id`,`trc_from_city_id`,`trc_to_city_id`,`trc_type_path`,`trc_region`, `trc_state`, `trc_total_served`) VALUES
							('1','" . $result['rut_id'] . "','" . $route['fromCityId'] . "','" . $route['toCityId'] . "', '" . $result['rut_name'] . "','" . $route['regionId'] . "','" . $route['stateid'] . "','" . $route['cnt'] . "')";
				DBUtil::execute($routeData);
			}
		}

		$sqlCities	 = "SELECT cty.cty_id as ctyId, cty.cty_name as city, cty.cty_alias_path, COUNT(DISTINCT bkg.bkg_id) as cnt, 
				stt.stt_id stateid, stt.stt_zone regionId 
				FROM cities cty
				INNER JOIN booking bkg ON cty.cty_id=bkg.bkg_from_city_id AND bkg.bkg_active=1 
					AND bkg.bkg_create_date > DATE_SUB(NOW(), INTERVAL 365 DAY) AND bkg.bkg_status IN (6,7) 
				INNER JOIN states stt ON stt.stt_id=cty.cty_state_id  
				WHERE cty.cty_is_airport = 0 AND cty.cty_active = 1 AND cty.cty_service_active = 1 
				GROUP BY bkg.bkg_from_city_id 
				HAVING cnt > 5 ORDER BY cnt DESC LIMIT 0, 500";
		$topCities	 = DBUtil::query($sqlCities);
		foreach ($topCities as $cities)
		{
			$cityData = "INSERT INTO `top_route_cities` (`trc_type`, `trc_type_id`,`trc_from_city_id`,`trc_type_path`,`trc_region`, `trc_state`, `trc_total_served`) VALUES
                    ('2','" . $cities['ctyId'] . "','" . $cities['ctyId'] . "', '" . $cities['cty_alias_path'] . "','" . $cities['regionId'] . "','" . $cities['stateid'] . "','" . $cities['cnt'] . "');";
			DBUtil::execute($cityData);
		}

		$sqlAirports = "SELECT cty.cty_id as ctyId, cty.cty_name as city, cty.cty_alias_path, COUNT(DISTINCT bkg.bkg_id) as cnt, 
				stt.stt_id stateid, stt.stt_zone regionId 
				FROM cities cty
				INNER JOIN booking bkg ON cty.cty_id=bkg.bkg_from_city_id AND bkg.bkg_active=1 
					AND bkg.bkg_create_date > DATE_SUB(NOW(), INTERVAL 365 DAY) AND bkg.bkg_status IN (6,7) 
				INNER JOIN states stt ON stt.stt_id=cty.cty_state_id  
				WHERE cty.cty_is_airport = 1 AND cty.cty_active = 1 AND cty.cty_service_active = 1 
				GROUP BY bkg.bkg_from_city_id
				HAVING cnt > 2 ORDER BY cnt DESC LIMIT 0, 150";
		$topAirports = DBUtil::query($sqlAirports);
		foreach ($topAirports as $airports)
		{
			$cityData = "INSERT INTO `top_route_cities` (`trc_type`, `trc_type_id`,`trc_from_city_id`,`trc_type_path`,`trc_region`, `trc_state`, `trc_total_served`) VALUES
                    ('3','" . $airports['ctyId'] . "','" . $airports['ctyId'] . "', '" . $airports['cty_alias_path'] . "','" . $airports['regionId'] . "','" . $airports['stateid'] . "','" . $airports['cnt'] . "');";
			DBUtil::execute($cityData);
		}
	}

	public function actionVendorDueAmount()
	{
		$sql	 = "SELECT
						atd.adt_trans_ref_id vndId,
						SUM(atd.adt_amount) balance,
						vendor_stats.vrs_last_trip_datetime
					FROM account_trans_details atd
						JOIN account_transactions act ON act.act_id = atd.adt_trans_id
						JOIN vendors vnd ON vnd.vnd_id = atd.adt_trans_ref_id AND vnd.vnd_id = vnd.vnd_ref_code
						JOIN vendor_stats  On  vnd.vnd_id = vendor_stats.vrs_vnd_id
					WHERE 1 
						AND atd.adt_ledger_id = 14
						AND act.act_active = 1 
						AND atd.adt_active = 1 
						AND act.act_status=1
						AND atd.adt_status=1
						AND  (vendor_stats.vrs_last_trip_datetime <='2023-04-30 23:59:59' OR  vendor_stats.vrs_last_trip_datetime IS NULL)
					GROUP BY atd.adt_trans_ref_id
					HAVING balance >2000 ORDER BY balance DESC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			try
			{
				$count = ServiceCallQueue::checkDuplicateDocumetApprovalForVendor($row['vndId'], ServiceCallQueue::TYPE_VENDOR_DUE_AMOUNT);
				if ($count == 0)
				{
					ServiceCallQueue::autoFURVendorDueAmount($row);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionVndBal()
	{
		$sql = "SELECT Vendor_ID FROM test.`vendorcollectionreport_500` 
			INNER JOIN gozodb.vendor_stats ON vrs_vnd_id = Vendor_ID 
			WHERE `Balance` > 0 AND `status` = 1 AND `write_off` IN (1,2) AND vrs_outstanding = 0";

		$result = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			$vndId	 = $row['Vendor_ID'];
			$cttId	 = ContactProfile::getByVndId($vndId);
			if (!$cttId || is_null($cttId) || $cttId == '')
			{
				continue;
			}
			$phoneNo = ContactPhone::model()->getContactPhoneById($cttId);
			if (!$phoneNo || is_null($phoneNo) || $phoneNo == '')
			{
				continue;
			}
			Vendors::notifyVendorDuesWaivedOff($vndId, $phoneNo, null, null);
		}
	}

	public function actionProcessGST()
	{
		$sql	 = "SELECT * FROM test.booking_fy_22_23 WHERE process = 0 LIMIT 0, 10000";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			$transaction = null;
			try
			{
				$transaction = DBUtil::beginTransaction();

				echo "\nBkgId: " . $bkgId				 = $row['bkg_id'];
				$newGST				 = $row['new_gst'];
				$newBFWithoutGST	 = $row['new_bkg_base_amount_without_gst'];
				$newDriverAllowance	 = $row['new_driver_allowance'];
				$newTollTax			 = $row['new_toll_tax'];

				$bkgModel = Booking::model()->findByPk($bkgId);
				if (!$bkgModel)
				{
					continue;
				}

				$diffTollTax = ($newTollTax - $bkgModel->bkgInvoice->bkg_extra_toll_tax);

				$sqlUpd	 = "UPDATE gozodb.booking_invoice SET bkg_driver_allowance_amount = {$newDriverAllowance},
							bkg_toll_tax = {$diffTollTax}, bkg_base_amount = {$newBFWithoutGST}, bkg_service_tax = {$newGST} 
							WHERE biv_bkg_id = {$bkgId}";
				$success = DBUtil::execute($sqlUpd);
				if ($success)
				{
					#BookingInvoice::updateGozoAmount($bkgModel->bkg_bcb_id);
					// GozoAmount
					$sql	 = "select TmpUpdateGozoAmount($bkgModel->bkg_bcb_id) from dual";
					$result	 = DBUtil::command($sql)->queryScalar();

					$sqlTmpUpd = "UPDATE test.booking_fy_22_23 SET process = 1 WHERE bkg_id = {$bkgId}";
					DBUtil::execute($sqlTmpUpd);

					DBUtil::commitTransaction($transaction);

					echo ", UPDATED";
				}
				else
				{
					$sqlTmpUpd = "UPDATE test.booking_fy_22_23 SET process = 2 WHERE bkg_id = {$bkgId}";
					DBUtil::execute($sqlTmpUpd);

					DBUtil::commitTransaction($transaction);

					echo ", ERROR NOT UPDATED";
				}
			}
			catch (Exception $ex)
			{

				echo ", ERROR: " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionUpdGozoAmtNew()
	{
		#$sql	 = "SELECT bkg_id FROM test.booking_fy_22_23 WHERE process = 1";
		$sql	 = "SELECT bkg_id FROM `booking` bkg WHERE bkg.bkg_pickup_date BETWEEN '2022-04-01 00:00:00' AND '2023-03-31 23:59:59' 
					AND bkg.bkg_active=1 AND bkg.bkg_status IN (2,3,5,6,7)";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			echo "\nBkgId: " . $bkgId = $row['bkg_id'];

			$bkgModel = Booking::model()->findByPk($bkgId);
			if (!$bkgModel)
			{
				continue;
			}

			// GozoAmount
			$bcbId	 = $bkgModel->bkg_bcb_id;
			$sql1	 = "select TmpUpdateGozoAmount({$bcbId}) from dual";
			DBUtil::command($sql1)->queryScalar();
		}
	}

	public function actionUpdVndApproveDate()
	{
		$sql	 = "SELECT vlg_vnd_id, vlg_created FROM vendors_log WHERE vlg_event_id = 60 ORDER BY vlg_id ASC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			echo "\nVndId: " . $vndId		 = $row['vlg_vnd_id'];
			echo ", CreateDate: " . $createDate	 = $row['vlg_created'];

			$vndStats = VendorStats::model()->getbyVendorId($vndId);
			if ($vndStats)
			{
				if ($vndStats->vrs_first_approve_date == null)
				{
					$vndStats->vrs_first_approve_date = $createDate;
				}
				$vndStats->vrs_last_approve_date = $createDate;
				$vndStats->save();
			}
		}
	}

	public function actionTdsRefundCorrection()
	{
		$sql	 = "SELECT * FROM test.tds_22_23 tds where tds_status = 0";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			if ($val['Id'])
			{
				$vendorId	 = $val['Id'];
				$balance	 = $val['Balance'];
				if ($balance > 0)
				{
					$remarks = "Provisional deducted amount for prospective TDS refunded";
				}
				else
				{
					$remarks = "Reverted extra refunded amount: ( Provisional deducted amount for prospective TDS refunded)";
				}
				$datetime			 = '2023-03-31 23:59:59';
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $balance), $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_TDS, $balance, $remarks);
				$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $balance, $vendorId, Accounting::AT_OPERATOR, $remarks, UserInfo::model());
				if ($success)
				{
					$sqlUpdate = "UPDATE test.tds_22_23 SET tds_status = 1 WHERE Id = $vendorId";
					DBUtil::execute($sqlUpdate);

					echo "DONE - " . $vendorId . " - balance - " . $balance;
				}
			}
		}
	}

	public function actionBoostDependency($minScore = -300, $tripDays = 30, $tripCount = 5)
	{
		if ($minScore >= 0)
		{
			throw new Exception("Minimum Score should be less than 0");
		}
		$vendorArr = VendorStats::getEligibleBoostDependencyList($minScore);
		foreach ($vendorArr as $val)
		{
			$vndId		 = $val['vrs_vnd_id'];
			$totalTrips	 = VendorStats::getTotalTrips($vndId, $tripDays);
			if ($totalTrips > $tripCount)
			{
				$addBoost = VendorStats::addBoostDependency($vndId);
				Logger::info("Updating vendor: " . $vndId . " - $addBoost");
				VendorStats::updateDependency($vndId);
			}
		}
	}

	public function actionClosingLedgerBalance2021($limit = 1)
	{
		if ($limit > 0)
		{
			$sqlLimit = " LIMIT 0, $limit";
		}
		$sql	 = "SELECT * FROM test.closing_ledger_balance_2021_230926 WHERE status=0 ORDER BY id ASC $sqlLimit";
		$results = DBUtil::query($sql, DBUtil::SDB());

		foreach ($results as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				Logger::trace(json_encode($row));
				$id		 = $row["id"];
				$crRefId = $row['adt_trans_ref_id'];
				$drRefId = $row['adt_trans_ref_id'];

				switch ($row['adt_ledger_id'])
				{
					case 14:
						$crRefType	 = Accounting::AT_OPERATOR;
						$drRefType	 = Accounting::AT_OPERATOR;
						break;
					case 15:
					case 49:
						$crRefType	 = Accounting::AT_PARTNER;
						$drRefType	 = Accounting::AT_PARTNER;
						break;
					default:
						$crRefType	 = Accounting::AT_OTHER;
						$drRefType	 = Accounting::AT_OTHER;
						Logger::writeToConsole("Invalid Ledger");
						break;
				}

				$drLedgerId	 = Accounting::LI_CLOSING;
				$crLedgerId	 = $row['adt_ledger_id'];
				$drLedgerId1 = $row['adt_ledger_id'];
				$crLedgerId1 = Accounting::LI_OPENING;

				if ($row["closing"] < 0)
				{
					$crLedgerId	 = Accounting::LI_CLOSING;
					$drLedgerId	 = $row['adt_ledger_id'];
					$crLedgerId1 = $row['adt_ledger_id'];
					$drLedgerId1 = Accounting::LI_OPENING;
				}

				$sql		 = "SELECT GROUP_CONCAT(act_id) FROM account_trans_details atd 
						INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_ledger_id IN ({$row['adt_ledger_id']}) 
						INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_id<>atd.adt_id AND atd1.adt_active=1 AND atd1.adt_ledger_id IN (56,57) 
						WHERE ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0)) AND atd.adt_trans_ref_id='{$row['adt_trans_ref_id']}'";
				Logger::trace($sql);
				$oldActId	 = DBUtil::queryScalar($sql);
				if ($oldActId == '')
				{
					goto skipDelete;
				}

				$sqlUpd = "UPDATE account_transactions SET act_active=0 WHERE act_id IN ({$oldActId})";
				Logger::trace($sqlUpd);
				DBUtil::execute($sqlUpd);

				$sqlUpd1	 = "UPDATE account_trans_details SET adt_addt_params='Revised and removed' WHERE adt_trans_id IN ({$oldActId})";
				Logger::trace($sqlUpd1);
				DBUtil::execute($sqlUpd1);
				skipDelete:
				$amount		 = abs($row['closing']);
				$closingDate = '2021-03-31 23:59:59';
				$openingDate = '2021-04-01 00:00:00';
				$remarks	 = "Closing balance";
				$remarks1	 = "Opening balance";
				$success	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId, $drLedgerId, $amount, $closingDate, $remarks, $userInfo);
				$success1	 = AccountTransactions::addClosingOpeningLedgerBalance($crRefId, $drRefId, $crRefType, $drRefType, $crLedgerId1, $drLedgerId1, $amount, $openingDate, $remarks1, $userInfo);
				if (!$success || !$success1)
				{
					throw new Exception("\r\nFailed id={$id} ===Amount===" . $amount);
				}

				$sql	 = "SELECT act_id
						FROM account_trans_details atd 
						INNER JOIN account_transactions act ON atd.adt_trans_id=act.act_id AND act.act_active=1 AND atd.adt_active=1 AND atd.adt_ledger_id IN ({$row['adt_ledger_id']})  AND atd.adt_trans_ref_id='{$row['adt_trans_ref_id']}'
						INNER JOIN account_trans_details atd1 ON atd1.adt_trans_id=act.act_id AND atd1.adt_id<>atd.adt_id AND atd1.adt_active=1 AND atd1.adt_ledger_id IN (56) 
						WHERE act_date < '2021-04-01 00:00:00' AND ((atd.adt_amount>0 AND atd1.adt_amount<0) OR (atd1.adt_amount>0 AND atd.adt_amount<0))";
				Logger::trace($sql);
				$actId	 = DBUtil::queryScalar($sql);

				$sql	 = "INSERT INTO gozo_archive.`account_transactions` (SELECT * FROM `account_transactions` WHERE act_id IN ($actId))";
				Logger::trace($sql);
				$rows	 = DBUtil::command($sql)->execute();

				$sql2	 = "INSERT INTO gozo_archive.`account_trans_details` (SELECT * FROM `account_trans_details` WHERE adt_trans_id IN ($actId))";
				Logger::trace($sql2);
				$rows2	 = DBUtil::command($sql2)->execute();

				if ($rows > 0)
				{
					$sql	 = "DELETE FROM `account_transactions` WHERE act_id IN ($actId)";
					Logger::trace($sql);
					$rowsDel = DBUtil::command($sql)->execute();
				}
				if ($rows2 > 0)
				{
					$sql2		 = "DELETE FROM `account_trans_details` WHERE adt_trans_id IN ($actId)";
					Logger::trace($sql2);
					$rowsDel2	 = DBUtil::command($sql2)->execute();
				}

				$query = "UPDATE test.closing_ledger_balance_2021_230926 SET status=1 WHERE  id={$id} AND status =0";
				Logger::trace($query);
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
				Logger::writeToConsole("Done id={$id}");
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::error("\r\nError == " . $ex->getMessage(), true);
			}
			Logger::flush();
		}
	}

	public function actionProcessQrCode()
	{
		$check = Filter::checkProcess("system ProcessQrCode");
		if (!$check)
		{
			return;
		}
		$sql	 = "SELECT
						COUNT(*) as cnt,
						qrc_ent_id
					FROM `qr_code`
						INNER JOIN users ON users.user_id=qr_code.qrc_ent_id
					WHERE 1 
						AND qrc_ent_type=1
						AND qrc_active=1 
						AND qrc_status=3
					GROUP by qrc_ent_id 
					HAVING  cnt>1 ORDER BY  cnt ASC";
		$result	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			try
			{
				QrCode::processData($row['qrc_ent_id']);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}
	}

	public function actionCheckPlaceId()
	{
		$sql = "SELECT ltg_id, ltg_place_id FROM `lat_long` 
				WHERE ltg_active = 1 AND ltg_created_on < '2022-04-01 00:00:00' LIMIT 0, 100";

		$result = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $row)
		{
			try
			{
				$ltgId		 = $row['ltg_id'];
				$oldPlaceId	 = $row['ltg_place_id'];

				Logger::writeToConsole("ltg_id: " . $ltgId);

				$api = Config::getGoogleApiKey('apikey');
				$url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$oldPlaceId}&fields=place_id&key={$api}";

				$googleObj = GoogleMapAPI::getInstance()->callAPI($url, 3);

				if (!$googleObj)
				{
					continue;
				}
				if (!isset($googleObj['data']->result->place_id))
				{
					continue;
				}
				$newPlaceId = $googleObj['data']->result->place_id;
				if ($newPlaceId == '' || $oldPlaceId == $newPlaceId)
				{
					continue;
				}

				$sqlIns	 = "INSERT INTO test.google_updated_placeid (ltg_id, old_place_id, new_place_id) 
							VALUES ('{$ltgId}', '{$oldPlaceId}', '{$newPlaceId}')";
				$res	 = DBUtil::execute($sqlIns);
			}
			catch (Exception $ex)
			{
				Logger::writeToConsole("Error: " . $ex->getMessage());
			}
		}
	}

	public function actionUpdateCommissionMobisign1()
	{
		Logger::writeToConsole("actionUpdateCommissionMobisign");
		$sql	 = "SELECT * FROM test.mobisignCommissionCorrection_23_24 WHERE status = 0";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$model			 = Booking::model()->findByPk($row['bkg_id']);
				$agentCommission = $row['bkg_partner_commission'];

				if ($agentCommission > 0)
				{
					$addCommission = AccountTransactions::model()->AddCommission($model->bkg_pickup_date, $model->bkg_id, $model->bkg_agent_id, $agentCommission);
					if ($addCommission)
					{
						$bkg_id	 = $model->bkg_id;
						$query	 = "UPDATE test.mobisignCommissionCorrection_23_24 SET status = 1 WHERE bkg_id = $bkg_id";
						DBUtil::execute($query);

						BookingInvoice::updateGozoAmount($model->bkg_bcb_id);

						DBUtil::commitTransaction($transaction);

						echo 'BookingId = ' . $row['bkg_id'] . ' Commission = ' . $agentCommission . ' Success = ' . $addCommission . '\n';
					}
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				Logger::writeToConsole($ex->getMessage());
				Logger::error($ex);
				DBUtil::rollbackTransaction($transaction);
			}
		}

		Logger::writeToConsole("FF");
	}

	public function actionUpdateTTPriceRule()
	{
		$sql	 = "SELECT * FROM test.tempo_rate WHERE status = 0 ORDER BY id ASC LIMIT 0, 50";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$id						 = $row['id'];
			$prrId					 = $row['prr_id'];
			$ratePerKm				 = $row['prr_rate_per_km'];
			$ratePerKmExtra			 = $row['prr_rate_per_km_extra'];
			$minKm					 = $row['prr_min_km'];
			$minKmDay				 = $row['prr_min_km_day'];
			$dayDriverAllowance		 = $row['prr_day_driver_allowance'];
			$nightDriverAllowance	 = $row['prr_night_driver_allowance'];

			$model								 = PriceRule::model()->findByPk($prrId);
			$model->prr_rate_per_km				 = $ratePerKm;
			$model->prr_rate_per_km_extra		 = $ratePerKmExtra;
			$model->prr_min_km					 = $minKm;
			$model->prr_min_km_day				 = $minKmDay;
			$model->prr_day_driver_allowance	 = $dayDriverAllowance;
			$model->prr_night_driver_allowance	 = $nightDriverAllowance;
			$model->save();

			$sqlUpd = "UPDATE test.tempo_rate SET status = 1 WHERE status = 0 AND id={$id}";
			DBUtil::execute($sqlUpd);

			Logger::writeToConsole("Id: " . $id);
		}
	}

	public function actionVendorBalWriteoff()
	{
		$sql	 = "SELECT * FROM test.vendor_writeoff_06032024 tds where status = 0 LIMIT 0, 100";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			if ($val['vnd_id'])
			{
				$vendorId	 = $val['vnd_id'];
				$balance	 = $val['amount'];
				if ($balance > 0)
				{
					$remarks = "Vendor balance write off";
				}

				$datetime			 = '2024-03-05 23:59:59';
				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $balance), $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_BAD_DEBT, $balance, $remarks);
				$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $balance, $vendorId, Accounting::AT_OPERATOR, $remarks, UserInfo::model());
				if ($success)
				{
					$sqlUpdate = "UPDATE test.vendor_writeoff_06032024 SET status = 1 WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);

					$desc = "DONE - " . $vendorId . " - balance - " . $balance;
					Logger::writeToConsole($desc);
				}
			}
		}
	}

	public function actionNotificationVendorBalWriteoff()
	{
		$sql	 = "SELECT * FROM test.vendor_writeoff_06032024 where is_processed = 0 LIMIT 0,1000";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			if ($val['vnd_id'])
			{
				$vendorId	 = $val['vnd_id'];
				$hash		 = Yii::app()->shortHash->hash($vendorId);
				$balance	 = $val['amount'];
				$contactId	 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
				$row		 = ContactPhone::getNumber($contactId);
				if (!$row || empty($row) || !Filter::processPhoneNumber($row['number'], $row['code']))
				{
					$sqlUpdate = "UPDATE test.vendor_writeoff_06032024 SET is_processed=1,processed_at=NOW() WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);
				}
				else
				{
					$arrDBData			 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vendorId, 'ref_type' => WhatsappLog::REF_TYPE_VENDOR, 'ref_id' => $vendorId];
					$arrBody			 = Whatsapp::buildComponentBody([$balance]);
					$arrButton			 = Whatsapp::buildComponentButton([$hash]);
					$response			 = WhatsappLog::send($row['code'] . $row['number'], 'vendor_write_off_v1', $arrDBData, $arrBody, $arrButton, 'en_GB');
					$whatsappResponse	 = json_encode($response);
					$phonenumber		 = $row['code'] . $row['number'];
					$sqlUpdate			 = "UPDATE test.vendor_writeoff_06032024 SET is_processed=1,processed_at=NOW(),phone_number=$phonenumber,whatsapp_response='$whatsappResponse' WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);
				}
			}
		}
	}

	public function actionModifyDependency()
	{
		$sql	 = "SELECT vrs_vnd_id,vrs_total_trips,vrs_dependency,vrs_system_unassign_count+vrs_step1_unassign_count+vrs_step2_unassign_count as total_count
				FROM `vendor_stats`
				INNER JOIN vendors ON vendors.vnd_id = vendor_stats.vrs_vnd_id AND vnd_active =1
				WHERE vrs_total_trips IS NULL AND vrs_dependency =0";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$vndId			 = $row['vrs_vnd_id'];
			$totalUnassign	 = $row['total_count'];
			if ($totalUnassign == 0)
			{

				$sql = " UPDATE  vendor_stats SET vrs_dependency=65 WHERE vrs_vnd_id = $vndId";
				DBUtil::execute($sql);
			}
		}
	}

	public function actionNotificationVendorDownloadDco()
	{
		$sql	 = "SELECT * FROM test.vendor_dco_download_20032024 WHERE is_processed = 0 AND status=1 LIMIT 0,1000";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['vnd_id'])
				{
					$vendorId	 = $val['vnd_id'];
					$hash		 = Yii::app()->shortHash->hash($vendorId);
					$contactId	 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
					$row		 = ContactPhone::getNumber($contactId);
					if (!$row || empty($row) || !Filter::processPhoneNumber($row['number'], $row['code']))
					{
						$sqlUpdate = "UPDATE test.vendor_dco_download_20032024 SET is_processed =1,processed_at=NOW() WHERE vnd_id = $vendorId";
						DBUtil::execute($sqlUpdate);
					}
					else
					{
						$arrDBData			 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vendorId, 'ref_type' => WhatsappLog::REF_TYPE_VENDOR, 'ref_id' => $vendorId];
						$arrBody			 = Whatsapp::buildComponentBody([]);
						$arrButton			 = Whatsapp::buildComponentButton([$hash]);
						$response			 = WhatsappLog::send($row['code'] . $row['number'], 'gozo_partner_app', $arrDBData, $arrBody, $arrButton, 'en_GB');
						$whatsappResponse	 = json_encode($response);
						$phonenumber		 = $row['code'] . $row['number'];
						$sqlUpdate			 = "UPDATE test.vendor_dco_download_20032024 SET is_processed =1,phone_number=$phonenumber,whatsapp_response='$whatsappResponse',processed_at=NOW() WHERE vnd_id = $vendorId";
						DBUtil::execute($sqlUpdate);
					}
				}
			}
			catch (Exception $e)
			{
				Logger::exception($e);
			}
		}
	}

	public function actionNotificationVendorLoginReminder()
	{
		$sql	 = "SELECT * FROM test.vendor_login_reminder_21032024 where is_first_processed=0 LIMIT 0,1000";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			if ($val['vnd_id'])
			{
				$vendorId	 = $val['vnd_id'];
				$hash		 = Yii::app()->shortHash->hash($vendorId);
				$contactId	 = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
				$row		 = ContactPhone::getNumber($contactId);
				if (!$row || empty($row) || !Filter::processPhoneNumber($row['number'], $row['code']))
				{
					$sqlUpdate = "UPDATE test.vendor_login_reminder_21032024 SET is_first_processed =1,first_processed_at=NOW() WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);
				}
				else
				{
					$arrDBData			 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vendorId, 'ref_type' => WhatsappLog::REF_TYPE_VENDOR, 'ref_id' => $vendorId];
					$arrBody			 = Whatsapp::buildComponentBody([]);
					$arrButton			 = Whatsapp::buildComponentButton([$hash]);
					$response			 = WhatsappLog::send($row['code'] . $row['number'], 'vendor_login_reminder', $arrDBData, $arrBody, $arrButton, 'en_GB');
					$whatsappResponse	 = json_encode($response);
					$phonenumber		 = $row['code'] . $row['number'];
					$sqlUpdate			 = "UPDATE test.vendor_login_reminder_21032024 SET is_first_processed=1,first_processed_at=NOW(),phone_number=$phonenumber,whatsapp_response='$whatsappResponse' WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);
				}
			}
		}
	}

	public function actionSendQuoteExpiryReminderToCustomer()
	{
//		$bkgId = Yii::app()->request->getParam('bkgId');
		Booking::sendQuoteExpiryReminderToCustomer(4390384);
	}

	/**
	 * Function for Archiving Data From Tables
	 */
	public function actionWhatsappArchiveSingle()
	{
		$check = Filter::checkProcess("onetime whatsappArchiveSingle");
		if (!$check)
		{
			return;
		}

		Logger::create("command.system.WhatsappArchiveSingle Start Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		$archiveDB = 'gozo_archive';

		// Archive WhatsappLog
		#Logger::create("WhatsappLog Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		#WhatsappLog::model()->archiveData($archiveDB, 1000000, 1000);
		#Logger::create("WhatsappLog Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);



		Logger::create("Call Status Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
		CallStatus::model()->archiveData($archiveDB, 1000000, 1000);
		Logger::create("Call Status Ends Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);

		Logger::create("command.system.whatsappArchiveSingle end Today " . date("Y-m-d H:i:s"), CLogger::LEVEL_PROFILE);
	}

	public function actionNotificationMonthlyReminder($isSchedule = 0, $schedulePlatform = null)
	{
		$sql	 = "SELECT 
						uml.uml_month_count,
						uml.uml_month_id,
						uml.uml_user_id,
						CASE
							WHEN uml.uml_month_id =1 THEN 'January'
							WHEN uml.uml_month_id =2 THEN 'February'
							WHEN uml.uml_month_id =3 THEN 'March'
							WHEN uml.uml_month_id =4 THEN 'April'
							WHEN uml.uml_month_id =5 THEN 'May'
							WHEN uml.uml_month_id =6 THEN 'June'
							WHEN uml.uml_month_id =7 THEN 'July'
							WHEN uml.uml_month_id =8 THEN 'August'
							WHEN uml.uml_month_id =9 THEN 'September'
							WHEN uml.uml_month_id =10 THEN 'October'
							WHEN uml.uml_month_id =11 THEN 'November'
							WHEN uml.uml_month_id =12 THEN 'December'
							ELSE 'NA'
						END AS monthName
					FROM user_month_lifetime uml
					INNER JOIN 
					(
						SELECT `uml_user_id`, MAX(`uml_month_count`) AS max_month_count FROM user_month_lifetime GROUP BY uml_user_id
					) max_month_user_traveled ON uml.uml_user_id = max_month_user_traveled.uml_user_id AND uml.uml_month_count = max_month_user_traveled.max_month_count
					WHRE 1 
						AND (MONTH(now())+1)=uml.uml_month_id
					GROUP BY uml.uml_month_count
					ORDER BY  uml.uml_user_id ASC";
		$rows	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($rows as $val)
		{
			try
			{
				$contentParams	 = ['eventId' => "51", 'monthName' => val['monthName'], 'amount' => moneyFormatter(123)];
				$contactId		 = ContactProfile::getByEntityId($val['uml_user_id'], UserInfo::TYPE_CONSUMER);
				$row			 = ContactPhone::getNumber($contactId);
				if (!$row || empty($row))
				{
					goto skipAll;
				}
				if (!Filter::processPhoneNumber($row['number'], $row['code']))
				{
					goto skipAll;
				}
				$receiverParams		 = EventReceiver::setData(UserInfo::TYPE_CONSUMER, $val['uml_user_id'], WhatsappLog::REF_TYPE_USER, $val['uml_user_id'], null, $row['code'], $row['number'], null, null);
				$eventScheduleParams = EventSchedule::setData($bkgId, ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::BOOKING_CONFIRM, "User Month notificication", $isSchedule, CJSON::encode(array('bkgId' => $bkgId)), 10, $schedulePlatform);
				MessageEventMaster::processPlatformSequences(51, $contentParams, $receiverParams, $eventScheduleParams);
				skipAll:
			}
			catch (Exception $ex)
			{
				
			}
		}
	}

	public function actionDumpTdsData()
	{
//		Vendors::dumpTdsData();
		Vendors::populateOutstandingData();
	}
}
