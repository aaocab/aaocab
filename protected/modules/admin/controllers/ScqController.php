<?php

use BookingLog;
use LeadLog;
use UserLog;

class ScqController extends Controller
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
		return array
			(
			array
				(
				'application.filters.HttpsFilter',
				'bypass' => false
			),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,pickup,rates', // we only allow deletion via POST request
			array
				(
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

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('list', 'add', 'registerlog', 'ServiceCallBackDoc', 'details', 'ctrScq', 'gozens', 'vnds', 'drvs', 'customer', 'Reschedule', 'showCallbackQue', 'teamStaticalData', 'cbrStaticalDetailsData', 'cbrStaticalCloseData', 'OnlineCsr', 'teams'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('*'),
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
			$ri	 = array("/getAllEmployees", "/getAllTeams", "/getAllPersons", "/getAllVendors", "/getAllDrivers", "/getAllCustomers", "/getAllAgents", "/getBookingDetails", "/add", "/getCBRcount", "/cancelCallBack");

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.post.add.render', function () {
			return $this->renderJSON($this->addServiceRequest());
		});
		$this->onRest('req.get.getAllTeams.render', function () {
			return $this->renderJSON($this->getTeams());
		});
		$this->onRest('req.get.getAllPersons.render', function () {
			return $this->renderJSON($this->getPersons());
		});
		$this->onRest('req.post.getBookingDetails.render', function () {
			return $this->renderJSON($this->getBookingDetails());
		});
	}

	/*
	 * This function is used for getting follow up log details
	 */

	public function actionlog()
	{
		$id				 = Yii::app()->request->getParam('Id');
		$dataProvider	 = FollowupLog::getLogDetails($id);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderAuto('log', array('dataProvider' => $dataProvider), false, true);
	}

	public function actionListId()
	{
		$fwpId			 = Yii::app()->request->getParam('fwpId');
		$this->pageTitle = "Follow up Report";
	}

	/**
	 * This function is used for adding remarks and events
	 */
	public function actionadd()
	{
		$refId		 = Yii::app()->request->getParam('Id');
		$isMycall	 = Yii::app()->request->getParam('isMycall', '0');
		$model		 = new ServiceCallQueue();
		$request	 = Yii::app()->request;
		$scqModel	 = ServiceCallQueue::model()->findByPk($refId);
		$entityType	 = $scqModel->scq_to_be_followed_up_with_entity_type;
		$data		 = json_decode(Config::get("notification.send.status"), true);
		$flag		 = $data[$entityType];
		if ($request->getPost('ServiceCallQueue'))
		{
			$remarks			 = $request->getParam('ServiceCallQueue')['scq_disposition_comments'];
			$eventId			 = $request->getParam('ServiceCallQueue')['event_id'];
			$followupWith		 = $request->getParam('ServiceCallQueue')['followupWith'];
			$notificationMessage = $request->getParam('ServiceCallQueue')['scq_notification'];
			$followupWithTeam	 = 0;
			if ($followupWith == 2)
			{
				$followupWithTeam = $request->getParam('ServiceCallQueue')['followupWithTeam'];
			}
			$reFollowup	 = $eventId == 4 ? 1 : 0;
			$csr		 = UserInfo::getUserId();
			if ($eventId > 0)
			{
				$list = ServiceCallQueue::updatestate($refId, $csr, $eventId, $remarks, $reFollowup);
				if ($reFollowup == 1)
				{
					$date = date('Y-m-d', strtotime(str_replace("/", "-", $request->getParam('ServiceCallQueue')['locale_followup_date']))) . " " . date("H:i", strtotime($request->getParam('ServiceCallQueue')['locale_followup_time']));
					ServiceCallQueue::addReFollowup($refId, $date, $followupWith, $followupWithTeam);
				}
				if ($isMycall)
				{
					if ($notificationMessage && $scqModel->scq_to_be_followed_up_with_entity_id > 0)
					{
//						Notification::callBackSendNotification($refId, $notificationMessage);
					}
					$this->redirect(array('lead/mycall'));
				}
				$this->redirect('list');
			}
			else
			{
				$model->addError('event_id', 'Select the event from list');
			}
		}
		$this->renderAuto('add', array('model' => $model, 'refId' => $refId, 'scqModel' => $scqModel, 'flag' => $flag), false, true);
	}

	/**
	 * This function is used closing the service call queue  
	 */
	public function actionRegisterlog()
	{
		$refId			 = Yii::app()->request->getParam('refId');
		$remarks		 = Yii::app()->request->getParam('remarks');
		$eventId		 = Yii::app()->request->getParam('eventId');
		$isReSchedule	 = Yii::app()->request->getParam('flag');
		$remarks		 = ($isReSchedule == 0) ? $remarks . ".[call closed]" : $remarks . ".[call rescheduled]";
		$status			 = ($isReSchedule == 0) ? 2 : 4;
		$csr			 = UserInfo::getUserId();
		$count			 = ServiceCallQueue::updatestate($refId, $csr, $status, $remarks);
		$detail			 = ServiceCallQueue::model()->detail($refId);
		if (!empty($detail))
		{
			$jsonDecode				 = json_decode($detail['scq_additional_param']);
			$manualAssignmentFlag	 = $jsonDecode->manualAssignmentAsk;
			if ($manualAssignmentFlag == 1)
			{
				$modelBooking = Booking::model()->findByPk($detail['scq_related_bkg_id']);
				if ($modelBooking->bkgPref->bpr_askmanual_assignment == 1)
				{
					$modelBooking->bkgPref->bpr_askmanual_assignment = 0;
					$modelBooking->bkgPref->save();
				}
			}
		}
		echo json_encode(['result' => $count]);
	}

	/**
	 * This function is used returning the details of service call queue
	 */
	public function actionDetails()
	{
		$refId		 = Yii::app()->request->getParam('refId');
		$detail		 = ServiceCallQueue::model()->detail($refId);
		$returnArr	 = ['success' => true, 'detail' => $detail];
		echo json_encode($returnArr);
	}

	/**
	 * This function is used  saving the service call queue data from admin panel follollowp up form
	 */
	public function actionStoreFollowUps()
	{
		$success		 = false;
		$reqData		 = [];
		$entityId		 = 0;
		$booking_id		 = Yii::app()->request->getParam('bkg_id');
		$team			 = Yii::app()->request->getParam('team');
		$followWith		 = Yii::app()->request->getParam('followWith');
		$followupDt		 = Yii::app()->request->getParam('followUpDt');
		$followupTime	 = Yii::app()->request->getParam('followUpTime');
		$desc			 = Yii::app()->request->getParam('description');
		$user			 = Yii::app()->request->getParam('user');
		$parent			 = Yii::app()->request->getParam('parent');
		$bookingModel	 = Booking::model()->findByPk($booking_id);
		$followupDateVal = DateTimeFormat::DatePickerToDate($followupDt);
		$followupTimeVal = DateTime::createFromFormat('h:i A', $followupTime)->format('H:i:00');
		$followupDate	 = $followupDateVal . ' ' . $followupTimeVal;

		if ($followWith == 1)
		{
			$entityId	 = $bookingModel->bkgUserInfo->bkg_user_id;
			$entityType	 = UserInfo::TYPE_CONSUMER;
		}
		else if ($followWith == 2)
		{
			$vndId		 = $bookingModel->bkgBcb->bcb_vendor_id;
			$entityId	 = $vndId;
			$entityType	 = UserInfo::TYPE_VENDOR;
		}
		else if ($followWith == 3)
		{
			$drvId		 = $bookingModel->bkgBcb->bcb_driver_id;
			$entityId	 = $drvId;
			$entityType	 = UserInfo::TYPE_DRIVER;
		}
		else if ($followWith == 5)
		{
			$agentId	 = $bookingModel->bkg_agent_id;
			$entityId	 = $agentId;
			$entityType	 = UserInfo::TYPE_AGENT;
		}
		else if ($followWith == 6)
		{
			$entityType	 = UserInfo::TYPE_INTERNAL;
			$entityId	 = 0;
		}
		$model									 = new ServiceCallQueue();
		$model->scq_creation_comments			 = $desc;
		$model->scq_to_be_followed_up_with_value = $bookingModel->bkgUserInfo->bkg_contact_no;
		$model->scq_related_bkg_id				 = $bookingModel->bkg_booking_id;
		$model->scq_follow_up_date_time			 = $followupDate;
		$model->scq_prev_or_originating_followup = $parent;
		$model->scq_to_be_followed_up_by_type	 = 1;
		$model->scq_to_be_followed_up_by_id		 = $team;
		if ($entityType == 11)
		{
			$model->scq_follow_up_queue_type			 = 9;
			$model->scq_to_be_followed_up_with_entity_id = 0;
			$model->contactRequired						 = 0;
		}
		else
		{
			$model->scq_follow_up_queue_type			 = 2;
			$model->contactRequired						 = 1;
			$model->scq_to_be_followed_up_with_entity_id = ContactProfile::getByEntityId($entityId, $entityType);
		}
		$follwup						 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
		$list							 = ServiceCallQueue::getfollowUpsByBkg($booking_id);
		$dt								 = $follwup->getData();
		$params["blg_booking_status"]	 = $bookingModel->bkg_status;
		$params['blg_ref_id']			 = $dt['followupId'];
		BookingLog::model()->createLog($booking_id, $desc, UserInfo::getInstance(), BookingLog::FOLLOWUP_CREATE, false, $params);
		$success						 = ($dt['followupId']) ? true : false;
		$returnArr						 = ['success' => $success, 'followupId' => $dt['followupId'], 'list' => $list];
		echo json_encode($returnArr);
	}

	public function actionFollowUps()
	{
		$success	 = false;
		$reqData	 = [];
		$booking_id	 = Yii::app()->request->getParam('bkg_id');
		$list		 = ServiceCallQueue::getfollowUpsByBkg($booking_id);
		$success	 = (count($list) > 0) ? true : false;
		$returnArr	 = ['success' => $success, 'list' => $list];
		echo json_encode($returnArr);
	}

	/**
	 * This function is used for getting followup form value
	 */
	public function actionCtrScq()
	{
		$ex_scqId		 = 0;
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$model			 = new ServiceCallQueue();
		$callBackModel	 = new CallBackDocuments();
		$scqId			 = Yii::app()->request->getParam('scqId');
		$isReschedule	 = Yii::app()->request->getParam('isReschedule');
		//$_REQUEST;
		$view_booking_id = Yii::app()->request->getParam('bkg_id');
		if ($view_booking_id)
		{
			$model->isBooking = 1;
		}

		if ($scqId)
		{
			$model				 = ServiceCallQueue::model()->findByPk($scqId);
			$model->followUpby	 = $model->scq_to_be_followed_up_by_type;
			if ($model->scq_related_bkg_id)
			{
				$model->isBooking = 1;
			}

			$model->scqType	 = ($model->scq_to_be_followed_up_with_value == 0) ? 1 : 2;
			$csr			 = UserInfo::getUserId();
			$ex_remarks		 = $model->scq_creation_comments . ".[call rescheduled]";
			if ($isReschedule == 1)
			{
				ServiceCallQueue::updatestate($scqId, $csr, 4, $ex_remarks);
				$ex_scqId = $scqId;
			}
		}
		$request		 = Yii::app()->request;
		$getbookingID	 = $request->getPost('ServiceCallQueue')['scq_related_bkg_id'];
		if (!$getbookingID)
		{
			$getbookingID = $view_booking_id;
		}
		$booking_id = Booking::getBookingId($getbookingID);

		if ($request->getPost('ServiceCallQueue'))
		{
			$response = ServiceCallQueue::model()->capture(Yii::app()->request, $ex_scqId);
			if ($response)
			{

				if ($response)
				{
					if ($booking_id)
					{

						$this->redirect('/admpnl/booking/view?id=' . $booking_id . '');
					}
					else
					{
						$this->redirect('/admpnl/index/dashboard?scq=' . $response . '');
					}
				}
			}
		}
		if ($outputJs)
		{
			$this->renderAuto("ctrScq", ['scq' => $model, 'callBackModel' => $callBackModel, 'bkgId' => $booking_id, 'scqID' => $response], false, true);
		}
	}

	/**
	 * This function is used for getting all  gozons/admin list
	 */
	public function actionGozens()
	{
		$query		 = Yii::app()->request->getParam('q');
		$gozenId	 = Yii::app()->request->getParam('gozen');
		$airportShow = Yii::app()->request->getParam('apshow', 0);
		$dataGozen	 = Yii::app()->cache->get("alllookupGozenlistbyQuery_{$query}_{$gozenId}_{$airportShow}");
		if ($dataGozen === false)
		{
			$dataGozen = ServiceCallQueue::model()->getGozensbyQuery($query, $gozenId, $airportShow);
			Yii::app()->cache->set("alllookupGozenlistbyQuery_{$query}_{$gozenId}_{$airportShow}", $dataGozen, 21600);
		}
		echo $dataGozen;
		Yii::app()->end();
	}

	/**
	 * This function is used for getting all  vendors list
	 */
	public function actionVnds()
	{
		$query		 = Yii::app()->request->getParam('q');
		$vndId		 = Yii::app()->request->getParam('vndId');
		$airportShow = Yii::app()->request->getParam('apshow', 0);
		$dataVendor	 = Yii::app()->cache->get("alllookupVendorlistbyQuery_{$query}_{$vndId}_{$airportShow}");
		if ($dataVendor === false)
		{
			$dataVendor = ServiceCallQueue::model()->getvndsbyQuery($query, $vndId, $airportShow);
			Yii::app()->cache->set("alllookupVendorlistbyQuery_{$query}_{$vndId}_{$airportShow}", $dataVendor, 21600);
		}
		echo $dataVendor;

		Yii::app()->end();
	}

	/**
	 * This function is used for getting all  drivers list
	 */
	public function actionDrvs()
	{
		$query		 = Yii::app()->request->getParam('q');
		$drvId		 = Yii::app()->request->getParam('drvId');
		$airportShow = Yii::app()->request->getParam('apshow', 0);
		$dataDriver	 = Yii::app()->cache->get("alllookupDriverlistbyQuery_{$query}_{$drvId}_{$airportShow}");
		if ($dataDriver === false)
		{
			$dataDriver = ServiceCallQueue::model()->getdrvsbyQuery($query, $drvId, $airportShow);
			Yii::app()->cache->set("alllookupDriverlistbyQuery_{$query}_{$drvId}_{$airportShow}", $dataDriver, 21600);
		}
		echo $dataDriver;
		Yii::app()->end();
	}

	/**
	 * This function is used for getting all  customers list
	 */
	public function actionCustomer()
	{
		$query			 = Yii::app()->request->getParam('q');
		$cust			 = Yii::app()->request->getParam('cust');
		$airportShow	 = Yii::app()->request->getParam('apshow', 0);
		$dataCustomer	 = Yii::app()->cache->get("alllookupCustomerlistbyQuery_{$query}_{$cust}_{$airportShow}");
		if ($dataCustomer === false)
		{
			$dataCustomer = ServiceCallQueue::model()->getCustomerbyQuery($query, $cust, $airportShow);
			Yii::app()->cache->set("alllookupCustomerlistbyQuery_{$query}_{$cust}_{$airportShow}", $dataCustomer, 21600);
		}
		echo $dataCustomer;
		Yii::app()->end();
	}

	/**
	 * This function is used for getting followup details
	 */
	public function actionList()
	{
		$this->pageTitle = "CallBackList";
		$model			 = new ServiceCallQueue();
		$fwpId			 = (Yii::app()->request->getParam('fwpId') > 0 ) ? Yii::app()->request->getParam('fwpId') : 0;
		$refId			 = (Yii::app()->request->getParam('refId') > 0 ) ? Yii::app()->request->getParam('refId') : 0;
		$isMycall		 = (Yii::app()->request->getParam('isMycall') > 0 ) ? Yii::app()->request->getParam('isMycall') : 0;
		$contactId		 = 0;
		if ($isMycall > 0 && $fwpId > 0)
		{
			$modelDetails		 = ServiceCallQueue::model()->findByPk($fwpId);
			$contactId			 = $modelDetails->scq_to_be_followed_up_with_contact != null && $modelDetails->scq_to_be_followed_up_with_contact > 0 ? $modelDetails->scq_to_be_followed_up_with_contact : 0;
			$fwpId				 = in_array($modelDetails->scq_follow_up_queue_type, [19, 32, 33]) ? 0 : $fwpId;
			$isMultiQueueAllowed = Config::get('SCQ.isMultiQueueAllowed');
			if ($isMultiQueueAllowed)
			{
				$fwpId = !in_array($modelDetails->scq_follow_up_queue_type, [16, 17, 20, 21, 34, 42, 43, 44, 45]) ? 0 : $fwpId;
			}
			else
			{
				$fwpId = in_array($modelDetails->scq_follow_up_queue_type, [19, 32, 33]) ? 0 : $fwpId;
			}
		}
		$userId			 = UserInfo::getUserId();
		$dataProvider	 = ServiceCallQueue::fetchList($fwpId, $refId, $isMycall, $contactId, $userId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderAuto('list', array('model' => $model, 'dataProvider' => $dataProvider, 'isMycall' => $isMycall));
	}

	public function actionShowCallbackQue()
	{
		$followupId		 = Yii::app()->request->getParam('followupId');
		$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
		$queueData		 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
		$queNo			 = $queueData['queNo'];
		$waitTime		 = $queueData['waitTime'];
		$contactNumber	 = $fpModel->scq_to_be_followed_up_with_value;
		$followupCode	 = $fpModel->scq_unique_code;
		$this->renderAuto('callbackConfirm', array('success' => $success, 'followupCode' => $followupCode, 'followupId' => $followupId, 'queNo' => $queNo, 'contactNumber' => $contactNumber, 'waitTime' => $waitTime), false, false);
		Yii::app()->end();
	}

	/**
	 * This function is used showing  team Statistical  Data 
	 */
	public function actionteamStaticalData()
	{
		$this->pageTitle = "Team Wise Statistical Report";
		$result			 = ServiceCallQueue::getStaticalDataByQueueId(ServiceCallQueue::TYPE_IMNTERNAL);
		$this->renderAuto('teamstaticaldata', array('result' => $result), false, false);
		Yii::app()->end();
	}

	/**
	 * This function is used showing  CBR Statistical  Details Data Report
	 */
	public function actionCbrStaticalDetailsData()
	{
		$this->pageTitle = "CBR Statistical Details Report";
		$request		 = Yii::app()->request;
		$fromdate		 = empty($request->getParam('fromdate')) ? date("Y-m-d") : $request->getParam('fromdate');
		$todate			 = empty($request->getParam('todate')) ? date("Y-m-d") : $request->getParam('todate');
		$result			 = ServiceCallQueue::getCbrStaticalDetailsData($fromdate, $todate);
		$this->renderAuto('cbrstaticaldetailsdata', array('result' => $result), false, false);
		Yii::app()->end();
	}

	/**
	 * This function is used showing  CBR Statistical  close Data Report
	 */
	public function actionCbrStaticalCloseData()
	{
		$this->pageTitle = "CBR Statistical Close Report";
		$request		 = Yii::app()->request;
		$fromdate		 = empty($request->getParam('date')) ? date("Y-m-d") : DateTimeFormat::DatePickerToDate($request->getParam('date'));
		$todate			 = empty($request->getParam('date')) ? date("Y-m-d") : DateTimeFormat::DatePickerToDate($request->getParam('date'));
		$result			 = ServiceCallQueue::getCbrStaticalCloseData($fromdate, $todate);
		$this->renderAuto('cbrstaticalclosedata', array('result' => $result), false, false);
		Yii::app()->end();
	}

	public function actionView()
	{
		$this->pageTitle = 'Followup Details';
		$scqId			 = Yii::app()->request->getParam('id');
		$models			 = ServiceCallQueue::model()->detail($scqId);
		$docImages		 = CallBackDocuments::model()->findByAttributes(['cbd_scq_id' => $models["scq_id"], 'cbd_active' => 1]);
		if (empty($models))
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$outputJs = Yii::app()->request->isAjaxRequest;
		$this->renderAuto('view', array('record' => $models, 'docImages' => $docImages), false, $outputJs);
	}

	/**
	 * This function is used for getting all team list
	 */
	public function actionTeams()
	{
		$query		 = Yii::app()->request->getParam('q');
		$teamId		 = Yii::app()->request->getParam('teamId');
		$airportShow = Yii::app()->request->getParam('apshow', 0);
		$dataTeams	 = Yii::app()->cache->get("alllookupTeamslistbyQuery_{$query}_{$teamId}_{$airportShow}");
		if ($dataTeams === false)
		{
			$dataTeams = ServiceCallQueue::model()->getTeamsbyQuery($query, $teamId, $airportShow);
			Yii::app()->cache->set("alllookupTeamslistbyQuery_{$query}_{$teamId}_{$airportShow}", $dataTeams, 21600);
		}
		echo $dataTeams;

		Yii::app()->end();
	}

	/**
	 * This function is used to fetch all list and sort through CSR or by TEAMS
	 */
	public function actionFetchList()
	{
		$this->pageTitle = "Team List";
		$csrId			 = Yii::app()->request->getParam('csr');
		$teamId			 = Yii::app()->request->getParam('teams');
		$model			 = new TeamQueueMapping();
		$model->csrList	 = empty($csrId) ? -1 : $csrId;
		$model->teamList = empty($teamId) ? -1 : $teamId;
		$dataProvider	 = $model->getTeamQueue($csrId, $teamId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pageSize' => 100]);
		$this->render('queueMappingList', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionModifyPriority()
	{
		$tqmId	 = Yii::app()->request->getParam('Id');
		$model	 = TeamQueueMapping::model()->findByPk($tqmId);
		$flag	 = false;
		if ($model == '')
		{
			$tqmId	 = 0;
			$flag	 = true;
			$model	 = new TeamQueueMapping('insert');
		}
		$request = Yii::app()->request;
		if ($request->getPost('TeamQueueMapping'))
		{
			$arrAttributes		 = $request->getPost('TeamQueueMapping');
			$teamName			 = Teams::model()->findByPk($arrAttributes['tqm_tea_id'])->tea_name;
			$queueName			 = TeamQueueMapping::model()->findByAttributes(['tqm_queue_id' => $arrAttributes['tqm_queue_id']])->tqm_queue_name;
			$model->attributes	 = $arrAttributes;
			if ($arrAttributes['tqm_queue_weight'] > 0 && $arrAttributes['tqm_priority'] > 0)
			{
				$model->tqm_queue_weight = $arrAttributes['tqm_queue_weight'];
				$model->tqm_priority	 = $arrAttributes['tqm_priority'];
				$model->tqm_tea_id		 = $arrAttributes['tqm_tea_id'];
				$model->tqm_tea_name	 = $teamName;
				$model->tqm_queue_id	 = $arrAttributes['tqm_queue_id'] ? $arrAttributes['tqm_queue_id'] : 0;
				$model->tqm_queue_name	 = $arrAttributes['queueName'] ? $arrAttributes['queueName'] : $queueName;

				$result = CActiveForm::validate($model);
				if ($result == '[]')
				{

					if (!$model->save())
					{
						goto skipAll;
					}
					$cacheid = ($tqmId > 0) ? $tqmId : $model->tqm_id;
					TeamQueueMapping::clearCache($cacheid);
					if ($model->tqm_queue_id <= 0)
					{
						$id					 = TeamQueueMapping::getMaxQueueId();
						$model->tqm_queue_id = $id + 1;
						$model->tqm_active	 = 1;
						$model->save();
					}
					if (Yii::app()->request->isAjaxRequest)
					{
						echo json_encode(['success' => $model->save()]);
						Yii::app()->user->setFlash('success', "Teams Mapping Data  added/updated successfully.");
						Yii::app()->end();
					}
				}
			}
			else
			{
				$model->addError("tqm_queue_weight", "queue/priority cannot be zero/negative");
			}
		}
		skipAll:
		$this->renderAuto('addPriority', array('model' => $model, 'flag' => $flag), false, true);
	}

	/**
	 * This is used to show  listing view for images
	 */
	public function actionServiceCallBackDoc()
	{
		$request		 = Yii::app()->request;
		$this->pageTitle = "Service request document";
		$followUpId		 = $request->getParam('id');
		if ($followUpId != "")
		{
			$dataProvider							 = CallBackDocuments::getDocImages($followUpId);
			$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
			$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
			$outputJs								 = $request->isAjaxRequest;
			$method									 = "render" . ( $outputJs ? "Partial" : "");
			$this->render('serviceCallDocs', array('dataProvider' => $dataProvider), false, $outputJs);
		}
	}

	/**
	 * This function is used to add a new request for service call queue
	 * @return type(object) $returnSet
	 */
	public function addServiceRequest()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$userId				 = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityType	 = 0;
			$entityId	 = 0;
			$contactId	 = 0;
			$cnt		 = 0;
			if (!empty($jsonObj->vendorId))
			{
				$entityType	 = UserInfo::TYPE_VENDOR;
				$entityId	 = $jsonObj->vendorId;
				$cnt++;
			}
			if (!empty($jsonObj->agentId))
			{
				$entityType	 = UserInfo::TYPE_AGENT;
				$entityId	 = $jsonObj->agentId;
				$cnt++;
			}
			if (!empty($jsonObj->driverId))
			{
				$entityType	 = UserInfo::TYPE_DRIVER;
				$entityId	 = $jsonObj->driverId;
				$cnt++;
			}
			if (!empty($jsonObj->customerId))
			{
				$entityType	 = UserInfo::TYPE_CONSUMER;
				$entityId	 = $jsonObj->customerId;
				$cnt++;
			}
			if (!empty($entityId))
			{
				$contactId = ContactProfile::getByEntityId($entityId, $entityType);
			}
			if (empty($contactId) && $cnt > 0)
			{
				throw new Exception("Sorry,no contact found.", ReturnSet::ERROR_INVALID_DATA);
			}
			$stub											 = new \Stub\common\ServiceCall();
			$scqModel										 = new ServiceCallQueue();
			$model											 = $stub->getRequestData($jsonObj, $scqModel);
			$userModel										 = UserInfo::getInstance();
			$model->scq_created_by_type						 = UserInfo::TYPE_ADMIN;
			$model->scq_created_by_uid						 = $userModel->userId;
			$model->scq_to_be_followed_up_with_entity_rating = -1;
			$model->scq_follow_up_queue_type				 = ServiceCallQueue::TYPE_IMNTERNAL;
			$model->scq_status								 = 1;
			$model->scq_platform							 = ServiceCallQueue::PLATFORM_ADMIN_CALL;
			if ($jsonObj->agentId == 18190 && $jsonObj->followUpPerson == 1)
			{
				$model->scq_to_be_followed_up_with_contact	 = 0;
				$model->scq_to_be_followed_up_with_value	 = Yii::app()->params['scqToCustomerforMMT'];
				$model->scq_to_be_followed_up_with_type		 = 2;
			}
			else
			{
				if ($jsonObj->followUpType == 1)
				{
					$entityType										 = UserInfo::TYPE_ADMIN;
					$model->scq_to_be_followed_up_with_entity_type	 = $entityType;
					$model->scq_to_be_followed_up_with_entity_id	 = 0;
					$model->scq_to_be_followed_up_with_type			 = 0;
					$model->scq_to_be_followed_up_with_value		 = 0;
				}
				else
				{
					$model->scq_to_be_followed_up_with_entity_type	 = $entityType;
					$model->scq_to_be_followed_up_with_entity_id	 = $entityId;
					$arrPhoneByPriority								 = Contact::getPhoneNoByPriority($contactId);
					if ($arrPhoneByPriority != null)
					{
						$model->scq_to_be_followed_up_with_type	 = 2;
						$model->scq_to_be_followed_up_with_value = $arrPhoneByPriority['phn_phone_no'];
					}
					else
					{
						$model->scq_to_be_followed_up_with_type	 = 1;
						$model->scq_to_be_followed_up_with_value = $contactId;
					}
					$model->scq_to_be_followed_up_with_contact = $contactId;
				}
			}
			$returnSet	 = ServiceCallQueue::model()->create($model, $entityType, ServiceCallQueue::PLATFORM_ADMIN_CALL);
			$returnArr	 = $returnSet->getData();
			$followupId	 = $returnArr['followupId'];
			if ($model->scq_related_bkg_id != null && $followupId != null)
			{
				$params['blg_ref_id'] = $model->scq_related_bkg_id;
				BookingLog::model()->createLog($model->scq_related_bkg_id, $model->scq_creation_comments, UserInfo::getInstance(), BookingLog::FOLLOWUP_CREATE, false, $params);
			}
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage('Service Request created successfully.');
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to fetch all teams Data
	 * @return type(object) $returnSet
	 */
	public function getTeams()
	{
		$returnSet = new ReturnSet();
		try
		{
			$returnArr = Teams::getByAllTeams();
			$returnSet->setStatus(true);
			$returnSet->setData($returnArr);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to get all entities type
	 * @return type(object) $returnSet
	 */
	public function getPersons()
	{
		$returnSet = new ReturnSet();
		try
		{
			$personoptions = Users::getPersonList();
			$returnSet->setStatus(true);
			$returnSet->setData($personoptions);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to get booking details based on booking id
	 * @return type(object) $returnSet
	 */
	public function getBookingDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data = Yii::app()->request->rawBody;

			$jsonObj = CJSON::decode($process_sync_data, false);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$getbookingID	 = $jsonObj->bkgId;
			$bookingID		 = Booking::getBookingId($getbookingID);
			if ($bookingID)
			{
				$model = Booking::model()->findByPk($bookingID);
				if (empty($model))
				{
					throw new Exception("Sorry,no booking found.", ReturnSet::ERROR_INVALID_DATA);
				}
				$userID		 = $model->bkgUserInfo->bkg_user_id;
				$drvId		 = $model->bkgBcb->bcb_driver_id;
				$vndId		 = $model->bkgBcb->bcb_vendor_id;
				$crtDate	 = date('d/m/Y', strtotime($model->bkg_create_date));
				$crtTime	 = date('h:i A', strtotime($model->bkg_create_date));
				$pctDate	 = date('d/m/Y', strtotime($model->bkg_pickup_date));
				$pctTime	 = date('h:i A', strtotime($model->bkg_pickup_date));
				$returnArr	 = ['userId' => $userID, 'drvId' => $drvId, 'vndId' => $vndId, 'bkgID' => $model->bkg_id, 'bookingId' => $model->bkg_booking_id, 'trip' => $model->bkg_pickup_address . '  to  ' . $model->bkg_drop_address, 'createdDate' => $crtDate . " " . $crtTime, 'pickupDate' => $pctDate . " " . $pctTime];
				$returnSet->setStatus(true);
				$returnSet->setData($returnArr, false);
			}
			else
			{
				$returnSet->setErrors('No Records Found for this search', ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function not in use currently
	 * This function is used to cancel call back
	 * @return type(object) $returnSet
	 */
	public function cancelCallBack()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$scq_id	 = $jsonObj->id;
			$userId	 = UserInfo::getUserId();
			$message = "Sorry !! But we are unable to process the request for cancellation.";
			$flag	 = ServiceCallQueue::deactivateById($userId, $scq_id);
			if ($flag)
			{
				$message = "Service Call Queue Request has been cancelled succesfully .";
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
			else
			{
				$returnSet->setMessage($message);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used showing all online csr along with cbr assigned
	 */
	public function actionOnlineCsr()
	{
		$this->pageTitle						 = "Online csr(CBR Assigned)";
		$cdt_id									 = Yii::app()->request->getParam('cdt_id');
		$followUps								 = new ServiceCallQueue();
		$followUps->scq_to_be_followed_up_by_id	 = $cdt_id > 0 ? $cdt_id : 0;
		$result									 = ServiceCallQueue::getOnlineCsr($cdt_id);
		$this->renderAuto('onlinecsr', array('result' => $result, 'followUps' => $followUps,), false, false);
		Yii::app()->end();
	}

}
