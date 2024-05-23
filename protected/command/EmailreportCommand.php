<?php

class EmailreportCommand extends BaseCommand
{

	public function actionEmail()
	{

		$action				 = "email";
		$req				 = new Booking();
		$results			 = $req->getBusinessPastDays();
		$emailWrapper		 = new emailWrapper();
		$results['action']	 = $action;
		$emailWrapper->emailReport($results);
	}

	public function actionLead()
	{
		$req			 = new BookingTemp();
		$results		 = $req->getLeadReport();
		$emailWrapper	 = new emailWrapper();
		$emailWrapper->leadReport($results);
	}

	public function actionHowemail()
	{
		//  echo "dfgdg";exit;
		// $action = "email";
		$booking = new Booking();
		$results = $booking->getHowWeDoEMail();
		foreach ($results as $key => $value)
		{
			$emailWrapper = new emailWrapper();
			$emailWrapper->markCompleteCommand($value['bkg_id']);
			echo "\n";
			echo 'Email sent to ' . $value['bkg_user_email'];
			echo "\n";
		}
	}

	public function actionSnapshot()
	{

		$emailWrapper = new emailWrapper();
		$emailWrapper->snapshotReport();
	}

	public function actionDaily()
	{
		Logger::create("command.emailreport.daily start", CLogger::LEVEL_PROFILE);
		$emailWrapper = new emailWrapper();
		$emailWrapper->dailyReport();
		Logger::create("command.emailreport.daily end", CLogger::LEVEL_PROFILE);
	}

	public function actionCancelDaily()
	{
		Logger::create("command.emailreport.cancelDaily start", CLogger::LEVEL_PROFILE);
		$emailWrapper = new emailWrapper();
		$emailWrapper->cancellationDaily();
		Logger::create("command.emailreport.cancelDaily end", CLogger::LEVEL_PROFILE);
	}

	public function actionMonthlyreport()
	{

		//Yii::setPathOfAlias($alias, $path);
		// Get cURL resource
		$url				 = "https://www.gozocabs.com/admpnl/report/monthlyreport?email=1";
		$curl				 = curl_init($url);
		// Set some options - we are passing in a useragent too here
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$responseParamList	 = curl_exec($curl);
		echo $responseParamList;

		// Close request to clear up some resources
		curl_close($curl);
	}

	public function actionRoutePerformance()
	{
		/* @var $modelsub BookingSub */
		Logger::create("command.emailreport.routePerformance start", CLogger::LEVEL_PROFILE);
		$modelsub	 = new BookingSub();
		$result		 = $modelsub->getRoutePerformanceReport();
		Logger::create("command.emailreport.routePerformance end", CLogger::LEVEL_PROFILE);

		//$emailWrapper = new emailWrapper();
		//$emailWrapper->zonalReport($result);
	}

	public function actionAccountReceivable()
	{
		Logger::create("command.emailreport.accountReceivable start", CLogger::LEVEL_PROFILE);
		$emailWrapper = new emailWrapper();
		$emailWrapper->accountReceivableWeekly();
		Logger::create("command.emailreport.accountReceivable end", CLogger::LEVEL_PROFILE);
	}

	public function actionInactiveMails()
	{
		$emailLog = new EmailLog();
		$emailLog->sentInactiveMails();
	}

	public function actionDailyBookingPickup()
	{
		Logger::create("command.emailreport.DailyBookingbyPickup start", CLogger::LEVEL_PROFILE);
		$emailCom = new emailWrapper();
		$emailCom->reportBookingMail();
		Logger::create("command.emailreport.DailyBookingbyPickup end", CLogger::LEVEL_PROFILE);
	}

	public function actionBookingCompletedToday2()
	{
		Logger::create("command.emailreport.BookingsCompleted start", CLogger::LEVEL_PROFILE);
		$emailCom = new emailWrapper;
		$emailCom->reportCompletedBookingReport();
		Logger::create("command.emailreport.BookingsCompleted end", CLogger::LEVEL_PROFILE);
	}

}
