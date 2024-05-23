<?php

class ZoneCommand extends BaseCommand
{

	public function actionRoutePerformance()
	{
		/* @var $modelsub BookingSub */
		$modelsub	 = new BookingSub();
		$result		 = $modelsub->getRoutePerformanceReport();

		$emailWrapper = new emailWrapper();
		$emailWrapper->zonalReport($result);
	}

	public function actionUpdateUnregisterOpsZones()
	{
		$sql	 = "SELECT unregister_operator.uo_phone , numbers.state , numbers.zone_ids , unregister_operator.uo_id
					FROM `unregister_operator`  
					INNER JOIN `numbers` ON numbers.mobilenumber=unregister_operator.uo_phone  WHERE 1
					GROUP BY unregister_operator.uo_phone  
					HAVING (state <> '')  
					ORDER BY unregister_operator.uo_id ASC";
		$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$uoId	 = $row['uo_id'];
				$state	 = $row['state'];
				if ($row['zone_ids'] != '')
				{
					$sql = "INSERT INTO `unregister_ops_zones`( `uoz_uo_id`, `uoz_area_id`, `uoz_area_type`)
							SELECT $uoId , zones.zon_id ,  3
							FROM
							  `zones`
							WHERE
							  zones.zon_id IN(
							  SELECT
								  zone_cities.zct_zon_id
							  FROM
								  `zone_cities`
							  WHERE zone_cities.zct_active=1
								  AND zone_cities.zct_cty_id IN(
								  SELECT
									  DISTINCT cities.cty_id
								  FROM
									  `cities`
								  LEFT JOIN `states` ON states.stt_id=cities.cty_state_id
								  WHERE
									  states.stt_name LIKE '%$state%' OR cities.cty_name LIKE '%$state%'
							  )
							) AND zones.zon_active=1";
					$e	 = Yii::app()->db->createCommand($sql)->execute();
					echo $e . " records are updated";
					echo "\n";
				}
			}
		}
	}

	public function actionInsertDZPP1Day()
	{
		$sql	 = "SELECT 
                    CONCAT('7','',LPAD(temp.RegionId,2,'0'),'',LPAD(temp.FromZoneId,5,'0'),'',LPAD(temp.ToZoneId,5,'0'),'',LPAD(temp.scv_id,3,'0'),'',LPAD(temp.booking_type,2,'0')) AS RowIdentifier,
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
                    temp.Profit,
                    temp.scv_label,
                    temp.scv_id,
                    temp.scv_scc_id,
                    temp.booking_type,
                    temp.SourceIsMaster,
                    temp.DestIsMaster, 
                    temp.target_boost AS TargetBoost,
                    (ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) AS TargetMargin,
                    ((((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)+temp.target_boost) - temp.Profit) + (IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(((temp.final_base_amount / temp.regular_base_amount)-1)*100, 2)))) AS DifffromGoal,
                    ROUND(if(temp.CountBooking > 4,(1+((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)-(temp.Profit)+(IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(((temp.final_base_amount / temp.regular_base_amount)-1)*100, 2))))/100),(1+(temp.CountBooking *((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)-(temp.Profit)+(IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(((temp.final_base_amount / temp.regular_base_amount)-1)*100, 2))))/400))),2) AS DZPP,
                    DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS CreateDate,
                    IF(temp.regular_base_amount=0 || temp.regular_base_amount IS NUll || temp.final_base_amount IS NUll ,0,ROUND(((temp.final_base_amount/temp.regular_base_amount)-1)*100,2)) AS DZPP_Applied,
                    temp.realizedVA,
                    temp.realizedCA
                    FROM(
                    SELECT 
                        CASE
                            WHEN stt.stt_zone = 1 THEN 'North'
                            WHEN stt.stt_zone = 2 THEN 'West'
                            WHEN stt.stt_zone = 3 THEN 'Central'
                            WHEN stt.stt_zone = 4 THEN 'South'
                            WHEN stt.stt_zone = 5 THEN 'East'
                            WHEN stt.stt_zone = 6 THEN 'North East'
                            WHEN stt.stt_zone = 7 THEN 'South'
                            ELSE '-'
                        END AS Region,
                        stt.stt_zone AS RegionId,
                        z1.zon_id AS FromZoneId,
                        z1.zon_name AS FromZoneName,
                        gz1.zon_name1 AS FromMasterZone,
                        gz1.zon_id AS FromMasterZoneId,
                        z2.zon_id AS ToZoneId,
                        z2.zon_name  AS ToZoneName,
                        gz2.zon_name1 AS ToMasterZone,
                        gz2.zon_id AS ToMasterZoneId,
                        COUNT(bkg_id) AS CountBooking,               
                        ROUND(((SUM(biv.bkg_gozo_amount- IFNULL(biv.bkg_credits_used,0)) / SUM(biv.bkg_net_base_amount)) * 100),2) AS Profit,
                        scvc.scv_label,
                        scvc.scv_id,                 
                        bkg.bkg_booking_type as booking_type,
                        ROUND(SUM(bpf.bkg_regular_base_amount),2) AS regular_base_amount,
                        SUM(CASE  bpf.bkg_surge_applied
                             WHEN 0 THEN ROUND((bpf.bkg_regular_base_amount),2)
                             WHEN 1 THEN ROUND((bpf.bkg_manual_base_amount),2)
                             WHEN 2 THEN ROUND((bpf.bkg_ddbp_base_amount),2)
                             WHEN 3 THEN 0
                             WHEN 4 THEN ROUND((bpf.bkg_dtbp_base_amount),2)
                             WHEN 5 THEN ROUND((bpf.bkg_profitability_base_amount),2)
                             WHEN 6 THEN ROUND((bpf.bkg_dzpp_base_amount),2)
                             WHEN 7 THEN ROUND((bpf.bkg_dzpp_base_amount),2)
							 WHEN 8 THEN ROUND((bpf.bkg_durp_base_amount),2)
							 WHEN 9 THEN ROUND((bpf.bkg_debp_base_amount),2)
							 WHEN 10 THEN ROUND((bpf.bkg_ddbpv2_base_amount),2)
							 WHEN 11 THEN ROUND((bpf.bkg_ddbpv2_base_amount),2)
                        END) AS final_base_amount, 
                        scvc.scv_scc_id,
                       (CASE scvc.scv_scc_id
                             WHEN 1 THEN 0
                             WHEN 2 THEN 8
                             WHEN 3 THEN 0
                             WHEN 4 THEN 12
                             WHEN 5 THEN 15
                             WHEN 6 THEN 0
                        END) AS target_boost,
                        if(z1.zon_id =  gz1.zon_id, 10, 15) AS SourceIsMaster,
                        if(z2.zon_id =  gz2.zon_id, 10, 15) AS DestIsMaster,
                        ROUND(SUM(bcb_vendor_amount)/SUM(bkg_trip_distance),2) AS realizedVA,
                        ROUND(SUM(bkg_base_amount)/SUM(bkg_trip_distance),2) AS realizedCA
                        FROM booking bkg
                        JOIN booking_cab ON booking_cab.bcb_id = bkg.bkg_bcb_id
                        JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id
                        JOIN booking_price_factor bpf ON bpf.bpf_bkg_id = bkg.bkg_id
                        JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
                        JOIN svc_class_vhc_cat scvc ON scvc.scv_id = bkg.bkg_vehicle_type_id AND scvc.scv_active=1
                        JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id AND vhc.vct_active=1
                        JOIN service_class sc ON scvc.scv_scc_id = sc.scc_id AND sc.scc_active=1
                        JOIN cities a ON a.cty_id = bkg.bkg_from_city_id AND a.cty_active=1
                        JOIN cities b ON b.cty_id = bkg.bkg_to_city_id   AND b.cty_active=1
                        JOIN states stt ON stt.stt_id = a.cty_state_id  AND stt.stt_active='1'
                        JOIN states s2 ON s2.stt_id = b.cty_state_id  AND s2.stt_active='1'
                        JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id AND zc1.zct_active=1
                        JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id  AND zc2.zct_active=1
                        JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id  AND z1.zon_active=1
                        JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id  AND z2.zon_active=1
                        JOIN geo_zones1 gz1 ON z1.zon_id = gz1.zon_id  
                        JOIN geo_zones1 gz2 ON z2.zon_id = gz2.zon_id
                        WHERE 1 
						AND bkg.bkg_pickup_date BETWEEN (CURDATE() - INTERVAL 01 DAY) AND CURDATE() 
						AND bkg.bkg_status IN (5,6,7) 
						AND bkg.bkg_booking_type NOT IN (14)
                        AND booking_cab.bcb_trip_type=0
						AND booking_cab.bcb_assign_mode NOT IN (3)
                        GROUP BY stt.stt_zone, z1.zon_id, z2.zon_id,scvc.scv_id,bkg.bkg_booking_type)                    
                        temp ORDER BY temp.profit DESC";
		$details = DBUtil::query($sql, DBUtil::SDB());
		foreach ($details as $row)
		{
			$result				 = Booking::getBookingCountByRowIdentifier($row['RowIdentifier']);
			$resultLead			 = BookingTemp::getBookingCountByRowIdentifier($row['RowIdentifier']);
			$row['cntLead']		 = $resultLead > 0 ? $resultLead : 0;
			$row['cntInquiry']	 = $result['cntInquiry'] > 0 ? $result['cntInquiry'] : 0;
			$row['cntCreated']	 = $result['cntCreated'] > 0 ? $result['cntCreated'] : 0;
			try
			{
				$sqldata = "INSERT INTO `dynamic_zone_surge_1day` (`dzs_row_identifier`,`dzs_regionname`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`, `dzs_active`,`dzs_va`, `dzs_ca`,`dzs_cntLead`,`dzs_cntInquiry`, `dzs_cntCreated`) VALUES 
                                ('" . $row['RowIdentifier'] . "','" . $row['Region'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['FromZoneName'] . "', '" . $row['FromMasterZone'] . "', '" . $row['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row['ToMasterZone'] . "', '" . $row['ToMasterZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row['booking_type'] . "', '" . $row['DestIsMaster'] . "', '" . $row['SourceIsMaster'] . "', '" . $row['TargetMargin'] . "', '" . $row['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "','1'," . $row['realizedVA'] . "," . $row['realizedCA'] . "," . $row['cntLead'] . "," . $row['cntInquiry'] . "," . $row['cntCreated'] . ")";
				DBUtil::execute($sqldata);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

	public function actionInsertDZPPGlobal()
	{
		Logger::info("\n*********************************** InsertDZPPGlobal Start *********************************************\n");
		$j				 = 0;
		$chkGolbally	 = true;
		$limitGlobally	 = 1000;
		while ($chkGolbally)
		{
			$sqlGlobally	 = "SELECT 
                            dzs_row_identifier,
                            dzs_regionname,                        
                            dzs_regionid,
                            dzs_fromzoneid,
                            dzs_fromzonename,
                            dzs_frommasterzone,
                            dzs_frommasterzoneid,
                            dzs_tozoneid,
                            dzs_tozonename,
                            dzs_tomasterzoneid,
                            dzs_fromzoneid,
                            dzs_countbooking,
                            dzs_profit,
                            dzs_scv_label,
                            dzs_scv_id,
                            dzs_scv_scc_id,
                            dzs_booking_type,
                            dzs_destismaster,
                            dzs_sourceismaster,                    
                            dzs_targetmargin,
                            dzs_difffromgoal,
                            dzs_dzpp,
                            dzs_dzpp_applied,
                            dzs_target_boost,
                            dzs_createdate,
                            dzs_active,
                            zsg_row_identifier,                           
                            zsg_countbooking,                           
                            zsg_difffromgoal,                           
                            zsg_dzpp_applied                           
                            FROM dynamic_zone_surge_1day
                            LEFT JOIN zone_surge_global  ON  dynamic_zone_surge_1day.dzs_row_identifier=zone_surge_global.zsg_row_identifier                            
                            WHERE 1 ORDER BY dynamic_zone_surge_1day.dzs_id ASC LIMIT $j, $limitGlobally";
			$detailsGlobally = DBUtil::query($sqlGlobally, DBUtil::SDB());
			foreach ($detailsGlobally as $row)
			{
				try
				{
					$days	 = Rate::lastModifidedDays($row['dzs_fromzoneid'], $row['dzs_tozoneid'], $row['dzs_scv_id']);
					$param	 = array();
					if (!$days)
					{
						if ($row['zsg_row_identifier'] == null)
						{
							$sqldata = "INSERT INTO `zone_surge_global` (`zsg_row_identifier`,`zsg_regionname`,`zsg_regionid`, `zsg_fromzoneid`, `zsg_fromzonename`, `zsg_frommasterzone`, `zsg_frommasterzoneid`, `zsg_tozoneid`, `zsg_tozonename`, `zsg_tomasterzone`, `zsg_tomasterzoneid`, `zsg_countbooking`, `zsg_profit`, `zsg_scv_label`, `zsg_scv_id`,`zsg_scv_scc_id`, `zsg_booking_type`, `zsg_destismaster`, `zsg_sourceismaster`, `zsg_targetmargin`, `zsg_difffromgoal`, `zsg_dzpp`,`zsg_dzpp_applied`, `zsg_target_boost`,`zsg_createdate`, `zsg_active`) VALUES 
                                ('" . $row['dzs_row_identifier'] . "','" . $row['dzs_regionname'] . "','" . $row['dzs_regionid'] . "','" . $row['dzs_fromzoneid'] . "', '" . $row['dzs_fromzonename'] . "', '" . $row['dzs_frommasterzone'] . "', '" . $row['dzs_frommasterzoneid'] . "', '" . $row['dzs_tozoneid'] . "', '" . $row['dzs_tozonename'] . "', '" . $row['dzs_tomasterzone'] . "', '" . $row['dzs_tomasterzoneid'] . "', '" . $row['dzs_countbooking'] . "', '" . $row['dzs_profit'] . "', '" . $row['dzs_scv_label'] . "', '" . $row['dzs_scv_id'] . "', '" . $row['dzs_scv_scc_id'] . "','" . $row['dzs_booking_type'] . "', '" . $row['dzs_destismaster'] . "', '" . $row['dzs_sourceismaster'] . "', '" . $row['dzs_targetmargin'] . "', '" . $row['dzs_difffromgoal'] . "', '" . $row['dzs_dzpp'] . "', '" . $row['dzs_dzpp_applied'] . "','" . $row['dzs_target_boost'] . "', '" . $row['dzs_createdate'] . "', '1');";
						}
						else
						{
							$updateBookingCount			 = $row['zsg_countbooking'] + $row['dzs_countbooking'];
							$upDateDifffromGoal			 = ((( $row['zsg_difffromgoal'] + ($row['zsg_dzpp_applied'] - 1) * 100) * $row['zsg_countbooking'] * 0.4) + (($row['dzs_difffromgoal'] + ($row['dzs_dzpp_applied'] - 1) * 100) * $row['dzs_countbooking'] * 0.6)) / ( ($row['zsg_countbooking'] * 0.4) + ($row['dzs_countbooking'] * 0.6));
							$upDateDzppApplied			 = 1; // putting 1 to ignore this value 
							$upDateDzpp					 = round($upDateDifffromGoal > 0 ? ($updateBookingCount > 4 ? (1 + ($upDateDifffromGoal / 100)) : 1 + (($updateBookingCount * $upDateDifffromGoal) / 400)) : 1, 2);
							$param['zsg_dzpp']			 = $upDateDzpp;
							$param['zsg_countbooking']	 = $updateBookingCount;
							$param['zsg_difffromgoal']	 = $upDateDifffromGoal;
							$param['zsg_dzpp_applied']	 = $upDateDzppApplied;
							$sqldata					 = "UPDATE `zone_surge_global` SET `zsg_dzpp` =:zsg_dzpp,`zsg_countbooking` =:zsg_countbooking, `zsg_difffromgoal` =:zsg_difffromgoal, `zsg_dzpp_applied` =:zsg_dzpp_applied WHERE `zone_surge_global`.`zsg_row_identifier` ='" . $row['zsg_row_identifier'] . "'";
						}
					}
					else
					{
						if ($row['zsg_row_identifier'] == null)
						{
							$sqldata = "INSERT INTO `zone_surge_global` (`zsg_row_identifier`,`zsg_regionname`,`zsg_rate_update_days`,`zsg_regionid`, `zsg_fromzoneid`, `zsg_fromzonename`, `zsg_frommasterzone`, `zsg_frommasterzoneid`, `zsg_tozoneid`, `zsg_tozonename`, `zsg_tomasterzone`, `zsg_tomasterzoneid`, `zsg_countbooking`, `zsg_profit`, `zsg_scv_label`, `zsg_scv_id`,`zsg_scv_scc_id`, `zsg_booking_type`, `zsg_destismaster`, `zsg_sourceismaster`, `zsg_targetmargin`, `zsg_difffromgoal`, `zsg_dzpp`,`zsg_dzpp_applied`, `zsg_target_boost`,`zsg_createdate`, `zsg_active`) VALUES 
                                ('" . $row['dzs_row_identifier'] . "','" . $row['dzs_regionname'] . "','" . $days . "','" . $row['dzs_regionid'] . "','" . $row['dzs_fromzoneid'] . "', '" . $row['dzs_fromzonename'] . "', '" . $row['dzs_frommasterzone'] . "', '" . $row['dzs_frommasterzoneid'] . "', '" . $row['dzs_tozoneid'] . "', '" . $row['dzs_tozonename'] . "', '" . $row['dzs_tomasterzone'] . "', '" . $row['dzs_tomasterzoneid'] . "', '" . $row['dzs_countbooking'] . "', '" . $row['dzs_profit'] . "', '" . $row['dzs_scv_label'] . "', '" . $row['dzs_scv_id'] . "', '" . $row['dzs_scv_scc_id'] . "','" . $row['dzs_booking_type'] . "', '" . $row['dzs_destismaster'] . "', '" . $row['dzs_sourceismaster'] . "', '" . $row['dzs_targetmargin'] . "', '" . $row['dzs_difffromgoal'] . "', '" . $row['dzs_dzpp'] . "', '" . $row['dzs_dzpp_applied'] . "','" . $row['dzs_target_boost'] . "', '" . $row['dzs_createdate'] . "', '1');";
						}
						else
						{
							$updateBookingCount			 = $row['zsg_countbooking'] + $row['dzs_countbooking'];
							$upDateDifffromGoal			 = ((( $row['zsg_difffromgoal'] + ($row['zsg_dzpp_applied'] - 1) * 100) * $row['zsg_countbooking'] * 0.4) + (($row['dzs_difffromgoal'] + ($row['dzs_dzpp_applied'] - 1) * 100) * $row['dzs_countbooking'] * 0.6)) / ( ($row['zsg_countbooking'] * 0.4) + ($row['dzs_countbooking'] * 0.6));
							$upDateDzppApplied			 = 1; // putting 1 to ignore this value 
							$upDateDzpp					 = round($upDateDifffromGoal > 0 ? ($updateBookingCount > 4 ? (1 + ($upDateDifffromGoal / 100)) : 1 + (($updateBookingCount * $upDateDifffromGoal) / 400)) : 1, 2);
							$param['zsg_dzpp']			 = $upDateDzpp;
							$param['zsg_countbooking']	 = $updateBookingCount;
							$param['zsg_difffromgoal']	 = $upDateDifffromGoal;
							$param['zsg_dzpp_applied']	 = $upDateDzppApplied;
							$param['days']				 = $days;
							$sqldata					 = "UPDATE `zone_surge_global` SET `zsg_dzpp` =:zsg_dzpp,zsg_rate_update_days=:days, `zsg_countbooking` =:zsg_countbooking, `zsg_difffromgoal` =:zsg_difffromgoal, `zsg_dzpp_applied` =:zsg_dzpp_applied WHERE `zone_surge_global`.`zsg_row_identifier` ='" . $row['zsg_row_identifier'] . "'";
						}
					}
					DBUtil::execute($sqldata, $param);
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
					Logger::info("\n*********************************** zone_surge_global Error Start *********************************************\n");
					Logger::info($ex->getMessage());
					Logger::info("\n*********************************** zone_surge_global Error Ends *********************************************\n");
					Logger::writeToConsole($ex->getMessage());
				}
			}
			$j += $limitGlobally;
			Logger::info("\n*********************************** InsertDZPPGlobal Count Start *********************************************\n");
			Logger::info($j);
			Logger::info("\n***********************************InsertDZPPGlobal Count Ends *********************************************\n");
			if ($detailsGlobally->rowCount == 0)
			{
				break;
			}
		}
		Logger::info("\n*********************************** InsertDZPPGlobal Ends *********************************************\n");
	}

	public function actionInsertDZPP90Day()
	{
		$i				 = 0;
		$chk			 = true;
		$limit			 = 500;
		$svcResult		 = SvcClassVhcCat::getAllVctIdByScvId();
		$vehiclesMileage = CJSON::decode(Config::get('vehicles.mileage'), true);
		$fuelPrice		 = Config::get('fuelPrice');
		$fuelPrice		 = $fuelPrice == null ? 120 : $fuelPrice;
		$DZPPFactor		 = Config::get('DZPP_SURGE_FACTOR');
		if (!empty($DZPPFactor))
		{
			$result		 = CJSON::decode($DZPPFactor);
			$z1Factor	 = $result['Z1_FACTOR'];
			$z2Factor	 = $result['Z2_FACTOR'];
			$z3Factor	 = $result['Z3_FACTOR'];
		}
		else
		{
			$z1Factor	 = 10;
			$z2Factor	 = 11.5;
			$z3Factor	 = 13;
		}
		while ($chk)
		{
			$sql	 = "SELECT 
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
						IF(tdz_id IS NOT NULL,0,IF((temp.CountBooking/90)>1,'1',IF(((temp.CountBooking/90)>0.5 AND (temp.CountBooking/90)<=1 ),'2','3'))) AS ZoneType,
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
                        ((((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)+temp.target_boost) - temp.Profit) + temp.DZPP_Applied) AS DifffromGoal,
                        ROUND(if(temp.CountBooking > 4,(1+(ROUND(( ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)-temp.profit,2)
                        +LEAST(temp.DZPP_Applied,30))/100),(1+(temp.CountBooking* (ROUND(( ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)-temp.profit,2)
                        +LEAST(temp.DZPP_Applied,30))/400))),2) AS DZPP,
                        CURDATE() AS CreateDate,
                        temp.realizedVA AS dzs_va,
                        temp.realizedCA AS dzs_ca,
                        temp.dzs_cntLead AS dzs_cntLead,
                        temp.dzs_cntInquiry AS dzs_cntInquiry,
                        temp.dzs_cntCreated AS dzs_cntCreated,
                        temp.conversionPer AS dzs_conversionPer,
                        temp.completionPer AS dzs_completionPer
                        FROM
                        (
                            SELECT 
                            dzs_row_identifier AS RowIdentifier,
							top_demand_zone.tdz_id,
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
                            ROUND(((SUBSTRING_INDEX(SUBSTRING_INDEX(group_concat(dzs_profit order by dzs_profit),',', floor(1+((count(dzs_profit)-1) / 2))), ',', -1))+(SUBSTRING_INDEX(SUBSTRING_INDEX(group_concat(dzs_profit order by dzs_profit), ',', ceiling(1+((count(dzs_profit)-1) / 2))), ',', -1)))/2 ,2) AS Profit,
                            dzs_scv_label AS  scv_label,
                            dzs_scv_id AS scv_id,
                            dzs_scv_scc_id AS scv_scc_id,
                            dzs_booking_type as booking_type,                           
                            IF(top_demand_zone.tdz_id IS NOT NULL,top_demand_zone.tdz_target_margin,IF
                            (
                                (
                                    SUM(dynamic_zone_surge_1day.dzs_cntLead)=0                                    
                                    OR                                     
                                    SUM(dynamic_zone_surge_1day.dzs_cntInquiry)=0                                    
                                    OR                                     
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntInquiry)/SUM(dynamic_zone_surge_1day.dzs_cntLead))*100), 2)<45
                                    OR 
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntCreated)/SUM(dynamic_zone_surge_1day.dzs_cntInquiry))*100), 2) <20
                                )
                                ,8,
								IF(SUM(dzs_countbooking)>90,$z1Factor,IF(SUM(dzs_countbooking)>45,$z2Factor,$z3Factor))
                            )) AS SourceIsMaster,                                
                             IF(top_demand_zone.tdz_id IS NOT NULL,top_demand_zone.tdz_target_margin,IF
                            (
                                (
                                    SUM(dynamic_zone_surge_1day.dzs_cntLead)=0                                    
                                    OR                                     
                                    SUM(dynamic_zone_surge_1day.dzs_cntInquiry)=0                                    
                                    OR 
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntInquiry)/SUM(dynamic_zone_surge_1day.dzs_cntLead))*100), 2)<45 
                                    OR 
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntCreated)/SUM(dynamic_zone_surge_1day.dzs_cntInquiry))*100), 2) <20
                                ),
                                8,
                                IF(SUM(dzs_countbooking)>90,$z1Factor,IF(SUM(dzs_countbooking)>45,$z2Factor,$z3Factor))
                            )) AS DestIsMaster,
                            
                            ROUND(SUM(dzs_target_boost * dzs_countbooking ) / SUM(dzs_countbooking), 2)  AS target_boost,
                            ROUND(SUM(dzs_dzpp_applied * dzs_countbooking) / SUM(dzs_countbooking), 2)  AS DZPP_Applied,
                            ROUND(SUM(dzs_va)/SUM(IF(dzs_va>0,1,NULL)), 2) AS realizedVA,
                            ROUND(SUM(dzs_ca) / SUM(IF(dzs_ca>0,1,NULL)), 2) AS realizedCA,
                            SUM(dzs_cntLead) AS  dzs_cntLead,
                            SUM(dzs_cntInquiry) AS  dzs_cntInquiry,
                            SUM(dzs_cntCreated) AS dzs_cntCreated,
							IF(SUM(dynamic_zone_surge_1day.dzs_cntInquiry)>0,ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntCreated)/SUM(dynamic_zone_surge_1day.dzs_cntInquiry))*100), 2),0) AS conversionPer,
                            IF(SUM(dynamic_zone_surge_1day.dzs_cntCreated)>0,ROUND(((SUM(dynamic_zone_surge_1day.dzs_countbooking)/SUM(dynamic_zone_surge_1day.dzs_cntCreated))*100), 2),0) AS completionPer       
                            FROM dynamic_zone_surge_1day
							LEFT JOIN top_demand_zone ON top_demand_zone.tdz_row_identifier=dynamic_zone_surge_1day.dzs_row_identifier AND top_demand_zone.tdz_active=1
                            WHERE dzs_active = 1 AND dzs_createdate BETWEEN  (CURDATE() - INTERVAL 90 DAY) AND CURDATE()
                            GROUP BY dzs_row_identifier 
                            ORDER BY dzs_id ASC 
                            LIMIT $i, $limit
                        ) temp ORDER BY temp.profit DESC";
			$details = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					$fuelVa					 = round($fuelPrice / $vehiclesMileage[$svcResult[$row['scv_id']]], 2);
					$row['dzs_suggested_va'] = max($row['dzs_va'], $fuelVa);
					$row['dzs_suggested_ca'] = round($row['dzs_suggested_va'] * $row['DZPP'], 2);
					$row['dzs_suggested_va'] = $row['dzs_suggested_va'] > 0 ? $row['dzs_suggested_va'] : 0;
					$row['dzs_suggested_ca'] = $row['dzs_suggested_ca'] > 0 ? $row['dzs_suggested_ca'] : 0;
					$days					 = Rate::lastModifidedDays($row['FromZoneId'], $row['ToZoneId'], $row['scv_id']);
					$row['dzs_va']			 = $row['dzs_va'] > 0 ? $row['dzs_va'] : 0;
					$row['dzs_ca']			 = $row['dzs_ca'] > 0 ? $row['dzs_ca'] : 0;
					$row['additional_param'] = json_encode(array('description' => "Initail data dump value"));
					if (!$days)
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge` (`dzs_row_identifier`,`dzs_90_14_final_dzpp`,`dzs_additional_param`,`dzs_regionname`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_zone_type`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`, `dzs_va`, `dzs_ca`,`dzs_cntLead`, `dzs_cntInquiry`,`dzs_cntCreated`, `dzs_conversionPer`,`dzs_completionPer`, `dzs_suggested_va`,`dzs_suggested_ca`) VALUES 
                                ('" . $row ['RowIdentifier'] . "','" . $row['DZPP'] . "','" . $row['additional_param'] . "','" . $row['Region'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row ['FromZoneName'] . "', '" . $row ['FromMasterZone'] . "', '" . $row ['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row ['ToMasterZone'] . "', '" . $row ['ToMasterZoneId'] . "', '" . $row ['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row ['booking_type'] . "', '" . $row ['DestIsMaster'] . "', '" . $row ['SourceIsMaster'] . "', '" . $row ['TargetMargin'] . "', '" . $row ['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "'," . $row['dzs_va'] . "," . $row['dzs_ca'] . "," . $row['dzs_cntLead'] . "," . $row['dzs_cntInquiry'] . "," . $row['dzs_cntCreated'] . "," . $row ['dzs_conversionPer'] . "," . $row ['dzs_completionPer'] . "," . $row ['dzs_suggested_va'] . "," . $row['dzs_suggested_ca'] . ")";
					}
					else
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge` (`dzs_row_identifier`,`dzs_90_14_final_dzpp`,`dzs_additional_param`,`dzs_regionname`,`dzs_rate_update_days`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_zone_type`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`, `dzs_va`, `dzs_ca`,`dzs_cntLead`, `dzs_cntInquiry`,`dzs_cntCreated`, `dzs_conversionPer`,`dzs_completionPer`, `dzs_suggested_va`,`dzs_suggested_ca`) VALUES 
                                ('" . $row ['RowIdentifier'] . "','" . $row['DZPP'] . "','" . $row['additional_param'] . "','" . $row['Region'] . "','" . $days . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row ['FromZoneName'] . "', '" . $row ['FromMasterZone'] . "', '" . $row ['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row ['ToMasterZone'] . "', '" . $row ['ToMasterZoneId'] . "', '" . $row ['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row ['booking_type'] . "', '" . $row ['DestIsMaster'] . "', '" . $row ['SourceIsMaster'] . "', '" . $row ['TargetMargin'] . "', '" . $row ['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "'," . $row['dzs_va'] . "," . $row['dzs_ca'] . "," . $row['dzs_cntLead'] . "," . $row['dzs_cntInquiry'] . "," . $row['dzs_cntCreated'] . "," . $row ['dzs_conversionPer'] . "," . $row ['dzs_completionPer'] . "," . $row ['dzs_suggested_va'] . "," . $row['dzs_suggested_ca'] . ")";
					}

					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
				}
			}
			$i += $limit;
			if ($details->rowCount == 0)
			{
				break;
			}
		}
		$dropQry = "DELETE FROM `dynamic_zone_surge` WHERE dzs_createdate < CURDATE()";
		DBUtil::execute($dropQry);
		Yii::app()->cache->set(CacheDependency::buildCacheId(CacheDependency::Type_Surge), time());
		$this->InsertDZPP14Day();
		$this->UpdateDZPP();
	}

	public function actionUpdateZoneVendorMapped()
	{
		Logger::info("\n*********************************** UpdateZoneVendorMapped Start *********************************************\n");
		$zoneModels = ZoneVendorMap::model()->getZoneVendorMapped();

		Logger::info("\n*********************************** UpdateZoneVendorMapped DELETE Start *********************************************\n");
		$dropQry = "TRUNCATE TABLE zone_vendor_map";
		DBUtil::execute($dropQry);
		Logger::info("\n*********************************** UpdateZoneVendorMapped  DELETE ENDS *********************************************\n");

		$lastVendorId = 0;
		foreach ($zoneModels as $row)
		{
			try
			{
				if ($row['HomeZone'] != "")
				{
					$models					 = new ZoneVendorMap();
					$models->zvm_zon_id		 = $row['HomeZone'];
					$models->zvm_vnd_id		 = $row['vnd_id'];
					$models->zvm_zone_type	 = 1;
					if (!$models->save())
					{
						$error = $models->errors;
					}
				}


				if ($row['AcceptedZone'] != "")
				{
					$acceptedZoneArr = explode(",", $row['AcceptedZone']);
					foreach ($acceptedZoneArr as $acceptedZone)
					{
						$models					 = new ZoneVendorMap();
						$models->zvm_zon_id		 = $acceptedZone;
						$models->zvm_vnd_id		 = $row['vnd_id'];
						$models->zvm_zone_type	 = 2;
						if (!$models->save())
						{
							$error = $models->errors;
						}
					}
				}
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole($ex->getMessage());
				Logger::writeToConsole($ex->getMessage());
			}
		}
		Logger::info("\n*********************************** UpdateZoneVendorMapped ENDS *********************************************\n");
	}

	public function actionInsertDZPP1DayHist()
	{
		$begin	 = new DateTime("2021-01-01");
		$end	 = new DateTime("2022-04-25");
		for ($i = $begin; $i <= $end; $i->modify('+1 day'))
		{
			$date		 = $i->format("Y-m-d");
			$actualDate	 = $date . " 12:00:00";
			$fromDate	 = $date . " 00:00:00";
			$toDate		 = $date . " 23:59:59";
			Logger::writeToConsole("" . $fromDate . " => " . $toDate);
			$sql		 = "SELECT 
                    CONCAT('7','',LPAD(temp.RegionId,2,'0'),'',LPAD(temp.FromZoneId,5,'0'),'',LPAD(temp.ToZoneId,5,'0'),'',LPAD(temp.scv_id,3,'0'),'',LPAD(temp.booking_type,2,'0')) AS RowIdentifier,                    temp.RegionId,
                    temp.RegionId,                    
                    temp.FromZoneId,                    
                    temp.ToZoneId,                   
                    temp.CountBooking,                    
                    temp.scv_id,                   
                    temp.booking_type,                   
                    '$actualDate' AS CreateDate                   
                    FROM
                    (
                        SELECT
                        stt.stt_zone AS RegionId,
                        z1.zon_id AS FromZoneId,                        
                        z2.zon_id AS ToZoneId,                       
                        COUNT(bkg_id) AS CountBooking, 
                        scvc.scv_id,                 
                        bkg.bkg_booking_type as booking_type            
                        FROM booking bkg
                        JOIN booking_invoice biv ON biv.biv_bkg_id = bkg.bkg_id                       
                        JOIN booking_trail btr ON btr.btr_bkg_id = bkg.bkg_id
                        JOIN svc_class_vhc_cat scvc ON scvc.scv_id = bkg.bkg_vehicle_type_id AND scvc.scv_active=1
                        JOIN vehicle_category vhc ON scvc.scv_vct_id = vhc.vct_id AND vhc.vct_active=1
                        JOIN service_class sc ON scvc.scv_scc_id = sc.scc_id AND sc.scc_active=1
                        JOIN cities a ON a.cty_id = bkg.bkg_from_city_id AND a.cty_active=1
                        JOIN cities b ON b.cty_id = bkg.bkg_to_city_id   AND b.cty_active=1
                        JOIN states stt ON stt.stt_id = a.cty_state_id  AND stt.stt_active='1'
                        JOIN states s2 ON s2.stt_id = b.cty_state_id  AND s2.stt_active='1'
                        JOIN zone_cities zc1 ON zc1.zct_cty_id = bkg.bkg_from_city_id AND zc1.zct_active=1
                        JOIN zone_cities zc2 ON zc2.zct_cty_id = bkg.bkg_to_city_id  AND zc2.zct_active=1
                        JOIN zones z1 ON z1.zon_id = zc1.zct_zon_id  AND z1.zon_active=1
                        JOIN zones z2 ON z2.zon_id = zc2.zct_zon_id  AND z2.zon_active=1
                        JOIN geo_zones1 gz1 ON z1.zon_id = gz1.zon_id  
                        JOIN geo_zones1 gz2 ON z2.zon_id = gz2.zon_id
                        WHERE 1 AND bkg.bkg_pickup_date BETWEEN '$fromDate' AND '$toDate' AND bkg.bkg_status IN (6, 7)                      
                        GROUP BY stt.stt_zone, z1.zon_id, z2.zon_id,scvc.scv_id,bkg.bkg_booking_type
                    ) temp ";
			$details	 = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					$sqldata = "INSERT INTO `dynamic_zone_surge_1day_Hist`(`dzs_row_identifier`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_tozoneid`,`dzs_countbooking`, `dzs_scv_id`,`dzs_booking_type`, `dzs_createdate`, `dzs_active`) VALUES ('" . $row['RowIdentifier'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['scv_id'] . "','" . $row['booking_type'] . "','" . $row['CreateDate'] . "', '1');";
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Logger::exception($ex);
					Logger::writeToConsole($ex->getMessage());
				}
			}
		}
	}

	public function actionInsertDZPP90DayHist()
	{
		$begin	 = new DateTime("2019-01-01");
		$end	 = new DateTime("2022-04-25");
		for ($j = $begin; $j <= $end; $j->modify('+1 day'))
		{
			$date		 = $j->format("Y-m-d");
			$actualDate	 = $date . " 12:00:00";
			$fromDate	 = $date . " 00:00:00";
			Logger::writeToConsole($fromDate);
			$i			 = 0;
			$chk		 = true;
			$limit		 = 1000;
			while ($chk)
			{
				$sql = "SELECT 
                        temp.RowIdentifier AS RowIdentifier,                      
                        temp.RegionId,
                        temp.FromZoneId,                       
                        temp.ToZoneId,                       
                        temp.CountBooking,
                        IF((temp.CountBooking/90)>1,'1',IF(((temp.CountBooking/90)>0.5 AND (temp.CountBooking/90)<=1 ),'2','3')) AS ZoneType,                       
                        temp.scv_id,                        
                        temp.booking_type,                              
                        '$actualDate' AS CreateDate
                        FROM
                        (
                            SELECT 
                            dzs_row_identifier AS RowIdentifier,                                                   
                            dzs_regionid AS  RegionId,
                            dzs_fromzoneid AS  FromZoneId,                            
                            dzs_tozoneid AS  ToZoneId,                           
                            SUM(dzs_countbooking) AS CountBooking,                           
                            dzs_scv_id AS scv_id,                           
                            dzs_booking_type as booking_type                            
                            FROM dynamic_zone_surge_1day_Hist
                            WHERE dzs_active = 1 AND dzs_createdate BETWEEN  ('$fromDate' - INTERVAL 90 DAY) AND '$fromDate'
                            GROUP BY dzs_row_identifier 
                            ORDER BY dzs_id ASC 
                            LIMIT $i, $limit
                        ) temp ";

				$details = DBUtil::query($sql, DBUtil::SDB());
				foreach ($details as $row)
				{
					try
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge_Hist`(`dzs_row_identifier`,`dzs_regionid`, `dzs_fromzoneid`,`dzs_tozoneid`,`dzs_countbooking`, `dzs_zone_type`, `dzs_scv_id`,`dzs_booking_type`,`dzs_createdate`) VALUES 
                            ('" . $row['RowIdentifier'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['scv_id'] . "','" . $row['booking_type'] . "', '" . $row['CreateDate'] . "');";
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

			$result = BookingPref::allgetBookingZoneTypeDateWise($date);
			foreach ($result as $row)
			{
				try
				{
					$fromCity	 = $row['bkg_from_city_id'];
					$toCity		 = $row['bkg_to_city_id'];
					$scv_id		 = $row['bkg_vehicle_type_id'];
					$tripType	 = $row['bkg_booking_type'];
					$res		 = DynamicZoneSurge::getDZPPZoneType($fromCity, $toCity, $scv_id, $tripType);
					$model		 = BookingPref::model()->getByBooking($row['bkg_id']);
					$zoneType	 = ($res['dzs_zone_type'] != null) ? $res['dzs_zone_type'] : 3;
					$sqldata	 = "INSERT INTO `booking_pref_Hist`(`bpr_bkg_id`,`bpr_zone_type`,`bpr_create_date`,`bpr_active`)
                                VALUES ('" . $row['bkg_id'] . "','" . $zoneType . "','" . $fromDate . "', '1');";
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
					Logger::exception($ex);
				}
			}


			$dropQry = "DELETE FROM `dynamic_zone_surge_Hist` WHERE dzs_createdate <= '$fromDate'";
			DBUtil::execute($dropQry);
		}
	}

	public function actionInsertZoneType()
	{
		$begin	 = new DateTime("2022-01-01");
		$end	 = new DateTime("2022-04-25");
		for ($j = $begin; $j <= $end; $j->modify('+1 day'))
		{
			$date		 = $j->format("Y-m-d");
			$actualDate	 = $date . " 12:00:00";
			$fromDate	 = $date . " 00:00:00";
			Logger::writeToConsole($fromDate);
			$result		 = BookingPref::getBookingRowIdentifier($date);
			foreach ($result as $row)
			{
				try
				{
					$bkg_id			 = $row['bkg_id'];
					$rowIdentifier	 = $row['bpr_row_identifier'];
					$year			 = $row['Year'];
					$res			 = DynamicZoneSurge::Yearwise_rowIdentifier($year, $rowIdentifier);
					$zoneType		 = ($res['dzs_zone_type'] != null) ? $res['dzs_zone_type'] : 3;
					$sqldata		 = "INSERT INTO `booking_pref_Hist_Year`(`bpr_bkg_id`,`bpr_zone_type`,`bpr_create_date`,`bpr_active`)
                                VALUES ('" . $bkg_id . "','" . $zoneType . "','" . $fromDate . "', '1');";
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
					Logger::exception($ex);
				}
			}
		}
	}

	public function actionInsertZoneTypeNew()
	{
		$begin	 = new DateTime("2022-01-01");
		$end	 = new DateTime("2022-04-25");
		for ($j = $begin; $j <= $end; $j->modify('+1 day'))
		{
			$date		 = $j->format("Y-m-d");
			$actualDate	 = $date . " 12:00:00";
			$fromDate	 = $date . " 00:00:00";
			Logger::writeToConsole($fromDate);
			$result		 = BookingPref::getBookingZoneIdentifier($date);
			foreach ($result as $row)
			{
				try
				{
					$bkg_id			 = $row['bkg_id'];
					$zoneIdentifier	 = $row['bpr_zone_identifier'];
					$year			 = $row['Year'];
					$res			 = DynamicZoneSurge::Yearwise_zoneIdentifier($year, $zoneIdentifier);
					$zoneType		 = ($res['dzs_zone_type'] != null) ? $res['dzs_zone_type'] : 3;
					$sqldata		 = "INSERT INTO `booking_pref_Hist_Zone_Year`(`bpr_bkg_id`,`bpr_zone_type`,`bpr_create_date`,`bpr_active`)
                                       VALUES ('" . $bkg_id . "','" . $zoneType . "','" . $fromDate . "', '1');";
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Logger::writeToConsole($ex->getMessage());
					Logger::exception($ex);
				}
			}
		}
	}

	public function InsertDZPP14Day()
	{
		$i				 = 0;
		$chk			 = true;
		$limit			 = 500;
		$svcResult		 = SvcClassVhcCat::getAllVctIdByScvId();
		$vehiclesMileage = CJSON::decode(Config::get('vehicles.mileage'), true);
		$fuelPrice		 = Config::get('fuelPrice');
		$fuelPrice		 = $fuelPrice == null ? 120 : $fuelPrice;
		$DZPPFactor		 = Config::get('DZPP_SURGE_FACTOR');
		if (!empty($DZPPFactor))
		{
			$result		 = CJSON::decode($DZPPFactor);
			$z1Factor	 = $result['Z1_FACTOR'];
			$z2Factor	 = $result['Z2_FACTOR'];
			$z3Factor	 = $result['Z3_FACTOR'];
		}
		else
		{
			$z1Factor	 = 10;
			$z2Factor	 = 11.5;
			$z3Factor	 = 13;
		}
		while ($chk)
		{
			$sql	 = "SELECT 
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
                       	IF(tdz_id IS NOT NULL,0,IF((temp.CountBooking/14)>1,'1',IF(((temp.CountBooking/14)>0.5 AND (temp.CountBooking/14)<=1 ),'2','3'))) AS ZoneType,
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
                        (ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+LEAST(temp.DZPP_Applied,30)) AS DifffromGoal,
                        ROUND(if(temp.CountBooking > 4,(1+(ROUND(( ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)-temp.profit,2)
                        +LEAST(temp.DZPP_Applied,30))/100),(1+(temp.CountBooking* (ROUND(( ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost)-temp.profit,2)
                        +LEAST(temp.DZPP_Applied,30))/400))),2) AS DZPP,
                        CURDATE() AS CreateDate,
                        temp.realizedVA AS dzs_va,
                        temp.realizedCA AS dzs_ca,
                        temp.dzs_cntLead AS dzs_cntLead,
                        temp.dzs_cntInquiry AS dzs_cntInquiry,
                        temp.dzs_cntCreated AS dzs_cntCreated,
                        temp.conversionPer AS dzs_conversionPer,
                        temp.completionPer AS dzs_completionPer
                        FROM
                        (
                            SELECT 
                            dzs_row_identifier AS RowIdentifier,
							top_demand_zone.tdz_id,
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
							ROUND(((SUBSTRING_INDEX(SUBSTRING_INDEX(group_concat(dzs_profit order by dzs_profit),',', floor(1+((count(dzs_profit)-1) / 2))), ',', -1))+(SUBSTRING_INDEX(SUBSTRING_INDEX(group_concat(dzs_profit order by dzs_profit), ',', ceiling(1+((count(dzs_profit)-1) / 2))), ',', -1)))/2 ,2) AS Profit,
                            dzs_scv_label AS  scv_label,
                            dzs_scv_id AS scv_id,
                            dzs_scv_scc_id AS scv_scc_id,
                            dzs_booking_type as booking_type,                           
                             IF(top_demand_zone.tdz_id IS NOT NULL,top_demand_zone.tdz_target_margin,IF
                            (
                                (
                                    SUM(dynamic_zone_surge_1day.dzs_cntLead)=0                                    
                                    OR                                     
                                    SUM(dynamic_zone_surge_1day.dzs_cntInquiry)=0                                    
                                    OR                                     
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntInquiry)/SUM(dynamic_zone_surge_1day.dzs_cntLead))*100), 2)<45
                                    OR 
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntCreated)/SUM(dynamic_zone_surge_1day.dzs_cntInquiry))*100), 2) <20
                                )
                                ,8,
                                IF(SUM(dzs_countbooking)>14,$z1Factor,IF(SUM(dzs_countbooking)>7,$z2Factor,$z3Factor))
                            )) AS SourceIsMaster,                                 
                            IF(top_demand_zone.tdz_id IS NOT NULL,top_demand_zone.tdz_target_margin,IF
                            (
                                (
                                    SUM(dynamic_zone_surge_1day.dzs_cntLead)=0                                    
                                    OR                                     
                                    SUM(dynamic_zone_surge_1day.dzs_cntInquiry)=0                                    
                                    OR 
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntInquiry)/SUM(dynamic_zone_surge_1day.dzs_cntLead))*100), 2)<45 
                                    OR 
                                    ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntCreated)/SUM(dynamic_zone_surge_1day.dzs_cntInquiry))*100), 2) <20
                                ),
                                8,
                                IF(SUM(dzs_countbooking)>14,$z1Factor,IF(SUM(dzs_countbooking)>7,$z2Factor,$z3Factor))
                            )) AS DestIsMaster,                            
                            ROUND(SUM(dzs_target_boost * dzs_countbooking ) / SUM(dzs_countbooking), 2)  AS target_boost,
                            ROUND(SUM(dzs_dzpp_applied * dzs_countbooking) / SUM(dzs_countbooking), 2)  AS DZPP_Applied,
                            ROUND(SUM(dzs_va)/SUM(IF(dzs_va>0,1,NULL)), 2) AS realizedVA,
                            ROUND(SUM(dzs_ca) / SUM(IF(dzs_ca>0,1,NULL)), 2) AS realizedCA,
                            SUM(dzs_cntLead) AS  dzs_cntLead,
                            SUM(dzs_cntInquiry) AS  dzs_cntInquiry,
                            SUM(dzs_cntCreated) AS dzs_cntCreated,
			                IF(SUM(dynamic_zone_surge_1day.dzs_cntInquiry)>0,ROUND(((SUM(dynamic_zone_surge_1day.dzs_cntCreated)/SUM(dynamic_zone_surge_1day.dzs_cntInquiry))*100), 2),0) AS conversionPer,
                            IF(SUM(dynamic_zone_surge_1day.dzs_cntCreated)>0,ROUND(((SUM(dynamic_zone_surge_1day.dzs_countbooking)/SUM(dynamic_zone_surge_1day.dzs_cntCreated))*100), 2),0) AS completionPer       
                            FROM dynamic_zone_surge_1day
							LEFT JOIN top_demand_zone ON top_demand_zone.tdz_row_identifier=dynamic_zone_surge_1day.dzs_row_identifier AND top_demand_zone.tdz_active=1
                            WHERE dzs_active = 1 AND dzs_createdate BETWEEN  (CURDATE() - INTERVAL 14 DAY) AND CURDATE()
                            GROUP BY dzs_row_identifier 
                            ORDER BY dzs_id ASC 
                            LIMIT $i, $limit
                        ) temp ORDER BY temp.profit DESC";
			$details = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					$fuelVa					 = round($fuelPrice / $vehiclesMileage[$svcResult[$row['scv_id']]], 2);
					$row['dzs_suggested_va'] = max($row['dzs_va'], $fuelVa);
					$row['dzs_suggested_ca'] = round($row['dzs_suggested_va'] * $row['DZPP'], 2);
					$row['dzs_suggested_va'] = $row['dzs_suggested_va'] > 0 ? $row['dzs_suggested_va'] : 0;
					$row['dzs_suggested_ca'] = $row['dzs_suggested_ca'] > 0 ? $row['dzs_suggested_ca'] : 0;
					$days					 = Rate::lastModifidedDays($row['FromZoneId'], $row['ToZoneId'], $row['scv_id']);
					$row['dzs_va']			 = $row['dzs_va'] > 0 ? $row['dzs_va'] : 0;
					$row['dzs_ca']			 = $row['dzs_ca'] > 0 ? $row['dzs_ca'] : 0;
					if (!$days)
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge_14day` (`dzs_row_identifier`,`dzs_regionname`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_zone_type`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`, `dzs_va`, `dzs_ca`,`dzs_cntLead`, `dzs_cntInquiry`,`dzs_cntCreated`, `dzs_conversionPer`,`dzs_completionPer`, `dzs_suggested_va`,`dzs_suggested_ca`) VALUES 
                                ('" . $row ['RowIdentifier'] . "','" . $row['Region'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row ['FromZoneName'] . "', '" . $row ['FromMasterZone'] . "', '" . $row ['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row ['ToMasterZone'] . "', '" . $row ['ToMasterZoneId'] . "', '" . $row ['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row ['booking_type'] . "', '" . $row ['DestIsMaster'] . "', '" . $row ['SourceIsMaster'] . "', '" . $row ['TargetMargin'] . "', '" . $row ['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "'," . $row['dzs_va'] . "," . $row['dzs_ca'] . "," . $row['dzs_cntLead'] . "," . $row['dzs_cntInquiry'] . "," . $row['dzs_cntCreated'] . "," . $row ['dzs_conversionPer'] . "," . $row ['dzs_completionPer'] . "," . $row ['dzs_suggested_va'] . "," . $row['dzs_suggested_ca'] . ")";
					}
					else
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge_14day` (`dzs_row_identifier`,`dzs_regionname`,`dzs_rate_update_days`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_zone_type`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`, `dzs_va`, `dzs_ca`,`dzs_cntLead`, `dzs_cntInquiry`,`dzs_cntCreated`, `dzs_conversionPer`,`dzs_completionPer`, `dzs_suggested_va`,`dzs_suggested_ca`) VALUES 
                                ('" . $row ['RowIdentifier'] . "','" . $row ['Region'] . "','" . $days . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row ['FromZoneName'] . "', '" . $row ['FromMasterZone'] . "', '" . $row ['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row ['ToMasterZone'] . "', '" . $row ['ToMasterZoneId'] . "', '" . $row ['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row ['booking_type'] . "', '" . $row ['DestIsMaster'] . "', '" . $row ['SourceIsMaster'] . "', '" . $row ['TargetMargin'] . "', '" . $row ['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "'," . $row['dzs_va'] . "," . $row['dzs_ca'] . "," . $row['dzs_cntLead'] . "," . $row['dzs_cntInquiry'] . "," . $row['dzs_cntCreated'] . "," . $row ['dzs_conversionPer'] . "," . $row ['dzs_completionPer'] . "," . $row ['dzs_suggested_va'] . "," . $row['dzs_suggested_ca'] . ")";
					}
					DBUtil::execute($sqldata);
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
				}
			}
			$i += $limit;
			if ($details->rowCount == 0)
			{
				break;
			}
		}
		$dropQry = "DELETE FROM `dynamic_zone_surge_14day` WHERE dzs_createdate < CURDATE()";
		DBUtil::execute($dropQry);
	}

	public function UpdateDZPP()
	{
		$sql = "SELECT
                dynamic_zone_surge.dzs_id,
                dynamic_zone_surge.dzs_row_identifier AS rowIdentifier_90D,
                dynamic_zone_surge.dzs_cntLead AS cntLead_90D,
                dynamic_zone_surge.dzs_cntInquiry AS cntInquiry_90D,
                IF((dynamic_zone_surge.dzs_cntLead)>0,ROUND((((dynamic_zone_surge.dzs_cntInquiry)/(dynamic_zone_surge.dzs_cntLead))*100), 2),0) AS conversionLeadPer_90D,
                dynamic_zone_surge.dzs_dzpp AS dzpp_90D,
                dynamic_zone_surge_14day.dzs_row_identifier AS rowIdentifier_14D,
                dynamic_zone_surge_14day.dzs_cntLead AS cntLead_14D,
                dynamic_zone_surge_14day.dzs_cntInquiry AS cntInquiry_14D,
                IF((dynamic_zone_surge_14day.dzs_cntLead)>0,ROUND((((dynamic_zone_surge_14day.dzs_cntInquiry)/(dynamic_zone_surge_14day.dzs_cntLead))*100), 2),0) AS conversionLeadPer_14D,
                dynamic_zone_surge_14day.dzs_dzpp AS dzpp_14D
                FROM dynamic_zone_surge 
                LEFT JOIN dynamic_zone_surge_14day ON dynamic_zone_surge_14day.dzs_row_identifier=dynamic_zone_surge.dzs_row_identifier
                WHERE 1 AND dynamic_zone_surge.dzs_active=1";

		$details = DBUtil::query($sql, DBUtil::SDB());
		foreach ($details as $row)
		{
			try
			{
				if ($row['rowIdentifier_14D'] == null)
				{
					$realizedDzpp	 = $row['dzpp_90D'] > 1 ? round((1 + (min($row['dzpp_90D'], 1.30) - 1) * 0.50), 2) : $row['dzpp_90D'];
					$additionalParam = json_encode(array('description' => "No value found  in 14 days", 'cntLead_14D' => $row['cntLead_14D'], 'cntLead_90D' => $row['cntLead_90D'], 'cntInquiry_14D' => $row['cntInquiry_14D'], 'cntInquiry_90D' => $row['cntInquiry_90D'], 'conversionLeadPer_14D' => $row['conversionLeadPer_14D'], 'conversionLeadPer_90D' => $row['conversionLeadPer_90D']));
					$sql			 = "UPDATE dynamic_zone_surge SET dynamic_zone_surge.dzs_90_14_final_dzpp=:realizedDzpp,dynamic_zone_surge.dzs_additional_param=:additionalParam WHERE 1 AND dynamic_zone_surge.dzs_id=:dzs_id";
					DBUtil::execute($sql, ['realizedDzpp' => $realizedDzpp, 'additionalParam' => $additionalParam, 'dzs_id' => $row['dzs_id']]);
				}
				else if ($row['rowIdentifier_14D'] != null && ($row['dzpp_14D']) < $row['dzpp_90D'])
				{
					$realizedDzpp	 = $row['dzpp_14D'] > 1 ? round((1 + (min($row['dzpp_14D'], 1.30) - 1) * 0.50), 2) : $row['dzpp_14D'];
					$additionalParam = json_encode(array('description' => "row['dzpp_14D']) < row['dzpp_90D']", 'cntLead_14D' => $row['cntLead_14D'], 'cntLead_90D' => $row['cntLead_90D'], 'cntInquiry_14D' => $row['cntInquiry_14D'], 'cntInquiry_90D' => $row['cntInquiry_90D'], 'conversionLeadPer_14D' => $row['conversionLeadPer_14D'], 'conversionLeadPer_90D' => $row['conversionLeadPer_90D']));
					$sql			 = "UPDATE dynamic_zone_surge SET dynamic_zone_surge.dzs_90_14_final_dzpp=:realizedDzpp,dynamic_zone_surge.dzs_additional_param=:additionalParam WHERE 1 AND dynamic_zone_surge.dzs_id=:dzs_id";
					DBUtil::execute($sql, ['realizedDzpp' => $realizedDzpp, 'dzs_id' => $row['dzs_id'], 'additionalParam' => $additionalParam]);
				}
				else if ($row['conversionLeadPer_14D'] < 0.75 * $row['conversionLeadPer_90D'])
				{
					$realizedDzpp	 = $row['dzpp_90D'] > 1 ? round((1 + (min($row['dzpp_90D'], 1.30) - 1) * 0.50), 2) : $row['dzpp_90D'];
					$additionalParam = json_encode(array('description' => 'conversionLeadPer_14D<0.75 * conversionLeadPer_90D', 'cntLead_14D' => $row['cntLead_14D'], 'cntLead_90D' => $row['cntLead_90D'], 'cntInquiry_14D' => $row['cntInquiry_14D'], 'cntInquiry_90D' => $row['cntInquiry_90D'], 'conversionLeadPer_14D' => $row['conversionLeadPer_14D'], 'conversionLeadPer_90D' => $row['conversionLeadPer_90D']));
					$sql			 = "UPDATE dynamic_zone_surge SET dynamic_zone_surge.dzs_90_14_final_dzpp=:realizedDzpp,dynamic_zone_surge.dzs_additional_param=:additionalParam WHERE 1 AND dynamic_zone_surge.dzs_id=:dzs_id";
					DBUtil::execute($sql, ['realizedDzpp' => $realizedDzpp, 'dzs_id' => $row['dzs_id'], 'additionalParam' => $additionalParam]);
				}
				else if (($row['cntLead_14D'] / 14) < 0.75 * ($row['cntLead_90D']) / 90)
				{
					$realizedDzpp	 = $row['dzpp_90D'] > 1 ? round((1 + (min($row['dzpp_90D'], 1.30) - 1) * 0.50), 2) : $row['dzpp_90D'];
					$additionalParam = json_encode(array('description' => 'cntLead_14D<cntLead_90D', 'cntLead_14D' => $row['cntLead_14D'], 'cntLead_90D' => $row['cntLead_90D'], 'cntInquiry_14D' => $row['cntInquiry_14D'], 'cntInquiry_90D' => $row['cntInquiry_90D'], 'conversionLeadPer_14D' => $row['conversionLeadPer_14D'], 'conversionLeadPer_90D' => $row['conversionLeadPer_90D']));
					$sql			 = "UPDATE dynamic_zone_surge SET dynamic_zone_surge.dzs_90_14_final_dzpp=:realizedDzpp,dynamic_zone_surge.dzs_additional_param=:additionalParam WHERE 1 AND dynamic_zone_surge.dzs_id=:dzs_id";
					DBUtil::execute($sql, ['realizedDzpp' => $realizedDzpp, 'dzs_id' => $row['dzs_id'], 'additionalParam' => $additionalParam]);
				}
			}
			catch (Exception $ex)
			{
				Filter::writeToConsole($ex->getMessage());
			}
		}
	}

	public function actionInsertDURP1Day()
	{
		$sql	 = "SELECT 
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
                        CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AS createDate,
                        CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AS updateDate
                        FROM  booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id
                        INNER JOIN cities ON cities.cty_id=booking.bkg_from_city_id
                        INNER JOIN zone_cities ON zone_cities.zct_cty_id=cities.cty_id
                        INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id 
                        WHERE 1 
                        AND booking.bkg_status IN (6,7) 
                        AND booking.bkg_pickup_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND 
                        CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
                        AND booking.bkg_active=1
                        GROUP BY zones.zon_id 

                        UNION 

                        SELECT 
                         0 AS bookingcnt,
                        COUNT(apt_entity_id) AS Vendorcnt,
                        zones.zon_id,
                        zones.zon_name,
                        CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AS createDate,
                        CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00') AS updateDate
                        FROM  booking
                        INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id
                        INNER JOIN cities ON cities.cty_id=booking.bkg_from_city_id
                        INNER JOIN zone_cities ON zone_cities.zct_cty_id=cities.cty_id
                        INNER JOIN zones ON zones.zon_id=zone_cities.zct_zon_id 
                        INNER JOIN home_service_zones ON home_service_zones.hsz_home_id=zones.zon_id   AND home_service_zones.hsz_active=1
						INNER JOIN vendors ON vendors.vnd_id = booking_cab.bcb_vendor_id AND bcb_vendor_id>0
                        INNER JOIN app_tokens ON app_tokens.apt_entity_id=vendors.vnd_id  
                        WHERE 1 
                        AND booking.bkg_status IN (6,7) 
                        AND booking.bkg_pickup_date BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 00:00:00') AND 
                        CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
                        AND app_tokens.apt_user_type =2 
                        AND app_tokens.apt_date  BETWEEN  CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 00:00:00')
                        AND  CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY), ' 23:59:59')
                        AND booking.bkg_active=1
                        GROUP BY zones.zon_id
                    ) TEMP WHERE 1 GROUP BY TEMP.zon_id";
		$details = DBUtil::query($sql, DBUtil::SDB());
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

	public function actionInsertDURP90Day()
	{
		$sql	 = "SELECT
                    dynamic_uncommon_route_1day.dur_zone_id AS zon_id,
                    dynamic_uncommon_route_1day.dur_zone_name AS zon_name,
                    SUM(dynamic_uncommon_route_1day.dur_booking_count) AS bookingcnt,
                    SUM(dynamic_uncommon_route_1day.dur_vendor_count) AS Vendorcnt,
                    CURDATE() AS createDate,
                    CURDATE() AS updateDate
                    FROM dynamic_uncommon_route_1day
                    WHERE 1 
                    AND dynamic_uncommon_route_1day.dur_createdate
                    BETWEEN CONCAT(DATE_SUB(CURDATE(),INTERVAL 90 DAY),' 00:00:00') AND  CONCAT(DATE_SUB(CURDATE(),INTERVAL 1 DAY),' 23:59:59')
                    AND dynamic_uncommon_route_1day.dur_active=1
                    GROUP BY dynamic_uncommon_route_1day.dur_zone_id";
		$details = DBUtil::query($sql, DBUtil::SDB());
		foreach ($details as $row)
		{
			try
			{
				$bookingSurgeFactor		 = ($row['bookingcnt'] != null && $row['bookingcnt'] < 5) ? (1 - ($row['bookingcnt'] / 5) * 0.25) : 1;
				$vendorSurgeFactor		 = ($row['Vendorcnt'] != null && $row['Vendorcnt'] < 5) ? (1 - ($row['Vendorcnt'] / 5) * 0.10) : 1;
				$row['dur_surge_factor'] = 1 + (min($bookingSurgeFactor, $vendorSurgeFactor) / 100);
				$sqldata				 = "INSERT INTO `dynamic_uncommon_route` (`dur_zone_id`,`dur_zone_name`,`dur_surge_factor`,`dur_booking_count`, `dur_vendor_count`, `dur_createdate`, `dur_updatedate`, `dur_active`) VALUES 
                                ('" . $row['zon_id'] . "','" . $row['zon_name'] . "','" . $row['dur_surge_factor'] . "','" . $row['bookingcnt'] . "','" . $row['Vendorcnt'] . "', '" . $row['createDate'] . "', '" . $row['updateDate'] . "', '1')";
				DBUtil::execute($sqldata);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
			}
		}

		$dropQry = "DELETE FROM `dynamic_uncommon_route` WHERE dur_createdate < CURDATE()";
		DBUtil::execute($dropQry);
	}

//    public function actionDURPBooking()
//    {
//        $sql     = "SELECT 
//                    DISTINCT bcb_id
//                    FROM booking
//                    INNER JOIN booking_cab ON booking_cab.bcb_id=booking.bkg_bcb_id
//                    INNER JOIN booking_price_factor ON booking_price_factor.bpf_bkg_id=booking.bkg_id
//                    INNER JOIN cities ON cities.cty_id =booking.bkg_from_city_id
//                    INNER JOIN zone_cities  ON zone_cities.zct_cty_id=cities.cty_id
//                    INNER JOIN zones ON zone_cities.zct_zon_id=zones.zon_id
//                    INNER JOIN zone_vendor_map ON zone_vendor_map.zvm_zon_id=zones.zon_id
//                    WHERE 1 
//                    booking_price_factor.bkg_surge_applied=8
//                    AND
//                    (
//                        bpr.bkg_critical_score > 0.75 OR (bpr.bkg_critical_score > 0.65 AND btr.btr_is_dem_sup_misfire = 1) OR btr.btr_nmi_flag=1
//                    )";
//        $details = DBUtil::query($sql, DBUtil::SDB());
//        foreach ($details as $row)
//        {
//            try
//            {
//
//                BookingCab::notifyVendorsForPendingBookings($row['bcb_id']);
////                $bcbmodel      = BookingCab::model()->findByPk($row['bcb_id']);
////                $bookings      = $bcbmodel->bookings;
////                $model         = $bookings[0];
////                $cabType       = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
////                $pickupAddress = $model->bkg_pickup_address;
////                $pickupDate    = \DateTimeFormat::SQLDateTimeToLocaleDateTime($model->bkg_pickup_date);
////                $cabType       = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
////                $tripType      = trim($model->getBookingType($model->bkg_booking_type));
////                $message       = "$cabType required at $pickupAddress | on $pickupDate, $tripType";
////                $title         = "$cabType required urgent";
////                $payLoadData   = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
////                AppTokens::model()->notifyVendor($row['vnd_id'], $payLoadData, $message, $title);
//            }
//            catch (Exception $ex)
//            {
//                Logger::exception($ex);
//            }
//        }
//    }

	public function actionUpdateZoneCapacity()
	{
		$i		 = 0;
		$chk	 = true;
		$limit	 = 500;
		while ($chk)
		{
			$sql	 = "SELECT row_identifier,median_count_30 FROM rowIdentifier_completions_last30d WHERE 1 AND active=1 LIMIT $i, $limit";
			$details = DBUtil::query($sql, DBUtil::SDB());
			foreach ($details as $row)
			{
				try
				{
					QuotesSituation::updateRowIdentifierMedainCapacity($row['row_identifier'], $row['median_count_30']);
					$regionId			 = (int) substr($row['row_identifier'], 1, 2);
					$fromZone			 = (int) substr($row['row_identifier'], 3, 5);
					$tripType			 = (int) substr($row['row_identifier'], 16, 2);
					$type				 = in_array($tripType, array("4", "9", "10", "11", "12")) ? 1 : 2;
					$param				 = array('regionId' => $regionId, 'fromZone' => $fromZone, 'tripType' => $type);
					$sql				 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromZone,5,'0'),LPAD(:tripType,2,'0')) AS demandIdentifier FROM DUAL";
					$demandIdentifier	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
					QuotesZoneSituation::updateDemandIdentifierMedainCapacity($demandIdentifier, $row['median_count_30']);
				}
				catch (Exception $ex)
				{
					Filter::writeToConsole($ex->getMessage());
				}
			}
			$i += $limit;
			if ($details->rowCount == 0)
			{
				break;
			}
		}
		// finally update  qzs_capacity by 30 to get per day capapcaity
		$sql = "UPDATE `quotes_zone_situation` SET qzs_capacity=CEIL(qzs_capacity/30) WHERE 1 AND DATE(qzs_updated_date)=CURDATE()";
		DBUtil::execute($sql);
	}

}
