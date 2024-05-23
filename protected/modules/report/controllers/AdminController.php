<?php

class AdminController extends Controller
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
			'postOnly + delete1', // we only allow deletion via POST request
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('manualAssignmentCount', 'AttendanceReport', 'csrPerformanceReport'),
				'roles'		 => array('GeneralReport'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionManualAssignmentCount()
	{
		$row = Report::getRoleAccess(64);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "CSR Manual Assignment - Report";
		$model			 = new Booking();
		$reportReady	 = false;
		if (isset($_REQUEST['Booking']))
		{
			$arr1					 = Yii::app()->request->getParam('Booking');
			$reportReady			 = ($arr1['bkg_pickup_date1'] != '' && $arr1['bkg_pickup_date2'] != '') ? true : false;
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'];
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
		$this->render('manual_assignment_count', array('model' => $model, 'reportReady' => $reportReady, 'roles' => $row));
	}

	public function actionAttendanceReport()
	{
		$row = Report::getRoleAccess(87);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->ats_create_date1 = Yii::app()->request->getParam('exportcreatedate1');
			$model->ats_create_date2 = Yii::app()->request->getParam('exportcreatedate2');
			$model->csrSearch		 = empty(Yii::app()->request->getParam('csrSearch')) ? "" : explode(",", Yii::app()->request->getParam("csrSearch"));
			$model->teamList		 = Yii::app()->request->getParam('teamList');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AttendanceReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "AttendanceReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->getAttendanceReport(DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Gozen', 'From Date', 'To Date', 'Total Hours']);
			foreach ($rows as $row)
			{
				$teamList	 = Teams::getMultipleTeamid($row['adm_id']);
				$teamsName	 = "";
				foreach ($teamList as $team)
				{
					$teamsName .= $team['tea_name'] . ",";
				}
				$teamsName = rtrim($teamsName, ",");

				$rowArray				 = array();
				$rowArray['gozen']		 = $row['gozen'] . "(" . $teamsName . ")";
				$rowArray['fromDate']	 = $row['fromDate'];
				$rowArray['toDate']		 = $row['toDate'];
				$rowArray['totalHrs']	 = $row['totalHrs'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getAttendanceReport();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('attendanceReport', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actioncsrPerformanceReport()
	{
		$row = Report::getRoleAccess(89);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$followUps->from_date	 = Yii::app()->request->getParam('from_date');
			$followUps->to_date		 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CsrPerformanceReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "CsrPerformanceReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = ServiceCallQueue::model()->csrLeadPerformanceReport($followUps, '', DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['CSR Name', 'Total Days', 'Quote Created', 'Booking Confirmed', 'Total Gozo Amount ']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['CSR_Name']			 = $row['CSR_Name'];
				$rowArray['Total_Days']			 = $row['Total_Days'];
				$rowArray['Quote_Created']		 = $row['Quote_Created'];
				$rowArray['Booking_Confirmed']	 = $row['Booking_Confirmed'];
				$rowArray['Total_Gozo_Amount']	 = $row['Total_Gozo_Amount'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = ServiceCallQueue::model()->csrLeadPerformanceReport($followUps);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('csrPerformanceReport', array('followUps' => $followUps, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	public function actionDemoLink()
	{
		$row = Report::getRoleAccess(76);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Tracking Details";
		$model			 = new Booking();
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
				$jsonArr	 = array(
					"params" => $arr,
					"keys"	 => array
						(
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
					)
				);
				$filename	 = "Bookingtrackdetails_" . date('YmdHis') . ".csv";
				$expiryDate	 = Date('Y-m-d', strtotime('+15 days'));
				ReportExport::CreateRequest($jsonArr, 76, $filename, $expiryDate, 1, UserInfo::getUserId());
			}
		}

		$dataProvider = BookingTrail::getBookingTrackDetails($arr, 'Command');
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('bookingtrackdetails', array('model' => $model, 'dataProvider' => $dataProvider, 'checked' => $checked, 'roles' => $row));
	}

	public function actionExportList($qry = [])
	{
		$this->pageTitle = "Report Export List";
		$model			 = new ReportExport();
		$request		 = Yii::app()->request;
		if ($request->getParam('ReportExport'))
		{
			$ReportExportData			 = $request->getParam('ReportExport');
			$model->create_date1		 = $ReportExportData['create_date1'];
			$model->create_date2		 = $ReportExportData['create_date2'];
			$model->rpe_isFile_created	 = $ReportExportData['rpe_isFile_created'];
		}
		else
		{
			$model->create_date1		 = date('Y-m-d', strtotime('today - 1 days'));
			$model->create_date2		 = date('Y-m-d', strtotime('today'));
			$model->rpe_isFile_created	 = 2;
		}
		$dataProvider = $model->getExportList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
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

}
