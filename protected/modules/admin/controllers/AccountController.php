<?php

class AccountController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $pageTitle;

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
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('list', 'lists', 'delterms', 'refund', 'create', 'refundtran',
					'paymenttran', 'accountlist', 'ledgeraccountlist', 'ledgerList', 'exportLedger', 'list1',
					'ledger', 'generateLedgerCsv', 'reconciliationSheet', 'addSheet', 'processReconciliationSheet', 'exportSheet', 'vendorBalance', 'accountingFlagData'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index',),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList()
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
		$model->resetScope();
		$dataProvider = AccountTransDetails::getAccountTransactionsList($date1, $date2, $ledgerId);
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionList1()
	{
		$ledgerId		 = Yii::app()->request->getParam("ledgerId");
		$this->pageTitle = "Account Ledger List";

		$model = new AccountTransDetails('search');
		if (isset($_REQUEST['AccountTransDetails']))
		{
			$arr	 = Yii::app()->request->getParam('AccountTransDetails');
			$date1	 = DateTimeFormat::DatePickerToDate($arr['trans_date1']);
			$date2	 = DateTimeFormat::DatePickerToDate($arr['trans_date2']);
		}
		else
		{
			$date1	 = date('Y-m-01', strtotime('last month'));
			$date2	 = date('Y-m-t', strtotime('last month'));
		}
		$model->trans_date1	 = $date1;
		$model->trans_date2	 = $date2;

		$model->resetScope();
		$dataProvider = AccountTransDetails::getAccountTransactionsList1($ledgerId, $date1, $date2);
		$this->render('list1', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAccountList()
	{
		$bkgid	 = Yii::app()->request->getParam('bkgid');
		$bcbid	 = Yii::app()->request->getParam('bcbid');

		$this->pageTitle = "Account Transaction List";
		$qry			 = [];
		$model			 = new AccountTransDetails('search');

		$model->resetScope();
		$params1								 = array_filter($_GET + $_POST);
		$dataProvider							 = AccountTransDetails::getLedgerAccountList($bkgid, $bcbid);
		$dataProvider->getPagination()->params	 = array_filter($params1);
		$dataProvider->getSort()->params		 = $params1;
		$this->render('accountlist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionLedgerList()
	{
		$this->pageTitle = "Ledger List Details";
		$model			 = new PaymentGateway();

		$model->scenario			 = "ledgerbooking";
		$transDate1					 = '';
		$transDate2					 = '';
		$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 29 days'));
		$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));

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

		$this->render('ledgerDetails', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionexportLedger()
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

			$rowArray = array();

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

	public function actionLedger()
	{
		$result			 = false;
		$returnSet		 = new ReturnSet();
		$this->pageTitle = "Add Ledger";

		$model = new LedgerDataRequests();
		if (isset($_POST['LedgerDataRequests']))
		{
			try
			{
				$arr = Yii::app()->request->getParam('LedgerDataRequests');

				$groupType = '';
				#$groupPeriod = '';
				if ($arr['group_by_type'][0] != '')
				{
					$groupType = $arr['group_by_type'][0];
				}
				elseif ($arr['group_by_partner'][0] != '')
				{
					$groupType = $arr['group_by_partner'][0];
				}
//				if ($arr['groupby_period'][0] != '')
//				{
//					$groupPeriod = $arr['groupby_period'][0];
//				}

				$ledgerCombineArr = array(
					"from_date"		 => $arr['fromDate'],
					"to_date"		 => $arr['toDate'],
					"from_ledger_id" => $arr['from_ledger_id'],
					"to_ledger_id"	 => $arr['to_ledger_id'],
					"groupby_period" => $arr['groupby_period'],
					"groupby_type"	 => $groupType
				);

				$model->ldr_form_input	 = json_encode($ledgerCombineArr);
				$model->ldr_created_by	 = UserInfo::getUserId();
				if ($model->save())
				{
					$this->redirect(array('ledger'));
				}
			}
			catch (Exception $e)
			{
				$returnSet->setStatus(false);
				$returnSet = ReturnSet::setException($e);
			}
		}

		$dataprovider	 = LedgerDataRequests::getList();
		$ledgers		 = AccountLedger::getAllLedgerId();
		$this->render('ledger1', array('model' => $model, 'dataprovider' => $dataprovider, 'ledgers' => $ledgers));
	}

	public function actionReconciliationSheet()
	{
		$this->pageTitle = "Reconciliation Sheet List";

		$model = new PartnerReconciliationSheet();

		$params1								 = array_filter($_GET + $_POST);
		$dataProvider							 = PartnerReconciliationSheet::getList();
		$dataProvider->getPagination()->params	 = array_filter($params1);
		$dataProvider->getSort()->params		 = $params1;

		$this->render('reconciliation_sheet', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAddSheet()
	{
		$this->pageTitle = "Post Reconciliation Sheet";

		$success = "";
		$model	 = new PartnerReconciliationSheet();

		if (isset($_FILES["file"]))
		{
			$arrSheet = Yii::app()->request->getParam('PartnerReconciliationSheet');

			$tmpFileName = $_FILES["file"]["tmp_name"];
			$fileName	 = $_FILES["file"]["name"];
			if ($_FILES["file"]["size"] > 0)
			{
				$file	 = fopen($tmpFileName, "r");
				
				if($arrSheet['prs_sheet_type'] == 1)
				{
					$success = PartnerReconciliationData::addPayoutSheet($arrSheet, $file, $fileName);
				}
				else if($arrSheet['prs_sheet_type'] == 2)
				{
					$success = PartnerReconciliationData::addPenaltySheet($arrSheet, $file, $fileName);
				}
			}
		}

		$this->render('add_sheet', array('model' => $model, 'success' => $success));
	}

	public function actionExportSheet()
	{
		$objSheet	 = false;
		$prsId		 = Yii::app()->request->getParam('prsId');
		if ($prsId > 0)
		{
			$objSheet = PartnerReconciliationSheet::model()->findByPk($prsId);
		}
		if (!$objSheet)
		{
			echo "Invalid Id";
			exit();
		}
		
		$sheetType = $objSheet->prs_sheet_type;
		if ($sheetType == 1)
		{
			PartnerReconciliationData::exportData($prsId);
		}
		else if ($sheetType == 2)
		{
			
		}
		else if ($sheetType == 3)
		{
			
		}
		
	}

	public function actionProcessReconciliationSheet()
	{
		$sqlSheet	 = "SELECT * FROM partner_reconciliation_sheet WHERE prs_status IN (1,2) ORDER BY prs_id ASC LIMIT 0,1";
		$rowSheet	 = DBUtil::queryRow($sqlSheet, DBUtil::SDB());
		if ($rowSheet)
		{
			$prsId		 = $rowSheet['prs_id'];
			$sheetType	 = $rowSheet['prs_sheet_type'];
			$status		 = $rowSheet['prs_status'];

			if ($sheetType == 1)
			{
				PartnerReconciliationData::processData($prsId, $status);
			}
			else if ($sheetType == 2)
			{
				PartnerReconciliationPenalty::processData($prsId, $status);
			}
			else if ($sheetType == 3)
			{
				
			}
		}
	}

	public function actionVendorBalance()
	{
		$fromDate	 = '';
		$toDate		 = '';
		if (filter_input(INPUT_POST, 'fromDate') && filter_input(INPUT_POST, 'toDate'))
		{
			$fromDate	 = filter_input(INPUT_POST, 'fromDate');
			$toDate		 = filter_input(INPUT_POST, 'toDate');
		}

		if (trim($fromDate) != '' && trim($toDate) != '')
		{
			$dataRows = Vendors::getBalanceDetailsByRange($fromDate, $toDate);

			$filename = "VendorBalance_{$fromDate}-{$toDate}_" . date('YmdHi') . ".csv";

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename={$filename}");
			header("Pragma: no-cache");
			header("Expires: 0");

			$foldername = Yii::app()->params['uploadPath'];

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
			fputcsv($handle, ['Vendor Id', 'Vendor Code', 'Opening', 'VendorAmt', 'GozoAmt', 'AdvAmt', 'BookingAmt',
				'PenaltyAmt', 'Paid/DeductedAmt', 'PaymentRecd', 'VendorBalance', 'BookingCount', 'BookingIds']);
			if (count($dataRows) > 0)
			{
				foreach ($dataRows as $row)
				{
					$rowArray						 = [];
					$rowArray['vnd_ref_code']		 = $row['vnd_ref_code'];
					$rowArray['vnd_code']			 = $row['vnd_code'];
					$rowArray['opening']			 = $row['opening'];
					$rowArray['vendor_amount']		 = $row['vendor_amount'];
					$rowArray['gozo_amount']		 = $row['gozo_amount'];
					$rowArray['advance_amount']		 = $row['advance_amount'];
					$rowArray['total_amount']		 = $row['total_amount'];
					$rowArray['penalty']			 = $row['penalty'];
					$rowArray['payment_made']		 = $row['payment_made'];
					$rowArray['payment_received']	 = $row['payment_received'];
					$rowArray['closing']			 = $row['closing'];
					$rowArray['booking_count']		 = $row['booking_count'];
					$rowArray['booking_ids']		 = $row['booking_ids'];
					$row1							 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
			}

			fclose($handle);
			exit;
		}

		$this->render('report_vendor_balance', array(
			'fromDate'	 => $fromDate,
			'toDate'	 => $toDate)
		);
	}

	public function actionAccountingFlagData()
	{
		$fromDate = trim(Yii::app()->request->getParam('fromDate', date("Y-m-d")));
        $toDate   = trim(Yii::app()->request->getParam('toDate', date("Y-m-d")));

        if ($fromDate == '' || $toDate == '')
        {
            echo "From & To Date is required";
            exit();
        }
		$model			 = new BookingLog();
//		$sql = "SELECT 
//				DATE_FORMAT(blg_created, '%Y-%m-%d') as date, 
//				SUM(IF(blg_event_id = 65, 1, 0)) as accFlgSetCnt, 
//				SUM(IF(blg_event_id = 66, 1, 0)) as accFlgUnsetCnt 
//				FROM booking_log 
//				WHERE blg_event_id IN (65,66) AND blg_created BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59' 
//				GROUP BY date";
//		$rec = DBUtil::query($sql);
//		
//		$str = "<table border='1' cellspacing='2' cellpadding='5'><tr><td>Date</td><td>AccFlgSetCnt</td><td>AccFlgUnsetCnt</td></tr>";
//		foreach ($rec as $row)
//		{
//			$str .= "<tr><td>{$row['date']}</td><td>{$row['accFlgSetCnt']}</td><td>{$row['accFlgUnsetCnt']}</td></tr>";
//		}
//		$str .= "</table>";
//		
//		echo $str;
        
    
        
//        $this->pageTitle = "AccountingFlagData";
//		$model			 = new Agents('search');
//		$model->agt_type = -1;
//		$request		 = Yii::app()->request;
//		if ($request->getParam('Agents'))
//		{
//			$arr				 = $request->getParam('Agents');
//			$model->createDate1	 = $arr['createDate1'];
//			$model->createDate2	 = $arr['createDate2'];
//			$model->adm_fname	 = $arr['adm_fname'];
//			$model->attributes	 = $arr;
//		}

		$dataProvider = $model->accountingFlagData($fromDate,$toDate);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('accountingFlagData', array('dataProvider' => $dataProvider, 'model' => $model));
	}
}
