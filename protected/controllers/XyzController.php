<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class XyzController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $pageHeader = '';

	public function filters()
	{
		return array(
			array(
				'CHttpCacheFilter + country',
			),
		);
	}

	public function actionTbkg()
	{
		$this->layout						 = "head1";
		Yii::app()->params['enableTracking'] = false;
		$setPass							 = "19917";
		$chkSession							 = $_COOKIE['tbkg'];
		$error								 = 0;
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('tbkg', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
		}
		$model1			 = Booking::model()->getRouteCount();
		$model			 = Booking::model()->getTodaysBookings();
		/* @var $booksub BookingSub */
		$booksub		 = new BookingSub();
		$bookings		 = $booksub->getBookingsByToday();
		$regionWiseData	 = $booksub->getRegionWiseTodaysBooking();
		$modelList		 = new CArrayDataProvider($model, array('pagination' => array('pageSize' => 50),));
		$models			 = $modelList->getData();
		$this->render('today_bookings', array('model'			 => $models,
			'usersList'		 => $modelList,
			'model1'		 => $model1,
			'bookings'		 => $bookings, 'regionWiseData' => $regionWiseData,
			'error'			 => $error,
			'chkSession'	 => $chkSession), false, true);
	}

	public function actionMbkg0()
	{
		$this->layout						 = "head1";
		Yii::app()->params['enableTracking'] = false;
		$setPass							 = "11112019";
		$chkSession							 = $_COOKIE['mbkg'];
		$error								 = 0;
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
		$bkgmodel		 = BookingSub::model()->getBookingCount(11);
		$model1			 = Booking::model()->getRouteCount();
		$model			 = Booking::model()->getTodaysBookings();
		$fromDate		 = date("Y-m-d") . ' 00:00:00';
		$toDate			 = date("Y-m-d") . ' 23:59:59';
		$bkgassigned	 = BookingTrail::getAssignmentStats($fromDate, $toDate);
		/* @var $booksub BookingSub */
		$booksub		 = new BookingSub();
		$bookings		 = $booksub->getBookingsByToday();
		$regionWiseData	 = $booksub->getRegionWiseTodaysBooking();
		$modelList		 = new CArrayDataProvider($model, array('pagination' => array('pageSize' => 50),));
		$models			 = $modelList->getData();
		result:
		$this->render('today_bookings1', array(
			'model'			 => $models,
			'bkgassigned'	 => $bkgassigned,
			'usersList'		 => $modelList,
			'model1'		 => $model1,
			'bkgmodel'		 => $bkgmodel,
			'bookings'		 => $bookings,
			'regionWiseData' => $regionWiseData,
			'error'			 => $error,
			'chkSession'	 => $chkSession), false, true);
	}

	public function actionMbkg()
	{
		Yii::app()->request->redirect('/admpnl/xyz/mbkg', true, 301);
	}

	public function actionMbkg2()
	{
		$this->layout						 = "head1";
		Yii::app()->params['enableTracking'] = false;
		$setPass							 = "11112019";
		$chkSession							 = $_COOKIE['mbkg'];
		$error								 = 0;
		$request							 = $_REQUEST['sort'];
		$sourceZonecount					 = 0;
		$destZonecount						 = 0;
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
		$fromDate	 = date("Y-m-d") . ' 00:00:00';
		$toDate		 = date("Y-m-d") . ' 23:59:59';
		/* @var $booksub BookingSub */
		$booksub	 = new BookingSub();
		if ($request == 'cntBkg1')
		{
			$data = $booksub->getSourceZoneTodaysBooking();
		}
		else if ($request == 'cntBkg2')
		{
			$data1 = $booksub->getDestZoneTodaysBooking();
		}
		else
		{
			$data	 = $booksub->getSourceZoneTodaysBooking();
			$data1	 = $booksub->getDestZoneTodaysBooking();
		}
		$dataProvider	 = $data[0];
		$sourceZonecount = $data[1];
		$datetime		 = $data[2];
		$dataProvider1	 = $data1[0];
		$destZonecount	 = $data1[1];

		result:
		$this->render('zone_wise_report', array('dataProvider'	 => $dataProvider, 'count'			 => $sourceZonecount, 'count1'		 => $destZonecount,
			'dataProvider1'	 => $dataProvider1,
			'error'			 => $error,
			'lastRefeshDate' => $datetime,
			'chkSession'	 => $chkSession), false, true);
	}

	public function actionMrpt()
	{
		$this->layout						 = "head";
		$this->pageTitle					 = 'Hire outstation cabs at affordable prices';
		Yii::app()->theme					 = "";
		Yii::app()->params['enableTracking'] = false;
		$setPass							 = Yii::app()->params['rptPass'];
		$chkSession							 = $_COOKIE['cbkg'];
		$error								 = 0;
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('cbkg', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
		}
		if ($error == 0)
		{
			$results	 = [];
			$results[0]	 = BookingSub::model()->getBookingCount(1);
			$results[1]	 = BookingSub::model()->getBookingCount(2);
			$results[2]	 = BookingSub::model()->getBookingCount(3);
			$results[3]	 = BookingSub::model()->getBookingCount(4);
		}
		$this->render('count_bookings', array('countResults' => $results, 'error' => $error, 'chkSession' => $chkSession), false, true);
	}

	public function actionMrptTopRoutes()
	{
		$this->layout						 = "head";
		$this->pageTitle					 = 'Hire outstation cabs at affordable prices for top routes';
		Yii::app()->theme					 = "";
		Yii::app()->params['enableTracking'] = false;
		$setPass							 = Yii::app()->params['rptPass'];
		$chkSession							 = $_COOKIE['cbkg'];
		$error								 = 0;
		if ($chkSession != md5($setPass))
		{
			$error	 = 1;
			$pass	 = Yii::app()->request->getParam('psw');
			if ($pass == $setPass)
			{
				setcookie('cbkg', md5($setPass), time() + 60 * 60 * 24);
				$error		 = 0;
				$chkSession	 = $setPass;
			}
			else if ($pass != '')
			{
				$error = 2;
			}
		}
		if ($error == 0)
		{
			$model			 = AgentApiTracking::getTopMmtRoutes(1, 300);
			$model1			 = AgentApiTracking::getTopMmtRoutes(0, 400);
			$modelCtNotFound = AgentApiTracking::getTopCityNotFound();
			$modelRtNotFound = AgentApiTracking::getTopRouteNotFound();
			$modelList		 = new CArrayDataProvider($model, array('pagination' => array('pageSize' => 150),));
			$modelList1		 = new CArrayDataProvider($model1, array('pagination' => array('pageSize' => 150),));
			$models			 = $modelList->getData();
			$models1		 = $modelList1->getData();
		}
		$this->render('count_bookings_top_routes', array('model' => $models, 'model1' => $models1, 'modelCtNotFound' => $modelCtNotFound, 'modelRtNotFound' => $modelRtNotFound, 'error' => $error, 'chkSession' => $chkSession), false, true);
	}

	public function actionLreport()
	{
		$this->layout	 = 'head';
		$this->pageTitle = 'Lead Report';
		$model			 = new BookingTemp();
		$dataProvider	 = $model->feedbackReport('', '', 1440);
		$this->render('lead_report', array('dataProvider' => $dataProvider), false, true);
	}

	public function actionTest1()
	{
		$sql = "SELECT c0.cty_id as tc_id, c0.cty_display_name as tc_name, 
					c0.cty_bounds as large, c1.cty_bounds as small,
					c1.cty_id as nc_id, c1.cty_display_name nc_name, CalcDistance(c1.cty_lat, c1.cty_long,c0.cty_lat, c0.cty_long) as distance 
				FROM  cities c0 
				INNER JOIN cities c1 ON c0.cty_id<c1.cty_id AND c0.cty_id BETWEEN 30000 AND 32000 AND c1.cty_id<36000 AND c1.cty_lat BETWEEN (c0.cty_lat-0.06) AND (c0.cty_lat+0.06) AND c1.cty_long BETWEEN (c0.cty_long-0.06) AND (c0.cty_long+0.06)
					AND c0.cty_service_active=1 AND c1.cty_active=1  AND c0.cty_types NOT LIKE '%administrative_area_level%'
					AND checkBounds(c0.cty_bounds, c1.cty_lat, c1.cty_long, 0.2) 
					AND checkBounds(c0.cty_bounds, JSON_VALUE(c1.cty_bounds, \"$.northeast.lat\"),JSON_VALUE(c1.cty_bounds, \"$.northeast.lng\"),0.2)
					AND checkBounds(c0.cty_bounds, JSON_VALUE(c1.cty_bounds, \"$.southwest.lat\"),JSON_VALUE(c1.cty_bounds, \"$.southwest.lng\"),0.2)
					AND c0.cty_state_id=c1.cty_state_id
						AND CalcDistance(c1.cty_lat, c1.cty_long,c0.cty_lat, c0.cty_long)<15 AND ((c1.cty_is_airport=1 AND c0.cty_is_airport=1) OR c1.cty_is_airport=0)
				WHERE 1";
		$res = DBUtil::query($sql);

		foreach ($res as $row)
		{
			$largeBounds = $row["large"];
			$smallBounds = $row["small"];
			$check		 = Filter::checkBoundsWithinBounds($largeBounds, $smallBounds);
			if ($check)
			{
				echo "\n<br/>{$row["tc_name"]} : {$row["nc_name"]} - success";
			}
			else
			{
				echo "\n<br/>{$row["tc_name"]} : {$row["nc_name"]} - fail";
			}
		}
	}

	public function actionPushCabInfo($bkgid)
	{
		$success = BookingCab::model()->pushPartnerCabDriver($bkgid);
	}

	public function actionCanb()
	{
		$bkgid	 = Yii::app()->request->getParam('bkg');
		$success = BookingScheduleEvent::processRefundEvent($bkgid);
	}

}
