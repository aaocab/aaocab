<?php

use UserLog;
use LeadLog;

class LeadController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow', 'actions' => ['dailyleadreport', 'track', 'autoLeadFollowup'], 'roles' => ['leadReportDaily']],
			['allow', 'actions' => ['leadsAndUnverifiedFeedback'], 'roles' => ['LeadsUnverifiedFeedbackReport']],
			array('allow',
				'actions'	 => array('leadsAndUnverifiedFeedback'),
				'roles'		 => array('ConfidentialReports'),
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

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('get');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.assignLead.render', function () {

			return $this->renderJSON($this->assignLeadToCsr());
		});

		$this->onRest('req.post.unassignLead.render', function () {
			return $this->renderJSON($this->unassignLeadFromCsr());
		});

		$this->onRest('req.post.getAssignedLead.render', function () {
			return $this->renderJSON($this->getAssignedLead());
		});

		$this->onRest('req.post.uploadAudio.render', function () {
			return $this->renderJSON($this->uploadAudio());
		});

		$this->onRest('req.post.leadPref.render', function () {
			return $this->renderJSON($this->leadPref());
		});
	}

	public function actionDailyLeadReport()
	{
		$row = Report::getRoleAccess(8);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Daily Lead Report";
		$model			 = new LeadLog();
		$model->fromDate = date('Y-m-d');
		$model->toDate	 = date('Y-m-d');
		if (isset($_REQUEST['LeadLog']))
		{
			$model->attributes	 = $_REQUEST['LeadLog'];
			$model->executive	 = $_REQUEST['LeadLog']['executive'];
			$model->fromDate	 = $_REQUEST['LeadLog']['fromDate'];
			$model->toDate		 = $_REQUEST['LeadLog']['toDate'];
			if ($_REQUEST['fromDate'] != '')
			{
				$model->fromDate = $_REQUEST['fromDate'];
			}
			if ($_REQUEST['toDate'] != '')
			{
				$model->toDate = $_REQUEST['toDate'];
			}
		}
		$countReport	 = BookingSub::model()->findZonewiseBookingCount($model->fromDate, $model->toDate);
		$returnArr		 = $model->getDailyLeadReportCount();
		$dataProvider	 = $returnArr['dataprovider'];
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('dailyreport', ['model' => $model, 'dataProvider' => $dataProvider, 'countReports' => $countReport, 'createTable' => $returnArr['createTable'], 'confirmTable' => $returnArr['confirmTable'], 'leadTable' => $returnArr['leadTable'], 'bookingFollowupTable' => $returnArr['bookingFollowupTable'], 'roles' => $row]);
	}

	public function actionLeadsAndUnverifiedFeedback()
	{
		$row = Report::getRoleAccess(34);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
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
		if (isset($_REQUEST['to_date']) && $_REQUEST['from_date'])
		{
			$fromDate	 = Yii::app()->request->getParam('from_date');
			$toDate		 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LeadsAndUnverifiedFeedbackReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "LeadsAndUnverifiedFeedbackReport_" . date('Ymdhis') . ".csv";
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
			$rows	 = LeadFollowup::model()->getList($fromDate, $toDate, true);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Customer Name', 'Lead/Booking Id', 'From City', 'To City', 'Bkg Amount', 'Pickup Date', 'Competitor Quote', 'Price Was High', 'Will Book Later', 'Tentative Requested', 'Call me please', 'Other Comments', 'Create Date']);
			foreach ($rows as $row)
			{
				$rowArray								 = array();
				$rowArray['user_fullname']				 = $row['user_fullname'];
				$rowArray['booking_id']					 = $row['booking_id'];
				$rowArray['fromCity']					 = $row['fromCity'];
				$rowArray['toCity']						 = $row['toCity'];
				$rowArray['bkgAmount']					 = $row['bkgAmount'];
				$rowArray['pickupDate']					 = date('d/m/Y h:i A', strtotime($row['pickupDate']));
				$rowArray['price_was_high_comment']		 = $row['price_was_high_comment'];
				$rowArray['price_was_high']				 = $row['price_was_high'];
				$rowArray['will_book_later']			 = $row['will_book_later'];
				$rowArray['will_book_later_tentative']	 = $row['will_book_later_tentative'];
				$rowArray['call_me_please']				 = $row['call_me_please'];
				$rowArray['other_comment']				 = $row['other_comment'];
				$rowArray['createDate']					 = date('d/m/Y h:i A', strtotime($row['createDate']));
				$row1									 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = LeadFollowup::model()->getList($model->lfu_from_date, $model->lfu_to_date, false);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report-leads-and-unverified', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionTrack()
	{
		$row = Report::getRoleAccess(108);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Lead Track";
		$model			 = new Booking();
		$fromCreateDate	 = $toCreateDate	 = '';
		$visitorDate1	 = $visitorDate2	 = '';
		$req			 = Yii::app()->request;

		if ($req->getParam('Booking'))
		{
			$arr			 = $req->getParam('Booking');
			$fromCreateDate	 = $arr['bkg_create_date1'];
			$toCreateDate	 = $arr['bkg_create_date2'];
		}
		else
		{
			$fromCreateDate	 = date("Y-m-d", strtotime("-7 day", time()));
			$toCreateDate	 = date('Y-m-d') . " 23:59:59";
		}
		$model->bkg_create_date1 = $fromCreateDate;
		$model->bkg_create_date2 = $toCreateDate;

		$params			 = [
			'bkg_create_date1'	 => $fromCreateDate,
			'bkg_create_date2'	 => $toCreateDate,
		];
		$dataProvider	 = Booking::leadTrack($params);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->render('leadtrack', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionAutoLeadFollowup()
	{
		$row = Report::getRoleAccess(130);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Booking History By create date";

		$model	 = new LeadFollowup();
		$request = Yii::app()->request;
		if ($request->getParam('LeadFollowup'))
		{
			$data = Yii::app()->request->getParam('LeadFollowup');
			if ($data['lfu_from_date'] != '' && $data['lfu_to_date'] != '')
			{
				$picupDate1				 = $data['lfu_from_date'] . " 00:00:00";
				$picupDate2				 = $data['lfu_to_date'] . " 23:59:59";
				$model->lfu_from_date	 = $picupDate1;
				$model->lfu_to_date		 = $picupDate2;
			}
		}
		else
		{
			$model->lfu_from_date	 = date('Y-m-d', strtotime('today - 3 days')) . " 00:00:00";
			$model->lfu_to_date		 = date('Y-m-d', strtotime('today + 1 day')) . " 23:59:59";
		}

		if ($_REQUEST['export'] == true)
		{
			$model->lfu_from_date	 = Yii::app()->request->getParam('from_date');
			$model->lfu_to_date		 = Yii::app()->request->getParam('to_date');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LeadFollowupReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "LeadFollowupReport_" . date('YmdHi') . ".csv";
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
			$rows	 = LeadFollowup::getAutoLeadFollowup($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['createDate','followup','lfu_id','ref_id','comment','tell us','type','status','contact number','user email','booking Id','pickup date' ]);
			foreach ($rows as $row)
			{
				$rowArray	 = array(
					'lfu_create_date'	 => $row['lfu_create_date'],
					'lfu_followup'		 => $row['lfu_followup'],
					'lfu_id'			 => $row['lfu_id'],
					'lfu_ref_id'		 => $row['lfu_ref_id'],
					'lfu_comment'		 => $row['lfu_comment'],
					'lfu_tellus'		 => $row['lfu_tellus'],
					'lfu_type'			 => $row['lfu_type'],
					'lfu_status'		 => $row['lfu_status'],
					'contact_no'		 => $row['bkg_country_code'] . " " . $row['bkg_contact_no'],
					'bkg_user_email'	 => $row['bkg_user_email'],
					'bkg_booking_id'	 => $row['bkg_booking_id'],
					'bkg_pickup_date'	 => $row['bkg_pickup_date'],
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
		$dataProvider	 = LeadFollowup::getAutoLeadFollowup($model);
		$dataProvider->setSort(['params' => array_filter($request)]);
		$dataProvider->setPagination(['params' => array_filter($request)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('lead_followup_list', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row), false, $outputJs);
	}

}
