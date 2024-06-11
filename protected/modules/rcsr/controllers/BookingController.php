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
		return array(
			['allow', 'actions' => ['create', 'confirmmobile', 'copybooking', 'multicityform', 'multicityvalidate'], 'roles' => ['bookingAdd']],
			['allow', 'actions' => ['edit', 'edituserinfo'], 'roles' => ['bookingEdit']],
			['allow', 'actions' => ['list'], 'roles' => ['bookingList']],
			['allow', 'actions' => ['delbooking'], 'roles' => ['bookingDelete']],
			['allow', 'actions' => ['canbooking'], 'roles' => ['bookingCancel']],
			['allow', 'actions' => ['completebooking'], 'roles' => ['bookingComplete']],
			['allow', 'actions' => ['updateamtnmarkcomp'], 'roles' => ['bookingCompletewithamount']],
			['allow', 'actions' => ['converttolead'], 'roles' => ['bookingCopytolead']],
			['allow', 'actions' => ['assigncabdriver'], 'roles' => ['bookingCabDetails']],
			['allow', 'actions' => ['assignvendor', 'showvendor', 'listbyids', 'listbyvhc'], 'roles' => ['bookingAssignvendor']],
			['allow', 'actions' => ['receipt'], 'roles' => ['bookingReceipt']],
			['allow', 'actions' => ['related'], 'roles' => ['bookingRelated']],
			['allow', 'actions' => ['addremarks'], 'roles' => ['bookingRemarks']],
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
			//    ['allow', 'actions' => ['updateRateByOla'], 'roles' => ['updateRateByOla']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('selectdriver', 'export', 'getcontacts', 'pendinglist', 'createTrip', 'unmatchedBkgId', 'matchtrip', 'ownmatchtrip', 'updatevendoramount', 'modifyvendoramount', 'pendingaction', 'cancelPendingBooking', 'getamountbyvehicle1', 'getamount', 'getcarmodel', 'sendpaymentlink', 'sendconfirmation', 'updatepaymentexpiry', 'lockpaymentoption',
					'getdriverdetails', 'getvehicledetails', 'view', 'getamountbyvehicle', 'showcsr', 'assigncsr', 'showlog', 'sendsmstodriver', 'addtransaction', 'reconfirmBooking', 'reconfirmBookingSms',
					'convert', 'addaccountingremark', 'accountflag', 'getdrivers', 'getcabs', 'getcitiesname', 'feedbackform', 'match', 'matchList', 'matchview', 'matchassign', 'assigncabdriver', 'escalationremarks', 'addFollowup', 'completefollowup', 'triprelatedbooking', 'sendcabdriverinfo',
					'upsellremarks', 'profitabilityremarks', 'getcabdetails', 'getdrivercabdetails', 'checkcabtimeoverlap',
					'inv', 'listlogdetails', 'verifycustomermarked', 'markedbadmessage', 'markedbadlist',
					'resetmarkedbad', 'addmarkremark', 'quotation1', 'quotation2', 'uploads', 'setcompletebooking',
					'flightstatus', 'blockmessage', 'cancellations', 'updateRateByOla', 'chat', 'chatLog'),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['mffreport', 'generateInvoiceForBooking'], 'users' => ['*']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
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
		$time				 = Filter::getExecutionTime();
		$GLOBALS['time'][1]	 = $time;
		$tab				 = Yii::app()->request->getParam('tab'); //$_GET['tab'];
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$bid				 = Yii::app()->request->getParam('bid', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$model				 = new Booking();
		$model->bkg_source	 = Yii::app()->request->getParam('source', '');
		$searchid			 = Yii::app()->request->getParam('searchid');
		$params1			 = array_filter($_GET + $_POST);
		if (!isset($params1['sort']))
		{
			//  $model->bkg_pickup_date1 = ($model->bkg_source == '') ? date('Y-m-d', strtotime('-1 days')) : '';
			$model->bkg_pickup_date1 = ($model->bkg_source == '') ? date('Y-m-d', strtotime('-1 month')) : '';
			$model->bkg_pickup_date2 = ($model->bkg_source == '') ? date('Y-m-d', strtotime('+11 month')) : '';
		}
		if ($searchid != '')
		{
			$model->bkg_id = $searchid;
		}
		if (isset($_REQUEST['Booking']) || isset($_REQUEST['booking_search']))
		{
			$arr						 = Yii::app()->request->getParam('Booking');
			$model->attributes			 = $arr;
			$model->corporate_id		 = $arr['corporate_id'];
			$model->bkg_create_date1	 = $arr['bkg_create_date1'];
			$model->bkg_create_date2	 = $arr['bkg_create_date2'];
			$model->bkg_pickup_date1	 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $arr['bkg_pickup_date2'];
			$model->bkg_vehicle_type_id	 = $arr['bkg_vehicle_type_id'];
		}
		/* @var $model Booking */
		/* @var $dataProvider CActiveDataProvider */
		if (isset($params1['Booking']))
		{
			$params1['Booking']		 = array_filter($params1['Booking']);
			$model->bkg_pickup_date1 = $params1['Booking']['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $params1['Booking']['bkg_pickup_date2'];
		}
		$params			 = array_filter($params1);
		$bookingStatus	 = Booking::model()->getActiveBookingStatus();
		$time			 = Filter::getExecutionTime();

		$GLOBALS['time'][2]	 = $time;
		unset($bookingStatus['8']);
		//array_push($bookingStatus,array(9 => 'Convert To Lead'));
		$leadStatus			 = ['0' => 'All'] + $bookingStatus;
		unset($leadStatus['8']);
		//print_r($leadStatus);exit();
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$GLOBALS['time'][3]	 = [];
		$model->bkg_status	 = null;
		$statusCount		 = $model->getStatusCount(Yii::app()->user->getId());
		$statusCount[0]		 = array_sum($statusCount);
		foreach ($leadStatus as $bid => $bval)
		{
			$pageSize											 = (!in_array($bid, [2, 3, 5])) ? 50 : 30;
			$model->bkg_status									 = $bid;
			$dataProvider[$bid]									 = [];
			$dataProvider[$bid]["label"]						 = $bval;
			$dataProvider[$bid]["labelCount"]					 = $bval;
			$dataProvider[$bid]["data"]							 = $model->fetchList($pageSize, 'data', Yii::app()->user->getId(), $statusCount[$bid]);
			$params['tab']										 = $bid;
			$dataProvider[$bid]["data"]->getPagination()->params = $params;
			$dataProvider[$bid]["data"]->getSort()->params		 = $params;
			//$dataProvider[$bid]["Oct15"] = $model->addCriteriaFromOct15($dataProvider[$bid]["data"]);
			//unset($params['Booking_page']);
			$time												 = Filter::getExecutionTime();

			$GLOBALS['time'][3][$bid] = $time;
		}

		if ($outputJs && $tab != '')
		{
			$labelArr = [];
			foreach ($dataProvider as $key => $provider)
			{
				$label = '';
				if (in_array($key, [6, 7]))
				{
//            $count = $provider['Oct15']->getTotalItemCount();
//            $label = "$count/";
				}
				$labelArr[$key] = $label . $provider['data']->getTotalItemCount();
			}
			$time = Filter::getExecutionTime();

			$GLOBALS['time'][4] = $time;
			$this->renderPartial("grid", ['status' => $tab, 'provider' => $dataProvider[$tab], 'labels' => $labelArr], false, true);
		}
		else
		{
			$tabFilter	 = array_filter([1, 2, 3, 5, $tab], 'is_numeric');
			$method		 = "render" . (($outputJs) ? "Partial" : "");
			$this->$method('list', array('tab' => $tab, 'dataProvider' => $dataProvider, 'model' => $model, 'tabFilter' => $tabFilter, 'formHide' => $formHide, 'lbid' => $bid), false, $outputJs);
		}
	}

	public function actionlistlogdetails()
	{
		$refId			 = Yii::app()->request->getParam('refId');
		$eventId		 = Yii::app()->request->getParam('eventId');
		/* $model Booking */
		$model			 = new Booking();
		$dataProvider	 = $model->fetchLogDetailsByRefId($refId, $eventId);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$methodUrl		 = '';
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
		endswitch;
		$this->$method($methodUrl, array('dataProvider' => $dataProvider, 'refId' => $refId), false, $outputJs);
	}

	public function actionCreate()
	{
		$this->pageTitle = "New Booking";
		$bkid			 = Yii::app()->request->getParam('booking_id');
		$leadid			 = Yii::app()->request->getParam('lead_id');
		$model			 = new Booking();
		$success		 = false;
		if (!isset($_POST['Booking']))
		{
			if ($bkid > 0)
			{
				$model = $this->actionCopybooking($bkid);
			}
			if ($leadid > 0)
			{
				$model			 = $this->actionLeadtoBooking($leadid);
				$model->lead_id	 = $leadid;
				$routeModel		 = $model->bookingRoutes;
				$Arrmulticity	 = [];
				foreach ($routeModel as $key => $value)
				{
					$Arrmulticity[$key] = ["pickup_city"		 => $value->brt_from_city_id,
						"pickup_city_name"	 => $value->brtFromCity->cty_name,
						"drop_city"			 => $value->brt_to_city_id,
						"drop_city_name"	 => $value->brtToCity->cty_name,
						"pickup_address"	 => $value->brt_from_location,
						"drop_address"		 => $value->brt_to_location,
						"date"				 => $value->brt_pickup_datetime,
						"distance"			 => $value->brt_trip_distance,
						"duration"			 => $value->brt_trip_duration,
						"pickup_date"		 => DateTimeFormat::DateTimeToDatePicker($value->brt_pickup_datetime),
						"pickup_time"		 => date('h:i A', $value->brt_pickup_datetime),
					];
				}
				$multijsondata	 = json_encode($Arrmulticity);
				$multijsondata	 = json_decode($multijsondata);
				if ($multijsondata != '')
				{
					$model->preData = $multijsondata;
				}
			}
		}
		$oldModel = clone $model;
		if (isset($_POST['Booking']))
		{

			$model				 = new Booking('admininsert');
			unset($_POST['Booking']['bkg_id']);
			$postData			 = Yii::app()->request->getParam('Booking');
			$model->attributes	 = $postData;

			$routeProcessed = $postData['routeProcessed'];

//            if (Yii::app()->request->getParam('Booking')['corporate_id'] > 0) {
//                $model->bkg_agent_id = Yii::app()->request->getParam('Booking')['corporate_id'];
//            }
			if ($_POST['agentnotifydata'] != '' && $_POST['agentnotifydata'] != null && $_POST['agentnotifydata'] != 'null')
			{
				$model->agentNotifyData = json_decode($_POST['agentnotifydata'], true);
			}
			$model->bkg_tags	 = implode(', ', $_POST['bkg_tags']);
			$model->bkg_platform = Booking::Platform_Admin;
			if ($model->bkg_info_source == '')
			{
				$model->bkg_info_source = 'Others';
			}
			$model->bkg_user_ip		 = \Filter::getUserIP();
			$cityinfo				 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
			$model->bkg_user_city	 = $cityinfo['city'];
			$model->bkg_user_country = $cityinfo['country'];
			$model->bkg_user_device	 = $_SERVER['HTTP_USER_AGENT'];
			$model->bkg_create_date	 = new CDbExpression('NOW()');
			$isCod					 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 3);
			if (!in_array($model->bkg_status, [2, 3, 5, 6]) && $isCod)
			{
				// $model->bkg_status = 2;
				$model->bkg_status	 = 1;
				$usersModel			 = Users::model()->find('usr_email=:email', ['email' => $model->bkg_user_email]);
				$usersModel1		 = Users::model()->find('usr_mobile=:phone', ['phone' => $model->bkg_contact_no]);
				if ($model->bkg_agent_id > 0 || ($usersModel != '' && $usersModel->usr_email_verify == 1) || ($usersModel1 != '' && $usersModel1->usr_mobile_verify == 1))
				{
					$model->bkg_status = 2;
					if ($usersModel->usr_email_verify == 1)
					{
						$model->bkg_email_verified = 1;
					}
					if ($usersModel1->usr_mobile_verify == 1)
					{
						$model->bkg_phone_verified = 1;
					}
				}
			}

			$model->bkg_booking_id		 = 'temp';
			$model->bkg_gozo_base_amount = $_POST['Booking']['bkg_gozo_base_amount'];
			//  $splRemark = 'Carrier Requested for Rs.150';
			//  if ($model->bkg_spl_req_carrier == 1 && !strstr($model->bkg_additional_charge_remark, $splRemark)) {
			//        $model->bkg_additional_charge = $model->bkg_additional_charge + 150;
			//        $model->bkg_additional_charge_remark = ($model->bkg_additional_charge_remark == '') ? $splRemark : $model->bkg_additional_charge_remark . ', ' . $splRemark;
			// }

			if ($model->bkg_agent_id > 0)
			{
				$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
				if (Yii::app()->request->getParam('Booking')['agentBkgAmountPay'] == 2 && ($agentsModel->agt_type == 0 || $agentsModel->agt_type == 2))
				{
					$model->bkg_corporate_remunerator = 2;
				}
			}
			$model->populateAmount(true,false,true,true,$model->bkg_agent_id);
			if ($model->bkg_agent_id != '')
			{
				$agtModel	 = Agents::model()->findByPk($model->bkg_agent_id);
				$cityfortax	 = $agtModel->agt_city;
			}
			else
			{
				$cityfortax = $model->bkg_from_city_id;
			}
			if ($cityfortax == 30706)
			{
				$model->bkg_cgst = Yii::app()->params['cgst'];
				$model->bkg_sgst = Yii::app()->params['sgst'];
				$model->bkg_igst = 0;
			}
			else
			{
				$model->bkg_igst = Yii::app()->params['igst'];
				$model->bkg_cgst = 0;
				$model->bkg_sgst = 0;
			}
			if ($model->bkg_vehicle_type_id == '')
			{
				$model->addError('bkg_vehicle_type_id', 'Select cab type');
			}

			$successValidate = $model->validate();
//			if ($model->bkg_agent_id > 0)
//			{
//				//check credit limit exceeded or not
//				$corpamount1		 = Yii::app()->request->getParam('Booking')['agentCreditAmount'] | 0;
//				$isRechargeAccount	 = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $corpamount1, '', 3, false);
//				if ($isRechargeAccount)
//				{
//					$model->addError('bkg_agent_ref_code', "Booking failed as partner credit limit exceeded.");
//				}
//				//check credit limit exceeded or not
//			}


			//if ($successValidate && !$isRechargeAccount)
			if ($successValidate)
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$model->bkg_booking_id = 'temp';
					$model->uploadAttachment();
					if ($model->save())
					{
						$msgArr = array(trim($model->bkg_instruction_to_driver_vendor), (($model->bkg_spl_req_carrier == 0 || $model->bkg_spl_req_carrier == '0') ? "" : "Carrier Requested for Rs.150"), (($model->bkg_spl_req_lunch_break_time != 0 || $model->bkg_spl_req_lunch_break_time != '0') ? "Customer has paid for " . $model->bkg_spl_req_lunch_break_time . " minutes journey break" : ""));
						//$msg = trim($model->bkg_instruction_to_driver_vendor);
						foreach ($msgArr as $msg)
						{
							if ($msg != '')
							{
								$userInfo	 = UserInfo::getInstance();
								$eventId	 = BookingLog::REMARKS_ADDED;
								$remark		 = "Additional Instruction to Vendor/Driver: " . $msg;
								BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);
							}
						}

						$model->setPaymentExpiryTime();
						$isRealtedBooking				 = $model->findRelatedBooking($model->bkg_id);
						$model->bkg_is_related_booking	 = ($isRealtedBooking) ? 1 : 0;
						$booking_id						 = Booking::model()->generateBookingid($model);
						$model->bkg_booking_id			 = $booking_id;
						if (!$model->save())
						{
							throw new Exception("Failed to generate Booking ID");
						}
					}
					else
					{
						throw new Exception("Failed to create booking");
					}

					if (Yii::app()->request->getParam('Booking')['bkg_agent_id'] > 0)
					{
						$model->bkg_agent_id = Yii::app()->request->getParam('Booking')['bkg_agent_id'];
					}
//                    if (Yii::app()->request->getParam('Booking')['corporate_id'] > 0) {
//                        $model->bkg_agent_id = Yii::app()->request->getParam('Booking')['corporate_id'];
//                    }
					if ($model->bkg_user_id > 0)
					{
						$userModel = Users::model()->findByPk($model->bkg_user_id);
					}
					else
					{
						$userModel = Users::model()->linkUserByEmail($this->bkg_id, Booking::Platform_Admin);
					}

					if ($model->bkg_agent_id > 0)
					{
						$agentUsersModel = AgentUsers::model()->find('agu_agent_id=:agent AND agu_role=1', ['agent' => $model->bkg_agent_id]);
						$userModel		 = Users::model()->findByPk($agentUsersModel->agu_user_id);
					}

					if ($userModel == '')
					{
						$userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_App);
					}

					if ($userModel)
					{
						$model->bkg_user_id = $userModel->user_id;
					}
					if ($model->save())
					{
						if ($model->bkg_agent_id > 0)
						{
							$corporateModel = Agents::model()->findByPk($model->bkg_agent_id, 'agt_type=1');
							if ($corporateModel != '')
							{
								$corpIns = "";
								foreach ($_REQUEST['corp_addt_details'] as $value)
								{
									if ($value == 1)
									{
										$corpIns .= ' Driver and Car details required at least 12 hours before the pickup, ';
									}
									if ($value == 2)
									{
										$corpIns .= ' Corporate booking – car must be new and clean inside and outside, ';
									}
									if ($value == 3)
									{
										$corpIns .= ' Corporate company require duty slips for all parking or toll payments, ';
									}
									if ($value == 4)
									{
										$corpIns .= ' Do not ask traveller for any cash. Contact Gozo for any issues, ';
									}
								}
								if ($model->bkg_instruction_to_driver_vendor != '')
								{
									$corpIns .= $model->bkg_instruction_to_driver_vendor . ", ";
								}
								if ($corpIns != '')
								{
									$model->bkg_instruction_to_driver_vendor = rtrim($corpIns, ', ');
								}

								if ($userModel != '')
								{
									$userModel->usr_corporate_id = $corporateModel->agt_id;
									$userModel->save();
								}
								$model->bkg_corporate_remunerator = 2;
								if (!$model->save())
								{
									// throw new Exception("Failed to link corporate");
								}
							}

							//notify details
							//booking pref
							$bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
							if ($bookingPref == '')
							{
								$bookingPref			 = new BookingPref();
								$bookingPref->bpr_bkg_id = $model->bkg_id;
							}

							$crpmodel							 = Yii::app()->request->getParam('Booking');
							$bookingPref->bkg_crp_name			 = $crpmodel['bkg_copybooking_name'];
							$bookingPref->bkg_crp_send_email	 = $crpmodel['bkg_copybooking_ismail'];
							$bookingPref->bkg_crp_send_sms		 = $crpmodel['bkg_copybooking_issms'];
							$bookingPref->bkg_crp_email			 = $crpmodel['bkg_copybooking_email'];
							$bookingPref->bkg_crp_phone			 = $crpmodel['bkg_copybooking_phone'];
							$bookingPref->bkg_crp_country_code	 = $crpmodel['bkg_copybooking_country'];
							$bookingPref->save();

							if ($_POST['agentnotifydata'] != '' && $_POST['agentnotifydata'] != null && $_POST['agentnotifydata'] != 'null')
							{
								$arrAgentNotifyOpt = $model->agentNotifyData;

								$arrEvents = AgentMessages::getEvents();
								foreach ($arrEvents as $key => $value)
								{
									$bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
									if ($bookingMessages == '')
									{
										$bookingMessages = new BookingMessages();
									}
									$bookingMessages->bkg_booking_id	 = $model->bkg_id;
									$bookingMessages->bkg_event_id		 = $key;
									$bookingMessages->bkg_agent_email	 = $arrAgentNotifyOpt['agt_agent_email'][$key];
									$bookingMessages->bkg_agent_sms		 = $arrAgentNotifyOpt['agt_agent_sms'][$key];
									$bookingMessages->bkg_agent_app		 = $arrAgentNotifyOpt['agt_agent_app'][$key];
									$bookingMessages->bkg_trvl_email	 = $arrAgentNotifyOpt['agt_trvl_email'][$key];
									$bookingMessages->bkg_trvl_sms		 = $arrAgentNotifyOpt['agt_trvl_sms'][$key];
									$bookingMessages->bkg_trvl_app		 = $arrAgentNotifyOpt['agt_trvl_app'][$key];
									$bookingMessages->bkg_rm_email		 = $arrAgentNotifyOpt['agt_rm_email'][$key];
									$bookingMessages->bkg_rm_sms		 = $arrAgentNotifyOpt['agt_rm_sms'][$key];
									$bookingMessages->bkg_rm_app		 = $arrAgentNotifyOpt['agt_rm_app'][$key];
									$bookingMessages->save();
								}
							}
							else
							{
								$arrEvents = AgentMessages::getEvents();
								foreach ($arrEvents as $key => $value)
								{
									$bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
									if ($bookingMessages == '')
									{
										$bookingMessages				 = new BookingMessages();
										$bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
										$bookingMessages->bkg_booking_id = $model->bkg_id;
										$bookingMessages->bkg_event_id	 = $key;
										$bookingMessages->save();
									}
								}
							}

							$corpamount = Yii::app()->request->getParam('Booking')['agentCreditAmount'] | 0; //Credit added by agent;
							if ($corporateModel->agt_type == 1)
							{
								$corpamount = $model->bkg_total_amount;
							}

							if ($corpamount > 0 && $model->bkg_agent_id > 0)
							{
								if ($model->bkg_status >= 1 && $model->bkg_status <= 7)
								{
									$isUpdateAdvance = $model->updateAdvance($corpamount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Coins added to booking by admin");
									if($isUpdateAdvance)
									{
										Booking::model()->confirm(true);
									}
								}
								$model->refresh();
								$model->calculateVendorAmount();
							}
						}
					}
					//  }

					$desc	 = "New booking created - $routeProcessed";
					$eventid = BookingLog::BOOKING_CREATED;
					if ($model->lead_id > 0)
					{
						$eventid						 = BookingLog::LEAD_CONVERTED_TO_BOOKING;
						$desc							 = "Lead Converted to Booking";
						$leadModel						 = BookingTemp::model()->findByPk($model->lead_id);
						$leadModel->bkg_ref_booking_id	 = $model->bkg_id;
						$leadModel->bkg_follow_up_status = 4;
						$leadModel->bkg_status			 = 13;

						$leadModel->bkg_follow_up_status = 13;
						$leadModel->save();
						$userInfo						 = UserInfo::getInstance();
						LeadLog::model()->createLog($model->lead_id, $desc, $userInfo);
					}


					if ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
					{
						$routesArr = json_decode($_POST['multicityjsondata']);
					}

					$i		 = 0;
					$brtCnt	 = sizeof($routesArr);
					if ($brtCnt > 1)
					{
						if ($routesArr[0]->pickup_address == '' && $model->bkg_pickup_address != '')
						{
							$routesArr[0]->pickup_address = $model->bkg_pickup_address;
						}
						if ($routesArr[$brtCnt - 1]->drop_address == '' && $model->bkg_drop_address != '')
						{
							$routesArr[$brtCnt - 1]->drop_address = $model->bkg_drop_address;
						}
					}


					do
					{
						$rModel							 = new BookingRoute();
						$rModel->brt_bkg_id				 = $model->bkg_id;
						$rModel->brt_from_city_id		 = $model->bkg_from_city_id;
						$rModel->brt_to_city_id			 = $model->bkg_to_city_id;
						$rModel->brt_from_location		 = $model->bkg_pickup_address;
						$rModel->brt_to_location		 = $model->bkg_drop_address;
						$rModel->brt_pickup_datetime	 = $model->bkg_pickup_date;
						$rModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
						$rModel->brt_pickup_date_time	 = date('h:i A', strtotime($model->bkg_pickup_date));
						$rModel->brt_trip_distance		 = $model->bkg_trip_distance;
						$rModel->brt_trip_duration		 = $model->bkg_trip_duration;
						$rModel->brt_from_pincode		 = $model->bkg_pickup_pincode;
						$rModel->brt_to_pincode			 = $model->bkg_drop_pincode;
						$rModel->brt_status				 = 2;
						if ($model->bkg_booking_type == 3 || $model->bkg_booking_type == 2)
						{
							$rModel->brt_from_city_id		 = $routesArr[$i]->pickup_city;
							$rModel->brt_to_city_id			 = $routesArr[$i]->drop_city;
							$rModel->brt_from_location		 = $routesArr[$i]->pickup_address;
							$rModel->brt_to_location		 = $routesArr[$i]->drop_address;
							$rModel->brt_pickup_datetime	 = $routesArr[$i]->date;
							$rModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($routesArr[$i]->date);
							$rModel->brt_pickup_date_time	 = date('h:i A', strtotime($routesArr[$i]->date));
							$rModel->brt_trip_distance		 = $routesArr[$i]->distance;
							$rModel->brt_trip_duration		 = $routesArr[$i]->duration;
							$rModel->brt_from_pincode		 = $routesArr[$i]->pickup_pin;
							$rModel->brt_to_pincode			 = $routesArr[$i]->drop_pin;
						}

						if ($rModel->validate())
						{
							if (!$rModel->save())
							{
								throw new Exception("Failed to Save Route Data");
							}
						}

						$i++;
					}
					while ($i < count($routesArr));



					if ($model->bkg_bcb_id > 0)
					{
						$bcbModel = $model->getBookingCabModel();
					}
					if ($bcbModel == '' || $bcbModel == null)
					{
						$bcbModel = new BookingCab();
					}
					$bcbModel->bcb_vendor_amount = $model->bkg_vendor_amount;
					$bcbModel->bcb_bkg_id1		 = $model->bkg_id;
					if (!$bcbModel->save())
					{
						throw new Exception("Failed to Save Route Data.");
					}
					$model->bkg_bcb_id = $bcbModel->bcb_id;
					BookingRoute::model()->linkAllBookingwithVendor($model->bkg_id, $bcbModel->bcb_id);

					$userInfo = UserInfo::getInstance();
					BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid);
					if (($model->bkg_agent_id == '' || $model->bkg_agent_id == null || $model->bkg_agent_id == 0) && $model->bkg_status == 1)
					{
						if ($model->bkg_remark != '')
						{
							$model->bkg_remark = $model->bkg_remark . " . Booking not confirmed due to unverified contact information";
						}
						else
						{
							$model->bkg_remark = "Booking not confirmed due to unverified contact information";
						}
					}

					if ($model->bkg_id != '' && $model->bkg_remark != '')
					{
						$remark							 = trim($model->bkg_remark);
						$userInfo						 = UserInfo::getInstance();
						$eventId						 = BookingLog::REMARKS_ADDED;
						$bkg_status						 = $model->bkg_status;
						$params							 = [];
						$params['blg_booking_status']	 = $bkg_status;
						$params['blg_remark_type']		 = '1';
						BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId, $oldModel, $params);
					}
					if ($model->bkg_promo_code != '' && $model->bkg_user_id > 0 && ($model->bkg_agent_id == '' || $model->bkg_agent_id == null || $model->bkg_agent_id == 0) && $model->bkg_status == 2)
					{
						$discount	 = Promotions::model()->getDiscount($model, trim($model->bkg_promo_code));
						$promoModel	 = Promos::model()->getByCode($model->bkg_promo_code);
						if ($discount > 0 && $promoModel->prm_activate_on != 1)
						{

							if ($promoModel->prm_type == 2 || $promoModel->prm_type == 3)
							{
								//  $model->bkg_discount_amount = 0;
								$creditModel1					 = new UserCredits();
								$creditModel1->ucr_user_id		 = $model->bkg_user_id;
								$creditModel1->ucr_value		 = $discount;
								$creditModel1->ucr_desc			 = 'CREDITS AGAINST PROMO';
								$creditModel1->ucr_type			 = 1;
								$creditModel1->ucr_maxuse_type	 = Yii::app()->params['creditMaxUseType']; //3;
								$creditModel1->ucr_status		 = 2;
								$creditModel1->ucr_validity		 = date('Y-m-d H:i:s', strtotime('+1 years'));
								$creditModel1->ucr_max_use		 = $creditModel1->ucr_value;
								$creditModel1->ucr_ref_id		 = $model->bkg_id;
								$creditModel1->save();
							}
						}

						$userInfo				 = UserInfo::getInstance();
						$eventid				 = BookingLog::BOOKING_PROMO;
						$params['blg_ref_id']	 = BookingLog::REF_PROMO_APPLIED;
						BookingLog::model()->createLog($model->bkg_id, "Promo '$model->bkg_promo_code' applied successfully.", $userInfo, $eventid, false, $params);
					}

					$logType = UserInfo::TYPE_SYSTEM;
					$isCod	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 3);

					//  if (Yii::app()->request->getParam('Booking')['bkg_trvl_sendupdate'] == 1) {
					//      $model->bkg_send_email = Yii::app()->request->getParam('Booking')['bkg_send_email'];
					//      $model->bkg_send_sms = Yii::app()->request->getParam('Booking')['bkg_send_sms'];
					//  }
					//  if (Yii::app()->request->getParam('Booking')['bkg_trvl_sendupdate'] == 2) {
					$model->bkg_send_email	 = 1;
					$model->bkg_send_sms	 = 1;
					$model->bkg_admin_id	 = Yii::app()->user->getId();
					if (!$model->save())
					{
						throw new Exception("Failed to create booking");
					}

					//    }
					if ($model->bkg_agent_id > 0 || (($model->bkg_agent_id == '' || $model->bkg_agent_id == null || $model->bkg_agent_id == 0) && $model->bkg_status == 2))
					{
						if (!$model->confirmBooking($logType, $isCod))
						{
							throw new Exception("Failed to create booking");
						}
					}
					$model->bkgTrack = BookingTrack::model()->sendTripOtp($model->bkg_id, $sendOtp = false);
					if($model->bkgTrack!=''){
						$model->bkgTrack->save();
					}
					$transaction->commit();
					if ($model->bkg_agent_id > 0)
					{
						$emailCom	 = new emailWrapper();
						$emailCom->gotBookingemail($model->bkg_id, UserInfo::TYPE_SYSTEM, $model->bkg_agent_id);
						$emailCom->gotBookingAgentUser($model->bkg_id);
						$msgCom		 = new smsWrapper();
						$msgCom->gotBooking($model, UserInfo::TYPE_SYSTEM);
					}
					else if (($model->bkg_agent_id == '' || $model->bkg_agent_id == null || $model->bkg_agent_id == 0) && $model->bkg_status == 2)
					{
						$model->sendConfirmation($logType);
					}

					$success = true;
					if (Yii::app()->request->isAjaxRequest)
					{
						$url	 = Yii::app()->createUrl('rcsr/booking/view', ['id' => $model->bkg_id]);
						$data	 = ['success' => $success, 'message' => 'Booking Created Successfully. Booking ID : ' . $model->bkg_booking_id, 'url' => $url];

						echo json_encode($data);
						Yii::app()->end();
					}
				}
				catch (Exception $e)
				{
					echo print_r($model);
					$model->addError("bkg_id", $e->getMessage());
					$transaction->rollback();
				}
			}
			else
			{
				$model->attributes = Yii::app()->request->getParam('Booking');
				if ($model->bkg_booking_type == '2' && $model->bkg_return_date_date != "" && $model->bkg_return_date_time != "")
				{
					$date1					 = DateTimeFormat::DatePickerToDate($model->bkg_return_date_date);
					$time1					 = date('H:i:00', strtotime($model->bkg_return_date_time));
					$model->bkg_return_date	 = $date1 . ' ' . $time1;
					$model->bkg_return_time	 = $time1;
				}

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


			$model->preData = json_decode($_POST['multicityjsondata']);
		}

		$this->render('create', array('model' => $model));
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

	public function actionGetcabdetails()
	{
		$vhcid			 = Yii::app()->request->getParam('vhcid'); //$_GET['drvid'];
		$car_mark_bad	 = Vehicles::model()->checkVehicleMarkCount($vhcid);
		echo CJSON::encode(array('carMarkBad' => $car_mark_bad));
	}

	public function actionView()
	{
		$bookingID	 = Yii::app()->request->getParam('id');
		$view		 = Yii::app()->request->getParam('view', 'view');
		if ($bookingID != '')
		{
			$bookModel				 = Booking::model()->findByPk($bookingID);
			$oldModel				 = clone $bookModel;
			$userInfo				 = UserInfo::getInstance();
			$desc					 = 'Booking Viewed';
			$params['blg_active']	 = 2;
			$eventId				 = BookingLog::BOOKING_VIEWED;
			BookingLog::model()->createLog($bookingID, $desc, $userInfo, $eventId, $oldModel, $params);
		}
		$models				 = Booking::model()->getBookingRelationalDetails($bookingID);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ($outputJs ? "Partial" : "");
		$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bookingID]);
		//$bkgPref			 = BookingPref::model()->getByBooking($bookingID);
        $bkgTrack			 = BookingTrack::model()->find('btk_bkg_id=:bkg_id', ['bkg_id' => $bookingID]);
		$this->$method($view, array('model' => $models, 'bookingRouteModel' => $bookingRouteModel, 'bkgTrack' => $bkgTrack, 'isAjax' => $outputJs), false, $outputJs);
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

		$fcity			 = Yii::app()->request->getParam('fromCity');
		$tcity			 = Yii::app()->request->getParam('toCity');
		$cabtypeid		 = Yii::app()->request->getParam('cabType');
		$distance		 = Yii::app()->request->getParam('tripDistance');
		$triptype		 = Yii::app()->request->getParam('tripType');
		$multijsondata	 = Yii::app()->request->getParam('multiCityData');
		$booking_type	 = Yii::app()->request->getParam('bookingType', 1);
		$bkg_id			 = Yii::app()->request->getParam('id');
		$pickLocation	 = Yii::app()->request->getParam('pickupAddress');
		$dropLocation	 = Yii::app()->request->getParam('dropupAddress');
		$pickup_date	 = Yii::app()->request->getParam('pickupDate');
		$pickup_time	 = Yii::app()->request->getParam('pickupTime');

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
		$cabtypeid			 = VehicleTypes::model()->resetScope()->findByPk($cabtypeid)->vht_car_type;
		$partnerId			 = Yii::app()->request->getParam('agentId');
		$bookingCPId		 = ($partnerId > 0) ? $partnerId : Yii::app()->params['gozoChannelPartnerId'];
		$quote				 = new Quote();
		$quote->routes		 = $routesArr;
		$quote->tripType	 = $booking_type;
		$quote->partnerId	 = $bookingCPId;
		$quote->quoteDate	 = date("Y-m-d H:i:s");
		$quote->pickupDate	 = $routesArr[0]->brt_pickup_datetime;
		$quote->returnDate	 = $routesArr[count($routesArr) - 1]->brt_pickup_datetime;
		$quote->setCabTypeArr(Quote::Platform_Admin);
		$qt					 = $quote->getQuote($cabtypeid);
		foreach ($qt as $k => $v)
		{
			if ($k > 0)
			{
				$quoteData = $qt[$k];
			}
		}
		$routeRates		 = $quoteData->routeRates;
		$routeDistance	 = $quoteData->routeDistance;
		$routeDuration	 = $quoteData->routeDuration;
		// $arr               = Quotation::model()->getQuote($routesArr, $booking_type, $bookingCPId, $cabtypeid);
		$distArr		 = [];
		foreach ($routesArr as $k => $v)
		{
			$distArr[$k]['dist'] = $v->brt_trip_distance;
			$distArr[$k]['dura'] = $v->brt_trip_duration;
		}
//        $bmodel                              = new Booking();
//        $bmodel->bkg_base_amount             = $routeRates->baseAmount;
//        $bmodel->bkg_driver_allowance_amount = $routeRates->driverAllowance;
//        $bmodel->bkg_toll_tax                = $routeRates->tollTaxAmount | 0;
//        $bmodel->bkg_state_tax               = $routeRates->stateTax | 0;
//        $bmodel->calculateTotal();

		$amount				 = $routeRates->totalAmount;
		$tax				 = $routeRates->gst;
		$arr['routeData']	 = ['totalGarage' => $routeDistance->totalGarage, 'quoted_km' => $routeDistance->tripDistance];
		if (count($arr) > 0)
		{
			$success = true;
		}
		$processedRoute = BookingLog::model()->logRouteProcessed($quoteData);
//        $processedTripTypeId = $quoteData->processedTripType;
//        $processedTripType   = trim(Booking::model()->getBookingType($processedTripTypeId));
//        $servingRoute        = $quoteData->servingRoute;
//        $startRoute          = Cities::model()->findByPk($servingRoute['start'])->cty_name;
//        $endRoute            = Cities::model()->findByPk($servingRoute['end'])->cty_name;
//        $getroute            = $quoteData->routeDistance->routeDesc;
//        $routeFollowed       = trim(trim($startRoute . " - " . implode(' - ', $getroute) . " - " . $endRoute), '-');
//        $processedRoute      = "$routeFollowed  ($processedTripType)";


		$data = $params + [
			'processedRoute' => $processedRoute,
			'cartypeid'		 => $cabtypeid,
			'quoteddata'	 => $quoteData,
			'distArr'		 => $distArr,
			'routeData'		 => $arr['routeData'],
			'type'			 => $type,
			'rate'			 => $rate,
			'amount'		 => $amount,
			'tax'			 => $tax];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

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

					$Arrmulticity[$key] = ["pickup_city"	 => $value->brt_from_city_id, "drop_city"		 => $value->brt_to_city_id,
						"pickup_address" => $value->brt_from_location, "drop_address"	 => $value->brt_to_location, "date"			 => $value->brt_pickup_datetime];
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
		$cabtypeid	 = VehicleTypes::model()->resetScope()->findByPk($cabtypeid)->vht_car_type;
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
//$booking_id = trim(Yii::app()->request->getParam('booking_id'));
		$qry				 = [];
		$qry['booking_id']	 = trim(Yii::app()->request->getParam('booking_id'));
		/* var $model BookingLog */
		$model				 = new BookingLog();
		$model->blg_event_id = '';
		if (isset($_REQUEST['BookingLog']))
		{
			$model->attributes = Yii::app()->request->getParam('BookingLog');
		}
		$dataProvider = $model->getByBookingId($qry['booking_id']);

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");


		$this->renderPartial('showlog', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry), false, $outputJs);
	}

	public function actionGetcontacts()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$model	 = Booking::model()->findByPk($bkgId);
		$result	 = ['success' => false];
		if ($model)
		{
			$result ['email']	 = ($model->bkg_user_email != '') ? $model->bkg_user_email : " ";
			$result ['phone']	 = ($model->bkg_contact_no != '') ? '+' . $model->bkg_country_code . '-' . $model->bkg_contact_no : " ";
			$result ['altPhone'] = ($model->bkg_alt_contact_no != '') ? '+' . $model->bkg_alt_country_code . '-' . $model->bkg_alt_contact_no : " ";

			$oldModel				 = clone $model;
			$userInfo				 = UserInfo::getInstance();
			$desc					 = 'Contact Access Requested';
			$params['blg_active']	 = 2;
			$eventId				 = BookingLog::BOOKING_VIEWED;
			$params['blg_ref_id']	 = 1;
			BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);

			$result['success'] = true;
		}

		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionCopybooking($bkid = 0)
	{
		if ($bkid > 0)
		{
			$oldmodel	 = Booking::model()->findbyPk($bkid);
			$newmodel	 = new Booking();
			$data		 = $oldmodel->attributes;
			$data1		 = $newmodel->attributes;
			foreach ($data as $attr => $val)
			{
				if ($val == null || $val == '' || $attr == 'bkg_id' || $attr == 'bkg_status' ||
						$attr == 'bkg_rating' || $attr == 'bkg_modified_on' || $attr == 'bkg_advance_amount' || $attr == 'bkg_due_amount' || $attr == 'bkg_bcb_id' || $attr == 'bkg_discount_amount' || $attr == 'bkg_promo_code' || $attr == 'bkg_user_id')
				{
					unset($data[$attr]);
					unset($data1[$attr]);
				}
				else
				{
					$newmodel->setAttribute($attr, $val);
				}
			}
			$newmodel->bkg_pickup_date_date	 = date('d/m/Y', strtotime($oldmodel->bkg_pickup_date));
			$newmodel->bkg_pickup_date_time	 = date('h:i A', strtotime($oldmodel->bkg_pickup_date));
			//  $newmodel->bkg_discount_amount = ($newmodel->bkg_discount_amount > 0) ? $newmodel->bkg_discount_amount : 0;
			$newmodel->bkg_additional_charge = ($newmodel->bkg_additional_charge > 0) ? $newmodel->bkg_additional_charge : 0;
//            if ($newmodel->bkg_total_amount == '') {
//                $newmodel->bkg_total_amount = $newmodel->bkg_base_amount - $newmodel->bkg_discount_amount + $newmodel->bkg_additional_charge + $newmodel->bkg_service_tax;
//            }
			$newmodel->populateAmount(true,false,true,true,$newmodel->bkg_agent_id);


			$Arrmulticity	 = [];
			$routeModel		 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bkid]);
			if ($newmodel->bkg_booking_type != 1)
			{
				foreach ($routeModel as $key => $value)
				{
					$Arrmulticity[$key] = ["pickup_city"		 => $value->brt_from_city_id, "drop_city"			 => $value->brt_to_city_id,
						"pickup_city_name"	 => Cities::getName($value->brt_from_city_id), "drop_city_name"	 => Cities::getName($value->brt_to_city_id),
						"pickup_date"		 => DateTimeFormat::DateTimeToDatePicker($value->brt_pickup_datetime), "pickup_time"		 => DateTimeFormat::DateTimeToTimePicker($value->brt_pickup_datetime),
						"date"				 => $value->brt_pickup_datetime, "duration"			 => $value->brt_trip_duration, "distance"			 => $value->brt_trip_distance, "pickup_pin"		 => $value->brt_from_pincode, "drop_pin"			 => $value->brt_to_pincode,
						"pickup_address"	 => $value->brt_from_location, "drop_address"		 => $value->brt_to_location, "date"				 => $value->brt_pickup_datetime];
				}
				$multijsondata		 = json_encode($Arrmulticity);
				$arrjsondata		 = json_decode($multijsondata);
				$newmodel->preData	 = $arrjsondata;
			}
			return $newmodel;
		}
	}

	public function actionLeadtoBooking($bkid = 0)
	{
		if ($bkid > 0)
		{
			$tmpmodel = BookingTemp::model()->findbyPk($bkid);
			if ($tmpmodel->bkg_ref_booking_id > 0)
			{
				$bkgmodel = Booking::model()->findbyPk($tmpmodel->bkg_ref_booking_id);
			}
			if (!$bkgmodel)
			{
				$bkgmodel = new Booking();
			}
			$data = $tmpmodel->attributes;

			$data1 = $bkgmodel->attributes;
			foreach ($data as $attr => $val)
			{
				if ($val == null || $val == '' || $attr == 'bkg_id' || $attr == 'bkg_status' || $attr == 'bkg_modified_on' || $attr == 'bkg_create_date')
				{
					unset($data[$attr]);
					unset($data1[$attr]);
				}
				else
				{
					$bkgmodel->setAttribute($attr, $val);
				}
			}

			if ($tmpmodel->bkg_ref_booking_id != '')
			{
				$bkgmodel->bkg_id = $tmpmodel->bkg_ref_booking_id;
			}
			$bkgmodel->bkg_gozo_base_amount	 = $tmpmodel->bkg_net_charge;
			$bkgmodel->bkg_total_amount		 = $tmpmodel->bkg_amount;
			$tmpmodel->bkg_discount_amount	 = $tmpmodel->bkg_discount;


			if ($tmpmodel->bkg_contact_no == '' && $tmpmodel->bkg_log_phone != '')
			{
				$bkgmodel->bkg_contact_no = preg_replace('/[^0-9\-]/', '', str_replace(' ', '', $tmpmodel->bkg_log_phone));
			}
			if ($tmpmodel->bkg_user_email == '' && $tmpmodel->bkg_log_email != '')
			{
				$bkgmodel->bkg_user_email = $tmpmodel->bkg_log_email;
			}
			$bkgmodel->bkg_status			 = Booking::STATUS_VERIFY;
			$bkgmodel->bkg_pickup_date_date	 = date('d/m/Y', strtotime($tmpmodel->bkg_pickup_date));

			$bkgmodel->bkg_pickup_date_time	 = date('h:i A', strtotime($tmpmodel->bkg_pickup_date));
			$route_data						 = json_decode($tmpmodel->bkg_route_data, true);
			if ($route_data != '')
			{
				foreach ($route_data as $k => $v)
				{
					$bookingRoute				 = new BookingRoute();
					$bookingRoute->attributes	 = $v;
					$bookingRoute->brt_bkg_id	 = $bkgmodel->bkg_id;
					$bookingRoutes[]			 = $bookingRoute;
				}
				$bkgmodel->bookingRoutes	 = $bookingRoutes;
				$bkgmodel->bookingRouteData	 = $bookingRoutes;
			}

//            $bkgmodel->bkg_discount_amount = ($tmpmodel->bkg_discount_amount > 0) ? $tmpmodel->bkg_discount_amount : 0;
//
//
//            //$bkgmodel->bkg_base_amount = $tmpmodel->bkg_total_amount + $tmpmodel->bkg_discount_amount;
//            $rateModel = Rate::model()->getRatebyCitiesnVehicletype($bkgmodel->bkg_from_city_id, $bkgmodel->bkg_to_city_id, $bkgmodel->bkg_vehicle_type_id);
//            $bkgmodel->bkg_total_amount = $rateModel->rte_amount;
//            $bkgmodel->bkg_vendor_amount = '';
//            $bkgmodel->reverseCalculate();

			$bkgmodel->addCorporateCredit();
			$bkgmodel->calculateConvenienceFee($bkgmodel->bkg_convenience_charge);
			$bkgmodel->calculateTotal();
			$bkgmodel->calculateVendorAmount();

			$bkgmodel->lead_id = $tmpmodel->bkg_id;


			return $bkgmodel;
		}
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
		$bkid		 = Yii::app()->request->getParam('bkg');
		$invoiceList = Booking::model()->getInvoiceByBooking($bkid);
		$strRoute	 = BookingRoute::model()->getRouteName($bkid);
////        /* @var $html2pdf \Spipu\Html2Pdf\Html2Pdf */
//        $html2pdf = Yii::app()->ePdf->mPdf();
//        $html2pdf->writeHTML($this->renderPartial('receipt', array('invoiceList' => $invoiceList), true));
//        $html2pdf->Output();
		$this->renderPartial('receipt', array('invoiceList' => $invoiceList, 'strRoute' => $strRoute), false, true);
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
		$bkid		 = Yii::app()->request->getParam('lead_id');
		$leadmodel	 = BookingTemp::model()->findByPk($bkid);
		if ($leadmodel->bkg_ref_booking_id > 0)
		{
			$this->actionEdit();
		}
		else
		{
			$this->actionCreate();
		}
	}

	public function actionSendpaymentlink()
	{
		$bkgid		 = Yii::app()->request->getParam('bkid');
		$model		 = Booking::model()->findByPk($bkgid);
		$model->sendPaymentinfo();
//log
		$userInfo	 = UserInfo::getInstance();
		$desc		 = "Payment link sent (by admin)";
		$eventid	 = BookingLog::PAYMENT_LINK_SENT_MANUALLY;
		BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $model);
	}

	public function actionSendconfirmation()
	{
		$bkgid		 = Yii::app()->request->getParam('bkid');
		$model		 = Booking::model()->findByPk($bkgid);
		$logType	 = UserInfo::TYPE_ADMIN;
		$userInfo	 = UserInfo::getInstance();
		$emailCom	 = new emailWrapper();
		$resend		 = 1;
		if ($model->bkg_contact_no != '' && $model->bkg_send_sms == 1)
		{
			$msgCom = new smsWrapper();
			$msgCom->gotBooking($model, $logType);
		}
		if ($model->bkg_user_email != '' && $model->bkg_send_email == 1 && $model->bkg_advance_amount == 0)
		{
			$emailCom->gotBookingemail($model->bkg_id, $logType);
		}
		else if ($model->bkg_user_email != '' && $model->bkg_send_email == 1 && $model->bkg_advance_amount > 0)
		{
			emailWrapper::confirmBooking($model->bkg_id, $logType, $resend);
		}

		$desc		 = "Confirmation mail/SMS sent manually by admin";
		$eventid	 = BookingLog::CONFIRMATION_MAIL_SMS_SENT_MANUALLY;
		BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $model);
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

	public function actionUploads()
	{
		$this->pageTitle = 'aaocab - Booking Uploads';
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
			$desc		 = "Payment option locked by admin";
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
			$desc		 = "Payment expiry time updated by admin";
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
		$arrData['error']				 = $arr['error'];
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
		$adminid						 = Yii::app()->request->getParam('csrid');
		$bkid							 = Yii::app()->request->getParam('bkid');
		$bookingmodel					 = Booking::model()->findByPk($bkid);
		$bookingmodel->bkg_assign_csr	 = $adminid;
		$bookingmodel->save();
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

	public function actionModifyvendoramount()
	{
		$this->pageTitle = "Vendor Amount";
		$bid			 = Yii::app()->request->getParam('booking_id');
		$model			 = Booking::model()->findByPk($bid);
		$cabmodel		 = $model->getBookingCabModel();
		if (isset($_REQUEST['BookingCab']))
		{
			$arr								 = Yii::app()->request->getParam('BookingCab');
			$modelBookingCab					 = BookingCab::model()->findByPk($arr['bcb_id']);
			$modelBookingCab->bcb_vendor_amount	 = $arr['bcb_vendor_amount'];
			$success							 = $modelBookingCab->save();
		}
		if ($success)
		{
			$this->redirect(array('list'));
			Yii::app()->user->setFlash('success', 'Vendor details updated successfully');
		}
		$this->renderPartial('modifyvendoramount', array('model' => $model, 'cabmodel' => $cabmodel));
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
		if ($date1 == "" && $date2 == "")
		{
			$date2	 = date('d/m/Y');
			$date1	 = DateTimeFormat::DatePickerToDate($date1);
			$date2	 = DateTimeFormat::DatePickerToDate($date2);
		}

		/* @var $submodel BookingSub */
		$submodel		 = new BookingSub();
		$dataProvider	 = $submodel->getCancellationList($date1, $date2);
		$this->render('report_cancellation', array('dataProvider'	 => $dataProvider,
			'model'			 => $model));
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
			if ($model->bkg_phone_verified == 1 || $model->bkg_email_verified == 1)
			{
				if ($model->bkg_status == 1)
				{
					$this->confirmbooking($bkgid);
				}
				$url = Yii::app()->createUrl('rcsr/booking/view', ['id' => $bkgid]);
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
					$model->sendVerificationCode(10, true);
				}
			}
		}
		$this->renderPartial('verify', array('model' => $model, 'smsExceed' => $smsExceed), false, true);
	}

	public function confirmbooking($bkgid1, $vcode1 = '', $vcode = '')
	{
		$bmodel			 = Booking::model()->findbyPk($bkgid1);
		$oldModel		 = clone $bmodel;
		$verifyresult	 = false;
		if ($bmodel->bkg_verification_code == $vcode && $vcode != '' && $bmodel->bkg_verification_code != '')
		{
			$bmodel->bkg_phone_verified		 = 1;
			$bmodel->bkg_verification_code	 = '';
		}
		if ($bmodel->bkg_verifycode_email == $vcode1 && $vcode1 != '' && $bmodel->bkg_verifycode_email != '')
		{
			$bmodel->bkg_email_verified		 = 1;
			$bmodel->bkg_verifycode_email	 = '';
		}

		if ($bmodel->bkg_phone_verified == 1 || $bmodel->bkg_email_verified == 1)
		{
			$logType = UserInfo::TYPE_SYSTEM;
			$isCod	 = BookingSub::model()->getApplicable($bmodel->bkg_from_city_id, $bmodel->bkg_to_city_id, 3);
			$bmodel->save();
			if (!$bmodel->confirmBooking($logType, $isCod))
			{
				throw new Exception("Failed to create booking");
			}
			$bmodel->bkgTrack = BookingTrack::model()->sendTripOtp($bmodel->bkg_id, $sendOtp = false);
			if($bmodel->bkgTrack!=''){
				$bmodel->bkgTrack->save();
			}
			$bmodel->sendConfirmation($logType);
			if ($bmodel->bkg_promo_code != '' && $bmodel->bkg_user_id > 0)
			{
				$discount = Promotions::model()->getDiscount($bmodel, trim($bmodel->bkg_promo_code));
				if ($discount > 0)
				{
					$promoModel = Promos::model()->getByCode($bmodel->bkg_promo_code);
					if (($promoModel->prm_type == 2 || $promoModel->prm_type==3) && $promoModel->prm_activate_on != 1)
					{
						$creditModel1 = UserCredits::model()->find('ucr_type=1 AND ucr_ref_id=:bkgId AND ucr_status=2 AND ucr_user_id=:user', ['bkgId' => $bmodel->bkg_id, 'user' => $bmodel->bkg_user_id]);
						if ($creditModel1 == '' || $creditModel1 == null)
						{
							$creditModel1 = new UserCredits();
						}
						$creditModel1->ucr_user_id		 = $bmodel->bkg_user_id;
						$creditModel1->ucr_value		 = $discount;
						$creditModel1->ucr_desc			 = 'CREDITS AGAINST PROMO';
						$creditModel1->ucr_type			 = 1;
						$creditModel1->ucr_maxuse_type	 = Yii::app()->params['creditMaxUseType']; //3;
						$creditModel1->ucr_status		 = 2;
						$creditModel1->ucr_max_use		 = $creditModel1->ucr_value;
						$creditModel1->ucr_validity		 = date('Y-m-d H:i:s', strtotime('+1 years'));
						$creditModel1->ucr_ref_id		 = $bmodel->bkg_id;
						$creditModel1->save();
					}
				}
			}
			$desc			 = "Booking verified manually.";
			$eventId		 = BookingLog::BOOKING_VERIFIED;
			$userInfo		 = UserInfo::getInstance();
			BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventId, $oldModel);
			$verifyresult	 = true;
			if ($bmodel->bkg_email_verified == 1)
			{
				/* @var $bmodel booking */
				$usersModel = Users::model()->findAll('usr_email=:email', ['email' => $bmodel->bkg_user_email]);
				foreach ($usersModel as $user)
				{
					if ($user != '' && $user->usr_email_verify != 1)
					{
						$user->usr_email_verify = 1;
						$user->save();
					}
				}
			}
			if ($bmodel->bkg_phone_verified == 1)
			{
				/* @var $bmodel booking */
				$usersModel = Users::model()->findAll('usr_mobile=:phone', ['phone' => $bmodel->bkg_contact_no]);
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
		return $verifyresult;
	}

	public function actionCheckcabtimeoverlap()
	{
		$bcbid	 = Yii::app()->request->getParam('bcbid');
		$cabid	 = Yii::app()->request->getParam('cabid');
		$model	 = BookingCab::model()->findByPk($bcbid);

		$bmodels	 = $model->bookings;
		$pickupTime	 = $bmodels[0]->bkg_pickup_date;
		$dropTime	 = date('Y-m-d H:i:s', strtotime($bmodels[0]->bkg_trip_duration . ' minutes', strtotime($pickupTime)));

		foreach ($bmodels as $bmodel)
		{
			$pickupTime	 = ($pickupTime < $bmodel->bkg_pickup_date) ? $pickupTime : $bmodel->bkg_pickup_date;
			$dropTimeVal = date('Y-m-d H:i:s', strtotime($bmodel->bkg_trip_duration . ' minutes', strtotime($pickupTime)));
			$dropTime	 = ($dropTime > $dropTimeVal) ? $pickupTime : $dropTimeVal;
		}
		$bcbModel				 = BookingCab::model()->findByPk($bcbid);
		$bcbModel->bcb_cab_id	 = $cabid;
		$overlapTrips			 = $bcbModel->checkCabActiveTripTiming();

		$data = ['success' => true, 'overlapTrips' => $overlapTrips];
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

}
