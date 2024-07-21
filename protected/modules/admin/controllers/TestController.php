<?php

class TestController extends Controller
{
    
    
	public $newHome		 = false;
	public $layoutSufix	 = 0;

	/**
	 * @return array action filters
	 */
        
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'users' => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	  public function filters()
	  {
	  // return the filter configuration for this controller, e.g.:
	  return array(
	  'inlineFilterName',
	  array(
	  'class'=>'path.to.FilterClass',
	  'propertyName'=>'propertyValue',
	  ),
	  );
	  }

	  public function actions()
	  {
	  // return external action classes, e.g.:
	  return array(
	  'action1'=>'path.to.ActionClass',
	  'action2'=>array(
	  'class'=>'path.to.AnotherActionClass',
	  'propertyName'=>'propertyValue',
	  ),
	  );
	  }
	 */

	public function actionVehicleTaxPref()
	{
		$model			 = new RegionPreference;
		$dataProvider	 = $model->saveVehicleTaxPref();
	}

	

	public function actionTestNotification()
	{
		BookingVendorRequest::model()->notifyRejectedVendor(2419450, 1);
	}

	public function actiontestdate()
	{
		//$date1 = date("Y-m-d");
		$date2		 = "2016-11-19";
		$date3		 = date("Y-m-d", strtotime("-5 years"));
		echo $date2 . "===" . $date3;
		$datediff	 = strtotime($date3) - strtotime($date2);
		echo "<BR>" . ($datediff / (60 * 60 * 24));
		if ($datediff < 0)
		{
			echo "more than 5 years";
		}
		else
		{
			echo "less than 5 year";
		}
	}

	public function actiontestvadocs()
	{
		echo "<pre>";
		VendorAgmtDocs::uploadAllToS3(2);
	}

	public function actiontest()
	{
		$data = Vendors::model()->getJSON('1');
		echo "<pre>";
		print_r($data);
	}

	public static function actioncheckCabAssigned()
	{
		$bcbid	 = 2420345;
		$sql	 = "SELECT booking_cab.bcb_driver_id , booking_cab.bcb_cab_id 
                FROM `booking_cab`
                WHERE booking_cab.bcb_id=$bcbid";
		$row	 = DBUtil::queryRow($sql);
//        print_r($row);exit;
		if ($row['bcb_driver_id'] != NULL && $row['bcb_driver_id'] != 0 && $row['bcb_cab_id'] != NULL && $row['bcb_cab_id'] != 0)
		{
			$step = 2;
		}
		else
		{
			$step = 1;
		} echo $step;
	}

	public function actionInsertDZPP1Day()
	{
		$sql	 = "SELECT 
                    CONCAT(temp.RegionId,'-',temp.FromZoneId,'-',temp.ToZoneId,'-',temp.scv_id,'-',temp.booking_type) AS RowIdentifier,
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
                    (ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(temp.final_base_amount / temp.regular_base_amount, 2))) AS DifffromGoal,
                    ROUND(if((ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(temp.final_base_amount / temp.regular_base_amount, 2)))>0,if(temp.CountBooking > 4,(1+((ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(temp.final_base_amount / temp.regular_base_amount, 2))))/100),(1+(temp.CountBooking *(ROUND(if(temp.Profit <(ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost),((ROUND((temp.SourceIsMaster+temp.DestIsMaster)/2,2)+temp.target_boost) - temp.Profit), 0),2)+IF(temp.regular_base_amount = 0 || temp.regular_base_amount IS NULL || temp.final_base_amount IS NULL,  0, ROUND(temp.final_base_amount / temp.regular_base_amount, 2))))/400)),1),2) AS DZPP,
                    DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS CreateDate,
                    IF(temp.regular_base_amount=0 || temp.regular_base_amount IS NUll || temp.final_base_amount IS NUll ,0,ROUND(temp.final_base_amount/temp.regular_base_amount,2)) AS DZPP_Applied
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
                        ROUND(((SUM(biv.bkg_gozo_amount) / SUM(biv.bkg_total_amount)) * 100),2) AS Profit,
                        scvc.scv_label,
                        scvc.scv_id,                 
                        bkg.bkg_booking_type as booking_type,
                        ROUND(SUM(bpf.bkg_regular_base_amount),2) AS regular_base_amount,
                        (CASE  bpf.bkg_surge_applied
                             WHEN 0 THEN ROUND(SUM(bpf.bkg_regular_base_amount),2)
                             WHEN 1 THEN ROUND(SUM(bpf.bkg_manual_base_amount),2)
                             WHEN 2 THEN ROUND(SUM(bpf.bkg_ddbp_base_amount),2)
                             WHEN 3 THEN 0
                             WHEN 4 THEN ROUND(SUM(bpf.bkg_dtbp_base_amount),2)
                             WHEN 5 THEN ROUND(SUM(bpf.bkg_profitability_base_amount),2)
                             WHEN 6 THEN ROUND(SUM(bpf.bkg_dzpp_base_amount),2)
                             WHEN 7 THEN ROUND(SUM(bpf.bkg_dzpp_base_amount),2)
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
                        if(z1.zon_id =  gz1.zon_id, 12, 17) AS SourceIsMaster,
                        if(z2.zon_id =  gz2.zon_id, 12, 17) AS DestIsMaster
                        FROM booking bkg
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
                        WHERE 1 AND bkg.bkg_pickup_date BETWEEN (CURDATE() - INTERVAL 01 DAY) AND CURDATE() AND bkg.bkg_status IN (6, 7)                      
                        GROUP BY stt.stt_zone, z1.zon_id, z2.zon_id,scvc.scv_id,bkg.bkg_booking_type)                    
                        temp ORDER BY temp.profit DESC";
		$details = DBUtil::query($sql, DBUtil::SDB());
		foreach ($details as $row)
		{
			try
			{
				$sqldata = "INSERT INTO `dynamic_zone_surge_1day` (`dzs_row_identifier`,`dzs_regionname`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`, `dzs_active`) VALUES 
                                ('" . $row['RowIdentifier'] . "','" . $row['Region'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['FromZoneName'] . "', '" . $row['FromMasterZone'] . "', '" . $row['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row['ToMasterZone'] . "', '" . $row['ToMasterZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row['booking_type'] . "', '" . $row['DestIsMaster'] . "', '" . $row['SourceIsMaster'] . "', '" . $row['TargetMargin'] . "', '" . $row['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "', '1');";
				DBUtil::execute($sqldata);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

	public function actionInsertDZPP90Day()
	{
		Logger::info("\n*********************************** InsertDZPP90Day Start *********************************************\n");
		$j					 = 0;
		$chkGolbally		 = true;
		$totRecordsGlobally	 = 1000000;
		$limitGlobally		 = 1000;
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
                            WHERE 1 ORDER BY dynamic_zone_surge_1day.dzs_id DESC LIMIT $j, $limitGlobally";
			$detailsGlobally = DBUtil::query($sqlGlobally, DBUtil::SDB());
			foreach ($detailsGlobally as $row)
			{
				try
				{
					$param = array();
					if ($row['zsg_row_identifier'] == null)
					{
						$sqldata = "INSERT INTO `zone_surge_global` (`zsg_row_identifier`,`zsg_regionname`,`zsg_regionid`, `zsg_fromzoneid`, `zsg_fromzonename`, `zsg_frommasterzone`, `zsg_frommasterzoneid`, `zsg_tozoneid`, `zsg_tozonename`, `zsg_tomasterzone`, `zsg_tomasterzoneid`, `zsg_countbooking`, `zsg_profit`, `zsg_scv_label`, `zsg_scv_id`,`zsg_scv_scc_id`, `zsg_booking_type`, `zsg_destismaster`, `zsg_sourceismaster`, `zsg_targetmargin`, `zsg_difffromgoal`, `zsg_dzpp`,`zsg_dzpp_applied`, `zsg_target_boost`,`zsg_createdate`, `zsg_active`) VALUES 
                                ('" . $row['dzs_row_identifier'] . "','" . $row['dzs_regionname'] . "','" . $row['dzs_regionid'] . "','" . $row['dzs_fromzoneid'] . "', '" . $row['dzs_fromzonename'] . "', '" . $row['dzs_frommasterzone'] . "', '" . $row['dzs_frommasterzoneid'] . "', '" . $row['dzs_tozoneid'] . "', '" . $row['dzs_tozonename'] . "', '" . $row['dzs_tomasterzone'] . "', '" . $row['dzs_tomasterzoneid'] . "', '" . $row['dzs_countbooking'] . "', '" . $row['dzs_profit'] . "', '" . $row['dzs_scv_label'] . "', '" . $row['dzs_scv_id'] . "', '" . $row['dzs_scv_scc_id'] . "','" . $row['dzs_booking_type'] . "', '" . $row['dzs_destismaster'] . "', '" . $row['dzs_sourceismaster'] . "', '" . $row['dzs_targetmargin'] . "', '" . $row['dzs_difffromgoal'] . "', '" . $row['dzs_dzpp'] . "', '" . $row['dzs_dzpp_applied'] . "','" . $row['dzs_target_boost'] . "', '" . $row['dzs_createdate'] . "', '1');";
					}
					else
					{
						$updateBookingCount			 = $row['zsg_countbooking'] + $row['dzs_countbooking'];
						$upDateDifffromGoal			 = ((($row['zsg_difffromgoal'] + $row['zsg_dzpp_applied'] - 1) * $row['zsg_countbooking']) + (($row['dzs_difffromgoal'] + $row['dzs_dzpp_applied'] - 1) * $row['dzs_countbooking'])) / ($row['zsg_countbooking'] + $row['dzs_countbooking']);
						$upDateDzppApplied			 = 1;
						$upDateDzpp					 = round($upDateDifffromGoal > 0 ? ($updateBookingCount > 4 ? (1 + ($upDateDifffromGoal / 100)) : 1 + (($updateBookingCount * $upDateDifffromGoal) / 400)) : 1, 2);
						$param['zsg_dzpp']			 = $upDateDzpp;
						$param['zsg_countbooking']	 = $updateBookingCount;
						$param['zsg_difffromgoal']	 = $upDateDifffromGoal;
						$param['zsg_dzpp_applied']	 = $upDateDzppApplied;
						$sqldata					 = "UPDATE `zone_surge_global` SET `zsg_dzpp` =:zsg_dzpp, `zsg_countbooking` =:zsg_countbooking, `zsg_difffromgoal` =:zsg_difffromgoal, `zsg_dzpp_applied` =:zsg_dzpp_applied WHERE `zone_surge_global`.`zsg_row_identifier` ='" . $row['zsg_row_identifier'] . "'";
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
			Logger::info("\n*********************************** InsertDZPP90Day Count Start *********************************************\n");
			Logger::info($j);
			Logger::info("\n*********************************** InsertDZPP90Day Count Ends *********************************************\n");
			if ($totRecordsGlobally <= $j)
			{
				break;
			}
		}


		$i			 = 0;
		$chk		 = true;
		$totRecords	 = 100000;
		$limit		 = 1000;
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
                            if(dzs_fromzoneid =dzs_frommasterzoneid, 12, 17) AS SourceIsMaster,
                            if(dzs_tozoneid = dzs_tomasterzoneid, 12, 17) AS DestIsMaster,
                            ROUND(SUM(dzs_target_boost) / SUM(dzs_countbooking), 2)  AS target_boost,
                            ROUND(SUM(dzs_dzpp_applied) / SUM(dzs_countbooking), 2)  AS DZPP_Applied  
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
					$sqldata = "INSERT INTO `dynamic_zone_surge` (`dzs_row_identifier`,`dzs_regionname`,`dzs_regionid`, `dzs_fromzoneid`, `dzs_fromzonename`, `dzs_frommasterzone`, `dzs_frommasterzoneid`, `dzs_tozoneid`, `dzs_tozonename`, `dzs_tomasterzone`, `dzs_tomasterzoneid`, `dzs_countbooking`, `dzs_profit`, `dzs_scv_label`, `dzs_scv_id`,`dzs_scv_scc_id`, `dzs_booking_type`, `dzs_destismaster`, `dzs_sourceismaster`, `dzs_targetmargin`, `dzs_difffromgoal`, `dzs_dzpp`,`dzs_dzpp_applied`, `dzs_target_boost`,`dzs_createdate`) VALUES 
                                ('" . $row['RowIdentifier'] . "','" . $row['Region'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['FromZoneName'] . "', '" . $row['FromMasterZone'] . "', '" . $row['FromMasterZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['ToZoneName'] . "', '" . $row['ToMasterZone'] . "', '" . $row['ToMasterZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['Profit'] . "', '" . $row['scv_label'] . "', '" . $row['scv_id'] . "', '" . $row['scv_scc_id'] . "','" . $row['booking_type'] . "', '" . $row['DestIsMaster'] . "', '" . $row['SourceIsMaster'] . "', '" . $row['TargetMargin'] . "', '" . $row['DifffromGoal'] . "', '" . $row['DZPP'] . "', '" . $row['DZPP_Applied'] . "','" . $row['TargetBoost'] . "', '" . $row['CreateDate'] . "');";
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
			if ($totRecords <= $i)
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

	public function actionMarkScqInactive()
	{
		$scqId = Yii::app()->request->getParam('scqId', 0);
		if ($scqId != null)
		{
			echo $count = ServiceCallQueue::markScqInactive($scqId);
		}
	}

	public function actionUpdateZoneVendorMapped()
	{
		Logger::info("\n*********************************** UpdateZoneVendorMapped Start *********************************************\n");
		$zoneModels = ZoneVendorMap::model()->getZoneVendorMapped();

		Logger::info("\n*********************************** UpdateZoneVendorMapped DELETE Start *********************************************\n");
		$dropQry = "DELETE FROM `zone_vendor_map` WHERE 1 ";
		DBUtil::execute($dropQry);
		Logger::info("\n*********************************** UpdateZoneVendorMapped  DELETE ENDS *********************************************\n");

		Logger::info("\n*********************************** UpdateZoneVendorMapped ALTER  Start *********************************************\n");
		$alterQry = "ALTER TABLE zone_vendor_map AUTO_INCREMENT = 1 ";
		DBUtil::execute($alterQry);
		Logger::info("\n*********************************** UpdateZoneVendorMapped ALTER ENDS *********************************************\n");

		$lastVendorId = 0;
		foreach ($zoneModels as $row)
		{
			try
			{
				$models					 = new ZoneVendorMap();
				$models->zvm_zon_id		 = $row['AcceptedZone'];
				$models->zvm_vnd_id		 = $row['vnd_id'];
				$models->zvm_zone_type	 = 2;
				if (!$models->save())
				{
					$error = $models->errors;
				}
				if ($row['vnd_id'] != $lastVendorId && $row['HomeZone'] != "")
				{
					$lastVendorId			 = $row['vnd_id'];
					$models					 = new ZoneVendorMap();
					$models->zvm_zon_id		 = $row['HomeZone'];
					$models->zvm_vnd_id		 = $row['vnd_id'];
					$models->zvm_zone_type	 = 1;
					if (!$models->save())
					{
						$error = $models->errors;
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

	public function actionRefundcheck()
	{
		exit;
		$oldTXNID				 = Yii::app()->request->getParam('paymentId');
		$pgModel				 = PaymentGateway::model()->find('apg_txn_id=:paymentId', ['paymentId' => $oldTXNID]);
		$url					 = Yii::app()->payu->refund_url;
		$data['paymentId']		 = $oldTXNID;
		$data['refundAmount']	 = (-1 * $pgModel->apg_amount);

		$responseArr = Yii::app()->payu->callAuthApi($url, $data);

		echo json_encode($responseArr);
	}

	public function actionRetryfailedpaymentstatus()
	{
		$bkgId = Yii::app()->request->getParam('id');
		PaymentGateway::updateFailedStatus($bkgId);
	}

	public function actionUpdateStatus()
	{
		$bkgId = Yii::app()->request->getParam('id');
		PaymentGateway::updateStatus($bkgId);
	}

	public function actionCanbookingtestuser()
	{
		$email		 = 'romanayek1810@gmail.com'; //'newux@gozo.cab';
		$password	 = md5('1234'); //md5('UIUX@101');
		$userModel	 = Users::model()->find('usr_email=:email AND usr_password=:password', ['email' => $email, 'password' => $password]);
		$userId		 = $userModel->user_id;
		$sql		 = "SELECT
					bkg_id
				FROM
					`booking`
				INNER JOIN booking_user ON bui_bkg_id = bkg_id
				INNER JOIN booking_trail ON btr_bkg_id = bkg_id
				WHERE
					bkg_status IN(2) AND bkg_confirm_datetime < DATE_SUB(NOW(), INTERVAL 1 HOUR) AND bkg_user_id = {$userId} LIMIT 2";
		$results	 = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $key => $value)
		{
			Booking::model()->canBooking($value['bkg_id'], "Test user cancel booking cron", 4);
		}
	}

	public function actionTest1()
	{
		$result = DBUtil::execute("UPDATE qr_code qrc SET qrc_contact_pic= 'gozospot-161900999-contact-bb.jpg' WHERE  1 AND qrc.qrc_id=999");
	}

	public function actionBlockassignmenttestuser()
	{
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
		echo $results;
	}

	public function actionAutoVendorApprovalOnInventoryShortage()
	{

		$result = Zones::getInventoryShortageZone();
		foreach ($result as $row)
		{
			/*			 * ********* On the based of From Zone ID ************* */
			$results = Vendors::getAllVendorApprovalOnInventoryShortage($row['fromZoneId']);
			foreach ($results as $rows)
			{
				try
				{
					$count = ServiceCallQueue::checkDuplicateAutoApprovalForVendor($rows['vnd_id'], ServiceCallQueue::TYPE_VENDOR_APPROVAl);
					if ($count == 0)
					{
						$returnSet = ServiceCallQueue::autoVendorApproval($rows);
						if ($returnSet->isSuccess())
						{
							$desc = "Service Request has been generated for " . $rows['vnd_id'];
							VendorsLog::model()->createLog($rows['vnd_id'], $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SR, false, false);
						}
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex->getMessage());
					Logger::writeToConsole($ex->getMessage());
				}
			}

			/*			 * ***************** On the based of To Zone ID ************* */
			$results = Vendors::getAllVendorApprovalOnInventoryShortage($row['toZoneId']);
			foreach ($results as $rows)
			{
				try
				{
					$count = ServiceCallQueue::checkDuplicateAutoApprovalForVendor($rows['vnd_id'], ServiceCallQueue::TYPE_VENDOR_APPROVAl);
					if ($count == 0)
					{
						$returnSet = ServiceCallQueue::autoVendorApproval($rows);
						if ($returnSet->isSuccess())
						{
							$desc = "Service Request has been generated for " . $rows['vnd_id'];
							VendorsLog::model()->createLog($rows['vnd_id'], $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SR, false, false);
						}
					}
				}
				catch (Exception $ex)
				{
					Logger::exception($ex->getMessage());
					Logger::writeToConsole($ex->getMessage());
				}
			}
		}
	}

	public function actionContactphonecorrection()
	{
		$sqlVerifiedPrimary	 = "UPDATE
									contact_phone
									SET phn_active = 0
								WHERE
									phn_is_primary = 0 AND phn_is_verified = 0 AND phn_active = 1 AND phn_contact_id IN(
									 SELECT
										phn_contact_id
									FROM
										contact_phone
									WHERE
										(
											phn_is_verified = 1 OR phn_is_primary = 1
										) AND phn_active = 1 AND phn_contact_id >0
									GROUP BY
										phn_contact_id
									HAVING
										COUNT(phn_contact_id) > 4
								)";
		$resultexe			 = DBUtil::execute($sqlVerifiedPrimary);
		echo $resultexe;
		$sql				 = "SELECT GROUP_CONCAT(phn_id ORDER BY phn_id ASC) phn,phn_contact_id FROM contact_phone WHERE phn_is_primary = 0 AND phn_is_verified = 0 AND phn_active = 1 AND phn_contact_id >0 GROUP BY phn_contact_id HAVING count(phn_contact_id)>4";
		$result				 = DBUtil::query($sql);
		foreach ($result as $res)
		{
			$original	 = explode(',', $res['phn']);
			$sql1		 = "UPDATE contact_phone SET phn_active = 0 WHERE phn_contact_id =:contactId AND phn_id<>:phnId";
			$resultexe1	 = DBUtil::execute($sql1, ['contactId' => $res['phn_contact_id'], 'phnId' => $original[0]]);
			echo $resultexe1;
		}
	}

	public function actionContactemailcorrection()
	{
		$sqlVerifiedPrimary	 = "UPDATE	contact_email
                                        SET eml_active = 0
                                     WHERE
                                        eml_is_primary = 0 AND eml_is_verified = 0 AND eml_active = 1 AND eml_contact_id IN(
                                            SELECT
                                            eml_contact_id
                                            FROM
                                            contact_email
                                            WHERE
                                            (
                                                eml_is_verified = 1 OR eml_is_primary = 1
                                            ) AND eml_active = 1 AND eml_contact_id >0
									GROUP BY
										eml_contact_id
									HAVING
										COUNT(eml_contact_id) > 4
                                        )";
		$resultexe			 = DBUtil::execute($sqlVerifiedPrimary);
		echo $resultexe;
		$sql				 = "SELECT GROUP_CONCAT(eml_id ORDER BY eml_id ASC) eml,eml_contact_id FROM contact_email WHERE eml_is_primary = 0 AND eml_is_verified = 0 AND eml_active = 1 AND eml_contact_id >0 GROUP BY eml_contact_id HAVING count(eml_contact_id)>4";
		$result				 = DBUtil::query($sql);
		foreach ($result as $res)
		{
			$original	 = explode(',', $res['eml']);
			$sql1		 = "UPDATE contact_email SET eml_active = 0 WHERE eml_contact_id =:contactId AND eml_id<>:emlId";
			$resultexe1	 = DBUtil::execute($sql1, ['contactId' => $res['eml_contact_id'], 'emlId' => $original[0]]);
			echo $resultexe1;
		}
	}

	public function actionMarkZoneType()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$result	 = BookingPref::getBookingZoneType($bkgId);
		foreach ($result as $row)
		{
			try
			{
				$fromCity				 = $row['bkg_from_city_id'];
				$toCity					 = $row['bkg_to_city_id'];
				$scv_id					 = $row['bkg_vehicle_type_id'];
				$tripType				 = $row['bkg_booking_type'];
				$res					 = DynamicZoneSurge::getDZPPZoneTypeTest($fromCity, $toCity, $scv_id, $tripType);
				$model					 = BookingPref::model()->getByBooking($row['bkg_id']);
				print_r($res['dzs_zone_type']);
				$model->bpr_zone_type	 = $res['dzs_zone_type'] != null ? $res['dzs_zone_type'] : 3;
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

	public function actionALLMarkZoneType()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$result	 = BookingPref::allgetBookingZoneType($bkgId);
		foreach ($result as $row)
		{
			try
			{
				$fromCity				 = $row['bkg_from_city_id'];
				$toCity					 = $row['bkg_to_city_id'];
				$scv_id					 = $row['bkg_vehicle_type_id'];
				$tripType				 = $row['bkg_booking_type'];
				$res					 = DynamicZoneSurge::getDZPPZoneTypeTest($fromCity, $toCity, $scv_id, $tripType);
				$model					 = BookingPref::model()->getByBooking($row['bkg_id']);
				print_r($res['dzs_zone_type']);
				$model->bpr_zone_type	 = ($res['dzs_zone_type'] != null) ? $res['dzs_zone_type'] : 3;
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

	public function actionInsertDZPP1DayHist()
	{
		$begin	 = Yii::app()->request->getParam('begin');
		$end	 = Yii::app()->request->getParam('end');
		if ($begin == null && $end == null)
		{
			$begin	 = new DateTime("2018-10-01");
			$end	 = new DateTime("2022-05-25");
		}
		for ($i = $begin; $i <= $end; $i->modify('+1 day'))
		{
			$date		 = $i->format("Y-m-d");
			$actualDate	 = $date . " 12:00:00";
			$fromDate	 = $date . " 00:00:00";
			$toDate		 = $date . " 23:59:59";
			echo("" . $begin . " => " . $end);
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

				Logger::info("\n*********************************** InsertDZPP90Day Sql Start *********************************************\n");
				Logger::info($sql);
				Logger::info("\n*********************************** InsertDZPP90Day Sql Ends *********************************************\n");

				$details = DBUtil::query($sql, DBUtil::SDB());
				foreach ($details as $row)
				{
					try
					{
						$sqldata = "INSERT INTO `dynamic_zone_surge_Hist`(`dzs_row_identifier`,`dzs_regionid`, `dzs_fromzoneid`,`dzs_tozoneid`,`dzs_countbooking`, `dzs_zone_type`, `dzs_scv_id`,`dzs_booking_type`,`dzs_createdate`) VALUES 
                            ('" . $row['RowIdentifier'] . "','" . $row['RegionId'] . "','" . $row['FromZoneId'] . "', '" . $row['ToZoneId'] . "', '" . $row['CountBooking'] . "', '" . $row['ZoneType'] . "', '" . $row['scv_id'] . "','" . $row['booking_type'] . "', '" . $row['CreateDate'] . "');";
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
			$dropQry = "DELETE FROM `dynamic_zone_surge_Hist` WHERE dzs_createdate < '$fromDate'";
			DBUtil::execute($dropQry);
			Logger::info("\n*********************************** InsertDZPP90Day Delete Ends *********************************************\n");
		}
	}

	public function actionAutoTripStartUpperTier()
	{
		$result = Booking::getAllTripStartBooking();
		foreach ($result as $row)
		{
			try
			{
				if ($row['bkg_agent_id'] != null && $row['bkg_agent_id'] > 0)
				{
					ServiceCallQueue::autoFURTripStartedForB2BHour($row['bkg_id']);
				}
				else
				{
					ServiceCallQueue::autoFURTripStartedHour($row['bkg_id']);
				}
			}
			catch (Exception $ex)
			{
				Logger::exception($ex->getMessage());
				Logger::writeToConsole($ex->getMessage());
			}
		}
	}

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

	public function actionUpdatepaymentfailed()
	{
		$transCode	 = Yii::app()->request->getParam('code');
		$pgModel	 = PaymentGateway::model()->getByCode($transCode);
		PaymentGateway::model()->updateFailedPGResponse($pgModel);
	}

	public function actionShowConfig()
	{
		$arr = Config::getArrayList();
		print_r($arr);
		exit();
	}

	public function actionAddLead()
	{
		$check = Filter::checkProcess("system AddLead");
		if (!$check)
		{
			return;
		}
		try
		{
			$eligibleScore		 = 105;
			$configCount		 = (int) Config::get('SCQ.maxLeadAllowed');
			$serviceCallCount	 = ServiceCallQueue::getLeadCount(true, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(true, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore);
			}

			$serviceCallCount = ServiceCallQueue::getLeadCount(false, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore FALSE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(false, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			Logger::info("\n*********************************** zone_surge_global Error Start *********************************************\n");
			Logger::info($ex->getMessage());
			Logger::info("\n*********************************** zone_surge_global Error Ends *********************************************\n");
			Logger::writeToConsole($ex->getMessage());
		}

		try
		{
			$eligibleScore		 = 100;
			$serviceCallCount	 = ServiceCallQueue::getLeadCount(true, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(true, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore);
			}

			$serviceCallCount = ServiceCallQueue::getLeadCount(false, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore FALSE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(false, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore);
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			Logger::info("\n*********************************** zone_surge_global Error Start *********************************************\n");
			Logger::info($ex->getMessage());
			Logger::info("\n*********************************** zone_surge_global Error Ends *********************************************\n");
			Logger::writeToConsole($ex->getMessage());
		}
		try
		{
			$eligibleScore		 = 80;
			$serviceCallCount	 = ServiceCallQueue::getLeadCount(true, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(true, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(true, $eligibleScore);
			}

			$serviceCallCount = ServiceCallQueue::getLeadCount(false, $eligibleScore);
			Logger::info("\n*********************************** ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore FALSE PART *********************************************\n");
			if (ServiceCallQueue::getLeadCount(false, $eligibleScore) < (int) Config::get('SCQ.maxLeadAllowed'))
			{
				Logger::info("\n*********************************** Inside ServiceCallQueueCount=$serviceCallCount ConfigCount=$configCount For $eligibleScore  TRUE PART *********************************************\n");
				ServiceCallQueue::updatePendingLeadsCron(false, $eligibleScore);
			}
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

	public function actionUpdateDynamicZoneSurge()
	{
		$begin	 = Yii::app()->request->getParam('begin');
		$end	 = Yii::app()->request->getParam('end');
		if ($begin == null && $end == null)
		{
			$begin	 = new DateTime("2022-06-07 00:00:00");
			$end	 = new DateTime("2021-12-03 00:00:00");
		}
		else
		{
			$begin	 = new DateTime($begin);
			$end	 = new DateTime($end);
		}
		for ($j = $begin; $j >= $end; $j->modify('-1 day'))
		{
			$date		 = $j->format("Y-m-d");
			$fromDate	 = $date . " 00:00:00";
			$toDate		 = $date . " 23:59:59";
			$i			 = 0;
			$chk		 = true;
			$limit		 = 500;
			while ($chk)
			{
				$sql		 = "SELECT dzs_id,`dzs_row_identifier` FROM dynamic_zone_surge_1day WHERE 1 AND  dzs_active=1 AND dzs_createdate=:date ORDER BY dzs_id ASC LIMIT $i, $limit";
				$resultDzpp	 = DBUtil::query($sql, DBUtil::SDB(), ['date' => $date]);
				foreach ($resultDzpp as $row)
				{
					try
					{
						$param				 = array();
						$result				 = Booking::getBookingCountByRowIdentifierDump($row['dzs_row_identifier'], $fromDate, $toDate);
						$resultLead			 = BookingTemp::getBookingCountByRowIdentifierDump($row['dzs_row_identifier'], $fromDate, $toDate);
						$param['cntLead']	 = $resultLead > 0 ? $resultLead : 0;
						$param['cntInquiry'] = $result['cntInquiry'] > 0 ? $result['cntInquiry'] : 0;
						$param['cntCreated'] = $result['cntCreated'] > 0 ? $result['cntCreated'] : 0;
						$param['dzs_id']	 = $row['dzs_id'];
						$sql				 = "UPDATE `dynamic_zone_surge_1day` SET `dzs_cntLead` =:cntLead, `dzs_cntInquiry` =:cntInquiry, `dzs_cntCreated` = :cntCreated WHERE `dynamic_zone_surge_1day`.`dzs_id` =:dzs_id";
						DBUtil::execute($sql, DBUtil::MDB(), $param);
					}
					catch (Exception $ex)
					{
						echo($ex->getMessage());
					}
				}
				$i += $limit;
				if ($resultDzpp->rowCount == 0)
				{
					break;
				}
			}
		}
	}

	public function actionUpdateEventSurge()
	{
		CalendarEvent::updateDayType();
		CalendarEvent::updateLongWeekends();
		CalendarEvent::updateNextLongWeekends();
		CalendarEvent::updateNextLgWeekends();
		CalendarEvent::updatePhantomWeekends();
		CalendarEvent::updateEventFactor();
		CalendarEvent::updateWeightedFactor();
	}

	public function actionFetchBookingStats()
	{
		$result = BookingStats::getAllBookingStats();
		foreach ($result as $row)
		{
			try
			{
				$models				 = BookingStats::model()->getByBooking($row['bks_bkg_id']);
				$denyCount			 = BookingCab::getTotalL1L2BookingCount($row['bks_bkg_id']);
				$traveltime			 = BookingTrack::getBookingTravelTime($row['bks_bkg_id']);
//				$demFireDetails	 = BookingLog::model()->getByBookingIdEventId($row['bks_bkg_id'], [BookingLog::DEMAND_SUPPLY_MISFIRE]);
				$demsupFireLogDate	 = BookingLog::getEventLogDate($row['bks_bkg_id'], [BookingLog::DEMAND_SUPPLY_MISFIRE]);
				if (!$models)
				{
					$models					 = new BookingStats();
					$models->bks_added_date	 = DBUtil::getCurrentTime();
				}
				$models->bks_travel_time			 = $traveltime ? $traveltime : NULL;
				$models->bks_l1_deny_count			 = $denyCount[0] > 0 ? $denyCount[0] : 0;
				$models->bks_l2_deny_count			 = $denyCount[1] > 0 ? $denyCount[1] : 0;
				$models->bks_row_identifier			 = $row['bks_row_identifier'];
				$models->bks_zone_identifier		 = $row['bks_zone_identifier'];
				$models->bks_city_identifier		 = $row['bks_city_identifier'];
				$models->bks_zone_type				 = $row['bks_zone_type'];
				$models->bks_source_region			 = $row['bks_source_region'];
				$models->bks_vehicle_type_id		 = $row['bks_vehicle_type_id'];
				$models->bks_demsup_misfire_date	 = ($demsupFireLogDate) ? $demsupFireLogDate : NULL;
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

					throw Exception(json_encode($models->errors), ReturnSet::ERROR_VALIDATION);
				}
			}
			catch (Exception $ex)
			{
				$returnSet = ReturnSet::setException($ex);
			}
		}
	}

	public function actionCheckbounds()
	{
		$largeBound		 = '{"northeast":{"lat":12.8412778999999996898395693278871476650238037109375,"lng":77.882244299999996428596205078065395355224609375},"southwest":{"lat":12.81951910000000083300619735382497310638427734375,"lng":77.8502294999999975289028952829539775848388671875}}';
		$objLargeBounds	 = json_decode($largeBound);
		$neLat			 = $objLargeBounds->northeast->lat;
		$neLong			 = $objLargeBounds->northeast->lng;
		$swLat			 = $objLargeBounds->southwest->lat;
		$swLong			 = $objLargeBounds->southwest->lng;

		//echo Filter::checkLatLongBound(12.83039856, 77.86623383, $neLat, $neLong, $swLat, $swLong);
		//	echo Filter::checkLatLongBound(12.740913, 77.825294, $neLat, $neLong, $swLat, $swLong);
		echo $this->inBounds(12.740913, 77.825294, $neLat, $neLong, $swLat, $swLong);
		exit;
	}

	function inBounds($pointLat, $pointLong, $boundsNElat, $boundsNElong, $boundsSWlat, $boundsSWlong)
	{
		$eastBound	 = $pointLong < $boundsNElong;
		$westBound	 = $pointLong > $boundsSWlong;

		if ($boundsNElong < $boundsSWlong)
		{
			$inLong = $eastBound || $westBound;
		}
		else
		{
			$inLong = $eastBound && $westBound;
		}

		$inLat = $pointLat > $boundsSWlat && $pointLat < $boundsNElat;
		return $inLat && $inLong;
	}

	public function actiongetRowIdentifier()
	{
		$bkgId		 = Yii::app()->request->getParam('id');
		$model		 = Booking::model()->findByPk($bkgId);
		$fromCity	 = $model->bkg_from_city_id;
		$toCity		 = $model->bkg_to_city_id;
		$scv_id		 = $model->bkg_vehicle_type_id;
		$tripType	 = $model->bkg_booking_type;
		echo $res		 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
	}

	public function actionGozoCoinLedgerChange()
	{
		$sql	 = "SELECT * FROM test.gozoCoinLedger1 WHERE gcl_status = 0 limit 0,2";
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
				echo "<br>Done adtid: " . $adtId;
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionTransferBalance()
	{
		$sql	 = "SELECT adt_trans_ref_id,closing, newOpening,status FROM test.partnerwalletclosing WHERE status=0";
		$rows	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($rows as $data)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$partnerId		 = $data['adt_trans_ref_id'];
				$closingBalance	 = $data['closing'];
				$openingBalance	 = $data['newOpening'];
				$date			 = '2022-03-31 23:59:59';
				$date1			 = '2022-04-01 00:00:00';
				$succes1		 = true;
				$success		 = AccountTransactions::model()->partnerWalletToPartnerLedger($partnerId, $closingBalance, $date, "Closing balance transferred to partner account");
				if ($openingBalance <> 0)
				{
					$succes1 = AccountTransactions::model()->partnerWalletToPartnerLedger($partnerId, $openingBalance, $date1, "Opening balance transferred from partner account");
				}

				if (!$success || !$succes1)
				{
					throw new Exception("<br>Failed adt_trans_ref_id===" . $partnerId . "===walletBalance===" . $amount);
				}
				$query = "UPDATE test.partnerwalletclosing SET status=1 WHERE  adt_trans_ref_id = $partnerId AND status =0";
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
				echo "<br>Done adt_trans_ref_id===" . $partnerId . "===closingBalance===" . $closingBalance . "opening" . $openingBalance;
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionActivateCustomerQr()
	{
		$sql	 = "Select * from test.cx_qr WHERE status =0 LIMIT 0,10";
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
					$qry	 = "UPDATE  test.cx_qr SET  status = 2 WHERE QRNumber = '$number'";
					DBUtil::execute($qry);
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

	public function actionBidRange()
	{
		$quoteAmt		 = 1698;
		$vendorBidRange	 = Config::get('vendorBidRange');
		$mainArray		 = array();
		if (!empty($vendorBidRange))
		{
			$result	 = CJSON::decode($vendorBidRange);
			$i		 = 0;
			foreach ($result as $value)
			{
				if ($i == 0 || $i == count($result) - 1)
				{
					$mainArray[] = array('text' => $value['text'], 'val' => $value['val'], 'color' => $value['color']);
				}
				else
				{
					$mainArray[] = array('text' => $value['text'], 'val' => $value['val'] < 1 ? ($quoteAmt * $value['val']) . " <== " : " ==> " . ($quoteAmt * $value['val']), 'color' => $value['color']);
				}
				$i++;
			}
		}
		echo "<pre>";
		print_r($mainArray);
	}

	public function actionDist()
	{
//		echo Filter::calculateDistance('30.194925', '78.192047', '30.413746', '78.345818');
//        exit;	
		$distance	 = ROUND(SQRT(POW(69.1 * (19.089560 - 19.0957428), 2) + POW(69.1 * (72.8538296 - 72.865616 ) * COS(19.089560 / 57.3), 2)), 2);
		$dis		 = $distance * 1.60934;
		echo $dis;
		exit;
	}

	public function actiontopDemandZone()
	{
		$zozoneName	 = Config::get('zo_zoneName');
		$result		 = json_decode($zozoneName, true);

		$dropQry = "DELETE FROM `top_demand_zone` WHERE 1 ";
		DBUtil::execute($dropQry);

		$alterQry = "ALTER TABLE top_demand_zone AUTO_INCREMENT = 1 ";
		DBUtil::execute($alterQry);

		foreach ($result['Zones'] as $value)
		{

			$fromZoneId		 = $value['FromZoneId'];
			$fromZoneName	 = $value['FromZoneName'];
			$toZoneId		 = $value['TozoneId'];
			$toZoneName		 = $value['ToZoneName'];
			$targetMargin	 = $value['TargetMargin'];
			$regionId		 = Zones::getRegionByZoneId($value['FromZoneId']);
			$regionname		 = States::model()->findRegionName($regionId);
			$tripType		 = 1;
			$serviceClass	 = SvcClassVhcCat::getCategoryServiceClass();
			foreach ($serviceClass as $val)
			{
				$svcId			 = $val['scv_id'];
				$svcLabel		 = $val['label'];
				$param			 = array('regionId' => $regionId, 'fromZone' => $fromZoneId, 'toZone' => $toZoneId, 'scv_id' => $svcId, 'tripType' => $tripType);
				$sql			 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromZone,5,'0'),'',LPAD(:toZone,5,'0'),'',LPAD(:scv_id,3,'0'),'',LPAD(:tripType,2,'0')) AS rowIdentifier FROM DUAL";
				$rowIdentifier	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
				$sqlInsert		 = "INSERT INTO `top_demand_zone` (`tdz_row_identifier`, `tdz_regionname`, `tdz_regionid`, `tdz_fromzoneid`, `tdz_fromzonename`, `tdz_tozoneid`, `tdz_tozonename`, `tdz_scv_label`, `tdz_scv_id`,`tdz_booking_type`,`tdz_target_margin`) 
									VALUES ('" . $rowIdentifier . "', '" . $regionname . "', '" . $regionId . "', '" . $fromZoneId . "', '" . $fromZoneName . "', '" . $toZoneId . "', '" . $toZoneName . "', '" . $svcLabel . "', '" . $svcId . "', '" . $tripType . "','" . $targetMargin . "')";

				DBUtil::execute($sqlInsert);
			}
		}
	}

	public function actionFile()
	{
		$imgPath	 = "/doc/1/bookings/2022/09/23/2861801/OW202861801.gpx";
		$filePath	 = APPLICATION_PATH . $imgPath;
		if (file_exists($filePath))
		{
			Yii::app()->request->downloadFile($filePath);
		}
	}

	public function actiongetDDBPV2()
	{
		$arr = array();
		try
		{
			$bkgId					 = Yii::app()->request->getParam('id');
			$model					 = Booking::model()->findByPk($bkgId);
			$fromCity				 = $model->bkg_from_city_id;
			$toCity					 = $model->bkg_to_city_id;
			$scv_id					 = $model->bkg_vehicle_type_id;
			$tripType				 = $model->bkg_booking_type;
			$pickupDate				 = $model->bkg_pickup_date;
			$distance				 = $model->bkg_trip_distance;
			$lastModifiedRateDate	 = Rate::getlastUpdated($fromCity, $toCity, $scv_id, $tripType);
			$days					 = (Filter::getTimeDiff(date("Y-m-d H:i:s"), $lastModifiedRateDate) / 1440);

			$arr['fromCity']			 = $fromCity;
			$arr['toCity']				 = $toCity;
			$arr['scv_id']				 = $scv_id;
			$arr['tripType']			 = $tripType;
			$arr['pickupDate']			 = $pickupDate;
			$arr['distance']			 = $distance;
			$arr['lastModifiedRateDate'] = $lastModifiedRateDate;
			$arr['days']				 = $days;

			if ($days > 7)
			{
				$rowIdentifier		 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
				$demandIdentifier	 = DynamicZoneSurge::getDemandIdentifier($fromCity, $tripType);
				$cityIdentifier		 = DynamicZoneSurge::getCityIdentifier($fromCity, $toCity);
				$vndGoingRate		 = DELIVEREDVATREND::getAvgVendorGoingRate($rowIdentifier) * $distance;
				$vndQuotedGoingRate	 = DELIVEREDVATREND::getAvgQuotedVendorGoingRate($rowIdentifier) * $distance;
				$vndAskingPerKm		 = BidSense::getAvgVendorAskingRate($rowIdentifier);
				$vndCostPerDistance	 = 0;
				$upperGuardRail		 = -1;
				$vndAskingRate		 = $vndAskingPerKm * $distance;

				$arr['rowIdentifier']		 = $rowIdentifier;
				$arr['demandIdentifier']	 = $demandIdentifier;
				$arr['cityIdentifier']		 = $cityIdentifier;
				$arr['vndGoingRate']		 = $vndGoingRate;
				$arr['vndQuotedGoingRate']	 = $vndQuotedGoingRate;
				$arr['vndAskingPerKm']		 = $vndAskingPerKm;
				$arr['vndAskingRate']		 = $vndAskingRate;

				if ($tripType == 1 && $cityIdentifier != null)
				{
					$vndCostPerDistance	 = TravelStatsOw::getVendorCostPerDistanceOW($cityIdentifier);
					$upperGuardRail		 = 0.70 * $vndAskingPerKm + ($vndCostPerDistance != null && $vndCostPerDistance > 0) ? (0.30 * $vndCostPerDistance) : 0;
					$vndAskingRate		 = ($upperGuardRail < $vndAskingPerKm ) && $vndAskingPerKm > 0 ? ($upperGuardRail * $distance) : ($vndAskingPerKm * $distance);

					$arr['vndCostPerDistance']			 = $vndCostPerDistance;
					$arr['upperGuardRail']				 = $upperGuardRail;
					$arr['vndAskingRate_travel_stats']	 = $vndAskingRate;
				}
				else if (in_array($tripType, array("4", "12")) && $cityIdentifier != null)
				{
					$vndCostPerDistance	 = TravelStatsAp::getVendorCostPerDistanceAP($cityIdentifier);
					$upperGuardRail		 = 0.70 * $vndAskingPerKm + ( 0.30 * ($vndCostPerDistance != null && $vndCostPerDistance > 0) ? $vndCostPerDistance : 0);
					$vndAskingRate		 = ($upperGuardRail < $vndAskingPerKm ) && $vndAskingPerKm > 0 ? ($upperGuardRail * $distance) : ($vndAskingPerKm * $distance);

					$arr['vndCostPerDistance']			 = $vndCostPerDistance;
					$arr['upperGuardRail']				 = $upperGuardRail;
					$arr['vndAskingRate_travel_stats']	 = $vndAskingRate;
				}
				else if (in_array($tripType, array("9", "10", '11')) && $cityIdentifier != null)
				{
					$vndCostPerDistance	 = TravelStatsDr::getVendorCostPerDistanceDR($cityIdentifier);
					$upperGuardRail		 = 0.70 * $vndAskingPerKm + 0.30 * ($vndCostPerDistance != null && $vndCostPerDistance > 0) ? $vndCostPerDistance : 0;
					$vndAskingRate		 = ($upperGuardRail < $vndAskingPerKm ) && $vndAskingPerKm > 0 ? ($upperGuardRail * $distance) : ($vndAskingPerKm * $distance);

					$arr['vndCostPerDistance']			 = $vndCostPerDistance;
					$arr['upperGuardRail']				 = $upperGuardRail;
					$arr['vndAskingRate_travel_stats']	 = $vndAskingRate;
				}
				$deliveryCount		 = QuotesZoneSituation::getDevliveryCount($demandIdentifier, $pickupDate);
				$regularBaseAmt		 = $vndQuotedGoingRate;
				$goingRegularRatio	 = ($regularBaseAmt == 0 || $regularBaseAmt == null) ? 0 : round(($vndGoingRate / $regularBaseAmt), 2);
				$askingGoingRatio	 = ($vndGoingRate == 0 || $vndGoingRate == null) ? 0 : round(($vndAskingRate / $vndGoingRate), 2);
				if ($goingRegularRatio == 0)
				{
					$goingRegularRatio = 1;
				}
				else if ($goingRegularRatio < 1)
				{
					$goingRegularRatio = round($goingRegularRatio + ( (1 - $goingRegularRatio ) / 2 ), 2);
				}
				if ($askingGoingRatio == 0)
				{
					$askingGoingRatio = 1;
				}
				else if ($askingGoingRatio < 1)
				{
					$askingGoingRatio = round($askingGoingRatio + ( ( 1 - $askingGoingRatio ) / 2 ), 2);
				}
				$minTargetSurge = min($goingRegularRatio, 1.35) * min($askingGoingRatio, 1.35);
				if ($minTargetSurge > 1.2)
				{
					$minTargetSurge = ( (0.6 * $goingRegularRatio ) + ($askingGoingRatio * 0.40));
				}
				$arr['deliveryCount']		 = json_encode($deliveryCount);
				$arr['regularBaseAmt']		 = $regularBaseAmt;
				$arr['askingGoingRatio']	 = $askingGoingRatio;
				$arr['goingRegularRatio']	 = $goingRegularRatio;
				$arr['minTargetSurge']		 = $minTargetSurge;
				$extraSurge					 = 1;
				$DDBPV2Factor				 = Config::get('DDBPV2Factor');
				if (!empty($DDBPV2Factor))
				{
					$result					 = CJSON::decode($DDBPV2Factor);
					$step_size_count_based	 = $result['step_size_count_based'];
					$step_size_5_pre_based	 = $result['step_size_5_pre_based'];
					$step_size_25_pre_based	 = $result['step_size_25_pre_based'];
					$step_size_50_pre_based	 = $result['step_size_50_pre_based'];
					$step_size_100_pre_based = $result['step_size_100_pre_based'];
					$min_capacity			 = $result['min_capacity'];
					$step_count				 = $result['step_count'];
					if ($deliveryCount['capacity'] == 0)
					{
						$zones						 = ZoneCities::getZonesByCity($fromCity);
						$countZone					 = Vendors::getHomeZonesCount($zones);
						$deliveryCount['capacity']	 = $countZone > 0 ? min(max($countZone, 10), $min_capacity) : $min_capacity;
					}

					$normalized_count_increase	 = max(0, ($deliveryCount['pickupCount'] - max($deliveryCount['capacity'], $min_capacity)));
					$normalized_increase_per	 = (($normalized_count_increase ) / max($deliveryCount['capacity'], $min_capacity) ) * 100;
					$surge_basis_count			 = ($deliveryCount['pickupCount'] > $deliveryCount['capacity'] ? ($normalized_count_increase / $step_count) : 0) * $step_size_count_based;
					$surge_basis_5_Per_steps	 = $normalized_increase_per >= 5 && $normalized_increase_per < 25 ? ($normalized_increase_per ) * $step_size_5_pre_based : 0;
					$surge_basis_25_Per_steps	 = $normalized_increase_per >= 25 && $normalized_increase_per < 50 ? ($normalized_increase_per ) * $step_size_25_pre_based : 0;
					$surge_basis_50_Per_steps	 = $normalized_increase_per >= 50 && $normalized_increase_per < 100 ? ($normalized_increase_per ) * $step_size_50_pre_based : 0;
					$surge_basis_100_Per_steps	 = $normalized_increase_per >= 100 ? ($normalized_increase_per ) * $step_size_100_pre_based : 0;
					$extraSurge					 = $normalized_count_increase > 0 ? max($surge_basis_5_Per_steps, $surge_basis_25_Per_steps, $surge_basis_50_Per_steps, $surge_basis_100_Per_steps, $surge_basis_count) : 0;

					$arr['zones']		 = $zones;
					$arr['countZone']	 = $countZone;
					$arr['min_capacity'] = $min_capacity;

					$arr['step_size_count_based']		 = $step_size_count_based;
					$arr['step_size_5_pre_based']		 = $step_size_5_pre_based;
					$arr['step_size_25_pre_based']		 = $step_size_25_pre_based;
					$arr['step_size_50_pre_based']		 = $step_size_50_pre_based;
					$arr['step_size_100_pre_based']		 = $step_size_100_pre_based;
					$arr['min_capacity']				 = $min_capacity;
					$arr['step_count']					 = $step_count;
					$arr['normalized_count_increase']	 = $normalized_count_increase;
					$arr['normalized_increase_per']		 = $normalized_increase_per;
					$arr['surge_basis_count']			 = $surge_basis_count;
					$arr['surge_basis_5_Per_steps']		 = $surge_basis_5_Per_steps;
					$arr['surge_basis_25_Per_steps']	 = $surge_basis_25_Per_steps;
					$arr['surge_basis_50_Per_steps']	 = $surge_basis_50_Per_steps;
					$arr['surge_basis_100_Per_steps']	 = $surge_basis_100_Per_steps;
					$arr['extraSurge']					 = $extraSurge;
				}
				$totalSurge			 = $extraSurge > 0 ? ( 1 + ($extraSurge / 100 ) ) * $minTargetSurge : 1 * $minTargetSurge;
				$arr['totalSurge']	 = $totalSurge;
				echo "<pre>";
				print_r($arr);
				echo $totalSurge;
			}
			else
			{
				echo "Rate was update within last 7 days";
			}
		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
		}
	}

	public function actionUpdateZoneCapacity()
	{
		Logger::warning('TestController actionUpdateZoneCapacity', true);
		return false;
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
					$demandIdentifier	 = DBUtil::queryScalar($sql, DBUtil:: SDB(), $param);
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

	public function actionManualbvr()
	{
		$sql = "SELECT * FROM test.tmp_manual_booking WHERE processed = 0 LIMIT 0, 100";
		$res = DBUtil::query($sql);
		foreach ($res as $row)
		{
			$obj					 = new BookingVendorRequest();
			$obj->bvr_booking_id	 = $row['bkg_id'];
			$obj->bvr_bcb_id		 = $row['bcb_id'];
			$obj->bvr_vendor_id		 = $row['bcb_vendor_id'];
			$obj->bvr_vendor_rating	 = $row['vendor_rating'];
			$obj->bvr_vendor_score	 = $row['vendor_score'];
			$obj->bvr_bid_amount	 = $row['bcb_vendor_amount'];
			$obj->bvr_created_at	 = $row['created_at'];
			$obj->bvr_accepted		 = 0;
			$obj->bvr_assigned		 = 1;
			$obj->bvr_assigned_at	 = $row['created_at'];
			$obj->bvr_active		 = 1;
			$obj->save();

			echo "<br>ID == " . $row['id'];

			$sqlUpd = "UPDATE test.tmp_manual_booking SET processed = 1 WHERE processed = 0 AND id = " . $row['id'];
			DBUtil::execute($sqlUpd);
		}
	}

	public function actionIncVA()
	{
		$tripId = Yii::app()->request->getParam('tripId', 2433525);

		$bcbModel = new BookingCab();
		$bcbModel->updateCriticalTripAmountNew($tripId);
	}

	public function actionUnfreezeDrv()
	{
		$sql	 = "SELECT drv_id, drv_overall_rating FROM `drivers` WHERE drv_is_freeze = 1 
			AND `drv_id` IN (162345,165192,160796,160790,151456,105748,68221)";
		$result	 = DBUtil::query($sql);
		foreach ($result as $row)
		{
			$drvId	 = $row['drv_id'];
			$rating	 = $row['drv_overall_rating'];

			$event_id = DriversLog::DRIVER_UNFREEZE;

			$model					 = Drivers::model()->resetScope()->findByPk($drvId);
			$model->drv_is_freeze	 = 0;
			if ($model->save())
			{
				$desc = "Driver unfreezed for ratings (" . $rating . ")";
				DriversLog::model()->createLog($drvId, $desc, UserInfo::getInstance(), $event_id, false, false);
				echo $drvId . " is unfreezed.\n";
			}
		}
	}

	public function actionUnfreezeVhc()
	{
		$sql	 = "SELECT vhc_id, vhc_overall_rating FROM vehicles 
			WHERE vhc_is_freeze = 1 AND `vhc_id` IN (140942,86169,116177,95981,90962,55024,131797,142904)";
		$result	 = DBUtil::query($sql);
		foreach ($result as $row)
		{
			$vhcId = $row['vhc_id'];

			$event_id = VehiclesLog::VEHICLE_UNFREEZE;

			$model					 = Vehicles::model()->resetScope()->findByPk($vhcId);
			$model->vhc_is_freeze	 = 0;
			if ($model->save())
			{
				$desc = "Car unfreezed for ratings ( " . $model->vhc_overall_rating . "  )";
				VehiclesLog::model()->createLog($model->vhc_id, $desc, Userinfo::getInstance(), $event_id, false, false);
				echo $model->vhc_id . " - [ " . $model->vhc_number . " ] is unfreezed.<br>";
			}
		}
	}

	public function actionCheckIntSMS()
	{
		$ext	 = "91";
		$number	 = "9831100164";
		$data	 = "1234 is your Gozo OTP for login. Do not share it with anyone.";
		$result	 = SMSOnex::sendIntMessage($ext, $number, $data);

		var_dump($result);
		die();
	}

	public function actionTestDDBSP()
	{
		DynamicDemandSupplySurge::model()->processMarkup(1000000);
	}

	public function actionTestCoin()
	{
		$bkgId = '2923787';
		VendorCoins::processCoinForBooking($bkgId);
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

	public function actionTestSms()
	{
		$sms = new Messages();
		$res = $sms->sendMessage('91', '9831100164', 'Your code is 8142. Use this code to reset password of your aaocab Agent Account.', 0);
		echo $res;
	}

	public function actionProcessScheduledEvents()
	{
		BookingScheduleEvent::processScheduledEvents();
	}

	public function actionProcessClosingBalance()
	{
		$date = '2021-04-01 00:00:00';
		AccountTransactions::closeLedgerBalance($date, 34);
	}

	public function actionRefundFromWalletToSource()
	{
		$bkg	 = 1896079;
		$model	 = Booking::model()->findByPk($bkg);
		Booking::RefundFromWalletToSource($model);
	}

	public function actionMMTDataCreated($days = 4)
	{
		for ($i = 1; $i <= $days; $i++)
		{
			$date = date("Y-m-d", strtotime("-$i day", time()));

			$sql = "INSERT INTO mmt_data_created (mdc_date, mdc_from_city_id, mdc_to_city_id, mdc_booking_type, 
					mdc_search_count, mdc_hold_count, mdc_confirm_count) 
					SELECT a.* FROM (
						SELECT DATE_FORMAT(aat_created_at, '%Y-%m-%d') mdc_date, aat_from_city mdc_from_city_id, aat_to_city mdc_to_city_id, 
						aat_booking_type mdc_booking_type, SUM(IF(aat_type = 2, 1, 0)) as mdc_search_count, 
						SUM(IF(aat_type = 8, 1, 0)) as mdc_hold_count, SUM(IF(aat_type = 3, 1, 0)) as mdc_confirm_count 
						FROM agent_api_tracking  
						WHERE aat_type IN (2,3,8) AND aat_created_at BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59' 
						AND aat_from_city > 0 AND aat_to_city > 0 AND aat_error_type IS NULL AND aat_booking_type IN (1,2,3) 
						GROUP BY mdc_date, mdc_from_city_id, mdc_to_city_id, mdc_booking_type) as a 
					ON DUPLICATE KEY UPDATE mdc_search_count=a.mdc_search_count, mdc_hold_count=a.mdc_hold_count, mdc_confirm_count=a.mdc_confirm_count ";
			DBUtil::execute($sql);
		}
	}

	public function actionMMTDataPickup($minDay = -4, $maxDays = 0)
	{
		$minDays = 1;
		$maxDays = 1;
		for ($i = $minDays; $i <= $maxDays; $i++)
		{
			echo "<br><br>" . $date = date("Y-m-d", strtotime("$i day", time()));

			$sql = "INSERT INTO mmt_data_pickup (mdp_date, mdp_from_city_id, mdp_to_city_id, mdp_booking_type, 
					mdp_search_count, mdp_hold_count, mdp_confirm_count) 
					SELECT a.* FROM (
						SELECT DATE_FORMAT(aat_pickup_date, '%Y-%m-%d') mdp_date, aat_from_city mdp_from_city_id, aat_to_city mdp_to_city_id, 
						aat_booking_type mdp_booking_type, SUM(IF(aat_type = 2, 1, 0)) as mdp_search_count, 
						SUM(IF(aat_type = 8, 1, 0)) as mdp_hold_count, SUM(IF(aat_type = 3, 1, 0)) as mdp_confirm_count 
						FROM agent_api_tracking  
						WHERE aat_type IN (2,3,8) AND aat_pickup_date BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59' 
						AND aat_from_city > 0 AND aat_to_city > 0 AND aat_error_type IS NULL AND aat_booking_type IN (1,2,3) 
						GROUP BY mdp_date, mdp_from_city_id, mdp_to_city_id, mdp_booking_type) as a 
					ON DUPLICATE KEY UPDATE mdp_search_count=a.mdp_search_count, mdp_hold_count=a.mdp_hold_count, mdp_confirm_count=a.mdp_confirm_count ";
			#DBUtil::execute($sql);
		}
	}

	public function actionProcessPartnerPendingAdvance()
	{
		BookingScheduleEvent::pendingAdvanceProcess();
	}

	public function actionGetData()
	{
		$data	 = Config::get('mask.customer.driver.number');
		$data1	 = CJSON::decode($data, true);
		print_r($data1);
	}

	public function actionGetMasking()
	{
		$testData1	 = \Config::get('mask.customer.driver.number');
		$testData12	 = CJSON::decode($testData1, true);
		//print_r($testData12);
		echo $testData12['customerToDriver'];
	}

	public function actionRedeemVendorCoinRecover()
	{
		$vendorId	 = 77039;
		$params		 = ["vendorId" => $vendorId];
		$sql1		 = "SELECT vnc_id FROM `vendor_coins` WHERE `vnc_vnd_id` = :vendorId 
				AND `vnc_value` < 0 AND vnc_type =4 AND vnc_active =1";
		$result1	 = DBUtil::query($sql1, DBUtil::SDB(), $params);
		foreach ($result1 as $res1)
		{
			$vncId = $res1['vnc_id'];

			$sqlUpdate1	 = 'UPDATE `vendor_coins` SET  `vnc_active` = 0 WHERE vnc_id=' . $vncId . '';
			$result1	 = DBUtil::execute($sqlUpdate1);
		}

		$sql = "SELECT adt_trans_id FROM account_trans_details
				WHERE JSON_EXTRACT(adt_addt_params, '$.vncId') >0 AND adt_type =1 AND  adt_trans_ref_id 
				IN (SELECT vnc_ref_id FROM `vendor_coins` WHERE `vnc_vnd_id` = :vendorId 
				AND `vnc_value` < 0 AND vnc_type =4 )";

		$result = DBUtil::query($sql, DBUtil::SDB(), $params);

		foreach ($result as $res)
		{
			$transactionId	 = $res['adt_trans_id'];
			$params			 = ['transactionId' => $transactionId];
			$sqlUpdate		 = 'UPDATE `account_transactions` SET  `act_active` = 0 WHERE act_id=:transactionId';
			$result			 = DBUtil::execute($sqlUpdate, $params);
		}
	}

	public function actionCalculateSDDemo()
	{
		$limit	 = 1;
		$vndId	 = 84363;
		$data	 = Vendors::getCollectionList(30, $limit, $vndId);
		foreach ($data as $d)
		{

			echo "<br>vnd_id = " . $vndID = $d['vnd_id'];

			print_r($d);

			echo "<br>Amount = " . $sdAmt = Vendors::getSD($vndID);

			$transaction = DBUtil::beginTransaction();
			try
			{
				echo "<br>vnd_id = " . $vndID		 = $d['vnd_id'];
				$totTrans	 = $d['totTrans'];
				if ($totTrans < 0)
				{
					echo "<br>Amount = " . $sdAmt = Vendors::getSD($vndID);

					if ($sdAmt > 0)
					{
						$model										 = Vendors::model()->resetScope()->findByPk($vndID);
						$modelVendStats								 = $model->vendorStats;
						$modelVendStats->vrs_security_amount		 = $modelVendStats->vrs_security_amount + $sdAmt;
						$modelVendStats->vrs_security_receive_date	 = new CDbExpression('NOW()');
						$modelVendStats->setAttribute('vrs_vnd_id', $model->vnd_id);
						if ($modelVendStats->save())
						{
							$desc = 'Security deposit Rs.' . $sdAmt . " transferred to vendor account";
							VendorsLog::model()->createLog($model->vnd_id, $desc, UserInfo::getInstance(), VendorsLog::VENDOR_SECURITY_DEPOSIT, false, false);
						}
					}
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::exception($e);
			}
		}
	}

	public function actionTestWebOtp()
	{

//$this->render('testWebOtp', array());
		$ph						 = $_REQUEST['ph'];
		$fourDigitRandomNumber	 = rand(1231, 7879);
		$isSend					 = smsWrapper::sendOtpWEBOTP(91, $ph, $fourDigitRandomNumber, SmsLog::SMS_LOGIN_REGISTER);
		if ($isSend)
		{
			$this->render('testWebOtp', array());
		}
	}

	public function actionNotifyVendorAssignCabDriver()
	{

		$bookingId	 = 1900260;
		$success	 = BookingCab::cabDrvAssignNotify($bookingId);
		var_dump($success);
	}

	public function actionNotificationList()
	{
		$result = BookingCab::notifyVendorForAssignCabDriver();
		foreach ($result as $row)
		{
			$bookingId = $row['bkg_id'] . '<br>';
		}
	}

	public function actionAdjustAct()
	{
		$sql		 = "SELECT * FROM account_transactions WHERE act_type = 14 LIMIT 10";
		$recordsets	 = DBUtil::queryAll($sql);
		foreach ($recordsets as $res)
		{
			$remarks = $res['act_remarks'];
			$id		 = $res['act_id'];
			echo "act_id : " . $id . "<br />";

			$var		 = explode(" : ", $remarks);
			$bookingID	 = $var[1];
			echo "booking ID : " . $bookingID . "<br />";

			$bkdModel	 = Booking::model()->findByPk($bookingID);
			$vnd		 = $bkdModel->bkgBcb->bcb_vendor_id;

			echo "Vendor ID : " . $vnd . "<br />";

			if ($vnd > 0)
			{
				$nextSql = "SELECT * FROM account_trans_details WHERE adt_trans_id = $id AND adt_ledger_id = 27 AND adt_active = 1 AND adt_status=1 ";
				$record	 = DBUtil::queryRow($nextSql);

				echo "adt_id : " . $record['adt_id'] . "<br />";

				$result = DBUtil::command('UPDATE `account_transactions` SET `act_type` = 2, `act_ref_id` = "' . $vnd . '" WHERE `act_id` = "' . $id . '"')->execute();

				$result1 = DBUtil::command('UPDATE `account_trans_details` SET `adt_trans_ref_id` = "' . $bookingID . '" WHERE `adt_id` = "' . $record['adt_id'] . '"')->execute();
			}
		}
	}

	public function actionRemoveAct()
	{
		$sql		 = "SELECT blg_booking_id FROM `booking_log` WHERE `blg_event_id` = 270";
		$recordsets	 = DBUtil::queryAll($sql);
		foreach ($recordsets as $res)
		{
			$bkgID	 = $res['blg_booking_id'];
			$result	 = AccountTransactions::model()->removeCompensationCharge($bkgID);
			echo "bookingID :" . $bkgID . "<br />";
			echo $result . "<br />";
		}
	}

	public function actionAddcities()
	{

		$sql	 = "SELECT * FROM test.railway_bus_data WHERE city_id = 0";
		$results = DBUtil::query($sql);
		foreach ($results as $city)
		{
			try
			{
				$postData['cty_name']				 = $city['city'];
				//$postData['cty_alias_name']			 = $city['city'];
				$postData['cty_state_id']			 = States::getByName($city['state'])->stt_id;
				$postData['cty_garage_address']		 = $city['city'] . ", " . $city['state'] . ", India";
				$postData['cty_bounds']				 = $city['bounds'];
				$postData['cty_poi_type']			 = $city['poi_type'];
				$result								 = GoogleMapAPI::getInstance()->getLatLong($city['city']);
				$postData['cty_lat']				 = $result['latitude'];
				$postData['cty_long']				 = $result['longitude'];
				$postData['cty_place_id']			 = $result['placeid'];
				$postData['cty_has_airport']		 = 0;
				$postData['cty_is_airport']			 = 0;
				$postData['cty_is_poi']				 = $city['is_poi'];
				$postData['cty_city_desc']			 = ($city['poi_type'] == 1) ? "Railway Terminal" : "Inter State Bus Terminal";
				$postData['cty_pickup_drop_info']	 = "";
				$postData['cty_ncr']				 = "";
				$zones								 = explode(",", $city['zones']);
				$zoneIds							 = [];
				foreach ($zones as $value)
				{
					$id = Zones::getIdByName(trim($value));
					if ($id > 0)
					{
						array_push($zoneIds, $id);
					}
				}
				$postData['cty_zones'] = $zoneIds;

				//$model = Cities::add($postData);
				$model					 = new Cities();
				$model->attributes		 = $postData;
				$model->cty_full_name	 = $postData['cty_name'];
				/* @var $place \Stub\common\Place */
				$placeObj				 = \Stub\common\Place::init($postData['cty_lat'], $postData['cty_long'], $postData['cty_place_id']);
				$placeObj->name			 = $postData['cty_name'];
				$lat					 = $placeObj->coordinates->latitude;
				$long					 = $placeObj->coordinates->longitude;
				$name					 = $placeObj->name;
				$placeId				 = $placeObj->place_id;
				$sql					 = "SELECT *, IF(cty_place_id = '$placeId',1,0) as rank "
						. "FROM `cities` "
						. "WHERE (cty_place_id = '$placeId' )"
						. " AND cty_types NOT LIKE '%administrative_area_level_2%'"
						. " AND cty_active = 1 "
						. " ORDER BY rank DESC, CalcDistance(cty_lat, cty_long, $lat, $long) ASC";
				$modelExist				 = Cities::model()->findBySql($sql);
				if ($modelExist)
				{
					continue;
				}
				if ($model->save())
				{
					echo "<br>City Added - " . $model->cty_id;
					echo "<br>";
					if ($postData['cty_zones'] != '')
					{
						$zones = $postData['cty_zones'];
						ZoneCities::model()->add($zones, $model->cty_id);
						echo "ZoneCities Added - " . implode(',', $zones);
						echo "<br>";
					}
					$sql	 = "UPDATE test.railway_bus_data SET city_id = {$model->cty_id} WHERE id = " . $city['id'];
					$success = DBUtil::execute($sql);
					if ($success == 1)
					{
						echo "railway_bus_data updated" . "<br>===========================";
					}
				}
			}
			catch (Exception $e)
			{
				echo "<br>" . "Error Adding - " . $city['city'] . "<br>";
				continue;
			}
		}
	}

	public function actionJwthash()
	{
		$token	 = Yii::app()->request->getParam('token');
		$jwt	 = Yii::app()->request->getParam('jwt');
		$vndid	 = Yii::app()->request->getParam('vndid') . Yii::app()->request->getParam('vndId');
		$vndcode = Yii::app()->request->getParam('vndcode');
		$drvcode = Yii::app()->request->getParam('drvcode');
		$drvid	 = Yii::app()->request->getParam('drvid');
		try
		{
			if ($vndcode != '' && strlen($vndcode) >= 6)
			{
				$vndcode = 'V-' . substr($vndcode, -6, 6);
				echo $vndid	 = Vendors::model()->findByCode($vndcode);
			}
			if ($drvcode != '' && strlen($drvcode) >= 6)
			{
				$drvcode = 'D-' . substr($drvcode, -6, 6);
				echo $drvid	 = Drivers::model()->getIdByCode($drvcode)->drv_id;
			}
			if (trim($token) == '')
			{
				if ($vndid > 0)
				{
					$entityId	 = $vndid;
					$entityType	 = UserInfo::TYPE_VENDOR;
					$token		 = AppTokens::getLatestTokenByEntity($entityId, $entityType);
				}
				if ($drvid > 0)
				{
					$entityId	 = $drvid;
					$entityType	 = '3, 5';
					$token		 = AppTokens::getLatestTokenByEntity($entityId, $entityType);
				}
			}
			if (trim($token) != '')
			{
				$jwtoken = JWTokens::generateToken($token);
			}
			if (trim($jwt) != '')
			{
				$tokenDecoded = JWTokens::decode($jwt);
				JWTokens::validateAppToken($jwt);
				AppTokens::validateToken($tokenDecoded->token);
			}
		}
		catch (Exception $ex)
		{
			$error = $ex->getMessage();
		}

		$this->renderAuto('tokenHash', array(
			'jwtoken'		 => $jwtoken,
			'tokenDecoded'	 => $tokenDecoded,
			'token'			 => $token,
			'jwt'			 => $jwt,
			'error'			 => $error));
	}

	public function actionAddHomeZone()
	{
		$results = VendorPref::actVndBlnkHomeZone();

		foreach ($results as $data)
		{
			try
			{
				$vndId		 = $data['vnp_vnd_id'];
				$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
				if ($contactId != "")
				{
					$contactModel	 = Contact::model()->findByPk($contactId);
					$cityId			 = $contactModel->ctt_city;
					if ($cityId != "")
					{
						$zoneData		 = Zones::model()->getNearestZonebyCity($cityId);
						$zoneId			 = $zoneData['zon_id'];
						$modifyHomeZone	 = VendorPref::addHomeZone($vndId, $zoneId);
					}
				}
			}
			catch (Exception $ex)
			{

				echo $error = $ex->getMessage();
			}
		}
	}

	public function actionProcessDriverDetailsToCustomerEvent()
	{
		BookingScheduleEvent::driverDetailsToCustomerEvent();
	}

	public function actionWhatsapp()
	{
		/*
		  #Whatsapp::writeSentMsgFile('START WRITING', 'w');

		  $templateName	 = 'sample_issue_resolution';
		  #$templateName	 = '';
		  $phoneNo		 = '+919831100164';

		  $arrComponent['header']	 = [];
		  $arrComponent['body']	 = [['type' => 'text', 'text' => 'Kaushal']];
		  #$arrComponent['body']	 = 'Test';
		  $arrComponent['buttons'] = [];

		  //		$data['entity_type'] = 1;
		  //		$data['entity_id']	 = 129215;
		  //
		  //		$data['ref_type']	 = 1;
		  //		$data['ref_id']		 = 1985623;

		  Whatsapp::sendMessage($phoneNo, $arrComponent, $templateName, $data);
		 */


		$res = WhatsappLog::sendTripDetailsToVendorDriver(3500068, 2);
		#$res = WhatsappLog::sendHelloWorld();

		echo "<br>=============<br>";
		var_dump($res);
	}

	public function actionProcessPostSyncDriverAppEvents()
	{
		$check = Filter::checkProcess("track processPostSyncDriverAppEvents");
		if (!$check)
		{
			return;
		}

		BookingScheduleEvent::postEvents();
	}

	/**
	 * this function is use for push airport booking to hornok operator
	 */
	public function actionPushBookingsToHornok()
	{
		$tripType	 = [12, 4, 10, 11];
		/* @var $bookingList booking */
		$bookingList = Booking::getBookings($tripType);
		foreach ($bookingList as $data)
		{
			$bkgId			 = $data['bkg_id'];
			$bkgCreateDate	 = $data['bkg_create_date'];

			/** @var OperatorApiTracking $cnt */
			$cnt = OperatorApiTracking::checkDuplicateId($bkgId, $typeAction, $bkgCreateDate);
			if ($cnt == 0)
			{
				$model		 = Booking::model()->findByPk($data['bkg_id']);
				$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
				$objOperator = Operator::getInstance($operatorId);

				/* @var $objOperator Operator */
				$objOperator = $objOperator->holdBooking($model->bkg_id, $operatorId);
			}
		}
	}

	/**
	 * use to push bookings to horn 
	 */
	public function actionPushUpdateBookingsToHornok()
	{
		$tripType	 = [12, 4];
		/* @var $bookingList booking */
		$bookingList = Booking::getAirport($tripType);

		$model		 = booking::model()->findByPk($data['bkg_id']);
		$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
		$objOperator = Operator::getInstance($operatorId);

		/* @var $objOperator Operator */
		$objOperator = $objOperator->updateBooking($model->bkg_id, $operatorId);
	}

	/**
	 * used to send bid booking details once bid accepted for horkok operator
	 */
	public function actionPushBidBookingsToHornok()
	{
		/* @var $bookingList booking */
		$bookingList = Booking::getBookingByBidAccepted();
		foreach ($bookingList as $data)
		{
			$operatorApiTracking					 = OperatorApiTracking::model()->findByPk($data['oat_id']);
			$operatorApiTracking->oat_request_count	 = 1;
			$operatorApiTracking->save();
			$operatorApiTracking->refresh();
			$model									 = booking::model()->findByPk($data['bkg_id']);
			$data									 = OperatorApiTracking::checkDuplicateIdWithBidAcceptedBooking($data['bkg_id']);
			if ($data['cnt'] > 0)
			{
				$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
				$objOperator = Operator::getInstance($operatorId);

				/* @var $objOperator Operator */
				$objOperator = $objOperator->holdBooking($model->bkg_id, $operatorId);
			}
		}
	}

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
					$refCode = QrCode::getCode($row['benefactorId']);
					$message = "Booking create by scanning QR Code: " . $refCode;
					BookingLog::model()->createLog($row['bkg_id'], $message, UserInfo::model(UserInfo::TYPE_CONSUMER, $row['beneficiaryId']), BookingLog::QR_SCAN);
				}
				else
				{
					$crTrans = AccountTransDetails::getInstance(Accounting::LI_OPERATOR, Accounting::AT_OPERATOR, $vndid, '', $remarks);
					$drTrans = AccountTransDetails::getInstance(Accounting::LI_CASH, Accounting::AT_OPERATOR, $vndid, '', $remarks);
				}
				$status = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_OPERATOR);

				if ($status)
				{
					$query = "UPDATE test.vendorcollectionreport_500 SET status = 1 WHERE `write_off` = 1 AND Vendor_ID = $vndid AND status = 0";
					DBUtil::execute($query);
					DBUtil::commitTransaction($transaction);
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

	public function actionTransferz()
	{
		$jsonData	 = '{"id":363773,"code":"JD88VJ-1","pickup":{"bookerEnteredAddress":"Netaji Subhas Chandra Bose Airport (CCU), Jessore Road, 700052 Kolkata, North 24 Parganas, India","resolvedAddress":"Netaji Subhas Chandra Bose Airport (CCU), Jessore Road, 700052 Kolkata, North 24 Parganas, India","fullResolvedAddress":{"streetName":"Jessore Road","city":"Kolkata","postalCode":"700052","region":"North 24 Parganas","countryCode":"IN","establishment":"Netaji Subhash Chandra Bose International Airport"},"latitude":22.64531,"longitude":88.43931,"hub":true,"iataCode":"CCU"},"dropoff":{"bookerEnteredAddress":"Park Street Metro Station, Jawaharlal Nehru Road, Maidan, Kolkata, West Bengal, India","resolvedAddress":"H83X+RW2, Jawaharlal Nehru Rd, Maidan, Kolkata, West Bengal 700071, India","fullResolvedAddress":{"streetName":"Jawaharlal Nehru Road","city":"Kolkata","postalCode":"700071","region":"Kolkata","countryCode":"IN","establishment":"Park Street Metro Station"},"latitude":22.5545156,"longitude":88.3497656,"hub":false},"pickupTime":{"localTime":"2023-05-11T08:10:00","timeZone":"Asia\/Calcutta"},"addOns":[],"vehicleCategory":"SEDAN","distance":22.159,"distanceUnit":"KILOMETER","duration":2298,"fareSummary":{"includingVat":23.28,"excludingVat":22.17,"vat":1.11,"currency":"USD"},"status":"PENDING","driverCode":"632QRA035","assignmentStatus":"ASSIGNED","travellerInfo":{"firstName":"Ankesh","lastName":"Singh","email":"jsrankesh.singh@gmail.com","phone":"+919903430853","meetAndGreetSignText":"","passengerCount":2,"luggageCount":0,"driverComments":"","flightNumber":"BR011","trainNumber":"","language":"en-US"},"hash":"22ef67aa6e58cefa8b1936b770b5d17b3c67896cecbb6483817be8291f3bcd5d"}';
		$data		 = CJSON::decode($jsonData);
		echo $data['id'];
//		$model->trb_trz_journey_id	 = $jsonData->id;
//		$model->trb_trz_journey_code = $jsonData->code;
		echo $model->trb_trz_journey_id;
		exit();
	}

	public function actionTestWhatsapp()
	{
//		$bookingId = 'OW123';
//		$bkgId = '123';
//		TransferzOffers::sendWhatsapp('ASDSA', $bookingId, $bkgId);
//		die();
		$code			 = "91";
		$tripId			 = Yii::app()->request->getParam('tripId', "123456");
		$vndPhone		 = Yii::app()->request->getParam('phone', "8013269763");
		$vndId			 = Yii::app()->request->getParam('vndId', "43");
		$bcbhash		 = Yii::app()->shortHash->hash($tripId);
		$vndhash		 = Yii::app()->shortHash->hash($vndId);
		$templateName	 = 'bid_for_new_booking_notification_to_partner_with_stop_reminder_v2';
		$lang			 = 'en_US';
		$arrWhatsAppData = ["Sedan", "Chandigarh", "Indira Gandhi International Airport Delhi", "25/May/2023 08:00 AM", "₹2,582", "Oneway", "https://gozo.cab/bkvn1/HvkaW/vaMHv"];
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vndId, 'ref_type' => WhatsappLog::REF_TYPE_TRIP, 'ref_id' => $tripId];
		$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$templateId		 = WhatsappLog::findByTemplateNameLang($templateName, $lang, 'wht_id');
		$arrButton		 = Whatsapp::buildComponentButton([$templateId], 'button', 'quick_reply', "payload");
		$unsubscribe	 = UnsubscribePhoneno::checkBlockedNumber(($code . $vndPhone), 2, $templateId);
		if ($unsubscribe == 0)
		{
			$response = WhatsappLog::send(($code . $vndPhone), $templateName, $arrDBData, $arrBody, $arrButton, $lang);
			echo "<pre>";
			print_r($response);
		}
		else
		{
			echo "You had blocked your number from whatsapp notification";
		}
	}

	public function actionRevertGstInFullAdvanceORCashCollectBooking()
	{
		$sql	 = "SELECT bkg_id,
						bkg_advance_amount,bkg_advance_amount_new,
						bkg_total_amount,bkg_total_amount_new
						bkg_service_tax,bkg_service_tax_new
					FROM test.mmtBookingGst 
					WHERE rbb_status = 2 AND active IN(1,3)";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				echo "<br>BkgId: " . $row['bkg_id'];
				if ($row['bkg_advance_amount'] > 0)
				{
					$model									 = Booking::model()->findByPk($row['bkg_id']);
					$model->bkgInvoice->bkg_advance_amount	 = $row['bkg_advance_amount_new'];
					$model->bkgInvoice->bkg_service_tax		 = 0;
					$model->bkgInvoice->calculateTotal();
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update BkgId ::" . $row['bkg_id'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}
					// refund service tax
					$bkg_id			 = $row['bkg_id'];
					$remarks		 = "Gst reverted booking to wallet bkgid:" . $bkg_id;
					$date			 = $model->bkg_pickup_date;
					$amount			 = $row['bkg_service_tax'];
					$partnerId		 = $model->bkg_agent_id;
					$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
					$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
					$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
					$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
					if (!$status)
					{
						throw new Exception("Unable to revert gst to wallet bkgid:" . $bkg_id);
					}
					$query = "UPDATE test.mmtBookingGst SET status = 1 WHERE bkg_id = $bkg_id";
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

	public function actionGetBookingAccountEntries()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$res	 = AccountTransactions::getEntriesByBooking($bkgId);
		//echo $this->generateTable($res);exit;
		$str	 .= '<table border="1" style="border-collapse:collapse">';
		foreach ($res as $k => $rowSet)
		{
			if ($k == 0)
			{
				$str .= '<tr>';
				foreach ($rowSet as $th => $data)
				{
					$str .= '<th style="padding:5px; white-space:nowrap " >';
					$str .= str_replace(['adt_', 'act_'], '', $th);
					$str .= '</th>';
				}
				$str .= '</tr >';
			}
			$borderAtt	 = '';
			$bgColor	 = '';
			if ($k & 1)
			{
				$borderAtt	 = "border-bottom:2px solid #696969;border-top:0;";
				$bgColor	 = "background:#ffeeee;";
			}
			else
			{
				$borderAtt	 = "border-bottom:1;border-top:0;";
				$bgColor	 = "background:#eeffee;";
			}
			if ($rowSet['adt_active'] <> 1)
			{
				//$borderAtt	 .= "border-bottom-color:#ff9999;";
				$bgColor = "background:#ffbbbb;";
			}
			$str .= "<tr style='$borderAtt $bgColor'>";
			foreach ($rowSet as $row => $data)
			{
				if (in_array($row, ['adt_trans_id', 'act_date', 'adt_modified', 'adt_active']))
				{
					if ($k & 1)
					{
						continue;
					}
					else
					{
						$str .= '<td rowspan="2" style="padding:5px; white-space:nowrap ">';
						$str .= $data;
					}
				}
				else
				{
					$rightAlign	 = (is_numeric($data) == 1) ? ';text-align:right' : '';
					$str		 .= '<td style="padding:5px; white-space:nowrap ' . $rightAlign . '">';
					$str		 .= $data;
				}

				$str .= '</td>';
			}
			$str .= '</tr>';
		}
		$str .= '</table>';
		echo $str;
		exit;
	}

	public function actionRevertGstInCancelBooking()
	{
		$sql	 = "SELECT bkg_id,
						bkg_advance_amount,bkg_advance_amount_new,
						bkg_refund_amount,bkg_refund_amount_new,
						bkg_total_amount,bkg_total_amount_new,
						bkg_service_tax,bkg_service_tax_new,
						bkg_due_amount,bkg_due_amount_new,
						bkg_cancel_charge,bkg_cancel_charge_new,
						bkg_net_advance_amount
					FROM test.mmtBookingGst 
					WHERE rbb_status = 2 AND active = 2";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				$model	 = Booking::model()->findByPk($row['bkg_id']);
				echo "<br>BkgId: " . $bkg_id	 = $row['bkg_id'];
				if ($row['bkg_net_advance_amount'] == 0)
				{
					$model->bkgInvoice->bkg_advance_amount	 = $row['bkg_advance_amount_new'];
					$model->bkgInvoice->bkg_refund_amount	 = $row['bkg_refund_amount_new'];
					$model->bkgInvoice->bkg_due_amount		 = $row['bkg_due_amount_new'];
					$model->bkgInvoice->bkg_total_amount	 = $row['bkg_total_amount_new'];
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update :: BkgId" . $row['bkg_id'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}
					AccountTransactions::removePartnerAdvance($model->bkg_id);
					AccountTransactions::advancePartnerWallet($model->bkg_agent_id, $model->bkg_id, $row['bkg_advance_amount_new'], $model->bkg_pickup_date, "Partner wallet used");
					AccountTransactions::refundPartnerWallet($model->bkg_agent_id, $model->bkg_id, $row['bkg_advance_amount_new'], $model->bkg_pickup_date);

					$query = "UPDATE test.mmtBookingGst SET status = 1 WHERE bkg_id = $bkg_id";
					DBUtil::execute($query);
				}
				else if ($row['bkg_net_advance_amount'] > 0)
				{
					$model->bkgInvoice->bkg_advance_amount	 = $row['bkg_advance_amount_new'];
					$model->bkgInvoice->bkg_total_amount	 = $row['bkg_total_amount_new'];
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update :: BkgId" . $row['bkg_id'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}

					AccountTransactions::removePartnerAdvance($model->bkg_id);
					AccountTransactions::advancePartnerWallet($model->bkg_agent_id, $model->bkg_id, $row['bkg_advance_amount_new'], $model->bkg_pickup_date, "Partner wallet used");
					if ($row['bkg_cancel_charge_new'] > 0)
					{
						AccountTransactions::AddCancellationCharge($model->bkg_id, $model->bkg_pickup_date, $row['bkg_cancel_charge_new']);
					}
					if ($row['bkg_refund_amount_new'] > 0)
					{
						$model->bkgInvoice->bkg_refund_amount	 = $row['bkg_refund_amount_new'];
						$model->bkgInvoice->bkg_due_amount		 = $row['bkg_due_amount_new'];
						if ($model->bkgInvoice->save())
						{
							AccountTransactions::refundPartnerWallet($model->bkg_agent_id, $model->bkg_id, $row['bkg_refund_amount_new'], $model->bkg_pickup_date);
						}
					}

					$query = "UPDATE test.mmtBookingGst SET status = 1 WHERE bkg_id = $bkg_id";
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

//	public function actionCustomerOngoingTrip()
//	{
//		$bkgId = Yii::app()->request->getParam('bkgId', "3483700");
//		WhatsappLog::sendWhatsappForCustomerOngoingTrip($bkgId);
//	}

	public function actionSendQTMsg()
	{
		$bkgId = Yii::app()->request->getParam('bkgId', "3495772");

		$response = WhatsappLog::sendPaymentRequestForBkg($bkgId);

		var_dump($response);
	}

	public function actionCancelDemo()
	{
		$id				 = 1904866;
		$bookingModel	 = Booking::model()->findByPk($id);
		$cancelCharges	 = $bookingModel->calculateCancelChargeWithoutGst($bookingModel);
	}

	public function actionUpdateMMT_OLD()
	{
		$sql	 = "SELECT bkg_id,bkg_advance_amount,bkg_total_amount,bkg_service_tax
					FROM test.mmtBookingGst 
					WHERE rbb_status = 0 AND active IN(1,3) limit 1000";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			if ($row['bkg_advance_amount'] > 0)
			{
				$totalAmount = ($row['bkg_total_amount'] - $row['bkg_service_tax']);
				$advance	 = ($row['bkg_advance_amount'] - $row['bkg_service_tax']);
				$bkg_id		 = $row['bkg_id'];
				$query		 = "UPDATE test.mmtBookingGst SET bkg_total_amount_new = $totalAmount,bkg_advance_amount_new = $advance,rbb_status = 2 WHERE bkg_id = $bkg_id";
				DBUtil::execute($query);

				echo "\r\nBkgId: " . $row['bkg_id'];
			}
		}
	}

	public function actionUpdateMMT()
	{
		$sqlUpd = "UPDATE test.mmtBookingGst SET rbb_status = 2, 
				bkg_total_amount_new = (bkg_total_amount - bkg_service_tax), 
				bkg_advance_amount_new = (bkg_advance_amount - bkg_service_tax) 
				WHERE rbb_status = 0 AND active IN (1,3) AND bkg_advance_amount > 0";
		DBUtil::execute($sqlUpd);
	}

	public function actionUpdateMMT1()
	{
		$sql	 = "SELECT bkg_id,bkg_advance_amount,bkg_refund_amount,bkg_total_amount,bkg_service_tax,bkg_due_amount,bkg_net_advance_amount
					FROM test.mmtBookingGst 
					WHERE rbb_status = 0 AND active = 2 AND bkg_advance_amount > 0 LIMIT 1000";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			if ($row['bkg_net_advance_amount'] == 0)
			{
				$totalAmount	 = ($row['bkg_total_amount'] - $row['bkg_service_tax']);
				$advance		 = ($row['bkg_advance_amount'] - $row['bkg_service_tax']);
				$refund			 = ($row['bkg_refund_amount'] - $row['bkg_service_tax']);
				$due			 = $totalAmount;
				$cancelCharge	 = 0;
			}
			else if ($row['bkg_net_advance_amount'] > 0)
			{
				$totalAmount = ($row['bkg_total_amount'] - $row['bkg_service_tax']);
				$advance	 = ($row['bkg_advance_amount'] - $row['bkg_service_tax']);

				$bkgModel		 = Booking::model()->findByPk($row['bkg_id']);
				$rule			 = CancellationPolicy::getRule($bkgModel->bkgPref->bkg_cancel_rule_id);
				$minCharges		 = $rule["minCharges"];
				$timeRules		 = $rule["timeRules"];
				$cancelCharge	 = CancellationPolicy::CalculateCharges($minCharges, $totalAmount);
				$cancelCharge	 = min([$advance, $cancelCharge]);
				if ($advance == $cancelCharge)
				{
					$refund			 = round($advance * 0.105);
					$cancelCharge	 = ($advance - $refund);
				}
				else
				{
					$refund = ($advance - $cancelCharge);
				}
				$due = ($totalAmount - ($advance - $refund));
			}

			$bkg_id	 = $row['bkg_id'];
			$query	 = "UPDATE test.mmtBookingGst SET rbb_status = 2, 
					bkg_total_amount_new = $totalAmount, bkg_advance_amount_new = $advance, bkg_refund_amount_new = $refund, 
					bkg_due_amount_new = $due, bkg_cancel_charge_new = $cancelCharge 
					WHERE bkg_id = $bkg_id";
			DBUtil::execute($query);

			echo "<br>BkgId: " . $row['bkg_id'];
		}
	}

	public function actionRevertGstMMT()
	{

		$sql	 = "SELECT bkg_id FROM test.mmtGstRevert_23_24 WHERE update_status = 0 AND bkg_status IN(6,7)";
		//$sql	 = "SELECT bkg_id FROM booking WHERE bkg_id = 1896059 AND bkg_status IN(6,7)";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				echo "<br>BkgId: " . $row['bkg_id'];
				$model = Booking::model()->findByPk($row['bkg_id']);
				if ($model)
				{
					$sTax									 = $model->bkgInvoice->bkg_service_tax;
					$model->bkgInvoice->bkg_advance_amount	 = ($model->bkgInvoice->bkg_advance_amount - $model->bkgInvoice->bkg_service_tax);
					$model->bkgInvoice->bkg_service_tax		 = 0;
					$model->bkgInvoice->bkg_service_tax_rate = 0;
					$model->bkgInvoice->calculateTotal_1();
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to update BkgId ::" . $row['bkg_id'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}
					$model->refresh();
					// refund service tax in accounts table
					$bkg_id			 = $model->bkg_id;
					$remarks		 = "Gst reverted booking to wallet bkgid:" . $bkg_id;
					$date			 = $model->bkg_pickup_date;
					$amount			 = $sTax;
					$partnerId		 = $model->bkg_agent_id;
					$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
					$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
					$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
					$status			 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
					if (!$status)
					{
						throw new Exception("Unable to revert gst to wallet bkgid:" . $bkg_id);
					}
					$query = "UPDATE test.mmtGstRevert_23_24 SET update_status = 1 WHERE bkg_id = $bkg_id";
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

	public function actionWa()
	{
		$tripId = Yii::app()->request->getParam('bcbId');

		if ($tripId == '' || $tripId == null)
		{
			die('No trip id');
		}

		WhatsappLog::tripAssignedToVendor($tripId);
	}

	public function actionInsertVendorDues()
	{
		$sql = "SELECT
					atd.adt_trans_ref_id vndId,
					ROUND(SUM(atd.adt_amount)) dueAmount,
					ROUND((SUM(atd.adt_amount))*0.5) waivedDueAmount
				FROM
					account_trans_details atd
				INNER JOIN account_transactions act ON
					act.act_id = atd.adt_trans_id
				INNER JOIN vendors vnd ON
					vnd.vnd_id = atd.adt_trans_ref_id AND vnd.vnd_id = vnd.vnd_ref_code
				WHERE
					atd.adt_ledger_id = 14 AND act.act_active = 1 AND atd.adt_active = 1 AND act.act_status = 1 AND atd.adt_status = 1 AND atd.adt_trans_ref_id NOT IN(
					SELECT
						atd.adt_trans_ref_id
					FROM
						account_trans_details atd
					INNER JOIN account_transactions act ON
						act.act_id = atd.adt_trans_id
					INNER JOIN account_trans_details atd1 ON
						act.act_id = atd1.adt_trans_id AND atd1.adt_id <> atd.adt_id AND atd1.adt_active AND atd1.adt_status = 1
					LEFT JOIN booking bkg ON
						bkg.bkg_bcb_id = atd1.adt_trans_ref_id
					LEFT JOIN booking_cab bcb ON
						bcb.bcb_id = atd1.adt_trans_ref_id
					WHERE
						atd.adt_ledger_id = 14 AND act.act_active = 1 AND atd.adt_active = 1 AND act.act_status = 1 AND atd.adt_status = 1 AND act.act_date >= '2022-12-31 00:00:00' AND atd.adt_trans_ref_id > 0
					GROUP BY
						atd.adt_trans_ref_id
				)
				GROUP BY
					atd.adt_trans_ref_id
				HAVING
					dueAmount > 0 limit 0,5";

		$details = DBUtil::query($sql, DBUtil::SDB());
		foreach ($details as $row)
		{
			try
			{
				$sqldata = "INSERT INTO `vendor_account_details`(`vad_id`, `vad_vnd_id`, `vad_total_due_amount`, `vad_waived_due_amount`, `vad_paid_due_amount`, `vad_paid_date`, `vad_status`, `vad_link_clicked_date`, `vad_send_date`) VALUES
							(NULL, '" . $row['vndId'] . "', '" . $row['dueAmount'] . "', '" . $row['waivedDueAmount'] . "', NULL, NULL, '0', NULL, NULL)";
				DBUtil::execute($sqldata);
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				Logger::writeToConsole($ex->getMessage());
			}
			echo "<br>Done Bkg_ID===" . $row['vndId'];
		}
	}

	public function actionUpdatePartnerAirportRates()
	{
		$sql = "SELECT * FROM partner_airport_transfer WHERE pat_active = 1 AND pat_vehicle_type IN (1,2,3) ORDER BY pat_id ASC";
		$res = DBUtil::query($sql);
		foreach ($res as $rec)
		{
			echo "<br>================<br>oldPatId = " . $oldPatId				 = $rec['pat_id'];
			$cityId					 = $rec['pat_city_id'];
			$transferType			 = $rec['pat_transfer_type'];
			$vehicleType			 = $rec['pat_vehicle_type'];
			echo "<br>vendorAmount = " . $vendorAmount			 = $rec['pat_vendor_amount'];
			echo "<br>totalFare = " . $totalFare				 = $rec['pat_total_fare'];
			$minKM					 = $rec['pat_minimum_km'];
			echo "<br>extraPerKMRate = " . $extraPerKMRate			 = $rec['pat_extra_per_km_rate'];
			$partnerId				 = $rec['pat_partner_id'];
			$isAirportFeeIncluded	 = $rec['is_airport_fee_included'];

			echo "<br><br>newVendorAmount = " . $newVendorAmount	 = round(($vendorAmount * 1.15), -1);
			echo "<br>newTotalFare = " . $newTotalFare		 = round(($totalFare * 1.15), -1);
			echo "<br>newExtraPerKMRate = " . $newExtraPerKMRate	 = round($extraPerKMRate * 1.15);

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
					echo "<br>Found";
					$objPAT = PartnerAirportTransfer::model()->findByPk($patId);
				}
				else
				{
					echo "<br>Not Found";
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
				echo "<br>pat_id = " . $objPAT->pat_id;
				$objPAT->pat_vendor_amount		 = $newVendorAmount;
				$objPAT->pat_total_fare			 = $newTotalFare;
				$objPAT->pat_minimum_km			 = $minKM;
				$objPAT->pat_extra_per_km_rate	 = $newExtraPerKMRate;
				$objPAT->pat_active				 = 1;
				$objPAT->pat_modified_on		 = date("Y-m-d H:i:s");
				$objPAT->is_airport_fee_included = $isAirportFeeIncluded;
				$objPAT->scenario				 = 'update';
				$objPAT->save();
				echo "<br>DONE";
			}
			catch (Exception $ex)
			{
				echo "<br>Err: " . $ex->getMessage();
			}
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

	public function actionSendQuoteExpiryReminderToCustomer()
	{
		$bkgId = Yii::app()->request->getParam('bkgId');
		Booking::sendQuoteExpiryReminderToCustomer($bkgId);
	}

	public function actionSentReviewMessage()
	{
		$bkgId	 = "3697887";
		$res	 = WhatsappLog::bookingReviewToCustomer($bkgId);
		var_dump($res);
	}

	public function actionAdvRefundCorrection()
	{
		$sql	 = "SELECT * FROM test.booking_adv_mismatch_22_23 where bkg_status = 0 limit 1";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$model		 = Booking::model()->findByPk($val['bkg_id']);
					$bkg_id		 = $model->bkg_id;
					$date		 = $model->bkg_pickup_date;
					//$balance		 = (-1 * $val['vendorCollected']);
					$balance	 = $val['vendorCollected'];
					$partnerId	 = $model->bkg_agent_id;
					if (($val['bkg_agent_id'] != '' || $val['bkg_agent_id'] != NULL) && $val['bkg_total_amount'] < $val['bkg_net_advance_amount'])
					{
//						$model->bkgInvoice->bkg_due_amount		 = 0;
//						$model->bkgInvoice->bkg_vendor_collected = 0;
//						$model->bkgInvoice->bkg_refund_amount	 += $balance;					
//						if (!$model->bkgInvoice->save())
//						{
//							throw new Exception("Unable to update bkgid:" . $bkg_id);
//						}
//						$amount			 = $balance;
//						$remarks		 = "Extra advance refunded for bkgid:" . $bkg_id;
//						$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
//						$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
//						$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
//						$success		 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
//						if ($success)
//						{
//							$sqlUpdate	 = "UPDATE test.booking_adv_mismatch_22_23 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
//							DBUtil::execute($sqlUpdate);
//
//							echo "DONE - " . $bkg_id . " - balance - " . $balance;
//						}
					}
					else
					{
//						$model->bkgInvoice->bkg_due_amount		 = 0;
//						$model->bkgInvoice->bkg_vendor_collected = 0;
//						$model->bkgInvoice->bkg_refund_amount	 += $balance;
//						if (!$model->bkgInvoice->save())
//						{
//							throw new Exception("Unable to update bkgid:" . $bkg_id);
//						}
//						$userId  = $model->bkgUserInfo->bkg_user_id;
//						$remarks = "Extra advance refunded for bkgid:" . $bkg_id;
//						$amount  = $val['vendorCollected'];
//						$success = AccountTransactions::processWallet($date, $amount, $bkg_id, Accounting::AT_BOOKING, Accounting::LI_BOOKING, $userId, $remarks);

						$model->bkgInvoice->bkg_due_amount		 = 0;
						$model->bkgInvoice->bkg_vendor_collected = $balance;
						if (!$model->bkgInvoice->save())
						{
							throw new Exception("Unable to update bkgid:" . $bkg_id);
						}
						$userInfo	 = UserInfo::getInstance();
						$success	 = AccountTransactions::model()->AddVendorCollection($model->bkgBcb->bcb_vendor_amount, $balance, $model->bkgBcb->bcb_id, $model->bkg_id, $model->bkgBcb->bcb_vendor_id, $date, $userInfo, $model->bkgBcb->bcb_trip_status);
						if ($success)
						{
							$sqlUpdate = "UPDATE test.booking_adv_mismatch_22_23 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
							DBUtil::execute($sqlUpdate);

							echo "DONE - " . $bkg_id . " - balance - " . $balance;
						}
					}
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionOnerupeeMismatch()
	{
		$sql	 = "SELECT * FROM test.driverCollectAccountEntryAmtTillJan2024 where bkg_status = 0";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$model		 = Booking::model()->findByPk($val['bkg_id']);
					$bkg_id		 = $model->bkg_id;
					$datetime	 = $model->bkg_pickup_date;
					$vendorId	 = $model->bkgBcb->bcb_vendor_id;
					if (($val['bivCashToCollect'] - $val['driverCollectAccountEntryAmt']) < 0)
					{
						$vendorCollected = ($val['bivCashToCollect'] - $val['driverCollectAccountEntryAmt']);
						$remarks		 = "Adjustment entry Rs" . $vendorCollected . " reverted:- Amount collected by operator";
					}
					else
					{
						$vendorCollected = ($val['bivCashToCollect'] - $val['driverCollectAccountEntryAmt']);
						$remarks		 = "Adjustment entry Rs" . $vendorCollected . " added:- Amount collected by operator";
					}

					$model->bkgInvoice->bkg_due_amount		 = 0;
					$model->bkgInvoice->bkg_vendor_collected = $val['bivCashToCollect'];
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Unable to update bkgid:" . $bkg_id);
					}

					$accTransDetArr		 = [];
					$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $vendorCollected, $remarks);
					$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkg_id, Accounting::LI_BOOKING, (-1 * $vendorCollected));
					$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $vendorCollected, $bkg_id, Accounting::AT_BOOKING, $remarks);
					// Booking Log
					$eventid			 = 0;
					$desc				 = $remarks;
					$userInfo			 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
					BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventid);
					if ($success)
					{
						$sqlUpdate = "UPDATE test.driverCollectAccountEntryAmtTillJan2024 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
						DBUtil::execute($sqlUpdate);

						echo "DONE - " . $bkg_id . " - vendorCollected - " . $vendorCollected;
					}
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionTdsRvertEntry()
	{
		$sql	 = "SELECT act_ref_id,act_amount FROM `account_transactions` 
					WHERE act_type = 2 AND `act_remarks` LIKE '%Provisional deducted amount for prospective TDS refunded%' 
					AND act_ref_id IN(9175,10509,18625,29405,30049,30892,34425,37071,38730,45183,60934,61451,65051,65157,66927,68169,68480,68775,69950,70492,70701,70790,71931,73001,74114,74567,
					74579,74712,75053,76547,77282,77466,77695,77778,77851,78446,79295,81388,81612,82140,83797,83830)
					GROUP BY act_ref_id";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			if ($val['act_ref_id'])
			{
				$vendorId	 = $val['act_ref_id'];
				$balance	 = (-1 * $val['act_amount']);
				$remarks	 = "Revert entry:- Provisional deducted amount for prospective TDS refunded";
				$datetime	 = '2023-03-31 23:59:59';

				$accTransDetArr		 = [];
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $balance), $remarks);
				$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_TDS, $balance, $remarks);
				$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $balance, $vendorId, Accounting::AT_OPERATOR, $remarks, UserInfo::model());
				if ($success)
				{
					echo "DONE - " . $vendorId . " - balance - " . $balance;
				}
			}
		}
	}

	public function actionVendorSecurityEntry()
	{
		$sql	 = "SELECT * FROM test.vendor_security1 WHERE securityDepositLedger = 0 OR securityDepositLedger > 0 AND vnd_status = 0 limit 1";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			$balance			 = ($val['securityDepositStats'] - $val['securityDepositLedger']);
			$vendorId			 = $val['vnd_id'];
			$datetime			 = '2022-04-01 00:00:00';
			$remarks			 = 'Security deposit adjusted';
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $balance, $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_SECURITY_DEPOSIT, (-1 * $balance));
			$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $balance, $vendorId, Accounting::AT_OPERATOR, $remarks);
			if ($success)
			{
				$sqlUpdate = "UPDATE test.vendor_security1 SET vnd_status = 1 WHERE vnd_id = $vendorId";
				DBUtil::execute($sqlUpdate);
				echo "DONE - " . $vendorId . " - balance - " . $balance;
			}
		}
	}

	public function actionGetWhatsappEventTemplates()
	{
		$type	 = Yii::app()->request->getParam('type', 1);
		$mapped	 = true;
		switch ($type)
		{
			case 1:
				$dataList	 = AgentMessages::getWATemplates($mapped);
				break;
			case 2:
				$dataList	 = AgentMessages::getWATemplates();
				break;
			case 3:
				$dataList	 = AgentMessages::getWATemplatesEvents();
				break;
			default:
				$dataList	 = AgentMessages::getWATemplates($mapped);
				break;
		}
		$table = $this->generateTable($dataList);
		echo $table;
		exit;
	}

//	public function actionNotification()
//	{
//		Logger::info("Notification Start");
//		$tripId		 = Yii::app()->request->getParam('tripId','3985148');
//		$type		 = Yii::app()->request->getParam('type');
//		Vendors::notifyAssignVendor($tripId, $isSchedule	 = 0,$type);
//		Logger::info("Notification Ends");
//	}

	public function actionDriverDetailsToCustomerNotification()
	{
		$bkgId		 = Yii::app()->request->getParam('bkgId', 3894188);
		Drivers::notifyDriverDetailsToCustomer($bkgId, $isSchedule	 = 0);
	}

//	public function actionBookingPaymentReceivedCustomerNotification()
//	{
//		notificationWrapper::notifyBookingPaymentReceivedCustomer(3500822, 1);
//	}

	public function actiontripCancelToDriver()
	{
		$bkgId		 = Yii::app()->request->getParam('bkgId');
		Booking::tripCancelToDriver($bkgId, $isSchedule	 = 0);
	}

	public function actionVendorReceivableAdjustmentEntry()
	{
		$sql	 = "SELECT * FROM test.vendorWorkBefore22_04 WHERE lastTransDate < '2023-01-01 00:00:00' AND vnd_status = 0";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			$balance			 = $val['adjustable'];
			$vendorId			 = $val['vndid'];
			$statsVal			 = ($val['security_amount'] - $val['adjustable']);
			$datetime			 = '2023-03-31 14:00:00';
			$remarks			 = 'Vendor due balance adjusted with security deposit';
			$accTransDetArr		 = [];
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, (-1 * $balance), $remarks);
			$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_SECURITY_DEPOSIT, $balance, $remarks);
			$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $balance, $vendorId, Accounting::AT_OPERATOR, $remarks);
			if ($success)
			{
				$sqlStats = "UPDATE vendor_stats SET vrs_security_amount = $statsVal WHERE vrs_vnd_id = $vendorId";
				DBUtil::execute($sqlStats);

				$sqlUpdate = "UPDATE test.vendorWorkBefore22_04 SET vnd_status = 1 WHERE vndid = $vendorId";
				DBUtil::execute($sqlUpdate);
				echo "DONE - " . $vendorId . " - balance - " . $balance;
				echo "</br>";

				Vendors::model()->updateDetails($vendorId);
			}
		}
	}

	public function actionunassignedTripFromVendor()
	{
		$bkgId = Yii::app()->request->getParam('bkgId', 3500822);
		Vendors::unassignedTripFromVendor($bkgId);
	}

	public function actionTransferBalance_22_23()
	{
		$sql	 = "SELECT * FROM test.partnerWalletDetails_22_23 WHERE status=0 AND adt_trans_ref_id IS NOT NULL limit 1";
		$rows	 = DBUtil::query($sql, DBUtil::MDB());
		foreach ($rows as $data)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$partnerId		 = $data['adt_trans_ref_id'];
				$closingBalance	 = (-1 * $data['closing']);
				$openingBalance	 = $data['closing'];
				$date			 = '2023-03-31 23:59:59';
				$date1			 = '2023-04-01 00:00:00';
				$succes1		 = true;
				$success		 = AccountTransactions::model()->partnerWalletToPartnerLedger($partnerId, $closingBalance, $date, "Closing balance transferred to partner account");
				if ($data['currentFY'] <> 0)
				{
					$succes1 = AccountTransactions::model()->partnerWalletToPartnerLedger($partnerId, $openingBalance, $date1, "Opening balance transferred from partner account");
				}

				if (!$success || !$succes1)
				{
					throw new Exception("<br>Failed adt_trans_ref_id===" . $partnerId . "===walletBalance===" . $amount);
				}
				$query = "UPDATE test.partnerWalletDetails_22_23 SET status=1 WHERE  adt_trans_ref_id = $partnerId AND status =0";
				DBUtil::execute($query);
				DBUtil::commitTransaction($transaction);
				echo "<br>Done adt_trans_ref_id===" . $partnerId . "===closingBalance===" . $closingBalance . "opening" . $openingBalance;
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
		}
	}

	public function actionsendTripDetailsToDriver()
	{
		$bkgId = Yii::app()->request->getParam('bkgId', 3500822);
		Booking::sendTripDetailsToDriver($bkgId);
	}

	public function actionsendTripDetailsToVendor()
	{
		//event 7
		$bkgId = Yii::app()->request->getParam('bkgId', 3500822);
		Booking::sendTripDetailsToVendor($bkgId);
	}

	public function actionConsumerForGozonow()
	{
		//booking_bid_received_to_customer
		$bkgId		 = Yii::app()->request->getParam('bkgId', 3500822);
		$response	 = Users::notifyConsumerForGozonow($bkgId);
		print($response);
	}

	public function actionTestSlaveLink()
	{
		$db			 = Yii::app()->db1;
		$delayCheck	 = 0;
		DBUtil::isSlaveUpdated($db, $delayCheck);
	}

	public function actionVendorAccountUnblocked()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$number	 = Yii::app()->request->getParam('number');

		Vendors::notifyToAccountUnblocked($vndId, null, null);
	}

	public function actionVendorAccountBlocked()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$number	 = Yii::app()->request->getParam('number');
		$success = Vendors::notifyToAccountBlocked($vndId, null, null);
		return $success;
	}

	public function actionVendorPaymentReleased()
	{
		$vndId = Yii::app()->request->getParam('vndId');
		Vendors::notifyVendorPaymentRelease($vndId, 200, 1, TemplateMaster::SEQ_WHATSAPP_CODE);
		Vendors::notifyVendorPaymentRelease($vndId, 200, 1, TemplateMaster::SEQ_APP_CODE);
	}

	public function actionEventNotification()
	{
		$res = ScheduleEvent::getEventList([ScheduleEvent::VENDOR_PAYMENT_RELEASE, ScheduleEvent::BOOKING_DRIVER_TO_CUSTOMER, ScheduleEvent::BOOKING_CAB_DRIVER_ASSIGNMNET, ScheduleEvent::BOOKING_REVIEW], [ScheduleEvent::BOOKING_REF_TYPE, ScheduleEvent::TRIP_REF_TYPE, ScheduleEvent::VENDOR_REF_TYPE]);
		foreach ($res as $row)
		{
			$model = ScheduleEvent::model()->findByPk($row['sde_id']);
			try
			{
				switch ($row['sde_event_id'])
				{
					case ScheduleEvent::BOOKING_CAB_DRIVER_ASSIGNMNET;
						$success = Vendors::notifyAssignVendor($row['sde_ref_id'], 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						break;
					case ScheduleEvent::BOOKING_DRIVER_TO_CUSTOMER;
						$success = Drivers::notifyDriverDetailsToCustomer($row['sde_ref_id'], 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						break;
					case ScheduleEvent::BOOKING_REVIEW;
						$success = Booking::bookingReview($row['sde_ref_id'], 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						break;
					case ScheduleEvent::VENDOR_PAYMENT_RELEASE;
						$jsonData	 = json_decode($row['sde_addtional_data']);
						$amount		 = $jsonData->amount;
						$success	 = Vendors::notifyVendorPaymentRelease($row['sde_ref_id'], $amount, 0, $row['sde_event_sequence']);
						if ($success)
						{
							$model->sde_event_status = 1;
							$model->save();
						}
						else
						{
							$model->sde_event_status = 2;
							$model->sde_last_error	 = "Fail";
							$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
							$model->save();
						}
						break;
				}
			}
			catch (Exception $ex)
			{
				$model->sde_event_status = 2;
				$model->sde_last_error	 = $ex->getMessage();
				$model->sde_err_count	 = $model->sde_err_count == 0 ? 1 : $model->sde_err_count + 1;
				$model->save();
				ReturnSet::exception($ex);
			}
		}
	}

	public function actionSendWhatsappOtp()
	{
		$phoneno		 = Yii::app()->request->getParam('phoneno', '918013269763');
		$otp			 = Yii::app()->request->getParam('otp', '918013269763');
		$arrDBData		 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => 43];
		$arrWhatsAppData = [$otp];
		$arrBody		 = Whatsapp::buildComponentBody($arrWhatsAppData);
		$arrButton		 = Whatsapp::buildComponentButton([$otp], 'button', 'url', 'payload');
		$lang			 = "en_GB";
		WhatsappLog::send($phoneno, "test_otp", $arrDBData, $arrBody, $arrButton, $lang);
	}

	public function actionChkPenalty()
	{
		$unassignedTime	 = "2023-10-19 08:10:47";
		$assignedTime	 = "2023-10-18 08:10:47";
		$pickupTime		 = "2023-10-18 10:00:00";
		$acceptType		 = 2;
		$vendorAmount	 = 8346;
		$dependencyScore = 78;
		$amount			 = BookingCab::GetUnassignPenaltyCharge($unassignedTime, $assignedTime, $pickupTime, $vendorAmount, $acceptType, $dependencyScore);
		echo $amount;
	}

	public function actionTest2()
	{
		$code	 = 231025000916299;
		$pgModel = \PaymentGateway::model()->getByCode($code);
//		$url		 = 'https://testdashboard.easebuzz.in/transaction/v1/retrieve';
//////		$data	 = $this->callApi($pgModel);
//		$payData	 = $this->getTransQueryData($pgModel);
//		$salt_key	 = 'DAH88E3UWQ';
//		$data		 = $this->_getTransaction($payData, $salt_key, $url);
//		$data	 = $this->_curlCall($url, $payData);
		$data	 = Yii::app()->easebuzz->getPaymentStatus($pgModel);
		var_dump($data);
		exit;
	}

	public function actionTestRefund()
	{
		$refundAmount	 = 110;
//		$bkg_id			 = 3782564;
		$code			 = 231026000916355;
		$pgModel		 = \PaymentGateway::model()->getByCode($code);
		$userInfo		 = new UserInfo();
		$response		 = $pgModel->refund($refundAmount, $userInfo, false);
		echo "<pre>";
		print_r($response);
		exit();
//		$result			 = PaymentGateway::model()->refundByRefId($amount, $bkg_id, Accounting::AT_BOOKING, $userInfo);
	}

	public function actionTestRefundStatus()
	{
		$code		 = 231025000916299;
//$code		 =231025000916309;
//		$pgModel = \PaymentGateway::model()->getByCode($code);
//		$success = Yii::app()->easebuzz->getRefundStatusByTranscode($code, $pgModel);
//		$code	 = 231025000916309;
//231025000916309;
		$pgModel	 = \PaymentGateway::model()->getByCode($code);
		$response	 = Yii::app()->easebuzz->getPaymentStatus($pgModel);
		echo "<pre>";
		print_r($response);
		exit();
	}

	public function actionLeadFollowup()
	{
		BookingTemp::notifyLeadFollowup(3503232, 0);
	}

	public function actionTestSMS1()
	{
		$url = "http://182.18.182.41/api/mt/SendSMS?user=SmartGzC&password=SMT@Gzc1&senderid=GOZOIN&channel=Trans&DCS=0&flashsms=1&number=919831100164&text=A%20new%20offer%20found%20for%20your%20booking.%20Check%20this%20link%20https%3A%2F%2Fc.gozo.cab%2F1YZic%20-%20aaocab&route=62&DLTTemplateId=1707169744347188263&PEID=1401449700000010560";

//		$data = array(
//			'name' => 'John Doe',
//			'email' => 'john.doe@example.com',
//			'phone' => '1234567890'
//		);
//
//		$json = json_encode($data);
		#$url = 'https://example.com/api/create';
		$ch = curl_init($url);

//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
////		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//			'Content-Type: application/json',
//			'Content-Length: 0'
//		));

		$response = curl_exec($ch);

		if (curl_errno($ch))
		{
			echo 'Error: ' . curl_error($ch);
		}
		else
		{
			echo $response;
		}
		curl_close($ch);
	}

	public function actionSendWebOtp()
	{
		$code		 = Yii::app()->request->getParam('code', '91');
		$number		 = Yii::app()->request->getParam('number', '8013269763');
		$otp		 = Yii::app()->request->getParam('otp', '2345');
		$dltId		 = Yii::app()->request->getParam('dltId', '1707164507767476278');
		$smsTextType = Yii::app()->request->getParam('smsTextType', 'webOTP');
		$smsLogType	 = Yii::app()->request->getParam('smsLogType', SmsLog::SMS_LOGIN_REGISTER);
		Users::notifySendOtp($code, $number, $otp, $dltId, $smsTextType, $smsLogType);
	}

	public function actionBidAcceptGnow()
	{
		$bkgId			 = Yii::app()->request->getParam('bkgId');
		$amount			 = Yii::app()->request->getParam('amount');
		$getCabId		 = Yii::app()->request->getParam('cabId');
		$getDriverId	 = Yii::app()->request->getParam('driverId');
		$getVendorId	 = Yii::app()->request->getParam('vendorId');
		$reachMinutes	 = Yii::app()->request->getParam('reachMinutes');

		$transaction = DBUtil::beginTransaction();
		try
		{

			$returnSet	 = new ReturnSet();
			/* @var $model Booking */
			$model		 = Booking::model()->findByPk($bkgId);
			if (!$model)
			{
				$error	 = "Invalid Booking";
				$errorId = 1;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$bcbId		 = $model->bkg_bcb_id;
			$bidAmount	 = ceil($amount);   //ceil($data['bidAmount']);
			$vendorId	 = $getVendorId;
			$driverId	 = $getDriverId;
			$cabId		 = $getCabId;

			/*  @var $drvModel Drivers */
			$drvModel = Drivers::model()->findByPk($driverId);
			if (!$drvModel)
			{
				$error	 = "Invalid Driver";
				$errorId = 1;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}

			$drvPhone = ContactPhone::getPhoneNo($driverId, UserInfo::TYPE_DRIVER);

			/* @var $vndModel Vendors */
			$vndModel = Vendors::model()->findByPk($vendorId);
			if (!$vndModel)
			{
				$error	 = "Invalid Vendor";
				$errorId = 1;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}

			/* @var $vhcModel Vehicles */
			$vhcModel = Vehicles::model()->findByPk($cabId);
			if (!$vhcModel)
			{
				$error	 = "Invalid Cab";
				$errorId = 1;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}


			/** @var BookingCab $cabModel */
			if ($bcbId == '' || $bcbId == 0)
			{
				$error	 = "Invalide data";
				$errorId = 1;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			if ($driverId == '' || $cabId == '')
			{
				$error	 = "Please provide driver and cab details";
				$errorId = 2;
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($bcbId);
			if (isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				$errorId = 3;
				$error	 = "Booking already assigned to other partner";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$cabModel = BookingCab::model()->findByPk($bcbId);

			if ($bidAmount == '' || $bidAmount == 0)
			{
				$errorId = 4;
				$error	 = "Please re-check the bid amount.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}


			$lastOffer = BookingVendorRequest::getMinimumGNowOfferAmountbyVendor($bcbId, $vendorId);
			if ($lastOffer && $lastOffer <= $bidAmount)
			{
				$errorId = 5;
				$error	 = "Current bid is higher than your previous bid(s). Try again.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}

			$bModels = $cabModel->bookings;
			$bkgId	 = $bModels[0]->bkg_id;

			////Abhishek bhaiya told to restrict the offer amount
			$isAdminGozoNow = ($bModels[0]->bkgPref->bkg_is_gozonow == 2) ? 1 : 0;

			$maxAllowableVndAmt	 = $cabModel->bcb_max_allowable_vendor_amount;
			$maxVndAmt			 = ($maxAllowableVndAmt > 0) ? $maxAllowableVndAmt : $cabModel->bcb_vendor_amount;

			$arrAllowedBids = $cabModel->getMinMaxAllowedBidAmount();
			#if ($maxVndAmt < $bidAmount && $isAdminGozoNow == 1)
			if ($arrAllowedBids['minBid'] > $bidAmount)
			{
				$errorId = 6;
				$error	 = "Bid amount is too small. Check your bid.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if ($arrAllowedBids['maxBid'] < $bidAmount)
			{
				$errorId = 7;
				$error	 = "Bid is much higher than other vendors. No chance of winning.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			Filter::parsePhoneNumber($drvPhone, $code, $driverMobile);
			if ($driverMobile == '')
			{
				$errorId = 8;
				$error	 = "Please provide valid driver mobile number";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			if ($reachMinutes == '' || $reachMinutes == 0)
			{
				$errorId = 9;
				$error	 = "Please enter the valid duration by which you will reach";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			$dnow = Filter::getDBDateTime();

			$reachingAT = date('Y-m-d H:i:s', strtotime($dnow . '+' . $reachMinutes . ' MINUTE'));

			$params				 = [
				'tripId'			 => $bcbId,
				'bkgId'				 => $bkgId,
				'bidAmount'			 => $bidAmount,
				'isAccept'			 => true,
				'driverId'			 => $driverId,
				'driverMobile'		 => $driverMobile,
				'cabId'				 => $cabId,
				'reachingAtMinutes'	 => $reachMinutes,
				'reachingAtTime'	 => $reachingAT
			];
			/** @var BookingCab $cabModel */
			$cabModel->scenario	 = 'assigncabdriver';

			$cabModel->bcb_driver_phone	 = $driverMobile;
			$cabModel->bcb_cab_id		 = $cabId;
			$cabModel->bcb_driver_id	 = $driverId;
			$cab_type					 = $bModels[0]->bkgSvcClassVhcCat->scv_vct_id;

			if ($cabModel->bcbCab->vhc_approved != 1)
			{
				$errorId = 9;
				$error	 = "Cab is not approved";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if (!$cabModel->bcbCab->getVehicleApproveStatus())
			{
				$errorId = 10;
				$error	 = "Cab is freezed";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if (!$cabModel->bcbDriver->getDriverApproveStatus())
			{
				$errorId = 11;
				$error	 = "Driver is not approved";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}
			$vvhcModel = VendorVehicle::model()->findByVndVhcId($vendorId, $cabId);
			if (!$vvhcModel && $vvhcModel->vvhc_active != 1)
			{
				$errorId = 12;
				$error	 = "Cab is not attached with you. Please sign LOU.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
			}

			if ($cab_type != '')
			{
				$cabModel->pre_cab_type	 = $cab_type;
				$cabModel->post_cab_type = $cabModel->bcbCab->vhcType->vht_VcvCatVhcType->vcv_vct_id;
			}

			Preg_match("/\d*(\d{10})/", $cabModel->bcb_driver_phone, $match);
			if (empty($match))
			{
				$errorId = 13;
				$cabModel->addError('bcb_driver_id', 'Driver Phone No is missing.');
				return false;
			}

			$cabModel->bcb_driver_phone = $match[1];

			$cabModel->bcb_cab_number	 = strtoupper($cabModel->bcbCab->vhc_number);
			$cabModel->bcb_trip_status	 = BookingCab::STATUS_CAB_DRIVER_ASSIGNED;
			$bModels[0]->bkg_status		 = 3;
			$validated					 = $cabModel->validate();

			foreach ($bModels as $bModel)
			{
				$bModel->refresh();
				$isVendorUnassigned = BookingLog::isVendorUnAssigned($vendorId, $bModel->bkg_id);
				if ($isVendorUnassigned)
				{
					$errorId = 14;
					$error	 = "You were unassigned from / denied this trip before. So you cannot bid on it again.";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}

				if (strtotime($bModel->bkg_pickup_date) + 4500 < strtotime($reachingAT) || strtotime($bModel->bkg_pickup_date) < strtotime($dnow))
				{
					$errorId = 15;
					$error	 = "Oops! Looks like you will not reach the pickup ontime";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}

				if ($bModel->bkg_status != 2)
				{
					$errorId = 16;
					$error	 = "Oops! The booking is already taken by another partner. Please be quicker next time";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				if ($bModel->bkgPref->bkg_block_autoassignment == 1)
				{
					$errorId = 17;
					$error	 = "Oops! This booking cannot be direct accepted.";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}

				if (!Drivers::checkDriverAvailability($vendorId, $cabModel->bcb_start_time, $cabModel->bcb_end_time))
				{
					$errorId = 19;
					$error	 = "Oops! You have no driver for this booking";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				$booking_class = $bModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
				if (!Vehicles::checkVehicleclass($vendorId, $booking_class))
				{
					$errorId = 20;
					$error	 = "Oops! You have no cab matching this booking class";
					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				$chkOutStanding = VendorStats::frozenOutstanding($vendorId);
				if ($chkOutStanding > 1500)
				{
					$errorId = 21;
					$error	 = "Oops! Your payment is overdue. Please settle your Gozo accounts.";

					throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
			}


			$bidModel = BookingVendorRequest::storeGNowRequest($params, $vendorId);

			if ($bidModel->bvr_id > 0)
			{

				$result		 = BookingTrail::notifyConsumerForMissedNewGnowOffers($bModel->bkg_id);
				$emailObj	 = new emailWrapper();
				$emailResult = $emailObj->mailGnowOfferReceived($bModel->bkg_id);
				notificationWrapper::customerNotifyBookingForGNow($bidModel);

				$drvCntId		 = ContactProfile::getByEntityId($driverId, UserInfo::TYPE_DRIVER);
				$drvCntDetails	 = Contact::getContactDetails($drvCntId);
				$driverName		 = $drvCntDetails['ctt_first_name'] . ' ' . $drvCntDetails['ctt_last_name'];
				if (empty(trim($driverName)))
				{
					$drvDetails	 = Drivers::getDriverInfo($driverId);
					$driverName	 = $drvDetails['drv_name'];
				}
				$cabDetails	 = Vehicles::getDetailbyid($cabId);
				$cabNumber	 = $cabDetails['vhc_number'];
				$desc		 = "Vendor offer received: Bid amount = &#x20B9;$bidAmount, reaching at = $reachingAT, cab number = $cabNumber, driver name = $driverName ($drvPhone)";
				BookingLog::model()->createLog($bModel->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BID_SET, false);
			}
			DBUtil::commitTransaction($transaction);

			$returnSet->setStatus(true);
			$returnSet->setMessage("Request processed successfully");
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}

		echo json_encode($returnSet);
	}

	public function actionDriverCollectionData()
	{
		$sql	 = "SELECT * FROM test.booking_collection_mismatch_950_23_24 where bkg_status = 0 limit 50";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$model		 = Booking::model()->findByPk($val['bkg_id']);
					$bkg_id		 = $model->bkg_id;
					$datetime	 = $model->bkg_pickup_date;
					$vendorId	 = $model->bkgBcb->bcb_vendor_id;
					if (($val['bivCashToCollect'] - $val['driverCollectAccountEntryAmt']) < 0)
					{
						$vendorCollected = ($val['bivCashToCollect'] - $val['driverCollectAccountEntryAmt']);
						$remarks		 = "Adjustment entry Rs" . $vendorCollected . " reverted:- Amount collected by operator";
					}
					else
					{
						$vendorCollected = ($val['bivCashToCollect'] - $val['driverCollectAccountEntryAmt']);
						$remarks		 = "Adjustment entry Rs" . $vendorCollected . " added:- Amount collected by operator";
					}

					$model->bkgInvoice->bkg_due_amount		 = 0;
					$model->bkgInvoice->bkg_vendor_collected = $val['bivCashToCollect'];
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Unable to update bkgid:" . $bkg_id);
					}

					$accTransDetArr		 = [];
					$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_OPERATOR, $vendorId, Accounting::LI_OPERATOR, $vendorCollected, $remarks);
					$accTransDetArr[]	 = AccountTransDetails::model()->initializeParams(Accounting::AT_BOOKING, $bkg_id, Accounting::LI_BOOKING, (-1 * $vendorCollected));
					$success			 = AccountTransactions::model()->add($accTransDetArr, $datetime, $vendorCollected, $bkg_id, Accounting::AT_BOOKING, $remarks);
					// Booking Log
					$eventid			 = 0;
					$desc				 = $remarks;
					$userInfo			 = UserInfo::model(UserInfo::TYPE_SYSTEM, 0);
					BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventid);
					if ($success)
					{
						$sqlUpdate = "UPDATE test.booking_collection_mismatch_950_23_24 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
						DBUtil::execute($sqlUpdate);

						echo "DONE - " . $bkg_id . " - vendorCollected - " . $vendorCollected;
					}
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionDbo()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId', '3895470');
		$resturn = Users::notifyDBO($bkgId);
	}

	public function actionExecutePartnerWallet()
	{
		BookingInvoice::processPartnerWallet();
	}

	public function actionConfirmBooking()
	{
		$bkgId		 = Yii::app()->request->getParam('id', 3898147);
		$response	 = Booking::notifyConfirmBookingB2C($bkgId);
		echo json_encode($response);
	}

	public function actionPaymentReceived()
	{
		$bkgId		 = Yii::app()->request->getParam('id');
		$response	 = Booking::notifyBookingPaymentReceivedByCustomerB2C($bkgId);
		echo json_encode($response);
	}

	public function actionPaymentRequest()
	{
		$bkgId		 = Yii::app()->request->getParam('id');
		$response	 = Booking::notifyPaymentRequestToCustomer($bkgId);
		echo json_encode($response);
	}

	public function actionQuoteBooking()
	{
		$bkgId		 = Yii::app()->request->getParam('id');
		$response	 = Booking::notifyQuoteBookingB2C($bkgId);
		echo json_encode($response);
	}

	public function actionAgentOneRupeesDiff()
	{

		$sql	 = "SELECT * FROM test.amountMismatchReportForB2CBookingJan2024 where bkg_status = 0";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$model		 = Booking::model()->findByPk($val['bkg_id']);
					$bkg_id		 = $model->bkg_id;
					$date		 = $model->bkg_pickup_date;
					$partnerId	 = $model->bkg_agent_id;
					if (($val['bkg_agent_id'] != '' || $val['bkg_agent_id'] != NULL) && $val['bkg_total_amount'] < $val['bkg_net_advance_amount'])
					{
						$balance								 = (-1 * $val['vendorCollected']);
						$model->bkgInvoice->bkg_due_amount		 = 0;
						$model->bkgInvoice->bkg_vendor_collected = 0;
						$model->bkgInvoice->bkg_refund_amount	 += $balance;
						if (!$model->bkgInvoice->save())
						{
							throw new Exception("Unable to update bkgid:" . $bkg_id);
						}
						$amount			 = $balance;
						$remarks		 = "Extra advance refunded for bkgid:" . $bkg_id;
						$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
						$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
						$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
						$success		 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
						if ($success)
						{
							$sqlUpdate = "UPDATE test.amountMismatchReportForB2CBookingJan2024 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
							DBUtil::execute($sqlUpdate);

							echo "DONE - " . $bkg_id . " - balance - " . $balance;
						}
					}

					if (($val['bkg_agent_id'] != '' || $val['bkg_agent_id'] != NULL) && $val['bkg_total_amount'] > $val['bkg_net_advance_amount'])
					{
						$balance								 = $val['vendorCollected'];
						$model->bkgInvoice->bkg_due_amount		 = 0;
						$model->bkgInvoice->bkg_vendor_collected = 0;
						$model->bkgInvoice->bkg_advance_amount	 += $balance;
						if (!$model->bkgInvoice->save())
						{
							throw new Exception("Unable to update bkgid:" . $bkg_id);
						}
						$amount			 = $balance;
						$remarks		 = "advance added for bkgid:" . $bkg_id;
						$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
						$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
						$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
						$success		 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
						if ($success)
						{
							$sqlUpdate = "UPDATE test.amountMismatchReportForB2CBookingJan2024 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
							DBUtil::execute($sqlUpdate);

							echo "DONE - " . $bkg_id . " - balance - " . $balance;
						}
					}
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionB2CDiff()
	{
		$sql	 = "SELECT * FROM test. amountMismatchReportForMMTBookingFebLast2024 where bkg_status = 0";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$model		 = Booking::model()->findByPk($val['bkg_id']);
					$bkg_id		 = $model->bkg_id;
					$date		 = $model->bkg_pickup_date;
					$partnerId	 = $model->bkgUserInfo->bkg_user_id;
//					if ($val['bkg_total_amount'] < $val['bkg_net_advance_amount'])
//					{
//						$balance	 = (-1 * $val['vendorCollected']);
//						$model->bkgInvoice->bkg_due_amount		 = 0;
//						$model->bkgInvoice->bkg_vendor_collected = 0;
//						$model->bkgInvoice->bkg_refund_amount	 += $balance;					
//						if (!$model->bkgInvoice->save())
//						{
//							throw new Exception("Unable to update bkgid:" . $bkg_id);
//						}
//						$amount			 = $balance;
//						$remarks		 = "Extra advance refunded for bkgid:" . $bkg_id;
//						$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
//						$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
//						$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_WALLET, Accounting::AT_BOOKING, $partnerId, '', $remarks, null);
//						$success		 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
//						if ($success)
//						{
//							$sqlUpdate	 = "UPDATE test.amountMismatchReportForB2CBookingJan2024 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
//							DBUtil::execute($sqlUpdate);
//
//							echo "DONE - " . $bkg_id . " - balance - " . $balance;
//						}
//					}
					if (($val['bkg_agent_id'] != '' || $val['bkg_agent_id'] != NULL) && $val['bkg_total_amount'] < $val['bkg_net_advance_amount'])
					{
						$balance								 = (-1 * $val['vendorCollected']);
						$model->bkgInvoice->bkg_due_amount		 = 0;
						$model->bkgInvoice->bkg_vendor_collected = 0;
						$model->bkgInvoice->bkg_advance_amount	 += $balance;
						if (!$model->bkgInvoice->save())
						{
							throw new Exception("Unable to update bkgid:" . $bkg_id);
						}
						$amount			 = $balance;
						$remarks		 = "Extra advance refunded for bkgid:" . $bkg_id;
						$accTransModel	 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
						$crTrans		 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
						$drTrans		 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
						$success		 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
						if ($success)
						{
							$sqlUpdate = "UPDATE test.amountMismatchReportForMMTBookingFebLast2024 SET bkg_status = 1 WHERE bkg_id = $bkg_id";
							DBUtil::execute($sqlUpdate);

							echo "DONE - " . $bkg_id . " - balance - " . $balance;
						}
					}
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionGetScqCount()
	{
		$scqId = Yii::app()->request->getParam('scqId');
		if ($scqId > 0)
		{
			$data = ServiceCallQueue::getQueueNumber($scqId);
//			$data['rank']	 = $data['rank'] | 0;
//
//			$data['scq_active'] = (int) $data['scq_active'] | 0;
//			if($data['rank'] == 0)
//			{
//				$data['totalWaitMinutes'] = 2;
//			}
			//echo "<pre>";
			var_dump($data);
			exit;
		}
		else
		{
			echo "no scqId provided";
		}
		exit;
	}

	public function actionTripAmountReset()
	{
		$bkgId		 = Yii::app()->request->getParam('id', 4284143);
		$response	 = Booking::notifyBookingComplete($bkgId);
		echo json_encode($response);
	}

	public function actionMMTAdvanceMismatch()
	{

		$sql	 = "SELECT * FROM test.amountMismatchReportForMMTBookingFebLast2024 where bkg_status = 1";
		$rows	 = DBUtil::query($sql);
		foreach ($rows as $val)
		{
			try
			{
				if ($val['bkg_id'] > 0)
				{
					$model									 = Booking::model()->findByPk($val['bkg_id']);
					$model->bkgInvoice->bkg_advance_amount	 = $model->bkgInvoice->bkg_total_amount;
					$model->bkgInvoice->save();
				}
			}
			catch (Exception $ex)
			{
				echo "<br>Error == " . $ex->getMessage();
			}
		}
	}

	public function actionDriverCollectAccountEntryAmtUpto()
	{

		$sql	 = "SELECT bkg_id FROM test.bookingAmtMismatchReports WHERE update_status = 0 AND bkg_status IN(6,7) ORDER BY bkg_id";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				echo "\nBkgId: " . $row['bkg_id'];
				$model = Booking::model()->findByPk($row['bkg_id']);
				if ($model)
				{
					$sql	 = "SELECT act_amount FROM account_transactions WHERE act_ref_id = $model->bkg_id ORDER BY act_id ASC LIMIT 0,1";
					$rows	 = DBUtil::queryRow($sql);

					$model->bkgInvoice->bkg_total_amount = $model->bkgInvoice->bkg_vendor_collected + $rows['act_amount'];
					$model->bkgInvoice->bkg_due_amount	 = 0;
					if (!$model->bkgInvoice->save())
					{
						$query = "UPDATE test.bookingAmtMismatchReports SET update_status = 1 WHERE bkg_id = " . $row['bkg_id'];
						DBUtil::execute($query);

						throw new Exception("Failed to update BkgId ::" . $row['bkg_id'] . " :: msg" . json_encode($model->bkgInvoice->getErrors()));
					}
					$model->refresh();

					$query = "UPDATE test.bookingAmtMismatchReports SET update_status = 1 WHERE bkg_id = {$model->bkg_id}";
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

	public function actionNotificationVendorBalWriteoff()
	{
		$sql	 = "SELECT * FROM test.vendor_writeoff_06032024 where is_processed = 0 LIMIT 0, 1";
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
					$sqlUpdate = "UPDATE test.vendor_writeoff_06032024 SET is_processed =1 WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);
				}
				else
				{
					$arrDBData			 = ['entity_type' => UserInfo::TYPE_VENDOR, 'entity_id' => $vendorId, 'ref_type' => WhatsappLog::REF_TYPE_VENDOR, 'ref_id' => $vendorId];
					$arrBody			 = Whatsapp::buildComponentBody([$balance]);
					$arrButton			 = Whatsapp::buildComponentButton([$hash]);
					$response			 = WhatsappLog::send(918013269763, 'vendor_write_off_v1', $arrDBData, $arrBody, $arrButton, 'en_GB');
					$whatsappResponse	 = json_encode($response);
					$phonenumber		 = $row['code'] . $row['number'];
					$sqlUpdate			 = "UPDATE test.vendor_writeoff_06032024 SET is_processed =1,phone_number=$phonenumber,whatsapp_response='$whatsappResponse' WHERE vnd_id = $vendorId";
					DBUtil::execute($sqlUpdate);
				}
			}
		}
	}

	public function actionRevertGst()
	{
		$sql	 = "SELECT bkg_id FROM test.mmtRevertGST2024 WHERE update_status = 0 AND bkg_status IN(6,7) ORDER BY bkg_id";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				echo "\nBkgId: " . $row['bkg_id'];
				$model = Booking::model()->findByPk($row['bkg_id']);
				if ($model)
				{

					// refund service tax in accounts table
					$sql	 = "SELECT act_amount FROM account_transactions WHERE act_ref_id = $model->bkg_id ORDER BY act_id ASC LIMIT 0,1";
					$rows	 = DBUtil::queryRow($sql);

					$model->bkgInvoice->bkg_advance_amount	 = $rows['act_amount'];
					$bkg_id									 = $model->bkg_id;
					$remarks								 = "GST reverted booking to wallet bkgid:" . $bkg_id;
					$date									 = $model->bkg_pickup_date;
					$amount									 = round($model->bkgInvoice->bkg_total_amount * 0.05);
					$partnerId								 = $model->bkg_agent_id;
					$accTransModel							 = AccountTransactions::getInstance(Accounting::AT_BOOKING, $date, $amount, $remarks, $bkg_id, null);
					$drTrans								 = AccountTransDetails::getInstance(Accounting::LI_BOOKING, Accounting::AT_BOOKING, $bkg_id, '', $remarks);
					$crTrans								 = AccountTransDetails::getInstance(Accounting::LI_PARTNERWALLET, Accounting::AT_PARTNER, $partnerId, '', $remarks, null);
					$status									 = $accTransModel->processReceipt($drTrans, $crTrans, Accounting::AT_BOOKING);
					if (!$status)
					{
						$query = "UPDATE test.mmtRevertGST2024 SET update_status = 2 WHERE bkg_id = {$bkg_id}";
						DBUtil::execute($query);

						throw new Exception("Unable to revert gst to wallet bkgid:" . $bkg_id);
					}
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);

					$query = "UPDATE test.mmtRevertGST2024 SET update_status = 1 WHERE bkg_id = {$bkg_id}";
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

	public function actionRevertOnlyGstMMT()
	{
		$sql	 = "SELECT bkg_id FROM test.mmtadvanceDataMismatchData WHERE update_status = 0 AND bkg_status IN(6,7) ORDER BY bkg_id LIMIT 0,1";
		$results = DBUtil::query($sql, DBUtil::SDB());
		foreach ($results as $row)
		{
			try
			{
				echo "\nBkgId: " . $row['bkg_id'];
				$model = Booking::model()->findByPk($row['bkg_id']);
				if ($model)
				{
					$sql	 = "SELECT act_amount FROM account_transactions WHERE act_ref_id = $model->bkg_id ORDER BY act_id ASC LIMIT 0,1";
					$rows	 = DBUtil::queryRow($sql);

					$sTax = $rows['act_amount'] - $model->bkgInvoice->bkg_advance_amount;

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
						$query = "UPDATE test.mmtadvanceDataMismatchData SET update_status = 2 WHERE bkg_id = {$bkg_id}";
						DBUtil::execute($query);

						throw new Exception("Unable to revert gst to wallet bkgid:" . $bkg_id);
					}
					BookingInvoice::updateGozoAmount($model->bkg_bcb_id);

					$query = "UPDATE test.mmtadvanceDataMismatchData SET update_status = 1 WHERE bkg_id = {$bkg_id}";
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

	public function actionNotificationVendorDownloadDco()
	{
		$sql	 = "SELECT * FROM test.vendor_dco_download_20032024 WHERE is_processed = 0 AND status=1 LIMIT 0,10";
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
						$sqlUpdate = "UPDATE test.vendor_dco_download_20032024 SET is_processed =1,processed_at=NOW() WHERE 1 AND status=1 AND  vnd_id = $vendorId";
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
						$sqlUpdate			 = "UPDATE test.vendor_dco_download_20032024 SET is_processed =1,phone_number=$phonenumber,whatsapp_response='$whatsappResponse',processed_at=NOW() WHERE 1 AND status=1 AND vnd_id = $vendorId";
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

	public function actionGetDuplicateContacts()
	{
		Contact::getDuplicateContacts();
	}

	public function actionTrkList()
	{
		// echo "<pre>";
		$sql	 = "select * from users_source_tracking";
		$rows	 = DBUtil::query($sql);
		echo "<table border='1'><tr><td>ust_id</td><td>ust_user_id</td><td>ust_tracking_id</td><td>ust_user_phone</td>"
		. "<td>ust_user_email</td><td>ust_source</td><td>ust_medium</td><td>ust_ip</td><td>ust_campaign_id</td>"
		. "<td>ust_group_id</td><td>ust_keyword</td><td>ust_referal_url</td><td>ust_create_date</td></tr>";
		foreach ($rows as $val)
		{
			//  print_r($val);
			echo "<tr><td>" . $val['ust_id'] . "</td>"
			. "<td>" . $val['ust_user_id'] . "</td>"
			. "<td>" . $val['ust_tracking_id'] . "</td>"
			. "<td>" . $val['ust_user_phone'] . "</td>"
			. "<td>" . $val['ust_user_email'] . "</td>"
			. "<td>" . $val['ust_source'] . "</td>"
			. "<td>" . $val['ust_medium'] . "</td>"
			. "<td>" . $val['ust_ip'] . "</td>"
			. "<td>" . $val['ust_campaign_id'] . "</td>"
			. "<td>" . $val['ust_group_id'] . "</td>"
			. "<td>" . $val['ust_keyword'] . "</td>"
			. "<td>" . $val['ust_referal_url'] . "</td>"
			. "<td>" . $val['ust_create_date'] . "</td></tr>";
		}
		echo "</table>";
	}

	public function actionUpdateCommissionIBIBO()
	{
		$sql	 = "SELECT * FROM test.updateCommissionIBIBO WHERE status = 0 LIMIT 0,1";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$model			 = Booking::model()->findByPk($row['bkg_id']);
			$extraCommission = round(($row['bkg_extra_km_charge'] + $row['bkg_extra_toll_tax'] + $row['bkg_extra_state_tax']) / 1.05);

			$model->bkgInvoice->bkg_cp_comm_type		 = 1;
			$model->bkgInvoice->bkg_partner_commission	 = $extraCommission;
			$model->bkgInvoice->save();

			$getAtdDataSql	 = "SELECT adt_amount FROM account_trans_details WHERE adt_trans_ref_id = $model->bkg_id AND adt_ledger_id = 35";
			$getAmt			 = DBUtil::queryScalar($getAtdDataSql, DBUtil::SDB());
			$agentCommission = $getAmt + $extraCommission;
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

	public function actionChangeUST()
	{
		$sql	 = "select * from users_source_tracking WHERE ust_source IS NOT NULL AND ust_id < 3870";
		$records = DBUtil::query($sql, DBUtil::SDB());
		foreach ($records as $row)
		{
			$var1		 = $row['ust_source'];
			$v			 = explode("source :", $var1);
			$v1			 = $v[1];
			$v2			 = explode(" ", $v1);
			$mainSource	 = $v2[1];

			$v3			 = explode("medium :", $v1);
			$mainmedium	 = trim($v3[1]);

			$ustID		 = $row['ust_id'];
			$updateSql	 = "UPDATE `users_source_tracking` SET `ust_medium` =  '$mainmedium',`ust_source`= '$mainSource'  WHERE `users_source_tracking`.`ust_id` = $ustID";
			DBUtil::execute($updateSql);
		}
	}

	public function actionLowestCabModel()
	{
		$check = Filter::checkProcess("booking userCheckRate");
		if (!$check)
		{
			return;
		}
		$sql		 = 'SELECT
							bkg_id
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
							AND bkg_follow_up_status=0
                            AND bkg_user_id IS NOT NULL
							AND bkg_ref_booking_id IS NULL
							AND bkg_create_date BETWEEN DATE_SUB(NOW(),INTERVAL 2 HOUR) AND DATE_SUB(NOW(),INTERVAL 1 HOUR)
						GROUP BY bkg_contact_no,bkg_from_city_id ORDER BY bkg_id DESC LIMIT 0,1 ';
		$queryObject = DBUtil::query($sql, DBUtil::SDB());
		foreach ($queryObject as $value)
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
			BookingTemp::notifyUserCheckRate($value['bkg_id'], $cabType, $fare);
		}
	}

	public function actionGetprimary()
	{
		$refId	 = Yii::app()->request->getParam('refId', 0);
		$refType = Yii::app()->request->getParam('refType', 0);

		$resPrimary = Contact::getRelatedPrimaryListByType($refId, $refType, true);
		echo $this->generateTable($resPrimary);

		$res = Contact::getRelatedPrimaryListByType($refId, $refType, false);
		echo "<div>Related Consumer Data" . $this->generateTable($res['consumerData']) . "</div>";
		echo "<div>Related Vendor Data " . $this->generateTable($res['vendorData']) . "</div>";
		echo "<div>Related Driver Data" . $this->generateTable($res['driverData']) . "</div>";

		exit;
	}

	public function count_dimension($Array, $count = 0)
	{
		return(is_array($Array)) ? $this->count_dimension(current($Array), ++$count) : $count;
	}

	public function generateTable($resData)
	{
		if (sizeof($resData) == 0)
		{
			return 'No records found';
		}
		$dim = $this->count_dimension($resData);

		if ($dim == 1)
		{
			$res[] = $resData;
		}
		else
		{
			$res = $resData;
		}

		$str		 = '';
		$str		 .= '<table border="1" style="border-collapse:collapse;margin-top:10px;margin-bottom:10px">';
		$activeVar	 = '';
		foreach ($res as $k => $rowSet)
		{


			if ($k == 0)
			{

				$str .= '<tr>';
				foreach ($rowSet as $th => $data)
				{
					if (strstr($th, '_active'))
					{
						$activeVar = $th;
					}

					$str .= '<th style="padding:5px; white-space:nowrap " >';
					$str .= str_replace('adt_', '', $th);
					$str .= '</th>';
				}
				$str .= '</tr >';
			}
			$bgColor = 'background:#ccffcc;';

			if (isset($rowSet['selfWeight']) && $rowSet['selfWeight'] == 0)
			{
				$bgColor = 'background:#ffffee;';
			}
			if (isset($rowSet['contactWeight']) && $rowSet['contactWeight'] == 0)
			{
				$bgColor = 'background:#ffeeee;';
			}

			if ($activeVar != '' && $rowSet[$activeVar] != 1)
			{
				$bgColor = 'background:#ffaaaa;';
			}
			$str .= "<tr style='border-bottom:1;border-top:0;{$bgColor}'>";
			foreach ($rowSet as $data)
			{
				$rightAlign	 = (is_numeric($data) == 1) ? ';text-align:right' : '';
				$str		 .= '<td style="padding:5px; white-space:nowrap ' . $rightAlign . ';">';
				$str		 .= $data;
				$str		 .= '</td>';
			}
			$str .= '</tr>';
		}
		$str .= '</table>';
		return $str;
	}

	public function actionGenerateGZQR()
	{
		$embededLink = "http://www.aaocab.com/?s=sticker";
		$filename	 = "aaocab_QR";
		$folderId	 = "1";

		echo "XX == " . $dirFileName = QrCode::generateCode($embededLink, $filename, $folderId);
	}

	public function actionNotifyGnow()
	{
		$tripId	 = 3991191;
		$notify	 = BookingCab::processPendingBulkNotifications($tripId, true);
	}

	public function actionInquiryLastTravelReminder()
	{
		$limit				 = Yii::app()->request->getParam('limit', 1);
		BookingTemp::NotificationInquiryLastTravelReminder( 0, null, $limit);
	}

	public function actionCheckEle()
	{
//		$bkgId	 = Yii::app()->request->getParam('bkgId', '') . Yii::app()->request->getParam('bkgid', '');
//		$bkgId	 = ($bkgId > 0) ? $bkgId : '3899501';
//$recordsets	 = Booking::getBookingsToAssignForEverestFleet($bkgId);
//echo $recordsets->getRowCount();
		$returnSet = BookingCab::assignToEverestFleet();
		echo $returnSet->getMessage();
//		BookingCab::assignToEverestFleet($bkgId); 

		exit;

		$evFleetVndId = Config::get('everestfleet.delhi.vendor.id');  //73552;

		$maxAssignedCount	 = 10;
		/** @var Booking $model */
		$model				 = Booking::model()->findByPk($bkgId);
		$isElegible			 = $model->checkVendorEligiblity($evFleetVndId, $maxAssignedCount);

		echo ($isElegible) ? 'Elegible' : 'Not Elegible';
		exit;
	}
	public function actionQuoteExpiredReminder()
	{
		Booking::NotificationQuoteExpired();
	}

	public function actionQuoteExpiringReminder()
	{
		Booking::NotificationQuoteExpiring();
	}

}
