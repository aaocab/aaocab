<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class AreaController extends Controller
{

	public $layout = 'admin1';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + flush', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('cityCoverage', 'sourcezones', 'destinationzones'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', 'actions' => array('routeAnalysis', 'zonesupplydensity', 'zeroInventory', 'zoneWiseCountBooking', 'stickyCarCount', 'stickyVendorCount', 'tierCount', 'RegionVendorwiseDriverAppusage', 'profitability', 'bookingCountByZone', 'routeLowConversion'), 'roles' => array('GeneralReport')),
			['allow', 'actions' => ['regionperf'], 'roles' => ['vendorList']],
			['allow', 'actions' => ['sourcezones'], 'roles' => ['SourceZonesReport']],
			['allow', 'actions' => ['destinationzones'], 'roles' => ['DestinationZonesReport']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'mbkg2'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actioncityCoverage()
	{
		$row = Report::getRoleAccess(12);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "City Coverage Report";
		$request		 = Yii::app()->request;
		$model			 = new Vendors();
		if ($request->getParam('Vendors'))
		{
			$arr				 = $request->getParam('Vendors');
			$region				 = ($arr['vnd_region'] != '') ? $arr['vnd_region'] : '';
			$zone				 = ($arr['vnd_zone'] != '') ? $arr['vnd_zone'] : '';
			$city				 = ($arr['vnd_cty'] != '') ? $arr['vnd_cty'] : '';
			$model->vnd_region	 = $region;
			$model->vnd_zone	 = $zone;
			$model->vnd_city	 = $city;
		}
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$region	 = ($_REQUEST['export_vnd_region'] != '') ? $_REQUEST['export_vnd_region'] : '';
			$zone	 = ($_REQUEST['export_vnd_zone'] != '') ? $_REQUEST['export_vnd_zone'] : '';
			$city	 = ($_REQUEST['export_vnd_cty'] != '') ? $_REQUEST['export_vnd_cty'] : '';
		}
		$args			 = array('region' => $region, 'zone' => $zone, 'city' => $city);
		$dataProvider	 = $model->getCityCoverageReport($args);
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CityCoverage_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "cityCoverage" . date('YmdHi') . ".csv";
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
			$rows	 = $model->getCityCoverageReport($args, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['City', 'Zone', 'Region', 'Operators Homezone', 'Frozen Vendors', 'Inactive Vendors', 'Operators Serving zone', 'Frozen Vendors', 'Inactive Vendors']);
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$rowArray								 = array();
					$rowArray['cty_name']					 = $row['cty_name'];
					$rowArray['home_zone_name']				 = $row['home_zone_name'];
					$rowArray['region']						 = $row['region'];
					$rowArray['opt_homezone']				 = $row['opt_homezone'];
					$rowArray['opt_homezone_freeze']		 = $row['opt_homezone_freeze'];
					$rowArray['opt_homezone_inactive']		 = $row['opt_homezone_inactive'];
					$rowArray['opt_servingzone']			 = $row['opt_servingzone'];
					$rowArray['opt_servingzone_freeze']		 = $row['opt_servingzone_freeze'];
					$rowArray['opt_servingzone_inactive']	 = $row['opt_servingzone_inactive'];
					$row1									 = array_values($rowArray);
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
		$this->render('report_city_coverage', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionRegionperf()
	{
		$row = Report::getRoleAccess(17);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Region Perf Report";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new Vendors();
		$region			 = '';
		if (isset($_REQUEST['Vendors']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Vendors');
			$arr				 = $model->attributes;
			$create_date1		 = $model->vnd_create_date1;
			$create_date2		 = $model->vnd_create_date2;
			$region				 = $model->vnd_region;
		}
		else
		{
			$create_date1	 = DateTimeFormat::DateToLocale(date('Y-m-01'));
			$create_date2	 = DateTimeFormat::DateToLocale(date('Y-m-d'));
		}
		$model->vnd_create_date1 = $create_date1;
		$model->vnd_create_date2 = $create_date2;
		$model->vnd_region		 = $region;
		$create_date1			 = DateTimeFormat::DatePickerToDate($create_date1);
		$create_date2			 = DateTimeFormat::DatePickerToDate($create_date2);
		if (isset($_REQUEST['export_from']) && isset($_REQUEST['export_to']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to'));
			$region		 = Yii::app()->request->getParam('export_region');
			$data		 = $model->getRegionPerfReport('command', $fromDate, $toDate, $region);
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"PerfReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen("php://output", 'w');
				fputcsv($handle, array("Region", "Vendor", "Rating", "Bookings Assigned", "Bookings Assigned(advance paid)", "Bookings Assigned(post-paid/COD)", "Bookings total cancellations", "Bookings cancellations(advance)", "Bookings cancellations(COD)", "Amount", "Vendor Amount"));
				foreach ($data as $d)
				{
					$bookingAmt	 = $d['booking_amount'] > 0 ? $d['booking_amount'] : '0';
					$vendorAmt	 = $d['vendor_amount'] > 0 ? $d['vendor_amount'] : '0';
					fputcsv($handle, array($d['region'],
						$d['vnd_name'],
						$d['vnd_overall_rating'],
						$d['bookings_assigned'],
						$d['bookings_assigned_advance'],
						$d['bookings_assigned_cod'],
						$d['bookings_cancelled'],
						$d['bookings_cancelled_advance'],
						$d['booking_cancelled_cod'],
						$bookingAmt,
						$vendorAmt));
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = $model->getRegionPerfReport('', $create_date1, $create_date2, $region);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_regionperf', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry, 'roles'			 => $row)
		);
	}

	public function actionSourcezones()
	{
		$row = Report::getRoleAccess(22);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Top 10 Source Zones";
		//$model = new Booking();
		/* @var $model BookingSub */
		$modelsub		 = new BookingSub();
		$recordSet		 = $modelsub->businessSourceZones();
		$this->render('businesssource', ['records' => $recordSet, 'type' => 's', 'roles' => $row]);
	}

	public function actionDestinationzones()
	{
		$row = Report::getRoleAccess(23);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Top 10 Destination Zones";
		/* @var $model BookingSub */
		$modelsub		 = new BookingSub();
		$recordSet		 = $modelsub->businessSourceZones();
		$this->render('businesssource', ['records' => $recordSet, 'type' => 'd', 'roles' => $row]);
	}

	public function actionProfitability()
	{
		$row = Report::getRoleAccess(56);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('zone_profitability', array('dataProvider' => $dataProvider, 'cabProfit' => $cabProfit, 'serviceTier' => $serviceTier, 'model' => $model, 'roles' => $row));
	}

	public function actionZoneSupplyDensity()
	{
		$row = Report::getRoleAccess(59);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->zon_id	 = $zon_id			 = Yii::app()->request->getParam('zon_id');
			$date1			 = date('Y-m-d', strtotime("-90 days"));
			$date2			 = date('Y-m-d');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ZoneSupplyDensityReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename		 = "ZoneSupplyDensityReport_" . date('Ymdhis') . ".csv";
			$foldername		 = Yii::app()->params['uploadPath'];
			$backup_file	 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Zones::model()->getZoneSupplyDensity($model, $date1, $date2, $zon_id, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Zone', 'Count of active vendors serving', 'Count of home-zone vendors']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['zon_name']			 = $row['zon_name'];
				$rowArray['active_vendors']		 = $row['active_vendors'];
				$rowArray['home_zone_vendors']	 = $row['home_zone_vendors'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$date1			 = date('Y-m-d', strtotime("-90 days"));
		$date2			 = date('Y-m-d');
		$dataProvider	 = Zones::model()->getZoneSupplyDensity($model, $date1, $date2, $zon_id);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render("zonesupplydensity", ['dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row], false);
	}

	public function actionZeroInventory()
	{
		$row = Report::getRoleAccess(60);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Zero Inventory Zone";
		$model			 = new Booking();

		$date1			 = date('Y-m-d', strtotime("-180 days"));
		$date2			 = date('Y-m-d');
		$dataProvider	 = Zones::model()->getZeroinventory($model, $date1, $date2);
		Logger::trace("checking data provider values - " . json_encode($dataProvider));
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render("zeroinventory", ['dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row], false);
	}

	public function actionZoneWiseCountBooking()
	{
		$row = Report::getRoleAccess(61);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('zone_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionStickyCarCount()
	{
		$row = Report::getRoleAccess(67);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('sticky_car_count_report', array('model' => $model, 'stickyCarScore' => $stickyCarScore, 'roles' => $row));
	}

	public function actionStickyVendorCount()
	{
		$row = Report::getRoleAccess(68);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$ledgerId				 = Yii::app()->request->getParam("ledgerId");
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('bkg_pickup_date1');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('bkg_pickup_date2');
			$zones					 = Yii::app()->request->getParam('sourcezone');
			$regions				 = Yii::app()->request->getParam('bkg_region');
			$states					 = Yii::app()->request->getParam('bkg_state');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"StickyVendorCount_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "StickyVendorCount_" . date('Ymdhis') . ".csv";
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
			$rows	 = VendorStats::model()->getStickyCount($model->bkg_pickup_date1, $model->bkg_pickup_date2, $zones, $regions, $states, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Name', 'Region', 'Home Zone', 'State', 'Approved Cars', 'Completed Trips']);
			foreach ($rows as $row)
			{
				$zones		 = States::model()->getRegionByZoneId();
				$zonid		 = array_search($row['vnp_home_zone'], array_column($zones, 'zon_id'));
				$zoneName	 = $GLOBALS['zon_name'];
				unset($GLOBALS['zon_name']);

				$rowArray							 = array();
				$rowArray['vnd_name']				 = $row['vnd_name'];
				$rowArray['Region']					 = States::model()->findRegionName($zones[$zonid]['stt_zone'][0]);
				$rowArray['vnp_home_zone']			 = $zones[$zonid]['zon_name'];
				$rowArray['state']					 = $row['state'];
				$rowArray['vrs_approve_car_count']	 = $row['vrs_approve_car_count'];
				$rowArray['Count_Trips']			 = $row['Count_Trips'];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = VendorStats::model()->getStickyCount($model->bkg_pickup_date1, $model->bkg_pickup_date2, $zones, $regions, $states);
		$this->render('sticky_vendor_count_report', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionTierCount()
	{
		$row = Report::getRoleAccess(69);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
		{
			$vnp_home_zone_export	 = Yii::app()->request->getParam("vnp_home_zone_export", false);
			$zonRegion_export		 = Yii::app()->request->getParam('zonRegion_export', false);
			$vnpModel				 = new VendorPref('search');
			if ($vnp_home_zone_export !== false || $zonRegion_export !== false)
			{
				$vnpModel->zonRegion	 = $zonRegion_export;
				$vnpModel->vnp_home_zone = $vnp_home_zone_export;
			}

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VehicleTierCountByZone_" . date('Ymd_his') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "VendorCountTierList_" . date('YmdHi') . ".csv";
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
			$rows	 = $vnpModel->getVehicleTierCountByZone(DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Zone Name', 'Value(Vendor Count|Car Count)', 'Value+(Vendor Count|Car Count)', 'Plus(Vendor Count|Car Count)', 'Select(Vendor Count|Car Count)']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['zoneName']		 = $row['zon_name'];
				$rowArray['valueTier']		 = $row['cntValueVendors'] . '/' . $row['cntValueVehicles'];
				$rowArray['valuePlusTier']	 = $row['cntValuePlusVendors'] . '/' . $row['cntValuePlusVehicles'];
				$rowArray['plusTier']		 = $row['cntPlusVendors'] . '/' . $row['cntPlusVehicles'];
				$rowArray['selecttier']		 = $row['cntSelectVendors'] . '/' . $row['cntSelectVehicles'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = $vnpModel->getVehicleTierCountByZone(DBUtil::ReturnType_Provider);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorcounttierlist', array('dataProvider' => $dataProvider, 'vnpmodel' => $vnpModel, 'roles' => $row));
	}

	public function actionRegionVendorwiseDriverAppusage()
	{
		$row = Report::getRoleAccess(80);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('bkg_pickup_date1');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('bkg_pickup_date2');
			$model->bcb_vendor_id	 = Yii::app()->request->getParam('bcb_vendor_id');
			$model->bkg_region		 = Yii::app()->request->getParam('bkg_region');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RegionVendorwiseDriverAppusage_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "RegionVendorwiseDriverAppusage_" . date('Ymdhis') . ".csv";
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
			$rows	 = BookingTrack::model()->getVendorwiseAppusageDetails($arr, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Region', 'Date Range', 'Vendor Name', 'Not Logged In', 'Not Left', '	Not Arrived',
				'Not Started', 'Not Ended', 'Start API Fail', 'End API Fail', 'Booking Count', 'Not Logged In Count',
				'Left Count', 'Arrived Count', 'Start Count', 'End Count', 'Arrived Percent', 'Start Percent',
				'End Percent', 'Arrived API Percent', 'Start API Percent', 'End API Percent']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['month']				 = $row['month'];
				$rowArray['OW_count']			 = $row['OW_count'];
				$rowArray['RT_count']			 = $row['RT_count'];
				$rowArray['AT_count']			 = $row['AT_count'];
				$rowArray['DR_count']			 = $row['DR_count'];
				$rowArray['LT_count']			 = $row['LT_count'];
				$rowArray['Average_rating_OW']	 = $row['Average_rating_OW'];
				$rowArray['Average_rating_RT']	 = $row['Average_rating_RT'];
				$rowArray['Average_rating_AT']	 = $row['Average_rating_AT'];
				$rowArray['Average_rating_DR']	 = $row['Average_rating_DR'];
				$rowArray['Average_rating_LT']	 = $row['Average_rating_LT'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = BookingTrack::model()->getVendorwiseAppusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorwiseappusage', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry, 'roles'			 => $row)
		);
	}

	public function actionMbkg2()
	{
		$this->layout						 = "head1";
		Yii::app()->params['enableTracking'] = false;
		$setPass							 = "11112019";
		$chkSession							 = $_COOKIE['mbkg'];
		$error								 = 0;
		$request							 = $_REQUEST['sort'];
		$sourceZonecount					 = 0;
		$destZonecount						 = 0;
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('mbkg', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
			if ($error > 0)
			{
				goto result;
			}
		}
		$fromDate	 = date("Y-m-d") . ' 00:00:00';
		$toDate		 = date("Y-m-d") . ' 23:59:59';
		/* @var $booksub BookingSub */
		$booksub	 = new BookingSub();
		if ($request == 'cntBkg1')
		{
			$data = $booksub->getSourceZoneTodaysBooking();
		}
		else if ($request == 'cntBkg2')
		{
			$data1 = $booksub->getDestZoneTodaysBooking();
		}
		else
		{
			$data	 = $booksub->getSourceZoneTodaysBooking();
			$data1	 = $booksub->getDestZoneTodaysBooking();
		}
		$dataProvider	 = $data[0];
		$sourceZonecount = $data[1];
		$datetime		 = $data[2];
		$dataProvider1	 = $data1[0];
		$destZonecount	 = $data1[1];

		result:
		$this->render('zone_wise_report', array('dataProvider'	 => $dataProvider, 'count'			 => $sourceZonecount, 'count1'		 => $destZonecount,
			'dataProvider1'	 => $dataProvider1,
			'error'			 => $error,
			'lastRefeshDate' => $datetime,
			'chkSession'	 => $chkSession), false, true);
	}

	public function actionRouteAnalysis()
	{
		$row = Report::getRoleAccess(107);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Route analysis Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1				 = $data['bkg_create_date1'];
				$createDate2				 = $data['bkg_create_date2'];
				$model->bkg_create_date1 = $createDate1;
				$model->bkg_create_date2 = $createDate2;
			}
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}


		if ($_REQUEST['export'] == true)
		{
			$model->bkg_create_date1	 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2	 = Yii::app()->request->getParam('to_date');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RouteAnalysisReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "RouteAnalysisReport_" . date('YmdHi') . ".csv";
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
			$rows	 = Route::getMarginByRoutes($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['totalInquiry', 'B2CInquiry', 'B2BInquiry', 'totalConfirm', 'B2CConfirm', 'B2BConfirm', 'fromCity', 'toCity', 'RouteName', 'Distance', 'CreateDate']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'totalInquiry'	 => $row['totalInquiry'],
					'B2CInquiry'	 => $row['B2CInquiry'],
					'B2BInquiry'	 => $row['B2BInquiry'],
					'totalConfirm'	 => $row['totalConfirm'],
					'B2CConfirm'	 => $row['B2CConfirm'],
					'B2BConfirm'	 => $row['B2BConfirm'],
					'fromCity'		 => $row['fromCity'],
					'toCity'		 => $row['toCity'],
					'rut_name'		 => $row['rut_name'],
					'distance'		 => $row['distance'],
					'createDate'	 => $row['createDate'],
				);
				$row1		 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);

			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}

			exit;
		}
		$dataProvider	 = Route::getMarginByRoutes($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('route_analysis_list', array('dataProvider'	 => $dataProvider,
			'model'		 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionBookingCountByZone()
	{
		$row = Report::getRoleAccess(112);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Zone wise booking Count";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$createDate1				 = $data['bkg_pickup_date1']. " 00:00:00";
				$createDate2				 = $data['bkg_pickup_date2']. " 23:59:59";
				$model->bkg_pickup_date1 = $createDate1;
				$model->bkg_pickup_date2 = $createDate2;
			}
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}

		if ($_REQUEST['export'] == true)
		{
			$pickUps->bkg_pickup_date1	 = Yii::app()->request->getParam('from_date');
			$pickUps->bkg_pickup_date2	 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ZonewiseBookingReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename			 = "LossAssignment" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername			 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
			$backup_file		 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Zones::getBookingByZone($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Count', 'PickupDate', 'Zone Name']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'Count'			 => $row['cnt'],
					'Pickup Date'	 => $row['PickupDate'],
					'Zone Name'		 => $row['zon_name']
				);
				$row1		 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}
		$dataProvider	 = Zones::getBookingByZone($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('zone_booking_count', array(
			'dataProvider'	 => $dataProvider,
			'model'		 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionRouteLowConversion()
	{
		$row = Report::getRoleAccess(116);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$model					 = new Booking();
		$this->pageTitle		 = "Routes with low conversion";
		
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;

		$bkgTypes	 = null;
		$req		 = Yii::app()->request;
		if ($req->getParam('Booking'))
		{
			$arr					 = $req->getParam('Booking');
			$date1					 = $arr['bkg_create_date1'];
			$date2					 = $arr['bkg_create_date2'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkgtypes		 = $arr['bkgtypes'];
			$model->bkg_create_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_create_date2 = $todate->format('Y-m-d') . " 23:59:59";
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('-15 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d') . " 23:59:59";
		}
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$bkgType	 = Yii::app()->request->getParam('export_bkgtype');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RouteLowConversion" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "RouteLowConversion" . date('YmdHi') . ".csv";
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
			$model->bkg_create_date1 = $date1;
			$model->bkg_create_date2 = $date2;
			$model->bkgtypes		 = ($bkgType != '') ? explode(',', $bkgType) : null;

			$rows	 = Booking::model()->routeWithLogConversion($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['From City Name', 'To City Name', 'Count', 'Percentage Served', 'Percentage Local', 'Percentage OS',
				'Percentage Conversion', 'Percentage Fulfilment', 'Count Created', 'Count Quoted',
				'Count Completed', 'Count RT', 'Count OW', 'Count AT', 'Count DR', 'Count Local', 'First Booking Create Date',
				'Last Booking Create Date']);
			foreach ($rows as $data)
			{
				$rowArray							 = array();
				$rowArray['fromCityName']			 = $data['fromCityName'];
				$rowArray['toCityName']				 = $data['toCityName'];
				$rowArray['cntInquired']			 = $data['cntInquired'];
				$rowArray['pct_served']				 = $data['pct_served'];
				$rowArray['pct_local']				 = $data['pct_local'];
				$rowArray['pct_OS']					 = $data['pct_OS'];
				$rowArray['pct_conversion']			 = $data['pct_conversion'];
				$rowArray['pct_fulfilment']			 = $data['pct_fulfilment'];
				$rowArray['cntCreated']				 = $data['cntCreated'];
				$rowArray['cntQuoted']				 = $data['cntQuoted'];
				$rowArray['cntCompleted']			 = $data['cntCompleted'];
				$rowArray['cntRT']					 = $data['cntRT'];
				$rowArray['cntOW']					 = $data['cntOW'];
				$rowArray['cntAT']					 = $data['cntAT'];
				$rowArray['cntDR']					 = $data['cntDR'];
				$rowArray['cntLocal']				 = $data['cntLocal'];
				$rowArray['firstBookingCreateDate']	 = $data['firstBookingCreateDate'];
				$rowArray['lastBookingCreateDate']	 = $data['lastBookingCreateDate'];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->routeWithLogConversion($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('routelowconversion', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, true);
	}

}
