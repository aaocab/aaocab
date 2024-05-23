<?php

class RouteController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $ven_date_type;
	public $ven_to_date;
	public $ven_from_date;

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
			array('allow', 'actions' => array('demandreport', '90DCalendar'), 'users' => array('*')),
			['allow', 'actions' => ['list'], 'roles' => ['surgeList']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionDemandReport()
	{
		$row = Report::getRoleAccess(6);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Demand Report";
		$model			 = new Booking;
		$routeModel		 = new Route;
		if (isset($_REQUEST['Booking']))
		{
			$arr				 = Yii::app()->request->getParam('Booking');
			$date				 = $arr['bkg_pickup_date'];
			$region				 = implode(',', $arr['bkg_region']);
			$sourcezone			 = implode(',', $arr['sourcezone']);
			$destinationzone	 = implode(',', $arr['destinationzone']);
			$bkg_vehicle_type_id = implode(',', $arr['bkg_vehicle_type_id']);
		}
		else
		{
			$date = DateTimeFormat::DateToLocale(date('Y-m-d'));
		}
		$model->bkg_pickup_date		 = $date;
		$model->bkg_region			 = $region;
		$model->sourcezone			 = $sourcezone;
		$model->destinationzone		 = $destinationzone;
		$date						 = DateTimeFormat::DatePickerToDate($date);
		$model->bkg_vehicle_type_id	 = $bkg_vehicle_type_id;

		$dataProvider = $routeModel->demandReport($date, $region, $sourcezone, $destinationzone, $bkg_vehicle_type_id);

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$type = 'command';
		if ($_REQUEST['export'] == true)
		{
			$fromDate = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from'));

			$expRegion		 = Yii::app()->request->getParam('bkg_region2');
			$expSource		 = Yii::app()->request->getParam('sourcezone2');
			$expDestination	 = Yii::app()->request->getParam('destinationzone2');
			$expVtype		 = Yii::app()->request->getParam('bkg_vehicle_type_id2');

			$data = $routeModel->demandReport($fromDate, $expRegion, $expSource, $expDestination, $expVtype, $type);

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"RouteDemandReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, array("Date", "From Zone", "To Zone", "UP COUNT", "DOWN COUNT", "UP Confirmed", "DOWN Confirmed"));
			foreach ($data as $row)
			{
				$rowArray						 = array();
				$rowArray['bkg_pickup_date']	 = $row['bkg_pickup_date'];
				$rowArray['from_zone']			 = $row['from_zone'];
				$rowArray['to_zone']			 = $row['to_zone'];
				$rowArray['bkg_vehicle_type_id'] = $row['bkg_vehicle_type_id'];
				$rowArray['up_count']			 = $row['up_count'];
				$rowArray['down_count']			 = $row['down_count'];
				$rowArray['up_confirmed']		 = $row['up_confirmed'];
				$rowArray['down_confirmed']		 = $row['down_confirmed'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			Yii::log("After IN TO OUT FILE query " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
			if (!$row1)
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

		$this->render('demandreport', array('model'			 => $model,
			'dataProvider'	 => $dataProvider, 'roles'			 => $row)
		);
	}

	public function actionList()
	{
		$row = Report::getRoleAccess(33);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Price surge quoted situation report";
		$model			 = new BookingPriceFactor();
		$bkgModel		 = new Booking();

		$bpf_pickup_date1	 = $_REQUEST['BookingPriceFactor']['bpf_pickup_date1'];
		$bpf_pickup_date2	 = $_REQUEST['BookingPriceFactor']['bpf_pickup_date2'];
		if ($bpf_pickup_date1 != '' && $bpf_pickup_date2 != '')
		{
			$date1	 = DateTimeFormat::DatePickerToDate($bpf_pickup_date1);
			$date2	 = DateTimeFormat::DatePickerToDate($bpf_pickup_date2);
		}
		else
		{
			$date1	 = date('Y-m-d');
			$date2	 = date('Y-m-d', strtotime("+1 days"));
		}
		if (isset($_REQUEST['Booking']))
		{
			$arr			 = Yii::app()->request->getParam('Booking');
			$sourcezone		 = implode(',', $arr['sourcezone']);
			$destinationzone = implode(',', $arr['destinationzone']);
		}
		$model->bpf_pickup_date1	 = $date1;
		$model->bpf_pickup_date2	 = $date2;
		$bkgModel->sourcezone		 = $arr['sourcezone'];
		$bkgModel->destinationzone	 = $arr['destinationzone'];

		if (isset($_REQUEST['bpf_pickup_date2']) && isset($_REQUEST['bpf_pickup_date1']))
		{
			$date1			 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('bpf_pickup_date1'));
			$date2			 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('bpf_pickup_date2'));
			$sourcezone		 = implode(',', Yii::app()->request->getParam('sourcezone'));
			$destinationzone = implode(',', Yii::app()->request->getParam('destinationzone'));

			$rows	 = $model->getList($date1, $date2, $sourcezone, $destinationzone, DBUtil::ReturnType_Query);
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RouteReport_{$date1}_{$date2}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Pickup Date', 'From Zone', 'To Zone', 'From City', 'TO City', 'Total Booking', 'count of regular', 'count of manual', 'count of manual+ddbp', 'count of dtbp', 'count of ddbp route-route', 'count of DDBP zone-zone', 'count of DDBP zone-state']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['pickupDate']			 = date("d/m/Y", strtotime($row['pickupDate']));
				$rowArray['from_zone']			 = $row['from_zone'];
				$rowArray['to_zone']			 = $row['to_zone'];
				$rowArray['fromCity']			 = $row['fromCity'];
				$rowArray['toCity']				 = $row['toCity'];
				$rowArray['totalBooking']		 = $row['totalBooking'];
				$rowArray['regular']			 = $row['regular'];
				$rowArray['manual']				 = $row['manual'];
				$rowArray['manualddbp']			 = $row['manualddbp'];
				$rowArray['dtbp']				 = $row['dtbp'];
				$rowArray['countOfroute_route']	 = $row['countOfroute_route'];
				$rowArray['countOfzone_zone']	 = $row['countOfzone_zone'];
				$rowArray['countOfzone_state']	 = $row['countOfzone_state'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}



		$dataProvider	 = $model->getList($date1, $date2, $sourcezone, $destinationzone);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider, 'bkgmodel' => $bkgModel, 'roles' => $row), false, $outputJs);
	}

	public function action90DCalendar()
	{
		$row = Report::getRoleAccess(13);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "View 90D Calendar";
		$request		 = Yii::app()->request;
		$pastDays		 = 30;
		$nextDays		 = 90;
		$model			 = new CalendarEvent();
		$model->pastDays = $pastDays;
		$model->nextDays = $nextDays;
		if ($request->getParam('CalendarEvent'))
		{
			$arr			 = $request->getParam('CalendarEvent');
			$pastDays		 = $arr['pastDays'];
			$nextDays		 = $arr['nextDays'];
			$model->pastDays = $pastDays;
			$model->nextDays = $nextDays;
		}
		$dataProvider	 = CalendarEvent::get90DayCalendar($pastDays, $nextDays);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('90dayviewcalendar', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row), null, $outputJs);
	}

}
