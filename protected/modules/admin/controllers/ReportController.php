<?php

class ReportController extends Controller
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
			['allow', 'actions' => ['dailyassignedreport'], 'roles' => ['vendorAssignedReport', 'DailyAssignedReport']],
			['allow', 'actions' => ['PartnerPerformance'], 'roles' => ['chanelPartnerPerformanceReport']],
			['allow', 'actions' => ['pickup', 'gstcollection', 'vendortds'], 'roles' => ['pickupreport']],
			['allow', 'actions' => ['partnercollection'], 'roles' => ['partnerReports']],
			['allow', 'actions' => ['vendorcollection'], 'roles' => ['vendorCollectionReport']],
			['allow', 'actions' => ['business'], 'roles' => ['BusinessReport']],
			['allow', 'actions' => ['businesstrend'], 'roles' => ['BusinessTrendReport']],
			['allow', 'actions' => ['sourcezones'], 'roles' => ['SourceZonesReport']],
			['allow', 'actions' => ['destinationzones'], 'roles' => ['DestinationZonesReport']],
			['allow', 'actions' => ['booking'], 'roles' => ['BookingReport']],
			['allow', 'actions' => ['money'], 'roles' => ['MoneyReport']],
			['allow', 'actions' => ['autoAssign'], 'roles' => ['AutoAssignReport']],
			['allow', 'actions' => ['daily'], 'roles' => ['DailyReport']],
			['allow', 'actions' => ['unapprovedCabdriver'], 'roles' => ['UnapprovedCabDriverReport']],
			['allow', 'actions' => ['leadsAndUnverifiedFeedback'], 'roles' => ['LeadsUnverifiedFeedbackReport']],
			['allow', 'actions' => ['cancellations'], 'roles' => ['CancellationsReport']],
			['allow', 'actions' => ['dormantVendor'], 'roles' => ['DormantVendorsReport']],
			['allow', 'actions' => ['otpserved'], 'roles' => ['OtpservedReport']],
			['allow', 'actions' => ['travellers'], 'roles' => ['TravellersReport']],
			['allow', 'actions' => ['vendorweekly'], 'roles' => ['VendorWeeklyReport']],
			['allow', 'actions' => ['weekly'], 'roles' => ['WeeklyReport']],
			['allow', 'actions' => ['runningtotal'], 'roles' => ['RunningTotalReport']],
			['allow', 'actions' => ['cabdetails'], 'roles' => ['CabDetailsReport']],
			['allow', 'actions' => ['npsscore'], 'roles' => ['NpsScoreReport']],
			['allow', 'actions' => ['promoReport'], 'roles' => ['PromoReport']],
			['allow', 'actions' => ['financial', 'finsummary'], 'roles' => ['FinancialReport']],
			['allow', 'actions' => ['revenue'], 'roles' => ['RevenueReport']],
			['allow', 'actions' => ['mmtEnquiry'], 'roles' => ['MMTReport']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('npsreport', 'vendorperformance', 'destinationzones', 'sourcezones', 'partnercollection',
					'businesstrend', 'dailyassignedreport', 'business', 'booking', 'pickup', 'feedback', 'vendorweekly', 'travellers',
					'weekly', 'daily', 'money', 'reviseDaily', 'runningtotal', 'cabdetails', 'leadfollow', 'showlog', 'npsscore', 'dailypickup', 'getdailypickupdata',
					'account', 'accountedit', 'cancellation', 'bookingaccount', 'accountflag', 'setaccountflag', 'otpserved', 'cancellations', 'financial',
					'clearaccountflag', 'listbookingaccount', 'snapshot', 'getleadclosure', 'vendorcollection', 'cityCoverage', 'financialReport', 'unapprovedassignment', 'populateTopDemandRoutes', 'unapprovedCabdriver',
					'leadsAndUnverifiedFeedback', 'dormantVendor', 'autoAssign', 'promoReport', 'inventoryShortage', 'DailyServiceDelivery', 'PartnerPerformance', 'revenue', 'ddsbp', 'vendorCoins', 'changeMarkup'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('cancellations'),
				'users'		 => array('GeneralReport'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'monthlyreport', 'leadTeamPerformance', 'referralBonous', 'urgentpickup'),
				'users'		 => array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('topDemandRoutes'),
				'roles'		 => array('opReports'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('Tripsinloss', 'PartnerMonthlyBalance', 'VendorPayable', 'gnowOffers', 'rejectedVendorsOfGnowOffer'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		];
	}

	public function actionBooking()
	{
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
			'countReport'	 => $countReport));
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

	public function actionAccount()
	{

		$this->pageTitle = "Account Report";
		$model			 = new Booking;
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr	 = $request->getParam('Booking');
			$date1	 = $arr['bkg_create_date1'];
			$date2	 = $arr['bkg_create_date2'];
			$vendor	 = $arr['bcb_vendor_id'];
		}
		else
		{
			$date2	 = DateTimeFormat::DateToLocale(date());
			$date1	 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-7 days')));
			$vendor	 = '';
		}

		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$model->bcb_vendor_id	 = $vendor;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);
		$dataProvider			 = $model->accountReport($date1, $date2, $from, $to, $vendor, $platform);
		$this->render('report_account', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAccountedit($bkgId)
	{

		$bkgId						 = Yii::app()->request->getParam('bkgId'); //$_POST['booking_id'];
		/* var $model Booking */
		$model						 = new Booking();
		$bookModel					 = $model->findByPk($bkgId);
		$oldModel					 = clone $bookModel;
		$oldData					 = $bookModel->attributes;
		$bookModel->bkg_due_amount	 = $bookModel->bkg_total_amount - $bookModel->getTotalPayment();
		$bookModel->scenario		 = 'accountupdate';

		if (isset($_POST['Booking']))
		{
			$bookModel->attributes	 = Yii::app()->request->getParam('Booking');
			$arr					 = $bookModel->attributes;
			if (count($arr) > 0)
			{
				$bookModel->bkg_total_amount	 = trim($arr['bkg_total_amount']);
				$bookModel->bkg_vendor_amount	 = trim($arr['bkg_vendor_amount']);
				$bookModel->bkg_advance_amount	 = trim($arr['bkg_advance_amount']);
				$bookModel->bkg_due_amount		 = (trim($arr['bkg_total_amount']) - trim($arr['bkg_advance_amount']));
				$bookModel->save();
				//$newData = Booking::model()->findByPk($bkgId);
				$newData						 = $arr;
				//$getDifference = array_diff_assoc($newData, $oldData);
				$getDifference					 = array_diff_assoc($oldData, $newData);
				if (count($getDifference) > 0)
				{
					$changesForLog = " Old Values: " . Booking::model()->getModificationMSG($getDifference, 'log');
				}
				$remarks = "Remarks: " . trim($_POST['Booking']['bkg_message']);
				if ($bkgId != '')
				{
					$desc							 = $changesForLog . " " . $remarks;
					$userInfo						 = UserInfo::getInstance();
					$eventId						 = BookingLog::ACCOUNT_REMARKS;
					$params							 = [];
					$params['blg_ref_id']			 = $bookModel->bkg_user_id;
					$params['blg_booking_status']	 = $bookModel->bkg_status;
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $arr];
				echo json_encode($data);
				Yii::app()->end();
			}
			//$this->redirect(array('account'));
		}
		$this->renderPartial('accountedit', array('bkgId' => $bkgId, 'bookModel' => $bookModel), false, true);
	}

	public function actionBookingaccount()
	{
		$this->pageTitle = "Booking Account";
		/* var $model Booking */
		$model			 = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr		 = Yii::app()->request->getParam('Booking');
			$date1		 = $arr['bkg_create_date1'];
			$date2		 = $arr['bkg_create_date2'];
			$from		 = $arr['bkg_from_city_id'];
			$to			 = $arr['bkg_to_city_id'];
			$vendor		 = $arr['bkg_vendor_id'];
			$platform	 = $arr['bkg_platform'];
			$searchTxt	 = $arr['search'];
		}
		else
		{
			$date2		 = '';
			$date1		 = '';
			$from		 = '';
			$to			 = '';
			$vendor		 = '';
			$platform	 = '';
			$searchTxt	 = '';
		}
		$model->bkg_create_date1		 = $date1;
		$model->bkg_create_date2		 = $date2;
		$model->bkg_from_city_id		 = $from;
		$model->bkg_to_city_id			 = $to;
		$model->bkg_vendor_id			 = $vendor;
		$model->bkgTrail->bkg_platform	 = $platform;
		$date1							 = DateTimeFormat::DatePickerToDate($date1);
		$date2							 = DateTimeFormat::DatePickerToDate($date2);
		$dataProvider					 = $model->bookingAccountReport($date1, $date2, $from, $to, $vendor, $platform, $searchTxt);
		$this->render('report_booking_account', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'searchTxt'		 => $searchTxt));
	}

	public function actionAccountflag1()
	{
		$bkgId			 = Yii::app()->request->getParam('bkg_id');  //$_POST['booking_id'];
		$bkgAccountFlag	 = Yii::app()->request->getParam('bkg_account_flag'); //$_POST['booking_id'];
		$success		 = false;
		$userInfo		 = UserInfo::getInstance();
		if ($bkgAccountFlag == 1)
		{
			/* var $model Booking */
			$model					 = Booking::model()->resetScope()->findByPk($bkgId);
			$oldModel				 = $model;
			$model->bkg_account_flag = 0;
			$model->scenario		 = 'accountflag';
			if ($model->save())
			{
				$eventId						 = BookingLog::UNSET_ACCOUNTING_FLAG;
				$desc							 = "Accounting Flag has been cleared.";
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
				$success						 = true;
			}
			else
			{
				print_r($model->getErrors());
			}
			$status = $model->bkg_status;
		}
		else if ($bkgAccountFlag == 0)
		{
			/* var $model Booking */
			$model					 = Booking::model()->resetScope()->findByPk($bkgId);
			$oldModel				 = $model;
			$model->bkg_account_flag = 1;
			$model->scenario		 = 'accountflag';
			if ($model->save())
			{
				$eventId						 = BookingLog::SET_ACCOUNTING_FLAG;
				$desc							 = "Accounting Flag has been set.";
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
				$success						 = true;
			}
			else
			{
				print_r($model->getErrors());
			}
			$status = $model->bkg_status;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'status' => $status];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionSetaccountflag()
	{
		$BookingIds	 = Yii::app()->request->getParam('bkIds');
		$success	 = false;
		if (Booking::model()->setAccountFlagByIds($BookingIds))
		{
			$success = true;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $success;
			Yii::app()->end();
		}
	}

	public function actionClearaccountflag()
	{
		$BookingIds	 = Yii::app()->request->getParam('bkIds');
		$success	 = false;
		if (Booking::model()->unsetAccountFlagByIds($BookingIds))
		{
			$success = true;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $success;
			Yii::app()->end();
		}
	}

	public function actionCancellation()
	{
		$this->pageTitle = "Cancellation Report";
		/* var $model Booking */
		$request		 = Yii::app()->request;
		$model			 = new Booking;
		if ($request->getParam('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$bookingDate1	 = $arr['bkg_create_date1'];
			$bookingDate2	 = $arr['bkg_create_date2'];
			$pickupFromDate	 = $arr['bkg_pickup_date_date'];
			$pickupToDate	 = $arr['bkg_return_date_date'];
			$from			 = $arr['bkg_from_city_id'];
			$to				 = $arr['bkg_to_city_id'];
			$vendor			 = $arr['bkg_vendor_id'];
		}
		else
		{
			$bookingDate1	 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-120 days')));
			$bookingDate2	 = DateTimeFormat::DateToLocale(date());
			$from			 = '';
			$to				 = '';
			$vendor			 = '';
		}
		$model->bkg_create_date1	 = $bookingDate1;
		$model->bkg_create_date2	 = $bookingDate2;
		$model->bkg_from_city_id	 = $from;
		$model->bkg_to_city_id		 = $to;
		$model->bkg_vendor_id		 = $vendor;
		$model->bkg_pickup_date_date = $pickupFromDate;
		$model->bkg_return_date_date = $pickupToDate;
		$bookingDate1				 = DateTimeFormat::DatePickerToDate($bookingDate1);
		$bookingDate2				 = DateTimeFormat::DatePickerToDate($bookingDate2);
		$pickupFromDate				 = DateTimeFormat::DatePickerToDate($pickupFromDate);
		$pickupToDate				 = DateTimeFormat::DatePickerToDate($pickupToDate);
		$dataProvider				 = $model->cancellationReport($bookingDate1, $bookingDate2, $from, $to, $vendor, $pickupFromDate, $pickupToDate);
		$countReport				 = $model->cancellationReportCount($bookingDate1, $bookingDate2, $from, $to, $vendor, $pickupFromDate, $pickupToDate);
		$this->render('report_cancellation', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'countReport'	 => $countReport
		));
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

	public function actionVendorweekly()
	{
		$this->pageTitle = "Vendor Weekly Report";
		$model			 = new Booking;
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$date1			 = $arr['bkg_create_date1'];
			$date2			 = $arr['bkg_create_date2'];
			$vendorStatus	 = $arr['bkg_vendor_status'];
			$vendorId		 = $arr['bkg_vendor'];
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d');
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('+7 days'));
			$date2					 = $model->bkg_pickup_date2;
			$date1					 = $model->bkg_pickup_date1;
			$vendorStatus			 = 6;
			$vendorId				 = 0;
		}
		$model->bkg_create_date1	 = $date1;
		$model->bkg_create_date2	 = $date2;
		$model->bkg_vendor_status	 = $vendorStatus;
		$model->bkg_vendor			 = $vendorId;
//        $date1 = DateTimeFormat::DatePickerToDate($date1);
//        $date2 = DateTimeFormat::DatePickerToDate($date2);
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']) && isset($_REQUEST['export_status1']) && isset($_REQUEST['export_id1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = DateTimeFormat::DatePickerToDate($request->getParam('export_from1'));
			$toDate			 = DateTimeFormat::DatePickerToDate($request->getParam('export_to1'));
			$arr2			 = $adminWrapper->vendorReportCount($fromDate, $toDate, $request->getParam('export_status1'), $request->getParam('export_id1'));
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"vendor_weekly_above_report.csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen("php://output", 'w');
				fputcsv($handle, array("Total Booking", "Total Trip Days", "Total Booking Amount", "Gozo Commission Due"));
				if ($arr2 != "")
				{
					fputcsv($handle, array($arr2['b_count'], $arr2['t_days'], $arr2['b_amount'], $arr2['commission']));
				}
				fclose($handle);
				exit;
			}
		}
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate($request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate($request->getParam('export_to2'));
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorWeeklyReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportbooking" . date('YmdHi') . ".csv";
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

			$status		 = Booking::model()->getBookingStatus();
			$bookingType = Booking::model()->getBookingType();
			$status1	 = $request->getParam('export_status2');
			$vid		 = $request > getParam('export_id2');
			$type		 = 'command';
			$rows		 = $model->vendorWeeklyReport($fromDate, $toDate, $status1, $vid, $type);
			$handle		 = fopen("php://output", 'w');
			$i			 = 0;
			foreach ($rows as $row)
			{
				if ($i > 0)
				{
					$row['Status']		 = $status[$row['Status']];
					$row['Booking Type'] = $bookingType[$row['Booking Type']];
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
		$dataProvider	 = $model->vendorWeeklyReport($date1, $date2, $vendorStatus, $vendorId);
		$countReport	 = $model->vendorReportCount($date1, $date2, $vendorStatus, $vendorId);
		$this->render('report_vendor_weekly', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport));
	}

	public function actionWeekly()
	{
		$this->pageTitle = "Weekly Report";
		$model			 = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr			 = Yii::app()->request->getParam('Booking');
			$date1			 = $arr['bkg_create_date1'];
			$date2			 = $arr['bkg_create_date2'];
			$vendorStatus	 = $arr['bkg_vendor_status'];
		}
		else
		{
			$date2			 = DateTimeFormat::DateToLocale(date());
			$date1			 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-7 days')));
			$vendorStatus	 = 0;
		}
		$model->bkg_create_date1	 = $date1;
		$model->bkg_create_date2	 = $date2;
		$model->bkg_vendor_status	 = $vendorStatus;
		$date1						 = DateTimeFormat::DatePickerToDate($date1);
		$date2						 = DateTimeFormat::DatePickerToDate($date2);
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']) && isset($_REQUEST['export_status1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from1'));
			$toDate			 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to1'));
			$arr2			 = $adminWrapper->weeklyReportCount($fromDate, $toDate, Yii::app()->request->getParam('export_status1'));
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"weekly_above_report.csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen("php://output", 'w');
				fputcsv($handle, array("Total Booking", "Total Trip Days", "Total Booking Amount", "Gozo Commission Due"));
				if ($arr2 != "")
				{
					fputcsv($handle, array($arr2['b_count'], $arr2['t_days'], $arr2['b_amount'], $arr2['commission']));
				}
				fclose($handle);
				exit;
			}
		}
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to2'));
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"WeeklyReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
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

			$status		 = Booking::model()->getBookingStatus();
			$bookingType = Booking::model()->getBookingType();
			$status1	 = Yii::app()->request->getParam('export_status2');
			$type		 = 'command';
			$rows		 = $model->weeklyReport($fromDate, $toDate, $status1, $type);

			$handle	 = fopen("php://output", 'w');
			$i		 = 0;
			foreach ($rows as $row)
			{
				if ($i > 0)
				{
					$row['Status']		 = $status[$row['Status']];
					$row['Booking Type'] = $bookingType[$row['Booking Type']];
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
		$dataProvider	 = $model->dailyReport($date1, $date2, $vendorStatus);
		$countReport	 = $model->dailyReportCount($date1, $date2, $vendorStatus);
		$this->render('report_weekly', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport));
	}

	public function actionReviseDaily()
	{

		$str		 = '<h3><b>Date & Time of Report:</b> ' . date("l") . ', ' . date("j F Y") . ' at ' . date("G:i") . '</h3><br/>';
		$modelsub	 = new BookingSub();
		$modelven	 = new Vendors();
		$str		 .= $modelsub->getBusinessReportHtml();
		$str		 .= $modelsub->getBusinessTrendReportHtml();
		$str		 .= $modelsub->getRevenueReportHtml();
		$str		 .= $modelsub->getRevenueReportHtmlByPickup();
		$str		 .= $modelven->getReceivablePendingHtml();
		$str		 .= $modelven->getReceivablePendingByVendorHtml();
		$str		 .= $modelsub->getDistributionByBookingTypeHtml();
		$str		 .= $modelsub->getVendorAssignmentReportHtml();
		$str		 .= $modelsub->getRegionalBookingDistributionHtml();
		$str		 .= $modelsub->getSmartMatchHtml();
		$str		 .= $modelsub->getActiveBookingHtml();
		$str		 .= $modelsub->getBookingCreatedPatternHtml();
		$str		 .= $modelsub->getBookingCancellationPatternHtml();
		$str		 .= $modelsub->getPLTrendReportHtml();
		$str		 .= $modelsub->getAdvancePaymentReportHtml();
		$str		 .= $modelsub->getNewRepeatCustomerHtml();
		$str		 .= $modelsub->getLifetimeTripReportHtml();
		$str		 .= $modelsub->getBookingByRatingReportHtml();
		$str		 .= $modelsub->getBookingByPlatformReportHtml();
		$str		 .= $modelsub->getBusinessSourceZoneHtml();
		$str		 .= $modelsub->getBusinessDestinationZoneHtml();
		$str		 .= $modelsub->getCancellationBookingReportHtml();
		$str		 .= $modelsub->getCancellationReasonReportHtml();
		$str		 .= $modelsub->getCancelReasonReportHtml();

		$str .= $modelsub->getCancellationSourceReportHtml();
		$str .= $modelsub->getInventoryMetricsReportHtml();
		$str .= $modelsub->getBookingBySourceReportHtml();

		$str .= $modelsub->getNonProfitBookingsByMtdHtml();
		$str .= $modelsub->getCancellationTrendReportHtml();
		$str .= $modelsub->getBookingByZoneReportHtml();
		$str .= $modelsub->getZoneCancellationReportHtml();
		$str .= $modelsub->getZoneCancellationReportHtml('to');
		echo $str;
		exit();
	}

	public function actionDaily()
	{
		$this->pageTitle = "Daily Report";

		$model = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr			 = Yii::app()->request->getParam('Booking');
			$date1			 = $arr['bkg_create_date1'];
			$date2			 = $arr['bkg_create_date2'];
			$vendorStatus	 = $arr['bkg_vendor_status'];
		}
		else
		{
			$date2			 = DateTimeFormat::DateToLocale(date());
			$date1			 = DateTimeFormat::DateToLocale(date());
			$vendorStatus	 = 0;
		}
		$model->bkg_create_date1	 = $date1;
		$model->bkg_create_date2	 = $date2;
		$model->bkg_vendor_status	 = $vendorStatus;
		$date1						 = DateTimeFormat::DatePickerToDate($date1);
		$date2						 = DateTimeFormat::DatePickerToDate($date2);
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']) && isset($_REQUEST['export_status1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from1'));
			$toDate			 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to1'));
			$arr2			 = $adminWrapper->dailyReportCount($fromDate, $toDate, Yii::app()->request->getParam('export_status1'));
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"daily_above_report.csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen("php://output", 'w');
				fputcsv($handle, array("Total Booking", "Total Trip Days", "Total Booking Amount", "Gozo Commission Due"));
				if ($arr2 != "")
				{
					fputcsv($handle, array($arr2['b_count'], $arr2['t_days'], $arr2['b_amount'], $arr2['commission']));
				}
				fclose($handle);
				exit;
			}
		}
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to2'));
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DailyReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
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
			$status		 = Booking::model()->getBookingStatus();
			$bookingType = Booking::model()->getBookingType();
			$status1	 = Yii::app()->request->getParam('export_status2');
			$type		 = 'command';
			$rows		 = $model->weeklyReport($fromDate, $toDate, $status1, $type);
			$handle		 = fopen("php://output", 'w');
			$i			 = 0;
			foreach ($rows as $row)
			{
				if ($i > 0)
				{
					$row['Status']		 = $status[$row['Status']];
					$row['Booking Type'] = $bookingType[$row['Booking Type']];
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
		$dataProvider	 = $model->dailyReport($date1, $date2, $vendorStatus);
		$countReport	 = $model->dailyReportCount($date1, $date2, $vendorStatus);

		$this->render('report_daily', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport));
	}

	public function actionRunningtotal()
	{
		$this->pageTitle = "Running Total Report";
		$model			 = new Booking;
		$request		 = Yii::app()->request;
		$queueType		 = $request->getParam("datetype", 1);
		if ($request->getParam('Booking'))
		{
			$arr		 = $request->getParam('Booking');
			$date1		 = $arr['bkg_create_date1'];
			$date2		 = $arr['bkg_create_date2'];
			$dateType	 = $arr['dateType'];
		}
		else
		{
			$date2		 = DateTimeFormat::DateToLocale(date());
			$date1		 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-15 days')));
			$dateType	 = $queueType;
		}
		$model->dateType		 = $dateType;
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate		 = DateTimeFormat::DatePickerToDate($request->getParam('export_from2'));
			$toDate			 = DateTimeFormat::DatePickerToDate($request->getParam('export_to2'));
			$export_dateType = $request->getParam('export_dateType');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RunningTotalReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename		 = "reportbooking" . date('YmdHi') . ".csv";
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
			$rows	 = $model->runningTotalReport($fromDate, $toDate, $export_dateType, 'data');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, array("Date", "Total Bookings", "Avg Booking Amount", "Trips Booked", "Trips Started", "Trips Completed"));
			foreach ($rows as $row)
			{
				$row1 = array_values($row);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}
		$dataProvider = $model->runningTotalReport($date1, $date2, $dateType);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_running_total', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionCabdetails()
	{
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
		$this->render('report_cab_details', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionMoney()
	{
		$this->pageTitle = "Money Report";
		$model			 = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr = Yii::app()->request->getParam('Booking');
			if ($arr['category'] != '' && $arr['category'] != 0)
			{
				$category = $arr['category'];
			}
			else
			{
				$category = '1,2,3,4,5,6,7';
			}
			$year	 = $arr['year'];
			$month	 = $arr['month'];
			$date	 = trim($arr['year']) . "-" . trim($arr['month']) . "-01";
		}
		else
		{
			$year		 = date('Y');
			$month		 = date('m');
			$date		 = trim($year) . "-" . trim($month) . "-01";
			$category	 = '1,2,3,4,5,6,7';
		}
		$model->year			 = $year;
		$model->month			 = $month;
		$model->category		 = $category;
		$model->bkg_create_date	 = $date;
		$dataProvider			 = BookingSub::model()->moneyReport($date, $category);
		$this->render('report_money', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionNpsscore()
	{
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
		$dataProvider		 = $model->npsReport($date1, $date2);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_nps', array('dataProvider' => $dataProvider, 'model' => $model));
	}

//    public function actionListbookingaccount()
//    {
//        $this->pageTitle = "Today's Accounting Action List";
//        /* var $model Booking */
//        $model           = new Booking();
//        $pageSize        = '9';
//        $setFlag         = '1';
//        $recordSet       = $model->accountReportByFlag($setFlag);
//        $bookList        = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => $pageSize),));
//        $bookModels      = $bookList->getData();
//        /* var $model VendorTransactions */
//        $venModel        = new VendorTransactions();
//        $recordSet2      = $venModel->vendorCollectionReport();
//        $vendorList      = new CArrayDataProvider($recordSet2, array('pagination' => array('pageSize' => $pageSize),));
//        $vendorModels    = $vendorList->getData();
//
//        $venModel->ven_date_type = '1';
//        $dateFromDate            = date('Y-m-d');
//        $dateTodate              = date('Y-m-d', strtotime("+7 days"));
//        $venModel->ven_from_date = DateTimeFormat::DateToLocale($dateFromDate);
//        $venModel->ven_to_date   = DateTimeFormat::DateToLocale($dateTodate);
//        $venModel->scenario      = 'transaction_search';
//
//        if (isset($_REQUEST['VendorTransactions']))
//        {
//            $venModel->attributes = $_REQUEST['VendorTransactions'];
//            if ($venModel->validate())
//            {
//                $submit = trim($_POST['submit']);
//                if ($submit == "1")
//                {
//                    $this->forward('vendor/generatepdf');
//                }
//                if ($submit == "2")
//                {
//                    $this->forward('vendor/listvendoraccount');
//                }
//            }
//            else
//            {
//
//                //  print_r($venModel->getErrors());exit();
//            }
//        }
//
//        $this->render('report_list_bookingaccount', array('model'        => $model,
//            'venModel'     => $venModel,
//            'bookModels'   => $bookModels,
//            'bookingList'  => $bookList,
//            'vendorModels' => $vendorModels,
//            'vendorList'   => $vendorList));
//    }

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

	public function actionGstcollection()
	{
		$this->pageTitle = "GST Collection";

		$model = new Booking;
		if (isset($_REQUEST['Booking']))
		{
			$arr	 = Yii::app()->request->getParam('Booking');
			$date1	 = $arr['bkg_create_date1'];
			$date2	 = $arr['bkg_create_date2'];
		}
		else
		{
			$month_ini			 = new DateTime("first day of last month");
			$month_end			 = new DateTime("last day of last month");
			$last_month_firstday = $month_ini->format('Y-m-d'); // 2012-02-01
			$last_month_lastday	 = $month_end->format('Y-m-d'); // 2012-02-29 
			$date1				 = ($last_month_firstday != '') ? date('d/m/Y', strtotime($last_month_firstday)) : '';
			$date2				 = ($last_month_lastday != '') ? date('d/m/Y', strtotime($last_month_lastday)) : '';
		}
		$model->bkg_create_date1 = $date1;
		$model->bkg_create_date2 = $date2;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);

		$dataProvider = $model->gstCollectionReport($date1, $date2);
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to2'));

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"GstCollectionReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportgstcollection" . date('YmdHi') . ".csv";
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

			$type	 = 'command';
			$rows	 = $model->gstCollectionReport($fromDate, $toDate, $type);

			$handle = fopen("php://output", 'w');
			fputcsv($handle, [
				'Date',
				'Agent Id',
				'Partner Name',
				'State',
				'Gst',
				'Base Fare',
				'Base Fare Without Driver Allowance',
				'Cancel Base Fare',
				'Cancel Gst']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['date']			 = $row['date'];
				$rowArray['AgentID']		 = $row['AgentID'];
				$rowArray['PartnerName']	 = $row['PartnerName'];
				$rowArray['stt_name']		 = $row['stt_name'];
				$rowArray['GST']			 = $row['GST'];
				$rowArray['BaseFare']		 = $row['BaseFare'];
				$rowArray['BaseFareWithoutDriverAllowance']		 = $row['netBaseAmount'];
				$rowArray['CancelBaseFare']	 = $row['CancelBaseFare'];
				$rowArray['CancelGST']		 = $row['CancelGST'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);

			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}

			exit;
		}
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 60]);

		$this->render('gst_collection', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionVendorTds()
	{
		$this->pageTitle = "Vendor TDS";

		$model = new Vendors;
		if (isset($_REQUEST['Vendors']))
		{
			$arr	 = Yii::app()->request->getParam('Vendors');
			$date1	 = $arr['vnd_create_date1'];
			$date2	 = $arr['vnd_create_date2'];
		}
		else
		{
			$month_end			 = new DateTime("last day of last month");
			$last_month_lastday	 = $month_end->format('Y-m-d');
			$date2				 = ($last_month_lastday != '') ? date('d/m/Y', strtotime($last_month_lastday)) : '';
			$newEndingDate		 = date("Y-m-d", strtotime(date("Y-m-d", strtotime($last_month_lastday)) . " - 1 year"));
			$date1				 = ($newEndingDate != '') ? date('d/m/Y', strtotime($newEndingDate)) : '';
		}
		$model->vnd_create_date1 = $date1;
		$model->vnd_create_date2 = $date2;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);

		$dataProvider = $model->getTdsReport($date1, $date2);

		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to2'));

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorTdsReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportvendortds" . date('YmdHi') . ".csv";
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

			$type	 = 'command';
			$rows	 = $model->getTdsReport($fromDate, $toDate, $type);

			$handle = fopen("php://output", 'w');
			fputcsv($handle, [
				'Vendor Name',
				'Contact Name',
				'Pan No',
				'Total TDS',
				'Total TripPurchased']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['vnd_name']			 = $row['vnd_name'];
				$rowArray['ctt_name']			 = $row['ctt_name'];
				$rowArray['ctt_pan_no']			 = $row['ctt_pan_no'];
				$rowArray['totalTds']			 = $row['totalTds'];
				$rowArray['totalTripPurchased']	 = $row['totalTripPurchased'];
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

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 60]);

		$this->render('vendor_tds', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionLeadsAndUnverifiedFeedback()
	{
		$this->pageTitle = 'Leads/Unverified Customers';
		$model			 = new LeadFollowup();
		$request		 = Yii::app()->request;
		if ($request->getParam('LeadFollowup'))
		{
			$arr					 = $request->getParam('LeadFollowup');
			$model->lfu_from_date	 = $arr['lfu_from_date'];
			$model->lfu_to_date		 = $arr['lfu_to_date'];
		}
		else
		{
			$model->lfu_from_date	 = date("Y-m-d");
			$model->lfu_to_date		 = date("Y-m-d");
		}
		$dataProvider = LeadFollowup::model()->getList($model->lfu_from_date, $model->lfu_to_date, false);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report-leads-and-unverified', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionMonthlyreport()
	{
		$isEmail = Yii::app()->request->getParam('email');
		if ($isEmail != '1')
		{
			$checkAccess = Yii::app()->authManager->checkAccess('Reports', Yii::app()->user->getId());
			if (!$checkAccess)
			{
				throw new CHttpException(403, 'Unauthorized Access', 403);
			}
		}
		$this->pageTitle = "Monthly Business Report";
		$pageSize		 = '12';
		/* var $model Booking */
		$model			 = new Booking();

		if (isset($_REQUEST['Booking']))
		{
			$year	 = trim($_REQUEST['Booking']['year']);
			$month	 = trim($_REQUEST['Booking']['month']);
		}
		else
		{
			$month	 = date('m');
			$year	 = date('Y');
		}
		if ($isEmail == '1')
		{
			$data		 = $model->monthlyReport($month, $year);
			$reportText	 = $this->renderPartial('report_monthly_business', ['model'	 => $model,
				'data'	 => $data, 'month'	 => $month, 'year'	 => $year, 'email'	 => $isEmail], true);

			$mail		 = new EIMailer();
			$mail->clearView();
			$mail->clearLayout();
			$mail->setFrom('info@aaocab.com', 'Info Gozocabs');
			$sentEmail	 = Yii::app()->params['adminEmail'];
			//$sentEmail = "sudipta.roy81@gmail.com";
			$mail->setTo($sentEmail, 'Gozocabs Admin');
			$mail->setBody($reportText);
			$subject	 = 'Gozocabs - Monthly Report as on ' . date('d/m/Y', strtotime(date('Y-m-d')));
			$mail->setSubject($subject);
			if ($mail->sendServicesEmail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			echo $delivered;
		}
		else
		{
			$data = $model->monthlyReport($month, $year);
			$this->render('report_monthly_business', ['model'	 => $model,
				'data'	 => $data,
				'month'	 => $month,
				'year'	 => $year]);
		}
	}

	public function actionBusiness()
	{
		$this->pageTitle = "Business Report";
		$model			 = new Booking();
		/* @var $modelsub BookingSub */
		$modelsub		 = new BookingSub();
		$trips_booked	 = $modelsub->businessBookingReport();
		$nps			 = $modelsub->businessNps();
		$this->render('business', ['trips_booked' => $trips_booked, 'nps' => $nps, 'modelsub' => $modelsub]);
	}

	public function actionBusinesstrend()
	{
		$this->pageTitle = "Business Trend Report";
		$model			 = new Booking();
		/* @var $modelsub BookingSub */
		$modelsub		 = new BookingSub();
		$gmv			 = $model->businesstrendGmv();
		$advance_payment = $model->businesstrendAdvancePayment();
		$trips_booked	 = $model->businesstrendBookingReport();
		$cancellations	 = $model->businesstrendCancellations();
		$trips_complete	 = $model->businesstrendComplete();
		$reviews		 = $model->businesstrendReviews();
		$nps			 = $model->businesstrendNps();

		// $collection.             = Vendors::model()->getReceivablePendingHtml();
		$this->render('businesstrend', ['gmv' => $gmv, 'advance_payment' => $advance_payment, 'trips_booked' => $trips_booked, 'cancellations' => $cancellations, 'trips_complete' => $trips_complete, 'reviews' => $reviews, 'nps' => $nps]);
	}

	public function actionSourcezones()
	{
		$this->pageTitle = "Top 10 Source Zones";
		//$model = new Booking();
		/* @var $model BookingSub */
		$modelsub		 = new BookingSub();
		$recordSet		 = $modelsub->businessSourceZones();
		$this->render('businesssource', ['records' => $recordSet, 'type' => 's']);
	}

	public function actionDestinationzones()
	{
		$this->pageTitle = "Top 10 Destination Zones";
		/* @var $model BookingSub */
		$modelsub		 = new BookingSub();
		$recordSet		 = $modelsub->businessSourceZones();
		$this->render('businesssource', ['records' => $recordSet, 'type' => 'd']);
	}

	public function actionVendorperformance()
	{
		$this->pageTitle = "Vendor Performance Report";
		$model			 = Vendors::model()->vendorPerformanceReport();
		$modelList		 = new CArrayDataProvider($model, array('pagination' => array('pageSize' => 20),));
		$models			 = $modelList->getData();
		$this->render('vendor_performance', array('records' => $models, 'usersList' => $modelList));
	}

	public function actionNpsreport()
	{
		$this->pageTitle = "NPS Trend Report";
		$model			 = new Ratings();
		$nps			 = $model->businesstrendNps();
		$this->render('businesstrend_nps', ['nps' => $nps]);
	}

	public function actionSnapshot()
	{
		$this->pageTitle = "Snapshot Report";
		$model			 = new BookingTemp();
		$data			 = $model->reportSnapshot();
		$this->render('report_snapshot', ['data' => $data]);
	}

	public function actionGetleadclosure()
	{
		$stdate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('stdate'));
		$endate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('endate'));
		$model	 = new BookingTemp();
		$data	 = $model->getLeadClosureTimeByDateRange($stdate, $endate);
		echo CJSON::encode(['ctime' => round($data['avgLeadClosingDay'], 1)]);
		//	$this->render('report_snapshot', ['data' => $data]);
	}

	public function actionVendorcollection()
	{
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$name		 = ($_REQUEST['export_vnd_operator'] != '') ? $_REQUEST['export_vnd_operator'] : '';
			$zone		 = ($_REQUEST['export_vnd_zone'] != '') ? $_REQUEST['export_vnd_zone'] : '';
			$city		 = ($_REQUEST['export_vnd_cty'] != '') ? $_REQUEST['export_vnd_cty'] : '';
			$payableFor	 = ($_REQUEST['export_vnd_amount_pay'] != '') ? $_REQUEST['export_vnd_amount_pay'] : '';
			$amount		 = ($_REQUEST['export_vnd_amount'] > 0) ? $_REQUEST['export_vnd_amount'] : '';
			$admin		 = ($_REQUEST['export_vnd_rm'] != '') ? $_REQUEST['export_vnd_rm'] : '';
			$modDay		 = ($_REQUEST['export_vnd_mod_day'] != '') ? $_REQUEST['export_vnd_mod_day'] : '';
			$dayRange	 = ($_REQUEST['export_day_range'] != '') ? $_REQUEST['export_day_range'] : '';

			$qry = ['name'		 => $name,
				'dayRange'	 => $dayRange,
				'zone'		 => $zone,
				'city'		 => $city,
				'payableFor' => $payableFor,
				'amount'	 => $amount,
				'admin'		 => $admin,
				'modDay'	 => $modDay];

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorCollection_" . date('Ymdhis') . ".csv\"");
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
			$type	 = 'command';
			$rows	 = Vendors::getCollectionReport($qry, true);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Operator Id', 'Operator Name', 'Beneficiary Id', 'Phone', 'Relationship Manager', 'Credit Limit', 'Effective Credit Limit',
				'Overdue Days', 'Security Amount', 'Last Payment Receive Amt', 'Last Payment Receive Date', 'Last Payment Sent Amt', 'Last Payment Sent Date',
				'ADB(30 days)', 'ADB(10 days)', 'Running Balance', 'Amount to pay', 'Last_Trip_Completed_Date', 'Withdrawable Balance', 'Locked Amount', 'Account status', 'COD Freeze Status',
				'Trips', 'Rating', 'Contact Id', 'Number of contact', 'BankDetails_Lastmodified']);
			if (count($rows) > 0)
			{

				foreach ($rows as $row)
				{
					if ($row['vnd_active'] == 2)
					{
						$vndActiveVal = 'Blocked';
					}
					else if ($row['vnd_is_freeze'] == 1)
					{
						$vndActiveVal = 'Freezed';
					}
					else
					{
						$vndActiveVal = 'Active';
					}

					$amtToPay = 0;
					if ($row['totTrans'] < 0)
					{
						$amtToPay	 = (abs($row['totTrans']) - $row['vnd_credit_limit']);
						$amtToPay	 = ($amtToPay > 0) ? $amtToPay : 0;
					}

					$recvData								 = AccountTransDetails::getLastPaymentReceived($row['vnd_id'], '2');
					$sentData								 = AccountTransDetails::getLastPaymentSent($row['vnd_id'], '2');
					$sentData['paymentSent']				 = ($sentData['paymentSent'] * -1);
					$rowArray								 = array(
						'vnd_id'			 => $row['vnd_id'],
						'vnd_name'			 => $row['vnd_name'],
						'vnd_beneficiary_id' => $row['vnd_beneficiary_id'],
						'vnd_phone'			 => $row['vnd_phone'],
						'relation_manager'	 => $row['relation_manager']);
					$rowArray['vnd_credit_limit']			 = ($row['vnd_credit_limit'] > 0) ? $row['vnd_credit_limit'] : 0;
					$rowArray['vnd_effective_credit_limit']	 = ($row['vnd_effective_credit_limit'] > 0) ? $row['vnd_effective_credit_limit'] : 0;

					$rowArray['vnd_effective_overdue_days']	 = ($row['vnd_effective_overdue_days'] > 0) ? $row['vnd_effective_overdue_days'] : 0;
					$rowArray['vnd_security_amount']		 = ($row['vnd_security_amount'] > 0) ? $row['vnd_security_amount'] : 0;
					$rowArray['last_payment_receive_amt']	 = ($recvData['paymentReceived'] > 0) ? $recvData['paymentReceived'] : 0;
					$rowArray['last_payment_receive_date']	 = ($recvData['ReceivedDate'] != '') ? $recvData['ReceivedDate'] : '';
					$rowArray['last_payment_sent_amt']		 = ($sentData['paymentSent'] > 0) ? $sentData['paymentSent'] : 0;
					$rowArray['last_payment_sent_date']		 = ($sentData['sentDate'] != '') ? $sentData['sentDate'] : '';

					$rowArray['vsm_avg30']					 = $row['vsm_avg30'];
					$rowArray['vsm_avg10']					 = $row['vsm_avg10'];
					$rowArray['totTrans']					 = $row['totTrans'];
					$rowArray['amt_to_pay']					 = $amtToPay;
					$rowArray['last_trip_completed_date']	 = $row['last_trip_completed_date'];
					$rowArray['withdrawable_balance']		 = $row['withdrawable_balance']; //AccountTransDetails::setWithdrawableBalance($row['vnd_id'], $row['totTrans']);
					$rowArray['vrs_locked_amount']			 = ($row['vrs_locked_amount'] > 0) ? $row['vrs_locked_amount'] : 0;
					$rowArray['vnd_active']					 = $vndActiveVal;
					$rowArray['vnd_cod_freeze']				 = ($row['vnd_cod_freeze'] == 1) ? 'Yes' : 'No';
					$rowArray['trips']						 = $row['trips'];
					$rowArray['rating']						 = $row['rating'];
					$rowArray['contact_id']					 = $row['contact_id'];
					$rowArray['NumOfContact']				 = $row['cntContact'];
					$rowArray['bankdetails_modify_date']	 = $row['bankdetails_modify_date'];

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


		$trans = DBUtil::beginTransaction();
		try
		{
			if (isset($_POST["import"]))
			{
				$message	 = "";
				$success	 = false;
				$fileName	 = $_FILES["file"]["tmp_name"];
				if ($_FILES["file"]["size"] > 0)
				{
					$file	 = fopen($fileName, "r");
					$i		 = 0;
					$count	 = 0;
					$vendors = "";
					$sendCnt = 0;
					while (($getData = fgetcsv($file, 10000, ",")))
					{
						if ($count > 0)
						{
							if ($getData[3] != '' && $getData[5] != '')
							{
								date('Y-m-d', strtotime($getData[6]));
								$bankLedger		 = 30;
								$remarks		 = trim($getData[7]);
								$operatorId		 = trim($getData[5]);
								$paymentTypeId	 = 2;
								$amount			 = trim($getData[3]);
								$paymentDate	 = $getData[6];
								$success		 = AccountTransactions::model()->AddCsvdatatoAccounts($amount, $operatorId, $paymentDate, $remarks, $bankLedger, $paymentTypeId, Accounting::AT_OPERATOR, Accounting::LI_OPERATOR);
								$i++;
							}
							if ($amount > 0)
							{
								try
								{
									$vendors .= $operatorId . ",";
									$sendCnt++;
									Vendors::notifyVendorPaymentRelease($operatorId, $amount, 1,TemplateMaster::SEQ_WHATSAPP_CODE);
									Vendors::notifyVendorPaymentRelease($operatorId, $amount, 1, TemplateMaster::SEQ_APP_CODE);
								}
								catch (Exception $e)
								{
									
								}
							}
						}
						$count++;
					}
					if ($success == true)
					{
						if ($sendCnt > 0)
						{
							$vendorIds	 = rtrim($vendors, ",");
							ServiceCallQueue::closeVendorPaymentRequest($vendorIds);
							$adminId	 = UserInfo::getUserId();
							BroadcastNotification::addCbrBroadcastNotification($vendorIds, $adminId);
						}
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
				DBUtil::commitTransaction($trans);
				$this->redirect(array('vendorcollection', 'message' => $message));
			}
			DBUtil::commitTransaction($trans);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($trans);
			throw new Exception($e->getMessage());
		}

		$this->pageTitle = "Vendor Collection";
		$model			 = new Vendors();
		$dayRange		 = 15;
		$message		 = Yii::app()->request->getParam('message');
		if ($_REQUEST['Vendors'])
		{
			$model->attributes	 = Yii::app()->request->getParam('Vendors');
			$name				 = ($model->vnd_operator != '') ? $model->vnd_operator : '';
			$dayRange			 = (Yii::app()->request->getParam('Vendors')['dayRange'] != '') ? (Yii::app()->request->getParam('Vendors')['dayRange']) : '';
			$zone				 = ($model->vnd_zone != '') ? $model->vnd_zone : '';
			$city				 = ($model->vnd_cty != '') ? $model->vnd_cty : '';
			$payableFor			 = ($model->vnd_amount_pay != '') ? $model->vnd_amount_pay : '';
			$amount				 = ($model->vnd_amount > 0) ? $model->vnd_amount : '';
			$admin				 = ($model->vnd_rm != '') ? $model->vnd_rm : '';
			$modDay				 = ($model->vnd_mod_day != '') ? $model->vnd_mod_day : '';
			$message			 = "";
		}
		$model->dayRange = $dayRange;
		$qry			 = ['name'		 => $name,
			'dayRange'	 => $dayRange,
			'zone'		 => $zone,
			'city'		 => $city,
			'payableFor' => $payableFor,
			'amount'	 => $amount,
			'admin'		 => $admin,
			'modDay'	 => $modDay];
		$dataProvider	 = Vendors::getCollectionReport($qry);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendor_collection', array('dataProvider' => $dataProvider, 'model' => $model, 'message' => $message));
	}

	public function actionCityCoverage()
	{
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
		$this->render('report_city_coverage', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionTripsinloss()
	{

		$model					 = new Booking();
		$model->bkg_pickup_date1 = date("Y-m-d", strtotime("-1 months"));
		$model->bkg_pickup_date2 = date("Y-m-d", strtotime("+36 hours"));
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $arr['bkg_pickup_date2'];
		}
		$dataProvider = BookingCab::model()->getLossTrips($model->bkg_pickup_date1, $model->bkg_pickup_date2);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('losstrips', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionFinancialReport()
	{
		$this->pageTitle = "Financial Model Actuals Report";
		$model			 = new BookingSub();
		$from_date		 = $to_date		 = '';
		if (isset($_REQUEST['BookingSub']))
		{
			$arr		 = Yii::app()->request->getParam('BookingSub');
			$from_date	 = DateTimeFormat::DatePickerToDate($arr['bkg_pickup_date1']);
			$to_date	 = DateTimeFormat::DatePickerToDate($arr['bkg_pickup_date2']);
		}
		if ($_REQUEST['export_from'] != '' && $_REQUEST['export_to'] != '')
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to'));
			$rows		 = $model->getFinancialDataByPickup('command', $fromDate, $toDate);
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"financialReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle		 = fopen("php://output", 'w');
			fputcsv($handle, ['Year - Month', 'Trips created', 'Bookings created',
				'Trips completed', 'Booking completed',
				'Trips cancelled', 'Bookings cancelled', 'Match trip completed', 'Unmatched trip completed',
				'B2B completed', 'B2C completed', 'B2BBkg Completed', 'B2CBkg Completed',
				'Matched total amount', 'Unmatched total amount', 'Total completed',
				'Matched vendor amount', 'Unmatched vendor amount', 'Vendor amount',
				'Matched gozo amount', 'Unmatched gozo amount', 'Gozo amount', 'B2C gozo amount', 'B2B gozo amount']);
			foreach ($rows as $row)
			{

				$rowArray							 = array();
				$rowArray['pickupDateRange']		 = $row['pickupDateRange'];
				$rowArray['tripCreated']			 = $row['tripCreated'];
				$rowArray['bookingCreated']			 = $row['bookingCreated'];
				$rowArray['tripCompleted']			 = $row['tripCompleted'];
				$rowArray['bookingCompleted']		 = $row['bookingCompleted'];
				$rowArray['tripCancelled']			 = $row['tripCancelled'];
				$rowArray['bookingCancelled']		 = $row['bookingCancelled'];
				$rowArray['matchTripCompleted']		 = $row['matchTripCompleted'];
				$rowArray['unmatchTripCompleted']	 = $row['unmatchTripCompleted'];
				$rowArray['B2bCompleted']			 = $row['B2bCompleted'];
				$rowArray['B2cCompleted']			 = $row['B2cCompleted'];
				$rowArray['B2BBKGCompleted']		 = $row['B2BBKGCompleted'];
				$rowArray['B2CBKGCompleted']		 = $row['B2CBKGCompleted'];
				$rowArray['matchTotalAmount']		 = $row['matchTotalAmount'];
				$rowArray['unMatchTotalAmount']		 = $row['unMatchTotalAmount'];
				$rowArray['gmv_completed']			 = $row['gmv_completed'];
				$rowArray['matchVendorAmount']		 = $row['matchVendorAmount'];
				$rowArray['unMatchVendorAmount']	 = $row['unMatchVendorAmount'];
				$rowArray['vendorAmount']			 = $row['vendorAmount'];
				$rowArray['matchGozoAmount']		 = $row['matchGozoAmount'];
				$rowArray['unmatchGozoAmount']		 = $row['unmatchGozoAmount'];
				$rowArray['gozoAmount']				 = $row['gozoAmount'];
				$rowArray['B2CgozoAmount']			 = $row['B2CgozoAmount'];
				$rowArray['B2BgozoAmount']			 = $row['B2BgozoAmount'];

				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			if (!$rows)
			{
				Yii::log("Can not export data " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
				die('Could not take data backup: ' . mysql_error());
			}
			else
			{
				Yii::log("Exported data successfully " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
			}
			exit();
		}
		$dataProvider	 = $model->getFinancialDataByPickup('', $from_date, $to_date);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$from_date		 = ($from_date != '') ? date('d/m/Y', strtotime($from_date)) : '';
		$to_date		 = ($to_date != '') ? date('d/m/Y', strtotime($to_date)) : '';
		$this->render('report_financial', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'from_date'		 => $from_date,
			'to_date'		 => $to_date)
		);
	}

	public function actionUnapprovedAssignment()
	{
		$this->pageTitle = "Unapproved Assignments Report";
		$model			 = new BookingSub();
		$from_date		 = $to_date		 = '';
		$request		 = Yii::app()->request;
		if ($request->getParam('BookingSub'))
		{
			$arr		 = $request->getParam('BookingSub');
			$from_date	 = DateTimeFormat::DatePickerToDate($arr['bkg_pickup_date1']);
			$to_date	 = DateTimeFormat::DatePickerToDate($arr['bkg_pickup_date2']);
		}
		else
		{
			$from_date	 = date("Y-m-d", strtotime("-6 month", time()));
			$to_date	 = date('Y-m-d');
		}
		if ($_REQUEST['export_from'] != '' && $_REQUEST['export_to'] != '')
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate($request->getParam('export_from'));
			$toDate		 = DateTimeFormat::DatePickerToDate($request->getParam('export_to'));
			$rows		 = $model->getUnapproveAssignmentByPickup('command', $fromDate, $toDate);
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"unapprovedAssignmentsReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle		 = fopen("php://output", 'w');
			fputcsv($handle, ['Region', 'Booking count', 'Unapproved Drivers', 'Unapproved Cars', 'Pickup Year/Month']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['region']				 = $row['region'];
				$rowArray['count_bookings']		 = $row['count_bookings'];
				$rowArray['unapproved_drivers']	 = $row['unapproved_drivers'];
				$rowArray['unapproved_cars']	 = $row['unapproved_cars'];
				$rowArray['DateRange']			 = $row['DateRange'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			if (!$rows)
			{
				Yii::log("Can not export data " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
				die('Could not take data backup: ' . mysql_error());
			}
			else
			{
				Yii::log("Exported data successfully " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
			}
			exit();
		}
		$dataProvider	 = $model->getUnapproveAssignmentByPickup('', $from_date, $to_date);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$from_date		 = ($from_date != '') ? date('d/m/Y', strtotime($from_date)) : '';
		$to_date		 = ($to_date != '') ? date('d/m/Y', strtotime($to_date)) : '';
		$this->render('report_unapprovedassignment', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'from_date'		 => $from_date,
			'to_date'		 => $to_date)
		);
	}

	public function actionCancellations()
	{
		$this->redirect(array("/report/booking/ReportCancellations"));
		exit();
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
			$model->IsGozoCancel		 = $arr['IsGozoCancel'];
			$model->IsCustomerCancel	 = $arr['IsCustomerCancel'];
			$model->sameDayCancellation	 = $arr['sameDayCancellation'];
			$model->bkg_service_class	 = $arr['bkg_service_class'];
			$model->bkgtypes			 = $arr['bkgtypes'];
			$agents->agt_id				 = $req->getParam('Agents')['agt_id'];
			$btocbooking				 = $req->getParam('btocbooking');
		}
		else if (isset($_REQUEST['export1']))
		{
			$service					 = $req->getParam('export_bkg_service_class');
			$bkgtypes					 = $req->getParam('export_bkgtypes');
			$model->bkg_create_date1	 = $req->getParam('export_bkg_create_date1');
			$model->bkg_create_date2	 = $req->getParam('export_bkg_create_date2');
			$model->bkg_region			 = $req->getParam('export_bkg_region');
			$model->bkgCancelCustomer	 = $req->getParam('export_bkgCancelCustomer');
			$model->bkgCancelAdmin		 = $req->getParam('export_bkgCancelAdmin');
			$model->bkgCancelAgent		 = $req->getParam('export_bkgCancelAgent');
			$model->bkgCancelSystem		 = $req->getParam('export_bkgCancelSystem');
			$model->searchIsDBO			 = $req->getParam('export_searchIsDBO');
			$model->IsGozoCancel		 = $req->getParam('export_IsGozoCancel');
			$model->IsCustomerCancel	 = $req->getParam('export_IsCustomerCancel');
			$model->sameDayCancellation	 = $req->getParam('export_sameDayCancellation');
			$model->bkg_service_class	 = ($service != '') ? explode(',', $req->getParam('export_bkg_service_class')) : '';
			$model->bkgtypes			 = ($bkgtypes != '') ? explode(',', $req->getParam('export_bkgtypes')) : '';
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
				$rowArray['dbo_refund_amount']		 = number_format($data['refund_amount'], 2);
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
			'btocbooking'	 => $btocbooking));
	}

	public function actionFinancial()
	{
		$this->pageTitle = "Financial Report";
		/* @var $model Booking */
		$model			 = new Booking;
		$date1			 = $date2			 = '';
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr					 = $request->getParam('Booking');
			$model->bkg_create_date1 = $arr['bkg_create_date1'];
			$model->bkg_create_date2 = $arr['bkg_create_date2'];
		}
		else
		{
			$time					 = strtotime("-6 month", time());
			$date1Year				 = date("Y-m-01", $time);
			$model->bkg_create_date1 = $date1Year;
			$model->bkg_create_date2 = date('Y-m-d');
		}
		$date1	 = $model->bkg_create_date1;
		$date2	 = $model->bkg_create_date2;

		$dataProvider1	 = BookingInvoice::model()->getFinancialReportByCreateDate($date1, $date2);
		$dataProvider2	 = BookingInvoice::model()->getFinancialReportByPickup($date1, $date2);
		$dataProvider3	 = BookingInvoice::model()->getFinancialReportPenaltyByDate($date1, $date2);
		$dataProvider4	 = VehicleStats::model()->getStickyScoreReport($date1, $date2);
		$dataProvider5	 = BookingSub::model()->getStickyBookingReport($date1, $date2);
		$this->render('financialreport_bydate',
				array(
					'data1'	 => $dataProvider1,
					'data2'	 => $dataProvider2,
					'data3'	 => $dataProvider3,
					'data4'	 => $dataProvider4,
					'data5'	 => $dataProvider5,
					'model'	 => $model,
					'params' => $arr
				)
		);
	}

	public function actionFinsummary()
	{
		$this->pageTitle = "Financial Summary Report";

		/* @var $model Booking */
		$model = new Booking;

		$date1	 = $date2	 = '';
		$params	 = [];

		$request = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr					 = $request->getParam('Booking');
			$model->bkg_create_date1 = $arr['bkg_create_date1'];
			$model->bkg_create_date2 = $arr['bkg_create_date2'];
			$model->b2cbookings		 = $arr['b2cbookings'];
			$model->mmtbookings		 = $arr['mmtbookings'];
			$model->otherAPIPartner	 = $arr['otherAPIPartner'];
			$model->nonAPIPartner	 = $arr['nonAPIPartner'];
			$model->restrictToDate	 = $arr['restrictToDate'];

			$params['b2cbookings']		 = $arr['b2cbookings'];
			$params['mmtbookings']		 = $arr['mmtbookings'];
			$params['otherAPIPartner']	 = $arr['otherAPIPartner'];
			$params['nonAPIPartner']	 = $arr['nonAPIPartner'];
			$params['restrictToDate']	 = $arr['restrictToDate'];
		}
		else
		{
			$time					 = strtotime("-6 month", time());
			$date1Year				 = date("Y-m-01", $time);
			$model->bkg_create_date1 = $date1Year;
			$model->bkg_create_date2 = date('Y-m-d');
		}
		$date1	 = $model->bkg_create_date1;
		$date2	 = $model->bkg_create_date2;

		$dataProvider2	 = BookingInvoice::model()->getFinancialSumByPickupDate($date1, $date2, $params);
		$arrPenalty		 = BookingInvoice::model()->getPenaltyArrayByDate($date1, $date2, $params);
		$this->render('report_finsummary',
				array(
					'data2'		 => $dataProvider2,
					'arrPenalty' => $arrPenalty,
					'model'		 => $model,
					'params'	 => $arr
				)
		);
	}

	/**
	 * Function for Populating Top Demand Routes
	 */
	public function actionPopulateTopDemandRoutes()
	{
		AgentApiTracking::populateTopDemandRoutes();
	}

	/**
	 * Function For Top Demand Routes
	 */
	public function actionTopDemandRoutes()
	{
		$this->pageTitle = "Top Demand Routes";
		$model			 = new AgentApiTracking();

		if (isset($_REQUEST['AgentApiTracking']))
		{
			$arr				 = Yii::app()->request->getParam('AgentApiTracking');
			$model->attributes	 = $arr;
		}

		$dataProvider = AgentApiTracking::fetchTopDemandRoutes($arr);

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$currDate	 = date("Y-m-d");
		$endDate	 = date('Y-m-d', strtotime('+10 day'));

		$period = new DatePeriod(
				new DateTime($currDate), new DateInterval('P1D'), new DateTime($endDate)
		);

		$arrDateRange = array();
		foreach ($period as $key => $value)
		{
			$arrDateRange[] = $value->format('d_M');
		}

		$this->render('top_demand_routes', array('model' => $model, 'dataProvider' => $dataProvider, 'arrDateRange' => $arrDateRange));
	}

	public function actionOtpserved()
	{
		$this->pageTitle = "OTP - Report";
		$model			 = new BookingTrack();
		$arr			 = [];
		if (isset($_REQUEST['BookingTrack']))
		{
			$arr				 = Yii::app()->request->getParam('BookingTrack');
			$model->attributes	 = $arr;
		}

		$dataProvider = $model->getServedOTPReport($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_otp_served', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function displayTable($res, $headingStr)
	{
		$rotate		 = "transform:  rotate(270deg);";
		$countRes	 = count($res);
		$str		 .= '<tr style="border-top:2px #666666 solid;">';
		$str		 .= '<th style="padding:5px; height:20px!important " rowspan="' . $countRes . '" > ' . $headingStr . ' </th>';

		foreach ($res as $k => $rowSet)
		{
			if ($k != 0)
			{
				$str .= '<tr >';
			}
			foreach ($rowSet as $data)
			{
				$str .= '<td style="padding:5px; white-space:nowrap " >';

				$str .= (is_numeric($data) == 1) ? number_format($data) : $data;
				$str .= '</td>';
			}
			$str .= '</tr>';
		}
		return $str;
	}

	public function actionDailypickup()
	{
		list($usec, $sec) = explode(" ", microtime());
		$time = ((float) $usec + (float) $sec);

		$setPass	 = "05062019";
		$chkSession	 = $_COOKIE['dailypickup'];
		$error		 = 0;
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('dailypickup', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
		}
		$this->pageTitle = "Daily Pickup Report";
		$this->render('dailypickup', ['time' => $time, 'error' => $error,]);
	}

	public function actionGetdailypickupdata()
	{
		$repId	 = Yii::app()->request->getParam('repId');
		$report	 = BookingSub::getDailyReportData($repId);
		$str	 = $this->displayTable($report['report'], $report['captionText']);
		echo $str;
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

	public function actionUnapprovedCabdriver()
	{
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
		$model->entity_type		 = $type;
		$date1					 = DateTimeFormat::DatePickerToDate($date1);
		$date2					 = DateTimeFormat::DatePickerToDate($date2);
		$dataProvider			 = BookingTrail::model()->unapprovedCabDriverUsed($date1, $date2, $usedTime, $entityType);
		$this->render('cab_driver_status', array('dataProvider' => $dataProvider, 'model' => $model, 'entity_type' => $type));
	}

	public function actionLeadTeamPerformance()
	{
		$this->pageTitle = "Lead Team Performance Report";
		$model			 = new Booking();
		$modelSub		 = new BookingSub();
		if (isset($_REQUEST['Booking']))
		{
			$arr		 = Yii::app()->request->getParam('Booking');
			$fromDate	 = $arr['bkg_from_date'];
			$toDate		 = $arr['bkg_to_date'];
		}
		else
		{
			$toDate		 = DateTimeFormat::DateToLocale(date());
			$fromDate	 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-7 days')));
		}
		$model->bkg_from_date	 = $fromDate;
		$model->bkg_to_date		 = $toDate;
		$fromDateFormated		 = DateTimeFormat::DatePickerToDate($fromDate);
		$toDateFormated			 = DateTimeFormat::DatePickerToDate($toDate);
		$countReport			 = $modelSub->findZonewiseBookingCount($fromDateFormated, $toDateFormated);
		$dataProvider			 = $modelSub->findLeadTeamPerformanceDetails($fromDateFormated, $toDateFormated);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_lead_team_performance', array('model' => $model, 'dataProvider' => $dataProvider, 'countReports' => $countReport));
	}

	public function actionDormantVendor()
	{
		$this->pageTitle = "Dormant Vendors - Report";
		$model			 = new Vendors();
		$modelPref		 = new VendorPref();
		$request		 = Yii::app()->request;
		if ($request->getParam('VendorPref') || $request->getParam('Vendors'))
		{
			$arr						 = $request->getParam('VendorPref');
			$vndarr						 = $request->getParam('Vendors');
			$modelPref->vnp_home_zone	 = $arr['vnp_home_zone'];
			$model->vnd_phone			 = $vndarr['vnd_phone'];
		}
		$dataProvider							 = VendorPref::model()->getDormantVendorReport($modelPref->vnp_home_zone, $model->vnd_phone);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('report_dormant_vendor', array('dataProvider' => $dataProvider, 'model' => $model, 'modelPref' => $modelPref));
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

	public function actionPromoReport()
	{
		$this->pageTitle		 = "Promo Report";
		$model					 = new Promos();
		$model->from_date_pickup = date('Y-m-d');
		$model->to_date_pickup	 = date('Y-m-d');
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
		if (isset($_REQUEST['from_date_pickup']) && isset($_REQUEST['to_date_pickup']))
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
			if ($fromPickupDate != '' && $toPickupDate != '')
			{
				$model->from_date_pickup = $fromPickupDate;
				$model->to_date_pickup	 = $toPickupDate;
			}
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
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
		}
		$countPromos	 = $model->getPromoWiseBookingCount();
		$dataProvider	 = $model->getPromoReportData('Command');
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('promo_report', array('model' => $model, 'dataProvider' => $dataProvider, 'countPromos' => $countPromos), false, true);
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

	/**
	 * This function is used to calculate amount that is net amount payable from vendor to G0Z0 
	 */
	public function actionVendorPayable()
	{
		$this->pageTitle = "Gozo Recievable Amount - Report";
		$dataProvider	 = Vendors::amountPayableToGozo();
		$this->render('report_payable_by_vendor', array('dataProvider' => $dataProvider));
	}

	public function actionGnowOffers()
	{
		$qry				 = Yii::app()->request->getParam('NotificationLog');
		$model				 = new NotificationLog();
//		$date1	 = $qry['ntl_date1'];
//		$date2	 = $qry['ntl_date2'];
		$model->gnowType	 = 1;
		$today				 = Filter::getDBDateTime();
		$model->ntl_date1	 = date('Y-m-d', strtotime($today));
		$model->ntl_date2	 = date('Y-m-d', strtotime($today));
		if (isset($qry))
		{
			$model->attributes = $qry;
			if (isset($qry['bkgStatus']))
			{
				$model->bkgStatus = $qry['bkgStatus'];
			}
			if (isset($qry['bkgCreateType']))
			{
				$model->bkgCreateType = $qry['bkgCreateType'];
			}
			if (isset($qry['bkgId']))
			{
				$model->bkgId = $qry['bkgId'];
			}
			if (isset($qry['vndSelected']))
			{
				$model->vndSelected = $qry['vndSelected'];
			}

			if (isset($qry['transferzSelected']))
			{
				$model->transferzSelected = $qry['transferzSelected'];
			}

			if (isset($qry['isDuplicate']))
			{
				$model->isDuplicate = $qry['isDuplicate'];
			}
		}

		$summaryData = NotificationLog::getGNowOfferSummary($model);

		$dataProvider = NotificationLog::gnowOfferReport($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('gnowOffersTrack', array('dataProvider' => $dataProvider, 'model' => $model, 'summaryData' => $summaryData), false, $outputJs);
	}

	public function actionRevenue()
	{
		$this->pageTitle = "Revenue Report";

		/* @var $model Booking */
		$model		 = new Booking;
		$fromDate	 = $toDate		 = '';
		$request	 = Yii::app()->request;
		$groupBy	 = 'month';
		if ($request->getParam('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$fromDate		 = $arr['bkg_create_date1'];
			$toDate			 = $arr['bkg_create_date2'];
			$groupBy		 = $arr['groupvar'];
			$nonAPIPartner	 = $arr['nonAPIPartner'];
			$b2cbookings	 = $arr['b2cbookings'];
			$partnerId		 = $arr['bkg_agent_id'];
			$status			 = $request->getParam('bkg_status') != "" ? implode($request->getParam('bkg_status'), ',') : '';
		}
		else
		{
			$fromDate	 = date("Y-m-1", strtotime("-3 month", time()));
			$toDate		 = date("Y-m-t", strtotime("-" . date("d") . " day", time()));
			$status		 = "2,3,5,6,7,9";
		}

		$model->bkg_create_date1 = $fromDate;
		$model->bkg_create_date2 = $toDate;
		$model->bkg_status		 = $status;
		$model->bkg_agent_id	 = $partnerId;
		$model->b2cbookings		 = $b2cbookings;
		$model->nonAPIPartner	 = $nonAPIPartner;

		$params = [
			'from_date'		 => $fromDate,
			'to_date'		 => $toDate,
			'nonAPIPartner'	 => $nonAPIPartner,
			'b2cbookings'	 => $b2cbookings,
			'bkg_agent_id'	 => $partnerId
		];

		$dataProvider = BookingInvoice::model()->getRevenueReportByDate($params, $groupBy, $status);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		skipAll:
		$this->render('revenue',
				array(
					'dataProvider'	 => $dataProvider,
					'from_date'		 => $fromDate,
					'to_date'		 => $toDate,
					'groupBy'		 => $groupBy,
					'model'			 => $model
				)
		);
	}

	public function actionrejectedVendorsOfGnowOffer()
	{
		$this->pageTitle = "Vendors who denied offer";
		$request		 = Yii::app()->request;
		if ($request->getParam('bkgId'))
		{
			$bkgId = $request->getParam('bkgId');
		}
		$model			 = Booking::model()->findByPk($bkgId);
		$dataProvider	 = BookingVendorRequest::getRejectedVendorDetails($bkgId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('rejectedVendorList',
				array(
					'dataProvider'	 => $dataProvider,
					'model'			 => $model
				)
		);
	}

	public function actionDdsbp()
	{
		$this->pageTitle = "DDSBP Report";

		$arrVhcCat = VehicleTypes::model()->getCarType();

		$model	 = new DynamicDemandSupplySurge();
		$req	 = Yii::app()->request;

		$from_date			 = date('Y-m-d');
		$to_date			 = date("Y-m-d", strtotime("+2 day", time()));
		$model->from_date	 = $from_date;
		$model->to_date		 = $to_date;
		$model->areaType	 = 7;

		if ($req->getParam('DynamicDemandSupplySurge'))
		{
			$arr = $req->getParam('DynamicDemandSupplySurge');

			$model->from_date		 = $arr['from_date'];
			$model->to_date			 = $arr['to_date'];
			$model->fromZone		 = $arr['fromZone'];
			$model->toZone			 = $arr['toZone'];
			$model->bkgTypes		 = $arr['bkgTypes'];
			$model->vehicleCategory	 = $arr['vehicleCategory'];
			$model->areaType		 = $arr['areaType'];
			$model->dds_apply_markup = isset($arr['dds_apply_markup'][0]);
			$model->activeCountDrop	 = $arr['activeCountDrop'];
			$model->activeCount		 = $arr['activeCount'];
			$model->profitCountDrop	 = $arr['profitCountDrop'];
			$model->profitCount		 = $arr['profitCount'];
			$model->lossCountDrop	 = $arr['lossCountDrop'];
			$model->lossCount		 = $arr['lossCount'];
			$model->netMarginDrop	 = $arr['netMarginDrop'];
			$model->netMarginCount	 = $arr['netMarginCount'];
			$model->markupDrop		 = $arr['markupDrop'];
			$model->markupCount		 = $arr['markupCount'];
		}

		$dataProvider = $model->getList();

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('report_ddbhp', array('model' => $model, 'dataProvider' => $dataProvider, 'arrVhcCat' => $arrVhcCat));
	}

	public function actionVendorCoins()
	{
		$this->pageTitle = "Vendor Coin Report";
		$model			 = new VendorCoins();

		$model->from_date	 = date("Y-m-01", strtotime("-3 month", time()));
		$model->to_date		 = date('Y-m-d');
		$model->groupBy		 = "month";

		$req = Yii::app()->request;
		if ($req->getParam('VendorCoins'))
		{
			$data				 = $req->getParam('VendorCoins');
			$model->from_date	 = $data['from_date'];
			$model->to_date		 = $data['to_date'];
			$model->groupBy		 = $data['groupBy'];
		}

		$dataProvider = $model->getCoinDetails();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_vendorcoins', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionChangeMarkup()
	{
		$ddsId		 = Yii::app()->getRequest()->getParam('dds_key');
		$objDynamic	 = DynamicDemandSupplySurge::model()->findByPk($ddsId);

		$objDynamic->dds_apply_markup = 1 - $objDynamic->dds_apply_markup;
		$objDynamic->save();
		$this->redirect(array('ddsbp'));
	}

	public function actionMmtEnquiry()
	{
		$orderby = 'date';

		$model			 = new Zones();
		$model->dateType = 2;
		$model->mdcDate1 = date('Y-m-d', strtotime("-1 days"));
		$model->mdcDate2 = date('Y-m-d', strtotime("-1 days"));

		$data = Yii::app()->request->getParam('Zones');
		if ($data)
		{
			$model->dateType		 = $data['dateType'];
			$model->mdcDate1		 = $data['mdcDate1'];
			$model->mdcDate2		 = $data['mdcDate2'];
			$model->sourcezone		 = $data['sourcezone'];
			$model->destinaitonzone	 = $data['destinaitonzone'];
			$model->fromcity		 = $data['fromcity'];
			$model->tocity			 = $data['tocity'];

			$bookingTypeArr = $data['bookingType'];
			if (count($data['bookingType']) > 0)
			{
				$bookingTypes		 = implode(',', $bookingTypeArr);
				$model->bookingType	 = $bookingTypes;
			}
		}

		$this->pageTitle = " MMT Enquiry";
		$dataProvider	 = $model->getMMTEnquiry();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('mmtEnquiry', array('model' => $model, 'dataType' => $dataType, 'dataProvider' => $dataProvider, 'orderby' => $orderby));
	}

}
