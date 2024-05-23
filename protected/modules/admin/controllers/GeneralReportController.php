<?php

class GeneralReportController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

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
		return [
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('escalation'),
				'roles'		 => array('bookingEscalationList'),
			),
			['allow', 'actions' => ['inventoryShortage', 'zoneCsv'], 'roles' => ['Vendor']],
			['allow', 'actions' => ['promoReport'], 'roles' => ['PromoReport']],
			['allow', 'actions' => ['agentTrackingDetails', 'trackingView', 'patTrackingDetails'], 'roles' => ['PartnerTrackingDetails']],
			['allow', 'actions' => ['partnerWiseCountBooking'], 'roles' => ['partnerReports']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('dailyServiceDelivery', 'louRequired', 'escalation', 'driverAppUsage', 'assignmentSummary',
					'driverAppNotUsed', 'blockedVendor', 'profitability', 'zoneWiseCountBooking', 'vendorWiseCountBooking',
					'manualAssignmentCount', 'assignmentReport', 'ratingReport', 'stickyCarCount', 'stickyVendorCount', 'tierCount',
					'directAcceptReport', 'partnerWiseCountBooking', 'processedPayments', 'referralBonous', 'promoReport', 'latepickup',
					'latepickup_v0', 'ScqReport', 'VendorCancellation', 'cbrDetailsReport', 'csrLeadPerformanceReport', 'cbrCloseReport',
					'cbrCombineCloseReport', 'serviceRequests', 'penaltyReport', 'bookingtrackdetails', 'serviceRequestsOwn', 'zonesupplydensity',
					'zonesupplydensityvendorslist', 'ProfitabilityZone', 'servicePerformance', 'zeroInventory', 'driverappusagereport',
					'marginPercentageReport', 'VendorUsageReport', 'RegionVendorwiseDriverAppusage', 'DailyLoss', 'processbooking',
					'bookingReport', 'AttendanceReport', 'AttendanceDetailsReport', 'vendorLockedPayment', 'driverBonus', 'accountingFlagClosedReport',
					'DzppReport', 'DzppDetailReport', 'csrLeadConversionReport', 'csrPerformanceReport', 'paymentSummaryReport', 'zoneProfitability',
					'dispatchPerformance', 'appNotRequired', 'penaltySummary', 'pickup', 'dnr', 'dailyConfirmation',
					'showBooking1', 'showBooking', 'operatorTrackingView', 'ViewBookingIds'
				),
				'roles'		 => array('GeneralReport'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('topDemandRoutes'),
				'roles'		 => array('opReports'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('Tripsinloss'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		];
	}

	public function actionEscalation()
	{
		$this->pageTitle = "Escalation Report";
		$bkgTrail		 = new BookingTrail();
		$request		 = Yii::app()->request;
		if ($request->getParam('BookingTrail'))
		{
			$bkgtrailarr = $request->getParam('BookingTrail');
			$teams		 = implode(",", $bkgtrailarr['btr_escalation_assigned_team']);
		}
		$dataProvider	 = BookingSub::model()->getEscalationlist($teams);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('escalation_list', array('dataProvider' => $dataProvider, 'bkgTrail' => $bkgTrail, 'teams' => $teams), false, $outputJs);
	}

	public function actionDailyServiceDelivery()
	{
		$this->pageTitle = "Daily Service Delivery";
		$model			 = new Booking();
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->bkg_create_date1 = $arr['bkg_create_date1'];
			$model->bkg_create_date2 = $arr['bkg_create_date2'];
			$cdate1					 = $model->bkg_create_date1;
			$cdate2					 = $model->bkg_create_date2;
			$cdate1					 = $model->bkg_create_date1;
			$cdate2					 = $model->bkg_create_date2;
			if ($_REQUEST['Booking']['bkg_create_date1'] == '' && $_REQUEST['Booking']['bkg_create_date2'] == '')
			{
				$model->bkg_create_date1 = date('Y-m-d');
				$model->bkg_create_date2 = date('Y-m-d');
				$cdate1					 = $model->bkg_create_date1;
				$cdate2					 = $model->bkg_create_date2;
			}
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d');
			$model->bkg_create_date2 = date('Y-m-d');
			$cdate1					 = $model->bkg_create_date1;
			$cdate2					 = $model->bkg_create_date2;
		}
		$no_qtcreate = BookingSub::model()->quoteMeasure($cdate1, $cdate2, 1);
		$no_qtstate	 = BookingSub::model()->quoteMeasure($cdate1, $cdate2, 0);

		$range_qt = BookingSub::model()->quoteMeasureByRange($cdate1, $cdate2);

		$no_create	 = BookingSub::model()->createMeasure($cdate1, $cdate2);
		$range_crt	 = BookingSub::model()->createMeasureByRange($cdate1, $cdate2);

		$no_cancel			 = BookingSub::model()->cancelMeasure($cdate1, $cdate2);
		$no_autoAssignByType = BookingSub::model()->countAssignmentByType($cdate1, $cdate2);

		$escalations	 = BookingSub::model()->countEscalations($cdate1, $cdate2);
		$accountsFlag	 = BookingSub::model()->countAccountingFlag($cdate1, $cdate2);

		$pickupCancel = BookingSub::model()->countPickupCancel($cdate1, $cdate2);

		$pickupTot = BookingSub::model()->counttotPickup($cdate1, $cdate2);

		$method = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('daily_measurement',
				array('model'					 => $model,
					'fromDateFormated'		 => $cdate1,
					'toDateFormated'		 => $cdate2,
					'qtstate'				 => $no_qtstate,
					'qtcreate'				 => $no_qtcreate,
					'qtrange'				 => $range_qt,
					'crtCount'				 => $no_create,
					'crtrange'				 => $range_crt,
					'crtcancel'				 => $no_cancel,
					'crtautoAssignByType'	 => $no_autoAssignByType,
					'escalations'			 => $escalations,
					'accounts'				 => $accountsFlag,
					'pickupCancel'			 => $pickupCancel,
					'pickupTot'				 => $pickupTot,
				),
				false, $outputJs);
	}

	public function actionInventoryShortage()
	{
		$this->pageTitle	 = "Inventory Shortage Report";
		$model				 = new Booking();
		$model->zero_percent = 1;
		if (isset($_REQUEST['Booking']))
		{
			$arr						 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1	 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $arr['bkg_pickup_date2'];
			$model->bkg_create_date1	 = $arr['bkg_create_date1'];
			$model->bkg_create_date2	 = $arr['bkg_create_date2'];
			$model->bkg_cancel_id		 = $arr['bkg_cancel_id'];
			$model->dem_sup_misfireCount = $arr['dem_sup_misfireCount'];
			$model->total_completedCount = $arr['total_completedCount'];
			$model->zero_percent		 = ($arr['zero_percent'] == 'on') ? 1 : 0;
		}
		if (!$model->dem_sup_misfireCount)
		{
			$model->dem_sup_misfireCount = 10;
		}
		if ($arr['bkg_create_date1'] == '')
		{
			$model->bkg_create_date1 = "";
			$model->bkg_create_date2 = "";
		}
		if ($arr['bkg_pickup_date1'] == '')
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime("first day of this month"));
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime("last day of this month"));
		}
		if ($arr['bkg_cancel_id'] == '')
		{
			$model->bkg_cancel_id = '9,17';
		}

		$dataProvider		 = BookingSub::model()->getInventorySortage($model);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$getInventoryZone	 = InventoryRequest::model()->getZoneCount();
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('inventory_sortage', array('model' => $model, 'dataProvider' => $dataProvider, 'countZone' => $getInventoryZone), false, $outputJs);
	}

	public function actionZoneCsv()
	{
		$data	 = InventoryRequest::model()->getZoneListByNMI();
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"NMIZones_" . date('Ymdhis') . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle	 = fopen("php://output", 'w');
		fputcsv($handle, array("Name"));
		foreach ($data as $d)
		{
			$rowArray				 = array();
			$rowArray['zon_name']	 = $d['zon_name'];
			$row1					 = array_values($rowArray);
			fputcsv($handle, $row1);
		}
		fclose($handle);
		exit;
	}

	public function actionDriverAppUsage()
	{
		$this->pageTitle = "Driver app compliance report";
		$model			 = new Booking();
		$dmodel			 = new Drivers();
		if (isset($_REQUEST['Booking']))
		{
			$arr						 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1	 = $date1						 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $date2						 = $arr['bkg_pickup_date2'];
			$model->bkg_drv_app_filter	 = $filter						 = $arr['bkg_drv_app_filter'];
			$model->sourcezone			 = $zones						 = implode(",", $arr['sourcezone']);
			$model->bkg_region			 = $regions					 = implode(",", $arr['bkg_region']);
		}
		if (isset($_REQUEST['Drivers']))
		{
			$arr1					 = Yii::app()->request->getParam('Drivers');
			$dmodel->drv_vendor_id	 = $arr1['drv_vendor_id'];
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			$filter			 = Yii::app()->request->getParam('export_filter1');
			$vndID			 = Yii::app()->request->getParam('export_vendor');
			$zonIDs			 = Yii::app()->request->getParam('export_zone');
			$sttZonIDs		 = Yii::app()->request->getParam('export_region');

			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"DriverAppUsage_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "DriverAppUsage_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = Drivers::model()->driverAppUsage($fromDate, $toDate, $filter, $vndID, $zonIDs, $sttZonIDs);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'Zone', 'Driver Name', 'Driver Code', 'Driver Phone', 'Booking Count', 'App Usage Count']);
				foreach ($rows as $row)
				{

					$rowArray					 = array();
					$rowArray['Region']			 = $row['Region'];
					$rowArray['city_zones']		 = $row['city_zones'];
					$rowArray['drv_name']		 = $row['drv_name'];
					$rowArray['drv_code']		 = $row['drv_code'];
					$rowArray['phn_phone_no']	 = $row['phn_phone_no'];
					$rowArray['booking_count']	 = $row['booking_count'];
					$rowArray['app_used_count']	 = $row['app_used_count'];

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$provider		 = Drivers::model()->getDriverAppUsage($date1, $date2, $filter, $dmodel->drv_vendor_id, $zones, $regions);
		$dataProvider	 = $provider[0];
		$count			 = $provider[1];
		if ($filter != "")
		{
			$count = DriverStats::model()->getPercentageDriverAppUsage($date1, $date2);
		}
		$startCount = DriverStats::model()->getStartStopCountApp($date1, $date2);
		$this->render('report_driver_app_usage', array('dataProvider' => $dataProvider, 'count' => $count, 'startCount' => $startCount, 'model' => $model, 'dmodel' => $dmodel));
	}

	public function actionDriverAppNotUsed()
	{
		$this->pageTitle = "Driver app usage drilleddown report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;

		if ($request->getParam('Booking'))
		{
			$arr1					 = $request->getParam('Booking');
			$date1					 = $model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'] != null ? $arr1['bkg_pickup_date1'] : date("Y-m-d", strtotime("-1 days"));
			$date2					 = $model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'] != null ? $arr1['bkg_pickup_date2'] : date("Y-m-d", strtotime("-1 days"));
			$appnotused				 = (Yii::app()->request->getParam('not_app_used') == 1) ? 1 : 0;
		}
		else
		{
			$date1					 = $model->bkg_pickup_date1 = date("Y-m-d", strtotime("-1 days"));
			$date2					 = $model->bkg_pickup_date2 = date("Y-m-d", strtotime("-1 days"));
			$appnotused				 = 1;
		}
		$provider		 = Drivers::model()->getDriverAppNotUsed($date1, $date2, $appnotused);
		$dataProvider	 = $provider[0];
		$count			 = $provider[1];
		$datasummary	 = Drivers::model()->getDriverAppNotUsedSummary($date1, $date2, $appnotused);
		$this->render('report_driver_app_not_usage', array('dataProvider' => $dataProvider, 'count' => $count, 'model' => $model, 'datasummary' => $datasummary, 'appnotused' => $appnotused));
	}

	public function actionBlockedVendor()
	{
		$this->pageTitle = "Blocked Vendors - Report";
		$model			 = new VendorsLog();
		$condition		 = "";
		if (isset($_REQUEST['VendorsLog']))
		{
			$arr = Yii::app()->request->getParam('VendorsLog');

			$date1					 = $arr['vlg_create_date1'];
			$date2					 = $arr['vlg_create_date2'];
			$model->vlg_create_date1 = $date1;
			$model->vlg_create_date2 = $date2;
			$condition				 = ($date1 != '' && $date2 != '') ? " vlg1.vlg_created BETWEEN '" . $date1 . "' AND '" . $date2 . "'" : '';
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new VendorsLog();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"BlockedVendor_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "BlockedVendors_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = Vendors::model()->blockedVendorExportList($fromDate, $toDate);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'Vendor Name', 'Joining Date', 'Vendor Rating', 'Blocked Date', 'Remarks']);
				foreach ($rows as $row)
				{

					$rowArray					 = array();
					$rowArray['Region']			 = $row['Region'];
					$rowArray['vnd_name']		 = $row['vnd_name'];
					$rowArray['joinDate']		 = $row['joinDate'];
					$rowArray['vnd_avg_rating']	 = $row['vnd_avg_rating'];
					$rowArray['blocked_date']	 = $row['blocked_date'];
					$rowArray['vlg_desc']		 = $row['vlg_desc'];
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = Vendors::model()->getBlockedVendorList($condition);
		$this->render('report_blocked_vendors', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionProfitability()
	{
		$this->pageTitle		 = "Profitability - Report";
		$model					 = new Booking();
		$condition				 = "";
		$model->bkg_pickup_date1 = date('Y-m-d', strtotime('-30 days')) . " 00:00:00";
		$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		$request				 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr						 = $request->getParam('Booking');
			$date1						 = $arr['bkg_pickup_date1'];
			$pickupDate1				 = new DateTime($date1);
			$model->bkg_pickup_date1	 = $pickupDate1->format('Y-m-d') . " 00:00:00";
			$date2						 = $arr['bkg_pickup_date2'];
			$pickupDate2				 = new DateTime($date2);
			$model->bkg_pickup_date2	 = $pickupDate2->format('Y-m-d') . " 23:59:59";
			$condition					 = ($model->bkg_pickup_date1 != '' && $model->bkg_pickup_date2 != '') ? "  AND  ( bkg.bkg_pickup_date BETWEEN '" . $model->bkg_pickup_date1 . "' AND '" . $model->bkg_pickup_date2 . "' ) " : '';
			$model->bkg_vehicle_type_id	 = $arr['bkg_vehicle_type_id'];
			$model->bkg_region			 = $arr['bkg_region'];
			$model->bkgtypes			 = $arr['bkgtypes'];
			if (count($arr['bkgtypes']) > 0)
			{
				$bkgtypes	 = implode(',', $model->bkgtypes);
				$condition	 .= " AND bkg_booking_type IN ($bkgtypes)";
			}
			if (count($arr['bkg_vehicle_type_id']) > 0)
			{
				$vtype		 = implode(",", $model->bkg_vehicle_type_id);
				$condition	 .= " AND bkg.bkg_vehicle_type_id IN ($vtype) ";
			}
			if ($arr['bkg_region'] > 0)
			{
				$condition .= " AND stt.stt_zone = " . $arr['bkg_region'];
			}
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			$vtype			 = Yii::app()->request->getParam('bkg_vehicle_type_id');
			$btype			 = Yii::app()->request->getParam('bkg_booking_type');
			$region			 = Yii::app()->request->getParam('export_bkg_region');
			$cond			 = ($fromDate != '' && $toDate != '') ? " AND ( bkg.bkg_pickup_date BETWEEN '" . $fromDate . "' AND '" . $toDate . "' )" : '';
			$cond			 .= ($vtype != '') ? " AND bkg.bkg_vehicle_type_id IN ($vtype) " : '';
			$cond			 .= ($btype != '') ? " AND bkg_booking_type IN ($btype)" : '';
			$cond			 .= ($region != '') ? " AND stt.stt_zone = $region " : '';
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"ZoneProfitability_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "ZoneProfitability_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = BookingInvoice::model()->getProfitabilityByZone($cond);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'From Zone', 'To Zone', 'Count of Completed Booking', 'Profit %']);
				foreach ($rows as $row)
				{

					$rowArray					 = array();
					$rowArray['Region']			 = $row['Region'];
					$rowArray['fromZone']		 = $row['fromZone'];
					$rowArray['toZone']			 = $row['toZone'];
					$rowArray['CountBooking']	 = $row['CountBooking'];
					$rowArray['Profit']			 = round($row['Profit'], 2);
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider	 = BookingInvoice::model()->getProfitabilityByZone($condition, 'Command');
		$cabProfit		 = BookingInvoice::model()->getProfitabilityByCabType($condition);
		$serviceTier	 = BookingInvoice::model()->getProfitabilityByServiceTier($condition);
		$this->render('zone_profitability', array('dataProvider' => $dataProvider, 'cabProfit' => $cabProfit, 'serviceTier' => $serviceTier, 'model' => $model));
	}

	public function actionProfitabilityZone()
	{
		$this->pageTitle		 = "Profitability - Report";
		$model					 = new Booking();
		$condition				 = "";
		$model->bkg_pickup_date1 = date('Y-m-d', strtotime('-3 days')) . " 00:00:00";
		$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		$request				 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr						 = $request->getParam('Booking');
			$date1						 = $arr['bkg_pickup_date1'];
			$pickupDate1				 = new DateTime($date1);
			$model->bkg_pickup_date1	 = $pickupDate1->format('Y-m-d') . " 00:00:00";
			$date2						 = $arr['bkg_pickup_date2'];
			$pickupDate2				 = new DateTime($date2);
			$model->bkg_pickup_date2	 = $pickupDate2->format('Y-m-d') . " 23:59:59";
			$condition					 = ($model->bkg_pickup_date1 != '' && $model->bkg_pickup_date2 != '') ? "  AND  ( bkg_pickup_date BETWEEN '" . $model->bkg_pickup_date1 . "' AND '" . $model->bkg_pickup_date2 . "' ) " : '';
			$model->bkg_service_class	 = $arr['bkg_service_class'];
			$model->bkg_region			 = $arr['bkg_region'];
			if (count($arr['bkg_service_class']) > 0)
			{
				$vtype		 = implode(",", $model->bkg_service_class);
				$condition	 .= " AND scc_id IN ($vtype) ";
			}
			if ($arr['bkg_region'] > 0)
			{
				$condition .= " AND stt.stt_zone = " . $arr['bkg_region'];
			}
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			$vtype			 = Yii::app()->request->getParam('bkg_service_class');
			$region			 = Yii::app()->request->getParam('export_bkg_region');
			$cond			 = ($fromDate != '' && $toDate != '') ? " AND ( bkg_pickup_date BETWEEN '" . $fromDate . "' AND '" . $toDate . "' )" : '';
			$cond			 .= ($vtype != '') ? " AND scc_id IN ($vtype) " : '';
			$cond			 .= ($region != '') ? " AND stt.stt_zone = $region " : '';
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"ZoneProfitability_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "ZoneProfitability_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = BookingInvoice::model()->getProfitabilityZone($cond);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, [
					'Region',
					'State',
					'Source Zone',
					'Quoted Margin(%)',
					'Realized Margin(%)',
					'Top of Funnel <br> (Created BKGs)',
					'Converted <br> (Active + Complete + CXL)',
					'Success <br> (Completed)',
					'Fallouts <br> (CXL)'
				]);
				foreach ($rows as $row)
				{
					$rowArray					 = array();
					$rowArray['region']			 = $row['region'];
					$rowArray['stateName']		 = $row['stateName'];
					$rowArray['sourceZone']		 = $row['sourceZone'];
					$rowArray['quotedMargin']	 = $row['bookingAmount'] == 0 ? 0 : round((($row['quote_amount'] / $row['bookingAmount']) * 100), 2);
					$rowArray['realizedMargin']	 = $row['bookingAmount'] == 0 ? 0 : round((($row['gozoAmount'] / $row['bookingAmount']) * 100), 2);
					$rowArray['totalCreated']	 = $row['CountBooking'];
					$rowArray['totalConverted']	 = $row['totalConverted'];
					$rowArray['totalCompleted']	 = $row['totalCompleted'];
					$rowArray['totalCancelled']	 = $row['totalCancelled'];
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = BookingInvoice::model()->getProfitabilityZone($condition, 'Command');
		$this->render('profitabilityzone', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionZoneWiseCountBooking()
	{
		$this->pageTitle = "Zone Wise Completed Booking - Report";
		$model			 = new Booking();
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1 = $date1					 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $date2					 = $arr['bkg_pickup_date2'];
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"CompletedBookingZone_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "CompletedBookingZone_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = BookingSub::model()->getZonewiseBookingCount($fromDate, $toDate);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'Source Zone', 'Count of Completed Booking(90 days)', 'Count of Completed Booking(180 days)']);
				foreach ($rows as $row)
				{

					$rowArray				 = array();
					$rowArray['Region']		 = $row['Region'];
					$rowArray['Source_Zone'] = $row['Source_Zone'];
					$rowArray['Count_90']	 = $row['Count_90'];
					$rowArray['Count_180']	 = $row['Count_180'];

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = BookingSub::model()->getZonewiseBookingCount($date1, $date2, 'Command');
		$this->render('zone_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionVendorWiseCountBooking()
	{
		$this->pageTitle = "Vendor Wise Completed Booking - Report";
		$model			 = new Booking();
		$condition		 = "";
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1 = $date1					 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $date2					 = $arr['bkg_pickup_date2'];
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"CompletedBookingZone_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "CompletedBookingZone_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = BookingSub::model()->getVendorWiseBookingCount($fromDate, $toDate);
				$zones	 = States::model()->getRegionByZoneId();
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'Home Zone', 'Vendor Name', 'Vendor Overall Rating', 'Count of Completed Booking(90 days)', 'Count of Completed Booking(180 days)']);
				foreach ($rows as $row)
				{
					$zonid								 = array_search($row['vnp_home_zone'], array_column($zones, 'zon_id'));
					$rowArray							 = array();
					$rowArray['Region']					 = States::model()->findRegionName($zones[$zonid]['stt_zone'][0]);
					$rowArray['vnp_home_zone']			 = $zones[$zonid]['zon_name'];
					$rowArray['vnd_name']				 = $row['vnd_name'];
					$rowArray['vrs_vnd_overall_rating']	 = $row['vrs_vnd_overall_rating'];
					$rowArray['Count_90']				 = $row['Count_90'];
					$rowArray['Count_180']				 = $row['Count_180'];

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = BookingSub::model()->getVendorWiseBookingCount($date1, $date2, 'Command');
		$this->render('vendor_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionManualAssignmentCount()
	{
		$this->pageTitle = "CSR Manual Assignment - Report";
		$model			 = new Booking();
		$reportReady	 = false;
		if (isset($_REQUEST['Booking']))
		{
			$arr1					 = Yii::app()->request->getParam('Booking');
			$reportReady			 = ($arr1['bkg_pickup_date1'] != '' && $arr1['bkg_pickup_date2'] != '') ? true : false;
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'];
//	
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{
			$fromDate	 = Yii::app()->request->getParam('export_from1');
			$toDate		 = Yii::app()->request->getParam('export_to1');
			$result		 = BookingPref::model()->getVendorCsrManualAssignment($fromDate, $toDate);
			$arr		 = $result[0];
			$rowCsr		 = $result[1];
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorCsrUsage_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle		 = fopen("php://output", 'w');
			$csrArr[0]	 = "VendorName/CSR";
			foreach ($rowCsr as $csr)
			{
				$csrArr[] = $csr['csr_name'];
			}
			fputcsv($handle, $csrArr);

			$vndIdArr = array();
			foreach ($arr as $vnd)
			{
				$vndIdArr[] = $vnd['vnd_id'];
			}
			for ($i = 0; $i < count($arr); $i++)
			{
				$rowArray				 = array();
				$rowArray['VendorName']	 = $arr[$i]['vnd_name'];
				$vendorRange			 = $this->findFirstAndLast($vndIdArr, count($arr), $arr[$i]['vnd_id']);
				$vendorRangeArr			 = explode(",", $vendorRange);
				$zero					 = array_fill(0, count($rowCsr), '0');
				for ($j = $vendorRangeArr[0]; $j <= $vendorRangeArr[1]; $j++)
				{
					$csrCountBooking = $arr[$j]['count_booking'];
					$csrAdmId		 = $arr[$j]['adm_id'];
					$CsrIndex		 = array_search($csrAdmId, array_column($rowCsr, 'adm_id'));
					$zero[$CsrIndex] = $csrCountBooking;
				}
				if ($vendorRangeArr[0] != $vendorRangeArr[1])
				{
					$i = $vendorRangeArr[1];
				}
				fputcsv($handle, array_merge($rowArray, $zero));
			}
			$reportReady = false;
			fclose($handle);
			exit;
		}
		$this->render('manual_assignment_count', array('model' => $model, 'reportReady' => $reportReady));
	}

	public function actionAssignmentReport()
	{
		$this->pageTitle = "Assignment Stats - Report";
		$model			 = new Booking();
		$fromDate		 = date("Y-m-d") . ' 00:00:00';
		$toDate			 = date("Y-m-d") . ' 23:59:59';
		if (isset($_REQUEST['Booking']))
		{
			$arr1						 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date_date = $arr1['bkg_pickup_date_date'];
			$model->bkg_pickup_date		 = DateTimeFormat::DatePickerToDate($model->bkg_pickup_date_date);
			if ($model->bkg_pickup_date > date("Y-m-d"))
			{
				$fromDate	 = date("Y-m-d") . ' 00:00:00';
				$toDate		 = date("Y-m-d") . ' 23:59:59';
			}
			else
			{
				$fromDate	 = $model->bkg_pickup_date . ' 00:00:00';
				$toDate		 = $model->bkg_pickup_date . ' 23:59:59';
			}
		}
		$bkgassigned = BookingTrail::getAssignmentStats($fromDate, $toDate);
		$this->render('assignment_report', array('model' => $model, 'bkgassigned' => $bkgassigned));
	}

	public function actionRatingReport()
	{
		$this->pageTitle		 = "Rating Stats - Report";
		$model					 = new Booking();
		$model->bkg_pickup_date1 = date("Y-m-d", strtotime("-1 years")) . ' 00:00:00';
		$model->bkg_pickup_date2 = date("Y-m-d") . ' 23:59:59';
		if (isset($_REQUEST['Booking']))
		{
			$arr1					 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'];
		}
		$dataProvider = Ratings::model()->getRatingsByBookingType($model->bkg_pickup_date1, $model->bkg_pickup_date2);
		$this->render('rating_report', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionStickyCarCount()
	{
		$this->pageTitle = "Sticky Car Count - Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr1					 = $request->getParam('Booking');
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'] != null ? $arr1['bkg_pickup_date1'] : date("Y-m-d", strtotime("-7 days")) . ' 00:00:00';
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'] != null ? $arr1['bkg_pickup_date2'] : date("Y-m-d") . ' 23:59:59';
		}
		else
		{
			$model->bkg_pickup_date1 = date("Y-m-d", strtotime("-7 days")) . ' 00:00:00';
			$model->bkg_pickup_date2 = date("Y-m-d") . ' 23:59:59';
		}
		$stickyCarScore = VehicleStats::model()->getStickyScoreCars($model->bkg_pickup_date1, $model->bkg_pickup_date2);
		$this->render('sticky_car_count_report', array('model' => $model, 'stickyCarScore' => $stickyCarScore));
	}

	public function actionStickyVendorCount()
	{
		$this->pageTitle = "Sticky Vendor Count - Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr1					 = $request->getParam('Booking');
			$model->sourcezone		 = $arr1['sourcezone'];
			$zones					 = implode(",", $arr1['sourcezone']);
			$model->bkg_region		 = $arr1['bkg_region'];
			$regions				 = implode(",", $arr1['bkg_region']);
			$model->bkg_state		 = $arr1['bkg_state'];
			$states					 = implode(",", $arr1['bkg_state']);
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'] != null ? $arr1['bkg_pickup_date1'] : date("Y-m-d", strtotime("-7 days")) . ' 00:00:00';
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'] != null ? $arr1['bkg_pickup_date2'] : date("Y-m-d") . ' 23:59:59';
		}
		else
		{
			$model->bkg_pickup_date1 = date("Y-m-d", strtotime("-7 days")) . ' 00:00:00';
			$model->bkg_pickup_date2 = date("Y-m-d") . ' 23:59:59';
		}
		$dataProvider = VendorStats::model()->getStickyCount($model->bkg_pickup_date1, $model->bkg_pickup_date2, $zones, $regions, $states);
		$this->render('sticky_vendor_count_report', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionTierCount()
	{
		$this->pageTitle = "Vendor Count List By Tier";
		$request		 = Yii::app()->request;
		$vendorPref		 = $request->getParam("VendorPref", false);
		$vendors		 = $request->getParam('Vendors', false);

		$vnpModel			 = new VendorPref('search');
		$vnpModel->vnpVnd	 = new Vendors('search');

		if ($vendorPref !== false)
		{
			$vnpModel->zonRegion	 = $vendorPref['zonRegion'];
			$vnpModel->attributes	 = $vendorPref;
		}

		$dataProvider = $vnpModel->getVehicleTierCountByZone(DBUtil::ReturnType_Provider);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorcounttierlist', array('dataProvider' => $dataProvider, 'vnpmodel' => $vnpModel));
	}

	public function actionDirectAcceptReport()
	{
		$this->pageTitle = "Accepted Bookings By Vendors - Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr1					 = $request->getParam('Booking');
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'] != null ? $arr1['bkg_pickup_date1'] : date("Y-m-d") . ' 00:00:00';
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'] != null ? $arr1['bkg_pickup_date2'] : date("Y-m-d") . ' 23:59:59';
		}
		else
		{
			$model->bkg_pickup_date1 = date("Y-m-d") . ' 00:00:00';
			$model->bkg_pickup_date2 = date("Y-m-d") . ' 23:59:59';
		}
		$dataProvider = VendorStats::model()->getAcceptedByVendorList($model->bkg_pickup_date1, $model->bkg_pickup_date2);
		$this->render('report_direct_accepted', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function findFirstAndLast($arr, $n, $x)
	{

		$first	 = -1;
		$last	 = -1;
		for ($i = 0; $i < $n; $i++)
		{
			if ($x != $arr[$i])
				continue;
			if ($first == -1)
				$first	 = $i;
			$last	 = $i;
		}
		if ($first != -1)
			return $first . "," . $last;
		else
			return -1;
	}

	public function actionPartnerWiseCountBooking()
	{

		$this->redirect("/report/booking/partnerWiseCountBooking");
		exit();

//		$this->pageTitle = "Partner Booking Count - Report(B2B Other)";
//		$model			 = new Booking();
//		$date1			 = date("Y-m-d");
//		$date2			 = date("Y-m-d");
//		$request		 = Yii::app()->request;
//		$agentId		 = 0;
//		if ($request->getParam('Booking'))
//		{
//			$arr	 = $request->getParam('Booking');
//			$date1	 = $arr['bkg_create_date1'];
//			$date2	 = $arr['bkg_create_date2'];
//			$agentId = $arr['bkg_agent_id'];
//		}
//		$model->bkg_create_date1 = $date1;
//		$model->bkg_create_date2 = $date2;
//		$model->bkg_agent_id	 = $agentId > 0 ? $agentId : "";
//
//		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
//		{
//
//			$arr2			 = array();
//			$adminWrapper	 = new Booking();
//			$fromDate		 = Yii::app()->request->getParam('export_from1');
//			$toDate			 = Yii::app()->request->getParam('export_to1');
//			$export_agent_id = Yii::app()->request->getParam('export_agent_id');
//			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
//			{
//				header('Content-type: text/csv');
//				header("Content-Disposition: attachment; filename=\"PartnerWiseCountBooking_" . date('Ymdhis') . ".csv\"");
//				header("Pragma: no-cache");
//				header("Expires: 0");
//				$filename	 = "PartnerWiseCountBooking_" . date('YmdHi') . ".csv";
//				$rows		 = BookingSub::model()->getPartnerWiseCountBookingReport($fromDate, $toDate, $export_agent_id, true);
//				$handle		 = fopen("php://output", 'w');				
//				fputcsv($handle, ['Partner Name', 'Booking Count(Local)', 'Booking Count(OutStation)', 'Served Booking Count', 'Total Amount', 'Net Base Amount', 'Gozo Amount', 'GROSS MARGIN (%)(Net Base Amt)', 'GROSS MARGIN (%)(Total Amt)', 'Receivable', 'Payable', 'Wallet Balance', 'Last Booking Received Date', 'Last Payment Received Date', 'Booking Ids']);
//				foreach ($rows as $row)
//				{
//					$agentId							 = $row['bkg_agent_id'];
//					$lastPaymentReceivedDate			 = AccountTransactions::getLastPaymentReceivedDate($agentId);
//					$rowArray							 = array();
//					$rowArray['PartnerName']			 = $row['partnername'];
//					$rowArray['BookingCountLocal']		 = $row['total_book_local'];
//					$rowArray['BookingCountOutStation']	 = $row['total_book_outstation'];
//					$rowArray['TotalServedBookingCount'] = $row['total_served_booking'];
//					$rowArray['TotalAmount']			 = $row['totalamount'];
//					$rowArray['NetBaseAmount']			 = $row['net_base_amount'];
//					$rowArray['GozoAmount']				 = $row['gozoamount'];
//					$rowArray['GrossMarginNetBaseAmt']	 = $row['netgrossmargin'];
//					$rowArray['GrossMarginTotalAmt']	 = $row['totalgrossmargin'];
//					$rowArray['Receivable']				 = ($row['accountBalance'] > 0) ? number_format($row['accountBalance']) : 0;
//					$rowArray['Payable']				 = ($row['accountBalance'] < 0) ? number_format(-1 * $row['accountBalance']) : 0;
//					$rowArray['WalletBalance']			 = number_format($row['pts_wallet_balance']);
//					$rowArray['LastBookingReceivedDate'] = date('d-m-Y', strtotime($row['lastBookingReceivedDate']));
//					$rowArray['LastPaymentReceivedDate'] = ($lastPaymentReceivedDate != '') ? date('d-m-Y', strtotime($lastPaymentReceivedDate)) : '';
//					$rowArray['BookingIds']				 = $row['booking_id'];
//					$row1									 = array_values($rowArray);
//					fputcsv($handle, $row1);
//				}
//				fclose($handle);
//			
//				$rows1       = BookingSub::getPartnerTotalCountBookingReport($fromDate, $toDate, $export_agent_id);
//				$handle1		 = fopen("php://output", 'w');
//			    fputcsv($handle1, ['Total Booking Count(Local)', 'Total Booking Count(OutStation)', 'Total Served Booking Count', 'Total Amount', 'Total Net Base Amount', 'Total Gozo Amount', 'Total Receivable', 'Total Payable', 'Total Wallet Balance']);
//				foreach ($rows1 as $val)
//				{
//				$rowArray1								 = array();
//				$rowArray1['BookingCountLocal']			 = $val['total_book_local'];
//				$rowArray1['BookingCountOutStation']	 = $val['total_book_outstation'];
//				$rowArray1['TotalServedBookingCount']	 = $val['total_served_booking'];
//				$rowArray1['TotalAmount']				 = $val['totalamount'];
//				$rowArray1['NetBaseAmount']				 = $val['net_base_amount'];
//				$rowArray1['GozoAmount']				 = $val['gozoamount'];
//				$rowArray1['Receivable']				 = number_format($val['receivable']);
//				$rowArray1['Payable']					 = number_format(-1 * $val['payable']);
//				$rowArray1['WalletBalance']				 = number_format($val['pts_wallet_balance']);
//				$row2									 = array_values($rowArray1);
//				fputcsv($handle1, $row2);
//				}
//				fclose($handle1);
//				exit;
//			}
//		}
//		$dataProvider			 = BookingSub::model()->getPartnerWiseCountBookingReport($date1, $date2, $agentId);
//		$totalCount				 = BookingSub::getPartnerTotalCountBookingReport($date1, $date2, $agentId);
//		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
//		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
//		$this->render('partner_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider, 'date1' => $date1, 'date2' => $date2, 'totalCount' => $totalCount));
	}

	public function actionLouRequired()
	{
		$pagetitle		 = "Cabs with LOU required";
		$this->pageTitle = $pagetitle;
		$model			 = new VendorVehicle();
		$pageSize		 = Yii::app()->params['listPerPage'];
		$data			 = Yii::app()->request->getParam('VendorVehicle');
		$dataProvider	 = VendorVehicle::model()->getLouRequiredData($data['search']);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('lourequired', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionProcessedPayments()
	{

		$model			 = new OnlineBanking();
		$dataProvider	 = OnlineBanking::fetchList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('processed_payments', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionReferralBonous()
	{
		$this->pageTitle = "Referral Bonus";
		$model			 = new Users();
		$request		 = Yii::app()->request;
		$dataProvider	 = $model->getReferralBonousList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('referral_bonous', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/*
	 * Latepickup is active action | 3 table structure is in new model.
	 * Depricated
	 */

	public function actionLatepickup_v0()
	{
		$this->pageTitle = "Late PickUp";
		$model			 = new BookingSub();
		$request		 = Yii::app()->request;
		$bkgType		 = Yii::app()->request->getParam('type');
		$dataProvider	 = $model->getUrgentPickup($bkgType);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('latepickup', array('model' => $model, 'dataProvider' => $dataProvider, 'countPromos' => $countPromos), false, true);
	}

	public function actionScqReport()
	{
		$this->pageTitle	 = "Service Center Queues ";
		$booksub			 = new BookingSub();
		$booksub->from_date	 = date("Y-m-d") . ' 00:00:00';
		$booksub->to_date	 = date("Y-m-d") . ' 23:59:59';
		$req				 = Yii::app()->request;
		$result				 = array();
		if ($req->getParam('BookingSub'))
		{
			$arr				 = $req->getParam('BookingSub');
			$date1				 = $arr['date'];
			$booksub->from_date	 = DateTimeFormat::DatePickerToDate($date1) . ' 00:00:00';
			$booksub->to_date	 = DateTimeFormat::DatePickerToDate($date1) . ' 23:59:59';
		}
		else
		{
			$date1 = DateTimeFormat::DateToLocale(date());
		}
		$booksub->date	 = $date1;
		$data			 = ServiceCallQueue::scqreport($booksub->from_date, $booksub->to_date);
		$this->render('cbrreport', array('data' => $data, 'booksub' => $booksub), false, true);
	}

	public function actionVendorCancellation()
	{
		$this->pageTitle = "Vendor Cancellation - Report";
		$model			 = new Booking();
		$condition		 = "";
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr					 = $request->getParam('Booking');
			$model->bkg_create_date1 = $date1					 = $arr['bkg_create_date1'];
			$model->bkg_create_date2 = $date2					 = $arr['bkg_create_date2'];
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"CompletedBookingZone_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "VendorCancellation_" . date('YmdHi') . ".csv";
				$rows		 = Vendors::getVendorCancellation($date1, $date2);
				$handle		 = fopen("php://output", 'w');
				fputcsv($handle, ['Vendor Id', 'Vendor Name', 'Total Assigned', 'Total Served', 'Total Cancellations']);
				foreach ($rows as $row)
				{
					$rowArray								 = array();
					$rowArray['vendor_id']					 = $row['vendor_id'];
					$rowArray['vendor_name']				 = $row['vendor_name'];
					$rowArray['total_vendor_assigned_count'] = $row['total_vendor_assigned_count'];
					$rowArray['total_vendor_served_count']	 = $row['total_vendor_served_count'];
					$rowArray['total_vendor_cancel_count']	 = $row['total_vendor_cancel_count'] . " (" . round((($row['total_vendor_cancel_count'] * 100) / $row['total_vendor_assigned_count']), 2) . "%)";
					$row1									 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = Vendors::getVendorCancellation($date1, $date2, 'Command');
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('vendor_cancellation', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actioncbrDetailsReport()
	{
		$this->redirect("/report/scq/cbrdetailsreport/");
		exit();
		$this->pageTitle = "CBR Details Report";
		$followUps		 = new ServiceCallQueue();
		$req			 = Yii::app()->request;

		$followUps->isCreated						 = empty($req->getParam('isCreated')) || $req->getParam('isCreated') == 0 ? 0 : 1;
		$followUps->csrSearch						 = empty($req->getParam('csrId')) ? "" : explode(",", $req->getParam("csrId"));
		$followUps->date							 = empty($req->getParam('date')) ? DateTimeFormat::DateToLocale(date()) : $req->getParam("date");
		$followUps->from_date						 = empty($req->getParam('fromdate')) ? date("Y-m-d") : $req->getParam("fromdate");
		$followUps->to_date							 = empty($req->getParam('todate')) ? date("Y-m-d") : $req->getParam("todate");
		$followUps->event_id						 = empty($req->getParam('event_id')) ? "" : $req->getParam("event_id");
		$followUps->event_by						 = empty($req->getParam('event_by')) ? "" : $req->getParam("event_by");
		$followUps->queueType						 = empty($req->getParam('queueType')) ? "" : $req->getParam("queueType");
		$followUps->date1							 = $followUps->from_date . ' 00:00:00';
		$followUps->date2							 = $followUps->to_date . ' 23:59:59';
		$followUps->scq_to_be_followed_up_by_id		 = empty($req->getParam('teamId')) ? 0 : $req->getParam("teamId");
		$followUps->requestedBy						 = empty($req->getParam('followupPerson')) ? 0 : $req->getParam("followupPerson");
		$followUps->scq_to_be_followed_up_by_type	 = empty($req->getParam('followupWith')) ? 0 : $req->getParam("followupWith");
		$followupPersonEntity						 = empty($req->getParam('followupPersonEntity')) ? 0 : $req->getParam("followupPersonEntity");
		$followupWithEntityType						 = empty($req->getParam('followupWithEntityType')) ? 0 : $req->getParam("followupWithEntityType");
		switch ($followUps->requestedBy)
		{
			case 1:
				$followUps->custId	 = $followupPersonEntity;
				break;
			case 2:
				$followUps->vendId	 = $followupPersonEntity;
				break;
			case 3:
				$followUps->drvId	 = $followupPersonEntity;
				break;
			case 4:
				$followUps->adminId	 = $followupPersonEntity;
				break;
			case 5:
				$followUps->agntId	 = $followupPersonEntity;
				break;
		}
		switch ($followUps->scq_to_be_followed_up_by_type)
		{
			case 2:
				$followUps->isGozen = $followupWithEntityType;
				break;
		}

		if (isset($_REQUEST['export1']))
		{
			$followUps->isCreated						 = empty($req->getParam('export_isCreated')) || $req->getParam('export_isCreated') == 0 ? 0 : 1;
			$followUps->csrSearch						 = empty($req->getParam('export_csrId')) ? "" : explode(",", $req->getParam("export_csrId"));
			$followUps->date							 = empty($req->getParam('export_date')) ? DateTimeFormat::DateToLocale(date()) : $req->getParam("export_date");
			$followUps->from_date						 = empty($req->getParam('export_fromdate')) ? date("Y-m-d") : $req->getParam("export_fromdate");
			$followUps->to_date							 = empty($req->getParam('export_todate')) ? date("Y-m-d") : $req->getParam("export_todate");
			$followUps->event_id						 = empty($req->getParam('export_event_id')) ? "" : $req->getParam("export_event_id");
			$followUps->event_by						 = empty($req->getParam('export_event_by')) ? "" : $req->getParam("export_event_by");
			$followUps->queueType						 = empty($req->getParam('export_queueType')) ? "" : $req->getParam("export_queueType");
			$followUps->date1							 = $followUps->from_date . ' 00:00:00';
			$followUps->date2							 = $followUps->to_date . ' 23:59:59';
			$followUps->scq_to_be_followed_up_by_id		 = empty($req->getParam('export_teamId')) ? 0 : $req->getParam("export_teamId");
			$followUps->requestedBy						 = empty($req->getParam('export_requested')) ? 0 : $req->getParam("export_requested");
			$followUps->scq_to_be_followed_up_by_type	 = empty($req->getParam('export_disposed')) ? 0 : $req->getParam("export_disposed");
			$followupPersonEntity						 = empty($req->getParam('export_followupPersonEntity')) ? 0 : $req->getParam("export_followupPersonEntity");
			$followupWithEntityType						 = empty($req->getParam('export_followupWithEntityType')) ? 0 : $req->getParam("export_followupWithEntityType");

			switch ($followUps->requestedBy)
			{
				case 1:
					$followUps->custId	 = $followupPersonEntity;
					break;
				case 2:
					$followUps->vendId	 = $followupPersonEntity;
					break;
				case 3:
					$followUps->drvId	 = $followupPersonEntity;
					break;
				case 4:
					$followUps->adminId	 = $followupPersonEntity;
					break;
				case 5:
					$followUps->agntId	 = $followupPersonEntity;
					break;
			}
			switch ($followUps->scq_to_be_followed_up_by_type)
			{
				case 2:
					$followUps->isGozen = $followupWithEntityType;
					break;
			}
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CBR_Details_Report_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "CBR_Details_Report_" . date('YmdHi') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}

			$rows	 = ServiceCallQueue::model()->cbrDetailsReport($followUps, $userInfo, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Followup ID', 'Customer Contact Id', 'Booking ID', 'Queue Type', 'Assigned CSR (Employee ID &NAME)', 'Created Date (CSR)', 'Creation Comment', 'FollowUp Date', 'Assign Date', 'Time to Assign', 'Closed Date (CSR)', 'Disposition Comment', 'Time to Close']);
			foreach ($rows as $data)
			{
				$rowArray				 = array();
				$rowArray['followup_id'] = $data['FollowupId'];

				$rowArray['customer_contact_id'] = " ";
				if ($data['CustomerContactId'] > 0)
				{
					$contacName						 = Contact::model()->findByPk($data['CustomerContactId'])->ctt_name;
					$contactProfileDetails			 = ContactProfile::getCodeByCttId($data['CustomerContactId']);
					$rowArray['customer_contact_id'] = $contacName . " ( " . $data['CustomerContactId'] . " )";
					if ($contactProfileDetails['cr_is_partner'] != null)
					{
						$rowArray['customer_contact_id'] = " " . "AGT00" . $contactProfileDetails['cr_is_partner'];
					}
					if ($contactProfileDetails['drv_code'] != null)
					{
						$rowArray['customer_contact_id'] = " " . $contactProfileDetails['drv_code'];
					}
					if ($contactProfileDetails['vnd_code'] != null)
					{
						$rowArray['customer_contact_id'] = " " . $contactProfileDetails['vnd_code'];
					}
				}

				$rowArray['booking_id']		 = ($data['ItemID'] != '') ? $data['ItemID'] : '';
				$rowArray['queue_type']		 = $data['followUpType'];
				$rowArray['assigned_csr']	 = $data['csrName'] . " (Employee ID : " . $data['empCode'] . ")";
				$created					 = '';
				if ($data['scq_created_by_type'] == 1)
				{
					$created = $data['usr_name'] . " ";
				}
				else if ($data['scq_created_by_type'] == 2)
				{
					$created = $data['vnd_name'] . " ";
				}
				else if ($data['scq_created_by_type'] == 3)
				{
					$created = $data['drv_name'] . " ";
				}
				else if ($data['scq_created_by_type'] == 4)
				{
					$created = $data['CreatedCsrName'] . " (Employee ID : " . $data['CreatedCsrempCode'] . ")";
				}
				$rowArray['created_date']		 = date("d-m-Y H:i:s", strtotime($data['createDate'])) . " " . $created;
				$rowArray['creation_comments']	 = $data['scq_creation_comments'];
				$rowArray['followup_date']		 = $data['followUpdDate'];
				$rowArray['assign_date']		 = $data['assignedDate'];
				$rowArray['time_toassigned']	 = '';
				if (($data['followUpdDate'] != null) && ($data['assignedDate'] != null))
				{
					$follow_from_time			 = strtotime($data['followUpdDate']);
					$assigned_to_time			 = strtotime($data['assignedDate']);
					$mintue						 = round(abs($assigned_to_time - $follow_from_time) / 60, 2);
					$rowArray['time_toassigned'] = Filter::getDurationbyMinute($mintue, 1);
				}
				$rowArray['closed_date'] = '';
				if ($data['closedDate'] != null)
				{
					$rowArray['closed_date'] = $data['closedDate'] . " " . $data['ClosedCsrName'] . " (EmpID : " . $data['ClosedCsrempCode'] . ")";
				}
				else
				{
					if ($data['scq_disposition_comments'] != null)
					{
						if ($data['scq_created_by_type'] == 1)
						{
							$rowArray['closed_date'] = $data['usr_name'];
						}
						else if ($data['scq_created_by_type'] == 2)
						{
							$rowArray['closed_date'] = $data['vnd_name'];
						}
						else if ($data['scq_created_by_type'] == 3)
						{
							$rowArray['closed_date'] = $data['drv_name'];
						}
					}
				}
				$rowArray['disposition_comments']	 = $data['scq_disposition_comments'];
				$rowArray['timeto_close']			 = '';
				if (($data['closedDate'] != null) && ($data['assignedDate'] != null))
				{
					$to_time					 = strtotime($data['closedDate']);
					$from_time					 = strtotime($data['assignedDate']);
					$mintue						 = round(abs($to_time - $from_time) / 60, 2);
					$rowArray['timeto_close']	 = Filter::getDurationbyMinute($mintue, 1);
				}
				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$userInfo		 = UserInfo::getInstance();
		$dataProvider	 = ServiceCallQueue::model()->cbrDetailsReport($followUps, $userInfo);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('cbrdetailsreport', array('followUps' => $followUps, 'dataProvider' => $dataProvider), false, true);
	}

	public function actioncsrLeadPerformanceReport()
	{
		$this->pageTitle		 = "Lead CSR Performance Report";
		$followUps				 = new ServiceCallQueue();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;

		$req = Yii::app()->request;

		if ($req->getParam('ServiceCallQueue'))
		{
			$arr					 = $req->getParam('ServiceCallQueue');
			$date1					 = $arr['from_date'];
			$fromdate				 = new DateTime($date1);
			$followUps->from_date	 = $fromdate->format('Y-m-d');
			$date2					 = $arr['to_date'];
			$todate					 = new DateTime($date2);
			$followUps->to_date		 = $todate->format('Y-m-d');
			$followUps->adminId		 = $arr['adminId'];
		}

		$dataProvider = ServiceCallQueue::model()->csrLeadPerformanceReport($followUps);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('csrLeadPerformanceReport', array('followUps' => $followUps, 'dataProvider' => $dataProvider), false, true);
	}

	public function actioncbrCloseReport()
	{
		$this->pageTitle = "CBR Close Report";
		$followup		 = new ServiceCallQueue();
		$req			 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr									 = $req->getParam('ServiceCallQueue');
			$date1									 = $arr['date'];
			$followup->date							 = $date1;
			$followup->date1						 = DateTimeFormat::DatePickerToDate($date1) . ' 00:00:00';
			$followup->date2						 = DateTimeFormat::DatePickerToDate($date1) . ' 23:59:59';
			$followup->queueType					 = $arr['queueType'];
			$followup->scq_to_be_followed_up_by_id	 = $arr['scq_to_be_followed_up_by_id'];
		}
		else
		{
			$followup->date							 = empty($req->getParam('date')) ? DateTimeFormat::DateToLocale(date()) : $req->getParam("date");
			$date1									 = $followup->date;
			$followup->date1						 = DateTimeFormat::DatePickerToDate($followup->date) . ' 00:00:00';
			$followup->date2						 = DateTimeFormat::DatePickerToDate($followup->date) . ' 23:59:59';
			$followup->queueType					 = null;
			$followup->scq_to_be_followed_up_by_id	 = empty($req->getParam('team')) ? 0 : $req->getParam('team');
		}
		$dataProvider = ServiceCallQueue::cbrCloseReport($followup->date, $followup->date1, $followup->date2, $followup->queueType, $followup->scq_to_be_followed_up_by_id);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('cbrclosesreport', array('followup' => $followup, 'dataProvider' => $dataProvider,), false, true);
	}

	public function actioncbrCombineCloseReport()
	{
		$this->pageTitle = "CBR Combine Close Report";
		$booksub		 = new BookingSub();

		$req		 = Yii::app()->request;
		$queueType	 = $req->getParam("queueType");
		if ($req->getParam('BookingSub'))
		{
			$arr					 = $req->getParam('BookingSub');
			$date1					 = $arr['bkg_from_date'] . ' 00:00:00';
			$date2					 = $arr['bkg_to_date'] . ' 23:59:59';
			$booksub->bkg_from_date	 = $arr['bkg_from_date'];
			$booksub->bkg_to_date	 = $arr['bkg_to_date'];
		}
		else
		{
			$date1					 = $booksub->bkg_from_date	 = date("Y-m-d", strtotime("-30 days")) . ' 00:00:00';
			$date2					 = $booksub->bkg_to_date	 = date("Y-m-d", strtotime("-1 days")) . ' 23:59:59';
		}

		$booksub->date	 = $date1;
		$dataProvider	 = FollowUps::cbrCombineCloseReport($booksub->date, $date1, $date2, $queueType);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('cbrcombineclosesreport', array('booksub' => $booksub, 'dataProvider' => $dataProvider,), false, true);
	}

	public function actionServiceRequests($qry = [])
	{
		$this->pageTitle						 = "Service Requests Reports ";
		$csr									 = UserInfo::getUserId();
		$csrTeam								 = Admins::getTeamid($csr);
		$model									 = new ServiceCallQueue();
		$isFollowUpOpen							 = $model->isFollowUpOpen					 = 1;
		$isDue24								 = $model->isDue24							 = 0;
		$model->scq_to_be_followed_up_by_type	 = 1;
		$model->scq_to_be_followed_up_by_id		 = $csrTeam;
		$req									 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr									 = $req->getParam('ServiceCallQueue');
			$csrTeam								 = $arr['scq_to_be_followed_up_by_id'];
			$model->scq_to_be_followed_up_by_id		 = $csrTeam;
			$isFollowUpOpen							 = $arr['isFollowUpOpen'] == 1 ? 1 : 0;
			$model->isFollowUpOpen					 = $isFollowUpOpen;
			$isDue24								 = $arr['isDue24'] == 1 && $isFollowUpOpen == 1 ? 1 : 0;
			$model->isDue24							 = $isDue24;
			$model->search							 = $search									 = !empty($arr['search']) ? $arr['search'] : "";
			$model->requestedBy						 = $arr['requestedBy'];
			$model->custId							 = $arr['custId'];
			$model->vendId							 = $arr['vendId'];
			$model->drvId							 = $arr['drvId'];
			$model->adminId							 = $arr['adminId'];
			$model->agntId							 = $arr['agntId'];
			$model->scq_to_be_followed_up_by_type	 = $arr['scq_to_be_followed_up_by_type'];
			$model->scq_to_be_followed_up_by_id		 = $arr['scq_to_be_followed_up_by_id'];
			$model->isGozen							 = $arr['isGozen'];
		}
		$dataProvider = $model->getInternals($isDue24, $search, $isFollowUpOpen);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 200]);
		$this->render('internalCbr', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionServiceRequestsOwn($qry = [])
	{
		$this->pageTitle						 = "Service Requests Reports ";
		$csr									 = UserInfo::getUserId();
		$csrTeam								 = Admins::getTeamid($csr);
		$model									 = new ServiceCallQueue();
		$isFollowUpOpen							 = $model->isFollowUpOpen					 = 1;
		$isDue24								 = $model->isDue24							 = 0;
		$model->isGozen							 = Yii::app()->user->getId();
		$model->scq_to_be_followed_up_by_type	 = 2;
		$req									 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr									 = $req->getParam('ServiceCallQueue');
			$csrTeam								 = $arr['scq_to_be_followed_up_by_id'];
			$model->scq_to_be_followed_up_by_id		 = $csrTeam;
			$isFollowUpOpen							 = $arr['isFollowUpOpen'] == 1 ? 1 : 0;
			$model->isFollowUpOpen					 = $isFollowUpOpen;
			$isDue24								 = $arr['isDue24'] == 1 && $isFollowUpOpen == 1 ? 1 : 0;
			$model->isDue24							 = $isDue24;
			$model->search							 = $search									 = !empty($arr['search']) ? $arr['search'] : "";
			$model->requestedBy						 = $arr['requestedBy'];
			$model->custId							 = $arr['custId'];
			$model->vendId							 = $arr['vendId'];
			$model->drvId							 = $arr['drvId'];
			$model->adminId							 = $arr['adminId'];
			$model->agntId							 = $arr['agntId'];
			$model->scq_to_be_followed_up_by_type	 = $arr['scq_to_be_followed_up_by_type'];
			$model->scq_to_be_followed_up_by_id		 = $arr['scq_to_be_followed_up_by_id'];
			$model->isGozen							 = $arr['isGozen'];
		}
		$dataProvider = $model->getInternals($isDue24, $search, $isFollowUpOpen);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 200]);
		$this->render('internalCbr', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionPenaltyReport()
	{

		$this->pageTitle			 = "Penalty Report";
		$model						 = new AccountTransDetails();
		$command					 = false;
		$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 29 days'));
		$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));
		if ($_REQUEST['AccountTransDetails'])
		{
			if ($_REQUEST['AccountTransDetails']['trans_create_date1'] != '' && $_REQUEST['AccountTransDetails']['trans_create_date2'] != '')
			{
				$transDate1					 = $_REQUEST['AccountTransDetails']['trans_create_date1'];
				$transDate2					 = $_REQUEST['AccountTransDetails']['trans_create_date2'];
				$removalDate1				 = $_REQUEST['AccountTransDetails']['trans_remove_date1'];
				$removalDate2				 = $_REQUEST['AccountTransDetails']['trans_remove_date2'];
				$model->trans_create_date1	 = $transDate1;
				$model->trans_create_date2	 = $transDate2;
				$model->trans_remove_date1	 = $removalDate1;
				$model->trans_remove_date2	 = $removalDate2;
			}
			$model->bkg_id		 = $_REQUEST['AccountTransDetails']['bkg_id'];
			$model->vendor_id	 = $_REQUEST['AccountTransDetails']['vendor_id'];
		}

		$dataProvider = AccountTransDetails::model()->getPenaltyReport($command, $transDate1, $transDate2, $model->bkg_id, $model->vendor_id, $removalDate1, $removalDate2);

		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$transDate1			 = ($_REQUEST['export_trans_create_date1'] != '') ? $_REQUEST['export_trans_create_date1'] : '';
			$transDate2			 = ($_REQUEST['export_trans_create_date2'] != '') ? $_REQUEST['export_trans_create_date2'] : '';
			$removalDate1		 = ($_REQUEST['export_trans_remove_date1'] != '') ? $_REQUEST['export_trans_remove_date1'] : '';
			$removalDate2		 = ($_REQUEST['export_trans_remove_date2'] != '') ? $_REQUEST['export_trans_remove_date2'] : '';
			$model->bkg_id		 = ($_REQUEST['export_bkg_id'] != '') ? $_REQUEST['export_bkg_id'] : '';
			$model->vendor_id	 = ($_REQUEST['export_vendor_id'] != '') ? $_REQUEST['export_vendor_id'] : '';
		}
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"PenaltyReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportVolumeTrend" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername	 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$command = true;
			$rows	 = AccountTransDetails::model()->getPenaltyReport($command, $transDate1, $transDate2, $model->bkg_id, $model->vendor_id, $removalDate1, $removalDate2);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Transaction Date', 'Create Date', 'Transaction Type', 'Transaction Status', 'Ledger entry', 'Booking ID', 'Comments', 'Applied by', 'Penalty Removal date',
				'Removal Comments', 'Removed by']);
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$ptype		 = CJSON::decode($row['adt_addt_params']);
					$penaltyType = $ptype['penaltyType'];
					$pModel		 = PenaltyRules::getValueByPenaltyType($penaltyType);
					if ($row['act_active'] == 1)
					{
						if ($row['adm_id'] != '')
						{
							$appliedby = $row['adm_fname'] . '' . $row['adm_lname'];
						}
						else
						{
							$appliedby = "System";
						}
					}
					if ($row['act_active'] == 0)
					{
						if ($row['adm_id'] != '')
						{
							$removedby = $row['adm_fname'] . '' . $row['adm_lname'];
						}
						else
						{
							$removedby = "System";
						}
					}
					if ($row['act_active'] == 0)
					{
						if ($row['blg_desc'] != '')
						{
							$removalComments = $row['blg_desc'];
						}
						else
						{
							$removalComments = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['adt_remarks']);
						}
					}
					$rowArray['act_date']			 = $row['act_date'];
					$rowArray['act_created']		 = $row['act_created'];
					$rowArray['adt_addt_params']	 = $pModel['plt_desc'];
					$rowArray['act_active']			 = ($row['act_active'] == 1) ? 'Applied' : 'Removed';
					$rowArray['adt_amount']			 = ($row['act_active'] == 1) ? $row['ledgerAmt'] : $row['ledgerAmt1'];
					$rowArray['adt_trans_ref_id']	 = $row['adt_trans_ref_id'];
					$rowArray['act_remarks']		 = ($row['act_active'] == 1) ? preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['adt_remarks']) : '';
					$rowArray['act_user_id']		 = $appliedby;
					$rowArray['adt_modified']		 = ($row['act_active'] == 0) ? $row['adt_modified'] : '';
					$rowArray['adt_remarks']		 = ($row['act_active'] == 0) ? $removalComments : '';
					$rowArray['act_user_id2']		 = $removedby;

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
			}
			fclose($handle);
			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('penaltyReport', array('dataProvider' => $dataProvider, 'model' => $model, 'message' => $message));
	}

	public function actionBookingtrackdetails()
	{
		$this->pageTitle = "Tracking Details";
		$model			 = new Booking();
		//$model->bkg_agent_id = 18190;
		$arr			 = [];
		$checked		 = [
			'late_started'				 => false,
			'late_arrival'				 => false, //added for arrival time
			'start_location_mismatch'	 => false,
			'end_location_mismatch'		 => false,
		];
		if (isset($_REQUEST['Booking']))
		{
			$arr				 = Yii::app()->request->getParam('Booking');
			$model->attributes	 = $arr;
			if (!empty($arr['late_started']) && $arr['late_started'] == 1)
			{
				$checked['late_started'] = true;
			}
			if (!empty($arr['late_arrival']) && $arr['late_arrival'] == 1)
			{
				$checked['late_arrival'] = true;
			}
			if (!empty($arr['start_location_mismatch']) && $arr['start_location_mismatch'] == 1)
			{
				$checked['start_location_mismatch'] = true;
			}
			if (!empty($arr['end_location_mismatch']) && $arr['end_location_mismatch'] == 1)
			{
				$checked['end_location_mismatch'] = true;
			}
		}
		else
		{
			$model->bkg_status = '0';
		}

		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{
			$arr2							 = array();
			$adminWrapper					 = new Booking();
			$arr['bkg_pickup_date1']		 = Yii::app()->request->getParam('export_from1');
			$arr['bkg_pickup_date2']		 = Yii::app()->request->getParam('export_to1');
			$arr['bkg_booking_id']			 = Yii::app()->request->getParam('bkg_booking_id');
			$arr['bkg_status']				 = Yii::app()->request->getParam('bkg_status');
			$arr['bkg_agent_id']			 = Yii::app()->request->getParam('bkg_agent_id');
			$arr['late_arrival']			 = Yii::app()->request->getParam('late_arrival');
			$arr['start_location_mismatch']	 = Yii::app()->request->getParam('start_location_mismatch');
			$arr['end_location_mismatch']	 = Yii::app()->request->getParam('end_location_mismatch');

			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"Bookingtrackdetails_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "Bookingtrackdetails_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = BookingTrail::getBookingTrackDetails($arr);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, [
					'Partner Name',
					'Booking ID',
					'Ref Booking ID',
					'Status',
					'Booking Route',
					'Vendor Code',
					'Driver Code',
					'Pickup Time',
					'Create Time',
					'Vendor Assign Time',
					'Driver Assign Time',
					'Cab Assign Time',
					'Driver Arrival Time',
					'Trip Start Time',
					'Trip End Time',
					'Trip Start Cordinates',
					'Trip End Cordinates',
				]);
				foreach ($rows as $row)
				{
					$rowArray							 = array();
					$rowArray['bkg_agent_id']			 = Agents::model()->findByPk($row['bkg_agent_id'])->agt_company;
					$rowArray['bkg_booking_id']			 = $row['bkg_booking_id'];
					$rowArray['bkg_agent_ref_code']		 = $row['bkg_agent_ref_code'];
					$rowArray['bkg_status']				 = Booking::model()->getActiveBookingStatus($row['bkg_status']);
					$rowArray['bkg_route_city_names']	 = implode(" - ", json_decode($row['bkg_route_city_names']));
					$vndDetails							 = Vendors::model()->findByPk($row['bcb_vendor_id']);
					$drvDetails							 = Drivers::model()->findByPk($row['bcb_driver_id']);
					$rowArray['bcb_vendor_id']			 = $vndDetails->vnd_code;
					$rowArray['bcb_driver_id']			 = $drvDetails->drv_code;
					$rowArray['bkgPickupDate']			 = ($row['bkg_pickup_date']) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
					$rowArray['bkgCreateDate']			 = ($row['bkg_create_date']) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
					$rowArray['btrVendorAssignLdate']	 = ($row['btr_vendor_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_vendor_assign_ldate'])) : '';
					$rowArray['btrDriverAssignLdate']	 = ($row['btr_driver_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_driver_assign_ldate'])) : '';
					$rowArray['btrCabAssignLdate']		 = ($row['btr_cab_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_cab_assign_ldate'])) : '';
					$rowArray['bkgTripArriveTime']		 = ($row['bkg_trip_arrive_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_arrive_time'])) : '';
					$rowArray['bkgTripStartTime']		 = ($row['bkg_trip_start_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_start_time'])) : '';
					$rowArray['bkgTripEndTime']			 = ($row['bkg_trip_end_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_end_time'])) : '';
					$rowArray['estPickupLatlong']		 = $row['estPickupLatlong'];
					$rowArray['estDropupLatlong']		 = $row['estDropupLatlong'];

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}

//		}
		//$arr['bkg_agent_id'] = 18190;
		$dataProvider = BookingTrail::getBookingTrackDetails($arr, 'Command');
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('bookingtrackdetails', array('model' => $model, 'dataProvider' => $dataProvider, 'checked' => $checked));
	}

	public function actionTrackingView()
	{
		$this->pageTitle = "Partner Tracking View";
		$bkgId			 = Yii::app()->request->getParam('id');
		$eventId		 = Yii::app()->request->getParam('eventId');
		$bModel			 = Booking::model()->findByPk($bkgId);
		if ($bModel->bkg_agent_id == 18190)
		{
			/* @var $model AgentApiTracking */
			$model					 = new AgentApiTracking();
			$model->aat_booking_id	 = $bkgId;
			$dataProvider			 = $model->getMmtTrackingDataByBkgId();
		}
		else
		{
			/* @var $model PartnerApiTracking */
			$model					 = new PartnerApiTracking();
			$model->pat_booking_id	 = $bkgId;
			$dataProvider			 = $model->getPartnerTrackingDataByBkgId();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('trackingView', array('model' => $model, 'bModel' => $bModel, 'dataProvider' => $dataProvider));
	}

	public function actionAgentTrackingDetails()
	{
		$this->pageTitle = "Show Agent Request and Response";

		$aatId = Yii::app()->request->getParam('aatId');

		$model = AgentApiTracking::model()->findByPk($aatId);
		if (!$model)
		{
			$model = AgentApiTracking::model()->getFromArchieveById($aatId);
		}
		if (!$model)
		{
			throw new Exception("Record not found!!!");
		}

		if ($model->aat_s3_data != '' && $model->aat_s3_data != null)
		{
			$s3data		 = $model->aat_s3_data;
			$spaceFile	 = Stub\common\SpaceFile::populate($s3data);
			$url		 = $spaceFile->getURL();
			echo $url . "URL==============><br><br>";

			if ($model->aat_s3_data != '' && ($file = $model->getSpaceFile()) != null)
			{
				$body = $file->getContents();
			}

			echo "<pre>";
			print_r($body);
			exit();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('agentTrackingDetails', array('model' => $model));
	}

	public function actionPatTrackingDetails()
	{
		$this->pageTitle = "Show Partner Request and Response";
		$bkgId			 = Yii::app()->request->getParam('pat_booking_id');
		$bModel			 = Booking::model()->findByPk($bkgId);
		$patId			 = Yii::app()->request->getParam('patId');
		$model			 = PartnerApiTracking::model()->findByPk($patId);

		if ($model->pat_s3_data != '' && ($file = $model->getSpaceFile()) != null)
		{
			$s3data		 = $model->pat_s3_data;
			$spaceFile	 = Stub\common\SpaceFile::populate($s3data);
			$url		 = $spaceFile->getURL();
			echo $url . "URL==============><br><br>";

			$body = $file->getContents();

			echo "<pre>";
			print_r($body);
			exit();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('patTrackingDetails', array('model' => $model));
	}

	public function actionLatepickup()
	{
		$this->pageTitle = "Late PickUp";
		$model			 = new BookingSub();
		$request		 = Yii::app()->request;
		$bkgType		 = Yii::app()->request->getParam('type');

		$date1	 = date("Y-m-d");
		$date2	 = date("Y-m-d", strtotime('+1 days'));

		if ($request->getParam('BookingSub'))
		{
			$arr	 = $request->getParam('BookingSub');
			$date1	 = $arr['bkg_pickup_date1'];
			$date2	 = $arr['bkg_pickup_date2'];
		}

		$model->bkg_pickup_date1 = $date1;
		$model->bkg_pickup_date2 = $date2;
		$type					 = 1;
		$dataProvider1			 = $model->getUrgentPickup_v1($bkgType, $date1, $date2, $type);
		$dataProvider1->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider1->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$type					 = 2;
		$dataProvider2			 = $model->getUrgentPickup_v1($bkgType, $date1, $date2, $type);
		$dataProvider2->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider2->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$type					 = 3;
		$dataProvider3			 = $model->getUrgentPickup_v1($bkgType, $date1, $date2, $type);
		$dataProvider3->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider3->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);

		$view_arr['latepickup_v1']	 = array('model' => $model, 'dataProvider' => $dataProvider1, 'countPromos' => $countPromos, 'title' => 'Was Late for Pickup');
		$view_arr['latepickup_v2']	 = array('model' => $model, 'dataProvider' => $dataProvider2, 'countPromos' => $countPromos, 'title' => 'Already Late for Pickup');
		$view_arr['latepickup_v3']	 = array('model' => $model, 'dataProvider' => $dataProvider3, 'countPromos' => $countPromos, 'title' => 'To be Late for Pickup');

//		$this->render('latepickup_v1', array('model' => $model, 'dataProvider' => $dataProvider, 'countPromos' => $countPromos), false, true);
		$this->render("latepickup_main", ['model' => $model, 'latepickupList' => $view_arr], false);
	}

	public function actionZoneSupplyDensity()
	{
		$this->pageTitle = "Zone Supply Density Report";
//		$model				 = new Booking();
		$request		 = Yii::app()->request;
		$model			 = new Zones();
		if ($request->getParam('Zones'))
		{
			$arr			 = $request->getParam('Zones');
			$zon_id			 = ($arr['zon_id'] != '') ? $arr['zon_id'] : 0;
			$model->zon_id	 = $zon_id;
		}
		else
		{
			$zon_id = 0;
		}
//		$model->zero_percent = 1;
//        $model->zon_id = 1;
		$date1			 = date('Y-m-d', strtotime("-90 days"));
		$date2			 = date('Y-m-d');
		$dataProvider	 = Zones::model()->getZoneSupplyDensity($model, $date1, $date2, $zon_id);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render("zonesupplydensity", ['dataProvider' => $dataProvider, 'model' => $model], false);
	}

	public function actionZoneSupplyDensityVendorsList()
	{
		$this->pageTitle		 = "Zone Supply Density Vendors List";
		$zoneID					 = Yii::app()->request->getParam('zid');
		$type					 = Yii::app()->request->getParam('type');
		$model					 = new Booking();
		$model->bkg_pickup_date1 = date('Y-m-d', strtotime("-90 days"));
		$model->bkg_pickup_date2 = date('Y-m-d', strtotime("last day of this month"));

		$dataProvider = Zones::model()->getZoneSupplyDensityVendorsList($model, $zoneID, $type);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render("zonesupplydensityvendorlist", ['dataProvider' => $dataProvider, 'model' => $model], false);
	}

	public function actionZeroInventory()
	{
		$this->pageTitle = "Zero Inventory Zone";
		$model			 = new Booking();

		$date1			 = date('Y-m-d', strtotime("-180 days"));
		$date2			 = date('Y-m-d');
		$dataProvider	 = Zones::model()->getZeroinventory($model, $date1, $date2);
		Logger::trace("checking data provider values - " . json_encode($dataProvider));
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render("zeroinventory", ['dataProvider' => $dataProvider, 'model' => $model], false);
	}

	public function actionservicePerformance()
	{
		$this->pageTitle = "Service performance by tier";
		$model			 = new Booking();
		$condition		 = "";
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr						 = $request->getParam('Booking');
			$date1						 = $arr['bkg_pickup_date1'];
			$pickupDate1				 = new DateTime($date1);
			$model->bkg_pickup_date1	 = $pickupDate1->format('Y-m-d') . " 00:00:00";
			$date2						 = $arr['bkg_pickup_date2'];
			$pickupDate2				 = new DateTime($date2);
			$model->bkg_pickup_date2	 = $pickupDate2->format('Y-m-d') . " 23:59:59";
			$condition					 = ($model->bkg_pickup_date1 != '' && $model->bkg_pickup_date2 != '') ? "  AND  ( bkg_pickup_date BETWEEN '" . $model->bkg_pickup_date1 . "' AND '" . $model->bkg_pickup_date2 . "' ) " : '';
			$model->bkg_service_class	 = $arr['bkg_service_class'];
			$model->bkgtypes			 = $arr['bkgtypes'];
			if (count($arr['bkg_service_class']) > 0)
			{
				$vtype		 = implode(",", $model->bkg_service_class);
				$condition	 .= " AND scc_id IN ($vtype) ";
			}
			if (count($arr['bkgtypes']) > 0)
			{
				$bkgtypes	 = implode(',', $model->bkgtypes);
				$condition	 .= " AND bkg_booking_type IN ($bkgtypes)";
			}
		}
		else
		{
			$model->bkg_pickup_date1	 = date('Y-m-d') . " 00:00:00";
			$model->bkg_pickup_date2	 = date('Y-m-d') . " 23:59:59";
			$model->bkg_service_class	 = array(0 => 2, 1 => 4);
			$condition					 .= "  AND ( bkg_pickup_date BETWEEN '" . $model->bkg_pickup_date1 . "' AND '" . $model->bkg_pickup_date2 . "' ) ";
			$condition					 .= " AND scc_id IN (2,4)";
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			$vtype			 = Yii::app()->request->getParam('bkg_service_class');
			$btype			 = Yii::app()->request->getParam('bkg_booking_type');
			$cond			 = ($fromDate != '' && $toDate != '') ? " AND ( bkg_pickup_date BETWEEN '" . $fromDate . "' AND '" . $toDate . "' )" : '';
			$cond			 .= ($vtype != '') ? " AND scc_id IN ($vtype) " : '';
			$cond			 .= ($btype != '') ? " AND bkg_booking_type IN ($btype)" : '';
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"ServicePerformance_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "ServicePerformance_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = Booking::model()->getServicePerformance($cond);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, [
					'Booking ID',
					'Service Tier',
					'Bkg Type',
					'Pickup date',
					'On Trip follow up dispostion notes',
					'Post trip follow up call',
					'Rating star',
					'Actual pickup time',
					'Delay by X min'
				]);
				foreach ($rows as $row)
				{
					$rowArray								 = array();
					$rowArray['bkg_id']						 = $row['bkg_id'];
					$rowArray['cabtype']					 = $row['cabtype'];
					$rowArray['bkg_booking_type']			 = Booking::model()->getBookingType($row['bkg_booking_type']);
					$rowArray['bkg_pickup_date']			 = $row['bkg_pickup_date'];
					$rowArray['disposition_comments']		 = $row['disposition_comments'];
					$rowArray['post_disposition_comments']	 = $row['post_disposition_comments'];
					$rowArray['rtg_customer_overall']		 = $row['rtg_customer_overall'];
					$rowArray['ArrivedForPickupTime']		 = $row['ArrivedForPickupTime'];
					$rowArray['delay']						 = $row['delay'] > 0 ? $row['delay'] : "No delay";
					$row1									 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = Booking::model()->getServicePerformance($condition, 'Command');
		$this->render('hightiersperformance', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionPromoReport()
	{
		$this->pageTitle		 = "Promo Report";
		$model					 = new Promos();
		$model->from_date_create = date('Y-m-d');
		$model->to_date_create	 = date('Y-m-d');
		if (isset($_REQUEST['Promos']))
		{
			$model->attributes		 = $_REQUEST['Promos'];
			$model->from_date_create = $_REQUEST['Promos']['from_date_create'];
			$model->to_date_create	 = $_REQUEST['Promos']['to_date_create'];
			$model->from_date_pickup = $_REQUEST['Promos']['from_date_pickup'];
			$model->to_date_pickup	 = $_REQUEST['Promos']['to_date_pickup'];
			$model->status			 = $_REQUEST['Promos']['status'];
			if ($model->from_date_create == '' && $model->to_date_create == '')
			{
				$model->from_date_create = $_REQUEST['from_date_create'];
				$model->to_date_create	 = $_REQUEST['to_date_create'];
			}
			if ($model->from_date_pickup == '' && $model->to_date_pickup == '')
			{
				$model->from_date_pickup = $_REQUEST['from_date_pickup'];
				$model->to_date_pickup	 = $_REQUEST['to_date_pickup'];
			}
			if ($model->prm_id == '')
			{
				$model->prm_id = $_REQUEST['prm_id'];
			}
		}
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
		{
			$fromCreateDate	 = Yii::app()->request->getParam('from_date_create');
			$toCreateDate	 = Yii::app()->request->getParam('to_date_create');
			$fromPickupDate	 = Yii::app()->request->getParam('from_date_pickup');
			$toPickupDate	 = Yii::app()->request->getParam('to_date_pickup');
			$prmId			 = Yii::app()->request->getParam('prm_id');
			if ($prmId != '')
			{
				$model->prm_id = $prmId;
			}
			if ($fromCreateDate != '' && $toCreateDate != '')
			{
				$model->from_date_create = $fromCreateDate;
				$model->to_date_create	 = $toCreateDate;
			}
			else
			{
				$model->from_date_create = null;
				$model->to_date_create	 = null;
			}
			if ($fromPickupDate != '' && $toPickupDate != '')
			{
				$model->from_date_pickup = $fromPickupDate;
				$model->to_date_pickup	 = $toPickupDate;
			}

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"promoReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "promoReport_" . date('YmdHi') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->getPromoReportData();
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [
				'Promo Code',
				'Booking Id',
				'User Name',
				'User Type',
				'Booking Status',
				'Pickup Date',
				'Create Date'
			]);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['promoCode']			 = $row['promoCode'];
				$rowArray['bookingId']			 = $row['bookingId'];
				$rowArray['bkg_booking_type']	 = $row['UserName'];
				$rowArray['bkg_pickup_date']	 = $row['UserType'];
				$rowArray['status']				 = $row['status'];
				$rowArray['pickupDate']			 = $row['pickupDate'];
				$rowArray['createDate']			 = $row['createDate'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$countPromos	 = $model->getPromoWiseBookingCount();
		$dataProvider	 = $model->getPromoReportData('Command');
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('promo_report', array('model' => $model, 'dataProvider' => $dataProvider, 'countPromos' => $countPromos), false, true);
	}

	/*
	 * 	This function is used for General Report/ Driver app usage Report for perticular date range
	 */

	public function actionDriverAppUsageReport()
	{
		$this->pageTitle = "Driver App Usage Details";
		$model			 = new Booking;
		$arr			 = Yii::app()->request->getParam('Booking');

		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$arr['bkg_pickup_date1'] = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$arr['bkg_pickup_date2'] = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->attributes		 = $arr;
		}
		else
		{
			$arr['bkg_pickup_date1'] = $model->bkg_pickup_date1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			;
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			$arr['bkg_agent_id']	 = $model->bkg_agent_id	 = 18190;
		}
		//$arr['bkg_agent_id'] = 18190;
		$dataProvider = Drivers::getDriverAppusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('driverappusagedetails', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionmarginPercentageReport()
	{
		$this->pageTitle = "Margin percentage Report";
		$model			 = new Booking;
		$arr			 = Yii::app()->request->getParam('Booking');

		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$arr['bkg_pickup_date1'] = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$arr['bkg_pickup_date2'] = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->attributes		 = $arr;
		}
		else
		{
			$arr['bkg_pickup_date1'] = $model->bkg_pickup_date1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			;
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		}

		$dataProvider = BookingSub::model()->marginPercentageReport($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('marginpercentagereport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/*
	 * 	This function is used for General Report/ Vendor usage Report for perticular date range and vendor filter
	 */

	public function actionVendorUsageReport()
	{
		$this->pageTitle = "Vendor Usage Details";
		$model			 = new Booking;
		$arr			 = Yii::app()->request->getParam('Booking');
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->attributes		 = $arr;
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->bcb_vendor_id	 = $arr['bcb_vendor_id'];
		}
		else
		{
			$arr['bkg_pickup_date1'] = $model->bkg_pickup_date1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			;
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			$arr['bcb_vendor_id']	 = $model->bcb_vendor_id	 = '';
		}
		$dataProvider = Vendors::getVendorusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorusagedetails', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionAssignmentSummary()
	{
		$this->redirect('report/booking/assignmentSummary');
		exit();

		$this->pageTitle = "Assignment Report";
		$model			 = new BookingSub();
		$from_date		 = $to_date		 = '';
		$fromCreateDate	 = $toCreateDate	 = '';
		$request		 = Yii::app()->request;
		$orderby		 = 'date';
		$partnerId		 = 0;
		if ($request->getParam('BookingSub'))
		{
			$arr			 = $request->getParam('BookingSub');
			$from_date		 = $arr['bkg_pickup_date1'];
			$to_date		 = $arr['bkg_pickup_date2'];
			$fromCreateDate	 = $arr['bkg_create_date1'];
			$toCreateDate	 = $arr['bkg_create_date2'];
			$orderby		 = $arr['groupvar'];
			$partnerId		 = $arr['bkg_agent_id'];
			$bkgType		 = $arr['bkgtypes'];
			$gnowType		 = $arr['gnowType'];
			$nonProfitable	 = $arr['nonProfitable'];
			$excludeAT		 = isset($arr['excludeAT'][0]);
			$includeB2c		 = isset($arr['b2cbookings'][0]);
			$mmtbookings	 = $arr['mmtbookings'];
			$nonAPIPartner	 = $arr['nonAPIPartner'];
			$weekDays		 = $arr['weekDays'];
			$zones			 = $arr['sourcezone'];
			$region			 = $arr['region'];
			$state			 = $arr['state'];
			$assignedFrom	 = $arr['from_date'];
			$assignedTo		 = $arr['to_date'];
			$local			 = $arr['local'];
			$outstation		 = $arr['outstation'];

			$model->bkg_agent_id	 = $partnerId;
			$model->excludeAT		 = $excludeAT;
			$model->b2cbookings		 = $includeB2c;
			$model->mmtbookings		 = $mmtbookings;
			$model->nonAPIPartner	 = $nonAPIPartner;
			$model->nonProfitable	 = $nonProfitable;
			$model->local			 = $arr['local'];
			$model->outstation		 = $arr['outstation'];
		}
		else
		{
			$from_date	 = date("Y-m-d", strtotime("-28 day", time()));
			$to_date	 = date('Y-m-d', strtotime("+2 day", time()));
			$bkgType	 = [];
		}
		$model->bkgtypes		 = $bkgType;
		$model->bkg_pickup_date1 = $from_date;
		$model->bkg_pickup_date2 = $to_date;
		$model->bkg_create_date1 = $fromCreateDate;
		$model->bkg_create_date2 = $toCreateDate;
		$model->gnowType		 = $gnowType;
		$model->weekDays		 = $weekDays;
		$model->sourcezone		 = $zones;
		$model->region			 = $region;
		$model->state			 = $state;
		$model->from_date		 = $assignedFrom;
		$model->to_date			 = $assignedTo;

		$diff3Month	 = strtotime("-6 month", strtotime($to_date)) - strtotime($from_date);
		$error		 = '';
		if ($diff3Month > 0)
		{
			$error = "Date range should be less than 6 months";
			goto skipAll;
		}
		$params = [
			'from_date'		 => $from_date,
			'to_date'		 => $to_date,
			'fromCreateDate' => $fromCreateDate,
			'toCreateDate'	 => $toCreateDate,
			'bkgTypes'		 => $bkgType,
			'nonProfitable'	 => $nonProfitable,
			'gnowType'		 => $gnowType,
			'weekDays'		 => $weekDays,
			'zones'			 => $zones,
			'region'		 => $region,
			'state'			 => $state,
			'assignedFrom'	 => $assignedFrom,
			'assignedTo'	 => $assignedTo,
			'local'			 => $local,
			'outstation'	 => $outstation,
		];

		$dataProvider	 = BookingCab::getAssignmentData($params, $orderby, $partnerId, $includeB2c, $excludeAT, $nonAPIPartner, $mmtbookings);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$from_date		 = ($from_date != '') ? date('d/m/Y', strtotime($from_date)) : '';
		$to_date		 = ($to_date != '') ? date('d/m/Y', strtotime($to_date)) : '';
		skipAll:
		$this->render('assignment_summary', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'from_date'		 => $from_date,
			'to_date'		 => $to_date,
			'orderby'		 => $orderby,
			'error'			 => $error)
		);
	}

	/* this function is used for general report > regionwise Vendor App usaage report */

	public function actionRegionVendorwiseDriverAppusage()
	{
		$this->pageTitle = "Vendor and Region wise Driver App Usage Report";
		$model			 = new Booking;
		$arr			 = Yii::app()->request->getParam('Booking');
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->attributes		 = $arr;
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->bcb_vendor_id	 = $arr['bcb_vendor_id'];
			$model->bkg_region		 = $arr['bkg_region'];
		}
		else
		{
			$arr['bkg_pickup_date1'] = $model->bkg_pickup_date1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			//$arr['bcb_vendor_id']	 = $model->bcb_vendor_id	 = [];
			$arr['bkg_region']		 = $model->bkg_region		 = '';
		}

		$dataProvider = BookingTrack::model()->getVendorwiseAppusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorwiseappusage', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry)
		);
	}

	public function actionDailyLoss()
	{
		$this->pageTitle = "Daily Loss";
		$model			 = new Booking;
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr					 = $request->getParam('Booking');
			$model->attributes		 = $arr;
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->preData			 = $arr['preData'];
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d') . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			$model->preData			 = '6,5,7';
		}

		if (isset($_REQUEST['export']) && isset($_REQUEST['export_from']) && isset($_REQUEST['export_to']) && isset($_REQUEST['export_preData']))
		{

			$model->bkg_pickup_date1 = $fromDate				 = $model->bkg_pickup_date1 = Yii::app()->request->getParam('export_from');
			$model->bkg_pickup_date2 = $toDate					 = $model->bkg_pickup_date2 = Yii::app()->request->getParam('export_to');
			$model->preData			 = Yii::app()->request->getParam('export_preData');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DailyLoss_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportbooking" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername	 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Booking::model()->dailylossReport($model, 'export');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [
				'Booking ID',
				'Tier',
				'Source Zone',
				'Destination Zone',
				'Bkg Type',
				'Create time',
				'Pickup time',
				'Assign Count',
				'Last vendor ID',
				'First Vendor ID',
				'Bkg Amount',
				'First VA',
				'Last VA',
				'Gozo P/Loss amount',
				'Last Assigntype',
				'Last Assigned by'
			]);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['bkg_id']				 = $row['bkg_id'];
				$rowArray['scc_label']			 = $row['scc_label'];
				$rowArray['sourceZone']			 = $row['sourceZone'];
				$rowArray['destinationZone']	 = $row['destinationZone'];
				$rowArray['bkg_booking_type']	 = Booking::model()->getBookingType($row['bkg_booking_type']);
				$rowArray['bkg_create_date']	 = $row['bkg_create_date'];
				$rowArray['bkg_pickup_date']	 = $row['bkg_pickup_date'];
				$rowArray['AssigbedCount']		 = $row['AssigbedCount'];
				$rowArray['LVendorID']			 = $row['LVendorID'];
				$rowArray['FVendorID']			 = $row['FVendorID'];
				$rowArray['bkg_total_amount']	 = $row['bkg_total_amount'];
				$rowArray['FVendorAmount']		 = $row['AssigbedCount'] > 1 ? BookingCab::getFirstVendorAmountByBkgId($row['bkg_id'], $row['FVendorID']) : $row['FVendorAmount'];
				$rowArray['LVendorAmount']		 = $row['LVendorAmount'];
				$rowArray['bkg_gozo_amount']	 = $row['bkg_gozo_amount'];
				$rowArray['LastAssigntype']		 = $row['LastAssigntype'];
				$rowArray['LastAssignedby']		 = $row['blg_admin_id'] != null ? " Admin Id:" . $row['blg_admin_id'] : "Vendor Id:" . $row['blg_vendor_id'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}


		$dataProvider = Booking::model()->dailylossReport($model, 'dataProvider');
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('dailyloss', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionProcessbooking()
	{
		$this->pageTitle = "Process Booking";
		$model			 = new Booking();
		if (!empty($_POST['Booking']))
		{
			$bookingData						 = Yii::app()->request->getParam('Booking');
			$date1								 = DateTimeFormat::DatePickerToDate($bookingData['bkg_pickup_date_date']);
			$time1								 = date('H:i:00', strtotime($bookingData['bkg_pickup_date_time']));
			$model->attributes					 = $bookingData;
			$model->bkg_pickup_date				 = $date1 . ' ' . $time1;
			$model->bkg_create_date				 = date('Y-m-d');
			$routes								 = [];
			$brtArray							 = array();
			$routeModel							 = new BookingRoute();
			$routes[0]['brt_from_city_id']		 = $model->bkg_from_city_id;
			$routes[0]['brt_to_city_id']		 = $model->bkg_to_city_id;
			$routes[0]['brt_pickup_date_date']	 = $date1;
			$routes[0]['brt_pickup_date_time']	 = $time1;
			$routes[0]['brt_pickup_datetime']	 = $model->bkg_pickup_date;
			$routeModel->attributes				 = $routes[0];
			array_push($brtArray, $routeModel);
			$model->bookingRoutes				 = $brtArray;
			$quote								 = new Quote();
			$quote->routes						 = $model->bookingRoutes;
			$quote->quoteDate					 = $model->bkg_create_date;
			$quote->pickupDate					 = $model->bkg_pickup_date;
			$quote->sourceQuotation				 = Quote::Platform_Admin;
			$quote->tripType					 = $model->bkg_booking_type;
			$quote->flexxi_type					 = 1;
			$quote->applyPromo					 = false;
//			$quote->suggestedPrice				 = 1;
			if ($quote->sourceQuotation == 1)
			{
				$quote->applyPromo = true;
			}
			$partnerId			 = Yii::app()->params['gozoChannelPartnerId'];
			$quote->partnerId	 = $partnerId;
			$quote->setCabTypeArr();
			$quotes				 = $quote->getQuote($model->bkg_vehicle_type_id, true, true, $checkBestRate);
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('processbooking', array('model' => $model, 'quote' => $quotes), false, $outputJs);
	}

	public function actionBookingReport()
	{

		$this->pageTitle		 = "Booking Report";
		$model					 = new Booking();
		$command				 = false;
		$picupDate1				 = $model->bkg_create_date1 = date('Y-m-d', strtotime('today - 29 days'));
		$picupDate2				 = $model->bkg_create_date2 = date('Y-m-d', strtotime('today'));
		if (!empty($_POST['Booking']))
		{
			$bookingData = Yii::app()->request->getParam('Booking');
			if ($bookingData['bkg_create_date1'] != '' && $bookingData['bkg_create_date2'] != '')
			{
				$picupDate1				 = $bookingData['bkg_create_date1'];
				$picupDate2				 = $bookingData['bkg_create_date2'];
				$model->bkg_create_date1 = $picupDate1;
				$model->bkg_create_date2 = $picupDate2;
				$dataProvider			 = $model->getBookingReport($command);
				$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
				$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
			}
		}
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$model					 = new Booking();
			$picupDate1				 = Yii::app()->request->getParam('export_bkg_create_date1');
			$picupDate2				 = Yii::app()->request->getParam('export_bkg_create_date2');
			$model->bkg_create_date1 = $picupDate1;
			$model->bkg_create_date2 = $picupDate2;
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"BookingReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "BookingReport_" . date('YmdHi') . ".csv";
			$foldername				 = Yii::app()->params['uploadPath'];
			$backup_file			 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$command = true;
			$rows	 = $model->getBookingReport($command);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [
				'Id',
				'Booking Id',
				'Pickup Date',
				'Base Amount',
				'Net Advance Amount',
				'Driver Allowance',
				'Total Gmv',
				'Service Tax',
				'Toll Tax',
				'State Tax',
				'Total Amount',
				'Status',
				'Company Name',
				'Cancel Reason'
			]);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['bkgId']				 = $row['bkgId'];
				$rowArray['bookigId']			 = $row['bookigId'];
				$rowArray['pickupDate']			 = $row['pickupDate'];
				$rowArray['baseAmount']			 = $row['baseAmount'];
				$rowArray['netAdvanceAmount']	 = $row['netAdvanceAmount'];
				$rowArray['driverAllowance']	 = $row['driverAllowance'];
				$rowArray['totalGmv']			 = $row['totalGmv'];
				$rowArray['serviceTax']			 = $row['serviceTax'];
				$rowArray['tollTax']			 = $row['tollTax'];
				$rowArray['stateTax']			 = $row['stateTax'];
				$rowArray['totalAmount']		 = $row['totalAmount'];
				$rowArray['bkgStatus']			 = $row['bkgStatus'];
				$rowArray['companyName']		 = $row['companyName'];
				$rowArray['cancelReason']		 = $row['cancelReason'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$this->render('bookingReport', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAttendanceReport()
	{
		$this->pageTitle = "Attendance Report";
		$model			 = new AttendanceStats();
		$request		 = Yii::app()->request;
		if ($request->getParam('AttendanceStats'))
		{
			$attendanceData			 = $request->getParam('AttendanceStats');
			$model->ats_create_date1 = $attendanceData['ats_create_date1'];
			$model->ats_create_date2 = $attendanceData['ats_create_date2'];
			$model->csrSearch		 = $attendanceData['csrSearch'];
			$model->teamList		 = $attendanceData['teamList'];
		}
		else
		{
			$model->ats_create_date1 = date('Y-m-d', strtotime('today - 1 days'));
			$model->ats_create_date2 = date('Y-m-d', strtotime('today'));
		}
		$dataProvider = $model->getAttendanceReport();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('attendanceReport', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAttendanceDetailsReport()
	{
		$this->pageTitle = "Attendance Details Report";
		$request		 = Yii::app()->request;
		$adm_id			 = trim($request->getParam('id'), "");
		$fromDate		 = trim($request->getParam('fromDate'), "");
		$toDate			 = trim($request->getParam('toDate'), "");
		$model			 = new AttendanceStats();
		if ($adm_id != '' && $fromDate != "" && $toDate != "")
		{
			$model->ats_create_date1 = $fromDate;
			$model->ats_create_date2 = $toDate;
			$model->ats_admin_id	 = $adm_id;
			$dataProvider			 = $model->getAttendanceDetailsReport();
			$dataProvider->setSort([
				'params' => array_filter($_GET + $_POST)]);
			$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		}
		else
		{
			throw new CHttpException(404, 'Something went wrong');
		}
		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('attendance_details_report', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	/**
	 * Vendor Locked Payment Report
	 */
	public function actionVendorLockedPayment()
	{
		$this->pageTitle = "Vendor Locked Payment Report";

		$model	 = new BookingSub();
		$data	 = Yii::app()->request->getParam('BookingSub');
		if ($data)
		{
			$model->vnd_code = $data['vnd_code'];
		}
		else
		{
			$model->vnd_code = '';
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"BlockedVendorPayments_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "BlockedVendorPayments_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->getVendorLockedPaymentsExport();
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Trip Id', 'Agent', 'Pickup Date', 'Vendor Id', 'Vendor Name', 'Vendor Code', 'Trip Vendor Amount', 'Reason']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['bkg_ids']			 = $row['bkg_ids'];
				$rowArray['bcb_id']				 = $row['bcb_id'];
				$rowArray['agt_company_names']	 = $row['agt_company_names'];
				$rowArray['bkg_pickup_dates']	 = $row['bkg_pickup_dates'];
				$rowArray['vnd_id']				 = $row['vnd_id'];
				$rowArray['vnd_name']			 = $row['vnd_name'];
				$rowArray['vnd_code']			 = $row['vnd_code'];
				$rowArray['bcb_vendor_amount']	 = $row['bcb_vendor_amount'];
				$rowArray['blg_desc']			 = $row['blg_desc'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}


		$dataProvider = $model->getVendorLockedPayments();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorLockedPayment', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	/**
	 * Driver Bonus Amount
	 */
	public function actionDriverBonus()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d");
			$model->drv_code	 = $data['drv_code'];
		}
		else
		{
			$model->from_date	 = date("Y-m-d");
			$model->to_date		 = date("Y-m-d");
			$model->drv_code	 = '';
		}
		$this->pageTitle = "Driver Bonus";
		$dataProvider	 = $model->driverBonusList();

		if (isset($_REQUEST['export_search']))
		{
			$search				 = Yii::app()->request->getParam('export_search');
			$drvCode			 = Yii::app()->request->getParam('export_drv_code');
			$fromDate			 = Yii::app()->request->getParam('export_from_date');
			$toDate				 = Yii::app()->request->getParam('export_to_date');
			$model->from_date	 = $fromDate;
			$model->to_date		 = $toDate;
			$model->drv_code	 = $drvCode;
			$rows				 = $model->driverBonusList($type				 = true);
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DriverBonusReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle				 = fopen("php://output", 'w');
			fputcsv($handle, ['Trip ID', 'Booking ID', 'Rating', 'Driver ID', 'Driver Name', 'Driver Code', 'Total Bonus Amount', 'Bank Name', 'Bank Branch', 'Beneficiary Name', 'Beneficiary Id', 'Bank Account No.', 'Bank Ifsc']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['bkg_bcb_id']			 = $row['bkg_bcb_id'];
				$rowArray['bkg_booking_id']		 = $row['bkg_booking_id'];
				$rowArray['rtg_customer_driver'] = $row['rtg_customer_driver'];
				$rowArray['drv_id']				 = $row['drv_id'];
				$rowArray['drv_name']			 = $row['drv_name'];
				$rowArray['drv_code']			 = $row['drv_code'];
				$rowArray['bonus_amount']		 = $row['bonus_amount'];
				$rowArray['bank_name']			 = $row['bank_name'];
				$rowArray['bank_branch']		 = $row['bank_branch'];
				$rowArray['beneficiary_name']	 = $row['beneficiary_name'];
				$rowArray['beneficiary_id']		 = $row['beneficiary_id'];
				$rowArray['bank_account_no']	 = $row['bank_account_no'];
				$rowArray['bank_ifsc']			 = $row['bank_ifsc'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			Yii::log("After IN TO OUT FILE query " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
			if (!$rows)
			{
				Yii::log("Can not export data " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
				die('Could not take data backup: ' . mysql_error());
			}
			else
			{
				Yii::log("Exported data successfully " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
			}
			exit;
		}

		$trans = DBUtil::beginTransaction();
		try
		{
			if (isset($_POST["import"]))
			{
				$success	 = false;
				$fileName	 = $_FILES["file"]["tmp_name"];
				if ($_FILES["file"]["size"] > 0)
				{
					$file	 = fopen($fileName, "r");
					$i		 = 0;
					$count	 = 0;
					while (($getData = fgetcsv($file, 10000, ",")))
					{
						if ($count > 0)
						{
							if ($getData[3] != '' && $getData[5] != '')
							{
								date('Y-m-d', strtotime($getData[6]));

								$bankLedger		 = 30;
								$remarks		 = trim($getData[7]);
								$driverId		 = trim($getData[5]);
								$paymentTypeId	 = 2;
								$amount			 = trim($getData[3]);
								$paymentDate	 = $getData[6];
								$success		 = AccountTransactions::model()->AddCsvdatatoAccounts($amount, $driverId, $paymentDate, $remarks, $bankLedger, $paymentTypeId, Accounting::AT_DRIVER, Accounting::LI_DRIVER);
								$i++;
							}
							if ($amount > 0)
							{
								// Send Notification
								$message = "Gozo has released bonus of Rs. " . $amount . " to you today. It normally takes between 2-24 hours for you to receive the amount in your bank account";
								//$payLoadData = ['vendorId' => $driverId, 'EventCode' => VendorsLog::PAYMENT_MADE];
								#$succ	 = AppTokens::model()->notifyVendor($operatorId, $payLoadData, $message, "Gozo has released payment.");
							}
						}
						$count++;
					}
					if ($success == true)
					{
						$totalRows	 = $i;
						$type		 = "success";
						$message	 = $totalRows . "&nbsp; CSV Data Imported into the Database.";
					}
					else
					{
						$type	 = "error";
						$message = "Problem in Importing CSV Data.";
						throw new Exception("Problem in Importing CSV Data ");
					}
				}
			}
			$success = DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			throw new Exception($e->getMessage());
		}

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('driverBonus', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Accounting Flag Closed By Admin Report
	 */
	public function actionAccountingFlagClosedReport()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d");
		}
		else
		{
			$model->from_date	 = date("Y-m-d");
			$model->to_date		 = date("Y-m-d");
		}
		$this->pageTitle = "Accounting Flag Closed By Admin List";
		$dataProvider	 = $model->accountingFlagClosedByAdminList();

		if (isset($_REQUEST['export_search']))
		{
			$search				 = Yii::app()->request->getParam('export_search');
			$fromDate			 = Yii::app()->request->getParam('export_from_date');
			$toDate				 = Yii::app()->request->getParam('export_to_date');
			$model->from_date	 = $fromDate;
			$model->to_date		 = $toDate;
			$rows				 = $model->accountingFlagClosedByAdminList($type				 = true);
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AccountingFlagClosingReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle				 = fopen("php://output", 'w');
			fputcsv($handle, ['Flag Closing Date', 'Admin Id', 'Admin Email', 'Admin Name', 'Total Flag Closed', 'Booking Ids']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['flagClosingDate'] = $row['flagClosingDate'];
				$rowArray['adminId']		 = $row['adminId'];
				$rowArray['adminEmail']		 = $row['adminEmail'];
				$rowArray['adminName']		 = $row['adminName'];
				$rowArray['totalFlagClosed'] = $row['totalFlagClosed'];
				$rowArray['bookingIds']		 = $row['bookingIds'] . ",";
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('accountingFlagClosed', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionDzppReport()
	{
		$this->pageTitle = "DZPP Report";
		$model			 = new DynamicZoneSurge();
		$request		 = Yii::app()->request;
		if ($request->getParam('DynamicZoneSurge'))
		{
			$arr					 = $request->getParam('DynamicZoneSurge');
			$model->dzs_fromzoneid	 = $arr['dzs_fromzoneid'];
			$model->dzs_tozoneid	 = $arr['dzs_tozoneid'];
			$model->dzs_booking_type = $arr['dzs_booking_type'];
			$model->dzs_scv_id		 = $arr['dzs_scv_id'];
			$model->dzs_regionid	 = $arr['dzs_regionid'];
			$model->dzs_zone_type	 = $arr['dzs_zone_type'];
			$model->dzs_state		 = $arr['dzs_state'];
		}
		else
		{
			$model->dzs_zone_type	 = [];
			$model->dzs_state		 = [];
		}
		$dataProvider	 = DynamicZoneSurge::getDZPPReport($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . (($outputJs ) ? "Partial" : "");
		$this->$method('dzppreport', array('dataProvider' => $dataProvider, 'model' => $model), false, $outputJs);
	}

	public function actionDzppDetailReport()
	{
		$this->pageTitle	 = "DZPP Detail Report";
		$request			 = Yii::app()->request;
		$rowIdentifier		 = trim($request->getParam('id'), "");
		$row				 = array();
		$row['regionId']	 = (int) substr($rowIdentifier, 1, 2);
		$row['fromZone']	 = (int) substr($rowIdentifier, 3, 5);
		$row['toZone']		 = (int) substr($rowIdentifier, 8, 5);
		$row['vehicleId']	 = (int) substr($rowIdentifier, 13, 3);
		$row['bookingType']	 = (int) substr($rowIdentifier, 16, 2);
		$dataProvider		 = DynamicZoneSurge::getDZPPDetailsReport($row);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . (($outputJs ) ? "Partial" : "");
		$this->$method('dzppreportDetails', array('dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionPaymentSummaryReport()
	{
		$this->pageTitle = "Payment Summary Report";
		$groupBy		 = 'date';
		$createDate1	 = date("Y-m-d", strtotime("-1 day"));
		$createDate2	 = date("Y-m-d", strtotime("-1 day"));
		$model			 = new PaymentGateway();
		$req			 = Yii::app()->request;
		$pgData			 = Yii::app()->request->getParam('PaymentGateway');

		if ($req->isPostRequest || $pgData != '')
		{
			$createDate1 = $pgData['trans_create_date1'];
			$createDate2 = $pgData['trans_create_date2'];
			$groupBy	 = $pgData['groupvar'];
			$ptpId		 = $pgData['apg_ptp_id'];
		}

		$model->trans_create_date1	 = $createDate1;
		$model->trans_create_date2	 = $createDate2;
		$model->apg_ptp_id			 = $ptpId;

		switch ($groupBy)
		{
			case "month":
				$diff	 = "-3 month";
				$errMsg	 = "Date range is greater than 2 months";
				break;
			case "week":
				$diff	 = "-7 week";
				$errMsg	 = "Date range is greater than 6 week";
				break;
			case "date":
			default:
				$diff	 = "-32 day";
				$errMsg	 = "Date range is greater than 31 days";
				break;
		}

		$diffMonth	 = strtotime($diff, strtotime($createDate2)) - strtotime($createDate1);
		$error		 = '';
		if ($diffMonth > 0)
		{
			$error = $errMsg;
			goto skipAll;
		}
		$params = [
			'from_date'	 => $createDate1,
			'to_date'	 => $createDate2,
		];

		$dataProvider = $model->paymentSummaryReport($params, $groupBy, $ptpId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		skipAll:
		$this->render('paymentSummaryReport', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'from_date'		 => $createDate1,
			'to_date'		 => $createDate2,
			'groupBy'		 => $groupBy,
			'error'			 => $error));
	}

	public function actionCsrLeadConversionReport()
	{
		$this->pageTitle = "CSR Lead Conversion Report";
		$groupBy		 = 'date';
		$createDate1	 = date("Y-m-d", strtotime("-15 day", time()));
		$createDate2	 = date('Y-m-d');
		$model			 = new ServiceCallQueue();
		$req			 = Yii::app()->request;
		$scqData		 = Yii::app()->request->getParam('ServiceCallQueue');
		$time			 = 0;
		$selfCreated	 = 0;
		if ($req->isPostRequest || $scqData != '')
		{
			$createDate1 = $scqData['fromDate'];
			$createDate2 = $scqData['toDate'];
			$groupBy	 = $scqData['groupvar'];
			$csrSearch	 = $scqData['csrSearch'];
			$time		 = $scqData['restrictCurrentTime'];
			$selfCreated = $scqData['selfCreated'];
			$teamLead	 = $scqData['adminId'];
			$isMobileApp = $scqData['isMobile'];
			$isGozonow	 = $scqData['isGozonow'];
			$weekDays	 = $scqData['weekDays'];
			$bkgType	 = $scqData['bkgtypes'];
			$regions	 = $scqData['regions'];
			$isAndroid	 = $scqData['isAndroid'];
			$isIOS		 = $scqData['isIOS'];
		}
		else
		{
			$bkgType = [];
		}

		$model->fromDate			 = $createDate1;
		$model->toDate				 = $createDate2;
		$model->csrSearch			 = $csrSearch;
		$model->restrictCurrentTime	 = $time;
		$model->selfCreated			 = $selfCreated;
		$model->adminId				 = $teamLead;
		$model->isMobile			 = $isMobileApp;
		$model->isGozonow			 = $isGozonow;
		$model->weekDays			 = $weekDays;
		$model->bkgtypes			 = $bkgType;
		$model->regions				 = $regions;
		$model->isAndroid			 = $scqData['isAndroid'];
		$model->isIOS				 = $scqData['isIOS'];

		switch ($groupBy)
		{
			case "date":
				$diff	 = "-15 day";
				$errMsg	 = "Date range is greater than 15 days";
				break;
			case "month":
				$diff	 = "-3 month";
				$errMsg	 = "Date range is greater than 2 months";
				break;
			case "week":
				$diff	 = "-7 week";
				$errMsg	 = "Date range is greater than 6 week";
				break;
			case "hour":
			default:
				$diff	 = "-3 day";
				$errMsg	 = "Date range is greater than 2 days";
				break;
		}

		$diffMonth	 = strtotime($diff, strtotime($createDate2)) - strtotime($createDate1);
		$error		 = '';
		if ($diffMonth > 0)
		{
			$error = $errMsg;
			goto skipAll;
		}
		$params			 = [
			'from_date'				 => $createDate1,
			'to_date'				 => $createDate2,
			'restrictCurrentTime'	 => $time,
			'selfCreated'			 => $selfCreated,
			'teamLead'				 => $teamLead,
			'isMobile'				 => $isMobileApp,
			'isGozonow'				 => $isGozonow,
			'weekDays'				 => $weekDays,
			'bkgTypes'				 => $bkgType,
			'regions'				 => $regions,
			'isAndroid'				 => $isAndroid,
			'isIOS'					 => $isIOS,
		];
//print_r($params) ; die;
		$dataProvider	 = $model->csrLeadConversionReport($params, $groupBy, $csrSearch);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		skipAll:
		$this->render('csrLeadConversionReport', array('model'			 => $model, 'dataProvider'	 => $dataProvider,
			'from_date'		 => $createDate1,
			'to_date'		 => $createDate2,
			'groupBy'		 => $groupBy,
			'error'			 => $error));
		//Yii::app()->end();
	}

	public function actioncsrPerformanceReport()
	{
		$this->pageTitle		 = "Lead CSR Performance Report";
		$followUps				 = new ServiceCallQueue();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;

		$req = Yii::app()->request;

		if ($req->getParam('ServiceCallQueue'))
		{
			$arr					 = $req->getParam('ServiceCallQueue');
			$date1					 = $arr['from_date'];
			$fromdate				 = new DateTime($date1);
			$followUps->from_date	 = $fromdate->format('Y-m-d');
			$date2					 = $arr['to_date'];
			$todate					 = new DateTime($date2);
			$followUps->to_date		 = $todate->format('Y-m-d');
		}

		$dataProvider = ServiceCallQueue::model()->csrLeadPerformanceReport($followUps);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('csrPerformanceReport', array('followUps' => $followUps, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionZoneProfitability()
	{
		$this->pageTitle	 = "Zone Profitability Report";
		$model				 = new BookingInvoice();
		$req				 = Yii::app()->request;
		$error				 = "";
		$from_date			 = date('Y-m-d');
		$to_date			 = date("Y-m-d", strtotime("+1 day", time()));
		$model->from_date	 = $from_date;
		$model->to_date		 = $to_date;
		$model->bkgZoneType	 = 1;

		if ($req->getParam('BookingInvoice'))
		{
			$arr = $req->getParam('BookingInvoice');
			/* $fromdate	 = strtotime($arr['from_date']);
			  $todate		 = strtotime($arr['to_date']);
			  $datediff	 = $todate - $fromdate;
			  $difference	 = round($datediff / (60 * 60 * 24));
			  if ($difference > 7)
			  {
			  $error = "date range should be within 7 days";
			  goto skipAll;
			  } */

			if ($arr['from_date'] == '' && $arr['create_from_date'] == '')
			{
				$error = "Please select pickup/ create date range";
				goto skipAll;
			}
			elseif ($arr['from_date'] != '' && ((strtotime($arr['to_date']) - strtotime($arr['from_date'])) / (60 * 60 * 24)) >= 7)
			{
				$error = "Pickup date range should be within 7 days";
				goto skipAll;
			}
			elseif ($arr['create_from_date'] != '' && ((strtotime($arr['create_to_date']) - strtotime($arr['create_from_date'])) / (60 * 60 * 24)) >= 7)
			{
				$error = "Create date range should be within 7 days";
				goto skipAll;
			}

			$model->from_date			 = $arr['from_date'];
			$model->to_date				 = $arr['to_date'];
			$model->create_from_date	 = $arr['create_from_date'];
			$model->create_to_date		 = $arr['create_to_date'];
			$model->bkgZoneType			 = $arr['bkgZoneType'];
			$model->bkgTypes			 = $arr['bkgTypes'];
			$model->sourcezone			 = $arr['sourcezone'];
			$model->region				 = $arr['region'];
			$model->state				 = $arr['state'];
			$model->assignCountDrop		 = $arr['assignCountDrop'];
			$model->assignCount			 = $arr['assignCount'];
			$model->lossCountDrop		 = $arr['lossCountDrop'];
			$model->lossCount			 = $arr['lossCount'];
			$model->netMarginDrop		 = $arr['netMarginDrop'];
			$model->netMargin			 = $arr['netMargin'];
			$model->b2cbookings			 = isset($arr['b2cbookings'][0]);
			$model->mmtbookings			 = $arr['mmtbookings'];
			$model->nonAPIPartner		 = $arr['nonAPIPartner'];
			$model->excludeAT			 = isset($arr['excludeAT'][0]);
			$model->bkg_vehicle_type_id	 = $arr['bkg_vehicle_type_id'];
//bivBkg->
		}

		$dataProvider = $model->getZoneProfitability();

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		skipAll:

		$this->render('zoneProfitability', array('model' => $model, 'dataProvider' => $dataProvider, 'error' => $error));
	}

	public function actionDispatchPerformance()
	{
		$this->pageTitle = "Dispatch Performance Report";
		$model			 = new ServiceCallQueue();
		$req			 = Yii::app()->request;
		$error			 = "";

		$from_date			 = date("Y-m-d", strtotime("-1 day", time()));
		$to_date			 = date("Y-m-d");
		$model->from_date	 = $from_date;
		$model->to_date		 = $to_date;

		if ($req->getParam('ServiceCallQueue'))
		{
			$arr = $req->getParam('ServiceCallQueue');

			if ($arr['from_date'] == '' && $arr['to_date'] == '')
			{
				$error = "Please select assigned date range";
				goto skipAll;
			}
			elseif ($arr['from_date'] != '' && ((strtotime($arr['to_date']) - strtotime($arr['from_date'])) / (60 * 60 * 24)) >= 15)
			{
				$error = "Pickup date range should be within 7 days";
				goto skipAll;
			}

			$model->from_date		 = $arr['from_date'];
			$model->to_date			 = $arr['to_date'];
			$model->adminId			 = $arr['adminId'];
			$model->bkg_pickup_date1 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $arr['bkg_pickup_date2'];
			$model->region			 = $arr['region'];
			$model->assignMode		 = $arr['assignMode'];
			$model->isManual		 = $arr['isManual'];
			$model->isCritical		 = $arr['isCritical'];
		}


		$dataProvider = $model->getDispatchPerformance();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		skipAll:
		$this->render('dispatchPerformance', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionAppNotRequired()
	{
		$this->pageTitle		 = "Driver app not required";
		$model					 = new BookingLog();
		$req					 = Yii::app()->request;
		$from_date				 = date("Y-m-d", strtotime("-1 day", time()));
		$to_date				 = date("Y-m-d");
		$model->bkg_pickup_date1 = $from_date;
		$model->bkg_pickup_date2 = $to_date;

		if ($req->getParam('BookingLog'))
		{
			$arr					 = $req->getParam('BookingLog');
			$model->bkg_pickup_date1 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $arr['bkg_pickup_date2'];
			$model->vendor_name		 = $arr['vendor_name'];
		}

		$dataProvider = $model->getAppNotRequired();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('appNotRequired', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionPenaltySummary()
	{
		$orderby = 'date';
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d") . " 00:00:00";
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d") . " 23:59:59";
			$orderby			 = $data['groupvar'];
		}
		else
		{
			$date1				 = $model->from_date	 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$date2				 = $model->to_date		 = date('Y-m-d') . " 23:59:59";
		}
		$this->pageTitle = " Penalty Summary";
		$dataProvider	 = $model->getPenaltySummary($orderby);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('penaltySummary', array('model' => $model, 'dataProvider' => $dataProvider, 'orderby' => $orderby,));
	}

	public function actionPickup()
	{
		echo "This report is moved to <a href='/report/financial/pickupSummary'>Pickup Summary Report</a>";
		exit;

		$this->pageTitle = "Pickup Report";
		$model			 = new Booking;
		/* @var $modelsub BookingSub */
		$modelsub		 = new BookingSub();
		if (isset($_REQUEST['Booking']))
		{
			$arr			 = Yii::app()->request->getParam('Booking');
			$arrBookingTrail = Yii::app()->request->getParam('BookingTrail');
			$date1			 = $arr['bkg_create_date1'];
			$date2			 = $arr['bkg_create_date2'];
			$from			 = $arr['bkg_from_city_id'];
			$to				 = $arr['bkg_to_city_id'];
			$vendor			 = $arr['bcb_vendor_id'];
			$platform		 = $arrBookingTrail['bkg_platform'];
			$agent			 = $arr['bkg_agent_id'];
			$status			 = $arr['bkg_status'];
			$tripId			 = $arr['trip_id'];
			$bkgType		 = $arr['bkgtypes'];
		}
		else
		{
			$date2		 = DateTimeFormat::DateToLocale(date());
			$date1		 = DateTimeFormat::DateToLocale(date());
			$from		 = '';
			$to			 = '';
			$vendor		 = '';
			$platform	 = '';
			$agent		 = '';
			$bkgType	 = [];
		}
		$model->bkgtypes				 = $bkgType;
		$model->trip_id					 = $tripId;
		$model->bkg_status				 = $status;
		$model->bkg_create_date1		 = $date1;
		$model->bkg_create_date2		 = $date2;
		$model->bkg_from_city_id		 = $from;
		$model->bkg_to_city_id			 = $to;
		$model->bcb_vendor_id			 = $vendor;
		$model->bkgTrail->bkg_platform	 = $platform;
		$model->bkg_agent_id			 = $agent;
		$modelsub->bkg_agent_id			 = $agent;
		$date1							 = DateTimeFormat::DatePickerToDate($date1);
		$date2							 = DateTimeFormat::DatePickerToDate($date2);

		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']) && isset($_REQUEST['export_from_city2']) && isset($_REQUEST['export_to_city2']) && isset($_REQUEST['export_vendor2']) && isset($_REQUEST['export_platform2']))
		{
			$fromDate				 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from2'));
			$toDate					 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to2'));
			$fromCity				 = Yii::app()->request->getParam('export_from_city2');
			$toCity					 = Yii::app()->request->getParam('export_to_city2');
			$bkgVendor				 = Yii::app()->request->getParam('export_vendor2');
			$bkgPlatform			 = Yii::app()->request->getParam('export_platform2');
			$bkgAgent				 = Yii::app()->request->getParam('export_agent2');
			$bkgStatus				 = Yii::app()->request->getParam('export_status');
			$bkgTripId				 = Yii::app()->request->getParam('export_trip_id');
			$bkgType				 = Yii::app()->request->getParam('export_booking_type');
			$bkgType				 = (!empty($bkgType[0])) ? $bkgType : [];
			$modelsub->bkg_agent_id	 = $bkgAgent;
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"GeneralPickupReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "reportbooking" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername				 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
			$backup_file			 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$type			 = 'command';
			$rows			 = $modelsub->pickupGeneralReport($fromDate, $toDate, $fromCity, $toCity, $bkgVendor, $bkgPlatform, $bkgStatus, $type, $bkgTripId, $bkgType);
			$status			 = Booking::model()->getBookingStatus();
			$bookingType	 = Booking::model()->getBookingType();
			$bookingPlatform = Booking::model()->booking_platform;
			$handle			 = fopen("php://output", 'w');

			fputcsv($handle, [
				'Booking ID',
				'Partner Booking Id',
				'Partner Name',
				'Booking Type',
				'Cab Type',
				'Total Distance',
				'Status',
				'Booking Date/Time',
				'Pickup Date/Time',
				'Amount',
				'Base Fare',
				'Discount',
				'Extra Discount Amount',
				'Toll Taxes',
				'Extra Toll Taxes',
				'Total Toll Taxes',
				'State Taxes',
				'Extra State Taxes',
				'Total State Taxes',
				'Driver Allowance',
				'Convenience Charges',
				'Parking Charges',
				'Additional Charges',
				'Airport Entry Fee',
				'Extra KM Charges',
				'Extra KM',
				'Extra Minutes Charges',
				'Extra Minutes',
				'GST',
				'Advance Received',
				'Credit Applied',
				'Amount Due',
				'Driver Collected',
				'Cancel Charge',
				'Refund',
				'Cancellation Policy Type',
				'Cancellation Date/Time',
				'Cancellation Reason',
				'Cancellation Remarks',
				'Partner Wallet',
				'Partner Payable',
				'Partner Commission',
				'Consumer Name',
				'Trip Vendor Amount',
				'From City',
				'To City',
				'Driver Arrived',
				'Trip Started',
				'Trip Stop',
				'Customer No Show'
			]);
			foreach ($rows as $row)
			{
				$referenceCode = $row['bkg_agent_ref_code'];
				if ($row['bkg_agent_id'] == Config::get('transferz.partner.id') && is_numeric($row['bkg_agent_ref_code']))
				{
					$partnerCode	 = TransferzOffers::getOffer($row['bkg_agent_ref_code']);
					$referenceCode	 = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode;
				}
				$policyType	 = CancellationPolicyDetails::model()->findByPk($row['bkg_cancel_rule_id'])->cnp_code; //CancellationPolicy::getPolicyType($row['bkg_cancel_rule_id'], $row['bkg_agent_id']);
				$invoiceId	 = '';
				if ($row['bkg_status'] == 6 || $row['bkg_status'] == 7)
				{
					$invoiceId = BookingInvoice::getInvoiceId($row['bkg_id'], $row['bkg_pickup_date']);
				}
				$rowArray								 = array();
				$rowArray['bkg_booking_id']				 = $row['bkg_booking_id'];
				$rowArray['bkg_agent_ref_code']			 = $referenceCode;
				$rowArray['agent_name']					 = $row['agent_name'];
				$rowArray['bkg_booking_type']			 = $bookingType[$row['bkg_booking_type']];
				$rowArray['desired_cab']				 = $row['serviceClass'];
				$rowArray['bkg_trip_distance']			 = $row['bkg_trip_distance'] . ' Km';
				$rowArray['bkg_status']					 = $status[$row['bkg_status']];
				$rowArray['bkg_create_date']			 = ($row['bkg_create_date'] != '' || $row['bkg_create_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
				$rowArray['bkg_pickup_date']			 = ($row['bkg_pickup_date'] != '' || $row['bkg_pickup_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
				$rowArray['bkg_total_amount']			 = $row['bkg_total_amount'];
				$rowArray['bkg_base_amount']			 = $row['bkg_base_amount'];
				$rowArray['bkg_discount_amount']		 = $row['bkg_discount_amount'];
				$rowArray['bkg_extra_discount_amount']	 = $row['bkg_extra_discount_amount'];
				$rowArray['bkg_toll_tax']				 = $row['bkg_toll_tax'];
				$rowArray['bkg_extra_toll_tax']			 = $row['bkg_extra_toll_tax'];
				$rowArray['bkg_total_toll_tax']			 = $row['total_toll_tax'];
				$rowArray['bkg_state_tax']				 = $row['bkg_state_tax'];
				$rowArray['bkg_extra_state_tax']		 = $row['bkg_extra_state_tax'];
				$rowArray['total_state_tax']			 = $row['total_state_tax'];
				$rowArray['drv_allowance']				 = $row['drv_allowance'];
				$rowArray['bkg_convenience_charge']		 = $row['bkg_convenience_charge'];
				$rowArray['bkg_parking_charge']			 = $row['bkg_parking_charge'] > 0 ? $row['bkg_parking_charge'] : '0';
				$rowArray['bkg_additional_charge']		 = $row['bkg_additional_charge'] > 0 ? $row['bkg_additional_charge'] : '0';
				$rowArray['bkg_airport_entry_fee']		 = $row['bkg_airport_entry_fee'];
				$rowArray['bkg_extra_km_charge']		 = $row['bkg_extra_km_charge'] > 0 ? $row['bkg_extra_km_charge'] : '0';
				$rowArray['bkg_extra_km']				 = $row['bkg_extra_km'] > 0 ? $row['bkg_extra_km'] : 0;
				$rowArray['bkg_extra_total_min_charge']	 = $row['bkg_extra_total_min_charge'] > 0 ? $row['bkg_extra_total_min_charge'] : 0;
				$rowArray['bkg_extra_min']				 = $row['bkg_extra_min'] > 0 ? $row['bkg_extra_min'] : 0;
				$rowArray['bkg_service_tax']			 = $row['bkg_service_tax'];
				$rowArray['bkg_advance_amount']			 = $row['bkg_advance_amount'] > 0 ? $row['bkg_advance_amount'] : 0;
				$rowArray['bkg_credits_used']			 = $row['bkg_credits_used'] > 0 ? $row['bkg_credits_used'] : '0';
				$rowArray['bkg_due_amount']				 = $row['bkg_due_amount'] > 0 ? $row['bkg_due_amount'] : '0';
				$rowArray['bkg_vendor_collected']		 = $row['bkg_vendor_collected'] > 0 ? $row['bkg_vendor_collected'] : '0';
				$rowArray['cancelCharge']				 = $row['bkg_status'] == 9 ? $row['cancelCharge'] : '0';
				$rowArray['bkg_refund_amount']			 = $row['bkg_refund_amount'] > 0 ? $row['bkg_refund_amount'] : '0';
				$rowArray['cancellationPolicyType']		 = $policyType;
				$rowArray['cancellation_datetime']		 = ($row['bkg_status'] == 9) ? date("d/m/Y H:i:s", strtotime($row['cancellation_datetime'])) : '-';
				$rowArray['cnr_reason']					 = $row['cnr_reason'];
				$rowArray['cancel_remarks']				 = $row['cancel_remarks'];
				$rowArray['bkg_corporate_credit']		 = $row['adtPartnerWallet'];
				$rowArray['payableAmount']				 = $row['partnerPayableAmount'];
				$rowArray['bkg_partner_commission']		 = $row['adtCommission'] < 0 ? ($row['adtCommission'] * -1) : $row['bkg_partner_commission'];
				$rowArray['bkg_user_name']				 = $row['bkg_user_fname'] . " " . $row['bkg_user_lname'];
				$rowArray['bcb_vendor_amount']			 = $row['bcb_vendor_amount'];
				$rowArray['from_city']					 = $row['fromCity'];
				$rowArray['to_city']					 = $row['toCity'];
				$rowArray['bkg_arrived_for_pickup']		 = ($row['bkg_arrived_for_pickup'] == 1) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_arrive_time'])) : '-';
				$rowArray['bkg_ride_start']				 = ($row['bkg_ride_start'] == 1) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_start_time'])) : '-';
				$rowArray['bkg_ride_complete']			 = ($row['bkg_ride_complete'] == 1) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_end_time'])) : '-';
				$rowArray['bkg_is_no_show']				 = ($row['bkg_is_no_show'] == 1) ? date("d/m/Y H:i:s", strtotime($row['bkg_no_show_time'])) : '-';

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);

			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}

			exit;
		}
		else
		{
			$dataProvider = $modelsub->pickupGeneralReport($date1, $date2, $from, $to, $vendor, $platform, $status, 'data', $tripId, $bkgType);
		}
		$this->render('report_pickup', array('dataProvider' => $dataProvider, 'model' => $model, 'trailModel' => new BookingTrail()));
	}

	public function actionDnr()
	{
		$this->pageTitle	 = "Report Driver App Not Requirement";
		$model				 = new BookingLog();
		$from_date			 = date("Y-m-01");
		$to_date			 = date('Y-m-d');
		$model->from_date	 = $from_date;
		$model->to_date		 = $to_date;
		$model->groupBy		 = 'executive';
		$req				 = Yii::app()->request;
		if ($req->getParam('BookingLog'))
		{
			$data				 = $req->getParam('BookingLog');
			$model->from_date	 = $data['from_date'];
			$model->to_date		 = $data['to_date'];
			$model->groupBy		 = $data['groupBy'];
		}

		$dataProvider = $model->DriverAppNotRequiredDetails();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_dnr', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionDailyConfirmation()
	{
		$url			 = Yii::app()->createUrl('report/financial/DailyConfirmation');
		$this->redirect($url);
		exit;
		$this->pageTitle = "Daily Confirmation Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		$orderby		 = 'hour';
		if ($request->getParam('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$orderby		 = $arr['groupvar'];
			$weekDays		 = $arr['weekDays'];
			$fromConfirmDate = $arr['bkg_create_date1'];
			$toConfirmDate	 = $arr['bkg_create_date2'];
			$bkgType		 = $arr['bkgtypes'];
			$region			 = $arr['bkg_region'];
			$local			 = $arr['local'];
			$outstation		 = $arr['outstation'];
			$restricted		 = $arr['restricted'];
			$isGozonow		 = $arr['isGozonow'];
			$isMobileApp	 = $arr['isMobile'];

			$model->local		 = $arr['local'];
			$model->outstation	 = $arr['outstation'];
		}
		else
		{
			$fromConfirmDate = date("Y-m-d", strtotime("-2 day", time()));
			$toConfirmDate	 = date('Y-m-d');
			$bkgType		 = [];
		}


		$model->bkgtypes		 = $bkgType;
		$model->bkg_create_date1 = $fromConfirmDate;
		$model->bkg_create_date2 = $toConfirmDate;
		$model->bkg_region		 = $region;
		$model->weekDays		 = $weekDays;
		$model->local			 = $arr['local'];
		$model->outstation		 = $arr['outstation'];
		$model->restricted		 = $arr['restricted'];
		$model->isGozonow		 = $arr['isGozonow'];
		$model->isMobile		 = $arr['isMobile'];

		switch ($orderby)
		{
			case "date":
				$diff	 = "-30 day";
				$errMsg	 = "Date range is not greater than 30 days";
				break;
			case "month":
				$diff	 = "-6 month";
				$errMsg	 = "Date range is not greater than 4 months";
				break;
			case "week":
				$diff	 = "-12 week";
				$errMsg	 = "Date range is not greater than 12 week";
				break;
			case "hour":
			default:
				$diff	 = "-7 day";
				$errMsg	 = "Date range is not greater than 7 days";
				break;
		}

		$diffMonth	 = strtotime($diff, strtotime($toConfirmDate)) - strtotime($fromConfirmDate);
		$error		 = '';
		if ($diffMonth > 0)
		{
			$error = $errMsg;
			goto skipAll;
		}

		$params			 = [
			'fromConfirmDate'	 => $fromConfirmDate,
			'toConfirmDate'		 => $toConfirmDate,
			'bkgTypes'			 => $bkgType,
			'region'			 => $region,
			'weekDays'			 => $weekDays,
			'local'				 => $local,
			'outstation'		 => $outstation,
			'restricted'		 => $restricted,
			'isGozonow'			 => $isGozonow,
			'mobileApp'			 => $isMobileApp,
		];
		Logger::profile("Params Init Done");
		$dataProvider	 = Booking::getDailyConfirmationData($params, $orderby);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		Logger::profile("Data Provider Initialized");
		skipAll:
		$this->render('dailyconfirmaiton', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'orderby'		 => $orderby,
			'error'			 => $error)
		);
	}

	public function actionshowBooking1()
	{
		$this->pageTitle = "Show dispatch Booking List";
		$bkgIds			 = Yii::app()->request->getParam('bkgIds');
		$dataProvider	 = Booking::bookingDetailsByIds($bkgIds);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('showbookingids', array('dataProvider' => $dataProvider), false, false);
	}

	public function actionOperatorTrackingView()
	{
		$this->pageTitle = "Operator Tracking View";
		$bkgId			 = Yii::app()->request->getParam('id');
		$bModel			 = Booking::model()->findByPk($bkgId);

		/* @var $model PartnerApiTracking */
		$model					 = new OperatorApiTracking();
		$model->oat_booking_id	 = $bkgId;
		$dataProvider			 = $model->getOperatorTrackingDataByBkgId();

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('operatortrackingview', array('model' => $model, 'bModel' => $bModel, 'dataProvider' => $dataProvider));
	}

	public function actionshowBooking()
	{
		$this->pageTitle	 = "Show dispatch Booking List";
		$admId				 = Yii::app()->request->getParam('admId');
		$assignFromDate		 = Yii::app()->request->getParam('assignFromDate');
		$assignToDate		 = Yii::app()->request->getParam('assignToDate');
		$pickupDate1		 = Yii::app()->request->getParam('pickupDate1');
		$pickupDate2		 = Yii::app()->request->getParam('pickupDate2');
		$assignMode			 = Yii::app()->request->getParam('assignMode');
		$region				 = Yii::app()->request->getParam('region');
		$nonManualAssigned	 = Yii::app()->request->getParam('nonManualAssigned');
		$isManual			 = Yii::app()->request->getParam('isManual');
		$isCritical			 = Yii::app()->request->getParam('isCritical');
		$isLossAssigned		 = Yii::app()->request->getParam('isLossAssigned');
		$isProfitAssigned	 = Yii::app()->request->getParam('isProfitAssigned');

		$params = [
			'admId'				 => $admId,
			'assignFromDate'	 => $assignFromDate,
			'assignToDate'		 => $assignToDate,
			'pickupDate1'		 => $pickupDate1,
			'pickupDate2'		 => $pickupDate2,
			'assignMode'		 => $assignMode,
			'nonManualAssigned'	 => $nonManualAssigned,
			'isManual'			 => $isManual,
			'isCritical'		 => $isCritical,
			'isLossAssigned'	 => $isLossAssigned,
			'isProfitAssigned'	 => $isProfitAssigned,
			'region'			 => $region];

		$dataProvider = Booking::bookingListByDispatch($params);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_dispatch_booking', ['dataProvider' => $dataProvider], false, false);
	}
	
	public function actionViewBookingIds()
	{
		$agtid			 = Yii::app()->request->getParam('agtid');
		$date1			 = Yii::app()->request->getParam('date1');
		$date2			 = Yii::app()->request->getParam('date2');
		$model			 = new Booking();
		$dataProvider	 = $model->getByAgentId($agtid, $date1, $date2);
		$this->renderPartial('viewBookingIds', ['dataProvider' => $dataProvider], false, true);
	}

}
