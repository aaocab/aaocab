<?php

class ScqController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('audioreport'),
				'users'		 => array('*'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('cbrDetailsReport', 'serviceRequestsOwn', 'serviceRequests', 'csrPerformanceReport', 'ScqReport', 'cbrCloseReport'),
				'roles'		 => array('GeneralReport'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('fetchlist', 'cbrStaticalDetailsData', 'teamStaticalData', 'cbrStaticalCloseData', 'dispatchFollowUp', 'serviceCallQueueByTeam', 'ServiceCallQueueByClosedDate'),
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

	public function actionAudioreport()
	{
		$row = Report::getRoleAccess(16);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle	 = "Audio List";
		$model				 = new CallStatus('search');
		$model->cst_status	 = 3;
		$model->cst_type	 = 4;
		$arr				 = [];
		if (isset($_REQUEST['CallStatus']))
		{
			$arr				 = Yii::app()->request->getParam('CallStatus');
			$model->attributes	 = array_filter($arr);
		}

		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
		{
			$arr['cst_id']					 = Yii::app()->request->getParam('cst_id');
			$arr['cst_lead_id']				 = Yii::app()->request->getParam('cst_lead_id');
			$arr['cst_phone_code']			 = Yii::app()->request->getParam('cst_phone_code');
			$arr['cst_phone']				 = Yii::app()->request->getParam('cst_phone');
			$arr['cst_did']					 = Yii::app()->request->getParam('cst_did');
			$arr['cst_agent_name']			 = Yii::app()->request->getParam('cst_agent_name');
			$arr['cst_recording_file_name']	 = Yii::app()->request->getParam('cst_recording_file_name');
			$arr['cst_status']				 = 3;
			$arr['cst_created']				 = Yii::app()->request->getParam('cst_created');
			$arr['cst_modified']			 = Yii::app()->request->getParam('cst_modified');

			$jsonArr	 = array(
				"params" => $arr,
				"keys"	 => array
					(
					'Cst Id',
					'Cst Lead ID',
					'Cst Phone Code',
					'Cst Phone',
					'Cst Did',
					'Cst Agent Name',
					'Cst Recording File Name',
					'Cst Status',
					'Cst Created',
					'Cst Modified'
				)
			);
			$filename	 = "AudioReport_" . date('YmdHis') . ".csv";
			$expiryDate	 = Date('Y-m-d', strtotime('+15 days'));
			ReportExport::CreateRequest($jsonArr, 16, $filename, $expiryDate, 1, UserInfo::getUserId());
		}
		$dataProvider = $model->getAudios(array_filter($arr));
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('audioreport', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider, 'roles'			 => $row
		));
	}

	public function actioncbrDetailsReport()
	{
		$row = Report::getRoleAccess(74);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle							 = "CBR Details Report";
		$followUps									 = new ServiceCallQueue();
		$req										 = Yii::app()->request;
		$followUps->bookingType						 = empty($req->getParam('bookingType')) || $req->getParam('bookingType') == 0 ? 0 : $req->getParam('bookingType');
		$followUps->isCreated						 = empty($req->getParam('isCreated')) || $req->getParam('isCreated') == 0 ? 0 : 1;
		$followUps->csrSearch						 = empty($req->getParam('csrId')) ? "" : explode(",", $req->getParam("csrId"));
		$followUps->date							 = empty($req->getParam('date')) ? DateTimeFormat::DateToLocale(date()) : $req->getParam("date");
		$followUps->from_date						 = empty($req->getParam('fromdate')) ? date("Y-m-d") : $req->getParam("fromdate");
		$followUps->to_date							 = empty($req->getParam('todate')) ? date("Y-m-d") : $req->getParam("todate");
		$followUps->event_id						 = empty($req->getParam('event_id')) ? "" : $req->getParam("event_id");
		$followUps->event_by						 = empty($req->getParam('event_by')) ? "" : $req->getParam("event_by");
		$followUps->queueType						 = empty($req->getParam('queueType')) ? "" : $req->getParam("queueType");
		$followUps->date1							 = $followUps->from_date . ' 00:00:00';
		$followUps->date2							 = $followUps->to_date . ' 23:59:59';
		$followUps->scq_to_be_followed_up_by_id		 = empty($req->getParam('teamId')) ? 0 : $req->getParam("teamId");
		$followUps->requestedBy						 = empty($req->getParam('followupPerson')) ? 0 : $req->getParam("followupPerson");
		$followUps->scq_to_be_followed_up_by_type	 = empty($req->getParam('followupWith')) ? 0 : $req->getParam("followupWith");
		$followupPersonEntity						 = empty($req->getParam('followupPersonEntity')) ? 0 : $req->getParam("followupPersonEntity");
		$followupWithEntityType						 = empty($req->getParam('followupWithEntityType')) ? 0 : $req->getParam("followupWithEntityType");
		switch ($followUps->requestedBy)
		{
			case 1:
				$followUps->custId	 = $followupPersonEntity;
				break;
			case 2:
				$followUps->vendId	 = $followupPersonEntity;
				break;
			case 3:
				$followUps->drvId	 = $followupPersonEntity;
				break;
			case 4:
				$followUps->adminId	 = $followupPersonEntity;
				break;
			case 5:
				$followUps->agntId	 = $followupPersonEntity;
				break;
		}
		switch ($followUps->scq_to_be_followed_up_by_type)
		{
			case 2:
				$followUps->isGozen = $followupWithEntityType;
				break;
		}

		if (isset($_REQUEST['export1']))
		{
			$followUps->isCreated						 = empty($req->getParam('export_isCreated')) || $req->getParam('export_isCreated') == 0 ? 0 : 1;
			$followUps->csrSearch						 = empty($req->getParam('export_csrId')) ? "" : explode(",", $req->getParam("export_csrId"));
			$followUps->date							 = empty($req->getParam('export_date')) ? DateTimeFormat::DateToLocale(date()) : $req->getParam("export_date");
			$followUps->from_date						 = empty($req->getParam('export_fromdate')) ? date("Y-m-d") : $req->getParam("export_fromdate");
			$followUps->to_date							 = empty($req->getParam('export_todate')) ? date("Y-m-d") : $req->getParam("export_todate");
			$followUps->event_id						 = empty($req->getParam('export_event_id')) ? "" : $req->getParam("export_event_id");
			$followUps->event_by						 = empty($req->getParam('export_event_by')) ? "" : $req->getParam("export_event_by");
			$followUps->queueType						 = empty($req->getParam('export_queueType')) ? "" : $req->getParam("export_queueType");
			$followUps->date1							 = $followUps->from_date . ' 00:00:00';
			$followUps->date2							 = $followUps->to_date . ' 23:59:59';
			$followUps->scq_to_be_followed_up_by_id		 = empty($req->getParam('export_teamId')) ? 0 : $req->getParam("export_teamId");
			$followUps->requestedBy						 = empty($req->getParam('export_requested')) ? 0 : $req->getParam("export_requested");
			$followUps->scq_to_be_followed_up_by_type	 = empty($req->getParam('export_disposed')) ? 0 : $req->getParam("export_disposed");
			$followupPersonEntity						 = empty($req->getParam('export_followupPersonEntity')) ? 0 : $req->getParam("export_followupPersonEntity");
			$followupWithEntityType						 = empty($req->getParam('export_followupWithEntityType')) ? 0 : $req->getParam("export_followupWithEntityType");
			$followUps->bookingType						 = empty($req->getParam('export_bookingType')) || $req->getParam('export_bookingType') == 0 ? 0 : $req->getParam("export_bookingType");

			switch ($followUps->requestedBy)
			{
				case 1:
					$followUps->custId	 = $followupPersonEntity;
					break;
				case 2:
					$followUps->vendId	 = $followupPersonEntity;
					break;
				case 3:
					$followUps->drvId	 = $followupPersonEntity;
					break;
				case 4:
					$followUps->adminId	 = $followupPersonEntity;
					break;
				case 5:
					$followUps->agntId	 = $followupPersonEntity;
					break;
			}
			switch ($followUps->scq_to_be_followed_up_by_type)
			{
				case 2:
					$followUps->isGozen = $followupWithEntityType;
					break;
			}
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"CBR_Details_Report_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "CBR_Details_Report_" . date('YmdHi') . ".csv";
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
			$rows	 = ServiceCallQueue::model()->cbrDetailsReport($followUps, $userInfo, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Followup ID', 'Customer Contact Id', 'Booking ID', 'Queue Type', 'Assigned CSR (Employee ID &NAME)', 'Created Date', 'Created By', 'Entity Type', 'Entity Id', 'Creation Comment', 'FollowUp Date', 'Assign Date', 'Time to Assign', 'Closed Date (CSR)', 'Disposition Comment', 'Time to Close']);
			foreach ($rows as $data)
			{
				$rowArray				 = array();
				$rowArray['followup_id'] = $data['FollowupId'];

				$rowArray['customer_contact_id'] = " ";
				if ($data['CustomerContactId'] > 0)
				{
					$contacName						 = Contact::model()->findByPk($data['CustomerContactId'])->ctt_name;
					$contactProfileDetails			 = ContactProfile::getCodeByCttId($data['CustomerContactId']);
					$rowArray['customer_contact_id'] = $contacName . " ( " . $data['CustomerContactId'] . " )";
					if ($contactProfileDetails['cr_is_partner'] != null)
					{
						$rowArray['customer_contact_id'] = " " . "AGT00" . $contactProfileDetails['cr_is_partner'];
					}
					if ($contactProfileDetails['drv_code'] != null)
					{
						$rowArray['customer_contact_id'] = " " . $contactProfileDetails['drv_code'];
					}
					if ($contactProfileDetails['vnd_code'] != null)
					{
						$rowArray['customer_contact_id'] = " " . $contactProfileDetails['vnd_code'];
					}
				}

				$rowArray['booking_id']		 = ($data['ItemID'] != '') ? $data['ItemID'] : '';
				$rowArray['queue_type']		 = $data['followUpType'];
				$rowArray['assigned_csr']	 = $data['csrName'] . " (Employee ID : " . $data['empCode'] . ")";
				$created					 = '';
				$entity_type				 = "";
				$entity_id					 = '';
				if ($data['scq_created_by_type'] == 1)
				{
					$entity_type = "User";
					$entity_id	 = $data['user_id'];
					$created	 = $data['usr_name'];
				}
				else if ($data['scq_created_by_type'] == 2)
				{
					$vnd_id		 = AppTokens::getEntityByUserInfo($data['scq_created_by_uid'], $data['scq_created_by_type']);
					$vendorModel = Vendors::model()->mergedVendorId($vnd_id);
					$created	 = $vendorModel->vnd_name;
					$entity_type = "Vendor";
					$entity_id	 = $vendorModel->vnd_id;
				}
				else if ($data['scq_created_by_type'] == 3)
				{
					$drv_id		 = AppTokens::getEntityByUserInfo($data['scq_created_by_uid'], $data['scq_created_by_type']);
					$driverModel = Drivers::model()->mergedDriverId($drv_id);
					$created	 = $driverModel->drv_name;
					$entity_type = "Driver";
					$entity_id	 = $driverModel->drv_id;
				}
				else if ($data['scq_created_by_type'] == 4)
				{
					$created	 = $data['CreatedCsrName'];
					$entity_type = "Admin";
					$entity_id	 = $data['adp_adm_id'];
				}
				$rowArray['created_date']		 = date("d-m-Y H:i:s", strtotime($data['createDate']));
				$rowArray['created_by']			 = $created;
				$rowArray['entity_type']		 = $entity_type;
				$rowArray['entity_id']			 = $entity_id;
				$rowArray['creation_comments']	 = $data['scq_creation_comments'];
				$rowArray['followup_date']		 = $data['followUpdDate'];
				$rowArray['assign_date']		 = $data['assignedDate'];
				$rowArray['time_toassigned']	 = '';
				if (($data['followUpdDate'] != null) && ($data['assignedDate'] != null))
				{
					$follow_from_time			 = strtotime($data['followUpdDate']);
					$assigned_to_time			 = strtotime($data['assignedDate']);
					$mintue						 = round(abs($assigned_to_time - $follow_from_time) / 60, 2);
					$rowArray['time_toassigned'] = Filter::getDurationbyMinute($mintue, 1);
				}
				$rowArray['closed_date'] = '';
				if ($data['closedDate'] != null)
				{
					$rowArray['closed_date'] = $data['closedDate'] . " " . $data['ClosedCsrName'] . " (EmpID : " . $data['ClosedCsrempCode'] . ")";
				}
				else
				{
					if ($data['scq_disposition_comments'] != null)
					{
						if ($data['scq_created_by_type'] == 1)
						{
							$rowArray['closed_date'] = $data['usr_name'];
						}
						else if ($data['scq_created_by_type'] == 2)
						{
							$rowArray['closed_date'] = $data['vnd_name'];
						}
						else if ($data['scq_created_by_type'] == 3)
						{
							$rowArray['closed_date'] = $data['drv_name'];
						}
					}
				}
				$rowArray['disposition_comments']	 = $data['scq_disposition_comments'];
				$rowArray['timeto_close']			 = '';
				if (($data['closedDate'] != null) && ($data['assignedDate'] != null))
				{
					$to_time					 = strtotime($data['closedDate']);
					$from_time					 = strtotime($data['assignedDate']);
					$mintue						 = round(abs($to_time - $from_time) / 60, 2);
					$rowArray['timeto_close']	 = Filter::getDurationbyMinute($mintue, 1);
				}
				$row1 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$userInfo		 = UserInfo::getInstance();
		$dataProvider	 = ServiceCallQueue::model()->cbrDetailsReport($followUps, $userInfo);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('cbrdetailsreport', array('followUps' => $followUps, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	public function actionServiceRequests($qry = [])
	{
		$row = Report::getRoleAccess(75);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle						 = "Service Requests Reports ";
		$csr									 = UserInfo::getUserId();
		$csrTeam								 = Admins::getTeamid($csr);
		$model									 = new ServiceCallQueue();
		$isFollowUpOpen							 = $model->isFollowUpOpen					 = 1;
		$isDue24								 = $model->isDue24							 = 0;
		$model->scq_to_be_followed_up_by_type	 = 1;
		$model->scq_to_be_followed_up_by_id		 = $csrTeam;
		$req									 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr									 = $req->getParam('ServiceCallQueue');
			$csrTeam								 = $arr['scq_to_be_followed_up_by_id'];
			$model->scq_to_be_followed_up_by_id		 = $csrTeam;
			$isFollowUpOpen							 = $arr['isFollowUpOpen'] == 1 ? 1 : 0;
			$model->isFollowUpOpen					 = $isFollowUpOpen;
			$isDue24								 = $arr['isDue24'] == 1 && $isFollowUpOpen == 1 ? 1 : 0;
			$model->isDue24							 = $isDue24;
			$model->search							 = $search									 = !empty($arr['search']) ? $arr['search'] : "";
			$model->requestedBy						 = $arr['requestedBy'];
			$model->custId							 = $arr['custId'];
			$model->vendId							 = $arr['vendId'];
			$model->drvId							 = $arr['drvId'];
			$model->adminId							 = $arr['adminId'];
			$model->agntId							 = $arr['agntId'];
			$model->scq_to_be_followed_up_by_type	 = $arr['scq_to_be_followed_up_by_type'];
			$model->scq_to_be_followed_up_by_id		 = $arr['scq_to_be_followed_up_by_id'];
			$model->isGozen							 = $arr['isGozen'];
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$arr['scq_to_be_followed_up_by_id']		 = Yii::app()->request->getParam('scq_to_be_followed_up_by_id');
			$arr['isFollowUpOpen']					 = Yii::app()->request->getParam('isFollowUpOpen');
			$arr['isDue24']							 = Yii::app()->request->getParam('isDue24');
			$arr['search']							 = Yii::app()->request->getParam('search');
			$arr['requestedBy']						 = Yii::app()->request->getParam('requestedBy');
			$arr['custId']							 = Yii::app()->request->getParam('custId');
			$arr['vendId']							 = Yii::app()->request->getParam('vendId');
			$arr['drvId']							 = Yii::app()->request->getParam('drvId');
			$arr['adminId']							 = Yii::app()->request->getParam('adminId');
			$arr['agntId']							 = Yii::app()->request->getParam('agntId');
			$arr['scq_to_be_followed_up_by_type']	 = Yii::app()->request->getParam('scq_to_be_followed_up_by_type');
			$arr['isGozen']							 = Yii::app()->request->getParam('isGozen');
			$jsonArr								 = array(
				"params" => $arr,
				"keys"	 => array
					(
					'Service Id',
					'Created by',
					'Instructions',
					'FollowUp With',
					'Follow up By Team',
					'Follow up CSR Name',
					'Remarks',
					'Created on',
					'Due date',
					'Related Booking',
					'Actions'
				)
			);
			$filename								 = "ServiceRequests_" . date('YmdHis') . ".csv";
			$expiryDate								 = Date('Y-m-d', strtotime('+15 days'));
			ReportExport::CreateRequest($jsonArr, 75, $filename, $expiryDate, 1, UserInfo::getUserId());
		}
		$dataProvider	 = $model->getInternals($isDue24, $search, $isFollowUpOpen, $command		 = false);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 200]);
		$this->render('internalCbr', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	public function actionServiceRequestsOwn($qry = [])
	{
		$row = Report::getRoleAccess(75);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle						 = "Service Requests Reports ";
		$csr									 = UserInfo::getUserId();
		$csrTeam								 = Admins::getTeamid($csr);
		$model									 = new ServiceCallQueue();
		$isFollowUpOpen							 = $model->isFollowUpOpen					 = 1;
		$isDue24								 = $model->isDue24							 = 0;
		$model->isGozen							 = Yii::app()->user->getId();
		$model->scq_to_be_followed_up_by_type	 = 2;
		$req									 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr									 = $req->getParam('ServiceCallQueue');
			$csrTeam								 = $arr['scq_to_be_followed_up_by_id'];
			$model->scq_to_be_followed_up_by_id		 = $csrTeam;
			$isFollowUpOpen							 = $arr['isFollowUpOpen'] == 1 ? 1 : 0;
			$model->isFollowUpOpen					 = $isFollowUpOpen;
			$isDue24								 = $arr['isDue24'] == 1 && $isFollowUpOpen == 1 ? 1 : 0;
			$model->isDue24							 = $isDue24;
			$model->search							 = $search									 = !empty($arr['search']) ? $arr['search'] : "";
			$model->requestedBy						 = $arr['requestedBy'];
			$model->custId							 = $arr['custId'];
			$model->vendId							 = $arr['vendId'];
			$model->drvId							 = $arr['drvId'];
			$model->adminId							 = $arr['adminId'];
			$model->agntId							 = $arr['agntId'];
			$model->scq_to_be_followed_up_by_type	 = $arr['scq_to_be_followed_up_by_type'];
			$model->scq_to_be_followed_up_by_id		 = $arr['scq_to_be_followed_up_by_id'];
			$model->isGozen							 = $arr['isGozen'];
		}
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
		{
			$arr['scq_to_be_followed_up_by_id']		 = Yii::app()->request->getParam('scq_to_be_followed_up_by_id');
			$arr['isFollowUpOpen']					 = Yii::app()->request->getParam('isFollowUpOpen');
			$arr['isDue24']							 = Yii::app()->request->getParam('isDue24');
			$arr['search']							 = Yii::app()->request->getParam('search');
			$arr['requestedBy']						 = Yii::app()->request->getParam('requestedBy');
			$arr['custId']							 = Yii::app()->request->getParam('custId');
			$arr['vendId']							 = Yii::app()->request->getParam('vendId');
			$arr['drvId']							 = Yii::app()->request->getParam('drvId');
			$arr['adminId']							 = Yii::app()->request->getParam('adminId');
			$arr['agntId']							 = Yii::app()->request->getParam('agntId');
			$arr['scq_to_be_followed_up_by_type']	 = Yii::app()->request->getParam('scq_to_be_followed_up_by_type');
			$arr['isGozen']							 = Yii::app()->request->getParam('isGozen');
			$jsonArr								 = array(
				"params" => $arr,
				"keys"	 => array
					(
					'ID',
					'Created by',
					'Instructions',
					'FollowUp With',
					'Follow up By Team',
					'Follow up CSR Name',
					'Remarks',
					'Created on',
					'Due date',
					'Related Booking',
					'Actions'
				)
			);
			$filename								 = "ServiceRequests_" . date('YmdHis') . ".csv";
			$expiryDate								 = Date('Y-m-d', strtotime('+15 days'));
			ReportExport::CreateRequest($jsonArr, 75, $filename, $expiryDate, 1, UserInfo::getUserId());
		}
		$dataProvider = $model->getInternals($isDue24, $search, $isFollowUpOpen);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 200]);
		$this->render('internalCbr', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	/**
	 * This function is used to fetch all list and sort through CSR or by TEAMS
	 */
	public function actionFetchList()
	{
		$row = Report::getRoleAccess(88);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Team List";
		$csrId			 = Yii::app()->request->getParam('csr');
		$teamId			 = Yii::app()->request->getParam('teams');
		$model			 = new TeamQueueMapping();
		$model->csrList	 = empty($csrId) ? -1 : $csrId;
		$model->teamList = empty($teamId) ? -1 : $teamId;
		$dataProvider	 = $model->getTeamQueue($csrId, $teamId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pageSize' => 100]);
		$this->render('queueMappingList', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actioncsrPerformanceReport()
	{
		$this->pageTitle		 = "Lead CSR Performance Report";
		$followUps				 = new ServiceCallQueue();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;

		$req = Yii::app()->request;

		if ($req->getParam('ServiceCallQueue'))
		{
			$arr					 = $req->getParam('ServiceCallQueue');
			$date1					 = $arr['from_date'];
			$fromdate				 = new DateTime($date1);
			$followUps->from_date	 = $fromdate->format('Y-m-d');
			$date2					 = $arr['to_date'];
			$todate					 = new DateTime($date2);
			$followUps->to_date		 = $todate->format('Y-m-d');
		}

		$dataProvider = ServiceCallQueue::model()->csrLeadPerformanceReport($followUps);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('csrPerformanceReport', array('followUps' => $followUps, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionScqReport()
	{
		$row = Report::getRoleAccess(94);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle	 = "Service Center Queues ";
		$booksub			 = new BookingSub();
		$booksub->from_date	 = date("Y-m-d") . ' 00:00:00';
		$booksub->to_date	 = date("Y-m-d") . ' 23:59:59';
		$req				 = Yii::app()->request;
		$result				 = array();
		if ($req->getParam('BookingSub'))
		{
			$arr				 = $req->getParam('BookingSub');
			$date1				 = $arr['date'];
			$booksub->from_date	 = DateTimeFormat::DatePickerToDate($date1) . ' 00:00:00';
			$booksub->to_date	 = DateTimeFormat::DatePickerToDate($date1) . ' 23:59:59';
		}
		else
		{
			$date1 = DateTimeFormat::DateToLocale(date());
		}
		$booksub->date	 = $date1;
		$data			 = ServiceCallQueue::scqreport($booksub->from_date, $booksub->to_date);
		$this->render('cbrreport', array('data' => $data, 'booksub' => $booksub, 'roles' => $row), false, true);
	}

	public function actioncbrCloseReport()
	{
		$row = Report::getRoleAccess(95);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "CBR Close Report";
		$followup		 = new ServiceCallQueue();
		$req			 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr									 = $req->getParam('ServiceCallQueue');
			$date1									 = $arr['date'];
			$followup->date							 = $date1;
			$followup->date1						 = DateTimeFormat::DatePickerToDate($date1) . ' 00:00:00';
			$followup->date2						 = DateTimeFormat::DatePickerToDate($date1) . ' 23:59:59';
			$followup->queueType					 = $arr['queueType'];
			$followup->scq_to_be_followed_up_by_id	 = $arr['scq_to_be_followed_up_by_id'];
		}
		else
		{
			$followup->date							 = empty($req->getParam('date')) ? DateTimeFormat::DateToLocale(date()) : $req->getParam("date");
			$date1									 = $followup->date;
			$followup->date1						 = DateTimeFormat::DatePickerToDate($followup->date) . ' 00:00:00';
			$followup->date2						 = DateTimeFormat::DatePickerToDate($followup->date) . ' 23:59:59';
			$followup->queueType					 = null;
			$followup->scq_to_be_followed_up_by_id	 = empty($req->getParam('team')) ? 0 : $req->getParam('team');
		}
		$dataProvider = ServiceCallQueue::cbrCloseReport($followup->date, $followup->date1, $followup->date2, $followup->queueType, $followup->scq_to_be_followed_up_by_id);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('cbrclosesreport', array('followup' => $followup, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	/**
	 * This function is used showing  CBR Statistical  Details Data Report
	 */
	public function actionCbrStaticalDetailsData()
	{
		$row = Report::getRoleAccess(96);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "CBR Statistical Details Report";
		$request		 = Yii::app()->request;
		$fromdate		 = empty($request->getParam('fromdate')) ? date("Y-m-d") : $request->getParam('fromdate');
		$todate			 = empty($request->getParam('todate')) ? date("Y-m-d") : $request->getParam('todate');
		$result			 = ServiceCallQueue::getCbrStaticalDetailsData($fromdate, $todate);
		$this->renderAuto('cbrstaticaldetailsdata', array('result' => $result, 'roles' => $row), false, false);
		Yii::app()->end();
	}

	/**
	 * This function is used showing  CBR Statistical  close Data Report
	 */
	public function actionCbrStaticalCloseData()
	{
		$row = Report::getRoleAccess(97);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "CBR Statistical Close Report";
		$request		 = Yii::app()->request;
		$fromdate		 = empty($request->getParam('date')) ? date("Y-m-d") : DateTimeFormat::DatePickerToDate($request->getParam('date'));
		$todate			 = empty($request->getParam('date')) ? date("Y-m-d") : DateTimeFormat::DatePickerToDate($request->getParam('date'));
		$result			 = ServiceCallQueue::getCbrStaticalCloseData($fromdate, $todate);
		$this->renderAuto('cbrstaticalclosedata', array('result' => $result, 'roles' => $row), false, false);
		Yii::app()->end();
	}

	/**
	 * This function is used showing  team Statistical  Data 
	 */
	public function actionteamStaticalData()
	{
		$row = Report::getRoleAccess(98);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Team Wise Statistical Report";
		$result			 = ServiceCallQueue::getStaticalDataByQueueId(ServiceCallQueue::TYPE_IMNTERNAL);
		$this->renderAuto('teamstaticaldata', array('result' => $result, 'roles' => $row), false, false);
		Yii::app()->end();
	}

	public function actiondispatchFollowUp()
	{
		$row = Report::getRoleAccess(100);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Dispatch FollowUp Report";
		$followup		 = new ServiceCallQueue();
		$req			 = Yii::app()->request;
		if ($req->getParam('ServiceCallQueue'))
		{
			$arr					 = $req->getParam('ServiceCallQueue');
			$followUps->from_date	 = $arr['from_date'] != null ? $arr['from_date'] . ' 00:00:00' : date("Y-m-d") . ' 00:00:00';
			$followUps->to_date		 = $arr['to_date'] != null ? $arr['to_date'] . ' 23:59:59' : date("Y-m-d") . ' 23:59:59';
		}
		else
		{
			$followUps->from_date	 = date('Y-m-d', strtotime('-30 days'));
			$followUps->to_date		 = date("Y-m-d");
		}

		if (isset($_REQUEST['export']))
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DispatchFollowUpReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DispatchFollowUpReport_" . date('YmdHi') . ".csv";
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

			$rows	 = ServiceCallQueue::DispatchFollowUp($date1, $date2, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Pickup Date', 'Auto Vendor Followup', 'Manual Vendor Followup', 'GOZEN', 'CLOSE DATE']);
			foreach ($rows as $data)
			{
				$rowArray					 = array();
				$rowArray['bkg_id']			 = $data['bkg_id'];
				$rowArray['bkg_pickup_date'] = $data['bkg_pickup_date'];
				$rowArray['AUTO']			 = $data['AUTO'];
				$rowArray['MANUAL']			 = $data['MANUAL'];
				$rowArray['GOZEN']			 = $data['GOZEN'];
				$rowArray['ClosedDate']		 = $data['ClosedDate'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = ServiceCallQueue::DispatchFollowUp($followUps->from_date, $followUps->to_date);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('dispatchfollowup', array('followUps' => $followUps, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	public function actionServiceCallQueueByTeam()
	{
		$row = Report::getRoleAccess(114);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle		 = "Service Call Queue List";
		$followUps				 = new ServiceCallQueue();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;

		$req = Yii::app()->request;

		if ($req->getParam('ServiceCallQueue'))
		{
			$arr					 = $req->getParam('ServiceCallQueue');
			$teamId					 = $arr['teamList'];
			$date1					 = $arr['from_date'];
			$fromdate				 = new DateTime($date1);
			$followUps->from_date	 = $fromdate->format('Y-m-d');
			$date2					 = $arr['to_date'];
			$todate					 = new DateTime($date2);
			$followUps->to_date		 = $todate->format('Y-m-d');
		}
		else
		{
			$date1	 = date("Y-m-d", strtotime("-15 day", time()));
			$date2	 = date('Y-m-d');
		}
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$teamId		 = Yii::app()->request->getParam('export_team');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ServiceCallQueueByTeam_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DispatchFollowUpReport_" . date('YmdHi') . ".csv";
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
			$followUps->from_date	 = $date1;
			$followUps->to_date		 = $date2;
			$followUps->teamList	 = ($teamId != '') ? explode(',', $teamId) : null;

			$rows	 = ServiceCallQueue::model()->serviceCallQueueByTeam($followUps, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Followup ID', 'Item ID', 'Follow Up Date', 'Queue Type', 'Create Date', 'Create By',
				'Creation Comment', 'Assigned CSR(Employee ID)', 'Assign Date', 'Time to Assign(Mintue)',
				'Closed Date (CSR)', 'Time to Close(Mintue)', 'Disposition Comments']);
			foreach ($rows as $data)
			{
				$rowArray						 = array();
				$rowArray['FollowupId']			 = $data['FollowupId'];
				$rowArray['ItemID']				 = $data['ItemID'];
				$rowArray['followUpdDate']		 = $data['followUpdDate'];
				$rowArray['QueueType']			 = $data['QueueType'];
				$rowArray['CreateDate']			 = $data['CreateDate'];
				$rowArray['CreateBy']			 = $data['Create By'];
				$rowArray['CreateDate']			 = $data['CreateDate'];
				$rowArray['CreationComment']	 = $data['Creation Comment'];
				$rowArray['AssignedCSR']		 = $data['Assigned CSR(Employee ID)'];
				$rowArray['AssignDate']			 = $data['Assign Date'];
				$rowArray['TimetoAssign']		 = $data['Time to Assign(Mintue)'];
				$rowArray['ClosedDate']			 = $data['Closed Date (CSR)'];
				$rowArray['TimetoClose']		 = $data['Time to Close(Mintue)'];
				$rowArray['DispositionComments'] = $data['Disposition Comments'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;
		$followUps->teamList	 = empty($teamId) ? -1 : $teamId;

		$dataProvider = ServiceCallQueue::model()->serviceCallQueueByTeam($followUps);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('serviceCallQueueTeam', array('followUps' => $followUps, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	public function actionServiceCallQueueByClosedDate()
	{
		$row = Report::getRoleAccess(115);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle		 = "Service Call Queue Closed Date";
		$followUps				 = new ServiceCallQueue();
		$date1					 = date('Y-m-d');
		$date2					 = date('Y-m-d');
		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;

		$req = Yii::app()->request;

		if ($req->getParam('ServiceCallQueue'))
		{
			$arr					 = $req->getParam('ServiceCallQueue');
			$teamId					 = $arr['teamList'];
			$date1					 = $arr['from_date'];
			$fromdate				 = new DateTime($date1);
			$followUps->from_date	 = $fromdate->format('Y-m-d');
			$date2					 = $arr['to_date'];
			$todate					 = new DateTime($date2);
			$followUps->to_date		 = $todate->format('Y-m-d');
		}
		else
		{
			$date1	 = date("Y-m-d", strtotime("-15 day", time()));
			$date2	 = date('Y-m-d');
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$date1		 = Yii::app()->request->getParam('export_fromdate') . ' 00:00:00';
			$date2		 = Yii::app()->request->getParam('export_todate') . ' 23:59:59';
			$teamId		 = Yii::app()->request->getParam('export_team');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ServiceCallQueueByClosedDate_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DispatchFollowUpReport_" . date('YmdHi') . ".csv";
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
			$followUps->from_date	 = $date1;
			$followUps->to_date		 = $date2;
			$followUps->teamList	 = ($teamId != '') ? explode(',', $teamId) : null;

			$rows	 = ServiceCallQueue::model()->serviceCallQueueByClosedDate($followUps, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Followup ID', 'Item ID', 'Follow Up Date', 'Queue Type', 'Create Date', 'Create By',
				'Creation Comment', 'Assigned CSR(Employee ID)', 'Assign Date', 'Time to Assign(Mintue)',
				'Closed Date (CSR)', 'Time to Close(Mintue)', 'Disposition Comments']);
			foreach ($rows as $data)
			{
				$rowArray						 = array();
				$rowArray['FollowupId']			 = $data['FollowupId'];
				$rowArray['ItemID']				 = $data['ItemID'];
				$rowArray['followUpdDate']		 = $data['followUpdDate'];
				$rowArray['QueueType']			 = $data['QueueType'];
				$rowArray['CreateDate']			 = $data['CreateDate'];
				$rowArray['CreateBy']			 = $data['Create By'];
				$rowArray['CreateDate']			 = $data['CreateDate'];
				$rowArray['CreationComment']	 = $data['Creation Comment'];
				$rowArray['AssignedCSR']		 = $data['Assigned CSR(Employee ID)'];
				$rowArray['AssignDate']			 = $data['Assign Date'];
				$rowArray['TimetoAssign']		 = $data['Time to Assign(Mintue)'];
				$rowArray['ClosedDate']			 = $data['Closed Date (CSR)'];
				$rowArray['TimetoClose']		 = $data['Time to Close(Mintue)'];
				$rowArray['DispositionComments'] = $data['Disposition Comments'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$followUps->from_date	 = $date1;
		$followUps->to_date		 = $date2;
		$followUps->teamList	 = empty($teamId) ? -1 : $teamId;

		$dataProvider = ServiceCallQueue::model()->serviceCallQueueByClosedDate($followUps);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('serviceCallQueueClosedDate', array('followUps' => $followUps, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

}
