<?php

class DriverController extends Controller
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
			['allow', 'actions' => ['accountlist', 'csrApproveList'], 'users' => ['@']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('unapprovedCabdriver'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('driverBonus', 'driverAppUsage', 'driverAppNotUsed'),
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

	public function actionAccountList()
	{
		$ledgerId		 = Yii::app()->request->getParam("ledgerId");
		$this->pageTitle = "Account Transaction List";
		$qry			 = [];
		$date1			 = '';
		$date2			 = '';
		$model			 = new AccountTransDetails('search');
		if (isset($_REQUEST['AccountTransDetails']))
		{
			$arr	 = Yii::app()->request->getParam('AccountTransDetails');
			$date1	 = DateTimeFormat::DatePickerToDate($arr['trans_date1']);
			$date2	 = DateTimeFormat::DatePickerToDate($arr['trans_date2']);
			$qry	 = [];
			foreach ($arr as $k => $v)
			{
				$model->$k = $v;
			}
		}
		else
		{
			$date1	 = date('Y-m-d', strtotime("-90 days"));
			$date2	 = date('Y-m-d');
		}
		$model->trans_date1	 = DateTimeFormat::DateToLocale($date1);
		$model->trans_date2	 = DateTimeFormat::DateToLocale($date2);
		$model->resetScope();
		$dataProvider		 = AccountTransDetails::getdriverAccountTransactionsList($date1, $date2, $ledgerId);
		$this->render('accountlist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionCsrApproveList()
	{
		$row = Report::getRoleAccess(9);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Approved Car / Driver By Member";
		$model			 = new Drivers();
		if (isset($_REQUEST['Drivers']))
		{
			$arr						 = Yii::app()->request->getParam('Drivers');
			$model->approve_from_date	 = $arr['approve_from_date'];
			$model->approve_to_date		 = $arr['approve_to_date'];
		}
		else
		{
			$model->approve_from_date	 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-8 days')));
			$model->approve_to_date		 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-1 day')));
		}
		
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$ledgerId		     = Yii::app()->request->getParam("ledgerId");
			$model->approve_from_date	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('approve_from_date'));
			$model->approve_to_date		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('approve_to_date'));
			
			$date1				 = $model->approve_from_date;
			$date2				 = $model->approve_to_date;

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CsrApproveList_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "CsrApproveList_" . date('Ymdhis') . ".csv";
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
			$rows	 = Drivers::model()->carDriverApproveList($date1, $date2);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Member', 'Cars Approved', 'Drivers Approved']);
			$carCnt		 = $driverCnt	 = 0;
			foreach ($rows as $row)
			{
				$carCnt		 = ($carCnt + $row['totalCarApprove']);
				$driverCnt	 = ($driverCnt + $row['toatalDrvApprove']);
				$rowArray						 = array();
				$rowArray['csr']				 = $row['csr'];
				$rowArray['totalCarApprove']	 = $row['totalCarApprove'];
				$rowArray['toatalDrvApprove']	 = $row['toatalDrvApprove'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			$rowArr							 = array();
			$rowArr['total']				 = 'Total';
			$rowArr['totalCarcnt']			 = $carCnt;
			$rowArr['totalDriverCount']		 = $driverCnt;
			$rows							 = array_values($rowArr);
			fputcsv($handle, $rows);
			fclose($handle);
			exit;
		}

		$date1				 = DateTimeFormat::DatePickerToDate($model->approve_from_date);
		$date2				 = DateTimeFormat::DatePickerToDate($model->approve_to_date);
		$driverDataProvider	 = Drivers::model()->carDriverApproveList($date1, $date2);
		//$driverDataProvider->getPagination()->params = array_filter($_GET + $_POST);
		//$driverDataProvider->getSort()->params = array_filter($_GET + $_POST);
		$this->render('csrapprove', array('driverDataProvider' => $driverDataProvider, 'model' => $model, 'roles'			 => $row));
	}

	public function actionUnapprovedCabdriver()
	{
		$row = Report::getRoleAccess(30);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Cab/Driver Unapproved";
		$type			 = "Car";
		$model			 = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr		 = Yii::app()->request->getParam('Booking');
			$date1		 = $arr['bkg_create_date1'];
			$date2		 = $arr['bkg_create_date2'];
			$usedTime	 = $arr['used_time'];
			$entityType	 = $arr['entity_type'];
			if (($entityType == 1) ? $type		 = "Driver" : $type		 = "Car")
				;
		}
		else
		{
			$date1		 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-30 days')));
			$date2		 = DateTimeFormat::DateToLocale(date());
			$usedTime	 = '2';
			$entityType	 = "Car";
		}
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$model->used_time		 = $usedTime;
		$model->entity_type		 = $entityType;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->bkg_create_date1 = $date1 = Yii::app()->request->getParam('bkg_create_date1');
			$model->bkg_create_date2 = $date2 = Yii::app()->request->getParam('bkg_create_date2');
			$model->used_time		 = $usedTime = Yii::app()->request->getParam('used_time');
			$model->entity_type		 = $entityType = Yii::app()->request->getParam('entity_type');
			$date1					 = DateTimeFormat::DatePickerToDate($date1);
			$date2					 = DateTimeFormat::DatePickerToDate($date2);

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"UnapprovedCabdriver_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "UnapprovedCabdriver_" . date('Ymdhis') . ".csv";
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
			$rows	 = BookingTrail::model()->unapprovedCabDriverUsed($date1, $date2, $usedTime, $entityType, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Entity type', 'Entity ID', 'Vendor Name', 'Total Count When Used', 'First time use date', 
							'Last time used date', 'Current Status']);
			foreach ($rows as $row)
			{	
				if (($entityType == 1) ? $type		 = "Driver" : $type		 = "Car");
				$rowArray					 = array();
				$rowArray['entity_type']	 = $type;
				$rowArray['entity_id']		 = $row['entity_id'];
				$rowArray['vnd_name']		 = $row['vnd_name'];
				$rowArray['total_trips']	 = $row['total_trips'];
				$rowArray['first_time_used'] = $row['first_time_used'];
				$rowArray['last_time_used']	 = $row['last_time_used'];
				$rowArray['current_status']	 = $row['current_status'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider			 = BookingTrail::model()->unapprovedCabDriverUsed($date1, $date2, $usedTime, $entityType);
		$this->render('cab_driver_status', array('dataProvider' => $dataProvider, 'model' => $model, 'entity_type' => $type, 'roles'			 => $row));
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

	public function actionDriverAppUsage()
	{
		$row = Report::getRoleAccess(52);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('report_driver_app_usage', array('dataProvider' => $dataProvider, 'count' => $count, 'startCount' => $startCount, 'model' => $model, 'dmodel' => $dmodel, 'roles' => $row));
	}

	public function actionDriverAppNotUsed()
	{
		$row = Report::getRoleAccess(53);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->bkg_pickup_date1 = $date1 = Yii::app()->request->getParam('bkg_pickup_date1');
			$model->bkg_pickup_date2 = $date2 = Yii::app()->request->getParam('bkg_pickup_date2');
			$appnotused	= Yii::app()->request->getParam('not_app_used');
			
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DriverAppNotUsed_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DriverAppNotUsed_" . date('Ymdhis') . ".csv";
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
			$rows	 = Drivers::model()->getDriverAppNotUsed($date1, $date2, $appnotused, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Pickup Date', 'Trip Duration(Mins)', 'Vendor Name', 'Trip arrived Using driver app', 
							'Trip started Using driver app', 'Trip ended Using driver app', 'Driver app used', 'Vendor Phone',
							'Driver Name', 'Driver Phone', 'Booking Source']);
			foreach ($rows as $row)
			{	
				if (($entityType == 1) ? $type		 = "Driver" : $type		 = "Car");
				$rowArray						 = array();
				$rowArray['bkg_booking_id']		 = $row['bkg_booking_id'];
				$rowArray['bkg_pickup_date']	 = $row['bkg_pickup_date'];
				$rowArray['bkg_trip_duration']	 = $row['bkg_trip_duration'];
				$rowArray['vnd_name']			 = $row['vnd_name'];
				$rowArray['trip_arrive_time']	 = $row['trip_arrive_time'];
				$rowArray['start_app']			 = $row['start_app'];
				$rowArray['end_app']			 = $row['end_app'];
				$rowArray['app_usage']			 = $row['app_usage'];
				$rowArray['vnd_phone']			 = $row['vnd_phone'];
				$rowArray['drv_contact_id']		 = $row['drv_contact_id'];
				$rowArray['drv_name']			 = $row['drv_name'];
				$rowArray['drv_phone']			 = $row['drv_phone'];
				$rowArray['drv_contact_id']		 = $row['drv_contact_id'];
				$rowArray['trip_type']			 = $row['trip_type'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$provider		 = Drivers::model()->getDriverAppNotUsed($date1, $date2, $appnotused);
		$dataProvider	 = $provider[0];
		$count			 = $provider[1];
		$datasummary	 = Drivers::model()->getDriverAppNotUsedSummary($date1, $date2, $appnotused);
		$this->render('report_driver_app_not_usage', array('dataProvider' => $dataProvider, 'count' => $count, 'model' => $model, 'datasummary' => $datasummary, 'appnotused' => $appnotused, 'roles'			 => $row));
	}

}
