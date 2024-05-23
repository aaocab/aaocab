<?php

use BookingLog;
use LeadLog;
use UserLog;

class FollowUpController extends Controller
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
				'actions'	 => array('list', 'add', 'registerlog', 'details', 'reschedule'),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['fetchList'], 'roles' => ['editTeamsQueueMapping']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('*'),
			),
		);
	}

	/**
	 * @todo Suvajit - Need to sync with actionList for runController Command
	 * Its just a Jugaar
	 */
	public function actionListOne()
	{
		$this->pageTitle = "CallBackList";
		$model			 = new FollowUps();

		$fwpId	 = (Yii::app()->request->getParam('fwpId') > 0 ) ? Yii::app()->request->getParam('fwpId') : 0;
		$refId	 = (Yii::app()->request->getParam('refId') > 0 ) ? Yii::app()->request->getParam('refId') : 0;

		$dataProvider = $model->fetchList($fwpId, $refId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderPartial('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider
		));
	}

	/**
	 * This function is used for getting followup details
	 */
	public function actionList()
	{
		$this->pageTitle = "CallBackList";
		$model			 = new FollowUps();

		$fwpId	 = (Yii::app()->request->getParam('fwpId') > 0 ) ? Yii::app()->request->getParam('fwpId') : 0;
		$refId	 = (Yii::app()->request->getParam('refId') > 0 ) ? Yii::app()->request->getParam('refId') : 0;

		$dataProvider = $model->fetchList($fwpId, $refId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderAuto('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider
		));
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
	public function actionAdd()
	{
		$refId	 = Yii::app()->request->getParam('Id');
		$model	 = new FollowupLog('followAdd');
		$request = Yii::app()->request;
		if ($request->getPost('FollowupLog'))
		{
			$remarks = $request->getParam('FollowupLog')['fpl_remarks'];
			$eventId = $request->getParam('FollowupLog')['fpl_event_id'];
			$csr	 = UserInfo::getUserId();
			if ($eventId > 0)
			{
				$list = FollowUps::updatestate($refId, $eventId, $eventId, $csr, $remarks);
				//$this->redirect('list');
				if (Yii::app()->request->isAjaxRequest)
				{
					echo json_encode(['success1' => 1]);
					Yii::app()->end();
				}
				$this->redirect(array('followUp/list'));
			}
			else
			{
				$model->addError('fpl_event_id', 'Select the event from list');
			}
		}
		$this->renderAuto('add', array('model' => $model), false, true);
	}

	public function actionRegisterlog()
	{
		$refId			 = Yii::app()->request->getParam('refId');
		$remarks		 = Yii::app()->request->getParam('remarks');
		$eventId		 = Yii::app()->request->getParam('eventId');
		$isReSchedule	 = Yii::app()->request->getParam('flag');
		$reSchedulemsg	 = $remarks . "-Close the followup due to reSchedule event";
		$remarks		 = ($isReSchedule == 0) ? $remarks : $reSchedulemsg;
		$eventId		 = ($isReSchedule == 0) ? $eventId : 4;
		$csr			 = UserInfo::getUserId();
		$model			 = FollowUps::model()->findByPk($refId);
		$status			 = FollowUps::updatestate($refId, $eventId, $eventId, $csr, $remarks);
		BookingTrail::unAssignCsr($model->fwp_ref_id);
		echo json_encode(['result' => $status]);
	}

	public function actionDetails()
	{
		$refId		 = Yii::app()->request->getParam('refId');
		$detail		 = FollowUps::model()->detail($refId);
		$returnArr	 = ['success' => true, 'detail' => $detail];
		echo json_encode($returnArr);
	}

	/**
	 * This function is used for adding and reschedule followup 
	 */
	public function actionReschedule()
	{
		$success		 = false;
		$refId			 = Yii::app()->request->getParam('refId');
		$isReSchedule	 = 1;
		Yii::app()->request->getParam('isReSchedule');
		$model			 = new FollowUps();
		if ($refId)
		{
			$model = FollowUps::model()->findByPk($refId);
		}
		$request = Yii::app()->request;


		if ($request->getPost('FollowUps'))
		{
			$desc				 = $request->getParam('FollowUps')['fwp_desc'];
			$parent				 = $request->getParam('FollowUps')['fwp_parent_id'];
			$existFollowModel	 = FollowUps::model()->findByPk($parent);
			if ($parent)
			{
				$csr		 = UserInfo::getUserId();
				$ex_remarks	 = "-Close the followup due to reSchedule event";
				FollowUps::updatestate($parent, 4, 4, $csr, $ex_remarks);
			}

			$bookingModel = Booking::model()->findByPk($existFollowModel->fwp_ref_id);

			$followWith	 = $request->getParam('FollowUps')['followupWith'];
			$teamId		 = $request->getParam('FollowUps')['fwp_team_id'];

			$followupDt		 = $request->getParam('FollowUps')['locale_followup_date'];
			$followupTime	 = $request->getParam('FollowUps')['locale_followup_time'];


			$followupDateVal = DateTimeFormat::DatePickerToDate($followupDt);
			$followupTimeVal = DateTime::createFromFormat('h:i A', $followupTime)->format('H:i:00');
			$followupDate	 = $followupDateVal . ' ' . $followupTimeVal;
			$reqData		 = ['fwp_desc'				 => $desc,
				'fwp_ref_id'			 => $bookingModel->bkg_booking_id,
				'fwp_ref_type'			 => 2,
				'fwp_contact_phone_no'	 => $bookingModel->bkgUserInfo->bkg_contact_no,
				'fwp_prefered_time'		 => $followupDate,
				'fwp_parent_id'			 => $parent];

			switch ($followWith)
			{
				case 1:
					$entityId	 = $bookingModel->bkgUserInfo->bkg_user_id;
					$entityType	 = UserInfo::TYPE_CONSUMER;
					break;
				case 2:
					$entityId	 = $bookingModel->bkgBcb->bcb_vendor_id;
					$entityType	 = UserInfo::TYPE_VENDOR;
					break;
				case 3:
					$entityId	 = $bookingModel->bkgBcb->bcb_driver_id;
					$entityType	 = UserInfo::TYPE_DRIVER;
					break;
				case 5:
					$entityId	 = $bookingModel->bkg_agent_id;
					$entityType	 = UserInfo::TYPE_AGENT;
					break;
				case 6:
					$entityType	 = UserInfo::TYPE_INTERNAL;
					$entityId	 = 0;
					break;
			}
			/* Patch work for vendor followup reschedule */
			if ($existFollowModel->fwp_ref_id == NULL)
			{
				$followup = FollowUps::model()->storeCMBDataForVendor($existFollowModel, $desc, $followupDate);
			}

			if ($entityType == 11)
			{
				$follwup	 = FollowUps::model()->storeCMBDataForInternal($reqData, $entityId, $entityType, $platform	 = FollowUps::PLATFORM_ADMIN_CALL, $teamId);
			}
			else
			{
				$follwup	 = FollowUps::model()->storeCMBData($reqData, $entityId, $entityType, $platform	 = FollowUps::PLATFORM_ADMIN_CALL, $teamId);
			}
			$dt		 = $follwup->getData();
			$success = ($dt['followupId']) ? true : false;
			if ($success)
			{


				if (Yii::app()->request->isAjaxRequest)
				{
					echo json_encode(['success' => $success]);
					Yii::app()->end();
				}
				$this->redirect(array('generalReport/internalCbr'));
			}
		}
		$this->renderPartial('followup', array('followUps' => $model), false, true);
	}
}

?>
	 