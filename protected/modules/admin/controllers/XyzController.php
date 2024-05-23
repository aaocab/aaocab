<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class XyzController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'admin1';
	public $pageHeader	 = '';

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
			//	['allow', 'actions' => ['surgeform'], 'roles' => ['pricesurge']],
			['allow', 'actions' => ['csrf'], 'roles' => ['csrReport']],
			['allow', 'actions' => ['mbkg'], 'roles' => ['mbkgReport']],
			['allow', 'actions' => ['priceAnalysisList', 'agentTrackingDetails', 'partnerTrackingDetails', 'operatorTrackingDetails'], 'users' => ['@']],
			['allow', 'actions' => ['Mffreport', 'Addcordinator', 'ChangeStatus', 'Mffcab', 'ApplyGozoCoins', 'ApplyPromoCoins']],
			['deny', 'users' => ['*']],
		);
	}

	/**
	 * @deprecated 
	 */
	public function actionMffreport()
	{
		exit;
		$setPass		 = "250250";
		$bType			 = Yii::app()->request->getParam('booktype');
		$dataBookingMff	 = Yii::app()->request->getParam('BookingMff');
		$coordinateName	 = $dataBookingMff['bmf_pickup_cordinator'];
		$vendor			 = $dataBookingMff['vendor_id'];

		$chkSession	 = $_COOKIE['totalmff'];
		$error		 = 0;
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('totalmff', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
		}

		if (!Yii::app()->user->isGuest)
		{
			$error	 = 0;
			$admin	 = 1;
		}
		if ($bType == '' || $bType == null)
		{
			$bType = 3;
		}
		$model			 = Booking::model()->getMffBookingDetails($admin, $bType, $coordinateName, $vendor);
		$modelBookingMff = new BookingMff();
		if ($coordinateName != '')
		{
			$modelBookingMff->bmf_pickup_cordinator = $coordinateName;
		}
		if ($vendor != '')
		{
			$modelBookingMff->vendor_id = $vendor;
		}
		$this->renderPartial('mff_report', array('modelBookingMff' => $modelBookingMff, 'model' => $model, 'usersList' => $modelList, 'model1' => $model1, 'error' => $error, 'chkSession' => $chkSession, 'admin' => $admin, 'bType' => $bType), false, true);
	}

	public function actionAddcordinator()
	{
		$bkId			 = Yii::app()->request->getParam('bkgId');
		$modelBookingMff = BookingMff::model()->find('bmf_booking_id=:id', ['id' => $bkId]);
		if ($modelBookingMff == '' || $modelBookingMff == NULL || empty($modelBookingMff))
		{
			$modelBookingMff = new BookingMff();
		}
		$modelBookingMff->scenario = "change_cordinator";
		if (isset($_POST['BookingMff']))
		{
			if ($modelBookingMff->bmf_pickup_cordinator != '')
			{
				$modelBookingMff->bmf_log = json_encode(['cord' => $modelBookingMff->bmf_pickup_cordinator, 'updated' => $modelBookingMff->bmf_created]);
			}
			$modelBookingMff->attributes			 = $_POST['BookingMff'];
			$modelBookingMff->bmf_pickup_cordinator	 = trim($_POST['BookingMff']['bmf_pickup_cordinator']);
			if ($modelBookingMff->validate())
			{
				$modelBookingMff->save();
				$this->redirect('/mffreport');
			}
		}
		else
		{
			$modelBookingMff->bmf_booking_id = $bkId;
		}
		$this->renderPartial('add_cordinator', array('modelBookingMff' => $modelBookingMff), false, true);
	}

	public function actionChangeStatus()
	{
		$bkId			 = Yii::app()->request->getParam('bkgId');
		$status			 = Yii::app()->request->getParam('status');
		$modelBookingMff = BookingMff::model()->find('bmf_booking_id=:id', ['id' => $bkId]);
		if ($modelBookingMff != '')
		{
			$modelBookingMff->bmf_status = $status;
		}
		else
		{
			$modelBookingMff				 = new BookingMff();
			$modelBookingMff->bmf_status	 = $status;
			$modelBookingMff->bmf_booking_id = $bkId;
		}
		if ($modelBookingMff->save())
		{
			echo json_encode(['success' => true]);
			Yii::app()->end();
		}
		echo json_encode(['success' => false]);
		Yii::app()->end();
	}

	public function actionMffcab()
	{
		exit;
		if (!Yii::app()->user->isGuest)
		{
			$error = 0;
		}
		else
		{
			$error = 1;
		}
		$dataBookingMff	 = Yii::app()->request->getParam('BookingMff');
		$vendor			 = $dataBookingMff['vendor_id'];
		$toZone			 = $dataBookingMff['to_zone_id'];
		$model			 = Booking::model()->getMffCab($vendor, $toZone);
		$modelBookingMff = new BookingMff();
		if ($vendor != '')
		{
			$modelBookingMff->vendor_id = $vendor;
		}
		if ($toZone != '')
		{
			$modelBookingMff->to_zone_id = $toZone;
		}
		$this->renderPartial('mff_cab', array('modelBookingMff' => $modelBookingMff, 'model' => $model, 'error' => $error), false, true);
	}

	/** @deprecated */
	public function actionCsrf()
	{
		$this->pageTitle = "Admin Report";
		$request		 = Yii::app()->request;
		$model			 = new Admins;
		if ($request->getParam('Admins'))
		{
			$arr		 = $request->getParam('Admins');
			$fromDate	 = $arr['from_date'];
			$toDate		 = $arr['to_date'];
		}
		else
		{

			$fromDate	 = DateTimeFormat::DateToLocale(date("Y-m-d"));
			$toDate		 = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime("+1 day")));
		}
		$model->from_date	 = $fromDate;
		$model->to_date		 = $toDate;
		$fromDate			 = DateTimeFormat::DatePickerToDate($fromDate);
		$toDate				 = DateTimeFormat::DatePickerToDate($toDate);
		//       $dataProvider     = Admins::csrPerformance($fromDate, $toDate);
//        $dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
//        $dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
//        $this->render('csrf', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionPriceAnalysisList()
	{
		$this->layout			 = "head";
		$this->pageTitle		 = "Price Analysis List";
		$pageSize				 = Yii::app()->params['listPerPage'];
		$model					 = new AgentApiTracking();
		$date					 = new DateTime();
		$date->add(new DateInterval('P1D'));
		$model->aat_pickup_date1 = $date->format('Y-m-d') . " 00:00:00";
		$model->aat_pickup_date2 = $date->format('Y-m-d') . " 23:59:59";
		$model->aat_hours		 = 12;
		$model->datafor			 = 'cities';
		if ($_REQUEST['AgentApiTracking'])
		{
			$arr					 = Yii::app()->request->getParam('AgentApiTracking');
			$model->attributes		 = $arr;
			$model->aat_pickup_date1 = date("Y-m-d", strtotime($arr['aat_pickup_date1'])) . " 00:00:00";
			$model->aat_pickup_date2 = date("Y-m-d", strtotime($arr['aat_pickup_date2'])) . " 23:59:59";
			$model->aat_booking_type = $arr['aat_booking_type'];
			$model->aat_hours		 = $arr['aat_hours'];
			$model->sourcezone		 = $arr['sourcezone'];
			$model->destinationzone	 = $arr['destinationzone'];
			$model->datafor			 = $arr['datafor'];
		}
		$params = array_filter($_GET + $_POST);

		$dataProvider	 = $dataProvider1	 = $dataProvider2	 = false;
		if ($model->datafor == 'cities')
		{
			$dataProvider = $model->getPriceanalysisList();
			$dataProvider->setSort(['params' => $params]);
			$dataProvider->setPagination(['params' => $params]);
		}
		elseif ($model->datafor == 'zones')
		{
			$dataProvider1 = $model->getPriceanalysisListByfZonetZone();
			$dataProvider1->setSort(['params' => $params]);
			$dataProvider1->setPagination(['params' => $params]);
		}
		elseif ($model->datafor == 'zone')
		{
			$dataProvider2 = $model->getPriceanalysisListByfZone();
			$dataProvider2->setSort(['params' => $params]);
			$dataProvider2->setPagination(['params' => $params]);
		}
		$this->render('priceanalysis', array('model' => $model, 'dataProvider' => $dataProvider, 'dataProvider1' => $dataProvider1, 'dataProvider2' => $dataProvider2));
	}

	public function actionagentTrackingDetails_OLD()
	{
		$this->pageTitle = "Show Agent Request";
		$elgId			 = Yii::app()->request->getParam('aatId');
		$model			 = AgentApiTracking::model()->findByPk($elgId);
		$s3data			 = $model->aat_s3_data;

		if ($model->aat_s3_data == '{}' || $model->aat_s3_data == NULL)
		{
			$this->renderPartial('list', array('model' => $model), false, false);
		}
		else
		{
			$spaceFile = Stub\common\SpaceFile::populate($s3data);
			if ($spaceFile->getFile() != null)
			{
				echo $spaceFile->getFile()->getContents();
			}
			exit();
		}
	}

	public function actionAgentTrackingDetails()
	{
		$this->pageTitle = "Show Agent Request and Response";

		$aatId = Yii::app()->request->getParam('aatId');

		$model = AgentApiTracking::model()->findByPk($aatId);
		if (!$model)
		{
			$model = AgentApiTracking::model()->getFromArchieveById($aatId);
		}
		if (!$model)
		{
			throw new Exception("Record not found!!!");
		}

		if ($model->aat_s3_data != '' && $model->aat_s3_data != null)
		{
			$s3data		 = $model->aat_s3_data;
			$spaceFile	 = Stub\common\SpaceFile::populate($s3data);
			$url		 = $spaceFile->getURL();
			echo $url . "URL==============><br><br>";

			if ($model->aat_s3_data != '' && ($file = $model->getSpaceFile()) != null)
			{
				$body = $file->getContents();
			}

			echo "<pre>";
			print_r($body);
			exit();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('list', array('model' => $model));
	}

	public function actionpartnerTrackingDetails()
	{
		$this->pageTitle = "Show Partner Request";
		$elgId			 = Yii::app()->request->getParam('patId');
		$model			 = PartnerApiTracking::model()->findByPk($elgId);
		$s3data			 = $model->pat_s3_data;
		if ($model->pat_s3_data == '{}' || $model->pat_s3_data == NULL)
		{
			$this->renderPartial('partnerlist', array('model' => $model), false, false);
		}
		else
		{
			$spaceFile = Stub\common\SpaceFile::populate($s3data);
			if ($spaceFile->getFile() != null)
			{
				echo $spaceFile->getFile()->getContents();
			}
			exit();
		}
	}

	public function actionoperatorTrackingDetails()
	{
		$this->pageTitle = "Show Operator Request";
		$elgId			 = Yii::app()->request->getParam('oatId');
		$model			 = OperatorApiTracking::model()->findByPk($elgId);
		$s3data			 = $model->oat_s3_data;

		if ($model->oat_s3_data != '' && $model->oat_s3_data != null)
		{
			$s3data		 = $model->oat_s3_data;
			$spaceFile	 = Stub\common\SpaceFile::populate($s3data);
			$url		 = $spaceFile->getURL();
			echo $url . "URL==============><br><br>";

			if ($model->oat_s3_data != '' && ($file = $model->getSpaceFile()) != null)
			{
				$body = $file->getContents();
			}

			echo "<pre>";
			print_r($body);
			exit();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('operatorlist', array('model' => $model), false, false);
	}

	public function actionMbkg()
	{

		Yii::app()->params['enableTracking'] = false;
		$this->pageTitle					 = "";
		$setPass							 = "11112019";
		$chkSession							 = $_COOKIE['mbkg'];
		$error								 = 0;
		$request							 = $_REQUEST['sort'];
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('mbkg', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
			if ($error > 0)
			{
				goto result;
			}
		}
		$booksub			 = new BookingSub();
		$booksub->from_date	 = date("Y-m-d") . ' 00:00:00';
		$booksub->to_date	 = date("Y-m-d") . ' 23:59:59';
		$req				 = Yii::app()->request;
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
		$monthWiseDaily	 = Booking::monthWiseDailyMetric(DateTimeFormat::DatePickerToDate($date1));
		$nowDate		 = date('d/m/Y');
		$booksub->date	 = $date1;
		$bkgmodel		 = $booksub->getBookingCount(11);
		$bkgassigned	 = BookingTrail::getAssignmentStats($booksub->from_date, $booksub->to_date);
		$bookings		 = $booksub->getBookingsByToday();
		$fromDate		 = DateTimeFormat::DatePickerToDate($date1) . " 00:00:00";
		$todate			 = DateTimeFormat::DatePickerToDate($date1) . " " . date("H:i:s");
		if (date('d/m/Y') != $booksub->date)
		{
			$bookings['lastRefeshDate']	 = DateTimeFormat::DatePickerToDate($date1) . " 23:59:59";
			$bookingsWK_0				 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate)) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate)));
		}
		$bookingsWK_1	 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate . '-7 days')) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate . '-7 days')));
		$bookingsWK_2	 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate . '-14 days')) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate . '-14 days')));
		$bookingsWK_3	 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate . '-21 days')) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate . '-21 days')));
		$bookingsWK_4	 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate . '-28 days')) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate . '-28 days')));
		$bookingsWK_5	 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate . '-35 days')) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate . '-35 days')));
		$bookingsWK_6	 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate . '-42 days')) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate . '-42 days')));
		if (date('d/m/Y') != $booksub->date)
		{
			$bookings['lastRefeshDate']	 = DateTimeFormat::DatePickerToDate($date1) . " 23:59:59";
			$bookingsWK_0				 = $booksub->getPastBookings(date('Y-m-d', strtotime($fromDate)) . ' 00:00:00', date('Y-m-d H:i:s', strtotime($todate)));
			$bookingWiseArr				 = array(
				0	 => $bookings,
				1	 => $bookingsWK_0,
				2	 => $bookingsWK_1,
				3	 => $bookingsWK_2,
				4	 => $bookingsWK_3,
				5	 => $bookingsWK_4,
				6	 => $bookingsWK_5,
				7	 => $bookingsWK_6
			);
		}
		else
		{
			$bookingWiseArr = array(
				0	 => $bookings,
				2	 => $bookingsWK_1,
				3	 => $bookingsWK_2,
				4	 => $bookingsWK_3,
				5	 => $bookingsWK_4,
				6	 => $bookingsWK_5,
				7	 => $bookingsWK_6
			);
		}


		$regionWiseData	 = $booksub->getRegionWiseTodaysBooking();
		$dataProvider2	 = $booksub->getCarCategoryTodaysBooking();
		$dataProvider3	 = $booksub->getServiceTierTodaysBooking();
		$dataProvider4	 = $booksub->getServiceTypeTodaysBooking();
		$dataProvider5	 = $booksub->getMarginByServiceType();
		$dataProvider6	 = $booksub->getTodayScheduledPickup($booksub->from_date, $booksub->to_date);
		$dataProvider7	 = $booksub->getTodayBookingCancellation($booksub->from_date, $booksub->to_date);
		result:
		$this->render('today_bookings2', array('dataProvider2'	 => $dataProvider2, 'dataProvider3'	 => $dataProvider3, 'dataProvider4'	 => $dataProvider4, 'dataProvider5'	 => $dataProvider5,
			'booksub'		 => $booksub,
			'bkgmodel'		 => $bkgmodel,
			'dataProvider6'	 => $dataProvider6,
			'dataProvider7'	 => $dataProvider7,
			'bkgassigned'	 => $bkgassigned,
			'bookings'		 => $bookings,
			'bookingWiseArr' => $bookingWiseArr,
			'regionWiseData' => $regionWiseData,
			'error'			 => $error,
			'typecount'		 => $typecount,
			'monthWiseDaily' => $monthWiseDaily,
			'chkSession'	 => $chkSession), null, true);
	}

	public function actionApplyGozoCoins()
	{
		BookingInvoice::updatePromoCoins();
	}

	public function actionApplyPromoCoins()
	{
		BookingInvoice::updateCashbackCoins();
	}

}
