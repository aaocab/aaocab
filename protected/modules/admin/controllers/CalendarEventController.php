<?php

class CalendarEventController extends Controller
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('MapYearEventDate', 'Create', 'EventDetails', 'AddUpdateEvent', 'MapEvent', '90DCalendar', 'ApprovedEvent'),
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
		];
	}

	public function actionMapYearEventDate()
	{
		$this->pageTitle = "Map Year Event Date";
		$models			 = new CalendarEvent();
		$request		 = Yii::app()->request;
		$year			 = date("Y");
		if ($request->getParam('CalendarEvent'))
		{
			$arr	 = $request->getParam('CalendarEvent');
			$year	 = $arr['year'];
		}
		$dataProvider	 = CalendarEvent::getYearEventDate($year);
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('map_year_event_date', array('dataProvider' => $dataProvider, 'models' => $models, 'year' => $year), false, $outputJs);
	}

	public function actionCreate()
	{
		$this->pageTitle = "Create/ Update Event";
		$models			 = new HolidayEvents();
		$request		 = Yii::app()->request;
		if ($request->getParam('type'))
		{
			$holidayname = Filter::sanitize($request->getParam('holidayName'));
			if (HolidayEvents::isExist($holidayname) == 0)
			{
				$holidayEventModel							 = new HolidayEvents();
				$holidayEventModel->hde_active				 = 2;
				$holidayEventModel->hde_added_by_uid		 = UserInfo::getUserId();
				$holidayEventModel->hde_calendar_event_type	 = 1;
				$holidayEventModel->hde_name				 = $request->getParam('holidayName');
				$holidayEventModel->hde_slug				 = $holidayname;
				$holidayEventModel->hde_description			 = $request->getParam('holidayDescription');
				$holidayEventModel->hde_recurrs				 = 0;
				$holidayEventModel->hde_std_or_not			 = 2;
				$holidayEventModel->hde_halo_previous_days	 = $request->getParam('previousHaloDays') != null ? $request->getParam('previousHaloDays') : 1;
				$holidayEventModel->hde_halo_next_days		 = $request->getParam('nextHaloDays') != null ? $request->getParam('nextHaloDays') : 1;
				$holidayEventModel->hde_created_at			 = DBUtil::getCurrentTime();
				$holidayEventModel->hde_modified_at			 = DBUtil::getCurrentTime();
				if ($holidayEventModel->save())
				{
					echo CJSON::encode(array('success' => true, 'message' => "Event name added sucessfully"));
				}
				else
				{
					echo CJSON::encode(array('success' => false, 'message' => "Fail to save event name"));
				}
			}
			else
			{
				echo CJSON::encode(array('success' => false, 'message' => "Event name already exist"));
			}
			Yii::app()->end();
		}


		$method = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('create', array('models' => $models), false, $outputJs);
	}

	public function actionEventDetails()
	{
		$this->pageTitle = "Event Details";
		$request		 = Yii::app()->request;
		$eventId		 = $request->getParam('eventId');
		if ($eventId > 0)
		{
			$eventGeoDetails = EventGeo::getEventGeoDetails($eventId);
			if ($eventGeoDetails)
			{
				$holidayEventsModel		 = HolidayEvents::model()->findByPk($eventId);
				$citySourceDetails		 = $eventGeoDetails[0]['etg_source_city_id'] != null ? Cities::model()->getCityNamebyArr($eventGeoDetails[0]['etg_source_city_id']) : [];
				$cityDestinationDetails	 = $eventGeoDetails[0]['etg_destination_city_id'] != null ? Cities::model()->getCityNamebyArr($eventGeoDetails[0]['etg_destination_city_id']) : [];
				echo CJSON::encode(array('success' => true, 'recurrs_rule' => $holidayEventsModel->hde_recurrs_rule, 'details' => $eventGeoDetails, 'citySourceDetails' => $citySourceDetails, 'cityDestinationDetails' => $cityDestinationDetails, 'message' => "Event details fetch sucessfully"));
			}
			else
			{
				echo CJSON::encode(array('success' => false, 'message' => "No record found"));
			}
		}
		else
		{
			echo CJSON::encode(array('success' => false, 'message' => "Please select event"));
		}
		Yii::app()->end();
	}

	public function actionAddUpdateEvent()
	{
		$this->pageTitle = "Add Update Event";
		$request		 = Yii::app()->request;
		if ($request->getParam('type'))
		{
			$eventId			 = $request->getParam('eventId');
			$regions			 = $request->getParam('region');
			$source_mzone		 = $request->getParam('source_mzone');
			$destination_mzone	 = $request->getParam('destination_mzone');
			$source_zone		 = $request->getParam('source_zone');
			$destination_zone	 = $request->getParam('destination_zone');
			$source_state		 = $request->getParam('source_state');
			$destination_state	 = $request->getParam('destination_state');
			$source_city		 = $request->getParam('source_city');
			$destination_city	 = $request->getParam('destination_city');
			$allRegionType		 = $request->getParam('allRegionType');
			$margin				 = $request->getParam('margin');
			$holidayEventsModel	 = HolidayEvents::model()->findByPk($eventId);
			if ($request->getParam('hde_recurrs') != null)
			{
				$holidayEventsModel->hde_recurrs_rule	 = $request->getParam('hde_recurrs');
				$holidayEventsModel->hde_recurrs		 = $request->getParam('hde_recurrs') != null ? 1 : 0;
				$holidayEventsModel->save();
			}

			$data = array(
				'isStandard'			 => ($holidayEventsModel->hde_std_or_not),
				'eventId'				 => $eventId,
				'margin'				 => $margin,
				'affects_region_type'	 => ($allRegionType | 0),
				'regions'				 => implode(",", $regions),
				'source_mzone'			 => implode(",", $source_mzone),
				'destination_mzone'		 => implode(",", $destination_mzone),
				'source_zone'			 => implode(",", $source_zone),
				'destination_zone'		 => implode(",", $destination_zone),
				'source_state'			 => implode(",", $source_state),
				'destination_state'		 => implode(",", $destination_state),
				'source_city'			 => implode(",", $source_city),
				'destination_city'		 => implode(",", $destination_city));

			if ($allRegionType == 0 || $allRegionType == -1 || $allRegionType == 1)
			{
				EventGeo::InactiveByEventId($eventId);
				EventGeo::add($data);
				CalendarEvent::MapEventWithRule($eventId);
				echo CJSON::encode(array('success' => true, 'message' => "Event Geo added sucessfully"));
			}
			else
			{
				echo CJSON::encode(array('success' => false, 'message' => "Something went wrong"));
			}
		}
		else
		{
			echo CJSON::encode(array('success' => false, 'message' => "Something went wrong"));
		}
		Yii::app()->end();
	}

	public function actionMapEvent()
	{
		$this->pageTitle = "Event Date Range";
		$model			 = new CalendarEvent();
		$request		 = Yii::app()->request;
		$success		 = true;
		if ($request->getParam('CalendarEvent'))
		{
			$arr			 = $request->getParam('CalendarEvent');
			$model->fromDate = trim($arr['fromDate']);
			$model->toDate	 = trim($arr['toDate']);
			$model->eventId	 = trim($arr['eventId']);
			$eventId		 = $model->eventId;
			$begin			 = new DateTime($model->fromDate);
			$end			 = new DateTime($model->toDate);

			$holidayEventsModel = HolidayEvents::model()->findbypk($eventId);
			for ($j = $begin; $j <= $end; $j->modify('+1  day'))
			{
				try
				{
					$date	 = $j->format("Y-m-d");
					$flag	 = CalendarEvent::IsEventByDate($date) > 0 ? 1 : 0;
					CalendarEvent::updateEvent($eventId, $date, $flag);
					if ($flag == 1)
					{
						$eventRowData	 = CalendarEvent::getEventRow($date);
						$eventIds		 = implode(',', array_unique(explode(',', $eventRowData['cle_event_id'])));
						$haloEventIds	 = implode(',', array_unique(explode(',', $eventRowData['cle_halo_event_id'])));
						CalendarEvent::updateEventByDate($date, $eventIds, 1);
						CalendarEvent::updateEventByDate($date, $haloEventIds, 2);
					}
				}
				catch (Exception $ex)
				{
					$success = false;
				}
			}

			for ($i = $holidayEventsModel->hde_halo_previous_days; $i >= 1; $i--)
			{
				//adding Halo event from previuos and next day of the range start
				$previousDate	 = new DateTime($model->fromDate);
				$previousDate	 = $previousDate->modify("-$i day");
				$previousDate	 = $previousDate->format("Y-m-d");
				$haloFlag		 = CalendarEvent::isHaloEventDate($previousDate) > 0 ? 1 : 0;
				CalendarEvent::updateHaloEvent($eventId, $previousDate, $haloFlag);
				if ($haloFlag == 1)
				{
					$eventRowData	 = CalendarEvent::getEventRow($previousDate);
					$haloEventIds	 = implode(',', array_unique(explode(',', $eventRowData['cle_halo_event_id'])));
					CalendarEvent::updateEventByDate($previousDate, $haloEventIds, 2);
				}
			}

			for ($i = 1; $i <= $holidayEventsModel->hde_halo_next_days; $i++)
			{
				$lastDate	 = new DateTime($model->toDate);
				$lastDate	 = $lastDate->modify("+$i day");
				$lastDate	 = $lastDate->format("Y-m-d");
				$haloFlag	 = CalendarEvent::isHaloEventDate($lastDate) > 0 ? 1 : 0;
				CalendarEvent::updateHaloEvent($eventId, $lastDate, $haloFlag);
				if ($haloFlag == 1)
				{
					$eventRowData	 = CalendarEvent::getEventRow($lastDate);
					$haloEventIds	 = implode(',', array_unique(explode(',', $eventRowData['cle_halo_event_id'])));
					CalendarEvent::updateEventByDate($lastDate, $haloEventIds, 2);
				}
				//adding Halo event from previuos and next day of the range Ends
			}
			$this->redirect(array('MapYearEventDate'));
		}
		else
		{
			$eventId		 = trim($request->getParam('eventId'));
			$model->eventId	 = $eventId;
		}
		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "" );
		$this->$method('mapeventdate', ['eventId' => $eventId, 'model' => $model, 'success' => $success], false, $outputJs);
	}

	public function action90DCalendar()
	{
		$this->pageTitle = "View 90D Calendar";
		$request		 = Yii::app()->request;
		$pastDays		 = 30;
		$nextDays		 = 90;
		$model			 = new CalendarEvent();
		$model->pastDays = $pastDays;
		$model->nextDays = $nextDays;
		if ($request->getParam('CalendarEvent'))
		{
			$arr			 = $request->getParam('CalendarEvent');
			$pastDays		 = $arr['pastDays'];
			$nextDays		 = $arr['nextDays'];
			$model->pastDays = $pastDays;
			$model->nextDays = $nextDays;
		}
		$dataProvider	 = CalendarEvent::get90DayCalendar($pastDays, $nextDays);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('90dayviewcalendar', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionApprovedEvent()
	{
		$this->pageTitle = "Approved Event";
		$request		 = Yii::app()->request;
		if ($request->getParam('holidayId') != "" && $request->getParam('actionType') != "")
		{
			$model = HolidayEvents::model()->findByPk($request->getParam('holidayId'));
			if ($model)
			{
				$model->hde_approved_by_uid	 = UserInfo::getUserId();
				$model->hde_active			 = $request->getParam('actionType');
				$model->hde_modified_at		 = DBUtil::getCurrentTime();
				if ($model->save())
				{
					echo CJSON::encode(array('success' => true, 'message' => "Event name added sucessfully"));
				}
				else
				{
					echo CJSON::encode(array('success' => false, 'message' => "Fail to save event name"));
				}
			}
			else
			{
				echo CJSON::encode(array('success' => false, 'message' => "Event name already exist"));
			}
		}
		else
		{
			echo CJSON::encode(array('success' => false, 'message' => "Some error occured"));
		}
		Yii::app()->end();
	}

}
