<?php

class VehicleController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete1', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
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
			['allow', 'actions' => ['cabdetails'], 'roles' => ['CabDetailsReport']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('cabdetails'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('louRequired'),
				'roles'		 => array('GeneralReport'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('availabilitylist'),
				'users'		 => array('@'),
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

	public function actionCabdetails()
	{
		$row = Report::getRoleAccess(42);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Cab Details Report";
		$model			 = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr	 = Yii::app()->request->getParam('Booking');
			$date1	 = $arr['bkg_create_date1'];
			$date2	 = $arr['bkg_create_date2'];
		}
		else
		{
			$date2	 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('15 days')));
			$date1	 = DateTimeFormat::DateToLocale(date());
		}
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);
		$dataProvider			 = $model->cabDetailsReport($date1, $date2);

		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to2'));
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CabDetailsReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
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

			$status	 = Booking::model()->getBookingStatus();
			$where	 = " AND date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration HOUR)) BETWEEN '$fromDate' AND '$toDate' order by date(DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration HOUR))";
			$sql	 = "SELECT bkg_booking_id,c1.cty_name as from_city, c2.cty_name as to_city,vct.vct_label,bkg_status,DATE_ADD(bkg_pickup_date,INTERVAL bkg_trip_duration HOUR)
						FROM booking 
							INNER JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id = scv.scv_id
							INNER JOIN vehicle_category vct ON vct.vct_id = scv.scv_vct_id
							INNER JOIN service_class sc ON scv.scv_scc_id = sc.scc_id
							left join cities c1 on bkg_from_city_id = c1.cty_id left join cities c2 on bkg_to_city_id = c2.cty_id 
							WHERE bkg_status IN ('2','3','5') and bkg_active=1 " . $where . "";

			$command = Yii::app()->db->createCommand($sql);
			$rows	 = $command->queryAll();
			$handle	 = fopen("php://output", 'w');
			$i		 = 0;
			fputcsv($handle, ['Booking ID', 'From City', 'To City', 'Cab Type', 'Status', 'Cab Free Time']);
			foreach ($rows as $row)
			{
				if ($i >= 0)
				{
					$row['bkg_status'] = $status[$row['bkg_status']];
				}
				$row1 = array_values($row);
				fputcsv($handle, $row1);
				$i++;
			}
			fclose($handle);

			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}

			exit;
		}
		$this->render('report_cab_details', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionLouRequired()
	{
		$row = Report::getRoleAccess(72);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$pagetitle		 = "Cabs with LOU required";
		$this->pageTitle = $pagetitle;
		$model			 = new VendorVehicle();
		$pageSize		 = Yii::app()->params['listPerPage'];
		$data			 = Yii::app()->request->getParam('VendorVehicle');
		$model->search	 = $data['search'];
		if (isset($_REQUEST['vhc_code']))
		{
			$data['search']	 = Yii::app()->request->getParam('vhc_code');
			$rows			 = VendorVehicle::model()->getLouRequiredData($data['search'], DBUtil::ReturnType_Query);
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LouRequiredReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle			 = fopen("php://output", 'w');
			fputcsv($handle, ['Vehicle Code', 'Vehicle Number', 'Vendor Code']);
			foreach ($rows as $row)
			{
				$rowArray				 = array();
				$rowArray['vhc_code']	 = $row['vhc_code'];
				$rowArray['vhc_number']	 = $row['vhc_number'];
				$rowArray['vnd_code']	 = $row['vnd_code'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider	 = VendorVehicle::model()->getLouRequiredData($data['search']);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('lourequired', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row), null, $outputJs);
	}

	public function actionAvailabilitylist()
	{
		$row = Report::getRoleAccess(7);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = 'Cab Availability List';
		$create_date1	 = $create_date2	 = "";
		$source			 = Yii::app()->request->getParam('source');
		$vndid			 = Yii::app()->request->getParam('vndid', 0);
		$showListOnly	 = false;

		$model = new CabAvailabilities('search');
		if ($source == 'mycall')
		{
			$showListOnly	 = true;
			$create_date1	 = date('Y-m-d');
			$create_date2	 = date('Y-m-d', strtotime('+2 MONTH'));
			$vnd_id			 = $vndid;
		}
		$request = Yii::app()->request;
		if ($request->getParam('CabAvailabilities'))
		{
			$model->attributes	 = $request->getParam('CabAvailabilities');
			$arr				 = $model->attributes;
			$create_date1		 = $model->from_date;
			$create_date2		 = $model->to_date;
			$from_city			 = $model->from_city;
			$to_city			 = $model->to_city;
			$vnd_id				 = $model->vnd_id;
		}
		if ($create_date1 == "" && $create_date2 == "")
		{
			$create_date2	 = DateTimeFormat::DateToLocale(date('Y-m-d'));
			$create_date1	 = DateTimeFormat::DatePickerToDate($create_date1);
			$create_date2	 = DateTimeFormat::DatePickerToDate($create_date2);
		}

		if ($request->getParam('export') == true)
		{
			$create_date1	 = $request->getParam('from_date');
			$create_date2	 = $request->getParam('to_date');
			$vnd_id			 = $request->getParam('vnd_id');
			$from_city		 = $request->getParam('from_city');
			$to_city		 = $request->getParam('to_city');
			$rows			 = $model->fetchList($from_city, $to_city, $vnd_id, $create_date1, $create_date2, 'command');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VehicleAvailabilityReport_{$create_date1}_{$create_date2}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle			 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Name', 'Cab Number', 'Cab Model', 'Cab Type', 'From City', 'To City', 'Driver Name', 'Date/Time']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['vnd_name']		 = $row['vnd_name'];
				$rowArray['vhc_number']		 = $row['vhc_number'];
				$rowArray['vht_make_model']	 = $row['vht_make_model'];
				$rowArray['cab_type']		 = $row['cab_type'];
				$rowArray['from_city']		 = $row['from_city'];
				$rowArray['to_city']		 = $row['to_city'];
				$rowArray['drv_name']		 = $row['drv_name'];
				$rowArray['cav_date_time']	 = $row['cav_date_time'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$model->from_city						 = $from_city;
		$model->to_city							 = $to_city;
		$model->vnd_id							 = $vnd_id;
		$dataProvider							 = $model->fetchList($from_city, $to_city, $vnd_id, $create_date1, $create_date2);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderAuto('cabavailabilitylist', array('dataProvider' => $dataProvider, 'model' => $model, 'qry' => $qry, 'showListOnly' => $showListOnly, 'roles' => $row));
	}

}
