<?php

use BookingLog;
use LeadLog;
use UserLog;

class BookingController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public static $cabTypeList;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,pickup,rates', // we only allow deletion via POST request
		);
	}

	public function actions()
	{
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
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
				'actions'	 => array('escalation'),
				'roles'		 => array('bookingEscalationList'),
			),
			['allow', 'actions' => ['otpserved'], 'roles' => ['OtpservedReport']],
			['allow', 'actions' => ['travellers'], 'roles' => ['TravellersReport']],
			['allow', 'actions' => ['cabdetails'], 'roles' => ['CabDetailsReport']],
			['allow', 'actions' => ['npsscore'], 'roles' => ['NpsScoreReport']],
			['allow', 'actions' => ['reportcancellations'], 'roles' => ['CancellationsReport']],
			['allow', 'actions' => ['booking'], 'roles' => ['BookingReport']],
			['allow', 'actions' => ['partnerWiseCountBooking'], 'roles' => ['partnerReports']],
			array('allow', 'actions' => array('cancellations', 'matchlist', 'quotebooking'), 'users' => array('@'),),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('pickup', 'dailyassignedreport', 'autoAssign', 'otpserved', 'travellers', 'cabdetails', 'npsscore'),
				'roles'		 => array('ConfidentialReports')),
			array('allow',
				'actions'	 => array('fulfilmentProfit', 'upperTierList', 'cancellationList', 'anyAssignment', 'localBooking', 'history', 'cancelHistory',
					'margin', 'assignment', 'lossAssignment', 'escalation', 'penaltyReport', 'tfrRejected', 'whatsappRef',
					'partnerWiseCountBooking', 'DailyLoss', 'bookingReport', 'RegionVendorwiseDriverAppusage', 'reportcancellations',
					'servicePerformance', 'penaltySummary', 'ratingReport', 'driverappusagereport', 'collectionMismatch', 'noneZeroExtraCharges',
					'directAcceptReport', 'bookingtrackdetails', 'stateWiseBookingCount', 'quoteBasedRatePerKm',
					'listTotalAmountByPickup', 'listTotalAmountByCreate', 'regionWiseBookingCount', 'zoneZoneBookingCount',
					'zoneWiseBookingCount', 'referralTrack', 'salesAssistedPercentByTier', 'bookingCountByStates', 'salesAssistedBookings', 
					'monthlyVolumeByServiceTier', 'salesAssistedByTier', 'assignmentSummary', 'manualReport', 'showbidlog', 'trfzOffers', 
					'mmtCancelReport', 'partnerBookings', 'accountingFlagSet'),
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

	public function loadModel($id)
	{
		$model = Booking::model()->findByPk($id);
		if ($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	public function actionCancellations()
	{
		$row = Report::getRoleAccess(4);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Cancellation Report";
		/* @var $model Booking */
		$model			 = new Booking;
		$date1			 = $date2			 = '';
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->bkg_create_date1 = $arr['bkg_create_date1'];
			$model->bkg_create_date2 = $arr['bkg_create_date2'];
			$date1					 = $model->bkg_create_date1;
			$date2					 = $model->bkg_create_date2;
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d');
			$model->bkg_create_date2 = date('Y-m-d');
			$date1					 = $model->bkg_create_date1;
			$date2					 = $model->bkg_create_date2;
		}
		if ($date1 == "" && $date2 == "")
		{
			$date2	 = date('d/m/Y');
			$date1	 = DateTimeFormat::DatePickerToDate($date1);
			$date2	 = DateTimeFormat::DatePickerToDate($date2);
		}
		/* @var $submodel BookingSub */
		$submodel = new BookingSub();

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->bkg_create_date1 = Yii::app()->request->getParam('bkg_create_date1');
			$model->bkg_create_date2 = Yii::app()->request->getParam('bkg_create_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"Cancellations_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "Cancellations_" . date('Ymdhis') . ".csv";
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
			$rows	 = $submodel->getCancellationList($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Booking Type', 'Booking Route', 'Booking Date/Time', 'Pickup Date/Time',
				'Cancellation Date/Time', 'Cancel Reason', 'Cancel Description', 'Amount']);
			foreach ($rows as $row)
			{
				$rowArray								 = array();
				$rowArray['bkg_booking_id']				 = $row['bkg_booking_id'];
				$rowArray['bkg_booking_type']			 = Booking::model()->getBookingType($row['bkg_booking_type']);
				$rowArray['booking_route']				 = $row['booking_route'];
				$rowArray['bkg_create_date']			 = $row['bkg_create_date'];
				$rowArray['bkg_pickup_date']			 = $row['bkg_pickup_date'];
				$rowArray['btr_cancel_date']			 = $row['btr_cancel_date'];
				$rowArray['cnr_reason']					 = $row['cnr_reason'];
				$rowArray['bkg_cancel_delete_reason']	 = $row['bkg_cancel_delete_reason'];
				$rowArray['bkg_total_amount']			 = $row['bkg_total_amount'];
				$row1									 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $submodel->getCancellationList($model);
		$this->render('report_cancellation', array('dataProvider'	 => $dataProvider[0],
			'model'			 => $model, 'roles'			 => $row));
	}

	public function actionCountBookingsByPickup()
	{
		
	}

	public function actionReportCancellations()
	{
		$row = Report::getRoleAccess(5);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle	 = "Cancellation Report";
		/* @var $model Booking */
		$model				 = new Booking;
		$agents				 = new Agents();
		$date1				 = $date2				 = $bkgRegion			 = '';
		$bkgCancelCustomer	 = $bkgCancelAdmin		 = $bkgCancelAgent		 = $bkgCancelSystem	 = 0;
		
		$sameDayCancellation = 1;
		$req				 = Yii::app()->request;
		$submodel			 = new BookingSub();
		if ($req->getParam('Booking'))
		{
			$arr						 = $req->getParam('Booking');
			$model->bkg_create_date1	 = $arr['bkg_create_date1'];
			$model->bkg_create_date2	 = $arr['bkg_create_date2'];
			$model->bkg_region			 = $arr['bkg_region'];
			$model->bkgCancelCustomer	 = $arr['bkgCancelCustomer'];
			$model->bkgCancelAdmin		 = $arr['bkgCancelAdmin'];
			$model->bkgCancelAgent		 = $arr['bkgCancelAgent'];
			$model->bkgCancelSystem		 = $arr['bkgCancelSystem'];
			$model->searchIsDBO			 = $arr['searchIsDBO'];
			$model->isGozonow			 = $arr['isGozonow'];
			$model->IsGozoCancel		 = $arr['IsGozoCancel'];
			$model->IsCustomerCancel	 = $arr['IsCustomerCancel'];
			$model->sameDayCancellation	 = $arr['sameDayCancellation'];
			$model->bkg_service_class	 = $arr['bkg_service_class'];
			$model->bkg_cancel_id		 = $arr['bkg_cancel_id'];
			$agents->agt_id				 = $req->getParam('Agents')['agt_id'];
			$btocbooking				 = $req->getParam('btocbooking');
		}
		else if (isset($_REQUEST['export1']))
		{
			$service					 = $req->getParam('export_bkg_service_class');
			$model->bkg_create_date1	 = $req->getParam('export_bkg_create_date1');
			$model->bkg_create_date2	 = $req->getParam('export_bkg_create_date2');
			$model->bkg_region			 = $req->getParam('export_bkg_region');
			$model->bkgCancelCustomer	 = $req->getParam('export_bkgCancelCustomer');
			$model->bkgCancelAdmin		 = $req->getParam('export_bkgCancelAdmin');
			$model->bkgCancelAgent		 = $req->getParam('export_bkgCancelAgent');
			$model->bkgCancelSystem		 = $req->getParam('export_bkgCancelSystem');
			$model->searchIsDBO			 = $req->getParam('export_searchIsDBO');
			$model->isGozonow			 = $arr['isGozonow'];
			$model->IsGozoCancel		 = $req->getParam('export_IsGozoCancel');
			$model->IsCustomerCancel	 = $req->getParam('export_IsCustomerCancel');
			$model->sameDayCancellation	 = $req->getParam('export_sameDayCancellation');
			$model->bkg_service_class	 = ($service != '') ? explode(',', $req->getParam('export_bkg_service_class')) : '';
			$model->bkg_cancel_id		 = $req->getParam('export_bkg_cancel_id');
			$agents->agt_id				 = $req->getParam('export_agt_id');
			$btocbooking				 = $req->getParam('export_btocbooking');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"Cancellations_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename					 = "Cancellations_" . date('YmdHi') . ".csv";
			$foldername					 = Yii::app()->params['uploadPath'];
			$backup_file				 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $submodel->getCancellationList($model, DBUtil::ReturnType_Query, $agents, $btocbooking);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Region', 'Booking ID', 'Partner Ref Code', 'Booking Type', 'Partner Type', 'Booking Route', 'Booking Date/Time', 'Pickup Date/Time', 'Working Hour', 'Arrival Date/Time', 'Cancellation Date/Time', 'Cancel Reason', 'Cancel Description', 'Cancellation Charge', 'Amount', 'DBO Status', 'DBO refund amount', 'Cancel By']);
			foreach ($rows as $data)
			{
				if ($data["bkg_agent_id"] != NULL)
				{
					$partnerType = ($data['agt_company'] != NULL || $data['agt_company'] != '') ? $data['agt_company'] : $data['agent_name'];
				}
				else
				{
					$partnerType = 'B2C';
				}
				$rowArray							 = array();
				$rowArray['Region']					 = States::findUniqueZone($data['stt_zone']);
				$rowArray['booking_id']				 = $data['bkg_booking_id'];
				$rowArray['partner_ref_code']		 = $data['bkg_agent_ref_code'];
				$rowArray['booking_type']			 = Booking::model()->getBookingType($data['bkg_booking_type']);
				$rowArray['partner_type']			 = $partnerType;
				$rowArray['booking_route']			 = $data['booking_route'];
				$rowArray['booking_date_time']		 = date("d-m-Y H:i:s", strtotime($data['bkg_create_date']));
				$rowArray['pickup_date_time']		 = date("d-m-Y H:i:s", strtotime($data['bkg_pickup_date']));
				$fromDate							 = $data['bkg_create_date'];
				$toDate								 = $data['bkg_pickup_date'];
				$rowArray['workingHour']			 = DBUtil::CalcWorkingHour($fromDate, $toDate);
				$rowArray['arrival_date_time']		 = ($data['arrive_time'] != null ? $data['arrive_time'] : '');
				$rowArray['cancellation_date_time']	 = date("d-m-Y H:i:s", strtotime($data['btr_cancel_date']));
				$rowArray['cancel_reason']			 = $data['cnr_reason'];
				$rowArray['cancel_description']		 = $data['bkg_cancel_delete_reason'];
				$rowArray['cancellation_charge']	 = number_format($data['bkg_cancel_charge'], 2);
				$rowArray['amount']					 = number_format($data['bkg_total_amount'], 2);
				$rowArray['dbo_status']				 = $data['is_dbo'];
				$rowArray['bkg_cust_compensation_amount']		 = number_format($data['bkg_cust_compensation_amount'], 2);
				$rowArray['cancel_by']				 = $data['cancelBy'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		else
		{
			$model->bkg_create_date1	 = empty($req->getParam('bkg_create_date1')) ? date("Y-m-d") : $req->getParam("bkg_create_date1");
			$model->bkg_create_date2	 = empty($req->getParam('bkg_create_date2')) ? date("Y-m-d") : $req->getParam("bkg_create_date2");
			$model->IsGozoCancel		 = empty($req->getParam('IsGozoCancel')) || $req->getParam('IsGozoCancel') == 0 ? 0 : 1;
			$model->sameDayCancellation	 = 0;
			$btocbooking				 = 0;
			$agents->agt_id				 = '';
		}

		/* @var $submodel BookingSub */

		$dataProvider = $submodel->getCancellationList($model, DBUtil::ReturnType_Provider, $agents, $btocbooking);
		$this->render('cancellation_bydate', array(
			'dataProvider'	 => $dataProvider[0],
			'model'			 => $model,
			'summary'		 => $dataProvider[1],
			'params'		 => $arr,
			'agents'		 => $agents,
			'btocbooking'	 => $btocbooking, 'roles'			 => $row));
	}

	public function actionBooking()
	{
		$row = Report::getRoleAccess(24);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Booking Report";
		$model			 = new Booking;
		$submodel		 = new BookingSub();
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
		}
		else
		{

			$date2		 = date('Y-m-d');
			$date1		 = date('Y-m-d');
			$from		 = '';
			$to			 = '';
			$vendor		 = '';
			$platform	 = '';
			$agent		 = '';
			$status		 = '6';
		}
		$model->bkg_status				 = $status;
		$model->bkg_create_date1		 = $date1;
		$model->bkg_create_date2		 = $date2;
		$model->bkg_from_city_id		 = $from;
		$model->bkg_to_city_id			 = $to;
		$model->bcb_vendor_id			 = $vendor;
		$model->bkgTrail->bkg_platform	 = $platform;
		$model->bkg_agent_id			 = $agent;
		$submodel->bkg_agent_id			 = $agent;

		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']) && isset($_REQUEST['export_from_city1']) && isset($_REQUEST['export_to_city1']) && isset($_REQUEST['export_vendor1']) && isset($_REQUEST['export_platform1']))
		{
			$arr2						 = array();
			$adminWrapper				 = new Booking();
			$fromDate					 = Yii::app()->request->getParam('export_from1');
			$toDate						 = Yii::app()->request->getParam('export_to1');
			$fromCity					 = Yii::app()->request->getParam('export_from_city1');
			$toCity						 = Yii::app()->request->getParam('export_to_city1');
			$bkgVendor					 = Yii::app()->request->getParam('export_vendor1');
			$bkgPlatform				 = Yii::app()->request->getParam('export_platform1');
			$bkgstatus					 = Yii::app()->request->getParam('export_status');
			$adminWrapper->bkg_agent_id	 = Yii::app()->request->getParam('export_agent1');
			$arr2['data']				 = $adminWrapper->bookingReportCount($fromDate, $toDate, $fromCity, $toCity, $bkgVendor, $bkgPlatform, $bkgstatus);
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				$status	 = Booking::model()->getBookingStatus();
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"BookingReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, array("Booking Status", "Count", "Amount", "Total Count", "Total Amount"));
				foreach ($arr2['data'] as $req)
				{

					$req['bkg_status'] = $status[$req['bkg_status']];
					fputcsv($handle, array($req['bkg_status'], $req['count'], $req['sum'], $req['total_count'], $req['total_amount']));
				}
				fclose($handle);
				exit;
			}
		}


		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']) && isset($_REQUEST['export_from_city2']) && isset($_REQUEST['export_to_city2']) && isset($_REQUEST['export_vendor2']) && isset($_REQUEST['export_platform2']))
		{
			$fromDate				 = Yii::app()->request->getParam('export_from2');
			$toDate					 = Yii::app()->request->getParam('export_to2');
			$fromCity				 = Yii::app()->request->getParam('export_from_city2');
			$toCity					 = Yii::app()->request->getParam('export_to_city2');
			$bkgVendor				 = Yii::app()->request->getParam('export_vendor2');
			$bkgPlatform			 = Yii::app()->request->getParam('export_platform2');
			$submodel->bkg_agent_id	 = Yii::app()->request->getParam('export_agent2');
			$bkgstatus				 = Yii::app()->request->getParam('export_status2');
			$type					 = 'command';
			//$rows = $model->bookingReport($fromDate, $toDate, $fromCity, $toCity, $bkgVendor, $bkgPlatform, $type);
			$rows					 = $submodel->bookingReport($fromDate, $toDate, $fromCity, $toCity, $bkgVendor, $bkgPlatform, $bkgstatus, $type);

			$status			 = Booking::model()->getBookingStatus();
			$bookingType	 = Booking::model()->getBookingType();
			$bookingPlatform = Booking::model()->booking_platform;
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"BookingReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Booking ID', 'Tentative Status', 'UserId', 'Consumer Name', 'Trip Type', 'Agent Name', 'Phone Number', 'Email', 'Booking Type', 'Cab Type', 'From City', 'To City',
				'Source Zone', 'Destination Zone', 'Region', 'Vendor Name', 'Base Fare', 'Discount', 'QUOTED MARGIN (%)', 'REALIZED MARGIN (%)', 'Driver Name', 'Cab Number', 'Route', 'Status', 'Cancellation Date/Time',
				'Amount', 'Vendor Amount', 'Quoted VA', 'Trip VA', 'Advanced Amount', 'Amount Due', 'Gozo Amount', 'Pickup Date/Time', 'Pickup Address', 'Return Date/Time',
				'Drop Off Address', 'Booking Date/Time', 'Source', 'Platform', 'Vendor Phone', 'Vendor Assigned Count', 'Last Vendor Assignment Date', 'First Vendor Assignment Date', 'Last Vendor Amount', 'First Vendor Amount', 'Last VendorID', 'First Vendor ID', 'DZPP Surge']);
			foreach ($rows as $row)
			{
				$rowArray								 = array();
				$rowArray['bkg_booking_id']				 = $row['bkg_booking_id'];
				$rowArray['tentative_flag']				 = $row['tentative_flag'];
				$rowArray['bkg_user_id']				 = $row['bkg_user_id'];
				$rowArray['bkg_user_name']				 = $row['bkg_user_name'] . " " . $row['bkg_user_lname'];
				$rowArray['book_type']					 = $row['book_type'];
				$rowArray['agent_name']					 = $row['agent_name'];
				$rowArray['bkg_contact_no']				 = $row['bkg_country_code'] . "-" . $row['bkg_contact_no'];
				$rowArray['bkg_user_email']				 = $row['bkg_user_email'];
				$rowArray['bkg_booking_type']			 = $row['serviceType'];
				$rowArray['vht_model']					 = $row['serviceClass'];
				$rowArray['from_city']					 = $row['fromCity'];
				$rowArray['to_city']					 = $row['toCity'];
				$rowArray['sourceZone']					 = $row['sourceZone'];
				$rowArray['destinationZone']			 = $row['destinationZone'];
				$rowArray['region']						 = $row['region'];
				$rowArray['vendor_name']				 = $row['vendor_name'];
				$rowArray['base_fare']					 = $row['base_fare'];
				$rowArray['discount']					 = $row['discount'];
				$rowArray['ry_quote_vendor_amount']		 = round((($row['ry_quote_vendor_amount'] / $row['ry_booking_amount']) * 100), 2);
				$rowArray['ry_gozo_amount']				 = round((($row['ry_gozo_amount'] / $row['ry_booking_amount']) * 100), 2);
				$rowArray['driver_name']				 = $row['driver_name'];
				$rowArray['cab_number']					 = $row['cab_number'];
				$rowArray['route_name']					 = $row['cities'];
				$rowArray['bkg_status']					 = $status[$row['bkg_status']];
				$rowArray['cancellation_datetime']		 = ($row['bkg_status'] == 9) ? date("d/m/Y H:i:s", strtotime($row['cancellation_datetime'])) : '';
				$rowArray['bkg_total_amount']			 = round($row['bkg_total_amount']);
				$rowArray['bkg_vendor_amount']			 = round($row['bkg_vendor_amount']);
				$rowArray['bkg_quoted_vendor_amount']	 = round($row['bkg_quoted_vendor_amount']);
				$rowArray['trip_vendor_amount']			 = round($row['trip_vendor_amount']);
				$rowArray['bkg_advance_amount']			 = round($row['bkg_advance_amount']);
				$rowArray['bkg_due_amount']				 = round($row['bkg_due_amount']);
				$rowArray['bkg_gozo_amount']			 = round($row['ry_gozo_amount']);
				$rowArray['bkg_pickup_date']			 = ($row['bkg_pickup_date'] != '' || $row['bkg_pickup_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
				$rowArray['bkg_pickup_address']			 = $row['bkg_pickup_address'];
				$rowArray['bkg_return_date']			 = ($row['bkg_return_date'] != '' || $row['bkg_return_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_return_date'])) : '';
				$rowArray['bkg_drop_address']			 = $row['bkg_drop_address'];
				$rowArray['bkg_create_date']			 = ($row['bkg_create_date'] != '' || $row['bkg_create_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
				$rowArray['bkg_info_source']			 = $row['bkg_info_source'];
				$rowArray['bkg_platform']				 = $bookingPlatform[$row['bkg_platform']];
				$rowArray['vnd_phone']					 = $row['vnd_phone'];
				$bookingCabDetails						 = $row['bkg_status'] > 0 ? BookingCab::getBookingCabDetailsByBkgID($row['bkg_id']) : [];
				$rowArray['TotalVendorAssignedCount']	 = $bookingCabDetails['TotalVendorAssignedCount'] != null ? $bookingCabDetails['TotalVendorAssignedCount'] : "";
				$rowArray['LVendorAssignmentDate']		 = $bookingCabDetails['LVendorAssignmentDate'] != null ? $bookingCabDetails['LVendorAssignmentDate'] : "";
				$rowArray['FVendorAssignmentDate']		 = $bookingCabDetails['FVendorAssignmentDate'] != null ? $bookingCabDetails['FVendorAssignmentDate'] : "";
				$rowArray['LVendorAmount']				 = $bookingCabDetails['LVendorAmount'] != null ? $bookingCabDetails['LVendorAmount'] : "";
				$rowArray['FVendorAmount']				 = $bookingCabDetails['FVendorAmount'] != null ? $bookingCabDetails['FVendorAmount'] : "";
				$rowArray['LVendorID']					 = $bookingCabDetails['LVendorID'] != null ? $bookingCabDetails['LVendorID'] : "";
				$rowArray['FVendorID']					 = $bookingCabDetails['FVendorID'] != null ? $bookingCabDetails['FVendorID'] : "";
				$rowArray['dzpp_surge']					 = $row['dzpp_surge'] != null ? $row['dzpp_surge'] : 0;

				//$rowArray['bkg_status'] = $status[$row['bkg_status']];
				$row1 = array_values($rowArray);
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
		$dataProvider	 = $submodel->bookingReport($date1, $date2, $from, $to, $vendor, $platform, $status);
		$countReport	 = $model->bookingReportCount($date1, $date2, $from, $to, $vendor, $platform, $status);
		$this->render('report_booking', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'trialModel'	 => new BookingTrail(),
			'countReport'	 => $countReport, 'roles'			 => $row));
	}

	public function actionPickup()
	{
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
			$status		 = 6;
			$bkgType	 = [];
		}
		$model->bkgtypes				 = $bkgType;
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
			$bkgType				 = Yii::app()->request->getParam('export_booking_type');
			$bkgType				 = (!empty($bkgType[0])) ? $bkgType : [];
			$modelsub->bkg_agent_id	 = $bkgAgent;
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"PickupReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
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
			$rows			 = $modelsub->pickupReport($fromDate, $toDate, $fromCity, $toCity, $bkgVendor, $bkgPlatform, $bkgStatus, $bkgType, $type);
			$status			 = Booking::model()->getBookingStatus();
			$bookingType	 = Booking::model()->getBookingType();
			$bookingPlatform = Booking::model()->booking_platform;
			$handle			 = fopen("php://output", 'w');

			fputcsv($handle, [
				'Booking ID',
				'Invoice ID',
				'Trip Type',
				'Status',
				'Consumer Name',
				'From City',
				'To City',
				'Source Zone',
				'Destination Zone',
				'Region',
				'Cancellation Policy Type',
				'Vendor Name',
				'Driver Name',
				'Cab Number',
				'Cab Model',
				'Cab Type',
				'Partner Name',
				'Partner Ref Code',
				'Booking Date/Time',
				'Pickup Date/Time',
				'Return Date/Time',
				'Cancellation Date/Time',
				'Cancellation Reason',
				'Cancellation Remarks',
				'Pickup Address',
				'Drop Off Address',
				'Amount',
				'Base Fare',
				'Discount',
				'One-Time Price Adjustment',
				'Vendor Amount',
				'Trip Vendor Amount',
				'Driver Allowance',
				'Toll Taxes',
				'Extra Toll Taxes',
				'Total Toll Taxes',
				'State Taxes',
				'Convenience Charges',
				'Extra State Taxes',
				'Total State Taxes',
				'Parking Charges',
				'Additional Charges',
				'Extra Km Charges',
				'Extra KM',
				'Extra Minutes Charges',
				'Extra Minutes',
				'GST',
				'Airport Entry Fee',
				'Advance Received',
				'Driver Collected',
				'Refund',
				'Credit Applied',
				'Cancel Charge',
				'Amount Due',
				'Partner Wallet',
				'Partner Payable',
				'Partner Commission',
				'Source',
				'Platform',
				'Payment Mode',
				'Vendor Assigned Count',
				'Last Vendor Assignment Date',
				'First Vendor Assignment Date',
				'Last Vendor Amount',
				'First Vendor Amount',
				'Last VendorID',
				'First Vendor ID',
				'DZPP Surge'
			]);
			foreach ($rows as $row)
			{

				$policyType	 = CancellationPolicyDetails::model()->findByPk($row['bkg_cancel_rule_id'])->cnp_code; //CancellationPolicy::getPolicyType($row['bkg_cancel_rule_id'], $row['bkg_agent_id']);
				$invoiceId	 = '';
				if ($row['bkg_status'] == 6 || $row['bkg_status'] == 7)
				{
					$invoiceId = BookingInvoice::getInvoiceId($row['bkg_id'], $row['bkg_pickup_date']);
				}
				$rowArray								 = array();
				$rowArray['bkg_booking_id']				 = $row['bkg_booking_id'];
				$rowArray['invoice_id']					 = $invoiceId;
				$rowArray['book_type']					 = $row['book_type'];
				$rowArray['bkg_status']					 = $status[$row['bkg_status']];
				$rowArray['bkg_user_name']				 = $row['bkg_user_fname'] . " " . $row['bkg_user_lname'];
				$rowArray['from_city']					 = $row['fromCity'];
				$rowArray['to_city']					 = $row['toCity'];
				$rowArray['sourceZone']					 = $row['sourceZone'];
				$rowArray['destinationZone']			 = $row['destinationZone'];
				$rowArray['region']						 = $row['region'];
				$rowArray['cancellationPolicyType']		 = $policyType;
				$rowArray['bkg_vnd_name']				 = $row['vendor_name'];
				$rowArray['drv_name']					 = $row['drv_name'];
				$rowArray['vhc_number']					 = $row['vhc_number'];
				$rowArray['vht_model']					 = $row['vht_model'];
				$rowArray['desired_cab']				 = $row['serviceClass'];
				$rowArray['agent_name']					 = $row['agent_name'];
				$rowArray['bkg_agent_ref_code']			 = $row['bkg_agent_ref_code'];
				$rowArray['bkg_create_date']			 = ($row['bkg_create_date'] != '' || $row['bkg_create_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
				$rowArray['bkg_pickup_date']			 = ($row['bkg_pickup_date'] != '' || $row['bkg_pickup_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
				$rowArray['bkg_return_date']			 = ($row['bkg_return_date'] != '' || $row['bkg_return_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_return_date'])) : '';
				$rowArray['cancellation_datetime']		 = ($row['bkg_status'] == 9) ? date("d/m/Y H:i:s", strtotime($row['cancellation_datetime'])) : '';
				$rowArray['cnr_reason']					 = $row['cnr_reason'];
				$rowArray['cancel_remarks']				 = $row['cancel_remarks'];
				$rowArray['bkg_pickup_address']			 = $row['bkg_pickup_address'];
				$rowArray['bkg_drop_address']			 = $row['bkg_drop_address'];
				$rowArray['bkg_total_amount']			 = $row['bkg_total_amount'];
				$rowArray['bkg_base_amount']			 = $row['bkg_base_amount'];
				$rowArray['bkg_discount_amount']		 = $row['bkg_discount_amount'];
				$rowArray['bkg_extra_discount_amount']	 = $row['bkg_extra_discount_amount'];
				$rowArray['bkg_vendor_amount']			 = $row['bkg_vendor_amount'];
				$rowArray['bcb_vendor_amount']			 = $row['bcb_vendor_amount'];
				$rowArray['drv_allowance']				 = $row['drv_allowance'];
				$rowArray['bkg_toll_tax']				 = $row['bkg_toll_tax'];
				$rowArray['bkg_extra_toll_tax']			 = $row['bkg_extra_toll_tax'];
				$rowArray['bkg_total_toll_tax']			 = $row['total_toll_tax'];
				$rowArray['bkg_state_tax']				 = $row['bkg_state_tax'];
				$rowArray['bkg_convenience_charge']		 = $row['bkg_convenience_charge'];
				$rowArray['bkg_extra_state_tax']		 = $row['bkg_extra_state_tax'];
				$rowArray['bkg_total_state_tax']		 = $row['total_state_tax'];
				$rowArray['bkg_parking_charge']			 = $row['bkg_parking_charge'] > 0 ? $row['bkg_parking_charge'] : '0';
				$rowArray['bkg_additional_charge']		 = $row['bkg_additional_charge'] > 0 ? $row['bkg_additional_charge'] : '0';
				$rowArray['bkg_extra_km_charge']		 = $row['bkg_extra_km_charge'] > 0 ? $row['bkg_extra_km_charge'] : '0';
				$rowArray['bkg_extra_km']				 = $row['bkg_extra_km'] > 0 ? $row['bkg_extra_km'] : 0;
				$rowArray['bkg_extra_per_min_charge']	 = $row['bkg_extra_per_min_charge'] > 0 ? $row['bkg_extra_per_min_charge'] : 0;
				$rowArray['bkg_extra_min']				 = $row['bkg_extra_min'] > 0 ? $row['bkg_extra_min'] : 0;
				$rowArray['bkg_service_tax']			 = $row['bkg_service_tax'];
				$rowArray['bkg_airport_entry_fee']		 = $row['bkg_airport_entry_fee'];
				$rowArray['bkg_advance_amount']			 = $row['bkg_advance_amount'] > 0 ? $row['bkg_advance_amount'] : '0';
				$rowArray['bkg_vendor_collected']		 = $row['bkg_vendor_collected'] > 0 ? $row['bkg_vendor_collected'] : '0';
				$rowArray['bkg_refund_amount']			 = $row['bkg_refund_amount'] > 0 ? $row['bkg_refund_amount'] : '0';
				$rowArray['bkg_credits_used']			 = $row['bkg_credits_used'] > 0 ? $row['bkg_credits_used'] : '0';
				$rowArray['cancelCharge']				 = $row['bkg_status'] == 9 ? $row['cancelCharge'] : '0';
				$rowArray['bkg_due_amount']				 = $row['bkg_due_amount'] > 0 ? $row['bkg_due_amount'] : '0';
				$rowArray['bkg_corporate_credit']		 = $row['adtPartnerWallet'];
				$rowArray['payableAmount']				 = $row['partnerPayableAmount'];
				$rowArray['bkg_partner_commission']		 = $row['adtCommission'] < 0 ? ($row['adtCommission'] * -1) : $row['bkg_partner_commission'];
				$rowArray['bkg_info_source']			 = $row['bkg_info_source'];
				$rowArray['bkg_platform']				 = $bookingPlatform[$row['bkg_platform']];
				$rowArray['paymentMode']				 = AccountTransDetails::model()->getPaymentModeByBkgId($row['bkg_id']); //$row['paymentMode'];
//                $bookingCabDetails                     = $row['bkg_status'] > 0 ? BookingCab::getBookingCabDetailsByBkgID($row['bkg_id']) : [];
				$rowArray['TotalVendorAssignedCount']	 = $bookingCabDetails['TotalVendorAssignedCount'] != null ? $bookingCabDetails['TotalVendorAssignedCount'] : "";
				$rowArray['LVendorAssignmentDate']		 = $bookingCabDetails['LVendorAssignmentDate'] != null ? $bookingCabDetails['LVendorAssignmentDate'] : "";
				$rowArray['FVendorAssignmentDate']		 = $bookingCabDetails['FVendorAssignmentDate'] != null ? $bookingCabDetails['FVendorAssignmentDate'] : "";
				$rowArray['LVendorAmount']				 = $bookingCabDetails['LVendorAmount'] != null ? $bookingCabDetails['LVendorAmount'] : "";
				$rowArray['FVendorAmount']				 = $bookingCabDetails['FVendorAmount'] != null ? $bookingCabDetails['FVendorAmount'] : "";
				$rowArray['LVendorID']					 = $bookingCabDetails['LVendorID'] != null ? $bookingCabDetails['LVendorID'] : "";
				$rowArray['FVendorID']					 = $bookingCabDetails['FVendorID'] != null ? $bookingCabDetails['FVendorID'] : "";
				$rowArray['dzpp_surge']					 = $row['dzpp_surge'] > 0 ? $row['dzpp_surge'] : 0;
				$row1									 = array_values($rowArray);
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
			$dataProvider	 = $modelsub->pickupReport($date1, $date2, $from, $to, $vendor, $platform, $status, $bkgType);
			$reportData		 = $model->pickupReportData($date1, $date2, $from, $to, $vendor, $platform, $bkgType);
		}
		$this->render('report_pickup', array('dataProvider' => $dataProvider, 'model' => $model, 'trailModel' => new BookingTrail(), 'reportData' => $reportData));
	}

	public function actionDailyAssignedReport()
	{
		$this->pageTitle	 = "Daily Assigned Report";
		$model				 = new BookingLog();
		$request			 = Yii::app()->request;
		$model->blg_created1 = date('Y-m-d');
		$model->blg_created2 = date('Y-m-d');
		if ($request->getParam('BookingLog'))
		{
			$model->attributes	 = $request->getParam('BookingLog');
			$model->adm_fname	 = $request->getParam('BookingLog')['adm_fname'];
			$model->adm_lname	 = $request->getParam('BookingLog')['adm_lname'];
			if ($request->getParam('BookingLog')['blg_created1'] != '')
			{
				$model->blg_created1 = DateTimeFormat::DatePickerToDate($request->getParam('BookingLog')['blg_created1']);
			}
			if ($request->getParam('BookingLog')['blg_created2'] != '')
			{
				$model->blg_created2 = DateTimeFormat::DatePickerToDate($request->getParam('BookingLog')['blg_created2']);
			}
			if ($request->getParam('blg_created1') != '')
			{
				$model->blg_created1 = $request->getParam('blg_created1');
			}
			if ($request->getParam('blg_created2') != '')
			{
				$model->blg_created2 = $request->getParam('blg_created2');
			}
		}
		$dataProvider = $model->getDailyAssignedCount();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('report_assigned', ['model' => $model, 'dataProvider' => $dataProvider]);
	}

	public function actionAutoAssign()
	{

		$this->pageTitle = "Auto Assignment Report";
		$model			 = new Booking();
		$preData		 = 0;

		if (isset($_REQUEST['Booking']))
		{
			$arr							 = Yii::app()->request->getParam('Booking');
			$preData						 = 1;
			$model->preData					 = 1;
			$model->bkg_create_date1		 = $arr['bkg_create_date1'];
			$model->bkg_create_date2		 = $arr['bkg_create_date2'];
			$model->bkg_pickup_date1		 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2		 = $arr['bkg_pickup_date2'];
			$model->tripAssignmnetFromTime	 = $arr['tripAssignmnetFromTime'];
			$model->tripAssignmnetToTime	 = $arr['tripAssignmnetToTime'];
			$model->bkg_service_class		 = $arr['bkg_service_class'];

			$cdate1	 = $model->bkg_create_date1;
			$cdate2	 = $model->bkg_create_date2;
			$pdate1	 = $model->bkg_pickup_date1;
			$pdate2	 = $model->bkg_pickup_date2;
			$adate1	 = $model->tripAssignmnetFromTime;
			$adate2	 = $model->tripAssignmnetToTime;
//            if($arr['tripAssignmnetTime'])
//			{
//            $model->tripAssignmnetTime= $arr['tripAssignmnetTime'];
//            $tripAssignmentTime = DateTimeFormat::DatePickerToDate($model->tripAssignmnetTime);
//			}

			if ($model->bkg_create_date1 == '' && $model->bkg_create_date2 == '')
			{
				$model->bkg_create_date1 = ""; //date('Y-m-d', strtotime('-7 days'));
				$model->bkg_create_date2 = ""; //date('Y-m-d');
				$cdate1					 = $model->bkg_create_date1;
				$cdate2					 = $model->bkg_create_date2;
			}
			if ($model->bkg_pickup_date1 == '' && $model->bkg_pickup_date2 == '')
			{
				$model->bkg_pickup_date1 = ""; //date('Y-m-d');
				$model->bkg_pickup_date2 = ""; //date('Y-m-d', strtotime('+7 days'));
				$pdate1					 = $model->bkg_pickup_date1;
				$pdate2					 = $model->bkg_pickup_date2;
			}
			if ($model->tripAssignmnetFromTime == '' && $model->tripAssignmnetToTime == '' && $preData == 0)
			{
				$model->tripAssignmnetFromTime	 = "";
				$model->tripAssignmnetToTime	 = "";
				$adate1							 = $model->tripAssignmnetFromTime;
				$adate2							 = $model->tripAssignmnetToTime;
			}
		}
//		else
//		{
//
//			$model->bkg_create_date1 = date('Y-m-d', strtotime('-7 days'));
//			$model->bkg_create_date2 = date('Y-m-d');
//			$cdate1					 = $model->bkg_create_date1;
//			$cdate2					 = $model->bkg_create_date2;
//
//			$model->bkg_pickup_date1 = date('Y-m-d');
//			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('+7 days'));
//			$pdate1					 = $model->bkg_pickup_date1;
//			$pdate2					 = $model->bkg_pickup_date2;
//
//			$model->tripAssignmnetFromTime	 = "";
//			$model->tripAssignmnetToTime	 = "";
//			$adate1							 = $model->tripAssignmnetFromTime;
//			$adate2							 = $model->tripAssignmnetToTime;
//		}


		if ($cdate1 == "" && $cdate2 == "")
		{
			$cdate2	 = ""; //date('d/m/Y');
			$cdate1	 = ""; //DateTimeFormat::DatePickerToDate($cdate1);
			$cdate2	 = ""; //DateTimeFormat::DatePickerToDate($cdate2);
		}
		if ($pdate1 == "" && $pdate2 == "")
		{
			$pdate2	 = ""; //date('d/m/Y');
			$pdate1	 = ""; //DateTimeFormat::DatePickerToDate($pdate1);
			$pdate2	 = ""; //DateTimeFormat::DatePickerToDate($pdate2);
		}
		if ($adate1 == "" && $adate2 == "" && $preData == 0)
		{
			$adate1	 = $adate2	 = date('Y-m-d');
		}
		if ($_REQUEST)
		{
			$is_amt						 = $param['is_advance_amount']	 = $_REQUEST['is_advance_amount'];
			$is_dbo						 = $param['is_dbo_applicable']	 = $_REQUEST['is_dbo_applicable'];
			$is_reconfirm				 = $param['is_reconfirm_flag']	 = $_REQUEST['is_reconfirm_flag'];
			$is_new						 = $param['is_New']			 = $_REQUEST['is_New'];
			$is_assigned				 = $param['is_Assigned']		 = $_REQUEST['is_Assigned'];
			$is_manual					 = $param['is_Manual']			 = $_REQUEST['is_Manual'];
		}
		else
		{
			$is_reconfirm	 = 1;
			$is_assigned	 = 1;
			$is_amt			 = 1;
		}

		$dataProvider							 = BookingSub::model()->autoAssignReport($model, $param);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('auto_assignment', array('dataProvider' => $dataProvider, 'model' => $model, 'fromDateFormated' => $cdate1, 'toDateFormated' => $cdate2), false, $outputJs);
	}

	public function actionOtpserved()
	{
		$row = Report::getRoleAccess(37);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "OTP - Report";
		$model			 = new BookingTrack();
		$arr			 = [];
		if (isset($_REQUEST['BookingTrack']))
		{
			$arr				 = Yii::app()->request->getParam('BookingTrack');
			$model->attributes	 = $arr;
		}
		$model->fromDate = $arr['fromDate'];
		$model->toDate	 = $arr['toDate'];
		$model->partner	 = $arr['partner'];
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$arr['fromDate']	 = Yii::app()->request->getParam('fromDate');
			$arr['toDate']		 = Yii::app()->request->getParam('toDate');
			$arr['partner']		 = Yii::app()->request->getParam('partner');
			$model->attributes	 = $arr;

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"Otpserved_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "Otpserved_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->getServedOTPReport($arr, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Pickup Week/Year', 'OTP Required', 'OTP Sent', 'OTP Verified', 'Total Booking Served']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['reportWeek']		 = $row['reportWeek'];
				$rowArray['otp_required']	 = $row['otp_required'];
				$rowArray['otp_sent']		 = $row['otp_sent'];
				$rowArray['otp_verified']	 = $row['otp_verified'];
				$rowArray['total_served']	 = $row['total_served'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getServedOTPReport($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_otp_served', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionTravellers()
	{
		$this->pageTitle = "Travellers Monthly Report";
		$model			 = new Booking();
		$month			 = 1;
		if (isset($_REQUEST['Booking']))
		{
			$arr	 = Yii::app()->request->getParam('Booking');
			$month	 = $arr['monthcount'];
		}
		$model->monthcount						 = $month;
		$dataProvider							 = $model->travellersMonthly($month);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('travellers_monthly', array('dataProvider' => $dataProvider, 'model' => $model), false, $outputJs);
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

	public function actionNpsscore()
	{
		$row = Report::getRoleAccess(43);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "NPS Score Report";
		$model			 = new Ratings;
		if (isset($_REQUEST['Ratings']))
		{
			$arr	 = Yii::app()->request->getParam('Ratings');
			$date1	 = $arr['rtg_date1'];
			$date2	 = $arr['rtg_date2'];
		}
		else
		{
			$date2	 = DateTimeFormat::DateToLocale(date());
			$date1	 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-30 days')));
		}
		$model->rtg_date1	 = $date1;
		$model->rtg_date2	 = $date2;
		$date1				 = DateTimeFormat::DatePickerToDate($date1);
		$date2				 = DateTimeFormat::DatePickerToDate($date2);

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->rtg_date1	 = Yii::app()->request->getParam('rtg_date1');
			$model->rtg_date2	 = Yii::app()->request->getParam('rtg_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"Npsscore_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "Npsscore_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->npsReport($date1, $date2, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Responded', 'Detractors', 'Passives', 'Promotors', 'NPS Score']);
			foreach ($rows as $row)
			{
				$rowArray				 = array();
				$rowArray['responded']	 = $row['responded'];
				$rowArray['detractors']	 = $row['detractors'];
				$rowArray['passives']	 = $row['passives'];
				$rowArray['promotors']	 = $row['promotors'];
				$rowArray['nps_score']	 = $row['nps'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}


		$dataProvider = $model->npsReport($date1, $date2);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_nps', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
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

	public function actionRatingReport()
	{
		$row = Report::getRoleAccess(66);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('bkg_pickup_date1');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('bkg_pickup_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RatingReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "RatingReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = Ratings::model()->getRatingsByBookingType($model->bkg_pickup_date1, $model->bkg_pickup_date2, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['', 'No. of OW Bookings', 'No. of RT Bookings', 'No. of AT Bookings', 'No. of DR Bookings', 'No. of LT Bookings', 'Average rating OW Bookings',
				'Average rating RT Bookings', 'Average rating AT Bookings', 'Average rating DR Bookings', 'Average rating LT Bookings']);
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

		$dataProvider = Ratings::model()->getRatingsByBookingType($model->bkg_pickup_date1, $model->bkg_pickup_date2);
		$this->render('rating_report', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
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

	public function actionPartnerWiseCountBooking()
	{
		$row = Report::getRoleAccess(71);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Partner Booking Count - Report(B2B Other)";
		$model			 = new Booking();
		$date1			 = date("Y-m-d");
		$date2			 = date("Y-m-d");
		$pickupFromDate	 = "";
		$pickupToDate	 = "";
		$agentId		 = 0;

		$request = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$date1			 = $arr['bkg_create_date1'];
			$date2			 = $arr['bkg_create_date2'];
			$pickupFromDate	 = $arr['bkg_pickup_date1'];
			$pickupToDate	 = $arr['bkg_pickup_date2'];
			$agentId		 = $arr['bkg_agent_id'];
		}

		if (isset($_REQUEST['bkg_create_date1']) || isset($_REQUEST['bkg_pickup_date1']))
		{
			$fromdate	 = Yii::app()->request->getParam('bkg_create_date1');
			$todate		 = Yii::app()->request->getParam('bkg_create_date2');
			$pickupFromDate	 = Yii::app()->request->getParam('bkg_pickup_date1');
			$pickupToDate		 = Yii::app()->request->getParam('bkg_pickup_date2');
			
			$agentId	 = Yii::app()->request->getParam('agent_id');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"PartnerWiseCountBooking_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "PartnerWiseCountBooking_" . date('YmdHi') . ".csv";
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
				$rows	 = BookingSub::model()->getPartnerWiseCountBookingReport($fromdate, $todate, $agentId, $command = true, $pickupFromDate, $pickupToDate);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, [
					'Partner Name',
					'Created Count',
					'Served Count',
					'Quoted Count',
					'Cancelled Count',
					'Gozo Intiated Cancellation',
					'Total Amount',
					'Gozo Amount',
					'Net Gross Margin (%)',
					'Booking Ids'
				]);
				foreach ($rows as $row)
				{
					$rowArray							 = array();
					$rowArray['bkg_agent_id']			 = Agents::model()->findByPk($row['bkg_agent_id'])->agt_company;
					$rowArray['created_booking']		 = $row['cnt'];
					$rowArray['total_served_booking']	 = $row['total_served_booking'];
					$rowArray['quoted_booking']			 = $row['quoted_booking'];
					$rowArray['cancelled_booking']		 = $row['cancelled_booking'];
					$rowArray['gozo_intiated_cancel']	 = $row['gozo_intiated_cancel'];
					$rowArray['totalamount']			 = $row['totalamount'];
					$rowArray['gozoamount']				 = $row['gozoamount'];
					$rowArray['netgrossmargin']			 = $row['netgrossmargin'];
					$rowArray['booking_id']				 = "'" . (string) $row['booking_id'] . "'";
					$row1								 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}

		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$model->bkg_pickup_date1 = $pickupFromDate;
		$model->bkg_pickup_date2 = $pickupToDate;
		$model->bkg_agent_id	 = $agentId > 0 ? $agentId : "";

		$dataProvider = BookingSub::model()->getPartnerWiseCountBookingReport($date1, $date2, $agentId, false, $pickupFromDate, $pickupToDate);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('partner_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionBookingtrackdetails()
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
					'Penalty Amount',
					'Vendor Assign Time',
					'Driver Assign Time',
					'Cab Assign Time',
					'Driver Arrival Time',
                    'Time Diff(pickup and arrival)',
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
					$rowArray['bkg_amount']				 = $row['adt_amount'];
					$rowArray['btrVendorAssignLdate']	 = ($row['btr_vendor_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_vendor_assign_ldate'])) : '';
					$rowArray['btrDriverAssignLdate']	 = ($row['btr_driver_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_driver_assign_ldate'])) : '';
					$rowArray['btrCabAssignLdate']		 = ($row['btr_cab_assign_ldate']) ? date("d/m/Y H:i:s", strtotime($row['btr_cab_assign_ldate'])) : '';
					$rowArray['bkgTripArriveTime']		 = ($row['bkg_trip_arrive_time']) ? date("d/m/Y H:i:s", strtotime($row['bkg_trip_arrive_time'])) : '';
                    
                    $min = '';
                    if($row['bkg_pickup_date'] != null && $row['bkg_trip_arrive_time'] != null)
                    {
                         $dateTimeObject1 = date_create($row['bkg_trip_arrive_time']);  
                         $dateTimeObject2 = date_create($row['bkg_pickup_date']);  
                         $interval = date_diff($dateTimeObject1, $dateTimeObject2); 
                         $min = $interval->days * 24 * 60; 
                         $min += $interval->h * 60; 
                         $min += $interval->i;
                         $min = $min.' Min'; 
                    }
                    
                    $rowArray['timeDiffPickArrival']     = $min;
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

		//$arr['bkg_agent_id'] = 18190;
		$dataProvider = BookingTrail::getBookingTrackDetails($arr, 'Command');
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('bookingtrackdetails', array('model' => $model, 'dataProvider' => $dataProvider, 'checked' => $checked, 'roles' => $row));
	}

	public function actionservicePerformance()
	{
		$row = Report::getRoleAccess(77);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('hightiersperformance', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
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
		$row = Report::getRoleAccess(81);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('dailyloss', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionBookingReport()
	{
		$row = Report::getRoleAccess(86);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		$this->render('bookingReport', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionMatchList()
	{
		$row = Report::getRoleAccess(3);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Smart Match Update";
		$model			 = new Booking();
		$booksub		 = new BookingSub();
		$smartBroken	 = $smartSuccessful = 0;
		if (isset($_REQUEST['Booking']))
		{
			$arr						 = Yii::app()->request->getParam('Booking');
			$model->attributes			 = $arr;
			//$model->attributes = Yii::app()->request->getParam('Booking');
			$model->bkg_smart_broken	 = $arr['bkg_smart_broken'];
			$model->bkg_smart_successful = $arr['bkg_smart_successful'];
			$model->trip_id				 = $arr['trip_id'];
		}
		$dataProvider = $booksub->getSmartMatchList(0, false, $model->bkg_smart_broken, $model->bkg_smart_successful, $model->trip_id);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		if (isset($_REQUEST['export1']))
		{
			$smartBroken	 = Yii::app()->request->getParam('export_smart_broken');
			$smartSuccessful = Yii::app()->request->getParam('export_smart_successful');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"SmartMatchReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename		 = "smatchMatch" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername		 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
			$backup_file	 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows = $booksub->getSmartMatchList(0, true, $smartBroken, $smartSuccessful);

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Trip ID', 'Booking Id(s)', 'From City(s)', 'To City(s)', 'Trip Amount', 'Vendor Amount Original', 'Vendor Amount Matched',
				'Service Tax', 'Gozo Amount', 'Gozo Amount Matched', 'Margin Original(%)', 'Margin Matched(%)', 'Date', 'Match Type', 'Matched By']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'trip_id'					 => $row['trip_id'],
					'booking_ids'				 => $row['booking_ids'],
					'from_city_ids'				 => $row['from_city_ids'],
					'to_city_ids'				 => $row['to_city_ids'],
					'trip_amount'				 => $row['trip_amount'],
					'vendor_amount_original'	 => $row['vendor_amount_original'],
					'vendor_amount_smart_match'	 => $row['vendor_amount_smart_match'],
					'service_tax_amount'		 => $row['service_tax_amount'],
					'gozo_amount_original'		 => $row['gozo_amount_original'],
					'gozo_amount_smart_match'	 => $row['gozo_amount_smart_match'],
					'margin_original'			 => ($row['margin_original'] * 100),
					'margin_smart_match'		 => ($row['margin_smart_match'] * 100),
					'match_date'				 => $row['match_date'],
					'matchtype'					 => $row['matchtype'],
					'name'						 => $row['name']
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
		$this->render('match_list', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionQuotebooking()
	{
		$row = Report::getRoleAccess(90);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Quote Booking";
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
		$this->$method('quotebooking', array('model' => $model, 'quote' => $quotes, 'roles' => $row), false, $outputJs);
	}

	public function actionEscalation()
	{
		$row = Report::getRoleAccess(104);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Escalation Report";
		$bkgTrail		 = new BookingTrail();
		$request		 = Yii::app()->request;
		if ($request->getParam('BookingTrail'))
		{
			$bkgtrailarr = $request->getParam('BookingTrail');
			$teams		 = implode(",", $bkgtrailarr['btr_escalation_assigned_team']);
		}
		if (Yii::app()->request->getParam('btr_escalation_assigned_team') != null && $_REQUEST['export'] == true)
		{
			$teams		 = Yii::app()->request->getParam('btr_escalation_assigned_team');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"EscalationReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "EscalationReport_" . date('YmdHi') . ".csv";
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
			$rows = BookingSub::model()->getEscalationlist($teams, DBUtil::ReturnType_Query);

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id(s)', 'Pickup Date', 'Booking Status', 'Est. Completion time', 'Escalation Level', 'Name of Owner', 'Escalation Raised By', 'Teams Effected', 'First Escalation set time', 'Last Escalation set time']);
			foreach ($rows as $row)
			{

				$arr	 = Booking::model()->getActiveBookingStatus();
				$elarr	 = BookingTrail::model()->escalation;
				$array	 = explode(',', $row['btr_escalation_assigned_team']);
				foreach ($array as $key => $value)
				{
					if ($value)
					{
						$team							 = Teams::getByID($value);
						$btr_escalation_assigned_team	 = $key + '1' . '.  ' . $team . "<br />";
					}
					else
					{
						$btr_escalation_assigned_team = "None";
					}
				}


				$rowArray	 = array(
					'bkg_id'						 => $row['bkg_id'],
					'bkg_pickup_date'				 => $row['bkg_pickup_date'],
					'bkg_status'					 => $arr[$row['bkg_status']],
					'trip_completion_time'			 => $row['trip_completion_time'],
					'btr_escalation_level'			 => $elarr[$row['btr_escalation_level']]['color'],
					'btr_escalation_assigned_lead'	 => Admins::model()->getFullNameById($row['btr_escalation_assigned_lead']),
					'escaltion_usr_id'				 => Admins::model()->getFullNameById($row['escaltion_usr_id']),
					'btr_escalation_assigned_team'	 => $btr_escalation_assigned_team,
					'btr_escalation_fdate'			 => $row['btr_escalation_fdate'],
					'btr_escalation_ldate'			 => $row['btr_escalation_ldate']
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
		$dataProvider	 = BookingSub::model()->getEscalationlist($teams);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('escalation_list', array('dataProvider' => $dataProvider, 'bkgTrail' => $bkgTrail, 'teams' => $teams, 'roles' => $row), false, $outputJs);
	}

	public function actionMargin()
	{
		$row = Report::getRoleAccess(105);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Margin for last minute bookings";
		$request		 = Yii::app()->request;
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$picupDate1				 = $data['bkg_pickup_date1'];
				$picupDate2				 = $data['bkg_pickup_date2'];
				$model->bkg_pickup_date1 = $picupDate1;
				$model->bkg_pickup_date2 = $picupDate2;
			}
			$bkgTypes				 = $data['bkg_booking_type'];
			$model->bkg_booking_type = implode(",", $data['bkg_booking_type']);
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}






		if ($_REQUEST['export'] == true)
		{
			$pickUps->from_date	 = Yii::app()->request->getParam('from_date');
			$pickUps->to_date	 = Yii::app()->request->getParam('to_date');
			$bkgTypes			 = Yii::app()->request->getParam('bkg_types');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"MarginReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "MarginReport_" . date('YmdHi') . ".csv";
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
			$rows = BookingSub::marginLastMinBooking($model, DBUtil::ReturnType_Query);

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Total Booking Count', 'Trip Type', 'Pickup Bins', 'Profit']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'TotalBookingCnt'	 => $row['TotalBookingCnt'],
					'tripType'			 => $row['tripType'],
					'PickupBins'		 => $row['PickupBins'],
					'Profit'			 => $row['Profit'],
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
		$dataProvider	 = BookingSub::marginLastMinBooking($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('margin_list', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'bkgTypes'		 => $bkgTypes,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionHistory()
	{
		$row = Report::getRoleAccess(106);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Booking History By create date";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$picupDate1				 = $data['bkg_create_date1'] . " 00:00:00";
				$picupDate2				 = $data['bkg_create_date2'] . " 23:59:59";
				$model->bkg_create_date1 = $picupDate1;
				$model->bkg_create_date2 = $picupDate2;
			}
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}

		if ($_REQUEST['export'] == true)
		{
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"BookingHistoryReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "BookingHistory_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::bookingHistoryByCreateDate($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['createDate', 'MMT/IBIBO(ALL/UV/QT/SV/CN)', 'MMT Served %', 'EMT(ALL/UV/QT/SV/CN)', 'EMT Served %', 'SPICE(ALL/UV/QT/SV/CN)', 'SPICE Served %', 'B2B_OTHER(ALL/UV/QT/SV/CN)', 'B2B_OTHER Served %', 'B2C(ALL/UV/QT/SV/CN)', 'B2C Served %', 'All(ALL/UV/QT/SV/CN)', 'All Served %']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'createDate'				 => $row['createDate'],
					'MMT/IBIBO(ALL/UV/QT/SV/CN)' => ($row['AllCntMMT'] . "/" . $row['UnVerifiedCntMMT'] . "/" . $row['QuotedCntMMT'] . "/" . $row['ServedCntMMT'] . "/" . $row['CancelledCntMMT']),
					'MMT Served %'				 => ($row['AllCntMMT'] > 0) ? ROUND(100 * ($row['ServedCntMMT']) / $row['AllCntMMT']) : 0,
					'EMT(ALL/UV/QT/SV/CN)'		 => ($row['AllCntEMT'] . "/" . $row['UnVerifiedCntEMT'] . "/" . $row['QuotedCntEMT'] . "/" . $row['ServedCntEMT'] . "/" . $row['CancelledCntEMT']),
					'EMT Served %'				 => ($row['AllCntEMT'] > 0) ? ROUND(100 * ($row['ServedCntEMT']) / $row['AllCntEMT']) : 0,
					'SPICE(ALL/UV/QT/SV/CN)'	 => ($row['AllCntSPICE'] . "/" . $row['UnVerifiedCntSPICE'] . "/" . $row['QuotedCntSPICE'] . "/" . $row['ServedCntSPICE'] . "/" . $row['CancelledCntSPICE']),
					'SPICE Served %'			 => ($row['AllCntSPICE'] > 0) ? ROUND(100 * ($row['ServedCntSPICE']) / $row['AllCntSPICE']) : 0,
					'B2B_OTHER(ALL/UV/QT/SV/CN)' => ($row['AllCntB2B'] . "/" . $row['UnVerifiedCntB2B'] . "/" . $row['QuotedCntB2B'] . "/" . $row['ServedCntB2B'] . "/" . $row['CancelledCntB2B']),
					'B2B_OTHER Served %'		 => ($row['AllCntB2B'] > 0) ? ROUND(100 * ($row['ServedCntB2B']) / $row['AllCntB2B']) : 0,
					'B2C(ALL/UV/QT/SV/CN)'		 => ($row['AllCntB2C'] . "/" . $row['UnVerifiedCntB2C'] . "/" . $row['QuotedCntB2C'] . "/" . $row['ServedCntB2C'] . "/" . $row['CancelledCntB2C']),
					'B2C Served %'				 => ($row['AllCntB2C'] > 0) ? ROUND(100 * ($row['ServedCntB2C']) / $row['AllCntB2C']) : 0,
					'All(ALL/UV/QT/SV/CN)'		 => ($row['AllCnt'] . "/" . $row['AllUnVerifiedCnt'] . "/" . $row['AllQuotedCnt'] . "/" . $row['AllServedCnt'] . "/" . $row['AllCancelledCnt']),
					'All Served %'				 => ($row['AllCnt'] > 0) ? ROUND(100 * ($row['AllServedCnt']) / $row['AllCnt']) : 0,
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
		$dataProvider	 = BookingSub::bookingHistoryByCreateDate($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('history_list', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionCancelHistory()
	{
		$row = Report::getRoleAccess(109);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Cancel History By pickup date";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$picupDate1				 = $data['bkg_pickup_date1'];
				$picupDate2				 = $data['bkg_pickup_date2'];
				$model->bkg_pickup_date1 = $picupDate1;
				$model->bkg_pickup_date2 = $picupDate2;
			}
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}



		if ($_REQUEST['export'] == true)
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CancelHistoryReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "CancelHistory_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::cancelHistoryByPickupDate($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['pickupDate', 'MMT/IBIBO(SC/TC/CI/GI/GI_TC(%))', 'EMT(SC/TC/CI/GI/GI_TC(%))', 'SPICE(SC/TC/CI/GI/GI_TC(%))', 'B2C(SC/TC/CI/GI/GI_TC(%))']);
			foreach ($rows as $row)
			{
				$MMT_GI_TC_PER	 = $row['MMT_GI_TC_PER'] > 0 ? $row['MMT_GI_TC_PER'] : 0.00;
				$EMT_GI_TC_PER	 = $row['EMT_GI_TC_PER'] > 0 ? $row['EMT_GI_TC_PER'] : 0.00;
				$SPICE_GI_TC_PER = $row['SPICE_GI_TC_PER'] > 0 ? $row['SPICE_GI_TC_PER'] : 0.00;
				$B2C_GI_TC_PER	 = $row['B2C_GI_TC_PER'] > 0 ? $row['B2C_GI_TC_PER'] : 0.00;

				$rowArray	 = array(
					'pickupDate'						 => $row['pickupDate'],
					'MMT/IBIBO(SC/TC/CI/GI/GI_TC(%))'	 => ($row['MMTSC'] . "/" . $row['MMTTC'] . "/" . $row['MMTCI'] . "/" . $row['MMTGI'] . "/" . $MMT_GI_TC_PER),
					'EMT(SC/TC/CI/GI/GI_TC(%))'			 => ($row['EMTSC'] . "/" . $row['EMTTC'] . "/" . $row['EMTCI'] . "/" . $row['EMTGI'] . "/" . $EMT_GI_TC_PER),
					'SPICE(SC/TC/CI/GI/GI_TC(%))'		 => ($row['SPICESC'] . "/" . $row['SPICETC'] . "/" . $row['SPICECI'] . "/" . $row['SPICEGI'] . "/" . $SPICE_GI_TC_PER),
					'B2C(SC/TC/CI/GI/GI_TC(%))'			 => ($row['B2CSC'] . "/" . $row['B2CTC'] . "/" . $row['B2CCI'] . "/" . $row['B2CGI'] . "/" . $B2C_GI_TC_PER),
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
		$dataProvider	 = BookingSub::cancelHistoryByPickupDate($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('cancel_history_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionAssignment()
	{
		$row = Report::getRoleAccess(110);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Assignment Report";
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
			if ($data['bkg_assigned_date1'] != '' && $data['bkg_assigned_date2'] != '')
			{
				$assignedDate1Data	 = explode(' ', $data['bkg_assigned_date1']);
				$assignedDate2Data	 = explode(' ', $data['bkg_assigned_date2']);
				$assignedDate1		 = ($assignedDate1Data[1] == '00:00:00') ? $data['bkg_assigned_date1'] : $data['bkg_assigned_date1'] . ' 00:00:00';
				$assignedDate2		 = ($assignedDate2Data[1] == '23:59:59') ? $data['bkg_assigned_date2'] : $data['bkg_assigned_date2'] . ' 23:59:59';

				$model->bkg_assigned_date1	 = $assignedDate1;
				$model->bkg_assigned_date2	 = $assignedDate2;
			}
			$bkgtypes		 = $data['bkgtypes'];
			$regions		 = $data['bkg_region'];
			$serviceClass	 = $data['bkg_service_class'];

			$model->bkg_booking_type	 = implode(",", $bkgtypes);
			$model->bkg_region			 = implode(",", $regions);
			$model->bkg_service_class	 = implode(",", $serviceClass);
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";

			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";

			$model->bkg_assigned_date1	 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_assigned_date2	 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}

		if ($_REQUEST['export'] == true)
		{
			$model->bkg_create_date1	 = Yii::app()->request->getParam('create_date1');
			$model->bkg_create_date2	 = Yii::app()->request->getParam('create_date2');
			$model->bkg_pickup_date1	 = Yii::app()->request->getParam('pickup_date1');
			$model->bkg_pickup_date2	 = Yii::app()->request->getParam('pickup_date2');
			$model->bkg_assigned_date1	 = Yii::app()->request->getParam('assigned_date1');
			$model->bkg_assigned_date2	 = Yii::app()->request->getParam('assigned_date2');
			$model->bkg_booking_type	 = Yii::app()->request->getParam('bkg_booking_type');
			$model->bkg_region			 = Yii::app()->request->getParam('bkg_region');
			$model->bkg_service_class	 = Yii::app()->request->getParam('bkg_service_class');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AssignmentReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "BookingHistory_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingCab::assignmentReport($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Trip Id', 'Booking Id', 'Create Date', 'Pickup Date', 'Bid Start Date', 'Driver/Cab Assigned Date', 'Vendor Assigned Date', 'Mutual Assign Date', 'Critical Assign Date', 'DemSup Misfire', 'Reconfirm', 'Booking Vendor Amount', 'Trip Vendor Amount', 'Booking Advanced Amount', 'Booking Total Amount', 'Avg Bid Amount', 'Max Bid Amount', 'Min Bid Amount', 'Bid Count', 'Scv Label', 'Booking Type', 'Stt Zone']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'Trip Id'					 => $row['trip_id'],
					'Booking Id'				 => $row['booking_ids'],
					'Create Date'				 => $row['create_date'],
					'Pickup Date'				 => $row['pickup_date'],
					'Bid Start Date'			 => $row['bid_start_date'],
					'Driver/Cab Assigned Date'	 => $row['Driver_Cab_Assigned_Date'],
					'Vendor Assigned Date'		 => $row['Vendor_Assigned_Date'],
					'Mutual Assign Date'		 => $row['manual_assign_date'],
					'Critical Assign Date'		 => $row['critical_assign_date'],
					'DemSup Misfire'			 => $row['demSup_misfire'],
					'Reconfirm'					 => $row['reconfirm'],
					'Booking Vendor Amount'		 => $row['booking_vendor_amount'],
					'Trip Vendor Amount'		 => $row['trip_vendor_amount'],
					'Booking Advanced Amount'	 => $row['booking_advanced_amount'],
					'Booking Total Amount'		 => $row['booking_total_amount'],
					'Avg Bid Amount'			 => $row['avg_bid_amount'],
					'Max Bid Amount'			 => $row['max_bid_amount'],
					'Min Bid Amount'			 => $row['min_bid_amount'],
					'Bid Count'					 => $row['bid_count'],
					'Scv Label'					 => $row['scv_label'],
					'Booking Type'				 => Booking::model()->getBookingType($row['bkg_booking_type']),
					'Stt Zone'					 => States::findUniqueZone($row['stt_zone']),
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

		$dataProvider	 = BookingCab::assignmentReport($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('assignment_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'bkgtypes'		 => $bkgtypes,
			'regions'		 => $regions,
			'serviceClass'	 => $serviceClass,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionLossAssignment()
	{
		$row = Report::getRoleAccess(111);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Loss Assignment Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1Data = explode(' ', $data['bkg_create_date1']);
				$createDate2Data = explode(' ', $data['bkg_create_date2']);
				$createDate1	 = ($createDate1Data[1] == '00:00:00') ? $data['bkg_create_date1'] : $data['bkg_create_date1'] . ' 00:00:00';
				$createDate2	 = ($createDate2Data[1] == '23:59:59') ? $data['bkg_create_date2'] : $data['bkg_create_date2'] . ' 23:59:59';

				$model->bkg_create_date1 = $createDate1;
				$model->bkg_create_date2 = $createDate2;
			}
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$pickupDate1Data = explode(' ', $data['bkg_pickup_date1']);
				$pickupDate2Data = explode(' ', $data['bkg_pickup_date2']);
				$pickupDate1	 = ($pickupDate1Data[1] == '00:00:00') ? $data['bkg_pickup_date1'] : $data['bkg_pickup_date1'] . ' 00:00:00';
				$pickupDate2	 = ($pickupDate2Data[1] == '23:59:59') ? $data['bkg_pickup_date2'] : $data['bkg_pickup_date2'] . ' 23:59:59';

				$model->bkg_pickup_date1 = $pickupDate1;
				$model->bkg_pickup_date2 = $pickupDate2;
			}
			$bkgTypes				 = $data['bkg_booking_type'];
			$model->bkg_booking_type = implode(",", $data['bkg_booking_type']);
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";

			$model->bkg_pickup_date1 = "";
			$model->bkg_pickup_date2 = "";
		}

		if ($_REQUEST['export'] == true)
		{
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('pickup_date1');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('pickup_date2');
			$model->bkg_booking_type = Yii::app()->request->getParam('booking_type');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LossAssignmentReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "LossAssignment" . date('YmdHi') . ".csv";
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

			$rows	 = BookingInvoice::lossAssignmentBkgs($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Zone Type', 'Source Zone', 'Destination Zone', 'Is Local', 'Booking Type', 'Loss Amount', 'C2P_bucket', 'First_A2P_bucket', 'Last_A2P_bucket']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'Booking Id'		 => $row['BookingId'],
					'Zone Type'			 => $row['ZoneType'],
					'Source Zone'		 => $row['SourceZone'],
					'Destination Zone'	 => $row['DestinationZone'],
					'Is Local'			 => $row['IsLocal'],
					'bkg_booking_type'	 => Booking::model()->getBookingType($row['bkg_booking_type']),
					'Loss Amount'		 => $row['LossAmount'],
					'C2P_bucket'		 => Filter::getBucket($row['C2P_bucket']),
					'First_A2P_bucket'	 => Filter::getBucket($row['First_A2P_bucket']),
					'Last_A2P_bucket'	 => Filter::getBucket($row['Last_A2P_bucket'])
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
		$dataProvider	 = BookingInvoice::lossAssignmentBkgs($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('loss_assignment_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'bkgTypes'		 => $bkgTypes,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionLocalBooking()
	{
		$row = Report::getRoleAccess(113);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Local Booking By create date";

		$model	 = new Booking();
		$request = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$picupDate1				 = $data['bkg_create_date1'];
				$picupDate2				 = $data['bkg_create_date2'];
				$model->bkg_create_date1 = $picupDate1;
				$model->bkg_create_date2 = $picupDate2;
			}
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}

		if ($_REQUEST['export'] == true)
		{
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LocalBookingReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "LocalBooking_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::localBookingByCreateDate($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['pickupDate', 'MMT/IBIBO(Booking/Completed/Cancel)', 'EMT(Booking/Completed/Cancel)', 'SPICEJET(Booking/Completed/Cancel)', 'OTHER_B2B(Booking/Completed/Cancel)', 'B2C(Booking/Completed/Cancel)', 'Total(Booking/Completed/Cancel)']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'DATE'									 => $row['DATE'],
					'MMT/IBIBO(Booking/Completed/Cancel)'	 => ($row['MMT/IBIBO_Booking'] . "/" . $row['MMT/IBIBO_Completed'] . "/" . $row['MMT/IBIBO_Cancel']),
					'EMT(Booking/Completed/Cancel)'			 => ($row['EMT_Booking'] . "/" . $row['EMT_Completed'] . "/" . $row['EMT_Cancel']),
					'SPICEJET(Booking/Completed/Cancel)'	 => ($row['SPICEJET_Booking'] . "/" . $row['SPICEJET_Completed'] . "/" . $row['SPICEJET_Cancel']),
					'OTHER_B2B(Booking/Completed/Cancel)'	 => ($row['OTHER_B2B_Booking'] . "/" . $row['OTHER_B2B_Completed'] . "/" . $row['OTHER_B2B_Cancel']),
					'B2C(Booking/Completed/Cancel)'			 => ($row['B2C_Booking'] . "/" . $row['B2C_Completed'] . "/" . $row['B2C_Cancel']),
					'Total(Booking/Completed/Cancel)'		 => ($row['Total_Booking'] . "/" . $row['Total_Completed'] . "/" . $row['Total_Cancel']),
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
		$dataProvider	 = BookingSub::localBookingByCreateDate($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('local_booking_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionStateWiseBookingCount()
	{
		$row = Report::getRoleAccess(117);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$model					 = new Booking();
		$this->pageTitle		 = "Month/State wise booking count";
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$model->bkg_pickup_date1 = $date1;
		$model->bkg_pickup_date2 = $date2;

		$bkgTypes	 = null;
		$req		 = Yii::app()->request;

		if ($req->getParam('Booking'))
		{
			$arr					 = $req->getParam('Booking');
			$date1					 = $arr['bkg_pickup_date1'];
			$date2					 = $arr['bkg_pickup_date2'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkgtypes		 = $arr['bkgtypes'];
			$model->bkg_pickup_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_pickup_date2 = $todate->format('Y-m-d') . " 23:59:59";
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('-15 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		}
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$bkgType	 = Yii::app()->request->getParam('export_bkgtype');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"StateWiseBookingCount" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "StateWiseBookingCount" . date('YmdHi') . ".csv";
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
			$model->bkg_pickup_date1 = $date1;
			$model->bkg_pickup_date2 = $date2;
			$model->bkgtypes		 = ($bkgType != '') ? explode(',', $bkgType) : null;

			$rows	 = Booking::model()->stateWiseBookingCount($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Count', 'Pickup Month', 'State Name']);
			foreach ($rows as $data)
			{
				$rowArray				 = array();
				$rowArray['Count']		 = $data['cnt'];
				$rowArray['PickupMonth'] = $data['PickupMonth'];
				$rowArray['StateName']	 = $data['StateName'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->stateWiseBookingCount($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('statewisebookingcount', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, true);
	}

	public function actionAnyAssignment()
	{
		$row = Report::getRoleAccess(118);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Any assignment yesterday";
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
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";

			$model->bkg_create_date1 = '';
			$model->bkg_create_date2 = '';
		}

		if ($_REQUEST['export'] == true)
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('to_date');

			$model->bkg_create_date1 = Yii::app()->request->getParam('create_date1');
			$model->bkg_create_date2 = Yii::app()->request->getParam('create_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AnyAssignmentReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "AnyAssignmentReport_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::bookingByAssignment($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['bookingId', 'Gozen', 'Route Name', 'Create Date', 'Pickup Date', 'Booking Amount', 'Gozo Amount', 'Profit']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'bkg_id'		 => $row['bkg_id'],
					'gozen'			 => $row['gozen'],
					'route_name'	 => $row['route_name'],
					'create_date'	 => $row['create_date'],
					'pickup_date'	 => $row['pickup_date'],
					'booking_amount' => $row['booking_amount'],
					'gozo_amount'	 => $row['gozo_amount'],
					'profit'		 => $row['profit'],
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
		$dataProvider	 = BookingSub::bookingByAssignment($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('any_assignment_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionCancellationList()
	{
		$row = Report::getRoleAccess(119);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Cancellation reason report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1			 = $data['bkg_create_date1'] . " 00:00:00";
				$createDate2			 = $data['bkg_create_date2'] . " 23:59:59";
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
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CancellationList_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "CancellationList_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::cancellationReasonList($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['bookingId',
				'UserName',
				'Booking Type',
				'Create Date',
				'Pickup Date',
				'Booking Route',
				'Arrival Time',
				'Cancel Date',
				'Total Amount',
				'Delete Reason',
				'Cancel Reason',
				'Refund Amount',
				'Cancel By',
				'Cancel Charge',
				'DBO Status']);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'bkg_id'			 => $row['bkg_id'],
					'UserName'			 => ($row['bkg_user_fname'] . " " . $row['bkg_user_lname']),
					'bkg_booking_type'	 => Booking::model()->getBookingType($row['bkg_booking_type']),
					'bkg_create_date'	 => $row['bkg_create_date'],
					'bkg_pickup_date'	 => $row['bkg_pickup_date'],
					'Booking_Route'		 => $row['from_cty_name'] . " - " . $row['to_cty_name'],
					'Arrival_Time'		 => $row['Arrival_Time'],
					'Cancel_Date'		 => $row['Cancel_Date'],
					'bkg_total_amount'	 => $row['bkg_total_amount'],
					'DeleteReason'		 => $row['DeleteReason'],
					'CancelReason'		 => $row['CancelReason'],
					'Refund_Amount'		 => $row['Refund_Amount'],
					'Cancel_By'			 => $row['Cancel_By'],
					'Cancel_Charge'		 => $row['Cancel_Charge'],
					'is_dbo'			 => ($row['is_dbo'] >0) ? 'ON' : 'OFF',
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

		$dataProvider	 = BookingSub::cancellationReasonList($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('cancellation_history', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionUpperTierList()
	{
		$row = Report::getRoleAccess(120);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Upper tier report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$createDate1			 = $data['bkg_pickup_date1'] . " 00:00:00";
				$createDate2			 = $data['bkg_pickup_date2'] . " 23:59:59";
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
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"UpperTierList_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "UpperTierList_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::getUpperTierList($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Pickup Date',
				'Booking Count',
				'Total Booking Count',
				'Profit'
			]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'pickup_date'			 => $row['pickup_date'],
					'booking_count'			 => $row['booking_count'],
					'total_booking_count'	 => $row['total_booking_count'],
					'profit'				 => $row['profit']
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

		$dataProvider	 = BookingSub::getUpperTierList($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('upper_tier_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionQuoteBasedRatePerKm()
	{
		$row = Report::getRoleAccess(121);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Quote based rate per km";
		$request		 = Yii::app()->request;

		$model					 = new Booking();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$bkgTypes				 = null;
		$req					 = Yii::app()->request;
		if ($req->getParam('Booking'))
		{
			$arr					 = $req->getParam('Booking');
			$date1					 = $arr['bkg_create_date1'];
			$date2					 = $arr['bkg_create_date2'];
			$zoneType				 = $arr['zoneType'];
			$bkgtypes				 = $arr['bkgtypes'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkg_create_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_create_date2 = $todate->format('Y-m-d') . " 23:59:59";
			$model->zoneType		 = $zoneType;
			$model->bkgtypes		 = $bkgtypes;
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
			$zoneType	 = Yii::app()->request->getParam('export_zonetype');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"QuoteBasedRatePerKm" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "QuoteBasedRatePerKm" . date('YmdHi') . ".csv";
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
			$model->zoneType		 = ($zoneType != '') ? explode(',', $zoneType) : null;

			$rows	 = Booking::model()->ratePerKmByQuote($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Create Date', 'Pickup Date', 'Region', 'Booking Type', 'Vehicle Type', 'From Zone',
				'To Zone', 'Zone Type', 'Service Class', 'Surge Type', 'Trip distance', 'Reliazed Margin', 'Rate Per Kilometer']);
			foreach ($rows as $data)
			{
				$rowArray						 = array();
				$rowArray['BookingId']			 = $data['bookingId'];
				$rowArray['CreateDate']			 = $data['Create Date'];
				$rowArray['PickupDate']			 = $data['Pickup Date'];
				$rowArray['Region']				 = $data['Region'];
				$rowArray['BookingType']		 = $data['Booking Type'];
				$rowArray['VehicleType']		 = $data['Vehicle Type'];
				$rowArray['FromZone']			 = $data['From Zone'];
				$rowArray['ToZone']				 = $data['To Zone'];
				$rowArray['ZoneType']			 = $data['Zone Type'];
				$rowArray['ServiceClass']		 = $data['Service Class'];
				$rowArray['surgeType']			 = $data['surge Type'];
				$rowArray['Tripdistance']		 = $data['Trip distance'];
				$rowArray['ReliazedMargin']		 = $data['Reliazed Margin'];
				$rowArray['RatePerKilometer']	 = $data['Rate Per Kilometer'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->ratePerKmByQuote($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('quotebasedrateperkm', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionFulfilmentProfit()
	{
		$row = Report::getRoleAccess(122);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Fulfilment VS Profit - In Percentage";

		$model	 = new Booking();
		$request = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1			 = $data['bkg_create_date1'] . " 00:00:00";
				$createDate2			 = $data['bkg_create_date2'] . " 23:59:59";
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
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"FulfilmentList_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "FulfilmentList_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingInvoice::getFulfilmentProfit($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['fromCity',
				'ToCity',
				'StateName',
				'Count Inquired',
				'Count Created',
				'Count Completed',
				'Pct Conversion',
				'Pct Fulfilment',
				'Total Amount',
				'Total GozoAmount',
				'Pct Profit',
				'First Booking CreateDate',
				'Last Booking CreateDate',
			]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'fromCityName'			 => $row['fromCityName'],
					'toCityName'			 => $row['toCityName'],
					'FromStateName'			 => $row['FromStateName'],
					'cntInquired'			 => $row['cntInquired'],
					'cntCreated'			 => $row['cntCreated'],
					'cntCompleted'			 => $row['cntCompleted'],
					'pct_conversion'		 => $row['pct_conversion'],
					'pct_fulfilment'		 => $row['pct_fulfilment'],
					'totalAmount'			 => $row['totalAmount'],
					'totalGozoAmount'		 => $row['totalGozoAmount'],
					'pct_profit'			 => $row['pct_profit'],
					'firstBookingCreateDate' => $row['firstBookingCreateDate'],
					'lastBookingCreateDate'	 => $row['lastBookingCreateDate'],
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

		$dataProvider	 = BookingInvoice::getFulfilmentProfit($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('fulfilment_profit_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionListTotalAmountByPickup()
	{
		$row = Report::getRoleAccess(126);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Booking By Total Amount (Pickup Date)";

		$model	 = new Booking();
		$request = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$picupDate1				 = $data['bkg_pickup_date1'] . " 00:00:00";
				$picupDate2				 = $data['bkg_pickup_date2'] . " 23:59:59";
				$model->bkg_pickup_date1 = $picupDate1;
				$model->bkg_pickup_date2 = $picupDate2;
			}
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}

		if ($_REQUEST['export'] == true)
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ListTotalAmountPickup_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "FulfilmentList_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingInvoice::getTotalAmountListByPickupDate($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [
				'pickupDate',
				'Total Booking',
				'BookingAmount<1000',
				'1000<=BookingAmount>2000',
				'2000<=BookingAmount>3000',
				'3000<=BookingAmount>4000',
				'4000<=BookingAmount>5000',
				'BookingAmount>=5000',
			]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'pickupDate'				 => $row['pickupDate'],
					'totalCnt'					 => $row['totalCnt'],
					'BookingAmount<1000'		 => $row['BookingAmount<1000'],
					'1000<=BookingAmount>2000'	 => $row['1000<=BookingAmount>2000'],
					'2000<=BookingAmount>3000'	 => $row['2000<=BookingAmount>3000'],
					'3000<=BookingAmount>4000'	 => $row['3000<=BookingAmount>4000'],
					'4000<=BookingAmount>5000'	 => $row['4000<=BookingAmount>5000'],
					'BookingAmount>=5000'		 => $row['BookingAmount>=5000'],
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

		$dataProvider	 = BookingInvoice::getTotalAmountListByPickupDate($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('booking_list_pickup', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionListTotalAmountByCreate()
	{
		$row = Report::getRoleAccess(127);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Booking By Total Amount( Create Date)";

		$model	 = new Booking();
		$request = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1			 = $data['bkg_create_date1'] . " 00:00:00";
				$createDate2			 = $data['bkg_create_date2'] . " 23:59:59";
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
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ListTotalAmountCreate_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "ListTotalAmountCreate_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingInvoice::getTotalAmountListByCreateDate($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['createDate', 'Total Booking',
				'BookingAmount<1000',
				'1000<=BookingAmount>2000',
				'2000<=BookingAmount>3000',
				'3000<=BookingAmount>4000',
				'4000<=BookingAmount>5000',
				'BookingAmount>=5000',
			]);

			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'createDate'				 => $row['createDate'],
					'totalCnt'					 => $row['totalCnt'],
					'BookingAmount<1000'		 => $row['BookingAmount<1000'],
					'1000<=BookingAmount>2000'	 => $row['1000<=BookingAmount>2000'],
					'2000<=BookingAmount>3000'	 => $row['2000<=BookingAmount>3000'],
					'3000<=BookingAmount>4000'	 => $row['3000<=BookingAmount>4000'],
					'4000<=BookingAmount>5000'	 => $row['4000<=BookingAmount>5000'],
					'BookingAmount>=5000'		 => $row['BookingAmount>=5000'],
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

		$dataProvider	 = BookingInvoice::getTotalAmountListByCreateDate($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('booking_list_create', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionRegionWiseBookingCount()
	{
		$row = Report::getRoleAccess(123);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Region Wise Booking Count";
		$request		 = Yii::app()->request;

		$model					 = new Booking();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$model->bkg_pickup_date1 = $date1;
		$model->bkg_pickup_date2 = $date2;
		$bkgTypes				 = null;
		$req					 = Yii::app()->request;
		if ($req->getParam('Booking'))
		{
			$arr					 = $req->getParam('Booking');
			$date1					 = $arr['bkg_pickup_date1'];
			$date2					 = $arr['bkg_pickup_date2'];
			$bkgRegion				 = $arr['bkg_region'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkg_pickup_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_pickup_date2 = $todate->format('Y-m-d') . " 23:59:59";
			$model->bkg_region		 = $bkgRegion;
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('-15 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$bkgregion	 = Yii::app()->request->getParam('export_bkgregion');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RegionWiseBookingCount" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "RegionWiseBookingCount" . date('YmdHi') . ".csv";
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
			$model->bkg_pickup_date1 = $date1;
			$model->bkg_pickup_date2 = $date2;
			$model->bkg_region		 = $bkgregion;

			$rows	 = Booking::model()->regionWiseBookingCount($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Count', 'Pickup Date', 'Region']);
			foreach ($rows as $data)
			{
				$rowArray				 = array();
				$rowArray['cnt']		 = $data['cnt'];
				$rowArray['PickupDate']	 = $data['PickupDate'];
				$rowArray['Region']		 = $data['Region'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->regionWiseBookingCount($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('regionwisebookingcount', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionZoneZoneBookingCount()
	{
		$row = Report::getRoleAccess(124);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Zone-Zone booking Count";
		$request		 = Yii::app()->request;

		$model					 = new Booking();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$model->bkg_pickup_date1 = $date1;
		$model->bkg_pickup_date2 = $date2;
		$bkgTypes				 = null;
		$req					 = Yii::app()->request;
		if ($req->getParam('Booking'))
		{
			$arr					 = $req->getParam('Booking');
			$date1					 = $arr['bkg_pickup_date1'];
			$date2					 = $arr['bkg_pickup_date2'];
			$bkgRegion				 = $arr['bkg_region'];
			$bkgtypes				 = $arr['bkgtypes'];
			$agentId				 = $arr['bkg_agent_id'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkg_pickup_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_pickup_date2 = $todate->format('Y-m-d') . " 23:59:59";
			$model->bkg_region		 = $bkgRegion;
			$model->bkgtypes		 = $bkgtypes;
			$model->bkg_agent_id	 = $agentId;
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('-15 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$bkgregion	 = Yii::app()->request->getParam('export_bkgregion');
			$bkgType	 = Yii::app()->request->getParam('export_bkgtype');
			$agentId	 = Yii::app()->request->getParam('export_agentid');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ZoneZoneBookingCount" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ZoneZoneBookingCount" . date('YmdHi') . ".csv";
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
			$model->bkg_pickup_date1 = $date1;
			$model->bkg_pickup_date2 = $date2;
			$model->bkg_region		 = $bkgregion;
			$model->bkgtypes		 = ($bkgType != '') ? explode(',', $bkgType) : null;
			$model->bkg_agent_id	 = $agentId;

			$rows	 = Booking::model()->zoneZoneBookingCount($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Count', 'Pickup Date', 'Zone Name', 'Trip Type']);
			foreach ($rows as $data)
			{
				$rowArray				 = array();
				$rowArray['cnt']		 = $data['cnt'];
				$rowArray['PickupDate']	 = $data['PickupDate'];
				$rowArray['zon_name']	 = $data['zon_name'];
				$rowArray['trip_type']	 = $data['trip_type'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->zoneZoneBookingCount($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('zonezonebookingcount', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionZoneWiseBookingCount()
	{
		$row = Report::getRoleAccess(125);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Zone Wise Booking Count";
		$request		 = Yii::app()->request;

		$model					 = new Booking();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$model->bkg_pickup_date1 = $date1;
		$model->bkg_pickup_date2 = $date2;
		$bkgTypes				 = null;
		$req					 = Yii::app()->request;
		if ($req->getParam('Booking'))
		{
			$arr					 = $req->getParam('Booking');
			$date1					 = $arr['bkg_pickup_date1'];
			$date2					 = $arr['bkg_pickup_date2'];
			$bkgRegion				 = $arr['bkg_region'];
			$bkgtypes				 = $arr['bkgtypes'];
			$bkgState				 = $arr['bkg_state'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkg_pickup_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_pickup_date2 = $todate->format('Y-m-d') . " 23:59:59";
			$model->bkg_region		 = $bkgRegion;
			$model->bkgtypes		 = $bkgtypes;
			$model->bkg_state		 = $bkgState;
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('-15 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$bkgregion	 = Yii::app()->request->getParam('export_bkgregion');
			$bkgType	 = Yii::app()->request->getParam('export_bkgtype');
			$bkgState	 = Yii::app()->request->getParam('export_bkgstate');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ZoneWiseBookingCount" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ZoneWiseBookingCount" . date('YmdHi') . ".csv";
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
			$model->bkg_pickup_date1 = $date1;
			$model->bkg_pickup_date2 = $date2;
			$model->bkg_region		 = $bkgregion;
			$model->bkgtypes		 = ($bkgType != '') ? explode(',', $bkgType) : null;
			$model->bkg_state		 = $bkgState;

			$rows	 = Booking::model()->zoneWiseBookingCount($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Count', 'Pickup Date', 'Zone Name']);
			foreach ($rows as $data)
			{
				$rowArray				 = array();
				$rowArray['cnt']		 = $data['cnt'];
				$rowArray['PickupDate']	 = $data['PickupDate'];
				$rowArray['zon_name']	 = $data['zon_name'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->zoneWiseBookingCount($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('zonewisebookingcount', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionSalesAssistedPercentByTier()
	{
		$row = Report::getRoleAccess(129);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Sales Assisted Percent By Tier";
		$model			 = new Booking();
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"SalesAssistedPercentByTier" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "SalesAssistedPercentByTier" . date('YmdHi') . ".csv";
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

			$rows	 = Booking::model()->salesAssistedPercentByTier(DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Create Date', 'Service Class', 'Count']);
			foreach ($rows as $data)
			{
				$rowArray							 = array();
				$rowArray['bkg_create_date']		 = $data['bkg_create_date'];
				$rowArray['ServiceClassSccLabel']	 = $data['Service Class__scc_label'];
				$rowArray['count']					 = $data['count'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = Booking::model()->salesAssistedPercentByTier();
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('salesassistedpercent', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionSalesAssistedByTier()
	{
		$row = Report::getRoleAccess(133);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Sales-Assisted (by Tier)";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$pickupDate1			 = $data['bkg_pickup_date1'] . " 00:00:00";
				$pickupDate2			 = $data['bkg_pickup_date2'] . " 23:59:59";
				$model->bkg_pickup_date1 = $pickupDate1;
				$model->bkg_pickup_date2 = $pickupDate2;
			}
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 7 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}


		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"SalesAssistedByTier" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "SalesAssistedByTier" . date('YmdHi') . ".csv";
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

			$rows	 = BookingInvoice::getSalesAssistedByTier($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Create Date', 'Scc Label', 'Count', 'Sum']);
			foreach ($rows as $data)
			{
				$rowArray					 = array();
				$rowArray['bkg_create_date'] = $data['bkg_create_date'];
				$rowArray['scc_label']		 = $data['scc_label'];
				$rowArray['count']			 = $data['count'];
				$rowArray['total_sum']		 = $data['total_sum'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider	 = BookingInvoice::getSalesAssistedByTier($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('list_sales_assisted_bytier', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionreferralTrack()
	{
		$row = Report::getRoleAccess(128);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Booking Referal Track";
		$request		 = Yii::app()->request;
		$model			 = new BookingReferralTrack();
		if ($request->getParam('BookingReferralTrack'))
		{
			$arr					 = $request->getParam('BookingReferralTrack');
			$date1					 = $arr['bkg_create_date1'];
			$date2					 = $arr['bkg_create_date2'];
			$fromdate				 = new DateTime($date1);
			$todate					 = new DateTime($date2);
			$model->bkg_create_date1 = $fromdate->format('Y-m-d') . " 00:00:00";
			$model->bkg_create_date2 = $todate->format('Y-m-d') . " 23:59:59";
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('-1 month')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d') . " 23:59:59";
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true" && isset($_REQUEST['export_bkg_create_date1']) && isset($_REQUEST['export_bkg_create_date2']))
		{
			$date1		 = Yii::app()->request->getParam('export_bkg_create_date1') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_bkg_create_date2') . ' 23:59:59';
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"BookingRefrealTrack_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "BookingRefrealTrack_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingReferralTrack::fetchList($date1, $date2, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Beneficiary Id', 'Benefactor Id,', 'isFirst Beneficiary', 'Last Benefactor BkgId', 'Beneficiary BkgId', 'Complete Date', 'Beneficiary Payout Amt', 'Beneficiary Payout Date', 'Beneficiary Payout Status', 'Benefactor Payout Amt', 'Benefactor Payout Date', 'Benefactor Payout Status', 'Beneficiary Benefit Received', 'Benefactor Benefit Received', 'Create Date']);
			foreach ($rows as $data)
			{
				$rowArray										 = array();
				$rowArray['brk_beneficiary_id']					 = $data['brk_beneficiary_id'];
				$rowArray['brk_benefactor_id']					 = $data['brk_benefactor_id'];
				$rowArray['brk_isfirst_beneficiary']			 = $data['brk_isfirst_beneficiary'];
				$rowArray['brk_last_benefactor_bkgId']			 = $data['brk_last_benefactor_bkgId'];
				$rowArray['brk_beneficiary_bkgId']				 = $data['brk_beneficiary_bkgId'];
				$rowArray['brk_beneficiary_bkg_complete_date']	 = $data['brk_beneficiary_bkg_complete_date'];
				$rowArray['brk_beneficiary_payout_amt']			 = $data['brk_beneficiary_payout_amt'];
				$rowArray['brk_beneficiary_payout_date']		 = $data['brk_beneficiary_payout_date'];
				$rowArray['brk_beneficiary_payout_status']		 = $data['brk_beneficiary_payout_status'];
				$rowArray['brk_benefactor_payout_amt']			 = $data['brk_benefactor_payout_amt'];
				$rowArray['brk_benefactor_payout_date']			 = $data['brk_benefactor_payout_date'];
				$rowArray['brk_benefactor_payout_status']		 = $data['brk_benefactor_payout_status'];
				$rowArray['brk_beneficiarybenefit_received']	 = $data['brk_beneficiarybenefit_received'];
				$rowArray['brk_benefactorbenefit_received']		 = $data['brk_benefactorbenefit_received'];
				$rowArray['brk_create_date']					 = $data['brk_create_date'];
				$row1											 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = BookingReferralTrack::fetchList($model->bkg_create_date1, $model->bkg_create_date2);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('referaltrack', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row), false, $outputJs);
	}

	public function actionBookingCountByStates()
	{

		$row = Report::getRoleAccess(131);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Booking By Total Amount( Create Date)";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$createDate1			 = $data['bkg_pickup_date1'] . " 00:00:00";
				$createDate2			 = $data['bkg_pickup_date2'] . " 23:59:59";
				$model->bkg_pickup_date1 = $createDate1;
				$model->bkg_pickup_date2 = $createDate2;
			}
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 30 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}



		if ($_REQUEST['export'] == true)
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ListBookingStates_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "ListBookingStates_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::getBookingCountByStates($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Count',
				'PickupDate',
				'StateName',
				'cty_state_id',
			]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'cnt'			 => $row['cnt'],
					'PickupDate'	 => $row['PickupDate'],
					'StateName'		 => $row['StateName'],
					'cty_state_id'	 => $row['cty_state_id'],
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

		$dataProvider	 = BookingSub::getBookingCountByStates($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('booking_list_states', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionSalesAssistedBookings()
	{
		$row = Report::getRoleAccess(133);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Sales-Assisted Bookings";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$createDate1			 = $data['bkg_create_date1'] . " 00:00:00";
				$createDate2			 = $data['bkg_create_date2'] . " 23:59:59";
				$model->bkg_create_date1 = $createDate1;
				$model->bkg_create_date2 = $createDate2;
			}
		}
		else
		{
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 90 days')) . " 00:00:00";
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}



		if ($_REQUEST['export'] == true)
		{
			$model->bkg_create_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_create_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"SalesAssistedBookings_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "SalesAssistedBookings_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::getSalesAssistedBooking($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Create Date',
				'Count',
			]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'bkg_create_date'	 => $row['bkg_create_date'],
					'count'				 => $row['count'],
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

		$dataProvider	 = BookingSub::getSalesAssistedBooking($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('list_sales_assisted_bookings', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionMonthlyVolumeByServiceTier()
	{
		$row = Report::getRoleAccess(134);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Monthly volume by Service tier";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$data = Yii::app()->request->getParam('Booking');
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$createDate1			 = $data['bkg_pickup_date1'];
				$createDate2			 = $data['bkg_pickup_date2'];
				$model->bkg_pickup_date1 = $createDate1;
				$model->bkg_pickup_date2 = $createDate2;
			}
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 120 days'));
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today'));
		}


		if ($_REQUEST['export'] == true)
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('from_date');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"MonthlyVolumeByServiceTier_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "MonthlyVolumeByServiceTier_" . date('YmdHi') . ".csv";
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
			$rows	 = BookingSub::getMonthlyVolumeByServiceTier($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Pickup Date',
				'Count',
				'Scc Label'
			]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'bkg_pickup_date'	 => $row['bkg_pickup_date'],
					'count'				 => $row['count'],
					'scc_label'			 => $row['scc_label'],
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

		$dataProvider	 = BookingSub::getMonthlyVolumeByServiceTier($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('list_monthly_volume_by_servicetier', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionAssignmentSummary()
	{
		$row = Report::getRoleAccess(49);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Assignment Report";
		$model			 = new BookingSub();
		$from_date		 = $to_date		 = '';
		$fromCreateDate	 = $toCreateDate	 = '';
		$request		 = Yii::app()->request;
		$orderby		 = 'date';
		$partnerId		 = 0;
		if ($request->getParam('BookingSub'))
		{
			$arr						 = $request->getParam('BookingSub');
			$from_date					 = $arr['bkg_pickup_date1'];
			$to_date					 = $arr['bkg_pickup_date2'];
			$fromCreateDate				 = $arr['bkg_create_date1'];
			$toCreateDate				 = $arr['bkg_create_date2'];
			$orderby					 = $arr['groupvar'];
			$partnerId					 = $arr['bkg_agent_id'];
			$bkgType					 = $arr['bkgtypes'];
			$gnowType					 = $arr['gnowType'];
			$nonProfitable				 = $arr['nonProfitable'];
			$excludeAT					 = isset($arr['excludeAT'][0]);
			$includeB2c					 = isset($arr['b2cbookings'][0]);
			$mmtbookings				 = $arr['mmtbookings'];
			$nonAPIPartner				 = $arr['nonAPIPartner'];
			$weekDays					 = $arr['weekDays'];
			$zones						 = $arr['sourcezone'];
			$region						 = $arr['region'];
			$state						 = $arr['state'];
			$assignedFrom				 = $arr['from_date'];
			$assignedTo					 = $arr['to_date'];
			$local						 = $arr['local'];
			$outstation					 = $arr['outstation'];
			$bkg_vehicle_type_id		 = $arr['bkg_vehicle_type_id'];
			$assignMode					 = $arr['assignMode'];
			$manualAssignment			 = $arr['manualAssignment'];
			$criticalAssignment			 = $arr['criticalAssignment'];
			$includeTFR					 = isset($arr['includeTFR']) ? $arr['includeTFR'] : 0;
			$model->bkg_agent_id		 = $partnerId;
			$model->excludeAT			 = $excludeAT;
			$model->b2cbookings			 = $includeB2c;
			$model->mmtbookings			 = $mmtbookings;
			$model->nonAPIPartner		 = $nonAPIPartner;
			$model->nonProfitable		 = $nonProfitable;
			$model->local				 = $arr['local'];
			$model->outstation			 = $arr['outstation'];
			$model->assignMode			 = $assignMode;
			$model->manualAssignment	 = $manualAssignment;
			$model->criticalAssignment	 = $criticalAssignment;
			$model->includeTFR			 = $includeTFR;
		}
		else
		{
			$from_date	 = date("Y-m-d", strtotime("-28 day", time()));
			$to_date	 = date('Y-m-d', strtotime("+2 day", time()));
			$bkgType	 = [];
		}
		$model->bkgtypes			 = $bkgType;
		$model->bkg_pickup_date1	 = $from_date;
		$model->bkg_pickup_date2	 = $to_date;
		$model->bkg_create_date1	 = $fromCreateDate;
		$model->bkg_create_date2	 = $toCreateDate;
		$model->gnowType			 = $gnowType;
		$model->weekDays			 = $weekDays;
		$model->sourcezone			 = $zones;
		$model->region				 = $region;
		$model->state				 = $state;
		$model->from_date			 = $assignedFrom;
		$model->to_date				 = $assignedTo;
		$model->bkg_vehicle_type_id	 = $bkg_vehicle_type_id;

		$diff3Month	 = strtotime("-6 month", strtotime($to_date)) - strtotime($from_date);
		$error		 = '';
		if ($diff3Month > 0)
		{
			$error = "Date range should be less than 6 months";
			goto skipAll;
		}
		$params = [
			'from_date'				 => $from_date,
			'to_date'				 => $to_date,
			'fromCreateDate'		 => $fromCreateDate,
			'toCreateDate'			 => $toCreateDate,
			'bkgTypes'				 => $bkgType,
			'nonProfitable'			 => $nonProfitable,
			'gnowType'				 => $gnowType,
			'weekDays'				 => $weekDays,
			'zones'					 => $zones,
			'region'				 => $region,
			'state'					 => $state,
			'assignedFrom'			 => $assignedFrom,
			'assignedTo'			 => $assignedTo,
			'local'					 => $local,
			'outstation'			 => $outstation,
			'bkg_vehicle_type_id'	 => $bkg_vehicle_type_id,
			'assignMode'			 => $assignMode,
			'manualAssignment'		 => $manualAssignment,
			'criticalAssignment'	 => $criticalAssignment,
			'includeTFR'			 => $includeTFR
		];

		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			$bkgType			 = [];
			$from_date			 = Yii::app()->request->getParam('bkg_pickup_date1');
			$to_date			 = Yii::app()->request->getParam('bkg_pickup_date2');
			$fromCreateDate		 = Yii::app()->request->getParam('bkg_create_date1');
			$toCreateDate		 = Yii::app()->request->getParam('bkg_create_date2');
			$assignedFrom		 = Yii::app()->request->getParam('from_date');
			$assignedTo			 = Yii::app()->request->getParam('to_date');
			$type				 = Yii::app()->request->getParam('bkgtypes');
			$bkgType			 = ($type != '') ? explode(',', $type) : null;
			$partnerId			 = Yii::app()->request->getParam('bkg_agent_id');
			$weekDay			 = Yii::app()->request->getParam('weekDays');
			$weekDays			 = ($weekDay != '') ? explode(',', $weekDay) : null;
			$sourceZone			 = Yii::app()->request->getParam('sourcezone');
			$zones				 = ($sourceZone != '') ? explode(',', $sourceZone) : null;
			$sourceRegion		 = Yii::app()->request->getParam('region');
			$region				 = ($sourceRegion != '') ? explode(',', $sourceRegion) : null;
			$sourceState		 = Yii::app()->request->getParam('state');
			$state				 = ($sourceState != '') ? explode(',', $sourceState) : null;
			$includeB2c			 = Yii::app()->request->getParam('b2cbookings');
			$mmtbookings		 = Yii::app()->request->getParam('mmtbookings');
			$excludeAT			 = Yii::app()->request->getParam('excludeAT');
			$gnow				 = Yii::app()->request->getParam('gnowType');
			$gnowType			 = ($gnow != '') ? explode(',', $gnow) : null;
			$nonProfitable		 = Yii::app()->request->getParam('nonProfitable');
			$local				 = Yii::app()->request->getParam('local');
			$outstation			 = Yii::app()->request->getParam('outstation');
			$assignMode			 = Yii::app()->request->getParam('assignMode');
			$manualAssignment	 = Yii::app()->request->getParam('manualAssignment');
			$criticalAssignment	 = Yii::app()->request->getParam('criticalAssignment');
			$includeTFR			 = Yii::app()->request->getParam('includeTFR', 0);

			$orderby = 'date';

			$params = [
				'from_date'			 => $from_date,
				'to_date'			 => $to_date,
				'fromCreateDate'	 => $fromCreateDate,
				'toCreateDate'		 => $toCreateDate,
				'bkgTypes'			 => $bkgType,
				'nonProfitable'		 => $nonProfitable,
				'gnowType'			 => $gnowType,
				'weekDays'			 => $weekDays,
				'zones'				 => $zones,
				'region'			 => $region,
				'state'				 => $state,
				'assignedFrom'		 => $assignedFrom,
				'assignedTo'		 => $assignedTo,
				'local'				 => $local,
				'outstation'		 => $outstation,
				'assignMode'		 => $assignMode,
				'manualAssignment'	 => $manualAssignment,
				'criticalAssignment' => $criticalAssignment,
				'includeTFR'		 => 0
			];

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AssignmentSummary_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "AssignmentSummary_" . date('Ymdhis') . ".csv";
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
			$rows	 = BookingCab::getAssignmentData($params, $orderby, $partnerId, $includeB2c, $excludeAT, $nonAPIPartner, $mmtbookings, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Date', 'Gozo Cancelled', 'Total Active', 'Gozo Amount', 'Total Margin', 'Manual Gozo Amount',
				'Auto Gozo Amount', 'Manual Margin', 'Auto Margin', 'Manual Assign Percent', 'Auto Assign Percent',
				'Net BaseAmount', 'Bid Assign Percent', 'Bid Assign Margin', 'Bid Gozo Amount', 'Direct Assign Percent',
				'Direct Assign Percent', 'Direct Assign Margin', 'Direct Gozo Amount']);
			foreach ($rows as $row)
			{
				$rowArray			 = array();
				$rowArray['date']	 = $row['date'];

				$totGozoCan	 = $row['totalGozoCancelled'];
				$mmtGozoCan	 = '';
				if ($row['MMTGozoCancelled'] > 0)
				{
					$others		 = $row['totalGozoCancelled'] - $row['MMTGozoCancelled'];
					$mmtGozoCan	 = " MMT: {$row['MMTGozoCancelled']}, Others: {$others}";
				}
				$rowArray['totalGozoCancelled'] = $totGozoCan . ' ' . $mmtGozoCan;

				$totalBooking	 = $row['totalBooking'];
				$cntManual		 = '';
				$totUnassigned	 = '';
				$totLossBooking	 = '';
				if ($row['cntManual'] > 0 || $row['cntCritical'] > 0)
				{
					$cntManual = "M: {$row['cntManual']}, C: {$row['cntCritical']}";
				}
				if ($row['totalUnassigned'] > 0)
				{
					$totUnassigned = " New: {$row['totalUnassigned']} $cntManual, Assigned: {$row['totalAssigned']}";
				}
				if ($row['totalLossBooking'] > 0)
				{
					$totLossBooking = " Loss: {$row['totalLossBooking']}";
				}
				$rowArray['totalBooking'] = $totalBooking . ' ' . $totUnassigned . ' ' . $totLossBooking;

				$gozoAmount			 = $row['gozoAmount'];
				$totUnassignedAmt	 = '';
				$gozoLossAmt		 = '';
				if ($row['totalUnassigned'] > 0)
				{
					$newGozoAmount		 = $row['gozoAmount'] - $row['AssignedGozoAmount'];
					$totUnassignedAmt	 = " New: {$newGozoAmount}, Assigned: " . $row['AssignedGozoAmount'];
				}
				if ($row['gozoLossAmount'] != 0)
				{
					$gozoLossAmt = " Loss: " . $row['gozoLossAmount'];
				}
				$rowArray['gozoAmount'] = $gozoAmount . ' ' . $totUnassignedAmt . ' ' . $gozoLossAmt;

				$unassignedMargin	 = '';
				$totMargin			 = $row['TotalMargin'];
				if ($row['totalUnassigned'] > 0)
				{
					$unassignedMargin = " New: {$row['UnassignedMargin']}, Assigned: {$row['AssignedMargin']}";
				}
				$rowArray['TotalMargin'] = $totMargin . ' ' . $unassignedMargin;

				$manualLossAmt	 = '';
				$manualGozoAmt	 = $row['ManualGozoAmount'];
				if ($row['ManualLossGozoAmount'] != 0)
				{
					$manualLossAmt = 'L: ' . $row['ManualLossGozoAmount'];
				}
				$rowArray['ManualGozoAmount'] = $manualGozoAmt . ' ' . $manualLossAmt;

				$autoLossAmt = '';
				$autoGozoAmt = $row['AutoGozoAmount'];
				if ($row['AutoLossGozoAmount'] != 0)
				{
					$autoLossAmt = 'L:' . $row['AutoLossGozoAmount'];
				}
				$rowArray['AutoGozoAmount'] = $autoGozoAmt . '' . $autoLossAmt;

				$manualLossMargin	 = '';
				$manualMargin		 = $row['ManualMargin'];
				if ($row['ManualLossMargin'] != '' || $row['ManualLossMargin'] != NULL)
				{
					$manualLossMargin = 'L: ' . $row['ManualLossMargin'];
				}
				$rowArray['ManualMargin'] = $manualMargin . ' ' . $manualLossMargin;

				$autoLossMargin	 = '';
				$autoMargin		 = $row['AutoMargin'];
				if ($row['AutoLossMargin'] != '' || $row['AutoLossMargin'] != NULL)
				{
					$autoLossMargin = 'L: ' . $row['AutoLossMargin'];
				}
				$rowArray['AutoMargin'] = $autoMargin . ' ' . $autoLossMargin;

				$manualAssignPercent = $row['ManualAssignPercent'] . " (" . $row['countManualMargin'] . ")";
				if ($row['ManualLossBookingCount'] != 0)
				{
					$manualLossBookingCount = 'Loss: ' . $row['ManualLossBookingCount'];
				}
				$rowArray['ManualAssignPercent'] = $manualAssignPercent . ' ' . $manualLossBookingCount;

				$rowArray['AutoAssignPercent'] = $row['AutoAssignPercent'] . " (" . $row['countAutoMargin'] . ")";

				$unassigndNetBaseAmt = '';
				$netBaseAmount		 = $row['netBaseAmount'];
				if ($row['totalUnassigned'] > 0)
				{
					$assignedBaseAmount	 = $row['netBaseAmount'] - $row['UnassignedNetBaseAmount'];
					$unassigndNetBaseAmt = "New: " . $row['UnassignedNetBaseAmount'] . ", Assigned: {$assignedBaseAmount}";
				}
				$rowArray['netBaseAmount']		 = $netBaseAmount . ' ' . $unassigndNetBaseAmt;
				$rowArray['BidAssignPercent']	 = $row['BidAssignPercent'] . " (" . $row['countBidMargin'] . ")";
				$rowArray['BidAssignMargin']	 = $row['BidAssignMargin'];
				$rowArray['BidGozoAmount']		 = $row['BidGozoAmount'];
				$rowArray['DirAssignPercent']	 = $row['DirectAssignPercent'];
				$rowArray['DirectAssignPercent'] = $row['DirectAssignPercent'] . " (" . $row['countDirectMargin'] . ")";

				$directAssignLossMargin	 = '';
				$directAssignMargin		 = $row['DirectAssignMargin'];
				if ($data['DirectAssignLossMargin'] != '' || $row['DirectAssignLossMargin'] != NULL)
				{
					$directAssignLossMargin = 'L: ' . $row['DirectAssignLossMargin'];
				}
				$rowArray['DirectAssignMargin'] = $directAssignMargin . ' ' . $directAssignLossMargin;

				$directLossGozoAmt	 = '';
				$directGozoAmt		 = $row['DirectGozoAmount'];
				if ($row['DirectLossGozoAmount'] != 0)
				{
					$directLossGozoAmt = 'L:' . $row['DirectLossGozoAmount'];
				}
				$rowArray['DirectGozoAmount'] = $directGozoAmt . ' ' . $directLossGozoAmt;

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

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
			'error'			 => $error, 'roles'			 => $row)
		);
	}

	public function actionManualReport()
	{
		$row = Report::getRoleAccess(140);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Manual Report";
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

				$model->is_Assigned	 = $data['is_Assigned'];
				$model->is_Manual	 = $data['is_Manual'];
				$model->is_Critical	 = $data['is_Critical'];
				$model->bkg_admin_id = $data['bkg_admin_id'];
			}

			if ($data['bkg_assigned_date1'] != '' && $data['bkg_assigned_date2'] != '')
			{
				$assignedDate1Data	 = explode(' ', $data['bkg_assigned_date1']);
				$assignedDate2Data	 = explode(' ', $data['bkg_assigned_date2']);
				$assignedDate1		 = ($assignedDate1Data[1] == '00:00:00') ? $data['bkg_assigned_date1'] : $data['bkg_assigned_date1'] . ' 00:00:00';
				$assignedDate2		 = ($assignedDate2Data[1] == '23:59:59') ? $data['bkg_assigned_date2'] : $data['bkg_assigned_date2'] . ' 23:59:59';

				$model->bkg_assigned_date1	 = $assignedDate1;
				$model->bkg_assigned_date2	 = $assignedDate2;
			}

			$model->search_tags = $data['search_tags'];
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 14 days')) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today')) . " 23:59:59";

			$model->bkg_assigned_date1	 = null;
			$model->bkg_assigned_date2	 = null;
		}

		$params = [
			'bkg_pickup_date1'	 => $model->bkg_pickup_date1,
			'bkg_pickup_date2'	 => $model->bkg_pickup_date2,
			'bkg_assigned_date1' => $model->bkg_assigned_date1,
			'bkg_assigned_date1' => $model->bkg_assigned_date1,
			'is_Assigned'		 => $data['is_Assigned'],
			'is_Manual'			 => $data['is_Manual'],
			'is_Critical'		 => $data['is_Critical'],
			'bkg_admin_id'		 => $data['bkg_admin_id'],
			'search_tags'        => $model->search_tags
		];

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{

			$bkg_pickup_date1	 = Yii::app()->request->getParam('pickup_date1');
			$bkg_pickup_date2	 = Yii::app()->request->getParam('pickup_date2');
			$bkg_assigned_date1	 = Yii::app()->request->getParam('bkg_assigned_date1');
			$bkg_assigned_date2	 = Yii::app()->request->getParam('bkg_assigned_date2');

			$is_Assigned = Yii::app()->request->getParam('is_Assigned');
			$is_Manual	 = Yii::app()->request->getParam('is_Manual');
			$is_Critical = Yii::app()->request->getParam('is_Critical');
			$adminId	 = Yii::app()->request->getParam('admin_id');
			$searchTags	 = Yii::app()->request->getParam('searchTags');
			$params = [
				'bkg_pickup_date1'	 => $bkg_pickup_date1,
				'bkg_pickup_date2'	 => $bkg_pickup_date2,
				'bkg_assigned_date1' => $bkg_assigned_date1,
				'bkg_assigned_date1' => $bkg_assigned_date2,
				'is_Assigned'		 => $is_Assigned,
				'is_Manual'			 => $is_Manual,
				'is_Critical'		 => $is_Critical,
				'bkg_admin_id'		 => $adminId,
				'search_tags'		 => ($searchTags!='')?explode(',',$searchTags):""
			];

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ManualReport" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ManualReport" . date('YmdHi') . ".csv";
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

			$rows	 = BookingCab::manualReport($params, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Trip Id', 'Booking Id ', 'Create Date', 'Pickup Date', 'Vendor Name', 'Driver Name', 'Vehicle Name', 'Assign At', 'Assign By', 'Profit', 'Bid Count']);
			foreach ($rows as $data)
			{
				$rowArray					 = array();
				$rowArray['bcb_id']			 = $data['bcb_id'];
				$rowArray['bkg_id']			 = $data['bkg_id'];
				$rowArray['bkg_create_date'] = $data['bkg_create_date'];
				$rowArray['bkg_pickup_date'] = $data['bkg_pickup_date'];
				$rowArray['vnd_name']		 = $data['vnd_name'];
				$rowArray['drv_name']		 = $data['drv_name'];
				$rowArray['vhc_number']		 = $data['vhc_number'];
				$rowArray['bkg_assigned_at'] = $data['bkg_assigned_at'];
				$rowArray['assign_csr']		 = $data['assign_csr'];
				$rowArray['bkg_gozo_amount'] = $data['bkg_gozo_amount'];
				$rowArray['bid_count']		 = $data['bid_count'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider	 = BookingCab::manualReport($params);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");

		$this->$method('list_manual_report', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionShowbidlog()
	{
		$bkgId									 = Yii::app()->request->getParam('bkgId');
		$dataProvider							 = Vendors::getNameBidAmountById($bkgId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderPartial('bid_log', ['dataProvider' => $dataProvider], false, true);
	}

	public function actionTrfzOffers()
	{
//		$row = Report::getRoleAccess(134);
//		if ($row['rpt_roles'] != null)
//		{
//			$roleAccess = Filter::checkACL($row['rpt_roles']);
//			if (!$roleAccess)
//			{
//				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
//			}
//		}

		$this->pageTitle = "Transferz Failed Booking";
		$model			 = new TransferzOffers();
		$request		 = Yii::app()->request;
		if ($request->getParam('TransferzOffers'))
		{
			$data = Yii::app()->request->getParam('TransferzOffers');

			$model->createDate1			 = $date1						 = $data['createDate1'];
			$model->createDate2			 = $date2						 = $data['createDate2'];
			$model->trb_trz_journey_code = $data['trb_trz_journey_code'];
			$model->trb_trz_journey_id	 = $data['trb_trz_journey_id'];
			$model->trb_status			 = $data['trb_status'];
		}
		else
		{
			$model->createDate1	 = date('Y-m-d', strtotime('today - 3 days')) . " 00:00:00";
			$model->createDate2	 = date('Y-m-d', strtotime('today')) . " 23:59:59";
		}

		$dataProvider	 = TransferzOffers::getFailedBooking($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('trfbkgfailed', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

	public function actionTfrRejected()
	{

		$this->pageTitle = "TFR Cancelled/Rejected Bookings";
		$model			 = new Booking();
		/** @var Booking $model */
		$request		 = Yii::app()->request;
		$showExport		 = false;
		if ($request->getParam('Booking'))
		{
			$data = $request->getParam('Booking');

			$model->bkg_pickup_date1 = $data['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $data['bkg_pickup_date2'];
			$model->bkg_booking_id	 = $data['bkg_booking_id'];
			$showExport				 = true;
		}
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{

			$dataReader	 = $model->getTFRCancelled('export');
			$filename	 = "TFRRejectionReport" . date('YmdHis') . ".csv";
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Pragma: no-cache");
			header("Expires: 0");

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

			$rowHead = [
				'BookingId',
				'PartnerRefCode',
				'CreateDate',
				'PickupDate',
				'CancelledDate',
				'DurationInMinutes',
				'CancelReason',
				'CancelledBy'
			];
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, $rowHead);
			foreach ($dataReader as $data)
			{
				$rowArray						 = array();
				$rowArray['BookingId']			 = $data['bkg_booking_id'];
				$rowArray['PartnerRefCode']		 = $data['bkg_agent_ref_code'];
				$rowArray['CreateDate']			 = DateTimeFormat::DateTimeToLocale($data['bkg_create_date']);
				$rowArray['PickupDate']			 = DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
				$rowArray['CancelledDate']		 = DateTimeFormat::DateTimeToLocale($data['btr_cancel_date']);
				$rowArray['DurationInMinutes']	 = $data['cancelDateDiff'];
				$rowArray['CancelReason']		 = $data['bkg_cancel_delete_reason'];

				$canUser = '';
				switch ($data['bkg_cancel_user_type'])
				{
					case '4':
						$canUser = $data['admName'];
						break;
					case '5':
						$canUser = 'MMT';
						break;
					case '10':
						$canUser = 'System';
						break;
					default:
						break;
				}
				$rowArray['CancelledBy'] = $canUser;
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$appliedSort = $request->getParam('sort');

		$dataProvider	 = $model->getTFRCancelled();
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('tfrRejected', array(
			'dataProvider'	 => $dataProvider,
			'showExport'	 => $showExport,
			'model'			 => $model), false, $outputJs);
	}

	public function actionCollectionMismatch()
	{
		$this->pageTitle		 = "Accounts Collection Mismatch";
		$request				 = Yii::app()->request;
		$model					 = new Booking();
		$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 1 days'));
		$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today'));
		if ($request->getParam('Booking'))
		{
			$data = $request->getParam('Booking');

			$model->bkg_pickup_date1	 = $data['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $data['bkg_pickup_date2'];
			$model->bkgtypes			 = $data['bkgtypes'];
			$model->diffCollectionType	 = $data['diffCollectionType'];
		}
		$dataProvider	 = BookingInvoice::getAccountCollectionMismatchData($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('collectionMismatch', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model), false, $outputJs);
	}

	public function actionNoneZeroExtraCharges()
	{
		$this->pageTitle		 = "None Zero Extra Charges";
		$request				 = Yii::app()->request;
		$model					 = new Booking();
		$model->bkg_pickup_date1 = date('Y-m-d', strtotime('today - 1 days'));
		$model->bkg_pickup_date2 = date('Y-m-d', strtotime('today'));
		if ($request->getParam('Booking'))
		{
			$data = $request->getParam('Booking');

			$model->bkg_pickup_date1 = $data['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $data['bkg_pickup_date2'];
			$model->bkgtypes		 = $data['bkgtypes'];
		}
		$dataProvider	 = BookingInvoice::getListWithExtraCharges($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('noneZeroExtraCharges', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model), false, $outputJs);
	}

	public function actionMmtCancelReport()
	{
		$this->pageTitle = "MMT Cancel Booking Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;

		$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days'));
		$model->bkg_create_date2 = date('Y-m-d', strtotime('today'));
		$model->bkg_pickup_date1 = '';
		$model->bkg_pickup_date2 = '';
		if ($request->getParam('Booking') != "")
		{
			$data					 = Yii::app()->request->getParam('Booking');
			$createDate1			 = $data['bkg_create_date1'];
			$createDate2			 = $data['bkg_create_date2'];
			$model->bkg_create_date1 = date('Y-m-d', strtotime('today - 7 days'));
			$model->bkg_create_date2 = date('Y-m-d', strtotime('today'));
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$model->bkg_create_date1 = $createDate1;
				$model->bkg_create_date2 = $createDate2;
			}
			else
			{
				$model->bkg_create_date1 = '';
				$model->bkg_create_date2 = '';
			}
			$pickupDate1			 = $data['bkg_pickup_date1'];
			$pickupDate2			 = $data['bkg_pickup_date2'];
			$model->bkg_pickup_date1 = '';
			$model->bkg_pickup_date2 = '';
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$model->bkg_pickup_date1 = $pickupDate1;
				$model->bkg_pickup_date2 = $pickupDate2;
			}
			else
			{
				$model->bkg_pickup_date1 = '';
				$model->bkg_pickup_date2 = '';
			}
		}
		$dataProvider = BookingSub::mmtcancelbookingList($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list_cancel_mmt', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionPartnerBookings()
	{
		$this->pageTitle = "Partner Bookings";

		$model					 = new Booking();
		$model->bkg_agent_id	 = "453162";
		$model->bkg_create_date1 = date('Y-m-d 00:00:00', strtotime('today - 90 days'));
		$model->bkg_create_date2 = date('Y-m-d 23:59:59', strtotime('today'));
		$model->bkg_pickup_date1 = date('Y-m-d 00:00:00', strtotime('today'));
		$model->bkg_pickup_date2 = date('Y-m-d 23:59:59', strtotime('today + 90 days'));

		$data = Yii::app()->request->getParam('Booking');
		if (count($data) > 0)
		{
			$model->bkg_create_date1 = "";
			$model->bkg_create_date2 = "";
			$model->bkg_pickup_date1 = "";
			$model->bkg_pickup_date2 = "";
			if ($data['bkg_create_date1'] != '' && $data['bkg_create_date2'] != '')
			{
				$model->bkg_create_date1 = $data['bkg_create_date1'];
				$model->bkg_create_date2 = $data['bkg_create_date2'];
			}
			if ($data['bkg_pickup_date1'] != '' && $data['bkg_pickup_date2'] != '')
			{
				$model->bkg_pickup_date1 = $data['bkg_pickup_date1'];
				$model->bkg_pickup_date2 = $data['bkg_pickup_date2'];
			}
		}

		$dataProvider = BookingSub::getPartnerBookings($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('partner_bookings', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionWhatsappRef()
	{
		$this->pageTitle		 = " ";
		$model					 = new Booking();
		/** @var Booking $model */
		$request				 = Yii::app()->request;
		$model->bkg_create_date1 = date('Y-m-d 00:00:00', strtotime('today - 6 days'));
		$model->bkg_create_date2 = date('Y-m-d 23:59:59', strtotime('today'));
		$orderby				 = 'date';
		if ($request->getParam('Booking'))
		{
			$data					 = $request->getParam('Booking');
			$model->bkg_create_date1 = $data['bkg_create_date1'];
			$model->bkg_create_date2 = $data['bkg_create_date2'];
			$orderby				 = $data['groupvar'];
		}

		$dataProvider	 = BookingSub::getWhatsAppRef($model, $orderby);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('whatsappref', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model, 'orderby'		 => $orderby), false, $outputJs);
	}

	public function actionAccountingFlagSet()
	{
		$this->pageTitle = "Accounting Flag Set Report";
		$model			 = new Booking();

		/** @var Booking $model */
		$request				 = Yii::app()->request;
		$model->bkg_create_date1 = date('Y-m-d 00:00:00', strtotime('today - 1 days'));
		$model->bkg_create_date2 = date('Y-m-d 23:59:59', strtotime('today'));

		if ($request->getParam('Booking'))
		{
			$data					 = $request->getParam('Booking');
			$model->bkg_create_date1 = $data['bkg_create_date1'];
			$model->bkg_create_date2 = $data['bkg_create_date2'];
		}

		$dataProvider = BookingPref::getAccountingFlagSet($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);

		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('accountingFlagSet', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model),
				false, $outputJs);
	}

}