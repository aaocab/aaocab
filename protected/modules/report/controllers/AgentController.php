<?php

class AgentController extends Controller
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
			'accessControl', //perform access control for CRUD operations
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
			['allow', 'actions' => ['partnerWiseCountBooking'], 'roles' => ['partnerReports']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('PartnerPerformance'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('driverappusagereport', 'partnerWiseCountBooking', 'bookingtrackdetails', 'activeChannelPartner','Revenue'),
				'roles'		 => array('GeneralReport'),
			),
			array('allow', 'actions'	 => ['regprogress', 'PartnerMonthlyBalance'],
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionRegProgress()
	{
		$row = Report::getRoleAccess(10);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Agent Registration Progress";
		$model			 = new Agents();
		if ($_REQUEST['Agents'])
		{
			$arr							 = Yii::app()->request->getParam('Agents');
			$model->agt_is_voterid			 = ($arr['agt_is_voterid'] != '') ? $arr['agt_is_voterid'] : '';
			$model->agt_is_driver_license	 = ($arr['agt_is_driver_license'] != '') ? $arr['agt_is_driver_license'] : '';
			$model->agt_is_aadhar			 = ($arr['agt_is_aadhar'] != '') ? $arr['agt_is_aadhar'] : '';
			$model->agt_first_name			 = ($arr['agt_first_name'] != '') ? $arr['agt_first_name'] : '';
		}
		$dataProvider	 = $model->getRegistrationProgress($model->agt_is_voterid, $model->agt_is_driver_license, $model->agt_is_aadhar, $model->agt_first_name);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$type			 = 'command';
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$voter_id		 = Yii::app()->request->getParam('export_agt_is_voterid');
			$driver_license	 = Yii::app()->request->getParam('export_agt_is_driver_license');
			$aadhar			 = Yii::app()->request->getParam('export_agt_is_aadhar');
			$name			 = Yii::app()->request->getParam('export_agt_first_name');

			$data = $model->getRegistrationProgress($voter_id, $driver_license, $aadhar, $name, $type);

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"RegProgress_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, array("Name", "Email", "City Name", "Registered", "Voter ID", "Aadhar", "License", "Trade License", "Bank Details"));
			foreach ($data as $d)
			{
				$rowArray					 = array();
				$rowArray['agt_fname']		 = $d['agt_fname'];
				$rowArray['agt_email']		 = $d['agt_email'];
				$rowArray['cty_name']		 = $d['cty_name'];
				$rowArray['agt_create_date'] = $d['agt_create_date'];
				$rowArray['voterPath']		 = $d['voterPath'];
				$rowArray['aadharPath']		 = $d['aadharPath'];
				$rowArray['driverLicense']	 = $d['driverLicense'];
				$rowArray['tradeLicense']	 = $d['tradeLicense'];
				$rowArray['bankDeatils']	 = $d['bankDeatils'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$this->render('report_reg_progress', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionPartnercollection()
	{
		$this->pageTitle = "Partner Collection Report";
		$request		 = Yii::app()->request;
		$partnerid		 = $request->getParam('partnerid');
		$model			 = new Agents();
		$arr			 = [];
		$request		 = Yii::app()->request;
		$sort			 = $request->getParam('sort', 'agt_company');
		if ($request->getParam('Agents'))
		{
			$arr		 = $request->getParam('Agents');
			$partnerid	 = $arr['agt_id'];
		}
		$model->agt_id	 = $partnerid;
		$dataProvider	 = Agents::getCollection($partnerid, $sort);
		$totalCollection = Agents::getTotalCollection($partnerid);
		$this->render('report_partner_collection', array('model' => $model, 'dataProvider' => $dataProvider, 'totalCollection' => $totalCollection));
	}

	public function actionPartnerPerformance()
	{
		$this->pageTitle = "Channel Partner Performance - Report";
		$model			 = new Booking();
		$aModel			 = new Agents();
		if (isset($_REQUEST['Booking']) && isset($_REQUEST['Agents']))
		{
			$arr						 = Yii::app()->request->getParam('Booking');
			$arr1						 = Yii::app()->request->getParam('Agents');
			$model->bkg_pickup_date1	 = $date1						 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $date2						 = $arr['bkg_pickup_date2'];
			$model->bkg_drv_app_filter	 = $filter						 = $arr['bkg_drv_app_filter'];
			$agtType					 = implode(',', $arr1['agt_type']);

			$appliedFilters .= "DATE : " . date('d/m/Y', strtotime($date1)) . " - " . date('d/m/Y', strtotime($date2)) . " && ";
			if ($filter == 1)
			{
				$appliedFilters .= " Having Zero Count && ";
			}
			else if ($filter == 2)
			{
				$appliedFilters .= " Having Non- Zero Count && ";
			}
			else
			{
				$appliedFilters .= " ";
			}
			foreach ($arr1['agt_type'] as $key => $value)
			{
				if ($value == 0)
				{
					$appliedFilters .= "  Agent Type : Travel Agent && ";
				}
				if ($value == 1)
				{
					$appliedFilters .= "  Agent Type : Corporate && ";
				}
				if ($value == 2)
				{
					$appliedFilters .= "  Agent Type : Authorized Reseller && ";
				}
			}
			$appliedFilters = substr($appliedFilters, 0, strlen($appliedFilters) - 3);
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			$agtType		 = Yii::app()->request->getParam('export_filter2');
			$countFilter	 = Yii::app()->request->getParam('export_filter1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"PartnerPerformance_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "PartnerPerformance_" . date('YmdHi') . ".csv";
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
				$rows	 = Agents::model()->getPartnerPerformance($fromDate, $toDate, $agtType, $countFilter);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'ChannelPartner', 'Credit Limit', 'Activation Date', 'Count of Completed Booking', 'Total Amount of Completed Booking', 'Profit/Loss %', 'Completed Booking Count(30 days)', 'Completed Booking Count(90 days)']);
				foreach ($rows as $row)
				{

					$rowArray						 = array();
					$rowArray['Region']				 = $row['Region'];
					$rowArray['agt_company']		 = ($row['agt_company'] != null) ? $row['agt_company'] : ($row['agt_fname'] . ' ' . $row['agt_lname']);
					$rowArray['agt_credit_limit']	 = $row['agt_credit_limit'];
					$rowArray['agt_create_date']	 = $row['agt_create_date'];
					$rowArray['totalCompletedCount'] = $row['totalCompletedCount'];
					$rowArray['totalAmount']		 = $row['totalAmount'];
					$rowArray['plpercent']			 = $row['plpercent'];
					$rowArray['Count_30_Days']		 = $row['Count_30_Days'];
					$rowArray['Count_90_Days']		 = $row['Count_90_Days'];

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider	 = Agents::model()->getPartnerPerformance($date1, $date2, $agtType, $filter, $type			 = 'Command');
		$this->render('partner_performance', array('model' => $model, 'amodel' => $aModel, 'agt_type' => $agtType, 'appliedFilters' => $appliedFilters, 'dataProvider' => $dataProvider));
	}

	public function actionDriverAppUsageReport()
	{
		$row = Report::getRoleAccess(54);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			$arr['bkg_agent_id']	 = $model->bkg_agent_id	 = 18190;
		}

		if (isset($_REQUEST['bkg_pickup_date1']) && isset($_REQUEST['bkg_pickup_date2']))
		{
			$arr['bkg_pickup_date1'] = date('Y-m-d', strtotime(Yii::app()->request->getParam('bkg_pickup_date1'))) . " 00:00:00";
			$arr['bkg_pickup_date2'] = date('Y-m-d', strtotime(Yii::app()->request->getParam('bkg_pickup_date2'))) . " 23:59:59";
			$arr['bkg_agent_id']	 = Yii::app()->request->getParam('bkg_agent_id');
			if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"DriverAppUsageReport_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "DriverAppUsageReport_" . date('YmdHi') . ".csv";
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
				$rows	 = Drivers::getDriverAppusageDetails($arr, DBUtil::ReturnType_Query);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Date', 'Not Logged In', 'Not Left', 'Not Arrived', 'Not Started', 'Not Ended', 'Start API Fail', 'End API Fail']);
				foreach ($rows as $row)
				{
					$rowArray					 = array();
					$rowArray['date']			 = date("d-m-Y", strtotime($row['date']));
					$rowArray['not_loggedin']	 = !empty($row['not_loggedin']) ? trim($row['not_loggedin']) : 'N/A';
					$rowArray['not_left']		 = !empty($row['not_left']) ? trim($row['not_left']) : 'N/A';
					$rowArray['not_arrived']	 = !empty($row['not_arrived']) ? trim($row['not_arrived']) : 'N/A';
					$rowArray['not_started']	 = !empty($row['not_started']) ? trim($row['not_started']) : 'N/A';
					$rowArray['not_ended']		 = !empty($row['not_ended']) ? trim($row['not_ended']) : 'N/A';
					$rowArray['StartAPIFail']	 = !empty($row['StartAPIFail']) ? trim($row['StartAPIFail']) : 'N/A';
					$rowArray['EndAPIFail']		 = !empty($row['EndAPIFail']) ? trim($row['EndAPIFail']) : 'N/A';
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}


		$dataProvider = Drivers::getDriverAppusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('driverappusagedetails', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionPartnerWiseCountBooking()
	{
		$this->pageTitle = "Partner Booking Count - Report(B2B Other)";
		$model			 = new Booking();
		$date1			 = date("Y-m-d");
		$date2			 = date("Y-m-d");
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr	 = $request->getParam('Booking');
			$date1	 = $arr['bkg_create_date1'];
			$date2	 = $arr['bkg_create_date2'];
		}
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$dataProvider			 = BookingSub::model()->getPartnerWiseCountBookingReport($date1, $date2);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('partner_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider));
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
		//$arr['bkg_agent_id'] = 18190;
		$dataProvider = BookingTrail::getBookingTrackDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('bookingtrackdetails', array('model' => $model, 'dataProvider' => $dataProvider, 'checked' => $checked));
	}

	public function actionPartnerMonthlyBalance()
	{
		$this->pageTitle = "Partner Monthly Balance - Report";
		$model			 = new AccountTransactions();
		$partnerModel	 = new ChannelPartnerMarkup();
		$request		 = Yii::app()->request;
		$fromDate		 = $toDate			 = '';
		$agentId		 = '';

		if ($request->getParam('AccountTransactions') || $request->getParam('ChannelPartnerMarkup'))
		{
			$arr		 = $request->getParam('AccountTransactions');
			$agentArr	 = $request->getParam('ChannelPartnerMarkup');
			$fromDate	 = $arr['from_date'];
			$toDate		 = $arr['to_date'];
			$agentId	 = $agentArr['cpm_agent_id'];
		}
		else
		{
			$fromDate	 = date("Y-m-d", strtotime("-12 month", time()));
			$toDate		 = date('Y-m-d');
		}

		$model->from_date			 = $fromDate;
		$model->to_date				 = $toDate;
		$partnerModel->cpm_agent_id	 = $agentId;
		$diff3Month					 = strtotime("-12 month", strtotime($toDate)) - strtotime($fromDate);
		$error						 = '';
		if ($diff3Month > 0)
		{
			$error = "Date range is not greater than 12 months";
			goto skipAll;
		}
		if ($agentId == "")
		{
			$agtError = "Please choose agent";
			goto skip;
		}
		$params = [
			'from_date'	 => $fromDate,
			'to_date'	 => $toDate
		];

		$dataProvider	 = Agents::partnerMonthlyBalance($agentId, $params);
		$fromDate		 = ($fromDate != '') ? date('d/m/Y', strtotime($fromDate)) : '';
		$toDate			 = ($toDate != '') ? date('d/m/Y', strtotime($toDate)) : '';
		skipAll:
		skip:
		$this->render('report_partner_monthly_balance', array('dataProvider'	 => $dataProvider, 'fromdate'		 => $fromDate,
			'todate'		 => $toDate, 'model'			 => $model, 'partnerModel'	 => $partnerModel, 'error'			 => $error, 'agtError'		 => $agtError));
	}

	public function actionActiveChannelPartner()
	{
		$row = Report::getRoleAccess(137);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Active Channel Partner Agents";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$pickupDate1Data = explode(' ', $data['bkg_pickup_date1']);
				$pickupDate2Data = explode(' ', $data['bkg_pickup_date2']);
				$pickupDate1	 = ($pickupDate1Data[1] == '00:00:00') ? $data['bkg_pickup_date1'] : $data['bkg_pickup_date1'] . ' 00:00:00';
				$pickupDate2	 = ($pickupDate2Data[1] == '23:59:59') ? $data['bkg_pickup_date2'] : $data['bkg_pickup_date2'] . ' 23:59:59';

				$model->bkg_pickup_date1 = $pickupDate1;
				$model->bkg_pickup_date2 = $pickupDate2;
			}
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1Data = explode(' ', $data['bkg_create_date1']);
				$createDate2Data = explode(' ', $data['bkg_create_date2']);
				$createDate1	 = ($createDate1Data[1] == '00:00:00') ? $data['bkg_create_date1'] : $data['bkg_create_date1'] . ' 00:00:00';
				$createDate2	 = ($createDate2Data[1] == '23:59:59') ? $data['bkg_create_date2'] : $data['bkg_create_date2'] . ' 23:59:59';

				$model->bkg_create_date1 = $createDate1;
				$model->bkg_create_date2 = $createDate2;
			}
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 42 months')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}


		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			$model->bkg_create_date1 = Yii::app()->request->getParam('create_date1');
			$model->bkg_create_date2 = Yii::app()->request->getParam('create_date2');

			$model->bkg_pickup_date1 = Yii::app()->request->getParam('pickup_date1');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('pickup_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ActiveChannelPartner_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ActiveChannelPartner_" . date('Ymdhis') . ".csv";
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
			$rows	 = Agents::getListActiveChannelPartner($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['ChannelPartner', 'Completed Count', 'Total Gozo Amount', 'Cancelled Count', 'Gozo Account Manager', 'Last Booking Received', 'Months Since Last Booking']);
			foreach ($rows as $row)
			{
				$rowArray['ChannelPartner']				 = ($row['ChannelPartner'] != '') ? $row['ChannelPartner'] : $row['Agent_Name'];
				$rowArray['Completed_Cnt']				 = $row['Completed_Cnt'];
				$rowArray['Total_Gozo_Amount']			 = $row['Total_Gozo_Amount'];
				$rowArray['CancelledCnt']				 = $row['CancelledCnt'];
				$rowArray['Gozo_Account_Manager']		 = $row['Gozo_Account_Manager'];
				$rowArray['Last_Booking_Received_On']	 = $row['Last_Booking_Received_On'];
				$rowArray['Months_Since_Last_Booking']	 = $row['Months_Since_Last_Booking'];
				$row1									 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = Agents::getListActiveChannelPartner($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('active_channel_partner_list', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row));
	}

	public function actionRevenue()
	{
		$this->pageTitle = "Agent Revenue";
		$from_date		 = $to_date		 = '';
		$request		 = Yii::app()->request;
		$orderby		 = 'date';
		$partnerId		 = 18190;
        $model			 = new BookingSub();
        
		if ($request->getParam('BookingSub'))
		{
			$arr		 = $request->getParam('BookingSub');
			$from_date	 = $arr['bkg_pickup_date1'];
			$to_date	 = $arr['bkg_pickup_date2'];
			$orderby	 = $arr['groupvar'];
			$partnerId	 = ($arr['bkg_agent_id'] != '')?$arr['bkg_agent_id']:18190;
            $model->bkg_agent_id		 = $partnerId;
		}
		else
		{
			$from_date	 = date("Y-m-d", strtotime("-31 day", time()));
			$to_date	 = date('Y-m-d', strtotime("-1 day", time()));
            $model->bkg_agent_id		 = $partnerId;
		}

		$model->bkg_pickup_date1 = $from_date;
		$model->bkg_pickup_date2 = $to_date;

		$diff3Month	 = strtotime("-3 month", strtotime($to_date)) - strtotime($from_date);
		$error		 = '';
		if ($diff3Month > 0)
		{
			$error = "Date range should be less than 3 months";
			goto skipAll;
		}

		$params			 = [
			'from_date'	 => $from_date,
			'to_date'	 => $to_date,
		];
		
		$dataProvider	 = BookingSub::revenue($params, $orderby, $partnerId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$from_date		 = ($from_date != '') ? date('d/m/Y', strtotime($from_date)) : '';
		$to_date		 = ($to_date != '') ? date('d/m/Y', strtotime($to_date)) : '';

		skipAll:
		$this->render('revenue', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'from_date'		 => $from_date,
			'to_date'		 => $to_date,
			'orderby'		 => $orderby,
            'error'			 => $error
				)
		);
	}

}
