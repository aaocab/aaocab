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
			['allow', 'actions' => ['create', 'createnew', 'partnerPreference', 'confirmmobile', 'copybooking', 'multicityform', 'multicityvalidate', 'editPackage', 'unverifiedToquote', 'CsrFeedBack'], 'roles' => ['bookingAdd']],
			['allow', 'actions' => ['bkgChangeStatus'], 'roles' => ['confirmAsCashBooking']],
			['allow', 'actions' => ['changecancelreason'], 'roles' => ['changeCancelReason']],
			['allow', 'actions' => ['edit', 'edituserinfo'], 'roles' => ['bookingEdit']],
			['allow', 'actions' => ['editTravellerInfo'], 'roles' => ['editTravellerInfo']],
			['allow', 'actions' => ['editpickuptime', 'savepickuptime', 'reschedule'], 'roles' => ['reschedulePickupTime']],
			['allow', 'actions' => ['list', 'uberlist'], 'roles' => ['bookingList']],
			['allow', 'actions' => ['delbooking'], 'roles' => ['bookingDelete']],
			['allow', 'actions' => ['canbooking'], 'roles' => ['bookingCancel']],
			['allow', 'actions' => ['completebooking'], 'roles' => ['bookingComplete']],
			['allow', 'actions' => ['extraDiscountAmount'], 'roles' => ['OneTimePriceAdjustment']],
			['allow', 'actions' => ['updateVenorAmount'], 'roles' => ['changeVendorAmount']],
			['allow', 'actions' => ['updateamtnmarkcomp'], 'roles' => ['bookingCompletewithamount']],
			['allow', 'actions' => ['converttolead'], 'roles' => ['bookingCopytolead']],
			['allow', 'actions' => ['assigncabdriver'], 'roles' => ['bookingCabDetails']],
			['allow', 'actions' => ['assignvendor', 'showvendor', 'listbyids', 'listbyvhc'], 'roles' => ['bookingAssignvendor']],
			//['allow', 'actions' => ['manuallytriggerassignment'], 'roles' => ['MaxOutBooking']],
			['allow', 'actions' => ['receipt'], 'roles' => ['bookingReceipt']],
			['allow', 'actions' => ['related'], 'roles' => ['bookingRelated']],
			['allow', 'actions' => ['addremarks', 'storeFollowUps', 'followUps', 'AskManualAssignment'], 'roles' => ['bookingRemarks']],
			['allow', 'actions' => ['remindvendor'], 'roles' => ['bookingRemindvendor']],
			['allow', 'actions' => ['sendratingmail'], 'roles' => ['bookingReviewlink']],
			['allow', 'actions' => ['sendpromocode'], 'roles' => ['bookingSenddiscount']],
			['allow', 'actions' => ['settlebooking'], 'roles' => ['bookingSettle']],
			['allow', 'actions' => ['sendsmstodriver'], 'roles' => ['bookingSmsdriver']],
			['allow', 'actions' => ['canvendor'], 'roles' => ['bookingUnassignvendor']],
			['allow', 'actions' => ['undoaction', 'undocandel'], 'roles' => ['bookingUndo']],
			['allow', 'actions' => ['canvendor'], 'roles' => ['bookingVendorcancel']],
			['allow', 'actions' => ['verifybooking', 'unverifieddelbooking'], 'roles' => ['bookingVerify']],
			['allow', 'actions' => ['editaccounts'], 'roles' => ['accountEdit']],
			['allow', 'actions' => ['adminrefund', 'refundFromWallet', 'savecustbankdetails', 'walletrefund', 'pgrefund'], 'roles' => ['refundProcessing']],
			['allow', 'actions' => ['addPenalty'], 'roles' => ['accountEdit']],
			['allow', 'actions' => ['removeVendorCompensation'], 'roles' => ['removeVendorCompensation']],
			//['allow', 'actions' => ['modifiedPaymentStatus'], 'roles' => ['accountEdit']],
			['allow', 'actions' => ['allocatecsr'], 'roles' => ['preVendorAssignment']],
			//['allow', 'actions' => ['dispatchcsr'], 'expression' => 'Booking::checkAllocateCSRAccess()'],
			['allow', 'actions' => ['changeDriverAppRequirementStatus'], 'roles' => ['updateDrvAppUsage']],
			['allow', 'actions' => ['notifyvendor'], 'roles' => ['PreAssignAccess']],
			['allow', 'actions' => ['stopSystemMaxAllowableVndAmount'], 'roles' => ['stopIncreasingVendorAmount']],
			['allow', 'actions' => ['activateGozonow', 'showgnowbidlist', 'getGNowbidData', 'processGNowbidaccept', 'gnowAdminReNotify'], 'roles' => ['activateGozoNow']],
			//  ['allow', 'actions' => ['assignvendor'], 'roles' => ['PreAssignAccess']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('selectdriver', 'startChat', 'addVndAccept', 'requestlist', 'export', 'getcontacts', 'gettravellerinfo', 'pendinglist', 'createTrip', 'unmatchedBkgId', 'matchtrip', 'ownmatchtrip', 'updatevendoramount', 'modifyvendoramount', 'pendingaction',
					'cancelPendingBooking', 'getamountbyvehicle1', 'getamount', 'getcarmodel', 'sendpaymentlink', 'sendconfirmation', 'updatepaymentexpiry', 'lockpaymentoption',
					'getdriverdetails', 'getvehicledetails', 'view', 'getamountbyvehicle', 'showcsr', 'assigncsr', 'showlog', 'sendsmstodriver', 'addtransaction', 'reconfirmBooking', 'reconfirmBookingSms',
					'convert', 'addaccountingremark', 'accountflag', 'getdrivers', 'getcabs', 'getcitiesname', 'feedbackform', 'match', 'matchList', 'matchview', 'matchassign', 'assigncabdriver', 'escalationremarks',
					'addFollowup', 'completefollowup', 'triprelatedbooking', 'sendcabdriverinfo', 'autoAssignUnverifiedFollowup', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS',
					'upsellremarks', 'sosOff', 'profitabilityremarks', 'getcabdetails', 'getdrivercabdetails', 'checkcabtimeoverlap', 'checkAdminGozonow', 'sendDetailToCustomer', 'checkDboApplicable',
					'inv', 'listlogdetails', 'verifycustomermarked', 'markedbadmessage', 'markedbadlist', 'convertdata', 'getsubscriberids', 'expireQuote',
					'resetmarkedbad', 'addmarkremark', 'quotation1', 'quotation2', 'uploads', 'setcompletebooking', 'changeCPComm', 'getcommission',
					'flightstatus', 'blockmessage', 'unregvndaccept', 'cancellations', 'onewayautoaddress', 'chat', 'chatLog', 'ShowTripStatus', 'changefsaddresses', 'flexximatch', 'flexxiMatchedValidation', 'NoShowUnset', 'sendSMSToUnregisteredVendors',
					'autoAssignment', 'assignOm', 'assigncsrbyOM', 'assignDispatchCsr', 'selfAssignOm', 'autoAssignmentByBid', 'autoCancel', 'sendVendorDriver', 'oneminutelog', 'deloneminutelog', 'ApproveDutySlip', 'ApproveDoc', 'SetdutyReceived', 'changeDutySlipStatus', 'smartMatchList', 'modifiedPaymentStatus', 'skipCsrAllocation',
					'getEscalationDesc', 'addnmi', 'changeRefundApprovalStatus', 'checkFollowupTiming', 'getPaymentStatus', 'showPaymentStatus', 'gnowNotificationList',
					'customerInfo', 'customerType', 'partnerInfo', 'carVerify', 'bookingType', 'route', 'payment', 'travellerInfo', 'additionalInfo', 'airportTransfer', 'autoMarkerAddress', 'Pricelock', 'voucherView', 'duplicateBooking', 'track', 'currentlyAssignedDetails', 'getDrvCurrentLocation', 'showAllBidRank',
					'display', 'dtlscq', 'vendorNotAssigned', 'breakSmartMatch', 'quotebooking', 'manuallytriggerassignment', 'reallocateCsr', 'checkDispatchCsr', 'selfAllocatCBR', 'dispatchcsr', 'AddNoShowCBR', 'getBlokedLocationData', 'viewVendorCompensation', 'railwayBusTransfer', 'blockUnassign', 'cngAllowed', 'pushDriverCustomEvents', 'confirmpartnerbooking','gpx'),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['mffreport', 'generateInvoiceForBooking', 'requestlist', 'startchat'], 'users' => ['*']],
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
			$ri	 = array('');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		//Getting Escalation List
		$this->onRest('req.post.getEscalationList.render', function () {
			return $this->renderJSON($this->getEscalationList());
		});
		//Getting Delegation List
		$this->onRest('req.post.getDelegationList.render', function () {
			return $this->renderJSON($this->getDelegationList());
		});
		//Getting getManualAssignment List
		$this->onRest('req.post.getManualAssignmentList.render', function () {
			return $this->renderJSON($this->getManualAssignmentList());
		});
		//Getting getCriticalAssignmentList List
		$this->onRest('req.post.getCriticalAssignmentList.render', function () {
			return $this->renderJSON($this->getCriticalAssignmentList());
		});
		//Getting getDriverNotLeftForPickUp List
		$this->onRest('req.post.getDriverNotLeftForPickUpList.render', function () {
			return $this->renderJSON($this->getDriverNotLeftForPickUpList());
		});
		//Getting getDriverNotLoggedInList List
		$this->onRest('req.post.getDriverNotLoggedInList.render', function () {
			return $this->renderJSON($this->getDriverNotLoggedInList());
		});
		//Getting get Auto Cancel List
		$this->onRest('req.post.getAutoCancelList.render', function () {
			return $this->renderJSON($this->getAutoCancelList());
		});
		//Getting get persistent notification counting
		$this->onRest('req.post.getPersistentNotification.render', function () {
			return $this->renderJSON($this->getPersistentNotification());
		});
	}

	public function getEscalationList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = BookingSub::model()->getEscalations($jsonObj->searchQry);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 37);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Escalation Bookings not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getDelegationList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = Booking::model()->getAdpList($jsonObj->searchQry, 224);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 224);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Delegate Bookings not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getManualAssignmentList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = Booking::model()->getAdpList($jsonObj->searchQry, 225);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 225);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Manual Assignment Bookings are not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getCriticalAssignmentList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = Booking::model()->getAdpList($jsonObj->searchQry, 226);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 226);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Critical Assignment Bookings are not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getDriverNotLeftForPickUpList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = Booking::model()->getAdpList($jsonObj->searchQry, 251);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 251);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Driver Not Left For Pick Up Bookings are not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getDriverNotLoggedInList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = Booking::model()->getAdpList($jsonObj->searchQry, 250);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 251);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Driver Not Logged In Bookings are not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getAutoCancelList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$listData			 = Booking::model()->getAdpList($jsonObj->searchQry, 252);
			/* @var $response \Stub\booking\AdmListResponse */
			$response			 = new \Stub\booking\AdmListResponse();
			$response->getData($listData, 252);
			$data				 = Filter::removeNull($response);
			if (empty($response->dataList))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Auto Cancel Bookings are not available.");
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getPersistentNotification()
	{
		$returnSet = new ReturnSet();
		try
		{
			$escCount		 = BookingSub::getActiveEscalations();
			$delegatedCount	 = BookingSub::counterDelegatedManager();
			/* @var $response \Stub\common\PersistentNotification */
			$response		 = new \Stub\common\PersistentNotification();
			$response->getData($escCount, $delegatedCount);
			$data			 = Filter::removeNull($response);
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $ReturnSet::setException($e);
		}
		return $returnSet;
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

	public function actionList($qry = [])
	{
		$tab				 = Yii::app()->request->getParam('tab');
		$userid				 = Yii::app()->request->getParam('userid', '');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$bid				 = Yii::app()->request->getParam('bid', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$bkgSource			 = Yii::app()->request->getParam('source', '');
		$searchid			 = Yii::app()->request->getParam('searchid');
		$related			 = Yii::app()->request->getParam('related', 0);
		$vndid				 = Yii::app()->request->getParam('vndid', 0);
		$drvid				 = Yii::app()->request->getParam('drvid', 0);
		$source				 = Yii::app()->request->getParam('source');
		$params1			 = array_filter($_GET + $_POST);
		$GLOBALS["search"]	 = "";
		if (isset($_REQUEST['Booking']) || isset($_REQUEST['booking_search']))
		{

			$arr				 = Yii::app()->request->getParam('Booking');
			$GLOBALS["search"]	 = $arr['search'];
		}
		$model = Booking::model()->setDataForBookingList($params1, $searchid, $arr, $bkgSource, $related);

		$params = array_filter($params1);
		if ($userid != '')
		{
			$bookingStatus	 = Booking::getMyCallTabCategories();
			$leadStatus		 = $bookingStatus;
		}
		else
		{
			$bookingStatus	 = Booking::model()->getActiveBookingStatus();
			unset($bookingStatus['8']);
			$leadStatus		 = ['0' => 'All'] + $bookingStatus;
			unset($leadStatus['8']);
		}
		$tabFilter = $leadStatus;

		if ($tab == '')
		{
			$tab = AdminProfiles::getTabByTeam();
		}
		if ($tab != '')
		{
			$tabFilter = [$tab];
		}
		else
		{
			$tabFilter = [2];
		}
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$dataProvider	 = [];

		$model->bkg_status	 = null;
		$model->mycallPage	 = $userid;

		if ($userid != '')
		{

			$statusCount	 = $model->getStatusCount(Yii::app()->user->getId(), '', $userid, $source);
			$statusCount[10] = $statusCount[2] + $statusCount[3] + $statusCount[5];
			$statusCount[20] = $statusCount[1] + $statusCount[15];
			$statusCount[30] = $statusCount[1];
			$statusCount[40] = $statusCount[6] + $statusCount[7] + $statusCount[9];
		}
		elseif ($vndid > 0)
		{
			$model->bcb_vendor_id	 = $vndid;
			$model->bkg_pickup_date1 = '';
			$model->bkg_pickup_date2 = '';
			$model->bkg_create_date1 = date('Y-m-d', strtotime('-6 MONTH'));
			$model->bkg_create_date2 = date('Y-m-d');
			$statusCount			 = $model->getStatusCount(0, $tab);
			$statusCount[10]		 = $statusCount[3] + $statusCount[5];

			$statusCount[40] = $statusCount[6] + $statusCount[7] + $statusCount[9];
		}
		elseif ($drvid > 0)
		{
			$model->bcb_driver_id	 = $drvid;
			$model->bkg_pickup_date1 = '';
			$model->bkg_pickup_date2 = '';
			$model->bkg_create_date1 = date('Y-m-d', strtotime('-6 MONTH'));
			$model->bkg_create_date2 = date('Y-m-d');
			$statusCount			 = $model->getStatusCount(0, $tab);
			$statusCount[10]		 = $statusCount[3] + $statusCount[5];

			$statusCount[40] = $statusCount[6] + $statusCount[7] + $statusCount[9];
		}
		else
		{
			$statusCount	 = $model->getStatusCount(Yii::app()->user->getId());
			$statusCount[0]	 = array_sum($statusCount);
		}

		foreach ($tabFilter as $bid)
		{
			$pageSize											 = (!in_array($bid, [2, 3, 5])) ? 50 : 30;
			$model->bkg_status									 = $bid;
			$model->mycallPage									 = $userid;
			$dataProvider[$bid]									 = [];
			$dataProvider[$bid]["label"]						 = $leadStatus[$bid];
			$dataProvider[$bid]["labelCount"]					 = $leadStatus[$bid];
			$user												 = ($vndid > 0) ? 0 : Yii::app()->user->getId();
			$dataProvider[$bid]["data"]							 = $model->fetchList($pageSize, 'data', $user, $statusCount[$bid], $bid, $userid, $source);
			$params['tab']										 = $bid;
			$dataProvider[$bid]["data"]->getPagination()->params = $params;
			$dataProvider[$bid]["data"]->getSort()->params		 = $params;
			$time												 = Filter::getExecutionTime();
		}

		$assignment['manual']		 = BookingSub::getAssignmentCount('manual', 'manualCnt');
		$assignment['critical']		 = BookingSub::getAssignmentCount('critical', 'criticalCnt');
		$assignment['delegation']	 = BookingSub::countEscalatedAssignment();
		$demsupmisfireCount			 = BookingSub::getCountDemandSupplyMisfire();

		if ($outputJs && $tab != '')
		{
			$labelArr = [];
			foreach ($dataProvider as $key => $provider)
			{
				$label			 = '';
				$labelArr[$key]	 = $label . $provider['data']->getTotalItemCount();
			}

			$this->renderPartial("grid", ['status' => $tab, 'provider' => $dataProvider[$tab], 'labels' => $labelArr], false, true);
		}
		else
		{
			//	$tabFilter	 = array_filter([1, 2, 3, 5, $tab], 'is_numeric');
			$method = "render" . (($outputJs) ? "Partial" : "");
			$this->$method('list', array('tab'				 => $tab, 'leadStatus'		 => $leadStatus,
				'statusCount'		 => $statusCount, 'dataProvider'		 => $dataProvider,
				'model'				 => $model,
				'tabFilter'			 => $tabFilter,
				'formHide'			 => $formHide,
				'lbid'				 => $bid,
				'assignment'		 => $assignment,
				'demsupmisfireCount' => $demsupmisfireCount), false, $outputJs);
		}
	}

	public function actionPendinglist()
	{
		$this->pageTitle = "Pending Booking List";
		$model			 = new Booking('search');

		if (isset($_REQUEST['Booking']))
		{
			$arr				 = Yii::app()->request->getParam('Booking');
			$model->attributes	 = $arr;

			$model->bkg_create_date1	 = $arr['bkg_create_date1'];
			$model->bkg_create_date2	 = $arr['bkg_create_date2'];
			$model->bkg_pickup_date1	 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $arr['bkg_pickup_date2'];
			$model->bkg_vendor_name		 = $arr['bkg_vendor_name'];
			$model->bkg_agent_company	 = $arr['bkg_agent_company'];
		}
		/* @var $model Booking */
		/* @var $dataProvider CActiveDataProvider */
		$dataProvider = $model->pendingStatusList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		//print_r($dataProvider); exit();
		$this->render('pendinglist', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionMatch()
	{
		$this->pageTitle		 = "Match Booking";
		$model					 = new Booking('search');
		$model->bcbTypeMatched	 = [0];
		if (isset($_REQUEST['Booking']))
		{
			$model->bcbTypeMatched = Yii::app()->request->getParam('Booking')['bcbTypeMatched'];
		}
		//$dataProvider = BookingSub::model()->resetScope()->getMatchedList($model->bcbTypeMatched,$model->up_bkg_booking_id,$model->down_bkg_booking_id,$model->up_bkg_pickup_date1,$model->up_bkg_pickup_date2,$model->down_bkg_pickup_date1,$model->down_bkg_pickup_date2);
		$dataProvider = BookingSub::model()->resetScope()->getMatchedList($model->bcbTypeMatched);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$this->render('match', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionMatchList()
	{

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
		$this->render('match_list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionmatchview()
	{
		$bookingID	 = Yii::app()->request->getParam('id');
		$models		 = Booking::model()->findByPk($bookingID);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('matchview', array('model' => $models, 'isAjax' => $outputJs), false, $outputJs);
	}

	public function actionmatchassign()
	{
		$userInfo		 = UserInfo::getInstance();
		$upBookingID	 = Yii::app()->request->getParam('up_bkg_id');
		$downBookingID	 = Yii::app()->request->getParam('down_bkg_id');
		$success		 = false;
		$trans			 = Yii::app()->db->beginTransaction();
		try
		{
			if ($upBookingID != '' && $downBookingID != '')
			{
				$model					 = Booking::model()->findByPk($upBookingID);
				$cabModel				 = $model->getBookingCabModel();
				$model->bkg_return_id	 = $downBookingID;
				$model->save();
				$model2					 = Booking::model()->findByPk($downBookingID);
				$cabModel2				 = $model2->getBookingCabModel();
				$model2->bkg_return_id	 = $upBookingID;
				$model2->save();
				if ($model->bkg_status > 2)
				{
					$bookingID = $downBookingID;
					if ($cabModel->bcb_vendor_id != '')
					{
						$model2->assignVendor($bookingID, $cabModel->bcb_vendor_id);
					}
					if ($cabModel->bcb_cab_id != '' && $cabModel->bcb_driver_id != '' && $cabModel->bcb_driver_phone != '')
					{
						$model2->assigncabdriver($cabModel->bcb_cab_id, $cabModel->bcb_driver_id, $cabModel->bcb_driver_phone, $userInfo);
					}
				}
				else if ($model2->bkg_status > 2)
				{
					$bookingID = $upBookingID;
					if ($cabModel2->bcb_vendor_id != '')
					{
						$model->assignVendor($bookingID, $cabModel->bcb_vendor_id);
					}
					if ($cabModel2->bcb_cab_id != '' && $cabModel2->bcb_driver_id != '' && $cabModel2->bcb_driver_phone != '')
					{
						$model->assigncabdriver($cabModel2->bcb_cab_id, $cabModel2->bcb_driver_id, $cabModel2->bcb_driver_phone, $userInfo);
					}
				}
				if ($model->bkg_status > 2 || $model2->bkg_status > 2)
				{
					$success = true;
				}
				else if ($model->bkg_status <= 2 || $model2->bkg_status <= 2)
				{
					$success = false;
				}
			}
			$trans->commit();
		}
		catch (Exception $e)
		{
			Yii::log($e->getTraceAsString(), CLogger::LEVEL_ERROR);
			$trans->rollback();
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionOwnmatchtrip()
	{
		$userInfo = UserInfo::getInstance();

		if ($_REQUEST['bkg_booking_id_Json'] != '')
		{
			$matchbkgIds	 = Yii::app()->request->getParam('bkg_booking_id_Json');
			$arrBkgIdsJSON	 = $matchbkgIds;
			$arrBkgIds		 = json_decode($matchbkgIds);
		}
		else
		{
			$arrBkgIds1		 = $_REQUEST['bkg_booking_id'];
			$arrBkgIdsJSON	 = json_encode($arrBkgIds1);
			$arrBkgIds		 = $_REQUEST['bkg_booking_id'];
		}
		$arrBkgIds = BookingSub::model()->getIdsByCodesArr($arrBkgIds);
		if ($arrBkgIds == '')
		{
			echo 'These match can not be possible';
			Yii::app()->end();
		}
		//$arrBkgIdsJSON	 = CJSON::encode($arrBkgIds);
		//$errorBkgId = Booking::model()->getBookingIdWithoutSmartMatch($arrBkgIds);
		if ($arrBkgIds)
		{
			$arrTotBookingAmounts = Booking::model()->getTotalBookingAmountsbyBookingIds($arrBkgIds);
		}
		$cabmodel	 = new BookingCab('matchtrip');
		$bkgIds		 = $arrTotBookingAmounts['bkg_booking_ids'];
		$bookingIds	 = explode(',', $bkgIds);

		$userModel	 = Admins::model()->getNameById($userInfo->getUserId());
		$userName	 = $userModel->adm_user;

		$bookingModel = Booking::model()->getBookingModelsbyIdsList($arrBkgIds);
		foreach ($bookingModel as $k => $value)
		{
			if ($value->bkg_id > 0)
			{
				$model = Booking::model()->findByPk($value->bkg_id);
				if ($model->bkg_status == 2)
				{
					$models[$k] = $model;
				}
			}
		}
		$arr = [];
		if (isset($_POST['BookingCab']))
		{
			$isValidate					 = true;
			$errMessage					 = "Error occurred";
			$bkgBookingIds				 = Booking::model()->getBkgBookingIds(json_decode($_POST['multicityjsondata']));
			$string						 = $bkgBookingIds['bkg_booking_ids'];
			$parts						 = explode(",", $string);
			$bkgingIds					 = implode(', ', $parts);
			$bcbArr						 = $_POST['BookingCab'];
			$cabmodel->bookings			 = $models;
			$cabmodel->bcb_vendor_amount = $bcbArr['bcb_vendor_amount'];
			if ($cabmodel->validate())
			{
				$transaction = DBUtil::beginTransaction();
				$bkgstring	 = $bkgBookingIds['bkgIds'];
				$parts1		 = explode(",", $bkgstring);
				$bkgIds		 = implode(',', $parts1);

				$bkIds			 = explode(',', $bkgingIds);
				$multijsondata	 = json_decode($_POST['multicityjsondata']);
				$allBkgId		 = implode(',', $multijsondata);
				if ($multijsondata != '')
				{
					$arrBkgIds = $multijsondata;
				}
				$arr			 = $_POST['BookingCab'];
				$vendorAmount	 = trim($arr['bcb_vendor_amount']);

				if ($vendorAmount > 0)
				{
					$successVal = 0;
					foreach ($models as $model)
					{
						if ($model->bkg_bcb_id > 0 && $model->bkgBcb->bcb_vendor_id > 0)
						{

							$reason	 = "Vendor Cancelled for Trip Rematch";
							$result	 = Booking::model()->canVendor($model->bkg_bcb_id, $reason, $userInfo);

							if (!$result['success'])
							{
								$successVal++;
							}
						}

						if ($model->bkg_agent_id > 0)
						{
							$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
							if ($agtModel->agt_allow_smartmatch == 0)
							{
								$successVal++;
							}
						}
					}
					if ($successVal == 0)
					{
						//$models								 = Booking::model()->getBookingModelsbyIdsList($arrBkgIds);

						$mergedCabModel						 = new BookingCab('matchtrip');
						$mergedCabModel->bcb_vendor_amount	 = $vendorAmount;
						$mergedCabModel->bcb_bkg_id1		 = $bkgIds;
						$mergedCabModel->bcb_trip_type		 = 1;
						$mergedCabModel->bcb_matched_type	 = 1;
//$mergedCabModel->bcb_matched_type
						if ($arr['bcb_assign_id'] > 0)
						{
							$arr['bcb_vendor_id'] = $arr['bcb_assign_id'];
						}
						if (trim($arr['bcb_vendor_id']) > 0)
						{
							$mergedCabModel->bcb_vendor_id = trim($arr['bcb_vendor_id']);
						}
						try
						{
							if ($mergedCabModel->validate())
							{
								$mergedCabModel->save();
								BookingRoute::model()->setBookingCabStartEndTime($mergedCabModel->bcb_id, $mergedCabModel->bcb_bkg_id1);
								BookingTrail::updateProfitFlag($mergedCabModel->bcb_id);

								if ($mergedCabModel->bcb_vendor_id > 0)
								{
									foreach ($models as $model)
									{
										$model->bkg_bcb_id		 = $mergedCabModel->bcb_id;
										$rsuccess				 = BookingRoute::model()->linkAllBookingwithVendor($model->bkg_id, $mergedCabModel->bcb_id);
										$model->scenario		 = 'updatestatus';
										$model->save();
										$desc					 = "Smart Match (Manual) booking Id - " . $bkgingIds . " by " . $userName;
										$eventid				 = BookingLog::SMART_MATCH;
										$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
										BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid, false, $params, '', $mergedCabModel->bcb_id);
									}
									$model	 = $models[0];
									$res	 = BookingCab::model()->assignVendor($model->bkg_bcb_id, $mergedCabModel->bcb_vendor_id, '', '', $userInfo);
									if (!$res->isSuccess())
									{
										throw $res->getException();
									}
								}
								else
								{
									foreach ($models as $model)
									{
										$model->bkg_bcb_id	 = $mergedCabModel->bcb_id;
										$rsuccess			 = BookingRoute::model()->linkAllBookingwithVendor($model->bkg_id, $mergedCabModel->bcb_id);
										$model->scenario	 = 'updatestatus';
										if ($rsuccess)
										{
											$model->save();
										}
										$desc					 = "Smart Match (Manual) booking Id - " . $bkgingIds . " by " . $userName;
										$eventid				 = BookingLog::SMART_MATCH;
										$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
										BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid, false, $params, '', $mergedCabModel->bcb_id);
									}
								}

								DBUtil::commitTransaction($transaction);
								$this->redirect(array('smartMatchList'));
							}
							else
							{
								$errMessage	 = "Validation failed in saving trip.";
								$isValidate	 = false;
								goto validateFail;
							}
						}
						catch (Exception $ex)
						{
							$cabmodel->addError('bcb_id', $ex->getMessage());
							DBUtil::rollbackTransaction($transaction);
						}
					}
					else
					{
						$errMessage	 = "Vendor cancel failed for trip rematch/Smart match not allowed for agent booking.";
						$isValidate	 = false;
						goto validateFail;
					}
				}
				else
				{
					$errMessage	 = "Vendor amount is mendatory.";
					$isValidate	 = false;
					goto validateFail;
				}
			}
			validateFail:
			if (!$isValidate)
			{
				DBUtil::rollbackTransaction($transaction);
				$cabmodel->addError('bcb_id', $errMessage);
			}
		}
		render:
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('ownmatchtrip', array('models'		 => $models, 'cabmodel'		 => $cabmodel,
			'arrAmounts'	 => $arrTotBookingAmounts, 'arrBkgIds'		 => $arrBkgIds, 'arrBkgIdsJSON'	 => $arrBkgIdsJSON), false, $outputJs);
	}

	public function actionUnmatchedBkgId()
	{
		$bkgIds		 = $_POST['bkg_booking_id'];
		$bkgCntIds	 = count($bkgIds);
		$success	 = Booking::model()->getBookingIdWithoutSmartMatch($bkgIds);

		if ($success == $bkgCntIds)
		{
			echo json_encode(['success' => true]);
		}
		else
		{
			echo json_encode(['success' => false]);
		}
	}

	public function actionMatchtrip1()
	{
		$userInfo				 = UserInfo::getInstance();
		$upBookingID			 = Yii::app()->request->getParam('up_bkg_id');
		$downBookingID			 = Yii::app()->request->getParam('down_bkg_id');
		$arrBkgIds[]			 = $upBookingID;
		$arrBkgIds[]			 = $downBookingID;
		$arrTotBookingAmounts	 = Booking::model()->getTotalBookingAmountsbyBookingIds($arrBkgIds);
		$bkgIds					 = $arrTotBookingAmounts['bkg_booking_ids'];

		$bkgIds1 = $arrTotBookingAmounts['bkg_booking_ids'];

		$bookingIds		 = explode(',', $bkgIds);
		$UpBkgBookingId	 = $bookingIds[0];
		$DnBkgBookingId	 = $bookingIds[1];
		$userModel		 = Admins::model()->getNameById($userInfo->userId);
		$userName		 = $userModel->adm_user;
		$cabmodel		 = new BookingCab('matchtrip');
		$bookingModel	 = Booking::model()->getBookingModelsbyIdsList($arrBkgIds);
		foreach ($bookingModel as $k => $value)
		{
			$model		 = Booking::model()->findByPk($value->bkg_id);
			$models[$k]	 = $model;
		}
		$arr = [];
		if (isset($_POST['BookingCab']))
		{
			$arr			 = $_POST['BookingCab'];
			$vendorAmount	 = trim($arr['bcb_vendor_amount']);

			$cabmodel->bookings			 = $models;
			$cabmodel->bcb_vendor_amount = $vendorAmount;
			if ($cabmodel->validate())
			{
				$transaction = DBUtil::beginTransaction();
				if ($vendorAmount > 0)
				{
					$successVal = 0;
					foreach ($models as $model)
					{
						if ($model->bkg_bcb_id > 0 && $model->bkgBcb->bcb_vendor_id > 0)
						{

							$reason	 = "Vendor Cancelled for Trip Rematch";
							$bkgid	 = Booking::model()->canVendor($model->bkg_bcb_id, $reason, $userInfo);

							if ($bkgid)
							{
								DBUtil::commitTransaction($transaction);
							}
							else
							{
								DBUtil::rollbackTransaction($transaction);
								$successVal++;
							}
						}
						if ($model->bkg_status != 2)
						{
							DBUtil::rollbackTransaction($transaction);
							$successVal++;
						}
					}
					if ($successVal == 0)
					{
						//$models								 = Booking::model()->getBookingModelsbyIdsList($arrBkgIds);
						$mergedCabModel						 = new BookingCab('matchtrip');
						$mergedCabModel->bcb_vendor_amount	 = $vendorAmount;
						$mergedCabModel->bcb_bcb_id1		 = $upBookingID . ',' . $downBookingID;
						$mergedCabModel->bcb_trip_type		 = 1;
						$mergedCabModel->bcb_matched_type	 = 1;
						if ($arr['bcb_assign_id'] > 0)
						{
							$arr['bcb_vendor_id'] = $arr['bcb_assign_id'];
						}
						if (trim($arr['bcb_vendor_id']) > 0)
						{
							$mergedCabModel->bcb_vendor_id = trim($arr['bcb_vendor_id']);
						}
						try
						{
							if ($mergedCabModel->validate())
							{

								$mergedCabModel->save();

								BookingTrail::updateProfitFlag($mergedCabModel->bcb_id);

								if ($mergedCabModel->bcb_vendor_id > 0)
								{
									foreach ($models as $model)
									{
										$model->bkg_bcb_id	 = $mergedCabModel->bcb_id;
										$model->scenario	 = 'updatestatus';
										$model->save();
									}
									$model	 = $models[0];
									$succ	 = BookingCab::model()->assignVendor($model->bkg_bcb_id, $mergedCabModel->bcb_vendor_id);
								}
								else
								{
									foreach ($models as $model)
									{
										$model->bkg_bcb_id	 = $mergedCabModel->bcb_id;
										$rsuccess			 = BookingRoute::model()->linkAllBookingwithVendor($model->bkg_id, $mergedCabModel->bcb_id);
										$model->scenario	 = 'updatestatus';
										if ($rsuccess)
										{
											$model->save();
										}
									}
								}
								$upbkgid				 = $upBookingID;
								$tripId					 = $mergedCabModel->bcb_id;
								$desc					 = "Smart Match (Manual) booking " . $UpBkgBookingId . " with " . $DnBkgBookingId . " by " . $userName;
								$eventid				 = BookingLog::SMART_MATCH;
								$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
								BookingLog::model()->createLog($upbkgid, $desc, $userInfo, $eventid, false, $params, '', $tripId);

								$dnbkgid = $downBookingID;
								$desc	 = "Smart Match (Manual) booking " . $DnBkgBookingId . " with " . $UpBkgBookingId . " by " . $userName;
								BookingLog::model()->createLog($dnbkgid, $desc, $userInfo, $eventid, false, $params, '', $tripId);
								DBUtil::commitTransaction($transaction);
							}
							else
							{
								DBUtil::rollbackTransaction($transaction);
							}
						}
						catch (Exception $ex)
						{
							echo json_encode(['success'	 => false,
								'errors'	 => [
									'code'		 => 2,
									'message'	 => $ex->getMessage()
								]
							]);
							DBUtil::rollbackTransaction($transaction);
							Yii::app()->end();
						}
					}
				}
				else
				{
					
				}
				$this->redirect(array('match'));
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('matchtrip', array('models' => $models, 'cabmodel' => $cabmodel, 'arrAmounts' => $arrTotBookingAmounts, 'arrBkgIds' => $arrBkgIds), false, $outputJs);
	}

	public function actionMatchtrip()
	{
		$userInfo	 = UserInfo::getInstance();
		$bsm_id		 = Yii::app()->request->getParam('bsm_id');
		if ($bsm_id == '')
		{
			$bsm_id = Yii::app()->request->getParam('bsmId');
		}
		$bsmModel	 = BookingSmartmatch::model()->findByPk($bsm_id);
		$bookingIds	 = BookingSmartmatch::model()->getMatchBooking($bsmModel->bsm_bcb_id);

		//$bkgIds1 = $arrTotBookingAmounts['bkg_booking_ids'];
		$arrBkgIds[]		 = $bookingIds['bsm_upbooking_id'];
		$arrBkgIds[]		 = $bookingIds['bsm_downbooking_id'];
		$userModel			 = Admins::model()->getNameById($userInfo->userId);
		$userName			 = $userModel->adm_user;
		$cabmodel			 = BookingCab::model()->findByPk($bsmModel->bsm_bcb_id); //new BookingCab('matchtrip');
		$cabmodel->scenario	 = 'matchtrip';
		$bookingModel		 = Booking::model()->getBookingModelsbyIdsList($arrBkgIds);

		$arr = [];
		if (isset($_POST['BookingCab']))
		{
			$arr			 = $_POST['BookingCab'];
			$vendorAmount	 = trim($arr['bcb_vendor_amount']);

			$cabmodel->bookings			 = $bookingModel;
			$cabmodel->bcb_vendor_amount = $vendorAmount;
			if ($cabmodel->validate())
			{
				$transaction = DBUtil::beginTransaction();
				if ($vendorAmount > 0)
				{
					$successVal = 0;
					foreach ($bookingModel as $model)
					{
						if ($model->bkg_bcb_id > 0 && $model->bkgBcb->bcb_vendor_id > 0)
						{

							$reason	 = "Vendor Cancelled for Trip Rematch";
							$bkgid	 = Booking::model()->canVendor($model->bkg_bcb_id, $reason, $userInfo);

							if ($bkgid)
							{
								DBUtil::commitTransaction($transaction);
							}
							else
							{
								DBUtil::rollbackTransaction($transaction);
								$successVal++;
							}
						}
						if ($model->bkg_status != 2)
						{
							DBUtil::rollbackTransaction($transaction);
							$successVal++;
						}

						if ($model->bkg_agent_id > 0)
						{
							$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
							if ($agtModel->agt_allow_smartmatch == 0)
							{
								DBUtil::rollbackTransaction($transaction);
								$successVal++;
							}
						}
					}
					if ($successVal == 0)
					{
						$mergedCabModel->bcb_trip_type		 = 1;
						$mergedCabModel->bcb_matched_type	 = 1;
						if ($arr['bcb_assign_id'] > 0)
						{
							$arr['bcb_vendor_id'] = $arr['bcb_assign_id'];
						}
						if (trim($arr['bcb_vendor_id']) > 0)
						{
							$cabmodel->bcb_vendor_id = trim($arr['bcb_vendor_id']);
						}
						try
						{
							if ($cabmodel->validate())
							{

								$cabmodel->save();

								BookingTrail::updateProfitFlag($cabmodel->bcb_id);

								if ($cabmodel->bcb_vendor_id > 0)
								{
									foreach ($bookingModel as $model)
									{
										$model->bkg_bcb_id	 = $cabmodel->bcb_id;
										$model->scenario	 = 'updatestatus';
										$model->save();
									}
									$model	 = $bookingModel[0];
									$res	 = BookingCab::model()->assignVendor($model->bkg_bcb_id, $cabmodel->bcb_vendor_id);
									if (!$res->isSuccess())
									{
										throw $res->getException();
									}
								}
								else
								{
									foreach ($bookingModel as $model)
									{
										$model->bkg_bcb_id	 = $cabmodel->bcb_id;
										$succ				 = BookingRoute::model()->linkAllBookingwithVendor($model->bkg_id, $cabmodel->bcb_id);
										$model->scenario	 = 'updatestatus';
										if ($succ)
										{
											$model->save();
										}
									}
								}
//								if ($succ)
//								{
//									BookingSmartmatch::model()->deactivateAllPreMatchedBooking($bookingIds['bsm_upbooking_id'], $bookingIds['bsm_downbooking_id'], $bsm_id);
//								}
								$upbkgid				 = $bookingIds['bsm_upbooking_id'];
								$tripId					 = $cabmodel->bcb_id;
								$desc					 = "Smart Match (Manual) booking " . $bookingIds['bsm_upbooking_id'] . " with " . $bookingIds['bsm_downbooking_id'] . " by " . $userName;
								$eventid				 = BookingLog::SMART_MATCH;
								$params['blg_ref_id']	 = BookingLog::REF_MATCH_FOUND;
								BookingLog::model()->createLog($upbkgid, $desc, $userInfo, $eventid, false, $params, '', $tripId);

								$dnbkgid = $bookingIds['bsm_downbooking_id'];
								$desc	 = "Smart Match (Manual) booking " . $bookingIds['bsm_downbooking_id'] . " with " . $bookingIds['bsm_upbooking_id'] . " by " . $userName;
								BookingLog::model()->createLog($dnbkgid, $desc, $userInfo, $eventid, false, $params, '', $tripId);
								DBUtil::commitTransaction($transaction);
							}
							else
							{
								DBUtil::rollbackTransaction($transaction);
							}
						}
						catch (Exception $ex)
						{
							DBUtil::rollbackTransaction($transaction);
							echo json_encode(['success'	 => false,
								'errors'	 => [
									'code'		 => 2,
									'message'	 => $ex->getMessage()
								]
							]);
							ReturnSet::setException($ex);
							Yii::app()->end();
						}
					}
				}
				else
				{
					
				}
				$this->redirect(array('smartMatchList'));
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('matchtrip', array('models' => $bookingModel, 'cabmodel' => $cabmodel, 'bsmModel' => $bsmModel), false, $outputJs);
	}

	public function actionfeedbackform()
	{
		$this->pageTitle = "Send Feedback";
		$id				 = Yii::app()->request->getParam('bookingID');
		$isCustomer		 = Yii::app()->request->getParam('chk_customer');
		$isVendor		 = Yii::app()->request->getParam('chk_vendor');
		$isDriver		 = Yii::app()->request->getParam('chk_driver');
		$model			 = Booking::model()->findByPk($id);

		$bcabModel = $model->getBookingCabModel();
		if (isset($_REQUEST['Booking']))
		{
			if ($_REQUEST['Booking']['bkg_message'] != '')
			{
				$model->bkg_message = trim($_REQUEST['Booking']['bkg_message']);
			}
			$model->scenario = 'feedback';
			if ($model->validate())
			{
				$msgCom		 = new smsWrapper();
				$changes	 = $model->bkg_message;
				$cttId		 = $bcabModel->bcbVendor->vnd_contact_id;
				$number		 = ContactPhone::model()->getContactPhoneById($cttId);
				$userModel	 = Booking::model()->getUserbyId($id);
				if ($isCustomer)
				{
					$msgCom->sentFeedbackSmsCustomer('91', $userModel['bkg_contact_no'], $model->bkg_booking_id, $changes);
				}
				if ($isVendor)
				{
					$msgCom->sentFeedbackSmsVendor('91', $number, $model->bkg_booking_id, $changes);
				}
				if ($isDriver)
				{
					$msgCom->sentFeedbackSmsDriver('91', $bcabModel->bcb_driver_phone, $model->bkg_booking_id, $changes);
				}


				if (Yii::app()->request->isAjaxRequest)
				{
					$data = ['success' => true, 'oldStatus' => $model->bkg_status];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				if ($model->hasErrors())
				{
					$result										 = [];
					foreach ($model->getErrors() as $attribute => $errors)
						$result[CHtml::activeId($model, $attribute)] = $errors;

					$data = ['success' => false, 'errors' => $result];
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$this->renderPartial('feedbackform', array('model' => $model, 'cabmodel' => $bcabModel), false, true);
	}

	public function actionListbyids($qry = [])
	{
		$bkid				 = Yii::app()->request->getParam('bkid');
		$ids				 = Yii::app()->request->getParam('ids', 0);
		$ids				 = str_replace(' ', '', $ids);
		$model				 = new Booking();
		$model->bkg_status	 = null;
		$model->ids			 = array_filter(explode(",", $ids));
		$dataProvider		 = $model->fetchList();
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('list1', array('dataProvider' => $dataProvider, 'bkid' => $bkid), false, $outputJs);
	}

	public function actionListbyvhc($qry = [])
	{
		$bkid				 = Yii::app()->request->getParam('bkid', 0);
		$agtid				 = Yii::app()->request->getParam('agtid', 0);
		$ids				 = Yii::app()->request->getParam('ids', 0);
		$ids				 = str_replace(' ', '', $ids);
		$model				 = new Booking();
		$model->bkg_status	 = null;
		$model->bkg_id		 = $bkid;
		$model->ids			 = array_filter(explode(",", $ids));
		$dataProvider		 = $model->fetchVehicles();
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('listVehicle', array('dataProvider' => $dataProvider, 'bkid' => $bkid, 'agtid' => $agtid), false, $outputJs);
	}

	public function actionlistlogdetails()
	{
		$refId	 = Yii::app()->request->getParam('refId');
		$eventId = Yii::app()->request->getParam('eventId');
		/* $model Booking */
		$model	 = new Booking();
		if ($refId != '' && $eventId != '')
		{
			$dataProvider = $model->fetchLogDetailsByRefId($refId, $eventId);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$methodUrl	 = '';
		switch ($eventId):
			case '13':
				$methodUrl	 = 'logdetailsSms';
				break;
			case '14':
				$methodUrl	 = 'logdetailsEmail';
				break;
			case '54':
				$methodUrl	 = 'logdetailsTransaction';
				break;
			case '55':
				$methodUrl	 = 'logdetailsTransaction';
				break;
			case '56':
				$methodUrl	 = 'logdetailsTransaction';
			case '57':
				$methodUrl	 = 'logdetailsTransaction';
				break;
			case '58':
				$methodUrl	 = 'logdetailsTransaction';
				break;
			case '268':
				$methodUrl	 = 'logdetailsTransaction';
				break;
		endswitch;
		$this->$method($methodUrl, array('dataProvider' => $dataProvider, 'refId' => $refId), false, $outputJs);
	}

	public function actionVoucherView()
	{
		$request		 = Yii::app()->request;
		$pagetitle		 = "View Voucher Details";
		$this->pageTitle = $pagetitle;
		$bkg_id			 = Yii::app()->request->getParam('id');
		$event_id		 = Yii::app()->request->getParam('eventId');
		$view			 = Yii::app()->request->getParam('voucherView', 'voucherView');
		if ($bkg_id != '')
		{
			$model		 = Booking::model()->findByPk($bkg_id);
			$event_id	 = (int) $event_id;
		}
		else
		{
			throw new CHttpException(404, 'Voucher not found.');
		}
		$outputJs	 = $request->isAjaxRequest;
		$method		 = "renderPartial";
		$this->$method($view, array(
			'model'		 => $model, 'bookingId'	 => $bkgid, 'eventId'	 => $event_id, 'isAjax'	 => $outputJs
				), false, $outputJs);
	}

	public function actionEdit()
	{
		$this->pageTitle = "Edit booking";
		$id				 = Yii::app()->request->getParam('bookingID');
		$leadid			 = Yii::app()->request->getParam('lead_id');
		$bkg_tags		 = Yii::app()->request->getParam('bkg_tags');
		$success		 = false;
		$bkgid			 = $_POST['Booking']['bkg_id'];
		if ($id > 0)
		{
			$model				 = $this->loadModel($id);
			$bookingRouteModel	 = BookingRoute::model()->with(['brtFromCity', 'brtToCity'])->findAll('brt_bkg_id=:id', ['id' => $id]);
		}

		if ($bkgid > 0)
		{
			$model = $this->loadModel($bkgid);
		}
		else if ($leadid > 0)
		{
			$model				 = Booking::model()->leadtoBooking($leadid);
			$bookingRouteModel	 = $model->bookingRoutes;
			$id					 = $model->bkg_id;
		}
		$isRestricted = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo 'Sorry, you cannot edit this booking now.';
			Yii::app()->end();
		}
		$oldModel		 = clone $model;
		$modelBookPref	 = new BookingPref();
		//$model->scenario				 = 'adminupdate';
		//$model->bkgUserInfo->scenario	 = 'adminupdate';
		$model->preData	 = '';
		if (isset($_POST['Booking']))
		{
			$oldData						 = $model->getDetailsbyId($model->bkg_id);
			$model->attributes				 = Yii::app()->request->getParam('Booking');
			$model->bkgInvoice->attributes	 = Yii::app()->request->getParam('BookingInvoice');
			$bookingUser					 = Yii::app()->request->getParam('BookingUser');
			if ($bookingUser['bkg_country_code'] == '')
			{
				$bookingUser['bkg_country_code'] = $model->bkgUserInfo->bkg_country_code;
			}
			$model->bkgUserInfo->attributes	 = $bookingUser;
			$model->bkgTrail->attributes	 = Yii::app()->request->getParam('BookingTrail');
			$model->bkgTrack->attributes	 = Yii::app()->request->getParam('BookingTrack');
			$bkgTrail						 = Yii::app()->request->getParam('BookingTrail');
			$userTags						 = Contact::getTags($model->bkgUserInfo->bkg_contact_id);
			if (count($bkgTrail['bkg_tags']) > 0 || $userTags != '')
			{
				if (count($bkgTrail['bkg_tags']) == 0)
				{
					$bkgTrail['bkg_tags'] = [];
				}
				$userTagList = [];
				if (trim($userTags) != '')
				{
					$userTagList = array_merge($userTagList, explode(',', trim($userTags)));
				}

				$model->bkgTrail->bkg_tags = implode(',', array_unique(array_merge($userTagList, $bkgTrail['bkg_tags'])));
			}

			if (isset($_POST['BookingPref']))
			{
				$model->bkgPref->attributes	 = Yii::app()->request->getParam('BookingPref');
				$model->bkgPref->bpr_bkg_id	 = $model->bkg_id;
				$model->bkgPref->save();
			}
			if (isset($_POST['BookingAddInfo']))
			{
				$model->bkgAddInfo->attributes	 = Yii::app()->request->getParam('BookingAddInfo');
				$model->bkgAddInfo->bad_bkg_id	 = $model->bkg_id;
				$model->bkgAddInfo->save();
			}
			if (isset($_POST['multicityjsondata']))
			{
				$model->multicityjsondata = Yii::app()->request->getParam('multicityjsondata');
			}
			$bookingRouteModel = $model->editBooking($oldData, $oldModel);
			if ($bookingRouteModel != false)
			{
				$success = true;
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'Booking Modified Successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];
					echo json_encode($data);
					Yii::app()->end();
				}
				else
				{
					$this->redirect(array('view', 'id' => $model->bkg_id));
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result = [];
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
					}
					$data = ['success' => $success, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			$model->preData = $model->multicityjsondata;
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('edit', array('model' => $model, 'modelBookPref' => $model->bkgPref == null ? $modelBookPref : $model->bkgPref, 'creditVal' => $creditVal, 'bookingRouteModel' => $bookingRouteModel), false, $outputJs);
	}

	public function actionEdituserinfo()
	{
		$this->pageTitle = "Edit booking";
		$id				 = Yii::app()->request->getParam('bookingID');
		$leadid			 = Yii::app()->request->getParam('lead_id');
		$agentnotify	 = Yii::app()->request->getParam('agentnotifydata');
		$bkg_tags		 = Yii::app()->request->getParam('bkg_tags');
		$success		 = false;
		$bkgid			 = $_POST['Booking']['bkg_id'];

		if ($id > 0)
		{
			$model				 = $this->loadModel($id);
			$bookingRouteModel	 = BookingRoute::model()->with(['brtFromCity', 'brtToCity'])->findAll('brt_bkg_id=:id', ['id' => $id]);
		}

		if ($bkgid > 0)
		{
			$model = $this->loadModel($bkgid);
		}
		else if ($leadid > 0)
		{
			$model				 = Booking::model()->leadtoBooking($leadid);
			$bookingRouteModel	 = $model->bookingRoutes;
			$id					 = $model->bkg_id;
		}
		$isRestricted = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo 'Sorry, you cannot edit user info now.';
			Yii::app()->end();
		}
		$oldModel						 = clone $model;
		$bkgUserInfoOld					 = clone $oldModel->bkgUserInfo;
		$modelBookPref					 = new BookingPref();
		$model->bkgUserInfo->scenario	 = 'adminupdateuser';
		$model->preData					 = '';

		if (!isset($_POST['Booking']))
		{
			$notifydata				 = AgentMessages::model()->getNotificationDataByBkgId($model->bkg_id);
			$model->agentNotifyData	 = $notifydata;
		}
		if (isset($_POST['Booking']))
		{
			$oldData			 = $model->getDetailsbyId($model->bkg_id);
			$model->attributes	 = Yii::app()->request->getParam('Booking');
			if (isset($_POST['BookingUser']))
			{
				$model->bkgUserInfo->attributes = Yii::app()->request->getParam('BookingUser');
				$model->bkgUserInfo->save();
			}
			if (isset($_POST['BookingInvoice']))
			{
				$model->bkgInvoice->attributes = Yii::app()->request->getParam('BookingInvoice');
				$model->bkgInvoice->save();
			}
			if (isset($_POST['BookingAddInfo']))
			{
				$model->bkgAddInfo->attributes = Yii::app()->request->getParam('BookingAddInfo');
				$model->bkgAddInfo->save();
			}
			if (isset($_POST['BookingTrail']))
			{
				$model->bkgTrail->attributes = Yii::app()->request->getParam('BookingTrail');
				$bkgTrail					 = Yii::app()->request->getParam('BookingTrail');
				$userTags					 = Contact::getTags($model->bkgUserInfo->bkg_contact_id);
				if (count($bkgTrail['bkg_tags']) > 0 || $userTags != '')
				{
					if (count($bkgTrail['bkg_tags']) == 0)
					{
						$bkgTrail['bkg_tags'] = [];
					}
					$userTagList = [];
					if (trim($userTags) != '')
					{
						$userTagList = array_merge($userTagList, explode(',', trim($userTags)));
					}

					$model->bkgTrail->bkg_tags = implode(',', array_unique(array_merge($userTagList, $bkgTrail['bkg_tags'])));
				}
				$model->bkgTrail->save();
			}
			if (isset($_POST['BookingPref']))
			{
				$model->bkgPref->attributes	 = Yii::app()->request->getParam('BookingPref');
				$model->bkgPref->bpr_bkg_id	 = $model->bkg_id;
				$model->bkgPref->save();
			}
			if ($agentnotify != '' && $agentnotify != null && $agentnotify != 'null')
			{
				$model->agentNotifyData = json_decode($agentnotify, true);
			}
			$params = false;
			if ($bkgUserInfoOld->bkg_user_fname != $model->bkgUserInfo->bkg_user_fname || $bkgUserInfoOld->bkg_user_lname != $model->bkgUserInfo->bkg_user_lname ||
					$bkgUserInfoOld->bkg_contact_no != $model->bkgUserInfo->bkg_contact_no || $oldModel->bkgUserInfo->bkg_user_email != $model->bkgUserInfo->bkg_user_email
			)
			{
				$params['blg_ref_id'] = BookingLog::INITIAL_INFO_CHANGED;
			}
			$success = $model->editUserInfo($oldData, $oldModel, $params);
			if ($success != false)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'Booking Modified Successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result		 = [];
					$customerror = [];
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
						foreach ($errors as $err)
						{
							$customerror[] = $err;
						}
					}
					$data = ['success' => $success, 'errors' => $result, 'customerror' => $customerror];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			$model->preData = $_POST['multicityjsondata'];
		}
		$errors = Yii::app()->request->getParam('errors');
		if ($errors != '')
		{
			$model->validate();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('edit_userinfo', array('model' => $model, 'modelBookPref' => $model->bkgPref == null ? $modelBookPref : $model->bkgPref, 'creditVal' => $creditVal, 'bookingRouteModel' => $bookingRouteModel), false, $outputJs);
	}

	public function actionSelectdriver()
	{
		$vch_id	 = Yii::app()->request->getParam('vch_id'); //$_GET['vch_id'];
		$driver	 = VehicleDriver::model()->driverlist($vch_id);
		echo CJSON::encode(array('driverlist' => $driver));
	}

	/**
	 * @deprecated since 03/07/2020
	 * new function create will be used
	 */
	public function actionCreatenew()
	{
		$this->pageTitle = "New Booking";
		$bkid			 = Yii::app()->request->getParam('booking_id');
		$leadid			 = Yii::app()->request->getParam('lead_id');
		$model			 = new Booking();
		if ($bkid > 0)
		{
			$model = Booking::model()->findByPk($bkid);
		}
		if ($model->bkgInvoice == null && $model->bkgPref == null && $model->bkgTrail == null && $model->bkgTrack == null && $model->bkgUserInfo == null && $model->bkgAddInfo == null)
		{
			$model->bkgInvoice	 = new BookingInvoice();
			$model->bkgPref		 = new BookingPref();
			$model->bkgTrack	 = new BookingTrack();
			$model->bkgTrail	 = new BookingTrail();
			$model->bkgUserInfo	 = new BookingUser();
			$model->bkgAddInfo	 = new BookingAddInfo();
			$model->bkgPf		 = new BookingPriceFactor();
		}

		$success	 = false;
		$agentnotify = Yii::app()->request->getParam('agentnotifydata');
		Logger::create("Agent booking notify ================>" . $agentnotify, CLogger::LEVEL_PROFILE);
		$multijson	 = Yii::app()->request->getParam('multicityjsondata');

		$packageid	 = Yii::app()->request->getParam('pck_id');
		$brtArr		 = Yii::app()->request->getParam('BookingRoute');

		$differentiateSurgeAmount	 = Yii::app()->request->getParam('bkg_surge_differentiate_amount');
		$bookingPricefactor			 = $_POST['bkgPricefactor'];
		$bpf						 = CJSON::decode($bookingPricefactor);
		if ($packageid != '')
		{

			$packagedetails			 = PackageDetails::model()->getDetails($packageid);
			$count					 = count($packagedetails);
			$fcity					 = $packagedetails[0]["pcd_from_city"];
			$tcitylast				 = $packagedetails[$count - 1]["pcd_to_city"];
//			$flocation				 = $packagedetails[0]["pcd_from_location"];
//			$tlocation				 = $packagedetails[$count - 1]["pcd_to_location"];
			$packagemodel			 = Package::model()->findByPk($packageid);
			$model->bkg_from_city_id = $fcity;
			$model->bkg_to_city_id	 = $tcitylast;
			$model->bkg_package_id	 = $packageid;
			$routeModel				 = $packagemodel->packageDetails;

//			$model->bkg_pickup_address	 = $packagedetails[0]["pcd_from_location"];
//			$model->bkg_drop_address	 = $packagedetails[0]["pcd_to_location"];
			$model->bkg_trip_distance	 = $packagemodel["pck_km_included"];
			$model->bkg_trip_duration	 = $packagemodel["pck_min_included"];
			if ($model->bkg_pickup_date_date == '' || $model->bkg_pickup_date == '')
			{
				$defaultPackagePickupTime	 = Yii::app()->params['defaultPackagePickupTime'];
				$currentDt					 = date("Y-m-d $defaultPackagePickupTime", strtotime('+4 DAYS'));

				$model->bkg_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($currentDt);
				$model->bkg_pickup_date_time = date('h:i A', strtotime($currentDt));
				$model->bkg_pickup_date		 = $currentDt;
			}

			$multijsondata = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $model->bkg_pickup_date);
			if ($multijsondata != '')
			{
				$model->preData = $multijsondata;
			}
		}

		if (!isset($_POST['Booking']))
		{
			if ($bkid > 0)
			{
				$model = $model->copyBooking($bkid);
			}
			if ($leadid > 0)
			{

				$model = $model->leadtoBooking($leadid);

				$model->lead_id	 = $leadid;
				$routeModel		 = $model->bookingRoutes;

				if ($model->bkg_booking_type == 3 || $model->bkg_booking_type == 5 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 1)
				{
					$multijsondata = BookingRoute::model()->setTripRouteInfo($routeModel, $model->bkg_booking_type);
					if ($multijsondata != '')
					{
						$model->preData = $multijsondata;
					}
				}
			}
		}
		if (isset($_POST['Booking']))
		{
			$createQuote		 = Yii::app()->request->getParam('createQuote', 0);
			$crpmodel			 = Yii::app()->request->getParam('Booking');
			$crpinvoiceModel	 = Yii::app()->request->getParam('BookingInvoice');
			$crpuserInfoModel	 = Yii::app()->request->getParam('BookingUser');
			$crpaddInfoModel	 = Yii::app()->request->getParam('BookingAddInfo');
			$crptrackModel		 = Yii::app()->request->getParam('BookingTrack');
			$crptrailModel		 = Yii::app()->request->getParam('BookingTrail');
			$crpprefModel		 = Yii::app()->request->getParam('BookingPref');
			$model				 = new Booking('admininsert');
			if ($model->bkgInvoice == null && $model->bkgPref == null && $model->bkgTrail == null && $model->bkgTrack == null && $model->bkgUserInfo == null && $model->bkgAddInfo == null && $model->bkgPf == null)
			{
				$model->bkgInvoice	 = new BookingInvoice();
				$model->bkgPref		 = new BookingPref();
				$model->bkgPf		 = new BookingPriceFactor();
				$model->bkgTrack	 = new BookingTrack();
				$model->bkgTrail	 = new BookingTrail();
				$model->bkgUserInfo	 = new BookingUser('admininsert');
				$model->bkgAddInfo	 = new BookingAddInfo();
			}
			if (isset($crpmodel['bkg_id']))
			{
				unset($crpmodel['bkg_id']);
			}
			$model->attributes = $crpmodel;
			if ($model->bkg_booking_type == 2)
			{
				$model->bkg_booking_type = 3;
			}
			if ($createQuote == 1)
			{
				$model->bkg_status = 15;
			}

			$pickupDate = DateTimeFormat::DatePickerToDate($crpmodel['bkg_pickup_date_date']);
			if ($crpmodel['bkg_pickup_date_time'] != null)
			{
				$time = DateTime::createFromFormat('h:i A', $crpmodel['bkg_pickup_date_time'])->format('H:i:00');
			}
			$model->bkgUserInfo->attributes	 = $crpuserInfoModel;
			$model->bkgInvoice->attributes	 = $crpinvoiceModel;
			$model->bkgTrack->attributes	 = $crptrackModel;
			$model->bkgTrail->attributes	 = $crptrailModel;
			$model->bkgPref->attributes		 = $crpprefModel;
			$model->bkgAddInfo->attributes	 = $crpaddInfoModel;
			$model->routeProcessed			 = $crpmodel['routeProcessed'];
			$model->agentCreditAmount		 = $crpmodel['agentCreditAmount'];
			$model->bkg_pickup_date			 = $pickupDate . " " . $time;

			$model->bkg_copybooking_name	 = $crpmodel['bkg_copybooking_name'];
			$model->bkg_copybooking_ismail	 = $crpmodel['bkg_copybooking_ismail'];
			$model->bkg_copybooking_issms	 = $crpmodel['bkg_copybooking_issms'];
			$model->bkg_copybooking_email	 = $crpmodel['bkg_copybooking_email'];
			$model->bkg_copybooking_phone	 = $crpmodel['bkg_copybooking_phone'];
			$model->bkg_copybooking_country	 = $crpmodel['bkg_copybooking_country'];
			$model->corpAddtDetails			 = Yii::app()->request->getParam('corp_addt_details');
			$agentModel						 = Agents::model()->findByPk($model->bkg_agent_id);

			$model->bkgPref->bkg_duty_slip_required = ($crpprefModel['bkg_duty_slip_required'] == '') ? $agentModel->agt_duty_slip_required : $crpprefModel['bkg_duty_slip_required'];
			if (($agentModel->agt_duty_slip_required == 1 && $crpprefModel['bkg_duty_slip_required'] == '') || $crpprefModel['bkg_duty_slip_required'] == 1)
			{
				$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? 'Submit all receipts and duty slips required in this booking when trip completes.|</br>' : ' And ' . 'Submit all receipts and duty slips required in this booking when trip completes.|</br>';
			}

			$model->bkgPref->bkg_driver_app_required = ($crpprefModel['bkg_driver_app_required'] == '') ? $agentModel->agt_driver_app_required : $crpprefModel['bkg_driver_app_required'];
			if (($agentModel->agt_driver_app_required == 1 && $crpprefModel['bkg_driver_app_required'] == '') || $crpprefModel['bkg_driver_app_required'] == 1)
			{
				$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? 'Driver app use is requred.</br>' : ' | ' . 'Driver app use is requred.|</br>';
			}

			$model->bkgPref->bkg_trip_otp_required = ($crpprefModel['bkg_trip_otp_required'] == '') ? $agentModel->agt_otp_required : $crpprefModel['bkg_trip_otp_required'];
			if (($agentModel->agt_otp_required == 1 && $crpprefModel['bkg_trip_otp_required'] == '') || ($crpprefModel['bkg_trip_otp_required'] == 1 && $agentModel->agt_otp_required == 1) || ($crpprefModel['bkg_trip_otp_required'] == 1 && ($agentModel->agt_id == '' || $agentModel->agt_id == null)))
			{
				$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? 'OTP is required from customer.</br>' : ' | ' . 'OTP is required from customer.|</br>';
			}
			else
			{
				$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? 'OTP not required from customer.| Use Driver app to start, stop trip.</br>' : ' | ' . 'OTP not required from customer,Use Driver app to start, stop trip.|</br>';
			}

			$model->bkgPref->bkg_water_bottles_required = ($crpprefModel['bkg_water_bottles_required'] == '') ? $agentModel->agt_water_bottles_required : $crpprefModel['bkg_water_bottles_required'];
			if (($agentModel->agt_water_bottles_required == 1 && $crpprefModel['bkg_water_bottles_required'] == '') || $crpprefModel['bkg_water_bottles_required'] == 1)
			{
				$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? 'Keep 2x 500ml water bottles in car for customer.|</br>' : ' | ' . 'Keep 2x 500ml water bottles in car for customer.</br>';
			}

			$model->bkgPref->bkg_is_cash_required = ($crpprefModel['bkg_is_cash_required'] == '') ? $agentModel->agt_is_cash_required : $crpprefModel['bkg_is_cash_required'];
			if (($agentModel->agt_is_cash_required == 1 && $crpprefModel['bkg_is_cash_required'] == '') || $crpprefModel['bkg_is_cash_required'] == 1)
			{
				$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? 'Do not ask customer for cash.</br>' : ' | ' . 'Do not ask customer for cash.</br>';
			}

			$model->bkgPref->bkg_pref_req_other		 = ($crpprefModel['bkg_pref_req_other'] == '') ? $agentModel->agt_pref_req_other : $crpprefModel['bkg_pref_req_other'];
			$model->bkg_instruction_to_driver_vendor .= ($crpmodel['bkg_instruction_to_driver_vendor'] == '') ? $model->bkgPref->bkg_pref_req_other : ' ' . $model->bkgPref->bkg_pref_req_other;

			$model->bkg_instruction_to_driver_vendor = $model->bkg_instruction_to_driver_vendor;
			if (SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id) == ServiceClass::CLASS_VALUE_CNG)
			{
				$model->bkgPref->bkg_cng_allowed = 1;
			}
			if ($model->bkg_agent_id > 0)
			{
				$model->bkgPref->bkg_driver_app_required = ($crpprefModel['bkg_driver_app_required'] == '') ? $agentModel->agt_driver_app_required : $crpprefModel['bkg_driver_app_required'];
			}
			else
			{
				$model->bkgPref->bkg_driver_app_required = 1;
			}
			if ($model->bkg_agent_id > 0)
			{
				$model->bkgPref->bkg_block_autoassignment = ($agentModel->agt_vendor_autoassign_flag == 0) ? 1 : 0;
			}
			if ($agentnotify != '' && $agentnotify != null && $agentnotify != 'null')
			{
				$model->agentNotifyData = json_decode($agentnotify, true);
			}
			if (isset($multijson))
			{
				$model->multicityjson = $multijson;
			}
			if ((in_array($model->bkg_booking_type, [1, 2, 3, 5])) && $brtArr != '')
			{
				$cntBrt = sizeof($brtArr);

				$route_data	 = json_decode($multijson);
//				foreach($bRoutes)
				$k			 = 0;
				foreach ($route_data as $k => $v)
				{
					$fromAdditionalAddress	 = ltrim(trim($brtArr[$k]['brt_additional_from_address'] . $brtArr[$k]['brt_additional_to_address']) . ', ', ', ');
					$toAdditionalAddress	 = ltrim(trim($brtArr[$k + 1]['brt_additional_to_address']) . ', ', ', ');

					$bookingRoute						 = new BookingRoute();
					$bookingRoute->attributes			 = $v;
					$bookingRoute->brt_from_location	 = $fromAdditionalAddress . $brtArr[$k]['brt_from_location'] . $brtArr[$k]['brt_to_location'];
					$bookingRoute->brt_to_location		 = $toAdditionalAddress . $brtArr[$k + 1]['brt_to_location'];
					$bookingRoute->brt_from_latitude	 = round($brtArr[$k]['brt_from_latitude'] . $brtArr[$k]['brt_to_latitude'], 6);
					$bookingRoute->brt_from_longitude	 = round($brtArr[$k]['brt_from_longitude'] . $brtArr[$k]['brt_to_longitude'], 6);
					$bookingRoute->brt_to_latitude		 = round($brtArr[$k + 1]['brt_to_latitude'], 6);
					$bookingRoute->brt_to_longitude		 = round($brtArr[$k + 1]['brt_to_longitude'], 6);
					$bookingRoutes[]					 = $bookingRoute;
					if (trim($bookingRoute->brt_from_location) != '')
					{
						$route_data[$k]->pickup_address = $bookingRoute->brt_from_location;
					}
					if (trim($bookingRoute->brt_to_location) != '')
					{
						$route_data[$k]->drop_address = $bookingRoute->brt_to_location;
					}
				}
				$multijson					 = json_encode($route_data);
				$model->multicityjson		 = $multijson;
				$model->bkg_pickup_address	 = $bookingRoutes[0]->brt_from_location;
				$model->bkg_drop_address	 = $bookingRoutes[$k]->brt_to_location;
				$model->bkg_from_city_id	 = $route_data[0]->pickup_city;
				$model->bkg_to_city_id		 = $route_data[(count($route_data) - 1)]->drop_city;
				$model->bookingRoutes		 = $bookingRoutes;
				$model->pickupLat			 = $bookingRoutes[0]->brt_from_latitude;
				$model->pickupLon			 = $bookingRoutes[0]->brt_from_longitude;
				$model->dropLat				 = $bookingRoutes[$k]->brt_to_latitude;
				$model->dropLon				 = $bookingRoutes[$k]->brt_to_longitude;
			}
			$fromcity								 = $model->bkg_from_city_id;
			$tocity									 = $model->bkg_to_city_id;
			$model->bkgPf->bpf_bkg_id				 = $model->bkg_id;
			$model->bkgPf->bkg_ddbp_surge_factor	 = $bpf['ddbpSurgeFactor'];
			$model->bkgPf->bkg_ddbp_base_amount		 = $bpf['ddbpBaseAmount'];
			$model->bkgPf->bkg_dtbp_base_amount		 = $bpf['dtbpBaseAmount'];
			$model->bkgPf->bkg_ddbp_factor_type		 = $bpf['ddbpFactorType'];
			$model->bkgPf->bkg_route_route_factor	 = $bpf['ddbpRouteToRouteFactor'];
			$model->bkgPf->bkg_zone_zone_factor		 = $bpf['ddbpZoneToZoneFactor'];
			$model->bkgPf->bkg_zone_state_factor	 = $bpf['ddbpZoneToStateFactor'];
			$model->bkgPf->bkg_zone_factor			 = $bpf['ddbpZoneFactor'];
			$model->bkgPf->bkg_manual_surge_id		 = $bpf['manualSurgeId'];
			$model->bkgPf->bkg_manual_base_amount	 = $bpf['manualBaseAmount'];
			$model->bkgPf->bkg_regular_base_amount	 = $bpf['regularBaseAmount'];
			$model->bkgPf->bkg_ddbp_route_flag		 = $bpf['routeSurgeFlag'];
			$model->bkgPf->bkg_ddbp_master_flag		 = $bpf['ddbpMasterFlag'];
			$model->bkgPf->bkg_surge_applied		 = $bpf['surgeFactorUsed'];
			$model->bkgPf->bkg_dzpp_base_amount		 = $bpf['dzppBaseAmount'];
			$model->bkgPf->bkg_dzpp_surge_factor	 = $bpf['dzppSurgeFactor'];
			$model->bkgPf->bkg_debp_base_amount		 = $bpf['debpBaseAmount'];
			$model->bkgPf->bkg_debp_surge_factor	 = $bpf['debpSurgeFactor'];
			$model->bkgPf->bkg_durp_base_amount		 = $bpf['durpBaseAmount'];
			$model->bkgPf->bkg_durp_surge_factor	 = $bpf['durpSurgeFactor'];
			$model->bkgPf->bkg_ddbpv2_base_amount	 = $bpf['ddbpv2BaseAmount'];
			$model->bkgPf->bkg_ddbpv2_surge_factor	 = $bpf['ddbpv2SurgeFactor'];
			$model->bkgPf->bkg_additional_param		 = $bpf['additional_param'];
			if ($bpf['additional_param'] != '')
			{
				$preDataArr					 = json_decode($bpf['additional_param'], true);
				$model->bkgPf->bkg_rte_id	 = $preDataArr['rateId'];
			}
			$model->bkgPf->bkg_partner_soldout					 = $bpf['partner_soldout'];
			$model->bkgInvoice->bkg_surge_differentiate_amount	 = $differentiateSurgeAmount;
			$model->bkg_booking_id								 = 'temp';
			$success											 = $model->saveInfo($fromcity, $tocity);

			if ($success == true)
			{
				$bookingRoute->clearQuoteSession();
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'Booking Created Successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];

					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				if ($model->bkg_booking_type == '2' && $model->bkg_return_date_date != "" && $model->bkg_return_date_time != "")
				{
					$date1					 = DateTimeFormat::DatePickerToDate($model->bkg_return_date_date);
					$time1					 = date('H:i:00', strtotime($model->bkg_return_date_time));
					$model->bkg_return_date	 = $date1 . ' ' . $time1;
					//$model->bkg_return_time	 = $time1;
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					$result = [];
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
					}
					foreach ($model->bkgUserInfo->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model->bkgUserInfo, $attribute)] = $errors;
					}
					$data = ['success' => $success, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			if ($packageid != '')
			{
				$model->preData = json_decode($_POST['packageJson']);
			}
			else
			{
				$model->preData = json_decode($multijson);
			}
		}
		$brtRoute = new BookingRoute();

		$this->render('create', array(
			'model'			 => $model,
			'invModel'		 => $model->bkgInvoice,
			'usrModel'		 => $model->bkgUserInfo,
			'addInfoModel'	 => $model->bkgAddInfo,
			'trlModel'		 => $model->bkgTrail,
			'trcModel'		 => $model->bkgTrack,
			'prfModel'		 => $model->bkgPref,
			'brtRoute'		 => $brtRoute,
			'package'		 => $packageid,
//			'packagedt'		 => $packagedetails,
			'packages'		 => $packagemodel,
			'firstPickup'	 => $flocation,
			'lastDrop'		 => $tlocation));
	}

	public function actionCustomerInfo()
	{
		$userArr			 = Yii::app()->request->getParam('BookingUser');
		$model				 = new BookingUser();
		$bookingModel		 = new Booking();
		$model->attributes	 = $userArr;
		$model->bui_bkg_id	 = 1;
		$success			 = false;

		if ($model->bkg_user_id == '' && $model->bkg_contact_id != '')
		{
			$userId = ContactProfile::getUserId($model->bkg_contact_id);
			if ($userId)
			{
				$model->bkg_user_id = $userId;
			}

			$cttModel = Contact::model()->findByPk($model->bkg_contact_id);
			if ($cttModel)
			{
				$model->bkg_user_fname	 = $cttModel->ctt_first_name;
				$model->bkg_user_lname	 = $cttModel->ctt_last_name;
			}
		}

		$prevjsondata	 = CJSON::decode(Yii::app()->request->getParam('jsonData_customerPhone'));
		$result			 = CActiveForm::validate($model, null, false);
		if ($result == '[]')
		{
			$success = true;
			$result	 = array_merge($prevjsondata, $userArr);
			$this->renderPartial("bkBookingType", ["model" => $bookingModel, 'data' => json_encode($result)], false, true);
		}
		else
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['errors' => $result];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionCustomerType()
	{
		$bookingArr		 = Yii::app()->request->getParam('Booking');
		$bookingModel	 = new Booking();
		$usrModel		 = new BookingUser();
		if ($bookingArr['trip_user'] == 2)
		{
			$this->renderPartial("bkPartner", ["model" => $bookingModel, 'data' => json_encode($bookingArr)], false, true);
		}
		else
		{
			$this->renderPartial("bkCustomerPhone", ["usrModel" => $usrModel, 'data' => json_encode($bookingArr)], false, true);
		}
	}

	public function actionPartnerInfo()
	{
		$bookingArr								 = Yii::app()->request->getParam('Booking');
		$prevjsondata							 = CJSON::decode(Yii::app()->request->getParam('jsonData_partner'));
		$prevjsondata['agt_type']				 = Yii::app()->request->getParam('agt_type');
		$prevjsondata['agt_commission_value']	 = Yii::app()->request->getParam('agt_commission_value');
		$prevjsondata['agt_commission']			 = Yii::app()->request->getParam('agt_commission');
		$usrModel								 = new BookingUser();
		$success								 = false;
		if ($bookingArr['bkg_agent_id'] > 0)
		{
			$success = true;
			$result	 = array_merge($prevjsondata, $bookingArr);
		}
		$this->renderPartial("bkCustomerPhone", ["usrModel" => $usrModel, 'data' => json_encode($result)], false, true);
	}

	public function actionBookingType()
	{
		$bookingArr		 = Yii::app()->request->getParam('Booking');
		$prevjsondata	 = CJSON::decode(Yii::app()->request->getParam('jsonData_bookingType'));
		$bookingModel	 = new Booking();
		if ($bookingArr['bkg_booking_type'] == 2)
		{
			$bookingArr['bkg_booking_type'] = 3;
		}
		$bookingModel->bkg_booking_type	 = $bookingArr['bkg_booking_type'];
		$result							 = array_merge($prevjsondata, $bookingArr);
		$this->renderPartial("bkRoute", ["model" => $bookingModel, 'data' => json_encode($result)], false, true);
	}

	public function actionRoute()
	{
		$bookingArr				 = Yii::app()->request->getParam('Booking');
		$bookingRouteArr		 = Yii::app()->request->getParam('BookingRoute');
		$vctId					 = Yii::app()->request->getParam('Booking')['bkg_vehicle_type_id'];
		$partnerId				 = Yii::app()->request->getParam('agentId');
		$prevjsondata			 = CJSON::decode(Yii::app()->request->getParam('jsonData_routeType'));
		$multijsondata			 = CJSON::decode(Yii::app()->request->getParam('multicityjsondata'));
		$multicityAutoComTot	 = Yii::app()->request->getParam('multicityAutoComTot');
		$multicityAutoComData	 = Yii::app()->request->getParam('multicityAutoComData');
		$vhcId					 = $bookingArr['bkg_vehicle_type_id'] | 0;
		if ($prevjsondata['bkg_booking_type'] == 4)
		{
			$dataArr		 = Booking::model()->swapRouteForAirportTransfer($bookingRouteArr, $multijsondata, $bookingArr['bkg_transfer_type']);
			$bookingRouteArr = $dataArr["routes"];
			$multijsondata	 = $dataArr["multicityjsondata"];
			if ($bookingArr['bkg_transfer_type'] == 2)
			{
				$fromCtyId						 = $bookingArr['bkg_from_city_id'];
				$bookingArr['bkg_from_city_id']	 = $bookingArr['bkg_to_city_id'];
				$bookingArr['bkg_to_city_id']	 = $fromCtyId;
			}
			if ($dataArr['routeValidResult']['errors'] == '')
			{
				$prevjsondata['bkg_booking_type'] = $dataArr['routeValidResult']['booking_type'];
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$data = ['errors' => $dataArr['routeValidResult']['errors']];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$prevjsondata['multicityjsondata']		 = $multijsondata;
		$prevjsondata['BookingRoute']			 = $bookingRouteArr;
		$prevjsondata['multicityAutoComTot']	 = $multicityAutoComTot;
		$prevjsondata['multicityAutoComData']	 = $multicityAutoComData;

		$bookingModel	 = new Booking();
		$bookingInvoice	 = new BookingInvoice();
		$bookingTrack	 = new BookingTrack();
		$result			 = array_merge($prevjsondata, $bookingArr);
		// Note section started over here
		$arrRoute		 = $result['multicityjsondata'];
		for ($m = 0; $m < count($arrRoute); $m++)
		{
			$pickup[]	 = $arrRoute[$m]['pickup_city'];
			$dropup[]	 = $arrRoute[$m]['drop_city'];
			$date[]		 = $arrRoute[$m]['date'];
		}
		$locationArr	 = array_unique(array_merge($pickup, $dropup));
		$dateArr		 = array(reset($date), end($date));
		#print_r($locationArr);
		#print_r($dateArr);
		$noteArr		 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr);
		#print_r(json_encode($noteArr));
		// Note section ended over here
		/**
		 * *new
		 */
		$bookingRouteArr = [];
		foreach ($arrRoute as $key => $value)
		{
			$routeModel						 = new BookingRoute('insert');
			$routeModel->brt_from_city_id	 = $value[pickup_city];
			$routeModel->brt_to_city_id		 = $value[drop_city];
			$routeModel->brt_from_location	 = $value[pickup_address];
			$routeModel->brt_to_location	 = $value[drop_address];
			$routeModel->brt_pickup_datetime = $value[date];
			if ($value[distance] > 0 && $value[duration] > 0)
			{
				$routeModel->brt_trip_distance	 = $value[distance];
				$routeModel->brt_trip_duration	 = $value[duration];
			}
			if (is_array($routeDataArr))
			{
				$routeModel->brt_from_latitude	 = $routeDataArr[$key]['locLatVal'];
				$routeModel->brt_from_longitude	 = $routeDataArr[$key]['locLonVal'];
				$routeModel->brt_from_location	 = $routeDataArr[$key]['brtLocationVal'];
				$routeModel->brt_to_latitude	 = $routeDataArr[$key + 1]['locLatVal'];
				$routeModel->brt_to_longitude	 = $routeDataArr[$key + 1]['locLonVal'];
				$routeModel->brt_to_location	 = $routeDataArr[$key + 1]['brtLocationVal'];
				if ($routeModel->brt_from_latitude == '' && $routeModel->brt_from_longitude == '')
				{
					$ctyModel						 = Cities::model()->findByPk($routeModel->brt_from_city_id);
					$routeModel->brt_from_latitude	 = $ctyModel->cty_lat;
					$routeModel->brt_from_longitude	 = $ctyModel->cty_long;
					$routeModel->brt_from_location	 = $ctyModel->cty_garage_address;
				}
				if ($routeModel->brt_to_latitude == '' && $routeModel->brt_to_longitude == '')
				{
					$ctyModel						 = Cities::model()->findByPk($routeModel->brt_to_city_id);
					$routeModel->brt_to_latitude	 = $ctyModel->cty_lat;
					$routeModel->brt_to_longitude	 = $ctyModel->cty_long;
					$routeModel->brt_to_location	 = $ctyModel->cty_garage_address;
				}
				if ($arrRoute[$key]->pickup_address == '')
				{
					$arrRoute[$key]->pickup_address	 = $routeDataArr[$key]['brtLocationVal'];
					$arrRoute[$key]->drop_address	 = $routeDataArr[$key + 1]['brtLocationVal'];
				}
			}
			if (count($arrRoute) > 0)
			{
				$success = true;
			}
			$bookingRouteArr[] = $routeModel;
		}
		$quote			 = new Quote();
		$quote->routes	 = $bookingRouteArr;
		$quote->tripType = ($prevjsondata['bkg_booking_type'] == 5 && $pckageID == '') ? 3 : $prevjsondata['bkg_booking_type']; // package
		if ($isPackageType == 1)
		{
			$quote->tripType = 3;
		}
		//  $partnerId		 = Yii::app()->request->getParam('agentId');
		$bookingCPId			 = ($partnerId > 0) ? $partnerId : Yii::app()->params['gozoChannelPartnerId'];
		$quote->partnerId		 = $bookingCPId;
		$quote->quoteDate		 = date("Y-m-d H:i:s");
		$quote->pickupDate		 = $bookingRouteArr[0]->brt_pickup_datetime;
		$quote->sourceQuotation	 = Quote::Platform_Admin;

		/* package */
		$quote->packageID		 = $pckageID;
		$quote->suggestedPrice	 = $suggestPrice;
		if ($minTripDistance == 1 && !in_array($prevjsondata['bkg_booking_type'], [9, 10, 11]))
		{
			$quote->minRequiredKms = $distance;
		}
		if (!$bookingRouteArr[0]->checkQuoteSession())
		{
			Quote::$updateCounter = true;
			$bookingRouteArr[0]->setQuoteSession();
		}

		if ($partnerId)
		{
			$quote->isB2Cbooking	 = false;
			$quote->sourceQuotation	 = Quote::Platform_Agent;
		}
		Logger::info("Getamountbyvehicle 4");
		$errors = BookingRoute::validateRoutes($quote->routes, $prevjsondata['bkg_booking_type'], null, 0);
		if (!empty($errors))
		{
			throw new Exception(json_encode($errors[0]), ReturnSet::ERROR_VALIDATION);
		}
		$quote->setCabTypeArr(Quote::Platform_Admin);
		$quote->showErrorQuotes	 = true;
		$quote->platform		 = Quote::Platform_Admin;
		$svcIds					 = SvcClassVhcCat::getScvListByCategory($vctId);

		if ($bookingArr['isGozonow'] == 1)
		{
			$quote->gozoNow							 = true;
			$svcIds									 = SvcClassVhcCat::getCabListGNowQuote($vctId);
			$quote->catypeArr						 = $svcIds;
			$bookingModel->bkgPref->bkg_is_gozonow	 = 1;
		}


		$qt						 = $quote->getQuote($svcIds, $priceSurge				 = true, $includeNightAllowance	 = true, $checkBestRate			 = false, $isAllowed				 = true);
		/**
		 * *new
		 */
		if ($result['trip_user'] == '2')
		{
			$this->renderPartial("bkPaymentAgt", ["model" => $bookingModel, 'invModel' => $bookingInvoice, 'trcModel' => $bookingTrack, 'data' => json_encode($result), 'note' => $noteArr, 'rePaymentOpt' => 'false', 'vhcId' => $vhcId, 'quotes' => $qt, 'vctId' => $vctId], false, true);
		}
		else
		{
			$this->renderPartial("bkPaymentCust", ["model" => $bookingModel, 'invModel' => $bookingInvoice, 'trcModel' => $bookingTrack, 'data' => json_encode($result), 'note' => $noteArr, 'rePaymentOpt' => 'false', 'vhcId' => $vhcId, 'quotes' => $qt, 'vctId' => $vctId, 'flag' => true], false, true);
		}
	}

	public function actionAdditionalInfo()
	{
		$bookingArr							 = Yii::app()->request->getParam('Booking');
		$bookingPrefArr						 = Yii::app()->request->getParam('BookingPref');
		$bookingAddInfoArr					 = Yii::app()->request->getParam('BookingAddInfo');
		$bookingTrailArr					 = Yii::app()->request->getParam('BookingTrail');
		$prevjsondata						 = CJSON::decode(Yii::app()->request->getParam('jsonData_additionalInfo'));
		//$prevjsondata['bkg_vehicle_type_id'] = SvcClassVhcCat::model()->getSvcClassIdByVehicleCat($prevjsondata['bkg_vehicle_type_id'], $prevjsondata['bkg_service_class']);
		$prevjsondata['bkg_vehicle_type_id'] = $prevjsondata['bkg_vehicle_type_id'];
		$prfModel							 = new BookingPref();
		$model								 = new Booking();
		$addInfoModel						 = new BookingAddInfo();
		$result								 = array_merge($prevjsondata, $bookingArr);
		$result								 = array_merge($result, $bookingPrefArr);
		$result								 = array_merge($result, $bookingAddInfoArr);
		$result								 = array_merge($result, $bookingTrailArr);
		$createQuote						 = true;
		if ($prevjsondata['bkg_agent_id'] > 0)
		{
			$createQuote = false;
		}
		if (isset($prevjsondata['isGozonow']))
		{
			$prfModel->bkg_is_gozonow	 = $prevjsondata['isGozonow'];
			$createQuote				 = false;
		}
		$this->renderPartial("bkVendorIns", ["prfModel" => $prfModel, "addInfoModel" => $addInfoModel, "model" => $model, 'data' => json_encode($result), 'createQuote' => $createQuote], false, true);
	}

	public function actionPayment()
	{
		$bookingArr										 = Yii::app()->request->getParam('Booking');
		$bookingInvoiceArr								 = Yii::app()->request->getParam('BookingInvoice');
		$paymentChangesData								 = Yii::app()->request->getParam('paymentChangesData');
		$rePaymentOpt									 = Yii::app()->request->getParam('rePaymentOpt');
		$rec											 = Yii::app()->request->getParam('rec', 0);
		$prevjsondata									 = CJSON::decode(Yii::app()->request->getParam('jsonData_payment'));
		$multicityjsondata								 = CJSON::decode(Yii::app()->request->getParam('multicityjsondata'));
		$prevjsondata['paymentChangesData']				 = $paymentChangesData;
		$prevjsondata['bkg_surge_differentiate_amount']	 = Yii::app()->request->getParam('bkg_surge_differentiate_amount');
		$prevjsondata['bkgPricefactor']					 = CJSON::decode(Yii::app()->request->getParam('bkgPricefactor'));
		$pickupDateTime									 = $prevjsondata['multicityjsondata'][0]['date'];
		$prevjsondata['agtBkgCategory']					 = Yii::app()->request->getParam('agtBkgCategory');
		$pricerad										 = Yii::app()->request->getParam('pricerad');
		if ($pricerad == 'custom')
		{
			$prevjsondata['bkg_booking_type'] = 8;
		}
		if (trim($pickupDateTime) != '')
		{
			$dboPickupDateTime	 = strtotime($pickupDateTime);
			$currDateTime		 = Filter::getDBDateTime();
//			$hourdiff			 = Filter::CalcWorkingHour($currDateTime, $pickupDateTime);
//			$timeTwentyPercent	 = round($hourdiff * 0.2);
			$timeDiff			 = round((strtotime($pickupDateTime) - strtotime($currDateTime)));
			$followUpSeconds	 = min(round($timeDiff * 0.15), 2 * 3600);
			$followUpDateTime	 = date("Y-m-d H:i:s", strtotime('+' . $followUpSeconds . 'seconds', strtotime($currDateTime)));
//			$quoteExpiry		 = date("Y-m-d H:i:s", strtotime('+' . $timeTwentyPercent . ' hours', strtotime($currDateTime)));
			$quoteExpiry		 = BookingTrail::calculateQuoteExpiryTime($currDateTime, $pickupDateTime);
			if ($prevjsondata['isGozonow'] == 1)
			{
				$quoteExpiry = date("Y-m-d H:i:s", strtotime('-30 MINUTE', strtotime($pickupDateTime)));
			}
		}
		if ($rec == 1)
		{
			$addInfoData					 = CJSON::decode(Yii::app()->request->getParam('addInfoData'));
			$result							 = array_replace($addInfoData, $bookingArr);
			$result							 = array_replace($result, $bookingInvoiceArr);
			unset($result['paymentChangesData']);
			$result['paymentChangesData']	 = $paymentChangesData;
			echo json_encode($result);
		}
		else
		{
			if ($rePaymentOpt == 'false')
			{
				$bookingUserModel					 = new BookingUser();
				$bookingUserModel->bkg_country_code1 = $prevjsondata['bkg_country_code2'];
				$bookingUserModel->bkg_contact_no1	 = $prevjsondata['bkg_contact_no'];
				$bookingUserModel->bkg_user_email1	 = $prevjsondata['bkg_user_email'];
				$bookingUserModel->bkg_user_fname1	 = $prevjsondata['bkg_user_fname'];
				$bookingUserModel->bkg_user_lname1	 = $prevjsondata['bkg_user_lname'];
				$result								 = array_merge($prevjsondata, $bookingArr);
				$result								 = array_merge($result, $bookingInvoiceArr);
				$this->renderPartial("bkTravellerInfo", ["usrModel" => $bookingUserModel, 'data' => json_encode($result)], false, true);
			}
			else
			{
				$result						 = array_replace($prevjsondata, $bookingArr);
				$result						 = array_replace($result, $bookingInvoiceArr);
				unset($result['multicityjsondata']);
				$result['multicityjsondata'] = $multicityjsondata;
				$prfModel					 = new BookingPref();
				$model						 = new Booking();
				$addInfoModel				 = new BookingAddInfo();
				$model->bkgTrail			 = new BookingTrail();

				if (isset($prevjsondata['isGozonow']))
				{
					$prfModel->bkg_is_gozonow = $prevjsondata['isGozonow'];
				}
				$this->renderPartial("bkAdditioanalInfo", ["prfModel" => $prfModel, "addInfoModel" => $addInfoModel, "trailModel" => $model->bkgTrail, "model" => $model, 'data' => json_encode($result), 'quoteExpiry' => $quoteExpiry, 'hourdiff' => $hourdiff, 'followUpDateTime' => $followUpDateTime], false, true);
			}
		}
	}

	public function actionTravellerInfo()
	{
		$isBlockedLocation					 = $_REQUEST['isBlockedLocation'];
		$bookingArr							 = Yii::app()->request->getParam('BookingUser');
		$bookingRouteArr					 = Yii::app()->request->getParam('BookingRoute');
		$prevjsondata						 = CJSON::decode(Yii::app()->request->getParam('jsonData_travellerInfo'));
		$prfModel							 = new BookingPref();
		$model								 = new Booking();
		$addInfoModel						 = new BookingAddInfo();
		$bookingInvoice						 = new BookingInvoice();
		$bookingTrack						 = new BookingTrack();
		$model->bkgTrail					 = new BookingTrail();
		$prevjsondata['bkg_country_code']	 = $bookingArr['bkg_country_code1'];
		$prevjsondata['bkg_contact_no']		 = $bookingArr['bkg_contact_no1'];
		$prevjsondata['bkg_user_email']		 = $bookingArr['bkg_user_email1'];
		$prevjsondata['bkg_user_fname']		 = $bookingArr['bkg_user_fname1'];
		$prevjsondata['bkg_user_lname']		 = $bookingArr['bkg_user_lname1'];

		$prevjsondata['bkg_is_warned'] = $isBlockedLocation;

		$vhcId = $prevjsondata['bkg_vehicle_type_id'] | 0;

		$bkgVehicleTypeId		 = SvcClassVhcCat::getSvcClassIdByVehicleCat($vhcId, $prevjsondata['bkg_service_class']);
		$prevjsondata['scvId']	 = $bkgVehicleTypeId;

		$pickupDateTime = $prevjsondata['multicityjsondata'][0]['date'];
		if (trim($pickupDateTime) != '')
		{
			$dboPickupDateTime	 = strtotime($pickupDateTime);
			$currDateTime		 = Filter::getDBDateTime();
			$hourdiff			 = Filter::CalcWorkingHour($currDateTime, $pickupDateTime);
			$timeDiff			 = round((strtotime($pickupDateTime) - strtotime($currDateTime)));
			$timeTwentyPercent	 = round($hourdiff * 0.2);
			$followUpSeconds	 = min(round($timeDiff * 0.15), 2 * 3600);
			$followUpDateTime	 = date("Y-m-d H:i:s", strtotime('+' . $followUpSeconds . 'seconds', strtotime($currDateTime)));
			$quoteExpiry		 = date("Y-m-d H:i:s", strtotime('+' . $timeTwentyPercent . ' hours', strtotime($currDateTime)));
		}
		if (isset($prevjsondata['isGozonow']))
		{
			$prfModel->bkg_is_gozonow = $prevjsondata['isGozonow'];
		}

		if ($bookingRouteArr == null)
		{
			$this->renderPartial("bkAdditioanalInfo", ["prfModel" => $prfModel, "addInfoModel" => $addInfoModel, "trailModel" => $model->bkgTrail, "model" => $model, 'data' => json_encode($prevjsondata), 'quoteExpiry' => $quoteExpiry, 'hourdiff' => $hourdiff, 'followUpDateTime' => $followUpDateTime], false, true);
		}
		elseif ($prevjsondata['trip_user'] == '2' && $prevjsondata['bkg_booking_type'] == 8)
		{
			unset($prevjsondata['BookingRoute']);
			$prevjsondata['BookingRoute'] = $bookingRouteArr;
			$this->renderPartial("bkAdditioanalInfo", ["prfModel" => $prfModel, "addInfoModel" => $addInfoModel, "trailModel" => $model->bkgTrail, "model" => $model, 'data' => json_encode($prevjsondata), 'quoteExpiry' => $quoteExpiry, 'hourdiff' => $hourdiff, 'followUpDateTime' => $followUpDateTime], false, true);
		}
		else
		{
			unset($prevjsondata['BookingRoute']);
			$prevjsondata['BookingRoute'] = $bookingRouteArr;
			if ($prevjsondata['trip_user'] == '2')
			{
				$this->renderPartial("bkPaymentAgt", ["model" => $model, 'invModel' => $bookingInvoice, 'trcModel' => $bookingTrack, 'data' => json_encode($prevjsondata), 'rePaymentOpt' => 'true', 'vhcId' => $vhcId], false, true);
			}
			else
			{
				$this->renderPartial("bkPaymentCust", ["model" => $model, 'invModel' => $bookingInvoice, 'trcModel' => $bookingTrack, 'data' => json_encode($prevjsondata), 'rePaymentOpt' => 'true', 'vhcId' => $vhcId, 'flag' => false], false, true);
			}
		}
	}

	public function actionCreate()
	{
		$this->pageTitle = "New Booking";
		$model			 = new Booking();
		$custType		 = Yii::app()->request->getParam('type');
		$success		 = false;
		try
		{
			if (isset($_POST['Booking']))
			{
				$jsonDataArr				 = CJSON::decode(Yii::app()->request->getParam('jsonData_vendorIns'));
				$crpmodel					 = Yii::app()->request->getParam('Booking');
				$crpPrefmodel				 = Yii::app()->request->getParam('BookingPref');
				$trailmodel					 = Yii::app()->request->getParam('BookingTrail');
				$model						 = new Booking('admininsert');
				$brtArr						 = $jsonDataArr['BookingRoute'];
				$differentiateSurgeAmount	 = $jsonDataArr['bkg_surge_differentiate_amount'];
				$bpf						 = $jsonDataArr['bkgPricefactor'];
				if ($model->bkgInvoice == null && $model->bkgPref == null && $model->bkgTrail == null && $model->bkgTrack == null && $model->bkgUserInfo == null && $model->bkgAddInfo == null && $model->bkgPf == null)
				{
					$model->bkgInvoice	 = new BookingInvoice();
					$model->bkgPref		 = new BookingPref();
					$model->bkgPf		 = new BookingPriceFactor();
					$model->bkgTrack	 = new BookingTrack();
					$model->bkgTrail	 = new BookingTrail();
					$model->bkgUserInfo	 = new BookingUser('admininsert');
					$model->bkgAddInfo	 = new BookingAddInfo();
				}
				if (isset($crpmodel['bkg_id']))
				{
					unset($crpmodel['bkg_id']);
				}
				$model->attributes	 = $crpmodel;
				$model->attributes	 = $jsonDataArr;

				if ($model->bkg_booking_type == 2)
				{
					$model->bkg_booking_type = 3;
				}

				$model->bkg_status = 15;

				if ($jsonDataArr['bkg_addon_ids'] == 0)
				{
					$jsonDataArr['bkg_addon_ids'] = '';
				}
				$pickupDate = DateTimeFormat::DatePickerToDate($jsonDataArr['bkg_pickup_date_date']);
				if ($jsonDataArr['bkg_pickup_date_time'] != null)
				{
					$time = DateTime::createFromFormat('h:i A', $jsonDataArr['bkg_pickup_date_time'])->format('H:i:00');
				}
				$quoteExpireDate = DateTimeFormat::DatePickerToDate($jsonDataArr['bkg_quote_expire_date']);
				if ($jsonDataArr['bkg_quote_expire_time_1'] != null)
				{
					$time1 = DateTime::createFromFormat('h:i A', $jsonDataArr['bkg_quote_expire_time_1'])->format('H:i:00');
				}
				$multijson = $jsonDataArr['multicityjsondata'];

				$model->bkgUserInfo->attributes			 = $jsonDataArr;
				$model->bkgInvoice->attributes			 = $jsonDataArr;
				$model->bkgInvoice->bkg_addon_details	 = ($jsonDataArr['bkg_addon_details'] != '') ? json_encode(array_values($jsonDataArr['bkg_addon_details'])) : null;
				$model->bkgTrack->attributes			 = $jsonDataArr;
				$model->bkgTrail->attributes			 = $jsonDataArr;
				$model->bkgPref->attributes				 = $jsonDataArr;
				$model->bkgPref->attributes				 = $crpPrefmodel;
				$model->bkgAddInfo->attributes			 = $jsonDataArr;
				$model->routeProcessed					 = $jsonDataArr['routeProcessed'];
				$model->agentCreditAmount				 = $jsonDataArr['agentCreditAmount'];
				if ($jsonDataArr['agtBkgCategory'] == 2)
				{
					$model->agentCreditAmount	 = 0;
					$model->createQuotePartner	 = 2;
				}
				$acmSvcId					 = ($jsonDataArr['bkg_addon_details']['type2']['adn_id'] != null) ? AddonCabModels::model()->findByPk($jsonDataArr['bkg_addon_details']['type2']['adn_id'])->acm_svc_id_to : null;
				$model->bkg_vht_id			 = ($acmSvcId) ? SvcClassVhcCat::model()->findByPk($acmSvcId)->scv_model : $jsonDataArr['modelId'];
				$model->bkg_pickup_date		 = $pickupDate . " " . $time;
				$model->paymentChangesData	 = $jsonDataArr['paymentChangesData'];

				$paymentChangeArr = explode(',', $model->paymentChangesData);
				if (in_array("FBG", $paymentChangeArr))
				{
					$model->bkgPref->bkg_is_fbg_type = 2;
				}

				if (isset($jsonDataArr['isGozonow']) && $jsonDataArr['isGozonow'] == 1)
				{
					$model->bkgTrail->bkg_gnow_created_at	 = new CDbExpression('NOW()');
					$model->bkgPref->bkg_is_gozonow			 = $jsonDataArr['isGozonow'];
					$model->bkg_status						 = 2;
				}

				$scvId = $jsonDataArr['scvId'];
				if (($jsonDataArr['bkg_service_class'] == 4 || $jsonDataArr['bkg_service_class'] == 5) && $model->bkg_vht_id > 0)
				{
					$scvId = SvcClassVhcCat::getByVhtAndTier($model->bkg_vht_id, $jsonDataArr['bkg_service_class'])['scv_id'];
				}
				$scvId									 = ($jsonDataArr['bkg_addon_details']['type2']['adn_id'] != null) ? $acmSvcId : $scvId;
				$model->bkg_vehicle_type_id				 = $scvId;
				$model->bkg_copybooking_name			 = $jsonDataArr['bkg_copybooking_name'];
				$model->bkg_copybooking_ismail			 = $jsonDataArr['bkg_copybooking_ismail'];
				$model->bkg_copybooking_issms			 = $jsonDataArr['bkg_copybooking_issms'];
				$model->bkg_copybooking_email			 = $jsonDataArr['bkg_copybooking_email'];
				$model->bkg_copybooking_phone			 = $jsonDataArr['bkg_copybooking_phone'];
				$model->bkg_copybooking_country			 = $jsonDataArr['bkg_copybooking_country'];
				$model->bkgTrail->bkg_quote_expire_date	 = $quoteExpireDate . " " . $time1;
				$agentModel								 = Agents::model()->findByPk($model->bkg_agent_id);

				$model->bkgPref->bkg_duty_slip_required = ($jsonDataArr['bkg_duty_slip_required'] == '') ? $agentModel->agt_duty_slip_required : $jsonDataArr['bkg_duty_slip_required'];
				if (($agentModel->agt_duty_slip_required == 1 && $jsonDataArr['bkg_duty_slip_required'] == '') || $jsonDataArr['bkg_duty_slip_required'] == 1)
				{
					$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? 'Submit all receipts and duty slips required in this booking when trip completes.|</br>' : ' And ' . 'Submit all receipts and duty slips required in this booking when trip completes.|</br>';
				}

				$model->bkgPref->bkg_driver_app_required = ($jsonDataArr['bkg_driver_app_required'] == '') ? $agentModel->agt_driver_app_required : $jsonDataArr['bkg_driver_app_required'];
				if (($agentModel->agt_driver_app_required == 1 && $jsonDataArr['bkg_driver_app_required'] == '') || $jsonDataArr['bkg_driver_app_required'] == 1)
				{
					$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? 'Driver app use is requred.|</br>' : ' And ' . 'Driver app use is requred.|</br>';
				}
				$userTags = '';
				if ($model->bkgUserInfo->bkg_contact_id > 0)
				{
					$userTags = Contact::getTags($model->bkgUserInfo->bkg_contact_id);
				}
				if (count($jsonDataArr['bkg_tags']) > 0 || $userTags != '')
				{
					if (count($jsonDataArr['bkg_tags']) == 0)
					{
						$jsonDataArr['bkg_tags'] = [];
					}
					$userTagList = [];
					if (trim($userTags) != '')
					{
						$userTagList = array_merge($userTagList, explode(',', trim($userTags)));
					}
					$model->bkgTrail->bkg_tags = implode(',', array_unique(array_merge($userTagList, $jsonDataArr['bkg_tags'])));
				}
				$model->bkgPref->bkg_trip_otp_required = ($jsonDataArr['bkg_trip_otp_required'] == '') ? $agentModel->agt_otp_required : $jsonDataArr['bkg_trip_otp_required'];
				if (($agentModel->agt_otp_required == 1 || $agentModel->agt_otp_required == '') && $jsonDataArr['bkg_trip_otp_required'] == 1)
				{
					$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? 'OTP is required from customer.|</br>' : ' And ' . 'OTP is required from customer.|</br>';
				}
				else
				{
					$model->bkgPref->bkg_trip_otp_required	 = 0;
					$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? 'OTP not required from customer. Use Driver app to start, stop trip.|</br>' : ' And ' . 'OTP not required from customer,Use Driver app to start, stop trip.|</br>';
				}

				$model->bkgPref->bkg_water_bottles_required = ($jsonDataArr['bkg_water_bottles_required'] == '') ? $agentModel->agt_water_bottles_required : $jsonDataArr['bkg_water_bottles_required'];
				if (($agentModel->agt_water_bottles_required == 1 && $jsonDataArr['bkg_water_bottles_required'] == '') || $jsonDataArr['bkg_water_bottles_required'] == 1)
				{
					$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? 'Keep 2x 500ml water bottles in car for customer.|</br>' : ' And ' . 'Keep 2x 500ml water bottles in car for customer.|</br>';
				}

				$model->bkgPref->bkg_is_cash_required = ($jsonDataArr['bkg_is_cash_required'] == '') ? $agentModel->agt_is_cash_required : $jsonDataArr['bkg_is_cash_required'];
				if (($agentModel->agt_is_cash_required == 1 && $jsonDataArr['bkg_is_cash_required'] == '') || $jsonDataArr['bkg_is_cash_required'] == 1)
				{
					$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? 'Do not ask customer for cash.|</br>' : ' And ' . 'Do not ask customer for cash.|</br>';
				}

				$model->bkgPref->bkg_pref_req_other		 .= $agentModel->agt_pref_req_other . '|</br>';
				$model->bkg_instruction_to_driver_vendor .= ($jsonDataArr['bkg_instruction_to_driver_vendor'] == '') ? $model->bkgPref->bkg_pref_req_other : ' ' . $model->bkgPref->bkg_pref_req_other;

				$model->bkg_instruction_to_driver_vendor = $model->bkg_instruction_to_driver_vendor;
				if (SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id) == ServiceClass::CLASS_VALUE_CNG || SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id) == ServiceClass::CLASS_VLAUE_PLUS)
				{
					$model->bkgPref->bkg_cng_allowed = 1;
				}
				if ($model->bkg_agent_id > 0)
				{
					$model->bkgPref->bkg_driver_app_required = ($jsonDataArr['bkg_driver_app_required'] == '') ? $agentModel->agt_driver_app_required : $jsonDataArr['bkg_driver_app_required'];
				}
				else
				{
					$model->bkgPref->bkg_driver_app_required = 1;
				}
				if ($model->bkg_agent_id > 0 && $model->bkgPref->bkg_block_autoassignment == 0)
				{
					$model->bkgPref->bkg_block_autoassignment = ($agentModel->agt_vendor_autoassign_flag == 0) ? 1 : 0;
				}
				if (isset($multijson))
				{
					$model->multicityjson = $multijson;
				}
				//if (($model->bkg_booking_type == 3 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 1 || $model->bkg_booking_type == 5 || $model->bkg_booking_type == 8 || $model->bkg_booking_type == 4) && $brtArr != '')
				if (in_array($model->bkg_booking_type, [1, 2, 3, 4, 5, 8, 9, 10, 11, 15]) && $brtArr != '')
				{
					$cntBrt = sizeof($brtArr);

					$route_data	 = $multijson;
					$k			 = 0;
					foreach ($route_data as $k => $v)
					{
						$fromAdditionalAddress	 = ltrim(trim($brtArr[$k]['brt_additional_from_address'] . $brtArr[$k]['brt_additional_to_address']) . ', ', ', ');
						$toAdditionalAddress	 = ltrim(trim($brtArr[$k + 1]['brt_additional_to_address']) . ', ', ', ');

						$bookingRoute					 = new BookingRoute();
						$bookingRoute->attributes		 = $v;
						$bookingRoute->brt_from_location = $fromAdditionalAddress . $brtArr[$k]['brt_from_location'] . $brtArr[$k]['brt_to_location'];
						$bookingRoute->brt_to_location	 = $toAdditionalAddress . $brtArr[$k + 1]['brt_to_location'];

						if ($bookingRoute->brt_from_location == '' && $brtArr[$k]['brt_from_formatted_address'] != '')
						{
							$bookingRoute->brt_from_location = $brtArr[$k]['brt_from_formatted_address'];
						}
						if ($bookingRoute->brt_from_location == '' && $brtArr[$k]['brt_from_city_is_airport'] == 1 && $v['pickup_city'] > 0)
						{
							$bookingRoute->brt_from_location = Cities::getDisplayName($v['pickup_city']);
						}
						if ($bookingRoute->brt_to_location == '' && $brtArr[$k + 1]['brt_to_formatted_address'] != '')
						{
							$bookingRoute->brt_to_location = $brtArr[$k + 1]['brt_to_formatted_address'];
						}

						if ($bookingRoute->brt_to_location == '' && $brtArr[$k + 1]['brt_to_city_is_airport'] == 1 && $v['drop_city'] > 0)
						{
							$bookingRoute->brt_to_location = Cities::getDisplayName($v['drop_city']);
						}
						$bookingRoute->brt_from_latitude	 = round($brtArr[$k]['brt_from_latitude'] . $brtArr[$k]['brt_to_latitude'], 6);
						$bookingRoute->brt_from_longitude	 = round($brtArr[$k]['brt_from_longitude'] . $brtArr[$k]['brt_to_longitude'], 6);
						$bookingRoute->brt_to_latitude		 = round($brtArr[$k + 1]['brt_to_latitude'], 6);
						$bookingRoute->brt_to_longitude		 = round($brtArr[$k + 1]['brt_to_longitude'], 6);

						$bookingRoutes[] = $bookingRoute;
						if (trim($bookingRoute->brt_from_location) != '')
						{
							$route_data[$k]['pickup_address'] = $bookingRoute->brt_from_location;
						}
						if (trim($bookingRoute->brt_to_location) != '')
						{
							$route_data[$k]['drop_address'] = $bookingRoute->brt_to_location;
						}
					}
					$multijson					 = json_encode($route_data);
					$model->multicityjson		 = $multijson;
					$model->bkg_pickup_address	 = $bookingRoutes[0]->brt_from_location;
					$model->bkg_drop_address	 = $bookingRoutes[$k]->brt_to_location;
					$model->bkg_from_city_id	 = $route_data[0]['pickup_city'];
					$model->bkg_to_city_id		 = $route_data[(count($route_data) - 1)]['drop_city'];
					$model->bookingRoutes		 = $bookingRoutes;
					$bookingRoutes[0]->attributes;
					$model->pickupLat			 = $bookingRoutes[0]->brt_from_latitude;
					$model->pickupLon			 = $bookingRoutes[0]->brt_from_longitude;
					$model->dropLat				 = $bookingRoutes[$k]->brt_to_latitude;
					$model->dropLon				 = $bookingRoutes[$k]->brt_to_longitude;
					$model->bkg_pickup_lat		 = $bookingRoutes[0]->brt_from_latitude;
					$model->bkg_pickup_long		 = $bookingRoutes[0]->brt_from_longitude;
					$model->bkg_dropup_lat		 = $bookingRoutes[$k]->brt_to_latitude;
					$model->bkg_dropup_long		 = $bookingRoutes[$k]->brt_to_longitude;
				}
				$fromcity								 = $model->bkg_from_city_id;
				$tocity									 = $model->bkg_to_city_id;
				$model->bkgPf->bpf_bkg_id				 = $model->bkg_id;
				$model->bkgPf->bkg_ddbp_surge_factor	 = $bpf['ddbpSurgeFactor'];
				$model->bkgPf->bkg_ddbp_base_amount		 = $bpf['ddbpBaseAmount'];
				$model->bkgPf->bkg_dtbp_base_amount		 = $bpf['dtbpBaseAmount'];
				$model->bkgPf->bkg_ddbp_factor_type		 = $bpf['ddbpFactorType'];
				$model->bkgPf->bkg_route_route_factor	 = $bpf['ddbpRouteToRouteFactor'];
				$model->bkgPf->bkg_zone_zone_factor		 = $bpf['ddbpZoneToZoneFactor'];
				$model->bkgPf->bkg_zone_state_factor	 = $bpf['ddbpZoneToStateFactor'];
				$model->bkgPf->bkg_zone_factor			 = $bpf['ddbpZoneFactor'];
				$model->bkgPf->bkg_manual_surge_id		 = $bpf['manualSurgeId'];
				$model->bkgPf->bkg_manual_base_amount	 = $bpf['manualBaseAmount'];
				$model->bkgPf->bkg_regular_base_amount	 = $bpf['regularBaseAmount'];
				$model->bkgPf->bkg_ddbp_route_flag		 = $bpf['routeSurgeFlag'];
				$model->bkgPf->bkg_ddbp_master_flag		 = $bpf['ddbpMasterFlag'];
				$model->bkgPf->bkg_surge_applied		 = $bpf['surgeFactorUsed'];
				$model->bkgPf->bkg_dzpp_base_amount		 = $bpf['dzppBaseAmount'];
				$model->bkgPf->bkg_dzpp_surge_factor	 = $bpf['dzppSurgeFactor'];
				$model->bkgPf->bkg_surge_description	 = $bpf['dzppSurgeDesc'];
				$model->bkgPf->bkg_debp_base_amount		 = $bpf['debpBaseAmount'];
				$model->bkgPf->bkg_debp_surge_factor	 = $bpf['debpSurgeFactor'];
				$model->bkgPf->bkg_durp_base_amount		 = $bpf['durpBaseAmount'];
				$model->bkgPf->bkg_durp_surge_factor	 = $bpf['durpSurgeFactor'];
				$model->bkgPf->bkg_ddbpv2_base_amount	 = $bpf['ddbpv2BaseAmount'];
				$model->bkgPf->bkg_ddbpv2_surge_factor	 = $bpf['ddbpv2SurgeFactor'];
				$model->bkgPf->bkg_ddsbp_base_amount	 = $bpf['ddsbpBaseAmount'];
				$model->bkgPf->bkg_ddsbp_surge_factor	 = $bpf['ddsbpSurgeFactor'];
				$model->bkgPf->bkg_additional_param		 = $bpf['additional_param'];
				$model->bkgPf->bkg_partner_soldout		 = $bpf['partner_soldout'];
				$preDataArr								 = [];
				$preData								 = $bpf['additional_param'];
				if ($preData != '')
				{
					$preDataArr					 = json_decode($preData, true);
					$model->bkgPf->bkg_rte_id	 = $preDataArr['rateId'];
				}
				if (isset($bpf['gnowSuggestedOfferRange']) && $bpf['gnowSuggestedOfferRange'] != '')
				{
					$preDataArr	 = [];
					$preData	 = $model->bkgPf->bkg_additional_param;
					if ($preData != '')
					{
						$preDataArr = json_decode($preData, true);
					}
					$dataRange							 = $bpf['gnowSuggestedOfferRange'];
					$bkgAdditionalParam					 = ['vndGnowOfferSuggestion' => $dataRange];
					$model->bkgPf->bkg_additional_param	 = json_encode($bkgAdditionalParam + $preDataArr);
				}
				$model->bkgInvoice->bkg_surge_differentiate_amount	 = $differentiateSurgeAmount;
				$model->bkgTrail->bkg_followup_active				 = 0;
				$model->bkg_booking_id								 = 'temp';
				$success											 = $model->saveInfo($fromcity, $tocity);

				$followupDt			 = DateTimeFormat::DatePickerToDate($jsonDataArr['locale_followup_date']);
				$followupTime		 = DateTime::createFromFormat('h:i A', $jsonDataArr['locale_followup_time'])->format('H:i:00');
				$followupDtTime		 = $followupDt . ' ' . $followupTime;
				$model->mycallPage	 = 1;
				$createDate			 = $model->bkg_create_date;
				$pickupDate			 = $model->bkg_pickup_date;
				$followupDate		 = BookingPref::model()->getRecommendedPaymentFollowupTime($createDate, $pickupDate);
				ServiceCallQueue::add_v1($model, $followupDate, 1);

				if ($model->bkgPref->bkg_is_gozonow == 1)
				{
					$desc = "Gozonow Booking created";
					BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_CREATED, false);
				}

				$blockedLocation = $jsonDataArr['bkg_is_warned'];
				if ($blockedLocation == 1)
				{
					$model->bkgPref->bkg_is_warned	 = $blockedLocation;
					$model->bkgPref->save();
					$desc							 = "The pickup/drop location is not supported for this route";
					BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_BLOCKED_LOCATION, false);
				}

				if ($jsonDataArr['trip_user'] == 1 && $model->bkgTrail->bkg_platform == 2)
				{
					$model->bkgInvoice->setAdminFee();
					$model->bkgInvoice->save();
					$desc = "Base fare markup (₹49) applied for admin assisted booking(s)";
					BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), BookingLog::QUOTE_CREATED, false);
				}
				if ($jsonDataArr['cabNotSupported'] == 1)
				{
					$model->bkgPref->bkg_is_warned = 1;
					$model->bkgPref->save();
					BookingLog::model()->createLog($model->bkg_id, "Cab type not supported for this route", UserInfo::getInstance(), BookingLog::QUOTE_CREATED, false);
				}

				if ($success == true)
				{
					if ($paymentChangesData != '')
					{
						
					}
					$bookingRoute->clearQuoteSession();
					if (Yii::app()->request->isAjaxRequest)
					{
						$url1	 = '';
						$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
						if ($model->bkgPref->bkg_is_gozonow == 1)
						{
							$bkgId	 = $model->bkg_id;
							$tripId	 = $model->bkg_bcb_id;
							$hash	 = Yii::app()->shortHash->hash($bkgId);
							$url1	 = Yii::app()->createUrl('bkpn/' . $bkgId . '/' . $hash, []);
							BookingCab::gnowNotifyBulk($tripId);
						}
						$data = ['success' => $success, 'message' => 'Booking Created Successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url, 'url1' => $url1];

						echo json_encode($data);
						Yii::app()->end();
					}
				}
				else
				{
					if ($model->bkg_booking_type == '2' && $model->bkg_return_date_date != "" && $model->bkg_return_date_time != "")
					{
						$date1					 = DateTimeFormat::DatePickerToDate($model->bkg_return_date_date);
						$time1					 = date('H:i:00', strtotime($model->bkg_return_date_time));
						$model->bkg_return_date	 = $date1 . ' ' . $time1;
						//$model->bkg_return_time	 = $time1;
					}
					if (Yii::app()->request->isAjaxRequest)
					{
						$result = [];

						foreach ($model->getErrors() as $attribute => $errors)
						{
							$result[CHtml::activeId($model, $attribute)] = $errors;
						}

						$relations = array_keys($model->relations());

						foreach ($relations as $rel)
						{
							if ($model->hasRelated($rel))
							{
								$relModel = $model->$rel;

								if (is_array($relModel))
								{
									continue;
								}

								foreach ($relModel->getErrors() as $attribute => $errors)
								{
									$result[CHtml::activeId($relModel, $attribute)] = $errors;
								}
							}
						}

						foreach ($model->bkgUserInfo->getErrors() as $attribute => $errors)
						{
							$result[CHtml::activeId($model->bkgUserInfo, $attribute)] = $errors;
						}
						$ex			 = new Exception(json_encode($result), ReturnSet::ERROR_FAILED);
						$returnSet	 = ReturnSet::setException($ex);
						$data		 = ['success' => $success, 'errors' => $result];
						echo json_encode($data);
						Yii::app()->end();
					}
				}
				if ($packageid != '')
				{
					$model->preData = json_decode($_POST['packageJson']);
				}
				else
				{
					$model->preData = json_decode($multijson);
				}
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::renderJSONException($ex);
		}
		$createView = 'create_new';
		$this->render($createView, array(
			'model'		 => $model,
			'custType'	 => $custType,
		));
	}

	public function actionPartnerPreference()
	{
		
	}

	public function actionAssignvendor()
	{
		$agtid					 = Yii::app()->request->getParam('agtid');
		$bkid					 = Yii::app()->request->getParam('bkid');
		$booking				 = Yii::app()->request->getParam('Booking');
		$bid_amount				 = Yii::app()->request->getParam('bid_amount');
		$forbiddenVendorRemarks	 = Yii::app()->request->getParam('forbiddenVendor');
		$from_uberlist			 = Yii::app()->request->getParam('from_uberlist');
//		$agtid					 = '43';
//		$bkid					 = '802749';
		$forbiddenVendorRemarks	 = $forbiddenVendorRemarks == '0' ? '' : $forbiddenVendorRemarks;
		$remark					 = '';
		if (is_array($booking) && isset($booking['bkg_user_message']))
		{
			$remark = $booking['bkg_user_message'];
		}

		$SearchMark = Booking::model()->usermarkbadByBookingId($bkid);
		if ($SearchMark['usr_mark_customer_count'] > 0 && $remark == '')
		{
			$userId = $SearchMark['bkg_user_id'];
			echo json_encode(['success'	 => false, 'errors'	 => [
					'code'		 => 1,
					'message'	 => 'Customer Marked as Bad',
					'url'		 => Yii::app()->createUrl('admin/booking/markedbadmessage', ['bkgId' => $bkid, 'agtId' => $agtid, 'userId' => $userId])]
			]);
			Yii::app()->end();
		}
		#$transaction = DBUtil::beginTransaction();
		try
		{
			/* @var $model Booking */
			$userId	 = Yii::app()->user->getId();
			$model	 = Booking::model()->findByPk($bkid);

			// Update Gozo Amount
			BookingInvoice::updateGozoAmount($model->bkg_bcb_id);

			$newStatus = 3;
			if ($forbiddenVendorRemarks == 1)
			{
				
			}
			$checkToBeAssign = BookingSub::model()->checkPreAssignmentValidation($model, $bid_amount);
			if (!$checkToBeAssign)
			{
				throw new Exception(json_encode("You are not allowed to assign this booking."), 1);
			}
			$assignMode	 = 1;
			$res		 = $model->bkgBcb->assignVendor($model->bkgBcb->bcb_id, $agtid, $bid_amount, $remark, UserInfo::getInstance(), $assignMode);

			if (!$res->isSuccess())
			{
				$errMessage = Filter::getNestedValues($res->getErrors());
				throw $res->getException();
			}
			#DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			echo json_encode(['success'	 => false,
				'errors'	 => [
					'code'		 => 2,
					'message'	 => (!is_array($e->getMessage())) ? trim($e->getMessage(), '"') : $e->getMessage()
				]
			]);
			ReturnSet::setException($e);
			#DBUtil::rollbackTransaction($transaction);
			Yii::app()->end();
		}


		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true, 'newStatus' => $newStatus];
			echo json_encode($data);
			Yii::app()->end();
		}
		if ($from_uberlist == 1)
		{
			$this->redirect(array('uberlist'));
		}

		if ($bkid2 != 0 && $bkid2 != '')
		{
			$this->redirect(array('match'));
		}
		else
		{
			$tab = 3;
			$this->redirect(array('list', 'tab' => $tab));
		}
	}

	public function actionAssigncabdriver()
	{
		Logger::info('Assign cab driver====>');
		$request	 = Yii::app()->request;
		$user_id	 = Yii::app()->user->getId();
		$bkid		 = $request->getParam('booking_id');
		$agtid		 = $request->getParam('agtid');
		$vehicle_id	 = $request->getParam('vhc');
		$cav		 = $request->getParam('cav');
		$documents	 = $request->getParam('Document', null);

		/* @var $model Booking */
		$dmodel				 = new Document();
		$modelVehicleDocs	 = new VehicleDocs();
		$bmodel				 = Booking::model()->findByPk($bkid);
		$bcabModel			 = $bmodel->getBkgCabModel();
		$remainingSeats		 = 0;
		$checkaccess		 = Yii::app()->user->checkAccess('CriticalAssignment');
		Logger::trace("bookingId" . $bkid);

//	if ($bmodel->bkgAgent->agt_payment_collect_flag == 1 && ($bmodel->bkg_agent_id != NULL || $bmodel->bkg_agent_id != ""))
//	{
//	    $data = "Channel Partner payment outstanding limit exceed. First collect outstanding amount from Channel Partner";
//	    echo $data;
//	    Yii::app()->end();
//	}

		if ($bmodel->bkg_flexxi_type == 2 && !$checkaccess)
		{
			$remainingSeats	 = Booking::model()->getRemainingSeats($bcabModel->bcb_id);
			$fpId			 = BookingSub::model()->getPromoterIdForFlexxiBooking($bcabModel->bcb_id);
			if ($fpId != '')
			{
				$remainingSeats = 0;
			}
		}

		if (isset($_POST['BookingCab']) && $_REQUEST['booking_id'] > 0)
		{
			$arr		 = [];
			$arrDoc		 = [];
			$arr		 = $_POST['BookingCab'];
			$arrDoc		 = $_POST['Document'];
			Vehicles::approveVehicleStatus($arr['bcb_cab_id']);
			$bcabModel	 = $bmodel->getBkgCabModel();
			$transaction = DBUtil::beginTransaction();
			$oldStatus	 = $bmodel->bkg_status;
			$data		 = ['success' => true, 'oldStatus' => $oldStatus];
			if ($arr['isVendorCabFleet'] == "0")
			{
				$dataVendorVechile	 = ['vendor' => $arr['bcb_vendor_id'], 'vehicle' => $arr['bcb_cab_id']];
				$linked				 = VendorVehicle::model()->checkAndSave($dataVendorVechile);
				if (!$linked)
				{
					DBUtil::rollbackTransaction($transaction);
					if ($request->isAjaxRequest)
					{
						$bcabModel->addError('BookingCab_bcb_cab_id', "Error! Unable to add cab");
						$data = ['success' => false, 'errors' => $bcabModel->getErrors()];
						echo json_encode($data);
						Yii::app()->end();
					}
					else
					{
						$this->redirect(array('list', 'tab' => $tab));
					}
				}
			}
			if ($arr['isVendorDriverFleet'] == "0")
			{
				$dataVendorDriver	 = ['vendor' => $arr['bcb_vendor_id'], 'driver' => $arr['bcb_driver_id']];
				$resLinked			 = VendorDriver::model()->checkAndSave($dataVendorDriver);
				if (!$resLinked)
				{
					DBUtil::rollbackTransaction($transaction);
					if ($request->isAjaxRequest)
					{
						$bcabModel->addError('BookingCab_bcb_driver_id', "Error! Unable to add driver");
						$data = ['success' => false, 'errors' => $bcabModel->getErrors()];
						echo json_encode($data);
						Yii::app()->end();
					}
					else
					{
						$this->redirect(array('list', 'tab' => $tab));
					}
				}
				VendorStats::model()->updateCountDrivers($arr['bcb_vendor_id']);
			}
			$bcabModel->attributes	 = $arr;
			$bcabModel->chk_user_msg = $arr['chk_user_msg'];
			$cab_type				 = $bmodel->bkgSvcClassVhcCat->scv_vct_id;
			$sccClass				 = $bmodel->bkgSvcClassVhcCat->scv_scc_id;
			//$cabtypeModel			 = VehicleTypes::model()->findByPk($type_id);
			//$cab_type				 = $cabtypeModel->vht_car_type;
			//$cab_type				 = $cabtypeModel->vht_VcvCatVhcType->vcv_vct_id;
			Logger::trace("before assign cab" . $bcabModel->bcb_cab_id);
			$assigned				 = $bcabModel->assigncabdriver($bcabModel->bcb_cab_id, $bcabModel->bcb_driver_id, $cab_type, UserInfo::getInstance(), $scenario);
			Logger::trace("after cab assign" . $bcabModel->bcb_cab_id);
			$modelVehicles			 = Vehicles::model()->findByPk($bcabModel->bcb_cab_id);
			if ($modelVehicles->vhc_approved == 3)
			{
				$bcabModel->addError('BookingCab_bcb_cab_id', "Cab not approved.Cannot assign.");
				$data = ['success' => false, 'errors' => $bcabModel->getErrors()];
				echo json_encode($data);
				Yii::app()->end();
			}
			if ($bcabModel->hasErrors())
			{
				$result = [];
				foreach ($bcabModel->getErrors() as $attribute => $errors)
				{
					$result[CHtml::activeId($bcabModel, $attribute)] = $errors;
				}
				$data = ['success' => false, 'errors' => $result];
				DBUtil::rollbackTransaction($transaction);
			}
			else
			{
				$drvModel		 = Drivers::model()->findByPk($arr['bcb_driver_id']);
				$contactModel	 = Contact::model()->findByPk($drvModel->drvContact->ctt_id);
				$arrVehicles	 = $request->getParam('Vehicles');
				$successVehicles = VehicleDocs::model()->addVehiclesPendingDocument($modelVehicles, $arrVehicles, $modelVehicleDocs);
				$successContact	 = Contact::model()->addDriverPendingDocument($drvModel, $contactModel, $dmodel, $arr, $documents);
				if ($successVehicles && $successContact)
				{
					$success = true;
				}
				if ($bmodel->bkgBcb->bcb_driver_id != "")
				{
					$userInfo		 = UserInfo::getInstance();
					$type			 = Booking::model()->userArr[$userInfo->userType];
					$message		 = "Booking " . $bmodel->bkg_booking_id . " Updated by $type";
					$image			 = NULL;
					$bkgID			 = $bmodel->bkg_booking_id;
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$payLoadData	 = ['EventCode' => Booking::CODE_CABDRIVER_ASSIGNED];
					$success		 = AppTokens::model()->notifyDriver($bmodel->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "Driver assigned", $bkgID);
				}
			}
//            $isCng = $modelVehicles->vhc_has_cng;
//			if ($sccClass == 2 && $isCng == 1)
//			{
//				$bcabModel->addError('BookingCab_bcb_cab_id', "Can't assign CNG cab for this booking.");
//				$data = ['success' => false, 'errors' => $bcabModel->getErrors()];
//				echo json_encode($data);
//				Yii::app()->end();
//			}

			$data['firstUnaaproved'] = false;
			if ($assigned && $bcabModel->bcbCab->vhc_approved != 1)
			{
				$data['firstUnaaproved'] = true;
			}
			if ($data['success'])
			{
				DBUtil::commitTransaction($transaction);
			}
			else
			{
				echo json_encode($data);
				Yii::app()->end();
			}
			if ($request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
			if ($data['success'])
			{
				$this->redirect(array('list', 'tab' => $tab));
			}
		}
		$vndId = $bcabModel->bcb_vendor_id;
//		$relVndIds	 = Vendors::getPrimaryId($vndId);

		$relVndIds = Vendors::getRelatedIds($vndId);

		$driverJSON	 = Drivers::model()->getJSONbyVendor($relVndIds);
		$vehicleList = Vehicles::model()->getJSONbyTypeNVendor(0, $relVndIds);

		$bcabModel->scenario = 'assigncabdriver';

		$this->renderPartial('assigncabdriver', array('model' => $bcabModel, 'bmodel' => $bmodel, 'vehicleList' => $vehicleList, 'driverJSON' => $driverJSON, 'remainingSeats' => $remainingSeats), false, true);
	}

	public function actionShowvendor()
	{
		$time				 = Filter::getExecutionTime();
		$GLOBALS['time'][1]	 = $time;
		$bkid				 = Yii::app()->request->getParam('booking_id');
		$bkid2				 = Yii::app()->request->getParam('booking2_id');
		$findReturn			 = Yii::app()->request->getParam('ret', '1');
		$model				 = new Vendors('search');
		$phoneModel			 = new ContactPhone('search');
		if (isset($_REQUEST['Vendors']) || isset($_REQUEST['ContactPhone']))
		{
			$model->attributes		 = Yii::app()->request->getParam('Vendors');
			$phoneModel->attributes	 = Yii::app()->request->getParam('ContactPhone');
			$model->vnd_phone		 = $phoneModel->phn_phone_no;
		}
		Logger::create("2");
		$time						 = Filter::getExecutionTime();
		$GLOBALS['time'][2]			 = $time;
		$bkModel					 = Booking::model()->findByPk($bkid);
		$pickupDate					 = date("Y-m-d", strtotime($bkModel->bkg_pickup_date));
		$manualAssignFlag			 = $bkModel->bkgPref->bkg_manual_assignment;
		$checkPreVendorAssignAccess	 = Yii::app()->user->checkAccess('preVendorAssignment');
		$isNMIcheckedZone			 = InventoryRequest::model()->checkNMIzonebyBkg($bkid);
		$assignBlocked				 = false;
		$checkaccess				 = Yii::app()->user->checkAccess('CriticalAssignment');
		$isUserAllocated			 = $bkModel->bkgPref->bpr_assignment_id == UserInfo::getUserId() && UserInfo::getUserType() == UserInfo::TYPE_ADMIN;

		$checkShowBids = Yii::app()->user->checkAccess('showBidAmount') || $isUserAllocated || (in_array($bkModel->bkg_status, [3, 5, 6, 7]));

		if ($checkaccess)
		{
			$assignBlocked = true;
		}
		$queueId								 = ServiceCallQueue::TYPE_BAR . "," . ServiceCallQueue::TYPE_CSA . "," . ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL;
		$scqId									 = ServiceCallQueue::getDetailsByQueueBkgCsr($queueId, $bkid, UserInfo::getUserId(), 1);
		$dataProvider							 = $model->manualAssignVendorList($bkid);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('assignVendor', array('dataProvider' => $dataProvider, 'bkModel' => $bkModel, 'assignBlocked' => $assignBlocked, 'bkid' => $bkid, 'bkid2' => $bkid2, 'model' => $model, 'phoneModel' => $phoneModel, 'isNMIcheckedZone' => $isNMIcheckedZone, 'checkBidAmountAccess' => $checkShowBids, 'inScq' => $scqId), false, true);
	}

	public function actionGetamount()
	{
		$success	 = false;
		$params		 = $_GET;
		$rt_id		 = Yii::app()->request->getParam('routeId'); //$_GET['rt_id'];
		$vht_id		 = Yii::app()->request->getParam('vehicleId'); //$_GET['vht_id'];
		$rate		 = Rate::model()->fetchRatebyRutnVht($rt_id, $vht_id);
		$exclRate	 = Rate::model()->fetchExclRatebyRutnVht($rt_id, $vht_id);
// echo $rate;exit;
		echo CJSON::encode(array('routeRate' => $rate, 'rutExclRate' => $exclRate));
	}

	public function actionGetcarmodel()
	{
		$rt_id		 = Yii::app()->request->getParam('rt_id'); //$_GET['rt_id'];
// $arrCities = Rate::model()->getCarModelbyrut($rt_id);
		$arrCities	 = Rate::model()->getCarModelbyrut(0);

		echo CJSON::encode($arrCities);
		Yii::app()->end();
	}

	public function actionVerifybooking()
	{
		$bkgId			 = Yii::app()->request->getParam('bkid'); //$_POST['bkid'];
		$model			 = Booking::model()->findByPk($bkgId);
		$oldModel		 = clone $model;
		$model->scenario = 'adminupdate';

		if ($model->validate())
		{
			$sendConf	 = false;
			$success	 = false;
			$transaction = DBUtil::beginTransaction();
			try
			{
				if ($model->bkgUserInfo->bkg_user_id == '')
				{
					$userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Admin);
				}
				if ($userModel)
				{
					$model->bkgUserInfo->bkg_user_id = $userModel->user_id;
					$model->save();
				}
				$errorMsg = '';
				if ($model->bkg_agent_id > 0)
				{
					if (count($model->getErrors()) > 0)
					{
						$errorMsg = "(" . implode(',', json_encode($model->getErrors())) . ")";
					}
					throw new Exception("Agent bookings can be verified by the agent only." . $errorMsg);
				}
				else
				{
					if ($model->bkg_status == 1)
					{
						$success = true;
					}
				}
				$result['tab']		 = 2;
				$result['success']	 = $success;
				$result['errors']	 = '';

				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{

				$result['errors'] = 'Error occurred while verifying.' . $e->getMessage();
				$model->addError("bkg_id", $e->getMessage());
				DBUtil::rollbackTransaction($transaction);
			}
		}
		else
		{
			$result['tab']		 = 1;
			$result['success']	 = false;
			$result['errors']	 = 'Incomplete data';
		}
		echo json_encode($result);
		Yii::app()->end();
	}

	public function actionVerifycustomermarked()
	{
		$bkgId			 = Yii::app()->request->getParam('bkid'); //$_POST['bkid'];
		$agtid			 = Yii::app()->request->getParam('agtid');
		/* var $model Booking */
		$model			 = new Booking();
		$markBadCustomer = $model->usermarkbadByBookingId($bkgId);
		$result			 = array();
		if ($markBadCustomer > 0)
		{
			$result['bkid']		 = $bkgId;
			$result['agtid']	 = $agtid;
			$result['markedbad'] = $markBadCustomer;
			$result['success']	 = true;
		}
		else
		{
			$result['bkid']		 = $bkgId;
			$result['agtid']	 = $agtid;
			$result['markedbad'] = $markBadCustomer;
			$result['success']	 = false;
		}
		echo json_encode($result);
		Yii::app()->end();
	}

	public function actionUnverifieddelbooking()
	{
		$this->actionDelbooking();
	}

	public function actionDelbooking()
	{
		$bkid	 = Yii::app()->request->getParam('booking_id'); //$_POST['booking_id'];
		$reason1 = Yii::app()->request->getParam('bkreason'); //$_POST['bkreason']
		$reason	 = trim($reason1);
		if (isset($_POST['bkreason']) && isset($_POST['bk_id']) && $reason != '')
		{
			$bk_id		 = Yii::app()->request->getParam('bk_id'); //$_POST['bk_id'];
			$model		 = Booking::model()->findByPk($bk_id);
			$oldModel	 = clone $model;

			if ($reason == 'Others')
			{
				$reason = Yii::app()->request->getParam('bkreasontext');
			}
			$scvVctId	 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
			if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && (($model->bkg_flexxi_type == 1 && count($subids		 = Booking::model()->getSubsIdsbyPromoIds($bk_id, $model->bkg_bcb_id)) > 0) || ($model->bkg_flexxi_type == 2 && $model->bkgInvoice->bkg_promo1_code != 'FLATRE1')))
			{
				$result	 = false;
				$msg	 = "Error! Cannot delete a booking while other bookings are related to it.";
			}
			else
			{
				$result = Booking::model()->delBooking($bk_id, $reason);
			}
			if (is_array($result))
			{
				$bookingModel = Booking::model()->findByPk($bk_id);
//				if ($bookingModel != '' && $bookingModel->bkgUserInfo->bkg_user_id != '')
//				{
//					$notificationId	 = substr(round(microtime(true) * 1000), -5);
//					$payLoadData	 = ['bookingId' => $bookingModel->bkg_booking_id, 'EventCode' => Booking::CODE_DELETED];
//					$success		 = AppTokens::model()->notifyConsumer($bookingModel->bkgUserInfo->bkg_user_id, $payLoadData, $notificationId, "(" . $bookingModel->bkg_booking_id . ") " . $bookingModel->bkgFromCity->cty_name . " to " . $bookingModel->bkgToCity->cty_name . " on " . $bookingModel->bkg_pickup_date, "Booking deleted(" . $bookingModel->bkg_booking_id . ")");
//				}

				$desc				 = "Booking deleted manually.(Reason: " . $reason . ")";
				$userInfo			 = UserInfo::getInstance();
				$eventid			 = BookingLog::BOOKING_DELETED;
				BookingLog::model()->createLog($bk_id, $desc, $userInfo, $eventid, $oldModel);
				$tab				 = $result['oldStatus'];
				$result['success']	 = true;
			}
			else
			{
				$result				 = [];
				$tab				 = 2;
				$result['success']	 = false;
				$result['message']	 = $msg;
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('delbooking', array('bkid' => $bkid));
	}

	public function actionAddFollowup()
	{
		$booking_id								 = Yii::app()->request->getParam('booking_id'); //$_POST['booking_id'];
		$model									 = Booking::model()->findByPk($booking_id);
		$model->bkgTrail->bkg_followup_comment	 = '';
		$model->bkgTrail->scenario				 = 'followup_scope';
		if (isset($_POST['BookingTrail']))
		{
			$model->bkgTrail->attributes		 = Yii::app()->request->getParam('BookingTrail');
			$model->bkgTrail->bkg_followup_time	 = Yii::app()->request->getParam('BookingTrail')['bkg_followup_time'];
			$arr								 = $model->bkgTrail->attributes;
			if ($model->bkgTrail->validate())
			{
				$transaction = DBUtil::beginTransaction();
				try
				{
					$date									 = DateTimeFormat::DatePickerToDate($arr['bkg_followup_date']);
					$time									 = date('H:i:s', strtotime($model->bkgTrail->bkg_followup_time));
					$model->bkgTrail->bkg_follow_type_id	 = 10;
					$model->bkgTrail->bkg_followup_date		 = $date . ' ' . $time;
					//$model->bkgTrail->bkg_followup_time		 = $time;
					$model->bkgTrail->bkg_followup_comment	 = $arr['bkg_followup_comment'];
					$model->bkgTrail->bkg_followup_active	 = 1;
					if ($model->bkgTrail->save())
					{
						$status		 = $model->bkg_status;
						$userInfo	 = UserInfo::getInstance();
						$desc		 = 'Follow-up flag set for ' . date('d/m/Y', strtotime($model->bkgTrail->bkg_followup_date)) . ' at ' . date('h:i A', strtotime($model->bkgTrail->bkg_followup_date)) . ', comments: ' . $arr['bkg_followup_comment'] . '';
						BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::FOLLOWUP_ASSIGN, false, false);
						$success	 = true;
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						$success = false;
					}
				}
				catch (Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					$success = false;
				}
			}
			else
			{
				$success = false;
			}


			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $success, 'oldStatus' => $status];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$this->renderPartial('add_followup', array('bkid' => $booking_id, 'model' => $model), false, true);
	}

	public function actionSendcabdriverinfo1()
	{

		$booking_id						 = Yii::app()->request->getParam('booking_id');
		/* @var $model Booking */
		$model							 = Booking::model()->findByPk($booking_id);
		$model->bkg_contact_no_chkbox	 = 1;
		$model->bkg_user_email_chkbox	 = 1;
		$model->scenario				 = 'sendcabdriver';
		if (isset($_POST['Booking']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Booking');
			$transaction		 = DBUtil::beginTransaction();
			if ($model->validate())
			{
				$success = false;
				try
				{
					$bkgId		 = $model->bkg_id;
					$smsSent	 = $emailSent	 = 0;
					if ($model->bkg_contact_no_chkbox == '1')
					{
						/* @var $msgCom smsWrapper */
						$msgCom	 = new smsWrapper();
						if ($msgCom->msgToUserBookingConfirmed($model, $type	 = 1, NULL, UserInfo::TYPE_SYSTEM))
						{
							$smsSent = 1;
						}
					}
					if ($model->bkg_user_email_chkbox == '1')
					{
						/* @var $emailObj emailWrapper */
						$emailObj = new emailWrapper();
						if ($emailObj->sendCabDriverDetailsToCustomer($bkgId))
						{
							$emailSent = 1;
						}
					}
					BookingLog::model()->maintainLogForCabDriverDetails($model, $emailSent, $smsSent);
					DBUtil::commitTransaction($transaction);
					$success = true;
					$return	 = ['success' => $success, 'oldStatus' => $model->bkg_status];
				}
				catch (Exception $ex)
				{
					$success = false;
					$result	 = [];
					if ($model->hasErrors())
					{
						foreach ($model->getErrors() as $attribute => $errors)
							$result[CHtml::activeId($model, $attribute)] = $errors;
					}
					$return = ['success' => $success, "errors" => $result];
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else
			{
				$success = false;
				$result	 = [];
				if ($model->hasErrors())
				{
					foreach ($model->getErrors() as $attribute => $errors)
						$result[CHtml::activeId($model, $attribute)] = $errors;
				}
				$return = ['success' => $success, "errors" => $result];
				DBUtil::rollbackTransaction($transaction);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo CJSON::encode($return);
				Yii::app()->end();
			}
			Yii::app()->end();
		}
		$this->renderPartial('send_cabdriver_info', array('bkid' => $booking_id, 'model' => $model), false, true);
	}

	public function actionCompletefollowup()
	{
		$booking_id					 = Yii::app()->request->getParam('booking_id');
		$followup_active			 = Yii::app()->request->getParam('followup_active');
		$model						 = Booking::model()->findByPk($booking_id);
		$model->bkgTrail->scenario	 = 'followup_scope';
		if (isset($_POST['Booking']))
		{
			$model->bkgTrail->attributes		 = Yii::app()->request->getParam('BookingTrail');
			$model->bkgTrail->bkg_followup_time	 = Yii::app()->request->getParam('BookingTrail')['bkg_followup_time'];
			$arr								 = Yii::app()->request->getParam('BookingTrail');
			$userInfo							 = UserInfo::getInstance();
			if ($model->bkgTrail->validate())
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					if ($arr['bkg_followup_active'] == 1)
					{
						$date									 = DateTimeFormat::DatePickerToDate($arr['bkg_followup_date']);
						$time									 = date('H:i:s', strtotime($model->bkgTrail->bkg_followup_time));
						$model->bkgTrail->bkg_follow_type_id	 = 10;
						$model->bkgTrail->bkg_followup_date		 = $date . ' ' . $time;
						//$model->bkgTrail->bkg_followup_time		 = $time;
						$model->bkgTrail->bkg_followup_comment	 = $arr['bkg_followup_comment'];
						$model->bkgTrail->bkg_followup_active	 = 1;
						if ($model->bkgTrail->save())
						{
							$desc	 = 'Follow-up flag set for ' . date('d/m/Y', strtotime($model->bkgTrail->bkg_followup_date)) . ' at ' . date('h:i A', strtotime($model->bkgTrail->bkg_followup_date)) . ', comments: ' . $arr['bkg_followup_comment'] . '';
							BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::FOLLOWUP_CHANGE, false, false);
							$status	 = $model->bkg_status;
							$success = true;
							$transaction->commit();
						}
						else
						{
							$success = false;
						}
					}
					else
					{
						$currentDate							 = date('Y-m-d', strtotime(date('Y-m-d H:i:s')));
						$currentTime							 = date('H:i:s', strtotime(date('Y-m-d H:i:s')));
						$model->bkgTrail->bkg_followup_date		 = $currentDate . ' ' . $currentTime;
						//$model->bkgTrail->bkg_followup_time		 = $currentTime;
						$model->bkgTrail->bkg_followup_comment	 = 'Follow Completed.';
						$model->bkgTrail->bkg_followup_active	 = 0;
						if ($model->bkgTrail->save())
						{
							$desc	 = 'Follow up completed @ ' . date('d/m/Y', strtotime($model->bkgTrail->bkg_followup_date)) . ' at ' . date('h:i A', strtotime($model->bkgTrail->bkg_followup_date)) . '.';
							BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::FOLLOWUP_COMPLETE, false, false);
							$status	 = $model->bkg_status;
							$success = true;
							$transaction->commit();
						}
						else
						{
							$success = false;
						}
					}
					$model->bkgTrail->updateUnverifiedFollowup();
				}
				catch (Exception $ex)
				{

					$transaction->rollback();
					$success = false;
				}
			}
			else
			{
				$success = false;
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $success, 'oldStatus' => $status];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$this->renderPartial('followup_complete', array('bkid' => $booking_id, 'model' => $model), false, true);
	}

	public function actionAddremarks()
	{
		$booking_id		 = Yii::app()->request->getParam('booking_id');
		$bookingPref	 = Yii::app()->request->getParam('BookingPref');
		$bookingTrail	 = Yii::app()->request->getParam('BookingTrail');
		$hash			 = Yii::app()->request->getParam('hash');
		$followuplist	 = ServiceCallQueue::getfollowUpsByBkg($booking_id);

		$followupArr = ['list' => $followuplist];

		/* @var $model Booking */
		$userInfo					 = UserInfo::getInstance();
		$model						 = Booking::model()->findByPk($booking_id);
		$isNMIcheckedZone			 = InventoryRequest::model()->checkNMIzonebyBkg($booking_id);
		$oldData					 = Booking::model()->getDetailsbyId($model->bkg_id);
		$events						 = array('29', '70');
		$logList					 = BookingLog::model()->getByBookingIdEventId($booking_id, $events);
		$logModel					 = new BookingLog();
		$logModel->blg_booking_id	 = $booking_id;
		$logModel->blg_remark_type	 = '1';
		$logModel->scenario			 = 'addremarks';

		if (isset($_POST['BookingLog']))
		{
			$transaction = DBUtil::beginTransaction();

			$isEscalated		 = $model->bkgTrail->bkg_escalation_status;
			$isAccountFlagged	 = $model->bkgPref->bkg_account_flag;
			$isDutySlipRequired	 = $model->bkgPref->bkg_duty_slip_required;
			$isFollowupSet		 = 0;
			$isNMI				 = $model->bkgTrail->btr_nmi_flag;
			$oldModel			 = clone $model;
			$oldTrailModel		 = clone $model->bkgTrail;
			$bkg_status			 = $model->bkg_status;

			$logModel->attributes							 = Yii::app()->request->getParam('BookingLog');
			$model->bkgTrail->attributes					 = $bookingTrail;
			$model->bkgPref->attributes						 = $bookingPref;
			$model->bkgTrail->btr_escalation_assigned_team	 = implode(",", $bookingTrail['btr_escalation_assigned_team']);
			$titcket_no										 = $_POST['BookingLog']['titcket_no'];

			$params							 = [];
			$params['blg_booking_status']	 = $bkg_status;
			$params['blg_remark_type']		 = trim($arr['blg_remark_type']);
			$params['blg_remark_type']		 = $arr['blg_remark_type'];

			$desc		 = $logModel->buildTxt($titcket_no);
			$checkLog	 = 0;
			try
			{
				if ($hash != '')
				{
					$followupDt						 = DateTimeFormat::DatePickerToDate($bookingTrail['locale_followup_date']);
					$followupTime					 = DateTime::createFromFormat('h:i A', $bookingTrail['locale_followup_time'])->format('H:i:00');
					$followupDtTime					 = $followupDt . ' ' . $followupTime;
					$fdesc							 = $logModel->blg_desc;
					ServiceCallQueue::add_v1($model, $followupDtTime, 1, 0, $fdesc);
					BookingTrail::unAssignCsr($model->bkg_id);
					$model->bkgTrail->bkg_assign_csr = 0;
				}

				if ($model->bkgPref->bkg_duty_slip_required != $isDutySlipRequired)
				{
					$checkLog++;
					$model->bkgPref->UpdateDutySlipStatus($desc, UserInfo::getInstance());
				}

				if ($bookingPref['bkg_penalty_flag'] == 1)
				{
					$checkLog++;
					$model->bkgPref->bkg_account_flag	 = 1;
					$model->bkgPref->bkg_penalty_flag	 = 1;
					$model->bkgPref->setPenaltyFlag($desc, $userInfo);
				}
				if ($model->bkgPref->bkg_account_flag != $isAccountFlagged && $model->bkgPref->bkg_account_flag == 1)
				{
					$checkLog++;
					$model->bkgPref->setAccountingFlag($desc, $userInfo);
				}

				$success = $model->bkgTrail->setFollowup($desc, $oldTrailModel, $userInfo);
				if ($success)
				{
					$checkLog++;
				}

				if ($isEscalated != $model->bkgTrail->bkg_escalation_status)
				{
					$escalationDesc = "";
					if ($model->bkgTrail->bkg_escalation_status == 1)
					{
						$escalationLbl	 = $model->bkgTrail->escalation[$model->bkgTrail->btr_escalation_level]['color'];
						$tlID			 = $model->bkgTrail->btr_escalation_assigned_lead;
						$tlNAme			 = Admins::model()->getFullNameById($tlID);
						$escalationDesc	 = "Level : $escalationLbl; TL - $tlNAme |";
					}
					$checkLog++;
					$result = $model->bkgTrail->updateEscalation($desc, $userInfo, $escalationDesc);
				}
				else
				{
					if ($model->bkgTrail->bkg_escalation_status == 1 && $model->bkgTrail->btr_escalation_level == 2)
					{
						$model->bkgTrail->bkg_escalation_status = '0';
						$model->bkgTrail->save();
						$model->bkgTrail->updateEscalation($desc, $userInfo);
					}
					else
					{
						$model->bkgTrail->save();
					}
				}
				if (($isNMI != $model->bkgTrail->btr_nmi_flag) && ($isNMIcheckedZone < 1))
				{
					$checkLog++;
					$model->bkgTrail->updateNMI($desc, $oldTrailModel, $userInfo);
				}

				if (isset($addtionalDesc) && $addtionalDesc != '')
				{
					$checkLog++;
					$separator = "";
					if ($model->bkg_instruction_to_driver_vendor)
					{
						$separator = " | ";
					}
					$model->bkg_instruction_to_driver_vendor = ($model->bkg_instruction_to_driver_vendor . $separator . $addtionalDesc);
					if ($model->save())
					{
						$desc = "Booking modified: " . $desc;
						BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::BOOKING_MODIFIED, $oldModel, $params);
					}
				}
				if ($checkLog == 0)
				{
					$eventId_ra = BookingLog::REMARKS_ADDED;
					BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId_ra, $oldModel, $params);
				}

				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $bkg_status, 'escalationStatus' => $result, 'hash' => $hash];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		if ($hash != '')
		{
			$this->renderPartial('addremark_mycall',
					array('bkid'				 => $booking_id,
						'bookModel'			 => $model,
						'logModel'			 => $logModel,
						'logList'			 => $logList,
						'userInfo'			 => $userInfo,
						'isNMIcheckedZone'	 => $isNMIcheckedZone), false, true);
		}
		else
		{
			$this->renderPartial('addremark',
					array('bkid'				 => $booking_id,
						'bookModel'			 => $model,
						'logModel'			 => $logModel,
						'logList'			 => $logList,
						'userInfo'			 => $userInfo,
						'followuplist'		 => json_encode($followupArr),
						'isNMIcheckedZone'	 => $isNMIcheckedZone), false, true);
		}
	}

	public function actionStartChat()
	{
		$entity_id	 = Yii::app()->request->getParam('entityId');
		$entity_type = Yii::app()->request->getParam('entityType');
		$entity_type = ($entity_type > 0) ? $entity_type : 0;

		$model = new ChatLog();

		$chatModel = Chats::model()->chatDetails($entity_id, $entity_type);

		$this->pageTitle = "Gozo Messaging";

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('startchat', array('model' => $model, 'entityId' => $entity_id, 'entityType' => $entity_type, 'chatModel' => $chatModel), false, $outputJs);
	}

	public function actionAddmarkremark()
	{
		$booking_id			 = Yii::app()->request->getParam('booking_id');  //$_POST['booking_id'];
		$blgRemarkType		 = Yii::app()->request->getParam('blg_remark_type'); //$_POST['blg_remark_type'];
		$model				 = Booking::model()->findByPk($booking_id);
		$logModel			 = new BookingLog();
		$logModel->scenario	 = 'addmarkremark';
		if (isset($_POST['BookingLog']))
		{
			$logModel->attributes	 = Yii::app()->request->getParam('BookingLog');
			$arr					 = $logModel->attributes;
			$desc					 = trim($arr['blg_desc']);
			if ($logModel->validate())
			{
				$userInfo						 = UserInfo::getInstance();
				$bkgStatus						 = $model->bkg_status;
				$oldModel						 = clone $model;
				$params							 = [];
				$params['blg_booking_status']	 = $bkgStatus;
				switch ($blgRemarkType)
				{
					case '2':
						$eventId				 = BookingLog::REMARK_BAD;
						$params['blg_mark_car']	 = '1';
						if ($model->bkg_vehicle_id != null):
							Vehicles::model()->updateVehicleMarkCount($model->bkg_vehicle_id);
							$rating		 = Ratings::model()->findByAttributes(array('rtg_booking_id' => $booking_id));
							$ext		 = '91';
							$carId		 = $model->bkg_vehicle_id;
							$modelnew	 = Vehicles::model()->findByPk($carId);
							$agtId		 = $modelnew->vendorVehicles->vvhc_vnd_id;
							$modelvendor = Vendors::model()->findByPk($agtId);
							$msgCom		 = new smsWrapper();
							$msgCom->informCarBlocked($ext, $bookingidfull, $modelvendor->vnd_phone, $modelvendor->getName(), $modelnew->vhc_number, $rating->rtg_customer_overall);
						endif;
						break;
					case '3':
						$eventId					 = BookingLog::REMARK_BAD;
						$params['blg_mark_driver']	 = '1';
						if ($model->bkg_driver_id != null):
							Drivers::model()->updateDriverMarkCount($model->bkg_driver_id);
							$rating		 = Ratings::model()->findByAttributes(array('rtg_booking_id' => $booking_id));
							$ext		 = '91';
							$driverId	 = $model->bkg_driver_id;
							$modelnew	 = Drivers::model()->findByPk($driverId);
							$agtId		 = $modelnew->vendorDrivers->vdrv_vnd_id;
							$modelvendor = Vendors::model()->findByPk($agtId);
							$msgCom		 = new smsWrapper();
							$msgCom->informDriverBlocked($ext, $bookingidfull, $modelvendor->vnd_phone, $modelvendor->vnd_name, $modelnew->drv_name, $rating->rtg_customer_overall);
						endif;
						break;
					case '4':
						$eventId					 = BookingLog::REMARK_BAD;
						$params['blg_mark_vendor']	 = '1';
						if ($model->bkg_vendor_id != null):
							Vendors::model()->updateVendorMarkCount($model->bkg_vendor_id);
						endif;
						break;
					case '5':
						$eventId					 = BookingLog::REMARK_BAD;
						$params['blg_mark_customer'] = '1';
						if ($model->bkg_user_id != null):
							Users::model()->updateUserMarkCount($model->bkg_user_id);
						endif;
						break;
				}
				$params['blg_remark_type'] = $blgRemarkType;
				BookingLog::model()->createLog($booking_id, $desc, $userInfo, $eventId, $oldModel, $params);
				if (Yii::app()->request->isAjaxRequest)
				{
					$data = ['success' => true, 'oldStatus' => $bkgStatus, 'remarkType' => $blgRemarkType];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result											 = [];
					foreach ($logModel->getErrors() as $attribute => $errors)
						$result[CHtml::activeId($logModel, $attribute)]	 = $errors;
					$data											 = ['success' => false, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$this->renderPartial('addmarkremark', array('bookingId' => $booking_id, 'blgRemarkType' => $blgRemarkType, 'logModel' => $logModel), false, true);
	}

	public function actionEscalationremarks()
	{

		$booking_id			 = Yii::app()->request->getParam('booking_id');
		$escalation_status	 = Yii::app()->request->getParam('escalation_status');
		$model				 = Booking::model()->findByPk($booking_id);
		/* var $model BookingLog */
		$logModel			 = new BookingLog();
		$logModel->scenario	 = 'escalation';
		if (isset($_POST['BookingLog']))
		{

			$logModel->attributes	 = Yii::app()->request->getParam('BookingLog');
			$arr					 = $logModel->attributes;
			$desc					 = trim($arr['blg_desc']);
			$escalation				 = $escalation_status;
			$userInfo				 = UserInfo::getInstance();
			if ($escalation == '0')
			{
				$eventid								 = BookingLog::BOOKING_ESCALATION_SET;
				$model->bkgTrail->bkg_escalation_status	 = '1';
				$model->bkgTrail->update();
			}
			else if ($escalation == '1')
			{
				$eventid								 = BookingLog::BOOKING_ESCALATION_UNSET;
				$model->bkgTrail->bkg_escalation_status	 = '0';
				$model->bkgTrail->update();
			}
			$bkg_status						 = $model->bkg_status;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_booking_status']	 = $bkg_status;
			BookingLog::model()->createLog($booking_id, $desc, $userInfo, $eventid, $oldModel, $params);
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $bkg_status];
				echo json_encode($data);
				Yii::app()->end();
			}
		}

		$this->renderPartial('escalationremarks', array('bkid' => $booking_id, 'escalation_status' => $escalation_status, 'bookModel' => $model, 'logModel' => $logModel), false, true);
	}

	public function actionUpsellremarks()
	{

		$booking_id			 = Yii::app()->request->getParam('booking_id');
		$upsell_status		 = Yii::app()->request->getParam('upsell_status');
		$model				 = Booking::model()->findByPk($booking_id);
		$logModel			 = new BookingLog();
		$logModel->scenario	 = 'upsell';
		if (isset($_POST['BookingLog']))
		{
			$logModel->attributes	 = Yii::app()->request->getParam('BookingLog');
			$arr					 = $logModel->attributes;
			$desc					 = trim($arr['blg_desc']);
			$upsell					 = $upsell_status;
			$userInfo				 = UserInfo::getInstance();
			if ($upsell == '0')
			{
				$eventid							 = BookingLog::BOOKING_UPSELL_SET;
				$model->bkgTrail->bkg_upsell_status	 = '1';
				$model->bkgTrail->save();
			}
			else if ($upsell == '1')
			{
				$eventid							 = BookingLog::BOOKING_UPSELL_UNSET;
				$model->bkgTrail->bkg_upsell_status	 = '0';
				$model->bkgTrail->save();
			}
			$bkg_status						 = $model->bkg_status;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_booking_status']	 = $bkg_status;

			BookingLog::model()->createLog($booking_id, $desc, $userInfo, $eventid, $oldModel, $params);
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $bkg_status];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('upsellremarks', array('bkid' => $booking_id, 'upsell_status' => $upsell_status, 'bookModel' => $model, 'logModel' => $logModel), false, true);
	}

	public function actionSosOff()
	{

		$booking_id			 = Yii::app()->request->getParam('booking_id');
		$model				 = Booking::model()->findByPk($booking_id);
		$logModel			 = new BookingLog();
		$logModel->scenario	 = 'upsell';
		if (isset($_POST['BookingLog']))
		{
			$data					 = array();
			$logModel->attributes	 = Yii::app()->request->getParam('BookingLog');
			$arr					 = $logModel->attributes;
			$data['bkg_id']			 = $model->bkg_id;
			$data['comment']		 = trim($arr['blg_desc']);
			if ($model->bkgUserInfo->bkg_user_id > 0)
			{
				$UserModel	 = Users::model()->findByPk($model->bkgUserInfo->bkg_user_id);
				$userName	 = $UserModel->usr_name . " " . $UserModel->usr_lname;
			}
			$userInfo	 = UserInfo::getInstance();
			$result		 = BookingTrack::model()->resolveSOS($data, $userInfo, $userName);
			$success	 = $result['success'];
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $model->bkg_status];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $model->bkg_status));
		}
		$this->renderPartial('sosoffremarks', array('bkid' => $booking_id, 'upsell_status' => $upsell_status, 'bookModel' => $model, 'logModel' => $logModel), false, true);
	}

	public function actionProfitabilityRemarks()
	{

		$booking_id			 = Yii::app()->request->getParam('booking_id');
		$model				 = Booking::model()->findByPk($booking_id);
		$logModel			 = new BookingLog();
		$logModel->scenario	 = 'profitability';
		if (isset($_POST['BookingLog']))
		{
			$logModel->attributes				 = Yii::app()->request->getParam('BookingLog');
			$arr								 = $logModel->attributes;
			$desc								 = trim($arr['blg_desc']);
			$model->bkg_non_profit_override_flag = 1;
			$oldModel							 = clone $model;
			if ($model->save())
			{
				BookingLog::model()->createLog($booking_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_NON_PROFITABLE_OVERRRIDE_SET, $oldModel, false);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $model->bkg_status];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('profitability_remarks', array('bkid' => $booking_id, 'bookModel' => $model, 'logModel' => $logModel), false, true);
	}

	public function actionBlockMessage()
	{
		$bkgId		 = Yii::app()->request->getParam('bkg_id');
		$bkgBlockMsg = Yii::app()->request->getParam('bkg_blocked_msg');
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		if ($bkgBlockMsg == 1)
		{
			$model							 = Booking::model()->resetScope()->findByPk($bkgId);
			$model->bkgPref->bkg_blocked_msg = 0;

			if ($model->bkgPref->save())
			{
				$oldModel						 = $model;
				$eventId						 = BookingLog::MESSAGE_STATUS;
				$desc							 = "Message unblocked.";
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
		else if ($bkgBlockMsg == 0)
		{
			$model							 = Booking::model()->resetScope()->findByPk($bkgId);
			$model->bkgPref->bkg_blocked_msg = 1;
			if ($model->bkgPref->save())
			{
				$oldModel						 = $model;
				$eventId						 = BookingLog::MESSAGE_STATUS;
				$desc							 = "Message blocked.";
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

	public function actionCanbooking()
	{

		$bkid = Yii::app()->request->getParam('booking_id'); //$_POST['booking_id'];

		$reasonid	 = trim(Yii::app()->request->getParam('bkreason'));
		$reasonText	 = Yii::app()->request->getParam('bkreasontext');

		if (isset($_POST['bkreason']) && isset($_POST['bk_id']) && $reasonid != '')
		{

			$offEscalation	 = Yii::app()->request->getParam('offEscalation');
			$offAccounts	 = Yii::app()->request->getParam('offAccounts');
			$bk_id			 = Yii::app()->request->getParam('bk_id'); //$_POST['bk_id'];
			$userInfo		 = UserInfo::getInstance();
			$model			 = Booking::model()->findByPk($bk_id);
			$oldModel		 = clone $model;
			#print_r($oldModel);



			$remainingSeat = 4;
			if ($offEscalation)
			{
				$desc									 = "Level : 0-Green | NOTES: Booking Cancel ";
				$model->bkgTrail->bkg_escalation_status	 = 0;
				$model->bkgTrail->updateEscalation($desc, $userInfo, $escalationDesc							 = "");
			}

			if ($offAccounts)
			{
				$model->bkgPref->bkg_account_flag	 = 0;
				$model->bkgPref->scenario			 = 'accountflag';
				if ($model->bkgPref->save())
				{
					$eventId						 = BookingLog::UNSET_ACCOUNTING_FLAG;
					$desc							 = "Accounting Flag has been cleared.";
					$params['blg_booking_status']	 = $model->bkg_status;
					BookingLog::model()->createLog($bk_id, $desc, $userInfo, $eventId, $oldModel, $params);
				}
			}

			if ($model->bkg_flexxi_type == 2 && $model->bkg_fp_id == "")
			{
				$remainingSeat = Booking::model()->findNoOfSeatAvailableForBooking($bk_id);
			}
			$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
			if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && ($model->bkg_flexxi_type == 1 || $remainingSeat < 3))
			{
				$subids = Booking::model()->getSubsIdsbyPromoIds($bk_id, $model->bkg_bcb_id);
				foreach ($subids as $val)
				{
					$text	 = "Since you had bought a shared Flexxi seat, your booking was subject to Flexxi Promoters travel plans.";
					$bkgid	 = Booking::model()->canBooking($val, $text, 26, $userInfo);
					$desc	 = "Booking cancelled manually.(Reason: " . "Since you had bought a shared Flexxi seat, your booking was subject to Flexxi Promoters travel plans." . ")";
					$eventid = BookingLog::BOOKING_CANCELLED;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
					emailWrapper::bookingCancellationMail($bkgid);
				}
			}

			$bkgid = Booking::model()->canBooking($bk_id, $reasonText, $reasonid, $userInfo);
			############## nmodify vendor amount of existing booking reduct cancel one ####################

			$existingBcbId		 = $oldModel->bkg_bcb_id;
			$oldBcbModel		 = BookingCab::model()->findByPk($existingBcbId);
			$oldBookingIds		 = $oldBcbModel->bcb_bkg_id1;
			$oldBookingIdsArr	 = explode(",", $oldBookingIds);
			if (count($oldBookingIdsArr) > 1)
			{
				$arrKey = array_search($bk_id, $oldBookingIdsArr);
				unset($oldBookingIdsArr[$arrKey]);

				foreach ($oldBookingIdsArr as $bookingId)
				{
					$relatedBookingModel			 = Booking::model()->findByPk($bookingId);
					$oldBcbModel->bcb_vendor_amount	 = $relatedBookingModel->bkgInvoice->bkg_vendor_amount;
					$oldBcbModel->save();
				}
			}
			############## nmodify vendor amount of existing booking reduct cancel one ####################


			if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 2 && $model->bkg_fp_id != '')
			{
				$promoterBooking											 = Booking::model()->findByPk($model->bkg_fp_id);
				$fareDetails												 = $promoterBooking->bkgInvoice->calculatePromoterFare($model->bkg_id, true);
				$promoterBooking->bkgInvoice->bkg_base_amount				 = $fareDetails->baseAmount;
				$promoterBooking->bkgInvoice->bkg_state_tax					 = $fareDetails->stateTax;
				$promoterBooking->bkgInvoice->bkg_toll_tax					 = $fareDetails->tollTaxAmount;
				$promoterBooking->bkgInvoice->bkg_driver_allowance_amount	 = $fareDetails->driverAllowance;
				$promoterBooking->bkgInvoice->bkg_service_tax				 = $fareDetails->gst;
				$promoterBooking->bkgInvoice->populateAmount(true, false, true, false, $promoterBooking->bkg_agent_id);
				$promoterBooking->bkgInvoice->save();
			}

			if ($bkgid)
			{
				$bookingModel = Booking::model()->findByPk($bkgid);

				if ($bookingModel != '' && $bookingModel->bkgUserInfo->bkg_user_id != '')
				{
//					$notificationId	 = substr(round(microtime(true) * 1000), -5);
//					$payLoadData	 = ['bookingId' => $bookingModel->bkg_booking_id, 'EventCode' => Booking::CODE_USER_CANCEL];
//					$success		 = AppTokens::model()->notifyConsumer($bookingModel->bkgUserInfo->bkg_user_id, $payLoadData, $notificationId, "(" . $bookingModel->bkg_booking_id . ") " . $bookingModel->bkgFromCity->cty_name . " to " . $bookingModel->bkgToCity->cty_name . " on " . $bookingModel->bkg_pickup_date, $bookingModel->bkg_booking_id . " booking cancelled");

					$userInfo		 = UserInfo::getInstance();
					$type			 = Booking::model()->userArr[$userInfo->userType];
					$message		 = "Booking " . $bookingModel->bkg_booking_id . " Cancelled by $type";
					$image			 = NULL;
					$bkgID			 = $bookingModel->bkg_booking_id;
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$payLoadData	 = ['EventCode' => Booking::CODE_USER_CANCEL];
					$success		 = AppTokens::model()->notifyDriver($bookingModel->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "Booking Cancelled", $bkgID);
					//AppTokens::model()->notifyDriver($bookingModel->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, "(" . $bookingModel->bkg_booking_id . ") " . $bookingModel->bkgFromCity->cty_name . " to " . $bookingModel->bkgToCity->cty_name . " on " . $bookingModel->bkg_pickup_date . " booking cancelled", '', $bookingModel->bkg_booking_id . " booking cancelled");
				}
				if ($reasonid == 22 && $bkgid != '' && ($bookingModel->bkgBcb->bcb_vendor_id != '' || $bookingModel->bkgBcb->bcb_vendor_id != NULL))
				{
					$prows			 = PenaltyRules::getValueByPenaltyType(PenaltyRules::PTYPE_CAB_NO_SHOW);
					$p_Amount		 = $prows['plt_value'];
					$vndAmount		 = $bookingModel->bkgBcb->bcb_vendor_amount;
					$penaltyAmount	 = min($vndAmount, $p_Amount);
					if ($bookingModel->bkgPref->bkg_is_gozonow == 1)
					{
						$gnowPenaltyAmount	 = 500;
						$penaltyAmount		 = min($vndAmount, $gnowPenaltyAmount);
					}
					//$penaltyArr[] = 5;
					$penaltyArr	 = AccountTransactions::model()->mapVendorPenalty($reasonid);
					$remarks	 = 'Auto-Penalized of booking #' . $bookingModel->bkg_booking_id . ' for cancellation reason is car no show. Penalty Amount : Rs' . $penaltyAmount;
					$penaltyType = PenaltyRules::PTYPE_CAB_NO_SHOW;
					$result		 = AccountTransactions::checkAppliedPenaltyByType($bkgid, $penaltyType);
					if ($result)
					{
						AccountTransactions::model()->addVendorPenalty($bkgid, $bookingModel->bkgBcb->bcb_vendor_id, $penaltyAmount, $remarks, $penaltyArr, $penaltyType);
					}
				}
				$desc	 = "Booking cancelled manually.(Reason: " . $reasonText . ")";
				$eventid = BookingLog::BOOKING_CANCELLED;
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
				emailWrapper::bookingCancellationMail($bkgid);
				$tab	 = 2;
				$this->redirect(array('list', 'tab' => $tab));
			}
		}
		$isNMIcheckedZone	 = InventoryRequest::model()->checkNMIzonebyBkg($bkid);
		$bkgCode			 = Booking::model()->getCodeById($bkid);
		$model				 = Booking::model()->findByPk($bkid);
		$escalation			 = $model->bkgTrail->bkg_escalation_status;
		$accounts			 = $model->bkgPref->bkg_account_flag;
		if ($model->bkgTrail->bkg_escalation_status == 1 || $model->bkgPref->bkg_account_flag == 1)
		{
			$showAskPanel = 1;
		}

		$this->renderPartial('canbooking',
				array('bkid'				 => $bkid,
					'bkgCode'			 => $bkgCode,
					'showAskPanel'		 => $showAskPanel,
					'escalation'		 => $escalation,
					'accounts'			 => $accounts,
					'isNMIcheckedZone'	 => $isNMIcheckedZone
				), false, true);
	}

	public function actionGetdrivercabdetails()
	{
		$request								 = Yii::app()->request;
		$success								 = false;
		$params									 = $_GET;
		$drvId									 = $request->getParam('driverId');
		$vhcId									 = $request->getParam('vehicleId');
		$vndId									 = $request->getParam('vendorId');
		/* var $model Drivers */
		$drvName								 = '';
		$drvContact								 = '';
		$isDrvlicense							 = '';
		$isDrvLicExpDate						 = '';
		$driver_mark_bad						 = '0';
		$isLicenseDocId							 = '';
		$car_mark_bad							 = '0';
		$isVhcApproved							 = '1';
		$isDrvApproved							 = '1';
		$vehicleRegistrationCertificateExpDate	 = '';
		$vehicleInsuranceExpDate				 = '';
		$isRegistrationCertificate				 = '0';
		$isInsurance							 = '0';
		$isDrvFleet								 = '0';
		$isDrvFreeze							 = '0';
		$isCabFleet								 = '0';
		$isCng									 = '0';
		$isCabFreeze							 = '0';
		$data									 = array();
		if ($drvId != null)
		{
			$drvmodel		 = Drivers::model()->getById($drvId);
			$cttId			 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
			$contactModel	 = Contact::model()->findByPk($cttId);
			if (count($drvmodel->vendorDrivers) > 0)
			{
				foreach ($drvmodel->vendorDrivers as $vendorDriver)
				{
					$vendorDriver = $vendorDriver->attributes['vdrv_vnd_id'];
					if ($vendorDriver == $vndId)
					{
						$isDrvFleet = '1';
						break;
					}
				}
			}

			$driver_mark_bad = ($drvmodel->drv_mark_driver_count > 0) ? $drvmodel->drv_mark_driver_count : 0;
			$isDrvApproved	 = $drvmodel->drv_approved;
			$isDrvlicense	 = ($contactModel->ctt_license_no != NULL && $contactModel->ctt_license_no != '') ? $contactModel->ctt_license_no : '';
			$isDrvLicExpDate = ($contactModel->ctt_license_exp_date != NULL && $contactModel->ctt_license_exp_date != '' && $contactModel->ctt_license_exp_date != '1970-01-01') ? $contactModel->ctt_license_exp_date : '';
			$isLicenseDocId	 = (($contactModel->contactLicense == null) || ($contactModel->contactLicense->doc_type == 5 && $contactModel->contactLicense->doc_active == 1 && $contactModel->contactLicense->doc_status == 2 && $contactModel->ctt_license_doc_id != NULL && $contactModel->ctt_license_doc_id != '')) ? '' : $contactModel->ctt_license_doc_id;
			$drvContact		 = ContactPhone::model()->getContactPhoneById($contactModel->ctt_id);
			$drvName		 = $drvmodel->drv_name;
			$isDrvFreeze	 = $drvmodel->drv_is_freeze;
		}
		if ($vhcId != null)
		{
			$vhcModel = Vehicles::model()->findByPk($vhcId);
			if (count($vhcModel->vendorVehicles) > 0)
			{
				foreach ($vhcModel->vendorVehicles as $vehicles)
				{
					$vendorVehicles = $vehicles->attributes['vvhc_vnd_id'];
					if ($vendorVehicles == $vndId)
					{
						$isCabFleet = '1';
						break;
					}
				}
			}
			$isCabFreeze							 = $vhcModel->vhc_is_freeze;
			$isCng									 = $vhcModel->vhc_has_cng;
			$car_mark_bad							 = ($vhcModel->vhc_mark_car_count > 0) ? $vhcModel->vhc_mark_car_count : 0;
			$isVhcApproved							 = $vhcModel->vhc_approved;
			$vehicleInsuranceExpDate				 = ($vhcModel->vhc_insurance_exp_date != NULL && $vhcModel->vhc_insurance_exp_date != '' && $vhcModel->vhc_insurance_exp_date != '1970-01-01') ? $vhcModel->vhc_insurance_exp_date : '';
			$vehicleRegistrationCertificateExpDate	 = ($vhcModel->vhc_reg_exp_date != NULL && $vhcModel->vhc_reg_exp_date != '' && $vhcModel->vhc_reg_exp_date != '1970-01-01') ? $vhcModel->vhc_reg_exp_date : '';
			$vehicleDocsDetails						 = VehicleDocs::model()->findAllByVhcId($vhcId);
			foreach ($vehicleDocsDetails as $docs)
			{
				if ($docs['vhd_type'] == 5 && $docs['vhd_active'] = 1 && ($docs['vhd_status'] == 1 || $docs['vhd_status'] == 0 ))
				{
					$isRegistrationCertificate = 1;
				}
				if ($docs['vhd_type'] == 1 && $docs['vhd_active'] = 1 && ($docs['vhd_status'] == 1 || $docs['vhd_status'] == 0 ))
				{
					$isInsurance = 1;
				}
			}
		}
		if ($drvId != null || $vhcId != null)
		{
			$drvId								 = ($drvId > 0) ? ($drvId) : '0';
			$vhcId								 = ($vhcId > 0) ? $vhcId : '0';
			$params								 = $_GET + $_POST;
			$params['isGrid']					 = 1;
			$logData							 = Booking::model()->markedBadListByBooking($drvId, $vhcId, 0);
			$logData->getPagination()->params	 = $params;
			$logData->getSort()->params			 = $params;
			$logOutput							 = $this->renderPartial("markedbadlist", array('dataProvider'	 => $logData, 'model'			 => Booking::model(),
				'drvId'			 => $drvId,
				'vhcId'			 => $vhcId), true, true);
			if ($_REQUEST['isGrid'] == 1)
			{
				echo $logOutput;
				Yii::app()->end();
			}
		}

		if (count($drvmodel) > 0 || count($vhcModel) > 0)
		{
			$success = true;
		}

		$data = $params + ['drvContact'							 => $drvContact,
			'vehicleId'								 => $vhcId,
			'isCabFleet'							 => $isCabFleet,
			'isCabFreeze'							 => $isCabFreeze,
			'isCabCng'								 => $isCng,
			'isDrvFreeze'							 => $isDrvFreeze,
			'isDrvFleet'							 => $isDrvFleet,
			'drvName'								 => trim($drvName),
			'drvMarkBad'							 => $driver_mark_bad,
			'carMarkBad'							 => $car_mark_bad,
			'isVhcApproved'							 => $isVhcApproved,
			'isDrvApproved'							 => $isDrvApproved,
			'isDrvlicense'							 => $isDrvlicense,
			'isDrvLicExpDate'						 => $isDrvLicExpDate,
			'isLicenseDocId'						 => ($isLicenseDocId == null || $isLicenseDocId == "") ? '' : $isLicenseDocId,
			'isRegistrationCertificate'				 => $isRegistrationCertificate,
			'isInsurance'							 => $isInsurance,
			'vehicleInsuranceExpDate'				 => $vehicleInsuranceExpDate,
			'vehicleRegistrationCertificateExpDate'	 => $vehicleRegistrationCertificateExpDate,
			'logOutput'								 => $logOutput];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionGetdriverdetails()
	{
		$drvid			 = Yii::app()->request->getParam('drvid'); //$_GET['drvid'];
		/* var $model Drivers */
		$model			 = Drivers::model()->findByPk($drvid);
		$driver_mark_bad = Drivers::model()->checkDriverMarkCount($drvid);
		$number			 = ContactPhone::model()->getContactPhoneById($model->drv_contact_id);
		echo CJSON::encode(array('drvContact'		 => $number,
			'drvName'			 => $model->drv_name,
			'drvMarkBad'		 => $driver_mark_bad,
			'drvMarkBadStatus'	 => $model->drv_mark_driver_status));
	}

	public function actionGetcabdetails()
	{
		$vhcid			 = Yii::app()->request->getParam('vhcid'); //$_GET['drvid'];
		$car_mark_bad	 = Vehicles::model()->checkVehicleMarkCount($vhcid);
		echo CJSON::encode(array('carMarkBad' => $car_mark_bad));
	}

	public function actionCanvendor()
	{
		$bkid			 = Yii::app()->request->getParam('booking_id'); // $_POST['booking_id'];
		$from_uberlist	 = Yii::app()->request->getParam('from_uberlist');

		Logger::create('Canvendor enter  .bkg: ' . $bkid, CLogger::LEVEL_TRACE);
		if (!isset($_POST['bkreason']) || !isset($_POST['bk_id']))
		{
			Logger::create(' open view. bkg: ' . $bkid, CLogger::LEVEL_TRACE);
			goto view;
		}
		$user_id	 = Yii::app()->user->getId();
		$bk_id		 = Yii::app()->request->getParam('bk_id'); //$_POST['bk_id'];
		$reason		 = Yii::app()->request->getParam('bkreason'); //$_POST['bkreason'];
		$reasontext	 = Yii::app()->request->getParam('bkreasontext'); //$_POST['bkreason'];
		Logger::create(' data received. bkg= ' . $bkid . '::reason=' . $reason . ':: reasontext=' . $reasontext, CLogger::LEVEL_TRACE);
		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			$model							 = Booking::model()->findByPk($bk_id);
			$bcb_id							 = $model->bkg_bcb_id;
			$modelcab						 = BookingCab::model()->findByPk($bcb_id);
			$modelcab->bcb_denied_reason_id	 = $reason;
			$vendorId						 = ($modelcab->bcb_vendor_id > 0) ? $modelcab->bcb_vendor_id : 0;

			Logger::trace("starting cancel vendor: {$bcbId} {$reasontext} {$bk_id} {$reason}");
			$result = Booking::model()->canVendor($bcb_id, $reasontext, UserInfo::getInstance(), [$bk_id], $reason);

			if ($result['success'] == false)
			{
				throw new Exception(json_encode($result["errors"]));
			}
			if (!$modelcab->save())
			{
				throw new Exception(json_encode($modelcab->getErrors()));
			}
//			if (!$status)
//			{
//				throw new Exception("Failed to update Vendor Stats");
//			}
			$modelcab->save();
			$transaction->commit();
			$step		 = BookingCab::model()->getVendorUnassignStep($bcb_id);
			$userInfo	 = UserInfo::getInstance();
			$userType	 = $userInfo->userType;
			$updateDate	 = VendorStats::updateLastUnassignDate($vendorId, $step, $userType);

			$updateUnassignMode = BookingCab::modifyUnassignMode($step, $bcb_id);

			if ($from_uberlist == 1)
			{
				$this->redirect(array('uberlist'));
			}
			$tab = 3;
//			if ($model->bkgBcb->bcb_driver_id != "")
//			{
//				$userInfo		 = UserInfo::getInstance();
//				$type			 = Booking::model()->userArr[$userInfo->userType];
//				$message		 = "Booking " . $model->bkg_booking_id . " Updated by $type";
//				$image			 = NULL;
//				$bkgID			 = $model->bkg_booking_id;
//				$notificationId	 = substr(round(microtime(true) * 1000), -5);
//				$payLoadData	 = ['EventCode' => Booking::CODE_VENDOR_CANCEL_NOTIFICATION];
//				$success		 = AppTokens::model()->notifyDriver($model->bkgBcb->bcb_driver_id, $payLoadData, $notificationId, $message, $image, "Vendor Cancelled", $bkgID);
//			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		catch (Exception $e)
		{
			$transaction->rollback();
			ReturnSet::setException($e);
			$desc = 'Failed to unassign Vendor: ' . $e->getMessage() . '';
			BookingLog::model()->createLog($bk_id, $desc, UserInfo::getInstance(), BookingLog::VENDOR_UNASSIGNED, false);
		}

		view:
		$this->renderPartial('canvendor', array('bkid' => $bkid));
	}

	public function actionCompletebooking()
	{
		$tab	 = 0;
		$bkid	 = Yii::app()->request->getParam('bkid'); //$_REQUEST['bkid'];
		$success = false;
		if (Booking::model()->markComplete($bkid))
		{
			$success	 = true;
			$oldStatus	 = Booking::STATUS_PROCESSED;
		}
		else
		{
			$tab = '5';
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'oldStatus' => $oldStatus];
			echo json_encode($data);
			Yii::app()->end();
		}
		$this->redirect(array('list', ['tab' => $tab]));
	}

	public function actionSetcompletebooking()
	{
		$Ids	 = Yii::app()->request->getParam('bkIds');
		$success = false;
		if (isset($Ids) && $Ids != '')
		{
			$BookingIds	 = explode(',', $Ids);
			$resultSet	 = BookingSub::model()->getVendorDueBookings($Ids);
			if (count($resultSet) > 0)
			{
				foreach ($resultSet as $key => $value)
				{
					if ($value['bkg_id'] != '')
					{
						//$resultSet['gozo'];
						//$resultSet['vnd'];
						$bkid			 = $value['bkg_id'];
						$model			 = Booking::model()->findByPk($bkid);
						$currentDate	 = date("Y-m-d H:i:s");
						$pickupDate		 = $model->bkg_pickup_date;
						$tripTime		 = $model->bkg_trip_duration;
						$pickupDuration	 = strtotime('+' . $tripTime . ' minutes', strtotime($pickupDate));
						$pickupDuration	 = date('Y-m-d H:i:s', $pickupDuration);
						if ($pickupDuration <= $currentDate && $model->bkgTrack->bkg_is_no_show != 1 && $model->bkgPref->bkg_account_flag != 1 && $model->bkgPref->bkg_duty_slip_required != 1)
						{
							if (Booking::model()->markComplete($bkid))
							{
								$success = true;
							}
						}
					}
				}
				$success	 = true;
				$oldStatus	 = Booking::STATUS_PROCESSED;
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'oldStatus' => $oldStatus];
			echo json_encode($data);
			//Yii::app()->end();
		}
	}

	public function actionGenerateInvoiceForBooking()
	{
		$this->actionReceipt();
	}

	public function actionSettlebooking()
	{
		$bkid		 = Yii::app()->request->getParam('bkid'); //$_POST['bkid'];
		$model		 = Booking::model()->findByPk($bkid);
		$oldModel	 = clone $model;
		if (Booking::model()->markSettle($bkid))
		{
			$bkgid		 = $bkid;
			$desc		 = "Booking marked as settled.";
			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::BOOKING_MARKED_SETTLED;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
			$tab		 = Booking:: STATUS_SETTELED;
			$bkg_status	 = $model->bkg_status;
		}
		else
		{
			$tab = 6;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
//echo json_encode(['success' => $success]);
			$data = ['success' => true, 'oldStatus' => $bkg_status];
			echo json_encode($data);
			Yii::app()->end();
		}
		$this->redirect(array('list', 'tab' => $tab));
	}

	public function actionView()
	{
		$bookingID	 = Yii::app()->request->getParam('id');
		$fullId		 = Yii::app()->request->getParam('booking_id');
		$partnerID	 = Yii::app()->request->getParam('partner_ref');
		if ($bookingID == '' && $fullId == '' && $partnerID == '')
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($bookingID == '')
		{
			$refID		 = ($fullId != '') ? $fullId : $partnerID;
			$bookingID	 = BookingSub::getIdByRef($refID);
		}

		$view		 = Yii::app()->request->getParam('view', 'view');
		$data_array	 = RatingAttributes::model()->getRatingAttributes(2);

		if ($bookingID != '')
		{
			$bookModel				 = Booking::model()->findByPk($bookingID);
			$bookData				 = BookingSub::getVendorCabDriverDetails($bookingID);
			$oldModel				 = clone $bookModel;
			$userInfo				 = UserInfo::getInstance();
			$desc					 = 'Booking Viewed';
			$params['blg_active']	 = 2;
			$eventId				 = BookingLog::BOOKING_VIEWED;
			BookingLog::model()->createLog($bookingID, $desc, $userInfo, $eventId, $oldModel, $params);
			$bkgTrail				 = BookingTrail::model()->find('btr_bkg_id=:id', ['id' => $bookingID]);
		}

		$models				 = Booking::model()->getBookingRelationalDetails($bookingID);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ($outputJs ? "Partial" : "");
		$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bookingID]);
		$bkgPref			 = BookingPref::model()->getByBooking($bookingID);
		$bkgTrack			 = BookingTrack::model()->find('btk_bkg_id=:bkg_id', ['bkg_id' => $bookingID]);

		//$bkgDriverAppinfo		 = TripTracking::model()->getDirverAppInfo($bookingID);
		$bkgDriverAppinfo		 = BookingTrackLog::model()->getDirverAppInfo($bookingID);
		$isConfirmCash			 = BookingUser::isConfirmCashBooking($bookingID);
		$zones					 = ZoneCities::model()->findZoneByCity($bookModel->bkg_from_city_id);
		$issourcezone_needSupply = InventoryRequest::model()->checkInventoryByFromCity($bookModel->bkg_from_city_id);
		// Show Note section 
		$cntRut					 = count($bookingRouteModel);
		//print_r($bookingRouteModel);exit;
		foreach ($bookingRouteModel as $key => $bookingRoute)
		{
			$pickupCity[]	 = $bookingRoute->brt_from_city_id;
			$dropCity[]		 = $bookingRoute->brt_to_city_id;
			$pickup_date[]	 = $bookingRoute->brt_pickup_datetime;
			$temp_last_date	 = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
		}
		$pickup_date_time	 = $pickup_date[0];
		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
		$dateArr			 = array($pickup_date_time, $drop_date_time);
		#print_r($dateArr);exit;
		#print_r($locationArr);exit;
		$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr);
		// endof Show Note section 
		$isRestricted		 = BookingInvoice::validateDateRestriction($models->bkg_pickup_date);
		#$showAllVendorRank  = BookingVendorRequest::allVendorRank($bookingID);
		$days				 = $models->bkg_pickup_date != null ? (Filter::getTimeDiff(date("Y-m-d H:i:s"), $models->bkg_pickup_date) / 1440) : 0;
//		$days				 = $bookModel->bkg_pickup_date != null ? (Filter::getTimeDiff(date("Y-m-d H:i:s"), $bookModel->bkg_pickup_date) / 1440) : 0;
		$csrFeedback		 = CsrFeedback::getCsrFeedbackRating($bookingID);

		$this->$method($view, array('model'				 => $models,
			'isConfirmCash'		 => $isConfirmCash,
			'userInfo'			 => $userInfo,
			'bookingRouteModel'	 => $bookingRouteModel,
			'bkgTrack'			 => $bkgTrack,
			'bkgDriverAppinfo'	 => $bkgDriverAppinfo,
			'isAjax'			 => $outputJs,
			'data_array'		 => $data_array,
			'bookData'			 => $bookData,
			'isSupply'			 => $issourcezone_needSupply,
			'note'				 => $noteArr,
			'isRestricted'		 => $isRestricted,
			'bkgTrail'			 => $bkgTrail,
			'bkgPref'			 => $bkgPref,
			'csrFeedback'		 => $csrFeedback,
			'days'				 => $days,
			'bookModel'			 => $bookModel
				), false, $outputJs);
	}

	public function actionShowTripStatus()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');
		if ($bkgid != '')
		{
			$bkgDriverAppinfo = BookingTrackLog::model()->getDirverAppInfo($bkgid);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showtripstatus', array('bkgDriverAppinfo' => $bkgDriverAppinfo), false, $outputJs);
	}

	public function actionShowAllBidRank()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');
		if ($bkgid != '')
		{
			$bkgallVendorRank = BookingVendorRequest::model()->allVendorRank($bkgid);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showVendorRank', array('bkgVendorRank' => $bkgallVendorRank), false, $outputJs);
	}

	public function actionRemindvendor()
	{
		$bkid		 = Yii::app()->request->getParam('bkid');
		$model		 = Booking::model()->findByPk($bkid);
		$cabModel	 = $model->getBookingCabModel();
		$oldModel	 = clone $model;
		$cttId		 = $cabModel->bcbVendor->vnd_contact_id;
		$number		 = ContactPhone::model()->getContactPhoneById($cttId);
		$ext		 = '91';

		$cityA		 = $model->bkgFromCity->cty_name;
		$cityB		 = $model->bkgToCity->cty_name;
		$bookingID	 = $model->bkg_booking_id;

		// Pickup Date check for UBER agent
		$vendor_pickup_date = $cabModel->getPickupDateTime("Y-m-d H:i:s", $model->bkg_pickup_date);

		$date	 = date('d-m-Y', strtotime($vendor_pickup_date));
		$time	 = date('H:i A', strtotime($vendor_pickup_date));
		$svcId	 = $model->bkgSvcClassVhcCat->scv_id;
		$cabType = SvcClassVhcCat::model()->getVehicleCategoryNameById($svcId);
		//$cabType = $model->bkgVehicleType->getVehicleModel();
		if ($cabModel->bcb_vendor_id != '')
		{
			$bcount		 = count($cabModel->bookings);
			$first_city	 = Cities::getName($cabModel->bookings[0]->bkg_from_city_id);
			//$pickup_date = $cabModel->bookings[0]->bkg_pickup_date;

			$pickup_date = $cabModel->getPickupDateTime("d M Y H:i A", $model->bkg_pickup_date);

			$last_city	 = Cities::getName($cabModel->bookings[$bcount - 1]->bkg_to_city_id);
			$payLoadData = ['tripId' => $cabModel->bcb_id, 'EventCode' => Booking::CODE_PENDING];
			$message	 = "Trip Id: " . $cabModel->bcb_id . ", " . $first_city . "-" . $last_city . ", " . date("d-m-Y h:i A", strtotime($pickup_date));
			$title		 = "Trip Id " . $cabModel->bcb_id . ": Allocate Car and Driver now ";
			$success	 = AppTokens::model()->notifyVendor($cabModel->bcb_vendor_id, $payLoadData, $message, $title);
		}
//		$msgCom		 = new smsWrapper();
//		$msgCom->remindVendorUpdateDetails($ext, $number, $cabType, $cityA, $cityB, $date, $time, $bookingID);
		$bkgid		 = $bookingID;
		$desc		 = "Vendor reminder sent for Cab & Driver (by admin)";
		$userInfo	 = UserInfo::getInstance();

		$eventid = BookingLog::VENDOR_REMINDED_FOR_DRIVER_INFORMATION;
		BookingLog::model()->createLog($bkid, $desc, $userInfo, $eventid, $oldModel);

		$tab = 3;
		$this->redirect(array('list', 'tab' => $tab));
	}

	public function actionUndocandel()
	{
		$booking_id	 = Yii::app()->request->getParam('booking_id'); //$_POST['booking_id'];
// $reason = trim($_POST['bkreason']);
		$tab		 = '';

		$model		 = Booking::model()->findByPk($booking_id);
		$cabmodel	 = $model->getBookingCabModel();
		$oldModel	 = clone $model;
		$oldstat	 = $model->bkg_status;
		$stat		 = ($model->bkg_status == 8) ? 'deletion' : 'cancellation';
		if ($cabmodel->bcb_vendor_id > 0 && $cabmodel->bcb_driver_id > 0)
		{
			$model->bkg_status	 = Booking::STATUS_PROCESSED;
			$tab				 = Booking::STATUS_PROCESSED;
		}
		else if ($cabmodel->bcb_vendor_id > 0 && $cabmodel->bcb_driver_id == NULL)
		{
			$model->bkg_status	 = Booking::STATUS_ASSIGN;
			$tab				 = Booking::STATUS_ASSIGN;
		}
		else
		{
			$model->bkg_status	 = Booking::STATUS_VERIFY;
			$tab				 = Booking::STATUS_VERIFY;
		}
		$model->scenario = 'updatestatus';
		if ($model->validate())
		{
			$success = $model->save();

			if ($success)
			{
				$bkgid		 = $model->bkg_id;
				$desc		 = "Booking $stat reverted.";
				$userInfo	 = UserInfo::getInstance();
				$eventid	 = BookingLog::BOOKING_LAST_ACTION_REVERTED;
				AccountTransactions::model()->removeBookingCommission($bkgid);
				BookingInvoice::model()->unsetRefundApprovalFlag($bkgid);
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
				AccountTransactions::removeRefund($bkgid);
				AccountTransactions::removeCancelationCharge($bkgid);
				$desc		 = "Cancelation charge reverted";
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
				AccountTransactions::removeCompensationDBO($bkgid);
			}
		}

		echo CJSON::encode(['oldtab' => $oldstat, 'newtab' => $tab]);
		//echo $tab;
	}

	public function actionUndoaction()
	{
		$booking_id = Yii::app()->request->getParam('booking_id'); //$_POST['booking_id'];

		$userInfo		 = UserInfo::getInstance();
		$model			 = Booking::model()->findByPk($booking_id);
		$isRestricted	 = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if (!$isRestricted)
		{
			$data = ["success" => false, "msg" => "Sorry, you cannot perform this action now."];
			echo json_encode($data);
			Yii::app()->end();
		}
		$oldStatus = Booking::model()->undoActions($booking_id, $userInfo);

		if (Yii::app()->request->isAjaxRequest)
		{
			echo $oldStatus;
			Yii::app()->end();
		}
	}

	public function actionExport()
	{
		$booking_status	 = Yii::app()->request->getParam('booking_status');
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="booking-' . $booking_status . '-' . date('YmdHi') . '.csv"');
		$filename		 = "booking-" . $booking_status . '-' . date('YmdHi') . '.csv';
//$foldername = '/Exported';
		$foldername		 = 'E:\Share';
		$backup_file	 = $foldername . DIRECTORY_SEPARATOR . $filename;

		if (!is_dir($foldername))
		{
			mkdir($foldername);
		}
		if (file_exists($backup_file))
		{
			unlink($backup_file);
		}

		$sql = "SELECT 'Booking ID','Name','Phone','Journey On','From','To','Vendor','Amount','Vehicle','Driver','Booked on'
                UNION ALL(SELECT trim(bkg_booking_id),trim(IFNULL(bkg_user_name,'')),trim(IFNULL(bkg_contact_no,'')),trim(bkg_pickup_date),trim(c1.cty_name),trim(c2.cty_name),trim(IFNULL(vnd_name,'')),trim(bkg_total_amount),trim(IFNULL(bkg_vehicle_id,'')),trim(IFNULL(d1.drv_name,'')),trim(bkg_create_date)
INTO OUTFILE '" . mysql_escape_string($backup_file) . "'
FIELDS TERMINATED BY ','
ENCLOSED BY '\"'
ESCAPED BY '\\\\'
LINES TERMINATED BY '\\\n'
FROM booking LEFT JOIN cities c1 ON c1.cty_id=bkg_from_city_id LEFT JOIN cities c2 ON c2.cty_id=bkg_to_city_id LEFT JOIN vendors a1 ON a1.vnd_id=bkg_vendor_id LEFT JOIN drivers d1 ON d1.drv_id=bkg_driver_id LEFT  WHERE bkg_status=" . $booking_status . ")";

		$command = Yii::app()->db->createCommand($sql);
		$retval	 = $command->execute();

		if (!$retval)
		{
			die('Could not take data backup: ' . mysql_error());
		}
		else
		{
			if (file_exists($backup_file))
			{
				echo file_get_contents($backup_file);
			}
			unlink($backup_file);
		}

		exit;
	}

	public function actionBookingLog()
	{
		$booking_id					 = Yii::app()->request->getParam('bkg_id');
		$bookingLog					 = new BookingLog();
		$bookingLog->blg_booking_id	 = $booking_id;
		return $dataProvider				 = $bookingLog->search();
	}

	public function actionGetamountbyvehicle()
	{
		$success = false;
		$params	 = $_GET;

		/* @var $value BookingRoute */
		try
		{
			$fcity				 = Yii::app()->request->getParam('fromCity');
			$tcity				 = Yii::app()->request->getParam('toCity');
			$vehicleModelId		 = Yii::app()->request->getParam('modelId', 0);
			$vctId				 = Yii::app()->request->getParam('cabType', 0);
			$distance			 = Yii::app()->request->getParam('tripDistance');
			$duration			 = Yii::app()->request->getParam('tripDuration');
			$isPackageType		 = Yii::app()->request->getParam('isPackageType', 0);
			$multijsondata		 = Yii::app()->request->getParam('multiCityData');
			$booking_type		 = Yii::app()->request->getParam('bookingType', 1);
			$bkg_id				 = Yii::app()->request->getParam('id');
			$packageid			 = Yii::app()->request->getParam('pckageID');
			$pickLocation		 = Yii::app()->request->getParam('pickupAddress');
			$dropLocation		 = Yii::app()->request->getParam('dropupAddress');
			$pickup_date		 = Yii::app()->request->getParam('pickupDate');
			$pickup_time		 = Yii::app()->request->getParam('pickupTime');
			$routeDataArr		 = Yii::app()->request->getParam('routeDataArr');
			$sccId				 = Yii::app()->request->getParam('sccId', 0);
			$isAirportPickup	 = Yii::app()->request->getParam('isAirportPickup', 0);
			/* package */
			$pckageID			 = Yii::app()->request->getParam('pckageID');
			$suggestPrice		 = Yii::app()->request->getParam('isCalculate');
			$minTripDistance	 = Yii::app()->request->getParam('minTripDistance', 0);
			$isGozoNow			 = Yii::app()->request->getParam('isGozonow', 0);
			$tripUser			 = Yii::app()->request->getParam('tripUser');
			$jsonData_payment	 = Yii::app()->request->getParam('jsonData_payment');
			$default			 = true;
			if ($pickup_date == "" && $pickup_time == "")
			{
				$pickup_date = date('d/m/Y', strtotime('+4 hour'));
				$pickup_time = date('h:i A', strtotime('+4 hour'));
			}

			if ($booking_type == 5)
			{
				if ($pckageID == '')
				{
					$arrjsondata = json_decode($multijsondata);
					$pcdId		 = $arrjsondata[0]->packagedelID;
					$pckageID	 = PackageDetails::model()->findByPk($pcdId)->pcd_pck_id;
				}
				if ($multijsondata == '' && $pickup_date != '')
				{
					$date			 = DateTimeFormat::DatePickerToDate($pickup_date);
					$time			 = DateTime::createFromFormat('h:i A', $pickup_time)->format('H:i:00');
					$pdatetime		 = $date . ' ' . $time;
					$packagemodel	 = Package::model()->findByPk($pckageID);
					$routeModel		 = $packagemodel->packageDetails;

					$multijsondata = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pdatetime);
				}
			}
			if (trim($multijsondata) != '""' && trim($multijsondata) != "" && !in_array($booking_type, [1, 4, 9, 10, 11]) && trim($multijsondata) != "null")
			{
				$arrjsondata = json_decode($multijsondata);
			}
			else
			{
				$Arrmulticity	 = [];
				$routeModel		 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bkg_id]);
				if ($bkg_id != '' && !in_array($booking_type, [1, 4, 9, 10, 11]))
				{
					foreach ($routeModel as $key => $value)
					{
						$Arrmulticity[$key] = ["pickup_city"	 => $value->brt_from_city_id,
							"drop_city"		 => $value->brt_to_city_id,
							"pickup_address" => $value->brt_from_location,
							"drop_address"	 => $value->brt_to_location,
							"date"			 => $value->brt_pickup_datetime];
					}
				}
				else
				{
					$date			 = DateTimeFormat::DatePickerToDate($pickup_date);
					$time			 = DateTime::createFromFormat('h:i A', $pickup_time)->format('H:i:00');
					$datetime		 = $date . ' ' . $time;
					$Arrmulticity[0] = [
						"pickup_city"	 => $fcity,
						"drop_city"		 => $tcity,
						"pickup_address" => $pickLocation,
						"drop_address"	 => $dropLocation,
						"date"			 => $datetime,
						"distance"		 => $distance,
						"duration"		 => $duration
					];
				}
				$multijsondata	 = json_encode($Arrmulticity);
				$arrjsondata	 = json_decode($multijsondata, false);
			}

			$routesArr	 = [];
			$currentDate = date('Y-m-d H:i:s', strtotime('+3 DAYS'));
			$noNight	 = 0;
			foreach ($arrjsondata as $key => $value)
			{
				$routeModel						 = new BookingRoute();
				$routeModel->brt_from_city_id	 = $value->pickup_city;
				$routeModel->brt_to_city_id		 = $value->drop_city;
				$routeModel->brt_from_location	 = $value->pickup_address;
				$routeModel->brt_to_location	 = $value->drop_address;
				$routeModel->brt_pickup_datetime = $value->date;
				if (is_array($routeDataArr))
				{
					$routeModel->brt_from_latitude	 = $routeDataArr[$key]['locLatVal'];
					$routeModel->brt_from_longitude	 = $routeDataArr[$key]['locLonVal'];
					$routeModel->brt_from_location	 = $routeDataArr[$key]['brtLocationVal'];
					$routeModel->brt_to_latitude	 = $routeDataArr[$key + 1]['locLatVal'];
					$routeModel->brt_to_longitude	 = $routeDataArr[$key + 1]['locLonVal'];
					$routeModel->brt_to_location	 = $routeDataArr[$key + 1]['brtLocationVal'];

					if ($arrjsondata[$key]->pickup_address == '')
					{
						$arrjsondata[$key]->pickup_address	 = $routeDataArr[$key]['brtLocationVal'];
						$arrjsondata[$key]->drop_address	 = $routeDataArr[$key + 1]['brtLocationVal'];
					}
				}
				if (count($arrjsondata) > 0)
				{
					$success = true;
				}
				$routesArr[] = $routeModel;
			}
			if ($booking_type != 8)
			{
				$partnerId		 = Yii::app()->request->getParam('agentId');
				$bookingCPId	 = ($partnerId > 0) ? $partnerId : Yii::app()->params['gozoChannelPartnerId'];
				$quote			 = new Quote();
				$quote->routes	 = $routesArr;
				$quote->tripType = ($booking_type == 5 && $pckageID == '') ? 3 : $booking_type; // package
				if ($isPackageType == 1)
				{
					$quote->tripType = 3;
				}
				$quote->partnerId		 = $bookingCPId;
				$quote->vehicleModelId	 = ((int) $vehicleModelId > 0 && in_array($sccId, [4, 5])) ? (int) $vehicleModelId : 0;
				$quote->quoteDate		 = date("Y-m-d H:i:s");
				$quote->pickupDate		 = $routesArr[0]->brt_pickup_datetime;
				$quote->sourceQuotation	 = Quote::Platform_Admin;

				/* package */
				$quote->packageID		 = $pckageID;
				$quote->suggestedPrice	 = $suggestPrice;
				if ($minTripDistance == 1 && !in_array($booking_type, [9, 10, 11]))
				{
					$quote->minRequiredKms = $distance;
				}
				if (!$routesArr[0]->checkQuoteSession())
				{
					Quote::$updateCounter = true;
					$routesArr[0]->setQuoteSession();
				}

				if ($partnerId)
				{
					$quote->isB2Cbooking	 = false;
					$quote->sourceQuotation	 = Quote::Platform_Agent;
				}

				$errors = BookingRoute::validateRoutes($quote->routes, $booking_type, null, 0);
				if (!empty($errors))
				{
					throw new Exception(json_encode($errors[0]), ReturnSet::ERROR_VALIDATION);
				}
				$quote->setCabTypeArr(Quote::Platform_Admin);
				$quote->showErrorQuotes	 = true;
				$quote->platform		 = Quote::Platform_Admin;

				if ($isGozoNow == '1')
				{
					$quote->gozoNow		 = true;
					$svcIds				 = SvcClassVhcCat::getCabListGNowQuote($vehicleModelId);
					$quote->catypeArr	 = $svcIds;
				}

				if ($sccId > 0)
				{
					$svcId	 = SvcClassVhcCat::getSvcClassIdByVehicleCat($vctId, $sccId, $vehicleModelId);
					$userId	 = UserInfo::getUserId();
					if ($userId == 2)
					{
						Logger::setCategory("info.models.Quote.adminbooking");
					}
					$qt = $quote->getQuote($svcId);
					if ($userId == 2)
					{
						Logger::unsetCategory("info.models.Quote.adminbooking");
					}
					$quoteData	 = $qt[$svcId];
					$default	 = false;
				}
				else
				{
					$svcIds	 = SvcClassVhcCat::getScvListByCategory($vctId);
					$qt		 = $quote->getQuote($svcIds);
					$svcId	 = array_key_first($qt);

					$quoteData = $qt[$svcId];
				}
				if ($quoteData->gozoNow && !$quote->gozoNow)
				{
					$quote->gozoNow	 = true;
					$isGozoNow		 = 1;
				}
				if ($quote->gozoNow)
				{
					$quoteData->routeRates->discount = 0;
				}

				if (empty($qt))
				{
					throw new Exception("No cabs available for this route", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
				$routeRates = [0 => $quoteData->routeRates];
				if ($svcId > 0)
				{
					$pdate			 = DateTimeFormat::DatePickerToDate($pickup_date);
					$ptime			 = DateTime::createFromFormat('h:i A', $pickup_time)->format('H:i:00');
					$pDateTime		 = $pdate . ' ' . $ptime;
					$isGozonow		 = ($quote->gozoNow) ? 1 : 0;
					$defCanRuleId	 = CancellationPolicy::getCancelRuleId(null, $svcId, $fcity, $tcity, $booking_type, $isGozonow);

					$data		 = AddonCancellationPolicy::getByCtyVehicleType($fcity, $tcity, $svcId, $booking_type, $defCanRuleId);
					$addons		 = new \Stub\common\Addons();
					$addonsObj	 = $addons->getList($data, $defCanRuleId, $quoteData->routeRates->baseAmount, 1, $pDateTime);
					$arrCPAddons = $addonsObj;
					$routeRates	 = [];
					foreach ($addonsObj as $cpaddon)
					{
						$rrate				 = clone $quoteData->routeRates;
						$rrate->addonCharge	 = $cpaddon->charge;
						if ($tripUser == 1 && $quote->platform == 2)
						{
							$rrate->baseAmount = $rrate->baseAmount + Config::get('admin.assisted.markup');
						}
						$rrate->calculateTotal();
						$routeRates[$cpaddon->id] = $rrate;
					}
					/*					 * ******************Cab Model Addons********************** */
					$addonCMdata = AddonCabModels::getByCtyVehicleType($fcity, $tcity, $svcId, $booking_type);
					$addonsCmObj = $addons->getList($addonCMdata, $defCanRuleId, $quoteData->routeRates->baseAmount, 2);
					$arrCMAddons = $addonsCmObj;

					if ((empty($addonsObj) || $addonsObj == null) && ($tripUser == 1 && $quote->platform == 2))
					{
						$routeRates[0]->baseAmount = $routeRates[0]->baseAmount + Config::get('admin.assisted.markup');
						$routeRates[0]->calculateTotal();
					}
				}
				//$quoteData->getAddonRates();
				$routesArr		 = $quoteData->routes;
				$routeDistance	 = $quoteData->routeDistance;
				$routeDuration	 = $quoteData->routeDuration;
				$sccId			 = ($sccId == 0) ? 1 : $sccId;

				$distArr = [];
				if ($booking_type != 5)
				{
					foreach ($routesArr as $k => $v)
					{
						$distArr[$k]['dist']			 = $v->brt_trip_distance;
						$distArr[$k]['dura']			 = $v->brt_trip_duration;
						$distArr[$k]['fromCity']		 = $v->brt_from_city_id;
						$distArr[$k]['toCity']			 = $v->brt_to_city_id;
						$arrjsondata[$k]->distance		 = $v->brt_trip_distance;
						$arrjsondata[$k]->duration		 = $v->brt_trip_duration;
						$arrjsondata[$k]->pickup_city	 = $v->brt_from_city_id;
						$arrjsondata[$k]->drop_city		 = $v->brt_to_city_id;
					}
				}
				$baseamount			 = $routeRates[0]->baseAmount;
				$amount				 = $routeRates[0]->totalAmount;
				$tax				 = $routeRates[0]->gst;
				//$staxrate			 = Filter::getServiceTaxRate();
				$staxrate			 = BookingInvoice::getGstTaxRate($quoteData->partnerId, $quoteData->tripType);
				$arr['routeData']	 = ['totalGarage' => $routeDistance->totalGarage, 'quoted_km' => $routeDistance->tripDistance];
				if (count($arr) > 0)
				{
					$success = true;
				}
				$processedRoute		 = BookingLog::model()->logRouteProcessed($quoteData, '', $isAirportPickup);
				$vehicleInfo		 = SvcClassVhcCat::model()->getVctSvcList($returnType			 = 'selectize', '', $vctId);
				$cabName			 = $vehicleInfo['vct_label'];
				$vhtModel			 = $vehicleInfo['vct_desc'];
				$assuredStringShow	 = (in_array($vctId, [5, 6]) ) ? '' : 'For a specific car model, book a Assured category';
				$destString			 = implode(' == ', $routeDistance->routeDesc);
				$quoteStatement		 = " 
				*** $destString ***
				***Fare summary for $cabName category ($vhtModel will be assigned. $assuredStringShow)***
				Base price: ₹$baseamount (Kms included: " . $routeDistance->quotedDistance . ")<br>";
				if ($quote->gozoNow)
				{
					$quoteStatement = " 
				*** $destString ***
				***Summary for $cabName category ($vhtModel will be assigned. $assuredStringShow)***
				 (Kms included: " . $routeDistance->quotedDistance . ")<br>";
				}
				if ($routeRates->isTollIncluded > 0)
				{
					$quoteStatement .= "Toll Tax (Included): ₹" . $routeRates->tollTaxAmount . "<br>";
				}
				if ($routeRates->isStateTaxIncluded > 0)
				{
					$quoteStatement .= "State Tax (Included): ₹" . $routeRates->stateTax . "<br>";
				}
				if ($routeRates->driverAllowance > 0)
				{
					$quoteStatement .= "Driver's Allowance (Included): ₹" . $routeRates->driverAllowance . "<br>";
				}
				if ($routeRates->isAirportEntryFeeIncluded > 0)
				{
					$quoteStatement .= "Airport Entry Fee (Included): ₹" . $routeRates->airportEntryFee . "<br>";
				}
//		$effectiveRate	 = round($routeRates->totalAmount / $routeDistance->quotedDistance, 1);

				if ($quote->gozoNow)
				{
					$quoteStatement .= " Gozo NOW is enabled for this booking. All prices shown here by Gozo sales team are only representative.
 The actual price of the booking may be much higher or lower as it will be based on the inventory situation
 & real-time offers chosen by the customer on Gozo NOW screen.

	##### $cabName Category ###   
Additional Km beyond " . $routeDistance->quotedDistance . "km will be charged at ₹" . $routeRates[0]->ratePerKM . "/km " .
//"Effective per km rate (inclusive of tolls, interstate taxes, service tax and driver charges) ₹$effectiveRate/km  " .   
							"
Change in itinerary,  waiting times, addition of on-journey stops or waypoints requires pre-authorization. 
Any additional kms driven, parking charges, waiting times, additional pickups or drops  may require additional charges. 
Full T and Cs and inclusions/exclusions will be clearly called out in your booking confirmation. See http://bit.ly/GozoTransparency
				";
				}
				else
				{
					$quoteStatement .= "GST: ₹$tax  
	##### $cabName Category ### FINAL AMOUNT: ₹" . $routeRates[0]->totalAmount . " ###  
Additional Km beyond " . $routeDistance->quotedDistance . "km will be charged at ₹" . $routeRates[0]->ratePerKM . "/km " .
//"Effective per km rate (inclusive of tolls, interstate taxes, service tax and driver charges) ₹$effectiveRate/km  " .   
							"
Change in itinerary,  waiting times, addition of on-journey stops or waypoints requires pre-authorization. 
Any additional kms driven, parking charges, waiting times, additional pickups or drops  may require additional charges. 
Full T and Cs and inclusions/exclusions will be clearly called out in your booking confirmation. See http://bit.ly/GozoTransparency
";
				}
			}

			$data = $params + [
				'processedRoute' => $processedRoute,
				'cartypeid'		 => $vctId,
				'quoteddata'	 => $quoteData,
				'distArr'		 => $distArr,
				'routeData'		 => $arr['routeData'],
				'amount'		 => $amount,
				'arrjsondata'	 => $arrjsondata,
				'quoteStatement' => $quoteStatement,
				'tax'			 => $tax,
				'routeRatesArr'	 => $routeRates,
				'sccId'			 => $sccId,
				'cpAddons'		 => $arrCPAddons,
				'cmAddons'		 => $arrCMAddons,
				'default'		 => $default
			];
			echo CJSON::encode(['success' => $success, 'data' => $data]);
		}
		catch (Exception $ex)
		{
			$returnSet	 = new ReturnSet();
			$returnSet	 = $returnSet->setException($ex);
			echo CJSON::encode(['success' => false, 'error' => $returnSet->getErrors()]);
		}
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function actionGetamountbyvehicle1()
	{
		$success		 = false;
		/* @var $value BookingRoute */
		$fcity			 = Yii::app()->request->getParam('fcity');
		$tcity			 = Yii::app()->request->getParam('tcity');
		$cabtypeid		 = Yii::app()->request->getParam('cabtype');
		$distance		 = Yii::app()->request->getParam('distance');
		$triptype		 = Yii::app()->request->getParam('triptype');
		$multijsondata	 = Yii::app()->request->getParam('multijsondata');
		$booking_type	 = Yii::app()->request->getParam('booking_type', 1);
		$bkg_id			 = Yii::app()->request->getParam('booking_id');
		$pickLocation	 = Yii::app()->request->getParam('pickadrs');
		$dropLocation	 = Yii::app()->request->getParam('dropadrs');
		$pickup_date	 = Yii::app()->request->getParam('pickup_date');
		$pickup_time	 = Yii::app()->request->getParam('pickup_time');

		if (trim($multijsondata) != '""' && trim($multijsondata) != "" && $booking_type != 1)
		{
			$arrjsondata = json_decode($multijsondata);
		}
		else
		{
			$Arrmulticity	 = [];
			$routeModel		 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bkg_id]);
			if ($bkg_id != '' && $booking_type != 1)
			{
				foreach ($routeModel as $key => $value)
				{

					$Arrmulticity[$key] = ["pickup_city"	 => $value->brt_from_city_id,
						"drop_city"		 => $value->brt_to_city_id,
						"pickup_address" => $value->brt_from_location,
						"drop_address"	 => $value->brt_to_location,
						"date"			 => $value->brt_pickup_datetime];
				}
			}
			else
			{
				$date			 = DateTimeFormat::DatePickerToDate($pickup_date);
				$time			 = DateTime::createFromFormat('h:i A', $pickup_time)->format('H:i:00');
				$datetime		 = $date . ' ' . $time;
				$Arrmulticity[0] = ["pickup_city" => $fcity, "drop_city" => $tcity, "pickup_address" => $pickLocation, "drop_address" => $dropLocation, "date" => $datetime];
			}
			$multijsondata	 = json_encode($Arrmulticity);
			$arrjsondata	 = json_decode($multijsondata);
		}

		$routesArr = [];
		foreach ($arrjsondata as $key => $value)
		{
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $value->pickup_city;
			$routeModel->brt_to_city_id		 = $value->drop_city;
			$routeModel->brt_from_location	 = $value->pickup_address;
			$routeModel->brt_to_location	 = $value->drop_address;
			$routeModel->brt_pickup_datetime = $value->date;
			$routesArr[]					 = $routeModel;
		}
		$cabtypeid	 = VehicleTypes::model()->resetScope()->findByPk($cabtypeid)->vht_car_type; //deprecated
		$partnerId	 = Yii::app()->params['gozoChannelPartnerId'];
		$arr		 = Quotation::model()->getQuote($routesArr, $booking_type, $partnerId, $cabtypeid);
		$amount		 = ceil(abs($arr[$cabtypeid]['total_amt']));
		$tax		 = $arr[$cabtypeid]['service_tax'];
		if (count($arr) > 0)
		{
			$success = true;
		}

		echo CJSON::encode(['success' => $success, 'quoteddata' => $arr, 'routeData' => $arr['routeData'], 'gozo_markup' => $gozo_markup, 'prc_id' => $prc_id, 'type' => $type, 'rate' => $rate, 'rateExclTax' => $rateExclTaxAmount, 'amount' => $amount, 'fcityname' => $fcityname, 'tcityname' => $tcityname, 'tax' => $tax, 'est_booking_info' => $arr[$cabtypeid]]);
	}

	public function actionShowlog()
	{
		$this->pageTitle	 = "Booking Log";
		$pageSize			 = Yii::app()->params['listPerPage'];
		$qry				 = [];
		$qry['booking_id']	 = trim(Yii::app()->request->getParam('booking_id'));
		$hash				 = trim(Yii::app()->request->getParam('hash'));
		$bkgPrefModel		 = BookingPref::model()->getByBooking($qry['booking_id']);
		/* var $model BookingLog */
		$model				 = new BookingLog();
		$model->blg_event_id = '';

		if (isset($_REQUEST['BookingLog']))
		{
			$model->attributes = Yii::app()->request->getParam('BookingLog');
		}
		if ($hash != '')
		{
			$userbooking	 = Booking::model()->getBkgbyuserid($hash);
			$dataProvider	 = $model->getByBookingId($userbooking[0]['bkg_id']);
		}
		else
		{
			$dataProvider = $model->getByBookingId($qry['booking_id']);
		}
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('showlog', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry, 'hash' => $hash, 'bkgPrefModel' => $bkgPrefModel), false, $outputJs);
	}

	public function actionRelated()
	{
		$bkid = Yii::app()->request->getParam('booking_id');

		$list = Booking::model()->findRelatedBooking($bkid, true);
// $logListDataProvider = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => $pageSize),));
		$this->renderPartial('relatedList', array('model' => $list));
	}

	public function actionTriprelatedbooking()
	{
		$tripID	 = Yii::app()->request->getParam('tid');
		$bid	 = Yii::app()->request->getParam('bid');

		$list		 = Booking::model()->findTripRelatedBooking($tripID);
		$cabmodel	 = BookingCab::model()->resetScope()->findByPk($tripID);
		$bookingIDs	 = $cabmodel->bcb_bkg_id1;
		$allBkgId	 = explode(',', $bookingIDs);
		foreach ($allBkgId as $bkgId)
		{
			$bkgModel		 = Booking::model()->findByPk($bkgId);
			$isRestricted	 = BookingInvoice::validateDateRestriction($bkgModel->bkg_pickup_date);
			$restriction	 = true;
			if (!$isRestricted)
			{
				$restriction = false;
				break;
			}
		}
//        if($_POST['BookingCab'])
//        {
//         $cabmodel->bcb_vendor_amount = trim($_POST['BookingCab']['bcb_vendor_amount']);
//         $cabmodel->save();
//        }

		$this->renderPartial('relatedTripBooking', array('tripID' => $tripID, 'model' => $list, 'cabmodel' => $cabmodel, 'bookingIDs' => $bookingIDs, 'bid' => $bid, 'restriction' => $restriction));
	}

	public function actionGetcontacts()
	{
		$bkgId			 = Yii::app()->request->getParam('bkgId');
		$model			 = Booking::model()->findByPk($bkgId);
		$contactId		 = ContactProfile::getByEntityId($model->bkgUserInfo->bkg_user_id);
		$contactModel	 = Contact::model()->findByPk($contactId);
		$primaryPhone	 = ContactPhone::getContactNumber($contactId);
		$emailId		 = ContactEmail::getContactEmailById($contactId);
		$isValid		 = Filter::validatePhoneNumber($primaryPhone);
		if ($isValid)
		{
			Filter::parsePhoneNumber($primaryPhone, $code, $number);
		}
		$emailRecord = ContactEmail::getByEmail($emailId, '', '', '', 'limit 1');
		$phoneRecord = ContactPhone::getByPhone($code . $number, '', '', '', 'limit 1');
		foreach ($emailRecord as $contactEmail)
		{
			$emailVerified = $contactEmail['isVerified'];
		}
		foreach ($phoneRecord as $contactPhone)
		{
			$phoneVerified = $contactPhone['isVerified'];
		}

		$result = ['success' => false];
		if ($model)
		{
			$access					 = Booking::checkLeadContactAccess($model->bkg_status, $model->bkgTrail->bkg_assign_csr, '', $model->bkgUserInfo->bkg_contact_no, $model->bkgTrail->bkg_create_user_type, $model->bkgTrail->bkg_create_user_id);
			$result ['email']		 = ($emailId != '') ? $emailId : $model->bkgUserInfo->bkg_user_email;
			$result ['phone']		 = ($number != '' && $access) ? '+' . $code . '-' . $number : '+' . $model->bkgUserInfo->bkg_country_code . '-' . $model->bkgUserInfo->bkg_contact_no;
			$result ['altPhone']	 = ($model->bkgUserInfo->bkg_alt_contact_no != '' && $access) ? '+' . $model->bkgUserInfo->bkg_alt_country_code . '-' . $model->bkgUserInfo->bkg_alt_contact_no : " ";
			$result ['isShowPh']	 = Config::model()->getAccess('CUST_PHONE_ADMIN_VISIBLE');
			$oldModel				 = clone $model;
			$userInfo				 = UserInfo::getInstance();
			$desc					 = 'Contact Access Requested';
			$params['blg_active']	 = 1;
			$eventId				 = BookingLog::CUSTOMER_DETAILS_VIEWED;
			$params['blg_ref_id']	 = 1;
			$result['emailVerified'] = $emailVerified;
			$result['phoneVerified'] = $phoneVerified;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);

			$result['success'] = true;
		}

		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionGettravellerinfo()
	{
		$bkgId		 = Yii::app()->request->getParam('bkgId');
		$model		 = Booking::model()->findByPk($bkgId);
		$userInfo	 = UserInfo::getInstance();
		$result		 = ['success' => false];
		if ($model)
		{
			$access				 = Booking::checkLeadContactAccess($model->bkg_status, $model->bkgTrail->bkg_assign_csr, '', $model->bkgUserInfo->bkg_contact_no, $model->bkgTrail->bkg_create_user_type, $model->bkgTrail->bkg_create_user_id);
			$result ['email']	 = ($model->bkgUserInfo->bkg_user_email != '') ? $model->bkgUserInfo->bkg_user_email : " ";
			$result ['phone']	 = ($model->bkgUserInfo->bkg_contact_no != '' && $access) ? '+' . $model->bkgUserInfo->bkg_country_code . '-' . $model->bkgUserInfo->bkg_contact_no : " ";
			$result ['altPhone'] = ($model->bkgUserInfo->bkg_alt_contact_no != '' && $access) ? '+' . $model->bkgUserInfo->bkg_alt_country_code . '-' . $model->bkgUserInfo->bkg_alt_contact_no : " ";
			$result ['isShowPh'] = 0;
			if (Config::model()->getAccess('CUST_PHONE_ADMIN_VISIBLE') == 1 && ($model->bkg_status != 15 || $model->bkgTrail->bkg_create_user_type == 4 || Yii::app()->user->checkAccess('bookingContactAccess') || $model->bkgTrail->bkg_assign_csr == $userInfo->userId))
			{
				$result ['isShowPh'] = Config::model()->getAccess('CUST_PHONE_ADMIN_VISIBLE');
			}

			$oldModel				 = clone $model;
			$userInfo				 = UserInfo::getInstance();
			$desc					 = 'Contact Access Requested';
			$params['blg_active']	 = 1;
			$eventId				 = BookingLog::CUSTOMER_DETAILS_VIEWED;
			$params['blg_ref_id']	 = 1;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);

			$result['success'] = true;
		}

		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionUpdatevendoramount()
	{
		try
		{
			$userInfo	 = UserInfo::getInstance();
			$model		 = new BookingCab();
			if ($_POST['BookingCab'])
			{
				$model->attributes = $_POST['BookingCab'];

				BookingInvoice::updateGozoAmount($model->bcb_id);

				$bcbModel			 = BookingCab::model()->findByPk($model->bcb_id);
				$bookingModel		 = $bcbModel->bookings[0];
				/** @var BookingCab $bcbModel */
				$bcbModel->scenario	 = 'updateTripAmount';
				$checkaccess		 = Yii::app()->user->checkAccess('accountEdit');
				if ($checkaccess)
				{
					$bcbModel->updateTripAmount($model->bcb_vendor_amount, $userInfo, false);
					$success = true;
					goto skip;
				}
				$checkaccess2 = BookingSub::model()->checkPreAssignmentValidation($bookingModel, $model->bcb_vendor_amount);
				if ($checkaccess2)
				{
					$bcbModel->updateTripAmount($model->bcb_vendor_amount, $userInfo, false);
					$success = true;
				}
				else
				{
					throw new Exception(json_encode("Trip amount cannot be modified."), 1);
				}
			}
		}
		catch (Exception $e)
		{
			$success = false;
			$msg	 = trim($e->getMessage(), '"');
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			skip:
			$data = ['success' => $success, 'message' => $msg];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

	public function actionCreateTrip()
	{
		$this->pageTitle = "Create Trip";
		$model			 = new Booking();
		$this->render('createTrip', array('model' => $model));
	}

	public function actionGetSubscriberIDs()
	{
		$bookingid	 = Yii::app()->request->getParam('bkg_booking_id');
		$model		 = Booking::model()->findByBookingid($bookingid);
		$scvVctId	 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
		if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 1)
		{
			$bkgId		 = $model->bkg_id;
			$subscriber	 = Booking::model()->subscriberIds($bkgId);
			if ($subscriber != '')
			{
				$subsArr = [];
				foreach ($subscriber as $key => $value)
				{
					$subsArr[] = $value->bkg_booking_id;
				}
				echo json_encode(['success' => true, 'subscribers' => $subsArr]);
				Yii::app()->end();
			}
		}
		echo json_encode(['success' => false, 'subscribers' => '']);
		Yii::app()->end();
	}

	public function actionPendingaction()
	{
		$this->pageTitle = "Vendor Amount";
		$bid			 = Yii::app()->request->getParam('id');
		$model			 = Booking::model()->findByPk($bid);
		$cabmodel		 = $model->getBookingCabModel();
		$cabmodel->setScenario('updatePendingStatus');
		$models			 = Booking::model()->getBookingModelsbyCab($cabmodel->bcb_id);
		// add date restriction for edit
		$isRestricted	 = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo "Sorry, you cannot perform this action now.";
			Yii::app()->end();
		}
		$bkgIds = [];
		foreach ($models as $val)
		{
			$bkgIds[] = $val['bkg_id'];
		}
		if (isset($_REQUEST['BookingCab']))
		{

			$userInfo	 = UserInfo::getInstance();
			$modelCab	 = new BookingCab();

			$arr					 = Yii::app()->request->getParam('BookingCab');
			$modelCab->attributes	 = $arr;
			$bcbModel				 = BookingCab::model()->findByPk($modelCab->bcb_id);
			$bcbModel->scenario		 = 'updateTripAmount';

			$checkaccess = Yii::app()->user->checkAccess('changeVendorAmount');
			if (($checkaccess && $modelCab->bcb_vendor_amount > $bcbModel->bcb_vendor_amount) || ($modelCab->bcb_vendor_amount <= $bcbModel->bcb_vendor_amount))
			{
				$transaction = DBUtil::beginTransaction();
				try
				{
					$bcbModel->updateTripAmount($modelCab->bcb_vendor_amount, $userInfo);

					$modelBookingCab					 = BookingCab::model()->findByPk($arr['bcb_id']);
					$modelBookingCab->setScenario('updatePendingStatus');
					$modelBookingCab->bcb_vendor_amount	 = $arr['bcb_vendor_amount'];
					$modelBookingCab->bcb_pending_status = 0;
					$success							 = $modelBookingCab->save();
					$lowestStatus						 = $modelBookingCab->getLowestBookingStatus();
					if ($modelBookingCab->bcbVendor && ($lowestStatus == 7 || $lowestStatus == 6))
					{

						$bkgamt	 = $model->bkgInvoice->bkg_total_amount;
						$amtdue	 = $bkgamt - $model->bkgInvoice->getTotalPayment();

						$model->bkgInvoice->bkg_due_amount	 = $amtdue;
						$vndamt								 = $modelBookingCab->bcb_vendor_amount;
						$gzamount							 = $model->bkgInvoice->bkg_gozo_amount;
						if ($gzamount == '')
						{
							$gzamount							 = $bkgamt - $vndamt;
							$model->bkgInvoice->bkg_gozo_amount	 = $gzamount;
						}
						$gzdue				 = $gzamount - $model->bkgInvoice->getAdvanceReceived();
						$vendorDue			 = $model->bkgInvoice->bkg_vendor_collected - $modelBookingCab->bcb_vendor_amount;
						$userInfo			 = UserInfo::getInstance();
						$date				 = new DateTime($model->bkg_pickup_date);
						$duration			 = $model->bkg_trip_duration | 120;
						$date->add(new DateInterval('PT' . $duration . 'M'));
						$findmatchBooking	 = Booking::model()->getMatchBookingIdbyTripId($modelBookingCab->bcb_id);
						foreach ($findmatchBooking as $valBookingID)
						{
							if (AccountTransDetails::model()->revertVenTransOnEditAcc($modelBookingCab->bcb_id, $valBookingID['bkg_id'], Accounting::LI_TRIP, Accounting::LI_OPERATOR))
							{
								if ($valBookingID['bkg_vendor_collected'] > 0)
								{
									AccountTransactions::model()->AddVendorCollection($modelBookingCab->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $modelBookingCab->bcb_id, $valBookingID['bkg_id'], $modelBookingCab->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $modelBookingCab->bcb_trip_status);
								}
								AccountTransactions::model()->AddVendorPurchaseTrip($modelBookingCab->bcb_vendor_amount, $valBookingID['bkg_vendor_collected'], $modelBookingCab->bcb_id, $valBookingID['bkg_id'], $modelBookingCab->bcb_vendor_id, $date->format('Y-m-d H:i:s'), $userInfo, $modelBookingCab->bcb_trip_status);
							}
						}
						$model->bkgInvoice->scenario		 = 'vendor_collected_update';
						$model->bkgInvoice->bkg_gozo_amount	 = round($gzamount);
						$model->bkgInvoice->bkg_due_amount	 = $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getTotalPayment();

						$model->bkgInvoice->addCorporateCredit();
						$model->bkgInvoice->calculateConvenienceFee($model->bkgInvoice->bkg_convenience_charge);
						$model->bkgInvoice->calculateTotal();
						$model->bkgInvoice->calculateVendorAmount();
						// $model->bkg_account_flag = 1;
						$model->bkgInvoice->save();
					}
					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
				}
				if ($success)
				{
					$this->redirect('pendinglist');
					Yii::app()->user->setFlash('success', 'Vendor details updated successfully');
				}
			}
			$this->redirect('pendinglist');
			Yii::app()->user->setFlash('danger', 'Vendor details not updated');
		}
		$this->renderPartial('pendingaction', array('models' => $models, 'cabmodel' => $cabmodel, 'bkgids' => $bkgIds));
	}

	public function actionCancelPendingBooking()
	{
		$bkid				 = Yii::app()->request->getParam('id');
		$model				 = Booking::model()->findByPk($bkid);
		$model->bkg_status	 = Booking::STATUS_CANCELLED;
		// add date restriction for cancel
		$isRestricted		 = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo "Sorry, you cannot perform this action now.";
			Yii::app()->user->setFlash('failure', 'Sorry, you cannot perform this action now.');
			Yii::app()->end();
		}

		$success = $model->update(array('bkg_status'));

		if ($success)
		{

			$this->redirect(array('pendinglist'));

			Yii::app()->user->setFlash('success', 'Booking Cancel Successfully');
		}
	}

	public function actionSendsmstodriver()
	{
		$bkid		 = Yii::app()->request->getParam('bkid');
//  $bkmodel=new Booking();
		$bkmodel	 = Booking::model()->findByPk($bkid);
		$bcabModel	 = $bkmodel->getBookingCabModel();
		$bookingId	 = $bkmodel->bkg_booking_id;
		$ext		 = '91';
		$phone		 = $bcabModel->bcb_driver_phone;
		$driver_id	 = $bcabModel->bcb_driver_id;
		//$number = '91' . $bkmodel->bkg_extdriver_contact;

		$amount		 = $bkmodel->bkgInvoice->bkg_due_amount;
//		if ($bkmodel->bkg_trip_type == 2)
//		{
//			$amount = $bkmodel->bkg_rate_per_km . 'Per Km';
//		}
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$firstName	 = $response->getData()->phone['firstName'];
			$lastName	 = $response->getData()->phone['lastName'];
		}
		$driver_name	 = $bcabModel->bcb_driver_name;
		$cust_phone		 = "+" . $countryCode . $contactNo;
		$cust_name		 = $firstName . ' ' . $lastName;
		$pickup_address	 = $bkmodel->bkg_pickup_address;
		//$date_time		 = date('d/m/Y h:i A', strtotime($bkmodel->bkg_pickup_date));

		$date_time_server = $bcabModel->getPickupDateTime("Y-m-d H:i:s", $bkmodel->bkg_pickup_date);

		$date_time = DateTimeFormat::DateTimeToLocale($date_time_server);

		$msgCom	 = new smsWrapper();
		$userId	 = Yii::app()->user->getId();
		$msgCom->pickupDetailsToDriver($ext, $phone, $driver_id, $driver_name, $cust_phone, $cust_name, $pickup_address, $date_time, $amount, $bookingId, SmsLog::Driver, '', $userId, true);
	}

	public function actionSendpromocode()
	{
		/* @var $bkmodel Booking  */
		$bkid		 = Yii::app()->request->getParam('booking_id');
		$bkmodel	 = Booking::model()->findByPk($bkid);
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email = $response->getData()->email['email'];
		}
		//$email	 = $bkmodel->bkgUserInfo->bkg_user_email;
		$userid	 = $bkmodel->bkgUserInfo->bkg_user_id;
		$promo	 = new Promos();

		$this->renderPartial('sendpromocode', array('bkid' => $bkid, 'email' => $email, 'promomodel' => $promo, 'userid' => $userid), false, true);
	}

	public function actionGetcabs()
	{
		$vendor	 = Yii::app()->request->getParam('vendor');
		$drivers = Vehicles::model()->getJSONbyVendor($vendor);
		echo $drivers;
		Yii::app()->end();
	}

	public function getDifference($oldData, $newData)
	{
		$diff = array_diff_assoc($newData, $oldData);
		return $diff;
	}

	public function getModificationMSG($diff, $user)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff['consumer_name'])
			{
				$msg .= ' Customer Name: ' . $diff['consumer_name'] . ',';
			}
			if ($diff['consumer_phone'])
			{
				$msg .= ' Customer Phone: ' . $diff['consumer_phone'] . ',';
			}

			if ($diff['bkg_user_email'])
			{
				$msg .= ' Customer Email: ' . $diff['bkg_user_email'] . ',';
			}
			if ($diff['consumer_alt_phone'])
			{
				$msg .= ' Alternate Phone: ' . $diff['consumer_alt_phone'] . ',';
			}
			if ($diff['route_name'])
			{
				$msg .= ' Route: ' . $diff['route_name'] . ',';
			}
			if ($diff['booking_type'])
			{
				$msg .= ' Booking Type: ' . $diff['booking_type'] . ',';
			}
			if ($diff['pick_date'])
			{
				$msg .= ' Pickup Date/Time: ' . $diff['pick_date'] . ',';
			}
			if ($diff['return_date'])
			{
				$msg .= ' Return Date/Time: ' . $diff['return_date'] . ',';
			}
			if ($diff['bkg_pickup_address'])
			{
				$msg .= ' Pickup Address: ' . $diff['bkg_pickup_address'] . ',';
			}
			if ($diff['brt_pickup_location'])
			{
				$msg .= ' Pickup Address: ' . $diff['brt_pickup_location'] . ',';
			}

			if ($diff['bkg_drop_address'])
			{
				$msg .= ' Drop Address: ' . $diff['bkg_drop_address'] . ',';
			}
			if ($diff['bkg_additional_charge'])
			{
				$msg .= ' Additional Charge: ' . $diff['bkg_additional_charge'] . ',';
			}
			if ($diff['payable_amount'])
			{
				$msg .= ' Payable Amount: ' . $diff['payable_amount'] . ',';
			}
			if ($diff['bkg_driver_allowance_amount'])
			{
				$msg .= ' Driver allowance: ' . $diff['bkg_driver_allowance_amount'] . ',';
			}
			if ($diff['bkg_rate_per_km_extra'])
			{
				$msg .= ' Extra rate: ' . $diff['bkg_rate_per_km_extra'] . ',';
			}

			if ($user != 'consumer')
			{
				if ($diff['bkg_instruction_to_driver_vendor'])
				{
					$msg .= ' Special Instruction: ' . $diff['bkg_instruction_to_driver_vendor'] . ',';
				}
			}
			if ($user == 'log')
			{
				if ($diff['bkg_vendor_amount'])
				{
					$msg .= ' Vendor Amount: ' . $diff['bkg_vendor_amount'] . ',';
				}
				if ($diff['bkg_total_amount'])
				{
					$msg .= ' Booking Amount: ' . $diff['bkg_total_amount'] . ',';
				}
				if ($diff['bkg_gozo_amount'])
				{
					$msg .= ' Gozo Amount: ' . $diff['bkg_gozo_amount'] . ',';
				}
				if ($diff['bkg_advance_amount'])
				{
					$msg .= ' Customer Advance: ' . round($diff['bkg_advance_amount']) . ',';
				}
				if ($diff['bkg_vendor_collected'])
				{
					$msg .= ' Vendor Collected: ' . $diff['bkg_vendor_collected'] . ',';
				}
				if ($diff['bkg_refund_amount'])
				{
					$msg .= ' Amount Refunded: ' . $diff['bkg_refund_amount'] . ',';
				}
				if ($diff['bkg_due_amount'])
				{
					$msg .= ' Customer Payment due: ' . round($diff['bkg_due_amount']) . ',';
				}
				if ($diff['bkg_trip_distance'])
				{
					$msg .= ' Kms Driven: ' . $diff['bkg_trip_distance'] . ',';
				}
				if ($diff['bkg_convenience_charge'] != '')
				{
					$msg .= ' COD Charge: ' . round($diff['bkg_convenience_charge']) . ',';
				}
				if ($diff['bkg_driver_allowance_amount'] != '')
				{
					$msg .= ' Driver Allowance: ' . round($diff['bkg_driver_allowance_amount']) . ',';
				}
				if ($diff['bkg_credits_used'])
				{
					$msg .= ' Credits Used: ' . $diff['bkg_credits_used'] . ',';
				}
				if ($diff['bkg_base_amount'])
				{
					$msg .= ' Base Amount: ' . $diff['bkg_base_amount'] . ',';
				}


				if ($diff['bkg_invoice'])
				{
					$msg .= ' Invoice Requirement Changed,';
				}
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function actionReceipt()
	{


		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$hash	 = Yii::app()->request->getParam('hash');
		$email	 = Yii::app()->request->getParam('email', 0);
		$isPdf	 = Yii::app()->request->getParam('pdf', 1);
//		if ($bkgId != Yii::app()->shortHash->unHash($hash))
//		{
//			throw new CHttpException(400, 'Invalid data. ');
//		}
		$model	 = Booking::model()->findByPk($bkgId);
		if ($model->bkg_status > 7 && $model->bkg_status != 9)
		{
			throw new Exception('Booking not active', 401);
		}
		if ($model->bkg_pickup_date < '2023-04-01')
		{
			echo $errorStr = 'Link expired';
			Yii::app()->end();
		}
		$invoiceList		 = Booking::model()->getInvoiceByBooking($bkgId);
		$totPartnerCredit	 = AccountTransDetails::getTotalPartnerCredit($bkgId);
		$totAdvance			 = PaymentGateway::model()->getTotalAdvance($bkgId);
		if ($isPdf == 1)
		{
			$html2pdf					 = Yii::app()->ePdf->mPdf();
			$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
			$html2pdf->writeHTML($css, 1);
			$html2pdf->setAutoTopMargin	 = 'stretch';

			$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
			$htmlView = $this->renderPartial('//invoice/view', array(
				'invoiceList'		 => $invoiceList,
				'totPartnerCredit'	 => $totPartnerCredit,
				'totAdvance'		 => $totAdvance,
				'isPDF'				 => true
					), true);
			$html2pdf->writeHTML($htmlView);
			if ($email == 1)
			{

				$filename		 = $model->bkg_booking_id . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';
				$fileBasePath	 = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'receipt';
				if (!is_dir($fileBasePath))
				{
					mkdir($fileBasePath);
				}
				$fileBaseYearPath = $fileBasePath . DIRECTORY_SEPARATOR . date('Y', strtotime($model->bkg_pickup_date));
				if (!is_dir($fileBaseYearPath))
				{
					mkdir($fileBaseYearPath);
				}
				$fileBaseMonthPath = $fileBaseYearPath . DIRECTORY_SEPARATOR . date('m', strtotime($model->bkg_pickup_date));
				if (!is_dir($fileBaseMonthPath))
				{
					mkdir($fileBaseMonthPath);
				}
				$filePath = $fileBaseMonthPath . DIRECTORY_SEPARATOR . date('d', strtotime($model->bkg_pickup_date));
				if (!is_dir($filePath))
				{
					mkdir($filePath);
				}
				$file = $filePath . DIRECTORY_SEPARATOR . $filename;
				$html2pdf->Output($file, 'F');
				echo $file;
				Yii::app()->end();
			}
			else
			{
				$html2pdf->Output();
			}
		}
		else
		{
			$this->renderPartial('//invoice/view', array('invoiceList'		 => $invoiceList,
				'totPartnerCredit'	 => $totPartnerCredit,
				'totAdvance'		 => $totAdvance,
				'isPDF'				 => false), false, true);
		}
	}

	public function actionConverttolead()
	{
		$bkid = Yii::app()->request->getParam('booking_id');

		$bkgmodel	 = Booking::model()->findbyPk($bkid);
		$tmpmodel	 = BookingTemp::model()->getLeadbyRefBookingid($bkid);
		if (!$tmpmodel)
		{
			$tmpmodel = new BookingTemp();
		}
		$tmpmodel->scenario	 = 'lead_convert';
		$data				 = $bkgmodel->attributes;
		$data1				 = $tmpmodel->attributes;

		foreach ($data as $attr => $val)
		{
			if ($val == null || $val == '' || $attr == 'bkg_id')
			{
				unset($data[$attr]);
				unset($data1[$attr]);
			}
			else
			{
				$tmpmodel->setAttribute($attr, $val);
			}
		}
		$tmpmodel->bkg_ref_booking_id = $bkid;

//$tmpmodel->bkg_log_type	 = 'Unverified Booking';
		$tmpmodel->bkg_lead_source	 = 9; //'Unverified Booking';
		$success					 = false;
//   var_dump($data);var_dump($data1);exit;
		if ($tmpmodel->validate())
		{
//$tmpmodel->bkg_booking_id = 'temp';
			$success = $tmpmodel->save();
			if ($success)
			{
				$booking_id					 = BookingTemp::model()->generateBookingCodeid($tmpmodel);
				$tmpmodel->bkg_booking_id	 = $booking_id;
				$tmpmodel->save();
			}
			else
			{
				throw new Exception("Could not convert booking . (" . json_encode($tmpmodel->getErrors()) . ")");
			}
		}

		$this->renderPartial('convert', array('leadmodel' => $tmpmodel, 'success' => $success), false, true);
	}

	public function actionConvert()
	{
		exit();
		$bkid		 = Yii::app()->request->getParam('lead_id');
		$leadmodel	 = BookingTemp::model()->findByPk($bkid);
		if ($leadmodel->bkg_ref_booking_id > 0)
		{

			$this->actionEdit();
		}
		else
		{
			$this->actionCreatenew();
		}
	}

	public function actionSendpaymentlink()
	{
		$bkgid		 = Yii::app()->request->getParam('bkid');
		$model		 = Booking::model()->findByPk($bkgid);
		$enableList	 = $model->enablePaymentLink();
		if ($model->bkg_status == 2 || $model->bkg_status == 15)
		{
			$enablePaymentLink = $enableList['enablePaymentLink'];
		}
		if ($enablePaymentLink || Yii::app()->user->checkAccess('7 - Admin'))
		{
			$model->sendPaymentinfo();
//log
			$userInfo	 = UserInfo::getInstance();
			$desc		 = "Payment link sent manually by Admin";
			$eventid	 = BookingLog::PAYMENT_LINK_SENT_MANUALLY;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $model);
		}
		else
		{
			$msg = "Failed: Last link sent at " . $enableList['sentpaymentlinkdate'] . ".  You can send link after " . $enableList['nextsentpaymentlinkdate'];
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $enablePaymentLink, 'error' => $msg];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionSendconfirmation()
	{
		$bkgid		 = Yii::app()->request->getParam('bkid');
		$model		 = Booking::model()->findByPk($bkgid);
		$enableList	 = $model->enablePaymentLink();
		$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$email		 = $response->getData()->email['email'];
		}
		$enableConfirmationLink = true;
		if ($model->bkg_status == 2 || $model->bkg_status == 15)
		{
			$enableConfirmationLink = $enableList['enableConfirmLink'];
		}
		if ($enableConfirmationLink || Yii::app()->user->checkAccess('7 - Admin'))
		{
			$logType	 = UserInfo::TYPE_ADMIN;
			$userInfo	 = UserInfo::getInstance();
			$emailCom	 = new emailWrapper();
			$resend		 = 1;
			if ($contactNo != '' && $model->bkgPref->bkg_send_sms == 1)
			{
				$resendSms = true;
				smsWrapper::confirmBooking($model->bkg_id, '', $resendSms);
			}
			if ($email != '' && $model->bkgPref->bkg_send_email == 1 && $model->bkgInvoice->bkg_advance_amount == 0)
			{
				$emailCom->gotBookingemail($model->bkg_id, $logType);
			}
			else if ($email != '' && $model->bkgPref->bkg_send_email == 1 && $model->bkgInvoice->bkg_advance_amount > 0)
			{
				emailWrapper::confirmBooking($model->bkg_id, $logType, $resend);
			}
			$desc	 = "Confirmation mail/SMS sent manually by Admin";
			$eventid = BookingLog::CONFIRMATION_MAIL_SMS_SENT_MANUALLY;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $model);
		}
		else
		{
			$msg = "Failed: Last confirmation sent at " . $enableList['sendconfirmadate'] . ".  You can send Confirmation after " . $enableList['nextsendconfirmadate'];
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $enableConfirmationLink, 'error' => $msg];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionMarkedbadmessage()
	{
		$bkgId				 = Yii::app()->request->getParam('bkgId');
		$agtId				 = Yii::app()->request->getParam('agtId');
		$userId				 = Yii::app()->request->getParam('userId');
		$model				 = Booking::model()->findByPk($bkgId);
		/* $model Booking */
		$bookModel			 = new Booking();
		$bookModel->scenario = 'markedbadremark';
		$oldModel			 = clone $model;
		if ($bkgId != '')
		{
			if (isset($_POST['Booking']))
			{
				if ($bookModel->scenario == 'markedbadremark')
				{
					if ($_POST['Booking']['bkg_user_message'] != '')
					{
						$bookModel->bkg_user_message = $_POST['Booking']['bkg_user_message'];
					}
					if ($bookModel->hasErrors())
					{
						$result											 = [];
						foreach ($model->getErrors() as $attribute => $errors)
							$result[CHtml::activeId($bookModel, $attribute)] = $errors;
						$data											 = ['success' => false, 'errors' => $result];
					}
					else
					{
						$remark	 = trim($_POST['Booking']['bkg_user_message']);
						$data	 = ['success' => true, 'oldStatus' => $model->bkg_status, 'bkgId' => $bkgId, 'agtId' => $agtId, 'remark' => $remark];
					}

					/*
					  if ($bookModel->validate()) {
					  $remark = trim($_POST['Booking']['bkg_user_message']);
					  $data = ['success' => true, 'oldStatus' => $model->bkg_status, 'bkgId' => $bkgId, 'agtId' => $agtId, 'remark' => $remark];
					  } else {
					  $data = ['success' => false];
					  }
					 */
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$this->renderPartial('markedbadmessage', array('bkgId' => $bkgId, 'agtId' => $agtId, 'userId' => $userId, 'bookModel' => $model), false, true);
	}

	public function actionMarkedbadlist()
	{
		$drvId			 = Yii::app()->request->getParam('drv_id');
		$drvId			 = ($drvId > 0) ? $drvId : '0';
		$vhcId			 = Yii::app()->request->getParam('vhc_id');
		$vhcId			 = ($vhcId > 0) ? $vhcId : '0';
		/* var $model Booking */
		$model			 = new Booking();
		$dataProvider	 = $model->markedBadListByBooking($drvId, $vhcId);
		$this->renderPartial('markedbadlist', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'drvId'			 => $drvId,
			'vhcId'			 => $vhcId), false, true);
	}

	public function actionEditaccounts()
	{
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$user_id		 = Yii::app()->user->getId();
		$model			 = Booking::model()->findByPk($bkgid);
		$booking		 = Yii::app()->request->getParam('Booking');
		$bookingInvoice	 = Yii::app()->request->getParam('BookingInvoice');
		$isRestricted	 = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if ($bkgid != '' && $isRestricted == true)
		{
			$oldModel = clone $model;
			if (isset($booking))
			{
				$oldData			 = $model->getDetailsbyId($bkgid);
				$model->attributes	 = $booking;
				if (isset($bookingInvoice))
				{
					$model->bkgInvoice->attributes = $bookingInvoice;
				}
				$model->chk_user_msg = $booking['chk_user_msg'];
				$result				 = CActiveForm::validate($model);
				$return				 = [];

				if ($result == '[]')
				{
					$success = $model->editAccountsInfo($bkgid, $oldModel, $oldData);
					if (!$success)
					{
						$error = $model->getErrors();
					}
				}
				else
				{
					$return['success']	 = false;
					$return['error']	 = CJSON::decode($result);
				}
				$return = ['success' => $success, 'error' => $error];
				echo CJSON::encode($return);
				Yii::app()->end();
			}
			//$model->calculateConvenienceFee($model->bkg_convenience_charge);
			$model->bkgInvoice->calculateTotal();
			$model->bkgInvoice->calculateVendorAmount();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('editaccounts', array('model' => $model), false, $outputJs);
	}

	public function actionPricelock()
	{
		$bkgId				 = Yii::app()->request->getParam('bkg_id');
		$bkgquoteexpiredate	 = Yii::app()->request->getParam('maxDate');
		if ($bkgquoteexpiredate != '')
		{
			$result = BookingTrail::variedQuoteExpiry($bkgId, $bkgquoteexpiredate);
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}
		//$createDate	 = Yii::app()->request->getParam('');
		//$id			 = 1468439;
		//$createDate	 = '2019-11-24 03:00:57';
		$model = BookingTrail::model()->find('btr_bkg_id=:id', ['id' => $bkgId]);

//		if (isset($_REQUEST['_bkg_quote_expire_date']))
//		{
		$maxquoteexpiredate = BookingTrail::maxQuoteExpiry($bkgId);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('quoteExpiry', array('model' => $model), false, $outputJs);

//		}
	}

//		$id			 = 1468439;
//		$createDate	 = '2019-11-24 03:00:57';
//		$result		 = BookingTrail::finalQuoteExpiry($id, $createDate);
//		$this->render('quoteExpiry', array('bkgid' => $id));
//	}

	public function actionAddtransaction()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');

		$model = Booking::model()->findByPk($bkgid);
		/* var $model Booking */

		$model->scenario = 'add_transaction';

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addtransaction', array('model' => $model), false, $outputJs);
	}

	public function actionQuotation1()
	{

		$this->pageTitle		 = "New Quotation";
		/* var $model Quotation */
		$model					 = new Quotation();
		$model->scenario		 = 'insert';
		$model->qot_trip_type	 = 1;
		$model->qot_passenger	 = 1;
		$model->qot_luggage		 = 0;
		if (isset($_POST['Quotation']))
		{
			$arr				 = Yii::app()->request->getParam('Quotation');
			$model->attributes	 = $arr;
			$result				 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				$tripType	 = $arr['qot_trip_type'];
				$quoteArr	 = array();
				switch ($tripType)
				{
					case '1':
						$routeModel = Route::model()->getbyCities($model->qot_pickup_city, $model->qot_drop_city);
						if (!$routeModel)
						{
							throw new CHttpException(404, "Route not found", 404);
						}
						if ($routeModel)
						{
							$sCityModel	 = $routeModel->rutFromCity;
							$dCityModel	 = $routeModel->rutToCity;
							if ($sCityModel == '' && $dCityModel == '')
							{
								$sCityModel	 = Cities::model()->findByPk($model->qot_pickup_city);
								$dCityModel	 = Cities::model()->findByPk($model->qot_drop_city);
							}
							/* @var $place \Stub\common\Place */
							$place			 = new \Stub\common\Place();
							$fromPlaceObj	 = $place->initGoogleRoute($sCityModel->cty_lat, $sCityModel->cty_long, $sCityModel->cty_place_id, $sCityModel->cty_garage_address);
							$toPlaceObj		 = $place->initGoogleRoute($dCityModel->cty_lat, $dCityModel->cty_long, $dCityModel->cty_place_id, $dCityModel->cty_garage_address);
							//$distance = Quotation::model()->getDistance($model->qot_pickup_point, $model->qot_drop_point, 'K');
							$distArr		 = Booking::model()->getDistance($fromPlaceObj, $toPlaceObj);
							$distance		 = $distArr['totdist'];
							if ($routeModel->rut_estm_distance > $distance)
							{
								$quoteArr['qot_estm_time']		 = $routeModel->rut_estm_time;
								$estmDistance					 = $routeModel->rut_estm_distance;
								$quoteArr['qot_estm_distance']	 = $estmDistance;
							}
							else
							{
								$extraDistance					 = ($distance - $routeModel->rut_estm_distance);
								$estmDistance					 = ($routeModel->rut_estm_distance + $extraDistance);
								$quoteArr['qot_estm_distance']	 = $estmDistance;
							}
							$routeId					 = $routeModel->rut_id;
							$quoteArr['qot_pickup_city'] = $routeModel->rut_from_city_id;
							$quoteArr['qot_drop_city']	 = $routeModel->rut_to_city_id;
						}
						$quoteArr['qot_pickup_point']	 = $model->qot_pickup_point;
						$quoteArr['qot_pickup_point']	 = $model->qot_drop_point;
						$quoteArr['qot_name']			 = $model->qot_name;
						$quoteArr['qot_email']			 = $model->qot_email;
						$quoteArr['qot_phone']			 = $model->qot_phone;
						$quoteArr['qot_passenger']		 = $model->qot_passenger;
						$quoteArr['qot_luggage']		 = $model->qot_luggage;
						$quoteArr['qot_special_needs']	 = $model->qot_special_needs;
						$cabListArr						 = array();
						$ctr							 = 0;
						if (count($arr['qot_car_type']) > 0)
						{
							foreach ($arr['qot_car_type'] as $carType)
							{
								$car								 = VehicleTypes::model()->getCarByCarType($carType);
								$amount								 = Rate::model()->fetchRatebyRutnVht($routeId, $carType);
								$estTax								 = Quotation::model()->calculateTax($amount);
								$rate								 = Zones::model()->getRatesByCityId($model->qot_pickup_city, $car);
								$totalAmount						 = ($amount + $estTax);
								$cabListArr[$ctr]['car_type']		 = $car;
								$cabListArr[$ctr]['amount']			 = $amount;
								$cabListArr[$ctr]['total_amount']	 = $totalAmount;
								$cabListArr[$ctr]['total_km']		 = $estmDistance;
								$cabListArr[$ctr]['total_days']		 = '';
								$cabListArr[$ctr]['est_tax']		 = $estTax;
								$cabListArr[$ctr]['addl_km_rate']	 = '';
								$ctr								 = ($ctr + 1);
							}
						}
						break;
					case '2':
						$travelDays		 = Quotation::model()->getTravelDays($model->qot_start_date, $model->qot_end_date);
						$nightAllowDays	 = Quotation::model()->getNightAllowDays($model->qot_start_date, $model->qot_end_date);

						break;
				}
				$data = ['success' => true, 'route' => $quoteArr, 'cabList' => $cabListArr];
				//$data = ['success' => true, 'data' => $arr];
			}
			else
			{
				$data = ['success' => false, 'errors' => CJSON::decode($result)];
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$this->render('quotation_step1', array('model' => $model));
		//$this->renderPartial('quotation_step1', array('model' => $model), false, true);
	}

	public function actionQuotation2()
	{
		$this->pageTitle = "New Quotation - Calculate";
		/* var $model Quotation */
		$model			 = new Quotation();

		$this->render('quotation_step2', array('model' => $model));
		//$this->renderPartial('quotation_step2', array('model' => $model), false, true);
	}

	public function actionQuotation3()
	{
		
	}

	public function actionUploads()
	{
		$this->pageTitle = 'Gozocabs - Booking Uploads';
		$bkg_id			 = Yii::app()->request->getParam('bkg_id');
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('uploads', array('bkg_id' => $bkg_id));
	}

	public function actionMultiCityForm()
	{
		$bookingModel					 = new Booking('new');
		$bookingModel->bkg_booking_type	 = Yii::app()->request->getParam('bookingType', 3);

		if (Yii::app()->request->isAjaxRequest)
			$render	 = 'renderPartial';
		else
			$render	 = 'render';
		$this->$render('multicitywidget', ['model' => $bookingModel], false, true);
	}

	public function actionLockpaymentoption()
	{
		$bkg_id = Yii::app()->request->getParam('bkid');

		$bkid = Booking::model()->lockPaymentOption($bkg_id);
		if ($bkid == $bkg_id)
		{
			//log
			$desc		 = "Payment option locked by Admin";
			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::UPDATE_PAYMENT_EXPIRY;
			BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventid, $model);
		}
	}

	public function actionUpdatepaymentexpiry()
	{
		$bkg_id = Yii::app()->request->getParam('bkid');

		$bkid = Booking::model()->updatePaymentExpiry($bkg_id);
		if ($bkid == $bkg_id)
		{
			//log
			$desc		 = "Payment expiry time updated by Admin";
			$userInfo	 = UserInfo::getInstance();
			$eventid	 = BookingLog::UPDATE_PAYMENT_EXPIRY;
			BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventid, $model);
		}
	}

	public function actionMulticityValidate()
	{
		$data			 = Yii::app()->request->getParam('multicitydata');
		$booking_type	 = Yii::app()->request->getParam('booking_type');
		$strdate		 = Yii::app()->request->getParam('start_pickup_date');
		$strtime		 = Yii::app()->request->getParam('start_pickup_time');
		//   $arr = Quotation::model()->calculateDistance($booking_type, [(Object) $data]);
		$date			 = DateTimeFormat::DatePickerToDate($data['pickup_date']);
		$time			 = DateTime::createFromFormat('h:i A', $data['pickup_time'])->format('H:i:s');
		$datetime		 = $date . ' ' . $time;
		$routesArr		 = [];
		if ($data != '')
		{
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $data['pickup_city'];
			$routeModel->brt_to_city_id		 = $data['drop_city'];
			$routeModel->brt_from_location	 = $data['pickup_address'];
			$routeModel->brt_to_location	 = $data['drop_address'];
			$routeModel->brt_pickup_datetime = $datetime;
			$routesArr[]					 = $routeModel;
		}


		$quote			 = new Quote();
		$quote->routes	 = $routesArr;
		$quote->tripType = $booking_type;

		$quote->quoteDate = date("Y-m-d H:i:s");

		//  $quote->returnDate = $Arrmulticity[1]['date'];
		$routeDistance = new RouteDistance();
		$routeDistance->calculateDistance($quote);

		//$arr = Quotation::model()->routeDistance($routesArr, $booking_type);
		//current pickupdatetime
		$estimated_pick_date = date('Y-m-d H:i:s', strtotime($datetime . '+ ' . $routesArr[0]->brt_trip_duration . ' minute'));

		//start pickupdatetime
		$date1					 = DateTimeFormat::DatePickerToDate($strdate);
		$time1					 = DateTime::createFromFormat('h:i A', $strtime)->format('H:i:s');
		$start_pickup_datetime	 = $date1 . ' ' . $time1;

		$start_pickup_datetime	 = new DateTime(date('Y-m-d', strtotime($start_pickup_datetime)));
		$current_pick_datetime	 = new DateTime(date('Y-m-d', strtotime($datetime)));
		$diffdays				 = $start_pickup_datetime->diff($current_pick_datetime)->d + 1;
		$totdiff				 = $start_pickup_datetime->diff(new DateTime(date('Y-m-d', strtotime($estimated_pick_date))))->d + 1;

		$arrData['next_pickup_date']	 = DateTimeFormat::DateTimeToDatePicker($estimated_pick_date);
		$arrData['next_pickup_time']	 = date('h:i A', strtotime($estimated_pick_date));
		$arrData['duration']			 = $routesArr[0]->brt_trip_duration;
		$arrData['distance']			 = $routesArr[0]->brt_trip_distance;
		$arrData['date']				 = $datetime;
		$arrData['day']					 = $diffdays;
		$arrData['totday']				 = $totdiff;
		$arrData['estimated_date_next']	 = $estimated_pick_date;
		$arrData['validate_success']	 = true;
		$arrData['pickup_cty_lat']		 = $routesArr[0]->brtFromCity->cty_lat;
		$arrData['pickup_cty_long']		 = $routesArr[0]->brtFromCity->cty_long;
		$arrData['drop_cty_lat']		 = $routesArr[0]->brtToCity->cty_lat;
		$arrData['drop_cty_long']		 = $routesArr[0]->brtToCity->cty_long;

		if (!$routesArr[0]->brtFromCity->cty_bounds)
		{
			$cty_lat								 = $routesArr[0]->brtFromCity->cty_lat;
			$cty_long								 = $routesArr[0]->brtFromCity->cty_long;
//		{"northeast":{"lat":24.4948681,"lng":72.7974744},"southwest":{"lat":24.4340034,"lng":72.7310692}}
			//	24.464073  // 72.771774
			$boundArr								 = [];
			$boundArr['northeast']['lat']			 = round(($cty_lat + 0.05), 6);
			$boundArr['northeast']['lng']			 = round(($cty_long + 0.05), 6);
			$boundArr['southwest']['lat']			 = round(($cty_lat - 0.05), 6);
			$boundArr['southwest']['lng']			 = round(($cty_long - 0.05), 6);
			$routesArr[0]->brtFromCity->cty_bounds	 = json_encode($boundArr);
		}
		if (!$routesArr[0]->brtToCity->cty_bounds)
		{
			$cty_lat							 = $routesArr[0]->brtToCity->cty_lat;
			$cty_long							 = $routesArr[0]->brtToCity->cty_long;
//		{"northeast":{"lat":24.4948681,"lng":72.7974744},"southwest":{"lat":24.4340034,"lng":72.7310692}}
			//	24.464073  // 72.771774
			$boundArr							 = [];
			$boundArr['northeast']['lat']		 = round(($cty_lat + 0.05), 6);
			$boundArr['northeast']['lng']		 = round(($cty_long + 0.05), 6);
			$boundArr['southwest']['lat']		 = round(($cty_lat - 0.05), 6);
			$boundArr['southwest']['lng']		 = round(($cty_long - 0.05), 6);
			$routesArr[0]->brtToCity->cty_bounds = json_encode($boundArr);
		}


		$arrData['pickup_cty_bounds']		 = json_decode($routesArr[0]->brtFromCity->cty_bounds);
		$arrData['drop_cty_bounds']			 = json_decode($routesArr[0]->brtToCity->cty_bounds);
		$arrData['pickup_cty_radius']		 = $routesArr[0]->brtFromCity->cty_radius | 0;
		$arrData['drop_cty_radius']			 = $routesArr[0]->brtToCity->cty_radius | 0;
		$arrData['pickup_cty_is_airport']	 = $routesArr[0]->brtFromCity->cty_is_airport | 0;
		$arrData['drop_cty_is_airport']		 = $routesArr[0]->brtToCity->cty_is_airport | 0;
		$arrData['pickup_cty_is_poi']		 = $routesArr[0]->brtFromCity->cty_is_poi | 0;
		$arrData['drop_cty_is_poi']			 = $routesArr[0]->brtToCity->cty_is_poi | 0;

		$arrData['pickup_cty_loc']	 = $routesArr[0]->brtFromCity->cty_garage_address;
		$arrData['drop_cty_loc']	 = $routesArr[0]->brtToCity->cty_garage_address;

		if ($data['estimated_date'] != "")
		{
			$d1	 = new DateTime($arrData['date']);
			$d2	 = new DateTime($data['estimated_date']);
			if ($d1 < $d2)
			{
				$arrData['validate_success'] = false;
			}
		}
		echo json_encode($arrData);
		exit;
	}

	public function actionAirportTransfer()
	{
		$model		 = new Booking();
		$brtModel	 = new BookingRoute();
		$this->renderPartial("bkAirportTransfer", ['model' => $model, 'brtModel' => $brtModel], false, true);
	}

	public function actionRailwayBusTransfer()
	{
		$model		 = new Booking();
		$brtModel	 = new BookingRoute();
		$this->renderPartial("bkRailwayBusTransfer", ['model' => $model, 'brtModel' => $brtModel], false, true);
	}

	public function actionOnewayautoaddress()
	{
		$pickup_city	 = Yii::app()->request->getParam('pickup_city');
		$drop_city		 = Yii::app()->request->getParam('drop_city');
		$booking_type	 = Yii::app()->request->getParam('booking_type');
		$isGozonow		 = Yii::app()->request->getParam('isGozonow');
		$hyperInitialize = Yii::app()->request->getParam('hyperInitialize');

		$routesArr = [];
		if ($pickup_city != '')
		{
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $pickup_city;
			$routeModel->brt_to_city_id		 = $drop_city;
			$routesArr[]					 = $routeModel;
		}

		$quote			 = new Quote();
		$quote->routes	 = $routesArr;
		$quote->tripType = 1;

		$quote->quoteDate	 = date("Y-m-d H:i:s");
		$routeDistance		 = new RouteDistance();
		$routeDistance->calculateDistance($quote);

		$datetime = new DateTime('tomorrow');
		$datetime->format('Y-m-d H:i:s');

		$estimated_pick_date = $datetime->format('Y-m-d H:i:s');

		//start pickupdatetime

		$arrData['duration']				 = $routesArr[0]->brt_trip_duration;
		$arrData['distance']				 = $routesArr[0]->brt_trip_distance;
		$arrData['pickup_city_name']		 = $routesArr[0]->brtFromCity->cty_name;
		$arrData['drop_city_name']			 = $routesArr[0]->brtToCity->cty_name;
		$arrData['pickup_cty_lat']			 = $routesArr[0]->brtFromCity->cty_lat;
		$arrData['pickup_cty_long']			 = $routesArr[0]->brtFromCity->cty_long;
		$arrData['drop_cty_lat']			 = $routesArr[0]->brtToCity->cty_lat;
		$arrData['drop_cty_long']			 = $routesArr[0]->brtToCity->cty_long;
		$arrData['pickup_cty_bounds']		 = $routesArr[0]->brtFromCity->cty_bounds;
		$arrData['drop_cty_bounds']			 = $routesArr[0]->brtToCity->cty_bounds;
		$arrData['pickup_cty_radius']		 = $routesArr[0]->brtFromCity->cty_radius;
		$arrData['drop_cty_radius']			 = $routesArr[0]->brtToCity->cty_radius;
		$arrData['pickup_cty_is_airport']	 = $routesArr[0]->brtFromCity->cty_is_airport;
		$arrData['drop_cty_is_airport']		 = $routesArr[0]->brtToCity->cty_is_airport;
		$arrData['pickup_cty_is_poi']		 = $routesArr[0]->brtFromCity->cty_is_poi;
		$arrData['drop_cty_is_poi']			 = $routesArr[0]->brtToCity->cty_is_poi;

		$arrData['pickup_cty_loc']	 = $routesArr[0]->brtFromCity->cty_garage_address;
		$arrData['drop_cty_loc']	 = $routesArr[0]->brtToCity->cty_garage_address;

		$fbounds	 = $routesArr[0]->brtFromCity->cty_bounds;
		$fboundArr	 = CJSON::decode($fbounds);
		$tbounds	 = $routesArr[0]->brtToCity->cty_bounds;
		$tboundArr	 = CJSON::decode($tbounds);

		$arrData['pickup_cty_ne_lat']	 = $fboundArr['northeast']['lat'];
		$arrData['pickup_cty_ne_long']	 = $fboundArr['northeast']['lng'];
		$arrData['pickup_cty_sw_lat']	 = $fboundArr['southwest']['lat'];
		$arrData['pickup_cty_sw_long']	 = $fboundArr['southwest']['lng'];

		$arrData['drop_cty_ne_lat']	 = $tboundArr['northeast']['lat'];
		$arrData['drop_cty_ne_long'] = $tboundArr['northeast']['lng'];
		$arrData['drop_cty_sw_lat']	 = $tboundArr['southwest']['lat'];
		$arrData['drop_cty_sw_long'] = $tboundArr['southwest']['lng'];
		$arrData['booking_type']	 = $booking_type;
		$arrData['isGozonow']		 = $isGozonow;
		$this->renderPartial("onewayautoaddress", ['data' => json_encode($arrData), 'hyperInitialize' => $hyperInitialize], false, true);
	}

	public function actionShowcsr()
	{

		$bkid	 = Yii::app()->request->getParam('booking_id'); // $_REQUEST['booking_id'];
		/* @var $model Admins */
		$model	 = new Admins('search');
		if (isset($_REQUEST['Admins']))
		{
			$model->attributes = Yii::app()->request->getParam('Admins');
		}
		$dataProvider							 = $model->fetchList();
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showcsr', array('dataProvider' => $dataProvider, 'bkid' => $bkid, 'model' => $model), false, $outputJs);
	}

	public function actionAssigncsr()
	{
		$adminid								 = Yii::app()->request->getParam('csrid');
		$bkid									 = Yii::app()->request->getParam('bkid');
		$bookingmodel							 = Booking::model()->findByPk($bkid);
		$bookingmodel->bkgTrail->bkg_assign_csr	 = $adminid;
		$bookingmodel->bkgTrail->save();
		if ($bkid)
		{
			$admin	 = Admins::model()->findByPk($adminid);
			$aname	 = $admin->adm_fname;
			//$bkgid = $success;
			$desc	 = "CSR ($aname) Assigned";
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog::CSR_ASSIGN, false, false);
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true, 'dec' => $desc];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

	public function actionFlightStatus()
	{
		//  curl -v  -X GET "https://api.flightstats.com/flex/flightstatus/rest/v2/json/flight/status/9W/946/arr/2016/11/25?appId=a815ba5a&appKey=1f5649c7eb1fdd225585bb3a3b0c99da&utc=false";
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$model	 = Booking::model()->findByPk($bkgId);
		$error	 = '';
		if ($model->bkg_flight_no != '')
		{
			$arrFlight		 = explode('-', $model->bkg_flight_no);
			$airlinecode	 = $arrFlight[0];
			$flightNo		 = $arrFlight[1];
			$dateStr		 = (string) date('Y/m/d', strtotime($model->bkg_pickup_date));
			$appId			 = Yii::app()->params['flightApi']['appId'];
			$appKey			 = Yii::app()->params['flightApi']['appKey'];
			$arriveCityCode	 = Cities::model()->getCodeByCityId($model->bkg_from_city_id);
			if ($arriveCityCode != '')
			{
				$url	 = "https://api.flightstats.com/flex/flightstatus/rest/v2/json/flight/status/" . $airlinecode . "/" . $flightNo . "/arr/" . $dateStr . "?appId=" . $appId . "&appKey=" . $appKey . "&utc=false&airport=" . $arriveCityCode . "&codeType=IATA";
				$ch		 = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				$result	 = curl_exec($ch);

				if ($result === false)
				{
					$error		 = curl_error($ch);
					$arrResponse = ['success' => false, 'errors' => $error, 'msg' => 'Error occurred'];
				}
				else
				{
					$arrres = json_decode($result, true);
					if ($arrres['flightStatuses'] != '' && count($arrres['flightStatuses']) >= 1)
					{
						$n				 = (count($arrres['flightStatuses']) - 1);
						$statusArr		 = ['A' => 'Active', 'C' => 'Canceled', 'D' => 'Diverted', 'DN' => 'Data source needed', 'L' => 'Landed', 'NO' => 'Not Operational', 'R' => 'Redirected', 'S' => 'Scheduled', 'U' => 'Unknown'];
						$status			 = $statusArr[$arrres['flightStatuses'][0]['status']];
						$cityFromArr	 = $arrres['appendix']['airports'];
						$departCityCode	 = $arrres['flightStatuses'][0]['departureAirportFsCode'];
						foreach ($cityFromArr as $value)
						{
							if ($value['iata'] == $arriveCityCode)
							{
								$cityToName		 = $value['city'];
								$cityToAirport	 = $value['name'];
							}
							if ($value['iata'] == $departCityCode)
							{
								$cityFromName	 = $value['city'];
								$cityFromAirport = $value['name'];
							}
						}
						$scheduledDepartTime = $arrres['flightStatuses'][0]['operationalTimes']['publishedDeparture']['dateLocal'];
						$scheduledArriveTime = $arrres['flightStatuses'][0]['operationalTimes']['publishedArrival']['dateLocal'];
						$actualDepartTime	 = $arrres['flightStatuses'][0]['operationalTimes']['estimatedGateDeparture']['dateLocal'];
						$actualArriveTime	 = $arrres['flightStatuses'][0]['operationalTimes']['estimatedGateArrival']['dateLocal'];
						if ($scheduledDepartTime != '')
						{
							$scheduledDepartTime = date('F j, Y, g:i a', strtotime($scheduledDepartTime));
						}
						if ($scheduledArriveTime != '')
						{
							$scheduledArriveTime = date('F j, Y, g:i a', strtotime($scheduledArriveTime));
						}
						if ($actualDepartTime != '')
						{
							$actualDepartTime = date('F j, Y, g:i a', strtotime($actualDepartTime));
						}
						if ($actualArriveTime != '')
						{
							$actualArriveTime = date('F j, Y, g:i a', strtotime($actualArriveTime));
						}

						$delayDepart	 = $arrres['flightStatuses'][0]['delays']['departureGateDelayMinutes'];
						$delayArrive	 = $arrres['flightStatuses'][0]['delays']['arrivalGateDelayMinutes'];
						$arriveTerminal	 = $arrres['flightStatuses'][0]['airportResources']['arrivalTerminal'];
						$lastUpdated	 = date('Y-m-d H:i:s');
						$arrResponse	 = ['success'				 => true, 'scheduledDepartTime'	 => $scheduledDepartTime,
							'scheduledArriveTime'	 => $scheduledArriveTime, 'actualDepartTime'		 => $actualDepartTime,
							'actualArriveTime'		 => $actualArriveTime, 'delayDepart'			 => $delayDepart, 'delayArrive'			 => $delayArrive, 'status'				 => $status,
							'from'					 => $cityFromName . " (" . $cityFromAirport . ")", 'to'					 => $cityToName . " (" . $cityToAirport . ")", "arriveTerminal"		 => $arriveTerminal, "lastUpdated"			 => $lastUpdated];

						$model->bkg_flight_info = json_encode(['schDept'		 => $scheduledDepartTime, 'schArr'		 => $scheduledArriveTime, 'actDept'		 => $actualDepartTime, 'actArr'		 => $actualArriveTime,
							'delayArr'		 => $delayArrive, 'status'		 => $status, 'from'			 => $cityFromName, 'to'			 => $cityToName, 'arrTerminal'	 => $arriveTerminal, "lastUpdated"	 => $lastUpdated]);
						$model->update();
					}
					else
					{
						$arrResponse = ['success' => false, 'errors' => $arrres['error']['errorMessage'], 'msg' => $arrres['error']['errorMessage']];
					}
				}
				curl_close($ch);
			}
			else
			{
				$arrResponse = ['success' => false, 'errors' => $error, 'msg' => 'City Code Not Available'];
			}
			echo json_encode($arrResponse);
			exit;
		}
	}

	public function actionAddaccountingremark()
	{
		$bkgId		 = Yii::app()->request->getParam('bkg_id');
		$model		 = Booking::model()->findByPk($bkgId);
		$logModel	 = new BookingLog('addmarkremark');
		if (isset($_POST['BookingLog']))
		{
			$arr					 = $_POST['BookingLog'];
			$logModel->attributes	 = $arr;
			$model					 = Booking::model()->findByPk($arr['blg_booking_id']);
			$remark					 = "Remarks added on setting Accounting Flag (" . $logModel->blg_desc . ")";
			if ($logModel->validate())
			{
				$error							 = '';
				$userInfo						 = UserInfo::getInstance();
				$eventId						 = BookingLog::REMARKS_ADDED;
				$bkg_status						 = $model->bkg_status;
				$params							 = [];
				$params['blg_booking_status']	 = $bkg_status;
				$params['blg_remark_type']		 = '1';
				$error							 = BookingLog::model()->createLog($logModel->blg_booking_id, $remark, $userInfo, $eventId, $oldModel, $params);
				$success						 = ($error['errors'] != '') ? false : true;
				$data							 = ['success'	 => $success,
					'bkgstatus'	 => $bkg_status,
					'bkgid'		 => $model->bkg_id,
				];
			}
			else
			{
				if ($logModel->hasErrors())
				{
					$result = [];
					foreach ($logModel->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($logModel, $attribute)] = $errors;
					}
					$data = ['success' => false, 'errors' => $result];
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addacctremark', array('model' => $model, 'logModel' => $logModel), false, $outputJs);
	}

	public function actionAccountflag()
	{
		$bkgId			 = Yii::app()->request->getParam('bkg_id');  //$_POST['booking_id'];
		$bkgAccountFlag	 = Yii::app()->request->getParam('bkg_account_flag'); //$_POST['booking_id'];
		$success		 = false;
		$userInfo		 = UserInfo::getInstance();
		if ($bkgAccountFlag == 1)
		{
			/* var $model Booking */
			$model								 = Booking::model()->resetScope()->findByPk($bkgId);
			$oldModel							 = $model;
			$model->bkgPref->bkg_account_flag	 = 0;
			$model->bkgPref->bkg_penalty_flag	 = 0;
			$model->bkgPref->scenario			 = 'accountflag';
			if ($model->bkgPref->save())
			{
				$eventId						 = BookingLog::UNSET_ACCOUNTING_FLAG;
				$desc							 = "Accounting Flag has been cleared.";
				$params['blg_booking_status']	 = $model->bkg_status;
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
				$success						 = true;
			}
			else
			{
				print_r($model->bkgPref->getErrors());
			}
			$status = $model->bkg_status;
		}
		else if ($bkgAccountFlag == 0)
		{
			/* var $model Booking */
			$model								 = Booking::model()->resetScope()->findByPk($bkgId);
			$oldModel							 = $model;
			$model->bkgPref->bkg_account_flag	 = 1;
			$model->bkgPref->scenario			 = 'accountflag';
			if ($model->bkgPref->save())
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

	public function actionModifyvendoramount()
	{
		$this->pageTitle = "Vendor Amount";
		$bid			 = Yii::app()->request->getParam('booking_id');
		$model			 = Booking::model()->findByPk($bid);
		$cabmodel		 = $model->getBookingCabModel();
		$userInfo		 = UserInfo::getInstance();
		$modelCab		 = new BookingCab();
		if (isset($_REQUEST['BookingCab']))
		{
//			$arr								 = Yii::app()->request->getParam('BookingCab');
//			$modelBookingCab					 = BookingCab::model()->findByPk($arr['bcb_id']);
//			$modelBookingCab->bcb_vendor_amount	 = $arr['bcb_vendor_amount'];
//			$success							 = $modelBookingCab->save(); 

			$modelCab->attributes	 = $_POST['BookingCab'];
			$bcbModel				 = BookingCab::model()->findByPk($modelCab->bcb_id);
			$bcbModel->scenario		 = 'updateTripAmount';

			$checkaccess = Yii::app()->user->checkAccess('changeVendorAmount');
			if (($checkaccess && $modelCab->bcb_vendor_amount > $bcbModel->bcb_vendor_amount) || ($modelCab->bcb_vendor_amount <= $bcbModel->bcb_vendor_amount))
			{
				$bcbModel->updateTripAmount($modelCab->bcb_vendor_amount, $userInfo);
			}
			$this->redirect(array('list'));
			Yii::app()->user->setFlash('success', 'Vendor details updated successfully');
		}
		$this->renderPartial('modifyvendoramount', array('model' => $model, 'cabmodel' => $cabmodel));
	}

	public function actionReconfirmBooking()
	{
		$bkgId		 = Yii::app()->request->getParam('bkg_id'); //$_POST['bkid'];
		$model		 = Booking::model()->findByPk($bkgId);
		$oldModel	 = clone $model;
		$oldStatus	 = $model->bkg_status;
		$success	 = false;
		try
		{
			if ($model->bkg_reconfirm_flag == 0)
			{
				if ($model->bkg_agent_id == Config::get('transferz.partner.id'))
				{
					$isAccept = TransferzOffers::isAccept($model);
					if ($isAccept->success == false)
					{
						throw new Exception(json_encode("booking no longer available"), 1);
					}
					if ($model->bkg_agent_id == Config::get('transferz.partner.id') && $model->bkgPref->bkg_is_gozonow == 0)
					{
						Booking::model()->confirm(true, true, $model->bkg_id);
					}
				}
				Booking::model()->setReconfirm($model->bkg_id);
				Booking::model()->confirmMessages($model->bkg_id);
				$success = true;
			}
		}
		catch (Exception $e)
		{
			echo json_encode(['success'	 => false,
				'errors'	 => [
					'code'		 => 2,
					'message'	 => (!is_array($e->getMessage())) ? trim($e->getMessage(), '"') : $e->getMessage()
				]
			]);
			Yii::app()->end();
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'oldStatus' => $oldStatus];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionReconfirmBookingSms()
	{
		$bkgId		 = Yii::app()->request->getParam('bkg_id'); //$_POST['bkid'];
		$model		 = Booking::model()->findByPk($bkgId);
		$oldModel	 = clone $model;
		$oldStatus	 = $model->bkg_status;
		$success	 = false;
		if ($model->bkg_reconfirm_flag == 0)
		{
			/* @var $modelsub BookingSub */
			$modelsub	 = new BookingSub();
			$success	 = $modelsub->sendReconfirmSms($bkgId);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'oldStatus' => $oldStatus];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionCancellations()
	{
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
		$submodel		 = new BookingSub();
		$dataProvider	 = $submodel->getCancellationList($model);
		$this->render('report_cancellation', array('dataProvider'	 => $dataProvider[0],
			'model'			 => $model));
	}

	public function actionBkgChangeStatus()
	{
		if (isset($_REQUEST))
		{
			$bkgid1			 = $_REQUEST['bid'];
			$verifyresult	 = $this->confirmbooking($bkgid1, '', '', false, true);
			echo CJSON::encode(['success' => $verifyresult, 'bkgid' => $bkgid1]);
			Yii::app()->end();
		}
	}

	public function actionUnverifiedToquote()
	{
		if (isset($_REQUEST))
		{
			$bkgID			 = $_REQUEST['bkgid'];
			$convertresult	 = $this->convertUnverifiedToquote($bkgID, true);
			echo CJSON::encode(['success' => $convertresult, 'bkgid' => $bkgID]);
			Yii::app()->end();
		}
	}

	public function actionConfirmmobile()
	{
		if (isset($_REQUEST['Booking']))
		{
			if (($_REQUEST['Booking']['bkg_verification_code1'] != "" && $_REQUEST['Booking']['bkg_verification_code1'] != null) || ($_REQUEST['Booking']['bkg_verification_code2'] != "" && $_REQUEST['Booking']['bkg_verification_code2'] != null))
			{
				$arr			 = Yii::app()->request->getParam('Booking');
				$vcode			 = trim($arr['bkg_verification_code1']);
				$vcode1			 = trim($arr['bkg_verification_code2']);
				$bkgid1			 = $arr['bkg_id'];
				$verifyresult	 = $this->confirmbooking($bkgid1, $vcode1, $vcode);
				echo CJSON::encode(['success' => $verifyresult, 'bkgid' => $bkgid1]);
				Yii::app()->end();
			}
			else
			{
				echo CJSON::encode(['success' => false, 'bkgid' => $bmodel->bkg_id]);
				Yii::app()->end();
			}
		}
		else
		{
			$bkgid		 = Yii::app()->request->getParam('bid');
			$smsExceed	 = false;
			$success	 = false;
			if (isset($bkgid))
			{
				$model = Booking::model()->findbyPk($bkgid);
				if (!$model)
				{
					throw new CHttpException(400, 'Invalid data');
				}
			}
			if ($model->bkgUserInfo->bkg_phone_verified == 1 || $model->bkgUserInfo->bkg_email_verified == 1)
			{
				if ($model->bkg_status == 1)
				{
					$this->confirmbooking($bkgid);
				}
				$url = Yii::app()->createUrl('admin/booking/view', ['id' => $bkgid]);
				echo '<div class="panel"><div class="panel-body"><div class="col-xs-12 mt20 mb20">Booking confirmed successfully</div><a href="' . $url . '"><button class="btn btn-info">Ok</button></a></div></div>';
				exit;
			}
			else
			{
				$isAlready2Sms = SmsLog::model()->getCountVerifySms($bkgid);
				if ($isAlready2Sms >= 2)
				{
					$smsExceed = true;
				}
				else
				{
					$model->bkgUserInfo->sendVerificationCode(10, true);
				}
			}
		}

		$data = ['model' => $model, 'smsExceed' => $smsExceed];
		echo json_encode($data);
		Yii::app()->end();
		//$this->renderPartial('verify', array('model' => $model, 'smsExceed' => $smsExceed), false, true);
	}

	public function confirmbooking($bkgid1, $vcode1 = '', $vcode = '', $forceVerify = false, $confirmCash = false)
	{
		$bmodel			 = Booking::model()->findbyPk($bkgid1);
		$oldModel		 = clone $bmodel;
		$verifyresult	 = false;
		if ($confirmCash == true)
		{
			$transaction = DBUtil::beginTransaction();

			try
			{
				/* @var $modelPref BookingPref */
				$modelPref = BookingPref::model()->getByBooking($bkgid1);
				if (!$modelPref)
				{
					$modelPref->bpr_bkg_id = $bkgid1;
				}
				$modelPref->bkg_is_confirm_cash	 = 1;
				$modelPref->scenario			 = 'confirmCash';
				if ($modelPref->save())
				{
					$logType	 = UserInfo::TYPE_SYSTEM;
					$userInfo	 = UserInfo::getInstance();
					$isCod		 = BookingSub::model()->getApplicable($bmodel->bkg_from_city_id, $bmodel->bkg_to_city_id, 3);
					if ($isCod && $bmodel->bkgInvoice->bkg_advance_amount <= 0)
					{
						$bmodel->bkgInvoice->calculateConvenienceFee();
						$bmodel->bkgInvoice->calculateTotal();
						$bmodel->bkgInvoice->save();
						$bmodel->refresh();
					}
//				if (!$bmodel->confirmBooking($logType, $isCod))
//				{
//					throw new Exception("Failed to create booking");
//				}
					$returnSet = $bmodel->confirm(true, true, $bkgid1);
					if ($returnSet->isSuccess() == true)
					{
						$desc			 = "Booking confirmed as Cash payment.";
						$eventId		 = BookingLog::BOOKING_CASH_CONFIRMED;
						BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventId, $oldModel);
						DBUtil::commitTransaction($transaction);
						$verifyresult	 = true;
					}
					else
					{
						throw new Exception(json_decode($returnSet->getErrors()));
					}
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$returnSet = $returnSet->setException($ex);
			}



			return $verifyresult;
		}

		if (!$forceVerify)
		{
			if ($bmodel->bkgUserInfo->bkg_verification_code == $vcode && $vcode != '' && $bmodel->bkgUserInfo->bkg_verification_code != '')
			{
				$bmodel->bkgUserInfo->bkg_phone_verified	 = 1;
				$bmodel->bkgUserInfo->bkg_verification_code	 = '';
			}
			if ($bmodel->bkgUserInfo->bkg_verifycode_email == $vcode1 && $vcode1 != '' && $bmodel->bkgUserInfo->bkg_verifycode_email != '')
			{
				$bmodel->bkgUserInfo->bkg_email_verified	 = 1;
				$bmodel->bkgUserInfo->bkg_verifycode_email	 = '';
			}
		}
		if ($bmodel->bkgUserInfo->bkg_phone_verified == 1 || $bmodel->bkgUserInfo->bkg_email_verified == 1 || $forceVerify)
		{
			$logType = UserInfo::TYPE_SYSTEM;
			$isCod	 = BookingSub::model()->getApplicable($bmodel->bkg_from_city_id, $bmodel->bkg_to_city_id, 3);
			if (!$bmodel->confirmBooking($logType, $isCod) && !$bmodel->bkgUserInfo->save())
			{
				throw new Exception("Failed to create booking");
			}
			$bmodel->refresh();
			if (!$forceVerify)
			{
				$bmodel->bkgTrack	 = BookingTrack::model()->sendTripOtp($bmodel->bkg_id, $sendOtp			 = false);
				$bmodel->bkgTrack->save();
			}
			$bmodel->sendConfirmation($logType);
			if ($bmodel->bkgInvoice->bkg_promo1_id > 0 && $bmodel->bkgUserInfo->bkg_user_id > 0)
			{
				$creditModel1	 = UserCredits::model()->find('ucr_type=1 AND ucr_ref_id=:bkgId AND ucr_status=2 AND ucr_user_id=:user', ['bkgId' => $bmodel->bkg_id, 'user' => $bmodel->bkgUserInfo->bkg_user_id]);
				//$promoModel		 = Promos::model()->getByCode($bmodel->bkgInvoice->bkg_promo1_code);
				$promoModel		 = Promos::model()->findByPk($bmodel->bkgInvoice->bkg_promo1_id);
				if (!$promoModel)
				{
					throw new Exception('Invalid Promo code');
				}
				$promoModel->promoCode	 = $bmodel->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $bmodel->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = date('Y-m-d H:i:s', time());
				$promoModel->pickupDate	 = $bmodel->bkg_pickup_date;
				$promoModel->fromCityId	 = $bmodel->bkg_from_city_id;
				$promoModel->toCityId	 = $bmodel->bkg_to_city_id;
				$promoModel->userId		 = $bmodel->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $bmodel->bkgTrail->bkg_platform;
				$promoModel->carType	 = $bmodel->bkg_vehicle_type_id;
				$promoModel->bookingType = $bmodel->bkg_booking_type;
				$promoModel->noOfSeat	 = $bmodel->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $bmodel->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
				{
					$discountArr['cash']	 = 0;
					$discountArr['coins']	 = 0;
				}
				$cashbackStatus = UserCredits::checkDuplicateCashbackStatus($bmodel->bkg_id, $bmodel->bkgUserInfo->bkg_user_id, $discountArr['coins']);
				if ($cashbackStatus)
				{
					UserCredits::model()->setCreditBookinginfo($bmodel->bkg_id, 1, $creditModel1, $discountArr);
				}
				if ($bmodel->bkg_status == 2)
				{
					Promos::model()->incrementCounter($bmodel->bkgInvoice->bkg_promo1_id, $bmodel->bkgUserInfo->bkg_user_id, $bmodel->bkg_id);
				}
			}
			$desc			 = "Booking verified manually.";
			$eventId		 = BookingLog::BOOKING_VERIFIED;
			$userInfo		 = UserInfo::getInstance();
			BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventId, $oldModel);
			$verifyresult	 = true;
			if (!$forceVerify)
			{
				if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
				{
					$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
					if ($response->getStatus())
					{
						$email = $response->getData()->email['email'];
					}
					/* @var $bmodel booking */
					$usersModel = Users::model()->findAll('usr_email=:email', ['email' => $email]);
					foreach ($usersModel as $user)
					{
						if ($user != '' && $user->usr_email_verify != 1)
						{
							$user->usr_email_verify = 1;
							$user->save();
						}
					}
				}
				if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
				{
					/* @var $bmodel booking */
					$usersModel = Users::model()->findAll('usr_mobile=:phone', ['phone' => $bmodel->bkgUserInfo->bkg_contact_no]);
					foreach ($usersModel as $user)
					{
						if ($user != '' && $user->usr_mobile_verify != 1)
						{
							$user->usr_mobile_verify = 1;
							$user->save();
						}
					}
				}
			}
		}
		return $verifyresult;
	}

	public function convertUnverifiedToquote($bkgid1, $convertQuote = false)
	{
		$bmodel			 = Booking::model()->findbyPk($bkgid1);
		$oldModel		 = clone $bmodel;
		$convertresult	 = false;
		if ($convertQuote == true)
		{
			$userInfo = UserInfo::getInstance();
			if (!$bmodel->convertUnverifiedToquote(true))
			{
				throw new Exception("Failed to create booking");
			}
			$bmodel->refresh();
			$desc			 = "Unverified booking convert to quote.";
			$eventId		 = BookingLog::UNVERIFIED_CONVERT_TO_QUOTE;
			BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventId, $oldModel);
			$convertresult	 = true;
			return $convertresult;
		}
		return $convertresult;
	}

	public function actionCheckcabtimeoverlap()
	{
		$bcbid				 = Yii::app()->request->getParam('bcbid');
		$cabid				 = Yii::app()->request->getParam('cabid');
		$driverid			 = Yii::app()->request->getParam('driverid');
		$model				 = BookingCab::model()->findByPk($bcbid);
		$overlapDriverTrips	 = 0;
		$msg				 = '';
		$bmodels			 = $model->bookings;
		$pickupTime			 = $bmodels[0]->bkg_pickup_date;
		$dropTime			 = date('Y-m-d H:i:s', strtotime($bmodels[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));

		foreach ($bmodels as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$bcbModel				 = BookingCab::model()->findByPk($bcbid);
		$bcbModel->bcb_cab_id	 = $cabid;
		$bcbModel->bcb_driver_id = $driverid;
		$overlapTrips			 = $bcbModel->checkCabActiveTripTiming();
		if ($overlapTrips > 0)
		{
			$bookingData = $bcbModel->getCabAssignBkgId();
			$msg		 = "This cab is already assigned to this booking (" . $bookingData . "). Do you want to procced?";
		}
		if ($driverid > 0)
		{
			$overlapDriverTrips = $bcbModel->checkDriverActiveTripTiming();
			if ($overlapDriverTrips > 0)
			{
				$bookingData = $bcbModel->getDriverAssignBkgId();
				$msg		 = "This driver is already assigned to this booking (" . $bookingData . "). Do you want to procced?";
			}
		}
		$userInfo = UserInfo::getInstance();

		$data = ['success' => true, 'overlapTrips' => $overlapTrips, 'overlapDriverTrips' => $overlapDriverTrips, 'msg' => $msg, 'userType' => $userInfo->userType];
		echo json_encode($data);
		Yii::app()->end();
	}

	public function actionQuotedetails()
	{
		$bkgid		 = Yii::app()->request->getParam('id', '');
		$bkgModel	 = Booking::model()->getDetailsbyId($bkgid);
		$brtRoute	 = BookingRoute::model()->getAllByBkgid($bkgid);
		$fromCity	 = $bkgModel['bkg_from_city_id'];
		$toCity		 = $bkgModel['bkg_to_city_id'];
		$cabType	 = $bkgModel['cab_type_id'];
		if ($bkgModel['bkg_booking_type'] == 1)
		{
			$res = Route::model()->getRouteRates($fromCity, $toCity, $cabType);
		}
		if (!$res)
		{
			$quote			 = new Quote();
			$quote->routes	 = $brtRoute;
			$routeDistance	 = new RouteDistance();
			$res			 = $routeDistance->getGarageCity($fromCity, $toCity, $cabType, $bkgModel['bkg_booking_type']);
		}
		$model = Booking::model()->findByPk($bkgid);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");

		$this->$method('quote_details', array('resmodel' => $res + $bkgModel, 'model' => $model), false, $outputJs);
	}

	/* public function actionChat_NR()
	  {
	  $this->pageTitle = "Chat";
	  $entity_id		 = Yii::app()->request->getParam('bookingID');
	  $entity_type	 = 0;
	  $userInfo		 = UserInfo::getInstance();
	  $model			 = new MessageLog();
	  $success		 = false;
	  $errors			 = '';
	  if (isset($_POST['MessageLog']))
	  {
	  try
	  {
	  $transaction		 = DBUtil::beginTransaction();
	  $messageHtml		 = '';
	  $model->attributes	 = Yii::app()->request->getParam('MessageLog');
	  $arr				 = $model->attributes;
	  $message			 = $arr['msg'];
	  $result				 = $model->addMessage($entity_id, $message, $userInfo, $arr['msg_driver_visible'], $arr['msg_vendor_visible'], $arr['msg_customer_visible'], $entity_type);
	  $success			 = $result['success'];
	  $errors				 = $result['errors'];
	  if ($success == true)
	  {
	  $messageHtml = MessageLog::model()->getMessageHtmlByBkg($entity_id, $entity_type, 1);
	  DBUtil::commitTransaction($transaction);
	  }
	  }
	  catch (Exception $ex)
	  {
	  DBUtil::rollbackTransaction($transaction);
	  }

	  if (Yii::app()->request->isAjaxRequest)
	  {
	  $result = [];
	  foreach ($errors as $attribute => $err)
	  {
	  $result[CHtml::activeId($model, $attribute)] = $err;
	  }
	  $data = ['success' => $success, 'errors' => $result, 'msgListDiv' => $messageHtml];
	  echo json_encode($data);
	  Yii::app()->end();
	  }
	  $this->redirect(array('list', 'tab' => $tab));
	  }
	  $outputJs	 = Yii::app()->request->isAjaxRequest;
	  $method		 = "render" . ($outputJs ? "Partial" : "");
	  $this->$method('chat', array('model' => $model, 'bkgId' => $entity_id, 'entity_type' => $entity_type), false, $outputJs);
	  } */

	public function actionChatLog()
	{
		$success		 = false;
		$this->pageTitle = "Chat Log";
		$bkg_id			 = Yii::app()->request->getParam('bookingID');
		/* @var $model MessageLog */
		$model			 = new MessageLog();
		if ($bkg_id > 0)
		{
			$messageHtml = MessageLog::model()->getMessageHtmlByBkg($bkg_id, 0);
			$success	 = true;
		}
		$data = ['success' => $success, 'model' => $model, 'msgListDiv' => $messageHtml];
		echo json_encode($data);
	}

	public function actionEditPackage()
	{
		$bookingModel					 = new Booking('new');
		$bookingModel->bkg_booking_type	 = Yii::app()->request->getParam('bookingType', 5);
		$packageID						 = Yii::app()->request->getParam('packageID');
//        $pcdID                          = Yii::app()->request->getParam('pcdID');
//        $type                           = Yii::app()->request->getParam('type');
		$packages						 = Package::model()->findByPk($packageID);
		if (Yii::app()->request->isAjaxRequest)
			$render							 = 'renderPartial';
		else
			$render							 = 'render';
		$this->$render('packagedelwidget', ['model'			 => $bookingModel,
			'packageID'		 => $packageID,
			'packagemodel'	 => $packages], false, true);
	}

	public function actionConvertdata()
	{
		$btype	 = Yii::app()->request->getParam('bookingType');
		$leadId	 = Yii::app()->request->getParam('leadId');
	}

	public function actionChangefsaddresses()
	{
		$fpBkgId	 = Yii::app()->request->getParam('booking_id');
		$fpbooking	 = Booking::model()->findByPk($fpBkgId);

		$bkgBcbId = $fpbooking->bkg_bcb_id;
		if ($fpbooking->bkg_flexxi_type == 2)
		{
			if ($fpbooking->bkg_fp_id != '')
			{
				$fpBkgId = $fpbooking->bkg_fp_id;
			}

			$fsAddress	 = $fpbooking->bkg_pickup_address;
			$bkgBcbId	 = $fpbooking->bkg_bcb_id;
		}
		$fpbooking = Booking::model()->with('bkgFromCity')->findByPk($fpBkgId);
		if ($fpbooking->bkg_fp_id == '' && $fpbooking->bkg_flexxi_type == 2)
		{
			$fsBkgIds = Booking::model()->getSubsIdsbyPromoIds(0, $bkgBcbId);
		}
		else
		{
			$fsBkgIds = Booking::model()->getSubsIdsbyPromoIds($fpBkgId, $bkgBcbId);
		}

		$brtRoute						 = new BookingRoute();
		$brtRoute->brt_pickup_datetime	 = DateTimeFormat::DateTimeToTimePicker($fpbooking->bkg_pickup_date);
		$brtRoute->brt_from_location	 = ($fsAddress != '') ? $fsAddress : $fpbooking->bkg_pickup_address;
		if (isset($_REQUEST['BookingRoute']))
		{
			foreach ($fsBkgIds as $val)
			{
				$bprBkgId						 = $val['bookings'];
				$bmodel							 = Booking::model()->findByPk($val);
				$bmodel->bkg_pickup_address		 = $_REQUEST['BookingRoute']['brt_from_location'];
				$bmodel->bkg_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($fpbooking->bkg_pickup_date);
				$bmodel->bkg_pickup_date_time	 = $_REQUEST['BookingRoute']['brt_pickup_datetime'];
				$bmodel->save();

				$bookingRoute						 = BookingRoute::model()->getByBkgid($val);
				$bookingRoute->brt_pickup_datetime	 = $bmodel->bkg_pickup_date;
				$bookingRoute->brt_from_location	 = $bmodel->bkg_pickup_address;

				$bookingRoute->brt_from_latitude	 = $_REQUEST['BookingRoute']['brt_from_latitude'];
				$bookingRoute->brt_from_longitude	 = $_REQUEST['BookingRoute']['brt_from_longitude'];
				$bookingRoute->calculateDistance();
				$bookingRoute->save();

				$bookingPref						 = BookingPref::model()->getByBooking($bprBkgId);
				$bookingPref->bkg_fs_address_change	 = 1;
				$bookingPref->save();

				$msgCom = new smsWrapper();
				$msgCom->informAddressChangesToFlexxiFS($bmodel->bkg_country_code, $bmodel->bkg_contact_no, $val, '', '');

				$emailObj = new emailWrapper();
				$emailObj->fschangeaddress($val);
			}
			$this->redirect('list');
		}
		$this->renderPartial('change_fs_addresses', array('brtRoute' => $brtRoute, 'fpbooking' => $fpbooking), false, true);
	}

	public function actionFlexximatch()
	{
		$this->pageTitle = 'Match flexxi shared bookings';
		$bookingID		 = Yii::app()->request->getParam('id');
		$bookingMatchId	 = Yii::app()->request->getParam('match');
		$bkgId			 = Yii::app()->request->getParam('booking_id');
		$modelBookingSub = new BookingSub();
		if ($bookingID > 0 && $bookingMatchId > 0)
		{
			if ($modelBookingSub->machedFlexxiBooking($bookingID, $bookingMatchId))
			{
				$ids = $modelBookingSub->getIdsOfMatchedFlexxiBooking($bookingID);
				if ($ids)
				{
					$userInfo	 = UserInfo::getInstance();
					$eventid	 = BookingLog::MATCH_FLEXXI_BOOKING;
					$changes	 = "Your booking matched successfully";
					$desc		 = "Booking matched successfully";

					foreach ($ids as $id)
					{

						$model = Booking::model()->findByPk($id);

						BookingLog::model()->createLog($id, $desc, $userInfo, $eventid, $model);
					}
				}
				echo json_encode(["success" => true, "message" => "Booking id $bookingID matched successfully"]);
				Yii::app()->end();
			}
			else
			{
				$userInfo	 = UserInfo::getInstance();
				$eventid	 = BookingLog::MATCH_FLEXXI_BOOKING;
				$desc		 = "Booking not matched";
				BookingLog::model()->createLog($bookingID, $desc, $userInfo, $eventid, $model);
				echo json_encode(["success" => false, "message" => "Error!!!! Booking id $bookingID not matched"]);
				Yii::app()->end();
			}
		}
		if ($bkgId > 0)
		{
			$seat = true;

			$model			 = Booking::model()->findByPk($bkgId);
			$remainingSeat	 = Booking::model()->getRemainingSeats($model->bkg_bcb_id);
			if ($remainingSeat > 0)
			{
				$dataProvider = $modelBookingSub->getMatchedFlexxiBookings($model);
			}
			else
			{
				$seat = false;
			}
			$this->renderPartial('flexximatch', ['bkgId' => $bkgId, 'dataProvider' => $dataProvider, 'seat' => $seat], false, true);
		}
	}

	public function actionNoShowUnset()
	{
		$success		 = false;
		$transactions	 = DBUtil::beginTransaction();
		try
		{
			$bkgId			 = Yii::app()->request->getParam('bkg_id');
			$bookingModel	 = Booking::model()->findByPk($bkgId);
			if ($bookingModel->bkgTrack->bkg_is_no_show == 1)
			{
				$bookingModel->bkgTrack->bkg_is_no_show = 0;
				if ($bookingModel->bkgTrack->validate())
				{
					if ($bookingModel->bkgTrack->save())
					{
						$userInfo	 = UserInfo::getInstance();
						//$tripTrackingModel = TripTracking::model()->unsetNoShowByBookingId($bkgId);
						$desc		 = "Consumer No Show has been unset.";

						//sending the Push notification to Driver for Reset on NO SHOW
						$driver_id			 = $bookingModel->bkgBcb->bcb_driver_id;
						$notificationId		 = substr(round(microtime(true) * 1000), -5);
						$notificationMessage = "No-show has now been removed from BKGID " . $bookingModel->bkg_booking_id . ". You may now continue the trip.";
						$notificationTitle	 = "No Show Status Reset";
						$payLoadData		 = ['EventCode' => BookingTrack::NO_SHOW_RESET];
						if (!empty($driver_id))
						{
							$noti_success = AppTokens::model()->notifyDriver($driver_id, $payLoadData, $notificationId, $notificationMessage, "", $notificationTitle, 0);
						}
						///////////////////////////

						$event		 = BookingTrack::NO_SHOW_RESET;
						$btlModel	 = BookingTrackLog::model()->addByNonDriver(UserInfo::TYPE_ADMIN, $bkgId, $event);
						if (!$btlModel)
						{
							throw new Exception('Error occurred while saving in BookingTrackLog:' . json_encode($btlModel->getErrors()));
						}
						##Update BookingTrack Last status
						$bkgId		 = $btlModel->btl_bkg_id;
						//$eventId	 = 203;
						$eventId	 = BookingTrack::NO_SHOW_RESET;
						$dateTime	 = $btlModel->btl_sync_time;
						$btSuccess	 = BookingTrack::updateLastStatus($bkgId, $eventId, null, $dateTime);

						$eventId = BookingLog::NO_SHOW_RESET;
						BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, false, false);
						$message = "Consumer No Show has been unset.";
						$success = DBUtil::commitTransaction($transactions);
					}
					else
					{
						$errors	 = "Not Saved Successfully.\n\t\t" . json_encode($bookingModel->getErrors());
						$message = "Not Saved Successfully.\n\t\t";
						throw Exception($errors);
					}
				}
				else
				{
					$errors	 = "Validate Failed.\n\t\t" . json_encode($bookingModel->getErrors());
					$message = "Validate Failed.\n\t\t";
					throw Exception($errors);
				}
			}
		}
		catch (Exception $e)
		{
			$success = false;
			$result	 = [];
			if ($bookingModel->hasErrors())
			{
				foreach ($bookingModel->getErrors() as $attribute => $errors)
					$result[CHtml::activeId($bookingModel, $attribute)] = $errors;
			}
			$message = "Error in Consumer No Show has been unset: ";
			DBUtil::rollbackTransaction($transaction);
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errors' => $errors, 'message' => $message];
			echo json_encode($message);
			Yii::app()->end();
		}
	}

	public function actionUberlist()
	{
		$model				 = new Booking();
		$model->bkg_status	 = 0;
		if (isset($_REQUEST['Booking']))
		{
			$model->attributes = $_REQUEST['Booking'];
		}
		$model->bkg_agent_id = Yii::app()->params['uberAgentId'];
		$dataProvider		 = $model->fetchUberList(30, 'data', Yii::app()->user->getId(), NULL);
		$this->render('uberlist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionSendcabdriverinfo()
	{
		$booking_id	 = Yii::app()->request->getParam('booking_id');
		/* @var $model Booking */
		$model		 = Booking::model()->findByPk($booking_id);

		$transaction = DBUtil::beginTransaction();

		$success = false;
		try
		{
			$bkgId		 = $model->bkg_id;
			$smsSent	 = $emailSent	 = 0;
			$msg		 = "Driver and Cab details sent succesfully";
			$partnerObj	 = Filter::getPartnerObject($model->bkg_agent_id);
			if ($partnerObj != null)
			{
				if ($model->bkg_agent_id == 1273)
				{
					$typeAction = PartnerApiTracking::VENDOR_DRIVER_ALLOCATION;
				}
				if ($model->bkg_agent_id == 450 || $model->bkg_agent_id == 18190)
				{
					$typeAction = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
				}

				$partnerResponse = AgentMessages::model()->pushApiCall($model, $typeAction);
				if ($partnerResponse->status != 1)
				{
					$msg = "Driver and Cab details not sent to Partner. Need follow up.";
				}
				else
				{

					$msg = "Driver and Cab details sent to Partner.";
				}
			}
			else
			{

				notificationWrapper::driverDetailsToCustomer($bkgId, true, true, UserInfo::TYPE_SYSTEM);
			}
			DBUtil::commitTransaction($transaction);

			$return = $msg;
		}
		catch (Exception $ex)
		{
			$success = false;
			$result	 = [];
			if ($model->hasErrors())
			{
				foreach ($model->getErrors() as $attribute => $errors)
					$result[CHtml::activeId($model, $attribute)] = $errors;
			}
			$return = "Error in sending details : ";
			DBUtil::rollbackTransaction($transaction);
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			echo $return;
			Yii::app()->end();
		}
		Yii::app()->end();
	}

	public function actionRequestList()
	{

		$this->pageTitle = 'Availabe 3rd party providers in Source zone';
		$bkgId			 = Yii::app()->request->getParam('booking_id');
		$bkgModel		 = Booking::model()->findByPk($bkgId);
		$from			 = $bkgModel->bkg_from_city_id;
		$to				 = $bkgModel->bkg_to_city_id;
		$zoneIds		 = ZoneCities::model()->findZoneByCity($from);
		$vhcCatId		 = $bkgModel->bkgSvcClassVhcCat->scv_vct_id;
		$vehicleType	 = VehicleCategory::model()->findByPk($vhcCatId);
		//$vehicleType	 = VehicleTypes::model()->findByPk($vhtId);
		$dataProvider	 = BookingUnregVendor::model()->getRequestList($bkgId, $from, $to, $zoneIds);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('requestlist', array('vehicleType' => $vehicleType, 'bkgmodel' => $bkgModel, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	/**
	 * Function for sending SMS to unregistered vendors
	 */
	public function actionSendSMSToUnregisteredVendors()
	{
		$bkgId		 = Yii::app()->request->getParam('bkg_id');
		$userInfo	 = UserInfo::getInstance();
		$result		 = Booking::model()->sendSMSToUnregisteredVendors($bkgId);
		if (Yii::app()->request->isAjaxRequest)
		{
			$eventId = 113;
			if ($result['success'] == true)
			{
				if ($result['countSms'] > 0)
				{
					if ($result['countSms'] > 1)
					{
						$desc = $result['countSms'] . ' messages has been sent successfully';
					}
					else
					{
						$desc = $result['countSms'] . ' message has been sent successfully';
					}
				}
				else
				{
					$desc = "No vendors found in this zone so message can't be sent.";
				}
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, false, false);
			}
			else
			{
				$desc = $result['error'];
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, false, false);
			}
			$data = ['success' => $result['success'], 'countSms' => $result['countSms'], 'errors' => $result['error']];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionCarVerify()
	{
		$success	 = false;
		$trans		 = DBUtil::beginTransaction();
		$userInfo	 = UserInfo::getInstance();

		$booking_id = Yii::app()->request->getParam('booking_id');
		try
		{


			$btkmodel = BookingTrack::model()->getByBkgId($booking_id);
			if (!empty($btkmodel))
			{

				$btkmodel->bkg_force_verification	 = 1;
				$btkmodel->update();
				$success							 = true;
				$eventid							 = BookingLog::CAB_VERIFIED;
				$desc								 = "Force cab verification flag on ";
				BookingLog::model()->createLog($booking_id, $desc, $userInfo, $eventid);
				DBUtil::commitTransaction($trans);
			}
			$data = $success;
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		catch (Exception $e)
		{

			DBUtil::rollbackTransaction($trans);
			// Booking Log

			$desc = "Failed to on force cab verification flag :  ({$e->getMessage()})";
		}
	}

	public function actionunregVndAccept()
	{
		$this->pageTitle = '';
		$buvid			 = Yii::app()->request->getParam('buv_id');
		$decline_id		 = Yii::app()->request->getParam('decline_id');
		$bmodel			 = BookingUnregVendor::model()->findByPk($buvid);
		$bkgModel		 = Booking::model()->findByPk($bmodel->buv_bkg_id);
		$model			 = UnregVendorRequest::model()->findByPk($bmodel->buv_vendor_id);

		$params = ['buv_id'		 => $bmodel->buv_id,
			'buv_is_add'	 => $bmodel->buv_is_add,
			'buv_is_apply'	 => $bmodel->buv_is_apply,
			'buv_bid_amount' => $bmodel->buv_bid_amount,
			'buv_active'	 => $bmodel->buv_active,
			'booking_id'	 => $bkgModel->bkg_booking_id,
			'trip_id'		 => $bkgModel->bkg_bcb_id,
			'from_city'		 => $bkgModel->bkgFromCity->cty_name,
			'to_city'		 => $bkgModel->bkgToCity->cty_name,
			'created_date'	 => DateTimeFormat::DateTimeToLocale($bkgModel->bkg_create_date),
			'pickup_date'	 => DateTimeFormat::DateTimeToLocale($bkgModel->bkg_pickup_date),
			'pickup_address' => $bkgModel->bkg_pickup_address,
			'trip_distance'	 => $bkgModel->bkg_trip_distance];

		if ($decline_id == 3)
		{
			$model->uvr_active = 0;
			if ($model->validate())
			{
				if ($model->save())
				{
					$this->redirect(array('admin/booking/list?tab=2'));
				}
			}
		}
		$this->render('unregVendorBid', array('buvmodel'	 => $bmodel,
			'model'		 => $model,
			'bkgModel'	 => $bkgModel,
			'sendParams' => $params), false, true);
	}

	public function actionAddVndAccept()
	{
		$success		 = false;
		$buvId			 = Yii::app()->request->getParam('buv_id');
		$bmodel			 = BookingUnregVendor::model()->findByPk($buvId);
		$model			 = UnregVendorRequest::model()->findByPk($bmodel->buv_vendor_id);
		$is_Add			 = Yii::app()->request->getParam('is_Add');
		$firstName		 = Yii::app()->request->getParam('firstName');
		$lastName		 = Yii::app()->request->getParam('lastName');
		$phoneNumber	 = Yii::app()->request->getParam('phoneNumber');
		$emailAddress	 = Yii::app()->request->getParam('emailAddress');
		$city			 = Yii::app()->request->getParam('businessCity');
		if ($is_Add == 2)
		{
			$vndActive = 3;
		}
		if ($is_Add == 1)
		{
			$vndActive	 = 1;
			$buvApply	 = 1;
		}
		$postParams = [
			'vnd_owner'				 => $firstName . " " . $lastName,
			'vnd_firstName'			 => $firstName,
			'vnd_lastName'			 => $lastName,
			'vnd_email'				 => $emailAddress,
			'vnd_phone'				 => $phoneNumber,
			'vnd_city'				 => $city,
			'vnd_voter_no'			 => $model->uvr_vnd_voter_no,
			'vnd_pan_no'			 => $model->uvr_vnd_pan_no,
			'vnd_aadhaar_no'		 => $model->uvr_vnd_aadhaar_no,
			'vnd_license_no'		 => $model->uvr_vnd_license_no,
			'vnd_is_driver'			 => $model->uvr_vnd_is_driver,
			'vnd_address'			 => $model->uvr_vnd_address,
			'vnd_username'			 => $model->uvr_vnd_username,
			'vnd_password'			 => $model->uvr_vnd_password,
			'vnd_aadhaar_front_path' => $model->uvr_vnd_aadhaar_front_path,
			'vnd_voter_front_path'	 => $model->uvr_vnd_voter_id_front_path,
			'vnd_pan_front_path'	 => $model->uvr_vnd_pan_front_path,
			'vnd_licence_front_path' => $model->uvr_vnd_licence_front_path,
			'vnd_uvr_id'			 => $model->uvr_id,
			'vnd_active'			 => $vndActive
		];
		try
		{
			$transaction = DBUtil::beginTransaction();
			switch ($is_Add)
			{
				case 1:
					$result = Vendors::model()->createNew($postParams);
					if ($result['success'] == true)
					{
						$bcbId		 = $bmodel->buvBkg->bkgBcb->bcb_id;
						$vendorId	 = $bmodel->buv_vendor_id;
						$bidAmount	 = $bmodel->buv_bid_amount;
						$remark		 = '';
						// $res = $model->bkgBcb->assignVendor($model->bkgBcb->bcb_id, $agtid, $bid_amount, $remark, UserInfo::getInstance());
						$res		 = $bmodel->buvBkg->bkgBcb->assignVendor($bcbId, $vendorId, $bidAmount, $remark, UserInfo::getInstance());
						if (!$res->isSuccess())
						{
							$errMessage = Filter::getNestedValues($res->getErrors());
							throw $res->getException();
						}
						$bmodel->scenario		 = '';
						$bmodel->buv_is_apply	 = $buvApply;
						$bmodel->buv_apply_date	 = new CDbExpression('NOW()');
						if ($bmodel->validate())
						{
							if ($bmodel->save())
							{
								$success	 = DBUtil::commitTransaction($transaction);
								$newStatus	 = 3;
							}
						}
						else
						{
							throw new Exception(json_encode($bmodel->getErrors()));
						}
					}
					else
					{
						throw new Exception(json_encode($result['errors']));
					}
					break;
				case 2:

					$result = Vendors::model()->createNew($postParams, 1);
					if ($result['success'] == true)
					{
						$bmodel->scenario	 = 'statusUpdate';
						$bmodel->buv_is_add	 = 1;
						//$bmodel->buv_active	 = 2;
						if ($bmodel->validate())
						{
							if ($bmodel->save())
							{
								$success	 = DBUtil::commitTransaction($transaction);
								$newStatus	 = 2;
							}
						}
						else
						{
							throw new Exception(json_encode($bmodel->getErrors()));
						}
					}
					else
					{
						throw new Exception(json_encode($result['errors']));
					}

					break;
				case 3:
					$bmodel->buv_active	 = 0;
					$model->uvr_active	 = 0;
					if ($bmodel->validate())
					{
						$bmodelRes = $bmodel->save();
					}
					else
					{
						throw new Exception(json_encode($model->getErrors()));
					}
					if ($model->validate())
					{
						$modelRes = $model->save();
					}
					else
					{
						throw new Exception(json_encode($model->getErrors()));
					}
					if ($modelRes == true && $bmodelRes == true)
					{
						$success	 = DBUtil::commitTransaction($transaction);
						$newStatus	 = 3;
					}
					break;
			}
		}
		catch (Exception $e)
		{
			echo json_encode(['success'	 => $success,
				'errors'	 => [
					'code'		 => 2,
					'message'	 => $e->getMessage()
				]
			]);
			DBUtil::rollbackTransaction($transaction);
			ReturnSet::setException($e);
			Yii::app()->end();
		}



		if (Yii::app()->request->isAjaxRequest)
		{

			$data = ['success' => $success, 'newStatus' => $newStatus, 'message' => $result['errors']];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/*  below function has a bad name should be called make manual assignment owner */

	public function actionAutoAssignment()
	{
		$userInfo	 = UserInfo::getInstance();
		$userModel	 = Admins::model()->findByPk($userInfo->userId);
		$userName	 = $userModel->adm_user;
		$fname		 = $userModel->adm_fname;
		$lname		 = $userModel->adm_lname;
		$name		 = $fname . ' ' . $lname;
		$success	 = false;
		$model		 = new BookingPref();
		$zoneId		 = Yii::app()->request->getParam('zoneId');
		$arrAccess	 = [];
		if (Yii::app()->user->checkAccess('assignNorth') && $zoneId == 1)
		{
			$arrAccess[] = 1;
		}
		if (Yii::app()->user->checkAccess('assignWest') && $zoneId == 2)
		{
			$arrAccess[] = 2;
		}
		if (Yii::app()->user->checkAccess('assignCentral') && $zoneId == 3)
		{
			$arrAccess[] = 3;
		}
		if (Yii::app()->user->checkAccess('assignSouth') && ($zoneId == 4))
		{
			$arrAccess[] = 4;
			$arrAccess[] = 7;
		}
		if (Yii::app()->user->checkAccess('assignEast') && $zoneId == 5)
		{
			$arrAccess[] = 5;
		}
		if (Yii::app()->user->checkAccess('assignNorthEast') && $zoneId == 6)
		{
			$arrAccess[] = 6;
		}
//	    $regionID = $this->bkgFromCity->ctyState->stt_zone; 
//	$regionList= Vendors::model()->getRegionList();
//		$regionName = str_replace(" ",  "",$regionList[$regionID]);
//		$accessName = "assign".$regionName;

		$bookingID		 = $model->getAssignmentBookinID($userInfo->userId, $arrAccess);
		//$bookingID = 696575;
		$bookingModel	 = Booking::model()->findByPk($bookingID);
		$regionID		 = $bookingModel->bkgFromCity->ctyState->stt_zone;

		$bookingName							 = $bookingModel->bkg_booking_id;
		//$role = $model->getAssignmentRole();
		$bookingPrefModel						 = $model->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $bookingID]);
		$bookingPrefModel->bpr_assignment_level	 = 1;
		$bookingPrefModel->bpr_assignment_id	 = $userInfo->userId; //Yii::app()->user->getId();
//$bookingPrefModel->bpr_assignment_fdate 
		if ($bookingPrefModel->update())
		{
			$desc	 = "Booking Id - " . $bookingName . " Manual assignment ownership given to " . $name;
			$eventid = BookingLog::AUTO_ASSIGNMENT;
			BookingLog::model()->createLog($bookingID, $desc, $userInfo, $eventid, false, '', '', '');
			$success = true;
			$tab	 = 2;
			$url	 = Yii::app()->createUrl("admin/booking/list", ["searchid" => $bookingID, "tab" => $tab]);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'BookingName' => $bookingName, 'url' => $url];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionAssignOm()
	{
		$success	 = false;
		$bkgId		 = Yii::app()->request->getParam('booking_id');
		$userInfo	 = UserInfo::getInstance();
		$userModel	 = Admins::model()->findByPk($userInfo->userId);
		$userName	 = $userModel->adm_user;
		$fname		 = $userModel->adm_fname;
		$lname		 = $userModel->adm_lname;
		$name		 = $fname . ' ' . $lname;

		$bookingName = Booking::model()->findByPk($bkgId)->bkg_booking_id;
		if (Yii::app()->request->isAjaxRequest)
		{

			$model									 = new BookingPref();
			$bookingPrefModel						 = $model->find('bpr_bkg_id=:bkg_id', ['bkg_id' => $bkgId]);
			$bookingPrefModel->bpr_assignment_level	 = 2;
			$bookingPrefModel->bpr_assignment_id	 = 0;
			if ($bookingPrefModel->bpr_assignment_fdate == NULL || $bookingPrefModel->bpr_assignment_fdate == "")
			{
				$bookingPrefModel->bpr_assignment_fdate = new CDbExpression('NOW()');
			}
			$bookingPrefModel->bpr_assignment_ldate = new CDbExpression('NOW()');

			if ($bookingPrefModel->update())
			{
				$desc	 = " Booking Id - " . $bookingName . " is delegated to Operation Manager";
				$eventid = BookingLog::ESCALATE_OM;
				$success = BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, false, '', '', '');
				if ($success && $bookingPrefModel->bkg_is_fbg_type == 0)
				{
					notificationWrapper::notifyDTMBooking($bkgId);
				}
				$msg = $bookingName . " is successfully delegated to Operation Manager";
				if ($success)
				{
					$notificationId	 = substr(round(microtime(true) * 1000), -5);
					$omIds			 = Admins::model()->getCsrNotificationList();
					$payLoadData	 = ['bookingId' => $bookingPrefModel->bpr_bkg_id, 'EventCode' => Booking::CODE_DELEGATED_OM];
					$title			 = "Booking Delegated to OM - " . $bookingName;
					$message		 = $bookingName . " is delegated to Operation Manager. ";
					foreach ($omIds as $omId)
					{
						$omId = $omId['adm_id'];
						AppTokens::model()->notifyAdmin($omId, $payLoadData, $notificationId, $message, $title);
					}
				}
				$data = ['success' => $success, 'msg' => $msg];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionAllocatecsr()
	{
		$bkid	 = Yii::app()->request->getParam('booking_id');
		/* @var $model Admins */
		$model	 = new Admins('search');
		if (isset($_REQUEST['Admins']))
		{
			$model->attributes = Yii::app()->request->getParam('Admins');
		}
		$dataProvider							 = $model->fetchList();
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('allocatecsr', array('dataProvider' => $dataProvider, 'bkid' => $bkid, 'model' => $model), false, $outputJs);
	}

	public function actionDispatchcsr()
	{
		$bkid	 = Yii::app()->request->getParam('booking_id');
		/* @var $model Admins */
		$model	 = new Admins('search');
		$scqId	 = ServiceCallQueue::checkExistDispatchCsr($bkid);
		if (isset($_REQUEST['Admins']))
		{
			$model->attributes = Yii::app()->request->getParam('Admins');
		}
		$dataProvider							 = $model->fetchList();
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('dispatchcsr', array('dataProvider' => $dataProvider, 'bkid' => $bkid, 'model' => $model, 'scqId' => $scqId), false, $outputJs);
	}

	public function actionAssigncsrbyOM()
	{
		$userInfo								 = UserInfo::getInstance();
		$userModel								 = Admins::model()->findByPk($userInfo->userId);
		$userName								 = $userModel->adm_user;
		$fname									 = $userModel->adm_fname;
		$lname									 = $userModel->adm_lname;
		$name									 = $fname . ' ' . $lname;
		$adminid								 = Yii::app()->request->getParam('csrid');
		$bkid									 = Yii::app()->request->getParam('bkid');
		$bookingmodel							 = Booking::model()->findByPk($bkid);
		$bookingmodel->bkgTrail->bkg_assign_csr	 = $adminid;
		$bookingmodel->bkgTrail->save();

		$bookingmodel->bkgPref->bpr_assignment_level = 1;
		$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;

		if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
		{
			$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
		}
		$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');

		$bookingmodel->bkgPref->save();
		if ($bookingmodel->bkgPref->save())
		{
			$admin	 = Admins::model()->findByPk($adminid);
			$aname	 = $admin->adm_fname;
			//$bkgid = $success;
			$desc	 = "CSR (" . $aname . ") Allocated By " . $name;
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog::CSR_ALLOCATE, false, false);
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$tab	 = 2;
			$url	 = Yii::app()->createAbsoluteUrl("admin/booking/list", ["searchid" => $bkid, "tab" => $tab]);
			$data	 = ['success' => true, 'dec' => $desc, 'url' => $url];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$tab = 2;
		$this->redirect(array('list', 'tab' => $tab));
	}

	public function actionAssignDispatchCsr()
	{
		$success		 = true;
		$userInfo		 = UserInfo::getInstance();
		$userModel		 = Admins::model()->findByPk($userInfo->userId);
		$userName		 = $userModel->adm_user;
		$fname			 = $userModel->adm_fname;
		$lname			 = $userModel->adm_lname;
		$name			 = $fname . ' ' . $lname;
		$adminid		 = Yii::app()->request->getParam('csrid');
		$bkid			 = Yii::app()->request->getParam('bkid');
		$bookingmodel	 = Booking::model()->findByPk($bkid);
		if ($adminid)
		{
			$scqId = ServiceCallQueue::checkExistDispatchCsr($bkid);
			if ($scqId > 0)
			{
				$success = false;
				$msg	 = "Failed to allocated this booking.This booking already allocated";
				goto skip;
			}

			$adminTeams		 = Teams::getMultipleTeamid($adminid);
			$dispatchTeamId	 = 0;
			$firstTeamId	 = 0;
			$i				 = 0;
			if ($adminTeams)
			{
				foreach ($adminTeams as $adminTeam)
				{
					if ($i == 0)
					{
						$firstTeamId = $adminTeam['tea_id'];
						$i++;
					}
					if (in_array($adminTeam['tea_id'], [48, 4]))
					{
						$dispatchTeamId = $adminTeam['tea_id'];
						break;
					}
				}
			}
			$adminTeamId = $dispatchTeamId > 0 ? $dispatchTeamId : $firstTeamId;
		}
		$bookingmodel->bkgPref->bpr_assignment_level = 1;
		$bookingmodel->bkgPref->bpr_assignment_id	 = $adminid;

		if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
		{
			$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
		}
		$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');

		if ($bookingmodel->bkgPref->save())
		{
			$dispatchCsr = ServiceCallQueue::addDispatchAllocateCsr($adminid, $bookingmodel, $adminTeamId, 1);
			$admin		 = Admins::model()->findByPk($adminid);
			$aname		 = $admin->adm_fname;
			$desc		 = "CSR (" . $aname . ") Allocated By " . $name . " (Manually) ";
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog::CSR_ALLOCATE, false, false);
			if ($dispatchCsr)
			{
				$success = true;
				$msg	 = "CSR Succesfully Allocated to(" . $aname . ")";
			}
			else
			{
				$success = false;
				$msg	 = "Some error occure";
			}
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			skip:
			$data = ['success' => $success, 'dec' => $msg];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

	public function actionSelfAssignOm()
	{
		$userInfo									 = UserInfo::getInstance();
		$userModel									 = Admins::model()->findByPk($userInfo->userId);
		$userName									 = $userModel->adm_user;
		$fname										 = $userModel->adm_fname;
		$lname										 = $userModel->adm_lname;
		$name										 = $fname . ' ' . $lname;
		$bkid										 = Yii::app()->request->getParam('booking_id');
		$bookingmodel								 = Booking::model()->findByPk($bkid);
		$bookingName								 = Booking::model()->findByPk($bkid)->bkg_booking_id;
		$bookingmodel->bkgPref->bpr_assignment_level = 3;
		$bookingmodel->bkgPref->bpr_assignment_id	 = $userInfo->userId;
		if ($bookingmodel->bkgPref->bpr_assignment_fdate == NULL || $bookingmodel->bkgPref->bpr_assignment_fdate == "")
		{
			$bookingmodel->bkgPref->bpr_assignment_fdate = new CDbExpression('NOW()');
		}
		$bookingmodel->bkgPref->bpr_assignment_ldate = new CDbExpression('NOW()');

		if ($bookingmodel->bkgPref->save())
		{
			$desc = "BookingID:  " . $bookingName . " Self Assigned By " . $name;
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), BookingLog::SELF_ASSIGN, false, false);
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$tab	 = 2;
			$url	 = Yii::app()->createAbsoluteUrl("admin/booking/list", ["searchid" => $bkid, "tab" => $tab]);
			$data	 = ['success' => true, 'dec' => $desc, 'url' => $url];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$tab = 2;
		$this->redirect(array('list', 'tab' => $tab));
	}

	public function actionAutoAssignmentByBid()
	{
		//1:blocked;0:open;
		$userInfo		 = UserInfo::getInstance();
		$userModel		 = Admins::model()->findByPk($userInfo->userId);
		$userName		 = $userModel->adm_user;
		$fname			 = $userModel->adm_fname;
		$lname			 = $userModel->adm_lname;
		$name			 = $fname . ' ' . $lname;
		$bkid			 = Yii::app()->request->getParam('booking_id');
		$bookingmodel	 = Booking::model()->findByPk($bkid);
		if ($bookingmodel->bkgPref->bkg_block_autoassignment == 0)
		{
			$existAutoAssignment = 1;
			$desc				 = "BookingID:  " . $bookingmodel->bkg_booking_id . " is blocked for auto assignment ";
			$eventid			 = BookingLog::BLOCK_AUTOASSIGNMENT;
		}
		else
		{
			$existAutoAssignment = 0;
			$desc				 = "BookingID:  " . $bookingmodel->bkg_booking_id . " is unblocked for auto assignment ";
			$eventid			 = BookingLog::UNBLOCK_AUTOASSIGNMENT;
		}
		$bookingmodel->bkgPref->bkg_block_autoassignment = $existAutoAssignment;
		if ($bookingmodel->bkgPref->save())
		{
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), $eventid, false, false);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			//$url = Yii::app()->createAbsoluteUrl("admin/booking/list", ["tab" => 2]);
			//, 'url' => $url
			$data = ['success' => true, 'dec' => $desc];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$this->redirect(array('list', 'tab' => 2));
	}

	public function actionBlockUnassign()
	{
		$bkid		 = Yii::app()->request->getParam('booking_id');
		$userInfo	 = UserInfo::getInstance();
		$userModel	 = Admins::model()->findByPk($userInfo->userId);
		$userName	 = $userModel->adm_user;
		$fname		 = $userModel->adm_fname;
		$lname		 = $userModel->adm_lname;
		$name		 = $fname . ' ' . $lname;

		$bookingmodel = Booking::model()->findByPk($bkid);
		if ($bookingmodel->bkgBcb->bcb_block_autounassignment == 0)
		{
			$existAutoUnassignment	 = 1;
			$desc					 = "BookingID:  " . $bookingmodel->bkg_booking_id . " is blocked for auto unassignment ";
			$eventid				 = BookingLog::BLOCK_AUTOASSIGNMENT;
		}
		else
		{
			$existAutoUnassignment	 = 0;
			$desc					 = "BookingID:  " . $bookingmodel->bkg_booking_id . " is unblocked for auto unassignment ";
			$eventid				 = BookingLog::UNBLOCK_AUTOASSIGNMENT;
		}
		$bookingmodel->bkgBcb->bcb_block_autounassignment = $existAutoUnassignment;
		if ($bookingmodel->bkgBcb->save())
		{
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), $eventid, false, false);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			//$url = Yii::app()->createAbsoluteUrl("admin/booking/list", ["tab" => 2]);
			//, 'url' => $url
			$data = ['success' => true, 'dec' => $desc];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$this->redirect(array('list', 'tab' => 2));
	}

	public function actionAutoCancel()
	{
		//0:enable AutoCAncel: 1:stop AutoCancel
		$userInfo		 = UserInfo::getInstance();
		$userModel		 = Admins::model()->findByPk($userInfo->userId);
		$userName		 = $userModel->adm_user;
		$fname			 = $userModel->adm_fname;
		$lname			 = $userModel->adm_lname;
		$name			 = $fname . ' ' . $lname;
		$bkid			 = Yii::app()->request->getParam('booking_id');
		$bookingmodel	 = Booking::model()->findByPk($bkid);
		$oldStatus		 = $bookingmodel->bkg_status;
		if ($bookingmodel->bkgPref->bkg_autocancel == 0)
		{
			$existAutoCancel = 1;
			$desc			 = "BookingID:  " . $bookingmodel->bkg_booking_id . " is blocked for auto cancel ";
			$eventid		 = BookingLog::STOP_AUTOCANCEL;
		}
		else
		{
			$existAutoCancel = 0;
			$desc			 = "BookingID:  " . $bookingmodel->bkg_booking_id . " is enable for auto cancel ";
			$eventid		 = BookingLog::ENABLE_AUTOCANCEL;
		}
		$bookingmodel->bkgPref->bkg_autocancel = $existAutoCancel;
		if ($bookingmodel->bkgPref->save())
		{
			BookingLog::model()->createLog($bkid, $desc, UserInfo::getInstance(), $eventid, false, false);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			//$url = Yii::app()->createAbsoluteUrl("admin/booking/list", ["tab" => 2]);
			//, 'url' => $url
			$data = ['success' => true, 'dec' => $desc, 'status' => $oldStatus];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$this->redirect(array('list', 'tab' => 2));
	}

	public function actionChangeDutySlipStatus()
	{
		if (isset($_REQUEST))
		{
			$bkgId					 = $_REQUEST['booking_id'];
			$existdutySlipRequired	 = $_REQUEST['dsval'];
			$bookingmodel			 = Booking::model()->findByPk($bkgId);
			$isRestricted			 = BookingInvoice::validateDateRestriction($bookingmodel->bkg_pickup_date);
			if (!$isRestricted && $existdutySlipRequired == 1)
			{
				$data = ["success" => false, "msg" => "Sorry, you cannot perform this action now."];
				echo json_encode($data);
				Yii::app()->end();
			}
			$oldStatus										 = $bookingmodel->bkg_status;
			$bookingmodel->bkgPref->bkg_duty_slip_required	 = $existdutySlipRequired;
			if ($bookingmodel->bkgPref->bkg_duty_slip_required == 1)
			{
				$desc	 = "Duty Slip required for booking " . $bookingmodel->bkg_booking_id;
				$eventId = BookingLog::DUTYSLIP_REQUIRED;
			}
			else
			{
				$desc	 = "Duty Slip not required for booking " . $bookingmodel->bkg_booking_id;
				$eventId = BookingLog::DUTYSLIP_NOT_REQUIRED;
			}
			if ($bookingmodel->bkgPref->save())
			{
				BookingLog::model()->createLog($bkgId, $desc, UserInfo::getInstance(), $eventId, false, false);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$url	 = Yii::app()->createAbsoluteUrl("admin/booking/list", ["tab" => 2]);
				$data	 = ['success' => true, 'status' => $oldStatus, 'url' => $url];
				echo CJSON::encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('admin/booking/list', 'tab' => 2));
		}
	}

	public function actionAddpenalty()
	{
		$success			 = false;
		$error				 = '';
		$totalPenalty		 = 0;
		$otheramount		 = 0;
		$this->pageTitle	 = "Add Penalty To Vendor";
		$PenaltyReason		 = Yii::app()->params['PenaltyReason'];
		$amountbyPenaltyID	 = Yii::app()->params['PenaltyAmount'];

		$bkid = Yii::app()->request->getParam('booking_id');
		if (isset($_POST['AccountTransactions']))
		{

			$arr			 = Yii::app()->request->getParam('AccountTransactions');
			$getMaxAmount	 = AccountTransactions::model()->getAmountByPenaltyId($arr['act_amount'], $amountbyPenaltyID);
			$vndid			 = $arr['act_ref_id'];
			$remarks		 = ($arr['act_remarks'] != '') ? $arr['act_remarks'] . ', ' . $arr['additional_remarks'] : $arr['additional_remarks'];
			$penaltyRulesArr = explode(',', $arr['penalty_rule_reason']);
			foreach ($penaltyRulesArr as $rules)
			{
				$getPenaltyRules = PenaltyRules::model()->getValueByPenaltyType($rules);
				$arrRules[]		 = $getPenaltyRules['plt_desc'];
			}
			$penaltyRules = implode(',', $arrRules);
			if ($arr['penalty_rule_reason'] != '' || $arr['penalty_rule_reason'] != NULL)
			{
				$remarks = $remarks . '<br> Reason:' . $penaltyRules;
			}
			$act_type	 = Accounting::AT_OPERATOR;
			$ledger_type = Accounting::LI_OPERATOR;
			$otheramount = ($arr['penalty_amount'] > 0) ? $arr['penalty_amount'] : 0;
			$amount		 = $getMaxAmount + $otheramount;
			if ($arr['act_ref_id'] != '' && ($getMaxAmount > 0 || ($arr['penalty_other_reason'][0] == 1 && $arr['penalty_amount'] > 0 && $arr['additional_remarks'] != '')))
			{

				$addVendorPenalty	 = AccountTransactions::model()->addVendorPenalty($bkid, $vndid, $amount, $remarks, $arr['act_amount'], $act_type, $ledger_type);
				$error				 = 'Success! Panelty has been added';
				$return				 = ['success' => TRUE, 'error' => $error];
				echo CJSON::encode($return);
			}
			else
			{
				$error	 = 'error! Panelty reason and amount is required.';
				$return	 = ['success' => FALSE, 'error' => $error];
				echo CJSON::encode($return);
			}
			Yii::app()->end();
		}
		$vendorList					 = BookingCab::model()->getAsssignVendorList($bkid);
		$acctransModel				 = new AccountTransactions();
		$acctransModel->act_ref_id	 = $vendorList[0]['blg_vendor_assigned_id'];
		$arrVendor					 = array();

		foreach ($vendorList as $vndmodel)
		{
			$arrVendor[] = array("id"	 => $vndmodel['blg_vendor_assigned_id'],
				"text"	 => $vndmodel['vnd_name']);
		}
		$data		 = CJSON::encode($arrVendor);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addpenalty', array('model' => $acctransModel, 'vendorList' => $vendorList, 'vendorJSON' => $data, 'bkgid' => $bkid, 'PenaltyReason' => $PenaltyReason, 'error' => $error), false, $outputJs);
	}

	public function actionApproveDutySlip($viewds = '')
	{

		$bkgid		 = Yii::app()->request->getParam('booking_id');
		$recordset	 = BookingPayDocs::model()->getDutySlipBybookingId($bkgid);
		#$boostDocs	 = VehicleStats::model()->getBoostDocs($bkgid);
		$boostDocs	 = BookingPayDocs::model()->getVerifyImages($bkgid);
		/* if(empty($boostDocs))
		  {
		  $boostDocs	 = VehicleStats::model()->getBoostDocs($bkgid);

		  } */
		#print_r($boostDocs);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('attachments', array('recordset' => $recordset, 'boostDocs' => $boostDocs, 'bkgID' => $bkgid, 'viewds' => $viewds), false, $outputJs);
		//$this->$method('approvedutyslip', array(recordset => $recordset, data => $data, 'bkgID' => $bkgid, 'viewds' => $viewds), false, $outputJs);
	}

	public function actionApproveDoc()
	{
		$userinfo		 = UserInfo::getInstance();
		$bpay_id		 = Yii::app()->request->getParam('bpay_id');
		$bpay_approved	 = Yii::app()->request->getParam('bpay_status');
		$bpayModel		 = BookingPayDocs::model()->findByPk($bpay_id);
		if ($bpayModel->validate())
		{
			$bpayModel->bpay_approved		 = $bpay_approved;
			$bpayModel->bpay_approved_by	 = $userinfo->userId;
			$bpayModel->bpay_approved_date	 = new CDbExpression('NOW()');
			if ($bpayModel->save())
			{
				if ($bpay_approved == 1)
				{
					$eventId = BookingLog::VOUCHER_APPROVED;
				}
				else
				{
					$eventId = BookingLog::VOUCHER_REJECTED;
				}
				$bkg_id			 = $bpayModel->bpay_bkg_id;
				$voucherTypeName = $bpayModel->getTypeByVoucherId($bpayModel->bpay_type);
				$desc			 = "Voucher ID : " . $bpay_id . ", " . "Voucher Type : " . $voucherTypeName;
				$success		 = BookingLog::model()->createLog($bkg_id, $desc, $userinfo, $eventId, false, false);
				$return			 = ['approve_status' => $bpay_approved, 'bpay_id' => $bpay_id];
				echo CJSON::encode($return);
			}
		}
	}

	public function actionSetdutyReceived()
	{
		$bkid		 = Yii::app()->request->getParam('bkid');
		$dutyReq	 = Yii::app()->request->getParam('dutyReq');
		$updateRow	 = BookingPref::model()->updateDutyRequireStatus($dutyReq, $bkid);
	}

	public function actionSmartMatchList()
	{
		$this->pageTitle		 = "Match Booking";
		$model					 = new BookingSmartmatch('search');
		$model->bcbTypeMatched	 = [0];

		if (isset($_REQUEST['BookingSmartmatch']))
		{
			$arr						 = Yii::app()->request->getParam('BookingSmartmatch');
			$model->up_bkg_booking_id	 = $arr['up_bkg_booking_id'];
			$model->down_bkg_booking_id	 = $arr['down_bkg_booking_id'];
			$model->matchedTripId		 = $arr['matchedTripId'];
			$model->bcbTypeMatched		 = Yii::app()->request->getParam('BookingSmartmatch')['bcbTypeMatched'];
			$bcbTypeMatched				 = implode(',', $model->bcbTypeMatched);
		}
		$dataProvider = $model->getToBeSmartMatchList($bcbTypeMatched == null ? 0 : $bcbTypeMatched, $model->up_bkg_booking_id, $model->down_bkg_booking_id, $model->matchedTripId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$this->render('smartMatchList', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionSendVendorDriver()
	{
		$bkgid		 = Yii::app()->request->getParam('bkg_id');
		$description = Yii::app()->request->getParam('description');
		$userInfo	 = UserInfo::getInstance();
		if ($description && $description != '')
		{
			$model		 = Booking::model()->findByPk($bkgid);
			$oldData	 = Booking::model()->getDetailsbyId($model->bkg_id);
			$bcabModel	 = $model->getBookingCabModel();
			$separator	 = "";
			if ($model->bkg_instruction_to_driver_vendor)
			{
				$separator = " | ";
			}
			$model->bkg_instruction_to_driver_vendor = ($model->bkg_instruction_to_driver_vendor . $separator . $description);
			if ($model->save())
			{
				$newData			 = Booking::model()->getDetailsbyId($model->bkg_id);
				$getDifference		 = array_diff_assoc($newData, $oldData); // $this->getDifference($oldData, $newData);
				$getOldDifference	 = array_diff_assoc($oldData, $newData);
				$changesForLog		 = " Old Values: " . $model->getModificationMSG($getOldDifference, 'log');
				$logDesc			 = "Booking modified | ";
				$desc				 = $logDesc . $changesForLog;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::BOOKING_MODIFIED, $oldModel, $params);

				//$msgCom	 = new smsWrapper();
				//$changes = $model->bkg_instruction_to_driver_vendor;
				//$msgCom->sentFeedbackSmsDriver('91', $bcabModel->bcb_driver_phone, $model->bkg_booking_id, $changes);
				//$msgCom->sentFeedbackSmsVendor('91', $bcabModel->bcbVendor->vnd_phone, $model->bkg_booking_id, $changes);
				// if (Yii::app()->request->isAjaxRequest) {
				$data = ['success' => true, 'oldStatus' => $model->bkg_status];
				echo json_encode($data);
				//  Yii::app()->end();
				// }
			}
		}
	}

	public function actionOneminutelog()
	{
		$bkgid		 = Yii::app()->request->getParam('booking_id');
		$second		 = Yii::app()->request->getParam('second');
		$IsexistID	 = Yii::app()->request->getParam('bkglogID');
		$minutes	 = round($second / 60);
		$userInfo	 = UserInfo::getInstance();
		$userModel	 = Admins::model()->findByPk($userInfo->userId);
		$userName	 = $userModel->adm_user;
		$fname		 = $userModel->adm_fname;
		$lname		 = $userModel->adm_lname;
		$name		 = $fname . ' ' . $lname;
		$desc		 = "Booking opened by [" . $name . "] for " . $minutes . " mins. No remarks entered.";
		//$IsexistID   = BookingLog::model()->isExistViewStatus($bkgid,$userInfo, BookingLog::BOOKING_VIEWED_1MIN);
		if ($IsexistID == 0 || $IsexistID == "")
		{
			$model		 = new BookingLog();
			$bkglogID	 = $model->updateViewStatus($model, $bkgid, $desc, $userInfo, BookingLog::BOOKING_VIEWED_1MIN);
		}
		else
		{
			$model		 = BookingLog::model()->findByPk($IsexistID);
			$bkglogID	 = $model->updateViewStatus($model, $bkgid, $desc, $userInfo, BookingLog::BOOKING_VIEWED_1MIN);
		}
		$data = ['bkgLogID' => $bkglogID];
		if ($bkglogID)
		{
			$data ['status'] = "You are in booking dashboard.Want to update anything?";
		}
		echo json_encode($data);
		Yii::app()->end();
	}

	public function actionDeloneminutelog()
	{
		$bkgid		 = Yii::app()->request->getParam('booking_id');
		$IsexistID	 = Yii::app()->request->getParam('logID', 0);
		if ($IsexistID > 0)
		{
			$model				 = BookingLog::model()->findByPk($IsexistID);
			$model->blg_active	 = 2;
			$model->save();
		}
	}

	public function actionModifiedPaymentStatus()
	{
		$userInfo						 = UserInfo::getInstance();
		$bcbid							 = Yii::app()->request->getParam('bcb_id');
		$bkgid							 = Yii::app()->request->getParam('booking_id');
		$statusType						 = Yii::app()->request->getParam('status_type');
		$model							 = BookingCab::model()->findByPk($bcbid);
		$model->bcb_lock_vendor_payment	 = $statusType;
		if ($model->save())
		{
			if ($statusType == 2)
			{
				$desc	 = "Payment Release (Manual) Booking Id - " . $bkgid;
				$eventid = BookingLog::RELEASED_PAYMENT;
			}
			if ($statusType == 1)
			{
				$desc	 = "Payment Locked (Manual) Booking Id - " . $bkgid;
				$eventid = BookingLog::LOCKED_PAYMENT;
			}
			$params['blg_ref_id'] = $bkgid;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, false, $params);
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'status' => $statusType];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionAutoAssignUnverifiedFollowup()
	{
		$csr = Yii::app()->user->getId();
		$row = BookingTrail::model()->getTopFollowup($csr);
		if ($row['csrRank'] <= 20)
		{
			/* @var $model Booking */
			$model							 = Booking::model()->findByPk($row['bkg_id']);
			$model->bkgTrail->bkg_assign_csr = $csr;
			$model->bkgTrail->save();

			$admin	 = Admins::model()->findByPk($csr);
			$aname	 = $admin->adm_fname;
			//$bkgid = $success;
			$desc	 = "CSR ($aname) Auto Assigned";
			BookingLog::model()->createLog($row['bkg_id'], $desc, UserInfo::model(), BookingLog::CSR_ASSIGN, false, false);
		}

		$url = Yii::app()->createUrl("admin/booking/list", ["searchid" => $row['bkg_id']]);
		echo json_encode(["url" => $url]);
		Yii::app()->end();
	}

	public function actionSkipCsrAllocation()
	{
		$bkg_id		 = Yii::app()->request->getParam('booking_id');
		$model		 = Booking::model()->findByPk($bkg_id);
		$userInfo	 = UserInfo::getInstance();
		$prefModel	 = $model->bkgPref;
		$csrID		 = $prefModel->bpr_assignment_id;
		$calWH		 = Filter::CalcWorkingHour(new CDbExpression('NOW()'), $model->bkg_pickup_date);
		if ($prefModel->bpr_assignment_level > 0 && $csrID == $userInfo->getUserId() && $calWH > 14)
		{
			$prefModel->bpr_assignment_level	 = 0;
			$prefModel->bpr_assignment_id		 = 0;
			$prefModel->bpr_skip_csr_assignment	 = new CDbExpression('DATE_ADD(NOW(),INTERVAL 4 HOUR)');
			if ($prefModel->bpr_askmanual_assignment == 1)
			{
				$prefModel->bpr_askmanual_assignment = 0;
			}
			if ($prefModel->save())
			{
				$desc = "Assignment snoozed ";
				BookingLog::model()->createLog($bkg_id, $desc, UserInfo::getInstance(), BookingLog::SNOOZE_ASSINGMENT, false, false);
			}
			echo 'CSR Allocation on hold for 4 hrs ';
		}
	}

	public function actionChangeCPComm()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');

		if (isset($_REQUEST['BookingInvoice']))
		{
			$bivArr	 = $_REQUEST['BookingInvoice'];
			$bkgarr	 = $_REQUEST['Booking'];
			$bkg_id	 = $bkgarr['bkg_id'];
			$success = BookingInvoice::model()->changeCPCommission($bivArr, $bkg_id);

			echo json_encode(['success' => $success]);
			Yii::app()->end();
		}


		if ($bkgid > 0)
		{
			$model = Booking::model()->findByPk($bkgid);
			if ($model->bkg_agent_id > 0 && $model->bkg_agent_id != Yii::app()->params[''])
			{
				
			}
			else
			{
				echo 'This is not a B2B Booking';
				exit;
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('edit_partner_comm', array('model' => $model, 'bivmodel' => $model->bkgInvoice), false, $outputJs);
	}

	public function actionGetcommission()
	{
		$bkgid		 = Yii::app()->request->getParam('bkg_id');
		$comm_type	 = Yii::app()->request->getParam('comm_type');
		$comm_val	 = Yii::app()->request->getParam('comm_val');
		$commission	 = BookingInvoice::model()->getCPCommission($comm_type, $comm_val, $bkgid);
		$return		 = ['commission' => $commission];
		echo CJSON::encode($return);
	}

	public function actionAddnmi()
	{
		$bkid		 = Yii::app()->request->getParam('bkg_id');
		$trailArr	 = Yii::app()->request->getParam('BookingTrail');
		/** @var Booking $bkModel */
		$bkModel	 = Booking::model()->findByPk($bkid);

		if ($bkModel->bkgPref->bkg_block_autoassignment == 1)
		{
			goto addNMI;
		}
		$retrunSet = BookingVendorRequest::autoVendorAssignments($bkModel->bkg_bcb_id);
		if (BookingCab::setMaxOut($bkModel->bkg_bcb_id, 1) && $bkModel->bkgPref->bkg_manual_assignment == 1)
		{
			$userInfo	 = UserInfo::getInstance();
			$desc		 = "Auto activated MaxOut trigger upon manual assignment";
			$eventid	 = BookingLog::MANUAL_ASSIGNMENT_TRIGGERED;
			BookingLog::model()->createLog($bkModel->bkg_id, $desc, $userInfo, $eventid, false);
		}
		if ($retrunSet->getStatus())
		{
			echo CJSON::encode(array('success' => false, 'error' => "Vendor is already assigned to this booking. Manual assignment not possible."));
			Yii::app()->end();
		}

		addNMI:
		BookingTrail::model()->addNMIreason($bkid, $trailArr);
	}

	public function actionGetEscalationDesc()
	{
		$id	 = Yii::app()->request->getParam('lbl_id');
		$arr = BookingTrail::model()->escalation;
		echo '<b>' . $arr[$id]['levelDesc'] . '</b>';
	}

	public function actionChangeRefundApprovalStatus()
	{
		if (isset($_REQUEST))
		{
			$bkgId													 = $_REQUEST['booking_id'];
			$refApprovalStatus										 = $_REQUEST['dsval'];
			$bookingmodel											 = Booking::model()->findByPk($bkgId);
			$oldStatus												 = $bookingmodel->bkg_status;
			$bookingmodel->bkgInvoice->biv_refund_approval_status	 = $refApprovalStatus;
			if ($bookingmodel->bkgInvoice->biv_refund_approval_status == 2)
			{
				$desc	 = "Refund for Booking: " . $bookingmodel->bkg_booking_id . " has been disapproved";
				$eventId = BookingLog::REFUND_DISAPPROVED;
			}
			else if ($bookingmodel->bkgInvoice->biv_refund_approval_status == 3)
			{
				$desc	 = "Refund for Booking: " . $bookingmodel->bkg_booking_id . " has been approved";
				$eventId = BookingLog::REFUND_APPROVED;
			}
			if ($bookingmodel->bkgInvoice->save())
			{
				BookingLog::model()->createLog($bkgId, $desc, UserInfo::getInstance(), $eventId, false, false);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$url	 = Yii::app()->createAbsoluteUrl("admin/booking/list", ["tab" => 2]);
				$data	 = ['success' => true, 'status' => $oldStatus, 'url' => $url];
				echo CJSON::encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('admin/booking/list', 'tab' => 2));
		}
	}

	public function actionCheckFollowupTiming()
	{
		$pickupDateDate		 = Yii::app()->request->getParam('pickupDate');
		$pickupDateTime		 = Yii::app()->request->getParam('pickupTime');
		$followupDateDate	 = Yii::app()->request->getParam('followupDate');
		$followupDateTime	 = Yii::app()->request->getParam('followupTime');

		$rtVal			 = true;
		$error			 = '';
		$pickupDateVal	 = DateTimeFormat::DatePickerToDate($pickupDateDate);
		$pickupTimeVal	 = DateTime::createFromFormat('h:i A', $pickupDateTime)->format('H:i:00');
		$pickupDate		 = $pickupDateVal . ' ' . $pickupTimeVal;

		$followupDateVal = DateTimeFormat::DatePickerToDate($followupDateDate);
		$followupTimeVal = DateTime::createFromFormat('h:i A', $followupDateTime)->format('H:i:00');
		$followupDate	 = $followupDateVal . ' ' . $followupTimeVal;

		$now = Filter::getDBDateTime();

		$fullDur		 = round((strtotime($pickupDate) - strtotime($now) ) / 60);
		$allowedseconds	 = min([round($fullDur * 0.15 * 60), 2 * 3600]);
		$followupDur	 = round((strtotime($followupDate) - strtotime($now) ) / 60);
		$maxFollowupTime = date('d/m/Y H:i', strtotime("+$allowedseconds seconds ", strtotime($now)));
		//$success		 = (($followupDur / $fullDur) <= 0.16);
		if ((strtotime($now) > strtotime($followupDate) ) || (($followupDur * 60 ) > $allowedseconds))
		{
			$rtVal	 = false;
			$error	 = 'Follow up timing should be less than ' . $maxFollowupTime . ' and more than current time';
		}
		echo json_encode(['success' => $rtVal, 'error' => $error]);
	}

	public function actionEditPickupTime()
	{
		$bkgid		 = Yii::app()->request->getParam('bkg_id');
		$model		 = Booking::model()->findByPk($bkgid);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('editpickuptime', array('model' => $model), false, $outputJs);
	}

	public function actionReschedule()
	{
		//$this->checkV3Theme();
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$newPickupDate	 = Yii::app()->request->getParam('newPickupDate');
		$newPickupTime	 = Yii::app()->request->getParam('newPickupTime');
		$isCommit		 = Yii::app()->request->getParam('isCommit') | 0;
		$prevModel		 = Booking::model()->findByPk($bkgid);
		$brtRoute		 = new BookingRoute();
		if ($newPickupDate != '' && $newPickupTime != '' && $isCommit == 1)
		{
			$result = $prevModel->confirmReschedule($newPickupDate, $newPickupTime, true);
			echo json_encode($result);
			Yii::app()->end();
		}
		if ($newPickupDate != '' && $newPickupTime != '')
		{
			$result			 = $prevModel->reschedule($newPickupDate, $newPickupTime);
			$bkgPrefModel	 = BookingPref::model()->findBySql("SELECT 1 FROM booking_pref WHERE bpr_rescheduled_from = {$prevModel->bkg_id}");
			if ($bkgPrefModel != '')
			{
				$result = false;
				$prevModel->addError('bkg_id', "Reschedule process already initiated");
			}
			if (!$result)
			{
				echo json_encode(['success' => false, 'errors' => $prevModel->getErrors()]);
				Yii::app()->end();
			}
			if ($result instanceof Booking)
			{
				$newModel	 = $result;
				$outputJs	 = Yii::app()->request->isAjaxRequest;
				$this->renderPartial('rescheduleReview1', array('extraPay' => $newModel->minPayExtra, 'minPayNew' => $newModel->minPay, 'newModel' => $newModel, 'prevModel' => $prevModel), false, $outputJs);
				Yii::app()->end();
			}
			else
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('editpickuptime1', array('model' => $prevModel, 'brtRoute' => $brtRoute), false, $outputJs);
	}

	public function actionSavepickuptime()
	{
		$isPrePostTime	 = Yii::app()->request->getParam('timePrePost');
		$timeSchedule	 = Yii::app()->request->getParam('timeSchedule');
		$bkgId			 = Yii::app()->request->getParam('bkg_id');

		if (isset($isPrePostTime) && isset($timeSchedule) && isset($bkgId))
		{
			$model				 = Booking::model()->findByPk($bkgId);
			$return				 = [];
			$return['success']	 = false;

			$success = $model->saveRescheduleTime($bkgId, $isPrePostTime, $timeSchedule);
			if ($success == true)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'Pickup Time modified successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];

					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				$result = [];
				foreach ($model->getErrors() as $attribute => $errors)
				{
					$result[CHtml::activeId($model, $attribute)] = $errors[0];
				}
				foreach ($model->bkgUserInfo->getErrors() as $attribute => $errors)
				{
					$result[CHtml::activeId($model->bkgUserInfo, $attribute)] = $errors;
				}
				$data = ['success' => $success, 'message' => $result['Booking_bkg_id']];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionAutoMarkerAddress()
	{
		$airport		 = Yii::app()->request->getParam('airport');
		$ctyLat			 = Yii::app()->request->getParam('ctyLat');
		$ctyLon			 = Yii::app()->request->getParam('ctyLon');
		$bound			 = Yii::app()->request->getParam('bound');
		$isCtyAirport	 = Yii::app()->request->getParam('isCtyAirport');
		$isCtyPoi		 = Yii::app()->request->getParam('isCtyPoi');
		$locKey			 = Yii::app()->request->getParam('locKey');
		$location		 = Yii::app()->request->getParam('loc');
		$this->renderPartial("bkMapLocation", ["ctyLat" => $ctyLat, 'ctyLon' => $ctyLon, 'bound' => $bound, 'isCtyAirport' => $isCtyAirport, 'isCtyPoi' => $isCtyPoi, 'locKey' => $locKey, 'location' => $location, 'airport' => $airport], false, true);
	}

	public function GetVehicleCache()
	{
		//Yii::app()->cache->flush();
		$datavehicle = Yii::app()->cache->get("cabTypeList");
		if (!$datavehicle)
		{
			$datavehicle = SvcClassVhcCat::getCabTypeList();
			Yii::app()->cache->set("cabTypeList", $datavehicle, 86400);
		}
		return $datavehicle;
	}

	public function actionGetPaymentStatus()
	{
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		$payData = PaymentGateway::fetchDetailsbyBkg($bkgId);
		$this->renderPartial("payment_status", ['payData' => $payData], false, true);
	}

	public function actionShowPaymentStatus()
	{
		$apgid		 = Yii::app()->request->getParam('apgid');
		$payResponse = PaymentGateway::getStatusById($apgid);
		$success	 = false;
		$status		 = 0;
		$message	 = 'Failed';
		$response	 = '';
		if ($payResponse)
		{
			$success	 = true;
			$status		 = $payResponse->payment_status;
			$message	 = $payResponse->message;
			$response	 = '(' . $payResponse->response . ')';
		}
		$data = [
			'apgid'		 => $apgid,
			'success'	 => $success,
			'status'	 => $status,
			'message'	 => $message,
			'response'	 => $response
		];
		echo json_encode($data);
		Yii::app()->end();
	}

	public function actionDuplicateBooking()
	{
		$bkg_id	 = Yii::app()->request->getParam('bkg_id');
		$view	 = Yii::app()->request->getParam('view', 0);
		$bkgIds	 = [];
		$urls	 = [];
		if ($bkg_id > 0)
		{
			$model = Booking::model()->findByPk($bkg_id);
		}
		if ($view == 0)
		{
			$this->renderPartial("bkDuplicateBooking", ['bkg_id' => $model->bkg_id, 'booking_id' => $model->bkg_booking_id, 'bkg_agent_id' => $model->bkg_agent_id, 'bkg_total_amount' => $model->bkgInvoice->bkg_total_amount, 'vendor_id' => $model->bkgBcb->bcb_vendor_id], false, true);
		}
		else
		{
			$copy			 = Yii::app()->request->getParam('copy', 0);
			$assignVendor	 = Yii::app()->request->getParam('assignVendor', 0);
			$applySurge		 = Yii::app()->request->getParam('applySurge', 1);
			$copyPayment	 = Yii::app()->request->getParam('copyPayment', 0);
			$advancedAmount	 = Yii::app()->request->getParam('advancedAmount', 0);
			$errors			 = [];
			if ($copy > 0)
			{
				$GLOBALS['ddbpBkgCount'] = 0;

				for ($i = 0; $i < $copy; $i++)
				{
					$transaction = Filter::beginTransaction();
					try
					{
						$bkgModel = Booking::getNewInstance();
						if ($model)
						{
							##################################TO CHECK PARTNER CREDIT LIMIT ###############################
//			    if ($advancedAmount > 0 && $model->bkg_agent_id > 0)
//			    {
//				$isRechargeAccount = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $advancedAmount, '', 3, false);
//				if ($isRechargeAccount)
//				{
//				    $noOfBookingFailed = $copy - count($bkgIds);
//				    throw new Exception("(" . $noOfBookingFailed . ")-Booking failed as your credit limit exceeded, please recharge.");
//				}
//			    }
							###############################################################################################	
							$bkgModel->copyAttributes($model);
							$bkgModel->loadDefaults();
							$bkgModel->bookingRoutes					 = BookingRoute::model()->copyRoutes($model);
							$bkgModel->loadInvoice($model, $applySurge);
							$bkgModel->bkgInvoice->bkg_advance_amount	 = ($advancedAmount > 0 && $bkgModel->bkg_agent_id > 0) ? $advancedAmount : 0;
							$bkgModel->bkgInvoice->bkg_corporate_credit	 = ($advancedAmount > 0 && $bkgModel->bkg_agent_id > 0) ? $advancedAmount : 0;
							$bkgModel->createQuote();
							if ($bkgModel->bkg_id > 0)
							{
								if (in_array($model->bkg_status, [2, 3, 5]))
								{
									$bkgModel->bookingConfirmation();
									if ($assignVendor == 1 && $model->bkgBcb->bcb_vendor_id > 0)
									{
										$bkgModel->bkgBcb->assignVendor($bkgModel->bkgBcb->bcb_id, $model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_vendor_amount, '', UserInfo::getInstance());
									}
								}
								$eventid = BookingLog::BOOKING_CREATED;
								BookingLog::model()->createLog($bkgModel->bkg_id, 'Booking created as copy of ' . $model->bkg_booking_id, UserInfo::getInstance(), $eventid);
								########################## partner credits applied########################################
								if ($advancedAmount > 0 && $bkgModel->bkg_agent_id > 0)
								{
									$ptpid		 = PaymentType::TYPE_AGENT_CORP_CREDIT;
									$remarks	 = "Partner Wallet credits added to booking (by admin)";
									// $actModel		 = AccountTransactions::model()->advanceReceived($bkgModel->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, $bkgModel->bkg_agent_id, $advancedAmount, Accounting::AT_BOOKING, $bkgModel->bkg_id, $remarks);
									$actModel	 = AccountTransactions::usePartnerWallet($bkgModel->bkg_pickup_date, $advancedAmount, $bkgModel->bkg_id, $bkgModel->bkg_agent_id, $remarks, UserInfo::getInstance());
									#if ($ptpid == PaymentType::TYPE_AGENT_CORP_CREDIT && $bkgModel->bkg_agent_id > 0)
									#{
									//AccountTransactions::model()->PartnerCoinsUsed($bkgModel->bkg_agent_id, $advancedAmount, $bkgModel->bkg_pickup_date, $bkgModel->bkg_id, Accounting::AT_BOOKING, $remarks, UserInfo::getInstance());
//				    $params			 = [];
//				    $params['blg_ref_id']	 = $actModel->act_id;
//				    $remarks		 = "Partner Wallet used - Payment Added";
//				    $ptpString		 = PaymentGateway::model()->getPayment($ptpId);
//				    $desc			 = " $remarks ({$ptpString} - {$bkgModel->bkg_booking_id})";
//				    $eventid		 = BookingLog::PAYMENT_COMPLETED;
//				    BookingLog::model()->createLog($bkgModel->bkg_id, $desc, UserInfo::getInstance(), $eventid, '', $params);
									#}
								}
								#################################################################################################
								$bkgIds[$i]				 = $bkgModel->bkg_booking_id;
								$urls[$i]				 = Yii::app()->createUrl('admin/booking/view', ['id' => $bkgModel->bkg_id]);
								$GLOBALS['ddbpBkgCount'] += 1;
							}
						}
						Filter::commitTransaction($transaction);
					}
					catch (Exception $e)
					{
						$errors = $e->getMessage();
						Filter::rollbackTransaction($transaction);
					}
				}
			}
			$this->renderPartial("bkDuplicateBookingList", ['bkgIds' => $bkgIds, 'urls' => $urls, 'errors' => $errors], false, true);
		}
	}

	public function actionManuallyTriggerAssignment()
	{
		$booking_id	 = Yii::app()->request->getParam('booking_id');
		$model		 = Booking::model()->findByPk($booking_id);
		$return		 = ['success' => false, 'message' => 'Some error occured'];

		#$UserInfo = UserInfo::getInstance();

		if ($model->bkgPref->bkg_critical_score >= 0.72 && ($model->bkgPref->bkg_manual_assignment == 1 || Yii::app()->user->checkAccess('MaxOutBooking') || $model->bkgTrail->bkg_assign_csr == UserInfo::getUserId() || $model->bkgPref->bpr_assignment_id == UserInfo::getUserId()))
		{
			if (BookingCab::setMaxOut($model->bkg_bcb_id, 1))
			{
				$userInfo	 = UserInfo::getInstance();
				$desc		 = "Manually triggered assignment system";
				$eventid	 = BookingLog::MANUAL_ASSIGNMENT_TRIGGERED;
				BookingLog::model()->createLog($booking_id, $desc, $userInfo, $eventid, false);
				$return		 = ['success' => true, 'message' => 'MaxOut assignment is manually triggered'];
			}
		}

		echo json_encode($return);
		Yii::app()->end();
	}

	public function actionTrack()
	{

		$this->pageTitle = "Booking Progress Tracker";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$booking_id		 = trim(Yii::app()->request->getParam('booking_id'));
		$bkgLogArr		 = ['130'	 => 'Quoted',
			'137'	 => 'Confirmed',
			'7'		 => 'Assigned',
			'46'	 => 'Cab Assign',
			'44'	 => 'Driver Assign'];
		$bkgTrackLogArr	 = ['101'	 => 'Start', '102'	 => 'Pause',
			'103'	 => 'Resume', '104'	 => 'Stop',
			'201'	 => 'Going For Pickup', '202'	 => 'Not Going For Pickup', '203'	 => 'Arrived',
			'204'	 => 'NoShow', '205'	 => 'Wait', '206'	 => 'NoShow Reset', '301'	 => 'SOS Start',
			'302'	 => 'SOS Resolved'];

		$bookingModel		 = Booking::model()->findByPk($booking_id);
		$bkgTrackLogModel	 = BookingTrackLog::model()->getInfoByEvent($booking_id);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('track', array('trackLogmodel'	 => $bkgTrackLogModel,
			'event'			 => $bkgLogArr,
			'trackevent'	 => $bkgTrackLogArr,
			'bkgId'			 => $booking_id,
			'bookingModel'	 => $bookingModel), false, $outputJs);
	}

	public function actionCurrentlyAssignedDetails()
	{
		$bookingID	 = Yii::app()->request->getParam('bkg_id');
		$view		 = Yii::app()->request->getParam('currentlyAssignedDetails', 'currentlyAssignedDetails');
		$data_array	 = RatingAttributes::model()->getRatingAttributes(2);

		if ($bookingID != '')
		{
			$bookModel				 = Booking::model()->findByPk($bookingID);
			$bookData				 = BookingSub::getVendorCabDriverDetails($bookingID);
			$oldModel				 = clone $bookModel;
			$userInfo				 = UserInfo::getInstance();
			$desc					 = 'Booking Viewed';
			$params['blg_active']	 = 2;
			$eventId				 = BookingLog::BOOKING_VIEWED;
			BookingLog::model()->createLog($bookingID, $desc, $userInfo, $eventId, $oldModel, $params);
		}
		else
		{
			echo "Booking id is not provided";
			Yii::app()->end();
		}
		$models				 = Booking::model()->getBookingRelationalDetails($bookingID);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ($outputJs ? "Partial" : "");
		$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bookingID]);
		//$bkgPref			 = BookingPref::model()->getByBooking($bookingID);
		$bkgTrack			 = BookingTrack::model()->find('btk_bkg_id=:bkg_id', ['bkg_id' => $bookingID]);

		//$bkgDriverAppinfo		 = TripTracking::model()->getDirverAppInfo($bookingID);
		$bkgDriverAppinfo		 = BookingTrackLog::model()->getDirverAppInfo($bookingID);
		$isConfirmCash			 = BookingUser::isConfirmCashBooking($bookingID);
		$zones					 = ZoneCities::model()->findZoneByCity($bookModel->bkg_from_city_id);
		$issourcezone_needSupply = InventoryRequest::model()->checkInventoryByFromCity($bookModel->bkg_from_city_id);
		// Show Note section 
		$cntRut					 = count($bookingRouteModel);
		//print_r($bookingRouteModel);exit;
		foreach ($bookingRouteModel as $key => $bookingRoute)
		{
			$pickupCity[]	 = $bookingRoute->brt_from_city_id;
			$dropCity[]		 = $bookingRoute->brt_to_city_id;
			$pickup_date[]	 = $bookingRoute->brt_pickup_datetime;
			$temp_last_date	 = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
		}
		$pickup_date_time	 = $pickup_date[0];
		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
		$dateArr			 = array($pickup_date_time, $drop_date_time);
		#print_r($dateArr);exit;
		#print_r($locationArr);exit;
		$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr);
		// endof Show Note section 

		$this->renderPartial($view, array('model'				 => $models,
			'isConfirmCash'		 => $isConfirmCash,
			'userInfo'			 => $userInfo,
			'bookingRouteModel'	 => $bookingRouteModel,
			'bkgTrack'			 => $bkgTrack,
			'bkgDriverAppinfo'	 => $bkgDriverAppinfo,
			'isAjax'			 => $outputJs,
			'data_array'		 => $data_array,
			'bookData'			 => $bookData,
			'isSupply'			 => $issourcezone_needSupply,
			'note'				 => $noteArr,
				), false, $outputJs);
	}

	public function actionAdminrefund1()
	{
		$success	 = false;
		$bkgId		 = Yii::app()->request->getParam('bkg_id');
		$balanceArr	 = AccountTransDetails::getBalancebyBookingid($bkgId);
		$model		 = Booking::model()->findByPk($bkgId);
		$refundArr	 = $model->calculateRefundable();
		$refAmount	 = $refundArr['refund'];

		$postArr = Yii::app()->request->getParam('PaymentGateway');

		if (isset($postArr))
		{
			try
			{
				$refundAmount = $postArr['apg_amount'];

				$result = PaymentGateway::model()->refundByRefId($refundAmount, $bkgId, Accounting::AT_BOOKING);

				$success = $result['success'];
				if ($success)
				{
					$message = 'Refund of Rs.' . $result['refunded'] . ' successfully done';
				}
				else
				{
					$message = 'Refund failed';
				}
			}
			catch (Exception $e)
			{
				$success = false;
				$result	 = [];
				if ($model->hasErrors())
				{
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
					}
				}
				$message = "Error in Consumer No Show has been unset: ";
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $success, 'errors' => $result, 'message' => $message, 'url' => ''];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$paymentGateway					 = new PaymentGateway();
		$paymentGateway->apg_booking_id	 = $bkgId;
		$maxrefund						 = max([$balanceArr['balance'], 0]);
		$this->renderPartial('refund', array('model'		 => $paymentGateway,
			'maxrefund'	 => round($maxrefund), 'balanceArr' => $balanceArr, 'refundArr'	 => $refundArr), false, true);
	}

	public function actionAdminrefund()
	{

		$success = false;
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		if ($model->bkg_status == 9)
		{
			$balanceArr = AccountTransDetails::getBalancebyBookingid($bkgId);

			$refundArr						 = $model->calculateRefundable();
			$refundable						 = $refundArr['refund'];
			$dboRefund						 = $model->bkgTrail->getDBORefundable();
			$cancelChargeRefundSuggestion	 = (($refundArr['cancelCharge'] < 0) ? $refundArr['cancelCharge'] : 0);
			$refundArr['cancelCharge']		 = (($refundArr['cancelCharge'] > 0) ? $refundArr['cancelCharge'] : 0);
			$refundArr['refund']			 = $refundable + $dboRefund + $cancelChargeRefundSuggestion;
			$isDBOApplicable				 = $model->bkgTrail->btr_is_dbo_applicable;
			$refAmount						 = $refundArr['refund'];

			$maxrefundable = max([0, min([$refAmount + $refundArr['cancelCharge'], $balanceArr['balance']])]);
		}
		if ($model->bkg_status == 6)
		{
			$maxrefund		 = $model->bkgInvoice->bkg_advance_amount + $model->bkgInvoice->bkg_vendor_collected - $model->bkgInvoice->bkg_refund_amount - $model->bkgInvoice->bkg_total_amount;
			$maxrefundable	 = $maxrefund;
		}
		$postArr = Yii::app()->request->getParam('PaymentGateway');

		if (isset($postArr))
		{
			try
			{
				if ($postArr['apg_amount'] > $maxrefundable)
				{
					throw new Exception('Amount ecxceeding permissible. Data: ' . json_encode($refundArr) . ':: refund requested : ' . $postArr['apg_amount']);
				}
				$refundAmount	 = $postArr['apg_amount'];
				$userInfo		 = UserInfo::getInstance();
				//$userInfo		 = UserInfo::model(UserInfo:: TYPE_CONSUMER, $model->bkgUserInfo->bkg_user_id);
				$result			 = PaymentGateway::model()->refundByRefId($refundAmount, $bkgId, Accounting:: AT_BOOKING, $userInfo, $isDBOApplicable);

				$success = $result['success'];
				if ($success)
				{
					$message = 'Refund of Rs.' . $result['refunded'] . ' successfully done';
				}
				else
				{
					$message = 'Refund failed';
				}
			}
			catch (Exception $e)
			{
				Logger::error($e);
				$success = false;
				$result	 = [];
				if ($model->hasErrors())
				{
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
					}
				}
				$message = "Error in Consumer No Show has been unset: ";
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $success, 'errors' => $result, 'message' => $message, 'url' => ''];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$paymentGateway					 = new PaymentGateway();
		$paymentGateway->apg_booking_id	 = $bkgId;
		$refundArr['isDBOApplicable']	 = $isDBOApplicable;
		if ($model->bkg_status == 9)
		{
			$maxrefund	 = max([$balanceArr['balance'], 0]);
			$maxrefund	 = ($isDBOApplicable > 0) ? $refundArr['refund'] : $maxrefund;
		}
		$this->renderPartial('refundtowallet', array('model'		 => $paymentGateway,
			'maxrefund'	 => round($maxrefund), 'balanceArr' => $balanceArr, 'refundArr'	 => $refundArr), false, true);
	}

	public function actionRefundFromWallet()
	{
		$success		 = false;
		$showBankDetails = Yii::app()->request->getParam('showbankDetails', 0);
		$bkgId			 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model			 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$userId	 = $model->bkgUserInfo->bkg_user_id;
		$cttid	 = $model->bkgUserInfo->bkg_contact_id;

		$transArr	 = AccountTransDetails::getWalletTransactionByBooking($bkgId);
		$bankArr	 = AccountTransDetails::getBankTransactionByBooking($bkgId);
		$bankPaid	 = -1 * $bankArr['paidThroughBank'];

		if ($model->bkg_status == 9 || $model->bkg_status == 6)
		{
			$onlineRefundable = PaymentGateway::getTotalOnlinePaymentByBooking($bkgId, $model->bkg_status);
		}
		$expirePaymentMonthDuration	 = 4;
		$onlineExpiredBalance		 = PaymentGateway::getTotalOnlineExpiredBalanceByBooking($bkgId, $expirePaymentMonthDuration);
		if ($onlineExpiredBalance > 0)
		{
			$onlineRefundable['balance'] -= $onlineExpiredBalance;
		}
		$walletBalance						 = UserWallet::getBalance($userId);
		$amountRefundableFromWalletToUser	 = min([$walletBalance, ($transArr['refundedToWallet'] | 0), ($onlineRefundable['balance'] + $bankPaid)]);
		$amount								 = $amountRefundableFromWalletToUser;

		$model	 = Contact::model()->findbyPk($cttid);
		$bank	 = new \Stub\common\Bank();
		$data	 = $bank->setData($model);
		if ($showBankDetails == 1)
		{
			$view			 = 'bankdetails';
			$pagetitle		 = 'Please provide bank account details for customer';
			$this->pageTitle = $pagetitle;
			$this->forward('savecustbankdetails');
		}
		else
		{
			if ($amount > 0)
			{
				if (!Yii::app()->icici->api_live)
				{
//					$amount = 1;
				}
				$pagetitle	 = 'Transfer from Gozo wallet to your bank';
				$view		 = 'transferForm';
			}
			else
			{
				$pagetitle	 = 'Insufficient Balance';
				$str		 = 'You have no sufficient balance to transfer';
				if ($onlineExpiredBalance > 0)
				{
					$str .= "<br><span class='text-danger'>Balance expired as payment duration is more than {$expirePaymentMonthDuration} months</span>";
				}
//				if ($totPendingRefunds > 0)
//				{
//					$str .= ". Refunds in queue=$totPendingRefunds.";
//				}
				echo $str;
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array('model' => $data, 'amount' => $amount, 'onlineRefundable' => $onlineRefundable, 'walletBalance' => $walletBalance, 'onlineExpiredBalance' => $onlineExpiredBalance, 'bank' => $bank, 'pagetitle' => $pagetitle, 'bkgId' => $bkgId), false, $outputJs);
	}

	public function actionSavecustbankdetails()
	{
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$userId	 = $model->bkgUserInfo->bkg_user_id;
		$cttid	 = $model->bkgUserInfo->bkg_contact_id;
		$model	 = Contact::model()->findbyPk($cttid);
		$bank	 = new \Stub\common\Bank();
		$data	 = $bank->setData($model);
		$req	 = Yii::app()->request->getParam('Bank');
		if (isset($req))
		{
			foreach ($req as $k => $val)
			{
				$req[$k] = trim($val);
			}
			$data = CJSON::encode($req);

			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$obj		 = $jsonMapper->map($jsonObj, $bank);
			$model		 = $obj->getData($model);
			$success	 = $model->save();
			$return		 = [];
			if ($success)
			{
				$return['success']	 = true;
				$return['message']	 = "Bank detail updated";
			}
			else
			{
				$return['success']	 = false;
				$return['message']	 = "Bank detail not updated";
			}

			echo CJSON::encode($return);
			Yii::app()->end();
		}


		$view = 'bankdetails';

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array('model' => $data, 'bank' => $bank, 'pagetitle' => $pagetitle, 'bkgId' => $bkgId), false, $outputJs);
	}

	public function actionWalletrefund()
	{
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$userId	 = $model->bkgUserInfo->bkg_user_id;
		$cttid	 = $model->bkgUserInfo->bkg_contact_id;
		$model	 = Contact::model()->findbyPk($cttid);
		$bank	 = new \Stub\common\Bank();
		$data	 = $bank->setData($model);
		$req	 = Yii::app()->request->getParam('Pay');
		if (isset($req))
		{
			$amount			 = $req['AMOUNT'];
			$remarks		 = substr(trim($req['REMARKS']), 0, 35);
			$walletbalance	 = UserWallet::getBalance($userId);
			if ($amount > $walletbalance || $amount <= 0)
			{
				return false;
			}
			$uniqueId					 = round(microtime(true) * 1000) . '';
			$entityArr['entity_type']	 = 1;
			$entityArr['entity_id']		 = $userId;
			$userInfo					 = UserInfo::getInstance();
			$added						 = Yii::app()->icici->registerRequest($bank, $uniqueId, $amount, $entityArr, $remarks, $userInfo);
			$transaction				 = DBUtil::beginTransaction();
			if ($added)
			{
				try
				{
					$bankModel = OnlineBanking::model()->getByUniqueId($uniqueId);
					$bankModel->processPayment();

					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					Logger::exception($ex);
				}
			}
		}

		$this->redirect(array('/admin/booking/view', 'id' => $bkgId));
	}

	public function actionPgrefund()
	{
		//Logger::setCategory('trace.module.admin.controller.booking.Pgrefund');
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model || !$bkgId)
		{
			return false;
		}
		$userId	 = $model->bkgUserInfo->bkg_user_id;
		$cttid	 = $model->bkgUserInfo->bkg_contact_id;

		$req = Yii::app()->request->getParam('Pay');
		if (isset($req))
		{
			$amount = $req['AMOUNT'];

			$walletBalance		 = UserWallet::getBalance($userId);
			$onlineRefundable	 = PaymentGateway::getTotalOnlinePaymentByBooking($bkgId, $model->bkg_status);
			$amountRefundable	 = max([0, min([$walletBalance, $onlineRefundable['balance']], $amount)]);
			$amount				 = $amountRefundable;
			if (!$onlineRefundable || $amount > $walletBalance || $amount <= 0)
			{
				return false;
			}


			$entityArr['entity_type']	 = 1;
			$entityArr['entity_id']		 = $userId;

			$transaction = DBUtil::beginTransaction();
			$userInfo	 = UserInfo::getInstance();
			Logger::create("BookingControler::Pgrefund bkgId:$bkgId walletBalance:{$walletBalance} onlineRefundable:{$onlineRefundable['balance']} amountRefundable:{$amountRefundable} ", CLogger::LEVEL_INFO);
			PaymentGateway::refundToPGByBookingid($bkgId, $amount, $userInfo);

			DBUtil::commitTransaction($transaction);
//				catch (Exception $ex)
//				{
//					DBUtil::rollbackTransaction($transaction);
//					Logger::exception($ex);
//				}
//			}
		}

		$this->redirect(array('/admin/booking/view', 'id' => $bkgId));
	}

	public function actionGetDrvCurrentLocation()
	{
		$bkid				 = Yii::app()->request->getParam('booking_id');
		$drvId				 = Yii::app()->request->getParam('drvId');
		$bookingmodel		 = Booking::model()->findByPk($bkid);
		$driverForBooking	 = $bookingmodel->bkgBcb->bcb_driver_id;
		if ($drvId != '')
		{
			$driverForBooking = $drvId;
		}
		else
		{
			$driverForBooking = $bookingmodel->bkgBcb->bcb_driver_id;
		}
		$drvStat = DriverStats::model()->getLastLocation($driverForBooking);
		if (Yii::app()->request->isAjaxRequest)
		{
			$latlong = $drvStat['drv_last_loc_lat'] . "," . $drvStat['drv_last_loc_long'];
			$url	 = 'https://maps.google.com/?q=' . $latlong;
			$data	 = ['success' => true, 'lastTime' => $drvStat['drv_last_loc_date'], 'lastLocation' => $drvStat['drv_last_loc_lat'] . "," . $drvStat['drv_last_loc_long'], 'destUrl' => $url];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

	public function actionExpireQuote()
	{
		$bkgId		 = Yii::app()->request->getParam('booking_id');
		$model		 = Booking::model()->findByPk($bkgId);
		$userInfo	 = UserInfo::getInstance();
		$logModel	 = new BookingLog();
		if (isset($_REQUEST['BookingLog']))
		{
			$logModel->attributes	 = Yii::app()->request->getParam('BookingLog');
			$arr					 = $logModel->attributes;
			$reason					 = trim($arr['blg_desc']);
			$success				 = Booking::expireQuote($model);
			if ($success)
			{
				$desc = 'Quote has been exired manually . Reason: ' . $reason;
				BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: QUOTE_EXPIRED, false, false);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $model->bkg_status];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('expireQuoteRemarks', array('bkid' => $bkgId, 'bookModel' => $model, 'logModel' => $logModel), false, true);
	}

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

		$reqData = ['fwp_desc' => $desc, 'fwp_ref_id' => $bookingModel->bkg_booking_id, 'fwp_ref_type' => 2, 'fwp_contact_phone_no' => $bookingModel->bkgUserInfo->bkg_contact_no, 'fwp_prefered_time' => $followupDate, 'fwp_parent_id' => $parent];
		if ($followWith == 1)
		{
			
		}
		else if ($followWith == 2)
		{
			$vndId		 = $bookingModel->bkgBcb->bcb_vendor_id;
			$entityId	 = $vndId; //Vendors::model()->findByPk($vndId)->vnd_contact_id;
			$entityType	 = UserInfo::TYPE_VENDOR;
		}
		else if ($followWith == 3)
		{
			$drvId		 = $bookingModel->bkgBcb->bcb_driver_id;
			$entityId	 = $drvId; //Drivers::model()->findByPk($drvId)->drv_contact_id;
			$entityType	 = UserInfo::TYPE_DRIVER;
		}
		else if ($followWith == 5)
		{
			$agentId	 = $bookingModel->bkg_agent_id;
			$entityId	 = $agentId; //Agents::model()->findByPk($agentId)->agt_contact_id;
			$entityType	 = UserInfo::TYPE_AGENT;
		}

		$follwup	 = ServiceCallQueue::storeCMBData($reqData, $entityId, $entityType, $platform	 = ServiceCallQueue:: PLATFORM_ADMIN_CALL, $team);

		$list							 = ServiceCallQueue::getfollowUpsByBkg($booking_id);
		$dt								 = $follwup->getData();
		$params["blg_booking_status"]	 = $bookingModel->bkg_status;
		$params['blg_ref_id']			 = $dt['followupId'];
		BookingLog::model()->createLog($booking_id, $desc, UserInfo::getInstance(), BookingLog:: FOLLOWUP_CREATE, false, $params);
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

	public function actionDisplay()
	{
		$getbookingID	 = Yii::app()->request->getParam('id');
		$bookingID		 = Booking::getBookingId($getbookingID);
		$model			 = Booking::model()->findByPk($bookingID);

		$userID	 = $model->bkgUserInfo->bkg_user_id;
		$drvId	 = $model->bkgBcb->bcb_driver_id;
		$vndId	 = $model->bkgBcb->bcb_vendor_id;

		$crtDate	 = date('d/m/Y', strtotime($model->bkg_create_date));
		$crtTime	 = date('h:i A', strtotime($model->bkg_create_date));
		$pctDate	 = date('d/m/Y', strtotime($model->bkg_pickup_date));
		$pctTime	 = date('h:i A', strtotime($model->bkg_pickup_date));
		$returnArr	 = ['userID' => $userID, 'drvID' => $drvId, 'vndID' => $vndId, 'NID' => $model->bkg_id, 'ID' => $model->bkg_booking_id, 'Trip' => $model->bkg_pickup_address . ' to ' . $model->bkg_drop_address, 'CRTDT' => $crtDate . " " . $crtTime, 'PCTDT' => $pctDate . " " . $pctTime];
		echo json_encode($returnArr);
	}

	public function actionvendorNotAssigned()
	{
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('vendorNotAssigned', array(), false, $outputJs);
	}

	public function actionchangeDriverAppRequirementStatus()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$bkgId		 = Yii::app()->request->getParam('bkgId');
			$bkgModel	 = Booking::model()->findByPk($bkgId);
			$diffMinutes = (DBUtil::getTimeDiff($bkgModel->bkg_pickup_date, DBUtil::getCurrentTime()));
			if ($diffMinutes >= 15)
			{
				$msg = "Modification time expired.";
				goto skip;
			}
			$model		 = BookingPref::model()->getByBooking($bkgId);
			$response	 = BookingPref::changeDrvAppRequirementStatus($bkgId);
			if ($response)
			{
				$msg							 = ($model->bkg_driver_app_required) ? "Driver app Requirement Currently Disabled" : "Driver app Requirement Currently Enabled";
				$params['blg_booking_status']	 = $bkgModel->bkg_status;
				BookingLog::model()->createLog($bkgModel->bkg_id, $msg, UserInfo::getInstance(), BookingLog:: DRIVER_APP_USAGE, '', $params);
			}
			skip:
			echo CJSON::encode($msg);
			Yii::app()->end();
		}
	}

	public function actionExtraDiscountAmount()
	{
		$bkgId			 = Yii::app()->request->getParam('bkgid');
		$model			 = Booking::model()->findByPk($bkgId);
		$bkgBookingId	 = $model->bkg_booking_id;
		$invoiceModel	 = BookingInvoice::model()->getByBookingID($bkgId);
		$disAmount		 = Yii::app()->request->getParam('disamount');
		$disReason		 = Yii::app()->request->getParam('disreason');
		if (isset($disAmount) && isset($bkgId))
		{
			$return				 = [];
			$return['success']	 = false;

			$success = $invoiceModel->applyExtraDiscount($bkgId, $disAmount, $disReason);
			if ($success == true)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'One-Time Price Added successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];

					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				$data = ['success' => $success, 'message' => 'Please Enter Valid Amount' . $result['Booking_bkg_id']];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('extradiscountamt', array('model' => $invoiceModel, 'bookingId' => $bkgBookingId), false, $outputJs);
	}

	public function actionNotifyvendor()
	{
		$bkgid				 = Yii::app()->request->getParam('bkid');
		$model				 = Booking::model()->findByPk($bkgid);
		$notificationSent	 = $model->bkgBcb->bcb_notification_sent;
		if ($notificationSent == 0)
		{
			$tripid	 = $model->bkg_bcb_id;
			BookingCab::notifyVendorsForPendingBookings($tripid);
			$model1	 = Booking::model()->findByPk($bkgid);
			$details = $model1->bkgBcb->bcb_notify_vendor_info;
			$msg	 = 'Notifications sent :
  ' . json_encode(json_decode($details), JSON_PRETTY_PRINT);

			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'msg' => $msg];
				echo json_encode($data, JSON_PRETTY_PRINT);
				Yii::app()->end();
			}
		}
		else
		{
			$details = $model->bkgBcb->bcb_notify_vendor_info;
			$msg	 = 'Notification already sent. Details :
  ' . json_encode(json_decode($details), JSON_PRETTY_PRINT);

			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'msg' => $msg];
				echo json_encode($data, JSON_PRETTY_PRINT);
				Yii::app()->end();
			}
		}
	}

	public function actionBreakSmartMatch()
	{

		$bcbid		 = Yii::app()->request->getParam('bcbid');
		$bookingId	 = Yii::app()->request->getParam('bookingId');
		$userInfo	 = UserInfo::getInstance();
		$success	 = Booking::breakSmartMatch($bcbid, $userInfo);
		if ($success == 1)
		{
			if ($bookingId != " ")
			{

				$this->redirect(array('/admin/booking/view', 'id' => $bookingId));
			}
			else
			{
				$this->redirect(array('matchlist'));
			}
		}
		else
		{
			$this->redirect(array('/admin/booking/matchlist', 'msg' => 1));
		}
	}

	public function actionQuotebooking()
	{
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
		$this->$method('quotebooking', array('model' => $model, 'quote' => $quotes), false, $outputJs);
	}

	/**
	 * This function is used to stop increasing booking vendor amount updated by system after manual assignment has been set
	 * 
	 */
	public function actionStopSystemMaxAllowableVndAmount()
	{
		if (Yii::app()->request->isAjaxRequest)
		{

			$bkgid	 = Yii::app()->request->getParam('bkgid');
			$model	 = Booking::model()->findByPk($bkgid);
			if ($model->bkgTrail->btr_stop_increasing_vendor_amount == 0)
			{
				$model->bkgTrail->btr_stop_increasing_vendor_amount = 1;
				if ($model->bkgTrail->save())
				{
					$desc					 = "Stopped Increasing Vendor Amount";
					$eventid				 = BookingLog::STOP_MAX_ALLOWABLE_VENDOR_AMOUNT;
					$params['blg_ref_id']	 = $bkgid;
					BookingLog::model()->createLog($bkgid, $desc, UserInfo::getInstance(), $eventid, false, $params);
					echo json_encode($desc);
					Yii::app()->end();
				}
			}
			else
			{
				$desc = "Already stopped increasing booking vendor amount";
				echo json_encode($desc);
				Yii::app()->end();
			}
		}
	}

	public function actionActivateGozonow()
	{
		$success = true;
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		$data	 = [];
		if (!$model)
		{
			$success		 = false;
			$data['message'] = 'Some error in data';
		}
//		if ($model->bkg_pickup_address == '')
//		{
//			$success		 = false;
//			$data['message'] = 'Pickup address is required';
//		}
		if ($model->bkg_pickup_date <= Filter::getDBDateTime())
		{
			$success		 = false;
			$data['message'] = 'Pickup date time is passed';
		}
		if ($model->bkg_status != 2)
		{
			$success		 = false;
			$data['message'] = 'Cannot send notification at this booking status';
		}
		if (!$success)
		{
			$data['success'] = $success;
			echo json_encode($data);
			Yii::app()->end();
		}
		$success		 = BookingPref::activateManualGozonow($bkgId);
		$data['message'] = 'GozoNow is already activated ';
		if ($success)
		{
			$desc		 = "GozoNOW activated manually for the booking: ";
			$userInfo	 = UserInfo::getInstance();
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: ACTIVATE_GOZO_NOW, false);

			$desc			 .= BookingCab::gnowNotify($model->bkg_bcb_id);
			$data['message'] = $desc;
			$success		 = true;
		}

		$data['success'] = $success;
		echo json_encode($data);
		Yii::app()->end();
//		$this->redirect(array('showgnowbidlist', ['bkid' => $model->bkg_id]));
	}

	public function actionShowgnowbidlist()
	{
		$bkgId	 = Yii::app()->request->getParam('id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}

		$success = BookingPref::activateManualGozonow($bkgId);
		if ($success)
		{
			$desc		 = "GozoNOW activated for the booking";
			$userInfo	 = UserInfo::model();
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: ACTIVATE_GOZO_NOW, false);
			BookingCab::gnowNotify($model->bkg_bcb_id);
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('showgnowbids', array('model' => $model), false, $outputJs);
	}

	public function actionGetGNowbidData()
	{
		$bkgid		 = Yii::app()->request->getParam('booking_id');
		$bkgModel	 = Booking::model()->findByPk($bkgid);
		$tripId		 = $bkgModel->bkg_bcb_id;
		if ($bkgModel->bkgPref->bkg_is_gozonow == 0)
		{
			throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
		}
		$bkgId		 = $bkgModel->bkg_id;
		$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
		if ($dataexist)
		{
			$hash	 = Yii::app()->shortHash->hash($bkgId);
			$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $bkgId]);
			echo json_encode(['type' => 'url', 'url' => $url]);
		}
		else
		{
			$bdIds		 = Yii::app()->request->getParam('bdids');
			$hideExpired = false;
			$data		 = BookingVendorRequest::getGNowAcceptedData($tripId, $hideExpired);
			$returnData	 = [];

			$rowCount = $data->getRowCount();

			if ($returnData == false && $rowCount > 0)
			{
				$dataList = BookingVendorRequest::getGNowManualAcceptedList($tripId, $hideExpired);

				$countBid	 = $rowCount;
				$data		 = $dataList;
				$bidIds		 = [];
				if ($data['success'])
				{
					$existingBidIds = explode(',', $bdIds);
					foreach ($data['data'] as $key => $value)
					{
						$bidIds[] = $value['bvr_id'];
						if (in_array($value['bvr_id'], $existingBidIds))
						{
							continue;
						}
						$returnData[$value['bvr_id']] = $this->renderPartial('gnowBidListTemplate', ['value' => $value], true);
					}
				}
//				$returnData['html']	 = $datahtml;
				Yii::app()->cache->set($cachekey2, $returnData, 6000);
				echo json_encode(['type' => 'html', 'dataHtml' => $returnData, 'cnt' => $countBid, 'bidIds' => $bidIds]);
				Yii::app()->end();
			}

			echo json_encode(['cnt' => $rowCount]);
		}
		Yii::app()->end();
	}

	public function actionProcessGNowbidaccept()
	{

		$bvrid		 = Yii::app()->request->getParam('bidId');
		$bookingId	 = Yii::app()->request->getParam('bookingId');

		/** @var BookingVendorRequest $bvrModel */
		$bvrModel	 = BookingVendorRequest::model()->findByPk($bvrid);
		$bkgId		 = $bvrModel->bvr_booking_id;
		$bkgModel	 = Booking::model()->findByPk($bkgId);
		$transaction = DBUtil::beginTransaction();

		try
		{
			if ($bookingId != $bkgId)
			{
				throw new CHttpException(400, 'Invalid booking data');
			}

			/** @var Booking $bkgModel */
			if (!$bkgModel)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			if ($bkgModel->bkg_status != 2)
			{
				throw new CHttpException(401, 'Already processed');
			}
			if ($bkgModel->bkgPref->bkg_is_gozonow == 0)
			{
				throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
			}
			$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($bkgModel->bkg_bcb_id);
			if (!$dataexist)
			{
				$bidAmount = $bvrModel->bvr_bid_amount;

				$success							 = $bvrModel->updatePreferredVendor();
				$userInfo							 = UserInfo::getInstance();
				$desc								 = "Vendor offer accepted";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog:: VENDOR_OFFER_ACCEPTED, false);
				$bkgModel->bkgBcb->bcb_vendor_amount = $bvrModel->bvr_bid_amount;
				if ($bkgModel->bkgBcb->save())
				{
					$model = BookingSub::processForManualGNowFromVendorAmount($bkgModel);
				}
				else
				{
					throw new Exception("Some error occured while processing the booking", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
			}
			DBUtil::commitTransaction($transaction);

			$url = Yii::app()->createUrl('admin/booking/view', ['id' => $bkgId]);

			echo json_encode(['success' => $success, 'url' => $url]);
			Yii::app()->end();
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
			Yii::app()->end();
		}
	}

	public function actionGnowAdminReNotify()
	{
		$success = true;

		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		if ($model->bkg_status != 2)
		{
			$success		 = false;
			$data['message'] = 'Cannot send notification at this booking status';
		}
		if (!$success)
		{
			$data['success'] = $success;
			echo json_encode($data);
			Yii::app()->end();
		}
		if ($model->bkgPref->bkg_is_gozonow == '2')
		{
			$desc			 = "Vendor re-notified for Gozo Now: ";
			$userInfo		 = UserInfo::model();
			BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog:: ACTIVATE_GOZO_NOW, false);
			$desc			 .= BookingCab::gnowNotify($model->bkg_bcb_id);
			$data['message'] = $desc;
			$success		 = true;
		}
		if ($model->bkgPref->bkg_is_gozonow == '1')
		{
			$message		 = BookingCab::gnowNotifyBulk($model->bkg_bcb_id);
			$data['message'] = $message;
			$success		 = true;
		}
		$data['gozonow'] = $model->bkgPref->bkg_is_gozonow;
		$data['success'] = $success;
		echo json_encode($data);
		Yii::app()->end();
//		$this->redirect(array('showgnowbidlist', ['bkid' => $model->bkg_id]));
	}

	public function actionGnowNotificationList()
	{
		$bkgId	 = Yii::app()->request->getParam('id');
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}
		$errorMessage	 = '';
		$dataProvider	 = false;
		if ($model->bkgPref->bkg_is_gozonow != 0)
		{
			$dataProvider = NotificationLog::getAllDetailsGozonowByTripid($model->bkg_bcb_id);
		}
		else
		{
			$errorMessage = "Booking is not activated for Gozo now";
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('gnowNotifications', array('dataProvider' => $dataProvider, 'model' => $model, 'errorMessage' => $errorMessage), false, $outputJs);
	}

	public function actionAskManualAssignment()
	{
		$request = Yii::app()->request;
		if ($request->getPost('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$bkgId			 = $arr['bkg_id'];
			$vendorId		 = $arr['bkg_vendor_id'];
			$vnd_name		 = Vendors::model()->getVendorById($vendorId);
			$desc			 = "Manual Assignment ask for booking:$bkgId. Vendor Name: $vnd_name.  Description:" . $arr['bkg_remark'];
			$modelBooking	 = Booking::model()->findByPk($bkgId);
			$userInfo		 = UserInfo::getInstance();
			$eventid		 = BookingLog::ASKMANUAL_ASSIGNMENT;
			$userModel		 = Admins::model()->getNameById($userInfo->getUserId());
			$userName		 = $userModel->adm_user;
			$params			 = ['blg_vendor_assigned_id' => $vendorId];
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, false, $params);
			$returnSet		 = ServiceCallQueue::autoFURManualAssignment($bkgId, $vendorId, $desc);
			if ($returnSet->getStatus())
			{
				$modelBooking->bkgPref->bpr_askmanual_assignment = 1;
				$modelBooking->bkgPref->save();

				/*				 * **************** Notification for Sanjeev team for ask for manual Assignment Start *********** */
//				$notificationId	 = substr(round(microtime(true) * 1000), -5);
//				$omIds			 = Admins::getCsrNotificationByTeam(18);
//				$payLoadData	 = ['bookingId' => $bkgId, 'EventCode' => Booking::CODE_PRICE_ANALYST];
//				$title			 = "Manual Assignment ask for booking:$bkgId. Vendor Name: $vnd_name";
//				foreach ($omIds as $omId)
//				{
//					$omId = $omId['adm_id'];
//					AppTokens::model()->notifyAdmin($omId, $payLoadData, $notificationId, $desc, $title);
//				}
				/*				 * **************** Notification for Sanjeev team for ask for manual Assignment Ends *********** */

//				$queueId = ServiceCallQueue::TYPE_BAR . "," . ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL . "," . ServiceCallQueue::TYPE_CSA;
//				$scqId	 = ServiceCallQueue::getDetailsByQueueBkgCsr($queueId, $bkgId, $csrId, 1);
//				if ($scqId > 0)
//				{
//					ServiceCallQueue::updateStatus($scqId, $userInfo->getUserId(), 2, $desc);
//				}
			}
			$this->redirect(['booking/view', 'id' => $bkgId]);
		}
		else
		{
			$model			 = new Booking();
			$bkgId			 = $request->getParam('bkg_id');
			$model->bkg_id	 = $bkgId;
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('askManualAssignment', array('bkg_id' => $bkgId, 'model' => $model), false, $outputJs);
	}

	public function actionCheckAdminGozonow()
	{
		$fcity			 = Yii::app()->request->getParam('fcity');
		$tcity			 = Yii::app()->request->getParam('tcity');
		$pickupDate		 = Yii::app()->request->getParam('pickupDate');
		$pickupTime		 = Yii::app()->request->getParam('pickupTime');
		$bkgType		 = Yii::app()->request->getParam('bkgType');
		$bkgVehicleType	 = Yii::app()->request->getParam('bkgVehicleType');
		$agtId			 = Yii::app()->request->getParam('agtId');

		$dateVal = DateTimeFormat::DatePickerToDate($pickupDate);

		$timeVal = DateTime::createFromFormat('h:i A', $pickupTime)->format('H:i:00');

		$pickupDateTime = $dateVal . ' ' . $timeVal;

		$result = BookingTemp::checkAdminGozoNowEligibility($fcity, $tcity, $pickupDateTime, $bkgType, $bkgVehicleType, $agtId);

		echo json_encode($result);
		Yii::app()->end();
	}

	/**
	 * This function is used to save csr feedback for every booking when he/she talk to customer/driver
	 * 
	 */
	public function actionCsrFeedBack()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$result = array();
			try
			{
				$userInfo								 = UserInfo::getInstance();
				$bkgid									 = Yii::app()->request->getParam('bkgid');
				$driverCabDetails						 = BookingSub::getDriverCabDetailsByBkgId($bkgid);
				$bkgstatus								 = Yii::app()->request->getParam('bkgstatus');
				$customer_to_driver_rating				 = Yii::app()->request->getParam('customer_to_driver_rating');
				$driver_to_cust_rating					 = Yii::app()->request->getParam('driver_to_cust_rating');
				$cust_to_car_rating						 = Yii::app()->request->getParam('cust_to_car_rating');
				$csr_to_customer_rating					 = Yii::app()->request->getParam('csr_to_customer_rating');
				$csr_to_driver_rating					 = Yii::app()->request->getParam('csr_to_driver_rating');
				$model									 = new CsrFeedback();
				$model->crf_admin_id					 = $userInfo->getUserId();
				$model->crf_bkg_id						 = $bkgid;
				$model->crf_bkg_status					 = $bkgstatus;
				$model->crf_driver_to_cust_rating		 = $driver_to_cust_rating;
				$model->crf_csr_to_customer_rating		 = $csr_to_customer_rating;
				$model->crf_cust_to_car_rating			 = $cust_to_car_rating;
				$model->crf_cab_id						 = ($cust_to_car_rating && $driverCabDetails) ? $driverCabDetails['bcb_cab_id'] : null;
				$model->crf_customer_to_driver_rating	 = $customer_to_driver_rating;
				$model->crf_csr_to_driver_rating		 = $csr_to_driver_rating;
				$model->crf_driver_id					 = (($customer_to_driver_rating != null || $csr_to_driver_rating != null ) && $driverCabDetails) ? $driverCabDetails['bcb_driver_id'] : null;
				$model->crf_create_at					 = DBUtil::getCurrentTime();
				$model->crf_updated_at					 = DBUtil::getCurrentTime();
				$model->crf_active						 = 1;
				if (!$model->save())
				{
					$result['success']	 = false;
					$result['message']	 = $model->getError();
				}
				else
				{
					$result['success']	 = true;
					$result['message']	 = "Feedback added sucessfully";
				}
			}
			catch (Exception $ex)
			{
				$result['success']	 = false;
				$result['message']	 = $ex->getMessage();
			}
			echo json_encode($result);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used to self Reallocate.
	 */
	public function actionReallocateCsr()
	{
		$userInfo					 = UserInfo::getInstance();
		$bkid						 = Yii::app()->request->getParam('booking_id');
		$bookingmodel				 = Booking::model()->findByPk($bkid);
		$data						 = [];
		$data['bkg_id']				 = $bookingmodel->bkg_id;
		$data['bkg_booking_type']	 = $bookingmodel->bkg_booking_type;
		$data['bpr_assignment_id']	 = $userInfo->userId;
		$data['type']				 = 1;
		$checkSelfReassignment		 = $bookingmodel::checkSelfReassignment($bookingmodel->bkg_id);
		if ($checkSelfReassignment && $bookingmodel->bkg_status == 2)
		{
			$scqCreated = ServiceCallQueue::createSelfReassignment($data, $userInfo->userId, 1);
			if ($scqCreated)
			{
				$model = ServiceCallQueue::model()->findByPk($scqCreated);
				CallStatus::model()->addMyCall($model->scq_id, $refType, $callType, 91, $model->scq_to_be_followed_up_with_value, 1);
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$tab	 = 2;
			$url	 = Yii::app()->createAbsoluteUrl("admin/booking/list", ["searchid" => $bkid, "tab" => $tab]);
			$data	 = ['success' => true, 'dec' => $desc, 'url' => $url];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$tab = 2;
		$this->redirect(array('list', 'tab' => $tab));
	}

	/**
	 * This function is used to self allocate. CBR
	 */
	public function actionselfAllocatCBR()
	{
		$success		 = false;
		$bkid			 = Yii::app()->request->getParam('bkg_id');
		$type			 = Yii::app()->request->getParam('type');
		$message		 = "Some error occured";
		$bookingmodel	 = Booking::model()->findByPk($bkid);
		$zoneType		 = States::model()->getZoenId($bookingmodel->bkg_from_city_id);
		$teamObj		 = Teams::getMultipleTeamid(UserInfo::getUserId());
		$team			 = Teams::getTeamByBookingRegionType($zoneType);
		$flag			 = 0;
		foreach ($teamObj as $teamId)
		{
			if ($teamId['tea_id'] == $team)
			{
				$flag = 1;
				break;
			}
		}
		$queueId = ServiceCallQueue::TYPE_BAR . "," . ServiceCallQueue::TYPE_AIRPORT_DAILYRENTAL . "," . ServiceCallQueue::TYPE_CSA;
		$scqIds	 = ServiceCallQueue::getDetailsByQueueBkgCsr($queueId, $bkid, UserInfo::getUserId());
		if ($scqIds)
		{
			$message = "Lead is already assigned to someone else.";
			$success = false;
		}
		else if (ServiceCallQueue::getDetailsByQueueBkgCsr($queueId, $bkid, UserInfo::getUserId(), 1, "2") > 0)
		{
			$data				 = [];
			$data['bkg_id']		 = $bkid;
			$data['stt_zone']	 = $zoneType;
			$data['Controller']	 = "Booking Controller";
			$data['desc']		 = "Dispatch team had escalate to field Operations for your help. Booking will auto cancel of vendor not assigned in time";
			$scqCreated			 = ServiceCallQueue::selfAllocatCBR($data, UserInfo::getUserId(), $team, $type, 1);
			if ($scqCreated)
			{
				$model	 = ServiceCallQueue::model()->findByPk($scqCreated);
				CallStatus::model()->addMyCall($model->scq_id, $refType, $callType, 91, $model->scq_to_be_followed_up_with_value, 1);
				$message = " Lead allocated successfully. ";
				$success = true;
			}
			else
			{
				$message = " Fail to allocate the lead. ";
				$success = false;
			}
		}
		else if ($flag == 0)
		{
			$message = " You are not authorized to perform this action. ";
			$success = false;
		}
		else
		{
			$data				 = [];
			$data['bkg_id']		 = $bkid;
			$data['stt_zone']	 = $zoneType;
			$data['Controller']	 = "Booking Controller";
			$data['desc']		 = "Dispatch team had escalate to field Operations for your help. Booking will auto cancel of vendor not assigned in time";
			$scqCreated			 = ServiceCallQueue::selfAllocatCBR($data, UserInfo::getUserId(), $team, $type, 1);
			if ($scqCreated)
			{
				$model	 = ServiceCallQueue::model()->findByPk($scqCreated);
				CallStatus::model()->addMyCall($model->scq_id, $refType, $callType, 91, $model->scq_to_be_followed_up_with_value, 1);
				$message = " Lead allocated successfully. ";
				$success = true;
			}
			else
			{
				$message = " Fail to allocate the lead. ";
				$success = false;
			}
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$url	 = Yii::app()->createAbsoluteUrl("admin/lead/mycall");
			$data	 = ['success' => $success, 'msg' => $message, 'url' => $url];
			echo CJSON::encode($data);
		}
		Yii::app()->end();
	}

	public function actionCheckDispatchCsr()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$scqId	 = ServiceCallQueue::checkExistDispatchCsr($bkgId);
		if ($scqId > 0)
		{
			$model	 = ServiceCallQueue::model()->findByPk($scqId);
			$admin	 = Admins::model()->findByPk($model->scq_assigned_uid);
			$name	 = $admin->adm_fname . ' ' . $admin->adm_lname;
			$msg	 = "Failed to allocated this booking.This booking already allocated to " . $name . "";
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true, 'scqId' => $scqId, 'msg' => $msg];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

	public function actionEditTravellerInfo()
	{
		$this->pageTitle = "Edit traveler booking";
		$id				 = Yii::app()->request->getParam('bookingID');
		$success		 = false;

		if ($id > 0)
		{
			$model = $this->loadModel($id);
		}
		$isRestricted = BookingInvoice::validateDateRestriction($model->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo 'Sorry, you cannot edit user info now.';
			Yii::app()->end();
		}
		$oldModel						 = clone $model;
		$model->bkgUserInfo->scenario	 = 'adminupdateuser';

		if (isset($_POST['Booking']))
		{
			$oldData = $model->getDetailsbyId($model->bkg_id);

			$bkgUserInfoOld = clone $oldModel->bkgUserInfo;
			if (isset($_POST['BookingUser']))
			{
				$model->bkgUserInfo->attributes = Yii::app()->request->getParam('BookingUser');
				$model->bkgUserInfo->save();
			}
			$params = false;
			if ($bkgUserInfoOld->bkg_user_fname != $model->bkgUserInfo->bkg_user_fname || $bkgUserInfoOld->bkg_user_lname != $model->bkgUserInfo->bkg_user_lname ||
					$bkgUserInfoOld->bkg_contact_no != $model->bkgUserInfo->bkg_contact_no || $oldModel->bkgUserInfo->bkg_user_email != $model->bkgUserInfo->bkg_user_email
			)
			{
				$params['blg_ref_id'] = BookingLog::INITIAL_INFO_CHANGED;
			}
			$success = $model->editUserInfo($oldData, $oldModel, $params);
			if ($success != false)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'Booking Modified Successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result		 = [];
					$customerror = [];
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
						foreach ($errors as $err)
						{
							$customerror[] = $err;
						}
					}
					$data = ['success' => $success, 'errors' => $result, 'customerror' => $customerror];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$errors = Yii::app()->request->getParam('errors');
		if ($errors != '')
		{
			$model->validate();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('edit_traveller_info', array('model' => $model), false, $outputJs);
	}

	/**
	 * This function is used to create Driver/Customer no show
	 *  bkg_id It will only require  booking id 
	 *  type 1=>Driver No show  2=>Customer No Show
	 */
	public function actionAddNoShowCBR()
	{
		$returnSet	 = new ReturnSet();
		$bkgId		 = Yii::app()->request->getParam('bkg_id');
		$type		 = Yii::app()->request->getParam('type');
		$queueId	 = $type == 1 ? ServiceCallQueue::TYPE_DRIVER_NOSHOW : ServiceCallQueue::TYPE_CUSTOMER_NOSHOW;
		$remark		 = $type == 1 ? "driver no show" : "customer no show";
		$scqIds		 = ServiceCallQueue::countQueueByBkgId($bkgId, $queueId, 'closed');
		if ($scqIds)
		{
			$returnSet->setMessage("CBR has been already created for $remark");
			$returnSet->setStatus(false);
		}
		else
		{
			$returnSet = ServiceCallQueue::addNoShowCBR($bkgId, $type);
			if ($returnSet->getStatus())
			{
				$returnSet->setMessage("CBR created successfully.");
				$returnSet->setStatus(true);
			}
			else
			{
				$returnSet->setMessage("Fail to create the CBR.");
				$returnSet->setStatus(false);
			}
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $returnSet->getStatus(), 'msg' => $returnSet->getMessage()];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		else
		{
			return $returnSet;
		}
	}

	/**
	 * 
	 * @param type $jsonData
	 * @return boolean
	 */
	public function actionGetBlokedLocationData()
	{
		$jsonData				 = Yii::app()->request->getParam('jsonData');
		$isBlocked				 = false;
		$cntRut					 = count($jsonData['BookingRoute']);
		$pickupPlaceObj			 = Stub\common\Place::init($jsonData['BookingRoute'][0]['brt_from_latitude'], $jsonData['BookingRoute'][0]['brt_from_longitude']);
		$isPickupBlockedLocation = BlockedLocations::getBlockedLocation($pickupPlaceObj);

		if ($cntRut > 1)
		{
			$dropoffPlaceObj		 = Stub\common\Place::init($jsonData['BookingRoute'][$cntRut - 1]['brt_to_latitude'], $jsonData['BookingRoute'][$cntRut - 1]['brt_to_longitude']);
			$dropoffBlockedLocation	 = BlockedLocations::getBlockedLocation($dropoffPlaceObj);
		}
		else
		{
			$dropoffPlaceObj		 = Stub\common\Place::init($jsonData['BookingRoute'][1]['brt_to_latitude'], $jsonData['BookingRoute'][1]['brt_to_longitude']);
			$dropoffBlockedLocation	 = BlockedLocations::getBlockedLocation($dropoffPlaceObj);
		}

		if (($isPickupBlockedLocation || $dropoffBlockedLocation) && $jsonData['trip_user'] == 2)
		{
			$isBlocked = true;
		}
		$data = ['isBlocked' => $isBlocked, 'msg' => "block location"];
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionUpdateVenorAmount()
	{
		$bkgId			 = Yii::app()->request->getParam('bkgid');
		$model			 = Booking::model()->findByPk($bkgId);
		$bkgBookingId	 = $model->bkg_booking_id;
		$invoiceModel	 = BookingInvoice::model()->getByBookingID($bkgId);
		$vndAmount		 = Yii::app()->request->getParam('vndamount');
		$vndReason		 = Yii::app()->request->getParam('vndreason');
		if (isset($vndAmount) && isset($bkgId) && (in_array($model->bkg_status, array(2, 15))))
		{
			$return				 = [];
			$return['success']	 = false;

			$success = $invoiceModel->updateVendorAmount($model, $vndAmount, $vndReason);
			BookingCab::gnowNotifyBulk($model->bkg_bcb_id, false);
			if ($success == true)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]);
					$data	 = ['success' => $success, 'message' => 'Update Vendor Amount successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];

					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				$data = ['success' => $success, 'message' => 'Please Enter Valid Amount' . $result['Booking_bkg_id']];
				echo json_encode($data);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('updateVendorAmount', array('model' => $invoiceModel, 'bookingId' => $bkgBookingId), false, $outputJs);
	}

	public function actionChangecancelreason()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');

		$model		 = Booking::model()->findByPk($bkgid);
		$reasonid	 = trim(Yii::app()->request->getParam('bkreason'));
		$reasonText	 = Yii::app()->request->getParam('bkreasontext');

		if (isset($_POST['bkreason']) && $bkgid != '')
		{

			$cancelReasons	 = CancelReasons::model()->getById($model->bkg_cancel_id);
			$oldText		 = "Old Reason: " . $cancelReasons["cnr_reason"] . " (" . $model->bkg_cancel_delete_reason . ")";

			$model->bkg_cancel_delete_reason = $reasonText;
			$model->bkg_cancel_id			 = $reasonid;
			$model->save();

			$cancelReasons	 = CancelReasons::model()->getById($reasonid);
			$newText		 = " ,New Reason: " . $cancelReasons["cnr_reason"] . " (" . $reasonText . ")";

			BookingLog::model()->createLog($bkgid, $oldText . " " . $newText, UserInfo::getInstance(), BookingLog::CANCEL_REASON_CHANGED, false, false);
			echo json_encode(['success' => true]);
			Yii::app()->end();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('changecancelreason', array('model' => $model), false, $outputJs);
	}

	public function actionRemoveVendorCompensation()
	{
		$transaction = null;
		$bkgId		 = Yii::app()->request->getParam('bkgid');
		$model		 = Booking::model()->findByPk($bkgId);

		if ($model == '')
		{
			echo json_encode(['success' => false, 'message' => 'Unable to remove.']);
			Yii::app()->end();
		}
		try
		{
			$transaction									 = DBUtil::beginTransaction();
			$model->bkgInvoice->bkg_vnd_compensation		 = 0;
			$model->bkgInvoice->bkg_vnd_compensation_date	 = null;
			if ($model->bkgInvoice->save())
			{
				AccountTransactions::model()->removeCompensationCharge($bkgId);

				$vnd_name	 = Vendors::model()->getVendorById($model->bkgBcb->bcb_vendor_id);
				$desc		 = "Remove vendor compensation.";
				$userInfo	 = UserInfo::getInstance();
				$eventid	 = BookingLog::COMPENSATION_REMOVE;

				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventid, false, '', '', $model->bkg_bcb_id);
				DBUtil::commitTransaction($transaction);
				echo json_encode(['success' => true, 'message' => 'Vendor Compensation for booking remove successfully']);
				Yii::app()->end();
			}
			echo json_encode(['success' => false, 'message' => 'Error Occured.']);
			Yii::app()->end();
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
			Yii::app()->end();
		}
	}

	public function actionViewVendorCompensation()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgid');
		$model	 = Booking::model()->findByPk($bkgId);
		if ($model == '')
		{
			echo json_encode(['success' => false, 'message' => 'Unable to remove.']);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('viewvendorcompensation', array('model' => $model, ' bkgid' => $bkgId), false, true);
	}

	public function actionSendDetailToCustomer()
	{
		$bkgId	 = Yii::app()->request->getParam('booking_id');
		$model	 = Booking::model()->findByPk($bkgId);
		if (!$model)
		{
			return false;
		}

		if (empty($model->bkg_agent_id) || $model->bkg_agent_id == 1249)
		{
			$email = $model->bkgUserInfo->bkg_user_email;
			if ($model->bkg_status == 15)
			{
				$response = WhatsappLog::sendPaymentRequestForBkg($model->bkg_id);
				if ($email != '' && $model->bkgPref->bkg_send_email == 1)
				{
					$emailCom = new emailWrapper();
					$emailCom->gotCreateQuoteBookingemail($model->bkg_id, BookingLog::System, false);
				}

				//Booking::notifyQuoteBookingB2C($bkgId);
			}
			else
			{
//				$response = WhatsappLog::bookingDetailsToCustomer($bkgId);
//				if ($email != '' && $model->bkgPref->bkg_send_email == 1)
//				{
//					emailWrapper::confirmBooking($model->bkg_id, '', 1);
//				}
				Booking::notifyConfirmBookingB2C($bkgId);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => false];
				if ($response && $response['status'] == 2)
				{
					$data = ['success' => true, 'desc' => 'Booking details sent to customer by email and whatsapp.'];
				}

				echo CJSON::encode($data);
				Yii::app()->end();
			}
		}
		$this->redirect(array('list', 'tab' => $model->bkg_status));
	}

	public function actionCngAllowed()
	{

		$booking_id			 = Yii::app()->request->getParam('booking_id');
		$cngallowed_status	 = Yii::app()->request->getParam('cngallowed_status');
		$model				 = Booking::model()->findByPk($booking_id);
		$logModel			 = new BookingLog();
		$logModel->scenario	 = 'cngallowed';
		if (isset($_POST['BookingLog']))
		{
			$logModel->attributes	 = Yii::app()->request->getParam('BookingLog');
			$arr					 = $logModel->attributes;
			$desc					 = trim($arr['blg_desc']);
			$cngallowed				 = $cngallowed_status;
			$userInfo				 = UserInfo::getInstance();

			$eventid						 = BookingLog::CNG_ALLOWED;
			$model->bkgPref->bkg_cng_allowed = '1';
			$model->bkgPref->save();

			$bkg_status						 = $model->bkg_status;
			$oldModel						 = clone $model;
			$params							 = [];
			$params['blg_booking_status']	 = $bkg_status;

			BookingLog::model()->createLog($booking_id, $desc, $userInfo, $eventid, $oldModel, $params);
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true, 'oldStatus' => $bkg_status];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('cngallowed', array('bkid' => $booking_id, 'cngallowed_status' => $cngallowed_status, 'bookModel' => $model, 'logModel' => $logModel), false, true);
	}

	public function actionCheckDboApplicable()
	{
		$result					 = ['success' => false, 'msg' => ' '];
		$applicableVehicleType	 = false;
		$pickupDate				 = Yii::app()->request->getParam('pickupDate');
		$pickupTime				 = Yii::app()->request->getParam('pickupTime');
		$bkgVehicleType			 = Yii::app()->request->getParam('bkgVehicleType');
		$agtId					 = Yii::app()->request->getParam('agtId');
		if ($agtId > 0)
		{
			goto skip;
		}
		if ($bkgVehicleType != null || $bkgVehicleType != '')
		{
			$applicableVehicleType = SvcClassVhcCat::applicableVehicleType($bkgVehicleType);
		}
		$dateVal		 = DateTimeFormat::DatePickerToDate($pickupDate);
		$timeVal		 = DateTime::createFromFormat('h:i A', $pickupTime)->format('H:i:00');
		$pickupDateTime	 = $dateVal . ' ' . $timeVal;
		$dboApplicable	 = Booking::checkDboApplicable($pickupDateTime);
		if ($dboApplicable && $applicableVehicleType)
		{
			$getDboConfirmEndTime	 = Filter::getDboConfirmEndTime($pickupDateTime);
			$date					 = date('D, jS M, h:i A', strtotime($getDboConfirmEndTime));
			$desc					 = 'Eligible for double back offer if you confirm before ' . $date . '</br> Not applicable for Tempo Traveller';
			$result					 = ['success' => true, 'msg' => $desc];
		}
		skip:
		echo json_encode($result);
		Yii::app()->end();
	}

	public function actionPushDriverCustomEvents()
	{
		$this->pageTitle = "Driver Custom event trigger";
		$id				 = Yii::app()->request->getParam('bookingID');
		$success		 = false;

		if ($id > 0)
		{
			$model = $this->loadModel($id);
		}

		if (isset($_POST['Booking']))
		{
			$data			 = $_POST['Booking'];
			$bkgId			 = $data['bkg_id'];
			$bkgBookingId	 = $data['bkg_booking_id'];
			$bkgModel		 = Booking::model()->findByPk($bkgId);

			$arrayList = [];
			if ($data['bkg_left_For_Pickup'] == 1)
			{
				$arrayList[] = BookingTrack::GOING_FOR_PICKUP;
			}
			if ($data['bkg_arrived'] == 1)
			{
				$arrayList[] = BookingTrack::DRIVER_ARRIVED;
			}
			if ($data['bkg_trip_start'] == 1)
			{
				$arrayList[] = BookingTrack::TRIP_START;
			}
			if ($data['bkg_trip_end'] == 1)
			{
				$arrayList[] = BookingTrack::TRIP_STOP;
			}

			foreach ($arrayList as $eventList)
			{
				switch ($eventList)
				{
					case $eventList == BookingTrack::GOING_FOR_PICKUP:
						$eventType	 = 'leftForPickup';
						break;
					case $eventList == BookingTrack::DRIVER_ARRIVED:
						$eventType	 = 'arrived';
						break;
					case $eventList == BookingTrack::TRIP_START:
						$eventType	 = 'tripStart';
						break;
					case $eventList == BookingTrack::TRIP_STOP:
						$eventType	 = 'tripEnd';
						break;
				}

				$data		 = booking::setEventSyncData($data, $eventType, $bkgModel->bkgInvoice->bkg_vendor_amount, $bkgModel);
				$jsonValue	 = CJSON::decode($data, false);
				$jsonObj	 = $jsonValue->data;

				$result		 = \Beans\booking\TrackEvent::setTrackModel($jsonObj, $isDCO		 = false);
				$model		 = $result[0];
				$trackObj	 = $result[1];

				$checkLog = DrvUnsyncLog::model()->checkExist($model->btl_bkg_id, $model->btl_event_type_id);

				/* @var $eventResponse booking */
				$eventResponse = $model->handleEvents($trackObj, 1);
			}

			if (count($arrayList) < 1)
			{
				$msg	 = "Please choose atleast one checkbox";
				$data	 = ['success' => false, 'message' => $msg];
				echo json_encode($data);
				Yii::app()->end();
			}

			end;

			if ($eventResponse->getStatus())
			{
				$userInfo	 = UserInfo::getInstance();
				$desc		 = 'Driver Custom Event triggered manually';
				BookingLog::model()->createLog($data['bkg_id'], $desc, $userInfo, BookingLog:: DRIVER_CUSTOM_EVENT_TRIGGERED_MANNUALY, false, false);
				if (Yii::app()->request->isAjaxRequest)
				{
					end:
					$url	 = Yii::app()->createUrl('admin/booking/view', ['id' => $bkgId]);
					$data	 = ['success' => $eventResponse->getStatus(), 'message' => $eventResponse->getMessage() . $bkgBookingId, 'url' => $url];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}

		$errors = Yii::app()->request->getParam('errors');
		if ($errors != '')
		{
			$model->validate();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('pushDriverCustomEvents', array('model' => $model), false, $outputJs);
	}

	public function actionConfirmpartnerbooking()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgid');
		$model	 = Booking::model()->findByPk($bkgId);
		if (isset($_POST['Booking']) && $bkgId > 0)
		{
			$corpamount = $_POST['Booking']['agentCreditAmount'];
			try
			{
				if ($corpamount > 0)
				{
					$isUpdateAdvance = $model->updateAdvance($corpamount, $model->bkg_pickup_date, PaymentType:: TYPE_AGENT_CORP_CREDIT, UserInfo:: getInstance(), null, "Partner Wallet Used On partner confirm booking from admin");
					if (!$isUpdateAdvance)
					{
						throw new Exception("Booking failed as partner wallet balance exceeded.");
					}
				}
				$model->refresh();
				Booking::model()->confirm(true, false, $bkgId);
				$createDate								 = Filter::getDBDateTime();
				$pickupDate								 = $model->bkg_pickup_date;
				$expireTime								 = BookingTrail::calculateQuoteExpiryTime($createDate, $pickupDate);
				$model->bkgTrail->bkg_quote_expire_date	 = $expireTime;
				$model->bkgTrail->save();
			}
			catch (Exception $ex)
			{
				echo json_encode(['success' => false]);
			}

			echo json_encode(['success' => true]);
			Yii::app()->end();
		}
		$this->renderPartial('confirmpartnerbooking', array('model' => $model), false, true);
	}
    
    public function actionGpx()
	{
		$bkgId = trim(Yii::app()->request->getParam('bkgId'));

		if (!$bkgId)
		{
			throw new CHttpException(400, 'Invalid Data');
		}

		$bkgModel = Booking::model()->findByPk($bkgId);
		if (!$bkgModel)
		{
			throw new CHttpException(400, 'Invalid Booking');
		}

		$bkgTrack	 = $bkgModel->bkgTrack;
		$imgPath	 = $bkgTrack->btk_gpx_file;
		$s3data		 = $bkgTrack->btk_gpx_s3_data;

		$filePath = (Yii::app()->basePath . $imgPath);

		if (file_exists($filePath))
		{
			Yii::app()->request->downloadFile($filePath);
		}
		else if ($s3data != '')
		{
			$spaceFile	 = Stub\common\SpaceFile::populate($s3data);
			$url		 = $spaceFile->getURL();
			Yii::app()->request->redirect($url);
		}
		else
		{
			throw new CHttpException(400, 'No File Found');
		}
	}

}
