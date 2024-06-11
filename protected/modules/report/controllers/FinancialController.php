<?php

class FinancialController extends Controller
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
			['allow', 'actions' => ['pickup', 'pickupSummary'], 'roles' => ['pickupreport']],
			['allow', 'actions' => ['accountlist', 'ledgerList', 'PartnerMonthlyBalance'], 'users' => ['@']],
			['allow', 'actions' => ['vendorcollection'], 'roles' => ['vendorCollectionReport']],
			['allow', 'actions' => ['promoReport'], 'roles' => ['PromoReport']],
			['allow', 'actions' => ['PartnerPerformance'], 'roles' => ['chanelPartnerPerformanceReport']],
			['allow', 'actions' => ['partnercollection'], 'roles' => ['partnerReports']],
			['allow', 'actions' => ['dailyassignedreport'], 'roles' => ['vendorAssignedReport', 'DailyAssignedReport']],
			['allow', 'actions' => ['runningtotal'], 'roles' => ['RunningTotalReport']],
			['allow', 'actions' => ['vendorweekly'], 'roles' => ['VendorWeeklyReport']],
			['allow', 'actions' => ['cancellations'], 'roles' => ['CancellationsReport']],
			['allow', 'actions' => ['weekly'], 'roles' => ['WeeklyReport']],
			['allow', 'actions' => ['daily'], 'roles' => ['DailyReport']],
			['allow', 'actions' => ['autoAssign'], 'roles' => ['AutoAssignReport']],
			['allow', 'actions' => ['financial', 'finsummary'], 'roles' => ['FinancialReport']],
			['allow', 'actions' => ['money'], 'roles' => ['MoneyReport']],
			['allow', 'actions' => ['assignment'], 'roles' => ['vendorList']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('PartnerPerformance', 'business', 'Cancellation', "vendorweekly", 'financial', 'weekly', 'runningtotal', 'cancellations', 'businesstrend', 'pickup', 'money', 'dailyassignedreport', 'autoAssign', 'daily', 'vendorcollection', 'partnercollection'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('processbooking', 'dailyConfirmation', 'paymentSummaryReport', 'cancellations', 'promoReport', 'assignmentReport', "processedPayments", 'accountingFlagClosedReport', 'vendorLockedPayment', 'driverBonus', 'penaltyReport', 'assignmentSummary', 'penaltySummary', 'vendorDueSummary'),
				'roles'		 => array('GeneralReport'),
			),
			['allow', 'actions' => ['vendoraccounts'], 'roles' => ['vendorList']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionVendoraccounts()
	{
		$row = Report::getRoleAccess(1);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Today's Accounting Action List";

		$model		 = new Booking();
		$venModel	 = new PaymentGateway();
		$pageSize	 = '36';
		$setFlag	 = '1';
		$recordSet	 = $model->accountReportByFlag($setFlag);
		$bookList	 = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => $pageSize),));
		$bookModels	 = $bookList->getData();

		$dataProvider							 = AccountTransDetails::vendorCollectionReport();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$venModel->ven_date_type = '1';
		$add_days				 = 7;
		$dateFromDate			 = (date('Y-m-d', strtotime('Today') - (24 * 3600 * $add_days)));
		$dateTodate				 = date('Y-m-d', strtotime('Today'));

		$venModel->ven_from_date = DateTimeFormat::DateToLocale($dateFromDate);
		$venModel->ven_to_date	 = DateTimeFormat::DateToLocale($dateTodate);
		$venModel->scenario		 = 'transaction_search';
		if (isset($_REQUEST['PaymentGateway']))
		{
			$venModel->attributes = $_REQUEST['PaymentGateway'];
			if ($venModel->validate())
			{
				$submit = trim($_POST['submit']);
				if ($submit == "1")
				{
					$this->forward('vendor/ledgerpdf');
				}
				if ($submit == "2")
				{
					$this->forward('vendor/listvendoraccount');
				}
			}
			else
			{
				
			}
		}
		$this->render('report_vendoraccounts', array('model'			 => $model,
			'venModel'		 => $venModel,
			'bookModels'	 => $bookModels,
			'bookingList'	 => $bookList,
			'dataProvider'	 => $dataProvider,
			'dateFromDate'	 => $dateFromDate,
			'dateTodate'	 => $dateTodate, 'roles'			 => $row));
	}

	public function actionAccountList()
	{
		$row = Report::getRoleAccess(2);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$ledgerId			 = Yii::app()->request->getParam("ledgerId");
			$model->trans_date1	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('trans_date1'));
			$model->trans_date2	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('trans_date2'));

			$date1	 = $model->trans_date1;
			$date2	 = $model->trans_date2;

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AccountList_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "AccountList_" . date('Ymdhis') . ".csv";
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
			$rows	 = AccountTransDetails::getdriverAccountTransactionsList($date1, $date2, $ledgerId, '', 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Driver Name', 'Phone', 'Opening Balance', 'Debit', 'Credit', 'Closing Balance']);
			foreach ($rows as $row)
			{
				$name = '';
				if (in_array($row["ledgerId"], [40]))
				{
					$name = $row['name'];
				}
				$rowArray			 = array();
				$rowArray['name']	 = $name;
				$rowArray['phone']	 = $row['phone'];
				$rowArray['opening'] = $row['opening'];
				$rowArray['debit']	 = $row['debit'];
				$rowArray['credit']	 = $row['credit'];
				$rowArray['closing'] = $row['closing'];
				$row1				 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = AccountTransDetails::getdriverAccountTransactionsList($date1, $date2, $ledgerId);
		$this->render('accountlist', array('dataProvider' => $dataProvider, 'ledgerId' => $ledgerId, 'model' => $model, 'roles' => $row));
	}

	public function actionAssignment()
	{
		$row = Report::getRoleAccess(11);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Assignment Report";
		$model			 = new Vendors();

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"Assignment_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "Assignment_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->getVendorAssignmentReport('command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Name', 'System Assigned Bookings (Lifetime)', 'System Assigned Bookings (Last 30days)',
				'Manual Assigned Bookings (Lifetime)', 'Manual Assigned Bookings (Last 30days)',
				'Last App Login Date/Time']);
			foreach ($rows as $row)
			{
				$rowArray								 = array();
				$rowArray['vnd_name']					 = $row['vnd_name'];
				$rowArray['system_assigned_bookings']	 = $row['system_assigned_bookings'];
				$rowArray['system_assigned_bookings_30'] = $row['system_assigned_bookings_30'];
				$rowArray['manual_assigned_bookings']	 = $row['manual_assigned_bookings'];
				$rowArray['manual_assigned_bookings_30'] = $row['manual_assigned_bookings_30'];
				$rowArray['last_login']					 = $row['last_login'];
				$row1									 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getVendorAssignmentReport();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_assignment', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionBusiness()
	{
		$row = Report::getRoleAccess(19);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Business Report";
		$model			 = new Booking();
		/* @var $modelsub BookingSub */
		$modelsub		 = new BookingSub();
		$trips_booked	 = $modelsub->businessBookingReport();
		$nps			 = $modelsub->businessNps();
		$this->render('business', ['trips_booked' => $trips_booked, 'nps' => $nps, 'modelsub' => $modelsub, 'roles' => $row]);
	}

	public function actionBusinesstrend()
	{
		$row = Report::getRoleAccess(21);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

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
		$this->render('businesstrend', ['gmv' => $gmv, 'advance_payment' => $advance_payment, 'trips_booked' => $trips_booked, 'cancellations' => $cancellations, 'trips_complete' => $trips_complete, 'reviews' => $reviews, 'nps' => $nps, 'roles' => $row]);
	}

	public function actionPickup()
	{
		$row = Report::getRoleAccess(25);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
			$serviceTire	 = $arr['bkg_service_class'];
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
			$serviceTire = [];
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
		$model->bkg_service_class		 = $serviceTire;

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
			$serviceTire			 = Yii::app()->request->getParam('export_bkg_service_class');
			$serviceTire			 = (!empty($serviceTire[0])) ? $serviceTire : [];
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
			$rows			 = $modelsub->pickupReport($fromDate, $toDate, $fromCity, $toCity, $bkgVendor, $bkgPlatform, $bkgStatus, $bkgType, $type, $serviceTire);
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
				'Service Tire',
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
				'Partner Extra Commission',
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
				$referenceCode = $row['bkg_agent_ref_code'];
				if ($row['bkg_agent_id'] == Config::get('transferz.partner.id') && is_numeric($row['bkg_agent_ref_code']))
				{
					$partnerCode	 = TransferzOffers::getOffer($row['bkg_agent_ref_code']);
					$referenceCode	 = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode;
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
				$rowArray['bkg_service_class']			 = $row['serviceTire'];
				$rowArray['agent_name']					 = $row['agent_name'];
				$rowArray['bkg_agent_ref_code']			 = $referenceCode;
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
				$rowArray['bkg_partner_extra_commission'] = $row['bkg_partner_extra_commission'];
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
			$dataProvider	 = $modelsub->pickupReport($date1, $date2, $from, $to, $vendor, $platform, $status, $bkgType, $type			 = 'data', $serviceTire);
			$reportData		 = $model->pickupReportData($date1, $date2, $from, $to, $vendor, $platform, $bkgType);
		}
		$this->render('report_pickup', array('dataProvider' => $dataProvider, 'model' => $model, 'trailModel' => new BookingTrail(), 'reportData' => $reportData, 'roles' => $row));
	}

	public function actionPickupSummary()
	{
		$row = Report::getRoleAccess(25);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Pickup Summary Report";
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
				'Partner Extra Commission',
				'Consumer Name',
				'Trip Vendor Amount',
				'From City',
				'To City',
				'Vendor Name',
				'Driver Name',
				'Vehicle No',
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
				$rowArray['bkg_partner_extra_commission'] = $row['bkg_partner_extra_commission'];
				$rowArray['bkg_user_name']				 = $row['bkg_user_fname'] . " " . $row['bkg_user_lname'];
				$rowArray['bcb_vendor_amount']			 = $row['bcb_vendor_amount'];
				$rowArray['from_city']					 = $row['fromCity'];
				$rowArray['to_city']					 = $row['toCity'];
				$rowArray['vendor_name']				 = $row['vendor_name'];
				$rowArray['driver_name']				 = $row['driver_name'];
				$rowArray['vhc_number']					 = $row['vhc_number'];
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
		$this->render('report_pickup_summary', array('dataProvider' => $dataProvider, 'model' => $model, 'trailModel' => new BookingTrail()));
	}

	public function actionMoney()
	{
		$row = Report::getRoleAccess(26);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('report_money', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionDailyAssignedReport()
	{
		$row = Report::getRoleAccess(27);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->blg_created1 = Yii::app()->request->getParam('blg_created1');
			$model->blg_created2 = Yii::app()->request->getParam('blg_created2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DailyAssignedReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DailyAssignedReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->getDailyAssignedCount(DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['First Name', 'Last Name', 'Vendor Assigned', 'Vendors']);
			foreach ($rows as $row)
			{
				$vals		 = array_count_values(explode(',', $row['vendor_name']));
				$strvendors	 = "";
				foreach ($vals as $key => $value)
				{
					$strvendors = $strvendors . " " . $key . "(" . $value . " times)";
				}
				$rowArray					 = array();
				$rowArray['adm_fname']		 = $row['adm_fname'];
				$rowArray['adm_lname']		 = $row['adm_lname'];
				$rowArray['total_assigned']	 = $row['total_assigned'];
				$rowArray['vendor_name']	 = $strvendors;
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getDailyAssignedCount();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('report_assigned', ['model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row]);
	}

	public function actionAutoAssign()
	{
		$row = Report::getRoleAccess(27);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

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
			$model->is_advance_amount	 = $is_amt						 = $param['is_advance_amount']	 = $_REQUEST['is_advance_amount'];
			$model->is_dbo_applicable	 = $is_dbo						 = $param['is_dbo_applicable']	 = $_REQUEST['is_dbo_applicable'];
			$model->is_reconfirm_flag	 = $is_reconfirm				 = $param['is_reconfirm_flag']	 = $_REQUEST['is_reconfirm_flag'];
			$model->is_New				 = $is_new						 = $param['is_New']			 = $_REQUEST['is_New'];
			$model->is_Assigned			 = $is_assigned				 = $param['is_Assigned']		 = $_REQUEST['is_Assigned'];
			$model->is_Manual			 = $is_manual					 = $param['is_Manual']			 = $_REQUEST['is_Manual'];
		}
		else
		{
			$model->is_reconfirm_flag	 = $is_reconfirm				 = 1;
			$model->is_Assigned			 = $is_assigned				 = 1;
			$model->is_advance_amount	 = $is_amt						 = 1;
		}


		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
		{
			$arr['bkg_create_date1']		 = Yii::app()->request->getParam('bkg_create_date1');
			$arr['bkg_create_date2']		 = Yii::app()->request->getParam('bkg_create_date2');
			$arr['bkg_pickup_date1']		 = Yii::app()->request->getParam('bkg_pickup_date1');
			$arr['bkg_pickup_date2']		 = Yii::app()->request->getParam('bkg_pickup_date2');
			$arr['tripAssignmnetFromTime']	 = Yii::app()->request->getParam('tripAssignmnetFromTime');
			$arr['tripAssignmnetToTime']	 = Yii::app()->request->getParam('tripAssignmnetToTime');
			$arr['bkg_service_class']		 = Yii::app()->request->getParam('bkg_service_class');
			$arr['is_advance_amount']		 = Yii::app()->request->getParam('is_advance_amount');
			$arr['is_dbo_applicable']		 = Yii::app()->request->getParam('is_dbo_applicable');
			$arr['is_reconfirm_flag']		 = Yii::app()->request->getParam('is_reconfirm_flag');
			$arr['is_New']					 = Yii::app()->request->getParam('is_New');
			$arr['is_Assigned']				 = Yii::app()->request->getParam('is_Assigned');
			$arr['is_Manual']				 = Yii::app()->request->getParam('is_Manual');

			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				$jsonArr	 = array(
					"params" => $arr,
					"keys"	 => array
						(
						'Trip ID',
						'Booking ID',
						'Company',
						'Pick Up Date',
						'Create Date',
						'ReConfirm Flag',
						'In Round1',
						'Double Back Flag',
						'Double Back Amount',
						'Criticality Score',
						'DemSup Missfire',
						'In Round2',
						'In Round3',
						'Advanced Amount',
						'Count/Avg/Max/Min (Bids)',
						'Booking VA',
						'Trip VA',
						'Gozo Amount/Gozo Percentage',
						'Max Allowable VA',
						'Assignment Datetime'
					)
				);
				$filename	 = "autoAssign_" . date('YmdHis') . ".csv";
				$expiryDate	 = Date('Y-m-d', strtotime('+15 days'));
				ReportExport::CreateRequest($jsonArr, 27, $filename, $expiryDate, 1, UserInfo::getUserId());
			}
		}



		$dataProvider							 = BookingSub::model()->autoAssignReport($model, $param);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('auto_assignment', array('dataProvider' => $dataProvider, 'model' => $model, 'fromDateFormated' => $cdate1, 'toDateFormated' => $cdate2, 'roles' => $row), false, $outputJs);
	}

	public function actionDaily()
	{
		$row = Report::getRoleAccess(29);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

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

		$this->render('report_daily', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport, 'roles' => $row));
	}

	public function actionVendorcollection()
	{
		$row = Report::getRoleAccess(31);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
				'Trips', 'Rating', 'Contact Id', 'Number of contact', 'BankDetails_Lastmodified', 'Last login date', 'Home zone', 'Dependency score']);
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
					$rowArray['apt_last_login']				 = $row['apt_last_login'];
					$rowArray['cty_name']					 = $row['cty_name'];
					$rowArray['vrs_dependency']				 = $row['vrs_dependency'];

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
		try
		{
			if (isset($_POST["import"]))
			{
				$trans		 = DBUtil::beginTransaction();
				$message	 = "";
				$success	 = false;
				$fileName	 = $_FILES["file"]["tmp_name"];
				if ($_FILES["file"]["size"] > 0)
				{
					$file	 = fopen($fileName, "r");
					$i		 = 0;
					$j		 = 0;
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
								if ($success == true)
								{
									$i++;
								}
								$j++;
							}
							if ($amount > 0)
							{
								try
								{
									$vendors .= $operatorId . ",";
									$sendCnt++;
									//$message	 = "Gozo has released payment of Rs. " . $amount . " to you today. It normally takes between 2-24 hours for you to receive the amount in your bank account";
									//$payLoadData = ['vendorId' => $operatorId, 'EventCode' => Booking::CODE_VENDOR_BROADCAST];
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
						$totalRows		 = $i;
						$totalRecords	 = $j;
						$type			 = "success";
						$message		 = $totalRows . "&nbsp; CSV Data Imported into the Database out of " . $totalRecords;
					}
				}
				else
				{
					$type	 = "error";
					$message = "Problem in Importing CSV Data.";
					throw new Exception("Problem in Importing CSV Data ");
				}
				DBUtil::commitTransaction($trans);
				$this->redirect(array('vendorcollection', 'message' => $message));
			}
			elseif (isset($_POST["updateBeneficiary"]))
			{
				$trans		 = DBUtil::beginTransaction();
				$messageBen	 = "";
				$success	 = false;
				$fileName	 = $_FILES["fileBeneficiary"]["tmp_name"];
				if ($_FILES["fileBeneficiary"]["size"] > 0)
				{
					$file	 = fopen($fileName, "r");
					$i		 = 0;
					$j		 = 0;
					$count	 = 0;
					$vendors = "";
					$sendCnt = 0;
					while (($getData = fgetcsv($file, 10000, ",")))
					{
						if ($count > 0)
						{
							$vndId			 = trim($getData[0]);
							$contactId		 = trim($getData[1]);
							$beneficiaryId	 = trim($getData[2]);
							if ($vndId != '' && $contactId != '' && $beneficiaryId != '')
							{
								$rowContact = Vendors::checkVendorContact($vndId, $contactId);
								if ($rowContact)
								{
									$sqlUpdContact	 = "UPDATE contact SET ctt_beneficiary_id = '{$beneficiaryId}' WHERE ctt_id = {$contactId}";
									$success		 = DBUtil::execute($sqlUpdContact);
									if ($success == true)
									{
										$i++;
									}
								}
								$j++;
							}
						}
						$count++;
					}

					$totalRows		 = $i;
					$totalRecords	 = $j;
					$type			 = "success";
					$messageBen		 = $totalRows . "&nbsp; Beneficiary data updated into the Database out of " . $totalRecords;
				}
				else
				{
					$type		 = "error";
					$messageBen	 = "Problem in Updating Beneficiary CSV Data.";
					throw new Exception("Problem in Updating Beneficiary CSV Data ");
				}
				DBUtil::commitTransaction($trans);
				$this->redirect(array('vendorcollection', 'messageBen' => $messageBen));
			}
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
		$messageBen		 = Yii::app()->request->getParam('messageBen');
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
			$messageBen			 = "";
		}
		$model->dayRange = $dayRange;
		$qry			 = ['name'		 => $name,
			'dayRange'	 => $dayRange,
			'zone'		 => $zone,
			'city'		 => $city,
			'payableFor' => $payableFor,
			'amount'	 => $amount,
			'admin'		 => $admin,
			'modDay'	 => $modDay, 'roles'		 => $row];
		$dataProvider	 = Vendors::getCollectionReport($qry);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendor_collection', array('dataProvider' => $dataProvider, 'model' => $model, 'message' => $message, 'messageBen' => $messageBen, 'roles' => $row));
	}

	public function actionPartnercollection()
	{
		$row = Report::getRoleAccess(32);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

		if (isset($_REQUEST['agt_id']))
		{
			$partnerid = Yii::app()->request->getParam('agt_id');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"Partnercollection_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "Partnercollection_" . date('YmdHi') . ".csv";
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
				$rows	 = Agents::getCollectionReport($partnerid, $sort);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, [
					'Partner Name',
					'Receivable',
					'Payable',
					'Wallet Balance'
				]);
				foreach ($rows as $row)
				{
					$rowArray					 = array();
					$rowArray['bkg_agent_id']	 = Agents::model()->findByPk($row['agt_id'])->agt_company;
					$rowArray['Receivable']		 = $row['Receivable'];
					$rowArray['Payable']		 = $row['Payable'];
					$rowArray['WalletBalance']	 = $row['WalletBalance'];
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$model->agt_id	 = $partnerid;
		$dataProvider	 = Agents::getCollectionReport($partnerid, $sort);
		$totalCollection = Agents::getTotalCollectionReport($partnerid);
		$this->render('report_partner_collection', array('model' => $model, 'dataProvider' => $dataProvider, 'totalCollection' => $totalCollection, 'roles' => $row));
	}

	public function actionCancellations()
	{
		echo "This report is moved to <a href='/report/booking/ReportCancellations'>Cancellation Report</a>";
		exit;
//		$row = Report::getRoleAccess(35);
//		if ($row['rpt_roles'] != null)
//		{
//			$roleAccess = Filter::checkACL($row['rpt_roles']);
//			if (!$roleAccess)
//			{
//				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
//			}
//		}
//		$this->pageTitle	 = "Cancellation Report";
//		/* @var $model Booking */
//		$model				 = new Booking;
//		$agents				 = new Agents();
//		$date1				 = $date2				 = $bkgRegion			 = '';
//		$bkgCancelCustomer	 = $bkgCancelAdmin		 = $bkgCancelAgent		 = $bkgCancelSystem	 = 0;
//		$sameDayCancellation = 1;
//		$req				 = Yii::app()->request;
//		$submodel			 = new BookingSub();
//		if ($req->getParam('Booking'))
//		{
//			$arr						 = $req->getParam('Booking');
//			$model->bkg_create_date1	 = $arr['bkg_create_date1'];
//			$model->bkg_create_date2	 = $arr['bkg_create_date2'];
//			$model->bkg_region			 = $arr['bkg_region'];
//			$model->bkgCancelCustomer	 = $arr['bkgCancelCustomer'];
//			$model->bkgCancelAdmin		 = $arr['bkgCancelAdmin'];
//			$model->bkgCancelAgent		 = $arr['bkgCancelAgent'];
//			$model->bkgCancelSystem		 = $arr['bkgCancelSystem'];
//			$model->searchIsDBO			 = $arr['searchIsDBO'];
//			$model->IsGozoCancel		 = $arr['IsGozoCancel'];
//			$model->IsCustomerCancel	 = $arr['IsCustomerCancel'];
//			$model->sameDayCancellation	 = $arr['sameDayCancellation'];
//			$model->bkg_service_class	 = $arr['bkg_service_class'];
//			$agents->agt_id				 = $req->getParam('Agents')['agt_id'];
//			$btocbooking				 = $req->getParam('btocbooking');
//		}
//		else if (isset($_REQUEST['export1']))
//		{
//			$service					 = $req->getParam('export_bkg_service_class');
//			$model->bkg_create_date1	 = $req->getParam('export_bkg_create_date1');
//			$model->bkg_create_date2	 = $req->getParam('export_bkg_create_date2');
//			$model->bkg_region			 = $req->getParam('export_bkg_region');
//			$model->bkgCancelCustomer	 = $req->getParam('export_bkgCancelCustomer');
//			$model->bkgCancelAdmin		 = $req->getParam('export_bkgCancelAdmin');
//			$model->bkgCancelAgent		 = $req->getParam('export_bkgCancelAgent');
//			$model->bkgCancelSystem		 = $req->getParam('export_bkgCancelSystem');
//			$model->searchIsDBO			 = $req->getParam('export_searchIsDBO');
//			$model->IsGozoCancel		 = $req->getParam('export_IsGozoCancel');
//			$model->IsCustomerCancel	 = $req->getParam('export_IsCustomerCancel');
//			$model->sameDayCancellation	 = $req->getParam('export_sameDayCancellation');
//			$model->bkg_service_class	 = ($service != '') ? explode(',', $req->getParam('export_bkg_service_class')) : '';
//			$agents->agt_id				 = $req->getParam('export_agt_id');
//			$btocbooking				 = $req->getParam('export_btocbooking');
//			header('Content-type: text/csv');
//			header("Content-Disposition: attachment; filename=\"Cancellations_" . date('Ymdhis') . ".csv\"");
//			header("Pragma: no-cache");
//			header("Expires: 0");
//			$filename					 = "Cancellations_" . date('YmdHi') . ".csv";
//			$foldername					 = Yii::app()->params['uploadPath'];
//			$backup_file				 = $foldername . DIRECTORY_SEPARATOR . $filename;
//			if (!is_dir($foldername))
//			{
//				mkdir($foldername);
//			}
//			if (file_exists($backup_file))
//			{
//				unlink($backup_file);
//			}
//			$rows	 = $submodel->getCancellationList($model, DBUtil::ReturnType_Query, $agents, $btocbooking,);
//			$handle	 = fopen("php://output", 'w');
//			fputcsv($handle, ['Region', 'Booking ID', 'Partner Ref Code', 'Booking Type', 'Partner Type', 'Booking Route', 'Booking Date/Time', 'Pickup Date/Time', 'Working Hour', 'Arrival Date/Time', 'Cancellation Date/Time', 'Cancel Reason', 'Cancel Description', 'Cancellation Charge', 'Amount', 'DBO Status', 'DBO refund amount', 'Cancel By']);
//			foreach ($rows as $data)
//			{
//				if ($data["bkg_agent_id"] != NULL)
//				{
//					$partnerType = ($data['agt_company'] != NULL || $data['agt_company'] != '') ? $data['agt_company'] : $data['agent_name'];
//				}
//				else
//				{
//					$partnerType = 'B2C';
//				}
//				$rowArray							 = array();
//				$rowArray['Region']					 = States::findUniqueZone($data['stt_zone']);
//				$rowArray['booking_id']				 = $data['bkg_booking_id'];
//				$rowArray['partner_ref_code']		 = $data['bkg_agent_ref_code'];
//				$rowArray['booking_type']			 = Booking::model()->getBookingType($data['bkg_booking_type']);
//				$rowArray['partner_type']			 = $partnerType;
//				$rowArray['booking_route']			 = $data['booking_route'];
//				$rowArray['booking_date_time']		 = date("d-m-Y H:i:s", strtotime($data['bkg_create_date']));
//				$rowArray['pickup_date_time']		 = date("d-m-Y H:i:s", strtotime($data['bkg_pickup_date']));
//				$fromDate							 = $data['bkg_create_date'];
//				$toDate								 = $data['bkg_pickup_date'];
//				$rowArray['workingHour']			 = DBUtil::CalcWorkingHour($fromDate, $toDate);
//				$rowArray['arrival_date_time']		 = ($data['arrive_time'] != null ? $data['arrive_time'] : '');
//				$rowArray['cancellation_date_time']	 = date("d-m-Y H:i:s", strtotime($data['btr_cancel_date']));
//				$rowArray['cancel_reason']			 = $data['cnr_reason'];
//				$rowArray['cancel_description']		 = $data['bkg_cancel_delete_reason'];
//				$rowArray['cancellation_charge']	 = number_format($data['bkg_cancel_charge'], 2);
//				$rowArray['amount']					 = number_format($data['bkg_total_amount'], 2);
//				$rowArray['dbo_status']				 = $data['is_dbo'];
//				$rowArray['dbo_refund_amount']		 = number_format($data['refund_amount'], 2);
//				$rowArray['cancel_by']				 = $data['cancelBy'];
//
//				$row1 = array_values($rowArray);
//				fputcsv($handle, $row1);
//			}
//			fclose($handle);
//			exit;
//		}
//		else
//		{
//			$model->bkg_create_date1	 = empty($req->getParam('bkg_create_date1')) ? date("Y-m-d") : $req->getParam("bkg_create_date1");
//			$model->bkg_create_date2	 = empty($req->getParam('bkg_create_date2')) ? date("Y-m-d") : $req->getParam("bkg_create_date2");
//			$model->IsGozoCancel		 = empty($req->getParam('IsGozoCancel')) || $req->getParam('IsGozoCancel') == 0 ? 0 : 1;
//			$model->sameDayCancellation	 = 0;
//			$btocbooking				 = 0;
//			$agents->agt_id				 = '';
//		}
//
//		/* @var $submodel BookingSub */
//
//		$dataProvider = $submodel->getCancellationList($model, DBUtil::ReturnType_Provider, $agents, $btocbooking);
//		$this->render('cancellation_bydate', array(
//			'dataProvider'	 => $dataProvider[0],
//			'model'			 => $model,
//			'summary'		 => $dataProvider[1],
//			'params'		 => $arr,
//			'agents'		 => $agents,
//			'btocbooking'	 => $btocbooking, 'roles'			 => $row));
	}

	public function actionVendorweekly()
	{
		$row = Report::getRoleAccess(39);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('report_vendor_weekly', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport, 'roles' => $row));
	}

	public function actionWeekly()
	{
		$row = Report::getRoleAccess(40);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('report_weekly', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport, 'roles' => $row));
	}

	public function actionRunningtotal()
	{
		$row = Report::getRoleAccess(41);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('report_running_total', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionFinancial()
	{
		$row = Report::getRoleAccess(44);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
					'params' => $arr, 'roles'	 => $row
				)
		);
	}

	public function actionPartnerPerformance()
	{
		$row = Report::getRoleAccess(45);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Channel Partner Performance - Report";
		$model			 = new Booking();
		$aModel			 = new Agents();

		$model->bkg_pickup_date1 = $date1					 = date("Y-m-d", strtotime("-30 day"));
		$model->bkg_pickup_date2 = $date2					 = date("Y-m-d");

		$appliedFilters = "";
		if (isset($_REQUEST['Booking']))
		{
			$arr = Yii::app()->request->getParam('Booking');
			if (count($arr) > 0 && ($arr['bkg_pickup_date1'] != '' || $arr['bkg_pickup_date2'] != '' || $arr['bkg_drv_app_filter']))
			{
				$model->bkg_pickup_date1	 = $date1						 = $arr['bkg_pickup_date1'];
				$model->bkg_pickup_date2	 = $date2						 = $arr['bkg_pickup_date2'];
				$model->bkg_drv_app_filter	 = $filter						 = $arr['bkg_drv_app_filter'];

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
			}
		}
		if (isset($_REQUEST['Agents']))
		{
			$arr1 = Yii::app()->request->getParam('Agents');
			if (count($arr1) > 0)
			{
				$agtType = implode(',', $arr1['agt_type']);

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
			}
		}

		$appliedFilters = substr($appliedFilters, 0, strlen($appliedFilters) - 4);

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
		$this->render('partner_performance', array('model' => $model, 'amodel' => $aModel, 'agt_type' => $agtType, 'appliedFilters' => $appliedFilters, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	/**
	 * Accounting Flag Closed By Admin Report
	 */
	public function actionAccountingFlagClosedReport()
	{
		$row = Report::getRoleAccess(46);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('accountingFlagClosed', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	/**
	 * Vendor Locked Payment Report
	 */
	public function actionVendorLockedPayment()
	{
		$row = Report::getRoleAccess(47);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('vendorLockedPayment', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	/**
	 * Driver Bonus Amount
	 */
	public function actionDriverBonus()
	{
		$row = Report::getRoleAccess(48);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('driverBonus', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionPenaltyReport()
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
		$this->pageTitle			 = "Penalty Report";
		$model						 = new AccountTransDetails();
		$command					 = false;
		$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('-1 day'));
		$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('-1 day'));
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
			$model->bkg_id			 = $_REQUEST['AccountTransDetails']['bkg_id'];
			$model->vendor_id		 = $_REQUEST['AccountTransDetails']['vendor_id'];
			$model->penalty_status	 = $_REQUEST['AccountTransDetails']['penalty_status'];
		}

		$dataProvider = AccountTransDetails::model()->getPenaltyReport($command, $transDate1, $transDate2, $model->bkg_id, $model->vendor_id, $removalDate1, $removalDate2, $model->penalty_status);

		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$transDate1				 = ($_REQUEST['export_trans_create_date1'] != '') ? $_REQUEST['export_trans_create_date1'] : '';
			$transDate2				 = ($_REQUEST['export_trans_create_date2'] != '') ? $_REQUEST['export_trans_create_date2'] : '';
			$removalDate1			 = ($_REQUEST['export_trans_remove_date1'] != '') ? $_REQUEST['export_trans_remove_date1'] : '';
			$removalDate2			 = ($_REQUEST['export_trans_remove_date2'] != '') ? $_REQUEST['export_trans_remove_date2'] : '';
			$model->bkg_id			 = ($_REQUEST['export_bkg_id'] != '') ? $_REQUEST['export_bkg_id'] : '';
			$model->vendor_id		 = ($_REQUEST['export_vendor_id'] != '') ? $_REQUEST['export_vendor_id'] : '';
			$model->penalty_status	 = ($_REQUEST['export_penalty_status'] != '') ? $_REQUEST['export_penalty_status'] : '';
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
			$rows	 = AccountTransDetails::model()->getPenaltyReport($command, $transDate1, $transDate2, $model->bkg_id, $model->vendor_id, $removalDate1, $removalDate2, $model->penalty_status);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Transaction Date', 'Create Date', 'Transaction Type', 'Transaction Status', 'Ledger entry', 'BKG ID', 'Booking ID', 'Agent Booking ID', 'Comments', 'Applied by', 'Penalty Removal date',
				'Removal Comments', 'Removed by']);
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$ptype		 = CJSON::decode($row['adt_addt_params']);
					$penaltyType = $ptype['penaltyType'];
					$pModel		 = PenaltyRules::getValueByPenaltyType($penaltyType);
					if ($row['act_active'] == 1 && $row['ledgerAmt'] > 0)
					{
						if ($row['adm_id'] != '')
						{
							$appliedby = $row['adm_fname'] . '' . $row['adm_lname'];
						}
						else if ($row['adm_id1'] != '')
						{
							$appliedby = $row['adm_fname1'] . '' . $row['adm_lname1'];
						}
						else
						{
							$appliedby = "System";
						}
					}
					if ($row['act_active'] == 0 || ($row['act_active'] == 1 && $row['ledgerAmt'] < 0))
					{
						if ($row['adm_id'] != '')
						{
							$removedby = $row['adm_fname'] . '' . $row['adm_lname'];
						}
						else if ($row['adm_id1'] != '')
						{
							$removedby = $row['adm_fname1'] . '' . $row['adm_lname1'];
						}
						else if ($row['act_user_type'] == 2)
						{
							$removedby = "Vendor";
						}
						else
						{
							$removedby = "System";
						}
					}
					if ($row['act_active'] == 0 || ($row['act_active'] == 1 && $row['ledgerAmt'] < 0))
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
					$rowArray['act_active']			 = ($row['act_active'] == 1 && $row['ledgerAmt'] > 0) ? 'Applied' : 'Removed';
					$rowArray['adt_amount']			 = ($row['act_active'] == 1) ? $row['ledgerAmt'] : $row['ledgerAmt1'];
					$rowArray['adt_trans_ref_id']	 = $row['adt_trans_ref_id'];
					$bookingModel					 = Booking::model()->findByPk($row['adt_trans_ref_id']);
					$rowArray['bkg_booking_id']		 = $bookingModel->bkg_booking_id;
					$rowArray['bkg_agent_ref_code']	 = $bookingModel->bkg_agent_ref_code;
					$rowArray['act_remarks']		 = ($row['act_active'] == 1 && $row['ledgerAmt'] > 0) ? preg_replace('/[^a-zA-Z0-9_ -]/s', '', $row['adt_remarks']) : '';
					$rowArray['act_user_id']		 = $appliedby;
					$rowArray['adt_modified']		 = ($row['act_active'] == 0 || ($row['act_active'] == 1 && $row['ledgerAmt'] < 0)) ? $row['adt_modified'] : '';
					$rowArray['adt_remarks']		 = ($row['act_active'] == 0 || ($row['act_active'] == 1 && $row['ledgerAmt'] < 0)) ? $removalComments : '';
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
		$this->render('penaltyReport', array('dataProvider' => $dataProvider, 'model' => $model, 'message' => $message, 'roles' => $row));
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
			$arr				 = $request->getParam('BookingSub');
			$from_date			 = $arr['bkg_pickup_date1'];
			$to_date			 = $arr['bkg_pickup_date2'];
			$fromCreateDate		 = $arr['bkg_create_date1'];
			$toCreateDate		 = $arr['bkg_create_date2'];
			$orderby			 = $arr['groupvar'];
			$partnerId			 = $arr['bkg_agent_id'];
			$bkgType			 = $arr['bkgtypes'];
			$gnowType			 = $arr['gnowType'];
			$nonProfitable		 = $arr['nonProfitable'];
			$excludeAT			 = isset($arr['excludeAT'][0]);
			$includeB2c			 = isset($arr['b2cbookings'][0]);
			$mmtbookings		 = $arr['mmtbookings'];
			$nonAPIPartner		 = $arr['nonAPIPartner'];
			$weekDays			 = $arr['weekDays'];
			$zones				 = $arr['sourcezone'];
			$region				 = $arr['region'];
			$state				 = $arr['state'];
			$assignedFrom		 = $arr['from_date'];
			$assignedTo			 = $arr['to_date'];
			$local				 = $arr['local'];
			$outstation			 = $arr['outstation'];
			$bkg_vehicle_type_id = $arr['bkg_vehicle_type_id'];
			$assignMode			 = $arr['assignMode'];
			$manualAssignment	 = $arr['manualAssignment'];
			$criticalAssignment	 = $arr['criticalAssignment'];

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
				'criticalAssignment' => $criticalAssignment
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

	public function actionPenaltySummary()
	{
		$row = Report::getRoleAccess(51);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

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
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->from_date	 = Yii::app()->request->getParam('from_date');
			$model->to_date		 = Yii::app()->request->getParam('to_date');
			$orderby			 = Yii::app()->request->getParam('groupvar');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"PenaltySummary_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "PenaltySummary_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->getPenaltySummary($orderby, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [ucfirst($orderby), 'Total Penalty', 'App', 'No Show', 'Not Allocated', 'Late', 'Unassign', 'Unregistered Cab']);
			foreach ($rows as $row)
			{

				switch ($row['groupType'])
				{
					case 'date':
						$date	 = $row['date'];
						break;
					case 'week':
						$date	 = $row['weekLabel'];
						break;
					case 'month':
						$date	 = $row['monthname'];
						break;
					default:
						break;
				}
				$rowArray					 = array();
				$rowArray['date']			 = $date;
				$rowArray['totalPenalty']	 = $row['totalPenalty'];
				$rowArray['app']			 = $row['app'];
				$rowArray['noShow']			 = $row['noShow'];
				$rowArray['notAllocated']	 = $row['notAllocated'];
				$rowArray['late']			 = $row['late'];
				$rowArray['unassign']		 = $row['unassign'];
				$rowArray['unregisteredcab'] = $row['unregisteredcab'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$this->pageTitle = " Penalty Summary";
		$dataProvider	 = $model->getPenaltySummary($orderby);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('penaltySummary', array('model' => $model, 'dataProvider' => $dataProvider, 'orderby' => $orderby, 'roles' => $row));
	}

	public function actionProcessedPayments()
	{
		$row = Report::getRoleAccess(63);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$model = new OnlineBanking();

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ProcessedPayments_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ProcessedPayments_" . date('Ymdhis') . ".csv";
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
			$rows	 = OnlineBanking::fetchList('command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Payeename', 'Payee Type', 'Amount in', 'Remarks', 'Transaction Time', 'Payment Status', 'Response Message']);
			foreach ($rows as $row)
			{
				$list								 = OnlineBanking::statusList;
				$rowArray							 = array();
				$rowArray['payeename']				 = $row['payeename'];
				$rowArray['payeetype']				 = $row['payeetype'];
				$rowArray['onb_amount']				 = $row['onb_amount'];
				$rowArray['onb_remarks']			 = $row['onb_remarks'];
				$rowArray['onb_created_on']			 = $row['onb_created_on'];
				$rowArray['onb_status']				 = $list[$row['onb_status']];
				$rowArray['onb_response_message']	 = $row['onb_response_message'];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = OnlineBanking::fetchList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('processed_payments', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionAssignmentReport()
	{
		$row = Report::getRoleAccess(65);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('assignment_report', array('model' => $model, 'bkgassigned' => $bkgassigned, 'roles' => $row));
	}

	public function actionPromoReport()
	{
		$row = Report::getRoleAccess(78);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		$this->render('promo_report', array('model' => $model, 'dataProvider' => $dataProvider, 'countPromos' => $countPromos, 'roles' => $row), false, true);
	}

	public function actionProcessbooking()
	{
		$row = Report::getRoleAccess(82);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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

	public function actionLedgerList()
	{
		$row = Report::getRoleAccess(83);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Ledger List Details";
		$model			 = new PaymentGateway();

		$model->scenario			 = "ledgerbooking";
		$transDate1					 = '';
		$transDate2					 = '';
		$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 29 days'));
		$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));

		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$model->trans_create_date1	 = $_REQUEST['export_from1'];
			$model->trans_create_date2	 = $_REQUEST['export_to1'];
			$model->apg_ledger_type_id	 = $_REQUEST['ledger1'];
			$model->apg_ledger_type_ids	 = $_REQUEST['ledger2'];
			$rows						 = AccountTransDetails::getAccountTLeadgerList($model);

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LedgerDetails" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "LedgerDetails_" . date('YmdHi') . ".csv";
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

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Date', 'Leadger1', 'Leadger2', 'BookingId', 'Vendor', 'Agent', 'Remarks', 'Amount']);
			foreach ($rows as $row)
			{
				$rowArray				 = array();
				$rowArray['Date']		 = $row['act_date'];
				$rowArray['Leadger1']	 = $row['ledgerName'];
				$rowArray['Leadger2']	 = $row['ledgerName2'];
				$rowArray['BookingId']	 = ($row['bkg_booking_id'] != "" ? $row['bkg_booking_id'] : $row['bkg_id2']);
				$rowArray['Vendor']		 = ($row['vendorName'] != "" ? $row['vendorName'] : $row['vendorName2']);
				$rowArray['Agent']		 = ($row['agent'] != "" ? $row['agent'] : $row['agent2']);
				$rowArray['Remarks']	 = $row['act_remarks'];
				$rowArray['Amount']		 = $row['amount'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}



		if (Yii::app()->request->getParam('PaymentGateway') != "")
		{
			$arr = Yii::app()->request->getParam('PaymentGateway');

			$transCreateDate1			 = $arr['trans_create_date1'];
			$transCreateDate2			 = $arr['trans_create_date2'];
			$ledgerId1					 = $arr['apg_ledger_type_id'];
			;
			$ledgerIds					 = $arr['apg_ledger_type_ids'];
			$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 29 days'));
			$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));
			if ($transCreateDate1 != '' && $transCreateDate2 != '')
			{
				$transDate1					 = $transCreateDate1;
				$transDate2					 = $transCreateDate2;
				$model->trans_create_date1	 = $transDate1;
				$model->trans_create_date2	 = $transDate2;
				$model->apg_ledger_type_id	 = $ledgerId1;
				$model->apg_ledger_type_ids	 = $ledgerIds;
			}

			$dataProvider							 = AccountTransDetails::getAccountTLeadgerList($model, 'Command');
			$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
			$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		}

		$this->render('ledgerDetails', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionPartnerMonthlyBalance()
	{
		$row = Report::getRoleAccess(84);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Partner Monthly Balance - Report";
		$model			 = new AccountTransactions();
		$partnerModel	 = new ChannelPartnerMarkup();
		$request		 = Yii::app()->request;
		$fromDate		 = $toDate			 = '';
		$agentId		 = '';

		if (isset($_REQUEST['to_date']) && $_REQUEST['from_date'])
		{
			$fromDate	 = Yii::app()->request->getParam('from_date');
			$toDate		 = Yii::app()->request->getParam('to_date');
			$agentId	 = Yii::app()->request->getParam('cpm_agent_id');
			$params		 = [
				'from_date'	 => $fromDate,
				'to_date'	 => $toDate
			];
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"PartnerMonthlyBalanceReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "PartnerMonthlyBalanceReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = Agents::partnerMonthlyBalance($agentId, $params, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Agent Name', 'pickupDate', 'Booking Amount', 'Served Amount', 'AdvanceAmount', 'CancelCharges', 'TotalBalance', 'Wallet', 'Bank', 'Compensation', 'OtherPayments', 'Commission']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['agentName']		 = $row['agentName'];
				$rowArray['pickupDate']		 = $row['pickupDate'];
				$rowArray['BookingAmount']	 = $row['BookingAmount'];
				$rowArray['ServedAmount']	 = $row['ServedAmount'];
				$rowArray['AdvanceAmount']	 = $row['AdvanceAmount'];
				$rowArray['CancelCharges']	 = $row['CancelCharges'];
				$rowArray['TotalBalance']	 = $row['TotalBalance'];
				$rowArray['Wallet']			 = $row['Wallet'];
				$rowArray['Bank']			 = $row['Bank'];
				$rowArray['Compensation']	 = $row['Compensation'];
				$rowArray['OtherPayments']	 = $row['OtherPayments'];
				$rowArray['Commission']		 = $row['Commission'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}




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
			'todate'		 => $toDate, 'model'			 => $model, 'partnerModel'	 => $partnerModel, 'error'			 => $error, 'agtError'		 => $agtError, 'roles'			 => $row));
	}

	public function actionPaymentSummaryReport()
	{
		$row = Report::getRoleAccess(85);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

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

		if (isset($_REQUEST['trans_create_date1']) && $_REQUEST['trans_create_date2'])
		{
			$createDate1 = Yii::app()->request->getParam('trans_create_date1');
			$createDate2 = Yii::app()->request->getParam('trans_create_date2');
			$ptpId		 = Yii::app()->request->getParam('apg_ptp_id');
			$groupBy	 = Yii::app()->request->getParam('groupBy');
			$params		 = [
				'from_date'	 => $createDate1,
				'to_date'	 => $createDate2
			];
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"PaymentSummaryReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "PaymentSummaryReport_" . date('Ymdhis') . ".csv";
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
			$rows			 = $model->paymentSummaryReport($params, $groupBy, $ptpId, DBUtil::ReturnType_Query);
			$handle			 = fopen("php://output", 'w');
			fputcsv($handle, ['Date ', 'Type', 'Received', 'Refund', 'Net']);
			$paymentTypeJson = PaymentType::model()->getList();
			foreach ($rows as $row)
			{
				$rowArray				 = array();
				$rowArray['date']		 = $row['date'];
				$rowArray['apg_ptp_id']	 = $paymentTypeJson[$row['apg_ptp_id']];
				$rowArray['receive']	 = $row['receive'];
				$rowArray['refund']		 = $row['refund'];
				$rowArray['net']		 = $row['net'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
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
			'error'			 => $error, 'roles'			 => $row));
	}

	public function actionCancellation()
	{
		$row = Report::getRoleAccess(93);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
			'countReport'	 => $countReport, 'roles'			 => $row
		));
	}

	public function actionDailyConfirmation()
	{
		$row = Report::getRoleAccess(101);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Daily Confirmation Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		$orderby		 = 'date';
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
			$isAndroidApp	 = $arr['isAndroid'];
			$isiOSApp		 = $arr['isiOS'];

			$model->local		 = $arr['local'];
			$model->outstation	 = $arr['outstation'];
		}
		else
		{
			$fromConfirmDate = date("Y-m-d", strtotime("-15 day", time()));
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
		$model->isAndroid		 = $arr['isAndroid'];
		$model->isiOS			 = $arr['isiOS'];

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

		$params = [
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
			'androidApp'		 => $isAndroidApp,
			'iosApp'			 => $isiOSApp,
		];
		Logger::profile("Params Init Done");
		if (isset($_REQUEST['bkg_create_date1']) && $_REQUEST['bkg_create_date2'])
		{
			$fromConfirmDate = Yii::app()->request->getParam('bkg_create_date1');
			$toConfirmDate	 = Yii::app()->request->getParam('bkg_create_date2');
			$bkgType		 = Yii::app()->request->getParam('bkgtypes') != null ? Yii::app()->request->getParam('bkgtypes') : [];
			$weekDays		 = Yii::app()->request->getParam('weekDays') != null ? Yii::app()->request->getParam('weekDays') : [];
			$region			 = Yii::app()->request->getParam('bkg_region') != null ? explode(",", Yii::app()->request->getParam('bkg_region')) : null;
			$local			 = Yii::app()->request->getParam('local') != null ? Yii::app()->request->getParam('local') : 0;
			$outstation		 = Yii::app()->request->getParam('outstation') != null ? Yii::app()->request->getParam('outstation') : 0;
			$restricted		 = Yii::app()->request->getParam('restricted') != null ? Yii::app()->request->getParam('restricted') : 0;
			$isGozonow		 = Yii::app()->request->getParam('isGozonow') != null ? Yii::app()->request->getParam('isGozonow') : 0;
			$isMobileApp	 = Yii::app()->request->getParam('isMobile') != null ? Yii::app()->request->getParam('isMobile') : 0;
			$isAndroidApp	 = Yii::app()->request->getParam('isAndroid') != null ? Yii::app()->request->getParam('isAndroid') : 0;
			$isIosApp		 = Yii::app()->request->getParam('isiOS') != null ? Yii::app()->request->getParam('isiOS') : 0;

			$orderby	 = Yii::app()->request->getParam('orderby');
			$params		 = [
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
				'androidApp'		 => $isAndroidApp,
				'iosApp'			 => $isIosApp,
			];
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DailyConfirmationReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DailyConfirmationReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = Booking::getDailyConfirmationData($params, $orderby, $command = DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, [ucfirst($orderby), 'Count Self Cancelled', 'Count Self Active', 'Count Self Gozo Coins', 'Count Self', 'Count Gozo Coins', 'Count Admin Cancelled', 'Count Admin Active', 'Count Admin', 'B2C Margin', 'B2C Gozo Amount', 'Count B2C', 'Cancelled MMT', 'Count MMT', 'MMT Margin', 'MMT Gozo Amount']);
			foreach ($rows as $row)
			{
				$rowArray	 = array();
				$dateCol	 = "";
				switch ($row['groupType'])
				{
					case 'hour':
						$dateCol = $row['hour'];
						break;
					case 'date':
						$dateCol = $row['date'];
						break;
					case 'week':
						$dateCol = $row['week'];
						break;
					case 'month':
						$dateCol = $row['month'];
						break;
					default:
						break;
				}
				$rowArray['date']				 = $dateCol;
				$rowArray['cntSelfCancelled']	 = $row['cnt Self Cancelled'];
				$rowArray['cntSelfActive']		 = $row['cnt Self Active'];
				$rowArray['cntSelfGozoCoins']	 = $row['cnt Self Gozo Coins'];
				$rowArray['cntSelf']			 = $row['cnt Self'];
				$rowArray['CountGozoCoins']		 = $row['Count Gozo Coins'];
				$rowArray['CountAdminCancelled'] = $row['Count Admin Cancelled'];
				$rowArray['CountAdminActive']	 = $row['Count Admin Active'];
				$rowArray['countAdmin']			 = $row['count Admin'];
				$rowArray['B2CMargin']			 = $row['B2C Margin'];
				$rowArray['B2CGozoAmount']		 = $row['B2C Gozo Amount'];
				$rowArray['CountB2C']			 = $row['Count B2C'];
				$rowArray['CancelledMMT']		 = $row['Cancelled MMT'];
				$rowArray['CountMMT']			 = $row['Count MMT'];
				$rowArray['MMTMargin']			 = $row['MMT Margin'];
				$rowArray['MMTGozoAmount']		 = $row['MMT Gozo Amount'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = Booking::getDailyConfirmationData($params, $orderby);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		Logger::profile("Data Provider Initialized");
		skipAll:
		$this->render('dailyconfirmaiton', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'orderby'		 => $orderby,
			'error'			 => $error, 'roles'			 => $row)
		);
	}

	public function actionVendorDueSummary()
	{
		$orderby = 'week';
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
			$date1				 = $model->from_date	 = date('Y-m-d', strtotime("-30 days")) . " 00:00:00";
			$date2				 = $model->to_date		 = date('Y-m-d') . " 23:59:59";
		}
		switch ($orderby)
		{
			case "date":
				$diff	 = "-30 day";
				$errMsg	 = "Date range is not greater than 30 days";
				break;
			case "month":
				$diff	 = "-6 month";
				$errMsg	 = "Date range is not greater than 6 months";
				break;
			case "week":
			default:
				$diff	 = "-12 week";
				$errMsg	 = "Date range is not greater than 12 week";
				break;
		}

		$diffMonth	 = strtotime($diff, strtotime($date2)) - strtotime($date1);
		$error		 = '';
		if ($diffMonth > 0)
		{
			$error = $errMsg;
			goto skipAll;
		}

		$this->pageTitle = " Vendor Due Summary";
		$dataProvider	 = $model->getVendorDueSummary($orderby);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		skipAll:
		$this->render('vendorDueSummary', array('error' => $error, 'model' => $model, 'dataProvider' => $dataProvider, 'orderby' => $orderby));
	}

}
