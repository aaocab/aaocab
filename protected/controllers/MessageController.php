<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class MessageController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $newHome		 = '';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

//public $layout = '//layouts/column2';

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
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

//    public function behaviors() {
//        return array(
//            'seo' => array('class' => 'application.components.SeoControllerBehavior'),
//        );
//    }
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('gozocoinsremove', 'canbooking', 'verifybooking', 'ajaxverification', 'confirmbookingemail', 'receipt', 'edit', 'editnew', 'creditapply'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('receive', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'), 'users'		 => array('admin'),
			),
			['allow', 'actions' => ['invoice'], 'users' => ['*']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function()
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});
	}

	public function actionIndex()
	{
		$this->redirect('/');
	}

	public function actionReceive()
	{
		$success		 = true;
		$message		 = Yii::app()->request->getParam('message');
		$mobilenumber	 = Yii::app()->request->getParam('mobilenumber');

		Logger::profile('MESSAGE RECEIVED');
		Logger::profile('MESSAGE == ' . $message);
		Logger::profile('MOBILE NUMBER == ' . $mobilenumber);

//		$mobilenumber	 = "8240276626";
//		$message		 = "STOP 1588767 125926 101";
//		$message = "STOP 1587274 165196 565656";
//		$message = "START 1587274 565656"; //WITHOUT OTP REQUIERED
//		$message = "STOP 1587447 586396"; //WITHOUT OTP REQUIERED
//		$message = "START 1587447 586396 "; //WITHOUT (OTP REQUIERED & ODOMETER)
//		$message		 = "STOP 1587274"; //WITHOUT (OTP REQUIERED & ODOMETER)

		$dt = Yii::app()->request->getParam('receivedon');

		$IP			 = \Filter::getUserIP();
		$req		 = [
			'message'		 => $message,
			'mobilenumber'	 => $mobilenumber,
			'receivedon'	 => $dt,
			'IP'			 => $IP
		];
		Logger::profile("Data received: " . json_encode($req));
		$receiveDt	 = date("Y-m-d H:i:s", strtotime($dt));
		$message	 = trim(preg_replace('/\s+/', ' ', $message));
		$var		 = explode(" ", trim($message));
		$arr		 = array_filter($var);
		$desc		 = '';
		$platform	 = TripOtplog::Platform_SMS;

		Logger::profile('PROCESSED MESSAGE == ' . $message);

		if (trim($message) == '')
		{
			$success = false;
			$desc	 = "Empty message. Data not saved";
			Logger::profile('BLANK MESSAGE == ' . $desc);
		}
		else
		{
			$phoneNumber = $mobilenumber;
			if (strlen($mobilenumber) != 10)
			{
				if (strlen($mobilenumber) > 10)
				{
					$phoneNumber = substr($mobilenumber, -10);
				}
			}

			Logger::profile('PHONE NUMBER == ' . $phoneNumber);

			$res = [];
			Logger::profile('COUNT == ' . count($arr));
			if (count($arr) > 0 && count($arr) < 3)
			{
				Logger::profile('COUNT IS LESS THAN 3');
				$otp			 = [];
				$otpBkgArrQry	 = [];
				foreach ($arr as $arrVal)
				{
					$arrVal	 = str_replace("<", "", $arrVal);
					$arrVal	 = str_replace(">", "", $arrVal);
					$arrVal	 = str_replace("(", "", $arrVal);
					$arrVal	 = str_replace(")", "", $arrVal);
					$arrVal	 = str_replace(" ", "", $arrVal);
					$arrVal	 = str_replace("+", "", $arrVal);
					$arrVal	 = str_replace('"', "", $arrVal);
					$arrVal	 = str_replace("'", "", $arrVal);

					if (strlen($arrVal) == 6)
					{
						$otp[] = $arrVal;
					}
					$otpBkgArrQry[] = "bkg_booking_id = '%$arrVal%'";
				}

				Logger::profile("OTP ARRAY == " . json_encode($otp));
				Logger::profile("OTP BKG ARRAY == " . json_encode($otpBkgArrQry));

				if (count($otp) > 0)
				{
					$otpStr = implode(',', $otp);

					$otpBkg	 = implode(' OR ', $otpBkgArrQry);
					$otpSql	 = "SELECT bkg_id,bcb_driver_phone,bkg_trip_otp
					FROM   booking_track btk
					JOIN booking bkg ON btk.btk_bkg_id = bkg.bkg_id 
					JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
					WHERE   DATE_ADD(NOW(),INTERVAL 8 HOUR) >  bkg_pickup_date  
					AND bkg_status = 5
					AND  DATE_SUB(NOW(),INTERVAL 1 HOUR) <  bkg_pickup_date 
					AND bkg_trip_otp IN ($otpStr) AND (bcb_driver_phone = $phoneNumber OR $otpBkg)";
					$res	 = Yii::app()->db->createCommand($otpSql)->queryRow();
				}
				if (count($arr) == 2)
				{
					if (strtolower($arr[0]) == 'start')
					{
						$sqlStr = 'DATE_ADD(NOW(),INTERVAL 8 HOUR) >  bkg_pickup_date  					
					AND  DATE_SUB(NOW(),INTERVAL 1 HOUR) <  bkg_pickup_date ';
					}
					if (strtolower($arr[0]) == 'stop')
					{
						$sqlStr = 'DATE_ADD(bkg_pickup_date ,bkg_trip_duration MINUTE) > NOW()   					
					AND  DATE_SUB(DATE_ADD(bkg_pickup_date ,bkg_trip_duration MINUTE),INTERVAL 1 HOUR) <  NOW()  ';
					}

					$bkg	 = $arr[1];
					$otpSql	 = "SELECT bkg_id,bcb_driver_phone,bkg_trip_otp,bkg_trip_otp_required
					FROM   booking_track btk
					JOIN booking bkg ON btk.btk_bkg_id = bkg.bkg_id 
					JOIN booking_pref bpr ON bpr.bpr_bkg_id = bkg.bkg_id 
					JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id 
					WHERE   
					$sqlStr
					AND bkg_status = 5
					AND (bkg_id = $bkg OR bkg_booking_id = '$bkg' ) AND (bcb_driver_phone = $phoneNumber) AND bkg_trip_otp_required = 0";
					$res	 = Yii::app()->db->createCommand($otpSql)->queryRow();
				}
			}

			Logger::profile("COUNT ARRAY == " . count($arr));
			if ((count($arr) < 3 || count($arr) > 4) && (count($res) == 0 || !$res))
			{
				Logger::profile("COUNT IS LESS THAN 4 AND GREATER THAN 4");

				$success			 = false;
				$tmodel				 = TripOtplog::model()->add('', $platform, '', $message, $phoneNumber);
				$tmodel->trl_status	 = 3;
				$tmodel->save();
				$msgCom				 = new smsWrapper();
				$desc				 = "Wrong number of parameter for tripLogTrip id: " . $tmodel->trl_id;
				Logger::create($desc);
				$msgCom->informDriverInvalidSMSFormat($phoneNumber);
			}
			else
			{
				Logger::profile("ELSE RES == " . json_encode($res));
				Logger::profile("ELSE ARR == " . json_encode($arr));

				if ($res)
				{
					$bkg_booking_id_6	 = $res['bkg_id'];
					$tripotp			 = $res['bkg_trip_otp'];
				}
				else
				{
					$bkg_booking_id_6	 = $arr[1];
					$tripotp			 = $arr[2];
				}

				Logger::profile("bkg_booking_id_6 == " . $bkg_booking_id_6);

				#$bookingModel	 = Booking::model()->getbyBookingExt($bkg_booking_id_6);
				$bookingModel	 = Booking::model()->findByPk($bkg_booking_id_6);
				$bkgBookingCode	 = $bookingModel->bkg_booking_id;
				$desc			 = "Trip START/STOP service is not allowed through SMS in this booking ($bkgBookingCode). Please use  Driver APP ";
				if (!$bookingModel->bkgPref->checkValidSMSTripStartRegion($phoneNumber, $desc))
				{
					$success = false;
					goto result;
				}
//				$userInfo		 = UserInfo::getInstance();
				$userInfo = UserInfo::model(UserInfo::TYPE_DRIVER, $bookingModel->bkgBcb->bcb_driver_id);
				if ($bookingModel != '')
				{
					Logger::profile("BOOKING MODEL FOUND == " . $bookingModel->bkgPref->bkg_trip_otp_required);

					$otpRequired = $bookingModel->bkgPref->bkg_trip_otp_required;
					if ($otpRequired != 1)
					{
						$tripotp	 = '';
						$odoReading	 = $arr[2];
					}
					if (strtolower($arr[0]) == 'start')
					{

						$odoStartReading = $odoReading;
						$returnSet		 = $bookingModel->bkgTrack->startTrip($platform, $tripotp, $message, $phoneNumber, $odoStartReading);
					}
					if (strtolower($arr[0]) == 'stop')
					{
						$odoStopReading	 = $odoReading;
						$returnSet		 = $bookingModel->bkgTrack->stopTrip($platform, $tripotp, $message, $phoneNumber, $odoStopReading, $userInfo);
					}

					$success = $returnSet->getStatus();
					$desc	 = $returnSet->getData()['message'];
				}
				else
				{
					Logger::profile("BOOKING MODEL NOT FOUND");

					$success = false;
					$desc	 = "Booking id / Trip Otp is invalid for  Booking id: " . $bkg_booking_id_6;
					Logger::create($desc);
					$msgCom	 = new smsWrapper();
					$msgCom->informDriverWrongBookingidinSMS($phoneNumber);
				}
			}
		}
		result:
		$result = [
			'success'	 => $success,
			'message'	 => $desc
		];

		Logger::profile("RESULT == " . json_encode($result));


//		if($errStr!='' && !$success){
//			$result['error']=$errStr;
//		}
		Logger::create("=================End of log =================");
		echo json_encode($result);
	}

}
