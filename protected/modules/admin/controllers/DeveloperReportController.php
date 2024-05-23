<?php

class DeveloperReportController extends Controller
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
		return array(
			['allow', 'actions'	 => ['list', 'mmtPushReport', 'mmtReviewReport', 'mmtReport', 'accountMismatchReport',
					'advanceMismatchReport', 'pull', 'query', 'queryList', 'tripPurchaseMissing', 'tripPurchaseMultipleEntry',
					'cashCollectedMissing', 'cashCollectedMultipleEntries', 'advanceMultipleEntries',
					'partnerCommMultipleEntries', 'partnerCommMissing', 'cancellationPolicyDetails', 'partnerReceivableReports', 'penaltyTypeReports', 'bookingAmountMismatchReports', 'driverCollectionMismatchReports'],
				'roles'		 => ['DeveloperReports']],
			['allow', 'actions' => ['query'], 'roles' => ['queryPanel']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(''),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList()
	{
		$this->layout	 = 'admin1';
		$this->pageTitle = " Developer Reports ";
		$model			 = AgentApiTracking::model()->getReportList();
		$this->render('list', array('model' => $model));
	}

	public function actionMmtPushReport()
	{
		$model	 = new AgentApiTracking();
		$data	 = Yii::app()->request->getParam('AgentApiTracking');
		if ($data)
		{
			$data['from_date']	 = DateTimeFormat::DatePickerToDate($data['from_date']);
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$date2				 = $model->to_date		 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
		}
		else
		{
			$date1				 = $model->from_date	 = date("Y-m-d");
			$date2				 = $model->to_date		 = date("Y-m-d");
		}
		$this->pageTitle	 = " MMT Push Report ";
		$cabDriverUpdate	 = $model->getMmtReports(AgentApiTracking::TYPE_CAB_DRIVER_UPDATE);
		$tripStart			 = $model->getMmtReports(AgentApiTracking::TYPE_TRIP_START);
		$tripEnd			 = $model->getMmtReports(AgentApiTracking::TYPE_TRIP_END);
		$leftForPickUp		 = $model->getMmtReports(AgentApiTracking::TYPE_LEFT_FOR_PICKUP);
		$cabDriverReassign	 = $model->getMmtReports(AgentApiTracking::TYPE_CAB_DRIVER_REASSIGN);
		$noShow				 = $model->getMmtReports(AgentApiTracking::TYPE_NO_SHOW);
		$arrived			 = $model->getMmtReports(AgentApiTracking::TYPE_ARRIVED);
		$updateLastLocation	 = $model->getMmtReports(AgentApiTracking::TYPE_UPDATE_LAST_LOCATION);
		$this->render('mmtPushReport', array('model' => $model, 'cabDriverUpdate' => $cabDriverUpdate, 'tripStart' => $tripStart, 'tripEnd' => $tripEnd, 'leftForPickUp' => $leftForPickUp, 'cabDriverReassign' => $cabDriverReassign, 'noShow' => $noShow, 'arrived' => $arrived, 'updateLastLocation' => $updateLastLocation));
	}

	public function actionMmtReviewReport()
	{
		$model	 = new AgentApiTracking();
		$data	 = Yii::app()->request->getParam('AgentApiTracking');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d");
		}
		else
		{
			$date1				 = $model->from_date	 = date("Y-m-d");
			$date2				 = $model->to_date		 = date("Y-m-d");
		}
		$this->pageTitle = " MMT Review Report ";
		$dataProvider	 = $model->getMmtReports(AgentApiTracking::TYPE_GET_REVIEW);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('mmtReviewReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionMmtReport()
	{
		$model	 = new AgentApiTracking();
		$data	 = Yii::app()->request->getParam('AgentApiTracking');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d", strtotime("-1 days"));
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d", strtotime("-1 days"));
		}
		else
		{
			$date1				 = $model->from_date	 = date("Y-m-d", strtotime("-1 days"));
			$date2				 = $model->to_date		 = date("Y-m-d", strtotime("-1 days"));
		}
		$this->pageTitle = " MMT Report ";
		$dataProvider	 = $model->getMmtData();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('mmtReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionAccountMismatchReport()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d");
		}
		else
		{
			$date1				 = $model->from_date	 = date("Y-m-d");
			$date2				 = $model->to_date		 = date("Y-m-d");
		}
		$this->pageTitle = " Account Mismatch Report ";
		$dataProvider	 = $model->getAccountMismatchReports();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('accountMismatchReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionAdvanceMismatchReport()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d");
		}
		else
		{
			$date1				 = $model->from_date	 = date("Y-m-d");
			$date2				 = $model->to_date		 = date("Y-m-d");
		}
		$this->pageTitle = " Advance Mismatch Report ";
		$dataProvider	 = $model->getAdvanceMismatchReports();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('advanceMismatchReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionPull()
	{
		$path	 = ROOT_PATH;
		$command = "cd {$path} && git pull";

		echo "<pre>";
		passthru($command);
	}

	public function actionQuery()
	{
		$query		 = $_REQUEST['query'];
		$queryDesc	 = $_REQUEST['desc'];
		$userInfo	 = UserInfo::getInstance();
		if ($query != null && $queryDesc != null)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				if (stripos($query, 'delete') !== false)
				{
					throw new Exception("You can not perform delete operation.");
				}
				if (stripos($query, 'drop') !== false)
				{
					throw new Exception("You can not perform drop operation.");
				}

				$cntRows = DBUtil::command($query)->execute(); //Yii::app()->db->createCommand($query)->execute();

				$queryLogModel						 = new QueryLog();
				$queryLogModel->qlg_query			 = $query;
				$queryLogModel->qlg_desc			 = $queryDesc;
				$queryLogModel->qlg_admin_id		 = $userInfo->getUserId();
				$queryLogModel->qlg_rows_effected	 = $cntRows;
				$queryLogModel->save();
				DBUtil::commitTransaction($transaction);
				Yii::app()->user->setFlash('success', 'Query executed successfully!  Rows effected: ' . $cntRows);
				$query								 = '';
				$queryDesc							 = '';
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Yii::app()->user->setFlash('error', $e->getMessage());
			}
		}
		$this->renderAuto('query', ['query' => $query, 'desc' => $queryDesc]);
	}

	/* This function is used for Query log LIsting */

	public function actionqueryList()
	{
		$this->layout	 = 'admin1';
		$this->pageTitle = " Developer Reports -Query List ";
		$model			 = new QueryLog();
		$arr			 = Yii::app()->request->getParam('QueryLog');
		if (isset($_REQUEST['QueryLog']))
		{
			$arr				 = Yii::app()->request->getParam('QueryLog');
			$model->attributes	 = $arr;
			$model->qlg_created1 = !empty($arr['qlg_created1']) ? date('Y-m-d', strtotime($arr['qlg_created1'])) . " 00:00:00" : "";
			$model->qlg_created2 = !empty($arr['qlg_created2']) ? date('Y-m-d', strtotime($arr['qlg_created2'])) . " 23:59:59" : "";
			$model->qlg_admin_id = $arr['qlg_admin_id'];
		}
		else
		{
			$arr['qlg_created1'] = $model->qlg_created1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$arr['qlg_created2'] = $model->qlg_created2 = date('Y-m-d') . " 23:59:59";
			$arr['qlg_admin_id'] = $model->qlg_admin_id = '';
		}
		$dataProvider = $model->getList($model);
		$this->render('querylist', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Vendor Trip Purchase Missing Report
	 */
	public function actionTripPurchaseMissing()
	{
		$this->pageTitle = "Vendor Trip Purchase Missing";

		$model = new AccountTransactions();

		$dataProvider = $model->tripPurchaseMissing();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('tripPurchaseMissing', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Vendor Trip Purchase Multiple Entry
	 */
	public function actionTripPurchaseMultipleEntry()
	{
		$this->pageTitle = "Vendor Trip Multiple Entry";

		$model = new AccountTransactions();

		$dataProvider = $model->tripPurchaseMultipleEntry();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('tripPurchaseMultipleEntry', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Driver Cash Collected Missing Report
	 */
	public function actionCashCollectedMissing()
	{
		$this->pageTitle = "Driver Cash Collected Missing";

		$model = new AccountTransactions();

		$dataProvider = $model->cashCollectedMissing();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('cashCollectedMissing', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Driver Cash Collected Multiple Entries Report
	 */
	public function actionCashCollectedMultipleEntries()
	{
		$this->pageTitle = "Driver Cash Collected Multiple Entries";

		$model = new AccountTransactions();

		$dataProvider = $model->cashCollectedMultipleEntries();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('cashCollectedMultipleEntries', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Booking Advance Multiple Entries Report
	 */
	public function actionAdvanceMultipleEntries()
	{
		$this->pageTitle = "Booking Advance Multiple Entries";

		$model = new AccountTransactions();

		$dataProvider = $model->advanceMultipleEntries();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('advanceMultipleEntries', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Partner Commission Multiple Entries Report
	 */
	public function actionPartnerCommMultipleEntries()
	{
		$this->pageTitle = "Partner Commission Multiple Entries";

		$model = new AccountTransactions();

		$dataProvider = $model->partnerCommMultipleEntries();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('partnerCommMultipleEntries', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * Partner Commission Missing Report
	 */
	public function actionPartnerCommMissing()
	{
		$this->pageTitle = "Partner Commission Missing";

		$model = new AccountTransactions();

		$dataProvider = $model->partnerCommMissing();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('partnerCommMissing', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionCancellationPolicyDetails()
	{
		$this->pageTitle = "Cancellation Policy Details";
		$dataProvider	 = PartnerCancelRule::getCancelRule();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderAuto('cancellationPolicyDetails', ['dataProvider' => $dataProvider], false, true);
	}

	public function actionPartnerReceivableReports()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d");
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d");
			$model->bkg_agent_id = $data['bkg_agent_id'] != null ? $data['bkg_agent_id'] : 18190;
		}
		else
		{
			$date1				 = $model->from_date	 = date("Y-m-d");
			$date2				 = $model->to_date		 = date("Y-m-d");
			$model->bkg_agent_id = 18190;
		}
		$this->pageTitle = " Partner Receivable Reports ";
		$dataProvider	 = $model->getPartnerReceivableReports();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('partnerReceivableReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionPenaltyTypeReports()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d") . " 00:00:00";
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d") . " 23:59:59";
		}
		else
		{
			$date1				 = $model->from_date	 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$date2				 = $model->to_date		 = date('Y-m-d') . " 23:59:59";
		}
		$this->pageTitle = " Penalty Type Reports ";
		$dataProvider	 = $model->getPenaltyTypeReports();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('penaltyTypeReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}
	
	public function actionBookingAmountMismatchReports()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d") . " 00:00:00";
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d") . " 23:59:59";
		}
		else
		{
			$date1				 = $model->from_date	 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$date2				 = $model->to_date		 = date('Y-m-d') . " 23:59:59";
		}

		$this->pageTitle = " Booking Amount Mismatch Reports ";
		$dataProvider	 = $model->getBookingAmountMismatchReports();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('bookingAmountMismatchReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionDriverCollectionMismatchReports()
	{
		$model	 = new AccountTransactions();
		$data	 = Yii::app()->request->getParam('AccountTransactions');
		if ($data)
		{
			$date1				 = $model->from_date	 = $data['from_date'] != null ? $data['from_date'] : date("Y-m-d") . " 00:00:00";
			$date2				 = $model->to_date		 = $data['to_date'] != null ? $data['to_date'] : date("Y-m-d") . " 23:59:59";
		}
		else
		{
			$date1				 = $model->from_date	 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$date2				 = $model->to_date		 = date('Y-m-d') . " 23:59:59";
		}

		$this->pageTitle = " Driver Collection Mismatch Reports ";
		$dataProvider	 = $model->getdriverCollectionMismatchReports();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('driverCollectionMismatchReport', array('model' => $model, 'dataProvider' => $dataProvider));
	}

}
